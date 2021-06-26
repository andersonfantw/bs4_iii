<?php /* Smarty version Smarty-3.1.7, created on 2020-10-06 17:25:36
         compiled from "/var/www/html/bs4/view/search/advsearch.html" */ ?>
<?php /*%%SmartyHeaderCode:10441782555f5cdd66d7bf34-67881159%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bfea655e3cb634952506bac63e0ae94bf358e089' => 
    array (
      0 => '/var/www/html/bs4/view/search/advsearch.html',
      1 => 1601976321,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10441782555f5cdd66d7bf34-67881159',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f5cdd66dce88',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f5cdd66dce88')) {function content_5f5cdd66dce88($_smarty_tpl) {?><!DOCTYPE html>
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
/plugin/search/scripts/loader_advsearch.js" type="text/javascript"></script>
<script type="text/javascript">
var bs_id = 0;
var uid = 0;
var web_url = '<?php echo @WEB_URL;?>
';
</script>
</head>

<body class="advsearch">
    <div class="modal fade" role="dialog" tabindex="-1" id="Manual">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">說明</h2><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                <div class="modal-body">
									<ol>
										<li>交集查詢 - 兩個關鍵字要同時出現於電子書中，該本計畫書才會被檢索出。可使用 "&"、"AND"、"and" 來關連兩個關鍵字。ex: "5G & 雲端"</li>
										<li>連集查詢 - 兩個關鍵字其一出現於電子書中，該本計畫書才會被檢索出。可使用 "|"、"OR"、"or" 來關連兩個關鍵字。ex: "5G | 雲端"</li>
										<li>使用空格分隔關鍵字 - 將視為交集查詢。ex: "5G 雲端"，等同於 "5G & 雲端"。</li>
										<li>使用多個運算子 - 先做交集(&)查詢，再做連集(|)查詢。ex: "5G | 雲端 & 資料"，先做"雲端"和"資料"連集結果，再與"5G"做交集。</li>
										<li>使用括號 - 可使用括號設定運算的優先順序。ex: "5G | (雲端 & 資料)"，先做"雲端"和"資料"的交集，再與"5G"做連集。</li>
									</ol>
                </div>
                <div class="modal-footer">
                	<button class="btn btn-light large" type="button" data-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" id="EditQuickSearchTitle">
    	<form id="QuickSearchTitleForm" class="bv-form" onsubmit="return false;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title"><a>新增</a>快速查詢名稱</h2><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                <div class="modal-body">
                	<div class="form-group"><span>完整名稱(必填)：</span><input type="text" id="QuickSearchName" name="QuickSearchName" class="form-control" maxlength="40" data-bv-notempty required ></div>
									<div class="form-group"><span>簡稱(必填)：</span><input type="text" id="QuickSearchShortName" name="QuickSearchShortName" class="form-control" maxlength="2" data-bv-notempty required ></div>
									<input type="hidden" id="sid" />
                </div>
                <div class="modal-footer">
                	<button class="btn btn-light large" type="button" data-dismiss="modal">關閉</button>
                	<button class="btn btn-primary large" type="button" id="saveQuickSearch">儲存</button>
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
                    <li class="nav-item active"><a class="nav-link active" href="/search/adv/">進階搜尋</a></li>
                    <li class="nav-item"><a class="nav-link" href="/signout/">登出</a></li>
                </ul>
            </div>
            <h1>法人科專電子圖書館</h1>
        </div>
    </header>
    <main>
        <h1>新增搜尋條件</h1>
        <form class="form-horizontal panel" role="form">
            <div><span>電子書全文檢索：<a href="javascript:;" data-toggle="modal" data-target="#Manual">說明</a></span><input class="form-control" type="text" id="fulltext" placeholder="請輸入關鍵字"></div>
            <h4>其他條件設定</h4>
            <div class="condition">
                <div class="form-group row no-gutters my-0"><label class="col-sm-2 control-label">領域</label>
                    <div class="col-sm-10"><select class="form-control" id="pwrf" multiple><optgroup label="This is a group"><option value="12" selected="">This is item 1</option><option value="13">This is item 2</option><option value="14">This is item 3</option></optgroup></select></div>
                </div>
                <div class="form-group row no-gutters my-0"><label class="col-sm-2 control-label">報告類型</label>
                    <div class="col-sm-10"><select class="form-control" id="prt" multiple><optgroup label="This is a group"><option value="12" selected="">This is item 1</option><option value="13">This is item 2</option><option value="14">This is item 3</option></optgroup></select></div>
                </div>
                <div class="form-group row no-gutters my-0"><label class="col-sm-2 control-label">執行單位</label>
                    <div class="col-sm-10"><select class="form-control" id="pi" multiple><optgroup label="This is a group"><option value="12" selected="">This is item 1</option><option value="13">This is item 2</option><option value="14">This is item 3</option></optgroup></select></div>
                </div>
                <div class="form-group row no-gutters my-0"><label class="col-sm-2 control-label">承辦科別</label>
                    <div class="col-sm-10"><select class="form-control" id="pcu" multiple><optgroup label="This is a group"><option value="12" selected="">This is item 1</option><option value="13">This is item 2</option><option value="14">This is item 3</option></optgroup></select></div>
                </div>
                <div class="form-group row no-gutters my-0"><label class="col-sm-2 control-label">承辦人</label>
                    <div class="col-sm-10"><select class="form-control" id="pc" multiple><optgroup label="This is a group"><option value="12" selected="">This is item 1</option><option value="13">This is item 2</option><option value="14">This is item 3</option></optgroup></select></div>
                </div>
                <div class="form-group row no-gutters my-0"><label class="col-sm-2 control-label">經費類別</label>
                    <div class="col-sm-10"><select class="form-control" id="pcof" multiple><optgroup label="This is a group"><option value="12" selected="">This is item 1</option><option value="13">This is item 2</option><option value="14">This is item 3</option></optgroup></select></div>
                </div>
                <div class="form-group row no-gutters my-0"><label class="col-sm-2 control-label">計畫名稱</label>
                    <div class="col-sm-10"><input class="form-control" type="text" id="pn" placeholder="請輸入計畫名稱"></div>
                </div>
                <div class="form-group row no-gutters my-0"><label class="col-sm-2 control-label">計畫年度</label>
                		<div class="col-sm-1">從</div>
                    <div class="col-sm-4"><select class="form-control" id="year_from"><optgroup label="This is a group"><option value="12" selected="">This is item 1</option><option value="13">This is item 2</option><option value="14">This is item 3</option></optgroup></select></div>
                    <div class="col-sm-1">至</div>
                    <div class="col-sm-4"><select class="form-control" id="year_to"><optgroup label="This is a group"><option value="12" selected="">This is item 1</option><option value="13">This is item 2</option><option value="14">This is item 3</option></optgroup></select></div>
            </div>
            </div>
            <div class="buttons d-flex justify-content-center">
            	<button class="btn btn-primary large" type="button" id="reset">全部重設</button>
            	<button class="btn btn-primary large" type="button" id="save">儲存條件</button>
            	<button class="btn btn-primary large" type="button" id="submit">送出查詢</button>
            </div>
        </form>
    </main>
    <input type="hidden" id="loaded" value="0" />
</body>

</html>
<?php }} ?>