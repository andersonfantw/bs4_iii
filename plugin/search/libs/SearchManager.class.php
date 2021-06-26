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
					$str = $data['result'][$i]['fts_name'] . ',' . $data['result'][$i]['fts_content'];
					$hash[$data['result'][$i]['fts_name']] = $str;
					$tmparr = explode(',',$data['result'][$i]['fts_content']);
					for($j=0;$j<count($tmparr);$j++){
						$hash[$tmparr[$j]] = $data['result'][$i]['fts_name'].','.$tmparr[$j];
					}
					break;
				case '3':
					$str = $data['result'][$i]['fts_name'] . ',' . $data['result'][$i]['fts_content'];
					$hash[$data['result'][$i]['fts_name']] = $str;
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
		
		$arrTagYear = array();
		if(array_key_exists('year',$this->arrTag)){
			foreach($this->arrTag['year'] as $r){
				$arrTagYear[] = $r['val'];
			}
		}

		//arrPCU,arrPI
		//pi_pi118 => array(
		//	0 => array('id','key','year'),
		//	1 => array('id','key','year')
		//);
/*
			search include parent tag
			type =>
				separate(has year)
				combine(no year)
				rename(no year)

			pkey => array(year_from,year_to,array(tags))

			example1:
			A => 103 B,C
			C => D

			pi D =>
				103~109 C,D
				102 A

			example2:
			A => 103 B,C
			C,D => F

			pi F =>
				103~109 C,F
				102 A

			example3:
			A,B => C
			C => 103 D,F

			pi F =>
				103~109 F
				102 A,B,C

			example4:
			A => 103 B,C
			C => 104 D,F

			pi F =>
				104~109 F
				103 C
				102 A

			example5:
			A,B => C
			C,D => E
			
			pi E =>
				A,C,E

			example6:
			A => B
			B => C

			pi C =>
				A,B,C

			example6:
			A => B
			B => 103 C,D
			D => E

			pi E =>
				103~109	D,E
				102 A,B

*/
		$arrPCU = array();
		$arrPI = array();
		$TagevolveManager = new TagevolveManager();
		$thisyear = date("Y")-1911;
		foreach($this->arrTag['pcu'] as $v){
			$key = $v['key'];
			$arr_path = $TagevolveManager->getPathByKey($key);
			if(empty($arr_path)){
				//don't have evolve
				$arrPCU[$key][] = array('from'=>102,'to'=>$thisyear,'pcu'=>array($v['val']));
			}else{
				$arrPCU[$key] = array();
				
				$tmpTags = array($v['val']);
				$y=$thisyear;
				foreach($arr_path as $t){
					if(empty($t['year'])){
						$tmpTags[] = $t['val'];
					}else{
						$arrPCU[$key][] = array('from'=>$t['year'],'to'=>$y,'pcu'=>$tmpTags);
						$y = $t['year']-1;
						$tmpTags = array($t['val']);
					}
				}
				$n = count($arrPCUPI[$key]);
				if($arrPCU[$key][$n-1]['from']!=102){
					$arrPCU[$key][] = array('from'=>102,'to'=>$y,'pcu'=>$tmpTags);
				}
			}
		}
		foreach($this->arrTag['pi'] as $v){
			$key = $v['key'];
			$arr_path = $TagevolveManager->getPathByKey($key);
			if(empty($arr_path)){
				//don't have evolve
				$arrPI[$key][] = array('from'=>102,'to'=>$thisyear,'pcu'=>array($v['val']));
			}else{
				$arrPI[$key] = array();

				$tmpTags = array($v['val']);
				$y=$thisyear;
				foreach($arr_path as $t){
					if(empty($t['year'])){
						$tmpTags[] = $t['val'];
					}else{
						$arrPI[$key][] = array('from'=>$t['year'],'to'=>$y,'pi'=>$tmpTags);
						$y = $t['year']-1;
						$tmpTags = array($t['val']);
					}
				}
				$n = count($arrPCUPI[$key]);
				if($arrPI[$key][$n-1]['from']!=102){
					$arrPI[$key][] = array('from'=>102,'to'=>$y,'pi'=>$tmpTags);
				}
			}
		}
