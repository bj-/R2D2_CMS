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

function get_db_stat($mode)
{
	global $db;

	switch( $mode )
	{
		case 'usercount':
			$sql = "SELECT COUNT(user_id) AS total
				FROM " . USERS_TABLE . "
				WHERE user_id <> " . ANONYMOUS;
			break;

		case 'newestuser':
			$sql = "SELECT user_id, username
				FROM " . USERS_TABLE . "
				WHERE user_id <> " . ANONYMOUS . "
				ORDER BY user_id DESC
				LIMIT 1";
			break;

		case 'postcount':
		case 'topiccount':
			$sql = "SELECT SUM(forum_topics) AS topic_total, SUM(forum_posts) AS post_total
				FROM " . FORUMS_TABLE;
			break;
	}

	if ( !($result = $db->sql_query($sql)) )
	{
		return false;
	}

	$row = $db->sql_fetchrow($result);

	switch ( $mode )
	{
		case 'usercount':
			return $row['total'];
			break;
		case 'newestuser':
			return $row;
			break;
		case 'postcount':
			return $row['post_total'];
			break;
		case 'topiccount':
			return $row['topic_total'];
			break;
	}

	return false;
}

//
// Get Userdata, $user can be username or user_id. If force_str is true, the username will be forced.
//
function get_userdata($user, $force_str = false)
{
	global $db;

	if (intval($user) == 0 || $force_str)
	{
		$user = trim(htmlspecialchars($user));
		$user = substr(str_replace("\\'", "'", $user), 0, 25);
		$user = str_replace("'", "\\'", $user);
	}
	else
	{
		$user = intval($user);
	}

	$sql = "SELECT *
		FROM " . USERS_TABLE . " 
		WHERE ";
	$sql .= ( ( is_integer($user) ) ? "user_id = $user" : "username = '" .  $user . "'" ) . " AND user_id <> " . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Tried obtaining data for a non-existent user', '', __LINE__, __FILE__, $sql);
	}

	return ( $row = $db->sql_fetchrow($result) ) ? $row : false;
}


// формирование URL
function gen_url($url) {
// парсим урл для домашнего и серверного использования с и без мод-реврайта
	global $localhost;
	$ret = '';
	if ($localhost) {
		$lang = '';
		if (!strpos($url, "/")) { 
			$surl = substr($url, 1); 
			$lang = substr($surl, 0, strpos($surl, "/"));
			$url = substr($surl, strpos($surl, "/"));
		};
		$ret = "/article.php?lang=".$lang."&article=".$url;
		}
	Else {
		$ret = $url;
	};
	return $ret;
};

// Проверяем права пользователя. если нет - выкидываем
function privilegies_check($groups, $users) {
	//  $groups - массив с юзерлевелами которым разрешен доступ
	//  $users - массив с конкретными ID юзеров которым разрешен доступ, 
	// либо All если всем подряд
	global $userdata;
	
	if (!in_array($userdata['user_level'],$groups) and (!array_search($userdata['user_id'], $users) or !$users[0]=="all")) {
		message_die(GENERAL_ERROR, 'Пользователь не имеет прав', '', __LINE__, __FILE__, $sql);
		exit;
	};
};

