<?php
/***************************************************************************
 *                                blocks.php
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
$microTime=microtime(true); 

define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');


// Get params from url
$AlertOnly = ( strtoupper(@$_GET["AlertOnly"]) == "TRUE" ) ? TRUE : FALSE;
$NeedRepairing = ( strtoupper(@$_GET["NeedRepairing"]) == "TRUE" ) ? TRUE : FALSE;
$NoLegend = ( strtoupper(@$_GET["NoLegend"]) == "TRUE" ) ? TRUE : FALSE;

$Filter = substr(@$_GET["Filter"],0,20);

//exit;

//var_dump (@$_GET);

$bp["wagon_in_train_not_connected_warning"] = 5760; // minutes
$bp["wagon_in_train_not_connected"] = 2880; // minutes
$bp["wagon_in_train_not_connected_light"] = 720; // minutes
$bp["wagon_in_train_not_connected_Diff"] = 300; // minutes (Disconnected time Difference betwen wagons in train )
$bp["wagon_alone_not_connected"] = 4320; // minutes
$bp["wagon_left_connected"] = 10; // блоки пропавшие со связи менее [минут] назад.
$bp["position_changed_time"] = 10; // блоки не менявшие местоположение более [минут].
$bp["svc_working_time_diff"] = 3600; // Разница во времени работы сервисов на блоках [секунд].

$rnd = rand ( 0 , 1000000000 );

function compact_station_name($StationName)
{
	$StationName = str_replace("улица", "ул.", $StationName );
	$StationName = str_replace("Александра Невского", "А.Невского", $StationName );
	$StationName = str_replace("Невского - ", "Невского-", $StationName );
	$StationName = str_replace(Array("проспект","Проспект"), "пр.", $StationName );
	return $StationName;
}


//$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );
$conn = MSSQLconnect( "SpbMetro-sRoot", "Shturman" );


$SQL = "
/****** Blocks list  ******/
SELECT
	[s].[Alias] AS [BlockSerialNo],
	[v].[Name] AS [Wagon],
	[c].[Name] AS [Train],
	-- AS [StationName]
	[c].[Route],
	[v].[WayNo],
	[st].[Name] AS [StationName],
	[st].[Stations_Types_Id], -- тип станции: 1 обычная; другое - депо/пто и пр не станции
	[sgp].[Latitude],
	[sgp].[Longitude],
	[l].[Name] AS [LineName],
--	[v].[Position_Changed] AS [PosChangedTime],
	FORMAT(DATEADD(hour,3,[v].[Position_Changed]), 'dd.MM.yyy HH:mm') AS [PosChangedTime],
	DATEDIFF(MINUTE, [v].[Position_Changed], getutcdate()) AS [PosChangedTimeAgo],
--	[c].[Route_Changed] AS [RouteChangedTime],
	FORMAT(DATEADD(hour,3,[c].[Route_Changed]), 'dd.MM.yyy HH:mm') AS [RouteChangedTime],
	DATEDIFF(MINUTE, [c].[Route_Changed], getutcdate()) AS [RouteChangedTimeAgo],
--	[c].[Users_Guid] AS [CouplingChangeByUser],
--	[p].[Last_Name] AS [CouplingChangeByUser],
	[s].[Is_Connected],
	[s].[IpAddress],
--	[ud].[Guid],
	CONCAT([p].[Last_Name],' ',[p].[First_Name],' ',[p].[Middle_Name]) AS [CouplingDriver],
	[u].[Deleted] AS [CouplingDriverDeleted],
	CONCAT([pd].[Last_Name],' ',[pd].[First_Name],' ',[pd].[Middle_Name]) AS [WagonDriver],
	[ud].[Deleted] AS [WagonDriverDeleted],
	[sn].[SerialNo] AS [HID_SerialNo],
	[sn].[Battery_Level] AS [HID_Battery],
	[sn].[FW_Version] AS [HID_FWVer],
--	[s].[Changed],
--	FORMAT([s].[Changed], 'dd.MM HH:mm') AS [dConnected]
	FORMAT(DATEADD(hour,3,[s].[Changed]), 'dd.MM HH:mm') AS [dChanged],
	FORMAT(DATEADD(hour,3,[s].[Changed]), 'dd.MM.yyy HH:mm') AS [dChangedFull],
	DATEDIFF(MINUTE,[s].[Changed], getutcdate()) AS [ConnTimeAgo]
FROM [Servers] AS [s]
	LEFT JOIN [Vehicles] AS [v] ON [v].[Guid] = [s].[Vehicles_Guid]
	LEFT JOIN [Couplings] AS [c] ON [c].[Guid] = [v].[Couplings_Guid]
	LEFT JOIN [Stations] AS [st] ON [st].[Guid] = [v].[Stations_Guid]
	LEFT JOIN [Lines] AS [l] ON [l].[Guid] = [st].[Lines_Guid]
	LEFT JOIN [Users] AS [u] ON [u].[Guid] = [c].[Users_Guid]
	LEFT JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
	LEFT JOIN [Users] AS [ud] ON [ud].[Vehicles_Guid] = [v].[Guid]
	LEFT JOIN [Users_Persons] AS [pd] ON [pd].[Guid] = [ud].[Users_Persons_Guid]
	LEFT JOIN [Sensors_Cardio] AS [sc] ON [sc].[Users_Guid] = [ud].[Guid]
	LEFT JOIN [Sensors] AS [sn] ON [sn].[Guid] = [sc].[Guid]
	LEFT JOIN [Servers_Geopos] AS [sgp] ON [sgp].[Guid] = [s].[Guid]
WHERE
	1=1
	AND ([s].[Alias] LIKE '" . str_replace(";","' OR [s].[Alias] LIKE '", $CONFIG_SHTURMAN["Block_NameFilter"]) . "')
	AND [s].[Alias] NOT IN ('" . str_replace(";", "', '", $CONFIG_SHTURMAN["Block_Name_Filter_Exclude"]) . "')

ORDER BY [s].[Alias] ASC

";
//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//echo "Step1b: [" . (microtime(true) - $microTime) . "] sec<br />";

$BlockList = Array();
$WagonList = Array();
$data = Array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	if ( !in_array($row["BlockSerialNo"], $BlockList ) ) { $BlockList[] = $row["BlockSerialNo"]; }
	$WagonList[$row["Wagon"]] = $row["BlockSerialNo"];
	$data[$row["BlockSerialNo"]]= $row;
	$DriverType = ( $row["WagonDriver"] == $row["CouplingDriver"] ) ? "Coupling" : "Wagon";
	$DriverDeleted = ( $row["WagonDriverDeleted"]  ) ? TRUE : FALSE;
	$WagonDrivers[$row["BlockSerialNo"]][] = array("WagonDriver" => trim($row["WagonDriver"]), "HID_SerialNo" => $row["HID_SerialNo"], 
							"DriverType" => $DriverType, "DriverDeleted" => $DriverDeleted,
							"HID_Battery" => $row["HID_Battery"], "HID_FWVer" => $row["HID_FWVer"]);
//	$data[]= $row;
}
//echo "<pre>"; var_dump($WagonList); echo "</pre>";
//echo "<pre>"; var_dump($data); echo "</pre>";
//echo "<pre>"; var_dump($WagonDrivers); echo "</pre>";

/*
$SQL = "
--- Stations by blocks at last XX hours 
select
	[s].[Alias] AS [BlockSerialNo],
	count(*) AS [StationsCnt]
FROM [Stages_Vehicles_Stations] AS [vs]
	LEFT JOIN [Vehicles] AS [v] ON [v].[Guid] = [vs].Vehicles_Guid
	LEFT JOIN [Servers] AS [s] ON [s].[Vehicles_Guid] = [v].[Guid]
WHERE 
	DATEDIFF(hour, [vs].[Started], GETDATE()) < 24*3
GROUP BY [s].[Alias]

";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$StationsCount = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$StationsCount[$row["BlockSerialNo"]]= $row["StationsCnt"];
}
*/

$SQL = "
/****** Services Versions and settings by blocks  ******/
SELECT --TOP 1000
	[s].[Alias] AS [BlockSerialNo],
	--[sv].[Alias] AS [ServiceName],
--	[sa].[Name] AS [PropertyName],
	MAX([sa].[Value]) AS 'Version',
	--[sa].[Created],
	--MAX([sa].[Modified]),
	FORMAT(DATEADD(HOUR, 3, MAX([sa].[Modified])), 'dd.MM.yyy HH:mm') AS [Version_Reported]
	--[sa].[Written],
	--[sv].[Is_Connected],
	--[sv].[Changed] AS [ConnectionChanged]
FROM [Services_Attributes] AS [sa]
LEFT JOIN [services] AS [sv] ON [sv].[Guid] = [sa].[Services_Guid]
LEFT JOIN [Servers] AS [s] ON [s].[Guid] = [sv].[Servers_Guid]
WHERE 1=1
	AND ". $CONFIG_SHTURMAN["BlocksFilter"]  ."
	AND [sa].[Name] = 'Version'
	--AND [sv].[Is_Connected] = 1
GROUP BY [s].[Alias]
--ORDER BY [s].[Alias] ASC, [sv].[Alias] ASC, [sa].[Name] ASC, [sa].[Value] DESC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$ServicesNamesList = array();
//$ServicesPropsList = array();
$Versions = Array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

	$data[$row["BlockSerialNo"]]["Version"] = $row["Version"];
	//$data[$row["BlockSerialNo"]]["Version_Installed"] = $row["Version_Installed"];
	$Versions[$row["Version"]] = @$Versions[$row["Version"]]+1;
	$Versions["MaxVersion"] = ( @$Versions["MaxVersion"] < $row["Version"] ) ? $row["Version"] : $Versions["MaxVersion"];
	$Versions["TotalCnt"] = @$Versions["TotalCnt"]+1;
}
$Versions["PercentVersion"] = round($Versions[$Versions["MaxVersion"]] / $Versions["TotalCnt"] * 100); // сколько блоков с последние версией в процентах

//echo "<pre>"; var_dump($data); echo "</pre>";
//echo "<pre>"; var_dump($Versions); echo "</pre>";

$SQL ="
/****** Группы вагонов  ******/
SELECT 
	[s].Alias AS [BlockSerialNo],
	--[vg].[Guid],
	--[vg].[Created],
	--[vg].[Vehicles_Guid],
	--[vg].[Groups_Guid],
	[g].[Code],
	[g].[Name]
FROM [Vehicles_In_Groups] AS [vg]
INNER JOIN [Vehicles] AS [v] ON [v].[Guid] = [vg].[Vehicles_Guid]
INNER JOIN [Servers] AS [s] ON [s].[Vehicles_Guid] = [v].[Guid]
INNER JOIN [Groups] AS [g] ON [g].[Guid] = [vg].[Groups_Guid]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[$row["BlockSerialNo"]]["Groups"][] =  iconv("Windows-1251", "UTF-8", $row["Name"]);
}
//echo "<pre>";var_dump($UserGroups);echo "</pre>";



sqlsrv_close($conn) ;

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );	

/*
// Working Time Difference by Wagons
	$SQL = "
SELECT [Train]
      ,[Wagon_Bad]
      ,[WorkingTime_WagBad]
      ,[Wagon_First]
      ,[WorkingTime_WagFirst]
      ,[Wagon_Second]
      ,[WorkingTime_WagSec]
      ,[WorkingTime_DIFF_sec]
      ,[WorkingTime_DIFF_percent]
      ,[Written]
  FROM [ServersWorkingTime]
  WHERE [Written] = (SELECT MAX([Written]) from [ServersWorkingTime])
	";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$WagonWorkingTime = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$WagonWorkingTime[$row["Wagon_Bad"]] = $row;
}
//echo "<pre>"; var_dump($WagonWorkingTime); echo "</pre>";
*/

// ==========================
// *.Errors files from blocks
// ==========================
 	$SQL = $SQL_QUERY["BlockLogErrorsShort"];
 	$SQL = str_replace("%%1%%" , "AND [ble].[DateTime] > DATEADD(hour,-72,SYSDATETIME())", $SQL);

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	$BlockLogErrors = Array();

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[$row["BlockSerialNo"]]["Errors_ServicesList"][] = str_replace("Shturman", "", $row["ServiceName"]) . ": " . $row["TotalCnt"] . " pcs";
		$data[$row["BlockSerialNo"]]["Errors_TotalCnt"] = @$data[$row["BlockSerialNo"]]["Errors_TotalCnt"] + $row["TotalCnt"];
/*
//		$BlockLogErrors[$row["BlockSerialNo"]] = $row;
		$BlockLogErrors[$row["BlockSerialNo"]]["ServicesList"] = (@$BlockLogErrors[$row["BlockSerialNo"]]["ServicesList"] == "") ? $row["ServiceName"] : $BlockLogErrors[$row["BlockSerialNo"]]["ServicesList"] . ";" . $row["ServiceName"];
		$BlockLogErrors[$row["BlockSerialNo"]][$row["ServiceName"]] = $row["TotalCnt"];
		$BlockLogErrors[$row["BlockSerialNo"]]["TotalCnt"] = @$BlockLogErrors[$row["BlockSerialNo"]]["TotalCnt"] + $row["TotalCnt"];
//*/
	}
//echo "<pre>"; var_dump($BlockLogErrors); echo "</pre>";
//echo "<pre>$SQL</pre>";


// Block's Services Working time history
	$SQL = "
/****** Block's Services Working time history  ******/
DECLARE @MAX_DIFF int = ".$bp["svc_working_time_diff"].";

SELECT [Alias] AS [BlockSerialNo]
--      ,[Servers_Guid]
--      ,[Bluegiga]
--      ,[Cron]
--      ,[Dios]
--      ,[Logic]
--      ,[Math]
--      ,[Modems]
--      ,[Netmon]
--      ,[Power]
--      ,[Sound]
--      ,[Udev]
--      ,[Hub]
--      ,[Written]
--	  ,FORMAT([Written], 'd.M.yyy') AS [Date]
--      ,[MinWorkingTime]
--      ,[MaxWorkingTime]
--      ,[DiffWorkingTime]
	  --, IIF ( [MaxWorkingTime] - [MinWorkingTime] > @MAX_DIFF, 1, 0) AS [Warning]
	--,'1' AS [Warning]
--	  , IIF ( [MaxWorkingTime] - [Bluegiga] > @MAX_DIFF or [MaxWorkingTime] - [Dios] > @MAX_DIFF, 2, 1 ) AS [Svc_Alert_Level]
	  , IIF ( [MaxWorkingTime] - ISNULL([Bluegiga], 0 ) > @MAX_DIFF or [MaxWorkingTime] - ISNULL([Dios], 0) > @MAX_DIFF , 2, 1 ) AS [Svc_Alert_Level]
FROM [BlockWorkingTimeHistory]
WHERE 
	[DiffWorkingTime] > 3600
	AND [Written] > DATEADD(day, -7, GETDATE())
ORDER BY 
	--[Written] DESC, 
	--[Svc_Alert_Level] ASC,
	[BlockSerialNo] ASC,
	[Svc_Alert_Level] ASC
";

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	$BlockSrvcWorkTime = array();
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[$row["BlockSerialNo"]]["Svc_Alert_Level"] = $row["Svc_Alert_Level"];

//		$data[$row["BlockSerialNo"]]["Errors_ServicesList"][] = str_replace("Shturman", "", $row["ServiceName"]) . ": " . $row["TotalCnt"] . " pcs";
//		$data[$row["BlockSerialNo"]]["Errors_TotalCnt"] = @$data[$row["BlockSerialNo"]]["Errors_TotalCnt"] + $row["TotalCnt"];

	}
//echo "<pre>";
//var_dump($BlockSrvcWorkTime);
//echo "</pre>";


// Blocks Configuratin reported by blocks
	$SQL = "
SELECT 
	[s].[BlockSerialNo], [sc].[PropertyName], [sc].[PropertyValue], 
	--[sc].[Reported],
	FORMAT([sc].[Reported], 'dd.MM.yyy HH:mm') AS [Reported],
	DATEDIFF(day, [sc].[Reported], GETDATE()) AS [DayAgo]
	--[sc].[Written]
