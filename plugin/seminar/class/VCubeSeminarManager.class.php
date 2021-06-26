<?PHP
//*******************************************************************
//apibase : http://api-demo.seminar.vcube.com/api/atom/
//acc : TWSEMAPI01
//pwd : nWT66Jub
//*******************************************************************
class VCubeSeminarManager{
	private $room_key = '';
	private $accountparam = array();

	function __construct(){
		$this->accountparam[0] = VCubeSeminarAPIBase;
		$this->accountparam[1] = VCubeSeminarID;
		$this->accountparam[2] = VCubeSeminarPWD;
/*
		$this->accountparam[0] = 'http://api-demo.seminar.vcube.com/api/atom/';
		$this->accountparam[1] = 'TWSEMAPI01';
		$this->accountparam[2] = 'nWT66Jub';
*/
	}

	public function getReservationListForWebadmin($roomkey,$start_limit,$end_limit){
		$_data = $this->reserve_list($roomkey,date('Y-m-d H:i:s',$start_limit),date('Y-m-d H:i:s',$end_limit),0,100);
		$data=array();
		for($i=0;$i<$_data['count'];$i++){
			$_start = date('Y-m-d H:i:s',strtotime($_data['seminar'][$i]['starttime']['local_time']));
			$_end = date('Y-m-d H:i:s',strtotime($_data['seminar'][$i]['endtime']['local_time']));
			$_duration = strtotime($_data['seminar'][$i]['endtime']['local_time'])
					- strtotime($_data['seminar'][$i]['starttime']['local_time']);
			$data[$i]=array();
			$data[$i]['id'] = $_data['seminar'][$i]['seminar_key'];
			$data[$i]['title'] = $_data['seminar'][$i]['title'].' '.$_data['seminar'][$i]['room_name'];
			$data[$i]['url'] = $_data['seminar'][$i]['url'];
			$data[$i]['start'] = $_start;
			$data[$i]['end'] = $_end;
			$data[$i]['in'] = $_data['seminar'][$i]['in'];
			$data[$i]['className'] = sprintf('%s|%s|%s|%s',$_duration,
								$_data['seminar'][$i]['uid'],
								$_data['seminar'][$i]['room_key'],
								$_data['seminar'][$i]['max']);
		}
		return $data;
	}

