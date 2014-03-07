<?php
//		типы галерей
//
//		0 - удалена
//		1 - стандартная
//		50 - скрытая

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

$template->set_filenames(array(
	'gallery_prop_body' => 'admin/gallery_edit_body.tpl')
);
//echo "sdfsdfsdf";
//
// выбираем все галерии из бд
//
$cat_id = substr($_GET['cat'], 0, 11);
$edit_content_type = substr($_GET['edit'], 0, 64);
$video_id = mysql_real_escape_string(substr($_POST['video_id'], 0, 250));
$video_thumb = mysql_real_escape_string(substr($_POST['video_thumb'], 0, 250));
$video_prov = mysql_real_escape_string(substr($_POST['video_prov'], 0, 30));

// ссылки на видео, добавление и сохранение
if ($_POST['video_add']) {
	if ($_POST['video_type'] == "parsed" and $_POST['video_prov'] == "youtube") {
		$video_code = '<iframe width="560" height="345" src="http://www.youtube.com/embed/'.$video_id.'" frameborder="0" allowfullscreen></iframe>';
		
	}
	else {
		$video_code = $_POST['video_code'];
	};
		$video_desc = mysql_real_escape_string(substr($_POST['video_desc'],0,254));
		$video_name = mysql_real_escape_string(substr($_POST['video_name'],0,254));
		$video_size = ($_POST['video_size']) ? "'".mysql_real_escape_string(substr($_POST['video_size'],0,254))."'" : 'NULL';
		
//	video_type

	$sql_max_srt = 'SELECT MAX(`img_sort`) as `max_srt` FROM `'.TABLE_GALLERY_IMG.'` where `cat_id` = "'.$cat_id.'" and `video_path` is NOT NULL;';
	if ( !($result = $db->sql_query($sql_max_srt)) ) {
		message_die(GENERAL_ERROR, 'Ошибка выяснения кол-ва фотографий в минигалее', '', __LINE__, __FILE__, $sql);
	};
	$sm_gal_data = $db->sql_fetchrow($result);
	$sm_gal_sort = $sm_gal_data["max_srt"]+1;
	
	$sql_insert = "INSERT INTO `".TABLE_GALLERY_IMG."` ".
					" (`cat_id`, `img_sort` , `img_name`, `img_desc`, `video_path`, `video_id`, `video_src`, `video_thumb`, `simg_size`) ".
					" VALUES ('".$cat_id."', '".$sm_gal_sort."', '".$video_name."', '".$video_desc."', '".$video_code."', '".$video_id."', '".$video_prov."', '".$video_thumb."', ".$video_size.");";
					
	if ( !($result = $db->sql_query($sql_insert)) ) {
		message_die(GENERAL_ERROR, 'Ошибка занесения видеоролика в галерею', '', __LINE__, __FILE__, $sql);
	};
	

};


// Сохраниение описание фотграфий
if ($_POST["GalImgSave"]) {
	// Сохраняем отредактированные описания
	if ($_POST['GalImgSave']) {
		$i = 0;
		while ($i<$_POST['img_total']) {
			$s_img_name = ($_POST['img_name'][$i]=='') ? "NULL" : "'".mysql_real_escape_string(substr($_POST['img_name'][$i],0,255))."'";
			$s_img_desc = ($_POST['img_desc'][$i]=='') ? "NULL" : "'".mysql_real_escape_string(substr($_POST['img_desc'][$i],0,255))."'";

			$sql = "UPDATE `".TABLE_GALLERY_IMG."` SET `img_name` =  ".$s_img_name.", `img_desc` =  ".$s_img_desc." WHERE `img_id` = '" . $_POST['img_id'][$i] ."'; \n";

			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'Информация в галерее не обновлена', '', __LINE__, __FILE__, $sql);
			};

			$i++;
		};
	};
};

