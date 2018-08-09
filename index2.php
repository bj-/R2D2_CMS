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

// задаем тип страницы для разных сценраев скриптов/шаблонов
$page_type = 'index';

//
// собираем страницу со статьей
//
$get_article = $_GET['article'];
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами

// ищем ID раздела странички если не указан файл. если путь в каком-то месте неверный - возвращаем ошибку
function get_paragraf_id($paragraf_name, $pid) {
	global $paragraf_id, $current_paragraf_id_list;
//	echo strpos($get_article, "/");
	if (substr($paragraf_name, (strlen($paragraf_name)-1))=="/" ) {
		$current_paragraf_name = substr($paragraf_name, 0, (strpos($paragraf_name, "/")));
 		$current_paragraf_id = $paragraf_id[$pid][$current_paragraf_name]['menu_id'];
		$current_paragraf_id_list[] = $current_paragraf_id;
		$sub_paragraf_name = substr($paragraf_name, (strpos($paragraf_name, "/"))+1);
		if (strlen($sub_paragraf_name)) {
 			$ret = get_paragraf_id($sub_paragraf_name, $current_paragraf_id);
			}
		Else {
			$ret = ($current_paragraf_id) ? $current_paragraf_id : -1;	// если путь в каком-то месте неверный или ведет в никуда - возвращаем ошибку (-1) и далее ее обработаем
		}; 
	};
 	return $ret;
};

// парсим путь
$article_name = substr($get_article, strrpos($get_article, "/")+1);
if ($article_name) {
	$current_article_id = substr($article_name, strrpos($article_name, "-")+1);
	$current_article_id = substr($current_article_id, 0, strpos($current_article_id, "."));
	$current_article_id = substr($current_article_id,0, 11); // обрезаем id статьи до 11 символов для борьбы и инжекшенами
	}
Else {
	$current_paragraf_id = get_paragraf_id($get_article, 0);
	$current_article_id = false; //$get_article;
};


// Берем статью из БД
$sql = "SELECT * FROM `" . 
TABLE_ARTICLE . '` WHERE `paragraf_id`="0" and `primary_article`="1" and `article_lang` = "'.$user_lang.'"';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$article_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$article_data[] = $row;
	};

if (!$article_data[0]["article_id"]) {
	$no_article = TRUE;
//	message_die(GENERAL_ERROR, 'Запрошенная статья не найдена ' . $add, '', __LINE__, __FILE__, $sql);
}
Else {
	$current_paragraf_id = $article_data[0]["paragraf_id"];
};

//Формирование пути раздела данной статьи
$i = 0;
$paragrad_desc = "";
$url = "";
while (@$current_paragraf_id_list[$i]) {
	$url = $url_lang."/".get_full_url($current_paragraf_id_list[$i]);
	$paragrad_desc .= '<a href="/'.$url.'/">'. $topmenu_data[$current_paragraf_id_list[$i]]['menu_name'] .'</a>';
	if (@$current_paragraf_id_list[$i+1]) { // добавляем >> между ссылками
		$paragrad_desc .= ' >> ';
	};

	$i++;
};

if ($userdata['user_level'] >0) {
	$edit = '<a href="/admin/article_edit.php?add=1" title="Создать новую статью"><img src="/pic/ico/page_add.gif" alt="Создать новую статью" width="16" height="16" border="0"></a> ';
	$edit .= ($article_data[0]["article_id"]) ? '<a href="/admin/article_edit.php?id='.$article_data[0]["article_id"].'&edit=1" title="'.$lang['Edit_post'].'"><img src="/pic/ico/edit.gif" alt="'.$lang['Edit_post'].'" width="16" height="16" border="0"></a> ' : "";
	$edit .= ($article_data[0]["article_id"]) ? '<a href="/admin/article_prop.php?&id='.$article_data[0]["article_id"].'" title="Настройки статьи статьи"><img src="/pic/ico/document-properties.png" alt="Настройки статьи" width="16" height="16" border="0"></a> ' : "";
};

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = (@$no_article) ? "Статья не найдена" : $article_data[0]["article_title"];
$page_classification = $article_data[0]["article_classification"];
$page_desc = $article_data[0]["article_desc"];
$page_keywords = $article_data[0]["article_keywords"];
$page_content_direction = "";

$submit_path = "/article.php?article=".$article_data[0]["article_id"];
$page_id = $article_data[0]["article_id"];
$page_unix_date = $article_data[0]["article_date"];
$page_date = create_date($board_config['article_dateformat'], $article_data[0]["article_date"], $board_config['board_timezone']);
$check_primary_article = ($article_data[0]["primary_article"]) ? " checked" : "";

$page_form_email = $article_data[0]["form_email"];
$page_form_subj = $article_data[0]["form_subject"];

$page_path = $article_data[0]["article_name"];
$page_text = (@$no_article) ? "<p><h2>Запрошенная статья не найдена</h2></p><p>Жалоба администратору сайта уже написана автоматически, спасибо за помощь.</p>" : $article_data[0]["article_text"];

$smGal_status = $article_data[0]["article_sgal_on"];

echo "AAAAAAAAAAAAA ывапрцурцук рукр уцк рцкур цук р";

if (@$no_article) {

};

include($DRoot . '/includes/page_header.'.$phpEx);

if (@$_GET['edit'] and $userdata['user_level'] >0) { // редактироване статьи
	$rows_edit = "40"; // кол-во строк в редакторе

	include $DRoot . "/includes/edit.php";

	$template->assign_block_vars('switch_property_edit', array());
	$template->assign_block_vars('switch_full_edit', array());
	$template->assign_block_vars('switch_article_edit', array());
	$template->assign_block_vars('switch_article_edit.switch_article_paragraf_edit', array());
	}
elseif (@$_GET['add'] and $userdata['user_level'] >0) { // редактироване статьи
	$rows_edit = "40"; // кол-во строк в редакторе
	$submit_path = "/article.php";
 	if ($_GET['adm']) {	$submit_path .= "?adm=1"; };
	include $DRoot . "/includes/edit.php";
	
	$template->assign_block_vars('switch_property_edit', array());
	$template->assign_block_vars('switch_full_edit', array());
	$template->assign_block_vars('switch_article_edit', array());
	$template->assign_block_vars('switch_article_edit.switch_article_paragraf_edit', array());
	
	}
Else {
	$template->set_filenames(array(
		'body' => 'index_body.tpl')
	);
	$template->assign_block_vars('switch_left_menu', array());

	if ($article_data[0]["form_email"]) {
		if ($_POST['send_mail']) {
			include $DRoot . "/includes/msend.php";
			}
		Else {
			$template->assign_block_vars('switch_form', array());
		};
	};

	$template->assign_vars(array(
		'SAVED' => @$saved,
		'PATCH_DESCRIPTION' => $paragrad_desc,
		'ARTICLE' => $page_text,
		'ARTICLE_ID' => $page_id,
		'EDIT' => @$edit
		));
};
//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>