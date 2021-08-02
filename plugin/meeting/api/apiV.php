<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('filter','db','ejson');
$VCubeManager = new VCubeManager();

$cmd = $fs->valid($_GET['cmd'],'cmd');
switch($cmd){
	case 'getAccountList':
		$account = new account($db);
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
		$group = new group($db);
		$data = $group->getList('',0,0,'',true);
		echo json_encode($data['result']);
		break;
	case 'getReservationList':
		$mode = $fs->valid($_POST['mode'],'cmd');
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
				$arr = $VCubeManager->getReservationListForWebadmin($id,$start_limit,$end_limit);
				break;
			case 'manager':
				//$uid = $id
				$uid = bssystem::getUID();
				$arr = $VCubeManager->getReservationListForManager($id,$uid,$start_limit,$end_limit);
				//$arr = $VCubeManager->getReservationListForManager($id);
				break;
			case 'user':
				//$buid = $id
				$id=bssystem::getLoginBUID();
				$arr = $VCubeManager->getReservationListForUser($id);
				break;
		}

		echo json_encode($arr);
		break;
	case 'addReservation':
		$uid = (int)$fs->valid($_POST['uid'],'id');
		$roomid = $fs->valid($_POST['roomid'],'key');
		$name = $fs->valid($_POST['name'],'name');
		$start = $fs->valid($_POST['start'],'timestamp');
		$end = $fs->valid($_POST['end'],'timestamp');
		//$sender_email = $fs->valid($_POST['sender_email'],'email');
		$g_id = (int)$fs->valid($_POST['gid'],'id');
		$data = array();
		$data['u_id'] = $uid;
		$data['vmc_roomid'] = $roomid;
		$data['vmc_name'] = $name;
		$data['vmc_start'] = $start;
		$data['vmc_end'] = $end;
		$data['g_id'] = $g_id;

		$arr = $VCubeManager->addReservation($data);
		echo json_encode($arr);
		break;
	case 'updateReservation':
		$reservationid = $fs->valid($_POST['reservationid'],'key');
		$roomid = $fs->valid($_POST['roomid'],'key');
		$name = $fs->valid($_POST['name'],'name');
		$start = $fs->valid($_POST['start'],'timestamp');
		$end = $fs->valid($_POST['end'],'timestamp');
		//$sender_email = $fs->valid($_POST['sender_email'],'email');
		$g_id = $fs->valid($_POST['gid'],'id');
		$data = array();
		$data['vmc_reservationid'] = $reservationid;
		$data['vmc_roomid'] = $roomid;
		$data['vmc_name'] = $name;
		$data['vmc_start'] = $start;
		$data['vmc_end'] = $end;
		$data['g_id'] = $g_id;

		$arr = $VCubeManager->updateReservation($reservationid,$data);
		echo json_encode($arr);
		break;
	case 'delReservation':
		$reservationid = $fs->valid($_POST['reservationid'],'key');
		$VCubeManager->action_login();
		$arr = $VCubeManager->delReservation($reservationid);
		echo json_encode($arr);
		break;
	case 'login':
		$id = $fs->valid($_POST['id'],'acc');
		$pw = $fs->valid($_POST['pw'],'pwd');
		echo json_encode($VCubeManager->action_login($id,$pw));
		break;
	case 'logout':
		$VCubeManager->action_logout();
		break;
	case 'action_get_room_list':
		$arr = $VCubeManager->action_get_room_list();
		echo json_encode($arr);
		break;
	case 'action_create':
		$roomid = $fs->valid($_POST['roomid'],'key');
		$arr = $VCubeManager->action_create($roomid);
		echo json_encode($arr);
		break;
/*
	case 'action_get_list':
		$roomid = $fs->valid($_POST['roomid'],'key');
		$start_limit = $fs->valid($_POST['start_limit'],'timestamp');
		$end_limit = $fs->valid($_POST['end_limit'],'timestamp');
		$arr = $VCubeManager->action_get_list($roomid,$start_limit,$end_limit,100);
		echo json_encode($arr);
*/
		break;
	case 'action_update':
		$roomid = $fs->valid($_POST['roomid'],'key');
		$name = $fs->valid($_POST['name'],'name');
		$start = $fs->valid($_POST['start'],'timestamp');
		$end = $fs->valid($_POST['end'],'timestamp');
		$sender_email = $fs->valid($_POST['sender_email'],'email');
		$arr = $VCubeManager->action_update($roomid,$name,$start,$end,$sender_email);
		break;
	case 'action_delete':
		$reservationid = $fs->valid($_POST['reservationid'],'key');
		$arr = $VCubeManager->action_delete($reservationid);
		break;
	case 'action_get_invite':
		$reservationid = $fs->valid($_POST['reservationid'],'key');
		$arr = $VCubeManager->action_get_invite($reservationid);
		break;
	case 'action_add_invite':
		$reservationid = $fs->valid($_POST['reservationid'],'key');
		$email = $fs->valid($_POST['email']);
		$arr = $VCubeManager->action_add_invite($reservationid,$email);
		break;
	case 'action_delete_invite':
		$reservationid = $fs->valid($_POST['reservationid'],'key');
		$arr = $VCubeManager->action_delete_invite($reservationid);
		break;
	case 'action_start':
		$roomid = $fs->valid($_POST['roomid'],'key');
		$meetingid = $fs->valid($_POST['meetingid'],'key');
		$name = $fs->valid($_POST['name'],'name');
		$arr = $VCubeManager->action_start($roomid,$meetingid,$name);
		break;
	case 'action_stop':
		$roomid = $fs->valid($_POST['roomid'],'key');
		$arr = $VCubeManager->action_stop($roomid);
		break;
	case 'IsClassBegin':
		$mode = $fs->valid($_POST['mode'],'cmd');
		$buid = $fs->valid($_POST['buid'],'id');
		$vcube_meetings_calendar = new vcube_meetings_calendar($db);
		$data = $vcube_meetings_calendar->getCurrentClass($mode,$buid);
		echo json_encode($data);
		break;
	case 'redirect':
		$reservationid = $fs->valid($_GET['id'],'id');
		$name = $fs->valid($_GET['name']);
		$email = 'anderson@ttii.com.tw';

		$VCubeManager = new VCubeManager();
		//check if already invite
		$VCubeManager->action_login();
		$arr = $VCubeManager->action_get_invite($reservationid);
		foreach($arr['guests']['guest'] as $guest){
			if($guest['name']==$name){
				$url = $guest['invite_url'];
			}
		}
		if(empty($url)){
			$arr = $VCubeManager->action_add_invite($reservationid,$name,$email);
			$url = $arr['guests']['guest']['invite_url'];
		}
		if(!empty($url)){
			header("Location: $url");
		}
		break;
}
?>
