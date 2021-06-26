<?php
class exercise extends db_process{
	
  function exercise($db) {
  	parent::db_process($db,'itutor_exercise','e_');
  }

  function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
    $order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
    $where_str = empty($where)?'':'where '.$where;
    if(!empty($limit_from) || !empty($offset)){
      if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }
    $sql=<<<SQL
select e.*, i.id
from bookshelf2_itutor_exercise e
left join bookshelf2_itutor i on (e.i_id=i.i_id)
%s
SQL;

    $sql = sprintf($sql, $where_str);
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);

    $data['result'] = $this->db->get_results($sqlwithorder);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;
  }
}