FROM [ServersConfig] AS [sc]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [sc].[ServerGuid]
WHERE [s].[ReportDateTime] = [sc].[Reported]
	and [s].[Guid] <> '00000000-0000-0000-0000-000000000000'
ORDER BY [sc].[Reported], [s].[BlockSerialNo], [sc].[PropertyName]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$FirmwareEINKverList = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[$row["BlockSerialNo"]]["bcfg_".$row["PropertyName"]] = $row["PropertyValue"];
	$data[$row["BlockSerialNo"]]["bcfg_Reported"] = $row["Reported"];
	$data[$row["BlockSerialNo"]]["bcfg_DayAgo"] = $row["DayAgo"];

	if ( $row["PropertyName"] == "FirmwareEINKver" ) 
	{
		//$ver = $row["PropertyValue"];
		//$ver = intval(substr($ver, 0, strpos($ver, ";")));
		//$verArr = explode(";", $ver);
		$verArr = explode(";", $row["PropertyValue"]);
		//var_dump($verArr);
		if ( @$verArr[2] ) // new ver style
		{
			$type = @$verArr[0];
			$ver = @$verArr[1];
		}
		else //old ver style
		{
			$type = "1";
			$ver = @$verArr[0];
			$ver = ( $ver ) ? strtoupper(dechex($ver)) : 0;
			$ver = ( $ver ) ? substr($ver,5) : 0 ;

			}
/*		if ( ! in_array($ver, $FirmwareEINKverList) && $ver != "" )
		{
			$FirmwareEINKverList[] = $ver;
		}
*/
		$FirmwareEINKverMAX = ( $ver > @$FirmwareEINKverMAX ) ? $ver : @$FirmwareEINKverMAX; // old
		$FirmwareDIOSverMAX[$type] = ( $ver > @$FirmwareDIOSverMAX[$type] ) ? $ver : @$FirmwareDIOSverMAX[$type];
	}
	
}
//echo "<pre>";var_dump($FirmwareDIOSverMAX);echo "</pre>";
//$FirmwareEINKverMAX = max($FirmwareEINKverList);

//echo "<pre>"; var_dump($FirmwareEINKverList); echo "Max: \n$FirmwareEINKverMAX</pre>";

// Жалобы по блокам и серверам (открытые)
	$SQL = "
/****** Task Opened Count by Servers  ******/
SELECT
	[s].[BlockSerialNo],
	count(*) AS [TaskCount]
FROM [Tasks] AS [t]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [t].[Object_Guid]
LEFT JOIN [Tasks_Assigned] AS [a] ON [a].[ID] = [t].[Assigned_ID]
WHERE [t].[Finished] IS NULL
	%%1%%
GROUP BY [s].[BlockSerialNo]
";

if ( $AlertOnly )
{
	$SQL = str_replace("%%1%%", "", $SQL);
}
elseif ( $NeedRepairing == "Opened" )
{
	$SQL = str_replace("%%1%%", "AND [a].[Code] IN ('RepairDep')", $SQL);
}
else {
	$SQL = str_replace("%%1%%", "", $SQL);
}


$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[$row["BlockSerialNo"]]["TaskCount"] = $row["TaskCount"];
}
//echo "<pre>"; var_dump($FirmwareEINKverList); echo "Max: \n$FirmwareEINKverMAX</pre>";


// Блоки в сцепке у которых не меняются линейные станции продолжительное время
	$SQL = "
/****** Блоки в сцепке у которых не меняются линейные станции продолжительное время   ******/
SELECT TOP 1000 
	[ID],
	[ServerName] AS [BlockSerialNo], 
	[PositionName],
	[LastChanged],
	[TimeAgo],
	[TimeAgoFormated],
--	[Written],
	DATEADD(hour, +3, [Written]) AS [Written],
	FORMAT(DATEADD(hour, +3, [Written]), 'dd.MM.yy hh:mm') AS [WrittenF],
--	[Created]
	DATEADD(hour, +3, [Created]) AS [Created],
	FORMAT(DATEADD(hour, +3, [Created]), 'dd.MM.yy hh:mm') AS [CreatedF]
FROM [dbo].[BlockPositionChangeHistory]
WHERE [Written] > DATEADD(DAY, -3, sysutcdatetime())
ORDER BY 
	[ServerName] ASC,
	[Written] ASC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//	$data[$row["BlockSerialNo"]]["LostPosCnt"] = @$data[$row["BlockSerialNo"]]["LostPosCnt"] + 1;
	@$data[$row["BlockSerialNo"]]["LostPosCnt"]++;
	if ( @$data[$row["BlockSerialNo"]]["LostPosMax"] < $row["TimeAgo"] ) 
	{ 
		$data[$row["BlockSerialNo"]]["LostPosMax"] = $row["TimeAgo"]; 
		$data[$row["BlockSerialNo"]]["LostPosMaxF"] = $row["TimeAgoFormated"]; 

	}
//	$data[$row["BlockSerialNo"]]["LostPostMax"] ( @$data[$row["BlockSerialNo"]]["LostPostMax"] < $row["TimeAgo"] ) = $row["TimeAgo"] : ;
}
//echo "<pre>"; var_dump($FirmwareEINKverList); echo "Max: \n$FirmwareEINKverMAX</pre>";

/*
// Количество раз потерянных всех USB устройств
	$SQL = "
-- ****** Количество раз потерянных всех USB устройств  ******
SELECT 
	--[ID],
	[BlockSerialNo],
	--[Date],
	--[Time],
	--[DateTime],
	--FORMAT([DateTime], 'dd.MM.yyy hh:mm') AS [Date],
	--[New],
	--[Old]
	COUNT(*) AS [LostAllUsbCnt]
FROM [Block_DeviceLostStat]
WHERE 
	[DateTime] > DATEADD(day, -3, SYSUTCDATETIME())
	AND [New] = 0
GROUP BY 
	[BlockSerialNo]
ORDER BY
	[BlockSerialNo] ASC
--	[DateTime] ASC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//	$data[$row["BlockSerialNo"]]["LostPosCnt"] = @$data[$row["BlockSerialNo"]]["LostPosCnt"] + 1;
	@$data[$row["BlockSerialNo"]]["LostAllUsbCnt"] = $row["LostAllUsbCnt"];
}
//echo "<pre>"; var_dump($FirmwareEINKverList); echo "Max: \n$FirmwareEINKverMAX</pre>";
*/


// Запуск блока с датой 2010-01-01 (дохлая батарейка)
	$SQL = "
/****** Count of starts in 2010 year  ******/
SELECT TOP 1000 
	[s].[BlockSerialNo],
	COUNT(*) AS [Cnt],
	FORMAT(MAX([sc].[Written]), 'dd.MM.yyy') AS [LastHappend]
FROM [ServersConfig] AS [sc]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [sc].[ServerGuid]
WHERE
	[PropertyName] = 'StartTime'
	AND [PropertyValue]= '2010-01-01 03:00'
	AND [Written] between DATEADD(DAY, -30, GETUTCDATE()) AND GETUTCDATE()
GROUP BY [s].[BlockSerialNo]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//	$data[$row["BlockSerialNo"]]["LostPosCnt"] = @$data[$row["BlockSerialNo"]]["LostPosCnt"] + 1;
	@$data[$row["BlockSerialNo"]]["DateFailStartsCnt"] = $row["Cnt"];
	@$data[$row["BlockSerialNo"]]["DateFailStartsLastHappend"] = $row["LastHappend"];
}
//echo "<pre>"; var_dump($FirmwareEINKverList); echo "Max: \n$FirmwareEINKverMAX</pre>";


// дитагностика проблем по таблицук diag_stat 7 days
	$SQL = "
/****** Блоков с диагностиченскими параметрами вышедшими за допустимые границы  ******/
SELECT 
	--TOP 10000 
	--[id],
	[Block_First],
	--[Block_Second],
	--[Vehicle_First],
	--[Vehicle_Second],
	--[Coupling],
	--[Train_First],
	--[Train_Second],
	--[Route],
	[PropertyName_First],
	[Value_First],
	[Value_First_PerHour],
	[PropertyName_Second],
	[Value_Second],
	[Value_Second_PerHour],
	[Value_Diff_Percent],
	[Value_Diff_perHour_Percent],
	--[SrvcWrkTime_First],
	--[SrvcWrkTime_Second],
	--[SrvcWrkTime_DiffPercent],
	[Date]
	--[Written]
FROM [Block_Diag_Stat]
WHERE --1=1
	(
		([PropertyName_First] = 'RRCount' AND [Value_First] > 1000 AND [Value_Second] > 1000 AND [Value_Diff_Percent] < -10)
		OR ([PropertyName_First] = 'USBLostANTCnt' AND ([Value_First] > 10 AND [Value_Diff_Percent] > 10) )
		OR ([PropertyName_First] = 'USBLostBGCnt' AND [Value_First] > 20 AND [Value_Diff_Percent] > 100)
		OR ([PropertyName_First] = 'USBLostINKCnt' AND [Value_First] > 10 AND [Value_Diff_Percent] > 10)
		OR ([PropertyName_First] = 'USBLostSimTechCnt' AND [Value_First] > 100 AND [Value_Diff_Percent] > 100) --  AND [Value_First]-[Value_Second] > 50 
		OR ([PropertyName_First] = 'USBLostSimTechGPSCnt' AND [Value_First] > 10 AND [Value_Diff_Percent] > 10)
		OR ([PropertyName_First] = 'USBLostSimTechModemCnt' AND [Value_First] > 10 AND [Value_Diff_Percent] > 10)
		OR ([PropertyName_First] = 'BG_STH_ConnectCount' AND [Value_First] > 20 AND [Value_Diff_Percent] > 100)		-- слишком много коннектов - плохо
		OR ([PropertyName_First] = 'BG_STH_ConnectCount' AND [Value_Second] > 10 AND [Value_Diff_Percent] < -10)	-- слишком мало коннектов - тоже плохо
		OR ([PropertyName_First] = 'BG_STH_Count' AND [Value_Second] > 1000 AND [Value_Diff_Percent] < -10)			-- только если у второго приличное количество
		OR ([PropertyName_First] = 'BG_STL_Count' AND [Value_Second] > 100 AND [Value_Diff_Percent] < -20)
		OR ([PropertyName_First] = 'StationsCount' AND [Value_Second] > 250 AND [Value_Diff_Percent] < -15)
		OR ([PropertyName_First] = 'WorkingTime' AND [Value_Second] > 8400 AND [Value_Diff_Percent] < -15)
	)
	AND [SrvcWrkTime_First] > 10800
	AND [SrvcWrkTime_Second] > 10800
ORDER BY 
	[Block_First] ASC,
	[PropertyName_First] ASC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//	$data[$row["BlockSerialNo"]]["LostPosCnt"] = @$data[$row["BlockSerialNo"]]["LostPosCnt"] + 1;
	//@$data[$row["Block_First"]]["Block_Diag_Stat"][] = $row;

	if ( abs(@$data[$row["Block_First"]]["Block_Diag_Stat"][$row["PropertyName_First"]]["Value_Diff_Percent"]) < abs($row["Value_Diff_Percent"]) )
	{
		@$data[$row["Block_First"]]["Block_Diag_Stat"][$row["PropertyName_First"]]["Name"] = $row["PropertyName_First"];
		@$data[$row["Block_First"]]["Block_Diag_Stat"][$row["PropertyName_First"]]["Value_First"] = $row["Value_First"];
		@$data[$row["Block_First"]]["Block_Diag_Stat"][$row["PropertyName_First"]]["Value_Second"] = $row["Value_Second"];
		@$data[$row["Block_First"]]["Block_Diag_Stat"][$row["PropertyName_First"]]["Value_Diff_Percent"] = $row["Value_Diff_Percent"];
	}
	@$data[$row["Block_First"]]["Block_Diag_Stat"][$row["PropertyName_First"]]["Count"]++ ;
			
//	@$data[$row["Block_First"]]["DateFailStartsLastHappend"] = $row["LastHappend"];
}
//echo "<pre>"; var_dump($FirmwareEINKverList); echo "Max: \n$FirmwareEINKverMAX</pre>";



sqlsrv_close($conn) ;


$currentdate = date("Y-m-d", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'blocks.tpl')
);

$VersionMax = $Versions["MaxVersion"];
$VersionMaxCnt = $Versions[$Versions["MaxVersion"]];
$VersionCnt = $Versions["TotalCnt"];
$VersionMaxPercent = $Versions["PercentVersion"];

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,

	'VERSION_MAX' => $VersionMax,
	'VERSION_MAX_CNT' => $VersionMaxCnt,
	'VERSION_TOTAL' => $VersionCnt,
	'VERSION_PERCENT' => $VersionMaxPercent,
	'SERVER_NAME' => $SrvName,

	));


if ( $CONFIG_SHTURMAN["Block_Col_Route"] ) { $template->assign_block_vars('col_route', array()); }
if ( $CONFIG_SHTURMAN["Block_Col_PositionLost"] ) { $template->assign_block_vars('col_positionlost', array()); }
if ( $CONFIG_SHTURMAN["Block_VersionTypes"] ) { $template->assign_block_vars('version_types', array()); }



//	$Versions["MaxVersion"] = ( @$Versions["MaxVersion"] < $row["Version"] ) ? $row["Version"] : $Versions["MaxVersion"];
//	$Versions["TotalCnt"] = @$Versions["TotalCnt"]+1;


$i = 0;
while ( @$BlockList[$i] )
{
 	$BlockSerialNo = $BlockList[$i]; //iconv("Windows-1251", "UTF-8", $data[$i]["BlockSerialNo"]);

	$bcfg_PacketInstalled =  @$data[$BlockSerialNo]["bcfg_PacketInstalled"];
	$bcfg_PacketInstalled = str_replace(";", "); ", $bcfg_PacketInstalled);
	$bcfg_PacketInstalled = str_replace("|", " (", $bcfg_PacketInstalled);
	
	$Version = @$data[$BlockSerialNo]["Version"];


	if ( @strpos(@$bcfg_PacketInstalled, "hturman (") or @strpos(@$bcfg_PacketInstalled, "shturmanhead (") or @strpos(@$bcfg_PacketInstalled, "shturmanrear (") ) 
	{
		$BlockType = "Olimex";
		$BlockTypeImg = "<img src=\"/pic/ico/linux_20x20.png\" Title=\"$BlockType\" width=\"16\" height=\"16\" />";
		$data[$BlockSerialNo]["BlockType"] = $BlockType;
		$VersionsS[$BlockType]["Min"] = ( $Version < @$VersionsS[$BlockType]["Min"] ) ? $Version : @$VersionsS[$BlockType]["Min"];
		$VersionsS[$BlockType]["Max"] = ( $Version > @$VersionsS[$BlockType]["Max"] ) ? $Version : @$VersionsS[$BlockType]["Max"];
		$VersionsS[$BlockType]["CntTotal"] = @$VersionsS[$BlockType]["CntTotal"] + 1;
		$VersionsS[$BlockType][$Version] = @$VersionsS[$BlockType][$Version] + 1;
		$VersionsS[$BlockType]["Type"] = $BlockType;
	}
	elseif ( strpos(@$bcfg_PacketInstalled, "hturman-x86 (") )
	{
		$BlockType = "Linux Pad";
		$BlockTypeImg = "<img src=\"/pic/ico/linux-half_20x20.png\" Title=\"$BlockType\" width=\"16\" height=\"16\" />";
		$data[$BlockSerialNo]["BlockType"] = $BlockType;
		$VersionsS[$BlockType]["Min"] = ( $Version < @$VersionsS[$BlockType]["Min"] ) ? $Version : @$VersionsS[$BlockType]["Min"];
		$VersionsS[$BlockType]["Max"] = ( $Version > @$VersionsS[$BlockType]["Max"] ) ? $Version : @$VersionsS[$BlockType]["Max"];
		$VersionsS[$BlockType]["CntTotal"] = @$VersionsS[$BlockType]["CntTotal"] + 1;
		$VersionsS[$BlockType][$Version] = @$VersionsS[$BlockType][$Version] + 1;
		$VersionsS[$BlockType]["Type"] = $BlockType;
	}
	elseif ( !$Version )
	{
		$BlockType = "Windows Pad";
		$BlockTypeImg = "<img src=\"/pic/ico/windows_20x20.png\" Title=\"$BlockType\" width=\"16\" height=\"16\" />";
		$data[$BlockSerialNo]["BlockType"] = $BlockType;
		$VersionsS[$BlockType]["Min"] = ( $Version < @$VersionsS[$BlockType]["Min"] ) ? $Version : @$VersionsS[$BlockType]["Min"];
		$VersionsS[$BlockType]["Max"] = ( $Version > @$VersionsS[$BlockType]["Max"] ) ? $Version : @$VersionsS[$BlockType]["Max"];
		$VersionsS[$BlockType]["CntTotal"] = @$VersionsS[$BlockType]["CntTotal"] + 1;
		$VersionsS[$BlockType][$Version] = @$VersionsS[$BlockType][$Version] + 1;
		$VersionsS[$BlockType]["Type"] = $BlockType;
	}
	else 
	{
		$BlockType = "Unknown";
		$BlockTypeImg = "<img src=\"/pic/ico/quest_12x20.png\" Title=\"$BlockType\" width=\"12\" height=\"16\" />";
		$data[$BlockSerialNo]["BlockType"] = $BlockType;
		$VersionsS[$BlockType]["Min"] = ( $Version < @$VersionsS[$BlockType]["Min"] ) ? $Version : @$VersionsS[$BlockType]["Min"];
		$VersionsS[$BlockType]["Max"] = ( $Version > @$VersionsS[$BlockType]["Max"] ) ? $Version : @$VersionsS[$BlockType]["Max"];
		$VersionsS[$BlockType]["CntTotal"] = @$VersionsS[$BlockType]["CntTotal"] + 1;
		$VersionsS[$BlockType][$Version] = @$VersionsS[$BlockType][$Version] + 1;
		$VersionsS[$BlockType]["Type"] = $BlockType;
	}
	$i++;
}


