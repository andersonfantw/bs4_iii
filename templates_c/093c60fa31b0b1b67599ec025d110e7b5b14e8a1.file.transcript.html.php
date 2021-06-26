<?php /* Smarty version Smarty-3.1.7, created on 2016-09-07 09:47:48
         compiled from "/var/www/html/bs4/view/page/transcript.html" */ ?>
<?php /*%%SmartyHeaderCode:181626994757cf71c4e31735-34752860%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '093c60fa31b0b1b67599ec025d110e7b5b14e8a1' => 
    array (
      0 => '/var/www/html/bs4/view/page/transcript.html',
      1 => 1471795653,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '181626994757cf71c4e31735-34752860',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bsid' => 0,
    'uid' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57cf71c4e81c5',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57cf71c4e81c5')) {function content_57cf71c4e81c5($_smarty_tpl) {?><html>
<head>
<title></title>
<script src="<?php echo @WEB_URL;?>
/scripts/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="<?php echo @WEB_URL;?>
/scripts/loader.class.js" type="text/javascript"></script>
<script src="<?php echo @WEB_URL;?>
/scripts/loader_transcript.js" type="text/javascript"></script>

<link type="text/css" href="<?php echo @WEB_URL;?>
/images/desktop/style.css" rel="stylesheet" />
<script type="text/javascript">
var bs_id = <?php echo $_smarty_tpl->tpl_vars['bsid']->value;?>
;
var uid = <?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
;
var web_url = '<?php echo @WEB_URL;?>
';
</script>
</head>
<body>
<div id="content">
<h1>Transcript</h1>
<span>Bookshelf Name:</span>
<span>User Name:</span>
<table cellpadding="0" cellspacing="2">
	<thead>
	<tr>
		<th colspan="6">Bookshelf Name Test Score</th>
	</tr>
  <tr>
    <th>Test Name</th>
    <th>Date</th>
    <th>Correct/Total</th>
    <th>Points</th>
    <th>Score</th>
    <th>Average</th>
  </tr>
	</thead>
	<tbody>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	</tbody>
	<tfoot>
  <tr>
    <td>Average</td>
    <td colspan="2" class="correct"></td>
    <td colspan="3" class="all_avg"></td>
  </tr>
	</tfoot>
</table>
</div>
</body>
</html>
<?php }} ?>