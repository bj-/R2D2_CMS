<?php
/***************************************************************************
 *                              page_header.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: page_header.php,v 1.106.2.20 2003/06/10 20:48:19 acydburn Exp $
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

define('HEADER_INC', TRUE);

$forum_zone = ($_SERVER["PHP_SELF"] == "/index.php") ? TRUE : FALSE;

//include($phpbb_root_path . 'forall.php');

//
// gzip_compression
//
$do_gzip_compress = FALSE;
if ( $board_config['gzip_compress'] )
{
	$phpver = phpversion();

	$useragent = (isset($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;

	if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
	{
		if ( extension_loaded('zlib') )
		{
			ob_start('ob_gzhandler');
		}
	}
	else if ( $phpver > '4.0' )
	{
		if ( strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') )
		{
			if ( extension_loaded('zlib') )
			{
				$do_gzip_compress = TRUE;
				ob_start();
				ob_implicit_flush(0);

				header('Content-Encoding: gzip');
			}
		}
	}
}


//
// Parse and show the overall header.
//
$template->set_filenames(array(
	'overall_header' => ( empty($gen_simple_header) ) ? 'overall_header.tpl' : 'simple_header.tpl')
);

// Mod_Rewrite
ob_start();


//
// Generate logged in/logged out status
//
if ( $userdata['session_logged_in'] )
{
	$u_login_logout = 'login.'.$phpEx.'?logout=true&amp;sid=' . $userdata['session_id'];
	$l_login_logout = $lang['Logout'] . ' [ ' . $userdata['username'] . ' ]';
//	$user_profile_link = '<a href="' . append_sid("userp.php?mode=editprofile">' . $row['username'] . '</a>';

}
else
{
	$u_login_logout = 'login.'.$phpEx;
	$l_login_logout = $lang['Login'];
}

$s_last_visit = ( $userdata['session_logged_in'] ) ? create_date($board_config['default_dateformat'], $userdata['user_lastvisit'], $board_config['board_timezone']) : '';

//
// Get basic (usernames + totals) online
// situation
//
$logged_visible_online = 0;
$logged_hidden_online = 0;
$guests_online = 0;
$online_userlist = '';

if (defined('SHOW_ONLINE'))
{

//	$user_forum_sql = ( !empty($forum_id) ) ? "AND s.session_page = " . intval($forum_id) : '';
	$sql = "SELECT u.username, u.user_id, u.user_allow_viewonline, u.user_level, s.session_logged_in, s.session_ip
		FROM ".USERS_TABLE." u, ".SESSIONS_TABLE." s
		WHERE u.user_id = s.session_user_id
			AND s.session_time >= ".( time() - 300 ) . "
		ORDER BY u.username ASC, s.session_ip ASC";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user/online information', '', __LINE__, __FILE__, $sql);
	}

	$userlist_ary = array();
	$userlist_visible = array();

	$prev_user_id = 0;
	$prev_user_ip = '';

	while( $row = $db->sql_fetchrow($result) ) {
		// User is logged in and therefor not a guest
		if ( $row['session_logged_in'] ) {
			// Skip multiple sessions for one user
			if ( $row['user_id'] != $prev_user_id ) {
				$user_online_link = '<a href="' . append_sid("/userp.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '">' . $row['username'] . '</a>';
				$logged_visible_online++;
				$online_userlist .= ( $online_userlist != '' ) ? ', ' . $user_online_link : $user_online_link;
				};
			$prev_user_id = $row['user_id'];
			}
		else {
			// Skip multiple sessions for one user
			if ( $row['session_ip'] != $prev_session_ip ) {
				$guests_online++;
				};
			};
		$prev_session_ip = $row['session_ip'];
		};

	$db->sql_freeresult($result);

	if ( empty($online_userlist) ) {
		$online_userlist = $lang['None'];
		};
	$online_userlist = $lang['Registered_users'] . ' ' . $online_userlist;

	$total_online_users = $logged_visible_online + $guests_online;

	if ( $total_online_users > $board_config['record_online_users']) {
		$board_config['record_online_users'] = $total_online_users;
		$board_config['record_online_date'] = time();

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '$total_online_users'
			WHERE config_name = 'record_online_users'";
		if ( !$db->sql_query($sql) ) {
			message_die(GENERAL_ERROR, 'Could not update online user record (nr of users)', '', __LINE__, __FILE__, $sql);
			};

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . $board_config['record_online_date'] . "'
			WHERE config_name = 'record_online_date'";
		if ( !$db->sql_query($sql) ) {
			message_die(GENERAL_ERROR, 'Could not update online user record (date)', '', __LINE__, __FILE__, $sql);
			};
		};

	if ( $guests_online == 0 ) {
		$l_g_user_s = $lang['Guest_users_zero_total'];
		}
	else if ( $guests_online == 1 ) {
		$l_g_user_s = $lang['Guest_user_total'];
		}
	else {
		$l_g_user_s = $lang['Guest_users_total'];
		};

	$l_online_users = sprintf($l_g_user_s, $guests_online);
}

//
// Obtain number of new private messages
// if user is logged in
//
if ( ($userdata['session_logged_in']) && (empty($gen_simple_header)) ) {
	if ( $userdata['user_new_privmsg'] ) {
		$l_message_new = ( $userdata['user_new_privmsg'] == 1 ) ? $lang['New_pm'] : $lang['New_pms'];
		$l_privmsgs_text = sprintf($l_message_new, $userdata['user_new_privmsg']);

		if ( $userdata['user_last_privmsg'] > $userdata['user_lastvisit'] ) {
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_last_privmsg = " . $userdata['user_lastvisit'] . "
				WHERE user_id = " . $userdata['user_id'];
			if ( !$db->sql_query($sql) ) {
				message_die(GENERAL_ERROR, 'Could not update private message new/read time for user', '', __LINE__, __FILE__, $sql);
				};

			$s_privmsg_new = 1;
			$icon_pm = $images['pm_new_msg'];
			}
		else {
			$s_privmsg_new = 0;
			$icon_pm = $images['pm_new_msg'];
			};
		}
	else {
		$l_privmsgs_text = $lang['No_new_pm'];

		$s_privmsg_new = 0;
		$icon_pm = $images['pm_no_new_msg'];
		};

	if ( $userdata['user_unread_privmsg'] ) {
		$l_message_unread = ( $userdata['user_unread_privmsg'] == 1 ) ? $lang['Unread_pm'] : $lang['Unread_pms'];
		$l_privmsgs_text_unread = sprintf($l_message_unread, $userdata['user_unread_privmsg']);
		}
	else {
		$l_privmsgs_text_unread = $lang['No_unread_pm'];
		};
	}
else {
	$icon_pm = $images['pm_no_new_msg'];
	$l_privmsgs_text = $lang['Login_check_pm'];
	$l_privmsgs_text_unread = '';
	$s_privmsg_new = 0;
	};

//
// Generate HTML required for Mozilla Navigation bar
//
if (!isset($nav_links)) {
	$nav_links = array();
	};

$nav_links_html = '';
$nav_link_proto = '<link rel="%s" href="%s" title="%s" />' . "\n";
while( list($nav_item, $nav_array) = @each($nav_links) )
{
	if ( !empty($nav_array['url']) )
	{
		$nav_links_html .= sprintf($nav_link_proto, $nav_item, append_sid($nav_array['url']), $nav_array['title']);
	}
	else
	{
		// We have a nested array, used for items like <link rel='chapter'> that can occur more than once.
		while( list(,$nested_array) = each($nav_array) )
		{
			$nav_links_html .= sprintf($nav_link_proto, $nav_item, $nested_array['url'], $nested_array['title']);
		}
	}
}

// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
$l_timezone = explode('.', $board_config['board_timezone']);
$l_timezone = (count($l_timezone) > 1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $board_config['board_timezone'])] : $lang[number_format($board_config['board_timezone'])];

/*
$template->set_filenames(array(
	'header_special' => ( empty($gen_simple_header) ) ? 'header_sitelike.tpl' : 'header_pbblike.tpl')
);
*/

