var _get = _getRequest();
if (_get['time']) {
  var dateobj = new Date();
  var nowtime = Math.round(dateobj.getTime() / 1000);
  console.log(_get['time'] + ":" + nowtime);
  if ((nowtime - _get['time']) > 10) {
    window.location.href = 'error.html';
  }
}
function _getRequest(){
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
