<?PHP
/*
status code progress
add	converting	converted	addBookshelf
0	=>	1~99		=>	100		=>		200	(done)
												=>		-1	(fail)
*/
//需依照計畫名稱、審查年度、期別將文件依符合審查作業的方式歸類在電子書系統內。
//ProjectName(cate1), ExamineYear(cate2), Stage

//zip is allowed to insert in queue, but zip insert to system is not take much time,
//no reason add in queue in implement, zip file will add immediately to system.

class UploadQueue{
	var $TagTree;
	var $tagdoc;
	var $tagstr;
	var $treeIndex;
	var $tagroot;
	var $tags;

	var $parentcate;
	var $parentcateKey;
	var $childcate;
	var $childcateKey;
	var $bsid;
	var $cid;
	var $spell;
	var $site;
	var $uploadfile;
	var $ecocat_allow_type;
	var $data;

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
		$this->TagTree = new TagTree();
		$this->tags = array();
		$this->heartbeat = CACHE_PATH.'/heartbeat';
		$this->ecocat_allow_type = array('pdf','ppt','doc','xls','pptx','docx','xlsx');
	}
	public function setBSID($_bsid){
		$this->bsid = $_bsid;
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
		$arr = explode('!',$str);
		foreach($arr as $a){
			$tags[] = explode(',',$a);
		}
		return $tags;
	}
	private function _makeTagKey($type,$key){
		if(strpos($key,'#')===0 || $type=='root' || empty($key)){
			$k=$key;
		}else{
			$k=sprintf('%s_%s',$type,$key);
		}
		return $k;
	}
	private function _setTag(){
		$tags = $this->_parseTagstr($this->tagstr);
		foreach($tags as $t){
			$this->tags[$k] = $t;
			list($type,$key,$val) = $t;
			$k = $this->_makeTagKey($type,$key);
			$tag = array('key'=>$k,'val'=>$val);
			$treenode = $this->TagTree->getNode($k);
			if(!empty($treenode)){
				$type=$treenode->parent->data['key'];
				$tag = $treenode->data;
				$k = $treenode->data['key'];
				
			}
			//make sure node can add to tag tree
			$treenode1 = $this->TagTree->getNode($type);
			if(empty($treenode1)){
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
				$pt = $treenode1->parent->data['key'];
				$ptag = $treenode1->data;
				$pk = $treenode1->data['key'];
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

		$k = $this->_makeTagKey($type,$key);
		switch($type){
			case $this->parentcate:
				$this->parentcateKey = $k;
				break;
			case $this->childcate:
				$this->childcateKey = $k;
				break;
		}
	}
	public function setSITE($_site){
		$this->site = $_site;
	}
	public function setSpell($_spell){
		$this->spell = $_spell;
	}
	private function _add($key,$uploadfile,$timestamp=''){
		global $db;
		
		$this->uploadfile = $uploadfile;
		
		$name = $uploadfile['name'];
		$path_parts = common::path_info($name);
		
		//move file to work folder
		if(empty($timestamp)){
			$timestamp = time();
		}
 		$workpath = WORK_PATH.'/'.$timestamp.'.'.$path_parts['extension'];
 		move_uploaded_file($uploadfile["tmp_name"],$workpath);

		//$tagstr = $this->TagTree->toString($this->treeIndex);
		//$tagstr = urlencode($tagstr);
		$this->data = array('tags'=>$this->tagstr,
			'bsid'=>$this->bsid,
			'cid'=>$this->cid,
			'spell'=>$this->spell,
			'tagroot'=>$this->tagroot,
			'pcatek'=>$this->parentcateKey,
			'ccatek'=>$this->childcateKey);

		//insert file to queue
		$data = array();
		$data['q_name'] = $name;
		$data['q_tmpname'] = $workpath;
		$data['q_key'] = $key;
		$data['q_data'] = json_encode($this->data);
		$queue = new queue(&$db);
		return $queue->insert($data);
	}
	public function get($id){
		global $db;
		$queue = new queue(&$db);
		return $queue->getByID($id);
	}
	public function add($key,$uploadfile){
		global $db;
		global $ee;

		$this->uploadfile = $uploadfile;

		$queue = new queue(&$db);
		$data = $queue->getListByKey($key);
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
			return $this->_add($key,$uploadfile);
		}elseif($row['status']>0 && $row['status']<100){
			//converting
			return false;
		}elseif($row['status']==0 && $row['q_retry']==0){
			//still in queue
			$name = $uploadfile['name'];
			$path_parts = common::path_info($name);
			$path_parts1 = common::path_info($row['q_tmpname']);
			if(empty($this->tagstr) && empty($this->bsid) && empty($this->spell)){
				$this->data = json_decode($row['q_data'],TRUE);
			}else{
				//$tagstr = $this->TagTree->toString($this->treeIndex);
				//$tagstr = urlencode($tagstr);
				$this->data = array('tags'=>$this->tagstr,
					'bsid'=>$this->bsid,
					'cid'=>$this->cid,
					'spell'=>$this->spell,
					'tagroot'=>$this->tagroot,
					'pcatek'=>$this->parentcateKey,
					'ccatek'=>$this->childcateKey);
			}

			//get original timestamp
			$timestamp = $path_parts1['filename'];
	 		$workpath = WORK_PATH.'/'.$timestamp.'.'.$path_parts['extension'];
	 		move_uploaded_file($uploadfile["tmp_name"],$workpath);
			$data1 = array();
			$data1['q_name'] = $name;
			$data1['q_tmpname'] = $workpath;
			$data1['q_key'] = $key;
			$data1['q_data'] = json_encode($this->data);
			$data1['createdate'] = date("Y-m-d H:i:s");
			$queue = new queue(&$db);
			return $queue->updateByKey($key,$data1);
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
			$queue->delByKey($key,false);
			$path_parts1 = common::path_info($row['q_tmpname']);
			$timestamp = $path_parts1['filename'];
			return $this->_add($key,$uploadfile,$timestamp);
		}
	}
	private function updateStatus($id,$status){
		global $db;
		$queue = new queue(&$db);
		$queue->updateStatus($id,$status);
	}
	private function retry($id){
		global $db;
		$queue = new queue(&$db);
		$queue->retry($id);
	}
	private function _del($row,$real=false){
		global $db;
		//if file is converting, then next
		if($row['status']>0 && $row['status']<100){
			$converting_qid = $row['q_id'];
			 return false;
		}
		$queue = new queue(&$db);
		$queue->del($row['q_id'],$real);
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
		global $db;
		$queue = new queue(&$db);
		$row = $queue->getByID($id);
		return $this->_del($row,$real);
	}
	public function delByKey($key){
		global $db;
		$queue = new queue(&$db);
		//取得未被標記為刪除的key的項目
		$data = $queue->getListByKey($key);
		$converting_qid=0;
		$result = true;
		foreach($data as $row){
			$result = $result && $this->_del($row);
		}
		return $result;
	}
	public function getNext(){
		global $db;
		$queue = new queue(&$db);
		$data = $queue->getNext();
		return $data;
	}
	public function doNext(){
		global $db;
		$item = $this->getNext();
		if($item){
			$tmpname = $item['q_tmpname'];
			$this->data = json_decode($item['q_data'],TRUE);
			//$this->tagstr = urldecode($this->data['tags']);
			$this->tagstr = $this->data['tags'];
			$this->spell = $this->data['spell'];
			$this->bsid = $this->data['bsid'];
			$this->cid = $this->data['cid'];
			$this->tagroot = $this->data['tagroot'];
			$this->parentcateKey = $this->data['pcatek'];
			$this->childcateKey = $this->data['ccatek'];
print_r(sprintf("\n[%s]===========================\ndoNext_heartbeat=",date('Y-m-d h:i:s')));
			$heartbeat = $this->checkHeartbeat();
print_r($heartbeat);

			$_doheartbeat=false;
			if($heartbeat){
				$filename = $heartbeat['filename'];
var_dump('filename='.$filename);
					$path_parts = common::path_info($filename);
					$subname = strtolower($path_parts['extension']);
var_dump('subname='.$subname);
					if(in_array($subname,$this->ecocat_allow_type)){
						$_doheartbeat=true;
					}
			}
var_dump($_doheartbeat);
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
					|| (time()-$modifytimestamp>600)){
						$this->retry($qid);
						$this->removeHeartbeat();
						exit;
				}

				$result = $this->checkEcocatProcess($ecocatid,$timestamp);
print_r("\ndoNext_progress=");
print_r($result);
				if(isset($result['message'])){
					$EcocatConnector = new EcocatConnector($this->bsid);
					$EcocatConnector->DeleteBook($ecocatid);
					$this->retry($qid);
					$this->doNext();
				}elseif($result['rate']==100){
					$this->updateHeartbeat($qid,$ecocatid,100);
					$bid = $this->addToBookshelf($ecocatid,$timestamp,$filename);
					$queue = new queue(&$db);
					$queue->update($qid,array('b_id'=>$bid));
					if(ENABLE_DECENTRALIZED){
						$this->updateStatus($qid,QueueStatusEnum::Success);
					}else{
						$this->updateStatus($qid,QueueStatusEnum::ImportSuccess);
					}
					$this->removeHeartbeat();
					$this->doNext();
				}else{
					if($rate != $result['rate']){
						$rate = $result['rate'];
						$this->updateHeartbeat($qid,$ecocatid,$rate);
						$queue = new queue(&$db);
						$queue->updateStatus($qid,intval($rate));
					}elseif((time()-$modifytimestamp>600) && ($result['rate']=='0') && $item['q_retry']==2){
						//coverting book with 10min no progress, delete record in ecocat
						$EcocatConnector = new EcocatConnector($this->bsid);
						$EcocatConnector->DeleteBook($ecocatid);
					}
				}
			}else{
				$qid = $item['q_id'];
				$timestamp = time();				
				$filename = $item['q_name'];
				$tmpname = $item['q_tmpname'];
				$path_parts = common::path_info($filename);
				$subname = strtolower($path_parts['extension']);
				$ecocatid = '';
				$rate = 0;

				if(!file_exists($tmpname)){
					$this->updateStatus($qid,QueueStatusEnum::MissingFile);
					$this->removeHeartbeat();
					exit;
				}

				$this->createHeartbeat($qid,$timestamp,$ecocatid,$filename,$rate);

				$result = $this->convert($tmpname,$filename,$this->spell);
print_r("\ndoNext_convert=");
print_r($result);
				if(in_array($subname,$this->ecocat_allow_type)){
print_r("\necocat");
					if(isset($result['process_id'])){
						$ecocatid = $result['process_id'];
					}elseif(isset($result['message'])){
						$ecocatid = 'error';
					}
					$this->updateHeartbeat($qid,$ecocatid,$rate);
				}else{
print_r("\nzip");
					//itu,zip
					if($result['code']=='200'){
						$bid = $result['bid'];
						$queue = new queue(&$db);
						$queue->update($qid,array('b_id'=>$bid));
						$this->removeHeartbeat();
					}else{
						$this->retry($qid);
						$row = $this->get($qid);
						if($row['q_retry']>=3){
							$this->removeHeartbeat();
						}else{
							$this->updateHeartbeat($qid,'error',$rate);
						}
					}
				}
			}
		}
	}
	public function checkHeartbeat(){
		if(is_file($this->heartbeat)){
			$str = file_get_contents($this->heartbeat);
			list($qid,$timestamp,$ecocatid,$filename,$rate) = explode('|',$str);
			return array('qid'=>$qid,'timestamp'=>$timestamp,'ecocatid'=>$ecocatid,'filename'=>$filename,'rate'=>$rate);
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
	public function createHeartbeat($qid,$timestamp,$ecocatid,$filename,$rate){
		$this->removeHeartbeat();
		$str = implode('|',array($qid,$timestamp,$ecocatid,$filename,$rate));
		return file_put_contents($this->heartbeat,$str);
	}
	public function updateHeartbeat($qid,$ecocatid,$rate){
		$arr = $this->checkHeartbeat();
		if($arr['qid']==$qid){
			$str = implode('|',array($qid,$arr['timestamp'],$ecocatid,$arr['filename'],$rate));
			return file_put_contents($this->heartbeat,$str);
		}
		return false;
	}
	public function removeHeartbeat(){
		@unlink($this->heartbeat);
	}
	public function convert($tmpname,$filename,$spell,$skin='',$language_type=''){
		//$this->setTagRoot($this->tagroot);
		//$this->TagTree->loadString($this->tagstr);
		//$cate2 = $this->_doCate();
		$cate2 = -1;

		$spell_mapping = array('right'=>1,'left'=>2);
		//param: bs, site, cate2
		$ConvertManager = new ConvertManager($site,$this->bsid);
		$ConvertManager->setUploadfile($tmpname,$filename);
		$result = $ConvertManager->Convert($cate2,$spell_mapping[$spell],$skin,$language_type);
		return $result;
	}
	public function checkEcocatProcess($process_id,$timestamp){
		//param: bs, site, cate2
		$EcocatConnector = new EcocatConnector($this->bsid);
		$result = $EcocatConnector->Process($process_id,$timestamp);
		return $result['detail'];
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

		$category = new category(&$db);
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
		
		$dataParent = $category->getByKey($this->parentcateKey);
		if(empty($dataParent)){
			$pNode = $this->TagTree->getNode($this->parentcateKey);
			$data = array();
			$data['c_parent_id'] = 0;
			$data['c_name'] = $pNode->data['val'];
			$data['bs_id'] = $this->bsid;
			$data['c_key'] = $pNode->data['key'];
			$data['c_parent_key'] = '';
			$pid = $category->insert($data);
		}else{
			$pid = $dataParent['c_id'];
		}
		$dataChild = $category->getByKey($this->childcateKey);
		if(empty($dataChild) || $dataChild['c_parent_id']!=$pid){
			//$k = $this->_makeTagKey($this->parentcateKey,$this->childcateKey);
			$cNode = $this->TagTree->getNode($this->childcateKey);
			$data = array();
			$data['c_parent_id'] = $pid;
			$data['c_name'] = $cNode->data['val'];
			$data['bs_id'] = $this->bsid;
			$data['c_key'] = $cNode->data['key'];
			$data['c_parent_key'] = $pNode->data['key'];
			$cate2 = $category->insert($data);
		}else{
			$cate2 = $dataChild['c_id'];
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
	public function addToBookshelf($process_id,$timestamp,$filename){
		global $db;
		$this->_setTagRoot($this->tagroot);
		
		if(empty($this->parentcateKey) || empty($this->childcateKey)){
			$this->_setDefaultTag();
		}else{
			$this->_setTag();
		}
		$cate2 = $this->_doCate();

		$ConvertManager = new ConvertManager($site,$this->bsid);
		$result = $ConvertManager->ConvertProgress($cate2,$process_id,$timestamp,$filename);
		$bid = intval($result['detail']['bid']);

		//$this->TagTree->loadString($this->tagstr);
		$this->TagTree->saveDB();
		$this->TagTree->bindOnBook($bid,$this->treeIndex);
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
						$queue = new queue(&$db);
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
	private function log($msg,$timestamp){
		error_log(sprintf('%s %s',date('Y-m-d h:i:s'),$msg),3,LOG_DIR.$timestamp);
	}
}
?>
