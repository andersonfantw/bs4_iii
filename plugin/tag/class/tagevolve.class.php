<?PHP
class tagevolve extends db_process{
	var $from = array();
	var $to = array();
	var $_year;
	function tagevolve($db) {
		parent::db_process($db,'tag_evolve','te_');
	}
	function reset(){
		$this->from = array();
		$this->to = array();
	}
	function addFrom($tid){
		array_push($this->from,$tid);
	}
	function addTo($tid){
		array_push($this->to,$tid);
	}
	function addYear($year){
		$this->_year=$year;
	}
  public function getListByOTID($id){
    if(!is_array($id)){
    	$id=array($id);
    }
		$ids = implode(',',$id);
  	$sql = sprintf("select * from BOOKSHELF2_VIEW_TAG_EVOLVE where te_otid in (%s)", $this->prefix, $ids);
  	$rows = $this->db->get_results($sql);
		return $rows;
  }
  public function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
    $order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
    $where_str = empty($where)?'':'where '.$where;
    if(!empty($limit_from) || !empty($offset)){
      if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }
    $sql =<<<SQL
select * from BOOKSHELF2_VIEW_TAG_EVOLVE %s
SQL;
    $sql = sprintf($sql,$where_str);
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
    
    $data['result'] = $this->db->get_results($sqlwithorder);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;
  }
  public function del($key){  
    if(intval($key)>0)
    {
    	$createdate = date('Y-m-d H:i:s',$key);
    	//set tag active
			$sql=<<<SQL
update bookshelf2_tag set isActive=1
where t_id in (select te_otid from bookshelf2_tag_evolve where createdate='%s')
SQL;
			$sql = sprintf($sql,$createdate);
			$this->db->query($sql);

			//del evolve
      $sql = sprintf("delete from bookshelf2_tag_evolve where createdate='%s'",$createdate);
      return $this->db->query($sql);
    }else{
      return false;
    }
  }
	function rename($name){
		global $db;
		$tag = new tag(&$db);
		if(count($this->from)>0){
			$tid = $this->from[0];
			$result = $tag->getByID($tid);
			if($result){
				//record evolve
				$data = array();
				$data['te_otid'] = intval($tid);
				$data['te_otname'] = $result['val'];
				$data['te_ntid'] = intval($tid);
				$data['te_ntname'] = $name;
				$data['te_type'] = 0;
				$data['createdate'] = date('Y-m-d H:i:s');
				$this->insert($data);

				//rename tag
				$tag->rename($tid,$name);
			}
		}
	}
	function combine(){
		$tag = new tag(&$this->db);
		if(count($this->from)>1 && count($this->to)==1){
			if(in_array($this->to[0],$this->from)){
				//error
			}
			$createdate = date('Y-m-d H:i:s');
			$to_tid = $this->to[0];
			$to_row = $tag->getByID($to_tid);
			for($i=0;$i<count($this->from);$i++){
				$row = $tag->getByID($this->from[$i]);
				if($row){
					$data = array();
					$data['te_otid'] = intval($row['t_id']);
					$data['te_otname'] = $row['val'];
					$data['te_ntid'] = intval($to_tid);
					$data['te_ntname'] = $to_row['val'];
					$data['te_type'] = 2;
					$data['createdate'] = $createdate;
					$this->insert($data);
				}
				$this->_setActive(intval($row['t_id']),0);
			}
		}else{
			//error, wrong param
			$ee->Error('406.67');
		}
	}
	function separate(){
		$tag = new tag(&$this->db);
		if(count($this->from)==1 && count($this->to)>1){
			//make sure from_tid not in to_tid
			if(in_array($this->from[0],$this->to)){
				//error
			}
			if(empty($this->_year)){
				$this->_year = (date('Y')-1911);
			}
			$createdate = date('Y-m-d H:i:s');
			$from_tid = $this->from[0];
			$from_row = $tag->getByID($from_tid);
			for($i=0;$i<count($this->to);$i++){
				$row = $tag->getByID($this->to[$i]);
				if($row){
					$data = array();
					$data['te_otid'] = intval($from_tid);
					$data['te_otname'] = $from_row['val'];
					$data['te_ntid'] = intval($row['t_id']);
					$data['te_ntname'] = $row['val'];
					$data['te_type'] = 1;
					$data['te_year'] = (int)$this->_year;
					$data['createdate'] = $createdate;
					$this->insert($data);
				}
			}
			$this->_setActive(intval($from_tid),0);
		}else{
			//error, wrong param
			$ee->Error('406.67');
		}
	}
	function getRenameList(){
		$sql=<<<SQL
select *
from BOOKSHELF2_VIEW_TAG_EVOLVE
where te_type=0
SQL;
		$result = $this->db->get_results($sql);
		return $result;
	}
	function getCombineList(){
		$sql=<<<SQL
select *
from BOOKSHELF2_VIEW_TAG_EVOLVE
where te_type=1
SQL;
		$result = $this->db->get_results($sql);
		return $result;		
	}
	function getSeparateList(){
		$sql=<<<SQL
select *
from BOOKSHELF2_VIEW_TAG_EVOLVE
where te_type=2
SQL;
		$result = $this->db->get_results($sql);
		return $result;
	}
	private function _setActive($tid,$isActive=0){
		$sql=<<<SQL
update bookshelf2_tag set isActive=%u where t_id in (%s)
SQL;
		$sql=sprintf($sql,$isActive,$tid);
		$this->db->query($sql);
	}
	function rollback($tid){
		//check see if this tag can be rollback
		$row = $this->getListByOTID($tid);
		if($row){
			if($row['n']==0){
				return $this->del($tid);
			}
		}
		return false;
	}
}
?>
