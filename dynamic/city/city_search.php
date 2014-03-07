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

//$site_prop = TRUE;

$type = substr($_GET["type"], 0, 15);
$district_id = preg_replace('/[^0-9]+/', '', substr($_GET["district_id"],0, 11));
$str_id = preg_replace('/[^0-9]+/', '', substr($_GET["str_id"],0, 11));
$house_id = preg_replace('/[^0-9]+/', '', substr($_GET["house_id"],0, 11));
$company_id = preg_replace('/[^0-9]+/', '', substr($_GET["company_id"],0, 11));

//echo $_SERVER["QUERY_STRING"];
//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = $lang['Index'];

$template->set_filenames(array(
	'body' => 'dynamic/city/city_search.tpl')
);


if ($type == "c_district") {

	$sql = 'SELECT * FROM `'.TABLE_CITY_DISTRICT.'` ORDER BY `district_name` ASC';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$district_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$district_data[] =  $row;
	};

	$template->assign_block_vars('swich_district', array());

	$i=0;
	while ( $district_data[$i]["district_id"]) {
		$template->assign_block_vars('swich_district.district_data', array(
			'DISTRICT_ID' => $district_data[$i]["district_id"],
			'DISTRICT_NAME' => $district_data[$i]["district_name"]
		));

		$i++;
	};
}
elseif ($type == "c_street") {

//	$sql = 'SELECT * FROM `'.TABLE_CITY_STR_NAMES.'` WHERE `district_id` = "'.$district_id.'" ORDER BY `str_name` ASC;';

	$sql = 'SELECT `str`.`str_id`, `str`.`str_name_id` , `str`.`str_type_id` , `str_t`.`type_name_short` , `str_t`.`type_name_full` , `str_n`.`str_name` '.
			'FROM `'.TABLE_CITY_STR.'` AS `str` '.
			'INNER JOIN `'.TABLE_CITY_STR_NAMES.'` AS `str_n` ON `str_n`.`str_id` = `str`.`str_name_id` '.
			'INNER JOIN `'.TABLE_CITY_STR_TYPE.'` AS `str_t` ON `str_t`.`type_id` = `str`.`str_type_id` '.
			'WHERE `str`.`str_district_id` = "'.$district_id.'" '.
			'ORDER BY `str_n`.`str_name` ASC;';


	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$street_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$street_data[] =  $row;
	};

	$template->assign_block_vars('swich_street', array());
	if ($_GET["edit"] <> 'none') {
		$template->assign_block_vars('swich_street.street_edit', array());
	};
	

	$i=0;
	while ( $street_data[$i]["str_id"]) {
		$template->assign_block_vars('swich_street.street_data', array(
			'STREET_ID' => $street_data[$i]["str_id"],
			'STREET_NAME' => htmlspecialchars($street_data[$i]["str_name"]),
			'STREET_TYPE_SHORT' => $street_data[$i]["type_name_short"]
		));

		$i++;
	};
}
elseif ($type == "c_house") {
	$sql = 'SELECT * FROM `'.TABLE_CITY_STR_HOUSE.'` WHERE `str_id` = "'.$str_id.'" ORDER BY `house_num` ASC';;

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$house_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$house_data[] =  $row;
	};
	$template->assign_block_vars('swich_house', array());
	if ($_GET["edit"] <> 'none') {
		$template->assign_block_vars('swich_house.house_edit', array());
	};
	
	$i=0;
	while ( $house_data[$i]["str_id"]) {
		$house_corp = ($house_data[$i]["house_corp"]) ? ' корп.'.$house_data[$i]["house_corp"] : "";
		$template->assign_block_vars('swich_house.house_data', array(
			'HOUSE_ID' => $house_data[$i]["house_id"],
			'HOUSE_NUM' => $house_data[$i]["house_num"],
			'HOUSE_CORP' => $house_corp,
			'HOUSE_LETTER' => $house_data[$i]["house_letter"],
			'M_COMPANY' => $house_data[$i]["mcompany_id"]
		));

		$i++;
	};

