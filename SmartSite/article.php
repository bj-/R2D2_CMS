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

define('IN_R2D2', true);
include("includes/config.php");
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/common.php');



//
// Start output of page
//
define('SHOW_ONLINE', true);

$page_text = "AAAAA";

$template->set_filenames(array(
	'body' => 'article_body.tpl')
);

$template->assign_block_vars('switch_left_menu', array());


$template->assign_vars(array(
	'SAVED' => @$saved,
	'ARTICLE' => $page_text,
	'EDIT' => @$edit
	));

$template->assign_block_vars('show_form.form_field_varchar', array(
	'NAME' => 'ff',
	'FIELD_NAME' => 'field_',
	'TYPE' => 'text',
	'VALUE' => 'ff'
	));



//
// Generate the page
//
$template->pparse('body');

?>