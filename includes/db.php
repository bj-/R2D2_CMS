<?php
/*
Create table 'Gallery'
CREATE TABLE `gallery` (`id` INT( 10 ) NOT NULL AUTO_INCREMENT,`img` VARCHAR( 128 ) ,
`cnt` INT( 10 ) DEFAULT '0' NOT NULL ,`grp` VARCHAR( 32 ) ,`gal` VARCHAR( 32 ) ,`prt` VARCHAR( 32 ) ,
PRIMARY KEY ( `id` ) ,UNIQUE (`img` ));
================

Create table 'WaresCD'
CREATE TABLE `waresCD` (`id` INT( 10 ) DEFAULT '0' NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`wName` VARCHAR( 128 ) DEFAULT '-' NOT NULL ,`Descr` TEXT,`Price` INT( 10 ) DEFAULT '0' NOT NULL ,
`ShDesc` VARCHAR( 255 ) ,`Lang` VARCHAR( 3 ) DEFAULT '-' NOT NULL ,`CDType` INT( 3 ) DEFAULT '0' NOT NULL ,
`Blocked` TINYINT( 1 ) DEFAULT '0' NOT NULL ,UNIQUE (`wName` )) 
===============

*/


switch($dbms)
{
	case 'mysql':
		include($DRoot . '/includes/mysql.'.$phpEx);
		break;

	case 'mysql4':
		include($DRoot . '/db/mysql4.'.$phpEx);
		break;

	case 'postgres':
		include($DRoot . '/db/postgres7.'.$phpEx);
		break;

	case 'mssql':
		include($DRoot . '/db/mssql.'.$phpEx);
		break;

	case 'oracle':
		include($DRoot . '/db/oracle.'.$phpEx);
		break;

	case 'msaccess':
		include($DRoot . '/db/msaccess.'.$phpEx);
		break;

	case 'mssql-odbc':
		include($DRoot . '/db/mssql-odbc.'.$phpEx);
		break;
}

// Make the database connection.
$db = new sql_db($dbhost, $dbuser, $dbpasswd, $dbname, false);
if(!$db->db_connect_id)
{
   message_die(CRITICAL_ERROR, "Could not connect to the database");
}


/*

$db = mysql_connect($database['host'], $database['user'], $database['password']);
mysql_select_db($database['database'], $db);
mysql_query('SET NAMES cp1251');
*/

//define('SHOP_GROUP_TABLE', 'shop_grp');
//define('SHOP_ORDERS_TABLE', 'shop_orders');
//define('SHOP_ORDERS_WARES_TABLE', 'shop_orders_wares');
//define('SHOP_WARES_TABLE', 'shop_wares');
/*
if($db==false) {
	$fo = fopen($_SERVER["DOCUMENT_ROOT"] . '/cs_db_err.htm', "a");
	$ErrStr = 'Error time: ' . date("j/m/Y H:i:s") . " Page: " . $PHP_SELF . "\n";
	fwrite($fo, $ErrStr);
	fclose($fo);
	};
*/
?>