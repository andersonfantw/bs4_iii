var searchMatch = new Array();
var searchMatchPageIndexes = new Array();
var searchMatchIndexes = new Array();
var searchMatchPageHitNums = new Array();
var searchMatchPageHitKeys = new Array();
var searchResults;
var memoObj = {};
var bookmarkObj = {num: 0, tag: [], message: []};
var bookmarkMaxLim = 12;
var bookmarkLength = 70;
var bookmarkOverflowLength = 7;
var bookmarkColors = [
    'blue',
    'navy',
    'teal',
    'green',
    'lime',
    'aqua',
    'yellow',
    'red',
    'fuchsia',
    'olive',
    'purple',
    'maroon'
    ];

/**
 * 
 * jQuery Digitalmax LiveBook plugin
 * Version 1.8.5.001
 * @requires jQuery v1.11.0
 * @requires jQuery.mobile v1.4.2
 * 
 * Copyright (c) 2014 - 2015 Digitalmax Co.,Ltd.
 * 
 */

(function($) {
	$.cookie.json = true;
	
    //バージョン
    var version = '1.9.0.005-sidel';
    
    //定数定義
    var DEF_PARAMS = {
        'PAGEACTION': { //綴じ方
            'RIGHT': 1, //右綴じ
            'LEFT': 2, //左綴じ
            'UPPER': 3, //上めくり
            'LOWER': 4, //下めくり
            'RSLIDE': 5, //右スライド
            'LSLIDE': 6 //左スライド
        },
        'PAGEGROUP': { //綴じ方
            'RIGHT': 1, //右綴じ
            'LEFT': 2 //左綴じ
        },
        'SPREADMODE': { //開き方
            'SINGLE': 0, //単ページ
            'DOUBLE': 1 //見開き
        },
        'SLIDEMODE': {
            'REWIND': 0, //スライド中断（元に戻す）
            'SLIDE': 1 //スライド継続
        },
        'ORIENTATION': {
            'LANDSCAPE': 'landscape',
            'PORTRAIT': 'portrait'
        },
        'FLIPTYPE': { //めくり方
            'FLIP': 0, //めくり
            'SLIDE': 1 //スライド
        },
        'LINKTYPE': {
            'PAGE': 0,
            'URL': 1
        },
        'PATH': { //画像パスなど
            'XML': '../xml/', //XML
            'THUMBIMG': '../images/Thumbnail/' //サムネイル
        },
        'WEBCRM': {
            'URL': 'http://livebook.webcrm.jp/crmmanager/webcrm_rec.php',
            'METHOD': 'get'
        },
        'THRESHOLD': { //イベント開始の閾値
            'ZOOMIN_SCALE': 1.3,
            'ZOOMOUT_SCALE': 0.7,
            'GESTURE_ABS': 2.0
        },
        'ZOOMMOVE' : {
        	'LENGTH': 10,
        	'INTERVAL': 50
        },
        'BOOKTHICK' : {
        	'W': 44,
        	'TH': 4,
        	'BH': 7
        },
        'BOOKGUTTER' : {
        	'W': 10,
        },
        'ERROR': {
            'FATAL': 1,
            'WARNING': 2,
            'NOTICE': 4
        },
        'COMMON': {
        	'PAD0': ''
        },
        'PAGEZOOMNUM': {
        	'NORMAL': 400,
        	'ZOOM': 1000
        },
        'LANG': {}
    };
    
    //XMLなどから作成するパラメータ
    var params = {
    	'options': null,
        'pageimgprefix': '', //ページ画像の格納ディレクトリおよびページ画像ファイル名のプレフィックス
        'pageimgdir': '', //ページ画像格納ディレクトリ
        'media_ow': 0, //100%画像の幅（px）スケール1.0
        'media_oh': 0, //100%画像の高さ（px）スケール1.0
        'media_w': 0, //100%画像の幅（px）
        'media_h': 0, //100%画像の高さ（px）
        'thumb_w': 100, //サムネイル画像の幅（px）
        'thumb_h': 0, //サムネイル画像の高さ（px）
        'paper_ox': 0, //紙面枠のx座標（px）スケール1.0
        'paper_oy': 0, //紙面枠のy座標（px）スケール1.0
        'paper_x': 0, //紙面枠のx座標（px）
        'paper_y': 0, //紙面枠のy座標（px）
        'paper_ow': 0, //紙面枠の幅（px）スケール1.0
        'paper_oh': 0, //紙面枠の高さ（px）スケール1.0
        'paper_w': 0, //紙面枠の幅（px）
        'paper_h': 0, //紙面枠の高さ（px）
        'paper_cols': 2, //紙面の横方向表示数（見開き:2、単ページ:1）
        'paper_rows': 1, //紙面の縦方向表示数（1に固定）
        'paper_outer': {
        	't': 0, 'r': 0, 'b': 0, 'l': 0, 'w': 0, 'h': 0
    	},
        'zoom_x': 0, //拡大枠のx座標（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_y': 0, //拡大枠のy座標（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_w': 0, //拡大枠の幅（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_h': 0, //拡大枠の高さ（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_pw': 0, //拡大画像の幅（px）
        'zoom_ph': 0, //拡大画像の高さ（px）
        'zoom_pow': 0, //拡大開始時の拡大画像の幅（px）
        'zoom_poh': 0, //拡大開始時の拡大画像の高さ（px）
        'zoom_outer_x': 0, //拡大枠（外）のx座標（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_outer_y': 0, //拡大枠（外）のy座標（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_outer_w': 0, //拡大枠（外）の幅（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_outer_h': 0, //拡大枠（外）の高さ（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_dox': 0, //拡大移動開始時の拡大枠原点座標（px）zoom drag origin x
        'zoom_doy': 0, //拡大移動開始時の拡大枠原点座標（px）zoom drag origin y
        'zoom_dsx': 0, //拡大移動開始時のタップ座標（px）zoom drag start x ※ウィンドウの原点基準
        'zoom_dsy': 0, //拡大移動開始時のタップ座標（px）zoom drag start y ※ウィンドウの原点基準
        'zoom_dcx': 0, //拡大移動中のタップ座標（px）zoom drag current x ※ウィンドウの原点基準
        'zoom_dcy': 0, //拡大移動中のタップ座標（px）zoom drag current y ※ウィンドウの原点基準
        'zoom_outer': {
        	't': 0, 'r': 0, 'b': 0, 'l': 0, 'w': 0, 'h': 0
    	},
    	'zoom_move_d': null,
    	'zoom_move_t': -1,
        'lastpage': 0, //最終ページ数（0ベースカウント）
        'timerid': -1, //タイマーID
        'zoomlvs': [100, 200, 400, 600, 800, 1000], //拡大率（面積比）
        'basezlv': 2, //非拡大時に使用する拡大率（zoomlvsの配列インデックス）
        'basescale': 1, //非拡大時に使用する拡大率（スケール）
        'maxscale': 1,
        'anglestep': 35, //めくりアニメーションの1コマあたりの角度（90の約数）
        'flipstep': 33, //めくりアニメーションの1コマの実行間隔（ミリ秒）
        'flipdirection': '', //めくりの方向
        'flipangle': 0, //めくりアニメーションの処理中の角度
        'slidedirection': '', //スライドの方向
        'slideduration': 300, //スライドのアニメーションスピード
        'slidedelay': 300, //スライドのアニメーション実行の遅延秒数
        'win_w': 0, // window width (current)
        'win_h': 0, // window height (current)
        'win_ow': 0, // window width (original)
        'win_oh': 0, // window height (original)
        'wscale': 1, //window scale
        'pscale': 1, //page scale
        'direction': 'left',
        'swipe': {
            'dsx': 0,
            'dxy': 0,
            'dcx': 0,
            'dcy': 0
        },
        'zscale': {
            'scale': 1,
            'tscale': 1,
            'startlen': 0,
            'gesturescale': 0,
            'gestureabs': 0,
            'screenx': null,
            'screeny': null,
            'gesturex': null,
            'gesturey': null
        }, //zoom scale
        'changespreadduration': 500,
        'tindex': {
            'enabled': false,
            'loaded': false,
            'loading': false,
            'data': new Array()
        },
        'autoflip': {
        	'enabled': false,
        	'timerID': -1,
        	'dir': null,
        	'interval': 0
        },
        'help': {
        	'ow': null,
        	'oh': null,
        	'ratio': null
        },
        'memo': {
        	'enabled': false,
        	'loaded': false,
        	'tool': {
        		'created': false,
            	'content_w': 285,
            	'dialog_w': 400,
            	'dialog_h': 110
        	},
        	'list': {
        		'created': false
        	},
        	'obj': null,
        	'resizeobj': null,
        	'target': {
        		'id': null,
        		'color': null,
        		'status': null,
        		'width': null,
        		'height': null,
        		'time': null,
        		'content': null,
        		'x': 0,
        		'y': 0,
        		'page': null,
        		'dir': null
        	},
        	'default': {
        		'id': null,
        		'color': '#FFF',
        		'status': 'normal',
        		'width': '200',
        		'height': '200',
        		'time': null,
        		'content': '',
        		'x': 0,
        		'y': 0,
        		'page': null,
        		'dir': null
        	},
        	'drag': {
                'sx': 0,
                'sy': 0,
                'cx': 0,
                'cy': 0
            },
            'resizedrag': {
                'sx': 0,
                'sy': 0,
                'cx': 0,
                'cy': 0
            }
            
        },
        'vindex': {
            'enabled': false,
            'loaded': false,
            'loading': false,
            'scrolling': false,
            'moverewind': false,
            'first_w': -1,
            'last_w': -1,
            'position': -1,
            'baseposition': -1,
            'movex': -1,
            'width': -1,
            'movelimmax': -1,
            'movelimmin': -1,
            'touchsx': -1,
            'touchex': -1
        },
        'searchall': {
            'enabled': false,
            'loaded': false,
            'dataloaded': false,
            'dataloading': false,
            'dataloadcnt': 0,
            'saerch_key': null,
            'search_pnt': null,
            'sort_mode': 'hitnum',
            'match': [],
            'datapntratio': 1
        },
        'sns': {
        	'enabled': false,
        	'loaded': false,
        	'facebook': 0,
        	'twitter': 0,
        	'instagram': 0
        },
        'pdf_dl': '',
        'print': {
        	'enabled': true,
        	'dialog_w': 181,
        	'dialog_h': 117
        },
        'page_num': true,
        'usewebcrm': false, //Web-CRMログ送信をするかどうか
        'webcrm_medianame': '', //webcrm media name
        'webcrm_sessionid': '', //webcrm session id
        'webcrm_prepagenum': -1, //webcrm previous page num（直前に送ったページ数）
        'linkrect': null,
        'pageflipbutton_padding': 0, //ページめくりボタンが紙面上へ重なる幅
        'delayfuncobj': undefined,
        'delayfuncparam': []
    };
    
    //状態のパラメータ
    var status = {
        'initready': true,
        'curbasepage': 0, //現在のページ数（見開き表示中は少ないページ数のページ）
        'fliptype': DEF_PARAMS.FLIPTYPE.FLIP, //めくり方
        'flipping': false, //めくり中かどうか
        'flipcue': {
        	'length': 0, //キューの先頭配列はめくりアクションが終わると破棄されるので、連続めくり回数を別変数で管理する
        	'cue': [{}]
        },
        'autoflip': false, //自動めくり中かどうか
        'sliding': false, //スライド中かどうか
        'changespread': false, //開き方の変更アニメーション中かどうか
        'spreadmode': DEF_PARAMS.SPREADMODE.DOUBLE, //ページの開き方
        'zoommode': false, //拡大中かどうか
        'tracezoom': false, //ピンチイン・アウトによる拡大中かどうか
        'zoomdrag': false, //拡大移動中（ドラッグ）かどうか
        'zoommove': false, //拡大移動中（ボタン）かどうか
        'zoomscale': 0, //拡大率
        'taphold': false, //taphold中かどうか
        'swipe': false, //swipe中かどうか
        'swipemove': false, //swipeで移動中かどうか
        'gesture': false, //gesture中かどうか
        'zoominact': false, //zoomin action中かどうか
        'zoomoutact': false, //zoomout action中かどうか
        'vindex': false, //vindex表示中かどうか
        'tindex': false, //tindex表示中かどうか
        'print': false, //print表示中かどうか
        'searchall': false, //全文検索表示中かどうか
        'mouse': false, //オリジナルマウスカーソル表示中かどうか
        'bookmarkreceive': false, //ブックマークタグモードかどうか
        'memoreceive': false,
        'memotool': false, //メモツールが表示中かどうか
        'memolist': false, //メモリストが表示中かどうか
        'memomove': false, //メモが移動中かどうか
        'memodrag': false, //メモがドラッグ中かどうか（memomoveとセットで使用）
        'memoresize': false, //メモがリサイズ中かどうか
        'memoresizedrag': false, //メモがリサイズドラッグ中かどうか（memoresizeとセットで使用）
        'sns': false //sns表示中かどうか
    };
    
    //設定のデフォルト値
    var settings = {
        'startpage': 0, //開始ページ
        'pageaction': DEF_PARAMS.PAGEACTION.LEFT, //ページの綴じ方
        'h0_invisible': true,
        'h5_invisible': true,
        'disp_stpage_num': 0,
        'disp_stpage_cnt': 0,
        'disp_edpage_cnt': null,
        'disp_nombre': false,
        'disp_nombre_color': '#000000',
        'disp_nombre_postop': null,
        'disp_nombre_posside': null,
        'disp_nombre_fontsize': null,
        'device': null,
        'xmldir': '../xml',
        'datadir': './data',
        'pagedir': '..',
        'imgdir': './images',
        'paper_outer': {'t': 0, 'r': 0, 'b': 0, 'l': 0, 'w': 0, 'h': 0},
        'zoom_outer': {'t': 0, 'r': 0, 'b': 0, 'l': 0, 'w': 0, 'h': 0},
        'gestureabs_div': 100
    };

    //セレクタまとめ用配列
    var selectors = {
        'livebook': '#dmxlivebook',
        'hoverbutton': '.hover_button', //hover用ボタンのクラス
        'paper_frame': '#paper_frame', //紙面枠
        'pages': '#paper_frame .page', //ページ
        'leftpages': '#paper_frame .page.left', //左ページ
        'rightpages': '#paper_frame .page.right', //右ページ
        'pageimgs': '#paper_frame .page img', //ページ画像
        'linkrect': '.link_rect',
        'skinlink': '.link_skin',
        'zoom_outer': '#zoom_outer',
        'zoom_frame': '#zoom_frame',
        'zoompages': '#zoom_frame .page',
        'zoomleftpage': '#zoom_frame .page.left',
        'zoomrightpage': '#zoom_frame .page.right',
        'zoomimgs': '#zoom_frame .page img',
        'zoomleftimgz': '#zoom_frame .page.left img.z',
        'zoomrightimgz': '#zoom_frame .page.right img.z',
        'menu': '#menu', //メニュー
        'menutrigger': '#menu_trigger', //メニュー起動領域
        'leftmenu': '#leftmenubar', //左側メニュー
        'totalpagenum': '#pagenum .tp',
        'curpagenum': '#pagenum .cp',
        'tindex': '#tindex',
        'tindexlist': '#tindex li a',
        'tindexback': '#tindex .ui-header .back-btn',
        'print': '#print',
        'memotool': '#memotool',
        'memolist': '#memolist',
        'memolistline': '#memolist li',
        'memo': '.memo_area',
        'memomove': '.memo_dialog,.minimize_icon',
        'memoresize': '.memo_area .resize',
        'vindex': '#vindex',
        'vindexlist': '#vindex .thumb_wrapper a',
        'vindexfg': '#vindex .thumblist_wrapper',
        'vindexcontent': '#vindex .thumblist_wrapper .contents',
        'vindexspread': '#vindex .thumblist_wrapper .thumb_spread_wrapper',
        'vindexpage': '#vindex .thumblist_wrapper .thumb_wrapper',
        'vindexbg': '#vindex .bg',
        'vindexwrapbg': '#vindex .wrap_bg',
        'searchall': '#searchall',
        'searchalllist': '#searchall #search_result_content li.result a',
        'sns': '#sns',
        'snsbg': '#sns .bg',
        'snswrapbg': '#sns .wrap_bg',
        'changespreadmenu': '#menu .trigger_changespread',
        'vindexmenu': '#menu .trigger_vindex',
        'tindexmenu': '#menu .trigger_tindex',
        'snsmenu': '#menu .trigger_sns',
        'menuclose': '#menu .trigger_menu_close',
        'changespreadmenu': '#menu .trigger_changespread',
        'zoommovebutton': '.zoommovebutton',
        'zoommovebuttont': '.zoommovebutton.top',
        'zoommovebuttonr': '.zoommovebutton.right',
        'zoommovebuttonb': '.zoommovebutton.bottom',
        'zoommovebuttonl': '.zoommovebutton.left',
        'pageflipbutton': '.pageflipbutton',
        'pageflipbuttonl': '.pageflipbutton.left',
        'pageflipbuttonr': '.pageflipbutton.right',
        'pagenombre': '.pagenombre',
        'pagenombrel': '.pagenombre.left',
        'pagenombrer': '.pagenombre.right',
        'bookthick': '.bookthick',
        'bookthickt': '.bookthick.top',
        'bookthickr': '.bookthick.right',
        'bookthickb': '.bookthick.bottom',
        'bookthickl': '.bookthick.left',
        'bookthicklt': '.bookthick.left.top',
        'bookthicklm': '.bookthick.left.middle',
        'bookthicklb': '.bookthick.left.bottom',
        'bookthickrt': '.bookthick.right.top',
        'bookthickrm': '.bookthick.right.middle',
        'bookthickrb': '.bookthick.right.bottom',
        'bookgutter': '.bookgutter',
        'bookgutterl': '.bookgutter.left',
        'bookgutterr': '.bookgutter.right'
    };
    
    //リンク用配列
    var links = new Array();
    
    var format_lang = function () {
    	var argnum = arguments.length;
    	var valnum = argnum - 1;
    	var target = arguments[0];
    	
    	for (var i = 0; i < valnum; i++) {
    		re = new RegExp('%'+(i+1), 'g');
    		target = target.replace(re, arguments[i + 1]) ;
    	}
    	
    	return target;
    };
    
    var setpad0 = function (num) {
    	var digit = String(num).length;
    	DEF_PARAMS.COMMON.PAD0 = '';
    	
    	for (var i = 0; i < digit; i++) {
    		DEF_PARAMS.COMMON.PAD0 += '0';
    	}
    };
    
    var pad0str = function (str) {
    	var num = DEF_PARAMS.COMMON.PAD0.length;
    	
        str = new String(str);
        if (str.length < num) {
            str = DEF_PARAMS.COMMON.PAD0 + str;
            str = str.substr(str.length - num, num);
        }

        return str;
    };
    
    /**
     * 検索：ハイライト削除関数
     */
    var clearSearchResult = function () {
        searchResults = new Array();

        $("#search_result_content").empty();

        searchMatch = new Array();
        searchTxt = '';

        $('#search_keyword').val('');
        $('#search_result_info span').text('');

        $(".page .search_rect").remove();
    };
    
    var getPageLabel = function (page) {
    	var label;
    	
		if (page < settings.disp_stpage_cnt) { label = '-'; }
		else if (settings.disp_edpage_num < page) { label = '-'; }
		else { label = page + settings.disp_stpage_num - settings.disp_stpage_cnt; }
		
    	return label;
    };
    
    var getTotalPageLabel = function () {
    	var label;
    	var page = params.lastpage;
    	var adjust = 0;
    	
    	if (settings.h5_invisible && params.lastpage % 2 == 1) {
    		adjust = 1;
    	}
    	
    	label = page + settings.disp_stpage_num - settings.disp_stpage_cnt - adjust;
    	
    	return label;
    };
    
    var getGoToPageNum = function (page) {
    	return page;
    };
    
    /**
     * ハッシュソート：キーソート関数
     */
    var keySort = function (hash, sort) {
        var sortFunc = sort || "sort";
        var keys = [];
        var newHash = {};

        for (var k in hash)
            keys.push(k);

        keys[sortFunc]();

        var length = keys.length;

        for (var i = 0; i < length; i++) {
            newHash[keys[i]] = hash[keys[i]];
        }

        return newHash;
    };

    /**
     * ハッシュソート：値ソート関数
     */
    var valueSort = function (hash, sort) {
        var sortFunc = sort || "sort";
        var keys = [];
        var values = [];
        var thash = new Array;
        var newHash = {};

        for (var k in hash) {
            thash.push({key: k, val: hash[k]})
        }

        if (sortFunc == "reverse")
            thash = thash.sort(valueSort_l2s);
        else
            thash = thash.sort(valueSort_s2l);

        var length = thash.length;

        for (var i = 0; i < length; i++) {
            newHash[thash[i].key] = thash[i].val;
        }

        return newHash;
    };
    
    /**
     * ソート関数（small to large）
     */
    var valueSort_s2l = function (a, b) {
        return (a.val > b.val) ? 1 : -1;
    };

    /**
     * ソート関数（large to small）
     */
    var valueSort_l2s = function (a, b) {
        return (a.val < b.val) ? 1 : -1;
    };
    
    //めくりアニメーション：回転角からシアー角を計算する
    var rotateDeg2skewDeg = function (ovala_w, rotateDeg, sratio) {
        var ovala, ovalb, ovalx, ovaly, atanv, rad = rotateDeg * Math.PI / 180;
        var skewDeg;
        
        if (rotateDeg === 180)
        {
            skewDeg = 0;
        }
        else
        {
            ovala = Math.floor(ovala_w / 2);
            ovalb = 50;
            
            ovalx = ovala * Math.cos(rad);
            ovalx *= 1 / sratio; //x方向のscale調整によりシアー角が変わるための補正
            ovaly = ovalb * Math.sin(rad);
            
            atanv = Math.atan(ovaly / ovalx);
            skewDeg = atanv * 180 / Math.PI;
        }
        
        return skewDeg;
    };
    
    //リンク領域の調整
    var linkadjust = function () {
        var ps = arguments[0];
    	var otc = $(this).data('otc'),
	    	ota = parseFloat($(this).data('ota')),
	    	otbc = $(this).data('otbc'),
	    	otbw = $(this).data('otbw');
        var p = $(this).data('pos').split(','),
            x1 = parseInt(p[0]),
            y1 = parseInt(p[1]),
            x2 = parseInt(p[2]),
            y2 = parseInt(p[3]);
    
        var x = parseInt(x1) * ps * params.basescale;
        var y = parseInt(y1) * ps * params.basescale;

        var w = (parseInt(x2) - otbw) * ps * params.basescale;
        var h = (parseInt(y2) - otbw) * ps * params.basescale;
        
        $(this).css('left', x+'px').css('top', y+'px')
               .width(w).height(h);
    };
    
    //検索ハイライト領域の調整
    var searchrectadjust = function () {
        var ps = arguments[0];
    	var p = $(this).data('pos').split(','),
            x1 = parseInt(p[0]),
            y1 = parseInt(p[1]),
            x2 = parseInt(p[2]),
            y2 = parseInt(p[3]);
    	
        var x = parseInt(x1) / params.searchall.datapntratio * ps * params.basescale;
        var y = parseInt(y1) / params.searchall.datapntratio * ps * params.basescale;

        var w = (parseInt(x2) - parseInt(x1)) / params.searchall.datapntratio * ps * params.basescale;
        var h = (parseInt(y2) - parseInt(y1)) / params.searchall.datapntratio * ps * params.basescale;
        
        $(this).css('left', x+'px').css('top', y+'px')
               .width(w).height(h);
    };
    
    //端末の向きの状態から 見開き か 単ページ かを返す
    var get_spreadmode = function (o) {
        var spreadmode,
            orientation = !o ? $.event.special.orientationchange.orientation() : o;
        
        if (orientation === DEF_PARAMS.ORIENTATION.LANDSCAPE)
            spreadmode = DEF_PARAMS.SPREADMODE.DOUBLE;
        else if (orientation === DEF_PARAMS.ORIENTATION.PORTRAIT)
            spreadmode = DEF_PARAMS.SPREADMODE.SINGLE;
        else {
            methods._runTimeError('[Err:00003-001] 未定義の orientation です。', DEF_PARAMS.ERROR.NOTICE);
            spreadmode = DEF_PARAMS.SPREADMODE.DOUBLE;
        }
//Anderson
spreadmode = DEF_PARAMS.SPREADMODE.SINGLE;
        return spreadmode;
    };
    
    //端末の向きの状態を返す
    var get_orientation = function () {
        return $.event.special.orientationchange.orientation();
    };
    
    var get_org_paper_outer_size = function () {
        var org_ppo_w = params.media_ow * params.paper_cols;
        var org_ppo_h = params.media_oh * params.paper_rows;
        
        return {'w': org_ppo_w, 'h': org_ppo_h};
    };
    
    var get_paper_outer_size = function (win_w, win_h, paper_outer) {
        var ppo_w = win_w - paper_outer.l - paper_outer.r;
        var ppo_h = win_h - paper_outer.t - paper_outer.b;
        var ppo_t = paper_outer.t;
        var ppo_l = paper_outer.l;
        
        return {'t': ppo_t, 'l': ppo_l, 'w': ppo_w, 'h': ppo_h};
    };
    
    var get_zoom_outer_size = function (win_w, win_h, zoom_outer) {
        var zmo_w = win_w - zoom_outer.l - zoom_outer.r;
        var zmo_h = win_h - zoom_outer.t - zoom_outer.b;
        var zmo_t = zoom_outer.t;
        var zmo_l = zoom_outer.l;
        
        return {'t': zmo_t, 'l': zmo_l, 'w': zmo_w, 'h': zmo_h};
    };
    
    var get_resize_param = function (win_w, win_h, cols, rows, ppo, zmo) {
        var wsw, wsh, psw, psh, ps, posw, posh, // window scale, page scale, paper outer scale
            mdw, mdh, tmdw, tmdh, // media, temp media
            ppow, ppoh, ppox, ppoy, // paper outer
            zmow, zmoh, zmox, zmoy, // zoom outer
            ppw, pph, ppx, ppy; // paper
        
        var org_ppo = get_org_paper_outer_size();
        var cur_ppo = get_paper_outer_size(win_w, win_h, ppo);
        posw = cur_ppo.w / org_ppo.w;
        posh = cur_ppo.h / org_ppo.h;

        var cur_zmo = get_zoom_outer_size(win_w, win_h, zmo);
        
        //起動時ウィンドウと現ウィンドウサイズのスケール計算
        wsw = win_w / params.win_ow;
        wsh = win_h / params.win_oh;
        
        //紙面横幅のサイズ調整（紙面枠paper_outerより大きければフィットさせる）
        if (ppo.w < params.media_ow * posw * cols) {
            tmdw = parseInt(ppo.w / cols);
        } else {
            tmdw = params.media_ow * posw;
        }
        //ページ横幅のスケールの計算
        psw = tmdw / params.media_ow;
        
        //紙面高さのサイズ調整（紙面枠paper_outer大きければフィットさせる）
        if (ppo.h < params.media_oh * posh * rows) {
            tmdh = parseInt(ppo.h / rows);
        } else {
            tmdh = params.media_oh * posh;
        }
        //ページ高さのスケールの計算
        psh = tmdh / params.media_oh;
        
        //縦と横で小さい方を縦横共通スケールとして採用
        if (psh < psw) ps = psh;
        else ps = psw;
        
        //スケールの適用
        mdw = parseInt(params.media_ow * ps);
        mdh = parseInt(params.media_oh * ps);
        
        //紙面枠の再計算
        ppw = mdw * cols;
        pph = mdh * rows;
        
        //紙面枠の位置
        _ismobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        _rate = 1;
        if(pph >= ppw && !_ismobile){
        	//Anderson modify 20180518
					_rate = (win_w-300)/win_h * 1.5;
				}
				//Anderson
				if( _ismobile ) {
        	ppx = ppo.l + parseInt(params.paper_ox * posw + (ppo.w - ppw) / 2);
        }else{
        	ppx = ppo.l + parseInt(params.paper_ox * posw * _rate + (ppo.w - ppw * _rate) / 2);
        }
        ppy = ppo.t + parseInt(params.paper_oy * posh + (ppo.h - pph) / 2);
        

        return {'wsw': wsw, 'wsh': wsh,
                'psw': psw, 'psh': psh, 'ps': ps, 'posw': posw, 'posh': posh,
                'ppx': ppx, 'ppy': ppy,
                'ppw': ppw * _rate, 'pph': pph * _rate,
                'mdw': mdw * _rate, 'mdh': mdh * _rate};
    };
    
    //渡された座標がページの左半分か右半分どちらをクリックしたかを返す
    var get_lr_of_page = function (x, obj_x) {
        var h = Math.floor(params.media_w / 2),
            p = x - obj_x,
            s = '';
        
        if (p <= h) s = 'left';
        else s = 'right';
        
        return s;
    };
    
    //渡されたウィンドウ座標系値をページ上の座標系に変換して返す
    var get_coord_of_page = function (x, y, obj_x, obj_y) {
        var tx = x - obj_x,
            ty = y - obj_y;
        
        return {'x': tx, 'y': ty};
    };
    
    //
    var set_thumb_position_by_page = function (p) {
        var tx; //対象の見開き要素のx座標
        var ti; //対象の見開き要素のインデックス
        var li; //最後の見開き要素のインデックス
        var fw; //最初の見開き要素の幅
        var lw; //最後の見開き要素の幅
        var tw; //対象の見開き要素の幅
        
        if (settings.device != 'pc') {
	        if ($(selectors.vindexpage+'.page_'+p).length) {
	            ti = $(selectors.vindexpage+'.page_'+p).parent().index();
	            li = $(selectors.vindexspread).last().index();
	            
	            fw = 101 + 10;
	            if (!settings.h0_invisible) {
	                fw += 101;
	            }
	            
	            lw = 101 + 10;
	            if (!settings.h5_invisible) {
	                lw += 101;
	            }
	            
	            if (ti === 0) {
	                tx = 0;
	            } else {
	                tx = fw + 212 * (ti - 1);
	            }
	            
	            if (ti === 0) {
	                tw = fw;
	            } else if (ti === li) {
	                tw = lw;
	            } else {
	                tw = 212;
	            }
	            
	            params.vindex.first_w = fw;
	            params.vindex.last_w = lw;
	            params.vindex.position = params.vindex.baseposition - tx - tw / 2;
	            params.vindex.movelimmax = params.vindex.baseposition - params.vindex.first_w / 2;
	            params.vindex.movelimmin = params.vindex.baseposition - params.vindex.width + params.vindex.last_w / 2;
	            
	            $(selectors.vindexcontent).css(params.direction, params.vindex.position);
	        }
        }
    };
    
    //
    var adjust_thumb_position = function () {
        var d; //vindex.basepositionとの距離
        
        if (settings.device != 'pc') {
	        if ($(selectors.vindexpage).length) {
	            d = params.vindex.baseposition - params.vindex.position;
	            
	            params.vindex.baseposition = params.win_w / 2;
	            params.vindex.position = params.vindex.baseposition - d;
	            params.vindex.movelimmax = params.vindex.baseposition - params.vindex.first_w / 2;
	            params.vindex.movelimmin = params.vindex.baseposition - params.vindex.width + params.vindex.last_w / 2;
	            
	            $(selectors.vindexcontent).css(params.direction, params.vindex.position);
	        } else {
	            params.vindex.baseposition = params.win_w / 2;
	        }
        }
    };
    
    //Web-CRM：セッションIDの作成
    var webcrm_make_sessionid = function () {
        var date = new Date();
        
        return date.getTime();
    };
    
    //Web-CRM：起動時ログの送信
    var webcrm_send_startup = function (p) {
        
        if (params.usewebcrm) {
            $.ajax({ 
                type: DEF_PARAMS.WEBCRM.METHOD,
                url: DEF_PARAMS.WEBCRM.URL,
                data: {
                    'media_name': params.webcrm_medianame,
                    'session_id': params.webcrm_sessionid
                },
                dataType: "text",
                success: function(text, status){
                },
                error: function (r, s, e){
                },
                complete: function (r, e){
                }
            });
        }
    };
    
    //Web-CRM：ページビューログの送信
    var webcrm_send_pageview = function (p) {
        
        if (params.usewebcrm) {
            //ページ数が変わったときのみ送信する
            if (p !== params.webcrm_prepagenum) {
                params.webcrm_prepagenum = p;

                $.ajax({ 
                    type: DEF_PARAMS.WEBCRM.METHOD,
                    url: DEF_PARAMS.WEBCRM.URL,
                    data: {
                        'session_id': params.webcrm_sessionid,
                        'page_num': p
                    },
                    dataType: "text",
                    success: function(text, status){
                    },
                    error: function (r, s, e){
                    },
                    complete: function (r, e){
                    }
                });

            }
        }
    };
    
    //Web-CRM：ズームビューログの送信
    var webcrm_send_zoomview = function (p, x, y, l) {
        
        if (params.usewebcrm) {
            $.ajax({ 
                type: DEF_PARAMS.WEBCRM.METHOD,
                url: DEF_PARAMS.WEBCRM.URL,
                data: {
                    'session_id': params.webcrm_sessionid,
                    'page_num': p,
                    'point_x': x,
                    'point_y': y,
                    'zoom_level': l
                },
                dataType: "text",
                success: function(text, status){
                },
                error: function (r, s, e){
                },
                complete: function (r, e){
                }
            });
        }
    };
    
    //2点間の距離を計算する
    var get_pos2len = function (x1, y1, x2, y2) {
        return Math.sqrt(Math.pow((x2 - x1), 2) + Math.pow((y2 - y1), 2));
    };
    
    //2点間の中央の座標を計算する
    var get_centerpos = function (x1, y1, x2, y2) {
        return {'x': x1 + (x2 - x1) / 2, 'y': y1 + (y2 - y1) / 2};
    };
    
    var is_blankpage = function (p, l, h0, h5) {
        if (p == 0 && h0 == true) {
            return true;
        } else if (l % 2 == 1 && p == l && h5 == true) {
            return true;
        }
        
        return false;
    };
    
    // 右綴じ系か左綴じ系かを返す
    var get_bind_group = function (type) {
        var group_id = DEF_PARAMS.PAGEACTION.LEFT;
        
        if (!type) {
            type = settings.pageaction;
        }
        
        switch (type) {
            case DEF_PARAMS.PAGEACTION.RIGHT:
            case DEF_PARAMS.PAGEACTION.UPPER:
            case DEF_PARAMS.PAGEACTION.RSLIDE:
                group_id = DEF_PARAMS.PAGEACTION.RIGHT;
                
                break;
            case DEF_PARAMS.PAGEACTION.LEFT:
            case DEF_PARAMS.PAGEACTION.LOWER:
            case DEF_PARAMS.PAGEACTION.LSLIDE:
                group_id = DEF_PARAMS.PAGEACTION.LEFT;

                break;
        }
        
        return group_id;
    };
    
    //端末の向き変更：遅延実行用関数
    var _orientationChange = function (o, t, f) {
        if (status.zoommode === true) {
            methods.zoomoutstart.apply(o, [t]);
        }
        methods.changespread.apply(o, [t, f]);
    };
    
    //メソッド
    var methods = {
        version: function () {
            return version;
        },
        init: function(options) {
            var $this = $(this);
            
            //各設定値のセット
            params.options = options;
            settings = $.extend({}, settings, options);
            
            if (!$.mobile) {
                methods._initError('[Err:00001-003] jQuery.mobile が読み込まれていません。', DEF_PARAMS.ERROR.FATAL);
            } else {
                if ($.mobile.version !== '1.4.2') {
                    methods._initError('[Err:00001-004] jQuery.mobile の対応バージョンは 1.4.2 です。', DEF_PARAMS.ERROR.FATAL);
                }
                if (!$.mobile.support.touch) {
//                    methods._initError('[Err:00001-005] タッチイベントがサポートされていないデバイスです。', DEF_PARAMS.ERROR.FATAL);
                }
            }
            
            //@ToDo 設定値の型変換（ex. startpage -> parseInt(startpage, 10)）
            
            //該当するセレクタ数を1つに制限
            if (this.length === 0) {
                methods._initError('[Err:00001-001] 指定のセレクタを持つオブジェクトが見つかりませんでした。', DEF_PARAMS.ERROR.FATAL);
            } else if (1 < this.length) {
                methods._initError('[Err:00001-002] 指定のセレクタを持つオブジェクトが複数見つかりました。当該セレクタ数は1つとしてください。', DEF_PARAMS.ERROR.FATAL);
            } else {
                
                var data = $this.data('dmxLivebook'),
                    livebook = $('<div>');

                //データの初期化
                if (!data) {
                    
                    //非拡大時に使用する拡大率（スケール）
                    params.basescale = Math.sqrt(params.zoomlvs[params.basezlv] / 100);
                    
                    //最大拡大時に使用する拡大率（スケール）※100%画像に対するスケール
                    params.maxscale = Math.sqrt(10);
                    
                    //Build.xmlの読み込みと値のセット
                    methods.loadparams.apply($this);
                    
                    //ページ数表示に使用する0埋めの初期設定
                    setpad0(getPageLabel(params.lastpage));
                    
                    //startpage のチェック
                    if (isNaN(settings.startpage)) {
                    	settings.startpage = 0;
                    }
                    //startpage が 0 より小さい場合
                    if (settings.startpage < 0)
                        settings.startpage = 0;
                    
                    //startpage が lastpage より大きい場合
                    if (params.lastpage < settings.startpage)
                        settings.startpage = params.lastpage;
                    
                    status.spreadmode = get_spreadmode();
                    if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
                    {
                        params.paper_ow = params.media_ow * 2;
                        params.paper_oh = params.media_oh;
                        params.paper_w = params.paper_ow * params.scale;
                        params.paper_h = params.media_oh * params.scale;
                        status.curbasepage = settings.startpage = settings.startpage - settings.startpage % 2;
                    }
                    else //単ページの場合
                    {
                        params.paper_ow = params.media_ow;
                        params.paper_oh = params.media_oh;
                        params.paper_w = params.paper_ow * params.scale;
                        params.paper_h = params.paper_oh * params.scale;
                        status.curbasepage = settings.startpage;
                    }

                    $(this).data('dmxLivebook', {
                        target: $this,
                        livebook: livebook
                    });
                    data = $this.data('dmxLivebook');
                }
                
                if (status.initready) {
                    params.win_ow = $(window).innerWidth();
                    params.win_oh = $(window).innerHeight();
                    
                    settings.paper_outer.w = params.win_ow - settings.paper_outer.l - settings.paper_outer.r;
                    settings.paper_outer.h = params.win_oh - settings.paper_outer.t - settings.paper_outer.b;
                    settings.zoom_outer = settings.paper_outer;
                    params.paper_outer = settings.paper_outer;
                    params.zoom_outer = settings.zoom_outer;
                    
                    if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
                        params.paper_cols = 2;
                    } else if (status.spreadmode === DEF_PARAMS.SPREADMODE.SINGLE) {
                        params.paper_cols = 1;
                    }
                    
                    //Web-CRM：起動時ログの送信
                    webcrm_send_startup();
                    
                    methods.create.apply($this);
                
                    methods.resize.apply($this, [data.target]);
                    $(window).bind("resize", {'thisobj': $this}, methods.resizeHandler);
                    $(window).bind('orientationchange', {'thisobj': $this}, methods.orientationchangeHandler);
                    
                    if (params.memo.list.created === false) {
                        methods.creatememolistpage.apply($this, [data.target]);
                        methods.resize.apply($this, [data.target]);
                    }
                    
                    methods.loadbookmarktag();
                    methods.loadmemo();
                }
            }
            
            return this;
        },
        _errorThrow: function() {
            $.error(arguments[0]);
            if (arguments[1] === DEF_PARAMS.ERROR.FATAL) alert(arguments[0]);
        },
        _initError: function() {
            status.initready = false;
            methods._errorThrow(arguments[0], arguments[1]);
        },
        _runTimeError: function() {
            methods._errorThrow(arguments[0], arguments[1]);
        },
        loadparams: function() {
            
            $.ajax({ 
                type: "GET", 
                url: settings.xmldir+'/Build.xml',
                dataType: "xml",
                async: false,
                success: function(xml, status){
                    if(status === 'success') {
                        params.pageimgprefix = $(xml).find('ProjectName').text();
                        params.pageimgdir = settings.pagedir+'/'+params.pageimgprefix+'__dmx/';
                        params.media_ow = parseInt(parseInt($(xml).find('MediaWidth').text()) * params.basescale);
                        params.media_oh = parseInt(parseInt($(xml).find('MediaHeight').text()) * params.basescale);
                        params.media_w = params.media_ow * params.scale; 
                        params.media_h = params.media_oh * params.scale; 
                        params.lastpage = parseInt($(xml).find('LastPageNumber').text());
                        params.webcrm_medianame = $(xml).find('mediaID').text();
                        
                        params.thumb_h = Math.floor(params.thumb_w / params.media_ow * params.media_oh);
                        
                        if (params.webcrm_medianame !== '') {
                            params.usewebcrm = true;
                            params.webcrm_sessionid = webcrm_make_sessionid();
                        }
                        
                    } else {
                        methods._initError('[Err:00002-002]  Build.xml データの読み込みに失敗しました。', DEF_PARAMS.ERROR.FATAL);
                    }
                },
                error: function (r, s, e){
                    methods._initError('[Err:00002-001] Build.xml ファイルの読み込みに失敗しました。', DEF_PARAMS.ERROR.FATAL);
                },
                complete: function (r, e){}
            });
            
            $.ajax({ 
                type: "GET", 
                url: settings.xmldir+'/EBookSetBaseParam.xml',
                dataType: "xml",
                async: false,
                success: function(xml, status){
                    if(status === 'success') {
                    	if (params.options.hasOwnProperty('h0_invisible')) {
                    		settings.h0_invisible = params.options.h0_invisible == true ? true : false;
                    	} else {
                            settings.h0_invisible = $(xml).find('PageActionCover').text().toLowerCase() == 'true' ? true : false;
                    	}
                    	if (params.options.hasOwnProperty('h5_invisible')) {
                    		settings.h5_invisible = params.options.h5_invisible == true ? true : false;
                    	} else {
                        	settings.h5_invisible = $(xml).find('PageActionEndCover').text().toLowerCase() == 'true' ? true : false;
                    	}
                        settings.pageaction = parseInt($(xml).find('SETEnvPage').find('PageAction').text());
                        
                        settings.disp_stpage_num = parseInt($(xml).find('SETEnvPage').find('PageDispStartPage').text());
                        settings.disp_stpage_cnt = parseInt($(xml).find('SETEnvPage').find('PageDispStartCont').text());
                        if ($(xml).find('SETEnvPage').find('PageDispLastPage').length) {
                            settings.disp_edpage_num = parseInt($(xml).find('SETEnvPage').find('PageDispLastPage').text());
                        }
                        if (settings.disp_edpage_num == undefined || isNaN(settings.disp_edpage_num)) {
                            settings.disp_edpage_num = params.lastpage - settings.disp_stpage_num + settings.disp_stpage_cnt;
                        }
                        
                        params.autoflip.enabled = $(xml).find('PageTimmingAutoPage').text().toLowerCase() == 'true' ? true : false;
                        params.autoflip.interval = parseInt($(xml).find('SETEnvPage').find('PageTimmingAutoPageSec').text()) * 1000;
                        
                        settings.disp_nombre = $(xml).find('PageDisp').text().toLowerCase() == 'true' ? true : false;
                        settings.disp_nombre_color = '#'+$(xml).find('PageDispColor').text();
                        settings.disp_nombre_postop = parseInt($(xml).find('PageDispPageTop').text());
                        settings.disp_nombre_posside = parseInt($(xml).find('PageDispPageSide').text());
                        settings.disp_nombre_fontsize = parseInt($(xml).find('PageDispFontSize').text());
                        
                        var scale_flg;
                        for (var i = params.zoomlvs.length; 1 < i; i--) {
                        	scale_flg = $(xml).find('ZoomZoomScale0'+i).text().toLowerCase() == 'true' ? true : false;
                        	if (scale_flg) {
                        		params.searchall.datapntratio = Math.sqrt(params.zoomlvs[i - 1] / 100);
                        		break;
                        	}
                        }
                        
                        if (get_bind_group() === DEF_PARAMS.PAGEGROUP.RIGHT) {
                            params.direction = 'right';
                        } else {
                            params.direction = 'left';
                        }
                    } else {
                        methods._initError('[Err:00002-007] EBookSetBaseParam.xml データの読み込みに失敗しました。', DEF_PARAMS.ERROR.FATAL);
                    }
                },
                error: function (r, s, e){
                    methods._initError('[Err:00002-008] EBookSetBaseParam.xml ファイルの読み込みに失敗しました。', DEF_PARAMS.ERROR.FATAL);
                },
                complete: function (r, e){}
            });
            
            $.ajax({ 
                type: "GET", 
                url: settings.datadir+'/params.json',
                dataType: "json",
                async: false,
                success: function(json, status){
                    if(status === 'success') {
                    	if (json.hasOwnProperty('sns')) {
                    		params.sns.facebook = parseInt(json.sns.facebook);
                    		params.sns.twitter = parseInt(json.sns.twitter);
                    		params.sns.instagram = parseInt(json.sns.instagram);
                    		if (params.sns.facebook == 1 || params.sns.twitter == 1 || params.sns.instagram == 1) {
                    			params.sns.enabled = true;
                    		}
                    	}
                    	if (json.hasOwnProperty('tindex')) {
                    		params.tindex.enabled = parseInt(json.tindex);
                    		
                    		if (params.tindex == 0) {
                    			params.tindex.enabled = false;
                    		}
                    	}
                    	if (json.hasOwnProperty('h0_invisible')) {
                    		json.h0_invisible = parseInt(json.h0_invisible);
                    		
                    		if (json.h0_invisible == 1) {
                    			settings.h0_invisible = true;
                    		} else if (json.h0_invisible == 0) {
                    			settings.h0_invisible = false;
                    		}
                    	}
                    	if (json.hasOwnProperty('pdf_dl') && json.pdf_dl != '') {
                    		params.pdf_dl = json.pdf_dl;
                    	}
                    	if (json.hasOwnProperty('print')) {
                    		params.print.enabled = json.print == false ? false : true;
                    	}
                    	if (json.hasOwnProperty('page_num')) {
                    		params.page_num = json.page_num == false ? false : true;
                    	}
                    	if (json.hasOwnProperty('bind_dir')) {
                    		params.direction = json.bind_dir == 'right' ? 'right' : 'left';
                    	}
                    } else {
                        methods._initError('[Err:00002-006]  params.json データの読み込みに失敗しました。', DEF_PARAMS.ERROR.FATAL);
                    }
                },
                error: function (r, s, e){
                    methods._initError('[Err:00002-005] params.json ファイルの読み込みに失敗しました。', DEF_PARAMS.ERROR.FATAL);
                },
                complete: function (r, e){}
            });
            
            $.ajax({ 
                type: "GET", 
                url: settings.datadir+'/lang.json',
                dataType: "json",
                async: false,
                success: function(json, status){
                    if(status === 'success') {
                    	DEF_PARAMS.LANG.SEARCHALL = {
                			'BRANCH': json.lang.searchall.branch,
                			'RESULT': json.lang.searchall.result,
                			'HIT': json.lang.searchall.hit,
                			'NOHIT': json.lang.searchall.nohit,
                			'SORTHIT': json.lang.searchall.sorthit,
                			'SORTPAGE': json.lang.searchall.sortpage,
                			'RESET': json.lang.searchall.reset,
                			'SEARCH': json.lang.searchall.search
                    	};
                		
                		DEF_PARAMS.LANG.PRINT = {
            				'LEFT': json.lang.print.left,
            				'RIGHT': json.lang.print.right
                		};
                		
                		DEF_PARAMS.LANG.MEMO = {
            				'MEMOLIST': json.lang.memo.memolist,
            				'DEFAULTCONTENT': json.lang.memo.defaultcontent
                		};
                		params.memo.default.content = DEF_PARAMS.LANG.MEMO.DEFAULTCONTENT;
                    } else {
                        methods._initError('[Err:00002-010]  lang.json データの読み込みに失敗しました。', DEF_PARAMS.ERROR.FATAL);
                    }
                },
                error: function (r, s, e){
                    methods._initError('[Err:00002-009] lang.json ファイルの読み込みに失敗しました。', DEF_PARAMS.ERROR.FATAL);
                },
                complete: function (r, e){}
            });
        },
        flipcounttype: function () {
        	var d = arguments[0]; // direction
        	var c; // count plus or minus
        	
            // めくり方向の判定
            switch (settings.pageaction) {
                case DEF_PARAMS.PAGEACTION.RIGHT:
                case DEF_PARAMS.PAGEACTION.UPPER:
                case DEF_PARAMS.PAGEACTION.RSLIDE:
            		
                	if (d == 'left' || d == 'r2l') {
                		c = 'plus'
                	} else {
                		c = 'minus';
                	}
                	
                    break;
                case DEF_PARAMS.PAGEACTION.LEFT:
                case DEF_PARAMS.PAGEACTION.LOWER:
                case DEF_PARAMS.PAGEACTION.LSLIDE:

                	if (d == 'left' || d == 'r2l') {
                		c = 'minus';
                	} else {
                		c = 'plus'
                	}
                	
                    break;
            }
            
            return c;
        },
        flipactiontype: function () {
        	var a; // action type
        	
            // めくり方向の判定
            switch (settings.pageaction) {
                case DEF_PARAMS.PAGEACTION.RIGHT:
                case DEF_PARAMS.PAGEACTION.UPPER:
                case DEF_PARAMS.PAGEACTION.LEFT:
                case DEF_PARAMS.PAGEACTION.LOWER:
            		
                	a = 'flip';
                	
                    break;
                case DEF_PARAMS.PAGEACTION.RSLIDE:
                case DEF_PARAMS.PAGEACTION.LSLIDE:

                	a = 'slide';
                	
                    break;
            }
            
            return a;
        },
        loadtindexdata: function() {
            var t = arguments[0]; //target
            
            $.ajax({ 
                type: "GET", 
                url: settings.xmldir+'/EBookIndexTextParam.xml',
                dataType: "xml",
                async: false,
                success: function(xml, status){
                    if(status === 'success') {
                        
                        if (params.tindex.loading)
                            $.mobile.loading('hide');
                        
                        $(xml).find('IndexText').each(function(){
                            params.tindex.data.push({
                                'page': parseInt($(this).attr('PageNo')),
                                'text': $('IndexTextData', this).text()
                            });
                        });
                        
                        if (0 < params.tindex.data.length) {
                            params.tindex.enabled = true;
                            params.tindex.loaded = true;
                            methods.createtindexlist.apply(t, [t, params.tindex.data]);
                        }
                        
                    } else {
                        methods._initError('[Err:00002-004]  EBookIndexTextParam.xml データの読み込みに失敗しました。', DEF_PARAMS.ERROR.FATAL);
                    }
                },
                error: function (r, s, e){
                    methods._initError('[Err:00002-003] EBookIndexTextParam.xml ファイルの読み込みに失敗しました。', DEF_PARAMS.ERROR.FATAL);
                },
                complete: function (r, e){}
            });
        },
        destroy: function() {

            var $this = $(this),
                data = $this.data('dmxLivebook');

            data.livebook.remove();
            $this.removeData('dmxLivebook');
            
            return this;
        },
        create: function() {

            var $this = $(this),
                data = $this.data('dmxLivebook');

            var p = settings.startpage;

            var flipbutton_dmxtap_fnc,
                zoomin_gesture_fnc, zoomout_gesture_fnc,
                pczoomin_gesture_fnc, pczoomout_gesture_fnc,
                triggermenu_swipe_fnc, indexlist_gotopage_fnc,
                swipestartHandler, swipechangeHandler, swipeendHandler,
                pcswipestartHandler, pcswipechangeHandler, pcswipeendHandler,
                gesturestartHandler, gesturechangeHandler, gestureendHandler,
                on_no_window_scroll, off_no_window_scroll;
        
            //紙面を格納するタグ（紙面枠）の追加
            data.target.html('<div id="paper_outer"><div id="paper_frame"></div></div><div id="zoom_outer" style="display: none;"><div id="zoom_frame"></div></div>');
            
            //目次の追加
            methods.createtindexpage.apply($this, [data.target]);
            
            //ビジュアル目次の追加
            methods.createvindexpage.apply($this, [data.target]);
            
            //全文検索の追加
            if (settings.device == 'pc') {
            	methods.createsearchallpage.apply($this, [data.target]);
            }
            
            //印刷の追加
            if (settings.device == 'pc') {
                methods.createprintpage.apply($this, [data.target]);
            }
            
            //ノンブルの追加
            if (settings.device == 'pc' && settings.disp_nombre) {
                methods.createnombre.apply($this, [data.target]);
            }
            
            //SNSの追加
            if (params.sns.enabled) {
            	methods.createsnspage.apply($this, [data.target]);
            }
            
            //メニューの追加
            //methods.createmenu.apply($this, [data.target]);
            //Anderson 20180816
            methods.createmenu.apply($this, [$('body')]);
            
            //カスタムメニューの追加
            methods.createcustommenu.apply($this, [data.target]);
            
            //ページめくりボタンの追加
            //Anderson 20180816
            methods.createpageflipbutton.apply($this, [data.target]);
            
            //紙面の厚み追加
//        	methods.createbookthick.apply($this, [data.target]);
            
        	//紙面ののど追加
//        	methods.createbookgutter.apply($this, [data.target]);
            
        	//拡大時移動ボタンの追加
//            methods.createzoommovebutton.apply($this, [data.target]);
            
            //初期ページの追加
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
            {
                if (0 < settings.startpage) { //開始ページが0のときは前のページは不要
                    methods.addpage.apply($this, [$(selectors.paper_frame), p - 2, DEF_PARAMS.PAGEZOOMNUM.NORMAL]); //前のページ
                    methods.addpage.apply($this, [$(selectors.paper_frame), p - 1, DEF_PARAMS.PAGEZOOMNUM.NORMAL]); //前のページ
                }

                methods.addpage.apply($this, [$(selectors.paper_frame), p, DEF_PARAMS.PAGEZOOMNUM.NORMAL]); //開始ページ
                methods.addpage.apply($this, [$(selectors.paper_frame), p + 1, DEF_PARAMS.PAGEZOOMNUM.NORMAL]); //開始ページ
                methods.addpage.apply($this, [$(selectors.paper_frame), p + 2, DEF_PARAMS.PAGEZOOMNUM.NORMAL]); //後のページ
                methods.addpage.apply($this, [$(selectors.paper_frame), p + 3, DEF_PARAMS.PAGEZOOMNUM.NORMAL]); //後のページ
            
                methods.loadlinkdata.apply($(this), [data.target, p]); //リンクの読み込み
                methods.loadlinkdata.apply($(this), [data.target, p + 1]); //リンクの読み込み
                
                $(selectors.pageflipbutton).show();
                $(selectors.bookthick).show();
                $(selectors.bookgutter).show();
            }
            else //単ページの場合
            {
                if (settings.startpage == 0 && settings.h0_invisible) {
                    settings.startpage += 1;
                    status.curbasepage = p = settings.startpage;
                }
                
                if (0 < settings.startpage) //開始ページが0のときは前のページは不要
                    methods.addpage.apply($this, [$(selectors.paper_frame), p - 1, DEF_PARAMS.PAGEZOOMNUM.NORMAL]); //前のページ

                methods.addpage.apply($this, [$(selectors.paper_frame), p, DEF_PARAMS.PAGEZOOMNUM.NORMAL]); //開始ページ
                methods.addpage.apply($this, [$(selectors.paper_frame), p + 1, DEF_PARAMS.PAGEZOOMNUM.NORMAL]); //後のページ
                
                methods.loadlinkdata.apply($(this), [data.target, p]); //リンクの読み込み
                
                $(selectors.pageflipbutton).hide();
                $(selectors.bookthick).hide();
                $(selectors.bookgutter).hide();
            }

            $(selectors.paper_frame, data.target).css({'width': params.paper_w+'px', 'height': params.media_h+'px'}); //紙面枠のサイズ設定
            $(selectors.pageimgs, data.target).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
            $(selectors.rightpages, data.target).css({'left': params.media_w+'px'}); //右ページの移動
            
            //タップ制御のための独自イベント
            $(data.target).on('dmxtaps', selectors.pageimgs, function(e){
            });
            
            flipbutton_dmxtap_fnc = function(e){
            	if (status.tracezoom == true) {
            		methods.tracezoomterminate.apply($this, [data.target]);
            	} else if (status.zoommode == true) {
            		methods.zoomoutstart.apply($this, [data.target]);
            	}
                if (status.changespread === false) {
                    var d = 'left';
                    if ($(e.target).hasClass('right')) {
                        d = 'right';
                    };
                    
                    var c = methods.flipcounttype.apply($this, [d]); // ページ数の増減タイプ plus or minus
                    
                    if (status.flipping === false && status.sliding === false) {
	                    if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
	                    	if (c == 'minus') {
	                    		p = status.curbasepage - 2;
	                    	} else {
	                    		p = status.curbasepage + 2;
	                    	}
                    		if (0 <= p && p <= params.lastpage) {
		                    	if (d == 'left') {
		                    		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'slidetype': 'overlay', 'counttype': c, 'flipangle': 0, 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
		                    		status.flipcue.length++;
		                    		methods.gotoleft.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
		                    	} else {
		                    		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'slidetype': 'overlay', 'counttype': c, 'flipangle': 0, 'direction': 'r2l', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
		                    		status.flipcue.length++;
		                    		methods.gotoright.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
		                    	}
                    		}
	                    } else {
	                    	if (c == 'minus') {
	                    		p = status.curbasepage - 1;
	                    	} else {
	                    		p = status.curbasepage + 1;
	                    	}
	                    	if (1 <= p && p <= params.lastpage - 1) {
		                    	if (d == 'left') {
		                    		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'slidetype': 'overlay', 'counttype': c, 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
		                    		status.flipcue.length++;
		                    		methods.gotoleft.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
		                    	} else {
		                    		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'slidetype': 'overlay', 'counttype': c, 'direction': 'r2l', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
		                    		status.flipcue.length++;
		                    		methods.gotoright.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
		                    	}
	                    	}
	                    }
                    } else {
	                    if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
	                    	if (c == 'minus') {
	                    		p = status.curbasepage - status.flipcue.length * 2;
	                    	} else {
	                    		p = status.curbasepage + status.flipcue.length * 2;
	                    	}
                    		if (0 <= p && p <= params.lastpage) {
		                    	if (d == 'left') {
		                    		status.flipcue.cue.push({'basepage': p, 'headcue': false, 'flipangle': 0, 'slidetype': 'overlay', 'counttype': c, 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
		                    		status.flipcue.length++;
		                    		methods.gotoleft.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
		                    	} else {
	                        		status.flipcue.cue.push({'basepage': p, 'headcue': false, 'flipangle': 0, 'slidetype': 'overlay', 'counttype': c, 'direction': 'r2l', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
	                        		status.flipcue.length++;
	                        		methods.gotoright.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
		                    	}
                    		}
	                    } else {
	                    	if (c == 'minus') {
	                    		p = status.curbasepage - status.flipcue.length;
	                    	} else {
	                    		p = status.curbasepage + status.flipcue.length;
	                    	}
                    		if (1 <= p && p <= params.lastpage - 1) {
		                    	if (d == 'left') {
	                    			status.flipcue.cue.push({'basepage': p, 'headcue': false, 'slidetype': 'overlay', 'counttype': c, 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
		                    		status.flipcue.length++;
		                    		methods.gotoleft.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
		                    	} else {
	                    			status.flipcue.cue.push({'basepage': p, 'headcue': false, 'slidetype': 'overlay', 'counttype': c, 'direction': 'r2l', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
	                        		status.flipcue.length++;
	                        		methods.gotoright.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
		                    	}
                    		}
	                    }
                    }
                }
            };
            //シングルタップ
            if (settings.device == 'pc') {
                $(data.target).on('click', selectors.pageflipbuttonl, flipbutton_dmxtap_fnc);
                $(data.target).on('click', selectors.pageflipbuttonr, flipbutton_dmxtap_fnc);
            } else {
                $(data.target).on('dmxtap', selectors.pageflipbuttonl, flipbutton_dmxtap_fnc);
                $(data.target).on('dmxtap', selectors.pageflipbuttonr, flipbutton_dmxtap_fnc);
            }
            
            //拡大(PC)
            pczoomin_gesture_fnc = function (e, originalEvent) {
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                		(status.zoommode === false || (status.zoommode === true && params.zscale.scale < params.maxscale))) {
                    var center = {'x': originalEvent.pageX, 'y': originalEvent.pageY};
                    var o = $(e.target).parent('div');
                    var p = parseInt(o.attr('rel'));
                    var c = get_coord_of_page(center.x, center.y, o.offset().left, o.offset().top);
                    
                    if (status.zoommode === false) {
	                    params.zscale.page = p;
	                    params.zscale.pagex = c.x;
	                    params.zscale.pagey = c.y;
	                    
	                    params.zoom_pow = params.media_w;
	                    params.zoom_poh = params.media_h;
                    } else {
	                    params.zoom_pow = params.zoom_pw;
	                    params.zoom_poh = params.zoom_ph;
                    }
                    
                    status.zoominact = true;
                    methods.zoominstart.apply($this, [data.target, p, c.x, c.y]); //現在の表示比率を100%とした座標を渡す（実際の紙面上での座標は変換が必要）
                }
            };
            pczoomout_gesture_fnc = function () {
                if (status.flipping === false && status.sliding === false && status.changespread === false && status.zoommode === true && params.zscale.scale == params.maxscale) {
                    status.zoomoutact = true;
                    methods.zoomoutstart.apply($this, [data.target]);
                }
            };
            
            //拡大(タッチデバイス)
            zoomin_gesture_fnc = function (e, touches) {
            	var center, o, p, c;
            	
                if (status.flipping === false && status.sliding === false && status.changespread === false && status.zoommode === false) {
                	if (params.zscale.screenx == null && params.zscale.screeny == null &&
                			params.zscale.page == null && params.zscale.pagex == null && params.zscale.pagey == null) {
                		
                		center = get_centerpos(touches[0].pageX, touches[0].pageY, touches[1].pageX, touches[1].pageY); //原点（画面左上）からの座標（スクリーン座標）
                		params.zscale.screenx = center.x;
                		params.zscale.screeny = center.y;
                		
	                    o = $(e.target).parent('div');
	                    p = parseInt(o.attr('rel'));
	                    c = get_coord_of_page(params.zscale.screenx, params.zscale.screeny, o.offset().left, o.offset().top);
	                    
	                    params.zscale.page = p;
	                    params.zscale.pagex = c.x;
	                    params.zscale.pagey = c.y;
	                    
	                    params.zoom_pow = params.media_w;
	                    params.zoom_poh = params.media_h;
                    }
                    status.zoominact = true;
                    status.zoomoutact = true;
                    methods.tracezoomstart.apply($this, [data.target, params.zscale.page, params.zscale.pagex, params.zscale.pagey]); //現在の表示比率を100%とした座標を渡す（実際の紙面上での座標は変換が必要）
                }
            };
            zoomchange_gesture_fnc = function (e, touches) {
            	var center, o, p, c;
            	
                if (status.flipping === false && status.sliding === false && status.changespread === false && status.zoommode === true) {
                	if (params.zscale.screenx == null && params.zscale.screeny == null) {
                		center = get_centerpos(touches[0].pageX, touches[0].pageY, touches[1].pageX, touches[1].pageY); //原点（画面左上）からの座標（スクリーン座標）
                		params.zscale.screenx = center.x;
                		params.zscale.screeny = center.y;
                	}
                	
                	if (params.zscale.page == null && params.zscale.pagex == null && params.zscale.pagey == null) {
	            		o = $(e.target).parent('div');
	                    p = parseInt(o.attr('rel'));
	                    c = get_coord_of_page(params.zscale.screenx, params.zscale.screeny, o.offset().left, o.offset().top);
	                    
	                    params.zscale.page = p;
	                    params.zscale.pagex = c.x;
	                    params.zscale.pagey = c.y;
	                    
	                    params.zoom_pow = params.zoom_pw;
	                    params.zoom_poh = params.zoom_ph;
                	}
                    
                    status.zoominact = true;
                    status.zoomoutact = true;
                	methods.tracezoomchange.apply($this, [data.target, params.zscale.page, params.zscale.pagex, params.zscale.pagey]); //現在の表示比率を100%とした座標を渡す（実際の紙面上での座標は変換が必要）
                }
            };
            zoomout_gesture_fnc = function () {
                if (status.flipping === false && status.sliding === false && status.changespread === false && status.zoommode === true) {
                    status.zoomoutact = true;
                    methods.zoomoutstart.apply($this, [data.target]);
                }
            };
            
            //スワイプ(PC)
            pcswipestartHandler = function(e, originalEvent) {
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === false && status.zoomdrag == false && status.bookmarkreceive === false) {
                    var x = originalEvent.pageX,
                        y = originalEvent.pageY;
                    
                    methods.slidedragstart.apply($this, [data.target, x, y]);
                }
            };
            pcswipechangeHandler = function(e, originalEvent) {
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === false && status.zoomdrag == false && status.bookmarkreceive === false) {
                    var x = originalEvent.pageX,
                        y = originalEvent.pageY;
                        
                    methods.slidedragmove.apply($this, [data.target, x, y]);
                }
            };
            pcswipeendHandler = function() {
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === false && status.zoomdrag == false && status.bookmarkreceive === false) {
                    methods.slidedragterminate.apply($this, [data.target]);
                }
            };
            
            
            //スワイプ
            swipestartHandler = function(e, touches) {
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === false && status.zoomdrag == false) {
                    var x = touches[0].pageX,
                        y = touches[0].pageY;
                    
                    methods.slidedragstart.apply($this, [data.target, x, y]);
                }
            };
            swipechangeHandler = function(e, touches) {
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === false && status.zoomdrag == false) {
                    var x = touches[0].pageX,
                        y = touches[0].pageY;
                        
                    methods.slidedragmove.apply($this, [data.target, x, y]);
                }
            };
            swipeendHandler = function() {
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === false && status.zoomdrag == false) {
                    methods.slidedragterminate.apply($this, [data.target]);
                }
            };
            
            //ジェスチャーハンドラ
            gesturestartHandler = function(e, touches) {
                if (status.swipe) {
                    params.swipe.dsx = params.swipe.dsy = 0;
                    params.swipe.dcx = params.swipe.dcy = 1;
                    methods.slidedragterminate.apply($this, [data.target]);
                }
                status.gesture = true;
                params.zscale.startlen = get_pos2len(touches[0].pageX, touches[0].pageY, touches[1].pageX, touches[1].pageY);
            };
            gesturechangeHandler = function(e, touches) {
                var curlen = get_pos2len(touches[0].pageX, touches[0].pageY, touches[1].pageX, touches[1].pageY);
                params.zscale.gesturescale = curlen / params.zscale.startlen;
                params.zscale.gestureabs = curlen - params.zscale.startlen;
                
                if (settings.device == 'pc') {
	                if (status.zoominact === false && status.zoomoutact === false) {
	                    if (status.zoommode === false) {
	                        if (DEF_PARAMS.THRESHOLD.ZOOMIN_SCALE <= params.zscale.gesturescale) {
	                            zoomin_gesture_fnc(e, touches);
	                        }
	                    } else {
	                        if (params.zscale.gesturescale <= DEF_PARAMS.THRESHOLD.ZOOMOUT_SCALE) {
	                            zoomout_gesture_fnc();
	                        }
	                    }
	                }
                } else {
                	if (status.zoominact === false && status.zoomoutact === false && status.zoommode === false) {
                		if (DEF_PARAMS.THRESHOLD.GESTURE_ABS <= params.zscale.gestureabs) { //Math.abs()は使用しない（縮小を無効にする）
                			zoomin_gesture_fnc(e, touches);
                		}
                	} else if (status.zoommode === true) {
                		if (DEF_PARAMS.THRESHOLD.GESTURE_ABS <= Math.abs(params.zscale.gestureabs)) {
                			zoomchange_gesture_fnc(e, touches);
                		}
                	}
                }
            };
            gestureendHandler = function() {
                status.gesture = false;
                params.zscale.startlen = 0;
                
                if (status.zoommode === true) {
                	methods.tracezoomactionterminate.apply($this, [data.target]);
                }
            };
            
            
            //PC メモの移動
            $(data.target).on('mousedown', selectors.memomove, function(e){
            	if ($(e.target).hasClass('memo_dialog') || $(e.target).hasClass('minimize_icon')) {
	                if (!status.memomove) {
	                	status.memomove = true;
	                	methods.memomovestart(data.target, e, e.originalEvent);
	                }
            		
            	}
            });
            $(data.target).on('mousemove', selectors.paper_frame, function(e){
                if (status.memomove) {
                	methods.memomovechange(data.target, e, e.originalEvent);
                }
            });
            $(data.target).on('mouseup', selectors.paper_frame, function(e){
            	if ($(e.target).hasClass('minimize_icon') && params.memo.drag.sx == params.memo.drag.cx && params.memo.drag.sy == params.memo.drag.cy) {
            		methods.normalizememo.apply(data.target, [data.target, $(e.target).parents('.memo_area')]);
            		methods.adjustmemo();
            	}
        	    if (status.memomove) {
                	status.memomove = false;
                	methods.memomoveend(data.target, e, e.originalEvent);
                }
            });
            
            //PC メモのリサイズ
            $(data.target).on('mousedown', selectors.memoresize, function(e){
        	    if (!status.memoresize) {
                	status.memoresize = true;
                	methods.memoresizestart(data.target, e, e.originalEvent);
                }
            });
            $(data.target).on('mousemove', selectors.paper_frame, function(e){
        	    if (status.memoresize) {
                	methods.memoresizechange(data.target, e, e.originalEvent);
                }
        	});
            $(data.target).on('mouseup', selectors.paper_frame, function(e){
                if (status.memoresize) {
                	status.memoresize = false;
                	methods.memoresizeend(data.target, e, e.originalEvent);
                }
            });
            
            
            //PC
            $(data.target).on('mousedown', selectors.pageimgs, function(e){
                if (!status.swipe && !status.gesture) {
                	pcswipestartHandler(e, e.originalEvent);
                }
                
                //Android用 Swipe時に touchmove イベントがキャンセルされる現象の対策
                e.preventDefault();
            });
            $(data.target).on('mousemove', selectors.pageimgs, function(e){
                if (status.swipe) {
                	pcswipechangeHandler(e, e.originalEvent);
                }
            });
            $(data.target).on('mouseup', selectors.pageimgs, function(e){
                if (status.swipe) {
                	pcswipeendHandler(e, e.originalEvent);
                }
            });
            $(data.target).on('dblclick', selectors.pageimgs, function(e){
            	if (status.zoommode === false) {
            		pczoomin_gesture_fnc(e, e.originalEvent);
            	}
                
                //Android用 Swipe時に touchmove イベントがキャンセルされる現象の対策
                e.preventDefault();
            });
            
            //タッチデバイス
            $(data.target).on('touchstart', selectors.pageimgs, function(e){
                if (e.originalEvent.touches.length === 1 && !status.swipe && !status.gesture) {
                    swipestartHandler(e, e.originalEvent.touches);
                } else if (e.originalEvent.touches.length === 2 && !status.gesture) {
                    gesturestartHandler(e, e.originalEvent.touches);
                }
                
                //Android用 Swipe時に touchmove イベントがキャンセルされる現象の対策
                e.preventDefault();
            });
            $(data.target).on('touchmove', selectors.pageimgs, function(e){
                if (e.originalEvent.touches.length === 1 && status.swipe) {
                    swipechangeHandler(e, e.originalEvent.touches);
                } else if (e.originalEvent.touches.length === 2 && status.gesture) {
                    gesturechangeHandler(e, e.originalEvent.touches);
                }
            });
            $(data.target).on('touchend', selectors.pageimgs, function(e){
                if (e.originalEvent.touches.length < 1 && status.swipe) {
                    swipeendHandler(e, e.originalEvent.touches);
                } else if (e.originalEvent.touches.length < 2 && status.gesture) {
                    gestureendHandler(e, e.originalEvent.touches);
                }
            });
            
            $(document).on('touchstart mousedown', selectors.hoverbutton, function(e){
                $(this).addClass('touchhover');
            });
            $(document).on('touchend mouseup', selectors.hoverbutton, function(e){
                $(this).removeClass('touchhover');
            });
            
            //拡大移動(PC)
            $(data.target).on('mousedown', selectors.zoomimgs, function(e){
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === true && status.zoomdrag == false) {
                    var x = e.originalEvent.pageX,
                        y = e.originalEvent.pageY;
                    methods.zoomdragstart.apply($this, [data.target, x, y]);
                }
            
                //Android用 Swipe時に touchmove イベントがキャンセルされる現象の対策
                e.preventDefault();
            });
            $(data.target).on('mousemove', selectors.zoomimgs, function(e){
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === true && status.zoomdrag == true) {
                    var x = e.originalEvent.pageX,
                        y = e.originalEvent.pageY;
                    methods.zoomdragmove.apply($this, [data.target, x, y]);
                }
            });
            $(data.target).on('mouseup', selectors.zoomimgs, function(e){
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === true && status.zoomdrag == true) {
                    methods.zoomdragterminate.apply($this, [data.target]);
                }
            });
            $(data.target).on('dblclick', selectors.zoomimgs, function(e){
            	if (status.zoommode === true) {
            		if (params.zscale.scale < params.maxscale) {
                		pczoomin_gesture_fnc(e, e.originalEvent);
            		} else {
                		zoomout_gesture_fnc();
            		}
            	}
            });
            
            //拡大移動(タッチデバイス)
            $(data.target).on('touchstart', selectors.zoomimgs, function(e){
                if (e.originalEvent.touches.length === 1) {
                    if (status.flipping === false && status.sliding === false && status.changespread === false &&
                            status.zoommode === true && status.zoomdrag == false) {
                        var x = e.originalEvent.touches[0].pageX,
                            y = e.originalEvent.touches[0].pageY;
                        methods.zoomdragstart.apply($this, [data.target, x, y]);
                    }
                } else if (e.originalEvent.touches.length === 2 && !status.gesture) {
                    status.zoomdrag = false;
                    gesturestartHandler(e, e.originalEvent.touches);
                }
            });
            $(data.target).on('touchmove', selectors.zoomimgs, function(e){
                if (e.originalEvent.touches.length === 1) {
                    if (status.flipping === false && status.sliding === false && status.changespread === false &&
                            status.zoommode === true && status.zoomdrag == true) {
                        var x = e.originalEvent.touches[0].pageX,
                            y = e.originalEvent.touches[0].pageY;
                        methods.zoomdragmove.apply($this, [data.target, x, y]);
                    }
                } else if (e.originalEvent.touches.length === 2 && status.gesture) {
                    gesturechangeHandler(e, e.originalEvent.touches);
                }
            });
            $(data.target).on('touchend', selectors.zoomimgs, function(e){
                if (e.originalEvent.touches.length === 0) {
                    if (status.flipping === false && status.sliding === false && status.changespread === false &&
                            status.zoommode === true && status.zoomdrag == true) {
                        methods.zoomdragterminate.apply($this, [data.target]);
                    }
                } else if (e.originalEvent.touches.length === 1 && status.gesture) {
                	gestureendHandler(e, e.originalEvent.touches);
                }
            });
            
            $(data.target).on('dmxtaps', selectors.linkrect, function(e){
            });
            
            //(PC)
            $(data.target).on('mousedown', selectors.linkrect, function(e){
                $(e.target).css('opacity', params.linkrect.opacityover); //リンクの透過率を変更（アクティブ表示）
                
                if (!status.swipe && !status.gesture) {
                	pcswipestartHandler(e, e.originalEvent);
                }
                
                //Android用 Swipe時に touchmove イベントがキャンセルされる現象の対策
                e.preventDefault();
            });
            $(data.target).on('mouseover', selectors.linkrect, function(e){
            	if (settings.device == 'pc') {
	            	var ovc = $(e.target).data('ovc'),
	            	ova = parseFloat($(e.target).data('ova')),
	            	ovbc = $(e.target).data('ovbc'),
	            	ovbw = $(e.target).data('ovbw');
	        	
		           $(e.target).css({
		                'opacity': ova,
		                'border-color': '#'+ovbc,
		                'border-width': ovbw+'px',
		                'background-color': '#'+ovc
		            });
            	}
            });
            $(data.target).on('mouseout', selectors.linkrect, function(e){
            	if (settings.device == 'pc') {
	            	var otc = $(e.target).data('otc'),
		            	ota = parseFloat($(e.target).data('ota')),
		            	otbc = $(e.target).data('otbc'),
		            	otbw = $(e.target).data('otbw');
		        	
		            $(e.target).css({
		                'opacity': ota,
		                'border-color': '#'+otbc,
		                'border-width': otbw+'px',
		                'background-color': '#'+otc
		            });
            	}
            });
            $(data.target).on('mousemove', selectors.linkrect, function(e){
                if (status.swipe) {
                	pcswipechangeHandler(e, e.originalEvent);
                }
            });
            $(data.target).on('mouseup', selectors.linkrect, function(e){
                $(e.target).css('opacity', params.linkrect.opacity); //リンクの透過率を通常状態に戻す
                
                if (status.swipe) {
                    pcswipeendHandler(e, e.originalEvent);
                }
            });
            $(data.target).on('dblclick', selectors.linkrect, function(e){
            	if (status.zoommode === false) {
            		pczoomin_gesture_fnc(e, e.originalEvent);
            	}
                
                //Android用 Swipe時に touchmove イベントがキャンセルされる現象の対策
                e.preventDefault();
            });
            
            //(タッチデバイス)
            $(data.target).on('touchstart', selectors.linkrect, function(e){
            	var tmp = get_resize_param(params.win_w, params.win_h, params.paper_cols, params.paper_rows, params.paper_outer, params.zoom_outer);
            	
            	var ovc = $(e.target).data('ovc'),
	            	ova = parseFloat($(e.target).data('ova')),
	            	ovbc = $(e.target).data('ovbc'),
	            	ovbw = $(e.target).data('ovbw');
            	
                var p = $(e.target).data('pos').split(','),
	                x1 = parseInt(p[0]),
	                y1 = parseInt(p[1]),
	                x2 = parseInt(p[2]),
	                y2 = parseInt(p[3]);
        
	            var x = parseInt(x1) * tmp.ps * params.basescale;
	            var y = parseInt(y1) * tmp.ps * params.basescale;
	
	            var w = (parseInt(x2) - ovbw) * tmp.ps * params.basescale;
	            var h = (parseInt(y2) - ovbw) * tmp.ps * params.basescale;
            	
	            $(e.target).width(w).height(h);
	            $(e.target).css({
	                'opacity': ova,
	                'border-color': '#'+ovbc,
	                'border-width': ovbw+'px',
	                'background-color': '#'+ovc
	            }); //リンクの透過率を変更（アクティブ表示）
                
                if (e.originalEvent.touches.length === 1 && !status.swipe && !status.gesture) {
                    swipestartHandler(e, e.originalEvent.touches);
                } else if (e.originalEvent.touches.length === 2 && !status.gesture) {
                    gesturestartHandler(e, e.originalEvent.touches);
                }
                
                //Android用 Swipe時に touchmove イベントがキャンセルされる現象の対策
                e.preventDefault();
            });
            $(data.target).on('touchmove', selectors.linkrect, function(e){
                if (e.originalEvent.touches.length === 1 && status.swipe) {
                    swipechangeHandler(e, e.originalEvent.touches);
                } else if (e.originalEvent.touches.length === 2 && status.gesture) {
                    gesturechangeHandler(e, e.originalEvent.touches);
                }
            });
            $(data.target).on('touchend', selectors.linkrect, function(e){
            	var tmp = get_resize_param(params.win_w, params.win_h, params.paper_cols, params.paper_rows, params.paper_outer, params.zoom_outer);
            	
            	var otc = $(e.target).data('otc'),
	            	ota = parseFloat($(e.target).data('ota')),
	            	otbc = $(e.target).data('otbc'),
	            	otbw = $(e.target).data('otbw');
            	
                var p = $(e.target).data('pos').split(','),
	                x1 = parseInt(p[0]),
	                y1 = parseInt(p[1]),
	                x2 = parseInt(p[2]),
	                y2 = parseInt(p[3]);
        
	            var x = parseInt(x1) * tmp.ps * params.basescale;
	            var y = parseInt(y1) * tmp.ps * params.basescale;
	
	            var w = (parseInt(x2) - otbw) * tmp.ps * params.basescale;
	            var h = (parseInt(y2) - otbw) * tmp.ps * params.basescale;
            	
	            $(e.target).width(w).height(h);
	            $(e.target).css({
	                'opacity': ota,
	                'border-color': '#'+otbc,
	                'border-width': otbw+'px',
	                'background-color': '#'+otc
	            }); //リンクの透過率を通常状態に戻す
                
                if (e.originalEvent.touches.length < 1 && status.swipe) {
                    swipeendHandler(e, e.originalEvent.touches);
                } else if (e.originalEvent.touches.length < 2 && status.gesture) {
                    gestureendHandler(e, e.originalEvent.touches);
                }
            });
            $(data.target).on('touchcancel', selectors.linkrect, function(e){
            	var tmp = get_resize_param(params.win_w, params.win_h, params.paper_cols, params.paper_rows, params.paper_outer, params.zoom_outer);
            	
            	var otc = $(e.target).data('otc'),
	            	ota = parseFloat($(e.target).data('ota')),
	            	otbc = $(e.target).data('otbc'),
	            	otbw = $(e.target).data('otbw');
            	
                var p = $(e.target).data('pos').split(','),
	                x1 = parseInt(p[0]),
	                y1 = parseInt(p[1]),
	                x2 = parseInt(p[2]),
	                y2 = parseInt(p[3]);
        
	            var x = parseInt(x1) * tmp.ps * params.basescale;
	            var y = parseInt(y1) * tmp.ps * params.basescale;
	
	            var w = (parseInt(x2) - otbw) * tmp.ps * params.basescale;
	            var h = (parseInt(y2) - otbw) * tmp.ps * params.basescale;
            	
	            $(e.target).width(w).height(h);
	            $(e.target).css({
	                'opacity': ota,
	                'border-color': '#'+otbc,
	                'border-width': otbw+'px',
	                'background-color': '#'+otc
	            }); //リンクの透過率を変更（アクティブ表示）
            });
            
            //(PC)
            $(data.target).on('mousedown', selectors.vindexcontent, function(e){
                params.vindex.scrolling = true;
                params.vindex.touchex = -1;
                params.vindex.touchsx = e.originalEvent.pageX;
                
                //Android用 Swipe時に touchmove イベントがキャンセルされる現象の対策
                e.preventDefault();
            });
            $(data.target).on('mousemove', selectors.vindexcontent, function(e){
                var x = e.originalEvent.pageX;
                var p;
                
                if (params.vindex.scrolling) {
	                if (get_bind_group() === DEF_PARAMS.PAGEGROUP.RIGHT) {
	                    p = params.vindex.position - (x - params.vindex.touchsx);
	                } else {
	                    p = params.vindex.position + (x - params.vindex.touchsx);
	                }
	                
	                if (p < params.vindex.movelimmin) {
	                    params.vindex.moverewind = params.vindex.movelimmin;
	                } else if (params.vindex.movelimmax < p) {
	                    params.vindex.moverewind = params.vindex.movelimmax;
	                } else {
	                    params.vindex.moverewind = 0;
	                }
	                params.vindex.movex = p;
	                $(selectors.vindexcontent).css(params.direction, p + 'px');
                }
            });
            $(data.target).on('mouseup', selectors.vindexcontent, function(e){
                var aob = {};
                var aop = {queue:false, duration: 500};
                
                if (params.vindex.moverewind) {
                    aob[params.direction] = params.vindex.moverewind;
                    aop.complete = function () {
                        params.vindex.position = params.vindex.moverewind;
                        params.vindex.scrolling = false;
                    };
                    $(selectors.vindexcontent).animate(aob, aop);
                } else {
                    params.vindex.position = params.vindex.movex;
                    params.vindex.scrolling = false;
                }
            });
            
            //(タッチデバイス)
            $(data.target).on('touchstart', selectors.vindexcontent, function(e){
                params.vindex.scrolling = true;
                params.vindex.touchex = -1;
                params.vindex.touchsx = e.originalEvent.touches[0].pageX;
            });
            $(data.target).on('touchmove', selectors.vindexcontent, function(e){
                var x = e.originalEvent.touches[0].pageX;
                var p;
                
                if (get_bind_group() === DEF_PARAMS.PAGEGROUP.RIGHT) {
                    p = params.vindex.position - (x - params.vindex.touchsx);
                } else {
                    p = params.vindex.position + (x - params.vindex.touchsx);
                }
                
                if (p < params.vindex.movelimmin) {
                    params.vindex.moverewind = params.vindex.movelimmin;
                } else if (params.vindex.movelimmax < p) {
                    params.vindex.moverewind = params.vindex.movelimmax;
                } else {
                    params.vindex.moverewind = 0;
                }
                params.vindex.movex = p;
                $(selectors.vindexcontent).css(params.direction, p + 'px');
            });
            $(data.target).on('touchend', selectors.vindexcontent, function(e){
                var aob = {};
                var aop = {queue:false, duration: 500};
                
                if (params.vindex.moverewind) {
                    aob[params.direction] = params.vindex.moverewind;
                    aop.complete = function () {
                        params.vindex.position = params.vindex.moverewind;
                        params.vindex.scrolling = false;
                    };
                    $(selectors.vindexcontent).animate(aob, aop);
                } else {
                    params.vindex.position = params.vindex.movex;
                    params.vindex.scrolling = false;
                }
            });
            
            $(data.target).on('dmxtap', selectors.linkrect, function(e){
            	var tmp = get_resize_param(params.win_w, params.win_h, params.paper_cols, params.paper_rows, params.paper_outer, params.zoom_outer);
            	
            	var otc = $(e.target).data('otc'),
	            	ota = parseFloat($(e.target).data('ota')),
	            	otbc = $(e.target).data('otbc'),
	            	otbw = $(e.target).data('otbw');
            	
                var p = $(e.target).data('pos').split(','),
	                x1 = parseInt(p[0]),
	                y1 = parseInt(p[1]),
	                x2 = parseInt(p[2]),
	                y2 = parseInt(p[3]);
        
	            var x = parseInt(x1) * tmp.ps * params.basescale;
	            var y = parseInt(y1) * tmp.ps * params.basescale;
	
	            var w = (parseInt(x2) - otbw) * tmp.ps * params.basescale;
	            var h = (parseInt(y2) - otbw) * tmp.ps * params.basescale;
            	
	            $(e.target).width(w).height(h);
	            $(e.target).css({
	                'opacity': ota,
	                'border-color': '#'+otbc,
	                'border-width': otbw+'px',
	                'background-color': '#'+otc
	            }); //リンクの透過率を通常状態に戻す
	            
                if (status.flipping === false && status.sliding === false && status.changespread === false) {
                    var t = $(e.target).data('type');
                    
                	if (status.tracezoom == true) {
                		methods.tracezoomterminate.apply($this, [data.target]);
                	} else if (status.zoommode == true) {
                		methods.zoomoutstart.apply($this, [data.target]);
                	}
                	
                    methods.clearautoflip();
                    
                    if (t == DEF_PARAMS.LINKTYPE.PAGE) {
                        if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                            status.flipping = true;
                    		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'flipangle': 0, 'direction': null, 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
                    		status.flipcue.length++;
	                        methods.gotostart.apply($this, [data.target, $(e.target).data('link'), status.flipcue.cue[status.flipcue.cue.length - 1]]);
                        } else {
                            status.sliding = true;
	                        methods.gotostart.apply($this, [data.target, $(e.target).data('link')]);
                        }
                    } else if (t == DEF_PARAMS.LINKTYPE.URL) {
                        window.open($(e.target).data('link'), $(e.target).data('target'));
                    }
                }
            });
           
            $(data.target).on('dmxtap', selectors.skinlink, function(e){
                if (status.flipping === false && status.sliding === false && status.changespread === false) {
                    var t = $(e.target).data('type');
                    
                    methods.clearautoflip();
                    
                    if (t == DEF_PARAMS.LINKTYPE.PAGE) {
                        if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                            status.flipping = true;
                    		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'flipangle': 0, 'direction': null, 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
                    		status.flipcue.length++;
	                        methods.gotostart.apply($this, [data.target, $(e.target).data('link'), status.flipcue.cue[status.flipcue.cue.length - 1]]);
                        } else {
                            status.sliding = true;
	                        methods.gotostart.apply($this, [data.target, $(e.target).data('link')]);
                        }
                    } else if (t == DEF_PARAMS.LINKTYPE.URL) {
                        window.open($(e.target).data('link'), $(e.target).data('target'));
                    }
                }
            });
            
            indexlist_gotopage_fnc = function(e){
                if (status.flipping === false && status.sliding === false && status.changespread === false) {
                    var t = $(e.currentTarget).data('type');
                    
                    if (t == DEF_PARAMS.LINKTYPE.PAGE) {
                        if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                            switch (settings.pageaction) {
                                case DEF_PARAMS.PAGEACTION.RIGHT:
                                case DEF_PARAMS.PAGEACTION.UPPER:
                                case DEF_PARAMS.PAGEACTION.LEFT:
                                case DEF_PARAMS.PAGEACTION.LOWER:
                                    status.flipping = true;

                                    break;
                                case DEF_PARAMS.PAGEACTION.RSLIDE:
                                case DEF_PARAMS.PAGEACTION.LSLIDE:
                                    status.sliding = true;

                                    break;
                            }
                        } else {
                            status.sliding = true;
                        }
                        if (status.zoommode) {
                    		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'flipangle': 0, 'direction': null, 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
                    		status.flipcue.length++;
                            params.delayfuncparam = [$(selectors.livebook), $(e.currentTarget).data('link'), status.flipcue.cue[status.flipcue.cue.length - 1]];
                            params.delayfuncobj = methods.gotostart;
                            methods.zoomoutstart.apply($(this), [$(selectors.livebook)]);
                        } else {
                    		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'flipangle': 0, 'direction': null, 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
                    		status.flipcue.length++;
                            methods.gotostart.apply($(this), [$(selectors.livebook), $(e.currentTarget).data('link'), status.flipcue.cue[status.flipcue.cue.length - 1]]);
                        }
                    } else if (t == DEF_PARAMS.LINKTYPE.URL) {
                        window.open($(e.currentTarget).data('link'), $(e.currentTarget).data('target'));
                    }
                    
                    if (status.searchall) {
                        methods.closesearchall.apply(data.target, [data.target]);
                    } else if (status.tindex) {
                        methods.closetindex.apply(data.target, [data.target]);
                    } else if (status.vindex) {
                        methods.closevindex.apply(data.target, [data.target]);
                    } else if (status.memolist) {
                        methods.closememolist.apply(data.target, [data.target]);
                    } else if (status.sns) {
                    	methods.closesns.apply(data.target, [data.target]);
                    } else {
                        $.mobile.changePage('#livebook', {transition: 'fade'});
                    }
                    
                }
                
                e.preventDefault();
                
                return false;
            };
            $(document).on("vclick", selectors.tindexlist, indexlist_gotopage_fnc);
            $(document).on("vclick", selectors.vindexlist, indexlist_gotopage_fnc);
            $(document).on("vclick", selectors.searchalllist, indexlist_gotopage_fnc);
            $(document).on("vclick", selectors.memolistline, indexlist_gotopage_fnc);
            
            //（PC）
            $(document).on("vclick", '#controllbar #l_top', function(e){
            	if (status.tracezoom == true) {
            		methods.tracezoomterminate.apply($this, [data.target]);
            	} else if (status.zoommode == true) {
            		methods.zoomoutstart.apply($this, [data.target]);
            	}
            	
        			methods.clearautoflip();
            	if (status.flipping === false && status.sliding === false && status.changespread === false) {
            		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'flipangle': 0, 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
            		status.flipcue.length++;
            		methods.gotolefttop.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
            	}
            });
            $(document).on("vclick", '#controllbar #l_auto', function(e){
            	if (status.tracezoom == true) {
            		methods.tracezoomterminate.apply($this, [data.target]);
            	} else if (status.zoommode == true) {
            		methods.zoomoutstart.apply($this, [data.target]);
            	}
            	
            	if (status.flipping === false && status.sliding === false && status.changespread === false) {
            		if (status.autoflip) {
            			if (-1 < params.autoflip.timerID) {
                			clearTimeout(params.autoflip.timerID);
                			status.autoflip = false;
                			params.autoflip.timerID = -1;
                			
	            			if (params.autoflip.dir == 'r2l') {
	                			params.autoflip.dir = null;
	            			} else {
	            				$('#controllbar #l_auto').click();
	            			}
            			}
            		} else {
            			if (params.autoflip.timerID == -1) {
                    		params.autoflip.dir = 'r2l';
                    		status.autoflip = true;
            				methods.autoflip.apply($this, [data.target]);
            			}
            		}
            	}
            });
            $(document).on("vclick", '#controllbar #l_page', function(e){
            	if (status.tracezoom == true) {
            		methods.tracezoomterminate.apply($this, [data.target]);
            	} else if (status.zoommode == true) {
            		methods.zoomoutstart.apply($this, [data.target]);
            	}
            	
            	var c = methods.flipcounttype.apply($this, ['left']); // ページ数の増減タイプ plus or minus
            	
        		methods.clearautoflip();
            	if (status.changespread === false) {
            		if (status.flipping === false && status.sliding === false) {
            			if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                        	if (c == 'minus') {
                        		p = status.curbasepage - 2;
                        	} else {
                        		p = status.curbasepage + 2;
                        	}
                    		if (0 <= p && p <= params.lastpage) {
    		            		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'slidetype': 'overlay', 'counttype': c, 'flipangle': 0, 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
    		            		status.flipcue.length++;
    		            		methods.gotoleft.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
                    		}
            			} else {
	                    	if (c == 'minus') {
	                    		p = status.curbasepage - 1;
	                    	} else {
	                    		p = status.curbasepage + 1;
	                    	}
	                    	if (1 <= p && p <= params.lastpage - 1) {
	                    		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'slidetype': 'overlay', 'counttype': c, 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
	                    		status.flipcue.length++;
	                    		methods.gotoleft.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
	                    	}
            			}
            		} else {
            			if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
	                    	if (c == 'minus') {
	                    		p = status.curbasepage - status.flipcue.length * 2;
	                    	} else {
	                    		p = status.curbasepage + status.flipcue.length * 2;
	                    	}
	                		if (0 <= p && p <= params.lastpage) {
		                		status.flipcue.cue.push({'basepage': p, 'headcue': false, 'flipangle': 0, 'slidetype': 'overlay', 'counttype': c, 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
		                		status.flipcue.length++;
		                		methods.gotoleft.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
	                		}
            			} else {
	                    	if (c == 'minus') {
	                    		p = status.curbasepage - status.flipcue.length;
	                    	} else {
	                    		p = status.curbasepage + status.flipcue.length;
	                    	}
                    		if (1 <= p && p <= params.lastpage - 1) {
                    			status.flipcue.cue.push({'basepage': p, 'headcue': false, 'slidetype': 'overlay', 'counttype': c, 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
	                    		status.flipcue.length++;
	                    		methods.gotoleft.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
                    		}
            			}
            		}
            	}
            });
            $(document).on("vclick", '#controllbar #r_page', function(e){
            	if (status.tracezoom == true) {
            		methods.tracezoomterminate.apply($this, [data.target]);
            	} else if (status.zoommode == true) {
            		methods.zoomoutstart.apply($this, [data.target]);
            	}
            	
            	var c = methods.flipcounttype.apply($this, ['right']); // ページ数の増減タイプ plus or minus
            	
            	methods.clearautoflip();
            	if (status.changespread === false) {
            		if (status.flipping === false && status.sliding === false) {
            			if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
	                    	if (c == 'minus') {
	                    		p = status.curbasepage - 2;
	                    	} else {
	                    		p = status.curbasepage + 2;
	                    	}
	                		if (0 <= p && p <= params.lastpage) {
			            		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'slidetype': 'overlay', 'counttype': c, 'flipangle': 0, 'direction': 'r2l', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
			            		status.flipcue.length++;
			            		methods.gotoright.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
	                		}
            			} else {
	                    	if (c == 'minus') {
	                    		p = status.curbasepage - 1;
	                    	} else {
	                    		p = status.curbasepage + 1;
	                    	}
	                    	if (1 <= p && p <= params.lastpage - 1) {
	                    		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'slidetype': 'overlay', 'counttype': c, 'direction': 'r2l', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
	                    		status.flipcue.length++;
	                    		methods.gotoright.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
	                    	}
            			}
            		} else {
            			if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
	                    	if (c == 'minus') {
	                    		p = status.curbasepage - status.flipcue.length * 2;
	                    	} else {
	                    		p = status.curbasepage + status.flipcue.length * 2;
	                    	}
	                		if (0 <= p && p <= params.lastpage) {
			            		status.flipcue.cue.push({'basepage': p, 'headcue': false, 'flipangle': 0, 'slidetype': 'overlay', 'counttype': c, 'direction': 'r2l', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
			            		status.flipcue.length++;
			            		methods.gotoright.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
	                		}
            			} else {
	                    	if (c == 'minus') {
	                    		p = status.curbasepage - status.flipcue.length;
	                    	} else {
	                    		p = status.curbasepage + status.flipcue.length;
	                    	}
                    		if (1 <= p && p <= params.lastpage - 1) {
                    			status.flipcue.cue.push({'basepage': p, 'headcue': false, 'slidetype': 'overlay', 'counttype': c, 'direction': 'r2l', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
                        		status.flipcue.length++;
                        		methods.gotoright.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
                    		}
            			}
            		}
            	}
            });
            $(document).on("vclick", '#controllbar #r_auto', function(e){
            	if (status.tracezoom == true) {
            		methods.tracezoomterminate.apply($this, [data.target]);
            	} else if (status.zoommode == true) {
            		methods.zoomoutstart.apply($this, [data.target]);
            	}
            	
            	if (status.flipping === false && status.sliding === false && status.changespread === false) {
            		if (status.autoflip) {
            			if (-1 < params.autoflip.timerID) {
                			clearTimeout(params.autoflip.timerID);
                			status.autoflip = false;
                			params.autoflip.timerID = -1;
                			
	            			if (params.autoflip.dir == 'l2r') {
	                			params.autoflip.dir = null;
	            			} else {
	            				$('#controllbar #r_auto').click();
	            			}
            			}
            		} else {
            			if (params.autoflip.timerID == -1) {
                    		params.autoflip.dir = 'l2r';
                    		status.autoflip = true;
                    		methods.autoflip.apply($this, [data.target]);
            			}
            		}
            	}
            });
            $(document).on("vclick", '#controllbar #r_top', function(e){
            	if (status.tracezoom == true) {
            		methods.tracezoomterminate.apply($this, [data.target]);
            	} else if (status.zoommode == true) {
            		methods.zoomoutstart.apply($this, [data.target]);
            	}
            	
        			methods.clearautoflip();
            	if (status.flipping === false && status.sliding === false && status.changespread === false) {
            		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'flipangle': 0, 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
            		status.flipcue.length++;
            		methods.gotorighttop.apply($this, [data.target, status.flipcue.cue[status.flipcue.cue.length - 1]]);
            	}
            });
            $(document).on("vclick", '#controllbar #goto_page,#controllbar #goto_submit', function(e){
            	var goto_text = $('#goto_text').val();
            	var goto_num;
            	
            	if (status.tracezoom == true) {
            		methods.tracezoomterminate.apply($this, [data.target]);
            	} else if (status.zoommode == true) {
            		methods.zoomoutstart.apply($this, [data.target]);
            	}
            	if (!isNaN(goto_text)) {
            		goto_num = parseInt(goto_text) + settings.disp_stpage_num - settings.disp_stpage_cnt;
            		if (0 <= goto_num && goto_num <= params.lastpage) {
            			methods.clearautoflip();
		            	if (status.flipping === false && status.sliding === false && status.changespread === false) {
			            	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
			                    status.flipping = true;
	                    		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'flipangle': 0, 'direction': null, 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
	                    		status.flipcue.length++;
				                methods.gotostart.apply($this, [data.target, goto_num, status.flipcue.cue[status.flipcue.cue.length - 1]]);
			                } else {
			                  status.sliding = true;
			                  cue=[];
			                  if(status.flipcue.cue.length>0){
			                  	cue = status.flipcue.cue[status.flipcue.cue.length - 1];
			                  }
				                methods.gotostart.apply($this, [data.target, goto_num, cue]);
			                }
			                if (settings.device == 'pc') {
			                	$('#goto_text').val('');
			                }
		            	}
	            	}
            	}
            	return false;
            });
            $(document).on("vclick", '#controllbar #v_index', function(e){
                if (params.vindex.loaded === false) {
                    methods.createvindexlist.apply($this, [data.target]);
                    methods.resize.apply($this, [data.target]);
                } else {
                    methods.resetvindexposition.apply($this, [$this]);
                }
                if (!status.vindex) {
                    if (status.sns) {
                    	methods.closesns.apply($this, [$this]);
                    }
                    methods.openvindex.apply($this, [$this]);
                } else {
                    methods.closevindex.apply($this, [$this]);
                }
                
                return false;
            });
            $(document).on("vclick", '#vindex .close', function(e){
            	methods.closevindex.apply($this, [$this]);
            });
            
            $(document).on("vclick", '#controllbar #memotool_btn', function(e){
                if (params.memo.tool.created === false) {
                    methods.creatememotool.apply($this, [data.target]);
                    methods.resize.apply($this, [data.target]);
                }
                if (!status.memotool) {
                    if (status.sns) {
                    	methods.closesns.apply($this, [$this]);
                    }
                    methods.openmemotool.apply($this, [$this]);
                } else {
                    methods.closememotool.apply($this, [$this]);
                }
                
                return false;
            });
            $(document).on("vclick", '#memotool .close', function(e){
            	methods.closememotool.apply($this, [$this]);
            });
            $(document).on("vclick", '#memotool .memolist_btn', function(e){
                if (params.memo.list.created === false) {
                    methods.creatememolistpage.apply($this, [data.target]);
                    methods.resize.apply($this, [data.target]);
                }
                if (!status.memolist) {
                    if (status.sns) {
                    	methods.closesns.apply($this, [$this]);
                    }
                    if (status.memotool) {
                    	methods.closememotool.apply($this, [$this]);
                    }
                    methods.openmemolist.apply($this, [$this]);
                } else {
                    methods.closememolist.apply($this, [$this]);
                }
                
                return false;
            });
            $(document).on("vclick", '#memolist .close', function(e){
            	methods.closememolist.apply($this, [$this]);
            });
            $(document).on("vclick", '#memotool .color', function(e){
                if (params.memo.tool.created === true) {
                	methods.closememotool.apply($this, [$this]);
                	
                	params.memo.target = $.extend(true, {}, params.memo.default);
                	params.memo.target.color = $(this).data('color');
                	
            		methods.addmouseicon.apply($this, [$this, 'memo_icon']);
                	methods.activatemouseicon.apply($this, [$this]);
                	methods.activatereceivememo.apply($this, [$this]);
                }
                
                return false;
            });
            $(document).on("vclick", '.memo_area .close', function(e){
            	methods.closememo.apply($this, [$this, $(e.target).parents('.memo_area')]);
            });
            $(document).on("vclick", '.memo_area .minimize', function(e){
            	methods.minimizememo.apply($this, [$this, $(e.target).parents('.memo_area')]);
            });
            $(document).on("vclick", '.memo_area .normalize', function(e){
//            	methods.normalizememo.apply($this, [$this, $(e.target).parents('.memo_area')]);
            });
            $(document).on("keyup", '.memo_area textarea', function(e){
            	methods.changememocontent.apply($this, [$this, $(e.target).parents('.memo_area')]);
            });
            
            $(document).on("vclick", '#controllbar #print_btn', function(e){
            	methods.clearautoflip();
            	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
	                if (!status.print) {
	                    if (status.sns) {
	                    	methods.closesns.apply($this, [$this]);
	                    }
	                    methods.openprint.apply($this, [$this]);
	                } else {
	                    methods.closeprint.apply($this, [$this]);
	                }
            	} else {
            		var pname = ('0000' + status.curbasepage).substr(-4);
            		window.open('pdf/'+pname+'.pdf', '_blank');
            	}
                
                return false;
            });
            $(document).on("vclick", '#print .close', function(e){
            	methods.closeprint.apply($this, [$this]);
            });
            
            $(document).on("vclick", '#controllbar #bookmarktag_btn', function(e){
            	if (!status.bookmarkreceive) {
            		methods.addmouseicon.apply($this, [$this, 'bookmarktag_icon']);
                	methods.activatemouseicon.apply($this, [$this]);
                	methods.activatereceivebookmark.apply($this, [$this]);
	            } else {
	            	methods.deactivatemouseicon.apply($this, [$this]);
	            	methods.removemouseicon.apply($this, [$this]);
	            	methods.deactivatereceivebookmark.apply($this, [$this]);
            	}
                
                return false;
            });
            $(document).on("vclick", 'div.tag_area span', function(e){
            	var s_page_num = status.curbasepage;
                var page_num = parseInt($(this).parent('div.tag_area').attr('rel'));
                
                if (page_num != s_page_num && page_num != s_page_num + 1) {
            		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'flipangle': 0, 'direction': null, 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
            		status.flipcue.length++;
                	methods.gotostart.apply($this, [$this, page_num, status.flipcue.cue[status.flipcue.cue.length - 1]]);
                }
            	
                return false;
            });
            
            $(document).on("vclick", '#controllbar #search_all', function(e){
                if (params.searchall.loaded === false) {
//                    methods.createvindexlist.apply($this, [data.target]);	//@ToDo
                    methods.resize.apply($this, [data.target]);	//@ToDo
                } else {
//                    methods.resetsearchallposition.apply($this, [$this]);	//@ToDo
                }
                methods.clearautoflip();
                if (!status.searchall) {	//@ToDo
                    if (status.sns) {
                    	methods.closesns.apply($this, [$this]);
                    }
                    methods.opensearchall.apply($this, [$this]);	//@ToDo
                } else {
                    methods.closesearchall.apply($this, [$this]);	//@ToDo
                }
                
                return false;
            });
            $(document).on("vclick", '#searchall .close', function(e){
            	methods.closesearchall.apply($this, [$this]);	//@ToDo
            });
            $(document).on("vclick", '#search_btn', function(e){
        		if (params.searchall.dataloaded) {
        			var keyword = $('#search_keyword').val();
                    var keys;

                    var _match = new Array();
                    var tmatch = new Array();
                    var resultFlg = false;

                    var search_mode = $('#search_mode_and input').is(':checked') ? 'and' : 'or';

                    var re = new RegExp("DEF", "i");

                    searchMatch = new Array();
                    searchMatchPageIndexes = new Array();
                    searchMatchIndexes = new Array();
                    searchMatchPageHitNums = new Array();
                    searchMatchPageHitKeys = new Array();

                    keyword = keyword.replace(/(^( |　)+)|(( |　)+$)/g, "");

                    if (!keyword || keyword.length === 0) {
                        return false;
                    }

                    keyword = keyword.replace(/( |　)+/g, " ");
                    keys = keyword.split(" ");
                    
                    var strings = params.searchall.search_key;
                    var coords = params.searchall.search_pnt;

                    for (var i = 0, l = strings.length; i < l; i++) {
                        var is_hit = false;
                        
                        for (var j = 0, m = keys.length; j < m; j++) {
                            var keyreg = new RegExp(keys[j], "i");
                            var p = String(strings[i]).search(keyreg);
                            if (0 <= p) {
                                var coord = String(coords[i]).split(";");

                                if (isNaN(searchMatchPageHitNums['page_' + coord[4]]))
                                    searchMatchPageHitNums['page_' + coord[4]] = 0;

                                searchMatchPageHitNums['page_' + coord[4]]++;


                                if (!$.isArray(searchMatchPageHitKeys['page_' + coord[4]]))
                                    searchMatchPageHitKeys['page_' + coord[4]] = new Array();

                                if (!$.isArray(searchMatchPageIndexes['page_' + coord[4]]))
                                    searchMatchPageIndexes['page_' + coord[4]] = new Array();

                                if (isNaN(searchMatchPageHitKeys['page_' + coord[4]][j]))
                                    searchMatchPageHitKeys['page_' + coord[4]][j] = 1;


                                if (!is_hit) {
                                    is_hit = true;
                                    searchMatchPageIndexes['page_' + coord[4]].push({"txt": strings[i], "page": coord[4], "x1": coord[0], "y1": coord[1], "x2": coord[2], "y2": coord[3]});
                                }
                            }
                        }
                    }

                    if (search_mode == 'and') {
                        for (var it in searchMatchPageHitKeys) {
                            for (var i = 0, l = keys.length; i < l; i++) {
                                if (searchMatchPageHitKeys[it][i] == undefined) {
                                    delete searchMatchPageHitKeys[it];
                                    delete searchMatchPageIndexes[it];
                                    delete searchMatchPageHitNums[it];
                                    break;
                                }
                            }
                        }

                    } else {
                    }

                    for (var it in searchMatchPageIndexes) {
                        _match = _match.concat(searchMatchPageIndexes[it]);
                    }

                    searchMatchPageHitNums = valueSort(searchMatchPageHitNums, "reverse")

                    for (var i = 0, l = _match.length; i < l; i++) {
                        if (!$.isArray(searchMatchIndexes['page_' + _match[i]['page']]))
                            searchMatchIndexes['page_' + _match[i]['page']] = new Array();

                        searchMatchIndexes['page_' + _match[i]['page']].push(i);
                    }

                    params.searchall.match = _match;
                    if (!resultFlg) {
                        resultFlg = true;
                        methods.createsearchresult.apply($this, [data.target, params.searchall.match, params.searchall.sort_mode]);
                    }
                } else {
                	methods.loadsearchalldata.apply($this, [data.target]);
                }
            });
            $(document).on("vclick", '#search_clear_result', function(e){
            	clearSearchResult();
            });
            $(document).on("vclick", '#search_sortorder_hit', function(e){
            	if (params.searchall.sort_mode == "hitnum") {

                } else {
                    $("#search_sortorder_page").removeClass('active');
                    $("#search_sortorder_hit").addClass('active');
                    params.searchall.sort_mode = "hitnum"
                	methods.createsearchresult.apply($this, [data.target, params.searchall.match, params.searchall.sort_mode]);
                }
            });
            $(document).on("vclick", '#search_sortorder_page', function(e){
            	if (params.searchall.sort_mode == "page") {

                } else {
                    $("#search_sortorder_hit").removeClass('active');
                    $("#search_sortorder_page").addClass('active');
                    params.searchall.sort_mode = "page"
                	methods.createsearchresult.apply($this, [data.target, params.searchall.match, params.searchall.sort_mode]);
                }
            });
            
            $(document).on("blur", '#controllbar #goto_text', function(e){
            	if (settings.device != 'pc') {
            		methods.setpagenum.apply($this, [data.target]);
            	}
            });
            $(document).on("vclick", '#controllbar #print_l', function(e){
            	if (!$(this).hasClass('disabled')) {
	            	$('#printarea').empty();
	                switch (settings.pageaction)
	                {
	                    case DEF_PARAMS.PAGEACTION.RIGHT:
	                    case DEF_PARAMS.PAGEACTION.UPPER:
	                    case DEF_PARAMS.PAGEACTION.RSLIDE:
	                        
	                        //現在のページ
	                    	$('#printarea').append('<div class="printpage"><img src="'+params.pageimgdir+params.pageimgprefix+'__dmx__' + (status.curbasepage + 1) + '__'+DEF_PARAMS.PAGEZOOMNUM.ZOOM+'.jpg"></div>');
	
	                        break;
	                    case DEF_PARAMS.PAGEACTION.LEFT:
	                    case DEF_PARAMS.PAGEACTION.LOWER:
	                    case DEF_PARAMS.PAGEACTION.LSLIDE:
	                        
	                        //現在のページ
	                    	$('#printarea').append('<div class="printpage"><img src="'+params.pageimgdir+params.pageimgprefix+'__dmx__' + status.curbasepage + '__'+DEF_PARAMS.PAGEZOOMNUM.ZOOM+'.jpg"></div>');
	                        
	                        break;
	                }
	                window.print();
            	}
            });
            $(document).on("vclick", '#controllbar #print_r', function(e){
            	if (!$(this).hasClass('disabled')) {
	            	$('#printarea').empty();
	                switch (settings.pageaction)
	                {
	                    case DEF_PARAMS.PAGEACTION.RIGHT:
	                    case DEF_PARAMS.PAGEACTION.UPPER:
	                    case DEF_PARAMS.PAGEACTION.RSLIDE:
	                        
	                        //現在のページ
	                    	$('#printarea').append('<div class="printpage"><img src="'+params.pageimgdir+params.pageimgprefix+'__dmx__' + status.curbasepage + '__'+DEF_PARAMS.PAGEZOOMNUM.ZOOM+'.jpg"></div>');
	                    	
	                        break;
	                    case DEF_PARAMS.PAGEACTION.LEFT:
	                    case DEF_PARAMS.PAGEACTION.LOWER:
	                    case DEF_PARAMS.PAGEACTION.LSLIDE:
	                        
	                        //現在のページ
	                    	$('#printarea').append('<div class="printpage"><img src="'+params.pageimgdir+params.pageimgprefix+'__dmx__' + (status.curbasepage + 1) + '__'+DEF_PARAMS.PAGEZOOMNUM.ZOOM+'.jpg"></div>');
	                        
	                        break;
	                }
	                window.print();
            	}
            });
            $(document).on("vclick", '#controllbar #print_d', function(e){
            	if (!$(this).hasClass('disabled')) {
	            	$('#printarea').empty();
	                switch (settings.pageaction)
	                {
	                    case DEF_PARAMS.PAGEACTION.RIGHT:
	                    case DEF_PARAMS.PAGEACTION.UPPER:
	                    case DEF_PARAMS.PAGEACTION.RSLIDE:
	                        
	                        //現在のページ
	                    	$('#printarea').append('<div class="printpage"><img src="'+params.pageimgdir+params.pageimgprefix+'__dmx__' + (status.curbasepage + 1) + '__'+DEF_PARAMS.PAGEZOOMNUM.ZOOM+'.jpg"></div>');
	                    	$('#printarea').append('<div class="printpage"><img src="'+params.pageimgdir+params.pageimgprefix+'__dmx__' + status.curbasepage + '__'+DEF_PARAMS.PAGEZOOMNUM.ZOOM+'.jpg"></div>');
	                    	
	                        break;
	                    case DEF_PARAMS.PAGEACTION.LEFT:
	                    case DEF_PARAMS.PAGEACTION.LOWER:
	                    case DEF_PARAMS.PAGEACTION.LSLIDE:
	                        
	                        //現在のページ
	                    	$('#printarea').append('<div class="printpage"><img src="'+params.pageimgdir+params.pageimgprefix+'__dmx__' + status.curbasepage + '__'+DEF_PARAMS.PAGEZOOMNUM.ZOOM+'.jpg"></div>');
	                    	$('#printarea').append('<div class="printpage"><img src="'+params.pageimgdir+params.pageimgprefix+'__dmx__' + (status.curbasepage + 1) + '__'+DEF_PARAMS.PAGEZOOMNUM.ZOOM+'.jpg"></div>');
	                        
	                        break;
	                }
	                window.print();
            	}
            });
            $(document).on("vclick", '#controllbar #pdf_dl', function(e){
            	if (params.pdf_dl != '') {
            		window.open(params.pdf_dl, '_blank');
            	}
            });
            $(document).on("vclick", '#controllbar #help_btn', function(e){
            	var $this = $('#livebook');
            	
            	methods.clearautoflip();
            	if ($('#help_screen .help_content').length == 0) {
	            	$('#help_screen').append('<div role="dialog" class="ui-dialog-contain ui-overlay-shadow ui-corner-all"><div class="help_content"><img src="'+settings.imgdir+'/help_screen.png"></div><div class="help_close_btn"></div></div><div id="help_bg"></div>');
	            	$('#help_screen .ui-dialog-contain').height(params.win_h);
	            	
	            	if (params.help.ow == null) {
	            		params.help.ow = $('#help_screen .help_content').width();
	            	}
	            	if (params.help.oh == null) {
	            		params.help.oh = $('#help_screen .help_content').height();
	            	}
	            	
	            	// ヘルプ画像の縦横比
            		params.help.ratio = params.help.ow / params.help.oh;
	            	
            		methods.resize.apply($this, [$this]);
	            	
	            	$('#help_screen').css('visibility', 'visible');
            	}
            });
            $(document).on("vclick", '#help_screen .help_close_btn,#help_screen #help_bg', function(e){
            	$('#help_screen').removeAttr('class');
            	$('#help_screen').empty();
            });
            $(document).on("vclick", '#controllbar #close_btn', function(e){
            	methods.clearautoflip();
            	window.close();
            });
            $(document).on("vclick", '#controllbar #zoom_in', function(e){
            	var p, o, c;
            	
            	if (!$(this).hasClass('disabled')) {
            		methods.clearautoflip();
	                if (status.flipping === false && status.sliding === false && status.changespread === false) {
	                    p = status.curbasepage;
	                    
	                    if (status.zoommode === false) {
	                    	o = $('#paper_frame .page[rel='+p+'] img');
		                    c = {
			                		'x': o.width()*2,
			                		'y': o.height() / 2 *2
			                	};
	                    } else {
	                    	o = $('#zoom_frame .page[rel='+p+'] img');
		                    c = {
			                		'x': o.width()*2,
			                		'y': o.height() / 2 *2
			                	};
	                    }
	                    if (status.zoommode === false) {
		                    params.zscale.page = p;
		                    params.zscale.pagex = c.x;
		                    params.zscale.pagey = c.y;
		                    
		                    params.zoom_pow = params.media_w*2;
		                    params.zoom_poh = params.media_h*2;
	                    }
	                    status.zoominact = true;
	                    methods.zoominstart.apply($this, [data.target, p, c.x, c.y]); //現在の表示比率を100%とした座標を渡す（実際の紙面上での座標は変換が必要）
	                }
            	}
            });
            $(document).on("vclick", '#controllbar #zoom_out', function(e){
            	if (!$(this).hasClass('disabled')) {
            		methods.clearautoflip();
                    if (status.flipping === false && status.sliding === false && status.changespread === false && status.zoommode === true) {
                        status.zoomoutact = true;
                        methods.zoomoutstart.apply($this, [data.target]);
                    }
            	}
            });
            $(document).on("mousedown", selectors.zoommovebutton, function(e){
            	var d;
            	
            	if (!$(this).hasClass('disabled')) {
            		if (status.flipping === false && status.sliding === false && status.changespread === false
            				&& status.zoommode === true && status.zoomdrag === false) {
            			
                        if ($(e.target).hasClass('top')) {
                        	d = 'top';
                        } else if ($(e.target).hasClass('right')) {
                        	d = 'right';
                        } else if ($(e.target).hasClass('bottom')) {
                        	d = 'bottom';
                        } else if ($(e.target).hasClass('left')) {
                        	d = 'left';
                        }
                        
                        params.zoom_move_d = d;
                        params.zoom_move_t = setInterval(methods.zoombuttonmove, DEF_PARAMS.ZOOMMOVE.INTERVAL, data.target);
            		}
            	}
            });
            $(document).on("mouseup", function(e){
            	if (!$(this).hasClass('disabled')) {
            		if (status.flipping === false && status.sliding === false && status.changespread === false
            				&& status.zoommode === true && status.zoomdrag === false && status.zoommove === true) {
            			status.zoommove = false;
            			clearInterval(params.zoom_move_t);
            			params.zoom_move_t = -1;
            		}
            	}
            });
            //メニューの表示
            triggermenu_swipe_fnc = function(e){
                $(selectors.menu).panel("open");
                e.preventDefault();
            };
            $(data.target).on('tap', selectors.menutrigger, triggermenu_swipe_fnc);
            
            //ページ切り替え
            //Anderson 20180822
            //$(data.target).on('vclick', selectors.changespreadmenu, function(e){
            $(document).on('vclick', selectors.changespreadmenu, function(e){
                if (status.changespread === false) {
                    $(selectors.menu).panel('close');
                    if (status.zoommode === true) {
                        methods.zoomoutstart.apply($this, [data.target]);
                    }
                    methods.changespread.apply($this, [data.target, true]);
                }
            });
            
            //ビジュアル目次
            //Anderson 20180822
            //$(data.target).on('vclick', selectors.vindexmenu, function(e){
            $(document).on('vclick', selectors.vindexmenu, function(e){
                if (params.vindex.loaded === false) {
                    methods.createvindexlist.apply($this, [data.target]);
                    methods.resize.apply($this, [data.target]);
                } else {
                    methods.resetvindexposition.apply($this, [$this]);
                }
                if (!status.vindex) {
                    if (status.sns) {
                    	methods.closesns.apply($this, [$this]);
                    }
                    $(selectors.menu).panel('close');
                    methods.openvindex.apply($this, [$this]);
                } else {
                    $(selectors.menu).panel('close');
                    methods.closevindex.apply($this, [$this]);
                }
                
                return false;
            });
            $(data.target).on('vclick', selectors.vindexbg, function(e){
                methods.closevindex.apply($this, [$this]);
            });
            
            //SNS
            $(data.target).on('vclick', selectors.snsmenu, function(e){
                if (params.sns.loaded === false) {
                    methods.createsnslist.apply($this, [data.target]);
                } else {
                	methods.refreshsnslist.apply($this, [data.target]);
                }
                methods.resize.apply($this, [data.target]);
                if (!status.sns) {
                    if (status.vindex) {
                    	methods.closevindex.apply($this, [$this]);
                    }
                    $(selectors.menu).panel('close');
                    methods.opensns.apply($this, [$this]);
                } else {
                    $(selectors.menu).panel('close');
                    methods.closesns.apply($this, [$this]);
                }
                
                return false;
            });
            $(data.target).on('vclick', selectors.snswrapbg, function(e){
                methods.closesns.apply($this, [$this]);
            });
            
            //テキスト目次
            if (settings.device != 'pc') {
            	//テキスト目次を開く
	            $(data.target).on('vclick', selectors.tindexmenu, function(e){
	                $.mobile.changePage('#tindex');
	                return false;
	            });
            
	            //テキスト目次を閉じる
	            $(document).on('vclick', selectors.tindexback, function(e){
	                $.mobile.changePage('#livebook');
	                return false;
	            });
            } else {
                $(document).on("vclick", '#controllbar #t_index', function(e){
                    if (params.tindex.loaded === false) {
                    	methods.loadtindexdata.apply($this, [data.target]);
                        methods.resize.apply($this, [data.target]);
                    }
                    methods.clearautoflip();
                    if (!status.tindex) {
                        if (status.sns) {
                        	methods.closesns.apply($this, [$this]);
                        }
                        methods.opentindex.apply($this, [$this]);
                    } else {
                        methods.closetindex.apply($this, [$this]);
                    }
                    
                    return false;
                });
                $(document).on("vclick", '#tindex .close', function(e){
                	methods.closetindex.apply($this, [$this]);
                });
            }
            
            //メニューを閉じる
            //Anderson 20180822
            //$(data.target).on('vclick', selectors.menuclose, function(e){
            $(document).on('vclick', selectors.menuclose, function(e){
                $(selectors.menu).panel('close');
                return false;
            });
            
            on_no_window_scroll = function() {
                //ウィンドウ全体のスクロールを禁止
                $(window).on('touchmove.noScroll', function(e) {
                    e.preventDefault();
                });
            };
            off_no_window_scroll = function() {
                //ウィンドウ全体のスクロール禁止を解除
                $(window).off('.noScroll');
            };
            $('#dmxlivebook').on('pageshow', function(e){
                on_no_window_scroll();
            });
            $('#dmxlivebook').on('pagehide', function(e){
                off_no_window_scroll();
            });
            on_no_window_scroll();
            
        	methods.setpagenum.apply($this, [data.target]);
            methods.changebuttonstatus.apply($this, [data.target]);
            methods.changedisplaystatus.apply($this, [data.target]);
            
            //Web-CRM：ページビューログの送信（少ない側のページ数に統一する）
            webcrm_send_pageview(p - p % 2);
        },
        createcustommenu: function (){
            var t = arguments[0]; //target
            var html;
        	
            if (settings.device == 'pc') {
            }
        },
        createmenu: function (){
            var t = arguments[0]; //target
            var html;
            
            if (settings.device == 'pc') {
	            html = '<div id="controllbar">'
      			  	      + '<div id="menu_button_wrapper">'
      			  	      //+ '<div class="button" id="t_index"></div>'
      			  	      + '<div class="button" id="l_page"></div>'
      			  	      + '<div class="button" id="r_page"></div>'
      			  	      + '<div class="button" id="v_index"></div>'
        			      + '<div class="button" id="zoom_in"></div>'
            			  + '<div class="button" id="zoom_out"></div>'
      			  	      + '<div class="button" id="search_all"></div>'
      			  	      + '<div class="button" id="print_btn"></div>'
      			  	      + '<div class="button" id="bookmarktag_btn"></div>'
      			  	      //+ '<div class="button" id="memotool_btn"></div>'
      			  	      + '<input type="text" id="goto_text">'
      			  	      + '<div class="button" id="goto_submit"></div>'
      			  	      + '<div id="page_num"><span class="current"></span> / <span class="total"></span></div>'
  			  	  	      + '</div>'
	                      + '</div>';
	            t.append(html);
	            
	            if (params.sns.enabled) {
		            html = '<div id="leftmenubar">'
	            		 + '<div id="left_menu_wrapper">'
	            		 + '</div>'
	            		 + '</div>';
		            t.append(html);
		            
		            if (params.sns.facebook == 1) {
		            	$('#left_menu_wrapper', selectors.leftmenu).append($.snsdmx('facebook', 'button', location.href, status.curbasepage));
		            }
		            if (params.sns.twitter == 1) {
		            	$('#left_menu_wrapper', selectors.leftmenu).append($.snsdmx('twitter', 'button', location.href, status.curbasepage));
		            }
		            
		            $(selectors.leftmenu).height((params.sns.facebook + params.sns.twitter) * 35 - 5);
		            $('#left_menu_wrapper', selectors.leftmenu).height((params.sns.facebook + params.sns.twitter) * 35 - 5);
	            }
            } else {
	            //メニュー本体の追加
	            html = '<div id="menu" data-role="panel" data-display="overlay">'
	                      + '<div id="menu_close"><a href="#" class="hover_button trigger_menu trigger_menu_close"></a></div>';
	            
	            if (params.tindex.enabled)
	            	html += '<div id="menu_tindex"><a href="#" data-transition="fade" class="hover_button trigger_menu trigger_tindex"></a></div>';
	            
	            html += '<div id="menu_vindex"><a href="#" class="hover_button trigger_menu trigger_vindex"></a></div>'
	                      + '<div id="menu_changespread"><a href="#" class="hover_button trigger_menu trigger_changespread"></a></div>';
	            
	            if (params.sns.enabled)
	            	html += '<div id="menu_sns"><a href="#" class="hover_button trigger_menu trigger_sns"></a></div>';
              
            	html += '<div id="menu_footer"></div>'
	                      + '</div>';
	            t.append(html);
	            
	            $(selectors.menu).panel();
	            $(selectors.menu + ' ul').listview();
            }
        },
        createnombre: function (){
            var t = arguments[0]; //target
            var html;
            
            if (settings.device == 'pc') {
                html =  '<div class="pagenombre left" style="color: '+settings.disp_nombre_color+'"></div>'
                    + '<div class="pagenombre right" style="color: '+settings.disp_nombre_color+'"></div>';
	            $('#paper_frame', t).append(html);
            }
        },
        adjustnombre: function (ps){
        	if (ps == undefined) {
        		ps = params.pscale;
        	}
        	$(selectors.pagenombre).css({'font-size': (settings.disp_nombre_fontsize * params.basescale * ps) + 'px'});
        	$(selectors.pagenombre).css({'top': (settings.disp_nombre_postop * params.basescale * ps) + 'px'});
        	$(selectors.pagenombrel).css({'left': (settings.disp_nombre_posside * params.basescale * ps) + 'px'});
        	$(selectors.pagenombrer).css({'right': (settings.disp_nombre_posside * params.basescale * ps) + 'px'});
        },
        createtindexpage: function (){
            var t = arguments[0]; //target
            var html;
            
            //目次の追加
            if (settings.device == 'pc') {
				html = '<div id="tindex" class="dialog_wrapper bind_'+get_bind_group()+'">'
					+ '<div id="tindex_dialog" class="dialog">'
					+ '<div class="close"><a href="#">&times;</a></div>'
					+ '<div class="list_wrapper dialog_content">'
					+ '</div>'
					+ '</div>'
					+ '</div>';
				
				t.append(html);
            } else {
	            html = '<div id="tindex" data-role="page" data-url="tindex" class="bind_'+get_bind_group()+'">'
	                      + '<div data-role="header" data-position="fixed" data-add-back-btn="false" data-back-btn-text="戻る">'
	                      + '<span class="back-btn hover_button">戻る</span>'
	                      + '<H1>目次</h1>'
	                      + '</div>'
	                      + '<div data-role="content" role="main">'
	                      + '</div>'
	                      + '<div data-role="footer" data-position="fixed">'
	                      + '<h4>&nbsp;</h4>'
	                      + '</div>'
	                      + '</div>';
	            t.after(html).page();
	            $(selectors.tindex).on("pageshow", function(e){
	                if (params.tindex.loaded === false) {
	                    params.tindex.loading = true;
	                    $.mobile.loading('show', {
	                        text: 'ロード中',
	                        textVisible: true,
	                        textonly: false
	                    });
	                    methods.loadtindexdata.apply($(this), [t]);
	                }
	                
	                if (status.vindex) {
	                    methods.closevindex.apply($(this), [t]);
	                } else if (status.sns) {
	                	methods.closesns.apply($(this), [t]);
	                }
	                
	                $('.ui-content', this).height(
	                    $(window).innerHeight() - $('.ui-header', this).outerHeight() - $('.ui-footer', this).outerHeight() - ($('.ui-content', this).innerHeight() - $('.ui-content', this).height())
	                );
	            });
            }
        },
        createtindexlist: function (){
            var t = arguments[0]; //target
            var d = arguments[1]; //data
            var html;
            
            if (settings.device == 'pc') {
	            html = '<ul>';
	            for (var i = 0; i < d.length; i++) {
	                html += '<li><a href="#" data-type="'+DEF_PARAMS.LINKTYPE.PAGE+'" data-link="'+d[i].page+'" data-target="_self">'+d[i].text+'</a></li>';
	            }
	            html += '</ul>';
	            
	            $('.list_wrapper', selectors.tindex).append(html);
	        } else {
	            html = '<ul data-role="listview">';
	            for (var i = 0; i < d.length; i++) {
	                html += '<li><a href="#" data-type="'+DEF_PARAMS.LINKTYPE.PAGE+'" data-link="'+d[i].page+'" data-target="_self">'+d[i].text+'</a></li>';
	            }
	            html += '</ul>';
	            
	            $(selectors.tindex + ' div[role=main]').append(html);
	            $(selectors.tindex + ' ul').listview();
            }
            
            params.tindex.loaded = true;
            params.tindex.enabled = true;
        },
        opentindex: function (){
            var t = arguments[0]; //target
            
            status.tindex = true;
            $(selectors.tindex).animate({'opacity': 'show'}, {'duration': 500});
        },
        closetindex: function (){
            var t = arguments[0]; //target
            
            status.tindex = false;
            $(selectors.tindex).animate({'opacity': 'hide'}, {'duration': 500});
        },
        createprintpage: function (){
            var t = arguments[0]; //target
            var html;
            
            if (settings.device == 'pc') {
	            //ビジュアル目次の追加
	            html = '<div id="print" class="dialog_wrapper bind_'+get_bind_group()+'">'
	                 + '<div id="print_dialog" class="dialog">'
                     + '<div class="close"><a href="#">&times;</a></div>'
	                 + '<div class="print_wrapper dialog_content">'
	                 + '<a href="#" target="_blank" class="l title">'+DEF_PARAMS.LANG.PRINT.LEFT+'</a>'
	                 + '<a href="#" target="_blank" class="l icon">'+DEF_PARAMS.LANG.PRINT.LEFT+'</a>'
	                 + '<a href="#" target="_blank" class="r title">'+DEF_PARAMS.LANG.PRINT.RIGHT+'</a>'
	                 + '<a href="#" target="_blank" class="r icon">'+DEF_PARAMS.LANG.PRINT.RIGHT+'</a>'
	                 + '</div>'
	                 + '</div>'
	                 + '</div>';
            }
            t.append(html);
            
            methods.setprinthref.apply(t, [t]);
        },
        setprinthref: function (){
            var t = arguments[0]; //target
            var html;
            
            if (settings.device == 'pc') {
	            //ビジュアル目次の追加
            	var lname = '';
            	var rname = '';
            	
            	switch (settings.pageaction) {
	                case DEF_PARAMS.PAGEACTION.RIGHT:
	                case DEF_PARAMS.PAGEACTION.UPPER:
	                case DEF_PARAMS.PAGEACTION.RSLIDE:
	                    
	            		lname = ('0000' + (status.curbasepage + 1)).substr(-4);
	            		rname = ('0000' + status.curbasepage).substr(-4);
	            		
	                    break;
	                case DEF_PARAMS.PAGEACTION.LEFT:
	                case DEF_PARAMS.PAGEACTION.LOWER:
	                case DEF_PARAMS.PAGEACTION.LSLIDE:
	                    
	            		lname = ('0000' + status.curbasepage).substr(-4);
	            		rname = ('0000' + (status.curbasepage + 1)).substr(-4);
	                	
	                    break;
	            }
            	
            	$('#print_dialog a.l', selectors.print).attr('href', 'pdf/'+lname+'.pdf');
            	$('#print_dialog a.r', selectors.print).attr('href', 'pdf/'+rname+'.pdf');
            }
        },
        openprint: function (){
            var t = arguments[0]; //target
            
            status.print = true;
            $(selectors.print).animate({'opacity': 'show'}, {'duration': 500});
        },
        closeprint: function (){
            var t = arguments[0]; //target
            
            status.print = false;
            $(selectors.print).animate({'opacity': 'hide'}, {'duration': 500});
        },
        createvindexpage: function (){
            var t = arguments[0]; //target
            var html;
            
            if (settings.device == 'pc') {
	            //ビジュアル目次の追加
	            html = '<div id="vindex" class="dialog_wrapper bind_'+get_bind_group()+'">'
	                 + '<div id="vindex_dialog" class="dialog">'
                     + '<div class="close"><a href="#">&times;</a></div>'
	                 + '<div class="thumbnail_wrapper dialog_content">'
	                 + '</div>'
	                 + '</div>'
	                 + '</div>';
            } else {
	            //ビジュアル目次の追加
	            html = '<div id="vindex" class="bind_'+get_bind_group()+'">'
	                 + '</div>';
            }
            t.append(html);
        },
        createvindexlist: function (){
            var t = arguments[0]; //target
            var html = '';
            var imgw = 100;
            var c = params.lastpage + 1;
            var d, w, p;
            
            // デバイスピクセル比を考慮
            //if (window.devicePixelRatio)
            //    imgw *= window.devicePixelRatio;
            
            if (get_bind_group() === DEF_PARAMS.PAGEGROUP.RIGHT) {
                d = new Array('r', 'l');
            } else {
                d = new Array('l', 'r');
            }
            
            w = 0;
            p = false;
            
            if (settings.device == 'pc') {
	            for (var i = 0; i <= params.lastpage; i++) {
	                if (i % 2 == 0) {
	                    p = true;
	                    html += '<div class="thumb_spread_wrapper">';
	                }
	                
	                if (is_blankpage(i, params.lastpage, settings.h0_invisible, settings.h5_invisible) && i != 0) {
	                    c--;
	                    continue;
	                }
	                
	                html += '<div class="thumb_wrapper thumb_'+d[i % 2]+' page_'+i+'">';
	                html += '<a href="#" data-type="'+DEF_PARAMS.LINKTYPE.PAGE+'" data-link="'+i+'" data-target="_self"><img src="'+params.pageimgdir+params.pageimgprefix+'__dmx__' + i + '__50.jpg" style="width: '+imgw+'px;"></a>';
	                html += '</div>';
	                
	                if (i % 2 == 1) {
	                    p = false;
	                    html += '</div>';
	                }
	            }
	            if (p === true) {
	                p = false;
	                html += '</div>';
	            }
	            
	            params.vindex.loaded = true;
	            params.vindex.enabled = true;
	            
	            $('.thumbnail_wrapper', selectors.vindex).append(html);
            } else {
	            html = '<div class="thumblist_wrapper"><div class="contents">';
	            for (var i = 0; i <= params.lastpage; i++) {
	                if (i % 2 == 0) {
	                    p = true;
	                    html += '<div class="thumb_spread_wrapper">';
	                }
	                
	                if (is_blankpage(i, params.lastpage, settings.h0_invisible, settings.h5_invisible)) {
	                    c--;
	                    continue;
	                }
	                
	                html += '<div class="thumb_wrapper thumb_'+d[i % 2]+' page_'+i+'">';
	                html += '<a href="#" data-type="'+DEF_PARAMS.LINKTYPE.PAGE+'" data-link="'+i+'" data-target="_self"><img src="'+params.pageimgdir+params.pageimgprefix+'__dmx__' + i + '__50.jpg" style="width: '+imgw+'px;"></a>';
	                html += '</div>';
	                
	                if (i % 2 == 1) {
	                    p = false;
	                    html += '</div>';
	                }
	            }
	            if (p === true) {
	                p = false;
	                html += '</div>';
	            }
	            html += '</div></div><div class="wrap_bg"></div><div class="bg"></div>';
	            
	            params.vindex.loaded = true;
	            params.vindex.enabled = true;
	            
	            $(selectors.vindex).append(html);
	            
	            params.vindex.width = 101 * c + $(selectors.vindexspread).length * 10;
	            $(selectors.vindexcontent).width(params.vindex.width);
	            $(selectors.vindexfg).height(params.thumb_h + 30);
	            
	            methods.resetvindexposition.apply(t, [t]);
            }
        },
        resetvindexposition: function (){
            var t = arguments[0]; //target
            var cp = status.curbasepage;
            
            if (settings.h0_invisible && cp === 0) {
                cp++;
            }
            if ($(selectors.vindexpage+'.page_'+cp).length) {
                set_thumb_position_by_page(cp);
            }
        },
        openvindex: function (){
            var t = arguments[0]; //target
            
            status.vindex = true;
            $(selectors.vindex).animate({'opacity': 'show'}, {'duration': 500});
        },
        closevindex: function (){
            var t = arguments[0]; //target
            
            status.vindex = false;
            $(selectors.vindex).animate({'opacity': 'hide'}, {'duration': 500});
        },
        createsearchallpage: function (){
            var t = arguments[0]; //target
            var html;
            
            if (settings.device == 'pc') {
	            html = '<div id="searchall" class="bind_'+get_bind_group()+'">'
	                 + '<div id="searchall_dialog">'
                     + '<div class="close"><a href="#">&times;</a></div>'
                     + '<input type="text" id="search_keyword">'
                     + '<div id="search_mode_and"><input type="radio" name="search_mode" value="and" checked="checked" />and</div>'
                     + '<div id="search_mode_or"><input type="radio" name="search_mode" value="or" />or</div>'
                     + '<div id="search_btn"><a href="#">'+DEF_PARAMS.LANG.SEARCHALL.SEARCH+'</a></div>'
                     + '<div id="search_branch_wrapper"><span>'+DEF_PARAMS.LANG.SEARCHALL.BRANCH+'</span>'
                     + '<div id="search_branch_openall"><a href="#">ON</a></div>'
                     + '<div id="search_branch_closeall"><a href="#">OFF</a></div>'
                     + '</div>'
                     + '<div id="search_clear_result"><a href="#">▼'+DEF_PARAMS.LANG.SEARCHALL.RESET+'</a></div>'
                     + '<div id="search_sortorder_hit" class="active"><a href="#">'+DEF_PARAMS.LANG.SEARCHALL.SORTHIT+'</a></div>'
                     + '<div id="search_sortorder_page"><a href="#">'+DEF_PARAMS.LANG.SEARCHALL.SORTPAGE+'</a></div>'
                     + '<div id="search_result_info">'+DEF_PARAMS.LANG.SEARCHALL.RESULT+'：<span></span></div>'
                     + '<div id="search_result_content" class="searchlist_wrapper">'
	                 + '</div>'
	                 + '</div>'
	                 + '</div>';
            } else {
	            html = '';
            }
            t.append(html);
        
            params.searchall.enabled = true;
        },
        loadsearchalldata: function (){
            var t = arguments[0]; //target
            var html, load_cnt;
            
            if (params.searchall.dataloading === false && params.searchall.dataloaded === false) {
            	
            	params.searchall.dataloading = true;
            	
            	$.ajax({
	                type: "GET",
	                url: "data/search_key.csv",
	                dataType: "text",
	                success: function(stringdata, type) {
	                    var strings = stringdata.split("\r\n");
	                    params.searchall.search_key = strings;
	                    
	                    params.searchall.dataloadcnt++;
	                    
	                    if (params.searchall.dataloadcnt == 2) {
	                    	params.searchall.dataloaded = true;
	                    	$('#search_btn').click();
	                    }
	                },
	                error: function (r, s, e){
	                },
	                complete: function (r, e){
	                }
	            });
	            $.ajax({
	                type: "GET",
	                url: "data/search_pnt.csv",
	                dataType: "text",
	                success: function(coorddata, type) {
	                    var coords = $.csv(",", '"', "\r\n")(coorddata);
	                    params.searchall.search_pnt = coords;
	                    
	                    params.searchall.dataloadcnt++;
	                    
	                    if (params.searchall.dataloadcnt == 2) {
	                    	params.searchall.dataloaded = true;
	                    	$('#search_btn').click();
	                    }
	                },
	                error: function (r, s, e){
	                },
	                complete: function (r, e){
	                }
	            });
            }
            
	            
        },
        createsearchresult: function (){
            var t = arguments[0]; //target
            var _match = arguments[1];
            var _sortmode = arguments[2];
            
            searchResults = new Array();

            $('#search_result_info span').text('');

            $("#search_result_content").empty();

            $(".search_result_region_rect").remove();

            var html;
            if (_match.length >= 1) {
                $("#search_result_content").append('<ul id="treenavi"></ul>');
                $("#search_result_info span").text(format_lang(DEF_PARAMS.LANG.SEARCHALL.HIT, _match.length));

                // sort : page number
                if (_sortmode == "page") {
                    for (var it in searchMatchIndexes) {
                        var m = it.match('page_([0-9]+)');
                        var page = parseInt(m[1]) - 1;

                        html = '<li class="result_wrapper"><a href="#">';
                        html += '<span class="page">p ' + getPageLabel(page) + "</span>";
                        html += '<span class="hitstring">' + format_lang(DEF_PARAMS.LANG.SEARCHALL.HIT, searchMatchPageHitNums[it]) + '</span>';
                        html += '</a><ul>';

                        for (var i = 0, l = searchMatchIndexes[it].length; i < l; i++) {
                            var j = searchMatchIndexes[it][i];
                            var page = parseInt(_match[j]["page"]) - 1;

                            html += '<li class="result"><a href="#" data-type="'+DEF_PARAMS.LINKTYPE.PAGE+'" data-link="'+getGoToPageNum(page)+'" data-target="_self">';
                            html += '<span class="string">' + _match[j]["txt"] + '</span>';
                            html += '</a></li>';
                        }

                        html += '</ul></li>';

                        $("#search_result_content #treenavi").append(html);
                    }
                } else {
                    for (var it in searchMatchPageHitNums) {
                        var m = it.match('page_([0-9]+)');
                        var page = parseInt(m[1]) - 1;

                        html = '<li class="result_wrapper"><a href="#">';
                        html += '<span class="page">p ' + getPageLabel(page) + "</span>";
                        html += '<span class="hitstring">' + format_lang(DEF_PARAMS.LANG.SEARCHALL.HIT, searchMatchPageHitNums[it]) + '</span>';
                        html += '</a><ul>';

                        for (var i = 0, l = searchMatchIndexes[it].length; i < l; i++) {
                            var j = searchMatchIndexes[it][i];
                            var page = parseInt(_match[j]["page"]) - 1;

                            html += '<li class="result"><a href="#" data-type="'+DEF_PARAMS.LINKTYPE.PAGE+'" data-link="'+getGoToPageNum(page)+'" data-target="_self">';
                            html += '<span class="string">' + _match[j]["txt"] + '</span>';
                            html += '</a></li>';
                        }

                        html += '</ul></li>';

                        $("#search_result_content #treenavi").append(html);
                    }
                }

                $("#treenavi").treeview({
                    persist: "location",
                    control: "#search_branch_wrapper"
                });
                
                $(".page .search_rect").remove();
                
                if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                    methods.createsearchrect.apply($(this), [t, status.curbasepage]);
                    methods.createsearchrect.apply($(this), [t, status.curbasepage + 1]);
                } else {
                    methods.createsearchrect.apply($(this), [t, status.curbasepage]);
                }
            } else {
                $("#search_result_info span").text(DEF_PARAMS.LANG.SEARCHALL.NOHIT);
            }
        },
        opensearchall: function (){
            var t = arguments[0]; //target
            
            status.searchall = true;
            $(selectors.searchall).animate({'opacity': 'show'}, {'duration': 500});
        },
        closesearchall: function (){
            var t = arguments[0]; //target
            
            status.searchall = false;
            $(selectors.searchall).animate({'opacity': 'hide'}, {'duration': 500});
        },
        createsnspage: function (){
            var t = arguments[0]; //target
            var html;
            
            //SNSの追加
            html = '<div id="sns" class="bind_'+get_bind_group()+'">'
                 + '</div>';
            t.append(html);
        },
        createsnslist: function (){
            var t = arguments[0]; //target
            var html;
            
            html = '<div class="snslist_wrapper"><div class="contents">';
            
            //html += '<script>(function(d, s, id) {';
            //html += '  var js, fjs = d.getElementsByTagName(s)[0];';
        	//html += '  if (d.getElementById(id)) return;';
    		//html += '  js = d.createElement(s); js.id = id;';
			//html += '  js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.5";';
			//html += '  fjs.parentNode.insertBefore(js, fjs);';
			//html += '}(document, \'script\', \'facebook-jssdk\'));</script>';
            //html += '<div class="fb-share-button share-button" data-href="http://www.digitalmax.jp/" data-layout="link">Facebook</div>';
            html += $.snsdmx('facebook', 'button', location.href, status.curbasepage);
            
            //html += '<script>window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));</script>';
            //html += '<a class="twitter-share-button share-button" href="https://twitter.com/share" data-count="none" data-dnt="true"></a>';
            html += $.snsdmx('twitter', 'button', location.href, status.curbasepage);
            
            html += '</div></div>';
            html += '<div class="wrap_bg"><div class="close">×</div></div><div class="bg"></div>';
            
            params.sns.loaded = true;
            
            $(selectors.sns).append(html);
        },
        refreshsnslist: function (){
            var t = arguments[0]; //target
            var html;
            
            $.snsdmx('facebook', 'refreshhref', location.href, status.curbasepage);
            $.snsdmx('twitter', 'refreshhref', location.href, status.curbasepage);
        },
        opensns: function (){
            var t = arguments[0]; //target
            
            status.sns = true;
            $(selectors.sns).animate({'opacity': 'show'}, {'duration': 500});
        },
        closesns: function (){
            var t = arguments[0]; //target
            
            status.sns = false;
            $(selectors.sns).animate({'opacity': 'hide'}, {'duration': 500});
        },
        creatememotool: function (){
            var t = arguments[0]; //target
            var html;
            
            if (settings.device == 'pc') {
	            html = '<div id="memotool" class="dialog_wrapper">'
	                 + '<div id="memotool_dialog" class="dialog">'
                     + '<div class="close"><a href="#">&times;</a></div>'
                     + '<div class="tool_wrapper dialog_content">'
                     + '<div class="color" data-color="#ffffc0" style="background-color: #ffffc0;"></div>'
                     + '<div class="color" data-color="#c0ffc0" style="background-color: #c0ffc0;"></div>'
                     + '<div class="color" data-color="#c0ffff" style="background-color: #c0ffff;"></div>'
                     + '<div class="color" data-color="#c0c0ff" style="background-color: #c0c0ff;"></div>'
                     + '<div class="color" data-color="#ffc0ff" style="background-color: #ffc0ff;"></div>'
                     + '</div>'
                     + '<div class="memolist_btn">'+DEF_PARAMS.LANG.MEMO.MEMOLIST+'</div>'
	                 + '</div>'
	                 + '</div>';
            } else {
	            html = '';
            }
            t.append(html);
        
            params.memo.tool.created = true;
        },
        openmemotool: function (){
            var t = arguments[0]; //target
            
            status.memotool = true;
            $(selectors.memotool).animate({'opacity': 'show'}, {'duration': 500});
        },
        closememotool: function (){
            var t = arguments[0]; //target
            
            status.memotool = false;
            $(selectors.memotool).animate({'opacity': 'hide'}, {'duration': 500});
        },
        creatememolistpage: function (){
            var t = arguments[0]; //target
            var html;
            
            //目次の追加
            if (settings.device == 'pc') {
				html = '<div id="memolist" class="dialog_wrapper bind_'+get_bind_group()+'" style="display: none;">'
					+ '<div id="memolist_dialog" class="dialog">'
					+ '<div class="close"><a href="#">&times;</a></div>'
					+ '<div class="list_wrapper dialog_content"><ul>'
					+ '</ul></div>'
					+ '</div>'
					+ '</div>';
				
				t.append(html);
				
				params.memo.list.created = true;
            }
        },
        addmemolist: function (memo_id){
            var html;
            var d, p, x, y, w, h, c, s;
            
            if (settings.device == 'pc') {
            	p = memoObj[memo_id].page;
            	c = memoObj[memo_id].color;
            	content = memoObj[memo_id].content;
            	
                html = '<li data-memo_id="'+memo_id+'" data-type="'+DEF_PARAMS.LINKTYPE.PAGE+'" data-link="'+p+'">'
                     + '<div class="color" style="background-color: '+c+'"><span></span></div>'
                     + '<div class="page">P'+pad0str(p)+'</div>'
                     + '<div class="content">'+content+'</div>'
                     + '</li>';
	            
	            $('.list_wrapper ul', selectors.memolist).append(html);
            }
        },
        changememolist: function (memo_id, content){
            var html;
            var d, p, x, y, w, h, c, s;
            
            if (settings.device == 'pc') {
            	$('.list_wrapper li[data-memo_id='+memo_id+'] div.content', selectors.memolist).text(content);
            }
        },
        delmemolist: function (memo_id){
            var html;
            var d, p, x, y, w, h, c, s;
            
            if (settings.device == 'pc') {
	            $('.list_wrapper li[data-memo_id='+memo_id+']', selectors.memolist).remove();
            }
        },
        openmemolist: function (){
            var t = arguments[0]; //target
            
            status.memolist = true;
            $(selectors.memolist).animate({'opacity': 'show'}, {'duration': 500});
        },
        closememolist: function (){
            var t = arguments[0]; //target
            
            status.memolist = false;
            $(selectors.memolist).animate({'opacity': 'hide'}, {'duration': 500});
        },
        savememo: function (){
        	$.cookie('memo', memoObj, {expires: 36500});
        },
        loadmemo: function(){
            var html, page_num;
            
            memoObj = $.cookie('memo');
            if (!memoObj)
            	memoObj = {};

            for (var it in memoObj) {
                params.memo.target = $.extend(true, {}, memoObj[it]);
                params.memo.target.id = it;
                methods.creatememo('load');
                methods.addmemolist(it);
            }
            
            methods.displaycurpagememo();
        },
        creatememo: function (v){
            var html;
            var memo_id;
            
            if (settings.device == 'pc') {
            	var cdate = new Date();
            	var adjust = 0;
            	var d, p, x, y, w, h, c, s, content;
            	var vx, vy, vw, vh;
            	
            	if (params.memo.target.id == null) {
            		params.memo.target.id = 'memo_'+cdate.getTime();
            	}
            	if (params.memo.target.dir == 'r') {
            		adjust = params.media_w;
            	}
            	
            	d = 'block';
            	if (v == 'hide' || v == 'load') {
            		d = 'none';
            	}
            	
            	memo_id = params.memo.target.id;
            	p = params.memo.target.page;
            	x = params.memo.target.x;
            	y = params.memo.target.y;
            	w = params.memo.target.width;
            	h = params.memo.target.height;
            	c = params.memo.target.color;
            	s = params.memo.target.status;
            	content = params.memo.target.content;
            	
            	vy = y * (params.pscale * params.basescale);
            	vx = x * (params.pscale * params.basescale);
            	vw = w * (params.pscale * params.basescale);
            	vh = h * (params.pscale * params.basescale);
            	
	            html = '<div class="memo_area" rel="'+p+'" id="'+memo_id+'"'
	            	 + ' style="top:'+vy+'px;left:'+(adjust + vx)+'px;display:'+d+'"'
	            	 + ' data-pos="'+x+','+y+','+w+','+h+'">'
	                 + '<div class="memo_dialog dialog" style="width:'+vw+'px;height:'+vh+'px;background-color:'+c+'">'
	                 + '<div class="minimize"><a href="#"></a></div>'
                     + '<div class="close"><a href="#">&times;</a></div>'
                     + '<div class="resize"><a href="#"></a></div>'
                     + '<textarea class="content_wrapper dialog_content" style="width:'+(vw - 30)+'px;height:'+(vh - 50)+'px">'+content+'</textarea>'
                     + '</div>'
                     + '<div class="normalize minimize_icon"></div>'
                     + '</div>'
	                 + '</div>';
	            
	            if (v != 'load') {
	            	memoObj[memo_id] = $.extend(true, {}, params.memo.target);
	            }
	        } else {
	            html = '';
            }
            $('#paper_frame').append(html);
            
            if (memo_id) {
            	return memo_id;
            } else {
            	return false;
            }
        },
        memomovestart: function($this, e, originalEvent) {
            if (status.flipping === false && status.sliding === false && status.changespread === false &&
                    status.zoommode === false && status.zoomdrag == false && status.memodrag === false) {
                var x = originalEvent.pageX,
                    y = originalEvent.pageY;
                
                params.memo.obj = e.target;
                methods.memodragstart.apply($this, [$this, e.target, x, y]);
            }
        },
        memomovechange: function($this, e, originalEvent) {
            if (status.flipping === false && status.sliding === false && status.changespread === false &&
                    status.zoommode === false && status.zoomdrag == false && status.memomove === true) {
                var x = originalEvent.pageX,
                    y = originalEvent.pageY;
                    
                methods.memodragmove.apply($this, [$this, params.memo.obj, x, y]);
            }
        },
        memomoveend: function($this, e, originalEvent) {
            if (status.flipping === false && status.sliding === false && status.changespread === false &&
                    status.zoommode === false && status.zoomdrag == false && status.memodrag === true) {
                methods.memodragterminate.apply($this, [$this, params.memo.obj]);
            }
        },
        memodragstart: function () {
            var $this = arguments[0];
            var t = arguments[1]; //target .memo_dialog
            var x = arguments[2]; //pos x
            var y = arguments[3]; //pos y
            
            status.memodrag = true;
            params.memo.drag.sx = params.memo.drag.cx = x;
            params.memo.drag.sy = params.memo.drag.cy = y;
        },
        memodragmove: function () {
            var $this = arguments[0];
            var t = arguments[1]; //target
            var x = arguments[2]; //pos x
            var y = arguments[3]; //pos y
            var dx = x - params.memo.drag.sx,
                dy = y - params.memo.drag.sy;
            var d;
            
            var memo_id = $(t).parents('.memo_area').attr('id');
    		var p = $('#'+memo_id).attr('rel');
            var points = $('#'+memo_id).data('pos').split(',');
            var pos_x = parseInt(points[0]);
            var pos_y = parseInt(points[1]);
            var pos_w = parseInt(points[2]);
            var pos_h = parseInt(points[3]);
            
        	var tvy = pos_y * (params.pscale * params.basescale);
        	var tvx = pos_x * (params.pscale * params.basescale);
        	var tvw = pos_w * (params.pscale * params.basescale);
        	var tvh = pos_h * (params.pscale * params.basescale);
            
        	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
            	switch (settings.pageaction) {
	                case DEF_PARAMS.PAGEACTION.RIGHT:
	                case DEF_PARAMS.PAGEACTION.UPPER:
	                case DEF_PARAMS.PAGEACTION.RSLIDE:
	                    
	                	if (p % 2 == 0) {
	                		tvx += params.media_w;
	                	}
	                	
	                    break;
	                case DEF_PARAMS.PAGEACTION.LEFT:
	                case DEF_PARAMS.PAGEACTION.LOWER:
	                case DEF_PARAMS.PAGEACTION.LSLIDE:
	                    
	                	if (p % 2 == 1) {
	                		tvx += params.media_w;
	                	}
	                	
	                    break;
	            }
        	} else {
        		
        	}
        	
        	var vx = tvx + dx;
        	var vy = tvy + dy;
        	var mw, mh;
        	
        	if (memoObj[memo_id].status == 'min') {
        		mw = 16;
        		mh = 22;
        	} else {
        		mw = $('#'+memo_id+' .memo_dialog').width();
        		mh = $('#'+memo_id+' .memo_dialog').height();
        	}
        	
        	if (vx < 0) {
        		vx = 0;
        	} else if (params.paper_w < vx + mw) {
        		vx = params.paper_w - mw;
        	}
        	if (vy < 0) {
        		vy = 0;
        	} else if (params.paper_h < vy + mh) {
        		vy = params.paper_h - mh;
        	}
        	
    		$('#'+memo_id).css({
    			'top': vy+'px',
    			'left': vx+'px'
    		});
            
            params.memo.drag.cx = x;
            params.memo.drag.cy = y;
        },
        memodragterminate: function () {
            var $this = arguments[0];
            var t = arguments[1]; //target
            var dx = params.memo.drag.cx - params.memo.drag.sx, //diff x
                dy = params.memo.drag.cy - params.memo.drag.sy; //diff y
            var cp = status.curbasepage;
            
            var memo_id = $(t).parents('.memo_area').attr('id');
    		var p = $('#'+memo_id).attr('rel');
            var points = $('#'+memo_id).data('pos').split(',');
            var pos_x = parseInt(points[0]);
            var pos_y = parseInt(points[1]);
            var pos_w = parseInt(points[2]);
            var pos_h = parseInt(points[3]);
            
        	var tvy = pos_y * (params.pscale * params.basescale);
        	var tvx = pos_x * (params.pscale * params.basescale);
        	var tvw = pos_w * (params.pscale * params.basescale);
        	var tvh = pos_h * (params.pscale * params.basescale);
            
        	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
            	switch (settings.pageaction) {
	                case DEF_PARAMS.PAGEACTION.RIGHT:
	                case DEF_PARAMS.PAGEACTION.UPPER:
	                case DEF_PARAMS.PAGEACTION.RSLIDE:
	                    
	                	if (p % 2 == 0) {
	                		tvx += params.media_w;
	                	}
	                	
	                    break;
	                case DEF_PARAMS.PAGEACTION.LEFT:
	                case DEF_PARAMS.PAGEACTION.LOWER:
	                case DEF_PARAMS.PAGEACTION.LSLIDE:
	                    
	                	if (p % 2 == 1) {
	                		tvx += params.media_w;
	                	}
	                	
	                    break;
	            }
        	} else {
        		
        	}
        	
        	var vx = tvx + dx;
        	var vy = tvy + dy;
        	var mw, mh;
        	
        	if (memoObj[memo_id].status == 'min') {
        		mw = 16;
        		mh = 22;
        	} else {
        		mw = $('#'+memo_id+' .memo_dialog').width();
        		mh = $('#'+memo_id+' .memo_dialog').height();
        	}
        	
        	if (vx < 0) {
        		vx = 0;
        	} else if (params.paper_w < vx + mw) {
        		vx = params.paper_w - mw;
        	}
        	if (vy < 0) {
        		vy = 0;
        	} else if (params.paper_h < vy + mh) {
        		vy = params.paper_h - mh;
        	}
        	
        	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
            	switch (settings.pageaction) {
	                case DEF_PARAMS.PAGEACTION.RIGHT:
	                case DEF_PARAMS.PAGEACTION.UPPER:
	                case DEF_PARAMS.PAGEACTION.RSLIDE:
	                    
	                	if (p % 2 == 0) { // 右ページのメモ
	                		if (params.media_w <= vx) { // 右ページのまま
	                			vx -= params.media_w;
	                		} else { // 左ページに移動した場合
	                			p++;
	                		}
	                	} else { // 左ページのメモ
	                		if (params.media_w <= vx) { // 右ページに移動した場合
	                			vx -= params.media_w;
	                			p--;
	                		}
	                	}
	                	
	                    break;
	                case DEF_PARAMS.PAGEACTION.LEFT:
	                case DEF_PARAMS.PAGEACTION.LOWER:
	                case DEF_PARAMS.PAGEACTION.LSLIDE:
	                    
	                	if (p % 2 == 0) { // 左ページのメモ
	                		if (params.media_w <= vx) { // 右ページに移動した場合
	                			vx -= params.media_w;
	                			p++;
	                		}
	                	} else { // 右ページのメモ
	                		if (params.media_w <= vx) { // 右ページのまま
	                			vx -= params.media_w;
	                		} else { // 左ページに移動した場合
	                			p--;
	                		}
	                	}
	                	
	                    break;
	            }
        	} else {
        		
        	}
        	
        	pos_x = vx / (params.pscale * params.basescale);
        	pos_y = vy / (params.pscale * params.basescale);
        	
        	var posstr = pos_x+','+pos_y+','+pos_w+','+pos_h;
        	$('#'+memo_id).data('pos', posstr).attr('data-pos', posstr);
        	$('#'+memo_id).attr('rel', p);
        	
        	memoObj[memo_id].x = pos_x;
        	memoObj[memo_id].y = pos_y;
        	memoObj[memo_id].page = p;
        	
        	methods.savememo();
        	
            status.memodrag = false;
            params.memo.drag.sx = params.memo.drag.cx = 0;
            params.memo.drag.sy = params.memo.drag.cy = 0;
            
            params.memo.obj = null
        },
        memoresizestart: function($this, e, originalEvent) {
            if (status.flipping === false && status.sliding === false && status.changespread === false &&
                    status.zoommode === false && status.zoomdrag == false && status.memoresizedrag === false) {
                var x = originalEvent.pageX,
                    y = originalEvent.pageY;
                
                params.memo.resizeobj = e.target;
                methods.memoresizedragstart.apply($this, [$this, e.target, x, y]);
            }
        },
        memoresizechange: function($this, e, originalEvent) {
            if (status.flipping === false && status.sliding === false && status.changespread === false &&
                    status.zoommode === false && status.zoomdrag == false && status.memoresize === true) {
                var x = originalEvent.pageX,
                    y = originalEvent.pageY;
                    
                methods.memoresizedragmove.apply($this, [$this, params.memo.resizeobj, x, y]);
            }
        },
        memoresizeend: function($this, e, originalEvent) {
            if (status.flipping === false && status.sliding === false && status.changespread === false &&
                    status.zoommode === false && status.zoomdrag == false && status.memoresizedrag === true) {
                methods.memoresizedragterminate.apply($this, [$this, params.memo.resizeobj]);
            }
        },
        memoresizedragstart: function () {
            var $this = arguments[0];
            var t = arguments[1]; //target .memo_dialog
            var x = arguments[2]; //pos x
            var y = arguments[3]; //pos y
            
            status.memoresizedrag = true;
            params.memo.resizedrag.sx = params.memo.resizedrag.cx = x;
            params.memo.resizedrag.sy = params.memo.resizedrag.cy = y;
        },
        memoresizedragmove: function () {
            var $this = arguments[0];
            var t = arguments[1]; //target
            var x = arguments[2]; //pos x
            var y = arguments[3]; //pos y
            var dx = x - params.memo.resizedrag.sx,
                dy = y - params.memo.resizedrag.sy;
            var d;
            
            var memo_id = $(t).parents('.memo_area').attr('id');
    		var p = $('#'+memo_id).attr('rel');
            var points = $('#'+memo_id).data('pos').split(',');
            var pos_x = parseInt(points[0]);
            var pos_y = parseInt(points[1]);
            var pos_w = parseInt(points[2]);
            var pos_h = parseInt(points[3]);
            
        	var vy = pos_y * (params.pscale * params.basescale);
        	var vx = pos_x * (params.pscale * params.basescale);
        	var tvw = pos_w * (params.pscale * params.basescale);
        	var tvh = pos_h * (params.pscale * params.basescale);
            
        	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
            	switch (settings.pageaction) {
	                case DEF_PARAMS.PAGEACTION.RIGHT:
	                case DEF_PARAMS.PAGEACTION.UPPER:
	                case DEF_PARAMS.PAGEACTION.RSLIDE:
	                    
	                	if (p % 2 == 0) {
	                		vx += params.media_w;
	                	}
	                	
	                    break;
	                case DEF_PARAMS.PAGEACTION.LEFT:
	                case DEF_PARAMS.PAGEACTION.LOWER:
	                case DEF_PARAMS.PAGEACTION.LSLIDE:
	                    
	                	if (p % 2 == 1) {
	                		vx += params.media_w;
	                	}
	                	
	                    break;
	            }
        	} else {
        		
        	}
        	
        	var vw = tvw + dx;
        	var vh = tvh + dy;
        	var mw, mh;
        	mw = vw;
    		mh = vh;
        	
    		var tpos_w = vw / (params.pscale * params.basescale);
        	var tpos_h = vh / (params.pscale * params.basescale);
            
        	if (tpos_w < 150) {
        		vw = 150 * (params.pscale * params.basescale);
        	} else if (params.paper_w < vx + mw) {
        		vw = params.paper_w - vx;
        	}
        	if (tpos_h < 150) {
        		vh = 150 * (params.pscale * params.basescale);
        	} else if (params.paper_h < vy + mh) {
        		vh = params.paper_h - vy;
        	}
        	
        	$('#'+memo_id+' .memo_dialog').width(vw).height(vh);
    		$('#'+memo_id+' .content_wrapper').width(vw - 30).height(vh - 50);
            
            params.memo.resizedrag.cx = x;
            params.memo.resizedrag.cy = y;
        },
        memoresizedragterminate: function () {
            var $this = arguments[0];
            var t = arguments[1]; //target
            var dx = params.memo.resizedrag.cx - params.memo.resizedrag.sx, //diff x
                dy = params.memo.resizedrag.cy - params.memo.resizedrag.sy; //diff y
            var cp = status.curbasepage;
            
            var memo_id = $(t).parents('.memo_area').attr('id');
    		var p = $('#'+memo_id).attr('rel');
            var points = $('#'+memo_id).data('pos').split(',');
            var pos_x = parseInt(points[0]);
            var pos_y = parseInt(points[1]);
            var pos_w = parseInt(points[2]);
            var pos_h = parseInt(points[3]);
            
        	var vy = pos_y * (params.pscale * params.basescale);
        	var vx = pos_x * (params.pscale * params.basescale);
        	var tvw = pos_w * (params.pscale * params.basescale);
        	var tvh = pos_h * (params.pscale * params.basescale);
            
        	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
            	switch (settings.pageaction) {
	                case DEF_PARAMS.PAGEACTION.RIGHT:
	                case DEF_PARAMS.PAGEACTION.UPPER:
	                case DEF_PARAMS.PAGEACTION.RSLIDE:
	                    
	                	if (p % 2 == 0) {
	                		vx += params.media_w;
	                	}
	                	
	                    break;
	                case DEF_PARAMS.PAGEACTION.LEFT:
	                case DEF_PARAMS.PAGEACTION.LOWER:
	                case DEF_PARAMS.PAGEACTION.LSLIDE:
	                    
	                	if (p % 2 == 1) {
	                		vx += params.media_w;
	                	}
	                	
	                    break;
	            }
        	} else {
        		
        	}
        	
        	var vw = tvw + dx;
        	var vh = tvh + dy;
        	var mw, mh;
        	mw = vw;
    		mh = vh;
    		
        	tpos_w = vw / (params.pscale * params.basescale);
        	tpos_h = vh / (params.pscale * params.basescale);
                	
        	if (tpos_w < 150) {
        		vw = 150 * (params.pscale * params.basescale);
        	} else if (params.paper_w < vx + mw) {
        		vw = params.paper_w - vx;
        	}
        	if (tpos_h < 150) {
        		vh = 150 * (params.pscale * params.basescale);
        	} else if (params.paper_h < vy + mh) {
        		vh = params.paper_h - vy;
        	}
        	
        	pos_w = vw / (params.pscale * params.basescale);
        	pos_h = vh / (params.pscale * params.basescale);
        	
        	var posstr = pos_x+','+pos_y+','+pos_w+','+pos_h;
        	$('#'+memo_id).data('pos', posstr).attr('data-pos', posstr);
        	
        	memoObj[memo_id].w = pos_w;
        	memoObj[memo_id].h = pos_h;
        	
        	methods.savememo();
        	
            status.memoresizedrag = false;
            params.memo.resizedrag.sx = params.memo.resizedrag.cx = 0;
            params.memo.resizedrag.sy = params.memo.resizedrag.cy = 0;
            
            params.memo.resizeobj = null;
        },
        adjustmemo: function (){
        	var x, y, w, h;
        	var vx, vy, vw, vh;
        	
        	for (var it in memoObj) {
        		var p = $('#'+it).attr('rel');
                var points = $('#'+it).data('pos').split(','),
                x = parseInt(points[0]),
                y = parseInt(points[1]),
                w = parseInt(points[2]),
                h = parseInt(points[3]);
                
            	vy = y * (params.pscale * params.basescale);
            	vx = x * (params.pscale * params.basescale);
            	vw = w * (params.pscale * params.basescale);
            	vh = h * (params.pscale * params.basescale);
                
            	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
                	switch (settings.pageaction) {
		                case DEF_PARAMS.PAGEACTION.RIGHT:
		                case DEF_PARAMS.PAGEACTION.UPPER:
		                case DEF_PARAMS.PAGEACTION.RSLIDE:
		                    
		                	if (p % 2 == 0) {
		                		vx += params.media_w;
		                	}
		                	
		                    break;
		                case DEF_PARAMS.PAGEACTION.LEFT:
		                case DEF_PARAMS.PAGEACTION.LOWER:
		                case DEF_PARAMS.PAGEACTION.LSLIDE:
		                    
		                	if (p % 2 == 1) {
		                		vx += params.media_w;
		                	}
		                	
		                    break;
		            }
            	} else {
            		
            	}
            	
        		$('#'+it+' .memo_dialog').width(vw).height(vh);
        		$('#'+it+' .content_wrapper').width(vw - 30).height(vh - 50);
        		
            	var mw, mh;
            	if (memoObj[it].status == 'min') {
            		mw = 16;
            		mh = 22;
            	} else {
            		mw = vw;
            		mh = vh;
            	}
            	
            	if (vx < 0) {
            		vx = 0;
            	} else if (params.paper_w < vx + mw) {
            		vx = params.paper_w - mw;
            	}
            	if (vy < 0) {
            		vy = 0;
            	} else if (params.paper_h < vy + mh) {
            		vy = params.paper_h - mh;
            	}
            	
        		$('#'+it).css({
        			'top': vy+'px',
        			'left': vx+'px'
        		});
        	}
        },
        changememocontent: function () {
            var t = arguments[0]; //target
            var $o = arguments[1]; //target memo
            var memo_id = $o.attr('id');
            
            //@ToDo 打鍵履歴を使用して保存のタイミングを最適化する
        	var c = $('.content_wrapper', $o).val();
            memoObj[memo_id].content = c.replace(/<("[^"]*"|'[^']*'|[^'">])*>/g,'');
            methods.changememolist(memo_id, memoObj[memo_id].content);
            
            methods.savememo();
        },
        closememo: function (){
            var t = arguments[0]; //target
            var $o = arguments[1]; //target memo
            var memo_id = $o.attr('id');
            
            $o.remove();
            methods.delmemolist(memo_id);
            
            delete memoObj[memo_id];
            
            methods.savememo();
        },
        minimizememo: function (){
            var t = arguments[0]; //target
            var $o = arguments[1]; //target memo
            var memo_id = $o.attr('id');
            
            $('.memo_dialog', $o).hide();
            $('.minimize_icon', $o).show();
            
            memoObj[memo_id].status = 'min';
            
            methods.savememo();
        },
        normalizememo: function (){
            var t = arguments[0]; //target
            var $o = arguments[1]; //target memo
            var memo_id = $o.attr('id');
            
            $('.memo_dialog', $o).show();
            $('.minimize_icon', $o).hide();
            
            memoObj[memo_id].status = 'normal';
            
            methods.savememo();
        },
        hideallmemo: function() {
        	$('.memo_area').hide();
        },
        displaycurpagememo: function () {
            var p;
            
            $('.memo_area').hide();
            for (var it in memoObj) {
            	p = memoObj[it].page;
            	
            	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
                	if (status.curbasepage == p || status.curbasepage + 1 == p) {
                		$('#'+it).show();
                	}
            	} else {
                	if (status.curbasepage == p) {
                		$('#'+it).show();
                	}
            	}
            }
        },
        activatereceivememo: function (){
            var t = arguments[0]; //target
            
        	if (!status.memoreceive) {
        		status.memoreceive = true;
        		$(document).on('click', '.page', methods.receivememo);
        	}
        },
        deactivatereceivememo: function (){
            var t = arguments[0]; //target
            
    		status.memoreceive = false;
    		$(document).off('click', '.page', methods.receivememo);
        },
        receivememo: function (e){
        	var memo_id;
        	
            if (status.memoreceive) {
            	if($(this).hasClass('left')) {
                    switch (settings.pageaction) {
	                    case DEF_PARAMS.PAGEACTION.RIGHT:
	                    case DEF_PARAMS.PAGEACTION.UPPER:
	                    case DEF_PARAMS.PAGEACTION.RSLIDE:
	                        
	                		params.memo.target.page = status.curbasepage + 1;
		                    
	                        break;
	                        
	                    case DEF_PARAMS.PAGEACTION.LEFT:
	                    case DEF_PARAMS.PAGEACTION.LOWER:
	                    case DEF_PARAMS.PAGEACTION.LSLIDE:
	                        
	                		params.memo.target.page = status.curbasepage;
	                    	
	                        break;
	                }
                    params.memo.target.dir = 'l';
            		params.memo.target.x = e.offsetX / (params.pscale * params.basescale);
            		params.memo.target.y = e.offsetY / (params.pscale * params.basescale);
            		memo_id = methods.creatememo('show');
            		methods.addmemolist(memo_id);
            	} else if ($(this).hasClass('right')) {
                    switch (settings.pageaction) {
	                    case DEF_PARAMS.PAGEACTION.RIGHT:
	                    case DEF_PARAMS.PAGEACTION.UPPER:
	                    case DEF_PARAMS.PAGEACTION.RSLIDE:
	                        
	                		params.memo.target.page = status.curbasepage;
		                    
	                        break;
	                        
	                    case DEF_PARAMS.PAGEACTION.LEFT:
	                    case DEF_PARAMS.PAGEACTION.LOWER:
	                    case DEF_PARAMS.PAGEACTION.LSLIDE:
	                        
	                		params.memo.target.page = status.curbasepage + 1;
	                    	
	                        break;
	                }
                    params.memo.target.dir = 'r';
            		params.memo.target.x = e.offsetX / (params.pscale * params.basescale);
            		params.memo.target.y = e.offsetY / (params.pscale * params.basescale);
            		memo_id = methods.creatememo('show');
            		methods.addmemolist(memo_id);
            	}
            	
	    		methods.deactivatemouseicon.apply($(selectors.livebook), [$(selectors.livebook)]);
	        	methods.removemouseicon.apply($(selectors.livebook), [$(selectors.livebook)]);
	        	methods.deactivatereceivememo.apply($(selectors.livebook), [$(selectors.livebook)]);
	        	
	        	methods.savememo();
            }
        },
        addmouseicon: function (){
            var t = arguments[0]; //target
            var o = arguments[1]; //selector
            
            if (0 != $('body .orgcursor').length) {
            	$('body .orgcursor').remove();
            }
            $('body').append('<div id="'+o+'" class="orgcursor" style="display: none;"></div>');
        },
        removemouseicon: function (){
            var t = arguments[0]; //target
            
            if (0 != $('body .orgcursor').length) {
            	$('body .orgcursor').remove();
            }
        },
        activatemouseicon: function (){
            var t = arguments[0]; //target
            
            if (0 != $('body .orgcursor').length) {
            	$('body').css('cursor', 'none');
            	$('body .orgcursor').show();
            }
            
            $(document).on('mousemove', 'body', methods.tracemouseicon);
            
            status.mouse = true;
        },
        tracemouseicon: function (e){
        	$('body .orgcursor').css({
        		'top': (e.pageY)+'px',
        		'left': (e.pageX+1)+'px'
        	});
        },
        deactivatemouseicon: function (){
            var t = arguments[0]; //target
            
        	$('body').css('cursor', 'default');
        	
        	$(document).off('mousemove', 'body', methods.tracemouseicon);
        	
        	status.mouse = false;
        },
        savebookmark: function (){
        	$.cookie('bookmark', bookmarkObj, {expires: 36500});
        },
        activatereceivebookmark: function (){
            var t = arguments[0]; //target
            
        	if (!status.bookmarkreceive) {
        		status.bookmarkreceive = true;
        		$(document).on('click', '.page', methods.receivebookmark);
        	}
        },
        deactivatereceivebookmark: function (){
            var t = arguments[0]; //target
            
    		status.bookmarkreceive = false;
    		$(document).off('click', '.page', methods.receivebookmark);
        },
        receivebookmark: function (e){
            if (status.bookmarkreceive) {
            	if($(this).hasClass('left')) {
                    var page_num;
                    var html = '';
                    
                    var is_exist = false;
                    var existidx = -1;

                    if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                        switch (settings.pageaction) {
	                        case DEF_PARAMS.PAGEACTION.RIGHT:
	                        case DEF_PARAMS.PAGEACTION.UPPER:
	                        case DEF_PARAMS.PAGEACTION.RSLIDE:
	                            
	                            page_num = status.curbasepage + 1;
	    	                    
	                            break;
	                            
	                        case DEF_PARAMS.PAGEACTION.LEFT:
	                        case DEF_PARAMS.PAGEACTION.LOWER:
	                        case DEF_PARAMS.PAGEACTION.LSLIDE:
	                            
	                            page_num = status.curbasepage;
	                        	
	                            break;
	                    }
                        
                        if (page_num < 1 || params.lastpage < page_num)
                            return
                    } else {
                        page_num = status.curbasepage;

                        switch (settings.pageaction) {
	                        case DEF_PARAMS.PAGEACTION.RIGHT:
	                        case DEF_PARAMS.PAGEACTION.UPPER:
	                        case DEF_PARAMS.PAGEACTION.RSLIDE:
	                            
	                            if (page_num % 2 == 0) {
	                                return;
	                            }
	    	                    
	                            break;
	                        case DEF_PARAMS.PAGEACTION.LEFT:
	                        case DEF_PARAMS.PAGEACTION.LOWER:
	                        case DEF_PARAMS.PAGEACTION.LSLIDE:
	                            
		                        if (page_num % 2 == 1) {
		                            return;
		                        }
	                        	
	                            break;
	                    }
                    }
                    
                    for (var i = 0; i < bookmarkObj.tag.length; i++) {
                        if (bookmarkObj.tag[i] == page_num) {
                            is_exist = true;
                            existidx = i;
                        }
                    }

                    if (!is_exist) {
                        if (bookmarkObj.tag.length < bookmarkMaxLim) {
                            html = '<div class="tag_area" rel="' + page_num + '" rev="' + bookmarkColors[bookmarkObj.tag.length] + '"><span class="on right" style="background-color: ' + bookmarkColors[bookmarkObj.tag.length] + ';"></span></div>';
                            $("#paper_frame").append(html);

                            bookmarkObj.tag.push(page_num);
                            bookmarkObj.message.push('');
                        }
                    } else {
                        $("div.tag_area[rel='" + page_num + "'] span").parent().remove();
                        bookmarkObj.tag.splice(existidx, 1);
                        bookmarkObj.message.splice(existidx, 1);
                    }
                    
                    $('.tag_area').show();
                    methods.adjustbookmarktag();
            	} else if ($(this).hasClass('right')) {
                    var page_num;
                    var html = '';
        
		            var is_exist = false;
		            var existidx = -1;
                        
		            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
                        switch (settings.pageaction) {
	                        case DEF_PARAMS.PAGEACTION.RIGHT:
	                        case DEF_PARAMS.PAGEACTION.UPPER:
	                        case DEF_PARAMS.PAGEACTION.RSLIDE:
	                            
	                            page_num = status.curbasepage;
	    	                    
	                            break;
	                        case DEF_PARAMS.PAGEACTION.LEFT:
	                        case DEF_PARAMS.PAGEACTION.LOWER:
	                        case DEF_PARAMS.PAGEACTION.LSLIDE:
	                            
	                            page_num = status.curbasepage + 1;
	                        	
	                            break;
	                    }
                        
                        if (page_num < 1 || params.lastpage < page_num)
                            return
                    } else {
                        page_num = getSystemPageNum();

                        switch (settings.pageaction) {
	                        case DEF_PARAMS.PAGEACTION.RIGHT:
	                        case DEF_PARAMS.PAGEACTION.UPPER:
	                        case DEF_PARAMS.PAGEACTION.RSLIDE:
	                            
	                            if (page_num % 2 == 1) {
	                                return;
	                            }
	    	                    
	                            break;
	                        case DEF_PARAMS.PAGEACTION.LEFT:
	                        case DEF_PARAMS.PAGEACTION.LOWER:
	                        case DEF_PARAMS.PAGEACTION.LSLIDE:
	                            
		                        if (page_num % 2 == 0) {
		                            return;
		                        }
	                        	
	                            break;
	                    }
                    }
                    
		            for (var i = 0; i < bookmarkObj.tag.length; i++) {
		                if (bookmarkObj.tag[i] == page_num) {
		                    is_exist = true;
		                    existidx = i;
		                }
		            }
            
	            	if (!is_exist) {
	            		if (bookmarkObj.tag.length < bookmarkMaxLim) {
                            html = '<div class="tag_area" rel="' + page_num + '" rev="' + bookmarkColors[bookmarkObj.tag.length] + '"><span class="on right" style="background-color: ' + bookmarkColors[bookmarkObj.tag.length] + ';"></span></div>';
                            $("#paper_frame").append(html);
                    
                            bookmarkObj.tag.push(page_num);
                            bookmarkObj.message.push('');
	            		}
	            	} else {
                        $("div.tag_area[rel='" + page_num + "'] span").parent().remove();
                        bookmarkObj.tag.splice(existidx, 1);
                        bookmarkObj.message.splice(existidx, 1);
	            	}
	            	
                    $('.tag_area').show();
                    methods.adjustbookmarktag();
            	}
	        	
	        	methods.savebookmark();
	            
	    		methods.deactivatemouseicon.apply($(selectors.livebook), [$(selectors.livebook)]);
	        	methods.removemouseicon.apply($(selectors.livebook), [$(selectors.livebook)]);
	        	methods.deactivatereceivebookmark.apply($(selectors.livebook), [$(selectors.livebook)]);
            }
        },
        loadbookmarktag: function(){
            var html, page_num;
            
            bookmarkObj = $.cookie('bookmark');
            if (!bookmarkObj)
                bookmarkObj = {num: 0, tag: [], message: []};

            for (var i = 0; i < bookmarkObj.tag.length; i++) {
                page_num = bookmarkObj.tag[i];
                html = '<div class="tag_area" rel="' + page_num + '" rev="' + bookmarkColors[i] + '"><span class="on right" style="background-color: ' + bookmarkColors[i] + ';"></span></div>';
                $("#paper_frame").append(html);
            }
            
            methods.adjustbookmarktag();
        },
        adjustbookmarktag: function(){
            var s_dir, l_dir, a_dir; // small direction, large direction, active direction
            var tag_h = 17 * params.pscale * params.basescale; // tag height
            var tag_mb = 10 * params.pscale * params.basescale; // tag margin bottom
            var top_pad = 10 * params.pscale * params.basescale; // top padding
            var on_w = 70 * params.pscale * params.basescale;
            var off_w = 7 * params.pscale * params.basescale;
            var s_pos_b, l_pos_b, a_pos_b, r_pos_b; // for book val (small position book, large position book, active position book)
            var s_pos_s, l_pos_s, a_pos_s, r_pos_s; // for slide val (small position slide, large position slide, active position slide)
            var cur_page_num_b = status.curbasepage;
            var cur_page_num_s = cur_page_num_b;
            var page_num_b, page_num_s;
            var cur_page_num_btn;

            l_pos_b = 0;
            r_pos_b = params.paper_w;

            switch (settings.pageaction) {
	            case DEF_PARAMS.PAGEACTION.RIGHT:
	            case DEF_PARAMS.PAGEACTION.UPPER:
	            case DEF_PARAMS.PAGEACTION.RSLIDE:
	                
                    s_dir = 'right';
                    l_dir = 'left';

	                s_pos_b = r_pos_b;
	                l_pos_b = l_pos_b - off_w;
	
	                s_pos_s = r_pos_s;
	                l_pos_s = l_pos_s - off_w;
	                a_pos_s = r_pos_s - on_w + off_w;
	                
	                break;
	            case DEF_PARAMS.PAGEACTION.LEFT:
	            case DEF_PARAMS.PAGEACTION.LOWER:
	            case DEF_PARAMS.PAGEACTION.LSLIDE:
	                
                    s_dir = 'left';
                    l_dir = 'right';

	                s_pos_b = l_pos_b - off_w;
	                l_pos_b = r_pos_b;
	
	                s_pos_s = l_pos_s - off_w;
	                l_pos_s = r_pos_s;
	                a_pos_s = r_pos_s - on_w + off_w;
	            	
	                break;
	        }
            
            
            for (var i = 0; i < bookmarkObj.tag.length; i++) {
                // book
                page_num_b = bookmarkObj.tag[i];

                switch (settings.pageaction) {
    	            case DEF_PARAMS.PAGEACTION.RIGHT:
    	            case DEF_PARAMS.PAGEACTION.UPPER:
    	            case DEF_PARAMS.PAGEACTION.RSLIDE:
    	                
                        if (page_num_b % 2 == 0) {
                            a_dir = 'right';
                            a_pos_b = r_pos_b - on_w + off_w;
                        } else {
                            a_dir = 'left';
                            a_pos_b = l_pos_b;
                        }
    	                
    	                break;
    	            case DEF_PARAMS.PAGEACTION.LEFT:
    	            case DEF_PARAMS.PAGEACTION.LOWER:
    	            case DEF_PARAMS.PAGEACTION.LSLIDE:
    	                
                        if (page_num_b % 2 == 0) {
                            a_dir = 'left';
                            a_pos_b = s_pos_b;
                        } else {
                            a_dir = 'right';
                            a_pos_b = r_pos_b - on_w + off_w;
                        }
    	            	
    	                break;
    	        }
                    
                if (page_num_b < cur_page_num_b) {
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").removeClass(l_dir);
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").removeClass('on');

                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").addClass(s_dir);
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").addClass('off');
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").width(off_w);
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "']").css('left', s_pos_b+'px');
                } else if (cur_page_num_b + 1 < page_num_b) {
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").removeClass(s_dir);
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").removeClass('on');

                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").addClass(l_dir);
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").addClass('off');
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").width(off_w);
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "']").css('left', l_pos_b+'px');
                } else {
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").removeClass(s_dir);
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").removeClass(l_dir);
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").removeClass('off');

                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").addClass(a_dir);
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").addClass('on');
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "'] span").width(on_w);
                    $("#paper_frame div.tag_area[rel='" + page_num_b + "']").css('left', a_pos_b+'px');
                }

                $("div.tag_area[rel='" + page_num_b + "']").css('top', (i * (tag_h + tag_mb) + top_pad)+'px');
                $("div.tag_area[rel='" + page_num_s + "']").css('top', (i * (tag_h + tag_mb) + top_pad)+'px');
            }
            
            $("div.tag_area span").height(tag_h);
            $("div.tag_area").css('margin-bottom', tag_mb+'px');
    	},
        setpagenum: function(){
        	var t = arguments[0]; //target
        	var cp, lp, rp;
        	
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                switch (settings.pageaction) {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        
                		rp = status.curbasepage - settings.disp_stpage_num + settings.disp_stpage_cnt;
                		lp = rp + 1;
                		if (rp < settings.disp_stpage_cnt) { rp = '-'; }
                		else if (settings.disp_edpage_num < rp) { rp = '-'; }
                		if (lp < settings.disp_stpage_cnt) { lp = '-'; }
                		else if (settings.disp_edpage_num < lp) { lp = '-'; }
                		
                        if (settings.device == 'pc') {
                    		cp = lp + '-'+ rp;
                        } else {
                    		cp = rp;
                        }
                        
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        
                		lp = status.curbasepage - settings.disp_stpage_num + settings.disp_stpage_cnt;
                		rp = lp + 1;
                		if (lp < settings.disp_stpage_cnt) { lp = '-'; }
                		else if (settings.disp_edpage_num < lp) { lp = '-'; }
                		if (rp < settings.disp_stpage_cnt) { rp = '-'; }
                		else if (settings.disp_edpage_num < rp) { rp = '-'; }
                		
                        if (settings.device == 'pc') {
                    		cp = lp + '-'+ rp;
                        } else {
                    		cp = lp;
                        }
                        
                        break;
                }
            } else { //単ページの場合
            	if (settings.device == 'pc') {
            		cp = status.curbasepage - settings.disp_stpage_num + settings.disp_stpage_cnt;
            		if (cp < settings.disp_stpage_cnt) { cp = '-'; }
            		else if (settings.disp_edpage_num < cp) { cp = '-'; }
            	} else {
            		cp = status.curbasepage - settings.disp_stpage_num + settings.disp_stpage_cnt;
            		if (cp < settings.disp_stpage_cnt) { cp = '-'; }
            		else if (settings.disp_edpage_num < cp) { cp = '-'; }
            	}
            }
            
            if (settings.device == 'pc') {
            	$('#page_num span.current').text(cp);
            	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
	            	$('.pagenombre.left').text(lp);
	            	$('.pagenombre.right').text(rp);
            	} else {
	            	$('.pagenombre.left').text(cp);
	            	$('.pagenombre.right').text(cp);
            	}
            	methods.adjustnombre();
            } else {
            	$('#goto_text').val(cp);
            }
            $('#page_num span.total').text(getTotalPageLabel());
        },
        changedisplaystatus: function(){
        	var t = arguments[0]; //target
        	
        	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
            	if (status.curbasepage == 0 && settings.h0_invisible) {
                    switch (settings.pageaction) {
	                    case DEF_PARAMS.PAGEACTION.RIGHT:
	                    case DEF_PARAMS.PAGEACTION.UPPER:
	                    case DEF_PARAMS.PAGEACTION.RSLIDE:
	                        
	                    	$(selectors.pagenombrel).show();
	                        $(selectors.pagenombrer).hide();
	                    	
	                        break;
	                    case DEF_PARAMS.PAGEACTION.LEFT:
	                    case DEF_PARAMS.PAGEACTION.LOWER:
	                    case DEF_PARAMS.PAGEACTION.LSLIDE:
	                        
	                        $(selectors.pagenombrel).hide();
	                        $(selectors.pagenombrer).show();
	                    	
	                        break;
	                }
            	} else if (status.curbasepage == params.lastpage - params.lastpage % 2 && settings.h5_invisible) {
                    switch (settings.pageaction) {
	                    case DEF_PARAMS.PAGEACTION.RIGHT:
	                    case DEF_PARAMS.PAGEACTION.UPPER:
	                    case DEF_PARAMS.PAGEACTION.RSLIDE:
	                        
	                        $(selectors.pagenombrel).hide();
	                        $(selectors.pagenombrer).show();
	                    	
	                        break;
	                    case DEF_PARAMS.PAGEACTION.LEFT:
	                    case DEF_PARAMS.PAGEACTION.LOWER:
	                    case DEF_PARAMS.PAGEACTION.LSLIDE:
	                        
	                    	$(selectors.pagenombrel).show();
	                        $(selectors.pagenombrer).hide();
	                    	
	                        break;
	                }
            	} else {
            		$(selectors.pagenombre).show();
            	}
        	} else {
                switch (settings.pageaction) {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        
                    	if (status.curbasepage % 2 == 0) {
	                        $(selectors.pagenombrel).hide();
	                        $(selectors.pagenombrer).show();
                    	} else {
	                    	$(selectors.pagenombrel).show();
	                        $(selectors.pagenombrer).hide();
                    	}
                    	
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        
                    	if (status.curbasepage % 2 == 0) {
	                    	$(selectors.pagenombrel).show();
	                        $(selectors.pagenombrer).hide();
                    	} else {
	                        $(selectors.pagenombrel).hide();
	                        $(selectors.pagenombrer).show();
                    	}
                    	
                        break;
                }
        	}
        },
        changebuttonstatus: function(){
        	var t = arguments[0]; //target
        	
        	if (status.curbasepage == 0) {
                switch (settings.pageaction) {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        
                        $('#r_top').addClass('disabled');
                    	$('#r_page').addClass('disabled');
                    	$('.pageflipbutton.right').addClass('disabled');
                    	
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        
                        $('#l_top').addClass('disabled');
                    	$('#l_page').addClass('disabled');
                        $('.pageflipbutton.left').addClass('disabled');
                    	
                        break;
                }
        	} else {
                switch (settings.pageaction) {
	                case DEF_PARAMS.PAGEACTION.RIGHT:
	                case DEF_PARAMS.PAGEACTION.UPPER:
	                case DEF_PARAMS.PAGEACTION.RSLIDE:
	                    
	                    $('#r_top').removeClass('disabled');
	                	$('#r_page').removeClass('disabled');
	                	$('.pageflipbutton.right').removeClass('disabled');
	                	
	                    break;
	                case DEF_PARAMS.PAGEACTION.LEFT:
	                case DEF_PARAMS.PAGEACTION.LOWER:
	                case DEF_PARAMS.PAGEACTION.LSLIDE:
	                    
	                    $('#l_top').removeClass('disabled');
	                	$('#l_page').removeClass('disabled');
	                	$('.pageflipbutton.left').removeClass('disabled');
	                	
	                    break;
	            }
        	}
        	
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
            	if (status.curbasepage == params.lastpage || status.curbasepage == params.lastpage - params.lastpage % 2) {
                    switch (settings.pageaction) {
                        case DEF_PARAMS.PAGEACTION.RIGHT:
                        case DEF_PARAMS.PAGEACTION.UPPER:
                        case DEF_PARAMS.PAGEACTION.RSLIDE:
                            
                            $('#l_top').addClass('disabled');
                        	$('#l_page').addClass('disabled');
                        	$('.pageflipbutton.left').addClass('disabled');
                        	
                            break;
                        case DEF_PARAMS.PAGEACTION.LEFT:
                        case DEF_PARAMS.PAGEACTION.LOWER:
                        case DEF_PARAMS.PAGEACTION.LSLIDE:
                            
                            $('#r_top').addClass('disabled');
                        	$('#r_page').addClass('disabled');
                        	$('.pageflipbutton.right').addClass('disabled');
                        	
                            break;
                    }
            	} else {
                    switch (settings.pageaction) {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        
                        $('#l_top').removeClass('disabled');
                    	$('#l_page').removeClass('disabled');
                    	$('.pageflipbutton.left').removeClass('disabled');
                    	
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        
                        $('#r_top').removeClass('disabled');
                    	$('#r_page').removeClass('disabled');
                    	$('.pageflipbutton.right').removeClass('disabled');
                    	
                        break;
                }
            	}
            } else {
            	if (status.curbasepage == params.lastpage) {
                    switch (settings.pageaction) {
                        case DEF_PARAMS.PAGEACTION.RIGHT:
                        case DEF_PARAMS.PAGEACTION.UPPER:
                        case DEF_PARAMS.PAGEACTION.RSLIDE:
                            
                            $('#l_top').addClass('disabled');
                        	$('#l_page').addClass('disabled');
                        	$('.pageflipbutton.left').addClass('disabled');
                        	
                            break;
                        case DEF_PARAMS.PAGEACTION.LEFT:
                        case DEF_PARAMS.PAGEACTION.LOWER:
                        case DEF_PARAMS.PAGEACTION.LSLIDE:
                            
                            $('#r_top').addClass('disabled');
                        	$('#r_page').addClass('disabled');
                        	$('.pageflipbutton.right').addClass('disabled');
                        	
                            break;
                    }
            	} else {
                    switch (settings.pageaction) {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        
                        $('#l_top').removeClass('disabled');
                    	$('#l_page').removeClass('disabled');
                    	$('.pageflipbutton.left').removeClass('disabled');
                    	
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        
                        $('#r_top').removeClass('disabled');
                    	$('#r_page').removeClass('disabled');
                    	$('.pageflipbutton.right').removeClass('disabled');
                    	
                        break;
                }
            	}
            }
            
            if (params.pdf_dl == '') {
            	$('#pdf_dl').addClass('disabled');
            }
            if (params.print.enabled == false) {
            	$('#print_l').addClass('disabled');
            	$('#print_r').addClass('disabled');
            	$('#print_d').addClass('disabled');
            }
            if (params.page_num == false) {
            	$('#page_num').addClass('disabled');
            }
            if (status.zoommode === false) {
            	$('#zoom_out').addClass('disabled');
            } else {
            	$('#zoom_out').removeClass('disabled');
            }
            if (status.zoommode === true && params.zscale.scale == params.maxscale) {
            	$('#zoom_in').addClass('disabled');
            } else {
            	$('#zoom_in').removeClass('disabled');
            }
        },
        createpageflipbutton: function (){
            var t = arguments[0]; //target
            var html;
            
            //メニュー本体の追加
            html =  '<div class="pageflipbutton left"></div>'
                      + '<div class="pageflipbutton right"></div>';
            //20180829 Anderson
            //t.append(html);
            
            if (settings.device != 'pc') {
	            //メニュー起動領域の追加
	            t.append('<div id="menu_trigger" class="hover_button"></div>');
	            
	            //メニュー起動領域の追加
	            html = '<div id="controllbar" class="">'
	            	 + '<div class="button_wrapper">'
	            	 + '<div id="l_top" class="button"></div>'
	            	 + '<div id="l_page" class="button"></div>'
	            	 + '<div id="r_page" class="button"></div>'
	            	 + '<div id="r_top" class="button"></div>'
	            	 + '<form><input type="text" id="goto_text"><input type="submit" id="goto_submit" value="&nbsp;"></form>'
	            	 + '<div id="page_num"><span class="total"></span></div>'
	            	 + '</div>'
	            	 + '</div>';
	            t.append(html);
            }
        },
        createbookthick: function (){
            var t = arguments[0]; //target
            var html;
            
            if (settings.device == 'pc') {
	            //メニュー本体の追加
	            html =  '<div class="bookthick left top"></div>'
	            	+ '<div class="bookthick left middle"></div>'
	            	+ '<div class="bookthick left bottom"></div>'
	            	+ '<div class="bookthick right top"></div>'
	            	+ '<div class="bookthick right middle"></div>'
	            	+ '<div class="bookthick right bottom"></div>';
	            t.append(html);
            }
        },
        createbookgutter: function (){
            var t = arguments[0]; //target
            var html;
            
            if (settings.device == 'pc') {
	            //メニュー本体の追加
	            html =  '<div class="bookgutter left"></div>'
	            	+ '<div class="bookgutter right"></div>';
	            t.append(html);
            }
        },
        createzoommovebutton: function (){
            var t = arguments[0]; //target
            var html;
            
            if (settings.device == 'pc') {
                //メニュー本体の追加
                html =  '<div class="zoommovebutton vertical top" style="display: none;"></div>'
                    + '<div class="zoommovebutton horizontal right" style="display: none;"></div>'
                    + '<div class="zoommovebutton vertical bottom" style="display: none;"></div>'
                    + '<div class="zoommovebutton horizontal left" style="display: none;"></div>';
                t.append(html);
            }
        },
        settotalpagenum: function (){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            
            $(selectors.totalpagenum).text(p + 1);
        },
        setcurpagenum: function (){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            
            $(selectors.curpagenum).text(p + 1);
        },
        overlayzoompage: function() {
            var t = arguments[0]; //target
            var s = arguments[1]; //size
            var w = arguments[2]; //width
            var h = arguments[3]; //height
            var p = t.attr('rel'); //page num
            var imgsrc;
            
            if (is_blankpage(p, params.lastpage, settings.h0_invisible, settings.h5_invisible)) {
                imgsrc = settings.imgdir+'/blank.gif';
            } else {
                imgsrc = params.pageimgdir+params.pageimgprefix+'__dmx__' + p + '__' + s + '.jpg';
            }
            t.append('<img class="z" src="'+imgsrc+'" style="width:'+w+'px; height:'+h+'px;"></div>');
        },
        zoominstart: function (){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var rx = arguments[2]; //pos x (real x) ※クリックした実際の座標（ウィンドウのリサイズを反映した座標）
            var ry = arguments[3]; //pos y (real y) ※クリックした実際の座標（ウィンドウのリサイズを反映した座標）
            var tx, ty; //ウィンドウのリサイズを無視した実際の紙面上での座標
            var zx, zy; //拡大枠の原点
            var zw, zh; //拡大画像のサイズ
            var zpw, zph; //拡大画像のサイズ（1ページ分）
            var lp, rp; //拡大対象のページ数
            var cp, cx, cy; //拡大ログ送信用のページ数、座標
            var pp, pw, ph; //get_resize_objectの返り値用
            var zo; //get_zoom_outer_sizeの返り値用
            var cscale;

            if (!status.zoommode) {
            	$(selectors.zoom_frame).css('display', 'none');
            	$(selectors.zoom_frame).css({'display': 'block', 'left': params.paper_x+'px', 'top': params.paper_y+'px'});
            }
            
            //拡大後の拡大率を設定
            if (!status.zoommode) {
                cscale = params.pscale * params.basescale;
            } else {
                cscale = params.zscale.scale;
            }
            if (params.pscale < 1 && params.zscale.scale == 1) { //params.zoomlvs[params.basezlv]より小さいスケールの場合
            	params.zscale.tscale = params.basescale;
            	//params.zscale.scale = params.basescale;
            } else if (params.zscale.scale < params.maxscale){
            	params.zscale.tscale = params.maxscale;
            	//params.zscale.scale = params.maxscale;
            }
            
            zo = get_zoom_outer_size(params.win_w, params.win_h, params.zoom_outer);
            
            zpw = Math.floor(params.zscale.tscale / params.basescale * params.media_ow);
            zph = Math.floor(params.zscale.tscale / params.basescale * params.media_oh);

            zw =  zpw * params.paper_cols;
            zh =  zph * params.paper_rows;
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                //ページの配置位置（cssクラス）、z-indexの設定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (p % 2 === 0){ //右ページをクリック
                            lp = p + 1;
                            rp = p;
                            tx = Math.floor((rx + params.zoom_pow) / cscale);
                            ty = Math.floor(ry / cscale);
                        } else { //左ページをクリック
                            lp = p;
                            rp = p - 1;
                            tx = Math.floor(rx / cscale);
                            ty = Math.floor(ry / cscale);
                        }

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (p % 2 === 0) { //左ページをクリック
                            lp = p;
                            rp = p + 1;
                            tx = Math.floor(rx / cscale);
                            ty = Math.floor(ry / cscale);
                        } else { //右ページをクリック
                            lp = p - 1;
                            rp = p;
                            tx = Math.floor((rx + params.zoom_pow) / cscale);
                            ty = Math.floor(ry / cscale);
                        }

                        break;
                }
                
                if (!status.zoommode) {
	                if ($(selectors.zoompages+'[rel='+lp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.zoom_frame), lp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
	                if ($(selectors.zoompages+'[rel='+rp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.zoom_frame), rp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
	                
	                $(selectors.zoompages+'[rel='+lp+']', t).css({'left': '0px', 'top': '0px'});
	                $(selectors.zoompages+'[rel='+rp+']', t).css({'left': Math.floor(params.zscale.scale / params.basescale * params.media_ow) + 'px', 'top': '0px'});
	                
	                pp = $(selectors.paper_frame).position();
	                pw = $(selectors.paper_frame).width();
	                ph = $(selectors.paper_frame).height();

	                $(selectors.zoom_outer).css({'left': pp.left+'px', 'top': pp.top+'px', 'width': pw+'px', 'height': ph+'px'});
	                $(selectors.zoom_frame).css({'left': '0px', 'top': '0px'});
	                $(selectors.zoom_frame).width(params.zoom_pow * params.paper_cols).height(params.zoom_poh);
	                $(selectors.zoomimgs).width(params.zoom_pow).height(params.zoom_poh);
	                $(selectors.zoomrightpage).css({'left': params.zoom_pow + 'px'});
                }
                
                //拡大ログの送信用パラメータのセット
                cp = Math.min(lp, rp); cx = tx; cy = ty;
            } else {
                
                tx = Math.floor(rx / cscale);
                ty = Math.floor(ry / cscale);
                
                //拡大ログの送信用パラメータのセット
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (p % 2 === 0){ //右ページをクリック
                            cx = Math.floor((rx + params.zoom_pow) / cscale);
                            cy = Math.floor(ry / cscale);
                        } else { //左ページをクリック
                            cx = Math.floor(rx / cscale);
                            cy = Math.floor(ry / cscale);
                        }

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (p % 2 === 0) { //左ページをクリック
                            cx = Math.floor(rx / cscale);
                            cy = Math.floor(ry / cscale);
                        } else { //右ページをクリック
                            cx = Math.floor((rx + params.zoom_pow) / cscale);
                            cy = Math.floor(ry / cscale);
                        }

                        break;
                }
                
                if (!status.zoommode) {
                	if ($(selectors.zoompages+'[rel='+p+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.zoom_frame), p, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
	                
                	$(selectors.zoompages+'[rel='+p+']', t).css({'left': '0px', 'top': '0px'});
            		
                	pp = $(selectors.paper_frame).position();
	                pw = $(selectors.paper_frame).width();
	                ph = $(selectors.paper_frame).height();
	                $(selectors.zoom_outer).css({'left': pp.left+'px', 'top': pp.top+'px', 'width': pw+'px', 'height': ph+'px'});
	                $(selectors.zoom_frame).css({'left': '0px', 'top': '0px'});
	                $(selectors.zoom_frame).width(params.zoom_pow).height(params.zoom_poh);
	                $(selectors.zoomimgs).width(params.zoom_pow).height(params.zoom_poh);
                }
                
                //拡大ログの送信用パラメータのセット
                cp = p - p % 2;
            }

            //ウィンドウ原点から中心点までの距離 = 拡大の中心点 = クリックの座標 = 拡大枠の原点からの距離
            // -> ウィンドウ原点から中心点までの距離 - クリックの座標 = 拡大枠の原点座標
            zx = Math.floor(zo.w / 2 - params.zscale.tscale * tx);
            if (zw < zo.w) { // 拡大枠（外）よりも拡大後紙面幅が小さい場合 -> 拡大枠（外）の中央にする
            	zx = (zo.w - zw) / 2;
            } else { // 拡大枠（外）よりも拡大後紙面幅が大きい場合 -> 拡大枠（外）の中央にする
	            if (0 < zx) zx = 0;
	            else if (zx < zo.w - zw) zx = zo.w - zw;
            }
            zx=0;
            
            zy = Math.floor(zo.h / 2 - params.zscale.tscale * ty);
            if (zh < zo.h) { // 拡大枠（外）よりも拡大後紙面高さが小さい場合 -> 拡大枠（外）の中央にする
            	zy = (zo.h - zh) / 2;
            } else {
	            if (0 < zy) zy = 0;
	            else if (zy < zo.h - zh) zy = zo.h - zh;
            }
            zy=0;
            
            params.zoom_pw = zpw; params.zoom_ph = zph;
            params.zoom_w = zw; params.zoom_h = zh;
            params.zoom_x = zx; params.zoom_y = zy;
            
            params.zoom_outer_x = zo.l;
            params.zoom_outer_y = zo.t;
            params.zoom_outer_w = zo.w;
            params.zoom_outer_h = zo.h;
            
            status.zoommode = true;
            $('#paper_outer').hide();
            $('#zoom_outer').show();
            
            //
            methods.changebuttonstatus.apply($(this), [t]);
            methods.changedisplaystatus.apply($(this), [t]);
            
            //Web-CRM：拡大ログの送信（100%紙面上の座標として送信）
            webcrm_send_zoomview(cp, Math.floor(cx / params.basescale), Math.floor(cy / params.basescale), 0);
            //Anderson modify 20180518
            _ismobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
						_rate=(_ismobile)?1:1.5;
            methods.zoominaction.apply($(this), [t, p, zx, zy, zpw*_rate, zph*_rate]);
        },
        zoominaction: function () {
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var x = arguments[2]; //pos x
            var y = arguments[3]; //pos y
            var w = arguments[4]; //zoom width
            var h = arguments[5]; //zoom height
            var a1 = {'queue': false, 'duration': 500};
            var a2 = $.extend(true, {}, a1);
            var o, f, i, r; //animate params
            
            a2.complete = function(){
                if ($(selectors.zoomleftpage, t).length === 1 && $(selectors.zoomleftimgz, t).length === 0) methods.overlayzoompage.apply($(this), [$(selectors.zoomleftpage), DEF_PARAMS.PAGEZOOMNUM.ZOOM, w, h]);
                if ($(selectors.zoomrightpage, t).length === 1 && $(selectors.zoomrightimgz, t).length === 0) methods.overlayzoompage.apply($(this), [$(selectors.zoomrightpage), DEF_PARAMS.PAGEZOOMNUM.ZOOM, w, h]);

                methods.zoominterminate.apply($(this), [t]);
            };
            
            $("#zoom_frame .page .link_rect").remove();
            $("#zoom_frame .page .search_rect").remove();
            $(selectors.zoom_frame).css({'display': 'block'});
            
            o = {'left': params.zoom_outer_x+'px', 'top': params.zoom_outer_y+'px', 'width': params.zoom_outer_w, 'height': params.zoom_outer_h};
            f = {'left': x+'px', 'top': y+'px', 'width': w * params.paper_cols, 'height': h * params.paper_rows};
            i = {'width': w, 'height': h};
            r = {'left': w+'px'};
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
            	$(selectors.zoom_outer).animate(o, a1);
                $(selectors.zoom_frame).animate(f, a2);
                $(selectors.zoomimgs).animate(i, a1);
                $(selectors.zoomrightpage).animate(r, a1);
            } else {
            	$(selectors.zoom_outer).animate(o, a1);
                $(selectors.zoom_frame).animate(f, a2);
                $(selectors.zoomimgs).animate(i, a1);
            }
        },
        zoominterminate: function () {
            var t = arguments[0]; //target
            
            params.zscale.scale = params.zscale.tscale;
            status.zoominact = false;
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
        		methods.createlinkrectzoomarea.apply($(this), [t, status.curbasepage]);
        		methods.createlinkrectzoomarea.apply($(this), [t, status.curbasepage + 1]);
        	} else {
        		methods.createlinkrectzoomarea.apply($(this), [t, status.curbasepage]);
        	}
            methods.createsearchrectzoomarea.apply($(this), [t]);
            
            $(selectors.zoommovebutton).show();
            $(selectors.pageflipbutton).hide();
            $(selectors.bookthick).hide();
            $(selectors.bookgutter).hide();
            
            methods.changebuttonstatus.apply($(this), [t]);
            methods.changedisplaystatus.apply($(this), [t]);
        },
        zoomoutstart: function (){
            var t = arguments[0]; //target
            
            $(selectors.zoom_frame).css({'display': 'none', 'left': '0px', 'top': '0px'});
            
            methods.zoomoutaction.apply($(this), [t]);
        },
        zoomoutaction: function () {
            var t = arguments[0]; //target
            
            $(selectors.zoom_frame).empty();

            methods.zoomoutterminate.apply($(this), [t]);
        },
        zoomoutterminate: function () {
            var t = arguments[0]; //target
            
            status.zoomoutact = false;
            status.zoommode = false;
            status.gesture = false;
            params.zscale.startlen = 0;
            params.zscale.scale = 1;
            $('#paper_outer').show();
            $('#zoom_outer').hide();
            $(selectors.pageflipbutton).show();
            $(selectors.bookthick).show();
            $(selectors.bookgutter).show();
            $(selectors.zoommovebutton).hide();
            //
            methods.changebuttonstatus.apply($(this), [t]);
            methods.changedisplaystatus.apply($(this), [t]);
			
            if (typeof params.delayfuncobj === 'function') {
                params.delayfuncobj.apply($(this), params.delayfuncparam);
                params.delayfuncparam = [];
                params.delayfuncobj = undefined;
            }
        },
        tracezoomstart: function (){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var rx = arguments[2]; //pos x (real x) ※クリックした実際の座標（ウィンドウのリサイズを反映した座標）
            var ry = arguments[3]; //pos y (real y) ※クリックした実際の座標（ウィンドウのリサイズを反映した座標）
            var tx, ty; //ウィンドウのリサイズを無視した実際の紙面上での座標
            var zx, zy; //拡大枠の原点
            var zw, zh; //拡大画像のサイズ
            var zpw, zph; //拡大画像のサイズ（1ページ分）
            var lp, rp; //拡大対象のページ数
            var cp, cx, cy; //拡大ログ送信用のページ数、座標
            var pp; //get_resize_objectの返り値用
            
            //拡大後の拡大率を設定（ピンチイン/アウト開始時は等倍）
            params.zscale.scale = params.pscale;
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                //ページの配置位置（cssクラス）、z-indexの設定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (p % 2 === 0){ //右ページをクリック
                            lp = p + 1;
                            rp = p;
                            tx = Math.floor((rx + params.media_w) / params.pscale);
                            ty = Math.floor(ry / params.pscale);
                        } else { //左ページをクリック
                            lp = p;
                            rp = p - 1;
                            tx = Math.floor(rx / params.pscale);
                            ty = Math.floor(ry / params.pscale);
                        }

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (p % 2 === 0) { //左ページをクリック
                            lp = p;
                            rp = p + 1;
                            tx = Math.floor(rx / params.pscale);
                            ty = Math.floor(ry / params.pscale);
                        } else { //右ページをクリック
                            lp = p - 1;
                            rp = p;
                            tx = Math.floor((rx + params.media_w) / params.pscale);
                            ty = Math.floor(ry / params.pscale);
                        }

                        break;
                }
                
                if (!status.zoommode) {
                	if ($(selectors.zoompages+'[rel='+lp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.zoom_frame), lp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
	                if ($(selectors.zoompages+'[rel='+rp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.zoom_frame), rp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
	                
	                $(selectors.zoompages+'[rel='+lp+']', t).css({'left': '0px', 'top': '0px'});
	                $(selectors.zoompages+'[rel='+rp+']', t).css({'left': Math.floor(params.zscale.scale / params.basescale * params.media_ow) + 'px', 'top': '0px'});
	                
	                pp = $(selectors.paper_frame).position();
	                pw = $(selectors.paper_frame).width();
	                ph = $(selectors.paper_frame).height();
	                $(selectors.zoom_outer).css({'left': pp.left+'px', 'top': pp.top+'px', 'width': pw+'px', 'height': ph+'px'});
                	$(selectors.zoom_frame).css({'left': '0px', 'top': '0px'});
	                $(selectors.zoom_frame).width(params.media_w * params.paper_cols).height(params.media_h);
	                $(selectors.zoomimgs).width(params.media_w).height(params.media_h);
	                $(selectors.zoomrightpage).css({'left': params.media_w + 'px'});
                }
                
                //拡大ログの送信用パラメータのセット
                cp = Math.min(lp, rp); cx = tx; cy = ty;
            } else {
                
                tx = Math.floor(rx / params.pscale);
                ty = Math.floor(ry / params.pscale);
                
                if ($(selectors.zoompages+'[rel='+p+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.zoom_frame), p, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                
                $(selectors.zoompages+'[rel='+p+']', t).css({'left': '0px', 'top': '0px'});
                
                //拡大ログの送信用パラメータのセット
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (p % 2 === 0){ //右ページをクリック
                            cx = Math.floor((rx + params.media_w) / params.pscale);
                            cy = Math.floor(ry / params.pscale);
                        } else { //左ページをクリック
                            cx = Math.floor(rx / params.pscale);
                            cy = Math.floor(ry / params.pscale);
                        }

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (p % 2 === 0) { //左ページをクリック
                            cx = Math.floor(rx / params.pscale);
                            cy = Math.floor(ry / params.pscale);
                        } else { //右ページをクリック
                            cx = Math.floor((rx + params.media_w) / params.pscale);
                            cy = Math.floor(ry / params.pscale);
                        }

                        break;
                }
                
                if (!status.zoommode) {
                	pp = $(selectors.paper_frame).position();
	                pw = $(selectors.paper_frame).width();
	                ph = $(selectors.paper_frame).height();
                	$(selectors.zoom_outer).css({'left': pp.left+'px', 'top': pp.top+'px', 'width': pw+'px', 'height': ph+'px'});
                	$(selectors.zoom_frame).css({'left': '0px', 'top': '0px'});
	                $(selectors.zoom_frame).width(params.media_w).height(params.media_h);
	                $(selectors.zoomimgs).width(params.media_w).height(params.media_h);
                }
                
                //拡大ログの送信用パラメータのセット
                cp = p - p % 2;
            }
            
            zpw = params.media_w;
            zph = params.media_h;
            zw =  zpw * params.paper_cols;
            zh =  zph * params.paper_rows;
            zx = pp.left; zy = pp.top;
            
            params.zoom_pw = zpw; params.zoom_ph = zph;
            params.zoom_w = zw; params.zoom_h = zh;
            params.zoom_x = zx; params.zoom_y = zy;
            
            status.zoommode = true;
            status.tracezoom = true;
            $('#paper_outer').hide();
            $('#zoom_outer').show();
            
            //
            methods.changebuttonstatus.apply($(this), [t]);
            methods.changedisplaystatus.apply($(this), [t]);
            
            //Web-CRM：拡大ログの送信（100%紙面上の座標として送信）
            webcrm_send_zoomview(cp, Math.floor(cx / params.basescale), Math.floor(cy / params.basescale), 0);
        },
        tracezoomchange: function () {
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var rx = arguments[2]; //pos x (real x) ※クリックした実際の座標（ウィンドウのリサイズを反映した座標）
            var ry = arguments[3]; //pos y (real y) ※クリックした実際の座標（ウィンドウのリサイズを反映した座標）
            var tx, ty; //ウィンドウのリサイズを無視した実際の紙面上での座標
            var zx, zy; //拡大枠の原点
            var zw, zh; //拡大画像のサイズ
            var zpw, zph; //拡大画像のサイズ（1ページ分）
            var lp, rp; //拡大対象のページ数
            var cp, cx, cy; //拡大ログ送信用のページ数、座標
            var resize;
            
            //拡大後の拡大率を設定
            var cscale = params.zscale.scale;
            var diff = (params.zscale.gestureabs - DEF_PARAMS.THRESHOLD.GESTURE_ABS) / settings.gestureabs_div;
            
            if (params.zscale.scale + diff < params.pscale - params.pscale / 5) {
	           	//一定の倍率以下は許容しない
            } else {
            	params.zscale.tscale = params.zscale.scale + diff; 
            }
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                //ページの配置位置（cssクラス）、z-indexの設定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (p % 2 === 0){ //右ページをクリック
                            lp = p + 1;
                            rp = p;
                            tx = Math.floor((rx + params.zoom_pow) / cscale);
                            ty = Math.floor(ry / cscale);
                        } else { //左ページをクリック
                            lp = p;
                            rp = p - 1;
                            tx = Math.floor(rx / cscale);
                            ty = Math.floor(ry / cscale);
                        }

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (p % 2 === 0) { //左ページをクリック
                            lp = p;
                            rp = p + 1;
                            tx = Math.floor(rx / cscale);
                            ty = Math.floor(ry / cscale);
                        } else { //右ページをクリック
                            lp = p - 1;
                            rp = p;
                            tx = Math.floor((rx + params.zoom_pow) / cscale);
                            ty = Math.floor(ry / cscale);
                        }

                        break;
                }
            } else {
                tx = Math.floor(rx / cscale);
                ty = Math.floor(ry / cscale);
            }
            
            zo = get_zoom_outer_size(params.win_w, params.win_h, params.zoom_outer);
            
            zpw = Math.floor(params.zscale.tscale * params.media_ow);
            zph = Math.floor(params.zscale.tscale * params.media_oh);
            zw =  zpw * params.paper_cols;
            zh =  zph * params.paper_rows;
            
            //ウィンドウ原点から中心点までの距離 = 拡大の中心点 = クリックの座標 = 拡大枠の原点からの距離
            // -> ウィンドウ原点から中心点までの距離 - クリックの座標 = 拡大枠の原点座標
            //if (zw < params.zoom_outer.w) { //拡大枠より拡大画像が小さいとき
            //	zx = (params.zoom_outer.w - zw) / 2; //余白が左右均等になるように配置する
            //} else { //拡大枠より拡大画像が大きいとき
            	zx = -(tx * params.zscale.tscale) + params.zscale.screenx;
            	//if (0 < zx) zx = 0;
	            //else if (zx < params.zoom_outer.w - zw) zx = params.zoom_outer.w - zw;
            //}
            
            //if (zh < params.zoom_outer.h) {
            //	zy = (params.zoom_outer.h - zh) / 2;
            //} else {
            	zy = -(ty * params.zscale.tscale) + params.zscale.screeny;
            	//if (0 < zy) zy = 0;
	            //else if (zy < params.zoom_outer.h - zh) zy = params.zoom_outer.h - zh;
            //}
            
        	params.zoom_pw = zpw; params.zoom_ph = zph;
            params.zoom_w = zw; params.zoom_h = zh;
            params.zoom_x = zx; params.zoom_y = zy;
            
            params.zoom_outer_x = zo.l;
            params.zoom_outer_y = zo.t;
            params.zoom_outer_w = zo.w;
            params.zoom_outer_h = zo.h;
            
        	methods.tracezoomchangeaction.apply($(this), [t, p, zx, zy, zpw, zph]);
        },
        tracezoomchangeaction: function () {
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var x = arguments[2]; //pos x
            var y = arguments[3]; //pos y
            var w = arguments[4]; //zoom width
            var h = arguments[5]; //zoom height
            var o, f, i, r; //animate params
            
            $("#zoom_frame .page .link_rect").remove();
            $("#zoom_frame .page .search_rect").remove();
            $(selectors.zoom_frame).css({'display': 'block'});
            o = {'left': params.zoom_outer_x+'px', 'top': params.zoom_outer_y+'px', 'width': params.zoom_outer_w, 'height': params.zoom_outer_h};
            f = {'left': x+'px', 'top': y+'px', 'width': w * params.paper_cols, 'height': h * params.paper_rows};
            i = {'width': w, 'height': h};
            r = {'left': w+'px'};
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
            	$(selectors.zoom_outer).animate(o);
            	$(selectors.zoom_frame).css(f);
            	$(selectors.zoomimgs).css(i);
            	$(selectors.zoomrightpage).css(r);
            } else {
            	$(selectors.zoom_outer).animate(o);
                $(selectors.zoom_frame).css(f);
                $(selectors.zoomimgs).css(i);
            }
        },
        tracezoomactionterminate: function () {
            var t = arguments[0]; //target
            var a1 = {'queue': false, 'duration': 500};
            var a2 = $.extend(true, {}, a1);
            var zx, zy, zpw, zph;
            
            if (params.zscale.tscale < params.pscale) {
            	//拡大無しの紙面より小さくなるため元の大きさに戻して拡大を解除する
            	zx = params.paper_x;
            	zy = params.paper_y;
            	zpw = params.media_w;
            	zph = params.media_h;
            	
	            a2.complete = function(){
	            	params.zscale.screenx = null;
		            params.zscale.screeny = null;
					params.zscale.page = null;
					params.zscale.pagex = null;
					params.zscale.pagey = null;
					
		            params.zscale.scale = params.zscale.tscale;
		            params.zscale.gesturescale = 0;
		            params.zscale.gesturesabs = 0;
		            
		            params.zoom_x = zx;
		            params.zoom_y = zy;
		            
		            status.zoominact = false;
		            status.zoomoutact = false;
		            
		            methods.tracezoomterminate.apply($(this), [t]);
	            };
            } else {
	            if (params.zoom_w < params.zoom_outer.w) { //拡大枠より拡大画像が小さいとき
	            	zx = (params.zoom_outer.w - params.zoom_w) / 2; //余白が均等になるように配置する
	            } else {
	            	if (0 < params.zoom_x) zx = 0;
		            else if (params.zoom_x < params.zoom_outer.w - params.zoom_w) zx = params.zoom_outer.w - params.zoom_w;
	            	else zx = params.zoom_x;
	            }
	            if (params.zoom_h < params.zoom_outer.h) { //拡大枠より拡大画像が小さいとき
	            	zy = (params.zoom_outer.h - params.zoom_h) / 2; //余白が均等になるように配置する
	            } else {
	            	if (0 < params.zoom_y) zy = 0;
		            else if (params.zoom_y < params.zoom_outer.h - params.zoom_h) zy = params.zoom_outer.h - params.zoom_h;
	            	else zy = params.zoom_y;
	            }
	            
            	zpw = params.zoom_pw;
            	zph = params.zoom_ph;
            	
	            a2.complete = function(){
	            	if ($(selectors.zoomleftpage, t).length === 1 && $(selectors.zoomleftimgz, t).length === 0) methods.overlayzoompage.apply($(this), [$(selectors.zoomleftpage), DEF_PARAMS.PAGEZOOMNUM.ZOOM, params.zoom_pw, params.zoom_ph]);
	                if ($(selectors.zoomrightpage, t).length === 1 && $(selectors.zoomrightimgz, t).length === 0) methods.overlayzoompage.apply($(this), [$(selectors.zoomrightpage), DEF_PARAMS.PAGEZOOMNUM.ZOOM, params.zoom_pw, params.zoom_ph]);
	                
		            params.zscale.screenx = null;
		            params.zscale.screeny = null;
					params.zscale.page = null;
					params.zscale.pagex = null;
					params.zscale.pagey = null;
					
		            params.zscale.scale = params.zscale.tscale;
		            params.zscale.gesturescale = 0;
		            params.zscale.gesturesabs = 0;
		            
		            params.zoom_x = zx;
		            params.zoom_y = zy;
		            
		            status.zoominact = false;
		            status.zoomoutact = false;
	            };
            }
            
            if (zx != params.zoom_x || zy != params.zoom_y || zpw != params.zoom_pw || zph != params.zoom_ph) {
            	o = {'left': params.zoom_outer_x+'px', 'top': params.zoom_outer_y+'px', 'width': params.zoom_outer_w, 'height': params.zoom_outer_h};
            	f = {'left': zx+'px', 'top': zy+'px', 'width': zpw * params.paper_cols, 'height': zph * params.paper_rows};
	            i = {'width': zpw, 'height': zph};
	            r = {'left': zpw+'px'};
	            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
	            	$(selectors.zoom_outer).animate(o, a1);
	            	$(selectors.zoom_frame).animate(f, a1);
	                $(selectors.zoomimgs).animate(i, a2);
	                $(selectors.zoomrightpage).animate(r, a1);
	            } else {
	            	$(selectors.zoom_outer).animate(o, a1);
	            	$(selectors.zoom_frame).animate(f, a1);
	                $(selectors.zoomimgs).animate(i, a2);
	            }
            } else {
            	a2.complete();
            }
        },
        tracezoomterminate: function () {
        	var t = arguments[0]; //target
        	
        	$(selectors.zoom_frame).empty();
        	$(selectors.zoom_frame).hide();
        	$('#paper_outer').show();
        	$('#zoom_outer').hide();
        	
        	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
        		methods.createlinkrectzoomarea.apply($(this), [t, status.curbasepage]);
        		methods.createlinkrectzoomarea.apply($(this), [t, status.curbasepage + 1]);
        	} else {
        		methods.createlinkrectzoomarea.apply($(this), [t, status.curbasepage]);
        	}
        	methods.createsearchrectzoomarea.apply($(this), [t]);
        	
        	params.zscale.scale = params.zscale.tscale = 1;
        	
            params.zscale.screenx = null;
            params.zscale.screeny = null;
			params.zscale.page = null;
			params.zscale.pagex = null;
			params.zscale.pagey = null;
			
            params.zscale.scale = params.zscale.tscale;
            params.zscale.gesturescale = 0;
            params.zscale.gesturesabs = 0;
            
            status.zoominact = false;
            status.zoomoutact = false;
            
        	status.zoommode = false;
        	status.tracezoom = false;
        },
        slidedragstart: function () {
            var t = arguments[0]; //target
            var x = arguments[1]; //pos x
            var y = arguments[2]; //pos y
            
            status.swipe = true;
            params.swipe.dsx = params.swipe.dcx = x;
            params.swipe.dsy = params.swipe.dcy = y;
        },
        slidedragmove: function () {
            var t = arguments[0]; //target
            var x = arguments[1]; //pos x
            var y = arguments[2]; //pos y
            var dx = params.swipe.dsx - x,
                dy = params.swipe.dsy - y;
            var d;
            var nlp, nrp; //next left page, next right page
            var clp, crp; //current left page, current right page
            var plp, prp; //previous left page, previous right page
            var np, cp, pp; //next page, current page (for single)
            
            if (status.swipemove == false) {
            	status.swipemove = true;
            	
                $('.tag_area').hide();

            	if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
                    $(selectors.paper_frame, t).css({'width': params.paper_w+'px', 'height': params.media_h+'px', 'overflow': 'hidden'}); //紙面枠のサイズ設定
                    $(selectors.pageimgs, t).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
            	} else {
                    $(selectors.paper_frame, t).css({'width': params.paper_w+'px', 'height': params.media_h+'px', 'overflow': 'hidden'}); //紙面枠のサイズ設定
                    $(selectors.pageimgs, t).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
            	}
            }
            
            params.swipe.dcx = x;
            params.swipe.dcy = y;
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
            {
                // めくり方向の判定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        
                        //現在のページ
                        clp = status.curbasepage + 1;
                        crp = status.curbasepage;
                        
                        d = 'l2r';
                        
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        
                        //現在のページ
                        clp = status.curbasepage;
                        crp = status.curbasepage + 1;
                        
                        d = 'r2l';
                        
                        break;
                }
                
                //先のページ
                nlp = clp + 2;
                nrp = crp + 2;
                //前のページ
                plp = clp - 2;
                prp = crp - 2;
                
                if (d === 'l2r') { // 綴じ方向（右）
                    $(selectors.pages+'[rel='+plp+']', t).css({'display': 'block', 'left': (2 * params.media_w - dx)+'px'});
                    $(selectors.pages+'[rel='+prp+']', t).css({'display': 'block', 'left': (3 * params.media_w - dx)+'px'});
                    $(selectors.pages+'[rel='+clp+']', t).css({'display': 'block', 'left': (0 - dx)+'px'});
                    $(selectors.pages+'[rel='+crp+']', t).css({'display': 'block', 'left': (1 * params.media_w - dx)+'px'});
                    $(selectors.pages+'[rel='+nlp+']', t).css({'display': 'block', 'left': (-2 * params.media_w - dx)+'px'});
                    $(selectors.pages+'[rel='+nrp+']', t).css({'display': 'block', 'left': (-1 * params.media_w - dx)+'px'});
                } else { // 綴じ方向（左）
                    $(selectors.pages+'[rel='+plp+']', t).css({'display': 'block', 'left': (-2 * params.media_w - dx)+'px'});
                    $(selectors.pages+'[rel='+prp+']', t).css({'display': 'block', 'left': (-1 * params.media_w - dx)+'px'});
                    $(selectors.pages+'[rel='+clp+']', t).css({'display': 'block', 'left': (0 - dx)+'px'});
                    $(selectors.pages+'[rel='+crp+']', t).css({'display': 'block', 'left': (1 * params.media_w - dx)+'px'});
                    $(selectors.pages+'[rel='+nlp+']', t).css({'display': 'block', 'left': (2 * params.media_w - dx)+'px'});
                    $(selectors.pages+'[rel='+nrp+']', t).css({'display': 'block', 'left': (3 * params.media_w - dx)+'px'});
                }
            } else {
                //現在のページ
                cp = status.curbasepage;
                //先のページ
                np = cp + 1;
                //前のページ
                pp = cp - 1;
                
                if (get_bind_group() === DEF_PARAMS.PAGEGROUP.RIGHT) { // 綴じ方向（右）
                    $(selectors.pages+'[rel='+pp+']', t).css({'display': 'block', 'left': (1 * params.media_w - dx)+'px'});
                    $(selectors.pages+'[rel='+cp+']', t).css({'display': 'block', 'left': (0 - dx)+'px'});
                    $(selectors.pages+'[rel='+np+']', t).css({'display': 'block', 'left': (-1 * params.media_w - dx)+'px'});
                } else { // 綴じ方向（左）
                    $(selectors.pages+'[rel='+pp+']', t).css({'display': 'block', 'left': (-1 * params.media_w - dx)+'px'});
                    $(selectors.pages+'[rel='+cp+']', t).css({'display': 'block', 'left': (0 - dx)+'px'});
                    $(selectors.pages+'[rel='+np+']', t).css({'display': 'block', 'left': (1 * params.media_w - dx)+'px'});
                }
            }
        },
        slidedragterminate: function () {
            var t = arguments[0]; //target
            var dx = params.swipe.dsx - params.swipe.dcx, //diff x
                dy = params.swipe.dsy - params.swipe.dcy; //diff y
            var md;
            var cp = status.curbasepage;
            
            status.swipe = false;
            params.swipe.dsx = params.swipe.dcx = 0;
            params.swipe.dsy = params.swipe.dcy = 0;
            
            if ($.event.special.swipe.horizontalDistanceThreshold < Math.abs(dx)) {
                md = DEF_PARAMS.SLIDEMODE.SLIDE;
            } else {
                md = DEF_PARAMS.SLIDEMODE.REWIND;
            }
            
            if (0 < dx) {
        		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'slidetype': 'tile', 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
        		status.flipcue.length++;
                methods.slidestart.apply($(this), [t, 'r2l', md, status.flipcue.cue[status.flipcue.cue.length - 1]]);
            } else if (dx < 0) {
        		status.flipcue.cue.push({'basepage': status.curbasepage, 'headcue': true, 'slidetype': 'tile', 'direction': 'l2r', 'timerid': -1, 'clp': null, 'crp': null, 'nlp': null, 'nrp': null});
        		status.flipcue.length++;
                methods.slidestart.apply($(this), [t, 'l2r', md, status.flipcue.cue[status.flipcue.cue.length - 1]]);
            } else {
                $(selectors.pages, t).css('display', 'none');
                $(selectors.pages+'[rel='+cp+']', t).css('display', 'block');
                
                if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
                    $(selectors.pages+'[rel='+(cp+1)+']', t).css('display', 'block');
            }
            
            // $(selectors.paper_frame).css('overflow', 'visible');
            // $('.tag_area').show();
            
            status.swipemove = false;
        },
        zoomdragstart: function () {
            var t = arguments[0]; //target
            var x = arguments[1]; //pos x
            var y = arguments[2]; //pos y
            
            params.zoom_dox = params.zoom_x;
            params.zoom_doy = params.zoom_y;
            params.zoom_dsx = params.zoom_dcx = x;
            params.zoom_dsy = params.zoom_dcy = y;
            
            status.zoomdrag = true;
        },
        zoomdragmove: function () {
            var t = arguments[0]; //target
            var x = arguments[1]; //pos x
            var y = arguments[2]; //pos y
            var dx = params.zoom_dsx - x,
                dy = params.zoom_dsy - y;
            var zx, zy;
            params.zoom_dcx = x;
            params.zoom_dcy = y;
            
            zx = params.zoom_dox - dx;
            //Anderson modify 20180518
            _ismobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
						_rate=(_ismobile)?1:1.5;
            _zoom_w = params.zoom_w*_rate;

            if (_zoom_w < params.zoom_outer_w) {
            	zx = (params.zoom_outer_w - _zoom_w);
            	//zx = 0;
            } else {
	            if (0 < zx) zx = 0;
	            else if (zx < params.zoom_outer_w - _zoom_w) zx = params.zoom_outer_w - _zoom_w;
            }
            zy = params.zoom_doy - dy;
            if (params.zoom_h < params.zoom_outer_h) {
            	zy = (params.zoom_outer_h - params.zoom_h) / 2;
            } else {
	            if (0 < zy) zy = 0;
	            else if (zy < params.zoom_outer_h - params.zoom_h) zy = params.zoom_outer_h - params.zoom_h;
            }
            $(selectors.zoom_frame).css({'left': zx+'px', 'top': zy+'px'});
        },
        zoomdragterminate: function () {
            var t = arguments[0]; //target
            var dx = params.zoom_dsx - params.zoom_dcx, //diff x
                dy = params.zoom_dsy - params.zoom_dcy; //diff y
            var zx, zy;
            
            zx = params.zoom_dox - dx;
            if (params.zoom_w < params.zoom_outer_w) {
            	zx = (params.zoom_outer_w - params.zoom_w) / 2;
            } else {
	            if (0 < zx) zx = 0;
	            else if (zx < params.zoom_outer_w - params.zoom_w) zx = params.zoom_outer_w - params.zoom_w;
            }
            
            zy = params.zoom_doy - dy;
            if (params.zoom_h < params.zoom_outer_h) {
            	zy = (params.zoom_outer_h - params.zoom_h) / 2;
            } else {
	            if (0 < zy) zy = 0;
	            else if (zy < params.zoom_outer_h - params.zoom_h) zy = params.zoom_outer_h - params.zoom_h;
            }
            
            params.zoom_x = zx;
            params.zoom_y = zy;
            params.zoom_dox = params.zoom_doy = 0;
            params.zoom_dsx = params.zoom_dsy = 0;
            params.zoom_dcx = params.zoom_dcy = 0;
            status.zoomdrag = false;
        },
        zoombuttonmove: function () {
            var t = arguments[0]; //target
            var z = $(selectors.zoom_frame).position();
            var zx, zy;
            
            status.zoommove = true;
            
            zx = z.left;
            zy = z.top;
            
            switch (params.zoom_move_d) {
	            case 'top':
	            	zy += DEF_PARAMS.ZOOMMOVE.LENGTH;
	            	break;
	            case 'right':
	            	zx -= DEF_PARAMS.ZOOMMOVE.LENGTH;
	            	break;
	            case 'bottom':
	            	zy -= DEF_PARAMS.ZOOMMOVE.LENGTH;
	            	break;
	            case 'left':
	            	zx += DEF_PARAMS.ZOOMMOVE.LENGTH;
	            	break;
            }
            
            if (params.zoom_w < params.zoom_outer_w) {
            	zx = (params.zoom_outer_w - params.zoom_w) / 2;
            } else {
	            if (0 < zx) zx = 0;
	            else if (zx < params.zoom_outer_w - params.zoom_w) zx = params.zoom_outer_w - params.zoom_w;
            }
            
            if (params.zoom_h < params.zoom_outer_h) {
            	zy = (params.zoom_outer_h - params.zoom_h) / 2;
            } else {
	            if (0 < zy) zy = 0;
	            else if (zy < params.zoom_outer_h - params.zoom_h) zy = params.zoom_outer_h - params.zoom_h;
            }
            
            params.zoom_x = zx;
            params.zoom_y = zy;

            $(selectors.zoom_frame).css({'left': zx+'px', 'top': zy+'px'});
        },
        addpage: function() {
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var s = arguments[2]; //size
            var d, z, v; //direction left or right, z-index, visible
            var imgsrc;
            
            // @ToDo 重複ページ数指定の場合
            // 範囲外のページ数指定は追加しない
            if (p < 0 || params.lastpage < p)
                return;
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
            {
                //ページの配置位置（cssクラス）、z-indexの設定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (p % 2 === 0){
                            d = 'right';
                            z = p;
                        } else {
                            d = 'left';
                            z = params.lastpage - p + 1;
                        }

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (p % 2 === 0) {
                            d = 'left';
                            z = p;
                        } else {
                            d = 'right';
                            z =  params.lastpage - p + 1;
                        }

                        break;
                }

                //表示しないページは隠す
                if (status.curbasepage === p || status.curbasepage + 1 === p) v = 'block';
                else v = 'none';
            }
            else //単ページの場合
            {
                // @ToDo
                //ページの配置位置（cssクラス）、z-indexの設定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (p % 2 === 0){
                            d = 'right';
                            z = p;
                        } else {
                            d = 'left';
                            z = params.lastpage - p + 1;
                        }

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (p % 2 === 0) {
                            d = 'left';
                            z = p;
                        } else {
                            d = 'right';
                            z =  params.lastpage - p + 1;
                        }

                        break;
                }
                
                //表示しないページは隠す
                if (status.curbasepage === p)  v = 'block';
                else v = 'none';
            }
            
            if (is_blankpage(p, params.lastpage, settings.h0_invisible, settings.h5_invisible)) {
                imgsrc = settings.imgdir+'/blank.gif';
            } else {
                imgsrc = params.pageimgdir+params.pageimgprefix+'__dmx__' + p + '__' + s + '.jpg';
            }
            t.append('<div class="page ' + d + '" rel="' + p + '" style="z-index: ' + z + '; display: ' + v + '" data-slide-z="'+p+'" data-flip-z="'+z+'"><img src="'+imgsrc+'"></div>');
        },
        gotolefttop: function () {
        	var t = arguments[0]; //target
        	var cue = arguments[1];
        	
    			if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                status.flipping = true;
                
                switch (settings.pageaction) {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        methods.gotostart.apply(t, [t, params.lastpage, cue]);
                        
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
	                    methods.gotostart.apply(t, [t, 0, cue]);
            
                        break;
                }
            } else {
                status.sliding = true;
                
                switch (settings.pageaction) {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
		            	if (is_blankpage(params.lastpage, params.lastpage, settings.h0_invisible, settings.h5_invisible)) {
		            		methods.gotostart.apply(t, [t, params.lastpage - 1, cue]);
		            	} else {
		            		methods.gotostart.apply(t, [t, params.lastpage, cue]);
		            	} 
                        
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
		            	if (is_blankpage(0, params.lastpage, settings.h0_invisible, settings.h5_invisible)) {
		            		methods.gotostart.apply(t, [t, 1, cue]);
		            	} else {
		            		methods.gotostart.apply(t, [t, 0, cue]);
		            	} 
            
                        break;
                }
            }
        },
        gotoleft: function() {
        	var t = arguments[0]; //target
        	var cue = arguments[1];
        	var gotop;
        	
    			if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
    				switch (settings.pageaction) {
	                case DEF_PARAMS.PAGEACTION.RIGHT:
	                case DEF_PARAMS.PAGEACTION.UPPER:
	                case DEF_PARAMS.PAGEACTION.RSLIDE:
	                	gotop = cue.basepage + 2;
	                    
	                    break;
	                case DEF_PARAMS.PAGEACTION.LEFT:
	                case DEF_PARAMS.PAGEACTION.LOWER:
	                case DEF_PARAMS.PAGEACTION.LSLIDE:
	                	gotop = cue.basepage - 2;
	            
	                    break;
					}
    		} else {
    				switch (settings.pageaction) {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                    	gotop = cue.basepage + 1;
                        
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                    	gotop = cue.basepage - 1;
                
                        break;
    				}
    		}
//    		console.log('p:'+gotop);
    		methods.gotostart.apply(t, [t, gotop, cue]);
        },
        gotoright: function() {
        	var t = arguments[0]; //target
        	var cue = arguments[1];
        	var gotop;
        	
    		if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
                switch (settings.pageaction) {
	                case DEF_PARAMS.PAGEACTION.RIGHT:
	                case DEF_PARAMS.PAGEACTION.UPPER:
	                case DEF_PARAMS.PAGEACTION.RSLIDE:
	                    gotop = cue.basepage - 2;
	                    
	                    break;
	                case DEF_PARAMS.PAGEACTION.LEFT:
	                case DEF_PARAMS.PAGEACTION.LOWER:
	                case DEF_PARAMS.PAGEACTION.LSLIDE:
	                	gotop = cue.basepage + 2;
	        
	                    break;
	            }
    		} else {
                switch (settings.pageaction) {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        gotop = cue.basepage - 1;
                        
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                    	gotop = cue.basepage + 1;
            
                        break;
                }
    		}
//    		console.log('p:'+gotop);
    		methods.gotostart.apply(t, [t, gotop, cue]);
        },
        gotorighttop: function () {
        	var t = arguments[0]; //target
        	var cue = arguments[1];
        	
    			if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                status.flipping = true;
                
                switch (settings.pageaction) {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
	                    methods.gotostart.apply(t, [t, 0, cue]);
                        
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        methods.gotostart.apply(t, [t, params.lastpage, cue]);
            
                        break;
                }
            } else {
                status.sliding = true;
            	
                switch (settings.pageaction) {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
		            	if (is_blankpage(0, params.lastpage, settings.h0_invisible, settings.h5_invisible)) {
		            		methods.gotostart.apply(t, [t, 1, cue]);
		            	} else {
		            		methods.gotostart.apply(t, [t, 0, cue]);
		            	} 
                        
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
	                    if (is_blankpage(params.lastpage, params.lastpage, settings.h0_invisible, settings.h5_invisible)) {
		            		methods.gotostart.apply(t, [t, params.lastpage - 1, cue]);
		            	} else {
		            		methods.gotostart.apply(t, [t, params.lastpage, cue]);
		            	} 
            
                        break;
                }
            }
        },
        gotostart: function() {
            var t = arguments[0]; //target
            var p = arguments[1]; //jump to page num
            var cue = arguments[2]; //cue object
            var d; //direction
            var np, nlp, nrp; //next left page, next right page
            var cp, clp, crp; //current left page, current right page
            var plplp, plprp, plpp; //preload previous left page, right page
            var plnlp, plnrp, plnp; //preload next left page, right page
            
            var f = methods.flipactiontype.apply(t); // ページめくりのタイプ flip or slide
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
            {
                //ページ数を見開きの小さい側に合わせる(status.curbasepageとの比較のため)
                p -= p % 2;
                
                //めくり後のページが0～最大ページ数範囲内、または現在のページと同じ場合は処理を中断
                if (p < 0 || params.lastpage < p || p === cue.basepage) {
                	if (f == 'flip') {
	                    //status.flipping = false;
	                    methods.flipterminate.apply($(this), [t, null, cue]);
                	} else {
	                    methods.slideterminate.apply($(this), [t, null, cue]);
                	}
                    return;
                } else {
                	if (f == 'flip') {
                		status.flipping = true;
                	} else {
                		status.sliding = true;
                	}
                }
                
                // めくり方向の判定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (p < cue.basepage){ // めくり方向：left <- right
                            d = 'r2l';
                        } else { // めくり方向：left -> right
                            d = 'l2r';
                        }
                        
                        //めくり後に現れるページ
                        nlp = p + 1;
                        nrp = p;
                        //現在のページ
                        clp = cue.basepage + 1;
                        crp = cue.basepage;

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (p < cue.basepage){ // めくり方向：left -> right
                            d = 'l2r';
                        } else { // めくり方向：left <- right
                            d = 'r2l';
                        }
                        
                        //めくり後に現れるページ
                        nlp = p;
                        nrp = p + 1;
                        //現在のページ
                        clp = cue.basepage;
                        crp = cue.basepage + 1;

                        break;
                }
                
                //プリロード用ページ
                plnlp = nlp + 2;
                plnrp = nrp + 2;
                plplp = nlp - 2;
                plprp = nrp - 2;

                //ページの有無確認と、なければ追加
                if ($(selectors.pages+'[rel='+nlp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), nlp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+nrp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), nrp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+clp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), clp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+crp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), crp, params.zoomlvs[params.basezlv]]);
                
                //ページの有無確認と、なければ追加（プリロード用）
                if ($(selectors.pages+'[rel='+plnlp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), plnlp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+plnrp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), plnrp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+plplp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), plplp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+plprp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), plprp, params.zoomlvs[params.basezlv]]);
                
                cue.clp = clp;
                cue.crp = crp;
                cue.nlp = nlp;
                cue.nrp = nrp;
                
                //先頭のキューの場合のみ処理する
                if (cue.headcue) {
	                $('.tag_area').hide();
	                $(selectors.paper_frame, t).css({'width': params.paper_w+'px', 'height': params.media_h+'px', 'overflow': 'hidden'}); //紙面枠のサイズ設定
                }
                $(selectors.pageimgs, t).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
                
                switch (settings.pageaction) {
	                case DEF_PARAMS.PAGEACTION.RIGHT:
	                case DEF_PARAMS.PAGEACTION.UPPER:
	                case DEF_PARAMS.PAGEACTION.LEFT:
	                case DEF_PARAMS.PAGEACTION.LOWER:
	                    
		            	var clpz = $(selectors.pages+'[rel='+clp+']', t).data('flip-z');
		            	var crpz = $(selectors.pages+'[rel='+crp+']', t).data('flip-z');
		            	var nlpz = $(selectors.pages+'[rel='+nlp+']', t).data('flip-z');
		            	var nrpz = $(selectors.pages+'[rel='+nrp+']', t).data('flip-z');
		            	$(selectors.pages+'[rel='+clp+']', t).css({'z-index': clpz});
		            	$(selectors.pages+'[rel='+crp+']', t).css({'z-index': crpz});
		            	$(selectors.pages+'[rel='+nlp+']', t).css({'z-index': nlpz});
		            	$(selectors.pages+'[rel='+nrp+']', t).css({'z-index': nrpz});
		            	
		                if (d === 'l2r') { // めくり方向：left -> right
		                    //アニメーションするページ
		                    $(selectors.pages+'[rel='+clp+']', t).css({'left': '0px', 'transform-origin': params.media_w+'px 0px'});
		                    $(selectors.pages+'[rel='+nrp+']', t).css({'left': 1 * (params.media_w)+'px', 'transform-origin': '0px 0px', 'transform': 'scale(0,1)', 'display': 'block'});
		                    //アニメーションしないページ
		                    if (cue.headcue) {
		                    	$(selectors.pages+'[rel='+crp+']', t).css({'left': 1 * (params.media_w)+'px'});
		                    }
		                    $(selectors.pages+'[rel='+nlp+']', t).css({'left': '0px', 'display': 'block'});
		                } else { // めくり方向：left <- right
		                    //アニメーションするページ
		                    $(selectors.pages+'[rel='+crp+']', t).css({'left': 1 * (params.media_w)+'px', 'transform-origin': '0px 0px'});
		                    $(selectors.pages+'[rel='+nlp+']', t).css({'left': '0px', 'transform-origin': params.media_w+'px 0px', 'transform': 'scale(0,1)', 'display': 'block'});
		                    //アニメーションしないページ
		                    if (cue.headcue) {
		                    	$(selectors.pages+'[rel='+clp+']', t).css({'left': '0px'});
		                    }
		                    $(selectors.pages+'[rel='+nrp+']', t).css({'left': 1 * (params.media_w)+'px', 'display': 'block'});
		                }
		                
	                    params.flipdirection = d;
	                    cue.timerid = setInterval(methods.flipaction, params.flipstep, $(this), [t, cue.clp, cue.crp, cue.nlp, cue.nrp, cue]);
	                    
	                    break;
	                case DEF_PARAMS.PAGEACTION.RSLIDE:
	                case DEF_PARAMS.PAGEACTION.LSLIDE:
	                    
		            	var clpz = $(selectors.pages+'[rel='+clp+']', t).data('slide-z');
		            	var crpz = $(selectors.pages+'[rel='+crp+']', t).data('slide-z');
		            	var nlpz = $(selectors.pages+'[rel='+nlp+']', t).data('slide-z');
		            	var nrpz = $(selectors.pages+'[rel='+nrp+']', t).data('slide-z');
		            	
		            	if (cue.slidetype == 'overlay' && cue.counttype == 'minus') {
		            		clpz = params.lastpage - clp + 1;
		            		crpz = params.lastpage - crp + 1;
		            		nlpz = params.lastpage - nlp + 1;
		            		nrpz = params.lastpage - nrp + 1;
		            	}
		            	
		            	$(selectors.pages+'[rel='+clp+']', t).css({'z-index': clpz});
		            	$(selectors.pages+'[rel='+crp+']', t).css({'z-index': crpz});
		            	$(selectors.pages+'[rel='+nlp+']', t).css({'z-index': nlpz});
		            	$(selectors.pages+'[rel='+nrp+']', t).css({'z-index': nrpz});
		            	
		                if (cue.slidetype == 'tile') {
		                    if (d === 'l2r') { // めくり方向：left -> right
		                        //アニメーションするページ
		                        $(selectors.pages+'[rel='+nlp+']', t).css({'display': 'block', 'left': -2 * (params.media_w)+'px'});
		                        $(selectors.pages+'[rel='+nrp+']', t).css({'display': 'block', 'left': -1 * (params.media_w)+'px'});
		                        $(selectors.pages+'[rel='+clp+']', t).css({'display': 'block', 'left': '0px'});
		                        $(selectors.pages+'[rel='+crp+']', t).css({'display': 'block', 'left': 1 * (params.media_w)+'px'});
		                    } else { // めくり方向：left <- right
		                        //アニメーションするページ
		                        $(selectors.pages+'[rel='+clp+']', t).css({'display': 'block', 'left': '0px'});
		                        $(selectors.pages+'[rel='+crp+']', t).css({'display': 'block', 'left': 1 * (params.media_w)+'px'});
		                        $(selectors.pages+'[rel='+nlp+']', t).css({'display': 'block', 'left': 2 * (params.media_w)+'px'});
		                        $(selectors.pages+'[rel='+nrp+']', t).css({'display': 'block', 'left': 3 * (params.media_w)+'px'});
		                    }
		                } else {
		                    if (d === 'l2r') { // めくり方向：left -> right
		                        //アニメーションするページ
		                        $(selectors.pages+'[rel='+nlp+']', t).css({'display': 'block', 'left': -2 * (params.media_w)+'px'});
		                        $(selectors.pages+'[rel='+nrp+']', t).css({'display': 'block', 'left': -1 * (params.media_w)+'px'});
		                    } else { // めくり方向：left <- right
		                        //アニメーションするページ
		                        $(selectors.pages+'[rel='+nlp+']', t).css({'display': 'block', 'left': 2 * (params.media_w)+'px'});
		                        $(selectors.pages+'[rel='+nrp+']', t).css({'display': 'block', 'left': 3 * (params.media_w)+'px'});
		                    }
		                }
	                    
	                	params.slidedirection = d;
	                	cue.timerid = setTimeout(methods.slideaction, params.slidedelay, $(this), [t, DEF_PARAMS.SLIDEMODE.SLIDE, cue.clp, cue.crp, cue.nlp, cue.nrp, null, null, cue]);
	                	
	                    break;
	            }
            } else {
                //めくり後のページが0～最大ページ数範囲内、または現在のページと同じ場合は処理を中断
                if (p < 0 || params.lastpage < p || p === status.curbasepage) {
                    //status.sliding = false;
                    methods.slideterminate.apply($(this), [t, null, cue]);
                    return;
                } else if (p == 0 && is_blankpage(0, params.lastpage, settings.h0_invisible, settings.h5_invisible)) {            		
                    //status.sliding = false;
                    methods.slideterminate.apply($(this), [t, null, cue]);
                    return;
                /* Anderson 20180824
                } else if (p == params.lastpage && is_blankpage(0, params.lastpage, settings.h0_invisible, settings.h5_invisible)) {            		
                    //status.sliding = false;
                    methods.slideterminate.apply($(this), [t, null, cue]);
                    return;
                */
	            	} else {
  	          		status.sliding = true;
    	        	}
                
                // めくり方向の判定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (p < status.curbasepage){ // めくり方向：left <- right
                            d = 'r2l';
                        } else { // めくり方向：left -> right
                            d = 'l2r';
                        }
                        
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (p < status.curbasepage){ // めくり方向：left -> right
                            d = 'l2r';
                        } else { // めくり方向：left <- right
                            d = 'r2l';
                        }
                        
                        break;
                }
                
                //めくり後に現れるページ
                np = p;
                //現在のページ
                cp = status.curbasepage;

                //プリロード用ページ
                plnp = np + 1;
                plpp = np - 1;
                
                //ページの有無確認と、なければ追加
                if ($(selectors.pages+'[rel='+np+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), np, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                if ($(selectors.pages+'[rel='+cp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), cp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                
                //ページの有無確認と、なければ追加（プリロード用）
                if ($(selectors.pages+'[rel='+plnp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), plnp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                if ($(selectors.pages+'[rel='+plpp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), plpp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                
                $(selectors.paper_frame, t).css({'width': params.paper_w+'px', 'height': params.media_h+'px', 'overflow': 'hidden'}); //紙面枠のサイズ設定
                $(selectors.pageimgs, t).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
                
                cue.nlp = np;
                cue.clp = cp;
                
            	var cpz = $(selectors.pages+'[rel='+cp+']', t).data('slide-z');
            	var npz = $(selectors.pages+'[rel='+np+']', t).data('slide-z');
            	if (cue.slidetype == 'overlay' && cue.counttype == 'minus') {
            		cpz = params.lastpage - cp + 1;
            		npz = params.lastpage - np + 1;
            	}
            	$(selectors.pages+'[rel='+cp+']', t).css({'z-index': cpz});
            	$(selectors.pages+'[rel='+np+']', t).css({'z-index': npz});
            	
                if (cue.slidetype == 'tile') {
	                if (d === 'l2r') { // めくり方向：left -> right
	                    //アニメーションするページ
	                    $(selectors.pages+'[rel='+cp+']', t).css({'display': 'block', 'left': '0px'});
	                    $(selectors.pages+'[rel='+np+']', t).css({'display': 'block', 'left': -1 * (params.media_w)+'px'});
	                } else { // めくり方向：left <- right
	                    //アニメーションするページ
	                    $(selectors.pages+'[rel='+cp+']', t).css({'display': 'block', 'left': '0px'});
	                    $(selectors.pages+'[rel='+np+']', t).css({'display': 'block', 'left': 1 * (params.media_w)+'px'});
	                }
                } else {
	                if (d === 'l2r') { // めくり方向：left -> right
	                    //アニメーションするページ
	                    $(selectors.pages+'[rel='+np+']', t).css({'display': 'block', 'left': -1 * (params.media_w)+'px'});
	                } else { // めくり方向：left <- right
	                    //アニメーションするページ
	                    $(selectors.pages+'[rel='+np+']', t).css({'display': 'block', 'left': 1 * (params.media_w)+'px'});
	                }
                }
                
                params.slidedirection = d;
                cue.timerid = setTimeout(methods.slideaction, params.slidedelay, $(this), [t, DEF_PARAMS.SLIDEMODE.SLIDE, cue.clp, null, cue.nlp, null, null, null, cue]);
            }
        },
        clearautoflip: function (){
					clearTimeout(params.autoflip.timerID);
					status.autoflip = false;
					params.autoflip.timerID = -1;
					params.autoflip.dir = null;
	    	},
        autoflip: function() {
        	var t = arguments[0]; //target
        	
        	if (status.autoflip == true) {
        		if (params.autoflip.dir == 'r2l') {
        			methods.gotoleft.apply(t, [t]);
        		} else {
        			methods.gotoright.apply(t, [t]);
        		}
        	}
        },
        flipstart: function() {
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var d; //direction
            var nlp, nrp; //next left page, next right page
            var clp, crp; //current left page, current right page
            var pllp, plrp; //preload left page, right page
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
            {
                // めくり方向の判定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (p === status.curbasepage){ // めくり方向：left <- right
                            d = 'r2l';

                            //めくり後に現れるページ
                            nlp = status.curbasepage - 1;
                            nrp = status.curbasepage - 2;
                            //プリロード用ページ
                            pllp = status.curbasepage - 3;
                            plrp = status.curbasepage - 4;
                        } else { // めくり方向：left -> right
                            d = 'l2r';
                            
                            //プリロード用ページ
                            pllp = status.curbasepage + 5;
                            plrp = status.curbasepage + 4;
                            //めくり後に現れるページ
                            nlp = status.curbasepage + 3;
                            nrp = status.curbasepage + 2;
                        }
                        
                        //現在のページ
                        clp = status.curbasepage + 1;
                        crp = status.curbasepage;

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (p === status.curbasepage) { // めくり方向：left -> right
                            d = 'l2r';

                            //プリロード用ページ
                            pllp = status.curbasepage - 4;
                            plrp = status.curbasepage - 3;
                            //めくり後に現れるページ
                            nlp = status.curbasepage - 2;
                            nrp = status.curbasepage - 1;
                        } else { // めくり方向：left <- right
                            d = 'r2l';
                            
                            //めくり後に現れるページ
                            nlp = status.curbasepage + 2;
                            nrp = status.curbasepage + 3;
                            //プリロード用ページ
                            pllp = status.curbasepage + 4;
                            plrp = status.curbasepage + 5;
                        }
                        
                        //現在のページ
                        clp = status.curbasepage;
                        crp = status.curbasepage + 1;

                        break;
                }
                
                //めくり後のページが0～最大ページ数範囲内にない場合は処理を中断
                if (Math.min(nlp, nrp) < 0 || params.lastpage < Math.min(nlp, nrp)) {
                    status.flipping = false;
                    return;
                }
                
                //ページの有無確認と、なければ追加
                if ($(selectors.pages+'[rel='+nlp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), nlp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+nrp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), nrp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+clp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), clp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+crp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), crp, params.zoomlvs[params.basezlv]]);
                
                //ページの有無確認と、なければ追加（プリロード用）
                if ($(selectors.pages+'[rel='+pllp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), pllp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+plrp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), plrp, params.zoomlvs[params.basezlv]]);
                                
                $(selectors.paper_frame, t).css({'width': params.paper_w+'px', 'height': params.media_h+'px'}); //紙面枠のサイズ設定
                $(selectors.pageimgs, t).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
                
                if (d === 'l2r') { // めくり方向：left -> right
                    //アニメーションするページ
                    $(selectors.pages+'[rel='+clp+']', t).css({'left': '0px', 'transform-origin': params.media_w+'px 0px'});
                    $(selectors.pages+'[rel='+nrp+']', t).css({'left': 1 * (params.media_w)+'px', 'transform-origin': '0px 0px', 'transform': 'scale(0,1)', 'display': 'block'});
                    //アニメーションしないページ
                    $(selectors.pages+'[rel='+crp+']', t).css({'left': 1 * (params.media_w)+'px'});
                    $(selectors.pages+'[rel='+nlp+']', t).css({'left': '0px', 'display': 'block'});
                } else { // めくり方向：left <- right
                    //アニメーションするページ
                    $(selectors.pages+'[rel='+crp+']', t).css({'left': 1 * (params.media_w)+'px', 'transform-origin': '0px 0px'});
                    $(selectors.pages+'[rel='+nlp+']', t).css({'left': '0px', 'transform-origin': params.media_w+'px 0px', 'transform': 'scale(0,1)', 'display': 'block'});
                    //アニメーションしないページ
                    $(selectors.pages+'[rel='+clp+']', t).css({'left': '0px'});
                    $(selectors.pages+'[rel='+nrp+']', t).css({'left': 1 * (params.media_w)+'px', 'display': 'block'});
                }
                
                params.flipdirection = d;
                params.timerid = setInterval(methods.flipaction, params.flipstep, $(this), [t, clp, crp, nlp, nrp]);
            }
        },
        flipaction: function(){
            var t = arguments[1][0]; //target
            var clp = arguments[1][1]; //page num
            var crp = arguments[1][2]; //page num
            var nlp = arguments[1][3]; //page num
            var nrp = arguments[1][4]; //page num
            var cue = arguments[1][5]; //cue object
            var d = params.flipdirection;
            
            cue.flipangle += params.anglestep;
            
            var rotateDeg = cue.flipangle,
                sratio = Math.abs(Math.cos(rotateDeg * Math.PI / 180)),
                skewDeg = rotateDeg2skewDeg(params.media_w, rotateDeg, sratio);
            
            if (180 <= rotateDeg)
            {
                rotateDeg = 180;
                sratio = 1;
            }
            
            if (0 <= rotateDeg && rotateDeg < 90)
            {
                if (d === 'l2r') { // めくり方向：left -> right
                    $(selectors.pages+'[rel='+clp+']', t).css({'transform': 'scale('+sratio+',1) skewY('+skewDeg+'deg)'});
                } else { // めくり方向：left <- right
                    skewDeg *= -1;
                    
                    $(selectors.pages+'[rel='+crp+']', t).css({'transform': 'scale('+sratio+',1) skewY('+skewDeg+'deg)'});
                }
            }
            else if (90 <= rotateDeg && rotateDeg <= 180)
            {
                if (d === 'l2r') { // めくり方向：left -> right
                    $(selectors.pages+'[rel='+clp+']', t).css({'transform': 'scale(0,1)'});
                    $(selectors.pages+'[rel='+nrp+']', t).css({'transform': 'scale('+sratio+',1) skewY('+skewDeg+'deg)'});
                } else { // めくり方向：left <- right
                    skewDeg *= -1;
                    
                    $(selectors.pages+'[rel='+crp+']', t).css({'transform': 'scale(0,1)'});
                    $(selectors.pages+'[rel='+nlp+']', t).css({'transform': 'scale('+sratio+',1) skewY('+skewDeg+'deg)'});
                }

                if (rotateDeg === 180)
                {
                    if (d === 'l2r') { // めくり方向：left -> right
                        $(selectors.pages+'[rel='+nrp+']', t).css({'transform': 'scale(1) skewY(0deg)'});
                    } else { // めくり方向：left <- right
                        $(selectors.pages+'[rel='+nlp+']', t).css({'transform': 'scale(1) skewY(0deg)'});
                    }
                    
                    $(selectors.pages+'[rel='+clp+']', t).css({'display': 'none', 'transform': 'scale(1)'});
                    $(selectors.pages+'[rel='+crp+']', t).css({'display': 'none', 'transform': 'scale(1)'});
                    methods.flipterminate.apply($(this), [t, Math.min(nlp, nrp), cue]);
                }
            }
        },
        flipterminate: function(){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var cue = arguments[2]; //cue object
            
            clearInterval(cue.timerid);
            cue.timerid = -1;
            cue.flipangle = 0;
            
            //キューの先頭を破棄する
            status.flipcue.cue.shift();
            
            if (p !== null && (status.flipcue.cue.length == 0 || p == 0 || p == params.lastpage - (params.lastpage % 2))) {
            	status.flipcue.length = 0;
            	status.flipcue.cue = new Array();
            	
	            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
	                methods.loadlinkdata.apply($(this), [t, p]);
	                methods.loadlinkdata.apply($(this), [t, p + 1]);
	                methods.createsearchrect.apply($(this), [t, p]);
	                methods.createsearchrect.apply($(this), [t, p + 1]);
	            } else {
	                methods.loadlinkdata.apply($(this), [t, p]);
	                methods.createsearchrect.apply($(this), [t, p]);
	            }
	            
	            $(selectors.paper_frame, t).css({'overflow': 'visible'});
	            
	            //Web-CRM：ページビューログの送信（少ない側のページ数に統一する）
	            webcrm_send_pageview(p - p % 2);
	            
	            status.curbasepage = p;
	            status.flipping = false;
	            
	            methods.setpagenum.apply($(this), [t]);
	            methods.changebuttonstatus.apply($(this), [t]);
	            methods.changedisplaystatus.apply($(this), [t]);
	            methods.resize.apply($(this), [t]);
	            methods.setprinthref.apply($(this), [t]);
	            
	            methods.adjustbookmarktag();
	            methods.displaycurpagememo();
	            methods.refreshsnslist.apply($(this), [t]);
	            
	            if (status.autoflip) {
	            	params.autoflip.timerID = setTimeout(function(){methods.autoflip.apply(t, [t])}, params.autoflip.interval);
	            } else {
	            	if (params.autoflip.timerID == -1) {
	                	clearTimeout(params.autoflip.timerID);
	            	}
	            	params.autoflip.timerID = -1;
	            }
            }
        },
        slidestart: function() {
            var t = arguments[0]; //target
            var d = arguments[1]; //direction
            var m = arguments[2]; //slide or rewind
            var cue = arguments[3];
            var nlp, nrp; //next left page, next right page (for flip)
            var clp, crp; //current left page, current right page (for flip)
            var plp, prp; //previous left page, previous right page (for flip)
            var np, cp, pp; //next page, current page, previous page (for slide)
            var pllp, plrp; //preload left page, right page
            var plp; //preload page (for slide)
            
            status.sliding = true;
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
            {
                // めくり方向の判定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (d === 'r2l'){ // スワイプの方向：left <- right
                            //前のページ
                            plp = status.curbasepage + 3;
                            prp = status.curbasepage + 2;
                            //めくり後に現れるページ
                            nlp = status.curbasepage - 1;
                            nrp = status.curbasepage - 2;
                            //プリロード用ページ
                            pllp = status.curbasepage - 3;
                            plrp = status.curbasepage - 4;
                        } else { // スワイプの方向：left -> right
                            //プリロード用ページ
                            pllp = status.curbasepage + 5;
                            plrp = status.curbasepage + 4;
                            //めくり後に現れるページ
                            nlp = status.curbasepage + 3;
                            nrp = status.curbasepage + 2;
                            //前のページ
                            plp = status.curbasepage - 1;
                            prp = status.curbasepage - 2;
                        }
                        
                        //現在のページ
                        clp = status.curbasepage + 1;
                        crp = status.curbasepage;

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (d === 'l2r') { // スワイプの方向：left -> right
                            //前のページ
                            plp = status.curbasepage + 3;
                            prp = status.curbasepage + 2;
                            //プリロード用ページ
                            pllp = status.curbasepage - 4;
                            plrp = status.curbasepage - 3;
                            //めくり後に現れるページ
                            nlp = status.curbasepage - 2;
                            nrp = status.curbasepage - 1;
                        } else { // スワイプの方向：left <- right
                            //めくり後に現れるページ
                            nlp = status.curbasepage + 2;
                            nrp = status.curbasepage + 3;
                            //プリロード用ページ
                            pllp = status.curbasepage + 4;
                            plrp = status.curbasepage + 5;
                            //前のページ
                            plp = status.curbasepage - 1;
                            prp = status.curbasepage - 2;
                        }
                        
                        //現在のページ
                        clp = status.curbasepage;
                        crp = status.curbasepage + 1;

                        break;
                }
                
                //めくり後のページが0～最大ページ数範囲内にない場合は処理を中断
                if (Math.min(nlp, nrp) < 0 || params.lastpage < Math.min(nlp, nrp)) {
                    m = DEF_PARAMS.SLIDEMODE.REWIND;
                }
                
                //ページの有無確認と、なければ追加（プリロード用）
                if ($(selectors.pages+'[rel='+pllp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), pllp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+plrp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), plrp, params.zoomlvs[params.basezlv]]);
                
                cue.clp = clp;
                cue.crp = crp;
                cue.nlp = nlp;
                cue.nrp = nrp;
                
                $(selectors.paper_frame, t).css({'width': params.paper_w+'px', 'height': params.media_h+'px', 'overflow': 'hidden'}); //紙面枠のサイズ設定
                $(selectors.pageimgs, t).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
                
                params.slidedirection = d;
                cue.timerid = setTimeout(methods.slideaction, 0, $(this), [t, m, cue.clp, cue.crp, cue.nlp, cue.nrp, plp, prp, cue]);
            } else {
                // めくり方向の判定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (d === 'r2l'){ // スワイプの方向：left <- right
                            //前のページ
                            pp = status.curbasepage + 1;
                            //めくり後に現れるページ
                            np = status.curbasepage - 1;
                            //プリロード用ページ
                            plp = status.curbasepage - 2;
                        } else { // スワイプの方向：left -> right
                            //前のページ
                            pp = status.curbasepage - 1;
                            //めくり後に現れるページ
                            np = status.curbasepage + 1;
                            //プリロード用ページ
                            plp = status.curbasepage + 2;
                        }
                        
                        //現在のページ
                        cp = status.curbasepage;

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (d === 'l2r') { // スワイプの方向：left -> right
                            //前のページ
                            pp = status.curbasepage + 1;
                            //めくり後に現れるページ
                            np = status.curbasepage - 1;
                            //プリロード用ページ
                            plp = status.curbasepage - 2;
                        } else { // スワイプの方向：left <- right
                            //前のページ
                            pp = status.curbasepage - 1;
                            //めくり後に現れるページ
                            np = status.curbasepage + 1;
                            //プリロード用ページ
                            plp = status.curbasepage + 2;
                        }
                        
                        //現在のページ
                        cp = status.curbasepage;
                        
                        break;
                }
                
                //めくり後のページが0～最大ページ数範囲内にない場合は処理を中断
                if (np < 0 || params.lastpage < np || is_blankpage(np, params.lastpage, settings.h0_invisible, settings.h5_invisible)) {
                    m = DEF_PARAMS.SLIDEMODE.REWIND;
                }
                
                //ページの有無確認と、なければ追加（プリロード用）
                if ($(selectors.pages+'[rel='+plp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), plp, params.zoomlvs[params.basezlv]]);
                
                $(selectors.paper_frame, t).css({'width': params.paper_w+'px', 'height': params.media_h+'px', 'overflow': 'hidden'}); //紙面枠のサイズ設定
                $(selectors.pageimgs, t).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
                
                cue.nlp = np;
                cue.clp = cp;
                
                params.slidedirection = d;
                cue.timerid = setTimeout(methods.slideaction, 0, $(this), [t, m, cue.clp, null, cue.nlp, null, pp, null, cue]);
            }
        },
        slideaction: function(){
            var t = arguments[1][0]; //target
            var m = arguments[1][1]; //slide or rewind
            var cue = arguments[1][8]; //cue object
            var d = params.slidedirection;
            var aop1 = {queue:false, duration: params.slideduration}; //アニメーションの基本オプション変数
            var aop2 = $.extend(true, {}, aop1); //completeコールバック関数設定追加用変数
            
            var clp, crp, nlp, nrp, plp, prp;
            var cp, np, pp;
            
//          cue.timerid = setTimeout(methods.slideaction, params.slidedelay, $(this), [t, DEF_PARAMS.SLIDEMODE.SLIDE, cp, np, null]);
//        	cue.timerid = setTimeout(methods.slideaction, params.slidedelay, $(this), [t, DEF_PARAMS.SLIDEMODE.SLIDE, cue.clp, cue.crp, cue.nlp, cue.nrp, cue]);

            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                clp = arguments[1][2]; crp = arguments[1][3];
                nlp = arguments[1][4]; nrp = arguments[1][5];
                plp = arguments[1][6]; prp = arguments[1][7];
                
                if (m === DEF_PARAMS.SLIDEMODE.REWIND) {
                    aop2.complete = function () {
                        $(selectors.pages+'[rel='+nlp+']', t).css({'display': 'none'});
                        $(selectors.pages+'[rel='+nrp+']', t).css({'display': 'none'});
                        $(selectors.pages+'[rel='+plp+']', t).css({'display': 'none'});
                        $(selectors.pages+'[rel='+prp+']', t).css({'display': 'none'});
                        methods.slideterminate.apply($(this), [t, Math.min(clp, crp), cue]);
                    };
                    
                    if (d === 'l2r') { // めくり方向：left -> right
                        $(selectors.pages+'[rel='+plp+']', t).animate({'left': (params.media_w * 2)+'px'}, aop1);
                        $(selectors.pages+'[rel='+prp+']', t).animate({'left': (params.media_w * 3)+'px'}, aop2);
                        $(selectors.pages+'[rel='+clp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop1);
                        $(selectors.pages+'[rel='+crp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop1);
                        $(selectors.pages+'[rel='+nlp+']', t).animate({'left': (params.media_w * -2)+'px'}, aop1);
                        $(selectors.pages+'[rel='+nrp+']', t).animate({'left': (params.media_w * -1)+'px'}, aop2);
                    } else if (d === 'r2l') { // めくり方向：left <- right
                        $(selectors.pages+'[rel='+plp+']', t).animate({'left': (params.media_w * -2)+'px'}, aop1);
                        $(selectors.pages+'[rel='+prp+']', t).animate({'left': (params.media_w * -1)+'px'}, aop2);
                        $(selectors.pages+'[rel='+clp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop1);
                        $(selectors.pages+'[rel='+crp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop1);
                        $(selectors.pages+'[rel='+nlp+']', t).animate({'left': (params.media_w * 2)+'px'}, aop1);
                        $(selectors.pages+'[rel='+nrp+']', t).animate({'left': (params.media_w * 3)+'px'}, aop2);
                    }
                    
                } else {
                    aop2.complete = function () {
                    	if (cue.slidetype == 'tile') {
	                        $(selectors.pages+'[rel='+clp+']', t).css({'display': 'none'});
	                        $(selectors.pages+'[rel='+crp+']', t).css({'display': 'none'});
	                        $(selectors.pages+'[rel='+plp+']', t).css({'display': 'none'});
	                        $(selectors.pages+'[rel='+prp+']', t).css({'display': 'none'});
                    	}
                        methods.slideterminate.apply($(this), [t, Math.min(nlp, nrp), cue]);
                    };

                	if (cue.slidetype == 'tile') {
	                    if (d === 'l2r') { // めくり方向：left -> right
	                        $(selectors.pages+'[rel='+clp+']', t).animate({'left': (params.media_w * 2)+'px'}, aop1);
	                        $(selectors.pages+'[rel='+crp+']', t).animate({'left': (params.media_w * 3)+'px'}, aop1);
	                        
	                        // オブジェクトの有無を確認してからaop2をセットする
	                        if ($(selectors.pages+'[rel='+nrp+']').length) {
		                        $(selectors.pages+'[rel='+nlp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop1);
		                        $(selectors.pages+'[rel='+nrp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop2);
	                        } else {
		                        $(selectors.pages+'[rel='+nlp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop2);
		                        $(selectors.pages+'[rel='+nrp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop1);
	                        }
	                    } else if (d === 'r2l') { // めくり方向：left <- right
	                        $(selectors.pages+'[rel='+clp+']', t).animate({'left': (params.media_w * -2)+'px'}, aop1);
	                        $(selectors.pages+'[rel='+crp+']', t).animate({'left': (params.media_w * -1)+'px'}, aop1);
	                        
	                        // オブジェクトの有無を確認してからaop2をセットする
	                        if ($(selectors.pages+'[rel='+nrp+']').length) {
		                        $(selectors.pages+'[rel='+nlp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop1);
		                        $(selectors.pages+'[rel='+nrp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop2);
	                        } else {
	                            $(selectors.pages+'[rel='+nlp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop2);
	                            $(selectors.pages+'[rel='+nrp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop1);
	                        }
	                    }
                	} else {
	                    if (d === 'l2r') { // めくり方向：left -> right
	                        // オブジェクトの有無を確認してからaop2をセットする
	                        if ($(selectors.pages+'[rel='+nrp+']').length) {
		                        $(selectors.pages+'[rel='+nlp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop1);
		                        $(selectors.pages+'[rel='+nrp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop2);
	                        } else {
		                        $(selectors.pages+'[rel='+nlp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop2);
		                        $(selectors.pages+'[rel='+nrp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop1);
	                        }
	                    } else if (d === 'r2l') { // めくり方向：left <- right
	                        // オブジェクトの有無を確認してからaop2をセットする
	                        if ($(selectors.pages+'[rel='+nrp+']').length) {
		                        $(selectors.pages+'[rel='+nlp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop1);
		                        $(selectors.pages+'[rel='+nrp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop2);
	                        } else {
	                            $(selectors.pages+'[rel='+nlp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop2);
	                            $(selectors.pages+'[rel='+nrp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop1);
	                        }
	                    }
                	}
                }
            } else {
                cp = arguments[1][2];
                np = arguments[1][4];
                pp = arguments[1][6];
                
                if (m === DEF_PARAMS.SLIDEMODE.REWIND) {
                    aop2.complete = function () {
                        $(selectors.pages+'[rel='+np+']', t).css({'display': 'none'});
                        $(selectors.pages+'[rel='+pp+']', t).css({'display': 'none'});
                        methods.slideterminate.apply($(this), [t, cp, cue]);
                    };

                    if (d === 'l2r') { // めくり方向：left -> right
                        $(selectors.pages+'[rel='+pp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop2);
                        $(selectors.pages+'[rel='+cp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop1);
                        $(selectors.pages+'[rel='+np+']', t).animate({'left': (params.media_w * -1)+'px'}, aop2);
                    } else if (d === 'r2l') { // めくり方向：left <- right
                        $(selectors.pages+'[rel='+pp+']', t).animate({'left': (params.media_w * -1)+'px'}, aop2);
                        $(selectors.pages+'[rel='+cp+']', t).animate({'left': (params.media_w * 0)+'px'}, aop1);
                        $(selectors.pages+'[rel='+np+']', t).animate({'left': (params.media_w * 1)+'px'}, aop2);
                    }
                } else {
                    aop2.complete = function () {
                    	if (cue.slidetype == 'tile') {
	                        $(selectors.pages+'[rel='+cp+']', t).css({'display': 'none'});
	                        $(selectors.pages+'[rel='+pp+']', t).css({'display': 'none'});
                    	}
                        methods.slideterminate.apply($(this), [t, np, cue]);
                    };

                	if (cue.slidetype == 'tile') {
	                    if (d === 'l2r') { // めくり方向：left -> right
	                        $(selectors.pages+'[rel='+cp+']', t).animate({'left': (params.media_w * 1)+'px'}, aop1);
	                        $(selectors.pages+'[rel='+np+']', t).animate({'left': (params.media_w * 0)+'px'}, aop2);
	                    } else if (d === 'r2l') { // めくり方向：left <- right
	                        $(selectors.pages+'[rel='+cp+']', t).animate({'left': (params.media_w * -1)+'px'}, aop1);
	                        $(selectors.pages+'[rel='+np+']', t).animate({'left': (params.media_w * 0)+'px'}, aop2);
	                    }
                	} else {
	                    if (d === 'l2r') { // めくり方向：left -> right
	                        $(selectors.pages+'[rel='+np+']', t).animate({'left': (params.media_w * 0)+'px'}, aop2);
	                    } else if (d === 'r2l') { // めくり方向：left <- right
	                        $(selectors.pages+'[rel='+np+']', t).animate({'left': (params.media_w * 0)+'px'}, aop2);
	                    }
                	}
                }
            }
        },
        slideterminate: function(){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var cue = arguments[2]; //cue object
            
            clearTimeout(cue.timerid);
            cue.timerid = -1;
            
            //キューの先頭を破棄する
            status.flipcue.cue.shift();
            
            if (p !== null && (status.flipcue.cue.length == 0 || p == 0 || p == params.lastpage - (params.lastpage % 2))) {
            	status.flipcue.length = 0;
            	status.flipcue.cue = new Array();
            	
	            $(selectors.pages).hide();
	            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
	                $(selectors.pages+'[rel='+p+']', t).show();
	                $(selectors.pages+'[rel='+(p+1)+']', t).show();
	                methods.loadlinkdata.apply($(this), [t, p]);
	                methods.loadlinkdata.apply($(this), [t, p + 1]);
	                $(selectors.paper_frame, t).css({'overflow': 'visible'});
	            } else {
	                $(selectors.pages+'[rel='+p+']', t).show();
	                methods.loadlinkdata.apply($(this), [t, p]);
	                $(selectors.paper_frame, t).css({'overflow': 'visible'});
	            }
	            
	            //Web-CRM：ページビューログの送信（少ない側のページ数に統一する）
	            webcrm_send_pageview(p - p % 2);
	            
	            status.curbasepage = p;
	            status.sliding = false;
	            
	            methods.setpagenum.apply(t, [t]);
	            methods.resize.apply(t, [t]);
	            methods.setprinthref.apply($(this), [t]);
	            
	            methods.changebuttonstatus.apply(t, [t]);
	            methods.changedisplaystatus.apply(t, [t]);
	            
	            methods.adjustbookmarktag();
	            methods.displaycurpagememo();
	            methods.refreshsnslist.apply($(this), [t]);
	            
	        	$('.tag_area').show();
	        	$(selectors.paper_frame).css({'overflow': 'visible'});
	        	
	        	if (status.autoflip) {
	            	params.autoflip.timerID = setTimeout(function(){methods.autoflip.apply(t, [t])}, params.autoflip.interval);
	            } else {
	            	if (params.autoflip.timerID == -1) {
	                	clearTimeout(params.autoflip.timerID);
	            	}
	            	params.autoflip.timerID = -1;
	            }
            }
        },
        loadlinkdata: function(){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            
            if (links['p'+p] === undefined && !is_blankpage(p, params.lastpage, settings.h0_invisible, settings.h5_invisible))
            {
                $.ajax({ 
                    type: "GET", 
                    url: settings.xmldir+'/EBookLinkDataParam_'+('0000' + p).substr(-4)+'.xml',
                    dataType: "xml", 
                    success: function(xml, status){
                        links['p'+p] = $(xml).find('LinkData');
                        if(status === 'success')
                            methods.createlinkrect.apply($(this), [t, p, links['p'+p]]);
                    },
                    error: function (r, s, e){
                        links['p'+p] = new Array();
                    },
                    complete: function (r, e){}
                });
            }
        },
        createlinkrect: function(){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var l = arguments[2]; //links
            
            var html;
            var x1, x2, y1, y2, link, linkurlflg, linktarget, linkurl, linkpage, linktype;
            var otc, ota, otbc, otbw, ovc, ova, ovbc, ovbw;
            var cnt = 0;
            
            l.each(function(){
                linkurlflg = $(this).find('LinkURLFlag').text().toLowerCase();
                linktarget = $(this).find('LinkTARGET').text();
                linkurl = $(this).find('LinkURL').text();
                linkpage = $(this).find('LinkPage').text();
                x1 = $(this).find('LinkAreaXStr').text();
                y1 = $(this).find('LinkAreaYStr').text();
                x2 = $(this).find('LinkAreaXLen').text();
                y2 = $(this).find('LinkAreaYLen').text();
                
                otc = $(this).find('LinkAreaMouseOutColor').text();
                ota = parseInt($(this).find('LinkAreaMouseOutTransparent').text()) / 255;
                otbc = $(this).find('LinkAreaMouseOutOutLineColor').text();
                otbw = parseInt($(this).find('LinkAreaMouseOutOutLineWidth').text());
                
                ovc = $(this).find('LinkAreaMouseOverColor').text();
                ova = parseInt($(this).find('LinkAreaMouseOverTransparent').text()) / 255;
                ovbc = $(this).find('LinkAreaMouseOverOutLineColor').text();
                ovbw = parseInt($(this).find('LinkAreaMouseOverOutLineWidth').text());
                
                if (linkurlflg === 'false') {
                    linktype = DEF_PARAMS.LINKTYPE.PAGE;
                    link = linkpage;
                    linktarget = '_self';
                } else {
                    linktype = DEF_PARAMS.LINKTYPE.URL;
                    link = linkurl;
                }
                
                var x = parseInt(x1) * params.pscale * params.basescale;
                var y = parseInt(y1) * params.pscale * params.basescale;
                
                var w = (parseInt(x2) - otbw) * params.pscale * params.basescale;
                var h = (parseInt(y2) - otbw) * params.pscale * params.basescale;
                
                html = '<div class="link_rect" style="top:' + y + 'px; left:' + x + 'px; width:' + w + 'px; height:' + h + 'px;"'
                		+ ' data-type="'+linktype+'" data-link="'+link+'" data-target="'+linktarget+'" data-pos="'+x1+','+y1+','+x2+','+y2+'"'
                		+ ' data-otc="'+otc+'" data-ota="'+ota+'" data-otbc="'+otbc+'"  data-otbw="'+otbw+'"'
                		+ ' data-ovc="'+ovc+'" data-ova="'+ova+'" data-ovbc="'+ovbc+'"  data-ovbw="'+ovbw+'"'
                		+ '></div>';

                if ($(".page[rel=" + p + "]", selectors.paper_frame).length)
                {
                    $(".page[rel=" + p + "]", selectors.paper_frame).append(html);
                }
                
	            $(".page[rel=" + p + "] .link_rect:eq("+cnt+")", selectors.paper_frame).css({
	                'opacity': ota,
	                'border-color': '#'+otbc,
	                'border-width': otbw+'px',
	                'background-color': '#'+otc
	            });
	            
	            cnt++;
            });
        },
        createlinkrectzoomarea: function(){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            
            var html;
            var x1, x2, y1, y2, link, linkurlflg, linktarget, linkurl, linkpage, linktype;
            var otc, ota, otbc, otbw, ovc, ova, ovbc, ovbw;
            var cnt = 0;
            
            $(".page[rel=" + p + "] .link_rect", selectors.paper_frame).each(function(){
                var points = $(this).data('pos').split(','),
	                x1 = parseInt(points[0]),
	                y1 = parseInt(points[1]),
	                x2 = parseInt(points[2]),
	                y2 = parseInt(points[3]);
                
                var otbw = parseInt($(this).data('otbw'));

                var x = parseInt(x1) * params.zscale.scale;
                var y = parseInt(y1) * params.zscale.scale;
                
                var w = (parseInt(x2) - otbw) * params.zscale.scale;
                var h = (parseInt(y2) - otbw) * params.zscale.scale;
                
                var link_rect = $(this).clone(true);
                link_rect.css({
                	top: y+'px',
                	left: x+'px',
                	width: w+'px',
                	height: h+'px'
        		});
                
                $("#zoom_frame div.page[rel=" + p + "]").append(link_rect);
            });
        },
        linkaction: function(){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            
        },
        createsearchrect: function(){
        	var t = arguments[0]; //target
            var tp = arguments[1]; //target page num
            
            var _match = params.searchall.match;
            var cleared = false;
            
            for (var i = 0, l = _match.length; i < l; i++) {
                var html;
                var p = parseInt(_match[i]["page"]) - 1;
                
                var x1 = _match[i]["x1"];
                var y1 = _match[i]["y1"];
                var x2 = _match[i]["x2"];
                var y2 = _match[i]["y2"];
                
                var x = parseInt(x1) / params.searchall.datapntratio * params.pscale * params.basescale;
                var y = parseInt(y1) / params.searchall.datapntratio * params.pscale * params.basescale;

                var w = (parseInt(x2) - parseInt(x1)) / params.searchall.datapntratio * params.pscale * params.basescale;
                var h = (parseInt(y2) - parseInt(y1)) / params.searchall.datapntratio * params.pscale * params.basescale;

                if (tp == p) {
                	if (cleared === false) {
                		cleared = true;
                		$(".page[rel=" + p + "] .search_rect").remove();
                	}
                	
	                html = '<div class="search_rect" style="top:' + y + 'px; left:' + x + 'px; width:' + w + 'px; height:' + h + 'px;"'
            		+ ' data-pos="'+x1+','+y1+','+x2+','+y2+'"></div>';

		            if ($(".page[rel=" + p + "]", selectors.paper_frame).length)
		            {
		                $(".page[rel=" + p + "]", selectors.paper_frame).append(html);
		            }
                }
                
	            $(".page[rel=" + p + "] .search_rect:eq("+i+")", selectors.paper_frame).css({
	                'opacity': 0.5,
	                'border-color': '#F00',
	                'border-width': '1px',
	                'background-color': '#F00'
	            });
            }
        },
        createsearchrectzoomarea: function(){
            var t = arguments[0]; //target
            
            var _match = params.searchall.match;
            
            for (var i = 0, l = _match.length; i < l; i++) {
            	var html;
            	var p = parseInt(_match[i]["page"]) - 1;

                if ($("#zoom_frame div.page[rel=" + p + "]").length) {
                    var x1 = _match[i]["x1"];
                    var y1 = _match[i]["y1"];
                    var x2 = _match[i]["x2"];
                    var y2 = _match[i]["y2"];
                    
                    var x = parseInt(x1) / params.searchall.datapntratio * params.zscale.scale;
                    var y = parseInt(y1) / params.searchall.datapntratio * params.zscale.scale;

                    var w = (parseInt(x2) - parseInt(x1)) / params.searchall.datapntratio * params.zscale.scale;
                    var h = (parseInt(y2) - parseInt(y1)) / params.searchall.datapntratio * params.zscale.scale;
                	
                    html = '<div class="search_rect" style="top:' + y + 'px; left:' + x + 'px; width:' + w + 'px; height:' + h + 'px;"'
                    + 'data-pos="' + x1 + ',' + y1 + ',' + x2 + ',' + y2 + '"></div>';

                    $("#zoom_frame div.page[rel=" + p + "]").append(html);
                }
            }
            
            $("#zoom_frame div.page .search_rect").css({
                'opacity': 0.5,
                'border-color': '#F00',
                'border-width': '1px',
                'background-color': '#F00'
            });
        },
        orientationchangeHandler: function(e){
            var $this = e.data.thisobj,
                data = $this.data('dmxLivebook');
            
            if (status.changespread === false) {
                setTimeout(_orientationChange, 500, $this, data.target, false);
            }
        },
        changespread: function(){
            var $this = arguments[0],
                force = arguments[1],
                data = $this.data('dmxLivebook'),
                opt_s = get_spreadmode(); //optimum spread mode (by current orientation)
            var lp, rp;
            var nlp, nrp; //next left page, next right page
            var plp, prp; //previous left page, previous right page
            var np, pp; //next page, current page (for single)
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.SINGLE &&
                (opt_s === DEF_PARAMS.SPREADMODE.DOUBLE || force)) { //landscape: single -> double
                
                // めくり方向の判定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (status.curbasepage % 2 === 0){ //見開き状態での右ページを表示中
                            lp = status.curbasepage + 1;
                            rp = status.curbasepage;
                        } else { //見開き状態での左ページを表示中
                            lp = status.curbasepage;
                            rp = status.curbasepage - 1;
                        }

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (status.curbasepage % 2 === 0){ //見開き状態での左ページを表示中
                            lp = status.curbasepage;
                            rp = status.curbasepage + 1;
                        } else { //見開き状態での右ページを表示中
                            lp = status.curbasepage - 1;
                            rp = status.curbasepage;
                        }

                        break;
                }
                
                //先のページ
                nlp = lp + 2;
                nrp = rp + 2;
                //前のページ
                plp = lp - 2;
                prp = rp - 2;
                
                if ($(selectors.pages+'[rel='+lp+']', $this).length === 0) methods.addpage.apply($this, [$(selectors.paper_frame), lp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                if ($(selectors.pages+'[rel='+rp+']', $this).length === 0) methods.addpage.apply($this, [$(selectors.paper_frame), rp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                if ($(selectors.pages+'[rel='+nlp+']', $this).length === 0) methods.addpage.apply($this, [$(selectors.paper_frame), nlp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                if ($(selectors.pages+'[rel='+nrp+']', $this).length === 0) methods.addpage.apply($this, [$(selectors.paper_frame), nrp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                if ($(selectors.pages+'[rel='+plp+']', $this).length === 0) methods.addpage.apply($this, [$(selectors.paper_frame), plp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                if ($(selectors.pages+'[rel='+prp+']', $this).length === 0) methods.addpage.apply($this, [$(selectors.paper_frame), prp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                
                methods.changespreadstart.apply($this, [data.target, DEF_PARAMS.SPREADMODE.DOUBLE]);
                
            } else if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE &&
                (opt_s === DEF_PARAMS.SPREADMODE.SINGLE || force)) { //portrait: double -> single
                
                //先のページ
                np = status.curbasepage + 1;
                //前のページ
                pp = status.curbasepage - 1;
                
                if ($(selectors.pages+'[rel='+np+']', $this).length === 0) methods.addpage.apply($this, [$(selectors.paper_frame), np, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                if ($(selectors.pages+'[rel='+pp+']', $this).length === 0) methods.addpage.apply($this, [$(selectors.paper_frame), pp, DEF_PARAMS.PAGEZOOMNUM.NORMAL]);
                
                methods.changespreadstart.apply($this, [data.target, DEF_PARAMS.SPREADMODE.SINGLE]);
                
            } else {
                //端末が横向き状態で単ページ表示にしたのち、端末を縦向きにした場合の処理
                if (opt_s === DEF_PARAMS.SPREADMODE.SINGLE) {
                    methods.changespreadstart.apply($this, [data.target, opt_s]);
                }
            }
        },
        changespreadstart: function(){
            var $this = arguments[0],
                s = arguments[1],
                data = $this.data('dmxLivebook'),
                wiw = $(window).innerWidth(),
                wih = $(window).innerHeight(),
                t = $this,
                tmp, lp, rp, lx, rx,
                lppos, rppos;
            
            status.changespread = true;
            
            $(selectors.paper_frame).css('overflow', 'hidden');
            
            if (s === DEF_PARAMS.SPREADMODE.DOUBLE) {
                params.paper_cols = 2;
                params.paper_rows = 1;
                
                // めくり方向の判定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (status.curbasepage % 2 === 0){ //見開き状態での右ページを表示中
                            lp = status.curbasepage + 1;
                            rp = status.curbasepage;
                            lx = - params.media_w;
                            rx = 0;
                        } else { //見開き状態での左ページを表示中
                            lp = status.curbasepage;
                            rp = status.curbasepage - 1;
                            lx = 0;
                            rx = params.media_w;
                        }

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (status.curbasepage % 2 === 0){ //見開き状態での左ページを表示中
                            lp = status.curbasepage;
                            rp = status.curbasepage + 1;
                            lx = 0;
                            rx = params.media_w;
                        } else { //見開き状態での右ページを表示中
                            lp = status.curbasepage - 1;
                            rp = status.curbasepage;
                            lx = - params.media_w;
                            rx = 0;
                        }

                        break;
                }
                
                $(selectors.pages+'[rel='+lp+']', t).css({'display': 'block', 'left': lx+'px'});
                $(selectors.pages+'[rel='+rp+']', t).css({'display': 'block', 'left': rx+'px'});
            } else if (s === DEF_PARAMS.SPREADMODE.SINGLE) {
                params.paper_cols = 1;
                params.paper_rows = 1;
            }
            
            status.spreadmode = s;
            tmp = get_resize_param(wiw, wih, params.paper_cols, params.paper_rows, params.paper_outer, params.zoom_outer);
            
            if (s === DEF_PARAMS.SPREADMODE.SINGLE) {
                // めくり方向の判定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        //右ページをアクティブにする
                        lppos = -tmp.mdw;
                        rppos = 0;

                        if (status.curbasepage === 0
                            && is_blankpage(0, params.lastpage, settings.h0_invisible, settings.h5_invisible)){
                            //左ページをアクティブにする
                            lppos = 0;
                            rppos = tmp.mdw;
                        }
                        
                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        //左ページをアクティブにする
                        lppos = 0;
                        rppos = tmp.mdw;

                        if (status.curbasepage === 0
                            && is_blankpage(0, params.lastpage, settings.h0_invisible, settings.h5_invisible)){
                            //右ページをアクティブにする
                            lppos = -tmp.mdw;
                            rppos = 0;
                        }
                        
                        break;
                }
            } else {
                lppos = 0;
                rppos = tmp.mdw;
            }
            
            $(selectors.paper_frame).animate({
                'width': tmp.ppw+'px',
                'height': tmp.pph+'px',
                'left': tmp.ppx+'px',
                'top': tmp.ppy+'px'
            }, {
                queue: false,
                duration: params.changespreadduration
            });

            $('img', selectors.pages).animate({
                'width': tmp.mdw+'px',
                'height': tmp.mdh+'px'
            }, {
                queue: false,
                duration: params.changespreadduration
            });

            $(selectors.leftpages).animate({
                'left': lppos+'px'
            }, {
                queue: false,
                duration: params.changespreadduration
            });
            
            $(selectors.rightpages).animate({
                'left': rppos+'px'
            }, {
                queue: false,
                duration: params.changespreadduration
            });
            
            $(selectors.vindex + ' .ui-content').animate({
                'height': (wih - $(selectors.vindex + ' .ui-header').outerHeight() - $(selectors.vindex + ' .ui-footer').outerHeight() - ($(selectors.vindex + ' .ui-content').innerHeight() - $(selectors.vindex + ' .ui-content').height()))+'px'
            }, {
                queue: false,
                duration: params.changespreadduration
            });
            $(selectors.tindex + ' .ui-content').animate({
                'height': (wih - $(selectors.tindex + ' .ui-header').outerHeight() - $(selectors.tindex + ' .ui-footer').outerHeight() - ($(selectors.tindex + ' .ui-content').innerHeight() - $(selectors.tindex + ' .ui-content').height()))+'px'
            }, {
                queue: false,
                duration: params.changespreadduration
            });
            
            //ここでcallback登録することで、complete中の$(this)を他と合わせる
            $(selectors.livebook).animate({
                'height': tmp.wih+'px'
            }, {
                queue: false,
                duration: params.changespreadduration,
                complete: methods.changespreadterminate
            });
        },
        changespreadterminate: function(){
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
                status.curbasepage -= status.curbasepage % 2; //見開きでは左右ページの小さいページ数を基準とする
                $(selectors.paper_frame).css('overflow', 'visible');
                methods.loadlinkdata.apply($(selectors.livebook), [$(selectors.livebook), status.curbasepage]);
                methods.loadlinkdata.apply($(selectors.livebook), [$(selectors.livebook), status.curbasepage + 1]);
                $(selectors.pageflipbutton).show();
                $(selectors.bookthick).show();
                $(selectors.bookgutter).show();
                methods.setprinthref.apply($(selectors.livebook), [$(selectors.livebook)]);
            } else {
                if (status.curbasepage === 0
                    && is_blankpage(0, params.lastpage, settings.h0_invisible, settings.h5_invisible)){
                    $(selectors.pages+'[rel='+(status.curbasepage)+']').css({'display': 'none'});
                    status.curbasepage += 1;
                } else {
                    $(selectors.pages+'[rel='+(status.curbasepage + 1)+']').css({'display': 'none'});
                }
                
                $(selectors.pageflipbutton).hide();
                $(selectors.bookthick).hide();
                $(selectors.bookgutter).hide();
                
                $(selectors.paper_frame).css('overflow', 'visible');
            }
            
            methods.adjustbookmarktag();
            
            methods.setpagenum.apply($(selectors.livebook), [$(selectors.livebook)]);
            methods.changebuttonstatus.apply($(selectors.livebook), [$(selectors.livebook)]);
            methods.changedisplaystatus.apply($(selectors.livebook), [$(selectors.livebook)]);
    
            status.changespread = false;
            methods.resize.apply($(this), [$(this)]);
        },
        resizeHandler: function(e){
            var $this = e.data.thisobj,
                data = $this.data('dmxLivebook');
            
            methods.resize.apply($this, [data.target]);
        },
        resize: function(){
            var wsw, wsh, ws, psw, psh, ps;
            var $this = arguments[0],
                data = $this.data('dmxLivebook');
            var pfbpnt; //ページめくりボタン用座標
        	var wr,hw,hh,hl,ht,pw = 10,ph = 10,ho = 'hidden';
            
            //ウィンドウのサイズを取得
            params.win_w = $(window).innerWidth();
            params.win_h = $(window).innerHeight();
            
            params.paper_outer.w = params.win_w - params.paper_outer.l - params.paper_outer.r;
            params.paper_outer.h = params.win_h - params.paper_outer.t - params.paper_outer.b;
            params.zoom_outer = params.paper_outer;
            
            var zo = get_zoom_outer_size(params.win_w, params.win_h, params.zoom_outer);
            params.zoom_outer_x = zo.l;
            params.zoom_outer_y = zo.t;
            params.zoom_outer_w = zo.w;
            params.zoom_outer_h = zo.h;
            
            var tmp = get_resize_param(params.win_w, params.win_h, params.paper_cols, params.paper_rows, params.paper_outer, params.zoom_outer);
            
            //起動時ウィンドウと現ウィンドウサイズのスケール
            wsw = tmp.wsw;
            wsh = tmp.wsh;
            //縦と横で小さい方を縦横共通スケールとして採用
            if (wsh < wsw) ws = wsh;
            else ws = wsw;
            
            //ページ幅のスケールの計算
            psw = tmp.psw;
            psh = tmp.psh;
            ps = tmp.ps;
            
            //紙面サイズ
            params.media_w = tmp.mdw; params.media_h = tmp.mdh;

            //紙面枠
            params.paper_w = tmp.ppw; params.paper_h = tmp.pph;

            //紙面枠の位置
            params.paper_x = tmp.ppx; params.paper_y = tmp.ppy;
            
            $(selectors.paper_frame).css({
            	'left': params.paper_x+'px',
            	'top': params.paper_y+'px',
            	'width': params.paper_w+'px',
            	'height': params.paper_h+'px'
            });
            
            // 紙面の厚みの位置
            if (settings.device == 'pc') {
            	$(selectors.bookthickt).css({'top': params.paper_y+'px'});
            	$(selectors.bookthickr).css({'left': (params.paper_x + params.paper_w + 2)+'px'});
            	$(selectors.bookthickb).css({'top': (params.paper_y + params.paper_h - DEF_PARAMS.BOOKTHICK.BH + 2)+'px'});
            	$(selectors.bookthickl).css({'left': (params.paper_x - DEF_PARAMS.BOOKTHICK.W)+'px'});
            	
            	$(selectors.bookthicklm).css({
            		'top': (params.paper_y + DEF_PARAMS.BOOKTHICK.TH)+'px',
            		'height': (params.paper_h - DEF_PARAMS.BOOKTHICK.TH - DEF_PARAMS.BOOKTHICK.BH + 2)+'px'
        		});
            	$(selectors.bookthickrm).css({
            		'top': (params.paper_y + DEF_PARAMS.BOOKTHICK.TH)+'px',
            		'height': (params.paper_h - DEF_PARAMS.BOOKTHICK.TH - DEF_PARAMS.BOOKTHICK.BH + 2)+'px'
        		});
            	
            	$(selectors.bookgutter).css({
            		'top': (params.paper_y + 1)+'px',
            		'height': params.paper_h+'px',
            		'width': DEF_PARAMS.BOOKGUTTER.W+'px'
        		});
            	$(selectors.bookgutterl).css({'left': (params.paper_x + params.media_w - DEF_PARAMS.BOOKGUTTER.W)+'px'});
            	$(selectors.bookgutterr).css({'left': (params.paper_x + params.media_w)+'px'});
            }
            
            // ノンブルの位置
            if (settings.device == 'pc' && settings.disp_nombre) {
            	methods.adjustnombre(ps);
            }
            
            //新しい紙面サイズの適用
            $('img', selectors.pages).width(params.media_w).height(params.media_h);
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
                $(selectors.rightpages).css('left', params.media_w);
            } else {
                $(selectors.pages).css('left', '0px');
            }
            
            //拡大枠
            $(selectors.zoom_outer).css({
            	'left': params.zoom_outer_x+'px',
            	'top': params.zoom_outer_y+'px',
            	'width': params.zoom_outer_w+'px',
            	'height': params.zoom_outer_h+'px'
            });
            
            //LiveBookオブジェクト全体の高さ調整
            $(selectors.livebook).height(params.win_h);
            
            $(".page[rel=" + status.curbasepage + "] .link_rect", selectors.paper_frame).each(function(){
                linkadjust.apply(this, [ps]);
            });
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
	            $(".page[rel=" + status.curbasepage + "] .search_rect", selectors.paper_frame).each(function(){
	                searchrectadjust.apply(this, [ps]);
	            });
	            $(".page[rel=" + (status.curbasepage + 1) + "] .search_rect", selectors.paper_frame).each(function(){
	                searchrectadjust.apply(this, [ps]);
	            });
            } else {
	            $(".page[rel=" + status.curbasepage + "] .search_rect", selectors.paper_frame).each(function(){
	                searchrectadjust.apply(this, [ps]);
	            });
            }
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
            {
                $(".page[rel=" + (status.curbasepage + 1) + "] .link_rect", selectors.paper_frame).each(function(){
                    linkadjust.apply(this, [ps]);
                });
            }
            
            pfbpnt = {
        		'display': 'block',
        		'top': (params.paper_h - $(selectors.pageflipbuttonl).height()) / 2,
        		'left': 0,
        		'right': 0,
                'width': (params.win_w - params.paper_w) / 2 + params.pageflipbutton_padding,
                'height': params.paper_h
            };
            pfbpnt.left = (pfbpnt.width - $(selectors.pageflipbuttonl).width()) / 2;
            pfbpnt.right = (pfbpnt.width - $(selectors.pageflipbuttonr).width()) / 2;
            
            // 表示サイズが確保できない場合は非表示にする
            if (pfbpnt.width < $(selectors.pageflipbuttonl).width()) {
            	pfbpnt.display = 'none';
            }
            
            $(selectors.pageflipbuttonl).css({
            	'display': pfbpnt.display,
                'top': pfbpnt.top+'px',
                'left': pfbpnt.left+'px'
            });
            $(selectors.pageflipbuttonr).css({
            	'display': pfbpnt.display,
                'top': pfbpnt.top+'px',
                'right': pfbpnt.right+'px'
            });
            
            $(selectors.zoommovebuttont).css({
                'top': params.zoom_outer_y+'px',
                'left': ((params.zoom_outer_w / 2) - ($(selectors.zoommovebuttont).width() / 2))+'px'
            });
            $(selectors.zoommovebuttonr).css({
                'top': (params.zoom_outer_y + (params.zoom_outer_h / 2) - ($(selectors.zoommovebuttonr).height() / 2))+'px',
                'left': (params.zoom_outer_x + params.zoom_outer_w - $(selectors.zoommovebuttonr).width())+'px'
            });
            $(selectors.zoommovebuttonb).css({
                'top': (params.zoom_outer_y + params.zoom_outer_h - $(selectors.zoommovebuttonb).height())+'px',
                'left': ((params.zoom_outer_w / 2) - ($(selectors.zoommovebuttonb).width() / 2))+'px'
            });
            $(selectors.zoommovebuttonl).css({
                'top': (params.zoom_outer_y + (params.zoom_outer_h / 2) - ($(selectors.zoommovebuttonb).width() / 2))+'px',
                'left': params.zoom_outer_x+'px'
            });
            
            if (settings.device != 'pc') {
	            $(selectors.vindex+','+selectors.vindexbg+','+selectors.sns+','+selectors.snsbg+','+selectors.snswrapbg).css({
	                'top': 0,
	                'left': 0,
	                'width': params.win_w+'px',
	                'height': params.win_h+'px'
	            });
	            $(selectors.vindexwrapbg+','+selectors.vindexfg).css({
	                'bottom': settings.paper_outer.b+'px',
	                'left': 0,
	                'width': params.win_w+'px',
	                'height': (params.thumb_h + 30)+'px'
	            });
	            
	            adjust_thumb_position();
            } else {
            	$('#tindex_dialog', selectors.tindex).css({
            		'top': (params.paper_y + 10)+'px',
            		'left': (params.paper_x + 10)+'px',
            		'width': (params.paper_w - 20)+'px',
            		'height': (params.paper_h - 20)+'px',
            	});
            	$('#tindex_dialog .list_wrapper', selectors.tindex).css({
            		'top': '30px',
            		'left': '10px',
            		'width': ($('#tindex_dialog').width() - 30)+'px',
            		'height': ($('#tindex_dialog').height() - 50)+'px'
            	});

            	$('#vindex_dialog', selectors.vindex).css({
            		'top': (params.paper_y + 10)+'px',
            		'left': (params.paper_x + 10)+'px',
            		'width': (params.paper_w - 20)+'px',
            		'height': (params.paper_h - 20)+'px',
            	});
            	$('#vindex_dialog .thumbnail_wrapper', selectors.vindex).css({
            		'top': '30px',
            		'left': '10px',
            		'width': ($('#vindex_dialog').width() - 30)+'px',
            		'height': ($('#vindex_dialog').height() - 50)+'px'
            	});
            	
            	$('#memotool_dialog', selectors.memotool).css({
            		'top': (params.paper_y + (params.paper_h - params.memo.tool.dialog_h) / 2)+'px',
            		'left': (params.paper_x + (params.paper_w - params.memo.tool.dialog_w) / 2)+'px',
            		'width': params.memo.tool.dialog_w+'px',
            		'height': params.memo.tool.dialog_h+'px'
            	});
            	$('#memotool_dialog .tool_wrapper', selectors.memotool).css({
            		'top': '30px',
            		'left': '10px',
            		'width': (params.memo.tool.content_w)+'px',
            		'height': (params.memo.tool.dialog_h - 50)+'px'
            	});
            	$('#memolist_dialog', selectors.memolist).css({
            		'top': (params.paper_y + 10)+'px',
            		'left': (params.paper_x + 10)+'px',
            		'width': (params.paper_w - 20)+'px',
            		'height': (params.paper_h - 20)+'px',
            	});
            	$('#memolist_dialog .list_wrapper', selectors.memolist).css({
            		'top': '30px',
            		'left': '10px',
            		'width': ($('#memolist_dialog').width() - 30)+'px',
            		'height': ($('#memolist_dialog').height() - 50)+'px'
            	});
            	
            	$('#print_dialog', selectors.print).css({
            		'top': (params.paper_y + (params.paper_h - params.print.dialog_h) / 2)+'px',
            		'left': (params.paper_x + (params.paper_w - params.print.dialog_w) / 2)+'px',
            		'width': params.print.dialog_w+'px',
            		'height': params.print.dialog_h+'px'
            	});
            	$('#print_dialog .print_wrapper', selectors.print).css({
            		'top': '30px',
            		'left': '10px',
            		'width': (params.print.dialog_w - 30)+'px',
            		'height': (params.print.dialog_h - 50)+'px'
            	});
            	
            	$('#searchall_dialog', selectors.searchall).css({
            		'top': (params.paper_y + 10)+'px',
            		'left': (params.paper_x + 10)+'px',
            		'width': (params.paper_w - 20)+'px',
            		'height': (params.paper_h - 20)+'px',
            	});
            	$('#searchall_dialog .searchlist_wrapper', selectors.searchall).css({
            		'top': '100px',
            		'left': '10px',
            		'width': ($('#searchall_dialog').width() - 30)+'px',
            		'height': ($('#searchall_dialog').height() - 120)+'px'
            	});
            }
            
            // ヘルプ
        	var rw = params.win_w / params.win_h; // ウィンドウの縦横比
        	
        	if (params.help.ratio < rw) {
        		// ヘルプ画像の縦方向が内接する -> 縦方向を基準にする
            	if (params.win_h < params.help.oh + ph * 2) {
            		hh = params.win_h - ph * 2;
            		ho = 'scroll';
            	} else {
            		hh = params.help.oh;
            	}
            	hw = hh * params.help.ratio;
        	} else {
        		// ヘルプ画像の横方向が内接する -> 横方向を基準にする
            	if (params.win_w  < params.help.ow + pw * 2) {
            		hw = params.win_w - pw * 2;
            		ho = 'scroll';
            	} else {
            		hw = params.help.ow;
            	}
        		hh = hw / params.help.ratio;
        	}
        	
        	hl = (params.win_w - hw) / 2;
        	ht = (params.win_h - hh) / 2;
        	
    		$('#help_screen .help_content').width(hw).height(hh).css({overflow: ho});
    		$('#help_screen .ui-dialog-contain').width(hw).height(hh).css({top: ht+'px', left: hl+'px'});
    		$('#help_screen #help_bg').width(params.win_w).height(params.win_h);
            
    		
            //新しいスケールを変数へ格納
            params.wscale = ws;
            params.pscale = ps;
            
            methods.adjustbookmarktag();
            methods.adjustmemo();
        }
    };
    
    $.fn.dmxLivebook = function(method) {
        
        //各メソッドへの振り分け（未指定時はinit実行）
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('メソッド ' + method + ' が見つかりません。');
        }

    };
    
    //エラーの定義
    //Chrome ver.33 では $.error = console.error で Uncaught Type Error になったため無名関数経由で渡す
    $.error = function () {
        console.error(arguments[0]);
    };
    
    //タップ操作の制御
    $.event.special.dmxtaps = {
        setup: function(){
            var thisObject = this,
                $this = $(thisObject),
                first = true, //初回のタップ発生を示すフラグ
                timer;

            if (!$.mobile) {
                alert('jQuery.mobile が読み込まれていません。');
            } else {
                function singleTap(event) { //シングルタップ
                    event.type = "dmxtap";
                    $.event.trigger(event, undefined, thisObject);
                    clearTapTimer();
                }
                function clearTapTimer() {
                    first = true;
                    clearTimeout(timer);
                }

                $this.bind("tap", function(event) {
                    if (event.which && event.which !== 1) {
                        return false;
                    }
                    
                    var origTarget = event.target;

                    if (first) {
                        first = false; //初回のタップ発生
                        timer = setTimeout(singleTap, $.event.special.dmxtaps.dblTapInterval, event); //待機時間経過後はシングルタップイベントを発生させる
                    } else {
                        clearTapTimer(); //シングルタップイベントの発生を防ぐためタイマーをクリア
                        event.type = "dmxdbltap";
                        $.event.trigger(event, undefined, thisObject);
                    }

                });
            }
        }, 
        dblTapInterval: 400
    };
    
})(jQuery);
$(document).on('pagecreate','#dmxlivebook',function(){
	$('body').prepend($('#controllbar'));
	flip=0;_flip=0;
	$('#dmxlivebook').bind('mousewheel', function(e){
		var scrollHeight = $('#paper_frame').height();
		var scrollWidth = $('#paper_frame').width();
		var _scrolltop = $('#dmxlivebook').scrollTop();
		var _pageheight = parseInt($('#dmxlivebook').css('height'));
		_flip++;
		if(e.originalEvent.wheelDelta < 0 && _scrolltop+_pageheight>scrollHeight){
			flip=1;
		}else	if(e.originalEvent.wheelDelta > 0 && _scrolltop==0){
			flip=2;
		}else{
			flip=0;
		}
		if(_flip==1){
			_flip=0;
			setTimeout(function(){
				if(e.originalEvent.wheelDelta < 0 && flip==1) {
					//scroll 
					flip=0;
					$('#controllbar #r_page').trigger('vclick');
					$('#dmxlivebook').scrollTop(0);
				}else if(e.originalEvent.wheelDelta > 0 && flip==2){
					//scroll up
					flip=0;
					$('#controllbar #l_page').trigger('vclick');
					$('#dmxlivebook').scrollTop(0);
				}
			},500);
		}
	});

	$('body').keyup(function(e){ 
		switch(e.which){
			case 1:
				$('#controllbar #l_page').trigger('vclick');
				//$('#dmxlivebook').scrollTop(3);
				break;
			case 3:
				$('#controllbar #r_page').trigger('vclick');
				//$('#dmxlivebook').scrollTop(3);
				break;
			case 96:
			case 97:
			case 98:
			case 99:
			case 100:
			case 101:
			case 102:
			case 103:
			case 104:
			case 105:
				timer = setTimeout(function(){
					clearTimeout(timer);
					$('#goto_submit').trigger('vclick');
				},500);
				if(timer){
					var goto_text = $('#goto_text').val();
					var num = parseInt(e.which-96);
					var goto_num='';
					if(goto_text!=''){
						goto_num = parseInt(goto_text);
					}
					$('#goto_text').val(goto_num.toString() + num.toString());
				}else{
					$('#goto_text').val('');
				}
				break;
		}
		return false;
	});
});
