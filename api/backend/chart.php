<?php
  require_once dirname(__FILE__).'/../../init/config.php';
  $init = new init('db');

  $cmd = $fs->valid($_POST['cmd'],'cmd');
  $p = $fs->valid($_POST['p'],'cmd');
  $t = $fs->valid($_POST['t'],'cmd');
  $q = $fs->valid($_POST['q'],'query');

  $login = new login($db);
  $login->setPeriod($p);
  $login->setType($t);
  $login->setQuery($q);
  $data = $login->getData();
  $charttype='line';
  switch($p){
  	case 'byday':
  	case 'byweek':
  	case 'bymonth':
  	case 'byyear':
  		$charttype='line';
  		break;
  	case 'dayofweek':
  	case 'hourofday':
  		$charttype='column';
  		break;
  }
  $data['type'] = $charttype;
  echo json_encode($data);
?>