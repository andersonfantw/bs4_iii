<?PHP
class activecode extends db_process{

  function activecode($db) {
	parent::db_process($db,'activecode','ac_');
  }

	function getByID($code){
  	$sql=<<<SQL
select *
from bookshelf2_activecode
where ac_code='%s'
SQL;
  	$sql = sprintf($sql, $code);
    $data = $this->db->query_first($sql);
    return $data;
	}

	function getByBUID($buid){
  	$sql=<<<SQL
select *
from bookshelf2_activecode
where bu_id=%u
SQL;
  	$sql = sprintf($sql, $buid);
    $data = $this->db->get_results($sql);
    return $data;
	}

	function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
		$where_str = '';
		$sql=<<<SQL
select a.*, bu.bu_name, bu.bu_cname
from BOOKSHELF2_ACTIVECODE a
left join bookshelf2_bookshelf_users bu on(bu.bu_id=a.bu_id)
where 1=1 %
SQL;
		$sql = sprintf($sql,$where_str);
		$sqlwithorder = $sql.sprintf(' order by %s %s', $order_str,$limit_str);
  	$data['result'] = $this->db->get_results($sqlwithorder);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
		return $data;
	}

	function insert($data){

		$data1 = array();
		$data1['at_name'] = $data['name'];
		$data1['at_email'] = $data['email'];
		$data1['ac_code'] = $data['ac_code'];
		$data1['remote_ip'] = $USER_IP;
;
		
		$data['createdate']=date('Y-m-d H:i:s');
		$gids = $data['arr_gid'];

		unset($data['arr_gid']);
		unset($data['name']);
		unset($data['email']);
		$activecode_trial = new db_process($this->db,'ACTIVECODE_TRIAL','at_');

		$sql=<<<SQL
insert into BOOKSHELF2_ACTIVECODE_GROUP (AC_CODE,G_ID)
VALUES ('%s',%u);
SQL;

		foreach($gids as $gid){
			$sql1=sprintf($sql,$data['ac_code'],$gid);
			$this->db->query($sql1);

			$data1['g_id'] = $gid;
			$activecode_trial->insert($data1);
		}
		return parent::insert($data);
	}

	function update($code,$data){
		if(!empty($code)){
			$sql=<<<SQL
delete from BOOKSHELF2_ACTIVECODE_GROUP where AC_CODE='%s'
SQL;
			$sql = sprintf($sql, $code);
			$this->db->query($sql);

			$gids = $data['arr_gid'];
			unset($data['arr_gid']);
			$sql=<<<SQL
insert into BOOKSHELF2_ACTIVECODE_GROUP (AC_CODE,G_ID)
VALUES ('%s',%u);
SQL;
			foreach($gids as $gid){
				$sql1=sprintf($sql,$code,$gid);
				$this->db->query($sql1);
			}
			if(empty($data['registdate'])) $data['registdate']=date('Y-m-d H:i:s');
			$sql = sprintf("update %s set %s where %scode='%s'",$this->table ,$this->process_data_update($data), $this->prefix, $code );
			return $this->db->query($sql);
		}else{
			return false;
		}
	}
	
	function del($code){
		$sql=<<<SQL
delete BOOKSHELF2_ACTIVECODE_GROUP where AC_CODE='%s'
SQL;
		$sql = sprintf($sql, $code);
		$this->db->query($sql);

		$sql=<<<SQL
delete BOOKSHELF2_ACTIVECODE_TRIAL where AC_CODE='%s'
SQL;
		$sql = sprintf($sql, $code);
		$this->db->query($sql);

  	$sql=<<<SQL
delete from bookshelf2_activecode
where ac_code='%s'
SQL;
  	$sql = sprintf($sql, $code);
    return $this->db->query($sql);
	}
	public function isApplyTrial($email,$gid){
		$sql=<<<SQL
select count(*) as num
from BOOKSHELF2_ACTIVECODE_TRIAL
where at_email='%s' and g_id=%u
SQL;
		$sql = sprintf($sql,$email,$gid);
		$row = $this->db->query_first($sql);
		return $row['num'];
	}
	public function isReApply($buid,$gid){
		$sql=<<<SQL
select count(*) as num
from BOOKSHELF2_ACTIVECODE a
left join BOOKSHELF2_ACTIVECODE_GROUP ag on(a.ac_code=ag.ac_code)
where a.bu_id=%u and ag.g_id=%u
SQL;
		$sql = sprintf($sql, $buid, $gid);
		$row = $this->db->query_first($sql);
		return $row['num'];
	}
}
?>
