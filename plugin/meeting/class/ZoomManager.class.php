<?PHP
//require_once dirname(__FILE__).'/../../../init/config.php';
//$init = new init('filter','db');
//define('ZOOMAPI_KEY' , 'e1c7d76dd00ff963dba6dde27b70aafa05FQ'); //´úPI_KEY
//define('ZOOMAPI_SECRET' , '8f98a61b7e8ccf25171e6775acef8635L8NN'); //´úPI_SECRET
//define('ZOOMAPI_SERVICE_URL' , 'https://zoomnow.net/API/zntw_api.php');
//define('ZOOMAPI_USER_ID' , '2a87c1a5c126d34fc8212f61bf5518'); //´úSER_ID
//include_once 'zoom_meetings_calendar.class.php';
//include_once 'zoom_meetings_calendar_group.class.php';
//include_once 'zoom_meetings_calendar_user.class.php';
//$ZoomManager = new ZoomManager();
//$data = $ZoomManager->action_get_list();
//$data = $ZoomManager->action_update(552219231,'test1','',120);
//$data = $ZoomManager->action_add('test','',60);
//$data = $ZoomManager->action_delete(552219231);
//var_dump($data);
/**********************************************************************************
zoomapi_V1.4.pdf
The Zoomnow.net REST API allows you to manage :
■ Meeting : create , delete , get , update , list
■ User : get , list
■ Report : getdailyreport , getaccountreport , getuserreport , getaccountnotified , getalluserreport
■ Cloud Recording : get , list

define('ZOOMAPI_KEY' , 'e1c7d76dd00ff963dba6dde27b70aafa05FQ'); //測試 API_KEY
define('ZOOMAPI_SECRET' , '8f98a61b7e8ccf25171e6775acef8635L8NN'); //測試 API_SECRET
define('ZOOMAPI_SERVICE_URL' , 'https://zoomnow.net/API/zntw_api.php');
define('ZOOMAPI_USER_ID' , '2a87c1a5c126d34fc8212f61bf5518'); //測試 USER_ID
**********************************************************************************/
class ZoomManager{
	private $APIBASE = ZoomAPIBase;	//https://zoomnow.net/
	private $prefix='ZoomManager_';

	private $path_prefix = 'API/zntw_api.php';

	private $param = 
		array('API_Key'=>ZoomKey,
			'host_id'=>ZoomID,
			'timezone'=>'Asia/Taipei');
	
	function __construct(){
	}
	function __destruct(){
	}

