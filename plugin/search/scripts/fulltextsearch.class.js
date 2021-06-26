/*
obj = {
	fulltext: 'string',
	pn: 'string',
	year_from: 'thisyear@今年,lastyear@去年,ROC101@101,ROC102@102....',
	year_to: 'thisyear@今年,lastyear@去年,ROC101@101,ROC102@102....',
	pwrf: 'pwrf_04@服務創新,pwrf_01@民生福祉,pwrf_03@綠能科技,pwrf_01@其他',
	prt:
	pi:
	pcu:
	pc:
	pcof:
}
*/
var FulltextSearch ={
	init : function(){
		console.log('init');
	},
	arrControls : function(m){
		switch(m){
			case 'name':
				return ['全文檢索','計畫名稱','領域','報告類型','執行單位','承辦科別','承辦人','經費類別','計畫年度'];
				break;
			case 'chosen':
				return ['pwrf','prt','pi','pcu','pc','pcof','year_from','year_to'];
				break;
			case 'text':
				return ['fulltext','pn'];
				break;
			case 'all':
			default:
				return ['fulltext','pn','pwrf','prt','pi','pcu','pc','pcof','year_from','year_to'];
				break;
		}
	},
	/*getParams : function(){
		//get params from controls
		obj = {name:$('#QuickSearchName').val(),shortname:$('#QuickSearchShortName').val()};
		_arr = this.arrControls('all');
  	for(i=0;i<_arr.length;i++){
  		_v = $('#'+_arr[i]).val();
  		if(_v) obj[_arr[i]] = _v;
  	}
		return obj;
	},
	setParams : function(obj){
		//set params to controls
		$('#QuickSearchName').val(obj.name);
		$('#QuickSearchShortName').val(obj.shortname);
  	for(i=0;i<arrCtl.length;i++){
  		$('#'+arrCtl[i]).val(obj[arrCtl[i]]).trigger("chosen:updated");
  	}
  	$('#fulltext').val(obj['fulltext']);
  	$('#pn').val(obj['pn']);
  	$('#year_from').val(obj['year_from']).trigger("chosen:updated");
  	$('#year_to').val(obj['year_to']).trigger("chosen:updated");
	},*/
	parse : function(_url){
		//get params from url
		obj = {};
		if(_url){
			p1 = _url.split('[@]');
		}else{
			q = $.getQuery('q');
			p1 = q.split('[@]');
		}
		obj.name = decodeURIComponent(p1[0]);
		obj.shortname = decodeURIComponent(p1[1]);
		p2 = p1[2].split('|');
		_arr = this.arrControls('all');
		if(p2.length!=_arr.length){
			console.log('Missing params!');
			return obj;
		}
		for(i=0;i<_arr.length;i++){
			if(p2[i]) obj[_arr[i]] = p2[i];
		}
		return obj;
	},
	toUrl : function(obj){
		_result = [];
		_arrp = [];
		_arr = this.arrControls('all');
		for(i=0;i<_arr.length;i++){
			_v = (obj.hasOwnProperty(_arr[i]))?obj[_arr[i]]:'';
			_arrp.push(_v);
		}
		_v = (obj.hasOwnProperty('name'))?obj.name:'';
		_result.push(_v);
		_v = (obj.hasOwnProperty('shortname'))?obj.shortname:'';
		_result.push(_v);
		_result.push(_arrp.join('|'));
		return  _result.join('[@]');
	},
	toString : function(obj){
		str = '';
		_result = [];
		_arr = this.arrControls('all');
		_arrT = this.arrControls('text');
		_arrN = this.arrControls('name');
		for(i=0;i<_arr.length;i++){
			if(obj.hasOwnProperty(_arr[i])){
				if(_arrT.indexOf(_arr[i])>=0){
					//text
					s = _arrN[i]+': '+decodeURIComponent(obj[_arr[i]])+'<br />';
				}else{
					//chosen
					if(i<_arrN.length){
						_v = obj[_arr[i]].split('@');
						s = _arrN[i]+': '+ decodeURIComponent(_v[1]);
						if(i<_arrN.length-1) s=s+'<br />';
					}else{
						_v = obj[_arr[i]].split('@');
						s = ' ~ '+ decodeURIComponent(_v[1]);
					}
				}
				str = str + s;
			}
		}
console.log(str);
		return str;
	},
	yearToString : function(obj){
		//return ex: 101,102,103...
		thisyear = (new Date()).getFullYear()-1911;
		_from = 101; _to = 0;
		if(obj.hasOwnProperty('year_from')){
			switch(obj['year_from']){
				case 'thisyear':
					_from = thisyear;
					break;
				case 'lastyear':
					_from = thisyear-1;
					break;
			}
		}
		if(obj.hasOwnProperty('year_to')){
			switch(obj['year_to']){
				case 'thisyear':
					_to = thisyear;
					break;
				case 'lastyear':
					_to = thisyear-1;
					break;
			}
		}
		delete obj.year_from;
		delete obj.year_to;
		_years = [];
		if(_to==0){
			_years = _from;
		}else{
			for(i=_from;i<=_to;i++){
				_years.push(i);
			}
		}
		return _years;
	}
}

