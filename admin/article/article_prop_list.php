<?php
// Редактирование текстов (статей)

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

if (@$article_prop) {
	$template->assign_block_vars('switch_article_properties', array());
}
elseif (@$event_prop) {
	$template->assign_block_vars('switch_events_properties', array());
	if ($smGal_status) {
		$template->assign_block_vars('switch_events_properties.switch_smgal_off', array());
	}
	else {
		$template->assign_block_vars('switch_events_properties.switch_smgal_on', array());
	};
	if ($smVideoGal_status) {
		$template->assign_block_vars('switch_events_properties.switch_smvideogal_off', array());
	}
	else {
		$template->assign_block_vars('switch_events_properties.switch_smvideogal_on', array());
	};
};



$template->assign_vars(array(
	'PAGE_ID' => $page_id,
	'PAGE_TITLE' => $page_title,
	'PAGE_DESC' => $page_desc,
	'PAGE_KEYWORDS' => $page_keywords,
	'PAGE_DATE' => $page_date,
	'PAGE_CLASSIFICATION' => $page_classification,
	'S_CONTENT_DIRECTION' => $page_content_direction,
	'PAGE_PARAGRAF' => $page_paragraf,
	'PAGE_PRIMARY' => (($primary_article) ? "<br><small>Основная статья раздела</small>" : ""),
	'PAGE_PATH' => $page_path,
	'PAGE_EMAIL' => $page_form_email,
	'PAGE_EMAIL_SUBJ' => $page_form_subj,
	'PAGE_TEXT' => strip_tags(substr($page_text,0,400), '<p><a>') . "...",

	'PAGE_FORM_NAME' => $article_form_name,

	'LANG' => $url_lang,

	'GALLERY_ONOFF' => $smgal_onoff,
	'VGALLERY_ONOFF' => $smVideoGal_onoff

));

?>