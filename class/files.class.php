<?php
class files extends db_process{
  function files($db)
  {
  	parent::db_process($db,'file','f_');
  }

	function insert($data){
		$data['f_time'] = date("Y-m-d H:i:s");
		return parent::insert($data,true);		
	}

  function update($id,$data)
  {
    $rs = $this->getByID($id);
    $f_path = $rs['f_path'];
    $data['f_time'] = date("Y-m-d H:i:s");

		if(parent::update($id,$data)){
			@unlink($f_path);
			return true;
		}
		return false;
  }

  function del($id)
  {
    $rs = $this->getByID($id);
    $f_path = $rs['f_path'];

		if(parent::del($id)){
      @unlink($f_path);
      return true;
		}
		return false;
  }
}
?>
