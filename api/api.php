<?PHP
require_once dirname(__FILE__).'/../init/config.php';
//$init = new init('db','auth','bookshelf_auth','filter');
$init = new init('db','filter','ejson');
$tag = new tag($db);
$system_tag = new system_tag($db);
$scanexam_test_tag = new scanexam_test_tag($db);

$cmd = $fs->valid($_REQUEST['cmd'],'cmd');
$uid=intval($_SESSION['adminid']);
switch($cmd){
	case 'getBookshelfList':
	case 'validBookshelfList':
		$isExpiredList = $fs->valid($_POST['isExpiredList'],'bool');
		$buid = $fs->valid($_POST['buid'],'num');
		$uid = $fs->valid($_POST['uid'],'num');
		$data = array();
		$_doExpiredList=false;
		if($isExpiredList && $buid>0 && LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::Regist)){
			$_doExpiredList=true;
			$sql =<<<SQL
select v.*,
	i.val as expiredlink,
	a.ac_term,
	a.registdate,
	TIMESTAMPADD('M',ac_term,registdate) as enddate,
	a.trial
from BOOKSHELF2_VIEW_ACTIVECODE_EXPIRED a
join bookshelf2_view_bookshelfdetail v on(v.bs_id=a.bs_id and a.bu_id=%u)
left join bookshelf2_ini i on(concat('bookshelf',CAST(v.bs_id as VARCHAR(11)))=i.group and i.key='expiredlink')
where TIMESTAMPDIFF('m',timestampadd('M',ac_term,registdate),now())>0
SQL;
			$sql = sprintf($sql, $buid);
			$data['result'] = $db->get_results($sql);
		}else{
			if($buid>0){
				if(LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::Regist)){
					$sql =<<<SQL
select * 
from bookshelf2_view_bookshelfdetail v
where v.bs_id in(select c.bs_id
	from BOOKSHELF2_CATEGORY c
	join BOOKSHELF2_GROUPS_CATEGORY gc on(c.c_id=gc.c_id)
	join bookshelf2_activecode_group ag on(ag.g_id=gc.g_id)
	join BOOKSHELF2_VIEW_ACTIVECODE_ACTIVELIST ac on(ag.ac_code=ac.ac_code and ac.bu_id=%u)
	where TIMESTAMPDIFF('m',timestampadd('M',ac_term,registdate),now())<0)
SQL;
				}else{
					$sql =<<<SQL
select * 
from bookshelf2_view_bookshelfdetail v
join bookshelf2_activecode a on(v.bs_id=a.bs_id and a.bu_id=%u)
where v.bs_id in(select bs_id
			from bookshelf2_view_group_users vgu
			where bu_id=%u);
SQL;
				}
				$sql = sprintf($sql, $buid, $buid);
				$data['result'] = $db->get_results($sql);
			}elseif($uid>0){
				$bookshelf = new bookshelf($db);
				$data = $bookshelf->getList('bs_id desc',0,0,'bs_status=1 and bs_list_status=1 and u_id='.$uid);
			}else{
				$bookshelf = new bookshelf($db,'bookshelfs');
				$data = $bookshelf->getList('bs_id desc',0,0,'bs_status=1 and bs_list_status=1');
			}
		}
		$data1 = array();
		foreach($data['result'] as $row){
			if($row['bs_header_image']=='' || $row['bs_header_height']==0){
				$_hasbanner=' nopic';
			}else{
				$_hasbanner='';
			}
			$_uname = str_replace(LDAP_DOMAIN_PREFIX,'',$row['u_name']);
			$info='';
			if($isExpiredList){
				$registdate = date('Y-m-d',strtotime($row['registdate']));
				$enddate = date('Y-m-d',strtotime($row['enddate']));
				$info = sprintf('%s ~ %s &nbsp;&nbsp;&nbsp;&nbsp;%u Monthes<br />',$registdate,$enddate,$row['ac_term']);
				if($row['trial']){
					$info .= LANG_BOOKSHELF_TRIAL_EXPIRED_HINT;
				}else{
					$info .= LANG_BOOKSHELF_EXPIRED_HINT;
				}
			}else{
				if($row['expiring']){
					if($row['trial']){
						$info = str_replace('@date@',$row['expireddate'],LANG_BOOKSHELF_TRIAL_GOING_EXPIRED_HINT);
					}else{
						$info = str_replace('@date@',$row['expireddate'],LANG_BOOKSHELF_GOING_EXPIRED_HINT);
					}
				}
			}
			if($_doExpiredList){
				$link = $row['expiredlink'];
			}else{
				$link = WEB_URL.'/'.$_uname.'/'.$row['bs_id'].'/';
			}
			if(!empty($row['bs_header_image'])){
				$img = '/hosts/'.$row['u_id'].'/'.$row['bs_id'].'/'.$row['bs_header_image'];
			}else{
				$img = '';
			}
			$data1[] = array(
				'hasbanner'=>$_hasbanner,
				'bs_id'=>$row['bs_id'],
				'bs_name'=>$row['bs_name'],
				'u_cname'=>$row['u_cname'],
				'link'=>$link,
				'img'=>$img,
				'info'=>$info
			);
		}
		switch($cmd){
			case 'getBookshelfList':
				header('Content-Type: application/json; charset=utf-8');
				echo json_encode($data1,TRUE);
				break;
			case 'validBookshelfList':
				$json = new Services_JSON(SERVICES_JSON_ESCAPED_UNICODE);
				$data1 = $json->encode($data1);
				$data1 = str_replace('\/','/',$data1);
				echo md5($data1);exit;
				break;
		}
		break;
	case 'getBookTag':
		$bid = $fs->valid($_POST['bid'],'id');

		$bid = intval($bid);
		$data = $tag->getTagsByBook($bid);
		$data = array_values($data);

		echo json_encode($data,TRUE);
		break;
	case 'getSuggestByDropDownList':
