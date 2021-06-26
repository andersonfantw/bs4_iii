<?php
/*
q_id integer
q_name varchar(255)
q_tmpname varchar(50)
q_key varchar(255)
q_retry smallint
q_priority smallint
status smallint 0:wait -1:fail 1~99:converting  100:success  101:import success
b_id integer
q_data varchar(512)
isdelete smallint 1:delete
createdate TIMESTAMP
editdate TIMESTAMP
*/
class queue extends db_process{
	var $bindBook = false;
	var $key = '';
	var $arrCol = null;

  function queue($db) {
    parent::db_process($db,'queue','q_');
  }
	public function setKey($_key){
		$this->key = $_key;
	}
	public function setType($_type){
		$this->listtype = $_type;
	}
	public function bindBook(){
		$this->bindBook = true;
	} 
	public function getUnprocess(){
		$arr = array();
		$sql=<<<SQL
	select q_name as filename
	from bookshelf2_queue
	where status>=0
		and status<100
		and isdelete=0
		and q_retry<3
	order by q_id
	limit 1
SQL;
		$data=$this->db->query_first($sql);
		$arr['filename'] = $data['filename'];
		$sql=<<<SQL
	select count(*) as num
	from bookshelf2_queue
	where status>=0
		and status<100
		and isdelete=0
		and q_retry<3
SQL;
		$data=$this->db->query_first($sql);
		$arr['num'] = $data['num'];
		return $arr;
	}

	public function getNext(){
		$sql=<<<SQL
select *
from bookshelf2_queue
where status>=0 and isdelete=0 and status<100 and q_retry<3
order by q_priority desc, createdate asc, q_retry desc
limit 1
SQL;
		return $this->db->query_first($sql);
	}
/*
	public function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$where_str = 'status>=0 and isdelete=0';
		if(!empty($where)){
			$where_str = $where_str.' and '.$where;
		}
		$data = parent::getList($orderby,$limit_from,$offset,$where);
    return $data;
	}
*/
	public function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		global $db;
		global $ee;

    $order_str = empty($orderby)?$this->prefix.'id ASC':$orderby;
    $where_str = empty($where)?'':' and '.$where;
		//$where_str = 'status>=0 and isdelete=0';
    if(!empty($limit_from) || !empty($offset)){
      if(DB_TYPE=='dbmaker') $offset+=$limit_from;
      $limit_str = 'limit '.$limit_from.','.$offset;
    }
    $select_str =<<<SELECT
q.q_id,q.q_name,q.q_tmpname,q.q_key,q.q_data,q.q_retry,
q.q_priority,q.status,q.isdelete,q.createdate,q.editdate
SELECT;

		if($this->key){
			$where_str .= sprintf(" and q_key='%s'",$this->key);
		}
		switch($this->listtype){
			case QueueTypeEnum::SuccessList:
				$where_str .= ' and isdelete=0 and status=200';
				break;
			case QueueTypeEnum::ErrorList:
				$where_str .= sprintf(' and q_id in (select target_id from bookshelf2_maillog ml where ml_target=%u)',MailTypeEnum::UploadQueue);
				break;
			case QueueTypeEnum::UnprocessList:
				$where_str .= ' and isdelete=0 and status>=0 and status<100 and q_retry<3';
				break;
			case QueueTypeEnum::Converting:
				$where_str .= ' and isdelete=0 and status>0 and status<100 and q_retry<3';
				break;
			case QueueTypeEnum::FailureList:
				$ini = new ini(&$db);
				$row = $ini->getByKey('uploadqueue','notice');
				$notice_qid = (int)$row['val'];
				$row = $this->getNext();
				if(!empty($row)){
					$_where1 = sprintf('and q_id<=%u',$row['q_id']);
				}
				$_where=<<<SQL
 and isdelete=0 and q_id>%u %s
 and (status in (-1,-2,-3,-4)
 or (status=-7 or q_retry=3)
 or (status=100 and timestampdiff('m',editdate,now()) > 120)
 or (status>0 and status<100 and timestampdiff('m',editdate,now()) > 60)
 or (status=0 and q_retry=1 and timestampdiff('m',createdate,now()) > 120))
SQL;
				$where_str .= sprintf($_where,$notice_qid,$_where1);
				break;
			case QueueTypeEnum::CheckStatus:
				if(empty($this->key)){
					$ee->Error('406.60');
				}
				$where_str.=<<<SQL
 and status>=-1 
 and isdelete=0
 and (
	(status=200 and b_id in (select b_id from bookshelf2_books))
	or (status>=0 and status<100 and b_id is null))
SQL;
				break;
			case QueueTypeEnum::MailList:
				$join_str .= sprintf(' join bookshelf2_maillog ml on(q.q_id=ml.target_id and ml_target=%u) ',MailTypeEnum::UploadQueue);
				break;
		}
		if($this->bindBook){
			$select_str .=<<<SELECT
,b.b_id,b.b_name,b.b_description,b.file_id,b.webbook_link,b.ibook_link,
b.b_status,b.b_top,b.b_order,b.ecocat_id,b.share_bs_id,b.b_views_webbook,
b.b_views_ibook,b.b_type,b.bs_id,b.webbook_show,b.ibook_show,b.bs_key,
b.c_key,b.b_key,b.create_date
SELECT;
			$join_str .= ' left join bookshelf2_books b on(q.b_id=b.b_id)';
		}

