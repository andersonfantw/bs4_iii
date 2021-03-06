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
<link rel="stylesheet" href="css/jquery.mobile-1.4.2.min.css">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/mobile.css">
<script src="js/jquery-1.11.0.min.js"></script>
<script src="js/js-url.js"></script>
<script src="js/jquery.cookie.js"></script>
<script>
$(document).on("mobileinit", function() {
    $.mobile.loader.prototype.options.disabled = true;
});
</script>
<script src="js/jquery.mobile-1.4.2.min.js"></script>
<script src="js/jquery.dmx.livebook.js"></script>
<script src="js/sns.dmx.livebook.js"></script>
<script>
$(document).ready(function(){
	var opt = {};
	opt.startpage = parseInt(url('?startpage'));
	opt.paper_outer = {'t':0, 'r':0, 'b':50, 'l':0, 'w':0, 'h':0};
	opt.h0_invisible = true;
    
	$('#dmxlivebook').dmxLivebook(opt);
});
</script>
</head>
<body>
<div id="dmxlivebook" data-role="page" data-url="livebook"></div>
</body>
</html>
