<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('tpl','auth','bookshelf_auth','db','ehttp');
require_once dirname(__FILE__).'/init.php';
/*
parse_str(base64_decode($id),$url);
if($url['username']){
	$name = sprintf('User name: %s',$url['username']);
}elseif($url['groupname']){
	$name = sprintf('Group name: %s',$url['groupname']);
}
$tpl->assign('name',$name);
*/
$tpl->display('backend/learninghistory.tpl');