$i = 0;
while ( @$BlockList[$i] )
{
	$BadBlock = FALSE;

 	$BlockSerialNo = $BlockList[$i]; //iconv("Windows-1251", "UTF-8", $data[$i]["BlockSerialNo"]);
//	$BlockSerialNoShort = str_replace("BDL", "", $BlockSerialNo);
//	$BlockSerialNoShort = str_replace("STB0", "", $BlockSerialNo);
//	$BlockSerialNoShort = str_replace("STB", "", $BlockSerialNoShort);

	// Убираем из названия блока ненужный префикс
	$BlockSerialNoShort = $BlockSerialNo;
	$x = explode(";", $CONFIG_SHTURMAN["Block_Name_Remove"]);
	foreach ( $x as $item )
	{
		$BlockSerialNoShort = str_replace($item, "", $BlockSerialNoShort);
	}

	$Wagon = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["Wagon"]);
	$Train = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["Train"]);
	$WagonSecond = str_replace($Wagon,"", $Train);
	$WagonSecond = str_replace("-","", $WagonSecond);
//	$WagonSecond = ($WagonSecond) ? "-$WagonSecond" : "";
	$BlockSerialNoSecond = ( $WagonSecond != "" ) ? @$WagonList[$WagonSecond] : "" ;

	$IpAdress = $data[$BlockSerialNo]["IpAddress"];
	$IpAdress = str_replace("10.168.", "-8.", $IpAdress);
	$IpAdress = str_replace("10.110.", "-0.", $IpAdress);
//	$Version = "ver"; //$data[$i]["dConnected"];

	// Version 
	$Version = @$data[$BlockSerialNo]["Version"];
	//$Version_Installed = @$data[$BlockSerialNo]["Version_Installed"];
//	$data[$BlockSerialNo]["BlockType"] = ( $Version ) ? "Linux" : "Windows";

	$BlockType = $data[$BlockSerialNo]["BlockType"];

	$Groups = @implode(";", $data[$BlockSerialNo]["Groups"]);
	//$GroupsShort = str_replace("ТЧ-5", "5", $Groups);
	$GroupsShort = str_replace("ТЧ-5", "5", $Groups);
	$GroupsShort = str_replace("ТЧ-3", "3", $GroupsShort);
	$GroupsShort = str_replace("Линия 4", "4", $GroupsShort);
	$GroupsShort = str_replace("Линия 3", "3", $GroupsShort);
	$GroupsShort = str_replace("Колонна", "к", $GroupsShort);
	$GroupsShort = str_replace("Гараж", "г", $GroupsShort);
	//$test = $Groups;
	
	$Is_Connected = $data[$BlockSerialNo]["Is_Connected"];
	$Connected = $data[$BlockSerialNo]["dChanged"];
	$ConnectedFull = $data[$BlockSerialNo]["dChangedFull"];
	$ConnTimeAgo = $data[$BlockSerialNo]["ConnTimeAgo"];
	$ConnTimeAgoSecond = @$data[$BlockSerialNoSecond]["ConnTimeAgo"];
	//$ConnTimeAgoMin = round($ConnTimeAgoSecond/60);
	$ConnTimeAgoDIFF = abs($ConnTimeAgo-$ConnTimeAgoSecond);
//	$ConnTimeAgoDIFF = $ConnTimeAgo-$ConnTimeAgoSecond;

	$WayNo = $data[$BlockSerialNo]["WayNo"];
	$WayDirection = ( $WayNo == 1 ) ? "=&gt;" : "";
	$WayDirection = ( $WayNo == 2 ) ? "&lt;=" : $WayDirection;

	$StationName = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["StationName"]);
	//$StationNameSecond = ( $WagonSecond != "" ) ? iconv("Windows-1251", "UTF-8", @$data[$BlockSerialNoSecond]["StationName"]) : "";
	$StationNameSecond = ( $WagonSecond != "" ) ? @iconv("Windows-1251", "UTF-8", $data[$BlockSerialNoSecond]["StationName"]) : "";
//	$StationNameSecond = ( $WagonSecond != "" ) ? $StationName : "";
	$StationNameIdentical = ( $StationName == $StationNameSecond ) ? TRUE : FALSE;

	$Stations_Types_Id = $data[$BlockSerialNo]["Stations_Types_Id"];
	$Stations_Types_Id_Second = @$data[$BlockSerialNoSecond]["Stations_Types_Id"];
	$LineName = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["LineName"]);

	$Latitude = $data[$BlockSerialNo]["Latitude"];
	$Longitude = $data[$BlockSerialNo]["Longitude"];
	$GetPosUrl = 'https://geocode-maps.yandex.ru/1.x/?apikey=fa96718d-e594-4ac3-9dfb-136d7c11427d&geocode='.$Longitude.','.$Latitude.'&results=1';
	$GEOAddress = ( $Latitude && $Longitude && $CONFIG_SHTURMAN["Block_Use_GEO_Pos"] ) ?  "<div id=\"Geo_Pos_$BlockSerialNo\"></div><script type=\"text/javascript\">show_content('/dynamic/geo_code.php?rnd=$rnd&Type=Coord2Address&Latitude=$Latitude&Longitude=$Longitude', '#Geo_Pos_$BlockSerialNo')</script>" : "";
	//$GEOAddress = ( $Latitude && $Longitude ) ?  "<div id=\"Geo_Pos_$BlockSerialNo\"></div><script type=\"text/javascript\">show_content('/dynamic/proxy.php?page=GEO_Code&prms=Type=Coord2Address;Latitude=$Latitude;Longitude=$Longitude', '#Geo_Pos_$BlockSerialNo')</script>" : "";
	//$test  = $GEOAddress;


	$PosChangedTime = $data[$BlockSerialNo]["PosChangedTime"];
	$PosChangedTimeAgo = $data[$BlockSerialNo]["PosChangedTimeAgo"];
	$PosChangedTimeAgoActual = ( $Is_Connected ) ? $PosChangedTimeAgo : ($PosChangedTimeAgo - $ConnTimeAgo) ;
	$PosChangedTimeAgoSecond = @$data[$BlockSerialNoSecond]["PosChangedTimeAgo"];

/*
	$TEMP_PrimorskayaSkipAlert = ( $StationName == "Приморская" 
										and $PosChangedTimeAgoActual < $bp["position_changed_time"]*3
										//and ($PosChangedTimeAgo > $bp["position_changed_time"]*3) 
										//and ( ($PosChangedTimeAgo - $ConnTimeAgo) > $bp["position_changed_time"]*3 or $Is_Connected )
										//) ? TRUE : FALSE;
										) ? "<span style=\"background:red;\">YYYYYYYYYYYYY</span>" : FALSE;
*/
	$TEMP_PosIsPrimorskayaX3Alert = ( $StationName == "Приморская" and ($PosChangedTimeAgo < $bp["position_changed_time"]*3) ) ? TRUE : FALSE;

	//$test = "$PosChangedTimeAgo-$ConnTimeAgo-".($PosChangedTimeAgo-$ConnTimeAgo).">".$bp["position_changed_time"] . "-" . $Is_Connected;
	//$test = ($PosChangedTimeAgo-$ConnTimeAgo).">".$bp["position_changed_time"] . " and " . $Is_Connected;
	//$test = ( ($PosChangedTimeAgo-$ConnTimeAgo) > $bp["position_changed_time"]  and $Is_Connected);

/*	
	$PosChangedAlert = ( $Train 
				and $PosChangedTimeAgo > $bp["position_changed_time"] 
				and $Stations_Types_Id == 1 and ($PosChangedTimeAgo - $PosChangedTimeAgoSecond) > $bp["position_changed_time"]
				and $Stations_Types_Id_Second == 1
				and ( ($PosChangedTimeAgo - $ConnTimeAgo) > $bp["position_changed_time"] or $Is_Connected )
				and ! $StationNameIdentical
				and !$TEMP_PrimorskayaSkipAlert // TODO костыль для приморской. там поезда не видят станции 21-25 минут
				//$Is_Connected and 
				) ? TRUE : FALSE;
*/
	$PosChangedAlert = ( $Train 
				and $PosChangedTimeAgo > $bp["position_changed_time"] 
				and $Stations_Types_Id == 1 
				and ($PosChangedTimeAgo - $PosChangedTimeAgoSecond) > $bp["position_changed_time"]
				and $Stations_Types_Id_Second == 1
				and ( ($PosChangedTimeAgo - $ConnTimeAgo) > $bp["position_changed_time"] or $Is_Connected ) // выходил на связь, но так и не обновил станцию.
				and !$StationNameIdentical
				and !$TEMP_PosIsPrimorskayaX3Alert // TODO костыль для приморской. там поезда не видят станции 21-25 минут
				//$Is_Connected and 
				) ? TRUE : FALSE;

	//$PosChangedAlert = TRUE;
	
	//$PosChangedTimeAgoStr = ($PosChangedTimeAgo > 59) ? round(($PosChangedTimeAgo / 60),0) . "h" : $PosChangedTimeAgo . "m";
	//$PosChangedTimeAgoStr = ($PosChangedTimeAgo >= (60*24)) ? round(($PosChangedTimeAgo / (60*24)),0) . "d" : $PosChangedTimeAgoStr;
	//$PosChangedTimeAgoStr = ($PosChangedTimeAgo >= (60*24*30)) ? round(($PosChangedTimeAgo / (60*24*30)),0) . "M" : $PosChangedTimeAgoStr;
	$PosChangedTimeAgoStr = ($PosChangedTimeAgoActual > 59) ? round(($PosChangedTimeAgoActual / 60),0) . "h" : $PosChangedTimeAgoActual . "m";
	$PosChangedTimeAgoStr = ($PosChangedTimeAgoActual >= (60*24)) ? round(($PosChangedTimeAgoActual / (60*24)),0) . "d" : $PosChangedTimeAgoStr;
	$PosChangedTimeAgoStr = ($PosChangedTimeAgoActual >= (60*24*30)) ? round(($PosChangedTimeAgoActual / (60*24*30)),0) . "M" : $PosChangedTimeAgoStr;

	//$test  = ($TEMP_PrimorskayaSkipAlert) ? "( $PosChangedTimeAgoStr )" : "";
	//$test  = ($PosChangedAlert) ? "( $PosChangedTimeAgoStr )" : "";

	$PosLostPostCnt = @$data[$BlockSerialNo]["LostPosCnt"];
	$PosLostPosMax = @$data[$BlockSerialNo]["LostPosMax"];
	$PosLostPosMaxF = @$data[$BlockSerialNo]["LostPosMaxF"];
	$PosLost = ( $PosLostPostCnt ) ? "<img src='/pic/ico/hangar_20x20.png' title='Не менялись станции [$PosLostPostCnt] раз за последние 3 дня; максимум: [$PosLostPosMaxF]' width='15' height='16' />" : "&nbsp;&nbsp;&nbsp;";

	// Working Time
	//$WorkingTime_DIFF_percent = @$WagonWorkingTime[$Wagon]["WorkingTime_DIFF_percent"];
	//$WorkingTime = ( $WorkingTime_DIFF_percent ) ? '<img src="/pic/ico/phone_2_24x24.png" width="15" height="16" title="Connected on '.$WorkingTime_DIFF_percent.'% less than second block" />' : "&nbsp;&nbsp;&nbsp;";


	//Errors
	$Errors_ServicesList = @iconv("Windows-1251", "UTF-8", @implode("; ", @$data[$BlockSerialNo]["Errors_ServicesList"]));
	$Errors_TotalCnt = @$data[$BlockSerialNo]["Errors_TotalCnt"];
	$Errors = ( $Errors_TotalCnt ) ? '<img src="/pic/ico/attention_2_24x24.png" width="15" height="16" title="Got '.$Errors_TotalCnt.' Exeptions in last 72 hours: '.$Errors_ServicesList.'">' : "&nbsp;&nbsp;&nbsp;";

	// Service working time  alert
	$Svc_Alert_Level = @$data[$BlockSerialNo]["Svc_Alert_Level"]; // 1 - некий сервис работал меньеш чем другие, 2 - BG или Диос - работал именьше чем другие
	$Svc_Alert_Img = ( $Svc_Alert_Level == 2 ) ? '<img src="/pic/ico/rainbow_20x20.png" width="16" height="16" title="Блок у которого как минимум Блюгига и Диос работали на ['.$bp["svc_working_time_diff"].'] секунд меньше чем другие за последние 7 дней">' : "&nbsp;&nbsp;&nbsp;";
	$Svc_Alert_Img = ( $Svc_Alert_Level == 1 ) ? '<img src="/pic/ico/rainbow_v3_16x16.png" width="16" height="16" title="Блок у которого как минимум один сервис работал на ['.$bp["svc_working_time_diff"].'] секунд меньше чем другие за последние 7 дней">' : $Svc_Alert_Img;


	// Blocks Configuratin reported by blocks
	$bcfg_Reported = @$data[$BlockSerialNo]["bcfg_Reported"];
	$bcfg_DayAgo = @$data[$BlockSerialNo]["bcfg_DayAgo"];
	$bcfg_DayAgoStr = ( $bcfg_DayAgo == "" ) ? "NEVER" : "";
	$bcfg_DayAgoStr = ( $bcfg_DayAgo == "0" ) ? "Today" : $bcfg_DayAgoStr;
	$bcfg_DayAgoStr = ( $bcfg_DayAgo > "0" ) ? "$bcfg_DayAgo days ago" : $bcfg_DayAgoStr;
	$bcfg_Hostname = @$data[$BlockSerialNo]["bcfg_Hostname"];
	$bcfg_BlockSerialNo = @$data[$BlockSerialNo]["bcfg_BlockSerialNo"];
	$bcfg_IPAddress = str_replace(";", " ", @$data[$BlockSerialNo]["bcfg_IPAddress"]);
