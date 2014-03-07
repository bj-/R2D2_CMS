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
include("../../../includes/config.php");
//include($DRoot . '/db/extension.inc');
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

$src_db_files = vg_get_db_files();

//echo "dsfsdf";
//var_dump($src_db_files);


//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Импорт/апдейт базы планет - список доступных для импорта баз";
$page_classification = "";
$page_desc = "";
$page_keywords = "";
$page_content_direction = "";

$submit_path = "";
$page_id = "";

$page_path = "";
$page_text = "";


$template->set_filenames(array(
	'body' => 'admin/mod/vg/dynamic/admin_dyn_src_db_list.tpl')
);

$i = 0;
while ($src_db_files[$i]["name"]) {
	$file_size = number_format(($src_db_files[$i]["size"]/1048576), 2, '.', ' '); // переводим в метры
	$template->assign_block_vars('src_db_list', array(
		'FILE' => $src_db_files[$i]["name"],
		'URL_CHANGE' => $src_db_files[$i]["name"],
		'FILE_SIZE' => $file_size
	));
	$i++;
};
//
// Generate the page
//
$template->pparse('body');

?>
