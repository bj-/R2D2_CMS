<?php
/***************************************************************************
 *                                dyn_gallery_cat.php
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

//$cat_id = substr($_GET["id"],0,11);
//$page =  substr($_GET["page"],0,11);
$limit = intval(substr($_GET["limit"],0,2));

$sql = 'SELECT `cat_id`, `cat_pid`, `cat_sort`, `cat_img`, `cat_name`, `cat_desc`, `cat_path`, `cat_type`, `gallery_text` '.
		' FROM `' . TABLE_GALLERY_CAT . '` ' .
		'WHERE `cat_pid` >= "0" AND `cat_type` = "1" '.
		'GROUP BY RAND() '.
		'LIMIT 0, '.$limit.';';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База галерей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$gallery_cat_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$gallery_cat_data[] = $row;
	};


$template->set_filenames(array(
	'body' => 'dynamic/gallery/dyn_gal_cat.tpl')
);

//$template->assign_vars(array(
//	'GALLERY_CLASS' => "highslide-gallery175"
//));


$i = 0;
while ($gallery_cat_data[$i]["cat_id"]) {
	$gat_img = ($gallery_cat_data[$i]["cat_img"]) ? $gallery_path.$gallery_cat_data[$i]["cat_img"] : "/pic/ico/question_b.gif";
	$template->assign_block_vars('gallery_cat_list', array(
		'GALLERY_CAT_IMG' => $gat_img,
		'GALLERY_CAT_NAME' => $gallery_cat_data[$i]["cat_name"],
		'GALLERY_CAT_URL' => '/ru/gallery/' . $gallery_cat_data[$i]["cat_path"] . '-' .$gallery_cat_data[$i]["cat_id"] . '/',
	));
	$i++;
};




//
// Generate the page
//
$template->pparse('body');

?>