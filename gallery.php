<?php
/***************************************************************************
 *                                article.php
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
include("includes/config.php");
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

//
// собираем страницу со статьей
//
$get_id = $_GET['id'];
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами

// вычленяем id галереи
if (strrpos($get_id,"-")) {
	if (strpos($get_id,"video")) {
		$get_id = substr($get_id, 0, strpos($get_id,"video"));
		$show_video = TRUE;
	};
	$get_id = substr($get_id,(strrpos($get_id,"-")+1), 11);
	$get_id = (strrpos($get_id,"/")) ? substr($get_id,0, (strrpos($get_id,"-")-1)) : $get_id;
}
else {
	$get_id = substr($get_id,0, 11);
	$show_video = FALSE;
};
$get_id = (@$get_id) ? $get_id : "0";

//
// выбираем все галерии из бд
//
$sql = "SELECT * FROM `" . 
TABLE_GALLERY_CAT . '` WHERE `cat_type`="1" ORDER BY `cat_sort` ASC';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База галерей отсутствует', '', __LINE__, __FILE__, $sql);
	};

$gallery_cat_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$gallery_cat_data[] = $row;
	};

if (!$gallery_cat_data[0]["cat_id"]) {
	message_die(GENERAL_ERROR, 'Запрошенная галерея не найдена ' . $add, '', __LINE__, __FILE__, $sql);
};


//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = (@$no_article) ? "Галерея не найдена" : "Фото-видео Галереи";
$page_classification = "";
$page_desc = "Фото-видео галерея";
$page_keywords = "";
$page_content_direction = "";

$page_paragraf_name = "Мультимедиа";
$page_paragraf_path = "gallery";
$page_paragraf_id = "";


$page_id = $get_id;
//$page_unix_date = $article_data[0]["article_date"];
//$page_date = create_date($board_config['article_dateformat'], $article_data[0]["article_date"], $board_config['board_timezone']);
//$check_primary_article = ($article_data[0]["primary_article"]) ? " checked" : "";

$page_path = "";
$page_text = (@$no_article) ? "<p><h2>Запрошенная галерея не найдена</h2></p><p>Жалоба администратору сайта уже написана автоматически, спасибо за помощь.</p>" : "";

if ($userdata['user_level'] >0) {
	$edit = '<a href="/admin/index.php?edit=gallery&add=cat&cat_pid='.$get_id.'" title="Добавить галерею"><img src="/pic/ico/page_add.gif" alt="Добавить галерею" width="16" height="16" border="0" /></a>';
	if ($get_id) {
		$gallery_type = ($show_video) ? "video" : "photo";
		$edit .= '&nbsp;&nbsp;&nbsp;<a href="/admin/index.php?edit='.$gallery_type.'&imageprop=1&cat='.$get_id.'" title="Редактировать текущую галерею"><img src="/pic/ico/edit.gif" alt="Добавить фото в текущую галерею" width="16" height="16" border="0" /></a>
		&nbsp;&nbsp;&nbsp;<a onclick="show_content(\'/admin/gallery/dynamic/adm_dyn_upload.php?id='.$get_id.'&type=photo\', \'#item-add\')" title="Добавить фото в текущую галерею"><img src="/pic/ico/camera_add.png" alt="Добавить фото в текущую галерею" width="16" height="16" border="0" style="cursor:pointer;" /></a>
&nbsp;<a onclick="show_content(\'/admin/gallery/dynamic/adm_dyn_upload.php?id='.$get_id.'&type=video&redirect_url=/ru/gallery/' . $gallery_cat_data[0]["cat_path"] .'-'.$get_id.'/\', \'#item-add\')" title="Добавить видео в текущую галерею"><img src="/pic/ico/film_add.png" alt="Добавить видео в текущую галерею" width="16" height="16" border="0" style="cursor:pointer;" /></a>';	
	};
};

include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'gallery_body.tpl')
);
$template->assign_block_vars('switch_left_menu', array());


$template->assign_block_vars('gallery_catlist', array());
$i=0;
while ($gallery_cat_data[$i]["cat_id"]) {
	if ($gallery_cat_data[$i]["cat_id"]==$get_id) {
		$template->assign_vars(array(
			'GALLERY_PATH' => $gallery_cat_data[$i]["cat_path"] . "-" . $gallery_cat_data[$i]["cat_id"] . "/"
			));
	};

	if ($gallery_cat_data[$i]["cat_pid"]==$get_id) {
		$cat_img = ($gallery_cat_data[$i]["cat_img"]) ? "/img/gallery/".$gallery_cat_data[$i]["cat_img"] : '/pic/ico/question_b.gif';

		if ($userdata['user_level'] >0) {
			$add_sub_gal = '<a href="/admin/index.php?edit=gallery&add=cat&cat_pid='.$gallery_cat_data[$i]["cat_id"].'" title="Добавить вложенную галерею"><img src="/pic/ico/page_add.gif" alt="Добавить вложенную галерею" width="16" height="16" border="0" /></a>';
			$del_gal = '<a href="/admin/index.php?edit=gallery&cat_delete='.$gallery_cat_data[$i]["cat_id"].'&redirect=1"><img src="/pic/ico/delete.gif" alt="Удалить" width="16" height="16" border="0"></a>';
			$cat_edit = '<a href="/admin/index.php?edit=gallery&cat_edit='.$gallery_cat_data[$i]["cat_id"].'">
	<img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0"></a>';
		};

		$template->assign_block_vars('gallery_catlist.gallery_catlist_row', array(
			'CAT_ID' => $gallery_cat_data[$i]["cat_id"],
			'CAT_PID' => $gallery_cat_data[$i]["cat_pid"],
			'CAT_IMG' => $cat_img,
			'CAT_NAME' => $gallery_cat_data[$i]["cat_name"],
			'CAT_PATH' => '/ru/gallery/' . $gallery_cat_data[$i]["cat_path"] .'-'.$gallery_cat_data[$i]["cat_id"].'/',
			'CAT_DESC' => $gallery_cat_data[$i]["cat_desc"],
			'CAT_EDIT' => $cat_edit,
			'CAT_ADD_SUB' => @$add_sub_gal,
			'CAT_DEL' => @$del_gal
		));
	};
	$i++;
};

// содержимое галереи
$sql = "SELECT * FROM `" . 
TABLE_GALLERY_IMG . '` WHERE `cat_id` = "'.$get_id.'" ORDER BY `img_sort` ASC;';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База галерей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$gallery_img_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$gallery_img_data[] = $row;
};

//$template->assign_block_vars('swich_gallery.swich_gallery_upload', array());

if (@$show_video) {
	$menu_style_video =  ' style="text-decoration:none"';
	$template->assign_block_vars('switch_video_gallery', array());
	$i = 0;
	while ($gallery_img_data[$i]["img_id"]) {
		if ($userdata['user_level'] >0) {
			$del_item = '<a href="/admin/index.php?edit=video&imageprop=1&cat='.$gallery_img_data[$i]["cat_id"].'&sgal_action=del-'.$gallery_img_data[$i]["img_id"].'" title="Удалить"><img src="/pic/ico/delete.gif" alt="Удалить" width="16" height="16" border="0"></a>';
		};
		if ($gallery_img_data[$i]["video_path"]) {
			$template->assign_block_vars('switch_video_gallery.gallery_video_list', array(
				'ITEM_ID' => $gallery_img_data[$i]["img_id"],
				'DEL_ITEM' => $del_item,
				'VIDEO_PATH' => $gallery_img_data[$i]["video_path"],
				'VIDEO_NAME' => $gallery_img_data[$i]["img_name"],
				'VIDEO_THUMB' => $gallery_img_data[$i]["video_thumb"],
				'VIDEO_ID' => $gallery_img_data[$i]["video_id"],
//				'SMALL_IMG_PATH' => $gallery_path.$gallery_img_data[$i]["cat_id"]."/sm/".$gallery_img_data[$i]["img_path"],
				'VIDEO_DESC' => $gallery_img_data[$i]["img_desc"]
			));
		};
		$i++;
	};
}
else {
	$menu_style_photo =  ' style="text-decoration:none"';
	$template->assign_block_vars('switch_gallery', array());
	$i = 0;
	while ($gallery_img_data[$i]["img_id"]) {
		if (!$gallery_img_data[$i]["video_path"]) {
			if ($userdata['user_level'] >0) {
				$del_item = '<a href="/admin/index.php?edit=photo&imageprop=1&cat='.$gallery_img_data[$i]["cat_id"].'&sgal_action=del-'.$gallery_img_data[$i]["img_id"].'" title="Удалить"><img src="/pic/ico/delete.gif" alt="Удалить" width="16" height="16" border="0"></a>';
			};
			$template->assign_block_vars('switch_gallery.gallery_img_list', array(
				'DEL_ITEM' => $del_item,
				'IMG_PATH' => $gallery_path.$gallery_img_data[$i]["cat_id"]."/".$gallery_img_data[$i]["img_path"],
				'IMG_NAME' => $gallery_img_data[$i]["img_name"],
				'SMALL_IMG_PATH' => $gallery_path.$gallery_img_data[$i]["cat_id"]."/sm/".$gallery_img_data[$i]["img_path"],
				'IMG_DESC' => $gallery_img_data[$i]["img_desc"]
			));
		};
		$i++;
	};
};

// меню выбора фото-видео галерей
$i = 0;
while ($gallery_img_data[$i]["img_id"]) {
	if ($gallery_img_data[$i]["video_path"]) {
		$present_video = True;
	}
	Else {
		$present_photo = True;
	};
	$i++;
};
if (@$present_photo or @$present_video) {
	$template->assign_block_vars('gallery_content_menu', array());
}
if (@$present_photo) {
	$template->assign_block_vars('gallery_content_menu.gallery_content_photo_menu', array(
		'MENU_STYLE' => @$menu_style_photo
	));
};
if (@$present_video) {
	$template->assign_block_vars('gallery_content_menu.gallery_content_video_menu', array(
		'MENU_STYLE' => @$menu_style_video
	));
};



$template->assign_vars(array(
	'SAVED' => @$saved,
	'PATCH_DESCRIPTION' => $paragrad_desc,
	'ARTICLE' => $page_text,
	'ARTICLE_ID' => $page_id,
	'EDIT' => @$edit
	));






//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>