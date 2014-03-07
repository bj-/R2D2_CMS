<?php
/***************************************************************************
 *                                ads_config.php
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

/*
//ѕользователи имеющие право редактировани€ и аплоада баз
$vg_privilegies["vg_admin_groups"] = array (2); 	// userlevel
$vg_privilegies["vg_admin_users"] = array ("all"); // ограничить до конкретного пользовател€ (all - нет ограничений)

// редакторы базы аль€нсов.
$vg_privilegies["vg_editors_groups"] = array (2, 11);
$vg_privilegies["vg_user_groups"] = array ("all"); 	// userlevel

// автозамена некоторых названий или еще чего.
$predefinded_var = array("[color:red]без названи€[/color]" => 1);
*/


//  онстанты модул€
define('TABLE_VG_CONFIG', $table_prefix.'vg_config');	// 

define('TABLE_ADS_BANNER', $table_prefix.'ads_banners');	// 
define('TABLE_ADS_CAT', $table_prefix.'ads_category');				// 


// конфиг баннерной системы
function ads_config() {
/*	global $db;

	$sql = 'SELECT * FROM `' . TABLE_VG_CONFIG . '`';
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$vg_config = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$vg_config[$row["vg_config_name"]] =  $row["vg_config_value"];

	};
	return $vg_config;
	*/
};

?>