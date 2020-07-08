<?php
/***************************************************************************
 *                                serversstate3_hist_measure.php
 *                            -------------------
 *   begin                : Jun 13, 2018
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: blockdetails.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
 *
 *
 ***************************************************************************/
$ver["serversstate3_hist_measure"] = "1.0.1"; // Version of script
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
//$ShowAlert = substr(@$_GET["ShowAlert"], 0, 20);
//var_dump(@$_GET);
//$ViewType = ( strtoupper(@$_GET["type"]) == "ERRORS" ) ? "ERRORS" : "ALL";  // Show only errors
//$ViewType = ( $ViewType ) ? $ViewType : "ALL";  // Show only errors
//echo "<pre>"; var_dump($_GET); echo "</pre>";

$params = (@$_POST) ? $_POST : $_GET;
$Srv = @$params["srv"];
$InstanceGuid = ( strlen(@$params["Instance"]) == 36 ) ? str_remove_sql_char(substr(@$params["Instance"],0,36)) : "";
$ServiceName = str_remove_sql_char(substr(@$params["Service"],0,30));
$Measure = str_remove_sql_char(substr(@$params["Measure"],0,30));

// TODO костыль для метро. прдумать общие правила (конфиг вероятно)
//$Srv_FrendlyNames = array("SRV-SHTURMAN" => "Root4/Root4H4","SRV-SHTURMAN-C" => "Anal","SRV-SHTURMAN-G1" => "sRoot","SRV-SHTURMAN-G3" => "Root3"); 

//print_r($_GET);

$rnd = rand ( 0 , 1000000000 );


$sql_server = "Diag";
$db = "DiagSrv";
$conn = MSSQLconnect( $sql_server, $db );

$SQL = "
SELECT
    [Guid],
    [ComputerName],
    [IpAddress],
    --[Created],
	FORMAT(DATEADD(HOUR, +3, [Created]), 'dd.MM.yyy HH:mm:ss') AS [Created],
    [MemoryTotalBytes],
    [MemoryAvailBytes],
    --[MemoryReceived],
	FORMAT(DATEADD(HOUR, +3, [MemoryReceived]), 'dd.MM.yyy HH:mm:ss') AS [MemoryReceived],
	DATEDIFF(second, [MemoryReceived], SYSUTCDATETIME()) AS [MemoryReceived_sAgo]
FROM [diagnostics].[Shturman_Instance] 
ORDER BY [ComputerName] ASC" ;

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$ServersList = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersList[$row["Guid"]] = $row;
}
//echo $InstanceGuid;
//echo "<pre>"; var_dump($ServersList); echo "</pre>";
//echo "<pre>$SQL</pre>";

$SQL = "
/****** Service's Errors  ******/
SELECT TOP 1000 
	--[Instance_Guid],
	--[Service_Name],
	[Measure_Name],
	--[mm],
	--[dd],
	--[hh],
	--[nn],
	[Measure_Value],
	--[Received]
	FORMAT(DATEADD(HOUR, +3, [Received]), 'dd.MM.yyy HH:mm:ss') AS [ReceivedF]
FROM [diagnostics].[Service_Measure_History]
WHERE 
	[Instance_Guid] = '%%GUID%%'
	AND [Service_Name] = '%%SERVICE%%'
	%%MEASURE%%
ORDER BY
	[Received] DESC,
	[Measure_Name] ASC
	
	" ;

