<?php
require_once dirname(__FILE__).'/../config.php';
$init = new init('db','auth','bookshelf_auth','tpl','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';

$db_process = new db_process(&$db,'books','b_');
$id = (int) $_GET['id'];
$page = (int) $_GET['page'];
$page = ($page==0)?1:$page;
//get books info
$book_info = $db_process->getById($id);
global $bs_code;
$bookshelf = new bookshelf(&$db,'bookshelfs');
$rsb = $bookshelf->getByID($bs_code);
//echo 'http://'.LocalIP.$rsb['ecocat_api'];exit;
$xml = simplexml_load_file(HttpLocalIPPort.$rsb['ecocat_api']); 

//$xml = simplexml_load_file($rsb['ecocat_api']); 
//$xml = simplexml_load_file(ECOCAT_API_URL);
//$xml = simplexml_load_file('ecocat.xml');

if(empty($xml) || !empty($xml->error)){
  echo "get ecocat data failed";
  exit;
}
$json = json_encode($xml);
$array = json_decode($json,TRUE);

//only one book in ecocat xml
if(!empty($array['detail']['process_id'])){
  $ecocat[] = $array['detail'];
}else{
  $ecocat = $array['detail'];
}
foreach($ecocat as $key=>$val)
{  
  if($val['process_id']==$book_info['ecocat_id']){
    $data['file_id'] = $book_info['file_id'];
    /*
    //更新書籍資料
    if(!$book->update($db_ecocat_book_arr[$val[process_id]]['b_id'],$fs->sql_safe($data)))
      echo "update book infomation failed";
    */

    /***********file upload*************/
    //download image
    $uploadfile = ROOT_PATH.'/'.FILE_UPLOAD_PATH.'tmp_image';
    $resize = array();
    $resize['m_'] = array('w'=>200,'h'=>260);
    $resize['s_'] = array('w'=>120,'h'=>120);
    $val = common::insert_host_image($uploadfile,$data['file_id'],$resize);
    if($val['id']) $data['file_id'] = $val['id'];
    @unlink($uploadfile);
    /***********file upload*************/

      $status->go('book.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);

  }
  
}
$status->back('error',LANG_WARNING_NO_BOOKS);

