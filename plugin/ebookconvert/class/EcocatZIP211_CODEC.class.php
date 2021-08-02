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
class EcocatZIP211_CODEC implements iBSFile_CODEC{
	const Name='ecocat211';
	const ExtensionName='zip';

	public function getDataFolders(){
		return array('/html'=>'html','/images'=>'jpg,png,swf,mp3,mp4','/images/Thumbnail'=>'jpg','/xml'=>'xml','/data'=>'png');
	}
	
	public function getDataFiles(){
		return array('/data/search_key.csv'=>'/data',
									'/data/search_key.txt'=>'/data',
									'/data/search_pnt.csv'=>'/data',
									'/data/search_pnt.txt'=>'/data');
	}

	//should be static function, will call by BSFile.code to check file type
	public function CheckFormat($tmpfolder){
		$ecocat = array('/data/search_key.txt','/data/search_pnt.txt');
		$f_exist=1;
		foreach($ecocat as $file){
			if(!is_file($tmpfolder.$file)){
				$f_exist=0;
			}
		}
		if($f_exist){
			$this->zipFormat = ConvertModeEnum::ECOCAT211_ZIP;
			return $this->zipFormat;
		}
	}

	public function preDecode(){
	}

	//this is for doing anything necessary after decode
	public function postDecode($sourcepath,$destpath){
		//move __dmx forder to dest
		$allowtype=array('jpg','mp3','mp4');
		$dir = opendir($sourcepath);
		while(false !== ( $file = readdir($dir)) ) { 
			if(is_dir($sourcepath . '/' . $file) && preg_match('/.+__dmx/i',$file,$matches)){
				mkdir($destpath.'/'.$file);
				$dir_dmx = opendir($sourcepath.'/'.$file);
				while(false !== ( $file1 = readdir($dir_dmx)) ) { 
					if (( $file1!='.' ) && ( $file1!='..' ) && !is_dir($sourcepath.'/'.$file.'/'.$file1)) {
						$path_parts=common::path_info($file1);
						if(in_array($path_parts['extension'],$allowtype)){
							rename($sourcepath.'/'.$file.'/'.$file1,$destpath.'/'.$file.'/'.$file1);
						}
					}
				}
			}
		}
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
		$uploadfile = $tmpfolder.'/data/cover.png';
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
			$indexpage = 'book_swf';
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
			$id = $book->insert($fs->sql_safe($data));
			if(!$id){
				$ee->Error('500.1');
			}
			return $id;
		}
		
	}
}
?>
