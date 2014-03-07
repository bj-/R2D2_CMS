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

$show_article = TRUE; // флаг задающий показ текста статьи

//
// собираем страницу со статьей
//
$get_article = $_GET['id'];
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами
$article_sgal = TRUE;

// ищем ID раздела странички если не указан файл. если путь в каком-то месте неверный - возвращаем ошибку
function get_paragraf_id($paragraf_name, $pid) {
	global $paragraf_id, $current_paragraf_id_list;
//	echo "sdfsdfsdf";
//	echo strpos($get_article, "/");
	if (substr($paragraf_name, (strlen($paragraf_name)-1))=="/" ) {
		$current_paragraf_name = substr($paragraf_name, 0, (strpos($paragraf_name, "/")));
		$current_paragraf_id = $paragraf_id[$pid][$current_paragraf_name]['menu_id'];
		$current_paragraf_id_list[] = $current_paragraf_id;
		$sub_paragraf_name = substr($paragraf_name, (strpos($paragraf_name, "/"))+1);
		if (strlen($sub_paragraf_name)) {
 			$ret = get_paragraf_id($sub_paragraf_name, $current_paragraf_id);
			}
		else {
			$ret = ($current_paragraf_id) ? $current_paragraf_id : -1;	// если путь в каком-то месте неверный или ведет в никуда - возвращаем ошибку (-1) и далее ее обработаем
		}; 
	};
 	return $ret;
};

// парсим путь
$article_name = substr($get_article, strrpos($get_article, "/")+1);
if ($article_name) {
	$current_article_id = substr($article_name, strrpos($article_name, "-")+1);
	$current_article_id = substr($current_article_id, 0, strpos($current_article_id, "."));
	$current_article_id = substr($current_article_id,0, 11); // обрезаем id статьи до 11 символов для борьбы и инжекшенами
	$paragraf_url = substr($get_article, 0, strrpos($get_article, "/")+1);
	get_paragraf_id($paragraf_url, 0);
//	$in_paragraf_root = TRUE; //$get_article;
	}
else {
	$current_paragraf_id = get_paragraf_id($get_article, 0);
	$current_article_id = false; //$get_article;
};


// Берем статью из БД
if (@$current_paragraf_id){
	$article_where = ' `primary_article` = "1" and `paragraf_id` = "'.$current_paragraf_id.'"';
};
if (@$current_article_id) {
	$article_where = ' `article_id` = "'.$current_article_id.'"';;
};

$sql = 'SELECT * '.
		'FROM `' . TABLE_ARTICLE . '` '.
		'WHERE '.$article_where.' and `article_lang` = "'.$user_lang.'"';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
	};

$article_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$article_data[] = $row;
	};

if (!$article_data[0]["article_id"]) {
	$no_article = TRUE;
//	message_die(GENERAL_ERROR, 'Запрошенная статья не найдена ' . $add, '', __LINE__, __FILE__, $sql);
}
else {
	$current_paragraf_id = $article_data[0]["paragraf_id"];
};

// выбираем данные о форме, если она есть и если указан емейл
if ($article_data[0]["form_id"] and $article_data[0]["form_email"]) {
	$sql = 'SELECT * '.
			'FROM `' . TABLE_FORMS_FIELDS . '` '.
			'WHERE `form_id` = "'.$article_data[0]["form_id"].'" '.
			'ORDER BY `field_sort` ASC;';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
	};

	$form_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$form_data[] = $row;
	};
};

//Формирование пути раздела данной статьи
//print_r($config);
$i = 0;
$paragraf_title_patch = "";
$url = "";
while (@$current_paragraf_id_list[$i]) {
	$url = $url_lang."/".get_full_url($current_paragraf_id_list[$i]);
	$paragraf_title_patch .= '<a class="paragraf_title_link" href="/'.$url.'/">'. $topmenu_data[$current_paragraf_id_list[$i]]['menu_name'] .'</a>';
	if (@$current_paragraf_id_list[$i+1]) { // добавляем >> между ссылками
		$paragraf_title_patch .= $config["paragraf_title_sep"];
	};
	$i++;
};

