﻿<?PHP
session_start();
if(!isset($_SESSION['uid'])){
        echo 'Please open book from bookshelf!';exit;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>EcocatCMS</title>
  <script src="scripts/AC_RunActiveContent.js" type="text/javascript"></script>
  <script src="scripts/send_js.js" type="text/javascript"></script>
  <script src="/scripts/analyztis.js" type="text/javascript"></script>
  <script type="text/javascript">send_js();</script>
<!-- DRM OFF -->
<link rel="stylesheet" href="css/default.css" type="text/css" />
</head>
<body bgcolor="#FFFFFF" onLoad="initWindow()" onResize="resizeWindow()">
<div class="crawler">
    <p>SAMPLE規程</p>
    <p>ページ&nbsp;
</p>
</div>
<script type="text/javascript">

AC_FL_RunContent( 'codebase','http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0','width','1010','height','655 ','align','middle','src','book','FlashVars','000000','loop','false','menu','true','quality','high','salign','lt','bgcolor','#FFFFFF','allowscriptaccess','sameDomain','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','book' ); //end AC code>>

</script>
<noscript>
  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="1010" height="655" align="middle">
    <param name="allowScriptAccess" value="sameDomain" />
    <param name="movie" value="book.swf" />
    <param name="loop" value="false" />
    <param name="menu" value="true" />
    <param name="quality" value="high" />
    <param name="salign" value="lt" />
    <param name="bgcolor" value="#FFFFFF" />
    <embed src="book.swf" loop="false" menu="true" quality="high" salign="lt" bgcolor="#FFFFFF" width="1010" height="655" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
  </object>
</noscript>

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,popupwidth,popupheight) { //v2.0
  if (navigator.userAgent.indexOf('Safari') >= 0) {
    window.open(theURL,winName,'width=' + (popupwidth + 1) + ',height=' + (popupheight + 1) + ',resizable=yes' + ',scrollbars=yes');
  } else {
    window.open(theURL,winName,'width=' + popupwidth + ',height=' + popupheight + ',resizable=yes' + ',scrollbars=yes');
  }
}
//-->
</script>
</body>
</html>
