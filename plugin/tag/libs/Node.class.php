<?PHP
class Node{
	public $depth=0;
	public $parent=null;
	public $key=null;
	public $data=null;
	public $children=array();
	public $db=false;
	public $delete=false;

	function __construct($_data,$_key='',$_db=false){
		if($_key!=''){
			$this->key = $_key;
		}else{
			$json = new Services_JSON();
			$this->key = $json->encode($_data);
		}
		$this->data = $_data;
		$this->db=$_db;
	}
	public function addChild(Node $node){
		$node->parent = &$this;
		$node->depth = $this->depth+1;
		//$k = $json->encode($node->data);
		if(!array_key_exists($node->key,$this->children)){
			$this->children[$node->key] = &$node;
		}
	}
	public function remove(){
		unset($this->data);
		unset($this);
	}
	public function path(){
		$p = array();
		$obj = $this;
		while($obj!=null && $obj->data['val']!='root'){
			array_unshift($p,$obj->data);
			$obj = $obj->parent;
		}
		return $p;
	}
	public function toString(){
		echo ' |'.implode(',',$this->data);
	}
}
?>