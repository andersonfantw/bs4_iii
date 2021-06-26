<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('auth','bookshelf_auth','tpl','status','db','ehttp');
require_once dirname(__FILE__).'/init.php';

$bookshelf_share_source = new bookshelf_share_source(&$db,'bookshelf_share_source');
$data_source = $bookshelf_share_source->getList('bsss_id desc');
$tpl->assign('data',$data_source['result']);
$tpl->display('backend/ecocat.tpl');
?>
