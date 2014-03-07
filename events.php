<?php
/***************************************************************************
 *                                events.php
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
include("db/config.php");
include($DRoot . '/db/extension.inc');
include($DRoot . '/db/common.'.$phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);
//
// End session management
//

//
// собираем страницу с событием
//
//echo$_GET["event_date"] . '<br>';
//echo "-".$_GET["show"] . "<br>";
//echo "=".$_SERVER["QUERY_STRING"] . "<br>";

$get_event_date = "";
if ($_GET["event_date"]) {
	$get_event_date = $_GET["event_date"];
	$show_photo = ($_GET['show'] == "photo") ? TRUE : FALSE;
	$show_video = ($_GET['show'] == "video") ? TRUE : FALSE;
	$event_photo = ($_GET['show'] == "photo") ? TRUE : FALSE;
	$event_video = ($_GET['show'] == "video") ? TRUE : FALSE;
	$event_details = (!$event_photo and !$event_video) ? TRUE : FALSE;
}
ElseIf ($_POST["event_date"]) {
	$get_event_date = $_POST["event_date"];
	$event_details = TRUE;
};
$get_event_date = substr($get_event_date, 0, 10); // обрезаем переменные
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами
$get_current_page = ($_GET['page']) ? substr($_GET['page'],0 ,4) : 1;
$get_show = substr($_GET['show'],0,11);

$current_event_url = "/".$get_lang."/events/".$get_event_date."/";
$current_event_gal_url = "/".$get_lang."/events/".$get_event_date."/".$get_show."/";

// Берем событие из БД
if ($get_event_date){
	$sql = "SELECT * FROM `" . 
	TABLE_EVENTS . '` WHERE event_ddate="'.$get_event_date.'" ORDER BY event_date DESC;';
}
Else {
	$sql = "SELECT * FROM `" . 
	TABLE_EVENTS . '` ORDER BY event_date DESC;';
};

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'Ошибка запроса из таблицы календаря событий', '', __LINE__, __FILE__, $sql);
	};


$event_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$event_data[] = $row;
	};
if (!$event_data[0]["event_id"]) {
	$no_event = TRUE;
//	message_die(GENERAL_ERROR, 'Запрошенная статья не найдена ' . $add, '', __LINE__, __FILE__, $sql);
};



if ($userdata['user_level'] >0) {
	$edit = '<a href="/admin/events_edit.php?add=1" title="Создать новую"><img src="/pic/ico/page_add.gif" alt="Создать новую" width="16" height="16" border="0"></a> ';
	$edit .= ($event_data[0]["event_id"]) ? '<a href="/admin/events_edit.php?id='.$event_data[0]["event_id"].'&edit=1" title="'.$lang['Edit_post'].'"><img src="/pic/ico/edit.gif" alt="'.$lang['Edit_post'].'" width="16" height="16" border="0" alt="'.$lang['Edit_post'].'"></a> ' : "";
	$edit .= ($event_data[0]["event_id"]) ? '<a href="/admin/events_prop.php?&id='.$event_data[0]["event_id"].'" title="Настройки"><img src="/pic/ico/document-properties.png" alt="Настройки" width="16" height="16" border="0"></a> ' : "";
};

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = (@$no_event) ? "Событые не найдено" : $event_data[0]["event_name"];
$page_classification = "";
$page_desc = "";
$page_keywords = "";
$page_content_direction = "";

$page_paragraf_id = "";
$page_paragraf_name = "Календарь событий";
$page_paragraf_path = "events";

$submit_path = "/article.php?article=".$article_data[0]["article_id"];

$event_path = "/ru/events/" . $event_data[0]["event_ddate"] . "/";

$page_id = $event_data[0]["event_id"];
$page_unix_date = $event_data[0]["event_date"];
$page_date = create_date($board_config['article_dateformat'], $event_data[0]["event_date"], $board_config['board_timezone']);

$page_name = $event_data[0]["event_name"];
$page_text = (@$no_event) ? "<p><h3>Для данной даты события отсутствуют</h3></p>" : $event_data[0]["event_text"];

$page_week_day_name = $lang['datetime']["d".date("N", $page_unix_date)];

include($DRoot . '/db/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'events_body.tpl'
));

if ($event_data[0]["event_id"]) {
	$show_photo_link_class = ($show_photo) ? "text-decoration: none;}" : "";
	$show_video_link_class = ($show_video) ? "text-decoration: none;}" : "";
	$show_details_link_class = (!$show_video and !$show_photo) ? "text-decoration: none;}" : "";

	
	$template->assign_vars(array(
		'EVENT_PATH' => $event_path
	));

	$template->assign_block_vars('swith_event', array(
//		'SAVED' => @$saved,
		'PAGE_DATE' => $page_date,
		'PAGE_WEEK_DAY' => $page_week_day_name,
		'PAGE_NAME' => $page_name,
		'PAGE_ID' => $page_id,
		'EDIT' => @$edit
		));
	$template->assign_block_vars('swith_event.event_details_menu', array(
		'MENU_SELECTED_STYLE' => $show_details_link_class,
	));
	if ($event_data[0]["event_foto"]) {
		$template->assign_block_vars('swith_event.event_photo_menu', array(
			'MENU_SELECTED_STYLE' => $show_photo_link_class
		));
	};
	if ($event_data[0]["event_video"]) {
		$template->assign_block_vars('swith_event.event_video_menu', array(
			'MENU_SELECTED_STYLE' => $show_video_link_class
		));
	};
	if ($event_details) {
		$template->assign_block_vars('swich_event_details', array(
			'PAGE_TEXT' => $page_text
		));
	}
	ElseIf ($event_photo) {
		$sql_sgal = 'SELECT * FROM '.TABLE_ARTILE_GALLERY.' WHERE `article_id` = "' .$page_id.'" and `source_id` = "2" and `video_path` is NULL;';
		$sgal_source_id = 2;
		$sgal_source_path = "events";

		$sql = $sql_sgal;
	
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к таблице минигалереи', '', __LINE__, __FILE__, $sql);
			};
	
		$smGal_data = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$smGal_data[] = $row;
			};

		include $DRoot . "/dynamic/pagination.php";
		pagination(@$get_current_page, $miniGal_item_per_page, $smGal_data, $current_event_gal_url);
		
		$template->assign_block_vars('switch_smgallery', array());

		$start_item = (@$get_current_page * $miniGal_item_per_page) - $miniGal_item_per_page;
		$end_item = @$get_current_page * $miniGal_item_per_page;
		$i = $start_item;
		while ($smGal_data[$i]['aimg_id']) {
			$template->assign_block_vars('switch_smgallery.smgallery_list', array(
				'IMG_ID' => $smGal_data[$i]['aimg_id'],
				'IMG_NAME' => $smGal_data[$i]['img_name'],
				'IMG_DESC' => $smGal_data[$i]['img_desc'],
				'IMG_PATH' => $miniGal_path . $smGal_data[$i]['img_path'],
				'SMALL_IMG_PATH' => $miniGal_path . "sm/" . $smGal_data[$i]['img_path'],
			));
			$i = ($i == $end_item-1) ? $i=99999999999 : $i;
			$i++;
		};
	}
	ElseIf ($event_video) {
		$sql_sgal = 'SELECT * FROM '.TABLE_ARTILE_GALLERY.' WHERE `article_id` = "' .$page_id.'" and `source_id` = "2" and `video_path` is NOT NULL;';
		$sgal_source_id = 2;
		$sgal_source_path = "events";

		$sql = $sql_sgal;
	
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к таблице минигалереи', '', __LINE__, __FILE__, $sql);
			};
	
		$smGal_data = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$smGal_data[] = $row;
			};

//		include $DRoot . "/dynamic/pagination.php";
//		pagination(@$get_current_page, $miniGal_item_per_page, $smGal_data, $current_event_gal_url);

		$template->assign_block_vars('switch_video_gallery', array());

//		$start_item = (@$get_current_page * $miniGal_item_per_page) - $miniGal_item_per_page;
//		$end_item = @$get_current_page * $miniGal_item_per_page;
//		$i = $start_item;
		$i = 0;
		while ($smGal_data[$i]['aimg_id']) {
			$template->assign_block_vars('switch_video_gallery.gallery_video_list', array(
				'VIDEO_ID' => $smGal_data[$i]['aimg_id'],
				'VIDEO_NAME' => $smGal_data[$i]['img_name'],
				'VIDEO_DESC' => $smGal_data[$i]['img_desc'],
				'VIDEO_PATH' => $smGal_data[$i]['video_path']
			));
//			$i = ($i == $end_item-1) ? $i=99999999999 : $i;
			$i++;
		};
		$template->assign_block_vars('swich_event_vodeo', array(
			'PAGE_TEXT' => $page_text. "ffffff"
		));
	};
	
}
Else {
	$template->assign_block_vars('swith_no_event', array());
};

//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/db/page_tail.'.$phpEx);

?>