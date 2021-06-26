<?PHP
/*******************************************************************
all caculate excude bookshelf manager (bu_id=0)
*******************************************************************/
class chart{
  public function setPeriod($p){
  }
  public function setType($t){
  }
  public function setQuery($q){
  }
	public function bs_summary(){
		global $db;
		$account = new account(&$db);
		$data = array();
		$sql=<<<SQL
select 'Enabled' as label, count(*) as y
from bookshelf2_bookshelfs
where bs_status=1
union
select 'Disabled' as label,count(*) as y
from bookshelf2_bookshelfs
where bs_status=0
SQL;
		$rs = $db->get_results($sql);
    for($i=0;$i<count($rs);$i++){
            $rs[$i]['y'] = intval($rs[$i]['y']);
    }
		$data['total']=array(
			'title'=>LANG_CHART_API_BOOKSHELF_SUMMARY_TOTAL,
			'type'=>'pie',
			'data'=>$rs
		);

		$sql=<<<SQL
select bs.bs_id as id,bs.bs_name as name,num,
	concat(concat(f.f_name,'.'),f.f_type) as filename
from (select bs_id,count(*) as num
	from bookshelf2_books b
	group by bs_id) as t
join bookshelf2_bookshelfs bs on(t.bs_id=bs.bs_id)
left join bookshelf2_file f on(bs.bs_header=f.f_id) 
order by num desc
limit 1
SQL;
		$rs = $db->get_results($sql);
		if($rs){
			$uid = $account->getUIDByBSID($rs[0]['id']);
			$rs[0]['image'] = HostManager::getBookshelfBase(false,false,$uid,$rs[0]['id']).'/uploadfiles/'.$rs[0]['filename'];
		}
		$data['MostBook'] = array(
			'title'=>LANG_CHART_API_BOOKSHELF_SUMMARY_HAS_MOST_BOOK,
			'type'=>'image',
			'data'=>$rs
		);

		$sql=<<<SQL
select bs.bs_id as id,bs.bs_name as name,num,
	concat(concat(f.f_name,'.'),f.f_type) as filename
from (select bs_id,count(*) as num
	from bookshelf2_books b
	join bookshelf2_reading_time rt on(b.b_id=rt.b_id and rt.bu_id>0)
	group by bs_id,bu_id) as t
join bookshelf2_bookshelfs bs on(t.bs_id=bs.bs_id)
left join bookshelf2_file f on(bs.bs_header=f.f_id) 
order by num desc
limit 1
SQL;
		$rs = $db->get_results($sql);
		if($rs){
			$uid = $account->getUIDByBSID($rs[0]['id']);
			$rs[0]['image'] = HostManager::getBookshelfBase(false,false,$uid,$rs[0]['id']).'/uploadfiles/'.$rs[0]['filename'];
		}
		$data['MostRead'] = array(
			'title'=>LANG_CHART_API_BOOKSHELF_SUMMARY_HAS_MOST_READ,
			'type'=>'image',
			'data'=>$rs
		);

		$sql=<<<SQL
select bs.bs_id as id,bs.bs_name as name,num,
	concat(concat(f.f_name,'.'),f.f_type) as filename
from (select b.bs_id,sum(timestampdiff('m',start_time,end_time)) as num
	from bookshelf2_books b
	join bookshelf2_reading_time rt on(b.b_id=rt.b_id and rt.bu_id>0)
	group by bs_id) as t
join bookshelf2_bookshelfs bs on(t.bs_id=bs.bs_id)
left join bookshelf2_file f on(bs.bs_header=f.f_id) 
order by num desc
limit 1
SQL;
		$rs = $db->get_results($sql);
		if($rs){
			$uid = $account->getUIDByBSID($rs[0]['id']);
			$rs[0]['image'] = HostManager::getBookshelfBase(false,false,$uid,$rs[0]['id']).'/uploadfiles/'.$rs[0]['filename'];
		}
		$data['MostReading'] = array(
			'title'=>LANG_CHART_API_BOOKSHELF_SUMMARY_HAS_MOST_READING,
			'type'=>'image',
			'data'=>$rs
		);
		return $data;
	}
	public function bs_mostbooks(){

	}
	public function bs_mostread(){
		$sql=<<<SQL

SQL;
	}
	public function bs_mostreading(){
		$sql=<<<SQL
SQL;
	}

	public function book_summary(){
		global $db;
		$account = new account(&$db);
		$data = array();
		$sql=<<<SQL
select 'Enabled' as label, count(*) as y
from bookshelf2_books
where b_status=1
union
select 'Disabled' as label,count(*) as y
from bookshelf2_books
where b_status=0
SQL;
		$rs = $db->get_results($sql);
    for($i=0;$i<count($rs);$i++){
            $rs[$i]['y'] = intval($rs[$i]['y']);
    }
		$data['total']=array(
			'title'=>LANG_CHART_API_BOOK_SUMMARY_TOTAL,
			'type'=>'pie',
			'data'=>$rs
		);

		$sql=<<<SQL
select b1.bs_id,b1.b_id as id,b1.b_name as name,num,
	concat(concat(f.f_name,'.'),f.f_type) as filename
from (select b.b_id,count(*) as num
	from bookshelf2_books b
	join bookshelf2_reading_time rt on(b.b_id=rt.b_id and rt.bu_id>0)
	group by b_id,bu_id) as t
join bookshelf2_books b1 on(b1.b_id=t.b_id)
left join bookshelf2_file f on(b1.file_id=f.f_id) 
order by num desc
limit 1
SQL;
		$rs = $db->get_results($sql);
		if($rs){
			$uid = $account->getUIDByBSID($rs[0]['bs_id']);
			$rs[0]['image'] = HostManager::getBookshelfBase(false,false,$uid,$rs[0]['bs_id']).'/uploadfiles/s_'.$rs[0]['filename'];
		}
		$data['MostReadOfUser'] = array(
			'title'=>LANG_CHART_API_BOOK_SUMMARY_MOST_READ_OF_USER,
			'type'=>'image',
			'data'=>$rs
		);


		$sql=<<<SQL
select b1.bs_id,b1.b_id as id,b1.b_name as name,num,
	concat(concat(f.f_name,'.'),f.f_type) as filename
from (select b.b_id,count(*) as num
	from bookshelf2_books b
	join bookshelf2_reading_time rt on(b.b_id=rt.b_id and rt.bu_id=0)
	group by b_id,bu_id) as t
join bookshelf2_books b1 on(b1.b_id=t.b_id)
left join bookshelf2_file f on(b1.file_id=f.f_id) 
order by num desc
limit 1
SQL;
		$rs = $db->get_results($sql);
    $uid = $account->getUIDByBSID($rs[0]['bs_id']);
    $rs[0]['image'] = HostManager::getBookshelfBase(false,false,$uid,$rs[0]['bs_id']).'/uploadfiles/s_'.$rs[0]['filename'];
		$data['MostReadOfManager'] = array(
			'title'=>LANG_CHART_API_BOOK_SUMMARY_MOST_READ_OF_MANAGER,
			'type'=>'image',
			'data'=>$rs
		);

		$sql=<<<SQL
select b1.bs_id,b1.b_id as id,b1.b_name as name,num,
	concat(concat(f.f_name,'.'),f.f_type) as filename
from (select b.b_id,sum(timestampdiff('m',start_time,end_time)) as num
	from bookshelf2_books b
	join bookshelf2_reading_time rt on(b.b_id=rt.b_id and rt.bu_id>0)
	group by b_id) as t
join bookshelf2_books b1 on(b1.b_id=t.b_id)
left join bookshelf2_file f on(b1.file_id=f.f_id) 
order by num desc
limit 1
SQL;
		$rs = $db->get_results($sql);
    $uid = $account->getUIDByBSID($rs[0]['bs_id']);
    $rs[0]['image'] = HostManager::getBookshelfBase(false,false,$uid,$rs[0]['bs_id']).'/uploadfiles/s_'.$rs[0]['filename'];
		$data['MostReadingOfUser'] = array(
			'title'=>LANG_CHART_API_BOOK_SUMMARY_MOST_READING_OF_USER,
			'type'=>'image',
			'data'=>$rs
		);

		$sql=<<<SQL
select b1.bs_id,b1.b_id as id,b1.b_name as name,num,
	concat(concat(f.f_name,'.'),f.f_type) as filename
from (select b.b_id,sum(timestampdiff('m',start_time,end_time)) as num
	from bookshelf2_books b
	join bookshelf2_reading_time rt on(b.b_id=rt.b_id and rt.bu_id=0)
	group by b_id) as t
join bookshelf2_books b1 on(b1.b_id=t.b_id)
left join bookshelf2_file f on(b1.file_id=f.f_id) 
order by num desc
limit 1
SQL;
		$rs = $db->get_results($sql);
    $uid = $account->getUIDByBSID($rs[0]['bs_id']);
    $rs[0]['image'] = HostManager::getBookshelfBase(false,false,$uid,$rs[0]['bs_id']).'/uploadfiles/s_'.$rs[0]['filename'];
		$data['MostReadingOfManager'] = array(
			'title'=>LANG_CHART_API_BOOK_SUMMARY_MOST_READING_OF_MANAGER,
			'type'=>'image',
			'data'=>$rs
		);
		return $data;
	}
	public function book_mostread(){
		$sql=<<<SQL
SQL;
	}
	public function book_mostreading(){
		$sql=<<<SQL
SQL;
	}

	public function user_summary(){
		global $db;
		$data = array();
		$sql=<<<SQL
select count(*) as total
from bookshelf2_bookshelf_users
SQL;
		$rs = $db->query_first($sql);
		$data['total'] = array(
			'title'=>LANG_CHART_API_USER_SUMMARY_TOTAL,
			'type'=>'image',
			'data'=>array(0=>array(
				'name'=>'User',
				'num'=>$rs['total']
			))
		);

		$sql=<<<SQL
select os,
	(select count(*) from bookshelf2_login l1
		where l1.os=t.os and type='-'
		and session_id not in (select session_id from bookshelf2_login where type='a' or type='u')
	) as notlogin,
	(select count(*) from bookshelf2_login l1 where l1.os=t.os and type='a') as manager,
	(select count(*) from bookshelf2_login l1 where l1.os=t.os and type='u') as user
from (select os
from bookshelf2_login l
group by os) as t
SQL;
		$rs = $db->get_results($sql);
		$val_notlogin=array();
		$val_manager=array();
		$val_user=array();
		for($i=0;$i<count($rs);$i++){
			$val_notlogin[$i]['x']=$rs[$i]['os'];
			$val_notlogin[$i]['y']=$rs[$i]['notlogin'];
			$val_manager[$i]['x']=$rs[$i]['os'];
			$val_manager[$i]['y']=$rs[$i]['manager'];
			$val_user[$i]['x']=$rs[$i]['os'];
			$val_user[$i]['y']=$rs[$i]['user'];
		}
		$data['os'] = array(
			'title'=>LANG_CHART_API_USER_SUMMARY_OS,
			'type'=>'stackedBar',
			'data'=>array(
				'notlogin'=>$val_notlogin,
				'manager'=>$val_manager,
				'user'=>$val_user
			)
		);

		$sql=<<<SQL
select browser,
	(select count(*) from bookshelf2_login l1
		where l1.browser=t.browser and type='-'
		and session_id not in (select session_id from bookshelf2_login where type='a' or type='u')
	) as notlogin,
	(select count(*) from bookshelf2_login l1 where l1.browser=t.browser and type='a') as manager,
	(select count(*) from bookshelf2_login l1 where l1.browser=t.browser and type='u') as user
from (select browser
from bookshelf2_login l
group by browser) as t
SQL;
		$rs = $db->get_results($sql);
		$val_notlogin=array();
		$val_manager=array();
		$val_user=array();
		for($i=0;$i<count($rs);$i++){
			$val_notlogin[$i]['x']=$rs[$i]['browser'];
			$val_notlogin[$i]['y']=$rs[$i]['notlogin'];
			$val_manager[$i]['x']=$rs[$i]['browser'];
			$val_manager[$i]['y']=$rs[$i]['manager'];
			$val_user[$i]['x']=$rs[$i]['browser'];
			$val_user[$i]['y']=$rs[$i]['user'];
		}
		$data['browser'] = array(
			'title'=>LANG_CHART_API_USER_SUMMARY_BROWSER,
			'type'=>'stackedBar',
			'data'=>array(
				'notlogin'=>$val_notlogin,
				'manager'=>$val_manager,
				'user'=>$val_user
			)
		);


		$sql=<<<SQL
select device,
	(select count(*) from bookshelf2_login l1
		where l1.device=t.device and type='-'
		and session_id not in (select session_id from bookshelf2_login where type='a' or type='u')
	) as notlogin,
	(select count(*) from bookshelf2_login l1 where l1.device=t.device and type='a') as manager,
	(select count(*) from bookshelf2_login l1 where l1.device=t.device and type='u') as user
from (select device
from bookshelf2_login l
group by device) as t
SQL;
		$rs = $db->get_results($sql);
		$val_notlogin=array();
		$val_manager=array();
		$val_user=array();
		for($i=0;$i<count($rs);$i++){
			if(empty($rs[$i]['device'])) $rs[$i]['device']='PC';
			$val_notlogin[$i]['x']=$rs[$i]['device'];
			$val_notlogin[$i]['y']=$rs[$i]['notlogin'];
			$val_manager[$i]['x']=$rs[$i]['device'];
			$val_manager[$i]['y']=$rs[$i]['manager'];
			$val_user[$i]['x']=$rs[$i]['device'];
			$val_user[$i]['y']=$rs[$i]['user'];
		}
		$data['device'] = array(
			'title'=>LANG_CHART_API_USER_SUMMARY_DEVICE,
			'type'=>'stackedBar',
			'data'=>array(
				'notlogin'=>$val_notlogin,
				'manager'=>$val_manager,
				'user'=>$val_user
			)
		);
		return $data;
	}
	public function user_usage(){
		$sql=<<<SQL
SQL;
	}

	public function tag_summary(){
		global $db;
		$data = array();
		$sql=<<<SQL
select count(*) as total
from bookshelf2_tag;
SQL;
		$rs = $db->query_first($sql);
		$data['total'] = array(
			'title'=>LANG_CHART_API_TAG_SUMMARY_TOTAL,
			'type'=>'image',
			'data'=>array(0=>array(
				'name'=>'Tag',
				'num'=>$rs['total']
			))
		);

		$sql=<<<SQL
select vt.t_id,key,val,num
from (select t_id,count(*) as num
	from bookshelf2_book_tag
	group by t_id) as t
join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
order by num desc
limit 1
SQL;
		$rs = $db->query_first($sql);
		$data['MostRef'] = array(
			'title'=>LANG_CHART_API_TAG_SUMMARY_MOST_REFERENCE,
			'type'=>'image',
			'data'=>array(0=>array(
				'name'=>$rs['key'].':'.$rs['val'],
				'num'=>$rs['num']
			))
		);
		return $data;
	}
	public function tag_distribution(){
		$sql=<<<SQL
SQL;
	}
	public function tag_mostref(){
		$sql=<<<SQL
SQL;
	}
	public function getLearningHistory($tid,$gid=0,$buid=0){
		global $db;
		$data = array();
		if(!empty($buid)){
			$where_str = sprintf(' and (bu_id=%u or bu_id is null)',$buid);
		}
		if(!empty($gid)){
			$where_str = sprintf(' and (bu_id in (select bu_id from bookshelf2_group_users where g_id=%u) or bu_id is null)',$gid);
		}
		if($buid){
			$sql=<<<SQL
select vt.t_id as id
from bookshelf2_view_tags vt
join bookshelf2_scanexam_test_tag stt on(vt.t_id=stt.t_id)
join bookshelf2_scanexam_user su on(su.se_key=stt.se_key)
where vt.pid=%u %s
SQL;
			$sql = sprintf($sql,$tid,$where_str);
			$rs = $db->get_results($sql);
		}else{
			$rs=1;
		}
		if($rs){
			$sql=<<<SQL
select id,label,sum(percent)/sum(num) as y from
(
select vt.t_id as id,vt.val as label,sum(CAST(seu_percent as int)) as percent, count(*) as num
from bookshelf2_view_tags vt
left join bookshelf2_scanexam_test_tag stt on(vt.t_id=stt.t_id)
left join bookshelf2_scanexam_user su on(su.se_key=stt.se_key)
where vt.pid=%u %s
group by vt.t_id,vt.val
union
select vt.t_id as id,vt.val as label,sum(CAST(i_percent as int)) as percent, count(*) as num
from bookshelf2_view_tags vt
join bookshelf2_scanexam_test_tag stt on(vt.t_id=stt.t_id)
join bookshelf2_itutor i on(i.id=stt.se_key)
where vt.pid=%u %s
group by vt.t_id,vt.val
) as t
group by id,label
SQL;
			$sql = sprintf($sql,$tid,$where_str,$tid,$where_str);
		}else{
			$sql=<<<SQL
select id,label,sum(percent)/sum(num) as y from
(
select vt.t_id as id,vt.val as label,sum(CAST(i_percent as int)) as percent, count(*) as num
from bookshelf2_view_tags vt
left join bookshelf2_scanexam_test_tag stt on(vt.t_id=stt.t_id)
left join bookshelf2_itutor i on(i.id=stt.se_key)
where vt.pid=%u %s
group by vt.t_id,vt.val
) as t
group by id,label
SQL;
			$sql = sprintf($sql,$tid,$where_str);
		}
		$rs = $db->get_results($sql);
		$data = array(
			'title'=>'',
			'type'=>'bar',
			'data'=>$rs
		);
		return $data;
	}
	
