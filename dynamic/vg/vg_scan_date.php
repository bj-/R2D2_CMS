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
include("../../includes/config.php");
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

$id = intval($_GET["id"]);
$action = substr($_GET["action"], 0, 32);
$sdate = substr($_GET["sdate"], 0, 32);

$sdate_arr = explode(".", $sdate);

$sdate_unix = mktime(12,0,0, intval($sdate_arr[1]), intval($sdate_arr[0]), intval($sdate_arr[2]));

//Добавляем дату в скан
if ($action == "add_date") {
	$sql = 'UPDATE `'.TABLE_VG_SCAN_TMP.'` SET `scan_date` = "'.$sdate_unix.'" WHERE `user_id` = "'.$userdata["user_id"].'" AND `scan_id` = "'.$id. '";';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
	};
};


//
// Start output of page
//
date_default_timezone_set("Europe/Moscow");


$sql = 'SELECT * FROM `'.TABLE_VG_SCAN_TMP.'` WHERE `scan_id` = "'.$id.'";';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
};
$scan_tmp = $db->sql_fetchrow($result);

echo date("d.m.Y", $scan_tmp["scan_date"]);

?>