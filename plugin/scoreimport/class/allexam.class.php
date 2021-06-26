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
class allexam extends db_process{
	var $scanexam_quiz;

	var $sekey;
	var $bskey;
	var $date;
	var $bsid;
	var $tids;

  function allexam($db) {
  	parent::db_process($db,'scanexam_test','set_');
  }

	public function setBSID($_bsid){
		$this->bsid = $_bsid;
	}
	public function setTags($_tids){
		$this->tids = $_tids;
	}

	public function getByKey($key,$date=''){
		if(!empty($date)){
			$where_str = sprintf(" and createdate='%s'",$date);
		}
		$sql=<<<SQL
select ve.*
from bookshelf2_view_examlist ve
where key='%s' %s
SQL;
		$sql = sprintf($sql,$key,$where_str);
		$rs = $this->db->query_first($sql);
		return $rs;
	}

	public function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		if(!empty($this->bsid)){
		  $where_str .= sprintf(' and (bs_id=%u or bskey in (select bs_key from bookshelf2_bookshelfs where bs_id=%u))',$this->bsid,$this->bsid);
		}elseif(!empty($this->bskey)){
		  $where_str .= sprintf(" and bskey='%s'",$this->bskey);
		}
		if(!empty($limit_from) || !empty($offset)){
			if(DB_TYPE=='dbmaker') $offset+=$limit_from;
			$limit_str = 'limit '.$limit_from.','.$offset;
		}
		if(empty($this->tids)){
			//type, key, name, description, question_num
			$sql=<<<SQL
select ve.*
from bookshelf2_view_examlist ve
where 1=1 %s
SQL;
			$sql = sprintf($sql,$where_str);
		}else{
			$str_tid = implode(',',$this->tids);
			$sql=<<<SQL
select ve.*
from bookshelf2_view_examlist ve
left join bookshelf2_scanexam_tag st on(ve.key=st.se_key and ve.type='infoacer')
left join bookshelf2_book_tag bt on(ve.id=bt.b_id and ve.type='itutor');
where t_id in (%s) %s;
SQL;
			$sql = sprintf($sql,$str_tid,$where_str);
		}
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
  	$data['result'] = $this->db->get_results($sqlwithorder);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
  	return $data;
	}
}