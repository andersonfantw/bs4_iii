<?PHP
/*
rule of tag
1. key is a uni-name of value
2. val is a reading name, can be different language
3. tag node => key : val = 1 : many
4. parent : child = 1 : many
5. can same value in different parent tag?
no, either 
 a. this tag can be a new branch
 b. they are actually different keys (same value but mean different things)
*/
class tag extends db_process{
	function tag($db) {
		parent::db_process($db,'tag','t_');
	}
	public function getByKey($key){
		$sql=<<<SQL
select t_id,key,val,path,type
from bookshelf2_view_tags vt
where key='%s'
SQL;
		$sql = sprintf($sql,$key);
		$rs = $this->db->get_results($sql);
		return $rs;
	}
	public function getTagsByBook($bid){
		$arr = array();
		$sql=<<<SQL
select t_id,key,val,path,type 
from bookshelf2_view_book_tags
where b_id=%u
SQL;
		$sql = sprintf($sql,$bid);
		$data = $this->db->get_results($sql);
		foreach($data as $row){
			$values = array();
			$sql=<<<SQL
select t_id,key,val,path,type
from bookshelf2_view_tags vt
where t_id in (%s)
order by t_id desc
SQL;
			$_path = $row['path'];
			$values = array();
			$values[] = array_values($row);
			if(!empty($_path)){
				$sql1 = sprintf($sql,$_path);
				$data1 = $this->db->get_results($sql1);
				foreach($data1 as $k => $v){
					$values[] = array_values($v);
					//array_unshift($values,array_values($v));
				}
			}
			//$values[] = array_values($row);
			//array_unshift($arr, $values);
			$arr[] = $values;
		}
		return $arr;
	}

	public function getTagByShortcut($ts_id,$seq){
		$arr = array();
		$sql=<<<SQL
select t_id,key,val,path,type
from bookshelf2_view_shortcut_tags vt
where vt.ts_id=%u and vt.seq=%u
SQL;
		$sql = sprintf($sql,$ts_id,$seq);
		$data = $this->db->get_results($sql);
		foreach($data as $row){
			$values = array();
			$sql=<<<SQL
select t_id,key,val,path,type
from bookshelf2_view_tags vt
where t_id in (%s)
order by t_id desc
SQL;
			$_path = $row['path'];
			$values = array();
			$values[] = array_values($row);
			if(!empty($_path)){
				$sql1 = sprintf($sql,$_path);
				$data1 = $this->db->get_results($sql1);
				foreach($data1 as $k => $v){
					$values[] = array_values($v);
					//array_unshift($values,array_values($v));
				}
			}
			//$values[] = array_values($row);
			//array_unshift($arr, $values);
			$arr[] = $values;
		}
		return $arr;
	}

	public function getSuggestByDropDownList($uid,$bid,$val=''){
		if(!empty($val)) $where = " and vt.val like'%".$val."%'";
		$sql=<<<SQL
select vt.val  from (
	select 1 as m, t_id, max(CreateDate) as CreateDate
	from bookshelf2_book_tag bt
	where CreateUser=%u
	and TIMESTAMPDIFF('m',CreateDate,now())<3600
	group by t_id
) as t
left join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where 1=1 %s
order by TIMESTAMPDIFF('m',CreateDate,now()) desc
limit 5
SQL;
		$sql = sprintf($sql,$uid,$where);
		$data1 = $this->db->get_results($sql);
		$sql=<<<SQL
select vt.val from (
	select 2 as m, t_id, count(*) as num
	from bookshelf2_book_tag bt
	where CreateUser=%u
	group by t_id
) as t
left join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where 1=1 %s
order by num desc
limit 5
SQL;
		$sql = sprintf($sql,$uid,$where);
		$data2 = $this->db->get_results($sql);
	
		$sql=<<<SQL
select vt.val from (
	select 3 as m, t_id, count(*) as num
	from bookshelf2_book_tag bt
	group by t_id
) as t
left join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where 1=1 %s
order by num desc
limit 5
SQL;
		$sql = sprintf($sql,$where);
		$data3 = $this->db->get_results($sql);
		$data = array();
		foreach($data1 as $r){
			$data[] = $r['val'];
		}
		foreach($data2 as $r){
			$data[] = $r['val'];
		}
		foreach($data3 as $r){
			$data[] = $r['val'];
		}
			$sql=<<<SQL
select vt.val,vt.refnum
from bookshelf2_view_tags vt
where vt.t_id not in (select t_id 
			from bookshelf2_book_tag
			where CreateUser=%u and b_id=%u)
order by refnum desc
limit 100
SQL;

			$num = count($arr);
			$sql = sprintf($sql,$uid,$bid);
			$data4 = $this->db->get_results($sql);
			foreach($data4 as $r){
				if($num++<30) $data[] = $r['val'];
			}
		$data = array_unique($data);
		$data = array_values($data);
		return $data;
	}

