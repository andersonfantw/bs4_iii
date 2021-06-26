
/*--------------------------------------------------------------------------*
 *  
 *  original
 *  
 *--------------------------------------------------------------------------*/	
 /* 初期値設定 */
var dmxset = {
    links: {} ,
    linkBool : false
};

var orientationChangeTimer = -1;
var orientationChangeCount = 0;

var docID = "d";
var docID_suffix = "__dmx";

var xmlDir = "../xml/";
var dataDir = "../data/";
var thumbDir = "../images/Thumbnail/";
var pageTrim = false;
var pageTrimS = false;
var pageTrimE = false;
var userAgent;
	
var zoom = "3.0"; // 拡大率
var myUrl = "";

var urlquery;
var forumUrl = '';
var forumQuery = 'action=forum_detail';
var forumPage = 0;

// 拡大率設定
var windowWidth = window.innerWidth;
//var windowHeight = window.innerHeight;
var windowHeight = window.outerHeight

var galleryHeight = ( window.outerHeight - 44 );
var galleryWidth = 0;

var mediaWidth = 0;
var mediaHeight = 0;

// 画像表示領域内側の余白
var galleryPaddingWidth = 20;
var galleryPaddingHeight = 0;
var galleryPaddingZoom = 20;

var wrapHeight;
var imageHeight;
var imageWidth;
var imageMarginTop;

var headerHeight = 20;
var taskbarHeight = 44;
var taskbarTop;

var zoomRatio = 0;
var ratio = 0;
var direction = "len"; // 方向 縦：len 横：side
var directionRate = {
    "iPhone"  : {"len" : {"w" : 3.5 , "h" : 2  } , "side" : {"w" : 0  , "h" : 3.3}},
    "iPad"    : {"len" : {"w" : 8   , "h" : 4.7} , "side" : {"w" : 15 , "h" : 3.4}},
    "android" : {"len" : {"w" : 3.5 , "h" : 2  } , "side" : {"w" : 0  , "h" : 3.3}},
    "other"   : {"len" : {"w" : 3.5 , "h" : 2  } , "side" : {"w" : 0  , "h" : 3.3}}
    };

// 初期ページ数設定
var pageStart = 0;
var pageEnd = 0;
var pageDifference = 1;

// 左綴じ or 右綴じ
var bindDirection;

// ページ数
var dispStPageNum;
var dispStPageCnt;

// 最終ページ番号、jCarousel最終スライド番号
var lastPageNumber;
var lastCarouselNumber;

// 
var galleryRatio = 1;

// 紙面縦横比
var paperRatio;

var searchMatch;
var searchTxt;

var listNumPerPager = 69;
var pagerViewNum = 5;

if (navigator.userAgent.indexOf('iPhone') > 0 && navigator.userAgent.indexOf('iPad') == -1)
{
     listNumPerPager = 11;
}


$(function(){
    // 戻る無効
    history.forward();

    // ページタイトル取得
    $.ajax({ 
        type: "GET", 
        url: xmlDir + "EBookIndexTextParam.xml", 
        dataType: "xml", 
        success: function(xml) {
            var _pageIndexText = new Array();
            $(xml).find("IndexText").each(function(){
                var no = $(this).attr("PageNo");
                var text = $(this).find("IndexTextData").text();
                // s.okayama
                //_pageIndexText[no - 1] = text;
                _pageIndexText.push({pageNo:no, indexText:text});
            });

            // ページ設定取得
            $.ajax({ 
                type: "GET", 
                url: xmlDir + "EBookSetBaseParam.xml", 
                dataType: "xml", 
                success: function(xml) {
                    // s.okayama
                    /*$(xml).find("SETEnvPage").each(function(){
                            var PageAction = $(this).find("PageAction").text();
                            // s.okayama
                            if(PageAction == 1 || PageAction == 2){
                                    pageTrim = true;
                            }
                    });*/
                                        
                    // s.okayama
                    var setBaseParamXML = xml;

                    // ギャラリー初期値設定
                    $.ajax({ 
                        type: "GET", 
                        url: xmlDir + "Build.xml", 
                        dataType: "xml", 
                        success: function(xml) {
                            // s.okayama
                            //setBuild(xml, _pageIndexText);
                            setBuild(xml , setBaseParamXML, _pageIndexText);
                        } 
                    });
                }
            });
        } 
    });	

    // 検索
    var xhr,
        form = $("#frm"),
        keyword = $("#search"),
        typeTimer;

    form.submit(function() {
        var _txt = keyword.val();
        var _match = new Array();
        var resultFlg = false;
        
        // valueがなければ何もしないよ
        if ( !_txt || _txt.length === 0 ) {
            return false;
        }	
        if ( typeTimer ) {
            clearTimeout(typeTimer);
        }
        
        // ズームモードはきっとく
        jCarousel.zoomMode = false;
        $("#imageArea").css({ "webkitTransform" : 'scale(1.0)'});
        jCarousel.scale = "1.0";
        jCarousel.sel[0].topnow = 0;

        // typeTimerでkeyupのする度にリクエストするのではなくて
        // 350ミリセカンド以上間があいた場合にリクエストする
        typeTimer = setTimeout(function() {
            searchClicker = false;
            xhr = $.get( dataDir + 'search_key.txt',function(data){
                // s.okayama
                //var csv = $.csv(",",'"',"\n")(data);
                var csv = data.split("\n");
                for (var i = 0, l = csv.length; i < l; i++) {
                    var p = String(csv[i]).indexOf(_txt , 0);
                    if(0 <= p){
                        // s.okayama
                        //_match.push ({"txt" : csv[i].join("") , "start" : p , "line" : i});
                        _match.push ({"txt":csv[i], "start":p ,"line":i});
                    }
                }

                $.get( dataDir + 'search_pnt.txt',function(data){
                    var csv = $.csv(",",'"',"\n")(data);
                    var result = [];
                    for (var i = 0, l = _match.length; i < l; i++) {
                        result.push(csv[_match[i]["line"]]);
                    }

                    var point = new Object();
                    for (var i = 0, l = result.length; i < l; i++) {
                        var rg = result[i]
                        var r = String(rg).split(";");
                        _match[i]["point"] = { "x1" : r[0] , "y1" : r[1] , "x2" : r[2] , "y2" : r[3] };
                        _match[i]["page"] = r[4];
                    }
						
                    // 後で呼び出された時のために
                    searchMatch = _match;
                    searchTxt = _txt;
                    if(!resultFlg){
                        resultFlg = true;
                        dmxset.searchResult(searchTxt ,  searchMatch ,true);
                    }
                    return false;
                });
            });
        }, 350)
        
        return false;
    });	
});

// slideNum -> pageLabel
function getPageLabel_by_sNum(s_num)
{
    var label;
    var page = parseInt(s_num);
    
    if (page < dispStPageNum) { label = '-'; }
    else { label = page - dispStPageNum + dispStPageCnt; }
    
    return label;
}

// carouselNum -> pageLabel
function getPageLabel_by_cNum(c_num)
{
    var label;
    var page = parseInt(c_num);
    var s_num = cNum2sNum(c_num);
    
    label = getPageLabel_by_sNum(s_num);
    
    return label;
}

// slideNum -> carouselNum
function sNum2cNum (s_num)
{
    console.log('snum2cNum('+s_num+')');
    var c_num = 0;
    
    if (s_num == 0 && pageTrimS) { s_num = 1; }
    
    $("#gallery .jCarousel > li").each(function(){
        if ($(this).attr('name') == s_num)
        {
            return false;
        }
        
        c_num++;
    });
    
    console.log('sNum2cNum.c_num1:'+c_num);
    c_num = getReversed_cNum(c_num);
    console.log('sNum2cNum.c_num2:'+c_num);
    
    return c_num;
}

// carouselNum -> slideNum
function cNum2sNum (c_num)
{
    console.log('cNum2sNum('+c_num+')');
    var s_num;
    
    c_num = getConverted_cNum(c_num);
    s_num = $("#imageArea .jCarousel li:nth-child(" + (c_num + 1) + ")").attr('name');
    console.log('cNum2sNum.s_num:'+s_num)
    //if (pageTrimS) { s_num++; }
    
    return s_num;
}

// carouselNum -> converted carouselNum
function getConverted_cNum(c_num)
{
    var c = parseInt(c_num);
    
    if (bindDirection == 'left')
        return c;
    else
        return $("#imageArea .jCarousel li").length - 1 - c;

    return c;
}

// converted carouselNum -> carouselNum
function getReversed_cNum(c_num)
{
    var c = parseInt(c_num);
    
    if (bindDirection == 'left')
        return c;
    else
        return $("#imageArea .jCarousel li").length - 1 - c;
    
    return c;
}

function getCarouselNum(c_num)
{
    var c = parseInt(c_num);
    
    if (bindDirection == 'left')
        return c;
    else
        return $("#imageArea .jCarousel li").length - 1 - c;
}

function getRectType(w, h)
{
    if (w / h < 1)
        return 'portrait'; //縦長
    else if (1 < w / h)
        return 'landscape'; //横長
    else
        return 'square'; //正方
}