var KeyValueObject = function(){
	this.k='';
	this.v='';
	this.init = function(){
		this.add('','');
	}
	this.add = function(_key,_val){
		this.k=_key;
		this.v=_val;
	}
	this.isEmpty = function(){
		return (this.k=='' || this.v=='');
	}
	this.parse = function(str){
		if(str){
			_arr0 = str.split('@');
			this.add(_arr0[0],decodeURIComponent(_arr0[1]));
		}
	}
	this.toString = function(){
		return this.k+'@'+this.v;
	}
	this.init();
};

var TagArray = function(_name){
	this.name = _name;
	this.arr = [];

	this.reset = function(){
		this.arr = [];
	}
	this.add = function(kvObject){
		this.arr.push(kvObject);
	}
	this.isEmpty = function(){
		return $.isEmptyObject(this.arr);
	}
	this.getKeys = function(){
		_arr1 = [];
		for(_i=0;_i<this.arr.length;_i++){
			_arr1.push(this.arr[_i].k);
		}
		return _arr1;
	}
	this.getValues = function(){
		_arr1 = [];
		for(_i=0;_i<this.arr.length;_i++){
			_arr1.push(this.arr[_i].v);
		}
		return _arr1;
	}
	this.toString = function(){
		_arr1 = [];
		for(_i=0;_i<this.arr.length;_i++){
			_arr1.push(this.arr[_i].toString());
		}
		return _arr1.join(',');
	}
	this.parse = function(str){
		this.reset();
		_arr1 = str.split(',');
		for(_i=0;_i<_arr1.length;_i++){
			var o = new KeyValueObject();
			o.parse(_arr1[_i]);
			this.arr.push(o);
		}
	}
}

var FulltextConst = {
	controlNames : ['全文檢索','計畫名稱','領域','報告類型','執行單位','承辦科別','承辦人','經費類別','計畫年度'],
	ChosenKeys : ['pwrf','prt','pi','pcu','pc','pcof','year_from','year_to'],
	TextKeys : ['fulltext','pn'],
	AllKeys : ['fulltext','pn','pwrf','prt','pi','pcu','pc','pcof','year_from','year_to']
}