	public function getSuggestByChoosePanel($uid,$bid,$path='',$val=''){
		if(empty($path)){
			if(!empty($val)) $where = " and vt.val like'%".$val."%'";
			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum  from (
	select 1 as m, t_id, max(CreateDate) as CreateDate
	from bookshelf2_book_tag bt
	where CreateUser=%u
	and TIMESTAMPDIFF('m',CreateDate,now())<2880
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
		from bookshelf2_book_tag
		where CreateUser=%u and b_id=%u)
	%s
order by TIMESTAMPDIFF('m',CreateDate,now()) desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$uid,$bid,$where);
			$data1 = $this->db->get_results($sql);

			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum from (
	select 2 as m, t_id, count(*) as num
	from bookshelf2_book_tag bt
	where CreateUser=%u
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
			from bookshelf2_book_tag
			where CreateUser=%u and b_id=%u)
	%s
order by num desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$uid,$bid,$where);
			$data2 = $this->db->get_results($sql);
		
			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum from (
	select 3 as m, t_id, count(*) as num
	from bookshelf2_book_tag bt
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
			from bookshelf2_book_tag
			where CreateUser=%u and b_id=%u)
	%s
order by num desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$bid,$where);
			$data3 = $this->db->get_results($sql);

			$arr = array();
			$arrid = array();
			foreach($data1 as $r){
				$arrid[] = $r['t_id'];
				$arr[] = array_values($r);
			}
			foreach($data2 as $r){
				if(!in_array($r['t_id'],$arrid)){
					$arrid[] = $r['t_id'];
					$arr[] = array_values($r);
				}
			}
			foreach($data3 as $r){
				if(!in_array($r['t_id'],$arrid)){
					$arrid[] = $r['t_id'];
					$arr[] = array_values($r);
				}
			}

				if(empty($val)){
					$sql=<<<SQL
select vt.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum
from bookshelf2_view_tags vt
where pid=0
	and vt.t_id not in (select t_id 
				from bookshelf2_book_tag
				where CreateUser=%u and b_id=%u)
order by refnum desc
limit 100
SQL;
				}else{
					$sql=<<<SQL
select vt.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum
from bookshelf2_view_tags vt
where vt.t_id not in (select t_id 
			from bookshelf2_book_tag
			where CreateUser=%u and b_id=%u)
	and vt.val like '%%%s%%'
order by refnum desc
limit 30
SQL;
				}
				$sql = sprintf($sql,$uid,$bid,$val);
				$data4 = $this->db->get_results($sql);
				foreach($data4 as $r){
					if(!in_array($r['t_id'],$arrid)){
						$arrid[] = $r['t_id'];
						$arr[] = array_values($r);
					}
				}
			$data = array('path'=>$path,'item'=>$arr);
		}else{

			$sql=<<<SQL
select t_id,key,val,path,type,refnum,childnum
from bookshelf2_view_tags vt 
where path='%s'
	and vt.t_id not in (select t_id 
		from bookshelf2_book_tag
		where CreateUser=%u and b_id=%u)
SQL;
			$sql = sprintf($sql,$path,$uid,$bid);
			$data = $this->db->get_results($sql);
			$arr=array();
			foreach($data as $r){
				$arr[] = array_values($r);
			}
			$data = array('path'=>$path,'item'=>$arr);
		}
		return $data;
	}

	public function getSuggestByChoosePanelByShortcut($uid,$tsid,$seq,$path='',$val=''){
		if(empty($path)){
			if(!empty($val)) $where = " and vt.val like'%".$val."%'";
			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum  from (
	select 1 as m, t_id, max(CreateDate) as CreateDate
	from bookshelf2_tag_shortcut_tag bt
	where CreateUser=%u
	and TIMESTAMPDIFF('m',CreateDate,now())<2880
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
		from bookshelf2_tag_shortcut_tag
		where CreateUser=%u and ts_id=%u and seq=%u)
	%s
order by TIMESTAMPDIFF('m',CreateDate,now()) desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$uid,$tsid,$seq,$where);
			$data1 = $this->db->get_results($sql);

			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum from (
	select 2 as m, t_id, count(*) as num
	from bookshelf2_tag_shortcut_tag bt
	where CreateUser=%u
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
			from bookshelf2_tag_shortcut_tag
			where CreateUser=%u and ts_id=%u and seq=%u)
	%s
order by num desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$uid,$tsid,$seq,$where);
			$data2 = $this->db->get_results($sql);
		
			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum from (
	select 3 as m, t_id, count(*) as num
	from bookshelf2_tag_shortcut_tag bt
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
			from bookshelf2_tag_shortcut_tag
			where CreateUser=%u and ts_id=%u and seq=%u)
	%s
