/**
 * 
 * jQuery Digitalmax LiveBook plugin
 * Version 0.1.0
 * @requires jQuery v1.11.0
 * @requires jQuery.mobile v1.4.2
 * 
 * Copyright (c) 2014 Digitalmax Co.,Ltd.
 * 
 */

(function($) {
    
    //バージョン
    var version = '0.1.0';
    
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
        'SPREADMODE': { //開き方
            'SINGLE': 0, //単ページ
            'DOUBLE': 1 //見開き
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
        'ERROR': {
            'FATAL': 1,
            'WARNING': 2,
            'NOTICE': 4
        }
    };
    
    //XMLなどから作成するパラメータ
    var params = {
        'pageimgprefix': '', //ページ画像の格納ディレクトリおよびページ画像ファイル名のプレフィックス
        'pageimgdir': '', //ページ画像格納ディレクトリ
        'media_ow': 0, //100%画像の幅（px）スケール1.0
        'media_oh': 0, //100%画像の高さ（px）スケール1.0
        'media_w': 0, //100%画像の幅（px）
        'media_h': 0, //100%画像の高さ（px）
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
        'zoom_x': 0, //拡大枠のx座標（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_y': 0, //拡大枠のy座標（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_w': 0, //拡大枠の幅（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_h': 0, //拡大枠の高さ（px）※拡大枠はウィンドウリサイズに応じて拡大/縮小しない
        'zoom_dox': 0, //拡大移動開始時の拡大枠原点座標（px）zoom drag origin x
        'zoom_doy': 0, //拡大移動開始時の拡大枠原点座標（px）zoom drag origin y
        'zoom_dsx': 0, //拡大移動開始時のタップ座標（px）zoom drag start x ※ウィンドウの原点基準
        'zoom_dsy': 0, //拡大移動開始時のタップ座標（px）zoom drag start y ※ウィンドウの原点基準
        'zoom_dcx': 0, //拡大移動中のタップ座標（px）zoom drag current x ※ウィンドウの原点基準
        'zoom_dcy': 0, //拡大移動中のタップ座標（px）zoom drag current y ※ウィンドウの原点基準
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
        'slideduration': 500, //スライドのアニメーションスピード
        'slidedelay': 300, //スライドのアニメーション実行の遅延秒数
        'win_w': 0, // window width (current)
        'win_h': 0, // window height (current)
        'win_ow': 0, // window width (original)
        'win_oh': 0, // window height (original)
        'wscale': 1, //window scale
        'pscale': 1, //page scale
        'zscale': 1, //zoom scale
        'changespreadduration': 500,
        'usewebcrm': false, //Web-CRMログ送信をするかどうか
        'webcrm_medianame': '', //webcrm media name
        'webcrm_sessionid': '', //webcrm session id
        'webcrm_prepagenum': -1 //webcrm previous page num（直前に送ったページ数）
    };
    
    //状態のパラメータ
    var status = {
        'initready': true,
        'curbasepage': 0, //現在のページ数（見開き表示中は少ないページ数のページ）
        'fliptype': DEF_PARAMS.FLIPTYPE.FLIP, //めくり方
        'flipping': false, //めくり中かどうか
        'sliding': false, //スライド中かどうか
        'changespread': false, //開き方の変更アニメーション中かどうか
        'spreadmode': DEF_PARAMS.SPREADMODE.DOUBLE, //ページの開き方
        'zoommode': false, //拡大中かどうか
        'zoomdrag': false, //拡大移動中かどうか
        'taphold': false //taphold中かどうか
    };
    
    //設定のデフォルト値
    var settings = {
        'startpage': 0, //開始ページ
        'pageaction': DEF_PARAMS.PAGEACTION.LEFT //ページの綴じ方
    };

    //セレクタまとめ用配列
    var selectors = {
        'livebook': '#dmxlivebook',
        'paper_frame': '#paper_frame', //紙面枠
        'pages': '#paper_frame .page', //ページ
        'leftpages': '#paper_frame .page.left', //左ページ
        'rightpages': '#paper_frame .page.right', //右ページ
        'pageimgs': '#paper_frame .page img', //ページ画像
        'linkrect': '.link_rect',
        'zoom_frame': '#zoom_frame',
        'zoompages': '#zoom_frame .page',
        'zoomimgs': '#zoom_frame .page img'
    };
    
    //リンク用配列
    var links = new Array();
    
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
        var p = $(this).data('pos').split(','),
            x1 = parseInt(p[0]),
            y1 = parseInt(p[1]),
            x2 = parseInt(p[2]),
            y2 = parseInt(p[3]);
    
        var x = parseInt(x1) * ps * params.basescale;
        var y = parseInt(y1) * ps * params.basescale;

        var w = parseInt(x2) * ps * params.basescale;
        var h = parseInt(y2) * ps * params.basescale;
        
        $(this).css('left', x+'px').css('top', y+'px')
               .width(w).height(h);
    }
    
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
        
        return spreadmode;
    }
    
    //端末の向きの状態を返す
    var get_orientation = function () {
        return $.event.special.orientationchange.orientation();
    }
    
    //
    var get_resize_param = function (win_w, win_h, cols, rows) {
        var wsw, wsh, psw, psh, ps, mdw, mdh, tmdw, tmdh, ppw, pph, ppx, ppy;
        
        //起動時ウィンドウと現ウィンドウサイズのスケール計算
        wsw = win_w / params.win_ow;
        wsh = win_h / params.win_oh;
        
        //紙面横幅のサイズ調整（ウィンドウより大きいければウィンドウにフィットさせる）
        if (win_w < params.media_ow * cols) {
            tmdw = parseInt(win_w / cols);
        } else {
            tmdw = params.media_ow;
        }
        //ページ横幅のスケールの計算
        psw = tmdw / params.media_ow;
        
        //紙面高さのサイズ調整（ウィンドウより大きいければウィンドウにフィットさせる）
        if (win_h < params.media_oh) {
            tmdh = parseInt(win_h / rows);
        } else {
            tmdh = params.media_oh;
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
        ppx = parseInt((params.paper_ox * wsw + (params.win_ow * wsw - ppw) / 2));
        ppy = parseInt((params.paper_oy * wsh + (params.win_oh * wsh - pph) / 2))
        
        return {'wsw': wsw, 'wsh': wsh,
                'psw': psw, 'psh': psh, 'ps': ps,
                'ppx': ppx, 'ppy': ppy,
                'ppw': ppw, 'pph': pph,
                'mdw': mdw, 'mdh': mdh};
    }
    
    //渡された座標がページの左半分か右半分どちらをクリックしたかを返す
    var get_lr_of_page = function (x, obj_x) {
        var h = Math.floor(params.media_w / 2),
            p = x - obj_x,
            s = '';
        
        if (p <= h) s = 'left';
        else s = 'right';
        
        return s;
    }
    
    //渡されたウィンドウ座標系値をページ上の座標系に変換して返す
    var get_coord_of_page = function (x, y, obj_x, obj_y) {
        var tx = x - obj_x,
            ty = y - obj_y;
        
        return {'x': tx, 'y': ty};
    }
    
    //Web-CRM：セッションIDの作成
    var webcrm_make_sessionid = function () {
        var date = new Date();
        
        return date.getTime();
    }
    
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
    }
    
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
    }
    
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
    }
    
    //メソッド
    var methods = {
        version: function () {
            return version;
        },
        init: function(options) {
            var $this = $(this);
            
            if (!$.mobile) {
                methods._initError('[Err:00001-003] jQuery.mobile が読み込まれていません。', DEF_PARAMS.ERROR.FATAL);
            } else {
                if ($.mobile.version !== '1.4.2') {
                    methods._initError('[Err:00001-004] jQuery.mobile の対応バージョンは 1.4.2 です。', DEF_PARAMS.ERROR.FATAL);
                }
                if (!$.mobile.support.touch) {
                    methods._initError('[Err:00001-005] タッチイベントがサポートされていないデバイスです。', DEF_PARAMS.ERROR.FATAL);
                }
            }
            
            //各設定値のセット
            settings = $.extend({}, settings, options);
            
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
                    
                    //startpage が lastpage より大きい場合
                    if (params.lastpage < settings.startpage)
                        settings.startpage = params.lastpage;
                    
                    if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
                    {
                        params.paper_ow = params.media_ow * 2;
                        params.paper_oh = params.media_oh;
                        params.paper_w = params.paper_ow * params.scale;
                        params.paper_h = params.media_oh * params.scale;
                        status.curbasepage = settings.startpage - settings.startpage % 2;
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
                    status.spreadmode = get_spreadmode();
                    
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
            var t = arguments[0]; //target
            
            $.ajax({ 
                type: "GET", 
                url: '../xml/Build.xml',
                dataType: "xml",
                async: false,
                success: function(xml, status){
                    if(status === 'success') {
                        params.pageimgprefix = $(xml).find('ProjectName').text();
                        params.pageimgdir = '../'+params.pageimgprefix+'__dmx/';
                        params.media_ow = parseInt(parseInt($(xml).find('MediaWidth').text()) * params.basescale);
                        params.media_oh = parseInt(parseInt($(xml).find('MediaHeight').text()) * params.basescale);
                        params.media_w = params.media_ow * params.scale; 
                        params.media_h = params.media_oh * params.scale; 
                        params.lastpage = parseInt($(xml).find('LastPageNumber').text());
                        params.webcrm_medianame = $(xml).find('mediaID').text();
                        
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

            var slide_swipeleft_fnc, slide_swiperight_fnc,
                flip_dmxtap_fnc, zoomin_dmxdbltap_fnc;
            
            //紙面を格納するタグ（紙面枠）の追加
            data.target.html('<div id="paper_outer"><div id="paper_frame"></div></div><div id="zoom_outer"><div id="zoom_frame"></div></div>');

            //初期ページの追加
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
            {
                if (0 < settings.startpage) //開始ページが0のときは前のページは不要
                    methods.addpage.apply($this, [$(selectors.paper_frame), p - 1, 400]); //前のページ

                methods.addpage.apply($this, [$(selectors.paper_frame), p, 400]); //開始ページ
                methods.addpage.apply($this, [$(selectors.paper_frame), p + 1, 400]); //開始ページ
                methods.addpage.apply($this, [$(selectors.paper_frame), p + 2, 400]); //後のページ
            
                methods.loadlinkdata.apply($(this), [data.target, p]); //リンクの読み込み
                methods.loadlinkdata.apply($(this), [data.target, p + 1]); //リンクの読み込み
            }
            else //単ページの場合
            {
                if (0 < settings.startpage) //開始ページが0のときは前のページは不要
                    methods.addpage.apply($this, [$(selectors.paper_frame), p - 1, 400]); //前のページ

                methods.addpage.apply($this, [$(selectors.paper_frame), p, 400]); //開始ページ
                methods.addpage.apply($this, [$(selectors.paper_frame), p + 1, 400]); //開始ページ
                
                methods.loadlinkdata.apply($(this), [data.target, p]); //リンクの読み込み
            }

            $(selectors.paper_frame, data.target).css({'width': params.paper_w+'px', 'height': params.media_h+'px'}); //紙面枠のサイズ設定
            $(selectors.pageimgs, data.target).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
            $(selectors.rightpages, data.target).css({'left': params.media_w+'px'}); //右ページの移動
            
            //タップ制御のための独自イベント
            $(data.target).on('dmxtaps', selectors.pageimgs, function(e){
            });
            
            flip_dmxtap_fnc = function(e){
                console.log('event:dmxtap');
                if (status.flipping === false && status.sliding === false && status.changespread === false) {
                    var p = parseInt($(e.target).parent('div').attr('rel'));
                    
                    if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                        status.flipping = true;
                        methods.flipstart.apply($this, [data.target, p]);
                    } else {
                        status.sliding = true;
                        if (get_lr_of_page(e.pageX, $(e.target).offset().left) === 'left') {
                            methods.slidestart.apply($this, [data.target, 'l2r']);
                        } else {
                            methods.slidestart.apply($this, [data.target, 'r2l']);
                        }
                    }
                }
            };
            //シングルタップ
            $(data.target).on('dmxtap', selectors.pageimgs, flip_dmxtap_fnc);
            
            zoomin_dmxdbltap_fnc = function(e){
                if (status.flipping === false && status.sliding === false && status.changespread === false && status.zoommode === false) {
                    var o = $(e.target).parent('div');
                    var p = parseInt(o.attr('rel'));
                    var c = get_coord_of_page(e.pageX, e.pageY, o.offset().left, o.offset().top);
                    methods.zoominstart.apply($this, [data.target, p, c.x, c.y]); //現在の表示比率を100%とした座標を渡す（実際の紙面上での座標は変換が必要）
                }
            };
            //ダブルタップ
            $(data.target).on('dmxdbltap', selectors.pageimgs, zoomin_dmxdbltap_fnc);
            $(data.target).on('dmxdbltap', selectors.zoomimgs, function(e){
                if (status.flipping === false && status.sliding === false && status.changespread === false && status.zoommode === true) {
                    methods.zoomoutstart.apply($this, [data.target]);
                }
            });
            
            //Android用 touchmove イベントがキャンセルさせる現象の対策
            $(data.target).on('touchstart', selectors.pageimgs, function(e){
                e.preventDefault();
            });
            //Swipeイベントのハンドラー
            $.event.special.swipe.handleSwipe = function(start, stop, thisObject, origTarget) {
                var esw, elr, o, d;
                
                //start.timeとstop.time、start.coords[0]とstop.coords[0]からスワイプの距離と速度を計算
                //結果から処理を分岐させる
                //console.log('swipe:'+start.coords[0]+' -> '+stop.coords[0]);
                
                //デフォルトのSwipe処理
                if (stop.time - start.time < $.event.special.swipe.durationThreshold &&
                    Math.abs(start.coords[0] - stop.coords[0]) > $.event.special.swipe.horizontalDistanceThreshold &&
                    Math.abs(start.coords[1] - stop.coords[1]) < $.event.special.swipe.verticalDistanceThreshold) {
                    
                    d = start.coords[0] > stop.coords[0] ? "swipeleft" : "swiperight";
                    o = { target: origTarget, swipestart: start, swipestop: stop };
                    esw = $.Event("swipe", o);
                    elr = $.Event(d, o);
                    start.origin.trigger(esw, undefined, thisObject).trigger(elr, undefined, thisObject);
                }
            }
            
            slide_swipeleft_fnc = function(e){
                console.log('event:swipeleft');
                if (status.flipping === false && status.sliding === false && status.changespread === false) {
                    status.sliding = true;
                    methods.slidestart.apply($this, [data.target, 'r2l']);
                }
            };
            
            $(data.target).on('swipeleft', selectors.pageimgs, slide_swipeleft_fnc);
            
            slide_swiperight_fnc = function(e){
                console.log('event:swiperight');
                if (status.flipping === false && status.sliding === false && status.changespread === false) {
                    status.sliding = true;
                    methods.slidestart.apply($this, [data.target, 'l2r']);
                }
            };
            $(data.target).on('swiperight', selectors.pageimgs, slide_swiperight_fnc);
            
            $(data.target).on('touchstart', selectors.zoomimgs, function(e){
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === true && status.zoomdrag == false) {
                    var x = e.originalEvent.touches[0].pageX,
                        y = e.originalEvent.touches[0].pageY;
                    methods.zoomdragstart.apply($this, [data.target, x, y]);
                }
            });
            $(data.target).on('touchmove', selectors.zoomimgs, function(e){
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === true && status.zoomdrag == true) {
                    var x = e.originalEvent.touches[0].pageX,
                        y = e.originalEvent.touches[0].pageY;
                    methods.zoomdragmove.apply($this, [data.target, x, y]);
                }
            });
            $(data.target).on('touchend', selectors.zoomimgs, function(e){
                if (status.flipping === false && status.sliding === false && status.changespread === false &&
                        status.zoommode === true && status.zoomdrag == true) {
                    methods.zoomdragterminate.apply($this, [data.target]);
                }
            });
            
            $(data.target).on('dmxtaps', selectors.linkrect, function(e){
            });
            $(data.target).on('dmxtap', selectors.linkrect, flip_dmxtap_fnc);
            $(data.target).on('dmxdbltap', selectors.linkrect, zoomin_dmxdbltap_fnc);
            
            $(data.target).on('touchstart', selectors.linkrect, function(e){
                $(e.target).css('opacity', 0.5); //リンクの透過率を変更（アクティブ表示）
                
                //Android用 touchmove イベントがキャンセルされる現象の対策
                e.preventDefault();
            });
            $(data.target).on('touchend', selectors.linkrect, function(e){
                $(e.target).css('opacity', 0.25); //リンクの透過率を通常状態に戻す
            });
            $(data.target).on('touchcancel', selectors.linkrect, function(e){
                $(e.target).css('opacity', 0.25); //リンクの透過率を通常状態に戻す
            });
            $(data.target).on('swipeleft', selectors.linkrect, slide_swipeleft_fnc);
            $(data.target).on('swiperight', selectors.linkrect, slide_swiperight_fnc);
            
            $(data.target).on('vclick', selectors.linkrect, function(e){
                //tapholdのイベント処理直後に指を離すとtapがtriggerされるのを防止
                if (status.taphold) {
                    status.taphold = false;
                    e.stopPropagation();
                }
            });
            $(data.target).on('taphold', selectors.linkrect, function(e){
                console.log('linkrect:taphold');
                $(e.target).css('opacity', 0.25); //リンクの透過率を通常状態に戻す
                status.taphold = true;
                if (status.flipping === false && status.sliding === false && status.changespread === false) {
                    var t = $(e.target).data('type');
                    
                    if (t == DEF_PARAMS.LINKTYPE.PAGE) {
                        if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                            status.flipping = true;
                        } else {
                            status.sliding = true;
                        }
                        methods.gotostart.apply($this, [data.target, $(e.target).data('link')]);
                    } else if (t == DEF_PARAMS.LINKTYPE.URL) {
                        window.open('../'+$(e.target).data('link'), $(e.target).data('target'));
                    }
                }
            });
            
            //Web-CRM：ページビューログの送信（少ない側のページ数に統一する）
            webcrm_send_pageview(p - p % 2);
        },
        zoominstart: function (){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var rx = arguments[2]; //pos x (real x) ※クリックした実際の座標（ウィンドウのリサイズを反映した座標）
            var ry = arguments[3]; //pos y (real y) ※クリックした実際の座標（ウィンドウのリサイズを反映した座標）
            var tx, ty; //ウィンドウのリサイズを無視した実際の紙面上での座標
            var zx, zy; //拡大枠の原点
            var zw, zh; //拡大画像のサイズ
            var lp, rp; //拡大対象のページ数
            var cp, cx, cy; //拡大ログ送信用のページ数、座標
            
            status.zoommode = true;
            $(selectors.zoom_frame).css('display', 'none');
            
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
                
                if ($(selectors.zoompages+'[rel='+lp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.zoom_frame), lp, 1000]);
                if ($(selectors.zoompages+'[rel='+rp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.zoom_frame), rp, 1000]);
                
                $(selectors.zoompages+'[rel='+lp+']', t).css({'left': '0px', 'top': '0px'});
                $(selectors.zoompages+'[rel='+rp+']', t).css({'left': Math.floor(params.maxscale / params.basescale * params.media_ow) + 'px', 'top': '0px'});
                
                //拡大ログの送信用パラメータのセット
                cp = Math.min(lp, rp); cx = tx; cy = ty;
            } else {
                
                tx = Math.floor(rx / params.pscale);
                ty = Math.floor(ry / params.pscale);
                
                if ($(selectors.zoompages+'[rel='+p+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.zoom_frame), p, 1000]);
                
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
                
                //拡大ログの送信用パラメータのセット
                cp = p - p % 2;
            }
            
            zw = Math.floor(params.maxscale / params.basescale * params.media_ow) * params.paper_cols;
            zh = Math.floor(params.maxscale / params.basescale * params.media_oh) * params.paper_rows;
            
            //ウィンドウ原点から中心点までの距離 = 拡大の中心点 = クリックの座標 = 拡大枠の原点からの距離
            // -> ウィンドウ原点から中心点までの距離 - クリックの座標 = 拡大枠の原点座標
            zx = Math.floor(params.win_w / 2 - params.maxscale / params.basescale * tx);
            if (0 < zx) zx = 0;
            else if (zx < params.win_w - zw) zx = params.win_w - zw;
            
            zy = Math.floor(params.win_h / 2 - params.maxscale / params.basescale * ty);
            if (0 < zy) zy = 0;
            else if (zy < params.win_h - zh) zy = params.win_h - zh;
            
            params.zoom_w = zw; params.zoom_h = zh;
            params.zoom_x = zx; params.zoom_y = zy;
            
            //Web-CRM：拡大ログの送信（100%紙面上の座標として送信）
            webcrm_send_zoomview(cp, Math.floor(cx / params.basescale), Math.floor(cy / params.basescale), 0);
            
            methods.zoominaction.apply($(this), [t, p, zx, zy]);
        },
        zoominaction: function () {
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var x = arguments[2]; //pos x
            var y = arguments[3]; //pos y
            
            params.win_w / 2 - x; 
            params.win_h / 2 - y; //ウィンドウ原点から中心点までの距離 = 拡大の中心点
            
            $(selectors.zoom_frame).css({'display': 'block', 'left': x+'px', 'top': y+'px'});
            
            methods.zoominterminate.apply($(this), [t]);
        },
        zoominterminate: function () {
            var t = arguments[0]; //target
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
            
            status.zoommode = false;
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
            if (0 < zx) zx = 0;
            else if (zx < params.win_w - params.zoom_w) zx = params.win_w - params.zoom_w;

            zy = params.zoom_doy - dy;
            if (0 < zy) zy = 0;
            else if (zy < params.win_h - params.zoom_h) zy = params.win_h - params.zoom_h;
            
            $(selectors.zoom_frame).css({'left': zx+'px', 'top': zy+'px'});
        },
        zoomdragterminate: function () {
            var t = arguments[0]; //target
            var dx = params.zoom_dsx - params.zoom_dcx, //diff x
                dy = params.zoom_dsy - params.zoom_dcy; //diff y
            var zx, zy;
            
            zx = params.zoom_dox - dx;
            if (0 < zx) zx = 0;
            else if (zx < params.win_w - params.zoom_w) zx = params.win_w - params.zoom_w;

            zy = params.zoom_doy - dy;
            if (0 < zy) zy = 0;
            else if (zy < params.win_h - params.zoom_h) zy = params.win_h - params.zoom_h;
            
            params.zoom_x = zx;
            params.zoom_y = zy;
            params.zoom_dox = params.zoom_doy = 0;
            params.zoom_dsx = params.zoom_dsy = 0;
            params.zoom_dcx = params.zoom_dcy = 0;
            status.zoomdrag = false;
        },
        addpage: function() {
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var s = arguments[2]; //size
            var d, z, v; //direction left or right, z-index, visible
            
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
            
            t.append('<div class="page ' + d + '" rel="' + p + '" style="z-index: ' + z + '; display: ' + v + '"><img src="'+params.pageimgdir+params.pageimgprefix+'__dmx__' + p + '__' + s + '.jpg"></div>');
        },
        gotostart: function() {
            var t = arguments[0]; //target
            var p = arguments[1]; //jump to page num
            var d; //direction
            var np, nlp, nrp; //next left page, next right page
            var cp, clp, crp; //current left page, current right page
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
            {
                //ページ数を見開きの小さい側に合わせる(status.curbasepageとの比較のため)
                p -= p % 2;
                
                //めくり後のページが0～最大ページ数範囲内、または現在のページと同じ場合は処理を中断
                if (p < 0 || params.lastpage < p || p === status.curbasepage) {
                    status.flipping = false;
                    return;
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
                        
                        //めくり後に現れるページ
                        nlp = p + 1;
                        nrp = p;
                        //現在のページ
                        clp = status.curbasepage + 1;
                        crp = status.curbasepage;

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (p < status.curbasepage){ // めくり方向：left -> right
                            d = 'l2r';
                        } else { // めくり方向：left <- right
                            d = 'r2l';
                        }
                        
                        //めくり後に現れるページ
                        nlp = p;
                        nrp = p + 1;
                        //現在のページ
                        clp = status.curbasepage;
                        crp = status.curbasepage + 1;

                        break;
                }
                
                //ページの有無確認と、なければ追加
                if ($(selectors.pages+'[rel='+nlp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), nlp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+nrp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), nrp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+clp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), clp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+crp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), crp, params.zoomlvs[params.basezlv]]);
                
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
            } else {
                //めくり後のページが0～最大ページ数範囲内、または現在のページと同じ場合は処理を中断
                if (p < 0 || params.lastpage < p || p === status.curbasepage) {
                    status.flipping = false;
                    return;
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

                //ページの有無確認と、なければ追加
                if ($(selectors.pages+'[rel='+np+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), np, 400]);
                if ($(selectors.pages+'[rel='+cp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), cp, 400]);
                
                $(selectors.paper_frame, t).css({'width': params.paper_w+'px', 'height': params.media_h+'px', 'overflow': 'hidden'}); //紙面枠のサイズ設定
                $(selectors.pageimgs, t).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
                
                if (d === 'l2r') { // めくり方向：left -> right
                    //アニメーションするページ
                    $(selectors.pages+'[rel='+cp+']', t).css({'display': 'block', 'left': '0px'});
                    $(selectors.pages+'[rel='+np+']', t).css({'display': 'block', 'left': -1 * (params.media_w)+'px'});
                } else { // めくり方向：left <- right
                    //アニメーションするページ
                    $(selectors.pages+'[rel='+cp+']', t).css({'display': 'block', 'left': '0px'});
                    $(selectors.pages+'[rel='+np+']', t).css({'display': 'block', 'left': 1 * (params.media_w)+'px'});
                }
                
                params.slidedirection = d;
                params.timerid = setTimeout(methods.slideaction, params.slidedelay, $(this), [t, cp, np]);
            }
        },
        flipstart: function() {
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            var d; //direction
            var nlp, nrp; //next left page, next right page
            var clp, crp; //current left page, current right page
            
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
                        } else { // めくり方向：left -> right
                            d = 'l2r';
                            
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

                            //めくり後に現れるページ
                            nlp = status.curbasepage - 2;
                            nrp = status.curbasepage - 1;
                        } else { // めくり方向：left <- right
                            d = 'r2l';
                            
                            //めくり後に現れるページ
                            nlp = status.curbasepage + 2;
                            nrp = status.curbasepage + 3;
                        }
                        
                        //現在のページ
                        clp = status.curbasepage;
                        crp = status.curbasepage + 1;

                        break;
                }
                
                //めくり後のページが0～最大ページ数範囲内にない場合は処理を中断
                if (Math.min(nlp, nrp) < 0 || params.lastpage < Math.max(nlp, nrp)) {
                    status.flipping = false;
                    return;
                }
                
                //ページの有無確認と、なければ追加
                if ($(selectors.pages+'[rel='+nlp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), nlp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+nrp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), nrp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+clp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), clp, params.zoomlvs[params.basezlv]]);
                if ($(selectors.pages+'[rel='+crp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), crp, params.zoomlvs[params.basezlv]]);
                
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
            var d = params.flipdirection;
            
            params.flipangle += params.anglestep;
            
            var rotateDeg = params.flipangle,
                sratio = Math.abs(Math.cos(rotateDeg * Math.PI / 180)),
                skewDeg = rotateDeg2skewDeg(params.media_w, rotateDeg, sratio);
            
            if (180 <= rotateDeg)
            {
                rotateDeg = 180;
                sratio = 1;
            }
            
            //skewDeg -= 90;
            
            //console.log('skewDeg:'+skewDeg+' sratio:'+sratio);
            
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
                    methods.flipterminate.apply($(this), [t, Math.min(nlp, nrp)]);
                }
            }
        },
        flipterminate: function(){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                methods.loadlinkdata.apply($(this), [t, p]);
                methods.loadlinkdata.apply($(this), [t, p + 1]);
            } else {
                methods.loadlinkdata.apply($(this), [t, p]);
            }
            
            //Web-CRM：ページビューログの送信（少ない側のページ数に統一する）
            webcrm_send_pageview(p - p % 2);
            
            clearInterval(params.timerid);
            params.timerid = -1;
            params.flipangle = 0;
            status.curbasepage = p;
            status.flipping = false;
            
            methods.resize.apply($(this), [t]);
        },
        slidestart: function() {
            var t = arguments[0]; //target
            var d = arguments[1]; //direction
            var nlp, nrp; //next left page, next right page (for flip)
            var clp, crp; //current left page, current right page (for flip)
            var np, cp; //next page, current page (for slide)
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
            {
                // めくり方向の判定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (d === 'r2l'){ // めくり方向：left <- right
                            //めくり後に現れるページ
                            nlp = status.curbasepage - 1;
                            nrp = status.curbasepage - 2;
                        } else { // めくり方向：left -> right
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
                        if (d === 'l2r') { // めくり方向：left -> right
                            //めくり後に現れるページ
                            nlp = status.curbasepage - 2;
                            nrp = status.curbasepage - 1;
                        } else { // めくり方向：left <- right
                            //めくり後に現れるページ
                            nlp = status.curbasepage + 2;
                            nrp = status.curbasepage + 3;
                        }
                        
                        //現在のページ
                        clp = status.curbasepage;
                        crp = status.curbasepage + 1;

                        break;
                }
                
                //めくり後のページが0～最大ページ数範囲内にない場合は処理を中断
                if (Math.min(nlp, nrp) < 0 || params.lastpage < Math.max(nlp, nrp)) {
                    status.sliding = false;
                    return;
                }
                
                //ページの有無確認と、なければ追加
                if ($(selectors.pages+'[rel='+nlp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), nlp, 400]);
                if ($(selectors.pages+'[rel='+nrp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), nrp, 400]);
                if ($(selectors.pages+'[rel='+clp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), clp, 400]);
                if ($(selectors.pages+'[rel='+crp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), crp, 400]);
                
                $(selectors.paper_frame, t).css({'width': params.paper_w+'px', 'height': params.media_h+'px', 'overflow': 'hidden'}); //紙面枠のサイズ設定
                $(selectors.pageimgs, t).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
                
                if (d === 'l2r') { // めくり方向：left -> right
                    //アニメーションするページ
                    $(selectors.pages+'[rel='+clp+']', t).css({'display': 'block', 'left': '0px'});
                    $(selectors.pages+'[rel='+crp+']', t).css({'display': 'block', 'left': 1 * (params.media_w)+'px'});
                    $(selectors.pages+'[rel='+nlp+']', t).css({'display': 'block', 'left': -2 * (params.media_w)+'px'});
                    $(selectors.pages+'[rel='+nrp+']', t).css({'display': 'block', 'left': -1 * (params.media_w)+'px'});
                } else { // めくり方向：left <- right
                    //アニメーションするページ
                    $(selectors.pages+'[rel='+clp+']', t).css({'display': 'block', 'left': '0px'});
                    $(selectors.pages+'[rel='+crp+']', t).css({'display': 'block', 'left': 1 * (params.media_w)+'px'});
                    $(selectors.pages+'[rel='+nlp+']', t).css({'display': 'block', 'left': 2 * (params.media_w)+'px'});
                    $(selectors.pages+'[rel='+nrp+']', t).css({'display': 'block', 'left': 3 * (params.media_w)+'px'});
                }
                
                params.slidedirection = d;
                params.timerid = setTimeout(methods.slideaction, params.slidedelay, $(this), [t, clp, crp, nlp, nrp]);
            } else {
                // めくり方向の判定
                switch (settings.pageaction)
                {
                    case DEF_PARAMS.PAGEACTION.RIGHT:
                    case DEF_PARAMS.PAGEACTION.UPPER:
                    case DEF_PARAMS.PAGEACTION.RSLIDE:
                        if (d === 'r2l'){ // めくり方向：left <- right
                            //めくり後に現れるページ
                            np = status.curbasepage - 1;
                        } else { // めくり方向：left -> right
                            //めくり後に現れるページ
                            np = status.curbasepage + 1;
                        }
                        
                        //現在のページ
                        clp = status.curbasepage;

                        break;
                    case DEF_PARAMS.PAGEACTION.LEFT:
                    case DEF_PARAMS.PAGEACTION.LOWER:
                    case DEF_PARAMS.PAGEACTION.LSLIDE:
                        if (d === 'l2r') { // めくり方向：left -> right
                            //めくり後に現れるページ
                            np = status.curbasepage - 1;
                        } else { // めくり方向：left <- right
                            //めくり後に現れるページ
                            np = status.curbasepage + 1;
                        }
                        
                        //現在のページ
                        cp = status.curbasepage;
                        
                        break;
                }
                
                //めくり後のページが0～最大ページ数範囲内にない場合は処理を中断
                if (np < 0 || params.lastpage < np) {
                    status.sliding = false;
                    return;
                }
                
                //ページの有無確認と、なければ追加
                if ($(selectors.pages+'[rel='+np+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), np, 400]);
                if ($(selectors.pages+'[rel='+cp+']', t).length === 0) methods.addpage.apply($(this), [$(selectors.paper_frame), cp, 400]);
                
                $(selectors.paper_frame, t).css({'width': params.paper_w+'px', 'height': params.media_h+'px', 'overflow': 'hidden'}); //紙面枠のサイズ設定
                $(selectors.pageimgs, t).css({'width': params.media_w+'px', 'height': params.media_h+'px'}); //紙面画像のサイズ設定
                
                if (d === 'l2r') { // めくり方向：left -> right
                    //アニメーションするページ
                    $(selectors.pages+'[rel='+cp+']', t).css({'display': 'block', 'left': '0px'});
                    $(selectors.pages+'[rel='+np+']', t).css({'display': 'block', 'left': -1 * (params.media_w)+'px'});
                } else { // めくり方向：left <- right
                    //アニメーションするページ
                    $(selectors.pages+'[rel='+cp+']', t).css({'display': 'block', 'left': '0px'});
                    $(selectors.pages+'[rel='+np+']', t).css({'display': 'block', 'left': 1 * (params.media_w)+'px'});
                }
                
                params.slidedirection = d;
                params.timerid = setTimeout(methods.slideaction, params.slidedelay, $(this), [t, cp, np]);
            }
        },
        slideaction: function(){
            var t = arguments[1][0]; //target
            var d = params.slidedirection;
            var aop1 = {queue:false, duration: params.slideduration}; //アニメーションの基本オプション変数
            var aop2 = $.extend(true, {}, aop1); //completeコールバック関数設定追加用変数
            
            var clp, crp, nlp, nrp;
            var cp, np;
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                clp = arguments[1][1]; crp = arguments[1][2];
                nlp = arguments[1][3]; nrp = arguments[1][4];
                
                aop2.complete = function () {
                    $(selectors.pages+'[rel='+clp+']', t).css({'display': 'none'});
                    $(selectors.pages+'[rel='+crp+']', t).css({'display': 'none'});
                    methods.slideterminate.apply($(this), [t, Math.min(nlp, nrp)]);
                };

                if (d === 'l2r') { // めくり方向：left -> right
                    $(selectors.pages+'[rel='+clp+']', t).animate({'left': '+='+(params.media_w * 2)+'px'}, aop1);
                    $(selectors.pages+'[rel='+crp+']', t).animate({'left': '+='+(params.media_w * 2)+'px'}, aop1);
                    $(selectors.pages+'[rel='+nlp+']', t).animate({'left': '+='+(params.media_w * 2)+'px'}, aop1);
                    $(selectors.pages+'[rel='+nrp+']', t).animate({'left': '+='+(params.media_w * 2)+'px'}, aop2);
                } else { // めくり方向：left <- right
                    $(selectors.pages+'[rel='+clp+']', t).animate({'left': '-='+(params.media_w * 2)+'px'}, aop1);
                    $(selectors.pages+'[rel='+crp+']', t).animate({'left': '-='+(params.media_w * 2)+'px'}, aop1);
                    $(selectors.pages+'[rel='+nlp+']', t).animate({'left': '-='+(params.media_w * 2)+'px'}, aop1);
                    $(selectors.pages+'[rel='+nrp+']', t).animate({'left': '-='+(params.media_w * 2)+'px'}, aop2);
                }
            } else {
                cp = arguments[1][1];
                np = arguments[1][2];
                
                aop2.complete = function () {
                    $(selectors.pages+'[rel='+cp+']', t).css({'display': 'none'});
                    methods.slideterminate.apply($(this), [t, np]);
                };

                if (d === 'l2r') { // めくり方向：left -> right
                    $(selectors.pages+'[rel='+cp+']', t).animate({'left': '+='+(params.media_w * 1)+'px'}, aop1);
                    $(selectors.pages+'[rel='+np+']', t).animate({'left': '+='+(params.media_w * 1)+'px'}, aop2);
                } else { // めくり方向：left <- right
                    $(selectors.pages+'[rel='+cp+']', t).animate({'left': '-='+(params.media_w * 1)+'px'}, aop1);
                    $(selectors.pages+'[rel='+np+']', t).animate({'left': '-='+(params.media_w * 1)+'px'}, aop2);
                }
            }
        },
        slideterminate: function(){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) { //見開きの場合
                methods.loadlinkdata.apply($(this), [t, p]);
                methods.loadlinkdata.apply($(this), [t, p + 1]);
                $(selectors.paper_frame, t).css({'overflow': 'visible'});
            } else {
                methods.loadlinkdata.apply($(this), [t, p]);
                $(selectors.paper_frame, t).css({'overflow': 'hidden'});
            }
            
            //Web-CRM：ページビューログの送信（少ない側のページ数に統一する）
            webcrm_send_pageview(p - p % 2);
            
            clearInterval(params.timerid);
            params.timerid = -1;
            status.curbasepage = p;
            status.sliding = false;
            
            methods.resize.apply(t, [t]);
        },
        loadlinkdata: function(){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            
            if (links['p'+p] === undefined)
            {
                $.ajax({ 
                    type: "GET", 
                    url: '../xml/EBookLinkDataParam_'+('0000' + p).substr(-4)+'.xml',
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
            l.each(function(){
                linkurlflg = $(this).find('LinkURLFlag').text().toLowerCase();
                linktarget = $(this).find('LinkTARGET').text();
                linkurl = $(this).find('LinkURL').text();
                linkpage = $(this).find('LinkPage').text();
                x1 = $(this).find('LinkAreaXStr').text();
                y1 = $(this).find('LinkAreaYStr').text();
                x2 = $(this).find('LinkAreaXLen').text();
                y2 = $(this).find('LinkAreaYLen').text();
                
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
                
                var w = parseInt(x2) * params.pscale * params.basescale;
                var h = parseInt(y2) * params.pscale * params.basescale;
                
                html = '<div class="link_rect" style="top:' + y + 'px; left:' + x + 'px; width:' + w + 'px; height:' + h + 'px;" data-type="'+linktype+'" data-link="'+link+'" data-target="'+linktarget+'" data-pos="'+x1+','+y1+','+x2+','+y2+'"></div>';

                if ($(".page[rel=" + p + "]", selectors.paper_frame).length)
                {
                    $(".page[rel=" + p + "]", selectors.paper_frame).append(html);
                }
            });
        },
        linkaction: function(){
            var t = arguments[0]; //target
            var p = arguments[1]; //page num
            
        },
        orientationchangeHandler: function(e){
            var $this = e.data.thisobj,
                data = $this.data('dmxLivebook');
            
            if (status.changespread === false) {
                methods.changespread.apply($this, [data.target]);
            }
        },
        changespread: function(){
            var $this = arguments[0],
                data = $this.data('dmxLivebook'),
                opt_s = get_spreadmode(); //optimum spread mode (by current orientation)
            var lp, rp
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.SINGLE &&
                opt_s === DEF_PARAMS.SPREADMODE.DOUBLE) { //landscape: single -> double
                
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
                
                if ($(selectors.pages+'[rel='+lp+']', $this).length === 0) methods.addpage.apply($this, [$(selectors.paper_frame), lp, 400]);
                if ($(selectors.pages+'[rel='+rp+']', $this).length === 0) methods.addpage.apply($this, [$(selectors.paper_frame), rp, 400]);
                
                methods.changespreadstart.apply($this, [data.target, opt_s]);
                
            } else if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE &&
                opt_s === DEF_PARAMS.SPREADMODE.SINGLE) { //portrait: double -> single
                
                methods.changespreadstart.apply($this, [data.target, opt_s]);
                
            }
        },
        changespreadstart: function(){
            var $this = arguments[0],
                s = arguments[1],
                data = $this.data('dmxLivebook'),
                wiw = $(window).innerWidth(),
                wih = $(window).innerHeight(),
                t = $this,
                tmp, lp, rp, lx, rx;
            
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
            tmp = get_resize_param(wiw, wih, params.paper_cols, params.paper_rows);
            
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
                'left': '0px'
            }, {
                queue: false,
                duration: params.changespreadduration
            });
            
            $(selectors.rightpages).animate({
                'left': tmp.mdw+'px'
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
            } else {
                $(selectors.pages+'[rel='+(status.curbasepage + 1)+']').css({'display': 'none'});
            }
    
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
            
            
            //ウィンドウのサイズを取得
            params.win_w = $(window).innerWidth();
            params.win_h = $(window).innerHeight();
            
            var tmp = get_resize_param(params.win_w, params.win_h, params.paper_cols, params.paper_rows);
            
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
            
            $(selectors.paper_frame).css('left', params.paper_x).css('top', params.paper_y);
            
            //新しい紙面サイズの適用
            $('img', selectors.pages).width(params.media_w).height(params.media_h);
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) {
                $(selectors.rightpages).css('left', params.media_w);
            } else {
                $(selectors.pages).css('left', '0px');
            }
            
            //LiveBookオブジェクト全体の高さ調整
            $(selectors.livebook).height(params.win_h);
            
            $(".page[rel=" + status.curbasepage + "] .link_rect", selectors.paper_frame).each(function(){
                linkadjust.apply(this, [ps]);
            });
            
            if (status.spreadmode === DEF_PARAMS.SPREADMODE.DOUBLE) //見開きの場合
            {
                $(".page[rel=" + (status.curbasepage + 1) + "] .link_rect", selectors.paper_frame).each(function(){
                    linkadjust.apply(this, [ps]);
                });
            }
            
            
            
            //新しいスケールを変数へ格納
            params.wscale = ws;
            params.pscale = ps;
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
    }

})(jQuery);
