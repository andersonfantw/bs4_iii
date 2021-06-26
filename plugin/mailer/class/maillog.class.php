<?PHP
class maillog extends db_process{
	var $bindBook = false;
	var $key = '';
	var $arrCol = null;

  function maillog($db) {
    parent::db_process($db,'maillog','ml_');
  }

	function insert($data){
		$data['createdate'] = date('Y-m-d H:i:s');
		parent::insert($data);
	}
}
?>