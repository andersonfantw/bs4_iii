if(!$.getQuery('q')){
	document.location.href='/search/';
}

var subData1 = '<div class="details"><table class="details"><thead><th>各期計畫書</th><th>創新</th><th>5G</th><th>雲端</th><th>大數據</th><th></th></thead><tbody><tr><td><button class="btn small">開啟</button><h5>簽約</h5></td><td>20</td><td>30</td><td>20</td><td>40</td><td></td></tr><tr><td><button class="btn small">開啟</button><h5>期中</h5></td><td>30</td><td>50</td><td>40</td><td>40</td><td></td></tr><tr><td><button class="btn small">開啟</button><h5>期末</h5></td><td>40</td><td>50</td><td>50</td><td>40</td><td></td></tr><tr><td><button class="btn small">開啟</button><h5>全程</h5></td><td>50</td><td>60</td><td>50</td><td>40</td><td></td></tr></tbody></table></div>';
var subData2 = '<div class="details"><table class="details"><tbody><tr><td><button class="btn small">開啟</button><h5>簽約</h5></td><td><button class="btn small">開啟</button><h5>期中</h5></td><td><button class="btn small">開啟</button><h5>期末</h5></td><td><button class="btn small">開啟</button><h5>全程</h5></td></tr></tbody></table></div>';
var subData3 = '<div class="details"><table class="details"><thead><th>各期計畫書</th><th>創新</th><th>5G</th><th>雲端</th><th>大數據</th><th>創新</th><th>5G</th><th>雲端</th><th>大數據</th></thead><tbody><tr><td><button class="btn small">開啟</button><h5>簽約</h5></td><td>20</td><td>30</td><td>20</td><td>40</td></td><td>20</td><td>30</td><td>20</td><td>40</td></tr><tr><td><button class="btn small">開啟</button><h5>期中</h5></td><td>30</td><td>50</td><td>40</td><td>40</td><td>30</td><td>50</td><td>40</td><td>40</td></tr><tr><td><button class="btn small">開啟</button><h5>期末</h5></td><td>40</td><td>50</td><td>50</td><td>40</td><td>40</td><td>50</td><td>50</td><td>40</td></tr><tr><td><button class="btn small">開啟</button><h5>全程</h5></td><td>50</td><td>60</td><td>50</td><td>40</td><td>50</td><td>60</td><td>50</td><td>40</td></tr></tbody></table></div>';
function subData(data){
console.log(data);
	//<th>各期計畫書</th><th>創新</th><th>5G</th><th>雲端</th><th>大數據</th><th>創新</th><th>5G</th><th>雲端</th><th>大數據</th>
	//<td><button class="btn small">開啟</button><h5>簽約</h5></td><td>20</td><td>30</td><td>20</td><td>40</td></td><td>20</td><td>30</td><td>20</td><td>40</td></tr><tr><td><button class="btn small">開啟</button><h5>期中</h5></td><td>30</td><td>50</td><td>40</td><td>40</td><td>30</td><td>50</td><td>40</td><td>40</td></tr><tr><td><button class="btn small">開啟</button><h5>期末</h5></td><td>40</td><td>50</td><td>50</td><td>40</td><td>40</td><td>50</td><td>50</td><td>40</td></tr><tr><td><button class="btn small">開啟</button><h5>全程</h5></td><td>50</td><td>60</td><td>50</td><td>40</td><td>50</td><td>60</td><td>50</td><td>40</td>
	heads = ''; rows = ''; 
	if(data[0].keywords==null){
		table = '<div class="details"><table class="details"><tbody><tr class="nokeyword">@tbody@</tr></tbody></table></div>';
		//col = '<td><button class="btn small" onclick="ToolsHandler.MM_openBrWindow(\'@link@\',\'\')">開啟</button><h5>@prt@</h5></td>';
		//col = '<td><a class="btn small" href="@link@" target="_blank">開啟</a><h5>@prt@</h5></td>';
		col = '<td><a class="btn small" href="javascript:SearchAPIHandler.OpenBook(\'@link@\')">開啟</a><h5>@prt@</h5></td>';
		cols = '';
		for(i=0;i<data.length;i++){
			cols = cols + col.replace('@link@',data[i].link).replace('@prt@',data[i].prt);
		}
		for(i=0;i<4-data.length;i++){
			cols = cols + '<td></td>';
		}
		return table.replace('@tbody@',cols);
		
	}else{
		table = '<div class="details"><table class="details"><thead><th>各期計畫書</th>@thead@</thead><tbody>@tbody@</tbody></table></div>';
		head_col = '<th>@head@</th>';
		//row = '<tr><td><button class="btn small @css@" onclick="ToolsHandler.MM_openBrWindow(\'@link@\',\'\')">開啟</button><h5>@prt@</h5></td>@col@</tr>';
		//row = '<tr><td><a class="btn small" href="@link@" target="_blank">開啟</a><h5>@prt@</h5></td>@col@</tr>';
		row = '<tr><td><a class="btn small" href="javascript:SearchAPIHandler.OpenBook(\'@link@\')">開啟</a><h5>@prt@</h5></td>@col@</tr>';
		col = '<td>@num@</td>';

		for(i=0;i<data.length;i++){
			cols = ''; j=0;
			if(i==0){
				$.each(data[0].keywords,function(k,v){
					heads = heads + head_col.replace('@head@',k);
				});
			}
			$.each(data[i].keywords,function(k,v){
				cols = cols + col.replace('@num@',v);
				j++;
			});
			for(l=0;l<4-data[i].length;l++){
				cols = cols + '<td></td>';
			}
			rows = rows + row.replace('@css@','w'+data[i].prt.length).replace('@link@',data[i].link).replace('@prt@',data[i].prt).replace('@col@',cols);
		}
		return table.replace('@thead@',heads).replace('@tbody@',rows);
	}
}
$(document).ready(function(){
		LoginHandler.chkSingleLogin();
		setInterval(LoginHandler.chkSingleLogin, 120000);
		$('#QuickSearchTitleForm').bootstrapValidator({
		    message: 'This value is not valid',
		    feedbackIcons: {
		        valid: 'glyphicon glyphicon-ok',
		        invalid: 'glyphicon glyphicon-remove',
		        validating: 'glyphicon glyphicon-refresh'
		    },
		    fields: {
		    	QuickSearchName:{
		          validators: {
		          	notEmpty: {},
			          stringLength:{
			          		min:1,
			          		max:40
			          },
			          callback: {
			          	message: '請填寫全文檢索，或選擇查詢條件!',
			          	callback: function(value, validator){
										var objPL = new ParamList();
										objPL.parse($.getQuery('q'));
										if(objPL.isEmpty()){
											validator.updateMessage('QuickSearchName', 'callback', ' 請填寫全文檢索，或選擇查詢條件!');
											return false;
										}else return true;
				          }
			          }
		          }
		      },
		      QuickSearchShortName:{
		      		tigger: 'change',
		          validators: {
		          	notEmpty: {},
			          stringLength:{
			          		min:1,
			          		max:2
			          }
		          }
		      }
		    }
		}).on('error.field.bv', function(e, data) {
		    console.log('error.field.bv -->', data.element);
		    if (data.bv.getSubmitButton()) {
		      data.bv.disableSubmitButtons(false);
		    }
		}).on('success.field.bv', function(e, data) {
		    console.log('success.field.bv -->', data.element);
		    if (data.bv.getSubmitButton()) {
		      data.bv.disableSubmitButtons(false);
		    }
		}).on('added.field.bv', function(e, data) {
		    console.log('Added element -->', data.field, data.element);
		}).on('removed.field.bv', function(e, data) {
		    console.log('Removed element -->', data.field, data.element);
		});

		var objPL = new ParamList();
		objPL.parse($.getQuery('q'));
		str = objPL.toHtml();
		$('p.card-text').html(str);

		settings = {
        "processing": true,
        "serverSide": true,
        "ajax": {
        	"url": "/plugin/search/api/api.php?cmd=search",
        	"contentType": "application/x-www-form-urlencoded",
        	"type": "POST",
        	"data": {"q":objPL.toServer()}
        },
        "dom": '<"top"i>rt<"bottom"p><"clear">',
        "order": [[ 2, "desc" ]],
        "language": {
			    "decimal":        "",
			    "emptyTable":     "沒有符合的資料",
			    "info":           "顯示 _START_ 至 _END_ 筆 ,共 _TOTAL_ 筆",
			    "infoEmpty":      "顯示 0 至 0 筆,共 0 筆",
			    "infoFiltered":   "(在 _MAX_ 筆資料中搜尋)",
			    "infoPostFix":    "",
			    "thousands":      ",",
			    "lengthMenu":     "每頁顯示 _MENU_ 筆",
			    "loadingRecords": "仔入中...",
			    "processing":     "搜尋中...",
			    "search":         "搜尋:",
			    "zeroRecords":    "沒有符合的資料",
			    "paginate": {
			        "first":      "第一頁",
			        "last":       "最後一頁",
			        "next":       "下一頁",
			        "previous":   "上一頁"
			    },
			    "aria": {
			        "sortAscending":  ": 將欄位正排序",
			        "sortDescending": ": 將欄位反排序"
			    }
        },
        "pageLength": 10,
 				"bAutoWidth": false,
        "bJQueryUI": true,
        "aoColumns": [
            {"width":"69px"},{"width":"251px"},{"width":"78px"},{"width":"78px"},{"width":"78px"},{"width":"70px"},{"width":"70px"}
                     ]
    };


		
    var oTable = $("#search_result").DataTable(settings);
    oTable.clear();
		console.log(oTable);

    //$('#search_result').append('<caption style="caption-side: top">搜尋結果</caption>');
    $('#search_result_wrapper .top').append('<h4 style="caption-side: top">搜尋結果</h4>');
 		btn = $('<button id="SearchFuther" class="btn large d-inline-block">進一步搜尋</button>').click(function(){
 			document.location.href='/search/adv/?q='+$.getQuery('q');
 		});
    $('#search_result_wrapper .bottom').append(btn);
    btn = $('<button id="SaveQuickSearch" class="btn large d-inline-block">儲存</button>').click(function(){
    	if($.getQuery('q')){
    		objPL.parse($.getQuery('q'));
    	}
    	$('#sid').val(objPL.getValue('sid'));
    	$('#QuickSearchName').val(objPL.getValue('name'));
    	$('#QuickSearchShortName').val(objPL.getValue('shortname'));
			$('#EditQuickSearchTitle').modal('toggle')
    });
    $('#search_result_wrapper .bottom').append(btn);
 		$('#saveQuickSearch').click(function(){
    	_url = objPL.toUrl(true);
    	p = _url.split('[@]');
    	_name = $('#QuickSearchName').val();
    	_shortname = $('#QuickSearchShortName').val();
 			SearchAPIHandler.checkQuickSearchName(p[0],_name,function(data){
 				if(data.hasName){
 					if(confirm('您已經有相同名稱，請問要繼續儲存嗎?')){
 						return;
 					}
 				}
	    	if($('#sid').val()>0){
		    	SearchAPIHandler.updateQuickSearch(p[0],_name,_shortname,p[3],function(data){
		    		if(data.code=='200'){
		    			alert('更新成功!');
		    			$('#EditQuickSearchTitle button.close').click();
		    		}
		    	});
	    	}else{
		    	SearchAPIHandler.addQuickSearch(_name,_shortname,p[3],function(data){
		    		if(data.code=='200'){
		    			$('#sid').val(data.id);
		    			alert('新增成功!');
		    			$('#EditQuickSearchTitle button.close').click();
		    		}
		    	});
		    }
	    	console.log(_url);
	    });
 		});
    $('#search_result tbody').on('click','tr',function () {
 				var nTr = $(this).closest('tr');
				var row = oTable.row( this );
        if (row.child.isShown()) {
            /* This row is already open - close it */
            row.child.hide();
            nTr.removeClass('open');
            //$(this).parent().removeClass('open');
            //oTable.fnClose(nTr);
        }else{
            /* Open this row */
						d = row.data();
						if(nTr.attr('role')=='row'){
							data = JSON.parse(d[7]);
	            row.child( subData(data) ).show();
	            nTr.addClass('open');
	          }
            //$(this).parent().addClass('open');
            //oTable.fnOpen(nTr, subData2, 'details');

/*
            switch($(this).parent().find('td:eq(0)').text()){
                case '104':
                    oTable.fnOpen(nTr, subData2, 'details');
                    break;
                case '105':
                    _w = 160+($(subData3).find('th').length-1)*200;
                    _w = (_w>940)?_w:940;
                    dt = $(subData3).find('table').css('width',_w+'px');
                    oTable.fnOpen(nTr, dt, 'details');
                    break;
                default:
                    oTable.fnOpen(nTr, subData1, 'details');
                    break;
            }
*/
            //var companyid = $(this).attr("rel");
            //$.get("CompanyEmployees?CompanyID=" + companyid, function (employees) {
            //   oTable.fnOpen(nTr, employees, 'details');
            //});
        }
    });

    $('#most-visited button.md-menu').click(function(){
        $('#edit-link-dialog').show();
    });
    $('#edit-link-dialog #cancel').click(function(){
        $('#edit-link-dialog').hide();
    });
    
    $('main.index a.del').click(function(){
        $('#DelQuickSearch').modal('toggle')
    });
    $('#submit').click(function(){
        $('#EditQuickSearchTitle').modal('toggle')
    });
    $('#accordion-1 .card-header').mouseover(function(){
        $('#accordion-1 .item-1').addClass('show');
        $('#accordion-1 .card-header small').text('');
    });
    $('#accordion-1 .card-header').mouseleave(function(){
        $('#accordion-1 .item-1').removeClass('show');
        $('#accordion-1 .card-header small').text($('#accordion-1 .card-body .card-text').text());
console.log($('#accordion-1 .card-body .card-text').text());
    });

		$('#fulltext').keypress(function(e){
			if(e.keyCode == 13){
				if($('#fulltext').val()==''){
					alert('請輸入關鍵字!');
				}else{
					var objPL = new ParamList();
					objPL.addText('fulltext',$('#fulltext').val());
					_url = objPL.toUrl();
					document.location.href = '/search/list/?q='+_url;
				}
			}
		});
});
