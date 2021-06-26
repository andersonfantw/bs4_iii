var selector_header = '';
var selector_loading='#loading';
var selector_menu = '#menu';
var selector_submenu = '#submenu';
var selector_menu_all = '';
var selector_menu_new = '';
var selector_bookshelf = '#bookshelf';
var selector_book_content = '#bs_detail .bs_content';
var selector_searchResult = '#bookshelf';
var selector_dialogue_bg = '#dialogue_bg';
var selector_footer = '';
var tplBook = '#TemplateFrame > li:eq(0)';
var tplSearch = '#TemplateFrame > li:eq(0)';

var footer = '';
$(document).ready(function(){
    var scripts = ['http://code.jquery.com/ui/1.8.17/jquery-ui.min.js','../scripts/jquery.address-1.4.min.js','../scripts/jquery.ui.touch-punch.min.js','../scripts/bs.class.2.1.js'];
    for(i=0;i<scripts.length;i++){
        $.ajax({
          url: scripts[i],
          dataType: "script",
          async:false
        });
    }

    //設定config中的設定值
    if(footer!=''){
        $(selector_footer).html(footer.replace('\n','<br />'));
    }
});

var systemEnv = {
    _errormsgcount:0,
    isErrorLogLoad:false,
    mousePosX:0,
    mousePosY:0,
    deviceJSON:[]
}

var bookEnv = {
    IsDefaultData:false,
    InitJSON:{},
    MenuJSON:{},
    BooksJSON:{},
    BooksByCateJSON:{},
    BooksBySearchJSON:{},
    BooksDetailJSON:{},
    allbookCover: [],
    newbookCover: [],
    cateCover: [],
    currentCateName:'',
    currentMainCateId:0,
    currentCateId:0,
    currentBookId:0,
    requestCode:'',
    requestMsg:'',
    searchKeyword:'',
    loginAndOpenBook: false,
    openMode:'', //webbook, ibook
    currentMode:function(){
        return ($(selector_bookshelf).attr('class').indexOf('modebs')!=-1)?'bs':'list';
    }
}