function addPage(sNum)
{
    sNum = parseInt(sNum);
    
    var lbnum = 0;
    var ubnum = lastPageNumber;
    var lckflg = true;
    var mckflg = true;
    var uckflg = true;
    
    var hit = false;
    
    if (pageTrimS) { lbnum = 1; }
    
    // ページ作成済みかどうかチェック（前のページ）
    if (lbnum <= sNum - 1)
    {
        if ($("#gallery .jCarousel > li[name="+(sNum - 1)+"]").length == 1)
        {
            lckflg = false;
        }
    }
    else
    {
        lckflg = false;
    }
    
    // ページ作成済みかどうかチェック（対象ページ）
    if ($("#gallery .jCarousel > li[name="+sNum+"]").length == 1)
    {
        mckflg = false;
    }
    
    // ページ作成済みかどうかチェック（次のページ）
    if (sNum + 1 <= ubnum)
    {
        if ($("#gallery .jCarousel > li[name="+(sNum + 1)+"]").length == 1)
        {
            uckflg = false;
        }
    }
    else
    {
        uckflg = false;
    }
    
    if (lckflg || mckflg || uckflg)
    {
        $("#gallery .jCarousel > li").each(function(){
            var liSNum = $(this).attr('name');
            
            if (sNum == liSNum)
            {
                hit = true;
                
                if (lckflg)
                {
                    $(this).before("<li name=\"" + (sNum - 1) + "\"></li>");
                }
                if (uckflg)
                {
                    $(this).after("<li name=\"" + (sNum + 1) + "\"></li>");
                }
                
                return false;
            }
            else if (sNum < liSNum)
            {
                hit = true;
                
                if (lckflg)
                {
                    $(this).before("<li name=\"" + (sNum - 1) + "\"></li>");
                }
                if (mckflg)
                {
                    $(this).before("<li name=\"" + sNum + "\"></li>");
                }
                if (uckflg)
                {
                    $(this).before("<li name=\"" + (sNum + 1) + "\"></li>");
                }
                
                return false;
            }
        });
        
        if (!hit)
        {
            if (lckflg)
            {
                $("#gallery .jCarousel").append("<li name=\"" + (sNum - 1) + "\"></li>");
            }
            if (mckflg)
            {
                $("#gallery .jCarousel").append("<li name=\"" + sNum + "\"></li>");
            }
            if (uckflg)
            {
                $("#gallery .jCarousel").append("<li name=\"" + (sNum + 1) + "\"></li>");
            }
        }
        
        if (lckflg || mckflg || uckflg)
        {
            var tMax = $("#jCarousel-object0 > li").length;
            var tWidth = windowWidth;
            
            jCarousel.sel[0].max = tMax;

            $("#jCarousel-object0 > li").css("float", bindDirection);
            
            $("#jCarousel-object0").css('width', (tMax * tWidth)+"px");
            
            //var ml = (bindDirection == 'right') ? -((tMax - 1) * tWidth) : 0;
            //$(target).css('margin-left', ml+'px');
            
            $("#jCarousel-object0 > li").css({
                width: tWidth + "px",
                listStyle: "none",
                padding: 0,
                margin: 0,
                color: "#000"
	    });
        }
            
    }
    
    //$("#gallery .jCarousel").append("<li name=\"" + sNum + "\"><img src=\"../" + docID + "/" + docID + "__" + sNum + "__400.jpg\" /></li>");
}

function setSearchResultResion (sNum)
{
    if (searchResultSet && searchResultSet[sNum])
    {
        if ($("#gallery .jCarousel li[name="+sNum+"]").length)
        {
            for (var i = 0; i < searchResultSet[sNum].length; i++)
            {
                $("#gallery .jCarousel li[name="+sNum+"]").append(searchResultSet[sNum][i]);
            }
            
            searchResultSet[sNum] = null;
        }
    }
}

function loadPageImage ()
{
    var sNum = -1;
    
    $("#gallery .jCarousel > li").each(function(){
        if ($(this).length == 1)
        {
            sNum = parseInt($(this).attr('name'));
            if ($(this).find('img').length == 0)
            {
                $(this).append("<img src=\"../" + docID + "/" + docID + "__" + sNum + "__400.jpg\" />");
                $(this).find('img').css("height", imageHeight).attr("height", imageHeight);
                $(this).find('img').css("margin-top", imageMarginTop);

                dmxset.setLinks(sNum);
            }
        }
    });
}

function drawThumbnail (curPager)
{
    var totalNum = lastPageNumber + 1;
    var startNum = pageTrimS ? 1 : 0;
    
    if (!curPager)
        curPager = 1;
    
    // ページャーの総数
    var pagerTotalNum = Math.ceil(((totalNum - startNum) / listNumPerPager));
    
    if(pagerTotalNum < curPager)
        curPager = pagerTotalNum;
    
    var pagerSt, pagerEd;
    if (pagerTotalNum < pagerViewNum)
    {
        pagerSt = 1;
        pagerEd = pagerTotalNum;
    }
    else
    {
	if((pagerViewNum - 2) < curPager)
        {
            if((curPager + 2) <= pagerTotalNum)
            {
                pagerEd = curPager + 2;
                pagerSt = pagerEd - (pagerViewNum - 1);
            }
            else
            {
                pagerEd = pagerTotalNum;
                pagerSt = pagerEd - (pagerViewNum - 1);
            }
	}
        else
        {
            pagerSt = 1;
            pagerEd = pagerViewNum;
	}
    }
    
    $("#thumb .pagerbar ul").empty();
    if (1 < pagerTotalNum)
    {
        if (Math.ceil(pagerViewNum / 2) < curPager)
        {
            $("#thumb .pagerbar ul").append('<li class="pager" onClick="drawThumbnail(0)">&lt;&lt;</li>');
        }
        
        if (1 < curPager)
        {
            // prev &p=(curPager-1)
            $("#thumb .pagerbar ul").append('<li class="pager" onClick="drawThumbnail('+(curPager-1)+')">&lt;</li>');
        }
        
        for (var i = pagerSt; i <= pagerEd; i++)
        {
            if (i != curPager)
            {
                // &p=i
                $("#thumb .pagerbar ul").append('<li class="pager" onClick="drawThumbnail('+i+')">'+i+'</li>');
            }
            else
            {
                // nolink
                $("#thumb .pagerbar ul").append('<li class="curpager">'+i+'</li>');
            }
        }
        
        if (curPager < pagerTotalNum)
        {
            // prev &p=(curPager+1)
            $("#thumb .pagerbar ul").append('<li class="pager" onClick="drawThumbnail('+(curPager+1)+')">&gt;</li>');
        }
    
        if (curPager < pagerTotalNum - Math.floor(pagerViewNum / 2))
        {
            // prev &p=(curPager+1)
            $("#thumb .pagerbar ul").append('<li class="pager" onClick="drawThumbnail('+(pagerTotalNum)+')">&gt;&gt;</li>');
        }
    }
    
    var listSt = (curPager - 1) * listNumPerPager + startNum;													//データ読み出し開始位置算出
    var listEd = listSt + listNumPerPager;
    
    if (listSt == 0)
        listSt = startNum;
    
    if (lastPageNumber < listEd)
        listEd = lastPageNumber;
    
    $("#thumb .icon-box").empty();
    
    for (var i = listSt; i <= listEd; i++)
    {
        // サムネイル　
        $("#thumb .icon-box").append('<dl><dt><a href="#carousel" name="0" rel="' + i + '"><img src="' + thumbDir + 'Page' + i + '.jpg" alt="" /></a></dt></dl>');
    }
    
    $("#thumb .icon-box dl").css('float', bindDirection);
    
    $(".jCarouselNavi a").unbind("click", pageJumpFunc);
    $(".jCarouselNavi a").bind("click", pageJumpFunc);
    
    //$(".jCarouselNavi a").unbind().bind("click", pageJumpFunc);
}

/*
 ■ 初期値の設定
 --------------------- */
