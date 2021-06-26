<?PHP
/***********************************************************************
ImportManager class has 2 methodes - set / append.
Currently, ImportManager support group, user, category, book, manager,
group & user's relation settings, and cate & book's relation settings.
Modes depend on column `id` or `account` empty or has value.
Set mode allow you to modify recoeds. Append mode allow you add new recoed.
Class won't erase record, delete need by manul in backend.

MEMBER_MODE
true: groups & users get from webservice/ldap/OpenID, or create by webadmin
webadmin - create MANAGER, GROUP, USER
manager - create CATEGORY, BOOK
false: manager create groups and users.
webadmin - create MANAGER
manager - create GROUP, USER, CATEGORY, BOOK

user / group
describtion: create / update managers, groups, usrs
function;
1. insert users, create belong group
2. update users(xls is download from site)
3. update group(xls is download from site)
multi-relation:
one user mapping multi groups rows.

book / category
describtion: mantainence category & book.
function;
1. insert books, create belong category
2. update books(xls is download from site)
3. update category(xls is download from site)
multi-relation:
one book mapping multi categories rows.

Relation Mode: set / append
depend on with/without column id/account
append mode must contain require(value can't be empty) column
set mode set value in modify cell, leaves other blank

Logs:
1. summaries, set record numbers, append record numbers
2. Error & warning

ERROR & Warning: 
1. 406.210 => not pass regex validation
2. 406.211 => necessary column(s) not exist, or miss column(s) or columns in different order
 book cover, open_link, download_link, id

Files(xlsx):
1. file name as group.xlsx, user.xlsx, category.xlsx, book.xlsx, manager.xlsx
2. only import first sheet(no matter what name of sheet)

Step:
1. valid filename, check ImportMode
2. fix data error(data foramt, require)
3. list difference and action / confirm & enter admin password
4. execute / processlog
5. show result(success) 

StatusCode
require: 1	- required column in import-append mode
PK: 2				- import-set mode must set this column. other columns are for update.
export: 4		- column without this flag will output blank
mapping: 8	- column record id with comma separate
file: 16		- column is file ref

Note: columns order should always in same order as below

# USER
	mode						import-set	import-append		export			[code]
	account						string			(require)				V						7
	group							_string			(require)				V						13
	password					_string			(require)			(blank)				1 (can set password, but cannot view)
	name							_string			(require)				V						5

ac	gn	pwd	name
X									error, missing key
V		V/F	 V		V		insert data
VF	(V)	(V) 	V		set data
VF	(V)	(V)		X		error, missing data

# GROUP
	mode						import-set	import-append		export			[code]
	id								int						X							V						6
	name							_string			(require)				V						5

id	sc	name
X							error, missing id
V		(V) (V)		error, id not exist
VF	(V)	 V		set data
VF	(V)	 X		error, missing required field


# CATEGORY
	mode						import-set	import-append		export			[code]
	id									int						X						V						6
	parentcate				_string			(require)			(blank)				9	(can't set parent cate)
	name							_string			(require)				V						5
	description				_string			_string					V						4
	order							_int				_int						V						4

id	pc	name	dc	or
X											error, missing id
		V			V	 (V) (V)	insert data
	 (X)	 (X) 					error, missing required field
V	 (V)	 (V) (V) (V)	error, id not exist
VF	V									error, can't change parentcate
VF 			  V	 (V) (V)	set data


# BOOK
	mode						import-set	import-append		export			[code]
	id									int						X						V						6
	subcate						_string			(require)				V						13
	name							_string			(require)				V						5
	description				_string			_string					V						4
	cover							_string			(require)			(blank)				17 (enter if need update book cover)
	open_link					_string			_string				(blank)				1
	download_link			_string			_string				(blank)				0
	order							_int				_int						V						4
	visible						_bool				_bool						V						4

id	sc	name	dc	ip	ol	dl	or	vi
		V		V		 (V)	V	 (V) (V) (V) (V)	insert data
	 (X) (X)		V	 (X)	V		V		V		V		error, insert data, missing required field
				V			V		V		V		V		V		V		warning, insert data, book doesn't set category
V	 (V) (V)	 (V) (V) (V) (V) (V) (V)	error, id not exist
VF 	V																	error, missing mc
VF (V) (V)	 (V) (V) (V) (V) (V) (V)	set data

# MANAGER
	mode						import-set	import-append		export			[code]
	account						string			(require)				V						7
	password					_string			(require)			(blank)				1 (can set password, but cannot view)
	name							_string			(require)				V						5

ac	pw	name
X								error, missing key
V		V			V			insert data
VF (V)	 (V)		set data
V/F(X)	 (X)		error, missing data


user start up flow
1. import account

MEMBER_MODE:CENTRALIZE
2. import group
3. export group(get id list)
> Create bookshelf, and enter
4. import category
5. import user
6. import book

> Create bookshelf, and enter
MEMBER_MODE:INDIVIDUAL
2. import group & category
3. export group & category (get id list)
4. import user
5. import book

function
一張表匯入同一張考卷 多間補習班(多個班級)
1. xls naming format: examdate_sekey_測驗名稱
		examdate - 8 digital, ex:20150701
2. xls contain multi bskey(補習班代號)
3. 1 exam 1 user 1 exam n score
4. webadmin can delete any import test


import step
check xls filename format
get quiz num info
insert bookshelf2_scanexam_test
insert se_key, quiz_num if not in bookshelf2_scanexam
insert bookshelf2_scanexam_quiz

read score
	- bskey mapping a bookshelf
	- check user is belong bookshelf
	- if bookshelf2_scanexam_quiz.seq_type is defined, valid answer format
	- insert bookshelf2_scanexam_user
	- insert bookshelf2_scanexam_exercise
***********************************************************************/