var MenuHandler = {
    hasNode : ($(selector_menu).length>0),
    hasAllbook : $(selector_menu).is('.all'),
    hasNewbook : $(selector_menu).is('.new'),
    length : $(selector_menu).length,
    getFirstUserDefinedMenuId : function(){
        (new MsgAction('MenuHandler')).Log('getFirstUserDefinedMenuId');
        //return $(selector_submenu).find('ul:first li:first').attr('tabindex');
        if(bookEnv.MenuJSON!=null)
            if(bookEnv.MenuJSON[0].sub_category!=null)
                return bookEnv.MenuJSON[0].sub_category[0].c_id;
        return 0;
    },
    clear : function(){
        (new MsgAction('MenuHandler')).Log('clear');
        $(selector_menu).find('ul li').remove();
        $(selector_submenu).find('ul li').remove();
    },
    getFirstCate : function(){
        (new MsgAction('MenuHandler')).Log('getFirstCate');
        return $(selector_menu).find('ul:first li:first').attr('id');
    },
    selectAllbook : function(){
        (new MsgAction('MenuHandler')).Log('selectAllbook');
        $(selector_menu).find('ul li.all').addClass('selected');
    },
    selectNewbook : function(){
        (new MsgAction('MenuHandler')).Log('selectNewbook');
        $(selector_menu).find('ul li.new').addClass('selected');
    },
    selectFirstUserDefinedMenu : function(){
        (new MsgAction('MenuHandler')).Log('selectFirstUserDefinedMenu');
        $(selector_submenu).find('ul:first li:first').addClass('selected');
    },
    selectMenu : function(){
        (new MsgAction('MenuHandler')).Log('selectMenu');
        $(this).addClass('selected');
    },
    unselect : function(){
        (new MsgAction('MenuHandler')).Log('unselect');
        //設定選單的樣式為不選取
        $(selector_menu).find('.selected').removeClass('selected');
    },
    addAllbook : function(){
        (new MsgAction('MenuHandler')).Log('addAllbook');
        $(selector_menu).find('ul').append('<li id="all" class="item"><a>全部</a></li>');
        $(selector_menu).find('ul > #all').click(function(){
            bookEnv.currentCateName = '全部'
            BooksHandler.bindBookshelf([],bookEnv.BooksJSON.allbook,'全部');
            MotionHandler.slideOutFromLeft(selector_menu);
            MotionHandler.slideInFromRight(selector_bookshelf);
            $(selector_submenu).css({left:640});
        });
    },
    addNewbook : function(){
        (new MsgAction('MenuHandler')).Log('addNewbook');
        $(selector_menu).find('ul').append('<li id="new" class="item"><a>新書區</a></li>');
        $(selector_menu).find('ul > #new').click(function(){
            bookEnv.currentCateName = '新書區';
            BooksHandler.bindBookshelf(bookEnv.BooksJSON.newbook,[],'新書區');
            MotionHandler.slideOutFromLeft(selector_menu);
            MotionHandler.slideInFromRight(selector_bookshelf);
            $(selector_submenu).css({left:640});
        });
    },
    setInit : function(){
        (new MsgAction('MenuHandler')).Log('setInit');
    },
    doMenu : function(pre_text){
        (new MsgAction('MenuHandler')).Log('doMenu');
        for(i=0;i<bookEnv.MenuJSON.length;i++){
            $(selector_menu).find('ul').append('<li class="item" tabindex="'+bookEnv.MenuJSON[i].c_id+'"><a>'+pre_text+bookEnv.MenuJSON[i].c_name+'</a></li>');
        }
    },
    doSubmenu : function(pre_text,_cid){
        (new MsgAction('MenuHandler')).Log('doSubmenu');
        $(selector_submenu).find('ul li').remove();
        for(i=0;i<bookEnv.MenuJSON.length;i++){
            if(bookEnv.MenuJSON[i].c_id==_cid){
                for(j=0;j<bookEnv.MenuJSON[i].sub_category.length;j++){
                    if(bookEnv.MenuJSON[i].sub_category[j].c_id == bookEnv.currentCateId){
                        _selected = ' selected';
                    }else{
                        _selected = ' ';
                    }                    
                    $(selector_submenu).find('ul').append('<li class="item'+_selected+'" tabindex="'+bookEnv.MenuJSON[i].sub_category[j].c_id+'"><a>'+pre_text+bookEnv.MenuJSON[i].sub_category[j].c_name+'</a></li>');
                }
                return;
            }
        }
    }
}

;(function($) {
    $.fn.selectMenu = function(){
        $(this).addClass('selected');
    };
})(jQuery);

var MenuAction = function(){
    var _msgHandler = new MsgAction('MenuAction');

    Init();
    _msgHandler.Log('Loaded!!!');
    
    function Init(){
        if(bookEnv.member){
            $('#btnLogin').show();
        }
        if(bookEnv.InitJSON.ibook){
            $('#btnIbook').show();
        }
        if(bookEnv.InitJSON.webbook){
            $('#btnWebbook').show();
        }
        //選單動作
        MenuHandler.clear();
        if(bookEnv.InitJSON.allbook){
            MenuHandler.addAllbook();
            bookEnv.currentMainCateId = 'all';
        }
        if(bookEnv.InitJSON.newbook){
            MenuHandler.addNewbook();
            bookEnv.currentMainCateId = 'new';
        }
        if(bookEnv.MenuJSON!=null){
            MenuHandler.doMenu((bookEnv.IsDefaultData)?'測試資料-':'');
            if((!bookEnv.InitJSON.allbook) && (!bookEnv.InitJSON.newbook)){
                MenuHandler.doSubmenu((bookEnv.IsDefaultData)?'測試資料-':'',bookEnv.MenuJSON[0].c_id);
            }

            $(selector_menu).find('.cate ul li').each(function(){
                if(($(this).attr('id')!='all') && ($(this).attr('id')!='new')){
                    $(this).click(function(){
                        MenuHandler.doSubmenu((bookEnv.IsDefaultData)?'測試資料-':'',$(this).attr('tabindex'));
                        $(selector_submenu).find('.cate ul li').click(function(){
                            bookEnv.currentCateName = $(this).find('a').text();
                            bookEnv.currentCateId = $(this).attr('tabindex');
                            APIHandler.getBooksJSON(bookEnv.currentCateId);
                            BooksHandler.bindBookshelf(bookEnv.BooksByCateJSON.newbook,bookEnv.BooksByCateJSON.allbook,bookEnv.currentCateName);
                            $(this).selectMenu();
                            MotionHandler.slideOutFromLeft(selector_submenu);
                            MotionHandler.slideInFromRight(selector_bookshelf);
                        });
                        $(this).selectMenu();
                        MotionHandler.slideOutFromLeft(selector_menu);
                        MotionHandler.slideInFromRight(selector_submenu);
                    });
                }
            });

            $('.menu .cate ul').draggable({
                axis: 'y',
                stop: function(){
                    if(parseInt($(this).css('top'))>0){
                        $(this).animate({top:0});                
                    }
                    if(parseInt($(this).css('height'))+parseInt($(this).css('top')) < 960){
                        $(this).animate({top:(740-parseInt($(this).css('height')))});
                    }
                }
            });
        }
    }
}

