<?PHP
  require_once dirname(__FILE__).'/../init/config.php';
  $init = new init('db','tpl','inputxss','filter','getIP','ejson');

	$AuthManager = new AuthManager();
  $itutor = new itutor(&$db);
 	$buid = $_SESSION['buid'];
 	if(empty($buid)){
 		exit;
 	}
  $data = $itutor->getUserList('i_name',0,0,'bu_id='.$buid);

  $output = $json = new Services_JSON();
  header('Content-Type: application/json; charset=utf-8');
  echo $json->encode($data);
?>