order by num desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$tsid,$seq,$where);
			$data3 = $this->db->get_results($sql);

			$arr = array();
			$arrid = array();
			foreach($data1 as $r){
				$arrid[] = $r['t_id'];
				$arr[] = array_values($r);
			}
			foreach($data2 as $r){
				if(!in_array($r['t_id'],$arrid)){
					$arrid[] = $r['t_id'];
					$arr[] = array_values($r);
				}
			}
			foreach($data3 as $r){
				if(!in_array($r['t_id'],$arrid)){
					$arrid[] = $r['t_id'];
					$arr[] = array_values($r);
				}
			}

			if(empty($val)){
				$sql=<<<SQL
select vt.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum
from bookshelf2_view_tags vt
where pid=0
	and vt.t_id not in (select t_id 
				from bookshelf2_tag_shortcut_tag
				where CreateUser=%u and ts_id=%u and seq=%u)
order by refnum desc
limit 30
SQL;
			}else{
					$sql=<<<SQL
select vt.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum
from bookshelf2_view_tags vt
where vt.t_id not in (select t_id 
			from bookshelf2_tag_shortcut_tag
			where CreateUser=%u and ts_id=%u and seq=%u)
	and vt.val like '%%%s%%'
order by refnum desc
limit 30
SQL;
			}
			$sql = sprintf($sql,$uid,$tsid,$seq,$val);
			$data4 = $this->db->get_results($sql);
			foreach($data4 as $r){
				if(!in_array($r['t_id'],$arrid)){
					$arrid[] = $r['t_id'];
					$arr[] = array_values($r);
				}
			}
			$data = array('path'=>$path,'item'=>$arr);
		}else{

			$sql=<<<SQL
select t_id,key,val,path,type,refnum,childnum
from bookshelf2_view_tags vt 
where path='%s'
	and vt.t_id not in (select t_id 
		from bookshelf2_tag_shortcut_tag
		where CreateUser=%u and ts_id=%u and seq=%u)
SQL;
			$sql = sprintf($sql,$path,$uid,$tsid,$seq);
			$data = $this->db->get_results($sql);
			$arr=array();
			foreach($data as $r){
				$arr[] = array_values($r);
			}
			$data = array('path'=>$path,'item'=>$arr);
		}
		return $data;
	}

	public function getSuggestSystemTagByChoosePanel($path=''){
		$sql=<<<SQL
select t_id,key,val,path,type,refnum,childnum
from bookshelf2_view_tags
where type=1 and path='%s'
SQL;
		$sql = sprintf($sql,$path);
		$data = $this->db->get_results($sql);
		$arr=array();
		foreach($data as $r){
			$arr[] = array_values($r);
		}
		$data = array('path'=>$path,'item'=>$arr);
		return $data;
	}

	public function getBooksByTSID($bsid,$tsid,$buid){
		//get seq set, default = 3
		$sql=<<<SQL
select seq, count(*) as num
from bookshelf2_tag_shortcut_tag
where ts_id=%u
group by seq
SQL;
		$sql = sprintf($sql,$tsid);
		$data = $this->db->get_results($sql);

		$arr = array();
		$arrid = array();
		foreach($data as $row){
		//get books form each set
			$sql=<<<SQL
select vb.*, concat('/%ss_',filename) as f_path,
	(select sum(testnum) from bookshelf2_view_has_test vt where vt.b_id=vb.b_id and vt.bu_id=%u) as testnum,
	(select sum(readnum) from bookshelf2_view_had_read_book vr where vr.b_id=vb.b_id and vr.bu_id=%u) as readnum
from bookshelf2_view_bookdetail vb
where vb.bs_id=%u and b_id in (select b_id
	from bookshelf2_book_tag
	where t_id in (
		select t_id
		from bookshelf2_tag_shortcut_tag
		where ts_id=%u and seq=%u
	)
	group by b_id
	having count(*)=%u)
SQL;
			$_sql = sprintf($sql,FILE_UPLOAD_PATH,$buid,$buid,$bsid,$tsid,$row['seq'],$row['num']);
			$data1 = $this->db->get_results($_sql);
			foreach($data1 as $r){
				if(!in_array($r['b_id'],$arrid)){
					$arrid[] = $r['b_id'];
					$arr[] = $r;
				}
			}
		}
		return $arr;
	}
	public function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
		$where_str = empty($where)?'':' and '.$where;
		$sql=<<<SQL
