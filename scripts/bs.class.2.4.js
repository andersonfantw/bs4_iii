/**************************************************************************
version:
last update date: 2014/03/08
fix list:
2014030801 - fix. login and reflash page, click 'open' button, cant't login.
20160321 - add APIHandler: validCode, isSuccess, getMainCode, getSubCode
**************************************************************************/
var selector_body = '#body';
var selector_header = '#body>#header';
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
var selector_dialogue_bg = '#dialogue_bg';

var _debugMode = true;
var _basepath = web_url;

var systemEnv = {
		serveraddr:'http://127.0.0.1:20038',
		defaultBookshelfLayer:-1,
		httpdomain:'',
    _errormsgcount:0,
    lang:'',
    isErrorLogLoad:false,
    mousePosX:0,
    mousePosY:0,
    deviceJSON:[],
    userDefinedSetting:[],
    LanguageSetting:[],
    Language:[],
    isBackend:false,
    isSysBackend:false,
    bsid:0,
    buid:0,
    account:0	//current bookshelf manager account
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
    currentCateId:0,
    currentCateLevel:0,
    currentBookId:0,
    requestCode:'',
    requestMsg:'',
    searchKeyword:'',
    loginAndOpenBook: false,
    openBookRemark:false,
    openMode:'', //webbook, ibook
    bookshelfSourceMode:'open', //open, my
    pid:'',	//user id
    pwd:'',	//user pwd
    viewBookshelfMode : function(){
        $('#body').removeClass('mode_search').addClass('mode_bookshelf');
    },
    viewSearchMode : function(){
        $('#body').removeClass('mode_bookshelf').addClass('mode_search');
    }
}

var MsgAction = function(name){
    var _console=true;
    var _htmlerrorlog=false;
    var _webpage=false;
    var _alert=false;
    var _database=false;
    var _mosionsensor=false;

    var ClassName = name;

    Init();
    function Init(){
        if(($('#ErrorLog').length==0) && _htmlerrorlog){
            $('body').prepend('<div id="ErrorLog" style="width:900px;height:400px;overflow-y:scroll;border:solid 10px #999;position:absolute;background-color:#ddd;font-size:11px;z-index:10000"></div>');
        }

        if(!systemEnv.isErrorLogLoad){
            $('body').keydown(function(event){
                if(event.which==115){
                    if($('#ErrorLog').is(':visible')){
                        $('#ErrorLog').hide();
                    }else{
                        $('#ErrorLog').show();
                    }
                }
            });
            //document.write('HTML error log is enable. Please press &ltF4&gt to turn on/off log window.');
            systemEnv.isErrorLogLoad = true;
        }
    }

    this.LogMotion = function(msg){if(_mosionsensor){Log(msg);}}
    this.Log = function(msg){Log(msg);}

    function Log(msg){
        if(_debugMode){
            systemEnv._errormsgcount += 1;
            var _msg=name+' : '+msg+'; ';
            if(_console && !!window.console){console.log(systemEnv._errormsgcount+')'+_msg)}
            if(_htmlerrorlog){
                $('#ErrorLog').html(systemEnv._errormsgcount+')'+_msg.replace('\n','<br />').replace(';',';<br />')+$('#ErrorLog').html())
            }
            if(_webpage){document.write(systemEnv._errormsgcount+')'+_msg)}
            if(_alert){alert(_msg)}
            if(_database){
                $.ajax({
                    type: "POST",
                    contentType: "application/json; charset=utf-8",
                    dataType: "html",
                    url: "../api/error_report.php",
                    data: _msg,
                    success: function(){
                        var _msg = 'Database error report mode success!';
                    },
                    error: function(){
                        var _msg = 'Database error report mode fail!';
                        if(_console){console.log(_msg)}
                        if(_webpage){document.write(_msg)}
                        if(_alert){alert(_msg)}
                    }
                }); 
            }
        };
    }

    this.ShowMsg = function(msg){
        alert(msg);
    }
}

var ToolsHandler = {
    isNull : function(obj){
        return (obj==null)?'':obj;
    },
    formatURL : function(_url){ //將所有網址設定為http://開頭
    	var arr = ['^(http[s]{0,1}:\/\/)','^(cyberhoodwonderbox:\/\/)','^(\/[a-zA-Z0-9\-_]+)+\.[^.]+'];
    	for(i=0;i<arr.length;i++){
    		var patt = new RegExp(arr[i]);
    		isValid = patt.test(_url);
    		if(isValid){
    			switch(i){
    				case 0:
    					return {type:'http',url:_url};
    					break;
    				case 1:
    					if(!ToolsHandler.isMobile()){
    						alert('Mobile only! Please install wonderbox app.');
    						return null;
    					}else{
    						 return {type:'wonderbox',url:_url};
    					}
    					break;
    				case 2:
    					return {type:'path',url:'http://'+location.host+_url};
    					break;
    			}
    		}
    	}
    	return false;
    },
    isOpera:(window.opera&&navigator.userAgent.match(/opera/gi))?true:false,
    isIE:(!this.isOpera&&document.all&&navigator.userAgent.match(/msie/gi))?true:false,
    isSafari:(!this.isIE&&navigator.userAgent.match(/safari/gi))?true:false,
    isGecko:(!this.isIE&&navigator.userAgent.match(/gecko/gi))?true:false,
    isFirefox:(!this.isIE&&navigator.userAgent.match(/firefox/gi))?true:false,
    isAndroid: (navigator.userAgent.match(/Android/i))?true:false,
    isBlackBerry: (navigator.userAgent.match(/BlackBerry/i))?true:false,
    isiOS: (navigator.userAgent.match(/iPhone|iPad|iPod/i))?true:false,
    isOpera: (navigator.userAgent.match(/Opera Mini/i))?true:false,
    isWindows: (navigator.userAgent.match(/IEMobile/i))?true:false,
    isMobile: function(){
			return (ToolsHandler.isAndroid || ToolsHandler.isBlackBerry || ToolsHandler.isiOS || ToolsHandler.isOpera || ToolsHandler.isWindows);
    },
    setCookie : function(key, value, expire, domain, path)
    {
        var ck=key +'='+ encodeURIComponent(value);
        if(expire==null){ expire=120; }  //設定登入30分鐘逾時
        if( expire )
        {
            var epr=new Date();
            epr.setTime(epr.getTime()+ expire*1000 );
            ck+=';expires='+ epr.toUTCString();
        }
        if( domain )
        ck+=';domain='+ domain;
        if( path )
        ck+=';path='+ path;
        document.cookie=ck;
    },
    getCookie : function(key)
    {
        if( document.cookie.length==0 ) return false;
        var i=document.cookie.search(key+'=');
        if( i==-1 )   return false;
        i+=key.length+1;
        var j=document.cookie.indexOf(';', i);
        if( j==-1 )   j=document.cookie.length;
        return document.cookie.slice(i,j);
    },
    delCookie : function(key)
    {
        this.setCookie(key, '', -2000);
    },
    showPageInfo : function()
    {
        var _msgHandler = new MsgAction('systemEnv(showPageInfo)');
        var s = "";
        s += " \n網頁可見區域寬："+document.documentElement.clientWidth;
        s += " \n網頁可見區域高："+document.documentElement.clientHeight;
        s += " \n網頁可見區域寬："+ document.documentElement.offsetWidth+ " (包括邊線和捲軸的寬)";
        s += " \n網頁可見區域高："+document.documentElement.offsetHeight + " (包括邊線的寬)";
        s += " \n網頁正文全文寬："+document.documentElement.scrollWidth;
        s += " \n網頁正文全文高："+document.documentElement.scrollHeight;
        s += " \n網頁被捲去的高(ff)："+ document.body.scrollTop;
        s += " \n網頁被捲去的高(ie)："+ document.documentElement.scrollTop;
        s += " \n網頁被捲去的左："+document.documentElement.scrollLeft;
        s += " \n網頁正文部分上："+window.screenTop;
        s += " \n網頁正文部分左："+window.screenLeft;
        s += " \n螢幕解析度的高："+window.screen.height;
        s += " \n螢幕解析度的寬："+window.screen.width;
        s += " \n螢幕可用工作區高度："+window.screen.availHeight;
        s += " \n螢幕可用工作區寬度："+window.screen.availWidth;
        s += " \n你的螢幕設置是 "+window.screen.colorDepth +" 位元彩色";
        s += " \n你的螢幕設置 "+window.screen.deviceXDPI +" 圖元/英寸";
        _msgHandler.Log(s);
    },
    showWindowEnv : function(){
        var _msgHandler = new MsgAction('systemEnv(showWindowEnv)');
        _msgHandler.Log(navigator.userAgent);
        if(ToolsHandler.isOpera){_msgHandler.Log('Broswer is Opera.');}
        if(ToolsHandler.isIE){_msgHandler.Log('Broswer is IE.');}
        if(ToolsHandler.isSafari){_msgHandler.Log('Broswer is Safari.');}
        if(ToolsHandler.isGecko){_msgHandler.Log('Broswer is Gecko.');}
        if(ToolsHandler.isFirefox){_msgHandler.Log('Broswer is Firefox.');}
    },
    MM_openBrWindow : function(theURL,winName,popupwidth,popupheight) { //v2.0
        if (navigator.userAgent.indexOf('Safari') >= 0) {
            var _newWin = window.open(theURL,winName,'width=' + (popupwidth + 1) + ',height=' + (popupheight + 1) + ',resizable=yes');
        } else {
            var _newWin = window.open(theURL,winName,'width=' + popupwidth + ',height=' + popupheight + ',resizable=yes');
        }
        if(!_newWin || _newWin.closed || typeof _newWin.closed=='undefined'){
        	alert(systemEnv.Language.popblockerwarning);
        }
    },
    decodeURL : function(query){
    	var p = $.getQuery(query);
    	var q = Base64.decode(p);
			elements = q.split('&');
			var keyValues = {};
			for(var i in elements) {
			  var key = elements[i].split("=");
			  if (key.length > 1) {
			    keyValues[decodeURIComponent(key[0].replace(/\+/g, " "))] = decodeURIComponent(key[1].replace(/\+/g, " "));
			  }
			}
			return keyValues;
    },
    MD5 : function(string) {
     
        function RotateLeft(lValue, iShiftBits) {
	        return (lValue<<iShiftBits) | (lValue>>>(32-iShiftBits));
        }
     
        function AddUnsigned(lX,lY) {
	        var lX4,lY4,lX8,lY8,lResult;
	        lX8 = (lX & 0x80000000);
	        lY8 = (lY & 0x80000000);
	        lX4 = (lX & 0x40000000);
	        lY4 = (lY & 0x40000000);
	        lResult = (lX & 0x3FFFFFFF)+(lY & 0x3FFFFFFF);
	        if (lX4 & lY4) {
		        return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
	        }
	        if (lX4 | lY4) {
		        if (lResult & 0x40000000) {
			        return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
		        } else {
			        return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
		        }
	        } else {
		        return (lResult ^ lX8 ^ lY8);
	        }
        }
     
        function F(x,y,z) { return (x & y) | ((~x) & z); }
        function G(x,y,z) { return (x & z) | (y & (~z)); }
        function H(x,y,z) { return (x ^ y ^ z); }
        function I(x,y,z) { return (y ^ (x | (~z))); }
     
        function FF(a,b,c,d,x,s,ac) {
	        a = AddUnsigned(a, AddUnsigned(AddUnsigned(F(b, c, d), x), ac));
	        return AddUnsigned(RotateLeft(a, s), b);
        };
     
        function GG(a,b,c,d,x,s,ac) {
	        a = AddUnsigned(a, AddUnsigned(AddUnsigned(G(b, c, d), x), ac));
	        return AddUnsigned(RotateLeft(a, s), b);
        };
     
        function HH(a,b,c,d,x,s,ac) {
	        a = AddUnsigned(a, AddUnsigned(AddUnsigned(H(b, c, d), x), ac));
	        return AddUnsigned(RotateLeft(a, s), b);
        };
     
        function II(a,b,c,d,x,s,ac) {
	        a = AddUnsigned(a, AddUnsigned(AddUnsigned(I(b, c, d), x), ac));
	        return AddUnsigned(RotateLeft(a, s), b);
        };
     
        function ConvertToWordArray(string) {
	        var lWordCount;
	        var lMessageLength = string.length;
	        var lNumberOfWords_temp1=lMessageLength + 8;
	        var lNumberOfWords_temp2=(lNumberOfWords_temp1-(lNumberOfWords_temp1 % 64))/64;
	        var lNumberOfWords = (lNumberOfWords_temp2+1)*16;
	        var lWordArray=Array(lNumberOfWords-1);
	        var lBytePosition = 0;
	        var lByteCount = 0;
	        while ( lByteCount < lMessageLength ) {
		        lWordCount = (lByteCount-(lByteCount % 4))/4;
		        lBytePosition = (lByteCount % 4)*8;
		        lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount)<<lBytePosition));
		        lByteCount++;
	        }
	        lWordCount = (lByteCount-(lByteCount % 4))/4;
	        lBytePosition = (lByteCount % 4)*8;
	        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80<<lBytePosition);
	        lWordArray[lNumberOfWords-2] = lMessageLength<<3;
	        lWordArray[lNumberOfWords-1] = lMessageLength>>>29;
	        return lWordArray;
        };
     
        function WordToHex(lValue) {
	        var WordToHexValue="",WordToHexValue_temp="",lByte,lCount;
	        for (lCount = 0;lCount<=3;lCount++) {
		        lByte = (lValue>>>(lCount*8)) & 255;
		        WordToHexValue_temp = "0" + lByte.toString(16);
		        WordToHexValue = WordToHexValue + WordToHexValue_temp.substr(WordToHexValue_temp.length-2,2);
	        }
	        return WordToHexValue;
        };
     
        function Utf8Encode(string) {
	        string = string.replace(/\r\n/g,"\n");
	        var utftext = "";
     
	        for (var n = 0; n < string.length; n++) {
     
		        var c = string.charCodeAt(n);
     
		        if (c < 128) {
			        utftext += String.fromCharCode(c);
		        }
		        else if((c > 127) && (c < 2048)) {
			        utftext += String.fromCharCode((c >> 6) | 192);
			        utftext += String.fromCharCode((c & 63) | 128);
		        }
		        else {
			        utftext += String.fromCharCode((c >> 12) | 224);
			        utftext += String.fromCharCode(((c >> 6) & 63) | 128);
			        utftext += String.fromCharCode((c & 63) | 128);
		        }
     
	        }
     
	        return utftext;
        };
     
        var x=Array();
        var k,AA,BB,CC,DD,a,b,c,d;
        var S11=7, S12=12, S13=17, S14=22;
        var S21=5, S22=9 , S23=14, S24=20;
        var S31=4, S32=11, S33=16, S34=23;
        var S41=6, S42=10, S43=15, S44=21;
     
        string = Utf8Encode(string);
     
        x = ConvertToWordArray(string);
     
        a = 0x67452301; b = 0xEFCDAB89; c = 0x98BADCFE; d = 0x10325476;
     
        for (k=0;k<x.length;k+=16) {
	        AA=a; BB=b; CC=c; DD=d;
	        a=FF(a,b,c,d,x[k+0], S11,0xD76AA478);
	        d=FF(d,a,b,c,x[k+1], S12,0xE8C7B756);
	        c=FF(c,d,a,b,x[k+2], S13,0x242070DB);
	        b=FF(b,c,d,a,x[k+3], S14,0xC1BDCEEE);
	        a=FF(a,b,c,d,x[k+4], S11,0xF57C0FAF);
	        d=FF(d,a,b,c,x[k+5], S12,0x4787C62A);
	        c=FF(c,d,a,b,x[k+6], S13,0xA8304613);
	        b=FF(b,c,d,a,x[k+7], S14,0xFD469501);
	        a=FF(a,b,c,d,x[k+8], S11,0x698098D8);
	        d=FF(d,a,b,c,x[k+9], S12,0x8B44F7AF);
	        c=FF(c,d,a,b,x[k+10],S13,0xFFFF5BB1);
	        b=FF(b,c,d,a,x[k+11],S14,0x895CD7BE);
	        a=FF(a,b,c,d,x[k+12],S11,0x6B901122);
	        d=FF(d,a,b,c,x[k+13],S12,0xFD987193);
	        c=FF(c,d,a,b,x[k+14],S13,0xA679438E);
	        b=FF(b,c,d,a,x[k+15],S14,0x49B40821);
	        a=GG(a,b,c,d,x[k+1], S21,0xF61E2562);
	        d=GG(d,a,b,c,x[k+6], S22,0xC040B340);
	        c=GG(c,d,a,b,x[k+11],S23,0x265E5A51);
	        b=GG(b,c,d,a,x[k+0], S24,0xE9B6C7AA);
	        a=GG(a,b,c,d,x[k+5], S21,0xD62F105D);
	        d=GG(d,a,b,c,x[k+10],S22,0x2441453);
	        c=GG(c,d,a,b,x[k+15],S23,0xD8A1E681);
	        b=GG(b,c,d,a,x[k+4], S24,0xE7D3FBC8);
	        a=GG(a,b,c,d,x[k+9], S21,0x21E1CDE6);
	        d=GG(d,a,b,c,x[k+14],S22,0xC33707D6);
	        c=GG(c,d,a,b,x[k+3], S23,0xF4D50D87);
	        b=GG(b,c,d,a,x[k+8], S24,0x455A14ED);
	        a=GG(a,b,c,d,x[k+13],S21,0xA9E3E905);
	        d=GG(d,a,b,c,x[k+2], S22,0xFCEFA3F8);
	        c=GG(c,d,a,b,x[k+7], S23,0x676F02D9);
	        b=GG(b,c,d,a,x[k+12],S24,0x8D2A4C8A);
	        a=HH(a,b,c,d,x[k+5], S31,0xFFFA3942);
	        d=HH(d,a,b,c,x[k+8], S32,0x8771F681);
	        c=HH(c,d,a,b,x[k+11],S33,0x6D9D6122);
	        b=HH(b,c,d,a,x[k+14],S34,0xFDE5380C);
	        a=HH(a,b,c,d,x[k+1], S31,0xA4BEEA44);
	        d=HH(d,a,b,c,x[k+4], S32,0x4BDECFA9);
	        c=HH(c,d,a,b,x[k+7], S33,0xF6BB4B60);
	        b=HH(b,c,d,a,x[k+10],S34,0xBEBFBC70);
	        a=HH(a,b,c,d,x[k+13],S31,0x289B7EC6);
	        d=HH(d,a,b,c,x[k+0], S32,0xEAA127FA);
	        c=HH(c,d,a,b,x[k+3], S33,0xD4EF3085);
	        b=HH(b,c,d,a,x[k+6], S34,0x4881D05);
	        a=HH(a,b,c,d,x[k+9], S31,0xD9D4D039);
	        d=HH(d,a,b,c,x[k+12],S32,0xE6DB99E5);
	        c=HH(c,d,a,b,x[k+15],S33,0x1FA27CF8);
	        b=HH(b,c,d,a,x[k+2], S34,0xC4AC5665);
	        a=II(a,b,c,d,x[k+0], S41,0xF4292244);
	        d=II(d,a,b,c,x[k+7], S42,0x432AFF97);
	        c=II(c,d,a,b,x[k+14],S43,0xAB9423A7);
	        b=II(b,c,d,a,x[k+5], S44,0xFC93A039);
	        a=II(a,b,c,d,x[k+12],S41,0x655B59C3);
	        d=II(d,a,b,c,x[k+3], S42,0x8F0CCC92);
	        c=II(c,d,a,b,x[k+10],S43,0xFFEFF47D);
	        b=II(b,c,d,a,x[k+1], S44,0x85845DD1);
	        a=II(a,b,c,d,x[k+8], S41,0x6FA87E4F);
	        d=II(d,a,b,c,x[k+15],S42,0xFE2CE6E0);
	        c=II(c,d,a,b,x[k+6], S43,0xA3014314);
	        b=II(b,c,d,a,x[k+13],S44,0x4E0811A1);
	        a=II(a,b,c,d,x[k+4], S41,0xF7537E82);
	        d=II(d,a,b,c,x[k+11],S42,0xBD3AF235);
	        c=II(c,d,a,b,x[k+2], S43,0x2AD7D2BB);
	        b=II(b,c,d,a,x[k+9], S44,0xEB86D391);
	        a=AddUnsigned(a,AA);
	        b=AddUnsigned(b,BB);
	        c=AddUnsigned(c,CC);
	        d=AddUnsigned(d,DD);
        }
     
        var temp = WordToHex(a)+WordToHex(b)+WordToHex(c)+WordToHex(d);
     
        return temp.toLowerCase();
    }
}

