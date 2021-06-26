<?PHP
$status = $_SERVER['REDIRECT_STATUS'];
$codes = array(
        403 => array('403 Forbidden', 'The server has refused to fulfill your request.'),
        404 => array('404 File Not Found', 'The document/file requested was not found.'),
        405 => array('405 Method Not Allowed', 'The method specified in the Request-Line is not allowed for the specified resource.'),
        408 => array('408 Request Timeout', 'Your browser failed to sent a request in the time allowed by the server.'),
        500 => array('500 Internal Server Error', 'The request was unsuccessful due to an unexpected condition encountered by the server.'),
        502 => array('502 Bad Gateway', 'The server received an invalid response from the upstream server while trying to fulfill the request.'),
        504 => array('504 Gateway Timeout', 'The upstream server failed to send a request in the time allowed by the server.')
        );
         
$title = $codes[$status][0];
$message = $codes[$status][1];
?>
<html>
<head>
<title><?PHP echo $title ?></title>
<style>
#main-content
{
	background-image:url(/errorpage/images/bg.png);
	width: 940px;
	height: 637px;
	margin: 0 auto;
}
#message{
	margin-left: 100px;
	padding-top: 20px;
	width:670px;
}
h1,h2,address,.slogan{
	text-align: left;
}
#message h2{
	font-size: 17px;
	color: #0092d4;
}
#message hr{
	color: #d1d1d1;
}
#message address{
	color: #757575;
}
#main-content .slogan{
	float: left;
	margin-top: 270px;
	margin-left: 70px;
	font-family:微軟正黑體;
	font-size:15px;
	color: #646464;
}
#buttons{
}
#buttons ul{
	list-style-type: none;
	background-image:url(/errorpage/images/icons.png);
	width: 319px;
	height: 25px;
	padding: 0px;
}
#buttons ul li{
	float: left;
}
#buttons ul li a{
	height: 25px;
	display: block;
}
#buttons ul li:nth-child(1) a{
	width: 130px;
}
#buttons ul li:nth-child(1) a:hover{
	background-image:url(/errorpage/images/icons.png);
	background-position: 0px -25px;
}
#buttons ul li:nth-child(2) a{
	width: 105px;
}
#buttons ul li:nth-child(2) a:hover{
	background-image:url(/errorpage/images/icons.png);
	background-position: -130px -25px;
}
#buttons ul li:nth-child(3) a{
	width: 84px;
}
#buttons ul li:nth-child(3) a:hover{
	background-image:url(/errorpage/images/icons.png);
	background-position: -235px -25px;
}

</style>
</head>
<body>
<center>
<div id="main-content">
<div id="message">
	<h1><?PHP echo $title ?></h1>
	<h2><?PHP echo $message ?></h2>
	<h2>Ooooooops!! 請確認您的連結的位置 <?PHP echo htmlspecialchars($_SERVER["REQUEST_URI"], ENT_QUOTES, 'utf-8') ?>，如果這個問題持續發生，請聯絡系統管理員!</h2>
	<address>Server at <?PHP echo $_SERVER["SERVER_NAME"]; ?> port <?PHP echo $_SERVER["SERVER_PORT"]; ?></address>
</div>
<div class="slogan">愛地球 愛孩子<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;為我們的後代盡一份心力
	<div id="buttons">
	<ul>
		<li><a href="http://www.ttii.com.tw"></a></li>
		<li><a href="/bs3/index.php"></a></li>
		<li><a href="http://59.125.179.141/ttii/"></a></li>
	</ul>
	</div>
</div>
</div>
</center>
</body>
</html>
