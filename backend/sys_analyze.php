<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','sysauth','tpl','filter','status','ehttp');

$type = $fs->valid($_GET['type'],'cmd');

switch ($type) {
  default:
  	$tpl->display('backend/sys_analyze.tpl');
  	break;
}
?>