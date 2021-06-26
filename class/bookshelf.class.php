<?php
class bookshelf extends db_process{

  function bookshelf($db) {
  	parent::db_process($db,'bookshelfs','bs_');
  }

  function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
    $order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
    $where_str = empty($where)?'':'where '.$where;
    if(!empty($limit_from) || !empty($offset)){
      if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }

    //$sql = sprintf("select SQL_CALC_FOUND_ROWS bs.*,a.u_id,a.u_cname from %s bs left join %saccount_bookshelf ab on (bs.bs_id = ab.bs_id) left join %saccount a on(ab.u_id=a.u_id) %s order by %s %s",$this->table,DB_PREFIX,DB_PREFIX,$where_str,$order_str,$limit_str);
    $sql =<<<SQL
select v.*, 
	(select count(*) 
			from bookshelf2_bookshelf_share share 
			where share.bs_id=v.bs_id) as share_num 
from bookshelf2_view_bookshelfdetail v
%s
SQL;

    $sql = sprintf($sql,$where_str);
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);

    $data['result'] = $this->db->get_results($sqlwithorder);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;
  }

  function getByID($id){
  	$sql =<<<SQL
select *,
	(select count(*) 
	from bookshelf2_bookshelf_share share 
	where share.bs_id=v.bs_id) as share_num 
from bookshelf2_view_bookshelfdetail v
where bs_id = %u  	
SQL;
    $sql = sprintf($sql,$id);
    return $this->db->query_first($sql);
  }

  function getByKey($key){
  	$sql =<<<SQL
select *,
	(select count(*) 
	from bookshelf2_bookshelf_share share 
	where share.bs_id=v.bs_id) as share_num 
from bookshelf2_view_bookshelfdetail v 
where bs_key = '%s'
SQL;
    $sql = sprintf($sql,$key);
    return $this->db->get_results($sql);
  }

  function insert($data,$u_id){
  	$bs_id = parent::insert($data,true);
    if($bs_id){
    	$sql=<<<SQL
insert into bookshelf2_account_bookshelf (bs_id,u_id) values (%u,%u)
SQL;
      $sql = sprintf($sql, $bs_id, $u_id);
      $rs = $this->db->query($sql);
      if($rs){
        //call ecocat api
        return $bs_id;
      }else{
        return false;
      }
    }
    
    return false;
  }

  function get_bookshelf_groups_structure($bs_id){
		if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::CENTRALIZE)){
  		$sql = <<<SQL
select g.*,
	case when bg.g_id IS NULL then 0 else 1 end as checked
from bookshelf2_groups g
left join bookshelf2_bookshelf_groups bg on(g.g_id=bg.g_id)
order by g_name
SQL;
		}else{
			$sql = <<<SQL
select g.*,
	case when bg.g_id IS NULL then 0 else 1 end as checked
from bookshelf2_groups g
left join bookshelf2_bookshelf_groups bg on(g.g_id=bg.g_id and bg.bs_id=%d)
order by g_name
SQL;
		}
		$sql = sprintf($sql,$bs_id);
    $groups = $this->db->get_results($sql);  
    $checked_arr = $this->get_bookshelf_groups($bs_id);
    return $groups;
  }

  function get_bookshelf_groups($bs_id){
  	$sql=<<<SQL
select * from bookshelf2_bookshelf_groups where bs_id=%u
SQL;
    $sql = sprintf($sql,$bs_id);
    return $this->db->get_results($sql);  
  }

  function getUserByBookshelf($bsid){
  	$sql=<<<SQL
select * from bookshelf2_view_users_by_account where bs_id=%u
SQL;
    $sql = sprintf($sql,$bs_id);
    return $this->db->get_results($sql); 
  }

  function getUserByAccount($uid){
  	$sql=<<<SQL
select * from bookshelf2_bookshelf_groups where u_id=%u
SQL;
    $sql = sprintf($sql,$uid);
    return $this->db->get_results($sql); 
  }

  function delete_bookshelf_groups($bs_id){
  	$sql=<<<SQL
delete from bookshelf2_bookshelf_groups where bs_id=%u
SQL;
    $sql = sprintf($sql,$bs_id );    
    return $this->db->query($sql);
  }

  function insert_bookshelf_groups($bs_id,$groups_arr){
    $rs = true;
    $sql=<<<SQL
insert into bookshelf2_bookshelf_groups (bs_id,g_id) values (%u,%u);
SQL;
    foreach($groups_arr as $val){
      if($rs){
        $_sql = sprintf($sql,$bs_id,$val );
        $rs = $this->db->query($_sql);
      }
    }
    return $rs;
  }

  function update_bookshelf_groups($bs_id,$groups_arr){
    if(empty($groups_arr)){
      return true;
      exit;
    }
    $rs = $this->delete_bookshelf_groups($bs_id);
    if($rs){
      $rs = $this->insert_bookshelf_groups($bs_id,$groups_arr);
    }
    return $rs;
  }

  function del($id){
  	if(parent::del($id)){
  		$sql=<<<SQL
delete from bookshelf2_account_bookshelf where bs_id=%u
SQL;
      $sql2 = sprintf($sql,$id);   
      return $this->db->query($sql2);
  	}
    return false;    
  }

  function sync_getByKey($Key){
  	$sql=<<<SQL
select *
from bookshelf2_view_bookshelfdetail v
where bs_key = '%s'
SQL;
    $sql = sprintf($sql,$Key);
    return $this->db->query_first($sql);
  }
/*
  function sync_hasChild($key, $nouse){
  	$sql=<<<SQL
select count(*) as n
from bookshelf2_category
where bs_key='%s'
  and c_status=1
SQL;
    $sql = sprintf($sql,$key);
    $rs = $this->db->query_first($sql);
    return $rs['n'];
  }
*/

  function sync_insert($data,$u_id){
  	$bs_id = parent::insert($data,true);
    if($bs_id){
			$sql=<<<SQL
insert into bookshelf2_account_bookshelf (bs_id,u_id) values (%u,%u)
SQL;
      $sql = sprintf($sql,$bs_id,$u_id);
      $rs = $this->db->query($sql);
      if($rs){
        //call ecocat api
        return $bs_id;
      }else{
        return false;
      }
    }
    
    return false;
  }
}
?>
