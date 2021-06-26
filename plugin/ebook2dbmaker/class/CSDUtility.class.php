<?
require_once('common.Utility.php');
class CSDUtility{
	public function ebook2dbmaker($uid, $bsid, $bookid, $ebook_folder, $orifile){
		$ret=array("rc"=>0, "errmsg"=>"");
		
		if ((!isset($uid)) || (!isset($bsid)) || (!isset($bookid)) || (!isset($ebook_folder)) || (!isset($orifile)))
			return array("rc"=>1, "errmsg"=>"parameter error");
		
		if(($uid<0) || ($bsid<0))
			return array("rc"=>1, "errmsg"=>"parameter uid or bsid error");
		
		if((!isset($orifile['filename'])) || (!isset($orifile['filepath'])))
			return array("rc"=>1, "errmsg"=>"parameter orifile error");
			
		
		$db=kwcr2_mapdb("cybersite", 'Program', 'wishbone');
		
		if($db){
			$rc=0;
			$errmsg="";
			do{
				/*$r=read_one_record($db, "select CreateTime,Randnum from FM_FileFolder where OwnerID=2 and Name='@@WEB' and SubType=3", array());
				if (($r===false) || (!isset($r))) {
					$rc=1;
					$errmsg="get root node fail".kwcr2_geterrormsg($db, 1);
					break;
				}*/
				$r=array();
				$r[0]="2012-10-03 11:37:18.596";
				$r[1]=1989311423;
				//2012-10-03 11:37:18.596      1989311423
				$r1=read_one_record($db, "select CreateTime,Randnum from FM_FileFolder where OwnerID=2 and Par_CreateTime=? and Par_Randnum=? and Name=? and SubType=3", array($r[0],$r[1],$uid));
				if($r1===false){
					$rc=1;
					$errmsg="check user id node fail".kwcr2_geterrormsg($db, 1);
					break;
				}
				if(!isset($r1)){
					//insert userid node
					//$sql="insert into FM_FileFolder (OwnerID,PAR_CREATETIME,PAR_RANDNUM,Type,Name,Path,SubType,Index) values (2,?,?,1,?,?,3,0)";
					if (!kwcr2_rawqueryexec($db, "insert into FM_FileFolder (OwnerID, Par_CreateTime, Par_RandNum, Type, Name, Path, SubType) values (2,?,?,1,?,?,3)", array($r[0], $r[1], $uid, "@@WEB/".$uid."/"), "")) {
						$rc=1;
						$errmsg="Insert user id node fail".kwcr2_geterrormsg($db, 1);
						break;
					}
					
					$oid_r=read_one_record($db, "select LAST_OID from SYSCONINFO", array());
					if (($oid_r===false) || (!isset($oid_r))) {
						$rc=1;
						$errmsg="get new OID fail!".kwcr2_geterrormsg($db, 1);
						break;
					}
					$r1=read_one_record($db, "select CreateTime, RandNum from FM_FileFolder where OID=?", array($oid_r[0]));
					if (($r1===false) || (!isset($r1))) {
						$rc=1;
						$errmsg="get new OID fail!".kwcr2_geterrormsg($db, 1);
						break;
					}
					$user_id_node=array('_ctime'=>$r1[0],'_randn'=>$r1[1]);
					
				}else{
					$user_id_node=array('_ctime'=>$r1[0],'_randn'=>$r1[1]);
				}
				
				
				$r1=read_one_record($db, "select CreateTime,Randnum from FM_FileFolder where OwnerID=2 and Par_CreateTime=? and Par_Randnum=? and Name=? and SubType=3", array($user_id_node['_ctime'],$user_id_node['_randn'],$bsid));
				if($r1===false){
					$rc=1;
					$errmsg="check bs id node fail".kwcr2_geterrormsg($db, 1);
					break;
				}
				if(!isset($r1)){
					//insert bsid node
					//$sql="insert into FM_FileFolder (OwnerID,PAR_CREATETIME,PAR_RANDNUM,Type,Name,Path,SubType,Index) values (2,?,?,1,?,?,3,0)";
					if (!kwcr2_rawqueryexec($db, "insert into FM_FileFolder (OwnerID, Par_CreateTime, Par_RandNum, Type, Name, Path, SubType) values (2,?,?,1,?,?,3)", array($user_id_node['_ctime'], $user_id_node['_randn'], $bsid, "@@WEB/".$uid."/".$bsid."/"), "")) {
						$rc=1;
						$errmsg="Insert bs id node fail".kwcr2_geterrormsg($db, 1);
						break;
					}
					
					$oid_r=read_one_record($db, "select LAST_OID from SYSCONINFO", array());
					if (($oid_r===false) || (!isset($oid_r))) {
						$rc=1;
						$errmsg="get new OID fail!".kwcr2_geterrormsg($db, 1);
						break;
					}
					$r1=read_one_record($db, "select CreateTime, RandNum from FM_FileFolder where OID=?", array($oid_r[0]));
					if (($r1===false) || (!isset($r1))) {
						$rc=1;
						$errmsg="get new OID fail!".kwcr2_geterrormsg($db, 1);
						break;
					}
					$bs_id_node=array('_ctime'=>$r1[0],'_randn'=>$r1[1]);
					
				}else{
					$bs_id_node=array('_ctime'=>$r1[0],'_randn'=>$r1[1]);
				}
			}while(0);
			kwcr2_unmapdb($db);
			
			if($rc>0){
				return array("rc"=>$rc, "errmsg"=>$errmsg);
			}
		}else{
			return array("rc"=>1, "errmsg"=>"connect db fail");
		}
	
		$fname="/var/cyberhood/queue/incoming/ebook2dbmaker/".$bookid.".in";
		$fp = fopen ($fname, "w");
		if($fp===FALSE){
			return array("rc"=>2, "errmsg"=>"file open fail!");
		}
	
		fwrite($fp,"UserID: ".$uid."\n");
		fwrite($fp,"BookshelfID: ".$bsid."\n");
		fwrite($fp,"BookID: ".$bookid."\n");
		fwrite($fp,"BookPath: ".$ebook_folder."\n");
		fwrite($fp,"OriFile: ".$orifile['filename']."\n");
		fwrite($fp,"OriFilePath: ".$orifile['filepath']."\n");
		fclose($fp);
	
		return array("rc"=>0, "errmsg"=>"");
	}
	
