<?php
// тесттьесттест
/***************************************************************************
 *                               functions.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: functions.php,v 1.133.2.31 2003/07/20 13:14:27 acydburn Exp $
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
 *
 ***************************************************************************/

//

function setup_style()
{
	global $db, $board_config, $template, $images, $DRoot, $style_name;

 	$template_path = '/templates/' ;
	$template_name = $style_name ;

	$template = new Template($DRoot . $template_path . $template_name);

	if ( $template )
	{
		$current_template_path = $template_path . $template_name;
	 	@include($DRoot . $template_path . $template_name . '/' . $template_name . '.cfg');

		if ( !defined('TEMPLATE_CONFIG') )
		{
			message_die(CRITICAL_ERROR, "Could not open $template_name template config file", '', __LINE__, __FILE__);
		}
		$img_lang = ( file_exists(@phpbb_realpath($DRoot . $current_template_path . '/images/lang_' . $board_config['default_lang'])) ) ? $board_config['default_lang'] : 'english';

		while( list($key, $value) = @each($images) )
		{
			if ( !is_array($value) )
			{
				$images[$key] = str_replace('{LANG}', 'lang_' . $img_lang, $value);
			}
		}
	}

//	return $row;
}


//

//
// Create short (dd.mm.yyyy) date from format and timezone
//
function create_short_date($format, $gmepoch, $tz)
{
	global $board_config;

	return @gmdate("d.m.Y", $gmepoch + (3600 * $tz));
}




//
// This is general replacement for die(), allows templated
// output in users (or default) language, etc.
//
// $msg_code can be one of these constants:
//
// GENERAL_MESSAGE : Use for any simple text message, eg. results 
// of an operation, authorisation failures, etc.
//
// GENERAL ERROR : Use for any error which occurs _AFTER_ the 
// common.php include and session code, ie. most errors in 
// pages/functions
//
// CRITICAL_MESSAGE : Used when basic config data is available but 
// a session may not exist, eg. banned users
//
// CRITICAL_ERROR : Used when config data cannot be obtained, eg
// no database connection. Should _not_ be used in 99.5% of cases
//
function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $template, $board_config, $theme, $lang, $phpEx, $DRoot, $nav_links, $gen_simple_header, $images, $debug_mode;
	global $userdata;
	global $user_ip, $session_length;
	global $starttime, $zone;

