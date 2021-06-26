<?PHP
require_once 'BaseTree.class.php';
/********************************************************************************************
//key1:val1/key2:val2/key3:val3/key4:val4
=key5:val5,key6:val6,key7:val7,key8:val8
********************************************************************************************/
class TagTree{
	private $_tempstr;
	private $dispute_list=array();	//check input has no disputed
	private $tree_index=0;
	private $load_db = false;
	private $tree = array();
	private $keyid = array();	//look up db keyID
	private $valid = array();	//look up db valID
	private $_log = array();
	private $_saveseq = array();

	function __construct(){
		$this->dispute_list[] = array();
		$this->tree[] = new BaseTree();
		$this->_loadTagKey();
		$this->_loadTagVal();
	}

	private function _loadTagKey(){
		global $db;
		$tagkey = new db_process($db,'tagkey','tk_');
		$data = $tagkey->getList();
		foreach($data as $tk){
			$this->keyid[$tk['tk_name']] = $tk['tk_id'];
		}
				
	}
	private function _loadTagVal(){
		global $db;
		$tagval = new db_process($db,'tagval','tv_');
		$data = $tagval->getList();
		foreach($data as $tv){
			$this->valid[$tv['tv_name']] = $tv['tv_id'];
		}
	}

	public function newTree(){
		$this->tree[++$this->tree_index] = new BaseTree();
		return $this->tree_index;
	}

	public function add($parentkey,$Tag,$TagKey,$treeindex=-1){
		$this->_saveseq[] = $TagKey;
		if($treeindex>=0){
			$node0 = new Node($Tag,$TagKey,$db);
			$nodei = new Node($Tag,$TagKey,$db);
			$this->tree[0]->add($parentkey,$node0);
			$this->tree[$this->tree_index]->add($parentkey,$nodei);
		}else{
			$this->_add($parentkey,$Tag,$TagKey);
		}
	}

	public function _add($parentkey,$Tag,$TagKey,$db=false){
		$node0 = new Node($Tag,$TagKey,$db);	//0: combine all source healthy tree.
		$nodei = new Node($Tag,$TagKey,$db);	//>0: tree for each source.
		if($this->tree[0]->hasTag($node0)){
		}else{
			$this->tree[0]->add($parentkey,$node0);
			$this->tree[$this->tree_index]->add($parentkey,$nodei);
		}
	}

	public function getNode($TagKey){
		return $this->tree[0]->getNode($TagKey);
	}
	public function getNodeByValue($TagVal){
		return $this->tree[0]->getNodeByValue($TagVal);
	}
/*
	public function makeTagKey($pkey,$pval,$key,$val){
		return sprintf('%s:%s@%s:%s',$pkey,$pval,$key,$val);
	}
*/
	public function makeTagKey($pkey,$key,$val){
		return sprintf('%s@%s:%s',$pkey,$key,$val);
	}

	public function loadString($input){
		$input = $this->formatString($input);
		if(!TagTree::chkFormat($input)){
			return false;
		}
		if(!TagTree::validCheckCode($input)){
			return false;
		}
		$this->newTree();
		$input = trim($input);
		$input = str_replace("\r\n","",$input);

		$arr1 = explode('//',$input);
		for($i=1;$i<count($arr1);$i++){
			list($path,$nodes) = explode('=',$arr1[$i]);
			$arr_path = explode('/',$path);
			$arr_node = explode(',',$nodes);
			$parentkey = 'root';
			$pkey = ''; $pval = '';
			//path
			foreach($arr_path as $p){
				list($k,$v) = explode(':',$p);
				$Tag = array('key'=>$k,'val'=>$v);
				//$TagKey = $this->makeTagKey($pkey,$pval,$k,$v);
				$TagKey = $k;
				if(!in_array($TagKey,array_values($this->_saveseq))){
					$this->_saveseq[] = $TagKey;
				}
				//$this->tree[$this->tree_index]->add($parentkey,$node);
				$this->_add($parentkey,$Tag,$TagKey,false);
				$pkey = $k; $pval = $v;
				$parentkey = $TagKey;
			}
			//node
			foreach($arr_node as $n){
				list($k,$v) = explode(':',$n);
				//$TagKey = $this->makeTagKey($pkey,$pval,$k,$v);
				$TagKey =$k;
				if(!in_array($TagKey,array_values($this->_saveseq))){
					$this->_saveseq[] = $TagKey;
				}
				if(!array_key_exists($TagKey,$this->tree[0]->hash)){
					$Tag = array('key'=>$k,'val'=>$v);
					//$this->tree[$this->tree_index]->add($parentkey,$node);
					$this->_add($parentkey,$Tag,$TagKey,false);
				}
			}
		}
	}

