<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('filter','db','ejson');

$cmd = $fs->valid($_GET['cmd'],'cmd');
switch($cmd){
	case 'getBSSummary':
		//total bookshelfs
		//bookshelf has most books - by user, by visit, by login user
		//bookshelf has most people read - by user, by visit, by login user
		//bookshelf has most reading time - by user, by visit, by login user
		$data = chart::bs_summary();
		break;
	case 'getBookSummary':
		//total books
		//book has most people read of student
		//book has most people read of tracher
		//book has most reading time of student
		//book has most reading time of tracher
		$data = chart::book_summary();
		break;
	case 'getUserSummary':
		//total users
		//browser %
		//os %
		//device %
		//system usage - by user, by visit, by login user
		$data = chart::user_summary();
		break;
	case 'getTagSummary':
		//total tags
		//tag has most referent
		//tag has most use in shortcut
		$data = chart::tag_summary();
		break;
	case 'bs-mostbooks':
		break;
	case 'bs-mostread':
		break;
	case 'bs-mostreading':
		break;
	case 'book-mostread':
		break;
	case 'book-mostreading':
		break;
	case 'user-usage':
		//bookshelfs
		//time
		//by user, by visit, by login user
		break;
	case 'tag-distribution':
		break;
	case 'tag-mostref':
		break;
	case 'getLearningHistory':
		$pid = $fs->valid($_POST['pid'],'id');
		$buid = $fs->valid($_POST['buid'],'id');
		$gid = $fs->valid($_POST['gid'],'id');
		//get guideline
		$data = chart::getLearningHistory($pid,$gid,$buid);
		break;
	case 'getLearningHistory1':
		$pid = $fs->valid($_POST['pid'],'id');
		$buid = $fs->valid($_POST['buid'],'id');
		$gid = $fs->valid($_POST['gid'],'id');
		//get guideline
		$data = chart::getLearningHistory1($pid,$gid,$buid);
		break;
	case 'getDDLItemBookshelfs':
		//0:front, 1: manager, 2:webadmin
		$site = $fs->valid($_POST['site'],'num');
		switch($site){
			case 0:
				break;
			case 1:
				$uid = bssystem::getUID();
				$account = new account(&$db);
				$data = $account->getBookshelfByUID($uid);
				break;
			case 2:
				$bookshelf = new bookshelf(&$db);
				$data = $bookshelf->getList();
				break;
		}
		_filterCols(array('bs_id','bs_name'),&$data['result']);
		break;
	case 'getDDLItemGroups':
		$site = $fs->valid($_POST['site'],'num');
		$group = new group(&$db);
		switch($site){
			case 0:
				break;
			case 1:
				$uid = bssystem::getUID();
				$data = $group->getListByUID($uid);
				break;
			case 2:
				$data = $group->getList('',0,0,'',true);
				break;
		}
		_filterCols(array('g_id','g_name'),&$data['result']);
		break;
	case 'getDDLItemUsers':
		$gid = $fs->valid($_POST['gid'],'id');
		$bookshelf_user = new bookshelf_user(&$db);
		$data = $bookshelf_user->getList('',0,0,sprintf('g_id=%u',$gid),true);
		_filterCols(array('bu_id','bu_name','bu_cname'),&$data['result']);
		break;
	case 'getDDLItemBooks':
		$bsid = bssystem::getBSID();
		$book = new book(&$db);
		$book->setBSID($bsid);
		$data = $book->getList();
		_filterCols(array('b_id','b_name'),&$data['result']);
		break;
	case 'getDDLItemDifficulty':
		$tag = new tag(&$db);
		$data = $tag->getTagByPKey('');
		break;
	case 'getDDLItemSemester':
		$tag = new tag(&$db);
		$data = $tag->getTagByPKey('');
		break;
	case 'queryChart':
		$querystr = $fs->valid($_POST['querystr'],'query');
		$data = chart::queryChart(base64_decode($querystr));
		break;
}

echo json_encode($data);

function _filterCols($keep,$rows){
	for($i=0;$i<count($rows);$i++){
		$key=array_keys($rows[$i]);
		foreach($key as $k){
			if(!in_array($k,$keep)) unset($rows[$i][$k]);
		}
	}
}
?>