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
include("../../../includes/config.php");
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
//echo "<pre>";
//print_r($_GET);
$get_id = substr($_GET['id'],0, 250);
$get_search_type = $_GET["ftype"];
$from_id = preg_replace('/[^0-9]+/', '', substr($_GET['from_id'],0, 20));

// в зависимости от того как мы получили входные координаты планет.
if ($_GET["coordstart"]) {
	$coord_start = preg_replace('/[^0-9]+/', '', substr($_GET['coordstart'],0, 20)); 
	$coord_end = preg_replace('/[^0-9]+/', '', substr($_GET['coordend'],0, 20));
}
else {
	$get_gstart = preg_replace('/[^0-9]+/', '', substr($_GET['gstart'],0, 2));
	$get_gend = preg_replace('/[^0-9]+/', '', substr($_GET['gend'],0, 2));
	$get_sstart = preg_replace('/[^0-9]+/', '', substr($_GET['sstart'],0, 5));
	$get_send = preg_replace('/[^0-9]+/', '', substr($_GET['send'],0, 5));
	// пересчитываем координаты обратно в их формат.
	$hex = 1048576;
	$coord_start = $get_gstart * $hex + $get_sstart * 16;
//	if (($get_gstart+1) <$get_gend) {
//		$coord_end = ($get_gstart+1) * $hex + $get_sstart * 16 + 14; // конечная координата + 1 галактика. если даипазон указан больший.
//	}
//	else {
	$coord_end = $get_gend * $hex + $get_send * 16 + 14;
//	};
};

if ($get_search_type == "id") {
	$get_id = preg_replace('/[^0-9]+/', '', $get_id); // килл всего проме цифр.
}
elseif ($get_search_type == "all") {
	$get_id = iconv("UTF-8", "windows-1251", urldecode($get_id));
	$get_id = str_replace('"', '\"', $get_id);
}
elseif ($get_search_type == "allid") {
	$get_id = preg_replace('/[^0-9]+/', '', $get_id); // килл всего проме цифр.
}
elseif ($get_search_type == "planet") {
	$get_id = iconv("UTF-8", "windows-1251", urldecode($get_id));
	$get_id = mysql_real_escape_string($get_id);

};

// массив для выбора aw / жив / протект
$att_cnt = array( 0 => "X", 1 => "V", "999999" => "aw" );
$att_cnt_long = array( 0 => "Протект", 1 => "Актив", "999999" => "aw" );

function search_gid ($id) {
	// поиск ИД игрока по ID социальной сети
	global $db;
	
	$sql = "SELECT * FROM `" . TABLE_VG_GAMERS .'` WHERE `gamers_socialnet_id`="'.$id.'" OR `gamers_socialnet_id`="-'.$id.'"';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База ID отсутствует', '', __LINE__, __FILE__, $sql);
		};
	return $db->sql_fetchrow($result);
};

function search_max_fleet($id) {
	global $db;
	
	$sql = 'SELECT MAX(`scan_date`) AS `scan_date`, MAX(`f_sh`) AS `f_sh`, MAX(`f_tr`) AS `f_tr`, MAX(`f_fi`) AS `f_fi`, MAX(`f_at`) AS `f_at`, MAX(`f_kr`) AS `f_kr`, '.
			'MAX(`f_fr`) AS `f_fr`, MAX(`f_pp`) AS `f_pp`, MAX(`f_kl`) AS `f_kl`, MAX(`f_rz`) AS `f_rz`, MAX(`f_bm`) AS `f_bm`, '.
			'MAX(`f_en`) AS `f_en`, MAX(`f_klbk`) AS `f_klbk`, MAX(`f_raz`) AS `f_raz`, MAX(`f_g`) AS `f_g` '.
			'FROM `' . TABLE_VG_FLEET .'` WHERE `vkid`="'.$id.'"';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База ID отсутствует', '', __LINE__, __FILE__, $sql);
		};
	$fleet = $db->sql_fetchrow($result);
	
	$sql = 'SELECT MAX(`scan_date`) AS `scan_date`,  MAX(`scan_date_add`) AS `scan_date_add`,  '.
			'MAX(`f_sh`) AS `f_sh`,  MAX(`f_tr`) AS `f_tr`,  MAX(`f_fi`) AS `f_fi`,  MAX(`f_at`) AS `f_at`,  MAX(`f_kr`) AS `f_kr`,  MAX(`f_fr`) AS `f_fr`,  MAX(`f_pp`) AS `f_pp`,  MAX(`f_kl`) AS `f_kl`,  MAX(`f_rz`) AS `f_rz`,  MAX(`f_bm`) AS `f_bm`,  MAX(`f_en`) AS `f_en`,  MAX(`f_klbk`) AS `f_klbk`,  MAX(`f_raz`) AS `f_raz`,  MAX(`f_g`) AS `f_g` '.
			'FROM `' . TABLE_VG_SCAN .'` '.
			'WHERE `vkid`="'.$id.'"';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База ID отсутствует', '', __LINE__, __FILE__, $sql);
		};
	$fleet["scan"] = $db->sql_fetchrow($result);

//	print_r($fleet);
	return $fleet;
};


