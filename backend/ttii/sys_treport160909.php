<?php
/*
sys_testcase_acunetixreport160909
*/
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('db','sysauth','tpl','filter','ejson');

$testcase=<<<CASE
File inclusion(High) p13
Cookie input currentlang was set to http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg Pattern found: Failed opening required '/var/www/html/bs4/languages/backend/http://some-inexistent-website.acu/some_inexistent_file_with_long_name?.jpg.cfg'
/
currentlang=http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

File inclusion(High) p14
Cookie input currentlang was set to http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg Pattern found: Failed opening required '/var/www/html/bs4/languages/backend/http://some-inexistent-website.acu/some_inexistent_file_with_long_name?.jpg.cfg'
/api/api.php
currentlang=http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

File inclusion(High) p15
Cookie input currentlang was set to http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg Pattern found: Failed opening required '/var/www/html/bs4/languages/backend/http://some-inexistent-website.acu/some_inexistent_file_with_long_name?.jpg.cfg'
/api/backend/api.php
currentlang=http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

File inclusion(High) p15
Cookie input currentlang was set to http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg Pattern found: Failed opening required '/var/www/html/bs4/languages/backend/http://some-inexistent-website.acu/some_inexistent_file_with_long_name?.jpg.cfg'
/api/get_itutor.php
currentlang=http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

File inclusion(High) p16
Cookie input currentlang was set to http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg Pattern found: Failed opening required '/var/www/html/bs4/languages/backend/http://some-inexistent-website.acu/some_inexistent_file_with_long_name?.jpg.cfg'
/api/get_menu.php
currentlang=http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

File inclusion(High) p16
Cookie input currentlang was set to http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg Pattern found: Failed opening required '/var/www/html/bs4/languages/backend/http://some-inexistent-website.acu/some_inexistent_file_with_long_name?.jpg.cfg'
/api/get_sys_info.php
currentlang=http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

File inclusion(High) p17
Cookie input currentlang was set to http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg Pattern found: Failed opening required '/var/www/html/bs4/languages/backend/http://some-inexistent-website.acu/some_inexistent_file_with_long_name?.jpg.cfg'
/api/login_check.php
currentlang=http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

File inclusion(High) p17
Cookie input currentlang was set to http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg Pattern found: Failed opening required '/var/www/html/bs4/languages/backend/http://some-inexistent-website.acu/some_inexistent_file_with_long_name?.jpg.cfg'
/api/logout.php
currentlang=http://some-inexistent-website.acu/some_inexistent_file_with_long_name%3F.jpg; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p19
Cookie input currentlang was set to acu2832%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca2832 Error message found: Fatal error
/
currentlang=acu2832%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca2832; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p19
Cookie input currentlang was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: Fatal error
/
currentlang=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p20
URL encoded POST input buid was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: MySQL Error
/api/api.php
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4
buid=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##&cmd=validBookshelfList&isExpiredList=false&uid=1
Application error message(Medium) p20
URL encoded POST input cmd was set to validBookshelfList Error message found: <b>Warning</b>: preg_match() expects parameter 2 to be string, array given in <b>/Storage/var/www/html/bs4/libs/filter_string.class.php</b> on line <b>112</b><br />
/api/api.php
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4
buid=-1&cmd[]=validBookshelfList&isExpiredList=false&uid=1
Application error message(Medium) p21
Cookie input currentlang was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: Fatal error
/api/api.php
currentlang=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p22
Cookie input currentlang was set to acu10408%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca10408 Error message found: Fatal error
/api/api.php
currentlang=acu10408%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca10408; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p22
URL encoded POST input uid was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: MySQL Error
/api/api.php
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4
buid=-1&cmd=validBookshelfList&isExpiredList=false&uid=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##
Application error message(Medium) p23
URL encoded GET input cmd was set to islogin Error message found: <b>Warning</b>: preg_match() expects parameter 2 to be string, array given in <b>/Storage/var/www/html/bs4/libs/filter_string.class.php</b> on line <b>112</b><br />
/api/backend/api.php?1473212942803&cmd[]=islogin
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p23
Cookie input currentlang was set to acu5493%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca5493 Error message found: Fatal error
/api/backend/api.php
currentlang=acu5493%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca5493; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p24
Cookie input currentlang was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: Fatal error
/api/backend/api.php
currentlang=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r

