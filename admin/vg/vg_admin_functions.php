<?php
/***************************************************************************
 *                                vg_admin_functions.php
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

// Проверяем права пользователя. если нет - выкидываем
privilegies_check($vg_privilegies["vg_admin_groups"], $vg_privilegies["vg_admin_users"]);

// функции администрирования
function vg_get_db_files() {
	global $vg_config, $DRoot;
	
	$upload_dir = $DRoot . $vg_config["upload_dir"];
//	$upload_dir = str_replace('www/../', '', $upload_dir);
	
	$handle = opendir($upload_dir) or die("Can't open directory!"); 

	$file_info = array();
	$files = array();
	while (false !== ($file = readdir($handle))) { 
		if (is_file($upload_dir.$file)) {
			$file_info = pathinfo($upload_dir.$file);
			if ($file_info['extension'] == 'db') {
				$files[] = array("name" => $file, "size" => filesize($upload_dir.$file));
			};
		};
	};

	closedir($handle); 
	
	asort($files);
	
	return $files;
};


function r2d2_sql_clause_max($name, $src, $target) {
	// составляем кусочик квири ' , name = "value" ' для полей где может быть значение NULL
	// выбираем макс значение из имеющихся (отчет и то что в базе)
	$val = ($src > $target)	? $src : $target;
	$ret = ($val) ? ', `'.$name.'` = "'.$val.'"' : ', `'.$name.'` = NULL';
	return $ret;
};

function r2d2_sql_clause($name, $src, $target) {
	// составляем кусочик квири ' , name = "value" ' для полей где может быть значение NULL
//	$val = ($src > $target)	? $src : $target;
	$ret = ($src) ? ', `'.$name.'` = "'.$src.'"' : ', `'.$name.'` = NULL';
	return $ret;
};

function r2d2_sql_clause_insert($val) {
	// валуе для инсерта NULL либо значение
	$ret = ($val) ? '"'.'", ' : 'NULL';
	return $ret;
};

function vg_stat() {
	global $DRoot, $vg_config;
	// 
	// ====================
	//  статистика исходной базы
	// ====================

	// конект к sqlite.db
	$sqlitedb = $DRoot . $vg_config["upload_dir"] . $vg_config["vg_curr_db"];
	$sdb = new PDO('sqlite:'.$sqlitedb);

	$data = $sdb->query('SELECT * FROM tbPlanets WHERE MyPlanet = 0 ORDER BY coords ASC LIMIT 0, 20');
	$result = $data->fetchAll();

	$ret = "";
	$ret .= "<table border='1px'><tr><th>Координаты</th><th>Название</th><th>Альянс</th><th>ID</th><th>MapDate</th><th>MyPlanet</th></tr>";
	$i=0;
	while ($result[$i][0]) {
		$planet = iconv("UTF-8", "windows-1251", $result[$i]["Name"]);
		$alliance = iconv("UTF-8", "windows-1251", $result[$i]["Allyance"]);
		
		$coord = coords($result[$i]["Coords"], "short" );
	
		$ret .=  "<tr><td style='padding-left:10px; padding-right:10px;'>" . $coord . "(".$result[$i]["Coords"].")</td><td style='padding-left:10px; padding-right:10px;'>".$planet ."</td><td style='padding-left:10px; padding-right:10px;'>".$alliance."</td><td style='padding-left:10px; padding-right:10px;'>" . $result[$i]["VKontakteID"] . "</td><td style='padding-left:10px; padding-right:10px;'>" . date("d.m.Y H:i:s", $result[$i]["MapDate"]) . "</td><td style='text-align:center;'>" . $result[$i]["MyPlanet"] . "</td></tr>";
		$i++;
	};
	$ret .=  "</table>";
	
	unset($data);
	unset($result);
	
	$ret .= "Статистика исходной базы: 
		<table>";

	$data = $sdb->query('SELECT count(Coords) AS myplanet_cnt FROM tbPlanets WHERE Coords NOT NULL ');
	$result = $data->fetchAll();
	$ret .= "<tr><td>Всего планет:</td><td>". $result[0]["myplanet_cnt"] . "</td></tr>\n\r";


	$data = $sdb->query('SELECT MIN(Coords) AS coord_min, MAX(Coords) AS coord_max FROM tbPlanets WHERE MyPlanet = 0');
	$result = $data->fetchAll();
	$ret .= "<tr><td>диапазон планет:</td><td>с " . coords($result[0]["coord_min"], "short" ) . "(" . $result[0]["coord_min"] . 
		")<br />по " .coords($result[0]["coord_max"], "short" ) . "(" . $result[0]["coord_max"] . ")</td></tr>\n\r";

	$data = $sdb->query('SELECT count(Coords) AS myplanet_cnt FROM tbPlanets WHERE MyPlanet = 1');
	$result = $data->fetchAll();
	$ret .= "<tr><td>Своих планет:</td><td>". $result[0]["myplanet_cnt"] . "</td></tr>\n\r";

	$data = $sdb->query('SELECT COUNT(Coords) AS to_import FROM tbPlanets WHERE Coords NOT NULL AND MyPlanet = 0 AND VKontakteID NOT NULL');
	$result = $data->fetchAll();
	$planet_to_import = $result[0]["to_import"];

	$data = $sdb->query('SELECT MIN(Coords) AS coord_min, MAX(Coords) AS coord_max FROM tbPlanets WHERE Coords NOT NULL AND MyPlanet = 0 AND VKontakteID NOT NULL');
	$result = $data->fetchAll();
	$planet_diapason = "с " . coords($result[0]["coord_min"], "short" ) . "(" . $result[0]["coord_min"] . 
		") по " .coords($result[0]["coord_max"], "short" ) . "(" . $result[0]["coord_max"] . ")";

	$ret .= "<tr><td>Планет: для импорта:</td><td>". $planet_to_import . ";<br />
		диапазон: ". $planet_diapason . "</td></tr>\n\r";

	$data = $sdb->query('SELECT count(Coords) AS novkid_cnt FROM tbPlanets WHERE VKontakteID IS NULL');
	$result = $data->fetchAll();
	$ret .= "<tr><td>планет без вконтактID: ". $result[0]["novkid_cnt"] . "</td></tr>\n\r";

	$data = $sdb->query('SELECT count(Coords) AS lived_cnt FROM tbPlanets WHERE AttacksCount != "999999" AND Leave = "0"');
	$result = $data->fetchAll();
	$ret .= "<tr><td>Живых планет (не в отпуске):</td><td>". $result[0]["lived_cnt"] . "</td></tr>\n\r";

	$data = $sdb->query('SELECT count(Leave) AS leave_cnt FROM tbPlanets WHERE Leave = "1"');
	$result = $data->fetchAll();
	$ret .= "<tr><td>Отпускников:</td><td>". $result[0]["leave_cnt"] . "</td></tr>\n\r";

	$ret .= "</table>";
	return $ret;
};

function import_to_tempdb($myPlanet) {
	global $DRoot,  $vg_config,  $db;
	// 
	// ====================
	//  перенос данных во временную таблицу мускула.
	// ====================

	$max_executing_time = 300;
	set_time_limit($max_executing_time);

	$ret = "";
	// конект к sqlite.db
	$sqlitedb = $DRoot . $vg_config["upload_dir"] . $vg_config["vg_curr_db"];
	$sdb = new PDO('sqlite:'.$sqlitedb);

// внесение в бд выполняем в несколько итераций из за ограничения на длину квири в 1 мб
	$sql_limit_row_start = 0;
	$sql_limit_increase = 3000; // лимит строчек
	$sql_next_quee = TRUE;
	$added_planet_cnt = 0; // счетчик добавленных плаент
	while ($sql_next_quee) {
		
		$vkid_null = ($myPlanet) ? "" : " AND VKontakteID NOT NULL ";

		$result = $sdb->query('SELECT Coords, Name, Allyance, VKontakteID, Leave, AttacksCount, MapDate, MyPlanet FROM tbPlanets WHERE Coords NOT NULL AND MyPlanet = '.$myPlanet.' '.$vkid_null.' ORDER BY Coords ASC LIMIT ' . $sql_limit_row_start . ", 10000"); // Leave Defence MyPlanet

		$name_data = $result->fetchAll();
		$import = TRUE; 
		$planet_present = 0;

		$name_import = array();
		$i = 0; 
		while (strlen($name_data[$i]["Coords"])) {
			$import_coords = $name_data[$i]["Coords"];
			$import_Name = strtolower(str_replace('"', '\"', str_replace('\\', '\\\\', iconv("UTF-8", "windows-1251", $name_data[$i]["Name"]))));
			if (!strlen($import_Name)) { // если нет названия - подставляем свое
				$import_Name = "[color:red]без названия[/color]";
			};

			$import_Allyance = str_replace('"', '\"', str_replace('\\', '\\\\', iconv("UTF-8", "windows-1251", $name_data[$i]["Allyance"])));
			$import_VKontakteID = $name_data[$i]["VKontakteID"];
			$import_Leave = $name_data[$i]["Leave"];
			$import_AttacksCount = $name_data[$i]["AttacksCount"];
			$import_MapDate = $name_data[$i]["MapDate"];
			$import_MyPlanet = $name_data[$i]["MyPlanet"];
		
			if ($planet_update[$name_data[$i]["Coords"]]) {
				$planet_present = $planet_present+1;
			}
			else {
				$name_import[] = '("'.$import_coords.'", "'.$import_Name.'", "'.$import_Allyance.'", "'.$import_VKontakteID.'", "'.$import_Leave.'", "'.
				$import_AttacksCount.'", "'.$import_MapDate.'", "'.$import_MyPlanet.'")';
				
			};
		 	$i++;
		};
		
		$source_planet_indb_cnt = count($name_data);
		$source_planet_cnt = $source_planet_cnt + $source_planet_indb_cnt ; //$source_planet_cnt + $total_row + 1;
		$target_planet_present_cnt = $target_planet_present_cnt + $planet_present;

		// если есть новые планеты - вносим.
		if (count($name_import) and $import) {
			$name_new_str = implode(', ', $name_import);
			
			if ($myPlanet == "1") {
				$table_temp_planet = TABLE_VG_TEMP_MYPLANETS;
			}
			elseif ($myPlanet == "0") {
				$table_temp_planet = TABLE_VG_TEMP_PLANETS;
			};

			$sql = 'INSERT INTO `' . $table_temp_planet . '` (`coord`, `planet_name`, `alliance`, `vkid`, `g_leave`, `attacks_count`, `update_date`, `myplanet`) VALUES '.$name_new_str.';';

			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
			};
		};
		$added_planet_cnt = $added_planet_cnt + count($name_import);
	
	// обнуляем переменные
		unset($name_import);

		$sql_limit_row_start = $source_planet_indb_cnt + $sql_limit_row_start;
		
		if ($source_planet_indb_cnt<$sql_limit_increase) {
			$sql_next_quee = FALSE;
			}
		else {
		};
	};
	unset($planet_update);
	unset($gid_current);
	unset($pname_current);
	$ret .= "Планет в исходной базе (".$myPlanet."): ". $source_planet_cnt . "<br />\n\r"; 
	$ret .= "Не импортированно планет т.к. они уже существуют (".$myPlanet."): ". $target_planet_present_cnt . "<br />\n\r"; 
	$ret .= "Добавлено планет (".$myPlanet."): ". $added_planet_cnt . "<br />\n\r"; 
	$ret .= "лимит времени на скрипт: ".$max_executing_time." сек.<br>\n\r";
	return $ret;
};


function t_transfer() {
/*
DROP TABLE IF EXISTS [tmp_tbReports];

CREATE TABLE "tmp_tbReports"(
[ID] INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL
,[Coords] INTEGER NOT NULL REFERENCES [tbPlanets] ([Coords]) On Delete CASCADE On Update RESTRICT
,[Time] INTEGER NOT NULL
,[Body] varchar NOT NULL
,[rImported] INTEGER
);

INSERT INTO tmp_tbReports ([ID], [Coords], [Time],  [Body])
SELECT [ID], [Coords], [Time], [Body]
FROM tbReports WHERE Body LIKE "%meta%" AND TypeIDC = 0 ORDER BY Coords ASC;

*/
	// конвертация отчетов в исходной базе
	global $DRoot,  $vg_config,  $db;

	$ret = "";
	// конект к sqlite.db
	$sqlitedb = $DRoot . $vg_config["upload_dir"] . $vg_config["vg_curr_db"];
	$sdb = new PDO('sqlite:'.$sqlitedb);

	$max_executing_time = 1500;
	set_time_limit($max_executing_time);

	$source_planet_cnt = 0;
	$replace_src  = array("</li><li>", "<br>", "\\",   '"' );
	$replace_trgt = array("\n",        "\n",   "\\\\", '\\"');

	$sql_limit_row_start = 0;
	$sql_limit_increase = 500; // лимит строчек
	$sql_next_quee = TRUE;
	$added_planet_cnt = 0; // счетчик добавленных 

	// считаем ко-во строк в таргетной базе. для продолжения импорта, если не хватит тайм лимита	
	$sql = 'SELECT count(*) AS `total_rows` FROM `'.TABLE_VG_TEMP_REPORTS.'`;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
	};
	$row = $db->sql_fetchrow($result);
	$sql_limit_row_start = $row["total_rows"];

	
	while ($sql_next_quee) {

//echo '<br>SELECT ID, Coords, Time, Body FROM tbReports WHERE Body LIKE "%meta%" AND TypeIDC = 0 ORDER BY [ID] ASC LIMIT ' . $sql_limit_row_start . ", 500<br>";

		$result = $sdb->query('SELECT ID, Coords, Time, Body FROM tbReports WHERE Body LIKE "%meta%" AND TypeIDC = 0 ORDER BY [ID] ASC LIMIT ' . $sql_limit_row_start . ", 500"); // Leave Defence MyPlanet
		$name_data = $result->fetchAll();

		$import = TRUE; 
		$planet_present = 0;

		$name_import = array();
		$i = 0; 
		while (strlen($name_data[$i]["ID"])) {
			$import_body = strip_tags(preg_replace("!<style>(.*?)</style>!", '', str_replace($replace_src, $replace_trgt, iconv("UTF-8", "windows-1251", $name_data[$i]["Body"]))));

			$name_import[] = '"'.$name_data[$i]["ID"].'", "'.$name_data[$i]["Coords"].'", "'.$name_data[$i]["Time"].'", "'.$import_body.'"';

			$planet_presen = ($i+1);

		 	$i++;
		};

		
		$source_planet_indb_cnt = count($name_data);
		$source_planet_cnt = $source_planet_cnt + $source_planet_indb_cnt ; //$source_planet_cnt + $total_row + 1;
		$target_planet_present_cnt = $target_planet_present_cnt + $planet_present;

		// обнуляем переменные
		unset($name_data);

		// если есть новые планеты - вносим.
		if (count($name_import) and $import) {
			$name_new_str = implode('), (', $name_import);
		

			$sql = 'INSERT INTO `'.TABLE_VG_TEMP_REPORTS.'` (`report_id`, `cords`, `r_date`, `r_body`) VALUES ('.$name_new_str.');';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
			};
		};
		$added_planet_cnt = $added_planet_cnt + count($name_import);
	
		// обнуляем переменные
		unset($name_new_str);
		unset($name_import);
		unset($sql);

		$sql_limit_row_start = $source_planet_indb_cnt + $sql_limit_row_start;
		
		if ($source_planet_indb_cnt<$sql_limit_increase) {
			$sql_next_quee = FALSE;
		}
		else {
		};
	};
	unset($planet_update);
	unset($gid_current);
	unset($pname_current);
	$ret .= "Отчетов в исходной базе: ". $source_planet_cnt . "<br />\n\r"; 
