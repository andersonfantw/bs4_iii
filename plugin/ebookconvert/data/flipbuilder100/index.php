<?PHP
session_start();
if(!isset($_SESSION['uid'])){
        echo 'Please open book from bookshelf!';exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
    version="XHTML+RDFa 1.0"
    xmlns:og="http://ogp.me/ns#"
    xml:lang="en">
<!-- 
    Smart developers always View Source. 
    
    This application was built using Adobe Flex, an open source framework
    for building rich Internet applications that get delivered via the
    Flash Player or to desktops via Adobe AIR. 
    
    Learn more about Flex at http://flex.org 
    // -->
<head>
<meta http-equiv="Content-Type" content=
"text/html; charset=utf-8" />
<title>410</title>
<meta name="Keywords" content="" />
<meta name="Description" content="410" />
<meta name="Generator" content="Flip PDF Professional 2.2.10  at http://www.flipbuilder.com" />
<meta name="medium" content="video"/> 

<meta property="og:image" content="files/shot.png"/>
<meta property="og:title" content="410"/> 
<meta property="og:description" content="410" />
<meta property="og:video" content="book.swf"/> 
<meta property="og:video:height" content="300"/> 
<meta property="og:video:width" content="420"/> 
<meta property="og:video:type" content="application/x-shockwave-flash"/> 

<meta name="video_height" content="300"/> 
<meta name="video_width" content="420"/> 
<meta name="video_type" content="application/x-shockwave-flash"/> 
<meta name="og:image" content="files/shot.png"/>

<link rel="image_src" href="files/shot.png"/>
<!-- Include CSS to eliminate any default margins/padding and set the height of the html element and 
       the body element to 100%, because Firefox, or any Gecko based browser, interprets percentage as 
    the percentage of the height of its parent container, which has to be set explicitly.  Initially, 
    don't display flashContent div so it won't show if JavaScript disabled.
  -->

<style type="text/css" media="screen">
/*<![CDATA[*/
html,
body
{
 height:100%;
}

body
{
 margin:0;
 padding:0;
 overflow:auto;
 text-align:center;
 background-color: #ffffff;
}

#flashContent
{
 display:none;
}
/*]]>*/
</style>
<script type="text/javascript" src="js/swfobject.js"></script>
<script src="/scripts/analyztis.js" type="text/javascript"></script>
<script type="text/javascript">
	
			

//<![CDATA[
					 var ua = navigator.userAgent.toLowerCase(),
		platform = navigator.platform.toLowerCase(),
		UA = ua.match(/(opera|ie|firefox|chrome|version)[\s\/:]([\w\d\.]+)?.*?(safari|version[\s\/:]([\w\d\.]+)|$)/) || [null, 'unknown', 0],
		mode = UA[1] == 'ie' && document.documentMode;
		var sUserAgent=ua;		
		var BR = {
				extend: Function.prototype.extend,
				name: (UA[1] == 'version') ? UA[3] : UA[1],
				version: mode || parseFloat((UA[1] == 'opera' && UA[4]) ? UA[4] : UA[2]),
				Platform: {
					name: ua.match(/ip(?:ad|od|hone)/) ? 'ios' : (ua.match(/(?:webos|android|bada|symbian|palm|blackberry)/) || platform.match(/mac|win|linux/) || ['other'])[0]
				},
				Features: {
					xpath: !!(document.evaluate),
					air: !!(window.runtime),
					query: !!(document.querySelector),
					json: !!(window.JSON)
				},
				Plugins: {},
				isPad : /pad/.test(sUserAgent) || /ipod/.test(sUserAgent), 
				isIPhone : /iphone/.test(sUserAgent),
				isWinPhone : /wpdesktop/.test(sUserAgent),
				isBlackBerry:/blackberry/.test(sUserAgent),
				//isMobile : /mobile/.test(sUserAgent) || /phone/.test(sUserAgent)
				isMobile :(function() {
									  var check = false;
									  (function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
									  return check||/mobile/.test(sUserAgent) || /phone/.test(sUserAgent);
									})()
			};
			
			
			
			//This function is invoked by SWFObject once the <object> has been created
var callback = function (e){ 
    //Only execute if SWFObject embed was successful
    if(!e.success || !e.ref){    	
    //	alert("No Flash Supported");
    	if('flipbuilder_hasMobile'=='flipbuilder_'+'hasMobile'){
    		delayToShowVersion("mobile/index.html")
    		// window.location = "mobile/index.html";
    	}else if(hasBasicHtml){
    		
    		delayToShowVersion("files/basic-html/index.html");
    	}else{//no sutable fla
    		
    	}
    	// setTimeout("shutDownLoader()", 1000);
    	return false; 
    } 
  
};

				
      // BR.Platform.name ='ios';     
    //    if(BR.Platform.name == 'ios'||BR.Platform.name == 'android') window.location = "mobile/index.html";
     //   if( BR.Platform.name == 'webos' || BR.Platform.name == 'bada' || BR.Platform.name == 'symbian' || BR.Platform.name == 'palm' || BR.Platform.name == 'blackberry') window.location = dir+"assets/seo/page1.html";
		


          function getURLParam(strParamName){
       var strReturn = "";
      var strHref = window.location.href;
       if ( strHref.indexOf("?") > -1 ){
       var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
       var aQueryString = strQueryString.split("&");
       for ( var iParam = 0; iParam < aQueryString.length; iParam++ ){
       if (  aQueryString[iParam].indexOf(strParamName.toLowerCase() + "=") > -1 ){
       var aParam = aQueryString[iParam].split("=");
       strReturn = aParam[1];
       break;
       }
       }
       }
       return unescape(strReturn);
     } 
     var pageIndex=getURLParam('pageIndex');
     if(!pageIndex){
      pageIndex=-1;
     }
     var alwaysShowBookMark=getURLParam('alwaysShowBookMark');
      if(!alwaysShowBookMark){
       alwaysShowBookMark=false;
     }
     var alwaysShowThumbnails=getURLParam('alwaysShowThumbnails');
      if(!alwaysShowThumbnails){
       alwaysShowThumbnails=false;
     }
     var alwaysMinimeStyle=getURLParam('alwaysMinimeStyle');
      if(!alwaysMinimeStyle){
       alwaysMinimeStyle=false;
     }
     var currentHTMLURL= window.encodeURIComponent(window.location.href);
      if(!currentHTMLURL){
       currentHTMLURL='';
     }
     
     var pice=getURLParam('pice');
      if(!pice){
       pice=false;
     }
     var lang=getURLParam('lang');
      if(!lang){
       lang="NONE";
     }
     var itemValues=getURLParam('itemValues');
      if(!itemValues){
       itemValues="NONE";
     }
   
   
     
     
         
         
            <!-- For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. --> 
            var swfVersionStr = "10.0.0";
            <!-- To use express install, set to playerProductInstall.swf, otherwise the empty string. -->
            var xiSwfUrlStr = "";
            var flashvars = {pageIndex:pageIndex,alwaysShowBookMark:alwaysShowBookMark,alwaysShowThumbnails:alwaysShowThumbnails,
            alwaysMinimeStyle:alwaysMinimeStyle,currentHTMLURL:currentHTMLURL,pice:pice,lang:lang,itemValues:itemValues};
            var params = {};
            params.quality = "high";
            params.bgcolor = "#ffffff";
            params.allowscriptaccess = "always";
            params.allowfullscreen = "true"; 
            params.allowFullScreenInteractive = "true";
            params.wmode = "transparent";          
            var attributes = {};
            attributes.id = "FlipBookBuilder";
            attributes.name = "FlipBookBuilder";
            attributes.align = "middle";
            
            
var hasMobileVersion=true;
var hasBasicHtmlVersion=true;

var callback = function (e){ 
    //Only execute if SWFObject embed was successful
  if(!e.success || !e.ref){ //	alert("No Flash Supported");
    if(hasMobileVersion){
    	var pageIndex=getURLParam('pageIndex');
		  if(!pageIndex){
		      window.location = "mobile/index.html"+(window.location.hash?window.location.hash:'');
		   }else{
		   		window.location = "mobile/index.html#p="+pageIndex;
		  	}
	 		
		}else if(hasBasicHtmlVersion){
			 window.location ='files/basic-html/index.html';
		}else{
			document.write("Sorry,need flash player. <a href='http://www.adobe.com/go/getflashplayer'>Get Adobe Flash Player<\/a> it's possible to <a id='linkSEO' href='files/basic-html/index.html'>view a simplified version of the book on any device</a>, or you can view the mobile version <a href='mobile/index.html'> here </a>" ); 
		} 
    return false; 
  }  
};

var forceOld=false;
function detectAndGoVersion(){
	if(forceOld){
		swfobject.embedSWF(
                "book.swf", "flashContent", 
                "100%", "100%", 
                swfVersionStr, xiSwfUrlStr, 
                flashvars, params, attributes);
    swfobject.createCSS("#flashContent", "display:block;text-align:left;");
   	return;
	}	
	var pageIndex=getURLParam('pageIndex');
  if(!pageIndex){
      pageIndex=-1;
   }
	if(BR.isMobile){
		if(hasMobileVersion){
			
	 		if(pageIndex!=-1){
	 			window.location = "mobile/index.html#p="+pageIndex;
	 		}else{
	 			window.location = "mobile/index.html"+(window.location.hash?window.location.hash:'');
	 			//window.location = "mobile/index.html";
	 		}
		}else if(hasBasicHtmlVersion){
			 window.location ='files/basic-html/index.html';
		}else {
			 swfobject.embedSWF(
                "book.swf", "flashContent", 
                "100%", "100%", 
                swfVersionStr, xiSwfUrlStr, 
                flashvars, params, attributes,callback);
        swfobject.createCSS("#flashContent", "display:block;text-align:left;");
		} 
	}else{
		swfobject.embedSWF(
                "book.swf", "flashContent", 
                "100%", "100%", 
                swfVersionStr, xiSwfUrlStr, 
                flashvars, params, attributes,callback);
    swfobject.createCSS("#flashContent", "display:block;text-align:left;");
	}	 
}
detectAndGoVersion();

//]]>
</script>
</head>
<body>
<!-- SWFObject's dynamic embed method replaces this alternative HTML content with Flash content when enough 
    JavaScript and Flash plug-in support is available. The div is initially hidden so that it doesn't show
    when JavaScript is disabled.
  -->
  
<div id="flashContent">
<p>To view this page ensure that Adobe Flash Player version 10.0.0
or greater is installed.</p>
<script type="text/javascript">
//<![CDATA[
 
    var pageHost = ((document.location.protocol == "https:") ? "https://" : "http://"); 
    document.write("<a href='http://www.adobe.com/go/getflashplayer'>Get Adobe Flash Player<\/a> <br/> <br/> Besides, it's possible to <a id='linkSEO' href='./files/basic-html/index.html'>view a simplified version of the book on any device</a>, or you can view the mobile version <a href='mobile/index.html'> here </a>" ); 
//]]>
</script></div>
<noscript><div><object classid=
"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height=
"100%" id="FlipBookBuilder"><param name="movie" value="book.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<param name="allowScriptAccess" value="always" />
<param name="allowFullScreen" value="true" />
<param name="allowFullScreenInteractive" value="true" />
<!--[if !IE]>-->
<object type="application/x-shockwave-flash" data="book.swf" width=
"100%" height="100%"><param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<param name="allowScriptAccess" value="always" />
<param name="allowFullScreen" value="true" />
<param name="allowFullScreenInteractive" value="true" />
<param name="wmode" value="transparent" />
<!--<![endif]--><!--[if gte IE 6]>-->
<p>Either scripts and active content are not permitted to run or
Adobe Flash Player version 10.0.0 or greater is not installed.</p>
<!--<![endif]--> 
<a href="http://www.adobe.com/go/getflashplayer">Get Adobe Flash Player</a> <br/> <br/>

Besides, it's possible to <a id="linkSEO" href="./files/basic-html/index.html">view a simplified version of the book on any device</a>,
  or you can view the mobile version <a href='mobile/index.html'> here </a>
<!--[if !IE]>--></object> <!--<![endif]--></object></div></noscript>


  
</body>
</html>