var Tools = function(){
    var _msgHadler = new MsgAction('Tools');

    Init();
    function Init(){
        if(_debugMode){
            ToolsHandler.showPageInfo();
            ToolsHandler.showWindowEnv();
        }

        // If NS -- that is, !IE -- then set up for mouse capture
        if (!ToolsHandler.isIE) document.captureEvents(Event.MOUSEMOVE)

        // Set-up to use getMouseXY function onMouseMove
        document.onmousemove = getMouseXY;

    }

    function getMouseXY(e) {
        if (ToolsHandler.isIE) { // grab the x-y pos.s if browser is IE
            systemEnv.mousePosX = event.clientX + document.body.scrollLeft
            systemEnv.mousePosY = event.clientY + document.body.scrollTop
        } else {  // grab the x-y pos.s if browser is NS
            systemEnv.mousePosX = e.pageX
            systemEnv.mousePosY = e.pageY
        }

        // catch possible negative values in NS4
        if (systemEnv.mousePosX < 0){windowEnv.mousePosX = 0}
        if (systemEnv.mousePosY < 0){windowEnv.mousePosY = 0}  
        // show the position values in the form named Show
        // in the text fields named MouseX and MouseY
        _msgHadler.LogMotion('x='+systemEnv.mousePosX+';y='+systemEnv.mousePosY+';');
        return true
    }
}

