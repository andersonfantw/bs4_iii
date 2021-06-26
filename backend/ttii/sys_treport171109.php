<?php
/*
sys_testcase
single sign-on
add/del/import tags
add/del/import doc-tag,
tableau api
APP api

PHPSESSID=
*/
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('db','sysauth','tpl','filter','ejson');

$testcase=<<<CASE
Application error message(Medium) p4
HTTP Header input User-Agent was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c, Error message found: MySQL Error
/plugin/uploadqueue/api/api.php?cmd=iiisso&acc=hclin
http://220.130.2.245:80/plugin/uploadqueue/api/api.php?cmd=iiisso&acc=hclin
12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?’c
PHPSESSID=rb3dq43n9ud3ioqt8pvb32aei6; currentlang=zh-tw; _pk_id.1.0356=ed5fff4eaaf90db6.1510541005.1.1510541005.1510541005.; _pk_ses.1.0356=*

Application error message(Medium) p4
HTTP Header input User-Agent was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c, Error message found: MySQL Error
/plugin/uploadqueue/api/api.php?cmd=iiisso&acc=hclin
12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?’c

PHPSESSID=rb3dq43n9ud3ioqt8pvb32aei6; currentlang=zh-tw; _pk_id.1.0356=ed5fff4eaaf90db6.1510541005.1.1510541005.1510541005.; _pk_ses.1.0356=*

CASE;

/*
testcase
title, url, cookie, params
*/
$arr = explode("\n",$testcase);
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

for($i=0;$i<count($arr);$i+=7){
  $http_request = "POST ".$arr[$i+2]." HTTP/1.1\r\n";
  $http_request .= "Host: ".$domain.":".$port." \r\n";
  if(!empty($arr[$i+3])){
  	$http_request .= "Referer: ".$arr[$i+3]."\r\n";;
  }
  if(empty($arr[$i+4])){
  	$http_request .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
  }else{
  	$http_request .= "User-Agent: ".$arr[$i+4]."\r\n";
  }
  $http_request .= "Cookie: ".$arr[$i+5]."\r\n";
  $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $http_request .= "Content-Length: ".strlen($arr[$i+6])."\r\n";
  $http_request .= "Connection: Close\r\n\r\n";

  $fp = fsockopen($domain, $port, $errno, $errstr, 10);

	$result='';
  if (!$fp) {
    echo "$errstr ($errno)<br>\n";exit;
  }else{
    fputs ($fp, $http_request.$arr[$i+6]);
    while (!feof($fp)) {
      $result .= fread($fp,32000);
    }
  }
  fclose ($fp);
  $output = preg_split("/\\r\\n\\r\\n/",$result,2);
  echo sprintf($table,$arr[$i],$arr[$i+1],$arr[$i+2],str_replace("\n","<br />",$http_request)."<br />".$arr[$i+6],str_replace("\n","<br />",$output[0])."<br /><br />".$output[1]);
  ob_flush();
  flush();
}


function http_request(){
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
