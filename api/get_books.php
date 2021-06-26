<?php
  require_once dirname(__FILE__).'/../init/config.php';
  $init = new init('db','tpl','filter','inputxss','ejson');

  global $bs_code;
  $cmd = $fs->valid($_POST['cmd'],'cmd');
  $bs_code = (int) $fs->valid($_POST['bs'],'id');
  $u_id = $fs->valid($_POST['u_id'],'num');
  $main_category_id = (int) $fs->valid($_REQUEST['mcid'],'id');
  $category_id = (int) $fs->valid($_POST['cid'],'id');
  $bu_id = $fs->valid($_POST['buid'],'num');
  $scid = (int) $fs->valid($_REQUEST['scid'],'id');

  $ConfigManager = new ConfigManager(0,$bs_code);
  $_path = $ConfigManager->getDefineUserbase();
  if(!empty($_path)){
  	include_once $_path;
  }
  include_once $ConfigManager->getDefineSyspath();
  $book = new book(&$db);
  $cols = array('c_id','c_name','b_id','b_name','b_description','webbook_link','ibook_link','b_top','b_order','bs_id','webbook_show','ibook_show','f_path');

  if($bu_id==-1){
		$bu_id = $_SESSION['buid'];
		$book->reset();
		$book->setBookStatus('private');
		$book->setBUID($bu_id);
		$book->setBSID($bs_code);
		$book->setCols($cols);
		if(!empty($scid)) $book->setCategory($scid);
		$all_data = $book->getList('b_order desc,b_id desc',0,0,'');
		$all_data = $all_data['result'];
		foreach($all_data as $key=>$val)
		{
			$all_data[$key]['f_path'] = HostManager::getBookshelfBase(false,false,$u_id,$bs_code).$all_data[$key]['f_path'];
		  $all_data[$key]['webbook_link'] = WEBBOOK_STATUS;
		  $all_data[$key]['webbook_show'] = WEBBOOK_STATUS && $all_data[$key]['webbook_show'];
		  $all_data[$key]['ibook_link'] = IBOOK_STATUS;
		  $all_data[$key]['ibook_show'] = IBOOK_STATUS && $all_data[$key]['ibook_show'];
		}
  }else if(!empty($bs_code)){
		//$new_data = $book->getPublicList($category_id,'b_order desc,b_id desc','','','new');  
  	$book->reset();
  	if(empty($scid) && empty($category_id)){
  		$book->setBookStatus('noauth');
  	}else{
  		$book->setBookStatus('public');
  	}
		$book->setType('new');
		$book->setBSID($bs_code);
  	$book->setCols($cols);
		$new_data = $book->getList('b_order desc,b_id desc',0,0,'');
		$new_data = $new_data['result'];

  	$book->reset();
  	if(empty($scid) && empty($category_id)){
  		$book->setBookStatus('noauth');
  	}else{
  		$book->setBookStatus('public');
  	}
		$book->setType('old');
		$book->setBSID($bs_code);
		$book->setCols($cols);
		if(!empty($scid)){
  		$book->setCategory($scid);
  	}else if(!empty($category_id)){
  		$book->setCategory($category_id);
  	}
		$all_data = $book->getList('b_order desc,b_id desc',0,0,'');
		$all_data = $all_data['result'];

    if(MEMBER){
      foreach($new_data as $key=>$val)
      {
				$new_data[$key]['f_path'] = HostManager::getBookshelfBase(false,false,$u_id,$bs_code).$new_data[$key]['f_path'];
        $new_data[$key]['webbook_link'] = WEBBOOK_STATUS;
        $new_data[$key]['webbook_show'] = WEBBOOK_STATUS && $new_data[$key]['webbook_show'];
        $new_data[$key]['ibook_link'] = IBOOK_STATUS;
        $new_data[$key]['ibook_show'] = IBOOK_STATUS && $new_data[$key]['ibook_show'];
      }

      foreach($all_data as $key=>$val)
      {
				$all_data[$key]['f_path'] = HostManager::getBookshelfBase(false,false,$u_id,$bs_code).$all_data[$key]['f_path'];
        $all_data[$key]['webbook_link'] = WEBBOOK_STATUS;
        $all_data[$key]['webbook_show'] = WEBBOOK_STATUS && $all_data[$key]['webbook_show'];
        $all_data[$key]['ibook_link'] = IBOOK_STATUS;
        $all_data[$key]['ibook_show'] = IBOOK_STATUS && $all_data[$key]['ibook_show'];
      }
    }else{
			foreach($new_data as $key=>$val)
			{
				$new_data[$key]['f_path'] = HostManager::getBookshelfBase(false,false,$u_id,$bs_code).$new_data[$key]['f_path'];
				if(WEBBOOK_STATUS && ($new_data[$key]['webbook_show']=='1')){
					$link = str_replace(HttpLocalIPPort,'',$new_data[$key]['webbook_link']);
					$link = str_replace(LocalHost, '', $link);
					$new_data[$key]['webbook_link']=$link;
				}else{
			  	$new_data[$key]['webbook_show']=0;
					$new_data[$key]['webbook_link'] = '';
				}
			  if(IBOOK_STATUS && ($new_data[$key]['ibook_show']=='1')){
					$link = str_replace(HttpLocalIPPort,'',$new_data[$key]['ibook_link']);
					$link = str_replace(LocalHost, '', $link);
					$new_data[$key]['ibook_link']=$link;
				}else{
			  	$new_data[$key]['ibook_show']=0;
			    $new_data[$key]['ibook_link'] = '';
				}
      }
      foreach($all_data as $key=>$val)
      {
				$all_data[$key]['f_path'] = HostManager::getBookshelfBase(false,false,$u_id,$bs_code).$all_data[$key]['f_path'];
			  if(WEBBOOK_STATUS && ($all_data[$key]['webbook_show']=='1')){
					$link = str_replace(HttpLocalIPPort,'',$all_data[$key]['webbook_link']);
					$link = str_replace(LocalHost, '', $link);
					$all_data[$key]['webbook_link']=$link;
				}else{
					$all_data[$key]['webbook_show']=0;
					$all_data[$key]['webbook_link']='';
				}
		  	if(IBOOK_STATUS && ($all_data[$key]['ibook_show']=='1')){
					$link = str_replace(HttpLocalIPPort,'',$all_data[$key]['ibook_link']);
					$link = str_replace(LocalHost, '', $link);
					$all_data[$key]['ibook_link']=$link;
				}else{
					$all_data[$key]['ibook_show']=0;
					$all_data[$key]['ibook_link'] = '';
				}
      }
    }

  }

  $data['newbook'] = $new_data;
  //$data['oldbook'] = $old_data;
  $data['allbook'] = $all_data;

	$output = new Services_JSON();
  $json = new Services_JSON(SERVICES_JSON_ESCAPED_UNICODE);
	if($cmd=='valid'){
		$data = $json->encode($data);
		$data = str_replace('\/','/',$data);
		echo md5($data);exit;
	}else{
		header('Content-Type: application/json; charset=utf-8');
		echo $output->encode($data);exit;
	}
  /*$tpl->assign('data',$data['result']);
  $tpl->display('index.tpl');*/
?>
