<?php
/***************************************************************************
 *                                event_edit.php
 *                            -------------------
 *   begin                : Jun 13, 2010
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: event.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
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

//
// собираем страницу со статьей
//
$get_event = substr($_GET['id'],0, 11);
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами


$event_data = array();

if (@$get_event) {
	$sql = "SELECT * FROM `" . TABLE_EVENTS . '` WHERE event_id="'.$get_event.'";';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка запроса события из базы данных', '', __LINE__, __FILE__, $sql);
		};
	$row = $db->sql_fetchrow($result);
	$event_data = $row;

	$page_unix_date = $event_data["event_date"];
	$page_date = create_date($board_config['article_dateformat'], $page_unix_date, $board_config['board_timezone']);

}
Else {
	$page_unix_date = false;
	$page_date = false;

};




//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = $event_data["event_name"];
$page_classification = "";
$page_desc = "";
$page_date = ($event_data["event_date"]) ? create_date($board_config['edit_dateformat'], $event_data["event_date"], $board_config['board_timezone']) : create_date($board_config['edit_dateformat'], time(), $board_config['board_timezone']);;
$page_keywords = "";
$page_content_direction = "";

$submit_path = "/admin/events_prop.php";
$page_id = $event_data["event_id"];

$page_path = "";
$page_text = $event_data["event_text"];

$event_foto = $event_data["event_foto"];
$event_video = $event_data["event_video"];


include($DRoot . '/db/page_header.'.$phpEx);

if (@$_GET['edit'] and $userdata['user_level'] >0) { // редактироване статьи
	$rows_edit = "25"; // кол-во строк в редакторе

	include $DRoot . "/db/edit.php";

	$template->assign_block_vars('switch_property_edit', array());
	}
ElseIf (@$_GET['add'] and $userdata['user_level'] >0) { // редактироване статьи
	$rows_edit = "25"; // кол-во строк в редакторе
	$submit_path = "/admin/events_prop.php";
 	if ($_GET['adm']) {	$submit_path .= "?adm=1"; };
	include $DRoot . "/db/edit.php";
	
	$template->assign_block_vars('switch_property_edit', array());
};


//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/db/page_tail.'.$phpEx);

?>