<?php
require_once dirname(__FILE__).'/../config.php';
/* require_once dirname(__FILE__).'/../api/nas.php'; */
require_once('Crypt/Blowfish.php');

$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');
$type = $fs->valid($_GET['type'],'cmd');
$db_process = new db_process(&$db,'bookshelfs','bs_');
$bookshelf = new bookshelf(&$db,'bookshelfs');
$bookshelf_share = new bookshelf_share(&$db,'bookshelf_share');
$bookshelf_share_source = new bookshelf_share_source(&$db,'bookshelf_share_source');
$game_reflection = new game_reflection(&$db);

if($type=='do_add' || $type=='do_update'){
  $id = (int) $fs->valid($_POST['id'],'id');
  $game_data['gr_name'] = $fs->valid($_POST['gr_name'],'name');
  $game_data['gr_width'] = (int) $fs->valid($_POST['gr_width'],'num');
  $game_data['gr_height'] = (int) $fs->valid($_POST['gr_height'],'num');
  $game_data['gr_map'] = (int) $fs->valid($_POST['map_img_id'],'id');

  //header image
  $uploadfile = $_FILES['map_img'];
  $resize = array();
  $resize['map_'] = array('w'=>800,'h'=>800);
  $val = common::insert_host_image($uploadfile,$game_data['gr_map'],$resize);
  if($val['id']) $game_data['gr_map'] = $val['id'];
}

$id = (int) $fs->valid($_REQUEST['id'],'id');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;


