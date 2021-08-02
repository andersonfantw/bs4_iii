<?PHP
/*********************************************************
* help developers get ebk info, so thay may management ebk files.
* call functions to Encode(become file)/Decode(add to system) ebk.
* 
* encoder input
* sf: source folder
* data: data of book, 
* d: distination folder, default same folder with source file folder
* encoder output
* ebk file
* encode step:
* 1. create a tmp folder in /work/(tmp), move ebook html in it
* 2. check/create license, and prepare ebk header data.
* 3. check ebk sysfile & cover image:
*��		.tag
*��		.log
*			.ebk(all header)
*    if lost files than create.
*    check book cover image (/work/(tmp)/data/cover.jpg, coverM.jpg, coverS.jpg)
* 4. check copyright
* 5. add extra tag into .tag
* 6. create ebk file with book english name, or ISBN, or book key
*    in /hosts/(uid)/(bsid)/work/(rand)/book_english_name.zip
* 7. create ebk file in /hosts/(uid)/(bsid)/download/book_english_name.ebk
* 8. rm /hosts/(uid)/(bsid)/work/(tmp)
*
* decoder input
* f: ebk file
* decoder output
* add book to bookshelf system
* bsid: (int( for success, -1 for error
* decode step:
* 1. sep cover image and ebk package
* 2. read ebk header, sep header and zip package.
*    save package to zip in /hosts/(uid)/(bsid)/work/(tmp).zip
* 3. unzip file to /hosts/(uid)/(bsid)/files/
* 4. check copyright
* 5. check & read ebk sysfile
* 6. add log to .log 
* 7. set license
* 8. insert a reacord to db, also .bookinfo to book content
* 9. insert tag to db
*10. rm /work/(rand).zip, /work/(tmp)/
* 
* EBK_read_data
* input
* f: ebk file
* output
* array of ebk info, format as below
* arr['EBKPath']['identification']
* arr['EBKPath']['length']
* arr['EBKPath']['licenseBSnum']
* arr['EBKPath']['licenseACnum']
* arr['BookInfo']['bookname']
* arr['BookInfo']['filename']
* arr['BookInfo']['publicshdate']
* arr['BookInfo']['version']
* arr['BookInfo']['pages']
* arr['BookInfo']['languagecode']
* arr['BookInfo']['ISBN']
* arr['BookInfo']['bookkey']
* arr['Copyright']['author']
* arr['Copyright']['publisher']
* arr['License']['users']
* arr['License']['concurrent']
* arr['LicenseBS'][0]['bskey']
* arr['LicenseBS'][0]['users']
* arr['LicenseBS'][0]['concurrent']
* arr['LicenseAcc'][0]['acckey']
* arr['LicenseAcc'][0]['users']
* arr['LicenseAcc'][0]['concurrent']
*********************************************************/
define('EBK_SEPARATOR','==lnetseparator==');
define('EBKPATH_COPYRIGHT','');
define('EBKPATH_TAG','');
define('EBKPATH_COPYLOG','');
class EBK_Codec extends BSFile_CODEC implements iBSFile_CODEC{
	private $Filename;
	private $CoverImagePath;

	private $EBKPath;
	private $CoverImagePath;
	private $ZipPath;
	private $binaryEbkHeader;

	private $EBKHeader = array('EbkDef','BookInfo','Copyright','License','LicenseBS','LicenseAcc');
	var $BookContent;
	var $Tag;
	var $Log;


	private $bsid;
	private $cid;
	private $bid;

	//exec EBK_read_data to get value
	// pointer in ebk file;
	private $pointerHeaderStart;
	private $pointerPackageStart;
	
	//var $HeaderDef = '{"ebk":{"identification":{"t":"C*","len":5},"length":{"t":"L","len":"4"}},"bookinfo":{"bookname":{"t":"C*","len":255},"filename":{"t":"C*","len":255},"publishdate":{"t":"","len":8},"version":{"t":"C*","len":10},"pages":{"t":"I","len":"4"},"languagecode":{"t":"C*","len":5,"IsTag":1},"ISBN":{"t":"C*","len":13},"bookkey":{"t":"C*","len":20}},"copyright":{"foldernum":{"t":"I","len":4},"filenum":{"t":"I","len":4},"author":{"t":"C*","len":30},"plblisher":{"t":"C*","len":50}},"license":{"wonderbox":{"t":"I","len":4},"bookshelf":{"identify":"B","num":{"t":"I","len":4},"users":{"t":"I","len":4},"concurrent":{"t":"I","len":4},"auth":{"bskey":{"t":"C*","len":10},"users":{"t":"I","len":4},"concurrent":{"t":"I","len":4}}},"account":{"identify":"A","num":{"t":"I","len":4},"users":{"t":"I","len":4},"concurrent":{"t":"I","len":4},"auth":{"bskey":{"t":"C*","len":10},"users":{"t":"I","len":4},"concurrent":{"t":"I","len":4}}}}}';
	//var $HeaderDef = '{"ebk":{"identification":{"t":"C*","len":5},"length":{"t":"L","len":"4"}},"bookinfo":{"bookname":{"t":"C*","len":255},"filename":{"t":"C*","len":255},"publishdate":{"t":"","len":8},"version":{"t":"C*","len":10},"pages":{"t":"I","len":"4"},"languagecode":{"t":"C*","len":5,"IsTag":1},"ISBN":{"t":"C*","len":13},"bookkey":{"t":"C*","len":20}},"copyright":{"foldernum":{"t":"I","len":4},"filenum":{"t":"I","len":4},"author":{"t":"C*","len":30},"plblisher":{"t":"C*","len":50}}}';

