var EBOOKCONVERT_BUTTON='#userconvert';
var EBOOKCONVERT_SKIN_SELECTOR='#convert_skin_selector';
var EBOOKCONVERT_SKIN_ITEM='#convert_skin_selector>ul>li';
var EBOOKCONVERT_SKIN_SELECTED='#convert_skin_selected';
var EBOOKCONVERT_SPELL_SELECTED='#convert_skin_selected #spell';

var EBOOKCONVERT_CONTAINER='#convert';
var EBOOKCONVERT_TITLE='#convert > h3';
var EBOOKCONVERT_MESSAGE='#convert > span';
var EBOOKCONVERT_TALK='#convert .talkbox .mwt_border > div';
var EBOOKCONVERT_DROPBOX='#dropbox';
var EBOOKCONVERT_UPLOAD='#upload';
var EBOOKCONVERT_PROGRESS_CONTAINER='#upload > div';
var EBOOKCONVERT_FILEINPUT='#convert>input:file';
var EBOOKCONVERT_ICON_CONTAINER='#convert > div:first';
var EBOOKCONVERT_ICONS='.icon';
var EBOOKCONVERT_ALLOWTYPE='#convert_allowfiletype';
var EBOOKCONVERT_UPLOAD_OBJECT='#dropbox, #convert input';

var EBOOKCONVERT_TALKBOX_CONTAINER='.talkbox';
var EBOOKCONVERT_TALKBOX_AGREE_BUTTON='.talkbox .agree';
var EBOOKCONVERT_TALKBOX_CANCEL_BUTTON='.talkbox .cancel';
var EBOOKCONVERT_TALKBOX_MSG='.mwt_border > div';

var EBOOKCONVERT_SKIN_TEMPLATE='<li><input class="skinname" type="hidden" /><input class="skinimg" type="hidden" /></li>';
var EBOOKCONVERT_STATUSICON_TEMPLATE='<div class="icon"><div><div><div></div></div>@str</div></div>';
var EBOOKCONVERT_ALLOWTYPE_HIDDENINPUT='<input id="convert_allowfiletype" type="hidden" value="@str">';
var EBOOKCONVERT_TALKBOX_BUTTON_TEMPLATE='<a class="btn agree" href="/api/redirect.php?cmd=@cmd">@agree</a> | <a class="btn cancel" href="javascript:;">@cancel</a>';

$(document).ready(function(){
	var params={
		path:web_url+'/plugin/ebookconvert/',
		script:['jquery-ui-1.11.4.custom.min','convert.class','jquery.html5uploader','html5uploader','ebookconvert'],
		css:['jquery-ui-1.11.4.custom.min','ebookconvert'],
		langJS:{'@title':'_convert_h3','@msg':'_convert_span','@talk':'_convert_talkbox_mwt_border_div','@catename@':'_catename'},
		langCSS:false,
		html:'ebook_assistant.html'
	};

	//loader example
	new Loader(params,function(panel){
		$('#body').prepend(panel);
		new EBookConvertAction();
	});
});
