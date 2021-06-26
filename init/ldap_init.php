<?PHP
//0: nas user 1: ldap user
if(!defined('LDAP_DOMAINTYPE')) define('LDAP_DOMAINTYPE',0);
//param LDAP_DOMAIN_PREFIX can't be empty, empty will make smarty replace error
if(!defined('LDAP_DOMAIN_PREFIX')) define('LDAP_DOMAIN_PREFIX','');
define('LDAP_EXCLUDE_ACCOUNT','admin|webadmin|demo');
?>
