<?PHP
/* Module name */
//loose : user login, can look every content
//strict: use only can look particular content
//define('SSO_MODE','strict');
//define('CENTRALIZE_MEMBER',$CENTRALIZE_MEMBER);

/*
reference: membersystem_init
	const Normal = 1;
	const Import = 2; //default
	const NAS_LDAP = 4;
	const API_PLUGIN = 8;
	const Regist = 16;
*/
if(!defined('MEMBER_SYSTEM')) define('MEMBER_SYSTEM',1);

$PLUGIN_SSO = 'DBbase';
switch(MEMBER_SYSTEM){
	case 4://MemberSystemEnum::NAS_LDAP:
		$PLUGIN_SSO = 'LDAP';	//NAS LDAP
		break;
	case 8://MemberSystemEnum::API_PLUGIN:
		$PLUGIN_SSO = 'API';
		break;
	case 2://MemberSystemEnum::Import:
	case 16://MemberSystemEnum::Regist:
	default:
		break;
}
define('PLUGIN_SSO',$PLUGIN_SSO);
/*
reference: membersystem_init
	const CENTRALIZE = 1;
	const CENTRALIZE_ASSIGN = 2;
	const INDIVIDUAL = 4;
	const USER_LOGIN_VERIFY_LOOSE_MODE = 8;
*/
if(!defined('MEMBER_MODE')) define('MEMBER_MODE',4);
?>
