<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','filter','ehttp');
$cmd = $fs->valid($_GET['cmd'],'cmd');

switch($cmd){
	case 'get_vcube_url':
		$reservationid = $fs->valid($_GET['reservationid'],'key');
		$name = $fs->valid($_GET['name'],'name');
		//$email = $fs->valid($_GET['email'],'email');
		$email = VCubeNoticeMail;

		$uid = bssystem::getUID();
		if(empty($uid)){
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
		}else{
			$vcube_meetings_calendar = new vcube_meetings_calendar($db);
			$data = $vcube_meetings_calendar->getByReservationID($reservationid);
			$url = $data['vmc_url'];
		}
		break;
}
if(!empty($url)){
	header("Location: $url");
}
?>
