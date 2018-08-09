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


//
// Define some basic configuration arrays this also prevents
// malicious rewriting of language and otherarray values via
// URI params
//
//$board_config = array();
//$userdata = array();
$theme = array();
$images = array();
$lang = array();
$gen_simple_header = FALSE;


include($DRoot . '/includes/template.'.$phpEx);
include($DRoot . '/includes/functions.'.$phpEx);

$theme = setup_style();


// used


//unk
/*
$board_config["default_style"] = "5";
$board_config["config_id"] = "1";
$board_config["board_disable"] = "0";
$board_config["sitename"] = "shturman-tracker.systech.local";
$board_config["site_desc"] = "Комфортная срана";
$board_config["cookie_name"] = "r2d2mysql";
$board_config["cookie_path"] = "/";
$board_config["cookie_domain"] = "";
$board_config["cookie_secure"] = "0";
$board_config["session_length"] = "3600";
$board_config["allow_html"] = "0";
$board_config["allow_html_tags"] = "b,i,u,pre,a";
$board_config["allow_bbcode"] = "1";
$board_config["allow_smilies"] = "1";
$board_config["allow_sig"] = "1";
$board_config["allow_namechange"] = "0";
$board_config["allow_theme_create"] = "0";
$board_config["allow_avatar_local"] = "1";
$board_config["allow_avatar_remote"] = "0";
$board_config["allow_avatar_upload"] = "1";
$board_config["enable_confirm"] = "0";
$board_config["override_user_style"] = "0";
$board_config["posts_per_page"] = "30";
$board_config["topics_per_page"] = "50";
$board_config["hot_threshold"] = "50";
$board_config["max_poll_options"] = "15";
$board_config["max_sig_chars"] = "255";
$board_config["max_inbox_privmsgs"] = "50";
$board_config["max_sentbox_privmsgs"] = "25";
$board_config["max_savebox_privmsgs"] = "50";
$board_config["board_email_sig"] = "Thanks, The Management";
$board_config["board_email"] = "bmw518@bk.ru";
$board_config["smtp_delivery"] = "0";
$board_config["smtp_host"] = "";
$board_config["smtp_username"] = "";
$board_config["smtp_password"] = "";
$board_config["sendmail_fix"] = "1";
$board_config["require_activation"] = "1";
$board_config["flood_interval"] = "15";
$board_config["board_email_form"] = "0";
$board_config["avatar_filesize"] = "8192";
$board_config["avatar_max_width"] = "120";
$board_config["avatar_max_height"] = "100";
$board_config["avatar_path"] = "img/avatars";
$board_config["avatar_gallery_path"] = "img/avatars/gallery";
$board_config["smilies_path"] = "img/smiles";
$board_config["default_dateformat"] = "d.m.Y G:i";
$board_config["board_timezone"] = "3";
$board_config["prune_enable"] = "0";
$board_config["privmsg_disable"] = "0";
$board_config["gzip_compress"] = "0";
$board_config["coppa_fax"] = "";
$board_config["coppa_mail"] = "";
$board_config["record_online_users"] = "46";
$board_config["record_online_date"] = "1134972062";
$board_config["server_name"] = "shturman-tracker.systech.local";
$board_config["server_port"] = "80";
$board_config["script_path"] = "/";
$board_config["version"] = ".0.6";
$board_config["board_startdate"] = "1078222456";
$board_config["default_lang"] = "1";
$board_config["forum_view"] = "1,2,7,4";
$board_config["news_dateformat"] = "d.m";
$board_config["edit_dateformat"] = "d.m.Y H:i";
$board_config["article_dateformat"] = "d.m.Y";
$board_config["weather"] = "3;0";
$board_config["menu_group_active"] = "0,1";

*/

?>