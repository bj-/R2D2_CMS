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

//$show_article = TRUE; // флаг задающий показ текста статьи

//
// собираем страницу со статьей
//
$get_employer = $_POST['id'];
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами

// сохраняем отрактированного работодателя
$get_employer_name = substr($_POST["employer_name"], 0, 255);
$get_employer_url = substr($_POST["employer_url"], 0, 255);
$get_employer_email = substr($_POST["employer_email"], 0, 255);
$get_employer_jobemail = substr($_POST["employer_jobemail"], 0, 255);
$get_employer_metro = substr($_POST["employer_metro"], 0, 255);
$get_employer_address = substr($_POST["employer_address"], 0, 255);
$get_action = substr($_POST["action"], 0, 16);
$get_employer_desc = str_replace('"', '\"', $_POST["employer_desc"]);
$get_employer_resp_site = substr($_POST["employer_resp_site"], 0, 11);
$get_employer_resp_direct = substr($_POST["employer_resp_direct"], 0, 11);
$get_employer_resp_fake = substr($_POST["employer_resp_fake"], 0, 11);
$get_employer_os = substr($_POST["employer_os"], 0, 255);

if ($get_action == "edit") {
	$sql = 'UPDATE `' . TABLE_EMPLOYERS . 
		'` SET `employer_name`="'.$get_employer_name.'", `employer_url`="'.$get_employer_url.'", `employer_email`="'.$get_employer_email.
		'", `employer_jobemail`="'.$get_employer_jobemail.'", `employer_metro`="'.$get_employer_metro.'", `employer_address`="'.$get_employer_address.
		'", `employer_desc`="'.$get_employer_desc.'", `employer_resp_site`="'.$get_employer_resp_site.'", `employer_resp_fake`="'.$get_employer_resp_fake.'", `employer_resp_direct`="'.$get_employer_resp_direct.'", `employer_os`="'.$get_employer_os.'" WHERE `employer_id`="'.$get_employer.'"';
}
ElseIf ($get_action == "add_employer") {
	$sql = 'INSERT INTO `' . TABLE_EMPLOYERS . '` (`employer_name`, `employer_url`, `employer_email`, `employer_jobemail`, `employer_metro`, `employer_address`, `employer_desc`) VALUES ("'.
		$get_employer_name.'", "'.$get_employer_url.'", "'.$get_employer_email.'", "'.$get_employer_jobemail.'", "'.
		$get_employer_metro.'", "'.$get_employer_address.'", "'.$get_employer_desc.'");';
};

// записываем изменения в бд
if ($get_action) {
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка редактирования/добавления работодателя', '', __LINE__, __FILE__, $sql);
	};
};

$sql = "SELECT * FROM `" . 
TABLE_EMPLOYERS . '` ORDER BY `employer_name` ASC';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$employers_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$employers_data[] = $row;
	};

if ($userdata['user_level'] >0) {
	$edit = '<a href="/admin/article_edit.php?add=1" title="Создать новую статью"><img src="/pic/ico/page_add.gif" alt="Создать новую статью" width="16" height="16" border="0"></a> ';
	$edit .= ($article_data[0]["article_id"] and $article_data[0]["article_edit_type"]<9) ? '<a href="/admin/article_edit.php?id='.$article_data[0]["article_id"].'&edit=1" title="'.$lang['Edit_post'].'"><img src="/pic/ico/edit.gif" alt="'.$lang['Edit_post'].'" width="16" height="16" border="0"></a> ' : "";
	$edit .= ($article_data[0]["article_id"]) ? '<a href="/admin/article_prop.php?&id='.$article_data[0]["article_id"].'" title="Настройки статьи статьи"><img src="/pic/ico/document-properties.png" alt="Настройки статьи" width="16" height="16" border="0"></a> ' : "";
};

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Работодатели";
$page_classification = $employers_data[0]["article_classification"];
$page_desc = $employers_data[0]["article_desc"];
$page_keywords = $employers_data[0]["article_keywords"];
$page_content_direction = "";

$page_paragraf_id = $article_data[0]["paragraf_id"];
$page_paragraf_name = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_name'];
$page_paragraf_path = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_path'];

$submit_path = "/employers.php?article=".$employers_data[0]["article_id"];
$page_id = $employers_data[0]["article_id"];
$page_date = create_date($board_config['article_dateformat'], $employers_data[0]["article_date"], $board_config['board_timezone']);
$check_primary_article = ($employers_data[0]["primary_article"]) ? " checked" : "";

$page_form_email = $employers_data[0]["form_email"];
$page_form_subj = $employers_data[0]["form_subject"];

$page_path = $employers_data[0]["article_name"];
$page_text = (@$no_article) ? "<p><h2>Запрошенная статья не найдена</h2></p><p>Жалоба администратору сайта уже написана автоматически, спасибо за помощь.</p>" : $article_data[0]["article_text"];


$employer_resp_val = array(0 => '--', 1 => 'отправлено', 2 => 'просмотрено', 3 => 'отказ', 4 => 'приглашение', 5 => 'непрошел', 6 => 'нехочу');
$employer_resp_site = $employer_resp_val[$employers_data[0]["employer_resp_site"]];
$employer_resp_direct = $employer_resp_val[$employers_data[0]["employer_resp_direct"]];
// 0 - none; 1 - send; 2 - отказ; 3 - пригл. 4 - непрошел

include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'employers_body.tpl')
);

// список работодателей
$i = 0;
while ($employers_data[$i]["employer_id"]) {
	$employer_resp_site = $employer_resp_val[$employers_data[$i]["employer_resp_site"]];
	$employer_resp_direct = $employer_resp_val[$employers_data[$i]["employer_resp_direct"]];
	$employer_resp_fake = $employer_resp_val[$employers_data[$i]["employer_resp_fake"]];
	// 0 - none; 1 - send; 2 - отказ; 3 - пригл. 
	$employer_os = ($employers_data[$i]["employer_os"]) ? $employers_data[$i]["employer_os"] : "-";

	$template->assign_block_vars('employers_list', array(
		'EMPLOYER_NUM' => ($i+1),
		'EMPLOYER_ID' => $employers_data[$i]["employer_id"],
		'EMPLOYER_NAME' => $employers_data[$i]["employer_name"],
		'EMPLOYER_URL' => $employers_data[$i]["employer_url"],
		'EMPLOYER_EMAIL' => $employers_data[$i]["employer_email"],
		'EMPLOYER_JOBEMAIL' => $employers_data[$i]["employer_jobemail"],
		'EMPLOYER_ADDRESS' => $employers_data[$i]["employer_address"],
		'EMPLOYER_METRO' => $employers_data[$i]["employer_metro"],
		'EMPLOYER_RESP_SITE' => $employer_resp_site,
		'EMPLOYER_RESP_DIRECT' => $employer_resp_direct,
		'EMPLOYER_RESP_FAKE' => $employer_resp_fake,
		'EMPLOYER_OS' => $employer_os,
		
	));
	$i++;
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