<?php
class quicksearch extends db_process{
  function quicksearch($db) {
  	parent::db_process($db,'quicksearch','q_');
  }
  public function getByBUID($id){
    if(is_array($id)){
			$ids = implode(',',$id);
    	$sql = sprintf("select * from %s where bu_id in (%s) order by q_id asc",$this->table, $ids);
    	$rows = $this->db->get_results($sql);
    }else{
    	$sql = sprintf("select * from %s where bu_id = %u order by q_id asc",$this->table, $id);
    	$rows = $this->db->get_results($sql);
    }
    if(count($rows)){
			return $rows;
    }else return null;
  }
  function insert($data){
  	$data['bu_id'] = bssystem::getLoginBUID();
		$data['createdate'] = date("Y-m-d H:i:s");
		return parent::insert($data,true);		
  }
}
?>
