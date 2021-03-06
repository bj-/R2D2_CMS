<?php
/***************************************************************************
 *                                common.php
 *                            -------------------
 *   begin                : Saturday, Feb 23, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: common.php,v 1.74.2.10 2003/06/04 17:41:39 acydburn Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

//
// addslashes to vars if magic_quotes_gpc is off
// this is a security precaution to prevent someone
// trying to break out of a SQL statement.
//
if( !get_magic_quotes_gpc() and FALSE)
{
	if( is_array($HTTP_GET_VARS) )
	{
		while( list($k, $v) = each($HTTP_GET_VARS) )
		{
			if( is_array($HTTP_GET_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) )
				{
					$HTTP_GET_VARS[$k][$k2] = addslashes($v2);
				}
				@reset($HTTP_GET_VARS[$k]);
			}
			else
			{
				$HTTP_GET_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_GET_VARS);
	}

	if( is_array($HTTP_POST_VARS) )
	{
		while( list($k, $v) = each($HTTP_POST_VARS) )
		{
			if( is_array($HTTP_POST_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) )
				{
					$HTTP_POST_VARS[$k][$k2] = addslashes($v2);
				}
				@reset($HTTP_POST_VARS[$k]);
			}
			else
			{
				$HTTP_POST_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_POST_VARS);
	}

	if( is_array($HTTP_COOKIE_VARS) )
	{
		while( list($k, $v) = each($HTTP_COOKIE_VARS) )
		{
			if( is_array($HTTP_COOKIE_VARS[$k]) )
			{
				while( list($k2, $v2) = each($HTTP_COOKIE_VARS[$k]) )
				{
					$HTTP_COOKIE_VARS[$k][$k2] = addslashes($v2);
				}
				@reset($HTTP_COOKIE_VARS[$k]);
			}
			else
			{
				$HTTP_COOKIE_VARS[$k] = addslashes($v);
			}
		}
		@reset($HTTP_COOKIE_VARS);
	}
}

//
// Define some basic configuration arrays this also prevents
// malicious rewriting of language and otherarray values via
// URI params
//
$board_config = array();
$userdata = array();
$theme = array();
$images = array();
$lang = array();
$gen_simple_header = FALSE;


include($DRoot . '/includes/constants.'.$phpEx);
include($DRoot . '/includes/template.'.$phpEx);
include($DRoot . '/includes/sessions.'.$phpEx);
include($DRoot . '/includes/auth.'.$phpEx);
include($DRoot . '/includes/functions.'.$phpEx);
include($DRoot . '/includes/db.'.$phpEx);

//
// Obtain and encode users IP
//
if( getenv('HTTP_X_FORWARDED_FOR') != '' )
{
	$client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );

	$entries = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
	reset($entries);
	while (list(, $entry) = each($entries)) 
	{
		$entry = trim($entry);
		if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
		{
			$private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/', '/^224\..*/', '/^240\..*/');
			$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);

			if ($client_ip != $found_ip)
			{
				$client_ip = $found_ip;
				break;
			}
		}
	}
}
else
{
	$client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );
}
$user_ip = encode_ip($client_ip);

//
// Setup forum wide options, if this fails
// then we output a CRITICAL_ERROR since
// basic forum information is not available
//
$sql = "SELECT *
	FROM " . CONFIG_TABLE;
if( !($result = $db->sql_query($sql)) )
{
	message_die(CRITICAL_ERROR, "Could not query config information", "", __LINE__, __FILE__, $sql);
}

while ( $row = $db->sql_fetchrow($result) )
{
	$board_config[$row['config_name']] = $row['config_value'];
}


// текущий язык
$sql = "SELECT *
	FROM " . TABLE_LANG;
if( !($result = $db->sql_query($sql)) )
{
	message_die(CRITICAL_ERROR, "Could not query config information", "", __LINE__, __FILE__, $sql);
}

while ( $row = $db->sql_fetchrow($result) )
{
	$site_lang[$row['lang_id']] = $row;
}
$url_lang = $site_lang[$board_config['default_lang']]['lang_sname'];
$user_lang = $site_lang[$board_config['default_lang']]['lang_id'];



// подгружаем моды
if (@$mod_blocks) {
	include $DRoot."/mod/blocks.php";
};

// Дерево меню
function menutree() {
	global $db, $topmenu_pids, $topmenu_data, $paragraf_id;
$sql = "SELECT * FROM " . TABLE_TOPMENU . " ORDER BY `menu_group`, `sortorder`, `menu_id` ASC";

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'Пункты меню отсутсвуют', '', __LINE__, __FILE__, $sql);
	};

$topmenu_data = array();
$topmenu_pid_list = array();
$topmenu_pids = array();
$paragraf_id =  array();

while( $row = $db->sql_fetchrow($result) ) {
 	$topmenu_pids[$row['menu_pid']][] = $row['menu_id'];
	if (!in_array($row['menu_pid'], $topmenu_pid_list)) { $topmenu_pid_list[] =  $row['menu_pid']; };
 	$topmenu_data[$row['menu_id']] = $row;
	$paragraf_id[$row['menu_pid']][$row['menu_path']] = $row;
//	$current_menu_id = 
	};
/*
	$ret = array();
	$ret["topmenu_pids"] = $topmenu_pids;
	$ret["topmenu_data"] = $topmenu_data;
	$ret["paragraf_id"] = $paragraf_id;
	return $ret;
*/
};
menutree();


/*
$menutree = menutree();
$topmenu_pids = $menutree["topmenu_pids"];
$topmenu_data = $menutree["topmenu_data"];
$paragraf_id = $menutree["paragraf_id"];
*/
//echo '<pre>';
//print_r($paragraf_id);
//echo '</pre>';

//
// Show 'Board is disabled' message if needed.
//
if( $board_config['board_disable'] && !defined("IN_ADMIN") && !defined("IN_LOGIN") )
{
	message_die(GENERAL_MESSAGE, 'Board_disable', 'Information');
}


//
// Подключаем дополнительные моды
//
if (@$mod_article_list) {
	include $DRoot . "/mod/article_list.php";
};
?>