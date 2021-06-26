/*
$.urlParam = function(name){
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
}
*/
$.getQuery = function( query ) {
    query = query.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var expr = "[\\?&]"+query+"=([^&#]*)";
    var regex = new RegExp( expr );
    var results = regex.exec( window.location.href );
    if( results !== null ) {
        return results[1];
        //return decodeURIComponent(results[1].replace(/\+/g, " "));
    } else {
        return 0;
    }
};

var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
var Base64Matcher = new RegExp("^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=|[A-Za-z0-9+/]{4})$");

/*
2E80��33FFh�G�������Ÿ��ϡC���e�d���r�峡���B���������U�����B�`���Ÿ��B�饻���W�B���孵�šA���������Ÿ��B���I�B�a��αa�A�Ť�Ʀr�B����A�H�Τ饻�����W�զX�B���B�~���B����B����B�ɶ����C
3400��4DFFh�G�������{�P��N��r�X�RA�ϡA�`�p���e6,582�Ӥ������~�r�C
4E00��9FFFh�G�������{�P��N��r�ϡA�`�p���e20,902�Ӥ������~�r�C
A000��A4FFh�G�U�ڤ�r�ϡA���e����n���U�ڤ�r�M�r�ڡC
AC00��D7FFh�G��������զX�r�ϡA���e�H���孵�ū�������r�C
F900��FAFFh�G�������ݮe��N��r�ϡA�`�p���e302�Ӥ������~�r�C
FB00��FFFDh�G��r��{�Φ��ϡA���e�զX�ԤB��r�B�ƧB�Ӥ�B���ԧB��B�������������I�B�p�Ÿ��B�b���Ÿ��B�����Ÿ����C
*/
function input_filter_unicode(str){
	//��������r�����h��F��
	var reg = new RegExp("^([\u2E80-\u9FFF\\w ]+)$");
	return reg.test(str);
}
