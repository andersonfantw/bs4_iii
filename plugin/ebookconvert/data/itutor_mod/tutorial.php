<?
session_start();
if(!isset($_SESSION['uid'])){
        echo 'Please open book from bookshelf!';exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="generator" content="iTutor 4.0.0"/>
	<meta name="author" content="Anderson" />
	<meta name="description" content=""/>

	<link rel="stylesheet" type="text/css" href="player/rlplayer.css" />
	<script type="text/javascript" src="player/jquery.js"></script>
	<script type="text/javascript" src="player/params.js"></script>
	<script type="text/javascript" src="player/languages.js"></script>
	<script type="text/javascript" src="player/slides.js"></script>
	<script type="text/javascript" src="player/mediaplayer.js"></script>
	<script type="text/javascript" src="player/rlplayer.js"></script>
	<script src="/scripts/analyztis.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	var g_nInitialMode = 2;
	</script>
</head>

<body>

<div id="ViewPortDiv">
	<div id="DockPaneLeft" class="ap-dock-pane"></div>
	<div id="DockPaneRight" class="ap-dock-pane"></div>
	<div id="DockPaneTop" class="ap-dock-pane"></div>
	<div id="DockPaneBottom" class="ap-dock-pane"></div>
	<div id="StageDiv" tabindex="-1">
		<div id="CanvasDiv" tabindex="-1">
			<div id="SlidesDiv"></div>
			<div id="CCaptionDiv"></div>
		</div>
	</div>
</div>

</body>
</html>
