var synonyms = {
	data:null,
	init : function(){
		SearchAPIHandler.getAllSynonyms(function(data){
			this.data=data;
		});
	}
}