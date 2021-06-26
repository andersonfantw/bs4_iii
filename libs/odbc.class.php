<?php
# http://www.phpshuo.com/show/13_3528.html
# Name: DatabaseI.class.php
# File Description: MySQLi Class to allow easy and clean access to common mysqli commands
# Author: ricocheting
# Web: http://www.ricocheting.com/scripts/
# Update: 2/2/2009
# Version: 2.1
# Copyright 2003 ricocheting.com


/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/



//require("config.php");
//$db = new Database($config['server'],$config['user'],$config['pass'],$config['database'],$config['tablePrefix']);


###################################################################################################
###################################################################################################
###################################################################################################
class odbc {


var $server   = ""; //database server
var $user     = ""; //database login name
var $pass     = ""; //database login password
var $database = ""; //database name
var $pre      = ""; //table prefix


#######################
//internal info
var $record = array();

var $error = "";
var $errno = 0;

//table name affected by SQL query
var $field_table= "";

//number of rows affected by SQL query
var $affected_rows = 0;

var $link_id = 0;
var $query_id = 0;


#-#############################################
# desc: constructor
function odbc($server, $user, $pass, $database, $pre='', $socket=''){
    $this->server=$server;
    $this->user=$user;
    $this->pass=$pass;
    $this->database=$database;
    $this->pre=$pre;
    $this->socket=$socket;
}#-#constructor()

function getError(){
	return array('msg'=>$this->error,'code'=>$this->errno);
}

#-#############################################
# desc: connect and select database using vars above
# Param: $new_link can force connect() to open a new link, even if mysqli_connect() was called before with the same parameters
function connect() {
	$this->link_id=@odbc_connect ($this->server,$this->user,$this->pass);

	if (!$this->link_id) {//open failed
		$this->oops("Could not connect to server: <b>$this->server</b>.");
		}

	//$this->toPhpArray();

/*	if(!@mysqli_select_db($this->database, $this->link_id)) {//no database
		$this->oops("Could not open database: <b>$this->database</b>.");
		}
*/
	// unset the data so it can't be dumped
	$this->server='';
	$this->user='';
	$this->pass='';
	$this->database='';
}#-#connect()


#-#############################################
# desc: close the connection
function close() {
	if(!odbc_close($this->link_id)){
		$this->oops("Connection close failed.");
	}
}#-#close()

#-#############################################
# desc: get list of bs system table
function getTableInfo(){
	$result = odbc_tables($this->link_id);
	
	$tables = array();
	while (odbc_fetch_row($result)){
		if((odbc_result($result,"TABLE_TYPE")=="TABLE") && (strpos(odbc_result($result,"TABLE_NAME"),'BOOKSHELF2_')===0)){
			$tables[] = strtolower(odbc_result($result,"TABLE_NAME"));
		}
	}
	return $tables;
}#-#getTableInfo()

#-#############################################
# desc: get columns from table
function getColumnInfo($column_name){
  $outval = odbc_columns($this->link_id,$this->database,"%",$column_name,"%");
  $col = array();
  $columns = array();
  while (odbc_fetch_into($outval, $col)) {
    $colname = strtolower($col[3]);
    $columns[$colname]=array();
    $columns[$colname]['type']=$col[5];
    $columns[$colname]['length']=$col[6];
    $columns[$colname]['null']=$col[17];
  }
  return $columns;
}#-#getColumnInfo()

#-#############################################
# desc: echo php array format on screen
function toPhpArray(){
  $content = '';
  $tabledef = array();
  $tables = $this->getTableInfo();
  foreach($tables as $tablename){
		$tabledef[$tablename] = $this->getColumnInfo($tablename);
  }
  foreach($tabledef as $table=>$cols){
		$content .= sprintf("\$tabledef['%s']=array();\r\n",$table);
		foreach($cols as $col=>$prop){
			$content .= sprintf("\$tabledef['%s']['%s']=array();\r\n",$table,$col);
			foreach($prop as $p=>$v){
				$content .= sprintf("\$$this-\>tabledef['%s']['%s']['%s']='%s';\r\n",$table,$col,$p,$v);
			}
		}
  }
  $content = "<?PHP\r\n\$tabledef=array();\r\n".$content."\r\n?\>";
  return $content;
}#-#toPhpArray()

#-#############################################
# Desc: escapes characters to be mysqli ready
# Param: string
# returns: string
function escape($string) {
	if(get_magic_quotes_gpc()) $string = stripslashes($string);
	return addslashes($string);
}#-#escape()


#-#############################################
# Desc: executes SQL query to an open connection
# Param: (MySQL query) to execute
# returns: (query_id) for fetching results etc
function query($sql) {
	// do query
	$this->query_id = @odbc_exec($this->link_id, $sql);

	if (!$this->query_id) {
		$this->oops("<b>MySQL Query fail:</b> $sql");
	}

	$this->affected_rows = @odbc_num_rows($this->query_id);

	return $this->query_id;
}#-#query()


#-#############################################
# Desc: executes SQL multi query to an open connection
# Param: (MySQL query) to execute
# returns: (query_id) for fetching results etc
function multi_query($sql) {
	// do multi_query
	$this->query_id = @odbc_exec($this->link_id, $sql);

	if (!$this->query_id) {
		$this->oops("<b>MySQL Query fail:</b> $sql");
	}

	$this->affected_rows = @odbc_num_rows($this->query_id);

	return $this->query_id;
}#-#multi_query()


#-#############################################
# desc: fetches and returns results one line at a time
# param: query_id for mysqli run. if none specified, last used
# return: (array) fetched record(s)
function fetch_array($query_id=-1) {
	// retrieve row
	if ($query_id!=-1) {
		$this->query_id=$query_id;
	}

	if (isset($this->query_id)) {
		$this->record = @odbc_fetch_array($this->query_id);
		if(empty($this->record)) $this->record=array();
	}else{
		$this->oops("Invalid query_id: <b>$this->query_id</b>. Records could not be fetched.");
	}

	// unescape records
	/*if($this->record){
		$this->record=array_map("stripslashes", $this->record);
		//foreach($this->record as $key=>$val) {
		//	$this->record[$key]=stripslashes($val);
		//}
	}*/
	return $this->record;
}#-#fetch_array()


#-#############################################
# desc: returns all the results (not one row), 
# when multi queries, only the last query allow
# use limit.
# param: (MySQL query) the query to run on server
# returns: assoc array of ALL fetched results
function get_results($sql) {
	$limit = $this->get_limit($sql);
	$query_id = $this->multi_query($limit['sql']);

  if(!$query_id)
      return NULL;

	$out = array();
	if($limit['from']!=-1){
		$row_idx=intval($limit['from'])+1;
		$length=intval($limit['len']);
		$num=0;
		while($row = odbc_fetch_array($query_id,$row_idx)){
			$out[]=array_change_key_case($row);
			$num++;
			if (isset($length)){
				if($num>=$length)
				break;
			}
			$row_idx++;
		}
	}else{
	  $row_idx=0;
		while ($row = $this->fetch_array($query_id)){
	    $row_idx++;
	
			$out[] = array_change_key_case($row);
		}
	}

  $this->num_rows = @odbc_num_rows($query_id);
	$this->free_result($query_id);
	return $out;
}#-#get_results()


#-#############################################
# desc: call sp
# param: (MySQL query) the query to run on server
# returns: assoc array of ALL fetched results
function call_sp($sql){
	$query_id = $this->query('{'.$sql.'}');

  if(!$query_id)
	return NULL;

	return $query_id;
}


#-#############################################
# desc: frees the resultset
# param: query_id for mysqli run. if none specified, last used
function free_result($query_id=-1) {
	if ($query_id!=-1) {
		$this->query_id=$query_id;
	}
	if(!@odbc_free_result($this->query_id)) {
		$this->oops("Result ID: <b>$this->query_id</b> could not be freed.");
	}
}#-#free_result()


#-#############################################
# desc: does a query, fetches the first row only, frees resultset
# param: (MySQL query) the query to run on server
# returns: array of fetched results
function query_first($query_string) {
	$query_id = $this->query($query_string);
	$out[] = array_change_key_case($this->fetch_array($query_id));
	$this->free_result($query_id);
	return $out[0];
}#-#query_first()


#-#############################################
# desc: does an update query with an array
# param: table (no prefix), assoc array with data (doesn't need escaped), where condition
# returns: (query_id) for fetching results etc
function query_update($table, $data, $where='1') {
	$q="UPDATE `".$this->pre.$table."` SET ";

	foreach($data as $key=>$val) {
		if(strtolower($val)=='null') $q.= "`$key` = NULL, ";
		elseif(strtolower($val)=='now()') $q.= "`$key` = NOW(), ";
		else $q.= "`$key`='".$this->escape($val)."', ";
	}

	$q = rtrim($q, ', ') . ' WHERE '.$where.';';

	return $this->query($q);
}#-#query_update()


#-#############################################
# desc: does an insert query with an array
# param: table (no prefix), assoc array with data
# returns: id of inserted record, false if error
function query_insert($table, $data) {
	$q="INSERT INTO ".$this->pre.$table." ";
	$v=''; $n='';

	foreach($data as $key=>$val) {
		$n.="$key, ";
		if(strtolower($val)=='null') $v.="NULL, ";
		elseif(strtolower($val)=='now()') $v.="NOW(), ";
		else $v.= "'".$this->escape($val)."', ";
	}

	$q .= "(". rtrim($n, ', ') .") VALUES (". rtrim($v, ', ') .");";

	if($this->query($q)){
		$this->free_result();
		return $this->insert_id();
	}
	else return false;

}#-#query_insert()

#-#############################################
# desc: pick order by, asc/desc, limit values
# param: (MySQL query) the query to run on server
# returns: true if parse success
function limit_for_ansi_sql($sql){
	preg_match("/order +by +(\S+)(( +\S+){0,1}( +(\S+){0,1} +(\d+), *(\d*)){0,1}){0,1} *$/", $sql, $arr);
	$order_by = $arr[1];   # order by
	$order = $arr[3];		# asc/desc
	if(strtolower($order)=='desc')
		$opposite_order = "asc";
	else
		$opposite_order = "desc";
	$limit = $arr[4];			#limit
	$limit_from = $arr[6];	# limit from
	$limit_to = $arr[7];		# limit to
	if(isset($limit_from) && isset($limit_to)){
		$limit_num = intval($limit_to)-intval($limit_from);
	}
	$sql_without_limit = str_replace($limit,'',$sql);

	if(isset($limit_from)){
		if(isset($limit_to)){
			$tmpsql=<<<SQL
SELECT * FROM 
(
	SELECT TOP [limit_num] * FROM 
	(
		SELECT TOP [limit_to] * FROM
		(
			[sql_without_limit]
		) odbc_t0
	)	odbc_t1
	ORDER BY [order_by] [opposite_order] 
) odbc_t2
ORDER BY [order_by] [order]
SQL;
		}else{
			$tmpsql=<<<SQL
SELECT TOP [limit_from] * FROM
(
	[sql_without_limit]
) odbc_t0
ORDER BY [order_by] [order]
SQL;
		}

		$exec = $tmpsql;
		$exec=str_replace('[limit_from]',$limit_from,$exec);
		$exec=str_replace('[limit_to]',$limit_to,$exec);
		$exec=str_replace('[limit_num]',$limit_num,$exec);
		$exec=str_replace('[sql_without_limit]',$sql_without_limit,$exec);
		$exec=str_replace('[order]',$order,$exec);
		$exec=str_replace('[opposite_order]',$opposite_order,$exec);
		$exec=str_replace('[order_by]',$order_by,$exec);
  }else{
    $exec = $sql;

		return $exec;
	}
	#order +by +(\S+)(( +desc| +asc){0,1}( +(limit){0,1} +(\d+), *(\d*)){0,1}){0,1}
}#-#parse_sql()

#-#############################################
# desc: pick order by, asc/desc, limit values
# param: (MySQL query) the query to run on server
# returns: limit array
function get_limit($sql){
	preg_match("/order +by +(\S+)(( +\S+){0,1}( +(\S+){0,1} +(\d+), *(\d*)){0,1}){0,1} *$/", $sql, $arr);
	$order_by = $arr[1];   # order by
	$order = $arr[3];		# asc/desc
	if(strtolower($order)=='desc')
		$opposite_order = "asc";
	else
		$opposite_order = "desc";
	$limit = $arr[4];			#limit
	$limit_from = $arr[6];	# limit from
	$limit_to = $arr[7];		# limit to
	
	$sql_without_limit = str_replace($limit,'',$sql);
	if(isset($limit_from) && isset($limit_to)){
		$limit_num = intval($limit_to)-intval($limit_from);
		$top = array('sql'=>$sql_without_limit,'from'=>intval($limit_from),'to'=>intval($limit_to),'len'=>intval($limit_num));
	}elseif(isset($limit_from)){
		$top = array('sql'=>$sql_without_limit,'from'=>0,'to'=>intval($limit_from),'len'=>intval($limit_from));
	}else{
		$top = array('sql'=>$sql_without_limit,'from'=>-1,'to'=>-1,'len'=>-1);
	}
	return $top;
}


#-#############################################
# desc: throw an error message
# param: [optional] any custom error to display
function oops($msg='') {
	if($this->link_id>0){
		$this->error=odbc_errormsg($this->link_id);
		$this->errno=odbc_error($this->link_id);
	}
	else{
		$this->error=odbc_errormsg();
		$this->errno=odbc_error();
	}
	?>
		<table align="center" border="1" cellspacing="0" style="background:white;color:black;width:80%;">
		<tr><th colspan=2>Database Error</th></tr>
		<tr><td align="right" valign="top">Message:</td><td><?php echo $msg; ?></td></tr>
		<?php if(strlen($this->error)>0) echo '<tr><td align="right" valign="top" nowrap>MySQL Error:</td><td>'.$this->error.'</td></tr>'; ?>
		<tr><td align="right">Date:</td><td><?php echo date("l, F j, Y \a\\t g:i:s A"); ?></td></tr>
		<tr><td align="right">Script:</td><td><a href="<?php echo @$_SERVER['REQUEST_URI']; ?>"><?php echo @$_SERVER['REQUEST_URI']; ?></a></td></tr>
		<?php if(strlen(@$_SERVER['HTTP_REFERER'])>0) echo '<tr><td align="right">Referer:</td><td><a href="'.@$_SERVER['HTTP_REFERER'].'">'.@$_SERVER['HTTP_REFERER'].'</a></td></tr>'; ?>
		</table>
	<?php
}#-#oops()


  function num_rows($query_id=-1) {
    // returns number of rows in query
    if ($query_id!=-1) {
      $this->query_id=$query_id;
    }
    return mysqli_num_rows($this->query_id);
  }


  function insert_id() {
    // returns last auto_increment field number assigned
    $row = $this->query_first("select LAST_SERIAL from SYSCONINFO");
    return $row['last_serial'];
  }

}//CLASS Database
###################################################################################################

?>
