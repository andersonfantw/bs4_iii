<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','sysauth','tpl','ehttp');
$type = $fs->valid($_GET['type'],'cmd');

switch ($type) {
	case 'exporttag':
		$filename = "tag_".time().".tag";
		header("Content-type: text/plain");
	  header("Content-Disposition: attachment; filename=".$filename);
		$TagDocument = new TagDocument();
		$TagDocument->loadDB();
		echo $TagDocument->toString();
		break;
	case 'exportdic':
		$exportType = ListTypeEnum::DocumentTag;
		$filename = "dic_".time().".dic";
		header("Content-type: text/plain");
	  header("Content-Disposition: attachment; filename=".$filename);
		$TagDocument = new TagDocument();
		$TagDocument->loadDB();
		$TagDocument->loadDictionaryDB($exportType);
		echo $TagDocument->exportDictionaryString();
		break;
	default:
		$tpl->display('backend/sys_tag.tpl');
		break;
}
?>