select *
from bookshelf2_view_tags
where 1=1 %s
SQL;
		$sql = sprintf($sql,$where_str);
		$sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);
  	$data['result'] = $this->db->get_results($sqlwithorder);
		$record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
  	$data['total'] = $record['record'];
		return $data;
	}
	public function getTag($kid,$vid,$parentid){
		$where = sprintf('tk_id=%u and tv_id=%u and pid=%u',$kid,$vid,$parentid);
		$data = $this->getList('',0,0,$where);
		if($data['total']==0){
			return null;
		}else{
			return $data['result'][0];
		}
	}
	public function getTagByPID($parentid){
		$sql=<<<SQL
select t_id,key,val,path,type
from bookshelf2_view_tags
where pid=%u
SQL;
		$sql = sprintf($sql,$parentid);
		$data = $this->db->get_results($sql);
		return $data;
	}
	public function getTagByPKey($pkey){
		$sql=<<<SQL
select t_id,key,val,path,type
from bookshelf2_view_tags
where ifnull(pkey,'')='%s'
SQL;
		$sql = sprintf($sql,$pkey);
		$data = $this->db->get_results($sql);
		return $data;
	}
	public function getTMListByPKey($pkey){
		$where = ' and pkey is null';
		if(!empty($pkey)){
		  $where = sprintf(" and pkey='%s'",$pkey);
		}
		$sql=<<<SQL
select t_id,key as id,val as text,path,type,
			(select count(*) from bookshelf2_view_tags vt1 where vt.t_id=vt1.pid) as children
from bookshelf2_view_tags vt
where 1=1 %s
SQL;
		$sql = sprintf($sql,$where);
		$data = $this->db->get_results($sql);
    for($i=0;$i<count($data);$i++){
    	$data[$i]['children']=!empty($data[$i]['children']);
    }
		$result=array();
		if(empty($pkey)){
			$result[0]=array();
	    $result[0]['id']='/';
	    $result[0]['text']='root';
	    $result[0]['icon']='folder';
	    $result[0]['children'] = $data;
	    //$data[0]['children'] = array(array('text'=>"New node", 'children'=>true, 'id'=>"New node", 'icon'=>"folder"),array('text'=>"New node 2", 'children'=>true, 'id'=>"New node 2", 'icon'=>"folder"));
	    $result[0]['state']=array('disabled'=>true,'opened'=>false);
		}else{
			$result = $data;
		}
		return $result;
	}
	public function getTagChildByPID($parentid){
		$sql=<<<SQL
select t_id,pid,key,val,path,type
from bookshelf2_view_tags
where pid in (select t_id
	from bookshelf2_tag
	where t_parent_id=%u)
SQL;
		$sql = sprintf($sql,$parentid);
		$data = $this->db->get_results($sql);
		return $data;
	}
	public function addTag($uid,$path,$key,$val,$type=0){
		global $ee;
		$type = intval($type);

		$arr = explode(',',$path);
		$parentid = array_pop($arr);
		if(empty($parentid)){
			$parentid=0;
		}else{
			$parentid=intval($parentid);
		}

		$uid = intval($uid);

		$kid = $this->_setKey($uid,$key);
		$vid = $this->_setVal($uid,$val);
		$tag = $this->getTag($kid,$vid,$parentid);
		/*
			warning msg:
			406.95: has same system tag
			406.96: has same user-define tag
		*/
		if($tag){
			if($tag['t_path']==$path){
				switch($tag['t_type']){
					case '0':
						if($type==0){
							$ee->Error('406.97');
						}else{
							$ee->Error('406.96');
						}
					case '1':
						if($type==1){
							$ee->Error('406.97');
						}else{
							$ee->Error('406.95');
						}
				}
			}
		}
		$tid = $this->_setTag($kid,$vid,$parentid,$path,$type);
		$data = array();
		$data['tid']=$tid;
		$data['kid']=$kid;
		$data['vid']=$vid;
		return $data;
	}

	public function getBookTagByID($bid,$tid){
		$sql=<<<SQL
select *
from bookshelf2_book_tag
where b_id=%u and t_id=%u
SQL;
		$sql = sprintf($sql,$bid,$tid);
		return $this->db->query_first($sql);
	}

	public function addBookTag($bid,$tid){
		$rs = $this->getBookTagByID($bid,$tid);
		if(empty($rs)){
			$book_tag = new db_process(&$this->db,'book_tag','');
			$data = array();
			$data['b_id'] = intval($bid);
			$data['t_id'] = intval($tid);
			$data['bt_type'] = 0;
			$data['Createuser'] = bssystem::getUID();
			$data['CreateDate'] = date('Y-m-d H:i:s');
			$book_tag = new db_process(&$this->db,'book_tag','bt_');
			$book_tag->insert($data);
		}
	}

	public function setTag($uid,$bid,$path,$key,$val){
		$parentid=0;
		if(!empty($path)){
			$arr = explode(',',$path);
			$parentid = array_pop($arr);
			$parentid = intval($parentid);
		}

		$kid = $this->_setKey($uid,$key);
		$vid = $this->_setVal($uid,$val);	
		$tid = $this->_setTag($kid,$vid,$parentid,$path);
		$result = $this->_setBookTag($uid,$bid,$tid);

		return $result;
	}
	public function setShortcutTag($tsid,$seq,$tid){
		$shortcut_tag = new db_process(&$this->db,'tag_shortcut_tag','ts_');
		$where = sprintf('ts_id=%u and seq=%u and t_id=%u',$tsid,$seq,$tid);
		$data = $shortcut_tag->getList('ts_id,seq,t_id',0,0,$where);
		if($data['total']==0){
			$data = array();
			$data['ts_id'] = intval($tsid);
			$data['seq'] = intval($seq);
			$data['t_id'] = intval($tid);
			$data['CreateUser'] = intval(bssystem::getUID());
			$data['CreateDate'] = date("Y-m-d H:i:s");
			$shortcut_tag->insert($data);
			return true;
		}else{
			return false;
		}
	}
	public function delBookTag($bid,$tid){
		global $ee;
		$bid = intval($bid);
		$this->_delTagRef($tid,$bid);
		$this->_minusTagNum($tid);
		//check tag used number
		$btnum = $this->_countBookTagRef($tid);
		//check is end node
		$tnum = $this->_countTagRef($tid);
		//check shortcut ref
		$snum = $this->_countShortcutRef($tid);

		if($btnum){
			return array('msg'=>'tag(s) is referenced by book(s)');
		}
		if($tnum){
			return array('msg'=>'tag(s) is referenced by tag(s)');
		}
		if($snum){
			return array('msg'=>'tag(s) is referenced by shortcut(s)');
		}

		if($btnum==0 && $tnum==0 && $snum==0){	
			$this->_delTag($tid,0);
			return true;
		}
	}
	public function delShortcutTag($tsid,$seq,$tid,$uid){
		global $ee;
		$sql=<<<SQL
delete from bookshelf2_tag_shortcut_tag
where ts_id=%u and seq=%u and t_id=%u
SQL;
		$sql = sprintf($sql,$tsid,$seq,$tid,$uid);
		$data = $this->db->query($sql);
		return true;
	}
	public function delSysTag($path,$tid){
		global $ee;
		//check tag used number
		$btnum = $this->_countBookTagRef($tid);
		//check is end node
		$tnum = $this->_countTagRef($tid);

		/*
			warning msg:
			406.93: Delete failed! Cannot delete while has book reference,
			406.94: Delete failed! Cannot delete while has children tag.
		*/

		if($btnum>0){
			return array('code'=>'406.93','msg'=>'book tag(s) referenced');
		}
		if($tnum>0){
			return array('code'=>'406.94','msg'=>'has children tag(s)');
		}
		if($btnum==0 && $tnum==0){
			$this->_delTag($tid,1);
			return true;
		}
	}
	private function _setKey($uid,$key){
		$tagkey = new db_process(&$this->db,'tagkey','tk_');

		$row = $tagkey->getByName($key);
		if(empty($row)){
			$data=array();
			$data['tk_name']=$key;
			$data['tk_system']=0;
			$data['CreateUser']=$uid;
			//$data['CreateDate']=time();
			$kid = intval($tagkey->insert($data,true));
		}else{
			$kid = intval($row['tk_id']);
		}
		return $kid;
	}
	private function _setVal($uid,$val){
		$tagval = new db_process(&$this->db,'tagval','tv_');
		$row = $tagval->getByName($val);
		if(empty($row)){
			$data=array();
			$data['tv_name']=$val;
			$data['tv_system']=0;
			$data['CreateUser']=$uid;
			//$data['CreateDate']=time();
			$vid = intval($tagval->insert($data,true));
		}else{
			$vid = intval($row['tv_id']);
		}
		return $vid;
	}
	private function _setTag($kid,$vid,$parentid,$path,$type=0){
		$type = intval($type);

		$tid = $this->_getTag($kid,$vid,$parentid);
		if(!$tid){
			$data = array();
			$data['tk_id'] = $kid;
			$data['tv_id'] = $vid;
			$data['t_parent_id'] = $parentid;
			$data['t_path'] = $path;
			$data['t_type'] = $type;
			$data['t_refnum'] = 0;
			$tid = intval($this->insert($data,true));
		}
		return $tid;
	}
	private function _setBookTag($uid,$bid,$tid){
		$book_tag = new db_process(&$this->db,'book_tag','bt_');

		$where = sprintf('b_id=%u and t_id=%u',$bid,$tid);
		$data = $book_tag->getList('b_id,t_id',0,0,$where);
		if($data['total']==0){
			$data = array();
			$data['b_id']=$bid;
			$data['t_id']=$tid;
			$data['bt_type']=0;
			$data['CreateUser']=$uid;
			//$data['CreateDate']=time();
			$book_tag->insert($data);

			$this->_plusTagNum($tid);
			return true;
		}else{
			return false;
		}
	}
	private function _delTag($tid,$type=null){
		$where='';
		if(!is_null($type)){
			$where=sprintf('and t_type=%u',$type);
		}
		$sql=<<<SQL
delete from bookshelf2_tag
where t_id=%u %s
SQL;
		$sql= sprintf($sql,$tid,$where);
		$this->db->query($sql);
	}
	private function _plusTagNum($tid){
		$sql=<<<SQL
update bookshelf2_tag
set t_refnum=t_refnum+1
where t_id=%u
SQL;
		$sql = sprintf($sql,$tid);
		$this->db->query($sql);
	}
	private function _minusTagNum($tid){
		$sql=<<<SQL
update bookshelf2_tag
set t_refnum=t_refnum-1
where t_id=%u and t_refnum>0
SQL;
		$sql = sprintf($sql,$tid);
		$this->db->query($sql);
	}
	private function _delTagRef($tid,$bid){
		//remove link
		$sql =<<<SQL
delete from bookshelf2_book_tag
where t_id=%u
	and b_id=%u
SQL;
		$sql= sprintf($sql,$tid,$bid);
		$this->db->query($sql);
	}
	private function _countBookTagRef($tid){
		//check tag used number
		$sql=<<<SQL
select count(*) as num
from bookshelf2_book_tag
where t_id=%u
SQL;
		$sql = sprintf($sql,$tid);
		$data = $this->db->query_first($sql);
		return $data['num'];
	}
	private function _countTagRef($tid){
		//check tag used number
		$sql=<<<SQL
select count(*) as num
from bookshelf2_tag
where t_parent_id=%u
SQL;
		$sql = sprintf($sql,$tid);
		$data = $this->db->query_first($sql);
		return $data['num'];
	}
	private function _countShortcutRef($tid){
		$sql=<<<SQL
select count(*) as num
from bookshelf2_tag_shortcut_tag
where t_id=%u
SQL;
		$sql = sprintf($sql,$tid);
		$data = $this->db->query_first($sql);
		return $data['num'];
	}
	private function _getTag($kid,$vid,$parentid){
		$type = intval($type);

		$where = sprintf('tk_id=%u and tv_id=%u and pid=%u',$kid,$vid,$parentid);
		$data = $this->getList('',0,0,$where);
		if($data['total']==0){
			return 0;
		}else{
			return intval($data['result'][0]['t_id']);
		}
	}

	public function getDictionary($id){
		$sql=<<<SQL
select dockey,quizid,t_id
from bookshelf2_tag_dictionary
where dockey='%s'
SQL;
		$sql = sprintf($sql,$id);
		$data = $db->get_results($sql);
		return $data;
	}
	