//function setBuild(xml , pageIndexText){
function setBuild(buildXML , setBaseParamXML, pageIndexText){
    // s.okayama
    $(setBaseParamXML).find("SETEnvPage").each(function(){
        switch ($(this).find("PageAction").text())
        {
            case '1':
            case '5':
                bindDirection = 'right';
                break;
            case '2':
            case '3':
            case '4':
            case '6':
            default:
                bindDirection = 'left';
                break;
        }
                
        // s.okayama
        pageTrimS = ('true' == $(this).find("PageActionCover").text().toLowerCase()) ? true : false;
        pageTrimE = ('true' == $(this).find("PageActionEndCover").text().toLowerCase()) ? true : false;

        // s.okayama
        dispStPageNum = parseInt($(this).find("PageDispStartPage").text());
        dispStPageCnt = parseInt($(this).find("PageDispStartCont").text());
        
    });
        
    $(setBaseParamXML).find("SETEnvZoom").each(function(){
        if ($(this).find("ZoomZoomScale06").text() == 'True') { zoomRatio = Math.sqrt(10); }
        else if ($(this).find("ZoomZoomScale05").text() == 'True') { zoomRatio = Math.sqrt(8); }
        else if ($(this).find("ZoomZoomScale04").text() == 'True') { zoomRatio = Math.sqrt(6); }
        else if ($(this).find("ZoomZoomScale03").text() == 'True') { zoomRatio = Math.sqrt(4); }
        else if ($(this).find("ZoomZoomScale02").text() == 'True') { zoomRatio = Math.sqrt(2); }
    });
    
    // s.okayama
    $(setBaseParamXML).find("SETEnvOutput").each(function(){
            $("#carousel > .toolbar > h1").append($(this).find("OutputHTMLTitle").text());
    });
        
    $(buildXML).find("SETEnvUserSetParam").each(function(){

        // URL取得
        myUrl = (location.href).split("?");
        myUrl = myUrl[0];
        myUrl = (myUrl).split("#");
        myUrl = myUrl[0];
        
        // 最終ページ番号
        lastPageNumber = parseInt($(this).find("LastPageNumber").text());
        lastCarouselNumber = lastPageNumber;

        var get = getRequest();
        var p = (get["p"]) ? parseInt(get["p"]) : 0;
        
        if (!isNaN(p) && 0 <= p)
        {
            pageStart = p;
        }
        
        // s.okayama
        // 表紙対向ページが非表示なら1から開始する
        if (pageTrimS)
        {
            if (pageStart == 0)
            {
                pageStart = 1;
            }
            
            lastCarouselNumber--;
        }
        // 裏表紙対向ページが非表示＆見開きで終わるなら最終ページ番号を1引く
        if (pageTrimE && lastPageNumber % 2 == 1)
        {
            lastPageNumber--;
            lastCarouselNumber--;
        }

        if (p == 0) p = pageStart;

        // ページ添え字調整		
        // ダミーがあるパターンの場合
        if(pageTrim){
                //pageStart = 1;
                //pageEnd = -1;
                pageDifference = 0;
        }

        // プロジェクト名の設定 s.okayama
        docID = $(this).find("ProjectName").text() + docID_suffix;

        // s.okayama
        //$("#carousel > .toolbar > h1").append($(this).find("ProjectName").text() );

        $("#carousel > .toolbar > dfn").append("<span id='nowPageNum'>" + getPageLabel_by_sNum(pageStart) + "</span>/" + "<span id='lastPageNum'>" + getPageLabel_by_sNum(lastPageNumber) + "</span>");

        mediaWidth = $(this).find("MediaWidth").text();
        mediaHeight = $(this).find("MediaHeight").text();
        
        // HTML初期セット
        $("#gallery").append('<div id="imageArea"><ol class="jCarousel"></ol></div>');
        $("#text .icon-box").append('<ul></ul>');
        
        // 全ページ読み込むコードはコメントアウト
        // s.okayama
        /*for (var i = pageStart, l = lastPageNumber; i <= l; i++) {
            $("#gallery .jCarousel").append("<li><img src=\"../" + docID + "/" + docID + "__" + i + "__400.jpg\" /></li>");
            $("#thumb .icon-box").append('<dl><dt><a href="#carousel" name="0" rel="' + sNum2cNum(i) + '"><img src="' + thumbDir + 'Page' + i + '.jpg" alt="" /></a></dt></dl>');
        }*/
        
        // 最初のページと次のページだけ画像を読み込み、あとは<li>だけをappendする
        // s.okayama
        for (var i = pageStart, l = lastPageNumber; i <= l; i++) {
            if (i == pageStart || i == pageStart + 1)
            {
                $("#gallery .jCarousel").append("<li name=\"" + i + "\"><img src=\"../" + docID + "/" + docID + "__" + i + "__400.jpg\" /></li>");
                
                // リンクを一部読み込む
                dmxset.setLinks(i);
            }
            else
            {
                // 空のliはappendしない
                //$("#gallery .jCarousel").append("<li></li>");
            }
        }

        // サムネイルを一部読み込む
        // s.okayama
        /*for (var i = pageStart, l = lastPageNumber; i <= l; i++) {
            // サムネイル　
            $("#thumb .icon-box").append('<dl><dt><a href="#carousel" name="0" rel="' + sNum2cNum(i) + '"><img src="' + thumbDir + 'Page' + i + '.jpg" alt="" /></a></dt></dl>');
        }*/
        drawThumbnail();

        // サムネイルの並び変更（綴じ方向 left / right）
        // s.okayama
        $("#thumb .icon-box dl").css('float', bindDirection);
        
        // リンクを一部読み込むのでコメントアウト
        // s.okayama
        /*var pageText = "";
        for (var i = pageStart, l = lastPageNumber; i <= l; i++) {
            // リンク設定
            dmxset.setLinks(i);			
        }*/

        // テキスト目次
        for (var i = 0; i < pageIndexText.length; i++) {
            $("#text .icon-box > ul").append('<li><a href="#carousel" name="0" rel="' + pageIndexText[i].pageNo + '">' + pageIndexText[i].indexText + '</a></li>');
        }

        // 現在ページ数セット	
        //var get = getRequest();
        //var c = (get["c"]) ? parseInt(get["c"]) : 0;
        //var p = (get["p"]) ? parseInt(get["p"]) : 0;
        //$("#nowPageNum").text(c);
        //$("#nowPageNum").text(getPageLabel_by_sNum(c));
        
        //if (p == 0) p = pageStart;
        
        $("#nowPageNum").text(getPageLabel_by_sNum(p));
        
        // PDFのON/OFF
        
        if ($(".taskbar a[name=pdf]").length == 1)
        {
            $(".taskbar a[name=pdf]").css("display", "inline");
            $(".taskbar a[name=pdf]").click(function () {
                window.open('../pdf/book.pdf', '_blank');
            });
        }
        
        
        // SNS連携のON/OFF
        var s = (get["s"] == '0') ? 0 : 1;
        if (s == 0)
        {
            $("#funcTwitter").css("display", "none");
            $("#funcFaceBook").css("display", "none");
            $("#funcMail").css("display", "none");
            $("#funcIndex").css("display", "none");
            
            // フォーラム
            forumPage = p;
            forumUrl = $(this).find("ForumURL").text();
            if (get["bid"])
                forumQuery += "&id="+get["bid"];
        }
        else
        {
            $("#funcForum").css("display", "none");
        }
        // ページURLセット
        //urlquery = "?c=" + (c - parseInt(pageDifference));
        //urlquery = "?c=" + c;
        urlquery = "?startpage=" + p;
        
        
        
        // クリックイベントスタート
        dmxset.eventini();

        // 画面サイズ制御
        jCarousel.setInterface();
        
        // jCarouselスタート
        jCarousel.ini();
        jCarousel.set();

        // s.okayama
        // 機能一覧、サムネイルなどの背景の高さを画面に合わせる
        // 
        // @ToDo 高さ微調整が必要
        //$(".body").height($("#carousel .toolbar").height() + $("#gallery").height() + $(".taskbar").height());
        $(".body").height(window.innerHeight);

        // サムネイル or list
        $("#carousel .taskbar a[name=link]").bind("click", function(){
            // リンク表示
            if(dmxset.linkBool == false){
                if(!$("#imageArea .link_result").attr("href")){
                    $(".jCarousel").each(function () {
                        var target = this;
                        var tNum = $(target).attr("name");
                        var carouselNum = jCarousel.sel[tNum].current;
                        
                        console.log('click1:'+carouselNum);
                        
                        //dmxset.getLinks(parseInt(carouselNum) + parseInt(pageStart));
                        dmxset.getLinks(cNum2sNum(carouselNum));
                    });
                    dmxset.linkBool = true;
                }
            }else{
                // 値を初期化
                $("#imageArea .link_result").remove();
                dmxset.linkBool = false;
            }
        });	

    });
}

function setInterface_reset()
{
    window.scrollTo(0,1);
    setInterface_rep();
}

function setInterface_rep ()
{
    jCarousel.resize()
    if(searchMatch instanceof Array){
        // 検索結果再配置
        dmxset.searchResult(searchTxt, searchMatch , false);
    }
}

function orientationChange_rep () {
    if (windowWidth != window.innerWidth || windowHeight != window.innerHeight)
    {
        clearInterval(orientationChangeTimer);
        orientationChangeTimer = -1;
        orientationChangeCount = 0;

        setInterface_reset();
    }
    else
    {
        orientationChangeCount++;
    }
    
    if (10 <= orientationChangeCount)
    {
        clearInterval(orientationChangeTimer);
        orientationChangeTimer = -1;
        orientationChangeCount = 0;
    }
}

// サムネイル、テキストもくじ、検索結果クリック時
function pageJumpFunc ()
{
    var tNum = $(this).attr("name");
    var sNum = $(this).attr("rel");

    addPage(sNum);

    var carouselNum = sNum2cNum(sNum);

    //alert('link3:'+carouselNum);
    jCarousel.scrollBool = true; 
    jCarousel.zoomMode = false; 
    jCarousel.scale = "1.0";  
    $("#imageArea").css({ "webkitTransform" : 'scale(1.0)'}); 
    jCarousel.setInterface(); 
    jCarousel.setZoom(tNum , "1.0"); 
    jCarousel.sel[tNum].topnow = 0; 
    jCarousel.sel[0].leftnow = 0; 

    //jQT.goTo("#carousel" , "slideup" , true);
    jQT.goTo("#carousel");
    setTimeout(function(){
            jCarousel.slide(tNum, carouselNum, 0);
            jCarousel.setPageNum(carouselNum);
    }, 100);

    return false;
};

/*--------------------------------------------------------------------------*
 *  
 *  クリックイベント
 *  
 *--------------------------------------------------------------------------*/