		$cols = '*'; $_cols = '';
		if(is_array($this->arrCol)){
			foreach($this->arrCol as $k => $v){
				if(is_int($k)){
					$_cols .= ','.$v;
				}else{
					$_cols .= sprintf(",%s as %s",$k,$v);
				}
			}
			if(!empty($_cols)){
				$cols = substr($_cols,1);
			}
		}

    $sql =<<<SQL
select %s 
from (
	select %s 
	from %s q
	%s
) as t
where 1=1 %s
SQL;
    $sql = sprintf($sql,$cols,$select_str,$this->table,$join_str,$where_str);
    $sqlwithorder =  $sql.sprintf(' order by %s %s', $order_str,$limit_str);

    $data['result'] = $this->db->get_results($sqlwithorder);
    $record = $this->db->query_first('SELECT count(*) as record from ('.$sql.') as t;');
    $data['total'] = $record['record'];
    return $data;
	}

	public function getListByKey($key){
		$sql=<<<SQL
select *
from bookshelf2_queue
where status>=-1 and isdelete=0 and q_key='%s'
	and (
		(status=200 and b_id in (select b_id from bookshelf2_books))
		or (status>=0 and status<100 and b_id is null))
order by q_id asc
SQL;
		$sql = sprintf($sql,$key);
		return $this->db->get_results($sql);
	}

	public function getFailureList($qid){
		$row = $this->getNext();
		$where = '';
		if(!empty($row)){
			$where = sprintf('and q_id<=%u',$row['q_id']);
		}
		$sql=<<<SQL
select * from bookshelf2_queue
where isdelete=0 and q_id>%u %s
and (status<0 
or q_retry=3
or (status=100 and timestampdiff('m',editdate,now()) > 120)
or (status>0 and status<100 and timestampdiff('m',editdate,now()) > 60)
or (status=0 and q_retry=1 and timestampdiff('m',createdate,now()) > 120))
order by q_id asc
SQL;
		$sql=sprintf($sql,$qid,$where);
		return $this->db->get_results($sql);
	}

	public function retry($id){
		$sql=<<<SQL
update bookshelf2_queue set q_retry=q_retry+1, status=0 where q_id=%u and q_retry<3
SQL;
		$sql = sprintf($sql,$id);
		return $this->db->query($sql);
	}

	public function insert($data){
		if(!isset($data['q_retry'])){$data['q_retry']=0;}
		if(!isset($data['q_priority'])){$data['q_priority']=0;}
		if(!isset($data['status'])){$data['status']=0;}
		if(!isset($data['createdate'])){$data['createdate']=date('Y-m-d H:i:s');}
		return parent::insert($data);
	}


	public function del($id,$real=false){
		if($real){
exit;
			parent::del($id);
		}else{
			$sql=<<<SQL
update bookshelf2_queue set isdelete=1 where q_id = %u;
SQL;
			$sql = sprintf($sql,$id);
			return $this->db->query($sql);
		}
	}

	//logical delete
	public function delByKey($key,$real=false){
		if($real){
			$sql=<<<SQL
delete from bookshelf2_queue where q_id=%u;
SQL;
		}else{
			$sql=<<<SQL
update bookshelf2_queue set isdelete=1 where q_key = '%s';
SQL;
		}
		$sql = sprintf($sql,$key);
		return $this->db->query($sql);
	}

/*
QueueStatusEnum
Fail(-1) | delete(-2) | Wait(0) | Success(100)
*/
  public function updateStatus($id,$status){
		$sql=<<<SQL
update bookshelf2_queue set status=%d, editdate='%s' where q_id=%u
SQL;
		$sql = sprintf($sql,$status,date("Y-m-d H:i:s"),$id);
		return $this->db->query($sql);
  }

  public function updateStatusByBID($bid,$status){
		$sql=<<<SQL
update bookshelf2_queue set status=%d, editdate='%s' where b_id=%u
SQL;
		$sql = sprintf($sql,$status,date("Y-m-d H:i:s"),$bid);
		return $this->db->query($sql);
  }
}
