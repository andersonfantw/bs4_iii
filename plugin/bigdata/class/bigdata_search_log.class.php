<?PHP
class bigdata_search_log extends db_process{
  function bigdata_search_log($db) {
  	parent::db_process($db,'bigdata_search_log','bsl_');
  }
  public function getByKey($_buid,$_type,$_date){
  	$sql=<<<SQL
select * 
from bookshelf2_bigdata_search_log
where uid=%u and user_type='%s' and createdate='%s'
SQL;
		$sql = sprintf($sql,$_buid,$_type,$_date);
		$rs = $this->db->query_first($sql);
		return $rs;
  }
}
?>