$SQL = ($InstanceGuid) ? str_replace('%%GUID%%', $InstanceGuid, $SQL) : $SQL ;
$SQL = ($ServiceName) ? str_replace('%%SERVICE%%', $ServiceName, $SQL) : $SQL;
if ( $Measure == "Memory" )
{
	$SQL = ($ServiceName) ? str_replace('%%MEASURE%%', "AND [Measure_Name] IN ('MemoryPrivateBytes', 'MemoryWorkingSet')", $SQL) : $SQL;
}
elseif ( $Measure == "Queue" )
{
	$SQL = ($ServiceName) ? str_replace('%%MEASURE%%', "AND [Measure_Name] IN ('DownQueueFileSizeByte', 'UpQueueFileSizeByte')", $SQL) : $SQL;
}
elseif ( $Measure == "Frames" )
{
	$SQL = ($ServiceName) ? str_replace('%%MEASURE%%', "AND [Measure_Name] IN ('FrameCount')", $SQL) : $SQL;
}
elseif ( $Measure == "Frames_RAW" )
{
	$SQL = ($ServiceName) ? str_replace('%%MEASURE%%', "AND [Measure_Name] IN ('RawFrameCount')", $SQL) : $SQL;
}
elseif ( $Measure == "Messages" )
{
	$SQL = ($ServiceName) ? str_replace('%%MEASURE%%', "AND [Measure_Name] IN ('MessageCount')", $SQL) : $SQL;
}
elseif ( $Measure == "Threads" )
{
	$SQL = ($ServiceName) ? str_replace('%%MEASURE%%', "AND [Measure_Name] IN ('ThreadCount')", $SQL) : $SQL;
}
else 
{
	$SQL = ($ServiceName) ? str_replace('%%MEASURE%%', "", $SQL) : $SQL;
}

//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$ServersList = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}

//echo "<pre>"; var_dump($data); echo "</pre>";


sqlsrv_close($conn) ;



//$currentdate = date("Y-m-d", time());
$currentdate = date("d.m.Y", time());
$currenttime = date("H:i:s", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'serversstate3_hist_measure.tpl')
);
//echo $ServersList[$InstanceGuid]["ComputerName"];

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTTIME' => $currenttime,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	//'COLUMN_COUNT' => @$ColumnCount,
	'SERVER' => @$ServersList[$InstanceGuid]["ComputerName"],
	'CLIENT' => iconv("Windows-1251", "UTF-8", $CONFIG_SHTURMAN["Client_Name"]),
	'CLIENT_SRV' => $Srv,
	'SERVICE' => $ServiceName,

	));

//foreach ( $ServersList AS $Srv )
$i = 0;
$Name = Array();
while ( @$data[$i] )
{
	//$Srv_Guid = $Srv["Guid"];
	//$Srv_IpAddress = substr($Srv_Ips, 0, strpos($Srv_Ips, "169.")-1);
	//$Srv_MemoryTotalBytesStr = format_bytes($Srv_MemoryTotalBytes);
	//$Srv_MemoryReceived_tAgo = sec2hours($Srv["MemoryReceived_sAgo"]);

	if ( !in_array( $data[$i]["Measure_Name"], $Name) ) { $Name[] = $data[$i]["Measure_Name"]; }
	
	//$Name = $data[$i]["Measure_Name"];
	$Value1 = $data[$i]["Measure_Value"];
	$Value2 = "";
	$Value3 = "";
	$date = $data[$i]["ReceivedF"];
	if (@$data[$i+1]["ReceivedF"] == $date )
	{
		$i++;
		if ( !in_array( $data[$i]["Measure_Name"], $Name) ) { $Name[] = $data[$i]["Measure_Name"]; }
		$Value2 = $data[$i]["Measure_Value"];
	}
	if (@$data[$i+1]["ReceivedF"] == $date )
	{
		$i++;
		if ( !in_array( $data[$i]["Measure_Name"], $Name) ) { $Name[] = $data[$i]["Measure_Name"]; }
		$Value3 = $data[$i]["Measure_Value"];
	}
	
	$template->assign_block_vars('row', array(
		'DATE' => $date,
		//'NAME' => $Name,
		'VALUE1' => $Value1,
		'VALUE2' => $Value2,
		'VALUE3' => $Value3,
	));
	$i++;
}
foreach ( $Name AS $row )
{
	$template->assign_block_vars('header', array(
		'NAME' => $row,
	));
}

$template->assign_block_vars('legend', array());

$template->pparse('body');
?>