/*
bookshelf2_tag_dictionary //getQuizSysTag
bookshelf2_scanexam_exercise_tag
bookshelf2_itutor_exercise_tag
bookshelf2_scanexam_quiz
bookshelf2_itutor_exercise
*/
	public function getScanexamQuiz($sekey,$setdate){
		$data = array();
		$sql=<<<SQL
select se_key as dockey,
	seq,
	seq_reportid as reportid
from bookshelf2_scanexam_quiz sq
where se_key='%s'
SQL;
		$sql = sprintf($sql,$sekey);
		$data = $this->db->get_results($sql);
		return $data;
	}
	public function getItutorQuiz($dockey){
		$data = array();
		$sql=<<<SQL
select i.id as dockey,
	e_reportid as seq,
	e_reportid as reportid
from bookshelf2_itutor_exercise ie
left join bookshelf2_itutor i on(ie.i_id=i.i_id)
where i.id='%s'
group by i.id, ie.e_reportid
SQL;
		$sql = sprintf($sql,$dockey);
		$data = $this->db->get_results($sql);
		return $data;
	}
	public function getQuizSysTag($dockey,$quizid){
		$arr = array();
		$sql=<<<SQL
select vt.t_id,key,val,path,type 
from bookshelf2_tag_dictionary td
left join bookshelf2_view_tags vt on(td.t_id=vt.t_id)
where dockey='%s' and quizid='%s'
SQL;
		$sql = sprintf($sql,$dockey,$quizid);
		$data = $this->db->get_results($sql);
		foreach($data as $row){
			$values = array();
			$sql=<<<SQL
select t_id,key,val,path,type
from bookshelf2_view_tags vt
where t_id in (%s)
order by t_id desc
SQL;
			$_path = $row['path'];
			$values = array();
			$values[] = array_values($row);
			if(!empty($_path)){
				$sql1 = sprintf($sql,$_path);
				$data1 = $this->db->get_results($sql1);
				foreach($data1 as $k => $v){
					$values[] = array_values($v);
					//array_unshift($values,array_values($v));
				}
			}
			//$values[] = array_values($row);
			//array_unshift($arr, $values);
			$arr[] = $values;
		}
		return $arr;
	}
	public function getScanexamQuizTag($sekey,$setdate,$seq){
		$arr = array();
		$sql=<<<SQL
select vt.t_id,key,val,path,type 
from bookshelf2_scanexam_exercise_tag et
left join bookshelf2_view_tags vt on(et.t_id=vt.t_id)
where se_key='%s' and set_date='%s' and seq=%u
SQL;
		$sql = sprintf($sql,$sekey,$setdate,$seq);
		$data = $this->db->get_results($sql);
		foreach($data as $row){
			$values = array();
			$sql=<<<SQL
select t_id,key,val,path,type
from bookshelf2_view_tags vt
where t_id in (%s)
order by t_id desc
SQL;
			$_path = $row['path'];
			$values = array();
			$values[] = array_values($row);
			if(!empty($_path)){
				$sql1 = sprintf($sql,$_path);
				$data1 = $this->db->get_results($sql1);
				foreach($data1 as $k => $v){
					$values[] = array_values($v);
					//array_unshift($values,array_values($v));
				}
			}
			//$values[] = array_values($row);
			//array_unshift($arr, $values);
			$arr[] = $values;
		}
		return $arr;
	}
	public function getItutorQuizTag($dockey,$reportid){
		$arr = array();
		$sql=<<<SQL
select vt.t_id,key,val,path,type 
from bookshelf2_itutor_exercise_tag iet
left join bookshelf2_view_tags vt on(iet.t_id=vt.t_id)
where dockey='%s' and e_reportid='%s'
SQL;
		$sql = sprintf($sql,$dockey,$reportid);
		$data = $this->db->get_results($sql);
		foreach($data as $row){
			$values = array();
			$sql=<<<SQL
select t_id,key,val,path,type
from bookshelf2_view_tags vt
where t_id in (%s)
order by t_id desc
SQL;
			$_path = $row['path'];
			$values = array();
			$values[] = array_values($row);
			if(!empty($_path)){
				$sql1 = sprintf($sql,$_path);
				$data1 = $this->db->get_results($sql1);
				foreach($data1 as $k => $v){
					$values[] = array_values($v);
				}
			}
			$arr[] = $values;
		}
		return $arr;
	}
	public function getSuggestByChoosePanelByTagquizItutor($uid,$dockey,$reportid,$path='',$val=''){
		if(empty($path)){
			if(!empty($val)) $where = " and vt.val like'%".$val."%'";
			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum  from (
	select 1 as m, t_id, max(CreateDate) as CreateDate
	from bookshelf2_itutor_exercise_tag iet
	where CreateUser=%u
	and TIMESTAMPDIFF('m',CreateDate,now())<2880
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
		from bookshelf2_itutor_exercise_tag
		where CreateUser=%u and dockey='%u' and e_reportid='%u')
	%s
order by TIMESTAMPDIFF('m',CreateDate,now()) desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$uid,$dockey,$reportid,$where);
			$data1 = $this->db->get_results($sql);

			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum from (
	select 2 as m, t_id, count(*) as num
	from bookshelf2_itutor_exercise_tag iet
	where CreateUser=%u
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
			from bookshelf2_itutor_exercise_tag
			where CreateUser=%u and dockey='%u' and e_reportid='%u')
	%s
order by num desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$uid,$dockey,$reportid,$where);
			$data2 = $this->db->get_results($sql);
		
			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum from (
	select 3 as m, t_id, count(*) as num
	from bookshelf2_itutor_exercise_tag iet
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
			from bookshelf2_itutor_exercise_tag
			where CreateUser=%u and dockey='%u' and e_reportid='%u')
	%s
