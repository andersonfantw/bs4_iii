<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';

global $bs_code;
$type = $fs->valid($_GET['type'],'cmd');
$bookshelf = new bookshelf($db);
$ConfigManager = new ConfigManager();

if($type=='do_update'){
	$data['bs_list_status'] = (int) $fs->valid($_POST['bs_list_status'],'bool');
	$data['is_webbook'] = (int) $fs->valid($_POST['is_webbook'],'bool');
	$data['is_ibook'] = (int) $fs->valid($_POST['is_ibook'],'bool');
	$data['enable_giantview_chat'] = (int) $fs->valid($_POST['enable_giantview_chat'],'bool');
	$data['enable_giantview_system'] = (int) $fs->valid($_POST['enable_giantview_system'],'bool');
	$is_member = (int) $fs->valid($_POST['mid'],'id');
	if($is_member){
		$data['is_allbook']=0;
		$data['is_newbook']=0;
	}else{
		$data['is_allbook'] = (int) $fs->valid($_POST['is_allbook'],'bool');
		$data['is_newbook'] = (int) $fs->valid($_POST['is_newbook'],'bool');
	}
	$data['bs_title'] = $fs->valid($_POST['bs_title'],'name');
	$data['bs_header_link'] = $fs->valid($_POST['bs_header_link'],'url');
	$data['bs_header_height'] = (int) $fs->valid($_POST['bs_header_height'],'num');
	$data['bs_footer_height'] = (int) $fs->valid($_POST['bs_footer_height'],'num');
	$data['bs_footer_content'] = $fs->valid($_POST['bs_footer_content'],'content');
	$data['bs_header'] = (int) $fs->valid($_POST['bs_header'],'id');
	$data['bs_footer'] = (int) $fs->valid($_POST['bs_footer'],'id');
	$del_bs_header = (int) $fs->valid($_POST['del_bs_header'],'id');
	$del_bs_footer = (int) $fs->valid($_POST['del_bs_footer'],'id');
	/***********file upload*************/

	//$_FILES array process
	//header image
	$uploadfile=$_FILES['bs_header_file'];
	$header_file_data = common::insert_host_image($uploadfile);
	if(!empty($header_file_data['id'])){
		//delete prev image
		$del_bs_header = $data['bs_header'];
		$data['bs_header'] = $header_file_data['id'];
	}
	if(!empty($del_bs_header)){
		$_adminid = bssystem::getLoginUID();
		common::remove_file('host',$del_bs_header,$_adminid,$bs_code);
	}

	//footer image
	$uploadfile=$_FILES['bs_footer_file'];
	$footer_file_data = common::insert_host_image($uploadfile);
	if(!empty($footer_file_data['id'])){
		//delete prev image
		$del_bs_footer = $data['bs_footer'];
		$data['bs_footer'] = $footer_file_data['id'];
	}
	if(!empty($del_bs_footer)){
		$_adminid = bssystem::getLoginUID();
		common::remove_file('host',$del_bs_footer,$_adminid,$bs_code);
	}

  /***********file upload*************/

 
}

switch ($type) {
  case 'do_update':
    if($bookshelf->update($bs_code,$fs->sql_safe($data))){
    	/***********write to config file*************/
    	$data['is_newbook'] = empty($data['is_newbook'])?0:$data['is_newbook'];
    	$data['is_allbook'] = empty($data['is_allbook'])?0:$data['is_allbook'];
			$data['is_cloudconvert'] = empty($data['is_cloudconvert'])?1:$data['is_cloudconvert'];

			unset($define);
			$define=array();
			$define['TITLE'] = $data['bs_title'];
			if(!empty($data['bs_footer_content'])) $define['FOOTER_TEXT'] = str_replace("\r\n","<br />",$data['bs_footer_content']);
			$define['LIST_STATUS'] = $data['bs_list_status'];
			$define['TYPE'] = $data['bs_type'];
			$define['HEADER_LINK'] = $data['bs_header_link'];
    	$define['IBOOK_STATUS'] = $data['is_ibook'];
    	$define['WEBBOOK_STATUS'] = $data['is_webbook'];
			$define['CLOUDCONVERT_STATUS'] = $data['is_cloudconvert'];
    	$define['MEMBER'] = $is_member;
    	$define['NEWBOOK'] = $data['is_newbook'];
    	$define['ALLBOOK'] = $data['is_allbook'];
    	$define['GIANTVIEW_SYSTEM'] = $data['enable_giantview_system'];
    	$define['GIANTVIEW_CHAT'] = $data['enable_giantview_chat'];
			$ConfigManager->SaveDefine('userbase',$define);
	   	/***********write to config file*************/

      /***********write to css file*************/
      if(!empty($header_file_data['path'])){
      	$header_image = $header_file_data['path'];
      	//not delete banner first, upload new banner
      	if(!empty($_POST['header_image'])){
      		$del_bs_header = $fs->valid($_POST['header_image'],'path');
      		$_adminid = bssystem::getLoginUID();
      		common::remove_file('host',$del_bs_header,$_adminid,$bs_code);
      	}
      }elseif(!empty($_POST['header_image'])){
        $header_image = $fs->valid($_POST['header_image'],'path');
      }

      if(!empty($footer_file_data['path'])){
      	$footer_image = $footer_file_data['path'];
      	//not delete banner first, upload new banner
      	if(!empty($_POST['footer_image'])){
      		$del_bs_footer = $fs->valid($_POST['footer_image'],'path');
      		$_adminid = bssystem::getLoginUID();
      		common::remove_file('host',$del_bs_footer,$_adminid,$bs_code);
      	}
      }elseif(!empty($_POST['footer_image'])){
        $footer_image = $fs->valid($_POST['footer_image'],'path');
      }

      if(!empty($header_image) && !empty($data['bs_header_height'])){
      	unset($css);
      	$css = array();
      	$css['height']=$data['bs_header_height'];
      	$css['image']=$header_image;
      	$ConfigManager->SetCSS('header',$css);
      }

      if(!empty($footer_image) && !empty($data['bs_footer_height'])){
      	unset($css);
      	$css = array();
      	$css['height']=$data['bs_footer_height'];
      	$css['image']=$footer_image;
      	$ConfigManager->SetCSS('footer',$css);
      }
      $ConfigManager->SaveCSS('userbase');
      /***********write to css file*************/

      /***********write to json file*************/
      unset($json);
      $json=array();
      $json['headerlink']=$data['bs_header_link'];
      $json['footer']=$data['bs_footer_content'];
      $ConfigManager->SaveJSON('userbase',$json);
      /***********write to json file*************/

	   	$status->go('setup.php','success',LANG_MESSAGE_UPDATE_SUCCESS);
  	}
  	else
  		$status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'edit':
  default:
	  $data = $bookshelf->getByID($bs_code); 
		if(!empty($data['bs_header_image'])){
    	$tpl->assign('path_header_image',HostManager::getBookshelfBase(true,false).'/'.$data['bs_header_image']);
    	$tpl->assign('header_image',$data['bs_header_image']);
		}
		if(!empty($data['bs_footer_image'])){
    	$tpl->assign('path_footer_image',HostManager::getBookshelfBase(true,false).'/'.$data['bs_footer_image']);
    	$tpl->assign('footer_image',$data['bs_footer_image']);
		}
 
	  $tpl->assign('data',$data);
  	$tpl->display('backend/setup.tpl');
  	break;
}