dmxset.eventini = function () {
	// メール
	$("#funcMail dt a").click(function(){
            location.href = "mailto:?body=" + encodeURIComponent(myUrl + urlquery);
	});

	// Twitter
	$("#funcTwitter dt a").click(function(){
            //location.href = "http://twitter.com/?status=" + encodeURIComponent(myUrl);
	    location.href = "http://twitter.com/?status=" + encodeURIComponent(myUrl + urlquery);
	});
	
	// FaceBook
	$("#funcFaceBook dt a").click(function(){
            //location.href = "http://www.facebook.com/sharer.php?u=" + encodeURIComponent(myUrl);
	    location.href = "http://www.facebook.com/sharer.php?u=" + encodeURIComponent(myUrl + urlquery);
	});
	 
	// Forum
	$("#funcForum dt a").click(function(){
            //location.href = forumUrl +'?' + forumQuery + '&open_page_num=' + forumPage;
            window.open(forumUrl +'?' + forumQuery + '&open_page_num=' + forumPage, '_blank');
	});
	 
	// サムネイル or list
	$("#carousel .taskbar a").bind("click", function(){
                // リンク表示
                if($(this).attr("name") != "link"){	
			jCarousel.scrollBool = true;
			jCarousel.zoomMode = false;
			jCarousel.scale = "1.0"; 
			$("#imageArea").css({ "webkitTransform" : 'scale(1.0)'});
			jCarousel.sel[0].topnow = 0;		
			jCarousel.sel[0].leftnow = 0;
		}
	});
	 
        $(".jCarouselNavi a").unbind().bind("click", pageJumpFunc);

	// サムネイル処理
	$(".innerLink").unbind().bind("click", function(){
		//alert('link4');
		var tNum = $(this).attr("name");
		var sNum = $(this).attr("rel");
                
                addPage(sNum);

                var carouselNum = sNum2cNum(sNum);

		jCarousel.zoomMode = false; 
		jCarousel.scale = "1.0";  
		$("#imageArea").css({ "webkitTransform" : 'scale(1.0)'}); 
		jCarousel.setInterface(); 
		jCarousel.setZoom(tNum , "1.0"); 
		jCarousel.sel[tNum].topnow = 0; 
		jCarousel.sel[0].leftnow = 0; 

		// alert(tNum + "  " + carouselNum);
		setTimeout(function(){
			// alert(tNum + "  " + carouselNum);
			jCarousel.slide(tNum , carouselNum , 400);
			jCarousel.setPageNum(carouselNum);
		}, 400);
		
		return false;	});

}	 


/*--------------------------------------------------------------------------*
 *  
 *  検索処理
 *  
 *--------------------------------------------------------------------------*/

var searchResultSet;
dmxset.searchResult = function (_txt, _match, _view) {
        searchResultSet = new Array();
	
	// 検索結果を反映
	$("#imageArea .search_result").remove();
	$("#result h2").text('検索結果 「' + _txt + '」');
	$("#result .icon-box").html('<ul></ul>');

	var wWidth = parseInt($("#imageArea .jCarousel li").css("width"));
	var gWidth = parseInt($("#imageArea .jCarousel li img").css("width"));
	var ratio = gWidth / mediaWidth;
	
        // s.okayama
        //var lastPageNum = parseInt($("#lastPageNum").text()) + parseInt(pageStart) - parseInt(pageEnd) - 1;
        var lastPageNum = lastPageNumber;
        
	if(_match.length >= 1){

		for (var i = 0, l = _match.length; i < l; i++) {
			var html;
			//if(lastPageNum >= parseInt(_match[i]["page"])){
			if(lastPageNum + 1 >= parseInt(_match[i]["page"])){
                            var sNum = parseInt(_match[i]["page"]) - 1;
				//html = '<li><a href="#carousel" name="0" rel="' + (parseInt(_match[i]["page"]) - 1 + parseInt(pageEnd)) + '">Page ' + (parseInt(_match[i]["page"]) +  parseInt(pageEnd)) + "<span>" + _match[i]["txt"]  + '</span></a></li>'
				html = '<li><a href="#carousel" name="0" rel="' + sNum + '">Page ' + getPageLabel_by_sNum(parseInt(_match[i]["page"]) - 1) + "<span>" + _match[i]["txt"]  + '</span></a></li>'
				$("#result .icon-box > ul").append(html);
	
                                // s.okayama
				//var sActive = parseInt(_match[i]["page"]) + parseInt(pageEnd);
				var sActive = parseInt(_match[i]["page"]) - 1;
				
                                var sCurrent = parseInt(_match[i]["page"] * galleryWidth);	
                                
                                var x = parseInt((wWidth - gWidth) / 2) + (parseInt(_match[i]["point"]["x1"]) / zoomRatio * ratio );
				
                                // s.okayama
                                //var y = ( parseInt( _match[i]["point"]["y1"])) * ratio;
				var y = ( parseInt( _match[i]["point"]["y1"])) / zoomRatio * ratio + imageMarginTop;
				
                                var sWidth = (parseInt(_match[i]["point"]["x2"]) - parseInt(_match[i]["point"]["x1"])) / zoomRatio * ratio;
				var sHeight = (parseInt(_match[i]["point"]["y2"]) - parseInt(_match[i]["point"]["y1"])) / zoomRatio * ratio;
	
				html = '<div class="search_result" style="top:' + y + 'px; left:' + x + 'px; width:' + sWidth + 'px; height:' + sHeight + 'px; border:1px solid red;"><!-- ' + _match[i]["page"] + " " + _match[i]["txt"] + '--></div>';
				
                                // s.okayama
                                //$("#imageArea .jCarousel li:nth-child(" + sActive + ")").append(html);
                            //$("#imageArea .jCarousel li[name=" + sNum + "]").append(html);

                            if ($("#imageArea .jCarousel li[name=" + sNum + "]").length)
                            {
				$("#imageArea .jCarousel li[name=" + sNum + "]").append(html);
			}
                            else
                            {
                                if (!searchResultSet[sNum]) searchResultSet[sNum] = new Array();
                                searchResultSet[sNum].push(html);
		}
                        }
		}
	}else{
		$("#result .icon-box > ul").append('<li>「' + _txt + '」' + "はみつかりませんでした。</li>");		
	}
	
	// click有効に
	dmxset.eventini();
	
	if(_view){
            jCarousel.scrollBool = true;
            //jQT.goTo("#result" , "slideup" , true);
	    jQT.goTo("#result");
	}
}



/*--------------------------------------------------------------------------*
 *  
 *  XMLからリンク取得
 *  
 *--------------------------------------------------------------------------*/
 
dmxset.setLinks = function (_num) {
    
        console.log('setLinks('+_num+')');
    
        var nowNum = ("000" + _num).slice(-4);
	
        // ページタイトル取得
	$.ajax({ 
		type: "GET", 
		url: xmlDir + "EBookLinkDataParam_" + nowNum + ".xml", 
		dataType: "xml", 
		async: false,
		success: function(xml) {
			var _links = new Array();
			var _i = 0;
			
			$(xml).find("LinkData").each(function(){
												  
				if($(this).find("LinkURLFlag").text() == "True"){
					var _url = $(this).find("LinkURL").text();
					if((_url.substring(0,1)!='/') && (_url.substring(0,7)!='http://')) _url='../'+_url;

					var _target = $(this).find("LinkTARGET").text();
                                        
                                        var _urlflg = true;
				}else{
					var _url = "#carousel";
					var _target = "_self";
                                        
                                        // s.okayama
                                        //var _pageNum = (parseInt($(this).find("LinkPage").text()) + parseInt(pageStart) + parseInt(pageEnd));
					var _pageNum = parseInt($(this).find("LinkPage").text());
				        var _urlflg = false;
				}
				
				var _alt = $(this).find("LinkALT").text();
				var x1 = $(this).find("LinkAreaXStr").text() * 2;
                                var y1 = $(this).find("LinkAreaYStr").text() * 2;
				var x2 = (parseInt($(this).find("LinkAreaXStr").text()) + parseInt($(this).find("LinkAreaXLen").text())) * 2;
				var y2 = (parseInt($(this).find("LinkAreaYStr").text()) + parseInt($(this).find("LinkAreaYLen").text())) * 2;
				
				// s.okayama
                                //_links[_i] = {	"url" : _url, "target" : _target, "alt" : _alt, "pageNum" : _pageNum,
				_links[_i] = {	"url" : _url, "urlflg" : _urlflg,  "target" : _target, "alt" : _alt, "pageNum" : _pageNum,
								"point" : { "x1" : x1 , "y1" : y1 , "x2" : x2 , "y2" : y2 } ,
								"color" :{ "mOutC" : $(this).find("LinkAreaMouseOutColor").text() , 
										 "mOutT" : $(this).find("LinkAreaMouseOutTransparent").text() ,
										 "mOverC" : $(this).find("LinkAreaMouseOverColor").text() ,
										 "mOverT" : $(this).find("LinkAreaMouseOverTransparent").text(),
										 "mOutLineC" : $(this).find("LinkAreaMouseOutOutLineColor").text() , 
										 "mOutLineW" : $(this).find("LinkAreaMouseOutOutLineWidth").text() ,
										 "mOverLineC" : $(this).find("LinkAreaMouseOverOutLineColor").text() ,
										 "mOverLineW" : $(this).find("LinkAreaMouseOverOutLineWidth").text()
										 }
								};
				_i = _i + 1;
			});
			// 保存
			dmxset.links[_num] = _links;
		} 
	});	
}

/*--------------------------------------------------------------------------*
 *  
 *  リンク設置
 *  
 *--------------------------------------------------------------------------*/
 