	/*$ret=ebook2dbmaker(1,3, "1409218865", "/var/www/html/bs3/ecocatcms211/lib/ecolab/export/010014c1_6853c00b", array('filename'=>"測試.pdf",'filepath'=>"/tmp/測試.pdf"));
	echo $ret["rc"];
	echo "<br>";
	echo $ret["errmsg"];
	*/


  public function delete_ebook($uid, $bsid, $bookid){
    $ret=array("rc"=>0, "errmsg"=>"");

    if ((!isset($uid)) || (!isset($bsid)) || (!isset($bookid)))
			return array("rc"=>1, "errmsg"=>"parameter error");

    if(($uid<0) || ($bsid<0))
			return array("rc"=>1, "errmsg"=>"parameter uid or bsid error");
    error_log("[delete_ebook]uid=".$uid,0);
    error_log("[delete_ebook]bsid=".$bsid,0);
    error_log("[delete_ebook]bookid=".$bookid,0);
    $db=kwcr2_mapdb("cybersite", 'Program', 'wishbone');

    if($db){
      $rc=0;
      $errmsg="";
      do{
        $book_folder_path="@@WEB/".$uid."/".$bsid."/".$bookid."/";
        $r=read_one_record($db, "select createtime,randnum from fm_filefolder where ownerid=2 and type=1 and subtype=3 and Path=?", array($book_folder_path));
        if ($r===false){
	        $rc=1;
	        $errmsg="bookid not found".kwcr2_geterrormsg($db, 1);
	        break;
        }
        if (isset($r)){
        	//delete from FM_FileFolder where OwnerID=2 and LEFT(Path, ?)=?, array(strlen($book_folder_path), $book_folder_path)
          if (!kwcr2_rawqueryexec($db, "delete from FM_FileFolder where Path like '".$book_folder_path."%'", array(), "")){
            $rc=2;
            $errmsg="delete book failed".kwcr2_geterrormsg($db, 1);
            break;
          }
        }
      }while(0);
      kwcr2_unmapdb($db);
      return array("rc"=>$rc, "errmsg"=>$errmsg);
    }else{
			return array("rc"=>1, "errmsg"=>"connect db fail");
    }
  }