//var_dump('$arr_path',$arr_path);
//var_dump('arrPI',$arrPI);
//var_dump('$this->arrTag',$this->arrTag);
		$arrPCUPI = array();
		//array(
		//	0 => array('year'=>'val','pcu'=>'val','pi'=>'val')
		//	1 => array('year'=>'val','pcu'=>'val','pi'=>'val')
		//)
/*
PI A(102~109:a1,102~109:a2),B(102~109:b1,102~109:b2)
PCU C(102~109:c1,102~109:c2),D(102~109:d1,102~109:d2)

(A | B) & (C | D)
(a1 | a2 | b1 | b2) & (c1 | c2 | d1 | d2)

102~109	a1,a2,c1,c2
102~109	a1,a2,d1,d2
102~109	b1,b2,c1,c2
102~109	b1,b2,d1,d2


PI A(102~104:a1,105~109:a2),B(102~103:b1,104~109:b2)
PCU C(102~105:c1,106~109:c2),D(102~106:d1,107~109:d2)

102~104	a1 c1
105	a2 c1
106~109	a2 c2

102~104	a1 d1
105~106	a2 d1
107~109	a2 d2

102~103	b1 c1
104~105	b2 c1
106~109	b2 c2

102~103	b1 d1
104~106	b2 d1
107~109	b2 d2

	A				B
1	106~109	107~109
2	104~105	106~106
3	102~103	102~105

109 At[1] Bt[1]
107 Bf[1]
106 Af[1] Bf[2] Bt[2]
105 At[2] Bt[3]
104 Af[2]
103 At[3]
102 Af[3] Bf[3]

=> 107~109	A1 B1
=> 106~106	A1 B2
=> 104~105	A2 B3
=> 102~103	A3 B3

*/
		$arrFromTo = array('from','to');
		$arrYear = array();
		//array('pi|pcu',index,'0:from|1:to',key)
		if(!empty($arrPI) && !empty($arrPCU)){
			foreach($arrPI as $pik => $arr_pi){
				foreach($arrPCU as $pcuk => $arr_pcu){
					$key = sprintf('%s@%s',$pik,$pcuk);
					if(!array_key_exists($key,$arrYear)){
						$arrYear[$key] = array();
					}
					if(!array_key_exists($key,$arrPCUPI)){
						$arrPCUPI[$key] = array();
					}
					for($i=0;$i<count($arr_pi);$i++){
						for($j=0;$j<count($arrFromTo);$j++){
							$y = $arr_pi[$i][$arrFromTo[$j]];
							if(!array_key_exists($y,$arrYear[$key])){
								$arrYear[$key][$y] = array();
							}
							if(empty($arrTagYear)){						//not set year period
								$arrYear[$key][$y][] = array('pi',$i,$j);
							}elseif(in_array($j,$arrTagYear)){	//when set year period
								$arrYear[$key][$y][] = array('pi',$i,$j);
							}
						}
					}
					for($i=0;$i<count($arr_pcu);$i++){
						for($j=0;$j<count($arrFromTo);$j++){
							$y = $arr_pcu[$i][$arrFromTo[$j]];
							if(!array_key_exists($y,$arrYear[$key])){
								$arrYear[$key][$y] = array();
							}
							if(empty($arrTagYear)){						//not set year period
								$arrYear[$key][$y][] = array('pcu',$i,$j);
							}elseif(in_array($j,$arrTagYear)){	//when set year period
								$arrYear[$key][$y][] = array('pcu',$i,$j);
							}
						}
					}
										
					krsort($arrYear[$key]);
					//          from,to
					//'pcu'=>array(0,0)
					$tmpPCUPI = array('pcu'=>array(0,0),'pi'=>array(0,0));
					$pY = $thisyear;
					$pi_index=0;$pi_index1=0;
					$pcu_index=0;$pcu_index1=0;
					foreach($arrYear as $k => $arr){
						$arrY = array_keys($arr);
						foreach($arr as $y => $arrv){
							foreach($arrv as $v){
								$tmpPCUPI[$v[0]][$v[2]] = 1;
							}
							$_hasExecute = false;
							$pcu_index1=$pcu_index;
							$pi_index1 = $pi_index;
							if($tmpPCUPI['pcu'][0]==1 && $tmpPCUPI['pcu'][1]==1){
								$_hasExecute = true;
								$tmpPCUPI['pcu']=array(0,0);
								if($pcu_index<count($arr_pcu)){
									$pcu_index++;
								}
							}
							if($tmpPCUPI['pi'][0]==1 && $tmpPCUPI['pi'][1]==1){
								$_hasExecute = true;
								$tmpPCUPI['pi']=array(0,0);
								if($pi_index<count($arr_pi)){
									$pi_index++;
								}
							}
							array_shift($arrY);
							if($_hasExecute){
								$tmpY = array();
								for($i=$y;$i<=$pY;$i++){
									$tmpY[] = $i;
								}
								$arrPCUPI[$key][] = array(
									'year' => implode(',',$tmpY),
									'pcu' => implode(',',$arr_pcu[$pcu_index1]['pcu']),
									'pi' => implode(',',$arr_pi[$pi_index1]['pi'])
								);
								//next year in list
								$pY = count($arrY) ? $arrY[0] : 102;
							}else{
								$pY = $y;
							}
						}
					}
				}
			}
		}elseif(!empty($arrPI)){
			foreach($arrPI as $pik => $arr_pi){
				$key = $pik;
				if(!array_key_exists($key,$arrPCUPI)){
					$arrPCUPI[$key] = array();
				}
				for($i=0;$i<count($arr_pi);$i++){
					$tmpY = array();
					for($j=$arr_pi[$i]['from'];$j<=$arr_pi[$i]['to'];$j++){
						$tmpY[] = $j;
					}
					$arrPCUPI[$key][] = array(
						'year' => implode(',',$tmpY),
						//'pcu' => implode(',',$arrPCU[$pcuk][$pcu_index1]['pcu']),
						'pi' => implode(',',$arr_pi[$i]['pi'])
					);
				}
			}
		}elseif(!empty($arrPCU)){
			foreach($arrPCU as $pcuk => $arr_pcu){
				$key = $pcuk;
				if(!array_key_exists($key,$arrPCUPI)){
					$arrPCUPI[$key] = array();
				}
				for($i=0;$i<count($arr_pcu);$i++){
					$tmpY = array();
					for($j=$arr_pcu[$i]['from'];$j<=$arr_pcu[$i]['to'];$j++){
						$tmpY[] = $j;
					}
					$arrPCUPI[$key][] = array(
						'year' => implode(',',$tmpY),
						'pcu' => implode(',',$arr_pcu[$i]['pcu'])
						//'pi' => implode(',',$arr_pi[$i]['pi'])
					);
				}
			}
		}

//var_dump('arrPCUPI1',$arrPCUPI1);
		//array('pn'=>'val','pwrf'=>'val',....)
		$arr = array();
		$arrExclude = array();
		if(!empty($arrPCUPI)){
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
		//	array('pn'=>'','pwrf'=>'',....,'year'=>'102,103','pcu'=>'','pi'=>''),
		//	array('pn'=>'','pwrf'=>'',....,'year'=>'104,105,106','pcu'=>'','pi'=>'')
		//)
		$arrTmp = array();
		if(empty($arrPCUPI)){
			$result = $arr;
			if(!empty($this->pn)){
				$result['pn'] = $this->pn;
			}
		}else{
			foreach($arrPCUPI as $k=>$v){
				$arrTmp = array_merge($arrPCUPI[$k]);
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
		//$url = 'http://59.125.179.140/EBookSearch.php';
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
