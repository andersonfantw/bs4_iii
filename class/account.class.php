<?php
class account extends db_process{

  function account($db) {
  	parent::db_process($db,'account','u_');
  }

  function getBookshelfByAccountName($u_name){
  	$sql=<<<SQL
select b.* 
from %s a 
join bookshelf2_account_bookshelf ab on(a.u_id=ab.u_id) 
join bookshelf2_bookshelfs b on(ab.bs_id=b.bs_id)
where u_name = '%s'
order by ab.bs_id desc
SQL;
    $sql = sprintf($sql,$this->table, $u_name);
    $data = $this->db->get_results($sql);

    return $data;
  }

  function getBookshelfByUID($u_id){
  	$sql=<<<SQL
select b.* 
from %s a 
join bookshelf2_account_bookshelf ab on(a.u_id=ab.u_id) 
join bookshelf2_bookshelfs b on(ab.bs_id=b.bs_id)
where a.u_id = %u
order by ab.bs_id desc
SQL;
    $sql = sprintf($sql,$this->table, $u_id);
    $data = $this->db->get_results($sql);

    return $data;
  }

  function getAccountByBUID($buid){
  	$sql=<<<SQL
select a.*
from bookshelf2_account a
join bookshelf2_account_bookshelf ab on (a.u_id=ab.u_id)
join bookshelf2_bookshelf_groups bg on(bg.bs_id=ab.bs_id)
join bookshelf2_group_users gu on(gu.g_id=bg.g_id and gu.bu_id=%u)
SQL;
  	$sql = sprintf($sql, $buid);
    $data = $this->db->get_results($sql);

    return $data;
  }

  function getAccountByBSID($bsid){
  	$sql=<<<SQL
select a.*
from bookshelf2_account a
join bookshelf2_account_bookshelf ab on (a.u_id=ab.u_id and ab.bs_id=%u)
SQL;
  	$sql = sprintf($sql, $bsid);
    $data = $this->db->query_first($sql);

    return $data;
  }

	function getUIDByBSID($bsid){
		$sql=<<<SQL
select u_id
from bookshelf2_account_bookshelf ab
where bs_id=%s;
SQL;
  	$sql = sprintf($sql, $bsid);
    $data = $this->db->query_first($sql);

    return $data['u_id'];
	}

	function updatePassword($account_name,$oldpassword,$newpassword){
		$sql=<<<SQL
update bookshelf2_account
set u_password='%s'
where u_name='%s' and u_password='%s'
SQL;
  	$sql = sprintf($sql,$newpassword,$account_name,$oldpassword);
    return $this->db->query($sql);
	}

  function del($id){
  	if(parent::del($id))
  	{
  		$sql=<<<SQL
delete from bookshelf2_account_bookshelf
where %sid=%u
SQL;
      $sql = sprintf($sql,$this->prefix,$id);      
      return $this->db->query($sql);
  	}
  	return false;
  }
}
?>
