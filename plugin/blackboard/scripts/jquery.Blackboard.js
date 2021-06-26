/****************************************************************
1. Multi sources
2. static message, and main message
3. typeSpeed
4. startDelay
5. endDelay
6. backDelay
7. loop
8. showAnchor
9. anchorClick
10. startPlay
11. endPlay
****************************************************************/
$.widget('ttii.Blackboard',{
	/* source: [{
			type:'',
			info:'',
			msg:'',
			link:'',
			admissiontime:(timestamp),
			starttime:(timestamp),
			endtime:(timestamp)
		}]
	*/
	options : {
		sources:[],
		sourceReload:600000,
		typeSpeed:100,
		startDelay:0,
		backDelay:0,
		loop:false,
		showAnchor:true,
		anchorClick:function(){},
		startPlay:function(){},
		endPlay:function(){}
	},
	play: function(){
		this._trigger('beforePlay');
		this._stop=false;
		this._createClassroomIcon();
		this._play(0);
	},
	_create: function(){
		this._blackboard_frame = '.blackboard';
		this._blackboard_classroom = '.classroom';
		this._blackboard_class_info = '.class_info';
		this._blackboard_msg = '#msg';
		this._template_frame = "\
			<div class='classroom'></div>\
			<div class='class_info'></div>\
			<div class='msg'></div>";

		this.sources=[];		//[static, main, link]
		this._currentAnchorIndex=-1;
		this._stop=false;
		this._t=0;
		this._isSetTimer=false;
	},
	_init: function(){
		this._timer();
		if(this._currentAnchorIndex==-1) this._currentAnchorIndex=0;
	},
	_timer: function(){
		if(!this._isSetTimer && this.options.sourceReload){
			this._isSetTimer=true;
			thisw = this;
			setInterval(function(){
				thisw.emptySource();
				thisw._trigger('setReloadSource');
				thisw._createClassroomIcon();
				},
				this.options.sourceReload
			);
		}
	},
	hide: function(){
		$(this).each(function(i){
			$(this)[i].element.hide();
		});
	},
	show: function(){
		$(this).each(function(i){
			$(this)[i].element.show();
		});
	},
	_clearClassroomIcon: function(){
		this.element.find(this._blackboard_classroom).empty();
	},
	_createClassroomIcon: function(){
		if(this.options.showAnchor){
			this._clearClassroomIcon();
			thisw = this;
			for(i=0;i<this.options.sources.length;i++){
				$obj = $('<a>'+(i+1)+'</a>');
				$obj.click(function(){
					var _index = parseInt($(this).html())-1;
					thisw._onAnchorClick(_index);
				});
				this.element.find(this._blackboard_classroom).append($obj);
			}
		}
	},
	destroy: function() {
		this.hide();
		this.stop();
		this.emptySource();
	},
	addSource: function(obj){
		switch(typeof obj){
			case 'function':
				var thisw=this;
				obj.call(this,function(data){
					if(!$.isEmptyObject(data)){
						thisw.options.sources = thisw.options.sources.concat(data);
					}
				});
				break;
			case 'object':
				if(!$.isEmptyObject(data)){
					if($.isArray(obj)){
						this.options.sources = this.options.sources.concat(data);
					}else{
						this.options.sources = this.options.sources.concat([data]);
					}
				}
				break;
		}
	},
	emptySource: function(){
		this.options.sources=[];
	},
	_play: function(_index){
		if(!this._stop){
			if(this.options.sources.length==0 && !this.options.loop){
				this.stop();
				this._currentAnchorIndex=-1;
			}
			if(_index<this.options.sources.length){
				var s = this._replaceParams(this.options.sources[_index]);
				if(s.isShow){
					this.element.find(this._blackboard_msg).typed('reset');
					this.element.find(this._blackboard_class_info).html(s.info);
					this.element.find(this._blackboard_msg).click(function(){
						window.open(s.link);
					});
					thisw = this;
					msg = s.msg.trim().replace(/! /g,'!^400 ') + '^2000';
					this.element.find(this._blackboard_msg).typed({
						strings: [msg],
						contentType: 'text',
						loop: false,
						typeSpeed: this.options.typeSpeed,
						startDelay: this.options.startDelay,
						backDelay: this.options.backDelay,
						showCursor: false,
						callback: function(){
							thisw._currentAnchorIndex++;
							thisw._play(thisw._currentAnchorIndex);
						}
					});
				}else{
					//popup expired class
					this.options.sources.splice(_index,1);
					this._play(this._currentAnchorIndex);
				}
			}else{
				this._currentAnchorIndex=0;
				this._play(this._currentAnchorIndex);
			}
		}
	},
	_replaceParams: function(data){
		var _isShow=false;
		if(data.admissiontime && data.starttime && data.endtime){
			var _admissiontime= Date.parse(data.admissiontime);
			var _start= Date.parse(data.starttime);
			var _end= Date.parse(data.endtime);
			var _now = Date.now();

			_info = data.info;
			if(_now < _admissiontime){
					_msg='';
			}else if(_now > _admissiontime && _now < _start){
				_msg = data.msg.replace('@min@',Math.floor((_start-_now)/60000));
				_isShow = true;
			}else if(_now > _start && _now < _end){
				_msg = data.msg.replace('@min@',Math.floor((_end-_now)/60000));
				_isShow = true;
			}
		}else{
			_info = data.info;
			_msg = data.msg;
		}
		return {
			info:_info,
			msg: _msg,
			link: data.link,
			isShow: _isShow
		};
	},
	_onAnchorClick: function(_index){
		if(this._t) clearTimeout(this._t);
		this.element.find(this._blackboard_msg).typed('reset');

		this._currentAnchorIndex=_index;
		var s = this._replaceParams(this.options.sources[_index]);
		this.element.find(this._blackboard_frame).find(this._blackboard_class_info).html(s.info);
		this.element.find(this._blackboard_frame).find(this._blackboard_msg).html(s.msg);
		this.element.find(this._blackboard_msg).click(function(){
			window.open(s.link);
		});
		thisw = this;
		this._t = setTimeout(function(){
			thisw._currentAnchorIndex++;
			thisw._play(this._currentAnchorIndex);
		},thisw.options.backDelay+2000);
	},
	_startPlay: function(){
		this.options.startPlay();
	},
	_endPlay: function(){
		this.options.endPlay();
	},
	stop: function(){
		this._stop=true;
		this.hide();
		this._clearClassroomIcon();
		$(this).find(this._blackboard_class_info).html('');
		$(this).find(this._blackboard_msg).typed('reset');
		this._trigger('stopCallback');
	}
});