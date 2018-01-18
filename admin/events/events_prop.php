<?php
/***************************************************************************
 *                                article_prop.php
 *                            -------------------
 *   begin                : Sep 20, 2010
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: article_prop.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
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
include("../db/config.php");
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

// Проверяем права пользователя. если нет - выкидываем
if ($userdata['user_level'] < 1) {
	message_die(GENERAL_ERROR, 'Пользователь не имеет прав', '', __LINE__, __FILE__, $sql);
	exit;
};

// Входящие переменные
$get_id = substr($_GET['id'],0,11);


//метка для построителя хидера, меню и редактора галереи - что включать
$event_prop = TRUE;

// НАЧАЛО сохранения

// подрезаем и проверяем входные переменные на спецсимволы и пр казусы вроде инжекшенов
if ($_POST['save']) {
	$save_page_title = str_encode_char(substr($_POST['page_title'],0,240));
	$save_page_desc = str_encode_char(substr($_POST['page_desc'],0,240));
	$save_page_keywords = str_encode_char(substr($_POST['page_keywords'],0,240));
	$save_page_text = str_encode_char($_POST['page_text']);
	$current_article_id = substr($_POST['article_id'],0,11);
	
	$event_foto = ($save_event_foto) ? ", `event_foto` =  '".$save_event_foto."' " : "";
	$event_video = ($event_video) ? ", `event_video` =  '".$event_video."' " : "";

	$event_date = substr($_POST['page_date'], 0 , strpos($_POST['page_date'], " "));
	$event_date = substr($event_date, 0 , 10);
	$event_time = substr($_POST['page_date'], (strpos($_POST['page_date'], " ")+1), 16);
	$event_time = substr($event_time, 0 , 5);
	
	$event_date_arr = explode(".", $event_date);
	$event_time_arr = explode(":", $event_time);
	$event_ddate = $event_date;
	$event_date = mktime($event_time_arr[0], $event_time_arr[1], 0, $event_date_arr[1], $event_date_arr[0], $event_date_arr[2]);

	$get_id = $current_article_id;
	
	if($_POST['date_sel'] == "current_date") {
		$article_date = time();
		}
	Else {
		// переводим дату в юникс формат
		$article_date = substr($_POST['page_date'], 0 , 10);
		$article_date_arr = explode(".", $article_date);
		$article_date = mktime(12, 0, 0, $article_date_arr[1], $article_date_arr[0], $article_date_arr[2]);
	};

};

// Сохраняем отредактированную статью
if ($_POST['save'] and $_POST['new'] and ($userdata['user_level'] >0)) { 

	// выбираем последний ID статьи
	$sql = "SELECT MAX(`event_id`) AS `max_article_id` FROM `" . TABLE_EVENTS . "`";

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка выяснения максимального ID статьи', '', __LINE__, __FILE__, $sql);
		};
	$new_article_id_data = array();
	$new_article_id_data = $db->sql_fetchrow($result);
	$new_article_id = $new_article_id_data["max_article_id"]+1;
	$get_article = $new_article_id;
	$get_id = $get_article;

	$sql = "INSERT INTO `" . TABLE_EVENTS . "` (`event_id`, `event_name` , `event_desc` , `event_text`, `event_ddate` , `event_date`) VALUES (".
				"'".$new_article_id."', '".$save_page_title."', '".$save_page_desc."', '".$save_page_text."', '".
				$event_ddate."', '".$event_date."');";

	$current_paragraf_id = $save_paragraf_id;
	$current_article_id = FALSE;
	

	if ($db->sql_query($sql)) {
//		$saved = '<strong>' . $lang['Saved'] . '</strong>';
	 	if ($_GET['adm']) {
			redirect("/admin/index.php?edit=articles");
			exit;
		};
	}
	Else {
		message_die(GENERAL_ERROR, 'Ошибка сохранения календаря', '', __LINE__, __FILE__, $sql);
	};

}
ElseIf ($_POST['save'] and ($userdata['user_level'] >0)) {
	$sql = 
	"UPDATE `" . TABLE_EVENTS . "` SET 
	`event_name` = '".$save_page_title."', 
	`event_text` = '".$save_page_text."',
	`event_date` =  '".$article_date."',
	`event_ddate` =  '".date("d.m.Y", $article_date)."' "
	. $event_foto
	. $event_video . "
	 WHERE `event_id` = '" . $get_id ."';";


if ($db->sql_query($sql)) {
	$saved = '<strong>' . $lang['Saved'] . '</strong>';
	}
	Else {
	message_die(GENERAL_ERROR, 'Ошибка сохранения статьи', '', __LINE__, __FILE__, $sql);
	};

};


// КОНЕЦ сохранения




define('SHOW_ONLINE', true);
//
// включение/выключение модулей до загрузки страницы.
//
if ($_GET['sgallery'] or $_GET['svgallery']) { // галерея
	include $DRoot . "/admin/includes/module_swich.php";
}

//echo		$smGal_status . $smVideoGal_status;

$sql = "SELECT * FROM `" . 
TABLE_EVENTS . '` WHERE `event_id` = "'.$get_id.'"';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'Статья отсутствует', '', __LINE__, __FILE__, $sql);
	};
$event_data = array();
$event_data = $db->sql_fetchrow($result);

if (!$event_data["event_id"]) {
	$no_article = TRUE;
	message_die(GENERAL_ERROR, 'Запрошенное событие не найдено' . $add, '', __LINE__, __FILE__, $sql);
};

// загоняем в переменные данные о статье
$page_title = $event_data["event_name"];
$page_classification = "";
$page_desc = "";
$page_keywords = "";
$page_content_direction = "";

//$submit_path = "/article.php?article=".$article_data[0]["article_id"];
$page_id = $event_data["event_id"];
$page_unix_date = $event_data["event_date"];
$page_date = create_date($board_config['article_dateformat'], $page_unix_date, $board_config['board_timezone']);


$page_text = (@$no_article) ? "<p><h2>Запрошенная событие не найдено</h2></p><p>Жалоба администратору сайта уже написана автоматически, спасибо за помощь.</p>" : $event_data["event_text"];

$smGal_status = $event_data["event_foto"];
$smgal_onoff = ($smGal_status) ? '<font style="color:green">подключена</font>' : '<font style="color:red">отключена</font>';
$smVideoGal_status = $event_data["event_video"];
$smVideoGal_onoff = ($smVideoGal_status) ? '<font style="color:green">подключена</font>' : '<font style="color:red">отключена</font>';


$article_path = "/".$url_lang."/events/" . $page_date;

include($DRoot . '/db/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'admin/article_prop_body.tpl'
));


//
// какую страницу показывать
//
if ($_GET['sgallery']) { // галерея
	include $DRoot . "/admin/includes/sgallery.php";
}
elseIf ($_GET['svgallery']) { // галерея
	include $DRoot . "/admin/includes/sgallery.php";
}
Else {
	include $DRoot . "/admin/includes/article_prop_list.php";
};

// обходной маневр для показывания дефолтной страницы после выполнении малозначительных операций
if ($show_prop) {
	include $DRoot . "/admin/includes/article_prop_list.php";
}

//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/db/page_tail.'.$phpEx);

?>