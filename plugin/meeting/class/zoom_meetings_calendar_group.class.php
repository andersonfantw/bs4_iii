<?PHP
class zoom_meetings_calendar_group extends db_process{
	function __construct($db){
  	parent::db_process($db,'zoom_meetings_calendar_group','zmcg_');
	}

	function insert($data){
    parent::insert($data);
    return true;
	}

	function del($uuid){
		$sql=<<<SQL
delete from bookshelf2_zoom_meetings_calendar_group
where zmc_uuid='%s'
SQL;
		$sql = sprintf($sql,$uuid);
		$rs = $this->db->query($sql);
		return $rs;
	}
}
?>