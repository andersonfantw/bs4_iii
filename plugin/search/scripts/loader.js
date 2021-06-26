$(document).ready(function(){
	var param={
		path:web_url+'/plugin/search/',
		script:['bootstrap.min','bootstrap-contextmenu','search.class.1.0','jquery.lnetsearch'],
		css:['common','bootstrap.min','component-chosen','Google-Style-Login','Highlight-Blue','scrollbar','jquery.dataTables.min','jquery.chosen.min'],
		langJS:false,
		langCSS:false,
		html:''
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
