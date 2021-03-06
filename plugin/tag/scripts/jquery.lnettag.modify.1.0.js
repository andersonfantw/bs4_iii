(function ($) {
$.fn.LnetTagModify = function(options){
	$(this).jstree({
		'core' : {
			'data' : {
				'url' : TagAPIHandler.getAPIurl('getTMList'),
				'data' : function (node) {
					return { 'id' : node.id };
				}
			},
			'check_callback' : function(o, n, p, i, m) {
				if(m && m.dnd && m.pos !== 'i') { return false; }
				if(o === "move_node" || o === "copy_node") {
					if(this.get_node(n).parent === this.get_node(p).id) { return false; }
				}
				return true;
			},
			'themes' : {
				'responsive' : false,
				'variant' : 'small',
				'stripes' : true
			}
		},
		'sort' : function(a, b) {
			return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : (this.get_type(a) >= this.get_type(b) ? 1 : -1);
		},
		'contextmenu' : {
			'items' : function(node) {
				var tmp = $.jstree.defaults.contextmenu.items();
				delete tmp.create.action;
				tmp.create.label = "New";
				tmp.create.submenu = {
					"create_tag" : {
						"separator_after"	: true,
						"label"				: "Tag",
						"action"			: function (data) {
							var inst = $.jstree.reference(data.reference),
							obj = inst.get_node(data.reference);
							inst.create_node(obj, { type : "default" }, "last", function (new_node) {
								setTimeout(function () { inst.edit(new_node); },0);
							});
						}
					}
				};
				if(this.get_type(node) === "file") {
					delete tmp.create;
				}
				return tmp;
			}
		},
		'types' : {
			'default' : { 'icon' : 'folder' },
			'file' : { 'valid_children' : [], 'icon' : 'file' }
		},
		'unique' : {
			'duplicate' : function (name, counter) {
				return name + ' ' + counter;
			}
		},
		'plugins' : ['state','dnd','sort','types','contextmenu','unique']
	}).on('delete_node.jstree', function (e, data) {
		_url = TagAPIHandler.getAPIurl('getTMDelete');
		$.get(_url, { 'id' : data.node.id })
			.fail(function () {
				data.instance.refresh();
			});
	}).on('create_node.jstree', function (e, data) {
/*
		_path = data.node.parent
		_key = 
		_val = data.node.text
		_type = 
		TagAPIHandler.addTag(_path,_key,_val,_type,function(d){
			data.instance.set_id(data.node, d.id);
		});
*/
/*
		$.get('?operation=create_node', { 'type' : data.node.type, 'id' : data.node.parent, 'text' : data.node.text })
			.done(function (d) {
				data.instance.set_id(data.node, d.id);
			})
			.fail(function () {
				data.instance.refresh();
			});
*/
	}).on('rename_node.jstree', function (e, data) {
		_url = TagAPIHandler.getAPIurl('getTMRename');
		$.get(_url, { 'id' : data.node.id, 'text' : data.text })
			.done(function (d) {
				data.instance.set_id(data.node, d.id);
			})
			.fail(function () {
				data.instance.refresh();
			});
	}).on('changed.jstree', function (e, data) {
		if(data && data.selected && data.selected.length) {
			_url = TagAPIHandler.getAPIurl('getTMList');
			$.get(_url+'&id=' + data.selected.join(':'), function (d) {
				if(d && typeof d.type !== 'undefined') {
					$('#data .content').hide();
					switch(d.type) {
						case 'text':
						case 'txt':
						case 'md':
						case 'htaccess':
						case 'log':
						case 'sql':
						case 'php':
						case 'js':
						case 'json':
						case 'css':
						case 'html':
							$('#data .code').show();
							$('#code').val(d.content);
							break;
						case 'png':
						case 'jpg':
						case 'jpeg':
						case 'bmp':
						case 'gif':
							$('#data .image img').one('load', function () { $(this).css({'marginTop':'-' + $(this).height()/2 + 'px','marginLeft':'-' + $(this).width()/2 + 'px'}); }).attr('src',d.content);
							$('#data .image').show();
							break;
						default:
							$('#data .default').html(d.content).show();
							break;
					}
				}
			});
		}else{
			$('#data .content').hide();
			$('#data .default').html('Select a file from the tree.').show();
		}
	});
}
})(jQuery);