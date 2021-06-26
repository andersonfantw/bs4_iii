<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';

$type = $fs->valid($_GET['type'],'cmd');
$shortcut = new shortcut(&$db);

if($type=='do_add' || $type=='do_update'){
	$data['bs_id'] = bssystem::getBSID();
  $data['ts_name'] = $fs->valid($_POST['ts_name'],'name');
  $data['ts_description'] = $fs->valid($_POST['ts_description'],'content');

  $ticket = $fs->valid($_POST['ticket'],'timestamp');
  if($ticket){
		$TagShortcutImage = new TagShortcutImage();
		$tmppath = $TagShortcutImage->getImageTmpPath($ticket);

		$val = common::insert_host_image($tmppath);
		if($val['id']) $data['file_id'] = intval($val['id']);
	}
}
$id = (int) $fs->valid($_REQUEST['id'],'id');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;

switch ($type) {
  case 'add':
    $tpl->display('backend/shortcut_add.tpl');
    break;
  case 'edit':
  	$host_base = HostManager::getBookshelfBase(true,false);
		$data = $shortcut->getByID($id);
		if(preg_match("/\.txt$/", $data['f_path'])){
			$path = sprintf('%s%s/uploadfiles/%s',ROOT_PATH,$host_base,$data['f_path']);
			$data['img_html'] = file_get_contents($path);
		}else{
			$data['img_html'] = sprintf('<img src="%s/uploadfiles/%s" />',$host_base,$data['f_path']);
		}
    $tpl->assign('data',$data);
    $tpl->display('backend/shortcut_edit.tpl');
    break;
  case 'do_add':
    $sc_id = $shortcut->insert($fs->sql_safe($data),true);
    if($sc_id){
      $status->go('shortcut.php?type=edit&id='.$sc_id,'success','');
    }else{
      $status->back('error',LANG_ERROR_ADD_FAIL);
    }
    break;
  case 'do_update':
    if($shortcut->update($id,$fs->sql_safe($data)))
      $status->go('shortcut.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'delete':
		$data = $shortcut->getByID($id);
		//delete image
    if($shortcut->del($id)){
			if(!empty($data['f_path'])){
				$file = HostManager::getBookshelfBase(true,true).'/uploadfiles/'.$data['f_name'].'.'.$data['f_type'];
				if(is_file($file)) @unlink($file);
			}
    	$status->go('shortcut.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    }else
    	$status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'disable':
    if($shortcut->update_status($id,0))
      $status->go('shortcut.php?page='.$page,'success',LANG_MESSAGE_BOOKSHELF_ACTIVE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_BOOKSHELF_INACTIVE_FAIL);
    break;
  case 'enable':
    if($shortcut->update_status($id,1))
      $status->go('shortcut.php?page='.$page,'success',LANG_MESSAGE_BOOKSHELF_INACTIVE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_BOOKSHELF_ACTIVE_FAIL);
    break;
  case 'list':
  case 'search':
  case 'search_top10':
  default:    
    require_once LIBS_PATH.'/page.class.php';
    $host_base = HostManager::getBookshelfBase(true,false);
    $shortcut->reset();
    $shortcut->setStatus('all');
    $shortcut->setBSID($bs_code);
		$data = $shortcut->getList('ts_id desc',($page-1)*PER_PAGE,PER_PAGE,'');
		for($i=0;$i<count($data['result']);$i++){
			//is html
			if(preg_match("/\.txt$/", $data['result'][$i]['f_path'])){
				$path = sprintf('%s%s/uploadfiles/%s',ROOT_PATH,$host_base,$data['result'][$i]['f_path']);
				$data['result'][$i]['img_html'] = file_get_contents($path);
			}else{
				$data['result'][$i]['img_html'] = sprintf('<img src="%s/uploadfiles/%s" />',$host_base,$data['result'][$i]['f_path']);
			}
		}
    $tpl->assign('data',$data['result']);
    $tpl->assign('host_base',$host_base);
    $tpl->display('backend/shortcut_list.tpl');
    break;
}