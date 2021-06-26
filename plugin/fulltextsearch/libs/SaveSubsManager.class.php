<?PHP
class SaveSubsManager{
	const service_url='https://savesubs.com/action/get';
	function __construct(){
	}
	function getSubs($_bname,$_videourl){
		$_type = BookManager::getFileType($_videourl);
		switch($_type['type']){
			case BookTypeEnum::URL_Youtube:
				$ch = curl_init(self::service_url);
				$jsonData = array('url'=>$_videourl);
				curl_setopt($ch, CURLPOT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsonData));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$result = curl_exec($ch);
				$arr = json_decode($result,TRUE);
        foreach($arr['formats'] as $r){
					if($r['type']=='subs'){
						$str = file_get_contents('https://savesubs.com'.$r['url']);
					}
        }
        $arr = explode("\n",$str);
        $hash = array();
        $hash[] = sprintf('0;0;0;0;0;%s',$_bname);
        $n = 0;
        for($i=0;$i<count($arr);$i+=4){
					list($t1,$t2) = explode(' --> ',$arr[$i+1]);
					$r = trim($arr[$i+2]);
					if(!empty($r)){
				    if(!in_array($r,$hash)){
				    	$n = strtotime(substr($t1,0,8))-strtotime('today');
							$hash[] = sprintf('0;0;0;0;%u;%s',$n,$r);
				    }
					}
        }
        return $hash;
				break;
		}
	}
}
?>