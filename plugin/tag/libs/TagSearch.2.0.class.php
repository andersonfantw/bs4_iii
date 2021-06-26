<?PHP
class TagSearch{
	var $_condition = array();
	//var $_tagid = array();
	//var $_tagkey = array();
	//var $_tagval = array();
	//var $_tagkeyword = array();
	//var $_tagname = array();
	var $_return_columns = array();
	var $_pkey = array();
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
		if(!in_array($pkey,$this->_pkey)) $this->_pkey[] = $pkey;
		if(!is_array($val)) $val = array($val);
		$this->_condition[] = array('value',$pkey,$val);
	}
	function addConditionByTID($pkey,$id){
		if(!in_array($pkey,$this->_pkey)) $this->_pkey[] = $pkey;
		if(!is_array($id)) $id = array($id);
		$this->_condition[] = array('id',$pkey,$id);
	}
	function addConditionByTagKey($pkey,$key){
		if(!in_array($pkey,$this->_pkey)) $this->_pkey[] = $pkey;
		if(!is_array($key)) $key = array($key);
		$this->_condition[] = array('key',$pkey,$key);
	}
	function addConditionRangeByTagKey($pkey,$key,$from,$to){
		if(!in_array($pkey,$this->_pkey)) $this->_pkey[] = $pkey;
		$this->_condition[] = array('range',$pkey,$key,$from,$to);
	}
	function addConditionByTagKeyword($pkey,$keyword){
		if(!in_array($pkey,$this->_pkey)) $this->_pkey[] = $pkey;
		$this->_condition[] = array('keyowrd',$pkey,$keyword);
	}
	function Columns($return_columns){
		$this->_return_columns = $return_columns;
	}
	private function _makeCondition($exclude=array()){
		foreach($this->_condition as $c){
			if(!in_array($c[1],$exclude)){
				switch($c[0]){
					case 'value':
						list($type,$pkey,$val) = $c;
						$_where.=sprintf(" or (pkey='%s' and val='%s')",$pkey,$val);
						break;
					case 'id':
						list($type,$pkey,$arr_id) = $c;
						foreach($arr_id as $c1){
							$_where.=sprintf(" or t_id=%u",$c1);
						}
						break;
					case 'key':
						list($type,$pkey,$key) = $c;
						if(is_array($key)){
							foreach($key as $c1){
								$_where.=sprintf(" or (pkey='%s' and key='%s')",$pkey,$c1);
							}
						}else{
							$_where.=sprintf(" or (pkey='%s' and key='%s')",$pkey,$key);
						}
						break;
					case 'keyowrd':
						list($type,$pkey,$keyword) = $c;
						switch(DB_TYPE){
							case 'dbmaker':
								$_where.=sprintf(" or (pkey='%s' and val contain '%s')",$pkey,$keyword);
								break;
							default:
								$_where.=sprintf(" or (pkey='%s' and val like '%%%s%%')",$pkey,$keyword);
								break;
						}
						break;
					case 'range':
						list($type,$pkey,$key,$from,$to) = $c;
						for($i=$from;$i<=$to;$i++){
							$_where.=sprintf(" or key='%s'",$key.$i);
						}
						break;
				}
			}
		}
		if(!empty($_where)){
			$_where = substr($_where,4);
		}
		return $_where;
	}
	function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		global $db;
		$allow_rows = array('b_id','b_name','b_key','webbook_link','ibook_link');
		$_where = '';
		$_num_condition = count($this->_pkey);

		$_where = $this->_makeCondition();
/*
		foreach($this->_tagid as $c){
			if(is_array($c)){
				$_str='';
				foreach($c as $c1){
					$_where.=sprintf(" or t_id=%u",$c1);
					$_str.=sprintf(" or t_id=%u",$c1);
				}
				$_where2.=sprintf(' and (%s)',substr($_str,4));
			}else{
				$_where.=sprintf(" or t_id=%u",$c);
				$_where2.=sprintf(" and t_id=%u",$c);
			}
		}
		foreach($this->_tagkey as $c){
			if(is_array($c)){
				list($type,$pkey,$from,$to) = $c;
				switch($type){
					case 'range':
						$_range=array();
						for($i=$from;$i<=$to;$i++){
							$_where.=sprintf(" or key='%s'",$pkey.$i);
						}
						//$_where.=sprintf(" or (pkey='%s' and cast(val as int)>=%u and cast(val as int)<=%u)",$pkey,$from,$to);
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
		foreach($this->_tagname as $c){
			$_where.=sprintf(" or (key='%s' and pkey='%s')",$c[0],$c[1]);
		}
		foreach($this->_tagkeyword as $c){
				switch(DB_TYPE){
					case 'dbmaker':
						$_where.=sprintf(" or (val contain '%s' and pkey='%s')",$c[0],$c[1]);
						break;
					default:
						$_where.=sprintf(" or (val like '%%%s%%' and pkey='%s')",$c[0],$c[1]);
						break;
				}
		}
		$_where = substr($_where,4);
*/
		//find out t_id in querys
		$TagevolveManager = new TagevolveManager();
		//$TagevolveManager->loadToTree();
		$sql=<<<SQL
