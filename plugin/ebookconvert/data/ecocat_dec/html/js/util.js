// JavaScript Document

function MM_openBrWindow(theURL,winName,popupwidth,popupheight) { //v2.0
  if (navigator.userAgent.indexOf('Safari') >= 0) {
    window.open(theURL,winName,'width=' + (popupwidth + 1) + ',height=' + (popupheight + 1) + ',resizable=yes');
  } else {
    window.open(theURL,winName,'width=' + popupwidth + ',height=' + popupheight + ',resizable=yes');
  }
}

function lastPageMsg () {
	alert('最終ページの為、次のページはありません。');
}