var APIHandler ={
		validCode : function(code){
			var regex = /[1-9][0-9]{2}\.[0-9]{1,3}/g
			return regex.test(code);
		},
		isSuccess : function(code){
			var regex = /^200([\.][0-9]{1,3}){0,1}$/g
			return regex.test(code);
		},
		getMainCode : function(code){
			if(this.validCode(code)){
				arr = code.split('.')
				return arr[0];
			}else{
				//unexpect code format
			}
		},
		getSubCode : function(code){
			if(this.validCode(code)){
				arr = code.split('.')
				return arr[1];
			}else{
				//unexpect code format
			}
		},
    getInitJSON : function(){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/get_sys_info.php?"+Date.now(),
            data: {bs:bs_id}, 
            success: function(data){
                bookEnv.InitJSON=data;
                systemEnv.httpdomain=data.httpdomain;
                systemEnv.serveraddr=data.serveraddr;
                if(bookEnv.InitJSON.acc){
                	bookEnv.pid = bookEnv.InitJSON.acc;
                }
                _msgHadler.Log('getInitJSON data='+JSON.stringify(data));
                _msgHadler.Log('Reveive getInitJSON success!');
            },
            error: function(){
                bookEnv.IsDefaultData=true;
                _msgHadler.Log('Reveive getInitJSON fail!');
            },
            async:false
        });
    },
    validBookshelfList : function(_isExpiredList,_buid,_uid,returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "text",
            url: _basepath+"/api/api.php?"+Date.now(),
            data: {cmd:'validBookshelfList',isExpiredList:_isExpiredList,buid:_buid,uid:_uid}, 
            success: function(data){
            		_str='';
            		if(_isExpiredList) _str += 'Expired';
            		if(_buid>0){
            			_str += 'ByBUID';
            		}else if(_uid>0){
            			_str += 'ByUID';
            		}
                _msgHadler.Log('validBookshelfList data='+data);
                _msgHadler.Log('Reveive validBookshelfList success!');
                if(!window.localStorage['bslist'+_str]){
                	returnFunction(false);
                }else{
                	returnFunction(ToolsHandler.MD5(lzw_decode(window.localStorage['bslist'+_str]))==data);
                }
            },
            error: function(){
                _msgHadler.Log('Reveive validBookshelfList fail!');
            },
            async:false
        });  
    },
    getBookshelfList : function(_isExpiredList,_buid,_uid,returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        APIHandler.validBookshelfList(_isExpiredList,_buid,_uid,function(v){
      		_str='';
      		if(_isExpiredList) _str += 'Expired';
      		if(_buid>0){
      			_str += 'ByBUID';
      		}else if(_uid>0){
      			_str += 'ByUID';
      		}
        	if(v){
        		_val=$.parseJSON(lzw_decode(window.localStorage['bslist'+_str]));
        		returnFunction(_val);
        	}else{
		        $.ajax({
		            type: "post",
		            dataType: "json",
		            url: _basepath+"/api/api.php?"+Date.now(),
		            data: {cmd:'getBookshelfList',isExpiredList:_isExpiredList,buid:_buid,uid:_uid},
		            success: function(data){
		            		_val = lzw_encode(JSON.stringify(data));
		                window.localStorage['bslist'+_str] = _val;
		                _msgHadler.Log('getBookshelfList data='+JSON.stringify(data));
		                _msgHadler.Log('Reveive getBookshelfList success!');
		                returnFunction(data);
		            },
		            error: function(){
		                _msgHadler.Log('Reveive getBookshelfList fail!');
		            },
		            async:false
		        });
	      	}
        });
    },
    validMenuJSON : function(returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "text",
            url: _basepath+"/api/get_menu.php?"+Date.now(),
            data: {cmd:'valid',bs:bs_id}, 
            success: function(data){
                _msgHadler.Log('validMenuJSON data='+data);
                _msgHadler.Log('Reveive validMenuJSON success!');
                if(!window.localStorage['bsm']) returnFunction(false);
                returnFunction(ToolsHandler.MD5(lzw_decode(window.localStorage['bsm']))==data);
            },
            error: function(){
                _msgHadler.Log('Reveive validMenuJSON fail!');
            },
            async:false
        });  
    },
    getMenuJSON : function(){
        var _msgHadler = new MsgAction('APIHandler');
        APIHandler.validMenuJSON(function(v){
        	if(v){
        		bookEnv.MenuJSON=$.parseJSON(lzw_decode(window.localStorage['bsm']));
        	}else{
		        $.ajax({
		            type: "post",
		            dataType: "json",
		            url: _basepath+"/api/get_menu.php?"+Date.now(),
		            data: {bs:bs_id}, 
		            success: function(data){
		                bookEnv.MenuJSON=data;
		                window.localStorage['bsm'] = lzw_encode(JSON.stringify(data));
		                _msgHadler.Log('getMenuJSON data='+JSON.stringify(data));
		                _msgHadler.Log('Reveive getMenuJSON success!');
		            },
		            error: function(){
		                bookEnv.IsDefaultData=true;
		                bookEnv.MenuJSON=data_menu;
		                _msgHadler.Log('getMenuJSON data='+JSON.stringify(data_menu));
		                _msgHadler.Log('Reveive getMenuJSON fail!');
		            },
		            async:false
		        });
	      	}
        });
    },
    validBooksJSON : function(_cid,returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "text",
            url: _basepath+"/api/get_books.php?"+Date.now(),
            data: {cmd:'valid',cid:_cid,u_id:uid,bs:bs_id},
            success: function(data){
	              _msgHadler.Log('validBooksJSON data='+data);
	              _msgHadler.Log('Reveive validBooksJSON success!');
								if(_cid==null){
									if(!window.localStorage['bspb']) returnFunction(false);
									returnFunction(ToolsHandler.MD5(lzw_decode(window.localStorage['bspb']))==data);
								}else{
									if(!window.localStorage['bspb'+_cid]) returnFunction(false);
									returnFunction(ToolsHandler.MD5(lzw_decode(window.localStorage['bspb'+_cid]))==data);
								}
	          },
            error: function(){
                _msgHadler.Log('Reveive validBooksJSON fail!');
            },
            async:false
        });
    },
    getBooksJSON : function(_cid){
        var _msgHadler = new MsgAction('APIHandler');
        APIHandler.validBooksJSON(_cid,function(v){
        	if(v){
        		if(_cid==null){
        			bookEnv.BooksJSON=$.parseJSON(lzw_decode(window.localStorage['bspb']));
        		}else{
        			bookEnv.BooksByCateJSON=$.parseJSON(lzw_decode(window.localStorage['bspb'+_cid]));
        		}
        	}else{
		        $.ajax({
		            type: "post",
		            dataType: "json",
		            url: _basepath+"/api/get_books.php?"+Date.now(),
		            data: {cid:_cid,u_id:uid,bs:bs_id},
		            success: function(data){
		            	if(_cid==null){
		                bookEnv.BooksJSON=data;
		                window.localStorage['bspb'] = lzw_encode(JSON.stringify(data));
	  	              _msgHadler.Log('getBooksJSON data='+JSON.stringify(data));
	  	            }else{
	                  bookEnv.BooksByCateJSON=data;
	                  window.localStorage['bspb'+_cid] = lzw_encode(JSON.stringify(data));
	                  _msgHadler.Log('getBooksByCateJSON data='+JSON.stringify(data));
	  	            }
			              _msgHadler.Log('Reveive getBooksJSON success!');
			          },
		            error: function(){
		                bookEnv.IsDefaultData=true;
		                bookEnv.BooksJSON=data_allbook;
		                bookEnv.BooksByCateJSON=data_allbook;
		                _msgHadler.Log('getBooksJSON data='+JSON.stringify(data_allbook));
		                _msgHadler.Log('Reveive getBooksJSON fail!');
		            },
		            async:false
		        });
		      }
		    });
    },
    getMainCateBooksJSON : function(_cid){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/get_books.php?"+Date.now(),
            data: {mcid:_cid,bs:bs_id},
            success: function(data){
                if(_cid==null){
                    bookEnv.BooksJSON=data;
                    _msgHadler.Log('getBooksJSON data='+JSON.stringify(data));
                }else{
                    bookEnv.BooksByCateJSON=data;
                    _msgHadler.Log('getBooksByCateJSON data='+JSON.stringify(data));
                }
                _msgHadler.Log('Reveive getBooksJSON success!');
            },
            error: function(){
                bookEnv.IsDefaultData=true;
                bookEnv.BooksJSON=data_allbook;
                bookEnv.BooksByCateJSON=data_allbook;
                _msgHadler.Log('getBooksJSON data='+JSON.stringify(data_allbook));
                _msgHadler.Log('Reveive getBooksJSON fail!');
            },
            async:false
        });
    },
    getMyBooksJSON : function(_cid){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/get_books.php?"+Date.now(),
            data: {cid:_cid,bs:bs_id,buid:-1},
            success: function(data){
                //if(_cid==null){
                //    bookEnv.BooksJSON=data;
                //    _msgHadler.Log('getMyBooksJSON data='+JSON.stringify(data));
                //}else{
                    bookEnv.BooksByCateJSON=data;
                    _msgHadler.Log('getMyBooksByCateJSON data='+JSON.stringify(data));
                //}
            },
            error: function(){
                bookEnv.IsDefaultData=true;
                bookEnv.BooksJSON=data_allbook;
                bookEnv.BooksByCateJSON=data_allbook;
                _msgHadler.Log('getMyBooksJSON data='+JSON.stringify(data_allbook));
                _msgHadler.Log('Reveive getMyBooksJSON fail!');
            },
            async:false
        });
    },
    getBookDetailJSON : function(_id,returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/get_book_info.php?"+Date.now(),
            data: {id:_id,bs:bs_id},
            success: function(data){
                bookEnv.BooksDetailJSON=data;
                _msgHadler.Log('Reveive getBookDetailJSON success!');
                returnFunction(data);
            },
            error: function(){
                bookEnv.IsDefaultData=true;
                _msgHadler.Log('Reveive getBookDetailJSON fail!');
            },
            async:false
        });
    },
    getSearchBooksJSON : function(_q,returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/search.php?"+Date.now(),
            data: {q:_q,bs:bs_id},
            success: function(data){
                bookEnv.BooksBySearchJSON=data;
                _msgHadler.Log('Reveive getSearchBooksJSON success!');
                returnFunction(data);
            },
            error: function(){
                bookEnv.IsDefaultData=true;
                _msgHadler.Log('Reveive getSearchBooksJSON fail!');
            },
            async:false
        });
    },
    login : function(_pid,_pwd,returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/auth_check.php?"+Date.now(),
            data: {ac:_pid,pw:_pwd,bs:bs_id},
            success: function(data){
                bookEnv.requestCode = data.code;
                bookEnv.requestMsg = data.msg;
                _msgHadler.Log('login data='+JSON.stringify(data));
                _msgHadler.Log('Send login success!');
                returnFunction(data);
            },
            error: function(){
                bookEnv.IsDefaultData=true;
                _msgHadler.Log('Send login fail!');
                return false;
            },
            async:false
        });
    },
    loginAndOpenBook : function(_pid,_pwd,returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/book_auth_check.php?"+Date.now(),
            data: {
                booktype:bookEnv.openMode,
                bid:bookEnv.currentBookId,
                cid:bookEnv.currentCateId,
                ac:_pid,
                pw:_pwd,
                bs:bs_id},
            success: function(data){
                bookEnv.requestCode = data.code;
                bookEnv.requestMsg = data.link;
                _msgHadler.Log('loginAndOpenBook data='+JSON.stringify(data));
                _msgHadler.Log('Send loginAndOpenBook success!');
                returnFunction(data);
            },
            error: function(){
                bookEnv.IsDefaultData=true;
                _msgHadler.Log('Send loginAndOpenBook fail!');
                return false;
            },
            async:false
        });
    },
    loginCheck : function(returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/login_check.php?"+Date.now(),
            data: {},
            success: function(data){
                returnFunction(data);
            },
            error: function(){
                _msgHadler.Log('Send loginCheck fail!');
                return false;
            },
            async:false
        });
    },
    logout : function(){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/logout.php?"+Date.now(),
            success: function(data){
                _msgHadler.Log('Send logout success!');
                return true;
            },
            error: function(){
                _msgHadler.Log('Send logout fail!');
                return false;
            }
        });
    },
    logBookView : function(_bid,_pid,_openMode){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/book_view.php?"+Date.now(),
            data: {bid:_bid, ac:_pid,openmode:_openMode,bs:bs_id},
            success: function(data){
                _msgHadler.Log('Send logBookView success!');
                return true;
            },
            error: function(){
                bookEnv.IsDefaultData=true;
                _msgHadler.Log('Send logBookView fail!');
                return false;
            }
        });
    },
    getDevice : function(){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/get_current_device_path.php?"+Date.now(),
            success: function(data){
                _msgHadler.Log('getDevice data='+JSON.stringify(data));
                _msgHadler.Log('Send getDevice success!');
                systemEnv.devicePath = data.currentDevicePath;
                return true;
            },
            error: function(){
                bookEnv.IsDefaultData=true;
                _msgHadler.Log('Send getDevice fail!');
                return false;
            },
            async:false
        });        
    },
    getDefaultSetting : function(){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/hosts/config.json.php?"+Date.now(),
            success: function(data){
                _msgHadler.Log('getDefaultSetting data='+JSON.stringify(data));
                _msgHadler.Log('Send getDefaultSetting success!');
                systemEnv.userDefinedSetting = data;
                return true;
            },
            error: function(){
                _msgHadler.Log('Send getDefaultSetting fail!');
                return false;
            },
            async:false
        });        
    },
    getUserDefinedSetting : function(){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/hosts/"+uid+"/"+bs_id+"/config.json.php?"+Date.now(),
            success: function(data){
                _msgHadler.Log('getUserDefinedSetting data='+JSON.stringify(data));
                _msgHadler.Log('Send getUserDefinedSetting success!');
                if(data.headerlink!='' && data.footer!=''){
                    systemEnv.userDefinedSetting = data;
                }else{
                    APIHandler.getDefaultSetting();
                    if(data.headerlink!=''){
                        systemEnv.userDefinedSetting.headerlink = data.headerlink;
                    }
                    if(data.footer!=''){
                        systemEnv.userDefinedSetting.footer = data.footer;
                    }                    
                }
                return true;
            },
            error: function(xhr,textStatus){
                APIHandler.getDefaultSetting();
                _msgHadler.Log('Send getUserDefinedSetting fail! '+textStatus);
                return false;
            },
            async:false
        });
    },
    getLanguageSetting : function(){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/languages/langsetting.json.php?"+Date.now(),
            success: function(data){
                _msgHadler.Log('getLanguageSetting data='+JSON.stringify(data));
                _msgHadler.Log('Send getLanguageSetting success!');
                systemEnv.LanguageSetting = data;
                return true;
            },
            error: function(){
                _msgHadler.Log('Send getLanguageSetting fail!');
                return false;
            },
            async:false
        });                
    },
    getLanguage : function(lang){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/languages/"+lang+"/lang.json.php?"+Date.now(),
            success: function(data){
                _msgHadler.Log('getLanguage data='+JSON.stringify(data));
                _msgHadler.Log('Send getLanguage success!');
                systemEnv.Language = data;
                return true;
            },
            error: function(){
                _msgHadler.Log('Send getLanguage fail!');
                return false;
            },
            async:false
        });                
    },
    getReadDurationKey : function(_cid,_bid,returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/read_time.php?"+Date.now(),
            data: {cid:_cid, bid:_bid},
            success: function(data){
                _msgHadler.Log('getReadDurationKey data='+JSON.stringify(data));
                _msgHadler.Log('Send setReadDurationKey success!');
                returnFunction(data);
            },
            error: function(){
                _msgHadler.Log('Send setReadDurationKey fail!');
                return false;
            },
            async:false
        });    	
    },
    setReadDuration : function(_cid,_bid,_key){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/read_time.php?"+Date.now(),
            data: {cid:_cid, bid:_bid, timestamp: _key},
            success: function(data){
                _msgHadler.Log('setReadDuration data='+JSON.stringify(data));
                _msgHadler.Log('Send setReadDuration success!');
                return true;
            },
            error: function(){
                _msgHadler.Log('Send setReadDuration fail!');
                return false;
            },
            async:false
        });                
    },
    getTranscript : function(returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/get_itutor.php?"+Date.now(),
            data: {},
            success: function(data){
                _msgHadler.Log('getTranscript data='+JSON.stringify(data));
                _msgHadler.Log('Send getTranscript success!');
                returnFunction(data);
                return true;
            },
            error: function(){
                _msgHadler.Log('Send getTranscript fail!');
                return false;
            },
            async:false
        });
    },
    chkSingleLogin : function(returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/chk_single_login.php?"+Date.now(),
            data: {},
            success: function(data){
                _msgHadler.Log('chkSingleLogin data='+JSON.stringify(data));
                _msgHadler.Log('Send chkSingleLogin success!');
                returnFunction(data);
                return true;
            },
            error: function(){
                _msgHadler.Log('Send chkSingleLogin fail!');
                return false;
            },
            async:false
        });
    },
    loginBackend : function(_pid,_pwd,returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/backend/api.php?cmd=login&"+Date.now(),
            data: {ac:_pid,pw:_pwd,bs:bs_id},
            success: function(data){
                bookEnv.requestCode = data.code;
                bookEnv.requestMsg = data.msg;
                _msgHadler.Log('loginBackend data='+JSON.stringify(data));
                _msgHadler.Log('Send loginBackend success!');
                returnFunction(data);
                return true;
            },
            error: function(){
                _msgHadler.Log('Send loginBackend fail!');
                return false;
            },
            async:false
        });
    },
    logoutBackend : function(){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: _basepath+"/api/backend/api.php?cmd=logout&"+Date.now(),
            data: {},
            success: function(){
                _msgHadler.Log('Send loginBackend success!');
                return true;
            },
            error: function(){
                _msgHadler.Log('Send loginBackend fail!');
                return false;
            },
            async:false
        });
    },
    isBackendLogin : function(returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            url: _basepath+"/api/backend/api.php?cmd=islogin&"+Date.now(),
            data: {},
            success: function(data){
                _msgHadler.Log('isBackendLogin data='+JSON.stringify(data));
                _msgHadler.Log('Send isBackendLogin success!');
                returnFunction(data);
                return true;
            },
            error: function(){
                _msgHadler.Log('Send isBackendLogin fail!');
                return false;
            },
            async:false
        });
    },
    getTpl : function(param,returnFunction){
        var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            url: _basepath+"/api/tpl.php?t="+param+"&"+Date.now(),
            data: {},
            success: function(data){
                _msgHadler.Log('getTpl data='+JSON.stringify(data));
                _msgHadler.Log('Send getTpl success!');
                returnFunction(data);
                return true;
            },
            error: function(){
                _msgHadler.Log('Send getTpl fail!');
                return false;
            },
            async:false
        });
    }
}

