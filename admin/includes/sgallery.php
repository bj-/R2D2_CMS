<?php
// Редактирование текстов (статей)

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

$get_article = substr($_GET['id'],0,11);
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами
$get_sgal_edit = substr($_GET['sgallery'],0, 15);

if ($_GET["sgal_action"]) {
	$sgal_delete_img = (substr($_GET["sgal_action"], 0, 3) == "del") ? "delete" : FALSE;
	$sgal_action_id = substr($_GET["sgal_action"], (strrpos($_GET["sgal_action"], "-")+1));
};

// переключалка 
if (@$article_prop) {
	$sql_sgal = 'SELECT * FROM '.TABLE_ARTILE_GALLERY.' WHERE `article_id` = "' .$get_article.'" and `source_id` = "1";';
	$sgal_source_id = 1;
	$sgal_source_path = "article";
}
ElseIf (@$event_prop) {
	$sql_sgal = 'SELECT * FROM '.TABLE_ARTILE_GALLERY.' WHERE `article_id` = "' .$get_article.'" and `source_id` = "2";';
	$sgal_source_id = 2;
	$sgal_source_path = "events";
	};


// удаление картинки
if (@$sgal_delete_img) {
	if ($get_sgal_edit=='edit') {
		$sql = 'SELECT * FROM '.TABLE_ARTILE_GALLERY.' WHERE `aimg_id` = "' .$sgal_action_id.'";';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка доступа к таблице минигалереи', '', __LINE__, __FILE__, $sql);
			};

		$smGal_del_data = array();
		$row = $db->sql_fetchrow($result);
		$smGal_del_data = $row;

		$sgal_big_file = $DRoot . $miniGal_path . $smGal_del_data["img_path"];
		$sgal_small_file = $DRoot . $miniGal_path . "sm/" . $smGal_del_data["img_path"];
		if (file_exists($sgal_big_file)) { unlink($sgal_big_file); };
		if (file_exists($sgal_small_file)) { unlink($sgal_small_file); };
	};
	$sql = 'DELETE FROM '.TABLE_ARTILE_GALLERY.' WHERE `aimg_id` = "'.$sgal_action_id.'";';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка удаления картинки из минигалереи', '', __LINE__, __FILE__, $sql);
		};
};


// добавление видео
if ($_POST["video_add"]) {
	$video_code = substr($_POST["video_code"],0,15000);
	$video_desc = substr($_POST["video_desc"],0,255);
	$video_name = substr($_POST["video_name"],0,255);
//	echo $_POST["video_code"] . $_POST["video_desc"] . $_POST["video_name"];
	
		$sql_max_srt = 'SELECT MAX(`img_sort`) as `max_srt` FROM `'.TABLE_ARTILE_GALLERY.'` where `article_id` = "'.$get_article.'" and `source_id` = "'.$sgal_source_id.'" and `video_path` is NOT NULL;';
	if ( !($result = $db->sql_query($sql_max_srt)) ) {
		message_die(GENERAL_ERROR, 'Ошибка выяснения кол-ва фотографий в минигалее', '', __LINE__, __FILE__, $sql);
	};
	$sm_gal_data = $db->sql_fetchrow($result);
	$sm_gal_sort = $sm_gal_data["max_srt"]+1;
	
	$sql_insert = "INSERT INTO `".TABLE_ARTILE_GALLERY."` ".
					" (`article_id`, `source_id` , `img_sort` , `img_name` , `img_desc` , `video_path`) ".
					" VALUES ('".$get_article."', '".$sgal_source_id."', '".$sm_gal_sort."', '".$video_name."', '".$video_desc."', '".$video_code."');";
	if ( !($result = $db->sql_query($sql_insert)) ) {
		message_die(GENERAL_ERROR, 'Ошибка занесения видеоролика в галерею', '', __LINE__, __FILE__, $sql);
	};
};

 

