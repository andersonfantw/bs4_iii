<?PHP
class TagSearch{
	var $_condition = array();
	//var $_tagid = array();
	//var $_tagkey = array();
	//var $_tagval = array();
	//var $_tagkeyword = array();
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
		$_where = '';$_where2 = '';
		$_num_condition = count($this->_pkey);

		$_where = $this->_makeCondition();

		//find out t_id in querys
		$TagevolveManager = new TagevolveManager();
		//$TagevolveManager->loadToTree();
		
		//convert codition to tag id
		$sql=<<<SQL
select t_id,key,val,pkey from(
select
	bt.t_id,tk.tk_name as key, tv.tv_name as val, bt.t_parent_id as pid,	tk1.tk_name as pkey
from BOOKSHELF2_TAG bt
left join BOOKSHELF2_TAGKEY tk on(bt.tk_id=tk.tk_id)
left join BOOKSHELF2_TAGVAL tv on(bt.tv_id=tv.tv_id)
left join BOOKSHELF2_TAG bt1 on(bt.t_parent_id=bt1.t_id)
left join BOOKSHELF2_TAGKEY tk1 on(bt1.tk_id=tk1.tk_id)) as t
where %s
SQL;
		$sql1 = sprintf($sql,$_where);
		$result = $db->get_results($sql1);
//var_dump($sql1,$result);
		if(empty($result)){
			return array();
		}
		$hash = array();
		$arr_tid=array();
		$arrtmp_tid=array();
		$results = array();
		foreach($result as $row){
			if(!array_key_exists($row['pkey'],$hash)){
				$hash[$row['pkey']]=array();
			}
			$hash[$row['pkey']][] = $row['t_id'];
			$arrtmp_tid[] = $row['t_id'];
		}
		$exclude=array('pcu'=>array(),'pi'=>array());
		foreach($exclude as $ek => $ev){
			if(array_key_exists($ek,$hash)){
				foreach($hash[$ek] as $t){
					$arr_path = $TagevolveManager->getPathByID($t);
					//see if set year
					if(!empty($arr_path)){
						foreach($arr_path as $p){
							$arr_tid[] = $p['id'];
						}
						if(!in_array($arr_path[count($arr_path)-1]['id'],$arrtmp_tid)){
							if(!empty($arr_path[count($arr_path)-1]['year'])){
								$exclude[$ek][$arr_path[count($arr_path)-1]['id']]=$arr_path[count($arr_path)-1]['year'];
							}
						}
					}
				}
			}
		}
		
		foreach($result as $row){
			$arr_tid[] = $row['t_id'];
			$arr_path = $TagevolveManager->getPathByID($row['t_id']);
			if(!empty($arr_path)){
				foreach($arr_path as $p){
					$arr_tid[] = $p['id'];	
				}
			}
/*
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
//var_dump($sql,$result);
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
where b_status=1 and b_id in (%s)
SQL;
		$sql = sprintf($sql,$cols,$str_bids);
		//$sql = sprintf($sql,$cols,$_where2);
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
		$result = $db->get_results($sqlwithorder);

		//combin tag
		$arr_books=array();
		$arr_books_id=array();
		foreach($result as $row){
			$arr_books[$row['b_id']] = $row;
			if(ENABLE_DECENTRALIZED==1){
				//$arr_books[$row['b_id']]['webbook_link'] = str_replace('webs@2/ebook/','webs@2/ebook/'.session_id().'/',$arr_books[$row['b_id']]['webbook_link']);
				$url = $arr_books[$row['b_id']]['webbook_link'];
				$url = str_replace(HttpLocalIPPort,'',$url);
				$url = str_replace(LocalHost,'',$url);
				//$url = str_replace('webs@2/ebook/','webs@2/ebook/'.session_id().'/',$url);
				$arr_books[$row['b_id']]['webbook_link'] = $url;
			}
			$arr_books_id[$row['b_id']] = array();
		}
		if(empty($arr_books)){
			return $result;
		}
		$arr_bid = array_keys($arr_books);
		$str_bid = implode(',',$arr_bid);
		if($this->enableTagCols){
			$str_tags = implode("','",$this->tags);
		}else{
			$str_tags = "pcu','pi','year";
		}
		
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
					//keep pcu & pi t_id & year value
					if(array_key_exists($row['pkey'],$exclude)){
						$arr_books_id[$row['b_id']][$row['pkey']]=$row['t_id'];
					}elseif($row['pkey']=='year'){
						$arr_books_id[$row['b_id']][$row['pkey']]=$row['val'];
					}
				}
			}
		}
		foreach($arr_books_id as $bid=>$row){
			if(array_key_exists($arr_books_id[$bid]['pcu'],$exclude['pcu'])){
				if($arr_books_id[$bid]['year'] >= $exclude['pcu'][$arr_books_id[$bid]['pcu']]){
					unset($arr_books[$bid]);
				}
			}
			if(array_key_exists($arr_books_id[$bid]['pi'],$exclude['pi'])){
				if($arr_books_id[$bid]['year'] >= $exclude['pi'][$arr_books_id[$bid]['pi']]){
					unset($arr_books[$bid]);
				}
			}
		}
		return $arr_books;
	}
}
?>
