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

// Конфигурация



// Константы модуля

//define('TABLE_CITY_CONFIG', $table_prefix.'city_config');	// 

define('TABLE_CITY_DISTRICT', $table_prefix.'city_district');	// 
define('TABLE_CITY_STR_HOUSE', $table_prefix.'city_str_house');	// 
define('TABLE_CITY_STR_NAMES', $table_prefix.'city_str_names');	// 
define('TABLE_CITY_STR_TYPE', $table_prefix.'city_str_type');	// 
define('TABLE_CITY_STR', $table_prefix.'city_str');	// 


define('TABLE_GOV_MCOMPANY_DATA', $table_prefix.'gov_mcompany_data');	// 
define('TABLE_GOV_MCOMPANY_NAME', $table_prefix.'gov_mcompany_name');	// 
define('TABLE_GOV_MCOMPANY_VOTE', $table_prefix.'gov_mcompany_vote');	// 



// конфиг базы планет
function vg_config() {
	global $db;

	$sql = 'SELECT * FROM `' . TABLE_CITY_CONFIG . '`';
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$config = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$config[$row["config_name"]] =  $row["config_value"];

	};
	return $config;
};


?>