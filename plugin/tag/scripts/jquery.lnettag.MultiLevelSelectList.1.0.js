$.widget( "ttii.MultiLevelSelectList",{
	options: {
		multiple:false,
		ParentKey:'',
		html:'',
		addButton:'.add',
		resetButton:'.reset',
		PlaceHolder:'.placeholder',
		SelectorPanel:'.SelectorPanel',
		value: ''
	},
	_template: {
		html:`<div class="ControlPanel">
<input type="hidden" class="selectedHolder" name="selectedHolder">
<div class="placeholder"></div>
<button class="add">新增</button>
<button class="reset">重設</button>
</div>
<div class="SelectorPanel" style="display: none;"><ul></ul></div>`,
		multilevelselectlist_item:'<li><a>@val@</a></li>',
		selectedholder_item:'<span class="axis" title="axis">@tag@</span>'
	},
	_init: function(){
		this.val = [];
		this.tree = [];
		this.ControlPanel = '.ControlPanel';
		this.SelectedHolder = '.selectedHolder';
		this.SelectorPanel = '.SelectorPanel';
		this.PlaceHolder = '.placeholder';
		this.addButton = '.add';
		this.resetButton = '.reset';
		
		_template = (this.options.html=='')?this._template.html:this.options.html;
		this.element.append(_template);
		if(this.options.addButton!=this.addButton){
			this.element.find(this.addButton).remove();
			this.$addButton = $(this.options.addButton);
		}else{
			this.$addButton = this.element.find(this.options.addButton);
		}
		if(this.options.resetButton!=this.resetButton){
			this.element.find(this.resetButton).remove();
			this.$resetButton = $(this.options.resetButton);
		}else{
			this.$resetButton = this.element.find(this.options.resetButton);
		}
		if(this.options.PlaceHolder!=this.PlaceHolder){
			this.element.find(this.PlaceHolder).remove();
			this.$PlaceHolder = $(this.options.PlaceHolder);
		}else{
			this.$PlaceHolder = this.element.find(this.options.PlaceHolder);
		}
		if(this.options.SelectorPanel!=this.SelectorPanel){
			this.element.find(this.SelectorPanel).remove();
			this.$SelectorPanel = $(this.options.SelectorPanel).append('<ul></ul>');
		}else{
			this.$SelectorPanel = this.element.find(this.options.SelectorPanel);
		}

		this.$PlaceHolder.mouseenter(this,function(e){
			e.data.$PlaceHolder.css('overflow','visible');
		});
		this.$PlaceHolder.mouseleave(this,function(e){
			e.data.$PlaceHolder.css('overflow','hidden');
		});
		this.$SelectorPanel.mouseleave(this,function(e){
			e.data.$SelectorPanel.hide();
			e.data.$SelectorPanel.find('ul ul').hide();
		});
		this.$addButton.click(this,function(e){
			_k = (e.data.options.ParentKey=='')?'root':e.data.options.ParentKey;
			data1 = e.data.tree[_k];
			if(data1){
				e.data.$SelectorPanel.find('ul *').remove();
				for(i=0;i<data1.length;i++){
					e.data._getTags(data1[i],e.data.$SelectorPanel);
				}
			}else{
				TagAPIHandler.getTagsByPKey(e.data.options.ParentKey,function(data){
					e.data.tree[_k] = data;
					for(i=0;i<data.length;i++){
						e.data._getTags(data[i],e.data.$SelectorPanel);
					}
				});
			}
			e.data.$SelectorPanel.find('ul:first').show();
			if(e.data.$SelectorPanel.is(':hidden')){
				e.data.$SelectorPanel.show();
			}else{
				e.data.$SelectorPanel.hide();
			}
		});
		this.$resetButton.click(this,function(e){
			e.data.element.find(e.data.SelectedHolder).val('');
			e.data.element.find(e.data.PlaceHolder+'>*').remove();
			e.data.options.value='';
			e.data.val=[];
		});
	},
	_getTags: function(d,$item){
		_item = this._template.multilevelselectlist_item.replace('@val@',d.val);
		$obj = $(_item);
		$obj.mouseenter(this,function(e){
			$obj1 = $(this);
			$obj1.find('ul').remove();
			$obj1.append('<ul></ul>');
			data1 = e.data.tree[d.key];
			if(data1){
				for(j=0;j<data1.length;j++){
					e.data._getTags(data1[j],$obj1);
				}				
			}else{
				TagAPIHandler.getTagsByPKey(d.key,function(data1){
					e.data.tree[d.key] = data1;
					for(j=0;j<data1.length;j++){
						e.data._getTags(data1[j],$obj1);
					}
				});
			}
		});
		$obj.click(this,function(e){
			k=d.key;
			v=d.val;
			tid=d.t_id;
			if(!e.data.val[k]){
				e.data.val[k]=v;
				_str='';
				for(var _k in e.data.val){
					_str+=','+_k+':'+e.data.val[_k];
				}
				_ParentKey = (e.data.options.ParentKey=='')?'root':e.data.options.ParentKey;
				_str = _ParentKey+'='+_str.substring(1);
				e.data.options.value=_str;
				e.data.element.find(e.data.SelectedHolder).val(e.data.options.value);
	
				//e.data.element.find(e.data.PlaceHolder).val(k+':'+v+':'+tid);
				_item = e.data._template.selectedholder_item.replace('@tag@',k+':'+v);
				if(!e.data.options.multiple){
					e.data.val=[];
					e.data.element.find(e.data.options.PlaceHolder+'>*').remove();
				}
				e.data.element.find(e.data.options.PlaceHolder).append(_item);
			}
			e.stopPropagation();
		});
		$item.find('ul').append($obj);
	}
});