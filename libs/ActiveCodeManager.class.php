<?PHP
class ActiveCodeManager{
	const check_code='anderson';
	const days_in_a_month = 31;
	const availability_period = 31536000;
	var $def1 = array(0,2,4,6,8);
	var $def2 = array(1,3,5,7,9);
	var $def3 = array(1,2,3,4,5,6,7,8,9,1);

	function __construct(){
	}

	private function _getChkCode($n){
		$arr = array_map('intval', str_split($n));
		$v = array_sum($arr);
		if($v>99){
			return $this->_getChkCode($v);
		}else{
			return $v;
		}
	}

	public function getCode(){
		$arr0 = array_map('intval', str_split((time()+self::availability_period)));
		$arr1 = array();
		$arr2 = array();
		$arr1chr = array();
		$arr2chr = array();
		for($i=0;$i<5;$i++){
			$arr1[$i] = $arr0[$this->def1[$i]];
			$arr1chr[$i] = chr($arr0[$this->def1[$i]]+65);
			$arr2[$i] = $arr0[$this->def2[$i]];
			$arr2chr[$i] = chr($arr0[$this->def2[$i]]+65);
		}
		
		$chk1sum=0;
		$chk2sum=0;
		for($i=1;$i<10;$i++){
			$chk1sum += $arr0[$i] * $this->def3[$i];
		}
		$chk1 = $this->_getChkCode($chk1sum);

		$arr3 = array_merge($arr1,$arr2);
		for($i=1;$i<10;$i++){
			$chk2sum += $arr3[$i] * $this->def3[$i];
		}
		$chk2 = $this->_getChkCode($chk2sum);
		
		$chk1chr = chr(($chk1 % 26) + 65);
		$chk2chr = chr(($chk2 % 26) + 65);
		$str = implode($arr1chr) . implode($arr2chr);
		$code = sprintf('TTII-%s-%s-%s%s%s',substr($str,0,4),substr($str,4,4),substr($str,-2),$chk1chr,$chk2chr);
		//$code = common::encryptString(time().self::check_code);
		return $code;
	}
	public function decode($code){
		$regex = array('/^(TTII[A-Z]{12})$/','/^(TTII(\-[A-Z]{4}){3})$/');
		$f = false;
		for($i=0;$i<count($regex);$i++){
			$v = preg_match($regex[$i],$code,$matches);
			if($v){
				$f = true;
				break;
			}
		}
		if(!$f) return false;	//don't match any
		if($i===1){
			$code=str_replace('-','',$code);
		}
		$str1=substr($code,4,5);
		$str2=substr($code,9,5);
		$chk1chr=substr($code,14,1);
		$chk2chr=substr($code,15,1);
		$arr1chr = str_split($str1);
		$arr2chr = str_split($str2);
		$arr0 = array();
		for($i=0;$i<5;$i++){
			$arr1[$i] = intval(ord($arr1chr[$i]) - 65);
			$arr2[$i] = intval(ord($arr2chr[$i]) - 65);
			$arr0[$this->def1[$i]] = $arr1[$i];
			$arr0[$this->def2[$i]] = $arr2[$i];
		}

		$chk1sum=0;
		$chk2sum=0;
		for($i=1;$i<10;$i++){
			$chk1sum += $arr0[$i] * $this->def3[$i];
		}
		$chk1 = $this->_getChkCode($chk1sum);

		$arr3 = array_merge($arr1,$arr2);
		for($i=1;$i<10;$i++){
			$chk2sum += $arr3[$i] * $this->def3[$i];
		}
		$chk2 = $this->_getChkCode($chk2sum);
		
		if(chr(($chk1 % 26) + 65)==substr($code,14,1) && $chk2chr = chr(($chk2 % 26) + 65)==substr($code,15,1)){
			$timestamp = intval(implode($arr0));
			return $timestamp;
		}
		return false;
	}
	
	//return false: insert fail
	//return string: code
	public function create($json_data){
		global $db;
		//$code = common::encryptString(time().self::check_code);
		$code = $this->getCode();
		$activecode = new activecode(&$db);
		$data = array();
		$data['code']=$code;
		$data['data']=$json_data;
		$data['enabled']=0;
		$data['createdate']=date('Y-m-d H:i:s');
		if(!$activecode->insert($data)){
			return false;
		}
		return $code;
	}

