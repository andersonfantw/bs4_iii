<?PHP
/**********************************************************************************
User API - ユーザーAPI
ユーザ認証関連 ( api/v1/user/ )
	ログイン (action_login)
	PINログイン (action_pin_login)
	所在地取得 (action_get_country_list)
	パスワード変更 (action_change_password)
	ユーザー情報の更新 (action_update)
	ログアウト (action_logout)
	メンバー一覧取得 (action_get_member_list)
	メンバー情報編集 (action_edit_member_info)
	部屋一覧取得 (action_get_room_list)
	部屋詳細取得 (action_get_room_detail)
	部屋状態取得 (action_get_room_status)
会議関連 ( api/v1/user/meeting/ )
	会議作成 (action_create)
	会議室作成 (action_add_room_one_time_meeting)
	会議開始 (action_start)
	ノッカー (action_knocker)
	資料追加 (action_wb_upload)
	会議終了 (action_stop)
	強制終了 (action_force_stop)
	メール招待 (action_send_invitation_mail)
	招待URL発行 (action_get_invite_url)
予約関連 ( api/v1/user/reservation/ )
	予約一覧 (action_get_list)
	予約内容詳細 (action_get_detail)
	予約内容詳細(セールス) (action_get_detail_sales)
	予約追加 (action_add)
	予約追加(セールス) (action_add_sales)
	予約変更 (action_update)
	予約変更(セールス) (action_update_sales)
	招待者追加（通常） (action_add_invite)
	招待者一覧 (action_get_invite)
	招待者一覧(セールス) (action_get_invite_sales)
	招待者削除 (action_delete_invite)
	資料追加 (action_add_document)
	資料追加（ストレージ） (action_add_document_storage)
	資料一覧取得 (action_get_document)
	資料名変更 (action_update_document)
	資料削除 (action_delete_document)
	予約削除 (action_delete)
会議記録関連 ( api/v1/user/meetinglog/ )
	会議記録一覧 (action_get_list)
	会議記録詳細 (action_get_detail)
	録画再生 (action_video_player)
	議事録再生 (action_minute_player)
	アドレス帳関連 ( api/v1/user/addressbook/ )
	アドレス帳追加 (action_add)
	アドレス帳一覧 (action_get_list)
	アドレス帳更新 (action_update)
	アドレス帳削除 (action_delete)
エコメーター関連 ( api/v1/user/eco/ )
	駅名検索 (action_get_station_list)
ストレージ関連 ( api/v1/user/storage/ )
	外部連携用フォルダ確認 (action_get_folder_key)
	ファイル一覧 (action_get_file_list)
	ファイル追加 (action_add_file)
	ファイル更新 (action_update_file)
	ファイル削除 (action_delete_file)

Admin API - 管理者API
管理者情報関連 ( api/v1/admin/ )
	管理者ログイン (action_login)
	管理者パスワード変更 (action_change_password)
	管理者ログアウト (action_logout)
部屋操作関連 ( api/v1/admin/room/ )
	部屋の設定変更 (action_update)
	メール資料貼り込みの送信元アドレス一覧取得 (action_get_wb_mail)
	メール資料貼り込みの送信元追加 (action_add_wb_mail)
	メール資料貼り込みの送信元更新 (action_update_wb_mail)
	メール資料貼り込みの送信元削除 (action_delete_wb_mail)
	表示順を上げる (action_sort_up)
	表示順を下げる (action_sort_down)
会議記録操作関連 ( api/v1/admin/meetinglog/ )
	オンデマンド有効 (action_set_ondemand)
	オンデマンド無効 (action_unset_ondemand)
	保護有効 (action_set_protect)
	保護無効 (action_unset_protect)
	パスワード設定 (action_set_password)
	パスワード解除 (action_unset_password)
	ECOメーター情報取得 (action_get_eco_info)
	映像を削除 (action_delete_video)
	議事録を削除 (action_delete_minutes)
	会議記録を削除 (action_delete)
メンバー管理関連 ( api/v1/admin/member/ )
	メンバー更新 (action_update)
	
v5
ユーザ認証関連 ( api/v5lite/user/ )
	ログイン (action_login)
	所在地取得 (action_get_country_list)
	ユーザー情報の更新 (action_update)
	ログアウト (action_logout)
	部屋一覧取得 (action_get_room_list)
	部屋詳細取得 (action_get_room_detail)
	部屋状態取得 (action_get_room_status)
会議関連 ( api/v5lite/user/meeting/ )
	会議開始 (action_create_meeting)
	資料共有会議開始 (action_create_wb_meeting)
	会議室作成 (action_add_room_one_time_meeting)
	一定期間その部屋で会議ができるURL発行 (action_get_time_limit_url)
予約関連 ( api/v5lite/user/reservation/ )
	予約一覧 (action_get_list)
	予約内容詳細 (action_get_detail)
	予約追加 (action_add)
	予約変更 (action_update)
	招待者追加 (action_add_invite)
	招待者一覧 (action_get_invite)
	招待者削除 (action_delete_invite)
	予約削除 (action_delete)
会議記録関連 ( api/v5lite/user/meetinglog/ )
	会議記録一覧 (action_get_list)
アドレス帳関連 ( api/v5lite/user/addressbook/ )
	アドレス帳追加 (action_add)
	アドレス帳一覧 (action_get_list)
	アドレス帳更新 (action_update)
	アドレス帳削除 (action_delete)
**********************************************************************************/
class VCubeManager{
	private $APIBASE = VCubeAPIBase;
	private $prefix='VCubeManager_';

	private $id=VCubeID;
	private $pwd=VCubePWD;
	private $default_email = VCubeNoticeMail;
	private $path_prefix = 'api/v5lite/';

