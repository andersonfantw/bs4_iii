<?PHP
/***********************************************************************
  these are the basic function for decode book format.
  all the decoder must extend this class.

	Avoid malicious files
	1. check zip type
		ecocat contain:
			/data/search_key.csv
			/data/search_pnt.csv
		itutor contain:
			/player/params.js
	2. only unzip expect file from data folder
	http://stackoverflow.com/questions/12195959/how-to-partially-extract-a-folder-from-a-7z-file-using-powershell
	7za x program.7z python-core-2.6.1\lib -oc:\data
	3. combine sysfile and data
	4. do the content in /data/html5/

==ecocat==
- sys
book.swf
book_swf.html
book_swf.php
help.html
index.html
/css/default.css
/html/css/style.css
/html/js/util.js
/scripts/AC_RunActiveContent.js
/scripts/send_js.js
/images/*.jpg.png.swf.mp4
/m/*

- data
/[folder]__dmx
/data/search_key.csv
/data/search_pnt.csv
/html/SearchString_Page{%4u}.html
/images/Thumbnail/page*.jpg
/xml/*.xml

- extra
/data/html5/

==itutor==
- sys
.htaccess
index.html
mediaplayer.swf
test.html
test.php
demo.html
demo.php
practice.html
practice.php
tutorial.html
tutorial.php
/index/index.css
/index/images/*.png
/player/images/*.png.gif
/player/jquery.js
/player/languages.js
/player/mediaplayer.js
/player/rlplayer.css
/player/rlplayer.js
/player/slides.js

- data
/player/params.js
/data/*.png.mp3.ogg

- extra
/data/html5/

***********************************************************************/
define('EBOOKCONVERT_DATA_PATH',PLUGIN_PATH.'/ebookconvert/data/');
//define('BSFILE_ECOCAT_SYS_FILE',PLUGIN_PATH.'/ebookconvert/data/ecocat/');
//define('BSFILE_ECOCAT211_SYS_FILE',PLUGIN_PATH.'/ebookconvert/data/ecocat211/');
//define('BSFILE_ITUTOR_SYS_FILE',PLUGIN_PATH.'/ebookconvert/data/itutor/');
//define('BSFILE_ITUTOR_SYS_MODFILE',PLUGIN_PATH.'/ebookconvert/data/itutor_mod/');

class BSFile_CODEC{
	const MultiContentFolder='/data/html5';
	const sysDataPath = '/ebookconvert/data/%s';

	var $cls;
	var $FileFormat=0;
	var $FileName='';
	var $Uniquekey='';
	var $tmpfolder='';
	var $destfolder='';
	var $hasMultiContent=false;
	
	function __construct($_time=null){
		if(!$_time) $_time = time();
		$this->Uniquekey = wonderbox_id . $_time;
		$this->tmpfolder = sys_get_temp_dir().'/'.$_time;
	}

	function __destruct(){
		//cleanup tmp folder
		$dir = $this->tmpfolder;
		common::rrmtmpdir($dir);
	}

	public function setUniquekey($_Uniquekey){
		$this->Uniquekey = $_Uniquekey;
	}

	public function getUniquekey(){
		return $this->Uniquekey;
	}

	public function getFormat(){
		if(empty($this->FileFormat)){
			echo 'Please run CheckFormat to get format!';exit;
		}
		return $this->FileFormat;
	}

	public function CheckFormat($file){
		if(is_file($file)){
			$path_parts = common::path_info($file);
			switch($path_parts['extension']){
				case 'zip':
					$result = common::unzip($file,$this->tmpfolder);
					if(!$result){
						return ConvertModeEnum::UnknowZIP;
					}
					$dh = opendir($this->tmpfolder);
					$exclude = array('.','..');
		    	$foldernum=0;
		    	$folder='';
					while (($file = readdir($dh)) !== false) {
						if(!in_array($file,$exclude) && (filetype($this->tmpfolder.'/'.$file)=='dir')){
							$foldernum++;
							$folder = $file;
						}
					}
					closedir($dh);

					if($foldernum==1){
						$this->tmpfolder = $this->tmpfolder.'/'.$folder;
					}

					//check type: itutor, ecocat/lbm
					$this->FileFormat = $this->_CheckFormat($this->tmpfolder,true);
				  	if(file_exists($this->tmpfolder.self::MultiContentFolder)){
			  			$this->hasMultiContent=true;
				  	}
				  	return $this->FileFormat;
					break;
				case 'itu':
					//rename itu to zip
					$path_parts = common::path_info($file);
					$zippath = $this->tmpfolder.'/'.$path_parts['filename'].'.zip';
					rename($file,$zippath);
					//return 
					return $this->CheckFormat($zippath);
					break;
				case 'ebk':
					break;
			}
		}elseif(is_dir($file)){
			//should be ecocat
			$this->tmpfolder = $file; 
			$this->FileFormat = $this->_CheckFormat($this->tmpfolder,true);
			if(file_exists($this->tmpfolder.self::MultiContentFolder)){
	  		$this->hasMultiContent=true;
		  }
		  return $this->FileFormat;
		}
	}

