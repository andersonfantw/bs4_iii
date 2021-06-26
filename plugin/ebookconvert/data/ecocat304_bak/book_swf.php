<?PHP
session_start();
if(!isset($_SESSION['uid'])){
        echo 'Please open book from bookshelf!';exit;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title></title>
  <script src="scripts/AC_RunActiveContent.js" type="text/javascript"></script>
  <script src="scripts/jquery-1.11.0.min.js" type="text/javascript"></script>
  <script src="scripts/sns.dmx.livebook.js" type="text/javascript"></script>
  <script src="scripts/js-url.js" type="text/javascript"></script>
  <script type="text/javascript">function sns_share(type,page) {$.snsdmx(type,'openwin',location.href,page);}</script>
  <script src="scripts/send_js.js" type="text/javascript"></script>
  <script src="/scripts/analyztis.js" type="text/javascript"></script>
  <script type="text/javascript">send_js();</script>
  <!-- DRM OFF -->
  <link rel="stylesheet" href="css/default.css" type="text/css" />
  <meta property="og:title" content="" />
  <meta property="og:type" content="article" />
  <meta property="og:description" content="" />
  <meta property="og:url" content="book_swf.html?startpage=1" />
  <meta property="og:image" content="d__dmx/d__dmx__1__1000.jpg" />
  <meta property="og:site_name" content="Ecocat Cloud" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="Ecocat Cloud" />
  <meta name="twitter:title" content="" />
  <meta name="twitter:description" content="-" />
  <meta name="twitter:image" content="d__dmx/d__dmx__1__1000.jpg" />
</head>
<body bgcolor="#FFFFFF" onLoad="initWindow()" onResize="resizeWindow()">
<div align="center">
<script type="text/javascript">
  AC_FL_RunContent( 'codebase','http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0','width','1010','height','635 ','align','middle','src','book','FlashVars','000000','loop','false','menu','true','quality','high','salign','lt','bgcolor','#FFFFFF','allowscriptaccess','sameDomain','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','book' ); //end AC code>>
</script>
<noscript>
  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="1010" height="635" align="middle">
    <param name="allowScriptAccess" value="sameDomain" />
    <param name="movie" value="book.swf" />
    <param name="loop" value="false" />
    <param name="menu" value="true" />
    <param name="quality" value="high" />
    <param name="salign" value="lt" />
    <param name="bgcolor" value="#FFFFFF" />
    <embed src="book.swf" loop="false" menu="true" quality="high" salign="lt" bgcolor="#FFFFFF" width="1010" height="635" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
  </object>
</noscript>
</div>
</body>
</html>
