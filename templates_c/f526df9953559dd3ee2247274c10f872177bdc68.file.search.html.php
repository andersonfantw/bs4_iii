<?php /* Smarty version Smarty-3.1.7, created on 2020-09-17 17:20:25
         compiled from "/var/www/html/bs4/view/search/search.html" */ ?>
<?php /*%%SmartyHeaderCode:20281772995f5ad1f6714eb6-21813163%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f526df9953559dd3ee2247274c10f872177bdc68' => 
    array (
      0 => '/var/www/html/bs4/view/search/search.html',
      1 => 1600334345,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20281772995f5ad1f6714eb6-21813163',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f5ad1f684531',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f5ad1f684531')) {function content_5f5ad1f684531($_smarty_tpl) {?><!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<title><?php echo @TITLE;?>
</title>
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100;300;400;500;700;900&amp;display=swap">
		<link type="text/css" href="<?php echo @WEB_URL;?>
/hosts/config.css" rel="stylesheet" />
		<script src="<?php echo @WEB_URL;?>
/scripts/jquery-1.11.2.min.js" type="text/javascript"></script>
		<script src="<?php echo @WEB_URL;?>
/scripts/loader.class.js" type="text/javascript"></script>
		<script src="<?php echo @WEB_URL;?>
/scripts/loader_search.js" type="text/javascript"></script>
		<script src="<?php echo @WEB_URL;?>
/plugin/search/scripts/loader_index.js" type="text/javascript"></script>
<script type="text/javascript">
var bs_id = 0;
var uid = 0;
var web_url = '<?php echo @WEB_URL;?>
';
</script>
</head>

<body class="index">
    <div class="modal fade" role="dialog" tabindex="-1" id="DelQuickSearch">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">????????????</h2><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">??</span></button></div>
                <div class="modal-body">
                    <p>?????????????????? "????????????????????????" ????</p>
                </div>
                <div class="modal-footer"><button class="btn btn-light large" type="button" data-dismiss="modal">??????</button><button class="btn btn-primary large" type="button">??????</button></div>
            </div>
        </div>
    </div>
    <header>
        <div>
            <div>
                <ul class="nav d-lg-flex justify-content-lg-end">
                    <li class="nav-item active"><a class="nav-link active" href="#">??????</a></li>
                    <li class="nav-item"><a class="nav-link" href="/search/adv/">????????????</a></li>
                    <li class="nav-item"><a class="nav-link" href="/signout/">??????</a></li>
                </ul>
            </div>
            <h1>???????????????????????????</h1>
            <input id="fulltext" type="text" placeholder="??????????????????">
        </div>
    </header>
    <main class="index">
        <div class="row">
<!--
            <div class="col">
                <div data-id="" data-param=""><a href="#" class="del">x</a><a href="/search/list/" class="link w2" data-toggle="tooltip" title="??????: ??????; ????????????: ??????">??????</a><span>????????????????????????</span></div>
            </div>
            <div class="col">
                <div><a href="#" class="del">x</a><a href="#" class="link w1" data-toggle="tooltip" title="??????: ??????; ?????????: 5G">???</a><span>?????????5G??????</span></div>
            </div>
            <div class="col">
                <div><a href="#" class="del">x</a><a href="#" class="link w2">10</a><span>109???????????????????????????</span></div>
            </div>
            <div class="col">
                <div><a href="#" class="del">x</a><a href="#" class="link w1">???</a><span>?????????????????????</span></div>
            </div>
            <div class="col">
                <div><a href="#" class="del">x</a><a href="#" class="link w1">???</a><span>?????????????????????</span></div>
            </div>
            <div class="col">
                <div><a href="#" class="del">x</a><a href="#" class="link w1">???</a><span>??????????????????????????????????????????</span></div>
            </div>
-->
            <div class="col add">
                <div><a href="/search/adv/" class="link">+</a><span>??????????????????</span></div>
            </div>
        </div>
    </main>
</body>

</html>
<?php }} ?>