function search_coord($id) {
	// по ID игрока ищем коры
	global $db;
/*	
	$sql = "SELECT `p`.`planet_coord`, `n`.`planet_name`, `p`.`update_date`, `p`.`is_del`, `p`.`scan_date`, ".
			'`p`.`d_rb`, `p`.`d_ir`, `p`.`d_uv`, `p`.`d_gr`, `p`.`d_f`, `p`.`d_l`, `p`.`d_mk`, `p`.`d_bk`, '.
			'`p`.`b_ti`, `p`.`b_si`, `p`.`b_kol`, `p`.`b_energy`, `p`.`b_anih`, `p`.`b_robo`, `p`.`b_nano`, '.
			'`p`.`b_doc`, `p`.`b_sti`, `p`.`b_ssi`, `p`.`b_sam`, `p`.`b_nc`, `p`.`b_pen`, `p`.`b_pbase`, `p`.`b_cnt`, `p`.`b_tp` '.
			'FROM `' . TABLE_VG_PLANETS .'` AS `p` '.
			'INNER JOIN `'.TABLE_VG_PLANET_NAME.'` AS `n` ON `p`.`planet_name_id` = `n`.`name_id` '.
			'WHERE `gamer_id`="'.$id.'" '.
			'ORDER BY `p`.`planet_coord`;';
*/
	$sql = 'SELECT * '.
			'FROM `' . TABLE_VG_PLANETS .'` '.
			'WHERE `gamer_id`="'.$id.'" and `planet_coord` < "6291472" '.
			'ORDER BY `planet_coord`;';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База планет отсутствует', '', __LINE__, __FILE__, $sql);
		};

	$planet_data = array();
	$name_id = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$planet_data[] = $row;
		$name_id[] = $row["planet_name_id"];
	};
	if (count($name_id)) {
		$sql = 'SELECT * FROM `'.TABLE_VG_PLANET_NAME.'` WHERE `name_id` IN ('.implode(", ", $name_id).')';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'База планет отсутствует', '', __LINE__, __FILE__, $sql);
			};
		$names = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$names[$row["name_id"]] = $row["planet_name"];
		};
	};

	$i=0;
	while ($planet_data[$i]["planet_coord"]) {
		$planet_data[$i]["planet_name"] = $names[$planet_data[$i]["planet_name_id"]];
		$i++;
	};
	
	return $planet_data;
};

function search_coord_by_name($planet) {
	// по названию планеты игрока ищем коры
	global $db, $coord_start, $coord_end;
	  
	$sql = 'SELECT `p`.`planet_coord`, `n`.`planet_name`, `g`.`gamers_socialnet_id`, `g`.`g_leave`, `g`.`attacks_count` '.
			'FROM `' . TABLE_VG_PLANETS .'` AS `p` '.
			'INNER JOIN `'.TABLE_VG_PLANET_NAME.'` AS `n` ON `p`.`planet_name_id` = `n`.`name_id` '.
			'INNER JOIN `'.TABLE_VG_GAMERS.'` AS `g` ON `g`.`gamers_id` = `p`.`gamer_id` '.
			'WHERE `n`.`planet_name`="'.$planet.'" AND `p`.`planet_coord` BETWEEN "'.$coord_start.'" AND "'.$coord_end.'"'.
			'ORDER BY `p`.`planet_coord` '.
			'LIMIT 0,100;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База планет отсутствует', '', __LINE__, __FILE__, $sql);
		};

	$planet_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$planet_data[] = $row;
	};
	$sql = 'SELECT count(`p`.`planet_coord`) AS `total_planet`'.
			'FROM `' . TABLE_VG_PLANETS .'` AS `p` '.
			'INNER JOIN `'.TABLE_VG_PLANET_NAME.'` AS `n` ON `p`.`planet_name_id` = `n`.`name_id` '.
			'WHERE `n`.`planet_name`="'.$planet.'" AND `p`.`planet_coord` BETWEEN "'.$coord_start.'" AND "'.$coord_end.'"';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База планет отсутствует', '', __LINE__, __FILE__, $sql);
		};
	$row = $db->sql_fetchrow($result);
	$planet_data[0]["total_planet"] = $row["total_planet"];
	
	return $planet_data;

};


function search_g_alliance($id) {
	// временная привязка к альянсам.
	global $db;

	$sql = 'SELECT `a`.`alliance_name`
FROM `'.TABLE_VG_GAMERS_ALLIANCE.'` AS `ga`
LEFT JOIN `'.TABLE_VG_ALLIANCE.'` AS `a` ON `ga`.`alliance_id` = `a`.`alliance_id`
WHERE `ga`.`gamers_id` = "'.$id.'"
GROUP BY `a`.`alliance_name`';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База планет отсутствует', '', __LINE__, __FILE__, $sql);
		};

	$alliance_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$alliance_data[] = $row["alliance_name"];
	};
	return $alliance_data;
};


function search_alliance($id) {
	// ищем ID альянса
	global $db;
	
	$sql = 'SELECT `alliance_id`, `alliance_name` FROM `'.TABLE_VG_ALLIANCE.'` WHERE `alliance_name` LIKE "%'.$id.'%";';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База планет отсутствует', '', __LINE__, __FILE__, $sql);
		};

	$alliance_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$alliance_data[] = $row;
	};

	return $alliance_data;
};

function search_alliance_cross($id) {
	// пересечение альянсов (запрос SG -> В ответ он выдает список альянсов в которых были замечены акки с ала SG)
	global $db;
	
	$sql = 'SELECT DISTINCT `ga`.`alliance_id`, `a`.`alliance_name`
FROM `'.TABLE_VG_GAMERS_ALLIANCE.'` AS `ga`
INNER JOIN `'.TABLE_VG_ALLIANCE.'` AS `a` ON `ga`.`alliance_id` = `a`.`alliance_id`
WHERE `ga`.`gamers_id` IN ((
	SELECT DISTINCT ga1.`gamers_id` 
	FROM `'.TABLE_VG_GAMERS_ALLIANCE.'` AS `ga1`
	WHERE ga1.`alliance_id` = "'.$id.'"
))
ORDER BY `a`.`alliance_name` ASC';
	
//	SELECT `alliance_id`, `alliance_name` FROM `'.TABLE_VG_ALLIANCE.'` WHERE `alliance_name` LIKE "%'.$id.'%";;

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База планет отсутствует', '', __LINE__, __FILE__, $sql);
		};

	$alliance_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$alliance_data[] = $row;
	};

	return $alliance_data;
};

function ally_history($id) {
	// История Альянсов
	global $db;

	$sql = 'SELECT `ga`.`alliance_id` , `a`.`alliance_name` , `ga`.`upd_date` 
FROM `'.TABLE_VG_GAMERS_ALLIANCE.'` AS `ga` 
INNER JOIN `'.TABLE_VG_ALLIANCE.'` AS `a` ON `ga`.`alliance_id` = `a`.`alliance_id` 
WHERE `ga`.`gamers_id` = "'.$id.'"
ORDER BY `ga`.`upd_date` ASC;';
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка выборки из таблицы принадлежности альянсов либо названия альянсов', '', __LINE__, __FILE__, $sql);
		};

	$alliance_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$alliance_data[] = $row;
	};

	return $alliance_data;
};

