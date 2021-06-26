<?PHP
class itutor_exercise_tag extends db_process{
	function itutor_exercise_tag($db) {
		parent::db_process($db,'itutor_exercise_tag','st_');
	}

	function insert($dockey,$reportid,$ptid,$tid){
		$data=array();
		$data['dockey'] = $dockey;
		$data['e_reportid'] = $reportid;
		$data['t_parent_id'] = (int)$ptid;
		$data['t_id'] = (int)$tid;
		$data['createuser']=bssystem::getUID(1);
		$data['createdate']=date('Y-m-d H:i:s');
		parent::insert($data);
	}

	function del($dockey,$reportid,$tpid,$tid){
		$sql=<<<SQL
delete from bookshelf2_itutor_exercise_tag
where dockey='%s' and e_reportid='%s' and t_parent_id=%u and t_id=%u
SQL;
		$sql = sprintf($sql,$dockey,$reportid,$tpid,$tid);
		$this->db->query($sql);
	}
}