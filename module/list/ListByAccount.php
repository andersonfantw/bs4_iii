<?php
function execListByAccount($uid){
/*
	$init = new init('db');
	global $db;
	$bookshelf = new bookshelf($db);
	$data = $bookshelf->getList('bs_id desc',0,0,'bs_status=1 and bs_list_status=1 and u_id='.$uid);
*/
	if(CONFIG_LOGINMODE){
		$_buid = bssystem::getLoginBUID();
		$_uid = bssystem::getLoginUID();
		if(empty($_buid) && empty($_uid)){
			header('Location: /signout/');
			exit;
		}
	}
	$w=common::get_wonderbox_id();
	if($w["rc"]==0)
		$wid = $w["wbox_id"];
	else
		$wid = $w["errmsg"];
	//$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('data',$data);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('wid',$wid);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('uid',$uid);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('buid',0);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('mode','acclist');
}
?>