//=========================================================================================================================
// Модуль админской части.
// 
// переключатель шаблона страницы админ/не админ
if ( $adm and $userdata['user_level'] >0)
{
	$template->assign_block_vars('admin_body_header', array());

	// Статьи
	if ($article_prop) {
		$template->assign_block_vars('admin_body_header.switch_article_prop_menu', array());
		// меню включить/выключить галерею
		if ($smGal_status) {
			$template->assign_block_vars('admin_body_header.switch_article_prop_menu.switch_smgal_off', array());
		}
		else {
			$template->assign_block_vars('admin_body_header.switch_article_prop_menu.switch_smgal_on', array());
		};

		if ($smVideoGal_status) {
			$template->assign_block_vars('admin_body_header.switch_article_prop_menu.switch_smvideogal_off', array());
		}
		else {
			$template->assign_block_vars('admin_body_header.switch_article_prop_menu.switch_smvideogal_on', array());
		};
		$template->assign_vars(array(
			'GALLERY_ONOFF' => $smgal_onoff,
			'VGALLERY_ONOFF' => $smVideoGal_onoff,
			'FORM_ONOFF' => $page_form_onoff,
			'ARTICLE_PATH' => $article_path
			));
		$template->assign_vars(array(
			'GALLERY_ONOFF' => $smgal_onoff,
			'FORM_ONOFF' => $page_form_onoff,
			'ARTICLE_PATH' => $article_path
			));
	};

	if ($event_prop) {
		// для календарика событий кажись...
		$template->assign_block_vars('admin_body_header.switch_event_prop_menu', array());
		// меню включить/выключить галерею
		if ($smGal_status) {
			$template->assign_block_vars('admin_body_header.switch_event_prop_menu.switch_smgal_off', array());
		}
		else {
			$template->assign_block_vars('admin_body_header.switch_event_prop_menu.switch_smgal_on', array());
		};

		if ($smVideoGal_status) {
			$template->assign_block_vars('admin_body_header.switch_event_prop_menu.switch_smvideogal_off', array());
		}
		else {
			$template->assign_block_vars('admin_body_header.switch_event_prop_menu.switch_smvideogal_on', array());
		};

		$template->assign_vars(array(
			'GALLERY_ONOFF' => $smgal_onoff,
			'VGALLERY_ONOFF' => $smVideoGal_onoff,
			'FORM_ONOFF' => $page_form_onoff,
			'ARTICLE_PATH' => $article_path
			));
	};

	if (@$site_prop) {
		$template->assign_block_vars('admin_body_header.siteadmin_left_menu', array());
		$template->assign_vars(array(
			));
	};

}
else {
	$template->assign_block_vars('body_header', array());
}

