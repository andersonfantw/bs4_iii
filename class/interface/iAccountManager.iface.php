<?PHP
/*
完成帳號系統商提供的帳號資料，並在書櫃資料庫中建立一份。
提供手動同步功能，當發現預期的資料不存在書櫃時，以手動更新。

iAccountInterface為帳號同步的interface
iAuthInterface為帳號驗證的interface
帳號同步的方式有
 1. DB
 2. LDAP & AD(需要匯入就直接匯入到LDAP)
 3. webservice
 4. api

帳號整合功能要達到以下的需求
 1. 使用套件安裝(未來保留使用安裝檔安裝套件的擴充)
 2. 使用設定檔設定使用的套件

帳號整合可能會遇到的狀況有
 1. 完整整合 - 書櫃管理員、使用者、群組都和系統整合
 2. 部分整合 - 只整合書櫃管理員帳號
 		前台的驗證為驗證成功後，前台使用者可以看所有的資料

設定一個Plugin
ex:
		TT
		
建立一個新的帳號同步class要implements iAccountInterface
建立一個新的帳號驗證class要implements iAuthInterface
並實做function
ex:
		class TT_Account_Class implements iAccountInterface
		class TT_Auth_Class implements iAuthInterface

帳號套件的名稱設定在/config.php
		define('AUTH_PLUGIN','TT');

/class/AccountManager.php取得AUTH_PLUGIN設定對應到帳號同步及認證
manager的function會對應到書櫃中的function。所有書櫃忠和帳號及認證相關的，
都應該在AccountManager和AuthManager中與plugin對應

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