	function getReservationListForWebadmin($start_limit,$end_limit){
		$_data = $this->action_get_list($start_limit,$end_limit,0,100);
		$j=0;
		$data=array();
		for($i=0;$i<count($_data['meetings']);$i++){
			$_start = strtotime($_data['meetings'][$i]['start_time']);
			$_duration = intval($_data['meetings'][$i]['duration']);
			$_end = date('Y-m-d H:i:s',$_start+$_duration*60);
			if($_duration>0){
				$data[$j]=array();
				$data[$j]['id'] = $_data['meetings'][$i]['id'];
				$data[$j]['title'] = $_data['meetings'][$i]['topic'];
				$data[$j]['url'] = $_data['meetings'][$i]['url'];
				$data[$j]['start'] = $_data['meetings'][$i]['start_time'];
				$data[$j]['end'] = $_end;
				$data[$j]['in'] = $_data['meetings'][$i]['in'];
				$data[$j]['className'] = sprintf('%s|%s|%s',$_data['meetings'][$i]['duration'],
									$_data['meetings'][$i]['uid'],
									$_data['meetings'][$i]['uuid']);
				$j++;
			}
		}
		return $data;
	}
	function getReservationListForManager($uid,$start_limit,$end_limit){
		$_data = $this->action_get_list($start_limit,$end_limit,$uid,100);
		$data=array();
		for($i=0;$i<count($_data['meetings']);$i++){
			$_start = strtotime($_data['meetings'][$i]['start_time']);
			$_duration = intval($_data['meetings'][$i]['duration']);
			$_end = date('Y-m-d H:i:s',$_start+$_duration*60);

			$data[$i]=array();
			$data[$i]['id'] = $_data['meetings'][$i]['id'];
			$data[$i]['title'] = $_data['meetings'][$i]['topic'];
			$data[$i]['url'] = $_data['meetings'][$i]['url'];
			$data[$i]['start'] = $_data['meetings'][$i]['start_time'];
			$data[$i]['end'] = $_end;
			$data[$i]['in'] = $_data['meetings'][$i]['in'];
			$data[$i]['className'] = sprintf('%s|%s|%s',$_duration,
																$_data['meetings'][$i]['uid'],
																$_data['meetings'][$i]['uuid']);
		}
		return $data;
	}
	function getReservationListForUser($buid,$start_limit,$end_limit){
		global $db;
		$zoom_meetings_calendar = new zoom_meetings_calendar($db);
		$_data = $zoom_meetings_calendar->getListByBUID($buid);
		$data = array();
		for($i=0;$i<count($_data['result']);$i++){
			$_duration = strtotime($_data['result'][$i]['zmc_end'])
					- strtotime($_data['result'][$i]['zmc_start']);

			$data[$i] = array();
			$data[$i]['id'] = $_data['result'][$i]['zmc_roomid'];
			$data[$i]['title'] = $_data['result'][$i]['zmc_name'];
			$data[$i]['start'] = $_data['result'][$i]['zmc_start'];
			$data[$i]['end'] = $_data['result'][$i]['zmc_end'];
			$data[$i]['in'] = 1;
			$data[$i]['className'] = sprintf('%s|%s|%s',$_duration,
								$_data['result'][$i]['u_id'],
								$_data['result'][$i]['zmc_uuid']);
			if(strtotime($_data['result'][$i]['zmc_start']) < (time()+600) &&
				strtotime($_data['result'][$i]['zmc_end'])>time()){
				$data[$i]['url'] = 'apiZ';
			}else{
				$data[$i]['url'] = '';
			}
		}
		return $data;
	}
	function addReservation($data){
		global $db;
		$lang = 'en';

		$uid = $data['u_id'];
		$account = new account($db);
		$rows = $account->getByID($uid);

		$gid = $data['g_id'];
		$name = $data['zmc_name'];
		$start = $data['zmc_start'];
		$end = $data['zmc_end'];
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
		$val = $this->action_add($name,$start,$end);
		$data['zmc_uuid'] = $val['uuid'];
		$data['zmc_roomid'] = $val['id'];
		$data['zmc_starturl'] = $val['start_url'];
		$data['zmc_joinurl'] = $val['join_url'];
		if(!empty($data['zmc_uuid'])){
			$zoom_meetings_calendar = new zoom_meetings_calendar($db);
			$zoom_meetings_calendar->insert($data);
			$data1 = array();
			foreach($data as $k => $v){
				$key = str_replace('zmc_','',$k);
				$data1[$key] = $v;
			}
			$data1['start'] = date('Y-m-d H:i:s',strtotime($data1['start']));
			$data1['end'] = date('Y-m-d H:i:s',strtotime($data1['end']));
			
			return $data1;
		}
	}
	function updateReservation($uuid,$data){
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
		$roomid = $data['zmc_roomid'];
		$name = $data['zmc_name'];
		$start = $data['zmc_start'];
		$end = $data['zmc_end'];
		$val = $this->action_update($uuid,$roomid,$name,$start,$end);
		$data['vmc_url'] = $val['presenter']['presenter_url'];
		if($val){
			$zoom_meetings_calendar = new zoom_meetings_calendar($db);
			$zoom_meetings_calendar->update($uuid,$data);
			return true;
		}
		return false;
	}
	function delReservation($reservationid){
		global $db;
		$val = $this->action_delete($reservationid);
		if($val){
			$vcube_meetings_calendar = new vcube_meetings_calendar($db);
			$vcube_meetings_calendar->del($reservationid);
			return true;
		}
		return false;
	}

