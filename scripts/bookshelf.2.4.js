/**************************************************************
develop log
20160321 - recieve api response by MsgHandler
**************************************************************/
var selector_body = '#body';
var selector_header = '#header';
var selector_login = '#login';
var selector_menu = '#menu';
var selector_menu_all = '';
var selector_menu_new = '';
var selector_bookshelf = '#bookshelf';
var selector_book_content = '#book_content';
var selector_searchResult = '#searchbook';
var selector_footer = '#footer';
var tplBook = '#TemplateFrame > li:eq(0)';
var tplSearch = '#TemplateFrame > li:eq(1)';

var footer = '';
$(document).ready(function(){
    $.address.init();
});

var MenuHandler = {
    hasNode : function(){
        return ($(selector_menu).find('ul').length>0);
    },
    hasAllbook : function(){
        return $(selector_menu).is('.all');
    },
    hasNewbook : function(){
        return $(selector_menu).is('.new');
    },
    length : $(selector_menu).length,
    getFirstUserDefinedMenuId : function(){
        (new MsgAction('MenuHandler')).Log('getFirstUserDefinedMenuId');
        return $(selector_menu).find('ul:first li p:first a').attr('tabindex');
    },
    clear : function(){
        (new MsgAction('MenuHandler')).Log('clear');
        $(selector_menu).find('ul').remove();
    },
    getFirstCate : function(){
        (new MsgAction('MenuHandler')).Log('getFirstCate');
        return $(selector_menu).find('ul:first li:first').attr('class');
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
        $(selector_menu).find('ul:first li:first').addClass('selected');
        $(selector_menu).find('ul:first li:first p:first a').addClass('selected');
    },
    selectMenu : function(_cid){
        (new MsgAction('MenuHandler')).Log('selectMenu');
        $(selector_menu).find('ul li p a[tabindex=' + _cid + ']').parent().parent().addClass('selected');
        $(selector_menu).find('ul li p a[tabindex=' + _cid + ']').addClass('selected');
        $(selector_menu).find('ul li a[tabindex=' + _cid + ']').parent().addClass('selected');
        _submenu_select=(typeof MenuHandler.getSelectedSubMenuID()=='undefined');
				bookEnv.currentCateName=MenuHandler.getSelectedSubMenuText();
        if(_submenu_select){
                bookEnv.currentCateLevel=0;
                bookEnv.currentCateName=MenuHandler.getSelectedMenuText();
        }else{
                bookEnv.currentCateLevel=1;
                bookEnv.currentCateName=MenuHandler.getSelectedSubMenuText();
        }
    },
    getSelectedMenuText : function(){
        (new MsgAction('MenuHandler')).Log('getFirstUserDefinedMenu');
        return $(selector_menu).find('ul li.selected > a').text();
    },
    getSelectedSubMenuText : function(){
        (new MsgAction('MenuHandler')).Log('getFirstUserDefinedMenu');
        return $(selector_menu).find('ul li.selected p a.selected').text();
    },
    getSelectedSubMenuID : function(){
        (new MsgAction('MenuHandler')).Log('getFirstUserDefinedMenu');
        return $(selector_menu).find('ul li.selected p a.selected').attr('tabindex');
    },
    unselect : function(){
        (new MsgAction('MenuHandler')).Log('unselect');
        //設定選單的樣式為不選取
        $(selector_menu).find('.selected').removeClass('selected');
    },
    addAllbook : function(){
        var _msgHandler=new MsgAction('MenuHandler');
        _msgHandler.Log('addAllbook');
        $(selector_menu).append('<ul><li class="all"><a>'+systemEnv.Language.allbook+'</a></li></ul>');
        var _obj_menu = this;
        $(selector_menu).find('ul > li.all > a').click(function(){
          _obj_menu.unselect();
          APIHandler.getBooksJSON(0);
          bookEnv.currentCateName = systemEnv.Language.allbook;
	        var _books;
	        switch(bookEnv.bookshelfSourceMode){
				    case 'open':
					    _books = bookEnv.BooksJSON.allbook;
					    break;
				    case 'my':
					    _books = bookEnv.MyBooksJSON.allbook;
					    break;
	        }
	        BooksHandler.bindBookshelf([],_books,systemEnv.Language.allbook);
					bookEnv.viewBookshelfMode();
					AddressHandler.addCid('all');
        });
    },
    addNewbook : function(){
        var _msgHandler=new MsgAction('MenuHandler');
        _msgHandler.Log('addNewbook');
        $(selector_menu).append('<ul><li class="new"><a>'+systemEnv.Language.newbook+'</a></li></ul>');
        var _obj_menu = this;
        $(selector_menu).find('ul > li.new > a').click(function(){
					_obj_menu.unselect();
					APIHandler.getBooksJSON(0);
					bookEnv.currentCateName = systemEnv.Language.newbook;
	        var _books;
	        switch(bookEnv.bookshelfSourceMode){
				    case 'open':
					    _books = bookEnv.BooksJSON.newbook;
					    break;
				    case 'my':
					    _books = bookEnv.MyBooksJSON.newbook;
					    break;
	        }
	        BooksHandler.bindBookshelf(_books,[],systemEnv.Language.newbook);
					bookEnv.viewBookshelfMode();
					AddressHandler.addCid('new');
        });
    },
    setInit : function(){
        (new MsgAction('MenuHandler')).Log('setInit');
    },
    removeInit : function(){
        (new MsgAction('MenuHandler')).Log('removeInit');
        $(selector_menu).removeClass('init');
        $(selector_menu).html('');
    },
    doMenu : function(pre_text){
        (new MsgAction('MenuHandler')).Log('doMenu');
        for(i=0;i<bookEnv.MenuJSON.length;i++){
            $(selector_menu).append('<ul><li><a tabindex="'+bookEnv.MenuJSON[i].c_id+'" title="'+bookEnv.MenuJSON[i].c_description+'">'+pre_text+bookEnv.MenuJSON[i].c_name+'</a></li></ul>');
            if(bookEnv.MenuJSON[i].sub_category!=null)
                for(j=0;j<bookEnv.MenuJSON[i].sub_category.length;j++){
                    $(selector_menu).find('ul li:last').append('<p><a tabindex="'+bookEnv.MenuJSON[i].sub_category[j].c_id+'" title="'+bookEnv.MenuJSON[i].c_description+'">'+bookEnv.MenuJSON[i].sub_category[j].c_name+'</a></p>');
                }
        }
    }
}

