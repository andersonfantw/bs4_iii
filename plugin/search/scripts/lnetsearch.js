/*var subData1 = `<div class="details"><table class="details">
<thead>
    <th>各期計畫書</th><th>創新</th><th>5G</th><th>雲端</th><th>大數據</th><th></th>
</thead>
<tbody>
<tr>
    <td><button class="btn small">開啟</button><h5>簽約</h5></td><td>20</td><td>30</td><td>20</td><td>40</td><td></td>
</tr>
<tr>
    <td><button class="btn small">開啟</button><h5>期中</h5></td><td>30</td><td>50</td><td>40</td><td>40</td><td></td>
</tr>
<tr>
    <td><button class="btn small">開啟</button><h5>期末</h5></td><td>40</td><td>50</td><td>50</td><td>40</td><td></td>
</tr>
<tr>
    <td><button class="btn small">開啟</button><h5>全程</h5></td><td>50</td><td>60</td><td>50</td><td>40</td><td></td>
</tr>
</tbody>
</table></div>`;
var subData2 = `<div class="details"><table class="details">
<tbody>
    <tr>
        <td><button class="btn small">開啟</button><h5>簽約</h5></td>
        <td><button class="btn small">開啟</button><h5>期中</h5></td>
        <td><button class="btn small">開啟</button><h5>期末</h5></td>
        <td><button class="btn small">開啟</button><h5>全程</h5></td>
    </tr>
</tbody>
</table></div>`;
var subData3 = `<div class="details"><table class="details">
<thead>
    <th>各期計畫書</th><th>創新</th><th>5G</th><th>雲端</th><th>大數據</th><th>創新</th><th>5G</th><th>雲端</th><th>大數據</th>
</thead>
<tbody>
<tr>
    <td><button class="btn small">開啟</button><h5>簽約</h5></td><td>20</td><td>30</td><td>20</td><td>40</td></td><td>20</td><td>30</td><td>20</td><td>40</td>
</tr>
<tr>
    <td><button class="btn small">開啟</button><h5>期中</h5></td><td>30</td><td>50</td><td>40</td><td>40</td><td>30</td><td>50</td><td>40</td><td>40</td>
</tr>
<tr>
    <td><button class="btn small">開啟</button><h5>期末</h5></td><td>40</td><td>50</td><td>50</td><td>40</td><td>40</td><td>50</td><td>50</td><td>40</td>
</tr>
<tr>
    <td><button class="btn small">開啟</button><h5>全程</h5></td><td>50</td><td>60</td><td>50</td><td>40</td><td>50</td><td>60</td><td>50</td><td>40</td>
</tr>
</tbody>
</table></div>`;
*/
var subData1 = '<div class="details"><table class="details"><thead><th>各期計畫書</th><th>創新</th><th>5G</th><th>雲端</th><th>大數據</th><th></th></thead><tbody><tr><td><button class="btn small">開啟</button><h5>簽約</h5></td><td>20</td><td>30</td><td>20</td><td>40</td><td></td></tr><tr><td><button class="btn small">開啟</button><h5>期中</h5></td><td>30</td><td>50</td><td>40</td><td>40</td><td></td></tr><tr><td><button class="btn small">開啟</button><h5>期末</h5></td><td>40</td><td>50</td><td>50</td><td>40</td><td></td></tr><tr><td><button class="btn small">開啟</button><h5>全程</h5></td><td>50</td><td>60</td><td>50</td><td>40</td><td></td></tr></tbody></table></div>';
var subData2 = '<div class="details"><table class="details"><tbody><tr><td><button class="btn small">開啟</button><h5>簽約</h5></td><td><button class="btn small">開啟</button><h5>期中</h5></td><td><button class="btn small">開啟</button><h5>期末</h5></td><td><button class="btn small">開啟</button><h5>全程</h5></td></tr></tbody></table></div>';
var subData3 = '<div class="details"><table class="details"><thead><th>各期計畫書</th><th>創新</th><th>5G</th><th>雲端</th><th>大數據</th><th>創新</th><th>5G</th><th>雲端</th><th>大數據</th></thead><tbody><tr><td><button class="btn small">開啟</button><h5>簽約</h5></td><td>20</td><td>30</td><td>20</td><td>40</td></td><td>20</td><td>30</td><td>20</td><td>40</td></tr><tr><td><button class="btn small">開啟</button><h5>期中</h5></td><td>30</td><td>50</td><td>40</td><td>40</td><td>30</td><td>50</td><td>40</td><td>40</td></tr><tr><td><button class="btn small">開啟</button><h5>期末</h5></td><td>40</td><td>50</td><td>50</td><td>40</td><td>40</td><td>50</td><td>50</td><td>40</td></tr><tr><td><button class="btn small">開啟</button><h5>全程</h5></td><td>50</td><td>60</td><td>50</td><td>40</td><td>50</td><td>60</td><td>50</td><td>40</td></tr></tbody></table></div>';
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();

    var oTable = $("#search_result").dataTable({
        "dom": '<"top"i>rt<"bottom"p><"clear">',
        "order": [[ 2, "desc" ]],
        "language": {
			    "decimal":        "",
			    "emptyTable":     "沒有資料",
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
        "bJQueryUI": true,
        "aoColumns": [
            {"width":"30px"},{"width":"100px"},{"width":"52px"},{"width":"52px"},{"width":"52px"},{"width":"52px"},{"width":"30px"}
                     ]
    });
    //$('#search_result').append('<caption style="caption-side: top">搜尋結果</caption>');
    $('#search_result_wrapper .top').append('<h4 style="caption-side: top">搜尋結果</h4>');
    $('#search_result_wrapper .bottom').append('<button id="SearchFuther" class="btn large d-inline-block">進一步搜尋</button>');
    obj = $('<button id="SaveQuickSearch" class="btn large d-inline-block">儲存快速查詢</button>').click(function(){
        $('#EditQuickSearchTitle').modal('toggle')
    });
    $('#search_result_wrapper .bottom').append(obj);
    $('#SearchFuther').click(function(){
        document.location.href='advsearch.html';
    });

    $('#search_result tbody td').click(function () {
        var nTr = this.parentNode;
        if ($(this).parent().hasClass('open')) {
            /* This row is already open - close it */
            $(this).parent().removeClass('open');
            oTable.fnClose(nTr);
        }else{
            /* Open this row */
            $(this).parent().addClass('open');
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
    });
    $('#accordion-1 .card-header').mouseleave(function(){
        $('#accordion-1 .item-1').removeClass('show');
    });

    $('#pwrf').chosen();
    $('#prt').chosen();
    $('#pi').chosen();
    $('#pcu').chosen();
    $('#pc').chosen();
    $('#pcof').chosen();
    $('#year_from').chosen();
    $('#year_to').chosen();
/*
    var options = {items:[
      {header: '功能'},
      {text: '編輯', href: 'advsearch.html'},
    ]}
    $('main.index > .row  >.col > div').contextify(options);
*/
});