Application error message(Medium) p24
Cookie input currentlang was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: Fatal error
/api/get_current_device_path.php
currentlang=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p25
Cookie input currentlang was set to acu10211%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca10211 Error message found: Fatal error
/api/get_current_device_path.php
currentlang=acu10211%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca10211; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p25
URL encoded GET input was set to acu3773%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca3773 Error message found: MySQL Error
/api/get_itutor.php?acu3773%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca3773
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p26
Cookie input currentlang was set to acu7417%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca7417 Error message found: Fatal error
/api/get_itutor.php
currentlang=acu7417%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca7417; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p27
URL encoded POST input bs was set to 0 Error message found: <b>Warning</b>: preg_match() expects parameter 2 to be string, array given in <b>/Storage/var/www/html/bs4/libs/filter_string.class.php</b> on line <b>112</b><br />
/api/get_menu.php
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4
bs[]=0&cmd=valid
Application error message(Medium) p28
URL encoded POST input cmd was set to valid Error message found: <b>Warning</b>: preg_match() expects parameter 2 to be string, array given in <b>/Storage/var/www/html/bs4/libs/filter_string.class.php</b> on line <b>112</b><br />
/api/get_menu.php
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4
bs=0&cmd[]=valid
Application error message(Medium) p28
Cookie input currentlang was set to acu6165%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca6165 Error message found: Fatal error
/api/get_menu.php
currentlang=acu6165%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca6165; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p29
Cookie input currentlang was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: Fatal error
/api/get_menu.php
currentlang=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p29
Cookie input bs was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: <b>Warning</b>: include_once(/var/www/html/bs4/hosts/1/12345/bs_config.php) [<a href='function.include-once'>function.include-once</a>]: failed to open stream: No such file or directory in <b>/Storage/var/www/html/bs4/api/get_sys_info.php</b> on line <b>9</b><br />
/api/get_sys_info.php
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; bs=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##;

Application error message(Medium) p30
URL encoded POST input bs was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: <b>Warning</b>: include_once(/var/www/html/bs4/hosts/1/12345/bs_config.php) [<a href='function.include-once'>function.include-once</a>]: failed to open stream: No such file or directory in <b>/Storage/var/www/html/bs4/api/get_sys_info.php</b> on line <b>9</b><br />
/api/get_sys_info.php
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4
bs=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##
Application error message(Medium) p30
Cookie input currentlang was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: Fatal error
/api/get_sys_info.php
currentlang=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p31
Cookie input currentlang was set to acu2634%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca2634 Error message found: Fatal error
/api/get_sys_info.php
currentlang=acu2634%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca2634; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p31
Cookie input currentlang was set to acu6112%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca6112 Error message found: Fatal error
/api/login_check.php
currentlang=acu6112%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca6112; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p32
Cookie input currentlang was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: Fatal error
/api/login_check.php
currentlang=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p32
Cookie input currentlang was set to 12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'e??c Error message found: Fatal error
/api/logout.php
currentlang=12345'"\'\");|]*%00{%0d%0a<%00>%bf%27'?##; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p33
Cookie input currentlang was set to acu6470%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca6470 Error message found: Fatal error
/api/logout.php
currentlang=acu6470%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca6470; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p33
Cookie input currentlang was set to acu10980%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca10980 Error message found: Fatal error
/assets/default/?account=assets
currentlang=acu10980%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca10980; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p34
Cookie input currentlang was set to acu7195%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca7195 Error message found: Fatal error
/assets/system/?account=assets
currentlang=acu7195%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca7195; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p34
Cookie input currentlang was set to acu3579%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca3579 Error message found: Fatal error
/templates/api/?account=templates
currentlang=acu3579%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca3579; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p35
Cookie input currentlang was set to acu1869%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca1869 Error message found: Fatal error
/view/include/?account=view
currentlang=acu1869%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca1869; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p35
Cookie input currentlang was set to acu9456%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca9456 Error message found: Fatal error
/view/list/?account=view
currentlang=acu9456%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca9456; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Application error message(Medium) p36
Cookie input currentlang was set to acu2435%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca2435 Error message found: Fatal error
/view/page/?account=view
currentlang=acu2435%EF%BC%9Cs1%EF%B9%A5s2%CA%BAs3%CA%B9uca2435; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Cross site scripting (content-sniffing)(Medium) p39
URL encoded GET input was set to 1473212866976"onmouseover=hMNr(9614)" The input is reflected inside a tag parameter between double quotes.
/api/get_itutor.php?1473212866976"onmouseover=hMNr(9614)"
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Cross site scripting (content-sniffing)(Medium) p39
URL encoded GET input was set to 1473212866976"onmouseover=hMNr(9091)" The input is reflected inside a tag parameter between double quotes.
/api/get_itutor.php?1473212866976"onmouseover=hMNr(9091)"
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Cross site scripting (content-sniffing)(Medium) p39
URL encoded GET input was set to 1473212866976"onmouseover=hMNr(9350)" The input is reflected inside a tag parameter between double quotes.
/api/get_itutor.php?1473212866976"onmouseover=hMNr(9350)"
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Error message on page(Medium) p41
Pattern found: MySQL Error
/api/get_itutor.php
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.;

Error message on page(Medium) p41
Pattern found: <b>Warning</b>: fgets(): 46 is not a valid stream resource in <b>/Storage/var/www/html/bs4/libs/Manifest.class.php</b> on line <b>87</b><br />
/desktop.appcache
currentlang=zh-tw

