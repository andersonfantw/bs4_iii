<?PHP
$result = checkEcocatLog('a691de33_8638ce62');
var_dump($result);
	function checkEcocatLog($ecocatid){
		//$path = sprintf('%s/lib/ecolab/msg/%s.html',ECOCAT_ROOT,$ecocatid);
		$path = sprintf('/var/www/html/bs4/ecocatcms305/lib/ecolab/msg/%s.html',$ecocatid);
		if(!is_file($path)){
			return array('status'=>-1,'msg'=>sprintf('path:%s is not exist.',$path));
		}
		$contents = file_get_contents($path);
		$arr = explode("\n",$contents);
		for($i=count($arr);$i>=0;$i--){
			$str = $arr[$i];
			if($arr[$i]!="") break;
		}
		if(strpos($str,'ERROR')==-1){
			return array('status'=>1,'msg'=>$str);
		}else{
			return array('status'=>0,'msg'=>$str);
		}
	}
?>
