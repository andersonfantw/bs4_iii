<?php
#HttpClient, from http://scripts.incutio.com/httpclient
require_once dirname(__FILE__).'/../libs/HttpClient.class.php';

class Nas
{
    var $nasPort;
    var $authSid;
    var $adminSid;

    var $domaintype = 0;

    #Constructor
    public function __construct()
    {
        #Get port number
        $file_handle = fopen("/etc/nasPort", "r");
        if($file_handle)
        {
            $this->nasPort = trim(fgets($file_handle));
            fclose($file_handle);
        }
        else
        {
            $this->nasPort = "8080"; #default value, 8080
        }

        #Get authSid
        $_ecocatCMSnasAuthId = common::getcookie('ecocatCMSnasAuthId');
        if(array_key_exists("ecocatCMSnasAuthId", $_COOKIE) && $_ecocatCMSnasAuthId != "")
        {
            $this->authSid = $_ecocatCMSnasAuthId;
        }
        else
        {
            $this->authSid = "";
        }

        #Get adminSid
        $_ecocatCMSnasAdminAuthId = common::getcookie('ecocatCMSnasAdminAuthId');
        if(array_key_exists("ecocatCMSnasAdminAuthId", $_COOKIE) && $_ecocatCMSnasAdminAuthId != "")
        {
            $this->adminSid = $_ecocatCMSnasAdminAuthId;
        }
        else
        {
            $this->adminSid = "";
        }
    }

    #Login
    public function login($username, $password)
    {
        //Login by user
        $pageContents = HttpClient::quickPost(
            "http://127.0.0.1:".$this->nasPort."/cgi-bin/authLogin.cgi",
            array(
                "user" => $username,
                "pwd" => base64_encode($password)
            )
        );

        preg_match('/<authSid>.*<\/authSid>/i', $pageContents, $this->authSid);
        if(count($this->authSid) == 1)
            $this->authSid = substr($this->authSid[0], 18, 8);
        else
            return "";

        sleep(1);

        //Login by sysadmin(for getting all privilege on NAS)
        $pageContents = HttpClient::quickPost(
            "http://127.0.0.1:".$this->nasPort."/cgi-bin/authLogin.cgi",
            array(
                "user" => "sysadmin",
                "pwd" => "amkzZzRhdTRhODM="
            )
        );

        preg_match('/<authSid>.*<\/authSid>/i', $pageContents, $this->adminSid);
        if(count($this->adminSid) == 1)
            $this->adminSid = substr($this->adminSid[0], 18, 8);
        else
            return "";

        setcookie('ecocatCMSnasAuthId', $this->authSid);
        setcookie("ecocatCMSnasAdminAuthId", $this->adminSid);
        return $this->authSid;
    }

    #Get current user profile
    public function getProfile($authSid="")
    {
        $profile = array (
            "account" => "",
            "username" => "",
            "groupname" => ""
        );

        if(empty($authSid))
            $authSid = $this->authSid;

        $pageContents = HttpClient::quickPost(
            "http://127.0.0.1:".$this->nasPort."/cgi-bin/authLogin.cgi",
            array(
                "sid" => $authSid
            )
        );

        #Get account
        preg_match('/<user>.*<\/user>/i', $pageContents, $regResult);
        if(!empty($regResult))
        {
            #<user><![CDATA[account]]></user>
            $profile["account"] = substr($regResult[0], 15, strlen($regResult[0])-25);
        }

        #Get username
        preg_match('/<username>.*<\/username>/i', $pageContents, $regResult);
        if(!empty($regResult))
        {
            #<username><![CDATA[username]]></username>
            $profile["username"] = substr($regResult[0], 19, strlen($regResult[0])-33);
        }

        #Get groupname
        preg_match('/<groupname>.*<\/groupname>/i', $pageContents, $regResult);
        if(!empty($regResult))
        {
            #<groupname><![CDATA[groupname]]></groupname>
            $profile["groupname"] = substr($regResult[0], 20, strlen($regResult[0])-35);
        }

        #Fixes: username isn't correct
        $nasUsers = $this->listUsers($authSid="", $lower="0", $upper="all", $filter=$profile["account"]);
        foreach ($nasUsers[0] as $key => $value) {
            if($value["username"] == $profile["account"]) {
                if($value["fullname"] == "") {
                    $profile["username"] = $value["username"];
                }
                else {
                    $profile["username"] = $value["fullname"];
                }
                break;
            }
        }

        #Fixes: groupname isn't correct
        $nasGroups = $this->listUserGroups($authSid="", $username=$profile["account"], $lower="0", $upper="all");
        $profile["groupname"] = $nasGroups[0];

        return $profile;
    }

