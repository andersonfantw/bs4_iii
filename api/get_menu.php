<?PHP
  require_once dirname(__FILE__).'/../init/config.php';
  $init = new init('db','ejson');
  global $bs_code;
  $cmd = $fs->valid($_POST['cmd'],'cmd');
  $bs_code = (int) $fs->valid($_POST['bs'],'id');
  $category = new category(&$db);
  $data = $category->getCategoryStructure();

  $output = new Services_JSON();
	$json = new Services_JSON(SERVICES_JSON_ESCAPED_UNICODE);
	if($cmd=='valid'){
		$data = $json->encode($data);
		$data = str_replace('\/','/',$data);
		echo md5($data);exit;

	}else{
		header('Content-Type: application/json; charset=utf-8');
		echo $output->encode($data);exit;
	}
?>
