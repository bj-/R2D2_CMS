<?php
// Редактирование текстов (статей)

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

/*
if (@$page_date <> 0) {
	$page_date = "";
//	$check_selected_date = " checked";
//	$check_current_date = "";
}
*/

$template->set_filenames(array(
	'body' => 'admin/edit_body.tpl')
);

if (@$_GET['add']) {
	$template->assign_block_vars('switch_add', array());
};

$general_page = ($article_data[0]["paragraf_id"] == 0) ? "<option value='0' selected>Главная страница</option>" : "";
$template->assign_vars(array(
//	'ARTICLE_PATH' => "/" . $url . "/",
	'PAGE_ID' => $page_id,
	'NEWS_SOURCE_ID' => $news_source_id,
	'SOURCE_ID' => $source_id,
	'SUBMIT_PATH' => $submit_path,
	'ARTICLE_ID' => $page_id,
	'PAGE_TITLE' => $page_title,
	'PAGE_DESC' => $page_desc,
	'PAGE_DATE' => $page_date,
	'PAGE_SOURCE_NAME' => $page_source_name,
	'PAGE_PHOTO' => $page_photo,
	'PAGE_PHOTO_EXIST' => $page_photo_exist,
	'PAGE_VIDEO' => $page_video,
//	'CHECK_CURRENT_DATE' => $check_current_date,
//	'CHECK_SELECTED_DATE' => $check_selected_date,
	'CHECK_PRIMARY_ARTICLE' => $check_primary_article,
	'GENERAL_PAGE' => $general_page,
	'ARTICLE_PARAGRAF' => buildparagraflist(0,0),
	'CLASSIFICATION' => $page_classification,
	'PAGE_KEYWORDS' => $page_keywords,
	'PAGE_PATH' => $page_path,
	'S_CONTENT_DIRECTION' => $page_content_redirection,
//	'PATCH_DESCRIPTION' => $paragrad_desc,
	'ARTICLE' => $page_text,
	'EDIT' => @$edit,
	'ROWS' => $rows_edit,
	'FORM_EMAIL' => $page_form_email,
	'FORM_SUBJECT' => $page_form_subj
	));

?>
