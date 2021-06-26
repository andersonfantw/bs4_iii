<?PHP
session_start();
if(!isset($_SESSION['uid'])){
        echo 'Please open book from bookshelf!';exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<title></title>
<link rel="stylesheet" href="html5/css/jquery.mobile-1.4.2.min.css">
<link rel="stylesheet" href="html5/css/common.css">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/ecocat.css">
<link rel="stylesheet" href="css/jquery.treeview.css">
<link rel="stylesheet" href="css/print.css" media="print">
<script src="html5/js/common.js"></script>
<script type="text/javascript">judge_sp();</script>
<script src="html5/js/jquery-1.11.0.min.js"></script>
<script src="html5/js/js-url.js"></script>
<script src="html5/js/jquery.csv.js"></script>
<script src="html5/js/jquery.treeview.js"></script>
<script src="html5/js/jquery.cookie.js"></script>
<script>
$(document).on("mobileinit", function() {
    $.mobile.loader.prototype.options.disabled = true;
});
</script>
<script src="html5/js/jquery.mobile-1.4.2.min.js"></script>
<script src="html5/js/jquery.dmx.livebook.js"></script>
<script src="html5/js/sns.dmx.livebook.js"></script>
<script>
$(document).ready(function(){
	var opt = {};
	opt.startpage = parseInt(url('?startpage'));
	opt.device = 'pc';
	opt.xmldir = './xml';
	opt.datadir = './html5/data';
	opt.pagedir = '.';
	opt.imgdir = './images';
    opt.paper_outer = {'t':2, 'r':2, 'b':2, 'l':177, 'w':0, 'h':0};
    
	$('#dmxlivebook').dmxLivebook(opt);
});
</script>
</head>
<body ONDRAGSTART="window.event.returnValue=false" onSelectStart="event.returnValue=false" ONCONTEXTMENU="window.event.returnValue=false">
<div id="dmxlivebook" data-role="page" data-url="livebook"></div>
<div id="printarea"></div>
<div id="help_screen"></div>
</body>
<style type="text/css">
body {
-moz-user-select : none;
-webkit-user-select: none;
}
</style>
<script>
function iEsc(){return false;}
function iRec(){return true;}
function DisableKeys() {
if(event.ctrlKey || event.shiftKey || event.altKey) {window.event.returnValue=false;iEsc();}}
document.ondragstart=iEsc;
document.onkeydown=DisableKeys;
document.oncontextmenu=iEsc;
if (typeof document.onselectstart !="undefined") document.onselectstart=iEsc;
else{document.onmousedown=iEsc;document.onmouseup=iRec;}
function DisableRightClick(qsyzDOTnet){
if (window.Event){if (qsyzDOTnet.which == 2 || qsyzDOTnet.which == 3) iEsc();}
else
if (event.button == 2 || event.button == 3){event.cancelBubble = true;event.returnValue = false;iEsc();}
}
</script>
</html>
