<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','sysauth','tpl','filter','ejson');

$logname = $fs->valid($_GET['ln'],'name');

switch($logname){
	case 'uploadqueue':
		$path = LOG_DIR . 'uploadqueue.log';
		break;
	case 'ecocat':
		$path = LOG_DIR . 'ecocat.log';
		break;
	case 'phperrors':
		$path = '/var/log/php/php_errors.log';
		break;
}
header("Content-Type: text/plain");

if(!file_exists($path)){
	exit;
}
$filesize = filesize($path);
$fp = fopen($path,'r');
$seek = $filesize-1024*1024*3;
if($seek<0){
	$seek=0;
}
fseek($fp,$seek);
while (!feof($fp)) {
  echo fread($fp,32000);
	ob_flush();
  flush();
}
fclose($fp);

?>
