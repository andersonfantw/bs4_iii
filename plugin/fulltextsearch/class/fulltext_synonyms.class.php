<?php
class fulltext_synonyms extends db_process{
	var $gid=0;
	var $arrCol = null;

  function fulltext_synonyms($db) {
  	parent::db_process($db,'fulltext_synonyms','fts_');
  }

	function getSynonyms($keyword){
		$sql=<<<SQL
select fts_name,fts_content
from bookshelf2_fulltext_synonyms
where fts_status=0 where fts_name='%s' or fts_content contain ',%s,'
union
select *
from bookshelf2_fulltext_synonyms
where fts_status=1 and fts_name='%s'
SQL;
		$sql = sprintf($sql,$keyword);
		return $this->db->get_results($sql);
	}
}
?>
