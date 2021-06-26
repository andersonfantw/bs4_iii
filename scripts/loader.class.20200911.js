/********************************************
Loader class
params
 start with a-zA-Z, defined jquery selector
 start with _, defined lang
 end with _TEMPLATE, defined html
********************************************/
var _regist_js = [];
var _regist_css = [];

Array.isArray = function (obj) {
    return Object.prototype.toString.call(obj) === "[object Array]";
};

var Loader = function(options, returnFunction){
	var _basepath = '/';
	var system_scripts=['json2','cookie','common.class.1.0','bs.class.2.5','taffy-min','lzw','piwik','jquery-ui-1.11.4.custom.min'];
	var system_css=['jquery-ui-1.11.4.custom.min'];

	var default_options={
		path:'',
		script:[],
		css:[],
		langJS:{},
		langCSS:false,
		html:''
	};
	var _css_template = '<link href="@file@" rel="stylesheet" title="style" media="all" />';
	var _js_template = '<script src="@file@" type="text/javascript"></script>';

	_loadScript(system_scripts,_basepath);
	_loadCSS(system_css,_basepath);

	var hasPanelSetting={};

	if(Array.isArray(options)){
		for(j=0;j<options.length;j++){
			_load(options[j]);
		}
		if(!$.isEmptyObject(hasPanelSetting)){
			_loadHTML(hasPanelSetting.path,hasPanelSetting.html,hasPanelSetting.langJS,function(panel){
				if(returnFunction) returnFunction(panel);
			});
		}else{
			if(returnFunction) returnFunction();
		}
	}else{
		_load(options);
	}

	function _load(_options){

		var settings = $.extend( {}, default_options, _options );
		settings.path = (typeof settings.path == 'undefined')?'':settings.path;

		if(settings.css) _loadCSS(settings.css,settings.path);
		if(settings.langCSS) _loadCSS(settings.path);
		if(settings.script) _loadScript(settings.script,settings.path);
		if(!$.isEmptyObject(settings.langJS)) _loadLangJS(settings.path);

		if(Array.isArray(options)){
			if(settings.html){
				hasPanelSetting = settings;
			}
		}else{
			if(settings.html){
				_loadHTML(settings.path,settings.html,settings.langJS,function(panel){
					if(returnFunction) returnFunction(panel);
				});
			}else{
				if(returnFunction) returnFunction();
			}
		}
	}

	function _loadScript(scripts,_path){
		for(i=0;i<scripts.length;i++){
			var _file = _path+'scripts/'+scripts[i]+'.js';
			if($.inArray(_file,_regist_js)===-1){
				_regist_js.push(_file);
				if(document.location.hostname==''){
					$('head').append(_js_template.replace('@file@',_file));
				}else{
		  			$.ajax({
					        url: _file,
		        			type:'GET',
					        dataType: "script",
		        			async:false
					});
				}
			}
		}
  }
	function _loadCSS(css,_path){
		for(i=0;i<css.length;i++){
			var _file = _path+'css/'+css[i]+'.css';
			if($.inArray(_file,_regist_css)===-1){
				_regist_css.push(_file);
				$('head').append(_css_template.replace('@file@',_file));
			}
		}
	}
 	function _loadLangJS(_path,returnFunction){
 		//var _lang = $.cookie("currentlang");
		var _lang = LanguageHandler.getLang();
 		var _file = _path+'scripts/'+_lang+'.js';
		if(document.location.hostname==''){
			$('head').append(_js_template.replace('@file@',_file));
		}else{
	    $.ajax({
	      url: _path+'languages/'+_lang+'.js',
	      type:'GET',
	      dataType: "script",
	      async:false,
	      success:function(){
	      }
	    });
		}
  }
  function _loadLangCSS(_path){
  	//var _lang = $.cookie("currentlang");
	var _lang = LanguageHandler.getLang();
  	var _file = _path+'css/'+_lang+'.css';
  	if(document.location.hostname==''){
			$('head').append(_css_template.replace('@file@',_file));
		}else{
	    $.ajax({
	      url: _path+'languages/'+_lang+'.css',
	      type:'GET',
	      dataType: "script",
	      async:false
	    });
	  }
  }
  function _loadHTML(_path,_html,_langJS,returnFunction){
  	$.get(_path+_html, function(panel) {
			if(_langJS){
	  		for(var p in _langJS){
	  			panel = panel.replace(p,_langJS[p]);
	  		}
			}
  		if(returnFunction) returnFunction(panel);
		},"html");
  }
}
