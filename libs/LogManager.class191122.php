<?PHP
class LogManager{
	var $eventlog_path;
	//const updatedetail_path = ERROR_DIR.'/UpdateDetail.json';

	var $userIP;
	var $name='';

	function __construct($name){
		$init = new init('getIP');
		global $USER_IP;
		$this->userIP = $USER_IP;
		$this->name = $name;
		$this->eventlog_path = LOG_DIR.'/'.date('Ym').'.log';

	}
	function event($title,$content){
		if(!isset($this->index[$cate]))
			$this->index[$title]=0;
		if(!is_array($this->logs[$cate]))
			$this->logs[$title] = array();

		$msg = $this->message('event',$title,$content);
		file_put_contents($this->eventlog_path,$msg,FILE_APPEND);
	}
	function warning($title,$content){
		if(!isset($this->index[$cate]))
			$this->index[$title]=0;
		if(!is_array($this->logs[$cate]))
			$this->logs[$title] = array();

		$msg = $this->message('warning',$title,$content);
		file_put_contents($this->eventlog_path,$msg,FILE_APPEND);
	}
	function error($title,$content){
		if(!empty($log)){
			$msg = $this->message('error',$title,$content);
			$this->eventlog($title,$content);
			error_log($msg);
		}
	}
	function message($status,$title,$content){
		$str .= sprintf("[%s][%s][%s][%s] %s - %s\r\n",
			$this->name,
			$this->userIP,
			date('Y/M/d H:m:s'),
			$status,
			$title,
			$content);
		return $str;
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
}
?>
