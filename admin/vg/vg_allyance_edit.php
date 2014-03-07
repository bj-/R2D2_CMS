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
include("../../../db/config.php");
include($DRoot . '/db/extension.inc');
include($DRoot . '/db/common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);
//
// End session management
//
include($DRoot . '/mod/vg/vg_config.'.$phpEx);
include($DRoot . $admin_patch . '/mod/vg/vg_admin_functions.'.$phpEx);

$vg_config = vg_config();

//$src_db_files = vg_get_db_files();



//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Импорт/апдейт базы планет";
$page_classification = "";
$page_desc = "";
$page_keywords = "";
$page_content_direction = "";


$submit_path = "";
$page_id = "";

$page_path = "";
$page_text = "";


include($DRoot . '/db/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'admin/mod/vg/vg_allyance_edit.tpl')
);
$template->assign_block_vars('switch_left_menu', array());


$template->assign_vars(array(
//	'CURRENT_BASE' => $vg_config["vg_curr_db"],
//	'SRC_DB_STAT' => @$vg_stat,
//	'FUNC_RET' => $func_ret,
	'ARTICLE_ID' => $page_id,
//	'EDIT' => @$edit
	));

//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/db/page_tail.'.$phpEx);

?>
