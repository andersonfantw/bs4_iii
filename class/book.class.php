<?php
class book extends db_process{
	//new,old,ecocat,share_bs,search
	var $type = '';
	var $bsid = 0;
	var $buid = 0;
	var $shareid = 0;
	var $tag = array();
	var $keyword='';
	var $book_status='public';
	var $category = array();
	var $ShortcutID = 0;
	var $arrCol = null;

	public function setBookStatus($_book_status){
		$this->book_status = $_book_status;
	}
	public function setType($_type){
		$this->type = $_type;
	}
	public function setBSID($_bsid){
		$this->bsid = $_bsid;
	}
	public function setBUID($_buid){
		$this->buid = $_buid;
	}
	public function setShareID($_shareid){
		$this->shareid = $_shareid;
	}
	public function setCategory($_category){
		$this->category = $_category;
	}
	public function setTag($_tag){
		$this->tag = $_tag;
	}
	public function setKeyword($_keyword){
		$this->keyword = $_keyword;
	}
	public function setShortcut($_ShortcutID){
		$this->ShortcutID=$_ShortcutID;
	}
	public function setCols($_arrCol){
		$this->arrCol = $_arrCol;
	}

  function book($db) {
  	parent::db_process($db,'books','b_');
  }


	function reset(){
		$this->type='';
		$this->bsid=0;
		$this->buid=0;
		$this->shareid=0;
		$this->category=array();
		$this->tag=array();
		$this->keyword='';
	}

  function getByID($id){
    $sql = <<<SQL
select b.*,f.* 
from %s b 
left join %sfile f 
	on(b.file_id=f.f_id) 
where %sid = %u
SQL;
		$sql = sprintf($sql ,$this->table,DB_PREFIX, $this->prefix, $id);
		$rs = $this->db->query_first($sql);
		if(!empty($rs)){
			$rs['webbook_link'] = htmlspecialchars_decode($rs['webbook_link']);
    	$rs['ibook_link'] = htmlspecialchars_decode($rs['ibook_link']);
    }
    return $rs;
  }

  function getByKey($Key, $bsid=0){
  	if(empty($bsid)){
    	$sql = <<<SQL
select b.*,f.* 
from %s b 
left join %sfile f 
	on(b.file_id=f.f_id) 
where %skey = '%s'
order by b_id desc
SQL;
		}else{
    	$sql = <<<SQL
select b.*,f.* 
from %s b 
left join %sfile f 
	on(b.file_id=f.f_id) 
where %skey = '%s'
	and b.bs_id = %u
order by b_id desc
SQL;
		}
		$sql = sprintf($sql ,$this->table,DB_PREFIX, $this->prefix, $Key, $bsid);
		$rs = $this->db->query_first($sql);
		if(!empty($rs)){
			$rs['webbook_link'] = htmlspecialchars_decode($rs['webbook_link']);
  	  $rs['ibook_link'] = htmlspecialchars_decode($rs['ibook_link']);
  	}
    return $rs;
  }

  function getPublicByID($id){
    $sql = <<<SQL
select b.*,f.* 
from %s b 
left join %sfile f 
	on(b.file_id=f.f_id) 
where b_status=1 and %sid = %u
SQL;
		$sql = sprintf($sql ,$this->table,DB_PREFIX, $this->prefix, $id);
		$rs = $this->db->query_first($sql);
		$rs['webbook_link'] = htmlspecialchars_decode($rs['webbook_link']);
    $rs['ibook_link'] = htmlspecialchars_decode($rs['ibook_link']);
    return $rs;
  }