//	echo "ljvfsadsad";
}
elseif ($type == "c_mcomp_sel") {
	$sql = 'SELECT `mcn`.`company_id`, `mcn`.`company_name` '.
		'FROM `revizor_gov_mcompany_district` AS `md` '.
		'INNER JOIN `'.TABLE_GOV_MCOMPANY_NAME.'` AS `mcn` ON `mcn`.`company_id` = `md`.`mcompany_id` '.
		'WHERE `md`.`district_id` = "'.$district_id.'" '.
		'ORDER BY `mcn`.`company_name` ASC;';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$company_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$company_data[] =  $row;
	};
	$template->assign_block_vars('swich_m_company_select', array());
	if ($_GET["edit"] <> 'none') {
//		$template->assign_block_vars('swich_house.house_edit', array());
	};
	
	$i=0;
	while ( $company_data[$i]["company_id"]) {
		$template->assign_block_vars('swich_m_company_select.company_data', array(
			'COMPANY_ID' => $company_data[$i]["company_id"],
			'COMPANY_NAME' => $company_data[$i]["company_name"],
		));

		$i++;
	};
//echo $type;
}
elseif ($type == "m_company" and ($house_id or $company_id)) {
	
	if ($company_id) {
		$sql = 'SELECT * FROM `'.TABLE_GOV_MCOMPANY_NAME.'` WHERE `company_id` ="'.$company_id.'"; ';
	}
	else {
	$sql = 'SELECT `mcn`.`company_id` , `mcn`.`company_name` , `h`.`house_id` '.
			'FROM `'.TABLE_CITY_STR_HOUSE.'` AS `h` '.
			'INNER JOIN `'.TABLE_GOV_MCOMPANY_NAME.'` AS `mcn` ON `mcn`.`company_id` = `h`.`mcompany_id` '.
			'WHERE `h`.`house_id` = "'.$house_id.'";';
	};
//echo $sql;
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$m_comp_data = array();
	$m_comp_data = $db->sql_fetchrow($result);


	/*====================
	     √лосовалка
	=====================*/
	// провер€ем голосовал ли клиент за последине сутки
	$sql = 'SELECT `vote_id` FROM `'.TABLE_GOV_MCOMPANY_VOTE.'` WHERE `ipaddress` = "'.$_SERVER["REMOTE_ADDR"].'" AND `votedate` >= "'.(mktime()-86600).'" LIMIT 0,1;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};
	$row = $db->sql_fetchrow($result);
	$m_comp_is_vote = ($row["vote_id"]) ? TRUE : FALSE;
	
	// —охран€ем голос, если он был
	$vote = substr(intval($_GET["vote"]),0,1);
	if ($vote <=5 and $vote >0 and !$m_comp_is_vote) {
		$sql = 'INSERT INTO `'.TABLE_GOV_MCOMPANY_VOTE.'` '.
				'(`mcompany_id`, `mcompany_vote`, `ipaddress`, `votedate`) '.
				'VALUES '.
				'("'.$m_comp_data["company_id"].'", "'.$vote.'", "'.$_SERVER["REMOTE_ADDR"].'", "'.mktime().'");';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка внесени€ голоса', '', __LINE__, __FILE__, $sql);
		};
		$m_comp_is_vote = TRUE;
	};

	// выбираем текущую оценку
	$sql = 'SELECT AVG( `mcompany_vote` ) AS `vote`, count( `vote_id` ) AS `vote_cnt` '.
			'FROM `revizor_gov_mcompany_vote` '.
			'WHERE `mcompany_id` = "'.$m_comp_data["company_id"].'";';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$m_comp_vote = array();
	$m_comp_vote = $db->sql_fetchrow($result);
	
	$mc_vote = ($m_comp_vote["vote"]) ? number_format($m_comp_vote["vote"], 2, '.', '') : "[оценка отсутсвует]";
	$mc_vote_star = number_format(($m_comp_vote["vote"] * 16), 0);
	
	$template->assign_block_vars('swich_m_company', array(
		'M_COMPANY_ID' => $m_comp_data["company_id"],
		'M_COMPANY_NAME' => $m_comp_data["company_name"],
		'M_COMPANY_ADDRESS' => $m_comp_data["mc_address"],
		'M_COMPANY_PHONE' => $m_comp_data["mc_phone"],
		'M_COMPANY_FAX' => $m_comp_data["mc_fax"],
		'M_COMPANY_MANAGER' => $m_comp_data["mc_director"],
		'M_COMPANY_VOTE' => $mc_vote,
		'M_COMPANY_VOTE_PX' => $mc_vote_star,
		'M_COMPANY_VOTE_COUNT' => $m_comp_vote["vote_cnt"],
	));
	if (!$m_comp_is_vote) {
		$template->assign_block_vars('swich_m_company.mc_vote_form', array());
	};
	
};
//echo $type;

$template->assign_vars(array(
	'TEXT' => $text
	));



//
// Generate the page
//
$template->pparse('body');

?>