//echo $msg_text;
//echo "x-" . @$_SERVER["phpbb_username"] . @$_SERVER["phpbb_user_id"];

	$overall = (@$zone=="board" || !isset($zone)) ? True : False;

	if(defined('HAS_DIED'))
	{
		die("message_die() was called multiple times. This isn't supposed to happen. Was message_die() used in page_tail.php?");
	}
	
	define(HAS_DIED, 1);
	

	$sql_store = $sql;
	
	//
	// Get SQL error if we are debugging. Do this as soon as possible to prevent 
	// subsequent queries from overwriting the status of sql_error()
	//
	if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
	{
		$sql_error = $db->sql_error();

		$debug_text = '';
		$debug_text_lite = '';
		$debug_text_sql_error = '';

		if ( $sql_error['message'] != '' )
		{
			$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
			$debug_text_sql_error .= $sql_error['message'];
			$debug_text_lite .= '<br /><br />SQL Error : ' . $sql_error['code'];
		}

		if ( $sql_store != '' )
		{
			$debug_text .= "<br /><br />$sql_store";
		}

		if ( $err_line != '' && $err_file != '' )
		{
			$debug_text .= '</br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
			$debug_text_lite .= '</br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
		}
	}

	if( empty($userdata) && ( $msg_code == GENERAL_MESSAGE || $msg_code == GENERAL_ERROR ) )
	{
		$userdata = session_pagestart($user_ip, PAGE_INDEX);
		init_userprefs($userdata);
	}

	//
	// If the header hasn't been output then do it
	//
	if ( !defined('HEADER_INC') && $msg_code != CRITICAL_ERROR && $overall)
	{
		if ( empty($lang) )
		{
			if ( !empty($board_config['default_lang']) )
			{
				include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main.'.$phpEx);
			}
			else
			{
				include($phpbb_root_path . 'language/lang_english/lang_main.'.$phpEx);
			}
		}

		if ( empty($template) )
		{
			$template = new Template($phpbb_root_path . 'templates/' . $board_config['board_template']);
		}
		if ( empty($theme) )
		{
			$theme = setup_style($board_config['default_style']);
		}

		//
		// Load the Page Header
		//
		if ( !defined('IN_ADMIN') )
		{
			include($DRoot . '/includes/page_header.'.$phpEx);
		}
		else
		{
			include($DRoot . '/includes/page_header_admin.'.$phpEx);
		}
	}

	switch($msg_code)
	{
		case GENERAL_MESSAGE:
			if ( $msg_title == '' )
			{
				$msg_title = $lang['Information'];
			}
			break;

		case CRITICAL_MESSAGE:
			if ( $msg_title == '' )
			{
				$msg_title = $lang['Critical_Information'];
			}
			break;

		case GENERAL_ERROR:
			if ( $msg_text == '' )
			{
				$msg_text = $lang['An_error_occured'];
			}

			if ( $msg_title == '' )
			{
				$msg_title = $lang['General_Error'];
			}
			break;

		case CRITICAL_ERROR:
			//
			// Critical errors mean we cannot rely on _ANY_ DB information being
			// available so we're going to dump out a simple echo'd statement
			//
			include($phpbb_root_path . 'language/lang_english/lang_main.'.$phpEx);

			if ( $msg_text == '' )
			{
				$msg_text = $lang['A_critical_error'];
			}

			if ( $msg_title == '' )
			{
				$msg_title = 'R2D2 : <b>' . $lang['Critical_Error'] . '</b>';
			}
			break;
	}

	//
	// Add on DEBUG info if we've enabled debug mode and this is an error. This
	// prevents debug info being output for general messages should DEBUG be
	// set TRUE by accident (preventing confusion for the end user!)
	//
	if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
	{
		if ( $debug_text != '' )
		{
			$user_error_text = $msg_text;
			$msg_text = $msg_text . '<br /><br /><b><u>DEBUG MODE</u></b>' . $debug_text_lite; // . $debug_text;
		}
	}

	if ( $msg_code != CRITICAL_ERROR)
	{
		if ( !empty($lang[$msg_text]) )
		{
			$msg_text = $lang[$msg_text];
		}

		if ( !defined('IN_ADMIN') )
		{
			$template->set_filenames(array(
				'message_body' => 'message_body.tpl')
			);
		}
		else
		{
			$template->set_filenames(array(
				'message_body' => 'admin/admin_message_body.tpl')
			);
		}
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $msg_title,
			'MESSAGE_TEXT' => @$user_error_text . $msg_text,
			
			'SQL_ERROR' => $debug_text_sql_error,
			'SQL_QUERY' => $sql_store,
			'FILE_NAME' => $err_file,
			'FILE_LINE' => $err_line,
			'URL_REQUEST_URI' => $_SERVER["REQUEST_URI"],
			'URL_SCRIPT_NAME' => $_SERVER["SCRIPT_NAME"],
			'URL_QUERY_STRING' => $_SERVER["QUERY_STRING"]
		));
		
		if (@$debug_mode) {
			$template->assign_block_vars('swich_debug', array(
			));
		};

		$template->pparse('message_body');

		//
		// write log
		//
		if ($msg_title != $lang['Information']) {
			$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/msg_die.htm', 'a');
			$wr = '$00$DATE: ' . date("Y-m-j G:i:s O") ."<br>\n".
				'$01$PAGE: ruri:' . $_SERVER["REQUEST_URI"] . "; sname:".$_SERVER["SCRIPT_NAME"]."; qstr:".@$_SERVER["QUERY_STRING"]."<br>\n".
				'$02$MSG_TITLE: ' . $msg_title . "<br>\n".
				'$03$USERNAME: ' . @$userdata["username"] . " (id=" . @$userdata["user_id"] .")<br>\n".
				'$04$USERNAME-X: ' . @$_SERVER["phpbb_username"] . " (id=" . @$_SERVER["phpbb_user_id"] .")<br>\n".
				'$05$USER_AGENT: ' . $_SERVER["HTTP_USER_AGENT"] ."<br>\n".
				'$06$REMOTE_ADDR: ' . $_SERVER["REMOTE_ADDR"] ."<br>\n".
				'$07$HTTP_REFERER: ' . $_SERVER["HTTP_REFERER"] ."<br>\n".
				'$08$SQL_ERROR: ' . $debug_text_sql_error ."<br>\n".
				'$09$SQL_QUERY: ' . $sql_store ."<br>\n".
				'$10$MSG_TEXT: ' . $msg_text . "<br>\n\n";
			fwrite($fp, $wr);
			fclose($fp);
			};
		if ( !defined('IN_ADMIN') && $overall)
		{
			include($DRoot . '/includes/page_tail.'.$phpEx);
		}
		ElseIf (!$overall) {}
		else
		{
			include($phpbb_root_path . 'admin/page_footer_admin.'.$phpEx);
		}
	}
	else
	{
		echo "<html>\n<body>\n" . $msg_title . "\n<br /><br />\n" . $msg_text . "</body>\n</html>";
		//
		// write log
		//
		if ($msg_title != $lang['Information']) {
			$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/msg_die.htm', 'a');
			$wr = '$00$DATE: ' . date("Y-m-j G:i:s O") ."<br>\n".
				'$01$PAGE: ruri:' . $_SERVER["REQUEST_URI"] . "; sname:".$_SERVER["SCRIPT_NAME"]."; qstr:".@$_SERVER["QUERY_STRING"]."<br>\n".
				'$02$MSG_TITLE: ' . $msg_title . "<br>\n".
				'$03$USERNAME: ' . @$userdata["username"] . " (id=" . @$userdata["user_id"] .")<br>\n".
				'$04$USERNAME-X: ' . @$_SERVER["phpbb_username"] . " (id=" . @$_SERVER["phpbb_user_id"] .")<br>\n".
				'$05$USER_AGENT: ' . $_SERVER["HTTP_USER_AGENT"] ."<br>\n".
				'$06$REMOTE_ADDR: ' . $_SERVER["REMOTE_ADDR"] ."<br>\n".
				'$07$HTTP_REFERER: ' . $_SERVER["HTTP_REFERER"] ."<br>\n".
				'$08$SQL_ERROR: ' . $debug_text_sql_error ."<br>\n".
				'$09$SQL_QUERY: ' . $sql_store ."<br>\n".
				'$10$MSG_TEXT: ' . $msg_text . "<br>\n\n";
			fwrite($fp, $wr);
			fclose($fp);
			};

	}
	
	exit;
}