if ($userdata['user_level'] >0) {
	$edit = '<a href="/admin/article_edit.php?add=1" title="Создать новую статью"><img src="/pic/ico/page_add.gif" alt="Создать новую статью" width="16" height="16" border="0"></a> ';
	$edit .= ($article_data[0]["article_id"] and $article_data[0]["article_edit_type"]<9) ? '<a href="/admin/article_edit.php?id='.$article_data[0]["article_id"].'&edit=1" title="'.$lang['Edit_post'].'"><img src="/pic/ico/edit.gif" alt="'.$lang['Edit_post'].'" width="16" height="16" border="0"></a> ' : "";
	$edit .= ($article_data[0]["article_id"]) ? '<a href="/admin/article_prop.php?&id='.$article_data[0]["article_id"].'" title="Настройки статьи"><img src="/pic/ico/document-properties.png" alt="Настройки статьи" width="16" height="16" border="0"></a> ' : "";
};

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = (@$no_article) ? "Статья не найдена" : $article_data[0]["article_title"];
$page_classification = $article_data[0]["article_classification"];
$page_desc = $article_data[0]["article_desc"];
$page_keywords = $article_data[0]["article_keywords"];
$page_content_direction = "";

$page_paragraf_id = $article_data[0]["paragraf_id"];
$page_paragraf_name_text = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_name'];
$page_paragraf_name = $paragraf_title_patch; //$topmenu_data[$article_data[0]["paragraf_id"]]['menu_name'];
$page_paragraf_path = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_path'];
$page_paragraf_title_patch = $paragraf_title_patch;

$submit_path = "/article.php?article=".$article_data[0]["article_id"];
$page_id = $article_data[0]["article_id"];
$page_unix_date = $article_data[0]["article_date"];
$page_date = create_date($board_config['article_dateformat'], $article_data[0]["article_date"], $board_config['board_timezone']);
$check_primary_article = ($article_data[0]["primary_article"]) ? " checked" : "";

$page_form_email = $article_data[0]["form_email"];
$page_form_subj = $article_data[0]["form_subject"];

$page_path = $article_data[0]["article_name"];
$page_text = (@$no_article) ? "<p><h2>Запрошенная статья не найдена</h2></p><p>Жалоба администратору сайта уже написана автоматически, спасибо за помощь.</p>" : $article_data[0]["article_text"];

$smGal_status = $article_data[0]["article_sgal_on"];


include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'article_body.tpl')
);
$template->assign_block_vars('switch_left_menu', array());

/*==============================================
   отправка сообщения из формы обратной связи
 ==============================================*/
if ($article_data[0]["form_email"]) {
	if ($_POST['send_mail']) {
		include $DRoot . "/includes/msend.php";
		$page_text = ($show_article) ? $page_text : "";
		}
	else {
		$show_form = TRUE;
//		$template->assign_block_vars('switch_form', array());
	};
};

$template->assign_vars(array(
	'SAVED' => @$saved,
	'PATCH_DESCRIPTION' => $paragrad_desc,
	'ARTICLE' => $page_text,
	'ARTICLE_ID' => $page_id,
	'EDIT' => @$edit
	));

// Вставляем форму в страницу, если форма имеется. и если нет флага об отправленном соощении.
if (count($form_data) and @$show_form) {
	$template->assign_block_vars('show_form', array());
	$i = 0;
	while ($form_data[$i]["field_id"]) {
		$field_required = ($form_data[$i]["field_require"]) ? ' <span style="color:red">*</span>' : "";
		$field_size = ($form_data[$i]["field_size"]) ? $form_data[$i]["field_size"] : "";
		$field_maxlen = ($form_data[$i]["field_maxlen"]) ? $form_data[$i]["field_maxlen"] : "";
		if (strpos($field_size,";")) {
			$tmp_fldsize = explode(";", $field_size);
			$field_size_cols  = $tmp_fldsize[0];
			$field_size_rows = $tmp_fldsize[1];
		};
		
		switch ($form_data[$i]["field_type"]) {
			case 1:
				$template->assign_block_vars('show_form.form_field_varchar', array(
					'NAME' => $form_data[$i]["field_name"],
					'FIELD_NAME' => 'field_' . $form_data[$i]["field_id"],
					'TYPE' => 'text',
					'VALUE' => $form_data[$i]["field_val"],
					'SIZE' => $field_size,
					'MAXLEN' => $field_maxlen,
					'REQUIRED' => $field_required,
					'CLASS' => $filed_class,
					'STYLE' => $filed_style,
				));
				break;
			case 2:
				$template->assign_block_vars('show_form.form_field_varchar', array(
					'NAME' => $form_data[$i]["field_name"],
					'FIELD_NAME' => 'field_' . $form_data[$i]["field_id"],
					'TYPE' => 'text',
					'VALUE' => $form_data[$i]["field_val"],
					'SIZE' => $field_size,
					'MAXLEN' => $field_maxlen,
					'REQUIRED' => $field_required,
					'CLASS' => $filed_class,
					'STYLE' => $filed_style,
				));
				break;
			case 3:
				$template->assign_block_vars('show_form.form_field_varchar', array(
					'NAME' => $form_data[$i]["field_name"],
					'FIELD_NAME' => 'field_' . $form_data[$i]["field_id"],
					'TYPE' => 'text',
					'VALUE' => $form_data[$i]["field_val"],
					'SIZE' => $field_size,
					'MAXLEN' => $field_maxlen,
					'REQUIRED' => $field_required,
					'CLASS' => $filed_class,
					'STYLE' => $filed_style,
				));
				break;
			case 4: //textarea
				$template->assign_block_vars('show_form.form_field_textarea', array(
					'NAME' => $form_data[$i]["field_name"],
					'FIELD_NAME' => 'field_' . $form_data[$i]["field_id"],
					'TYPE' => 'text',
					'VALUE' => $form_data[$i]["field_val"],
					'ROWS' => $field_size_rows,
					'COLS' => $field_size_cols,
					'REQUIRED' => $field_required,
					'CLASS' => $filed_class,
					'STYLE' => $filed_style,
				));
				break;
		};
		$i++;
	};
};

