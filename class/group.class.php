<?php
class group extends db_process{

  function group($db) {
  	parent::db_process($db,'groups','g_');
  }

  /*function checkGroupExist(){
	$sql = sprintf("select * from %s where bs_id = %u  limit 1",$this->table, $this->bs_code);
	return $this->db->get_results($sql);
  }*/

  function getByKey($Key, $bsid=0){
  	if(empty($bsid)){
  		$sql =<<<SQL
select g.g_id, g.g_name, g.g_key, (select count(*) from bookshelf2_group_users where g_id=g.g_id) as bu_total
from bookshelf2_groups g
where g_key='%s'
order by g_id
SQL;
  	}else{
    	$sql =<<<SQL
select g.g_id, g.g_name, g.g_key, (select count(*) from bookshelf2_group_users where g_id=g.g_id) as bu_total
from bookshelf2_groups g
left join bookshelf2_bookshelf_groups bg on (g.g_id=bs.g_id)
where g.g_key='%s'
	and bs.bs_id=%u
	order by `g_id`
SQL;
		}
    $sql = sprintf($sql, $Key, $bsid);
    return $this->db->query_first($sql);
  }

	function getListByBUID($buid){
    $sql =<<<SQL
select g.g_id, g.g_name, g.g_key, (select count(*) from bookshelf2_group_users where g_id=g.g_id) as bu_total
from bookshelf2_groups g
join bookshelf2_group_users gu on(gu.g_id=g.g_id and gu.bu_id=%u)
SQL;
		$sql = sprintf($sql, $buid);
		return $this->db->get_results($sql);
	}

	function getListByUID($uid){
    $sql =<<<SQL
select g.g_id, g.g_name, g.g_key, (select count(*) from bookshelf2_group_users where g_id=g.g_id) as bu_total
from bookshelf2_groups g
join bookshelf2_bookshelf_groups bg on(bg.g_id=g.g_id)
join bookshelf2_account_bookshelf ab on(ab.bs_id=bg.bs_id)
where ab.u_id=%u
SQL;
		$sql = sprintf($sql, $uid);
		return $this->db->get_results($sql);
	}

  function getCategoryByGID($id){
  	$sql=<<<SQL
select c.*,gc.g_id
from bookshelf2_category c
join bookshelf2_groups_category gc ON (c.c_id=gc.c_id)
where gc.g_id= %u
SQL;
    $sql = sprintf($sql,$id);
    return $this->db->get_results($sql);
  }

  function getCategoryByKey($key){
  	$sql=<<<SQL
select c.*,gc.g_id
from bookshelf2_category c
join bookshelf2_groups_category gc ON (c.c_id=gc.c_id)
left join bookshelf2_groups g on (g.g_id=gc.g_id)
where g.g_key= '%s'
SQL;
    $sql = sprintf($sql,$key);
    return $this->db->get_results($sql);
  }

