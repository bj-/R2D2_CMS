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
include("../../includes/config.php");
include($DRoot . '/includes/common.'.$phpEx);
include($DRoot . '/mod/form/form_config.'.$phpEx);


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

$get_form_id = intval(substr($_GET["form_id"],0,11));

// Название формы
$get_form_name = substr(mysql_real_escape_string($_POST["form_name"]),0,255);
$get_form_desc = substr(mysql_real_escape_string($_POST["form_desc"]),0,255);

// Поля формы
$get_field_id = intval(substr($_POST["field_id"],0,11));
$get_field_name = substr(mysql_real_escape_string($_POST["field_name"]),0,255);
$get_field_type = intval(substr($_POST["field_type"],0,3));
$get_field_val = substr(mysql_real_escape_string($_POST["field_val"]),0,255);
$get_field_size = intval(substr(mysql_real_escape_string($_POST["field_size"]),0,10));
	$field_size_width = intval(substr(mysql_real_escape_string($_POST["field_size_width"]),0,10));
	$field_size_height = intval(substr(mysql_real_escape_string($_POST["field_size_height"]),0,9));
$get_field_maxlen = intval(substr($_POST["field_maxlen"],0,11));
$get_field_require = intval(substr($_POST["field_require"],0,1));
$get_field_sort = intval(substr($_POST["field_sort"],0,11));
$get_field_class = substr(mysql_real_escape_string($_POST["field_class"]),0,255);
$get_field_style = substr(mysql_real_escape_string($_POST["field_style"]),0,255);

//
// Сохраняем  / удаляем
//
if ($_GET["action"] == "remove") {
	// Удаляем форму.
	$sql = 'DELETE FROM `' . TABLE_FORMS . '` WHERE `form_id` = "'.$get_form_id.'";';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка изменения названия почтовой формы', '', __LINE__, __FILE__, $sql);
	};
	
}
elseif ($_POST["save_form"]) {
	if ($_POST["new_form"]) {
		// Добавляем новую форму
		$sql = 'INSERT INTO `' . TABLE_FORMS . '` '.
				'(`form_name`, `form_desc`, `form_type`) VALUES ('.
				'"'.$get_form_name.'", "'.$get_form_desc.'", "10"'.
				');';
	}
	else {
		// сохраняем название существующей формы.
		$sql = 'UPDATE `' . TABLE_FORMS . '` '.
				'SET '.
				'`form_name` = "'.$get_form_name.'", '.
				'`form_desc` = "'.$get_form_desc.'" '.
				'WHERE `form_id` = "'.$get_form_id .'";';
	};
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка изменения названия почтовой формы', '', __LINE__, __FILE__, $sql);
	};

}
elseif ($_GET["delete_feild_id"]) {
	$get_delete_feild_id = intval(substr($_GET["delete_feild_id"],0,11));
	$sql = 'DELETE FROM `' . TABLE_FORMS_FIELDS . '` WHERE `field_id` = "'.$get_delete_feild_id.'";';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Таблица полей форм отсутствует', '', __LINE__, __FILE__, $sql);
	};
}
elseif ($_POST["save_field"]) {
//	echo "seve";
	if ($get_field_type==4) {
		$get_field_size = $field_size_width . ";" . $field_size_height;
	};
	
	if ($_POST["field_id"] == "new" ) { // добовляем новое
//		$get_field_name = ($get_field_name) ? $get_field_name : "Текст" ; // пишем ченить если не ввели название
		$get_field_size = ($get_field_type==4) ? $field_size_width . ";" . $field_size_height : $get_field_size;

		// смотрим какое макс значение было в сортировке. что б назначить для нового поля.
		$sql = 'SELECT MAX(`field_sort`) AS `f_sort` FROM `' . TABLE_FORMS_FIELDS . '` WHERE `form_id`="'.$get_form_id.'";';

		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'Таблица полей форм отсутствует', '', __LINE__, __FILE__, $sql);
		};
		$f_sort = $db->sql_fetchrow($result);

		// инсертим поле.	
		$sql = 'INSERT INTO `' . TABLE_FORMS_FIELDS . '` '.
			'(`form_id`, `field_name`, `field_type`, `field_val`, `field_size`, `field_maxlen`, `field_require`, `field_sort`, `field_class`, `field_style`) '.
			'VALUES ('.
			'"'.$get_form_id.'", "'.$get_field_name.'", "'.$get_field_type.'", "'.$get_field_val.'", "'.$get_field_size.'", "'.$get_field_maxlen.'", "'.$get_field_require.'", "'.($f_sort["f_sort"]+1).'", "'.$get_field_class.'", "'.$get_field_style.'" '.
			');';
		
	}
	else { // апдейтим существующее
		$sql = 'UPDATE `' . TABLE_FORMS_FIELDS . '` '.
				'SET '.
				'field_name = "'.$get_field_name.'", field_type = "'.$get_field_type.'", field_val = "'.$get_field_val.'", field_size = "'.$get_field_size.'", field_maxlen = "'.$get_field_maxlen.'", '.
				'field_require = "'.$get_field_require.'", field_class = "'.$get_field_class.'", field_style  = "'.$get_field_style.'" '.
				'WHERE `field_id` = "'.$get_field_id.'"';
	};
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка апдейта поля формы', '', __LINE__, __FILE__, $sql);
	};

};


