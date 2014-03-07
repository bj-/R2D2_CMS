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

$site_prop = TRUE;

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = $lang['Index'];
include($DRoot . '/includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'admin/city/city_index.tpl')
//	'body' => 'dynamic/city/city_search.tpl')
);

$template->assign_block_vars('switch_left_menu', array());

if ($userdata['user_level'] >0)
{
	$template->assign_block_vars('switch_menu', array());

// выбор района
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

	//¬ыбор управл€ющей компании дл€ редактировани€/добавлени€
	$sql = 'SELECT * FROM `'.TABLE_GOV_MCOMPANY_NAME.'` ORDER BY `company_name` ASC;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$m_company_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$m_company_data[] =  $row;
	};

	$template->assign_block_vars('swich_m_comp', array());

	$i=0;
	while ( $m_company_data[$i]["company_id"]) {

		$template->assign_block_vars('swich_m_comp.m_comp_data', array(
			'M_COMPANY_ID' => $m_company_data[$i]["company_id"],
			'M_COMPANY_NAME' => $m_company_data[$i]["company_name"]
		));

		$i++;
	};

};


$template->assign_vars(array(
	'TEXT' => $article_data[0]['article_text']
	));



//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>

