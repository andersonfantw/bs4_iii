<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';

$type = $fs->valid($_GET['type'],'cmd');
$book = new book($db);
$game_reflection = new game_reflection($db);
if($type=='do_add' || $type=='do_update'){
  $data['b_name'] = $fs->valid($_POST['b_name'],'name');
  $data['b_key'] = $fs->valid($_POST['b_key'],'key');
  $data['webbook_link'] = $fs->valid($_POST['webbook_link'],'url');
  $data['webbook_show'] = (int)$fs->valid($_POST['webbook_show'],'bool');
  $data['ibook_link'] = $fs->valid($_POST['ibook_link'],'url');
  $data['ibook_show'] = (int)$fs->valid($_POST['ibook_show'],'bool');
  $data['c_id'] = implode(',',$fs->valid($_POST['c_id'],'idarray'));
  $data['b_order'] = (int) $fs->valid($_POST['b_order'],'num');
  $data['b_top'] = (int) $fs->valid($_POST['b_top'],'bool');
  $data['b_status'] = $fs->valid($_POST['b_status'],'bool');	
  $data['b_description'] = $fs->valid($_POST['b_description'],'content');
  $data['file_id'] = (int) $fs->valid($_POST['file_id'],'key');
  $data['bs_id'] = $fs->valid($bs_code,'id');
  $data['webbook_show'] = empty($data['webbook_link'])?0:$data['webbook_show'];
  $data['ibook_show'] = empty($data['ibook_link'])?0:$data['ibook_show'];
  $icons_data = $fs->valid($_POST['icons_data'],'content');
}
$id = (int) $fs->valid($_REQUEST['id'],'id');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;

if($type=='do_add' || $type=='do_update')
{
  /***********file upload*************/

  //$_FILES array process
  $uploadfiles = array();
  if(count($_FILES['img']['tmp_name'])>1){
    foreach ($_FILES['img'] as $k => $l){
      foreach ($l as $i => $v){
        if (!array_key_exists($i, $uploadfiles))
          $uploadfiles[$i] = array();
        $uploadfiles[$i][$k] = $v;
      }
    }
  }else{
    $uploadfiles[0] = $_FILES['img'];
  }

  foreach($uploadfiles as $key=>$uploadfile)
  {
  	$resize = array();
  	$resize['m_'] = array('w'=>200,'h'=>260);
  	$resize['s_'] = array('w'=>120,'h'=>120);
  	$val = common::insert_host_image($uploadfile,$data['file_id'],$resize);
  	if($val['id']) $data['file_id'] = $val['id'];
  }

  /***********file upload*************/

  /***********link icon upload*************/

  //$_FILES array process
  $uploadfiles = array();
  $iconlinks = array();
  if(count($_FILES['icon']['tmp_name'])>1){
    foreach ($_FILES['icon'] as $k => $l){
      foreach ($l as $i => $v){
        if (!array_key_exists($i, $uploadfiles)){
          $uploadfiles[$i] = array();
          $iconlinks[$i] = array();
        }
        $uploadfiles[$i][$k] = $v;
        $iconlinks[$i] = $fs->valid($_POST['imglink'][$i],'url');
      }
    }
  }else{
    $uploadfiles[0] = $_FILES['icon'];
    $iconlinks[0] = $fs->valid($_POST['imglink'],'url');
  }
  $upload_icons = '';
  for($i=0; $i<count($uploadfiles);$i++)
  {
		$uploadfile = $uploadfiles[$i];
  	$resize = array();
  	$resize['i_'] = array('w'=>300,'h'=>300);
  	$val = common::insert_host_image($uploadfile,0,$resize);
  	if($val['id']) $upload_icons .= HostManager::getBookshelfBase(true,false) . '/uploadfiles/i_' . $val['name'] . ','.$iconlinks[$i].';';
  }

  /***********link icon upload*************/

}