// =================================================================
// выбираем данные из таблицы.
if ($get_search_type == "id") {
	// поиск по ID
	$id_row = search_gid($get_id);
	$planet_data = search_coord($id_row["gamers_id"]);
	$alliance_data = search_g_alliance($id_row["gamers_id"]);
	$fleet_data = search_max_fleet($get_id);
	$g_member = ($id_row["gamers_member"]) ? $id_row["gamers_member"] : "враг";
	$finded_planet = count($planet_data);
}
elseif ($get_search_type == "all") {
	// Поиск по Альянсу
	$alliance_list = search_alliance($get_id);
	$finded_all = count($alliance_list);
	$get_search_type = (count($alliance_list) == 1) ? "allid" : "all"; // если найден всего 1 ал, сразу его и покажем.
}
elseif ($get_search_type == "ally_cross") {
	// Поиск пересечений по Альянсам
	$alliance_list = search_alliance_cross($get_id);
	$finded_all = count($alliance_list);
	$get_search_type = (count($alliance_list) == 1) ? "allid" : "all"; // если найден всего 1 ал, сразу его и покажем.
	$get_search_type_req = "ally_cross"; // сохраняем оригинальный тип запроса.
}
elseif ($get_search_type == "ally_history") {
	// Поиск пересечений по Альянсам
	$id_row = search_gid($get_id);
	$alliance_data = ally_history($id_row["gamers_id"]);
	$finded_ally_history = count($alliance_data);
//	$get_search_type = (count($alliance_list) == 1) ? "allid" : "all"; // если найден всего 1 ал, сразу его и покажем.
//	$get_search_type_req = "ally_history"; // сохраняем оригинальный тип запроса.
}
elseif ($get_search_type == "planet") {
	$planet_data = search_coord_by_name($get_id);
	$finded_palanet_by_name = count($planet_data);
//	print_r($planet_data);
//	echo"sdfsd";
};


if ($get_search_type == "allid") {
	// Поиск планет входящих в альянс.
	$sql = 'SELECT `gamers_id` FROM `mrsm_vg_gamers_alliance` WHERE `alliance_id` = "'.$get_id.'" GROUP BY `gamers_id`;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База планет отсутствует', '', __LINE__, __FILE__, $sql);
		};
	$gid_list = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$gid_list[] = $row["gamers_id"];
	};
	$gid_in = implode(', ', $gid_list);
   $gid_in = (strlen($gid_in)) ? $gid_in : "0";
	
	$sql = 'SELECT `p`.`planet_coord`, `pn`.`planet_name`, `g`.`gamers_socialnet_id`, `p`.`update_date`
	FROM `' . TABLE_VG_PLANETS .'` AS `p` 
	LEFT JOIN `'.TABLE_VG_PLANET_NAME.'` AS `pn` ON `p`.`planet_name_id` = `pn`.`name_id` 
	LEFT JOIN `'.TABLE_VG_GAMERS.'` AS `g` ON `p`.`gamer_id` = g.`gamers_id`
	WHERE `p`.`gamer_id` in ('.$gid_in.') AND	`p`.`planet_coord` BETWEEN "'.$coord_start.'" AND "'.$coord_end.'" '.
	'ORDER BY `p`.`planet_coord` ASC';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База планет отсутствует', '', __LINE__, __FILE__, $sql);
		};
	$planet_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$planet_data[] = $row;
	};
	
	$finded_alliance_planet = count($planet_data);
};
	

//
// Start output of page
//
define('SHOW_ONLINE', true);


date_default_timezone_set("Europe/Moscow");

$template->set_filenames(array(
	'body' => 'dynamic/vg/vg_search.tpl')
);

