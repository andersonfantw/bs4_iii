<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','ehttp');
require_once dirname(__FILE__).'/init.php';

$bs_code = (int) $_GET['bs'];
$type = $_GET['type'];

$bookshelf = new bookshelf(&$db);
$data = $bookshelf->getByID($bs_code);
$_bsid = common::getcookie('bs');
if( (empty($_bsid) && !empty($bs_code)) || ($_bsid!=$bs_code)){
	BookshelfManager::BSManagerLoginCookie($bs_code,$data['bs_name']);
	header("Refresh: 0;");
	exit;
}

if($type=='sso' && $bs_code!=$_bsid){
	BookshelfManager::BSManagerLogin($data['u_id'],$data['u_name'],$data['u_cname']);
	BookshelfManager::BSManagerLoginCookie($data['bs_id'],$data['bs_name']);
	header("Refresh: 0;");
	exit;
}

if($data['bs_status']!=1 && $type!='sso'){
	$ee->Error('404');
}

$init = new init('tpl','auth','bookshelf_auth');
$tpl->assign('bsname',$data['bs_name']);
$tpl->display('backend/bookshelf_index.tpl');