/*
	recent input(in an hour)
	this bookshelf suggest
	general suggest
*/
		$like = $fs->valid($_POST['like'],'name');
		$data = $tag->getSuggestByDropDownList($uid,$like);
		echo json_encode($data);
		break;
	case 'getSuggestByChoosePanel':
		$uid = bssystem::getUID();
		$bid = $fs->valid($_POST['bid'],'id');
		$like = $fs->valid($_POST['like'],'name');
		$path = $fs->valid($_POST['path'],'idarray');

		$bid = intval($bid);
		$data = $tag->getSuggestByChoosePanel($uid,$bid,$path,$like);
		echo json_encode($data);
		break;
	case 'getSuggestByChoosePanelByShortcut':
		$uid = bssystem::getUID();
		$tsid = $fs->valid($_POST['tsid'],'id');
		$seq = $fs->valid($_POST['seq'],'id');
		$like = $fs->valid($_POST['like'],'name');
		$path = $fs->valid($_POST['path'],'idarray');

		$bid = intval($bid);
		$data = $tag->getSuggestByChoosePanelByShortcut($uid,$tsid,$seq,$path,$like);
		echo json_encode($data);
		break;
	case 'getSuggestSystemTagByChoosePanel':
		$path = $fs->valid($_POST['path'],'idarray');
		$data = $tag->getSuggestSystemTagByChoosePanel($path);
		echo json_encode($data);
		break;
	case 'getBooksByTSID':
		$tsid = $fs->valid($_POST['tsid'],'id');
		$bsid = $fs->valid($_POST['bsid'],'id');
		$bu_id = $fs->valid($_POST['bu_id'],'id');

		$ConfigManager = new ConfigManager(0,$bsid);
	  $_path = $ConfigManager->getDefineUserbase();
	  if(!empty($_path)){
	  	include_once $_path;
	  }
		include_once $ConfigManager->getDefineSyspath();
		$bsid=bssystem::getBSID();
		$data = $tag->getBooksByTSID($bsid,$tsid,$bu_id);
		$shortcut = new shortcut($db);
		$data1 = $shortcut->getByID($tsid);
		$host_base = HostManager::getBookshelfBase();
		if(MEMBER){
		  foreach($data as $key=>$val)
		  {
				$data[$key]['f_path'] = $host_base.$data[$key]['f_path'];
		    $data[$key]['webbook_link'] = WEBBOOK_STATUS;
		    $data[$key]['webbook_show'] = WEBBOOK_STATUS && $data[$key]['webbook_show'];
		    $data[$key]['ibook_link'] = IBOOK_STATUS;
		    $data[$key]['ibook_show'] = IBOOK_STATUS && $data[$key]['ibook_show'];
		  }
		}else{
		  foreach($data as $key=>$val)
		  {
				$data[$key]['f_path'] = $host_base.$data[$key]['f_path'];
			  if(WEBBOOK_STATUS && ($data[$key]['webbook_show']=='1')){
					$data[$key]['webbook_link']=str_replace(LocalHost, '', $data[$key]['webbook_link']);
				}else{
					$data[$key]['webbook_show']=0;
					$data[$key]['webbook_link']='';
				}
		  	if(IBOOK_STATUS && ($data[$key]['ibook_show']=='1')){
					$data[$key]['ibook_link']=str_replace(LocalHost, '', $data[$key]['ibook_link']);
				}else{
					$data[$key]['ibook_show']=0;
					$data[$key]['ibook_link'] = '';
				}
		  }
		}
		$arr = array();
		$arr['name'] = $data1['ts_name'];
		$arr['result'] = $data;
		echo json_encode($arr);
		break;
	case 'setTag':
		$json_str_path = $fs->valid($_POST['path'],'idarray');
		$bid = $fs->valid($_POST['bid'],'id');
		$key = $fs->valid($_POST['key'],'name');
		$val = $fs->valid($_POST['val'],'name');

		$bid = intval($bid);
		$result = $tag->setTag($uid,$bid,$json_str_path,$key,$val);

		if($result){
			$ee->Message('200');
		}else{
			$ee->Error('302');
		}
		break;
	case 'addTag':	//add Tag in suggest panel
		$path = $fs->valid($_POST['path'],'idarray');
		$key = $fs->valid($_POST['key'],'name');
		$val = $fs->valid($_POST['val'],'name');
		$type = $fs->valid($_POST['type'],'cmd');
		$tid = $tag->addTag($uid,$path,$key,$val,$type);
		$data = array(
			'code'=>'200',
			'msg'=>$tid
		);
		echo json_encode($data);
		break;
	case 'delBookTag':
		$bid = $fs->valid($_POST['bid'],'id');
		$tid = $fs->valid($_POST['tid'],'id');

		$result = $tag->delBookTag($bid,$tid);
		if($result===true){
			$ee->Message('200');
		}else{
			$ee->add('msg',$result['msg']);
			$ee->Message('406.93');
		}
		break;
	case 'delShortcutTag':
		$tsid = $fs->valid($_POST['tsid'],'id');
		$seq = $fs->valid($_POST['seq'],'id');
		$tid = $fs->valid($_POST['tid'],'id');
		$uid = bssystem::getUID();

		$result = $tag->delShortcutTag($tsid,$seq,$tid,$uid);
		if($result===true){
			$ee->Message('200');
		}
		break;
	case 'delSysTag':
		$json_str_path = $fs->valid($_POST['path'],'idarray');
		$tid = $fs->valid($_POST['tid'],'id');

		$tag->delSysTag($json_str_path,$tid);
		break;
	case 'getMostContributed':
		//�^�m��
		break;
	case 'getShortcutList':
		$bsid = $fs->valid($_POST['bsid'],'id');
		$shortcut = new shortcut($db);
		$shortcut->reset();
		$shortcut->setBSID($bsid);
		$shortcut->setStatus(1);
		$data = $shortcut->getList('ts_id asc',0,0,'');
		$host_base = HostManager::getBookshelfBase();
		$link_temp = '<a class="shortcut" data-id="%u" title="%s" >%s</a>';
		for($i=0;$i<$data['total'];$i++){
			if(preg_match("/\.txt$/", $data['result'][$i]['f_path'])){
				$path = sprintf('%s%s/uploadfiles/%s',ROOT_PATH,$host_base,$data['result'][$i]['f_path']);
				$data['result'][$i]['img_html'] = sprintf($link_temp,$data['result'][$i]['ts_id'],$data['result'][$i]['ts_description'],file_get_contents($path));
			}else{
				$data['result'][$i]['img_html'] = sprintf($link_temp,$data['result'][$i]['ts_id'],$data['result'][$i]['ts_description'],sprintf('<img src="%s/uploadfiles/%s" />',$host_base,$data['result'][$i]['f_path']));
			}
			unset($data['result'][$i]['bs_id']);
			unset($data['result'][$i]['ts_id']);
			unset($data['result'][$i]['ts_name']);
			unset($data['result'][$i]['ts_description']);
			unset($data['result'][$i]['ts_status']);
			unset($data['result'][$i]['f_path']);
			unset($data['result'][$i]['file_id']);
		}
		echo json_encode($data);
		break;
	case 'getShortcutTag':
		$tsid = $fs->valid($_POST['tsid'],'id');
		$seq = $fs->valid($_POST['seq'],'id');
		$data = $tag->getTagByShortcut($tsid,$seq);
		echo json_encode($data);
		break;
	case 'getShortcutImage':
		$ticket = $fs->valid($_GET['t'],'timestamp');
		$TagShortcutImage = new TagShortcutImage();
		$TagShortcutImage->getImage($ticket);
		break;
	case 'getShortcutHtml':
		$ticket = $fs->valid($_POST['t'],'timestamp');
		$TagShortcutImage = new TagShortcutImage();
		$_html = $TagShortcutImage->getHtml($ticket);
		$data = array();
		$data['html'] = $_html;
		echo json_encode($data);
		break;
	case 'getImageTicket':
		$str = $fs->valid($_POST['str'],'pname');
		$TagShortcutImage = new TagShortcutImage();
		$TagShortcutImage->setString($str);
		//get string lang, if lang=all_zh, call Drow
		$info = $TagShortcutImage->getInfo();
		if($info['has_zh'] && !$info['has_en']){
			$TagShortcutImage->Drow();
		}else{
			$TagShortcutImage->Html();
		}
		$data = array();
		$data['info'] = $info;
		$data['ticket'] = $TagShortcutImage->getImageTicket();
		echo json_encode($data);
		break;
	case 'setShortcutTag':
		$tsid = $fs->valid($_POST['tsid'],'id');
		$seq = $fs->valid($_POST['seq'],'id');
		$tid = $fs->valid($_POST['tid'],'id');

		$result = $tag->setShortcutTag($tsid,$seq,$tid);

		if($result){
			$ee->Message('200');
		}else{
			$ee->Error('302');
		}
		break;
	case 'getSystemTag':
		$method = $fs->valid($_POST['method'],'cmd');
		$id = $fs->valid($_POST['id'],'id');
		$tid = $fs->valid($_POST['tid'],'id');
		switch($method){
			case 'system':
				$id=0;
				break;
			case 'group':
			case 'cate':
			case 'book':
			case 'itutor':
				break;
		}
		$data = $system_tag->getByKey($method,$id,$tid);
		$data = array_values($data);
		echo json_encode($data);
		break;
	case 'setSystemTag':
		$method = $fs->valid($_POST['method'],'cmd');
		$id = $fs->valid($_POST['id'],'id');
		$tid = $fs->valid($_POST['tid'],'id');
		switch($method){
			case 'system':
				$id=0;
				break;
			case 'group':
			case 'cate':
			case 'book':
			case 'itutor':
				break;
		}
		$result = $system_tag->getByKey($method,$id,$tid);
		if($result){
			$ejson->Error('302');
		}else{
			$data = array();
			$data['method'] = $method;
			$data['id'] = intval($id);
			$data['t_id'] = intval($tid);
			$system_tag->insert($data);
			$ee->Message('200');
		}
		break;
	case 'delSystemTag':
		$method = $fs->valid($_POST['method'],'cmd');
		$id = $fs->valid($_POST['id'],'id');
		$tid = $fs->valid($_POST['tid'],'id');
		$system_tag->del($method,$id,$tid);
		break;
	case 'getDropDownList':
		$method = $fs->valid($_POST['method'],'cmd');
		$data = $system_tag->getDropDownItemByMethod($method);
		echo json_encode($data);
		break;
	case 'getDropDownListItems':
		$method = $fs->valid($_POST['method'],'cmd');
		switch($method){
			case 'getByPKey':
				$pkey = $fs->valid($_POST['pkey'],'name');

				$data = array();
				$rs = $tag->getTagByPKey($pkey);
				$data['pid'] = 0;
				$data['data'] = $rs;
				break;
			default:
				$pid = $fs->valid($_POST['tid'],'id');
				$key = $fs->valid($_POST['key'],'name');
				$date = $fs->valid($_POST['date'],'date');

				$data = array();
				$rs = $scanexam_test_tag->getSelectedTagByKey($key,$method,$date,$pid);
				$data['selected'] = $rs['t_id'];
				$data['data'] = $tag->getTagByPID($pid);
				$data['items'] = $tag->getTagChildByPID($pid);
				break;
		}

		echo json_encode($data);
		break;
	case 'setDropDownListItems':
		$key = $fs->valid($_POST['key'],'name');
		$date = $fs->valid($_POST['date'],'date');
		$method = $fs->valid($_POST['method'],'cmd');
		$pid = (int) $fs->valid($_POST['pid'],'id');
		$tid = (int) $fs->valid($_POST['tid'],'id');

		$rs=$scanexam_test_tag->getByKey($key,$method,$date,$pid);
		if($rs){
			$data['t_id'] = $tid;
			$data['method'] = $method;
			$scanexam_test_tag->update($key,$method,$date,$pid,$data);
		}else{
			$data = array();
			$data['se_key'] = $key;
			$data['set_date'] = $date;
			$data['t_parent_id'] = $pid;
			$data['method'] = $method;
			$data['t_id'] = $tid;
			$scanexam_test_tag->insert($data);
		}
		$ee->Message('200');
		break;
	case 'getTagMap':
		//new init('sys_auth');
		$data = $tag->getList();
		echo json_encode($data);
		break;
	case 'getSubjectItems':
		$data = chart::getTagByPKey('subject');
		break;
	case 'import':
		$bs_code = $fs->valid($_GET['bs'],'id');
		$mode = $fs->valid($_GET['m'],'cmd');

		$TagImportManager = new TagImportManager($bs_code,$mode);
		$TagImportManager->Import();
		break;
	case 'statuscode':
		$data = array();
		$data['tag'] = '200.901';
		$data['dic'] =  '200.902';

		$output = $json = new Services_JSON();
		header('Content-Type: application/json; charset=utf-8');
		echo $json->encode($data);
		exit;
		break;
	case 'chkUserAccount':
		$acc = $fs->valid($_GET['account'],'acc');
		$bookshelf_user = new bookshelf_user($db);
		$rs = $bookshelf_user->getByName($acc);
		if(empty($rs)){
			$data = true;
		}else{
			$data = sprintf('this name [%s] is already taken',$acc);
		}
		$output = $json = new Services_JSON();
		header('Content-Type: application/json; charset=utf-8');
		echo $json->encode($data);
		exit;
		break;
	case 'chkUserEmail':
		$email = $fs->valid($_GET['email'],'acc');
		$bookshelf_user = new bookshelf_user($db);
		$rs = $bookshelf_user->getByEmail($email);
		if(empty($rs)){
			$data = true;
		}else{
			$data = sprintf('this email [%s] is already taken',$email);
		}
		$output = $json = new Services_JSON();
		header('Content-Type: application/json; charset=utf-8');
		echo $json->encode($data);
		exit;
		break;
	case 'setLang':
		$lang = $fs->valid($_POST['lang'],'key');
		$arr = array('zh-tw','zh-cn','vi','jp','en');
		if(!in_array($lang,$arr)) $lang='zh-tw';
		setcookie('currentlang',$lang, strtotime( '+7 days' ), '/');
		break;
}
?>
