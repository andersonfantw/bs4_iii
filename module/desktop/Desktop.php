<?php
function execDesktop($uid,$bs_id,$token){
	global $tpl;
	global $db;
	global $ee;
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('uid',$uid);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('bsid',$bs_id);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('isApp',!empty($token));

	if(CONFIG_LOGINMODE){
		$_buid = bssystem::getLoginBUID();
		$_uid = bssystem::getLoginUID();
		if(empty($_buid) && empty($_uid)){
			header('Location: /signout/');
			exit;
		}
	}

	$bookshelf = new bookshelf($db,'bookshelfs');
	$data = $bookshelf->getList('bs_id desc',0,0,sprintf('bs_status=1 and bs_id=%u',$bs_id));
	if(empty($data['result'])){
		$ee->Error('404');
	}

	$header=VIEW_PATH.'include/header.tpl';
	if(!empty($_SESSION['adminid'])){
		$header=VIEW_PATH.'include/header_adminlogin.tpl';
	}elseif($_SESSION['buid']>0){
		$header=VIEW_PATH.'include/header_userlogin.tpl';
	}
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('header_html', $tpl->fetch($header));
}
?>