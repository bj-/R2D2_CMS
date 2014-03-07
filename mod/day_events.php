<?php
if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}


$events_week = ($_POST["week"]) ? substr($_POST["week"],0, 11) : 0;

$events_date_current = date("j.m", time());
$events_day_current = date("N", time());

$events_date_request_unix = (time()+(604800*$events_week));

$events_date_show = ($events_week) ? $events_date_request_unix : time();

$events_start_date_unix = $events_date_show-(86400*($events_day_current-1));

$events_date_data = Array();
$events_day_data = Array();
$i = 1;
$max_day = 7;
while ($i <= $max_day) {
	// даты
	$events_date_data[$i] = date("j.m", $events_start_date_unix+(86400*($i-1)));
	$events_date_data_full[$i] = date("d.m.Y", $events_start_date_unix+(86400*($i-1)));
	$events_date_data[$i] = ($events_date_data[$i] == $events_date_current) ? "сегодня" : $events_date_data[$i];
	
	// дни
	$events_day_data[$i]["class_base"] = ($events_date_data[$i] == "сегодня") ? "acalbuttb" : "calbutt";
	$events_day_data[$i]["current_start"] = ($events_date_data[$i] == "сегодня") ? '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="acalbuttt"><tr><td align="center">' : "";
	$events_day_data[$i]["current_end"] = ($events_date_data[$i] == "сегодня") ? '</td></tr></table>' : "";
	$events_day_data[$i]["link"] = "/ru/events/" . $events_date_data_full[$i] . "/";
	
	$i++;
};

$template->assign_vars(array(
	'PREV_WEEK' => $events_week-1,
	'NEXT_WEEK' => $events_week+1
));

$template->assign_block_vars('events_date', array(
		'DATE_1ST' => $events_date_data[1],
		'DATE_2ST' => $events_date_data[2],
		'DATE_3ST' => $events_date_data[3],
		'DATE_4ST' => $events_date_data[4],
		'DATE_5ST' => $events_date_data[5],
		'DATE_6ST' => $events_date_data[6],
		'DATE_7ST' => $events_date_data[7]
	));

$template->assign_block_vars('events_day', array(
	'CLASS_BASE_1ST' => $events_day_data[1]["class_base"],
	'CURRENT_START_1ST' => $events_day_data[1]["current_start"],
	'CURRENT_END_1ST' => $events_day_data[1]["current_end"],
	'LINK_1ST' => $events_day_data[1]["link"],
	
	'CLASS_BASE_2ST' => $events_day_data[2]["class_base"],
	'CURRENT_START_2ST' => $events_day_data[2]["current_start"],
	'CURRENT_END_2ST' => $events_day_data[2]["current_end"],
	'LINK_2ST' => $events_day_data[2]["link"],

	'CLASS_BASE_3ST' => $events_day_data[3]["class_base"],
	'CURRENT_START_3ST' => $events_day_data[3]["current_start"],
	'CURRENT_END_3ST' => $events_day_data[3]["current_end"],
	'LINK_3ST' => $events_day_data[3]["link"],

	'CLASS_BASE_4ST' => $events_day_data[4]["class_base"],
	'CURRENT_START_4ST' => $events_day_data[4]["current_start"],
	'CURRENT_END_4ST' => $events_day_data[4]["current_end"],
	'LINK_4ST' => $events_day_data[4]["link"],

	'CLASS_BASE_5ST' => $events_day_data[5]["class_base"],
	'CURRENT_START_5ST' => $events_day_data[5]["current_start"],
	'CURRENT_END_5ST' => $events_day_data[5]["current_end"],
	'LINK_5ST' => $events_day_data[5]["link"],

	'CLASS_BASE_6ST' => $events_day_data[6]["class_base"],
	'CURRENT_START_6ST' => $events_day_data[6]["current_start"],
	'CURRENT_END_6ST' => $events_day_data[6]["current_end"],
	'LINK_6ST' => $events_day_data[6]["link"],

	'CLASS_BASE_7ST' => $events_day_data[7]["class_base"],
	'CURRENT_START_7ST' => $events_day_data[7]["current_start"],
	'CURRENT_END_7ST' => $events_day_data[7]["current_end"],
	'LINK_7ST' => $events_day_data[7]["link"],
	));


$template->assign_vars(array(

));


?>