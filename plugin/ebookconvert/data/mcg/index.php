<?
session_start();
if(!isset($_SESSION['uid'])){
        echo 'Please open book from bookshelf!';exit;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta name="ROBOTS" content="noindex,nofollow">
    <title></title>
<link href="video-js/video-js.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="draw.js" charset="Shift-JIS"></script>
<script type="text/javascript" src="msgtext.js"></script>
<script type="text/javascript" src="timeline.js"></script>
<script type="text/javascript" src="config.js" charset="Shift-JIS"></script>
<script type="text/javascript" src="contents.js"></script>
<script type="text/javascript" src="indexout.js"></script>
<script type="text/javascript" src="video-js/video.dev.js"></script>
<script type="text/javascript" src="APIWrapper.js" charset="Shift-JIS"></script>
<script type="text/javascript" src="SCOFunctions.js" charset="Shift-JIS"></script>
<script type="text/javascript">
<!--
 if(fScorm) {
  loadPage();
 }
-->
</script>
<script type="text/javascript">
    videojs.options.flash.swf = "video-js/video-js.swf";

    if (typeof sessionStorage !== 'undefined') {
        fWebstorage = 1;
    } else {
        fWebstorage = 0;
    }

    if (vFastbutton == 0) {
        document.writeln('<style type="text/css">');
        document.writeln('.vjs-default-skin .vjs-progress-control {');
        document.writeln('    display: none;');
        document.writeln('}');
        document.writeln('</style>');
    }
</script>

</head>

<body oncontextmenu="return false;">

<div id="titlearea" style='position: absolute;'>
</div>

<div id="logoarea" style='position: absolute;'>
<img alt="ロゴ" id="logopic"/>
<script type="text/javascript">
	var lop = document.getElementById("logopic");
    var img1 = new Image();
    img1.src = "logo.jpg";
    img1.onload = function () {
        	lop.src = "logo.jpg"
    }
    var img2 = new Image();
    img2.src = "logo.gif";
    img2.onload = function () {
        	lop.src = "logo.gif"
    }
</script>

</div>

<div id="slidearea" style='position: absolute; display: none;'>
    <iframe id="sframe" src= "Slide.html" frameborder=0 scrolling=no></iframe>
</div>

<div id="drawarea" style='position: absolute; overflow: hidden;'>
    <canvas id="can1" style='position: absolute;'></canvas>
    <canvas id="can2" style='position: absolute;'></canvas>
</div>


<div id="videoarea" class="player_rtmp" style='background-color: #000000; position: absolute;'>

<script type="text/javascript">
    if (bStreaming) {
        var browstr = brow();
        if (browstr == "ipad" || browstr == "iphone" || browstr == "safari") {
            document.writeln('<video id=\"video\" autoplay controls preload=\"metadata\" class=\"video-js vjs-default-skin\" data-setup=\"{}\">');
            document.writeln('<source type=\"video/mp4\" src=\"' + surl + '\">');
        } else if (browstr == "Android") {
            document.writeln('<video id=\"video\" autoplay controls preload=\"metadata\" class=\"video-js vjs-default-skin\" data-setup=\"{}\">');
            document.writeln('<source type=\"video/mp4\" src=\"' + surl + '\">');
        } else {
            document.writeln("<video id=\"video\" class=\"video-js vjs-default-skin\" autoplay controls preload=\"metadata\" data-setup='{ \"techOrder\": [\"flash\"] }'>");
            document.writeln('<source type=\"rtmp/mp4\" src=\"' + url + '\">');
        }
    } else {
        document.writeln('<video id=\"video\" autoplay controls preload=\"none\" class=\"video-js vjs-default-skin\" data-setup=\"{}\">');
        document.writeln('<source src=\"movie/movie.mp4\" type=\"video/mp4\" />');
    }
</script>

        <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
    </video>
</div>

<div id="indexarea" style='position: absolute;'>
    <script type="text/javascript">
        indexstyle();
        indexwrite();
    </script>
</div>

<div id="adarea" style='position: absolute; display: none;'>
<img alt="adviser" id="adpic" />
<script type="text/javascript">
	var adp = document.getElementById("adpic");
    var img1 = new Image();
    img1.src = "_advisor.jpg";
    img1.onload = function () {
        	adp.src = "_advisor.jpg"
    }
    var img2 = new Image();
    img2.src = "_advisor.gif";
    img2.onload = function () {
        	adp.src = "_advisor.gif"
    }
</script>

</div>

<div id="demoarea" style='background-color: #000000; position: absolute; display: none;'>
    <video id="demo" controls autoplay preload="none" class="video-js vjs-default-skin" data-setup="{}">

        <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
    </video>
</div>

<script type="text/javascript" src="drawfunc.js"></script>
<script type="text/javascript" src="layout.js"></script>
<script type="text/javascript" src="player.js"></script>
<script type="text/javascript" src="mark.js"></script>
<script type="text/javascript">
    //オープン時にページを構築
    
    lpicv(false);

    document.title = TitleName;

    ChangeLayout(layoutNo);
    ChangeTab('tab1');

    /*if (bStreaming) {
        var browstr = brow();
        if (browstr == "ipad" || browstr == "iphone" || browstr == "safari") {
            v.src(surl);
        } else if (browstr == "Android") {
            v.src(surl);
        }
    }*/

    arView(arid);
    endstart();
    ChangeLayout(layoutNo);
</script>
<style type="text/css">
        .vjs-default-skin .vjs-volume-menu-button .vjs-menu .vjs-menu-content, .vjs-default-skin .vjs-volume-menu-button .vjs-lock-showing.vjs-menu .vjs-menu-content{
            left: -70px;
        }
        
        .vjs-default-skin .vjs-volume-menu-button .vjs-menu
        {
            z-index: 1;
        }
        
        .vjs-default-skin .vjs-menu-button:hover .vjs-control-content .vjs-menu, .vjs-default-skin .vjs-control-content .vjs-lock-showing.vjs-menu
        {
            display: none;
        }
        
        .vjs-default-skin .vjs-menu-button .vjs-menu .vjs-menu-content
        {
            bottom: -3em;
        }
        
        .vjs-default-skin .vjs-menu
        {
            display: none;
        }
</style>
</body>
</html>