	public function toString($source_index=0,$debug=false){
		foreach($this->tree[$source_index]->root->children as $n){
			$this->_toString($n,$source_index,$debug);
		}
		if(!$debug){
			$str = str_replace("\r\n","",$this->_tempstr);
			$chk = $this->_encodeCheckCode($str);
		}
		return $chk . $this->_tempstr;
	}
	private function _toString(Node $node,$source_index,$debug=false){
		if(!empty($node->children)){
			//path
			$path = $node->path();
      if($debug){
      	$this->_tempstr.="<br />\r\n/";
      }else{
      	$this->_tempstr.="\r\n/";
      }
			$pkey = 'root';
			$pval = 'root';
			for($i=0;$i<count($path);$i++){
				//$TagKey = $this->makeTagKey($pkey,$pval,$path[$i]['key'],$path[$i]['val']);
				$TagKey = $path[$i]['key'];
				$str = sprintf('%s:%s',$path[$i]['key'],$path[$i]['val']);
				if($debug){
					if(array_key_exists($TagKey,$this->dispute_list[$source_index])){
						$this->_tempstr.=sprintf('<b>%s</b>/',$str);
					//}elseif(array_key_exists($TagKey,$this->tree[$source_index]->hash)){
					//	$this->_tempstr.=sprintf('<u>%s</u>/',$str);
					}else{
						$this->_tempstr.='/'.$str;
					}
				}else{
					$this->_tempstr.='/'.$str;
				}
				$pkey = $path[$i]['key'];
				$pval = $path[$i]['val'];
			}
			if($debug){
				$this->_tempstr.="=<br />\r\n";
			}else{
				$this->_tempstr.="=\r\n";
			}
			//children
			$_str = '';
			foreach($node->children as $n){
				//$TagKey = $this->makeTagKey($n->parent->data['key'],$n->parent->data['val'],$n->data['key'],$n->data['val']);
				$TagKey = $n->data['key'];
				$str = sprintf('%s:%s',$n->data['key'],$n->data['val']);
				if($debug){
					if(array_key_exists($TagKey,$this->dispute_list[$source_index])){
						$_str.=sprintf(',<b>%s</b>',$str);
					//}elseif(array_key_exists($TagKey,$this->tree[$source_index]->hash)){
					//	$_str.=sprintf(',<u>%s</u>',$str);
					}else{
						$_str.=','.$str;
					}
				}else{
					$_str.=','.$str;
				}
			}
			$this->_tempstr.=substr ($_str,1);

			foreach($node->children as $n){
				$this->_toString(&$n,$source_index,$debug);
			}
		}
	}

	public function isLoadDB(){
		return $this->load_db;
	}

	//only load once
	function loadDB(){
		if(!$this->load_db){
			global $db;
			$this->load_db = true;
			$tag = new tag(&$db);
			$tags = $tag->getList();
			$this->newTree();
			foreach($tags['result'] as $t){
				if(empty($t['pkey']) && empty($t['pval'])){
					$parentkey = 'root';
				}else{
					//$parentkey = sprintf('%s:%s',$t['pkey'],$t['pval']);
					//$parentkey = $this->makeTagKey($t['ppkey'],$t['ppval'],$t['pkey'],$t['pval']);
					$parentkey = $t['pkey'];
				}
				//$TagKey = $this->makeTagKey($t['pkey'],$t['pval'],$t['key'],$t['val']);
				$TagKey = $t['key'];
				//db first, replace node
	/*
				$node = $this->tree[0]->getNode($TagKey);
				if($node){
					$this->tree[0]->remove($TagKey);
				}
	*/
				$Tag = array('id'=>$t['t_id'],'key'=>$t['key'],'val'=>$t['val']);
				$this->keyid[$t['key']] = $t['tk_id'];
				$this->valid[$t['val']] = $t['tv_id'];
				$this->_add($parentkey,$Tag,$TagKey,true);
			}
		}
	}

