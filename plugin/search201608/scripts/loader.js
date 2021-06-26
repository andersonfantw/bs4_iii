$(document).ready(function(){
	var param={
		path:web_url+'/plugin/search/',
		script:['search.class.1.0','jquery.dataTables.min','jquery.anythingzoomer','jquery.chosen.min','jquery.multiple-select','jquery.lnetsearch'],
		css:['jquery.dataTables.min','anythingzoomer','jquery.chosen.min','jquery.multiple-select','jquery.lnetsearch'],
		langJS:false,
		langCSS:false,
		html:'lnetsearch.html'
	};
	var param_login={
		path:web_url+'/plugin/login/',
		script:['login.class.1.0'],
		css:[],
		langJS:false,
		langCSS:false,
		html:''
	};
	var param_tag={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0'],
		css:['queue'],
		langJS:false,
		langCSS:false,
		html:''
	};
	//loader example
	new Loader([param_tag,param_login,param],function(_panel){
		$('#body').append(_panel);
		$('#body').lnetsearch({defaultScreenSize:'full-screen'});
	});
});
