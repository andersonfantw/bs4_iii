//v1.0
//Copyright 2006 Adobe Systems, Inc. All rights reserved.
function AC_AddExtension(src, ext)
{
  if (src.indexOf('?') != -1)
    return src.replace(/\?/, ext+'?'); 
  else
    return src + ext;
}

function AC_Generateobj(objAttrs, params, embedAttrs) 
{ 
  var str = '<object id="spa" ';
  for (var i in objAttrs)
    str += i + '="' + objAttrs[i] + '" ';
  str += '>';
  for (var i in params)
    str += '<param name="' + i + '" value="' + params[i] + '" /> ';
  str += '<embed id="spn" ';
  for (var i in embedAttrs)
    str += i + '="' + embedAttrs[i] + '" ';
  str += ' ></embed></object>';

  document.write(str);
}

function AC_FL_RunContent(){
  var ret = 
    AC_GetArgs
    (  arguments, ".swf", "movie", "clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
     , "application/x-shockwave-flash"
    );
  AC_Generateobj(ret.objAttrs, ret.params, ret.embedAttrs);
}

function AC_SW_RunContent(){
  var ret = 
    AC_GetArgs
    (  arguments, ".dcr", "src", "clsid:166B1BCA-3F9C-11CF-8075-444553540000"
     , null
    );
  AC_Generateobj(ret.objAttrs, ret.params, ret.embedAttrs);
}

function AC_GetArgs(args, ext, srcParamName, classid, mimeType){
  // FlashVars 用配列
  var fvars;
  var fvars = new Array();
  var ret = new Object();
  ret.embedAttrs = new Object();
  ret.params = new Object();
  ret.objAttrs = new Object();
  for (var i=0; i < args.length; i=i+2){
    var currArg = args[i].toLowerCase();    

    switch (currArg){	
      case "classid":
        break;
      case "pluginspage":
        ret.embedAttrs[args[i]] = args[i+1];
        break;
      case "src":
      case "movie":	
        args[i+1] = AC_AddExtension(args[i+1], ext);
        ret.embedAttrs["src"] = args[i+1];
        ret.params[srcParamName] = args[i+1];
        break;
      case "onafterupdate":
      case "onbeforeupdate":
      case "onblur":
      case "oncellchange":
      case "onclick":
      case "ondblClick":
      case "ondrag":
      case "ondragend":
      case "ondragenter":
      case "ondragleave":
      case "ondragover":
      case "ondrop":
      case "onfinish":
      case "onfocus":
      case "onhelp":
      case "onmousedown":
      case "onmouseup":
      case "onmouseover":
      case "onmousemove":
      case "onmouseout":
      case "onkeypress":
      case "onkeydown":
      case "onkeyup":
      case "onload":
      case "onlosecapture":
      case "onpropertychange":
      case "onreadystatechange":
      case "onrowsdelete":
      case "onrowenter":
      case "onrowexit":
      case "onrowsinserted":
      case "onstart":
      case "onscroll":
      case "onbeforeeditfocus":
      case "onactivate":
      case "onbeforedeactivate":
      case "ondeactivate":
      case "type":
      case "codebase":
        ret.objAttrs[args[i]] = args[i+1];
        break;
      case "flashvars":
        ret.params[args[i]] = "startpage=" + getParameter('startpage') + "&ldbgcolor=" + args[i+1] + "&keyword=" + getParameter('keyword');
        ret.embedAttrs[args[i]] = ret.params[args[i]];
        fvars.push(ret.params[args[i]]);
        break;
      case "width":
      case "height":
        // 本来のステージサイズを保存（パラメータ名の前に 's' を付加してパラメータ名を変更）
        fvars.push('s'+args[i]+'='+args[i+1]);
	initOriginalWindowSize(args[i], parseInt(args[i+1]));
        ret.embedAttrs[args[i]] = ret.objAttrs[args[i]] = args[i+1];
        break;
      case "align":
      case "vspace":
      case "hspace":
      case "class":
      case "title":
      case "accesskey":
      case "name":
      case "id":
      case "tabindex":
        ret.embedAttrs[args[i]] = ret.objAttrs[args[i]] = args[i+1];
        break;
      default:
        ret.embedAttrs[args[i]] = ret.params[args[i]] = args[i+1];
    }
  }
  ret.objAttrs["classid"] = classid;
  if (mimeType) ret.embedAttrs["type"] = mimeType;

  /* ================================================== //
  ;; [EDITION] SANGETSU
  ;; start
  // ================================================== */
//  ret.embedAttrs['width'] = ret.objAttrs['width'] = getBookAreaFitSize('width');
//  ret.embedAttrs['height'] = ret.objAttrs['height'] = getBookAreaFitSize('height');

  // SWF領域の比率を計算
  swfRatio = swfOrgW / swfOrgH;

  // ウィンドウオブジェクトからステージサイズを取得
  initMaximumWindowSize('width', parseInt(ret.objAttrs['width']));
  initMaximumWindowSize('height', parseInt(ret.objAttrs['height']));

  // 配列から FlashVars 文字列を作成
  if (0 < fvars.length) {
    ret.params['FlashVars'] = fvars.join('&');
    ret.embedAttrs['FlashVars'] = ret.params['FlashVars'];
  }

  var diff_w = Math.floor(screen.width - getBookAreaToWindowSize('width'));
  var diff_h = Math.floor(screen.height - getBookAreaToWindowSize('height'));

  ret.embedAttrs['width'] = ret.objAttrs['width'] = getBookAreaFitSize('width');
  ret.embedAttrs['height'] = ret.objAttrs['height'] = getBookAreaFitSize('height');

  //resizeTo(diff_w, diff_h);
  moveTo(Math.floor((screen.width - diff_w) / 2), Math.floor((screen.height - diff_h) / 2));

  /* ================================================== //
  ;; [EDITION] SANGETSU
  ;; end
  // ================================================== */

  return ret;
}

