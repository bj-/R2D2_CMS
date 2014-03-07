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
$get_employer = substr($_GET['id'], 0, 11);
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами
$get_action = substr($_GET['action'], 0, 16);

// ищем ID раздела странички если не указан файл. если путь в каком-то месте неверный - возвращаем ошибку


$sql = "SELECT * FROM `" . 
TABLE_EMPLOYERS . '` WHERE `employer_id`="'.$get_employer.'"';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$employers_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$employers_data[] = $row;
	};

//
// Start output of page
//
define('SHOW_ONLINE', true);

$template->set_filenames(array(
	'body' => 'dynamic/employers/dyn_employers_edit.tpl')
);

if ($get_action == 'edit' or $get_action == 'add_employer') {
	$template->assign_block_vars('employer_edit', array());
	$employer_desc = $employers_data[0]["employer_desc"];
}
ElseIf ($get_action == 'details') {
	$template->assign_block_vars('employer_details', array());
	$employer_desc = nl2br($employers_data[0]["employer_desc"]);
};

$employer_resp_val = array(0 => '--', 1 => 'отправлено', 2 => 'просмотрено', 3 => 'отказ', 4 => 'приглашение', 5 => 'непрошел', 6 => 'нехочу');

$i = 0;
$employer_resp_site = "";
$employer_resp_direct = "";
while ($employer_resp_val[$i]) {
	$employer_resp_site .= ($employers_data[0]["employer_resp_site"] == $i) ? '<option value="'.$i.'" selected="selected">'.$employer_resp_val[$i].'</option>' : '<option value="'.$i.'">'.$employer_resp_val[$i].'</option>';
	$employer_resp_direct .= ($employers_data[0]["employer_resp_direct"] == $i) ? '<option value="'.$i.'" selected="selected">'.$employer_resp_val[$i].'</option>' : '<option value="'.$i.'">'.$employer_resp_val[$i].'</option>';
	$employer_resp_fake .= ($employers_data[0]["employer_resp_fake"] == $i) ? '<option value="'.$i.'" selected="selected">'.$employer_resp_val[$i].'</option>' : '<option value="'.$i.'">'.$employer_resp_val[$i].'</option>';
	$i++;
};

$template->assign_vars(array(
	'EMPLOYER_ID' => $employers_data[0]["employer_id"],
	'EMPLOYER_NAME' => $employers_data[0]["employer_name"],
	'EMPLOYER_URL' => $employers_data[0]["employer_url"],
	'EMPLOYER_EMAIL' => $employers_data[0]["employer_email"],
	'EMPLOYER_JOBEMAIL' => $employers_data[0]["employer_jobemail"],
	'EMPLOYER_ADDRESS' => $employers_data[0]["employer_address"],
	'EMPLOYER_METRO' => $employers_data[0]["employer_metro"],
	'EMPLOYER_RESP_SITE' => $employer_resp_site,
	'EMPLOYER_RESP_DIRECT' => $employer_resp_direct,
	'EMPLOYER_RESP_FAKE' => $employer_resp_fake,
	'EMPLOYER_OS' => $employers_data[0]["employer_os"],
	'EMPLOYER_DESC' => $employer_desc,
	'ACTION' => $get_action
	));




//
// Generate the page
//
$template->pparse('body');

?>