var APIAction = function(){
    var _msgHandler = new MsgAction('APIAction');
    var _t = new Tools();
    Init();
    _msgHandler.Log('Loaded!!!');
    
    function Init(){
        APIHandler.getUserDefinedSetting();
        APIHandler.getInitJSON();
        APIHandler.getDevice();
        APIHandler.getMenuJSON();

        _debugMode = bookEnv.InitJSON.debug_mode;
        if(bookEnv.InitJSON.mapping_device){
            if(document.location.href.indexOf(systemEnv.devicePath)==-1){
               document.location.href = systemEnv.devicePath;
               return;
            }
        }
				if(systemEnv.userDefinedSetting.headerlink!=''){
					$(selector_header).css('cursor','pointer');
					$(selector_header).click(function(){
						window.open(systemEnv.userDefinedSetting.headerlink,'header');
					});
				}
				if(systemEnv.userDefinedSetting.footer!=''){
					$(selector_footer).html(systemEnv.userDefinedSetting.footer.replace(/\\n/gi,'<br />').replace(/\*RED/,'<font color="red">').replace(/RED\*/,'</font>'));
				}
    }
}

var AnalytisHandler = {
  Start : function(_cid,_bid, returnFunction){
		_msgHandler = new MsgAction('AnalytisHandler');
		_msgHandler.Log('execute! param: _cid='+_cid+'; _bid='+_bid+';');
		APIHandler.getReadDurationKey(_cid,_bid,function(data){
			returnFunction(data);
		});
	},
	Do : function(_cid,_bid,_timestamp){
		_msgHandler = new MsgAction('AnalytisHandler');
		_msgHandler.Log('execute! param: _timestamp='+_timestamp+';_cid='+_cid+'; _bid='+_bid+';');
		delete _msgHandler;
		APIHandler.setReadDuration(_cid,_bid,_timestamp);
	}
}

