<?PHP
class MailManager{

	var $ReplayTo = array();
	var $APPLYTO2;
	function __construct(){
	}
	function setReplyTo($email_address){
		$this->ReplayTo[] = $email_address;
	}
	function setSubject($_subject){
		$this->subject = $_subject;
	}
	function setContent($_content){
		$this->content = $_content;
	}
	function setTargetID($_targetid){
		$this->TargetID = $_targetid;
	}
	function send(){
		global $db;
		mb_internal_encoding('UTF-8');
		
		//send code through mail
		//SMTP needs accurate times, and the PHP time zone MUST be set
		//This should be done in your php.ini, but this is how to do it if you don't have access to that
		//date_default_timezone_set('Asia/Taipei');
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
	
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
	
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;

		$mail->CharSet = 'utf-8';

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
		$mail->addCC('anderson@ttii.com.tw', 'Anderson');
		//$mail->addCC('support@ttii.com.tw', 'SUPPORT');
		
		foreach($this->ReplayTo as $ReplayTo){
			if(!empty($ReplayTo)){
				$mail->addAddress($ReplayTo, $ReplayTo);
			}
		}

		//Set the subject line
		//$mail->Subject = mb_convert_encoding(sprintf('[�Ʀ�Ϯ��]]�ɮ��ഫ���ѳq�� - %s',$pn),"utf-8","big5");

		$mail->Subject = $this->subject;
		$mail->Body = $this->content;

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML($content, MAIL_TEMPLATE_PATH);

		//Replace the plain text body with one created manually
		//$mail->AltBody = $content;
		
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');

		//send the message, check for errors
		$result = $mail->send();
		if ($result) {
			print_r("\nsend mail success");
			$maillog = new maillog($db);
			$data = array(
				'ML_TARGET' => MailTypeEnum::UploadQueue,
				'TARGET_ID' => (int)$this->TargetID,
				'ML_SUBJECT' => $this->subject,
				'ML_CONTENT' => $this->content
			);
			$maillog->insert($data);
		}
		return $result;
	}
}
?>
