<?php
/***************************************************************************
 *                                vg_config.php
 *                            -------------------
 *   begin                : Feb 25, 2011
 *   copyright            : (C) 2010-2011 The R2D2 Group
 *
 *   $Id: vg_config.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   License
 *
 ***************************************************************************/

//  онфигураци€
if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
};

//ѕользователи имеющие право редактировани€ и аплоада баз
$vg_privilegies["vg_admin_groups"] = array (2,20); 	// userlevel
$vg_privilegies["vg_admin_users"] = array ("all"); // ограничить до конкретного пользовател€ (all - нет ограничений)

// редакторы базы аль€нсов.
$vg_privilegies["vg_editors_groups"] = array (2, 11);

$vg_privilegies["vg_user_groups"] = array ("all"); 	// userlevel

// автозамена некоторых названий или еще чего.
$predefinded_var = array("[color:red]без названи€[/color]" => 1);

//  онстанты модул€
define('TABLE_VG_CONFIG', $table_prefix.'vg_config');	// 

define('TABLE_VG_PLANET_NAME', $table_prefix.'vg_planet_name');	// 
define('TABLE_VG_PLANETS', $table_prefix.'vg_planets');				// 
define('TABLE_VG_ALLIANCE', $table_prefix.'vg_alliance');	// 
define('TABLE_VG_GAMERS', $table_prefix.'vg_gamers');		// 
define('TABLE_VG_GAMERS_ALLIANCE', $table_prefix.'vg_gamers_alliance');	// 
define('TABLE_VG_FLEET', $table_prefix.'vg_fleet');		// 
define('TABLE_VG_ID_HISTORY', $table_prefix.'vg_id_history');		// 

define('TABLE_VG_TEMP_PLANETS', $table_prefix.'vg_temp_planets');	// 
define('TABLE_VG_TEMP_MYPLANETS', $table_prefix.'vg_temp_myplanets');	// 
define('TABLE_VG_TEMP_REPORTS', $table_prefix.'vg_temp_reports');	// 

define('TABLE_VG_SCAN_TMP', $table_prefix.'vg_scan_temp');	// 
define('TABLE_VG_SCAN', $table_prefix.'vg_scan');	// 



// конфиг базы планет
function vg_config() {
	global $db;

	$sql = 'SELECT * FROM `' . TABLE_VG_CONFIG . '`';
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$vg_config = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$vg_config[$row["vg_config_name"]] =  $row["vg_config_value"];

	};
	return $vg_config;
};


// функции
// преобразование координат
function coords($coords, $type) {
	// $coords - координаны
	// $type = "short" - без пробелов
	//         "mono" - с выравниванием пробелами
	
	$nbsp = ($type == "short") ? "" : "&nbsp;";
	$ret = "";
	
//	$string_len = ;
	
	$hex = 1048576;
	$G = floor($coords / $hex);
	$S = floor(($coords - $G*$hex) / 16);
	$P = ((($coords - $G*$hex) / 16)  - floor(($coords - $G*$hex) / 16))*16 +1 ;


	$G = (strlen($G)==1) ? $nbsp.$nbsp.$G : $G;
	$system_len = strlen($S);
	$planet_len = strlen($P);

	$add_space_cnt = 4 + (2-$planet_len)*2 + (4-$system_len)*2;
	$add_spaces = "";
	$i=1;
	while ($i<= $add_space_cnt) {
		$add_spaces .= $nbsp; 
		$i++;
	};
	
	$ret = $G." : ".$S." : ".$P . $add_spaces;
//	$coords_len = strlen($ret);
	
	return $ret;
};

 function coords2($coords) {
	$ret = "";
	$hex = 1048576;
	$G = floor($coords / $hex);
	$S = floor(($coords - $G*$hex) / 16);
	$P = ((($coords - $G*$hex) / 16)  - floor(($coords - $G*$hex) / 16))*16 +1 ;
	$ret = $G." : ".$S." : ".$P;
	return $ret;
};

?>