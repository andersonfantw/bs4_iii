<?PHP
/*									exam											quiz(seq)
bs_key,ex_key		�����scanexam				�D��scanscore_quiz
buid						���Zscanscore_user		����scanscore_exercise

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
class scanexam_test extends db_process{
	var $scanexam_quiz;

	var $sekey;
	var $bskey;
	var $date;

  function scanexam_test($db) {
  	parent::db_process($db,'scanexam_test','set_');
  }

	public function setSEKey($_sekey){
		$this->sekey = $_sekey;
	}
	public function setBSKey($_bskey){
		$this->bskey = $_bskey;
	}
	public function setDate($_date){
		$this->date = $_date;
	}

	public function getByKey($se_key,$set_date){
		$sql=<<<SQL
select * from bookshelf2_scanexam_test
where se_key='%s' and set_date='%s'
SQL;
		$sql = sprintf($sql,$se_key,$set_date);
		$rs = $this->db->query_first($sql);
		return $rs;
	}

	public function insert($data){
		$data['Createuser'] = bssystem::getUID();
		$data['CreateDate'] = date('Y-m-d H:i:s');
		parent::insert($data);
	}
	public function del($se_key,$set_date,$bs_key){
		global $db;

		$sql=<<<SQL
delete from bookshelf2_scanexam_test
where se_key='%s' and set_date='%s' %s
SQL;
		$sql = sprintf($sql,$se_key,$set_date,$where_str);
		$this->db->query($sql);
		
		//bookshelf2_scanexam_test_tag
		//bookshelf2_scanexam_user
		//bookshelf2_scanexam_exercise
		$scanexam_test_tag = new scanexam_test_tag($db);
		$scanexam_user = new scanexam_user($db);
		$scanexam_exercise = new scanexam_exercise($db);
		$scanexam_test_tag->del($se_key,'',$set_date);
		$scanexam_user->del($bs_key,$se_key,$set_date);
		$scanexam_exercise->del($bs_key,$se_key,$set_date);
		return true;
	}
}