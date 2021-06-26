<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('filter','db','ejson');

$UploadQueue = new UploadQueue();
$UploadQueue->doNext();
?>