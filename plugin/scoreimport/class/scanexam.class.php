<?PHP
/*									exam											quiz(seq)
bs_key,ex_key		測驗卷scanexam				題目scanscore_quiz
buid						成績scanscore_user		答案scanscore_exercise

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