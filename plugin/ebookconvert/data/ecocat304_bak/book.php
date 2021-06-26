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
<link rel="stylesheet" href="css/ecocat_html5.css">
<link rel="stylesheet" href="css/jquery.treeview.css">
<link rel="stylesheet" href="css/print.css" media="print">
<script src="html5/js/common.js"></script>
<script type="text/javascript">judge_sp();</script>
<script src="html5/js/jquery-1.11.0.min.js"></script>
<script src="html5/js/js-url.js"></script>
<script src="html5/js/jquery.csv.js"></script>
<script src="html5/js/jquery.treeview.js"></script>
<script src="html5/js/jquery.cookie.js"></script>
<script src="/scripts/analyztis.js" type="text/javascript"></script>
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
    opt.paper_outer = {'t':2, 'r':0, 'b':62, 'l':0, 'w':0, 'h':0};
    
	$('#dmxlivebook').dmxLivebook(opt);
});
</script>
</head>
<body>
<div id="dmxlivebook" data-role="page" data-url="livebook"></div>
<div id="printarea"></div>
<div id="help_screen"></div>
</body>
</html>
