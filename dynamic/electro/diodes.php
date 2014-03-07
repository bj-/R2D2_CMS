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
$limit = intval(substr($_GET["limit"],0,2));

$sql = 'SELECT * '.
		' FROM `' . TABLE_ELECTRO_DIODES . '` '; // .
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


$template->set_filenames(array(
	'body' => 'dynamic/electro/diodes.tpl')
);

//$template->assign_vars(array(
//	'GALLERY_CLASS' => "highslide-gallery175"
//));


// одрезаем нули справа и, затем, точку если она стала последней => получаем целое число если у него не было дроби
function rTrimZeroAndDot($string)
{
	$ret = rtrim($string,"0");
	$ret = rtrim($ret,".");
	return $ret;
}

$i = 0;
while ($elements_data[$i]["id"]) {
//	$gat_img = ($gallery_cat_data[$i]["cat_img"]) ? $gallery_path.$gallery_cat_data[$i]["cat_img"] : "/pic/ico/question_b.gif";
	$UForw = rTrimZeroAndDot($elements_data[$i]["UForw"]);
	$URevMax = rTrimZeroAndDot($elements_data[$i]["URevMax"]);
	$URevImpMax = rTrimZeroAndDot($elements_data[$i]["URevImpMax"]);
	$IforwMaxA = rTrimZeroAndDot($elements_data[$i]["IforwMaxA"]);
	$IforwImpMaxA = rTrimZeroAndDot($elements_data[$i]["IforwImpMaxA"]);
	$IRevMaxA = rTrimZeroAndDot($elements_data[$i]["IRevMaxA"]);

	$design = ($elements_data[$i]["design"]) ? '<a href="/img/files/electro/DataSheets/Diodes/Cases/' . $elements_data[$i]["design"] . '"><img src="/pic/ico/pencil-ruler_7907.png" alt="Data Sheet" border="0" height="16" width="16" /></a>' : '';

	$photo = ($elements_data[$i]["photo"]) ? $elements_data[$i]["photo"] : '<img src="/pic/1pxtransparent.png" width="16" height="16" />';

	$datasheet = ($elements_data[$i]["dSheet"]) ? '<a href="/img/files/electro/DataSheets/Diodes/' . $elements_data[$i]["dSheet"] . '"><img src="/pic/ico/document.png" alt="Item Design" border="0" height="16" width="16" /></a>' : '';

	$template->assign_block_vars('item_list', array(
		'ID' => $elements_data[$i]["id"],
		'TYPE' => $elements_data[$i]["type"],
		'NAME' => $elements_data[$i]["name"],
		'QUANTITY' => $elements_data[$i]["quantity"],
		'MANUFACTURER' => $elements_data[$i]["manuf"],
		'MATERIAL' => $elements_data[$i]["material"],
		'UFORW' => $UForw,
		'UREVMAX' => $URevMax,
		'UREVIMPMAX' => $URevImpMax,
		'IFORWMAXA' => $IforwMaxA,
		'IFORWIMPMAXA' => $IforwImpMaxA,
		'IREVMAXA' => $IRevMaxA,
		'CASE' => $elements_data[$i]["case"],
		'TWORK' => $elements_data[$i]["tWork"],
		'MOUNTING' => $elements_data[$i]["mounting"],
		'DESIGN' => $design,
		'PHOTO' => $photo,
		'DSHEET' => $datasheet,
 
	));
	$i++;
};




//
// Generate the page
//
$template->pparse('body');

?>