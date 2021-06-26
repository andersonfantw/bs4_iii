<?PHP
//installed languages;
$SystemLanguage = array('vi','zh-tw','zh-cn','en','jp');
//$CURRNT_LANGUAGE='zh-tw';
$CURRNT_LANGUAGE=DEFAULT_LANGUAGE;

//detect language
$str_lang =  $_SERVER['HTTP_ACCEPT_LANGUAGE'];

$langs = explode(',',$str_lang);
foreach($langs as $lang){
  list($codelang,$quoficient) = explode(';',$lang);
  $userlang[] = strtolower($codelang);
}

if(count($userlang)>0){
	foreach($userlang as $lang){
		if(in_array($lang, $SystemLanguage)){
			$CURRNT_LANGUAGE = $lang;
			break;
		}
	}
}
//var_dump($_currentlang,$SystemLanguage);
//safety check
$_currentlang = $_COOKIE['currentlang'];
if(in_array($_currentlang,$SystemLanguage)){
	$CURRNT_LANGUAGE = htmlspecialchars(strip_tags(trim($_currentlang)));
}else{
	$CURRNT_LANGUAGE = DEFAULT_LANGUAGE;
}
//var_dump($CURRNT_LANGUAGE,$_currentlang);
setcookie('currentlang', $CURRNT_LANGUAGE, strtotime( '+7 days' ), '/');
require_once LANGUAGES_PATH.'/backend/'.$CURRNT_LANGUAGE.'.cfg';
require_once LANGUAGES_PATH.'/'.$CURRNT_LANGUAGE.'/lang.cfg';
?>
