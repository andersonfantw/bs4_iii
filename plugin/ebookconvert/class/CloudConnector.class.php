<?PHP
class CloudConnector{
	const USERCONVERT_APIPath = 'http://cloudbook.cyberhood.net/cloudbook/convertbook0.php';
	const USERCONVERT_domain = 'cloudbook.cyberhood.net';
	const USERCONVERT_port = 80;
	const USERCONVERT_SAVEPATH = '/home/ebook/cloud_convert/';

	var $ErrorHandler;
	
	var $service_id;
	var $book_name;
	var $book_size;
	var $book_pages;
	var $book_path;
	var $adm_key;
	var $bs_key;
	var $cate_key;

	function __construct(){
		global $ee;
		if(!LicenseManager::IsBookshelfLicenseValid()){
			$ee->add('link','http://cloudbook.cyberhood.net/cloudbook/licensebuy_02.php?service_id='.wonderbox_id);
			$ee->Error('401.26');
		}
		if(!is_numeric(wonderbox_id)){
			$ee->Error('404.80');
		}

		global $fs;
		$this->bs_id = $fs->valid($_GET['bs'],'id');
		$this->cate2 = $fs->valid($_GET['c'],'c');

		if(!$_FILES){
			$ee->Error('400.32');
		}
		if($_FILES['uploadedFile']['type']!='application/pdf'){
			$ee->Error('406.30');
		}
		if(!isset($_FILES['uploadedFile']['size'])){
			$ee->Error('400.34');
		}
		if(empty($this->bs_id)){
			$ee->Error('406.60');
		}
		if(empty($this->cate2)){
			$ee->Error('406.60');
		}
		if(empty($_SESSION['adminid'])){
			$ee->Error('401.11');
		}
	}

	public function Send(){
		$timestamp = time();
		$PostData = array();
		$path = self::USERCONVERT_SAVEPATH.wonderbox_id;
		$bookpath = $path.'/'.$timestamp.'.pdf';

		//move uploaded file to uploadfolder
		if(!file_exists($path)){
			mkdir($path);
		}
		move_uploaded_file($_FILES['uploadedFile']['tmp_name'],$bookpath);

		$path_parts = common::path_info($_FILES['uploadedFile']["name"]);
		//prepare sending info
		$PostData['service_id'] = $this->service_id = wonderbox_id;
		$PostData['book_name'] 	= $this->book_name = $path_parts['filename'];
		$PostData['book_path'] 	= $this->book_path = $bookpath;
		
		$PostData['bs_key'] 		= $this->bs_id;
		$PostData['cate_key'] 	= $this->cate2;
		$PostData['adm_key'] 		= $this->adm_key = $_SESSION['uid'];
		$PostData['book_size'] 	= $this->booksize = intval($_FILES['uploadedFile']['size'])/1024;
		$PostData['book_pages'] = $this->book_pages = self::count_pages($bookpath);

		//post data
		$result = HttpClient::quickPost(self::USERCONVERT_APIPath, $PostData);
		if(!$result){
			//$code = $HttpClient->getStatus();
			//$this->ErrorHandler.Error($code);
		}
		
		$array = json_decode($result,TRUE);
	  if(empty($array)){
	  	$ee->Error('204.71');
	  }
		
		if($array['code']=='0'){
			$ee->add('link','http://cloudbook.cyberhood.net/cloudbook/convertbook.php?session_id='.$array['session_id'])
			$ee->Message('200.23');
			exit;
		}
	}

	/****************************************
	use pdfinfo to get pdf info.
	Title:
	Subject:
	Keywords:
	Author:
	Creator:        PDFCreator Version 1.5.0
	Producer:       GPL Ghostscript 9.05
	CreationDate:   Wed Mar 27 16:28:32 2013
	ModDate:        Wed Mar 27 16:28:32 2013
	Tagged:         no
	Form:           none
	Pages:          1
	Encrypted:      no
	Page size:      595 x 842 pts (A4)
	File size:      2595 bytes
	Optimized:      no
	PDF version:    1.4
	******************************************/
	private function count_pages($pdf_path) {

		$cmd=PLUGIN_PATH.'/ebookconvert/libs/pdfinfo '.$pdf_path;
		exec($cmd, $cmd_ret, $retCode);
		foreach($cmd_ret as $_k => $_v){
		        if(strpos ($_v,"Pages:") !== false){
		                $Pages = ltrim(str_replace ("Pages:", "",$_v));
		        }
		}
		return $Pages;
	}
}
?>
