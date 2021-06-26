$(document).ready(function(){
	LanguageHandler.setLang();
	initSelector();
});
function initSelector(){
  if(systemEnv.LanguageSetting==''){
      APIHandler.getLanguageSetting();
  }
  $.each(systemEnv.LanguageSetting, function(i){
      $('#lang').append('<option value="'+systemEnv.LanguageSetting[i].key+'"'+((systemEnv.LanguageSetting[i].key==systemEnv.lang)?' selected':'')+'>'+systemEnv.LanguageSetting[i].value+'</option>')
  });
  $('#button .lang select').change(function(){
      //ToolsHandler.setCookie('currentlang',$(this).val(),'','','/');
      window.localStorage['lang'] = $(this).val();
      document.location.reload();
  });
}
function selectlanguage(obj){
	//$.cookie('currentlang',obj.value,{path:'/',expires:7});
	APIHandler.setLang(obj.value,function(data){});
	window.localStorage['lang'] = obj.value;
	document.location.href=document.location.href;
}
