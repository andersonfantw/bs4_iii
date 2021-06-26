<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('filter','db','ejson');
$VCubeSeminarManager = new VCubeSeminarManager();
$cmd = $fs->valid($_GET['cmd'],'cmd');

switch($cmd){
	case 'getAccountList':
		$account = new account(&$db);
		$data = $account->getList();
		$arr_keep = array('u_id','u_name');
		for($i=0;$i<count($data['result']);$i++){
			foreach($data['result'][$i] as $k=>$v){
				if(!in_array($k,$arr_keep)) unset($data['result'][$i][$k]);
			}
		}
		echo json_encode($data['result']);
		break;
	case 'getGroupList':
		$group = new group(&$db);
		$data = $group->getList('',0,0,'',true);
		echo json_encode($data['result']);
		break;
	case 'getRoomList':
		$arr = $VCubeSeminarManager->room_list();
		echo json_encode($arr);
		break;
	case 'getReservationList':
		$mode = $fs->valid($_POST['mode'],'key');
		$start_limit = $fs->valid($_POST['start'],'timestamp');
		$end_limit = $fs->valid($_POST['end'],'timestamp');
		$id = $fs->valid($_POST['param'],'id');

		if($mode=='manager|user'){
			if(bssystem::getUID()) $mode='manager';
			else $mode='user';
		}

		switch($mode){
			case 'webadmin':
				//$roomid = $id
				$arr = $VCubeSeminarManager->getReservationListForWebadmin($id,$start_limit,$end_limit);
				break;
			case 'manager':
				//$uid = $id
				$uid = bssystem::getUID();
				$arr = $VCubeSeminarManager->getReservationListForManager($id,$uid,$start_limit,$end_limit);
				//$arr = $VCubeManager->getReservationListForManager($id);
				break;
			case 'user':
				//$buid = $id
				$buid=bssystem::getLoginBUID();
				$arr = $VCubeSeminarManager->getReservationListForUser($buid,$start_limit,$end_limit);
				break;
		}
		echo json_encode($arr);
		break;
	case 'addReservation':
		$uid = (int)$fs->valid($_POST['uid'],'id');
		$roomkey = $fs->valid($_POST['roomkey'],'key');
		$name = $fs->valid($_POST['name'],'name');
		$start = $fs->valid($_POST['start'],'date');
		$end = $fs->valid($_POST['end'],'date');
		$max = $fs->valid($_POST['max'],'num');
		//$sender_email = $fs->valid($_POST['sender_email'],'email');
		$g_id = (int)$fs->valid($_POST['gid'],'id');
		$data = array();
		$data['u_id'] = $uid;
		$data['vsc_roomkey'] = $roomkey;
		$data['vsc_name'] = $name;
		$data['vsc_start'] = $start;
		$data['vsc_end'] = $end;
		$data['vsc_max'] = $max;
		$data['g_id'] = $g_id;

		$arr = $VCubeSeminarManager->addReservation($data);
		echo json_encode($arr);
		break;
	case 'updateReservation':
		$seminarkey = $fs->valid($_POST['seminarkey'],'key');
		$roomkey = $fs->valid($_POST['roomkey'],'key');
		$name = $fs->valid($_POST['name'],'name');
		$start = $fs->valid($_POST['start'],'date');
		$end = $fs->valid($_POST['end'],'date');
		$max = $fs->valid($_POST['max'],'num');
		$g_id = $fs->valid($_POST['gid'],'id');
		$data = array();
		$data['vsc_seminarkey'] = $seminarkey;
		$data['vsc_roomkey'] = $roomkey;
		$data['vsc_name'] = $name;
		$data['vsc_start'] = $start;
		$data['vsc_end'] = $end;
		$data['vsc_max'] = $max;
		$data['g_id'] = $g_id;

		$arr = $VCubeSeminarManager->updateReservation($seminarkey,$data);
		echo json_encode($arr);
		break;
	case 'delReservation':
		$seminarkey = $fs->valid($_POST['seminarkey'],'key');
		$arr = $VCubeSeminarManager->delReservation($seminarkey);
		echo json_encode($arr);
		break;
	case 'IsSeminarBegin':
		$mode = $fs->valid($_POST['mode'],'cmd');
		$buid = $fs->valid($_POST['buid'],'id');
		$vcube_seminar_calendar = new vcube_seminar_calendar(&$db);
		$data = $vcube_seminar_calendar->getCurrentClass($mode,$buid);
		echo json_encode($data);
		break;
}
?>
