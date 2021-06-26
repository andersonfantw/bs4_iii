<?PHP
/************************************************************************
Tell what's happen, and what client can do.
main code: http code
http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html

10: login
20: convert
30: upload
40: program exec
50: ecocat
60: param
70: api
80: wonderbox
90: tag
100: mail

************************************************************************/
class ErrorHandler{
	var $type;
	var $arr=array();
	var $http_status_codes = array(
		100 => 'Continue',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'unused',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Authorization Required',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Large',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'unused[vcube]',
		419 => 'unused[zoom]',
		420 => 'unused',
		421 => 'unused',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'No code',
		426 => 'Upgrade Required',
		500 => 'Internal Server Error',
		501 => 'Method Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Temporarily Unavailable',
		504 => 'Gateway Time-out',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'unused',
		509 => 'unused',
		510 => 'Not Extended'
	);

	var $_errormsg = array(
		'401.10'=>'Sysadmin login fail',
		'401.11'=>'Bookshelf Manager login fail',
		'401.12'=>'User login fail',
		'404.13'=>'account not found',
		'401.14'=>'account expired',
		'401.20'=>'ECOCAT ZIP upload none license',
		'401.21'=>'LBMZIP upload none license',
		'401.22'=>'ITUZIP upload none license',
		'401.23'=>'CloudConvert none license',
		'401.24'=>'Trial expired',
		'401.25'=>'Ecocat license expired',
		'401.26'=>'Bookshelf none license',
		'401.27'=>'EBK upload none license',
		'500.28'=>'Converting fail',
		'406.30'=>'File type is not allowed(System recognize)',
		'406.31'=>'Unknow format(System can\'t recognize)',
		'400.32'=>'Missing upload file',
		'400.33'=>'file type could not be obtained',
		'400.34'=>'file size could not be obtained',
		'404.35'=>'Missing file(s)',
		'400.36'=>'file is occupied',
		'500.40'=>'Insert fail',
		'500.41'=>'Update fail',
		'500.42'=>'Delete fail',
		'500.43'=>'Program execute fail',
		'500.44'=>'Invalid cookie value',
		'404.45'=>'Missing category',
		'406.46'=>'Category is not a sub-category',
		'406.60'=>'Missing parameter',
		'406.61'=>'Unacceptable character',
		'406.62'=>'Bad format',
		'406.63'=>'Not permitted',
		'406.64'=>'Group is not belong to this category',
		'406.65'=>'Book is not belong to this group',
		'406.66'=>'Duplicate key',
		'401.70'=>'Invalid access token',
		'204.71'=>'Get data failed',
		'406.71'=>'Get data failed',
		'401.72'=>'Login failed',
		'401.74'=>'Token expired',
		'404.80'=>'Wonderbox not started',
		'405.1'=>'It is not allowed to convert from frontsite.',
		'404.90'=>'Missing tag',
		'406.91'=>'Incorrect format(please check file again)',
		'406.92'=>'Missing tag(s)(make sure import tags are all in system)',
		'406.93'=>'Delete failed! Cannot delete while has book reference.',
		'406.94'=>'Delete failed! Cannot delete while has children tag.',
		'406.95'=>'Has the same system tag.',
		'406.96'=>'Has the same user-define tag.',
		'406.97'=>'Has the same tag.',
		'406.10'=>'Delete failed! Cannot delete while has book reference and has children tag.',
		'406.11'=>'Delete failed! Cannot delete while has book reference.',
		'406.12'=>'Delete failed! Cannot delete while has children tag.',
		'406.13'=>'Has the same system tag.',
		'406.14'=>'Has the same user-define tag.',
		'406.15'=>'Has the same tag.',
		'406.16'=>'This email is occupied.',
		'406.17'=>'You have experienced this course.',
		'418.1'=>'Login failed / Invalid auth token',
		'418.2'=>'Method not found',
		'418.100'=>'Parameter error',
		'418.101'=>'Object not found',
		'418.1000'=>'DataBase error',
		'404.1001'=>'Missing record');

	/*
	type = none | json | header | log
	*/
	function __construct($type='json'){
		$this->type = $type;
	}
	public function add($key,$value){
		$this->arr[$key] = $value;
	}
	public function setReturn($return){
		$this->_return = $return;
	}
	// allow json | header
	public function Error($code){
		$allowecho = array('json','header');

		$data = $this->Warning($code,false);
		$this->output($data,$allowecho);
		exit;
	}
	// allow none | json | log
	public function Warning($code,$echo=true){
		$allowecho = array('none','json','log');

		$arr = explode('.',$code);
		$http_status_code = $arr[0];

		if(array_key_exists($code,$this->_errormsg)) $_msg=', '.$this->_errormsg[$code];
		if(isset($this->arr['msg'])) $_msg .= ' '.$this->arr['msg'];
		$data = array('code'=>$code,'msg'=>sprintf('[%s] %s. %s',$code, $this->http_status_codes[$http_status_code],$_msg));
		$data = array_merge($this->arr,$data);
		if($echo){
			$this->output($data,$allowecho);
		}else{
			return $data;
		}
	}
	// allow none | json | log
	public function Message($code){
		$allowecho = array('none','json','log');
		$_msg = array('200'=>'Success',
									'200.10'=>'Sysadmin login sucecess',
									'200.11'=>'Bookshelf Manager login success',
									'200.12'=>'User login success',
									'302.13'=>'account found',
									'200.20'=>'ECOCAT ZIP upload licensed',
									'200.21'=>'LBMZIP upload licensed',
									'200.22'=>'ITUZIP upload licensed',
									'200.23'=>'CloudConvert licensed',
									'200.24'=>'Trial',
									'200.25'=>'Ecocat licensed',
									'200.26'=>'Bookshelf licensed',
									'200.27'=>'EBK upload licensed',
									'200.43'=>'Success',
									'200.70'=>'API login success');

		$data = array('code'=>$code,'msg'=>$_msg[$code]);
		$data = array_merge($this->arr,$data);

		$this->output($data,$allowecho);
	}

	private function output($data,$allowecho,$type=''){
		$_type = strtolower($this->type);
		if(!empty($type)) $_type = $type;

		$arr = explode('.',$data['code']);
		$http_status_code = $arr[0];
		$typearr = explode('|',$_type);
		foreach($typearr as $t){
			switch($t){
				case 'log':
					error_log(sprintf('[%s] L-NET LOG: %s\n',date("d-m-Y H:i:s"),
						implode('  ',
							array_map(
								function($v, $k){ return $k.'='.$v; }, 
								$data, 
								array_keys($data)
							)
						)
					),0);
					break;
				case 'json':
					$json = json_encode($data);
					echo $json;
					break;
				case 'header':
					$code = $data['code'];
					$arr = explode('.',$code);
					$http_status_code = $arr[0];
			
					$msg = $this->http_status_codes[$http_status_code];
					$errmsg = $http_status_code." ".$msg;
	
				  header($_SERVER["SERVER_PROTOCOL"]." ".$errmsg);
				  header("Status: ".$errmsg);
				  $_SERVER['REDIRECT_STATUS'] = intval($arr[0]);
				  exit;
					break;
				case 'none':
				default:
					break;
			}
		}
	}

}
?>