dmxset.getLinks = function (_num , _view) {
        console.log('getLinks('+_num+', '+_view+')');
	
	// 値を取得
	var _array = dmxset.links[_num]; 

	// 値を初期化
	if($("#imageArea .link_result").attr("href")){
		$("#imageArea .link_result").remove();
	}
	$("#link h2").text('「 Page' + _num + ' 」リンク一覧 ');
	$("#link .icon-box").html('<ul></ul>');

	var wWidth = parseInt($("#imageArea .jCarousel li").css("width"));
	var gWidth = parseInt($("#imageArea .jCarousel li img").css("width"));
	var ratio = (gWidth / 2) / mediaWidth;

	for (var i = 0, l = _array.length; i < l; i++) {
		var html;
		
		// 一覧更新	
		// ページ内リンクだったら
                // s.okayama
		//if(_array[i]["pageNum"] && dmxset.trim(_array[i]["pageNum"]) != ""){
		if(_array[i]["urlflg"] == false){
			// s.okayama
                        //var addAttr = 'name="0" rel="' + (parseInt(_array[i]["pageNum"]) + parseInt(pageEnd)) + '"';
			var addAttr = 'name="0" rel="' + _array[i]["pageNum"] + '"';
                        var addClass = "innerLink";
			var text = "Page " + _array[i]["pageNum"];	
		}else{
			var addAttr = "";
			var addClass = "";
			var text = (dmxset.trim(_array[i]["alt"]) != "") ? _array[i]["alt"] : _array[i]["url"];	
		}
		html = '<li><a href="' + _array[i]["url"] + '" target="' + _array[i]["target"] + '" ' + addAttr + '>' + text + '</a></li>'
		
                $("#link .icon-box > ul").append(html);

		// リンクエリア配置
		//var sActive = parseInt(pageDifference) + _num;
                var sActive = sNum2cNum(_num);
                
		var url = _array[i]["url"];
		var target = _array[i]["target"];
		var x = parseInt((wWidth - gWidth) / 2) + (parseInt(_array[i]["point"]["x1"]) * (ratio) );
		
                // s.okayama
                //var y = ( parseInt( _array[i]["point"]["y1"])) *(ratio);
		var y = ( parseInt( _array[i]["point"]["y1"])) *(ratio) + imageMarginTop;
		
                var sWidth = (parseInt(_array[i]["point"]["x2"]) - parseInt(_array[i]["point"]["x1"])) * (ratio);
		var sHeight = (parseInt(_array[i]["point"]["y2"]) - parseInt(_array[i]["point"]["y1"])) * (ratio);

                html = '<a class="link_result ' + addClass + '" ' + addAttr + ' href="' + url + '" target="' + target + '"" style="top:' + y + 'px; left:' + x + 'px; width:' + sWidth + 'px; height:' + sHeight + 'px;"><!-- --></a>';
		$("#imageArea .jCarousel li:nth-child(" + (getReversed_cNum(sActive) + 1) + ")").append(html);

	}

	// click有効に
	dmxset.eventini();
	
	if(_view){
            jCarousel.scrollBool = true;
            //jQT.goTo("#result" , "slideup" , true);
	    jQT.goTo("#result");
	}
}

/*--------------------------------------------------------------------------*
 *  
 *  空白除去
 *  
 *--------------------------------------------------------------------------*/
 
dmxset.trim = function (_var) {
	return unescape(escape(_var).replace(/^(%u3000|%20|%09|%0D|%0A)+|(%u3000|%20|%09|%0D|%0A)+$/g, ""));
}


/*--------------------------------------------------------------------------*
 * 
 * jCarousel - jQuery Plugin
 * http://d.hatena.ne.jp/kudakurage/
 *
 * Copyright (c) 2010 Kazuyuki Motoyama
 * Licensed under the MIT license
 *
 * $Date: 2010-11-28
 * $version: 1.3
 * 
 * This jQuery plugin will only run on devices running Mobile Safari
 * on iPhone or iPod Touch devices running iPhone OS 2.0 or later. 
 * http://developer.apple.com/iphone/library/documentation/AppleApplications/Reference/SafariWebContent/HandlingEvents/HandlingEvents.html#//apple_ref/doc/uid/TP40006511-SW5
 * 
 *--------------------------------------------------------------------------*/
var jCarousel = {
    ua: "pc",
    mediaWidth: 0,
    mediaHeight: 0,
    num: 0,
    target: new Array,
    sel: new Array,
    activeBool: false,
    zoomBool: false,
    zoomMode: false,
    active: 0,
    main: 0,
    scale: 1,
    dbltapsaveTime:0,
    dbltapBool: true,
    scrollBool: false,
    colorSet: {
        black: {
            back: "#eee",
            active: "#888",
            shadow: "#333"
        },
        white: {
            back: "#ddd",
            active: "#999",
            shadow: "#333"
        }
    }
};

