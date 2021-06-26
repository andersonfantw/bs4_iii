<?PHP
class SearchManager{
	var $arrTag = array();
	var $result = array();
	var $pn = '';
	var $fulltextsearch = '';
	var $operand;
	var $exp;
	function __construct(){
		$arr = array('pwrf','prt','pi','pcu','pc','pcof','year');
		for($i=0;$i<count($arr);$i++){
			$this->arrTag[$arr[$i]] = array();
		}
	}
	//pkey = pcu,pi,year.....
	//value = array(tid,tid...)
	function setCondition($pkey,$key,$value){
		$this->arrTag[$pkey][] = array('key'=>$key,'val'=>$value);
	}
	function setProjectName($value){
		$this->pn = $value;
	}
	//return array(keyword) = synonyms, seperate by comma.
	function getSynonyms($arr){
		global $db;
		$fulltext_synonyms = new fulltext_synonyms(&$db);
		$data = $fulltext_synonyms->getList();

		$hash = array();
		for($i=0;$i<count($data['result']);$i++){
			switch($data['result'][$i]['fts_status']){
				case '1':
					$tmparr = explode(',',$data['result'][$i]['fts_content']);
					$str = $data['result'][$i]['fts_name'] . ',' . $data['result'][$i]['fts_content'];
					$hash[$data['result'][$i]['fts_name']] = $str;
					for($j=0;$j<count($tmparr);$j++){
						$hash[$tmparr[$j]] = $str;
					}
					break;
				case '2':
					$hash[$data['result'][$i]['fts_name']] = $data['result'][$i]['fts_content'];
					$tmparr = explode(',',$data['result'][$i]['fts_content']);
					for($j=0;$j<count($tmparr);$j++){
						$hash[$tmparr[$j]] = $data['result'][$i]['fts_name'];
					}
					break;
				case '3':
					$hash[$data['result'][$i]['fts_name']] = $data['result'][$i]['fts_content'];
					break;
			}
		}

		$arrFoundSynonyms = array();
		for($i=0;$i<count($arr);$i++){
			if(array_key_exists($arr[$i],$hash)){
				$arrFoundSynonyms[$arr[$i]] = $hash[$arr[$i]];
			}
		}
		return $arrFoundSynonyms;
	}
	function fulltextsearch($value){
		$_arr_from = array('/  /','/\( /','/ \)/','/ OR /','/ or /','/ AND /','/ and /','/｜/','/＆/','/ \| /','/ & /','/ /');
		$_arr_to = array(' ','(',')','|','|','&','&','|','&','|','&','&');
		$value = preg_replace($_arr_from,$_arr_to,$value);
		$this->fulltextsearch = $value;
	}
	public function getList($orderby='',$limit_from=0,$offset=0,$where=''){
		//$_GET[order[0][column]]
		//$_GET[order[0][dir]]
		//$_GET[start]
		//$_GET[length]
		//同義詞
		//執行單位、承辦科別
		if(!$this->validFulltextExpression($this->fulltextsearch)){
			return $ee->Warning('400');
		}
		$arr = $this->getFulltextExp($this->fulltextsearch);
//var_dump('getFulltextExp',$arr);
		$this->exp = $arr['exp'];
		$this->operand = $arr['operand'];

		$synonyms = $this->getSynonyms($arr['operand']);

		foreach($synonyms as $key => $val){
			$s = '('.str_replace(',','|',$val).')';
			$this->exp = str_replace($key,$s,$this->exp);
			foreach(explode(',',$val) as $v){
				if(!in_array($v,$this->operand)){
					$this->operand[] = $v;
				}
			}
		}

		//array('id','key','val','type')
		$arrPCU = array();
		$arrPI = array();
		$TagevolveManager = new TagevolveManager();
		foreach($this->arrTag['pcu'] as $v){
			$arr_path = $TagevolveManager->getPathByKey($v['key']);
			if(!empty($arr_path)){
				$arrPCU[$v['key']] = $arr_path;
			}
		}
		foreach($this->arrTag['pi'] as $v){
			$arr_path = $TagevolveManager->getPathByKey($v['key']);
			if(!empty($arr_path)){
				$arrPI[$v['key']] = $arr_path;
			}
		}		
//var_dump('$arr_path',$arr_path);
//var_dump('arrPCU',$arrPCU);
//var_dump('arrPI',$arrPI);
//var_dump('$this->arrTag',$this->arrTag);
		$arrPCUPI = array();
		//array(
		//	0 => array('id','key','val','type'),
		//	1 => array('id','key','val','type')
		//)
		if(!empty($this->arrTag['pcu']) && !empty($this->arrTag['pi']) && !empty($arrPCU) && !empty($arrPI)){
			foreach($this->arrTag['pcu'] as $pcu_v){
				foreach($this->arrTag['pi'] as $pi_v){
					$key = $pcu_v['key'] . ',' . $pi_v['key'];
					$arrPCUPI[$key] = array();
					$thisyear = date("Y")-1911;
					$pcu_index=0;
					$pi_index=0;
					for($y=102;$y<=$thisyear;$y++){
						if(empty($arrPCU[$pcu_v['key']][$pcu_index]) && empty($arrPI[$pi_v['key']][$pi_index])){
							$arrPCUPI[$key][] = array('year'=>$y,'pcu'=>$pcu_v['key'],'pi'=> $pi_v['key']);
						}elseif($y>$arrPCU[$pcu_v['key']][$pcu_index]['year'] && $y<$arrPI[$pi_v['key']][$pi_index]['year']){
							$arrPCUPI[$key][] = array('year'=>$y,'pcu'=>$arrPCU[$pcu_v['key']][$pcu_index]['key'],'pi'=>$arrPI[$pi_v['key']][$pi_index]['key']);
						}else{
							$_arr = array('year'=>$y);
							if($y==$arrPCU[$pcu_v['key']][$pcu_index]['year']){
								$pcu_index++;
								if(empty($arrPCU[$pcu_v['key']][$pcu_index])){
									$_arr['pcu'] = $pcu_v['key'];
								}else{
									$_arr['pcu'] = $arrPCU[$pcu_v['key']][$pcu_index]['key'];
								}
							}
							if($y==$arrPI[$pi_v['key']][$pi_index]['year']){
								$pi_index++;
								if(empty($arrPI[$pi_v['key']][$pi_index])){
									$_arr['pi'] = $pi_v['key'];
								}else{
									$_arr['pi'] = $arrPI[$pi_v['key']][$pi_index]['key'];
								}
							}
							$arrPCUPI[$key][] = $_arr;
						}
					}
				}
			}
		}else{
			if(!empty($this->arrTag['pcu']) && !empty($arrPCU)){
				foreach($this->arrTag['pcu'] as $pcu_v){
					$key = $pcu_v['key'];
					$arrPCUPI[$key] = array();
					$thisyear = date("Y")-1911;
					$pcu_index=0;
					for($y=102;$y<=$thisyear;$y++){
						switch(true){
							case $y==$arrPCU[$key][$pcu_index]['year']:
								$pcu_index++;
							case empty($arrPCU[$key][$pi_index]):
							case $y>$arrPCU[$key][$pcu_index]['year']:
								$arrPCUPI[$key][] = array('year'=>$y,'pi'=>$pcu_v['val']);
								break;
							case $y<$arrPI[$key][$pi_index]['year']:
								$arrPCUPI[$key][] = array('year'=>$y,'pcu'=>$arrPCU[$key][$pcu_index]['key']);
								break;
						}
					}
				}
			}
			if(!empty($this->arrTag['pi']) && !empty($arrPI)){
				foreach($this->arrTag['pi'] as $pi_v){
					$key = $pi_v['key'];
					$arrPCUPI[$key] = array();
					$thisyear = date("Y")-1911;
					$pi_index=0;
					for($y=102;$y<=$thisyear;$y++){
						switch(true){
							case $y==$arrPI[$key][$pi_index]['year']:
								$pi_index++;
							case empty($arrPI[$key][$pi_index]):
							case $y>$arrPI[$key][$pi_index]['year']:
								$arrPCUPI[$key][] = array('year'=>$y,'pi'=>$pi_v['val']);
								break;
							case $y<$arrPI[$pi_v['key']][$pi_index]['year']:
								$arrPCUPI[$key][] = array('year'=>$y,'pi'=>$arrPI[$pi_v['key']][$pi_index]['val']);
								break;
						}
					}
				}
			}
		}
//var_dump('arrPCUPI',$arrPCUPI);
		//marge same pcu & pi
		$arrPCUPI1 = array();
		//array(
		//	0 => array('year'=>'val','pcu'=>'val','pi'=>'val')
		//	1 => array('year'=>'val','pcu'=>'val','pi'=>'val')
		//)
		if(!empty($arrPCUPI)){
			//has tag evolve
			foreach($arrPCUPI as $k => $v){
				if(strpos($k,',')>0){
					list($pcutkey,$pitkey) = explode(',',$k);
					$index=0;
					$arrPCUPI1[$key] = array();
					$arrPCUPI1[$key][$index] = array('year'=>$arrPCUPI[$k][$i]['year'],'pcu'=>$arrPCUPI[$k][$i]['pcu']['val'],'pi'=>$arrPCUPI[$k][$i]['pi']['val']);
					for($i=0;$i<count($arrPCUPI[$k])-1;$i++){
						if($arrPCUPI[$k][$i]['pcu']['key']==$arrPCUPI[$k][$i+1]['pcu']['key'] && $arrPCUPI[$k][$i]['pi']['key']==$arrPCUPI[$k][$i+1]['pi']['key']){
							$arrPCUPI1[$index]['year'] = $arrPCUPI1[$index]['year'].','.$arrPCUPI[$k][$i+1]['year'];
						}else{
							$index++;
							$arrPCUPI1[$k][$index] = array('year'=>$arrPCUPI[$k][$i+1]['year'],'pcu'=>$arrPCUPI[$k][$i+1]['pcu']['val'],'pi'=>$arrPCUPI[$k][$i+1]['pi']['val']);
						}
					}
				}elseif(strpos($k,'pcu')===0){
					$index=0;
					$arrPCUPI1[$k] = array();
					$arrPCUPI1[$k][$index] = array('year'=>$arrPCUPI[$k][0]['year'],'pcu'=>$arrPCUPI[$k][0]['pcu']);
					for($i=0;$i<count($arrPCUPI[$k])-1;$i++){
						if($arrPCUPI[$k][$i]['pcu']==$arrPCUPI[$k][$i+1]['pcu']){
							$arrPCUPI1[$k][$index]['year'] = $arrPCUPI1[$k][$index]['year'].','.$arrPCUPI[$k][$i+1]['year'];
						}else{
							$index++;
							$arrPCUPI1[$k][$index] = array('year'=>$arrPCUPI[$k][$i+1]['year'],'pcu'=>$arrPCUPI[$k][$i+1]['pcu']);
						}
					}
				}elseif(strpos($k,'pi')===0){
					$index=0;
					$arrPCUPI1[$k] = array();
					$arrPCUPI1[$k][$index] = array('year'=>$arrPCUPI[$k][0]['year'],'pi'=>$arrPCUPI[$k][0]['pi']);
					for($i=0;$i<count($arrPCUPI[$k])-1;$i++){
						if($arrPCUPI[$k][$i]['pi']==$arrPCUPI[$k][$i+1]['pi']){
							$arrPCUPI1[$k][$index]['year'] = $arrPCUPI1[$k][$index]['year'].','.$arrPCUPI[$k][$i+1]['year'];
						}else{
							$index++;
							$arrPCUPI1[$k][$index] = array('year'=>$arrPCUPI[$k][$i+1]['year'],'pi'=>$arrPCUPI[$k][$i+1]['pi']);
						}
					}
				}
			}
		}
//var_dump('arrPCUPI1',$arrPCUPI1);
		//array('pn'=>'val','pwrf'=>'val',....)
		$arr = array();
		$arrExclude = array();
		if(!empty($arrPCUPI1)){
			$arrExclude = array('pcu','pi','year');
		}
		foreach($this->arrTag as $k => $v){
			if(!in_array($k,$arrExclude)){
				if(!empty($v)){
					$str='';
					foreach($v as $v1){
						$str .= ','.$v1['val'];
					}
					$arr[$k] = substr($str,1);
				}
			}
		}
//var_dump('arr',$arr);

		$result = array();
		//array(
		//	array('pn'=>'','pwrf'=>'',....,'year'=>'','pcu'=>'','pi'=>''),
		//	array('pn'=>'','pwrf'=>'',....,'year'=>'','pcu'=>'','pi'=>'')
		//)
		$arrTmp = array();
		if(empty($arrPCUPI1)){
			$result = $arr;
			if(!empty($this->pn)){
				$result['pn'] = $this->pn;
			}
		}else{
			foreach($arrPCUPI1 as $k=>$v){
				$arrTmp = array_merge($arrPCUPI1[$k]);
				foreach($v as $v1){
					$arrTmp[$k] = $v1;
					if(!empty($this->pn)){
						$result[] = array_merge($arrTmp[$k],$arr,array('pn'=>$this->pn));
					}else{
						$result[] = array_merge($arrTmp[$k],$arr);
					}
				}
			}
		}

//var_dump('arrTmp',$arrTmp);
		$this->result = $result;
		return $this->connect($orderby,$limit_from,$offset);
	}

