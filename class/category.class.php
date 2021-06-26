<?php
class category extends db_process{

	var $cols;
  function category($db) {
    parent::db_process($db,'category','c_');
  }

  function getCategoryStructure($checked_arr='',$all=false){
  	if(!$all){
  		$where_str = 'and bs_id='.$this->bs_code;
  	}
  	$sql=<<<SQL
select c_id,c_parent_id,c_name,c_description,c_order
from bookshelf2_category
where 1=1 %s
order by bs_id, c_parent_id,c_order desc,c_id
SQL;
    $sql = sprintf($sql,$where_str);
    $data['result'] = $this->db->get_results($sql);

    //finding out parent category
    foreach($data['result'] as $key=>$val)
    {
      if($val['c_parent_id']==0){
        $category_arr[]=$val;
        array_shift($data['result']);
      }else
        break;
    }

    //check checked array
    if(!empty($checked_arr)){
      foreach($data['result'] as $key=>$val){
        foreach($checked_arr as $key2=>$val2){
          if($val['c_id']==$val2['c_id']){
            $data['result'][$key]['checked'] = true;
            array_shift($checked_arr[$key2]);
            break;
          }
        }
      }
    }

    //set sub category to it's parent category
    if($category_arr){
      foreach($category_arr as $key=>$val)
      {
        $p_id = $val['c_id'];
        foreach($data['result'] as $key2=>$val2)
        {
          if($val2['c_parent_id']==$p_id){
            $category_arr[$key]['sub_category'][]=$val2;
          }
        }
      }
    }
    return $category_arr;
  }

  function getByKey($Key,$bsid=0){
  	$condition = '';
  	if(!empty($bsid)) $condition='and bs_id='.$bsid;
    $sql = sprintf("select * from %s where %skey='%s' %s order by %sid",$this->table, $this->prefix, $Key, $condition, $this->prefix);
    return $this->db->query_first($sql);
  }

	function insert($data,$bsid=0){
		if(!empty($bsid)){
			$data['bs_id'] = $bsid;
		}
		return parent::insert($data,true);
	}

	function del($id){
		//delete children
		$sql=<<<SQL
delete from bookshelf2_category
where c_parent_id=%u
SQL;
    $sql = sprintf($sql,$id);
    $this->db->query($sql);

		//delete group ref
		$this->delete_group_category_by_category($id);

		//delete item
		return parent::del($id);
	}

	function delete_group_category_by_category($id){
		$sql=<<<SQL
delete from bookshelf2_groups_category
where c_id=%u
SQL;
    $sql = sprintf($sql,$id);
    $this->db->query($sql);
	}

  function sync_getByKey($Key){
    $sql = sprintf("select * from `%s` where `%skey`='%s' order by `%sid`",$this->table, $this->prefix, $Key, $this->prefix);
    return $this->db->query_first($sql);
  }

  function sync_hasChild($key, $isMainMenu){
    if($isMainMenu){
      $sql = sprintf("select count(*) as 'n' from `%s` where `%sparent_key`='%s' and c_status=1",$this->table, $this->prefix,$key);
    }else{
      $sql = sprintf("select count(*) as 'n' from `%sbooks` where `c_key`='%s' and b_status=1",DB_PREFIX,$key);
    }
    $rs = $this->db->query_first($sql);
    return $rs['n'];
  }

  function sync_insert($data){
    $bs_key = $data['bs_key'];
    $c_key = $data['c_key'];
    $c_parent_key = $data['c_parent_key'];
    if(isset($data['bs_key'])){
      $sql = sprintf("select `bs_id` from %sbookshelfs where bs_key='%s' order by `bs_id`",DB_PREFIX,$bs_key);
      $rs = $this->db->query_first($sql);
      if($rs){
        $data['bs_id'] = $rs['bs_id'];
      }
    }
    if(isset($data['c_parent_key']) && $data['c_parent_key']!=''){
      $sql = sprintf("select `%sid` from %s where %skey='%s' order by `%sid`",$this->prefix,$this->table,$this->prefix,$c_parent_key, $this->prefix);
      $rs = $this->db->query_first($sql);
      if($rs){
        $data['c_parent_id'] = $rs[$this->prefix.'id'];
      }
    }
    $sql = sprintf("insert into %s set %s",$this->table ,$this->process_data($data) );
    $rs = $this->db->query($sql);
    return $this->db->insert_id();
  }

  function sync_update($key,$data){
    $category_arr = $data['c_id'];
    unset($data['c_id']);
    $c_parent_key = $data['c_parent_key'];
    if(isset($data['c_parent_key']) && $data['c_parent_key']!=''){
      $sql = sprintf("select `%sid` from %s where %skey='%s' order by `%sid` limit 1",$this->prefix,$this->table,$this->prefix,$c_parent_key, $this->prefix);
      $rs = $this->db->query_first($sql);
      if($rs){
        $data['c_parent_id'] = $rs[$this->prefix.'id'];
      }
    }
    $sql = sprintf("update `%s` set %s where `%skey`='%s'",$this->table ,$this->process_data($data), $this->prefix, $key );
    $rs = $this->db->query($sql);
    if($rs && $category_arr){
    	$cate_arr = preg_split('/,/',$category_arr);
      $rs = $this->update_book_category($cate_arr,$id);
    }
    return $rs;
  }

  function sync_clean(){
    //刪除無效的關聯
    $sql = "delete from bookshelf2_books_category where b_id not in (select b_id from bookshelf2_books where b_status='on');";
    $this->db->query($sql);
    //隱藏沒有書籍的次分類
    $sql = "update bookshelf2_category set c_status=0 where c_parent_id>0 and c_id not in (select c_id from bookshelf2_books_category);";
    $this->db->query($sql);
    //隱藏沒有書籍的主分類
    //隱藏沒有書籍的書櫃
  }
}
?>
