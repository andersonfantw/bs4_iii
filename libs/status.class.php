<?php

class status{

  function status(){
  }

  function set_status($code,$msg){
    setcookie('sysmsg',$msg,time()+60,'/');
	setcookie('syscode',$code,time()+60,'/');
  }

  function get_status_desc(){
    $msg = stripslashes($_COOKIE['sysmsg']);
    setcookie('sysmsg','',0,'/');
		unset($_COOKIE['sysmsg']);
    return $msg;
  }
  
  function get_status_code(){
    $code = $_COOKIE['syscode'];
	setcookie('syscode','',0,'/');
	unset($_COOKIE['syscode']);
    return $code;
  }

  function go($url = '',$code='',$msg =''){
	$this->set_status($code,$msg);
	header('location:'.$url);
  }
  
  function back($code='',$msg =''){
	echo "
		<html>
		<head> 
	    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>   
		</head>
		<body>
		<script language='javascript'>		  		  
		  /*errDialog();
          $('#dialog p').html('".$msg."');
          $('#dialog').dialog('open');*/
		  alert('".$msg."');
		  history.go(-1);
		</script>
		</body>
		</html>
		";
  }
  
}