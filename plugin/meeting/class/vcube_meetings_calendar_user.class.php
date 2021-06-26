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
class vcube_meetings_calendar_user extends db_process{
	function __construct($db){
  	parent::db_process($db,'vcube_meetings_calendar_user','vmcu_');
	}
	
	function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?'vmc_reservationid ASC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select vmc_reservationid,bu_id,vmcu_url
from bookshelf2_vcube_meetings_calendar_user
where 1=1 %s
SQL;
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
  	$data['result'] = $this->db->get_results($sqlwithorder);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
  	return $data;
	}

	function insert($data){
    parent::insert($data);
    return true;
	}

	function del($vmc_reservationid){
		$rs = parent::del($id);
		return $rs;
	}
}
?>