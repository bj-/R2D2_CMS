<?php
/***************************************************************************
 *                                vg_db_admin 
 * 											index.php
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
include("../../includes/config.php");
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);
//
// End session management
//
include($DRoot . '/mod/vg/vg_config.'.$phpEx);
include($DRoot . $admin_patch . '/vg/vg_admin_functions.'.$phpEx);

$vg_config = vg_config();

//$src_db_files = vg_get_db_files();

// меняем текущую базу данных
if ($_GET["changedb"]) {
	$new_db = str_replace("\\", "", str_replace("/", "", substr($_GET["changedb"],0,255)));
	
	$new_db_fullpath = $DRoot . $vg_config["upload_dir"] . $new_db;
	if (is_file($new_db_fullpath)) { // обработчик неверных и несуществующих имен файлов
		$file_info = pathinfo($new_db_fullpath);
		$update_curr_db = ($file_info['extension'] == 'db') ? TRUE : FALSE;
	}
	else {
		$update_curr_db = FALSE;
	};

	$sql = 'UPDATE `'.TABLE_VG_CONFIG.'` SET `vg_config_value` = "'.$new_db.'" WHERE `vg_config_name` = "vg_curr_db"';

	if ($update_curr_db) {
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка изменения текущего файла импортируемой базы (VGDB)', $new_db, __LINE__, __FILE__, $sql);
		};
		$vg_config["vg_curr_db"] = $new_db;
	}
	else {
			message_die(GENERAL_ERROR, 'неверно указано файла импортируемой базы (VGDB) либо фай не существует', $new_db, __LINE__, __FILE__, $sql);
	};
};

/*
if ($_GET["action"] == 'prewiev') {
	$vg_stat = vg_stat();
}
elseif ($_GET["action"] == 'truncate_temp') {
	$func_ret = truncate_temp_db();
}
elseif ($_GET["action"] == 'p_transfer') {
	$func_ret = import_to_tempdb();
};
*/

//
// temp block
//

/*
$sql = 'SELECT `planet_name` , count( `planet_name` ) AS pcnt
FROM `mrsm_vg_planet_name` 
GROUP BY `planet_name` 
ORDER BY `pcnt` DESC ';

$sql = 'SELECT `alliance_name` , count( `alliance_name` ) AS `ancnt` 
FROM `mrsm_vg_alliance` 
GROUP BY `alliance_name` 
ORDER BY `ancnt` DESC;';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'ошибка изменения текущего файла импортируемой базы (VGDB)', $new_db, __LINE__, __FILE__, $sql);
};
$bad_id = array();
while( $row = $db->sql_fetchrow($result) ) {
	if ($row["pcnt"]>1) {
		$bad_id[] = $row;
		$bad_vk_id[] = mysql_real_escape_string($row["planet_name"]);
	};
};

echo "плохих названий планет:" . count($bad_id) . "<br>\r\n";

//$sql = 'DELETE FROM `mrsm_vg_gamers` WHERE `gamers_socialnet_id` IN (' . implode(", ", $bad_vk_id) . ');';
//$sql = 'DELETE FROM `mrsm_vg_planet_name` WHERE `planet_name` IN ("' . implode('", "', $bad_vk_id) . '");';

$sql = 'SELECT `name_id` FROM `mrsm_vg_planet_name` WHERE `planet_name` IN ("' . implode('", "', $bad_vk_id) . '");';
if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'ошибка изменения текущего файла импортируемой базы (VGDB)', $new_db, __LINE__, __FILE__, $sql);
};
$bad_gid = array();
while( $row = $db->sql_fetchrow($result) ) {
	$bad_gid[] = $row["name_id"];
};


$sql = 'SELECT count(*) FROM `mrsm_vg_planet_name` WHERE `name_id` IN ('. implode(", ", $bad_gid).');';
if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'ошибка изменения текущего файла импортируемой базы (VGDB)', $new_db, __LINE__, __FILE__, $sql);
};
$bad_allyrow = $db->sql_fetchrow($result);

print_r($bad_allyrow);
echo "<br>\n\r";


$sql = 'SELECT * FROM `mrsm_vg_planets` WHERE `planet_name_id` IN ('. implode(", ", $bad_gid).');';
//$sql = 'DELETE FROM `mrsm_vg_planets` WHERE `gamer_id` IN ('. implode(", ", $bad_gid).');';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'ошибка изменения текущего файла импортируемой базы (VGDB)', $new_db, __LINE__, __FILE__, $sql);
};
//$bad_plntrow = $db->sql_fetchrow($result);
$bad_plntrow = array();
while( $row = $db->sql_fetchrow($result) ) {
	$bad_plntrow[] = $row;
};

echo '<pre>';
print_r($bad_plntrow);
echo '</pre>';
echo "<br>\n\r";
 


//echo $sql . "<br>\n\r";

echo count($bad_gid);
//echo implode(", ", $bad_gid);

/*
$i = 0;
while ($bad_id[$i]["gcnt"]) {
echo	$sql = 'SELECT * FROM `mrsm_vg_gamers` WHERE `gamers_socialnet_id` = "'.$bad_id[$i]["gamers_socialnet_id"].'";' ;
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка изменения текущего файла импортируемой базы (VGDB)', $new_db, __LINE__, __FILE__, $sql);
	};
	unset($bad_id_ev);
	$bad_id_ev = array();
	while( $row = $db->sql_fetchrow($result) ) {
			$bad_id_ev[] = $row;
	};


	$e = 0;
	while ($bad_id_ev[$e]["gamers_id"]) {
		$sql = 'SELECT count(*) AS gall_cnt FROM `mrsm_vg_gamers_alliance` WHERE `gamers_id` = "'.$bad_id_ev[$e]["gamers_id"].'";<br>';
//		if ( !($result = $db->sql_query($sql)) ) {
//			message_die(GENERAL_ERROR, 'ошибка изменения текущего файла импортируемой базы (VGDB)', $new_db, __LINE__, __FILE__, $sql);
//		};
//		$row_1 = $db->sql_fetchrow($result)

echo		$sql = 'SELECT count(*) AS plnt_cnt FROM `mrsm_vg_planets` WHERE `gamer_id` = "'.$bad_id_ev[$e]["gamers_id"].'";<br>';
		
		$e++;
	};

	$i = 500000;
	$i++;
};
*/




//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Импорт/апдейт базы планет";
$page_classification = "";
$page_desc = "";
$page_keywords = "";
$page_content_direction = "";

//$page_paragraf_id = $article_data[0]["paragraf_id"];
//$page_paragraf_name = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_name'];
//$page_paragraf_path = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_path'];

$submit_path = "";
$page_id = "";

$page_path = "";
$page_text = "";


include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'admin/mod/vg/vg_admin_index.tpl')
);
$template->assign_block_vars('switch_left_menu', array());


$template->assign_vars(array(
	'CURRENT_BASE' => $vg_config["vg_curr_db"],
	'SRC_DB_STAT' => @$vg_stat,
	'FUNC_RET' => $func_ret,
//	'ARTICLE_ID' => $page_id,
//	'EDIT' => @$edit
	));

//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>
