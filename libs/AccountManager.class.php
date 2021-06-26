<?PHP
require_once ROOT_PATH.'/plugin/DBbase/class/DBbase_Account.class.php';
require_once ROOT_PATH.'/plugin/'.PLUGIN_SSO.'/class/'.PLUGIN_SSO.'_Account.class.php';

class AccountManager{
	var $objClass;
  function __construct(){
	  $cls = PLUGIN_SSO.'_Account';
    $this->objClass = new $cls;
  }

	public function sync(){
		$params = func_get_args();
		return call_user_func_array(array($this->objClass,'sync'),$params);
	}

	public function getBSManagerList(){
		$params = func_get_args();
		return call_user_func_array(array($this->objClass,'getBSManagerList'),$params);
	}

	public function SearchManagerAccount(){
		$params = func_get_args();
		return call_user_func_array(array($this->objClass,'SearchManagerAccount'),$params);
	}

	public function getBSManagerUID(){
		$params = func_get_args();
		return call_user_func_array(array($this->objClass,'getBSManagerUID'),$params);
	}

	public function getGroupList(){
		$params = func_get_args();
		return call_user_func_array(array($this->objClass,'getGroupList'),$params);
	}

	public function getGroup(){
		$params = func_get_args();
		return call_user_func_array(array($this->objClass,'getGroup'),$params);
	}

	public function getCategoryStructure(){
		$params = func_get_args();
		return call_user_func_array(array($this->objClass,'getCategoryStructure'),$params);
	}

	public function setBSGroup(){
		$params = func_get_args();
		return call_user_func_array(array($this->objClass,'setBSGroup'),$params);
	}

	public function updGroup(){
		$params = func_get_args();
		return call_user_func_array(array($this->objClass,'updGroup'),$params);
	}

	public function getUserList(){
		$params = func_get_args();
		return call_user_func_array(array($this->objClass,'getUserList'),$params);
	}
}
?>