Error message on page(Medium) p42
Pattern found: <b>Warning</b>: Missing argument 1 for VCubeSeminarManager::__construct(), called in /Storage/var/www/html/bs4/plugin/seminar/api/api.php on line 4 and defined in <b>/Storage/var/www/html/bs4/plugin/seminar/class/VCubeSeminarManager.class.php</b> on line <b>11</b><br />
/plugin/seminar/api/api.php
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Error message on page(Medium) p43
Pattern found: <b>Warning</b>: array_key_exists() expects parameter 2 to be array, null given in <b>/Storage/var/www/html/bs4/plugin/tag/libs/TagTree.class.php</b> on line <b>123</b><br />
/plugin/tag/libs/test.php
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.; _pk_ses.1.5dc8=*; PIWIK_SESSID=lskvg0kgl6s20rajvllev7s8r4

Password field submitted using GET method(Medium) p44
form name: "<unnamed>"<br />form action: ""<br />password input: "form_password"
/plugin/piwik/
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.;

User credentials are sent in clear text(Medium) p45
Form name: login_form<br />Form action: http://203.70.194.127:8080/plugin/piwik/<br />Form method: POST<br />Form inputs:<br />- form_login [Text]<br />- form_password [Password]<br />- form_nonce [Hidden]<br />- form_rememberme [Checkbox]
/plugin/piwik/
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.;

User credentials are sent in clear text(Medium) p46
Form name: <empty><br />Form action: http://203.70.194.127:8080/plugin/piwik/<br />Form method: GET<br />Form inputs:<br />- form_login [Text]<br />- form_nonce [Hidden]<br />- form_password [Password]<br />- form_password_bis [Password]<br />- module [Hidden]<br />- action [Hidden]
/plugin/piwik/
currentlang=zh-tw; site_bsid=3; _pk_ref.1.5dc8=%5B%22%22%2C%22%22%2C1473212870%2C%22http%3A%2F%2Fwww.acunetix-referrer.com%2Fjavascript%3AdomxssExecutionSink(0%2C%5C%22'%5C%5C%5C%22%3E%3Cxsstag%3E()refdxss%5C%22)%22%5D; _pk_id.1.5dc8=35f1f2d8ac7b5723.1473212870.1.1473212870.1473212870.;

CASE;

/*
testcase
title, url, cookie, params
*/
$arr = explode("\r\n",$testcase);
list($domain,$port) = explode(':',ExternalIPPort);
$domain = $_SERVER['SERVER_ADDR'];
$arr_port=array();
if(empty($port)){
	$port='80';
	$arr_port[] = '80';
}else{
	$arr_port[] = $port;
	$arr_port[] = '80';
}

//check which address is correct
foreach($arr_port as $_port){
	if(@fsockopen($domain, $_port, $errno, $errstr, 5)){
		$port = $_port;
		break;
	}
}
echo sprintf('<div style="width:1024px;margin:20px auto">L-NET Vulnerability scanning<br />LOCAL IP: %s:%s</div><br /><br />',$domain, $port);

$table=<<<TABLE
<table style="width:1024px;border:solid 1px #000;margin:15px auto">
	<tr>
		<td>TITLE</td>
		<td style="width:900px;border-bottom:solid 1px #000;">%s</td>
	</tr>
	<tr>
		<td>DESCRIPTION</td>
		<td style="width:900px;border-bottom:solid 1px #000;">%s</td>
	</tr>
	<tr>
		<td>PATH</td>
		<td style="width:900px;border-bottom:solid 1px #000; word-break:break-all">%s</td>
	</tr>
	<tr>
		<td>HTTP REQUEST</td>
		<td style="width:900px;border-bottom:solid 1px #000; word-break:break-all">%s</td>
	</tr>
	<tr>
		<td>HTTP RESPONSE</td>
		<td style="width:900px; word-break:break-all">%s</td>
	</tr>
</table>
TABLE;

for($i=0;$i<count($arr);$i+=5){
  $http_request = "POST ".$arr[$i+2]." HTTP/1.1\r\n";
  $http_request .= "Host: ".$domain.":".$port." \r\n";
  $http_request .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
  $http_request .= "Cookie: ".$arr[$i+3]."\r\n";
  $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $http_request .= "Content-Length: ".strlen($arr[$i+4])."\r\n";
  $http_request .= "Connection: Close\r\n\r\n";

  $fp = fsockopen($domain, $port, $errno, $errstr, 10);

	$result='';
  if (!$fp) {
    echo "$errstr ($errno)<br>\n";exit;
  }else{
    fputs ($fp, $http_request.$arr[$i+4]);
    while (!feof($fp)) {
      $result .= fread($fp,32000);
    }
  }
  fclose ($fp);
  $output = preg_split("/\\r\\n\\r\\n/",$result,2);
  echo sprintf($table,$arr[$i],$arr[$i+1],$arr[$i+2],str_replace("\n","<br />",$http_request)."<br />".$arr[$i+4],str_replace("\n","<br />",$output[0])."<br /><br />".$output[1]);
  ob_flush();
  flush();
}
?>