	function __construct(){

	}
	function __destruct(){
		//remove all tmp files.
	}
	//�Ҧ�ebk�ɮ�&�ʭ��Y�ϡA����b/data
	public function EBK_read_data($path){
		var $pEBKDef = 'a5identification/i4length/i4licenseBSnum/i4licenseACnum';
		var $pBookInfo = 'a255bookname/a255filename/l8publicshdate/a10version/i4pages/a5languagecode/a13ISBN/a20bookkey';
		var $pCopyright = 'i4xcode/i4ycode/a30author/a50publisher';
		var $pLicense  = 'i4wonderbox/i4users/i4concurrent';
		var $pLicenseBS = '/a10bskey/i4users/i4concurrent';
		var $pLicenseACC = '/a10acckey/i4users/i4concurrent';

		$image_data = exif_read_data($this->EBKPath);

		$this->pointerHeaderStart = $image_data['FILE']['FileSize']+1;
		$this->pointerPackageStart = $image_data['FILE']['FileSize']+1+$EBKDef['length'];

		$fp=fopen($filename,"r+");
		fseek($fp,$this->pointHeaderStart);
		$this->EBKHeader['EBKDef'] = unpack($pEBKDef, fread($fp,17));
		$this->EBKHeader['BookInfo'] = unpack($pBookInfo, fread($fp,566));
		$this->EBKHeader['Copyright'] = unpack($pCopyright, fread($fp,88));
		$this->EBKHeader['License'] = unpack($pLicense, fread($fp,12));
		
		$this->Filename = $this->EBKDef['filename'];

		if($EBKDef['licenseBSnum']>0){
			$str = substr(str_repeat($pLicenseBS,$EBKDef['licenseBSnum']),1);
			$bytes = $EBKDef['licenseBSnum']*18;
			$this->EBKHeader['LicenseBS'] = unpack($str, fread($fp,$bytes));
		}
		if($EBKDef['licenseACnum']>0){
			$str = substr(str_repeat($pLicenseBS,$EBKDef['licenseACnum']),1);
			$bytes = $EBKDef['licenseACnum']*18;
			$this->EBKHeader['LicenseAcc'] = unpack($str, fread($fp,$bytes));
		}

		fclose($fp);

		if(!$this->checkEBKHeader($this->EBKHeader)){
		}

		//check length
		if($EBKDef['length']!=(656+18*($EBKDef['licenseBSnum']+$EBKDef['licenseACnum']))){
			
		}
		
		return $this->EBKHeader;
	}

	//Extract files to work folder
	function ExtractTO(){
		if(!empty($this->pointPackageStart)){
			$tmpfname = tempnam(sys_get_temp_dir(), "ebk");
			$fp = fopen($this->EBKPath, "r+");
			fseek($fp, $this->pointerPackageStart);
			$temp = fopen($tmpfname, "w");
			
			while (!feof($fp)) {
			  fwrite($temp, fread($fp, 8192));
			}
			fclose($fp);
			fclose($temp);
		}
		
		$workpath = HostManager::getBookshelfBase(false,true).'work/';
		common::unzip($tmpfname,$workpath);
		@unlink($tmpfname);
		
		//set sysfiles path
		if(is_file($workpath.'data/cover.jpg')){
			$this->CoverImagePath = $workpath.'data/cover.jpg';
		}else{
			copy(ROOT_PATH.'/images/cover.jpg',$workpath.'data/');
		}
		
	}

	function Install(){
		global $db;
		//insert book record
  	$resize = array();
  	$resize['m_'] = array('w'=>200,'h'=>260);
  	$resize['s_'] = array('w'=>120,'h'=>120);
		$val = common::insert_host_image($this->CoverImagePath,0,$resize);
		
		$data = array();
		$data['b_name'] = $this->BookInfo['bookname'];
		$data['b_description'] = $this->BookContent['description'];
		$data['file_id'] = $val['id'];
		$data['webbook_link']
		$data['ibook_link']
		$data['b_status'] = 1;
		$data['b_top'] = 0;
		$data['b_order'] = 0;
		$data['ecocat_id'] = '';
		$data['share_bs_id'] = '';
		$data['b_views_webbook'] = 0;
		$data['b_views_ibook'] = 0;
		$data['webbook_show'] = 1;
		$data['ibook_show'] = 0;
		$data['bs_key']
		$data['c_key']
		$data['b_key']
		$data['bs_id'] = $this->bsid;
		$data['c_id'] = $this->cid;

		$book = new book($db);
		$this->b_id = $book->insert($data, true);

		//set license
		//add log
	}

