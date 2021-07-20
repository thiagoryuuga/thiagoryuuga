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

putenv("ORACLE_HOME=/u01/app/oracle/product/9i_32bit");

$active_group = 'default';
$active_record = TRUE;


$tnsname = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.192.4)(PORT = 1521))
            (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = LGXPRD)))';

//$tnsname = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.192.10)(PORT = 1521))
//         (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = TESTE)))';

$db['default']['hostname'] = $tnsname;
$db['default']['username'] = 'esmaltec';
$db['default']['password'] = 'esmaltec';
$db['default']['database'] = 'esmaltec';
$db['default']['dbdriver'] = 'oci8';
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

$db['UsersMaster']['hostname'] = "192.168.192.6";
$db['UsersMaster']['username'] = "inet";
$db['UsersMaster']['password'] = "inet";
$db['UsersMaster']['database'] = "UsersMaster";
$db['UsersMaster']['dbdriver'] = "mysql";
$db['UsersMaster']['dbprefix'] = "";
$db['UsersMaster']['pconnect'] = FALSE;
$db['UsersMaster']['db_debug'] = FALSE;
$db['UsersMaster']['cache_on'] = FALSE;
$db['UsersMaster']['cachedir'] = "";
$db['UsersMaster']['char_set'] = "latin1";
$db['UsersMaster']['dbcollat'] = "latin1_general_ci";

$tnsname_logix = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.192.4)(PORT = 1521))
            (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = LGXPRD)))';

//$tnsname_logix = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.192.10)(PORT = 1521))
//            (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = TESTE)))';

$db['LOGIX']['hostname'] = $tnsname_logix;
$db['LOGIX']['username'] = 'logix';
$db['LOGIX']['password'] = 'logix';
$db['LOGIX']['database'] = 'logix';
$db['LOGIX']['dbdriver'] = 'oci8';
$db['LOGIX']['dbprefix'] = '';
$db['LOGIX']['pconnect'] = TRUE;
$db['LOGIX']['db_debug'] = TRUE;
$db['LOGIX']['cache_on'] = FALSE;
$db['LOGIX']['cachedir'] = '';
$db['LOGIX']['char_set'] = 'utf8';
$db['LOGIX']['dbcollat'] = 'utf8_general_ci';
$db['LOGIX']['swap_pre'] = '';
$db['LOGIX']['autoinit'] = TRUE;
$db['LOGIX']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */