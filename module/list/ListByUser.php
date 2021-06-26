<?php
function execListByUser($buid, $device){
/*	
	global $db;
	$json = new Services_JSON();
	if(LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::Regist)){
		$sql =<<<SQL
select * from bookshelf2_view_bookshelfdetail v
where v.bs_id in(select c.bs_id
	from BOOKSHELF2_CATEGORY c
	join BOOKSHELF2_GROUPS_CATEGORY gc on(c.c_id=gc.c_id)
	join BOOKSHELF2_GROUP_USERS gu on(gc.g_id=gu.g_id)
	join bookshelf2_activecode ac on(gu.bu_id=ac.bu_id and ac.bu_id=%u)
	where TIMESTAMPDIFF('m',timestampadd('M',ac_term,registdate),now())<0)
SQL;
	}else{
		$sql =<<<SQL
select * from bookshelf2_view_bookshelfdetail v
where v.bs_id in(select bs_id
			from bookshelf2_view_group_users vgu
			where bu_id=%u);
SQL;
	}
	$sql = sprintf($sql, $uid);
	$data['result'] = $db->get_results($sql);
*/
	$w=common::get_wonderbox_id();
	if($w["rc"]==0)
		$wid = $w["wbox_id"];
	else
		$wid = $w["errmsg"];
	//$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('data',$data);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('wid',$wid);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('uid',0);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('buid',$buid);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('mode','userlist');
}
?>
