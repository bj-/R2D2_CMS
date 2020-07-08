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

$SQLSrvCode = "SpbMetro-sRoot";
$conn = MSSQLconnect( $SQLSrvCode, "Shturman" );

$SQL = $SQL_QUERY["Persons_NormsGroup"];
$SQL = str_replace("%%FROM%%", $DateFrom, $SQL);
$SQL = str_replace("%%TO%%", $DateTo, $SQL);
//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$data = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}

sqlsrv_close($conn) ;

//$HID_SerNo = iconv("Windows-1251", "UTF-8", $data[$i]["HID"]);

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'persons_gpnorms.tpl')
);


$template->assign_vars(array(
	'SRV_NAME' => $SQLSrvCode,
	'DATE_FROM' => $DateFrom,
	'DATE_UNTIL' => $DateTo,
	'ARTICLE' => $page_text,
	'CALCULATED' => $data[0]["Calculated"],
	'VALIDITY' => $data[0]["Validity"],
	'RR_MD_MIN' => $data[0]["RR_MD_Min"],
	'RR_MD_MAX' => $data[0]["RR_MD_Max"],
	'RR_MD_AVG' => $data[0]["RR_MD_Avg"],
	'RR_AM_MIN' => $data[0]["RR_AM_Min"],
	'RR_AM_MAX' => $data[0]["RR_AM_Max"],
	'RR_AM_AVG' => $data[0]["RR_AM_Avg"],
	'BOI_MD_MIN' => $data[0]["BOI_MD_Min"],
	'BOI_MD_MAX' => $data[0]["BOI_MD_Max"],
	'BOI_MD_AVG' => $data[0]["BOI_MD_Avg"],
	'BOI_AM_MIN' => $data[0]["BOI_AM_Min"],
	'BOI_AM_MAX' => $data[0]["BOI_AM_Max"],
	'BOI_AM_AVG' => $data[0]["BOI_AM_Avg"],
	));



$i = 0;
while ( @$data[$i] )
{
	$Calculated = $data[$i]["Calculated"];
	$Validity = $data[$i]["Validity"];
	$RR_MD_Min = $data[$i]["RR_MD_Min"];
	$RR_MD_Max = $data[$i]["RR_MD_Max"];
	$RR_MD_Avg = $data[$i]["RR_MD_Avg"];
	$RR_AM_Min = $data[$i]["RR_AM_Min"];
	$RR_AM_Max = $data[$i]["RR_AM_Max"];
	$RR_AM_Avg = $data[$i]["RR_AM_Avg"];
	$BOI_MD_Min = $data[$i]["BOI_MD_Min"];
	$BOI_MD_Max = $data[$i]["BOI_MD_Max"];
	$BOI_MD_Avg = $data[$i]["BOI_MD_Avg"];
	$BOI_AM_Min = $data[$i]["BOI_AM_Min"];
	$BOI_AM_Max = $data[$i]["BOI_AM_Max"];
	$BOI_AM_Avg = $data[$i]["BOI_AM_Avg"];

	$template->assign_block_vars('group_norm', array(
		'CALCULATED' => $Calculated,
		'VALIDITY' => $Validity,
		'RR_MD_MIN' => $RR_MD_Min,
		'RR_MD_MAX' => $RR_MD_Max,
		'RR_MD_AVG' => $RR_MD_Avg,
		'RR_AM_MIN' => $RR_AM_Min,
		'RR_AM_MAX' => $RR_AM_Max,
		'RR_AM_AVG' => $RR_AM_Avg,
		'BOI_MD_MIN' => $BOI_MD_Min,
		'BOI_MD_MAX' => $BOI_MD_Max,
		'BOI_MD_AVG' => $BOI_MD_Avg,
		'BOI_AM_MIN' => $BOI_AM_Min,
		'BOI_AM_MAX' => $BOI_AM_Max,
		'BOI_AM_AVG' => $BOI_AM_Avg,
	));
		
	$i++;
}




$template->pparse('body');

?>