function getParameter( strParamName )
{
	//  input : String, Parameter name from HTTP GET Paramemeter.
	// output : String, Parameter value which is parsed.
	
	var strParamValue = "";
    var objWin = this;
    
    try 
    {
        while ( objWin != null )
        {
    		var strThisURL = objWin.location.href;
    		
            if( -1 != strThisURL.indexOf( strParamName + "=",0 ) )
            {
        		var strParam = strThisURL.substring( strThisURL.indexOf(strParamName + "=",0), strThisURL.length );
        		if ( strParam == null || strParam == "" )		break;
        		var arrParam = strParam.split( "&" );
        		strParamValue = arrParam[0].substring( arrParam[0].indexOf(strParamName + "=",0)+strParamName.length+1, arrParam[0].length );
    	    }
      	    if ( objWin.parent==null || objWin==window.top ) 	break;
    	    else objWin = objWin.parent;
        }
    } 
    catch (e) 
    {
    	alert( "Parameter parsing failure.\nPlease check the frameset of HTML." );
    }
    return strParamValue;
}

var winPopupObj = new Array();
function openPopupWindow (url, name, feature) {
	if (!winPopupObj[name] || winPopupObj[name].closed == true)
	{
		winPopupObj[name] = window.open(url, name, feature);
	}
	else
	{
		winPopupObj[name].location.href = url;
	}
	winPopupObj[name].focus();
}

function openGetFlashPlayerWindow () {
	var winObj = window.open('http://get.adobe.com/jp/flashplayer/', '_blank', 'location=yes,menubar=yes,resizable=yes,scrollbars=yes,status=yes,toolbar=yes');
	winObj.focus();
}

function showHelp () {
	var helpWin = window.open('help.html', 'livebookhelp', 'width=404,height=420,scrollbars=yes');
	if (helpWin != null)
		helpWin.focus();
}

