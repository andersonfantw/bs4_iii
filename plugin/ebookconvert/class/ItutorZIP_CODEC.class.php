<?PHP
/*********************************************************
* decode step:
* 1. create a random folder in /work/(rand)
* 2. unzip to /hosts/(uid)/(bsid)/files/
* 3. check itutor format, make sure it's a itutor file
* 4. set report path
* 5. decide first page
* 6. add reading log script
* 7. insert to db
* 
*********************************************************/
include_once dirname(__FILE__)."/interface/iBSFile_CODEC.iface.php";
class ItutorZIP_CODEC implements iBSFile_CODEC{
	const Name='itutor';
	const ExtensionName='zip';

	public function getDataFolders(){
		return array('/data'=>'png,mp3,mp4,ogg,webm');
	}
	
	public function getDataFiles(){
		return array('/player/params.js'=>'/player','/player/rlplayer.js'=>'/player/','/player/slides.js'=>'/player/');
	}

	public function _getMods(){
		return array('demo','test','practice','tutorial');
	}

	//should be static function, will call by BSFile.code to check file type
	public function CheckFormat($tmpfolder){
		$itutor = array('/player/params.js');
		$f_exist=0;
		foreach($itutor as $file){
			if(is_file($tmpfolder.$file)){
				$f_exist=1;
			}
		}
		if($f_exist){
			$this->zipFormat = ConvertModeEnum::ITUTOR_ZIP;
			return $this->zipFormat;
		}
		return null;
	}

	public function preDecode(){
	}

	//this is for doing anything necessary after decode
	public function postDecode($sourcepath,$destpath){
		$mods = self::_getMods();
		foreach($mods as $mod){
			$file = $sourcepath.'/'.$mod.'.html';
			//check source play mod
			if(is_file($file)){
				//copy mode file to destfolder
				$mod_path = '_mod/';
				if(ENABLE_DECENTRALIZED){
					$mod_path = '_mod_dec/';
				}
				$mod_path = EBOOKCONVERT_DATA_PATH.self::Name.$mod_path;
				copy($mod_path.$mod.'.html',$destpath.'/'.$mod.'.html');
				if(!ENABLE_DECENTRALIZED){
					copy($mod_path.$mod.'.php',$destpath.'/'.$mod.'.php');
				}
			}
		}

		//set report url
		$report_path = $destpath.'/player/params.js';
		if(is_file($report_path)){
			$pattern = '("report_value":"[^"]*")';
			$replacement = '"report_value":"/api/report.php"';
			$string = file_get_contents($report_path);
			file_put_contents($report_path,preg_replace($pattern, $replacement, $string));
		}
	}

	public function setOrifile($tmp_file,$filename,$subname){
	}

	/*
	check inside covert, if not exist, user default.
	and insert to system(db).
	*/
	public function InsertDB($destfolder,$Uniquekey,$filename,$bsid,$uid,$cid){
		global $db;
		global $fs;
		global $ee;

		$mods = self::_getMods();
		//check start page
		//only one mod, set page as start page
		//over 2 mods, set index.html as start page
		$count=0;
		$startpage = '';
		$sub='.php';
		if(ENABLE_DECENTRALIZED){
			$sub='.html';
		}
		foreach($mods as $mod){
			$file = $destfolder.'/'.$mod.$sub;
			//check source play mod
			if(is_file($file)){
				//if exists
				$startpage = $mod;
				$count++;
			}
		}
		if($count>1) $startpage='index.html';

		//check user conver path: /data/cover.png
		$uploadfile = $destfolder.'/data/cover.png';
		if(!is_file($uploadfile)){
			//if user cover is not exist, user default /images/itutor.png
			$uploadfile = PLUGIN_PATH.'/ebookconvert/images/lnet_cover1.png';
		}
		//create cover image
  	$resize = array();
  	$resize['s_'] = array('w'=>120,'h'=>120);
  	$resize['m_'] = array('w'=>200,'h'=>260);
  	$val = common::insert_host_image($uploadfile,0,$resize,$uid,$bsid);
  	if($val['id']){
			$originalfile = array(
				'filename'=>'',
				'filepath'=>'');
			$result = BookManager::putOnShelf($uid, $bsid, $Uniquekey, $destfolder, $originalfile, $startpage);

  		//insert db
  		$data['file_id'] = $val['id'];
			$data['b_name'] = $filename;
			$data['webbook_link'] = $result['webbook_link'];
			$data['bs_id']=$bsid;
			$data['b_key']=$Uniquekey;
			$data['c_id']=$cid;
			$data['webbook_show']=1;
			$data['ibook_show']=0;
			$data['b_status']=$result['status'];
			$book = new book($db);
			$id = $book->insert($fs->sql_safe($data));
			if(!$id){
				$ee->Error('500.1');
			}
			return $id;
		}
	}
}
?>