//	$ret .= "Не импортированно отчетов т.к. они уже существуют (".$myPlanet."): ". $target_planet_present_cnt . "<br />\n\r"; 
	$ret .= "Добавлено отчетов : ". $added_planet_cnt . "<br />\n\r"; 
	$ret .= 'Для данной операции установленно максимальное время выполнения скрипта ($max_executing_time) в : '. $max_executing_time . " сек.<br />\n\r"; 

	return $ret;
};

function id_history($src_type) {
	// ========================
	//   История изменения ID
	// ========================
	// src_type = map, report
	global $vg_config,  $db;
	$ret = "";
	
	if ($src_type == 'map') {
		$sql = 'SELECT `p`.`planet_coord` , `p`.`is_del`, `g`.`gamers_socialnet_id` , `tp`.`vkid` , `tp`.`update_date` '.
				'FROM `'.TABLE_VG_TEMP_PLANETS.'` AS `tp` '.
				'INNER JOIN `'.TABLE_VG_PLANETS.'` AS `p` ON `p`.`planet_coord` = `tp`.`coord` '.
				'INNER JOIN `'.TABLE_VG_GAMERS.'` AS `g` ON `g`.`gamers_id` = `p`.`gamer_id` '.
				'WHERE `g`.`gamers_socialnet_id` != `tp`.`vkid`;';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
		};

		$id_list = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$id_list[] =  $row;
		};
		
		$update_date = array();
		$i = 0;
		while ($id_list[$i]["planet_coord"]) {
			if (!$id_list[$i]["is_del"]) {
				$id_history[] = '("'.$id_list[$i]["planet_coord"].'", "'.$id_list[$i]["gamers_socialnet_id"].'", "'.
									$id_list[$i]["vkid"].'", "'.$id_list[$i]["update_date"].'")';
				$coords_list[] = $id_list[$i]["planet_coord"];
			}
			else {
				// измененные, но удаленные - тоже в список на обнуление
				$coords_list[] = $id_list[$i]["planet_coord"];
			};
			
			$i++;
		};

		// Заносим ID History
		if (count($id_history)) {
			$sql_id_history = implode(", ", $id_history);
			$sql = 'INSERT INTO `'.TABLE_VG_ID_HISTORY.'` (`planet_coord`, `socialnetid_old`, `socialnetid_new`, `update_date`) VALUES ' . $sql_id_history . ';';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу истории gID', '', __LINE__, __FILE__, $sql);
			};

			$sql = 'DELETE FROM `'.TABLE_VG_PLANETS.'` WHERE `planet_coord` IN ("'.implode('", "', $coords_list).'");';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка удаления неактаульных планет', '', __LINE__, __FILE__, $sql);
			};
		};
		
		$ret .= "Планет с измененным ID: " . count($id_list) . " шт<br>\n\r";
	};
	return $ret;
};

