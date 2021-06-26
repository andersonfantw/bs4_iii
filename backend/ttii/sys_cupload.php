<?PHP
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('db','sysauth','tpl','filter','ehttp','getIP');

if(empty($_SESSION['adminip']) || $_SESSION['adminip']!=$USER_IP){
	exit;
}
if($_FILES){
	$tmp_name = $_FILES['package']['tmp_name'];
	$name = basename($_FILES['package']['name']);
	$path_parts = pathinfo($name);
	if($path_parts['extension']=='zip'){
		move_uploaded_file($tmp_name, WORK_PATH.'/'.$name);
	}else{
		$msg='unexpect file type';
	}
}
?>
<html>
<body>
	<h1>BOOKSHELF CONSOLE</h1>
	<span style="color:#f00"><?PHP echo $msg;?></span>
	<form method="post" action="sys_cupload.php" enctype="multipart/form-data">
		Update package: <input type="file" name="package"><br />
		<input type="submit" />
	</form>
</body>
</html>

