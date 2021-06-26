<?
require_once dirname(__FILE__).'/libs/browser_detection_php_ar.php';
?>
browser_math_number:	<?=browser_detection('browser_math_number');?><br />
browser_name:			<?=browser_detection('browser_name');?><br />
browser_number:			<?=browser_detection('browser_number');?><br />
browser_working:		<?=browser_detection('browser_working');?><br />
dom:					<?=browser_detection('dom');?><br />
ie_version:				<?=browser_detection('ie_version');?><br />
mobile_test:			<?=browser_detection('mobile_test');?><br />
mobile_data:						<?=browser_detection('mobile_data');?><br />
mobile_data[mobile_device]:			<?//=browser_detection('mobile_data')[0];?><br />
mobile_data[mobile_browser]:		<?//=browser_detection('mobile_data')[1];?><br />
mobile_data[mobile_browser_number]:	<?//=browser_detection('mobile_data')[2];?><br />
mobile_data[mobile_os]:				<?//=browser_detection('mobile_data')[3];?><br />
mobile_data[mobile_os_number]:		<?//=browser_detection('mobile_data')[4];?><br />
mobile_data[mobile_server]:			<?//=browser_detection('mobile_data')[5];?><br />
mobile_data[mobile_server_number]:	<?//=browser_detection('mobile_data')[6];?><br />
mobile_data[mobile_device_number]:	<?//=browser_detection('mobile_data')[7];?><br />
mobile_data[mobile_tablet]:			<?//=browser_detection('mobile_data')[8];?><br />
moz_data:							<?=browser_detection('moz_data');?><br />
moz_data[moz_type]:					<?//=browser_detection('moz_data')[0];?><br />
moz_data[moz_type_number]:			<?//=browser_detection('moz_data')[1];?><br />
moz_data[moz_rv]:					<?//=browser_detection('moz_data')[2];?><br />
moz_data[moz_rv_full]:				<?//=browser_detection('moz_data')[3];?><br />
moz_data[moz_release_date]:			<?//=browser_detection('moz_data')[4];?><br />
os:						<?=browser_detection('os');?><br />
os_number:				<?=browser_detection('os_number');?><br />
run_time:				<?=browser_detection('run_time');?><br />
safe:					<?=browser_detection('safe');?><br />
true_ie_number:			<?=browser_detection('true_ie_number');?><br />
ua_type:				<?=browser_detection('ua_type');?><br />
webkit_data:			<?=browser_detection('webkit_data');?><br />
html_type:				<?=browser_detection('html_type');?><br />
engine_data:			<?=browser_detection('engine_data');?><br />
$_SERVER['HTTP_HOST'] 		<?=$_SERVER['HTTP_HOST'];?><br />
$_SERVER['SCRIPT_NAME']		<?=$_SERVER['SCRIPT_NAME'];?><br />
$_SERVER['PATH_INFO']		<?=$_SERVER['PATH_INFO'];?>