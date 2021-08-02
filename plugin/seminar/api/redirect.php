<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','filter','ehttp');
$cmd = $fs->valid($_GET['cmd'],'cmd');

switch($cmd){
	case 'get_vcubeseminar_url':
		$uid = bssystem::getUID();
		$seminar_key = $fs->valid($_GET['seminarkey'],'key');
		if(empty($uid)){
			$VCubeSeminarManager = new VCubeSeminarManager();
			$vcube_seminar_calendar_user = new vcube_seminar_calendar_user($db);
			//$invitation_key = $fs->valid($_GET['invitationkey'],'key');
			//$email = $fs->valid($_GET['email'],'email');
			$email = 'anderson@ttii.com.tw';
			$buid=bssystem::getLoginBUID();
	
			$where ="vsc_seminarkey='%s' and bu_id=%u";
			$data = $vcube_seminar_calendar_user->getList('',0,0,sprintf($where,$seminar_key,$buid));
			//check if already invite
			//$arr = $VCubeSeminarManager->participant_list($seminar_key);
			if(intval($data['total'])>0){
				$url = $data['result'][0]['vscu_url'];
			}
			if(empty($url)){
				$arr = $VCubeSeminarManager->participant_add($seminar_key,1);
	
				$data=array();
				$data['vsc_seminarkey'] = $seminar_key;
				$data['bu_id'] = $buid;
				$data['vscu_participant'] = $arr['participant']['participant_key'];
				$data['vscu_invitationkey'] = $arr['participant']['invitation_key'];
				$data['vscu_url'] = $arr['participant']['url'];
				$vcube_seminar_calendar_user->insert($data);
				$url = $arr['participant']['url'];
			}
		}else{
			$vcube_seminar_calendar = new vcube_seminar_calendar($db);
			$data = $vcube_seminar_calendar->getBySeminarKey($seminar_key);
			$url = $data['vsc_url'];
		}
		break;
}
if(!empty($url)){
	header("Location: $url");
}
?>
