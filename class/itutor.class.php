<?php
class itutor extends db_process{
	
  function itutor($db) {
  	parent::db_process($db,'itutor','i_');
  }

  function getGroupList($orderby='i_name',$limit_from=0 ,$offset=0,$where=''){
    $order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
    $where_str = empty($where)?'':'where '.$where;
    if(!empty($limit_from) || !empty($offset)){
    	if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }
    
    $sql=<<<SQL
select i_name, i_slidecount, i_totalinteraction, id, COUNT(*) as num
from bookshelf2_itutor i
join bookshelf2_books b on (b.b_id=i.b_id)
%s group by i_name, i_slidecount, i_totalinteraction, id
SQL;

    $sql = sprintf($sql,$where_str);
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);

    $data['result'] = $this->db->get_results($sql);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;
  }

  function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
    $order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
    $where_str = empty($where)?'':'where '.$where;
    if(!empty($limit_from) || !empty($offset)){
    	if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }
    $sql=<<<SQL
select i.*, bu.bu_name, bu.bu_cname
from bookshelf2_itutor i
left join bookshelf2_bookshelf_users bu on(bu.bu_id=i.bu_id)
%s
SQL;
    $sql = sprintf($sql,$where_str);
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);

    $data['result'] = $this->db->get_results($sqlwithorder);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;
  }

  function getUserList($orderby='',$limit_from=0,$offset=0,$where=''){
  	return parent::getList($orderby,$limit_from,$offset,$where);
  }
  
  /*
  get test sum data by cate
  */
  function analytic1($bsid, $buid=0){
  	if($buid>0){
  		$where_str = ' and i.bu_id='.$buid;
  	}else{
  		$where_str = ' and !isnull(i_points)';
  	}
		$sql=<<<SQL
select c1.c_id, c1.c_name, ifnull(count(*),0) as num, ifnull(sum(i.i_points),0) as points, ifnull(sum(i.i_maxpoints),0) as maxpoints, ifnull(sum(i.i_percent),0) as percent 
from bookshelf2_category as c1
left join bookshelf2_category as c2 on (c2.c_parent_id=c1.c_id and c2.c_status=1)
left join bookshelf2_books_category bc on (c2.c_id=bc.c_id)
left join bookshelf2_itutor i on (i.b_id=bc.b_id)
where c1.c_parent_id=0 and c1.c_status=1 and c1.bs_id=%u %s
group by c1.c_id, c1.c_name, i.i_points, i.i_maxpoints, i.i_percent
SQL;

		$sql = sprintf($sql, $bsid, $where_str);
		$data['result'] = $this->db->get_results($sql);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;		
  }
}