if ($_GET["sgal_action"]) {
	$gal_delete_img = (substr($_GET["sgal_action"], 0, 3) == "del") ? "delete" : FALSE;
	$gal_action_id = substr($_GET["sgal_action"], (strrpos($_GET["sgal_action"], "-")+1));
};
// удаление картинки
if (@$gal_delete_img) {
	$sql = 'SELECT * FROM '.TABLE_GALLERY_IMG.' WHERE `img_id` = "' .$gal_action_id.'";';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка доступа к таблице минигалереи', '', __LINE__, __FILE__, $sql);
		};

	$smGal_del_data = array();
	$row = $db->sql_fetchrow($result);
	$smGal_del_data = $row;

	if ($_GET['edit'] == "photo") {
		$gal_path = $DRoot . "/img/gallery/" . $smGal_del_data["cat_id"] . "/";
		$sgal_big_file = $gal_path . $smGal_del_data["img_path"];
		$sgal_small_file = $gal_path . "sm/" . $smGal_del_data["img_path"];
		if (file_exists($sgal_big_file)) { unlink($sgal_big_file); };
		if (file_exists($sgal_small_file)) { unlink($sgal_small_file); };
	};
	
	$sql = 'DELETE FROM '.TABLE_GALLERY_IMG.' WHERE `img_id` = "'.$gal_action_id.'";';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка удаления картинки из галереи', '', __LINE__, __FILE__, $sql);
		};
};


$sql = "SELECT * FROM `" . 
TABLE_GALLERY_CAT . '` WHERE `cat_id` = "'.$cat_id.'"';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База галерей отсутствует', '', __LINE__, __FILE__, $sql);
	};

$gallery_cat_data = array();
$gallery_cat_data = $db->sql_fetchrow($result);

if (!$gallery_cat_data["cat_id"]) {
	message_die(GENERAL_ERROR, 'Запрошенная галерея не найдена ' . $add, '', __LINE__, __FILE__, $sql);
};

$cat_name = $gallery_cat_data["cat_name"];
$cat_patch = $gallery_cat_data["cat_path"];
$cat_desc = $gallery_cat_data["cat_desc"];

$template->assign_block_vars('swich_gallery', array(
	'CAT' => '<a href="/ru/gallery/'.$cat_patch.'-'.$gallery_cat_data["cat_id"].'/">'.$cat_name.'</a><br><small>'.$cat_desc.'</small>'
));


//
// превью галереи
//


$sql = "SELECT * FROM `" . 
TABLE_GALLERY_IMG . '` WHERE `cat_id` = "'.$cat_id.'"';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База галерей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$gallery_img_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$gallery_img_data[] = $row;
	};

$template->assign_vars(array(
	'PAGE_ID' => $gallery_cat_data["cat_id"],
	'IMG_TOTAL' => (count($gallery_img_data)+1)
));


$template->assign_block_vars('swich_gallery_script', array());

