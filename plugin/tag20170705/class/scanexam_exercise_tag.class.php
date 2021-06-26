<?PHP
class scanexam_exercise_tag extends db_process{
	function scanexam_exercise_tag($db) {
		parent::db_process($db,'scanexam_exercise_tag','t_');
	}

	function insert($bskey,$sekey,$setdate,$seq,$ptid,$tid,$uid){
		$data=array();
		$data['bs_key'] = $bskey;
		$data['se_key'] = $sekey;
		$data['set_date'] = $setdate;
		$data['seq'] = (int)$seq;
		$data['t_parent_id'] = (int)$ptid;
		$data['t_id'] = (int)$tid;
		$data['createuser']=bssystem::getUID(1);
		$data['createdate']=date('Y-m-d H:i:s');
		parent::insert($data);
	}

	function del($bskey,$se_key,$set_date,$seq,$tpid,$tid){
		$sql=<<<SQL
delete from bookshelf2_scanexam_exercise_tag
where bs_key='%s' and se_key='%s' and set_date='%s' and seq=%u and t_parent_id=%u and t_id=%u
SQL;
		$sql = sprintf($sql,$bskey,$se_key,$set_date,$seq,$tpid,$tid);
		$this->db->query($sql);
	}
}