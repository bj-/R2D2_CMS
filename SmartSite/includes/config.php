<?php
if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

$phpEx = "php";
$style_name = "cs";

//$starttime = 0;

// хостозависимые настройки
$localhost = false;
if ($_SERVER["HTTP_HOST"] == 'deputat') {
	$DRoot = $_SERVER["DOCUMENT_ROOT"]; // локально
//	$localhost = true;
	}
Else {
 	$DRoot = $_SERVER["DOCUMENT_ROOT"]; // на сервере
};


// for debug
//$debug_mode = TRUE;
$debug_mode = FALSE;
?>