function t_parse() {
/*	
ALTER TABLE `mrsm_vg_temp_reports` 
ADD `b_ti` TINYINT( 3 ) NULL DEFAULT NULL AFTER `r_build` ,
ADD `b_si` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_ti` ,
ADD `b_kol` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_si` ,
ADD `b_energy` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_kol` ,
ADD `b_anih` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_energy` ,
ADD `b_robo` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_anih` ,
ADD `b_nano` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_robo` ,
ADD `b_doc` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_nano` ,
ADD `b_sti` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_doc` ,
ADD `b_ssi` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_sti` ,
ADD `b_sam` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_ssi` ,
ADD `b_nc` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_sam` ,
ADD `b_pen` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_nc` ,
ADD `b_pbase` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_pen` ,
ADD `b_cnt` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_pbase` ,
ADD `b_tp` TINYINT( 3 ) NULL DEFAULT NULL AFTER `b_cnt` 
*/
	// ====================
	//  Парсинг отчетов
	// ====================
	global $DRoot,  $vg_config,  $db;
	$ret = "";

	$max_executing_time = 300;
	set_time_limit($max_executing_time);


	$replace_src  = array(" ()", "\\",   '"' );
	$replace_trgt = array("",    "\\\\", '\\"');

	
	$sql = 'SELECT `report_id`, `r_body` FROM `'.TABLE_VG_TEMP_REPORTS.'` WHERE `fr_parsed` IS NULL LIMIT 50000;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
	};

	$report = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$report[] =  $row;
	};

	$i = 0;
	while ($report[$i]["report_id"]) {
	
		// Флот
		$dSAB = FALSE;
		preg_match_all("/(Шатл:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_sh = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Транспорт:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_tr = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Истребитель:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_fi = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Штурмовик:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_at = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Корвет:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_kr = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Фрегат:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_fr = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Первопроходец:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_pp = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Коллектор:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_kl = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Разведзонд:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_rz = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Бомбардир:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_bm = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Энергодрон:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_en = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Колосс:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_klbk = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Разрушитель:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_raz = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		preg_match_all("/(Галактион:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		$f_g = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';

		// если обнаружен саб - сбрасываем флоты т.к. нет воможности определить кто сабер, а кто хозяин
		if ($dSAB) {
			$f_sh = 'NULL';
			$f_tr = 'NULL';
			$f_fi = 'NULL';
			$f_at = 'NULL';
			$f_kr = 'NULL';
			$f_fr = 'NULL';
			$f_pp = 'NULL';
			$f_kl = 'NULL';
			$f_rz = 'NULL';
			$f_bm = 'NULL';
			$f_en = 'NULL';
			$f_klbk = 'NULL';
			$f_raz = 'NULL';
			$f_g = 'NULL';

		};
		// Оборона
		preg_match("/(Ракетный блок:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$d_rb = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Инфракрасный лазер:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$d_ir = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Ультрафиолетовый лазер:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$d_uv = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Гравитонное орудие:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$d_gr = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Фотонная пушка:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$d_f = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Лептонная пушка:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$d_l = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Малый энергокупол:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$d_mk = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Большой энергокупол:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$d_bk = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		
		
		// Строения
		preg_match("/(Титановая шахта:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_ti = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Кремниевая шахта:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_si = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Коллайдер:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_kol = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Нейтринная электростанция:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_energy = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Аннигиляционный реактор:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_anih = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Робофабрика:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_robo = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Нанофабрика:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_nano = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Док:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_doc = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Склад титана:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_sti = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Склад кремния:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_ssi = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Склад антиматерии:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_sam = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Научный центр:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_nc = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Планетарный энергоузел:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_pen = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Заправочная база:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_pbase = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Центр нанотехнологий:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_cnt = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Телепорт:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$b_tp = ($res[2]) ? '"'.$res[2].'"' : 'NULL';

		// Техи
		preg_match("/(Планетарное сканирование:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_ps = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Навигация:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_navi = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Вооружения:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_at = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Защита кораблей:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_def = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Энергетические поля:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_sh = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Энергетика:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_eng = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Перемещение в подпространстве:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_ssm = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Барионный двигатель:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_be = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Аннигиляционный двигатель:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_ae = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Подпространственный двигатель:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_sse = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Боевые лазеры:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_lsr = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Фотонное оружие:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_fot = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Лептонное оружие:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_lep = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Тахионное сканирование:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_tah = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Освоение планет:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_op = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Вибротрон:)\s+([0-9]{0,})/", $report[$i]["r_body"],$res);
		$t_vib = ($res[2]) ? '"'.$res[2].'"' : 'NULL';

		preg_match_all("/(['])([0-9]{1,})/", $report[$i]["r_body"],$res); 
		$vk_id = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';


// "Альянс: (.*)\(\(d*)\)"
// "нс: (.*)[Т\s]"

//выдаст типа такой "SG (18)"    "нс: (.*)(Т|\r)"
		$ally = "";
		preg_match("/(Альянс:)\s+(.*|[.\s+]*|[.\s+][.\s+]*|[.\s+][.\s+][.\s+]*|[.\s+][.\s+][.\s+][.\s+]*)/", $report[$i]["r_body"],$res); 
		if ($res[2]) {
			$ally = $res[2];
		};
		if ($ally) {
			preg_match("/(.*|[.\s+]*|[.\s+][.\s+]*|[.\s+][.\s+][.\s+]*|[.\s+][.\s+][.\s+][.\s+]*)(Титан)/", $ally,$res); 
			if ($res[1]) {
				$ally = $res[1];
			};
			$ally = 	'"'. str_replace($replace_src, $replace_trgt, preg_replace("!([0-9])!", "", $ally)) .'"';
		}
		else {
			$ally = 'NULL';
		}

//if ($ally) {
//echo "<b>".$ally."</b>";
//};
		
//		echo "<pre>";
//		print_r($res);
//		echo "</pre>";

		$sql = 'UPDATE `'.TABLE_VG_TEMP_REPORTS.'` SET `fr_parsed` = "1"' .
			// Fleet
			', `f_sh` = '.$f_sh.', `f_tr` = '.$f_tr.', `f_fi` = '.$f_fi.', `f_at` = '.$f_at.', `f_kr` = '.$f_kr.', `f_fr` = '.$f_fr.
			', `f_pp` = '.$f_pp.', `f_kl` = '.$f_kl.', `f_rz` = '.$f_rz.', `f_bm` = '.$f_bm.', `f_en` = '.$f_en.', `f_klbk` = '.$f_klbk.
			', `f_raz` = '.$f_raz.', `f_g` = '.$f_g.
			// Defense
			', `d_rb` = '.$d_rb.', `d_ir` = '.$d_ir.', `d_uv` = '.$d_uv.', `d_gr` = '.$d_gr.', `d_f` = '.$d_f.', `d_l` = '.$d_l.
			', `d_mk` = '.$d_mk.', `d_bk` = '.$d_bk.
			
			// Buildings
			', `b_ti` = '.$b_ti.', `b_si` = '.$b_si.', `b_kol` = '.$b_kol.', `b_energy` = '.$b_energy.', `b_anih` = '.$b_anih.
			', `b_robo` = '.$b_robo.', `b_nano` = '.$b_nano.', `b_doc` = '.$b_doc.', `b_sti` = '.$b_sti.', `b_ssi` = '.$b_ssi.
			', `b_sam` = '.$b_sam.', `b_nc` = '.$b_nc.', `b_pen` = '.$b_pen.', `b_pbase` = '.$b_pbase.', `b_cnt` = '.$b_cnt.
			', `b_tp` = '.$b_tp.

			// Tech
			', `t_ps` = '.$t_ps.', `t_navi` = '.$t_navi.', `t_at` = '.$t_at.', `t_def` = '.$t_def.', `t_sh` = '.$t_sh.', `t_eng` = '.$t_eng.
			', `t_ssm` = '.$t_ssm.', `t_be` = '.$t_be.', `t_ae` = '.$t_ae.', `t_sse` = '.$t_sse.', `t_lsr` = '.$t_lsr.', `t_fot` = '.$t_fot.
			', `t_lep` = '.$t_lep.', `t_tah` = '.$t_tah.', `t_op` = '.$t_op.', `t_vib` = '.$t_vib.
			
			', `r_ally` = '.$ally.', vk_id = '.$vk_id.' WHERE `report_id` = "'.$report[$i]["report_id"].'";';
//echo $sql."<br>";
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
		};

		$i++;
	};

	$ret .= "Обработано: " . count($report) . " отчетов (если 50к - повторить, если скипт не успел выполниться - тоже повторить, он продолжит с того места где остановился) лимит времени на скрипт: ".$max_executing_time." сек.<br>\n\r";
	return $ret;
};

function spy_reports() {
	// ====================
	//  Парсинг отчетов
	// ====================
	global $vg_config,  $db;
	$ret = "";
	$row_passed = 0; //счетчик обработанных отчетов
	$row_passed_current_quee = 0; // счетчик обработанных отчетов в текущем цикле (для продолжения оного)
	$row_per_quee = 10000; // кол-во отчетов за проход
	$max_executing_time = 1500;
	set_time_limit($max_executing_time);
	ini_set('memory_limit', '128M');


	$next_quee = TRUE;
	while ($next_quee) {
		$sql = 'SELECT	`report_id`, `cords`, `r_date`, `vk_id`, `f_sh`, `f_tr`, `f_fi`, `f_at`, `f_kr`, `f_fr`, `f_pp`, `f_kl`, `f_rz`, `f_bm`, `f_en`, `f_klbk`, `f_raz`, `f_g`, `d_rb`, `d_ir`, `d_uv`, `d_gr`, `d_f`, `d_l`, `d_mk`, `d_bk`, `b_ti`, `b_si`, `b_kol`, `b_energy`, `b_anih`, `b_robo`, `b_nano`, `b_doc`, `b_sti`, `b_ssi`, `b_sam`, `b_nc`, `b_pen`, `b_pbase`, `b_cnt`, `b_tp`, `t_ps`, `t_navi`, `t_at`, `t_def`, `t_sh`, `t_eng`, `t_ssm`, `t_be`, `t_ae`, `t_sse`, `t_lsr`, `t_fot`, `t_lep`, `t_tah`, `t_op`, `t_vib` 		
 FROM  `'.TABLE_VG_TEMP_REPORTS.'` WHERE `fr_parsed` = 1 LIMIT 0, '.$row_per_quee.';';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
		};

		$report = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$report[] =  $row;
//			echo $row["cords"] . " - " . $row["vk_id"] . "<br>";
		};

		$i = 0;
		while ($report[$i]["report_id"]) {
			// берем из базы инфу по текущему игроку
			if (@$g_data["gamers_socialnet_id"] != $report[$i]["vk_id"]) {
				unset($g_data); // ансетим на случай если в базе не будет информации - чтоб не подхватилась инфа с прошлого цикла.
				$sql = 'SELECT * FROM `'.TABLE_VG_GAMERS.'` WHERE `gamers_socialnet_id` = "'.$report[$i]["vk_id"].'";';

				if ( !($result = mysql_query($sql)) ) {
					message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
				};
				$g_data = mysql_fetch_assoc($result);
				
//				if ( !($result = $db->sql_query($sql)) ) {
//					message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
//				};
//				$g_data = $db->sql_fetchrow($result);
			};
			// берем из базы инфу по текущему игроку
			if (@$p_data["planet_coord"] != $report[$i]["cords"]) {
				unset($p_data);
				$sql = 'SELECT * FROM `'.TABLE_VG_PLANETS.'` WHERE `planet_coord` = "'.$report[$i]["cords"].'";';

				if ( !($result = mysql_query($sql)) ) {
					message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
				};
				$p_data = mysql_fetch_assoc($result);

//				if ( !($result = $db->sql_query($sql)) ) {
//					message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
//				};
//				$p_data = $db->sql_fetchrow($result);
			};
			
			// Трилогия импорта, Чать 1:
			// Технологии. импортим только если отчет более свежий.
			if (FALSE and $report[$i]["r_date"] > $g_data["scan_date"] and $g_data["gamers_id"]) { // импортим только более свежие отчеты и если есть такой ID в базе.

				// ----------------------
				//         Техи 
				// ----------------------
				// сумма тех что бы определиться надо ли импортить т.е. есть ли техи вообще.
				$tech_sum = $report[$i]["t_ps"] + $report[$i]["t_navi"] + $report[$i]["t_at"] + $report[$i]["t_def"] + $report[$i]["t_sh"] + 
					$report[$i]["t_eng"] + $report[$i]["t_ssm"] + $report[$i]["t_be"] + $report[$i]["t_ae"] + $report[$i]["t_sse"] + 
					$report[$i]["t_lsr"] + $report[$i]["t_fot"] + $report[$i]["t_lep"] + $report[$i]["t_tah"] + $report[$i]["t_op"] + $report[$i]["t_vib"];
				if (($tech_sum or ($g_data["scan_date"] == FALSE))) {
//					echo $i . " - " . $tech_sum . "<br>";

					// === собираем квирю (старт)			
	$tech_radar = 				($report[$i]["t_ps"] >	 $g_data["tech_radar"]) 				? ', `tech_radar` = "'.$report[$i]["t_ps"].'"'					: '';
	$tech_navigation = 		($report[$i]["t_navi"] > $g_data["tech_navigation"]) 			? ', `tech_navigation` = "'.$report[$i]["t_navi"].'"'			: '';
	$tech_attack = 			($report[$i]["t_at"] >	 $g_data["tech_attack"]) 				? ', `tech_attack` = "'.$report[$i]["t_at"].'"'					: '';
	$tech_armor = 				($report[$i]["t_def"] >	 $g_data["tech_armor"]) 				? ', `tech_armor` = "'.$report[$i]["t_def"].'"'					: '';
	$tech_shields = 			($report[$i]["t_sh"] >	 $g_data["tech_shields"]) 				? ', `tech_shields` = "'.$report[$i]["t_sh"].'"'				: '';
	$tech_enegry = 			($report[$i]["t_eng"] >	 $g_data["tech_enegry"]) 				? ', `tech_enegry` = "'.$report[$i]["t_eng"].'"'				: '';
	$tech_subspacemovement =($report[$i]["t_ssm"] >	 $g_data["tech_subspacemovement"])	? ', `tech_subspacemovement` = "'.$report[$i]["t_ssm"].'"'	: '';
	$tech_eng_baryonic =		($report[$i]["t_be"] >	 $g_data["tech_eng_baryonic"])		? ', `tech_eng_baryonic` = "'.$report[$i]["t_be"].'"'			: '';
	$tech_eng_annihilation =($report[$i]["t_ae"] >	 $g_data["tech_eng_annihilation"])	? ', `tech_eng_annihilation` = "'.$report[$i]["t_ae"].'"'	: '';
	$tech_eng_subspace =		($report[$i]["t_sse"] >	 $g_data["tech_eng_subspace"]) 		? ', `tech_eng_subspace` = "'.$report[$i]["t_sse"].'"'		: '';
	$tech_laser =				($report[$i]["t_lsr"] >	 $g_data["tech_laser"])					? ', `tech_laser` = "'.$report[$i]["t_lsr"].'"'				:	 '';
	$tech_foton =				($report[$i]["t_fot"] >	 $g_data["tech_foton"])					? ', `tech_foton` = "'.$report[$i]["t_fot"].'"'				:	 '';
	$tech_lepton =				($report[$i]["t_lep"] >	 $g_data["tech_lepton"])				? ', `tech_lepton` = "'.$report[$i]["t_lep"].'"'			:	 '';
	$tech_tachyonscan =		($report[$i]["t_tah"] >	 $g_data["tech_tachyonscan"])			? ', `tech_tachyonscan` = "'.$report[$i]["t_tah"].'"'		:	 '';
	$tech_planetology =		($report[$i]["t_op"] >	 $g_data["tech_planetology"])			? ', `tech_planetology` = "'.$report[$i]["t_op"].'"'		:	 '';
	$tech_vibrotron =			($report[$i]["t_vib"] >	 $g_data["tech_vibrotron"])			? ', `tech_vibrotron` = "'.$report[$i]["t_vib"].'"'		:	 '';
					// === собираем квирю (конец)

					$sql = 'UPDATE `'.TABLE_VG_GAMERS.
						'` SET `scan_date` = "'.$report[$i]["r_date"].'"' . $tech_radar.$tech_navigation . $tech_attack . $tech_armor . 
						$tech_shields . $tech_enegry . $tech_subspacemovement . $tech_eng_baryonic . $tech_eng_annihilation . 
						$tech_eng_subspace . $tech_laser . $tech_foton . $tech_lepton . $tech_tachyonscan . $tech_planetology . $tech_vibrotron.
						' WHERE `gamers_id` = "'.$g_data["gamers_id"].'" ;';
					if ( !($result = $db->sql_query($sql)) ) {
						message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
					};
				};
			};

			// Трилогия импорта, Чать 2:
			// Здания и оборона. импортим только если отчет более свежий и если совпадает ID игрока. дабы не импортнуть данные по чужой удаленной планете.
			if (FALSE and $report[$i]["r_date"] > $p_data["scan_date"] and $g_data["gamers_id"] == $p_data["gamer_id"]) {
				// ----------------------
				//       Постройки 
				// ----------------------
				// сумма построек, чтобы определиться надо ли импортить т.е. есть ли постройки вообще.
				$building_sum = $report[$i]["b_ti"] + $report[$i]["b_si"] + $report[$i]["b_kol"] + $report[$i]["b_energy"] + $report[$i]["b_anih"] + 
					$report[$i]["b_robo"] + $report[$i]["b_nano"] + $report[$i]["b_doc"] +  + $report[$i]["b_sti"] + $report[$i]["b_ssi"] + 
					$report[$i]["b_sam"] + $report[$i]["b_nc"] + $report[$i]["b_pen"] + $report[$i]["b_pbase"] + $report[$i]["b_cnt"] + 
					$report[$i]["b_tp"];

//echo $building_sum . "<br>";
				// продолжаем если на скане есть постройки либо техи
				$sql_set = "";
				if ($building_sum or $tech_sum) {

//	$ret = ($src) ? ', `'.$name.'` = "'.$src.'"' : ', `'.$name.'` = NULL';

					$sql_set .=	($report[$i]["b_ti"]) ? ', `b_ti` = "'.$report[$i]["b_ti"].'"' : ', `b_ti` = NULL';
					$sql_set .=	($report[$i]["b_si"]) ? ', `b_si` = "'.$report[$i]["b_si"].'"' : ', `b_si` = NULL';
					$sql_set .=	($report[$i]["b_kol"]) ? ', `b_kol` = "'.$report[$i]["b_kol"].'"' : ', `b_kol` = NULL';
					$sql_set .=	($report[$i]["b_energy"]) ? ', `b_energy` = "'.$report[$i]["b_energy"].'"' : ', `b_energy` = NULL';
					$sql_set .=	($report[$i]["b_anih"]) ? ', `b_anih` = "'.$report[$i]["b_anih"].'"' : ', `b_anih` = NULL';
					$sql_set .=	($report[$i]["b_robo"]) ? ', `b_robo` = "'.$report[$i]["b_robo"].'"' : ', `b_robo` = NULL';
					$sql_set .=	($report[$i]["b_nano"]) ? ', `b_nano` = "'.$report[$i]["b_nano"].'"' : ', `b_nano` = NULL';
					$sql_set .=	($report[$i]["b_doc"]) ? ', `b_doc` = "'.$report[$i]["b_doc"].'"' : ', `b_doc` = NULL';
					$sql_set .=	($report[$i]["b_sti"]) ? ', `b_sti` = "'.$report[$i]["b_sti"].'"' : ', `b_sti` = NULL';
					$sql_set .=	($report[$i]["b_ssi"]) ? ', `b_ssi` = "'.$report[$i]["b_ssi"].'"' : ', `b_ssi` = NULL';
					$sql_set .=	($report[$i]["b_sam"]) ? ', `b_sam` = "'.$report[$i]["b_sam"].'"' : ', `b_sam` = NULL';
					$sql_set .=	($report[$i]["b_nc"]) ? ', `b_nc` = "'.$report[$i]["b_nc"].'"' : ', `b_nc` = NULL';
					$sql_set .=	($report[$i]["b_pen"]) ? ', `b_pen` = "'.$report[$i]["b_pen"].'"' : ', `b_pen` = NULL';
					$sql_set .=	($report[$i]["b_pbase"]) ? ', `b_pbase` = "'.$report[$i]["b_pbase"].'"' : ', `b_pbase` = NULL';
					$sql_set .=	($report[$i]["b_cnt"]) ? ', `b_cnt` = "'.$report[$i]["b_cnt"].'"' : ', `b_cnt` = NULL';
					$sql_set .=	($report[$i]["b_tp"]) ? ', `b_tp` = "'.$report[$i]["b_tp"].'"' : ', `b_tp` = NULL';
					
/*
					$sql_set .=	r2d2_sql_clause('b_ti',		$report[$i]["b_ti"],		$p_data["b_ti"]) .
									r2d2_sql_clause('b_si',		$report[$i]["b_si"],		$p_data["b_si"]) .
									r2d2_sql_clause('b_kol',	$report[$i]["b_kol"],	$p_data["b_kol"]) . 
									r2d2_sql_clause('b_energy',	$report[$i]["b_energy"],	$p_data["b_energy"]) .
									r2d2_sql_clause('b_anih',	$report[$i]["b_anih"],	$p_data["b_anih"]) .
									r2d2_sql_clause('b_robo',	$report[$i]["b_robo"],	$p_data["b_robo"]) .
									r2d2_sql_clause('b_nano',	$report[$i]["b_nano"],	$p_data["b_nano"]) . 
									r2d2_sql_clause('b_doc',	$report[$i]["b_doc"],	$p_data["b_doc"]) .
									r2d2_sql_clause('b_sti',	$report[$i]["b_sti"],	$p_data["b_sti"]) .
									r2d2_sql_clause('b_ssi',	$report[$i]["b_ssi"],	$p_data["b_ssi"]) .
									r2d2_sql_clause('b_sam',	$report[$i]["b_sam"],	$p_data["b_sam"]) .
									r2d2_sql_clause('b_nc',		$report[$i]["b_nc"],		$p_data["b_nc"]) .
									r2d2_sql_clause('b_pen',	$report[$i]["b_pen"],	$p_data["b_pen"]) .
									r2d2_sql_clause('b_pbase',	$report[$i]["b_pbase"],	$p_data["b_pbase"]) .
									r2d2_sql_clause('b_cnt',	$report[$i]["b_cnt"],	$p_data["b_cnt"]) .
									r2d2_sql_clause('b_tp',		$report[$i]["b_tp"],		$p_data["b_tp"]);
*/
				};
				// ----------------------
				//       Оборона 
				// ----------------------
				// сумма оборонных сооружений, чтобы определиться надо ли импортить т.е. есть ли они вообще.
				$defence_sum = $report[$i]["d_rb"] + $report[$i]["d_ir"] + $report[$i]["d_uv"] + $report[$i]["d_gr"] + 
									$report[$i]["d_f"]  + $report[$i]["d_l"]  + $report[$i]["d_mk"] + $report[$i]["d_bk"];

				// продолжаем если на скане оборона, либо если есть постройки / техи
				if ($defence_sum or $building_sum or $tech_sum) {

					$sql_set .=	($report[$i]["d_rb"]) ? ', `d_rb` = "'.$report[$i]["d_rb"].'"' : ', `d_rb` = NULL';
					$sql_set .=	($report[$i]["d_ir"]) ? ', `d_ir` = "'.$report[$i]["d_ir"].'"' : ', `d_ir` = NULL';
					$sql_set .=	($report[$i]["d_uv"]) ? ', `d_uv` = "'.$report[$i]["d_uv"].'"' : ', `d_uv` = NULL';
					$sql_set .=	($report[$i]["d_gr"]) ? ', `d_gr` = "'.$report[$i]["d_gr"].'"' : ', `d_gr` = NULL';
					$sql_set .=	($report[$i]["d_f"])  ? ', `d_f` = "'. $report[$i]["d_f"]. '"' : ', `d_f` = NULL';
					$sql_set .=	($report[$i]["d_l"])  ? ', `d_l` = "'. $report[$i]["d_l"]. '"' : ', `d_l` = NULL';
					$sql_set .=	($report[$i]["d_mk"]) ? ', `d_mk` = "'.$report[$i]["d_mk"].'"' : ', `d_mk` = NULL';
					$sql_set .=	($report[$i]["d_bk"]) ? ', `d_bk` = "'.$report[$i]["d_bk"].'"' : ', `d_bk` = NULL';
/*

					$sql_set .=	r2d2_sql_clause('d_rb',	$report[$i]["d_rb"],	$p_data["d_rb"]) .
									r2d2_sql_clause('d_ir',	$report[$i]["d_ir"],	$p_data["d_ir"]) .
									r2d2_sql_clause('d_uv',	$report[$i]["d_uv"],	$p_data["d_uv"]) .
									r2d2_sql_clause('d_gr',	$report[$i]["d_gr"],	$p_data["d_gr"]) .
									r2d2_sql_clause('d_f',	$report[$i]["d_f"],	$p_data["d_f"]) .
									r2d2_sql_clause('d_l',	$report[$i]["d_l"],	$p_data["d_l"]) .
									r2d2_sql_clause('d_mk',	$report[$i]["d_mk"],	$p_data["d_mk"]) .
									r2d2_sql_clause('d_bk',	$report[$i]["d_bk"],	$p_data["d_bk"]);
*/
				};
				$sql = 'UPDATE `'.TABLE_VG_PLANETS.
						'` SET scan_date = "'.$report[$i]["r_date"].'"' . $sql_set . 
						' WHERE `planet_coord` = "'.$report[$i]["cords"].'";';
//				echo $sql . "<br>";
				if ( !($result = $db->sql_query($sql)) ) {
					message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
				};
			};

			// Трилогия импорта, Чать 3:
			// Флот. импортим тупо все данные о флотах.
			$fleet_sum = $report[$i]["f_sh"] + $report[$i]["f_tr"] + $report[$i]["f_fi"] + $report[$i]["f_at"] + $report[$i]["f_kr"] + 
							 $report[$i]["f_fr"] + $report[$i]["f_pp"] + $report[$i]["f_kl"] + $report[$i]["f_rz"] + $report[$i]["f_bm"] + 
							 $report[$i]["f_en"] + $report[$i]["f_klbk"] + $report[$i]["f_raz"] + $report[$i]["f_g"];
			if (FALSE and $fleet_sum) {
				$f_sh   = ($report[$i]["f_sh"])	? '"'.$report[$i]["f_sh"].'", ' : 'NULL, ';
				$f_tr   = ($report[$i]["f_tr"])	? '"'.$report[$i]["f_tr"].'", ' : 'NULL, ';
				$f_fi   = ($report[$i]["f_fi"])	? '"'.$report[$i]["f_fi"].'", ' : 'NULL, ';
				$f_at   = ($report[$i]["f_at"])	? '"'.$report[$i]["f_at"].'", ' : 'NULL, ';
				$f_kr   = ($report[$i]["f_kr"])	? '"'.$report[$i]["f_kr"].'", ' : 'NULL, ';
				$f_fr   = ($report[$i]["f_fr"])	? '"'.$report[$i]["f_fr"].'", ' : 'NULL, ';
				$f_pp   = ($report[$i]["f_pp"])	? '"'.$report[$i]["f_pp"].'", ' : 'NULL, ';
				$f_kl   = ($report[$i]["f_kl"])	? '"'.$report[$i]["f_kl"].'", ' : 'NULL, ';
				$f_rz   = ($report[$i]["f_rz"])	? '"'.$report[$i]["f_rz"].'", ' : 'NULL, ';
				$f_bm   = ($report[$i]["f_bm"])	? '"'.$report[$i]["f_bm"].'", ' : 'NULL, ';
				$f_en   = ($report[$i]["f_en"])	? '"'.$report[$i]["f_en"].'", ' : 'NULL, ';
				$f_klbk = ($report[$i]["f_klbk"]) ? '"'.$report[$i]["f_klbk"].'", ' : 'NULL, ';
				$f_raz  = ($report[$i]["f_raz"]) ? '"'.$report[$i]["f_raz"].'", ' : 'NULL, ';
				$f_g	  = ($report[$i]["f_g"])	? '"'.$report[$i]["f_g"].'"' : 'NULL';

				$fleet_insert = $f_sh . $f_tr . $f_fi . $f_at . $f_kr . $f_fr . $f_pp . $f_kl . $f_rz . $f_bm . $f_en . $f_klbk . $f_raz . $f_g;
								
				$sql = 'INSERT INTO `'.TABLE_VG_FLEET.
							'` (`coord`, `vkid`, `scan_date`, `f_sh`, `f_tr`, `f_fi`, `f_at`, `f_kr`, `f_fr`, `f_pp`, `f_kl`, `f_rz`, '.
							'`f_bm`, `f_en`, `f_klbk`, `f_raz`, `f_g`) VALUES ('.
							'"'.$report[$i]["cords"].'", "'.$report[$i]["vk_id"].'", "'.$report[$i]["r_date"].'", '.
							$fleet_insert
							.'); ';
				if ( !($result = $db->sql_query($sql)) ) {
					message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
				};
			};
		
			// помечаем строчку как обработанную
			$sql = 'UPDATE `'.TABLE_VG_TEMP_REPORTS.'` SET `fr_parsed` = "2" WHERE `report_id` = "'.$report[$i]["report_id"].'"';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
			};
			$row_passed = $row_passed+1;
			$row_passed_current_quee = $row_passed_current_quee+1;
			
			$i++;
		};

		// обнуление переменных
		unset($report);
		unset($fleet_insert);
		unset($sql);
		unset($sql_set);
		unset($building_sum);
		unset($defence_sum);
		unset($fleet_sum);
		unset($result);
		
		if ($row_passed_current_quee == $row_per_quee) {
			unset($row_passed_current_quee);
			$row_passed_current_quee = 0;
			$next_quee = TRUE;
		}
		else {
			$next_quee = FALSE;
		}

if ($row_passed < 51000) {
	$next_quee = TRUE;
}
else {
	$next_quee = FALSE;
};
//		$next_quee = FALSE;

	};

	$ret .= "обработано: ".$row_passed ." отчетов<br>\n\r";
	$ret .= "Макс время на выполнение скрипта: ".$max_executing_time ." сек. (если отвалится - повторить операцию. продолжит с места падения)<br>\n\r";
	
	return $ret;
};
function report_ally_import() {
	// ====================
	//  импорт принаджержностей к альянсам из шпионских отчетов
	//  привызываем толкьо те альянсы которые уже есть в базе, на случай некорректно парсинга и удаленных аллов.
	// ====================
	global $db;
	$ret ="";

	$sql = 'SELECT `a`.`alliance_id`, `g`.`gamers_id`, `tr`.`r_date` '.
			'FROM `'.TABLE_VG_TEMP_REPORTS.'` AS `tr` '.
			'INNER JOIN `'.TABLE_VG_ALLIANCE.'` AS `a` ON `tr`.`r_ally` = `a`.`alliance_name` '.
			'INNER JOIN `'.TABLE_VG_GAMERS.'` AS `g` ON `tr`.`vk_id` = `g`.`gamers_socialnet_id`;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
	};

	$ally_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$ally_data[] =  '('. $row["alliance_id"] . ','.$row["gamers_id"].','.$row["r_date"].')';
