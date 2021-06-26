<?php
function execTrial($g_id){
	global $db;
	global $fs;
	global $ee;

	$exceptlist = array('anderson@ttii.com.tw','support@ttii.com.tw');
	$_errmsg = '';
	mb_internal_encoding('UTF-8');
	$name = $fs->valid($_POST['name'],'name');
	$email = $fs->valid($_POST['email'],'email');
	
	$group = new group(&$db);
	$data = $group->getByID($g_id);

	//check if group[trial] not valid (!=1), redirect to main page 
	$gdata = json_decode(stripslashes($data['g_data']),true);
  if($gdata['trial']!='1'){
		header('Location:/');exit;
  }

	//limit check
	$bookshelf_user = new bookshelf_user(&$db);
	$count = $bookshelf_user->getCountByGID($g_id);
	
  if($gdata['limit']!='' && intval($gdata['limit'])<=intval($count)){
  	$_msg = LANG_TRIAL_CLASSFULL;
  }

	if($_POST && (empty($name) || empty($email))){
		$_msg = LANG_TRIAL_MISSIMG_PARAMETERS;
	}

	$activecode = new activecode(&$db);
	$hasApplyTrial=false;
	if(!in_array($email,$exceptlist)){
		$hasApplyTrial = $activecode->isApplyTrial($email,$g_id);
		if($hasApplyTrial){
			$_msg = LANG_TRIAL_HAS_APPLIED;
		}
	}

	if($_POST && empty($_msg)){
		//send code through mail
		//SMTP needs accurate times, and the PHP time zone MUST be set
		//This should be done in your php.ini, but this is how to do it if you don't have access to that
		date_default_timezone_set('Etc/UTC');
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
	
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
	
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
	
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
	
		//Set the hostname of the mail server
		$mail->Host = GMAIL_HOST;
		// use
		// $mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6
		
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = 587;
		
		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = 'tls';
		
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		
		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = GMAIL_USERNAME;
		
		//Password to use for SMTP authentication
		$mail->Password = GMAIL_PASSWORD;
		
		//Set who the message is to be sent from
		$mail->setFrom(GMAIL_FROM, GMAIL_FROMNAME);
		
		//Set an alternative reply-to address
		$mail->addReplyTo(GMAIL_APPLYTO, GMAIL_APPLYTONAME);
		
		//Set who the message is to be sent to
		$encode_name = mb_encode_mimeheader($name, "UTF-8");
		$mail->addAddress($email, $encode_name);
		
		//Set the subject line
		$mail->Subject = mb_encode_mimeheader(sprintf('課程[%s]體驗啟用碼 %s',$data['g_name'],date('Y-m-d')));
	
		$ActiveCodeManager = new ActiveCodeManager();
		$code = $ActiveCodeManager->getCode();
		$content = file_get_contents(MAIL_TEMPLATE_PATH.'/mail_trial');
		$content = str_replace('@COURSENAME@',$data['g_name'],$content);
		$content = str_replace('@CODE@',$code,$content);
	
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($content, MAIL_TEMPLATE_PATH);
	
		//Replace the plain text body with one created manually
		//$mail->AltBody = '';
		
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');
	
		//send the message, check for errors
		if ($mail->send()) {
			$sql=<<<SQL
select bs_id
from BOOKSHELF2_GROUPS_CATEGORY gc
join BOOKSHELF2_CATEGORY c on(gc.c_id=c.c_id)
where g_id=%u
group by g_id,bs_id
limit 1
SQL;
			$sql=sprintf($sql,$g_id);
			$rs = $db->query_first($sql);
			$bsid = intval($rs['bs_id']);
	
			$data1 = array();
			$data1['name'] = $name;
			$data1['email'] = $email;
			$data1['ac_code'] = $code;
			$data1['ac_term'] = 1;
			$data1['bs_id'] = $bsid;
			$strjson = json_encode(array('trial'=>true,'username'=>$name,'gid'=>$g_id));
			$data1['arr_gid'] = array($g_id);
			$data1['ac_data'] = $strjson;
			$activecode = new activecode(&$db);
			$activecode->insert($data1);
	
			$_msg = LANG_TRIAL_MSG;
		}else{
		  $_errmsg = LANG_TRIAL_SENDMAIL_ERROR.' '.$mail->ErrorInfo;
		}
	}

	if(empty($uid)) $uid=0;
	if(empty($bs_id)) $bs_id=0;
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('data',$data);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('uid',$uid);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('bsid',$bs_id);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('errmsg',$_msg);
}
?>