switch ($type) {
  case 'add':
    $category = new category($db);
    $cate_data = $category->getCategoryStructure();
    $tpl->assign('category',$cate_data);
    $tpl->display('backend/book_edit.tpl');
    break;
  case 'edit':
    $bookshelf = new bookshelf($db);
    $data_setup = $bookshelf->getByID($bs_code);

    $book_category = $book->getCategoryByBID($id);
    $category = new category($db);
    $cate_data = $category->getCategoryStructure($book_category);

    $data = $book->getByID($id);
    $data['webbook_link'] = htmlspecialchars_decode($data['webbook_link']);
    $data['ibook_link'] = htmlspecialchars_decode($data['ibook_link']);
    $data['is_webbook'] = (int)$data_setup['is_webbook'];
    $data['is_ibook'] = (int)$data_setup['is_ibook'];
    $data['b_description']=htmlspecialchars_decode($data['b_description']);

		$cowriter_pattern = '/<input type="hidden" class="writer_data" value="(?<writer_data>[^\"]*)" \/><input type="hidden" class="cowriter_data" value="(?<cowriter_data>[^\"]*)" \/>/';
		$link_pattern = '/<input type=\"hidden\" class=\"l_data\" value=\"(?<link_data>[^\"]*)\" \/>/';
		$icons_pattern = '/<input type=\"hidden\" class=\"icons_data\" value=\"(?<icons_data>[^\"]*)\" \/>/';
	
		//取得圖示連結
		$matches = null;
		$icons_html = '';
		preg_match($icons_pattern,$data['b_description'],$matches);
		$data['icons_data'] = $matches['icons_data'];	//之前儲存的icons, $upload_icons 是上傳的icons
	
		//取得作者及共同作者
		$matches = null;
		preg_match($cowriter_pattern,$data['b_description'],$matches);
		$data['writer_data'] = $matches['writer_data'];
		$data['cowriter_data'] = $matches['cowriter_data'];

		//取得連結
		$matches = null;
		preg_match($link_pattern,$data['b_description'],$matches);
		$data['link_data'] = $matches['link_data'];

    $arr = preg_split('/<hr \/><br \/>/i',$data['b_description']);
		if(count($arr)>0){
			$data['b_description'] = $arr[count($arr)-1];
		}
		$data['b_description'] = $icons_html . $data['b_description'];

    $cover_image = HostManager::getBookshelfBase(true,false).'/'.$data['f_path'];
    $tpl->assign('cover_image',$cover_image);

    $tpl->assign('data',$data);
    $tpl->assign('category',$cate_data);
    $tpl->display('backend/book_edit.tpl');
    break;
  case 'do_add':
  	if(REFLECTION_GAME){
			$isGameReflectionBS = $game_reflection->isGameReflectionBS($bs_code);
			if($isGameReflectionBS){
		    $nextseq = $game_reflection->getNextMapSeq($bs_code);
		    if($nextseq==0){
		        echo '<script>alert("Cannot add more books!");history.back(-1);</script>';
		        exit;
		    }
	  	}
  	}

    $b_id = $book->insert($fs->sql_safe($data));
    if($b_id){
    	if($isGameReflectionBS && REFLECTION_GAME){
    		$game_reflection->insert_bookref($bs_code,$b_id,$nextseq);
    	}
      $status->go('book.php','success',LANG_MESSAGE_ADD_SUCCESS);
    }else{
      $status->back('error',LANG_ERROR_ADD_FAIL);
    }
    break;
  case 'do_update':
		$icons_html = '';
    //icons_data 先前存的icon link
    //upload_icons 本次上傳的icon link
		$icons_replacpattern = '/(<a [^>]*><img [^>]*\/><\/a><br \/>)+<input [^>]*\/><hr \/><br \/>/';
		$data['b_description'] = preg_replace($icons_replacpattern,'',$data['b_description']);
		if(strlen($upload_icons)>0){
			$icons_data .= ((strlen($icons_data)>0)?';':'').substr($upload_icons,0,-1);
		}
		$arr_icons_data = preg_split('/;/i',$icons_data);
		if($icons_data!=''){
			foreach($arr_icons_data as $val){
				$arr = preg_split('/,/i',$val);
				$icons_html .= htmlspecialchars('<a target="_blank" href="'.$arr[1].'"><img height="100" src="'.$arr[0].'" /></a><br />');
			}
			if($icons_data!=''){
				$icons_html .= htmlspecialchars('<input type="hidden" class="icons_data" value="'.$icons_data.'" /><hr /><br />');
				$data['b_description'] = $icons_html . $data['b_description'];
			}
		}
    if($book->update($id,$fs->sql_safe($data)))
      $status->go('book.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'delete':
  		$game_reflection_check = REFLECTION_GAME;
  		if(REFLECTION_GAME){
    		if(!$game_reflection->isCommonForOwner($bs_code) && !$game_reflection->isCommonByBook($id)){
    			$game_reflection_check = false;
    		}
    	}

			if(!$game_reflection_check){
				$BookManager = new BookManager();
		    if($BookManager->del($id)){
	      	$status->go('book.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
		    }else
	      	$status->back('error',LANG_ERROR_DELETE_FAIL);
      }else{
      	$status->back('error',LANG_ERROR_DELETE_FAIL_HAS_GAME_REFLECTION_GAME_COMMON);
      }

    break;
  case 'do_update_users_bookshelf':
    $bu_array = $fs->valid($_POST['bu_id'],'idarray');
    if($book->update_users_bookshelf($fs->sql_safe($bu_array),$id))
      $status->go('book.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'users_bookshelf':    
    $book_users_bookshelf = $book->getUsersBookshelfByBID($id);
    $bookshelf_user = new bookshelf_user($db);
    $bookshelf_user_data = $bookshelf_user->getBookshelfUserStructure($book_users_bookshelf);
    $data = $book->getByID($id);

    $tpl->assign('data',$data);
    $tpl->assign('bookshelf_user',$bookshelf_user_data);
    $tpl->display('backend/book_users_bookshelf.tpl');
    break;
  case 'list':
  case 'search':
  case 'search_top10':
  default:    
    require_once LIBS_PATH.'/page.class.php';
    $host_base = HostManager::getBookshelfBase(true,false);
    $category = new category($db);
    $cate_data = $category->getCategoryStructure();
    if(REFLECTION_GAME){
			$gr_data = $game_reflection->getCommonList($bs_code, 0, 'gr_id desc');
	    foreach($gr_data['result'] as $key=>$val){
  	      $game_data[$val['b_id']]=true;
    	}
    }
		if(!empty($cate_data)){
			foreach($cate_data as $cate){		
			  $cate_arr[$cate[c_id]]=$cate['c_name'];
			}
		}
		switch($type){
			case 'search':
			case 'search_top10':
				$q_str = $fs->valid($_POST['q'],'query');
				$q_tag = $fs->valid($_POST['tagid'],'idarray');
				$book->reset();
				$book->setBookStatus('all');
				$book->setBSID($bs_code);
				$book->setKeyword($q_str);
				$book->setTag($q_tag);
				break;
			default:
				$book->reset();
				$book->setBookStatus('all');
				$book->setBSID($bs_code);
				break;
		}
		$data = $book->getList('b_id desc',($page-1)*PER_PAGE,PER_PAGE,'');
		foreach($data['result'] as $key => $value){
			$arr = preg_split('/<hr \/><br \/>/i',$value['b_description']);

			$pattern = '<input type=\"hidden\" class=\"writer_data\" value=\"(?<writer_data>[^\"]*)\" \/\>\<iput type=\"hidden\" class=\"cowriter_data\" value=\"(?<cowriter_data>[^\"]*)\" />';
			preg_match($pattern,$arr[0],$matches);
			$data['result'][$key]['writer_data'] = $matches[writer_data];
			$data['result'][$key]['cowriter_data'] = $matches[cowriter_data];
		}

		switch($type){
			case 'search_top10':			
				$tpl->assign('host_base',$host_base);
		    $tpl->assign('data',$data['result']);
		    $tpl->display('backend/book_list_data.tpl');
		    exit;
				break;
			case 'search':
				$url = 'book.php?type=search&q='.$q_str;
			default:
		    $record=$data['total'];
		    if($record>PER_PAGE){
		      $pagebar=new page(PER_PAGE,$page,$record,$url);
		      $tpl->assign('pagebar',$pagebar);
		    }
			
		    $bookshelf = new bookshelf($db,'bookshelfs');
		    $bookshelf_rs = $bookshelf->getByID($bs_code);    
		    $tpl->assign('bookshelf_data',$bookshelf_rs);
		    $tpl->assign('data',$data['result']);
		    $tpl->assign('host_base',$host_base);
		    $tpl->assign('game_data',$game_data);
		    $tpl->assign('book_list_data_html', $tpl->fetch('backend/book_list_data.tpl'));
		    $tpl->display('backend/book_list.tpl');
				break;
		}
    break;
}
