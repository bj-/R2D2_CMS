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
$limit = intval(substr($_GET["limit"],0,2));


// параметры фильтрации
$filter = array();
$s="";
if ($s = get_filter_parameters("name", $_GET["name"], "like")) $filter[] = $s;
if ($s = get_filter_parameters("color", $_GET["color"], "like")) $filter[] = $s;
if ($s = get_filter_parameters("case", $_GET["case"], "like")) $filter[] = $s;
if ($s = get_filter_parameters("mounting", $_GET["mount"], "like")) $filter[] = $s;


if (count($filter))
{
	$filter_str = 'WHERE ' . implode(" AND ", $filter) . " ";
}

$sql = 'SELECT * '.
		' FROM `' . TABLE_ELECTRO_LED . '` ' .
		$filter_str .
		'ORDER BY color ASC'; // .
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
	'body' => 'dynamic/electro/resistor.tpl')
);

// Paths
$DataSheetPath = "Resistor";



//$template->assign_vars(array(
//	'GALLERY_CLASS' => "highslide-gallery175"
//));


$i = 0;
while ($elements_data[$i]["id"]) {
//	$gat_img = ($gallery_cat_data[$i]["cat_img"]) ? $gallery_path.$gallery_cat_data[$i]["cat_img"] : "/pic/ico/question_b.gif";
	$UForwMin = rTrimZeroAndDot($elements_data[$i]["UForwMin"]);
	$UForwTyp = rTrimZeroAndDot($elements_data[$i]["UForwTyp"]);
	$UForwMax = rTrimZeroAndDot($elements_data[$i]["UForwMax"]);

	$ITyp = rTrimZeroAndDot($elements_data[$i]["ITyp"]);
	$IImpMax = rTrimZeroAndDot($elements_data[$i]["IImpMax"]);

	$power = rTrimZeroAndDot($elements_data[$i]["power"]);
	$WaweLenght = $elements_data[$i]["WaweLenghtMin"] . "-" .  $elements_data[$i]["WaweLenghtTyp"];
	$Luminous = $elements_data[$i]["LuminousMin"] . "-" .  $elements_data[$i]["LuminousTyp"];
	$mCd = $elements_data[$i]["mCdMin"] . "-" .  $elements_data[$i]["mCdTyp"];

	$design = MakePropetyLinks($DataSheetPath, "design", $elements_data[$i]["design"]);
	$photo = MakePropetyLinks($DataSheetPath, "photo", $elements_data[$i]["photo"]);
	$datasheet = MakePropetyLinks($DataSheetPath, "dSheet", $elements_data[$i]["dSheet"]);
/*
	$design = ($elements_data[$i]["design"]) ? '<a href="/img/files/electro/DataSheets/Led/Cases/' . $elements_data[$i]["design"] . '"><img src="/pic/ico/pencil-ruler_7907.png" alt="Data Sheet" border="0" height="16" width="16" /></a>' : '';
	$photo = ($elements_data[$i]["photo"]) ? '<a href="/img/files/electro/DataSheets/Led/Photo/' . $elements_data[$i]["photo"] . '"><img src="/pic/ico/photo_16x16.png" alt="Photo" border="0" height="16" width="16" /></a>' : '<img src="/pic/1pxtransparent.png" width="16" height="16" />';
	$datasheet = ($elements_data[$i]["dSheet"]) ? '<a href="/img/files/electro/DataSheets/Led/' . $elements_data[$i]["dSheet"] . '"><img src="/pic/ico/document.png" alt="Item Design" border="0" height="16" width="16" /></a>' : '';
*/
	$template->assign_block_vars('item_list', array(
		'ID' => $elements_data[$i]["id"],
		'COLOR' => $elements_data[$i]["color"],
		'NAME' => $elements_data[$i]["name"],
		'QUANTITY' => $elements_data[$i]["quantity"],
		'MANUFACTURER' => $elements_data[$i]["manuf"],
		'UFORWMIN' => $UForwMin,
		'UFORWTYP' => $UForwTyp,
		'UFORWMAX' => $UForwMax,
		'ITYP' => $ITyp,
		'IIMPMAX' => $IImpMax,
		
		'POWER' => $power,
		'WAWELENGTH' => $WaweLenght,
		'LUMINOUS' => $Luminous,
		'MCD' => $mCd,
		'ANGLE' => $elements_data[$i]["angle"],
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