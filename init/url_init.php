<?PHP
list($host,$port) = explode(':',$_SERVER['HTTP_HOST']);
define('LocalHost','http://127.0.0.1');	//for db
define('WEB_URL','');

define('ExternalIPPort',$_SERVER['HTTP_HOST']);
define('HttpExternalIPPort','https://'.$_SERVER['HTTP_HOST']);
define('LocalIP','127.0.0.1');
define('LocalIPPort',LocalIP.':80');
//define('LocalIPPort',LocalIP.':20038');
define('HttpLocalIPPort','http://'.LocalIPPort);
define('HttpServerAddrPort','https://'.$_SERVER['SERVER_ADDR'].':80');
//define('HttpServerAddrPort','http://'.$_SERVER['SERVER_ADDR'].':20038');

//if no domain, use ExternalIPPort instead
define('Domain',ExternalIPPort);
define('HttpDomain',HttpExternalIPPort);
?>