var BooksHandler = {
    clear : function(){
        (new MsgAction('BooksHandler')).Log('clear');
        $(selector_bookshelf).find('ul').remove();
    },
    bookshelfMode : function(){
        $('#btnListMode').removeClass('selected');
        $('#btnBookshelfMode').addClass('selected');
        $(selector_bookshelf).removeClass('modelist').addClass('modebs');
        $(selector_bookshelf).find('.bs img').attr('style','');
        $(selector_bookshelf).find('.bs li').each(function(index){
            var _w = parseInt($(this).find('img').css('width'));
            var _h = parseInt($(this).find('img').css('height'));
            if(_w<_h){
                $(this).find('img').css({height:200});
                $(this).find('.cover > div').css({height:200, width:parseInt($(this).find('img').css('width'))});
            }else{
                $(this).find('img').css({width:200});
                $(this).find('.cover > div').css({height:parseInt($(this).find('img').css('height')), width:200});
            }
 
           //書本置中(使用left)
            //var _left = (140-parseInt($(this).find('img').css('width')))/2-5;
            //if(!isNaN(_left)){
            //    (new MsgAction('BooksHandler')).Log('bookshelfMode: _w:'+_w+';_left'+_left);
            //    $(this).find('.cover').css('left',_left+'px');
            //}
            //書本置中(使用left)
            var _left = parseInt($(this).find('.cover').css('width')) - parseInt($(this).find('img').css('width'));
            if(!isNaN(_left)){
		(new MsgAction('BooksHandler')).Log('bookshelfMode: _w:'+_w+';_left'+_left);
		$(this).find('.cover').css('left',parseInt(_left/2)+'px');
            }
        });
        //設定scrolltop
        $(selector_bookshelf).find('.bs').css({top:0});
    },
    listMode : function(){
        //將正在跑馬燈的項目取消跑馬燈
        var _marquee=$('.bs > ul > li > div.title > marquee').text();
        if(_marquee!=''){
            $('.bs > ul > li > div.title > marquee').parent().html('<div>'+_marquee+'</div>');
        }
    
        $('#btnListMode').addClass('selected');
        $('#btnBookshelfMode').removeClass('selected');
        $(selector_bookshelf).removeClass('modebs').addClass('modelist');

	$(selector_bookshelf).find('img').each(function(){
		if(parseInt($(this).css('height'))>parseInt($(this).css('width'))){
		        $(this).attr('style','').css({height:90});
		}else{
		        $(this).attr('style','').css({width:120});
		}	
	});

        //設定scrolltop
        $(selector_bookshelf).find('.bs').css({top:0});
    },
    doPreSet : function(books){
        (new MsgAction('BooksHandler')).Log('doPreSet');
        if((books=='') || isNaN(books)) return;

        var imgCover = new Array(books.length);
        var obj;
        for(i=0;i<books.length;i++){
            objImg = new Image();
            obj = new Object();  
            obj.img = objImg;
            imgCover[i] = obj;            
            imgCover[i].img.src=books.f_path;
            imgCover[i].img.tabindex = i;
            imgCover[i].img.onerror = function(){
                this.src='images/noimage.png';
            }
            imgCover[i].img.onload = function(){
                imgCover[this.tabindex].isVertical = (imgCover[this.tabindex].img.width<imgCover[this.tabindex].img.height);
            }
        }
        return imgCover;
    },
    bindBookshelf : function(new_books,old_books,catename){
        (new MsgAction('BooksHandler')).Log('bindBookshelf');
        this.clear();

        this.doSetBooks(new_books,0,catename);
        this.doSetBooks(old_books,1,catename);
        this.doSetBooks([],2,catename);
        BooksHandler.bookshelfMode();

    },
    // books: books json;
    // book_type: 0新書區 1全部 2補滿書櫃最少層數4層
    doSetBooks : function(books,book_type,catename){
        var _msgHandler = new MsgAction('BooksHandler');
        _msgHandler.Log('doSetBooks');
        var bs_num = $(selector_bookshelf).find('.bs ul').length;
        if(book_type==2){
            var _catename = (catename=='')?'':'| '+catename;
            if(bookEnv.currentMode()=='bs'){
                for(i=bs_num;i<3;i++){
                    $(selector_bookshelf).find('.bs').append('<ul class="mid"><li></li><li></li></ul>');
                }
            }else{
                for(i=bs_num;i<5;i++){
                    $(selector_bookshelf).find('.bs').append('<ul class="mid"><li></li><li></li></ul>');
                }
            }
            $(selector_bookshelf).find('.bs').append('<ul class="mid"><li></li><li></li></ul>');
        }

        if((books=='') || (books==null)) return;

        for(i=0;i<(books.length/2);i++){
            $(selector_bookshelf).find('.bs').append('<ul class="mid"></ul>');

            for(j=i*2;j<((i*2+2<books.length)?i*2+2:books.length);j++){
                //顯示書
                _msgHandler.Log('doSetBooks. Put in stock.');
                var $book = $(tplBook).clone();
                $(selector_bookshelf).find('.bs ul').eq(bs_num+i).append($book);
                $book.find('img').attr('src',books[j].f_path);
		$book.find('.title div').text(books[j].b_name);
                $book.find('input').val(books[j].b_id);
                _msgHandler.Log('doSetBooks. Set book event.');
                $book.find('img').load(function(){
                    var _w = this.width;
                    var _h = this.height;
                    if(bookEnv.currentMode()=='bs'){
                        if(_w<_h){
                            $(this).css({height:200, width:''});
                            $(this).prev().css({height:200, width:this.width});
                        }else{
                            $(this).css({height:'',width:200});
                            $(this).prev().css({height:this.height, width:200});
                        }
                    }else{
			if(_w<_h){
	                        $(this).css({height:90, width:''});
        	                $(this).prev().css({height:90, width:this.width});
			}else{
	                        $(this).css({height:'', width:120});
        	                $(this).prev().css({height:this.height, width:120});
			}
                    }
                    _msgHandler.Log('doSetBooks. Image "'+this.src+'" loaded. h:'+$(this).css('height')+', w:'+$(this).css('width')+'.');
                });
                $book.find('img').error(function(){
                    $(this).attr('src','images/noimage.png');
                });
                //按下封面開起更多細節視窗
                $book.find('.cover > div, .cover > img').click(function(){
                    MotionHandler.showLoading();
                    bookEnv.currentBookId = $(this).parent().children('input').val();
                    BooksHandler.showDetail(bookEnv.currentBookId);
                    setTimeout(function(){MotionHandler.hideLoading()},1000);
                });
                $book.find('img').load(function(){
                    //書本置中(使用left)
                    var _left = parseInt($(this).parent().css('width')) - parseInt($(this).css('width'));
                    if(!isNaN(_left)){
                        $(this).parent().css('left',parseInt(_left/2)+'px');
                    }
		});
            }
        }

        //選擇搜尋結果，書的標題過長以跑馬燈顯示
        $(selector_bookshelf).find('.bs > ul > li').mousedown(function(){
            var _marquee=$('.bs > ul > li > div.title > marquee').text();
            if(_marquee!=''){
                $('.bs > ul > li > div.title > marquee').parent().html('<div>'+_marquee+'</div>');
            }
            var _w=$(this).find('div.title > div').text();
            $(this).find('div.title').html('<marquee>' +_w+ '</marquee>');
        }); 
    },
    openBookWithAuth : function(){
        (new MsgAction('BooksHandler')).Log('openBookWithAuth');
        //驗證cline端是否有紀錄帳號密碼
        if(ToolsHandler.getCookie('name')==''){
            bookEnv.loginAndOpenBook=true;
            //開啟login視窗
            $(selector_bookshelf).find('#btnLogin').click();
            return;
        }else{
            _pid = ToolsHandler.getCookie('name');
            _pwd = ToolsHandler.getCookie('pwd');
            //跟主機端驗證帳密
            LoginHandler.loginAndOpenBook(_pid,_pwd);
        }
    },
    openBook : function(bookurl){
        (new MsgAction('BooksHandler')).Log('openBook');
        if((bookurl=='') || (bookurl==null)){
            alert('沒有連結!');
            return;
        }

        bookEnv.requestCode = '200';
        bookEnv.requestMsg = ToolsHandler.formatURL(bookurl);

        ToolsHandler.MM_openBrWindow(bookEnv.requestMsg,"livebook",1010,655);
        APIHandler.logBookView(bookEnv.currentBookId, null);
    },
    showDetail : function(_id){
        (new MsgAction('BooksHandler')).Log('showDetail');
        bookEnv.currentBookId = _id;
        $('#bs_detail').show();
	$('.search input').blur();

        APIHandler.getBookDetailJSON(_id);
        //設定書本細節視窗的內容
        if(bookEnv.BooksDetailJSON!=null){
            $(selector_book_content).find('h3').text(ToolsHandler.isNull(bookEnv.BooksDetailJSON.b_name));
            $(selector_book_content).find('img').attr({'src':ToolsHandler.isNull(bookEnv.BooksDetailJSON.f_path), style:''});
            $(selector_book_content).find('img').load(function(){
                var _w = this.width;
                var _h = this.height;
                if(_w<_h){
                    $(selector_book_content).find('img').css({height:'',width:200});
                }else{
                    $(selector_book_content).find('img').css({height:200,width:''});
                }
                var _center_img = 320-parseInt($(selector_book_content).find('img').css('width'))/2;
                $(selector_book_content).find('img').css({'margin-left':_center_img});
                $(selector_book_content).find('.spine').css({'margin-left':_center_img});
                $(selector_book_content).find('.spine').css({height: $(selector_book_content).find('img').css('height')});
                
            });
            $(selector_book_content).find('.content ul li').html(ToolsHandler.isNull(bookEnv.BooksDetailJSON.b_description).replace('\n','<br />'));
            $(selector_book_content).find('input').val(ToolsHandler.isNull(bookEnv.BooksDetailJSON.b_id));
        }
        
       
    }
}

