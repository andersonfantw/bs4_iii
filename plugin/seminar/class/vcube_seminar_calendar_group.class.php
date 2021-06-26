<?PHP
class vcube_seminar_calendar_group extends db_process{
	function __construct($db){
  	parent::db_process($db,'vcube_seminar_calendar_group','vmcg_');
	}

	function insert($data){
    parent::insert($data);
    return true;
	}

	function del($seminar_key){
		$sql=<<<SQL
delete from bookshelf2_vcube_seminar_calendar_group
where vsc_seminarkey='%s'
SQL;
		$sql = sprintf($sql,$seminar_key);
		$rs = $this->db->query($sql);
		return $rs;
	}
}
?>