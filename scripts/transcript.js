$(document).ready(function(){
	if(opener!=null){
		var env = opener.systemEnv;
	}else if(typeof systemEnv!='undefined'){
		var env = systemEnv;
	}
	$('#content h1').text(env.Language.transcript);
	$('#content span:eq(0)').text(env.Language.bookshelfname+': '+bookEnv.InitJSON.bstitle);
	$('#content span:eq(1)').text(env.Language.username+': '+bookEnv.InitJSON.acc);
	$('#content thead tr:eq(0) th:eq(0)').text(bookEnv.InitJSON.bstitle+env.Language.testscore);
	$('#content thead tr:eq(1) th:eq(0)').text(env.Language.testname);
	$('#content thead tr:eq(1) th:eq(2)').text(env.Language.correct);
	$('#content thead tr:eq(1) th:eq(3)').text(env.Language.points);
	$('#content thead tr:eq(1) th:eq(4)').text(env.Language.score);
	$('#content thead tr:eq(1) th:eq(5)').text(env.Language.average);
	$('#content tfoot tr:eq(0) td:eq(0)').text(env.Language.average);
	$.address.init();

	APIHandler.getTranscript(function(data){
		var html='';
		var rowspan=1;
		var score=0;
		var totalscore=0;
		var totalcorrect=0;
		var totalquestions=0;
		var totalgrade=0;
		var same_test=0;
		unit_num=1;
		for(i=1;i<=data.result.length;i++){
		  score+=parseFloat(data.result[i-1].i_points);
		  totalscore+=parseFloat(data.result[i-1].i_totalinteraction);
		  totalcorrect+=parseInt(data.result[i-1].i_correct);
		  totalquestions+=parseInt(data.result[i-1].i_totalinteraction);
			same_test+=Math.round(data.result[i-1].i_correct/data.result[i-1].i_totalinteraction*100);
		
			if((i==data.result.length) || (data.result[i-1].id!=data.result[i].id)){
		    str_name = '<td rowspan="'+rowspan+'">'+data.result[i-1].i_name+'</td>';
		    str_avg = '<td class="avg" rowspan="'+rowspan+'">'+Math.round(same_test/rowspan)+'</td>';
				totalgrade+=Math.round(same_test/rowspan);
				rowspan=1;
		    score=0;
		    totalscore=0;
		    same_test=0;
				unit_num++;
			}else{
		    str_name = '';
		    str_avg = '';
				rowspan++;
			}
		  html='<tr>'+str_name+'<td class="date">'+data.result[i-1].createdate+'</td><td>'+data.result[i-1].i_correct+'/'+data.result[i-1].i_totalinteraction+'</td><td class="points">'+data.result[i-1].i_points+'</td><td>'+Math.round(data.result[i-1].i_correct/data.result[i-1].i_totalinteraction*100)+'</td>'+str_avg+'</tr>'+html;
		}
		
		$('tbody').html(html);
		$('.correct').html(totalcorrect+'/'+totalquestions);
		$('.all_avg').html(Math.round(totalgrade/--unit_num));
	});
});