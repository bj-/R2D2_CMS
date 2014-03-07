<?php
/***************************************************************************
 *                                weather.php
 *                            -------------------
 *   begin                : Dec 06, 2010
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: weather.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   License
 *
 ***************************************************************************/

/***********************************************************

  “ип погоды:
  	0 - €сно
	1 - малооблачно
	2 - облачно
	3 - пасмурно
	4 - дождь
	5 - ливень
	6 - гроза
	7 - снег

**********************************************************/

define('IN_R2D2', true);
include("../db/config.php");
include($DRoot . '/db/extension.inc');
include($DRoot . '/db/common.'.$phpEx);

$lines = file('http://informer.gismeteo.ru/xml/26063_1.xml');

// 3 9 15 21
$curr_hour = date("G",time());
$last_hour_update = substr($board_config['weather'],0, strpos($board_config['weather'], ";"));

if ($last_hour_update == "21" and $curr_hour>=3 and $curr_hour<9) {
	$update_weather = TRUE;
	$new_weather_hour_update = "3";
}
ElseIf ($last_hour_update == "3" and $curr_hour>=9 and $curr_hour<15) {
	$update_weather = TRUE;
	$new_weather_hour_update = "9";
}
ElseIf ($last_hour_update == "9" and $curr_hour>=15 and $curr_hour<21) {
	$update_weather = TRUE;
	$new_weather_hour_update = "15";
}
ElseIf ($last_hour_update == "15" and ($curr_hour>=23 or $curr_hour<3)) {
	$update_weather = TRUE;
	$new_weather_hour_update = "21";
}
Else {
	$update_weather = FALSE;
};
if ($update_weather) {

	$i_ph = 0;
	foreach ($lines as $line_num => $line) {
		if (strpos($line, "PHENOMENA")) {
			$line = str_replace("	", "", $line);
			$line = preg_replace('/[^0-9 ]+/', '', $line);
			$line = ltrim($line, " ");
			$tmp_ph = array();
			$tmp_ph = explode(" ", $line);
			$phenomena[$i_ph]["cloudiness"] = $tmp_ph[0];
			$phenomena[$i_ph]["precipitation"] = $tmp_ph[1];
			$phenomena[$i_ph]["rpower"] = $tmp_ph[2];
			$phenomena[$i_ph]["spower"] = $tmp_ph[3];
			$i_ph++;
		};
	};
	
	// сначала присваиваем данные о облачности
	$weather_type = $phenomena[0]["cloudiness"];
	
	// перезабиваем данными о осадках если они есть
	$weather_type = ($phenomena[0]["precipitation"] == 4) ? "4" : $weather_type;
	$weather_type = ($phenomena[0]["precipitation"] == 5) ? "5" : $weather_type;
	$weather_type = ($phenomena[0]["precipitation"] == 6) ? "7" : $weather_type;
	$weather_type = ($phenomena[0]["precipitation"] == 7) ? "7" : $weather_type;
	$weather_type = ($phenomena[0]["precipitation"] == 8) ? "6" : $weather_type;
	
	$sql = "UPDATE `". CONFIG_TABLE . "` SET `config_value` = '".$new_weather_hour_update.";".$weather_type."' WHERE `config_name` = 'weather';";
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ќшибка занесени€ данных о погоде в бд', '', __LINE__, __FILE__, $sql);
		};
};
?>