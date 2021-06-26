<?PHP
/******************************* Member Setting ******************************
									Customer view			
									db: mysql/dbmaker			
												normal / import			ldap		api/webservice	regist
MemberMode						 IND	 CEN/A	VERIFY	 CEN/A			 CEN/A			IND CEN
sys			set bs groups		X			X/V							X/V					X/V				 X	 X
				manager view		V			V								V						V					 V	 V
							modify		V			V								X						X					 V	 V
				group						X			V								X						X					 V	 V
				user						X			V								X						X					 V	 V
backend	group	view			V			V								V						V					 V	 V
						modify			V			X								X						X					 V	 V
				user	view			V			V								V						V					 V	 V
						modify			V			X								X						X					 V	 V
						activecode	X			X								X						X					 V	 V

user login verify	loose								V
	mode							

												normal / import		ldap	api/webservice	regist
sys			set bs groups					2						2						2						0
				manager view					7						3						3						3	
							modify					7						0						0						3
				group									3						0						0						3
				user									3						0						0						3
backend	group	view						7						3						3						3
						modify						4						0						0						3
				user	view						7						3						3						3
						modify						4						0						0						3
						activecode				0						0						0						8

user login verify	loose				15					15					15					7
	mode							

*****************************************************************************/
abstract class MemberSystemFuncMapping{
	public static function getMapping($page){
		list($p,$m) = explode('|',$page);
		if(isset($m)){
			if(isset(self::$Mapping[MEMBER_SYSTEM][$p])){
				if(self::$Mapping[MEMBER_SYSTEM][$page]>0){
					//page with method
					return self::$Mapping[MEMBER_SYSTEM][$page];
				}else{
					//set method same with list
					return self::$Mapping[MEMBER_SYSTEM][$p];
				}
			}
		}else{
			//list page
			if(isset(self::$Mapping[MEMBER_SYSTEM][$p])){
				return self::$Mapping[MEMBER_SYSTEM][$p];
			}
		}

		//page which is not define, return allow
		//CENTRALIZE + CENTRALIZE_ASSIGN + INDIVIDUAL + USER_LOGIN_VERIFY_LOOSE_MODE
		return 15;
	}

	//control button or flow enable/disable
	public static function isEnable($method){
		global $fs;
		switch(gettype($method)){
			case 'integer':
				$page = $method;
				break;
			case 'string':
				$page = $fs->filter($_SERVER['PHP_SELF']);
  			$allow_method = array('add','edit','delete');
  			if(!in_array($method,$allow_method)) $method = '';
  			$page = $page.'|'.$method;
				break;
		}
		return LicenseManager::chkAuth(MEMBER_MODE,MemberSystemFuncMapping::getMapping($page));
	}

