<?PHP
class system_tag extends db_process{
	function system_tag($db) {
		parent::db_process($db,'system_tag','st_');
	}

	public function getByKey($method,$id=0,$tid=0){
		$where_str = sprintf("st.method='%s'",$method);
		if(!empty($id)){
			$where_str .= sprintf(' and st.id=%u',$id);
		}
		if(!empty($tid)){
			$where_str .= sprintf(' and st.t_id=%u',$tid);
		}
		$sql=<<<SQL
select vt.t_id,vt.key,vt.val,vt.path,vt.type
from bookshelf2_system_tag st
join bookshelf2_view_tags vt on(st.t_id=vt.t_id)
where %s
SQL;
		$sql = sprintf($sql,$where_str);
		$data = $this->db->get_results($sql);
		foreach($data as $row){
			$values = array();
			$sql=<<<SQL
select t_id,key,val,path,type
from bookshelf2_view_tags vt
where t_id in (%s)
order by t_id desc
SQL;
			$_path = $row['path'];
			$values = array();
			$values[] = array_values($row);
			if(!empty($_path)){
				$sql1 = sprintf($sql,$_path);
				$data1 = $this->db->get_results($sql1);
				foreach($data1 as $k => $v){
					$values[] = array_values($v);
					//array_unshift($values,array_values($v));
				}
			}
			//$values[] = array_values($row);
			//array_unshift($arr, $values);
			$arr[] = $values;
		}
		return $arr;
	}
	public function getDropDownItemByMethod($method){
		$sql=<<<SQL
select vt.t_id, vt.key, vt.val
from bookshelf2_system_tag st
left join bookshelf2_view_tags vt on(st.t_id=vt.t_id)
where method='%s'
SQL;
		$sql = sprintf($sql,$method);
		return $this->db->get_results($sql);
	}
	public function insert($date){
		//$date['createdate'] = date("Y-m-d H:i:s");
		parent::insert($date);
	}
	//$method,$id,$tid
	public function del($method,$id=0,$tid=0){
		$where_str = sprintf("method='%s'",$method);
		if(!empty($id)){
			$where_str .= sprintf(' and id=%u',$id);
		}
		if(!empty($tid)){
			$where_str .= sprintf(' and t_id=%u',$tid);
		}
		$sql=<<<SQL
delete from bookshelf2_system_tag where %s
SQL;
		$sql = sprintf($sql,$where_str);
		$this->db->query($sql);
	}
}
?>
