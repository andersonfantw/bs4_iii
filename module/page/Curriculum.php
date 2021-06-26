<?php
function execCurriculum($uid,$bs_id){
	if(empty($uid)) $uid=0;
	if(empty($bs_id)) $bs_id=0;
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('uid',$uid);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('bsid',$bs_id);
}
?>