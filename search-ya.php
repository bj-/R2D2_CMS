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
// собираем страницу со статьей
//
$get_article = $_GET['id'];
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами
$article_sgal = True;


//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Поиск";
$page_classification = "";
$page_desc = "Поиск по сайту";
$page_keywords = "поиск";
$page_content_direction = "";

$page_paragraf_id = "";
$page_paragraf_name = "";
$page_paragraf_path = "";

$submit_path = "";
$page_id = "";
$page_unix_date = "";
$page_date = "";

$page_path = "";
$page_text = '<div id="yandex-results-outer" onclick="return {encoding: \'\'}"></div><script type="text/javascript" src="http://site.yandex.net/load/site.js" charset="utf-8"></script>';


include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'article_body.tpl')
);
$template->assign_block_vars('switch_left_menu', array());


$template->assign_vars(array(
	'SAVED' => @$saved,
	'PATCH_DESCRIPTION' => $paragrad_desc,
	'ARTICLE' => $page_text,
	'ARTICLE_ID' => $page_id,
	'EDIT' => @$edit
	));





//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>