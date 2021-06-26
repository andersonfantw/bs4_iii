<?PHP
$arr = array();
$arr[] = array('key'=>'zh-tw','value'=>'繁體','selected'=>false);
$arr[] = array('key'=>'zh-cn','value'=>'简体','selected'=>false);
$arr[] = array('key'=>'jp','value'=>'日本語','selected'=>false);
$arr[] = array('key'=>'en','value'=>'English','selected'=>false);
$arr[] = array('key'=>'vi','value'=>'tiếng việt','selected'=>false);

for($i=0;$i<count($arr);$i++){
	$arr[$i]['selected'] = ($arr[$i]['key']==$_COOKIE['currentlang']);
}
echo json_encode($arr);
?>
