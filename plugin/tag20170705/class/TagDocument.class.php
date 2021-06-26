<?PHP
/**************************************************************************
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
	private $dictionarty = array();
	private $dictionarty1 = array();
	private $dispute_list = array();
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
			if(!array_key_exists($dockey,$this->dictionarty)){
				$this->disctionarty[$dockey] = array();
			}
			foreach($arr_tag as $tagkey){
				$_tagkey = str_replace('@',':',$tagkey);
				list($pkey,$pval,$key,$val) = explode(':',$_tagkey);
				if($key=='' && $val==''){
					$pkey = $key; $pval = $val;
				}
				if(in_array($tagkey,array_keys($this->dictionarty[$dockey]))){
					//skip if repeat
				}else{
					//look up id in hash
					//tag should refer tag in system. And tag can only belong one parent.
					$id=0;
					$node = $this->getNode($tagkey);
					if(!empty($node->data['id'])){
						$id=$node->data['id'];
					}else{
						if(array_key_exists($tagkey,$this->dictionarty[$dockey])){
							if(!array_key_exists($dockey,$this->dispute_list)){
								$this->dispute_list[$dockey] = array();
							}
							$this->dispute_list[$dockey][$tagkey] = array('pkey'=>$pkey,'pval'=>$pval,'key'=>$key,'val'=>$val);
						}
					}
					$this->dictionarty[$dockey][$tagkey] = array('id'=>$id,'pkey'=>$pkey,'pval'=>$pval,'key'=>$key,'val'=>$val);
					if(!array_key_exists($dockey,$this->dictionarty1)){
						$this->disctionarty1[$dockey] = array();
					}
					$this->dictionarty1[$dockey][$tagkey] = array('id'=>$id,'pkey'=>$pkey,'pval'=>$pval,'key'=>$key,'val'=>$val);
				}
			}
		}
	}
	public function exportDictionaryString($debug=false){
		$output = ''; $chk = '';
		foreach($this->dictionarty as $dockey => $arr){
			if($debug){
				$output.=sprintf(";<br />\r\n%s=",$dockey);
			}else{
				$output.=sprintf(";\r\n%s=",$dockey);
			}
			$str='';
			foreach($arr as $k=>$n){
				$tagkey=sprintf('%s:%s@%s:%s',$n['pkey'],$n['pval'],$n['key'],$n['val']);
				if($debug && !empty($this->dispute_list[$k][$tagkey])){
					$str.=','.sprintf('<b>%s</b>',$tagkey);
				}else{
					$str.=','.$tagkey;
				}
			}
			$output.=substr($str,1);
		}
		if(!$debug){
			$output=substr($output,3).';';
			$str = $this->formatString($output);
			$chk = $this->_encodeCheckCode($str) . "\r\n";
		}else{
			$output=substr($output,7).';';
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
	public function loadDictionaryDB($exportAll=false){
		global $db;
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
) as t
left join bookshelf2_view_tags vt on(t.t_id=vt.t_id)
SQL;
			$data['result']=$db->get_results($sql);
		}else{
			$tag_disctionary = new tag_dictionary(&$db);
			$data = $tag_disctionary->getList();
		}
		$output = '';
		foreach($data['result'] as $row){
			if($row['quizid']==''){
				$dockey=$row['dockey'];
			}else{
				$dockey=sprintf('%s:%s',$row['dockey'],$row['quizid']);
			}
			$tagkey=sprintf('%s:%s@%s:%s',$row['pkey'],$row['pval'],$row['key'],$row['val']);
			if(!array_key_exists($dockey,$this->dictionarty)){
				$this->disctionarty[$dockey] = array();
			}
			if(array_key_exists($tagkey,$this->dictionarty[$dockey])){
				if(empty($this->dictionarty[$dockey][$tagkey]['id'])){
					//override data from loadDictionaryString
					$this->dictionarty[$dockey][$tagkey] = array('id'=>$row['t_id'],'key'=>$row['key'],'pval'=>$row['pval'],'pkey'=>$row['pkey'],'pval'=>$row['pval']);
				}
			}else{
				$this->dictionarty[$dockey][$tagkey] = array('id'=>$row['t_id'],'key'=>$row['key'],'val'=>$row['val'],'pval'=>$row['pval'],'pkey'=>$row['pkey'],'pval'=>$row['pval']);
			}
		}
	}
	public function saveDictionaryDB(){
		if(empty($this->dispute_list)){
			global $db;
			$tag_disctionary = new tag_dictionary(&$db);
	
			foreach($this->dictionarty1 as $dockey => $arr){
				$data = array();
				list($dockey,$quizid) = explode(':',$dockey);
				foreach($arr as $n){
					$data['dockey'] = $dockey;
					$data['quizid'] = $quizid;
					$data['t_id'] = intval($n['id']);
					$tag_disctionary->insert($data);
				}
			}
		}else{
		}
	}
	
	public function chkFormat($filecontent){
		$pattern = '/^(\w{32}){0,1}(([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255}){0,1}\=([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255})@([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255})(\,([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255})@([^\=\:@;]{1,255})(\:[^\=\:@;]{1,255}))*;)+$/';
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
		return !empty($this->dispute_list);
	}
}
?>
