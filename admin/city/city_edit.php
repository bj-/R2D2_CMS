<?php
/***************************************************************************
 *                                admin index.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The R2D2 Group
 *
 *   $Id: article.php,v 1.99.2.1 2002/12/19 17:17:40 psotfx Exp $
 *
 *
 ***************************************************************************/
// район -> улица -> дом = управл€юща€ компани€ -> оценка -> [оценить] - лимит раз в день.
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
include("../../includes/config.php");
//include($DRoot . '/db/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);

include($DRoot . '/mod/city/city_config.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

$type = substr($_GET["type"], 0, 10);
$district_id = preg_replace('/[^0-9]+/', '', substr($_GET["district_id"],0, 11));
$str_id = preg_replace('/[^0-9]+/', '', substr($_GET["str_id"],0, 11));
$house_id = preg_replace('/[^0-9]+/', '', substr($_GET["house_id"],0, 11));
$m_comp_id = preg_replace('/[^0-9]+/', '', substr($_GET["m_comp_id"],0, 11));


$save = ($_GET["save"]) ? TRUE : FALSE;

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = $lang['Index'];

$template->set_filenames(array(
	'body' => 'admin/city/city_edit.tpl')
);

$template->assign_block_vars('switch_left_menu', array());

function get_str_name_id($get_str_name_sql) {
	global $db;

	$sql = 'SELECT * FROM `'.TABLE_CITY_STR_NAMES.'` WHERE str_name = "'. $get_str_name_sql . '";';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице названий улиц', '', __LINE__, __FILE__, $sql);
		};
	
	$str_name_data =  $db->sql_fetchrow($result);

	return $str_name_data;
};

if ($save and $userdata['user_level'] >0) {
	$get_str_id = intval(substr($_GET["str_id"], 0, 11));
	$get_house_num = intval(substr($_GET["house_num"], 0, 11));
	$get_mng_company = intval(substr($_GET["mng_company"], 0, 11));
	$get_house_corp = intval(substr($_GET["house_corp"], 0, 11));
	$get_house_letter =  substr(iconv("UTF-8", "windows-1251", urldecode($_GET["house_letter"])), 0, 5);
	$get_house_letter_sql = mysql_real_escape_string($get_house_letter);
	
	$get_str_name = iconv("UTF-8", "windows-1251", urldecode($_GET["str_name"]));
	$get_str_name_sql = mysql_real_escape_string($get_str_name);
	$get_str_name_id =  intval(substr($_GET["str_name_id"], 0, 11));
	$get_str_type = intval(substr($_GET["str_type"],0, 11));
	$get_district = intval(substr($_GET["district_s"],0, 11));
	
	$get_m_comp_id = intval(substr($_GET["m_comp_id"], 0, 11));
	$get_m_comp_name = substr(iconv("UTF-8", "windows-1251", urldecode($_GET["m_comp_name"])), 0, 250);
	$get_m_comp_name_sql = mysql_real_escape_string($get_m_comp_name);
	
	if ($type == "street") {
		// проверка улицы на дубликат названи€
		$str_name_data =  get_str_name_id($get_str_name_sql);

		// добавл€ем название в список названий
		if ($str_name_data["str_id"]) {
			// если зазвание уже существует - присваем его ID
			$get_str_name_id = $str_name_data["str_id"];
		}
		else {
			$sql = 'INSERT INTO `'.TABLE_CITY_STR_NAMES.'` (`str_name`) VALUES ("'.$get_str_name_sql.'");';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка добавлени€ нового названи€ улицы', '', __LINE__, __FILE__, $sql);
			};
		
			unset($str_name_data);
			$str_name_data = get_str_name_id($get_str_name_sql);
		};

//		echo $str_name_data["str_id"];


		// провер€ем есть ли уже така€ комбинаци€ улица-тип-район в базе
		$sql = 'SELECT * FROM `'.TABLE_CITY_STR.'` WHERE `str_name_id` = "'.$str_name_data["str_id"].'" AND `str_type_id` = "'.$get_str_type.'" AND `str_district_id` = "'.$get_district.'";';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к базе улиц', '', __LINE__, __FILE__, $sql);
		};
		$str_data =  $db->sql_fetchrow($result);

		// добавл€ем улицу в список улиц.
		if (!$str_data["str_id"]) {
			$sql = 'INSERT INTO `'.TABLE_CITY_STR.'` (`str_name_id`, `str_type_id`, `str_district_id`) VALUES ("'.$str_name_data["str_id"].'", "'.$get_str_type.'", "'.$get_district.'");';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка добавлени€ новой улицы', '', __LINE__, __FILE__, $sql);
			};
		// перезагружаем список улиц
			$template->assign_block_vars('swich_street_ok', array(
				'DISTRICT_ID' => $get_district
			));
		};
	}
	elseif ($type == "house") {
		
		$sql = 'SELECT * FROM `'.TABLE_CITY_STR_HOUSE.'` WHERE `str_id` = "'.$get_str_id.'" AND `house_num` = "'.$get_house_num.'" AND `house_corp` = "'.$get_house_corp.'" AND `house_letter` = "'.$get_house_letter_sql.'";';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка добавлени€ нового названи€ улицы', '', __LINE__, __FILE__, $sql);
		};
	
		$str_data =  $db->sql_fetchrow($result);
		
		if ($_GET["action"] == 'edit') {
			$sql = 'UPDATE `'.TABLE_CITY_STR_HOUSE.'` SET `house_num` = "'.$get_house_num.'", `house_corp` = "'.$get_house_corp.'", `house_letter` = "'.$get_house_letter_sql.'", `mcompany_id` = "'.$get_mng_company.'" WHERE `house_id` ="'.$house_id.'";';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка добавлени€ нового номера дома', '', __LINE__, __FILE__, $sql);
			};
			
		}
		elseif (!$str_data["house_id"]) {
			$sql = 'INSERT INTO `'.TABLE_CITY_STR_HOUSE.'` (`str_id`, `house_num`, `house_corp`, `house_letter`, `mcompany_id`) VALUES ("'.$get_str_id.'", "'.$get_house_num.'", "'.$get_house_corp.'", "'.$get_house_letter_sql.'", "'.$get_mng_company.'");';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка добавлени€ нового номера дома', '', __LINE__, __FILE__, $sql);
			};
		};
		// перезагружаем список улиц
		$template->assign_block_vars('swich_house_ok', array(
			'STR_ID' => $get_str_id
		));		
	}
	elseif ($type == "m_comp") {
		// управл€ющие компании
		if ($_GET["action"] == "edit") {
			$sql = 'UPDATE `'.TABLE_GOV_MCOMPANY_NAME.'` SET `company_name` = "'.$get_m_comp_name_sql.'" WHERE `company_id` = "'.$get_m_comp_id.'" ;';
		}
		else {
			$sql = 'INSERT INTO `'.TABLE_GOV_MCOMPANY_NAME.'` (`company_name`) VALUES ("'.$get_m_comp_name_sql.'");';
		};
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка редактировани€ добавлени€ управл€ющие компании', '', __LINE__, __FILE__, $sql);
		};
		echo "ќбновите страницу.";
		exit;
