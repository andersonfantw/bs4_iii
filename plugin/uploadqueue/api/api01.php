<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','tpl','inputxss','filter','ejson');

$cmd = $fs->valid($_REQUEST['cmd'],'cmd');
$key = $fs->valid($_POST['fileid'],'key');

$UploadQueue = new UploadQueue();
$UploadQueue->setSITE(SiteModeEnum::API);
$UploadQueue->setSpell(1);
switch($cmd){
	case 'login':
		$acc = $fs->valid($_POST['acc'],'acc');
		$pwd = $fs->valid($_POST['pwd'],'pwd');
		if(empty($acc) && empty($pwd)){
			$ee->Error('401.72');
		}
		if($acc==UPLOADQUEUE_ID && $pwd==UPLOADQUEUE_PWD){
			$token = common::makeToken();
			$ee->add('token',$token);
			$ee->Message('200');
			exit;
		}else{
			$ee->Error('401.72');
		}
		break;
	case 'upload3':
	case 'update3':
		validToken();
		if(!$_FILES){
			$ee->add('msg','[uploadfile]');
			$ee->Error('406.60');
		}

		$tagroot = UPLOADQUEUE_TAG_ROOT;
		if(empty($tagroot)){
			$tagroot = 'iii';
		}
		$arr=array('pn'=>'','py'=>'py','pty'=>'pty','pcof'=>'pcof','pwrf'=>'pwrf','pi'=>'pi','pcu'=>'pcu','pc'=>'','prt'=>'prt','year'=>'ROC');
		$requird_arr=array('pcu','pi','pn','prt');
		$arr_prt = array('10'=>'C','20'=>'M','30'=>'S','40','F','50'=>'A','60'=>'P');
		$UploadQueue->setTagRoot($tagroot);
		$UploadQueue->setBS('pcu');
		$UploadQueue->setParentCate('pi');
		$UploadQueue->setChildCate('pn');
		foreach($requird_arr as $i){
			$v = $fs->valid($_POST[$i],'name');
			$k = $fs->valid($_POST[$i.'key'],'key');
//var_dump($i,$v);
//var_dump($i.'key',$k);
			if(empty($v) || empty($k)){
				$ee->add('msg',sprintf('%s(%s) or %skey(%s) is empty!',$i,$v,$i,$k));
				$ee->Error('500');
			}
			switch($i){
				case 'pn':
					$pnkey=$k;
					$pn=$v;
					break;
				case 'prt':
					if(array_key_exists($k,$arr_prt)){
						$prtkey=$arr_prt[$k];
						$prt=$v;
					}
					break;
			}
		}
		if(!empty($pnkey) && !empty($prtkey)){
			$key=sprintf('%s%s0',$pnkey,$prtkey);
		}
		if(!empty($pn) && !empty($pnkey) && !empty($prt)){
			$bookname=sprintf('%s_%s_%s',$pnkey,$pn,$prt);
		}
		foreach($arr as $i => $prefex){
			$v = $fs->valid($_POST[$i],'name');
			$k = $fs->valid($_POST[$i.'key'],'key');
			switch($i){
				case 'py':
				case 'pty':
				case 'year':
					$k=$v;
					break;
			}
			if(!empty($v) && !empty($k)){
				$UploadQueue->setTag($i,$prefex.$k,$v);
			}
		}
/*
$tmpfile=array(
	'name'=>'test.pdf',
	'type'=>'application/pdf',
	'tmp_name'=>'/tmp/test',
	'error'=>UPLOAD_ERR_OK,
	'size'=>808894
);
		$isSuccess = $UploadQueue->add($key,$tmpfile);
*/
		$isSuccess = $UploadQueue->add($key,$_FILES['uploadfile'],$bookname);
		if($isSuccess){
			$ee->add('key',$key);
			$ee->Message('200');
		}else{
			$ee->Error('500');
		}
		break;
	case 'upload2':
	case 'update2':
		validToken(28800);
		$tagroot = UPLOADQUEUE_TAG_ROOT;
		if(empty($tagroot)){
			$tagroot = 'root';
		}
		$UploadQueue->setTagRoot($tagroot);

		//mc=;ms=Math;bs=Physics;root=Chemistry:化學;Math=math104:比長短;Physics=Physics1:直線運動
		$tags = $fs->valid($_POST['tags'],'name');
		$arr = explode(';',$tags);
		$arr_prt = array('prt10'=>'C','prt20'=>'M','prt30'=>'S','prt40'=>'F','prt50'=>'A','prt60'=>'P');
		$pnkey='';
		$prtkey='';
		$pn='';
		$prt='';
		foreach($arr as $item){
			list($tag,$val)=explode('=',$item);
			switch($tag){
				case '$bs':
					$UploadQueue->setBS($val);
					break;
				case '$mc':
					$UploadQueue->setParentCate($val);
					break;
				case '$sc':
					$UploadQueue->setChildCate($val);
					break;
				default:
					$_tags = explode(',',$val);
					foreach($_tags as $_tag){
						list($k,$v)=explode(':',$_tag);
						$UploadQueue->setTag($tag,$k,$v);
						switch($tag){
							case 'prt':
								if(array_key_exists($k,$arr_prt)){
									$prtkey=$arr_prt[$k];
									$prt=$v;
								}
								break;
							case 'pn':
								$pnkey=$k;
								$pn=$v;
								break;
						}
					}
					break;
			}
		}
		if(!empty($pnkey) && !empty($prtkey)){
			$key=sprintf('%s%s0',$pnkey,$prtkey);
		}
		if(!empty($pn) && !empty($pnkey) && !empty($prt)){
			$bookname=sprintf('%s_%s_%s',$pnkey,$pn,$prt);
		}
		if(empty($key)){
			$key = sprintf('ttii%u',time());
		}
		$isSuccess = $UploadQueue->add($key,$_FILES['uploadfile'],$bookname);
		if($isSuccess){
			$ee->add('key',$key);
			$ee->Message('200');
		}else{
			$ee->Error('500');
		}
		break;
	case 'upload':
	case 'update':
		validToken();

		if(!$_FILES){
			$ee->add('msg','[uploadfile]');
			$ee->Error('406.60');
		}
		if(empty($key)){
			$ee->add('msg','[fileid]');
			$ee->Error('406.60');
		}

		$tagroot = UPLOADQUEUE_TAG_ROOT;
		if(empty($tagroot)){
			$tagroot = 'root';
		}
			
		$UploadQueue->setTagRoot($tagroot);
		if(UPLOADQUEUE_SETBSID){
			$bsid = $fs->valid($_POST['bookshelfid'],'id');
			if(empty($bsid)){
				$ee->add('msg','[bookshelfid]');
				$ee->Error('406.60');							
			}
		}else{
			$bsid = UPLOADQUEUE_BSID_DEFAULT;
		}
		$UploadQueue->setBSID($bsid);
		if(UPLOADQUEUE_SETCID){
			$cid = $fs->valid($_POST['categoryid'],'id');
			if(!empty($cid)){
				$UploadQueue->setCID($cid);
			}
		}else{
			$pcate = UPLOADQUEUE_PARENT_CATE;
			$ccate = UPLOADQUEUE_CHILD_CATE;
			if(empty($pcate) || empty($ccate)){
				if($cmd=='upload'){
					$ee->add('msg',sprintf('System setting error! please contact system admin.'));
					$ee->Error('406.60');
				}
			}

			$ProjectName = $fs->valid($_POST[UPLOADQUEUE_PARENT_CATE],'name');
			$ProjectNameKey = $fs->valid($_POST[UPLOADQUEUE_PARENT_CATE.'Key'],'key');
			$ExamineYear = $fs->valid($_POST[UPLOADQUEUE_CHILD_CATE],'name');
			$ExamineYearKey = $fs->valid($_POST[UPLOADQUEUE_CHILD_CATE.'Key'],'key');

			$UploadQueue->setParentCate(UPLOADQUEUE_PARENT_CATE);
			$UploadQueue->setChildCate(UPLOADQUEUE_CHILD_CATE);

			if(!empty($ProjectNameKey) && !empty($ProjectName)){
				$UploadQueue->setTag('ProjectName',$ProjectNameKey,$ProjectName);
			}else{
				if($cmd=='upload'){
					$ee->add('msg',sprintf('Missing [%s] or [%sKey]',UPLOADQUEUE_PARENT_CATE,UPLOADQUEUE_PARENT_CATE));
					$ee->Error('406.60');
				}
			}
			if(!empty($ExamineYearKey) && !empty($ExamineYear)){
				$UploadQueue->setTag('ExamineYear',$ExamineYearKey,$ExamineYear);
			}else{
				if($cmd=='upload'){
					$ee->add('msg',sprintf('Missing [%s] or [%sKey]',UPLOADQUEUE_CHILD_CATE,UPLOADQUEUE_CHILD_CATE));
					$ee->Error('406.60');
				}
			}

			$tags = explode(',',UPLOADQUEUE_TAG_SETTINGS);
			foreach($tags as $tag){
				$required = (strpos($tag,'*')===0);
				if($required){
					$tag = strstr($tag,1);
				}
				$tag = $fs->valid($tag,'name');
				$tagval = $fs->valid($_POST[$tag],'name');
				$tagkey = $fs->valid($_POST[$tag.'Key'],'key');
				if($required){
					if(empty($tagkey) || empty($tagval)){
						if($cmd=='upload'){
							$ee->add('msg',sprintf('Missing [%s] or [%sKey]',$tag,$tag));
							$ee->Error('406.60');
						}
					}
					$UploadQueue->setTag($tag,$tagkey,$tagval);
				}else{
					if(!empty($tagkey) && !empty($tagval)){
						$UploadQueue->setTag($tag,$tagkey,$tagval);
					}
				}
			}
		}

		$isSuccess = $UploadQueue->add($key,$_FILES['uploadfile']);
		if($isSuccess){
			$ee->Message('200');
		}else{
			$ee->Error('500');
		}
		break;
	case 'progress':
		validToken(28800);

		$queue = new queue(&$db);
		$unprocess = $queue->getUnprocess();
		$heartbeat = $UploadQueue->checkHeartbeat();
		$ee->add('total',$unprocess['num']);
		if($unprocess['num']>0){
			if($heartbeat){
				$filename = $heartbeat['filename'];
				$rate = $heartbeat['rate'];
			}else{
				$filename = $unprocess['filename'];
				$rate = 0;
			}
			$ee->add('filename',$filename);
			$ee->add('rate',$rate);
		}
		$ee->Message('200');
		break;
	case 'chkstatus':
		validToken();

		if(empty($key)){
			$ee->add('msg','Missing parameter [fileid]');
			$ee->Error('406.60');
		}
		$queue = new queue(&$db);
		$data = $queue->getListByKey($key);
		if(empty($data)){
			$ee->add('key',$key);
			$ee->Error('404');
		}
		$row = $data[count($data)-1];
		if($row['q_retry']>=3){
			$ee->add('key',$key);
			$ee->Error('500.28');
		}else{
			$ee->add('key',$key);
			$ee->add('progress',$row['status']);
			switch(intval($row['status'])){
				case QueueStatusEnum::Success:
					$ee->add('msg','importing');
					$ee->Warning('102.3');
					break;
				case QueueStatusEnum::ImportSuccess:
					$bid = $row['b_id'];
					$book = new book(&$db);
					$data1 = $book->getByID($bid);
					$openurl = $data1['webbook_link'];
					$openurl = str_replace(HttpLocalIPPort,'',$data1['webbook_link']);
					$openurl = str_replace(LocalHost, '', $openurl);
					$ee->add('openurl',$openurl);
					$ee->Message('200');
					break;
				case QueueStatusEnum::Wait:
					$ee->add('retry',$row['q_retry']);
					$ee->add('msg','waiting in queue');
					$ee->Warning('102.1');
					break;
				case QueueStatusEnum::Fail:
					$ee->Warning('500');
					break;
				default:
					$ee->add('msg','converting');
					$ee->Warning('102.2');
					break;
			}
		}
		break;
	case 'delete':
		validToken();

		if(empty($key)){
			$ee->add('msg','Missing parameter [fileid]');
			$ee->Warning('406.60');
		}
		$result = $UploadQueue->delByKey($key);
		if($result){
			$ee->Message('200');
		}else{
			$ee->add('msg','converting');
			$ee->Warning('102');
		}
		break;
	case 'start':
		validToken();
		$UploadQueue = new UploadQueue();
		$UploadQueue->doNext();
		break;
	case 'openbook':
		validToken();
		$bookurl = $fs->valid($_GET['bookurl'],'path');
		BookshelfManager::UserLogin(0,'iii','iii');
		if(empty($_SESSION['uid'])){
			$_SESSION['uid']=0; //for the prev book valid
		}
		$bookurl = str_replace('webs@2/ebook/','webs@2/ebook/'.session_id().'/',$bookurl);
		header('location: '.$bookurl);
		break;
	case 'getBookshelfList':
		validToken();
		$bookshelf = new bookshelf(&$db);
		$data = $bookshelf->getList('bs_id desc',0,0,'bs_status=1 and bs_list_status=1');
		$arr = array();
		foreach($data['result'] as $row){
			$arr[] = array('id'=>$row['bs_id'],'name'=>$row['bs_name']);
		}
		echo json_encode($arr);
		break;
	case 'getCategoryList':
		validToken();
		global $bs_code;
		$bs_code = $fs->valid($_POST['bookshelfid'],'num');
		$category = new category(&$db);
		$data = $category->getCategoryStructure();
		$arr = array();
		foreach($data['result'] as $pcate){
			foreach($pcate['sub_category'] as $ccate){
				
				
				$arr[] = array('id'=>$ccate['c_id'],
												'name'=>sprintf('%s-%s',$pcate['c_name'],$ccate['c_name']));
			}
		}
		echo json_encode($arr);
		break;
	case 'iiissotest':
		$token = $fs->valid($_POST['token'],'key');
		$arr = array('unit'=>'pcu31','account'=>'demo','username'=>'demo');
	  $json = new Services_JSON(SERVICES_JSON_ESCAPED_UNICODE);
		header('Content-Type: application/json; charset=utf-8');
		echo $json->encode($arr);exit;
		break;
	case 'iiisso':
		$token = $fs->valid($_GET['token'],'key');
                $iiiurl = 'http://doitpms3.tdp.org.tw/content/application/doitpms/api/ebookServer.php';
                $iiipostjson = sprintf('{"method":"auth","token":"%s"}',$token);
if(!empty($token)){
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $iiiurl);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Content-Length: ' . strlen($iiipostjson)));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $iiipostjson);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$body = curl_exec($ch);
		$arr = json_decode($body,TRUE);

		$units = $arr['unit'];
		$account = $arr['account'];
		$username = $arr['username'];

		if($arr['Code']!='0'){
			echo $arr['message'];
			exit;
		}
}else{
	$_loginacc = $fs->valid($_GET['acc'],'acc');
	$acc_allow = array('ccyu','fllee','jyjou','syliu2','cwtan','cwchan','hclin');
	if(in_array($_loginacc,$acc_allow)){
		if($_loginacc=='syliu2'){
			$units='30';
		}else{
			//$units='pcu30,pcu31,pcu32,pcu33,pcu34,pcu35';
			$units='30,31,32,33,34,35';
		}
		$account=$_loginacc;
		$username=$account;
	}
}

		$TagTree = new TagTree();
		$TagTree->loadDB();

		//see if user exist
		$bookshelf_user = new bookshelf_user(&$db);
		$row_u = $bookshelf_user->getByName($account);
		if(empty($row_u)){
			$data = array();
			$data['g_id'] = $gid;
			$data['bu_name'] = $account;
			$data['bu_cname'] = $username;
			$data['BU_PASSWORD'] = md5('ttii'.$account);
			$_buid = $bookshelf_user->insert($fs->sql_safe($data),true);
		}else{
			$_buid = $row_u['bu_id'];
			if($row_u['bu_cname']!=$username){
				$data['bu_cname'] = $username;
				$bookshelf_user->update($_buid,$data);
			}
		}

		//see if group exist
		$group = new group(&$db);
		$arr_unit = explode(',',$units);
		$arr_gids = array();
		foreach($arr_unit as $unit){
				$row_g = $group->getByKey('pcu'.$unit);
				if(empty($row_g)){
					//see if group in tag
					$_obj = $TagTree->getNode('pcu'.$unit);
					if(empty($_obj)){
						$ee->Error('404.90');
					}else{
						$data = array();
						$data['g_key'] = $_obj->data['key'];
						$data['g_name'] = $_obj->data['val'];
						$arr_gids[] = $group->insert($fs->sql_safe($data), true);
					}
				}else{
					$arr_gids[] = $row_g['g_id'];
				}
		}
		$group->update_group_user($arr_gids,$_buid);
		$AuthManager = new AuthManager();
		$AuthManager->validUser($account);
		header('location:/search/');
		break;
	case 'updateProjectName':
		validToken();
		$pnkey = $fs->valid($_POST['pnkey'],'key');
		$pn = $fs->valid($_POST['pn'],'name');
		$tag = new tag(&$db);
		$tagevolve = new tagevolve(&$db);
		$tagrow = $tag->getByKey($pnkey);
		if(count($tagrow)==1){
			$tid = $tagrow['result'][0]['t_id'];
			$tagevolve->addFrom($tid);
			$tagevolve->rename($pn);
			$ee->Message('200');
			exit;
		}
		$ee->add('msg','expect return one row, multiple rows return');
		$ee->Error('406.47');
		break;
	case 'iiilookuplist':
		validToken();
		$arr = array('ty'=>'key',
								'py'=>'key',
								'pty'=>'key',
								'pn'=>'key',
								'pcof'=>'key',
								'pwrf'=>'key',
								'pc'=>'key',
								'pi'=>'key',
								'prt'=>'key',
								'pcu'=>'key');
		$TagSearch = new TagSearch();
		$TagSearch->Columns(array('b_key','b_name','webbook_link'));
		foreach($arr as $_name=>$valid){
			$val = $fs->valid($_POST[$_name],$valid);
			if(!empty($val)){
				$TagSearch->addConditionByTagKey($_name,$val);
			}
		}
		$data = $TagSearch->getList('b_id desc',0,0,'');
		//$output = new Services_JSON();
		$json = new Services_JSON(SERVICES_JSON_ESCAPED_UNICODE);
		header('Content-Type: application/json; charset=utf-8');
		echo $json->encode($data);exit;
		break;
	case 'search':
		$index = $fs->valid($_POST['index'],'num');
		//get user auth.
		$group = new group(&$db);
		$buid = bssystem::getLoginBUID();
		$arr_pcu = array('pcu30'=>175,
											'pcu31'=>176,
											'pcu32'=>177,
											'pcu33'=>178,
											'pcu34'=>179,
											'pcu35'=>180);
		$arr = array('ty'=>'idarray',
								'py'=>'id',
								'pty'=>'id',
								'pn'=>'name',
								'pcof'=>'id',
								'pwrf'=>'idarray',
								'pc'=>'id',
								'pi'=>'idarray',
								'prt'=>'idarray',
								'pcu'=>'idarray');
		$TagSearch = new TagSearch();
		$TagSearch->enableTagCols(true);
		$TagSearch->addTags(array('year','pn','prt','pi','pcu','pc','pwrf'));
		$TagSearch->Columns(array('b_name','webbook_link','ibook_link'));
		$_has_param=false;
		$_has_pcu=false;
		$_auth_pcu = array();
		$data = $group->getListByBUID($buid);
		foreach($data as $row){
			if(array_key_exists($row['g_key'],$arr_pcu)){
				$_auth_pcu[] = $arr_pcu[$row['g_key']];
			}
		}
		foreach($arr as $_name=>$data){
			list($valid,$type) = explode('|',$data);
			$val = $fs->valid($_POST[$_name],$valid);
			if(!empty($val)){
				$_has_param=true;
				switch($type){
					case 'range':
						$_from = $fs->valid($_POST[$_name.'f'],'id');
						$_to = $fs->valid($_POST[$_name.'t'],'id');
						$TagSearch->addConditionRangeByTagKey($_name,$val,$_from,$_to);
						break;
					default:
						switch($_name){
							case 'pn':
								$TagSearch->addConditionByTagKeyword($_name,$val);
								break;
/*
							case 'prt':
							case 'pwrf':
							case 'pcu':
								if(is_array($val)){
									foreach($val as $v){
										$TagSearch->addConditionByTID($name,$v);
									}
								}
								break;
*/
							default:
								if($_name=='pcu') $_has_pcu=true;
								$TagSearch->addConditionByTID($_name,$val);
								break;
						}
						break;
				}
			}
		}
		if(!$_has_pcu){
			$TagSearch->addConditionByTID($_name,$_auth_pcu);
		}
		if(!$_has_param){
			$ee->Error('406.60');
		}
		$limit=($index>0)?200:0;
		$data = $TagSearch->getList('b_id desc',($index-1)*$limit,$limit,'');
		//$output = new Services_JSON();
	  $json = new Services_JSON(SERVICES_JSON_ESCAPED_UNICODE);
		header('Content-Type: application/json; charset=utf-8');
		echo $json->encode($data);exit;
		break;
}

function validToken($sec=3600){
	global $fs;
	global $ee;
	$token = $fs->valid($_REQUEST['token'],'lnettoken');
	if(empty($token)){
		$ee->Error('401.70');
	}else{
		$valid = common::checkToken($token,$sec);
		if(!$valid){
			$ee->Error('401.74');
		}
	}
}

?>
