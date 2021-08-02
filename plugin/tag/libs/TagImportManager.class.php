<?PHP
require_once(LIBS_PATH.'/PHPExcel/PHPExcel.php');
require_once(LIBS_PATH.'/PHPExcel/PHPExcel/Reader/Excel2007.php');
require_once(LIBS_PATH.'/PHPExcel/PHPExcel/Writer/Excel5.php');
class TagImportManager{
	var $ErrorHandler;
	var $has_error = false;
	var $mode = ImportManagerModeEnum::Unknow;
	var $bsid = 0;
	var $file;
	var $docstr;
	function __construct($bsid=0,$mode=ImportManagerModeEnum::Unknow){
		$this->mode=$mode;
		$this->bsid=$bsid;
	}
	function addFile($uploadfile){
		$this->file = $uploadfile;
	}
	function addString($str){
		$this->docstr=$str;
	}
	function Import(){
		global $fs;
		global $ee;

		if(!empty($this->docstr)){
			$this->ImportByStr($this->docstr,'dic');
			exit;
		}
		$mode=TagImportModeEnum::Unknow;
		$uploadfile_name = $fs->valid($this->file['name'],'name');
		$uploadfile_tmpname = $fs->valid($this->file['tmp_name'],'path');
		$file_parts = pathinfo($uploadfile_name);
		switch($this->file['type']){
			case 'application/octet-stream':
				$sub = strtolower($file_parts['extension']);
				switch($sub){
					case 'xls':
					case 'xlsx':
						if($this->mode!=TagImportModeEnum::Dictionary){
							$ee->Error('406.91');
						}
						$result = $this->ImportTagByExcel($uploadfile_tmpname);
						$json = new Services_JSON();
						echo $json->encode($result);
						ob_flush();flush();
						break;
					case 'tag':
					case 'dic':
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
				break;
			case 'application/vnd.ms-excel':
				if($this->mode!=TagImportModeEnum::Dictionary){
					$ee->Error('406.91');
				}
				$result = $this->ImportTagByExcel($uploadfile_tmpname);
				$json = new Services_JSON();
				echo $json->encode($result);
				ob_flush();flush();
				break;
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
				if($this->mode!=TagImportModeEnum::Tags){
					$ee->Error('406.91');
				}
				$TagTree = new TagTree();
				$input = $TagTree->formatString($file_content);
				if(!$TagTree->chkFormat($input)){
					$ee->Error('406.91');
				}
				$TagTree->loadDB();
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
				if($this->mode!=TagImportModeEnum::Dictionary){
					$ee->Error('406.91');
				}
				$TagDocument = new TagDocument();
				$input = $TagDocument->formatString($file_content);
				if(!$TagDocument->chkFormat($input)){
					$ee->Error('406.91');
				}

				//$TagDocument->loadDictionaryDB();
				$TagDocument->loadDictionaryString($input);
				if($TagDocument->hasDispute()){
					$str = $TagDocument->exportDictionaryString(ListTypeEnum::Dispute,true);
					$ee->add('content',$str);
					$ee->Error('406.92');
				}else{
					$hasUpdate = $TagDocument->saveDictionaryDB();
					if($hasUpdate){
						$str = $TagDocument->exportDictionaryString(ListTypeEnum::Dispute,true);
						$ee->add('content',$str);
					}
					$ee->Message('200');
				}
		}
	}

	/**************************************
	step to import tag by excel
	1. first row is parent tag.
	2. rows over 2nd row are children tags.
	3. all value must have tag keys.
	**************************************/
	public function ImportTagByExcel($uploadfile_tmpname){
		global $ee;
		global $db;
		global $fs;
		$data = array();
		$dataheader = array('bookid');
		$report = array();
		$parentkey = array();

		$objReader = new PHPExcel_Reader_Excel5;
		$objPHPExcel = $objReader->load($uploadfile_tmpname);
		$sheet = $objPHPExcel->getSheet(0); // get first sheet(start from 0)
		$highestRow = $sheet->getHighestRow(); // get total rows
		$highestCol = $sheet->getHighestColumn(); // get total cols
		$highestCol = PHPExcel_Cell::columnIndexFromString($highestCol);

		$TagTree = new TagTree();
		$TagTree->loadDB();
		//header, parent tag
		for($i=1;$i<$highestCol;$i++){
			$mapping_value = $fs->valid($sheet->getCellByColumnAndRow($i, 1)->getValue(),'content');
			if(empty($mapping_value)){
				//error empty header
				$ee->add('title','Header field has something wrong!');
				$ee->add('msg','Missing field name.');
				$ee->Error('406.91');
			}
			$obj = $TagTree->getNodeByValue($mapping_value);
			if(empty($obj)){
				$_obj = $TagTree->getNode($mapping_value);
				if(empty($_obj)){
					//error header tag is not exist
					$ee->add('title','Header field has something wrong!');
					$ee->add('msg',sprintf('Field name %s is not a tag or tag key. Please add tag first.',$mapping_value));
					$ee->Error('406.91');
				}
				$mapping_value = $_obj->data['val'];
				$obj = array($_obj->key=>$_obj);
			}elseif(count($obj)>1){
				//more than one tag named $mapping_value
				$ee->add('title','Header field has something wrong!');
				$ee->add('msg','more than one tag named '.$mapping_value.'.');
				$ee->Error('406.91');
			}
			$keys = array_keys($obj);
			$parentkey[] = $keys[0];
			$dataheader[] = sprintf('%skey(%s)',$mapping_value,$keys[0]);
			$dataheader[] = $mapping_value;
		}

		$book = new book($db);
		//tags value
		for($j=2;$j<=$highestRow;$j++){
			$arr = array();
			for($i=0;$i<$highestCol;$i++){
				$mapping_value = $fs->valid($sheet->getCellByColumnAndRow($i, $j)->getValue(),'content');
				if(empty($mapping_value)){
					//error empty header
				}
				if($i==0){
					//dockey
					$row = $book->getByKey($mapping_value);
					if(empty($row)){
						$report[]=array('row'=>$j-2,'col'=>0,'comment'=>array('value'=>'book is not exist'));
					}
				}else{
					$obj = $TagTree->getNodeByValue($mapping_value);
					if(empty($obj)){
						//error header tag is not exist
						$_obj = $TagTree->getNode($mapping_value);
						if(empty($_obj)){
							$report[]=array('row'=>$j-2,'col'=>$i*2-1,'comment'=>array('value'=>'tag is not exist'));
							$arr[] =  '';
						}else{
							$mapping_value = $_obj->data['val'];
							$arr[] = $_obj->key;
						}
					}elseif(count($obj)>1){
						$options=array();
						$childkey = false;
						foreach($obj as $n){
							if($parentkey[$i-1] == $n->parent->key){
								$childkey=$n->key;
								$options[]=$n->key;
							}
						}
						//report
						if($childkey===false){
							$report[]=array('row'=>$j-2,'col'=>$i+1,'comment'=>array('value'=>'tag is not exist'));
						}else{
							if(count($options)>1){
								$report[]=array('row'=>$j-2,'col'=>$i+1,'comment'=>array('value'=>'tag has multi options'),'options'=>$options);
							}
							$arr[] = $childkey;
						}
					}else{
						reset($obj);
						$n = current($obj);
						if($parentkey[$i-1] != $n->parent->key){
							$report[]=array('row'=>$j-2,'col'=>$i+1,'comment'=>array('value'=>sprintf('tag is not child tag of %s',$parentkey[$i-1])));
						}
						$keys = array_keys($obj);
						$arr[] = $keys[0];
					}
				}
				$arr[] = $mapping_value;
			}
			$data[] = $arr;
		}
		//var_dump(array('code'=>'200.43','data'=>$data,'dataheader'=>$dataheader,'report'=>$report));
		return array('code'=>'200.43','data'=>$data,'dataheader'=>$dataheader,'report'=>$report);
	}
}
?>
