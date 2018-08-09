<?php
/***************************************************************************
 *                                article.php
 *                            -------------------
 *   begin                : Jun 13, 2010
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: article.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   License
 *
 ***************************************************************************/
$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));


define('IN_R2D2', true);
include("includes/_xconfig.php");
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/_xcommon.php');


//
// Start session management
//
$userdata = array();

init_userprefs($userdata);

//echo "<pre>";
//var_dump($userdata);
//echo "</pre>";



//
// Start output of page
//
define('SHOW_ONLINE', true);


$template->set_filenames(array(
	'body' => 'article_body.tpl')
);
$template->assign_block_vars('switch_left_menu', array());

$template->assign_vars(array(
	'SAVED' => 'saved',
	'ARTICLE' => 'pageText5'. date("h-m-s")
	));



$template->assign_block_vars('submenu.submenu_list', array(
	'MENU_NAME' => 'aa',
	'MENU_PATH' => 'bb',
	'MENU_CLASS' => 'cc'
));





//
// Generate the page
//
$template->pparse('body');

//include($DRoot . '/includes/page_tail.'.$phpEx);

?>