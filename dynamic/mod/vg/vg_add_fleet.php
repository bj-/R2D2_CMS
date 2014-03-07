<?php
/***************************************************************************
 *                                vg_add_fleet.php
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
// Start output of page
//
define('SHOW_ONLINE', true);


date_default_timezone_set("Europe/Moscow");

$template->set_filenames(array(
	'body' => 'dynamic/vg/vg_add_fleet.tpl')
);

if ($userdata['session_logged_in']) {
	$template->assign_block_vars('add_form_fleet', array());

}
else {
	$template->assign_block_vars('no_logged', array());
};


//
// Generate the page
//
$template->pparse('body');

?>