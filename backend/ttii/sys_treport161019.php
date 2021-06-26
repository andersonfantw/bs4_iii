<?php
/*
sys_testcase_acunetixreport160909
*/
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('db','sysauth','tpl','filter','ejson');

$testcase=<<<CASE
Application error message(Medium) p10
URL encoded POST input uid was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: MySQL Error
/api/api.php
currentlang=en; _pk_ref.1.0356=%5B%22%22%2C%22%22%2C1475809893%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.0356=b429197db7c2b552.1475809893.1.1475810416.1475809893.; _pk_ses.1.0356=*; site_bsid=3; PIWIK_SESSID=sqak1hsubanv7rul3m69c14c97
buid=-1&cmd=validBookshelfList&isExpiredList=false&uid=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##
Application error message(Medium) p11
URL encoded POST input bs was set to 0 Error message found: Fatal error
/api/get_sys_info.php
currentlang=en; _pk_ref.1.0356=%5B%22%22%2C%22%22%2C1475809893%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.0356=b429197db7c2b552.1475809893.1.1475810416.1475809893.; _pk_ses.1.0356=*; site_bsid=3; PIWIK_SESSID=sqak1hsubanv7rul3m69c14c97
bs[]=0
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