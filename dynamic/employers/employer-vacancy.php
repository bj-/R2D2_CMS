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

//$show_article = TRUE; // флаг задающий показ текста статьи

//
// собираем страницу со статьей
//
$get_id = substr($_GET['id'], 0, 11);
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами
$get_action = substr($_GET['action'], 0, 16);
$get_employer_id = substr($_GET['employer_id'], 0, 11);

if ($_POST["action"] == "edit" or $_POST["action"] == "add") {
	$vacancy_id = substr($_POST["vacancy_id"], 0, 11);
	$page_text = $_POST["page_text"];
	$employer_id = substr($_POST["employer_id"], 0, 255);
	$vacancy_name = substr($_POST["vacancy_name"], 0, 255);
	$vacancy_url = substr($_POST["vacancy_url"], 0, 255);
	$vacancy_response = substr($_POST["vacancy_response"], 0, 11);
	$vacancy_many = substr($_POST["vacancy_many"], 0, 255);
	
	$vacancy_name = str_replace('"', '\"', $vacancy_name);
	$page_text = str_replace('"', '\"', $page_text);

	if ($_POST["action"] == "edit") {
		$sql = "UPDATE `" . TABLE_EMPLOYERS_VACANCY . 
			'` SET `vacancy_name` = "'.$vacancy_name.'",  `vacancy_url` = "'.$vacancy_url.
			'",  `vacancy_text` = "'.$page_text.'",  `vacancy_response` = "'.$vacancy_response.
			'", `vacancy_many` = "'.$vacancy_many.'" WHERE `vacancy_id` = "'.$vacancy_id.'";';
	}
	if ($_POST["action"] == "add") {
		$sql = "INSERT INTO `" . TABLE_EMPLOYERS_VACANCY . 
			'` (`employer_id`, `vacancy_name`, `vacancy_url`, `vacancy_text`, `vacancy_response`, `vacancy_many`)
			VALUES ("'.$employer_id.'", "'.$vacancy_name.'", "'.$vacancy_url.'", "'.$page_text.'", "'.$vacancy_response.'", "'.$vacancy_many.'");';
	};
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка сохранения отредактированной вакансии', '', __LINE__, __FILE__, $sql);
	};
	$get_action = "list";
	$get_id = $employer_id;
};
/*
 vacancy_id  employer_id  vacancy_name  vacancy_url  vacancy_text  vacancy_response 


vacancy_id
employer_id
action
page_text
vacancy_name
vacancy_url
vacancy_response
*/
// ищем ID раздела странички если не указан файл. если путь в каком-то месте неверный - возвращаем ошибку

if ($get_action == "list") {
	$sql = "SELECT * FROM `" . 
	TABLE_EMPLOYERS_VACANCY . '` WHERE `employer_id`="'.$get_id.'" ORDER BY `vacancy_name` ASC';
}
ElseIf ($get_action == "text" or $get_action == "edit") {
	$sql = "SELECT * FROM `" . 
	TABLE_EMPLOYERS_VACANCY . '` WHERE `vacancy_id`="'.$get_id.'" ORDER BY `vacancy_name` ASC';
};

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$vacancy_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$vacancy_data[] = $row;
	};

//
// Start output of page
//
define('SHOW_ONLINE', true);

$template->set_filenames(array(
	'body' => 'dynamic/employers/dyn_employers_vacancy.tpl')
);

$employer_resp_val = array(0 => '--', 1 => 'отправлено', 2 => 'просмотрено', 3 => 'отказ', 4 => 'приглашение', 5 => 'непрошел', 6 => 'нехочу');


if ($get_action == "list") {
	$i=0;
	while ($vacancy_data[$i]["vacancy_id"]) {
		$template->assign_block_vars('vacancy_list', array(
			'VACANCY_ID' => $vacancy_data[$i]["vacancy_id"],
			'EMPLOYER_ID' => $vacancy_data[$i]["employer_id"],
			'VACANCY_NAME' => $vacancy_data[$i]["vacancy_name"],
			'VACANCY_URL' => $vacancy_data[$i]["vacancy_url"],
			'VACANCY_TEXT' => $vacancy_data[$i]["vacancy_text"],
			'VACANCY_RESPONSE' => $employer_resp_val[$vacancy_data[$i]["vacancy_response"]],
			'VACANCY_MANY' => $vacancy_data[$i]["vacancy_many"],
			'ACTION' => $get_action
			));
		$i++;
	};
}
ElseIf ($get_action == "text") {
	$template->assign_block_vars('vacancy_text', array(
		'VACANCY_ID' => $vacancy_data[0]["vacancy_id"],
		'VACANCY_TEXT' => $vacancy_data[0]["vacancy_text"],
		'ACTION' => $get_action
		));
}
ElseIf ($get_action == "edit") {
	
	$i = 0;
	$vacancy_response = "";
	while ($employer_resp_val[$i]) {
		$vacancy_response .= ($vacancy_data[0]["vacancy_response"] == $i) ? '<option value="'.$i.'" selected="selected">'.$employer_resp_val[$i].'</option>' : '<option value="'.$i.'">'.$employer_resp_val[$i].'</option>';
		$i++;
	};

	$template->assign_block_vars('vacancy_edit', array(
		'VACANCY_ID' => $vacancy_data[0]["vacancy_id"],
		'EMPLOYER_ID' => $vacancy_data[0]["employer_id"],
		'VACANCY_NAME' => $vacancy_data[0]["vacancy_name"],
		'VACANCY_URL' => $vacancy_data[0]["vacancy_url"],
		'VACANCY_TEXT' => $vacancy_data[0]["vacancy_text"],
		'VACANCY_RESPONSE' => $vacancy_response,
		'VACANCY_MANY' => $vacancy_data[0]["vacancy_many"],
		'ACTION' => $get_action
		));
}
ElseIf ($get_action == "add") {
	$template->assign_block_vars('vacancy_edit', array(
//		''
		'EMPLOYER_ID' => $get_employer_id,
		'VACANCY_NAME' => "",
		'VACANCY_URL' => "",
		'VACANCY_TEXT' => "",
		'VACANCY_RESPONSE' => "",
		'VACANCY_MANY' => "",
		'ACTION' => $get_action
		));
};

//
// Generate the page
//
$template->pparse('body');

?>