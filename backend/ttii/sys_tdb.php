<?php
/*
sys_testcase_db
*/
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('db','sysauth','tpl','filter','ehttp');

$dbtables =<<<DB
BOOKSHELF2_INI
BOOKSHELF2_SYSTEM
BOOKSHELF2_SYSTEM_UPGRADE
BOOKSHELF2_FILE
BOOKSHELF2_ACCOUNT
BOOKSHELF2_BOOKSHELFS
BOOKSHELF2_CATEGORY
BOOKSHELF2_GROUPS
BOOKSHELF2_BOOKS
BOOKSHELF2_BOOKSHELF_USERS
BOOKSHELF2_BOOKSHELF_SHARE
BOOKSHELF2_ACTIVECODE
BOOKSHELF2_ACTIVECODE_GROUP
BOOKSHELF2_ACTIVECODE_TRIAL
BOOKSHELF2_ACCOUNT_BOOKSHELF
BOOKSHELF2_ACCOUNT_GROUPS
BOOKSHELF2_ACTION_LOG
BOOKSHELF2_BOOKSHELF_GROUPS
BOOKSHELF2_BOOKSHELF_USER_BOOKS
BOOKSHELF2_BOOKS_CATEGORY
BOOKSHELF2_BOOKS_VIEWS
BOOKSHELF2_BOOK_TAG
BOOKSHELF2_GROUPS_CATEGORY
BOOKSHELF2_GROUP_USERS
BOOKSHELF2_ITUTOR
BOOKSHELF2_ITUTOR_EXERCISE
BOOKSHELF2_ITUTOR_EXERCISE_TAG
BOOKSHELF2_LICENSE
BOOKSHELF2_LINKCLICK
BOOKSHELF2_LOGIN
BOOKSHELF2_READING_TIME
BOOKSHELF2_SCANEXAM
BOOKSHELF2_SCANEXAM_EXERCISE
BOOKSHELF2_SCANEXAM_EXERCISE_TAG
BOOKSHELF2_SCANEXAM_QUIZ
BOOKSHELF2_SCANEXAM_TAG
BOOKSHELF2_SCANEXAM_TEST
BOOKSHELF2_SCANEXAM_TEST_TAG
BOOKSHELF2_SCANEXAM_USER
BOOKSHELF2_SYSTEM_ACCOUNT
BOOKSHELF2_SYSTEM_TAG
BOOKSHELF2_TAG
BOOKSHELF2_TAGCATE
BOOKSHELF2_TAGKEY
BOOKSHELF2_TAGVAL
BOOKSHELF2_TAG_DICTIONARY
BOOKSHELF2_TAG_SHORTCUT
BOOKSHELF2_TAG_SHORTCUT_NODE
BOOKSHELF2_TAG_SHORTCUT_NODETAG
BOOKSHELF2_TAG_SHORTCUT_TAG
BOOKSHELF2_TAG_EVOLVE
BOOKSHELF2_VCUBE_MEETINGS_CALENDAR
BOOKSHELF2_VCUBE_MEETINGS_CALENDAR_GROUP
BOOKSHELF2_VCUBE_SEMINAR_CALENDAR
BOOKSHELF2_VCUBE_SEMINAR_CALENDAR_GROUP
BOOKSHELF2_VCUBE_SEMINAR_CALENDAR_USER
BOOKSHELF2_ZOOM_MEETINGS_CALENDAR
BOOKSHELF2_ZOOM_MEETINGS_CALENDAR_GROUP
BOOKSHELF2_ZOOM_MEETINGS_CALENDAR_USER
BOOKSHELF2_I519
BOOKSHELF2_BOOKSHELF_USER_FORGET
BOOKSHELF2_PURCHASE
BOOKSHELF2_PURCHASE_PROGRAM
BOOKSHELF2_VIEW_BOOKDETAIL_PERSONAL_BOOK
BOOKSHELF2_VIEW_GROUP_USERS
BOOKSHELF2_VIEW_USERS_BY_BOOKSHELF
BOOKSHELF2_VIEW_USERS_BY_ACCOUNT
BOOKSHELF2_VIEW_HAD_READ_BOOK
BOOKSHELF2_VIEW_HAS_TEST
BOOKSHELF2_VIEW_BOOKDETAIL
BOOKSHELF2_VIEW_BOOKDETAIL_PUBLIC
BOOKSHELF2_VIEW_BOOKDETAIL_PERSONAL
BOOKSHELF2_VIEW_BOOKSHELFDETAIL
BOOKSHELF2_VIEW_TAGS
BOOKSHELF2_VIEW_TAGS_MINALL
BOOKSHELF2_VIEW_TAG_DICTIONARY
BOOKSHELF2_VIEW_TAG_SCANIMPORT_EXERCISE
BOOKSHELF2_VIEW_TAG_ITUTOR_EXERCISE
BOOKSHELF2_VIEW_TAG_EVOLVE
BOOKSHELF2_VIEW_BOOK_TAGS
BOOKSHELF2_VIEW_SHORTCUT_TAGS
BOOKSHELF2_VIEW_ITUTOR
BOOKSHELF2_VIEW_EXAMLIST
BOOKSHELF2_VIEW_TAG_ITUTOR
BOOKSHELF2_VIEW_TAG_SCANIMPORT
BOOKSHELF2_VIEW_BOOK_TAG
BOOKSHELF2_VIEW_ALLEXAM_RW
BOOKSHELF2_VIEW_ACTIVECODE_EXPIRED
BOOKSHELF2_VIEW_ACTIVECODE_ACTIVELIST
BOOKSHELF2_VIEW_ACTIVECODE_GROUP
DB;
$arr = explode("\r\n",$dbtables);

