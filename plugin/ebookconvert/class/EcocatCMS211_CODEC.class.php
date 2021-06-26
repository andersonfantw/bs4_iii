<?PHP
/*********************************************************
* decode step:
* 1. create a random folder in /work/(rand)
* 2. unzip to /hosts/(uid)/(bsid)/files/
* 3. check ecocat format, make sure it's a ecocat file
* 4. add reading log script
* 5. add mobile/html5 script
* 6. insert to db
* get ecocat cover:
* http://125.227.186.122:20038/ecocatcms/index.php?action=image_loader&bid=4e1526cb_c8d39651&type=d&page=1&rate=100&apikey=8876ae114a07c0510da3f8703d208e96&apipass=cdec5e85051a41cacb344c4747ab7fc7
*********************************************************/
include_once dirname(__FILE__)."/interface/iBSFile_CODEC.iface.php";
class EcocatCMS211_CODEC implements iBSFile_CODEC{
	const Name='ecocat211';
	const ExtensionName='zip';
	var $processid;
	var $uploadfile;
	var $oritmpfile;

	public function getDataFolders(){
		return array('/css'=>'css',
									'/images'=>'jpg,png,swf,mp3,mp4.swf',
									'/images/Thumbnail'=>'jpg',
									'/m'=>'html',
									'/m/css'=>'css',
									'/m/images'=>'gif,png',
									'/m/js'=>'js,map',
									'/scripts'=>'js',
									'/xml'=>'xml');
	}
	
	public function getDataFiles(){
		return array('/book.swf'=>'',
				'/data/search_key.csv'=>'/data',
				'/data/search_pnt.csv'=>'/data',
				'/m/data/params.json'=>'/m/data/');
	}

	//should be static function, will call by BSFile.code to check file type
	public function CheckFormat($tmpfolder){
		if(strpos($tmpfolder,ROOT_PATH.'/ecocatcms211/lib/ecolab/export/')===0){
			$arr = explode('/',$tmpfolder);
			$this->processid = $arr[count($arr)-1];
			$this->zipFormat = ConvertModeEnum::ECOCATCMS211;
			return $this->zipFormat;
		}
	}

	public function preDecode(){
		//upload image
		$this->uploadfile = sys_get_temp_dir().'/'.time().'.jpg';
		$imagepath = HttpLocalIPPort.'/ecocatcms211/index.php?action=image_loader&bid='.$this->processid.'&type=d&page=1&rate=100&apikey='.EcocatConnector_Default_Key.'&apipass='.EcocatConnector_Default_Pass;
		file_put_contents($this->uploadfile,file_get_contents($imagepath));
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
		//move original file to bookshelf system download path
		$this->oritmpfile = $tmp_file;
	}

	/*
	check inside covert, if not exist, user default.
	and insert to system(db).
	*/
	public function InsertDB($tmpfolder,$Uniquekey,$filename,$bsid,$uid,$cid){
		global $db;
		global $dbr;
		global $fs;
		global $ee;

		//create cover image
  	$resize = array();
  	$resize['s_'] = array('w'=>120,'h'=>120);
  	$resize['m_'] = array('w'=>200,'h'=>260);
  	$val = common::insert_host_image($this->uploadfile,0,$resize,$uid,$bsid);
  	if($val['id']){
  		//get filename from ecocat_db
  		$sql=<<<SQL
select book_name, original_file_name from t_book where process_id='%s'
SQL;
			$sql = sprintf($sql,$this->processid);
			$data1 = $dbr->query_first($sql);

			$originalfile = array(
				'filename'=>$data1['original_file_name'],
				'filepath'=>$this->oritmpfile);
			$indexpage = 'book_swf';
			$result = BookManager::putOnShelf($uid, $bsid, $Uniquekey, $tmpfolder, $originalfile, $indexpage);
			//ibook_link = /files/(wonderboxid + timestamp)/(filename.extension)
			$ibook_link = LocalHost.'/download/'.$Uniquekey.'/'.$data1['original_file_name'];

  		//insert db
  		$data['file_id'] = $val['id'];
			$data['b_name'] = $data1['book_name'];
			$data['webbook_link'] = $result['webbook_link'];
			$data['ibook_link'] = $ibook_link;
			$data['bs_id']=$bsid;
			$data['b_key']=$Uniquekey;
			$data['c_id']=$cid;
			$data['webbook_show']=1;
			$data['ibook_show']=1;
			$data['b_status']=$result['status'];
			$book = new book(&$db);
			$id = $book->insert($fs->sql_safe($data));
			if(!$id){
				$ee->Error('500.1');
			}else{
				$EcocatConnector = new EcocatConnector($bsid);
				$EcocatConnector->DeleteBook($this->processid);
				return $id;
			}
		}
		
	}
}
?>
