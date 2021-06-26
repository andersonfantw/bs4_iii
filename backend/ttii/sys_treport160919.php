<?php
/*
sys_testcase_acunetixreport160919
*/
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('db','sysauth','tpl','filter','ejson');

$testcase=<<<CASE
Application error message(Medium) p11
Cookie input currentlang was set to en Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Application error message(Medium) p11
URL encoded POST input buid was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: MySQL Error
/api/api.php
currentlang=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4
buid=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##&cmd=validBookshelfList&isExpiredList=false&uid=-1
Application error message(Medium) p12
Cookie input currentlang was set to en Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/api/api.php
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Application error message(Medium) p12
Cookie input currentlang was set to en Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/api/api.php
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Application error message(Medium) p13
URL encoded POST input uid was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: MySQL Error
/api/api.php
currentlang=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4
buid=-1&cmd=validBookshelfList&isExpiredList=false&uid=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##
Application error message(Medium) p13
Cookie input currentlang was set to en Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/api/backend/api.php
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Application error message(Medium) p14
Cookie input currentlang was set to en Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/api/get_books.php
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Application error message(Medium) p14
Cookie input currentlang was set to en Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/api/get_current_device_path.php
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Application error message(Medium) p15
Cookie input currentlang was set to en Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/api/get_itutor.php
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Application error message(Medium) p15
Cookie input currentlang was set to en Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/api/get_menu.php
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Application error message(Medium) p16
URL encoded POST input bs was set to 0 Error message found: Fatal error
/api/get_sys_info.php
currentlang=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4
bs[]=0
Application error message(Medium) p16
Cookie input currentlang was set to en Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/api/get_sys_info.php
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Application error message(Medium) p17
Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/api/login_check.php
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Application error message(Medium) p17
Cookie input currentlang was set to en Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/api/logout.php
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Application error message(Medium) p18
Cookie input currentlang was set to en Error message found: <b>Warning</b>: trim() expects parameter 1 to be string, array given in <b>/Storage/var/www/html/bs4/init/lang_init.php</b> on line <b>28</b><br />
/plugin/tag/api/api.php
currentlang[]=en; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757307.1473757057.; _pk_ses.1.5dc8=*; site_bsid=3; PIWIK_SESSID=l8j5dond8318p7fl4ncirlcgs4

Password field submitted using GET method(Medium) p19
form name: "<unnamed>"<br />form action: ""<br />password input: "form_password"
/plugin/piwik/
currentlang=zh-tw; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757057.1473757057.; _pk_ses.1.5dc8=*

User credentials are sent in clear text(Medium) p20
Form name: login_form<br />Form action: http://203.70.194.127:8080/plugin/piwik/<br />Form method: POST<br />Form inputs:<br />- form_login [Text]<br />- form_password [Password]<br />- form_nonce [Hidden]<br />- form_rememberme [Checkbox]
/plugin/piwik/
currentlang=zh-tw; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757057.1473757057.; _pk_ses.1.5dc8=*

User credentials are sent in clear text(Medium) p21
Form name: <empty><br />Form action: http://203.70.194.127:8080/plugin/piwik/<br />Form method: GET<br />Form inputs:<br />- form_login [Text]<br />- form_nonce [Hidden]<br />- form_password [Password]<br />- form_password_bis [Password]<br />- module [Hidden]<br />- action [Hidden]
/plugin/piwik/
currentlang=zh-tw; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473757057%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=359f08605f69bbd6.1473757057.1.1473757057.1473757057.; _pk_ses.1.5dc8=*
CASE;

/*
testcase
title, url, cookie, params
*/
$arr = explode("\r\n",$testcase);
list($domain,$port) = explode(':',ExternalIPPort);
$domain = $_SERVER['SERVER_ADDR'];
$arr_port=array();
if(empty($port)){
	$port='80';
	$arr_port[] = '80';
}else{
	$arr_port[] = $port;
	$arr_port[] = '80';
}

//check which address is correct
foreach($arr_port as $_port){
	if(@fsockopen($domain, $_port, $errno, $errstr, 5)){
		$port = $_port;
		break;
	}
}
echo sprintf('<div style="width:1024px;margin:20px auto">L-NET Vulnerability scanning<br />LOCAL IP: %s:%s</div><br /><br />',$domain, $port);

$table=<<<TABLE
<table style="width:1024px;border:solid 1px #000;margin:15px auto">
	<tr>
		<td>TITLE</td>
		<td style="width:900px;border-bottom:solid 1px #000;">%s</td>
	</tr>
	<tr>
		<td>DESCRIPTION</td>
		<td style="width:900px;border-bottom:solid 1px #000;">%s</td>
	</tr>
	<tr>
		<td>PATH</td>
		<td style="width:900px;border-bottom:solid 1px #000; word-break:break-all">%s</td>
	</tr>
	<tr>
		<td>HTTP REQUEST</td>
		<td style="width:900px;border-bottom:solid 1px #000; word-break:break-all">%s</td>
	</tr>
	<tr>
		<td>HTTP RESPONSE</td>
		<td style="width:900px; word-break:break-all">%s</td>
	</tr>
</table>
TABLE;

for($i=0;$i<count($arr);$i+=5){
  $http_request = "POST ".$arr[$i+2]." HTTP/1.1\r\n";
  $http_request .= "Host: ".$domain.":".$port." \r\n";
  $http_request .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
  $http_request .= "Cookie: ".$arr[$i+3]."\r\n";
  $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $http_request .= "Content-Length: ".strlen($arr[$i+4])."\r\n";
  $http_request .= "Connection: Close\r\n\r\n";

  $fp = fsockopen($domain, $port, $errno, $errstr, 10);

	$result='';
  if (!$fp) {
    echo "$errstr ($errno)<br>\n";exit;
  }else{
    fputs ($fp, $http_request.$arr[$i+4]);
    while (!feof($fp)) {
      $result .= fread($fp,32000);
    }
  }
  fclose ($fp);
  $output = preg_split("/\\r\\n\\r\\n/",$result,2);
  echo sprintf($table,$arr[$i],$arr[$i+1],$arr[$i+2],str_replace("\n","<br />",$http_request)."<br />".$arr[$i+4],str_replace("\n","<br />",$output[0])."<br /><br />".$output[1]);
  ob_flush();
  flush();
}
?>