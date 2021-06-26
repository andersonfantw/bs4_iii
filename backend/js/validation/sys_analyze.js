$(document).ready(function(){
	$('input[name=period]').click(function(){
		doChart();
	});
	$('input[name=type]').click(function(){
		doChart();
	});
	$('input[name=query\\[\\]]').click(function(){
		doChart();
	});
	
	function doChart(){
		if($('input[name=period]:checked').length>0
			&& $('input[name=type]:checked').length>0
			&& $('input[name=query\\[\\]]:checked').length>0){
				_period = $('input[name=period]:checked').val();
				_type = $('input[name=type]:checked').val();
				_query = [];
				$('input[name=query\\[\\]]:checked').each(function(){
					_query.push($(this).val());
				});
        $.ajax({
            type: "post",
            dataType: "json",
            url: web_url+"/api/backend/chart.php",
            data: {cmd:'login',p:_period,t:_type,q:_query}, 
            success: function(data){
            		var option = doParam(data);
                $("#chart").CanvasJSChart(option);
            },
            error: function(){
            },
            async:false
        });
		}
	}
});

function doParam(data){
	if(data.amount_time){
		_maximum=2000;
		_interval=200;
	}else{
		_maximum=300;
		_interval=30;
	}
	var option={
		zoomEnabled: false,
		animationEnabled: true,
		title:{
			text: "Bookshelf usage statistics"
		},
		axisY2:{
			valueFormatString:"0 bn",
			maximum: _maximum,
			interval: _interval,
			interlacedColor: "#F5F5F5",
			gridColor: "#D7D7D7",
 			tickColor: "#D7D7D7"
		},
    theme: "theme2",
    toolTip:{
            shared: true
    },
		legend:{
			verticalAlign: "bottom",
			horizontalAlign: "center",
			fontSize: 15,
			fontFamily: "Lucida Sans Unicode"

		},
		data: [],
      legend: {
        cursor:"pointer",
        itemclick : function(e) {
          if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
          	e.dataSeries.visible = false;
          }
          else {
            e.dataSeries.visible = true;
          }
          chart.render();
        }
      }
	}
	var index=0;
	if(data.user){
		option.data[index]={
			name: "user",
			type: data.type,
			lineThickness:3,
			showInLegend: true,
			axisYType:"secondary",
			markerType: "circle",
			dataPoints:[]
		};
		for(i=0;i<data.user.length;i++){
			var dt = setDate(data.user[i]);
			option.data[index].dataPoints[i] = {
				label:dt,
				y:parseInt(data.user[i].user)
			};
		}
		index++;
	}
	if(data.visit){
		option.data[index]={
			name: "visit",
			type: data.type,
			lineThickness:3,
			showInLegend: true,
			axisYType:"secondary",
			markerType: "triangle",
			dataPoints:[]
		};
		for(i=0;i<data.visit.length;i++){
			var dt = setDate(data.visit[i]);
			option.data[index].dataPoints[i] = {
				label:dt,
				y:parseInt(data.visit[i].visit)
			};
		}
		index++;
	}
	if(data.amount_time){
		option.data[index]={
			name: "amount_time",
			type: data.type,
			lineThickness:3,
			showInLegend: true,
			axisYType:"secondary",
			dataPoints:[]
		};
		for(i=0;i<data.amount_time.length;i++){
			var dt = setDate(data.amount_time[i]);
			option.data[index].dataPoints[i] = {
				label:dt,
				y:parseInt(data.amount_time[i].amounttime)
			};
		}
		index++;
	}
	if(data.browser){
		index++;
	}
	if(data.os){
		index++;
	}
	return option;
}

function setDate(o){
	if(o.d){
		var _dt = new Date(Date.parse(o.d));
		var _y=_dt.getFullYear();
		var _m=_dt.getMonth();
		var _d=_dt.getDate();
		return new Date(_y,_m,_d);
	}
	if(o.m){
		var _y = parseInt(o.y);
		var _m = parseInt(o.m);
		return new Date(_y,_m);
	}
	if(o.w){
		var _y = parseInt(o.y);
		var _w = parseInt(o.w);
		return _y+'-'+_w;
	}
	if(o.y){
		var _y = parseInt(o.y);
		return new Date(_y,0);
	}
	if(o.d2){
		_week = ['Sun','Mon','Tru','Wen','Thu','Fri','Sat'];
		return _week[o.d2];
	}
	if(o.h){
		return o.h;
	}
}