var DialogueHandler = {
    //對話框的半透明背景
    isMaskShow : function(){
        return $(selector_dialogue_bg).is('.show');
    },
    showMask : function(){
        (new MsgAction('DialogueHandler')).Log('showMask');
        $(selector_dialogue_bg).addClass('show');
        var _w = document.documentElement.scrollWidth;
        var _h = document.documentElement.scrollHeight;
        if(navigator.userAgent.indexOf('iPhone')!=-1){
            _w+=746;
        }
        $(selector_dialogue_bg).css({width:_w, height:_h});
    },
    hideMask : function(){
        (new MsgAction('DialogueHandler')).Log('hideMask');
        $(selector_dialogue_bg).removeClass('show');
        $(selector_dialogue_bg).css({width:0, height:0});
        AddressHandler.removeItem();
        bookEnv.loginAndOpenBook=false;
    },
    center : function(dialogueName){
        //設定開啟時於視窗中間顯示
        var _msgHandler = new MsgAction('DialogueHandler');
        _msgHandler.Log('center');
        var _scrollTop=(document.body.scrollTop)?document.body.scrollTop:document.documentElement.scrollTop;
        var _msgHandler = new MsgAction('DialogueHandler');
        var _clientHeight = document.documentElement.clientHeight;
        var _objHeight = parseInt($(dialogueName).css('height'));
        var _screenTop=(_clientHeight>_objHeight)?(_clientHeight-_objHeight)/2:0;
				if(!ToolsHandler.isMobile()){
					$(dialogueName).css('top',((_scrollTop+_screenTop)/2+'px'));
				}
        var _screenWidth = (ToolsHandler.isMobile())?document.documentElement.scrollWidth:document.documentElement.clientWidth;
        $(dialogueName).css('left',(_screenWidth-parseInt($(dialogueName).css('width')))/2+'px');
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
            $('#convert').removeClass('show');
            $(selector_book_content).removeClass('show');
            DialogueHandler.hideMask();
        });
        
        $(window).resize(function(){
            if(DialogueHandler.isMaskShow()){
                DialogueHandler.showMask();
                DialogueHandler.center(selector_book_content);
                DialogueHandler.center(selector_login);
                DialogueHandler.center('#convert');
            }
        });
        $(window).scroll(function(){
            if(DialogueHandler.isMaskShow()){
                DialogueHandler.showMask();
                DialogueHandler.center(selector_book_content);
                DialogueHandler.center(selector_login);
                DialogueHandler.center('#convert');
            }
        });
    }
}