jCarousel.set = function (arg) {
    jCarousel.color = jCarousel.colorSet.black;
    jCarousel.main = 0;
    if (typeof arg == "object") {
        if (arg.color == "white") jCarousel.color = jCarousel.colorSet.white;
        if (!isNaN(arg.main)) jCarousel.main = eval(arg.main)
    }
	
    /* セット */
    $(".jCarousel").each(function () {
        if (!$(this).attr("name")) {
            var tNum = jCarousel.num;
            var target = this;
            //var tWidth = $(target).width();
            var tMax = $(target).find("li").length;
            
            $(".jCarousel:after").css({
                visibility: "hidden",
                display: "block",
                clear: "both",
                height: 0,
                fontSize: 0,
                content: "."
            });
            $(".jCarousel").css({
                display: "inline-table",
                minHeight: "1%"
            });
            var tHeight = $(target).height();
            $(target).attr("name", tNum);
            $(target).attr("id", "jCarousel-object" + tNum);
            jCarousel.target[tNum] = target;
            jCarousel.sel[tNum] = {
                width: tWidth,
                max: tMax,
                top: 0,
                left: 0,
                leftnow: null,
		topnow: null,
                current: 0,
                startX: 0,
                startY: 0,
                endX: 0,
                endY: 0,
                auto: 0,
            };
            $(target).wrap('<div class="jCarouselWrapper' + tNum + '"></div>');
            var naviInner = "";
            for (var i = 0; i < tMax; i++) naviInner += '<a rel="' + i + '" name="' + tNum + '"></a>';
            $(".jCarouselWrapper" + tNum).append('<div class="jCarouselNavi">' + naviInner + "</div>");
            $(".jCarouselWrapper" + tNum + ' .jCarouselNavi a[rel="' + jCarousel.sel[tNum].current + '"]').addClass("selected");
            $(".jCarouselWrapper" + tNum).css({
                overflow: "hidden",
                width: "100%"
            });
            
            // s.okayama
            //$("#jCarousel-object" + tNum + " > li").css("float", "right");bindDirection
            $("#jCarousel-object" + tNum + " > li").css("float", bindDirection);
            
            // sokayama
            //var tWidth = $(target).width();
            var tWidth = windowWidth;
            jCarousel.sel[tNum].width = tWidth;
            
            // s.okayama
            /*$(target).css({
                width: "900000px",
            }*/
            $(target).css({
                width: (tMax * tWidth)+"px",
                listStyle: "none",
                padding: 0,
                margin: 0,
                backgroundColor: "transparent"
            });
            
            // s.okayama
            //var ml = (bindDirection == 'right') ? -((tMax - 1) * tWidth) : 0;
            var ml = (bindDirection == 'right') ? -tWidth : 0;
            $(target).css('margin-left', ml+'px');
            
            $("#jCarousel-object" + tNum + " > li").css({
                width: tWidth + "px",
                listStyle: "none",
                padding: 0,
                margin: 0,
                color: "#000",
					//background:"#0f0"
            });
            $(".jCarouselWrapper" + tNum + " .jCarouselNavi").css({
                clear: "both",
                textAlign: "center"
            });
            $(".jCarouselWrapper" + tNum + " .jCarouselNavi a").css({
                display: "inline-block",
                width: "8px",
                height: "8px",
                margin: "5px",
                padding: "0px",
                backgroundColor: jCarousel.color.back,
                cursor: "pointer",
                borderRadius: "5px",
                boxShadow: "0px -2px 1px " + jCarousel.color.shadow,
                webkitBorderRadius: "5px",
                webkitBoxShadow: "0px -2px 1px " + jCarousel.color.shadow,
                mozBorderRadius: "5px",
                mozBoxShadow: "0px -2px 1px " + jCarousel.color.shadow
            });
            $(".jCarouselWrapper" + tNum + " .jCarouselNavi a.selected").css({
                backgroundColor: jCarousel.color.active
            });
            
            if (jCarousel.ua == "mobile") {

                $(target).bind("touchstart", function () {
                    var tNum = $(this).attr("name");
                    jCarousel.active = tNum;
                    // alert("pageX:"+event.touches[0].pageX+" pageY:"+event.touches[0].pageY);
                    // event.touches[0].pageX .wrapの原点からのX座標
                    // event.touches[0].pageY .wrapの原点からのY座標
                    jCarousel.sel[tNum].startX = event.touches[0].pageX;
                    jCarousel.sel[tNum].startY = event.touches[0].pageY;
                    jCarousel.sel[tNum].endX = event.touches[0].pageX;
                    jCarousel.activeBool = true;
                    jCarousel.scrollBool = false;
                    
                    //alert("page:"+event.touches[0].pageX+' '+event.touches[0].pageY);

                    // 上部のアドレスバー除去
                    var h = $('html, body');
                    if($(".current h1").text() && h.scrollTop() <= 1){
                            // alert(h.scrollTop());
                            h.animate({scrollTop: 0}, 600);
                            // h.scrollTop(0);
                            // event.preventDefault();
                    }

                    // ダブルタップ
                    if (event.touches.length == 1 && jCarousel.dbltapBool == true) {
                        var prev = jCarousel.dbltapsaveTime;
                        var date = new Date;
                        jCarousel.dbltapsaveTime = date.getTime();
                        
                        if (prev !== null) {
                            if (jCarousel.dbltapsaveTime - prev < 300) {
                                jCarousel.dbltapBool = false;

                                // 一定時間後復帰
                                setTimeout(function(){
                                    jCarousel.dbltapBool = true;
                                }, 500);

                                jCarousel.activeBool = false;
                                
                                if(jCarousel.zoomMode)
                                { // ズーム中の場合 -> ズーム解除
                                    
                                    jCarousel.zoomMode = false;
                                    jCarousel.setZoom(tNum , "1.0");
                                    jCarousel.sel[tNum].leftnow = 0;
                                    
                                    var carouselNum = jCarousel.sel[tNum].current;
                                    jCarousel.slide(tNum, carouselNum, 300);
                                    
                                }
                                else
                                { // ズーム中ではない場合 -> ズームする
                                    
                                    jCarousel.zoomBool = true;
                                    jCarousel.zoomMode = true;
                                    jCarousel.setZoom(tNum , zoom);
                                    jCarousel.sel[tNum].leftnow = 0;
                                }
                            }
                        }
                    }

                });

                $(window).bind("touchmove", function () {
                    // ズームモード中はscrollバー機能停止
                    if (!jCarousel.scrollBool) {
                            event.preventDefault();
                    }

                    // スライド（ページめくり）
                    if (jCarousel.activeBool && !jCarousel.zoomMode) {
                        var tNum = jCarousel.active;
                        
                        jCarousel.sel[tNum].endX = event.touches[0].pageX;
                        jCarousel.sel[tNum].endY = event.touches[0].pageY;
                        var offsetX = -jCarousel.sel[tNum].startX + jCarousel.sel[tNum].endX;
                        var offsetY = -jCarousel.sel[tNum].startY + jCarousel.sel[tNum].endY;
                        
                        if (offsetX / offsetY > 0.5 || offsetX / offsetY < -0.5) {
                            event.preventDefault();
                            // ページを読み込む
                            $(jCarousel.target[tNum]).css({
                                marginLeft: jCarousel.sel[tNum].left + offsetX + "px"
                            })
                        } else jCarousel.activeBool = false
                    
                    // 拡大・縮小
                    }else if(jCarousel.zoomMode && jCarousel.zoomBool){
                        var tNum = jCarousel.active;
                        
                        jCarousel.sel[tNum].endX = event.touches[0].pageX;
                        jCarousel.sel[tNum].endY = event.touches[0].pageY;
                        
                        var offsetX = (-jCarousel.sel[tNum].startX + jCarousel.sel[tNum].endX) * ratio;
                        var offsetY = -jCarousel.sel[tNum].startY + jCarousel.sel[tNum].endY;
                        
                        // 左右の座標
                        var carouselNum = jCarousel.sel[tNum].current;
                        // 拡大なし状態での.jCarouselの左端の座標（margin-left値）
                        var wCenter = -carouselNum * jCarousel.sel[tNum].width;
			//alert("wCenter:"+wCenter)
                        //alert("top left:"+jCarousel.sel[tNum].top+' '+jCarousel.sel[tNum].left)
                        //alert("margin-left:"+$(jCarousel.target[tNum]).css('margin-left'))
                        
                        /*
                        var w = directionRate[userAgent][direction]["w"];
                        var h = directionRate[userAgent][direction]["h"];
			if(w >= 1){
                                var width = parseInt(( (galleryWidth / (w -1)) * ratio) * jCarousel.scale)
                        }else{
                                var width = (w > 0)? parseInt(( (galleryWidth ) * ratio) * jCarousel.scale) : 0;
                        }
                        if(h >= 1){
                                var height = parseInt( ((galleryHeight / (h -1)) * ratio) * jCarousel.scale)
                        }else{
                                var height = (h > 0)? parseInt( ((galleryHeight) * ratio) * jCarousel.scale) : 0;
                        }
                        
                        var wleft = wCenter + width;
                        var wright = wCenter - width;
                        var hTop = height;
                        var hBottom = - height;
                        */
                        
                        var scale = jCarousel.scale;
                        var width = (Math.floor(imageWidth / 2) * scale - Math.floor(galleryWidth / 2)) / scale;
                        var height = Math.floor(imageHeight / 2);
                        
                        var wleft = wCenter + width;
                        var wright = wCenter - width;
                        var hTop = height - imageMarginTop + galleryPaddingZoom;
                        var hBottom = - height - galleryPaddingZoom;
                        
                        var moveX = jCarousel.sel[tNum].left + offsetX;
                        var moveY = jCarousel.sel[tNum].top + offsetY;

                        /* X方向の移動量制限（限界の設定） */
                        if(moveX > wleft){
                            moveX = wleft;
                        }else if(moveX < wright){
                            if (carouselNum < lastCarouselNumber)
                                moveX = wright;
                        }
                        /* Y方向の移動量制限（限界の設定） */
                        if(moveY > hTop){
                            moveY = hTop;
                        }else if(moveY < hBottom){
                            moveY = hBottom;
                        }
                        
                        $(jCarousel.target[tNum]).animate({
                                marginLeft: moveX + "px" ,
                                marginTop: moveY + "px"
                        }, 0, "easeCarousel", function () {});
						
                        // 現在の値を保存
                        jCarousel.sel[tNum].leftnow = moveX;
                        jCarousel.sel[tNum].topnow = moveY;
                    }
                });
                
                $(window).bind("touchend", function () {
                    if (jCarousel.activeBool && !jCarousel.zoomMode) {
                        jCarousel.activeBool = false;
                        var tNum = jCarousel.active;
                        var offsetX = -jCarousel.sel[tNum].startX + jCarousel.sel[tNum].endX;
                        var eventArea = jCarousel.sel[tNum].width / 5;
                        var carouselNum = jCarousel.sel[tNum].current;
                        var carouselMax = jCarousel.sel[tNum].max;
                        if (offsetX > eventArea && carouselNum > 0){ 
                            carouselNum--;
                        }else if (offsetX < -eventArea && carouselNum < carouselMax - 1){
                            carouselNum++;
                        }
			
                        //var sNum = parseInt(cNum2sNum(getReversed_cNum(carouselNum)));
                        var sNum = parseInt(cNum2sNum(carouselNum));
                        addPage(sNum);
                        
                        carouselNum = sNum2cNum(sNum);
                        jCarousel.slide(tNum, carouselNum, 300);
                        // 現ページ表示切り替え
                        jCarousel.zoomMode = false;
                        jCarousel.setZoom(tNum , "1.0");
                        jCarousel.sel[tNum].topnow = 0;
                        
                        jCarousel.setPageNum(carouselNum);
                        
                    }
                    else if(jCarousel.zoomMode)
                    {
                        var tNum = jCarousel.active;
						
                        // 現在の紙面の中心座標を計算する
                        var carouselNum = jCarousel.sel[tNum].current;
                        var wCenter = -carouselNum * jCarousel.sel[tNum].width;
						
                        var wleft   = wCenter + parseInt(( (galleryWidth / directionRate[userAgent][direction]["w"]) * ratio) * jCarousel.scale);
                        var wright  = wCenter - parseInt(( (galleryWidth / directionRate[userAgent][direction]["w"]) * ratio) * jCarousel.scale);
                        var hTop    =   parseInt( ((galleryHeight / directionRate[userAgent][direction]["h"]) * ratio) * jCarousel.scale);
                        var hBottom = - parseInt( ((galleryHeight / directionRate[userAgent][direction]["h"]) * ratio) * jCarousel.scale);
						
			// $("#nowPageNum").text(wleft + " r" + wright + " t" + hTop + " b" + hBottom + " w"  + directionRate[userAgent][direction]["w"] + " h " + directionRate[userAgent][direction]["h"]);
			
                        var moveX = jCarousel.sel[tNum].leftnow;
                        var moveY = jCarousel.sel[tNum].topnow;
						
                        // 値が無い
                        if(!moveX){
                            moveX = wCenter;
                        }else if(!moveY){
                            moveY = 0;
                        }
                        /* 横の移動 */
                        if(moveX > wleft){
                            moveX = wleft;
                        }else if(moveX < wright){
                            moveX = wright;
                        }
                        /* 縦の移動 */
                        if(moveY > hTop){
                            moveY = hTop;
                        }else if(moveY < hBottom){
                            moveY = hBottom;
                        }							
                        $(jCarousel.target[tNum]).animate({
                                        marginLeft: moveX + "px" ,
                                        marginTop: moveY + "px" ,
                        }, 600, "easeCarousel", function () {});
			
			jCarousel.sel[tNum].left = moveX;
                        jCarousel.sel[tNum].top = moveY;

                    }
                });

                //スクリーンに2本指が触れた時
                $(target).bind("gesturestart", function () {
                    jCarousel.scrollBool = false;
                });

                //スクリーンに2本指が触れ、かつスライドした時
                $(target).bind("gesturechange", function () {
                    var tNum = jCarousel.active;
                    jCarousel.active = tNum;
                    jCarousel.zoomBool = false;
                    jCarousel.activeBool = false;
                });
				
                // ジェスチャーイベントが終了した場合
                $(target).bind("gestureend", function () {
                    var tNum = jCarousel.active;
                    jCarousel.active = tNum;

                    if((event.scale) > 1 && jCarousel.zoomMode == false){				
                            jCarousel.zoomMode = true;
                            jCarousel.zoomBool = true;
                            jCarousel.setZoom(tNum , zoom);

                    }else{
                            jCarousel.zoomMode = false;
                            jCarousel.setZoom(tNum , "1.0");
                            jCarousel.sel[tNum].topnow = 0;
                    }

                });

            }
            
            if (jCarousel.ua == "pc") {
                $(target).bind("mousedown", function (event) {
                    var tNum = $(this).attr("name");
                    jCarousel.active = tNum;
                    jCarousel.sel[tNum].startX = event.pageX;
                    jCarousel.activeBool = true;
                    return false
                });
                $(window).bind("mousemove", function (event) {
                    if (jCarousel.activeBool) {
                        var tNum = jCarousel.active;
                        jCarousel.sel[tNum].endX = event.pageX;
                        var offset = -jCarousel.sel[tNum].startX + jCarousel.sel[tNum].endX;
  
  						$(jCarousel.target[tNum]).css({
                            marginLeft: jCarousel.sel[tNum].left + offset + "px"
                        })
                    }
                });
                $(window).bind("mouseup", function (event) {


                    if (jCarousel.activeBool) {
                        jCarousel.activeBool = false;
                        var tNum = jCarousel.active;
                        jCarousel.sel[tNum].endX = event.pageX;
                        var offset = -jCarousel.sel[tNum].startX + jCarousel.sel[tNum].endX;
                        var eventArea = jCarousel.sel[tNum].width / 5;
                        var carouselNum = jCarousel.sel[tNum].current;
                        var carouselMax = jCarousel.sel[tNum].max;
                        if (offset > eventArea && carouselNum > 0){
                            carouselNum--;
                        }else if (offset < -eventArea && carouselNum < carouselMax - 1){
                            carouselNum++;
                        }
                        
                        //var sNum = parseInt(cNum2sNum(getReversed_cNum(carouselNum)));
                        var sNum = parseInt(cNum2sNum(carouselNum));
                        addPage(sNum);
                        
                        carouselNum = sNum2cNum(sNum);
                        
                        // 現ページ表示切り替え
                        jCarousel.setPageNum(carouselNum);
                        jCarousel.slide(tNum, carouselNum, 250);
                    }
                })
            }

            jCarousel.num++
        } /*alert(tNum);*/


        /* 初期値 */
        var get = getRequest();
        var tNum = (get["t"])? get["t"] : "0";
        //var carouselNum = (get["c"])? parseInt(get["c"]) : "0";
        var sNum = (get["p"])? parseInt(get["p"]) : 0;
        
        /*if (pageTrimS && 0 < sNum)
        {
            sNum--;
        }*/
        
        console.log("1:"+sNum)
        var carouselNum = sNum2cNum(sNum);
        console.log("2:"+carouselNum)
        
        //if (pageTrimS && 0 < carouselNum)
        //    carouselNum--;

        jCarousel.slide(tNum, carouselNum, 800);
    })
};

