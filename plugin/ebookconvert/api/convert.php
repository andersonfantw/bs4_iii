<?PHP
ini_set('memory_limit','5000M');
set_time_limit(1800);
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('filter','db','ejson');
$ErrorHandler = new ErrorHandler;

$cmd = $_GET['cmd'];
$bs_id = $fs->valid($_GET['bs'],'id');
$cate2 = $fs->valid($_GET['c'],'id');
$site = $fs->valid($_GET['site'],'num');
if(empty($cmd)) $cmd='format';

$ConvertManager = new ConvertManager($site);
$ConvertManager->Convert($cmd,$cate2);
	
?>