//	$bcfg_BlockOrientation = @$data[$BlockSerialNo]["bcfg_BlockOrientation"];
 	$bcfg_BlockOrientation = ( @$data[$BlockSerialNo]["bcfg_BlockOrientation"] != "" ) ? @$data[$BlockSerialNo]["bcfg_BlockOrientation"] : "N/A";
	
	$bcfg_FirmwareEINKver = @$data[$BlockSerialNo]["bcfg_FirmwareEINKver"];
	//$bcfg_FirmwareDIOSEINKver = @$data[$BlockSerialNo]["bcfg_FirmwareEINKver"];
	//$bcfg_FirmwareEINKverHex = "";
	//$bcfg_FirmwareEINKverVer = "";
	//$bcfg_FirmwareEINKverShort = "";
	//$bcfg_FirmwareEINKverTime = "";

	$bcfg_DIOSArr = explode(";", $bcfg_FirmwareEINKver);
	if ( @$bcfg_DIOSArr[2] != "" ) //
	{
		$bcfg_FirmwareDIOSType = @$bcfg_DIOSArr[0];
		$bcfg_FirmwareDIOSVer = @$bcfg_DIOSArr[1];
		$bcfg_FirmwareDIOSTime = @$bcfg_DIOSArr[2];
		$bcfg_FirmwareDIOSVerHEX = ( $bcfg_FirmwareDIOSVer ) ? strtoupper(dechex($bcfg_FirmwareDIOSVer)) : "Uncknown";
		$bcfg_FirmwareDIOSVerIsMax = ( $bcfg_FirmwareDIOSVer == $FirmwareDIOSverMAX[$bcfg_FirmwareDIOSType] and $bcfg_FirmwareDIOSVer ) ? TRUE : FALSE;
		//$test = "type: [$bcfg_FirmwareDIOSType]; ver: [$bcfg_FirmwareDIOSVer]; time: [$bcfg_FirmwareDIOSTime]";
	}
	else // старый стиль версии
	{
		//$bcfg_FirmwareDIOSType = "N/A";
		$bcfg_FirmwareDIOSVer = @$bcfg_DIOSArr[0];
		$bcfg_FirmwareDIOSTime = @$bcfg_DIOSArr[1];
		$bcfg_FirmwareDIOSVerHEX = ( $bcfg_FirmwareDIOSVer ) ? strtoupper(dechex($bcfg_FirmwareDIOSVer)) : "";
		$bcfg_FirmwareDIOSType = ( $bcfg_FirmwareDIOSVerHEX ) ? substr($bcfg_FirmwareDIOSVerHEX,0,1) : "N/A" ;
		$bcfg_FirmwareDIOSVerHEX = ( $bcfg_FirmwareDIOSVerHEX ) ? substr($bcfg_FirmwareDIOSVerHEX,5) : "N/A" ;

		//$bcfg_FirmwareDIOSVerIsMax = ( $bcfg_FirmwareDIOSVer == @$FirmwareDIOSverMAX[$bcfg_FirmwareDIOSType] and $bcfg_FirmwareDIOSVer ) ? TRUE : FALSE;
		$bcfg_FirmwareDIOSVerIsMax = ( $bcfg_FirmwareDIOSVerHEX == @strtoupper(dechex($FirmwareDIOSverMAX[$bcfg_FirmwareDIOSType])) and $bcfg_FirmwareDIOSVerHEX ) ? TRUE : FALSE;



		//$bcfg_FirmwareEINKverVer = substr($bcfg_FirmwareEINKver, 0, strpos($bcfg_FirmwareEINKver, ";"));
		#$bcfg_FirmwareEINKverHex = ( $bcfg_FirmwareEINKverVer != "" ) ? strtoupper(dechex($bcfg_FirmwareEINKverVer)) : "";
		#$bcfg_FirmwareEINKverShort = ( $bcfg_FirmwareEINKverVer != "" && $FirmwareEINKverMAX != $bcfg_FirmwareEINKverVer ) ? "" . substr($bcfg_FirmwareEINKverHex, 5) : "";
		#$bcfg_FirmwareEINKverShort = ( ( $bcfg_FirmwareEINKverVer == "" || $bcfg_FirmwareEINKverVer == "N/A" ) && $bcfg_BlockOrientation != "" ) ? "" : $bcfg_FirmwareEINKverShort;
		#$bcfg_FirmwareEINKverTime = substr($bcfg_FirmwareEINKver, strpos($bcfg_FirmwareEINKver, ";")+1);
	}

	//$test = "[$bcfg_FirmwareDIOSVerHEX-".@strtoupper(dechex($FirmwareDIOSverMAX[$bcfg_FirmwareDIOSType]))."-$bcfg_FirmwareDIOSVerIsMax]" . strtoupper(dechex($bcfg_FirmwareDIOSVer));
	
	$bcfg_BlockOrientationImg = ( strtoupper($bcfg_BlockOrientation) == "TRUE" ) ? "<img style='filter: invert(0);' src='/pic/ico/display_24x24.png' width='15' height='16' title='Display orientation is: Cable from DOWN side; FW Type: [$bcfg_FirmwareDIOSType], Ver: [0x$bcfg_FirmwareDIOSVerHEX]'> " : "";
	$bcfg_BlockOrientationImg = ( strtoupper($bcfg_BlockOrientation) == "FALSE" ) ? "<img style='filter: invert(0);' src='/pic/ico/display_rev_24x24.png' width='15' height='16' title='Display orientation is: Cable from UP side; FW Type: [$bcfg_FirmwareDIOSType], Ver: [0x$bcfg_FirmwareDIOSVerHEX]'> " : @$bcfg_BlockOrientationImg;;
	$bcfg_WhiteScreen = @$data[$BlockSerialNo]["bcfg_WhiteScreen"];
	//$bcfg_BlockOrientationImg = ( $bcfg_WhiteScreen == 'true' ) ? $bcfg_BlockOrientationImg . "W" : $bcfg_BlockOrientationImg . "B";
	$bcfg_BlockOrientationImg = ( $bcfg_WhiteScreen == 'true' ) ? $bcfg_BlockOrientationImg : str_replace("invert(0)", "invert(1);background-color:white;", $bcfg_BlockOrientationImg);
	
	$bcfg_BaseRoute = @$data[$BlockSerialNo]["bcfg_BaseRoute"];
	$bcfg_CurrentRoute = @$data[$BlockSerialNo]["bcfg_CurrentRoute"];
	$bcfg_MaxRoute = @$data[$BlockSerialNo]["bcfg_MaxRoute"];
	$bcfg_ConfigurrationPacket = @$data[$BlockSerialNo]["bcfg_ConfigurrationPacket"];
	$bcfg_ConfigurrationPacketShort = substr($bcfg_ConfigurrationPacket, 0, strpos($bcfg_ConfigurrationPacket, ";"));
	$bcfg_ConfigurrationPacketShort = str_replace("shturman-config-","",$bcfg_ConfigurrationPacketShort);
	$bcfg_ConfigurrationPacketShort = ($bcfg_ConfigurrationPacketShort == "spbmetro4thline") ? "4s" : $bcfg_ConfigurrationPacketShort;
	$bcfg_ConfigurrationPacketShort = ($bcfg_ConfigurrationPacketShort == "spbmetro3thline") ? "3s" : $bcfg_ConfigurrationPacketShort;
	$bcfg_ConfigurrationPacketShort = ($bcfg_ConfigurrationPacketShort == "standalone") ? "aln" : $bcfg_ConfigurrationPacketShort;
	$bcfg_ConfigurrationPacketShort = ($bcfg_ConfigurrationPacketShort == "shturmantestinet") ? "tstI" : $bcfg_ConfigurrationPacketShort;
	$bcfg_ConfigurrationPacketShort = ($bcfg_ConfigurrationPacketShort == "dellineinet") ? "DI" : $bcfg_ConfigurrationPacketShort;
	$bcfg_ConfigurrationPacketShort = ( $Is_Connected and $bcfg_ConfigurrationPacketShort == "" and $Version != "" ) ? "N/A" : $bcfg_ConfigurrationPacketShort;
	$bcfg_PacketInstalled =  @$data[$BlockSerialNo]["bcfg_PacketInstalled"];
	$bcfg_PacketInstalled = str_replace(";", "); ", $bcfg_PacketInstalled);
	$bcfg_PacketInstalled = str_replace("|", " (", $bcfg_PacketInstalled);
	//if ( @strpos("hturman (", @$bcfg_PacketInstalled) )

	$DoNotShowBlock = FALSE;
	if ( @strpos(@$bcfg_PacketInstalled, "hturman (") or @strpos(@$bcfg_PacketInstalled, "shturmanhead (") or @strpos(@$bcfg_PacketInstalled, "shturmanrear (") ) 
	{
		//$BlockType = "Olimex";
		$BlockTypeImg = "<img src=\"/pic/ico/linux_20x20.png\" Title=\"$BlockType\" width=\"16\" height=\"16\" />";
		//$BlockTypeCnt["Olimex"] = @$BlockTypeCnt["Olimex"] + 1;
		//$data[$BlockSerialNo]["BlockType"] = "Linux";
		//$VersionsS[$BlockType]["Min"] = ( $Version < @$VersionsS[$BlockType]["Min"] ) ? $Version : @$VersionsS[$BlockType]["Min"];
		//$VersionsS[$BlockType]["Max"] = ( $Version > @$VersionsS[$BlockType]["Max"] ) ? $Version : @$VersionsS[$BlockType]["Max"];
		//$VersionsS[$BlockType]["CntTotal"] = @$VersionsS[$BlockType]["CntTotal"] + 1;
		//$VersionsS[$BlockType][$Version] = @$VersionsS[$BlockType][$Version] + 1;
		//$VersionsS[$BlockType]["Type"] = $BlockType;
	}
	elseif ( strpos(@$bcfg_PacketInstalled, "hturman-x86 (") )
	{
		//$BlockType = "Linux Pad";
		$BlockTypeImg = "<img src=\"/pic/ico/linux-half_20x20.png\" Title=\"$BlockType\" width=\"16\" height=\"16\" />";
		//$BlockTypeCnt["LinuxPad"] = @$BlockTypeCnt["LinuxPad"] + 1;
		//$data[$BlockSerialNo]["BlockType"] = "LinuxPad";
		//$VersionsS[$BlockType]["Min"] = ( $Version < @$VersionsS[$BlockType]["Min"] ) ? $Version : @$VersionsS[$BlockType]["Min"];
		//$VersionsS[$BlockType]["Max"] = ( $Version > @$VersionsS[$BlockType]["Max"] ) ? $Version : @$VersionsS[$BlockType]["Max"];
		//$VersionsS[$BlockType]["CntTotal"] = @$VersionsS[$BlockType]["CntTotal"] + 1;
		//$VersionsS[$BlockType][$Version] = @$VersionsS[$BlockType][$Version] + 1;
		//$VersionsS[$BlockType]["Type"] = $BlockType;
	}
	elseif ( !$Version )
	{
		//$BlockType = "Windows Pad";
		$BlockTypeImg = "<img src=\"/pic/ico/windows_20x20.png\" Title=\"$BlockType\" width=\"16\" height=\"16\" />";
		//$BlockTypeCnt["WindowsPad"] = @$BlockTypeCnt["WindowsPad"] + 1;
		//$data[$BlockSerialNo]["BlockType"] = "WindowsPad";
		$DoNotShowBlock = ( $Is_Connected or $ConnTimeAgo < 60*72 ) ? FALSE : TRUE;
		//$VersionsS[$BlockType]["Min"] = ( $Version < @$VersionsS[$BlockType]["Min"] ) ? $Version : @$VersionsS[$BlockType]["Min"];
//		$VersionsS[$BlockType]["Max"] = ( $Version > @$VersionsS[$BlockType]["Max"] ) ? $Version : @$VersionsS[$BlockType]["Max"];
		//$VersionsS[$BlockType]["CntTotal"] = @$VersionsS[$BlockType]["CntTotal"] + 1;
		//$VersionsS[$BlockType][$Version] = @$VersionsS[$BlockType][$Version] + 1;
		//$VersionsS[$BlockType]["Type"] = $BlockType;
	}
	else 
	{
		$BlockType = "Unknown";
		$BlockTypeImg = "<img src=\"/pic/ico/quest_12x20.png\" Title=\"$BlockType\" width=\"12\" height=\"16\" />";
		//$BlockTypeCnt["Uncknown"] = @$BlockTypeCnt["Uncknown"] + 1;
		$DoNotShowBlock = ( $Is_Connected or $ConnTimeAgo < 60*72 ) ? FALSE : TRUE;
		//$VersionsS[$BlockType]["Min"] = ( $Version < @$VersionsS[$BlockType]["Min"] ) ? $Version : @$VersionsS[$BlockType]["Min"];
		//$VersionsS[$BlockType]["Max"] = ( $Version > @$VersionsS[$BlockType]["Max"] ) ? $Version : @$VersionsS[$BlockType]["Max"];
		//$VersionsS[$BlockType]["CntTotal"] = @$VersionsS[$BlockType]["CntTotal"] + 1;
		//$VersionsS[$BlockType][$Version] = @$VersionsS[$BlockType][$Version] + 1;
		//$VersionsS[$BlockType]["Type"] = $BlockType;
	}
	//$test = $BlockType;

	$bcfg_SoftwareVer = @$data[$BlockSerialNo]["bcfg_SoftwareVer"];
//	$bcfg_SoftwareVer = str_replace(":", ": ", str_replace(";", "; ", $bcfg_SoftwareVer));