	private function _connect($p,$param){
/* example
		$URL = $APIBASE."/user/?action_login=";
		$URL .= "&id=".$id;
		$URL .= "&pw=".$pwd;
		
		$URL .= "&lang=zh-cn&country=cn&timezone=8&enc=&login_type=&output_type=xml";
		$xls = simplexml_load_file($URL);
*/

		//combine p and param
		foreach($p as $k=>$v){
			if(!array_key_exists($v,$param)){
				$param[$v] = $this->param[$v];
			}
		}

		foreach($param as $k => $v)
		{
			if(trim($v)==""){
				unset($param[$k]);
			}else{
				$param[$k]=trim($v);
			}
		}
		ksort($param);
		$encode_str = "API_Key=".ZoomKey."&".urldecode(http_build_query($param))."&API_Secret=".ZoomSecret;
		$encode_str = strtolower($encode_str);
		$CheckMacValue = strtoupper(md5($encode_str));
		$param["check_value"] = $CheckMacValue;
		$param["API_Key"] = ZoomKey;
		//return $CheckMacValue;
		
		$postFields = http_build_query($param);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_URL, ZoomAPIBase);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		$errorMsg = curl_error($ch);
		curl_close($ch);
//var_dump($response,$errorMsg,$param,$postFields);
		if (!$response)
		{
			echo "false!!";
			exit();
		}
		$data=json_decode($response,TRUE);
		return $data;
	}

	//find out which reservations set by this site.
	function action_get_list($start_limit='',$end_limit='',$uid=0,$limit=0,$page=0,$sort_key='',$sort_type=''){
		global $db;
		$hash = array();
		$where_str = '';
		
		if(!empty($uid)){
			$where_str = sprintf(' and u_id=%u',$uid);
		}
		
		$zoom_meetings_calendar = new zoom_meetings_calendar($db);
		$data = $zoom_meetings_calendar->getList('',0,0,"timestampdiff('m',zmc_start,now())<43200".$where_str);
		foreach($data['result'] as $row){
			$timestamp = strtotime($row['zmc_start']);
			$hash[$timestamp] = $row;
		}
		$p = array('host_id');
		$param1 = array('api'=>'meeting_list',
				'page_size'=>100);
		$check_value='';
		$arr = $this->_connect($p,$param1);
		$tmp_arr = array($arr);
		unset($tmp_arr['data']['meetings']);
		$tmp_arr['data']['meetings'] = array();
		$j = 0;
		for($i=0;$i<count($arr['data']['meetings']);$i++){
			if(!empty($arr['data']['meetings'][$i]['start_time'])){
				$tmp_arr['data']['meetings'][$j++] = array_merge($arr['data']['meetings'][$i]);
			}
		}
		$arr = array();
		$arr = array_merge($tmp_arr);

		if(isset($arr['data']['meetings']['uuid'])){
		  $tmparr = array_merge($arr['data']['meetings']);
		  unset($arr['data']['meetings']);
		  $arr['data']['meetings'] = array();
		  $arr['data']['meetings'][0] = $tmparr;
		}
		//arrival time
		for($i=0;$i<count($arr['data']['meetings']);$i++){
			$arr['data']['meetings'][$i]['start_time'] = date('Y-m-d H:i:s',strtotime($arr['data']['meetings'][$i]['start_time']));
			$arr['data']['meetings'][$i]['in'] = 0;
			$timestamp = strtotime($arr['data']['meetings'][$i]['start_time']);
			if(array_key_exists($timestamp,$hash)){
				if($timestamp>time()){
					//coming meeting
					$arr['data']['meetings'][$i]['in'] = 1;
				}else{
					//expired meeting
					$arr['data']['meetings'][$i]['in'] = 2;
				}
			}
			//get link ready 10 mins before class begin
			if(strtotime($arr['data']['meetings'][$i]['start_time']) < (time()+600) &&
				(strtotime($arr['data']['meetings'][$i]['start_time'])+intval($arr['data']['meetings'][$i]['duration'])*60)>time()){
				$uuid = $arr['data']['meetings'][$i]['uuid'];
				//$Zoom_meetings_calendar = new Zoom_meetings_calendar($db);
				$data = $zoom_meetings_calendar->getByUUID($uuid);
				$arr['data']['meetings'][$i]['url'] = $data['zmc_starturl'];
				$arr['data']['meetings'][$i]['uid'] = $data['u_id'];
			}
		}
		if(!$arr['code']){
			return $arr['data'];
		}else{
			$ee->add('msg',$arr['msg']);
			$ee->Error('419.'.$arr['code']);
		}
		return false;
	}

	function action_get_detail($roomid,$password){
		$p = array('host_id');
		$param1 = array('api'=>'meeting_get',
										'id'=>$roomid);
		$xls = $this->_connect($p,$param1);
		if(!$arr['code']){
			return $arr['data'];
		}else{
			$ee->add('msg',$arr['message']);
			$ee->Error('419.'.$arr['code']);
		}
		return false;
	}
	function action_add($name,$start,$end){
		global $ee;
		$duration = (strtotime($end)-strtotime($start))/60;
		$start = date('c',strtotime($start));
		$p = array('host_id','timezone');
		$param1 = array('api'=>'meeting_create',
										'topic'=>$name,
										'start_time'=>$start,
										'duration'=>$duration,
										'type'=>2,
										'option_jbh'=>true,
										'option_host_video'=>true,
										'option_participants_video'=>true);
		$arr = $this->_connect($p,$param1);
		if(!$arr['code']){
			return $arr['data'];
		}else{
			$ee->add('msg',$arr['message']);
			$ee->Error('419.'.$arr['code']);
		}
		return false;
	}
	function action_update($uuid,$roomid,$name,$start,$end){
		$duration = (strtotime($end)-strtotime($start))/60;
		$start = date('c',strtotime($start));

		$p = array('host_id','timezone');
		$param1 = array('api'=>'meeting_update',
										'id'=>$uuid,
										'topic'=>$name,
										'start_time'=>$start,
										'duration'=>$duration,
										'type'=>2,
										'option_jbh'=>true,
										'option_host_video'=>true,
										'option_participants_video'=>true);
		$check_value = '';
		$arr = $this->_connect($p,$param1);
		if(!$arr['code']){
			return $arr['data'];
		}else{
			$ee->add('msg',$arr['message']);
			$ee->Error('419.'.$arr['code']);
		}
		return false;
	}
	function action_delete($roomid){
		$path  = $this->path_prefix;
		$p = array('host_id');
		$param1 = array('api'=>'meeting_delete',
										'id'=>$roomid);
		$arr = $this->_connect($p,$param1);
		if(!$arr['code']){
			return true;
		}else{
			$ee->add('msg',$arr['message']);
			$ee->Error('419.'.$arr['code']);
		}
		return false;
	}

}
?>
