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
include("includes/config.php");
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);



//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "It works!";

$template->set_filenames(array(
	'body' => 'index_body.tpl')
);

$template->assign_vars(array(
	'ARTICLE' => $page_text,
	'TITLE' => $page_title
	));
//
// Generate the page
//
$template->pparse('body');

?>