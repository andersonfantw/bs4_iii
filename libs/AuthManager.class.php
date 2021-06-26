<?PHP
require_once ROOT_PATH.'/plugin/DBbase/class/DBbase_Auth.class.php';
require_once ROOT_PATH.'/plugin/'.PLUGIN_SSO.'/class/'.PLUGIN_SSO.'_Auth.class.php';

class AuthManager{
  var $objClass;
  function __construct(){
    $_class = PLUGIN_SSO.'_Auth';
    $this->objClass = new $_class;
  }
 
  public function validAdmin(){
  	$params = func_get_args();
  	return call_user_func_array(array($this->objClass,'validAdmin'),$params);
  }
  
  public function validBSManager(){
  	$params = func_get_args();
  	return call_user_func_array(array($this->objClass,'validBSManager'),$params);
  }
  
  public function validUser(){
  	$params = func_get_args();
  	return call_user_func_array(array($this->objClass,'validUser'),$params);
  }

  public function validUserSingleLogin(){
  	$params = func_get_args();
  	return call_user_func_array(array($this->objClass,'validUserSingleLogin'),$params);
  }
}
?>
