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
class BookZip_CODEC implements iBSFile_CODEC{
	const ExtensionName='zip';

	public function getDataFolders(){
		return array('/covers'=>'jpg,jpeg,png');
	}
	
	public function getDataFiles(){
		return array();
	}

	//should be static function, will call by BSFile.code to check file type
	public function CheckFormat($tmpfolder){
		$ecocat = array('/book.xls');
		$f_exist=1;
		foreach($ecocat as $file){
			if(!is_file($tmpfolder.$file)){
				$f_exist=0;
			}
		}
		if($f_exist){
			$this->zipFormat = ConvertModeEnum::ECOCAT_ZIP;
			return $this->zipFormat;
		}
	}

	//this is for doing anything necessary after decode
	public function postDecode($sourcepath,$destpath){
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
			$uploadfile = PLUGIN_PATH.'/ebookconvert/images/ecocat.png';
		}
		//create cover image
  	$resize = array();
  	$resize['s_'] = array('w'=>120,'h'=>120);
  	$resize['m_'] = array('w'=>200,'h'=>260);
  	$val = common::insert_host_image($uploadfile,0,$resize,$uid,$bsid);
  	if($val['id']){
  		//insert db
  		$data['file_id'] = $val['id'];
			$data['b_name'] = $filename;
			$data['webbook_link'] = LocalHost.HostManager::getBookshelfBase(false,false,$uid,$bsid).'/files/'.$Uniquekey.'/book_swf.php';
			$data['bs_id']=$bsid;
			$data['c_id']=$cid;
			$data['webbook_show']=1;
			$data['ibook_show']=0;
			$data['b_status']=1;
			$book = new book(&$db);
			if(!$book->insert($fs->sql_safe($data))){
				$ee->Error('500.1');
			}
		}
		
	}
}
?>