var AddressHandler = {
    issetCid : function(){
        return ($.address.parameter('cid')!=null);
    },
    getCid : function(){
        return $.address.parameter('cid');
    },
    addCid : function(v){
        $.address.value('?cid='+v);
    },
    issetItem : function(){
        return ($.address.parameter('item')!=null);
    },
    getItem : function(){
        return $.address.parameter('item');
    },
    addItem : function(v, append){ //bookid
        $.address.parameter('item', v, append);
        $.address.update();
    },
    removeItem : function(){ //bookid
        $.address.parameter('item','',false);
        $.address.update();
    },
    issetK : function(){
        return ($.address.parameter('k')!=null);
    },
    getK : function(){
        return $.address.parameter('k');
    },
    addK : function(v){
        $.address.value('?k='+v);
    }
}

var LanguageHandler = {
    detect : function(){
        var _msgHandler = new MsgAction('LanguageHandler');
        if(systemEnv.LanguageSetting==''){
            APIHandler.getLanguageSetting();
        }
        _lang = ToolsHandler.getCookie('currentlang');
        $.each(systemEnv.LanguageSetting,function(i){
        	if(systemEnv.LanguageSetting[i].key==_lang){
        		_currentlang = _lang;
        	}
        });
        if(!_currentlang){
          lang = window.navigator.userLanguage || window.navigator.language;
          _lang = lang.toLowerCase();
	        $.each(systemEnv.LanguageSetting,function(i){
	        	if(systemEnv.LanguageSetting[i].key==_lang){
	        		_currentlang = _lang;
	        	}
	        });
	        if(!_currentlang){
	        	_currentlang = 'zh-tw';
	        }
          ToolsHandler.setCookie('currentlang',_currentlang);
        }
        systemEnv.lang = _currentlang;
        _msgHandler.Log('detect:'+systemEnv.lang);
    },
    initSelector : function(){
        var _msgHandler = new MsgAction('LanguageHandler');
        if(systemEnv.LanguageSetting==''){
            APIHandler.getLanguageSetting();
        }
        $.each(systemEnv.LanguageSetting, function(i){
            $('#button .lang select').append('<option value="'+systemEnv.LanguageSetting[i].key+'"'+((systemEnv.LanguageSetting[i].key==systemEnv.lang)?' selected':'')+'>'+systemEnv.LanguageSetting[i].value+'</option>')
            _msgHandler.Log('initSelector do each key:'+systemEnv.LanguageSetting[i].key+' value:'+systemEnv.LanguageSetting[i].value+' success!');
        });
        $('#button .lang select').change(function(){
            ToolsHandler.setCookie('currentlang',$(this).val(),'','','/');
            document.location.reload();
        });
        _msgHandler.Log('initSelector success!');
    },
    setLang : function(){
        var _msgHandler = new MsgAction('LanguageHandler');
        if(systemEnv.LanguageSetting==''){
            APIHandler.getLanguageSetting();
        }
        LanguageHandler.detect();
        APIHandler.getLanguage((systemEnv.lang)?systemEnv.lang:'zh-tw');
        _msgHandler.Log('setLang success!');
    }
}

