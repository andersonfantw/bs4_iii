<?php
class reading_time extends db_process{
	
  function reading_time($db) {
  	parent::db_process($db,'reading_time','');
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

	function getByPKey($bid, $cid, $starttime){
		$sql =<<<SQL
select *
from %s
where b_id=%u
	and c_id=%u
	and start_time='%s'
SQL;
		$sql = sprintf($sql,$this->table,$bid,$cid,$starttime);
		return $this->db->query_first($sql);
	}

	function updateByPKey($bid, $cid, $starttime, $data){
		$sql =<<<SQL
update %s set %s 
where b_id=%u
	and c_id=%u
	and start_time='%s'
SQL;
		$sql = sprintf($sql,$this->table ,parent::process_data_update($data), $bid, $cid, $starttime);
		return $this->db->query($sql);
	}
}
