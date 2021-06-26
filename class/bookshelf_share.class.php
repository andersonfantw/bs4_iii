<?php
class bookshelf_share extends db_process{

  function bookshelf_share($db) {
  	parent::db_process($db,'bookshelf_share','bss_');
  }

  function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
    $order_str = empty($orderby)?'`'.$this->prefix.'id` ASC':$orderby;
    $where_str = empty($where)?'':'where '.$where;
    if(!empty($limit_from) || !empty($offset)){
      if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }
    $sql=<<<SQL
select bss.*,bs.bs_name 
from bookshelf2_bookshelf_share bss 
join bookshelf2_bookshelfs bs on (bs.bs_id=bss.bs_id)
%s
SQL;
    $sql = sprintf($sql, $where_str);
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
    $rs = $this->db->get_results($sql);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];

    if($rs){
    	$sql=<<<SQL
select count(*) as books_count, bs_id
from  bookshelf2_books
where share_bs_id =''
group by bs_id
SQL;
      $rst = $this->db->get_results($sql);
      foreach ($rs as $key => $val) {
        foreach ($rst as $key2 => $val2) {
          if($val['bs_id']==$val2['bs_id']){
            $rs[$key]['books_count'] = $val2['books_count'];
          }
        }        
      }
    }

		$data['result']=$rs;

    return $data;
  }

  function AuthCheck($bs_id,$bss_ip,$bss_account,$bss_password){
  	$sql=<<<SQL
select bss.*
from bookshelf2_bookshelf_share bss
where bs_id=%u
	and bss_ip='%s'
	and bss_account='%s'
SQL;
    $sql = sprintf($sql, $bs_id,$bss_ip,$bss_account);
    $rs = $this->db->query_first($sql);
    if( md5($rs['bss_password']) != $bss_password ){
      return false;
    }
    return $rs;
  }

  function getShareBookshelfInfo($bs_id){
  	$sql=<<<SQL
select count(*) as books_count , b.bs_id 
from bookshelf2_books b
where share_bs_id =''
	and b.bs_id=%u
group by bs_id
SQL;
    $sql = sprintf($sql,$bs_id);
    return $this->db->query_first($sql);
  }

  function getShareBooksByBSID($bs_id){
  	$sql=<<<SQL
select *,concat('%s/%s',f.filename) as f_path
from bookshelf2_view_bookdetail v
where share_bs_id ='' 
	and b.bs_id=%u
SQL;
    $sql = sprintf($sql,$bs_id);
    return $this->db->get_results($sql);
  }
}
?>
