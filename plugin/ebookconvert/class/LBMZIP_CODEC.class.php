<?PHP
/*
Decode:
�[�J�������
*/
class LBMZIP_CODEC extends BSFile_CODEC implements iBSFile_CODEC{
	function Decode($path){
		if(!$this->CheckFormat($path))
		{
		}
	}
		
	function CheckFormat(){
		$folderlist=array('data','images','scripts','css','html','xml');
		$filelist=array('book.swf','index.html','help.html');
		return true;
	}
}
?>