var BooksAction = function(){
    var _msgHandler = new MsgAction('BooksAction');
    Init();
    _msgHandler.Log('Loaded!!!');
    
    function Init(){
        bookEnv.allbookCover = BooksHandler.doPreSet(bookEnv.BooksJSON.allbook);
        bookEnv.newbookCover = BooksHandler.doPreSet(bookEnv.BooksJSON.newbook);

        var _c=null;
        _c = MenuHandler.getFirstCate();
        switch(_c){
            case 'new':
                BooksHandler.bindBookshelf(bookEnv.BooksJSON.newbook,[],'新書區');
                MenuHandler.selectNewbook();
                break;
            case 'all':
                BooksHandler.bindBookshelf([],bookEnv.BooksJSON.allbook,'全部');
                MenuHandler.selectAllbook();
                break;
            default:
                var _cid=0;
                MenuHandler.selectFirstUserDefinedMenu();
                _cid = MenuHandler.getFirstUserDefinedMenuId();
                bookEnv.currentCateId = _cid;
                bookEnv.currentCateName = $('#menu ul li p a[tabindex='+_cid+']').text();
                APIHandler.getBooksJSON(_cid);
                bookEnv.cateCover = BooksHandler.doPreSet(bookEnv.BooksByCateJSON.allbook);
                BooksHandler.bindBookshelf(
                    bookEnv.BooksByCateJSON.newbook,
                    bookEnv.BooksByCateJSON.allbook,
                    bookEnv.currentCateName
                );
                break;
        }

        $('#btnListMode').click(function(){
            BooksHandler.listMode();
        });

        $('#btnBookshelfMode').click(function(){
            BooksHandler.bookshelfMode();
        });

        //書籍細節關閉按鈕功能
        $(selector_book_content).find('.close').click(function(){
            $(selector_dialogue_bg).click();
        });

        //書籍介紹中開啟閱讀按鈕的動作
        if(bookEnv.InitJSON.member){
             $(selector_book_content).prev().find('.button #btnIbook').click(function(){
                bookEnv.loginAndOpenBook = true;
                bookEnv.openMode='ibook';
                BooksHandler.openBookWithAuth();      
             });
             $(selector_book_content).prev().find('.button #btnWebbook').click(function(){
                bookEnv.loginAndOpenBook = true;
                bookEnv.openMode='webbook';
                BooksHandler.openBookWithAuth();
             });
         }else{
             $(selector_book_content).prev().find('.button #btnIbook').click(function(){
                bookEnv.openMode='ibook';
                BooksHandler.openBook(bookEnv.BooksDetailJSON.ibook_link);
             });
             $(selector_book_content).prev().find('.button #btnWebbook').click(function(){
                bookEnv.openMode='webbook';
                BooksHandler.openBook(bookEnv.BooksDetailJSON.webbook_link);
             });
         }
    }

}

