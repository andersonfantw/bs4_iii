<?PHP
require_once('pear/Crypt/Blowfish.php');
class common{
	const TOKEN_SEPARATOR='|-|';
	
	public function encryptString ($str){
      $cbf = new Crypt_Blowfish(ENCRYPT_KEY);
      $str = $cbf->encrypt($str);
      $str = base64_encode($str);	
	    return $str;
	}

	public function decryptString ($str) {
	    $str = base64_decode($str);
	    $cbf = new Crypt_Blowfish(ENCRYPT_KEY);
	    $str = $cbf->decrypt($str);
	    if($str===NULL) return false;
	    return rtrim($str);
	}

	public function makeToken(){
		return self::encryptString(time() . self::TOKEN_SEPARATOR . GLOBAL_IDENTIFIER);
	}

	//Valid time is 120sec
	public function checkToken($request_token,$valid_sec=120){
        // init
        $token_info = array();
        // get split token
        $token_info = self::split_token($request_token);
        if(is_null($token_info)) return null;
        // check 60 sec
        if ((time() - $token_info[0]) > $valid_sec) {
            return false;
        } else {
            return true;
        }
	}

	//valid session id
	public function validSessionID($sid){
		return !empty($sid) && preg_match('/^[a-zA-Z0-9]{26,40}$/', $sid);
	}

  public function split_token($token) {
  	$val = self::decryptString($token);
  	if($val===false) return null;
  	return explode(self::TOKEN_SEPARATOR, $val);
  }

	public function remove_directory($dir) {
		$check_list = array(HOST_PATH,UPGRADE_PATH,DBBACKUP_PATH);
		$valid=false;
		foreach($check_list as $path){
			if((strpos($dir,$path)!==false) && ($dir!=$path)){
				$valid=true;
			}
		}
		if(!$valid){
			return false;
		}

    if ($handle = opendir("$dir")) {
      while (false !== ($item = readdir($handle))) {
        if ($item != "." && $item != "..") {
          if (is_dir("$dir/$item")) {
            self::remove_directory("$dir/$item");
          } else {	
            unlink("$dir/$item");
          }
        }
      }
      closedir($handle);
      rmdir($dir);
    }
		//exec(sprintf('rm -rf %s',$dir));
		return true;
	}

	//move to upload folder
	//insert db
	//resize image
	public function insert_host_image($uploadfile,$f_id=0,$resize=false,$uid=0,$bsid=0){
		$base = HostManager::getBookshelfBase(false,true,$uid,$bsid);
		return self::insert_image($uploadfile,$base,$f_id,$resize);
	}

	public function insert_sys_image($uploadfile,$f_id=0,$resize=false){
		$base = ROOT_PATH.'/';
		return self::insert_image($uploadfile,$base,$f_id,$resize);
	}

	public function isInTmpFolder($path){
		return (strpos($path,sys_get_temp_dir())!==false);
	}

	public function insert_image($uploadfile,$base,$fid=0,$resize=false){
		global $db;

    $foo = new uploadimage($uploadfile,$base.'/'.FILE_UPLOAD_PATH);
    $out=array();
    if ($foo->uploaded) {
      $new_filename = date('YmdHis').substr(md5($foo->file_src_name),0,3);
      // save uploaded image with a new name
      if ($foo->createimage($new_filename) ) {
        //insert file data to database
        $files = new files($db);
        $file_data['f_name'] = $new_filename;
        $file_data['f_path'] = FILE_UPLOAD_PATH.$new_filename.'.'.$foo->file_dst_name_ext;
        $file_data['f_type'] = $foo->file_dst_name_ext;

        if(!$fid){
          //new file upload
          $f_id = $files->insert($file_data,true);
          $f_id = ($f_id>0)?$f_id:0;
        }else{
        	$rs = $files->getByID($fid);
					@unlink($host_base."/".$rs['file_path']);
					if($resize){
						foreach($resize as $key=>$val){
							$path_parts = common::path_info($rs['file_path']);
							$path = $host_base."/".$path_parts['basename']."/".$key.$path_parts['basename'];

							$path = $base."/".$key.$rs['file_path'];
							if(file_exists($path) && is_file($path)){
								@unlink($path);
							}
						}
					}
          //replace file
					$frs = $files->update($fid,$file_data);          
				}

			} else {echo 'error : ' . $foo->error;}
			if($resize){
				foreach($resize as $key=>$val){
					if (!$foo->createimage($key.$new_filename,$val['w'],$val['h']) ) {echo 'error : ' . $foo->error;}
				}
			}

      //$foo->Clean();	//this will delete source file
      $out['id']=$f_id;
      $out['name']=$new_filename.'.'.$foo->file_dst_name_ext;
      $out['path']=$file_data['f_path'];
      return $out;
    }
    $out['id']=0;
    return $out;
	}