	private static $Mapping=array(
		MemberSystemEnum::Normal=>array(
			'/trial/'=>0,
			'/subscribe/'=>0,
			'/regist/step1/'=>0,
			'/regist/step2/'=>0,
			'/regist/step3/'=>0,
			'/backend/activecode.php'=>0,
			'/backend/sys_account.php'=>7,
			'/backend/sys_group.php'=>3,
			'/backend/sys_user.php'=>3,
			'/backend/group.php'=>7,
			'/backend/group.php|add'=>4,
			'/backend/group.php|edit'=>7,
			'/backend/group.php|delete'=>4,
			'/backend/bookshelf_user.php'=>7,
			'/backend/bookshelf_user.php|add'=>4,
			'/backend/bookshelf_user.php|edit'=>4,
			'/backend/bookshelf_user.php|delete'=>4,
			'/backend/sys_group.php'=>3,
			'/backend/sys_group.php|delete'=>0,
			'/backend/sys_bookshelf_user.php'=>3,
			'/backend/sys_bookshelf_user.php|delete'=>0,
			PageFuncEnum::SYS_ASSIGN_GROUP=>2
		),
		MemberSystemEnum::Import=>array(
			'/trial/'=>0,
			'/subscribe/'=>0,
			'/regist/step1/'=>0,
			'/regist/step2/'=>0,
			'/regist/step3/'=>0,
			'/backend/activecode.php'=>0,
			'/backend/sys_account.php'=>7,
			'/backend/sys_group.php'=>3,
			'/backend/sys_user.php'=>3,
			'/backend/group.php'=>7,
			'/backend/group.php|add'=>4,
			'/backend/group.php|edit'=>7,
			'/backend/group.php|delete'=>4,
			'/backend/bookshelf_user.php'=>7,
			'/backend/bookshelf_user.php|add'=>4,
			'/backend/bookshelf_user.php|edit'=>4,
			'/backend/bookshelf_user.php|delete'=>4,
			'/backend/sys_group.php'=>3,
			'/backend/sys_group.php|delete'=>3,
			'/backend/sys_bookshelf_user.php'=>3,
			'/backend/sys_bookshelf_user.php|delete'=>3,
			PageFuncEnum::SYS_ASSIGN_GROUP=>2
		),
		MemberSystemEnum::NAS_LDAP=>array(
			'/trial/'=>0,
			'/subscribe/'=>0,
			'/regist/step1/'=>0,
			'/regist/step2/'=>0,
			'/regist/step3/'=>0,
			'/backend/activecode.php'=>0,
			'/backend/sys_account.php'=>7,
			'/backend/sys_group.php'=>3,
			'/backend/sys_user.php'=>3,
			'/backend/group.php'=>3,
			'/backend/group.php|add'=>0,
			'/backend/group.php|edit'=>3,
			'/backend/group.php|delete'=>0,
			'/backend/bookshelf_user.php'=>3,
			'/backend/bookshelf_user.php|add'=>0,
			'/backend/bookshelf_user.php|edit'=>0,
			'/backend/bookshelf_user.php|delete'=>0,
			'/backend/sys_group.php'=>0,
			'/backend/sys_group.php|delete'=>0,
			'/backend/sys_bookshelf_user.php'=>0,
			'/backend/sys_bookshelf_user.php|delete'=>0,
			PageFuncEnum::SYS_ASSIGN_GROUP=>2
		),
		MemberSystemEnum::API_PLUGIN=>array(
			'/trial/'=>0,
			'/subscribe/'=>0,
			'/regist/step1/'=>0,
			'/regist/step2/'=>0,
			'/regist/step3/'=>0,
			'/backend/sys_group.php'=>0,
			'/backend/sys_group.php|delete'=>0,
			'/backend/sys_bookshelf_user.php'=>0,
			'/backend/sys_bookshelf_user.php|delete'=>3,
			PageFuncEnum::SYS_ASSIGN_GROUP=>2,
			PageFuncEnum::SYS_MANAGER_VIEW=>3,
			PageFuncEnum::SYS_MANAGER_MODIFY=>0,
			PageFuncEnum::SYS_GROUP_VIEW=>0,
			PageFuncEnum::SYS_GROUP_MODIFY=>0,
			PageFuncEnum::SYS_USER_VIEW=>0,
			PageFuncEnum::SYS_USER_MODIFY=>0,
			PageFuncEnum::GROUP_VIEW=>3,
			PageFuncEnum::GROUP_MODIFY=>0,
			PageFuncEnum::USER_VIEW=>3,
			PageFuncEnum::USER_MODIFY=>0,
			PageFuncEnum::ACTIVECODE=>0,
			PageFuncEnum::USER_LOGIN_VERIFY_LOOSE_MODE=>15
		),
		MemberSystemEnum::Regist=>array(
			'/trial/'=>7,
			'/subscribe/'=>7,
			'/backend/sys_group.php'=>7,
			'/backend/sys_group.php|delete'=>7,
			'/backend/sys_bookshelf_user.php'=>7,
			'/backend/sys_bookshelf_user.php|delete'=>7,
			PageFuncEnum::SYS_ASSIGN_GROUP=>0,
			PageFuncEnum::SYS_MANAGER_VIEW=>3,
			PageFuncEnum::SYS_MANAGER_MODIFY=>3,
			PageFuncEnum::SYS_GROUP_VIEW=>3,
			PageFuncEnum::SYS_GROUP_MODIFY=>3,
			PageFuncEnum::SYS_USER_VIEW=>3,
			PageFuncEnum::SYS_USER_MODIFY=>3,
			PageFuncEnum::GROUP_VIEW=>3,
			PageFuncEnum::GROUP_MODIFY=>3,
			PageFuncEnum::USER_VIEW=>3,
			PageFuncEnum::USER_MODIFY=>3,
			PageFuncEnum::ACTIVECODE=>8,
			PageFuncEnum::USER_LOGIN_VERIFY_LOOSE_MODE=>7
		)
	);
}
?>
