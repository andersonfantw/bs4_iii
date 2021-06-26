<?php
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('db','sysauth','tpl','filter','ehttp','getIP');

if(empty($_SESSION['adminip']) || $_SESSION['adminip']!=$USER_IP){
	exit;
}

abstract class DisplayEnum{
	const NONE=0;
	const MSG = 1;
	const CONFIRM = 2;
	const DataResult = 4;
}

$content= $_POST['content'];
$sqlstatement= $fs->valid($_POST['sqlstatement'],'content');
$token = $fs->valid($_POST['token'],'lnettoken');

$status=array('code'=>'0','msg'=>'');
$display = 0;

if(!empty($sqlstatement) && !empty($token)){
	$result = common::checkToken($token);
	if($result){
		$sql_cmd = 'done';
		$str = base64_decode($sqlstatement);
		list($cmd,$sql) = preg_split('/\|@\|/',$str);
		switch($smd){
			case 'select':
				$data = $db->get_results($sql);
				break;
			default:
				$db->query($sql);
				break;
		}
		$status = $db->getError();
		if($status['code']!='0') $valid=false;
	}
}


echo '<h1>DB CONSOLE</h1>';

//if delete statment, double check

$sqlstatment = array('delete'=>'/^delete from (bookshelf2_[a-zA-z0-9_]+)/',
											'droptable'=>'/^drop table (bookshelf2_[a-zA-z0-9_]+)/',
											'dropview'=>'/^drop view (bookshelf2_[a-zA-z0-9_]+)/',
											'altertable'=>'/^alter table (bookshelf2_[a-zA-z0-9_]+) (add|drop column|modify) ([a-zA-z0-9_]+)/',
											'createtable'=>'/^CREATE TABLE (BOOKSHELF2_[a-zA-z0-9_]+)/',
											'createview'=>'/^CREATE VIEW (BOOKSHELF2_[a-zA-z0-9_]+)/',
											'update'=>'/^update (bookshelf2_[a-zA-z0-9_]+) set [^;]+/',
											'select'=>'/^select [^;]+ from (bookshelf2_[a-zA-z0-9_]+)/',
											'insert'=>'/^insert into (bookshelf2_[a-zA-z0-9_]+)/',
											'magicword'=>'/^Anderson says\:(.+)/');

