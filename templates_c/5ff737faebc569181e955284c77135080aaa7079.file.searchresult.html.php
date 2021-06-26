<?php /* Smarty version Smarty-3.1.7, created on 2020-10-06 17:32:36
         compiled from "/var/www/html/bs4/view/search/searchresult.html" */ ?>
<?php /*%%SmartyHeaderCode:9587772585f5ad9ec6a34e2-33202791%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5ff737faebc569181e955284c77135080aaa7079' => 
    array (
      0 => '/var/www/html/bs4/view/search/searchresult.html',
      1 => 1601976333,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9587772585f5ad9ec6a34e2-33202791',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f5ad9ec6f808',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f5ad9ec6f808')) {function content_5f5ad9ec6f808($_smarty_tpl) {?><!DOCTYPE html>
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
/plugin/search/scripts/loader_result.js" type="text/javascript"></script>
<script type="text/javascript">
var bs_id = 0;
var uid = 0;
var web_url = '<?php echo @WEB_URL;?>
';
</script>
</head>

<body class="result">
    <div class="modal fade" role="dialog" tabindex="-1" id="EditQuickSearchTitle">
    	<form id="QuickSearchTitleForm" class="bv-form" onsubmit="return false;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title"><a>新增</a>快速查詢名稱</h2><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                <div class="modal-body">
                    <div class="form-group"><span>完整名稱(必填)：</span><input type="text" id="QuickSearchName" class="form-control"></div>
                    <div class="form-group"><span>短名稱(必填)：</span><input type="text" id="QuickSearchShortName" class="form-control" name="QuickSearchShortName"></div>
                    <input type="hidden" id="sid" />
                </div>
                <div class="modal-footer">
                	<button class="btn btn-light large" type="button" data-dismiss="modal">關 &nbsp; &nbsp;閉</button>
                	<button class="btn btn-primary large" type="button" id="saveQuickSearch">儲 &nbsp; &nbsp;存</button>
                </div>
            </div>
        </div>
      </form>
    </div>
    <header class="min">
        <div>
            <div>
                <ul class="nav d-lg-flex justify-content-lg-end">
                    <li class="nav-item"><a class="nav-link" href="/search/">首頁</a></li>
                    <li class="nav-item"><a class="nav-link" href="/search/adv/">進階搜尋</a></li>
                    <li class="nav-item"><a class="nav-link" href="/signout/">登出</a></li>
                </ul>
            </div>
            <h1>法人科專電子圖書館</h1>
            <input id="fulltext" type="text" placeholder="請輸入關鍵字">
        </div>
    </header>
    <main>
        <div role="tablist" id="accordion-1" class="mt-5">
            <div class="card">
                <div class="card-header" role="tab">
                    <h5 class="mb-0"><a data-toggle="collapse" aria-expanded="true" aria-controls="accordion-1 .item-1" href="div#accordion-1 .item-1">查詢條件</a><small></small></h5>
                </div>
                <div class="collapse show item-1" role="tabpanel" data-parent="#accordion-1">
                    <div class="card-body">
                        <p class="card-text">報告類型: 簽約計畫書</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table" id="search_result">
                <thead>
                    <tr>
                        <th>年度</th>
                        <th>計畫名稱</th>
                        <th>檢索筆數</th>
                        <th>執行單位</th>
                        <th>承辦科別</th>
                        <th>承辦人</th>
                        <th>領域</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
<?php }} ?>