//			echo $row["cords"] . " - " . $row["vk_id"] . "<br>";
	};	

	$sql = 'INSERT INTO `'.TABLE_VG_GAMERS_ALLIANCE.'` () VALUES '.implode(',',$ally_data).';';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
	};

	$ret .= 'Добавленно: '.count($ally_data)." привязок<br>\n\r";
	$ret .= 'Длина квири: '.strlen($sql)." байт<br>\n\r";

	return $ret;	
};

function truncate_temp_db($db_type) {
	// ====================
	//  чистим временную таблицу
	// ====================
	global $db;

	$ret = "ошибка чистки временной таблицы планет";

	if ($db_type == "my") {
		$sql = 'TRUNCATE TABLE `' . TABLE_VG_TEMP_MYPLANETS . '`';
	}
	elseif ($db_type== "base") {
		$sql = 'TRUNCATE TABLE `' . TABLE_VG_TEMP_PLANETS . '`';
	}
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	}
	else {
		$ret = "временная база (".$db_type .") почищена<br />\n\r";
	};
	return $ret;
};

function stat_temp_db() {
	// ====================
	//  статистика временной таблицы.
	// ====================
	global $db;

	$ret= "<br /><br />Статистика временной базы: <br>
		<table>";

	// Средняя дата карты
	$sql = 'SELECT AVG(`update_date`) AS average_date FROM `' . TABLE_VG_TEMP_PLANETS . '` WHERE `myplanet` = "0";';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$row = $db->sql_fetchrow($result);
	$ret .= "<tr><td>Средняя дата построения карты:</td><td>". date("d.m.Y", number_format($row["average_date"], 0, '', '')) . "</td></tr>\n\r";


	// Всего планет
	$sql = 'SELECT count(coord) AS coord_cnt FROM `' . TABLE_VG_TEMP_PLANETS . '`';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$row = $db->sql_fetchrow($result);
	$ret .= "<tr><td>Всего планет:</td><td>". $row["coord_cnt"] . "</td></tr>\n\r";

	//диапазон планет
	$sql = 'SELECT MIN(coord) AS coord_min, MAX(coord) AS coord_max FROM `' . TABLE_VG_TEMP_PLANETS . '`';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$row = $db->sql_fetchrow($result);
	$cord_min = $row["coord_min"];
	$cord_max = $row["coord_max"];
	$ret .= "<tr><td>диапазон планет:</td><td>с " . coords($row["coord_min"], "short" ) . "(" . $row["coord_min"] . 
		")<br />по " .coords($row["coord_max"], "short" ) . "(" . $row["coord_max"] . ")</td></tr>\n\r";

	// планет без вконтактID
	$sql = 'SELECT COUNT(coord) AS coord_cnt FROM `' . TABLE_VG_TEMP_PLANETS . '` WHERE `vkid` in ("", NULL)  ';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$row = $db->sql_fetchrow($result);
	$ret .= "<tr><td>планет без вконтактID:</td><td>". $row["coord_cnt"] . "</td></tr>\n\r";

	// Живых планет (не в отпуске)
	$sql = 'SELECT COUNT(coord) AS coord_cnt FROM `' . TABLE_VG_TEMP_PLANETS . '` WHERE `g_leave` = 0 AND `attacks_count` != "999999"';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$row = $db->sql_fetchrow($result);
	$ret .= "<tr><td>Живых планет (не в отпуске):</td><td>". $row["coord_cnt"] . "</td></tr>\n\r";

	// Отпускников
	$sql = 'SELECT COUNT(coord) AS coord_cnt FROM `' . TABLE_VG_TEMP_PLANETS . '` WHERE `g_leave` = 1  ';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$row = $db->sql_fetchrow($result);
	$ret .= "<tr><td>Отпускников:</td><td>". $row["coord_cnt"] . "</td></tr>\n\r";

	// Своих планет
	$sql = 'SELECT COUNT(coord) AS coord_cnt FROM `' . TABLE_VG_TEMP_MYPLANETS . '`';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$row = $db->sql_fetchrow($result);
	$ret .= "<tr><td>Своих планет:</td><td>". $row["coord_cnt"] . "</td></tr>\n\r";

	// Названий планет
	$sql = 'SELECT `planet_name` FROM `' . TABLE_VG_TEMP_PLANETS . '` GROUP BY `planet_name` ';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$numRows = mysql_num_rows( $result );
	$ret .= "<tr><td>Уникальных названий планет:</td><td>". $numRows."</td></tr>\n\r";

	$sql = 'SELECT `t`.`vkid` , `g`.`gamers_id`
FROM `' . TABLE_VG_TEMP_PLANETS . '` AS `t`
LEFT JOIN `'. TABLE_VG_GAMERS .'` AS `g` ON `t`.`vkid` = `g`.`gamers_socialnet_id`
WHERE `g`.`gamers_socialnet_id` IS NULL
GROUP BY `t`.`vkid`;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$numRows = mysql_num_rows( $result );
	$ret .= "<tr><td>Новых ID игроков:</td><td>". $numRows."</td></tr>\n\r";

	// новые планеты	
	$sql = 'SELECT t.`coord` , `p`.`planet_coord`
FROM `' . TABLE_VG_TEMP_PLANETS . '` AS `t` 
LEFT JOIN `' . TABLE_VG_PLANETS . '` AS `p` ON `t`.`coord` = `p`.`planet_coord`
WHERE `p`.`planet_coord` IS NULL 
AND `t`.`coord` BETWEEN "'.$cord_min.'" AND "'.$cord_max.'";';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$numRows = mysql_num_rows( $result );
	$ret .= "<tr><td>Новых планет:</td><td>". $numRows."</td></tr>\n\r";

	// удаленные планеты
	$sql = 'SELECT `p`.`planet_coord` , t.`coord` 
FROM `' . TABLE_VG_PLANETS . '` AS `p` 
LEFT JOIN `' . TABLE_VG_TEMP_PLANETS . '` AS `t` ON `p`.`planet_coord` = `t`.`coord` 
WHERE t.`coord` IS NULL 
AND `p`.`planet_coord` BETWEEN "'.$cord_min.'" AND "'.$cord_max.'";';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$numRows = mysql_num_rows( $result );
	$ret .= "<tr><td>Удаленных планет:</td><td>". $numRows."</td></tr>\n\r";

	// планеты с обновленной картой
	$sql = 'SELECT `t`.`coord`, `t`.`update_date`, `p`.`planet_coord`, `p`.`update_date`
FROM `' . TABLE_VG_TEMP_PLANETS . '` AS `t` , `' . TABLE_VG_PLANETS . '` AS `p` 
WHERE `t`.`coord` = `p`.`planet_coord`
AND `t`.`update_date` > `p`.`update_date`;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$numRows = mysql_num_rows( $result );
	$ret .= "<tr><td>Планеты с обновленной картой:</td><td>". $numRows."</td></tr>\n\r";

	// планет с измененным ID
	$sql = 'SELECT `p`.`planet_coord` , `p`.`is_del`, `g`.`gamers_socialnet_id` , `tp`.`vkid` , `tp`.`update_date` '.
			'FROM `'.TABLE_VG_TEMP_PLANETS.'` AS `tp` '.
			'INNER JOIN `'.TABLE_VG_PLANETS.'` AS `p` ON `p`.`planet_coord` = `tp`.`coord` '.
			'INNER JOIN `'.TABLE_VG_GAMERS.'` AS `g` ON `g`.`gamers_id` = `p`.`gamer_id` '.
			'WHERE `g`.`gamers_socialnet_id` != `tp`.`vkid`;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка добавления ID  в таблицу', '', __LINE__, __FILE__, $sql);
	};

	// просканированных планет 
	$sql = 'SELECT COUNT(coord) AS coord_cnt FROM `' . TABLE_VG_TEMP_PLANETS . '` WHERE `g_leave` = 1  ';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
	};
	$row = $db->sql_fetchrow($result);
	$ret .= "<tr><td>[просканированных планет]:</td><td>". $row["coord_cnt"] . "</td></tr>\n\r";

	unset($result);	
	$ret .= "</table>";
	
	return $ret;
};

