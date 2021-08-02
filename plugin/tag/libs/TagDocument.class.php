<?PHP
/**************************************************************************
This function call after tag import. Should setup necessary tags first.
missing necessary tag will show notice message.

bookshelf2_tag_dictionary: all pre-set tags, book & test & questions
dockey,quizid,t_id,CreateUser,CreateDate
bookshelf2_view_tag_dictionary
dockey,quizid,t_id,key,val,pkey,pval,CreateUser,CreateDate

bookshelf2_book_tag: user-defined tags
bookshelf2_itutor_exercise_tag: user-defined tags
bookshelf2_scanexam_quiz_tag: user-defined tags
bookshelf2_scanexam_exercise_tag: user-defined tags
**************************************************************************/
require_once dirname(__FILE__).'/../libs/Node.class.php';
require_once dirname(__FILE__).'/../libs/TagTree.class.php';

class TagDocument extends TagTree{
	//all dictionary
	private $dictionary = array();
	//new dictionary
	private $dictionary1 = array();
	//dispute dictionary
	//should add these tags first. Tag is not exist.
	private $tag_dispute_list = array();
	//these tags will be update. This doc already exist.
	private $doc_dispute_list = array();
	//$input = 'key:q=t:k,t1:k1';
	public function loadDictionaryString($input){
		$input = $this->formatString($input);
		if(!TagDocument::chkFormat($input)){
			return false;
		}
		if(!TagDocument::validCheckCode($input)){
			return false;
		}
		if(TagDocument::hasCheckCode($input)){
			$input = substr($input,32);
		}
		$arr1 = explode(";",$input);
		if($arr1[count($arr1)-1]==''){
			array_pop($arr1);
		}
		$this->loadDB();
		$this->loadDictionaryDB();
		for($i=0;$i<count($arr1);$i++){
			list($dockey,$tags) = explode('=',$arr1[$i]);
			$arr_tag = explode(',',$tags);
			foreach($arr_tag as $str){
				$_str = str_replace('@',':',$str);
				//list($pkey,$pval,$key,$val) = explode(':',$_str);
				list($key,$val) = explode(':',$_str);
				//$tagkey = sprintf('%s:%s@%s:%s',$pkey,$pval,$key,$val);
				$tagkey = $key;
				if($key=='' && $val==''){
					$key = $pkey; $val = $pval; $pkey = ''; $pval = '';
					//$tagkey = sprintf('%s:%s',$key,$val);
				}

				//look up id in hash
				//It's better to tag system tags. And one tag can only belong one parent.
				$id=0;
				$node = $this->getNode($key);
				if(empty($node->data['id'])){
					//tag not in db, same dictionary item in input file
					if(!array_key_exists($dockey,$this->tag_dispute_list)){
						$this->tag_dispute_list[$dockey] = array();
					}
					$this->tag_dispute_list[$dockey][] = $tagkey;
				}else{
					//tag in db
					$id=$node->data['id'];
				}

				if(!array_key_exists($dockey,$this->dictionary1)){
					$this->dictionary1[$dockey] = array();
				}
				$this->dictionary1[$dockey][$tagkey] = array('id'=>$id,'pkey'=>$pkey,'pval'=>$pval,'key'=>$key,'val'=>$val);
				$isDispute=false;
				if(array_key_exists($dockey,$this->dictionary)){
					//from db or from other file
					if(array_key_exists($tagkey,$this->dictionary[$dockey])){
						if(!array_key_exists($dockey,$this->doc_dispute_list)){
							$isDispute=true;
							$this->doc_dispute_list[$dockey] = array();
						}
						$this->tag_dispute_list[$dockey][] = $tagkey;
					}
				}
				if(!$isDispute){
					if(!array_key_exists($dockey,$this->dictionary)){
						$this->dictionary[$dockey] = array();
					}
					$this->dictionary[$dockey][$tagkey] = array('id'=>$id,'pkey'=>$pkey,'pval'=>$pval,'key'=>$key,'val'=>$val);
				}
			}
		}
	}
	public function exportDictionaryString($listType = ListTypeEnum::All,$debug=false){
		$output = ''; $chk = '';
		switch($listType){
			case ListTypeEnum::All:
				$list = $this->dictionary;
				break;
			case ListTypeEnum::NewItem:
				$list = $this->dictionary1;
				break;
			case ListTypeEnum::Dispute:
				$list = $this->doc_dispute_list+$this->tag_dispute_list;
				//$list = array_unique($list);
				break;
		}
		ksort($list);
		foreach($list as $dockey => $tags){
			$showItem = false;
			if(in_array($dockey,$this->doc_dispute_list)){
				$showItem = true;
				$s = '<b class="doc">%s</b>';
			}else{
				$s = '%s';
			}
			if($debug){
				$output .= sprintf(";<br />\r\n".$s."=",$dockey);
			}else{
				$output .= sprintf(";\r\n".$s."=",$dockey);
			}
			$str='';
			$arr = $this->dictionary[$dockey];
			foreach($arr as $k=>$n){
/*
				$tagkey=sprintf('%s:%s@%s:%s',$n['pkey'],$n['pval'],$n['key'],$n['val']);
				if(empty($n['pkey']) && empty($n['pval'])){
					$tagkey=$n['key'];
				}
*/
				$tagstr=sprintf('%s:%s',$n['key'],$n['val']);
				$tagkey=$n['key'];

				$showTag = false;
				if(array_key_exists($dockey,$this->tag_dispute_list)){
					$tag_dispute = $this->tag_dispute_list[$dockey];
					if(in_array($tagkey,$tag_dispute)){
						$showTag = true;
					}
					foreach($tag_dispute as $dispute){
						if(in_array($tagkey,$dispute)){
							$showTag = true;
						}
					}
					if(in_array($tagkey,array_key_exists($this->tag_dispute_list[$dockey]))){
						$showTag = true;
					}
				}
				if($showTag){
					$str.=','.sprintf('<b>%s</b>',$tagstr);
				}else{
					$str.=','.$tagstr;
				}
			}
			$output.=substr($str,1);
		}
		if($debug){
			$output=substr($output,7).';';
		}else{
			$output=substr($output,3).';';
			$str = $this->formatString($output);
			$chk = $this->_encodeCheckCode($str) . "\r\n";			
		}
		return $chk.$output;
	}

