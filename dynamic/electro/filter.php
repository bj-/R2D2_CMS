<?php
/***************************************************************************
 *                                dyn_gallery_cat.php
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
include($DRoot . '/includes/ElectroSchemaCommon.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);
//
// End session management
//

//$cat_id = substr($_GET["id"],0,11);
//$page =  substr($_GET["page"],0,11);
$type = substr($_GET["type"],0,15); // тип фильтра


$template->set_filenames(array(
	'body' => 'dynamic/electro/filter.tpl')
);

if ($type == "led")
{
	// echo $_GET["type"];
	$template->assign_block_vars('filter_led', array(
	'ID' => $elements_data[$i]["id"],
	));
}
else if ($type == "resistor")
{
	// echo $_GET["type"];
	$template->assign_block_vars('filter_resistor', array(
	'ID' => $elements_data[$i]["id"],
	));
}
/*
$sql = 'SELECT * '.
		' FROM `' . TABLE_ELECTRO_LED . '` ' .
		'ORDER BY color ASC' ; // .
//		'WHERE `cat_pid` >= "0" AND `cat_type` = "1" '.
//		'GROUP BY RAND() '.
//		'LIMIT 0, '.$limit.';';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База элементов отсутствует', '', __LINE__, __FILE__, $sql);
	};


$elements_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$elements_data[] = $row;
	};
*/




//$template->assign_vars(array(
//	'GALLERY_CLASS' => "highslide-gallery175"
//));

/*
$i = 0;
while ($elements_data[$i]["id"]) {

	$template->assign_block_vars('item_list', array(
		'ID' => $elements_data[$i]["id"],
 
	));
	$i++;
};
*/



//
// Generate the page
//
$template->pparse('body');

?>