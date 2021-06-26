<?PHP
// be aware this class content fix path
class	Manifest{
	var $filename = '/lnet.appcache';
	var $enable = true;
	var $valid = false;
	var $version = '';
	var $update = '';
	var $chkcode = '';
	var $allData = array();
	var $excludeDirs = array('.','..');
	var $includeFiles = array('png','jpg','jpeg','bmp','html','htm','css','js','mp3','mp4','ogg','swf','xml','csv');
	var $excludeFiles = array('php','cfg','dir','gitkeep','DS_Store','md','');

	function __construct($_version){
		$this->version = $_version;
		$this->filename = CACHE_PATH.$this->filename;
		$this->valid = $this->_chkVersion();
	}
	
	public function enable($_enable){
		$this->enable = $_enable;
	}
	
	public function setVersion($_version){
		$this->version = $_version;
	}

	function scanDirectory($rootDir,$pattern='') {
		if(!$this->valid){
	    $dirContent = scandir(ROOT_PATH.$rootDir);
	    foreach($dirContent as $key => $content) {
	      $path = ROOT_PATH.$rootDir.'/'.$content;
	      $rpath = $rootDir.'/'.$content;
	      $path_parts = pathinfo($path);
	      $valid = true;
	      if(!empty($pattern)){
	      	$valid = preg_match($pattern,$path,$matches);
	      }
	      if(is_file($path) && is_readable($path) && in_array($path_parts['extension'],$this->includeFiles) && $valid) {
	          $this->allData[] = $rpath;
	      }else if(is_dir($path) && !in_array($content,$this->excludeDirs)){
	      	$this->scanDirectory($rpath,$pattern);
	      }
	    }
	  }
	}

	public function output(){
		if(!$this->enable){
			echo sprintf("CACHE MANIFEST\n# default version, by Anderson\nSETTINGS:\nprefer-online\nNETWORK:\n*",time());exit;
		}
		if($this->valid){
			$handle = @fopen($this->filename, "r");
			while (($buffer = fgets($handle, 4096)) !== false) {
				echo $buffer;
			}
			fclose($handle);
		}else{
			//make new manifest file
			$chkcode = md5(serialize($this->allData));
			$handle = @fopen($this->filename, "w");
			fwrite($handle,"CACHE MANIFEST\n");
			fwrite($handle,sprintf("# VERSION %s\n",$this->version));
			fwrite($handle,sprintf("# %s\n",$chkcode));
			foreach($this->allData as $f){
				fwrite($handle,$f."\n");
			}
			fwrite($handle,"NETWORK:\n*");
			fclose($handle);
			echo sprintf("CACHE MANIFEST\n# VERSION %s\n# %s\n",$this->version,$chkcode);
			foreach($this->allData as $f){
				echo substr($f,strlen(ROOT_PATH))."\n";
			}
			echo"NETWORK:\n*";
		}
	}

	//true: need update, false: current file is the newest
	private function _chkVersion(){
		$handle = @fopen($this->filename, "r");
		if($handle){
			$buffer = fgets($handle, 50);
			$buffer = fgets($handle, 50);
			$this->update = substr($buffer,9);
			$buffer = fgets($handle, 50);
			$this->chkcode = $buffer;
			fclose($handle);
			return (intval($this->version)==intval($this->update));
		}else{
			return false;
		}
	}
}
?>
