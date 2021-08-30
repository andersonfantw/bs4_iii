<?PHP
/*
status code progress
add	converting	converted	addBookshelf
0	=>	1~99		=>	100		=>		200	(done)
												=>		-1	(fail)
												
大領域 pwrf
承辦人 pc
執行單位 pi
專案名稱 pn
報告類型 prt
計畫年數 py
計畫總年數 pty
年度 year
承辦科別 pcu
經費類別 pcof
*/
//需依照計畫名稱、審查年度、期別將文件依符合審查作業的方式歸類在電子書系統內。
//ProjectName(cate1), ExamineYear(cate2), Stage

//zip is allowed to insert in queue, but zip insert to system is not take much time,
//no reason add in queue in implement, zip file will add immediately to system.

//create bookshelf when before convert, may cause empty bookshef when convert has failed.
class UploadQueue{
	var $TagTree;
	var $tagdoc;
	var $tagstr;
	var $treeIndex;
	var $tagroot;
	var $tags;
	var $queue;

	var $parentcate;
	var $parentcateKey;
	var $_parentcateName;
	var $childcate;
	var $childcateKey;
	var $_childcateName;
	var $uid;
	var $bsid;
	var $bskey;
	var $bsname;
	var $cid;
	var $spell;
	var $site;
	var $uploadfile;
	var $ecocat_allow_type;
	var $data;

	var $arrQueueStatusEnum = array(
		QueueStatusEnum::Fail=>'系統錯誤。可能檔案過大，或檔案格式有誤。請用其他PDF轉檔軟體重新轉檔後上傳。',
		QueueStatusEnum::MissingFile=>'檔案遺失。請至數位圖書館管理後台重新上傳。',
		QueueStatusEnum::IncorrectFilename=>'不合法的檔案名稱，名稱不可包含單引號或雙引號。請修改專案名稱後至數位圖書館管理後台重新上傳。',
		QueueStatusEnum::ConvertingTimeout=>'轉檔逾時，持續30分鐘沒有進度。',
		QueueStatusEnum::ConvertSuspend=>'轉檔時意外中止',
		QueueStatusEnum::ImportFail=>'電子書匯入超過2小時。批次轉換多個大檔時，轉檔及同時匯入多本電子書可能延長匯入時間。請再注意該檔是否完成匯入。',
		QueueStatusEnum::ExceedMaxRetryTimes=>'轉檔嘗試三次後仍失敗。請用其他PDF轉檔軟體重新轉檔後上傳。',
		QueueStatusEnum::FileNotGiving=>'建立排程時遺失檔案。請至數位圖書館管理後台重新上傳。',
		QueueStatusEnum::UnknowFileFormat=>'不明的轉檔格式，請將文件轉為PDF後重新上傳。',
		QueueStatusEnum::MissingLogFile=>'遺失轉檔引擎記錄檔。請將文件轉為PDF後重新上傳。若問題持續發生，請聯絡系統管理員。',
		QueueStatusEnum::ErrorInLog=>'轉檔引擎記錄檔中出現錯誤',
		QueueStatusEnum::ErrorOccurredWhileConverting=>'轉檔中出現錯誤',
		QueueStatusEnum::NoAuth=>'沒有權限。請聯絡系統管理員。'
	);

	public function testtag(){
		$this->setParentCate('ProjectName');
		$this->setChildCate('ExamineYear');

		$this->setTagRoot('iii');
		$this->setTag('ProjectName','tp','test project');
		$this->setTag('ExamineYear','2016','2016');
		$this->setTag('stage','1','1');
/*
		if(empty($this->parentcate) || empty($this->childcate)){
			$this->_setDefaultTag();
		}
*/
		$this->tagstr = $this->TagTree->toString($this->treeIndex);
		$this->TagTree->loadString($this->tagstr);
		$this->TagTree->saveDB();

		$this->bsid=3;
		$this->_doCate();
		$this->TagTree->bindOnBook($bid,$this->treeIndex);
	}

