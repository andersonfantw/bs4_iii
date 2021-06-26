<?PHP
  require_once dirname(__FILE__).'/../init/config.php';
  $init = new init('db','filter','ejson');

	$itutor_cols = array("Name","Description","Date","UserID","TotalTime","SlideCount","TakenSlide","TotalInteraction","TakenInteraction","Correct","Points","MaxPoints","Percent","Result");
	//$exercise_cols = array("SlideIndex","ReportID","Attempts","Points","MaxPoints","Result","Type","Answers");

	$itutor = new itutor(&$db);
	$db_process = new db_process(&$db,'itutor_exercise','e_');
	$report = $fs->valid($_POST['report'],'xml');
	$xml = simplexml_load_string($report);

	if(empty($xml) || !empty($xml->error)){
	  echo "get itutor data failed";
	  exit;
	}

	$json = json_encode($xml);
	$json_itutor = json_decode($json,TRUE);
	foreach($itutor_cols as $val){
		$str = $json_itutor['Report'][$val];
		if(is_array($str)){
			$str = implode('',$str);
		}
		if(strtolower($val)=='date'){
			$data['i_'.strtolower($val)] = date('Y-m-d H:i:s',strtotime($str));
		}else{
			$data['i_'.strtolower($val)] = $str;
		}
	}

	$id = $_SERVER['HTTP_REFERER'];
	$arr_id = explode('/',$id);

	$data['id'] = $arr_id[7];
	$sql = "select b_id from bookshelf2_books where webbook_link like '%".$data['id']."%';";
	$rs = $db->query_first($sql);
	if($rs)
	{
		$data['b_id'] = $rs['b_id'];
	}

	$buid=0;
	$_buid = bssystem::getLoginBUID();
	if(!empty($_buid)){
		$buid = $_buid;
	}
	$data['bu_id'] = $buid;
	$data['remote_ip'] = $USER_IP;
	$data['session_id'] = session_id();
	$data['createdate'] = date('Y-m-d H:i:s');

	$itu_id = $itutor->insert($fs->sql_safe($data),true);

	if(!$itu_id){
		echo "add book failed";
		exit;
	}
	if(count($json_itutor['Report']['Details']['Interaction'])>1) {
		foreach($json_itutor['Report']['Details']['Interaction'] as $val){
			unset($data);
			$data['i_id'] = $itu_id;
			foreach($val as $k => $v){
				$data['e_'.strtolower($k)] = $v;
			}
			$db_process->insert($fs->sql_safe($data));
		}
	}else{
		unset($data);
		foreach($json_itutor['Report']['Details']['Interaction'] as $k => $v){
                        $data['i_id'] = $itu_id;
			$data['e_'.strtolower($k)] = $v;
		}
		$db_process->insert($fs->sql_safe($data));
	}

?>
