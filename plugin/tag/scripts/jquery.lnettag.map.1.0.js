(function ($) {
$.fn.LnetTagMap = function(options){
	var $this = this;
	var settings = {
	};
	var arr_color=[];
	var color=[];
	var index_color=0;
	TagAPIHandler.getTagMap(function(data){
		var db = TAFFY(data.result);
		var t = db({pid:'0'}).get();
		var _c = _calColorNum(t.length);
		n = 255 / (_c-1);
		for(i=0;i<_c;i++){
			arr_color.push(Math.round(i*n));
		}

		color = P(arr_color,3);
		_recurcive(0,0,data.result,$this);
	});
	function _recurcive(_deep,_pid,data,obj){
		++_deep;
		var db = TAFFY(data);
		var t = db({pid:_pid.toString()}).get();
		$(t).each(function(i,v){
			_a = 0.2+_deep *0.01;
			_type = '';
			if(v.type=='1') _type='system';
			var $container = $('<div style="background-color:rgba('+color[index_color][0]+','+color[index_color][1]+','+color[index_color][2]+','+_a+')"></div>');
			var $tag = $('<div id="id'+v.t_id+'" class="tag"><p class="'+_type+'">'+v.t_id+'</p><span>'+v.key+'</span><span>'+v.val+'</span></div>');
			var $subcontainer = $('<div id="container'+v.t_id+'"  class="container last"></div>');
			
			$container.append($tag).append($subcontainer);
			obj.append($container);
	
			_recurcive(_deep,v.t_id,data,$subcontainer);
			obj.parent().parent().removeClass('last');
			if(_pid=='0') ++index_color;
		});
	}
	function _calculate(n){
		//n*(n-1)*(n-2)/6 * 6
		if(n>3){
			return n*(n-1)*(n-2);
		}
	}
	function _calColorNum(n){
		var _m=0;
		var _i=3;
		while(_m<n){
			_i++;
			_m=_calculate(_i);
		}
		return _i;
	}
	function C(arr, num)  
	{  
	    var r=[];  
	    (function f(t,a,n)  
	    {  
	        if (n==0)  
	        {  
	            return r.push(t);  
	        }  
	        for (var i=0,l=a.length; i<=l-n; i++)  
	        {  
	            f(t.concat(a[i]), a.slice(i+1), n-1);  
	        }  
	    })([],arr,num);  
	    return r;  
	}
	function P(arr, num)  
	{  
	    var r=[];  
	    (function f(t,a,n)  
	    {  
	        if (n==0)  
	        {  
	            return r.push(t);  
	        }  
	        for (var i=0,l=a.length; i<l; i++)  
	        {  
	            f(t.concat(a[i]), a.slice(0,i).concat(a.slice(i+1)), n-1);  
	        }  
	    })([],arr,num);  
	    return r;  
	}

};
})(jQuery);