<?PHP
/*									exam											quiz(seq)
bs_key,ex_key		�����scanexam				�D��scanscore_quiz
buid						���Zscanscore_user		����scanscore_exercise

scanexam=array(....,quiz=>array(,,,,,));
answer=array(....,exercise=>array(,,,,,));

scanexam=>
	se_key
	se_name
	se_description
	se_question_num
*/
class scanexam extends db_process{
  function scanexam($db) {
  	parent::db_process($db,'scanexam','se_');
  }

}