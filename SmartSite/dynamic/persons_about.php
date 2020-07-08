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
$DateFrom = substr($_GET["DateFrom"], 0, 10);
$UseDateFrom = substr($_GET["UseDateFrom"], 0, 10);
$DateTo = substr($_GET["DateTo"], 0, 10);
$UseDateTo = substr($_GET["UseDateTo"], 0, 10);
$UseSenOrFio = substr($_GET["UseSenOrFio"], 0, 10);
$UserGuid = substr($_GET["uGuid"], 0, 36);


$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

$SQL = $SQL_QUERY["Persons"] . " AND [u].[Guid] = '$UserGuid'";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$data = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}

sqlsrv_close($conn) ;

$i=0;

$LastName = iconv("Windows-1251", "UTF-8", $data[$i]["Last_Name"]);
$First_Name = iconv("Windows-1251", "UTF-8", $data[$i]["First_Name"]);
$Middle_Name = iconv("Windows-1251", "UTF-8", $data[$i]["Middle_Name"]);

$HID_SerNo = iconv("Windows-1251", "UTF-8", $data[$i]["HID"]);

//	$MacAddress = iconv("Windows-1251", "UTF-8", $data[$i]["MacAddress"]);
$HID_FW = iconv("Windows-1251", "UTF-8", $data[$i]["HID_FW"]);
//	$Is_Connected = iconv("Windows-1251", "UTF-8", $data[$i]["Is_Connected"]);
$Battery_Level = $data[$i]["Battery_Level"];
$Battery_Level = ( $Battery_Level != "" ) ? "$Battery_Level%" : "";

$BlockSerialNo = iconv("Windows-1251", "UTF-8", $data[$i]["BlockSerialNo"]);
$Wagon = iconv("Windows-1251", "UTF-8", $data[$i]["Wagon"]);
//	$Train = iconv("Windows-1251", "UTF-8", $data[$i]["Train"]);
$StationName = iconv("Windows-1251", "UTF-8", $data[$i]["StationName"]);
//	$LineName = iconv("Windows-1251", "UTF-8", $data[$i]["LineName"]);
$LineNum = iconv("Windows-1251", "UTF-8", $data[$i]["LineNum"]);
//	$WayNo = iconv("Windows-1251", "UTF-8", $data[$i]["WayNo"]);

$Position = ( $Wagon != "" ) ?  "$Wagon ($BlockSerialNo), $StationName ($LineNum)" : "";
$Position = ( $Wagon == "" and  $BlockSerialNo ) ?  "on $BlockSerialNo" : $Position;

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'persons_about.tpl')
);


$template->assign_vars(array(
	'LASTNAME' => $LastName,
	'FIRSTNAME' => $First_Name,
	'MIDDLENAME' => $Middle_Name,
	'SENSORSERNO' => $HID_SerNo,
	'BATTERY_LEVEL' => $Battery_Level,
	'HID_FW' => $HID_FW,
	'POSITION' => $Position,
	));

$template->pparse('body');

?>