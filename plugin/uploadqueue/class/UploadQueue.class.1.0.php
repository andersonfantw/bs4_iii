<?PHP
/*
status code progress
add	converting	converted	addBookshelf
0	=>	1~99		=>	100		=>		200	(done)
												=>		-1	(fail)
*/
//»Ý¨Ì·Ó­pµe¦WºÙ¡B¼f¬d¦~«×¡B´Á§O±N¤å¥ó¨Ì²Å¦X¼f¬d§@·~ªº¤è¦¡ÂkÃþ¦b¹q¤l®Ñ¨t²Î¤º¡C
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
		$this->uid = 1;
		$this->TagTree = new TagTree();
		$this->tags = array();
		$this->heartbeat = CACHE_PATH.'/heartbeat';
		$this->ecocat_allow_type = array('pdf','ppt','doc','xls','pptx','docx','xlsx');
		$this->file_allow_type = array('zip','itu');
	}
	public function setBSID($_bsid){
		$this->bsid = $_bsid;
	}
	public function setBSKey($_bskey){
		$this->bskey = $_bskey;
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
	}
	private function _setDataFromParams(){
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
 		move_uploaded_file($uploadfile["tmp_name"],$workpath);

		//$tagstr = $this->TagTree->toString($this->treeIndex);
		//$tagstr = urlencode($tagstr);
		$this->data = $this->_setDataFromParams();

		//insert file to queue
		$data = array();
		$data['q_name'] = $name;
		$data['q_tmpname'] = $workpath;
		$data['q_key'] = $key;
		$data['q_data'] = json_encode($this->data);
		$queue = new queue($db);
		return $queue->insert($data);
	}
	public function get($id){
		global $db;
		$queue = new queue($db);
		return $queue->getByID($id);
	}
	public function add($key,$uploadfile,$bookname=''){
		global $db;
		global $ee;

		$this->uploadfile = $uploadfile;

		$queue = new queue($db);
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
	 		move_uploaded_file($uploadfile["tmp_name"],$workpath);
			$data1 = array();
			$data1['q_name'] = $name;
			$data1['q_tmpname'] = $workpath;
			$data1['q_key'] = $key;
			$data1['q_data'] = json_encode($this->data);
			$data1['createdate'] = date("Y-m-d H:i:s");
			$queue = new queue($db);
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
			if(!empty($bookname)){
				$name = $bookname.'.'.$path_parts1['extension'];
			}
			return $this->_add($key,$uploadfile,$timestamp,$bookname);
		}
	}
	private function updateStatus($id,$status){
		global $db;
		$queue = new queue($db);
		$queue->updateStatus($id,$status);
	}
	private function retry($id){
		global $db;
		$queue = new queue($db);
		$queue->retry($id);
	}
	private function _del($row,$real=false){
		global $db;
		//if file is converting, then next
		if($row['status']>0 && $row['status']<100){
			$converting_qid = $row['q_id'];
			 return false;
		}
		$queue = new queue($db);
		$queue->del($row['q_id'],$real);
		//ÀË¬d¬O§_Âà®Ñ¦¨¥\
		if(!empty($row['b_id'])){
			//§R°£®Ñ
			$BookManager = new BookManager();
			$BookManager->del($row['b_id']);
		}
		//§R°£¤u§@¸ê®Æ§¨¤¤­nÂà´«ªºÀÉ®×
		if(substr($row['q_tmpname'],0,strlen(WORK_PATH)) == WORK_PATH){
			//delete file, if exists
			@unlink($row['q_tmpname']);
		}
		return true;
	}
	public function del($id,$real){
		global $db;
		$queue = new queue($db);
		$row = $queue->getByID($id);
		return $this->_del($row,$real);
	}
	public function delByKey($key){
		global $db;
		$queue = new queue($db);
		//¨ú±o¥¼³Q¼Ð°O¬°§R°£ªºkeyªº¶µ¥Ø
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
		$queue = new queue($db);
		$data = $queue->getNext();
		return $data;
	}
	public function doNext(){
		global $db;
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
				if($item['q_id']==$heartbeat['qid'] && $heartbeat['rate']=='100'){
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
					$this->_mail(QueueStatusEnum::Fail,$qid,$item['q_name'],$this->tagstr);
					$is_unlink = $this->removeHeartbeat();
					if($is_unlink) $this->doNext();
				}elseif($result['rate']==100){
					$this->updateHeartbeat($qid,$ecocatid,100);
					$bid = $this->addToBookshelf($ecocatid,$timestamp,$filename,$_key);
					$status = (ENABLE_DECENTRALIZED)?QueueStatusEnum::Success:QueueStatusEnum::ImportSuccess;
					$queue = new queue($db);
					$queue->update($qid,array('b_id'=>$bid,'status'=>$status));
					$is_unlink = $this->removeHeartbeat();
					if($is_unlink) $this->doNext();
				}else{
					if($rate != $result['rate']){
						$rate = $result['rate'];
						$this->updateHeartbeat($qid,$ecocatid,$rate);
						$queue = new queue($db);
						$queue->updateStatus($qid,intval($rate));
					}elseif((time()-$modifytimestamp>600) && ($result['rate']=='0') && $item['q_retry']==2){
						//coverting book with 10min no progress, delete record in ecocat
						$EcocatConnector = new EcocatConnector($this->bsid);
						$EcocatConnector->DeleteBook($ecocatid);
						$this->_mail(QueueStatusEnum::Fail,$row['q_id'],$row['q_name'],$this->tagstr);
					}
				}
			}else{
				$qid = $item['q_id'];
				$timestamp = time();				
				$filename = $item['q_name'];
				$path_parts = common::path_info($filename);
				$subname = strtolower($path_parts['extension']);
				$ecocatid = '';
				$rate = 0;

				if(!file_exists($tmpname)){
					$this->updateStatus($qid,QueueStatusEnum::MissingFile);
					$this->removeHeartbeat();
					//$this->_mail(QueueStatusEnum::MissingFile,$item['q_id'],$item['q_name'],$this->tagstr);
					exit;
				}
var_dump('doNext:',$filename);
				$this->createHeartbeat($qid,$timestamp,$ecocatid,$filename,$rate);

print_r("\ndoNext_convert=");
				if(in_array($subname,$this->ecocat_allow_type)){
print_r("\necocat=");
					$result = $this->convert(-1,$qid,$tmpname,$filename,$this->spell);
print_r($result);
					if(isset($result['process_id'])){
						$ecocatid = $result['process_id'];
					}elseif(isset($result['message'])){
						$ecocatid = 'error';
					}
					$this->updateHeartbeat($qid,$ecocatid,$rate);
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
						$queue = new queue($db);
						$queue->update($qid,array('b_id'=>$bid,'status'=>$status));
						$this->removeHeartbeat();
					}else{
						$this->retry($qid);
						$row = $this->get($qid);
						if($row['q_retry']>=3){
							$this->removeHeartbeat();
							$this->_mail(QueueStatusEnum::Fail,$row['q_id'],$row['q_name'],$this->tagstr);
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
		return @unlink($this->heartbeat);
	}
	public function convert($cate2,$qid,$tmpname,$filename,$spell,$skin='',$language_type=''){
		global $db;
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
		//param: bs, site, cate2
		$ConvertManager = new ConvertManager(true,$this->bsid);
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
		$tv='¨t²Î®É¶¡';
*/
		$ppk='#Year';
		$ppv='¦è¤¸¦~';

		$pk=sprintf('#DC%u',date('Y'));
		$pv=date('Y');

		$cpk='#Month';
		$cpv='¤ë¥÷';

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
	private function log($msg,$timestamp){
		error_log(sprintf('%s %s',date('Y-m-d h:i:s'),$msg),3,LOG_DIR.$timestamp);
	}
	function _mail($status,$qid,$name,$tagstr){
		print_r("\ncall send mail");
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

		$UPLOADQUEUE_ERROR_APPLYTO1 = UPLOADQUEUE_ERROR_APPLYTO1;
		$UPLOADQUEUE_ERROR_APPLYTO2 = UPLOADQUEUE_ERROR_APPLYTO2;
		if(empty($UPLOADQUEUE_ERROR_APPLYTO1) && empty($UPLOADQUEUE_ERROR_APPLYTO2)){
			return;
		}
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
		$mail->addCC('support@ttii.com.tw', 'SUPPORT');
		if(!empty($UPLOADQUEUE_ERROR_APPLYTO1)){
			$mail->addAddress($UPLOADQUEUE_ERROR_APPLYTO1, $UPLOADQUEUE_ERROR_APPLYTO1);
		}
		if(!empty($UPLOADQUEUE_ERROR_APPLYTO2)){
			$mail->addAddress($UPLOADQUEUE_ERROR_APPLYTO2, $UPLOADQUEUE_ERROR_APPLYTO2);
		}

		$arr_status = array(QueueStatusEnum::Fail=>'轉檔失敗',QueueStatusEnum::MissingFile=>'檔案遺失');		
		
		//Set the subject line
		//$mail->Subject = mb_convert_encoding(sprintf('[數位圖書館]檔案轉換失敗通知 - %s',$name),"utf-8","big5");
		$mail->Subject = sprintf('[數位圖書館]檔案轉換失敗通知 - %s',$pn);

		if(array_key_exists($status,$arr_status)){
			$errmsg = $arr_status[$status];
		}else{
			$errmsg = '錯誤';
		}
		$content = sprintf("%s 轉檔編號%u: 檔案 %s\n%s\n%s，請至數位圖書館管理後台重新上傳。",date('Y-m-d H:i:s'),$qid,$name,$pn,$errmsg);

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
			//echo 'success';
		}
	}
}
?>
