<?PHP
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('db','sysauth','tpl','filter','ehttp','getIP');

$id = $fs->valid($_POST['id'],'acc');
$pwd = $fs->valid($_POST['pwd'],'pwd');
$cmd = $fs->valid($_POST['cmd'],'cmd');

if($cmd=='logout'){
	unset($_SESSION['adminip']);
}

if($id=='ttii' && $pwd=='ttii297176853#'){
	$_SESSION['adminip'] = $USER_IP;
}
if(!empty($_SESSION['adminip'])){
	$adminip = $fs->valid($_SESSION['adminip'],'ip');
?>
	<h1>BOOKSHELF CONSOLE</h1>
	<a href="sys_cssh.php" target="_blank">SSH</a><br />
	<a href="sys_cupload.php" target="_blank">UPLOAD</a><br />
	<a href="sys_cdb.php" target="_blank">DB</a><br />
	<form method="post" action="sys_cpass.php">
		<input type="hidden" name="cmd" value="logout" />
		<input type="submit" value="logout" />
	</form>
<?
}else{
?>
<html>
<body>
	<h1>BOOKSHELF CONSOLE</h1>
	<form method="post" action="sys_cpass.php">
		ID: <input type="text" name="id"><br />
		PWD: <input type="password" name="pwd"><br />
		<input type="submit" />
	</form>
</body>
</html>
<?
}
?>
