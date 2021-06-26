<?php
class bookshelf_user extends db_process{

  function bookshelf_user($db) {
  	parent::db_process($db,'bookshelf_users','bu_');
  }

	public function getListByGroupOfUID($uid){
   	$sql =<<<SQL
select gu.g_id,bu.*
from bookshelf2_bookshelf_users bu
left join bookshelf2_group_users gu on(gu.bu_id = bu.bu_id)
join bookshelf2_bookshelf_groups bg on(bg.g_id=gu.g_id)
join bookshelf2_account_bookshelf ab on(ab.bs_id=bg.bs_id)
where ab.u_id=%u
SQL;
		$sql = sprintf($sql, $uid);
		return $this->db->get_results($sql);
	}

	public function getCountByGID($gid){
  	$sql=<<<SQL
select count(*)
from bookshelf2_group_users
where g_id=%u
SQL;
		$sql = sprintf($sql, $gid);
		return $this->db->query_first($sql);
	}

	public function getByEmail($email){
		$sql=<<<SQL
select *
from bookshelf2_bookshelf_users
where bu_email='%s'
SQL;
		$sql = sprintf($sql,$email);
		return $this->db->query_first($sql);
	}

  public function getList($orderby='',$limit_from=0 ,$offset=0,$where='',$all_list=false){
    $order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
    $where_str = empty($where)?'':'and '.$where;
    if(!empty($limit_from) || !empty($offset)){
    	if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }
    if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::CENTRALIZE) || $all_list){
    	$sql =<<<SQL
select gu.g_id,bu.*
from bookshelf2_bookshelf_users bu
left join bookshelf2_group_users gu on(gu.bu_id = bu.bu_id)
where 1=1 %s
SQL;
			$sql = sprintf($sql,$where_str);
    }else{
    	$sql =<<<SQL
select *
from bookshelf2_view_group_users
where bs_id=%u %s
SQL;
			$sql = sprintf($sql,$this->bs_code,$where_str);
		}
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
    
    $data['result'] = $this->db->get_results($sqlwithorder);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;
	}

  function getBookshelfUserStructure($checked_arr=''){
  	$sql=<<<SQL
select bu_id,bu_cname,g_id,g_name
from bookshelf2_view_group_users v
where bs_id = %u
order by g_name,bu_cname
SQL;
    $sql = sprintf($sql,$this->bs_code);
    $data['result'] = $this->db->get_results($sql);

    //finding out group
    $g_id = '';
    $g_name = '';
    $i = 0;
    foreach($data['result'] as $key=>$val)
    {
      //new group
      if($g_id != $val['g_id']){
        $g_id = $val['g_id'];
        $g_name = $val['g_name'];
        $group_arr[$i]['g_id'] = $val['g_id'];
        $group_arr[$i]['g_name'] = $val['g_name'];
        $i++;
      }
    }

    //check checked array
    if(!empty($checked_arr)){
      foreach($data['result'] as $key=>$val){
        foreach($checked_arr as $key2=>$val2){
          if($val['bu_id']==$val2['bu_id']){
            $data['result'][$key]['checked'] = true;
            array_shift($checked_arr[$key2]);
            break;
          }
        }
      }
    }

    //set user to it's group
    if($group_arr){
      foreach($group_arr as $key=>$val)
      {
        $g_id = $val['g_id'];
        foreach($data['result'] as $key2=>$val2)
        {
          if($val2['g_id']==$g_id){
            $group_arr[$key]['users'][]=$val2;
          }
        }
      }
    }
    return $group_arr;
  }

	function setLastLogin($buid){
		//update last login times
		$date = $_SESSION['singlelogin'];
		$sql =<<<SQL
update bookshelf2_bookshelf_users
set last_login='%s'
where bu_id=%s;
SQL;
		$sql = sprintf($sql, $date, $buid);
		$this->db->query($sql);
	}

  function insert($data,$bsid=0){
  	unset($data['bu_id']);
  	$arr_gid = array();
  	if(!empty($data['g_id'])){
  		$arr_gid = explode(',',$data['g_id']);
  	}

  	unset($data['g_id']);
  	$data['last_login'] = date("Y-m-d H:i:s",mktime(0, 0, 0, 1, 1, 1900));
  	$bu_id = parent::insert($data,true);
    if($bu_id){
    	foreach($arr_gid as $gid){
    		$sql=<<<SQL
update bookshelf2_groups set bu_total=bu_total+1 where g_id=%u
SQL;
	      $sql = sprintf($sql,$gid);
  	    //$rs = $this->db->query($sql);
  	    $sql=<<<SQL
insert into bookshelf2_group_users (g_id,bu_id) values (%u,%u);
SQL;
	      $sql = sprintf($sql,$gid,$bu_id);
  	    $rs = $this->db->query($sql);
    	}
    }
    return $bu_id;
  }

  function del($bu_id,$gid){
  	if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::INDIVIDUAL)){
  		parent::del($id);
  	}
   	$sql=<<<SQL
update bookshelf2_groups set bu_total=bu_total-1 where g_id=%u
SQL;
    $sql = sprintf($sql,$gid);
    $rs = $this->db->query($sql);

		$sql=<<<SQL
delete from bookshelf2_group_users where g_id=%u and bu_id=%u;
SQL;
    $sql = sprintf($sql,$gid,$bu_id);
    $rs = $this->db->query($sql);
    return true;
  }
}
?>
