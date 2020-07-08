<?php
/***************************************************************************
 *                                serverstarte3.php
 *                            -------------------
 *   begin                : Jun 13, 2018
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: blockdetails.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
 *
 *
 ***************************************************************************/
$ver["serverstarte3"] = "1.0.4"; // Version of script
/***************************************************************************
 *
1.0.3
	- костыль для ликвидации дублирующихся серверов
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
$ViewType = ( strtoupper(@$_GET["type"]) == "ERRORS" ) ? "ERRORS" : "ALL";  // Show only errors
//$ViewType = ( $ViewType ) ? $ViewType : "ALL";  // Show only errors
$Srv = @$_GET["srv"];
$cSrvID = @$_GET["srv"];

$Warnings = false;
$Errors = array(false,false,false,false,false,false,false,false,false,false);

// TODO костыль для метро. прдумать общие правила (конфиг вероятно)
$Srv_FrendlyNames = array("SRV-SHTURMAN" => "Root4/Root4H4","SRV-SHTURMAN-C" => "Anal","SRV-SHTURMAN-G1" => "sRoot","SRV-SHTURMAN-G3" => "Root3"); 

//print_r($_GET);

$rnd = rand ( 0 , 1000000000 );

/*
// Лимиты
$Limit["LastErr"] = 60*60*24*1; // Ошибки старше этого времени - не показывать
//$Limit["LastErr"] = 60*60*24*7*5; // Ошибки старше этого времени - не показывать
$Limit["Drive_C"] = 1024*1024*1024*15; // Места на диске C
//$Limit["Drive_C"] = 1024*1024*1024*1500; // Места на диске C
$Limit["Drive_D"] = 1024*1024*1024*250; // Места на диске D
//$Limit["Drive_D"] = 1024*1024*1024*25000; // Места на диске D
$Limit["Queue"] = 200000; // Размер очереди
//$Limit["Queue"] = 1; // Размер очереди
$Limit["Refresh"] = 60*5; // Обновление информации (секунд)
////$Limit["Refresh"] = 50; // Обновление информации (секунд)
$Limit["LogSize"] = 1024*1024*1024*12; // Лимит размера лог файла
////$Limit["LogSize"] = 1024; // Лимит размера лог файла
$Limit["LogTime"] = 60*2; // Послденяя запись в лог ен позднее с назад
////$Limit["LogTime"] = 40; // Послденяя запись в лог ен позднее с назад
$Limit["MemFree"] = 1024*1024*1024*1; // Свободной памяти
//$Limit["MemFree"] = 1024*1024*1024*1500; // Свободной памяти
$Limit["MemPVT"] = 1024*1024*500; // Памть PVT
//$Limit["MemPVT"] = 1024*1024*30; // Памть PVT
$Limit["MemWKS"] = 1024*1024*500; // память WKS
//$Limit["MemWKS"] = 1024*1024*50; // память WKS
$Limit["Thread"] = 200; // Threads
//$Limit["Thread"] = 15; // Threads
$Limit["Msg"] = 500; // Messages
//$Limit["Msg"] = 5; // Messages
$Limit["Frames"] = 500; // Frames
//$Limit["Frames"] = 0; // Frames
*/

//var_dump($CONFIG_SHTURMAN);

$sql_server = "Diag";
$db = "DiagSrv";
$conn = MSSQLconnect( $sql_server, $db );

function Get_Limits()
{
	global $conn;
	// Limits
	$SQL = "
/****** Limits  ******/
SELECT
	[Instance_Guid],
	[Service_Name],
	--[Service_Guid],
	[Measure_Name],
	[Limit],
	[Divider],
	[Until],
	[Limit_Description],
	[Divider_Description],
	[Multiplier],
	[Multiplier_Description]
FROM [diagnostics].[Limits]
	" ;

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$Limit[$row["Measure_Name"]][$row["Service_Name"]][$row["Instance_Guid"]]["Limit"] = $row["Limit"];
		$Limit[$row["Measure_Name"]][$row["Service_Name"]][$row["Instance_Guid"]]["Divider"] = $row["Divider"];
		$Limit[$row["Measure_Name"]][$row["Service_Name"]][$row["Instance_Guid"]]["Until"] = $row["Until"];
		$Limit[$row["Measure_Name"]][$row["Service_Name"]][$row["Instance_Guid"]]["Multiplier"] = $row["Multiplier"];
	}
	//$ServersCount = count($Measure_Definition);
	//echo "<pre>"; var_dump($Limit); echo "</pre>";
	return @$Limit;
}
$Limit =  Get_Limits();

function compare_ver($ver1, $ver2)
{
		$verCurr = explode(".", $ver1 );
		$verNew = explode(".", $ver2 );
		
		$verMax = $ver1;
		/*
		if ( $verCurr[0] < $verNew[0] or $verCurr[1] < $verNew[1] or $verCurr[2] < $verNew[2] or $verCurr[3] < $verNew[3] )
		{
			$dataX["srvcLastVersion"][$row["Service_Name"]] = $row["FileVersion"];
		}
		*/
		
		if ( $verCurr[0] == $verNew[0] )
		{
			if ( @$verCurr[1] == @$verNew[1] )
			{
			
				if ( @$verCurr[2] == @$verNew[2] )
				{
					
					if ( @$verCurr[3] < @$verNew[3] )	
					{
						$verMax = $ver2;
					}
					
				}
				elseif ( $verCurr[2] < $verNew[2] )
				{
					$verMax = $ver2;
				}
			}
			elseif ( $verCurr[1] < $verNew[1] )
			{
				$verMax = $ver2;
			}
		}
		elseif ( $verCurr[0] < $verNew[0] )
		{
			$verMax = $ver2;
		}
		return $verMax;
}

function get_VersionControlServices()
{
	// массив сервисов (по серверам, у которых не надо проверять версию на актуальность
	global $CONFIG_SHTURMAN;
	//var_dump($CONFIG_SHTURMAN);
	//$CONFIG_SHTURMAN["IgnoreVersionControl_Services"];

	//$x = Limit_check($Metric, $value, $Service, $Instance)

	check_config_property("shturman", "IgnoreVersionControl_Services", "", "1", "Игнорировать устарвшие версии сервисов (Hosrname:Srvc1:Srvc2;Host2:Srvc1:Srvc2)");
	
	$servers = explode(";", $CONFIG_SHTURMAN["IgnoreVersionControl_Services"]);
	# Hostname1:Service1:Service2;Hostname2:Service1:Service2;
	# example [ST-SHTURMAN3TST:ShturmanData4:ShturmanHub4:ShturmanLogic4:ShturmanMath4array]
	
	foreach ($servers as $srv)
	{
		//echo $srv;
		$services = explode(":", $srv);
		//		$ret[]
		foreach ($services as $srvc)
		{
			if ( $services[0] != $srvc )
			{
				$ret[$services[0]][] = $srvc;
			}
		}
	}
	return @$ret;
}
$VersionControl = get_VersionControlServices();
//var_dump($VersionControl);

//function get_identical_instances
//	check_config_property("shturman", "IgnoreVersionControl_Services", "", "1", "Игнорировать устарвшие версии сервисов (Hosrname:Srvc1:Srvc2;Host2:Srvc1:Srvc2)");


//echo "4.21.0.0 3.21.0.0 - " . compare_ver("4.21.0.0", "3.21.0.0") . "<br>";
//echo "3.21.0.0 3.21.0.0 - " . compare_ver("3.21.0.0", "3.21.0.0") . "<br>";
//echo "4.50.50.50 3.0.0.0 - " . compare_ver("4.50.50.50", "3.0.0.0") . "<br>";
//echo "4 - " . compare_ver("4.50.50.50", "3.0.0.0") . "<br>";
//echo "5 - " . compare_ver("4.50.50.50", "3.0.0.0") . "<br>";
//echo "6 - " . compare_ver("4.00.200.200", "4.01.10.100") . "<br>";


$SQL = "SELECT
	[Measure_Name],
	[Measure_Type],
	[Description],
	[Sernum],
	[Granularity_Percent]
FROM [diagnostics].[Service_Measure_Definition]" ;

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$ServersList = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$Measure_Definition[$row["Measure_Name"]] = $row;
}
//$ServersCount = count($Measure_Definition);


$SQL = "SELECT
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
--WHERE [Guid] = '1B6B02F5-7C66-4856-954A-4DC1CDB489C7'
ORDER BY [ComputerName] ASC" ;

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$ServersList = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersList[$row["Guid"]] = $row;
	$ServerInstances[$row["ComputerName"]][] = $row["Guid"];
	$data[$row["Guid"]] = $row;
}
//$ServersCount = count($ServersList);
//$ColumnCount = ( $ViewType == "ERRORS" ) ? "2": $ServersCount+1;
//echo "<pre>"; var_dump($ServersList); echo "</pre>";
//echo "<pre>"; var_dump($ServerInstances); echo "</pre>";

/*     Disk Drive state and spaces    */
$SQL = "-- Disk Drive state and spaces 
SELECT
	[Instance_Guid] AS [Instance_Guid],
	[Letter],
	[TotalSpaceBytes],
	[TotalFreeBytes],
	--[Received],
	FORMAT(DATEADD(HOUR, +3, [Received]), 'dd.MM.yyy HH:mm:ss') AS [Received],
	DATEDIFF(second, [Received], SYSUTCDATETIME()) AS [Received_sAgo],
	--[Modified],
	FORMAT(DATEADD(HOUR, +3, [Modified]), 'dd.MM.yyy HH:mm:ss') AS [Modified],
	DATEDIFF(second, [Modified], SYSUTCDATETIME()) AS [Modified_sAgo]
