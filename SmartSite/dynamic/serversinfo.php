<?php
/***************************************************************************
 *                                persons.php
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


$rnd = rand ( 0 , 1000000000 );

$data = array();

$i = 0;

//echo "dsds";
//Shturman3 Databases 
//$Servers = ["sRoot", "Root3"];
$Servers = ["sRoot","Anal"];
foreach ($Servers AS $server)
{
	$x = "";

	if ($server == "Root3")
	{
		$conn = MSSQLconnect( "SpbMetro3s-Root3", "Shturman" );
	}
	elseif ($server == "sRoot")
	{
		$conn = MSSQLconnect( "SpbMetro-sRoot", "Shturman" );
	}		
	elseif ($server == "Anal")
	{
		$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );
	}		
	else
	{
		echo "<b>ERROR Server selection</b>";
		exit;
	}


	$data[$i]["SrvName"] = $server;


	$SQL_QUERY["SrvInfo_3_LastConnect"] = "
		SELECT TOP 1 DateDiff(SECOND,MAX([Changed]),GetUtcDate()) AS [TimeAgo] FROM [servers] WHERE [Alias] LIKE 'STB%'
	";

	$SQL = $SQL_QUERY["SrvInfo_3_LastConnect"];
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	$ServersList= array();
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[$i]["BlockLastConnect"]["val"] = $row["TimeAgo"];
		$data[$i]["BlockLastConnect"]["style"] = ($row["TimeAgo"] > 180) ? $style["bg-red"] : "";
	}


	$SQL_QUERY["SrvInfo_3_DriversOnTrain"] = "
		SELECT count(*) AS [cnt]
		 FROM [Shturman3].[dbo].[Users]
		 WHERE Vehicles_Guid IS NOT NULL
		";
	$SQL = $SQL_QUERY["SrvInfo_3_DriversOnTrain"];
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	$ServersList= array();
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[$i]["DriversOnTrain"]["val"] = $row["cnt"];
		$data[$i]["DriversOnTrain"]["style"] = ($row["cnt"] < 0) ? $style["bg-red"] : "";
	}

	$SQL_QUERY["SrvInfo_3_BlockConnected"] = "
		SELECT count(*) AS [cnt]
		FROM [Servers]
		WHERE
			[Is_Connected] = 1
			AND [Alias] like 'STB%'
	";

	$SQL = $SQL_QUERY["SrvInfo_3_BlockConnected"];
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	$ServersList= array();
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[$i]["BlockConnected"]["val"] = $row["cnt"];
		$data[$i]["BlockConnected"]["style"] = ($row["cnt"] < 20) ? $style["bg-red"] : "";
	}

	$SQL_QUERY["SrvInfo_3_BlockConnectedToday"] = "
		SELECT count(*) AS [cnt]
		  FROM [Servers]
		  WHERE
			[Alias] like 'STB%'
			AND [Connected] > format(getutcdate(), 'yyy-MM-dd')
		";

	$SQL = $SQL_QUERY["SrvInfo_3_BlockConnectedToday"];
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	$ServersList= array();
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[$i]["BlockConnectedToday"]["val"] = $row["cnt"];
	}

	sqlsrv_close($conn) ;

	$i++;
}

/*
$conn = MSSQLconnect( "SpbMetro4s-Root4", "Shturman" );

$data[$i]["SrvName"] = "Root4";

$SQL_QUERY["SrvInfo_4_BlockLastConnect"] = "
SELECT TOP 1 DateDiff(SECOND,MAX([Connected]),GetDate()) AS [TimeAgo] FROM [servers] WHERE [SerialNo] LIKE 'STB%'
";

$SQL = $SQL_QUERY["SrvInfo_4_BlockLastConnect"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[$i]["BlockLastConnect"]["val"] = $row["TimeAgo"];
	$data[$i]["BlockLastConnect"]["style"] = ($row["TimeAgo"] > 180) ? $style["bg-ll-red"] : "";
}

$SQL_QUERY["SrvInfo_4_DriversOnTrain"] = "
SELECT 
	count(*) AS [cnt] 
FROM 
	SensorsCardio sc, Sensors s, Servers sv, Vehicles ve, MetroTrains mt, users u, persons p 
WHERE 
	sc.Guid = s.guid 
	and sc.UserGuid = u.Guid 
	and u.PersonsGuid = p.Guid 
	and sc.ServersGuid = sv.Guid 
	and ve.Guid = mt.Guid 
	and mt.IsActive = 1 
	and sc.IsConnected = 1  
	and sc.UserGuid = ve.UsersGuid
";

$SQL = $SQL_QUERY["SrvInfo_4_DriversOnTrain"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[$i]["DriversOnTrain"]["val"] = $row["cnt"];
	$data[$i]["DriversOnTrain"]["style"] = ($row["cnt"] < 7) ? $style["bg-ll-red"] : "";
}


$SQL_QUERY["SrvInfo_4_BlockConnected"] = "
SELECT COUNT(*) AS [cnt] FROM [servers] WHERE [SerialNo] LIKE 'STB%' AND [IsConnected] = 1
";

$SQL = $SQL_QUERY["SrvInfo_4_BlockConnected"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[$i]["BlockConnected"]["val"] = $row["cnt"];
	$data[$i]["BlockConnected"]["style"] = ($row["cnt"] < 20) ? $style["bg-ll-red"] : "";
}

$SQL_QUERY["SrvInfo_4_BlockConnectedToday"] = "
SELECT COUNT(*) AS [cnt] FROM [servers] WHERE [SerialNo] LIKE 'STB%' AND [Connected] > format(getdate(), 'yyy-MM-dd')
";

$SQL = $SQL_QUERY["SrvInfo_4_BlockConnectedToday"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[$i]["BlockConnectedToday"]["val"] = $row["cnt"];
}

sqlsrv_close($conn) ;
*/