/*--------------------------------------------------------------------------* 
 *  初期
 *--------------------------------------------------------------------------*/	
jCarousel.ini = function () {
    var ua = navigator.userAgent;
    if (ua.indexOf("iPhone") > -1 || ua.indexOf("iPad") > -1 || ua.indexOf("iPod") > -1 || ua.toLowerCase().indexOf("android") > -1) jCarousel.ua = "mobile";
    else jCarousel.ua = "pc";
	jCarousel.uaCheck();
	
    $(window).keydown(function (e) {
        if (e.keyCode == 39) {
            var tNum = jCarousel.main;
            var carouselNum = jCarousel.sel[tNum].current;
            var carouselMax = jCarousel.sel[tNum].max;
            if (carouselNum < carouselMax - 1) {
                carouselNum++;
                jCarousel.slide(tNum, carouselNum, 800)
            }
        }
        if (e.keyCode == 37) {
            var tNum = jCarousel.main;
            var carouselNum = jCarousel.sel[tNum].current;
            if (carouselNum > 0) {
                carouselNum--;
                jCarousel.slide(tNum, carouselNum, 800)
            }
        }
    });
    $(window).bind("orientationchange", function () {
        
        if (windowWidth != window.innerWidth || windowHeight != window.innerHeight)
        {
            setInterface_reset();
        }
        else
        {
            if (-1 < orientationChangeTimer)
            {
                clearInterval(orientationChangeTimer);
                orientationChangeTimer = -1;
                orientationChangeCount = 0;
            }
            
            orientationChangeTimer = setInterval(orientationChange_rep, 500);
        }
        
        /*if(searchMatch instanceof Array){
            // 検索結果再配置
            alert('research')
            dmxset.searchResult(searchTxt, searchMatch , false);
        }
        jCarousel.resize();*/
    });
    
    $(window).resize(function () {
        /*jCarousel.resize()
        if(searchMatch instanceof Array){
            // 検索結果再配置
            dmxset.searchResult(searchTxt, searchMatch , false);
        }*/
    })
};


/*--------------------------------------------------------------------------* 
 *  端末チェック
 *--------------------------------------------------------------------------*/	
jCarousel.uaCheck = function () {
    var ua = navigator.userAgent;

    if (ua.indexOf("iPhone") > -1 || ua.indexOf("iPod") > -1){
        userAgent = "iPhone";
    }else if(ua.indexOf("iPad") > -1){
        userAgent = "iPad";
    }else if(ua.indexOf("android") > -1){
        userAgent = "android";
    }else{
        userAgent = "other";		
    }

};

/*--------------------------------------------------------------------------* 
 *  スライド
 *--------------------------------------------------------------------------*/	
jCarousel.slide = function (activeNum, carouselNum, speed) {
    
    $("#gallery .jCarousel li").height($("#gallery .jCarousel").height());
    
    //alert('slide.carouselNum:'+carouselNum);
    //var sNum = parseInt(cNum2sNum(getReversed_cNum(carouselNum)));
    var sNum = parseInt(cNum2sNum(carouselNum));
    addPage(sNum);

    //alert('slide.sNum:'+sNum);
    setSearchResultResion(sNum - 1)
    setSearchResultResion(sNum)
    setSearchResultResion(sNum + 1)
    
    carouselNum = sNum2cNum(sNum);
    //alert('slide.carouselNum:'+carouselNum);
    
    jCarousel.activeBool = false;
    var tNum = activeNum;
    var margin = -carouselNum * jCarousel.sel[tNum].width;
    $(jCarousel.target[tNum]).animate({
        marginLeft: margin + "px"
    }, speed, "easeCarousel", function () { loadPageImage() });
	
    //alert('slide:'+tNum+' '+carouselNum);
    jCarousel.sel[tNum].left = margin;
    jCarousel.sel[tNum].current = carouselNum;
    $(".jCarouselWrapper" + tNum + " .jCarouselNavi a").css({
        backgroundColor: jCarousel.color.back
    });
    $(".jCarouselWrapper" + tNum + " .jCarouselNavi a[rel='" + carouselNum + "']").css({
        backgroundColor: jCarousel.color.active
    })
	
    // リンク設定読み込み
    if(dmxset.linkBool == true){
        var timer = 0;
        if(speed == 0){
            timer = 1000;		
        }
        setTimeout(function(){
            //dmxset.getLinks(parseInt(carouselNum) + parseInt(pageStart));
            //dmxset.getLinks(cNum2sNum(carouselNum));
            dmxset.getLinks(sNum);
        }, timer);
    }
};

/*--------------------------------------------------------------------------* 
 *  リサイズ
 *--------------------------------------------------------------------------*/
jCarousel.resize = function () {
    $(".jCarousel").each(function () {
        var target = this;
        var tNum = $(target).attr("name");
        
        // s.okayama
        //var tWidth = $(".jCarouselWrapper" + tNum).width();
        var tWidth = window.innerWidth;
        
        // s.okayama
        var tMax = jCarousel.sel[tNum].max;
        
        jCarousel.sel[tNum].width = tWidth;
        
        var margin = -jCarousel.sel[tNum].current * jCarousel.sel[tNum].width;
        
        jCarousel.sel[tNum].left = margin;
        
        $(target).css({
            width: tMax * tWidth + "px"
        });
        
        $(target).find("li").css({
            width: tWidth + "px"
        });
        $(target).css({
            marginLeft: margin + "px"
        })
            
        /*if(dmxset.linkBool == true){
            var carouselNum = jCarousel.sel[tNum].current;
            dmxset.getLinks(parseInt(carouselNum) + parseInt(pageStart));
        }*/
    });
    
    jCarousel.setInterface();

    $(".jCarousel").each(function () {
        var target = this;
        var tNum = $(target).attr("name");
        
        if(dmxset.linkBool == true){
            var carouselNum = jCarousel.sel[tNum].current;
            //dmxset.getLinks(parseInt(carouselNum) + parseInt(pageStart));
            dmxset.getLinks(cNum2sNum(parseInt(carouselNum)));
        }
    });
};

/*--------------------------------------------------------------------------* 
 *  アニメーション
 *--------------------------------------------------------------------------*/	
jQuery.extend(jQuery.easing, {
    easeCarousel: function (x, t, b, c, d) {
        return c * ((t = t / d - 1) * t * t + 1) + b
    }
});

/*--------------------------------------------------------------------------* 
 *  ページ数セット  
 *--------------------------------------------------------------------------*/	
jCarousel.setPageNum = function (num) {
    var set = -1;
    
    // s.okayama
    //num = getConverted_cNum(num);
     
    // s.okayama
    //var set = parseInt(num) + parseInt(pageStart) + parseInt(pageDifference);
    set = cNum2sNum(num);

    // s.okayama
    //$("#nowPageNum").text(set);
    $("#nowPageNum").text(getPageLabel_by_cNum(num));

    // ページURLセット
    //urlquery = "?c=" + (set);
    urlquery = "?startpage=" + (set);

    // Forum
    forumPage = set;
};

/*--------------------------------------------------------------------------* 
 *  拡大処理
 *--------------------------------------------------------------------------*/
jCarousel.setZoom = function (tNum , scale) {
	$("#imageArea").css({ "webkitTransition" : "-webkit-transform 0.5s" , "webkitTransform" : 'scale(' + scale + ')'});	
	// window.preventDefault();
	$(jCarousel.target[tNum]).animate({
		marginTop: 0 + "px"
	}, 0, "easeCarousel", function () {});
	jCarousel.scale = scale; 
};