$search_text_default = $search_text;
$search_text = ($_GET["searchid"]) ? $_GET["text"] : $search_text;

// подмена картинок в дизайне в зависимости от погоды (значения см в gis-weather.php)
$weather_type = explode(";", $board_config["weather"]);
$weather_id = ($weather_type[1]) ? $weather_type[1] : "0";

//========================================================================================================================

include $DRoot . '/includes/incl_menu.php';
buildmenu(0, 0); // top menu
buildmenu(0, 1); // left menu

//
// The following assigns all _common_ variables that may be used at any point
// in a template.
//
/*
if ($userdata['user_level'] >0) {
	$template->assign_vars(array(
		'BLOCK_LEFT_1_EDIT' => $blocks_data["left"][1]["edit"],
		'BLOCK_TOP_1_EDIT' => $blocks_data["top"][1]["edit"],
		'BLOCK_TOP_2_EDIT' => $blocks_data["top"][2]["edit"],
	));
};
*/
$template->assign_vars(array(
	'PAGE_TITLE' => $page_title,
	'PAGE_ID' => $page_id,
	'CLASSIFICATION' => $page_classification,
	'PAGE_DESC' => $page_desc,
	'PAGE_KEYWORDS' => $page_keywords,
	'S_CONTENT_DIRECTION' => $page_content_direction,
	'SITENAME' => $board_config['sitename'],
	'SITE_DESCRIPTION' => $board_config['site_desc'],
	
	'TEMPLATE_NAME' => $theme["template_name"],

	'CURRENT_PARAGRAF_NAME' => $page_paragraf_name,
	'CURRENT_PARAGRAF_NAME_TEXT' => $page_paragraf_name_text,
	'CURRENT_PARAGRAF_PATH' => $page_paragraf_path,
	'CURRENT_PARAGRAF_ID' => $page_paragraf_id,

	'PRIVATE_MESSAGE_INFO' => $l_privmsgs_text,
	'PRIVATE_MESSAGE_INFO_UNREAD' => $l_privmsgs_text_unread,
	'PRIVATE_MESSAGE_NEW_FLAG' => $s_privmsg_new,

//	'BLOCK_LEFT_1' => $blocks_data["left"][1]["text"],
//	'BLOCK_TOP_1' => $blocks_data["top"][1]["text"],
//	'BLOCK_TOP_2' => $blocks_data["top"][2]["text"],

	'WEATHER_ID' => $weather_id,

//	'TOP_MENU' => buildtopmenu(0, ""),
//	'TOP_MENU' => buildtopmenuonelvl(0, ""),
	
	'EDIT' => @$add_news . @$edit,

	'L_USERNAME' => $lang['Username'],
	'L_PASSWORD' => $lang['Password'],
	'L_LOGIN_LOGOUT' => $l_login_logout,
	'L_LOGIN' => $lang['Login'],
	'L_LOG_ME_IN' => $lang['Log_me_in'],
	'L_AUTO_LOGIN' => $lang['Log_me_in'],
	'L_INDEX' => $board_config['sitename'],
	'L_REGISTER' => $lang['Register'],
	'L_PROFILE' => $lang['Profile'],
	'L_PRIVATEMSGS' => $lang['Private_Messages'],
	'L_MEMBERLIST' => $lang['Memberlist'],
	'L_SEARCH_NEW' => $lang['Search_new'],
	'L_SEARCH' => $lang['Search'],

	'U_SEARCH_SELF' => append_sid('search.'.$phpEx.'?search_id=egosearch'),
	'U_SEARCH_NEW' => append_sid('search.'.$phpEx.'?search_id=newposts'),
	'U_INDEX' => append_sid('index.'.$phpEx),
	'U_REGISTER' => append_sid('/userp.'.$phpEx.'?mode=register'),
	'U_PROFILE' => append_sid('/userp.'.$phpEx.'?mode=editprofile'),
	'U_PRIVATEMSGS' => append_sid('privmsg.'.$phpEx.'?folder=inbox'),
	'U_PRIVATEMSGS_POPUP' => append_sid('privmsg.'.$phpEx.'?mode=newpm'),
	'U_SEARCH' => append_sid('search.'.$phpEx),
	'U_MEMBERLIST' => append_sid('memberlist.'.$phpEx),
	'U_FAQ' => append_sid('faq.'.$phpEx),
	'U_LOGIN_LOGOUT' => append_sid($u_login_logout),

	'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'S_CONTENT_DIR_LEFT' => $lang['LEFT'],
	'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'],
	'S_TIMEZONE' => sprintf($lang['All_times'], $l_timezone),
	'S_LOGIN_ACTION' => append_sid('/login.'.$phpEx),
	'SEARCH_TEXT' => $search_text,
	'SEARCH_TEXT_DEFAULT' => $search_text_default,

	'NAV_LINKS' => $nav_links_html,
	
//	'LEFT_MENU' => mod_article_list('menu_link')
	));