require_once(LIBS_PATH.'/PHPExcel/PHPExcel.php');
require_once(LIBS_PATH.'/PHPExcel/PHPExcel/Reader/Excel2007.php');
class ScoreImportManager{
	//ImportModeEnum 
	var $setting;

	//var $bsid=0;
	var $date;
	var $sekey;
	var $examname;
	var $has_error = false;
	var $_report = array();
	var $mode = ImportManagerModeEnum::Unknow;
	var $objReader;
	var $ErrorHandler;
	var $_sql = array();
	var $fields = array();
	var $keyidmapping = array();
	var $HeaderDef;
	var $HeaderDefColMapping;
	var $tmpfolder;
	var $objPHPExcel;

	var $_data = array(
		'scanexam' => array(
			'閱卷序號'=>0,
			'補習班代號'=>0,
			'學生代號'=>0,
			'試卷編號'=>0,
			'總分'=>0,
			'客觀題得分'=>0,
			'圖檔連結'=>0,
			'quiz' => array(1)
		),
		'm_scanexam' => array(
			'閱卷序號'=>'',
			'補習班代號'=>'',
			'學生代號'=>'',
			'試卷編號'=>'',
			'總分'=>'',
			'客觀題得分'=>'',
			'圖檔連結'=>'',
			'quiz'=>array(
				1=>'seq_reportid',
				2=>'seq_correct'
			)
		),
		'scanexam_user' => array(
			'閱卷序號'=>0,
			'補習班代號'=>67,
			'學生代號'=>35,
			'試卷編號'=>131,
			'總分'=>1,
			'客觀題得分'=>1,
			'圖檔連結'=>0,
			'answers'=>array(1)
		),
		'm_scanexam_user' => array(
			'閱卷序號'=>'',
			'補習班代號'=>'bs_key',
			'學生代號'=>'bu_name',
			'試卷編號'=>'se_key',
			'總分'=>'seu_points',
			'客觀題得分'=>'seu_percent',
			'圖檔連結'=>'',
			'answers'=>array('se_answers')
		),
	);

	var $_ValidRegex = array(
		'date' => '/^(20\d{6})$/',
		'sekey' => '/^([^_]{1,20})$/'
	);

	var $_ValidExpression = array(
		'補習班代號'=>'/^([A-D],\d{3})$/',
		'學生代號'	=>'/^([A-F],[1-4]\d{2})$/',
		'試卷編號'	=>'/^(\d{4})$/',
		'總分'			=>'/^(\d{1,3})$/',
		'客觀題得分'=>'/^(\d{1,3})$/');			//0|1

