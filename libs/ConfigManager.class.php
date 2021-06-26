<?PHP
class ConfigManager{
	var $css='';
	var $uid=0;
	var $bsid=0;
	public function __construct ($uid=0,$bsid=0){
		if(!empty($bsid)){
			$this->bsid = (int)$bsid;
		}else{
			$this->bsid = bssystem::getBSID();
		}

		if(!empty($uid)){
			$this->uid = (int)$uid;
		}else{
			$this->uid = bssystem::getUID();
		}
//var_dump($this->bsid,$this->uid);
	}

	public function unSetCSS(){
		$this->css='';
	}


	public function getCSSSyspath(){
		return HOST_PATH.'/config.css';;
	}

	public function getJSONSyspath(){
		return HOST_PATH.'/config.json.php';
	}

	public function getDefineSyspath(){
		return HOST_PATH.'/bs_config.php';
	}

	public function getDefineSysConfigpath(){
		return HOST_PATH.'/sys_config.php';
	}

	public function getCSSUserbase(){
		return $this->_pathvalid(HostManager::getBookshelfBase(false,true,$this->uid,$this->bsid).'/config.css');
	}

	public function getJSONUserbase(){
		return $this->_pathvalid(HostManager::getBookshelfBase(false,true,$this->uid,$this->bsid).'/config.json.php');
	}

	public function getDefineUserbase(){
		return $this->_pathvalid(HostManager::getBookshelfBase(false,true,$this->uid,$this->bsid).'/bs_config.php');
	}

	public function getDefineUserConfigbase(){
		return $this->_pathvalid(HostManager::getBookshelfBase(false,true,$this->uid,$this->bsid).'/sys_config.php');
	}

	private function _pathvalid($path){
		if(file_exists($path)){
			return $path;
		}else{
			return null;
		}
	}

	public function SetCSS($type,$data){
		$template = array();
		$template['height'] = 'height:%upx !important;';
		$template['image'] = 'background-image:url(%s) !important;';

		$header=<<<CSS
#header{
{height}
{image}
}

CSS;

		$footer=<<<CSS
#footer{
background-color: #dcdcdc !important;
font-family: ·L³n¥¿¶ÂÅé !important;
font-size: 14px !important;
{height}
{image}
}

CSS;

		$f_isset=0;
		$content = $$type;
		foreach($template as $key=>$value){
			$str = '';
			if(!empty($data[$key])){
				$f_isset=1;
				$str = sprintf($template[$key],$data[$key]);
			}
			$content=str_replace('{'.$key.'}',$str,$content);
		}
		if(!$f_isset) $content = '';
		$this->css .= $content;
	}

	public function SaveCSS($path){
		switch($path){
		  	case 'userbase':
	  			$path = self::getCSSUserbase();
	  		break;
		  	case 'sys':
		  		$path = self::getCSSSyspath();
	  			break;
		}

		if(empty($this->css)){
			if(is_file($path)) @unlink($path);
		}else{
			file_put_contents($path,$this->css);
		}
		$this->unSetCSS();
	}

	public function SaveDefine($path,$data,$check_data=null){
		$content="<?PHP\r\n";
		if(!empty($check_data)) $check_data = array_keys($check_data);
		foreach($data as $key=>$value){
			if(!empty($value)  || $value===0){
				if(!is_numeric($value)){
					$value = "'".$value."'";
				}
				if(is_null($check_data)){
					$content .= sprintf("define('%s',%s);\r\n",$key,nl2br($value));
				}else{
					if(in_array($key,$check_data)){
						$content .= sprintf("if(!defined('%s')) define('%s',%s);\r\n",$key,$key,nl2br($value));
					}else{
						$content .= sprintf("define('%s',%s);\r\n",$key,nl2br($value));
					}
				}
			}else{
				$content .= sprintf("if(!defined(%s)) define('%s','%s');\r\n",$key,$key,nl2br($value));
      }
		}
		$content .= "?>";

		switch($path){
			case 'userbase':
				$path = self::getDefineUserbase();
				break;
			case 'userconfig':
				$path = self::getDefineUserConfigbase();
				break;
			case 'sys':
				$path = self::getDefineSyspath();
				break;
			case 'sysconfig':
				$path = self::getDefineSysConfigpath();
				break;
		}
		file_put_contents($path,$content);
	}

	public function SaveJSON($path,$data){
	  $output = $json = new Services_JSON();
	  header('Content-Type: application/json; charset=utf-8');
	  foreach($data as $key=>$value){
	  	$data[$key] = str_replace("\r\n","\\n",$value);
	  }
	  $content = $json->encode($data);

	  switch($path){
	  	case 'userbase':
	  		$path = self::getJSONUserbase();
	  		break;
	  	case 'sys':
	  		$path = self::getJSONSyspath();
	  		break;
	  }
	  file_put_contents($path,$content);
	}
}
?>