var SearchHandler = {
    isSetSearchEntity : ($(selector_bookshelf).find('ul:first > li:first > div.search').length>0),
    setSearchEntity : function(){
        (new MsgAction('SearchHandler')).Log('setSearchEntity');
        if(!this.isSetSearchEntity){
            $(selector_bookshelf).find('ul:first > li:first').append('<div class="search"><input type="text" /></div>');
            this.initInput();
            new SearchAction();
        }
    },
    clear : function(){
        (new MsgAction('SearchHandler')).Log('clear');
        //刪除之前的搜尋結果
        $(selector_searchResult).find('ul').remove();
    },
    initInput : function(){
        (new MsgAction('SearchHandler')).Log('initInput');
        $('.search input').val('搜尋');
    },
    setResultInfo : function(n){
        (new MsgAction('SearchHandler')).Log('setResultInfo');
        $('#button .result_info span').text(n);
    },
    doSearch : function(books,book_type){
        var _msgHandler = new MsgAction('SearchHandler');
        _msgHandler.Log('doSearch');
        $('.search input').val(bookEnv.searchKeyword);
        
        BooksHandler.doSetBooks(books,book_type,'搜尋');
    },
    bindSearch : function(){
        (new MsgAction('SearchHandler')).Log('bindSearch');
        this.clear();
        APIHandler.getSearchBooksJSON(bookEnv.searchKeyword);
        this.doSearch(bookEnv.BooksBySearchJSON,1,'搜尋');
        this.doSearch([],2,'搜尋');
        MenuHandler.unselect();
        BooksHandler.listMode();

        //設定scrolltop
        $(selector_bookshelf).find('.bs').css({top:0});
    }
}