select t_id,key from(
select
	bt.t_id,tk.tk_name as key, tv.tv_name as val, bt.t_parent_id as pid,	tk1.tk_name as pkey
from BOOKSHELF2_TAG bt
left join BOOKSHELF2_TAGKEY tk on(bt.tk_id=tk.tk_id)
left join BOOKSHELF2_TAGVAL tv on(bt.tv_id=tv.tv_id)
left join BOOKSHELF2_TAG bt1 on(bt.t_parent_id=bt1.t_id)
left join BOOKSHELF2_TAGKEY tk1 on(bt1.tk_id=tk1.tk_id)) as t
where %s
SQL;
		$sql = sprintf($sql,$_where);
		$result =$db->get_results($sql);
		if(empty($result)){
			return array();
		}
		$arr_tid=array();
		$results = array();
		foreach($result as $row){
			$arr_tid[] = $row['t_id'];
			$arr_path = $TagevolveManager->getPathByID($row['t_id']);
			if(!empty($arr_path)){
				foreach($arr_path as $p){
					if(empty($p['year'])){
						$arr_tid[] = $p['id'];	
					}else{
						$_where = $this->_makeCondition(array($row['pkey'],'year'));
						$sql1 = sprintf($sql,$_where).sprintf(" or (t_id=%u and val='%u')",$p['id'],$p['year']);
						$results[] = $db->get_results($sql1);
					}
				}
			}
			if(!empty($results)){
				foreach($results as $r){
					$result = $result + $r;
				}
			}

/*
			$arr_tid[] = $row['t_id'];
			$r1 = $TagevolveManager->getNode($row['key']);
			$r2 = $TagevolveManager->getNode(sprintf('%s_%s',$row['key'],$row['t_id']));
			if($r1){
				$r = $r1;
			}elseif($r2){
				$r = $r2;
			}
			if($r){
				$path = $r->path();
				foreach($path as $p){
					if(!in_array($p['id'],$arr_tid)){
						$arr_tid[] = $p['id'];
					}
				}
			}
*/
		}
		//all query tid
		$str_tags = implode(',',$arr_tid);
		//generate return cols
		$_return_columns = array();
		foreach($this->_return_columns as $col){
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

		$order_str = empty($orderby)?'b_id ASC':$orderby;
    if(!empty($limit_from) || !empty($offset)){
      if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }

		$sql=<<<SQL
select b_id
from BOOKSHELF2_VIEW_BOOKTAG vb
where t_id in (%s)
group by b_id
having count(*)=%u
SQL;
		$sql = sprintf($sql,$str_tags,$_num_condition);
		$result = $db->get_results($sql);
		if(empty($result)){
			return array();
		}
		$str_bids='';
		foreach($result as $r){
			$str_bids.=','.$r['b_id'];
		}
		if(!empty($str_bids)){
			$str_bids=substr($str_bids,1);
		}
		
		$sql=<<<SQL
select b.b_id,%s
from bookshelf2_books b
where b_id in (%s)
SQL;
		$sql = sprintf($sql,$cols,$str_bids);
		//$sql = sprintf($sql,$cols,$_where2);
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
		$result =$db->get_results($sqlwithorder);
//var_dump($sqlwithorder,$result);
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
select vb.b_id, vt.*
from BOOKSHELF2_VIEW_BOOKTAG vb
left join BOOKSHELF2_VIEW_TAGS_MINALL vt on(vb.t_id=vt.t_id)
where b_id in(%s) and pkey in ('%s');
SQL;

		$sql = sprintf($sql,$str_bid,$str_tags);
		$result_tags =$db->get_results($sql);
		foreach($result_tags as $row){
			if(array_key_exists($row['b_id'],$arr_books)){
				if(!in_array($row['pkey'],$arr_books[$row['b_id']])){
					$arr_books[$row['b_id']][$row['pkey']]=$row['val'];
				}
			}
		}
		return $arr_books;
	}
}
?>
