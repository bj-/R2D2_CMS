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

if ($_GET["action"] == 'prewiev') {
	$text = vg_stat();
}
elseif ($_GET["action"] == 'gamers_alliances') {
	$text = gamers_alliances();
}
elseif ($_GET["action"] == 'p_transfer') {
	$text = truncate_temp_db("base");
	$text .= import_to_tempdb("0");
//	$text .= import_to_tempdb("1");
}
elseif ($_GET["action"] == 'stat_temp_db') {
	$text = stat_temp_db();
}
elseif ($_GET["action"] == 't_transfer') {
	$text = t_transfer();
}
elseif ($_GET["action"] == 't_parse') {
	$text = t_parse();
}
elseif ($_GET["action"] == 'import_names') {
	$text = import_names();
	$text .= alliance_import();
	$text .= import_vkid();
}
elseif ($_GET["action"] == 'id_history') {
	$text = id_history("map");	
}
elseif ($_GET["action"] == 'spy_reports') {
	$text = spy_reports();
}
elseif ($_GET["action"] == 'report_ally_import') {
	$text = report_ally_import();
}
elseif ($_GET["action"] == 'tplanet_import') {
	$text = tplanet_import(substr($_GET["mark"],0,50));
}
elseif ($_GET["action"] == 'otaw') {
	$text = ot_aw();
};

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Импорт/апдейт базы планет - динамические странички.";
$page_classification = "";
$page_desc = "";
$page_keywords = "";
$page_content_direction = "";

$submit_path = "";
$page_id = "";

$page_path = "";
$page_text = "";


$template->set_filenames(array(
	'body' => 'admin/mod/vg/dynamic/admin_dyn_action.tpl')
);

$template->assign_vars(array(
	'TEXT' => $text
));

//
// Generate the page
//
$template->pparse('body');

$ddd=microtime();
$ddd=((double)strstr($ddd, ' ')+(double)substr($ddd,0,strpos($ddd,' ')));
$xxx = (@number_format((@$ddd-$GLOBALS["ttt"]),3));
echo "<br /><small>Время генерации страницы: " . $xxx ." секунд</small>";

?>