	function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		global $ee;
		if(!empty($this->category)){
			$_orderby = 'c_order desc,c_id desc';
		}
		if(!empty($orderby)){
			if(!empty($_orderby)){
				$_orderby = $_orderby.','.$orderby;
			}else{
				$_orderby = $orderby;
			}
		}
		$order_str = empty($_orderby)?$this->prefix.'id ASC':$_orderby;
		$where_str .= (empty($where)?'':' and ' . $where);
		$innerWhere = '';
		switch($this->type){
			case 'new':
				$where_str.= " and b_top=1";
				break;
			case 'old':
				$where_str.= " and b_top=0";
				break;
			case 'ecocat':
				$where_str .= " and ecocat_id!='' and share_bs_id=''";
				break;
		}
		if(!empty($this->shareid)){
			$where_str .= sprintf(" and share_bs_id='%s' and ecocat_id=''",$this->shareid);
		}
		if(!empty($this->keyword)){
	  	switch(DB_TYPE){
	  		case 'dbmaker':
	  			$_sql =<<<SQL
and (b_name contain '%s'
or b_description contain '%s'
or ecocat_id contain '%s')
SQL;
	  			$_sql =<<<SQL
and (b_name contain '%s')
SQL;
	  			$where_str .= sprintf($_sql,$this->keyword,$this->keyword,$this->keyword);
	  			break;
	  		default:
	  			$_sql =<<<SQL
and (b_name like '%%%s%%'
or b_description like '%%%s%%'
or ecocat_id like '%%%s%%')
SQL;
					$where_str .= sprintf($_sql,$this->keyword,$this->keyword,$this->keyword);
					break;
		  	}
		}
		if(!empty($this->bsid)){
			$where_str .= sprintf(' and bs_id=%u',$this->bsid);
			$innerWhere = sprintf(' and vb.bs_id=%u',$this->bsid);
		}
		if(!empty($this->buid)){
			$where_str .= sprintf(' and bu_id=%u',$this->buid);
		}
		if(!empty($this->tag)){
			//$_sql = " and b_id in (select b_id from bookshelf2_book_tag where t_id in (%s))";
			$_sql = " and b_id in (select b_id from bookshelf2_book_tag where t_id in (%s) group by b_id having count(*)=%u)";
			$_tag=array();
			foreach($this->tag as $v){
				if(is_numeric($v)){
					array_push($_tag, $v);
				}
			}
			$arr_tag = implode(',',$_tag);
			$where_str .= sprintf($_sql,$arr_tag,count($_tag));
		}
		if(!empty($this->ShortcutID)){
			$_sql=<<<SQL
select node_key
from bookshelf2_tag_shortcut_node
where ts_id=%u
SQL;
			$_sql = sprintf($_sql, $this->ShortcutID);
			$arr_nodekey = $this->db->get_results($_sql);

			$_arrsql = array();
			foreach($arr_nodekey as $nodekey){
				$sql=<<<SQL
b_id in (select bt.b_id
from bookshelf2_book_tag bt
join bookshelf2_tag_shortcut_nodetag nt on(bt.t_id=nt.t_id and nt.node_key='%s')
group by bt.b_id
having count(*) = (select count(*)
	from bookshelf2_tag_shortcut_nodetag nt1
	where nt1.node_key='%s'))
SQL;
				$_arrsql[] = sprintf($_sql, $nodekey, $nodekey);
			}
			if(!empty($_arrsql)){
				$where_str .= sprintf(' and (%s)',implode(' or ',$_arrsql));
			}
		}

  	if(!empty($limit_from) || !empty($offset)){
  		if(DB_TYPE=='dbmaker') $offset+=$limit_from;
	  	$limit_str = 'limit '.$limit_from.','.$offset;
  	}

		$cols = '*';
		if(is_array($this->arrCol)){
			$cols = implode(',',$this->arrCol);
		}

