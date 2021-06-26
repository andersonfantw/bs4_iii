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
class scanexam_tag extends db_process{
	var $scanexam_quiz;

	var $sekey;
	var $bskey;
	var $date;

  function scanexam_tag($db) {
  	parent::db_process($db,'scanexam_tag','se_');
  }

	public function getByKey($se_key){
		$sql=<<<SQL
select vt.* 
from bookshelf2_scanexam_tag st
left join bookshelf2_view_tags vt on(st.t_id=vt.t_id)
where st.se_key='%s'
SQL;
		$sql = sprintf($sql,$se_key,$set_date);
		$rs = $this->db->query_first($sql);
		return $rs;
	}

	//$data['se_key'], $data['t_id']
	public function insert($data){
		$data['Createuser'] = bssystem::getUID();
		$data['CreateDate'] = time();
		parent::insert($data);
	}

	public function del($se_key,$t_id=0){
		if(!empty($t_id)){
			$where_str = sprintf('and t_id=%u', $t_id);
		}
		$sql=<<<SQL
delete from bookshelf2_scanexam_tag
where se_key='%s' %s
SQL;
		$sql = sprintf($sql,$se_key,$where_str);
		$this->db->query($sql);
	}
}