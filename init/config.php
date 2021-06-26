<?php
$path = '/data/IncludeFile';
set_include_path(get_include_path() . ':.:' . $path);

ini_set('session.gc_maxlifetime', 86400);
//session_set_cookie_params(86400);

ini_set('session.use_trans_sid', false);
ini_set('session.use_cookies', true);
ini_set('session.use_only_cookies', true);

ini_set('session.cookie_httponly', 1);
session_start();
/*正式上線請註解掉下面這兩行*/
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ERROR);
ini_set('display_errors','On');
/*正式上線請註解掉上面這兩行*/

$cookieLifetime = 365 * 24 * 60 * 60; // A year in seconds
setcookie(session_name(),session_id(),time()+$cookieLifetime);

mb_internal_encoding("UTF-8");
header('Content-type: text/html; charset=utf-8');
header("X-Frame-Options: DENY");

define('ECOCAT_VERSION','305');
define('GLOBAL_IDENTIFIER','bs3');
//General site parameters prefix
define('SITE_PREFIX','site_');

//和Ecocat連接 (開啟mtsqli)
//define('CONNECT_ECOCAT',true);
//true: import convert files, false: link convert files
//default: true
define('CONNECT_ECOCAT_IMPORT',true);

//是否開啟裝置對應的顯示模式
define('MAPPING_DEVICE',false);
//是否開啟js偵錯模式
//define('DEBUG_MODE',false);

//功能設定
//初始化設定精靈(目前都關閉)
define('WIZARD',false);
//心得拼圖遊戲
define('REFLECTION_GAME',false);

/*File pool */
define('FPS_THEME_ASSET','/assets/default');
define('FPS_THEME_URL',WEB_URL.FPS_THEME_ASSET);
define('FPS_SYS_ASSET','/assets/system');
define('FPS_SYS_URL',WEB_URL.FPS_SYS_ASSET);

define('CACHE_VER',1);
define('PER_PAGE',10);
define('CHECK_CODE','asdh8@&#1');

/**
 * ...
 */
define("ENCRYPT_KEY", "CRYPT_KEY_FOR_ECOCATCMS");

define("MAIN_DIR", dirname(dirname(__FILE__)));
define("LIB_DIR", MAIN_DIR . "/libs");
if (!defined('PEAR_DIR')) {

    define('PEAR_DIR', LIB_DIR . "/pear/");
    ini_set('include_path', PEAR_DIR . ':' . ini_get("include_path"));
}

require_once dirname(__FILE__).'/../hosts/sys_config.php';
require_once dirname(__FILE__).'/init.php';
/*********************************** AUTOLOAD *****************************************/
require_once LIBS_PATH.'/PHPMailer/PHPMailerAutoload.php';

spl_autoload_register(null, false);
spl_autoload_extensions('.class.php');
function classLoader($class){
    $path = array(CLASS_PATH."/{$class}.class.php",
			LIBS_PATH."/{$class}.class.php",
			PLUGIN_PATH."/DB/class/{$class}.class.php",
			PLUGIN_PATH."/ebookconvert/class/{$class}.class.php",
			PLUGIN_PATH."/dataimport/class/{$class}.class.php",
			PLUGIN_PATH."/scoreimport/class/{$class}.class.php",
			PLUGIN_PATH."/dataexport/class/{$class}.class.php",
			PLUGIN_PATH."/tag/libs/{$class}.class.php",
			PLUGIN_PATH."/tag/class/{$class}.class.php",
			PLUGIN_PATH."/chart/class/{$class}.class.php",
			PLUGIN_PATH."/meeting/class/{$class}.class.php",
			PLUGIN_PATH."/seminar/class/{$class}.class.php",
			PLUGIN_PATH."/membermode/class/{$class}.class.php",
			PLUGIN_PATH."/uploadqueue/class/{$class}.class.php",
			PLUGIN_PATH."/ebook2dbmaker/class/{$class}.class.php",
			PLUGIN_PATH."/mailer/class/{$class}.class.php",
			PLUGIN_PATH."/mailer/libs/{$class}.class.php",
			PLUGIN_PATH."/bigdata/class/{$class}.class.php",
			PLUGIN_PATH."/bigdata/libs/{$class}.class.php",
			PLUGIN_PATH."/search/class/{$class}.class.php",
			PLUGIN_PATH."/search/libs/{$class}.class.php"
		);

    //$class=strtolower($class);
    $file1=CLASS_PATH."/interface/{$class}.iface.php";
    if (file_exists($file1)){
        require_once $file1;
    }

		$val = false;
		foreach($path as $file){
			if(file_exists($file)){
				require_once $file;
			}
		}
		if(!$val) return false;

}
spl_autoload_register('classLoader');

/*********************************** FUNCTION FEEBACK DEFINE *****************************************/
$wid = common::get_wonderbox_id();
$wonderbox_id='';
if($wid['rc']==0) $wonderbox_id=$wid['wbox_id'];
define("wonderbox_id",$wonderbox_id);

require_once ROOT_PATH.'/hosts/sys_config.php';
?>