var SearchAction = function(){
    var _msgHandler = new MsgAction('SearchAction');
    Init();
    _msgHandler.Log('Loaded!!!');
    
    function Init(){
        SearchHandler.initInput();

        $('.search input').click(function(){
            if($(this).val()=='搜尋'){
                $('.search input').val('');
            }
            $(this).focus();
        });
        $('.search input').blur(function(){
            if($(this).val()==''){
                $(this).val('搜尋');
            }
        });
        $('.search input').keydown(function(event){
            if(event.which==13){
                if($(this).val()!='搜尋'){
                    //設定搜尋的參數keyword
                    bookEnv.searchKeyword = $(this).val();
                    SearchHandler.bindSearch();
                }
            }
        });
    }
}

var LoginHandler = {
    login : function(_pid, _pwd){
        (new MsgAction('LoginHandler')).Log('login');
        APIHandler.login(_pid, _pwd);
        if(bookEnv.requestCode=='200'){
            this.viewLoginMode();
            
            //儲存帳密
            ToolsHandler.setCookie('name',_pid);
            ToolsHandler.setCookie('pwd',_pwd);
        }else{
            alert(bookEnv.requestMsg);
        }
        $(selector_dialogue_bg).click();
    },
    loginAndOpenBook : function(_pid,_pwd){
        (new MsgAction('LoginHandler')).Log('loginAndOpenBook');
        APIHandler.loginAndOpenBook(_pid,_pwd)
        if(bookEnv.requestCode=='200'){
            this.viewLoginMode();
            //儲存帳密
            ToolsHandler.setCookie('name',_pid);
            ToolsHandler.setCookie('pwd',_pwd);

            APIHandler.logBookView(bookEnv.currentBookId,_pid);
            
            ToolsHandler.MM_openBrWindow(bookEnv.requestMsg,"livebook",1010,655);
        }else{
            alert(bookEnv.requestMsg);
        }
        $(selector_dialogue_bg).click();
    },
    viewLoginMode : function(){
        (new MsgAction('LoginHandler')).Log('viewLoginMode');
        //切換至登出按鈕
        $('#buttonlogin').removeClass('login').addClass('logout');
    },
    viewLogoutMode : function(){
        (new MsgAction('LoginHandler')).Log('viewLogoutMode');
        //切換至登入按鈕
        $('#buttonlogin').removeClass('logout').addClass('login');
    },
    showLogin : function(){
        (new MsgAction('LoginHandler')).Log('showLogin');
        $('#login').show();
    },
    hideLogin : function(){
        (new MsgAction('LoginHandler')).Log('hideLogin');
        $('#login').hide();
    }
}