	public function bindOnBook($bid,$treeindex=0){
		global $db;
		$tag = new tag(&$db);
		foreach($this->_saveseq as $nodekey){
			if(empty($this->tree[$treeindex]->hash[$nodekey]->children)){
				$tid = $this->tree[0]->hash[$nodekey]->data['id'];
				$tag->addBookTag($bid,$tid);
			}
		}
	}

	function saveDB(){
		global $db;
		$tag = new tag(&$db);
		foreach($this->_saveseq as $nodekey){
			$node = $this->tree[0]->hash[$nodekey];
			if(!isset($node->data['id']) && $node->key!='root'){
				$path='';
				foreach($node->path() as $p){
					if(!empty($p['id'])){
						$_tid = $p['id'];
						$path .= ','.$_tid;
					}
				}
				if(!empty($path)){
					$path = substr($path,1);
				}
				/*
				$data = array();
				$data['tk_id'] = $this->keyid[$node->data['key']];
				$data['tv_id'] = $this->valid[$node->data['val']];
				$data['t_parent_id'] = $node->parent->data['id'];
				$data['path'] = $path;
				$tid = $tag->insert($data);
				*/
				$uid=0;
				$key = $node->data['key'];
				$val = $node->data['val'];
				$type=1;
				$arr = $tag->addTag($uid,$path,$key,$val,$type);
				$tid = $arr['tid'];
				if(empty($this->keyid[$node->data['key']])){
					$this->keyid[$node->data['key']]=$arr['kid'];
				}
				if(empty($this->valid[$node->data['val']])){
					$this->valid[$node->data['val']]=$arr['vid'];
				}
				if($tid){
					$this->tree[0]->hash[$nodekey]->data['id'] = $tid;
					$this->log($node,'insert db success');
				}else{
					$this->log($node,'insert db fail!');
				}
			}
		}
	}

	public function formatString($filecontent){
		$input = trim($filecontent);
		$input = str_replace("\r\n","",$input);
		if(!mb_check_encoding($input,'UTF-8')){
			$input = iconv("big5","UTF-8",$input);
		}else{
			$input = common::remove_utf8_bom($input);
		}
		return $input;
	}
	public function chkFormat($filecontent){
		$pattern = '/^(\w{32}){0,1}((\/(\/[^\/]{1,255}\:[^\/]{1,255})+\=([^\/]{1,255}\:[^\/]{1,255})(\,[^\/]{1,255}\:[^\/]{1,255})*)+)+$/';
		return preg_match($pattern,$filecontent);
	}
	public function hasCheckCode($filecontent){
		$input = str_replace("\r\n","",$filecontent);
		$arr1 = explode('//',$input);
		return ($arr1[0]!='');
	}
	protected function _encodeCheckCode($content){
		return md5('ttii'.base64_encode($content));
	}
	public function validCheckCode($filecontent){
		if(TagTree::hasCheckCode($filecontent)){
			$code = substr($filecontent,0,32);
			$content = substr($filecontent,32);
			return ($code==$this->_encodeCheckCode($content));
		}
		return true;
	}

	public function hasDispute(){
		return !empty($this->dispute_list[0]);
	}
	public function DumpDispute($source_index=0){
		echo "\r\n [DumpDispute]:\r\n";
		if(array_key_exists($source_index,$this->dispute_list)){
			foreach($this->dispute_list[$source_index] as $node){
				$node->toString();
			}
		}
	}

	public function log(Node $node,$action){
		$this->_log[] .= sprintf("%s:%s %s <br />\r\n",$node->data['key'],$node->data['val'],$action);
	}
	
	public function DumpLog(){
		echo "\r\n[DumpLog]:\r\n";
		var_dump($this->_log);
	}

	public function DebugInfo($treeindex=-1){
		if($treeindex>=0){
			echo "\r\n[DebugInfo $tree_index]:\r\n";
			$this->tree[$treeindex]->hashLog();
			$this->tree[$treeindex]->treeLog();
			$this->DumpDispute($treeindex);
		}else{
			for($i=0;$i<=$this->tree_index;$i++){
				echo "\r\n[DebugInfo $i]:\r\n";
				$this->tree[$i]->hashLog();
				$this->tree[$i]->treeLog();
				$this->DumpDispute($i);
			}
		}
		$this->DumpLog();
	}
}
?>
