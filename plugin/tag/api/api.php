<?PHP
set_time_limit(1800);
require_once dirname(__FILE__).'/../../../init/config.php';
//$init = new init('db','auth','bookshelf_auth','filter');
$init = new init('db','filter','ejson');
$tag = new tag($db);
$system_tag = new system_tag($db);
$scanexam_test_tag = new scanexam_test_tag($db);

$cmd = $fs->valid($_GET['cmd'],'cmd');
$uid=intval($_SESSION['adminid']);
switch($cmd){
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
		$like = $fs->valid($_POST['like'],'query');
		$data = $tag->getSuggestByDropDownList($uid,$like);
		echo json_encode($data);
		break;
	case 'getSuggestByChoosePanel':
		$uid = bssystem::getUID();
		$bid = $fs->valid($_POST['bid'],'id');
		$like = $fs->valid($_POST['like'],'query');
		$path = $fs->valid($_POST['path'],'idarray');

		$bid = intval($bid);
		$data = $tag->getSuggestByChoosePanel($uid,$bid,$path,$like);
		echo json_encode($data);
		break;
	case 'getSuggestByChoosePanelByShortcut':
		$uid = bssystem::getUID();
		$tsid = $fs->valid($_POST['tsid'],'id');
		$seq = $fs->valid($_POST['seq'],'num');
		$like = $fs->valid($_POST['like'],'query');
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
		$key = $fs->valid($_POST['key'],'pname');
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
		$key = $fs->valid($_POST['key'],'key');
		$val = $fs->valid($_POST['val'],'name');
		$type = $fs->valid($_POST['type'],'bool');
		$arr = $tag->addTag($uid,$path,$key,$val,$type);
		$data = array(
			'code'=>'200',
			'msg'=>$arr['tid']
		);
		echo json_encode($data);
		break;
	case 'delBookTag':
		$bid = $fs->valid($_POST['bid'],'id');
		$tid = $fs->valid($_POST['tid'],'id');

		$result = $tag->delBookTag($bid,$tid);
		if($result['code']=='200'){
			$ee->Message('200');
		}else{
			$ee->add('msg',$result['msg']);
			$ee->Error('406.93');
		}
		break;
	case 'delShortcutTag':
		$tsid = $fs->valid($_POST['tsid'],'id');
		$seq = $fs->valid($_POST['seq'],'num');
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

		$result = $tag->delSysTag($json_str_path,$tid);
		if($result===true){
			$ee->Message('200');
		}
		break;
	case 'getMostContributed':
		//�^�m��
		break;
	case 'getShortcutList':
		$uid = $fs->valid($_POST['uid'],'id');
		$bsid = $fs->valid($_POST['bsid'],'id');
		$shortcut = new shortcut($db);
		$shortcut->reset();
		$shortcut->setBSID($bsid);
		$shortcut->setStatus(1);
		$data = $shortcut->getList('ts_id asc',0,0,'');
		$host_base = HostManager::getBookshelfBase(false,false,$uid,$bsid);
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
		$seq = $fs->valid($_POST['seq'],'num');
		$data = $tag->getTagByShortcut($tsid,$seq);
		echo json_encode($data);
		break;
	case 'getShortcutImage':
		$ticket = $fs->valid($_GET['t'],'name');
		$TagShortcutImage = new TagShortcutImage();
		$TagShortcutImage->getImage($ticket);
		break;
	case 'getShortcutHtml':
		$ticket = $fs->valid($_POST['t'],'key');
		$TagShortcutImage = new TagShortcutImage();
		$_html = $TagShortcutImage->getHtml($ticket);
		$data = array();
		$data['html'] = $_html;
		echo json_encode($data);
		break;
	case 'getImageTicket':
		$str = $fs->valid($_POST['str'],'name');
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
		$seq = $fs->valid($_POST['seq'],'num');
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
			$ee->Error('302');
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
		$all=false;
		switch($method){
			case 'getAllTagByPKey':
			case 'validAllTagByPKey':
				$all=true;
			case 'getByPKey':
			case 'validByPKey':
				$pkey = $fs->valid($_POST['pkey'],'key');

				$data = array();
				if($all){
					$rs = $tag->getAllTagByPKey($pkey,true);
				}else{
					$rs = $tag->getTagByPKey($pkey,true);
				}
				//for iii auth
				/************************************/
				$path = parse_url($_SERVER['HTTP_REFERER']);
				$path = pathinfo($path['path']);
				if($pkey=='pcu' && $path['dirname']!='/backend'){
					$_r=array();
					$_rs=array();
					foreach($rs as $r){
						$_r[$r['key']]=$r;
					}
					$buid = bssystem::getLoginBUID();
					$group = new group($db);
					$rs1 = $group->getListByBUID($buid);
					foreach($rs1 as $r){
						$_rs[] = $_r[$r['g_key']];
					}
					$rs=$_rs;
				}
				/************************************/
				$data['pid'] = 0;
				$data['pkey'] = $pkey;
				$data['data'] = $rs;
				switch($method){
					case 'getByPKey':
					case 'getAllTagByPKey':
						echo json_encode($data,TRUE);
						break;
					case 'validByPKey':
					case 'validAllTagByPKey':
						$json = new Services_JSON(SERVICES_JSON_ESCAPED_UNICODE);
						$data = $json->encode($data);
						$data = str_replace('\/','/',$data);
						echo md5($data);
						break;
				}
				break;
			default:
				$pid = $fs->valid($_POST['tid'],'id');
				$key = $fs->valid($_POST['key'],'key');
				$date = $fs->valid($_POST['date'],'date');

				$data = array();
				$rs = $scanexam_test_tag->getSelectedTagByKey($key,$method,$date,$pid);
				$data['selected'] = $rs['t_id'];
				$data['data'] = $tag->getTagByPID($pid);
				$data['items'] = $tag->getTagChildByPID($pid);
				echo json_encode($data);
				break;
		}
		break;
	case 'setDropDownListItems':
		$key = $fs->valid($_POST['key'],'key');
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
		$mode = $fs->valid($_GET['m'],'num');

		$TagImportManager = new TagImportManager($bs_code,$mode);
		$TagImportManager->addFile($_FILES['uploadedFile']);
		$TagImportManager->Import();
		break;
	case 'importByStr':
		$bs_code = $fs->valid($_POST['bs'],'id');
		$mode = $fs->valid($_POST['m'],'num');
		$str = $fs->valid($_POST['str'],'content');

		$TagImportManager = new TagImportManager($bs_code,$mode);
		$TagImportManager->addString($str);
		$TagImportManager->Import();
		break;
	case 'statuscode':
		$mode = $fs->valid($_POST['m'],'num');
		$data = array();
		switch($mode){
			case TagImportModeEnum::Tags:
				$data['tag'] = array(1,'200.901');
				break;
			case TagImportModeEnum::Dictionary:
				$data['dic'] = array(1,'200.902');
				break;
		}

		$output = $json = new Services_JSON();
		header('Content-Type: application/json; charset=utf-8');
		echo $json->encode($data);
		exit;
		break;
	case 'getItutorQuiz':
		$dockey = $fs->valid($_POST['id'],'key');
		$data['quiz'] = $tag->getItutorQuiz($dockey);
		$data['tags'] = array();
		foreach($data['quiz'] as $quiz){
			$data['tags'][$quiz['seq']] = $tag->getItutorQuizTag($dockey,$quiz['seq']);
		}
		$data['systags'] = array();
		foreach($data['quiz'] as $quiz){
			$data['systags'][$quiz['seq']] = $tag->getQuizSysTag($dockey,$quiz['seq']);
		}
		echo json_encode($data);
		break;
	case 'getScanexamQuiz':
		$sekey = $fs->valid($_POST['key'],'key');
		$setdate = $fs->valid($_POST['date'],'date');
		$data['quiz'] = $tag->getScanexamQuiz($sekey,strtotime($setdate));
		$data['tags'] = array();
		foreach($data['quiz'] as $quiz){
			$data['tags'][$quiz['seq']] = $tag->getScanexamQuizTag($sekey,$setdate,$quiz['seq']);
		}
		$data['systags'] = array();
		foreach($data['quiz'] as $quiz){
			$data['systags'][$quiz['seq']] = $tag->getQuizSysTag($sekey,$quiz['seq']);
		}
		echo json_encode($data);
		break;
	case 'getItutorQuizTag':
		$dockey = $fs->valid($_POST['id'],'key');
		$reportid = $fs->valid($_POST['reportid'],'key');
		$data = $tag->getItutorQuizTag($dockey,$reportid);
		echo json_encode($data);
		break;
	case 'getScanexamQuizTag':
		$sekey = $fs->valid($_POST['key'],'key');
		$setdate = $fs->valid($_POST['date'],'date');
		$seq = $fs->valid($_POST['seq'],'num');
		$data = $tag->getScanexamQuizTag($sekey,$setdate,$seq);
		echo json_encode($data);
		break;
	case 'getSuggestByChoosePanelByTagquizItutor':
		$sekey = $fs->valid($_POST['key'],'key');
		$reportid = $fs->valid($_POST['reportid'],'key');
		$path = $fs->valid($_POST['path'],'idarray');
		$like = $fs->valid($_POST['like'],'query');
		$data = $tag->getSuggestByChoosePanelByTagquizItutor($uid,$dockey,$reportid,$path,$like);
		echo json_encode($data);
		break;
	case 'getSuggestByChoosePanelByTagquizInfoacer':
		$sekey = $fs->valid($_POST['key'],'key');
		$setdate = $fs->valid($_POST['date'],'date');
		$seq = $fs->valid($_POST['seq'],'num');
		$path = $fs->valid($_POST['path'],'idarray');
		$like = $fs->valid($_POST['like'],'query');
		$data = $tag->getSuggestByChoosePanelByTagquizInfoacer($uid,$dockey,strtotime($setdate),$seq,$path,$like);
		echo json_encode($data);
		break;
	case 'setItutorQuizTag':
		$dockey = $fs->valid($_POST['dockey'],'key');
		$reportid = $fs->valid($_POST['reportid'],'key');
		$ptid = (int)$fs->valid($_POST['ptid'],'id');
		$tid = (int)$fs->valid($_POST['tid'],'id');
		$itutor_exercise_tag = new itutor_exercise_tag($db);
		$data = $itutor_exercise_tag->insert($dockey,$reportid,$ptid,$tid);
		$ee->Message('200');
		break;
	case 'setScanexamQuizTag':
		$bskey = $fs->valid($_POST['bskey'],'key');
		$sekey = $fs->valid($_POST['sekey'],'key');
		$setdate = $fs->valid($_POST['setdate'],'date');
		$seq = (int)$fs->valid($_POST['seq'],'num');
		$ptid = (int)$fs->valid($_POST['ptid'],'id');
		$tid = (int)$fs->valid($_POST['tid'],'id');
		$scanexam_exercise_tag = new scanexam_exercise_tag($db);
		$data = $scanexam_exercise_tag->insert($bskey,$sekey,$setdate,$seq,$ptid,$tid,$uid);
		$ee->Message('200');
		break;
	case 'delItutorQuizTag':
		$dockey = $fs->valid($_POST['dockey'],'key');
		$reportid = $fs->valid($_POST['reportid'],'key');
		$ptid = $fs->valid($_POST['ptid'],'id');
		$tid = $fs->valid($_POST['tid'],'id');
		$itutor_exercise_tag = new itutor_exercise_tag($db);
		$itutor_exercise_tag->del($dockey,$reportid,$ptid,$tid);
		$ee->Message('200');
		break;
	case 'delScanexamQuizTag':
		$bskey = $fs->valid($_POST['bskey'],'key');
		$sekey = $fs->valid($_POST['sekey'],'key');
		$setdate = $fs->valid($_POST['setdate'],'date');
		$seq = $fs->valid($_POST['seq'],'num');
		$ptid = $fs->valid($_POST['ptid'],'id');
		$tid = $fs->valid($_POST['tid'],'id');
		$scanexam_exercise_tag = new scanexam_exercise_tag($db);
		$scanexam_exercise_tag->del($bskey,$sekey,$setdate,$seq,$ptid,$tid);
		$ee->Message('200');
		break;
	case 'getTagsByPKey':
		$keys = $fs->valid($_POST['keys'],'key');
		$data = $tag->getTagsByPKey($keys);
		echo json_encode($data);
		break;
	case 'getTMList':
		$key = $_GET['id'];
		if($key=='#'){
			$key='';
		}else{
			$key = $fs->valid($_GET['id'],'tagkey');
		}
		$data = $tag->getTMListByPKey($key);
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
		break;
	case 'getTMRename':
		//new tag & rename
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
		break;
	case 'getTMDelete':
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
		break;
}
?>