var LoginAction = function(){
    var _msgHandler = new MsgAction('LoginAction');
    Init();
    _msgHandler.Log('Loaded!!!');
    
    function Init(){
        if(bookEnv.InitJSON.member){
            //畫面設定
            //登入/登出按鈕設定
            if(ToolsHandler.getCookie('name')!=''){
                $('#buttonlogin').addClass('logout');
            }else{
                $('#buttonlogin').addClass('login');
            }
        }else{
            $('#buttonlogin').hide();
        }
        
        //按鈕功能列的登入功能開啟登入畫面
        $('#btnLogin').click(function(){
            LoginHandler.showLogin();
        });
        
        $('#login div input').click(function(){
            LoginHandler.hideLogin();        
        });

        $('#login div input').keydown(function(event){
            if(event.which==13){
                $('#login .button div').click();
            }
        });

        $('#login > div > input[type=button]').eq(1).click(function(){
            if($('#login input[type=text]').val()==''){
                alert('請輸入帳號!');
                return;
            }
            if($('#login input[type=password]').eq(1).val()==''){
                alert('請輸入密碼!');
                return;
            }
            
            //跟主機端驗證帳密
            _pid = $('#login input[type=text]').val();
            _pwd = ToolsHandler.MD5($('#login input[type=password]').val());

            //由登入註記判斷登入模式
            if(bookEnv.loginAndOpenBook){
                LoginHandler.loginAndOpenBook(
                    _pid,
                    _pwd);
            }else{
                LoginHandler.login(_pid,_pwd);
            }
        });
        
       //按鈕功能列的登出功能登出帳號
        $('.btnlogout').click(function(){
            ToolsHandler.delCookie('name');
            ToolsHandler.delCookie('pwd');
            LoginHandler.viewLogoutMode();
        });

    }
}

var DialogueHandler = {
    //對話框的半透明背景
    showMask : function(){
        (new MsgAction('DialogueHandler')).Log('showMask');
        $(selector_dialogue_bg).addClass('show');
        var _w = document.documentElement.scrollWidth;
        var _h = document.documentElement.scrollHeight;
        if(navigator.userAgent.indexOf('iPad')!=-1){
            _w+=296;
        }
        if(navigator.userAgent.indexOf('iPhone')!=-1){
            _w+=746;
        }
        $(selector_dialogue_bg).css({width:_w, height:_h});
    },
    hideMask : function(){
        (new MsgAction('DialogueHandler')).Log('hideMask');
        $(selector_dialogue_bg).removeClass('show');
        $(selector_dialogue_bg).css({width:0, height:0});
        
        bookEnv.loginAndOpenBook=false;
    }
}

var DialogueAction = function(){
    var _msgHandler = new MsgAction('DialogueAction');
    Init();
    _msgHandler.Log('Loaded!!!');
    
    function Init(){
        $(selector_dialogue_bg).click(function(){
            $('#login').removeClass('show');
            $('#forget').removeClass('show');
            $(selector_book_content).removeClass('show');
            DialogueHandler.hideMask();
        });
    }
}

