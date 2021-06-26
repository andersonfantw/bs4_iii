/*$(document).ready(function(){
  var json={
  data:[
    ["id","subcate","name","description","cover","open_link","download_link","order","visible"],
		["8","2","test3",'',"book.jpg","http://127.0.0.1:20038/hosts/1/3/files/1636141411610220/test.php",'',"1","1"],
		['',"2","test4",'',"book1.jpg","http://127.0.0.1:20038/hosts/1/3/files/1636141411610220/test.php",'',"1","1"]
  ],
  report:[
		{row: 1, col: 4, comment:"File not exists"}
	]};

	report_mapping = [];
	for(i=0;i<json.report.length;i++){
		key = json.report[i].row+'_'+json.report[i].col;
		report_mapping[key] = json.report[i].comment;
	}

	setHandsontable(json);
});*/

function setHandsontable(json){
  Handsontable.renderers.registerRenderer('reportValueRenderer', reportValueRenderer); //maps function to lookup string

  var container = $("#convert");
  container.html('');
  container.addClass('handson');
  container.parent().append('<a href="javascript:;" class="btn ui-state-default" onclick="document.location.reload()">upload again</a>');
  container.handsontable({
    data: json.data,
    startRows: 5,
    startCols: 5,
    rowHeaders: true,
    colHeaders: true,
    fixedRowsTop: 1,
    fixedColumnsLeft: -1,
    minSpareRows: 1,
    contextMenu: true,
    comments: true,
    readOnly: true,
    cell: json.report,
    cells: function (row, col, prop) {
      var cellProperties = {};
      if (row === 0) {
        cellProperties.renderer = firstRowRenderer; //uses function directly
      }
      else {
        cellProperties.renderer = "reportValueRenderer"; //uses lookup map
      }
      return cellProperties;
    }
  });
}

function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
  Handsontable.renderers.TextRenderer.apply(this, arguments);
  td.style.fontWeight = 'bold';
  td.style.color = 'green';
  td.style.background = '#CEC';
}

function reportValueRenderer(instance, td, row, col, prop, value, cellProperties) {
  Handsontable.renderers.TextRenderer.apply(this, arguments);
  key = row+'_'+col;
  if (report_mapping[key]) {
    td.style.color = 'red';
  }
}