	//return 1: space between, not logic symble
	//return 2: logic symble
	//return -1: invalud expression
	private function validFulltextExpression($str,$returnExp=false){
		$exp = array();
		$operand = array();
		if(strpos($str,'(')===false && strpos($str,')')===false && strpos($str,'&')===false && strpos($str,'|')===false ){
			if($returnExp){
				$arr = explode(' ',$str);
				for($i=0;$i<count($arr);$i++){
					if($arr[$i]!='' && !in_array($arr[$i],$operand)) $operand[]=$arr[$i];
				}
				$exp = implode('&',$operand);
				return array('exp'=>$exp,'operand'=>$operand);
			}else return 1;
		}else{
			$arr1=array();
			$tmp = str_replace(array('(',')','&','|'),array(',(,',',),',',%26,',',|,'),$str);
			$tmp = str_replace(',,',',',$tmp);
			if(substr($tmp,0,1)==','){
				$tmp = substr($tmp,1);
			}
			if(substr($tmp,-1)==','){
				$tmp = substr($tmp,0,count($tmp)-2);
			}
			$arr = explode(',',$tmp);
			$f=0;
			for($i=0;$i<count($arr);$i++){
				$p=trim($arr[$i]);
				$arr1[] = $p;
				switch($p){
					case '(':
						$f++;
						break;
					case ')':
						$f--;
						break;
					case '%26':
					case '|':
						break;
					default:
						if(!in_array($p,$operand)) $operand[]=$p;
						break;
				}
			}
			if($f==0){
				if($returnExp){
					$exp = implode('',$arr1);
					return array('exp'=>$exp,'operand'=>$operand);
				}else	return 2;
			}else return -1;
		}
	}
	private function getFulltextExp($str){
		return $this->validFulltextExpression($str,true);
	}

