<?php
if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

$table_prefix = 'etalon_';

// database information
$database['name'] = 'mysql';
$database['host'] = 'localhost';
$database['database'] = 'r2d2';
$database['user'] = 'r2d2_cms';
$database['password'] = 'HcsCx2x0DWG';



// совместимость с phpbb движком
$dbms = $database['name'];
$dbhost = $database['host'];
$dbname = $database['database'];
$dbuser = $database['user'];
$dbpasswd = $database['password'];

$phpEx = "php";


	$DRoot = $_SERVER["DOCUMENT_ROOT"]; // локально
?>