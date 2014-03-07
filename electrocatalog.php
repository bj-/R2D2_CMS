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
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами


if ($userdata['user_level'] >0) {
	$edit = '<a href="/admin/article_edit.php?add=1" title="Создать новую статью"><img src="/pic/ico/page_add.gif" alt="Создать новую статью" width="16" height="16" border="0"></a> ';
	$edit .= ($article_data[0]["article_id"] and $article_data[0]["article_edit_type"]<9) ? '<a href="/admin/article_edit.php?id='.$article_data[0]["article_id"].'&edit=1" title="'.$lang['Edit_post'].'"><img src="/pic/ico/edit.gif" alt="'.$lang['Edit_post'].'" width="16" height="16" border="0"></a> ' : "";
	$edit .= ($article_data[0]["article_id"]) ? '<a href="/admin/article_prop.php?&id='.$article_data[0]["article_id"].'" title="Настройки статьи"><img src="/pic/ico/document-properties.png" alt="Настройки статьи" width="16" height="16" border="0"></a> ' : "";
};

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Каталог электродеталей";
$page_classification = "";
$page_desc = "";
$page_keywords = "";


include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'electrocatalog_body.tpl')
);


// переключалка показываемых элементов каталога
if (TRUE) {
	$template->assign_block_vars('switch_diodes', array());
	$template->assign_block_vars('switch_led', array());
}

// генерация страницы
$template->assign_vars(array(
	'ARTICLE' => @$page_text,
	'EDIT' => @$edit
	));



//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>