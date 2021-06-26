<?php
class fulltext_synonyms extends db_process{
  function fulltext_synonyms($db) {
  	parent::db_process($db,'fulltext_synonyms','fts_');
  }
}
?>
