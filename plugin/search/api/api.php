<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','tpl','inputxss','filter','ejson');

$cmd = $fs->valid($_REQUEST['cmd'],'cmd');
$type = $fs->valid($_POST['type'],'cmd');
$_buid = $fs->valid($_POST['buid'],'id');

$ini = new ini(&$db);
$quicksearch = new quicksearch(&$db);
$output = new Services_JSON();
$json = new Services_JSON(SERVICES_JSON_ESCAPED_UNICODE);

$_buid = bssystem::getLoginBUID();
switch($cmd){
	case 'checkQuickSearchName':
		if(empty($_buid) && false){
			$ee->add('msg','Please login again!');
			$ee->Error('406');
		}
		$id= $fs->valid($_POST['id'],'id');
		$name= $fs->valid($_POST['name'],'name');
		$data = $quicksearch->getList('',0,0,sprintf("q_id!=%u and bu_id=%u and q_name='%s'",$id,$_buid,$name));
		$ee->add('hasName',$data['total']>0);
		$ee->Message('200');
		break;
	case 'validQuickSearch':
	case 'getQuickSearch':
		$data = $quicksearch->getByBUID($_buid);
		$str = '[]';
		if(!empty($data)){
			$str = json_encode($data);
		}
		if($cmd=='validQuickSearch'){
			echo md5($str);
			exit;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo $str;exit;
		break;
	case 'addQuickSearch':
		if(empty($_buid) && false){
			$ee->add('msg','Please login again!');
			$ee->Error('406');
		}

		$name= $fs->valid($_POST['name'],'name');
		$shortname= $fs->valid($_POST['shortname'],'name');
		$str = $fs->valid($_POST['content'],'content');

		$data = array();
		$data['bu_id'] = $_buid;
		$data['q_name'] = $name;
		$data['q_shortname'] = $shortname;
		$data['q_content'] = $str;
		$id = $quicksearch->insert($data, true);
		if($id){
			$ee->add('id',$id);
			$ee->Message('200');
		}
		break;
	case 'updateQuickSearch':
		$id= $fs->valid($_POST['id'],'id');
		$name= $fs->valid($_POST['name'],'name');
		$shortname= $fs->valid($_POST['shortname'],'name');
		$str = $fs->valid($_POST['content'],'content');

		$data = array();
		$data['q_name'] = $name;
		$data['q_shortname'] = $shortname;
		$data['q_content'] = $str;
		$data = $quicksearch->update($id,$data);
		if($data){
			$ee->Message('200');
		}
		break;
	case 'delQuickSearch':
		$id= $fs->valid($_POST['id'],'id');
		if($quicksearch->del($id)){
			$ee->Message('200');
		}
		break;
	case 'search':
		$arr = array('fulltext','pn','pwrf','prt','pi','pcu','pc','pcof');
		$arrYear = array('year_from','year_to');
		$arrCol = array('year','pn','total_count','pi','pcu','pc','pwrf');
		$arrGroup = array();
		$arrGroupKey = array();

		$order = $fs->valid($_POST['order'],'ARRAY');
		$limit_from = $fs->valid($_POST['start'],'num');
		$offset = $fs->valid($_POST['length'],'num');
		$q = $fs->valid($_POST['q'],'content');
		//$q = base64_decode(str_replace('&lt;x&gt;','',$q));

		list($id,$name,$shortname,$tags) = explode('[@]',htmlspecialchars_decode($q));

		$orderby = $arrCol[$order[0]["column"]];
		$sort_order = $order[0]["dir"];
		$arrtag = explode(';',$tags);
//var_dump($orderby,$sort_order,$_POST);exit;

		//save fulltext search keywords
		if($arrtag[0]!=''){
			$bigdata_search_log = new bigdata_search_log(&$db);
			$uid = bssystem::getLoginUID();
			if(empty($uid)){
				$uid = bssystem::getLoginBUID();
				$utype = 'u';
				$group = new group(&$db);
				$data = $group->getListByBUID($uid);
				$arrGroup = array();
				foreach($data as $row){
					$arrGroup[] = $row['g_name'];
					$arrGroupKey[] = $row['g_key'];
				}
			}else{
				$utype = 'a';
			}
			$_date = date('Y-m-d H:i:s');
			$row = $bigdata_search_log->getByKey((int)$uid,$utype,$_date);
			if(empty($row)){
				$data = array(
					'bsl_name'=>$arrtag[0],
					'bsl_channel'=>BigdataChannelEnum::web,
					'bsl_type'=>BigdataSearchTypeEnum::fulltext,
					'uid'=>(int)$uid,
					'user_type'=>$utype,
					'createdate'=>$_date,
				);
				$bigdata_search_log->insert($data);
			}
		}


		if(count($arrtag)!=(count($arr)+count($arrYear))){
			$ee->add('msg',sprintf("%u - %u",count($arr)+count($arrYear),count($arrtag)));
			$ee->Error('404');
		}
		$SearchManager = new SearchManager();
		$SearchManager->fulltextsearch($arrtag[0]);
		$SearchManager->setProjectName($arrtag[1]);

		$hasPCU = false;
		for($i=2;$i<count($arr);$i++){
			$arr1 = explode(',',$arrtag[$i]);
			for($j=0;$j<count($arr1);$j++){
				list($k,$v) = explode('@',$arr1[$j]);
				if(!empty($k) && !empty($v)){
					if($arr[$i]=='pcu'){
						if(in_array($v,$arrGroup)){
							$hasPCU = true;
							$SearchManager->setCondition($arr[$i],$k,$v);
						}
					}else{
						$SearchManager->setCondition($arr[$i],$k,$v);
					}
				}
			}
		}

		if(!$hasPCU){
			for($i=0;$i<count($arrGroup);$i++){
				$k = $arrGroupKey[$i];
				$v = $arrGroup[$i];
				$SearchManager->setCondition('pcu',$k,$v);
			}
		}

		$y = array(101,0);
		for($i=0;$i<count($arrYear);$i++){
			list($k,$v) = explode('@',$arrtag[8+$i]);
			switch($k){
				case 'thisyear':
					$y[$i] = date('Y')-1911;
					break;
				case 'lastyear':
					$y[$i] = date('Y')-1912;
					break;
				case '':
					break;
				default:
					$y[$i] = $v;
					break;
			}
		}

		if(!empty($arrtag[8]) && !empty($arrtag[9])){
			for($i=$y[0];$i<=$y[1];$i++){
				$SearchManager->setCondition('year','ROC'.$i,$i);
			}
		}elseif(!empty($arrtag[8])){
			$SearchManager->setCondition('year','ROC'.$y[0],$y[0]);
		}
		$result = $SearchManager->getList($orderby.' '.$sort_order,$limit_from,$offset);
		$result['tags'] = $arrtag;
		echo json_encode($result);
}

?>
