<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','tpl','inputxss','filter','ejson');

global $bs_code;
$bs_code = (int) $fs->valid($_POST['bs'],'id');
$ConfigManager = new ConfigManager(0,$bs_code);
$login = new login($db);
$_path = $ConfigManager->getDefineUserbase();
if(!empty($_path)){
	include_once $_path;
}
include_once $ConfigManager->getDefineSyspath();

$output = $json = new Services_JSON();
header('Content-Type: application/json; charset=utf-8');
$AuthManager = new AuthManager();

$bid = (int) $fs->valid($_POST['bid'],'id');
$cid = (int) $fs->valid($_POST['cid'],'id');
$bt = $fs->valid($_POST['booktype'],'cmd');

if($bid<1){
	$ee->ERROR('406.60');
}
if($bt!='webbook' && $bt!='ibook'){
	$ee->ERROR('406.62');
}
if($bt=='webbook' && WEBBOOK_STATUS!=true){
	$ee->ERROR('406.63');
}
if($bt=='ibook' && IBOOK_STATUS!=true){
	$ee->ERROR('406.63');
}

//has login
$u_id = bssystem::getLoginUID();
$bu_id = bssystem::getLoginBUID();
if(empty($u_id)){
	if(empty($bu_id)){
		$ac = $fs->valid($_POST['ac'],'acc');
		$pw = $fs->valid($_POST['pw'],'pwd');
		$rs = $AuthManager->validUser($ac,$pw);

		if($rs){
		  $gid = $rs['g_id'];
		  $bu_id = $rs['bu_id'];
		  $bu_name = $rs['bu_name'];
	
			BookshelfManager::UserLogin($bu_id,$ac,$bu_name);
		}else{
			$ee->ERROR('401.12');
		}
		
	}

	//check is mybook
	$book = new book($db);
	$book->reset();
	$book->setBookStatus('private');
	$book->setBUID($bu_id);
	$book->setBSID($bs_code);
	$all_data = $book->getList('',0,0,'b_id='.$bid);
	$isMyBook = ($all_data['total']>0);

	if(!LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::USER_LOGIN_VERIFY_LOOSE_MODE) && !$isMyBook){
		if(!empty($cid)){
		  //check group and category
		  $sql=<<<SQL
select * 
from bookshelf2_groups_category
where g_id in (select g_id from bookshelf2_group_users where bu_id=%u)
	and (c_id=%u or c_id in (select c_id from bookshelf2_category where c_parent_id=%u))
SQL;
		  $sql = sprintf($sql,$bu_id,$cid,$cid);
		  $rs = $db->query_first($sql);
		  if(!$rs){
		    $ee->ERROR('406.64');
		  }
		  //check book and category
		  $sql=<<<SQL
select * 
from bookshelf2_books_category
where b_id = %u
	and (c_id=%u or c_id in (select c_id from bookshelf2_category where c_parent_id=%u))
SQL;
		  $sql = sprintf($sql,$bid,$cid,$cid);
		  $rs = $db->query_first($sql);
		  if(!$rs){
		    $ee->ERROR('406.64');
		  }
		}else{
		  //check group and book
		  $sql=<<<SQL
select * 
from bookshelf2_books_category
where b_id = %u
	and c_id in(select c_id
			from bookshelf2_groups_category
			where g_id in (select g_id
					from bookshelf2_group_users
					where bu_id=%u))
SQL;
		  $sql = sprintf($sql,$bid,$bu_id);
		  $rs = $db->query_first($sql);
		  if($rs){
		  }else{
		    $ee->ERROR('406.65');
		  }
		}

		if(MEMBER_SYSTEM==MemberSystemEnum::Regist){
			$sql=<<<SQL
select g_id 
from bookshelf2_groups_category 
where c_id in (select c_id 
	from bookshelf2_books_category
	where b_id=%u);
SQL;
			$sql = sprintf($sql,$bid);
			$data = $db->get_results($sql);
			$arr_gid=array();
			foreach($data as $row){
				$arr_gid[] = $row['g_id'];
			}
			if(ActiveCodeManager::isExpired($bu_id,$arr_gid)){
				$ee->ERROR('401.14');
			}
		}

	}
}

$data = array();
$book = new book($db);
$rsb = $book->getPublicByID($bid);

if($bt=='webbook'){
	$link = str_replace(HttpLocalIPPort,'',$rsb['webbook_link']);
	$link = str_replace(LocalHost,'',$link);
}else if($bt=='ibook'){
	$link = str_replace(HttpLocalIPPort,'',$rsb['ibook_link']);
	$link = str_replace(LocalHost,'',$link);
}
$link = HttpExternalIPPort.$link;
$ee->add('link',trim($link));
$ee->Message('200');
?>
