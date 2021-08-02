<?PHP
function execRegistStep1(){
	global $fs;
	global $db;
	global $tpl;
	global $ee;
	$token = $fs->valid($_POST['token'],'sessionid');
	$code = $fs->valid($_POST['activecode'],'activecode');

	if(!empty($token)){
		$_SESSION['_device']='mobile';
	}

	if(!empty($code)){
		if(ActiveCodeManager::isRegist($code)){
			echo "<script>alert('".LANG_WARNING_ACTIVECODE_REGISTED."!');document.location.href=document.location.href;</script>";
			exit;
		}
		$_SESSION['regist_code_valid']=false;
		$buid = bssystem::getLoginBUID();
		$data = ActiveCodeManager::check($code);
		if(!empty($buid)){
			//re-apply check
			$json = json_decode($data['ac_data'],TRUE);
			$gid = $json['gid'];
			$activecode = new activecode($db);
			$num = $activecode->isReApply($buid,$gid);
			//try to apply trial course more than once
			if($num>0 && $json['trial']==1){
				echo "<script>alert('".LANG_WARNING_TRIAL_COURSE_APPLIED."');document.location.href=document.location.href;</script>";
				exit;
				//$ee->ERROR('406.17');
			}

			ActiveCodeManager::regist($code,$buid);
			$_SESSION['regist_code_valid']='step2valid';
			$_SESSION['regist_code']=$data['ac_code'];
			$path = WEB_URL.'/regist/step3/';
			header('Location: '.$path);exit;
		}

		if($data){
			$_SESSION['regist_code_valid']='step1valid';
			$_SESSION['regist_code']=$data['ac_code'];
			$_SESSION['regist_code_data']=$data['ac_data'];
			$path = WEB_URL.'/regist/step2/';
			header('Location: '.$path);exit;
		}
	}
	$header=VIEW_PATH.'include/header_regist.tpl';
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('token',$token);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('header_html', $tpl->fetch($header));
}
?>