	//exportAll flag allow you export dictionary from
	//tag_dictionary V
	//itutor_exercise_tag V
	//scanexam_tag X
	//scanexam_quiz_tag X
	//scanexam_test_tag V
	//scanexam_exercise_tag V
	public function loadDictionaryDB($loadType=ListTypeEnum::BookTag){
		global $db;
		switch($loadType){
			case ListTypeEnum::All:
				$sql=<<<SQL
select dockey, quizid, vt.*
from (
	select dockey, e_reportid as quizid, t_id from bookshelf2_itutor_exercise_tag
	union
	select se_key as dockey, '' as quizid, t_id from bookshelf2_scanexam_test_tag
	union
	select se_key as dockey,cast(seq as varchar(30)) as quizid, t_id from bookshelf2_scanexam_exercise_tag
	union
	select dockey, quizid, t_id from BOOKSHELF2_TAG_DICTIONARY
) as t
left join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
SQL;
				break;
			case ListTypeEnum::DocumentTag:
				$sql=<<<SQL
select t.dockey, t.quizid, vt.*
from BOOKSHELF2_TAG_DICTIONARY as t
left join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
SQL;

				break;
			case ListTypeEnum::NonDocumentag:
				$sql=<<<SQL
select dockey, quizid, vt.*
from (
	select dockey, e_reportid as quizid, t_id from bookshelf2_itutor_exercise_tag
	union
	select se_key as dockey, '' as quizid, t_id from bookshelf2_scanexam_test_tag
	union
	select se_key as dockey,cast(seq as varchar(30)) as quizid, t_id from bookshelf2_scanexam_exercise_tag
) as t
left join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
SQL;
				break;
			case ListTypeEnum::ItutorTag:
				$sql=<<<SQL
select t.dockey, t.e_reportid as quizid, vt.*
from bookshelf2_itutor_exercise_tag as t
left join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
SQL;
				break;
			case ListTypeEnum::ScanexamTag:
				$sql=<<<SQL
select t.dockey, t.quizid, vt.*
from (
	select se_key as dockey, '' as quizid, t_id from bookshelf2_scanexam_test_tag
	union
	select se_key as dockey,cast(seq as varchar(30)) as quizid, t_id from bookshelf2_scanexam_exercise_tag
) as t
left join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
SQL;
				break;
			case ListTypeEnum::BookTag:
				$sql=<<<SQL
select b.b_key as dockey, '' as quizid, vt.*
from BOOKSHELF2_BOOK_TAG as t
left join bookshelf2_books b on(t.b_id=b.b_id)
left join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
SQL;
				break;
		}
		$data['result']=$db->get_results($sql);
/*
		if($exportAll){
			$data=array();
			$sql=<<<SQL
select dockey, quizid, vt.*
from (
	select dockey, e_reportid as quizid, t_id from bookshelf2_itutor_exercise_tag
	union
	select se_key as dockey, '' as quizid, t_id from bookshelf2_scanexam_test_tag
	union
	select se_key as dockey,cast(seq as varchar(30)) as quizid, t_id from bookshelf2_scanexam_exercise_tag
	union
	select dockey, quizid, t_id from BOOKSHELF2_VIEW_TAG_DICTIONARY
) as t
left join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
SQL;
			$data['result']=$db->get_results($sql);
		}else{
			$tag_disctionary = new tag_dictionary($db);
			$data = $tag_disctionary->getList();
		}
*/
		$output = '';
		foreach($data['result'] as $row){
			if($row['quizid']==''){
				$dockey=(string)$row['dockey'];
			}else{
				$dockey=sprintf('%s:%s',$row['dockey'],$row['quizid']);
			}
			//$tagkey=sprintf('%s:%s@%s:%s',$row['pkey'],$row['pval'],$row['key'],$row['val']);
			//$tagkey=sprintf('%s:%s',$row['key'],$row['val']);
			$tagkey=$row['key'];
			if(!array_key_exists($dockey,$this->dictionary)){
				$this->dictionary[$dockey] = array();
			}
			if($tagkey){
				if(array_key_exists($tagkey,$this->dictionary[$dockey])){
					if(empty($this->dictionary[$dockey][$tagkey]['id'])){
						//override data from loadDictionaryString
						$this->dictionary[$dockey][$tagkey] = array('id'=>$row['t_id'],'key'=>$row['key'],'pval'=>$row['pval'],'pkey'=>$row['pkey'],'pval'=>$row['pval']);
					}
				}else{
					$this->dictionary[$dockey][$tagkey] = array('id'=>$row['t_id'],'key'=>$row['key'],'val'=>$row['val'],'pval'=>$row['pval'],'pkey'=>$row['pkey'],'pval'=>$row['pval']);
				}
			}
		}
	}
	public function saveDictionaryDB(){
		$hasUpdate=false;
		if(empty($this->tag_dispute_list)){
			global $db;
			$tag_dictionary = new tag_dictionary($db);
			foreach($this->dictionary1 as $dockey => $arr){
				$data = array();
				list($dockey,$quizid) = explode(':',$dockey);

				if(in_array($dockey,$this->doc_dispute_list)){
					$tag_dictionary->deleteByKey($dockey,$quizid);
					$hasUpdate=true;
				}
				foreach($arr as $n){
					$data['dockey'] = $dockey;
					$data['quizid'] = $quizid;
					$data['t_id'] = intval($n['id']);
					$tag_dictionary->insert($data);
				}
/*
				if(array_key_exists($dockey,$this->doc_dispute_list)){
					$arr_existtagkey = $this->doc_dispute_list[$dockey];
					foreach($arr as $n){
						if(!in_array($n['key'],$arr_existtagkey)){
							$data['dockey'] = $dockey;
							$data['quizid'] = $quizid;
							$data['t_id'] = intval($n['id']);
							$tag_dictionary->insert($data);
							$hasUpdate=true;
						}
					}
				}
*/
			}
			return $hasUpdate;
		}
	}
	
	public function chkFormat($filecontent){
		//$pattern = '/^(\w{32}){0,1}(([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255}){0,1}\=([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255})@([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255})(\,([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255})@([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255}))*;)+$/';
		$pattern = '/^(\w{32}){0,1}(([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255}){0,1}\=([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255}){0,1}(\,([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255}){0,1})*;)+$/';
		return preg_match($pattern,$filecontent);
	}
	public function hasCheckCode($filecontent){
		$pattern = '/^(\w{32})/';
		return preg_match($pattern,$filecontent);
	}
	public function validCheckCode($filecontent){
		if(TagDocument::hasCheckCode($filecontent)){
			$code = substr($filecontent,0,32);
			$content = substr($filecontent,32);
			return ($code==$this->_encodeCheckCode($content));
		}
		return true;
	}
	public function hasDispute(){
		return !empty($this->tag_dispute_list);
	}
}
?>
