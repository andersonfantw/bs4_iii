<?PHP
/******************************************
Time:系統時間
	Year:西元年
		DC2014:2014
		DC2015:2015
	RepublicEra:民國年
		ROC103:103
		ROC104:104
	SchoolYear:學年
		SYear2014:2014
		SYear2015:2015
	SchoolTerm:學期
		FirstTerm:上學期
		LastTerm:下學期
******************************************/
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db');

$tag = new tag(&$db);
$system_tag = new system_tag(&$db);
$rs = $tag->getByKey('#Time');
if($rs){
	$tid=$rs[0]['t_id'];
}else{
	//$uid,$path,$key,$val,$type=0
	$arr=$tag->addTag(0,'','#Time','系統時間',1);	
	$tid = $arr['tid'];
}

$rs = $tag->getByKey('#Year');
if($rs){
	$arr=$rs[0]['t_id'];
	$tid1 = $arr['tid'];
}else{
	$arr=$tag->addTag(0,(string)$tid,'#Year','西元年',1);
	$tid1 = $arr['tid'];
}
$thisYear = date('Y');
$keyYear = '#DC'.$thisYear;
$rs = $tag->getByKey($keyYear);
if(!$rs){
	$id = $tag->addTag(0,$tid.','.$tid1,$keyYear,$thisYear,1);
	$rs = $system_tag->getByKey('system',0,$id);
	if(!$rs){
		$data = array('method'=>'system','id'=>0,'t_id'=>intval($id));
		$system_tag->insert($data);
	}
}

$rs = $tag->getByKey('#RepublicEra');
if($rs){
	$arr=$rs[0]['t_id'];
	$tid2 = $arr['tid'];
}else{
	$arr=$tag->addTag(0,(string)$tid,'#RepublicEra','民國年',1);
	$tid2 = $arr['tid'];
}
$thisYear = (string)(intval(date('Y'))-1911);
$keyYear = '#ROC'.$thisYear;
$rs = $tag->getByKey($keyYear);
if(!$rs){
	$arr = $tag->addTag(0,$tid.','.$tid2,$keyYear,$thisYear,1);
	$id = $arr['tid'];
	$rs = $system_tag->getByKey('system',0,$id);
	if(!$rs){
		$data = array('method'=>'system','id'=>0,'t_id'=>intval($id));
		$system_tag->insert($data);
	}
}

$rs = $tag->getByKey('#SchoolYear');
if($rs){
	$tid3=$rs[0]['t_id'];
}else{
	$arr=$tag->addTag(0,(string)$tid,'#SchoolYear','學年',1);
	$tid3 = $arr['tid'];
}
$thisYear = (date('Y'));
$thisMonth = intval(date('m'));
if($thisMonth<8){$thisYear=$thisYear-1;}
$thisYear = (string)$thisYear;
$keyYear = '#SYear'.$thisYear;
$rs = $tag->getByKey($keyYear);
if(!$rs){
	$arr = $tag->addTag(0,$tid.','.$tid3,$keyYear,$thisYear,1);
	$id = $arr['tid'];
	$rs = $system_tag->getByKey('system',0,$id);
	if(!$rs){
		$data = array('method'=>'system','id'=>0,'t_id'=>intval($id));
		$system_tag->insert($data);
	}
}

$rs = $tag->getByKey('#SchoolTerm');
if($rs){
	$tid4=$rs[0]['t_id'];
}else{
	$arr=$tag->addTag(0,(string)$tid,'#SchoolTerm','學期',1);
	$tid4 = $arr['tid'];
}
$rs = $tag->getByKey('#FirstTerm');
if($rs){
	$tidf = $rs[0]['t_id'];
}else{
	$arr = $tag->addTag(0,$tid.','.$tid4,'#FirstTerm','上學期',1);
	$tidf = $arr['tid'];
}
$rs = $tag->getByKey('#LastTerm');
if($rs){
	$tidl = $rs[0]['t_id'];
}else{
	$arr = $tag->addTag(0,$tid.','.$tid4,'#LastTerm','下學期',1);
	$tidl = $arr['tid'];
}
$id= (intval(date('m'))<8) ? $tidf:$tidl;
$rs = $system_tag->getByKey('system',0,$id);
if(!$rs){
	$data = array('method'=>'system','id'=>0,'t_id'=>intval($id));
	$system_tag->insert($data);
}
?>