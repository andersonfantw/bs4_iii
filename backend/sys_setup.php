<?php
require_once dirname(__FILE__).'/../init/config.php';

$init = new init('db','sysauth','tpl','filter','status','ehttp');

$type = $fs->valid($_GET['type'],'cmd');
$system_setup = new system_setup($db);
$ConfigManager = new ConfigManager();

switch ($type) {
  case 'do_update':
    $data['google_code'] = addcslashes($_POST['google_code'],"'");
    $data['bs_title'] = $fs->valid($_POST['bs_title'],'name');
    $data['bs_header_link'] = $fs->valid($_POST['bs_header_link'],'url');
    $data['bs_header_height'] = (int) $fs->valid($_POST['bs_header_height'],'num');
    $data['bs_footer_height'] = (int) $fs->valid($_POST['bs_footer_height'],'num');
    $data['bs_footer_content'] = $fs->valid($_POST['bs_footer_content'],'content');
    $data['bs_number'] = (int) $fs->valid($_POST['bs_number'],'num');
    $data['bs_header'] = (int) $fs->valid($_POST['bs_header'],'id');
    $data['bs_footer'] = (int) $fs->valid($_POST['bs_footer'],'id');
    $del_bs_header = (int) $fs->valid($_POST['del_bs_header'],'bool');
    $del_bs_footer = (int) $fs->valid($_POST['del_bs_footer'],'bool');

    $data['b_writer'] = (int) $fs->valid($_POST['b_writer'],'bool');
    $data['b_link'] = (int) $fs->valid($_POST['b_link'],'bool');
    $data['b_imglink'] = (int) $fs->valid($_POST['b_imglink'],'bool');
    /***********file upload*************/

    //$_FILES array process
    //header image
	  $uploadfile=$_FILES['bs_header_file'];
		$header_file_data = common::insert_sys_image($uploadfile);
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
		$footer_file_data = common::insert_sys_image($uploadfile);
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

    if($system_setup->update($data)){

    	/***********write to GA code file*************/
    	$ga_content = stripcslashes($_POST['google_code']);
			file_put_contents(GA_PATH.'/ga.js',$ga_content);
			/***********write to GA code file*************/

      /***********write to css file*************/
      if(!empty($header_file_data['path'])){
      	$header_image = $header_file_data['path'];
      }
      if(!empty($_POST['header_image'])){
        $header_image = $fs->valid($_POST['header_image'],'path');
      }

      if(!empty($footer_file_data['path'])){
      	$footer_image = $footer_file_data['path'];
      }
      if(!empty($_POST['header_image'])){
        $footer_image = $fs->valid($_POST['footer_image'],'path');
      }

      if(!empty($header_image) && !empty($data['bs_header_height'])){
      	unset($css);
      	$css = array();
      	$css['height']=$data['bs_header_height'];
      	$css['image']='/'.$header_image;
      	$ConfigManager->SetCSS('header',$css);
      }

      if(!empty($footer_image) && !empty($data['bs_footer_height'])){
      	unset($css);
      	$css = array();
      	$css['height']=$data['bs_footer_height'];
      	$css['image']='/'.$footer_image;
      	$ConfigManager->SetCSS('footer',$css);
      }
			$ConfigManager->SaveCSS('sys');
      /***********write to css file*************/

      /***********write to json file*************/
      unset($json);
      $json=array();
      $json['headerlink']=$data['bs_header_link'];
      $json['footer']=$data['bs_footer_content'];
      $ConfigManager->SaveJSON('sys',$json);
      /***********write to json file*************/

      /***********write to config file*************/
			unset($check);
			unset($define);
		
			$check = array();
			$check['TITLE']=true;
			$check['FOOTER_TEXT']=true;
			$check['HEADER_LINK']=true;
		
			$define=array();
			$define['TITLE'] = $data['bs_title'];
			$define['FOOTER_TEXT'] = $data['bs_footer_content'];
			$define['HEADER_LINK'] = $data['bs_header_link'];
			$define['WIZARD'] = 'false';
			$define['FUNCTION_WRITER'] = $data['b_writer'];
			$define['FUNCTION_LINK'] = $data['b_link'];
			$define['FUNCTION_IMGLINK'] = $data['b_imglink'];
			$ConfigManager->SaveDefine('sys',$define,$check);
	  	/***********write to config file*************/

			$status->go('sys_setup.php','success',LANG_MESSAGE_UPDATE_SUCCESS);
  	}
  	else
  		$status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'do_update_ip':
    $data['external_ip'] = $fs->valid($_POST['external_ip'],'ip');
    if($system_setup->update($fs->sql_safe($data))){
    /***********write to config file*************/
    $status->go('sys_setup.php','success',LANG_MESSAGE_UPDATE_SUCCESS);
  }
  else
    $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'edit':
  default:
		$data = $system_setup->getList();
  	$data = $data[0];
		$files = new files($db);
		if($data['bs_header']>0){
			$header_image_arr = $files->getByID($data['bs_header']);
			$tpl->assign('path_header_image',WEB_URL."/".$header_image_arr['f_path']);
			$tpl->assign('header_image',$header_image_arr['f_path']);
		}
		if($data['bs_footer']>0){
			$footer_image_arr = $files->getByID($data['bs_footer']);
			$tpl->assign('path_footer_image',WEB_URL."/".$footer_image_arr['f_path']);
			$tpl->assign('footer_image',$footer_image_arr['f_path']);
		}
    //$data[0]['google_code'] = htmlspecialchars_decode($data[0]['google_code']);
  	$tpl->assign('data',$data);
  	$tpl->display('backend/sys_setup.tpl');
  	break;
}