if ($_GET["imageprop"]) {
	if ($edit_content_type == 'photo') {
		$template->assign_block_vars('swich_gallery_edit_list', array());
		$i = 0;
		while ($gallery_img_data[$i]["img_id"]) {
			if (!$gallery_img_data[$i]["video_path"]) {
				$template->assign_block_vars('swich_gallery_edit_list.gallery_img', array(
					'IMG_ID' => $gallery_img_data[$i]["img_id"],
					'IMG_PATH' => $gallery_path.$gallery_img_data[$i]["cat_id"]."/".$gallery_img_data[$i]["img_path"],
					'IMG_NAME' => $gallery_img_data[$i]["img_name"],
					'SMALL_IMG_PATH' => $gallery_path.$gallery_img_data[$i]["cat_id"]."/sm/".$gallery_img_data[$i]["img_path"],
					'IMG_DESC' => $gallery_img_data[$i]["img_desc"]
				));
			};
			$i++;
		};
	}
	elseif ($edit_content_type == 'video') {
		$template->assign_block_vars('swich_gallery_video_edit_list', array());
		$i = 0;
		while ($gallery_img_data[$i]["img_id"]) {
			if ($gallery_img_data[$i]["video_path"]) {
//				$img_big_size = explode(";", $gallery_img_data[$i]["img_size"]);
				$template->assign_block_vars('swich_gallery_video_edit_list.gallery_video', array(
					'IMG_ID' => $gallery_img_data[$i]["img_id"],
					'IMG_PATH' => $gallery_path.$gallery_img_data[$i]["cat_id"]."/".$gallery_img_data[$i]["img_path"],
					'IMG_NAME' => $gallery_img_data[$i]["img_name"],
					'VIDEO_PATH' => $gallery_img_data[$i]["video_path"],
					'VIDEO_ID' =>  $gallery_img_data[$i]["video_id"],
					'VIDEO_THUMB' => $gallery_img_data[$i]["video_thumb"],
//					'VIDEO_WIDTH' => $img_big_size[0],
//					'VIDEO_HEIGHT' => $img_big_size[1],
//					'SMALL_IMG_PATH' => "",
//					'SMALL_IMG_PATH' => $gallery_video_path.$gallery_img_data[$i]["cat_id"]."/sm/".$gallery_img_data[$i]["img_path"],
					'IMG_DESC' => $gallery_img_data[$i]["img_desc"]
				));
			};
			$i++;
		};
	};
}
Else {
	if ($edit_content_type == 'photo') {
		$template->assign_block_vars('swich_gallery_preview', array());
		$template->assign_block_vars('swich_gallery.swich_gallery_upload', array());
		
		$i = 0;
		while ($gallery_img_data[$i]["img_id"]) {
			if (!$gallery_img_data[$i]["video_path"]) {
				$template->assign_block_vars('swich_gallery_preview.gallery_preview_list', array(
					'IMG_PATH' => $gallery_path.$gallery_img_data[$i]["cat_id"]."/".$gallery_img_data[$i]["img_path"],
					'IMG_NAME' => $gallery_img_data[$i]["img_name"],
					'SMALL_IMG_PATH' => $gallery_path.$gallery_img_data[$i]["cat_id"]."/sm/".$gallery_img_data[$i]["img_path"],
					'IMG_DESC' => $gallery_img_data[$i]["img_desc"]
				));
			};
			$i++;
		};
	}
	ElseIf ($edit_content_type == 'video') {
		$template->assign_block_vars('switch_video_gallery', array());
		$template->assign_block_vars('swich_gallery.swich_gallery_video_upload', array());
		
		$i = 0;
		while ($gallery_img_data[$i]["img_id"]) {
				$img_big_size = explode(";", $gallery_img_data[$i]["img_size"]);
			if ($gallery_img_data[$i]["video_path"]) {
				$template->assign_block_vars('switch_video_gallery.gallery_video_list', array(
					'VIDEO_PATH' => $gallery_img_data[$i]["video_path"],
					'VIDEO_NAME' => $gallery_img_data[$i]["img_name"],
					'VIDEO_THUMB' => $gallery_img_data[$i]["video_thumb"],
					'VIDEO_ID' => $gallery_img_data[$i]["video_id"],
//					'VIDEO_WIDTH' => $img_big_size[0],
//					'VIDEO_HEIGHT' => $img_big_size[1],
//					'SMALL_IMG_PATH' => $gallery_img_data[$i]["img_path"],
					'VIDEO_DESC' => $gallery_img_data[$i]["img_desc"]
				));
			};
			$i++;
		};
	};
};


$template->assign_block_vars('swich_gallery_editgal', array());