//
// Initialise user settings on page load
function init_userprefs($userdata)
{
	global $board_config, $theme, $images;
	global $template, $lang, $phpEx, $DRoot;
	global $nav_links, $site_lang;

//echo 	$userdata['user_lang'] . $site_lang[1]['lang_path'];

	if ( $userdata['user_id'] != ANONYMOUS )
	{
		if ( !empty($userdata['user_lang']))
		{
			$board_config['default_lang'] = $site_lang[$userdata['user_lang']]['lang_path'];
		}
		Else {
			$board_config['default_lang'] = $site_lang[$userdata['user_lang']]['lang_path'];
		}

		if ( !empty($userdata['user_dateformat']) )
		{
			$board_config['default_dateformat'] = $userdata['user_dateformat'];
		}

		if ( isset($userdata['user_timezone']) )
		{
			$board_config['board_timezone'] = $userdata['user_timezone'];
		}
	}
	if ( !file_exists(@phpbb_realpath($DRoot . '/db/language/lang_' . $board_config['default_lang'] . '/lang_main.'.$phpEx)) )
	{
		$board_config['default_lang'] = $site_lang[1]['lang_path'];
	}

	include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main.' . $phpEx);

	if ( defined('IN_ADMIN') )
	{
		if( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.'.$phpEx)) )
		{
			$board_config['default_lang'] = 'english';
		}

		include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . $phpEx);
	}

	//
	// Set up style
	//
	if ( !$board_config['override_user_style'] )
	{
		if ( $userdata['user_id'] != ANONYMOUS && $userdata['user_style'] > 0 )
		{
			if ( $theme = setup_style($userdata['user_style']) )
			{
				return;
			}
		}
	}

	$theme = setup_style($board_config['default_style']);

	//
	// Mozilla navigation bar
	// Default items that should be valid on all pages.
	// Defined here to correctly assign the Language Variables
	// and be able to change the variables within code.
	//
	$nav_links['top'] = array ( 
		'url' => append_sid($phpbb_root_path . 'index.' . $phpEx),
		'title' => $board_config['sitename']
	);
	$nav_links['search'] = array ( 
		'url' => append_sid($phpbb_root_path . 'search.' . $phpEx),
		'title' => $lang['Search']
	);
	$nav_links['help'] = array ( 
		'url' => append_sid($phpbb_root_path . 'faq.' . $phpEx),
		'title' => $lang['FAQ']
	);
	$nav_links['author'] = array ( 
		'url' => append_sid($phpbb_root_path . 'memberlist.' . $phpEx),
		'title' => $lang['Memberlist']
	);

	return;
}

function setup_style($style)
{
	global $db, $board_config, $template, $images, $DRoot;

	$sql = "SELECT *
		FROM " . THEMES_TABLE . "
		WHERE themes_id = $style";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(CRITICAL_ERROR, 'Could not query database for theme info');
	}

	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(CRITICAL_ERROR, "Could not get theme data for themes_id [$style]");
	}

	$template_path = '/templates/' ;
	$template_name = $row['template_name'] ;

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

	return $row;
}

function encode_ip($dotquad_ip)
{
	$ip_sep = explode('.', $dotquad_ip);
	return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
}

function decode_ip($int_ip)
{
	$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
	return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
}

//
// Create date/time from format and timezone
//
function create_date($format, $gmepoch, $tz)
{
	global $board_config, $lang;
	static $translate;

	if ( empty($translate) && $board_config['default_lang'] != 'english' )
	{
		@reset($lang['datetime']);
		while ( list($match, $replace) = @each($lang['datetime']) )
		{
			$translate[$match] = $replace;
		}
	}

	return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
}

// считаем количество минут/дней/месяцев/лет с последнего посещения
function listvisit($date, $format) {
	global $lang;

	$lastvisit = time() - $date;
//	echo $date . "-" . time() . "-".$lastvisit;
	if ($lastvisit>=0 and $lastvisit <= 60) { $ret = $lang['1_min']; }
	elseif ($lastvisit>=61 and $lastvisit <=299 ) { $ret = $lang['sev_min']; }
	elseif ($lastvisit>=300 and $lastvisit <=3599 ) { $ret = $lang['1_hour']; }
	elseif ($lastvisit>=3600 and $lastvisit <=86400 ) { $ret = $lang['1_day']; }
	elseif ($lastvisit>=86401 and $lastvisit <=604800 ) { $ret = $lang['sev_days']; }
	elseif ($lastvisit>=604801 and $lastvisit <=2419200 ) { $ret = $lang['sev_week']; }
	elseif ($lastvisit>=2419201 and $lastvisit <=5356800 ) { $ret = $lang['sev_month']; }
	elseif ($lastvisit>=5356801 and $lastvisit <=16070400 ) { $ret = $lang['more_2_month']; }
	elseif ($lastvisit>=16070401 ) { $ret = $lang['more_6_month']; }
	
	return $ret;
};

//
// Create short (dd.mm.yyyy) date from format and timezone
//
function create_short_date($format, $gmepoch, $tz)
{
	global $board_config;

	return @gmdate("d.m.Y", $gmepoch + (3600 * $tz));
}

