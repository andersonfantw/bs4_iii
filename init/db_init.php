<?PHP
//mysql, dbmaker
//define('DB_TYPE','mysql');
define('DB_TYPE','dbmaker');

define('DB_PREFIX','bookshelf2_');

//資料庫連線資訊
define('DB_SERVER','CyberSite');
//define('DB_SERVER','localhost:/var/lib/mysql/CloudShelf.sock');
//define('DB_SERVER_SOCKET','/var/lib/mysql/CloudShelf.sock');
define('DB_SERVER_SOCKET','/var/lib/mysql/mysql.sock');
define('DB_USER','Program');
define('DB_PASS','wishbone');
//define('DB_USER','root');
//define('DB_PASS','njA8m3at');
define('DB_DATABASE','bs3');

if(CONNECT_ECOCAT){
define('DB_SLAVE_SERVER','localhost');
define('DB_SLAVE_USER','root');
define('DB_SLAVE_PASS','njA8m3at');
define('DB_SLAVE_DATABASE','ecocat'.ECOCAT_VERSION.'_db');
//define('DB_SLAVE_SOCKET', '/var/lib/mysql/CloudShelf.sock');
define('DB_SLAVE_SOCKET', '/var/lib/mysql/mysql.sock');
}
?>
