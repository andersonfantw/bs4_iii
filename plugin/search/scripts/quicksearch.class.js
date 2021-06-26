var QuickSearch = function(){
	var qlTemplate = '<div class="col"><div><a href="#" class="del">x</a><a href="/search/list/@id@" class="link w2" data-toggle="tooltip" title="@condition@">@shortname@</a><span>@name@</span></div></div>';
	var isLoad=false;
	var data=null;
	var settings  = {
	};

	function Add(shortname,fullname,kw,pwrf,prt,pi,pcu,pc,pcof,pn,py){
		//add item to db
	}
	function Edit(id,shortname,fullname,kw,pwrf,prt,pi,pcu,pc,pcof,pn,py){
		//edit itme in db
	}
	function Remove(id){
		//remove item from db
	}
	function Load(){
		//get from db to data
	}
}