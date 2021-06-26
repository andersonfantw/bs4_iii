<?
session_start();
if(!isset($_SESSION['uid'])){
        echo 'Please open book from bookshelf!';exit;
}
?>
<!DOCTYPE html>
<html lang="zh" dir="ltr">
<head>
	<title></title>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="generator" content="iTutor 5.0.0"/>
	<meta name="author" content="kuochun" />
	<meta name="description" content=""/>

	<link rel="stylesheet" type="text/css" href="player/rlplayer.css" />
	<script type="text/javascript" src="player/rljquery.custom.js"></script>
	<script type="text/javascript" src="player/rlparams.js"></script>
	<script type="text/javascript" src="player/rllanguages.js"></script>
	<script type="text/javascript" src="player/rlslides.js"></script>
	<script type="text/javascript" src="player/rlmediaplayer.html5.fl.js"></script>
	<script type="text/javascript" src="player/rlplayer.js"></script>
	<script src="/scripts/analyztis.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	var g_nInitialMode = 2;
	</script>
</head>
<body>
	<div id="ViewPortDiv"></div>
</body>
</html>
