<?PHP
/*********************************************************
* decode step:
* 1. create a random folder in /work/(rand)
* 2. unzip to /hosts/(uid)/(bsid)/files/
* 3. check ecocat format, make sure it's a ecocat file
* 4. add reading log script
* 5. add mobile/html5 script
* 6. insert to db
*
*********************************************************/
include_once dirname(__FILE__)."/interface/iBSFile_CODEC.iface.php";
class FlipbuilderZIP100_CODEC implements iBSFile_CODEC{
	const Name='flipbuilder100';
	const ExtensionName='zip';

	public function getDataFolders(){
		return array('/files/thumb'=>'jpg','/files/search'=>'xml','/files/pageConfig'=>'mp3,mp4,swf','/files/page'=>'swf','/files/mobile'=>'jpg','/files/extfiles'=>'jpg,jpeg,png,gif','/files/basic-html'=>'html');
	}
	
	public function getDataFiles(){
		return array('/files/pageConfig/fbp.swf'=>'/files/pageConfig',
									'/files/config.xml'=>'/files',
									'/files/shot.png'=>'/files',
									'/mobile/javascript/bookmark_config.js'=>'/mobile/javascript',
									'/mobile/javascript/config.js'=>'/mobile/javascript',
									'/mobile/javascript/search_config.js'=>'/mobile/javascript');
	}

	//should be static function, will call by BSFile.code to check file type
	public function CheckFormat($tmpfolder){
		$ecocat = array('/files/config.xml','/files/shot.png');
		$f_exist=1;
		foreach($ecocat as $file){
			if(!is_file($tmpfolder.$file)){
				$f_exist=0;
			}
		}
		if($f_exist){
			$this->zipFormat = ConvertModeEnum::Flipbuilder100_ZIP;
			return $this->zipFormat;
		}
	}

	public function preDecode(){
	}

	//this is for doing anything necessary after decode
	public function postDecode($sourcepath,$destpath){
	}

	public function setOrifile($tmp_file,$filename,$subname){
	}

	/*
	check inside covert, if not exist, user default.
	and insert to system(db).
	*/
	public function InsertDB($tmpfolder,$Uniquekey,$filename,$bsid,$uid,$cid){
		global $db;
		global $fs;
		global $ee;

		//check user conver path: /data/cover.png
		$uploadfile = $tmpfolder.'/files/shot.png';
		if(!is_file($uploadfile)){
			//if user cover is not exist, user default /images/ecocat.png
			$uploadfile = PLUGIN_PATH.'/ebookconvert/images/lnet_cover1.png';
		}
		//create cover image
  	$resize = array();
  	$resize['s_'] = array('w'=>120,'h'=>120);
  	$resize['m_'] = array('w'=>200,'h'=>260);
  	$val = common::insert_host_image($uploadfile,0,$resize,$uid,$bsid);
  	if($val['id']){
			$originalfile = array(
				'filename'=>$filename,
				'filepath'=>$this->oritmpfile);
			$indexpage = 'index';
			$result = BookManager::putOnShelf($uid, $bsid, $Uniquekey, $tmpfolder, $originalfile, $indexpage);

  		//insert db
  		$data['file_id'] = $val['id'];
			$data['b_name'] = $filename;
			$data['webbook_link'] = $result['webbook_link'];
			$data['bs_id']=$bsid;
			$data['c_id']=$cid;
			$data['webbook_show']=1;
			$data['ibook_show']=0;
			$data['b_status']=$result['status'];
			$book = new book($db);
			$id =$book->insert($fs->sql_safe($data));
			if(!$id){
				$ee->Error('500.1');
			}
			return $id;
		}
		
	}
}
?>