//
// This function is for compatibility with PHP 4.x's realpath()
// function.  In later versions of PHP, it needs to be called
// to do checks with some functions.  Older versions of PHP don't
// seem to need this, so we'll just return the original value.
// dougk_ff7 <October 5, 2002>
function phpbb_realpath($path)
{
	global $phpbb_root_path, $phpEx;

	return (!@function_exists('realpath') || !@realpath($phpbb_root_path . 'includes/functions.'.$phpEx)) ? $path : @realpath($path);
}


//
// убираем вражеские символы из пути
//
function str_remove_enemy_char($str) {
	$str = trim($str);
	$str = preg_replace("/  +/"," ",$str);
	$str = str_replace(" ", "_", $str);
	$str = preg_replace('/[^a-zA-Z0-9_-]+/', '', $str);
	$str = preg_replace("/__+/","_",$str);
	return $str; 
};

function str_remove_sql_char($str)
{
	$str = trim($str);
//	$str = str_replace("UNION", "", $str);
	$str = str_replace("\\", "", $str);
	$str = str_replace("'", "", $str);
	$str = str_replace('"', "", $str);
	$str = str_replace("\0", "", $str);
//	$str = preg_replace('/["\']/', '', $str);
	
	return $str;
}

//
// Экранируем апостров
//
function str_encode_char($str) {
	$str = str_replace("'", "''",$str);
	return $str; 
};

function win2utf($value)
{
	return iconv("Windows-1251", "UTF-8", $value);
}
function utf2win($value)
{
	return iconv("UTF-8", "Windows-1251", $value);
}

function sql2rus($val)
{
	// конвертирует дату из SQL формата в русский 2020-12-23 -> 23.12.2020
	$ret = $val;
	$val = substr($val,0,10);
	if ( strlen($val) == 10 )
	{
		$x = explode("-", $val );
		$ret = ( count($x) == 3 ) ? $x[2] . "." . $x[1] . "." . $x[0] : $val;
	}
	return $ret;
}

function sec2hours($seconds)
{
	// преобразовать секунды к человекочитаемому формату [XXs | XXm | XXh | ...]
	$ret = ( $seconds == 0 ) ? "0s" : $seconds . "s";
	$ret = ( $seconds > 60 ) ? round($seconds / 60) . "m" : $ret;
	$ret = ( $seconds > 60*60 ) ? round($seconds / (60*60)) . "h"  : $ret;
	$ret = ( $seconds > 60*60*24 ) ? round($seconds / (60*60*24)) . "D"  : $ret;
	$ret = ( $seconds > 60*60*24*30 ) ? round($seconds / (60*60*24*30)) . "M"  : $ret;
	$ret = ( $seconds > 60*60*24*30*12 ) ? round($seconds / (60*60*24*30*12)) . "Y"  : $ret;
	return $ret; // . "($seconds)";
}

function format_bytes($val)
{
	// Байты приводим к кило-метрам-гигам-терам
	$ret = ( $val == 0 ) ? "0b" : $val . "b";
	$ret = ( $val > 1024) ? round($val / 1024) . "Kb" : $ret;
	$ret = ( $val > 1024*1024 ) ? round($val / (1024*1024)) . "Mb"  : $ret;
	$ret = ( $val > 1024*1024*1024 ) ? round($val / (1024*1024*1024), 2) . "Gb"  : $ret;
	$ret = ( $val > 1024*1024*1024*1024 ) ? round($val / (1024*1024*1024*1024)) . "Tb"  : $ret;
	return $ret; // . "($val)";
}


function int2kkk($val)
{
	// convert x1000 of int value to "k"
	$val = intval($val);
	$ret = ( $val >= 1000 ) ? round($val/1000) . "k" : $val;
	$ret = ( $val >= 1000000 ) ? round($val/1000/1000) . "kk" : $ret;
	$ret = ( $val >= 1000000000 ) ? round($val/1000/1000/1000) . "kkk" : $ret;
	
	return $ret;
}

?>