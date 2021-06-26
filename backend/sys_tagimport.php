<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');
$ImportManager = new ImportManager();

$m = $fs->valid($_GET['m'],'num');
$cmd = $fs->valid($_GET['cmd'],'cmd');
if(empty($m)){
  switch($cmd){
          case 'import_tag':
            $m = TagImportModeEnum::Tags;
            break;
					case 'import_dictionary':
						$m = TagImportModeEnum::Dictionary;
						break;
					default:
						exit;
  }
  header('LOCATION: sys_tagimport.php?m='.$m);
}
$tpl->assign('mode',$m);
$tpl->display('backend/sys_tagimport.tpl');