$t = $fs->valid($_GET['t'],'pname');
$token = $fs->valid(urldecode($_REQUEST['token']),'key');
$from = $fs->valid($_POST['from'],'id');
$to = $fs->valid($_POST['to'],'id');

if(!empty($t)){
	if(empty($token)){
		echo 'invalid!';exit;
	}else{
		$valid = common::checkToken($token,3000);
		if(!$valid){
			echo 'expired!';exit;
		}
	}
}


if(!empty($t)){
	if(in_array($t,$arr)){
		$sql="select column_name, type_name from SYSCOLUMN where table_name='%s' order by column_order";
		$sql=sprintf($sql,$t);
		$data_cols = $db->get_results($sql);
		$where='';
		foreach($data_cols as $col){
			$column_name=trim($col['column_name']);
			$column_type=trim($col['type_name']);
			$v = $fs->valid($_POST['col_'.$column_name],'name');
			if(!empty($v)){
				if(in_array($column_type,array('INTEGER','SERIAL','SMALLINT'))){
					$where.=sprintf(" and %s=%u",$column_name,$v);
				}else{
					$where.=sprintf(" and %s like'%%%s%%'",$column_name,$v);
				}
			}
		}

		$sql = sprintf('select * from %s where 1=1',$t);
		if(!empty($where)) $sql.=$where;
		$data = $db->get_results($sql);

		if(empty($from) && empty($to)){
			$from=count($data)-10;
			if($from<0) $from=0;
			$to=count($data);
		}else{
			if($from<0) $from=0;
			if($to>count($data)) $to=count($data);
			if($from>$to){
				$_to=$from+10;
				if($_to>count($data)) $to=count($data);
				if($from>$to) $from=0;
			}
		}

		$str=<<<HTML
<h1>%s</h1><br />
ROWS: %u<br />
<form method="post" action="sys_testcase_db.php?t=%s">
	FROM: <input type="text" name="from" value="%u" />
	TO: <input type="text" name="to" value="%u" /> 
	<input type="submit" value="Submit" />
	<input type="hidden" name="token" value="%s" />
<table><tr>
HTML;
		echo sprintf($str,$t,count($data),$t,$from,$to,$token);
		foreach($data_cols as $col){
			echo sprintf('<td style="background:#ccc">%s</td>',$col['column_name']);
		}
		echo '</tr><tr>';
		foreach($data_cols as $col){
			$column_name=trim($col['column_name']);
			$v = $fs->valid($_POST['col_'.$column_name],'name');
			echo sprintf('<td style="background:#ccc"><input type="text" name="col_%s" value="%s" /></td>',$column_name,$v);
		}
		echo '</tr>';
		if(!empty($data)){
			for($i=$from;$i<$to;$i++){
				echo '<tr>';
				foreach($data[$i] as $k=>$val){
					echo sprintf('<td>%s</td>',$val);
				}
				echo '</tr>';
			}
		}
		echo '</table></form>';
		exit;
	}else{
		$ee->Error('406.63');
	}
}


echo '<div style="width:1024px;margin:20px auto"><h1>L-NET DB Tables test</h1></div>';

$token = urlencode(common::makeToken());
$sql = 'select count(*) as n from %s';
foreach($arr as $t){
	$sql1=sprintf($sql,$t);
	$row = $db->query_first($sql1);
	echo sprintf("<a href='javascript:;' onclick='window.open(\"sys_testcase_db.php?t=%s&token=%s\")'>%s</a> checked! %u row(s)<br />",$t,$token,$t,$row['n']);
}
?>