//	$BlockCfg_SrvcRestarts = $bcfg_SoftwareVer;
	$bcfg_SrvcsArr = explode(";", $bcfg_SoftwareVer);
	$bcfg_SrvcRestartsArr = array();
	$bcfg_SrvcVerArr = array();
	$bcfg_SrvcMinRestarts = 100000000;
	$bcfg_SrvcMaxRestarts = 0;
	foreach ( $bcfg_SrvcsArr as $value )
	{
		$row = explode(":", $value);
		if ( @$row[0] != "CronApp" ) 
		{
			$bcfg_SrvcRestartsArr[] = $row[0] . ":" . @$row[2];
			$bcfg_SrvcVerArr[] = $row[0] . ": " . @$row[1];
			$bcfg_SrvcMinRestarts = ( @$row[2] != 0 and @$row[2] < $bcfg_SrvcMinRestarts ) ? @$row[2] : $bcfg_SrvcMinRestarts;
			$bcfg_SrvcMaxRestarts = ( @$row[2] > $bcfg_SrvcMaxRestarts ) ? @$row[2] : $bcfg_SrvcMaxRestarts;
		}
		
	}
	$bcfg_SrvcMaxRestartsDIFF = $bcfg_SrvcMaxRestarts - $bcfg_SrvcMinRestarts;
	$bcfg_SrvcRestarts = ( $bcfg_DayAgo == 0 ) ? implode("; ", $bcfg_SrvcRestartsArr) : "";
	$bcfg_SrvcRestarts_Img = ( $bcfg_SrvcMaxRestartsDIFF and $bcfg_SrvcMaxRestarts != 0 and $bcfg_DayAgo == 0) ? "<img src='/pic/ico/reboot_18x18.png' width='16' height='16' title='$bcfg_SrvcRestarts ($bcfg_Reported; $bcfg_DayAgoStr)'>" : "&nbsp;&nbsp;&nbsp;";
	$bcfg_SrvcRestarts_Img = ( $bcfg_SrvcMaxRestarts == $bcfg_SrvcMinRestarts and $bcfg_SrvcMaxRestarts >=5 and $bcfg_DayAgo == 0 ) ? "<img src='/pic/ico/poweron_20x20.png' width='16' height='16' title='$bcfg_SrvcRestarts ($bcfg_Reported; $bcfg_DayAgoStr)'>" : $bcfg_SrvcRestarts_Img;
	//"<img src='/pic/ico/reboot_18x18.png' width='"16' height='16' title='$bcfg_SrvcRestarts ($bcfg_Reported; $bcfg_DayAgoStr)'>"

	$bcfg_SoftwareVer = implode("; ", $bcfg_SrvcVerArr);
	$bcfg_ServicesRunning = str_replace(";", "; ", @$data[$BlockSerialNo]["bcfg_ServicesRunning"]);
	$bcfg_ServicesStopped = str_replace(";", "; ", @$data[$BlockSerialNo]["bcfg_ServicesStopped"]);
	$bcfg_ServersPool = @$data[$BlockSerialNo]["bcfg_ServersPool"];
	$bcfg_APNName = @$data[$BlockSerialNo]["bcfg_APNName"];
	$bcfg_StartTime = @$data[$BlockSerialNo]["bcfg_StartTime"];

	$bcfg_StartTimeFailsCnt = @$data[$BlockSerialNo]["DateFailStartsCnt"];
	$bcfg_StartTimeFailsLastHappend = @$data[$BlockSerialNo]["DateFailStartsLastHappend"];
	
	
	$bcfg_TimeZone = @$data[$BlockSerialNo]["bcfg_TimeZone"];
	$bcfg_UpTime = @$data[$BlockSerialNo]["bcfg_UpTime"];

	// рабор статистики по очередям
	$bcfg_Alert_Queue = "";
	$bcfg_Queues = @$data[$BlockSerialNo]["bcfg_SrvcQueueSizes"];
	$bcfg_QueuesArr1 = explode(";", $bcfg_Queues);
	if ( $bcfg_Queues )
	{
		$bcfg_QueuesStr = "";
		foreach ( $bcfg_QueuesArr1 as $item )
		{
			$bcfg_QueuesArr2 = explode(":", $item);
			$bcfg_QueuesArr3 = @explode("/", $bcfg_QueuesArr2[1]);
	
			$QueueSrvcName = @$bcfg_QueuesArr2[0];
			$QueueSrvcFrom = @$bcfg_QueuesArr3[0];
			$QueueSrvcTo = @$bcfg_QueuesArr3[1];

			// если это хаб, тоу  него лимит больше т.к. при некотором отсутвии связи - это нормально.
			$QueueSrvcMaxSize = ( $QueueSrvcName == "Hub" ) ? 50000 : 3000;
			
			$S_Q = ( intval($QueueSrvcFrom) > $QueueSrvcMaxSize or intval($QueueSrvcTo) > $QueueSrvcMaxSize or $QueueSrvcFrom == "NO_QUEUE" ) ? $style["bg-l-red"] : "";
			//$S_Q = ( $QueueSrvcName == "Hub" and (intval($QueueSrvcFrom) > $QueueSrvcMaxSize or intval($QueueSrvcTo) > $QueueSrvcMaxSize or $QueueSrvcFrom == "NO_QUEUE") ) ? $style["bg-l-red"] : "";
			$str = "<span style='$S_Q'>$QueueSrvcName: $QueueSrvcFrom / $QueueSrvcTo</span>; ";
			$bcfg_QueuesStr .= $str;
			$bcfg_Alert_Queue .= ( ($S_Q ) or ( $QueueSrvcFrom == "NO_QUEUE" or $QueueSrvcTo == "NO_QUEUE" ) ) ? strip_tags($str) : ""; // строка подсказки для сумарных проблем с блоком
		}
		$bcfg_Alert_Queue = ( $bcfg_Alert_Queue ) ? "Queue: $bcfg_Alert_Queue" : ""; // строка подсказки для сумарных проблем с блоком
		$bcfg_Queues = $bcfg_QueuesStr;
	}
	$bcfg_Queues = str_replace("NOT_APPLICABLE", "N/A", $bcfg_Queues);

	// Разбор статистики по последней активности в логе
	$bcfg_Alert_LastLog = "";
	$bcfg_LastLogRec = "";
	$bcfg_LastLogRecArr1 = explode(";", @$data[$BlockSerialNo]["bcfg_SrvcLastLogRecord"] );
	if ( $bcfg_LastLogRecArr1 )
	{
		foreach ( $bcfg_LastLogRecArr1 as $item )
		{
		$arr = explode(":", $item);
		$logActSrvcName = @$arr[0];
		$logActSrvcTimeF = @$arr[1];
		$logActSrvcTime = @$arr[2];

		if ( $logActSrvcName == "Tracking" ) 
		{
			$LogActSrvcMaxTime = 600;
		}
		elseif ( $logActSrvcName == "CronApp" ) 
		{
			$LogActSrvcMaxTime = 8000;
		}
		else
		{
			$LogActSrvcMaxTime = 300;
		}
//		$LogActSrvcMaxTime = ( $logActSrvcName == "Tracking" ) ? 600 : 300;
		$S_LL = ( intval($logActSrvcTime) > $LogActSrvcMaxTime or $logActSrvcTime == "NO_LOG" or $logActSrvcTime < 0 ) ? $style["bg-l-red"] : "";
		
		// TODO: с проверкой на крон - костыль пока не выпилится файл сервиса крон
		$str = ( $logActSrvcName != "Cron" ) ? "<span style='$S_LL'>$logActSrvcName: $logActSrvcTimeF</span>; " : ""; 
		$bcfg_LastLogRec .=  $str;
		$bcfg_Alert_LastLog .= ( intval($logActSrvcTime) > $LogActSrvcMaxTime or intval($logActSrvcTime) < 0 or $logActSrvcTime == "NO_LOG" ) ? strip_tags($str) : ""; // строка подсказки для сумарных проблем с блоком
		}
		$bcfg_Alert_LastLog = ( $bcfg_Alert_LastLog ) ? "Log Activity: $bcfg_Alert_LastLog" : "";
	}
	
	//$bcfg_LastLogRec = str_replace(";", "; ", $bcfg_LastLogRec);
	//$bcfg_LastLogRec = $bcfg_LastLogRecStr;
	$bcfg_Alert = ( $bcfg_Alert_Queue or $bcfg_Alert_LastLog ) ? "<img src='/pic/ico/quest_12x20.png' width='12' height='16' Title='$bcfg_Alert_Queue $bcfg_Alert_LastLog' />" : "&nbsp;&nbsp;&nbsp;";
	$Style_bcfg_Alert = ( $bcfg_Alert_Queue or $bcfg_Alert_LastLog ) ? $style["bg-ll-lightblue"] : "";

	$bcfg_MAK_BG = @$data[$BlockSerialNo]["bcfg_MAK_BG"];
	$bcfg_MAK_usb = @$data[$BlockSerialNo]["bcfg_MAK_usb"];
	$bcfg_MAK_wlan = @$data[$BlockSerialNo]["bcfg_MAK_wlan"];
	$bcfg_UID_Hardware = @$data[$BlockSerialNo]["bcfg_UID_Hardware"];
	$bcfg_UUID_nanda = @$data[$BlockSerialNo]["bcfg_UUID_nanda"];
	$bcfg_UUID_sda = @$data[$BlockSerialNo]["bcfg_UUID_sda"];
	
	$bcfg_StatDIOSPacketErrorReceivedMax = @$data[$BlockSerialNo]["bcfg_StatDIOSPacketErrorReceivedMax"];
	$bcfg_StatDIOSPacketErrorReceivedTotal = @$data[$BlockSerialNo]["bcfg_StatDIOSPacketErrorReceivedTotal"];
	$bcfg_StatDIOSPacketErrorReceivedCntMin = @$data[$BlockSerialNo]["bcfg_StatDIOSPacketErrorReceivedCntMin"];

	if ( $bcfg_StatDIOSPacketErrorReceivedTotal != 'N/A' and $bcfg_StatDIOSPacketErrorReceivedTotal != '0' and $bcfg_StatDIOSPacketErrorReceivedTotal != '' )
	{
		$bcfg_StatDIOSPacketErrorReceivedDesc = "Ошибок подтверждения получения пакетов от EInk: Всего [$bcfg_StatDIOSPacketErrorReceivedTotal]; Раз [$bcfg_StatDIOSPacketErrorReceivedCntMin]; Макс [$bcfg_StatDIOSPacketErrorReceivedMax]";
		$bcfg_StatDIOSPacketErrorReceived = "$bcfg_StatDIOSPacketErrorReceivedTotal / $bcfg_StatDIOSPacketErrorReceivedCntMin / $bcfg_StatDIOSPacketErrorReceivedMax";
		$bcfg_StatDIOSPacketErrorReceivedImg = "<img src='/pic/ico/attention_20x20.png' width='16' height='16' Title='$bcfg_StatDIOSPacketErrorReceivedDesc' />";
		//ATTENTION
	}
	else
	{
		$bcfg_StatDIOSPacketErrorReceivedDesc = "";
		$bcfg_StatDIOSPacketErrorReceived = "";
		$bcfg_StatDIOSPacketErrorReceivedImg = "&nbsp;&nbsp;&nbsp;";
	}
	
	$StyleAttention = "";
	$StyleAttention = ( $bcfg_StatDIOSPacketErrorReceivedCntMin > 1 ) ?  $style["bg-llll-red"] : $StyleAttention;
	$StyleAttention = ( $bcfg_StatDIOSPacketErrorReceivedCntMin > 5 ) ?  $style["bg-lll-red"] : $StyleAttention;
	$StyleAttention = ( $bcfg_StatDIOSPacketErrorReceivedCntMin > 10 ) ?  $style["bg-ll-red"] : $StyleAttention;
	$StyleAttention = ( $bcfg_StatDIOSPacketErrorReceivedCntMin > 20 ) ?  $style["bg-l-red"] : $StyleAttention;
	$StyleAttention = ( $bcfg_StatDIOSPacketErrorReceivedCntMin > 50 ) ?  $style["bg-red"] : $StyleAttention;

	/*
	$bcfg_USBLostINKCnt = @$data[$BlockSerialNo]["bcfg_USBLostINKCnt"];
	$bcfg_USBLostANTCnt = @$data[$BlockSerialNo]["bcfg_USBLostANTCnt"];
	$bcfg_USBLostBGCnt = @$data[$BlockSerialNo]["bcfg_USBLostBGCnt"];
	$bcfg_USBLostSimTechCnt = @$data[$BlockSerialNo]["bcfg_USBLostSimTechCnt"];
	$bcfg_USBLostSimTechModemCnt = @$data[$BlockSerialNo]["bcfg_USBLostSimTechModemCnt"];
	$bcfg_USBLostSimTechGPSCnt = @$data[$BlockSerialNo]["bcfg_USBLostSimTechGPSCnt"];

	/*
	$USBDevLostCnt = @$data[$BlockSerialNo]["LostAllUsbCnt"]; // скольк ораз были потеряны все USB устройства за ХХ дней (3)
	$USBDevLost = "";
	$USBTitle = "";
	if ( $USBDevLostCnt )
	{
		$USBDevLost .= "<img src='/pic/ico/usb_24x24.png' title='Все USB устройства потеряны [$USBDevLostCnt] раз за последние 3 дня' width='8' height='8' />";
		$USBTitle .= "USB: [$USBDevLostCnt]; ";
	}
	if ( $bcfg_USBLostINKCnt )
	{
		$USBDevLost .= "<img src='/pic/ico/display_24x24.png' title='Отвалилися INK: [$bcfg_USBLostINKCnt] раз за последние 3 дня' width='8' height='8' />";
		$USBTitle .= "INK: [$bcfg_USBLostINKCnt]; ";
	}
	if ( $bcfg_USBLostANTCnt )
	{
		$USBDevLost .= "<img src='/pic/ico/ant_16x16.png' title='Отвалилися INK: [$bcfg_USBLostANTCnt] раз за последние 3 дня' width='8' height='8' />";
		$USBTitle .= "ANT: [$bcfg_USBLostANTCnt]; ";
	}
	if ( $bcfg_USBLostBGCnt )
	{
		$USBDevLost .= "<img src='/pic/ico/BlueTooth_1_24x24.png' title='Отвалилися BG: [$bcfg_USBLostBGCnt] раз за последние 3 дня' width='8' height='8' />";
		$USBTitle .= "BG: [$bcfg_USBLostBGCnt]; ";
	}
	if ( $bcfg_USBLostSimTechCnt )
	{
		$USBDevLost .= "<img src='/pic/ico/st.png' title='Отвалилися SimTech: [$bcfg_USBLostSimTechCnt] раз за последние 3 дня' width='8' height='8' />";
		$USBTitle .= "ST: [$bcfg_USBLostSimTechCnt]; ";
	}
	if ( $bcfg_USBLostSimTechModemCnt )
	{
		$USBDevLost .= "<img src='/pic/ico/phone_2_24x24.png' title='Отвалилися Modem: [$bcfg_USBLostSimTechModemCnt] раз за последние 3 дня' width='8' height='8' />";
		$USBTitle .= "Modem: [$bcfg_USBLostSimTechModemCnt]; ";
	}
	if ( $bcfg_USBLostSimTechGPSCnt )
	{
		$USBDevLost .= "<img src='/pic/ico/gps_16x16.png' title='Отвалилися GPS: [$bcfg_USBLostSimTechGPSCnt] раз за последние 3 дня' width='8' height='8' />";
		$USBTitle .= "GPS: [$bcfg_USBLostSimTechGPSCnt]; ";
	}
	$USBTitle = ( $USBTitle ) ? "Потеряны " : " раз";
	*/
	
	//$USBDevLost .= ( $USBDevLostCnt ) ? "<img src='/pic/ico/usb_24x24.png' title='Все USB устройства потеряны [$USBDevLostCnt] раз за последние 3 дня' width='8' height='8' />" : "";
	//$USBDevLost .= ( $bcfg_USBLostINKCnt ) ? "<img src='/pic/ico/display_24x24.png' title='Отвалилися INK: [$bcfg_USBLostINKCnt] раз за последние 3 дня' width='8' height='8' />" : "";
	//$USBDevLost .= ( $bcfg_USBLostANTCnt ) ? "<img src='/pic/ico/ant_16x16.png' title='Отвалилися INK: [$bcfg_USBLostANTCnt] раз за последние 3 дня' width='8' height='8' />" : "";
	//$USBDevLost .= ( $bcfg_USBLostBGCnt ) ? "<img src='/pic/ico/BlueTooth_1_24x24.png' title='Отвалилися INK: [$bcfg_USBLostBGCnt] раз за последние 3 дня' width='8' height='8' />" : "";
	//$USBDevLost .= ( $bcfg_USBLostSimTechCnt ) ? "<img src='/pic/ico/st.png' title='Отвалилися INK: [$bcfg_USBLostSimTechCnt] раз за последние 3 дня' width='8' height='8' />" : "";
	//$USBDevLost .= ( $bcfg_USBLostSimTechModemCnt ) ? "<img src='/pic/ico/phone_2_24x24.png' title='Отвалилися INK: [$bcfg_USBLostSimTechModemCnt] раз за последние 3 дня' width='8' height='8' />" : "";
	//$USBDevLost .= ( $bcfg_USBLostSimTechGPSCnt ) ? "<img src='/pic/ico/gps_16x16.png' title='Отвалилися INK: [$bcfg_USBLostSimTechGPSCnt] раз за последние 3 дня' width='8' height='8' />" : "";

