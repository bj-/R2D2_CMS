<?php
/***************************************************************************
 *                                cruch_calendar.php
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

$site_prop = TRUE;

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = $lang['Index'];
include($DRoot . '/db/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'admin/index_body.tpl')
);

$template->assign_block_vars('switch_left_menu', array());

if ($userdata['user_level'] >0)
{
	$template->assign_block_vars('switch_menu', array());

}
Else {
	exit;
};

$text = "Церковный календарь. технический скрипт<br> 
<p>Грабить календарь с сайта script.days.ru: (выполнять действие 1 раз в начале года.) т.к. грабится только текущий год.<br>
<a href='?grab=1&m=1&d=31'>Январь</a>, 
<a href='?grab=1&m=2&d=28'>Февраль</a>, 
<a href='?grab=1&m=3&d=31'>Март</a>, 
<a href='?grab=1&m=4&d=30'>Апрель</a>, 
<a href='?grab=1&m=5&d=31'>Май</a>, 
<a href='?grab=1&m=6&d=30'>Июнь</a>, 
<a href='?grab=1&m=7&d=31'>Июль</a>, 
<a href='?grab=1&m=8&d=31'>Август</a>, 
<a href='?grab=1&m=9&d=30'>Сентябрь</a>, 
<a href='?grab=1&m=10&d=31'>Октябрь</a>, 
<a href='?grab=1&m=11&d=30'>Ноябрь</a>, 
<a href='?grab=1&m=12&d=31'>Декабрь</a><br>
Для проверки успешного граббинга - показывается отчет с тем что награбили. (не должно быть пустых строк)
</p>
<p><a href='?check=1'>Проверить</a> (проверяется корректность положенных в базу данных. также не должно быть абсолютно пустых строк)</p>
<p><a href='?grab=1'><a href='?show=1'>Показать одну дату в виде данных</a> (техническая кнопочка)</p>";


if ($_GET["show"]) {
	$lines = file("http://script.days.ru/php.php?date=0115&mesta=2");
	foreach ($lines as $line_num => $line) {
		$cruch = unserialize($line);
		$cruch_s = str_replace("'", "\'", $line);
//		$line = str_replace(' CLASS="DD_ICON"', "",  $line);
//		$line = str_replace(" CLASS='DA'", "",  $line);
//		$line = str_replace(" CLASS='DI'", "",  $line);
//		$cruch_s = $line;
	};

	echo "<br>".$m.$d." - ".strip_tags($cruch["day"]);

	echo "==================\n\r<br />";
	echo	$sql = "INSERT INTO `vm_cruch_calendar` (`calendar_date`, `chten`, `day`, `para`, `ned`, `post`, `prazdnik`, `dayicon`, `calendar_data`) 
				VALUES ('".$m.$d."', '".$cruch["chten"]."', '".strip_tags($cruch["day"])."', '".$cruch["para"]."', '".$cruch["ned"]."', '".
				$cruch["post"]."', '".strip_tags($cruch["prazdnik"]).
			"', '".$cruch["dayicon"]."', '".$cruch_s."')";
	echo "\n\r<br />==================\n\r";
	

echo "<pre>";
print_r(array_values($cruch));
echo "</pre>";

echo "<pre>";
print_r(array_keys($cruch));
echo "</pre>";
 
}
ElseIf ($_GET["grab"]) {
	if (!$_GET["m"] or !$_GET["d"]) {
		exit;
	}
	Else {
		$m = $_GET["m"];
		$d_total = $_GET["d"];
	};
	$d=1;
	
	echo "<table>\n\r";
	echo "<tr><th>Дата мм.дд</th><th>дата по старому и новому стилю</th><th>седмица</th></tr>\n\r";

	
	while ($d<=$d_total) {
		$m = (strlen($m)==1) ? $m = "0" . $m : $m;
		$d = (strlen($d)==1) ? $d = "0" . $d : $d;
		$lines = file("http://script.days.ru/php.php?date=".$m.$d."&mesta=2");
		foreach ($lines as $line_num => $line) {
			$cruch = unserialize($line);
//			$cruch_s = str_replace("'", "\'", $line);
			
			$calendar_full_date = "2011".$m.$d;
			$calendar_date = $m.$d;
			$chten = str_replace("'", "\'", $cruch["chten"]);
			$day = strip_tags(str_replace("'", "\'", $cruch["day"]));
			$para = str_replace("'", "\'", $cruch["para"]);
			$ned = str_replace("'", "\'", $cruch["ned"]);
			$post = str_replace("'", "\'", $cruch["post"]);
			$prazdnik = strip_tags(str_replace("'", "\'", $cruch["prazdnik"]));
			$dayicon = str_replace("'", "\'", $cruch["dayicon"]);
			$calendar_data = str_replace("'", "\'", $line);
		}
		echo "<tr><td>".$calendar_date."</td><td>".$day . "</td><td>" . $ned . "</td></tr>\n\r";
	//	echo "==================\n\r<br />";
		$sql = "INSERT INTO `vm_cruch_calendar` (`calendar_full_date`, `calendar_date`, `chten`, `day`, `para`, `ned`, `post`, `prazdnik`, `dayicon`, `calendar_data`) 
						VALUES ('".$calendar_full_date."', '".$calendar_date."', '".$chten."', '".$day."', '".$para."', '".$ned."', '".$post."', '".
						$prazdnik."', '".$dayicon."', '".$calendar_data."')";
	//	echo "\n\r<br />==================\n\r";
	
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'Ошибка занесения в бд', '', __LINE__, __FILE__, $sql);
			};
	//	sleep(1);
		$d++;
	};
	
	echo "</table>\n\r";
}
ElseIf ($_GET["check"]) {
	$sql = "SELECT * FROM `vm_cruch_calendar` ORDER BY `calendar_full_date` ASC"; 

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка запроса из бд', '', __LINE__, __FILE__, $sql);
		};

	echo "<table>\n\r";
	echo "<tr><th>Дата гггг.мм.дд по ст.ст.</th><th>дата по старому и новому стилю</th><th>седмица</th></tr>\n\r";

	$cruch_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$cruch_data[] = $row;
		};
	$i = 0;
	while ($cruch_data[$i]["calendar_id"]) {
// 		$cruch_data[$i]["id"];
		$cruch = unserialize($cruch_data[$i]["calendar_data"]);
		echo "<tr><td>".($i+1)." - ".$cruch["ymd"]."</td><td>".$cruch["day"] . "</td><td>" . $cruch["ned"] . "</td></tr>\n\r";
		$i++;
	};
	echo "</table>\n\r";
};

/*
echo "<pre>";
print_r(array_values($cruch));
echo "</pre>";

echo "<pre>";
print_r(array_keys($cruch));
echo "</pre>";

echo "<pre>";
print_r(array_keys($cruch["feofan"]));
echo "</pre>";
*/


$template->assign_vars(array(
	'TEXT' => $text
	));



//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/db/page_tail.'.$phpEx);

?>

