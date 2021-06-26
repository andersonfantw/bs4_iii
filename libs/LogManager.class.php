<?PHP
class LogManager{
	var $eventlog_path;
	//const updatedetail_path = ERROR_DIR.'/UpdateDetail.json';

	var $user;
	var $userIP;
	var $name='sysbs';	//default bs log name
	var $doneChkDataChange=false;
	var $_setting = array(
		'logrotatate'=>false,
		'logErrorLevel'=>array('event'=>true,'warning'=>true,'error'=>true),
		'sendMailErrorLevel'=>array('event'=>false,'warning'=>false,'error'=>true)
	);
	var $_error_reporting = 0;
	var $_file = '';
	var $_data = '';
	var $_mailtitle = '';
	var $_arrCompare = array();

	function setLogRotate($_bool){
		$this->_setting['logrotate'] = (bool)$_bool;
		if($this->_setting['logrotate']){
			$filename = LOG_DIR.$this->name;
			if(!file_exists($filename)) mkdir($filename, 0777);
			$this->eventlog_path = LOG_DIR.sprintf('%s/%s_%s.log',$this->name,$this->name,date('Ym'));
		}else{
			$this->eventlog_path = LOG_DIR.sprintf('%s.log',$this->name);
		}		
	}

	function setData($_data){
		$this->_data = $_data;
	}
	function setNewData($_data){
		$this->_newdata = $_data;
	}
	function setMailTitle($_title){
		$this->_mailtitle = $_title;
	}
	function setLogErrorLevel($errorlevels){
		$this->_setting['logErrorLevel']['event'] = (($errorlevels & E_All)==E_ALL) || (($errorlevels & E_NOTICE)==E_NOTICE);
		$this->_setting['logErrorLevel']['warning'] = (($errorlevels & E_All)==E_ALL) || (($errorlevels & E_WARNING)==E_WARNING);
		$this->_setting['logErrorLevel']['error'] = (($errorlevels & E_All)==E_ALL) || (($errorlevels & E_ERROR)==E_ERROR);
	}
	function setMailErrorLevel($errorlevels){
		$this->_setting['sendMailErrorLevel']['event'] = (($errorlevels & E_All)==E_ALL) || (($errorlevels & E_NOTICE)==E_NOTICE);
		$this->_setting['sendMailErrorLevel']['warning'] = (($errorlevels & E_All)==E_ALL) || (($errorlevels & E_WARNING)==E_WARNING);
		$this->_setting['sendMailErrorLevel']['error'] = (($errorlevels & E_All)==E_ALL) || (($errorlevels & E_ERROR)==E_ERROR);
	}