/*
		'USB_LOST_INK' => ($bcfg_USBLostINKCnt) ? "<img src='/pic/ico/reboot_18x18.png' width='8' height='8' Title='' />" : "",
		'USB_LOST_ANT' => ($bcfg_USBLostANTCnt) ? "<img src='/pic/ico/reboot_18x18.png' width='8' height='8' Title='' />" : "",
		'USB_LOST_BG' => ($bcfg_USBLostBGCnt) ? "<img src='/pic/ico/BlueTooth_1_24x24.png' width='8' height='8' Title='' />" : "",
		'USB_LOST_ST' => ($bcfg_USBLostSimTechCnt) ? "<img src='/pic/ico/reboot_18x18.png' width='8' height='8' Title='' />" : "",
		'USB_LOST_MODEM' => ($bcfg_USBLostSimTechModemCnt) ? "<img src='/pic/ico/phone_2_24x24.png' width='8' height='8' Title='' />" : "",
		'USB_LOST_GPS' => ($bcfg_USBLostSimTechGPSCnt) ? "<img src='/pic/ico/reboot_18x18.png' width='8' height='8' Title='' />" : "",
	*/	

	/*
	// Отвалы устройств из лога
	if ( $bcfg_USBLostANTCnt != "" and $bcfg_USBLostINKCnt != "" and $bcfg_USBLostBGCnt != "" and $bcfg_USBLostSimTechCnt != "" and $bcfg_USBLostSimTechModemCnt != "" and $bcfg_USBLostSimTechGPSCnt)
	{
		$test = "<br/>ANT [$bcfg_USBLostANTCnt]; INK [$bcfg_USBLostINKCnt]; BG [$bcfg_USBLostBGCnt]; ST [$bcfg_USBLostSimTechCnt]; Md [$bcfg_USBLostSimTechModemCnt]; GPS [$bcfg_USBLostSimTechGPSCnt]";
	}
	else{ $test = ""; }
	//*/

	// Диагностика по diag_stat
	$dStat = "";
	$Diag_ConnectionTime = "";
	$Style_Diag_ConnectionTime = "";
	$Diag_BT = "";
	$Style_Diag_BT = "";
	if ( @$data[$BlockSerialNo]["Block_Diag_Stat"] )
	{
		//$diag_stat = "";
		//$diag_stat = Array("0"=>"","1"=>"","2"=>"","3"=>"","4"=>"","5"=>"","6"=>"","7"=>"","8"=>"","9"=>"","10"=>"","11"=>"","12"=>"","13"=>"");
		$diag_stat_Arr = Array();
		
		foreach ( @$data[$BlockSerialNo]["Block_Diag_Stat"] as $dProp )
		{
			$dPName = $dProp["Name"];
			$dValueF = $dProp["Value_First"];
			$dValueS = $dProp["Value_Second"];
			$dDiff = $dProp["Value_Diff_Percent"];
			$dCount = $dProp["Count"];
			///$test = var_dump($dProp);
			//$test = "$dPName: $dCount $dValueF / $dValueS ($dDiff %); ";
			
			if ($dCount > 1)
			{
				switch ($dPName)
				{
					case "WorkingTime":
						$style_item = style_by_val($dDiff, $direction = "DOWN", NULL, $Scale_Dn = 10, $Color = "purple");
						$Style_Diag_ConnectionTime = $style_item;
						//$diag_stat["0"] = "<img src='/pic/ico/phone_2_24x24.png' title='На связи: [". sec2hours($dValueF) ."], что на [$dDiff%] от второго [". sec2hours($dValueS) ."]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						$Diag_ConnectionTime = "<img src='/pic/ico/phone_2_24x24.png' title='На связи: [". sec2hours($dValueF) ."], что на [$dDiff%] от второго [". sec2hours($dValueS) ."]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						break;
					case "StationsCount":
						$style_item = style_by_val($dDiff, $direction = "DOWN", NULL, $Scale_Dn = 10, $Color = "blue");
						$Style_Diag_BT = $style_item;
						//$diag_stat["1"] = "<img src='/pic/ico/BlueTooth_1_24x24.png' title='Меток замечено: [". int2kkk($dValueF) ."], что на [$dDiff%] от второго [". int2kkk($dValueS) ."]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						$Diag_BT = "<img src='/pic/ico/BlueTooth_1_24x24.png' title='Меток замечено: [". int2kkk($dValueF) ."], что на [$dDiff%] от второго [". int2kkk($dValueS) ."]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						break;

					case "RRCount":
						$style_item = style_by_val($dDiff, $direction = "DOWN", NULL, $Scale_Dn = 10);
						//$diag_stat["2"] = "<img src='/pic/ico/pulse_20x20.png' title='Получено RR: [". int2kkk($dValueF) ."] шт, что на [$dDiff%] меньше второго [". int2kkk($dValueS) ."]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						$diag_stat_Arr[] = "<img src='/pic/ico/pulse_20x20.png' title='Получено RR: [". int2kkk($dValueF) ."] шт, что на [$dDiff%] меньше второго [". int2kkk($dValueS) ."]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						break;
					case "BG_STH_ConnectCount":
						$style_item = ( $dDiff > 0 ) ? $style_item = style_by_val($dValueF-$dValueS, $direction = "UP", $Scale_Up = 10) : style_by_val($dDiff, $direction = "DOWN", NULL, $Scale_Dn = 15);
						//$diag_stat["3"] = "<img src='/pic/ico/wireless-charging_20x20.png' title='В логе STH-Connect: [$dValueF] раз, что на [$dDiff%] от второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						$diag_stat_Arr[] = "<img src='/pic/ico/wireless-charging_20x20.png' title='В логе STH-Connect: [$dValueF] раз, что на [$dDiff%] от второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						break;
					case "BG_STH_Count":
						$style_item = style_by_val($dDiff, $direction = "DOWN", NULL, $Scale_Dn = 10);
						//$diag_stat["4"] = "<img src='/pic/ico/hid-ear_20x20.png' title='В логе STH: [". int2kkk($dValueF) ."] раз, что на [$dDiff%] от второго [". int2kkk($dValueS) ."]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						$diag_stat_Arr[] = "<img src='/pic/ico/hid-ear_20x20.png' title='В логе STH: [". int2kkk($dValueF) ."] раз, что на [$dDiff%] от второго [". int2kkk($dValueS) ."]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						break;
					case "BG_STL_Count":
						$style_item = style_by_val($dDiff, $direction = "DOWN", NULL, $Scale_Dn = 10);
						//$diag_stat["5"] = "<img src='/pic/ico/label_20x20.png' title='В логе STL: [". int2kkk($dValueF) ."] раз, что на [$dDiff%] от второго [". int2kkk($dValueS) ."]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						$diag_stat_Arr[] = "<img src='/pic/ico/label_20x20.png' title='В логе STL: [". int2kkk($dValueF) ."] раз, что на [$dDiff%] от второго [". int2kkk($dValueS) ."]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						break;

					case "USBLostANTCnt":
						$style_item = style_by_val($dValueF-$dValueS, $direction = "UP", $Scale_Up = 20);
						//$diag_stat["6"] = "<img src='/pic/ico/ant-2_20x20.png' title='Отвалы ANT: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						$diag_stat_Arr[] = "<img src='/pic/ico/ant-2_20x20.png' title='Отвалы ANT: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						break;
					case "USBLostBGCnt":
						$style_item = style_by_val($dValueF-$dValueS, $direction = "UP", $Scale_Up = 20);
						//$diag_stat["7"] = "<img src='/pic/ico/bluetooth-2_20x20.png' title='Отвалы BG: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						$diag_stat_Arr[] = "<img src='/pic/ico/bluetooth-2_20x20.png' title='Отвалы BG: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						break;
					case "USBLostINKCnt":
						$style_item = style_by_val($dValueF-$dValueS, $direction = "UP", $Scale_Up = 20);
						//$diag_stat["8"] = "<img src='/pic/ico/display_24x24.png' title='Отвалы INK: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						$diag_stat_Arr[] = "<img src='/pic/ico/display_24x24.png' title='Отвалы INK: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						break;
					case "USBLostSimTechCnt":
						$style_item = style_by_val($dValueF-$dValueS, $direction = "UP", $Scale_Up = 20);
						//$diag_stat["9"] = "<img src='/pic/ico/st-2_18x18.png' title='Отвалы SimTech: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						$diag_stat_Arr[] = "<img src='/pic/ico/st-2_18x18.png' title='Отвалы SimTech: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						break;
					case "USBLostSimTechGPSCnt":
						$style_item = style_by_val($dValueF-$dValueS, $direction = "UP", $Scale_Up = 20);
						//$diag_stat["10"] = "<img src='/pic/ico/gps_16x16.png' title='Отвалы GPS: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' width='16' style='padding-left:2px;padding-right:2px;$style_item' height='16' />";
						$diag_stat_Arr[] = "<img src='/pic/ico/gps_16x16.png' title='Отвалы GPS: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' width='16' style='padding-left:2px;padding-right:2px;$style_item' height='16' />";
						break;
					case "USBLostSimTechModemCnt":
						$style_item = style_by_val($dValueF-$dValueS, $direction = "UP", $Scale_Up = 20);
						//$diag_stat["11"] = "<img src='/pic/ico/modem_dongle_20x20.png' title='Отвалы Modem: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						$diag_stat_Arr[] = "<img src='/pic/ico/modem_dongle_20x20.png' title='Отвалы Modem: [$dValueF] раз, что на [$dDiff%] больше второго [$dValueS]; за последние 7 дней повторялось [$dCount] раз' style='padding-left:2px;padding-right:2px;$style_item' width='16' height='16' />";
						break;
				}
			}
			
			/*
			name
			count
			val
			RRCount: 2x 22k/1k -23%
			*/
			//$test = "a";
			
		}
		//$dStat = implode("", $diag_stat);
		$dStat = implode("", $diag_stat_Arr);
		$dStat = ( count($diag_stat_Arr) > 5 ) ? str_replace("width='16'", "width='6'", $dStat) : $dStat;
		$dStat = ( count($diag_stat_Arr) > 4 ) ? str_replace("width='16'", "width='8'", $dStat) : $dStat;
		$dStat = ( count($diag_stat_Arr) > 3 ) ? str_replace("width='16'", "width='11'", $dStat) : $dStat;
		//$dStat = ( count($diag_stat_Arr) > 2 ) ? str_replace("width='16'", "width='12'", $dStat) : $dStat;
	}
	//$test = $dStat;

	// Маршрут
	$RouteSrv = ( $data[$BlockSerialNo]["Route"] ) ? $data[$BlockSerialNo]["Route"] : "---";
//	$RouteBlock = $bcfg_CurrentRoute;
	$RouteBlock = ( $bcfg_CurrentRoute  ) ? $bcfg_CurrentRoute : "---";
	$Route = ( $RouteSrv == $RouteBlock or $RouteSrv == "---" ) ? $RouteSrv : "$RouteSrv / $RouteBlock";
	$RouteChangedTime = $data[$BlockSerialNo]["RouteChangedTime"];
//	$RouteChangedTimeAgo = $data[$BlockSerialNo]["RouteChangedTimeAgo"];


//	$WorkingTimeAlert = ((@$WagonWorkingTime[$Wagon]["Train"] != "") and ($WagonWorkingTime[$Wagon]["WorkingTime_DIFF_percent"] > 10)) ? TRUE : FALSE ;
// "<img src='/pic/ico/phone_2_24x24.png' width='15' height='16' Title='Connected on ".$WagonWorkingTime[$Wagon]["WorkingTime_DIFF_percent"]."% less than second block' />" : "&nbsp;&nbsp;&nbsp;";
//	$WorkingTimeAlert = "<a href='/dynamic/proxy.php?rnd=$rnd&page=BlockStatHistory&params=".$Wagon."' onclick=\"return hs.htmlExpand(this, { slideshowGroup: 'modem-group', objectType: 'ajax', contentId: 'highslide-html-8' } )\">$WorkingTimeAlert</a>";

	

//	$WagonDriver = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["WagonDriver"]);
//	$WagonDriverAdd = ( count($SecondDrivers[$BlockSerialNo]) > 1 ) ? " +" . (count($SecondDrivers[$BlockSerialNo])-1) : "";
//	$HID_SerialNo = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["HID_SerialNo"]);
//	$HID_Battery = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["HID_Battery"]);
//	$HID_FWVer = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["HID_FWVer"]);

	// Statistics
	/*
	// Количество виденных станций за последние ХХ часов
	$StationsCnt = ( @$StationsCount[$BlockSerialNo] ) ? $StationsCount[$BlockSerialNo] : 0 ;
	$StationsCntSecond = ( @$StationsCount[$BlockSerialNoSecond] ) ? $StationsCount[$BlockSerialNoSecond] : 0 ;
	// приводим значения к процентному соотношению
	if ( @$BlockSerialNoSecond != ""  and ( $StationsCnt >= 200 or $StationsCntSecond >=200 ) )
	{
		$StationsCntPERCENT = ( $StationsCnt >= $StationsCntSecond ) ?  $StationsCntSecond / $StationsCnt : $StationsCnt / $StationsCntSecond;
		$StationsCntPERCENT = ( $StationsCntPERCENT == 0 ) ? 100 : intval(100 - ($StationsCntPERCENT * 100) );
		
		$StationsCntPERCENT = ( $StationsCnt <= $StationsCntSecond ) ?  $StationsCntPERCENT : 0;
	}
	else
	{
		$StationsCntPERCENT = 0;
	}
	*/

	$TaskCount = @$data[$BlockSerialNo]["TaskCount"];
	$Tasks = ( $TaskCount ) ? "<img src='/pic/ico/task_128x128.png' width='16' height='16' Title='Жалоб по блоку: [$TaskCount] шт' />" : "&nbsp;&nbsp;&nbsp;";

//	$StationsCount[$BlockSerialNo]

//	$CouplingChangeByUser = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["CouplingChangeByUser"]);
//	$ = $data[$i]["Route"];
//	$ = $data[$i]["Route"];
//	$ = $data[$i]["Route"];


	// Styles
	// Row bg connection style
	$StyleConnected = "";
	$StyleConnected = ( $Is_Connected ) ? $style["bg-lll-green"] : $StyleConnected; // На связи
	$StyleConnected = ( $ConnTimeAgo < $bp["wagon_left_connected"] and ! $Is_Connected ) ? $style["bg-lllll-green"] : $StyleConnected; // ушел со связи совсем недавно
	$StyleConnected = ( $ConnTimeAgo > $bp["wagon_alone_not_connected"] and ! $Train ) ? $style["color-dark-red"] : $StyleConnected; // вагон отцеплен и ушел связи
	// в сцепке, но ОБА вагона не на связи
	$StyleConnected = ( $ConnTimeAgo > $bp["wagon_alone_not_connected"] and $ConnTimeAgoDIFF < $bp["wagon_in_train_not_connected_Diff"] and $Train != "" ) ? $style["color-dark-red"] : $StyleConnected; // вагон отцеплен и ушел связи

	// в сцепке, но ТОЛЬКО ЭТОТ вагон не на связи ( мало | долго | очень долго )
	$StyleConnectedAlert = "";
	$StyleConnectedAlert = ( $ConnTimeAgo > $ConnTimeAgoSecond and $Train != "" and $ConnTimeAgoDIFF > $bp["wagon_in_train_not_connected_Diff"] and $ConnTimeAgo > $bp["wagon_in_train_not_connected_light"] ) ? $style["bg-lllll-red"] : $StyleConnectedAlert;
	$StyleConnectedAlert = ( $ConnTimeAgo > $ConnTimeAgoSecond and $Train != "" and $ConnTimeAgoDIFF > $bp["wagon_in_train_not_connected_Diff"] and $ConnTimeAgo > $bp["wagon_in_train_not_connected"] ) ? $style["bg-lll-red"] : $StyleConnectedAlert;
	$StyleConnectedAlert = ( $ConnTimeAgo > $ConnTimeAgoSecond and $Train != "" and $ConnTimeAgoDIFF > $bp["wagon_in_train_not_connected_Diff"] and $ConnTimeAgo > $bp["wagon_in_train_not_connected_warning"] ) ? $style["bg-l-red"] : $StyleConnectedAlert;

	$StyleVersion = "";
	//$StyleVersion = ( $Version == $VersionMax and $VersionMaxPercent < 50 ) ? "font-weight:bold;" : $StyleVersion;
	$StyleVersion = ( $Version == $VersionsS[$BlockType]["Max"] and $VersionMaxPercent < 50 ) ? "font-weight:bold;" : $StyleVersion;
	//$StyleVersion = ( $Version != $VersionMax and $VersionMaxPercent > 50 and $Is_Connected ) ? $style["bg-l-red"] : $StyleVersion;
	$StyleVersion = ( $Version != $VersionsS[$BlockType]["Max"] and $VersionMaxPercent > 50 and $Is_Connected ) ? $style["bg-l-red"] : $StyleVersion;
	$StyleVersion = ( $Version == "" ) ? "" : $StyleVersion;  // Old Windows block
	
	
	
	//$test = "<br>$Version --" .$VersionsS[$BlockType]["Max"];
	
	
	
	
	
	
	
	
	
//$VersionMax = $Versions["MaxVersion"];
//$VersionMaxCnt = $Versions[$Versions["MaxVersion"]];
//$VersionCnt = $Versions["TotalCnt"];
//$VersionMaxPercent = $Versions["PercentVersion"];


//	$Versions["MaxVersion"] = ( @$Versions["MaxVersion"] < $row["Version"] ) ? $row["Version"] : $Versions["MaxVersion"];
//	$Versions["TotalCnt"] = @$Versions["TotalCnt"]+1;

	// Не менял местоположение длительное время
	$StylePositionAlert = ( $Train 
							and $PosChangedTimeAgo > $bp["position_changed_time"] and 
							//$Is_Connected and 
							$Stations_Types_Id == 1 and ($PosChangedTimeAgo - $PosChangedTimeAgoSecond) > $bp["position_changed_time"]
				) ? $style["bg-l-red"] : "";
	$StylePositionAlert = ( $PosChangedAlert ) ? $style["bg-l-red"] : "";

