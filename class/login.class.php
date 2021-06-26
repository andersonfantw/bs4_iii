<?php
class login extends db_process{

	var $period='';
	var $type='';
	var $query='';
	
  function login($db) {
  	parent::db_process($db,'login','');
  }

	function getBySESSIONID($sid){
		$sql=<<<SQL
select *
from bookshelf2_bookshelf_users
where last_login = (select max(start_time)
										from bookshelf2_login
										where uid!=0 and session_id='%s' and type='u'
)
SQL;
/* pass valid evertyime
		$sql=<<<SQL
select *
from bookshelf2_bookshelf_users bu
join bookshelf2_login l on(bu.bu_id=l.uid)
where uid!=0 and session_id='%s'
)
SQL;
*/
		$sql = sprintf($sql,$sid);
		return $this->db->query_first($sql);
	}

  function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
    $order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
    $where_str = empty($where)?'':'and '.$where;
    if(!empty($limit_from) || !empty($offset)){
    	if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }
		if(DB_TYPE=='dbmaker'){
        $sql=<<<SQL
select t1.b_id, t1.sec, bu.*
from (select rt.bu_id, b_id, sum(SECS_BETWEEN(end_time,start_time)) as sec
                        from bookshelf2_reading_time rt
                        group by rt.bu_id, b_id) as t1
left join bookshelf2_bookshelf_users bu on (t1.bu_id=bu.bu_id)
where b_id>0 %s
SQL;
		}else{
        $sql=<<<SQL
select t1.b_id, t1.sec, bu.* from (
        select bu_id,b_id, sum(IFNULL(EXTRACT(MINUTE_SECOND FROM timediff(end_time,start_time)),5)) as sec
        from bookshelf2_reading_time
        group by bu_id, b_id
        with rollup) as t1
left join bookshelf2_bookshelf_users bu on (t1.bu_id=bu.bu_id)
where b_id is not null %s
SQL;
		}

    $sql = sprintf($sql,$where_str);
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);

    $data['result'] = $this->db->get_results($sqlwithorder);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');

		if(DB_TYPE=='dbmaker'){
			$sql =<<<SQL
select max(sec) as sec from (
        select b_id, sum(SECS_BETWEEN(end_time,start_time)) as sec
        from %s rt
        group by b_id) as t
where b_id>0
SQL;
		}else{
			$sql =<<<SQL
select max(sec) as sec from (
        select rt.bu_id, b_id, sum(IFNULL(EXTRACT(MINUTE_SECOND FROM timediff(end_time,start_time)),5)) as sec
        from %s rt
        group by rt.bu_id, b_id with rollup) as t
where b_id is not null and sec>0
SQL;
		}
		$sql = sprintf($sql,$this->table);
    $data['max'] = $this->db->get_results($sql);
 
    $data['total'] = $record['record'];
    return $data;
  }

/*
everyday, everyweek, everymonth, everyyear
weekday, dayhour
*/
	function setPeriod($_period){
		$this->period=$_period;
	}

/*
a,u,-
*/
	function setType($_type){
		$this->type=$_type;
	}