	private function connect($orderby='',$limit_from=0,$offset=0){
		$order = split(' ',$orderby);
		foreach($this->operand as $k=>$v){
			$this->operand[$k] = urlencode($v);
		}

		if(empty($this->result)){
		}elseif(empty($this->result[0])){
			foreach($this->result as $k=>$v){
				$this->result[$k] = urlencode($v);
			}
			$this->result = array($this->result);
		}else{
			for($i=0;$i<count($this->result);$i++){
				foreach($this->result[$i] as $k=>$v){
					$this->result[$i][$k] = urlencode($v);
				}
			}
		}

		$params = array('token'=>'',
			'total_num_info'=>1,
			'start' => (int)$limit_from,
			'length' => (int)$offset);
		if($this->exp!=''){
			$params['kw_exp'] = $this->exp;
			$params['kw_operands'] = urldecode(json_encode($this->operand));
		}
		if(!empty($this->result)){
			$params['tag_srh_json'] = urldecode(json_encode($this->result));
		}
		if($order[0]!='total_count'){
			$params['orderby'] = $order[0];
		}
		if(in_array($order[1],array('asc','desc'))){
			$params['sort_order'] = $order[1];
		}
		$postjson = $params;
		$url = 'http://127.0.0.1/plugin/search/libs/EBookSearch.php';
		//$postjson = sprintf('{"method":"auth","token":"%s"}',$token);
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		//curl_setopt($ch, CURLOPT_POSTFIELDS, "method=auth&token=".$token);
		curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query($postjson)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//curl_setopt($ch, CURLOPT_HEADER, TRUE); 
		//curl_setopt($ch, CURLOPT_NOBODY, FALSE);
		$body = curl_exec($ch);
//var_dump(urldecode(http_build_query($postjson)));
		$result = $this->SearchEnginRequrnFormatToDatatableFormat($body);
		$result['params'] = $params;
		$result['searchengin'] = $body;
		return $result;
	}
	
