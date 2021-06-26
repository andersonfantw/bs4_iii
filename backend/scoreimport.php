<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
$ImportManager = new ImportManager();

$m = $fs->valid($_GET['m'],'num');
$cmd = $fs->valid($_GET['cmd'],'cmd');
if(empty($m)){
  switch($cmd){
    case 'import_infoacer':
      $m = ScoreImportManagerModeEnum::InfoacerExam1;
      break;
  }
  header('LOCATION: scoreimport.php?m='.$m);
}
$tpl->assign('mode',$m);
$tpl->display('backend/scoreimport.tpl');