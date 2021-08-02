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


***********************************************************************/

require_once(LIBS_PATH.'/PHPExcel/PHPExcel.php');
require_once(LIBS_PATH.'/PHPExcel/PHPExcel/Reader/Excel2007.php');
require_once(LIBS_PATH.'/PHPExcel/PHPExcel/Writer/Excel5.php');
require_once(PLUGIN_PATH.'/dataexport/class/ExportManager.class.php');
class ImportManager extends ExportManager{
	//ImportModeEnum 
	var $setting;

	var $has_error = false;
	var $bsid=0;
	var $_report = array();
	var $mode = ImportManagerModeEnum::Unknow;
	var $objReader;
	var $ErrorHandler;
	var $_sql = array();
	var $fields = array();
	var $HeaderDef;
	var $HeaderDefColMapping;
	var $tmpfolder;
	var $objPHPExcel;

	var $_data = array(
		'user' => array(
			'group'=>13,
			'account'=>7,
			'password'=>1,
			'name'=>5
		),
		'm_user' => array(
			'group'=>'g_id',
			'account'=>'bu_name',
			'password'=>'bu_password',
			'name'=>'bu_cname'	
		),
		'group' => array(
			'id'=>6,
			'name'=>5
		),
		'm_group' => array(
			'id'=>'g_id',
			'name'=>'g_name'
		),	
		'category' => array(
			'id'=>6,
			'parentcate'=>13,
			'name'=>5,
			'description'=>4,
			'order'=>4
		),
		'm_category' => array(
			'id'=>'c_id',
			'parentcate'=>'c_parent_id',
			'name'=>'c_name',
			'description'=>'c_description',
			'order'=>'c_order'
		),
		'book' => array(
			'id'=>6,
			'subcate'=>13,
			'name'=>5,
			'description'=>4,
			'cover'=>17,
			'open_link'=>5,
			'download_link'=>4,
			'order'=>4,
			'visible'=>4
		),
		'm_book' => array(
			'id'=>'b_id',
			'subcate'=>'c_id',
			'name'=>'b_name',
			'description'=>'b_description',
			'cover'=>'file_id',
			'open_link'=>'webbook_link',
			'download_link'=>'ibook_link',
			'order'=>'b_order',
			'visible'=>'b_status'
		),
		'manager' => array(
			'account'=>7,
			'password'=>1,
			'name'=>5
		),
		'm_manager' => array(
			'account'=>'u_name',
			'password'=>'u_password',
			'name'=>'u_cname'
		)
	);

	var $_ValidExpression = array(
		'id'=>'/^(\d{1,11})$/',						//digit only
		'account'=>'/^([a-zA-Z][\w]{3,15})$/',	//less 6 character, 20 character max. start and emd with a-zA-Z, a-zA-Z_ in between.
		'password'=>'/^(.{4,20})$/',	//any character between 6 and 20 times
		'name'=>'/^(.{1,255})$/',			//any character between 2 and 20 times. include chinese or other language code
		'order'=>'/^(\d{1,11})$/',					//digit only
		'parentcate'=>'/^(\d{1,11})$/',		//digit only
		'group'=>'/^([\d{1.11},]+)$/',			//any character between 1 and 20 times, comma seperate
		'subcate'=>'/^([\d{1.11},]+)$/',		//any character between 1 and 20 times, comma seperate
		'groupname'=>'/^(.{1,255})$/',//any character between 1 and 255 times
		'bookname'=>'/^(.{1,255})$/',	//any character between 1 and 255 times
		'description'=>'/.*/',				//any character
		'cover'=>'/^(\w+\.\w{3,4})$/',				//filename
		'open_link'=>'/^http:\/\/[\w\.]+\w+[\/.]*/',				//start with http://
		'download_link'=>'/^http:\/\/[\w\.]+\w+[\/.]*/',		//start with http://
		'visible'=>'/^([01])$/');			//0|1

