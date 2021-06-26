<?PHP
/*
提供即時與帳號伺服器的驗證

iAccountInterface為帳號同步的interface
iAuthInterface為帳號驗證的interface
帳號驗證的方式有
 1. DB
 2. webservice
 3. api
 4. POP3
 5. OpenID

帳號整合功能要達到以下的需求
 1. 使用套件安裝(未來保留使用安裝檔安裝套件的擴充)
 2. 使用設定檔設定使用的套件

帳號整合可能會遇到的狀況有
 1. 完整整合 - 老師、學生、群組都和系統整合
 2. 部分整合 - 只整合老師帳號
 		前台的驗證為驗證成功後，前台使用者可以看所有的資料

建立一個新的帳號驗證class要implements iAuthInterface
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
