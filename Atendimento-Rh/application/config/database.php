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
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7.
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

$db['default']['hostname'] = '192.168.192.2';
$db['default']['username'] = 'root';
$db['default']['password'] = 'esminfo';
$db['default']['database'] = 'test';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";  
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$tnsname = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.192.4)(PORT = 1521))
            (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = LGXPRD)))';

$db['logix']['hostname'] = $tnsname;
$db['logix']['username'] = 'esmaltec';
$db['logix']['password'] = 'esmaltec';
$db['logix']['database'] = 'esmaltec';
$db['logix']['dbdriver'] = 'oci8';
$db['logix']['dbprefix'] = "";
$db['logix']['pconnect'] = TRUE;
$db['logix']['db_debug'] = TRUE;
$db['logix']['cache_on'] = FALSE;
$db['logix']['cachedir'] = "";
$db['logix']['char_set'] = 'utf8';
$db['logix']['dbcollat'] = 'utf8_general_ci';
$db['logix']['swap_pre'] = "";
$db['logix']['autoinit'] = TRUE;
$db['logix']['stricton'] = FALSE;   



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

$db['cid']['hostname'] = "192.168.192.6";
$db['cid']['username'] = "inet";
$db['cid']['password'] = "inet";
$db['cid']['database'] = "cid";
$db['cid']['dbdriver'] = "mysql";
$db['cid']['dbprefix'] = "";
$db['cid']['pconnect'] = FALSE;
$db['cid']['db_debug'] = FALSE;
$db['cid']['cache_on'] = FALSE;
$db['cid']['cachedir'] = "";
$db['cid']['char_set'] = "latin1";
$db['cid']['dbcollat'] = "latin1_general_ci";

$tnsnam = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.192.4)(PORT = 1521))
            (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = LGXPRD)))';

$db['funcio']['hostname'] = $tnsnam;
$db['funcio']['username'] = 'logix';
$db['funcio']['password'] = 'logix';
$db['funcio']['database'] = 'logix';
$db['funcio']['dbdriver'] = 'oci8';
$db['funcio']['dbprefix'] = "";
$db['funcio']['pconnect'] = TRUE;
$db['funcio']['db_debug'] = TRUE;
$db['funcio']['cache_on'] = FALSE;
$db['funcio']['cachedir'] = "";
$db['funcio']['char_set'] = 'utf8';
$db['funcio']['dbcollat'] = 'utf8_general_ci';
$db['funcio']['swap_pre'] = "";
$db['funcio']['autoinit'] = TRUE;
$db['funcio']['stricton'] = FALSE;   



$tnsn = '(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.192.4)(PORT = 1521))
            (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = LGXPRD)))';

$db['lgx']['hostname'] = $tnsn;
$db['lgx']['username'] = 'logix';
$db['lgx']['password'] = 'logix';
$db['lgx']['database'] = 'logix';
$db['lgx']['dbdriver'] = 'oci8';
$db['lgx']['dbprefix'] = "";
$db['lgx']['pconnect'] = TRUE;
$db['lgx']['db_debug'] = TRUE;
$db['lgx']['cache_on'] = FALSE;
$db['lgx']['cachedir'] = "";
$db['lgx']['char_set'] = 'utf8';
$db['lgx']['dbcollat'] = 'utf8_general_ci';
$db['lgx']['swap_pre'] = "";
$db['lgx']['autoinit'] = TRUE;
$db['lgx']['stricton'] = FALSE;   


/* End of file database.php */
/* Location: ./application/config/database.php */