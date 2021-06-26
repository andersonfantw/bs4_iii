<?PHP
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','ehttp');
require_once dirname(__FILE__).'/init.php';

$tpl->display('backend/class.tpl');
?>