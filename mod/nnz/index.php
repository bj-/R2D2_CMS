<?php
/***************************************************************************
 *                                nnz_personal.php
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

//
// собираем страницу со статьей
//
//$get_article = $_GET['article'];
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами

$get_lastname = substr($_POST['lastname'],0,20);
$get_firstname = substr($_POST['firstname'],0,20);
$get_middlename = substr($_POST['middlename'],0,20);


//$get_ = $_POST['

$filter = array();

$filter[] = ($get_lastname) ? ' `lastname` LIKE "%'.$get_lastname.'%" ': false;
$filter[] = ($get_firstname) ? ' `firstname` LIKE "%'.$get_firstname.'%" ': false;
$filter[] = ($get_middlename) ? ' `middlename` LIKE "%'.$get_middlename.'%" ': false;


//echo '<pre>';
//var_dump($filter);
$filter = array_filter($filter);
$sql_filter = (count($filter)) ? ' WHERE '. implode(" AND ", $filter) : "";

// Берем статью из БД
$sql = "SELECT * FROM `" . TABLE_NNZ_PEOPLE . '` '.
			$sql_filter. 
			'LIMIT 0,100;';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
};


$article_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$article_data[] = $row;
};



if ($userdata['user_level'] >0) {
/*
	$edit = '<a href="/admin/article_edit.php?add=1" title="Создать новую статью"><img src="/pic/ico/page_add.gif" alt="Создать новую статью" width="16" height="16" border="0"></a> ';
	$edit .= ($article_data[0]["article_id"]) ? '<a href="/admin/article_edit.php?id='.$article_data[0]["article_id"].'&edit=1" title="'.$lang['Edit_post'].'"><img src="/pic/ico/edit.gif" alt="'.$lang['Edit_post'].'" width="16" height="16" border="0"></a> ' : "";
	$edit .= ($article_data[0]["article_id"]) ? '<a href="/admin/article_prop.php?&id='.$article_data[0]["article_id"].'" title="Настройки статьи статьи"><img src="/pic/ico/document-properties.png" alt="Настройки статьи" width="16" height="16" border="0"></a> ' : "";
*/
};

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = (@$no_article) ? "Статья не найдена" : $article_data[0]["article_title"];



include($DRoot . '/includes/page_header.'.$phpEx);

//var_dump($template);
$template->set_filenames(array(
	'nnz_body' => 'nnz/nnz_people_body.tpl')
);

$template->assign_vars(array(
	'SAVED' => @$saved,
	'S_LASTNAME' => $get_lastname,
	'S_FIRSTNAME' => $get_firstname,
	'S_MIDDLENAME' => $get_middlename,


	'PATCH_DESCRIPTION' => $paragrad_desc,
	'ARTICLE' => $page_text,
	'ARTICLE_ID' => $page_id,
	'EDIT' => @$edit
));

$i = 0;
while ($article_data[$i]["id"]) {
	
	$photo = ($article_data[$i]["img_nnz_id"]) ? '<img src="'.$article_data[$i]["img_url"].'" width="50" />' : "";
	
	$template->assign_block_vars('searchresult', array(
		'ID' => $article_data[$i]["id"],
		
		'PHOTO' =>  $photo,
		'LASTMANE' => $article_data[$i]["Lastname"],
		'FIRSTMANE' => $article_data[$i]["Firstname"],
		'MIDDLETMANE' => $article_data[$i]["Middlename"],

		'DEP_NAME' => $article_data[$i]["dep_name"],
	
	));
	$i++;
}

//
// Generate the page
//
$template->pparse('nnz_body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>