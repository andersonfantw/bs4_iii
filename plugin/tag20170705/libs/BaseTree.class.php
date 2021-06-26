<?PHP
/********************************************************************************************
//key1:val1/key2:val2/key3:val3/key4:val4
=key5:val5,key6:val6,key7:val7,key8:val8
********************************************************************************************/
class BaseTree{
	public $root=array();	//health tree
	public $hash=array();	//pkey:pval@key:val
	public $hash1=array();	//key:val
	protected $_tempstr='';

	function __construct(){
		$n = new Node(array('key'=>'root','val'=>'root'),'root');
		$this->root = $n;
		$this->hash['root'] = &$n;
	}
	function __destruct(){
		$this->destroy();
	}
	public function destroy(){
			for($i=0;$i<count($this->hash);$i++){
				unset($this->hash[$i]);
			}
			unset($this->hash);
			unset($this->root);
	}
	public function add($parentKey,Node $node){
		//combine node
		if($this->hasTag($node)){
		}else{
			$this->hash[$node->key] = &$node;
			$arr = explode('@',$node->key);
			if(count($arr)==2){
				$_key = $arr[1];
			}else{
				$_key = $arr[0];
			}
			$this->hash1[$_key] = &$node;
		}
		if(!array_key_exists($parentKey,$this->hash)){
			//echo 'err';
		}else{
			$this->hash[$parentKey]->addChild(&$node);
		}
	}
	public function getNode($TagKey){
		if(array_key_exists($TagKey,$this->hash)){
			return $this->hash[$TagKey];
		}
		return null;
	}
	
	public function toString($_root_index=0){
		$this->_toString($this->root[$_root_index]);
		return $this->_tempstr;
	}
	private function _toString(Node $node){
		if(!empty($node->children)){
			//path
			$path = $node->path();
			$this->_tempstr.="\r\n//";
			foreach($path as $p){
				$this->_tempstr.=sprintf('%s:%s/',$p['key'],$p['val']);
			}
			$this->_tempstr.="=\r\n";
			//children
			$_str = '';
			foreach($node->children as $n){
				$_str.=sprintf(',%s:%s',$n->data['key'],$n->data['val']);
			}
			$this->_tempstr.=substr ($_str,1);

			foreach($node->children as $n){
				$this->_toString(&$n);
			}
		}
	}
	public function remove($TagKey){
		$node = $this->getNode($TagKey);
		$node->remove();
		unset($this->hash[$TagKey]);
	}
	//looking up in hash
	public function hasTag(Node $node){
		return $this->hasTagByKey($node->key);
	}
	public function hasTagByKey($key){
		return array_key_exists($key,$this->hash);
	}

	public function treeLog(){
		echo "=====tree log=====\r\n";
		$this->_treeLog($this->root);
	}
	private function _treeLog($node){
		if(!empty($node->children)){
			foreach($node->children as $n){
				echo str_repeat('_',$n->depth).implode(':',$n->data)."\r\n";
				$this->_treeLog(&$n);
			}
		}
	}
	public function hashLog(){
		echo "=====hash log=====\r\n";
		foreach($this->hash as $k=>$n){
			echo sprintf("%u) %s => %s\r\n",++$i,$k,$n->data['val']);
		}
	}
}
?>
