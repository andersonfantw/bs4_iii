<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','filter','ehttp');

$AuthManager = new AuthManager();

$bs_code = (int) $fs->valid($_GET['bs'],'id');
$acc = $fs->valid($_GET['acc'],'acc');
$op = $fs->valid($_GET['op'],'cmd');

if($op=='login'){
	new init('tpl');
	$tpl->assign('bs',$bs_code);
	$tpl->display('backend/login.tpl');
	exit;
}

if($op=='system_login'){
  new init('tpl');
  $tpl->assign('sys','system');
  $tpl->display('backend/login.tpl');
  exit;
}

if($op=='sso'){
	$account = new account($db);
	$rs = $account->getByName($acc);
	$uid = $rs['u_id'];
	BookshelfManager::SSOLogin($uid,$acc);
  header("Location:bookshelf_index.php?type=sso&bs=".$bs_code);
  exit;
}

$op=$_GET['op'];
if($op=='logout'){
	BookshelfManager::BSManagerLogout();
	BookshelfManager::SysLogout();
  new init('tpl');
  $tpl->display('backend/login.tpl');
  exit;
}
if($_POST){
	$init = new init('tpl','db','filter');
	$account=$fs->valid($_POST['account'],'acc');
	$password=$fs->valid($_POST['password'],'pwd');

  if($fs->valid($_GET['sys'],'cmd')=='system'){
  	if($account!='admin'){
			$info = LicenseManager::getSystemActiveInfo();
			if(!$info['active']){
				switch($info['mode']){
					case -1:
						$path = WEB_URL.'/expired/';
						if($module!='Expired'){
							header('Location: '.$path);
						}
						break;
					default:
						$path = WEB_URL.'/startup/';
						if($module!='Startup'){
							header('Location: '.$path);exit;
						}
						break;
				}
			}
  	}
  	$rs = $AuthManager->validAdmin($account,$password);

    if($rs){
/*
			$su_name = $rs['su_name'];
			BookshelfManager::AdminLogin($su_name);
*/
			header("Location:sys_index.php");
			exit;
    }else{
      echo "<script>alert('".LANG_ERROR_LOGIN_FAIL."');window.location.href='index.php?op=system_login';</script>";
    exit;
    }
    
  }else{
  	//���d�޲z���n�J
		$info = LicenseManager::getSystemActiveInfo();
		if(!$info['active']){
			switch($info['mode']){
				case -1:
					$path = WEB_URL.'/expired/';
					if($module!='Expired'){
						header('Location: '.$path);
					}
					break;
				default:
					$path = WEB_URL.'/startup/';
					if($module!='Startup'){
						header('Location: '.$path);exit;
					}
					break;
			}
		}
  	$rs = $AuthManager->validBSManager($account,$password);

    if($rs){
/*
    	$uname = $rs['u_name'];
    	$ucname = $rs['u_cname'];
    	$uid = $rs['u_id'];
    	BookshelfManager::BSManagerLogin($uid,$uname,$ucname);
*/
    	header("Location:index.php");
    	exit;
    }else{
      echo "<script>alert('".LANG_ERROR_LOGIN_FAIL."');window.location.href='index.php?op=login';</script>";
    exit;
    }
  }
}
$_bsid = common::getcookie('bs');
if(empty($_bsid) && !empty($bs_code) ){
	//setcookie('bs',$bs_code);
	$bookshelf = new bookshelf($db);
	$data = $bookshelf->getByID($bs_code);
	BookshelfManager::BSManagerLoginCookie($bs_code,$data['bs_name']);
}
$init = new init('tpl','auth','db');
$account = new account($db);
$_adminid = bssystem::getLoginUID();
$data = $account->getBookshelfByUID($_adminid);
$tpl->assign('data',$data);
$tpl->display('backend/index.tpl');