order by num desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$dockey,$reportid,$where);
			$data3 = $this->db->get_results($sql);

			$arr = array();
			$arrid = array();
			foreach($data1 as $r){
				$arrid[] = $r['t_id'];
				$arr[] = array_values($r);
			}
			foreach($data2 as $r){
				if(!in_array($r['t_id'],$arrid)){
					$arrid[] = $r['t_id'];
					$arr[] = array_values($r);
				}
			}
			foreach($data3 as $r){
				if(!in_array($r['t_id'],$arrid)){
					$arrid[] = $r['t_id'];
					$arr[] = array_values($r);
				}
			}

				if(empty($val)){
					$sql=<<<SQL
select vt.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum
from bookshelf2_view_tags vt
where pid=0
	and vt.t_id not in (select t_id 
				from bookshelf2_itutor_exercise_tag
				where CreateUser=%u and dockey='%u' and e_reportid='%u')
order by refnum desc
limit 100
SQL;
				}else{
					$sql=<<<SQL
select vt.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum
from bookshelf2_view_tags vt
where vt.t_id not in (select t_id 
			from bookshelf2_itutor_exercise_tag
			where CreateUser=%u and dockey='%u' and e_reportid='%u')
	and vt.val like '%%%s%%'
order by refnum desc
limit 30
SQL;
				}
				$sql = sprintf($sql,$uid,$dockey,$reportid,$val);
				$data4 = $this->db->get_results($sql);
				foreach($data4 as $r){
					if(!in_array($r['t_id'],$arrid)){
						$arrid[] = $r['t_id'];
						$arr[] = array_values($r);
					}
				}
			$data = array('path'=>$path,'item'=>$arr);
		}else{

			$sql=<<<SQL
select t_id,key,val,path,type,refnum,childnum
from bookshelf2_view_tags vt 
where path='%s'
	and vt.t_id not in (select t_id 
		from bookshelf2_itutor_exercise_tag
		where CreateUser=%u and dockey='%u' and e_reportid='%u')
SQL;
			$sql = sprintf($sql,$path,$dockey,$reportid,$bid);
			$data = $this->db->get_results($sql);
			$arr=array();
			foreach($data as $r){
				$arr[] = array_values($r);
			}
			$data = array('path'=>$path,'item'=>$arr);
		}
		return $data;
	}
	public function getSuggestByChoosePanelByTagquizInfoacer($uid,$dockey,$date,$seq,$path='',$val=''){
		if(empty($path)){
			if(!empty($val)) $where = " and vt.val like'%".$val."%'";
			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum  from (
	select 1 as m, t_id, max(CreateDate) as CreateDate
	from bookshelf2_scanexam_exercise_tag et
	where CreateUser=%u
	and TIMESTAMPDIFF('m',CreateDate,now())<2880
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
		from bookshelf2_scanexam_exercise_tag
		where CreateUser=%u and se_key='%u' and set_date=%u and seq=%u)
	%s
order by TIMESTAMPDIFF('m',CreateDate,now()) desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$uid,$dockey,$date,$seq,$where);
			$data1 = $this->db->get_results($sql);

			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum from (
	select 2 as m, t_id, count(*) as num
	from bookshelf2_scanexam_exercise_tag et
	where CreateUser=%u
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
			from bookshelf2_scanexam_exercise_tag
			where CreateUser=%u and se_key='%u' and set_date=%u and seq=%u)
	%s
order by num desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$uid,$dockey,$date,$seq,$where);
			$data2 = $this->db->get_results($sql);
		
			$sql=<<<SQL