function import_names() {
	global $DRoot,  $vg_config,  $db;
	// 
	// ========================
	//  импорт названий планет
	// ========================
	/*
		INSERT INTO `mrsm_vg_planet_name` (`name_id`, `planet_name`) VALUES
		(1, '[color:red]без названия[/color]'),
		(2, '[color:red]пробел[/color]'),
		(3, '[color:red]спецсимволы[/color]');
	*/
	$ret = "";

	// выбираем новые названия.
	$sql = "SELECT DISTINCT `tp`.`planet_name` AS `new_name`, `pn`.`planet_name` AS `old_name` , `pn`.`name_id` ".
		"FROM `" . TABLE_VG_TEMP_PLANETS . "` AS `tp` ".
		"LEFT JOIN `" . TABLE_VG_PLANET_NAME . "` AS `pn` ON `pn`.`planet_name` = `tp`.`planet_name` ".
		"WHERE pn.planet_name IS NULL;";

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Не удалось выбрать новые названия планет', '', __LINE__, __FILE__, $sql);
		};

	$name_import = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$name_import[] =  mysql_real_escape_string($row["new_name"]);

	};



/*
	$sql = 'SELECT `name_id`, `planet_name` FROM `' . TABLE_VG_PLANET_NAME . '`';
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$name_current = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$name_current[$row["planet_name"]] =  $row["name_id"];

	};


	$sql = 'SELECT `planet_name` FROM `' . TABLE_VG_TEMP_PLANETS . '` GROUP BY `planet_name` ';
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен планет', '', __LINE__, __FILE__, $sql);
		};

	$name_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$name_data[] = $row;

	};
//	echo $name_data[10]["planet_name"];

	//var_dump($name_current);
	$name_import = array();
	$i = 0;
	while (strlen($name_data[$i]["planet_name"])) {
		$new_name = $name_data[$i]["planet_name"];
		if (!$name_current[$new_name]) {
			$name_import[] = str_replace('"', '\"', str_replace('\\', '\\\\', $new_name));
		};
		$total_row = $i+1;
	 	$i++;
	};
	
//	var_dump($name_data);
	
	$ret .= "Уникальных названий планет в исходной базе: ". $total_row . "<br />\n\r"; 
*/
	// если есть новые планеты - вносим.
	if (count($name_import)) {
		$name_new_str = implode('"), ("', $name_import);

		$sql = 'INSERT INTO `' . TABLE_VG_PLANET_NAME . '` (`planet_name`) VALUES ("'.$name_new_str.'");';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка добавления имен планет в таблицу', '', __LINE__, __FILE__, $sql);
		};
	};
