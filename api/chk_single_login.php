<?PHP
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','filter','ejson');
$login = new login($db);

$token = session_id();
$rs = $login->getBySESSIONID($token);
$valid = true;
if(empty($rs)){
	BookshelfManager::UserLogout();
	$valid = false;
}
echo json_encode($valid);
?>
