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
include("../../includes/config.php");
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

$show_article = TRUE; // флаг задающий показ текста статьи

//
// собираем страницу со статьей
//
$get_id = intval(substr($_GET['id'],0,11));
$rand = (intval(substr($_GET['rand'],0,11))) ? ' GROUP BY RAND() ' : ''; //рандомный порядок
$order = (substr($_GET['order'],0,10) == 'asc') ? ' ORDER BY `img_sort` ASC ' : ' ORDER BY `img_sort` DESC ';
$page = intval(substr($_GET['page'],0,11));
$img_cnt = intval(substr($_GET['img_cnt'],0,11));
$thumb_w = intval(substr($_GET['thumb_w'],0,5));
$thumb_h = intval(substr($_GET['thumb_h'],0,5));
//
// Start output of page
//
define('SHOW_ONLINE', true);


$template->set_filenames(array(
	'body' => 'dynamic/gallery/dyn_gal_preview.tpl'
));

$sql = 'SELECT * '.
		'FROM `' . TABLE_GALLERY_CAT . '` '.
		'WHERE `cat_pid`="'.$get_id.'" AND `cat_type` ="1";';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
	};

$cat_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$cat_data[] = $row;
	};

if (!$cat_data[0]["cat_id"]) {
	$no_article = TRUE;
};



if (in_array($_GET["type"], array("singlecat", "sm_img", "sm_video"))) {
	// одна категория, Х - количесво фото, переход +- страница

	/*
	 http://corruption/dynamic/gallery/dyn_gal_galpreview.php
			?id=1
			&type=singlecat
			&page=1
			&order=asc
			&img_cnt=4
			&thumb_w					// пережимаем картинки под заданные размеры с сохранением пропорций
			&thumb_h

//			&custom=1      		// кастомный вариант - отдаем только картинки без форматирования для встривания на страницу.
			&imgvisible=XX			// XX-кол-во картинок на странице.
			&imgscroll=XX			// XX-кол-во картинок которое скроллится за раз.
	*/
	//	echo "sdfsdf";
	$limit = 'LIMIT ' . ($page*4) . ', ' . $img_cnt . ';';

	if ($_GET["type"] == "singlecat") 
	{
		$sql = 'SELECT * '.
				'FROM `' . TABLE_GALLERY_IMG . '` '.
				'WHERE `cat_id`="'.$get_id.'" AND img_path IS NOT NULL '.
				$rand.
				$order.
				$limit;
	}
	elseif ($_GET["type"] == "sm_img")
	{
		
	}
	elseif ($_GET["type"] == "sm_video")
	{
		$sql = 'SELECT * '.
				'FROM `' . TABLE_ARTILE_GALLERY . '` '.
				'WHERE `article_id`="'.$get_id.'" AND `video_thumb` IS NOT NULL '.
				$rand.
				$order.
				$limit;
		
	};

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
	};

	$gallery_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$gallery_data[] = $row;
	};

	if ($gallery_data[0]["img_id"] or $gallery_data[0]["aimg_id"]) {
		if ($_GET["type"] == "singlecat") {
			$block_name = 'cat_preview';
		}
		elseif ($_GET["type"] == "sm_img") {
			$block_name = 'cat_preview_img';
		}
		elseif ($_GET["type"] == "sm_video") {
			$block_name = 'cat_preview_video';
		};
				$template->assign_block_vars($block_name, array());
	};

	$i =0;
	while ($gallery_data[$i]["img_id"] or $gallery_data[$i]["aimg_id"]) {
		$img_size = explode(";", $gallery_data[$i]["simg_size"]);

		if (@$thumb_w and @$thumb_h) {
			// пропорциb нового изображения
			$ratio = $thumb_w/$thumb_h; 
			// получим коэффициент сжатия исходного изображения 
			$src_ratio=$img_size[0]/$img_size[1]; 
			// Здесь вычисляем размеры уменьшенной копии, чтобы при масштабировании сохранились 
			// пропорции исходного изображения 
			if ($ratio<$src_ratio) { 
				$img_size[1] = $thumb_w/$src_ratio; 
				$img_size[0] = $thumb_w;
			} 
			else { 
				$img_size[0] = $thumb_h*$src_ratio; 
				$img_size[1] = $thumb_h;
			} 
		};
		
		
		$template->assign_block_vars($block_name.'.img_list', array(
			'IMG_ID' => $gallery_data[$i]["cat_id"],
			'IMG_NAME' => $gallery_data[$i]["img_name"],
			'IMG_DESC' => $gallery_data[$i]["img_desc"],
			'IMG_CAT_ID' => $gallery_data[$i]["cat_id"],
			'IMG_PATH' => $gallery_data[$i]["img_path"],
			'IMG_BIG_SIZE' => $gallery_data[$i]["img_size"],
			'IMG_SM_SIZE' => $gallery_data[$i]["simg_size"],
			'IMG_SM_WIDTH' => $img_size[0],
			'IMG_SM_HEIGHT' => $img_size[1],
			'VIDEO_THUMB' => $gallery_data[$i]["video_thumb"],
			'VIDEO_ID' => $gallery_data[$i]["video_id"],
		));
		$i++;
	};

	
}
else {
	// Первоначальный вариант
$i=0;
while ($cat_data[$i]["cat_id"]) {
	$sql = 'SELECT `img_id`, `img_name`, `img_desc`, `img_path`, `img_size`, `simg_size` '.
			'FROM `' . TABLE_GALLERY_IMG . '` '.
			'WHERE `cat_id`="'.$cat_data[$i]["cat_id"].'" '.
			'GROUP BY RAND() '.
			'LIMIT 0, 3;';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
		};

	$gallery_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$gallery_data[] = $row;
	};

	if ($gallery_data[0]["img_id"]) {
		// что-то показываем только если галерея не пустая
		$template->assign_block_vars('cat_list', array(
			'CAT_ID' => $cat_data[$i]["cat_id"],
			'CAT_IMG' => $cat_data[$i]["cat_img"],
			'CAT_NAME' => $cat_data[$i]["cat_name"],
			'CAT_DESC' => $cat_data[$i]["cat_desc"],
			'CAT_PATH' => $cat_data[$i]["cat_path"],
			'CAT_TEXT' => $cat_data[$i]["gallery_text"],
		));

	
		$i2=0;
		while ($gallery_data[$i2]["img_id"]) {
			$img_size = explode(";", $gallery_data[$i2]["simg_size"]);
			
			$template->assign_block_vars('cat_list.img_list', array(
				'IMG_ID' => $gallery_data[$i2]["cat_id"],
				'IMG_NAME' => $gallery_data[$i2]["img_name"],
				'IMG_DESC' => $gallery_data[$i2]["img_desc"],
				'IMG_CAT_ID' => $cat_data[$i]["cat_id"],
				'IMG_PATH' => $gallery_data[$i2]["img_path"],
				'IMG_BIG_SIZE' => $gallery_data[$i2]["img_size"],
				'IMG_SM_SIZE' => $gallery_data[$i2]["simg_size"],
				'IMG_SM_WIDTH' => $img_size[0],
				'IMG_SM_HEIGHT' => $img_size[1],
			));
			$i2++;
		};
	};

	$i++;
};

};
//
// Generate the page
//
$template->pparse('body');

?>