var LanguageAction = function(){
    var _msgHandler = new MsgAction('LanguageAction');
    Init();
    _msgHandler.Log('Loaded!!!');
    function Init(){
        LanguageHandler.setLang();
        LanguageHandler.initSelector();
    }
}

var StyleHandler = {
    setCustomizeStyle : function(){
        if(bookEnv.InitJSON=={}){
            APIHandler.getInitJSON();
        }
        var _msgHandler = new MsgAction('StyleHandler');
	if(bookEnv.InitJSON.style!=''){
	        $("head").append('<link rel="stylesheet" href="'+bookEnv.InitJSON.style+'/style.css'+'" type="text/css">');
	}
        _msgHandler.Log('setCustomizeStyle success!!!');
    }
}

var StyleAction = function(){
    var _msgHandler = new MsgAction('StyleAction');
    Init();
    _msgHandler.Log('Loaded!!!');
    function Init(){
        StyleHandler.setCustomizeStyle();
    }
}

var NoticeModeEnum = {
	none:0,
	alert:1,
	confirm:2,
	console:3,
	html:4
}

var ReturnCodeControler = function(callername,jsonString,SuccessMod,FailureMod){
	if(typeof callername=='undefined') callername='';	
	if(typeof SuccessMod=='undefined') SuccessMod=NoticeModeEnum.console;
	if(typeof FailureMod=='undefined') FailureMod=NoticeModeEnum.alert;

	if(jsonString==""){
  	alert('Server not response!');
  	document.location.reload();
	}
	//parse json string to json object
	this.StatusObj=(typeof jsonString=='string')?JSON.parse(jsonString):jsonString;

	this.getStatusObj = function(){
		return this.StatusObj;
	}

	this._response = function(mod,htmlModeSelector){
		switch(mod){
			case NoticeModeEnum.alert:
				alert(this.StatusObj.msg);
				return true;
				break;
			case NoticeModeEnum.confirm:
				return confirm(this.StatusObj.msg);
				break;
			case NoticeModeEnum.console:
				var _msgHandler = new MsgAction('ReturnCodeControler/'+callername);
				_msgHandler.Log(this.StatusObj.msg);
				return true;
				break;
			case NoticeModeEnum.html:
				if(typeof htmlModeSelector=='undefined') htmlModeSelector='#ErrorLog';
				$(htmlModeSelector).html(this.StatusObj.msg+'<br />'+$(htmlModeSelector).html());
				return true;
				break;
			case NoticeModeEnum.none:
			default:
				break;
		}
	}

	this.handling = function(){
		//code=200, return true
		//code!=200, show msg
		//has link, redirect link url
		switch(this.StatusObj.code.substr(0,3)){
			case '200':
				this._response(SuccessMod);
				if(this.StatusObj.link){
					document.location.href=this.StatusObj.link;
				}
				return true;
				break;
			default:
				this._response(FailureMod);
				if(this.StatusObj.link){
					document.location.href=this.StatusObj.link;
				}
				return false;
				break;
		}
	}
}

$(document).ready(function(){
	var api = new APIAction();
  systemEnv.isBackend=(document.location.href.match(/\/backend\//g)!=null);
  systemEnv.isSysBackend=(document.location.href.match(/\/backend\/sys_/g)!=null);
  systemEnv.bsid=(bookEnv.InitJSON.bs)?bookEnv.InitJSON.bs:$.cookie('site_bsid');
  systemEnv.buid=(bookEnv.InitJSON.buid)?bookEnv.InitJSON.buid:$.cookie('buid');
  systemEnv.account=(bookEnv.InitJSON.acc)?bookEnv.InitJSON.acc:$.cookie('acc');
});
