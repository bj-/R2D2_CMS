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


//$template->assign_block_vars('swich_gallery', array());


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
	$create_dir = $DRoot . "/img/gallery/". $cat_id ."/" ;
	if (!file_exists($create_dir)) {
		mkdir($create_dir);
	};

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

//
// выбираем все галерии из бд
//
$sql = "SELECT * FROM `" . 
TABLE_GALLERY_CAT . '` ORDER BY `cat_sort` ASC';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База галерей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$gallery_cat_edit_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$gallery_cat_edit_data[] = $row;
	};

if (!$gallery_cat_edit_data[0]["cat_id"]) {
	message_die(GENERAL_ERROR, 'Запрошенная галерея не найдена ' . $add, '', __LINE__, __FILE__, $sql);
};



// Генерируем страницу

$template->set_filenames(array(
	'gallery_prop_body' => 'admin/gallery_edit_body.tpl')
);





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
			
			$cat_img = ($gallery_cat_edit_data[$i]["cat_img"]) ? '/img/gallery/'.$gallery_cat_edit_data[$i]["cat_img"] : "/pic/ico/question_b.gif";
			
			$template->assign_block_vars('swich_gallery_editcat', array(
				'CAT_TYPE_0' => $cat_type_0,
				'CAT_TYPE_1' => $cat_type_1,
				'CAT_TYPE_50' => $cat_type_50,
				'CAT_ID' => $gallery_cat_edit_data[$i]["cat_id"],
				'CAT_PHOTO' => '<img src="' . $cat_img .'" border="0" />',
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
				$cat_img = ($gallery_cat_edit_data[$i]["cat_img"]) ? "/img/gallery/".$gallery_cat_edit_data[$i]["cat_img"] : '/pic/ico/question_b.gif';
		
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
				$cat_img = ($gallery_cat_edit_data[$i]["cat_img"]) ? "/img/gallery/".$gallery_cat_edit_data[$i]["cat_img"] : '/pic/ico/question_b.gif';
		
				$template->assign_block_vars('gallery_catlist.gallery_catlist_row', array(
					'CAT_ID' => $gallery_cat_edit_data[$i]["cat_id"],
					'CAT_PID' => $gallery_cat_edit_data[$i]["cat_pid"],
					'CAT_IMG' => $cat_img,
					'CAT_NAME' => $gallery_cat_edit_data[$i]["cat_name"],
					'CAT_PATH' => '/gallery/' . $gallery_cat_edit_data[$i]["cat_path"] .'-'.$gallery_cat_edit_data[$i]["cat_id"].'/',
					'CAT_DESC' => $gallery_cat_edit_data[$i]["cat_desc"],
					'CAT_ADD' => '<a href="/admin/index.php?edit=gallery&add=cat&cat_pid='.$gallery_cat_edit_data[$i]["cat_id"].'" title="Добавить вложенную галерею">Доб...</a>',
					'CAT_DEL' => '<a href="/admin/index.php?edit=gallery&cat_delete='.$gallery_cat_edit_data[$i]["cat_id"].'"><img src="/pic/ico/delete.gif" alt="Удалить" width="16" height="16" border="0"></a><br>'
				));
			};
		}
		Else {
			if ($gallery_cat_edit_data[$i]["cat_type"] == 1) {
				$cat_img = ($gallery_cat_edit_data[$i]["cat_img"]) ? "/img/gallery/".$gallery_cat_edit_data[$i]["cat_img"] : '/pic/ico/question_b.gif';
		
				$template->assign_block_vars('gallery_catlist.gallery_catlist_row', array(
					'CAT_ID' => $gallery_cat_edit_data[$i]["cat_id"],
					'CAT_PID' => $gallery_cat_edit_data[$i]["cat_pid"],
					'CAT_IMG' => $cat_img,
					'CAT_NAME' => $gallery_cat_edit_data[$i]["cat_name"],
					'CAT_PATH' => '/gallery/' . $gallery_cat_edit_data[$i]["cat_path"] .'-'.$gallery_cat_edit_data[$i]["cat_id"].'/',
					'CAT_DESC' => $gallery_cat_edit_data[$i]["cat_desc"],
					'CAT_ADD' => '<a href="/admin/index.php?edit=gallery&add=cat&cat_pid='.$gallery_cat_edit_data[$i]["cat_id"].'" title="Добавить вложенную галерею">Доб...</a>',
					'CAT_DEL' => '<a href="/admin/index.php?edit=gallery&cat_delete='.$gallery_cat_edit_data[$i]["cat_id"].'"><img src="/pic/ico/delete.gif" alt="Удалить" width="16" height="16" border="0"></a><br>'
				));
			};
		};
			
		$i++;
	};


};

$template->pparse('gallery_prop_body');

?>