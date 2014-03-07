<?php
/***************************************************************************
 *                                index.php
 *                              (article_prop)
 *                            -------------------
 *   begin                : Sep 20, 2010
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: article_prop.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
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
include($DRoot . '/includes/common.php');

// фейк-линки для дрювавера (ссылки на инклюденные файлы)
// include("../../includes/common.php");
// include ('../../admin/includes/articles.php');

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);
//
// End session management
//

// Проверяем права пользователя. если нет - выкидываем
if ($userdata['user_level'] < 1) {
	message_die(GENERAL_ERROR, 'Пользователь не имеет прав', '', __LINE__, __FILE__, $sql);
	exit;
};


$site_prop = TRUE;

if ($_GET["articlelist"]) {
	include ($DRoot . '/admin/includes/articles.php');
};

/*
// Входящие переменные
$get_article = substr($_GET['id'],0,11);

//метка для построителя хидера, меню и редактора галереи - что включать
$article_prop = TRUE;


// НАЧАЛО сохранения

// подрезаем и проверяем входные переменные на спецсимволы и пр казусы вроде инжекшенов
if ($_POST['save'] and ($userdata['user_level'] >0)) {
	$save_page_title = mysql_real_escape_string(substr($_POST['page_title'],0,240));
	$save_page_desc = mysql_real_escape_string(substr($_POST['page_desc'],0,240));
	$save_page_keywords = mysql_real_escape_string(substr($_POST['page_keywords'],0,240));
	$save_paragraf_id = intval(substr($_POST['paragraf_id'],0,11));
	$save_article_primary = intval(substr($_POST['article_primary'],0,3));
	$save_page_path = str_remove_enemy_char(substr($_POST['page_path'],0,240));
	$save_page_text = mysql_real_escape_string($_POST['page_text']);
	$save_form_email = mysql_real_escape_string(substr($_POST['form_email'],0,240));
	$save_form_subject = mysql_real_escape_string(substr($_POST['form_subject'],0,240));
	$current_article_id = intval(substr($_POST['article_id'],0,11));
	$save_form_id = intval(substr($_POST['form_id'],0,11));
	
	if($_POST['date_sel'] == "current_date") {
		$article_date = time();
		}
	else {
		// переводим дату в юникс формат
		$article_date = substr($_POST['page_date'], 0 , 10);
		$article_date_arr = explode(".", $article_date);
		$article_date = mktime(0, 0, 0, $article_date_arr[1], $article_date_arr[0], $article_date_arr[2]);
	};

};

// Сохраняем отредактированную статью
if ($_POST['save'] and $_POST['new'] and ($userdata['user_level'] >0)) { 

	// выбираем последний ID статьи
	$sql = "SELECT MAX(`article_id`) AS `max_article_id` FROM `" . TABLE_ARTICLE . "`";

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка выяснения максимального ID статьи', '', __LINE__, __FILE__, $sql);
		};
	$new_article_id_data = array();
	$new_article_id_data = $db->sql_fetchrow($result);
	$new_article_id = $new_article_id_data["max_article_id"]+1;
	$get_article = $new_article_id;
	
	$sql = 'INSERT INTO `' . TABLE_ARTICLE . '` '.
			'(`article_id`, `article_title` , `article_desc` , `article_classification` , `article_keywords` , `paragraf_id` , `primary_article` , '.
			'`article_name` , `article_text` , `article_date` , `article_status` , `article_vote_id` , `article_lang`, '.
			'`form_email`, `form_subject`, `form_id` '.
			') '.
			"VALUES ('".$new_article_id."', '".$save_page_title."', '".$save_page_desc."', '', '".$save_page_keywords."', '".$save_paragraf_id."', '".$save_article_primary.
			"', '".$save_page_path."', '".$save_page_text."', '".$news_date."', '1', '', '1', ".
			"'".$save_form_email."', '".$save_form_subject."', '".$save_form_id."'".
			" );";

	$current_paragraf_id = $save_paragraf_id;
	$current_article_id = FALSE;
	

	if ($db->sql_query($sql)) {
		$saved = '<strong>' . $lang['Saved'] . '</strong>';
	 	if ($_GET['adm']) {
			redirect("/admin/index.php?edit=articles");
			exit;
		};
	}
	else {
		message_die(GENERAL_ERROR, 'Ошибка сохранения статьи', '', __LINE__, __FILE__, $sql);
	};
}
elseif ($_POST['save'] and ($userdata['user_level'] >0)) {
	$sql = 
	"UPDATE `" . TABLE_ARTICLE . "` SET ".
	"`article_title` = '".$save_page_title."', ".
	"`article_desc` = '".$save_page_desc."', ".
	"`article_classification` =  '', ".
	"`article_keywords` = '".$save_page_keywords."', ".
	"`paragraf_id` = '".$save_paragraf_id."', ".
	"`primary_article` =  '".$save_article_primary."', ".
	"`article_name` = '".$save_page_path."', ".
	"`article_text` = '".$save_page_text."', ".
	"`article_date` =  '".$article_date."', ".
	"`article_status` =  '1', ".
	"`article_vote_id` =  '', ".
	"`article_lang` =  '1', ".
	"`form_id` = '".$save_form_id."'".
	", `form_email` =  '".$save_form_email."' ".
	", `form_subject` =  '".$save_form_subject."' ".
	"WHERE `article_id` = '" . substr($_POST['article_id'],0, 11) ."'";
//echo "<!--" . $sql . "-->";
	if ($db->sql_query($sql)) {
		$saved = '<strong>' . $lang['Saved'] . '</strong>';
	}
	else {
		message_die(GENERAL_ERROR, 'Ошибка сохранения статьи', '', __LINE__, __FILE__, $sql);
	};

};


// КОНЕЦ сохранения




define('SHOW_ONLINE', true);
//
// включение/выключение модулей до загрузки страницы.
//
if ($_GET['sgallery']) { // галерея
	include $DRoot . "/admin/includes/module_swich.php";
}


$sql = 'SELECT * '.
		'FROM `' . TABLE_ARTICLE . '` AS `a` '.
		'JOIN `' . TABLE_FORMS . '` AS `f` ON `f`.`form_id` = `a`.`form_id` '.
		'WHERE `article_id` = "'.$get_article.'"';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'Статья отсутствует', '', __LINE__, __FILE__, $sql);
	};
$article_data = array();
$article_data = $db->sql_fetchrow($result);

if (!$article_data["article_id"]) {
	$no_article = TRUE;
	message_die(GENERAL_ERROR, 'Запрошенная статья не найдена ' . $add, '', __LINE__, __FILE__, $sql);
};
*/

