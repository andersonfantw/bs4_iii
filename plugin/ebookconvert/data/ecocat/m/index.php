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
<script src="js/jquery-1.11.0.min.js"></script>
<script src="/scripts/analyztis.js" type="text/javascript"></script>
<script>
$(document).on("mobileinit", function() {
    $.mobile.loader.prototype.options.disabled = true;
});
</script>
<script src="js/jquery.mobile-1.4.2.min.js"></script>
<script src="js/jquery.dmx.livebook-0.1.0.js"></script>
<script>
$(document).ready(function(){
    $('#dmxlivebook').dmxLivebook();
});
</script>
</head>
<body>
<div id="dmxlivebook"></div>
</body>
</html>