	public function getReservationListForManager($roomkey,$uid,$start_limit,$end_limit){
		$_data = $this->reserve_list($roomkey,date('Y-m-d H:i:s',$start_limit),date('Y-m-d H:i:s',$end_limit),$uid,100);
		$data=array();
		for($i=0;$i<$_data['count'];$i++){
                        $_start = date('Y-m-d H:i:s',strtotime($_data['seminar'][$i]['starttime']['local_time']));
                        $_end = date('Y-m-d H:i:s',strtotime($_data['seminar'][$i]['endtime']['local_time']));
			$_duration = strtotime($_data['seminar'][$i]['starttime']['local_time'])
					- strtotime($_data['seminar'][$i]['endtime']['local_time']);
			$data[$i]=array();
			$data[$i]['id'] = $_data['seminar'][$i]['seminar_key'];
			$data[$i]['title'] = $_data['seminar'][$i]['title'].' '.$_data['seminar'][$i]['room_name'];
			$data[$i]['url'] = $_data['seminar'][$i]['url'];
			$data[$i]['start'] = $_start;
			$data[$i]['end'] = $_end;
			$data[$i]['in'] = $_data['seminar'][$i]['in'];
			$data[$i]['className'] = sprintf('%s|%s|%s|%s',$_duration,
								$_data['seminar'][$i]['uid'],
								$_data['seminar'][$i]['room_key'],
								$_data['seminar'][$i]['max']);
		}
		return $data;
	}
	public function getReservationListForUser($buid,$start_limit,$end_limit){
		global $db;
		$vcube_seminar_calendar = new vcube_seminar_calendar(&$db);
		$_data = $vcube_seminar_calendar->getListByBUID($buid);
		$data = array();
		for($i=0;$i<count($_data['result']);$i++){
			$_duration = intval($_data['result'][$i]['vsc_end'])
										- intval($_data['result'][$i]['vsc_start']);

			$data[$i] = array();
			$data[$i]['id'] = $_data['result'][$i]['vsc_reservationid'];
			$data[$i]['title'] = $_data['result'][$i]['vsc_name'];
			$data[$i]['start'] = $_data['result'][$i]['vsc_start'];
			$data[$i]['end'] = $_data['result'][$i]['vsc_end'];
			$data[$i]['className'] = sprintf('%s|%s|%s',$_duration,
																$_data['result'][$i]['u_id'],
																$_data['result'][$i]['vsc_roomid']);

			if(strtotime($_data['result'][$i]['vsc_start']) < (time()+600) &&
				strtotime($_data['result'][$i]['vsc_end'])>time()){
				$data[$i]['url'] = 'redirect';
				$data[$i]['in'] = 1;
			}else{
				$data[$i]['url'] = '';
				$data[$i]['in'] = 0;
			}
		}
		return $data;
	}
	public function addReservation($data){
		global $db;
		$lang = 'en';
		$data['vsc_max'] = intval($data['vsc_max']);

		$uid = $data['u_id'];
		$account = new account(&$db);
		$rows = $account->getByID($uid);
		$presenter_name = sprintf('%s[%s]',$rows['u_cname'],$rows['u_name']);

		$gid = $data['g_id'];
		$roomkey = $data['vsc_roomkey'];
		$name = $data['vsc_name'];
		$start = date("Y-m-d H:i",strtotime($data['vsc_start']));
		$end = date("Y-m-d H:i",strtotime($data['vsc_end']));
		$max = $data['vsc_max'];

		$val = $this->reserve_add($roomkey,$name,$start,$end,$max);
		$data['vsc_seminarkey'] = $val['seminar_key'];
		$data['vsc_seminarkey'] = $val['seminar']['seminar_key'];
		$data['vsc_url'] = $val['seminar']['chairman_url'];
		if(!empty($data['vsc_seminarkey'])){
			$vcube_seminar_calendar = new vcube_seminar_calendar(&$db);
			$vcube_seminar_calendar->insert($data);
			$data1 = array();
			foreach($data as $k => $v){
				$key = str_replace('vsc_','',$k);
				$data1[$key] = $v;
			}
			$data1['start'] = date('Y-m-d\TH:i:s',strtotime($data1['start']));
			$data1['end'] = date('Y-m-d\TH:i:s',strtotime($data1['end']));
			return $data1;
		}
	}
	public function updateReservation($seminar_key,$data){
		global $db;
		$sender_email = $this->default_email;
		$lang = 'en';
		$email = $this->default_email;
		$presenter_email = $this->default_email;

		$uid = $data['u_id'];
		$account = new account(&$db);
		$rows = $account->getByID($uid);
		$presenter_name = sprintf('%s[%s]',$rows['u_cname'],$rows['u_name']);

		$gid = $data['g_id'];
		$roomkey = $data['vsc_roomkey'];
		$name = $data['vsc_name'];
		$start = $data['vsc_start'];
		$end = $data['vsc_end'];
		$max = $data['vsc_max'];
		$val = $this->reserve_update($seminar_key,$roomkey,$name,$start,$end,$max);
		$data['vsc_url'] = $val['presenter']['presenter_url'];
		if($val){
			$vcube_seminar_calendar = new vcube_seminar_calendar(&$db);
			$vcube_seminar_calendar->update($seminar_key,$data);
			return true;
		}
		return false;
	}
	public function delReservation($seminar_key){
		global $db;
		$val = $this->reserve_del($seminar_key);
		if($val){
			$vcube_seminar_calendar = new vcube_seminar_calendar(&$db);
			$vcube_seminar_calendar->del($seminar_key);
			return true;
		}
		return false;
	}
	public function addReservationGuest($seminar_key,$gid){
		global $db;
		$bookshelf_users = bookshelf_users(&$db);
		$vcube_seminar_calendar_user = new vcube_seminar_calendar_user(&$db);
		$vcube_seminar_calendar_group = new vcube_seminar_calendar_group($db);
		//insert seminar group
		$data1 = array('vsc_seminarkey'=>$seminar_key,'g_id');
		$vcube_seminar_calendar_group->insert($data1);
		$rows = $bookshelf_users->getList('',0,0,sprintf('g_id=%u',$gid));
		
		foreach($rows as $r){
			$name = $r['bu_cname'];
			if(!empty($row[$i]['bu_email'])){
				$email = $row[$i]['bu_email'];
			}
			$val = $this->participant_add($seminar_key,1);
			$data = array();
			$data['vsc_seminarkey'] = $seminar_key;
			$data['bu_id'] = $r['bu_id'];
			$data['vscu_participant'] = $val['participant']['participant_key'];
			$data['vscu_invitationkey'] = $val['participant']['invitation_key'];
			$data['vscu_url'] = $val['participant']['url'];
			$vcube_seminar_calendar_user->insert($data);
		}
	}
	public function delReservationGuest($seminar_key,$participantkey){
		$vcube_seminar_calendar_user = new vcube_seminar_calendar_user(&$db);
		$val = $this->participant_del($reservationid);
		if($val){
			$vcube_seminar_calendar_user->del($reservationid);
		}
	}

