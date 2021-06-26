<?PHP
abstract class QueueStatusEnum{
	const Wait = 0;
	const Fail = -1;							//Error Occur while converting
	const MissingFile = -2;
	const IncorrectFilename = -3; //include incorrect character ex: \ / ? !
	const ConvertingTimeout = -4;	//something wrong, not progress over 10 mins.
	const ConvertSuspend = -5;		//convert suspend by accident.
	const ImportFail = -6;
	const ExceedMaxRetryTimes = -7;	//retry 3 times, and cannot convert successfully.
	const Success = 100;
	const ImportSuccess = 200;
}
?>