//
// Pagination routine, generates
// page number sequence
//
function generate_pagination($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = TRUE)
{
	global $lang;

	$total_pages = ceil($num_items/$per_page);

	if ( $total_pages == 1 )
	{
		return '';
	}

	$on_page = floor($start_item / $per_page) + 1;

	$page_string = '';
	if ( $total_pages > 10 )
	{
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;

		for($i = 1; $i < $init_page_max + 1; $i++)
		{
			$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
			if ( $i <  $init_page_max )
			{
				$page_string .= ", ";
			}
		}

		if ( $total_pages > 3 )
		{
			if ( $on_page > 1  && $on_page < $total_pages )
			{
				$page_string .= ( $on_page > 5 ) ? ' ... ' : ', ';

				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;

				for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)
				{
					$page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
					if ( $i <  $init_page_max + 1 )
					{
						$page_string .= ', ';
					}
				}

				$page_string .= ( $on_page < $total_pages - 4 ) ? ' ... ' : ', ';
			}
			else
			{
				$page_string .= ' ... ';
			}

			for($i = $total_pages - 2; $i < $total_pages + 1; $i++)
			{
				$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>'  : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
				if( $i <  $total_pages )
				{
					$page_string .= ", ";
				}
			}
		}
	}
	else
	{
		for($i = 1; $i < $total_pages + 1; $i++)
		{
			$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
			if ( $i <  $total_pages )
			{
				$page_string .= ', ';
			}
		}
	}

	if ( $add_prevnext_text )
	{
		if ( $on_page > 1 )
		{
			$page_string = ' <a href="' . append_sid($base_url . "&amp;start=" . ( ( $on_page - 2 ) * $per_page ) ) . '">' . $lang['Previous'] . '</a>&nbsp;&nbsp;' . $page_string;
		}

		if ( $on_page < $total_pages )
		{
			$page_string .= '&nbsp;&nbsp;<a href="' . append_sid($base_url . "&amp;start=" . ( $on_page * $per_page ) ) . '">' . $lang['Next'] . '</a>';
		}

	}

	$page_string = $lang['Goto_page'] . ' ' . $page_string;

	return $page_string;
}

//
// This does exactly what preg_quote() does in PHP 4-ish
// If you just need the 1-parameter preg_quote call, then don't bother using this.
//
function phpbb_preg_quote($str, $delimiter)
{
	$text = preg_quote($str);
	$text = str_replace($delimiter, '\\' . $delimiter, $text);
	
	return $text;
}

//
// Obtain list of naughty words and build preg style replacement arrays for use by the
// calling script, note that the vars are passed as references this just makes it easier
// to return both sets of arrays
//
function obtain_word_list(&$orig_word, &$replacement_word)
{
	global $db;

	//
	// Define censored word matches
	//
	$sql = "SELECT word, replacement
		FROM  " . WORDS_TABLE;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get censored words from database', '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		do 
		{
			$orig_word[] = '#\b(' . str_replace('\*', '\w*?', phpbb_preg_quote($row['word'], '#')) . ')\b#i';
			$replacement_word[] = $row['replacement'];
		}
		while ( $row = $db->sql_fetchrow($result) );
	}

	return true;
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
			'MESSAGE_TEXT' => @$user_error_text,
			
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

function redirect($url)
{
	global $db, $board_config;

	if (!empty($db))
	{
		$db->sql_close();
	}

	$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
	$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) : '';
	$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));
	$script_name = ($script_name == '') ? $script_name : '/' . $script_name;
	$url = preg_replace('#^\/?(.*?)\/?$#', '/\1', trim($url));

	// Redirect via an HTML form for PITA webservers
	if (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')))
	{
		header('Refresh: 0; URL=' . $server_protocol . $server_name . $server_port . $script_name . $url);
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset='.$lang['ENCODING'].'"><meta http-equiv="refresh" content="0; url=' . $server_protocol . $server_name . $server_port . $script_name . $url . '"><title>Redirect</title></head><body><div align="center">If your browser does not support meta redirection please click <a href="' . $server_protocol . $server_name . $server_port . $script_name . $url . '">HERE</a> to be redirected</div></body></html>';
		exit;
	}

	// Behave as per HTTP/1.1 spec for others
	header('Location: ' . $server_protocol . $server_name . $server_port . $script_name . $url);
	exit;
}

//
// собираем путь главного меню в раздел с путями родителей
//
function get_full_url($id) {
	global $topmenu_data;
	$ret = "";
	if (@$topmenu_data[$id]['menu_pid']) {
		$ret .= get_full_url($topmenu_data[$id]['menu_pid']) . "/";

	};
	$ret .= @$topmenu_data[$id]['menu_path'];
	return $ret;
};

