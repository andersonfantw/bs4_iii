<?PHP
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('db');
const BR = '<br>';
$testcases = array('cloud_api'=>'testcase_cloud',
									'hanhua_api'=>'testcase_hanhua');

foreach($testcases as $key => $val){
	echo $key.BR;
	require_once ROOT_PATH.'/Tool/api/'.$val.'.php';
	$testcase_class = new $val;
	$testcase_class->test();
}
?>