//$ret .= $sql;
	$ret .= "Добавлено уникальных названий планет: ".  count($name_import) . "<br />\n\r"; 
/*	
	$ret .= "<table>";
	$i = 0;
	while ($name_import[$i] or $i < 32265) {
		$ret .= "<tr><td>".$i."</td><td>".strip_tags($name_data[$i]["planet_name"])."</td><td>".strip_tags($name_import[$i])."</td></tr>";
	 	$i++;
	};
	$ret .= "</table>";
*/	
	// обнуляем переменные
	unset($name_result);
	unset($name_import);
	unset($name_data);
	unset($name_current);
	unset($name_new_str);
	unset($name_new);

	return $ret;
};

function alliance_import() {
	global $vg_config,  $db;

	// ====================
	// импорт имен альянсов
	// ====================
	
	$ret = "";
	
	$sql = 'SELECT `alliance` FROM `' . TABLE_VG_TEMP_PLANETS . '` GROUP BY `alliance`; ';
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
		};

	$total_row = 0;
	$name_import = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$name_import[] = str_replace('"', '\"', str_replace('\\', '\\\\', $row["alliance"]));
		$total_row = $total_row + 1;
	};
/*
	$result = $sdb->query('SELECT Allyance FROM tbPlanets GROUP BY Allyance');
	$name_data = $result->fetchAll();

	$name_import = array();
	$i = (strlen($name_data[0]["Allyance"])) ? 0 : 1; // если первая строчка с названием альянса пустая (весьма актуально)
	while (strlen($name_data[$i]["Allyance"])) {
		$name_import[] = str_replace('"', '\"', str_replace('\\', '\\\\', iconv("UTF-8", "windows-1251", $name_data[$i]["Allyance"])));
		$total_row = $i;
	 	$i++;
	};
*/
	$ret .= "Уникальных названий альянсов в исходной базе: ". $total_row . "<br />\n\r"; 


	$sql = 'SELECT `alliance_name` FROM `' . TABLE_VG_ALLIANCE . '`';
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице имен альянсов', '', __LINE__, __FILE__, $sql);
		};

	$name_current = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$name_current[] = str_replace('"', '\"', str_replace('\\', '\\\\', $row["alliance_name"]));

	};
	
	$name_new = array_diff($name_import, $name_current);

	// если есть новые планеты - вносим.
	if (count($name_new)) {
		$name_new_str = implode('"), ("', $name_new);

		$sql = 'INSERT INTO `' . TABLE_VG_ALLIANCE . '` (`alliance_name`) VALUES ("'.$name_new_str.'");';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка добавления имен альянсов в таблицу', '', __LINE__, __FILE__, $sql);
		};
	};
	$ret .= "Добавлено уникальных альянсов: ".  count($name_new) . "<br />\n\r"; 
	
	// обнуляем переменные
	unset($name_result);
	unset($name_import);
	unset($name_data);
	unset($name_current);
	unset($name_new_str);
	unset($name_new);	

	return $ret;
};
/*
function gamers_check() {
	global $vg_config,  $db;
	// 
	// ========================
	//      ID игроков. помечаем существующие.
	// ========================
	//
	$ret = "";
	
	// выбираем новые ID
	$sql = 'SELECT `t`.`vkid` , `g`.`gamers_id`
FROM `' . TABLE_VG_TEMP_PLANETS . '` AS `t`
LEFT JOIN `'. TABLE_VG_GAMERS .'` AS `g` ON `t`.`vkid` = `g`.`gamers_socialnet_id`
WHERE `g`.`gamers_socialnet_id` IS NULL;';


	
	$sql = 'SELECT `t`.`vkid`, `g`.`gamers_id`
	FROM `' . TABLE_VG_TEMP_PLANETS . '` AS `t`, `'. TABLE_VG_GAMERS .'` AS `g`
	WHERE `g`.`gamers_socialnet_id` = `t`.`vkid` AND `t`.`row_passed` = "0"
	GROUP BY `t`.`vkid`;';
//$ret .= $sql;
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или таблице ID игроков', '', __LINE__, __FILE__, $sql);
		};

	$gid_current = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$gid_current[] = $row;

	};

	$row_count = 0;
	$i = 0;
	$ic = 0;
	$insert_flt = array();
	while (strlen($gid_current[$i]["gamers_id"])) {
		
		if ($ic<10000) {
			$insert_flt[] = $gid_current[$i]["vkid"];
//			return $ret . "\n\r<br>обработано ".$i." строк макс 100 за раз";
			$ic++;
		}
		else {
			$vkid_flt =  implode('", "', $insert_flt);
			$sql = 'UPDATE `' . TABLE_VG_TEMP_PLANETS . '` SET `row_passed` = "1" WHERE `vkid` in ("'.$vkid_flt.'");';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или таблице ID игроков', '', __LINE__, __FILE__, $sql);
				};
			unset($insert_flt);
			unset($vkid_flt);
			$insert_flt = array();
			$ic = 0;
		};

		$row_count = $row_count + 1;
		$i++;
	};

	// вносим последнюю партию
	if (count($insert_flt)) {
		$vkid_flt =  implode('", "', $insert_flt);
		$sql = 'UPDATE `' . TABLE_VG_TEMP_PLANETS . '` SET `row_passed` = "1" WHERE `vkid` in ("'.$vkid_flt.'");';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или таблице ID игроков', '', __LINE__, __FILE__, $sql);
			};
		unset($insert_flt);
		unset($vkid_flt);
	};
	
	$ret .= "обработано ".$row_count." строк<br />\n\r";
	
	$sql = 'SELECT COUNT(`vkid`) AS `pl_cnt` FROM `' . TABLE_VG_TEMP_PLANETS . '` WHERE `row_passed` = "0";';
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или таблице ID игроков', '', __LINE__, __FILE__, $sql);
		};
	$row = $db->sql_fetchrow($result);

	$ret .= "планет с новыми игроками: " . $row["pl_cnt"] . "<br />\n\r";
	
	return $ret;
};
*/
function import_vkid() {
	/*
	 ========================
	 
	*/
	global $vg_config,  $db;
	$ret = "";

	$sql = 'SELECT `t`.`vkid` , `g`.`gamers_id`
FROM `' . TABLE_VG_TEMP_PLANETS . '` AS `t`
LEFT JOIN `'. TABLE_VG_GAMERS .'` AS `g` ON `t`.`vkid` = `g`.`gamers_socialnet_id`
WHERE `g`.`gamers_socialnet_id` IS NULL
GROUP BY `t`.`vkid`;';	

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или таблице ID игроков', '', __LINE__, __FILE__, $sql);
		};

	$gid_new = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$gid_new[] = '"' . $row["vkid"] . '"';
	};
	
	$row_count = 0;
	$i = 0;
	$ic = 0;
	$gid_insert = array();
	while (strlen($gid_new[$i])) {
		
		if ($ic<30000) {
			$gid_insert[] = $gid_new[$i];
			$ic++;
		}
		else {
			$gid_import =  implode('), (', $gid_insert);
			$sql = 'INSERT INTO `'. TABLE_VG_GAMERS .'` (`gamers_socialnet_id`) VALUES ('.$gid_import.');';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или таблице ID игроков', '', __LINE__, __FILE__, $sql);
				};
			unset($gid_insert);
			unset($gid_import);
			$gid_insert = array();
			$ic = 0;
			$i--;
		};

		$row_count = ($i+1);
		$i++;
	};

	// вносим последнюю партию
	if (count($gid_insert)) {
			$gid_import =  implode('), (', $gid_insert);
			$sql = 'INSERT INTO `'. TABLE_VG_GAMERS .'` (`gamers_socialnet_id`) VALUES ('. $gid_import.');';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или таблице ID игроков', '', __LINE__, __FILE__, $sql);
				};
			unset($gid_insert);
			unset($gid_import);
	};

	$ret .= "импортированно новых ID: " . $row_count . "<br />\n\r";