	private function _CheckFormat($folderpath,$root=false){
		$Codecs = array('EcocatCMS304','EcocatCMS211','EcocatCMS','EcocatZIP304','EcocatZIP211','EcocatZIP','ItutorZIP600','ItutorZIP500','ItutorZIP','FlipbuilderZIP100','MCG');
		foreach($Codecs as $CodecName){
			$classname = $CodecName.'_CODEC';
			$cls = new $classname;
			if($root) $this->cls = $cls;
			$format = $cls->CheckFormat($folderpath);
			if($format) return $format;
		}
	}

	private function _decode($path,$format){
		$userpath = $this->destfolder.$path;
		$this->cls->preDecode();
		switch($format){
			case ConvertModeEnum::ECOCAT_ZIP:
				$files = EcocatZIP_CODEC::getDataFiles();
				$dirs = EcocatZIP_CODEC::getDataFolders();
				$name = EcocatZIP_CODEC::Name;
				if(ENABLE_DECENTRALIZED) $name .= '_dec';
				$sysfile = sprintf(PLUGIN_PATH.self::sysDataPath,$name);
				break;
			case ConvertModeEnum::ECOCAT211_ZIP:
				$files = EcocatZIP211_CODEC::getDataFiles();
				$dirs = EcocatZIP211_CODEC::getDataFolders();
				$name = EcocatZIP211_CODEC::Name;
				if(ENABLE_DECENTRALIZED) $name .= '_dec';
				$sysfile = sprintf(PLUGIN_PATH.self::sysDataPath,$name);
				break;
			case ConvertModeEnum::ECOCAT304_ZIP:
				$files = EcocatZIP304_CODEC::getDataFiles();
				$dirs = EcocatZIP304_CODEC::getDataFolders();
				$name = EcocatZIP304_CODEC::Name;
				$name .= EcocatZIP304_CODEC::Html5Version;
				if(ENABLE_DECENTRALIZED) $name .= '_dec';
				$sysfile = sprintf(PLUGIN_PATH.self::sysDataPath,$name);
				break;
			case ConvertModeEnum::ITUTOR600_ZIP:
				$files = ItutorZIP600_CODEC::getDataFiles();
				$dirs = ItutorZIP600_CODEC::getDataFolders();
				$name = ItutorZIP600_CODEC::Name;
				$sysfile = sprintf(PLUGIN_PATH.self::sysDataPath,$name);
				break;
			case ConvertModeEnum::ITUTOR500_ZIP:
				$files = ItutorZIP500_CODEC::getDataFiles();
				$dirs = ItutorZIP500_CODEC::getDataFolders();
				$name = ItutorZIP500_CODEC::Name;
				$sysfile = sprintf(PLUGIN_PATH.self::sysDataPath,$name);
				break;
			case ConvertModeEnum::ITUTOR_ZIP:
				$files = ItutorZIP_CODEC::getDataFiles();
				$dirs = ItutorZIP_CODEC::getDataFolders();
				$name = ItutorZIP_CODEC::Name;
				$sysfile = sprintf(PLUGIN_PATH.self::sysDataPath,$name);
				break;
			case ConvertModeEnum::ECOCATCMS:
				$files = EcocatCMS_CODEC::getDataFiles();
				$dirs = EcocatCMS_CODEC::getDataFolders();
				$name = EcocatCMS_CODEC::Name;
				if(ENABLE_DECENTRALIZED) $name .= '_dec';
				$sysfile = sprintf(PLUGIN_PATH.self::sysDataPath,$name);
				break;
			case ConvertModeEnum::ECOCATCMS211:
				$files = EcocatCMS211_CODEC::getDataFiles();
				$dirs = EcocatCMS211_CODEC::getDataFolders();
				$name = EcocatCMS211_CODEC::Name;
				if(ENABLE_DECENTRALIZED) $name .= '_dec';
				$sysfile = sprintf(PLUGIN_PATH.self::sysDataPath,$name);
				break;
			case ConvertModeEnum::ECOCATCMS304:
				$files = EcocatCMS304_CODEC::getDataFiles();
				$dirs = EcocatCMS304_CODEC::getDataFolders();
				$name = EcocatCMS304_CODEC::Name;
				$name .= EcocatCMS304_CODEC::Html5Version;
				if(ENABLE_DECENTRALIZED) $name .= '_dec';
				$sysfile = sprintf(PLUGIN_PATH.self::sysDataPath,$name);
				break;
			case ConvertModeEnum::Flipbuilder100_ZIP:
				$files = FlipbuilderZIP100_CODEC::getDataFiles();
				$dirs = FlipbuilderZIP100_CODEC::getDataFolders();
				$name = FlipbuilderZIP100_CODEC::Name;
				if(ENABLE_DECENTRALIZED) $name .= '_dec';
				$sysfile = sprintf(PLUGIN_PATH.self::sysDataPath,$name);
				break;
			case ConvertModeEnum::MCG_ZIP:
				$files = MCG_CODEC::getDataFiles();
				$dirs = MCG_CODEC::getDataFolders();
				$name = MCG_CODEC::Name;
				if(ENABLE_DECENTRALIZED) $name .= '_dec';
				$sysfile = sprintf(PLUGIN_PATH.self::sysDataPath,$name);
		}
		common::copy($sysfile,$userpath);
		$this->merge($path,$dirs,$files);
		$this->cls->postDecode($this->tmpfolder.$path,$userpath);
	}