	function __construct(){
		global $db;
		$this->uid = 1;
		$this->TagTree = new TagTree();
		$this->queue = new queue($db);
		$this->ini = new ini($db);
		$this->tags = array();
		$this->heartbeat = CACHE_PATH.'/heartbeat';
		$this->ecocat_allow_type = array('pdf','ppt','doc','xls','pptx','docx','xlsx');
		$this->file_allow_type = array('zip','itu');
	}
	public function setBSID($_bsid){
		global $db;
		if($_bsid){
			$bookshelf = new bookshelf($db);
			$result = $bookshelf->getByKey($_bsid);
			$this->bskey = $result['bs_key'];
			$this->parentbskey = $result['bs_key'];
			if(empty($this->bsname)){
				$this->bsname = $result['bs_name'];
			}
			$this->bsid = $_bsid;
		}
	}
	public function setBSKey($_bskey){
		global $db;
		if($_bskey){
			$bookshelf = new bookshelf($db);
			$result = $bookshelf->getByID($_bskey);
			$this->bsid = $result['bs_id'];
			if(empty($this->bsname)){
				$this->bsname = $result['bs_name'];
			}
			$this->bskey = $_bskey;
			$this->parentbskey = $_bskey;
		}
	}
	public function setBS($_parentbskey){
		$this->parentbskey = $_parentbskey;
	}
	public function setCID($_cid){
		$this->cid = $_cid;
	}
	public function setParentCate($name){
		$this->parentcate = $name;
	}
	public function setChildCate($name){
		$this->childcate = $name;
	}
	private function _setTagRoot($key){
		$this->setTagRoot($key);
		$stack = array();
		if($key!='root'){
			$node = $this->TagTree->getNode($key);
			do{
				$stack[] = $node;
				$node = $node->parent;
			}while($node->parent->data['key']=='root');
		}
		while(!empty($stack)){
			$node = array_pop($stack);
			//$this->setTag($node->parent->data['key'],$node->data['key'],$node->data['val']);
			$this->TagTree->add($node->parent->data['key'],$node->data,$node->data['key'],$this->treeIndex);
		}
	}
	public function setTagRoot($key){
		if(!$this->TagTree->isLoadDB()){
			$this->TagTree->loadDB();
			$this->treeIndex = $this->TagTree->newTree();
		}
		$this->tagroot = $key;
	}
	private function _parseTagstr($str){
		$tags = array();
		//$arr = explode('!',$str);
		$str = preg_replace(array('/\!\!/','/\!/'),array('！|@|','|@|'),$str);
		$arr = explode('|@|',$str);
		foreach($arr as $a){
			$tags[] = explode(',',$a);
		}
		return $tags;
	}
	private function _makeTagKey($type,$key){
		$arr=array('pn','year');
		if(strpos($key,'#')===0 || $type=='root' || empty($key) || in_array($type,$arr)){
			$k=$key;
		}else{
			$k=sprintf('%s_%s',$type,$key);
		}
		return $k;
	}
	private function _setTag(){
		$tags = $this->_parseTagstr($this->tagstr);
		foreach($tags as $t){
			list($type,$key,$val) = $t;
			$this->tags[$key] = $t;
			$treenode_p = $this->TagTree->getNode($type);
			$treenode_k = $this->TagTree->getNode($key);
			if(!empty($treenode_p) && !empty($treenode_k)){
				$pt = $treenode_p->parent->data['key'];
				$ptag = $treenode_p->data;
				$pk = $treenode_p->data['key'];
				$type = $treenode_k->parent->data['key'];
				$tag = $treenode_k->data;
				$k = $treenode_k->data['key'];
				$this->TagTree->add($pt,$ptag,$pk,$this->treeIndex);
				$this->TagTree->add($type,$tag,$k,$this->treeIndex);
				continue;
			}
			$k = $this->_makeTagKey($type,$key);
			$tag = array('key'=>$k,'val'=>$val);
			$treenode = $this->TagTree->getNode($k);
			if(!empty($treenode)){
				$type = $treenode->parent->data['key'];
				$tag = $treenode->data;
				$k = $treenode->data['key'];	
			}
			//make sure node can add to tag tree
			if(empty($treenode_p)){
				//it is better to make key in the system first.
				//but if parent node is not exist, make one and under root tag
				$pt = $this->tagroot;
				$pk = $type;
				if(strpos($type,'#')===0){
					$pv = substr($type,1);
				}else{
					$pv = $type;
				}
				$ptag = array('key'=>$pk,'val'=>$pv);
			}else{
				$pt = $treenode_p->parent->data['key'];
				$ptag = $treenode_p->data;
				$pk = $treenode_p->data['key'];
			}
			$this->TagTree->add($pt,$ptag,$pk,$this->treeIndex);
			$this->TagTree->add($type,$tag,$k,$this->treeIndex);
		}
	}
	public function setTag($type,$key,$val){
		if(!empty($this->tagstr)){
			$this->tagstr .= '!';
		}
		$this->tagstr .= sprintf("%s,%s,%s",$type,$key,$val);

		//$treenode_p = $this->TagTree->getNode($type);
		//$treenode_k = $this->TagTree->getNode($key);
		//$k = $this->_makeTagKey($type,$key);

		switch($type){
			case $this->parentbskey:
				$this->bskey = $key;
				$this->bsname = $val;
				break;
			case $this->parentcate:
				//$this->parentcateKey = (empty($treenode_p)) ? $k:$key;
				$this->parentcateKey = $key;
				$this->_parentcateName = $val;
				break;
			case $this->childcate:
				//$this->childcateKey = (empty($treenode_k)) ? $k:$key;
				$this->childcateKey = $key;
				$this->_childcateName = $val;
				break;
		}
	}
	public function setSITE($_site){
		$this->site = $_site;
	}
	public function setSpell($_spell){
		$this->spell = $_spell;
	}
	private function _setParamsFromData($data){
		global $db;
		$this->tagstr = $data['tags'];
		$this->spell = $data['spell'];
		$this->bsid = $data['bsid'];
		$this->bskey = $data['bskey'];
		$this->parentbskey = $data['pbskey'];
		$this->bsname = $data['bsname'];
		$this->cid = $data['cid'];
		$this->tagroot = $data['tagroot'];
		$this->parentcateKey = $data['pcatek'];
		$this->_parentcateName = $data['pcateN'];
		$this->childcateKey = $data['ccatek'];
		$this->_childcateName = $data['ccateN'];
		//fillup empty data
		$bookshelf = new bookshelf($db);
		if($this->bsid){
			$result = $bookshelf->getByID($this->bsid);
			$this->bskey = $result[0]['bs_key'];
			if(empty($this->bsname)){
				$this->bsname = $result[0]['bs_name'];
			}
		}
		if($this->bskey){
			$result = $bookshelf->getByKey($this->bskey);
			$this->bsid = $result[0]['bs_id'];
			if(empty($this->bsname)){
				$this->bsname = $result[0]['bs_name'];
			}
		}
	}
	private function _setDataFromParams(){
		global $db;
		//fillup empty data

		$bookshelf = new bookshelf($db);
		if($this->bsid){
			$result = $bookshelf->getByID($this->bsid);
			$this->bskey = $result[0]['bs_key'];
			if(empty($this->bsname)){
				$this->bsname = $result[0]['bs_name'];
			}
		}
		if($this->bskey){
			$result = $bookshelf->getByKey($this->bskey);
			$this->bsid = $result[0]['bs_id'];
			if(empty($this->bsname)){
				$this->bsname = $result[0]['bs_name'];
			}
		}

		$this->data = array('tags'=>$this->tagstr,
			'bsid'=>$this->bsid,
			'bskey'=>$this->bskey,
			'pbskey'=>$this->parentbskey,
			'bsname'=>$this->bsname,
			'cid'=>$this->cid,
			'spell'=>$this->spell,
			'tagroot'=>$this->tagroot,
			'pcatek'=>$this->parentcateKey,
			'pcateN'=>$this->_parentcateName,
			'ccatek'=>$this->childcateKey,
			'ccateN'=>$this->_childcateName);
		return $this->data;
	}
	private function _add($key,$uploadfile,$timestamp='',$bookname=''){
		global $db;
		
		$this->uploadfile = $uploadfile;

		$name = $uploadfile['name'];
		$path_parts = common::path_info($name);
		if(!empty($bookname)){
			$name = $bookname.'.'.$path_parts['extension'];
		}
		
		//move file to work folder
		if(empty($timestamp)){
			$timestamp = time();
		}
 		$workpath = WORK_PATH.'/'.$timestamp.'.'.$path_parts['extension'];
 		$f = move_uploaded_file($uploadfile["tmp_name"],$workpath);

		//$tagstr = $this->TagTree->toString($this->treeIndex);
		//$tagstr = urlencode($tagstr);
		$this->data = $this->_setDataFromParams();

		//insert file to queue
		$data = array();
		$data['q_name'] = $name;
		$data['q_tmpname'] = $workpath;
		$data['q_key'] = $key;
		$data['q_data'] = json_encode($this->data);
		if(!$f){
			$data['status'] = QueueStatusEnum::FileNotGiving;
		}
		return $this->queue->insert($data);
	}
	public function get($id){
		global $db;
		return $this->queue->getByID($id);
	}
	public function add($key,$uploadfile,$bookname=''){
		global $db;
		global $ee;

		$this->uploadfile = $uploadfile;

		$data = $this->queue->getListByKey($key);
		if(count($data)==0){
			$row = null;
		}elseif(count($data)>1){
			//should always have one effective 
			//keep the last, delete the rest
			for($i=0;$i<count($data)-1;$i++){
				$qid = $data[$i]['q_id'];
				$this->del($qid,true);
			}
			$row = $data[count($data)-1];
		}else{
			$row = $data[0];
		}
		if(empty($row)){
			return $this->_add($key,$uploadfile,'',$bookname);
		}elseif($row['status']>0 && $row['status']<100){
			//converting
			return false;
		}elseif($row['status']==0 && $row['q_retry']==0){
			//still in queue
			$name = $uploadfile['name'];
			$path_parts = common::path_info($name);
			$path_parts1 = common::path_info($row['q_tmpname']);
			if(!empty($bookname)){
				$name = $bookname.'.'.$path_parts['extension'];
			}
			if(empty($this->tagstr) && empty($this->bsid) && empty($this->spell)){
				$this->data = json_decode($row['q_data'],TRUE);
			}else{
				//$tagstr = $this->TagTree->toString($this->treeIndex);
				//$tagstr = urlencode($tagstr);
				$this->data = $this->_setDataFromParams();
			}

			//get original timestamp
			$timestamp = $path_parts1['filename'];
	 		$workpath = WORK_PATH.'/'.$timestamp.'.'.$path_parts['extension'];
	 		$f = move_uploaded_file($uploadfile["tmp_name"],$workpath);
			$data1 = array();
			$data1['q_name'] = $name;
			$data1['q_tmpname'] = $workpath;
			$data1['q_key'] = $key;
			$data1['q_data'] = json_encode($this->data);
			$data1['createdate'] = date("Y-m-d H:i:s");
			if(!$f){
				$data1['status'] = QueueStatusEnum::FileNotGiving;
			}
			return $this->queue->updateByKey($key,$data1);
/*
		}elseif($row['status']==100){
			//$ee->Error('406.66');
			$this->_del($row);
			$path_parts1 = common::path_info($row['q_tmpname']);
			$timestamp = $path_parts1['filename'];
			return $this->_add($key,$uploadfile,$timestamp);
*/
		}else{
			//error, retry>3
			$this->_del($row);
			$this->queue->delByKey($key,false);
			$path_parts1 = common::path_info($row['q_tmpname']);
			$timestamp = $path_parts1['filename'];
			if(!empty($bookname)){
				$name = $bookname.'.'.$path_parts1['extension'];
			}
			return $this->_add($key,$uploadfile,$timestamp,$bookname);
		}
	}
	private function _del($row,$real=false){
		global $db;
		//if file is converting, then next
		if($row['status']>0 && $row['status']<100){
			$converting_qid = $row['q_id'];
			 return false;
		}
		$this->queue->del($row['q_id'],$real);
		//檢查是否轉書成功
		if(!empty($row['b_id'])){
			//刪除書
			$BookManager = new BookManager();
			$BookManager->del($row['b_id']);
		}
		//刪除工作資料夾中要轉換的檔案
		if(substr($row['q_tmpname'],0,strlen(WORK_PATH)) == WORK_PATH){
			//delete file, if exists
			@unlink($row['q_tmpname']);
		}
		return true;
	}
	public function del($id,$real){
		$row = $this->queue->getByID($id);
		return $this->_del($row,$real);
	}
	public function delByKey($key){
		//取得未被標記為刪除的key的項目
		$data = $this->queue->getListByKey($key);
		$converting_qid=0;
		$result = true;
		foreach($data as $row){
			$result = $result && $this->_del($row);
		}
		return $result;
	}
	public function getNext(){
		return $this->queue->getNext();
	}
	public function doNext1(){
		$item = $this->getNext();
		if($item){
			$tmpname = $item['q_tmpname'];
			$_key = $item['q_key'];
			$this->data = json_decode($item['q_data'],TRUE);
			//$this->tagstr = urldecode($this->data['tags']);
			$this->_setParamsFromData($this->data);
print_r(sprintf("\n[%s]===========================\ndoNext_heartbeat=",date('Y-m-d H:i:s')));
			$heartbeat = $this->checkHeartbeat();
print_r($heartbeat);

			$_doheartbeat=false;
			if($heartbeat){
				//if heartbeat is complete but lock file, try unlink again.
				$modifytimestamp = $this->getHeartbeatModifyTime();
				if($item['q_id']==$heartbeat['qid'] && $heartbeat['rate']=='100' && (time()-$modifytimestamp>300)){

					$this->removeHeartbeat();
					exit;
				}
				$filename = $heartbeat['filename'];
				$path_parts = common::path_info($filename);
				$subname = strtolower($path_parts['extension']);
				if(in_array($subname,$this->ecocat_allow_type)){
					$_doheartbeat=true;
				}
			}
	
			//only pdf & office file will be convert, and check progress
			//others just need to upload
			if($_doheartbeat){
				$qid = $heartbeat['qid'];
				$timestamp = $heartbeat['timestamp'];
				$ecocatid = $heartbeat['ecocatid'];	
				$rate = $heartbeat['rate'];
				$modifytimestamp = $this->getHeartbeatModifyTime();

				//something wrong
				if(empty($qid)
					|| empty($ecocatid)
					|| empty($timestamp)
					|| (time()-$modifytimestamp>1800)){
						$this->queue->retry($qid);
						$this->removeHeartbeat();
						exit;
				}

print_r("\ndoNext_progress:");
				//check ecocat log
				$result = $this->checkEcocatLog($ecocatid);
print_r("\ndoNext_checkEcocatLog=");
print_r($result);
				if($result['status']<=0){
print_r("\nFial!! ecocat log has error!! ".$result['msg']);
print_r("\nMail=");
var_dump($qid,$item['q_name'],$this->tagstr);
					//coverting book with 10min no progress, delete record in ecocat
					$EcocatConnector = new EcocatConnector($this->bsid);
					$EcocatConnector->DeleteBook($ecocatid);
					$this->queue->updateStatus($qid,QueueStatusEnum::Fail);
				}

				//check ecocat status
				$result = $this->checkEcocatProcess($ecocatid,$timestamp);
print_r("\ndoNext_checkEcocatProcess=");
print_r($result);
				if(isset($result['message'])){
print_r("\nProcess Error=");
var_dump($result);
					//something wrong
					$EcocatConnector = new EcocatConnector($this->bsid);
					$EcocatConnector->DeleteBook($ecocatid);
					$this->queue->retry($qid);
					$row = $this->get($qid);
					if($row['q_retry']>=3){
print_r("\nMail=");
var_dump($qid,$row['q_name'],$this->tagstr);
						$this->queue->updateStatus($qid,QueueStatusEnum::ExceedMaxRetryTimes);
						//$this->_mail(QueueStatusEnum::ExceedMaxRetryTimes,$qid,$row['q_name'],$this->tagstr);
					}
					$is_unlink = $this->removeHeartbeat();
					if($is_unlink) $this->doNext();
				}elseif($result['rate']==100){
print_r("\nSuccess!!");
					$this->updateHeartbeat($qid,$ecocatid,100);
					$bid = $this->addToBookshelf($ecocatid,$timestamp,$filename,$_key);
					$status = (ENABLE_DECENTRALIZED)?QueueStatusEnum::Success:QueueStatusEnum::ImportSuccess;
					$this->queue->update($qid,array('b_id'=>$bid,'status'=>$status));
					$is_unlink = $this->removeHeartbeat();
					if($is_unlink) $this->doNext();
				}else{
					if($rate != $result['rate']){
						$rate = $result['rate'];
						$this->updateHeartbeat($qid,$ecocatid,$rate);
						$this->queue->updateStatus($qid,intval($rate));
					}elseif((time()-$modifytimestamp>600) && ($result['rate']=='0') && $item['q_retry']==2){
print_r("\nTimeout!!");
print_r("\nMail=");
var_dump($qid,$item['q_name'],$this->tagstr);
						//coverting book with 10min no progress, delete record in ecocat
						$EcocatConnector = new EcocatConnector($this->bsid);
						$EcocatConnector->DeleteBook($ecocatid);
						$this->queue->updateStatus($qid,QueueStatusEnum::ConvertingTimeout);
						//$this->_mail(QueueStatusEnum::ConvertingTimeout,$qid,$item['q_name'],$this->tagstr);
					}
				}
			}else{
				$qid = $item['q_id'];
				$qname = $item['q_name'];
				$timestamp = time();				
				$filename = $item['q_name'];
				$path_parts = common::path_info($filename);
				$subname = strtolower($path_parts['extension']);
				$ecocatid = '';
				$rate = 0;
	
				if(!file_exists($tmpname)){
					$this->removeHeartbeat();
					$qdata = $item['q_data'];
					$decode_arr = json_decode($qdata,true);
					$tagstr = $decode_arr['tags'];
					$this->_mail(QueueStatusEnum::MissingFile,$qid,$tagstr);
					$this->queue->del($qid);
					$this->ini->update('uploadqueue','notice',$qid);
					exit;
				}
var_dump('doNext:',$filename);
				$this->createHeartbeat($qid,$timestamp,$ecocatid,$filename,$rate);

print_r("\ndoNext_convert=");
				if(in_array($subname,$this->ecocat_allow_type)){
print_r("\necocat=");
					$result = $this->convert(-1,$qid,$tmpname,$filename,$this->spell);
var_dump($result);
					if($result['code']=='406.61'){
print_r("\nError406.61!!");
print_r("\nMail=");
var_dump($qid,$filename,$this->tagstr);
						$this->queue->updateStatus($qid,QueueStatusEnum::IncorrectFilename);
						//$this->_mail(QueueStatusEnum::IncorrectFilename,$qid,$filename,$this->tagstr);
						$this->removeHeartbeat();
					}elseif(isset($result['process_id'])){
						$ecocatid = $result['process_id'];
						$this->updateHeartbeat($qid,$ecocatid,$rate);
					}elseif(isset($result['message'])){
print_r("\nConvert Error!!");
var_dump($result);
print_r("\nMail=");
var_dump($qid,$filename,$this->tagstr);
						$this->queue->updateStatus($qid,QueueStatusEnum::Fail);
						$this->removeHeartbeat();
					}
				}else{
print_r("\nzip=");
					$this->_propareTag();
					$result = $this->convert(-2,$qid,$tmpname,$filename,$this->spell,'zip');
print_r($result);
					//itu,zip
					if(ErrorHandler::isSuccess($result['code'])){
						$bid = intval($result['bid']);
						$this->_bindOnBook($bid);
						$status = (ENABLE_DECENTRALIZED)?QueueStatusEnum::Success:QueueStatusEnum::ImportSuccess;
						$this->queue->update($qid,array('b_id'=>$bid,'status'=>$status));
						$this->removeHeartbeat();
					}else{
						$this->queue->retry($qid);
						$row = $this->get($qid);
						if($row['q_retry']>=3){
print_r("\nRetry 3 times!!");
print_r("\nMail=");
var_dump($row['q_id'],$row['q_name'],$this->tagstr);
							$this->removeHeartbeat();
							$this->queue->updateStatus($qid,QueueStatusEnum::ExceedMaxRetryTimes);
							//$this->_mail(QueueStatusEnum::ExceedMaxRetryTimes,$row['q_id'],$row['q_name'],$this->tagstr);
						}else{
print_r("\nRetry under 3 times");
							$this->updateHeartbeat($qid,'error',$rate);
						}
					}
				}
			}
		}
	}
	public function doNext(){
		$LogManager = new LogManager(__FILE__,'uploadqueue');
		$LogManager->setLogRotate(true);
		//all mail notice will excuete by api/notice.php
		//LogManager will only send mail while log error
		$LogManager->setLogErrorLevel(E_ALL);
		$LogManager->setMailErrorLevel(0);

		$heartbeat = $this->checkHeartbeat();
		if($heartbeat){
			$qid			 = $heartbeat['qid'];
			$key			 = $heartbeat['key'];
			$rate			 = $heartbeat['rate'];
			$filename	 = $heartbeat['filename'];
			$ecocatid	 = $heartbeat['ecocatid'];
			$timestamp = $heartbeat['timestamp'];

			$path_parts = common::path_info($filename);
			$subname = strtolower($path_parts['extension']);

			$modifytimestamp = $this->getHeartbeatModifyTime();

			//missing heartbeat info
			if(empty($qid)
				|| empty($ecocatid)
				|| empty($timestamp)){
					//clear heartbeat
					$LogManager->warning('missing heartbeat info',sprintf("heartbeat=%s",json_encode($heartbeat)));
					$this->queue->updateStatus($qid,QueueStatusEnum::Fail);
					$is_unlink = $this->removeHeartbeat();
					if($is_unlink) $this->doNext();
					exit;
			}

			$row = $this->queue->getByID($qid);
			//missing record. should not happen!
			if(empty($row)){
				$LogManager->error('missing record','qid='.$qid.',heartbeat='.json_encode($heartbeat));
				$this->_mail(QueueStatusEnum::MissingRecord,$qid,$tagstr);
				$is_unlink = $this->removeHeartbeat();
				if($is_unlink) $this->doNext();
				exit;
			}

			$this->data = json_decode($row['q_data'],TRUE);
			$this->_setParamsFromData($this->data);
			if(empty($this->bsid)){
				$LogManager->warning('missing bsid(bskey)','qid='.$qid.',bsid='.$this->bsid.',bskey='.$this->bskey.',$this->data='.json_encode($this->data).',heartbeat='.json_encode($heartbeat));
			}

			//file retry over 3 times, should not happen
			if($row['q_retry']>=3){
				$LogManager->error($this->arrQueueStatusEnum[QueueStatusEnum::ExceedMaxRetryTimes],
					"heartbeat=".json_encode($heartbeat));
				$this->queue->updateStatus($qid,QueueStatusEnum::ExceedMaxRetryTimes);
				$is_unlink = $this->removeHeartbeat();
				if($is_unlink) $this->doNext();
				exit;
			}

			$process = $this->checkEcocatProcess($ecocatid,$timestamp);
			$ecocatlog = $this->checkEcocatLog($ecocatid);

			//1. server power off while converting
			//2. huge file has no progress over 30mins
			if(time()-$modifytimestamp>1800){
				$EcocatConnector = new EcocatConnector($this->bsid);
				$EcocatConnector->DeleteBook($ecocatid);
				$LogManager->error($this->arrQueueStatusEnum[QueueStatusEnum::ConvertingTimeout],
					"heartbeat=".json_encode($heartbeat));
				$this->queue->updateStatus($qid,QueueStatusEnum::ConvertingTimeout);
				$this->queue->retry($qid);
				$is_unlink = $this->removeHeartbeat();
				if($is_unlink) $this->doNext();
			}

			//error while converting files
			if(isset($process['message'])){
				//something wrong
				$EcocatConnector = new EcocatConnector($this->bsid);
				$EcocatConnector->DeleteBook($ecocatid);
				$LogManager->error($this->arrQueueStatusEnum[QueueStatusEnum::ErrorOccurredWhileConverting],
					sprintf("ecocat error message=%s,heartbeat=%s",$process['message'],json_encode($heartbeat)));
				$this->queue->updateStatus($qid,QueueStatusEnum::ErrorOccurredWhileConverting);
				$this->queue->retry($qid);
				$is_unlink = $this->removeHeartbeat();
				if($is_unlink) $this->doNext();
				exit;
			}

			//ecocat log has error
			if($ecocatlog['status']<0){
				//$ecocatlog['msg']
				$arr = array(-1=>QueueStatusEnum::MissingLogFile,-2=>QueueStatusEnum::ErrorInLog);
				//coverting book with 10min no progress, delete record in ecocat
				$EcocatConnector = new EcocatConnector($this->bsid);
				$EcocatConnector->DeleteBook($ecocatid);
				$_status = QueueStatusEnum::Fail;
				if(array_key_exists($ecocatlog['status'],$arr)){
					$_status = $arr[$ecocatlog['status']];
				}
				$LogManager->error($this->arrQueueStatusEnum[$_status],
					sprintf("status=%u,ecocat log message=%s,heartbeat=%s",$ecocatlog['status'],$ecocatlog['msg'],json_encode($heartbeat)));
				$this->queue->updateStatus($qid,$_status);
				//$this->queue->retry($qid);
				$is_unlink = $this->removeHeartbeat();
				if($is_unlink) $this->doNext();
				exit;
			}

			if($process['rate']!=$heartbeat['rate']){
				$LogManager->event('Convert processing', sprintf("qid=%u,key=%s,ecocatid=%s,rate=%s",$qid,$key,$ecocatid,$process['rate']));
				$this->updateHeartbeat($qid,$ecocatid,$process['rate']);
				$this->queue->updateStatus($qid,intval($process['rate']));
				exit;
			}

			//convert complete over 5mins but ecocat convert engin still not finish.
			//document has been deleted, status won't progresss to 200
			if($process['rate']=='100' && (time()-$modifytimestamp>300)){
				$LogManager->warning('Convert complete over 5mins but still not finish',
					sprintf("rate=100,heartbeat modify time=%s,ecocatid=%s,heartbeat=%s,process=%s",date("Y-m-d H:i:s",$modifytimestamp),$ecocatid,json_encode($heartbeat),json_encode($process)));
				$is_unlink = $this->removeHeartbeat();
				if($is_unlink) $this->doNext();
				exit;
			}

			//converting file delete by user
			if($row['isdelete']=='1'){
				//check ecocat process until success or fail to avoid convert at same time.
				exit;
			}

			//3rd time and over 10mins no prograss
			/*
			if((time()-$modifytimestamp>600) && ($process['rate']=='0') && $retry>=2){
				//coverting book with 10min no progress, delete record in ecocat
				$EcocatConnector = new EcocatConnector($this->bsid);
				$EcocatConnector->DeleteBook($ecocatid);
				$this->queue->updateStatus($qid,QueueStatusEnum::ConvertingTimeout);
				//$this->_mail(QueueStatusEnum::ConvertingTimeout,$qid,$item['q_name'],$this->tagstr);
			}*/

			//conver success and less than 5mins
			if($process['rate']==100){
				$this->updateHeartbeat($qid,$ecocatid,100);
				$bid = $this->addToBookshelf($ecocatid,$timestamp,$filename,$key);
				$status = (ENABLE_DECENTRALIZED)?QueueStatusEnum::Success:QueueStatusEnum::ImportSuccess;
				$LogManager->event('Convert complete', sprintf("bid=%u,ecocatid=%s,process=%s",$bid,$ecocatid,json_encode($process)));
				$this->queue->update($qid,array('b_id'=>$bid,'status'=>$status));
				$is_unlink = $this->removeHeartbeat();
				if($is_unlink) $this->doNext();
			}
		}else{
			$item = $this->getNext();
			if($item){
				$qid = $item['q_id'];
				$qkey = $item['q_key'];
				$retry = $item['q_retry'];
				$filename = $item['q_name'];
				$tmpname = $item['q_tmpname'];
				$qdata = $item['q_data'];

				$this->data = json_decode($qdata,TRUE);
				$this->_setParamsFromData($this->data);
				$timestamp = time();				

				$path_parts = common::path_info($filename);
				$subname = strtolower($path_parts['extension']);
				$ecocatid = '';
				$rate = 0;

				if(!file_exists($tmpname)){					
					$LogManager->error('Missing tmp file',
						"tmpfile=".$tmpname." ,heartbeat=".json_encode($heartbeat));
					//$this->_mail(QueueStatusEnum::MissingFile,$qid,$tagstr);
					$this->queue->updateStatus($qid,QueueStatusEnum::MissingFile);
					$this->doNext();
					exit;
				}

				$filesize = filesize($tmpname);
				$this->createHeartbeat($qid,$timestamp,'',$filename,0,$qkey);

				$title = '';
				if(in_array($subname,$this->ecocat_allow_type)){
					//something wrong may occur while start conver, and will stop program execution.
					$result = $this->convert(-1,$qid,$tmpname,$filename,$this->spell);
					if(isset($result['process_id'])){
						$ecocatid = $result['process_id'];
						$_rate = $result['rate'];
						$this->queue->updateStatus($qid,intval($_rate));
						$this->updateHeartbeat($qid,$ecocatid,$_rate);
						$LogManager->event('Upload success',
							sprintf("qid=%u, filename=%s, ecocatid=%s, key=%s, tmpfile=%s, filesize=%s, timestamp=%s",$qid,$filename,$ecocatid,$qkey,$tmpname,$filesize,$timestamp));
					}elseif($result['code']=='406.61'){
						$_title = $this->arrQueueStatusEnum[QueueStatusEnum::IncorrectFilename];
						$LogManager->error($title,
							"filename=".$filename."\ndb record=".json_encode($item));
						$this->queue->updateStatus($qid,QueueStatusEnum::IncorrectFilename);
						$this->updateHeartbeat($qid,$title,QueueStatusEnum::IncorrectFilename);
						$this->doNext();
					}elseif(isset($result['message'])){
						$_title = $this->arrQueueStatusEnum[QueueStatusEnum::ErrorOccurredWhileConverting];
						$LogManager->error($_title,
							"ecocat message=".json_encode($result));
						$this->queue->updateStatus($qid,QueueStatusEnum::ErrorOccurredWhileConverting);
						$this->updateHeartbeat($qid,$title,QueueStatusEnum::ErrorOccurredWhileConverting);
						$this->doNext();
					}else{
						$_title = $this->arrQueueStatusEnum[QueueStatusEnum::Fail];
						$LogManager->error($_title,
							"ecocat message=".json_encode($result));
						$this->queue->updateStatus($qid,QueueStatusEnum::Fail);
						$this->updateHeartbeat($qid,$title,QueueStatusEnum::Fail);
						$this->doNext();
					}
				}elseif(in_array($subname,$this->file_allow_type)){
					$this->_propareTag();
					$result = $this->convert(-2,$qid,$tmpname,$filename,$this->spell,'zip');
					if(!ErrorHandler::isSuccess($result['code'])){
						$arr = array('401.23'=>QueueStatusEnum::NoAuth,
													'406.30'=>QueueStatusEnum::NoAuth,
													'406.31'=>QueueStatusEnum::UnknowFileFormat,
													'406.61'=>QueueStatusEnum::IncorrectFilename,
													'500.43'=>QueueStatusEnum::ErrorOccurredWhileConverting);
						if(array_key_exists($result['code'],$arr)){
							$_status = $arr[$result['code']];
						}else{
							$_status = QueueStatusEnum::Fail;
						}
						$LogManager->error($this->arrQueueStatusEnum[$_status],
							"ecocat message=".json_encode($result));
						$this->queue->updateStatus($qid,$_status);
						$this->removeHeartbeat();
						exit;
					}
					$bid = intval($result['bid']);
					$this->_bindOnBook($bid);
					$status = (ENABLE_DECENTRALIZED)?QueueStatusEnum::Success:QueueStatusEnum::ImportSuccess;
					$this->queue->update($qid,array('b_id'=>$bid,'status'=>$status));
					$is_unlink = $this->removeHeartbeat();
					if($is_unlink) $this->doNext();
				}else{
					$LogManager->error($this->arrQueueStatusEnum[QueueStatusEnum::UnknowFileFormat],
						"filename=".$filename."\ndb record=".json_encode($item));
					$this->queue->updateStatus($qid,QueueStatusEnum::UnknowFileFormat);
					$is_unlink = $this->removeHeartbeat();
					if($is_unlink) $this->doNext();
				}
			}
		}
	}
	public function checkHeartbeat(){
		if(is_file($this->heartbeat)){
			$str = file_get_contents($this->heartbeat);
			list($qid,$timestamp,$ecocatid,$filename,$rate,$key) = explode('|',$str);
			return array('qid'=>$qid,'timestamp'=>$timestamp,'ecocatid'=>$ecocatid,'filename'=>$filename,'rate'=>$rate,'key'=>$key);
		}
		return false;
	}
	public function getHeartbeatModifyTime(){
		if(!is_file($this->heartbeat)){
			return 0;
		}
		return filemtime($this->heartbeat);
	}
	//heartbeat: qid,timestamp,ecocatid,rate
	public function createHeartbeat($qid,$timestamp,$ecocatid,$filename,$rate,$key){
		$this->removeHeartbeat();
		$str = implode('|',array($qid,$timestamp,$ecocatid,$filename,$rate,$key));
		return file_put_contents($this->heartbeat,$str);
	}
	public function updateHeartbeat($qid,$ecocatid,$rate){
		$arr = $this->checkHeartbeat();
		if($arr['qid']==$qid){
			$str = implode('|',array($qid,$arr['timestamp'],$ecocatid,$arr['filename'],$rate,$arr['key']));
			return file_put_contents($this->heartbeat,$str);
		}
		return false;
	}
	public function removeHeartbeat(){
		return @unlink($this->heartbeat);
	}
	public function convert($cate2,$qid,$tmpname,$filename,$spell,$skin='',$language_type=''){
		global $db;
		global $ee;
		global $fs;
		//$this->setTagRoot($this->tagroot);
		//$this->TagTree->loadString($this->tagstr);
		//$cate2 = $this->_doCate();
		$_hassetbsid=false;

		$spell_mapping = array('right'=>1,'left'=>2);
		if(!empty($this->bskey)){
			$bookshelf = new bookshelf($db);
			$row = $bookshelf->getByKey($this->bskey);
			if(!empty($row)){
				$this->bsid = $row[0]['bs_id'];
				$_hassetbsid=true;
			}
		}
		if(!empty($this->bsid)){
		}elseif(empty($this->bsid) && !empty($this->bskey)){
			$data = array();
			$data['bs_name']=$this->bsname;
			$data['bs_title']=$this->bsname;
			$data['bs_key']=$this->bskey;
			$BookshelfManager = new BookshelfManager();
			$result = $BookshelfManager->CreateBookshelf($this->uid,$data);
			if($result['status']){
				$this->bsid=$result['bsid'];
				$_hassetbsid=true;
			}
		}
		if($_hassetbsid){
			$this->data = $this->_setDataFromParams();
			$data1=array();
			$data1['q_data'] = json_encode($this->data);
			$queue = new queue($db);
			$queue->update($qid,$data1);
		}
		if($cate2==-2){
			$cate2 = $this->_doCate();
		}
		//valid filename
		$f = $fs->test($filename,'name');
		if($f){
			//param: bs, site, cate2
			$ConvertManager = new ConvertManager(true,$this->bsid);
			$ConvertManager->setUploadfile($tmpname,$filename);
			$result = $ConvertManager->Convert($cate2,$spell_mapping[$spell],$skin,$language_type);
			return $result;
		}else{
			return $ee->Warning('406.61',false);
		}
	}
	public function checkEcocatProcess($process_id,$timestamp){
		//param: bs, site, cate2
		$EcocatConnector = new EcocatConnector($this->bsid);
		$result = $EcocatConnector->Process($process_id,$timestamp);
		if(array_key_exists('detail',$result)){
			return $result['detail'];
		}else{
			return $result['error'][0];
		}
	}
	private function _doCate(){
		global $db;
		global $ee;

		if(!empty($this->db)){
			$db = $this->db;
		}
		if(!empty($this->ee)){
			$ee = $this->ee;
		}

		$category = new category($db);
		if(!empty($this->cid)){
			$row = $category->getByID($this->cid);
			//check it's exist cate
			if(empty($row)){
				$ee->Error('404.45');
			}
			//check is sub category
			if(intval($row['c_parent_id'])==0){
				$ee->Error('406.46');
			}
			return $this->cid;
		}

		//create category
		if(empty($this->parentcateKey) || empty($this->childcateKey)){
			return false;
		}

		$_newParentCate=false;
		$_newChildCate=false;
		$_dataParentKey=$this->parentcateKey;
		if(!empty($this->bskey)){
			$_dataParentKey = sprintf('%s_%s',$this->bskey,$this->parentcateKey);
		}
		$dataParent = $category->getByKey($_dataParentKey);
		$pNode = $this->TagTree->getNode($this->parentcateKey);
		if(!empty($dataParent)){
			if($dataParent['c_parent_id']!=0){
				//wrong setting, find parentcateKey, but is a child cate.
				$ee->Error('406');
			}
			$pid = $dataParent['c_id'];
			$pkey = $dataParent['c_key'];
/*
		}elseif(!empty($pNode)){
			//in tag table
			$_pkey = sprintf('%u_%u',$this->bsid,$pNode->data['id']);
			//see if in category
			$_dataParent = $category->getByKey($_pkey);
			if(empty($_dataParent)){
				$pkey = $this->parentcateKey;
				$pName = $this->_parentcateName;
				$_newParentCate=true;
			}else{
				$pid = $_dataParent['c_id'];
				$pkey = $_pkey;
			}*/
		}else{
			$pkey=$this->parentcateKey;
			$pName = $this->_parentcateName;
			$_newParentCate=true;
		}
		if($_newParentCate){
			if(!empty($this->bskey)){
				$pkey = sprintf('%s_%s',$this->bskey,$pkey);
			}
			$data = array();
			$data['c_parent_id'] = 0;
			$data['c_name'] = $pName;
			$data['bs_id'] = $this->bsid;
			$data['bs_key'] = $this->bskey;
			$data['c_key'] = $pkey;
			$data['c_parent_key'] = '';
			$pid = $category->insert($data);
		}

		$_dataChildKey = $this->childcateKey;
		if(!empty($pkey)){
			$_dataChildKey=sprintf('%s_%s',$pkey,$this->childcateKey);
		}
		$dataChild = $category->getByKey($_dataChildKey);
		$cNode = $this->TagTree->getNode($this->childcateKey);
		if(!empty($dataChild)){
			if($dataChild['c_parent_id']==0){
				//wrong setting, find childcateKey, but is a parent cate.
				$ee->Error('406');
			}
			$cate2 = $dataChild['c_id'];
			$ckey = $dataChild['c_key'];
/*
		}elseif(!empty($cNode)){
			//in tag table
			$_ckey = sprintf('%u_%u_%u',$this->bsid,$pNode->data['id'],$cNode->data['id']);
			//see if in category
			$_dataChild = $category->getByKey($_ckey);
			if(empty($_dataChild)){
				$ckey = $this->childcateKey;
				$cName = $this->_childcateName;
				$_newChildCate=true;
			}else{
				$cate2 = $_dataParent['c_id'];
				$ckey = $_ckey;
			}*/
		}else{
			$ckey = $this->childcateKey;
			$cName = $this->_childcateName;
			$_newChildCate=true;
		}
		if($_newChildCate){
			if(!empty($pkey)){
				$ckey = sprintf('%s_%s',$pkey,$ckey);
			}
			$data = array();
			$data['c_parent_id'] = $pid;
			$data['c_name'] = $cName;
			$data['bs_id'] = $this->bsid;
			$data['bs_key'] = $this->bskey;
			$data['c_key'] = $ckey;
			$data['c_parent_key'] = $pkey;
			$cate2 = $category->insert($data);
		}
		return $cate2;
	}
	private function _setDefaultTag(){
		$this->_setTagRoot('#Time');

		//$node_T = $this->TagTree->getNode('#Time');
		$node_Y = $this->TagTree->getNode('#Year');
		$node_M = $this->TagTree->getNode('#Month');
/*
		$tk='#Time';
		$tv='系統時間';
*/
		$ppk='#Year';
		$ppv='西元年';

		$pk=sprintf('#DC%u',date('Y'));
		$pv=date('Y');

		$cpk='#Month';
		$cpv='月份';

		$ck=sprintf('#Month%u',date('m'));
		$cv=date('M');

		$this->setParentCate($ppk);
		$this->setChildCate($cpk);
/*
		if(empty($node_T)){
			$this->setTag('root',$tk,$tv);
		}else{
			$this->setTag('root',$node_T->data['key'],$node_T->data['val']);
		}
*/
		if(empty($node_Y)){
			$this->setTag('#Time',$ppk,$ppv);
		}else{
			$this->setTag('#Time',$node_Y->data['key'],$node_Y->data['val']);
		}
		$this->setTag('#Year',$pk,$pv);
		if(empty($node_M)){
			$this->setTag('#Time',$cpk,$cpv);
		}else{
			$this->setTag('#Time',$node_M->data['key'],$node_M->data['val']);
		}
		$this->setTag('#Month',$ck,$cv);
		$this->_setTag();
	}
	private function _propareTag(){
		$this->_setTagRoot($this->tagroot);
		if(empty($this->parentcateKey) || empty($this->childcateKey)){
			$this->_setDefaultTag();
		}else{
			$this->_setTag();
		}
	}