	public function room_list(){
		$param = array();
		$arr = $this->execute_api($param,'room','GET');
		return $arr;
	}

	public function reserve_list($room_key,$start_limit,$end_limit,$uid=0,$limit=200){
		global $db;
		$hash = array();
		$where_str = '';
		
		if(!empty($uid)){
			$where_str = sprintf(' and u_id=%u',$uid);
		}
		
		$vcube_seminar_calendar = new vcube_seminar_calendar(&$db);
		$data = $vcube_seminar_calendar->getList('',0,0,"timestampdiff('m',vsc_start,now())<43200".$where_str);
		foreach($data['result'] as $row){
			$timestamp = strtotime($row['vsc_start']);
			$hash[$timestamp] = $row;
		}
		$param = array('room_key' => $room_key,
									'seminar_name'=>'',
									'room_name'=>'',
									'get-history'=>'',
									'get-hold-status'=>'',
									'change-from'=>'',
									'start-from' => $start_limit,
									'start-to' => $end_limit,
									'order'=>'start-desc',
									'limit'=>$limit,
									'offset'=>'');
		$arr = $this->execute_api($param,'reserve','GET');
//var_dump($data,$arr);
		if(!isset($arr['seminar'][0])){
			$tmparr = array_merge($arr['seminar']);
		  unset($arr['seminar']);
		  $arr['seminar'] = array(0 => $tmparr);
		}
		//arrival time
		for($i=0;$i<count($arr['seminar']);$i++){
			$arr['seminar'][$i]['in'] = 0;
			$timestamp = strtotime($arr['seminar'][$i]['starttime']['local_time']);
			if(array_key_exists($timestamp,$hash)){
				if($timestamp>time()){
					//coming seminar
					$arr['seminar'][$i]['in'] = 1;
				}else{
					//expired seminar
					$arr['seminar'][$i]['in'] = 2;
				}
				$arr['seminar'][$i]['max'] = $hash[$timestamp]['vsc_max'];
				$arr['seminar'][$i]['uid'] = $hash[$timestamp]['u_id'];
			}
			//get link ready 10 mins before class begin
			if(strtotime($arr['seminar'][$i]['starttime']['local_time']) < (time()+600) &&
				strtotime($arr['seminar'][$i]['endtime']['local_time'])>time()){
				$seminarkey = $arr['seminar'][$i]['seminar_key'];
				$data = $vcube_seminar_calendar->getBySeminarKey($seminarkey);
				$arr['seminar'][$i]['url'] = $data['vsc_url'];
			}else{
				$arr['seminar'][$i]['url'] = '';
			}
		}
		if(isset($arr['seminar'])){
			return $arr;
		}
	}
	public function reserve_get($seminar_key){
		global $db;
		$param = array();
		$hash = array();
		$where_str = '';

		if(!empty($uid)){
			$where_str = sprintf(' and u_id=%u',$uid);
		}

		$vcube_seminar_calendar = new vcube_seminar_calendar(&$db);
		$data = $vcube_seminar_calendar->getList('',0,0,"timestampdiff('m',vsc_start,now())<43200".$where_str);
		foreach($data['result'] as $row){
			$timestamp = strtotime($row['vsc_start']);
			$hash[$timestamp] = $row;
		}

		$arr = $this->execute_api($param,'reserve/'.$seminar_key,'GET');
		if(isset($arr['seminar']['seminar_key'])){
		  $tmparr = array_merge($arr['seminar']);
		  unset($arr['seminar']);
		  $arr['seminar'] = array(0 => $tmparr);
		}
		//arrival time
		for($i=0;$i<count($arr['seminar']);$i++){
			$arr['seminar'][$i]['in'] = 0;
			if(array_key_exists(strtotime($arr['seminar'][$i]['starttime']['local_time']),$hash)){
				$arr['seminar'][$i]['in'] = 1;
			}
			//get link ready 10 mins before class begin
			if(strtotime($arr['seminar'][$i]['starttime']['local_time']) < (time()+600) &&
				strtotime($arr['seminar'][$i]['endtime']['local_time'])>time()){
				$seminar_key = $arr['seminar'][$i]['seminar_key'];
				$data = $vcube_meetings_calendar->getBySeminarKey($seminar_key);
				$arr['seminar'][$i]['url'] = $data['vsc_url'];
				$arr['seminar'][$i]['uid'] = $data['u_id'];
			}
		}
		return $arr;
	}
	public function reserve_add($room_key,$name,$starttime,$endtime,$max){
		$param = array('room_key'=>$room_key,
									'title'=>$name,
									'place'=>'8',
									'starttime'=>date("Y-m-d H:i",strtotime($starttime)),
									'endtime'=>date("Y-m-d H:i",strtotime($endtime)),
									'curtaintime'=>date("Y-m-d H:i",strtotime($starttime)+30*60),
									'max'=>$max,
									'is_use_local_source'=>0,
									'is_auto_rec'=>'1',
									'video_codec'=>'Sorenson',
									'open'=>'0',
									'is_entry_limit'=>'0',
									'use_public_chairman_url'=>'1');
		$arr = $this->execute_api($param,'reserve','POST');
		$seminar_key = $arr['seminar_key'];
		$param = array();
		$arr = $this->execute_api($param,'reserve/'.$seminar_key,'GET');
		return $arr;
	}
	public function reserve_update($seminar_key,$room_key,$name,$starttime,$endtime,$max){
		$param = array('room_key'=>$room_key,
									'title'=>$name,
									'place'=>'8',
									'starttime'=>date("Y-m-d H:i",strtotime($starttime)),
									'endtime'=>date("Y-m-d H:i",strtotime($endtime)),
									'curtaintime'=>date("Y-m-d H:i",strtotime($starttime)+30*60),
									'max'=>$max,
									'is_use_local_source'=>'1',
									'is_auto_rec'=>'1',
									'video_codec'=>'H264',
									'open'=>'0',
									'is_entry_limit'=>'0',
									'use_public_chairman_url'=>'1');
		$arr = $this->execute_api($param,'reserve/'.$seminar_key,'PUT');
		return $arr;
	}
	public function reserve_del($seminar_key){
		$param = array();
		$arr = $this->execute_api($param,'reserve/'.$seminar_key,'DELETE');
		return $arr;
	}

