<?PHP
/*
BSFileManager
���d�t�Ϊ��ɮ׺޲z��
�|�ϥΨ�BSFileManager���{���G�ɮפW�ǩ�J���d�C���e�C
�ѧO�ɮת������A�I�s������codec
�Vcodec�߰ݥ\��
�̷�codec���\���B�z�������\��
����codec�������ҡA�s�X/�ѽX
�Ndecode���q�l���ɮש��ϥΪ̪���Ƨ�
�N�ǳ�encode���ɮש��work�u�@�ϸ�Ƨ�
�N�ǳ�encode����Ʈw��ƿ�X���ɮש��work�u�@��
���ϥΪ̱N�ѤW�[����d

BSFile_CODEC
���d�ɮ������ѽX���ɮײ�
���ѵ�BSFileManager���\���
�ǳƻs�@�ɮשһݪ��ɮײսs�X���ɮ�
�����ɮת����T��

*/
class BSFileManager{
	var $codecs;
  function __construct(){
  	foreach($this->codecs as $codec){
  		require_once ROOT_PATH.'plugin/filemanage/class/'.$codec.'.codec.class.php';
  	}
  }

/*
���U�s�W��Codec
*/
  function RegisterCodec(){
  }

/*
���o�w���U��Codec
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