	public function Decode($sourcefile,$userpath){
		if($this->FileFormat==ConvertModeEnum::Unknow){
			$this->CheckFormat($sourcefile);
		}
		$this->destfolder = $userpath.'/files/'.$this->Uniquekey;

		$this->_decode('',$this->FileFormat);
		if($this->hasMultiContent){
			$dir = $this->tmpfolder.self::MultiContentFolder;
			if (file_exists($dir)){
		    if ($dh = opendir($dir)) {
		    	mkdir($this->destfolder.self::MultiContentFolder);
		    	$exclude = array('.','..');
		      while (($file = readdir($dh)) !== false) {
		      	if(!in_array($file,$exclude) && (filetype($dir.'/'.$file)=='dir')){
		      		mkdir($this->destfolder.self::MultiContentFolder.'/'.$file);
		      		//this will do check folder in the /data/html5/
		      		$format = $this->_CheckFormat($dir.'/'.$file,false);
		      		$this->_decode(self::MultiContentFolder.'/'.$file,$format);
		      	}
		      }
		      closedir($dh);
		    }
	  	}
		}
		return true;
	}

	public function InsertDB($bsid, $uid, $cid, $filename){
		return $this->cls->InsertDB($this->destfolder,$this->Uniquekey,$filename,$bsid,$uid,$cid);
	}

	//$tmp_file = /tmp/(ecocatid)
	//$target_subname = (extension type, ex:pdf)
	public function setSource($tmp_file,$subname){
		/*
		if(ENABLE_DECENTRALIZED){
			$str = $target_subname;
		}else{
			$str = $this->Uniquekey.'.'.$target_subname;
		}*/
		$filename = $this->Uniquekey;
		//decentralized: /tmp/(ecocatid), (extension type, ex:pdf)
		//not decentralized /tmp/(ecocatid), (filename.extension)
		$this->cls->setOrifile($tmp_file,$filename,$subname);
	}

	private function merge($path,$dirs,$files){
		$tmpfolder = $this->tmpfolder.$path;
		$destfolder = $this->destfolder.$path;
		foreach($files as $file=>$dir){
			$_file = $tmpfolder.$file;
			if(!file_exists($destfolder.$dir)) mkdir($destfolder.$dir);
			if(is_file($_file)){
				rename($_file,$destfolder.$file);
			}
		}
		foreach($dirs as $folder=>$allowtype){
			$allData = $this->scanDirectory($tmpfolder.$folder,explode(',',$allowtype));
			foreach($allData as $file){
				$path_parts = common::path_info($file);
				$desfile = $destfolder.$folder.'/'.$path_parts['basename'];
				if(!file_exists($desfile)){
        	rename($file,$desfile);
        }
			}
		}
	}

	private function scanDirectory($rootDir, $allowext) {
		$allData = array();
    $dirContent = scandir($rootDir);
    foreach($dirContent as $key => $content) {
        $path = $rootDir.'/'.$content;
        $ext = substr($content, strrpos($content, '.') + 1);

        if(in_array($ext, $allowext)) {
            if(is_file($path) && is_readable($path)) {
                $allData[] = $path;
            }
        }
    }
    return $allData;
	}
}
?>
