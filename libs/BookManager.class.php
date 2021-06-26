<?PHP
class BookManager{
	function __construct(){
	}

	public function setCyberhoodEbookURL($indexpage,$Uniquekey,$isbackend=false,$uid=0,$bsid=0){
		if(empty($uid)) $uid = bssystem::getUID($isbackend);
		if(empty($bsid)) $bsid = bssystem::getBSID($isbackend);
		
		return sprintf('/webs@2/ebook/%u/%u/%s/%s.html',$uid,$bsid,$Uniquekey,$indexpage);
	}

	public function setCyberhoodOrifileURL($filename,$Uniquekey,$isbackend=false,$uid=0,$bsid=0){
		if(empty($uid)) $uid = bssystem::getUID($isbackend);
		if(empty($bsid)) $bsid = bssystem::getBSID($isbackend);

		return sprintf('/webs@2/ebook/%u/%u/%s/%s',$uid,$bsid,$Uniquekey,$filename);
	}

	public function putOnShelf($uid, $bsid, $Uniquekey, $tmpfolder, $originalfile, $indexpage){
		global $ee;
		$webbook_link = LocalHost.HostManager::getBookshelfBase(false,false,$uid,$bsid).'/files/'.$Uniquekey.'/'.$indexpage.'.php';
		$status=1;
		if(ENABLE_DECENTRALIZED){
			$status=0;
			//insert ebook to dbmaker
			$result = CSDUtility::ebook2dbmaker($uid, $bsid, $Uniquekey, $tmpfolder, $originalfile);
var_dump($result);
			if($result['rc']!=0){
				$ee->add('msg',$result['errmsg']);
				$ee->Error('500');
			}
			//delete ebook folder
			common::rrmtmpdir($tmpfolder);
			
			$webbook_link = LocalHost.BookManager::setCyberhoodEbookURL($indexpage,$Uniquekey,false,$uid,$bsid);
		}else{
			$path_info = common::path_info($originalfile['filename']);
			//files/(wonderboxid + timestamp).(extension ex:pdf)
			rename($originalfile['filepath'], FILE_PATH.'/'.$Uniquekey.'.'.$path_info['extension']);
		}
		return array('webbook_link'=>$webbook_link, 'status'=>$status);
	}

	public function del($id){
		global $db;
		$book = new book($db);
		$data = $book->getByID($id);
    if($book->del($id)){
			#delete covert image
			#check book type: delete files for now. Do differently with ebk.
			# 1. bought book, - cant't delete
			# 2. cloud convert book, - delete files
			# 3. upload book(itu), - delete files
			# 4. link, - do nothing
			if(ENABLE_DECENTRALIZED){
				//delete from decentralized system
				$bookid = $data['b_key'];
				$bsid = $data['bs_id'];
				$account = new account($db);
				$arow = $account->getAccountByBSID($bsid);
				$uid = $arow['u_id'];
				CSDUtility::delete_ebook($uid, $bsid, $bookid);
			}elseif(CONNECT_ECOCAT && ($data['ecocat_id']!='')){
				//delete ebook from ecocat
	    	$ecocat = new EcocatConnector($bs_code);
	    	$ecocat->DeleteBook($data['ecocat_id']);
			}else{
				//delete ebook folder
				if(!empty($data['webbook_link'])){
				 	if(strpos($data['webbook_link'],LocalHost)===0){
				 		$path = ROOT_PATH.str_replace(LocalHost,'',$data['webbook_link']);
				 		$p = common::path_info($path);
				    if(is_dir($p['dirname'])){
				    	common::remove_directory($p['dirname']);
				    }
					}
				}
				//delete original file
				if(!empty($data['ibook_link'])){
				 		$path = ROOT_PATH.str_replace(LocalHost,'',$data['ibook_link']);
				 		$path_info = common::path_info($path);
				 		$path1 = FILE_PATH.'/'.$data['b_key'].'.'.$path_info['extension'];
				 		if(is_file($path1)){
				 			@unlink($path1);
				 		}
				}
			}
			//del cover images
			if(!empty($data['f_path'])){
				$file = HostManager::getBookshelfBase(true,true).'/uploadfiles/'.$data['f_name'].'.'.$data['f_type'];
				$file1 = HostManager::getBookshelfBase(true,true).'/uploadfiles/m_'.$data['f_name'].'.'.$data['f_type'];
				$file2 = HostManager::getBookshelfBase(true,true).'/uploadfiles/s_'.$data['f_name'].'.'.$data['f_type'];
				if(is_file($file)) @unlink($file);
				if(is_file($file1)) @unlink($file1);
				if(is_file($file2)) @unlink($file2);
			}

			//delete tag
			$tag = new tag($db);
			$data = $tag->getTagsByBook($id);
			$data = array_values($data);
			foreach($data as $t){
				$tid = $t[0][0];
				$tag->delBookTag($id,$tid);
			}

    	return true;
    }else{
    	return false;
    }
	}
}