/*
// Выбираем ID формы если она есть у статьи
if($article_data["form_id"]) {
	$sql = 'SELECT * '.
		'FROM `' . TABLE_FORMS . '` '.
		'WHERE `form_id` = "'.$article_data["form_id"].'"';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Статья отсутствует', '', __LINE__, __FILE__, $sql);
		};
	$article_data = $db->sql_fetchrow($result);
};
*/

/*
// загоняем в переменные данные о статье
$page_title = $article_data["article_title"];
$page_classification = $article_data["article_classification"];
$page_desc = $article_data["article_desc"];
$page_keywords = $article_data["article_keywords"];
$page_content_direction = "";

$page_paragraf = $article_data["paragraf_id"];

//$submit_path = "/article.php?article=".$article_data[0]["article_id"];
$page_id = $article_data["article_id"];
$page_unix_date = $article_data["article_date"];
$page_date = create_date($board_config['article_dateformat'], $article_data["article_date"], $board_config['board_timezone']);
$primary_article = $article_data["primary_article"];

$page_form_email = $article_data["form_email"];
$page_form_subj = $article_data["form_subject"];
$page_form_onoff = ($article_data["form_email"]) ? '<font style="color:green">подключена</font>' : '<font style="color:red">отключена</font>';

$page_path = $article_data["article_name"];
$page_text = (@$no_article) ? "<p><h2>Запрошенная статья не найдена</h2></p><p>Жалоба администратору сайта уже написана автоматически, спасибо за помощь.</p>" : $article_data["article_text"];

$smGal_status = $article_data["article_sgal_on"];
$smgal_onoff = ($smGal_status) ? '<font style="color:green">подключена</font>' : '<font style="color:red">отключена</font>';


$article_prop = TRUE; //метка для построителя хидера какое меню включить

$article_form_name = ($article_data["form_name"]) ? $article_data["form_name"] : "Нет";

if ($primary_article) {
	$article_path = ($page_paragraf) ? "/".$url_lang."/".get_full_url($page_paragraf) . "/" : "http://" . $board_config["server_name"] . "/";
}
else {
	$article_path = "/".$url_lang."/".get_full_url($page_paragraf) . "/" . $page_path . "-" . $page_id . ".html";
}

*/

include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'admin/article_prop_body.tpl')
);


//
// какую страницу показывать
//
if ($_GET['sgallery']) { // галерея
	include $DRoot . "/admin/includes/sgallery.php";
}
else {
	include $DRoot . "/admin/article/article_prop_list.php";
};


















// обходной маневр для показывания дефолтной страницы после выполнении малозначительных операций
if ($show_prop) {
	include $DRoot . "/admin/includes/article_prop_list.php";
};

$template->assign_vars(array(
	'FORM_ONOFF' => $page_form_onoff,
	'ARTICLE_PATH' => $article_path,

));




//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>