	public function participant_list($seminar_key){
		$param = array('seminar_key'=>'',
									'participant_key'=>'',
									'invitation_key'=>'',
									'order'=>'',
									'limit'=>'',
									'officeset'=>'');
		return $this->execute_api($param,'participant/'.$seminar_key,'GET');
	}
	public function participant_add($seminar_key,$num){
		$param = array('seminar_key'=>$seminar_key,
									'num'=>$num);
		return $this->execute_api($param,'participant','POST');
	}
	public function participant_del($seminar_key,$participant_key){
		$param = array('participant_key'=>$participant_key);
		return $this->execute_api($param,'participant/'.$seminar_key,'DELETE');
	}

	private function execute_api($param, $apiurl = '', $method = 'GET'){
		try{
			if ( $apiurl != '' ){
				$_apiurl = $apiurl;
			}else{
				throw new moodle_exception('No endpoint');
			}

			//if( $this->get_vcubeaccount() === false) return false;

			$nonce    = sha1( uniqid( rand(), true ) );
			$created  = date( 'Y-m-d\TH:i:s\Z', time() );
			$pdigest  = sha1( $nonce . $created . sha1($this->accountparam[2]) );
			$nonce    = base64_encode( $nonce );
			$pdigest  = base64_encode( $pdigest );
			$tmp = <<< WSSE
X-WSSE: UsernameToken Username="{$this->accountparam[1]}", PasswordDigest="{$pdigest}", Nonce="{$nonce}", Created="{$created}"
WSSE;
			$x_wsse = array($tmp);
			$url = $this->accountparam[0].$_apiurl;
			$ch=curl_init($url);

			switch($method){
				case 'POST':
					$xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
					$this->array_to_xml($param,$xml_data);
					$param = $xml_data->asXML();

					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
					break;
				case 'PUT':
					$xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
					$this->array_to_xml($param,$xml_data);
					$param = $xml_data->asXML();

					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
					curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
					break;
				case 'GET':
					if(!empty($param)){
						$query=http_build_query($param,null,'&');
						curl_setopt($ch, CURLOPT_URL,$url.'?'.$query);
					}
					curl_setopt($ch, CURLOPT_HTTPGET, true);
					break;
				case 'DELETE':
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
					break;
			}

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $x_wsse);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			$result = curl_exec($ch);
			curl_close($ch);
			$xml = new SimpleXMLElement($result);
			$json = json_encode($xml);
			return json_decode($json,TRUE);
		}catch(Exception $e){
			throw new $e->getMessage();
		}
	}

	private function array_to_xml( $data, &$xml_data ) {
	    foreach( $data as $key => $value ) {
	        if( is_array($value) ) {
	            if( is_numeric($key) ){
	                $key = 'item'.$key; //dealing with <0/>..<n/> issues
	            }
	            $subnode = $xml_data->addChild($key);
	            array_to_xml($value, $subnode);
	        } else {
	            $xml_data->addChild("$key",htmlspecialchars("$value"));
	        }
	     }
	}
}
?>
