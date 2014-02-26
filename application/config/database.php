<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = "goautodialdb";
$active_record = TRUE;

//$db = parse_ini_file("/var/www/html/christest/testdbfile.conf", true);
//var_dump($db);die();
$dbconfig = file_get_contents(config_item('conf_path'));
$oneliner = preg_replace("/ |>|\n|\r|\t|\#.*|;.*/","",$dbconfig);

parse_str($oneliner);


/*
$db['goautodialdb']['hostname'] = "localhost";
$db['goautodialdb']['username'] = "goautodialu";
$db['goautodialdb']['password'] = "pancit888";
$db['goautodialdb']['database'] = "goautodial";
$db['goautodialdb']['dbdriver'] = "mysql";
$db['goautodialdb']['dbprefix'] = "";
$db['goautodialdb']['pconnect'] = FALSE;
$db['goautodialdb']['db_debug'] = TRUE;
$db['goautodialdb']['cache_on'] = FALSE;
$db['goautodialdb']['cachedir'] = "";
$db['goautodialdb']['char_set'] = "utf8";
$db['goautodialdb']['dbcollat'] = "utf8_general_ci";

$db['dialerdb']['hostname'] = "localhost";
$db['dialerdb']['username'] = "cron";
$db['dialerdb']['password'] = "1234";
$db['dialerdb']['database'] = "asterisk";
$db['dialerdb']['dbdriver'] = "mysql";
$db['dialerdb']['dbprefix'] = "";
$db['dialerdb']['pconnect'] = FALSE;
$db['dialerdb']['db_debug'] = TRUE;
$db['dialerdb']['cache_on'] = FALSE;
$db['dialerdb']['cachedir'] = "";
$db['dialerdb']['char_set'] = "utf8";
$db['dialerdb']['dbcollat'] = "utf8_general_ci";

$db['customdialerdb']['hostname'] = "localhost";
$db['customdialerdb']['username'] = "custom";
$db['customdialerdb']['password'] = "custom1234";
$db['customdialerdb']['database'] = "asterisk";
$db['customdialerdb']['dbdriver'] = "mysql";
$db['customdialerdb']['dbprefix'] = "";
$db['customdialerdb']['pconnect'] = FALSE;
$db['customdialerdb']['db_debug'] = TRUE;
$db['customdialerdb']['cache_on'] = FALSE;
$db['customdialerdb']['cachedir'] = "";
$db['customdialerdb']['char_set'] = "utf8";
$db['customdialerdb']['dbcollat'] = "utf8_general_ci";

$db['limesurveydb']['hostname'] = "localhost";
$db['limesurveydb']['username'] = "goautodialu";
$db['limesurveydb']['password'] = "pancit888";
$db['limesurveydb']['database'] = "limesurvey";
$db['limesurveydb']['dbdriver'] = "mysql";
$db['limesurveydb']['dbprefix'] = "";
$db['limesurveydb']['pconnect'] = FALSE;
$db['limesurveydb']['db_debug'] = TRUE;
$db['limesurveydb']['cache_on'] = FALSE;
$db['limesurveydb']['cachedir'] = "";
$db['limesurveydb']['char_set'] = "utf8";
$db['limesurveydb']['dbcollat'] = "utf8_general_ci";
*/

#echo "<pre>";
#var_dump($db);
#die("</pre>");

/* End of file database.php */
/* Location: ./system/application/config/database.php */
