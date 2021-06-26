//js版との切り分け
function judge_sp(){

    var user_agent = "";
    user_agent = navigator.userAgent.toLowerCase();

    if(user_agent.indexOf('iphone') != -1 ||
        user_agent.indexOf('iPhone') != -1 ||
        user_agent.indexOf('ipad') != -1 ||
        user_agent.indexOf('iPad') != -1 ||
        user_agent.indexOf('android') != -1){

        var get = getRequest();
        var p = (get["startpage"]) ? parseInt(get["startpage"]) : 0;
        	
        //var hrefStr = "html5/index.html?startpage="+p;
        var hrefStr = "html5/";
        var b = (get["bid"]) ? get["bid"] : "";
        if (get["bid"])
        {
            hrefStr += "&bid="+b;
            hrefStr += "&s=0";
        }
        
        location.href = hrefStr;

    }else{

    }

}

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
