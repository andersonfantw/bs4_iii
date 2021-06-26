<?PHP
require_once dirname(__FILE__).'/../libs/TagTree.class.php';
class TagevolveManager extends TagTree{
	private $tree = array();
	function __construct(){
		parent::__construct();
		$this->loadToHash();
	}
	function loadToHash(){
		global $db;
		$sql=<<<SQL
select te.*,
	vt1.key as okey, vt1.val as oval,
	vt2.key as nkey, vt2.val as nval
from bookshelf2_tag_evolve te
left join BOOKSHELF2_VIEW_TAGS_MINALL vt1 on(te.te_otid=vt1.t_id)
left join BOOKSHELF2_VIEW_TAGS_MINALL vt2 on(te.te_ntid=vt2.t_id)
where te_type>0
union
select te.*,
	concat(concat(vt1.key,'_'),cast(te_otid as nvarchar(10))) as okey, vt1.val as oval,
	concat(concat(vt2.key,'_'),cast(te_ntid as nvarchar(10))) as nkey, vt2.val as nval
from bookshelf2_tag_evolve te
left join BOOKSHELF2_VIEW_TAGS_MINALL vt1 on(te.te_otid=vt1.t_id)
left join BOOKSHELF2_VIEW_TAGS_MINALL vt2 on(te.te_ntid=vt2.t_id)
where te_type=0
order by createdate
SQL;
		$data = $db->get_results($sql);
		foreach($data as $t){
			$this->idhash[$t['te_ntid']]=$t;
			$this->keyhash[$t['nkey']]=$t;
		}
	}
	function loadToTree(){
		global $db;
		$this->newTree();
		//$tagevolve = new tagevolve(&$db);
		//$data = $tagevolve->getList('',0,0,'');
		$sql=<<<SQL
select te.*,
	vt1.key as okey, vt1.val as oval,
	vt2.key as nkey, vt2.val as nval
from bookshelf2_tag_evolve te
left join BOOKSHELF2_VIEW_TAGS_MINALL vt1 on(te.te_otid=vt1.t_id)
left join BOOKSHELF2_VIEW_TAGS_MINALL vt2 on(te.te_ntid=vt2.t_id)
where te_type>0
union
select te.*,
	concat(concat(vt1.key,'_'),cast(te_otid as nvarchar(10))) as okey, vt1.val as oval,
	concat(concat(vt2.key,'_'),cast(te_ntid as nvarchar(10))) as nkey, vt2.val as nval
from bookshelf2_tag_evolve te
left join BOOKSHELF2_VIEW_TAGS_MINALL vt1 on(te.te_otid=vt1.t_id)
left join BOOKSHELF2_VIEW_TAGS_MINALL vt2 on(te.te_ntid=vt2.t_id)
where te_type=0
order by createdate
SQL;
		$data = $db->get_results($sql);
		foreach($data as $t){
			$onode = $this->getNode($t['te_otid']);
			if(empty($onode)){
				$oparentkey = 'root';
			}else{
				$oparentkey = $onode->parent->data['key'];
			}
			$oTagKey = $t['okey'];
			$oTag = array('id'=>$t['te_otid'],'key'=>$t['okey'],'val'=>$t['te_otname'],'type'=>$t['te_type']);
			$this->_add($oparentkey,$oTag,$oTagKey);
			$nnode = $this->getNode($t['te_ntid']);
			if(empty($nnode)){
				$nparentkey = $t['okey'];
				$nTagKey = $t['nkey'];
				$nTag = array('id'=>$t['te_ntid'],'key'=>$t['nkey'],'val'=>$t['te_ntname'],'type'=>$t['te_type']);
				$this->_add($nparentkey,$nTag,$nTagKey);
			}
		}
		//$this->DebugInfo(0);
	}
	function getPathByID($_id){
		$path = array();
		$t = $this->idhash[$_id];
		while(!empty($t)){
			$path[] = array('id'=>$t['te_otid'],'key'=>$t['okey'],'year'=>$t['te_year']);
			$_id=$t['te_otid'];
			if(!empty($t['te_year'])) break;
			$t = $this->idhash[$_id];
		}
		return $path;
	}
	function getPathByKey($_key){
		$path = array();
		$t = $this->keyhash[$_key];
		while(!empty($t)){
			$path[] = array('id'=>$t['te_otid'],'key'=>$t['okey'],'year'=>$t['te_year']);
			$_key=$t['okey'];
			if(!empty($t['te_year'])) break;
			$t = $this->keyhash[$_key];
		}
		return $path;
	}
	function _loadToList(){
		global $db;
		$sql=<<<SQL
select te.*,
	vt1.key as okey, vt1.val as oval,
	vt2.key as nkey, vt2.val as nval
from bookshelf2_tag_evolve te
left join BOOKSHELF2_VIEW_TAGS_MINALL vt1 on(te.te_otid=vt1.t_id)
left join BOOKSHELF2_VIEW_TAGS_MINALL vt2 on(te.te_ntid=vt2.t_id)
order by createdate
SQL;
		$data = $db->get_results($sql);
		$list = array();
		$hash = array();
		$key='';
		for($i=0;$i<count($data);$i++){
			$t=$data[$i];
			if($i==0 || $key!=$t['createdate']){
				if(!empty($evolve)) $list[] = $evolve;
				$key = $t['createdate'];
				if(array_key_exists($t['te_otid'],$hash)){
					$j = $hash[$t['te_otid']];
					$list[$j]['edit']=false;
				}
				$hash[$t['te_ntid']]=count($list);
				$evolve = array('key'=>strtotime($key),
											'type'=>$t['te_type'],
											'edit'=>true,
											'year'=>$t['te_year'],
											'old'=>array($t['te_otid']=>$t['te_otname']),
											'new'=>array($t['te_ntid']=>$t['te_ntname']));
			}else{
				if(array_key_exists($t['te_otid'],$hash)){
					$j = $hash[$t['te_otid']];
					$list[$j]['edit']=false;
				}
				$hash[$t['te_ntid']]=count($list);
				switch($t['te_type']){
					case '0':
						break;
					case '1':
						$evolve['new'][$t['te_ntid']]=$t['te_ntname'];
						break;
					case '2':
						$evolve['old'][$t['te_otid']]=$t['te_otname'];
						break;
				}
			}
		}
		if(count($data)>0) $list[] = $evolve;
		return $list;
	}
	function getList(){
		$list = $this->_loadToList();
		for($i=0;$i<count($list);$i++){
			$list[$i]['old'] = implode(',',array_values($list[$i]['old']));
			$list[$i]['new'] = implode(',',array_values($list[$i]['new']));
		}
		return $list;
	}
	function merge($arr_tagkeys,$tagname){
		return $tid;
	}
	//$arr_obj format:
	// [{key:'',val:''},{key:'',val:''}...]
	function separate($tagkey,$arr_obj){
		return $tids; //[1,2,3,4..]
	}
	function rename($tagkey,$newname){
	}
	
}
?>
