<?php /* Smarty version Smarty-3.1.7, created on 2016-09-21 02:22:40
         compiled from "/var/www/html/bs4/templates/backend/sys_testcase_uploadqueue.tpl" */ ?>
<?php /*%%SmartyHeaderCode:69349901657ce670c4844d4-90305294%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ab0f81f13c0fd9469b116ba7f224d0cc29fe75ca' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_testcase_uploadqueue.tpl',
      1 => 1474394879,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '69349901657ce670c4844d4-90305294',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57ce670c4ca85',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ce670c4ca85')) {function content_57ce670c4ca85($_smarty_tpl) {?><html>
<head></head>
<body>
	
<div>
	<form action="../api/queue/api.php" method="post">
	<ul>
		<li>get token: </li>
		<li>
			帳號 account: <input type="text" name="acc" value="iiiebconverter" /><br />
			密碼 password: <input type="text" name="pwd" value="ttii@dRss2" /><br />
			<input type="submit" />
		</li>
	</ul>
	<input type="hidden" name="cmd" value="login">
	</form>
</div>
<div>
	<form action="../api/queue/api.php" enctype="multipart/form-data" method="post">
	<ul>
		<li>add file to queue: </li>
		<li>
			認證金鑰 token: <input type="text" name="token" value="915jAnzctxK9HpCK6HCrNi9g3blQfGGF" /><br />
			檔案代號 key: <input type="text" name="fileid" value="fileid" /><br />
			書櫃： <input type="text" name="bookshelfid" value="3" /><br />
			分類： <input type="text" name="categoryid" value="2" /><br />
			上傳檔案: <input type="file" name="uploadfile" />
			<input type="submit" />			
		</li>
	</ul>
	<input type="hidden" name="cmd" value="upload">
	</form>
</div>
<div>
	<form action="../api/queue/api.php" enctype="multipart/form-data" method="post">
	<ul>
		<li>add file to queue: </li>
		<li>
			認證金鑰 token: <input type="text" name="token" value="915jAnzctxK9HpCK6HCrNi9g3blQfGGF" /><br />
			書櫃： <input type="text" name="bookshelfid" value="3" /><br />
			檔案代號 key: <input type="text" name="fileid" value="fileid" /><br />
			計劃名稱 Project Name: <input type="text" name="ProjectName" value="Test Project" />
			<input type="text" name="ProjectNameKey" value="tp" /><br />
			審查年度 Examine Year: <input type="text" name="ExamineYear" value="2016" />
			<input type="text" name="ExamineYearKey" value="2016" /><br />
			期別 stage: <input type="text" name="Stage" value="1" />
			<input type="text" name="StageKey" value="1" /><br />
			上傳檔案: <input type="file" name="uploadfile" />
			<input type="submit" />			
		</li>
	</ul>
	<input type="hidden" name="cmd" value="upload">
	</form>
</div>
<div>
	<form action="../api/queue/api.php" enctype="multipart/form-data" method="post">
	<ul>
		<li>update file / tag(s) to queue: </li>
		<li>
			認證金鑰 token: <input type="text" name="token" value="915jAnzctxK9HpCK6HCrNi9g3blQfGGF" /><br />
			檔案代號 key: <input type="text" name="fileid" value="fileid" /><br />
			書櫃： <input type="text" name="bookshelfid" value="3" /><br />
			分類： <input type="text" name="categoryid" value="2" /><br />
			上傳檔案: <input type="file" name="uploadfile" />
			<input type="submit" />			
		</li>
	</ul>
	<input type="hidden" name="cmd" value="update">
	</form>
</div>
<div>
	<form action="../api/queue/api.php" enctype="multipart/form-data" method="post">
	<ul>
		<li>update file / tag(s) to queue: </li>
		<li>
			認證金鑰 token: <input type="text" name="token" value="915jAnzctxK9HpCK6HCrNi9g3blQfGGF" /><br />
			書櫃： <input type="text" name="bookshelfid" value="3" /><br />
			檔案代號 key: <input type="text" name="fileid" value="fileid" /><br />
			計劃名稱 Project Name: <input type="text" name="ProjectName" value="Test Project1" />
			<input type="text" name="ProjectNameKey" value="tp1key" /><br />
			審查年度 Examine Year: <input type="text" name="ExamineYear" value="2016" />
			<input type="text" name="ExamineYearKey" value="2016key" /><br />
			期別 stage: <input type="text" name="Stage" value="1" />
			<input type="text" name="StageKey" value="1key" /><br />
			上傳檔案: <input type="file" name="uploadfile" />
			<input type="submit" />			
		</li>
	</ul>
	<input type="hidden" name="cmd" value="update">
	</form>
</div>
<div>
	<form action="../api/queue/api.php" enctype="multipart/form-data" method="post">
	<ul>
		<li>目前轉換進度 check progress in queue: </li>
		<li>
			認證金鑰 token: <input type="text" name="token" value="915jAnzctxK9HpCK6HCrNi9g3blQfGGF" /><br />
			<input type="submit" />			
		</li>
	</ul>
	<input type="hidden" name="cmd" value="progress">
	</form>
</div>
<div>
	<form action="../api/queue/api.php" enctype="multipart/form-data" method="post">
	<ul>
		<li>檔案轉換狀態，以檔案代號查詢 check status by fileid(key): </li>
		<li>
			認證金鑰 token: <input type="text" name="token" value="915jAnzctxK9HpCK6HCrNi9g3blQfGGF" /><br />
			<input type="text" name="fileid" value="fileid" /><br />
			<input type="submit" />			
		</li>
	</ul>
	<input type="hidden" name="cmd" value="chkstatus">
	</form>
</div>
<div>
	<form action="../api/queue/api.php" enctype="multipart/form-data" method="post">
	<ul>
		<li>刪除排程檔案 delete record in queue: </li>
		<li>
			認證金鑰 token: <input type="text" name="token" value="915jAnzctxK9HpCK6HCrNi9g3blQfGGF" /><br />
			檔案代號 key: <input type="text" name="fileid" value="fileid" /><br />
			<input type="submit" />			
		</li>
	</ul>
	<input type="hidden" name="cmd" value="delete">
	</form>
</div>
</body>
</html><?php }} ?>