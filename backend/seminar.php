<?PHP
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('tpl','auth','bookshelf_auth','db','ehttp');

$tpl->display('backend/seminar.tpl');
?>
