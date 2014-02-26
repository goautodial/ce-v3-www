<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|-----------------------------------------------------------------------------
| User Authenication Configuration
|-----------------------------------------------------------------------------
|
| 'login_expiration' = number of seconds inactive before a login expires.
| 'remember_me_life' = number of seconds a "Remember Me" cookie lasts
|
*/

$config['login_expiration'] = 0;		// 1200 = 20 minutes
$config['remember_me_life'] = 0;		// 31536000 = 12 weeks

/*
|-----------------------------------------------------------------------------
| User Authorization Roles - Access Control Lists
|-----------------------------------------------------------------------------
|
| $config['ua_role_rolename_allow'] = '';
| $config['ua_role_rolename_deny']  = '';
|
| Space-separated list of usernames/groupnames
| Groups delimited by "@"
|
| These are examples - You should create 
| your own user-groups and roles
|
| If a role defined here, it exists for authorize
*/

// this is an example of defining a role of "user"
$config['ua_role_user_allow']    = '@user @member @editor @manager @admin';
$config['ua_role_user_deny']     = '';

// this is an example of defining a role of "member"
$config['ua_role_member_allow']  = '@member @editor @manager @admin';
$config['ua_role_member_deny']   = '';

// this is an example of defining a role of "editor"
$config['ua_role_editor_allow']  = '@editor @manager @admin';
$config['ua_role_editor_deny']   = 'joe';

// this is an example of defining a role of "manager"
$config['ua_role_manager_allow'] = '@manager @admin suzy';
$config['ua_role_manager_deny']  = '';

// this is an example of defining a role of "admin"
$config['ua_role_admin_allow']   = '@admin';
$config['ua_role_admin_deny']    = '';

/*
|-----------------------------------------------------------------------------
| UserAuth Mini-App configure languages
|-----------------------------------------------------------------------------
|
| Mapping browser's primary language id to language name 
| Mapping language name to a character set
| Mapping language name to language id
|
*/

// If FALSE, disables language detect & user select
$config['ua_multi_language']  = TRUE;

// Mini-App's views/template needs encoding setting

$config['ua_language_en']     = 'english';
$config['ua_charset_english'] = 'iso-8859-1';
$config['ua_lang_english']    = 'en';

$config['ua_language_fi']     = 'finnish';
$config['ua_charset_finnish'] = 'iso-8859-1'; 
$config['ua_lang_finnish']    = 'fi';

$config['ua_language_fr']     = 'french';
$config['ua_charset_french']  = 'iso-8859-1';
$config['ua_lang_french']     = 'fr';

$config['ua_language_de']     = 'german';
$config['ua_charset_german']  = 'iso-8859-1';
$config['ua_lang_german']     = 'de';

$config['ua_language_pl']     = 'polish';
$config['ua_charset_polish']  = 'iso-8859-2';
$config['ua_lang_polish']     = 'pl';

$config['ua_language_es']     = 'spanish';
$config['ua_charset_spanish'] = 'iso-8859-1';
$config['ua_lang_spanish']    = 'es';

$config['ua_language_gr']     = 'greek';
$config['ua_charset_greek']   = 'iso-8859-1';
$config['ua_lang_greek']      = 'gr';

$config['ua_language_sw']     = 'swedish';
$config['ua_charset_swedish'] = 'iso-8859-1';
$config['ua_lang_swedish']    = 'sw';

$config['ua_language_ru']     = 'russian';
$config['ua_charset_russian'] = 'iso-8859-1';
$config['ua_lang_russian']    = 'ru';

?>