<?PHP
abstract class QueueStatusEnum{
	const Wait = 0;
	const Fail = -1;
	const MissingFile = -2;
	const Success = 100;
	const ImportSuccess = 200;
}
?>
