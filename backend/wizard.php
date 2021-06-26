<?php
require_once dirname(__FILE__).'/../config.php';

$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
$group = new group(&$db);
/*if($group->checkGroupExist()){
	$tpl->assign('WIZARD','off');	
}*/
$step = (int) $fs->valid($_GET['step'],'num');
if($step<1){
  $step = 1;}

  $error = false;

  /*
     $db_process = new db_process(&$db,'groups','g_');
     $group = new group(&$db);

     if($type=='do_add' || $type=='do_update'){
     $data['g_name'] = $fs->valid($_POST['g_name'],'name');
     $data['g_account'] = $fs->valid($_POST['g_account'],'acc');
     $data['c_id'] = $_POST['c_id'];
     }
   */
  switch ($step) {
    case 1:
      $tpl->display('backend/wizard_step_1.tpl');
      break;
    case 2:
      $grade_number = (int) $fs->valid($_POST['grade_number'],'num');
      $tpl->assign('grade_number',$grade_number);
      $tpl->display('backend/wizard_step_2.tpl');
      break;
    case 3:
      $class_number_arr = $fs->valid($_POST['class_number'],'num');
      $grade_number = sizeof($class_number_arr);
      foreach($class_number_arr as $key=>$val){
        $class_number_arr[$key] = (int) $val;
      }
      $group = new group(&$db);
      $c_db_process = new db_process(&$db,'category','c_');
      //新增年級主分類
      for($i=1;$i<=$grade_number;$i++){
        unset($c_data);
        $c_data['c_name'] = $i.'年級';
				$c_data['bs_id'] = $bs_code;
        $rs = $c_db_process->insert($fs->sql_safe($c_data),true);
        if(!$rs){
          $error = true;
          break;
        }else{
          $grade_id[$i] = $rs;
        }
      }
      //新增班級次分類
      for($i=1;$i<=$grade_number;$i++){
        unset($c_data);
        for($j=1;$j<=$class_number_arr[$i-1];$j++){
          $c_data['c_name'] = $i.'年'.$j.'班';
          $c_data['c_parent_id'] = $grade_id[$i];
					$c_data['bs_id'] = $bs_code;
          $rs = $c_db_process->insert($fs->sql_safe($c_data),true);
          if(!$rs){
            $error = true;
            break;
          }else{
            $class_id[] = $rs;
            //新增群組並且對應到班級次分類
            unset($g_data);
            $g_data['g_name'] = $i.'年'.$j.'班';
            //$g_data['g_account'] = 'g'.$i.'c'.$j;
			      //$g_data['g_password'] = md5('123456');
            $g_data['c_id'] = array($rs);
			      //$g_data['bs_id'] = $bs_code;
            if(!$group->insert($fs->sql_safe($g_data))){
              $error = true;
              break;
            }
          }
        }
      }
      //新增老師群組並且對應到所有班級次分類
	    unset($g_data);
	    $g_data['g_name'] = '老師';
	    //$g_data['g_account'] = 'teacher';
	    //$g_data['g_password'] = md5('123456');
	    $g_data['c_id'] = $class_id;
	    //$g_data['bs_id'] = $bs_code;
	    if(!$group->insert($fs->sql_safe($g_data))){
	      $error = true;
	      break;
	    }
			if(!$error){
			  $status->go('group.php','success','初始化精靈設定成功');
			}else{
			  $status->go('wizard.php','error','初始化精靈設定失敗失敗');
			}
		}