	private function _bindOnBook($bid){
		$this->TagTree->saveDB();
		$this->TagTree->bindOnBook($bid,$this->treeIndex);
	}
	public function addToBookshelf($process_id,$timestamp,$filename,$key){
		global $db;
		$this->_propareTag();
		$cate2 = $this->_doCate();
		$ConvertManager = new ConvertManager($site,$this->bsid);
		$ConvertManager->setKey($key);
		$result = $ConvertManager->ConvertProgress($cate2,$process_id,$timestamp,$filename);
		$bid = intval($result['detail']['bid']);

		//$this->TagTree->loadString($this->tagstr);
		$this->_bindOnBook($bid);
		return $bid;
	}
/*
  public function getEbookFileFromURL($urlfile,$filename){
  	global $ee;
  	global $db;
		if(!empty($this->ee)){
			$ee = $this->ee;
		}
		if(filter_var($url, FILTER_VALIDATE_URL)){
			$arrUrl = parse_url($urlfile);
			$path_info = common::path_info($arrUrl['path']);
			if(empty($path_info['basename'])){
				//url doesn't content filename
				return $ee->Warning('406.91');
			}else{
				//check if url exists
				$file_headers = @get_headers($urlfile);
				if(strpos($file_headers[0],'200 OK')>=0){
					$extension = '';
					if(empty($path_info['extension'])){
						//extension should not be empty
						$ee->add('msg','extension should not be empty');
						return $ee->Warning('406.62');
					}else{
						$extension = '.'.$path_info['extension'];
					}
					$tmpname = WORK_PATH .'/qu'. time() .$extension;
					if(file_put_contents($tmpname, fopen($urlfile, 'r'))){
						$data = array();
						$data['q_name'] = $filename;
						$data['q_tmpname'] = $tmpname;
						$queue = new queue($db);
						$queue->insert($data);
					}else{
						//something like hard disk full
						$ee->add('msg','system error!');
						return $ee->Warning('500');
					}
				}else{
					//url is not exists
					return $ee->Warning('404.35');
				}
			}
		}else{
			//not a url
			return $ee->Warning('406.62');
		}
  }
*/
	private function _mail($status,$qid,$tagstr){
		$decode_arr = json_decode(sprintf('{"str":"%s"}',$tagstr),true);
		$tags = $this->_parseTagstr($decode_arr['str']);
		$arr = array();
		foreach($tags as $t){
			list($type,$key,$val) = $t;
			$arr[$type]=$key.' - '.$val;
		}
		$pn = 'Not set!';
		if(array_key_exists('ProjectName',$arr)){
			$pn = $arr['ProjectName'];
		}elseif(array_key_exists('pn',$arr)){
			$pn = $arr['pn'];
		}
		$this->mail($status,$qid,$pn);
	}
	public function parseTagstr($tagstr){
		$decode_arr = json_decode(sprintf('{"str":"%s"}',$tagstr),true);
		$tags = $this->_parseTagstr($decode_arr['str']);
		$arr = array();
		foreach($tags as $t){
			list($type,$key,$val) = $t;
			$arr[$type]=$key.' - '.$val;
		}
		return $arr;
	}
	function getUploadQueueEnumString($UploadQueueEnum=''){
		if(empty($UploadQueueEnum)) return $this->arrQueueStatusEnum;
		if(array_key_exists($UploadQueueEnum,$this->arrQueueStatusEnum)){
			$errmsg = $this->arrQueueStatusEnum[$UploadQueueEnum];
		}else{
			$errmsg = '錯誤';
		}
		return $errmsg;
	}
	public function mail($status,$qid,$name){
		global $db;
		print_r("\ncall send mail");

		mb_internal_encoding('UTF-8');
		
		//send code through mail
		//SMTP needs accurate times, and the PHP time zone MUST be set
		//This should be done in your php.ini, but this is how to do it if you don't have access to that
		//date_default_timezone_set('Asia/Taipei');
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
	
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
	
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;

		$mail->CharSet = 'utf-8';

		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
	
		//Set the hostname of the mail server
		$mail->Host = GMAIL_HOST;
		// use
		// $mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6
		
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = 587;
		
		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = 'tls';
		
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = GMAIL_USERNAME;

		//Password to use for SMTP authentication
		$mail->Password = GMAIL_PASSWORD;
		
		//Set who the message is to be sent from
		$mail->setFrom(GMAIL_FROM, GMAIL_FROMNAME);
		
		//Set an alternative reply-to address
		$mail->addReplyTo(GMAIL_APPLYTO, GMAIL_APPLYTONAME);
		
		//Set who the message is to be sent to
		$mail->addCC('anderson@ttii.com.tw', 'Anderson');
		//$mail->addCC('support@ttii.com.tw', 'SUPPORT');
		if(!empty($UPLOADQUEUE_ERROR_APPLYTO1)){
			$mail->addAddress($UPLOADQUEUE_ERROR_APPLYTO1, $UPLOADQUEUE_ERROR_APPLYTO1);
		}
		if(!empty($UPLOADQUEUE_ERROR_APPLYTO2)){
			$mail->addAddress($UPLOADQUEUE_ERROR_APPLYTO2, $UPLOADQUEUE_ERROR_APPLYTO2);
		}

		$arr_status = array(QueueStatusEnum::Fail=>'轉檔失敗',
												QueueStatusEnum::MissingFile=>'檔案遺失',
												QueueStatusEnum::IncorrectFilename=>'檔案名稱包含斜線/、或其他不允許的字元',
												QueueStatusEnum::ConvertingTimeout=>'轉檔逾時(超過30min)',
												QueueStatusEnum::ConvertSuspend=>'轉檔時意外中止',
												QueueStatusEnum::ImportFail=>'匯入到分散式系統失敗',
												QueueStatusEnum::ExceedMaxRetryTimes=>'轉檔三次後仍失敗');

		if(array_key_exists($status,$arr_status)){
			$errmsg = $arr_status[$status];
		}else{
			$errmsg = '錯誤';
		}
		//Set the subject line
		//$mail->Subject = mb_convert_encoding(sprintf('[數位圖書館]檔案轉換失敗通知 - %s',$pn),"utf-8","big5");
		$subject = sprintf('[數位圖書館]檔案轉換失敗通知 - %s',$name);
		$content = sprintf("[%s] %s\n轉檔編號: %u\n檔案名稱: %s\n請至數位圖書館管理後台重新上傳。",date('Y-m-d H:i:s'),$errmsg,$qid,$name);
		$mail->Subject = $subject;
		$mail->Body = $content;

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML($content, MAIL_TEMPLATE_PATH);

		//Replace the plain text body with one created manually
		//$mail->AltBody = $content;
		
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');

		//send the message, check for errors
		if ($mail->send()) {
			print_r("\nsend mail success");
			$maillog = new maillog($db);
			$data = array(
				'ML_TARGET' => MailTypeEnum::UploadQueue,
				'TARGET_ID' => (int)$qid,
				'ML_SUBJECT' => $subject,
				'ML_CONTENT' => $content
			);
			$maillog->insert($data);
		}else{
print_r("\n".$content);
		}
	}
	public function checkEcocatLog($ecocatid){
		$path = sprintf('%s/lib/ecolab/msg/%s.html',ECOCAT_ROOT,$ecocatid);
		if(!is_file($path)){
			return array('status'=>-1,'msg'=>sprintf('path:%s is not exist.',$path));
		}
		$contents = file_get_contents($path);
		$arr = explode("\n",$contents);
		for($i=count($arr);$i>=0;$i--){
			$str = $arr[$i];
			if($arr[$i]!="") break;
		}
		if(strpos($str,'ERROR')===false){
			return array('status'=>0,'msg'=>$str);
		}else{
			return array('status'=>-2,'msg'=>$str);
		}
	}
}
?>