var ParamList = function(){
	this.urlEncode = true;
	this.sid = 0;
	this.name = '';
	this.shortname = '';
	
	this.init = function(){
		for(_j=0;_j<FulltextConst.TextKeys.length;_j++){
			this[FulltextConst.TextKeys[_j]] = '';
		}
		for(_j=0;_j<FulltextConst.ChosenKeys.length;_j++){
			this[FulltextConst.ChosenKeys[_j]] = new TagArray(FulltextConst.ChosenKeys[_j]);
		}
	}
	this.getValue = function(_name){
		if(FulltextConst.ChosenKeys.indexOf(_name)>=0){
			return (this.hasOwnProperty(_name))?this[_name].getValues():[];
		}else{
			return (this.hasOwnProperty(_name))?this[_name]:'';
		}
	}
	this.parse = function(str){
		_str = this.decodeFulltextString(str);
		_arr2 = _str.split('[@]');
console.log(this.urlEncode)
console.log(str);
console.log(_str);
console.log(_arr2);
		this.addID(_arr2[0]);
		this.addName(_arr2[1]);
		this.addShortname(_arr2[2]);
		this.parseTags(_arr2[3]);
	}
	this.parseTags = function(str){
		_arrT = str.split(';');
		if(_arrT.length!=FulltextConst.AllKeys.length){
			console.log('incorrect format!');
			return;
		}
		for(_j=0;_j<_arrT.length;_j++){
			if(_j<FulltextConst.TextKeys.length){
				//text content
				this[FulltextConst.AllKeys[_j]] = _arrT[_j];
			}else{
				//tag content
				if(!this.hasOwnProperty(FulltextConst.AllKeys[_j])){
					this[FulltextConst.AllKeys[_j]] = new TagArray();
				}
				if(_arrT[_j]!='') this[FulltextConst.AllKeys[_j]].parse(_arrT[_j]);
			}
		}
	}
	this.encodeFulltextString = function(str){
		if(this.urlEncode){
			return Base64.encode(str);
		}else{
			return str.replace('&','＆').replace('|','｜');
		}
	}
	this.decodeFulltextString = function(str){
		if(this.urlEncode){
			return Base64.decode(str);
		}else{
			return decodeURIComponent(str).replace('＆','&').replace('｜','|');
		}
	}
	this.addTag = function(_name,_key,_val){
		var o = new KeyValueObject();
		o.add(_key,_val);
		this[_name].add(o);
	}
	this.addText = function(_name, _val){
		this[_name] = _val;
	}
	this.addID = function(_id){
		this['sid'] = _id;
	}
	this.addName = function(_name){
console.log(_name);
		this['name'] = _name;
		this['shortname'] = _name.substr(0,2);
	}
	this.addShortname = function(_name){
		this['shortname'] = _name;
	}
	this.toUrl = function(toServer){
		toServer = toServer || false;
		_arr2 = [];
		for(_j=0;_j<FulltextConst.AllKeys.length;_j++){
			if(_j<FulltextConst.TextKeys.length){
				if(toServer && (this[FulltextConst.AllKeys[_j]]!='')){
					_arr_from = [/  /g,/\( /g,/ \)/g,/ OR /g,/ or /g,/ AND /g,/ and /g,/｜/g,/＆/g,/ \| /g,/ & /g,/ /g];
					_arr_to = [' ','(',')','|','|','&','&','|','&','|','&','&'];
					_str = this[FulltextConst.AllKeys[_j]];
					for(_l=0;_l<_arr_from.length;_l++){
						_str = _str.replace(_arr_from[_l],_arr_to[_l]);
					}
					_arr2[_j] = _str;
				}else{
					_arr2[_j] = this[FulltextConst.AllKeys[_j]];
				}
			}else{
				_v = this.hasOwnProperty(FulltextConst.AllKeys[_j])?this[FulltextConst.AllKeys[_j]].toString():'';
				_arr2[_j] = _v;
			}
		}
		_str = this.sid + '[@]' + this.name + '[@]' + this.shortname + '[@]' + _arr2.join(';');
		return (toServer)?_str:this.encodeFulltextString(_str);
	}
	this.toServer = function(){
		return this.toUrl(true);
	}
	this.toText = function(){
		return this.toHtml(', ');
	}
	this.toHtml = function(sep){
		sep = sep || '<br />';
		_arr2 = [];
		for(_j=0;_j<FulltextConst.AllKeys.length-2;_j++){
			if(_j<FulltextConst.TextKeys.length){
				_v = this[FulltextConst.AllKeys[_j]];
			}else{
				_v = this[FulltextConst.AllKeys[_j]].getValues();
			}
			if(!$.isEmptyObject(_v)){
				_arr2.push(FulltextConst.controlNames[_j]+': '+_v);
			}
		}
		if(!this['year_from'].isEmpty() && !this['year_to'].isEmpty()){
			_arr2.push('計畫年度: '+this['year_from'].getValues()+' ~ '+this['year_to'].getValues());
		}else if(!this['year_from'].isEmpty()){
			_arr2.push('計畫年度: '+this['year_from'].getValues());
		}else if(!this['year_to'].isEmpty()){
			_arr2.push('計畫年度: '+this['year_to'].getValues());
		}
		return _arr2.join(sep);
	}
	this.isEmpty = function(){
		for(_j=0;_j<FulltextConst.AllKeys.length;_j++){
			if(_j<FulltextConst.TextKeys.length){
				_v = this[FulltextConst.AllKeys[_j]];
			}else{
				_v = this[FulltextConst.AllKeys[_j]].getValues();
			}
			if(!$.isEmptyObject(_v)){
				return false;
			}
		}
		return true;
	}

	this.init();
}
