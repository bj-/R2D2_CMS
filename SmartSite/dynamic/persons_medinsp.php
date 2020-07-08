<?php
/***************************************************************************
 *                                blockdetails.php
 *                            -------------------
 *   begin                : Jun 13, 2018
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: blockdetails.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
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
include("../includes/config.php");
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');


// Get params from url
//$params = $_GET["params"];
//$params = explode(";", $params);
//$UserGuid = $params[5];                     
//var_dump($_GET);

$DateFrom = substr($_GET["DateFrom"], 0, 10);
$UseDateFrom = substr($_GET["UseDateFrom"], 0, 10);
$DateTo = substr($_GET["DateTo"], 0, 10);
$UseDateTo = substr($_GET["UseDateTo"], 0, 10);
$UseSenOrFio = substr($_GET["UseSenOrFio"], 0, 10);
$UserGuid = substr($_GET["uGuid"], 0, 36);


$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

$SQL = $SQL_QUERY["Persons_MedicalInspections"];
$SQL = str_replace("%%GUID%%", $UserGuid, $SQL);
$SQL = str_replace("%%FROM%%", $DateFrom, $SQL);
$SQL = str_replace("%%TO%%", $DateTo, $SQL);
//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$data = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}
//var_dump($dataP);


sqlsrv_close($conn) ;

//$HID_SerNo = iconv("Windows-1251", "UTF-8", $data[$i]["HID"]);

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";
//$FIO = iconv("Windows-1251", "UTF-8", @$data[0]["FIO"]);

$template->set_filenames(array(
	'body' => 'persons_medinsp.tpl')
);


$template->assign_vars(array(
	'ARTICLE' => $page_text,
//	'FIO' => $FIO,
	));

$i = 0;
while ( @$data[$i] )
{
//	$ReceivedFromBlock = $data[$i]["ReceivedFromBlock"];
	$FIO = iconv("Windows-1251", "UTF-8", $data[$i]["FIO"]);
	$HID = $data[$i]["HID"];
	$ExaminationRead = $data[$i]["ExaminationRead"];
//	$RecordingState = $data[$i]["RecordingState"];
//	$SystPressure = $data[$i]["SystPressure"];
//	$DistPressure = $data[$i]["DistPressure"];
//	$ExaminationType = $data[$i]["ExaminationType"];
	$ExaminationDateTime = $data[$i]["ExaminationDateTime"];
//	$ExaminationResult = $data[$i]["ExaminationResult"];
//	$InterShiftExaminationType = $data[$i]["InterShiftExaminationType"];
//	$InterShiftExaminationDateTime = $data[$i]["InterShiftExaminationDateTime"];
//	$InterShiftExaminationResult = $data[$i]["InterShiftExaminationResult"];
//	$Last_Name = $data[$i]["Last_Name"];
//	$First_Name = $data[$i]["First_Name"];
//	$Middle_Name = $data[$i]["Middle_Name"];

	$template->assign_block_vars('row', array(
//		'BLOCKSERNO' => $ReceivedFromBlock,
		'FIO' => $FIO,
		'HID' => $HID,
		'EXAM_READ_DATE' => $ExaminationRead,
		'EXAM_DATE' => $ExaminationDateTime,
//		'RR_MD_AVG' => $RR_MD_Avg,
	));
		
	$i++;
}

$template->pparse('body');

?>