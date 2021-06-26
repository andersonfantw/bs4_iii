<?PHP
interface iBSFile_CODEC{
	public function CheckFormat($tmpfolder);
	public function postDecode($sourcepath,$destpath);
	public function InsertDB($tmpfolder,$Uniquekey,$filename,$bsid,$uid,$cid);
}
?>