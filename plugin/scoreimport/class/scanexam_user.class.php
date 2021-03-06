<?PHP
/*									exam											quiz
bs_key,ex_key		??????scanexam				?D??scanscore_question
buid						???Zscanscore_user		????scanscore_exercise

scanscore=array(....,quiz=>array(,,,,,));
answer=array(....,exercise=>array(,,,,,));

scanexam_user=>
	bs_key
	se_key
	set_date
	bu_id
	seu_correct
	seu_points
	seu_percent
	exercise=>
		bs_key
		se_key
		set_date
		bu_id
		seq
		see_result
		see_answers
*/
class scanexam_user extends db_process{
	var $scanexam_exercise;
	var $sekey;
	var $bskey;
	var $date;
	var $buid;
	var $seq;

  function scanexam_user($db) {
  	parent::db_process($db,'scanexam_user','seu_');
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
	public function setBUID($_buid){
		$this->buid = $_buid;
	}

	public function reset(){
		$this->sekey='';
		$this->bskey='';
		$this->date='';
		$this->buid=0;
	}

	public function getByKey($bs_key,$se_key,$set_date,$bu_id){
		$sql=<<<SQL
select * from bookshelf2_scanexam_user
where bs_key='%s' and se_key='%s' and set_date='%s' and bu_id=%u
SQL;
		$sql = sprintf($sql,$bs_key,$se_key,$set_date,$bu_id);
		$rs = $this->db->query_first($sql);
		return $rs;
	}

	public function getList(){
		$order_str = empty($orderby)?'se_key desc, bs_key ASC, set_date desc, su.bu_id asc':$orderby;
		$where_str = empty($where)?'':' and '.$where;

		if(!empty($this->sekey)){
			$where_str .= sprintf(" and se_key='%s'",$this->sekey);
		}
		if(!empty($this->bskey)){
			$where_str .= sprintf(" and bs_key='%s'",$this->bskey);
		}
		if(!empty($this->date)){
			$where_str .= sprintf(" and set_date='%s'",$this->date);
		}
		if(!empty($this->buid)){
			$where_str .= sprintf(" and su.bu_id=%u",$this->buid);
		}

		$sql=<<<SQL
select su.*, bu.bu_name, bu.bu_cname, bu.bu_status
from bookshelf2_scanexam_user su
left join bookshelf2_bookshelf_users bu on(su.bu_id=bu.bu_id)
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

	public function del($bs_key,$se_key,$set_date,$bu_id=0){
		$where_str = sprintf("se_key='%s' and set_date='%s'",$se_key,$set_date);
		if(!empty($bs_key)){
			$where_str .= sprintf(" and bs_key='%s'",$bs_key);
		}
		if(!empty($buid)){
			$where_str .= sprintf(" and bu_id=%u",$buid);
		}
		$sql=<<<SQL
delete from bookshelf2_scanexam_user
where %s
SQL;
		$sql = sprintf($sql,$where_str);
		$this->db->query($sql);
	}
}