	var $_ValidExpressionError = array(
	'en' => array(
		'id'=>'ID is not digit, or number is over 11-digits',
		'account'=>'Account must between 4~20, start with letter',
		'password'=>'Passeord must between 4~20 any character',
		'name'=>'Name is between 1~255 characters',
		'order'=>'Order is not digit, or number is over 11-digits',
		'parentcate'=>'ParentCate is not digit, or number is over 11-digits',
		'group'=>'Multi-digits with comma seperat',
		'subcate'=>'Multi-digits with comma seperat',
		'groupname'=>'Groupname is between 1~255 characters',
		'bookname'=>'Bookname is between 1~255 characters',
		'description'=>'',
		'cover'=>'Input filename with extension, without path',
		'open_link'=>'It is not a valid url',
		'download_link'=>'It is not a valid url',
		'visible'=>'Input 0 for false, 1 fot true'),
	'zh-tw' => array(
		'id'=>'ID不是數字，或超過11位數',
		'account'=>'帳號要介於6~20個字之間',
		'password'=>'密碼要介於6~20個字之間',
		'name'=>'名稱要介於1~255個字之間',
		'order'=>'Order不是數字，或超過11位數',
		'parentcate'=>'ParentCate不是數字，或超過11位數',
		'group'=>'請輸入用逗號分隔的數字',
		'subcate'=>'請輸入用逗號分隔的數字',
		'groupname'=>'請輸入1~255個字',
		'bookname'=>'請輸入1~255個字',
		'description'=>'',
		'cover'=>'請輸入不含路徑的檔名',
		'open_link'=>'請輸入合法的網址',
		'download_link'=>'請輸入合法的網址',
		'visible'=>'請輸入0為不顯示，1為顯示')
	);