FROM [diagnostics].[Instance_Drive]
--WHERE [Instance_Guid] = '1B6B02F5-7C66-4856-954A-4DC1CDB489C7'
ORDER BY [Instance_Guid] ASC, [Letter] ASC";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersDisks[$row["Instance_Guid"]][] = $row;
	$data[$row["Instance_Guid"]]["HDD"][$row["Letter"]]["Letter"] = $row["Letter"];
	$data[$row["Instance_Guid"]]["HDD"][$row["Letter"]]["TotalSpaceBytes"] = $row["TotalSpaceBytes"];
	$data[$row["Instance_Guid"]]["HDD"][$row["Letter"]]["TotalFreeBytes"] = $row["TotalFreeBytes"];
	$data[$row["Instance_Guid"]]["HDD"][$row["Letter"]]["Received"] = $row["Received"];
	$data[$row["Instance_Guid"]]["HDD"][$row["Letter"]]["Received_sAgo"] = $row["Received_sAgo"];
	$data[$row["Instance_Guid"]]["HDD"][$row["Letter"]]["Modified"] = $row["Modified"];
	$data[$row["Instance_Guid"]]["HDD"][$row["Letter"]]["Modified_sAgo"] = $row["Modified_sAgo"];
}
//echo "<pre>"; var_dump($ServersDisks); echo "</pre>";

$SQL = "-- Services Sate
SELECT
	[Instance_Guid],
	[Service_Name],
	[Display_Name],
	--[Created],
	FORMAT(DATEADD(HOUR, +3, [Created]), 'dd.MM.yyy HH:mm:ss') AS [Created],
	DATEDIFF(second, [Created], SYSUTCDATETIME()) AS [Created_sAgo],
	--[Modified],
	FORMAT(DATEADD(HOUR, +3, [Modified]), 'dd.MM.yyy HH:mm:ss') AS [Modified],
	DATEDIFF(second, [Modified], SYSUTCDATETIME()) AS [Modified_sAgo],
	[ServiceState],
	[StartType],
	[Platform],
	[BinaryPathName],
	[FileVersion],
	[FileModified],
	FORMAT([FileModified], 'dd.MM.yyy HH:mm:ss') AS [FileModified],
	DATEDIFF(HOUR, [FileModified], SYSDATETIME()) AS [FileModified_hAgo],
	[Alias],
	[LogFileName],
	[QueueFileName],
	[ErrorFileName],
	[LogFileSize],
	[QueueFileSize],
	[ErrorFileSize],
	[Uninstalled],
	[RootHub],
	--[LastError],
	FORMAT(DATEADD(HOUR, +3, [LastError]), 'dd.MM.yyy HH:mm:ss') AS [LastError],
	DATEDIFF(second, [LastError], SYSUTCDATETIME()) AS [LastError_sAgo],
	[QueueFileModified],
	FORMAT(DATEADD(HOUR, +3, [QueueFileModified]), 'dd.MM.yyy HH:mm:ss') AS [QueueFileModified],
	DATEDIFF(second, [QueueFileModified], SYSUTCDATETIME()) AS [QueueFileModified_sAgo],
	[FrameCount] AS [FrameCnt],
	[RowFrameCount]
FROM [diagnostics].[Shturman_Service]
WHERE 1=1
	AND Uninstalled = 0
	--AND [Instance_Guid] = '1B6B02F5-7C66-4856-954A-4DC1CDB489C7'
ORDER BY 
	[Instance_Guid], 
	CASE 
		WHEN [StartType] = 'Auto' THEN 1
		WHEN [StartType] = 'Manual' THEN 1
		ELSE 2
		END ASC,
	[Service_Name] ASC
	";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$Services[$row["Instance_Guid"]][] = $row;
	$data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]] = $row;
	
//	if ( $row["Service_Name"] == "ShturmanDiagnostics4" )
//	{
	//echo @$dataX["srvcLastVersion"][$row["Service_Name"]] . "--" . $row["FileVersion"] . " ------ ";
	
	
	if ( @$dataX["srvcLastVersion"][$row["Service_Name"]]) 
	{
		$verCurr = @$dataX["srvcLastVersion"][$row["Service_Name"]];
		$verNew = $row["FileVersion"];
		
		$dataX["srvcLastVersion"][$row["Service_Name"]] = compare_ver($verCurr, $verNew);
		
		//$verCurr = explode(".", @$dataX["srvcLastVersion"][$row["Service_Name"]] );
		//$verNew = explode(".", $row["FileVersion"] );
		
		/*
		if ( $verCurr[0] < $verNew[0] or $verCurr[1] < $verNew[1] or $verCurr[2] < $verNew[2] or $verCurr[3] < $verNew[3] )
		{
			$dataX["srvcLastVersion"][$row["Service_Name"]] = $row["FileVersion"];
		}
		// */
		/*
		if ( $verCurr[0] == $verNew[0] )
		{
			if ( $verCurr[1] == $verNew[1] )
			{
			
				if ( $verCurr[2] == $verNew[2] )
				{
					
					if ( $verCurr[3] < $verNew[3] )	
					{
						$dataX["srvcLastVersion"][$row["Service_Name"]] = $row["FileVersion"];
					}
					
				}
				elseif ( $verCurr[2] < $verNew[2] )
				{
					$dataX["srvcLastVersion"][$row["Service_Name"]] = $row["FileVersion"];
				}
			}
			elseif ( $verCurr[1] < $verNew[1] )
			{
				$dataX["srvcLastVersion"][$row["Service_Name"]] = $row["FileVersion"];
			}
		}
		elseif ( $verCurr[0] < $verNew[0] )
		{
			$dataX["srvcLastVersion"][$row["Service_Name"]] = $row["FileVersion"];
		}
		*/
	}
	else 
	{
		$dataX["srvcLastVersion"][$row["Service_Name"]] = $row["FileVersion"];
	}
	
	
	/*if ( @$dataX["srvcLastVersion"][$row["Service_Name"]] < $row["FileVersion"] or !@$data["srvcLastVersion"][$row["Service_Name"]] )
	{
		echo "will change [" . @$dataX["srvcLastVersion"][$row["Service_Name"]] . "] to [" . $row["FileVersion"] . "]  ------ ";
		
		$dataX["srvcLastVersion"][$row["Service_Name"]] = $row["FileVersion"];
		echo "New is [" . $dataX["srvcLastVersion"][$row["Service_Name"]] . "]";
	}
	*/
	//echo "<br>";
	//$data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]]["LastVersion"] = 
	//		( @$data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]]["LastVersion"] < $row["FileVersion"] ) ?
			//$row["FileVersion"] : 
			//$data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]]["LastVersion"] < $row["FileVersion"];
	//		$row["FileVersion"]
	//	}
	//echo $data[$row["Instance_Guid"]]["ComputerName"] . " - " . $row["Service_Name"] . " - " . $data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]]["LastVersion"] . "<br>";

}
//echo "<pre>"; var_dump($Services); echo "</pre>";
//echo "<pre>"; var_dump($dataX); echo "</pre>";
//exit;

$SQL = "
SELECT
	[sm].[Instance_Guid],
	[sm].[Service_Name],
	[sm].[Measure_Name],
	[sm].[Measure_Value],
	--[d].[Measure_Type],
	--[d].[Description],
	--[d].[Sernum],
	--[d].[Granularity_Percent],
	--[sm].[Received],
	FORMAT(DATEADD(HOUR, +3, [sm].[Received]), 'dd.MM.yyy HH:mm:ss') AS [Received],
	DATEDIFF(second, [sm].[Received], SYSUTCDATETIME()) AS [Received_sAgo],
	--[sm].[Modified]
	FORMAT(DATEADD(HOUR, +3, [sm].[Modified]), 'dd.MM.yyy HH:mm:ss') AS [Modified],
	DATEDIFF(second, [sm].[Modified], SYSUTCDATETIME()) AS [Modified_sAgo]
FROM [diagnostics].[Service_Measure] AS [sm]
INNER JOIN [diagnostics].[Shturman_Service] AS [ss] ON ([ss].[Service_Name] = [sm].[Service_Name] AND [ss].[Instance_Guid] = [sm].[Instance_Guid] )
WHERE 1=1
	--AND [sm].[Instance_Guid] = '1B6B02F5-7C66-4856-954A-4DC1CDB489C7'
	AND [ss].[Uninstalled] = 0
	
--INNER JOIN [diagnostics].[Service_Measure_Definition] AS [d] ON [d].[Measure_Name] = [sm].[Measure_Name]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	//$Services[$row["Instance_Guid"]][] = $row;
	$data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]][$row["Measure_Name"]]["Measure_Name"] = $row["Measure_Name"];
	$data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]][$row["Measure_Name"]]["Measure_Value"] = $row["Measure_Value"];
	$data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]][$row["Measure_Name"]]["Received"] = $row["Received"];
	$data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]][$row["Measure_Name"]]["Received_sAgo"] = $row["Received_sAgo"];
	$data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]][$row["Measure_Name"]]["Modified"] = $row["Modified"];
	$data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]][$row["Measure_Name"]]["Modified_sAgo"] = $row["Modified_sAgo"];

}
//echo "<pre>"; var_dump($data); echo "</pre>";

$SQL = "/****** Service's Cliens  ******/
SELECT
	[Instance_Guid],
	[HubServiceName] AS [Service_Name],
	[Alias],
	[Role],
	[UseQueue],
	[QueueFileName],
	[QueueFileSize],
	--[Modified],
	FORMAT(DATEADD(HOUR, +3, [Modified]), 'dd.MM.yyy HH:mm:ss') AS [Modified],
	DATEDIFF(second, [Modified], SYSUTCDATETIME()) AS [Modified_sAgo],
	--[QueueFileModified]
	FORMAT(DATEADD(HOUR, +3, [QueueFileModified]), 'dd.MM.yyy HH:mm:ss') AS [QueueFileModified],
	DATEDIFF(second, [QueueFileModified], SYSUTCDATETIME()) AS [QueueFileModified_sAgo]
