<?PHP
class Javascript{
	var $ajaxCache=false;

	function __construct(){
	}
	public function ajaxCache($_ajaxCache){
		$this->ajaxCache = $_ajaxCache;
	}
	public function output(){
		if($this->ajaxCache){
			echo '$.ajaxSetup({ cache: true });';
		}else{
			echo '$.ajaxSetup({ cache: false });';
		}
	}
}
?>