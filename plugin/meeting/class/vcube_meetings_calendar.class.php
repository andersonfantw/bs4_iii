<?PHP
class vcube_meetings_calendar extends db_process{
	function __construct($db){
  	parent::db_process($db,'vcube_meetings_calendar','vmc_');
	}
	function getByReservationID($reservationid){
		$sql=<<<SQL
select * from bookshelf2_vcube_meetings_calendar
where  vmc_reservationid='%s'
SQL;
		$sql = sprintf($sql,$reservationid);
		return $this->db->query_first($sql);
	}
	function getCurrentClass($mode,$buid){
		switch($mode){
			case 'u':
				$sql=<<<SQL
select
	vc.vmc_reservationid as id,
	vmc_roomid as roomid,
	vmc_name as name,
	vmc_start as admissiontime,
	vmc_end as end,
	TIMESTAMPADD('m',10,vmc_start) as start,
	TIMESTAMPDIFF('m',vmc_start,vmc_end) as duration,
	u_id
from bookshelf2_vcube_meetings_calendar vc
left join bookshelf2_vcube_meetings_calendar_group vg on(vg.vmc_reservationid=vc.vmc_reservationid)
where vg.g_id in (select g_id
	from bookshelf2_group_users gu
	where bu_id=%u)
	and (TIMESTAMPDIFF('m',vmc_start,now()) > 0
		and TIMESTAMPDIFF('m',vmc_end,now()) < 0)
SQL;
			break;
		case 'a':
				$sql=<<<SQL
select
	vc.vmc_reservationid as id,
	vmc_roomid as roomid,
	vmc_name as name,
	vmc_start as admissiontime,
	vmc_end as end,
	TIMESTAMPADD('m',10,vmc_start) as start,
	TIMESTAMPDIFF('m',vmc_start,vmc_end) as duration,
	u_id
from bookshelf2_vcube_meetings_calendar vc
where u_id=%u
	and (TIMESTAMPDIFF('m',vmc_start,now()) > 0
		and TIMESTAMPDIFF('m',vmc_end,now()) < 0)
SQL;
			break;
		}
		$sql = sprintf($sql,$buid);
		return $this->db->get_results($sql);
	}
	function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?'vmc_start DESC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select
	vmc_reservationid,
	vmc_roomid,
	vmc_name,
	vmc_start,
	vmc_end,
	TIMESTAMPDIFF('m',vmc_start,vmc_end) as duration,
	a.u_id,
	a.u_name,
	a.u_cname,
	'redirect' as url
from bookshelf2_vcube_meetings_calendar vmc
left join bookshelf2_account a on(vmc.u_id=a.u_id)
where 1=1 %s
SQL;
		$sql = sprintf($sql,$where_str);
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
  	$data['result'] = $this->db->get_results($sqlwithorder);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
  	$sql_mcg=<<<SQL
select g.g_id,g_key,g_name
from bookshelf2_vcube_meetings_calendar_group vmcg
join bookshelf2_groups g on(g.g_id=vmcg.g_id)
where VMC_RESERVATIONID='%s'
SQL;
  	for($i=0;$i<count($data['result']);$i++){
  		$sql1=sprintf($sql_mcg,$data['result'][$i]['vmc_reservationid']);
  		$data['result'][$i]['group'] = $this->db->get_results($sql1);
  	}
  	return $data;
	}
	function getListByUID($uid,$orderby='',$limit_from=0 ,$offset=0,$where=''){
		return $this->getList($orderby,$limit_from ,$offset,'a.u_id='.$uid);
	}
	function getListByBUID($buid,$orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?'vmc_start DESC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select
	vc.vmc_reservationid,
	vmc_roomid,
	vmc_name,
	vmc_start,
	vmc_end,
	TIMESTAMPDIFF('m',vmc_start,vmc_end) as duration,
	u_id,
	'redirect' as url
from bookshelf2_vcube_meetings_calendar vc
left join bookshelf2_vcube_meetings_calendar_group vg on(vg.vmc_reservationid=vc.vmc_reservationid)
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
		$vcube_meetings_calendar_group = new vcube_meetings_calendar_group($db);

		$data['CreateDate'] = date('Y-m-d H:i:s');

		//for now, only one group
		$data1 = array();
		$data1['g_id'] = intval($data['g_id']);
		$data1['vmc_reservationid'] = $data['vmc_reservationid'];

		unset($data['g_id']);
    parent::insert($data);
    $vcube_meetings_calendar_group->insert($data1);
    return true;
	}

	function update($reservationid,$data){
		global $db;
		$data1 = array();
		$data1['g_id'] = intval($data['g_id']);
		$data1['vmc_reservationid'] = $reservationid;
		//update group
		$vcube_meetings_calendar_group = new vcube_meetings_calendar_group($db);
		$vcube_meetings_calendar_group->del($reservationid);
		$vcube_meetings_calendar_group->insert($data1);
		//update db
		$sql=<<<SQL
update bookshelf2_vcube_meetings_calendar
set vmc_name='%s',vmc_start='%s',vmc_end='%s'
where vmc_reservationid='%s';
SQL;
		$sql = sprintf($sql,$data['vmc_name'],$data['vmc_start'],$data['vmc_end'],$reservationid);
		$rs = $this->db->query($sql);
		return $rs;
	}

	function del($vmc_reservationid){
		global $db;
		$vcube_meetings_calendar_group = new vcube_meetings_calendar_group($db);
		$vcube_meetings_calendar_group->del($vmc_reservationid);

		$sql=<<<SQL
delete from bookshelf2_vcube_meetings_calendar
where vmc_reservationid='%s'
SQL;
		$sql = sprintf($sql,$vmc_reservationid);
		$rs = $this->db->query($sql);
		return $rs;
	}
}
?>