var runMode;
var staMode;
var bzName = window.navigator.appName;
var osName = window.navigator.platform;
var swfOrgW, swfOrgH, swfRatio;
var bodyOrgW, bodyOrgH;
var bodyMaxW, bodyMaxH;
var bookOrgW, bookOrgH;
var bookMaxW, bookMaxH;

function initWindow () {
	runMode = 'initialize';

	bzName = window.navigator.appName;
	osName = window.navigator.platform;

	if (0 <= bzName.indexOf('Microsoft Internet Explorer')) {
		if (0 <= osName.indexOf('Win')) {
			bodyOrgW = document.body.clientWidth;
			bodyOrgH = document.body.clientHeight;
			bookOrgW = document.getElementById('spa').width;
			bookOrgH = document.getElementById('spa').height;
		} else {
			bodyOrgW = window.innerWidth;
			bodyOrgH = window.innerHeight;
			bookOrgW = document.getElementById('spn').width;
			bookOrgH = document.getElementById('spn').height;
		}
	} else {
		bodyOrgW = window.innerWidth;
		bodyOrgH = window.innerHeight;
		bookOrgW = document.getElementById('spn').width;
		bookOrgH = document.getElementById('spn').height;
	}
}

function originalWindow () {
	runMode = 'original';

	if (0 <= bzName.indexOf('Microsoft Internet Explorer')) {
		if (0 <= osName.indexOf('Win')) {
			bodyCurW = document.body.clientWidth;
			bodyCurH = document.body.clientHeight;
		} else {
			bodyCurW = window.innerWidth;
			bodyCurH = window.innerHeight;
		}
	} else {
		bodyCurW = window.innerWidth;
		bodyCurH = window.innerHeight;
	}

	var winsOrgW = bodyCurW - bodyOrgW;
	var winsOrgH = bodyCurH - bodyOrgH;

	resizeBy(-winsOrgW, -winsOrgH);
	moveBy(winsOrgW / 2, winsOrgH / 2);

	if (0 <= bzName.indexOf('Microsoft Internet Explorer')) {
		if (0 <= osName.indexOf('Win')) {
			document.getElementById('spa').width = bookOrgW;
			document.getElementById('spa').height = bookOrgH;
		} else {
			document.getElementById('spn').width = bookOrgW;
			document.getElementById('spn').height = bookOrgH;
		}
	} else {
		document.getElementById('spn').width = bookOrgW;
		document.getElementById('spn').height = bookOrgH;
	}
}

function maximumWindow () {
	runMode = 'maximum';

	moveTo(0,0);
	resizeTo(screen.width, screen.height);

	if (0 <= bzName.indexOf('Microsoft Internet Explorer')) {
		if (0 <= osName.indexOf('Win')) {
			bodyCurW = document.body.clientWidth;
			bodyCurH = document.body.clientHeight;
		} else {
			bodyCurW = window.innerWidth;
			bodyCurH = window.innerHeight;
		}
	} else {
		bodyCurW = window.innerWidth;
		bodyCurH = window.innerHeight;
	}

	bodyCurW = getBookAreaFitSize('width');
	bodyCurH = getBookAreaFitSize('height');

	if (0 <= bzName.indexOf('Microsoft Internet Explorer')) {
		if (0 <= osName.indexOf('Win')) {
			document.getElementById('spa').width = bodyCurW;
			document.getElementById('spa').height = bodyCurH;
		} else {
			document.getElementById('spn').width = bodyCurW;
			document.getElementById('spn').height = bodyCurH;
		}
	} else {
		document.getElementById('spn').width = bodyCurW;
		document.getElementById('spn').height = bodyCurH;
	}
}