	public function getLearningHistory1($tid,$gid=0,$uid=0){
		global $db;
		$data = array();
		if(!empty($buid)){
			$where_str = sprintf(' and (bu_id=%u or bu_id is null)',$buid);
		}
		if(!empty($gid)){
			$where_str = sprintf(' and (bu_id in (select bu_id from bookshelf2_group_users where g_id=%u) or bu_id is null)',$gid);
		}
		$sql=<<<SQL
select t3.t_id,vt.val as label,correct*100/all as y, concat(concat(cast(correct as varchar(255)),'/'),cast(all as varchar(255))) as indexLabel
from bookshelf2_view_tags vt
left join	(select t1.t_id,correct,all from 
	(select t_id, count(*) as correct
		from bookshelf2_view_tag_itutor_exercise
		where length(result)=6 %s
		group by t_id
	 union
	 select t_id, count(*) as correct
	 	from bookshelf2_view_tag_scanimport_exercise
	 	where result='1' %s
	 	group by t_id
	) as t1
	left join	
	(select t_id, count(*) as all
		from bookshelf2_view_tag_itutor_exercise
		where 1=1 %s
		group by t_id
	 union
	 select t_id, count(*) as all
	 from bookshelf2_view_tag_scanimport_exercise
	 where 1=1 %s
	 group by t_id
	) as t2
		on(t1.t_id=t2.t_id)) as t3
	on(vt.t_id=t3.t_id)
where vt.pid=%u;
SQL;
		$sql = sprintf($sql,$where_str,$where_str,$where_str,$where_str,$tid);
		$rs = $db->get_results($sql);
		$data = array(
			'title'=>'',
			'type'=>'bar',
			'data'=>$rs
		);
		return $data;
	}
	
