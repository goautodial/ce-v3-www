<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "go_index";
$route['scaffolding_trigger'] = "";
$route['campaigns'] = "go_campaign_ce";
$route['users'] = "go_user_ce/go_users";
$route['scripts'] = "go_script_ce";
$route['ingroups'] = "go_ingroup";
$route['audiostore'] = "go_audiostore";
$route['reports'] = "go_site/go_reports";
$route['callhistory'] = "go_callhistory";
$route['dashboard'] = "go_site/go_dashboard";
$route['portal'] = "go_site/go_dashboard";
$route['support'] = "go_support_ce";
$route['dnc'] = "go_dnc_ce";
$route['logout'] = "go_site/logout";
$route['usergroups'] = "go_usergroup_ce";
$route['phones'] = "go_phones_ce";
$route['carriers'] = "go_carriers_ce";
//$route['settings'] = "go_systemsettings_ce";
$route['servers'] = "go_servers_ce";
$route['telephony'] = "go_user_ce/go_users";
$route['voicemails'] = "go_voicemail_ce";
$route['adminsettings'] = "go_carriers_ce";
$route['credits']="go_index/credits";
$route['agplv2']="go_index/agplv2";
$route['gplv2']="go_index/gplv2";
$route['search'] = "go_search_ce/go_search";
$route["search/(:any)"] = "go_search_ce/go_search/$1";
$route['calltimes'] = "go_calltimes_ce";
$route['logs'] = "go_logs_ce";
$route['go_list/go_list'] = "go_list/go_list";
$route['moh'] = "go_moh_ce";
$route['settings'] = "go_settings_ce";

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */
