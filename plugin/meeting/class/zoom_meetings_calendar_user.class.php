<?PHP
class zoom_meetings_calendar_user extends db_process{
	function __construct($db){
  	parent::db_process($db,'zoom_meetings_calendar_user','vmcu_');
	}
	
	function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?'zmc_uuid ASC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select zmc_uuid,bu_id,vmcu_url
from bookshelf2_zoom_meetings_calendar_user
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