	function __construct($_file,$name='sysbs'){
		$init = new init('getIP');
		global $USER_IP;
		$this->user = $this->_getCurrentUser();
		$this->userIP = $USER_IP;
		$this->name = $name;
		$this->_file = str_replace(ROOT_PATH,'',$_file);
		$this->setLogRotate($this->_setting['logrotate']);
		$this->_setting['logErrorLevel']['event'] = ((error_reporting() & E_All)==E_ALL) || ((error_reporting() & E_NOTICE)==E_NOTICE);
		$this->_setting['logErrorLevel']['warning'] = ((error_reporting() & E_All)==E_ALL) || ((error_reporting() & E_WARNING)==E_WARNING);
		$this->_setting['logErrorLevel']['error'] = ((error_reporting() & E_All)==E_ALL) || ((error_reporting() & E_ERROR)==E_ERROR);
	}
	function event($title,$content,$forceLog=false,$forceMail=false){
		if($this->_newdata && !$this->_hasDataChange()) return;
		$_title = sprintf("[%s] %s event by %s: %s", date('Y-m-d H:i:s'), $this->name, $this->user, $title);
		$msg = $this->_message('event',$_title,$content);
		if($this->_setting['logErrorLevel']['event'] || $forceLog){
			$result = file_put_contents($this->eventlog_path,$msg,FILE_APPEND);
		}
		if($this->_setting['sendMailErrorLevel']['event'] || $forceMail){
			$_title = ($this->_mailtitle) ? $this->_mailtitle:$_title;
			$this->_sendMail('event',$_title,$content);
		}
		if($result===false){
			$this->_wiriteLogError($msg);
		}
	}
	function warning($title,$content,$forceLog=false,$forceMail=false){
		if($this->_newdata && !$this->_hasDataChange()) return;
		$_title = sprintf("[%s] %s warning by %s: %s", date('Y-m-d H:i:s'), $this->name, $this->user, $title);
		$msg = $this->_message('warning',$_title,$content);
		if($this->_setting['logErrorLevel']['warning'] || $forceLog){
			$result = file_put_contents($this->eventlog_path,$msg,FILE_APPEND);
		}
		if($this->_setting['sendMailErrorLevel']['warning'] || $forceMail){
			$_title = ($this->_mailtitle) ? $this->_mailtitle:$_title;
			$this->_sendMail('warning',$_title,$content);
		}
		if($result===false){
			$this->_wiriteLogError($content);
		}
	}
	function error($title,$content,$forceLog=false,$forceMail=false){
		if($this->_newdata && !$this->_hasDataChange()) return;
		$_title = sprintf("[%s] %s error by %s: %s", date('Y-m-d H:i:s'), $this->name, $this->user, $title);
		$msg = $this->_message('error',$_title,$content);
		if($this->_setting['logErrorLevel']['error'] || $forceLog){
			$result = file_put_contents($this->eventlog_path,$msg,FILE_APPEND);
		}
		if($this->_setting['sendMailErrorLevel']['error'] || $forceMail){
			$_title = ($this->_mailtitle) ? $this->_mailtitle:$_title;
			$this->_sendMail('error',$_title,$content);
		}
		if($result===false){
			$this->_wiriteLogError($content);
		}
	}
	private function _getCurrentUser(){
		$_user = 'system';
		if(bssystem::getSysLoginName()!=''){
			$_user = 'sysadmin:'.bssystem::getSysLoginName();
		}elseif(bssystem::getLoginUName()!=''){
			$_user = 'admin:'.bssystem::getLoginUName();
		}elseif(bssystem::getLoginBUName()!=''){
			$_user = 'user:'.bssystem::getLoginBUName();
		}
		return $_user;
	}
	private function _message($status,$title,$content){
		$str = sprintf("%s, %s\n", $title,	$content);
		if(!empty($this->_arrCompare)){
			$str .= sprintf("db data difference: %s\n",$this->_toString($this->_arrCompare));
		}elseif(!empty($this->_data)){
			$str .= sprintf("db data: %s\n",$this->_toString($this->_data));
		}
		return $str;
	}
	private function _toString($arr){
		$result = array();
		foreach($arr as $k=>$v){
			$result[] = sprintf("%s=%s",$k,$v);
		}
		return implode(' , ',$result);
	}
	private function _chkDataChange(){
		if(!empty($this->_data) && !empty($this->_newdata)){
			foreach($this->_newdata as $k=>$v){
				if(array_key_exists($k,$this->_data)){
					if($this->_data[$k]!=$v){
						$this->_arrCompare[$k] = sprintf('編輯欄位 %s 由 "%s" => "%s"',$k,$this->_data[$k],$v);
					}
				}
			}
			$this->doneChkDataChange=true;
		}
	}
	private function _hasDataChange(){
		if(!$this->doneChkDataChange) $this->_chkDataChange();
		return (!empty($this->_arrCompare));
	}
	private function _sendMail($status,$title,$content){
		$_subject = sprintf('[%s] %s %s by %s: %s',date('Y-m-d H:i:s'),$this->name,$status,$this->user,$title);
		$_content =<<<LOG
時間: %s
主機: %s
執行程式: %s
記錄檔資訊:
%s
LOG;
		$_content = sprintf($_content,
									date('Y-m-d H:i:s'),
									$_SERVER['SERVER_ADDR'],
									$this->_file,
									$content);
		$MailManager = new MailManager();
		$MailManager->setReplyTo(UPLOADQUEUE_ERROR_APPLYTO1);
		$MailManager->setReplyTo(UPLOADQUEUE_ERROR_APPLYTO2);
		$MailManager->setSubject($_subject);
		$MailManager->setContent($_content);
		$MailManager->send();
	}
	private function _wiriteLogError($content){
		$_content=<<<CONTENT
執行 %s 時寫入日誌檔 %s 發生錯誤

以下是未寫入到日誌檔的內容:
%s
CONTENT;
		$_content=sprintf($_content,$this->_file,$this->eventlog_path,$content);
		$this->_sendMail('error','寫入記錄檔發生錯誤',$_content);
	}

	function save(){
		file_put_contents(updatedetail_file,json_encode($this->_updatedetail));
		$this->clear();
	}
	function clear(){
		$this->_eventlog='';
		$this->_updatedetail='';
		$this->_errorlog='';
	}
	//action =
	//	add : id
	//	edit : id, changed record data.
	// delete : id, all record data.
	function actionlog($_type='',$_orgdata=array(),$_newdata=array()){
		global $ee;
		global $db;
		$action='';

		$action_log = new action_log(&$db);
		$_page = basename($_SERVER['PHP_SELF']);

		$type = $fs->valid($_GET['type'],'cmd');
		$_action = ($_type=='')?$type:$_type;
		switch($_action){
			case 'delete':
				//action
				$action = 'delete';
				break;
			default:
				if(substr($_action,0,3)=='do_'){
					//action
					$action = $_action;
				}else{
					return;
				}
				break;
		}
		if(!is_array($_orgdata)){
			$ee->Error();
		}
		if(!is_array($_newdata)){
			$ee->Error();
		}
		if($_orgdata!='' && $_newdata!=''){
			foreach($_newdata as $col){
				if(array_key_exists($col,$_orgdata)){
					$_desc[$col] = $_orgdata[$col];
				}
				$_desc[$col] .= sprintf('@=@%s',$_newdata[$col]);
			}
		}elseif($_orgdata!=''){
			foreach($_orgdata as $col){
				$_desc[$col] = $_orgdata[$col];
			}
		}
		if(!empty($action)){
			$data =array(
				'CREATE_DATE'=>date('Y-m-d H:i:s'),
				'UID'=>$uid,
				'USERTYPE'=>'a',
				'page'=>$_page,
				'ACTION'=>$action,
				'DESCRIPTION'=>json_encode($_desc),
				'CLIENT_IP'=>$USER_IP,
				'SESSION_ID'=> session_id()
			);
		}
	}
}
?>
