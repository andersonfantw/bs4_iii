<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once('koala.Utility.php');
require_once('common.Utility.php');
require_once('rpc.Utility2.php');
function getmicrotime() {
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

$run_start=getmicrotime();
/*if(!isset($token)){
	$out=array(array(104,"parameter error"));
	echo json_encode($out);
	exit;
}*/

//////////////
//check token....



//////////////

if(isset($kw_exp)){
	if(!isset($kw_operands)){
		$out=array(array(104,"parameter error(kw_operands)"));
		echo json_encode($out);
		exit;
	}
}

if (!isset($total_num_info))
	$total_num_info=0;

if (!isset($orderby))
	$orderby="total_count";

if (!isset($sort_order)){
	$sort_order="desc";
	//if($orderby=="total_count")
	//	$sort_order="desc";
	//else
	//	$sort_order="asc";
}

if (!isset($start))
	$start=0;

$out=array(array(0,''));
$rc=0;
$errmsg="";

if($orderby=="total_count"){
	$order_s="order by total_count ".$sort_order;
}else{
	$order_s="order by Tag_".$orderby." ".$sort_order.", total_count desc";
}

$db=kwcr2_mapdb("cybersite","Program","wishbone");
if($db!=0){
	do{
		if(isset($kw_exp)){
			$kw_exp=urldecode($kw_exp);
			error_log("kw_exp=".$kw_exp,0);
			$where_s=" where TextBlock match '".$kw_exp."'";
		}else{
			$where_s="";
		}
		$p=array();
		if(isset($tag_srh_json)){
			if(strlen($tag_srh_json)>0){
				if(strlen($where_s)>0)
					$where_s.=" and ";
				else
					$where_s.=" where ";
			$tags=json_decode($tag_srh_json,true);
			$tag_sql="";
			foreach($tags as $_tag){
				if($tag_sql=="")
					$tag_sql.="(";
				else
					$tag_sql.=" or (";
				$sub_tag_sql="";
				foreach($_tag as $k=>$v){
					//標籤下拉選單可以多選，若是多選，用逗號分隔
					//例: [{"pn":"研究計畫" , "prt":"簽約報告,期末報告" , "pi":"工研院電光系統所"…}]
					//標籤有可能多組條件, 用 or
					//例: [{"year":106, "pi":"工研院電光系統所"},{"year":107, "pi":"工研院資通所"}]
					//例: [{"year":"109,108,107,106","pi":"中尉中心"},{"year":"105,104,103,102","pi":"工研院巨資中心"}]"
					if($sub_tag_sql!="")
						$sub_tag_sql.=" and ";
					if(strtolower($k)=="pn"){
						$sub_tag_sql.="Tag_".$k." contain '".$v."'";
					}else{
						$items=explode(",",$v);
						if(count($items)==1){
							$sub_tag_sql.="Tag_".$k." = ?";
							$p[]=$v;
						}else{	//多組
							$sub_tag_sql.="(";
							foreach ($items as $_v){
								$sub_tag_sql.="Tag_".$k."=? or ";
								$p[]=$_v;
							}
							$sub_tag_sql=substr($sub_tag_sql, 0, -4);
							$sub_tag_sql.=")";
						}
					}
				}
				$tag_sql.=$sub_tag_sql;
				$tag_sql.=")";
			}
			$where_s.="(".$tag_sql.")";
			}
		}

		$cols="B_ID,U_ID,BS_ID,B_Key,Name,Tag_year,Tag_pn,Tag_pi,Tag_pcu,Tag_pc,Tag_pwrf,Tag_prt";
		$sum_q="";
		$cols1="";
		if(isset($kw_exp)){
			$kw=json_decode($kw_operands,true);
			$i=0;
			foreach($kw as $_k){
				$i++;
				$cols.=",hitcount(TextBlock,'".$_k."',0) as c".$i;
				$cols1.=",b.c".$i;
				$sum_q.="sum(c".$i.")+";
			}
			if(strlen($sum_q)>0)
				$sum_q=substr($sum_q,0,strlen($sum_q)-1)." as total_count";
		}else{
			$cols.=",0 as c1";
			$cols1.=",b.c1";
			$sum_q.="sum(c1) as total_count";
		}

		//kwcr2_rawqueryexec($db, "select $cols from BOOKSHELF2_IndexSource (text index IDXTEXTBLOCK) ".$where_s." into tmpQ", $p,"");
		error_log("select $cols from BOOKSHELF2_IndexSource ".$where_s." into tmpB",0);
		foreach($p as $_p)
			error_log($_p,0);
		if(!kwcr2_rawqueryexec($db, "select $cols from BOOKSHELF2_IndexSource ".$where_s." into tmpB", $p,"")){
			$rc=1;
			$out[0]=array(1,"get list fail!".kwcr2_geterrormsg($db, 1));
			break;
		}
		//kwcr2_rawqueryexec($db, "select Tag_year,Tag_pn,Tag_pi,Tag_pcu,Tag_pc,Tag_pwrf,$sum_q from tmpB group by Tag_year,Tag_pn,Tag_pi,Tag_pcu,Tag_pc,Tag_pwrf into tmpP", array(),"");
		//會不會有的書沒有 tag ??
		error_log("select Tag_year,Tag_pn,Tag_pi,Tag_pcu,Tag_pc,Tag_pwrf,$sum_q from tmpB where tag_pn is not null group by Tag_year,Tag_pn,Tag_pi,Tag_pcu,Tag_pc,Tag_pwrf into tmpP",0);
		kwcr2_rawqueryexec($db, "select Tag_year,Tag_pn,Tag_pi,Tag_pcu,Tag_pc,Tag_pwrf,$sum_q from tmpB where tag_pn is not null group by Tag_year,Tag_pn,Tag_pi,Tag_pcu,Tag_pc,Tag_pwrf into tmpP", array(),"");

		//if ($total_num_info>0){
			$sql="select count(*) from tmpP for browse";
			$r=read_one_record($db, $sql, array());
			if (($r===false)||(!isset($r))){
				$rc=1;
				$out[0]=array(1,"get total info fail(1)!".kwcr2_geterrormsg($db, 1));
				break;
			}
			$total_num=(int)$r[0];
		//}
		
		if(!isset($length))
			$length=$total_num;
		$sql="select Tag_year,Tag_pn,Tag_pi,Tag_pcu,Tag_pc,Tag_pwrf,total_count from tmpP ".$order_s." limit ".$start.", ".$length." into tmpP10";
		error_log($sql,0);
		kwcr2_rawqueryexec($db, $sql , array(),"");
		
		if($total_num_info==1){
			$r=read_one_record($db, "select count(*) from tmpP10", array());
			if (($r===false)||(!isset($r))){
				$rc=2;
				$out[0]=array(1,"get total info fail!(2)".kwcr2_geterrormsg($db, 1));
				break;
			}
			$current_num=(int)$r[0];
			$out[]=array($total_num,$current_num);
		}
		$sql="select b.B_ID,b.U_ID,b.BS_ID,b.B_Key,b.Name,b.Tag_year,b.Tag_pn,b.Tag_pi,b.Tag_pcu,b.Tag_pc,b.Tag_pwrf,b.Tag_prt,p.total_count".$cols1." from tmpB b,tmpP10 p where b.Tag_year=p.Tag_year and b.Tag_pn=p.Tag_pn ".$order_s;
		error_log($sql,0);
		$rs=read_multi_record($db, $sql, array(), array());
		if($rs===false){
			$out[0]=array(1,"get book list failed!(".kwcr2_geterrormsg($db,1).")");
			break;
		}
		
		foreach($rs as $r){
			$kw_count=array();
			$i=13;
			if(isset($kw_exp)){
				foreach($kw as $_k){
					$kw_count[urlencode($_k)]=(int)$r[$i];
					$i++;
				}
				$kw_count_json=json_encode($kw_count);
			}else
				$kw_count_json="";
			

			$out[]=array((int)$r[0],(int)$r[1],(int)$r[2],$r[3],urlencode($r[4]),(int)$r[5],urlencode($r[6]),urlencode($r[7]),urlencode($r[8]),urlencode($r[9]),urlencode($r[10]),urlencode($r[11]),(int)$r[12],$kw_count_json);
		}
		
	}while(0);
	
	kwcr2_unmapdb($db);
}else{
	$out[0]=array(100,"connect db error！");
}
$run_end=getmicrotime();
$total_time = $run_end - $run_start;
$out[0][2]=round($total_time,2);
echo urldecode(json_encode($out));
?>