//	$sql = 'INSERT INTO `'. TABLE_VG_GAMERS .'` (`gamers_socialnet_id`) VALUES ('.implode(", ", $gid_new).');';
//echo strlen($sql);
	

	return $ret;
	};

function tplanet_import($mark) {
	/*
	 ========================
	 импортим планеты.
	 ========================
	*/
	
	global $vg_config,  $db;
	$ret = "";
	
//	if ($mark == "new" or $mark == "upd") {
		// диапазон планет во временной базе
		$sql = 'SELECT MIN(coord) AS coord_min, MAX(coord) AS coord_max FROM `' . TABLE_VG_TEMP_PLANETS . '`';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице планет', '', __LINE__, __FILE__, $sql);
		};
		$cord_diapazon = $db->sql_fetchrow($result);
//	};

	if ($mark == "new") {
		// новые планеты	
		$sql = 'SELECT t.`coord`, `n`.`name_id`, `g`.`gamers_id`, `t`.`update_date`
FROM `' . TABLE_VG_TEMP_PLANETS . '` AS `t` 
LEFT JOIN `' . TABLE_VG_PLANETS . '` AS `p` ON `t`.`coord` = `p`.`planet_coord`
LEFT JOIN `' . TABLE_VG_GAMERS . '` AS `g` ON `t`.`vkid` = `g`.`gamers_socialnet_id` 
LEFT JOIN `' . TABLE_VG_PLANET_NAME . '` AS `n` ON `t`.`planet_name` = `n`.`planet_name` 
WHERE `p`.`planet_coord` IS NULL 
AND `t`.`coord` BETWEEN "'.$cord_diapazon["coord_min"].'" AND "'.$cord_diapazon["coord_max"].'" ;';

		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или еще какой', '', __LINE__, __FILE__, $sql);
		};

