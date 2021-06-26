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
class scanexam_quiz extends db_process{
	var $scanexam_quiz;

	var $sekey;
	var $seq;

  function scanexam_quiz($db) {
  	parent::db_process($db,'scanexam_quiz','seq_');
  }

	public function setSEKey($_sekey){
		$this->sekey = $_sekey;
	}
	public function setSeq($_seq){
		$this->seq = $_seq;
	}

	public function reset(){
		$this->sekey='';
		$this->seq=0;
	}

	public function getByKey($se_key){
		$sql=<<<SQL
select * from bookshelf2_scanexam_quiz
where se_key='%s'
SQL;
		$sql = sprintf($sql,$se_key);
		$rs = $this->db->get_results($sql);
		return $rs;
	}

	public function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?'se_key desc, seq ASC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		if(!empty($this->sekey)){
			$where_str .= sprintf(" and se_key='%s'",$this->sekey);
		}
		if(!empty($this->seq)){
			$where_str .= sprintf(" and seq='%s'",$this->seq);
		}

		$sql=<<<SQL
select *
from bookshelf2_scanexam_quiz se
where 1=1 %s
SQL;
		$sql = sprintf($sql,$where_str);
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
  	$data['result'] = $this->db->get_results($sqlwithorder);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
  	return $data;
	}

	public function insert($data){
		parent::insert($data);
	}
	public function del($se_key){
		$sql=<<<SQL
delete from bookshelf2_scanexam_quiz
where se_key='%s'
SQL;
		$sql = sprintf($sql,$se_key);
		$this->db->query($sql);
	}
}
?>