	private $param = 
		array('lang'=>'zh-tw',
					'country'=>'auto',
					'timezone'=>'8',
					'output_type'=>'xml',
					'sender_email'=>'anderson@ttii.com.tw',
					'presenter[lang]'=>'zh-tw');
	//ja,en,zh-cn,ch-tw
	private $lang='';
	private $country='';
	private $timezone='';
	//xml,json
	private $n2my_session;
	private $output_type='xml';
	
	function __construct(){
		if(empty($this->id) || empty($this->pwd)){
			exit;
		}
		switch(VCubeVersion){
			case 'v5':
				$this->path_prefix = 'api/v5lite/';
				break;
			case 'v4':
				$this->path_prefix = 'api/v1/';
				break;
		}
		if(empty($_SESSION[$this->prefix.'n2my_session'])){
			$this->action_login();
		}
		$this->param['n2my_session'] = $_SESSION[$this->prefix.'n2my_session'];
	}
	function __destruct(){
	}

	public function getReservationListForWebadmin($roomid,$start_limit,$end_limit){
		$_data = $this->action_get_list($roomid,$start_limit,$end_limit,0,100);
		$data=array();
		for($i=0;$i<count($_data['reservations']['reservation']);$i++){
			$_start = date('Y-m-d H:i:s',$_data['reservations']['reservation'][$i]['reservation_start_date']);
			$_end = date('Y-m-d H:i:s',$_data['reservations']['reservation'][$i]['reservation_end_date']);
			$_duration = intval($_data['reservations']['reservation'][$i]['reservation_end_date'])
										- intval($_data['reservations']['reservation'][$i]['reservation_start_date']);
			$data[$i]=array();
			$data[$i]['id'] = $_data['reservations']['reservation'][$i]['reservation_id'];
			$data[$i]['title'] = $_data['reservations']['reservation'][$i]['reservation_name'];
			$data[$i]['url'] = $_data['reservations']['reservation'][$i]['url'];
			$data[$i]['start'] = $_start;
			$data[$i]['end'] = $_end;
			$data[$i]['in'] = $_data['reservations']['reservation'][$i]['in'];
			$data[$i]['className'] = base64_encode(sprintf('%s|%s=%s(%s)|%s|%s=%s',
																$_duration,
																$_data['reservations']['reservation'][$i]['manager']['u_id'],$_data['reservations']['reservation'][$i]['manager']['u_name'],$_data['reservations']['reservation'][$i]['manager']['u_cname'],
																$_data['reservations']['reservation'][$i]['room_id'],
																$_data['reservations']['reservation'][$i]['group'][0]['g_id'],$_data['reservations']['reservation'][$i]['group'][0]['g_name']));
		}
		return $data;
	}
	public function getReservationListForManager($roomid,$uid,$start_limit,$end_limit){
		$_data = $this->action_get_list($roomid,$start_limit,$end_limit,$uid,100);
		$data=array();
		for($i=0;$i<count($_data['reservations']['reservation']);$i++){
			$_start = date('Y-m-d H:i:s',$_data['reservations']['reservation'][$i]['reservation_start_date']);
			$_end = date('Y-m-d H:i:s',$_data['reservations']['reservation'][$i]['reservation_end_date']);
			$_duration = intval($_data['reservations']['reservation'][$i]['reservation_end_date'])
										- intval($_data['reservations']['reservation'][$i]['reservation_start_date']);
			$data[$i]=array();
			$data[$i]['id'] = $_data['reservations']['reservation'][$i]['reservation_id'];
			$data[$i]['title'] = $_data['reservations']['reservation'][$i]['reservation_name'];
			$data[$i]['url'] = $_data['reservations']['reservation'][$i]['url'];
			$data[$i]['start'] = $_start;
			$data[$i]['end'] = $_end;
			$data[$i]['in'] = $_data['reservations']['reservation'][$i]['in'];
			$data[$i]['className'] = base64_encode(sprintf('%s|%s=%s(%s)|%s|%s=%s',
																$_duration,
																$_data['reservations']['reservation'][$i]['manager']['u_id'],$_data['reservations']['reservation'][$i]['manager']['u_name'],$_data['reservations']['reservation'][$i]['manager']['u_cname'],
																$_data['reservations']['reservation'][$i]['room_id'],
																$_data['reservations']['reservation'][$i]['group'][0]['g_id'],$_data['reservations']['reservation'][$i]['group'][0]['g_name']));
		}
		return $data;
	}
	public function getReservationListForUser($buid,$start_limit,$end_limit){
		global $db;
		$vcube_meetings_calendar = new vcube_meetings_calendar($db);
		$_data = $vcube_meetings_calendar->getListByBUID($buid);
		$data = array();
		for($i=0;$i<count($_data['result']);$i++){
			$_duration = intval($_data['result'][$i]['vmc_end'])
										- intval($_data['result'][$i]['vmc_start']);

			$data[$i] = array();
			$data[$i]['id'] = $_data['result'][$i]['vmc_reservationid'];
			$data[$i]['title'] = $_data['result'][$i]['vmc_name'];
			$data[$i]['start'] = $_data['result'][$i]['vmc_start'];
			$data[$i]['end'] = $_data['result'][$i]['vmc_end'];
			$data[$i]['in'] = 1;
			$data[$i]['className'] = sprintf('%s|%s|%s',$_duration,
																$_data['result'][$i]['u_id'],
																$_data['result'][$i]['vmc_roomid']);

			if(strtotime($_data['result'][$i]['vmc_start']) < (time()+600) &&
				strtotime($_data['result'][$i]['vmc_end'])>time()){
				$data[$i]['url'] = 'apiV';
			}else{
				$data[$i]['url'] = '';
			}
		}
		return $data;
	}
	public function addReservation($data){
		global $db;
		$lang = 'en';
		$sender_email = $this->default_email;
		$email = $this->default_email;
		$presenter_email = $this->default_email;

		$uid = $data['u_id'];
		$account = new account($db);
		$rows = $account->getByID($uid);
		$presenter_name = sprintf('%s[%s]',$rows['u_cname'],$rows['u_name']);

		$gid = $data['g_id'];
		$roomid = $data['vmc_roomid'];
		$name = $data['vmc_name'];
		$start = $data['vmc_start'];
		$end = $data['vmc_end'];
/*
		unset($data['g_id']);
		$bookshelf_users = bookshelf_users($db);
		$rows = $bookshelf_users->getList('',0,0,sprintf('g_id=%u',$gid));
		$guest = array();
		$arr_buid = array();
		for($i=0;$i<count($row['result']);$i++){
			if(!empty($row[$i]['bu_email'])){
				$email = $row[$i]['bu_email'];
			}
			$arr_buid[] = $row[$i]['bu_id'];
			$guest[$i] = array('name'=>$r['bu_cname'],
												'email'=>$email,
												'lang'=>$lang);
		}
*/
		$val = $this->action_add($roomid,$name,$start,$end,$sender_email,1,$presenter_name,$presenter_email);
		$data['vmc_reservationid'] = $val['reservation_id'];
		//$data['vmc_url'] = $val['presenter']['presenter_url'];
		$data['vmc_url'] = $val['url'];
		if(!empty($data['vmc_reservationid'])){
			$vcube_meetings_calendar = new vcube_meetings_calendar($db);
			$vcube_meetings_calendar->insert($data);
			$data1 = array();
			foreach($data as $k => $v){
				$key = str_replace('vmc_','',$k);
				$data1[$key] = $v;
			}
			$data1['start'] = date('Y-m-d\TH:i:s',strtotime($data1['start']));
			$data1['end'] = date('Y-m-d\TH:i:s',strtotime($data1['end']));
			
			return $data1;
		}
	}
	public function updateReservation($reservationid,$data){
		global $db;
		$sender_email = $this->default_email;
		$lang = 'en';
		$email = $this->default_email;
		$presenter_email = $this->default_email;

		$uid = $data['u_id'];
		$account = new account($db);
		$rows = $account->getByID($uid);
		$presenter_name = sprintf('%s[%s]',$rows['u_cname'],$rows['u_name']);

		$gid = $data['g_id'];
		$roomid = $data['vmc_roomid'];
		$name = $data['vmc_name'];
		$start = $data['vmc_start'];
		$end = $data['vmc_end'];
		$val = $this->action_update($reservationid,$roomid,$name,$start,$end,$sender_email,1,$presenter_name,$presenter_email);
		$data['vmc_url'] = $val['presenter']['presenter_url'];
		if($val){
			$vcube_meetings_calendar = new vcube_meetings_calendar($db);
			$vcube_meetings_calendar->update($reservationid,$data);
			return true;
		}
		return false;
	}
	public function delReservation($reservationid){
		global $db;
		$val = $this->action_delete($reservationid);
		if($val){
			$vcube_meetings_calendar = new vcube_meetings_calendar($db);
			$vcube_meetings_calendar->del($reservationid);
			return true;
		}
		return false;
	}
	public function addReservationGuest($reservationid,$gid){
		global $db;
		$bookshelf_users = bookshelf_users($db);
		$vcube_meetings_calendar_user = new vcube_meetings_calendar_user($db);
		$rows = $bookshelf_users->getList('',0,0,sprintf('g_id=%u',$gid));
		
		foreach($rows as $r){
			$name = $r['bu_cname'];
			if(!empty($row[$i]['bu_email'])){
				$email = $row[$i]['bu_email'];
			}
			$val = $this->action_add_invite($reservationid,$name,$email);
			$data = array();
			$data['vmc_reservationid'] = $reservationid;
			$data['bu_id'] = $r['bu_id'];
			$data['vmcu_url'] = $val['guests']['guest']['invite_url'];
			$vcube_meetings_calendar_user->insert($data);
		}
	}
	public function delReservationGuest($reservationid){
		$vcube_meetings_calendar_user = new vcube_meetings_calendar_user($db);
		$val = $this->action_delete_invite($reservationid);
		if($val){
			$vcube_meetings_calendar_user->del($reservationid);
		}
	}
	
