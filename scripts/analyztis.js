if(opener){
	if(typeof opener.bookEnv=="object"){
		cls = opener;
	}else if(typeof opener.opener.bookEnv=="object"){
		cls = opener.opener;
	}
	if(cls){
	  _cid = cls.bookEnv.currentCateId;
		_bid = cls.bookEnv.currentBookId;
		cls.AnalytisHandler.Start(_cid,_bid,function(data){
			var timestamp = data.timestamp;
	    setInterval(function(){
				cls.AnalytisHandler.Do(_cid,_bid,timestamp);
		  },60000);
		});
	}
}

//piwik
/*
var _paq = _paq || [];
_paq.push(["setCookieDomain", "*."]);
_paq.push(['trackPageView']);
_paq.push(['enableLinkTracking']);
(function() {
  var u="/plugin/piwik/";
  _paq.push(['setTrackerUrl', u+'piwik.php']);
  _paq.push(['setSiteId', 1]);
  var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
  g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
})();
*/
