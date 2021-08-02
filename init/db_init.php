<?PHP
//mysql, dbmaker
define('MYSQL_ASSOC', 1);
//define('DB_TYPE','mysql');
define('DB_TYPE','dbmaker');

define('DB_PREFIX','bookshelf2_');

//��Ʈw�s�u��T
define('DB_SERVER','CyberSite');
//define('DB_SERVER','localhost:/var/lib/mysql/CloudShelf.sock');
//define('DB_SERVER_SOCKET','/var/lib/mysql/CloudShelf.sock');
define('DB_SERVER_SOCKET','/var/lib/mysql/mysql.sock');
define('DB_USER','');
define('DB_PASS','');
define('DB_DATABASE','bs3');

if(CONNECT_ECOCAT){
define('DB_SLAVE_SERVER','localhost');
define('DB_SLAVE_USER','');
define('DB_SLAVE_PASS','');
define('DB_SLAVE_DATABASE','ecocat'.ECOCAT_VERSION.'_db');
//define('DB_SLAVE_SOCKET', '/var/lib/mysql/CloudShelf.sock');
define('DB_SLAVE_SOCKET', '/var/lib/mysql/mysql.sock');
}
?>