    #List users on NAS (0 <= users < upper)
    public function listUsers($authSid="", $lower="0", $upper="all", $filter="")
    {
        $userList = Array();

        if(empty($authSid))
            $authSid = $this->adminSid;

        $requestUrl = sprintf(
            "http://127.0.0.1:%s/cgi-bin/priv/privRequest.cgi?subfunc=user&getdata=1&type=%s&refresh=0&filter=%s&lower=%s&upper=%s&sort=13&sid=%s",
            $this->nasPort,
            $this->domaintype,
            urlencode($filter),
            $lower,
            $upper,
            $authSid
        );
        $pageContents = HttpClient::quickGet($requestUrl);

        $xmlObject = simplexml_load_string($pageContents, "SimpleXMLElement", LIBXML_NOCDATA);
        foreach($xmlObject->userroot->data->user as $userElement)
        {
            $userList[] = Array(
                "username" => (string)$userElement->username,
                "fullname" => ((string)$userElement->fullname == "") ? (string)$userElement->username : (string)$userElement->fullname,
                "email" => (string)$userElement->email,
                "description" => (string)$userElement->description
            );
        }

        return Array($userList, (int)$xmlObject->userroot->count);
    }

    #List groups on NAS (0 <= groups < upper)
    public function listGroups($authSid="", $lower="0", $upper="all", $filter="")
    {
        $groupList = Array();

        if(empty($authSid))
            $authSid = $this->adminSid;

        $requestUrl = sprintf(
            "http://127.0.0.1:%s/cgi-bin/priv/privRequest.cgi?subfunc=group&getdata=1&type=%s&refresh=0&filter=%s&lower=%s&upper=%s&sort=13&sid=%s",
            $this->nasPort,
            $this->domaintype,
            $filter,
            $lower,
            $upper,
            $authSid
        );
        $pageContents = HttpClient::quickGet($requestUrl);

        $xmlObject = simplexml_load_string($pageContents, "SimpleXMLElement", LIBXML_NOCDATA);
        foreach($xmlObject->grouproot->data->group as $groupElement)
            $groupList[] = (string)$groupElement->groupname;

        return Array($groupList, (int)$xmlObject->grouproot->count);
    }

    #List group's users
    public function listGroupUsers($authSid="", $groupname="", $lower="0", $upper="all")
    {
        if(empty($authSid))
            $authSid = $this->adminSid;

        $requestUrl = sprintf(
            "http://127.0.0.1:%s/cgi-bin/priv/privWizard.cgi?&wiz_func=group_user_edit&getdata=1&refresh=0&sort=13&groupname=%s&filter=&lower=%s&upper=%s&sid=%s&type=%s",
            $this->nasPort,
            $groupname,
            0,
            1,
            $authSid,
            $this->domaintype
        );
        $pageContents = HttpClient::quickGet($requestUrl);

        $xmlObject = simplexml_load_string($pageContents, "SimpleXMLElement", LIBXML_NOCDATA);
        $userList = (string)$xmlObject->func->ownContent->ownUser;

        if(empty($userList))
            return Array(Array(), 0);

        $userList =  explode(", ", $userList);
        $total = count($userList);
        if($upper=="all")
        {
            $userList = array_slice($userList,  (int)$lower);
        }
        else
        {
            $userList = array_slice($userList,  (int)$lower, (int)$upper-(int)$lower+1);
        }

        return Array($userList, $total);
    }

    #List user's groups
    public function listUserGroups($authSid="", $username="", $lower="0", $upper="all")
    {
        if(empty($authSid))
            $authSid = $this->adminSid;

        $requestUrl = sprintf(
            "http://127.0.0.1:%s/cgi-bin/priv/privWizard.cgi?&wiz_func=user_group_edit&getdata=1&refresh=0&sort=13&userName=%s&filter=&lower=%s&upper=%s&sid=%s&type=%s",
            $this->nasPort,
            $username,
            0,
            1,
            $authSid,
            $this->domaintype
        );
        $pageContents = HttpClient::quickGet($requestUrl);

        $xmlObject = simplexml_load_string($pageContents, "SimpleXMLElement", LIBXML_NOCDATA);
        $groupList = (string)$xmlObject->func->ownContent->ownGroup;

        if(empty($groupList))
            return Array(Array(), 0);

        $groupList =  explode(", ", $groupList);
        $total = count($groupList);
        if($upper=="all")
        {
            $groupList = array_slice($groupList,  (int)$lower);
        }
        else
        {
            $groupList = array_slice($groupList,  (int)$lower, (int)$upper-(int)$lower+1);
        }

        return Array($groupList, $total);
    }
}
?>