select t.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum from (
	select 3 as m, t_id, count(*) as num
	from bookshelf2_scanexam_exercise_tag et
	group by t_id
) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
where t.t_id not in (select t_id 
			from bookshelf2_scanexam_exercise_tag
			where CreateUser=%u and se_key='%u' and set_date=%u and seq=%u)
	%s
order by num desc
limit 5
SQL;
			$sql = sprintf($sql,$uid,$dockey,$date,$seq,$where);
			$data3 = $this->db->get_results($sql);

			$arr = array();
			$arrid = array();
			foreach($data1 as $r){
				$arrid[] = $r['t_id'];
				$arr[] = array_values($r);
			}
			foreach($data2 as $r){
				if(!in_array($r['t_id'],$arrid)){
					$arrid[] = $r['t_id'];
					$arr[] = array_values($r);
				}
			}
			foreach($data3 as $r){
				if(!in_array($r['t_id'],$arrid)){
					$arrid[] = $r['t_id'];
					$arr[] = array_values($r);
				}
			}

				if(empty($val)){
					$sql=<<<SQL
select vt.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum
from bookshelf2_view_tags vt
where pid=0
	and vt.t_id not in (select t_id 
				from bookshelf2_scanexam_exercise_tag et
				where CreateUser=%u and se_key='%u' and set_date=%u and seq=%u)
order by refnum desc
limit 100
SQL;
				}else{
					$sql=<<<SQL
select vt.t_id,vt.key,vt.val,vt.path,vt.type,vt.refnum,vt.childnum
from bookshelf2_view_tags vt
where vt.t_id not in (select t_id 
			from bookshelf2_scanexam_exercise_tag
			where CreateUser=%u and and se_key='%u' and set_date=%u and seq=%u)
	and vt.val like '%%%s%%'
order by refnum desc
limit 30
SQL;
				}
				$sql = sprintf($sql,$uid,$dockey,$date,$seq,$val);
				$data4 = $this->db->get_results($sql);
				foreach($data4 as $r){
					if(!in_array($r['t_id'],$arrid)){
						$arrid[] = $r['t_id'];
						$arr[] = array_values($r);
					}
				}
			$data = array('path'=>$path,'item'=>$arr);
		}else{

			$sql=<<<SQL
select t_id,key,val,path,type,refnum,childnum
from bookshelf2_view_tags vt 
where path='%s'
	and vt.t_id not in (select t_id 
		from bookshelf2_scanexam_exercise_tag
		where CreateUser=%u and se_key='%u' and set_date=%u and seq=%u)
SQL;
			$sql = sprintf($sql,$path,$uid,$dockey,$date,$seq);
			$data = $this->db->get_results($sql);
			$arr=array();
			foreach($data as $r){
				$arr[] = array_values($r);
			}
			$data = array('path'=>$path,'item'=>$arr);
		}
		return $data;
	}

	function getTagsByPKey($keys){
		if(empty($keys)){
			$keys="Subject','edu";
			$sql=<<<SQL
select t_id,pid,key,val,path,type
from bookshelf2_view_tags
where key in ('%s')
SQL;
		}else{
			$sql=<<<SQL
select t_id,pid,key,val,path,type
from bookshelf2_view_tags
where pkey in ('%s')
SQL;
		}
		$sql = sprintf($sql,$keys);
		$data = $this->db->get_results($sql);
		return $data;
	}
}
?>
