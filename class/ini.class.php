<?PHP
class ini extends db_process{
	
  function ini($db) {
  	parent::db_process($db,'ini','');
  }
  
  public function getByGroup($group){
  	$sql=<<<SQL
select i.*
from %s i 
where group = '%s'
SQL;
  	$sql = sprintf($sql ,$this->table, $group);
  	$data['result'] = $this->db->get_results($sql);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
  	return $data;
	}

  public function getByKey($group,$key){
  	$sql=<<<SQL
select i.*
from %s i 
where group = '%s'
	and key = '%s'
SQL;
  	$sql = sprintf($sql ,$this->table, $group, $key);
  	$rs = $this->db->query_first($sql);
	return $rs;
  }

	public function update($group,$key,$val=''){
		if(is_array($key)){
			try{
				foreach($key as $k => $v){
					$data = array('group'=>'_'.$group,'key'=>(string)$k,'val'=>(string)$v);
					self::insert($data);
				}
				self::delete($group);
				$sql=<<<SQL
update %s set group='%s'
where group='_%s'
SQL;
				$sql = sprintf($sql ,$this->table, $group, $group);
				$this->db->query($sql);
				return true;
			}catch(Exception $e) {
				self::delete('_'.$group);
			}
		}else if(!empty($val)){
			$sql=<<<SQL
update %s set val='%s'
where group='%s' and key='%s'
SQL;
			$sql = sprintf($sql ,$this->table, $val, $group, $key);
			return $this->db->query($sql);
		}
		return false;
	}

  public function delete($group,$key=''){
  	$where = (empty($key))?'':sprintf("and key='%s'",$key);
  	$sql=<<<SQL
delete from %s
where group='%s' %s
SQL;
		$sql = sprintf($sql ,$this->table, $group, $where);
		return $this->db->query($sql);
  }
}
?>