var MotionHandler = {
    loadingPage : function(){
        $(selector_loading).fadeIn("slow");
        $(selector_loading).fadeOut(2000);
        $(selector_bookshelf).show();
    },
    slideInFromRight : function(p){
        $(p).css({left:640});
        $(p).animate({left:0},1000);
    },
    slideInFomLeft : function(p){
        $(p).css({left:-640});
        $(p).animate({left:0},1000);
    },
    slideOutFromRight : function(p){
        $(p).css({left:0});
        $(p).animate({left:640},1000);
    },
    slideOutFromLeft : function(p){
        $(p).css({left:0});
        $(p).animate({left:-640},1000);
    },
    showLoading : function(){
        $('#bs_loading').addClass('load');
    },
    hideLoading : function(){
        $('#bs_loading').removeClass('load');
    }
    
    
}

var MotionAction = function(){
    Init();
    function Init(){
        MotionHandler.loadingPage();

        $('#btnMenu').click(function(){
            MotionHandler.slideInFomLeft(selector_menu);
            MotionHandler.slideOutFromRight(selector_bookshelf);
        });


        var _dragtime;
        //拖曳時後製造加速度遞減的效果
        $('.bs').draggable({
            axis: 'y',
            start: function(){
                _dragtime = (new Date()).getTime();
                _dragstartpos = systemEnv.mousePosY;
            },
            stop: function(){
                var _top=(bookEnv.currentMode()=='bs')?1060:960;
                if(parseInt($(this).css('top'))>0){
                    $(this).animate({top:0});
                    return;
                }
                else if(parseInt($(this).css('height'))+parseInt($(this).css('top')) < 960){
                    $(this).animate({top:(_top-parseInt($(this).css('height')))});
                    return;
                }
                else{
                    _time=(new Date()).getTime()-_dragtime;
                    _dragendpos = systemEnv.mousePosY;
                    var _s=(_dragendpos-_dragstartpos)/_time; // 距離/時間=速度 >0:下 <0:上
                    $(this).animate({top:parseInt($(this).css('top'))+_s*300},function(){
                        if(parseInt($(this).css('top'))>0){
                            $(this).animate({top:0});
                        }
                        if(parseInt($(this).css('height'))+parseInt($(this).css('top')) < 960){
                            $(this).animate({top:(_top-parseInt($(this).css('height')))});
                        }
                    });
                }
            }
        });

        $(selector_bookshelf).draggable({
            axis: 'x',
            start: function(){
                $(selector_menu).css({left:-640});
                $(selector_bookshelf).css({left:0});
            },
            drag: function(){
                $(selector_menu).css({left:-640+$(this).offset().left});
            },
            stop: function(){
                if($(this).position().left>320){
                    $(selector_menu).animate({left:0},300);
                    $(selector_bookshelf).animate({left:640},300);
                }else{
                    $(selector_menu).animate({left:-640},300);
                    $(selector_bookshelf).animate({left:0},300);
                }
            }
        });

        $('.menu .cate ul').draggable({
            axis: 'y',
            stop: function(){
                if(parseInt($(this).css('top'))>0){
                    $(this).animate({top:0});                
                }
                var item_num = $(this).find('li').length;
                var order_list_height = (order_list_height<11)?800:(item_num * 80);
                if(parseInt($(this).css('height'))+parseInt($(this).css('top')) < order_list_height){
                    $(this).animate({top:(order_list_height-parseInt($(this).css('height')))});
                }
            }
        });
        //menu的[完成]按鈕
        $('.menu .top input').click(function(){
            $(this).parent().parent().animate({left:-640},1000);
            MotionHandler.slideInFromRight(selector_bookshelf);
        });

        $('#btnDone').click(function(){
            $('#bs_detail').hide();
            AddressHandler.removeItem();
        });
    }
}

$(document).ready(function(){
    var motion = new MotionAction();
    var api = new APIAction();
    var _m = new MenuAction();
    var _b = new BooksAction();
    var _l = new LoginAction();
    var _s = new SearchAction();
});
