<?php
	require_once dirname(__FILE__).'/../init/config.php';
	
	//如果是手持裝置
	$data = array();
  $browser = new browser();
  $data['ua_type'] = $browser::detect('ua_type');
  $data['mobile_test'] = $browser::detect('mobile_test');
  switch($browser::detect('ua_type')){
		case 'bot':	//(web bot)
			$data['currentDevicePath'] = WEB_URL."/desktop/";
			$data['ua_type_text'] = "web bot";
			break;
		case 'bro':	//(normal browser)
			$data['currentDevicePath'] = WEB_URL."/desktop/";
			$data['ua_type_text'] = "normal browser";
			break;
		case 'bbro':	//(simple browser)
			$data['currentDevicePath'] = WEB_URL."/desktop/";
			$data['ua_type_text'] = "simple browser";
			break;
		case 'mobile':	//(handheld)
			if(MAPPING_DEVICE && ($data['mobile_test']=='iphone')){
				$data['currentDevicePath'] = WEB_URL."/iphone/";
			}else{
				//ipad
				$data['currentDevicePath'] = WEB_URL."/desktop/";
			}
			$data['ua_type_text'] = "handheld";
			break;
		case 'dow':	//(downloading agent)
			$data['currentDevicePath'] = WEB_URL."/desktop/";
			$data['ua_type_text'] = "downloading agent";
			break;
		case 'lib':	//(http library)
			$data['currentDevicePath'] = WEB_URL."/desktop/";
			$data['ua_type_text'] = "http library";
			break;	
	}
	$output = $json = new Services_JSON();
	header('Content-Type: application/json; charset=utf-8');
	echo $json->encode($data);
	exit;
?>