function resizeWindow () {
	if (runMode == 'initialize' || runMode == 'normal') {
		runMode = 'resize';
	}

	if (runMode == 'resize') {
		if (0 <= bzName.indexOf('Microsoft Internet Explorer')) {
			if (0 <= osName.indexOf('Win')) {
				bodyCurW = document.body.clientWidth;
				bodyCurH = document.body.clientHeight;
			} else {
				bodyCurW = window.innerWidth;
				bodyCurH = window.innerHeight;
			}
		} else {
			bodyCurW = window.innerWidth;
			bodyCurH = window.innerHeight;
		}

		bodyCurW = getBookAreaFitSize('width');
		bodyCurH = getBookAreaFitSize('height');

		if (0 <= bzName.indexOf('Microsoft Internet Explorer')) {
			if (0 <= osName.indexOf('Win')) {
				document.getElementById('spa').width = bodyCurW;
				document.getElementById('spa').height = bodyCurH;
			} else {
				document.getElementById('spn').width = bodyCurW;

				document.getElementById('spn').height = bodyCurH;
			}
		} else {
			document.getElementById('spn').width = bodyCurW;
			document.getElementById('spn').height = bodyCurH;
		}
	} else {
		runMode = 'normal';
	}
}

function getBookAreaFitSize (key) {
	var num = getBookAreaSize(key, getWindowInnerWith(), getWindowInnerHeight());
	return num;
}

function getBookAreaToWindowSize (key) {
	var area = getBookAreaSize(key, getWindowInnerWith(), getWindowInnerHeight());

	switch (key) {
		case 'width':
			num = getWindowInnerWith() - area;
			break;
		case 'height':
			num = getWindowInnerHeight() - area;
			break;
	}

	return num;
}

function getWindowAreaFitSize (key) {
	var num = getBookAreaSize(key, screen.width, screen.height);
	return num;
}

function getBookAreaSize (key, numW, numH) {
	var numR;

	switch (key) {
		case 'width':
			if (swfRatio < numW / numH) {
				numR = swfRatio * numH;
			} else if (swfRatio > numW / numH) {
				numR = numW;
			} else {
				numR = numW;
			}
			break;
		case 'height':
			if (swfRatio < numW / numH) {
				numR = numH;
			} else if (swfRatio > numW / numH) {
				numR = 1 / swfRatio * numW;
			} else {
				numR = numH;
			}
			break;
	}

	return numR;
}

var otherBookWin;
function book_window_open(path, title) {
    if (!otherBookWin) {
        otherBookWin = window.opener;
    }

    if (otherBookWin.closed) {
        openBrMaximumWindow(path,title);
    } else {
	    if (otherBookWin.name == title) {
	        if (otherBookWin.closed) {
	            openBrMaximumWindow(path,title);
	        } else {
	            //self.blur();
	            otherBookWin.focus();
		}
	    } else {
	        openBrMaximumWindow(path,title);
	    }
    }
}

var innerW, innerH;
var swfW = 1010;
var swfH = 645;
var swfR = swfW / swfH;

function openBrMaximumWindow (theURL,winName) {
	resizeInit = false;

	var bzName = window.navigator.appName;
	var osName = window.navigator.platform;

	var availW = screen.availWidth;
	var availH = screen.availHeight;

	openBrWindow(theURL, winName, availW, availH, 'left=0,top=0,resizable=yes');
}

function resizeFromMaximumWindow () {
	innerW = getWindowInnerWith();
	innerH = getWindowInnerHeight();

	var availW = screen.availWidth;
	var availH = screen.availHeight;
	var availR = availW / availH;

	var pupW = availW * 1;
	var pupH = availH * 1;

	if (swfR <= availR)
	{
		pupH = parseInt(availH * 1);
		pupW = parseInt(swfR * pupH);
	}
	else
	{
		pupW = parseInt(availW * 1);
		pupH = parseInt(pupW / swfR);
	}

	movW = parseInt((availW - pupW) / 2);
	movH = parseInt((availH - pupH) / 2);

	resizeTo(pupW, pupH);
	moveTo(movW, movH);
}

