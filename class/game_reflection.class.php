<?php
class game_reflection extends db_process{

  function game_reflection($db) {
  	parent::db_process($db,'game_reflection','gr_');
  }

  function getSummeryList($orderby='',$limit_from=0 ,$offset=0,$where=''){
    $order_str = empty($orderby)?'`'.$this->prefix.'id` ASC':$orderby;
    $where_str = empty($where)?'':'where '.$where;
    if(!empty($limit_from) || !empty($offset)){
      if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }

    $sql = <<<SQL
select gr_id, gr_name,
	(gr_width * gr_height) as gr_amount,
	(select count(*)
		from %sgame_reflection_book
		where gr_id=gr.gr_id) as gr_set,
	(select count(*)
		from %sgame_reflection_common
		where gr_id=gr.gr_id and grc_common!=''
		group by bs_id) as gr_classes,
	(select count(*)
		from %sgame_reflection_common
		where gr_id=gr.gr_id) as gr_reflect,
	(select count(*)
		from %sgame_reflection_common
		where gr_id=gr.gr_id
		and grc_mark<>0) as gr_mark
	from %s gr
SQL;
    $sql = sprintf($sql,
		DB_PREFIX,
		DB_PREFIX,
		DB_PREFIX,
		DB_PREFIX,
		$this->table);
    $data['result'] = $this->db->get_results($sql);
    $record = $this->db->query_first('SELECT FOUND_ROWS() as record');
    $data['total'] = $record['record'];
    return $data;
  }

	function getCommonList($bs_id=0,$b_id=0,$orderby='',$limit_from=0 ,$offset=0,$where=''){
    $order_str = empty($orderby)?'`'.$this->prefix.'id` ASC':$orderby;
    $where_str = empty($where)?'':'where '.$where;
    $bid_str = ($b_id==0)?'':'and grc.b_id='.$b_id;
    if(!empty($limit_from) || !empty($offset)){
      if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }
    
    $sql = <<<SQL
select grc.bs_id, grc.b_id, bu.bu_id, bu.bu_cname, bu.bu_name, grc.grc_common, grc.grc_mark
from %sgame_reflection_common grc
left join %sbookshelf_users bu on(grc.bu_id=bu.bu_id)
where grc.bs_id=%u %s %s
SQL;
    $sql = sprintf($sql, DB_PREFIX, DB_PREFIX, $bs_id, $bid_str, $where_str);
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);

