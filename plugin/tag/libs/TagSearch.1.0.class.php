<?PHP
class TagSearch10{
	var $_tagid = array();
	var $_tagkey = array();
	var $_tagval = array();
	var $_tagkeyword = array();
	var $_tagname = array();
	var $tags = array();
	var $enableTagCols=false;
	function enableTagCols($enable){
		$this->enableTagCols=$enable;
	}
	function addTags($t){
		if(is_array($t)){
			$this->tags = array_merge($this->tags,$t);
		}else{
			$this->tags[] = $t;
		}
	}
	function addConditionByValue($pkey,$val){
		$this->_tagval[] = array($pkey,$val);
	}
	function addConditionByTID($id){
		$this->_tagid[] = $id;
	}
	function addConditionByTagKey($key){
		$this->_tagkey[] = $key;
	}
	function addConditionRangeByTagKey($key,$from,$to){
		$this->_tagkey[] = array('range',$key,$from,$to);
	}
	function addConditionByTagKeyword($keyword,$rootkey=''){
		$this->_tagkeyword[] = array($key,$rootkey);
	}
	function addConditionByTagName($name,$rootkey=''){
		$this->_tagname[] = array($name,$rootkey);
	}
	function Search($return_columns){
		global $db;
		$allow_rows = array('b_id','b_name','b_key','webbook_link','ibook_link');
		$_where = '';
		$_num_condition = count($this->_tagkey)
											+ count($this->_tagval)
											+ count($this->_tagkeyword)
											+ count($this->_tagname)
											+ count($this->_tagid);
		foreach($this->_tagid as $c){
			$_where.=sprintf(" or key='%s'",$c);
		}
		foreach($this->_tagkey as $c){
			if(is_array($c)){
				list($type,$pkey,$from,$to) = $c;
				switch($type){
					case 'range':
						$_where.=sprintf(" or (pkey='%s' and cast(val as int)>=%u and cast(val as int)<=%u)",$pkey,$from,$to);
						break;
				}
			}else{
				$_where.=sprintf(" or key='%s'",$c);
			}
		}
		foreach($this->_tagval as $c){
			list($pkey,$val) = $c;
			$_where.=sprintf(" or (pkey='%s' and val='%s')",$pkey,$val);
		}
		foreach($this->_tagkeyword as $c){
			if($c[1]===''){
				$_where.=sprintf(" or key='%s'",$c);
			}else{
				$_where.=sprintf(" or (key='%s' and pkey='%s')",$c[0],$c[1]);
			}
		}
		foreach($this->_tagname as $c){
			if($c[1]===''){
				switch(DB_TYPE){
					case 'dbmaker':
						$_where.=sprintf(" or val contain '%s'",$c);
						break;
					default:
						$_where.=sprintf(" or val like '%%%s%%'",$c);
						break;
				}
			}else{
				switch(DB_TYPE){
					case 'dbmaker':
						$_where.=sprintf(" or (key contain '%s' and pkey='%s')",$c[0],$c[1]);
						break;
					default:
						$_where.=sprintf(" or (key like '%%%s%%' and pkey='%s')",$c[0],$c[1]);
						break;
				}
			}
		}
		$_where = substr($_where,4);
		//find out t_id in querys
		$sql=<<<SQL
select t_id from(
select
	bt.t_id,tk.tk_name as key, tv.tv_name as val, bt.t_parent_id as pid,
	tk1.tk_name as pkey
from BOOKSHELF2_TAG bt
left join BOOKSHELF2_TAGKEY tk on(bt.tk_id=tk.tk_id)
left join BOOKSHELF2_TAGVAL tv on(bt.tv_id=tv.tv_id)
left join BOOKSHELF2_TAG bt1 on(bt.t_parent_id=bt1.t_id)
left join BOOKSHELF2_TAGKEY tk1 on(bt1.tk_id=tk1.tk_id)) as t
where %s
SQL;
		$sql = sprintf($sql,$_where);
		$result =$db->get_results($sql);
		$arr_rows=array();
		foreach($result as $row){
			$arr_rows[] = $row['t_id'];
		}
		$arr_rows = $arr_rows + $this->_tagid;
		$str_tags = implode(',',$arr_rows);
		//get evolve
		$sql=<<<SQL
select te_otid as t_id
from bookshelf2_tag_evolve
where te_type>0 and te_ntid in (%s)
SQL;
		$_arr = array();
		while(!empty($str_tags)){
			$sql1 = sprintf($sql,$str_tags);
			$result =$db->get_results($sql1);
			foreach($result as $row){
				$arr_rows[] = $row['t_id'];
				$_arr[] = $row['t_id'];
			}
			$str_tags = implode(',',$_arr);

		}
		//all query tid
		$str_tags = implode(',',$arr_rows);
		//generate return cols
		$_return_columns = array();
		foreach($return_columns as $col){
			$_col = strtolower($col);
			if(in_array($_col,$allow_rows)){
				$_return_columns[] = $_col;
			}
		}
		$cols = (empty($_return_columns))?'*':implode(',',$_return_columns);
		if(!empty($this->tags)){
			foreach($this->tags as $_t){
				$cols .= sprintf(", '' as %s",$_t);
			}
		}
		$sql=<<<SQL
select b_id,%s from bookshelf2_books
where b_id in (select b_id
	from BOOKSHELF2_BOOK_TAG
	where t_id in (%s)
	group by b_id
	having count(*)=%u)
SQL;
		$sql = sprintf($sql,$cols,$str_tags,$_num_condition);
		$result =$db->get_results($sql);
		if(!$this->enableTagCols){
			return $result;
		}
		//combin tag
		$arr_books=array();
		foreach($result as $row){
			$arr_books[$row['b_id']] = $row;
		}
		if(empty($arr_books)){
			return $result;
		}
		$arr_bid = array_keys($arr_books);
		$str_bid = implode(',',$arr_bid);
		$str_tags = implode("','",$this->tags);
		$sql=<<<SQL
select bt.b_id, vt.*
from bookshelf2_book_tag bt
left join bookshelf2_view_tags vt on(bt.t_id=vt.t_id)
where b_id in(%s) and pkey in ('%s');
SQL;
		
		$sql = sprintf($sql,$str_bid,$str_tags);
		$result_tags =$db->get_results($sql);
		foreach($result_tags as $row){
			if(array_key_exists($row['b_id'],$arr_books)){
				if(!in_array($row['pkey'],$arr_books['b_id'])){
					$arr_books[$row['b_id']][$row['pkey']]=$row['val'];
				}
			}
		}
		
		return $arr_books;
	}
}
?>