/*
user,visit
amount_time,browser,os
*/
	function setQuery($_query){
		$this->query=$_query;
	}

	function getData(){
		$_select='';
		$_groupby='';
		$where='';
		$orderby='';
		$_groupbycol='';
		switch($this->period){
			case 'byday':
				$_select.='datepart(start_time) as d';
				$_groupby.='datepart(start_time)';
				$_groupbycol.='d';
				break;
			case 'byweek':
				$_select.='year(start_time) as y,week(start_time) as w';
				$_groupby.='year(start_time),week(start_time)';
				$_groupbycol.='y,w';
				break;
			case 'bymonth':
				$_select.='year(start_time) as y,month(start_time) as m';
				$_groupby.='year(start_time),month(start_time)';
				$_groupbycol.='y,m';
				break;
			case 'byyear':
				$_select.='year(start_time) as y';
				$_groupby.='year(start_time)';
				$_groupbycol.='y';
				break;
			case 'dayofweek':
				$_select='dayofweek(start_time) as d2';
				$_groupby='dayofweek(start_time)';
				$_groupbycol.='d2';
				$orderby='dayofweek(start_time)';
				break;
			case 'hourofday':
				$_select.='hour(start_time) as h';
				$_groupby.='hour(start_time)';
				$_groupbycol.='h';
				$orderby.='hour(start_time)';
				break;
		}
		switch($this->type){
			case 'a':
				$where = "where type='a'";
				break;
			case 'u':
				$where = "where type='u'";
				break;
			case '-':
				$where = "where type='-'";
				break;
			default:
				$_select .= ",type";
				$_groupby .= ",type";
				break;
		}
		$sql=<<<SQL
select %s
from bookshelf2_login
%s
group by %s
SQL;
		$data = array();
		foreach($this->query as $val){
			switch($val){
				case 'user':
					$select=$_select.',count(*) as user';
					switch($this->period){
						case 'byday':
						case 'byweek':
						case 'bymonth':
						case 'byyear':
							$groupby=$_groupby.',uid';
							break;
						default:
							$groupby=$_groupby;
							break;
					}
					break;
				case 'visit':
					$select=$_select.',count(*) as visit';
					switch($this->period){
						case 'byday':
						case 'byweek':
						case 'bymonth':
						case 'byyear':
							$groupby=$_groupby.',session_id';
							break;
						default:
							$groupby=$_groupby;
							break;
					}
					break;
				case 'amount_time':
					$select=$_select.",sum(timestampdiff('m',start_time,end_time)) as amounttime";
					$groupby=$_groupby;
					break;
				case 'browser':
					$select=$_select.',browser,count(*) as num';
					$groupby=$_groupby.',browser';
					break;
				case 'os':
					$select=$_select.',os,count(*) as num';
					$groupby=$_groupby.',os';
					break;
			}
			$_sql = sprintf($sql,$select,$where,$groupby);
			switch($val){
				case 'user':
					$sql1=<<<SQL
select %s,count(*) as user
from (%s) as t
group by %s
SQL;
					$_sql=sprintf($sql1,$_groupbycol,$_sql,$_groupbycol);
					break;
				case 'visit':
					$sql1=<<<SQL
select %s,count(*) as visit
from (%s) as t
group by %s
SQL;
					$_sql=sprintf($sql1,$_groupbycol,$_sql,$_groupbycol);
					break;
			}
			if(!empty($orderby)) $_sql.=' order by '.$orderby;
			$data[$val] = $this->db->get_results($_sql);
		}
		return $data;
	}

	function hasLogin($uid, $type, $sessionid, $starttime=''){
		$where = (empty($starttime))?'':" and start_time='".$starttime."'";
		//if login over half hour, deemed to different login
		$sql =<<<SQL
select *
from %s
where uid=%u
	and type='%s'
	and session_id='%s'
	%s
order by start_time desc
SQL;
		$sql = sprintf($sql,$this->table, $uid, $type, $sessionid, $where);
		$data = $this->db->query_first($sql);
		if(time() - strtotime($data['end_time']) > 1800){
			//mean has login, but over 30mins.
			return false;
		}
		//etheir found(row) or not found(empty array)
		return $data;
	}

	function getByPKey($uid, $type, $sessionid, $starttime=''){
		$where = (empty($starttime))?'':" and start_time='".$starttime."'";
		$sql =<<<SQL
select *
from %s
where uid=%u
	and type='%s'
	and session_id='%s'
	%s
SQL;
		$sql = sprintf($sql,$this->table, $uid, $type, $sessionid, $where);
		return $this->db->get_results($sql);
	}

	function insert($uid,$type,$sessionid,$starttime){
		global $USER_IP;
		global $fs;
		if(empty($starttime)) $starttime=date("Y-m-d H:i:s");
		$data=array();
		$data['uid'] = $uid;
		$data['type'] = $type;
		$data['bs_id'] = bssystem::getBSID();
		$data['session_id'] = $sessionid;
		$data['browser']=htmlspecialchars(browser::detect('browser_name'), ENT_QUOTES);
		$data['device']=htmlspecialchars(browser::detect('mobile_test'), ENT_QUOTES);
		$data['os']=htmlspecialchars(browser::detect('os'), ENT_QUOTES);
		$data['remote_ip']=$USER_IP;
		$data['start_time']=$starttime;
		$data['end_time']=$starttime;
		parent::insert($data);
	}

	function updateByPKey($uid, $type, $sessionid, $starttime){
		if(!empty($starttime)){
			$sql =<<<SQL
update %s set %s 
where uid=%u
	and type='%s'
	and session_id='%s'
	and start_time='%s'
SQL;
			$data = array();
			$data['end_time']=date("Y-m-d H:i:s");
			$sql = sprintf($sql,$this->table ,parent::process_data_update($data), $uid, $type, $sessionid, $starttime);
			return $this->db->query($sql);
		}
	}
}
?>