	public function isValid($code){
		$timestamp = $this->decode($code);
		if($timestamp>time()) return true;
		return false;
		/*
		$str = common::decryptString($code);
		$pattern = sprintf('/^(\d{10})(%s)/',self::check_code);
		if(preg_match($pattern,$str,$matches)===1){
			$chk = time()-intval($matches[1]);
			if($chk<0) return false;
			if($matches[2]!=self::check_code) return false;
		}else{
			return false;
		}
		return true;
		*/
	}
	
	public function check($code){
		global $db;
		//check in db
		$activecode = new activecode(&$db);
		$data = $activecode->getByID($code);
		if($data){
			return $data;
		}else{
			return false;
		}
	}

	public function regist($code,$buid){
		global $db;
		if(self::check($code)){
			$activecode = new activecode(&$db);
			$data1 = $activecode->getByID($code);

			$acdata = json_decode($data1['ac_data'],TRUE);
			$groups = $acdata['gid'];
			$arr_gid = explode(',',$groups);
			$sql1=<<<SQL
select * from bookshelf2_group_users
where g_id=%u and bu_id=%u;
SQL;
			$sql2=<<<SQL
insert into bookshelf2_group_users (g_id,bu_id) values (%u,%u);
SQL;
			foreach($arr_gid as $gid){
				$sql = sprintf($sql1,$gid,$buid);
				$row = $db->query_first($sql);

				if(!$row){
					$sql = sprintf($sql2,$gid,$buid);
					$db->query($sql);
				}
			}
/*
			$sql=<<<SQL
select TIMESTAMPADD('M',ac_term,createdate) as duedate
from bookshelf2_activecode
where bu_id=%u
	and CAST(ac_data as varchar(512))='%s'
order by registdate desc
limit 1
SQL;
			$sql = sprintf($sql,$buid,$data1['ac_data']);
*/
			$sql=<<<SQL
select TIMESTAMPADD('M',ac_term,registdate) as duedate
from bookshelf2_activecode a
where ac_code in(
select vag1.ac_code
from BOOKSHELF2_VIEW_ACTIVECODE_GROUP vag1
join BOOKSHELF2_VIEW_ACTIVECODE_GROUP vag2 on(vag1.bu_id=%u and vag1.num=vag2.num and vag1.g_id=vag2.g_id and vag2.ac_code='%s')
group by vag1.ac_code)
order by registdate desc
limit 1;
SQL;
			$sql = sprintf($sql,$buid,$code);
			$data2 = $db->query_first($sql);

			if(empty($data2)){
				$registdate = date('Y-m-d H:i:s');
			}else{
				$registdate = $data2['duedate'];
			}

			$activecode = new activecode(&$db);
			$data = array();
			$data['bu_id'] = $buid;
			$data['arr_gid'] = $arr_gid;
			$data['registdate']=$registdate;
			return $activecode->update($code,$data);
		}
		return false;
	}

	public function isRegist($code){
		global $db;
		$activecode = new activecode(&$db);
		$data = $activecode->getByID($code);
		return !empty($data['registdate']);
	}

	/*
	then reason why not create a mapping table for code & gid & user,
	is because increase elastic, for now has only one gid,
	maybe plan will contain many gid in the future.
	*/
	public function isExpired($buid,$arr_gid){
		global $db;
		$activecode = new activecode(&$db);
		$data = $activecode->getByBUID($buid);
		foreach($data as $row){
			$json=json_decode($row['ac_data']);
			$_arr_gid = explode(',',$json->gid);
			//should only one gid
			if(count($_arr_gid)>1){
				$ErrorHandler = new ErrorHandler(false);
				$ErrorHandler->Warning('417.91');
			}

			foreach($arr_gid as $gid){
				if(in_array($gid,$_arr_gid)){
					//go throuth all orders of this user of this plan
					//see if any order is not expired.
					$month = intval($row['ac_term']);
					$day = $month * self::days_in_a_month;
					$d = strtotime(sprintf('%s +%u day',$row['registdate'],$day));
					if($d>time()) return false;
				}
			}
		}
		//all order are expired
		return true;
	}
}
?>