//echo $sql;
	};


//	echo "sdfsdf";
//echo $_SERVER["QUERY_STRING"];
//	echo $_GET["str_type"];
//	echo $_GET["str_name"];

//iconv("UTF-8", "Windows1251", urldecode($_GET["str_name"]));

//echo	iconv("UTF-8", "windows-1251", urldecode($get_str_name));
};

if ($userdata['user_level'] >0 and !$save)
{
	if ($type == "street") {

		if ($_GET["action"] == 'edit') {
			// выбираем улицу дл€ редактировани€
			$sql = 'SELECT `s`.`str_id` , `s`.`str_name_id` , `s`.`str_type_id` , `s`.`str_district_id` , `sn`.`str_name` '.
					'FROM `revizor_city_str` AS `s` '.
					'INNER JOIN `revizor_city_str_names` AS `sn` ON `sn`.`str_id` = `s`.`str_name_id` '.
					'WHERE `s`.`str_id`="'.$str_id.'";';
	
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
				};
		
			$str_data = $db->sql_fetchrow($result);
			// позмен€ем переменные если редактирование
			$district_id = $str_data["str_district_id"];
			$str_type_id = $str_data["str_type_id"];
		};

		$template->assign_block_vars('swich_street', array(
			'STR_NAME' => @$str_data["str_name"],
			'STR_NAME_ID' => @$str_data["str_id"]
		));

		$sql = 'SELECT * FROM `'.TABLE_CITY_DISTRICT.'` ORDER BY `district_name` ASC';
	
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
			};
	
		$district_data = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$district_data[] =  $row;
		};
	
		$i=0;
		while ( $district_data[$i]["district_id"]) {
			$district_selected = ($district_data[$i]["district_id"] == $district_id) ? ' selected' : '';
			
			$template->assign_block_vars('swich_street.district_data', array(
				'DISTRICT_ID' => $district_data[$i]["district_id"],
				'DISTRICT_NAME' => htmlspecialchars($district_data[$i]["district_name"]),
				'DISTRICT_SEL' => $district_selected
			));
	
			$i++;
		};

		//
		// тип улиц
		//
		$sql = 'SELECT * FROM `'.TABLE_CITY_STR_TYPE.'` ORDER BY `type_id` ASC';
	
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
			};
	
		$str_type_data = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$str_type_data[] =  $row;
		};
	
		$i=0;
		while ( $str_type_data[$i]["type_id"]) {
			$str_type_selected = ($str_type_data[$i]["type_id"] == $str_type_id) ? ' selected' : '';
			
			$template->assign_block_vars('swich_street.str_type', array(
				'STR_TYPE_ID' => $str_type_data[$i]["type_id"],
				'STR_TYPE_NAME_FULL' => $str_type_data[$i]["type_name_full"],
				'STR_TYPE_SEL' => $str_type_selected
			));
	
			$i++;
		};
		
	}
	elseif ($type == "house") {
		$sql = 'SELECT `str`.`str_id` , `str_n`.`str_name` , `str_t`.`type_name_short` , `d`.`district_name` '.
					'FROM `revizor_city_str` AS `str` '.
					'INNER JOIN `revizor_city_str_names` AS `str_n` ON `str_n`.`str_id` = `str`.`str_name_id` '.
					'INNER JOIN `revizor_city_str_type` AS `str_t` ON `str_t`.`type_id` = `str`.`str_type_id` '.
					'INNER JOIN `revizor_city_district` AS `d` ON `d`.`district_id` = `str`.`str_district_id` '.
					'WHERE `str`.`str_id` = "'.$str_id.'";';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
			};
	
		$str_data = array();
		$str_data =  $db->sql_fetchrow($result);

		$template->assign_block_vars('swich_house', array(
			'STR_ID' => $str_data["str_id"],
			'STR_NAME' => htmlspecialchars($str_data["str_name"]),
			'STR_TYPE_NAME_SHORT' => htmlspecialchars($str_data["type_name_short"]),
			'DISTRICT_NAME' => htmlspecialchars($str_data["district_name"])
			));

		$sql = 'SELECT * FROM `'.TABLE_GOV_MCOMPANY_NAME.'` ORDER BY `company_name` ASC;';

		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к таблице названий управл€ющих компаний', '', __LINE__, __FILE__, $sql);
			};
	
		$m_company_data = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$m_company_data[] =  $row;
		};
	
		if ($_GET["action"] == 'edit') {
			$sql = 'SELECT * FROM `'.TABLE_CITY_STR_HOUSE.'` WHERE `house_id` = "'.$house_id.'";';

			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка доступа к таблице улиц', '', __LINE__, __FILE__, $sql);
			};
	
			$house_data = array();
			$house_data =  $db->sql_fetchrow($result);
			
			$house_corp = ($house_data["house_corp"]) ? $house_data["house_corp"] : '';

			$template->assign_vars(array(
				'HOUSE_ID' => $house_data["house_id"],
				'HOUSE_NUM' => $house_data["house_num"],
				'HOUSE_CORP' => $house_corp,
				'HOUSE_LETTER' => htmlspecialchars($house_data["house_letter"]),
				'ACTION' => $_GET["action"]
			));


		};
		
		$i=0;
		while ( $m_company_data[$i]["company_id"]) {
			$company_selected = ($m_company_data[$i]["company_id"] == $house_data["mcompany_id"]) ? ' selected' : '';
			
			$template->assign_block_vars('swich_house.m_company', array(
				'M_COMPANY_ID' => $m_company_data[$i]["company_id"],
				'M_COMPANY_NAME' => htmlspecialchars($m_company_data[$i]["company_name"]),
				'M_COMPANY_SEL' => $company_selected
			));
	
			$i++;
		};
		


	}
	elseif ($type == "m_comp") {
		
		$sql = 'SELECT * FROM `'.TABLE_GOV_MCOMPANY_NAME.'` WHERE `company_id` = "'.$m_comp_id.'";';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

		$m_company_data =  $db->sql_fetchrow($result);

		$template->assign_block_vars('swich_m_comp', array(
			'M_COMPANY_ID' => $m_company_data["company_id"],
			'M_COMPANY_NAME' => htmlspecialchars($m_company_data["company_name"])
		));

		$template->assign_vars(array(
			'ACTION' => $_GET["action"]
		));
	};



/*
*/
};


$template->assign_vars(array(
	'TEXT' => $text
	));



//
// Generate the page
//
$template->pparse('body');


?>

