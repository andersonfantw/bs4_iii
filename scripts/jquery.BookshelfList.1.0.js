(function ($) {
$.fn.BookshelfList = function(options){
	var $this = this;
	var _template='\
<div class="frame @hasbanner@">\
	<div class="top"><a name="bs@bsid@" href="@link@" target="bookshelf"><h2>@bsname@ | @ucname@</h2></a></div>\
	<div class="mid">\
		<table cellpadding="0" cellspacing="0">\
			<tr>\
				<td class="borderleft"></td>\
				<td><a name="bs@bsid@" href="@link@" target="_blank"><img src="@img@" border="0" /></a></td>\
				<td class="borderright"></td>\
			</tr>\
		</table>\
	</div>\
	<div class="bottom"></div>\
	<div class="info">@info@</div>\
</div>';

	var settings = {
		ExpiredList:false,
		buid:-1,
		uid:-1
	};
  if (options) {
      $.extend(settings, options);
  }
	_init();
	function _init(){
		APIHandler.getBookshelfList(settings.ExpiredList,settings.buid,settings.uid,function(data){
			for(i=0;i<data.length;i++){
				var _tmpstr=_template;
				_tmpstr = _tmpstr.replace('@hasbanner@',data[i].hasbanner)
											.replace(/@bsid@/g,data[i].bs_id)
											.replace(/@link@/g,data[i].link)
											.replace(/@info@/g,'')
											.replace(/@bsname@/g,data[i].bs_name)
											.replace(/@ucname@/g,data[i].u_cname)
											.replace(/@img@/g,data[i].img);
				$this.append(_tmpstr);
			}
		});
	}
};
})(jQuery);
