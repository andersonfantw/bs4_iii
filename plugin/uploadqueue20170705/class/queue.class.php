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
  function queue($db) {
    parent::db_process($db,'queue','q_');
  }

	public function getUnprocess(){
		$sql=<<<SQL
select count(*) as num
from bookshelf2_queue
where status>=0
	and status<100
	and isdelete=0
	and q_retry<3
SQL;
		return $this->db->query_first($sql);
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

	public function getList($orderby='',$limit_from=0 ,$offset=0,$where=''){
		$where_str = 'status>=0 and isdelete=0';
		if(!empty($where)){
			$where_str = $where_str.' and '.$where;
		}
		$data = parent::getList($orderby,$limit_from,$offset,$where);
    return $data;
	}

	public function getListByKey($key){
		$sql=<<<SQL
select *
from bookshelf2_queue
where status>=-1 and isdelete=0 and q_key='%s'
order by q_id asc
SQL;
		$sql = sprintf($sql,$key);
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