//
// Дерево главного меню.
//
function buildtopmenuonelvl($pid, $menu_desc) {
	global $topmenu_pids, $topmenu_data, $url_lang;
	$i = 0;
	$ret = "";
	$tree = "";
	$url = "";
	$menu_desc = "";
	while ($topmenu_pids[$pid][$i]) {
		$c_id = $topmenu_pids[$pid][$i];								// длину названия переменной уменьшаем (удобства ради)
		
		$url = "/" .$url_lang."/".get_full_url($c_id);
		
		$tree .= '<p><a href="'.$url.'/" style="color: #426394; text-decoration: none;" title="'.@$topmenu_data[$c_id]['menu_desc'].'">' . $topmenu_data[$c_id]['menu_name'] . "</a></p>";
		if (@$topmenu_data[$c_id]['menu_desc']) {	// если есть описание - готовим переменную с текстом для передачи в дочернюю функцию
			$menu_desc = $topmenu_data[$c_id]['menu_desc'];
		};
		$tree .= "\n";
		$i++;
	};
	$ret = $tree;
	return $ret;
};

//
// Дерево главного меню.
//
function buildtopmenu($pid, $menu_desc) {
	global $topmenu_pids, $topmenu_data, $url_lang, $localhost, $blank_tree_top_menu;
	$menu_top_first = (@$menu_top_first) ? $menu_top_first = $menu_top_first : $menu_top_first = 1; // метка для конструктора меню 1 - это топ меню и первый вход в функцию, 2 - это топ меню (требуется для добавления доп элементов разделителей и пр.)
	$menu_top_first = (@$blank_tree_top_menu) ? 0 : $menu_top_first; // если надо вывести меню просто списком.
	$i = 0;
	$ret = "";
	$tree = "";
	$url = "";
	if ($menu_desc) {							// если есть описание для данного пункта меню - вставлем его первой строкой
		$menu_desc_style = ($menu_top_first) ? ' style="color:#000000; PADDING: 8px;"' : "";
		$tree .= "<li".$menu_desc_style.">".$menu_desc."</li>";
	};
	$menu_desc = "";
	while ($topmenu_pids[$pid][$i]) {
		$c_id = $topmenu_pids[$pid][$i];								// длину названия переменной уменьшаем (удобства ради)
		
		
		if ($localhost) {
			$url = '/article.php?lang='. $url_lang .'&article=' . get_full_url($c_id);
			}
		else {
			$url = "/" .$url_lang."/".get_full_url($c_id);
		};
//		$url = 
		
		$tree .= ($pid == 0 & @!$blank_tree_top_menu) ? '<li><img src="/pic/tmenusep.gif" alt="" width="2" height="30" border="0" /></li>' : "";
		
		$tree .= '<li><a href="'.$url.'/">' . $topmenu_data[$c_id]['menu_name'] . "</a>";
		if (@$topmenu_data[$c_id]['menu_desc']) {	// если есть описание - готовим переменную с текстом для передачи в дочернюю функцию
			$menu_desc = $topmenu_data[$c_id]['menu_desc'];
		};
		$tree .= buildtopmenu($c_id, $menu_desc);
		$tree .= "</li>\n";
		$i++;
	};
	if ($tree) {			// если небыло детей, то и группировка не нужна.
		if ($menu_top_first == 1 and $pid == 0) {
			$top_menu_script = ' id="nav"';
			$menu_top_first = 2;
		};
		$ret = "<ul".@$top_menu_script.">\n" . $tree . "</ul>";
	};
	return $ret;
};

