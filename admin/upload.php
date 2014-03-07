<?php
/***************************************************************************
 *                                admin index.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The R2D2 Group
 *
 *   $Id: article.php,v 1.99.2.1 2002/12/19 17:17:40 psotfx Exp $
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
$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));


define('IN_R2D2', true);
//echo  $_SERVER["DOCUMENT_ROOT"];
include($_SERVER["DOCUMENT_ROOT"] . "/includes/config.php");
//include($DRoot . '/db/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//



//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = $lang['Index'];
//include($DRoot . '/db/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'admin/upload_body.tpl')
);

if ($userdata['user_level'] >0)
{
	$template->assign_block_vars('switch_menu', array());

	if ($_GET['edit'] == "menu") {
		include $DRoot . "/admin/includes/menu.php";
	}
	ElseIf ($_GET['edit'] == "prop") {
		include $DRoot . "/admin/includes/siteconfig.php";
	}
	ElseIf ($_GET['edit'] == "blocks") {
		include $DRoot . "/admin/includes/blocks.php";
	}
	ElseIf ($_GET['edit'] == "articles") {
		include $DRoot . "/admin/includes/articles.php";
	}
	ElseIf ($_GET['edit'] == "gallery") {
		include $DRoot . "/admin/includes/gallery.php";
	}
	ElseIf ($_GET['edit'] == "photo") {
		include $DRoot . "/admin/includes/gallery_edit.php";
	};

};


$template->assign_vars(array(
	'TEXT' => $article_data[0]['article_text']
	));



//
// Generate the page
//
$template->pparse('body');

//include($DRoot . '/db/page_tail.'.$phpEx);

?>

