<?PHP
/*
bookshelf2_vcube_meetings_calendar
vmc_reservationid
vmc_roomid
vmc_name
vmc_start
vmc_end

bookshelf2_vcube_meetings_calendar_group
vmc_reservationid
g_id

bookshelf2_vcube_meetings_calendar_user
vmc_reservationid
bu_id
vmcu_url
*/
class vcube_meetings_calendar_group extends db_process{
	function __construct($db){
  	parent::db_process($db,'vcube_meetings_calendar_group','vmcg_');
	}

	function insert($data){
    parent::insert($data);
    return true;
	}

	function del($vmc_reservationid){
		$sql=<<<SQL
delete from bookshelf2_vcube_meetings_calendar_group
where vmc_reservationid='%s'
SQL;
		$sql = sprintf($sql,$vmc_reservationid);
		$rs = $this->db->query($sql);
		return $rs;
	}
}
?>