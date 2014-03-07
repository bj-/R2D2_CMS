<?php
// Редактирование текстов (статей)

if ($_GET["action"] == "grab") {
	// грабим текущую дату если ее нет в базе
	
}
Else {
	// показываем календарик
	if ( !defined('IN_R2D2') )
	{
		die("Hacking attempt");
	};
	// Входящие переменные
	// нет

};


function cruch_clendar_show() {
	global $template, $db;

	$ret = "";

	$template->set_filenames(array(
		'mod_cruch_calendar' => 'mod_cruch_calendar.tpl')
	);

	$curr_date = date("Ymd", mktime());

	$sql = "SELECT `calendar_id`, `calendar_full_date`, `calendar_date`, `chten`, `day`, `para`, `ned`, `prazdnik`, `dayicon` FROM `" . 
			TABLE_CRUCH_CALENDAR ."` WHERE `calendar_full_date` = '".$curr_date."' OR `calendar_id`='-1' ORDER BY `calendar_id` ASC;";

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка запроса из бд', '', __LINE__, __FILE__, $sql);
	};
		
	$cruch_calendar_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$cruch_calendar_data[] = $row;
//		echo $row; $cruch_calendar_data[0]["calendar_id"];
	};

	if (!$cruch_calendar_data[0]["calendar_id"]) {
		message_die(GENERAL_ERROR, 'Дата в православном календаре не найдена', '', __LINE__, __FILE__, $sql);
	};

//echo $cruch_icon = $cruch_calendar_data[1]["calendar_id"];
	if (@$cruch_calendar_data[1]["calendar_id"]) {
		$cruch_icon = $cruch_calendar_data[1]["dayicon"];
		$cruch_week = $cruch_calendar_data[1]["ned"];
		$cruch_post = $cruch_calendar_data[1]["post"];
		$cruch_day = $cruch_calendar_data[1]["day"];
		$cruch_holiday = $cruch_calendar_data[1]["prazdnik"];
		$cruch_saints = $cruch_calendar_data[1]["para"];
		$cruch_righting = $cruch_calendar_data[1]["chten"];
	};

	$template->assign_block_vars('cruch_calendar_show',array(
		'CRUCH_ICON' => $cruch_icon,
		'CRUCH_WEEK' => $cruch_week,
		'CRUCH_POST' => $cruch_post,
		'CRUCH_DAY' => $cruch_day,
		'CRUCH_HOLIDAY' => $cruch_holiday
	));
	if ($cruch_saints) {
		$template->assign_block_vars('cruch_calendar_show.cruch_saints',array(
			'CRUCH_SAINTS' => $cruch_saints
		));
	};
	if ($cruch_righting) {
		$template->assign_block_vars('cruch_calendar_show.cruch_righting',array(
			'CRUCH_RIGHTING' => $cruch_righting
		));
	};


	$template->assign_var_from_handle('CRUCH_CALENDAR', 'mod_cruch_calendar');

	return $ret;

};
cruch_clendar_show();
?>