var MenuAction = function(){
    var _msgHandler = new MsgAction('MenuAction');

    Init();
    _msgHandler.Log('Loaded!!!');

    function Init(){
        //選單動作
        MenuHandler.clear();
        
        if(parseInt(bookEnv.InitJSON.allbook) && !MenuHandler.hasAllbook()){
            if(!MenuHandler.hasNode()){
                MenuHandler.removeInit();
            }
            MenuHandler.addAllbook();
        }
        if(parseInt(bookEnv.InitJSON.newbook) && !MenuHandler.hasNewbook()){
            if(!MenuHandler.hasNode()){
                MenuHandler.removeInit();
            }
            MenuHandler.addNewbook();
        }

        //如果有參數item就顯示書籍詳細
        if (AddressHandler.issetItem()) {
            systemEnv.mousePosY = 300;
            BooksHandler.showDetail(AddressHandler.getItem());
        }

        if(bookEnv.MenuJSON!=null){
            if(!MenuHandler.hasNode()){
                MenuHandler.removeInit();
            }
            MenuHandler.doMenu((bookEnv.IsDefaultData)?'測試資料-':'');

            //主選單click & 樣式
            //主選單未選取 click變成選取狀態，打開次選單。其他已開啟的次選單會收合。
            //主選單已選取 click不會有任何動作
            $(selector_menu).find('ul li >  a[tabindex]').click(function(){
                if(bookEnv.bookshelfSourceMode!='my'){
	                bookEnv.currentCateName = $(this).text();
  	              bookEnv.currentCateId = $(this).attr('tabindex');
    	            bookEnv.currentCateLevel = 0;
    	          }

                $('#currentCateId').val(bookEnv.currentCateId);
                if(bookEnv.bookshelfSourceMode=='my'){
	                APIHandler.getMyBooksJSON(bookEnv.currentCateId);
                }else{
									APIHandler.getBooksJSON(bookEnv.currentCateId);
                }
                bookEnv.cateCover = BooksHandler.doPreSet(bookEnv.BooksByCateJSON.allbook);

				        var _newbooks;
				        var _allbooks;
				        _newbooks = bookEnv.BooksByCateJSON.newbook;
				        _allbooks = (bookEnv.BooksByCateJSON.oldbook)?bookEnv.BooksByCateJSON.oldbook:bookEnv.BooksByCateJSON.allbook;
                BooksHandler.bindBookshelf([],_allbooks,bookEnv.currentCateName);
                $(this).parent().find('p a').removeClass('selected');

                $selected_submenu = $(this).parent().find('p a.selected');
                MenuHandler.unselect();
                if(!$(this).parent().is('.selected')){
                    $(this).parent().addClass('selected');
                    $selected_submenu.addClass('selected');
                }
                $(this).blur();
                
                if($(this).parent().attr('class').indexOf('new')>=0){
                    AddressHandler.addCid('new');
                }else if($(this).parent().attr('class').indexOf('all')>=0){
                    AddressHandler.addCid('all');
                }else{
                    AddressHandler.addCid(bookEnv.currentCateId);
                }
            }); 

            //次選單click & 樣式
            $(selector_menu).find('ul li p').click(function(){
            		if(bookEnv.bookshelfSourceMode!='my'){
	                bookEnv.currentCateName = $(this).find('a').text();
  	              bookEnv.currentCateId = $(this).find('a').attr('tabindex');
    	            bookEnv.currentCateLevel = 1;
              	}

                $('#currentCateId').val(bookEnv.currentCateId);
                if(bookEnv.bookshelfSourceMode=='my'){
	                APIHandler.getMyBooksJSON(bookEnv.currentCateId);
                }else{
                	APIHandler.getBooksJSON(bookEnv.currentCateId);
                }
                bookEnv.cateCover = BooksHandler.doPreSet(bookEnv.BooksByCateJSON.allbook);
								var _newbooks;
								var _allbooks;
								_newbooks = bookEnv.BooksByCateJSON.newbook;
								_allbooks = (bookEnv.BooksByCateJSON.oldbook)?bookEnv.BooksByCateJSON.oldbook:bookEnv.BooksByCateJSON.allbook;
                BooksHandler.bindBookshelf([],_allbooks,bookEnv.currentCateName);
                $(this).parent().find('a').removeClass('selected');
                $(this).children('a').addClass('selected');

                bookEnv.viewBookshelfMode();

                $(this).children('a').blur();

                //設定分類的$.address('cid')
                AddressHandler.addCid(bookEnv.currentCateId);
            });

            //如果有參數cid就切換到該分類
            if (AddressHandler.issetCid()) {
                var _cid = AddressHandler.getCid();
                switch(_cid){
                    case 'all':
                        MenuHandler.selectAllbook();
                        break;
                    case 'new':
                        MenuHandler.selectNewbook();
                        break;
                    default:
                        MenuHandler.selectMenu(_cid);
                        break;
                }
            }

            //如果有參數k
            if(AddressHandler.issetK()){
                bookEnv.searchKeyword = AddressHandler.getK();
                SearchHandler.doSearch();
            }
        }
    }
}