	private function _connect($cmd,$path,$p,$param){
/* example
		$URL = $APIBASE."/user/?action_login=";
		$URL .= "&id=".$id;
		$URL .= "&pw=".$pwd;
		
		$URL .= "&lang=zh-cn&country=cn&timezone=8&enc=&login_type=&output_type=xml";
		$xls = simplexml_load_file($URL);
*/
		global $ee;
		$URL = sprintf('%s%s?%s=',$this->APIBASE,$path,$cmd);
		foreach($p as $k=>$v){
			if(array_key_exists($v,$param)){
				$URL .= sprintf("&%s=%s",$v,urlencode($param[$v]));
			}else{
				$URL .= sprintf("&%s=%s",$v,urlencode($this->param[$v]));
			}
		}
		$ch = curl_init($URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($ch);
		curl_close($ch);
		$xls = simplexml_load_string($content);
		//$xls = simplexml_load_file($URL);
		if($xls){
			$json = json_encode($xls);
			$arr = json_decode($json,TRUE);
			return $arr;
		}else{
			$ee->Error('500');
		}
	}
	public function action_login(){
		$path  = $this->path_prefix.'user/';
		$p = array('id','pw','output_type','lang','country','timezone');
		$cmd = 'action_login';
		$param1 = array();
		$param1['id'] = $this->id;
		$param1['pw'] = $this->pwd;
		$arr = $this->_connect($cmd,$path,$p,$param1);
		if($arr['status']){
			$_SESSION[$this->prefix.'n2my_session'] = $arr['data']['session'];
			return true;
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<type>user</type>
<session>3jfjun9qm345i48e0hauio7qn6</session>
<user_info>
<user_id>api_test</user_id>
<intra_fms>0</intra_fms>
<user_name />
<is_one_time_meeting>0</is_one_time_meeting>
</user_info>
		*/
	}
	public function action_logout(){
		$path  = $this->path_prefix.'user/';
		$p = array('n2my_session','output_type');
		$cmd = 'action_logout';
		$param1 = array();
		$arr = $this->_connect($cmd,$path,$p,$param1);
		if(!empty($arr['status'])){
			unset($this->param['n2my_session']);
			return true;
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data />
</result>
		*/
		
	}
	public function action_get_room_list(){
		$path  = $this->path_prefix.'user/';
		$p = array('n2my_session','output_type');
		$cmd = 'action_get_room_list';
		$param1 = array();
		$arr = $this->_connect($cmd,$path,$p,$param1);
		if($arr['status']){
			return $arr['data']['rooms'];
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<rooms>
<room>
<room_info>
<max_seat>10</max_seat>
<max_audience_seat>0</max_audience_seat>
<max_whiteboard_seat>0</max_whiteboard_seat>
<room_name>部屋１</room_name>
<room_id>api_test-1-6b4e</room_id>
<use_sales>0</use_sales>
<transcoder>0</transcoder>
<max_record_size>524288000</max_record_size>
</room_info>
<options>
<meeting_ssl>0</meeting_ssl>
<desktop_share>0</desktop_share>
<high_quality>0</high_quality>
<mobile_phone_number>0</mobile_phone_number>
<h323_number>0</h323_number>
<whiteboard>0</whiteboard>
<multicamera>0</multicamera>
<telephone>0</telephone>
<smartphone>0</smartphone>
<record_gw>0</record_gw>
<h264>0</h264>
<video_conference>0</video_conference>
</options>
</room>
</rooms>
</data>
</result>
		*/
		$this->room_id = $arr['room_id'];
	}
	public function action_get_room_detail($roomid){
		$path  = $this->path_prefix.'user/';
		$p = array('n2my_session','output_type');
		$cmd = 'action_get_room_detail';
		$param1 = array('room_id'=>$roomid);
		$xls = $this->_connect($path,$p,$param1);
		if($xls->status){
			$json = json_encode($xls->data->room_info);
			$arr = json_decode($json,TRUE);
			return $arr;
		}
		return false;

		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<room_info>
<max_seat>10</max_seat>
<max_audience_seat>0</max_audience_seat>
<room_name>部屋１</room_name>
<room_id>api_test-1-6b4e</room_id>
<mfp>all</mfp>
<mfp_address>api_test-1-6b4e@web.example.com</mfp_address>
<cabinet>1</cabinet>
<is_device_skip>0</is_device_skip>
<default_microphone_mute>0</default_microphone_mute>
<default_camera_mute>0</default_camera_mute>
</room_info>
<options>
<meeting_ssl>0</meeting_ssl>
<desktop_share>0</desktop_share>
<high_quality>0</high_quality>
<mobile_phone_number>0</mobile_phone_number>
<h323_number>0</h323_number>
<whiteboard>0</whiteboard>
<multicamera>0</multicamera>
<telephone>0</telephone>
<smartphone>0</smartphone>
<record_gw>0</record_gw>
</options>
</data>
</result>

		*/	
	}
	public function action_get_room_status($roomid){
		$path  = $this->path_prefix.'user/';
		$p = array('n2my_session','output_type');
		$cmd = 'action_get_room_status';
		$param1 = array('room_id'=>$roomid);
		$xls = $this->_connect($path,$p,$param1);
		if($xls->status){
			$json = json_encode($xls->data->room_info);
			$arr = json_decode($json,TRUE);
			return $arr;
		}
		return false;

		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<room_status>
<room_id>api_test-1-6b4e</room_id>
<meeting_id>a1cdbe823a8aa01d6578bc8dd73a37f9</meeting_id>
<pin_cd>0</pin_cd>
<status>0</status>
<pcount>0</pcount>
<participants>
<participant>
<use_count>1</use_count>
<participant_id>1</participant_id>
<participant_name>name</participant_name>
<participant_type>normal</participant_type>
</participant>
</participants>
<reservations>
<reservation>
<reservation_name>予約１</reservation_name>
<reservation_pw>0</reservation_pw>
<sender_name>test</sender_name>
<sender_email>test@example.com</sender_email>
<reservation_session>84ed77941d75eea86124de7cc7e39f54</reservation_session>
<status>wait</status>
<meeting_id>7c4454bf3fb8d87f6802528c97a8b378</meeting_id>
<reservation_start_date>1281056700</reservation_start_date>
<reservation_end_date>1281060300</reservation_end_date>
</reservation>
</reservations>
</room_status>
</data>
</result>
		*/
	}
	public function action_create($roomid){
		$path  = $this->path_prefix.'user/meeting/';
		$p = array('n2my_session','room_id','output_type');
		$cmd = 'action_create';
		$param1 = array('room_id'=>$roomid);
		$xls = $this->_connect($path,$p,$param1);
		if($xls->status){
			$json = json_encode($xls->data);
			$arr = json_decode($json,TRUE);
			return $arr;
		}
		return false;
		
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<meeting_id>39e8c7366ca89150627993c09cad2430</meeting_id>
<meeting_name />
<password_flg>1</password_flg>
<pin_cd>000000</pin_cd>
</data>
</result>
		*/
	}
	public function action_add_room_one_time_meeting(){
		$path  = $this->path_prefix.'user/meeting/';
		$p = array('n2my_session','output_type');
		$cmd = 'action_add_room_one_time_meeting';
		$param1 = array();
		$xls = $this->_connect($path,$p,$param1);
		if($xls->status){
			$json = json_encode($xls->data->room_id);
			$arr = json_decode($json,TRUE);
			return $arr;
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<room_id>test-1-1234</meeting_id>
</data>
</result>
		*/
	}
	//type
	//normal: 通常 / audience: オーディエンス　/ authority: 議長 / whiteboard: 資料共有　/ staff: スタッフ　/ customer: カスタマー
	public function action_start($roomid,$meetingid='',$type='',$name=''){
		$path  = $this->path_prefix.'user/meeting/';
		$p = array('n2my_session','lang','country','timezone','output_type');
		$cmd = 'action_start';
		//flash: as2, as3
		//screen_mode: normal: 標準(4:3) / wide： ワイド(16:9)
		//onetime_url: 1: 発行 / 0: 発行しない
		$param1 = array('room_id'=>$roomid,
										'meeting_id'=>$meetingid,
										'type'=>$type,
										'name'=>$name,
										'flash'=>'as2',
										'screen_mode'=>'normal',
										'onetime_url'=>0);
		$xls = $this->_connect($path,$p,$param1);
		if($xls->status){
			$json = json_encode($xls->data);
			$arr = json_decode($json,TRUE);
			return $arr;
		}
		return false;
		
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<url>http://meeting-try-api.nice2meet.us/services/?action_meeting_display=&amp;n2my_session=l6teg8lv9rh35iemee2cjoq7b0</url>
<meeting_id>39e8c7366ca89150627993c09cad2430</meeting_id>
<meeting_name />
<password_flg />
<need_login_flg />
</data>
</result>
		*/
	}
	public function action_knocker($meetingid,$message){
		$path  = $this->path_prefix.'user/meeting/';
		$p = array('n2my_session','meeting_id','message','output_type');
		$cmd = 'action_knocker';
		$param1 = array('meeting_id'=>$meetingid,'message'=>$message);
		$xls = $this->_connect($path,$p,$param1);
		if($xls->status){
			return true;
		}
		return false;
		
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data />
</result>

		*/
	}
	public function action_stop($roomid){
		$path  = $this->path_prefix.'user/meeting/';
		$p = array('n2my_session','room_id','output_type');
		$cmd = 'action_stop';
		$param1 = array('room_id'=>$roomid);
		$xls = $this->_connect($path,$p,$param1);
		if($xls->status){
			$json = json_encode($xls->data);
			$arr = json_decode($json,TRUE);
			return $arr;
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<meeting_name />
<meeting_size_used>0</meeting_size_used>
<is_locked>0</is_locked>
<is_reserved>0</is_reserved>
<is_publish>0</is_publish>
<meeting_use_minute>0</meeting_use_minute>
<eco_move>0</eco_move>
<eco_time>0</eco_time>
<eco_fare>0</eco_fare>
<eco_co2>0</eco_co2>
<meeting_start_date />
<meeting_end_date />
<is_recorded_minutes>0</is_recorded_minutes>
<is_recorded_video>0</is_recorded_video>
<room_id>api_test-1-6b4e</room_id>
<meeting_id>39e8c7366ca89150627993c09cad2430</meeting_id>
</data>
</result>
		*/
	}
	public function action_force_stop($roomid,$admin_pw){
		$path  = $this->path_prefix.'user/meeting/';
		$p = array('n2my_session','room_id','admin_pw','output_type');
		$cmd = 'action_force_stop';
		$param1 = array('room_id'=>$roomid,'admin_pw'=>$admin_pw);
		$xls = $this->_connect($path,$p,$param1);
		if($xls->status){
			$json = json_encode($xls->data);
			$arr = json_decode($json,TRUE);
			return $arr;
		}
		return false;

		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<room_id>aero-6-f597</room_id>
<meeting_id>ad06900d281ac37c0a212d5066069f57</meeting_id>
<meeting_name />
<meeting_use_minute>0</meeting_use_minute>
<meeting_start_date />
<meeting_end_date />
</data>
</result>
		*/
	}
	public function action_send_invitation_mail($meetingid,$email_address){
		$path  = $this->path_prefix.'user/meeting/';
		$p = array('n2my_session','meeting_id','user_type','email_address','output_type');
		$cmd = 'action_send_invitation_mail';
		$param1 = array('meeting_id'=>$meetingid,'user_type'=>'normal','email_address'=>$email_address);
		$xls = $this->_connect($path,$p,$param1);
		if($xls->status){
			$json = json_encode($xls->data);
			$arr = json_decode($json,TRUE);
			return $arr;
		}
		return false;

		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<meeting_id>39e8c7366ca89150627993c09cad2430</meeting_id>
<email_address>test@vcube.co.jp</email_address>
<invitation_url>http://$domain_name/g/ja/84d9739b1638e86dd883946ea9c4d6fd/e4317dd16fc042a60b5705c46c4aa672</invitation_url>
</data>
</result>
		*/
	}
	public function action_get_invite_url($meetingid){
		$path  = $this->path_prefix.'user/meeting/';
		$p = array('n2my_session','meeting_id','output_type');
		$cmd = 'action_get_invite_url';
		$param1 = array('meeting_id'=>$meetingid);
		$xls = $this->_connect($path,$p,$param1);
		if($xls->status){
			$json = json_encode($xls->data);
			$arr = json_decode($json,TRUE);
			return $arr;
		}
		return false;

		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<meeting_id>39e8c7366ca89150627993c09cad2430</meeting_id>
<user_invite_url>https://domain_name/g/ja/6a74a042a0ecc83ae29a6fddad1bb6b5/7571477fddad9d19e6efae03a6a067be</user_invite_url>
<audience_invite_url>https://domain_name/g/ja/6a74a042a0ecc83ae29a6fddad1bb6b5/7571477fddad9d19e6efae03a6a067be</audience_invite_url>
<authority_invite_url>https://domain_name/g/ja/6a74a042a0ecc83ae29a6fddad1bb6b5/7571477fddad9d19e6efae03a6a067be</authority_invite_url>
</data>
</result>
		*/
	}
	//find out which reservations set by this site.
	public function action_get_list($roomid,$start_limit,$end_limit,$uid=0,$limit=0,$page=0,$sort_key='',$sort_type=''){
		global $db;
		$hash = array();
		$hash1 = array();
		$where_str = '';
		
		if(!empty($uid)){
			$where_str = sprintf(' and a.u_id=%u',$uid);
		}
		
		$vcube_meetings_calendar = new vcube_meetings_calendar($db);
		$data = $vcube_meetings_calendar->getList('',0,0,"timestampdiff('m',vmc_start,now())<43200".$where_str);
		foreach($data['result'] as $row){
			$timestamp = strtotime($row['vmc_start']);
			$hash[$timestamp] = $row;
			$hash1[$row['vmc_reservationid']] = $row;
		}
		$path  = $this->path_prefix.'user/reservation/';
		$p = array('n2my_session','room_id','start_limit','end_limit','limit','page','sort_key','sort_type','output_type');
		$cmd = 'action_get_list';
		$param1 = array('room_id'=>$roomid,
										'start_limit'=>$start_limit,
										'end_limit'=>$end_limit,
										'limit'=>$limit,
										'page'=>$page,
										'sort_key'=>$sort_key,
										'sort_type'=>$sort_type);
		$arr = $this->_connect($cmd,$path,$p,$param1);

		if(isset($arr['data']['reservations']['reservation']['reservation_name'])){
		  $tmparr = array_merge($arr['data']['reservations']['reservation']);
		  unset($arr['data']['reservations']['reservation']);
		  $arr['data']['reservations'] = array('reservation'=>array());
		  $arr['data']['reservations']['reservation'][0] = array();
		  $arr['data']['reservations']['reservation'][0] = $tmparr;
		}
		//arrival time
		for($i=0;$i<count($arr['data']['reservations']['reservation']);$i++){
			$arr['data']['reservations']['reservation'][$i]['in'] = 0;
			$timestamp = intval($arr['data']['reservations']['reservation'][$i]['reservation_start_date']);
			$reservationid = $arr['data']['reservations']['reservation'][$i]['reservation_id'];
			$arr['data']['reservations']['reservation'][$i]['group'] = $hash1[$reservationid]['group'];
			$arr['data']['reservations']['reservation'][$i]['manager'] = array('u_id'=>$hash1[$reservationid]['u_id'],
																																					'u_name'=>$hash1[$reservationid]['u_name'],
																																					'u_cname'=>$hash1[$reservationid]['u_cname']);
			if(array_key_exists($timestamp,$hash)){
				if($timestamp>time()){
					//coming meeting
					$arr['data']['reservations']['reservation'][$i]['in'] = 1;
				}else{
					//expired meeting
					$arr['data']['reservations']['reservation'][$i]['in'] = 2;
				}
			}
			//get link ready 10 mins before class begin
			if(intval($arr['data']['reservations']['reservation'][$i]['reservation_start_date']) < (time()+600) &&
				intval($arr['data']['reservations']['reservation'][$i]['reservation_end_date'])>time()){
				$vcube_meetings_calendar = new vcube_meetings_calendar($db);
				$data = $vcube_meetings_calendar->getByReservationID($reservationid);
				$arr['data']['reservations']['reservation'][$i]['url'] = $data['vmc_url'];
				$arr['data']['reservations']['reservation'][$i]['uid'] = $data['u_id'];
			}
		}
		if($arr['status']){
			return $arr['data'];
		}else{
			$this->action_login();
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<count>0</count>
<reservations>
<reservation />
</reservations>
</data>
</result>
		*/
	}
	public function action_get_detail($reservationid,$password){
		$path  = $this->path_prefix.'user/reservation/';
		$p = array('n2my_session','reservation_id','password');
		$cmd = 'action_get_detail';
		$param1 = array('reservation_id'=>$reservationid,
										'password'=>$password);
		$xls = $this->_connect($path,$p,$param1);
		if($xls->status){
			$json = json_encode($xls->data);
			$arr = json_decode($json,TRUE);
			return $arr;
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<reservation_info>
<info>
<reservation_name>テストです</reservation_name>
<reservation_pw>0</reservation_pw>
<status>end</status>
<sender>sample</sender>
<sender_mail>sample@vcube.co.jp</sender_mail>
<mail_body>body</mail_body>
<room_id>api_test-1-6b4e</room_id>
<meeting_id>dd7f7b2a353b5120355c554d19743ce7</meeting_id>
<reservation_id>ac22e77158085dac44959a4675fe4df9</reservation_id>
<reservation_start_date>1257786000</reservation_start_date>
<reservation_end_date>1257786060</reservation_end_date>
<url>http://meeting-try-api.nice2meet.us/y/ITr/Xh3ihFjw7/zd1Nxf0xQvQmfg10zdulKMPZpejDv00R5INq0ppUpiGN2BAFuT</url>
</info>
<presenter>
<name>authority</name>
<email>authority@vcube.co.jp</email>
<timezone>9</timezone>
<lang>ja</lang>
<presenter_url>http://meeting-try-api.nice2meet.us/r/87f37b516a81f78d1ff9ac92&c=&lang=en</presenter_url>
</presenter>
<guests>
<guest>
<name>sample</name>
<email>sample@vcube.co.jp</email>
<timezone>100</timezone>
<lang>en</lang>
<type />
<guest_id>87f37b516a81f78d1ff9ac92</guest_id>
<invite_url>http://meeting-try-api.nice2meet.us/r/87f37b516a81f78d1ff9ac92&c=&lang=en</invite_url>
</guest>
</guests>
<documents>
<document />
</documents>
</reservation_info>
</data>
</result>
		*/
	}
	public function action_add($roomid,$name,$start,$end,$sender_email,$presenter_flag=0,$presenter_name='',$presenter_email=''){
		global $ee;
		$path  = $this->path_prefix.'user/reservation/';
/*
		$p = array('n2my_session','room_id','name','start','end','password','password_type','sender_email','is_reminder','mail_type','sender_name','send_email','info',
							'presenter_flag','presenter[name]','presenter[email]','presenter[timezone]','presenter[lang]',
							'guest[0][name]','guest[0][email]','guest[0][type]','guest[0][timezone]','guest[0][lang]',
							'organizer_flag','organizer[name]','organizer[email]','organizer[timezone]','organizer[lang]',
							'is_desktop_share','is_invite','is_rec','is_convert_web_to_pdf','is_cabinet','output_type');
*/
		$p = array('n2my_session','room_id','name','start','end','sender_email','presenter_flag',
							'presenter[name]','presenter[email]','presenter[timezone]','presenter[lang]',
							'organizer_flag','output_type');
		$cmd = 'action_add';
		$param1 = array('room_id'=>$roomid,
										'name'=>$name,
										'start'=>$start,
										'end'=>$end,
										'sender_email'=>$sender_email,
										'presenter_flag'=>$presenter_flag,
										'presenter[name]'=>$presenter_name,
										'presenter[email]'=>$presenter_email,
										'organizer_flag'=>0);
		$arr = $this->_connect($cmd,$path,$p,$param1);
		if($arr['status']){
			return $arr['data'];
		}else{
			$ee->Error('418.'.$arr['error_info']['error_cd']);
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<reservation_id>f327998e82dc9fa496889c79c80e5d1f</reservation_id>
<guests>
<invite_url>http://meeting-try-api.nice2meet.us/r/56a6db4962d684d4743bebde&amp;c=jp&amp;lang=ja</invite_url>
<guest_id>56a6db4962d684d4743bebde</guest_id>
</guests>
<guests>
<invite_url>http://meeting-try-api.nice2meet.us/r/b74749d1252c35868111a6c2&amp;c=jp&amp;lang=ja</invite_url>
<guest_id>b74749d1252c35868111a6c2</guest_id>
</guests>
<guests>
<invite_url>http://meeting-try-api.nice2meet.us/r/a93ac0b75f621cb67b43eb3b&amp;c=jp&amp;lang=ja</invite_url>
<guest_id>a93ac0b75f621cb67b43eb3b</guest_id>
</guests>
<presenter>
<presenter_url>http://meeting-try-api.nice2meet.us/r/5a33464fa69c459a3f683546&amp;c=jp&amp;lang=ja</presenter_url>
</presenter>
</data>
</result>
		*/
	}
	public function action_update($reservationid,$roomid,$name,$start,$end,$sender_email,$presenter_flag=0,$presenter_name='',$presenter_email=''){
		$path  = $this->path_prefix.'user/reservation/';
/*
		$p = array('n2my_session','room_id','name','start','end','password','password_type','send_mail','is_reminder','mail_type','sender_name','send_email','info',
							'presenter_flag','presenter[name]','presenter[email]','presenter[timezone]','presenter[lang]',
							'guest[0][name]','guest[0][email]','guest[0][type]','guest[0][timezone]','guest[0][lang]',
							'organizer_flag','organizer[name]','organizer[email]','organizer[timezone]','organizer[lang]',
							'is_desktop_share','is_invite','is_rec','is_convert_web_to_pdf','is_cabinet','output_type');
*/
		$p = array('n2my_session','reservation_id','room_id','name','start','end','sender_email','presenter_flag',
							'presenter[name]','presenter[email]','presenter[timezone]','presenter[lang]',
							'organizer_flag','output_type');
		$cmd = 'action_update';
		$param1 = array('reservation_id'=>$reservationid,
										'room_id'=>$roomid,
										'name'=>$name,
										'start'=>$start,
										'end'=>$end,
										'sender_email'=>$sender_email,
										'presenter_flag'=>$presenter_flag,
										'presenter[name]'=>$presenter_name,
										'presenter[email]'=>$presenter_email,
										'organizer_flag'=>0);
		$arr = $this->_connect($cmd,$path,$p,$param1);
		if($arr['status']){
			return $arr['data'];
		}
		return false;
		/*
【※guests[n]入力なしの場合】
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data />
</result>

【※guests[n]の入力ありの場合】
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<guests>
<guest>
<invite_url>http://meeting-try-api.nice2meet.us/r/a2dece6ec6de42a8e5e56936&c=j&lang=ja</invite_url>
<guest_id>a2dece6ec6de42a8e5e56936</guest_id>
</guest>
</guests>
</data>
</result>

		*/
	}
	public function action_get_invite($reservationid){
		$path  = $this->path_prefix.'user/reservation/';
		$p = array('n2my_session','reservation_id','output_type');
		$cmd = 'action_get_invite';
		$param1 = array('reservation_id'=>$reservationid);
		$arr = $this->_connect($cmd,$path,$p,$param1);
		if($arr['status']){
			return $arr['data'];
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<guests>
<guest>
<name>sample1</name>
<email>sample@example.com</email>
<timezone>100</timezone>
<lang>ja</lang>
<type />
<invite_url>http://meeting-try-api.nice2meet.us/r/014aaf5a6d2a097cc89e9e09&amp;c=&amp;lang=ja</invite_url>
<guest_id>014aaf5a6d2a097cc89e9e09</guest_id>
</guest>
<guest>
<name>sample2</name>
<email>sample@example.com</email>
<timezone>100</timezone>
<lang>ja</lang>
<type />
<invite_url>http://meeting-try-api.nice2meet.us/r/b0ea907328a6a56ad2f9e3f5&amp;c=&amp;lang=ja</invite_url>
<guest_id>b0ea907328a6a56ad2f9e3f5</guest_id>
</guest>
<guest>
<name>sample3</name>
<email>sample@example.com</email>
<timezone>9</timezone>
<lang>ja</lang>
<type />
<invite_url>http://meeting-try-api.nice2meet.us/r/ae840a667fc855320956259d&amp;c=&amp;lang=ja</invite_url>
<guest_id>ae840a667fc855320956259d</guest_id>
</guest>
<guest>
<name>2009-08-12 17:10:33[招待者新規]</name>
<email>sample@example.com</email>
<timezone>9</timezone>
<lang>ja</lang>
<type />
<invite_url>http://meeting-try-api.nice2meet.us/r/1ba1a1d90f4415417d83dfb7&amp;c=&amp;lang=ja</invite_url>
<guest_id>1ba1a1d90f4415417d83dfb7</guest_id>
</guest>
<guest>
<name>招待者新規1</name>
<email>sample@example.com</email>
<timezone>4</timezone>
<lang>en</lang>
<type />
<invite_url>http://meeting-try-api.nice2meet.us/r/4dae8d24f8ec91bc3348f1e5&amp;c=&amp;lang=en</invite_url>
<guest_id>4dae8d24f8ec91bc3348f1e5</guest_id>
</guest>
<guest>
<name>招待者新規2</name>
<email>sample@example.com</email>
<timezone>100</timezone>
<lang>ja</lang>
<type />
<invite_url>http://meeting-try-api.nice2meet.us/r/4ed957f301091d2120cdf855&amp;c=&amp;lang=ja</invite_url>
<guest_id>4ed957f301091d2120cdf855</guest_id>
</guest>
</guests>
</data>
</result>
		*/
	}
	public function action_add_invite($reservationid,$name,$email,$timezone='',$lang='',$type='',$send_mail=0){
		$path  = $this->path_prefix.'user/reservation/';
		$p = array('n2my_session','reservation_id','name','email','timezone','lang','type','send_mail','output_type');
		$cmd = 'action_add_invite';
		$param1 = array('reservation_id'=>$reservationid,
										'name'=>$name,
										'email'=>$email);
		$arr = $this->_connect($cmd,$path,$p,$param1);
		if($arr['status']){
			return $arr['data'];
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data>
<guests>
<guest>
<guest_id>1ba1a1d90f4415417d83dfb7</guest_id>
<invite_url>http://meeting-try-api.nice2meet.us/r/1ba1a1d90f4415417d83dfb7&amp;c=jp&amp;lang=ja</invite_url>
</guest>
</guests>
</data>
</result>
		*/
	}
	public function action_delete_invite($reservationid){
		$path  = $this->path_prefix.'user/reservation/';
		$p = array('n2my_session','reservation_id','lang','output_type');
		$cmd = 'action_delete_invite';
		$param1 = array('reservation_id'=>$reservationid);
		$arr = $this->_connect($cmd,$path,$p,$param1);
		if($arr['status']){
			return true;
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data />
</result>
		*/
	}
	public function action_delete($reservationid){
		$path  = $this->path_prefix.'user/reservation/';
		$p = array('n2my_session','reservation_id','output_type');
		$cmd = 'action_delete';
		$param1 = array('reservation_id'=>$reservationid);
		$arr = $this->_connect($cmd,$path,$p,$param1);
		if($arr['status']){
			return true;
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data />
</result>
		*/
	}

	public function action_create_meeting($roomid){
		$path  = $this->path_prefix.'user/meeting/';
		$p = array('n2my_session','room_id','output_type');
		$cmd = 'action_delete';
		$param1 = array('room_id'=>$roomid);
		$arr = $this->_connect($cmd,$path,$p,$param1);
		if($arr['status']){
			return true;
		}
		return false;
		/*
<?xml version="1.0" encoding="UTF-8"?>
<result>
<status>1</status>
<data />
</result>
		*/
	}

  function Connect($method,$cmd,$p='')
  {
    $http_request = $method." ".$this->APIPath.$cmd.".xml HTTP/1.1\r\n";
    $http_request .= "Host:".$this->domain.":".$this->port."\r\n";
    $http_request .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
    $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $http_request .= "Content-Length:".strlen($p)."\r\n\r\n"; 

    $fp = fsockopen($this->domain, $this->port, $errno, $errstr, 10);

    if (!$fp) {
      echo "$errstr ($errno)<br>\n";exit;
    }else{
      fputs ($fp, $http_request.$p);
      while (!feof($fp)) {
	      $result .= fread($fp,32000);
      }
    }
    fclose ($fp);
    //echo $http_request;
    //echo $result;

    $content = substr(strstr($result,"\r\n\r\n"),4);
    $xml = simplexml_load_string($content);
    $ErrorHandler = new ErrorHandler;
    if(empty($xml)){
    	$ee->Error('204.71');
    }elseif(!empty($xml->error)){
			$ee->Error('406.71');
    }
    return $xml;

  }
}
?>