		switch($this->book_status){
			case 'private':
				if(empty($this->buid)){
					$ee->Error('406.60');
				}
				if(!empty($this->category)){
					/*
					$_cate=array();
					foreach($this->category as $v){
						if(is_int($v)){
							array_push($_cate, $v);
						}
					}
					$arr_cate = implode(',',$_cate);
					*/
					$arr_cate = $this->category;
  				$sql =<<<SQL
select %s, ifnull(testnum,0)+ifnull(readnum,0) as is_read
from (
	select vb.*, concat('/%ss_',filename) as f_path, c.c_order, vt.testnum, vr.readnum
	from bookshelf2_view_bookdetail_personal vb
	left join bookshelf2_books_category bc on(vb.b_id=bc.b_id)
	left join bookshelf2_category c on(c.c_id=bc.c_id)
	left join (select b_id, sum(testnum) as testnum from bookshelf2_view_has_test group by b_id) vt on(vt.b_id=vb.b_id and vt.bu_id=%u)
	left join (select b_id, sum(readnum) as readnum from bookshelf2_view_had_read_book group by b_id) vr on(vr.b_id=vb.b_id and vb.bu_id=%u)
	where (c.c_id in (%s)
		or c.c_parent_id in (%s)) %s
) as tb
where 1=1 %s
SQL;
					$sql = sprintf($sql,$cols,FILE_UPLOAD_PATH,$this->buid,$this->buid,$arr_cate,$arr_cate,$innerWhere,$where_str);
				}else{
  				$sql =<<<SQL
select %s, ifnull(testnum,0)+ifnull(readnum,0) as is_read from (
select vb.*, concat('/%ss_',filename) as f_path, vt.testnum, vr.readnum
from bookshelf2_view_bookdetail_personal vb
	left join (select b_id, sum(testnum) as testnum from bookshelf2_view_has_test group by b_id) vt on(vt.b_id=vb.b_id and vt.bu_id=%u)
	left join (select b_id, sum(readnum) as readnum from bookshelf2_view_had_read_book group by b_id) vr on(vr.b_id=vb.b_id and vb.bu_id=%u)
	where 1=1 %s
) as tb
where 1=1 %s
SQL;
					$sql = sprintf($sql,$cols,FILE_UPLOAD_PATH,$this->buid,$this->buid,$innerWhere,$where_str);
				}
				break;
			case 'public':
				if(!empty($this->category)){
					/*
					$_cate=array();
					foreach($this->category as $v){
						if(is_int($v)){
							array_push($_cate, $v);
						}
					}
					$arr_cate = implode(',',$_cate);
					*/
					$arr_cate = $this->category;
  				$sql =<<<SQL
select %s, ifnull(testnum,0)+ifnull(readnum,0) as is_read from (
select c.c_id,c.c_name, vb.*, concat('/%ss_',filename) as f_path, c.c_order, vt.testnum, vr.readnum
from bookshelf2_view_bookdetail_public vb
left join bookshelf2_books_category bc on(vb.b_id=bc.b_id)
left join bookshelf2_category c on(c.c_id=bc.c_id)
	left join (select b_id, sum(testnum) as testnum from bookshelf2_view_has_test group by b_id) vt on(vt.b_id=vb.b_id)
	left join (select b_id, sum(readnum) as readnum from bookshelf2_view_had_read_book group by b_id) vr on(vr.b_id=vb.b_id)
where (c.c_id in (%s)
		or c.c_parent_id in (%s)) %s
) as tb
where 1=1 %s
SQL;
					$sql = sprintf($sql,$cols,FILE_UPLOAD_PATH,$arr_cate,$arr_cate,$innerWhere,$where_str);
				}else{
					$sql =<<<SQL
select %s, ifnull(testnum,0)+ifnull(readnum,0) as is_read from (
select c.c_id,c.c_name, vb.*, concat('/%ss_',filename) as f_path, c.c_order, vt.testnum, vr.readnum
from bookshelf2_view_bookdetail_public vb
left join bookshelf2_books_category bc on(vb.b_id=bc.b_id)
left join bookshelf2_category c on(c.c_id=bc.c_id)
	left join (select b_id, sum(testnum) as testnum from bookshelf2_view_has_test group by b_id) vt on(vt.b_id=vb.b_id)
	left join (select b_id, sum(readnum) as readnum from bookshelf2_view_had_read_book group by b_id) vr on(vr.b_id=vb.b_id)
	where 1=1 %s
) as tb
where 1=1 %s
SQL;
					$sql = sprintf($sql,$cols,FILE_UPLOAD_PATH,$innerWhere,$where_str);
				}
				break;
			case 'all':
			default:
					$sql =<<<SQL
select %s, ifnull(testnum,0)+ifnull(readnum,0) as is_read from (
select 0 as c_id, '' as c_name, vb.*, concat('/%ss_',filename) as f_path, vt.testnum, vr.readnum
from bookshelf2_view_bookdetail vb
	left join (select b_id, sum(testnum) as testnum from bookshelf2_view_has_test group by b_id) vt on(vt.b_id=vb.b_id)
	left join (select b_id, sum(readnum) as readnum from bookshelf2_view_had_read_book group by b_id) vr on(vr.b_id=vb.b_id)
	where 1=1 %s
) as tb
where 1=1 %s
SQL;
					$sql = sprintf($sql,$cols,FILE_UPLOAD_PATH,$innerWhere,$where_str);
				break;
		}
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
  	$data['result'] = $this->db->get_results($sqlwithorder);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
    foreach($data['result'] as $key=>$val){
      $data['result'][$key]['webbook_link'] = htmlspecialchars_decode($val['webbook_link']);
      $data['result'][$key]['ibook_link'] = htmlspecialchars_decode($val['ibook_link']);
    }
    return $data;
	}


  function getCategoryByBID($id){
  	$sql=<<<SQL
select c.*,bc.b_id 
from bookshelf2_category c 
join bookshelf2_books_category bc on (c.c_id = bc.c_id)
where bc.b_id= %u
SQL;
    $sql = sprintf($sql, $id);
    $rs = $this->db->get_results($sql);
		foreach($rs as $key=>$val){
      $rs[$key]['webbook_link'] = htmlspecialchars_decode($val['webbook_link']);
      $rs[$key]['ibook_link'] = htmlspecialchars_decode($val['ibook_link']);
    }
    return $rs;
  }

  function getUsersBookshelfByBID($id){
  	$sql=<<<SQL
select bu.*,ub.b_id 
from bookshelf2_bookshelf_users bu 
join bookshelf2_bookshelf_user_books ub on ( bu.bu_id = ub.bu_id ) 
where ub.b_id= %u
SQL;
    $sql = sprintf($sql, $id);
    $rs = $this->db->get_results($sql);
    return $rs;
  }

  function getMyBooksByBUID($cid,$id){
  	$sql =<<<SQL
select v.*, concat('/%ss_',v.filename) as f_path
from bookshelf2_view_bookdetail_personal_book v
where bu_id=%u 
SQL;
		if(!empty($cid)) $sql.='and (c_id=%u or c_id in (select c_id from %scategory where c_parent_id=%u))';

    $sql = sprintf($sql,FILE_UPLOAD_PATH, $id, $cid, DB_PREFIX, $cid);
    $rs = $this->db->get_results($sql);
    return $rs;
  }

  function insert($data){
    $category_arr = $data['c_id'];
    unset($data['c_id']);
    $data['create_date'] = date("Y-m-d H:i:s");

    $b_id = parent::insert($data,true);
    if($b_id && $category_arr){
    	$cate_arr = preg_split('/,/',$category_arr);
      $rs = $this->insert_book_category($cate_arr,$b_id);
    }
    return $b_id;
  }

  function update($id,$data){
    if(intval($id)>0)
    {
      $category_arr = $data['c_id'];
      unset($data['c_id']);

      $rs = parent::update($id,$data);
      if($rs && $category_arr){
				$cate_arr = preg_split('/,/',$category_arr);
        $rs = $this->update_book_category($cate_arr,$id);
      }
      return $rs;
    }else{
      return false;
    }
  }

  function del($id){
    if(intval($id)>0)
    {
      $rs = parent::del($id);
      if($rs){
        $rs = $this->delete_book_category_by_book($id);
	$this->delete_book_tags($id);
      }
      return $rs;
    }else{
      return false;
    }
  }

  function delByKey($key,$real=false){
  	$data = parent::getByKey(array($key));
    if($real){
exit;
	  	foreach($data as $row){
	  		$id = $row['b_id'];
	      $this->delete_book_category_by_book($id);
	      //remove book tag reference
	      $this->delete_book_tags($id);
	    }
    	$rs = parent::delByKey($key);
    }else{
    	$sql=<<<SQL
update bookshelf2_books set b_status=0 where b_key='%u'
SQL;
	$sql=sprintf($sql,$key);
			$rs=$this->db->query($sql);
    }
    return $rs;
  }

  function delete_book_tags($id){
  	$sql=<<<SQL
delete from BOOKSHELF2_BOOK_TAG where b_id=%u
SQL;
    $sql = sprintf($sql,$id );
    return $this->db->query($sql);
  }

  function insert_book_category($cate_arr,$b_id){
    $rs = true;
    foreach($cate_arr as $val){
      if($rs){
      	$sql=<<<SQL
insert into bookshelf2_books_category (b_id,c_id) values (%u,%u);
SQL;
        $sql = sprintf($sql,$b_id,$val );
        $rs = $this->db->query($sql);
      }
    }
    return $rs;
  }

  function delete_book_category_by_book($b_id){
  	$sql=<<<SQL
delete from bookshelf2_books_category where b_id=%u
SQL;
    $sql = sprintf($sql,$b_id );
    return $this->db->query($sql);
  }

  function update_book_category($cate_arr,$b_id){
    $rs = $this->delete_book_category_by_book($b_id);
    if($rs){
      $rs = $this->insert_book_category($cate_arr,$b_id);
    }
    return $rs;
  }

  function insert_users_bookshelf($bookshelf_user_arr,$b_id){
    $rs = true;
    foreach($bookshelf_user_arr as $val){
      if($rs){
      	$sql=<<<SQL
insert into bookshelf2_bookshelf_user_books (b_id,bu_id) values (%u,%u)
SQL;
        $sql = sprintf($sql,$b_id,$val);
        $rs = $this->db->query($sql);
      }
    }
    return $rs;
  }

  function delete_users_bookshelf_by_book($b_id){
  	$sql=<<<SQL
delete from bookshelf2_bookshelf_user_books where b_id=%u
SQL;
    $sql = sprintf($sql,$b_id);
    return $this->db->query($sql);
  }

  function update_users_bookshelf($bookshelf_user_arr,$b_id){
    $rs = $this->delete_users_bookshelf_by_book($b_id);
    if($rs){
      $rs = $this->insert_users_bookshelf($bookshelf_user_arr,$b_id);
    }
    return $rs;
  }

  function update_views($id,$type){
    if(intval($id)>0)
    {
    	$sql=<<<SQL
update bookshelf2_books set b_views_%s=b_views_%s+1 where b_id=%u
SQL;
      $sql = sprintf($sql,$type,$type,$id);
      $rs = $this->db->query($sql);
      return $rs;
    }else{
      return false;
    }
  }

  function sync_getByKey($Key){
    $sql = sprintf("select b.*, f.f_path as book_path from %s b left join %sfile f on b.file_id=f.f_id where %skey = '%s'",$this->table,DB_PREFIX, $this->prefix, $Key);
    return $this->db->query_first($sql);
  }

  function sync_hasChild($key, $nouse){
    return 0;
  }


  //所有的關聯都先刪除在新增，減少資料庫查詢的次數
  function sync_set_book_category($c_key, $b_id){
  	$sql=<<<SQL
delete from
bookshelf2_books_category
where b_id=%u
	and c_id in (select c_id from bookshelf2_category where c_key='%s');
SQL;
    $sql = sprintf($sql,DB_PREFIX,$b_id,DB_PREFIX,$c_key);
    $this->db->query($sql);
    $sql=<<<SQL
insert into bookshelf2_books_category (b_id,c_id)
select %u,c_id from bookshelf2_category
where c_key='%s';
SQL;
    $sql = sprintf($sql,$b_id,$c_key);
    $this->db->query($sql);
  }

  function sync_insert($data){
    $bs_key = $data['bs_key'];
    $c_key = $data['c_key'];
    
    if(isset($data['bs_key'])){
    	$sql=<<<SQL
select bs_id
from bookshelf2_bookshelfs
where bs_key='%s'
order by bs_id
SQL;
      $sql = sprintf($sql,DB_PREFIX,$bs_key);
      $rs = $this->db->query_first($sql);
      if($rs){
        $data['bs_id'] = $rs['bs_id'];
      }
    }
    $b_id = parent::insert($data,true);
    if($b_id && $c_key){
      $rs = $this->sync_set_book_category($c_key,$b_id);
    }
    return $b_id;
  }

  function sync_update($key,$data){
      $bs_key = $data['bs_key'];
      $c_key = $data['c_key'];
      $b_key = $data['b_key'];
      //unset($data['c_id']);

      $rs = parent::updateByKey($key,$data);
      if($rs && $c_key){
      	$rs1 = parent::getByKey($b_key);
        $b_id = $rs1['b_id'];
        $this->sync_set_book_category($c_key,$b_id);
      }
      return $rs;
  }
}
?>
