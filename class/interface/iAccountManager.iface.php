<?PHP
/*
�����b���t�ΰӴ��Ѫ��b����ơA�æb���d��Ʈw���إߤ@���C
���Ѥ�ʦP�B�\��A��o�{�w������Ƥ��s�b���d�ɡA�H��ʧ�s�C

iAccountInterface���b���P�B��interface
iAuthInterface���b�����Ҫ�interface
�b���P�B���覡��
 1. DB
 2. LDAP & AD(�ݭn�פJ�N�����פJ��LDAP)
 3. webservice
 4. api

�b����X�\��n�F��H�U���ݨD
 1. �ϥήM��w��(���ӫO�d�ϥΦw���ɦw�ˮM���X�R)
 2. �ϥγ]�w�ɳ]�w�ϥΪ��M��

�b����X�i��|�J�쪺���p��
 1. �����X - ���d�޲z���B�ϥΪ̡B�s�ճ��M�t�ξ�X
 2. ������X - �u��X���d�޲z���b��
 		�e�x�����Ҭ����Ҧ��\��A�e�x�ϥΪ̥i�H�ݩҦ������

�]�w�@��Plugin
ex:
		TT
		
�إߤ@�ӷs���b���P�Bclass�nimplements iAccountInterface
�إߤ@�ӷs���b������class�nimplements iAuthInterface
�ù갵function
ex:
		class TT_Account_Class implements iAccountInterface
		class TT_Auth_Class implements iAuthInterface

�b���M�󪺦W�ٳ]�w�b/config.php
		define('AUTH_PLUGIN','TT');

/class/AccountManager.php���oAUTH_PLUGIN�]�w������b���P�B�λ{��
manager��function�|��������d����function�C�Ҧ����d���M�b���λ{�Ҭ������A
�����ӦbAccountManager�MAuthManager���Pplugin����

*/
interface iAccountInterface
{
	public function getBSManagerList();
	public function SearchManagerAccount();
	public function getBSManagerUID();
	public function getGroupList();
	public function setBSGroup();
	public function getUserList();
}
?>
