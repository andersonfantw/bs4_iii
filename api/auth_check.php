<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','tpl','inputxss','filter','ejson');
$ConfigManager = new ConfigManager();
$_path = $ConfigManager->getDefineUserbase();
if(!empty($_path)){
	include_once $_path;
}
include_once $ConfigManager->getDefineSyspath();

global $bs_code;
$bs_code = (int) $fs->valid($_POST['bs'],'id');

$output = $json = new Services_JSON();
header('Content-Type: application/json; charset=utf-8');
$AuthManager = new AuthManager();

$ac = $fs->valid($_POST['ac'],'acc');
$pw = $fs->valid($_POST['pw'],'pwd');

if(empty($ac) || empty($pw) ){
	$ee->ERROR('406.60');
}

if(substr($ac,0,1)=='@'){
	$rs = $AuthManager->validBSManager($ac,$pw);

	if(!$rs){
	  $ee->ERROR('401.11');
	}else{
		$id=$rs['u_id'];
	}
	
}else{
	$rs = $AuthManager->validUser($ac,$pw);

	if(!$rs){
	  $ee->ERROR('401.12');
	}else{
		$id=$rs['bu_id'];
	}
}

$ee->Message('200.12');