	public static function remove_file($type,$f_id,$uid=0,$bsid=0){
		global $db;
		switch($type){
			case 'host':
				break;
				$base = HostManager::getBookshelfBase(false,true,$uid,$bsid);
			case 'sys':
				break;
				$base = ROOT_PATH.'/';
		}
		$files = new files(&$db);
		$data = $files->getByID($f_id);
		$path = $base.$data['f_path'];
		if(is_file($path)) @unlink($path);
		$files->del($f_id);
		return true;
	}

	public function _rrmdir($dir){
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") self::_rrmdir($dir."/".$object);
					else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}

	public function rrmtmpdir($dir){
		//make sure $dir is under sys_temp_dir;
		if(strpos($dir,sys_get_temp_dir())===false){
			return false;
		}
		self::_rrmdir($dir);
	}

	public function rrmbookdir($dir){
		$_str = str_replace(HOST_PATH,'',$dir);
		$result = preg_match('/^(\/\d+\/\d+\/files\/[^\/]+\/)/',$_str,$matches);
		if($result){
			if(file_exists(HOST_PATH.$matches[1])){
				self::_rrmdir(HOST_PATH.$matches[1]);
			}
		}
	}

	public static function get_wonderbox_id(){
		$ret=array('rc'=>0, 'errmsg'=>"", 'wbox_id'=>"");

		$cmd="curl -s -o \"/tmp/wbox.info\" \"http://127.0.0.1:20001/unet/system.Environment\"";
		system($cmd, $rc_curl);
		if ($rc_curl==0){
			$ss1=@file("/tmp/wbox.info");
			if (($ss1===false)||(count($ss1)==0)||(count($ss1)>2)){
				$ret["rc"]=1;
				$ret["errmsg"]="get wbox info fail(1)";
				return $ret;
			}
			$i=-1;
			foreach ($ss1 as $s){
				$i++;
				$s=rtrim($s);
				$data=explode(",",$s);
				if ($i==0){
					if($data[0]==0){
						continue;
					}else{
						$ret["rc"]=1;
						$ret["errmsg"]="get wbox info fail(2)(".$data[1].")";
						return $ret;
					}
				}
				if($i==1){
					$ret["wbox_id"]=trim($data[0],"\"");
					return $ret;
				}
			}
		}else{
			$ret["rc"]=1;
			$ret["errmsg"]="get wbox info fail(call curl fail)(".$rc_curl.")";
		}
		return $ret;
	}