	function __construct($bsid=0,$mode=ImportManagerModeEnum::Unknow){
		global $db;
		global $ee;

		$this->fields['PK'] = array();
		$this->fields['mapping'] = array();
		$this->fields['export'] = array();
		$this->fields['require'] = array();
		$this->fields['file'] = array();

		$this->objReader = new PHPExcel_Reader_Excel5;
		$this->mode=$mode;
		$this->bsid=$bsid;

		if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::INDIVIDUAL) && $mode!=ImportManagerModeEnum::MANAGER){
			$account = new account($db);
			$uid = $account->getUIDByBSID($this->bsid);
			if(empty($uid)){
				$this->has_error = true;
				$ee->Warning('404.13',false);
			}		
			$this->uid = $uid;
		}
	}

	function __destruct(){
		//cleanup tmp folder
		if(!empty($this->tmpfolder)){
			@unlink($this->tmpfolder.'.zip');
			common::rrmtmpdir($this->tmpfolder);
		}
	}
	
	function Import(){
		global $ee;
		$uploadfile = $_FILES['uploadedFile'];
		$file_parts = common::path_info($_FILES['uploadedFile']['name']);

		switch($_FILES['uploadedFile']['type']){
			case 'application/octet-stream':
				$sub = strtolower($file_parts['extension']);
				switch($sub){
					case 'zip':
						$mode=ImportManagerModeEnum::BOOK;
						break;
					case 'xls':
					case 'xlsx':
						$mode = $this->CheckFormat($uploadfile);
						break;
					default:
						$mode=ImportManagerModeEnum::Unknow;
						break;
				}
				break;
			case 'application/x-zip-compressed':
			case 'application/zip':
				$mode=ImportManagerModeEnum::BOOK;
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
			case ImportManagerModeEnum::BOOK;
				$filename = strtolower($file_parts['filename']);
				if($this->_validFilename(ImportManagerModeEnum::BOOK,$_FILES['uploadedFile'])){
					$this->has_error = true;
					echo $this->ErrorHandler->Error('406.30');
				}else{
					$this->tmpfolder = sys_get_temp_dir().'/'.time();
					$zip_file = $this->tmpfolder.'.zip';
					//unzip
					move_uploaded_file($_FILES["uploadedFile"]["tmp_name"],$zip_file);
					common::unzip($zip_file,$this->tmpfolder);
					//check book.xls exist
					if(is_file($this->tmpfolder.'/book.xls')){
						$uploadfile = array('name'=>'book.xls','tmp_name'=>$this->tmpfolder.'/book.xls');
					}elseif(is_file($this->tmpfolder.'/'.$filename.'.xls')){
						$uploadfile = array('name'=>$filename.'.xls','tmp_name'=>$this->tmpfolder.'/'.$filename.'.xls');
					}else{
						$this->has_error = true;
						$ee->Error('404.35');
					}
					$mode = $this->CheckFormat($uploadfile);
				}
				break;
			case ImportManagerModeEnum::USER:
			case ImportManagerModeEnum::GROUP:
			case ImportManagerModeEnum::CATEGORY:
			case ImportManagerModeEnum::MANAGER:
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
	function Export($mode, $filename=''){
		global $db;

		switch($mode){
			case ImportManagerModeEnum::GROUP:
				if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::INDIVIDUAL)){
					$condition = sprintf("bs_id=%u ",$this->bsid);
				}
				$this->HeaderDef = $this->_data['group'];
				if(empty($filename)) $filename='group_'.time();
				$group = new group($db);
				$data = $group->getList('',0,0,$condition);
				$obj = $this->_data['m_group'];
				break;
			case ImportManagerModeEnum::USER:
				if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::INDIVIDUAL)){
					$condition = sprintf("bs_id=%u ",$this->bsid);
				}
				$this->HeaderDef = $this->_data['user'];
				if(empty($filename)) $filename='user_'.time();
				$bookshelf_user = new bookshelf_user($db);
				$data = $bookshelf_user->getList('',0,0,$condition);
				$obj = $this->_data['m_user'];
				break;
			case ImportManagerModeEnum::CATEGORY:
				$condition = sprintf("bs_id=%u ",$this->bsid);
				$this->HeaderDef = $this->_data['category'];
				if(empty($filename)) $filename='category_'.time();
				$category = new category($db);
				$data = $category->getList('',0,0,$condition);
				$obj = $this->_data['m_category'];
				break;
			case ImportManagerModeEnum::BOOK:
				$this->HeaderDef = $this->_data['book'];
				//還不能取得正確的清單
				if(empty($filename)) $filename='book_'.time();
				$book = new book($db);
				$book->setBookStatus('all');
				$book->setBSID($this->bsid);

				$data = $book->getList('b_id',0,0,'');
				$pattern = '/^http:\/\/127.0.0.1:(\d+)\/hosts\/(\d+)\/(\d+)\/files\//';
				$patterns = array('/^http:\/\/127.0.0.1:\d+\/hosts\/\d+\/\d+\/files\//');
				$replacements = array('http://127.0.0.1:[PORT]/hosts/[UID]/[BSID]/files/');
				for($i=0;$i<count($data['result']);$i++){
					$webbook_link = $data['result'][$i]['webbook_link'];
					if(preg_match($pattern,$webbook_link,$webbook_matches)===1){
						$data['result'][$i]['webbook_link'] = preg_replace($patterns, $replacements, $webbook_link);
					}
					$ibook_link = $data['result'][$i]['ibook_link'];
					if(preg_match($pattern,$ibook_link,$ibook_matches)===1){
						$data['result'][$i]['ibook_link'] = preg_replace($patterns, $replacements, $ibook_link);
					}
				}
				
				$obj = $this->_data['m_book'];
				break;
			case ImportManagerModeEnum::MANAGER:
				$this->HeaderDef = $this->_data['manager'];
				if(empty($filename)) $filename='manager_'.time();
				$account = new account($db);
				$data = $account->getList();
				$obj = $this->_data['m_manager'];
				break;
		}

		$header_columns = array();
		foreach($obj as $key=>$val ){
			$header_columns[] = $key;
		}
		if(!$this->_parseHeader($header_columns)){
		}
		$arr_export = array_values($this->fields['export']);

		$this->xlsBOF($filename);
		$c=0;
		foreach($obj as $key=>$val ){
			$c++;
			$this->xlsWriteLabel(1,$c,$key);
		}
		$r=2;
		foreach($data['result'] as $datarow){
			$c=0;
			foreach($obj as $key=>$val){
				$c++;
				if(in_array($key,$arr_export)){
					switch($key){
						case 'id':
						case 'order':
						case 'visible':
						case 'parentcate':
							$this->xlsWriteNumber($r,$c,$datarow[$val]);
							break;
						case 'password':
						case 'account':
						case 'name':
						case 'group':
						case 'bookname':
						case 'cover':
							$this->xlsWriteLabel($r,$c,$datarow[$val]);
							break;
						case 'description':
							$this->xlsWriteMultiLine($r,$c,$datarow[$val]);
							break;
						case 'open_link':
						case 'download_link':
							$this->xlsWriteURL($r,$c,$datarow[$val]);
							break;
						case 'subcate':
							switch($mode){
								case ImportManagerModeEnum::BOOK:
									$sql=<<<SQL
select c_id
from bookshelf2_books_category
where b_id=%u
SQL;
									$sql = sprintf($sql,$datarow['b_id']);
									$arr = $db->get_results($sql);
									if(!empty($arr)){
										$str = implode(',',array_values($arr[0]));
										$this->xlsWriteLabel($r,$c,$str);
									}
								break;
							}
							break;
					}
				}else{
					$this->xlsWriteLabel($r,$c,'');
				}
			}
			$r++;
		}
		$this->xlsEOF();
		/*
    $this->_downloadHeader($filename);
    echo "<table border='1px'><tr>";
    foreach($obj as $key=>$val ){
            echo "<td>".$key."</td>";
    }
    echo "</tr>";

    $row=0;
    foreach($data['result'] as $datarow){
      echo "<tr>";
      foreach($obj as $key=>$val){
        echo "<td>";
        if(!empty($val)){
          $str = preg_replace("/\r\n/", "\t\n", $datarow[$val]);
          echo $str;
        }
        echo "</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
    */
	}

	function ValidData($file, $import=false){
		global $db;
		global $fs;
		global $ee;
		
		switch($this->mode){
			case ImportManagerModeEnum::GROUP:
				$this->HeaderDef = $this->_data['group'];
				$this->HeaderDefColMapping = $this->_data['m_group'];
				$obj = new group($db);
				$prefix='g_';
				$tsn='g.';
				break;
			case ImportManagerModeEnum::USER:
				$this->HeaderDef = $this->_data['user'];
				$this->HeaderDefColMapping = $this->_data['m_user'];
				$group = new group($db);
				$obj = new bookshelf_user($db);
				$prefix='bu_';
				$tsn='bu.';
				break;
			case ImportManagerModeEnum::CATEGORY:
				$this->HeaderDef = $this->_data['category'];
				$this->HeaderDefColMapping = $this->_data['m_category'];
				$obj = new category($db);
				$prefix='c_';
				$tsn='';
				break;
			case ImportManagerModeEnum::BOOK:
				$this->HeaderDef = $this->_data['book'];
				$this->HeaderDefColMapping = $this->_data['m_book'];
				$obj = new book($db);
				$prefix='b_';
				$tsn='';
				break;
			case ImportManagerModeEnum::MANAGER:
				$this->HeaderDef = $this->_data['manager'];
				$this->HeaderDefColMapping = $this->_data['m_manager'];
				$obj = new account($db);
				$prefix='u_';
				$tsn='a.';
				break;
			default:
				break;
		}
		$objPHPExcel = $this->objReader->load($file['tmp_name']);
		$sheet = $objPHPExcel->getSheet(0); // get first sheet(start from 0)
		$highestRow = $sheet->getHighestRow(); // get total rows
		$columns = array_keys($this->HeaderDefColMapping);

		//get header
		$colnum = count($this->HeaderDef);
		$columns = array();
		for($i=0;$i <= $colnum;$i++){
			$header_columns[] = $sheet->getCellByColumnAndRow($i, 1)->getValue();	
		}

		if(!$this->_parseHeader($header_columns)){
		}

		for ($row = 2; $row <= $highestRow; $row++) {
			$SettingEnum = 0;
			foreach($this->fields['PK'] as $key=>$val){
				//check has value
				$PK_value = $fs->valid($sheet->getCellByColumnAndRow($key, $row)->getValue(),'content');
				
				switch($this->mode){
					case ImportManagerModeEnum::GROUP:
					case ImportManagerModeEnum::CATEGORY:
					case ImportManagerModeEnum::BOOK:

	          if(empty($PK_value)){
              //insert
              $SettingEnum = ImportManagerSettingEnum::import_append;
	          }else{
	          	$condition = '%s%sid=%u';
	          	if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::INDIVIDUAL)){
	          		$condition .= ' and bs_id='.$this->bsid;
	          	}
              $data = $obj->getList('',0,0,sprintf($condition,$tsn,$prefix,$PK_value));
              $data = $data['result'][0];
              if($data[$prefix."id"]){
                //update
                $SettingEnum = ImportManagerSettingEnum::import_set;
                if($this->mode==ImportManagerModeEnum::BOOK){
                	$file_id = $data['file_id'];
                }
              }else{
	              //error, id not exist
	              $this->has_error = true;
	              $ee->Warning('424.217',false);
	              $this->_report[] = array(
	                      'row'=>$row,
	                      'column'=>$key,
	                      'value'=>$PK_value,
	                      'msg'=>sprintf('ID "%s" is not in the system. Please EXPORT again, or make sure ID is correct!',$PK_value));
              }
	          }
						break;
					case ImportManagerModeEnum::USER:
					case ImportManagerModeEnum::MANAGER:
						if(empty($PK_value)){
							//error, missing key
							$this->has_error = true;
							$ee->Warning('404.213',false);
							$this->_report[] = array(
								'row'=>$row,
								'column'=>$key,
								'value'=>$PK_value,
								'msg'=>sprintf('ID "%s" is not in the system. Please EXPORT again, or make sure ID is correct!',$PK_value));
						}else{
							switch($this->mode){
								case ImportManagerModeEnum::USER:
			          	$condition = "%sname='%s'";
			          	if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::INDIVIDUAL)){
			          		$condition .= ' and bs_id='.$this->bsid;
			          	}
		              $data = $obj->getList('',0,0,sprintf($condition,$prefix,$PK_value));
		              $data = $data['result'][0];
									break;
								case ImportManagerModeEnum::MANAGER:
									$data = $obj->getByName($PK_value);
									break;
							}
						}
						if($data){
							//update
							$SettingEnum = ImportManagerSettingEnum::import_set;
						}else{
							//insert
							$SettingEnum = ImportManagerSettingEnum::import_append;
						}
						break;
				}
				//make sure account is not the same in xls
				if(!empty($PK_value)){
					for ($j = $row+1; $j <= $highestRow; $j++) {
						$_val = $fs->valid($sheet->getCellByColumnAndRow($key, $j)->getValue(),'content');
						if($_val==$PK_value){
							$this->has_error = true;
							$ee->Warning('404.216',false);
							$this->_report[] = array(
								'row'=>$j,
								'column'=>$key,
								'value'=>$PK_value,
								'msg'=>sprintf('Have the same account "%s", row %s,%s!',$PK_value,$row,$j));
						}
					}
				}
			}
			if($SettingEnum==ImportManagerSettingEnum::import_append){
				foreach($this->fields['require'] as $key=>$val){
					$require_value = $fs->valid($sheet->getCellByColumnAndRow($key, $row)->getValue(),'content');
					if($require_value==''){
						$this->has_error = true;
						$ee->Warning('404.213',false);
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
							$ee->Warning('404.213',false);
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
							$ee->Warning('404.213',false);
							$this->_report[] = array(
								'row'=>$row,
								'column'=>$key,
								'value'=>$id,
								'msg'=>sprintf('Parent category id "%s" is not in the system. Please EXPORT again, or make sure ID is correct!',$id));
						}
					}
					//check if all id exists
					switch($this->mode){
						case ImportManagerModeEnum::CATEGORY:
							$category = new category($db);
							//should be only one id in parentid
							if(count($mapping_ids)!=1){
								//error, can't set multi parent_id
								$this->has_error = true;
								$ee->Warning('406.211',false);
								$this->_report[] = array(
									'row'=>$row,
									'column'=>$key,
									'value'=>$mapping_value,
									'msg'=>sprintf('Parent category id "%s" cannot be muliti. Please EXPORT again, or make sure ID is correct!',$mapping_value));
							}
							if(($mapping_value!='0') && ($mapping_value!='') && $allint){	
								$data = $category->getList('',0,0,'c_id in ('.$mapping_value.') and c_parent_id=0');
								if($data['total']==0){
									//id is not in db
									$this->has_error = true;
									$ee->Warning('404.213',false);
									$this->_report[] = array(
										'row'=>$row,
										'column'=>$key,
										'value'=>$mapping_value,
										'msg'=>sprintf('Parent category id "%s" is not in the system. Please EXPORT again, or make sure ID is correct!',$mapping_value));
								}
							}
							//id should not be same with parent id
							if($PK_value==$mapping_value){
								$this->has_error = true;
								$ee->Warning('406.214',false);
								$this->_report[] = array(
									'row'=>$row,
									'column'=>$key,
									'value'=>$mapping_value,
									'msg'=>sprintf('Parent category id "%s" should not be the same with id. Please EXPORT again, or make sure ID is correct!',$mapping_value));
							}
							//import_set should not change parent id
							if(($SettingEnum == ImportManagerSettingEnum::import_set) && (intval($mapping_value)>0)){
								$data = $category->getByID($PK_value);
								if($data){
									if($data['c_parent_id']!=$mapping_value){
										$this->has_error = true;
										$ee->Warning('406.215',false);
										$this->_report[] = array(
											'row'=>$row,
											'column'=>$key,
											'value'=>$mapping_value,
											'msg'=>sprintf('Can not change parent id',$mapping_value));
									}
								}
							}
							break;
						case ImportManagerModeEnum::BOOK:
							if($allint){
								$category = new category($db);
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
									$ee->Warning('404.213',false);
									$str_diff_id = implode(',',$diff);
									$this->_report[] = array(
									'row'=>$row,
									'column'=>$key,
									'value'=>$mapping_value,
									'msg'=>sprintf('Category id "%s" is not in the system. Please EXPORT again, or make sure ID is correct!',$str_diff_id));
								}
							}
							break;
						case ImportManagerModeEnum::USER:
							//can't update group
							if($SettingEnum == ImportManagerSettingEnum::import_set){
								if(!empty($mapping_value)){
									$this->has_error = true;
									$ee->Warning('406.218',false);
									$str_diff_id = implode(',',$diff);
									$this->_report[] = array(
										'row'=>$row,
										'column'=>$key,
										'value'=>$mapping_value,
										'msg'=>'Can not update group of user');
								}
							}
							if($allint){
								$group = new group($db);
								$data = $group->getList('',0,0,'g.g_id in ('.$mapping_value.')');
								$gid  = array();
								foreach($data['result'] as $r){
									$gid[] = $r['g_id'];
								}
								//see if any id not in db
								$diff = array_diff($mapping_ids,$gid);
								if(!empty($diff)){
									//id is not exist
									$this->has_error = true;
									$ee->Warning('404.213',false);
									$str_diff_id = implode(',',$diff);
									$this->_report[] = array(
										'row'=>$row,
										'column'=>$key,
										'value'=>$mapping_value,
										'msg'=>sprintf('One of the group id "%s" is not in the system. Please EXPORT again, or make sure ID is correct!',$str_diff_id));
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
				if(!empty($value) && !$this->_validColumn($val,$value)){
					if(($SettingEnum == ImportManagerSettingEnum::import_set) && in_array($val,$this->fields['PK'])){
					}else{
						$this->has_error = true;
						$ee->Warning('406.211',false);
						$this->_report[] = array(
							'row'=>$row,
							'column'=>$key,
							'value'=>$value,
							'msg'=>$this->_ValidExpressionError['zh-tw'][$val]);
					}
				}
			}
			if($allempty){
				$this->has_error = true;
				$ee->Warning('500.41',false);
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
						$ee->Warning('406.62',false);						
						$this->_report[] = array(
							'row'=>$row,
							'column'=>$key,
							'value'=>$value,
							'msg'=>sprintf('Cover "%s" cannot contain path!',$value));
					}
					$cover = $this->tmpfolder.'/covers/'.$value;
					$cover_tmp = $this->tmpfolder.'/'.$value;
					if(!is_file($cover)){
						$this->has_error = true;
						$ee->Warning('404.35',false);
						$this->_report[] = array(
							'row'=>$row,
							'column'=>$key,
							'value'=>$value,
							'msg'=>sprintf('Cover "%s" is missing!',$value));
					}else{
						//create covert to db
						$account = new account($db);
						$data = $account->getAccountByBSID($this->bsid);
						$uid = $data['u_id'];
						//make sure is proper bookshelf(has uid);
						if(empty($uid)){
							$this->has_error = true;
							$ee->Warning('406.90',false);	
						}
					}
				}
			}

			if($import){
				switch($SettingEnum){
					case ImportManagerSettingEnum::import_append:
						$data = array();
						for($i=0;$i < $colnum;$i++){
							$value = $fs->valid($sheet->getCellByColumnAndRow($i, $row)->getValue(),'content');
							$col_name = $this->fields['all'][$i];
							$db_col_name = $this->HeaderDefColMapping[$col_name];

							if($value!=''){
								$data[$db_col_name] = $value;
							}else{
								$data[$db_col_name] = '';
							}
						}
						if(!$this->has_error){
							$col_PK = array_values($this->fields['PK']);
							$col_name_PK = $this->HeaderDefColMapping[$col_PK[0]];

							switch($this->mode){
								case ImportManagerModeEnum::BOOK:
									copy($cover,$cover_tmp);
									$f = BookshelfManager::create_cover($cover_tmp,$uid,$this->bsid);
									$data['file_id'] = $f['id'];
									$data['bs_id'] = $this->bsid;

									//replace params to bookshelf settings
									$data['webbook_link'] = $this->_setLink($data['webbook_link']);
									$data['ibook_link'] = $this->_setLink($data['ibook_link']);

									unset($data[$col_name_PK]);
									break;
								case ImportManagerModeEnum::GROUP:
									unset($data[$col_name_PK]);
									if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::INDIVIDUAL)){
										$data['bs_id'] = $this->bsid;
									}
									break;
								case ImportManagerModeEnum::USER:
									//$data['g_id'] = array($data['g_id']);
									if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::INDIVIDUAL)){
										//$data['bs_id'] = $this->bsid;
									}
									$data['bu_password'] = md5($data['bu_password']);
									break;
								case ImportManagerModeEnum::CATEGORY:
									$data['bs_id'] = $this->bsid;
									if(empty($data['c_order'])) $data['c_order']=0;
									if(empty($data['c_parent_id'])) $data['c_parent_id']=0;
									unset($data[$col_name_PK]);
									break;
								case ImportManagerModeEnum::MANAGER:
									$data['u_password'] = base64_encode($data['u_password']);
									break;
							}
							//only get first PK
							$obj->insert($data,$this->bsid);
						}
						break;
					case ImportManagerSettingEnum::import_set:
						$data = array();
						foreach($this->fields['all'] as $i=>$val){
							$value = $fs->valid($sheet->getCellByColumnAndRow($i, $row)->getValue(),'content');
							//check less 1 field has value
							if(array_key_exists($i,$this->fields['PK'])){
								$id = $value;
							}elseif(!empty($value)){
								$col_name = $this->fields['all'][$i];
								$db_col_name = $this->HeaderDefColMapping[$col_name];
								$data[$db_col_name] = $value;
							}
						}
						if(!$this->has_error){
							switch($this->mode){
								case ImportManagerModeEnum::BOOK:
									$f = BookshelfManager::update_cover($cover,$file_id,$uid,$this->bsid);
									if($f){
										//not set image
										$data['file_id'] = $f['id'];
									}
									$data['bs_id'] = $this->bsid;

									//replace params to bookshelf settings
									$data['webbook_link'] = $this->_setLink($data['webbook_link']);
									$data['ibook_link'] = $this->_setLink($data['ibook_link']);

									$obj->update($id,$data);
									break;
								case ImportManagerModeEnum::GROUP:
									if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::INDIVIDUAL)){
										//$data['bs_id'] = $this->bsid;
									}
									$obj->update($id,$data);
									break;
								case ImportManagerModeEnum::USER:
									//$data['g_id'] = array($data['g_id']);
									if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::INDIVIDUAL)){
										//$data['bs_id'] = $this->bsid;
									}
									unset($data['g_id']);
									if(!empty($data['bu_password'])){
										$data['bu_password'] = md5($data['bu_password']);
									}
									$obj->updateByName($id,$data);
									break;
								case ImportManagerModeEnum::CATEGORY:
									$obj->update($id,$data);
									break;
								case ImportManagerModeEnum::MANAGER:
									if(!empty($data['u_password'])){
										$data['u_password'] = base64_encode($data['u_password']);
									}
									$obj->updateByName($id,$data);
									break;
							}
						}
						break;
				}
			}
		}
		if($import && !$this->has_error){
			echo $ee->Message('200');
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
		if($this->_validFilename(ImportManagerModeEnum::GROUP, $file['name'])){
			$mode = ImportManagerModeEnum::GROUP;
			$tar = $this->_data['group'];
		}
		if($this->_validFilename(ImportManagerModeEnum::USER, $file['name'])){
			$mode = ImportManagerModeEnum::USER;
			$tar = $this->_data['user'];
		}
		if($this->_validFilename(ImportManagerModeEnum::CATEGORY, $file['name'])){
			$mode = ImportManagerModeEnum::CATEGORY;
			$tar = $this->_data['category'];
		}
		if($this->_validFilename(ImportManagerModeEnum::BOOK, $file['name'])){
			$mode = ImportManagerModeEnum::BOOK;
			$tar = $this->_data['book'];
		}
		if($this->_validFilename(ImportManagerModeEnum::MANAGER, $file['name'])){
			$mode = ImportManagerModeEnum::MANAGER;
			$tar = $this->_data['manager'];
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
				if($this->_hasSetting($code,ImportManagerSettingCodeEnum::_require)){
					$this->fields['require'][$i] = $val;
				}
				if($this->_hasSetting($code,ImportManagerSettingCodeEnum::_PK)){
					$this->fields['PK'][$i] = $val;
				}
				if($this->_hasSetting($code,ImportManagerSettingCodeEnum::_export)){
					$this->fields['export'][$i] = $val;
				}
				if($this->_hasSetting($code,ImportManagerSettingCodeEnum::_mapping)){
					$this->fields['mapping'][$i] = $val;
				}
				if($this->_hasSetting($code,ImportManagerSettingCodeEnum::_file)){
					$this->fields['file'][$i] = $val;
				}
				$this->fields['all'][$i] = $val;
				$i++;
			}
		}
		//one PK field
		if(count($this->fields['PK'])>1) return false;
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
	private function _validFilename($mode,$filename){
		$file_parts = common::path_info($filename);
		foreach($file_parts as $key=>$val){
			$file_parts[$key] = strtolower($val);
		}
		switch($mode){
			case ImportManagerModeEnum::USER:
				return ((strpos($file_parts['filename'],'user')===0) && ($file_parts['extension']=='xls'));
				break;
			case ImportManagerModeEnum::GROUP:
				return ((strpos($file_parts['filename'],'group')===0) && ($file_parts['extension']=='xls'));
				break;
			case ImportManagerModeEnum::BOOK:
				return ((strpos($file_parts['filename'],'book')===0) && (($file_parts['extension']=='xls') || ($file_parts['extension']=='zip')));
				break;
			case ImportManagerModeEnum::CATEGORY:
				return ((strpos($file_parts['filename'],'cate')===0) && ($file_parts['extension']=='xls'));
				break;
			case ImportManagerModeEnum::MANAGER:
				return ((strpos($file_parts['filename'],'manager')===0) && ($file_parts['extension']=='xls'));
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