if ($_GET['sgallery']=='upload' and $userdata['user_level'] >0) {
	$template->assign_block_vars('switch_smgallery_upload', array());
}
if ($_GET['sgallery']=='v-upload' and $userdata['user_level'] >0) {
	$template->assign_block_vars('switch_smvideogallery_upload', array());
}
elseif ($_GET['sgallery']=='edit' and $userdata['user_level'] >0) {

	// Сохраняем отредактированные описания
	if ($_POST['smGalSave']) {
		$i = 0;
		while ($i<$_POST['img_total']) {
			$s_img_name = ($_POST['img_name'][$i]=='') ? "NULL" : "'".substr($_POST['img_name'][$i],0,255)."'";
			$s_img_desc = ($_POST['img_desc'][$i]=='') ? "NULL" : "'".substr($_POST['img_desc'][$i],0,255)."'";

			$sql = "UPDATE `".TABLE_ARTILE_GALLERY."` SET `img_name` =  ".$s_img_name.", `img_desc` =  ".$s_img_desc." WHERE `aimg_id` = '" . $_POST['img_id'][$i] ."'; \n";

			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'Информация в минигалерее не обновлена', '', __LINE__, __FILE__, $sql);
			};

			$i++;
		};
	};

	$template->assign_block_vars('switch_smgallery_edit', array());

	$sql = $sql_sgal;
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице минигалереи', '', __LINE__, __FILE__, $sql);
		};

	$smGal_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$smGal_data[] = $row;
		};
	$i = 0;
	while ($smGal_data[$i]['aimg_id']) {
		if (!$smGal_data[$i]['video_path']) {
			$template->assign_block_vars('switch_smgallery_edit.smgallery_edit_list', array(
				'IMG_ID' => $smGal_data[$i]['aimg_id'],
				'IMG_NAME' => $smGal_data[$i]['img_name'],
				'IMG_DESC' => $smGal_data[$i]['img_desc'],
				'IMG_PATH' => $miniGal_path . $smGal_data[$i]['img_path'],
				'SMALL_IMG_PATH' => $miniGal_path . "sm/" . $smGal_data[$i]['img_path'],
			));
		};
	$i++;
	};
}
ElseIf ($_GET['sgallery']=='v-edit' and $userdata['user_level'] >0) {

	// Сохраняем отредактированные описания
	if ($_POST['smGalSave']) {
		$i = 0;
		while ($i<$_POST['img_total']) {
			$s_img_name = ($_POST['img_name'][$i]=='') ? "NULL" : "'".substr($_POST['img_name'][$i],0,255)."'";
			$s_img_desc = ($_POST['img_desc'][$i]=='') ? "NULL" : "'".substr($_POST['img_desc'][$i],0,255)."'";

			$sql = "UPDATE `".TABLE_ARTILE_GALLERY."` SET `img_name` =  ".$s_img_name.", `img_desc` =  ".$s_img_desc." WHERE `aimg_id` = '" . $_POST['img_id'][$i] ."'; \n";

			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'Информация в минигалерее не обновлена', '', __LINE__, __FILE__, $sql);
			};

			$i++;
		};
	};

	$template->assign_block_vars('switch_smvideogallery_edit', array());

	$sql = $sql_sgal;
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице минигалереи', '', __LINE__, __FILE__, $sql);
		};

	$smGal_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$smGal_data[] = $row;
		};
	$i = 0;
	while ($smGal_data[$i]['aimg_id']) {
		if ($smGal_data[$i]['video_path']) {
			$template->assign_block_vars('switch_smvideogallery_edit.smgallery_edit_list', array(
				'IMG_ID' => $smGal_data[$i]['aimg_id'],
				'IMG_NAME' => $smGal_data[$i]['img_name'],
				'IMG_DESC' => $smGal_data[$i]['img_desc'],
				'VIDEO_PATH' => $smGal_data[$i]['video_path'],
				'SMALL_IMG_PATH' => $miniGal_path . "sm/" . $smGal_data[$i]['img_path'],
			));
		};
	$i++;
	};

};
/*
// включить выключить галерею)
if ($_GET['sgallery'] == 'on') {
	$sql = "UPDATE `" . TABLE_ARTICLE . "` SET `article_sgal_on` =  '1' WHERE `article_id` = '" . substr($_GET['article'],0, 11) ."'";
	$smGal_status = 1;
}
ElseIf ($_GET['sgallery'] == 'off') {
	$sql = "UPDATE `" . TABLE_ARTICLE . "` SET `article_sgal_on` =  NULL WHERE `article_id` = '" . substr($_GET['article'],0, 11) ."'";
	$smGal_status = 0;
};

if ($_GET['sgallery']) {
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка включения/отключения галереи', '', __LINE__, __FILE__, $sql);
	};
};
*/
//echo"sdsadaD".$smVideoGal_onoff;
$template->assign_vars(array(
	'PAGE_ID' => $page_id,
	'SOURCE_ID' => $sgal_source_id,

	'LANG' => $url_lang,

	'GALLERY_ONOFF' => $smgal_onoff,
	'VGALLERY_ONOFF' => $smVideoGal_onoff,
	
//	'PAGE_TITLE' => $page_title,
//	'PAGE_DESC' => $page_desc,

	'SUBMIT_PATH' => '/admin/'.$sgal_source_path.'_prop.php?id='.$get_article.'&sgallery='.$get_sgal_edit,
	'IMG_TOTAL' => count($smGal_data),
	
	'SGALLERY_EDITTYPE' => $_GET['sgallery']

	));

?>
