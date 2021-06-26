$(document).ready(function(){
  var json={
  data:[
    	['ttii001','math','數學','math001','10以內的數'],
    	['ttii002','math','數學','','數的順序和大小1'],
    	['ttii003','math','數學','math003','數的大小'],
    	['ttii004','math','數學','','比長短'],
    	['ttii005','math','數學','math005','分與合'],
    	['ttii006','math','數學','math006','前後左右上下'],
    	['ttii007','math','數學','math007','10以內的加法1'],
    	['ttii008','math','數學','math008','10以內的加法2'],
    	['ttii009','math','數學','math009','認識形狀'],
    	['ttii010','math','數學','math010','10以內的減法1'],
    	['ttii011','math','數學','math011','10以內的減法2']
  ],
  dataheader:['ttii編號','科目key','科目','單元key','單元'],
  report:[
  	{row: 1, col: 0, comment:{value:"book is not exist"}},
		{row: 1, col: 3, comment:{value:"tag is not exist"}},
		{row: 3, col: 3, comment:{value:"tag has multi options"}, options:['k1','k2']},
	]};

	//$('#submit').prop( "disabled", true );
	//setHandsontable(json);
});

function setHandsontable(json){
	if(typeof json.code==="undefined"){
		//error message handler
		alert(json.msg);
		document.location.reload();
	}

	report_mapping = [];
	for(i=0;i<json.report.length;i++){
		key = json.report[i].row+'_'+json.report[i].col;
		report_mapping[key] = json.report[i];
	}

	$('#body').removeClass('hastable');
	$('#body').append('<button id="submit">submit</button>');
	$('body').append('<select id="HandsontableSelect"></select>');
	$('#HandsontableSelect').hide();
  //var container = $("#page-wrapper");

  var container = document.getElementById('body');
  var hot = new Handsontable(container,{
    data: json.data,
    startRows: 5,
    startCols: 5,
    rowHeaders: true,
    colHeaders: json.dataheader,
    autoColumnSize: true,
    stretchH: 'all',
    contextMenu: true,
    comments: true,
    readOnly: false,
    cell: json.report,
    cells: function (row, col, prop) {
			var cellProperties = {};
			cellProperties.renderer = reportValueRenderer;
      return cellProperties;
    }
  });
	hot.view.wt.update('onCellDblClick', function (e,cell) {
		key = cell.row+'_'+cell.col;
		if(typeof report_mapping[key] !== "undefined"){
			if(typeof report_mapping[key].options !== "undefined"){
				$('#HandsontableSelect').empty();
				$('#HandsontableSelect').append('<option>please select</option>');
				$(report_mapping[key].options).each(function(i){
					$('#HandsontableSelect').append('<option value="'+key+'">'+report_mapping[key].options[i]+'</option>');
				});
				$('#HandsontableSelect').css({position:'absolute',top:e.y-e.offsetY,left:e.x-e.offsetX}).show();
			}
		}
	});
	$('#HandsontableSelect').change(function(){
		t=$(this).find('option:selected').text();
		if(t!='please select'){
			v=$(this).find('option:selected').val();
			c = v.split('_');
			hot.setDataAtCell(parseInt(c[0]),parseInt(c[1]),t);
			$(this).hide();
		}
	});
	$('#submit').click(function(){
		var str='';
		var ferr=false;
		for(y=0;y<json.data.length;y++){
			_str = '';_str1='';
			for(x=0;x<json.dataheader.length;x++){
				_v=hot.getDataAtCell(y,x);
				if(_v===null || _v==''){ferr=true;}
				if(x==0){
					_str=_v+'=';
				}else if(x%2==1){
					_str1+=','+_v+':';
				}else{
					_str1+=_v;
				}
			}
			str+=_str+_str1.substring(1)+';';
		}
		if(ferr){
			console.log(str);
			alert('There is one or more empty cell, please current those cells.');
		}else{
			TagAPIHandler.importByStr(str,2,function(data){
				switch(data.code){
					case '200':
						alert(data.msg);
						document.location.reload();
						break;
					default:
						alert(data.msg);
						break;
				}
			});
		}
	});
}

function reportValueRenderer(instance, td, row, col, prop, value, cellProperties) {
  Handsontable.renderers.TextRenderer.apply(this, arguments);
  key = row+'_'+col;
  if(value=='' || value==null){
  	td.style.background = '#CEC';
  }
  /*
  if (report_mapping[key]) {
    td.style.color = 'red';
  }*/
}