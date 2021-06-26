<?php
class db_process extends dbtabledef{

  public function db_process($db,$table,$prefix) {
    $this->db = $db;
    $this->table = DB_PREFIX.$table;
    $this->prefix = $prefix;

    global $bs_code;
    $this->bs_code = $bs_code;
	
    parent::__construct();
  }

	public function setColumn($type,$null=false,$size=-1,$pkey=false){
		return array('type'=>$type,'null'=>$null,'$size'=>$size,'pkey'=>$pkey);
	}

  public function getByID($id){
    if(is_array($id)){
			$ids = implode(',',$id);
    	$sql = sprintf("select * from %s where %sid in (%s)",$this->table, $this->prefix, $ids);
    	$rows = $this->db->get_results($sql);
    }else{
    	$sql = sprintf("select * from %s where %sid = %u",$this->table, $this->prefix, $id);
    	$rows = $this->db->query_first($sql);
    }
    if(count($rows)){
			return $rows;
    }else return null;
  }

  public function getByKey($key){
  	if(is_array($key)){
			$keys = implode("','",$key);
			$sql = sprintf("select * from %s where %skey in ('%s')",$this->table, $this->prefix, $keys);
			$rows = $this->db->get_results($sql);  		
  	}else{
			$sql = sprintf("select * from %s where %skey = '%s'",$this->table, $this->prefix, $key);
  		$rows = $this->db->query_first($sql);
  	}
    if(count($rows)){
			return $rows;
    }else return null;
  }

  public function getByName($name){
		if(is_array($name)){
  		$names = implode("','",$name);
			$sql = sprintf("select * from %s where %skey in ('%s')",$this->table, $this->prefix, $names);
			$rows = $this->db->get_results($sql);  		
  	}else{
			$sql = sprintf("select * from %s where %sname = '%s'",$this->table, $this->prefix, $name);
			$rows = $this->db->query_first($sql);
  	}
    if(count($rows)){
			return $rows;
    }else return null;
  }

  public function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
    $order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
    $where_str = empty($where)?'':'where '.$where;
    if(!empty($limit_from) || !empty($offset)){
      if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }
    $sql =<<<SQL
select * from %s %s
SQL;
    $sql = sprintf($sql,$this->table,$where_str);
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
    
    $data['result'] = $this->db->get_results($sqlwithorder);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;
  }

  public function insert($data,$insert_id=false){
    $sql = sprintf("insert into %s %s",$this->table ,$this->process_data_insert($data) );
    $rs = $this->db->query($sql);
    if($insert_id && $rs)
			return $this->db->insert_id();
    else
      return $rs;
  }

  public function update($id,$data){
    if(intval($id)>0)
    {
      $sql = sprintf("update %s set %s where %sid=%u",$this->table ,$this->process_data_update($data), $this->prefix, $id );
      return $this->db->query($sql);
    }else{
      return false;
    }
  }

  public function updateByKey($key,$data){
    if(!empty($key))
    {
      $sql = sprintf("update %s set %s where %skey='%s'",$this->table ,$this->process_data_update($data), $this->prefix, $key );
      return $this->db->query($sql);
    }else{
      return false;
    }
  }
  
  public function updateByName($name,$data){
    if(!empty($name))
    {
      $sql = sprintf("update %s set %s where %sname='%s'",$this->table ,$this->process_data_update($data), $this->prefix, $name );
      return $this->db->query($sql);
    }else{
      return false;
    }
  }

  public function update_status($id,$status){
    if(intval($id)>0)
    {
      $sql = sprintf("update %s set %sstatus=%u where %sid=%u",$this->table ,$this->prefix,$status, $this->prefix, $id );
      return $this->db->query($sql);
    }else{
      return false;
    }
  }

  public function del($id){  
    if(intval($id)>0)
    {
      $sql = sprintf("delete from %s where %sid=%u",$this->table, $this->prefix, $id);
      return $this->db->query($sql);
    }else{
      return false;
    }
  }
  public function delByKey($key){
  	$sql = sprintf("delete from %s where %skey=%u",$this->table, $this->prefix, $key);
var_dump($sql);exit;
  	return $this->db->query($sql);
  }

  function process_data_update($data){
    $str = "";
    foreach($data as $key=>$val){
      switch($this->find($this->table,$key,'type')){
        case 'INTEGER':
        case 'SMALLINT':
      		$str .= ",".$key."=".$val;
      		break;
        case 'VARCHAR':
        case 'LONG VARCHAR':
        case 'TIMESTAMP':
      		$str .= ",".$key."='".$val."'";
      		break;
      	default:
      		switch(gettype($val)){
      			case 'boolean':
      			case 'integer':
      			case 'double':
		      		$str .= ",".$key."=".$val;
		      		break;
      			case 'string':
			default:
		      		$str .= ",".$key."='".$val."'";
		      		break;
      		}
      		break;
      }
    }
    if(!empty($str)) $str = substr($str,1);
    return $str;
  }

  function process_data_insert($data){
    $str = "";
    $keys = "";
    $values = "";
    foreach($data as $key=>$val){
      //if(!empty($str))
      //  $str .= ",";
      //$str .= "`".$key."`='".$val."'";
      $keys .= ",".$key;
      switch($this->find($this->table,$key,'type')){
        case 'INTEGER':
        case 'SMALLINT':
          $values .= ",".$val;
          break;
        case 'VARCHAR':
        case 'LONG VARCHAR':
        case 'TIMESTAMP':
          $values .= ",'".$val."'";
          break;
      	default:
      		switch(gettype($val)){
      			case 'boolean':
      			case 'integer':
      			case 'double':
		      		$values .= ",".$val;
		      		break;
      			case 'string':
			default:
		      		$values .= ",'".$val."'";
		      		break;
      		}
      		break;
      }
    }
    if(!empty($keys)){
    	$str = "(".substr($keys,1).") values (".substr($values,1).")" ;
    }
    return $str;
  }
}
?>
