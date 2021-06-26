<?php
function execGuide($uid,$bs_id){
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('uid',$uid);
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('bsid',$bs_id);
}
?>