/*



if (@$_POST['add_cat']) {			// Добавление новой галереи

	// убираем вражеские символы из пути
	$cat_path = str_remove_enemy_char($_POST['cat_path']);

	// считаем количество галерей в разделе - новой в сортировку добавим +1
	$sql = 'SELECT count(`cat_pid`) as `galley_cnt` FROM `'.TABLE_GALLERY_CAT.'` where `cat_pid` = "'.$_POST['cat_pid'].'"' ;
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка выяснения кол-ва галерей в разделе', '', __LINE__, __FILE__, $sql);
	};
	$cat_pid_data = $db->sql_fetchrow($result);
	$cat_sort = $cat_pid_data["galley_cnt"]+1;

	// ижем максимальный id галереи
	$sql = 'SELECT max(`cat_id`) as `cat_id_max` FROM `'.TABLE_GALLERY_CAT.'`;' ;
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка выяснения кол-ва галерей в разделе', '', __LINE__, __FILE__, $sql);
	};
	$cat_id_max_data = $db->sql_fetchrow($result);
	$cat_id = $cat_id_max_data["cat_id_max"]+1;

	// создаем директорию, проверив нет ли уже такой, если есть - добавляем цифру к концу названия каталога "_х"
	$create_dir = $DRoot . "/gallery/". $cat_path ;
	if (file_exists($create_dir . "-" . $cat_id."/")) {
		$i = 2;
		while (file_exists($create_dir. "_". $i . "-" . $cat_id. "/")) {
			$i++;
		};
		$create_dir = $create_dir . "_" . $i . "-" . $cat_id."/";
		$cat_path .= "_" . $i;
		}
	Else {
		$create_dir = $create_dir . "-" . $cat_id."/";
	};
	mkdir($create_dir);

	// обезопашиваем переменные
	$cat_pid = substr($_POST['cat_pid'],0,11);
	$cat_img = substr($_POST['cat_img'],0,255);
	$cat_name = substr($_POST['cat_name'],0,255);
	$cat_desc = substr($_POST['cat_desc'],0,255);
	
//  `cat_img` , '".$cat_img."', 		обложка галереи. теоретически можно изначально ее установвливать
	$sql = "INSERT INTO `".TABLE_GALLERY_CAT."` (`cat_id`, `cat_pid` , `cat_sort` , `cat_name` , `cat_desc` , `cat_path` )
VALUES ('".$cat_id."', '".$cat_pid."', '".$cat_sort."', '".$cat_name."', '".$cat_desc."', '".$cat_path."');";

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка добавления галерии', '', __LINE__, __FILE__, $sql);
		}
	Else {
		$template->assign_block_vars('swich_save', array());
	};


}
Elseif (@$_GET['cat_delete']) {
	$cat_delete = substr($_GET['cat_delete'], 0, 11);
	$sql = 'UPDATE `'.TABLE_GALLERY_CAT.'` SET `cat_type` = "0" where `cat_id` = "'.$cat_delete.'"' ;
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка удаления галереи', '', __LINE__, __FILE__, $sql);
	};
}
Elseif (@$_POST['cat_save']) {

	$sql = "UPDATE `".TABLE_GALLERY_CAT."` SET 
`cat_name` = '".$_POST['cat_name']."',
`cat_desc` = '".$_POST['cat_desc']."',
`cat_type` = '".$_POST['cat_type']."' WHERE `cat_id` ='".$_POST['cat_id']."';";

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка сохранения галереи', '', __LINE__, __FILE__, $sql);
		}
	Else {
		$template->assign_block_vars('swich_save', array());
	};

};
*/




