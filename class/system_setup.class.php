<?php
class system_setup extends db_process{

  function system_setup($db) {
  	parent::db_process($db,'system','system_');
  }

  function getList(){
    $sql = sprintf("select * from %s ",$this->table);
    $data = $this->db->get_results($sql);    
    return $data;
  }

  function update($data){
    return parent::update(1,$data);
  }
}
?>
