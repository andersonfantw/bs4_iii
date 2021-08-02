<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');
$LogManager = new LogManager(__FILE__);
$tagevolve = new tagevolve($db);
$TagevolveManager = new TagevolveManager();

$type = $fs->valid($_GET['type'],'cmd');
if($type=='do_add' || $type=='do_update'){
	$te_type = $fs->valid($_POST['te_type'],'num');
	$tag = $fs->valid($_POST['tag'],'cmd');
	$arr_oldtag = $_POST['oldtag'];
	$arr_newtag = $_POST['newtag'];
	$year = $_POST['year'];
}

$id =  $fs->valid($_REQUEST['id'],'id');
$key = $fs->valid($_REQUEST['key'],'key');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;

switch ($type) {
  case 'add':
    $tpl->display('backend/sys_tagevolve_edit.tpl');
    break;
  case 'do_add':
  	$tagevolve = new tagevolve($db);
  	$is_success = true;
  	switch($te_type){
  		case 0:
  			list($_ftid,$_ftval)=explode(':',$arr_oldtag[0]);
  			list($_ttid,$_ttval)=explode(':',$arr_newtag[0]);
  			$tagevolve->addFrom($_ftid);
  			$tagevolve->addTo($_ttid);
  			$tagevolve->rename();
  			break;
  		case 1:
	  		list($_tid,$_tval)=explode(':',$arr_oldtag[0]);
	  		list($_yid,$_yval)=explode(':',$year);
	  		$ftid = $fs->valid($_tid,'id');
	  		$ftval = $fs->valid($_tval,'name');
	  		$year = $fs->valid($_yval,'num');
	  		$tagevolve->addFrom($ftid);
	  		for($i=0;$i<count($arr_newtag);$i++){
	  			list($_tid,$_tval)=explode(':',$arr_newtag[$i]);
		  		$ttid = $fs->valid($_tid,'id');
	  			$ttval = $fs->valid($_tval,'name');
	  			$tagevolve->addYear($year);
	  			$tagevolve->addTo($ttid);
	  			/*
					$data=array('te_otid'=>intval($ftid),
											'te_otname'=>$ftval,
											'te_ntid'=>intval($ttid),
											'te_ntname'=>$ttval,
											'te_type'=>intval($te_type),
											'createdate'=>$createdate);
					$is_success &= $tagevolve->insert($fs->sql_safe($data));
					*/
	  		}
	  		$tagevolve->separate();
	  		break;
  		case 2:
	  		list($_tid,$_tval)=explode(':',$arr_newtag[0]);
	  		$ftid = $fs->valid($_tid,'id');
	  		$ftval = $fs->valid($_tval,'name');
	  		$tagevolve->addTo($ftid);
	  		for($i=0;$i<count($arr_oldtag);$i++){
	  			list($_tid,$_tval)=explode(':',$arr_oldtag[$i]);
		  		$ttid = $fs->valid($_tid,'id');
	  			$ttval = $fs->valid($_tval,'name');
	  			$tagevolve->addFrom($ttid);
	  			/*
					$data=array('te_otid'=>intval($ttid),
											'te_otname'=>$ttval,
											'te_ntid'=>intval($ftid),
											'te_ntname'=>$ftval,
											'te_type'=>intval($te_type),
											'createdate'=>$createdate);
					$is_success &= $tagevolve->insert($fs->sql_safe($data));
					*/
	  		}
	  		$tagevolve->combine();
	  		break;
  	}
    if($is_success){
      $status->go('sys_tagevolve.php','success',LANG_MESSAGE_ADD_SUCCESS);
    }else
      $status->back('error',LANG_ERROR_ADD_FAIL);
    break;
  case 'delete':
  	$data = $tagevolve->getByKey($key);
    if($tagevolve->del($key)){
    	$LogManager->setData($data);
    	$LogManager->event('command: '.$type, sprintf('%s key=%s by SysAdmin %s',$type,$key,bssystem::getLoginUName()));
      $status->go('sys_tagevolve.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    }else
			$status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'list':
  case 'search':
  default:
    require_once LIBS_PATH.'/page.class.php';

		$data = $TagevolveManager->getList('',($page-1)*PER_PAGE,PER_PAGE,'');
    $record=$data['total'];
    if($record>PER_PAGE){
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }
    $tpl->assign('arr',array('更名','分開','合併'));
    $tpl->assign('data',$data);
    $tpl->display('backend/sys_tagevolve_list.tpl');
    break;
}
?>
