<?php
class tag_dictionary extends db_process{
	function tag_dictionary($db) {
		parent::db_process($db,'tag_dictionary','t_');
	}
	
	public function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?'vtd.'.$this->prefix.'id ASC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select vtd.dockey,vtd.quizid,vt.*
from bookshelf2_tag_dictionary vtd
left join bookshelf2_view_tags vt on(vtd.t_id=vt.t_id)
where 1=1 %s
SQL;
		$sql = sprintf($sql,$where_str);
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
  	$data['result'] = $this->db->get_results($sqlwithorder);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
		return $data;
	}
	
	public function insert($data,$insert_id=false){
		$data['createuser']=bssystem::getUID(1);
		$data['createdate']=date('Y-m-d H:i:s');
		return parent::insert($data,$insert_id);
	}
}
?>