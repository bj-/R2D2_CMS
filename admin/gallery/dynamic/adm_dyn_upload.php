<?php
/***************************************************************************
 *                                gallery_list.php
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

$id = substr($_GET["id"],0,11);
$page =  substr($_GET["page"],0,11);
$type = substr($_GET["type"],0 , 20);;


$template->set_filenames(array(
	'body' => 'admin/gallery/adm_dyn_upload.tpl')
);

$template->assign_vars(array(
	'PAGE_ID' => $id,
	'REDIRECT_URL' => $_GET["redirect_url"],
	'TYPE' => $type,
));

if ($userdata['user_level'] >0) {
	if ($type=="photo") {
		$template->assign_block_vars('switch_upload_photo', array(
		));
	
	}
	elseif ($type=="video" or $type=="smvideo") {
		$template->assign_block_vars('switch_upload_video', array(
		));
	};
}
else {
		$template->assign_block_vars('no_right', array(
		));
};
/*
$sql = "SELECT * FROM `" . 
TABLE_GALLERY_IMG . '` WHERE `cat_id` = "'.$cat_id.'"';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База галерей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$gallery_img_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$gallery_img_data[] = $row;
	};




$i = 0;
while ($gallery_img_data[$i]["img_id"]) {
	if (!$gallery_img_data[$i]["video_path"]) {
		$template->assign_block_vars('gallery_preview_list', array(
			'IMG_PATH' => $gallery_path.$gallery_img_data[$i]["cat_id"]."/".$gallery_img_data[$i]["img_path"],
			'IMG_NAME' => $gallery_img_data[$i]["img_name"],
			'SMALL_IMG_PATH' => $gallery_path.$gallery_img_data[$i]["cat_id"]."/sm/".$gallery_img_data[$i]["img_path"],
			'IMG_DESC' => $gallery_img_data[$i]["img_desc"]
		));
	};
	$i++;
};
*/



//
// Generate the page
//
$template->pparse('body');

?>