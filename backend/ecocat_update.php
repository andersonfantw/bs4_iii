<?php
/*************************************************
*        ecocat_id, share_bs_id
* manual '',        ''
* ecocat ecocat_id, ''
* share  bsss_id,    process_id
*************************************************/
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','filter','ehttp');
require_once dirname(__FILE__).'/init.php';

global $bs_code;

$type = $fs->valid($_GET['type'],'cmd');
$cid = $fs->valid($_GET['cid'],'id');
$processid = $fs->valid($_GET['processid'],'key');

$account = new account($db);
$data = $account->getAccountByBSID($bs_code);
$uid = $data['u_id'];

switch($type){
	case 'share_bs':
		$bsss_id = (int) $_GET['bsss_id'];
		if($bsss_id>0){
			BookshelfManager::doEcocatUpdate('share_bs',$bs_code,$uid,$cid,$processid,$bsss_id);
		}
		break;
	case 'ecocat':
	default:
		BookshelfManager::doEcocatUpdate('ecocat',$bs_code,$uid);
		break;
}
?>