/*
IF ($_GET['add']=='cat') {

	$i=0;
	while ($gallery_cat_edit_data[$i]["cat_id"]) {
		if ($gallery_cat_edit_data[$i]["cat_id"]==$_GET["cat_pid"]) { 
			$pcat_name = $gallery_cat_edit_data[$i]["cat_name"]; 
		};
		$i++;
	};
	
	$template->assign_block_vars('swich_gallery_addcat', array(
		'CAT_PID' => $_GET["cat_pid"],
		'CAT_PID_NAME' => $pcat_name
	));
}
ElseIF ($_GET['cat_edit']) {
	$i=0;
	while ($gallery_cat_edit_data[$i]["cat_id"]) {
		if ($gallery_cat_edit_data[$i]["cat_id"]==$_GET['cat_edit']) {
			$cat_type_0 = ($gallery_cat_edit_data[$i]["cat_type"]==0) ? " checked" : "";
			$cat_type_1 = ($gallery_cat_edit_data[$i]["cat_type"]==1) ? " checked" : "";;
			$cat_type_50 = ($gallery_cat_edit_data[$i]["cat_type"]==50) ? " checked" : "";;
	
			$template->assign_block_vars('swich_gallery_editcat', array(
				'CAT_TYPE_0' => $cat_type_0,
				'CAT_TYPE_1' => $cat_type_1,
				'CAT_TYPE_50' => $cat_type_50,
				'CAT_ID' => $gallery_cat_edit_data[$i]["cat_id"],
				'CAT_DESC' => $gallery_cat_edit_data[$i]["cat_desc"],
				'CAT_NAME' => $gallery_cat_edit_data[$i]["cat_name"],
				'CAT_PATH' => "/gallery/" . $gallery_cat_edit_data[$i]["cat_path"] . "-" . $gallery_cat_edit_data[$i]["cat_id"] . "/"
			));
		};
		$i++;
	};
//swich_gallery_editcat
}
ElseIF ($_GET['edit']=='gallery') {

	if ($_GET['cat_type']=='del') {
		$cat_type = "del";
		}
	ElseIf ($_GET['cat_type']=='hidden') {
		$cat_type = "hidden";
		}
	Else {
		$cat_type = "all";
	};

	$template->assign_block_vars('gallery_catlist', array());
	$i=0;
	while ($gallery_cat_edit_data[$i]["cat_id"]) {
		if ($cat_type=="del") {			// показываем только удаленные статьи
			if ($gallery_cat_edit_data[$i]["cat_type"] == 0) {
				$cat_img = ($gallery_cat_edit_data[$i]["cat_img"]) ? $gallery_cat_edit_data[$i]["cat_img"] : '/admin/pic/question_b.gif';
		
				$template->assign_block_vars('gallery_catlist.gallery_catlist_row', array(
					'CAT_ID' => $gallery_cat_edit_data[$i]["cat_id"],
					'CAT_PID' => $gallery_cat_edit_data[$i]["cat_pid"],
					'CAT_IMG' => $cat_img,
					'CAT_NAME' => $gallery_cat_edit_data[$i]["cat_name"],
					'CAT_PATH' => '/gallery/' . $gallery_cat_edit_data[$i]["cat_path"] .'-'.$gallery_cat_edit_data[$i]["cat_id"].'/',
					'CAT_DESC' => $gallery_cat_edit_data[$i]["cat_desc"]
				));
			};
		}
		ElseIf ($cat_type=="hidden") {			// показываем только скрытые галереи
			if ($gallery_cat_edit_data[$i]["cat_type"] == 50) {
				$cat_img = ($gallery_cat_edit_data[$i]["cat_img"]) ? $gallery_cat_edit_data[$i]["cat_img"] : '/admin/pic/question_b.gif';
		
				$template->assign_block_vars('gallery_catlist.gallery_catlist_row', array(
					'CAT_ID' => $gallery_cat_edit_data[$i]["cat_id"],
					'CAT_PID' => $gallery_cat_edit_data[$i]["cat_pid"],
					'CAT_IMG' => $cat_img,
					'CAT_NAME' => $gallery_cat_edit_data[$i]["cat_name"],
					'CAT_PATH' => '/gallery/' . $gallery_cat_edit_data[$i]["cat_path"] .'-'.$gallery_cat_edit_data[$i]["cat_id"].'/',
					'CAT_DESC' => $gallery_cat_edit_data[$i]["cat_desc"],
					'CAT_ADD' => '<a href="/admin/index.php?edit=gallery&add=cat&cat_pid='.$gallery_cat_edit_data[$i]["cat_id"].'" title="Добавить вложенную галерею">Доб...</a>',
					'CAT_DEL' => '<a href="/admin/index.php?edit=gallery&cat_delete='.$gallery_cat_edit_data[$i]["cat_id"].'"><img src="/admin/pic/delete.png" alt="Удалить" width="16" height="16" border="0"></a><br>'
				));
			};
		}
		Else {
			if ($gallery_cat_edit_data[$i]["cat_type"] == 1) {
				$cat_img = ($gallery_cat_edit_data[$i]["cat_img"]) ? $gallery_cat_edit_data[$i]["cat_img"] : '/admin/pic/question_b.gif';
		
				$template->assign_block_vars('gallery_catlist.gallery_catlist_row', array(
					'CAT_ID' => $gallery_cat_edit_data[$i]["cat_id"],
					'CAT_PID' => $gallery_cat_edit_data[$i]["cat_pid"],
					'CAT_IMG' => $cat_img,
					'CAT_NAME' => $gallery_cat_edit_data[$i]["cat_name"],
					'CAT_PATH' => '/gallery/' . $gallery_cat_edit_data[$i]["cat_path"] .'-'.$gallery_cat_edit_data[$i]["cat_id"].'/',
					'CAT_DESC' => $gallery_cat_edit_data[$i]["cat_desc"],
					'CAT_ADD' => '<a href="/admin/index.php?edit=gallery&add=cat&cat_pid='.$gallery_cat_edit_data[$i]["cat_id"].'" title="Добавить вложенную галерею">Доб...</a>',
					'CAT_DEL' => '<a href="/admin/index.php?edit=gallery&cat_delete='.$gallery_cat_edit_data[$i]["cat_id"].'"><img src="/admin/pic/delete.png" alt="Удалить" width="16" height="16" border="0"></a><br>'
				));
			};
		};
			
		$i++;
	};


};
*/

$template->pparse('gallery_prop_body');

?>