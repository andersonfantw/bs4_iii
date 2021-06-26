<?PHP
/*
BSFileManager
書櫃系統的檔案管理員
會使用到BSFileManager的程式：檔案上傳放入書櫃。派送。
識別檔案的類型，呼叫對應的codec
向codec詢問功能
依照codec的功能表處理對應的功能
執行codec中的驗證，編碼/解碼
將decode的電子書檔案放到使用者的資料夾
將準備encode的檔案放到work工作區資料夾
將準備encode的資料庫資料輸出成檔案放到work工作區
為使用者將書上架到書櫃

BSFile_CODEC
書櫃檔案類型解碼為檔案組
提供給BSFileManager的功能表
準備製作檔案所需的檔案組編碼為檔案
驗證檔案的正確性

*/
class BSFileManager{
	var $codecs;
  function __construct(){
  	foreach($this->codecs as $codec){
  		require_once ROOT_PATH.'plugin/filemanage/class/'.$codec.'.codec.class.php';
  	}
  }

/*
註冊新增的Codec
*/
  function RegisterCodec(){
  }

/*
取得已註冊的Codec
*/
	function GetCodec(){
		$this->codecs = array('EBK','ITU','EcocatZIP','ItutorZIP','LBMZIP');
	}

  /*
  params:
  	filename: file in /work
  return:
  	bid
  */
	function Import($path){
		$filetype = CheckFileType($path);
		if($filetype == ''){
			echo 'Codec not support!';
			return;
		}

		$pathinfo = common::path_info($path);
		$workpath = WORK_APTH.'/'.$pathinfo['basename'];
		rename($path,$workpath);

  	$cls = $filetype.'_Codec';
  	$codec = new $cls;
		$bookinfo = $codec->Decode($workpath);
		if($bookinfo)
		{
			$bid = InsertDB($bookinfo);
			return $bid;
		}else{
		}

  	
	}

  /*
  params:
  	bid
  	filetype: EBK, EcocatZIP, ITU, ItutorZIP, LBMZIP
  return:
  	filename: file in /work
  */
	function Export($bid, $filetype){
		$codec_path = ROOT_PATH.'plugin/filemanage/class/'.$filetype.'codec.class.php';
		if(!file_exists($codec_path)){
			echo $filetype. ' codec not exists!';
			return -1;
		}
		require_once $codec_path
  	$cls = $filetype.'_Codec';
  	$codec = new $cls;

		if($codec->Encode()){
			return $filename;
		}else{
		}
	}

	/*
	params: 
		filename: file name in /work
	return: 
		filetype: EBK, ITU, EcocatZIP, ItutorZIP, LBMZIP
	*/
	function CheckFileType($path){
		$path_parts = common::path_info($path);
		$ext = strtolower($path_parts['extension']);
		switch($ext){
			case 'itu':
				if($ITU::CheckFolder($path)){
					$filetype = 'ITU';
				}
				break;
			case 'ebk':
				if($EBK::CheckFolder($path)){
					$filetype = 'EBK';
				}
				break;
			case 'zip':
				if($EcocatZIP::CheckFolder($path)){
					$filetype='EcocatZIP';
				}
				if($ItutorZIP::CheckFolder($path)){
					$filetype='ItutorZIP';
				}
				if($LBMZIP::CheckFolder($path)){
					$filetype='LBMZIP';
				}
				break;
		}
		return $filetype;
		
	}
	function MoveToWorkArea();
	function MoveToUserFolder();
	function ImportTag();
	function ExportTag();
	function InsertDB($bookinfo);
}
?>