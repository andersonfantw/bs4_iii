(function ($) {
$.fn.chkConvertProgress = function(options){
	var $this = this;
	var settings = {
		progressReload:5000,
		chkHasProgress:60000,
		lnettoken:'',
		reloadlist:function(){}
	};
  if (options) {
      $.extend(settings, options);
  }
	_init();
	function _init(){
		$this.find('div').progressbar({'value':0});
		_hasStart();
	}
	function _hasStart(){
		UploadqueueAPIHandler.chkConvertProgress(settings.lnettoken,function(data){
			if(data.total>0){
				clearTimeout($this._hasstartTimer);
				_progressing();
			}else{
				$this.hide();
				_hasStartTimeout();
			}
			settings.reloadlist();
		});
	}
	function _hasStartTimeout(){
		$this._hasstartTimer = setTimeout(function(){
			_hasStart();
		},settings.chkHasProgress);
	}
	function _progressing(){
		$this._progressTimer = setTimeout(function(){
			UploadqueueAPIHandler.chkConvertProgress(settings.lnettoken,function(data){
				if(data.filename){
					$this.show();
					if($this.data){
						if($this.data.rate==0 && data.rate!=0){
							settings.reloadlist();
						}
					}
					$this.data=data;
					$this.find('span').text(data.filename+' - '+parseInt(data.rate)+'%');
					$this.find('div').progressbar({'value':parseInt(data.rate)});
					_progressing();
				}else{
					if(data.total==0){
						clearTimeout($this._progressTimer);
						$this.find('div').progressbar({'value':100});
						$this.data=null;
						setTimeout(function(){
							$this.hide();
							_hasStart();
						},5000);
					}else{
						setTimeout(function(){
							_hasStart();
						},5000);
					}
				}
			});
		},settings.progressReload);
	}
};
})(jQuery);