var BooksHandler = {
    mode : function(){
        return ($('#body').is('.mode_search'))?'search':'bookshelf';
    },
    clear : function(){
        (new MsgAction('BooksHandler')).Log('clear');
        this.defaultBookshelfLayer();
        $(selector_bookshelf).find('ul').remove();
    },
    defaultBookshelfLayer : function(){
        (new MsgAction('BooksHandler')).Log('defaultBookshelfLayer');
        if(systemEnv.defaultBookshelfLayer==-1){
        	systemEnv.defaultBookshelfLayer = $(selector_bookshelf).find('ul').length;
        }
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
                //this.src='images/noimage.png';
            }
            imgCover[i].img.onload = function(){
                imgCover[this.tabindex].isOverWidth = (this.width>120);
                imgCover[this.tabindex].imgWidth = imgCover[this.tabindex].img.width;
            }
        }
        return imgCover;
    },
    bindBookshelf : function(new_books,old_books,catename){
        var _msgHandler = new MsgAction('BooksHandler')
        _msgHandler.Log('bindBookshelf');
        this.clear();

        this.doSetBooks(new_books,0,catename);
        this.doSetBooks(old_books,1,catename);
        this.doSetBooks([],2,catename);
        SearchHandler.setSearchEntity()

        //重新設定書籍的邊線長寬
        $(selector_bookshelf).find('ul.cover img').load(function(){
            $(this).prev().css('width',$(this).css('width'));
            $(this).prev().css('height',$(this).css('height'));
        });
    },
    doSetBooks : function(books,book_type,catename){
        var _msgHandler = new MsgAction('BooksHandler');
        _msgHandler.Log('doSetBooks');

        var done=false;
        var _index=0;
        var _catename='';
        var _new_row = false;
        
        _msgHandler.Log('book_type='+book_type+';books='+books+';catename='+catename);

        bs_row_num = $(selector_bookshelf).children('ul').length;
        if(book_type==2){
					var _catename = (catename=='')?'':'| '+catename;
					for(i=bs_row_num;i<systemEnv.defaultBookshelfLayer;i++){
						if(bs_row_num==0 && i==0){
							$(selector_bookshelf).append('<ul class="top"><li class="allbook"><div class="cate">'+_catename+'</div></li></ul>');
						}else{
							$(selector_bookshelf).append('<ul class="mid"></ul>');
						}
					}
        }else if((books!='') && (books!=null)){
					var pretext = (bookEnv.bookshelfSourceMode=='my')?systemEnv.Language.mybs+'-':'';
					if(books.length>0){
						if(catename){
							_catename = '| '+pretext+catename;
						}else{
							_catename = '| '+pretext+((!books[0].c_name)?systemEnv.Language.uncatagorized:books[0].c_name);
						}
					}
        }

        if((books=='') || (books==null)) return;

        if(bs_row_num==0){
            $(selector_bookshelf).append('<ul class="top"><li class="allbook"><div class="cate">'+_catename+'</div></li></ul>');
        }

        while(_index<books.length){
            _msgHandler.Log('index='+_index+';length='+books.length+';bs_row_num='+bs_row_num);
            if(_index>3000) break;
            
            bs_row_num      = $(selector_bookshelf).children('ul').length;
            bs_row_book_num = $(selector_bookshelf).children('ul').eq(bs_row_num-1).children('li').length;

            _catename='';
            switch(book_type){
                case 0:
                    if(bs_row_book_num==6) _new_row=true;
                    break;
                case 1:
                    if(bs_row_book_num==6) _new_row=true;
                    if(_index>0)
                        if(books[_index].c_id!=books[_index-1].c_id){
                            _new_row=true;
                            _catename = '| '+books[_index].c_name;
                        }
                    break;
            }
            if(_new_row){
                $(selector_bookshelf).append('<ul class="mid"><li><div class="cate">'+_catename+'</div></li></ul>');
                bs_row_num++;
                _new_row=false;
            }

            //顯示書
            _msgHandler.Log('bs_row_num='+bs_row_num+';bs_row_book_num='+bs_row_book_num);
            _msgHandler.Log('doSetBooks. Put in stock.');
            var $book = $(tplBook).clone();
            $(selector_bookshelf).children('ul').eq(bs_row_num-1).append($book);
            $book.find('img').attr('src',books[_index].f_path);
				    if(books[_index].is_read>0){
				        $book.find('span').addClass('style'+(Math.round(Math.random())+1));
				    }
            $book.find('li > input').val(books[_index].b_id);
            $book.find('li > h3').text('│'+books[_index].b_name);

            if(bookEnv.InitJSON.member){
                _msgHandler.Log('doSetBooks. Set view of member mode.');
                //會員模式書籍的按鈕設定
                if(!books[_index].webbook_show && !books[_index].ibook_show){
                    $book.find('.btn').remove();
                }else if(books[_index].webbook_show==0){
                    $book.find('.btn li:nth-child(2)').remove();
                    $book.find('.btn li:nth-child(1)').remove();
                }else if(books[_index].ibook_show==0){
                    $book.find('.btn li:nth-child(3)').remove();
                    $book.find('.btn li:nth-child(2)').remove();
                }

                $book.find('.webbook a').click(function(){
                    bookEnv.loginAndOpenBook = true;
                    bookEnv.currentBookId = $(this).parent().parent().prev().find('input').val();
                    $('#currentBookId').val(bookEnv.currentBookId);
                    bookEnv.openMode = 'webbook';
                    BooksHandler.openBookWithAuth();
                });
                $book.find('.ibook a').click(function(){
                    bookEnv.loginAndOpenBook = true;
                    bookEnv.currentBookId = $(this).parent().parent().prev().find('input').val();
                    $('#currentBookId').val(bookEnv.currentBookId);
                    bookEnv.openMode = 'ibook';
                    BooksHandler.openBookWithAuth();
                });
            }else{
                _msgHandler.Log('doSetBooks. Set view of non-member mode.');
                //非會員模式書籍的按鈕設定
                //使用連結判斷是否有出現按鈕，如果後台未設定連結位置一樣不會出現按鈕
                if((books[_index].webbook_link=="") && (books[_index].ibook_link=="")){
                    $book.find('.btn').remove();
                }else if(books[_index].webbook_show==0){
                    $book.find('.btn li:nth-child(2)').remove();
                    $book.find('.btn li:nth-child(1)').remove();
                }else if(books[_index].ibook_show==0){
                    $book.find('.btn li:nth-child(3)').remove();
                    $book.find('.btn li:nth-child(2)').remove();
                }
                
                var ibook_link = books[_index].ibook_link.toString();
                var webbook_link = books[_index].webbook_link.toString();
              	$book.find('.webbook a').attr('href','javascript:BooksHandler.openBook("'+webbook_link+'");');
              	$book.find('.webbook a').click(function(){
                  bookEnv.currentBookId = $(this).parent().parent().prev().find('input').val();
                  $('#currentBookId').val(bookEnv.currentBookId);
                  bookEnv.openMode = 'webbook';
              	});

              	$book.find('.ibook a').attr('href','javascript:BooksHandler.openBook("'+ibook_link+'");');
              	$book.find('.ibook a').click(function(){
                  bookEnv.currentBookId = $(this).parent().parent().prev().find('input').val();
                  $('#currentBookId').val(bookEnv.currentBookId);
                  bookEnv.openMode = 'ibook';
              	});
            }

            _msgHandler.Log('doSetBooks. Set book event.');
            $book.find('img').error(function(){
                //$(this).attr('src','images/noimage.png');
            });
            //按下封面開起更多細節視窗
            $book.find('.cover > li > div > div').click(function(){
                bookEnv.currentBookId = $(this).parent().parent().children('input').val();
                $('#currentBookId').val(bookEnv.currentBookId);
                BooksHandler.showDetail(bookEnv.currentBookId);
            });
            $book.find('.cover > li > div > div').mouseenter(function(){
                bookEnv.openBookRemark=true;
            });
            $book.find('.cover > li > div > div').mouseleave(function(){
                bookEnv.openBookRemark=false;
            });
            $book.find('.cover > li > h3').mouseenter(function(){
                bookEnv.openBookRemark=true;
            });
            $book.find('.cover > li > h3').mouseleave(function(){
                bookEnv.openBookRemark=false;
            });                

            //書本置中(使用left)
            $book.find('img').load(function(){
								var height = parseInt($(this).css('height'));
                var left = parseInt($(this).parent().css('width')) - parseInt($(this).css('width'));
								$(this).parent().find('span').attr('style','bottom:'+(height-1)+'px');
                if(!isNaN(left)){
                    $(this).parent().css('left',parseInt(left/2)+'px')
                }
            });


            _index++;
        }
    },
    showRemark : function(){
        $(selector_bookshelf).find('.cover > li > h3').show().animate({height:55, 'margin-top':85, 'font-size':13, 'padding-left':3, 'padding-right':3, width:120},100)
                                                    .animate({height:48, 'margin-top':92},10)
                                                    .animate({height:50, 'margin-top':90},5);
    },
    hideRemark : function(){
        $(selector_bookshelf).find('.cover > li > h3').animate({height:0, 'margin-top':140, 'font-size':0, 'padding-left':0, 'padding-right':0, width:126},100)
                                                    .animate({height:5, 'margin-top':137},10)
                                                    .animate({height:0, 'margin-top':140},10)
    },
    doRemark : function(){
        var isShow = (parseInt($(selector_bookshelf).find('.cover > li > h3:first').css('height'))>0)
        if(bookEnv.openBookRemark){
            if(!isShow) BooksHandler.showRemark();
        }else{
            if(isShow) BooksHandler.hideRemark();
        }
    },
    openBookWithAuth : function(){
        (new MsgAction('BooksHandler')).Log('openBookWithAuth');
        //驗證cline端是否有紀錄帳號密碼
				APIHandler.loginCheck(function(data){
					switch(data.type){
						case 'a':
						case 'u':
							//_pid = ToolsHandler.getCookie('name');
							//_pwd = ToolsHandler.getCookie('pwd');
							if(data.id>0){
								_pid = bookEnv.pid;
								_pwd = bookEnv.pwd;
							  //跟主機端驗證帳密
							  LoginHandler.loginAndOpenBook(_pid,_pwd,function(){});
							}
							break;
						case '-':
						default:
							bookEnv.loginAndOpenBook=true;
							//開啟login視窗
							$('#button .btnlogin').click();
							return;
							break;
					}
				});
    },
    openBook : function(bookurl){
        (new MsgAction('BooksHandler')).Log('openBook');
        if((bookurl=='') || (bookurl==null)){
            alert('沒有連結!');
            return;
        }

        _val = ToolsHandler.formatURL(bookurl);
        if(_val===false){
        	alert('Open book error! Make sure URL is correct!');
        }else if(_val===null){
        	//if null, do nothing
        }else{
	        bookEnv.requestCode = '200';
  	      bookEnv.requestMsg = _val.url;

	        ToolsHandler.MM_openBrWindow(bookEnv.requestMsg,"_blank",1010,655);
  	      APIHandler.logBookView(bookEnv.currentBookId, bookEnv.pid, bookEnv.openMode);
  	    }
    },
    showDetail : function(_id){
        (new MsgAction('BooksHandler')).Log('showDetail');
        bookEnv.currentBookId = _id;
        $('#currentBookId').val(bookEnv.currentBookId);
        //設定分類的$.address('item')
        if (!AddressHandler.issetItem) {
            AddressHandler.addItem(_id, true);
        } else {
            AddressHandler.addItem(_id, false);
        }

        DialogueHandler.showMask();
        
        //設定開啟時於視窗中間顯示
        DialogueHandler.center(selector_book_content);
        
        //設定書本細節視窗的內容
        APIHandler.getBookDetailJSON(_id,function(data){
        	if(bookEnv.BooksDetailJSON!=null){
            $(selector_book_content).find('.booktitle span').text(ToolsHandler.isNull(bookEnv.BooksDetailJSON.b_name));
            $(selector_book_content).find('.bookimg img').attr('src',ToolsHandler.isNull(bookEnv.BooksDetailJSON.f_path));
            $(selector_book_content).find('.bookcontent').html(ToolsHandler.isNull(bookEnv.BooksDetailJSON.b_description));
            $(selector_book_content).find('input').val(ToolsHandler.isNull(bookEnv.BooksDetailJSON.b_id));

            $(selector_book_content).find('.button .ibook').hide();
            $(selector_book_content).find('.button .webbook').hide();
            if(bookEnv.InitJSON.member){
                if(bookEnv.BooksDetailJSON.ibook_show=="1"){ $(selector_book_content).find('.button .ibook').show(); }
                if(bookEnv.BooksDetailJSON.webbook_show=="1"){ $(selector_book_content).find('.button .webbook').show(); }
            }else{
                if(bookEnv.BooksDetailJSON.ibook_show=="1"){ 
                    $(selector_book_content).find('.button .ibook').show();
                    $(selector_book_content).find('.button .ibook a').attr('href','javascript:BooksHandler.openBook("'+bookEnv.BooksDetailJSON.ibook_link+'");');
                }
                if(bookEnv.BooksDetailJSON.webbook_show=="1"){ 
                    $(selector_book_content).find('.button .webbook').show();
                    $(selector_book_content).find('.button .webbook a').attr('href','javascript:BooksHandler.openBook("'+bookEnv.BooksDetailJSON.webbook_link+'");');
                }
            }
            
            if($.fn.GiantviewChat){
	            $(selector_book_content).find('.button .send').GiantviewChat({
	            	image:ToolsHandler.isNull(bookEnv.BooksDetailJSON.f_path)
	            });
          	}

            //書籍細節關閉按鈕功能
            $(selector_book_content).find('.close').click(function(){
                $(selector_dialogue_bg).click();
                //移除item參數
                AddressHandler.removeItem();
            });
        	}
        
        	$(selector_book_content).addClass('show');
        });
       
    },
    viewOpenBsMode : function(){
        (new MsgAction('BooksHandler')).Log('viewOpenBsMode');
        //切換至登出按鈕
        $('#buttonbs').removeClass('my').addClass('open');
    },
    viewMyBsMode : function(){
        (new MsgAction('BooksHandler')).Log('viewMyBsMode');
        //切換至登入按鈕
        $('#buttonbs').removeClass('open').addClass('my');
    }
}

