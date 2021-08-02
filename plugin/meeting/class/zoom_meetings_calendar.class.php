<?PHP
class zoom_meetings_calendar extends db_process{
	function __construct($db){
  	parent::db_process($db,'zoom_meetings_calendar','zmc_');
	}
	function getByUUID($uuid){
		$sql=<<<SQL
select * from bookshelf2_zoom_meetings_calendar
where  zmc_uuid='%s'
SQL;
		$sql = sprintf($sql,$uuid);
		return $this->db->query_first($sql);
	}
	function getByRoomID($roomid){
		$sql=<<<SQL
select * from bookshelf2_zoom_meetings_calendar
where zmc_roomid='%s'
SQL;
		$sql = sprintf($sql,$roomid);
		return $this->db->query_first($sql);
	}
	function getCurrentClass($mode,$buid){
		switch($mode){
			case 'u':
				$sql=<<<SQL
select
	zc.zmc_uuid as id,
	zmc_roomid as roomid,
	zmc_name as name,
	zmc_start as admissiontime,
	zmc_end as end,
	TIMESTAMPADD('m',10,zmc_start) as start,
	timestampdiff('s',zmc_start,zmc_end) as duration,
	u_id
from bookshelf2_zoom_meetings_calendar zc
left join bookshelf2_zoom_meetings_calendar_group zg on(zg.zmc_uuid=zc.zmc_uuid)
where zg.g_id in (select g_id
	from bookshelf2_group_users gu
	where bu_id=%u)
	and (TIMESTAMPDIFF('m',zmc_start,now()) > 0
		and TIMESTAMPDIFF('m',zmc_end,now()) < 0)
SQL;
				break;
			case 'a':
				$sql=<<<SQL
select
	zc.zmc_uuid as id,
	zmc_roomid as roomid,
	zmc_name as name,
	zmc_start as admissiontime,
	zmc_end as end,
	TIMESTAMPADD('m',10,zmc_start) as start,
	timestampdiff('s',zmc_start,zmc_end) as duration,
	u_id
from bookshelf2_zoom_meetings_calendar zc
where u_id=%u
	and (TIMESTAMPDIFF('m',zmc_start,now()) > 0
		and TIMESTAMPDIFF('m',zmc_end,now()) < 0)
SQL;
				break;
		}
		$sql = sprintf($sql,$buid);
		return $this->db->get_results($sql);
	}
	function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?'zmc_start DESC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select
	zmc_uuid,
	zmc_roomid,
	zmc_name,
	zmc_start,
	zmc_end,
	timestampdiff('s',zmc_start,zmc_end) as duration,
	u_id,
	'redirect' as url
from bookshelf2_zoom_meetings_calendar
where 1=1 %s
SQL;
		$sql = sprintf($sql,$where_str);
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
  	$data['result'] = $this->db->get_results($sqlwithorder);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
  	return $data;
	}
	function getListByUID($uid,$orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?'zmc_start DESC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select
	zc.zmc_uuid,
	zmc_roomid,
	zmc_name,
	zmc_start,
	zmc_end,
	timestampdiff('s',zmc_start,zmc_end) as duration,
	u_id,
	'redirect' as url,
from bookshelf2_zoom_meetings_calendar zc
left join bookshelf2_zoom_meetings_calendar_group zg on(zg.zmc_uuid=zc.zmc_uuid)
where u_id=%u and %s
SQL;
		$sql = sprintf($sql,$buid,$where_str);
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
  	$data['result'] = $this->db->get_results($sqlwithorder);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
  	return $data;
	}
	function getListByBUID($buid,$orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?'zmc_start DESC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select
	zc.zmc_uuid,
	zmc_roomid,
	zmc_name,
	zmc_start,
	zmc_end,
	timestampdiff('s',zmc_start,zmc_end) as duration,
	u_id,
	'redirect' as url
from bookshelf2_zoom_meetings_calendar zc
left join bookshelf2_zoom_meetings_calendar_group zg on(zg.zmc_uuid=zc.zmc_uuid)
where zg.g_id in (select g_id
	from bookshelf2_group_users gu
	where bu_id=%u) %s
SQL;
		$sql = sprintf($sql,$buid,$where_str);
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
  	$data['result'] = $this->db->get_results($sqlwithorder);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
  	return $data;
	}

	function insert($data){
		global $db;
		$zoom_meetings_calendar_group = new zoom_meetings_calendar_group($db);

		$data['zmc_roomid'] = (string)$data['zmc_roomid'];
		$data['CreateDate'] = date('Y-m-d H:i:s');

		//for now, only one group
		$data1 = array();
		$data1['g_id'] = intval($data['g_id']);
		$data1['zmc_uuid'] = $data['zmc_uuid'];

		unset($data['g_id']);
    parent::insert($data);
    $zoom_meetings_calendar_group->insert($data1);
    return true;
	}

	function update($uuid,$data){
		global $db;
		$data1 = array();
		$data1['g_id'] = intval($data['g_id']);
		$data1['zmc_uuid'] = $uuid;
		//update group
		$zoom_meetings_calendar_group = new zoom_meetings_calendar_group($db);
		$zoom_meetings_calendar_group->del($uuid);
		$zoom_meetings_calendar_group->insert($data1);
		//update db
		$sql=<<<SQL
update bookshelf2_zoom_meetings_calendar
set zmc_name='%s',zmc_start='%s',zmc_end='%s'
where zmc_roomid='%s';
SQL;
		$sql = sprintf($sql,$data['zmc_name'],$data['zmc_start'],$data['zmc_end'],$uuid);
		$rs = $this->db->query($sql);
		return $rs;
	}

	function del($uuid){
		global $db;
		$zoom_meetings_calendar_group = new zoom_meetings_calendar_group($db);
		$zoom_meetings_calendar_group->del($uuid);

		$sql=<<<SQL
delete from bookshelf2_zoom_meetings_calendar
where zmc_zoomid='%s'
SQL;
		$sql = sprintf($sql,$zmc_zoomid);
		$rs = $this->db->query($sql);
		return $rs;
	}
}
?>