FROM [diagnostics].[Hub_Client]
WHERE [Instance_Guid] = '1B6B02F5-7C66-4856-954A-4DC1CDB489C7'
ORDER BY
	[Instance_Guid],
	[HubServiceName],
	[Alias]";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	//$Services[$row["Instance_Guid"]][] = $row;
	$data[$row["Instance_Guid"]]["Services"][$row["Service_Name"]]["Clients"][$row["Alias"]] = array(
		"Alias" => $row["Alias"],
		"Role" => $row["Role"],
		"UseQueue" => $row["UseQueue"],
		"QueueFileName" => $row["QueueFileName"],
		"QueueFileSize" => $row["QueueFileSize"],
		"Modified" => $row["Modified"],
		"Modified_sAgo" => $row["Modified_sAgo"],
		"QueueFileModified" => $row["QueueFileModified"],
		"QueueFileModified_sAgo" => $row["QueueFileModified_sAgo"],
	);

}
//echo "<pre>"; var_dump($data); echo "</pre>";

/*
$SQL = $SQL_QUERY["ServerDiagInfo"];

//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$data = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}

*/
//echo "<pre>"; var_dump($data); echo "</pre>";



//$currentdate = date("Y-m-d", time());
$currentdate = date("d.m.Y", time());
$currenttime = date("H:i:s", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));

function Limit_value( $Current )
{
	$Limit = $Current["Limit"];
	$Divider = $Current["Divider"];
	$Until = $Current["Until"];
	$Multiplier = $Current["Multiplier"];

	if ( $Current["Divider"] )
	{
		return $Current["Limit"] / $Current["Divider"];
	}
	elseif ($Current["Multiplier"] )
	{
		return $Current["Limit"] * $Current["Multiplier"];
	}
	else
	{
		return $Current["Limit"];
	}
	
	return 1;
}

function Limit_check($Metric, $value, $Service, $Instance)
{
	global $Limit;
	
	$NullGuid = "00000000-0000-0000-0000-000000000000";
	$AllServices = "All";
	$Service = ( $Service ) ? $Service : $AllServices;
	$Instance = ( $Instance and strlen($Instance) == 36 ) ? $Instance : $NullGuid;
	/*
	["UpQueueFileSizeByte"]=>
	array(1)
		["00000000-0000-0000-0000-000000000000"]=>
		array(1)
			["00000000-0000-0000-0000-000000000000"]=>
			array(4)
				["Limit"]=>
				string(3) "200"
				["Divider"]=>
				NULL
				["Until"]=>
				NULL
				["Multiplier"]=>
				string(4) "1024"
	*/
	//if ( $Service == "ShturmanWatcher4" ) { echo $Service; }
	if ( @$Limit[$Metric][$Service][$Instance] )
	{
		$CurrentLimits = $Limit[$Metric][$Service][$Instance];
	}
	elseif ( @$Limit[$Metric][$Service][$NullGuid] )
	{
		$CurrentLimits = $Limit[$Metric][$Service][$NullGuid];
	}
	elseif ( @$Limit[$Metric][$AllServices][$Instance] )
	{
		$CurrentLimits = $Limit[$Metric][$AllServices][$Instance];
	}
	else 
	{
		if ( !@$Limit[$Metric][$AllServices][$NullGuid] )
		{
			$SQL = "
INSERT INTO [diagnostics].[Limits]
	([Instance_Guid], [Service_name], [Measure_Name])
VALUES
	('00000000-0000-0000-0000-000000000000', 'All', '$Metric')
					";
			MSSQLsiletnQuery($SQL);
			
			$Limit = Get_Limits();
			
			echo "<pre>DBG: Adder new Metric [$Metric] to Limits Table</pre>";
			
		}
		$CurrentLimits = @$Limit[$Metric][$AllServices][$NullGuid];
	}

	//echo "<pre>"; var_dump($Metric); echo "</pre>";
	//echo "<pre>"; var_dump($CurrentLimits); echo "</pre>";

	$LimitVal = Limit_value( $CurrentLimits );
	$LimitUntil = ( $CurrentLimits["Until"] ) ? TRUE : FALSE;

	//echo "<pre>value "; var_dump($value); echo "</pre>";
	//echo "<pre>LimitVal "; var_dump($LimitVal); echo "</pre>";
	//echo "<pre>LimitUntil "; var_dump($LimitUntil); echo "</pre>";
	
	
	if ( $LimitUntil == FALSE )
	{
		if ( $value > $LimitVal )
		{
			//echo "TRUE";
			return TRUE;
		}
	}
	else
	{
		if ( $value < $LimitVal )
		{
			//echo "TRUE";
			return TRUE;
		}
	}
	//echo "FALSE";
		
	return FALSE;
}


//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'serversstate3.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTTIME' => $currenttime,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'COLUMN_COUNT' => @$ColumnCount,
	'CLIENT' => iconv("Windows-1251", "UTF-8", $CONFIG_SHTURMAN["Client_Name"]),
	'CLIENT_SRV' => $cSrvID,
	'SERVER_NAME' => $cSrvID,

	));