	var $_ValidExpressionError = array(
	'en' => array(
		'補習班代號'=>'Wrong format!',
		'學生代號'=>'Wrong format!',
		'試卷編號'=>'Wrong format!',
		'總分'=>'number only',
		'客觀題得分'=>'number only'),
	'zh-tw' => array(
		'補習班代號'=>'格式錯誤!',
		'學生代號'=>'格式錯誤!',
		'試卷編號'=>'格式錯誤!',
		'總分'=>'分數應為數字',
		'客觀題得分'=>'分數應為數字')
	);

	function __construct($bsid=0,$mode=ImportManagerModeEnum::Unknow){
		global $db;
		global $ee;
		$this->ErrorHandler = $ee;

		$this->fields['PK'] = array();
		$this->fields['mapping'] = array();
		$this->fields['export'] = array();
		$this->fields['require'] = array();
		$this->fields['file'] = array();
		$this->fields['uname'] = array();
		$this->fields['bskey'] = array();
		$this->fields['sekey'] = array();

		$this->objReader = new PHPExcel_Reader_Excel5;
		$this->mode=$mode;
		$this->bsid=$bsid;
	}

	function __destruct(){
	}
	
	function Import(){
		global $ee;
		$uploadfile = $_FILES['uploadedFile'];
		$file_parts = pathinfo($_FILES['uploadedFile']['name']);

		switch($_FILES['uploadedFile']['type']){
			case 'application/octet-stream':
				$sub = strtolower($file_parts['extension']);
				switch($sub){
					case 'xls':
					case 'xlsx':
						$mode = $this->CheckFormat($uploadfile);
						break;
					default:
						$mode=ImportManagerModeEnum::Unknow;
						break;
				}
				break;
			case 'application/vnd.ms-excel':
				$mode = $this->CheckFormat($uploadfile);
				break;
			default:
				$this->has_error = true;
				$ee->Error('406.30');
				break;
		}

		switch($mode){
			case ScoreImportManagerModeEnum::InfoacerExam1:
				break;
			default:
				$this->has_error = true;
				$ee->Error('406.30');
				break;
		}

		if($mode!=$this->mode){
			$this->has_error = true;
			$ee->Error('406.62');
		}
		$this->ValidData($uploadfile);
		if($this->has_error){
			$this->_Report($uploadfile);
		}else{
			$this->DoImport($uploadfile);
		}
	}

