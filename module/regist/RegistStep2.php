<?PHP
function execRegistStep2(){
	global $fs;
	global $db;
	global $tpl;
	
	//$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('header_html', $tpl->fetch(VIEW_PATH.'include/header_not_user.tpl'));

	//get plan info
	if($_SESSION['regist_code_valid']!='step1valid' || empty($_SESSION['regist_code_data']) || empty($_SESSION['regist_code'])){
		$path = WEB_URL.'/regist/step1/';
		header('Location: '.$path);
	}else{
		$json = json_decode($_SESSION['regist_code_data'],TRUE);
		if(!empty($json)){
			$groups = $json['gid'];
			$arr_gid = explode(',',$groups);
			$group = new group(&$db);
			$plans = array();
			foreach($arr_gid as $gid){
				$data = $group->getByID($gid);
				$plans[] = $data['g_name'];
			}

			$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('plans',$plans);
		}		
	}

	if($_POST){
		$name = $fs->valid($_POST['name'],'name');
		$birthday = $fs->valid($_POST['birthday'],'date');
		$email = $fs->valid($_POST['email'],'email');
		$account = $fs->valid($_POST['account'],'acc');
		$pwd = $fs->valid($_POST['password'],'pwd');
		$career = (int) $fs->valid($_POST['career'],'num');
		$receive_mail = (int) $fs->valid($_POST['receive_mail'],'bool');
	
		if(empty($name) || empty($birthday) || empty($email) || empty($account) || empty($pwd)){
			echo "<script>alert('".LANG_WARNING_MISSING_PARAMETERS."');document.location.href=document.location.href;</script>";
			exit;
		}

		//email check.
		$email = strtolower($email);
	
		$bookshelf_user = new bookshelf_user(&$db);
		$data = $bookshelf_user->getByEmail($email);
		if(!empty($data) && !in_array(array('anderson@ttii.com.tw'),$email)){
			echo "<script>alert('".LANG_WARNING_EMAIL_OCCUPIED."');document.location.href=document.location.href;</script>";
			exit;
			//$ee->ERROR('406.16');
		}
	
		$json = json_decode($_SESSION['regist_code_data'],TRUE);
		$groups = $json['gid'];
		//$arr_gid = explode(',',$groups);
	
		$data = array();
		$data['bu_name'] = $account;
		$data['bu_cname'] = $name;
		$data['bu_password'] = md5($pwd);
		$data['bu_status'] = 1;
		$data['bu_birth'] = $birthday;
		$data['bu_email'] = $email;
		$data['bu_career'] = $career;
		//$data['g_id'] = $groups;
		$data['bu_receive_mail'] = $receive_mail;
	
		$bookshelf_user = new bookshelf_user(&$db);
		$rs = $bookshelf_user->getByName($account);
		if(!empty($rs)){
			echo "<script>alert('".LANG_WARNING_ACCOUNT_OCCUPIED."');document.location.href=document.location.href;</script>";
			exit;
		}
		$buid = $bookshelf_user->insert($data,true);
		$buid = intval($buid);
		if($buid){
			//add regist date
			ActiveCodeManager::regist($_SESSION['regist_code'],$buid);
			//login
			BookshelfManager::UserLogin($buid, $account, $name);
			$_SESSION['regist_code_valid']='step2valid';
			$path = WEB_URL.'/regist/step3/';
			header('Location: '.$path);
		}
	}
	$header=VIEW_PATH.'include/header_regist.tpl';
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('header_html', $tpl->fetch($header));
}
?>
