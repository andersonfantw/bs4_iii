<?php
require_once dirname(__FILE__).'/../init/config.php';

$init = new init('tpl','sysauth','db','ehttp');

//$system_setup = new system_setup($db,'system');
//$data = $system_setup->getList();
//if(empty($data[0]['external_ip']))
//	echo "<script>alert('請先設定外部IP');window.location.href='sys_setup.php';</script>";
/*$account = new account($db);
$data = $account->getBookshelfByAccountName($_COOKIE['adminuser']);
$tpl->assign('data',$data);*/
$tpl->display('backend/sys_index.tpl');