	public function _mime_content_type($filename) {
	  $mime_types = array(
	
	      'txt' => 'text/plain',
	      'htm' => 'text/html',
	      'html' => 'text/html',
	      'php' => 'text/html',
	      'css' => 'text/css',
	      'js' => 'application/javascript',
	      'json' => 'application/json',
	      'xml' => 'application/xml',
	      'swf' => 'application/x-shockwave-flash',
	      'flv' => 'video/x-flv',
	
	      // images
	      'png' => 'image/png',
	      'jpe' => 'image/jpeg',
	      'jpeg' => 'image/jpeg',
	      'jpg' => 'image/jpeg',
	      'gif' => 'image/gif',
	      'bmp' => 'image/bmp',
	      'ico' => 'image/vnd.microsoft.icon',
	      'tiff' => 'image/tiff',
	      'tif' => 'image/tiff',
	      'svg' => 'image/svg+xml',
	      'svgz' => 'image/svg+xml',
	
	      // archives
	      'zip' => 'application/zip',
	      'rar' => 'application/x-rar-compressed',
	      'exe' => 'application/x-msdownload',
	      'msi' => 'application/x-msdownload',
	      'cab' => 'application/vnd.ms-cab-compressed',
	
	      // audio/video
	      'mp3' => 'audio/mpeg',
	      'qt' => 'video/quicktime',
	      'mov' => 'video/quicktime',
	
	      // adobe
	      'pdf' => 'application/pdf',
	      'psd' => 'image/vnd.adobe.photoshop',
	      'ai' => 'application/postscript',
	      'eps' => 'application/postscript',
	      'ps' => 'application/postscript',
	
	      // ms office
	      'doc' => 'application/msword',
	      'rtf' => 'application/rtf',
	      'xls' => 'application/vnd.ms-excel',
	      'ppt' => 'application/vnd.ms-powerpoint',
	
	      // open office
	      'odt' => 'application/vnd.oasis.opendocument.text',
	      'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	  );
	
	  $ext = strtolower(array_pop(explode('.',$filename)));
	  if (array_key_exists($ext, $mime_types)) {
	      return $mime_types[$ext];
	  }
	  elseif (function_exists('finfo_open')) {
	      $finfo = finfo_open(FILEINFO_MIME);
	      $mimetype = finfo_file($finfo, $filename);
	      finfo_close($finfo);
	      return $mimetype;
	  }
	  else {
	      return 'application/octet-stream';
	  }
  }
/*
	public function page404(){
	  header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
	  header("Status: 404 Not Found");
	  $_SERVER['REDIRECT_STATUS'] = 404;
	  exit;
	}
*/
	public function zip($filename,$path,$password=""){
		$cmd = "env LANG=\"\" /usr/local/bin/7za a -tzip \"".$filename."\" \"".$path."/*\"";
		if(!empty($password)) $cmd .= " -p".$password;
		exec($cmd, $output, $exitval);
		return (!$exitval);
	}
	public function unzip($zippath,$destination,$password=""){
		$cmd = "env LANG=\"\" /usr/local/bin/7za x \"".$zippath."\" -o\"".$destination."\" -aoa";
		//$cmd = "unzip ".$zippath." -d ".$destination;
		if(!empty($password)) $cmd .= " -p".$password;
		exec($cmd, $output, $exitval);
		return (!$exitval);
		//check file to see if extract currectly
/*
		$zip = new ZipArchive;
		$res = $zip->open($zippath);
		if ($res !== TRUE) {
			$val['type']='itutor';
			$val['code']='500';
			$val['msg']='error';
			return $val;
		}
		
		$zip_root = $zip->getNameIndex(0);
		for($i = 0; $i < $zip->numFiles; $i++) {
	  	$zip->extractTo($destination, array($zip->getNameIndex($i)));
			//echo $zip->getNameIndex($i).'<br />';
		}

		$zip->close();
*/
		return true;
	}
	
	//this will copy folders & files
	public function copy($source,$dest){
		if(strpos($source,'//')){
			//to avoid access denied, cause recursive error
      exit;
		}
    $dir = opendir($source); 
    @mkdir($dest); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($source . '/' . $file) ) { 
                self::copy($source . '/' . $file,$dest . '/' . $file); 
            } 
            else { 
                copy($source . '/' . $file,$dest . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
	}
	
	function randString($len){
		$str = '';
		do{
			$str .= rtrim(base64_encode(md5(microtime())),"=");
		}while(strlen($str)<$len);
		return substr($str,0,$len);
	}

	public function path_info($filepath)   
	{   
	  $path_parts = array();   
	  $path_parts ['dirname'] = rtrim(substr($filepath, 0, strrpos($filepath, '/')),"/")."/";
	  $path_parts ['basename'] = ltrim(substr($filepath, strrpos($filepath, '/')),"/");
	  $path_parts ['extension'] = substr(strrchr($filepath, '.'), 1);
	  $path_parts ['filename'] = ltrim(substr($path_parts ['basename'], 0, strrpos($path_parts ['basename'], '.')),"/");
	  return $path_parts;   
	}  

	public function rs2ini($rs){
		$ini = array();
		$g = '';
		foreach($rs as $k=>$r){
			if($g!=$r['group']){
				$ini[$r['group']]=array();
			}
			$ini[$r['group']][$r['key']] = $r['val'];
			$g=$r['group'];
		}
		return $ini;
	}
	public function ini2rs($ini){
		$rs = array();
		foreach($ini as $g=>$r){
			foreach($r as $key => $val){
				$rs[] = array('group'=>$g, 'key'=>$key, 'val'=>(string)$val);
			}
		}
		return $rs;
	}

	function getcookie($name){
		global $fs;
		global $ee;
		$val = $fs->filter($name);
		if($val!=$name){
			$ee->Error('500.44');
		}
		return $_COOKIE[$val];
	}

	function download($file,$filename){
		if (file_exists($file)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.$filename);
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));
		    readfile($file);
		    exit;
		}
	}

	function remove_utf8_bom($text){
	    $bom = pack('H*','EFBBBF');
	    $text = preg_replace("/^$bom/", '', $text);
	    return $text;
	}
}
?>