	function move_ebook($old_path,$new_path){
		$ret=array("rc"=>0, "errmsg"=>"");

		if ((!isset($old_path["uid"])) || (!isset($old_path["bsid"])) || (!isset($old_path["bookid"])))
			return array("rc"=>1, "errmsg"=>"parameter error");
		if ((!isset($new_path["uid"])) || (!isset($new_path["bsid"])) || (!isset($new_path["bookid"])))
			return array("rc"=>1, "errmsg"=>"parameter error");

    	if(($old_path["uid"]<0) || ($old_path["bsid"]<0))
			return array("rc"=>1, "errmsg"=>"parameter uid or bsid error");
		if(($new_path["uid"]<0) || ($new_path["bsid"]<0))
			return array("rc"=>1, "errmsg"=>"parameter uid or bsid error");
	
    	//error_log("[move_ebook]old_path=".$old_path["uid"]."/".$old_path["bsid"]."/".$old_path["bookid"],0);
    	//error_log("[move_ebook]new_path=".$new_path["uid"]."/".$new_path["bsid"]."/".$new_path["bookid"],0);

    	$db=kwcr2_mapdb("cybersite", 'Program', 'wishbone');

    	if($db){
    		$rc=0;
    		$errmsg="";
    		do{
    			$book_folder_path="@@WEB/".$old_path["uid"]."/".$old_path["bsid"]."/".$old_path["bookid"]."/";
    			$book_r=read_one_record($db, "select createtime,randnum from fm_filefolder where Path='".$book_folder_path."' and type=1 for browse", array());
        		if (($book_r===false) || (!isset($book_r))){
	        		$rc=1;
	        		$errmsg="bookid not found".kwcr2_geterrormsg($db, 1);
	        		break;
        		}
        
       			$new_book_folder_path="@@WEB/".$new_path["uid"]."/".$new_path["bsid"]."/".$new_path["bookid"]."/";
       			$new_r=read_one_record($db, "select createtime,randnum from fm_filefolder where Path='".$new_book_folder_path."' and type=1 for browse", array($new_book_folder_path));
        		if ($new_r===false){
	        		$rc=1;
	        		$errmsg="check new path error".kwcr2_geterrormsg($db, 1);
	        		break;
        		}

        		if(isset($new_r)){
        			$rc=1;
	        		$errmsg="new path already exist";
	        		break;
        		}

        		/*$par_r=read_one_record($db, "select CreateTime,Randnum from FM_FileFolder where OwnerID=2 and Name='@@WEB' and SubType=3", array());
				if (($par_r===false) || (!isset($par_r))) {
					$rc=1;
					$errmsg="get root node fail".kwcr2_geterrormsg($db, 1);
					break;
				}*/
				$par_r=array();
				$par_r[0]="2012-10-03 11:37:18.596";
				$par_r[1]=1989311423;
				
				$par_r1=read_one_record($db, "select CreateTime,Randnum from FM_FileFolder where OwnerID=2 and Par_CreateTime=? and Par_Randnum=? and Name=? and SubType=3", array($par_r[0],$par_r[1],$new_path["uid"]));
				if($par_r1===false){
					$rc=1;
					$errmsg="check user id node fail".kwcr2_geterrormsg($db, 1);
					break;
				}
				if(!isset($par_r1)){
					//insert userid node
					//$sql="insert into FM_FileFolder (OwnerID,PAR_CREATETIME,PAR_RANDNUM,Type,Name,Path,SubType,Index) values (2,?,?,1,?,?,3,0)";
					if (!kwcr2_rawqueryexec($db, "insert into FM_FileFolder (OwnerID, Par_CreateTime, Par_RandNum, Type, Name, Path, SubType) values (2,?,?,1,?,?,3)", array($par_r[0], $par_r[1], $new_path["uid"], "@@WEB/".$new_path["uid"]."/"), "")) {
						$rc=1;
						$errmsg="Insert user id node fail".kwcr2_geterrormsg($db, 1);
						break;
					}

					$oid_r=read_one_record($db, "select LAST_OID from SYSCONINFO", array());
					if (($oid_r===false) || (!isset($oid_r))) {
						$rc=1;
						$errmsg="get new OID fail!".kwcr2_geterrormsg($db, 1);
						break;
					}
					$par_r1=read_one_record($db, "select CreateTime, RandNum from FM_FileFolder where OID=?", array($oid_r[0]));
					if (($par_r1===false) || (!isset($par_r1))) {
						$rc=1;
						$errmsg="get new OID fail!".kwcr2_geterrormsg($db, 1);
						break;
					}
					$new_user_id_node=array('_ctime'=>$par_r1[0],'_randn'=>$par_r1[1]);
					
				}else{
					$new_user_id_node=array('_ctime'=>$par_r1[0],'_randn'=>$par_r1[1]);
				}


				$r1=read_one_record($db, "select CreateTime,Randnum from FM_FileFolder where OwnerID=2 and Par_CreateTime=? and Par_Randnum=? and Name=? and SubType=3", array($new_user_id_node['_ctime'],$new_user_id_node['_randn'],$new_path["bsid"]));
				if($r1===false){
					$rc=1;
					$errmsg="check bs id node fail".kwcr2_geterrormsg($db, 1);
					break;
				}
				if(!isset($r1)){
					//insert bsid node
					//$sql="insert into FM_FileFolder (OwnerID,PAR_CREATETIME,PAR_RANDNUM,Type,Name,Path,SubType,Index) values (2,?,?,1,?,?,3,0)";
					if (!kwcr2_rawqueryexec($db, "insert into FM_FileFolder (OwnerID, Par_CreateTime, Par_RandNum, Type, Name, Path, SubType) values (2,?,?,1,?,?,3)", array($new_user_id_node['_ctime'], $new_user_id_node['_randn'], $new_path["bsid"], "@@WEB/".$new_path["uid"]."/".$new_path["bsid"]."/"), "")) {
						$rc=1;
						$errmsg="Insert bs id node fail".kwcr2_geterrormsg($db, 1);
						break;
					}
					
					$oid_r=read_one_record($db, "select LAST_OID from SYSCONINFO", array());
					if (($oid_r===false) || (!isset($oid_r))) {
						$rc=1;
						$errmsg="get new OID fail!".kwcr2_geterrormsg($db, 1);
						break;
					}
					$r1=read_one_record($db, "select CreateTime, RandNum from FM_FileFolder where OID=?", array($oid_r[0]));
					if (($r1===false) || (!isset($r1))) {
						$rc=1;
						$errmsg="get new OID fail!".kwcr2_geterrormsg($db, 1);
						break;
					}
					$new_bs_id_node=array('_ctime'=>$r1[0],'_randn'=>$r1[1]);
			
				}else{
					$new_bs_id_node=array('_ctime'=>$r1[0],'_randn'=>$r1[1]);
				}
        
        		//move book root folder
        		$pm=array();
        		$pm[]=$new_bs_id_node['_ctime'];
        		$pm[]=$new_bs_id_node['_randn'];
        		$pm[]=$new_path["bookid"];
        		$pm[]="@@WEB/".$new_path["uid"]."/".$new_path["bsid"]."/".$new_path["bookid"]."/";
        		$pm[]=$book_r[0];
        		$pm[]=$book_r[1];
        		if (!kwcr2_rawqueryexec($db, "update FM_FileFolder set Par_CreateTime=?,Par_Randnum=?,Name=?,Path=? where OwnerID=2 and CreateTime=? and Randnum=? and Type=1", $pm, "")){
            		$rc=2;
            		$errmsg="move book failed".kwcr2_geterrormsg($db, 1);
            		break;
        		}
        
        		//move sub-node..  
        		//$book_folder_path="@@WEB/".$old_path["uid"]."/".$old_path["bsid"]."/".$old_path["bookid"]."/";
        		$i=strlen($book_folder_path);
				if (!kwcr2_rawqueryexec($db, "update FM_FileFolder set Path=? || RIGHT(Path, LENGTH(Path)-?) where Path like '".$book_folder_path."%'", array($new_book_folder_path, $i), "")) {
					$rc=2;
            		$errmsg="move sub-node failed".kwcr2_geterrormsg($db, 1);
            		break;
				}
      		}while(0);
      		kwcr2_unmapdb($db);
      		return array("rc"=>$rc, "errmsg"=>$errmsg);
    	}else{
				return array("rc"=>1, "errmsg"=>"connect db fail");
    	}
  }
}
?>