function isResizeComplete () {
	if (getSwfWidth() == getWindowInnerWith() && getSwfHeight() == getWindowInnerHeight())
	{
		if (innerW != getWindowInnerWith() && innerH != getWindowInnerHeight())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		resizeWindow();
	}

	return false;
}

function isStageAdjustComplete () {
	var curSwfW = getSwfWidth();
	var curSwfH = getSwfHeight();
	var curSwfR = curSwfW / curSwfH;

	var t_curSwfR = parseInt(curSwfR * 100);
	var t_swfR = parseInt(swfR * 100)

	if (t_curSwfR < t_swfR)
	{
		dstSwfH = parseInt(swfR / curSwfW);
		difSwfH = dstSwfH - curSwfH;
		resizeBy(0, difSwfH);
		moveBy(0, -1 * parseInt(difSwfH / 2));

		return false;
	}
	else if (t_swfR < t_curSwfR)
	{
		dstSwfW = parseInt(swfR * curSwfH);
		difSwfW = dstSwfW - curSwfW;
		resizeBy(difSwfW, 0);
		moveBy(-1 * parseInt(difSwfW / 2), 0);

		return false;
	}
	else
	{
		return true;
	}
}

function callAlert (msg) {
	alert(msg);
}

function openBrWindow(theURL,winName,popupwidth,popupheight,feature) { //v2.0
  if (navigator.userAgent.indexOf('Safari') >= 0) {
    otherBookWin = window.open(theURL,winName,'width=' + (popupwidth + 1) + ',height=' + (popupheight + 1) + ',' + feature);
  } else {
    otherBookWin = window.open(theURL,winName,'width=' + popupwidth + ',height=' + popupheight + ',' + feature);
  }
}

function getWindowInnerSize (key) {
	switch (key) {
		case 'width':
			return getWindowInnerWith();
			break;
		case 'height':
			return getWindowInnerHeight();
			break;
	}
}

function getWindowInnerWith () {
	var bodyMaxW;

	if (0 <= bzName.indexOf('Microsoft Internet Explorer')) {
		if (0 <= osName.indexOf('Win')) {
			bodyMaxW = document.body.clientWidth;
		} else {
			bodyMaxW = window.innerWidth;
		}
	} else {
		bodyMaxW = window.innerWidth;
	}

	return bodyMaxW;
}

function getWindowInnerHeight () {
	var bodyMaxH;

	if (0 <= bzName.indexOf('Microsoft Internet Explorer')) {
		if (0 <= osName.indexOf('Win')) {
			bodyMaxH = document.body.clientHeight;
		} else {
			bodyMaxH = window.innerHeight;
		}
	} else {
		bodyMaxH = window.innerHeight;
	}

	return bodyMaxH;
}

function getSwfWidth () {
	if (0 <= bzName.indexOf('Microsoft Internet Explorer')) {
		if (0 <= osName.indexOf('Win')) {
			return document.getElementById('spa').width;
		} else {
			return document.getElementById('spn').width;
		}
	} else {
		return document.getElementById('spn').width;
	}
}

function getSwfHeight () {
	if (0 <= bzName.indexOf('Microsoft Internet Explorer')) {
		if (0 <= osName.indexOf('Win')) {
			return document.getElementById('spa').height;
		} else {
			return document.getElementById('spn').height;
		}
	} else {
		return document.getElementById('spn').height;
	}
}

function initOriginalWindowSize (key, num) {

	switch (key) {
		case 'width':
			bodyOrgW = num;
			bookOrgW = num;
			bodyMaxW = num;
			swfOrgW = num;
			break;
		case 'height':
			bodyOrgH = num;
			bookOrgH = num;
			bodyMaxH = num;
			swfOrgH = num;
			break;
	}
}

function initMaximumWindowSize (key, num) {
	switch (key) {
		case 'width':
			bodyMaxW = num;
			break;
		case 'height':
			bodyMaxH = num;
			break;
	}
}