	private function SearchEnginRequrnFormatToDatatableFormat($data){
/*
範例:  [[0,"",0.02519],[50,10],[10043,1,3,"4918139","書名",108,"計畫名稱","執行單位","承辦科別","承辦人","領域",140,"{…}"],[10042,1,3,"4918137","書名",...],[…],[…]]
SearchEngin:
data[0]為回傳值，[return_code,message]		
	data[0][0]=0 表示正確，>0表示錯誤	
	data[0][1] 為錯誤訊息	
	data[0][2] 搜尋時間 (秒數)	
		
data[n], n>=1 為資料		
	data[1][0] 總筆數(total_num_info=1時才有意義)	
	data[1][1] 實際回傳筆數	
	n >= 2	
	data[n][0] B_ID	
	data[n][1] uid	
	data[n][2] bs_id	
	data[n][3] b_key	
	data[n][4] 書名	
	data[n][5] 年度	
	data[n][6] 計畫名稱	
	data[n][7] 執行單位	
	data[n][8] 承辦科別	
	data[n][9] 承辦人	
	data[n][10] 領域	
	data[n][11] 關鍵字出現總數 (同一計畫)	
	data[n][12] 關鍵字出現次數count (json 字串)  例 : {"kw1":121,"kw2":20,"kw3":12...}

Datatable:
		//draw: 3
		//recordsFiltered: 57
		//recordsTotal: 57
		//data[0]["","","",...]
		
ebook path:
https://ebook.ctdp.org.tw/webs@2/ebook/uid/bs_id/109xxxxC0/book.html
*/
		$json = json_decode($data,true);
		if($json[0][0]>0){
			$msg = $json[0][1];
		}

		$result = array(
			'searchtime'=>$json[0][2],
			'recordsFiltered'=>$json[1][0],
			'recordsTotal'=>$json[1][0],
			'data'=>array()
		);

		$subdata = array();
		$year = $json[2][5];
		$pn = $json[2][6];
		$prekey = '';
		for($i=2;$i<count($json);$i++){
			if(($year != $json[$i][5]) || ($pn != $json[$i][6])){
				$i1 = $i-1;
				$result['data'][]=array($json[$i1][5],$json[$i1][6],$num,$json[$i1][7],$json[$i1][8],$json[$i1][9],$json[$i1][10],json_encode($subdata));
				$subdata = array();
				$num = 0;
				$year = $json[$i][5];
				$pn = $json[$i][6];
			}
			//if($prekey != $json[$i][3]){  //skip search engin bug 
				$prekey = $json[$i][3];
				$subdata[] = array(
					'name'=>$json[$i][4],
					'prt'=>$json[$i][11],
					'link'=>sprintf('/webs@2/ebook/%u/%u/%s/book.html?keys=%s',$json[$i][1],$json[$i][2],$json[$i][3],base64_encode(implode(',',$this->operand))),
					//'link'=>sprintf('/hosts/%u/%u/files/%s/book.php?keys=%s',$json[$i][1],$json[$i][2],$json[$i][3],implode(',',$this->operand)),
					'keywords'=>json_decode($json[$i][13],true)
				);
				$num = $json[$i][12];
			//}
		}
		if(count($json)>2){
			$i--;
			$result['data'][]=array($json[$i][5],$json[$i][6],$num,$json[$i][7],$json[$i][8],$json[$i][9],$json[$i][10],json_encode($subdata));
		}
		return $result;
	}
}
?>
