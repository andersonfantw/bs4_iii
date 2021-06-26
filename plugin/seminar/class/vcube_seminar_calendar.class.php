<?PHP
class vcube_seminar_calendar extends db_process{
	function __construct($db){
  	parent::db_process($db,'vcube_seminar_calendar','vsc_');
	}
	function getBySeminarKey($seminarkey){
		$sql=<<<SQL
select * from bookshelf2_vcube_seminar_calendar
where vsc_seminarkey ='%s'
SQL;
		$sql = sprintf($sql,$seminarkey);
		return $this->db->query_first($sql);
	}
	function getCurrentClass($mode,$buid){
		switch($mode){
			case 'u':
				$sql=<<<SQL
select
	vsc.vsc_seminarkey as id,
	vsc_roomkey as roomid,
	vsc_name as name,
	vsc_start as admissiontime,
	vsc_end as end,
	TIMESTAMPADD('m',30,vsc_start) as start,
	TIMESTAMPDIFF('m',vsc_start,vsc_end) as duration,
	vsc_max as max,
	u_id,
	'redirect' as url
from bookshelf2_vcube_seminar_calendar vsc
left join bookshelf2_vcube_seminar_calendar_group vsg on(vsg.vsc_seminarkey=vsc.vsc_seminarkey)
where vsg.g_id in (select g_id
	from bookshelf2_group_users gu
	where bu_id=%u)
	and (TIMESTAMPDIFF('m',vsc_start,now()) >= 0
		and TIMESTAMPDIFF('m',vsc_end,now()) <= 0)
SQL;
			break;
		case 'a':
				$sql=<<<SQL
select
	vsc.vsc_seminarkey as id,
	vsc_roomkey as roomid,
	vsc_name as name,
	vsc_start as admissiontime,
	vsc_end as end,
	TIMESTAMPADD('m',30,vsc_start) as start,
	TIMESTAMPDIFF('m',vsc_start,vsc_end) as duration,
	vsc_max as max,
	u_id,
	'redirect' as url
from bookshelf2_vcube_seminar_calendar vsc
where u_id=%u
	and (TIMESTAMPDIFF('m',vsc_start,now()) >= 0
		and TIMESTAMPDIFF('m',vsc_end,now()) <= 0)
SQL;
			break;
		}
		$sql = sprintf($sql,$buid);
		$data = $this->db->get_results($sql);
		return $data;
	}
	function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?'vsc_start DESC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select
	vsc_seminarkey,
	vsc_roomkey,
	vsc_name,
	vsc_start,
	vsc_end,
	TIMESTAMPDIFF('m',vsc_start,vsc_end) as duration,
	vsc_max,
	u_id,
	'redirect' as url
from bookshelf2_vcube_seminar_calendar
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
		$order_str = empty($orderby)?'vsc_start DESC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select
	vsc.vsc_seminarkey,
	vsc_roomkey,
	vsc_name,
	vsc_start,
	vsc_end,
	TIMESTAMPDIFF('m',vsc_start,vsc_end) as duration,
	vsc_max,
	u_id,
	'redirect' as url,
from bookshelf2_vcube_seminar_calendar vc
left join bookshelf2_vcube_seminar_calendar_group vg on(vsg.vsc_seminarkey=vc.vsc_seminarkey)
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
		$order_str = empty($orderby)?'vsc_start DESC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select
	vsc.vsc_seminarkey,
	vsc_roomkey,
	vsc_name,
	vsc_start,
	vsc_end,
	TIMESTAMPDIFF('m',vsc_start,vsc_end) as duration,
	vsc_max,
	u_id,
	'redirect' as url
from bookshelf2_vcube_seminar_calendar vc
left join bookshelf2_vcube_seminar_calendar_group vg on(vsg.vsc_seminarkey=vc.vsc_seminarkey)
where vg.g_id in (select g_id
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
		$vcube_meetings_calendar_group = new vcube_seminar_calendar_group(&$db);

		$data['CreateDate'] = date('Y-m-d H:i:s');

		//for now, only one group
		$data1 = array();
		$data1['g_id'] = intval($data['g_id']);
		$data1['vsc_seminarkey'] = $data['vsc_seminarkey'];

		unset($data['g_id']);
    parent::insert($data);
    $vcube_meetings_calendar_group->insert($data1);
    return true;
	}

	function update($seminar_key,$data){
		global $db;
		$data1 = array();
		$data1['g_id'] = intval($data['g_id']);
		$data1['vsc_seminarkey'] = $seminar_key;
		//update group
		$vcube_meetings_calendar_group = new vcube_seminar_calendar_group(&$db);
		$vcube_meetings_calendar_group->del($seminar_key);
		$vcube_meetings_calendar_group->insert($data1);
		//update db
		$sql=<<<SQL
update bookshelf2_vcube_seminar_calendar
set vsc_name='%s',vsc_start='%s',vsc_end='%s'
where vsc_seminarkey='%s';
SQL;
		$sql = sprintf($sql,$data['vsc_name'],$data['vsc_start'],$data['vsc_end'],$seminar_key);
		$rs = $this->db->query($sql);
		return $rs;
	}

	function del($seminar_key){
		global $db;
		$vcube_meetings_calendar_group = new vcube_meetings_calendar_group(&$db);
		$vcube_meetings_calendar_group->del($seminar_key);

		$sql=<<<SQL
delete from bookshelf2_vcube_seminar_calendar
where vsc_seminarkey='%s'
SQL;
		$sql = sprintf($sql,$vsc_reservationid);
		$rs = $this->db->query($sql);
		return $rs;
	}
}
?>