/*	$PosChangedAlert = ( $Train 
							and $PosChangedTimeAgo > $bp["position_changed_time"] 
							and $Stations_Types_Id == 1 and ($PosChangedTimeAgo - $PosChangedTimeAgoSecond) > $bp["position_changed_time"]
							and ($PosChangedTimeAgo - $ConnTimeAgo) > $bp["position_changed_time"]
				//$Is_Connected and 
				) ? TRUE : FALSE;
*/				
	// Working Time
	/*
	$StyleWorkingTimeAlert = "";
	$StyleWorkingTimeAlert = ((@$WagonWorkingTime[$Wagon]["Train"] != "") and ($WorkingTime_DIFF_percent > 10)) ? $style["bg-llll-puple"] : $StyleWorkingTimeAlert;
	$StyleWorkingTimeAlert = ((@$WagonWorkingTime[$Wagon]["Train"] != "") and ($WorkingTime_DIFF_percent > 20)) ? $style["bg-lll-puple"] : $StyleWorkingTimeAlert;
	$StyleWorkingTimeAlert = ((@$WagonWorkingTime[$Wagon]["Train"] != "") and ($WorkingTime_DIFF_percent > 30)) ? $style["bg-ll-puple"] : $StyleWorkingTimeAlert;
	$StyleWorkingTimeAlert = ((@$WagonWorkingTime[$Wagon]["Train"] != "") and ($WorkingTime_DIFF_percent > 40)) ? $style["bg-l-puple"] : $StyleWorkingTimeAlert;
	$StyleWorkingTimeAlert = ((@$WagonWorkingTime[$Wagon]["Train"] != "") and ($WorkingTime_DIFF_percent > 50)) ? $style["bg-puple"] : $StyleWorkingTimeAlert;
	*/

	/*
	$BTAlertStyle = "";
	$BTAlertStyle = ($StationsCntPERCENT > 10) ? $style["bg-llll-blue"] : $BTAlertStyle;
	$BTAlertStyle = ($StationsCntPERCENT > 20) ? $style["bg-lll-blue"] : $BTAlertStyle;
	$BTAlertStyle = ($StationsCntPERCENT > 30) ? $style["bg-ll-blue"] : $BTAlertStyle;
	$BTAlertStyle = ($StationsCntPERCENT > 40) ? $style["bg-l-blue"] : $BTAlertStyle;
	$BTAlertStyle = ($StationsCntPERCENT > 50) ? $style["bg-blue"] : $BTAlertStyle;
//	$BTAlert = ($StationsCntPERCENT > 10) ? "<img src='/pic/ico/BlueTooth_1_24x24.png' width='15' height='16' Title='Got Station labels on ".$StationsCntPERCENT."% less than second block. Stations labels cnt: [$StationsCnt] / [$StationsCntSecond]' />" : "&nbsp;&nbsp;&nbsp;";
	$BTAlert = ($StationsCntPERCENT > 10) ? "<img src='/pic/ico/BlueTooth_1_24x24.png' width='15' height='16' />" : "&nbsp;&nbsp;&nbsp;";
//	$BTAlert = "<a href='/dynamic/proxy.php?rnd=$rnd&page=BlockStatHistory&params=".$Wagon."' onclick=\"return hs.htmlExpand(this, { slideshowGroup: 'bluetooth-group', objectType: 'ajax', contentId: 'highslide-html-8' } )\">$BTAlert</a>";
	*/
	
	// Position lost
	$StylePosLost = "";
	$StylePosLost = ( $PosLostPostCnt > 1 or $PosLostPosMax > 10 ) ? $style["bg-lllll-red"] : $StylePosLost;
	$StylePosLost = ( $PosLostPostCnt > 2 or $PosLostPosMax > 20 ) ? $style["bg-llll-red"] : $StylePosLost;
	$StylePosLost = ( $PosLostPostCnt > 3 or $PosLostPosMax > 30 ) ? $style["bg-lll-red"] : $StylePosLost;
	$StylePosLost = ( $PosLostPostCnt > 5 or $PosLostPosMax > 50 ) ? $style["bg-ll-red"] : $StylePosLost;
	$StylePosLost = ( $PosLostPostCnt > 10 or $PosLostPosMax > 100 ) ? $style["bg-l-red"] : $StylePosLost;
	$StylePosLost = ( $PosLostPostCnt > 20 or $PosLostPosMax > 200 ) ? $style["bg-red"] : $StylePosLost;

	/*
	// USB Devices lost
	$StyleUSBDevLost = "";
	$StyleUSBDevLost = ( $USBDevLostCnt > 1 ) ? $style["bg-llll-red"] : $StyleUSBDevLost;
	$StyleUSBDevLost = ( $USBDevLostCnt > 2 ) ? $style["bg-lll-red"] : $StyleUSBDevLost;
	$StyleUSBDevLost = ( $USBDevLostCnt > 3 ) ? $style["bg-ll-red"] : $StyleUSBDevLost;
	$StyleUSBDevLost = ( $USBDevLostCnt > 5 ) ? $style["bg-l-red"] : $StyleUSBDevLost;
	$StyleUSBDevLost = ( $USBDevLostCnt > 10 ) ? $style["bg-red"] : $StyleUSBDevLost;
	*/
	// Errors
	$StyleErrors = "";
	$StyleErrors = ( $Errors_TotalCnt > 0 ) ? $style["bg-lllll-red"] : "" ;
	$StyleErrors = ( $Errors_TotalCnt > 1 ) ? $style["bg-llll-red"] : $StyleErrors ;
	$StyleErrors = ( $Errors_TotalCnt > 3 ) ? $style["bg-lll-red"] : $StyleErrors ;
	$StyleErrors = ( $Errors_TotalCnt > 5 ) ? $style["bg-ll-red"] : $StyleErrors ;
	$StyleErrors = ( $Errors_TotalCnt > 10 ) ? $style["bg-l-red"] : $StyleErrors ;
	$StyleErrors = ( $Errors_TotalCnt > 20 ) ? $style["bg-red"] : $StyleErrors ;
	$StyleErrors = ( $Errors_TotalCnt > 50 ) ? $style["bg-l-purple"] : $StyleErrors ;
	$StyleErrors = ( $Errors_TotalCnt > 100 ) ? $style["bg-purple"] : $StyleErrors ;

	//bcfg
	$StyleBcfgReportDate = ( $bcfg_DayAgo > 0 ) ? $style["bg-l-red"] : "";
	//$StyleBcfgFirmwareEINKver = ( $bcfg_FirmwareEINKverVer != "" && $FirmwareEINKverMAX != $bcfg_FirmwareEINKverVer && $bcfg_FirmwareEINKverVer != "N/A" && $Is_Connected ) ? $style["bg-l-red"] : ""; 
	$StyleBcfgFirmwareDIOSver = ( !$bcfg_FirmwareDIOSVerIsMax && $bcfg_FirmwareDIOSType != "N/A" && $Is_Connected ) ? $style["bg-l-red"] : ""; 
	//$StyleBcfgFirmwareDIOSver = ( !$bcfg_FirmwareDIOSVerIsMax && $bcfg_FirmwareDIOSType != "N/A" ) ? $style["bg-l-red"] : ""; 
	//bcfg_FirmwareDIOSVer
	$StyleBcfgBoviTypeError = ( $bcfg_FirmwareDIOSType == "1" && $Is_Connected ) ? $style["bg-l-red"] : "";
	
	$StyleBcfg_ConfigurrationPacket = ( $bcfg_ConfigurrationPacketShort == "N/A" ) ? $style["bg-l-red"] : "";


	// Services restarts
	$StylebcfgSrvcRestarts = "";
	if ( $bcfg_DayAgo == 0 ) // только по сегодняшним отчетам
	{
		$StylebcfgSrvcRestarts = ( $bcfg_SrvcMaxRestartsDIFF > 1 ) ? $style["bg-lllll-red"] : "" ;
		$StylebcfgSrvcRestarts = ( $bcfg_SrvcMaxRestartsDIFF > 3 ) ? $style["bg-llll-red"] : $StylebcfgSrvcRestarts ;
		$StylebcfgSrvcRestarts = ( $bcfg_SrvcMaxRestartsDIFF > 5 ) ? $style["bg-lll-red"] : $StylebcfgSrvcRestarts ;
		$StylebcfgSrvcRestarts = ( $bcfg_SrvcMaxRestartsDIFF > 10 or $bcfg_SrvcMaxRestarts >= 10 ) ? $style["bg-ll-red"] : $StylebcfgSrvcRestarts ;
		$StylebcfgSrvcRestarts = ( $bcfg_SrvcMaxRestartsDIFF > 20 or $bcfg_SrvcMaxRestarts > 20 ) ? $style["bg-l-red"] : $StylebcfgSrvcRestarts ;
		$StylebcfgSrvcRestarts = ( $bcfg_SrvcMaxRestartsDIFF > 50 or $bcfg_SrvcMaxRestarts > 50 ) ? $style["bg-red"] : $StylebcfgSrvcRestarts ;
		$StylebcfgSrvcRestarts = ( $bcfg_SrvcMaxRestartsDIFF > 100 or $bcfg_SrvcMaxRestarts > 100 ) ? $style["bg-l-purple"] : $StylebcfgSrvcRestarts ;
		$StylebcfgSrvcRestarts = ( $bcfg_SrvcMaxRestartsDIFF > 200 or $bcfg_SrvcMaxRestarts > 200 ) ? $style["bg-purple"] : $StylebcfgSrvcRestarts ;
	}
//	$StylebcfgSrvcRestarts = ( $BlockCfg_DayAgo == 0 ) ? $StylebcfgSrvcRestarts : ""; // сброс если это все вчерашнее.



//	$test = "<br> ConnTimeAgo = [$ConnTimeAgo]; ConnTimeAgoSecond [$ConnTimeAgoSecond] ; ConnTimeAgoDIFF [$ConnTimeAgoDIFF]; Train [$Train]; BlockSerialNoSecond [$BlockSerialNoSecond]";
//	$test = "<br>svcMinRst: $bcfg_SrvcMinRestarts ; SvcMaxRst: $bcfg_SrvcMaxRestarts";

//	$StyleConnected = ( $ConnTimeAgo > $ConnTimeAgoSecond and $Train != "" and $ConnTimeAgoDIFF > $bp["wagon_in_train_not_connected_Diff"] and $ConnTimeAgo > $bp["wagon_in_train_not_connected_light"] ) ? $style["bg-lllll-red"] : $StyleConnected;
//	$StyleConnected = ( $ConnTimeAgo > $ConnTimeAgoSecond and $Train != "" and $ConnTimeAgoDIFF > $bp["wagon_in_train_not_connected_Diff"] and $ConnTimeAgo > $bp["wagon_in_train_not_connected"] ) ? $style["bg-lll-red"] : $StyleConnected;
//	$StyleConnected = ( $ConnTimeAgo > $ConnTimeAgoSecond and $Train != "" and $ConnTimeAgoDIFF > $bp["wagon_in_train_not_connected_Diff"] and $ConnTimeAgo > $bp["wagon_in_train_not_connected_warning"] ) ? $style["bg-l-red"] : $StyleConnected;
//	$rowWarningStyle = ( $Is_Connected == "1" ) ? $style["bg-light-green"] : "";


/*
	[v].[Position_Changed] AS [PosChangedTime],
	[c].[Route_Changed] AS [RouteChangedTime],
*/

/*
	$LastName = iconv("Windows-1251", "UTF-8", $data[$i]["Last_Name"]);
	$First_Name = iconv("Windows-1251", "UTF-8", $data[$i]["First_Name"]);
	$Middle_Name = iconv("Windows-1251", "UTF-8", $data[$i]["Middle_Name"]);

	$HID_SerNo = iconv("Windows-1251", "UTF-8", $data[$i]["HID"]);
	$HID_Connected = $data[$i]["HID_Connected"];
	$LastActivity = $data[$i]["LastActivityDate"];
	$LastActivityDaysAgo = $data[$i]["LastActivityDaysAgo"];

	$State = iconv("Windows-1251", "UTF-8", $data[$i]["User_State"]);
	$StateCode = iconv("Windows-1251", "UTF-8", $data[$i]["User_State_Code"]);

	$BlockSerialNo = iconv("Windows-1251", "UTF-8", $data[$i]["BlockSerialNo"]);
	$Wagon = iconv("Windows-1251", "UTF-8", $data[$i]["Wagon"]);
//	$Train = iconv("Windows-1251", "UTF-8", $data[$i]["Train"]);
	$StationName = iconv("Windows-1251", "UTF-8", $data[$i]["StationName"]);
//	$LineName = iconv("Windows-1251", "UTF-8", $data[$i]["LineName"]);
	$LineNum = iconv("Windows-1251", "UTF-8", $data[$i]["LineNum"]);
//	$WayNo = iconv("Windows-1251", "UTF-8", $data[$i]["WayNo"]);

	$Position = ( $Wagon != "" ) ?  "$Wagon ($BlockSerialNo), $StationName ($LineNum)" : "";
	$Position = ( $Wagon == "" and  $BlockSerialNo ) ?  "on $BlockSerialNo" : $Position;

	$ShowRow = FALSE;
	$ShowRow = ( $ShowAlert == "LastActivity" and $DriverConnAgoStyle != "" ) ? TRUE : $ShowRow;
	$ShowRow = ( $ShowAlert == "FW" and $FWVerAlertStyle != "" ) ? TRUE : $ShowRow;
	$ShowRow = ( $ShowAlert == "" ) ? TRUE : $ShowRow;
*/
	

	$ShowRow = FALSE;
	if ( !$AlertOnly and !$NeedRepairing  and !$Filter )
	{
		$ShowRow = TRUE;
	}
	elseif ( $Filter == "LastVersion" and $Version == $VersionMax )
	{
		$ShowRow = TRUE;
	}
	elseif ( $Filter == "NoCfgPacket" and $bcfg_ConfigurrationPacketShort == "N/A" )
	{
		$ShowRow = TRUE;
	}
	elseif ( $Filter == "ModemConnect" and $StyleWorkingTimeAlert )
	{
		$ShowRow = TRUE;
	}
