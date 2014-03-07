<?php
/***************************************************************************
 *                                article.php
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
// Обрезаем ID статей, языка и.т.п  для борьбы и инжекшенами
$get_news  = substr($_GET['id'],0, 11);
$get_lang  = substr($_GET['lang'],0, 3);
$remove_id = substr($_GET['remove'],0, 11);
$source_id = substr($_GET['source'],0, 11);
$img_url = substr($_POST['img_url'],0, 300);


// ищем ID раздела странички если не указан файл. если путь в каком-то месте неверный - возвращаем ошибку
function get_paragraf_id($paragraf_name, $pid) {
	global $paragraf_id, $current_paragraf_id_list;
//	echo "sdfsdfsdf";
//	echo strpos($get_article, "/");
//print_r($paragraf_id);
	if (substr($paragraf_name, (strlen($paragraf_name)-1))=="/" ) {
		$current_paragraf_name = substr($paragraf_name, 0, (strpos($paragraf_name, "/")));
		$current_paragraf_id = $paragraf_id[$pid][$current_paragraf_name]['menu_id'];
		$current_paragraf_id_list[] = $current_paragraf_id;
		$sub_paragraf_name = substr($paragraf_name, (strpos($paragraf_name, "/"))+1);
		if (strlen($sub_paragraf_name)) {
 			$ret = get_paragraf_id($sub_paragraf_name, $current_paragraf_id);
			}
		else {
			$ret = ($current_paragraf_id) ? $current_paragraf_id : -1;	// если путь в каком-то месте неверный или ведет в никуда - возвращаем ошибку (-1) и далее ее обработаем
		}; 
	};
 	return $ret;
};

// описание-коментарий новостного раздела.
$sql = 'SELECT * FROM `' . TABLE_NEWS .'` WHERE `news_id` < "0" AND `news_source_id` = "'.$source_id.'";';
if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База новостей отсутствует', '', __LINE__, __FILE__, $sql);
};
$news_desc = $db->sql_fetchrow($result);

if ($userdata['user_level'] >0) {
	$edit_paragraf = '&nbsp;&nbsp;<a href="/news.php?id='.$news_desc["news_id"] . '&source='.$source_id.'&edit=1" title="'.$lang['Edit_post'].' описание раздела">'.
			'<img src="/pic/ico/edit.gif" alt="'.$lang['Edit_post'].'" width="16" height="16" border="0"></a>';
};


if ($userdata['user_level'] >0) {
	$add_news = '<div style="position:relative; width:100%" align="right"><a href="/news.php?add=1&source='.$source_id.'" title="'.$lang['Add'].'"><img src="/pic/ico/page_add.gif" alt="'.$lang['Add'].'" width="16" height="16" border="0"></a>'.$edit_paragraf.'</div>';
};


// "Удаляем" статью, а точнее помечаем ее как удаленную.
if ($remove_id and ($userdata['user_level'] >0)) {
	$sql = "UPDATE `" . TABLE_NEWS . "` SET 
	`news_active` = '0'
	 WHERE `news_id` = '" . $remove_id ."'";

if ($db->sql_query($sql)) {
	$saved = '<strong>' . $lang['Saved'] . '</strong>';
	}
	else {
	message_die(GENERAL_ERROR, 'Ошибка добавления статьи', '', __LINE__, __FILE__, $sql);
	};

};

//
// Сохраняем/добавляем отредактированную статью
//
if (@$_POST['save'] and $userdata['user_level'] >0) {
	// дата
	// переводим дату в юникс формат
	$news_date = substr($_POST['page_date'], 0 , strpos($_POST['page_date'], " "));
	$news_date = substr($news_date, 0 , 10);
	$news_time = substr($_POST['page_date'], (strpos($_POST['page_date'], " ")+1), 16);
	$news_time = substr($news_time, 0 , 5);
	$news_video = str_replace('"', '\"', str_replace("'", "\'", $_POST['page_video']));
//echo $_POST['page_video'] . "\n\r";
	
	$news_date_arr = explode(".", $news_date);
	$news_time_arr = explode(":", $news_time);
	$news_date = mktime($news_time_arr[0], $news_time_arr[1], 0, $news_date_arr[1], $news_date_arr[0], $news_date_arr[2]);

	if (file_exists($DRoot.$img_url) and ($DRoot.$img_url<>$DRoot)) {
		$img_size = getimagesize($DRoot.$img_url);
		$img_size_sql = $img_size[0] . ";" . $img_size[1];
		$img_update = ($_POST["delete_img"]) ? " `news_img` = '', `news_img_size` = '', " : " `news_img` = '" . $img_url . "', `news_img_size` = '" . $img_size_sql . "', ";
	}
	elseif ($_POST["delete_img"]) {
		$img_url = "";
		$img_size_sql = "";
		$img_update =  " `news_img` = '', `news_img_size` = '', ";
	}
	else {
		$img_url = "";
		$img_size_sql = "";
		$img_update = "";
	};

	$post_text = str_encode_char($_POST['page_text']);
	$post_title = str_encode_char(substr($_POST['page_title'],0, 255));
	$post_title = (trim(strip_tags($post_title), " \t.\n\r\0\x0B")) ? $post_title : "Без названия" .$post_title;
	$post_source_name = substr($_POST["page_source_name"],0,255);
	$post_id = substr($_POST['article_id'],0, 11);
	
	
	if (@$_POST['new']) {
		$sql = "INSERT INTO `" . TABLE_NEWS . "` (`news_date` , `news_source_id`, `news_name` , `path_name` , `news_text`, `news_source`, `news_img`, `news_img_size`, `news_video`)
			VALUES ('".$news_date."', '".$source_id."', '".$post_title."', '', '".$post_text."', '".$post_source_name."', '".$img_url."', '".$img_size_sql."', '".$news_video."')";
	}
	elseif ($_POST['save']) {
		$sql = 
		"UPDATE `" . TABLE_NEWS . "` SET 
		`news_name` = '".$post_title."', 
		`news_date` = '".$news_date."',
		".$img_update."
		`path_name` = '', 
		`news_text` = '".$post_text."',
		`news_source` = '".$post_source_name."',
		`news_video` = '".$news_video."' 
		 WHERE `news_id` = '" . $post_id ."'";
	};
	if ($db->sql_query($sql)) {
		$saved = '<strong>' . $lang['Saved'] . '</strong>';
	}
	else {
		message_die(GENERAL_ERROR, 'Ошибка сохранения статьи', '', __LINE__, __FILE__, $sql);
	};
};

if (!@$_GET['add']) {
	$news_id = "";
	if (@$get_news) {
		$news_id = ' `news_id` = "' . $get_news . '" AND ';
	};
	
	if ($get_news < 0) {
		$sql = 'SELECT * FROM `' . TABLE_NEWS .'` WHERE '.$news_id . ' `news_source_id` = "'.$source_id.'" ORDER BY `news_date` DESC LIMIT 0,30';
	}
	else {
		$sql = 'SELECT * FROM `' . TABLE_NEWS .'` WHERE '. $news_id . ' `news_active` = "1" AND `news_source_id` = "'.$source_id.'" ORDER BY `news_date` DESC LIMIT 0,30';
	};
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База новостей отсутствует', '', __LINE__, __FILE__, $sql);
		};


	$news = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$news[] = $row;
		};

	if (!$news[0]["news_id"]) {
		message_die(GENERAL_ERROR, 'Запрошенная новость не найдена ' . @$add_news, '', __LINE__, __FILE__, $sql);
		};
};

$page_paragraf_path = "news_s" . $source_id;

//
// Start output of page
//
define('SHOW_ONLINE', true);

$get_paragraf_name = substr($_GET["menuname"],0,30);
$current_paragraf_id = get_paragraf_id($get_paragraf_name . "/", 0);


if (@!$_GET['add']) {
	$page_title = ($news[0]["news_text"]) ? $news[0]["news_name"] : $news_desc["news_name"];
	$page_date = create_date($board_config['edit_dateformat'], $news[0]["news_date"], $board_config['board_timezone']);
	$page_date_short = create_date($board_config['news_dateformat_short'], $news[0]["news_date"], $board_config['board_timezone']);
	$page_unix_date = $news[0]["news_date"];
	$page_desc = ($news[0]["news_text"]) ? $news_desc["news_name"] . ' - ' . $news[0]["news_name"] : $news_desc["news_name"];;
	$page_keywords = $news_desc["news_name"];
	$page_text = $news[0]["news_text"];
	$page_path = $news[0]["path_name"];
	$page_id = $news[0]["news_id"];
	$page_paragraf = "";
	$page_paragraf_name = $topmenu_data[$current_paragraf_id]['menu_name'];
	$page_paragraf_name_text = $topmenu_data[$current_paragraf_id]['menu_name'];
	$page_patch_desc = "";
	$page_classification = "";
	$page_content_redirection = "";
	$page_img = $news[0]["news_img"];
	$page_video = str_replace('"', '&quot;', $news[0]["news_video"]);
	
	$page_photo_size = explode(";", $news[0]["news_img_size"]);
	$page_photo = ($news[0]["news_img"]) ? '<img src="'.$news[0]["news_img"].'" height="'.$page_photo_size[1].'" width="'.$page_photo_size[0].'" alt="" />' : "";
//	$page_photo_exist = '<input type="hidden" name="page_photo_exist" value="1">';
	
	$page_source_name = $news[0]["news_source"];
}
else {
	$page_date = create_date($board_config['edit_dateformat'], time(), $board_config['board_timezone']);
};

include($DRoot . '/includes/page_header.'.$phpEx);

if (@$_GET['edit'] and $userdata['user_level'] >0) { // редактироване статьи
	$rows_edit = "20"; // кол-во строк в редакторе
	$submit_path = "/news.php?source=".$source_id."&id=". $news[0]["news_id"];
	$news_source_id = $source_id;
	$source_id = "news";
	include $DRoot . "/includes/edit.php";
	$template->assign_block_vars('switch_property_edit', array());
	$template->assign_block_vars('switch_news_edit', array());
	}
elseif (@$_GET['add'] and $userdata['user_level'] >0) { // редактироване статьи
	$rows_edit = "20"; // кол-во строк в редакторе
	$submit_path = "/news.php?source=".$source_id;
	$news_source_id = $source_id;
	$source_id = "news";
	include $DRoot . "/includes/edit.php";
	$template->assign_block_vars('switch_property_edit', array());
	$template->assign_block_vars('switch_news_edit', array());
	}
else {
	$template->set_filenames(array(
		'body' => 'news_body.tpl')
	);

	$template->assign_vars(array(
//		'SAVED' => "<br>" . @$saved,
		'PATCH_DESCRIPTION' => $paragrad_desc,
//		'ADD_NEWS' => @$add_news,
//		'EDIT' => @$edit
		));
	

	if ($get_news) {
		if ($userdata['user_level'] >0) {
			$edit = '<br><a href="/news.php?id='.$news[0]["news_id"] . '&source='.$source_id.'&edit=1" title="'.$lang['Edit_post'].'">'.
					'<img src="/pic/ico/edit.gif" alt="'.$lang['Edit_post'].'" width="16" height="16" border="0"></a> '.
					'<a href="/news.php?remove='.$news[0]["news_id"].'&source='.$source_id.'" title="'.$lang['Delete'].'">'.
					'<img src="/pic/ico/delete.gif" alt="'.$lang['Delete'].'" width="16" height="16" border="0" border="0" /></a>';
		};

		$page_photo = ($news[0]["news_img"]) ? '<img src="'.$news[0]["news_img"].'" height="'.$page_photo_size[1].'" width="'.$page_photo_size[0].'" alt="" hspace="10"  align="left" />' : "";
		$template->assign_block_vars('show_news_source_'.$source_id, array(
			'NEWS_TITLE' => $news_link.$news[0]["news_name"] .'</a>',
			'NEWS_DATE' => create_date($board_config['news_dateformat'], $news[0]["news_date"], $board_config['board_timezone']),
			'NEWS_TIME' => create_date("G:i", $news[0]["news_date"], $board_config['board_timezone']),
			'NEWS_TEXT' => $news_text,
			'NEWS_SOURCE' => $news[0]["news_source"],
			'NEWS_IMG' => $page_photo,
			'NEWS_FULL' => $news_full,
			'NEWS_TEXT' => $page_text, 
			'NEWS_VIDEO' => $news[0]["news_video"],
			'EDIT' => @$edit
			));
	}
	else {
		$template->assign_block_vars('news_source_'.$source_id, array(
			'NEWS_DESC' => $news_desc["news_text"]
		));
	
		$i = 0;
		while ($news[$i]["news_id"]) {

			if ($userdata['user_level'] >0) {
				$edit = '<br><a href="/news.php?id='.$news[$i]["news_id"] . '&source='.$source_id.'&edit=1" title="'.$lang['Edit_post'].'">'.
							'<img src="/pic/ico/edit.gif" alt="'.$lang['Edit_post'].'" width="16" height="16" border="0"></a> '.
							'<a href="/news.php?remove='.$news[$i]["news_id"].'&source='.$source_id.'" title="'.$lang['Delete'].'">'.
							'<img src="/pic/ico/delete.gif" alt="'.$lang['Delete'].'" width="16" height="16" border="0" border="0" /></a>';
			};

			$news_text_short = substr($news[$i]["news_text"], 0, 400);
			$news_text = (strlen($news[$i]["news_text"])<300) ? $news[$i]["news_text"] : substr($news_text_short, 0, strrpos($news_text_short, " "));
			$news_text = strip_tags($news_text, '<a><br>');
			$news_img_size = explode(';', $news[$i]["news_img_size"]);
			$news_img = '<img src="'.$news[$i]["news_img"].'" alt="" width="'.$news_img_size[0].'" height="'.$news_img_size[1].'" border="0" />';
			$news_link = '<a href="/ru/news/'.$news[$i]["news_id"].'">';
			$news_full = (strlen($news[$i]["news_text"]) > strlen($news_text_short)) ? '&nbsp;'.$news_link.'далее...</a>' : "";

			$template->assign_block_vars('news_source_'.$source_id.'.newsrow', array(
				'NEWS_ID' => $news[$i]["news_id"],
				'NEWS_TITLE' => $news[$i]["news_name"],
				'NEWS_DATE' => create_date($board_config['news_dateformat'], $news[$i]["news_date"], $board_config['board_timezone']),
				'NEWS_TIME' => create_date("G:i", $news[0]["news_date"], $board_config['board_timezone']),
				'NEWS_TEXT' => $news_text,
				'NEWS_SOURCE' => $news[$i]["news_source"],
				'NEWS_IMG' => $news_img,
				'NEWS_FULL' => $news_full,
				'NEWS_VIDEO' => $news[$i]["news_video"],
				'EDIT' => @$edit
			));
			
			if ($news_full) {
				$template->assign_block_vars('news_source_'.$source_id.'.newsrow.newsfull', array());
			};
			
			$i++;
		};
	};
};


//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>