	function Compress($bid){
		//get book path
		//zip bookand move to /work
	}

	private function createEBKHeader($bid){
		global $db;
		$EbkDefMap = array("identification"=>"a5","length"=>"i4","licenseBSnum"=>"i4","licenseACnum"=>"i4");
		$BookInfoMap = array("bookname"=>"a255","filename"=>"a255","publicshdate"=>"l8","version"=>"a10","pages"=>"i4","languagecode"=>"a5","ISBN"=>"a13","bookkey"=>"a20");
		$CopyrightMap = array("xcode"=>"i4","ycode"=>"i4","author"=>"a30","publisher"=>"a50");
		$LicenseMap = array("wonderbox"=>"i4","users"=>"i4","concurrent"=>"i4");
		$LicenseBSMap = array("bskey"=>"i4","users"=>"i4","concurrent"=>"i4");
		$LicenseAccMap = array("Acckey"=>"i4","users"=>"i4","concurrent"=>"i4");

		$maincate = array("EbkDef","BookInfo","Copyright","License","LicenseBS","LicenseAcc");

		$book = new book($db);
		$data = $book->getByID($bid);
		//get necessary data
		$this->EbkDef=array();
		$this->EbkDef['identification']='L-NET';
		$this->EbkDef['length']=;
		$this->EbkDef['licenseBSnum']=0;
		$this->EbkDef['licenseACnum']=0;
		$this->BookInfo=array();
		$this->BookInfo['bookname'] = $data['b_name'];
		$this->BookInfo['filename']
		$this->BookInfo['publicshdate'] = time();
		$this->BookInfo['version']
		$this->BookInfo['pages']
		$this->BookInfo['languagecode']
		$this->BookInfo['ISBN']
		$this->BookInfo['bookkey']
		$this->Copyright=array();
		$this->Copyright['xcode']
		$this->Copyright['ycode']
		$this->Copyright['author']
		$this->Copyright['publisher']
		$this->License=array();
		$this->License['Wonderbox']
		$this->License['users']
		$this->License['concurrent']
		$this->LicenseBS=array();
		$this->LicenseBS[0]=array();
		$this->LicenseBS[0]['bskey']
		$this->LicenseBS[0]['users']
		$this->LicenseBS[0]['concurrent']
		$this->LicenseAcc=array();
		$this->LicenseAcc[0]=array();
		$this->LicenseAcc[0]['acckey']
		$this->LicenseAcc[0]['users']
		$this->LicenseAcc[0]['concurrent']
		
		var $bytes;
		foreach($maincate as $cate){
			$Map = $cate.'Msp';
			foreach($this->$cate as $key => $val){
				$bytes .= pack($$Map[$key],$val);
			}
		}
		$this->binaryEbkHeader = $bytes;
	}

	function Encode($bid){
		$val = $this->checkEBKHeaderFile($bid);

		$this->addTag();
		$EBKHeader = $this->createEBKHeader();
		$this->PackageEBKFile($EBKHeader);
		$this->MoveToUserPath();
		
	}
	function Decode($path,$bsid,$cid){
		$this->EBKPath = $path;
		$this->bsid = $bsid;
		$this->cid = $cid;

		$this->EBK_read_data($path);
		if(!$this->EBKHeader)
		{
		}

		$this->LoadTag();
		$this->addLog();
		$this->setLicense();
		$this->Install();

	}

	private function _getCoverImage(){
	}
	private function _setCovertImage(){
	}
	private function _formatCovertImage(){
	}
	
	//check format is correct
	//check Copyright
	//stop process while Copyright not allow, or is empty
	function checkCopyright($path,$wonderboxid,$bsid){
		exec('ls '.$path.' -lR|grep "^d"|wc -l', $folder_nums, $retCode);
		exec('find '.$path.' -type f | wc -l', $file_nums, $retCode);
		$copyright = file_get_contents(EBKPATH_COPYRIGHT.'/.copyright');
		$find=strpos($copyright,$folder_nums.$file_nums);
		if($find!==0){
			//throw new Exception('EBK not be authorized!');
			return false;
		}
		return (strpos($copyright,$wonderboxid) && strpos($copyright,sprintf('%011d',$bsid)));
	}

	function LoadTag(){
	}
	function addTag(){
	}
	function addLog(){
	}


	function PackageEBKFile(){
	}

	function checkEBKHeader(){
		return true;
	}

	//save .ebk file
	function saveEBKHeaderToFile(){
	}
}
?>