//
// Дерево главного меню.
//
function buildparagraflist($pid,$level,$exclude=FALSE) {

	// $exclude - ID пункта который необходимо исключить из списка.

	// Добавляем главную страницу
//	$selected = ($article_data[0]["paragraf_id"] == 0) ? " selected" : ""; // автовыбор нужного раздела
//	$tree .= "<option value='".$topmenu_data[$c_id]['menu_id']."'" . $selected .">".$sep.$topmenu_data[$c_id]['menu_name']."</option>";

	global $topmenu_pids, $topmenu_data, $url_lang, $localhost, $blank_tree_top_menu, $article_data, $menu_edit_data;
	$menu_top_first = (@$menu_top_first) ? $menu_top_first = $menu_top_first : $menu_top_first = 1; // метка для конструктора меню 1 - это топ меню и первый вход в функцию, 2 - это топ меню (требуется для добавления доп элементов разделителей и пр.)
	$menu_top_first = (@$blank_tree_top_menu) ? 0 : $menu_top_first; // если надо вывести меню просто списком.
	$i = 0;
	$ret = "";
	$tree = "";
	while ($topmenu_pids[$pid][$i]) {
		$c_id = $topmenu_pids[$pid][$i];								// длину названия переменной уменьшаем (удобства ради)
		
		$i_lev = 0;
		$sep = "";
		while ($i_lev < $level) {
			$sep .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			$i_lev++;
			};
		$selected = ($article_data[0]["paragraf_id"] == $topmenu_data[$c_id]['menu_id'] or $menu_edit_data["menu_pid"] == $topmenu_data[$c_id]['menu_id']) ? " selected" : ""; // автовыбор нужного раздела
		$tree .= ($exclude == $topmenu_data[$c_id]['menu_id']) ? "" : "<option value='".$topmenu_data[$c_id]['menu_id']."'" . $selected .">".$sep.$topmenu_data[$c_id]['menu_name']."</option>";
		$level++;
		$tree .= buildparagraflist($c_id,$level, $exclude);
		$level--;
		$tree .= "\n";
		$i++;
	};
	if ($tree) {			// если небыло детей, то и группировка не нужна.
		$ret = "\n" . $tree . "";
	};
	return $ret;
};


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

//
// Экранируем апостров
//
function str_encode_char($str) {
	$str = str_replace("'", "''",$str);
	return $str; 
};


/*----------------------------------------------------------------------------------+
|
|   Функция создающая уменьшенную копию фотографии $filename,
|   которая помещается в файл $smallimage
|   Уменьшенная копия имеет ширину и высоту равную
|   $w и $h пикселам, соответственно. Это максимально возможные значения.
|   Они будут пересчитаны чтобы сохранить пропорции масштабируемого изображения.
|	
+-----------------------------------------------------------------------------------*/
function resizeimg($filename, $smallimage, $w, $h) {
	// Имя файла с масштабируемым изображением 
	$filename = $filename; 
	// Имя файла с уменьшенной копией. 
	$smallimage = $smallimage;
	// определим коэффициент сжатия изображения, которое будем генерить 
	$ratio = $w/$h; 
	// получим размеры исходного изображения 
	$size_img = getimagesize($filename); 
	// Если размеры меньше, то масштабирования не нужно и копируем файл (некрасиво, зато просто :)
	if (($size_img[0]<$w) && ($size_img[1]<$h)) {
		move_uploaded_file($filename,$smallimage);
		return "original"; 
	};
	// получим коэффициент сжатия исходного изображения 
	$src_ratio=$size_img[0]/$size_img[1]; 
		// Здесь вычисляем размеры уменьшенной копии, чтобы при масштабировании сохранились 
	// пропорции исходного изображения 
	if ($ratio<$src_ratio) { 
		$h = $w/$src_ratio; 
	} 
	else { 
		$w = $h*$src_ratio; 
	} 
	// создадим пустое изображение по заданным размерам 
	$dest_img = imagecreatetruecolor($w, $h);
	$white = imagecolorallocate($dest_img, 255, 255, 255);
	if ($size_img[2]==2)  $src_img = imagecreatefromjpeg($filename);
	else if ($size_img[2]==1) $src_img = imagecreatefromgif($filename);
	else if ($size_img[2]==3) $src_img = imagecreatefrompng($filename); 

	// масштабируем изображение     функцией imagecopyresampled() 
	// $dest_img - уменьшенная копия 
	// $src_img - исходной изображение 
	// $w - ширина уменьшенной копии 
	// $h - высота уменьшенной копии         
	// $size_img[0] - ширина исходного изображения 
	// $size_img[1] - высота исходного изображения 
	imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $w, $h, $size_img[0], $size_img[1]);
	// сохраняем уменьшенную копию в файл 
	if ($size_img[2]==2)  imagejpeg($dest_img, $smallimage, 90);
	else if ($size_img[2]==1) imagegif($dest_img, $smallimage);
	else if ($size_img[2]==3) imagepng($dest_img, $smallimage, 1);
	// чистим память от созданных изображений 
	imagedestroy($dest_img); 
	imagedestroy($src_img);
	return "resized";
};
?>