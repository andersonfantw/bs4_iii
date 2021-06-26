<?PHP
/*									exam											quiz(seq)
bs_key,ex_key		測驗卷scanexam				題目scanscore_quiz
buid						成績scanscore_user		答案scanscore_exercise

scanexam=array(....,quiz=>array(,,,,,));
answer=array(....,exercise=>array(,,,,,));

scanexam_test=>
	se_key
	set_date
	set_name
	quiz=>
		se_key
		seq
		seq_reportid
		seq_correct
*/
class scanexam_test_tag extends db_process{
	var $scanexam_quiz;

	var $sekey;
	var $bskey;
	var $date;

  function scanexam_test_tag($db) {
  	parent::db_process($db,'scanexam_test_tag','se_');
  }

	public function getByKey($se_key,$method,$set_date,$t_parent_id=0){
		$where_str = sprintf("stt.se_key='%s'",$se_key);
		if(!empty($method)){
			$where_str .= sprintf(" and stt.method='%s'",$method);
		}
		if(!empty($set_date)){
			$where_str .= sprintf(" and stt.set_date='%s'",$set_date);
		}
		if(!empty($t_parent_id)){
			$where_str .= sprintf(' and stt.t_parent_id=%u',$t_parent_id);
		}
		$sql=<<<SQL
select vt.* 
from bookshelf2_scanexam_test_tag stt
left join bookshelf2_view_tags vt on(stt.t_id=vt.t_id)
where %s
SQL;
		$sql = sprintf($sql,$where_str);
		$rs = $this->db->query_first($sql);
		return $rs;
	}

	public function getSelectedTagByKey($se_key,$method,$set_date,$t_parent_id){
		$where_str = sprintf("stt.se_key='%s'",$se_key);
		if(!empty($method)){
			$where_str .= sprintf(" and stt.method='%s'",$method);
		}
		if(!empty($set_date)){
			$where_str .= sprintf(" and stt.set_date='%s'",$set_date);
		}
		if(!empty($t_parent_id)){
			$where_str .= sprintf(' and (stt.t_parent_id in (select t_id from bookshelf2_tag where t_parent_id=%u) or stt.t_parent_id=%u)',$t_parent_id,$t_parent_id);
		}
		$sql=<<<SQL
select vt.* 
from bookshelf2_scanexam_test_tag stt
left join bookshelf2_view_tags vt on(stt.t_id=vt.t_id)
where %s
SQL;
		$sql = sprintf($sql,$where_str);
		$rs = $this->db->query_first($sql);
		return $rs;
	}

	public function insertSystemTag($se_key,$set_date){
		$CreateUser = bssystem::getUID();
		$CreateDate = date('Y-m-d H:i:s');

		$this->del($se_key,'',$set_date);

		$sql=<<<SQL
insert into bookshelf2_scanexam_test_tag (se_key,set_date,method,t_parent_id,t_id,CreateUser,CreateDate)
select '%s','%s',st.method,ifnull(t.t_parent_id,-1),st.t_id,%u,'%s'
from bookshelf2_system_tag st
left join bookshelf2_tag t on(st.t_id=t.t_id)
where st.method='system'
SQL;
		$sql = sprintf($sql,$se_key,$set_date,$CreateUser,$CreateDate);
		$this->db->query($sql);
	}

	//$data['se_key'], $data['set_date'], $data['t_id']
	public function insert($data){
		$data['Createuser'] = bssystem::getUID();
		$data['CreateDate'] = date('Y-m-d H:i:s');
		parent::insert($data);
	}
	public function update($se_key,$method,$set_date,$t_parent_id,$data){
		$data['Createuser'] = bssystem::getUID();
		$data['CreateDate'] = date('Y-m-d H:i:s');
		$sql=<<<SQL
update bookshelf2_scanexam_test_tag
set %s where se_key='%s' and set_date='%s' and method='%s' and t_parent_id=%u
SQL;
		$sql = sprintf($sql,parent::process_data_update($data),$se_key,$set_date,$method,$t_parent_id);
		return $this->db->query($sql);
	}
	public function del($se_key,$method,$set_date,$t_parent_id){
		$where_str = sprintf("se_key='%s' and set_date='%s'",$se_key,$set_date);
		if(!empty($method)){
			$where_str .= sprintf(" and method='%s'",$method);
		}
		if(!empty($t_parent_id)){
			$where_str .= sprintf(' and t_parent_id=%u',$t_parent_id);
		}
		$sql=<<<SQL
delete from bookshelf2_scanexam_test_tag
where %s
SQL;
		$sql = sprintf($sql,$where_str);
		$this->db->query($sql);
	}
}
?>