<?php
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
class DatabaseI {


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
function DatabaseI($server, $user, $pass, $database, $pre='', $socket=''){
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
	$this->link_id=@mysqli_connect($this->server,$this->user,$this->pass,$this->database,ini_get("mysqli.default_port"),$this->socket);

	if (!$this->link_id) {//open failed
		$this->oops("Could not connect to server: <b>$this->server</b>.");
		}

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
	if(!mysqli_close($this->link_id)){
		$this->oops("Connection close failed.");
	}
}#-#close()


#-#############################################
# Desc: escapes characters to be mysqli ready
# Param: string
# returns: string
function escape($string) {
	if(get_magic_quotes_gpc()) $string = stripslashes($string);
	return mysqli_real_escape_string($string);
}#-#escape()


#-#############################################
# Desc: executes SQL query to an open connection
# Param: (MySQL query) to execute
# returns: (query_id) for fetching results etc
function query($sql) {
	// do query
	$this->query_id = @mysqli_query($this->link_id,$sql);
	if (!$this->query_id) {
		$this->oops("<b>MySQL Query fail:</b> $sql");
	}

	$this->affected_rows = @mysqli_affected_rows();

	return $this->query_id;
}#-#query()


#-#############################################
# Desc: executes SQL multi query to an open connection
# Param: (MySQL query) to execute
# returns: (query_id) for fetching results etc
function multi_query($sql) {
	// do multi_query
	$this->query_id = @mysqli_multi_query($this->link_id, $sql);

	if (!$this->query_id) {
		$this->oops("<b>MySQL Query fail:</b> $sql");
	}

	$this->affected_rows = @mysqli_affected_rows();

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
		$this->record = @mysqli_fetch_assoc($this->query_id);
	}else{
		$this->oops("Invalid query_id: Records could not be fetched.");
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
# desc: returns all the results (not one row)
# param: (MySQL query) the query to run on server
# returns: assoc array of ALL fetched results
function get_results($sql) {
	$query_id = $this->multi_query($sql);

  if(!$query_id)
      return NULL;

	$out = array();
  $i=0;
	while ($row = $this->fetch_array($query_id)){
    $i++;

		$out[] = $row;
	}

  $this->num_rows = $i;
	//$this->free_result($query_id);
	return $out;
}#-#fetch_all_array()


#-#############################################
# desc: call sp
# param: (MySQL query) the query to run on server
# returns: assoc array of ALL fetched results
function call_sp($sql){
	$query_id = $this->multi_query($sql);

  if(!$query_id)
	return NULL;

  if($ecocat_rs = mysqli_store_result($this->link_id)){
	return mysqli_fetch_row($ecocat_rs);
  }
}


#-#############################################
# desc: frees the resultset
# param: query_id for mysqli run. if none specified, last used
function free_result($query_id=-1) {
	if ($query_id!=-1) {
		$this->query_id=$query_id;
	}
	mysqli_free_result($this->query_id);
}#-#free_result()


#-#############################################
# desc: does a query, fetches the first row only, frees resultset
# param: (MySQL query) the query to run on server
# returns: array of fetched results
function query_first($sql) {
	$this->query_id = $this->query($sql);

	$out = $this->fetch_array($this->query_id);
	$this->free_result($this->query_id);
	return $out;
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
	$q="INSERT INTO `".$this->pre.$table."` ";
	$v=''; $n='';

	foreach($data as $key=>$val) {
		$n.="`$key`, ";
		if(strtolower($val)=='null') $v.="NULL, ";
		elseif(strtolower($val)=='now()') $v.="NOW(), ";
		else $v.= "'".$this->escape($val)."', ";
	}

	$q .= "(". rtrim($n, ', ') .") VALUES (". rtrim($v, ', ') .");";

	if($this->query($q)){
		//$this->free_result();
		return mysqli_insert_id();
	}
	else return false;

}#-#query_insert()


#-#############################################
# desc: throw an error message
# param: [optional] any custom error to display
function oops($msg='') {
	if($this->link_id>0){
		$this->error=mysqli_error($this->link_id);
		$this->errno=mysqli_errno($this->link_id);
	}
	else{
		$this->error=mysqli_connect_error();
		$this->errno=mysqli_connect_errno();
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
    return mysqli_insert_id($this->link_id);
  }

}//CLASS Database
###################################################################################################

?>
