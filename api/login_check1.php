<?PHP
	require_once dirname(__FILE__).'/../init/config.php';
	$init = new init('filter');
	$bookurl = $fs->valid($_POST['u'],'content');
//echo mb_detect_encoding($bookurl);exit;
	//$bookurl = mb_convert_encoding($bookurl,'big5','utf-8');
	$_buid = bssystem::getLoginBUID();
	$sessionid = session_id();
	if(!empty($_buid)){
		$bookurl = str_replace('webs@2/ebook/','webs@2/ebook/'.session_id().'/',$bookurl);
	}
	echo $bookurl;
?>