if ($page_type <> 'index' and !$adm) {
	$template->assign_block_vars('page_header', array(
		'PARAGRAF_NAME' => $page_paragraf_name,
		'PARAGRAF_NAME_PATH' => $paragraf_title_patch
	));
};

//
// Login box?
//
if ( !$userdata['session_logged_in'] )
{
	$template->assign_block_vars('switch_user_logged_out', array());
}
else
{
	$template->assign_block_vars('switch_user_logged_in', array());

	if ( !empty($userdata['user_popup_pm']) )
	{
		$template->assign_block_vars('switch_enable_pm_popup', array());
	}
}

// Для редактирования страницы добавляем доп элементы (стили и пр.)
if(@$_GET['edit'] or @$_GET['add']) {
	$template->assign_block_vars('switch_edit', array());
};

// модуль day_events.php
include $DRoot . "/mod/day_events.php";

// модуль церковного календаря
if (@$mod_cruch_calendar) {
	$cruch_calendar_action = "show";
	include $DRoot . "/mod/cruch_calendar.php";
};

// Add no-cache control for cookies if they are set
//$c_no_cache = (isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid']) || isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_data'])) ? 'no-cache="set-cookie", ' : '';


//
// Блоки вставляем в страницы.
//
//var_dump($blocks_xdata);
//echo $mod_blocks;
if ($mod_blocks) {
	$i = 0;
	while ($blocks_xdata[$i]["id"]) {
		$template->assign_vars(array(
			'BLOCK_'.$blocks_xdata[$i]["id"] => $blocks_xdata[$i]["text"],
		));
		
		if ($userdata['user_level'] >0) {
			$template->assign_vars(array(
				'BLOCK_'.$blocks_xdata[$i]["id"].'_EDIT' => $blocks_xdata[$i]["edit"],
			));
		};
		
		$i++;
	};
};

// Work around for "current" Apache 2 + PHP module which seems to not
// cope with private cache control setting
if (!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2')) {
	header ('Cache-Control: no-cache, pre-check=0, post-check=0');
	}
else {
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
	};
header ('Expires: 0');
header ('Pragma: no-cache');

$template->pparse('overall_header');
//$template->pparse('header_special');

?>