		$data['result'] = $this->db->get_results($sqlwithorder);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;
	}

  function getList($bs_id=0,$orderby='',$limit_from=0 ,$offset=0,$where=''){
    $order_str = empty($orderby)?'`'.$this->prefix.'id` ASC':$orderby;
    $where_str = empty($where)?'':'and '.$where;
    if(!empty($limit_from) || !empty($offset)){
      if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }

/*
    $sql = <<<SQL
	select SQL_CALC_FOUND_ROWS bs.*,a.u_id,a.u_cname 
	from %s bs 
	left join %saccount_bookshelf ab on (bs.bs_id = ab.bs_id) 
	left join %saccount a on(ab.u_id=a.u_id) %s order by %s %s
SQL;
	$sql = sprintf($sql,
		$this->table,
		DB_PREFIX,
		DB_PREFIX,
		$where_str,
		$order_str,
		$limit_str);
*/

/*    $sql = <<<SQL
	select * 
	from %s gr 
	left join %sbookshelfs bs 
		on(gr.bs_id=bs.bs_id) %s 
	order by %s %s
SQL;
    $sql = sprintf($sql,
		$this->table,
		DB_PREFIX,
		$where_str,
		$order_str,
		$limit_str);
*/
    $sql = <<<SQL
select SQL_CALC_FOUND_ROWS gr_id, gr_name,
	(select count(*)
		from %sgame_reflection_common
		where gr_id=gr.gr_id) as gr_reflect,
	(select count(*)
		from %sgame_reflection_common
		where gr_id=gr.gr_id
		and grc_mark<>0) as gr_mark
from %s gr 
where gr.gr_id in (select gr_id 
			from %sgame_reflection_common 
			where bs_id=%u) %s
order by %s %s;
SQL;
    $sql = sprintf($sql,
    DB_PREFIX,
    DB_PREFIX,
		$this->table,
		DB_PREFIX,
		$bs_id,
		$where_str,
		$order_str,
		$limit_str);

    $data['result'] = $this->db->get_results($sql);
    $record = $this->db->query_first('SELECT FOUND_ROWS() as record');
    $data['total'] = $record['record'];
    return $data;
  }

	//return -1: no avaliable seq
	function getNextMapSeq($bs_id){
		$sql =<<<SQL
set @seq=(select min(seq) as seq
					from (select (@i:=@i+1) as seq, b_id, grb_sn
								from bookshelf2_game_reflection_book
								where gr_id=(select gr_id
								from bookshelf2_game_reflection
								where bs_id=%u)
					order by grb_sn) as t
					where seq <> grb_sn);
SQL;
		$sql = sprintf($sql, $bs_id);
		$this->db->query('set @i=0;');
		$this->db->query($sql);
		$sql =<<<SQL
select ifnull(@seq,(select max(ifnull(grb_sn,0))+1 
										from bookshelf2_game_reflection_book
										where gr_id=(select gr_id
								from bookshelf2_game_reflection
								where bs_id=%u))) as seq;
SQL;
		$sql = sprintf($sql, $bs_id);
		$seq=$this->db->query_first($sql);
		
		$sql = <<<SQL
select gr_width*gr_height as total
from %s 
where bs_id=%u
SQL;
		$sql = sprintf($sql,$this->table,$bs_id);
		$this->db->query('set @i=0;');
		$maxseq=$this->db->query_first($sql);
		if($maxseq==null) return -1;
		if((intval($maxseq['total'])-intval($seq['seq']))<0){
			return 0;
		}else{
			return ((int)$seq['seq']);
		}

	}

	function getMapSeq($id,$bid){
		$sql =<<<SQL
select seq
from %sreflection_book
where gr_id=%u
and b_id=%u;
SQL;
		$sql = srpintf($sql,DB_PREFIX,$id,$bid); 
		$seq=$this->db->query_first($sql);
		return $seq;
	}


	//檢查是否為活動書櫃
	function isGameReflectionBS($bs_id){
		$sql = <<<SQL
select *
from bookshelf2_game_reflection
where bs_id = %u;
SQL;
		$sql = sprintf($sql, $bs_id);
		$rs = $this->db->get_results($sql);
		if($rs) return true;
		return false;
	}

	//在老師書櫃中檢查這本書是否有心得
	//在系統管理員書櫃中檢查這本書是否有心得
	function isCommonForSys($identity, $grid){
	$sql = <<<SQL
select count(*) as isCommon
from bookshelf2_game_reflection_common grc
left join bookshelf2_books b
	on(grc.b_id=b.b_id)
left join bookshelf2_game_reflection_book grb
	on(substr(b.share_bs_id,34)=grb.b_id and grb.gr_id=%u)
SQL;
		$sql = sprintf($sql, $grid);
    $rs = $this->db->query_first($sql);
		return $rs['isCommon'];
	}

	function isCommonForOwner($bsid){
		$sql = <<<SQL
select count(*) as isCommon
from bookshelf2_game_reflection gr
left join bookshelf2_game_reflection_book grb
	on(grb.gr_id=gr.gr_id)
left join bookshelf2_books b
	on(substr(b.share_bs_id,34)=grb.b_id 
		and left(b.share_bs_id,32)=md5(concat('%s','datasource.php?bs=',gr.bs_id)))
left join bookshelf2_game_reflection_common grc
	on(grc.bs_id=gr.bs_id)
where gr.bs_id=%u
SQL;
		$sql = sprintf($sql, DATA_SOURCE_PATH, $bsid);
		$rs = $this->db->query_first($sql);
    return $rs['isCommon'];
	}
	
	function isCommonByBook($bid){
		$sql = <<<SQL
select count(*) as isCommon
from bookshelf2_game_reflection_common
where b_id=%u
SQL;
		$sql = sprintf($sql, $bid);
    $rs = $this->db->query_first($sql);
    return $rs['isCommon'];
	}

  function getByID($id){
    $sql = <<<SQL
select * 
from %sgame_reflection gr 
left join %sbookshelfs bs on(gr.bs_id=bs.bs_id)
where %sid = %u
SQL;
    $sql = srpintf($sql,$this->table,DB_PREFIX, $this->prefix, $id);
    return $this->db->query_first($sql);
  }

  function insert($data){
    $sql = sprintf("insert into %s set %s",$this->table ,$this->process_data($data) );
    $rs = $this->db->query($sql);
    $bs_id = $this->db->insert_id();
    if($rs){
      //call ecocat api
      return $bs_id;
    }else{
      return false;
    }
  }

  function insert_bookref($bs_id,$bid, $sn){
    $sql = sprintf("select gr_id from %s where bs_id=%u",$this->table, $bs_id);
    $rs = $this->db->query_first($sql);
    if($rs){
      $data['gr_id'] = $rs['gr_id'];
      $data['b_id'] = $bid;
      $data['grb_sn'] = $sn;
      $sql = sprintf("insert into %sgame_reflection_book set %s",DB_PREFIX, $this->process_data($data));
      $rs = $this->db->query($sql);
      if($rs){
        //call ecocat api
        return true;
      }else{
        return false;
      }
    }

  }

  function update($bs_id,$data){
    if(intval($bs_id)>0)
    {
      $sql = sprintf("update %s set %s  where %sid=%u",$this->table ,$this->process_data($data), $this->prefix, $bs_id );
      $rs = $this->db->query($sql);
/*
      if($rs){
        $sql = sprintf("insert into %saccount_bookshelf set u_id=%u ,%sid=%u on duplicate key update u_id=%u",DB_PREFIX, $u_id,$this->prefix,$bs_id,$u_id);
        $rs = $this->db->query($sql);
      }
*/
      return $rs;
    }else{
      return false;
    }
  }

	function updateCommonMark($bs_id, $b_id, $bu_id, $val){
		$sql = <<<SQL
update %sgame_reflection_common 
set grc_mark=%s 
where bs_id=%u and b_id=%u and bu_id=%u;
SQL;
		$sql = sprintf($sql, DB_PREFIX, $val, $bs_id, $b_id, $bu_id);
		$rs = $this->db->query($sql);
		return $rs;
	}

  function del($id){  
    if(intval($id)>0)
    {
      $sql = sprintf("delete from %s where %sid=%u",$this->table, $this->prefix, $id);
      if($this->db->query($sql)){
        $sql2 = sprintf("delete from %sgame_reflection_book where %sid=%u",DB_PREFIX, $this->prefix, $id); 
        return $this->db->query($sql2);
      }
    }
    return false;    
  }

  function del_bookref($id,$bid){
    if(intval($val)>0){
      $sql = sprintf("delete from %sgame_reflection_book where gr_id=%u and b_id=%u",DB_PREFIX, $id, $bid);
    }
  }

  function process_data($data){
    $str = "";
    foreach($data as $key=>$val){
      if(!empty($str))
        $str .= ",";
      $str .= "`".$key."`='".$val."'";
    }
    return $str;
  }
}
?>