// билдим превью формочки
function buld_form_preview($form_id) {
	global $db;
	$ret = "";

	$sql = 'SELECT * '.
			'FROM `' . TABLE_FORMS_FIELDS . '` '.
			'WHERE `form_id` = "'.$form_id.'" '.
			'ORDER BY `field_sort` ASC;';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Таблица полей форм отсутствует', '', __LINE__, __FILE__, $sql);
	};

	$forms_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$forms_data[] = $row;
	};
	
	if (count($forms_data)) {
		$i = 0;
		while ($forms_data[$i]["field_id"]) {
			$requierd_field = ($forms_data[$i]["field_require"]) ? '<span style="color:red">*</span>' : '';
			switch ($forms_data[$i]["field_type"]) {
				case 1: // текстовое до 255 сиволов
					$ret .= '<tr><td>'.$forms_data[$i]["field_name"].': '.$requierd_field.'</td><td style="border:solid 1px gray; padding-left:2px; padding-right:2px;" width="60%" nowrap="nowrap">текст до '. $forms_data[$i]["field_maxlen"] .' символов</td></tr>';
					break;
				case 2: // емыло
					$ret .= '<tr><td>'.$forms_data[$i]["field_name"].': '.$requierd_field.'</td><td style="border:solid 1px gray; padding-left:2px; padding-right:2px;" width="60%" nowrap="nowrap">Client.Name@mail.ru</td></tr>';
					break;
				case 3: // телефон
					$ret .= '<tr><td>'.$forms_data[$i]["field_name"].': '.$requierd_field.'</td><td style="border:solid 1px gray; padding-left:2px; padding-right:2px;" width="60%" nowrap="nowrap">+7 (911) 001-02-03</td></tr>';
					break;
				case 4: // текстареа
					$ret .= '<tr><td valign="top">'.$forms_data[$i]["field_name"].': '.$requierd_field.'</td><td style="border:solid 1px gray; padding-left:2px; padding-right:2px; height:30px;" valign="top" width="60%">текстовое сообщение</td></tr>';
					break;
				case 5: // число
					$ret .= '<tr><td>'.$forms_data[$i]["field_name"].': '.$requierd_field.'</td><td style="border:solid 1px gray; padding-left:2px; padding-right:2px;" width="60%" nowrap="nowrap">1000</td></tr>';
					break;
			};
			
			$i++;
		};
		$ret = '<table width="60%" border="0" cellpadding="0" celsspacing="2" style="font-size:9px;" align="center">'.
			$ret.
			'<tr><td></td><td><div style="padding:3px; background:#adadad; border:gray; font-weight:bold; color:black; width:50px;; ">Отправить</div></td></tr>'.
			'</table>';
	}
	else {
		$ret = 'Форма пустая (нет ни одного поля)';
	};
	
	
	return $ret;
};

//
// Список форм
//

