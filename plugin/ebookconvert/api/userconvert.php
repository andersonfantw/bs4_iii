<?PHP
/*
1. Àx¦sÀÉ®×
*/ 
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('filter');

define('USERCONVERT_APIPath','http://cloudbook.cyberhood.net/cloudbook/convertbook0.php');
define('USERCONVERT_domain','cloudbook.cyberhood.net');
define('USERCONVERT_port','80');

define('USERCONVERT_SAVEPATH','/home/ebook/cloud_convert/');
define('USERCONVERT_KEY','FHFJTYE');
define('USERCONVERT_PASS','KFSRYYE');

$bs_id = $fs->valid($_GET['bs'],'id');
$cate2 = $fs->valid($_GET['c'],'id');

/*
$key = $fs->valid($_GET['key'],'key');
$pass = $fs->valid($_POST['pass'],'key');

if(($key!=USERCONVERT_KEY) || ($pass!=USERCONVERT_PASS)){
	$data['code']='500';
	$data['msg']='access token is invalid';
	$json = json_encode($data);
	echo $json;
	exit;
}
*/

if(!LicenseManager::IsBookshelfLicenseValid()){
	$ee->add('msg','License is expired! Click [yes] to see more infomation, click [cancel] to return page.');
	$ee->add('link','http://cloudbook.cyberhood.net/cloudbook/licensebuy_02.php?service_id='.wonderbox_id);
	$ee->ERROR('401.26');
	exit;
}

if(!$_FILES){
	$ee->ERROR('400.32');
	exit;
}

$timestamp = time();
$serviceid = wonderbox_id;
$filetype = $_FILES['uploadedFile']['type'];
$bookname = str_before($_FILES['uploadedFile']["name"],'.pdf');
$booksize = 0;
if(isset($_FILES['uploadedFile']['size'])){
	$booksize = intval($_FILES['uploadedFile']['size'])/1024;
}

if(wonderbox_id==''){
	$data['code']='503';
	$data['msg']='program must execute on wonderbox';
	$json = json_encode($data);
	echo $json;
	exit;
}

if($filetype!='application/pdf'){
	$data['code']='504';
	$data['msg']='file type could not be obtained';
	$json = json_encode($data);
	echo $json;
	exit;
}

$bookpath = USERCONVERT_SAVEPATH.wonderbox_id.'/'.$timestamp.'.pdf';
$path = USERCONVERT_SAVEPATH.wonderbox_id;
if(!file_exists($path)){
        mkdir($path);
}

move_uploaded_file($_FILES['uploadedFile']['tmp_name'],$bookpath);
$bookpages = count_pages($bookpath);

$param = array("service_id"=>wonderbox_id,
								"book_name"=>$bookname,
								"book_size"=>$booksize,
								"book_pages"=>$bookpages,
								"book_path"=>$bookpath,
								"adm_key"=>$_SESSION['uid'],
								"bs_key"=>$bs_id,
								"cate_key"=>$cate2);
$p = http_build_query($param);
$result = Connect('POST', $p);
if($result['code']=='0'){
	echo json_encode($result);
	exit;
}

function Connect($method,$p='')
{
  $http_request = $method." ".USERCONVERT_APIPath." HTTP/1.1\r\n";
  $http_request .= "Host:".USERCONVERT_domain.":".USERCONVERT_port."\r\n";
  $http_request .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
  $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $http_request .= "Content-Length:".strlen($p)."\r\n\r\n"; 

  $fp = fsockopen(USERCONVERT_domain, USERCONVERT_port, $errno, $errstr, 10);

  if (!$fp) {
    echo "$errstr ($errno)<br>\n";exit;
  }else{
    fputs ($fp, $http_request.$p);
    while (!feof($fp)) {
      $result .= fread($fp,32000);
    }
  }
  fclose ($fp);

  $json = substr(strstr($result,"\r\n\r\n"),4);
	$array = json_decode($json,TRUE);
  if(empty($array)){
		$data['code']='505';
		$data['msg']='get data failed';
		$json = json_encode($data);
		echo $json;
    exit;
  }
  return $array;

}

function count_pages($pdf_path) {
/*
Title:
Subject:
Keywords:
Author:
Creator:        PDFCreator Version 1.5.0
Producer:       GPL Ghostscript 9.05
CreationDate:   Wed Mar 27 16:28:32 2013
ModDate:        Wed Mar 27 16:28:32 2013
Tagged:         no
Form:           none
Pages:          1
Encrypted:      no
Page size:      595 x 842 pts (A4)
File size:      2595 bytes
Optimized:      no
PDF version:    1.4
*/
	$cmd=PLUGIN_PATH.'/ebookconvert/libs/pdfinfo '.$pdf_path;
	exec($cmd, $cmd_ret, $retCode);
	foreach($cmd_ret as $_k => $_v){
	        if(strpos ($_v,"Pages:") !== false){
	                $Pages = ltrim(str_replace ("Pages:", "",$_v));
	        }
	}
	return $Pages;
	/*
  $count=0;
  if(preg_match_all("\/Type\s\/Pages[\r\n\s\w\d\/\[\]]+\/Count\s+(\d+)", $contents, $capture)){
    foreach($capture as $c) {
        if(intval($c[0]) > $count)
            $count = intval($c[0]);
    }
    return $count; 
  }
  return 0;
  */
}

function str_before($subject, $needle)
{
    $p = strpos($subject, $needle);
    return substr($subject, 0, $p);
}

?>
