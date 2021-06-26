<?PHP
class TagImportManager{
	var $ErrorHandler;
	var $has_error = false;
	var $mode = ImportManagerModeEnum::Unknow;
	var $bsid = 0;
	function __construct($bsid=0,$mode=ImportManagerModeEnum::Unknow){
		$this->mode=$mode;
		$this->bsid=$bsid;
	}
	function Import(){
		global $fs;
		global $ee;
		$mode=TagImportModeEnum::Unknow;
		$uploadfile_name = $fs->valid($_FILES['uploadedFile']['name'],'name');
		$uploadfile_tmpname = $fs->valid($_FILES['uploadedFile']['tmp_name'],'path');
		$file_parts = pathinfo($uploadfile_name);

		switch($_FILES['uploadedFile']['type']){
			case 'application/octet-stream':
			case 'text/plain':
				$sub = strtolower($file_parts['extension']);
				$file_content = file_get_contents($uploadfile_tmpname);
				if(!$file_content){
					echo $this->ErrorHandler->Error('404.35');
					exit;
				}
				$this->ImportByStr($file_content,$sub);
				break;
			default:
				$this->has_error = true;
				$ee->Error('406.30');
				break;
		}
	}
	public function ImportByStr($file_content,$type='tag'){
		global $ee;
		switch($type){
			case 'tag':
				$TagTree = new TagTree();
				$input = $TagTree->formatString($file_content);
				if(!$TagTree->chkFormat($input)){
					$ee->Error('406.91');
				}
				$mode=TagImportModeEnum::Tags;
				//$TagTree->loadDB();
				$TagTree->loadString($input);
				if($TagTree->hasDispute()){
					$str = $TagTree->toString(2,true);
					$ee->add('content',$str);
					$ee->Error('406.91');
				}else{
					$TagTree->saveDB();
					$ee->Message('200');
					exit;
				}
			case 'dic':
				$TagDocument = new TagDocument();
				$input = $TagDocument->formatString($file_content);
				if(!$TagDocument->chkFormat($input)){
					$ee->Error('406.91');
				}

				$mode=TagImportModeEnum::Dictionary;
				$TagDocument->loadDictionaryDB();
				$TagDocument->loadDictionaryString($input);
				if($TagDocument->hasDispute()){
					$str = $TagDocument->exportDictionaryString(true);
					$ee->add('content',$str);
					$ee->Error('406.92');
				}else{
					$TagDocument->saveDictionaryDB();
					$ee->Message('200');
				}
		}
	}
}
?>
