(function ($) {
$.fn.LnetTagShortcut = function(options){
	var $this = this;

	var settings = {
		max:-1
	}
  if (options) {
      $.extend(settings, options);
  }
  
  TagAPIHandler.getShortcutList(uid,bs_id,function(data){
  	for(var i in data.result){
			_tpl = data.result[i].img_html;
  		var $shortcut = $(_tpl).clone();
  		$shortcut.click(function(){
  			tsid = $(this).attr('data-id');
  			TagAPIHandler.getBooksByTSID(bs_id,tsid,uid,function(data){
  				BooksHandler.bindBookshelf([],data.result,data.name);
  				bookEnv.viewBookshelfMode();
  			});
  		});
  		$this.prepend($shortcut);
  	}
  });

}
})(jQuery);