  function getList($orderby='',$limit_from=0 ,$offset=0,$where='',$all_list=false){
    $order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
    $where_str = empty($where)?'':'and '.$where;
    if(!empty($limit_from) || !empty($offset)){
    	if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }

    if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::CENTRALIZE) || $all_list){
    	$sql =<<<SQL
select g.g_id, g.g_name, g.g_key, (select count(*) from bookshelf2_group_users where g_id=g.g_id) as bu_total
from %s g
where 1=1 %s
SQL;
			$sql = sprintf($sql,$this->table,$where_str);
    }else{
    	$sql =<<<SQL
select g.g_id, g.g_name, g.g_key, (select count(*) from bookshelf2_group_users where g_id=g.g_id) as bu_total
from %s g
inner join bookshelf2_bookshelf_groups bsg on(g.g_id = bsg.g_id)
where bs_id=%u %s
SQL;
			$sql = sprintf($sql,$this->table,$this->bs_code,$where_str);
		}
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);

    $data['result'] = $this->db->get_results($sqlwithorder);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;
  }
  
  function insert($data,$insert_id=false){
    $category_arr = explode(',',$data['c_id']);
    unset($data['c_id']);
    if(empty($data['bs_id'])){
	$data['bs_id'] = $this->bs_code;
    }
    $arr_bsid=explode(',',$data['bs_id']);
    unset($data['bs_id']);

		$g_id=parent::insert($data,true);
    if($g_id){
	foreach($arr_bsid as $bsid){
	    	$sql=<<<SQL
insert into bookshelf2_bookshelf_groups (bs_id,g_id) values (%u,%u)
SQL;
		$sql = sprintf($sql,$bsid,$g_id);
		$rs2 = $this->db->query($sql);
	}
    }
    if($rs2 && $category_arr){      
      $rs2 = $this->insert_group_category($category_arr,$g_id);
    }
    if($insert_id){
    	return $g_id;
    }else{
    	return $rs2;
    }
  }

  function update($id,$data){
    if(intval($id)>0)
    {
      $category_arr = explode(',',$data['c_id']);
      unset($data['c_id']);

      $rs=parent::update($id,$data);
      if($rs && $category_arr){
        $rs = $this->update_group_category($category_arr,$id);
      }
      return $rs;
    }else{
      return false;
    }
  }

  function updateByKey($key,$data){
    if(!empty($key))
    {
      $category_arr = explode(',',$data['c_id']);
      unset($data['c_id']);

      $rs=parent::updateByKey($key,$data);
      if($rs && $category_arr){
        $rs = $this->update_group_category($category_arr,$id);
      }
      return $rs;
    }else{
      return false;
    }
  }

  function del($id){
    if(intval($id)>0)
    {
      $rs=parent::del($id);
      if($rs){
      	$sql=<<<SQL
delete from bookshelf2_bookshelf_groups where g_id=%u
SQL;
        $sql = sprintf($sql,$id);
        $rs2 = $this->db->query($sql);
      }
      if($rs2){
        $rs3 = $this->delete_group_category_by_group($id);
      }
      return $rs3;
    }else{
      return false;
    }
  }

  function insert_group_category($cate_arr,$g_id){
    $rs = true;
    foreach($cate_arr as $val){
      if($rs){
      	$sql=<<<SQL
insert into bookshelf2_groups_category (g_id,c_id) values (%u,%u)
SQL;
        $sql = sprintf($sql,$g_id,$val );
        $rs = $this->db->query($sql);
      }
    }
    return $rs;
  }

  function delete_group_category_by_group($g_id){
  	$sql=<<<SQL
delete from bookshelf2_groups_category
where g_id=%u
	and c_id in (select c_id 
								from bookshelf2_category
								where bs_id=%u)
SQL;
    $sql = sprintf($sql,$g_id,$this->bs_code );
    return $this->db->query($sql);
  }

  function update_group_category($cate_arr,$g_id){
    $rs = $this->delete_group_category_by_group($g_id);
    if($rs){
      $rs = $this->insert_group_category($cate_arr,$g_id);
    }
    return $rs;
  }

	function insert_group_user($arr_gid,$buid){
    $rs = true;
    foreach($arr_gid as $val){
      if($rs){
				$sql=<<<SQL
insert into bookshelf2_group_users (g_id,bu_id) values (%u,%u)
SQL;
        $sql = sprintf($sql,$val,$buid);
        $rs = $this->db->query($sql);
			}
		}
	}
	function delete_group_user($buid){
		$sql=<<<SQL
delete from bookshelf2_group_users
where bu_id=%u
SQL;
    $sql = sprintf($sql,$buid);
    return $this->db->query($sql);
	}
	function update_group_user($arr_gid,$buid){
		$rs = $this->delete_group_user($buid);
		if($rs){
			$rs = $this->insert_group_user($arr_gid,$buid);
		}
		return $rs;
	}
}
?>
