<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
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
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;
//$active_record = FALSE;

//只读数据库，用于线上服务，目前都使用主库
#$db['default']['hostname'] = 'rdsv2ibfjqzm3a2.mysql.rds.aliyuncs.com';
//$db['default']['hostname'] = 'rdsraai2yanbira1365007489856.mysql.rds.aliyuncs.com';
$db['default']['hostname'] = 'rds5ytuekh6hv4k36g4n.mysql.rds.aliyuncs.com';
$db['default']['username'] = 'rootali';
$db['default']['password'] = 'rootali';
$db['default']['database'] = 'appbk';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

//读写数据库,用于写服务
//$db['user']['hostname'] = 'rdsraai2yanbira1365007489856.mysql.rds.aliyuncs.com';
$db['user']['hostname'] = 'rds5ytuekh6hv4k36g4n.mysql.rds.aliyuncs.com';
$db['user']['username'] = 'rootali';
$db['user']['password'] = 'rootali';
$db['user']['database'] = 'appbk';
$db['user']['dbdriver'] = 'mysql';
$db['user']['dbprefix'] = '';
$db['user']['pconnect'] = TRUE;
$db['user']['db_debug'] = TRUE;
$db['user']['cache_on'] = FALSE;
$db['user']['cachedir'] = '';
$db['user']['char_set'] = 'utf8';
$db['user']['dbcollat'] = 'utf8_general_ci';
$db['user']['swap_pre'] = '';
$db['user']['autoinit'] = TRUE;
$db['user']['stricton'] = FALSE;

//美国数据库，用于国际业务
$db['inter']['hostname'] = 'rds5ytuekh6hv4k36g4n.mysql.rds.aliyuncs.com';
$db['inter']['username'] = 'rootali';
$db['inter']['password'] = 'rootali';
$db['inter']['database'] = 'appbk';
$db['inter']['dbdriver'] = 'mysql';
$db['inter']['dbprefix'] = '';
$db['inter']['pconnect'] = TRUE;
$db['inter']['db_debug'] = TRUE;
$db['inter']['cache_on'] = FALSE;
$db['inter']['cachedir'] = '';
$db['inter']['char_set'] = 'utf8';
$db['inter']['dbcollat'] = 'utf8_general_ci';
$db['inter']['swap_pre'] = '';
$db['inter']['autoinit'] = TRUE;
$db['inter']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