	function ValidData($file, $import=false){
		global $db;
		global $fs;
		$insert_examinfo = false;

		$bookshelf = new bookshelf(&$db);
		$bookshelf_user = new bookshelf_user(&$db);
		$scanexam_test = new scanexam_test(&$db);
		$scanexam_test_tag = new scanexam_test_tag(&$db);
		$scanexam_quiz = new scanexam_quiz(&$db);
		$scanexam_user = new scanexam_user(&$db);
		$scanexam_exercise = new scanexam_exercise(&$db);

		switch($this->mode){
			case ScoreImportManagerModeEnum::InfoacerExam1:
				$this->HeaderDef = $this->_data['scanexam_user'];
				$this->HeaderDefExamCol = $this->_data['m_scanexam'];
				$this->HeaderDefDataCol = $this->_data['m_scanexam_user'];
				break;
			default:
				break;
		}
		$objPHPExcel = $this->objReader->load($file['tmp_name']);
		$sheet = $objPHPExcel->getSheet(0); // get first sheet(start from 0)
		$highestRow = $sheet->getHighestRow(); // get total rows
		$highestColumn = $sheet->getHighestColumn(); // get total cols, do not allow comment column.
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

		//get header
		$columns = array();
		for($i=0;$i <= $highestColumnIndex;$i++){
			$header_columns[] = $sheet->getCellByColumnAndRow($i, 1)->getValue();	
		}

		if(!$this->_parseHeader($header_columns)){
		}

		for ($row = 3; $row <= $highestRow; $row++) {
			$SettingEnum = 0;
			$keyidmapping['uname']=array();
			$keyidmapping['bskey']=array();

			foreach($this->fields['PK'] as $key=>$val){
				//check has value
				//should have multi PK
				//check if is in db in bookshelf2_scanexam_user& xls
				$_field = $this->_data['m_scanexam_user'][$val];
				$PK_val = $fs->valid($sheet->getCellByColumnAndRow($key, $row)->getValue(),'content');
				if(empty($PK_val)){
					$this->has_error = true;
				}else{
					switch($_field){
						case 'bs_key':
							$col_bs_key = $key;
							$bs_key = $PK_val;
							$bsid = 0;
							if(!isset($keyidmapping['bskey'][$PK_val])){
								$data = $bookshelf->getByKey($PK_val);
								if($data){
									$bsid = $data['bs_id'];
									$keyidmapping['bskey'][$PK_val] = $bsid;
								}else{
									$this->has_error = true;
									$this->ErrorHandler->Warning('404.216');
									$this->_report[] = array(
										'row'=>$row,
										'column'=>$col_bs_key,
										'value'=>$uname,
										'msg'=>sprintf('Bookshelf key not set in bookshelf "%s", row %s,%s!',$bs_key,$row,$col_bs_key));									
								}
							}
							break;
						case 'bu_name':
							$col_uname = $key;
							$uname = $PK_val;
							$bu_id = 0;
							if(!isset($keyidmapping['uname'][$PK_val])){
								//check if user is in bookshelf, if no then show warning
								$data = $bookshelf_user->getByName($PK_val);
								if($data){
									$bu_id = $data['bu_id'];
									$keyidmapping['uname'][$PK_val] = $bu_id;
								}else{
									$this->has_error = true;
									$this->ErrorHandler->Warning('404.216');
									$this->_report[] = array(
										'row'=>$row,
										'column'=>$col_uname,
										'value'=>$uname,
										'msg'=>sprintf('User not in bookshelf "%s", row %s,%s!',$uname,$row,$col_uname));
								}
							}
							break;
						case 'se_key':
							$col_se_key = $key;
							$se_key = $PK_val;
							break;
					}
				}
				
			}
			$data = $scanexam_user->getByKey($bs_key,$se_key,$this->date,$bu_id);
			if($data){
				//update
				$SettingEnum = ImportManagerSettingEnum::import_set;
			}else{
				//insert
				$SettingEnum = ImportManagerSettingEnum::import_append;
			}

			for ($j = $row+1; $j <= $highestRow; $j++) {
				if(($bs_key == $fs->valid($sheet->getCellByColumnAndRow($col_bs_key, $j)->getValue(),'key'))	&&
					($se_key == $fs->valid($sheet->getCellByColumnAndRow($col_se_key, $j)->getValue(),'key')) &&
					($uname == $fs->valid($sheet->getCellByColumnAndRow($col_uname, $j)->getValue(),'name'))){
						$this->has_error = true;
						$this->ErrorHandler->Warning('404.216');
						$this->_report[] = array(
							'row'=>$j,
							'column'=>$col_bs_key,
							'value'=>$bs_key,
							'msg'=>sprintf('Have the same Prime Key "%s", row %s,%s!',$bs_key,$row,$j));
						$this->_report[] = array(
							'row'=>$j,
							'column'=>$col_se_key,
							'value'=>$se_key,
							'msg'=>sprintf('Have the same Prime Key "%s", row %s,%s!',$se_key,$row,$j));
						$this->_report[] = array(
							'row'=>$j,
							'column'=>$col_uname,
							'value'=>$uname,
							'msg'=>sprintf('Have the same Prime Key "%s", row %s,%s!',$uname,$row,$j));

				}
			}

			if($SettingEnum==ImportManagerSettingEnum::import_append){
				foreach($this->fields['require'] as $key=>$val){
					$require_value = $fs->valid($sheet->getCellByColumnAndRow($key, $row)->getValue(),'content');
					if($require_value==''){
						$this->has_error = true;
						$this->ErrorHandler->Warning('404.213');
						$this->_report[] = array(
							'row'=>$row,
							'column'=>$key,
							'value'=>$val,
							'msg'=>sprintf('Request column "%s" is empty!',$val));
					}
				}
			}
			foreach($this->fields['mapping'] as $key=>$val){
				$mapping_value = $fs->valid($sheet->getCellByColumnAndRow($key, $row)->getValue(),'content');
				if(!empty($mapping_value) || ($mapping_value=='0')){
					$allint=true;
					$mapping_ids = explode(',',$mapping_value);
					//check ids
					foreach($mapping_ids as $id){
						if(empty($id) && ($id!='0')){
							//error, id is empty
							$this->has_error = true;
							$this->ErrorHandler->Warning('404.213');
							$this->_report[] = array(
								'row'=>$row,
								'column'=>$key,
								'value'=>$id,
								'msg'=>sprintf('Parent category id "%s" is not in the system. Please EXPORT again, or make sure ID is correct!',$id));
						}
						if(!is_numeric($id)){
							//error, id is not valid
							$allint=false;
							$this->has_error = true;
							$this->ErrorHandler->Warning('404.213');
							$this->_report[] = array(
								'row'=>$row,
								'column'=>$key,
								'value'=>$id,
								'msg'=>sprintf('Parent category id "%s" is not in the system. Please EXPORT again, or make sure ID is correct!',$id));
						}
					}
					//check if all id exists
					switch($this->mode){
						case ScoreImportManagerModeEnum::InfoacerExam1:
							if($allint){
								$category = new category(&$db);
								$data = $category->getList('',0,0,'c_parent_id>0 and c_id in ('.$mapping_value.')');
								$cid  = array();
								foreach($data['result'] as $r){
									$cid[] = $r['c_id'];
								}
								//see if any id not in db
								$diff = array_diff($mapping_ids,$cid);
								if(!empty($diff)){
									//id is not exist
									$this->has_error = true;
									$this->ErrorHandler->Warning('404.213');
									$str_diff_id = implode(',',$diff);
									$this->_report[] = array(
									'row'=>$row,
									'column'=>$key,
									'value'=>$mapping_value,
									'msg'=>sprintf('Category id "%s" is not in the system. Please EXPORT again, or make sure ID is correct!',$str_diff_id));
								}
							}
							break;
						default:
							//warning
							$this->has_error = true;
							break;

					}
				}
			}
			$allempty = true;
			foreach($this->fields['all'] as $key=>$val){
				$value = $fs->valid($sheet->getCellByColumnAndRow($key, $row)->getValue(),'content');
				if(!empty($value) && !array_key_exists($key,$this->fields['PK'])) $allempty=false;
			}
			if($allempty){
				$this->has_error = true;
				$this->ErrorHandler->Warning('500.41');						
				$this->_report[] = array(
					'row'=>$row,
					'column'=>0,
					'value'=>$value,
					'msg'=>sprintf('No data to upadate!'));
			}
			foreach($this->fields['file'] as $key=>$val){
				$value = $fs->valid($sheet->getCellByColumnAndRow($key, $row)->getValue(),'content');
				//value cannot contain
				if(!empty($value)){
					if(strstr($value,'/')){
						$this->has_error = true;
						$this->ErrorHandler->Warning('406.62');						
						$this->_report[] = array(
							'row'=>$row,
							'column'=>$key,
							'value'=>$value,
							'msg'=>sprintf('Cover "%s" cannot contain path!',$value));
					}
					$cover = $this->tmpfolder.'/covers/'.$value;
					if(!is_file($cover)){
						$this->has_error = true;
						$this->ErrorHandler->Warning('404.35');
						$this->_report[] = array(
							'row'=>$row,
							'column'=>$key,
							'value'=>$value,
							'msg'=>sprintf('Cover "%s" is missing!',$value));
					}else{
						//create covert to db
						$account = new account(&$db);
						$data = $account->getAccountByBSID($this->bsid);
						$uid = $data['u_id'];
						//make sure is proper bookshelf(has uid);
						if(empty($uid)){
							$this->has_error = true;
							$this->ErrorHandler->Warning('406.90');	
						}
					}
				}
			}

			if($import){
				if($row==3){
					$scanexam_test->del($se_key,$this->date,$bs_key);
				}
				switch($SettingEnum){
					case ImportManagerSettingEnum::import_append:
					case ImportManagerSettingEnum::import_set:
						//insert bookshelf2_scanexam_user
						$_m = $this->HeaderDefExamCol['quiz'];
						$_mk = array_keys($_m);
						if(!$insert_examinfo){
							$arr_quiz = array();
							$data_test = array();
							$data_test['bs_key'] = $bs_key;
							$data_test['se_key'] = $se_key;
							$data_test['set_date'] = $this->date;
							$data_test['set_name'] = $this->examname;

							for($i=count($this->HeaderDefExamCol)-1;$i < $highestColumnIndex;$i++){
								$data_quiz = array();
								$data_quiz['se_key'] = $se_key;
								$data_quiz['seq'] = $i+1;
								for($j=0;$j<count($_m);$j++){
									$val = $fs->valid($sheet->getCellByColumnAndRow($i, $_mk[$j])->getValue(),'content');
									$data_quiz[$_m[$j+1]] = $val;
								}
								$arr_quiz[] = $data_quiz;
							}
						}
						$data_user = array();
						$data_user['set_date'] = $this->date;
						$dbfields = array_values($this->HeaderDefDataCol);
						for($i=0;$i < count($this->HeaderDefExamCol);$i++){
							if($dbfields[$i]!=''){
								$val = $fs->valid($sheet->getCellByColumnAndRow($i, $row)->getValue(),'content');
								switch($dbfields[$i]){
									case 'bu_name':
										$buid = $keyidmapping['uname'][$val];
										$data_user['bu_id'] = intval($bu_id);
										break;
									case 'bs_key':
									case 'se_key':
									case 'seu_points':
									case 'seu_percent':
										$data_user[$dbfields[$i]] = $val;
										break;
									default:
										break;
								}
							}
							$data_user['bs_key'] = $bs_key;
						}
						$arr_exercise = array();
						$n = count($this->HeaderDefExamCol);
						$correct_num=0;
						for($i=$n-1;$i < $highestColumnIndex;$i++){
							$val = $fs->valid($sheet->getCellByColumnAndRow($i, $row)->getValue(),'content');
							$data_exercise = array();
							$data_exercise['bs_key']=$bs_key;
							$data_exercise['se_key']=$se_key;
							$data_exercise['set_date']=$this->date;
							$data_exercise['bu_id']= intval($bu_id);
							$data_exercise['seq'] = $i+1;
							$data_exercise['see_answers'] = $val;
							if($arr_quiz[$i-$n+1]['seq_correct'] == $val){
								$data_exercise['see_result']='1';
								$correct_num++;
							}else{
								$data_exercise['see_result']='0';
							}
							$arr_exercise[] = $data_exercise;
						}
						$data_user['seu_correct'] = (string)$correct_num;

						if(!$this->has_error){
							$data = $scanexam_test->getByKey($se_key,$this->date);
							if($data) $insert_examinfo = true;
							if(!$insert_examinfo){
								$scanexam_test->insert($data_test);
								//add system tag
								$scanexam_test_tag->insertSystemTag($se_key,$this->date);
								if(!$scanexam_quiz->getByKey($se_key)){
									foreach($arr_quiz as $data_quiz){
										$scanexam_quiz->insert($data_quiz);
									}
								}
								$insert_examinfo = true;
							}

							$scanexam_user->insert($data_user);
							foreach($arr_exercise as $data_exercise){
								$scanexam_exercise->insert($data_exercise);
							}
						}
						break;
				}
			}
		}
		if($import && !$this->has_error){
			echo $this->ErrorHandler->Message('200');
		}
	}
	function _Report($file){
		/* return value example
    {data:[
      ["id","subcate","name","description","cover","open_link","download_link","order","visible"],
			["8","2","test3",'',"book.jpg","http://127.0.0.1:20038/hosts/1/3/files/1636141411610220/test.php",'',"1","1"],
			['',"2","test4",'',"book1.jpg","http://127.0.0.1:20038/hosts/1/3/files/1636141411610220/test.php",'',"1","1"]
    ],
    report:[
			{row: 1, col: 4, comment:"File not exists"}
		]};
		*/

    global $fs;
    //export json format
    $json = new Services_JSON();
    header('Content-Type: application/json; charset=utf-8');
    $_json = array();
    $_json['code'] = 406;
    $_json['data'] = array();
    $_json['report'] = array();
    if(count($this->fields['all'])>0){
      $_json['data'][] = array_values($this->fields['all']);
      $objPHPExcel = $this->objReader->load($file['tmp_name']);
      $sheet = $objPHPExcel->getSheet(0); // get first sheet(start from 0)
      $highestRow = $sheet->getHighestRow(); // get total rows
      for ($row = 2; $row <= $highestRow; $row++) {
        $v = array();
        foreach($this->fields['all'] as $key=>$val){
          $v[] = $fs->valid($sheet->getCellByColumnAndRow($key, $row)->getValue(),'content');
        }
        $_json['data'][] = $v;
      }
    }
    if(count($this->_report)>0){
      foreach($this->_report as $item){
      	$_json['report'][] = array('row'=>$item['row']-1,'col'=>$item['column'],'comment'=>$item['msg']);
      }
    }
    echo $json->encode($_json);
  }
	function DoImport($file){
		$this->ValidData($file,true);
	}
	function CheckFormat($file){
		global $ee;
		//check filename
		$mode = ScoreImportManagerModeEnum::Unknow;
		if($this->_validFilename(ScoreImportManagerModeEnum::InfoacerExam1, $file['name'])){
			$mode = ScoreImportManagerModeEnum::InfoacerExam1;
			$tar = $this->_data['infoacer'];
		}

		$objPHPExcel = $this->objReader->load($file['tmp_name']);
		$sheet = $objPHPExcel->getSheet(0); // 讀取第一個工作表(編號從 0 開始)
		$highestRow = $sheet->getHighestRow(); // 取得總列數
		$arr = array_keys($tar);
		for($i=0;$i<count($arr);$i++){
			$val = $sheet->getCellByColumnAndRow($i, 1)->getValue();
			if($val!=$arr[$i]){
				$this->has_error = true;
				$ee->Error('406.212');
			}
		}

		return $mode;
	}
	function _parseHeader($columns){
		$i = 0;
		foreach($columns as $val){
			if(!empty($val)){
				$code = $this->HeaderDef[strtolower($val)];
				if($this->_hasSetting($code,ScoreImportManagerSettingCodeEnum::_require)){
					$this->fields['require'][$i] = $val;
				}
				if($this->_hasSetting($code,ScoreImportManagerSettingCodeEnum::_PK)){
					$this->fields['PK'][$i] = $val;
				}
				if($this->_hasSetting($code,ScoreImportManagerSettingCodeEnum::_export)){
					$this->fields['export'][$i] = $val;
				}
				if($this->_hasSetting($code,ScoreImportManagerSettingCodeEnum::_mapping)){
					$this->fields['mapping'][$i] = $val;
				}
				if($this->_hasSetting($code,ScoreImportManagerSettingCodeEnum::_file)){
					$this->fields['file'][$i] = $val;
				}
				if($this->_hasSetting($code,ScoreImportManagerSettingCodeEnum::_uname)){
					$this->fields['uname'][$i] = $val;
				}
				if($this->_hasSetting($code,ScoreImportManagerSettingCodeEnum::_bskey)){
					$this->fields['bskey'][$i] = $val;
				}
				if($this->_hasSetting($code,ScoreImportManagerSettingCodeEnum::_sekey)){
					$this->fields['sekey'][$i] = $val;
				}
				$this->fields['all'][$i] = $val;
				$i++;
			}
		}
		//one mapping field
		if(count($this->fields['mapping'])>1) return false;
		return true;
	}
	private function _hasSetting($code,$check){
		return (($code & $check)>0);
	}
	private function _validColumn($col,$val){
		$pattern = $this->_ValidExpression[$col];
		preg_match($pattern, $val, $matches);
		return (count($matches)>0);
	}
	private function _validValue($col,$val){
		$pattern = $this->_ValidRegex[$col];
		preg_match($pattern, $val, $matches);
		return (count($matches)>0);		
	}

