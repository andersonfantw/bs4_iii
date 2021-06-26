<?
$cmd="curl -s \"http://127.0.0.1:8001/unet/cloudbook.crondRestart\"";
system($cmd, $rc_curl);
echo $rc_curl;

?>