// 拡大なし状態で
function getGalleryRatio ()
{
    
}


/*--------------------------------------------------------------------------* 
 *  画面サイズセット
 *--------------------------------------------------------------------------*/
jCarousel.setInterface = function () {
	windowWidth = window.innerWidth;
        
        // s.okayama
        //windowHeight = window.outerHeight;
	//galleryHeight = ( window.outerHeight - 44 );
        windowHeight = window.innerHeight; // ウィンドウの高さ（）
	galleryHeight = window.innerHeight - headerHeight - taskbarHeight - galleryPaddingHeight * 2; // 画像を表示できる領域の高さ
        galleryWidth = windowWidth - galleryPaddingWidth * 2;
        
        // 画像表示領域の縦横比
        galleryRatio = galleryWidth / galleryHeight;
        // 紙面の縦横比
        imageRatio = mediaWidth / mediaHeight;
        
        var imageFitType;
        // @ToDo 引き延ばしの限界考慮(画像が画像表示領域より大きいことが前提)
        //
        if (galleryRatio < imageRatio)
        { // 紙面の左右が画像表示領域に内接する
            imageFitType = 1;
            imageWidth = galleryWidth;
            imageHeight = Math.floor(galleryWidth / imageRatio);
            imageMarginTop = Math.floor((galleryHeight - imageHeight) / 2);
        }
        else if (imageRatio < galleryRatio)
        { // 紙面の上下が画像表示領域に内接する
            imageFitType = 2;
            imageWidth = Math.floor(imageRatio * galleryHeight);
            imageHeight = galleryHeight;
            imageMarginTop = 0;
        }
        else
        { // 紙面の四辺が画像表示領域と接する
            imageFitType = 3;
            imageWidth = galleryWidth;
            imageHeight = galleryHeight;
            imageMarginTop = 0;
        }
        
        //galleryRatio = parseInt(galleryHeight) / parseInt(mediaHeight);
	//galleryWidth = parseInt(parseInt(mediaWidth) * galleryRatio);
        
	// 拡大率セット
	//ratio = (galleryWidth / 2) / mediaWidth;
	ratio = imageWidth / mediaWidth;
	
	var ua = navigator.userAgent;
        
        //alert("iw:"+window.innerWidth+" ih:"+window.innerHeight+" ow:"+window.outerWidth+" oh:"+window.outerHeight);
        
	// 縦横判定
	if (windowWidth > windowHeight) {
		direction = "side";
	}else{
		direction = "len";		
	}
	
        $("window").ready(function() {

            /*if ( ua.indexOf("iPad") > -1 ) {
                $("#wrap", this).css("height", windowHeight);
                $("#gallery img", this).css("height", galleryHeight - 20).attr("height", galleryHeight - 20);
                $(".taskbar", this).css("top", windowHeight );
            }else if ( ua.indexOf("iPod") > -1 ) {
                $("#wrap", this).css("height", windowHeight);
                $("#gallery img", this).css("height", galleryHeight ).attr("height", galleryHeight );
                $(".taskbar", this).css("top", windowHeight + 20);
            } else {
                if (direction == "side") {
                    $("#wrap", this).css("height", windowHeight -100);
                    $("#gallery img", this).css("height", galleryHeight - 164).attr("height", galleryHeight - 164);
                    $(".taskbar", this).css("top", galleryHeight - 104);
                } else {
                    $("#wrap", this).css("height", windowHeight);
                    $("#gallery img", this).css("height", galleryHeight - 20).attr("height", galleryHeight - 20);
                    $(".taskbar", this).css("top", windowHeight );
                }
            }*/
            
            wrapHeight = windowHeight;
            taskbarTop = windowHeight - taskbarHeight;
            
            $("#wrap", this).css("height", wrapHeight);
            $("#gallery img", this).css("height", imageHeight).attr("height", imageHeight);
            $("#gallery img", this).css("width", imageWidth).attr("width", imageWidth);
            $("#gallery img", this).css("margin-top", imageMarginTop);
            $("#gallery li", this).css("width", windowWidth);
            $("#gallery li", this).css("height", imageHeight + imageMarginTop);
            $(".taskbar", this).css("top", taskbarTop);
        });
        
        $(".body").height(windowHeight);
        $("#thumb .pagerbar").css('bottom', '');
        $("#thumb .pagerbar").css('top', (windowHeight - 51) + 'px');
};

/*
$(function () {
   //  jCarousel.set()
});
*/


/*--------------------------------------------------------------------------*
 *  
 *  jQuery.csv
 *  
 *--------------------------------------------------------------------------*/

/* Usage:
 *  jQuery.csv()(csvtext)               returns an array of arrays representing the CSV text.
 *  jQuery.csv("\t")(tsvtext)           uses Tab as a delimiter (comma is the default)
 *  jQuery.csv("\t", "'")(tsvtext)      uses a single quote as the quote character instead of double quotes
 *  jQuery.csv("\t", "'\"")(tsvtext)    uses single & double quotes as the quote character
 */

jQuery.extend({
    csv: function(delim, quote, linedelim) {

        delim = typeof delim == "string" ? new RegExp( "[" + (delim || ","   ) + "]" ) : typeof delim == "undefined" ? ","    : delim;
        quote = typeof quote == "string" ? new RegExp("^[" + (quote || '"'   ) + "]" ) : typeof quote == "undefined" ? '"'    : quote;
        lined = typeof linedelim == "string" ? new RegExp( "[" + (linedelim || "\r\n") + "]+") : typeof linedelim == "undefined" ? "\r\n" : linedelim;

        function splitline (v) {
            // Split the line using the delimitor
            var arr  = v.split(delim),
                out = [], q;
            for (var i=0, l=arr.length; i<l; i++) {
                if (q = arr[i].match(quote)) {
                    for (j=i; j<l; j++) {
                        if (arr[j].charAt(arr[j].length-1) == q[0]) { break; }
                    }
                    var s = arr.slice(i,j+1).join(delim);
                    out.push(s.substr(1,s.length-2));
                    i = j;
                }
                else { out.push(arr[i]); }
            }

            return out;
        }

        return function(text) {
            var lines = text.split(lined);
            for (var i=0, l=lines.length; i<l; i++) {
                lines[i] = splitline(lines[i]);
            }
            return lines;
        };
    }
});



/*--------------------------------------------------------------------------*
 *  
 *  getRequest
 *  
 *--------------------------------------------------------------------------*/
function getRequest(){
  if(location.search.length > 1) {
	var get = new Object();
	var ret = location.search.substr(1).split("&");
	for(var i = 0; i < ret.length; i++) {
	  var r = ret[i].split("=");
	  var p = r[1].split("#");
	  get[r[0]] = p[0];
	  
	}
	return get;
  } else {
	return false;
  }
}

/*--------------------------------------------------------------------------*
 *  
 *  set transition animate
 *  
 *  MIT-style license. 
 *  
 *  2010 Kazuma Nishihata 
 *  http://blog.webcreativepark.net/2010/09/17-183446.html
 *  
 *--------------------------------------------------------------------------*/
jQuery.fn.transitionAnimate = function(prop,duration,easing,callback){
	if (jQuery.isEmptyObject(prop)) {
		return this
	}
	callback = callback? callback:function(){};
	jQuery(this)
		.css("-webkit-transition","all "+duration+" "+easing)
		.unbind("webkitTransitionEnd")
		.bind("webkitTransitionEnd",function(){
			jQuery(this)
				.unbind("webkitTransitionEnd")
				.css("-webkit-transition","");
			callback.apply(this);
		})
		.css(prop);
	return this;
}


/*--------------------------------------------------------------------------*
 *  
 *  showObject
 *  
 *--------------------------------------------------------------------------*/

function showObject(elm,type){ 
var str = '「' + typeof elm + "」の中身"; 
var cnt = 0; 
for(i in elm){ 
if(type == 'html'){ 
str += '<br />\n' + "[" + cnt + "] " + i.bold() + ' = ' + elm[i]; 
} 
else { 
str += '\n' + "[" + cnt + "] " + i + ' = ' + elm[i]; 
} 
cnt++; 
status = cnt; 
} 
return str; 
} 


/*--------------------------------------------------------------------------*
 *  
 *  print_r
 *  
 *--------------------------------------------------------------------------*/
// http://binnyva.blogspot.com/2005/10/dump-function-javascript-equivalent-of.html
function print_r(arr, br, nbsp) {
 br = (br) ? br : "\n";
 nbsp = (nbsp) ? nbsp : " ";
 function dump(arr, br, nbsp, level) {
  var dumped_text = "";
  if(!level) {
   level = 0;
  }
  //The padding given at the beginning of the line.
  var level_padding = "";
  for(var j=0; j<level+1; j++) {
   level_padding += nbsp + nbsp;
  }
  if(typeof(arr)=="object") { //Array/Hashes/Objects
   for(var item in arr) {
    var value = arr[item];
    if(typeof(value)=="object") { //If it is an array,
     dumped_text += level_padding + "[" + item + "] => Array" + br;
     dumped_text += nbsp + level_padding + "(" + br + dump(value, br, nbsp, level+1) + nbsp + level_padding + ")" + br;
    }else {
     dumped_text += level_padding + "[" + item + "] => '" + value + "'" + br;
    }
   }
  }else { //Stings/Chars/Numbers etc.
   dumped_text = "===>" + arr + "<===(" + typeof(arr) + ")";
  }
  return dumped_text;
 }
 return "Array" + br + nbsp + "(" + br + dump(arr, br, nbsp) + nbsp + ")";
}