define('SHOW_ONLINE', true);
$page_title = "Формы отсылки сообщений";
/*
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
*/

$site_prop = TRUE; // включем админское меню
include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'admin/form/adm_form_index.tpl')
);

if ($_GET["action"] == "edit") {
	// выбираем название и описание формы
	$sql = 'SELECT * '.
			'FROM `' . TABLE_FORMS . '` '.
			'WHERE `form_id` = "'.$get_form_id.'";';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Таблица форм отсутствует', '', __LINE__, __FILE__, $sql);
	};
	$forms_data = $db->sql_fetchrow($result);
	
	$template->assign_block_vars('switch_form_edit', array(
		'FORM_ID' => $get_form_id,
		'FORM_NAME' => $forms_data["form_name"],
		'FORM_DESC' => $forms_data["form_desc"],
		'FORM_PREVIEW' => buld_form_preview($get_form_id),
	));

	// данные о полях формы.		
	$sql = 'SELECT * '.
			'FROM `' . TABLE_FORMS_FIELDS . '` '.
			'WHERE `form_id` = "'.$forms_data["form_id"].'" '.
			'ORDER BY `field_sort` ASC;';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Таблица полей форм отсутствует', '', __LINE__, __FILE__, $sql);
	};

	$form_fields_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$form_fields_data[] = $row;
	};
	
	
	$i=0;
	while ($form_fields_data[$i]["field_id"]) {
		$required = ($form_fields_data[$i]["field_require"]) ? '<span style="color:red; font-weight:bold;">*</span>' : "";
		$field_CSS = ($form_fields_data[$i]["field_class"] or $form_fields_data[$i]["field_style"]) ? '<img src="/pic/ico/style_16x16.png" alt="Назначен CSS класс или стиль" width="16" height="16" border="0" />' : "";
		if (strpos($form_fields_data[$i]["field_size"], ";")) {
			$form_fields_data_xy = explode(";", $form_fields_data[$i]["field_size"]);
			$field_size = $form_fields_data_xy[0] . ' x ' . $form_fields_data_xy[1];
		}
		else {
			$field_size = $form_fields_data[$i]["field_size"];
		};
		
		$template->assign_block_vars('switch_form_edit.form_field_list', array(
			'FORM_ID' => $forms_data["form_id"],
			'FIELD_ID' => $form_fields_data[$i]["field_id"],
			'NAME' => $form_fields_data[$i]["field_name"],
			'VAL' => $form_fields_data[$i]["field_val"],
			'TYPE' => $field_type[$form_fields_data[$i]["field_type"]],
			'SIZE' => $field_size,
			'LEN' => $form_fields_data[$i]["field_maxlen"],
			'REQ' => $required,
			'CSS' => $field_CSS,
			'SORT' => $form_fields_data[$i]["field_sort"],
		));
		$i++;
	};
}
else {

	$sql = 'SELECT * '.
			'FROM `' . TABLE_FORMS . '` '.
			'WHERE `form_id` >0 '.
			'ORDER BY `form_type` ASC, `form_name` ASC';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Таблица форм отсутствует', '', __LINE__, __FILE__, $sql);
	};
	$forms_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$forms_data[] = $row;
	};

	$template->assign_block_vars('switch_form_show', array());
//switch_form_show.
	$form_type_id = -1;
	$i = 0;
	while ($forms_data[$i]["form_id"]) {
		$template->assign_block_vars('switch_form_show.form_list', array(
			'FORM_ID' => $forms_data[$i]["form_id"],
			'FORM_NAME' => $forms_data[$i]["form_name"],
			'FORM_DESC' => $forms_data[$i]["form_desc"],
			'FORM_PREVIEW' => buld_form_preview($forms_data[$i]["form_id"]),
		));
		if ($form_type_id <> $forms_data[$i]["form_type"]) {
			$template->assign_block_vars('switch_form_show.form_list.form_type', array(
				'FORM_TYPE' => $form_type[$forms_data[$i]["form_type"]],
			));
			$form_type_id = $forms_data[$i]["form_type"];
		};
	
		$i++;
	};
};

//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>