var BooksAction = function(){
    var _msgHandler = new MsgAction('BooksAction');
    if(typeof _BookshelfSourceMode!='undefined'){
    	if(_BookshelfSourceMode=='my'){
    		bookEnv.bookshelfSourceMode = 'my';
	}
    }
    Init();
    _msgHandler.Log('Loaded!!!');
    
    function Init(){
        //bookEnv.allbookCover = BooksHandler.doPreSet(bookEnv.BooksJSON.allbook);
        //bookEnv.newbookCover = BooksHandler.doPreSet(bookEnv.BooksJSON.newbook);

        var _c=null;

        //如果網址有參數以網址優先，否則就取menu中的第一個項目
        if(AddressHandler.issetK()){
            bookEnv.searchKeyword = AddressHandler.getK();
            SearchHandler.doSearch();
            return;
        }

        _msgHandler.Log('BooksAction bookEnv.bookshelfSourceMode='+bookEnv.bookshelfSourceMode);


        if(!AddressHandler.issetCid()){
            _c = MenuHandler.getFirstCate();
        }else{
            _c = AddressHandler.getCid();
        }
        switch(_c){
            case 'new':
                if(!AddressHandler.issetK()){
                    MenuHandler.selectNewbook();
                }
                var _pretext='';
                if(bookEnv.bookshelfSourceMode=='my'){
                    APIHandler.getMyBooksJSON();
                    _pretext = systemEnv.Language.mybs+'-';
                }else{
                    APIHandler.getBooksJSON();
                    _pretext = '';
                }
                BooksHandler.bindBookshelf(bookEnv.BooksJSON.newbook,[],_pretext+systemEnv.Language.newbook);
                break;
            case 'all':
                if(!AddressHandler.issetK()){
                    MenuHandler.selectAllbook();
                }
                var _pretext='';
                if(bookEnv.bookshelfSourceMode=='my'){
                    APIHandler.getMyBooksJSON();
                    _pretext = systemEnv.Language.mybs+'-';
                }else{
                    APIHandler.getBooksJSON();
                    _pretext = '';
                }
                BooksHandler.bindBookshelf([],bookEnv.BooksJSON.allbook,_pretext+systemEnv.Language.allbook);
                break;
            default:
                var _cid=0;
                if(!AddressHandler.issetK() && !AddressHandler.issetCid()){
                    MenuHandler.selectFirstUserDefinedMenu();
                    _cid = MenuHandler.getFirstUserDefinedMenuId();
                    AddressHandler.addCid(_cid);
                }else{
                    _cid = AddressHandler.getCid();
                }
                bookEnv.currentCateId = _cid;
                $('#currentCateId').val(bookEnv.currentCateId);
                var _pretext='';
                if(bookEnv.bookshelfSourceMode=='my'){
                    APIHandler.getMyBooksJSON(_cid);
                    _pretext = systemEnv.Language.mybs+'-';
                }else{
                    APIHandler.getBooksJSON(_cid);
                    _pretext = '';
                }
//alert(JSON.stringify(bookEnv.BooksByCateJSON.allbook));
                BooksHandler.bindBookshelf(
                    bookEnv.BooksByCateJSON.newbook,
                    bookEnv.BooksByCateJSON.allbook,
                    _pretext+$('#menu ul li p a[tabindex='+_cid+']').text()
                );
                break;
        }

        //書籍介紹中開啟閱讀按鈕的動作
        if(bookEnv.InitJSON.member){
             $(selector_book_content).find('.button .ibook').click(function(){
                bookEnv.loginAndOpenBook = true;
                bookEnv.openMode='ibook';
                BooksHandler.openBookWithAuth();      
             });
             $(selector_book_content).find('.button .webbook').click(function(){
                bookEnv.loginAndOpenBook = true;
                bookEnv.openMode='webbook';
                BooksHandler.openBookWithAuth();
             });
         }else{
             $(selector_book_content).find('.button .ibook a').click(function(){
                bookEnv.currentBookId = $(this).parent().parent().next().val();
                $('#currentBookId').val(bookEnv.currentBookId);
                bookEnv.openMode='ibook';
             });
             $(selector_book_content).find('.button .webbook a').click(function(){
                bookEnv.currentBookId = $(this).parent().parent().next().val();
                $('#currentBookId').val(bookEnv.currentBookId);
                bookEnv.openMode='webbook';
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
        $(selector_searchResult).find('ul > li').remove();
    },
    initInput : function(){
        (new MsgAction('SearchHandler')).Log('initInput');
        $('.search input').val(systemEnv.Language.searchtxt);
    },
    setResultInfo : function(n){
        (new MsgAction('SearchHandler')).Log('setResultInfo');
        $('#button .result_info').html(systemEnv.Language.searchresulttxt.replace('%num','<span>'+n+'</span>'))
    },
    doSearch : function(){
        (new MsgAction('SearchHandler')).Log('doSearch');
        $('.search input').val(bookEnv.searchKeyword);
        APIHandler.getSearchBooksJSON(bookEnv.searchKeyword,function(data){
	        SearchHandler.bindSearch(data);
	        bookEnv.viewSearchMode();
	        MenuHandler.unselect();
	        //設定scrolltop
	        $(selector_searchResult).find('ul').scrollTop(0);        
        });
    },
    bindSearch : function(data){
        (new MsgAction('SearchHandler')).Log('bindSearch');
        this.clear();
        this.setResultInfo(data.length);
        for(i=0;i<data.length;i++){
            var $result = $(tplSearch).clone();
            $result.find('.bookimg > img').attr('src',data[i].f_path);
            $result.find('.bookimg > img').error(function(){
                //$(this).attr('src','images/noimage.png');
            });
            $result.find('.booktitle').text(data[i].b_name);
            $result.find('.bookcontent').html(ToolsHandler.isNull(data[i].b_description));
            $result.find('input').val(data[i].b_id);
            $result.find('.button div').text(systemEnv.Language.search_btn_text);
            $result.find('.button div').click(function(){
                var _id = $(this).parent().next().val();
                BooksHandler.showDetail(_id);
            });
            $(selector_searchResult).find(' ul').append($result);
        }
    }
}

var SearchAction = function(){
    var _msgHandler = new MsgAction('SearchAction');
    Init();
    _msgHandler.Log('Loaded!!!');
    
    function Init(){
        $('.search input').click(function(){
            if($('.search input').val()==systemEnv.Language.searchtxt){
                $('.search input').val('');
            }
        });
        $('.search input').blur(function(){
            if($('.search input').val()==''){
                $('.search input').val(systemEnv.Language.searchtxt);
            }
        });
        $('.search input').keydown(function(event){
            if(event.which==13){
                if($(this).val()!=systemEnv.Language.searchtxt){
                    bookEnv.currentCateName = '';
                    bookEnv.currentCateId = 0;
                    $('#currentCateId').val(bookEnv.currentCateId);
                    //設定搜尋的參數keyword
                    bookEnv.searchKeyword = $(this).val();
                    AddressHandler.addK(bookEnv.searchKeyword);
                    SearchHandler.doSearch();
                }
            }
        });
    }
}

var login_callbacks = $.Callbacks();
var logout_callbacks = $.Callbacks();
var LoginHandler = {
    login : function(_pid, _pwd, returnFunc){
        (new MsgAction('LoginHandler')).Log('login');
        APIHandler.login(_pid,_pwd,function(){
	        if(APIHandler.isSuccess(bookEnv.requestCode)){
            LoginHandler.viewLoginMode();
            
            //儲存帳密
            //ToolsHandler.setCookie('name',_pid);
            //ToolsHandler.setCookie('pwd',_pwd);
            bookEnv.pid = _pid;
            bookEnv.pwd = _pwd;

						TemplateHandler.attachHeader_userlogin();
						if(typeof webChat=='object'){
							GVHandler.login();
							GVHandler.bindGiantview();
						}
						returnFunc();
	        }else{
						alert(bookEnv.requestMsg);
	        }
        });
        $(selector_dialogue_bg).click();
    },
    loginAndOpenBook : function(_pid,_pwd, returnFunc){
        (new MsgAction('LoginHandler')).Log('loginAndOpenBook');
        APIHandler.loginAndOpenBook(_pid,_pwd,function(data){
	        if(APIHandler.isSuccess(bookEnv.requestCode)){
		  			//APIHandler.getInitJSON();
	          LoginHandler.viewLoginMode();
	
	          //儲存帳密
	          //ToolsHandler.setCookie('name',_pid);
	          //ToolsHandler.setCookie('pwd',_pwd);
						bookEnv.pid = _pid;
						bookEnv.pwd = _pwd;
	
	          TemplateHandler.attachHeader_userlogin();
	          if(typeof webChat=='object'){
		          GVHandler.login();
		          GVHandler.bindGiantview();
		        }
		        BooksHandler.openBook(bookEnv.requestMsg);
	          returnFunc();
	        }else{
	        	APIHandler.loginCheck(function(data1){
	        		switch((data1.type)){
	        			case 'a':
	        				LoginHandler.viewLoginMode();
	        				TemplateHandler.attachHeader_adminlogin();
	        			case 'u':
			        		LoginHandler.viewLoginMode();
			        		TemplateHandler.attachHeader_userlogin();
		        			break;
		        	}
	        	});
	      		alert(data.msg);
	        }
        });
        $(selector_dialogue_bg).click();
    },
    logout : function(){
				APIHandler.logout();
				if(typeof webChat=='object') GVHandler.logout();
				bookEnv.pid='';
				bookEnv.pwd='';
				LoginHandler.viewLogoutMode();
				TemplateHandler.removeHeader();
				APIHandler.isBackendLogin(function(data){
					if(data){
						APIHandler.logoutBackend();
					}
				});
    },
    bsLogout : function(){
				LoginHandler.logout();
				bookEnv.bookshelfSourceMode = 'open';
				new BooksAction();
    },
    viewLoginMode : function(){
        (new MsgAction('LoginHandler')).Log('viewLoginMode');
        //切換至登出按鈕
        $('#buttonlogin').removeClass('login').addClass('logout');
/*
				APIHandler.getBooksJSON(bookEnv.currentCateId);
        _newbooks = bookEnv.BooksByCateJSON.newbook;
        _allbooks = bookEnv.BooksByCateJSON.allbook;
        BooksHandler.bindBookshelf(_newbooks,_allbooks,bookEnv.currentCateName);
*/
    },
    viewLogoutMode : function(){
        (new MsgAction('LoginHandler')).Log('viewLogoutMode');
        //切換至登入按鈕
        $('#buttonlogin').removeClass('logout').addClass('login');
    },
    showLogin : function(){
        (new MsgAction('LoginHandler')).Log('showLogin');
        $(selector_book_content).removeClass('show');
        DialogueHandler.showMask();
        $(selector_login).addClass('show');
        DialogueHandler.center(selector_login);
        $('#login h3 input').eq(0).focus();
    },
    chkSingleLogin : function(){
	    	APIHandler.loginCheck(function(data){
	    		(new MsgAction('LoginHandler')).Log('chkSingleLogin');
	    		switch(data.type){
	    			case 'a':
	    				TemplateHandler.attachHeader_adminlogin();
	    				break;
	    			case 'u':
	    				if(data.id>0){
				    		APIHandler.chkSingleLogin(function(valid){
			  	  			if(valid){
			  	  				TemplateHandler.attachHeader_userlogin();
			  	  			}else{
			  	  				alert(systemEnv.Language.login_in_different_place);
			  	  				if(ToolsHandler.isMobile()){
			  	  					document.location.href='/logout/';
			  	  				}else{
			  	  					LoginHandler.bsLogout();
											logout_callbacks.fire();
			  	  				}
			  	  			}
			    			});
	    				}
	    				break;
	    			case '-':
	    				TemplateHandler.removeHeader();
	    				if($('#buttonlogin').hasClass('logout')){
	    					LoginHandler.logout();
	    				}
	    				break;
	    		}
	    	});
    }
}

/*
isShow: show login button
redirect: if true, than redirect to personal list after login
*/
var LoginAction = function(isShow){
    var _msgHandler = new MsgAction('LoginAction');
    Init(isShow);
    _msgHandler.Log('Loaded!!!');

    function Init(_isShow){
        if(bookEnv.InitJSON.member || _isShow){
            //畫面設定
            //登入/登出按鈕設定
            if(bookEnv.InitJSON.buid!=null){
                $('#buttonlogin').addClass('logout');
            }else{
                $('#buttonlogin').addClass('login');
            }
            $('#buttonlogin').show();
        }
        
        //按鈕功能列的登入功能開啟登入畫面
        $('.btnlogin').click(function(){
            LoginHandler.showLogin();
        });

        //我的書櫃按鈕
        $('.btnmybs').click(function(){
	          bookEnv.currentCateName = systemEnv.Language.mybs+'-'+ MenuHandler.getSelectedSubMenuText();
			    	bookEnv.currentCateId = MenuHandler.getSelectedSubMenuID();
		        bookEnv.bookshelfSourceMode = 'my';
		        _msgHandler.Log('LoginAction: bookshelfSourceMode='+bookEnv.bookshelfSourceMode);
		        bookEnv.currentCateId = $('#currentCateId').val();
		        APIHandler.getMyBooksJSON(bookEnv.currentCateId);
		        _newbooks = bookEnv.BooksByCateJSON.newbook;
		        _allbooks = bookEnv.BooksByCateJSON.allbook;
	          BooksHandler.bindBookshelf(_newbooks,_allbooks,'');
			    	BooksHandler.viewMyBsMode();
        });

        //公開的書櫃按鈕
        $('.btnopenbs').click(function(){
            bookEnv.currentCateName = MenuHandler.getSelectedSubMenuText();
            bookEnv.currentCateId = MenuHandler.getSelectedSubMenuID();
            bookEnv.bookshelfSourceMode = 'open';
            _msgHandler.Log('LoginAction: bookshelfSourceMode='+bookEnv.bookshelfSourceMode);
            bookEnv.currentCateId = $('#currentCateId').val();
            APIHandler.getBooksJSON(bookEnv.currentCateId);
            _newbooks = bookEnv.BooksByCateJSON.newbook;
            _allbooks = bookEnv.BooksByCateJSON.allbook;
            BooksHandler.bindBookshelf(_newbooks,_allbooks,bookEnv.currentCateName);
		    		BooksHandler.viewOpenBsMode();
        });


        $('#login input').keydown(function(event){
            if(event.which==13){
                $('#login .button div').click();
            }
        });

        $('#login .button div').click(function(){
            if($('#login h3 input').eq(0).val()==''){
                alert('請輸入帳號!');
                return;
            }
            if($('#login h3 input').eq(1).val()==''){
                alert('請輸入密碼!');
                return;
            }

            //跟主機端驗證帳密
            _login_backend_prefix = '@';
            _pid = $('#login input[type=text]').val();
            if(_pid.indexOf(_login_backend_prefix)==0){
            	_pid = _pid.substr(_login_backend_prefix.length);
            	_pwd = $('#login input[type=password]').val();
            	APIHandler.loginBackend(_pid,_pwd,function(data){
								if(APIHandler.isSuccess(data.code)){
									$(selector_dialogue_bg).click();
									LoginHandler.viewLoginMode();
									TemplateHandler.attachHeader_adminlogin();
									login_callbacks.fire();
								}else{
									alert(data.msg);
								}
            	});
            }else{
	            switch(bookEnv.InitJSON.member_system){
        	        case 'self':
                	    _pwd = ToolsHandler.MD5($('#login input[type=password]').val());
	                    break;
        	        default:
                	    _pwd = $('#login input[type=password]').val();
	                    break;
        	    }

	            //由登入註記判斷登入模式
        	    if(bookEnv.loginAndOpenBook){
                	LoginHandler.loginAndOpenBook(_pid,_pwd,function(){
    	            	login_callbacks.fire();
    	            });
	            }else{
        	        LoginHandler.login(_pid,_pwd,function(){
        	        	login_callbacks.fire();
        	        });
	            }

        	    if(bookEnv.bookshelfSourceMode=='my'){
                	bookEnv.currentCateName = systemEnv.Language.mybs+'-' + bookEnv.currentCateName;
	                new BooksAction();
        	    }
	    			}
        });
        
				//按鈕功能列的登出功能登出帳號
        $('.btnlogout > div.logout').click(function(){
        	logout_callbacks.fire();
					LoginHandler.bsLogout();
        });
				//list lougout
        $('.btnlogout > div.listlogout').click(function(){
        	logout_callbacks.fire();
					LoginHandler.logout();
        });				
    }
}

var TemplateHandler = {
    attachHeader : function(){
      (new MsgAction('TemplateHandler')).Log('attachHeader');
      if(!$('#topMenu').length){
				APIHandler.getTpl('Header',function(tpl){
					$(selector_body).parent().prepend(tpl);
				});
			}
    },
    attachHeader_adminlogin : function(){
    	if(typeof(_isApp) == "undefined" || !_isApp){
				(new MsgAction('TemplateHandler')).Log('attachHeader_adminlogin');
				if(!$('#topMenu').length){
					APIHandler.getTpl('HeaderAdminLogin',function(tpl){
						$(selector_body).parent().prepend(tpl);
						new EBookConvertAction();
					});
				}
			}
    },
    attachHeader_userlogin : function(){
			if(typeof(_isApp) == "undefined" || !_isApp){
        (new MsgAction('TemplateHandler')).Log('attachHeader_userlogin');
        if(!$('#topMenu').length){
					APIHandler.getTpl('HeaderUserLogin',function(tpl){
						$(selector_body).parent().prepend(tpl);
					});
				}
			}
    },
    removeHeader : function(){
  		(new MsgAction('TemplateHandler')).Log('removeHeader');
  		$('#topMenu').remove();
    }
}

var KeyboardAction = function(){
    Init();
    function Init(){
        $(document).keydown(function(event){
            switch(event.which){
           		case 27://[ESC]
                if(DialogueHandler.isMaskShow()){
                    $(selector_dialogue_bg).click();
                    return;
                }

                if(BooksHandler.mode()=='search'){
                	console.log(MenuHandler.getFirstCate());
                    switch(MenuHandler.getFirstCate()){
                        case 'all':
                            MenuHandler.selectAllbook();
                            $(selector_menu).find('.all a').click();
                            break;
                        case 'new':
                            MenuHandler.selectNewbook();
                            $(selector_menu).find('.new a').click();
                            break;
                        default:
                            $(selector_menu).find('ul:first-child > li:first-child > a:first-child').click();
                            break;
                    }
                    bookEnv.viewBookshelfMode();
                    return;
                }
               	break;
               case 112:	//F1,help, guide
               	window.open('/uploadfiles/guide/')
               	break;
               case 119:	//F8,login
								APIHandler.loginCheck(function(data){
									if(data.type!='a'){
										LoginHandler.showLogin();
									}
									if(data.type=='a'){
										LoginHandler.logout();
									}
								});
               	break;
            }
        });
    }
}

var TimerAction = function(){
		LoginHandler.chkSingleLogin();
		setInterval(BooksHandler.doRemark, 1000);
		setInterval(LoginHandler.chkSingleLogin, 120000);
}

var MotionAction = function(){
    Init();
    function Init(){
			$("#bookshelf").touchwipe({
				wipeLeft: function() { bookEnv.openBookRemark=false; },
				wipeRight: function() { bookEnv.openBookRemark=true; },
				wipeUp: function() {},
				wipeDown: function() {},
				min_move_x: 20,
				min_move_y: 20,
				preventDefaultEvents: false
			});
    }
}

$(window).load(function(){
	var _lang = new LanguageAction();
	$('#button .lang span').text(systemEnv.Language.lang);
	$('#button .info span').text(systemEnv.Language.info);
	$('#button .curriculum span').text(systemEnv.Language.curriculum);
	$('#button .btnlogin span').text(systemEnv.Language.login);
	$('#button .btnlogout > div.transcript > span').text(systemEnv.Language.transcript);
	$('#button .btnlogout > div.webcam > span').text(systemEnv.Language.webcam);
	$('#button .btnlogout > div.logout > span').text(systemEnv.Language.logout);
	$('#button .btnlogout > div.listlogout > span').text(systemEnv.Language.logout);
	$('#button .btnlogout > div#userconvert > span').text(systemEnv.Language.cloudconvert);
	$('#button .btnmybs span').text(systemEnv.Language.mybs);
	$('#button .btnopenbs span').text(systemEnv.Language.openbs);
	$('#login h1').text(systemEnv.Language.logintitle);
	$('#login h3 span:eq(0)').text(systemEnv.Language.account);
	$('#login h3 span:eq(1)').text(systemEnv.Language.password);
	$('#login .button div').text(systemEnv.Language.submit);
	$('body').append('<input type="hidden" id="currentCateId">');
	$('body').append('<input type="hidden" id="currentBookId">');
	$('#menu.init img').attr('title',systemEnv.Language.member_nomenu_text);

	//keep for bookshelf list login
	var _t = new TimerAction();
});