$content_nonewline = str_replace("\n","",$content);
foreach($sqlstatment as $key => $regex){
	preg_match($regex, $content_nonewline, $matches);
	if(!empty($matches)){
		$sql_table = strtoupper($matches[1]);
		switch($key){
			case 'select':
				$sql_cmd=$key;
				$arr = explode('where',$content_nonewline);
				$sql_where = array_pop($arr);
				$data = $db->get_results($content);
				break;
			case 'delete':
			case 'update':
				$sql_cmd=$key;
				$arr = explode('where',$content_nonewline);
				$sql_where = array_pop($arr);

				$checkstatment = sprintf('select * from %s%s',$sql_table,$sql_where);
				$data = $db->get_results($checkstatment);
				break;
			case 'droptable':
				$sql_cmd=$key;
				$checkstatment = sprintf('select * from %s limit 10',$sql_table);
				$data = $db->get_results($checkstatment);
				break;
			case 'altertable':
				$sql_cmd=$key;
				$sql_subcmd = $matches[2];
				$sql_col=strtoupper($matches[3]);
				$checkstatment="select * from SYSCOLUMN where table_name='%s' and column_name='%s'";
				$checkstatment=sprintf($checkstatment,$sql_table,$sql_col);
				$data_col = $db->query_first($checkstatment);
				break;
			case 'createtable':
			case 'createview':
			case 'dropview':
			case 'insert':
				$sql_cmd=$key;
				$db->query($content);
				break;
			case 'magicword':
				$sql_cmd='select';
				$checkstatment = $matches[1];
				$data = $db->get_results($checkstatment);
				break;
		}
		$status = $db->getError();
	}
}
?>
<br />
<form method="post" action="sys_cdb.php">
<textarea name="content" cols="160" rows="50"><?PHP echo $content;?></textarea><br />
<input type="submit">
</form>
<?PHP
	if(empty($sql_cmd)){
		$msg='Syntax error!';
	}
	switch($sql_cmd){
		case 'delete':
			if(empty($sql_where)){
				$msg="Don't allow to delete all rows!";
				$display = DisplayEnum::MSG;
			}else{
				$msg='All theses rows will be delete! ARE YOU SURE?';
				$display = DisplayEnum::CONFIRM;
				if($status['code']=='0') $display |= DisplayEnum::DataResult;
			}
			break;
		case 'droptable':
			$msg=sprintf('Table %s will be drop! ARE YOU SURE?',$sql_table);
			$display = DisplayEnum::CONFIRM;
			if($status['code']=='0') $display |= DisplayEnum::DataResult;
			break;
		case 'altertable':
			$msg=sprintf('%s.%s %s(%s) will be %s, ARE YOU SURE?',$sql_table,$sql_col,$data_col['type_name'],$data_col['precision'],$sql_subcmd);
			$display = DisplayEnum::CONFIRM;
			if($status['code']=='0') $display |= DisplayEnum::DataResult;
			break;
		case 'update':
			if(empty($sql_where)){
				$msg="Don't allow to update all rows!";
				$display = DisplayEnum::MSG;
			}else{
				$msg='Data of theses rows will be change! ARE YOU SURE?';
				$display = DisplayEnum::CONFIRM;
				if($status['code']=='0') $display |= DisplayEnum::DataResult;
			}
			break;
		case 'select':
			$display = DisplayEnum::MSG;
			if($status['code']=='0') $display |= DisplayEnum::DataResult;
			break;
		case 'insert':
		case 'createview':
		case 'dropview':
		case 'done':
			if($status['code']==0){
				$msg = 'DONE!';
			}else{
				$msg = 'ERROR! '.$status['mag'];
			}
			$display = DisplayEnum::MSG;
			break;
	}
	if($status['code']!='0'){
		$display = DisplayEnum::MSG;
		$msg=$status['msg'];
	}
	$msg_html = sprintf('<span style="font-size:18px;font-weight:bold;color:#f00">%s</span>',$msg);

	$str=<<<HTML
<form method="post" action="sys_cdb.php">
	%s
	<input type="hidden" name="sqlstatement" value="%s" />
	<input type="hidden" name="token" value="%s" />
	<input type="submit" value="YES" />
	<input type="button" value="NO" onclick="document.location.href=document.location.href" />
</form>
HTML;
	if($display & DisplayEnum::MSG){
		echo $msg_html;
	}
	if($display & DisplayEnum::CONFIRM){
		$token = common::makeToken();
		echo sprintf($str,$msg_html,base64_encode($sql_cmd.'|@|'.$content),$token);
	}
	if($display & DisplayEnum::DataResult){
		$from =0;
		$to = count($data);
		//if($to>200){
		//	$from = $to - 200;
		//}

		if($sql_cmd=='select'){
			if(empty($data)){
				echo '0 row found!';
				exit;
			}
			echo '<table><tr>';
			foreach($data[0] as $k=>$v){
				echo sprintf('<td style="background:#ccc">%s</td>',$k);
			}
			echo '</tr>';
			for($i=$from;$i<$to;$i++){
				echo '<tr>';
				foreach($data[$i] as $v){
					echo sprintf('<td>%s</td>',$v);
				}
				echo '</tr>';
			}
			echo '</table></form>';
		}else{
			$sql="select column_name, type_name from SYSCOLUMN where table_name='%s' order by column_order";
			$sql=sprintf($sql,strtoupper($sql_table));
			$data_cols = $db->get_results($sql);

			echo '<table><tr>';
			foreach($data_cols as $col){
				echo sprintf('<td style="background:#ccc">%s</td>',$col['column_name']);
			}
			echo '</tr>';
			if(!empty($data)){
				for($i=$from;$i<$to;$i++){
					echo '<tr>';
					for($j=0;$j<count($data_cols);$j++){
						$col = strtolower(trim($data_cols[$j]['column_name']));
						echo sprintf('<td>%s</td>',$data[$i][$col]);
					}
					echo '</tr>';
				}
			}
			echo '</table></form>';
		}
	}
?>