	public function queryChart($querystr){
		global $db;
		parse_str($querystr,$arr);
		$where_str='';
		$where_str1='';
		$where_str2='';

		if(!empty($arr['co'])){
			//list($k,$v) = explode(':',$arr['co']);
			if($arr['las']){
				$where_str.=sprintf(" and locate(concat(concat(',',cast(%u as varchar(255))),','),vt.path,0)>0",$arr['co']);
				$where_str1.=sprintf(" and locate(concat(concat(',',cast(%u as varchar(255))),','),path,0)>0",$arr['co']);
			}else{
				$where_str.=sprintf(" and vt.pid=%u",$arr['co']);
			}
		}
		if(!empty($arr['rwp'])){
			$where_str2.=sprintf(" and ifnull(y,0)<=%u",$arr['rwp']);
		}
		if(!empty($arr['start'])){
			$where_str1.=sprintf(" and TIMESTAMPDIFF('m',createdate,'%s')<=0",$arr['start']);
		}
		if(!empty($arr['end'])){
			$where_str1.=sprintf(" and TIMESTAMPDIFF('m',createdate,'%s')>=0",$arr['end']);
		}
		if(!empty($arr['bs'])){
			$where_str1.=sprintf(" and t3.bs_id=%u",$arr['bs']);
		}
		if(!empty($arr['bu'])){
			$where_str1.=sprintf(' and (t3.bu_id=%u)',$arr['bu']);
		}elseif(!empty($arr['g'])){
			$where_str1.=sprintf(' and (t3.bu_id in (select bu_id from bookshelf2_group_users where g_id=%u))',$arr['g']);
		}

		if(!empty($arr['b'])){
			$where_str1.=sprintf('and b_id = %u',$arr['b']);
		}
		if(!empty($arr['d']) && !empty($arr['s'])){
			$where_str1.=sprintf('and t_id in(%u,%u)',$arr['d'],$arr['s']);
		}elseif(!empty($arr['d'])){
			$where_str1.=sprintf('and t_id = %u',$arr['d']);
		}elseif(!empty($arr['s'])){
			$where_str1.=sprintf('and t_id = %u',$arr['s']);
		}

		switch($arr['r']){
			case 'rw':
		$sql=<<<SQL
select vt.t_id as id, vt.val as label,sum(c)*100/sum(a) as y,concat(concat(cast(ifnull(sum(c),0) as varchar(20)),'/'),cast(ifnull(sum(a),1) as varchar(20))) as indexlabel, sum(c) as c,sum(a) as a
from bookshelf2_view_tags vt
left join (
	select bu_id,t_id,y,ifnull(c,0) as c,ifnull(a,0) as a,path
	from (
		select t3.bu_id,t3.t_id, sum(t3.correct)*100/sum(t3.all) as y, sum(t3.correct) as c, sum(t3.all) as a,t3.path
		from bookshelf2_view_allexam_rw t3
		where 1=1 %s
		group by t3.bu_id,t3.t_id,t3.path
	) as t
	where 1=1 %s
) as t2 on(locate(concat(concat(',',cast(vt.t_id as varchar(255))),','),t2.path,0)>0)
where 1=1 %s
group by vt.t_id,vt.val
order by vt.t_id
SQL;
					$sql = sprintf($sql,$where_str1,$where_str2,$where_str);
				break;
			case 's':
		$sql=<<<SQL
select vt.t_id as id, vt.val as label, sum(CAST(percent as int))/count(*) as y
from bookshelf2_view_tags vt
left join (
	select bs_id,b_id,bu_id,t.t_id,percent,concat(concat(concat(concat(',',path),','),cast(t.t_id as varchar(255))),',') as path,createdate
	from (
		select b.bs_id,i.b_id,bu_id,t_id,i_percent as percent, i.createdate
		from bookshelf2_itutor i
		join bookshelf2_books b on(i.b_id=b.b_id)
		left join
		(
			select b.b_key as key, bt.t_id
			from bookshelf2_book_tag bt
			left join bookshelf2_books b on(bt.b_id=b.b_id)
			union
			select dockey as key, t_id
			from bookshelf2_tag_dictionary
		) as t on(i.id=t.key)
		union
		select 0 as bs_id,0 as b_id,bu_id,stt.t_id, su.seu_percent as percent, su.set_date as createdate
		from bookshelf2_scanexam_user su
		left join bookshelf2_scanexam_test_tag stt on(su.se_key=stt.se_key and su.set_date=stt.set_date)
	) as t
	left join bookshelf2_view_tags vt on(vt.t_id=t.t_id)
	where 1=1 %s
) as t1 on(locate(concat(concat(',',cast(vt.t_id as varchar(255))),','),t1.path,0)>0)
where 1=1 %s
group by vt.t_id, vt.val
order by vt.t_id
SQL;
					$sql = sprintf($sql,$where_str1,$where_str);
				break;
			case 'lt':
		$sql=<<<SQL
select vt.t_id,vt.val as label,sum(sec)/60 as y
from bookshelf2_view_tags vt
left join (
	select t_id,sec,path
	from (
		select t1.t_id,bs_id,bu_id,t1.b_id,sec,concat(concat(concat(concat(',',path),','),cast(t1.t_id as varchar(255))),',') as path,createdate
		from (
			select b.bs_id,b.b_id,t_id
			from bookshelf2_book_tag bt
			join bookshelf2_books b on(bt.b_id=b.b_id)
			union
			select bs_id,b_id,t_id
			from bookshelf2_books b
			join bookshelf2_tag_dictionary td on(b.b_key=td.dockey)
			where quizid=''
		) as t1
		left join
		(
			select bu_id,b_id,sum(SECS_BETWEEN(end_time,start_time)) as sec, start_time as createdate
			from bookshelf2_reading_time
			group by bu_id, b_id, start_time
		) as t2 on(t2.b_id=t1.b_id)
		left join bookshelf2_view_tags vt on(vt.t_id=t1.t_id)
	) as t
	where 1=1 %s
) as t3 on(locate(concat(concat(',',cast(vt.t_id as varchar(255))),','),t3.path,0)>0)
where 1=1 %s
group by vt.t_id, vt.val
SQL;
					$sql = sprintf($sql,$where_str1,$where_str);
				break;
		}
		$rs = $db->get_results($sql);
		$data = array(
			'title'=>'',
			'type'=>'bar',
			'data'=>$rs
		);
		return $data;
	}
}
?>