switch ($type) {
  case 'add':
    $tpl->display('backend/sys_game_edit.tpl');
    break;
  case 'edit':
    $game = new db_process(&$db,'game_reflection','gr_');
    $data = $game->getList('gr_id desc',0,0,'gr_id='.$id);
    $tpl->assign('data',$data['result'][0]);
    $tpl->display('backend/sys_game_edit.tpl');
    break;
  case 'do_add':
    $data['is_member'] = 1;
    $data['is_webbook'] = 1;
    $data['is_ibook'] = 0;
    $data['is_allbook'] = 0;
    $data['is_newbook'] = 0;
    switch(MEMBER_SYSTEM){
    	case 'nas':
    	case 'api':
	      $tmp_nasUserInfo = explode('|', $_POST['u_id']); //$_POST['u_id'] is 'u_name|u_cname'
	      $nasUserInfo=array();
	      $nasUserInfo[0] = $fs->valid($tmp_nasUserInfo[0],'acc');
	      $nasUserInfo[1] = $fs->valid($tmp_nasUserInfo[1],'name');
	      $u_id = $bookshelf->db->query_first('select u_id from bookshelf2_account where u_name="'.$bookshelf->db->escape($nasUserInfo[0]).'"');
	
	      if(!$u_id){
	        $bookshelf->db->query_insert(
	          'bookshelf2_account',
	          Array(
	            'u_cname' => $nasUserInfo[1],
	            'u_name' => $nasUserInfo[0],
	            'u_password' => uniqid()
	          )
	        );
	        $u_id = $bookshelf->db->query_first('select u_id from bookshelf2_account where u_name="'.$bookshelf->db->escape($nasUserInfo[0]).'"');
	      }
	
	      $u_id = $u_id['u_id'];
	      break;
	    default:
	    	$account = new db_process(&$db,'account','u_');
	    	$rs=$account->getList('',0,0,'u_name="event"');
	    	if($rs){
	    		$u_id = $rs['result'][0]['u_id'];
	    	}else{
  				$data['u_name'] = 'event';
  				$data['u_cname'] = 'event';
	    		$data['u_password'] = base64_encode('event');
	    		$rs = $account->insert($fs->sql_safe($data),true);
	    	}
	    	break;
    }

		$data['bs_name'] = $game_data['gr_name'];
    $data['bs_title'] = $game_data['gr_name'];
    $bs_id = $bookshelf->insert($fs->sql_safe($data),$u_id);
    $game_data['bs_id'] = $bs_id;
    $gr_id = $game_reflection->insert($fs->sql_safe($game_data));
    if($bs_id){
      file_put_contents(CSS_PATH.'/'.$bs_id.'/puzzle.css',$puzzle_css);
      /*******create share**********/
      $bss_data['bss_ip'] = LocalIP;
      $bss_data['bss_account'] = 'share';
      $bss_data['bss_password'] = 'share';
      $bss_data['bs_id'] = $bs_id;
      $rs = $bookshelf_share->insert($fs->sql_safe($bss_data));

      $bsss_data['bsss_name'] = $data['bs_name'];
      $bsss_data['bsss_source'] = HttpLocalIPPort.DATA_SOURCE_PATH.'datasource.php?bs='.$bs_id;
      $bsss_data['bsss_account'] = 'share';
      $bsss_data['bsss_password'] = 'share';
      $bsss_data['bsss_status'] = 200;
      $rs = $bookshelf_share_source->insert($fs->sql_safe($bsss_data));
      /*******create share**********/

      /***********write to css file*************/
      mkdir(CSS_PATH.'/'.$bs_id,0777);
      file_put_contents(CSS_PATH.'/'.$bs_id.'/bs'.$bs_id.'_config.css','');
      /***********write to css file*************/

      /***********write to json file*************/
      if(CSS_PATH!=JSON_PATH){
				mkdir(JSON_PATH.'/'.$bs_id,0777);
      }
      $json_content = "{\r\n";
      $json_content .= '"headerlink":""\r\n';
      $json_content .= '"footer":""\r\n';
      $json_content .= "}";
      file_put_contents(JSON_PATH.'/'.$bs_id.'/bs'.$bs_id.'_config.json',$json_content);
      /***********write to json file*************/

      /***********write to config file*************/
      if(CSS_PATH!=HOST_PATH && JSON_PATH!=HOST_PATH ){
        mkdir(HOST_PATH.'/'.$bs_id,0777);
      }
      $config_content = "<?php\r\n";
      $config_content .= "define('IBOOK_STATUS',0);\r\n";
      $config_content .= "define('WEBBOOK_STATUS',1);\r\n";
      $config_content .= "define('MEMBER',1);\r\n";
      $config_content .= "define('NEWBOOK',0);\r\n";
      $config_content .= "define('ALLBOOK',0);";
      file_put_contents(HOST_PATH.'/'.$bs_id.'/bs'.$bs_id.'.cfg',$config_content);
      /***********write to config file*************/

      /*******get ecocat api url**********/
      if(CONNECT_ECOCAT){
        $account = new db_process(&$db,'account','u_');
        $account_data = $account->getByID($u_id);

        $cbf = new Crypt_Blowfish(ENCRYPT_KEY);
        $crypt = base64_decode($account_data['u_password']);
        $crypt = $cbf->encrypt($crypt);
        $crypt = base64_encode($crypt);

        $rows = $dbr->call_sp("call CreateAPI('','{$account_data['u_cname']}','{$account_data['u_name']}','{$crypt}',{$bs_id},'http://127.0.0.1');");

        $data_update['ecocat_api'] = $rows[0];
      }
      /*******get ecocat api url**********/

      $bookshelf->update($bs_id,$fs->sql_safe($data_update),$u_id);
      $status->go('sys_game.php','success',LANG_MESSAGE_ADD_SUCCESS);
    }else
      $status->back('error',LANG_ERROR_ADD_FAIL);
    break;
  case 'do_update':

    if($game_reflection->update($id,$fs->sql_safe($game_data))){
        $status->go('sys_game.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
    }
    else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'delete':
    if($game_reflection->del($id))
      $status->go('sys_game.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'list':
  default:
    require_once LIBS_PATH.'/page.class.php';

    $data = $game_reflection->getSummeryList('gr_id desc',($page-1)*PER_PAGE,PER_PAGE);
    $record=$data['total'];

    if($record>PER_PAGE){
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }
    $tpl->assign('data',$data['result']);
    $tpl->assign('game_list_data_html', $tpl->fetch('backend/sys_game_list_data.tpl'));
    $tpl->display('backend/sys_game_list.tpl');
    break;
}