//		$i = 0;
		$planet_new = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$planet_new[] = '("'.$row["coord"].'", "'.$row["name_id"].'", "'.$row["gamers_id"].'", "'.$row["update_date"] . '")';
		};

		$ic = 0;
		$i = 0;
		$row_count = 0;
		$planet_import = array();
		while (strlen($planet_new[$i])) {
			if ($ic<10000) {
				$planet_import[] = $planet_new[$i];
				$ic++;
			}
			else {
				$planet_import[] = $planet_new[$i];
				$import =  implode(', ', $planet_import);
				$sql = 'INSERT INTO `' . TABLE_VG_PLANETS . '` (`planet_coord`, `planet_name_id`, `gamer_id`, `update_date`) VALUES '.$import.';';
				if ( !($result = $db->sql_query($sql)) ) {
					message_die(GENERAL_ERROR, 'ошибка добавления планет в таблицу', '', __LINE__, __FILE__, $sql);
				};
	
				unset($import);
				unset($planet_import);
				$planet_import = array();
				$ic = 0;
			};

			$row_count = ($i+1);
			$i++;			
		};
		// последняя партия.
		if ($import =  implode(', ', $planet_import)) {
				$sql = 'INSERT INTO `' . TABLE_VG_PLANETS . '` (`planet_coord`, `planet_name_id`, `gamer_id`, `update_date`) VALUES '.$import.';';
//				if ( !($result = $db->sql_query($sql)) ) {
//					message_die(GENERAL_ERROR, 'ошибка добавления планет в таблицу', '', __LINE__, __FILE__, $sql);
//				};
	
				unset($import);
				unset($planet_import);
		};
		
		$ret .= "Добавлено планет: ". ($row_count) . " (если испортированно 50к - повторить)<br />\n\r"; 
		
	}
	elseif ($mark == "del") {
		// удаленные планеты
		$sql = 'SELECT `p`.`planet_coord` 
FROM `' . TABLE_VG_PLANETS . '` AS `p` 
LEFT JOIN `' . TABLE_VG_TEMP_PLANETS . '` AS `t` ON `p`.`planet_coord` = `t`.`coord` 
WHERE t.`coord` IS NULL 
AND `p`.`planet_coord` BETWEEN "'.$cord_diapazon["coord_min"].'" AND "'.$cord_diapazon["coord_max"].'";';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или еще какой', '', __LINE__, __FILE__, $sql);
		};

		$planets = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$planets[] = $row["planet_coord"];
		};
//		echo "<pre>" . var_dump($planets) , "</pre>";
		$planet_del = implode(', ', $planets);
		$sql = 'UPDATE `' . TABLE_VG_PLANETS . '` SET is_del = 1 WHERE `planet_coord` IN ('.$planet_del.');';

		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или еще какой', '', __LINE__, __FILE__, $sql);
		};

		$ret .= "Удалено ". count($planets) . " планет<br />\n\r";
		
	}
	elseif ($mark == "upd") {
		// планеты с обновленной картой
		// новые планеты	
		$sql = 'SELECT t.`coord`, `n`.`name_id`, `g`.`gamers_id`, `t`.`update_date`
FROM `' . TABLE_VG_TEMP_PLANETS . '` AS `t` 
LEFT JOIN `' . TABLE_VG_PLANETS . '` AS `p` ON `t`.`coord` = `p`.`planet_coord`
LEFT JOIN `' . TABLE_VG_GAMERS . '` AS `g` ON `t`.`vkid` = `g`.`gamers_socialnet_id` 
LEFT JOIN `' . TABLE_VG_PLANET_NAME . '` AS `n` ON `t`.`planet_name` = `n`.`planet_name` 
WHERE `t`.`update_date` > `p`.`update_date`
AND `t`.`coord` BETWEEN "'.$cord_diapazon["coord_min"].'" AND "'.$cord_diapazon["coord_max"].'" LIMIT 80000;';

		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или еще какой', '', __LINE__, __FILE__, $sql);
		};

//		$i = 0;
		$planet_new = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$planet_new[] = '`planet_name_id` = "'.$row["name_id"].'",  `update_date` = "'.$row["update_date"] . '", `gamer_id` = "'.$row["gamers_id"].
				'", `is_del` = NULL WHERE `planet_coord` ="'.$row["coord"].'";' . "\n";
		};

		$ic = 0;
		$i = 0;
		$row_count = 0;
//		$planet_import = array();
		while (strlen($planet_new[$i])) {
//			if ($ic==1) {
//				$planet_import[] = $planet_new[$i];
//				$ic++;
//			}
//			else {
//				$planet_import[] = $planet_new[$i];
//				echo "<pre>";
				$sql =  'UPDATE `' . TABLE_VG_PLANETS . '` SET ' . $planet_new[$i];

//				$sql =  'UPDATE `' . TABLE_VG_PLANETS . '` SET ' . implode('UPDATE `' . TABLE_VG_PLANETS . '` SET ', $planet_import);
//				echo "</pre>";
//				echo strlen($sql) . "<br>";
//				$sql = 'INSERT INTO `' . TABLE_VG_PLANETS . '` (`planet_coord`, `planet_name_id`, `gamer_id`, `update_date`) VALUES '.$import.';';
				if ( !($result = $db->sql_query($sql)) ) {
					message_die(GENERAL_ERROR, 'ошибка обновления планет в таблице', '', __LINE__, __FILE__, $sql);
				};
	
//				unset($import);
//				unset($planet_import);
//				$planet_import = array();
//				$ic = 0;
//			};

			$row_count = ($i+1);
			$i++;			
		};
		
		$ret .= "Обновлено планет: ". ($row_count) . " (если импортированно ровное кол-во к - повторить)<br />\n\r"; 
				

/*		
		$sql = 'SELECT `t`.`coord`, `t`.`update_date`, `p`.`planet_coord`, `p`.`update_date`
FROM `' . TABLE_VG_TEMP_PLANETS . '` AS `t` , `' . TABLE_VG_PLANETS . '` AS `p` 
WHERE `t`.`coord` = `p`.`planet_coord`
AND `t`.`update_date` > `p`.`update_date`;';
*/
	};
	
	
	return $ret;
};

function gamers_alliances() {
	global $vg_config,  $db;
	// 
	// ========================
	//      импорт альянсов
	// ========================
	//
	$ret = "";

	$sql = 'SELECT `a`.`alliance_id`, `g`.`gamers_id`, `t`.`update_date`
FROM `mrsm_vg_temp_planets` AS `t`
LEFT JOIN `mrsm_vg_alliance` AS `a` ON `a`.`alliance_name` = `t`.`alliance`
LEFT JOIN `mrsm_vg_gamers` AS `g` ON `t`.`vkid` = `g`.`gamers_socialnet_id`
WHERE `t`.`alliance` != ""
GROUP BY `t`.`vkid`, `a`.`alliance_name`;';
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к временной таблице и/или еще какой', '', __LINE__, __FILE__, $sql);
	};

	$row_count = 0;
	$g_alliance_import = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$g_alliance_import[] = '("'.$row["alliance_id"].'", "'.$row["gamers_id"] . '", "'.$row["update_date"] . '")';
		$row_count = $row_count+1;
	};

	if ($import =  implode(', ', $g_alliance_import)) {
			$sql = 'INSERT INTO `' . TABLE_VG_GAMERS_ALLIANCE . '` (`alliance_id`, `gamers_id`, `upd_date`) VALUES '.$import.';';
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'ошибка добавления планет в таблицу', '', __LINE__, __FILE__, $sql);
			};

			unset($import);
			unset($g_alliance_import);
	};
		
	$ret .= "добавлено игроков с альнсом: ". ($row_count) . " (если испортированно 50к - повторить)<br />\n\r"; 
		
	
	return $ret;
};

function ot_aw () {
	global $vg_config,  $db;
	// 
	// ========================
	//      апдейт статусов игроков (ot, aw, ban и пр)
	// ========================
	//
	$ret = "";
	
	$sql = 'SELECT DISTINCT `g`.`gamers_id` , `tp`.`g_leave` , `tp`.`attacks_count` '.
			'FROM `'.TABLE_VG_TEMP_PLANETS.'` AS `tp` '.
			'INNER JOIN `'.TABLE_VG_GAMERS.'` AS `g` ON `tp`.`vkid` = `g`.`gamers_socialnet_id`;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка выборки статусов игроков (ot, aw, ban и пр) из временной таблицы', '', __LINE__, __FILE__, $sql);
	};

	$row_count = 0;
	$g_status = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$g_status[] = $row;
		$row_count = $row_count+1;
	};

	$i = 0;
	while ($g_status[$i]) {
		$sql = 'UPDATE `'.TABLE_VG_GAMERS.'` '.
				'SET `g_leave` = "'.$g_status[$i]["g_leave"].'", `attacks_count` = "'.$g_status[$i]["attacks_count"] . '" '.
				'WHERE `gamers_id` = "'.$g_status[$i]["gamers_id"].'";';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка апдейта статуса игрока (ot, aw, ban и пр)', '', __LINE__, __FILE__, $sql);
		};
		
		$i++;
	};

	$ret .= "Обновлено статусов у: ". ($row_count) . " игроков<br />\n\r"; 
	
	return $ret;
};

function update_planets () {
	global $vg_config,  $db;
	// 
	// ========================
	//      апдейт планет
	// ========================
	//
	$ret = "";
	
	return $ret;
};



/*


SELECT `p`.`gamer_id` 
FROM `mrsm_vg_temp_myplanets` AS `my` 
INNER JOIN `mrsm_vg_planets` AS `p` ON `my`.`coord` = `p`.`planet_coord` 
INNER JOIN `mrsm_vg_gamers` AS `g` ON `p`.`gamer_id` = `g`.`gamers_id` 
WHERE `g`.`gamers_group` IS NULL 
GROUP BY `p`.`gamer_id`


SELECT DISTINCT `ga`.`alliance_id`, `a`.`alliance_name`
FROM `mrsm_vg_gamers_alliance` AS `ga`
INNER JOIN `mrsm_vg_alliance` AS `a` ON `ga`.`alliance_id` = `a`.`alliance_id`
WHERE `ga`.`gamers_id` IN ((
	SELECT DISTINCT ga1.`gamers_id` 
	FROM `mrsm_vg_gamers_alliance` AS `ga1`
	WHERE ga1.`alliance_id` = 1555
))
ORDER BY `a`.`alliance_name` ASC

*/
?>