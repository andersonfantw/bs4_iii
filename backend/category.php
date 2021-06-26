<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';

$group = new group(&$db);

$type = $fs->valid($_GET['type'],'cmd');
$category = new category(&$db);

if($type=='do_add' || $type=='do_update'){
	$data['c_name'] = $fs->valid($_POST['c_name'],'name');
	$data['c_description'] = $fs->valid($_POST['c_description'],'content');
	$data['c_parent_id'] = (int) $fs->valid($_POST['c_parent_id'],'id');
	$data['c_order'] = (int) $fs->valid($_POST['c_order'],'num');
	$data['bs_id'] = $fs->valid($bs_code,'id');
}

if($type!='delete')
{
  $parent_data = $category->getList('c_order desc,c_id desc','','','c_parent_id = 0 and bs_id='.$bs_code);
}

$id = (int) $fs->valid($_REQUEST['id'],'id');
$pid = (int) $fs->valid($_REQUEST['pid'],'id');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;
switch ($type) {
  case 'add':
	$tpl->assign('parent_data',$parent_data['result']);
	$tpl->display('backend/category_edit.tpl');
    break;
  case 'edit':
	$data = $category->getByID($id);
	$tpl->assign('data',$data);
	$tpl->assign('parent_data',$parent_data['result']);
	$tpl->display('backend/category_edit.tpl');
    break;
  case 'do_add':	
    if($category->insert($fs->sql_safe($data)))
		$status->go('category.php','success',LANG_MESSAGE_ADD_SUCCESS);
	else
		$status->back('error',LANG_ERROR_ADD_FAIL);
    break;
  case 'do_update':
    if($category->update($id,$fs->sql_safe($data)))
		$status->go('category.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
	else
		$status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'delete':
  	$data = $category->getList('c_order desc,c_id desc',($page-1)*PER_PAGE,PER_PAGE,'c_parent_id = '.$id.' and bs_id='.$bs_code);
  	if($data['total']==0){
	    if($category->del($id)){
	    	if(isset($pid)){
	    		$status->go('category.php?pid='.$pid,'success',LANG_MESSAGE_DELETE_SUCCESS);
	    	}else{
					$status->go('category.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
				}
			}else
				$status->back('error',LANG_ERROR_DELETE_FAIL);
		}else{
			$status->back('error',LANG_ERROR_DELETE_HAVE_INSITE_ITEM);
		}
    break;
  case 'list':
  default:    
	require_once LIBS_PATH.'/page.class.php';	

  foreach($parent_data['result'] as $val)
  {
    $parent_arr[$val[c_id]] = $val['c_name'];
  }
	$data = $category->getList('c_order desc,c_id desc',($page-1)*PER_PAGE,PER_PAGE,'c_parent_id = '.$pid.' and bs_id='.$bs_code);
	$record=$data['total'];
	if($record>PER_PAGE){
		if(!empty($pid)){
			$url = 'category.php?pid='.$pid;
		}
		$pagebar=new page(PER_PAGE,$page,$record,$url);
		$tpl->assign('pagebar',$pagebar);
	}
  foreach($data['result'] as $key=>$val)
  {
    $data['result'][$key]['c_parent_name'] = ($val['c_parent_id']==0)?'無':$parent_arr[$val['c_parent_id']];
  }
	$tpl->assign('data',$data['result']);
  $tpl->display('backend/category_list.tpl');
  break;
}
?>