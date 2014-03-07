<?php
/***************************************************************************
 *                                vgbase.php
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


include($DRoot . '/mod/vg/vg_config.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);
//
// End session management
//

//
// приводим в порядок входные переменные.
// 
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами

//
// собираем страницу со статьей
//

if ($userdata['user_level'] >0) {
	$edit = '';
};

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = (@$no_article) ? "Статья не найдена" : "База ID";
$page_classification = "";
$page_desc = "";
$page_keywords = "";
$page_content_direction = "";

$page_paragraf_id = $article_data[0]["paragraf_id"];
$page_paragraf_name = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_name'];
$page_paragraf_path = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_path'];

$submit_path = "";
$page_id = "";

$page_path = "";
$page_text = (@$no_article) ? "<p><h2>Запрошенная статья не найдена</h2></p><p>Жалоба администратору сайта уже написана автоматически, спасибо за помощь.</p>" : $article_data[0]["article_text"];

$g_member = ($id_row["gamers_member"]) ? $id_row["gamers_member"] : "враг";
$finded_planet = count($planet_data);
date_default_timezone_set("Europe/Moscow");

include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'vgbase.tpl')
);




$template->assign_vars(array(
//	'SAVED' => @$saved,
//	'PATCH_DESCRIPTION' => $paragrad_desc,
//	'ARTICLE' => $page_text,
	'ID' => $get_id,
	'SEL_ID' => @$sel_id,
	'SEL_ALL' => @$sel_all,
	'SEL_PLANET' => @$sel_planet
	
//	'EDIT' => @$edit
	));


//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>