//	elseif ( $Filter == "BTDiff" and $StationsCntPERCENT > 10 )
//	{
//		$ShowRow = TRUE;
//	}
	elseif ( $Filter == "StationsNoChange" and $PosLostPostCnt > 1 or $PosLostPosMax > 10 )
	{
		$ShowRow = TRUE;
	}
	elseif ( $Filter == "USBLost" and $USBDevLostCnt )
	{
		$ShowRow = TRUE;
	}
	elseif ( $Filter == "BlockAlerts" and $Style_bcfg_Alert )
	{
		$ShowRow = TRUE;
	}
	elseif ( $Filter == "SrvcCrashes" and $StyleErrors )
	{
		$ShowRow = TRUE;
	}
	elseif ( $Filter == "SrvcReset" and ( $bcfg_SrvcMaxRestartsDIFF > 0 or $bcfg_SrvcMaxRestarts > 5 ) )
	{
		$ShowRow = TRUE;
		//$test = "=$bcfg_SrvcMaxRestartsDIFF-$bcfg_SrvcMaxRestarts";
	}
	elseif ( $Filter == "BlocksRainbow" and $Svc_Alert_Level )
	{
		$ShowRow = TRUE;
	}
	elseif ( $Filter == "TasksExist" and $TaskCount )
	{
		$ShowRow = TRUE;
	}
	elseif ( $Filter == "Active" and !$DoNotShowBlock )
	{
		$ShowRow = TRUE;
	}
	elseif ( $AlertOnly  and (	//$StyleConnected 
					$StyleVersion 
					or $StyleConnectedAlert 
					or $StylePositionAlert 
					or $StyleWorkingTimeAlert 
					or $BTAlertStyle 
					or $StyleErrors 
					or $StyleBcfgFirmwareDIOSver 
					or !$Wagon
					or $StyleBcfg_ConfigurrationPacket
					or $StyleUSBDevLost
					or $USBDevLostCnt
					or $StylePosLost
					or $TaskCount

				)
		)	// Все болеющие (по любым причинам)
	{
		$ShowRow = TRUE;
	}
	elseif ( $NeedRepairing and (	//$StyleConnected or 
					//$StyleVersion 
					$StyleConnectedAlert 
					or $StylePositionAlert 
					or $StyleWorkingTimeAlert 
					or $BTAlertStyle 
					//or $USBDevLostCnt
					//or $StyleErrors 
					//or $StyleBcfgFirmwareDIOSver 
					or $TaskCount
				)
		) // В ремонт
	{
		$ShowRow = TRUE;
	}
	/*
	if ( $DoNotShowBlock and $Filter != "ShowAll" )
	{
		$test = "$DoNotShowBlock $Filter";
		//$ShowRow = FALSE;
	}
*/
	if ( $ShowRow ) {

	$template->assign_block_vars('row', array(
		'BLOCKSERIALNO' => $BlockSerialNo,
		'BLOCK' => $BlockSerialNoShort,
		'SECOND_WAGON' => ($WagonSecond) ? "-$WagonSecond" : "",
		'IPADDRESS' => $IpAdress,
		'VERSION' => $Version,
		//'VERSION_INSTALLED' => $Version_Installed,

		'CONNECTED' => $Connected,
		'CONNECTED_FULL' => $ConnectedFull,


		'WAY_DIRECTION' => $WayDirection,
		'STATION_NAME' => compact_station_name($StationName),
		'GEO_ADDRESS' => $GEOAddress,
		'POSITION_CHANGED_TIME' => $PosChangedTime,
		'POSITION_CHANGED_TIME_AGO' => $PosChangedTimeAgoActual, //$PosChangedTimeAgo,
		'POSITION_CHANGED_TIME_AGO_FORMATED' => $PosChangedTimeAgoStr,
		'POSITION_CHANGED_TIME_AGO_ALERT_FORMATED' => ( $PosChangedAlert ) ? "($PosChangedTimeAgoStr)" : "",

		// ' 
		'GROUPS' => $Groups,
		'GROUPS_SHORT' => $GroupsShort,

		'ROUTE' => $Route,
		'ROUTE_SRV' => $RouteSrv,
		'ROUTE_BLOCK' => $RouteBlock,
		'ROUTE_CHANGED_TIME' => $RouteChangedTime,

//		'WORKING_TIME_PERCENT' => $WorkingTime_DIFF_percent,
		//'WORKING_TIME' => $WorkingTime,
		'DIAG_CONNECTION_TIME' => ( $Diag_ConnectionTime ) ? $Diag_ConnectionTime : "&nbsp;&nbsp;&nbsp;",

//		'DRIVER_FIO' => "", //$WagonDriver,
//		'DRIVER_ADD' => "", //$WagonDriverAdd,
//		'DRIVER_HID_ID' => "", //$HID_SerialNo,
//		'DRIVER_BAT' => "", //$HID_Battery,
//		'DRIVER_FW_VER' => "", //$HID_FWVer,

		//'STATION_CNT_PERCENT' => $StationsCntPERCENT,
		//'STATION_CNT' => $StationsCnt,
		//'STATION_CNT_SECOND' => $StationsCntSecond,

		//'BT_ALERT' => $BTAlert,
		'DIAG_BT' => ( $Diag_BT ) ? $Diag_BT : "&nbsp;&nbsp;&nbsp;",

//		'POSITION_LOST' => ( $PosLostPostCnt ) ? "[$PosLostPostCnt/$PosLostPosMaxF]" : "",
//		'POSITION_LOST' => "[$PosLostPostCnt / $PosLostPosMax]",
		'POSITION_LOST' => $PosLost,
		//'USB_ALERT' => $USBDevLost,
		'BLOCK_DIAG' => $dStat,
		//'USB_TITLE' => $USBTitle,
/*
		'USB_LOST_INK' => ($bcfg_USBLostINKCnt) ? "<img src='/pic/ico/reboot_18x18.png' width='8' height='8' Title='' />" : "",
		'USB_LOST_ANT' => ($bcfg_USBLostANTCnt) ? "<img src='/pic/ico/reboot_18x18.png' width='8' height='8' Title='' />" : "",
		'USB_LOST_BG' => ($bcfg_USBLostBGCnt) ? "<img src='/pic/ico/BlueTooth_1_24x24.png' width='8' height='8' Title='' />" : "",
		'USB_LOST_ST' => ($bcfg_USBLostSimTechCnt) ? "<img src='/pic/ico/reboot_18x18.png' width='8' height='8' Title='' />" : "",
		'USB_LOST_MODEM' => ($bcfg_USBLostSimTechModemCnt) ? "<img src='/pic/ico/phone_2_24x24.png' width='8' height='8' Title='' />" : "",
		'USB_LOST_GPS' => ($bcfg_USBLostSimTechGPSCnt) ? "<img src='/pic/ico/reboot_18x18.png' width='8' height='8' Title='' />" : "",
	*/	
		'BLOCK_ALERT' => $bcfg_Alert,

		'ERORRS' => $Errors,

		'SRVC_RESTARTS' => $bcfg_SrvcRestarts_Img,
		
		'SVC_ALERT' => $Svc_Alert_Img,
		'TASK' => $Tasks,

		'BLOCK_TYPE' => $BlockType,
		'BLOCK_TYPE_IMG' => $BlockTypeImg,
		
		'BCFG_HOSTNAME' => $bcfg_Hostname,
		'BCFG_BLOCKSERNO' => $bcfg_BlockSerialNo,
		'BCFG_IP_ADDRESSES' => $bcfg_IPAddress,
		'BCFG_FIRMWARE_EINK' => $bcfg_FirmwareDIOSVerHEX, //$bcfg_FirmwareEINKverHex,
		'BCFG_FIRMWARE_DIOS_TYPE' => $bcfg_FirmwareDIOSType,
		'BCFG_FIRMWARE_EINK_SHORT' => ( $StyleBcfgFirmwareDIOSver ) ? $bcfg_FirmwareDIOSVerHEX : "", // $bcfg_FirmwareEINKverShort,
//		'BCFG_FIRMWARE_EINK_SHORT' => $bcfg_FirmwareDIOSVerHEX,
		'BCFG_FIRMWARE_EINK_ORIENTATION' => $bcfg_BlockOrientation,
		'BCFG_FIRMWARE_EINK_ORIENTATION_IMG' => $bcfg_BlockOrientationImg,
		'BCFG_FIRMWARE_EINK_CHECKTIME' => $bcfg_FirmwareDIOSTime, //$bcfg_FirmwareEINKverTime,
		'BCFG_ROUTE_MIN' => $bcfg_BaseRoute,
		'BCFG_ROUTE_CURRENT' => $bcfg_CurrentRoute,
		'BCFG_ROUTE_MAX' => $bcfg_MaxRoute,
		'BCFG_CONFIG_PACKET_SHORT' => $bcfg_ConfigurrationPacketShort,
		'BCFG_CONFIG_PACKET' => $bcfg_ConfigurrationPacket,
		'BCFG_PACKETS_INSTALLED' => $bcfg_PacketInstalled,
		'BCFG_SERVICES_VERSIONS' => $bcfg_SoftwareVer,
		'BCFG_QUEUES' => $bcfg_Queues,
		'BCFG_LOGLASTREC' => $bcfg_LastLogRec,
		'BCFG_SERVICES_RESTARTS' => $bcfg_SrvcRestarts,
		'BCFG_SERVICES_RUNNING' => $bcfg_ServicesRunning,
		'BCFG_SERVICES_STOPPED' => $bcfg_ServicesStopped,
		'BCFG_SERVERS_POOL' => $bcfg_ServersPool,
		'BCFG_APN_NAME' => $bcfg_APNName,
		'BCFG_SYSTEM_STARTED' => $bcfg_StartTime,
		'BCFG_SYSTEM_STARTED_DATE_FAIL' => ($bcfg_StartTimeFailsCnt) ? "; Блок запускался с нулевой датой [$bcfg_StartTimeFailsCnt] раз за последние [30] дней, последний раз [$bcfg_StartTimeFailsLastHappend]" : "",
		//'BCFG_SYSTEM_STARTED_DATE_FAIL_CNT' => $bcfg_StartTimeFailsCnt,
		//'BCFG_SYSTEM_STARTED_DATE_FAIL_LASTH_APPEND' => $bcfg_StartTimeFailsLastHappend,

		
		'BCFG_TIMEZONE' => $bcfg_TimeZone,
		'BCFG_STARTUPTIME' => $bcfg_UpTime,
		'BCFG_UUID_NANDA' => $bcfg_UUID_nanda,
		'BCFG_UUID_SDA' => $bcfg_UUID_sda,
		'BCFG_MAK_BG' => $bcfg_MAK_BG,
		'BCFG_MAK_USB' => $bcfg_MAK_usb,
		'BCFG_MAK_WIFI' => $bcfg_MAK_wlan,
		'BCFG_HARDWARE_UID' => $bcfg_UID_Hardware,
		'BCFG_REPORT_DATE' => $bcfg_Reported,
		'BCFG_DAY_AGO' => $bcfg_DayAgoStr,
		
		'ATTENTION' => $bcfg_StatDIOSPacketErrorReceivedImg,

		// Styles
		'STYLE_CONNECTED' => $StyleConnected,
		'STYLE_CONNECTED_ALERT' => $StyleConnectedAlert,
		'STYLE_STARTED_DATE_FAIL_ALERT' => ($bcfg_StartTimeFailsCnt) ? $style["bg-l-red"] : "",
		
		
		'STYLE_VERSION' => $StyleVersion,
//		'STYLE_RENAME_ERROR' => $StyleRenameError,
		'STYLE_POSITION_CHANGE_ALERT' => $StylePositionAlert,
		'STYLE_MODEM_ALERT' => $Style_Diag_ConnectionTime, //$StyleWorkingTimeAlert,
		'STYLE_BT_ALERT' => $Style_Diag_BT, //$BTAlertStyle,
		'STYLE_POSLOST_ALERT' => $StylePosLost,
		//'STYLE_USB_ALERT' => $StyleUSBDevLost,
		'STYLE_ERORRS' => $StyleErrors,
		'STYLE_ATTENTION' => $StyleAttention,

		'STYLE_BCFG_REPORTDATE' => $StyleBcfgReportDate,
		'STYLE_BCFG_FIRMWARE_EINK' => $StyleBcfgFirmwareDIOSver,
		'STYLE_BCFG_BOVI_TYPE' => $StyleBcfgBoviTypeError,
		'STYLE_BCFG_CONFIG_PACKET' => $StyleBcfg_ConfigurrationPacket,
		'STYLE_BCFG_SRVC_RESTARTS' => @$StylebcfgSrvcRestarts,

		'STYLE_BLOCK_ALERT' => $Style_bcfg_Alert,

		'TEST' => @$test,
	));
		if ( $CONFIG_SHTURMAN["Block_Col_Route"] ) { $template->assign_block_vars('row.col_route', array()); }
		if ( $CONFIG_SHTURMAN["Block_Col_PositionLost"] ) { $template->assign_block_vars('row.col_positionlost', array()); }


	if ( ! $Wagon ) // Block not connected to wagon
	{
		$template->assign_block_vars('row.wagonnotconnected', array(
		));
	}
	foreach ( $WagonDrivers[$BlockSerialNo] as $Driver )
	{
		//var_dump ($Driver);
		$WagonDriver = iconv("Windows-1251", "UTF-8", @$Driver["WagonDriver"]);
		$HID_SerialNo = iconv("Windows-1251", "UTF-8", @$Driver["HID_SerialNo"]);
		$HID_Battery = @$Driver["HID_Battery"];
		$HID_FWVer = @$Driver["HID_FWVer"];
		$DriverIcoType = "";
		$DriverIcoType = ( $Driver["DriverType"] =="Wagon" ) ? "-add" : $DriverIcoType;
		$DriverIcoType = ( $Driver["DriverType"] =="Coupling" ) ? "" : $DriverIcoType;
		$DriverIcoType = ( $Driver["DriverDeleted"] ) ? "-outline" : $DriverIcoType;
		
//		"Coupling" : "Wagon";
//	"DriverType" "DriverDeleted"
	
	
		$WagonDriverType = "";
		if ( $WagonDriver != "" )
		{
			$template->assign_block_vars('row.driver', array(
				'DRIVER_FIO' => $WagonDriver,
				'DRIVER_HID_ID' => $HID_SerialNo,
				'DRIVER_BAT' => $HID_Battery,
				'DRIVER_FW_VER' => $HID_FWVer,
				'DRIVER_ICO_TYPE' => $DriverIcoType
			));
		}

	}
	}
	// Алерт по работе сервиса

	$i++;
}

/*
$template->assign_vars(array(
	'CNT_LINUX_PAD' => @$BlockTypeCnt["LinuxPad"],
	'CNT_WINDOWS_PAD' => @$BlockTypeCnt["WindowsPad"],
	'CNT_UNCKNOWN' => @$BlockTypeCnt["Uncknown"],
	'CNT_OLIMEX' => @$BlockTypeCnt["Olimex"],

	));
*/
if ( $CONFIG_SHTURMAN["Block_VersionTypes"] )
{
	foreach ( $VersionsS as $item )
	{
		$Type = $item["Type"];
		$VerMin = $item["Min"];
		$VerMax = $item["Max"];
		$VerTotal = $item["CntTotal"];
		$VerMaxCnt = $item[$VerMax];
		$VerPercent = round($VerMaxCnt / $VerTotal * 100); // сколько блоков с последние версией в процентах;
		
		$template->assign_block_vars('version_types.ver', array(
			'TYPE' => $Type,
			'MIN' => $VerMin,
			'MAX' => $VerMax,
			'MAX_CNT' => $VerMaxCnt,
			'TOTAL' => $VerTotal,
			'PERCENT' => $VerPercent,
		));
	}
}
// Легенда
if ( !$NeedRepairing and !$NoLegend)
{
	$template->assign_block_vars('legend', array(
	));
}


// Update Block List in Diag DB

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );	

$SQL = "
SELECT [BlockSerialNo]
FROM [Servers]
--WHERE [ServerType_Guid] in ('F2585ED4-8D03-4E82-A895-3982E93B860C')
--WHERE [ServerType_Guid] in ('F2585ED4-8D03-4E82-A895-3982E93B860C', 'f63848b9-f300-4955-accd-afb8dc20de6a')
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$BlockListDiag[] = $row["BlockSerialNo"];
}


$NewBlocks = array_diff($BlockList, $BlockListDiag);
$NewBlocks = explode(";", implode(";", $NewBlocks)); 	// TODO Что это? зачем эта конструкция??? 

//var_dump($NewBlocks);

//$Linux = "";
//$Windows = "";

$i = 0;
while ( @$NewBlocks[$i] != "" )
{
//	echo "a";
	$BlockSerialNo = $NewBlocks[$i];

	if ( @$data[$BlockSerialNo]["BlockType"] == 'Linux' ) 
	{
		$Linux[] = "('$BlockSerialNo', '".$data[$BlockSerialNo]["IpAddress"]."',  'F2585ED4-8D03-4E82-A895-3982E93B860C')";
	}
	else
	{
		$Windows[] = "('$BlockSerialNo', '".$data[$BlockSerialNo]["IpAddress"]."',  'f63848b9-f300-4955-accd-afb8dc20de6a')";
	}
	$i++;
}

$SQLs = "
INSERT INTO [Servers]
           ([BlockSerialNo], [IpAddress], [ServerType_Guid])
     VALUES
		%%1%%
";

//echo "<pre>"; var_dump($Linux); echo "</pre>"; 
//echo "<pre>"; var_dump($Windows); echo "</pre>"; 


if ( count(@$Linux) )
{
	$SQL = str_replace('%%1%%', implode(", ", $Linux), $SQLs );

	echo "<pre>Linux:<br/>$SQL</pre>";

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	
}

if ( count(@$Windows) )
{
	$SQL = str_replace('%%1%%', implode(", ", $Windows), $SQLs );

	//echo "<pre>Windows:<br/>$SQL</pre>";
	
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	
}
///*/

sqlsrv_close($conn) ;


$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));

// Page generation time spent
$microTime = microtime(true) - $microTime;
$template->assign_vars(array(
	'SPENT_TIME' => $microTime,
));

$template->pparse('body');
?>