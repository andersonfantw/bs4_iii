<?php
function execExpiredCourse($buid, $token){
/*
	global $db;
	$json = new Services_JSON();
	$sql =<<<SQL
select v.*,
	a.ac_term,
	a.registdate,
	TIMESTAMPADD('M',ac_term,registdate) as enddate
from bookshelf2_view_bookshelfdetail v
join bookshelf2_activecode a on(v.bs_id=a.bs_id)
where TIMESTAMPDIFF('m',timestampadd('M',ac_term,registdate),now())>0
SQL;
	$sql = sprintf($sql, $uid);
	$data['result'] = $db->get_results($sql);
*/
	$w=common::get_wonderbox_id();
	if($w["rc"]==0)
		$wid = $w["wbox_id"];
	else
		$wid = $w["errmsg"];
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('wid',$wid);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('uid',0);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('buid',$buid);
}
?>
