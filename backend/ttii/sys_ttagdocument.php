<?PHP
/*
sys_testcase_tagdocument
*/
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('tpl','sysauth','db','ehttp');

$input =<<<STRING
//subject:科目=
Math:數學,English:英文,Chinese:中文
//subject:科目/Math:數學=
math1:第一課,math2:第二課
STRING;

$TagDocument = new TagDocument();

$TagDocument->loadString($input);
$TagDocument->loadDB();
$TagDocument->saveDB();
echo $TagDocument->toString();

$input = 'key:q=t:k,t1:k1';
$TagDocument->loadDictionaryString($input);
echo $TagDocument->exportDictionaryString();
?>
