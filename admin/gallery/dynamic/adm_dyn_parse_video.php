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

$id = intval(substr($_GET["id"],0,11));
//$page =  substr($_GET["page"],0,11);
$type = substr($_GET["type"],0 , 20);
$url = trim(substr($_GET["url"],0 , 250));
$galtype = substr($_GET["galtype"],0 , 20);


$template->set_filenames(array(
	'body' => 'admin/gallery/adm_dyn_parse_video.tpl')
);


if (!$userdata['user_level'] >0) {
	message_die(GENERAL_ERROR, 'Нет прав доступа. Вы не залогинены в систему.', '', __LINE__, __FILE__, $sql);
};

function reenter_video_url() {
	global $template, $id, $url;
	
	$template->assign_block_vars("set_url", array());
	$template->assign_block_vars("set_url.url_not_resolved", array());

	$template->assign_vars(array(
		'PAGE_ID' => $id,
		'VIDEO_URL' => $url,
	));
};

//парсим урл с ютуба
if (strpos($url, "v=")) {
	$video_id = substr($url, (strpos($url, "v=")+2));
	$video_id = substr($video_id, strpos($video_id, "&"));
	
}
elseif (strpos($url, ".be/")) {
	$video_id = substr($url, (strpos($url, ".be/")+4));
	$video_id = substr($video_id, strpos($video_id, "&"));
};

// http://www.youtube.com/watch?v=xDoSFqqL4WI&feature=grec_index
// http://youtu.be/dhuI3NxwI8c

$xml_url = 'http://gdata.youtube.com/feeds/api/videos?vq='.$video_id; // IuBaPUtitBE';       //адрес XML документа

// Запрашиваем данные о ролике
if ($xml_file = @file_get_contents($xml_url)) {
	preg_match_all("/(<media:thumbnail )(.*?)(\/\>)/", $xml_file,$video_thumb_res);
	$video_data_arr = explode(" ", $video_thumb_res[2][0]);
	$video_data_arr_s = explode(" ", $video_thumb_res[2][1]);

	// превью
	// Оригинального размера
	$video_data_arr[0] = substr($video_data_arr[0], (strpos($video_data_arr[0], "=")+2));
	$video_thumb = substr($video_data_arr[0], 0, (strlen($video_data_arr[0])-1));
	// маленькое
	$video_data_arr_s[0] = substr($video_data_arr_s[0], (strpos($video_data_arr_s[0], "=")+2));
	$video_thumb_s = substr($video_data_arr_s[0], 0, (strlen($video_data_arr_s[0])-1));


	// размеры
	$video_h = preg_replace('/[^0-9]+/', '', $video_data_arr[1]);
	$video_w = preg_replace('/[^0-9]+/', '', $video_data_arr[2]);

//preg_match("/([0-9])/", $video_data_arr[1], $res);
//print_r($res);
	$xml= simplexml_load_string($xml_file); 
	$video_title = substr(iconv("UTF-8", "windows-1251", $xml->entry->title),0,240);; 
	$video_content = substr(iconv("UTF-8", "windows-1251", $xml->entry->content),0,240); 
	$video_author =  iconv("UTF-8", "windows-1251", $xml->entry->author->name);

//echo "<pre>";
//print_r($xml);

	if ($video_author) {

		$template->assign_block_vars("show_data", array());
		$template->assign_vars(array(
			'PAGE_ID' => $id,
			'VIDEO_THUMB_URL_BIG' => $video_thumb,
			'VIDEO_THUMB_URL_S' => $video_thumb_s,
			'VIDEO_TITLE' => $video_title,
			'VIDEO_CONTENT' => $video_content,
			'VIDEO_H' => $video_h,
			'VIDEO_W' => $video_w,
			'VIDEO_ID' => $video_id,
			'GALTYPE' => $galtype,
		));
	}
	else {
		reenter_video_url();
	};
}
else {
	reenter_video_url();
	
}

//echo "<pre>";
//print_r($xml);


/*
echo 
$video_thumb . "<br>".
$video_title . "<br>".
$video_content . "<br>".
$video_h . "<br>".
$video_w . "<br>";
*/

/*
if ($userdata['user_level'] >0) {
	if ($type=="photo") {
		$template->assign_block_vars('switch_upload_photo', array(
		));
	
	}
	elseif ($type=="video") {
		$template->assign_block_vars('switch_upload_video', array(
		));
	};
}
else {
		$template->assign_block_vars('no_right', array(
		));
};
*/


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