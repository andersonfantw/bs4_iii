<?php
class shortcut extends db_process{
	var $bsid = 0;
	var $status='enabled';

	public function setBSID($_bsid){
		$this->bsid = $_bsid;
	}
	function reset(){
		$this->bsid=0;
	}
	function setStatus($status){
		$this->status=$status;
	}

  function shortcut($db) {
  	parent::db_process($db,'tag_shortcut','ts_');
  }

  function getList($orderby='',$limit_from=0 ,$offset=0,$where='',$all_list=false){
    $order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
    $where_str = empty($where)?'':'and '.$where;
    if(!empty($limit_from) || !empty($offset)){
    	if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }

		if(!empty($this->bsid)){
			$where_str .= sprintf(' and ts.bs_id=%u',$this->bsid);
		}
		switch($this->status){
			case 'all':
    	$sql =<<<SQL
select ts.*, concat(concat(f.f_name, '.'),f.f_type) as f_path
from bookshelf2_tag_shortcut ts
left join bookshelf2_file f on(ts.file_id=f.f_id)
where 1=1 %s
SQL;
				break;
			case 'enabled':
			default:
    	$sql =<<<SQL
select ts.*, concat(concat(f.f_name, '.'),f.f_type) as f_path
from bookshelf2_tag_shortcut ts
left join bookshelf2_file f on(ts.file_id=f.f_id)
where ts_status=1 %s
SQL;
			break;
		}
		$sql = sprintf($sql,$where_str);
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);

    $data['result'] = $this->db->get_results($sqlwithorder);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;
  }

	public function getByID($id){
		$sql=<<<SQL
select ts.*, concat(concat(f.f_name, '.'),f.f_type) as f_path
from bookshelf2_tag_shortcut ts
left join bookshelf2_file f on(ts.file_id=f.f_id)
where ts_id=%u
SQL;
		$sql = sprintf($sql,$id);
		$rows = $this->db->query_first($sql);
    if(count($rows)){
            return $rows;
    }else return null;
	}

  public function insert($data,$insert_id=false){
  	if(!empty($data['bs_id'])) $data['bs_id'] = intval($data['bs_id']);
  	if(!empty($data['file_id'])) $data['file_id'] = intval($data['file_id']);
  	return parent::insert($data,$insert_id);
  }
  
  public function update($id,$data){
  	if(!empty($data['bs_id'])) $data['bs_id'] = intval($data['bs_id']);
  	if(!empty($data['file_id'])) $data['file_id'] = intval($data['file_id']);
  	return parent::update($id,$data);
  }
}
?>