// Показываем подменю на странице, если таковое указано в пропертях страницы.
if (TRUE) {
//	echo "<pre>";
//	print_r($topmenu_data);
	
	$template->assign_block_vars('submenu', array());
	$i = 0;
	while ($topmenu_pids[$current_paragraf_id][$i]) {
		$c_id = $topmenu_pids[$current_paragraf_id][$i];

		$menu_path = ($topmenu_data[$c_id]["link_type"]) ? $topmenu_data[$c_id]["link_type"] : '/'.$url_lang.'/' . get_full_url($c_id) . '/';
		
		$template->assign_block_vars('submenu.submenu_list', array(
			'MENU_NAME' => $topmenu_data[$c_id]["menu_name"],
			'MENU_PATH' => $menu_path,
			'MENU_CLASS' => $topmenu_data[$c_id]["menu_class"]
		));
		$i++;
	};
};


// миниГалерея
// переключалка 
if ($article_data[0]["article_sgal_on"]) {
	$sql_sgal = 'SELECT * FROM '.TABLE_ARTILE_GALLERY.' WHERE `article_id` = "' .$article_data[0]["article_id"].'" and `source_id` = "1" AND `img_path` IS NOT NULL;';
	$sgal_source_id = 1;
	$sgal_source_path = "article";
}
elseif($article_data[0]["article_svgal_on"]) {
	$sql_sgal = 'SELECT * FROM '.TABLE_ARTILE_GALLERY.' WHERE `article_id` = "' .$article_data[0]["article_id"].'" and `source_id` = "1" AND `video_thumb` IS NOT NULL;';
	$sgal_source_id = 1;
	$sgal_source_path = "article";
}
elseif (@$event_sgal) {
	$sql_sgal = 'SELECT * FROM '.TABLE_ARTILE_GALLERY.' WHERE `article_id` = "' .$article_data[0]["article_id"].'" and `source_id` = "2";';
	$sgal_source_id = 2;
	$sgal_source_path = "events";
	};

if ($article_data[0]["article_sgal_on"]) {
	$template->assign_block_vars('switch_smgallery_script', array());	
	$template->assign_block_vars('switch_smgallery', array());
	

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
		$template->assign_block_vars('switch_smgallery.smgallery_list', array(
			'IMG_ID' => $smGal_data[$i]['aimg_id'],
			'IMG_NAME' => $smGal_data[$i]['img_name'],
			'IMG_DESC' => $smGal_data[$i]['img_desc'],
			'IMG_PATH' => $miniGal_path . $smGal_data[$i]['img_path'],
			'SMALL_IMG_PATH' => $miniGal_path . "sm/" . $smGal_data[$i]['img_path'],
		));
	$i++;
	};
};

if ($article_data[0]["article_svgal_on"]) {
	$template->assign_block_vars('switch_smgallery_script', array());	
	$template->assign_block_vars('switch_smvgallery', array());
};



//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>