//=================================  Page  =====================

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'serversinfo.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
//	'CURRENTDATE' => $currentdate,
//	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,

	));

/*
	$template->assign_block_vars('row', array(
		'L_LINK' => $l_Link,
	));

*/

$i = 0;

while ( @$data[$i] )
{
	$ServerName = $data[$i]["SrvName"];

	$BlockLastConnect = $data[$i]["BlockLastConnect"]["val"];
	$BlockLastConnectS = $BlockLastConnect . "s";
	$BlockLastConnectS = ( $BlockLastConnect > 60 ) ? gmdate("i:s", $BlockLastConnect) . "s" : $BlockLastConnectS;
	$BlockLastConnectS = ( $BlockLastConnect > 3600 ) ? gmdate("H:i:s", $BlockLastConnect) . "s" : $BlockLastConnectS;
	$BlockLastConnectS = ( $BlockLastConnect > 86400 ) ? "Died" : $BlockLastConnectS;

	$BlockLastConnectStyle = $data[$i]["BlockLastConnect"]["style"];
	$DriversOnTrain = $data[$i]["DriversOnTrain"]["val"];
	$DriversOnTrainStyle = $data[$i]["DriversOnTrain"]["style"];
	$BlockConnected = $data[$i]["BlockConnected"]["val"];
	$BlockConnectedStyle = $data[$i]["BlockConnected"]["style"];
	$BlockConnectedToday = $data[$i]["BlockConnectedToday"]["val"];


	$template->assign_block_vars('row', array(
		'NAME' => $ServerName,
		'BLOCK_LAST_CONN' => $BlockLastConnectS,
		'BLOCK_LAST_CONN_STYLE' => $BlockLastConnectStyle,
		'DRIVERS' => $DriversOnTrain,
		'DRIVERS_STYLE' => $DriversOnTrainStyle,
		'BLOCKS' => $BlockConnected,
		'BLOCKS_STYLE' => $BlockConnectedStyle,
		'BLOCKS_TOTAL' => $BlockConnectedToday,
	));

	$i++;
}

$template->pparse('body');
?>