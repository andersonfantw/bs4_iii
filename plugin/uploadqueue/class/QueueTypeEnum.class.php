<?PHP
abstract class QueueTypeEnum{
	const SuccessList = 1;
	const ErrorList = 2;
	const UnprocessList = 3;
	const Converting = 4;
	const FailureList = 5;
	const CheckStatus = 6;
	const MailList = 7;
}
?>