if (@$finded_planet) {
	$template->assign_block_vars('switch_left_menu', array());

	$registered_user = ( $userdata['session_logged_in'] ) ? "" : '<span style="color:red; font-weight:bold;">База доступна только зарегистрированным пользователям</span>';

	$planetology = ($id_row["tech_planetology"]) ? ' (оп: '.$id_row["tech_planetology"].')' : '';

	$g_leave = ($id_row["g_leave"]) ? "ot" : "";
	$g_attack_cnt = $att_cnt_long[$id_row["attacks_count"]];
	$g_status = ($g_leave) ? $g_leave . "/" . $g_attack_cnt : $g_leave . $g_attack_cnt;
	
	$template->assign_block_vars('switch_id', array(
		'G_ID' => $id_row["gamers_socialnet_id"],
		'G_MEMBER' => $g_member,
		'PLANETS_CNT' => $finded_planet,
		'REGISTERED_USER' => $registered_user,
		'PLANET_SCAN' => $planetology,
		'G_STATUS' => $g_status
	));

	// Техи
	$tech_sum = $id_row["tech_radar"] + $id_row["tech_navigation"] + $id_row["tech_attack"] + $id_row["tech_armor"] + 
					$id_row["tech_shields"] + $id_row["tech_enegry"] + $id_row["tech_subspacemovement"] + $id_row["tech_eng_baryonic"] + 
					$id_row["tech_eng_annihilation"] + $id_row["tech_eng_subspace"] + $id_row["tech_laser"] + $id_row["tech_foton"] + 
					$id_row["tech_lepton"] + $id_row["tech_tachyonscan"] + $id_row["tech_planetology"] + $id_row["tech_vibrotron"];

	if ($tech_sum) {
		
		// права доступа
		if ($userdata['session_logged_in']) {

$f_rz = $fleet_data["f_rz"] . ($fleet_data["scan"]["f_rz"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_rz"], 0, ',', ' '). "</span>)" : "");

			$tech_radar = $id_row["tech_radar"];
			$tech_navigation = $id_row["tech_navigation"];
			$tech_attack = $id_row["tech_attack"];
			$tech_armor = $id_row["tech_armor"];
			$tech_shields = $id_row["tech_shields"];
			$tech_eng_baryonic = $id_row["tech_eng_baryonic"];
			$tech_eng_annihilation = $id_row["tech_eng_annihilation"];
			$tech_eng_subspace = $id_row["tech_eng_subspace"];

/*
	// це не фурычит
			$tech_radar = ($fleet_data["scan"]["tech_radar"]) ? $fleet_data["scan"]["tech_radar"] : $id_row["tech_radar"];
			$tech_navigation = ($fleet_data["scan"]["tech_navigation"]) ? $fleet_data["scan"]["tech_navigation"] : $id_row["tech_navigation"];
			$tech_attack = ($fleet_data["scan"]["tech_attack"]) ? $fleet_data["scan"]["tech_attack"] : $id_row["tech_attack"];
			$tech_armor = ($fleet_data["scan"]["tech_armor"]) ? $fleet_data["scan"]["tech_armor"] : $id_row["tech_armor"];
			$tech_shields = ($fleet_data["scan"]["tech_shields"]) ? $fleet_data["scan"]["tech_shields"] : $id_row["tech_shields"];
			$tech_eng_baryonic = ($fleet_data["scan"]["tech_eng_baryonic"]) ? $fleet_data["scan"]["tech_eng_baryonic"] : $id_row["tech_eng_baryonic"];
			$tech_eng_annihilation = ($fleet_data["scan"]["tech_eng_annihilation"]) ? $fleet_data["scan"]["tech_eng_annihilation"] : $id_row["tech_eng_annihilation"];
			$tech_eng_subspace = ($fleet_data["scan"]["tech_eng_subspace"]) ? $fleet_data["scan"]["tech_eng_subspace"] : $id_row["tech_eng_subspace"];
*/

		}
		else {
			$tech_radar = "??";
			$tech_navigation = "??";
			$tech_attack = "??";
			$tech_armor = "??";
			$tech_shields = "??";
			$tech_eng_baryonic = "??";
			$tech_eng_annihilation = "??";
			$tech_eng_subspace = "??";
			
		};
		$scan_date = date("d.m.Y", $id_row["scan_date"]);
//		$scan_date = date("d.m.Y", $fleet_data["scan_date"])  . ($fleet_data["scan"]["scan_date"] ? '(<span style="color:red">'.date("d.m.Y", $fleet_data["scan"]["scan_date"]). "</span>)" : "");
		
		$template->assign_block_vars('switch_tech', array(
			'TECH_PS' => $tech_radar,
			'TECH_NAVI' => $tech_navigation,
			'TECH_ATTACK' => $tech_attack,
			'TECH_ARMOR' => $tech_armor,
			'TECH_SHIELDS' => $tech_shields,
			'TECH_ENERGY' => $id_row["tech_enegry"],
			'TECH_SSM' => $id_row["tech_subspacemovement"],
			'TECH_ENG_BAR' => $tech_eng_baryonic,
			'TECH_ENG_ANN' => $tech_eng_annihilation,
			'TECH_ENG_SSE' => $tech_eng_subspace,
			'TECH_LASER' => $id_row["tech_laser"],
			'TECH_FOTON' => $id_row["tech_foton"],
			'TECH_LEPTON' => $id_row["tech_lepton"],
			'TECH_TAHYONSCAN' =>$id_row["tech_tachyonscan"],
			'TECH_PLANETOLOGY' => $id_row["tech_planetology"],
			'TECH_VIBROTRON' => $id_row["tech_vibrotron"],
			'SCAN_DATE' => $scan_date //date("d.m.Y", $id_row["scan_date"])
		));
	};

	//Флоты
	if (array_sum($fleet_data) or array_sum($fleet_data["scan"])) {
//print_r($fleet_data);
		// права доступа
		if ($userdata['session_logged_in']) {
			$f_fi = $fleet_data["f_fi"] . ($fleet_data["scan"]["f_fi"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_fi"], 0, ',', ' '). "</span>)" : "");
			$f_at = $fleet_data["f_at"] . ($fleet_data["scan"]["f_at"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_at"], 0, ',', ' '). "</span>)" : "");
			$f_kr = $fleet_data["f_kr"] . ($fleet_data["scan"]["f_kr"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_kr"], 0, ',', ' '). "</span>)" : "");
			$f_fr = $fleet_data["f_fr"] . ($fleet_data["scan"]["f_fr"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_fr"], 0, ',', ' '). "</span>)" : "");
			$f_bm = $fleet_data["f_bm"] . ($fleet_data["scan"]["f_bm"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_bm"], 0, ',', ' '). "</span>)" : "");
			$f_klbk = $fleet_data["f_klbk"] . ($fleet_data["scan"]["f_klbk"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_klbk"], 0, ',', ' '). "</span>)" : "");
			$f_raz = $fleet_data["f_raz"] . ($fleet_data["scan"]["f_raz"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_raz"], 0, ',', ' '). "</span>)" : "");
			$f_g = $fleet_data["f_g"] . ($fleet_data["scan"]["f_g"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_g"], 0, ',', ' '). "</span>)" : "");

			// сумманрая стоимость флота:
			$fleet_sum_ti = $fleet_data["f_fi"]*1 +
				$fleet_data["f_at"]*3 +
				$fleet_data["f_kr"]*20 +
				$fleet_data["f_fr"]*45 +
				$fleet_data["f_bm"]*50 +
				$fleet_data["f_klbk"]*5000 +
				$fleet_data["f_raz"]*60 +
				$fleet_data["f_g"]*30 ;
			$fleet_sum_si = $fleet_data["f_fi"]*1 +
				$fleet_data["f_at"]*1 +
				$fleet_data["f_kr"]*7 +
				$fleet_data["f_fr"]*15 +
				$fleet_data["f_bm"]*25 +
				$fleet_data["f_klbk"]*4000 +
				$fleet_data["f_raz"]*50 +
				$fleet_data["f_g"]*40 ;

			$fleet_scan_sum_ti = $fleet_data["scan"]["f_fi"]*1 +
				$fleet_data["scan"]["f_at"]*3 +
				$fleet_data["scan"]["f_kr"]*20 +
				$fleet_data["scan"]["f_fr"]*45 +
				$fleet_data["scan"]["f_bm"]*50 +
				$fleet_data["scan"]["f_klbk"]*5000 +
				$fleet_data["scan"]["f_raz"]*60 +
				$fleet_data["scan"]["f_g"]*30 ;
			$fleet_scan_sum_si = $fleet_data["scan"]["f_fi"]*1 +
				$fleet_data["scan"]["f_at"]*1 +
				$fleet_data["scan"]["f_kr"]*7 +
				$fleet_data["scan"]["f_fr"]*15 +
				$fleet_data["scan"]["f_bm"]*25 +
				$fleet_data["scan"]["f_klbk"]*4000 +
				$fleet_data["scan"]["f_raz"]*50 +
				$fleet_data["scan"]["f_g"]*40 ;

/*
			// сумманрая стоимость флота:
			$fleet_sum_ti = $fleet_data["f_fi"]*1 +
								$fleet_data["f_at"]*1 +
								$fleet_data["f_kr"]*1 +
								$fleet_data["f_fr"]*1 +
								$fleet_data["f_bm"]*1 +
								$fleet_data["f_klbk"]*1 +
								$fleet_data["f_raz"]*1 +
								$fleet_data["f_g"]*1 ;
			$fleet_sum_si = $fleet_data["f_fi"]*1 +
								$fleet_data["f_at"]*1 +
								$fleet_data["f_kr"]*1 +
								$fleet_data["f_fr"]*1 +
								$fleet_data["f_bm"]*1 +
								$fleet_data["f_klbk"]*1 +
								$fleet_data["f_raz"]*1 +
								$fleet_data["f_g"]*1 ;

			$fleet_scan_sum_ti = $fleet_data["scan"]["f_fi"]*1 +
								$fleet_data["scan"]["f_at"]*1 +
								$fleet_data["scan"]["f_kr"]*1 +
								$fleet_data["scan"]["f_fr"]*1 +
								$fleet_data["scan"]["f_bm"]*1 +
								$fleet_data["scan"]["f_klbk"]*1 +
								$fleet_data["scan"]["f_raz"]*1 +
								$fleet_data["scan"]["f_g"]*1 ;
			$fleet_scan_sum_si = $fleet_data["scan"]["f_fi"]*1 +
								$fleet_data["scan"]["f_at"]*1 +
								$fleet_data["scan"]["f_kr"]*1 +
								$fleet_data["scan"]["f_fr"]*1 +
								$fleet_data["scan"]["f_bm"]*1 +
								$fleet_data["scan"]["f_klbk"]*1 +
								$fleet_data["scan"]["f_raz"]*1 +
								$fleet_data["scan"]["f_g"]*1 ;
*/
		}
		else {
			$f_fi = "??";
			$f_at = "??";
			$f_kr = "??";
			$f_fr = "??";
			$f_bm = "??";
			$f_klbk = "??";
			$f_raz = "??";
			$f_g = "??";
		};
		
		$f_sh = $fleet_data["f_sh"] . ($fleet_data["scan"]["f_sh"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_sh"], 0, ',', ' '). "</span>)" : "");
		$f_tr = $fleet_data["f_tr"] . ($fleet_data["scan"]["f_tr"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_tr"], 0, ',', ' '). "</span>)" : "");
		$f_pp = $fleet_data["f_pp"] . ($fleet_data["scan"]["f_pp"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_pp"], 0, ',', ' '). "</span>)" : "");
		$f_kl = $fleet_data["f_kl"] . ($fleet_data["scan"]["f_kl"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_kl"], 0, ',', ' '). "</span>)" : "");
		$f_en = $fleet_data["f_en"] . ($fleet_data["scan"]["f_en"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_en"], 0, ',', ' '). "</span>)" : "");
		$f_rz = $fleet_data["f_rz"] . ($fleet_data["scan"]["f_rz"] ? '(<span style="color:red">'.number_format($fleet_data["scan"]["f_rz"], 0, ',', ' '). "</span>)" : "");

			// сумманрая стоимость флота:
			$fleet_sum_ti = $fleet_sum_ti +
				$fleet_data["f_sh"]*2 +
				$fleet_data["f_tr"]*6 +
				$fleet_data["f_pp"]*10 +
				$fleet_data["f_kl"]*10 +
				$fleet_data["f_en"]*0 +
				$fleet_data["f_rz"]*0;
			$fleet_sum_si = $fleet_sum_si +
				$fleet_data["f_sh"]*2 +
				$fleet_data["f_tr"]*6 +
				$fleet_data["f_pp"]*20 +
				$fleet_data["f_kl"]*6 +
				$fleet_data["f_en"]*2 +
				$fleet_data["f_rz"]*1;

			$fleet_scan_sum_ti = $fleet_scan_sum_ti +
				$fleet_data["scan"]["f_sh"]*2 +
				$fleet_data["scan"]["f_tr"]*6 +
				$fleet_data["scan"]["f_pp"]*10 +
				$fleet_data["scan"]["f_kl"]*10 +
				$fleet_data["scan"]["f_en"]*0 +
				$fleet_data["scan"]["f_rz"]*0;
			$fleet_scan_sum_si = $fleet_scan_sum_si +
				$fleet_data["scan"]["f_sh"]*2 +
				$fleet_data["scan"]["f_tr"]*6 +
				$fleet_data["scan"]["f_pp"]*20 +
				$fleet_data["scan"]["f_kl"]*6 +
				$fleet_data["scan"]["f_en"]*2 +
				$fleet_data["scan"]["f_rz"]*1;
/*		
					// сумманрая стоимость флота:
		$fleet_sum_ti = $fleet_sum_ti +
							$fleet_data["f_sh"]*1 +
							$fleet_data["f_tr"]*1 +
							$fleet_data["f_pp"]*1 +
							$fleet_data["f_kl"]*1 +
							$fleet_data["f_en"]*1 +
							$fleet_data["f_rz"]*1;
		$fleet_sum_si = $fleet_sum_si +
							$fleet_data["f_sh"]*1 +
							$fleet_data["f_tr"]*1 +
							$fleet_data["f_pp"]*1 +
							$fleet_data["f_kl"]*1 +
							$fleet_data["f_en"]*1 +
							$fleet_data["f_rz"]*1;
		$fleet_scan_sum_ti = $fleet_scan_sum_ti +
							$fleet_data["scan"]["f_sh"]*1 +
							$fleet_data["scan"]["f_tr"]*1 +
							$fleet_data["scan"]["f_pp"]*1 +
							$fleet_data["scan"]["f_kl"]*1 +
							$fleet_data["scan"]["f_en"]*1 +
							$fleet_data["scan"]["f_rz"]*1;
		$fleet_scan_sum_si = $fleet_scan_sum_si +
							$fleet_data["scan"]["f_sh"]*1 +
							$fleet_data["scan"]["f_tr"]*1 +
							$fleet_data["scan"]["f_pp"]*1 +
							$fleet_data["scan"]["f_kl"]*1 +
							$fleet_data["scan"]["f_en"]*1 +
							$fleet_data["scan"]["f_rz"]*1;
*/
		
		$scan_date = date("d.m.Y", $fleet_data["scan_date"])  . ($fleet_data["scan"]["scan_date"] ? '(<span style="color:red">'.date("d.m.Y", $fleet_data["scan"]["scan_date"]). "</span>)" : "");
		
		$fleet_sum = ($fleet_sum_ti+$fleet_sum_si) ? number_format(($fleet_sum_ti+$fleet_sum_si)/1000, 0, ',', ' ') . " кк " : "" ;
		$fleet_scan_sum = ($fleet_scan_sum_ti+$fleet_scan_sum_si) ? '(<span style="color:red;">'.number_format(($fleet_scan_sum_ti+$fleet_scan_sum_si)/1000, 0, ',', ' ') . " кк</span>)" : "";
		
		$template->assign_block_vars('switch_fleet', array(
			'FLEET_SUM' => $fleet_sum,
			'FLEET_SCAN_SUM' => $fleet_scan_sum,
			'FLEET_SHATTLE' => $f_sh,
			'FLEET_TRANSTORT' => $f_tr,
			'FLEET_FIGHTER' => $f_fi,
			'FLEET_ATACKER' => $f_at,
			'FLEET_KORVET' => $f_kr,
			'FLEET_FREGAT' => $f_fr,
			'FLEET_PP' => $f_pp,
			'FLEET_KOLLECTOR' => $f_kl,
			'FLEET_SPYZOND' => $f_rz,
			'FLEET_BOMBARDIER' => $f_bm,
			'FLEET_ENERGODRON' => $f_en,
			'FLEET_DEATHSTAR' => $f_klbk,
			'FLEET_RAZR' => $f_raz,
			'FLEET_GALLACTIONE' => $f_g,
			'SCAN_DATE' =>  $scan_date
		));
		
	};
	
	if ($id_row["gamers_socialnet_id"] > 0) {	// текст показываем толкьое если пользователь из вконтакта.
		$template->assign_block_vars('switch_id.swich_vk_module', array(
			'G_ID' => $id_row["gamers_socialnet_id"]
		));
	};

	// временная привязка к альсам.
	if (count($alliance_data)) {
		$template->assign_block_vars('switch_id.switch_alliance', array());
		$i = 0;
		while ($alliance_data[$i]) {
			$ally_name = ($userdata['session_logged_in']) ?  $alliance_data[$i] : "?????????"; 
			$template->assign_block_vars('switch_id.switch_alliance.alliance_list', array(
				'ALLIANCE' => $ally_name
			));
			
			$i++;
		};
	};



	$template->assign_block_vars('switch_coords', array());

	
	$i = 0;
	while ($planet_data[$i]["planet_coord"]) {
		$planet_name = ( $userdata['session_logged_in'] ) ? $planet_data[$i]["planet_name"] : substr($planet_data[$i]["planet_name"],0,2) . "...";
		
		$planet_name_add_space_cnt = (15-strlen($planet_name))*2;
		
		$planet_add_space = "";
		$ip=0;
		while ($ip<=$planet_name_add_space_cnt) {
			$planet_add_space .= "&nbsp;";
			$ip++;
		};
		$planet_name = $planet_name . $planet_add_space;
		
		$planet_is_del = ($planet_data[$i]["is_del"]) ? TRUE : FALSE;

		if ( $userdata['session_logged_in'] ) {
			$planet_coord = coords($planet_data[$i]["planet_coord"], "mono");
		}
		else {
			$planet_coord = "?&nbsp;:&nbsp;????&nbsp;:&nbsp;?&nbsp;&nbsp;&nbsp;";
		};

		$scan_date = ($planet_data[$i]["scan_date"]) ? 
				'<div style="text-align:right;">&nbsp;&nbsp;&nbsp;Скан от: '.date("d.m.Y", $planet_data[$i]["scan_date"]) . "</div>" : '<div style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Скан отсутсвует</div>';
		
		$defence  = "Оборона: ";
		$defence_len = strlen($defence);
		$defence .= ($planet_data[$i]["d_rb"]) ? $planet_data[$i]["d_rb"] ."рб, " : "";
		$defence .= ($planet_data[$i]["d_ir"]) ? $planet_data[$i]["d_ir"] ."ик, " : "";
		$defence .= ($planet_data[$i]["d_uv"]) ? $planet_data[$i]["d_uv"] ."уф, " : "";
		$defence .= ($planet_data[$i]["d_gr"]) ? $planet_data[$i]["d_gr"] ."го, " : "";
		$defence .= ($planet_data[$i]["d_f"]) ? $planet_data[$i]["d_f"] 	."фп, " : "";
		$defence .= ($planet_data[$i]["d_l"]) ? $planet_data[$i]["d_l"] 	."лп, " : "";
		$defence .= ($planet_data[$i]["d_mk"]) ? "МК, " : "";
		$defence .= ($planet_data[$i]["d_bk"]) ? "БК" : "";
		// Подрезаем последнюю запятую
		$defence = (strlen($defence) == (strrpos($defence,",")+2)) ? substr($defence, 0, strrpos($defence,","))  : $defence; 
		$defence = (strlen($defence) == $defence_len) ? "" : '<div>&nbsp;&nbsp;&nbsp;'.$defence . "</div>";

		$buildings  = "Постройки: ";
		$buildings_len = strlen($buildings);
		$buildings .= ($planet_data[$i]["b_nano"])	? "Нано:".$planet_data[$i]["b_nano"]	.", " : "";
		$buildings .= ($planet_data[$i]["b_doc"])		? "Док:"	.$planet_data[$i]["b_doc"]		.", " : "";
		$buildings .= ($planet_data[$i]["b_robo"])	? "Робо:".$planet_data[$i]["b_robo"]	.", " : "";
		$buildings .= ($planet_data[$i]["b_nc"])		? "Нц:"	.$planet_data[$i]["b_nc"]		.", " : "";
		$buildings .= ($planet_data[$i]["b_pen"])		? "Пэн:"	.$planet_data[$i]["b_pen"]		.", " : "";
		$buildings .= ($planet_data[$i]["b_cnt"])		? "Цнт:"	.$planet_data[$i]["b_cnt"]		.", " : "";
		// Подрезаем последнюю запятую
		$buildings = (strlen($buildings) == (strrpos($buildings,",")+2)) ? substr($buildings, 0, strrpos($buildings,","))  : $buildings;
		$buildings = (strlen($buildings) == $buildings_len) ? "" : '<div>&nbsp;&nbsp;&nbsp;'.$buildings . "</div>";

		$battle  = "Боевые: ";
		$battle_len = strlen($battle);
		$battle .= ($planet_data[$i]["b_pbase"])	? "Заправка: "	.$planet_data[$i]["b_pbase"]	.", " : "";
		$battle .= ($planet_data[$i]["b_tp"])		? "Телепорт: "	.$planet_data[$i]["b_tp"]		.", " : "";
		$battle = (strlen($battle) == (strrpos($battle,",")+2)) ? substr($battle, 0, strrpos($battle,","))  : $battle;
		$battle = (strlen($battle) == $battle_len) ? "" : '<div>&nbsp;&nbsp;&nbsp;'.$battle . "</div>";

		$mines  = "Шахты: ";
		$mines_len = strlen($mines);
		$mines .= ($planet_data[$i]["b_ti"])		? "Ti: "	.$planet_data[$i]["b_ti"]		.", " : "";
		$mines .= ($planet_data[$i]["b_si"])		? "Si: "	.$planet_data[$i]["b_si"]		.", " : "";
		$mines .= ($planet_data[$i]["b_kol"])		? "Am: "	.$planet_data[$i]["b_kol"]		.", " : "";
		$mines .= ($planet_data[$i]["b_energy"])	? "Кол: ".$planet_data[$i]["b_energy"]	.", " : "";
		$mines .= ($planet_data[$i]["b_anih"])		? "А/р: ".$planet_data[$i]["b_anih"]	.", " : "";
		$mines = (strlen($mines) == (strrpos($mines,",")+2)) ? substr($mines, 0, strrpos($mines,","))  : $mines;
		$mines = (strlen($mines) == $mines_len) ? "" : '<div>&nbsp;&nbsp;&nbsp;'.$mines . "</div>";
		
		$store = "Склады: ";
		$store_len = strlen($store);
		$store .= ($planet_data[$i]["b_sti"])		? "Ti: ".$planet_data[$i]["b_sti"]	.", " : "";
		$store .= ($planet_data[$i]["b_ssi"])		? "Si: ".$planet_data[$i]["b_ssi"]	.", " : "";
		$store .= ($planet_data[$i]["b_sam"])		? "Am: ".$planet_data[$i]["b_sam"]	.", " : "";
		$store = (strlen($store) == (strrpos($store,",")+2)) ? substr($store, 0, strrpos($store,","))  : $store;
		$store = (strlen($store) == $store_len) ? "" : '<div>&nbsp;&nbsp;&nbsp;'.$store . "</div>";

		if ($planet_is_del) {
			$template->assign_block_vars('switch_coords.coords_list_pdel', array(
				'NUM' => "",
				'COORDS' =>   $planet_coord,
				'NAME' => $planet_name,
				'MAPDATE' => date("d.m.y", $planet_data[$i]["update_date"]),
				'DEFENCE' => $defence,
				'MINES' => $mines,
				'STORE' => $store,
				'BATTLE' => $battle,
				'BUILDINGS' => $buildings, 
				'SCAN_DATE' => $scan_date,
				'COORD_SCAN_ID' => $planet_data[$i]["planet_coord"]
			));
		}
		else {
			$template->assign_block_vars('switch_coords.coords_list', array(
				'NUM' => $i+1,
				'COORDS' =>   $planet_coord,
				'NAME' => $planet_name,
				'MAPDATE' => date("d.m.y", $planet_data[$i]["update_date"]),
				'DEFENCE' => $defence,
				'MINES' => $mines,
				'STORE' => $store,
				'BATTLE' => $battle,
				'BUILDINGS' => $buildings, 
				'SCAN_DATE' => $scan_date,
				'COORD_SCAN_ID' => $planet_data[$i]["planet_coord"]
			));
		};
		
		$i++;
	};
	
//	$template->assign_block_vars('switch_history', array());
	
}
elseif (@$finded_all) {
	$template->assign_block_vars('alliance_list', array());

	if ($get_search_type_req == "ally_cross" and $_GET["from_id"] and strlen($from_id)) {
		$from_id = ( $userdata['session_logged_in'] ) ? $from_id : "?????";

		$template->assign_block_vars('alliance_list.alliance_list_nav', array(
			'FIND_TYPE' => "ally_cross",
			'ALLIANCE_ID' => $from_id,
			'COORD_START' => $coord_start,
			'COORD_END' => $coord_end,
		));
		
//		echo $get_id; "sdfsdf";
	};
	$i = 0;
	while ($alliance_list[$i]["alliance_id"]) {
		$allyance_id = ($userdata['session_logged_in']) ? $alliance_list[$i]["alliance_id"] : "?????";
		$allyance_name = ($userdata['session_logged_in']) ? $alliance_list[$i]["alliance_name"] : substr($alliance_list[$i]["alliance_name"],0,1) . "........";

		$template->assign_block_vars('alliance_list.alliance_list_row', array(
			'FROM_ID' => $get_id,
			'COORD_START' => $coord_start,
			'COORD_END' => $coord_end,
			'ALLIANCE_ID' => $allyance_id,
			'ALLIANCE_NAME' => $allyance_name
		));
		$i++;
	};

//	echo "finded_all";
}
elseif ($finded_alliance_planet) {
	$template->assign_block_vars('alliance_planet', array(
		'TOTAL' => $finded_alliance_planet
	));
	
	$i=0;
	while ($planet_data[$i]["planet_coord"]) {

		if ( $userdata['session_logged_in'] ) {
			$planet_coord = coords($planet_data[$i]["planet_coord"], "mono");
			$gamer_id = $planet_data[$i]["gamers_socialnet_id"];
		}
		else {
			$planet_coord = "?&nbsp;:&nbsp;????&nbsp;:&nbsp;?&nbsp;&nbsp;&nbsp;";
			$gamer_id = "????????";
		};

		$template->assign_block_vars('alliance_planet.alliance_planet_list', array(
			'COORD' => $planet_coord,
			'P_NAME' => $planet_data[$i]["planet_name"],
			'G_ID' => $gamer_id,
			'UPDATE' => date("d.m.y", $planet_data[$i]["update_date"])
		));
		$i = ($i>=100) ? $finded_alliance_planet : $i;
		$i++;
	};
}
elseif (@$finded_ally_history) {
//	print_r($alliance_data);
//	$not_found = 'sdfsd';
	
	$template->assign_block_vars('switch_ally_history', array(
		));

	if ( $userdata['session_logged_in'] ) {
		$i = 0;
		$current_ally_id = 0;
		while ( $alliance_data[$i]["alliance_id"]) {
			if ($current_ally_id != $alliance_data[$i]["alliance_id"]) {
				$template->assign_block_vars('switch_ally_history.ally_list', array(
					'ALLY_ID' => $alliance_data[$i]["alliance_id"],
					'ALLY_NAME' => $alliance_data[$i]["alliance_name"],
					'ANNY_DATE' => date("d.m.y", $alliance_data[$i]["upd_date"])
				));
				$current_ally_id = $alliance_data[$i]["alliance_id"];
			};
			$i++;
		};
	}
	else {
		$not_found = '<span style="font-weight:bold; color:red;">У вас недостаточно прав на поиск данной информации</span>';
	};
}
elseif (@$finded_palanet_by_name) {
//	$planet_data
	$showed_planet = ($finded_palanet_by_name <=100) ? $finded_palanet_by_name : "100"; 
	$template->assign_block_vars('planet_by_name', array(
		'SHOWED_PLANET' => $showed_planet,
		'TOTAL_PLANET' => $planet_data[0]["total_planet"]
		));


	
	$i = 0;
	while ($planet_data[$i]["planet_coord"]) {
		$planet_name = $planet_data[$i]["planet_name"];
		// добавляем пробелы для лучшего выравнивания
		$planet_name_add_space_cnt = (15-strlen($planet_name))*2;
		$planet_add_space = "";
		$ip=0;
		while ($ip<=$planet_name_add_space_cnt) {
			$planet_add_space .= "&nbsp;";
			$ip++;
		};
		$planet_name = $planet_name . $planet_add_space;
		$planet_is_del = ($planet_data[$i]["is_del"]) ? TRUE : FALSE;

		$g_leave = ($planet_data[$i]["g_leave"]) ? "ot" : "";
		$g_attack_cnt = $att_cnt[$planet_data[$i]["attacks_count"]];
		$g_status = ($g_leave) ? $g_leave . "/" . $g_attack_cnt : $g_leave . $g_attack_cnt;

		if ( $userdata['session_logged_in'] ) {
			$planet_coord = coords($planet_data[$i]["planet_coord"], "mono");
			$g_id = $planet_data[$i]["gamers_socialnet_id"];
		}
		else {
			$planet_coord = "?&nbsp;:&nbsp;????&nbsp;:&nbsp;?&nbsp;&nbsp;&nbsp;";
			$g_id = substr($planet_data[$i]["gamers_socialnet_id"],0,1) . "....";
		};

		// добавляем пробелы для лучшего выравнивания
		$g_id_add_space_cnt = (10-strlen($g_id))*2;
		$g_id_add_space = "";
		$ip=0;
		while ($ip<=$g_id_add_space_cnt) {
			$g_id_add_space .= "&nbsp;";
			$ip++;
		};
		$g_id = $g_id . $g_id_add_space;

/*
		if ($planet_is_del) {
			$template->assign_block_vars('planet_by_name.coords_list_pdel', array(
				'NUM' => "",
				'COORDS' =>   $planet_coord,
				'NAME' => $planet_name,
				'G_ID' => $planet_data[$i]["gamers_socialnet_id"]
			));
		}
		else {
*/
			$template->assign_block_vars('planet_by_name.coords_list', array(
				'NUM' => "",
				'COORDS' =>   $planet_coord,
				'NAME' => $planet_name,
				'G_ID' => $g_id,
				'G_STATUS' => $g_status
			));
/*
		};
*/		$i = ($i>=100) ? '9999999999' : $i;
		$i++;
	};

}
else {
	$not_found = '<span style="font-weight:bold; color:red;">По вашему запросу ничего не найдено</span>';
};


$template->assign_vars(array(
//	'SAVED' => @$saved,
//	'PATCH_DESCRIPTION' => $paragrad_desc,
//	'ARTICLE' => $page_text,
	'ID' => $get_id,
//	'QSTRING' => "<br><br>".$_SERVER["QUERY_STRING"] . "<plaintext>",
	'NOT_FOUND' => $not_found
	
//	'EDIT' => @$edit
	));


//
// Generate the page
//
$template->pparse('body');

?>