<?PHP
/*
���ѧY�ɻP�b�����A��������

iAccountInterface���b���P�B��interface
iAuthInterface���b�����Ҫ�interface
�b�����Ҫ��覡��
 1. DB
 2. webservice
 3. api
 4. POP3
 5. OpenID

�b����X�\��n�F��H�U���ݨD
 1. �ϥήM��w��(���ӫO�d�ϥΦw���ɦw�ˮM���X�R)
 2. �ϥγ]�w�ɳ]�w�ϥΪ��M��

�b����X�i��|�J�쪺���p��
 1. �����X - �Ѯv�B�ǥ͡B�s�ճ��M�t�ξ�X
 2. ������X - �u��X�Ѯv�b��
 		�e�x�����Ҭ����Ҧ��\��A�e�x�ϥΪ̥i�H�ݩҦ������

�إߤ@�ӷs���b������class�nimplements iAuthInterface
ex:
		class TT_Auth_Class implements iAuthInterface
*/
interface iAuthInterface
{
	public function validAdmin();
	public function validBSManager();
	public function validUser();
}
?>