	private function _validFilename($mode,$filename){
		$file_parts = pathinfo($filename);
		foreach($file_parts as $key=>$val){
			$file_parts[$key] = strtolower($val);
		}

		list($examdate,$sekey,$examname) = explode('_',$file_parts['filename']);
		if(!$this->_validValue('date',$examdate)){
			$this->has_error = true;
			//echo $this->ErrorHandler->Error('406.62');
		}else{
			$this->date = $examdate;
		}
		if(!$this->_validValue('sekey',$sekey)){
			$this->has_error = true;
			//echo $this->ErrorHandler->Error('406.62');
		}else{
			$this->sekey = $sekey;
		}
		if(empty($examname)){
			$this->has_error = true;
			//echo $this->ErrorHandler->Error('406.62');
		}else{
			$this->examname = $examname;
		}

		switch($mode){
			case ScoreImportManagerModeEnum::InfoacerExam1:
				return (!$this->has_error && ($file_parts['extension']=='xls'));
				break;
		}
	}
	private function _setLink($link){
		global $db;
		list($host,$port) = explode(':',$_SERVER['HTTP_HOST']);
		$search = array('[PORT]','[UID]','[BSID]');
		$replace = array($port,$this->uid,$this->bsid);

		return str_replace($search,$replace,$link);
	}
}
?>