//foreach ( $ServersList AS $Srv )
foreach ( $data AS $Srv )
{
	//if ( !@$Srv["Guid"]) { echo "<pre>"; var_dump($Srv); echo "</pre>"; }
	
	$Srv_Guid = $Srv["Guid"];
	$Srv_ComputerName = $Srv["ComputerName"];

	$Srv_ComputerFrendlyName = (@$Srv_FrendlyNames[$Srv_ComputerName]) ? $Srv_FrendlyNames[$Srv_ComputerName] : $Srv_ComputerName;

	$Srv_Ips = $Srv["IpAddress"];
	//$Srv_Ips = "10.200.24.85;169.254.107.96;172.16.30.254";
	$Srv_IpsArr = explode( ";", $Srv_Ips );
	$IP_Useless = Array();
	$IP_Local = Array();
	$IP_Special = Array();
	foreach ( $Srv_IpsArr as $ip )
	{
		if ( substr($ip,0,8) == "169.254." )
		{
			$IP_Useless[] = $ip;
		}
		elseif ( substr($ip,0,10) == "10.200.24." )
		{
			$IP_Local[] = $ip;
			$IP_Special[] = str_replace("10.200.24.", "192.168.51.", $ip);
		}
		else
		{
			$IP_Local[] = $ip;
		}
	}
	//echo "<pre>";var_dump($IP_Useless);var_dump($IP_Special);var_dump($IP_Local);echo "</pre>";

	$Srv_IP_Special = ( $IP_Special ) ? "Special: " . implode("; ", $IP_Special) . "; "  : "" ;
	$Srv_IP_Address = implode("; ", $IP_Local);
	$Srv_IP_Useless = ( $IP_Useless ) ? "; Netcard not Used: " . implode("; ", $IP_Useless) : "" ;
	//$Srv_IpAddress = substr($Srv_Ips, 0, strpos($Srv_Ips, "169.")-1);
	//$Srv_Ip_Metro = str_replace("10.200.24.", "192.168.51.", $Srv_IpAddress);	// TODO костыль для метро. подумать как сделать общие правила
	//$Srv_Ip_Useless = substr($Srv_Ips, strpos($Srv_Ips, "169."));;
	
	$Srv_Created = $Srv["Created"];
	$Srv_MemoryTotalBytes = $Srv["MemoryTotalBytes"];
	$Srv_MemoryTotalBytesStr = format_bytes($Srv_MemoryTotalBytes);
	$Srv_MemoryAvailBytes = $Srv["MemoryAvailBytes"];
	$Srv_MemoryAvailBytesStr = format_bytes($Srv_MemoryAvailBytes);
	$Srv_MemoryUsedStr = format_bytes($Srv_MemoryTotalBytes-$Srv_MemoryAvailBytes);
	

	$Srv_MemoryReceived = $Srv["MemoryReceived"];
	$Srv_MemoryReceived_sAgo = $Srv["MemoryReceived_sAgo"];
	$Srv_MemoryReceived_tAgo = sec2hours($Srv["MemoryReceived_sAgo"]);
	
	//$Style_Srv_MemoryAvailBytes = ( $Srv_MemoryTotalBytes < Limit_check("MemFree", ) $Limit["MemFree"] ) ? $style["bg-ll-red"] : "";
	$Style_Srv_MemoryAvailBytes = ( Limit_check("MemoryFree", $Srv_MemoryAvailBytes, "", $Srv_Guid) ) ? $style["bg-ll-red"] : "";

	// Костыль для скипанья дуликатов серверов 
	// вфыбирается тот у которого время последнего одновления меньше. 
	if ( count($ServerInstances[$Srv_ComputerName]) > 1 )
	{
		$actualSrv = "";
		$minTimeAgo = 99999999999999;
		foreach ( $ServerInstances[$Srv_ComputerName] as $item )
		{
			$g = $item;
			$time = $data[$item]["MemoryReceived_sAgo"];
			if ( $time < $minTimeAgo )
			{
				$minTimeAgo = $time;
				$actualSrv = $item;
			}
			//echo $item . " $time "  . "<br>";
			//if 
			
			
		}
		if ( $actualSrv != $Srv_Guid )
		{
			//echo "$actualSrv skipp";
			continue;
		}
	}

	
	$template->assign_block_vars('server', array(
		'SRV_GUID' => $Srv_Guid,
		'COMPUTER_NAME' => $Srv_ComputerName,
		//'SERVER_NAME' => $Srv_ComputerFrendlyName,
		'NAME' => $Srv_ComputerFrendlyName,
		'IP' => $Srv_IP_Address,
		//'IP_METRO' => $Srv_Ip_Metro,
		'IP_SPECIAL' => $Srv_IP_Special,
		'IP_USELESS' => $Srv_IP_Useless,
		'CREATED' => $Srv_Created,
		'MEM_TOTAL' => $Srv_MemoryTotalBytesStr,
		'MEM_FREE' => $Srv_MemoryAvailBytesStr,
		'MEM_USED' => $Srv_MemoryUsedStr,
		'MEM_UPDATED' => $Srv_MemoryReceived,
		'MEM_UPDATED_TAGO' => $Srv_MemoryReceived_tAgo,
		
		'S_MEM_FREE' => $Style_Srv_MemoryAvailBytes,
	));
	
	$Warnings = ( 1==0 ) ? true : $Warnings;
	if ( $Style_Srv_MemoryAvailBytes )
	{
		$Errors[2][] = array("Server" => $Srv_ComputerFrendlyName, "Service" =>  "---", "param" => "Свободно памяти", "val" => $Srv_MemoryAvailBytesStr);
	}

	//foreach ( $ServersDisks[$Srv_Guid] AS $HDD )
	if ( @$Srv["HDD"] )
	{
		foreach ( $Srv["HDD"] AS $HDD )
		{
			//$HDD_Instance_Guid = $HDD["Instance_Guid"];
			$HDD_Letter = $HDD["Letter"];
			$HDD_TotalSpaceBytes = $HDD["TotalSpaceBytes"];
			$HDD_FreeBytes = $HDD["TotalFreeBytes"];
			$HDD_FreeBytes_F = format_bytes($HDD_FreeBytes);
			$HDD_Received = $HDD["Received"];
			$HDD_Received_sAgo = $HDD["Received_sAgo"];
			$HDD_Modified = $HDD["Modified"];
			$HDD_Modified_sAgo = $HDD["Modified_sAgo"];
	
			//$style_Drive_Err = ( $HDD_FreeBytes < $Limit["Drive_$HDD_Letter"] ) ? $style["bg-ll-red"] : "";
			$style_Drive_Err = ( Limit_check("HDD_Drive_$HDD_Letter", $HDD_FreeBytes, "", $Srv_Guid) ) ? $style["bg-ll-red"] : "";
			
	
			//echo $Limit["Drive_$HDD_Letter"] . "  - $HDD_FreeBytes " . ( $HDD_FreeBytes < $Limit["Drive_$HDD_Letter"] ) . " $style_Drive_Err<br>";
			//echo $style_Drive_Err
	
			$template->assign_block_vars('server.hdd', array(
				//'I_GUID' => $HDD_Instance_Guid,
				'LETTER' => $HDD_Letter,
				'SPACE' => format_bytes($HDD_TotalSpaceBytes),
				'FREE' => $HDD_FreeBytes_F,
				'RECEIVED' => $HDD_Received,
				'RECEIVED_TAGO' => sec2hours($HDD_Received_sAgo),
				'MODIFIED' => $HDD_Modified,
				'MODIFIED_TAGO' => sec2hours($HDD_Modified_sAgo),
				
				'S_FREE' => $style_Drive_Err,
			));
	
			$Warnings = ( 1==0 ) ? true : $Warnings;
			if ( $style_Drive_Err )
			{
				$Errors[3][] = array("Server" => $Srv_ComputerFrendlyName, "Service" =>  "---", "param" => "Свободно на диске [$HDD_Letter]", "val" => $HDD_FreeBytes_F);
			}
		}
	}
	
	// Сервисы на инстансе
	//foreach ( $Services[$Srv_Guid] AS $srvc) 
	if ( @$Srv["Services"] )
	{
		foreach ( $Srv["Services"] AS $srvc) 
		{
			//echo "<pre>"; var_dump($srvc); echo "</pre>";
			//if ( !@$srvc["Instance_Guid"]) { echo "<pre>"; var_dump($srvc); echo "</pre>"; }
			
			$srvc_Instance_Guid = $srvc["Instance_Guid"];

			$srvc_Alias = $srvc["Alias"];
			$srvc_Service_Name = $srvc["Service_Name"];
			$srvc_Display_Name = iconv("Windows-1251", "UTF-8", $srvc["Display_Name"]);
			$srvc_ServiceState = $srvc["ServiceState"];
			$srvc_ServiceStateImg = ( strtoupper($srvc["ServiceState"]) == "RUNNING" ) ? '<img src="/pic/ico/green.png" width="16" height="16" />' : '<img src="/pic/ico/red.png" width="16" height="16" />';
			$srvc_Platform = $srvc["Platform"];
			$srvc_StartType = $srvc["StartType"];
			$srvc_Uninstalled = $srvc["Uninstalled"];
			$srvc_UninstalledStr = ( $srvc_Uninstalled ) ? "(Не установлен)" : "" ;
			$srvc_BinaryPathName= $srvc["BinaryPathName"];
			$srvc_FileVersion = $srvc["FileVersion"];
			$srvc_LastVersion = $dataX["srvcLastVersion"][$srvc_Service_Name];
			//$srvc_RequireUpgrade = ($srvc_FileVersion < $srvc_LastVersion AND strtoupper($srvc_ServiceState) == "RUNNING" ) ? TRUE : FALSE;
			$srvc_RequireUpgrade = (
									compare_ver($srvc_FileVersion, $srvc_LastVersion) != $srvc_FileVersion  // версия не максимальна
									AND strtoupper($srvc_ServiceState) == "RUNNING"							// сервис зщапущен
									AND !@in_array($srvc_Service_Name, $VersionControl[$Srv_ComputerName])	// Сервиса нет в списке игнорируемый по вершн контролю
									) ? TRUE : FALSE;
			//compare_ver($srvc_FileVersion, $srvc_LastVersion);
			//echo "$srvc_Service_Name - " . compare_ver($srvc_FileVersion, $srvc_LastVersion) . " - $srvc_LastVersion <br>";
			
			;
			
			
			$srvc_FileModified = $srvc["FileModified"];
			$srvc_FileModified_hAgo = $srvc["FileModified_hAgo"];

			/*
			echo "
				Srv_Guid:  $Srv_Guid<br/>
				Srv_ComputerFrendlyName: $Srv_ComputerFrendlyName<br/>
				srvc_Instance_Guid: $srvc_Instance_Guid <br/>
				srvc_Service_Name: $srvc_Service_Name<br/>";
			*/

			$srvc_Created = $srvc["Created"];
			$srvc_Created_sAgo = $srvc["Created_sAgo"];
			$srvc_Modified = $srvc["Modified"];
			$srvc_Modified_sAgo = $srvc["Modified_sAgo"];
			//$Style_srvc_date = ( $srvc_Modified_sAgo  > $Limit["Refresh"] ) ? $style["bg-ll-yellow"] : ""; 
			$Style_srvc_date = ( Limit_check("RefreshStat", $srvc_Modified_sAgo, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-yellow"] : ""; 

			$srvc_LogFileName = $srvc["LogFileName"];
			$srvc_LogFileSize = $srvc["LogFileSize"];

			$srvc_RootHub = $srvc["RootHub"];

			$srvc_ErrorFileName = $srvc["ErrorFileName"];
			$srvc_LastError = $srvc["LastError"];
			$srvc_LastError_sAgo = $srvc["LastError_sAgo"];
			$srvc_LastError_tAgo = sec2hours($srvc_LastError_sAgo);
			$srvc_ErrorFileSize = $srvc["ErrorFileSize"];


			// Очереди
			$srvc_QueueFileSize = $srvc["QueueFileSize"];
			$srvc_QueueFileSize_F = format_bytes($srvc_QueueFileSize);
			$srvc_QueueFileName = ( $srvc["QueueFileName"] ) ? $srvc["QueueFileName"] : "N/A";
			$srvc_QueueFileModified = $srvc["QueueFileModified"];
			$srvc_QueueFileModified_sAgo = $srvc["QueueFileModified_sAgo"];
			
			$srvc_m_UpQueueFileSizeByte = @$srvc["UpQueueFileSizeByte"]["Measure_Value"];
			$srvc_m_UpQueueFileSizeByte_Received = @$srvc["DownQueueFileSizeByte"]["Received"];
			$srvc_m_UpQueueFileSizeByte_Received_sAgo = @$srvc["DownQueueFileSizeByte"]["Received_sAgo"];
			$srvc_m_DownQueueFileSizeByte = @$srvc["DownQueueFileSizeByte"]["Measure_Value"];
			$srvc_m_DownQueueFileSizeByte_Received = @$srvc["DownQueueFileSizeByte"]["Received"];
			$srvc_m_DownQueueFileSizeByte_Received_sAgo = @$srvc["DownQueueFileSizeByte"]["Received_sAgo"];

			$srvc_Queue_title = ( $srvc["QueueFileName"] ) ? "Файл очереди: $srvc_QueueFileName, ". $srvc_QueueFileSize_F .", изменен: ".sec2hours($srvc_QueueFileModified_sAgo) ." назад ($srvc_QueueFileModified)" : "N/A";

			$Style_Queue_Wrn = "";
			$Style_Queue_Err = "";
			$srvc_Queue_Refresh= "";
			//if ( ($srvc_QueueFileSize > $Limit["Queue"]) or ($srvc_m_UpQueueFileSizeByte > $Limit["Queue"]) or ($srvc_m_DownQueueFileSizeByte > $Limit["Queue"]) )
			//if ( ($srvc_QueueFileSize > $Limit["QueueSize"]) or ($srvc_m_UpQueueFileSizeByte > $Limit["UpQueueFileSizeByte"]) or ($srvc_m_DownQueueFileSizeByte > $Limit["DownQueueFileSizeByte"]) )
			if ( Limit_check("QueueSize", $srvc_QueueFileSize, $srvc_Service_Name, $Srv_Guid) 
					or Limit_check("UpQueueFileSizeByte", $srvc_m_UpQueueFileSizeByte, $srvc_Service_Name, $Srv_Guid)
					or Limit_check("DownQueueFileSizeByte", $srvc_m_DownQueueFileSizeByte, $srvc_Service_Name, $Srv_Guid)
				)
			{
				$Style_Queue_Err = $style["bg-ll-red"];
			}
			//elseif ( ($srvc_QueueFileModified_sAgo > $Limit["Refresh"]) or ($srvc_m_UpQueueFileSizeByte_Received_sAgo > $Limit["Refresh"]) or ($srvc_m_DownQueueFileSizeByte_Received_sAgo > $Limit["Refresh"]) )
			elseif ( Limit_check("DownQueueFileSizeByte", $srvc_m_DownQueueFileSizeByte, $srvc_Service_Name, $Srv_Guid)
				or Limit_check("DownQueueFileSizeByte", $srvc_m_DownQueueFileSizeByte, $srvc_Service_Name, $Srv_Guid)
				or Limit_check("DownQueueFileSizeByte", $srvc_m_DownQueueFileSizeByte, $srvc_Service_Name, $Srv_Guid)
				)
			{
				$Style_Queue_Wrn = $style["bg-ll-yellow"];
				
				//$srvc_Queue_Refresh = ( $srvc_QueueFileModified_sAgo > $Limit["Refresh"] ) ? "Q: " . sec2hours($srvc_QueueFileModified_sAgo) . "; " : "";
				$srvc_Queue_Refresh = ( Limit_check("RefreshStat", $srvc_QueueFileModified_sAgo, $srvc_Service_Name, $Srv_Guid) ) ? "Q: " . sec2hours($srvc_QueueFileModified_sAgo) . "; " : "";
				
				//$srvc_Queue_Refresh .= ( $srvc_m_UpQueueFileSizeByte_Received_sAgo > $Limit["Refresh"] ) ? "<img src='/pic/ico/arrow_full_up_20x20.png' width='5' height='12' title='Up' /> " . sec2hours($srvc_m_UpQueueFileSizeByte_Received_sAgo) . "; " : "";
				$srvc_Queue_Refresh .= ( Limit_check("RefreshStat", $srvc_m_UpQueueFileSizeByte_Received_sAgo, $srvc_Service_Name, $Srv_Guid) ) ? "<img src='/pic/ico/arrow_full_up_20x20.png' width='5' height='12' title='Up' /> " . sec2hours($srvc_m_UpQueueFileSizeByte_Received_sAgo) . "; " : "";
				//$srvc_Queue_Refresh .= ( $srvc_m_DownQueueFileSizeByte_Received_sAgo > $Limit["Refresh"] ) ? "<img src='/pic/ico/arrow_full_down_20x20.png' width='5' height='12' title='Down' /> " . sec2hours($srvc_m_DownQueueFileSizeByte_Received_sAgo) : "";
				$srvc_Queue_Refresh .= ( Limit_check("RefreshStat", $srvc_m_DownQueueFileSizeByte_Received_sAgo, $srvc_Service_Name, $Srv_Guid) ) ? "<img src='/pic/ico/arrow_full_down_20x20.png' width='5' height='12' title='Down' /> " . sec2hours($srvc_m_DownQueueFileSizeByte_Received_sAgo) : "";
				$srvc_Queue_Refresh = ( $srvc_Queue_Refresh ) ? "<br/>$srvc_Queue_Refresh" : "";
			}
			
			if ( $srvc_m_UpQueueFileSizeByte != "" )
			{
				$srvc_m_UpQueueFileSizeByte_title = "To Service: ".format_bytes($srvc_m_UpQueueFileSizeByte).", обновлено: ".sec2hours($srvc_m_UpQueueFileSizeByte_Received_sAgo)." назад ($srvc_m_UpQueueFileSizeByte_Received)";
				$srvc_Queue_state = "<img src='/pic/ico/arrow_full_up_20x20.png' width='5' height='12' title='$srvc_m_UpQueueFileSizeByte_title' /> " . format_bytes($srvc_m_UpQueueFileSizeByte);
			}
			else
			{
				$srvc_m_UpQueueFileSizeByte_title = "";
				$srvc_Queue_state = "";
			}
			$srvc_Queue_state .= ( ( $srvc_m_UpQueueFileSizeByte != "" ) and ( $srvc_m_DownQueueFileSizeByte != "" ) ) ? " / " : "";
			if ( $srvc_m_DownQueueFileSizeByte != "" )
			{
				$srvc_m_DownQueueFileSizeByte_title = "From Service: ".format_bytes($srvc_m_DownQueueFileSizeByte).", обновлено: ".sec2hours($srvc_m_DownQueueFileSizeByte_Received_sAgo)." назад ($srvc_m_DownQueueFileSizeByte_Received)";
				$srvc_Queue_state .= "<img src='/pic/ico/arrow_full_down_20x20.png' width='5' height='12' title='$srvc_m_DownQueueFileSizeByte_title' /> " . format_bytes($srvc_m_DownQueueFileSizeByte);
			}
			else
			{
				$srvc_m_DownQueueFileSizeByte_title = "";
				$srvc_Queue_state .= "";
			}
			//$test = "<br>[$srvc_m_DownQueueFileSizeByte - $srvc_m_DownQueueFileSizeByte_title] - [$srvc_m_UpQueueFileSizeByte - $srvc_m_UpQueueFileSizeByte_title]";


			// Логи:
			$srvc_m_LogFileSizeByte = @$srvc["LogFileSizeByte"]["Measure_Value"] ;
			$srvc_m_LogFileSizeByte_date = @$srvc["LogFileSizeByte"]["Received"];
			$srvc_m_LogFileSizeByte_sAgo = @$srvc["LogFileSizeByte"]["Received_sAgo"];
			$srvc_m_LogFileSizeByte_TAgo = ($srvc_m_LogFileSizeByte_sAgo) ? sec2hours($srvc_m_LogFileSizeByte_sAgo)  : "N/A";

			$srvc_m_LogLastWritten = @$srvc["LogLastWritten"]["Measure_Value"];

			$srvc_m_LogLastWritten_Unix = strtotime($srvc_m_LogLastWritten);
			$srvc_m_LogLastWritten_date = ($srvc_m_LogLastWritten) ? date("d.m.Y H:i:s", $srvc_m_LogLastWritten_Unix) : "N/A";
			$srvc_m_LogLastWritten_sAgo = time() - $srvc_m_LogLastWritten_Unix;
			$srvc_m_LogLastWritten_tAgo = ($srvc_m_LogLastWritten) ? sec2hours($srvc_m_LogLastWritten_sAgo) : "N/A"; // TODO: корректно надо проверять, лога может не быть или он не содался
			//$srvc_m_LogLastWritten_tAgo = date("d.m.Y H:i:s", time()) . "===" . date("d.m.Y H:i:s", $srvc_m_LogLastWritten_Unix) . " ==-== " . date("d.m.Y H:i:s O");
			
			//$Style_Log_Wrn = ( $srvc_m_LogFileSizeByte > $Limit["LogSize"] ) ? $style["bg-ll-yellow"] : "";
			$Style_Log_Wrn = ( Limit_check("LogFileSizeByte", $srvc_m_LogFileSizeByte, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-yellow"] : "";

			//if ( $srvc_m_LogLastWritten_sAgo > $Limit["LogTime"] 

			//$test = "";
			if ( Limit_check("LogLastWritten", $srvc_m_LogLastWritten_sAgo, $srvc_Service_Name, $Srv_Guid) 
					and $srvc_ServiceState == "running" 
					and substr($srvc_FileVersion,0,1) == "4" 
				)
			{
				$Style_Log_Err = $style["bg-ll-red"];
				//$test = "[$srvc_m_LogLastWritten_sAgo] - [$srvc_Service_Name] - [$Srv_Guid]";
			}
			else
			{
				$Style_Log_Err = "";
				//$test ="";
			}
			
	/*							
			$Style_Log_Err = ( $srvc_m_LogLastWritten_sAgo > $Limit["LogTime"] 
								and $srvc_ServiceState == "running" 
								and substr($srvc_FileVersion,0,1) == "4" ) ? $style["bg-ll-red"] : "";
			$Style_Log_Err = ( $srvc_Service_Name == "ShturmanWatcher4" and $srvc_m_LogLastWritten_sAgo > ($Limit["LogTime"]*1) ) ? "" : $Style_Log_Err;
			*/
			
			//$test = "<br> $srvc_m_LogLastWritten - " . strtotime($srvc_m_LogLastWritten) . " - " . date("d.m.Y H:i:s", $srvc_m_LogLastWritten_Unix) . "--" . $srvc_m_LogLastWritten_sAgo;
			//$test = "<br> $Style_Log $srvc_m_LogFileSizeByte / $srvc_m_LogFileSizeByte_TAgo назад; ($srvc_m_LogFileSizeByte_date)";
			
			
			// Ресурсы
			$srvc_m_MemoryPrivateBytes = @$srvc["MemoryPrivateBytes"]["Measure_Value"];
			//$srvc_m_MemoryPrivateBytes_F = @$srvc["MemoryPrivateBytes"]["Measure_Value"];
			$srvc_m_MemoryPrivateBytes_date = @$srvc["MemoryPrivateBytes"]["Received"];
			$srvc_m_MemoryPrivateBytes_sAgo = @$srvc["MemoryPrivateBytes"]["Received_sAgo"];
			$srvc_m_MemoryPrivateBytes_tAgo = sec2hours($srvc_m_MemoryPrivateBytes_sAgo);
			$srvc_m_MemoryWorkingSet = @$srvc["MemoryWorkingSet"]["Measure_Value"];
			$srvc_m_MemoryWorkingSet_date = @$srvc["MemoryWorkingSet"]["Received"];
			$srvc_m_MemoryWorkingSet_sAgo = @$srvc["MemoryWorkingSet"]["Received_sAgo"];
			$srvc_m_MemoryWorkingSet_tAgo = sec2hours($srvc_m_MemoryWorkingSet_sAgo);
			$srvc_m_ThreadCount = @$srvc["ThreadCount"]["Measure_Value"];
			$srvc_m_ThreadCount_date = @$srvc["ThreadCount"]["Received"];
			$srvc_m_ThreadCount_sAgo = @$srvc["ThreadCount"]["Received_sAgo"];
			$srvc_m_ThreadCount_tAgo = sec2hours($srvc_m_ThreadCount_sAgo);
			$srvc_m_Mem = ( $srvc_m_MemoryPrivateBytes or $srvc_m_MemoryWorkingSet ) ? format_bytes($srvc_m_MemoryPrivateBytes + $srvc_m_MemoryWorkingSet) : "";
			//$srvc_m_Resources = ( $srvc_m_MemoryPrivateBytes or $srvc_m_MemoryWorkingSet ) ? 
								//format_bytes($srvc_m_MemoryPrivateBytes + $srvc_m_MemoryWorkingSet) : "";

			//$Style_srvc_Mem_Wrn = ( $srvc_m_MemoryPrivateBytes_sAgo > $Limit["Refresh"] or $srvc_m_MemoryWorkingSet_sAgo > $Limit["Refresh"] ) ? $style["bg-ll-yellow"] : "";
			$Style_srvc_Mem_Wrn = ( Limit_check("RefreshStat", $srvc_m_MemoryPrivateBytes_sAgo, $srvc_Service_Name, $Srv_Guid)
									or Limit_check("RefreshStat", $srvc_m_MemoryWorkingSet_sAgo, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-yellow"] : "";
			//$Style_srvc_Mem_Err = ( $srvc_m_MemoryPrivateBytes > $Limit["MemPVT"] ) ? $style["bg-ll-red"] : "";
			$Style_srvc_Mem_Err = ( Limit_check("MemoryPrivateBytes", $srvc_m_MemoryPrivateBytes, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-red"] : "";
			//$Style_srvc_Mem_Err = ( $srvc_m_MemoryWorkingSet > $Limit["MemWKS"] ) ? $style["bg-ll-red"] : $Style_srvc_Mem_Err;
			$Style_srvc_Mem_Err = ( Limit_check("MemoryWorkingSet", $srvc_m_MemoryWorkingSet, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-red"] : $Style_srvc_Mem_Err;
			//$Style_srvc_Threads_Wrn = ( $srvc_m_ThreadCount_sAgo > $Limit["Refresh"] ) ? $style["bg-ll-yellow"] : "";
			$Style_srvc_Threads_Wrn = ( Limit_check("RefreshStat", $srvc_m_ThreadCount_sAgo, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-yellow"] : "";
			//$Style_srvc_Threads_Err = ( $srvc_m_ThreadCount > $Limit["Thread"] ) ? $style["bg-ll-red"] : "";
			$Style_srvc_Threads_Err = ( Limit_check("ThreadCount", $srvc_m_ThreadCount, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-red"] : "";
			
			// Messages
			$srvc_m_MessageCount = @$srvc["MessageCount"]["Measure_Value"];
			$srvc_m_MessageCount_date = @$srvc["MessageCount"]["Received"];
			$srvc_m_MessageCount_sAgo = @$srvc["MessageCount"]["Received_sAgo"];
			$srvc_m_MessageCount_tAgo = sec2hours($srvc_m_MessageCount_sAgo);
			//$Style_srvc_Msg_Wrn = ( $srvc_m_MessageCount_sAgo > $Limit["Refresh"] ) ? $style["bg-ll-yellow"] : "";
			$Style_srvc_Msg_Wrn = ( Limit_check("RefreshStat", $srvc_m_MessageCount_sAgo, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-yellow"] : "";
			//$Style_srvc_Msg_Err = ( $srvc_m_MessageCount > $Limit["Msg"] ) ? $style["bg-ll-red"] : "";
			$Style_srvc_Msg_Err = ( Limit_check("MessageCount", $srvc_m_MessageCount, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-red"] : "";
			//$test = "sss".$srvc_m_MessageCount_sAgo;
			
			// Frames
			$srvc_m_FrameCount = @$srvc["FrameCount"]["Measure_Value"];
			$srvc_m_FrameCount_date = @$srvc["FrameCount"]["Received"];
			$srvc_m_FrameCount_sAgo = @$srvc["FrameCount"]["Received_sAgo"];
			$srvc_m_FrameCount_tAgo = sec2hours($srvc_m_FrameCount_sAgo);
			//$Style_srvc_Frames_Wrn = ( $srvc_m_FrameCount_sAgo > $Limit["Refresh"] ) ? $style["bg-ll-yellow"] : "";
			$Style_srvc_Frames_Wrn = ( Limit_check("RefreshStat", $srvc_m_FrameCount_sAgo, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-yellow"] : "";
			//$Style_srvc_Frames_Err = ( $srvc_m_FrameCount > $Limit["Frames"] ) ? $style["bg-ll-red"] : "";
			$Style_srvc_Frames_Err = ( Limit_check("FrameCount", $srvc_m_FrameCount, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-red"] : "";
			$srvc_FrameCount = $srvc["FrameCnt"];
			$srvc_RowFrameCount = $srvc["RowFrameCount"];

			// Frames RAW
			$srvc_m_FrameRawCount = @$srvc["RawFrameCount"]["Measure_Value"];
			$srvc_m_FrameRawCount_date = @$srvc["RawFrameCount"]["Received"];
			$srvc_m_FrameRawCount_sAgo = @$srvc["RawFrameCount"]["Received_sAgo"];
			$srvc_m_FrameRawCount_tAgo = sec2hours($srvc_m_FrameRawCount_sAgo);
			//$Style_srvc_Frames_Wrn = ( $srvc_m_FrameCount_sAgo > $Limit["Refresh"] ) ? $style["bg-ll-yellow"] : "";
			$Style_srvc_FramesRaw_Wrn = ( Limit_check("RefreshStat", $srvc_m_FrameRawCount_sAgo, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-yellow"] : "";
			//$Style_srvc_Frames_Err = ( $srvc_m_FrameCount > $Limit["Frames"] ) ? $style["bg-ll-red"] : "";
			$Style_srvc_FramesRaw_Err = ( Limit_check("RawFrameCount", $srvc_m_FrameRawCount, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-red"] : "";
			$srvc_FrameRawCount = $srvc["FrameCnt"];
			$srvc_RowFrameRawCount = $srvc["RowFrameCount"];

			//$Measure_Definition


			$Style_srvc_ServiceState = ( strtoupper($srvc["ServiceState"]) == "RUNNING" ) ? $style["color-darkgreen"]  : $style["color-darkred"]  ;
			$Style_srvc_StartType = ( $srvc_StartType == "Disabled" ) ? $style["color-lll-gray"] : "";
			//if ( $srvc_Service_Name == "ShturmanDiagnostics4" )
			//{
	//			$Style_LastError = ( ($srvc_LastError_sAgo < $Limit["LastErr"]/3) and $srvc_LastError_sAgo ) ? $style["bg-ll-red"] : "";  //TODO костыль на падучую службу и НГ
			//}
			//else
			//{
			//$Style_LastError = ( ($srvc_LastError_sAgo < $Limit["LastErr"]) and $srvc_LastError_sAgo ) ? $style["bg-ll-red"] : "";
			$Style_LastError = ( Limit_check("LastError", $srvc_LastError_sAgo, $srvc_Service_Name, $Srv_Guid) and $srvc_LastError_sAgo ) ? $style["bg-ll-red"] : "";
			//}
			
			//$test = $srvc_LastError_sAgo. $Style_LastError;
			//$Style_Queue = ( $srvc_QueueFileSize > $Limit["Queue"] ) ? $style["bg-ll-red"] : "";


			//echo "<pre>"; var_dump($srvc); echo "</pre>";
			//exit;

			if ( $srvc_Service_Name == "ShturmanDiagnostics4" )
			{
				$template->assign_block_vars('server.diag', array(
				'VER' => $srvc_FileVersion,
				));
			}

			//$srvc_LastVersion = "4.15.0.0";
			//strtoupper($srvc["ServiceState"]) == "RUNNING"
			$template->assign_block_vars('server.srvc', array(
				'I_GUID' => $srvc_Instance_Guid,
				'TEST' => (@$test) ? "<br />". @$test : "",
				'NAME' => $srvc_Service_Name,
				'DISPLAY_NAME' => $srvc_Display_Name,
				'CREATED' => $srvc_Created,
				'CREATED_TAGO' => sec2hours($srvc_Created_sAgo),
				'MODIFIED' => $srvc_Modified,
				'MODIFIED_TAGO' => sec2hours($srvc_Modified_sAgo),
				'STATE' => $srvc_ServiceState,
				'STATE_IMG' => $srvc_ServiceStateImg,
				'START_TYPE' => $srvc_StartType,
				'PLATFORM' => $srvc_Platform,
				'FILE_PATH' => $srvc_BinaryPathName,
				'VER' => $srvc_FileVersion,
				'LAST_VERSION' => "Last version of service is [$srvc_LastVersion]",
				'FILE_MODIFIED' => $srvc_FileModified,
				'FILE_MODIFIED_TAGO' => sec2hours($srvc_FileModified_hAgo, "h"),
				'ALIAS' => $srvc_Alias,
				'ERROR_FILE' => $srvc_ErrorFileName,

				'LOG_FILE' => $srvc_LogFileName,
				'LOG_SIZE' => format_bytes($srvc_LogFileSize),
				'LOG_SIZE_SRVC' => format_bytes($srvc_m_LogFileSizeByte),
				//'LOG' => ( $srvc_LogFileName != '<no information>' ) ? format_bytes($srvc_LogFileSize) . " (" . "XXs" . ")"  : "---",
				'LOG_CHANGED' => $srvc_m_LogFileSizeByte_date,
				'LOG_CHANGED_TAGO' => ( $srvc_LogFileName != '<no information>' ) ? $srvc_m_LogFileSizeByte_TAgo : "",
				//'LOG_SZ_NAME' => @$srvc["LogFileSizeByte"]["Measure_Name"], //$srvc_m_LogFileSizeByte_Measure_Name,
				'LOG_SZ' => @$srvc["LogFileSizeByte"]["Measure_Value"], //$srvc_m_LogFileSizeByte_Measure_Value,
				'LOG_SZ_DATE' => @$srvc["LogFileSizeByte"]["Received"], //$srvc_m_LogFileSizeByte_Received,
				'LOG_SZ_TAGO' => @$srvc["LogFileSizeByte"]["Received_sAgo"], //$srvc_m_LogFileSizeByte_Received_sAgo,
				//'LOG_WRTN_NAME' => @$srvc["LogLastWritten"]["Measure_Name"], //$srvc_m_LogLastWritten_Measure_Name,
				'LOG_WRTN' => @$srvc["LogLastWritten"]["Measure_Value"], //$srvc_m_LogLastWritten_Measure_Value,
				'LOG_WRTN_DATE' => @$srvc["LogLastWritten"]["Received"], //$srvc_m_LogLastWritten_Received,
				'LOG_WRTN_TAGO' => @$srvc["LogLastWritten"]["Received_sAgo"], //$srvc_m_LogLastWritten_Received_sAgo,
				'LOG_LAST' => $srvc_m_LogLastWritten_date,
				'LOG_LAST_TAGO' => $srvc_m_LogLastWritten_tAgo,
	/*
				{server.srvc.S_LOG_WRN}{server.srvc.S_LOG_ERR}
				{server.srvc.LOG_LAST_TAGO}
				назад ({server.srvc.LOG_LAST}); файл изменен: {server.srvc.LOG_CHANGED_TAGO} назад ({server.srvc.LOG_CHANGED}), 
				файл: {server.srvc.LOG_FILE} ({server.srvc.LOG_SIZE} / {server.srvc.LOG_SIZE_SRVC})">
				{server.srvc.LOG_LAST_TAGO}
	*/

				'QUEUE_FILE' => $srvc_QueueFileName,
				'QUEUE' => ( $srvc_QueueFileName != "N/A" ) ? $srvc_QueueFileSize_F . " (" . sec2hours($srvc_QueueFileModified_sAgo) . ")" : "---",
				'QUEUE_SIZE' => $srvc_QueueFileSize_F,
				'QUEUE_MODIFIED' => $srvc_QueueFileModified,
				'QUEUE_MODIFIED_TAGO' => sec2hours($srvc_QueueFileModified_sAgo),
				'QUEUE_TITLE_FULL' => "$srvc_Queue_title $srvc_m_UpQueueFileSizeByte_title $srvc_m_DownQueueFileSizeByte_title",
				'QUEUE_TITLE' => $srvc_Queue_title,
				
	//$srvc_Queue_title
	//$srvc_m_UpQueueFileSizeByte_title
	//$srvc_m_DownQueueFileSizeByte_title

				'LAST_ERR' => $srvc_LastError,
				'LAST_ERR_TAGO' => ( $srvc_LastError_sAgo ) ? $srvc_LastError_tAgo : "",
				'ERROR_SIZE' => format_bytes($srvc_ErrorFileSize),
				//'LAST_ERR_STR' => ( ($srvc_LastError_sAgo < $Limit_LastErr) and $srvc_LastError_sAgo ) ? sec2hours($srvc_LastError_sAgo) : "",
				//'LAST_ERR_STR' => ( $srvc_LastError_sAgo ) ? sec2hours($srvc_LastError_sAgo) : "",

				'UNINSTALLED' => $srvc_UninstalledStr,
				'ROOT_HUB' => $srvc_RootHub,

				'FRAMES_COUNT' => $srvc_FrameCount,
				'FRAMES_ROW' => $srvc_RowFrameCount,

				//Measurement
				//'QUEUE_UP_NAME' => $srvc["UpQueueFileSizeByte"]["Measure_Name"], //$srvc_m_UpQueueFileSizeByte_Measure_Name,
				'QUEUE_UP' => @$srvc_m_UpQueueFileSizeByte, //$srvc_m_UpQueueFileSizeByte_Measure_Value,
				'QUEUE_UP_DATE' => @$srvc["UpQueueFileSizeByte"]["Received"], //$srvc_m_UpQueueFileSizeByte_Received,
				'QUEUE_UP_TAGO' => @$srvc["UpQueueFileSizeByte"]["Received_sAgo"], //$srvc_m_UpQueueFileSizeByte_Received_sAgo,
				'QUEUE_STR' => @$srvc_Queue_state,
				'QUEUE_TITLE_UP_BR' => ($srvc_Queue_title) ? "<br />" : "",
				'QUEUE_TITLE_UP' => $srvc_m_UpQueueFileSizeByte_title,
				//'QUEUE_DOWN_NAME' = > @$srvc["DownQueueFileSizeByte"]["Measure_Name"], // $srvc_m_DownQueueFileSizeByte_Measure_Name,
				'QUEUE_DOWN' => @$srvc_m_DownQueueFileSizeByte, //$srvc_m_DownQueueFileSizeByte_Measure_Value,
				'QUEUE_DOWN_DATE' => @$srvc["DownQueueFileSizeByte"]["Received"], //$srvc_m_DownQueueFileSizeByte_Received,
				'QUEUE_DOWN_TAGO' => @$srvc["DownQueueFileSizeByte"]["Received_sAgo"], //$srvc_m_DownQueueFileSizeByte_Received_sAgo,
				'QUEUE_TITLE_DOWN_BR' => ($srvc_Queue_title or $srvc_m_UpQueueFileSizeByte_title) ? "<br />" : "",
				'QUEUE_TITLE_DOWN' => $srvc_m_DownQueueFileSizeByte_title,
				'QUEUE_REFRESH' => @$srvc_Queue_Refresh,
				
				//'FRAMES_NAME' => @$srvc["FrameCount"]["Measure_Name"], //$srvc_m_FrameCount_Measure_Name,
				'FRAMES' => $srvc_m_FrameCount, //$srvc_m_FrameCount_Measure_Value,
				'FRAMES_DATE' => $srvc_m_FrameCount_date, //$srvc_m_FrameCount_Received,
				'FRAMES_TAGO' => $srvc_m_FrameCount_tAgo, //$srvc_m_FrameCount_Received_sAgo,

				//'FRAMES_NAME' => @$srvc["FrameCount"]["Measure_Name"], //$srvc_m_FrameCount_Measure_Name,
				'FRAMES_RAW' => $srvc_m_FrameRawCount, //$srvc_m_FrameCount_Measure_Value,
				'FRAMES_RAW_DATE' => $srvc_m_FrameRawCount_date, //$srvc_m_FrameCount_Received,
				'FRAMES_RAW_TAGO' => $srvc_m_FrameRawCount_tAgo, //$srvc_m_FrameCount_Received_sAgo,
				
				
				//'MSG_NAME' => @$srvc["MessageCount"]["Measure_Name"], //$srvc_m_MessageCount_Measure_Name,
				'MSG' => $srvc_m_MessageCount, //$srvc_m_MessageCount_Measure_Value,
				'MSG_DATE' => $srvc_m_MessageCount_date, //$srvc_m_MessageCount_Received,
				'MSG_TAGO' => $srvc_m_MessageCount_tAgo, //$srvc_m_MessageCount_Received_sAgo,
				
				//'MEM_PVT_NAME' => $srvc["MemoryPrivateBytes"]["Measure_Name"], //$srvc_m_MemoryPrivateBytes_Measure_Name,
				'MEM_PVT' => format_bytes($srvc_m_MemoryPrivateBytes), //$srvc_m_MemoryPrivateBytes_Measure_Value,
				'MEM_PVT_DATE' => $srvc_m_MemoryPrivateBytes_date, //$srvc_m_MemoryPrivateBytes_Received,
				'MEM_PVT_TAGO' => $srvc_m_MemoryPrivateBytes_tAgo, //$srvc_m_MemoryPrivateBytes_Received_sAgo,
				//'MEM_WKS_NAME' => @$srvc["MemoryWorkingSet"]["Measure_Name"], //$srvc_m_MemoryWorkingSet_Measure_Name,
				'MEM_WKS' => format_bytes($srvc_m_MemoryWorkingSet), //$srvc_m_MemoryWorkingSet_Measure_Value,
				'MEM_WKS_DATE' => $srvc_m_MemoryWorkingSet_date, //$srvc_m_MemoryWorkingSet_Received,
				'MEM_WKS_TAGO' => $srvc_m_MemoryWorkingSet_tAgo, //$srvc_m_MemoryWorkingSet_Received_sAgo,
				//'THREAD_NAME' => @$srvc["ThreadCount"]["Measure_Name"], //$srvc_m_ThreadCount_Measure_Name,
				'THREAD' => $srvc_m_ThreadCount, //$srvc_m_ThreadCount_Measure_Value,
				'THREAD_DATE' => $srvc_m_ThreadCount_date, //$srvc_m_ThreadCount_Received,
				'THREAD_TAGO' => $srvc_m_ThreadCount_tAgo, //$srvc_m_ThreadCount_Received_sAgo,
				//'RESOURCES' => $srvc_m_Resources,
				'MEM' => $srvc_m_Mem,

				'S_FILE_VERSION' => ($srvc_RequireUpgrade ) ? $style["bg-ll-red"] : "",
				'S_START_TYPE' => $Style_srvc_StartType,
				'S_STATE' => $Style_srvc_ServiceState,
				'S_LAST_ERR' => $Style_LastError,
				'S_QUEUE_WRN' => $Style_Queue_Wrn,
				'S_QUEUE_ERR' => $Style_Queue_Err,
				'S_LOG_WRN' => $Style_Log_Wrn,
				'S_LOG_ERR' => $Style_Log_Err,
				'S_FRAMES_WRN' => $Style_srvc_Frames_Wrn,
				'S_FRAMES_ERR' => $Style_srvc_Frames_Err,
				'S_FRAMES_RAW_WRN' => $Style_srvc_FramesRaw_Wrn,
				'S_FRAMES_RAW_ERR' => $Style_srvc_FramesRaw_Err,
				'S_MSG_WRN' => $Style_srvc_Msg_Wrn,
				'S_MSG_ERR' => $Style_srvc_Msg_Err,
				//'S_RESOURCES' => $Style_srvc_Resources,
				'S_MEM_WRN' => $Style_srvc_Mem_Wrn,
				'S_MEM_ERR' => $Style_srvc_Mem_Err,
				'S_THREADS_WRN' => $Style_srvc_Threads_Wrn,
				'S_THREADS_ERR' => $Style_srvc_Threads_Err,
				'S_MODIFIED_WRN' => $Style_srvc_date,
			));
			
			$Warnings = ( $Style_Queue_Wrn or $Style_Log_Wrn or $Style_srvc_Frames_Wrn or $Style_srvc_Msg_Wrn or $Style_srvc_Mem_Wrn or $Style_srvc_Threads_Wrn or $Style_srvc_date ) ? true : $Warnings;
			if ( $Style_Queue_Err )
			{
				$Errors[0][] = array("Server" => $Srv_ComputerFrendlyName, "Service" =>  $srvc_Display_Name, "param" => "Очередь", "val" => $srvc_QueueFileSize_F);
			}
			if ( $Style_Log_Err )
			{
				$Errors[1][] = array("Server" => $Srv_ComputerFrendlyName, "Service" =>  $srvc_Display_Name, "param" => "Последняя запись в лог", "val" => $srvc_m_LogLastWritten_tAgo);
			}
			if ( $Style_srvc_Frames_Err )
			{
				$Errors[0][] = array("Server" => $Srv_ComputerFrendlyName, "Service" =>  $srvc_Display_Name, "param" => "Фреймы", "val" => $srvc_m_FrameCount);
			}
			if ( $Style_srvc_Mem_Err )
			{
				$Errors[0][] = array("Server" => $Srv_ComputerFrendlyName, "Service" =>  $srvc_Display_Name, "param" => "Память", "val" => $srvc_m_Mem);
			}
			if ( $Style_srvc_Msg_Err )
			{
				$Errors[0][] = array("Server" => $Srv_ComputerFrendlyName, "Service" =>  $srvc_Display_Name, "param" => "Messages", "val" => $srvc_m_MessageCount);
			}
			if ( $Style_srvc_Threads_Err )
			{
				$Errors[0][] = array("Server" => $Srv_ComputerFrendlyName, "Service" =>  $srvc_Display_Name, "param" => "Treads", "val" => $srvc_m_ThreadCount);
			}
			if ( $Style_LastError )
			{
				$Errors[1][] = array("Server" => $Srv_ComputerFrendlyName, "Service" =>  $srvc_Display_Name, "param" => "Exception", "val" => $srvc_LastError_tAgo);
			}
			if ( $srvc_RequireUpgrade )
			{
				$Errors[10][] = array("Server" => $Srv_ComputerFrendlyName, "Service" =>  $srvc_Display_Name, "param" => "Требуется обновить сервис", "val" => "$srvc_FileVersion to $srvc_LastVersion" );
			}
			

			if ( @$srvc["Clients"] )
			{
				$template->assign_block_vars('server.srvc.clients', array());

				foreach( $srvc["Clients"] as $Client)
				{
					/*
					["Alias"]=> string(4) "Data"
					["Role"]=> string(15) "DatabaseService"
					["UseQueue"]=> int(1)
					["QueueFileName"]=> string(14) "Data.Hub.queue"
					["QueueFileSize"]=> string(3) "488"
					["Modified"]=> string(19) "24.12.2019 16:47:05"
					["Modified_sAgo"]=> int(16)
					["QueueFileModified"]=> string(19) "24.12.2019 16:46:51"
					["QueueFileModified_sAgo"]=> int(30)
					*/
					//echo "<pre>"; var_dump($Client); echo "</pre>";
					$clnt_Alias = $Client["Alias"];
					$clnt_QSize = $Client["QueueFileSize"];
					$clnt_QSize_F = format_bytes($clnt_QSize);
					
					//$Limit["Queue"] = 100;
					//$style_client_QSize = ( $clnt_QSize > $Limit["Queue"] ) ? $style["bg-ll-red"] : "";
					$style_client_QSize = ( Limit_check("QueueSize", $clnt_QSize, $srvc_Service_Name, $Srv_Guid) ) ? $style["bg-ll-red"] : "";
					
					//$Client["Clients"]
					$template->assign_block_vars('server.srvc.clients.row', array(
						//'I_GUID' => $srvc_Instance_Guid,
						'TEST' => (@$test) ? "<br />". @$test : "",
						'NAME' => $clnt_Alias,
						'ROLE' => $Client["Role"],
						'USE_QUEUE' => $Client["UseQueue"],
						'QUEUE_FILE_NAME' => ($Client["QueueFileName"] ) ? $Client["QueueFileName"] : "No File",
						'QUEUE_FILE_SIZE' => ( $clnt_QSize ) ? $clnt_QSize_F : "",
						'UPDATED' => $Client["Modified"],
						'UPDATED_TAGO' => sec2hours($Client["Modified_sAgo"]),
						'QUEUE_DATE' => ($Client["QueueFileModified"] ) ? $Client["QueueFileModified"] : "No File",
						'QUEUE_TAGO' => ($Client["QueueFileModified_sAgo"] ) ? sec2hours($Client["QueueFileModified_sAgo"]) : "",
						
						'S_SIZE_GRAY' => ( !$Client["UseQueue"] ) ? $style["bg-lllll-gray"] :"",
						'S_SIZE_ERR' => $style_client_QSize,
						'S_UPD_GRAY' => ( !$Client["UseQueue"] ) ? $style["bg-lllll-gray"] :"",
						));
						$Warnings = ( 1==2 ) ? true : $Warnings;

						if ( $style_client_QSize )
						{
							$Errors[1][] = array("Server" => $Srv_ComputerFrendlyName, "Service" =>  $srvc_Display_Name, "param" => "Очередь к [$clnt_Alias]", "val" => $clnt_QSize_F);
						}
				}
			}
		}
	}

}

$ErrorsTitles = array("У меня запор!", "Я обделался!", "Меня стошнило!", "У меня животик болит", "У меня общее недомогание", "Мне немного дурно", "Я поранился", "Я ушибся", "У меня дипрессия", "Мне грустно", "Хочу на ручки");
$ErrorsStyles = array($style["bg-l-red"], $style["bg-ll-red"], $style["bg-lll-red"], $style["bg-llll-red"], $style["bg-lllll-red"], $style["bg-lllll-red"], $style["bg-lllll-red"], $style["bg-lllll-red"], $style["bg-lllll-red"], "", "");
$ErrorsExist = false;
$i = 0;
foreach ( $Errors as $alert )
{
	if ($alert) 
	{
		$template->assign_block_vars('errors', array(
			'CAPTION' => $ErrorsTitles[$i],
			'LEVEL' => $i,
			'STYLE' => @$ErrorsStyles[$i],
		));
	}
	//echo "<pre>"; var_dump($alert); echo "</pre>";
	
	if ( is_array($alert) ) 
	{
		$template->assign_block_vars('errors.alert', array());
		foreach ( $alert as $row )
		{
			$template->assign_block_vars('errors.alert.row', array(
				'SERVER' => $row["Server"],
				'SERVICE' => $row["Service"],
				'PARAM' => $row["param"],
				'VAL' => $row["val"],
			));
		}
		$ErrorsExist = true;
	}
	//*/
	$i++;
}

if ( $Warnings )
{
	$template->assign_block_vars('warnings', array());
}
if ( !$ErrorsExist and !$Warnings ) 
{
	$template->assign_block_vars('noerrors', array());
}

$template->assign_block_vars('legend', array(
	));

sqlsrv_close($conn) ;


$template->pparse('body');
?>