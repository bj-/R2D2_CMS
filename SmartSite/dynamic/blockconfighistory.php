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

$BlockSerNo = str_remove_sql_char(substr(@$_GET["BlockSerNo"], 0, 20));
$Properties = str_remove_sql_char(substr(@$_GET["property"], 0, 200));
$type = str_remove_sql_char(substr(@$_GET["type"], 0, 200));
//echo "<pre>".var_dump($_GET) . "</pre>";
$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

//$SQL = $SQL_QUERY["Persons"] . " AND [u].[Guid] = '$UserGuid'";
$PropertyList = explode(":", $Properties);

$PropertySql = implode("','", $PropertyList);

//exit;

if (! strlen($BlockSerNo) )
{
	echo "Paramerters are not specified!";
	exit;
}

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

$SQL = "
/****** Block configuration hostory (by block reports)  ******/
SELECT TOP 100000 
	[s].[BlockSerialNo],
	[sc].[ServerGuid],
	[sc].[PropertyName],
	[sc].[PropertyValue],
--	[sc].[Reported] AS [DateRAW],
--	FORMAT([sc].[Reported], 'yyy-MM-dd HH:mm') AS [DateSQL],
	CONVERT(varchar(27), [sc].[Reported]) AS [DateSQL],
	FORMAT([sc].[Reported], 'dd.MM.yyy HH:mm') AS [Date]
--	MIN([sc].[Reported]) AS [Started],
--	FORMAT(MIN([sc].[Reported]), 'dd.MM.yyy HH:mm') AS [Started],
--	MAX([sc].[Reported]) AS [Finished],
--	FORMAT(MAX([sc].[Reported]), 'dd.MM.yyy HH:mm') AS [Finished],
--	DATEDIFF(hour, MIN([sc].[Reported]), MAX([sc].[Reported])) AS [Total_Hours]
FROM [ServersConfig] AS [sc]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [sc].[ServerGuid]
WHERE 
	[s].[BlockSerialNo] = '$BlockSerNo'
	AND [sc].[PropertyName] IN ('$PropertySql')
--GROUP BY [s].[BlockSerialNo],
--	[sc].[PropertyName],
--	[sc].[PropertyValue]
ORDER BY [sc].[Reported] DESC
";

//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//echo "Step1b: [" . (microtime(true) - $microTime) . "] sec<br />";

$data = array();
$dates  = array();
$BlockGuid = '';

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//	$data[$row["ReportedDateTime"]][$row["PropertyName"]] = $row["PropertyValue"];

	// список дат
//	if ( !in_array($row["ReportedDateTime"], $datesList)  )
//	{
		$data[] = $row;
		$dates[] = $row["DateSQL"]; //["date"];
		$BlockGuid = @$row["ServerGuid"];
		//echo $row["ServiceName"];
//	}

//	$WagonList[$row["Wagon"]] = $row["BlockSerialNo"];
//	$data[$row["BlockSerialNo"]]= $row;
}

//var_dump($dates[0]);

if ( $BlockGuid != "" )
{ 
$SQL = "
SELECT 
	[PropertyValue],
	FORMAT([Reported], 'dd.MM.yyy HH:mm') AS [Date]
FROM [ServersConfig]
WHERE [ServerGuid] = '".$BlockGuid."' 
	AND PropertyName = 'SoftwareVer'
	AND [Reported] IN ('".implode("', '", $dates)."') 
";
//echo "<pre>$SQL</pre>";


$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$Version = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//	echo $row["Date"] . " - " . $row["PropertyValue"] . "</br>";
	$Version[$row["Date"]] = $row["PropertyValue"];
}
//echo "<pre>".var_dump($Version)."</pre>";
}
else
{
	$Version[@$row["Date"]] = "N/A";
}

sqlsrv_close($conn) ;

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'blockconfighistory.tpl')
);



$template->assign_vars(array(
	'PROPERTIES' => implode(", ", $PropertyList),
	'BLOCK_SER_NO' => $BlockSerNo,
	));

function srvcName2img ($string)
{
		
	$src = ["Tracking", "Bluegiga", "BlueGiga", "Cron", "Dios", "Hub", "Logic", "Math", "Modems", "Netmon", "Power", "Sound", "Udev", "Can"];
	$trg = [
		"<img src=\"/pic/ico/tool_24x24.png\" width=\"16\" height=\"16\" title=\"Tracking\">", 
		"<img src=\"/pic/ico/BlueTooth_1_24x24.png\" width=\"16\" height=\"16\" title=\"Bluegiga\">", 
		"<img src=\"/pic/ico/BlueTooth_1_24x24.png\" width=\"16\" height=\"16\" title=\"BlueGiga\">", 
		"<img src=\"/pic/ico/Task_128x128.png\" width=\"16\" height=\"16\" title=\"Cron \">", 
		"<img src=\"/pic/ico/display_24x24.png\" width=\"16\" height=\"16\" title=\"Dios\">", 
		"<img src=\"/pic/ico/data-storage_24x24.png\" width=\"16\" height=\"16\" title=\"Hub\">", 
		"<img src=\"/pic/ico/logic_24x24.png\" width=\"14\" height=\"14\" title=\"Logic\">", 
		"<img src=\"/pic/ico/calc_24x24.png\" width=\"14\" height=\"14\" title=\"Math\">", 
		"<img src=\"/pic/ico/phone_2_24x24.png\" width=\"14\" height=\"14\" title=\"Modem\">", 
		"<img src=\"/pic/ico/network_24x24.png\" width=\"14\" height=\"14\" title=\"Network\">", 
		"<img src=\"/pic/ico/poweron_20x20.png\" width=\"16\" height=\"16\" title=\"Power\">", 
		"<img src=\"/pic/ico/melody_24x24.png\" width=\"16\" height=\"16\" title=\"Sound\">", 
		"<img src=\"/pic/ico/usb_24x24.png\" width=\"16\" height=\"16\" title=\"Udev\">",
		"<img src=\"/pic/ico/rj45_24x24.png\" width=\"16\" height=\"16\" title=\"Tracking\">"
		];
	return str_replace($src, $trg, $string);
}
	
function parseBlockRepVersion ($Value, $type, $Compact)
{
//	$BlockCfg_SrvcRestarts = ( $PropertyValue ) ? $PropertyValue : "";
	$BlockCfg_SrvcRestartsArrR = explode(";", $Value);
//	$BlockCfg_SrvcRestartsArr = array();
//	$BlockCfg_SoftwareVerArr = array();
//	$MinRestarts = 100000000;
//	$MaxRestarts = 0;

	$valsR = array();
	$keysR = array();
	$valsV = array();
	$keysV = array();
	foreach ( $BlockCfg_SrvcRestartsArrR as $value )
	{
		$row = explode(":", $value);

// 		$BlockCfg_SrvcRestartsArr[] = $row[0] . ":" . @$row[2];
		$valsR[@$row[2]][] = $row[0];
		if ( !in_array(@$row[2], $keysR) ) { $keysR[] =  @$row[2]; };
		rsort($keysR);

		$valsV[@$row[1]][] = $row[0];
		if ( !in_array(@$row[1], $keysV) ) { $keysV[] =  @$row[1]; };
//		rsort($keysV);

//		$BlockCfg_SoftwareVerArr[] = $row[0] . ": " . @$row[1];
//		$y[@$row[1]][] = $row[0];
//		$MinRestarts = ( @$row[2] != 0 and @$row[2] < @$MinRestarts ) ? @$row[2] : $MinRestarts;
//		$MaxRestarts = ( @$row[2] > @$MaxRestarts ) ? @$row[2] : $MaxRestarts;
		
	}

	$SrvcRestarts	= '';
	$i = 0;
	while ( @$keysR[$i] ) 
	{
//		$SrvcRestarts .= $keysR[$i] . ": ";
		$SrvcRestarts .= '<td style="padding-left:10px;padding-right:5px;font-weight:bold;">' . $keysR[$i] . ':</td><td style="background-color:#CCCCCC;">';
		$n = 0;
		while ( @$valsR[$keysR[$i]][$n] )
		{
			$SrvcRestarts .= $valsR[$keysR[$i]][$n] . " ";
			$n++;
		}
		$i++;
	}
	$SrvcRestarts	= ( strlen($SrvcRestarts) > 5 ) ? '<table border="0" align="left"><tr>' . $SrvcRestarts . "</tr></table>" : "";

	$SoftwareVer	= '';
	$i = 0;
	while ( @$keysV[$i] ) 
	{
		$SoftwareVer .= '<td style="padding-left:10px;font-weight:bold;">' . $keysV[$i] . '</td><td style="background-color:#CCCCCC;">';
		$n = 0;
		while ( @$valsV[$keysV[$i]][$n] )
		{
			$SoftwareVer .= $valsV[$keysV[$i]][$n] . " ";
			$n++;
		}
		$SoftwareVer .= "</td>";
		$i++;
	}
	$SoftwareVer	= ( strlen($SoftwareVer) > 5 ) ? '<table border="0" align="left"><tr>' . $SoftwareVer . "</tr></table>" : "";

//	$SoftwareVer = ( strlen($SoftwareVer) > 5 ) ? substr($SoftwareVer ,0, strlen($SoftwareVer)-2) : "";

	if ( $Compact )
	{
		// 			"<img src=\"/pic/ico/tool_24x24.png\" width=\"16\" height=\"16\" title=\"Tracking\">", 
		/*
		$src = ["Tracking", "Bluegiga", "BlueGiga", "Cron", "Dios", "Hub", "Logic", "Math", "Modems", "Netmon", "Power", "Sound", "Udev", "Can"];
//		$trg = ["BG", "Cron", "Dios", "Hub", "Logc", "Math", "Mod", "Nen", "Pwr", "Snd", "Udv"];
		$trg = [
			"<img src=\"/pic/ico/tool_24x24.png\" width=\"16\" height=\"16\" title=\"Tracking\">", 
			"<img src=\"/pic/ico/BlueTooth_1_24x24.png\" width=\"16\" height=\"16\" title=\"Bluegiga\">", 
			"<img src=\"/pic/ico/BlueTooth_1_24x24.png\" width=\"16\" height=\"16\" title=\"BlueGiga\">", 
			"<img src=\"/pic/ico/Task_128x128.png\" width=\"16\" height=\"16\" title=\"Cron \">", 
			"<img src=\"/pic/ico/display_24x24.png\" width=\"16\" height=\"16\" title=\"Dios\">", 
			"<img src=\"/pic/ico/data-storage_24x24.png\" width=\"16\" height=\"16\" title=\"Hub\">", 
			"<img src=\"/pic/ico/logic_24x24.png\" width=\"14\" height=\"14\" title=\"Logic\">", 
			"<img src=\"/pic/ico/calc_24x24.png\" width=\"14\" height=\"14\" title=\"Math\">", 
			"<img src=\"/pic/ico/phone_2_24x24.png\" width=\"14\" height=\"14\" title=\"Modem\">", 
			"<img src=\"/pic/ico/network_24x24.png\" width=\"14\" height=\"14\" title=\"Network\">", 
			"<img src=\"/pic/ico/poweron_20x20.png\" width=\"16\" height=\"16\" title=\"Power\">", 
			"<img src=\"/pic/ico/melody_24x24.png\" width=\"16\" height=\"16\" title=\"Sound\">", 
			"<img src=\"/pic/ico/usb_24x24.png\" width=\"16\" height=\"16\" title=\"Udev\">",
			"<img src=\"/pic/ico/rj45_24x24.png\" width=\"16\" height=\"16\" title=\"Tracking\">"
			];
		*/
		//$SrvcRestarts = str_replace($src, $trg, $SrvcRestarts);
		//$SoftwareVer = str_replace($src, $trg, $SoftwareVer);
		$SrvcRestarts = srvcName2img ($SrvcRestarts);
		$SoftwareVer = srvcName2img ($SoftwareVer);
	}

	if ( strtoupper($type) == "VERSION" )
	{
		return $SoftwareVer;
	}
	else
	{
		return $SrvcRestarts;
	}
}

function parse_SrvcLastLogRecord ($string)
{
	global $style;
	
	$bcfg_LastLogRec = "";
	$bcfg_LastLogRecArr1 = explode(";", @$string );
	if ( $bcfg_LastLogRecArr1 )
	{
		foreach ( $bcfg_LastLogRecArr1 as $item )
		{
		$arr = explode(":", $item);
		$logActSrvcName = @$arr[0];
		$logActSrvcTimeF = @$arr[1];
		$logActSrvcTime = @$arr[2];

		$LogActSrvcMaxTime = ( $logActSrvcName == "Tracking" ) ? 600 : 300;
		//$S_LL = ( intval($logActSrvcTime) > $LogActSrvcMaxTime or $logActSrvcTime == "NO_LOG" or $logActSrvcTime < 0 ) ? $style["bg-l-red"] : "";
		$S_LL = ( intval($logActSrvcTime) > $LogActSrvcMaxTime or $logActSrvcTime == "NO_LOG" or $logActSrvcTime < 0 ) ? $style["bg-l-red"] : "";
		
		$str = "$logActSrvcName: <span style='$S_LL'>$logActSrvcTimeF</span>; ";
		$bcfg_LastLogRec .=  $str;
		//$bcfg_Alert_LastLog .= ( intval($logActSrvcTime) > 60 or intval($logActSrvcTime) < 0 or $logActSrvcTime == "NO_LOG" ) ? strip_tags($str) : ""; // строка подсказки для сумарных проблем с блоком
		}
		//$bcfg_Alert_LastLog = ( $bcfg_Alert_LastLog ) ? "Log Activity: $bcfg_Alert_LastLog" : "";
	}

	return srvcName2img($bcfg_LastLogRec);
}

function parse_SrvcQueueSizes ($string)
{
	global $style;
		// рабор статистики по очередям
	//$bcfg_Alert_Queue = "";
	$bcfg_QueuesStr = "";
	$bcfg_QueuesArr1 = explode(";", $string);
	if ( $string )
	{
		$bcfg_QueuesStr = "";
		foreach ( $bcfg_QueuesArr1 as $item )
		{
			$bcfg_QueuesArr2 = explode(":", $item);
			$bcfg_QueuesArr3 = explode("/", $bcfg_QueuesArr2[1]);
	
			$QueueSrvcName = @$bcfg_QueuesArr2[0];
			$QueueSrvcFrom = @$bcfg_QueuesArr3[0];
			$QueueSrvcTo = @$bcfg_QueuesArr3[1];

			$QueueSrvcMaxSize = ( $QueueSrvcName == "Hub" ) ? 10000 : 1000;
			
			//$S_Q = ( intval($QueueSrvcFrom) > $QueueSrvcMaxSize or intval($QueueSrvcTo) > $QueueSrvcMaxSize or $QueueSrvcFrom == "NO_QUEUE" ) ? $style["bg-l-red"] : "";
			$S_Q = ( intval($QueueSrvcFrom) > $QueueSrvcMaxSize or intval($QueueSrvcTo) > $QueueSrvcMaxSize or $QueueSrvcFrom == "NO_QUEUE" ) ? $style["bg-l-red"] : "";
			$str = "<span style='$S_Q'>$QueueSrvcName: $QueueSrvcFrom / $QueueSrvcTo</span>; ";
			$bcfg_QueuesStr .= $str;
			//$bcfg_Alert_Queue .= ( ($S_Q ) or ( $QueueSrvcFrom == "NO_QUEUE" or $QueueSrvcTo == "NO_QUEUE" ) ) ? strip_tags($str) : ""; // строка подсказки для сумарных проблем с блоком
		}
		//$bcfg_Alert_Queue = ( $bcfg_Alert_Queue ) ? "Queue: $bcfg_Alert_Queue" : ""; // строка подсказки для сумарных проблем с блоком
		$bcfg_Queues = $bcfg_QueuesStr;
	}
	$bcfg_QueuesStr = str_replace("NOT_APPLICABLE", "N/A", $bcfg_QueuesStr);
	
	return srvcName2img($bcfg_QueuesStr);
}

$i = 0;
$VersionSoftPrev = ''; //пред. версия
$VersionStyle = '';
$Finished = "";
$LastCorrectValue = "" ;
$CreatedLinesCnt = 0;
while ( @$data[$i] and $CreatedLinesCnt < 100 )
{
        $Date = $data[$i]["Date"];
//        $PropertyName = $data[$i]["PropertyName"];
        $PropertyValue = $data[$i]["PropertyValue"];
        $PropertyValueNext = @$data[$i+1]["PropertyValue"];
        $PropertyValuePrev = @$data[$i-1]["PropertyValue"];

	// parse property values
//echo $PropertySql[0];
	if ( $PropertyList[0] == "FirmwareEINKver" ) 	
	{
		$PropertyValueParsed = explode(";", $PropertyValue)[0];
		$PropertyValueParsed = ( $PropertyValueParsed == "N/A" ) ? $LastCorrectValue : $PropertyValueParsed;
//echo		$PropertyValueParsed = $PropertyValueParsed[0];
		$PropertyValueParsedNext = explode(";", $PropertyValueNext)[0];
		$PropertyValueParsedNext = ( $PropertyValueParsedNext == "N/A" ) ? $LastCorrectValue : $PropertyValueParsedNext;
		$PropertyValueParsedPrev = explode(";", $PropertyValuePrev)[0];
		$PropertyValueParsedPrev = ( $PropertyValueParsedPrev == "N/A" ) ? $LastCorrectValue : $PropertyValueParsedPrev;

		$LastCorrectValue = ( $PropertyValueParsed != "N/A" ) ? $PropertyValueParsed : $LastCorrectValue;

		// Конвертим вывод в HEX
		$PropertyValueParsed = dechex($PropertyValueParsed);
		$PropertyValueParsedNext = dechex($PropertyValueParsedNext);
		$PropertyValueParsedPrev = dechex($PropertyValueParsedPrev);

	}
	elseif ( $PropertyList[0] == "ConfigurrationPacket" ) 	
	{
		$PropertyValueParsed = ( $PropertyValue != "" ) ? @explode(";", $PropertyValue)[0] . " ( " . @explode(";", $PropertyValue)[1] . "  )" : "";
		$PropertyValueParsedNext = ( $PropertyValueNext != "" ) ?  @explode(";", $PropertyValueNext)[0] . " ( " . @explode(";", $PropertyValueNext)[1] . "  )" : "";
		$PropertyValueParsedPrev = ( $PropertyValuePrev != "" ) ?  @explode(";", $PropertyValuePrev)[0] . " ( " . @explode(";", $PropertyValuePrev)[1] . "  )" : "";
	}
	elseif ( $PropertyList[0] == "PacketInstalled" ) 	
	{
		$PropertyValueParsed = str_replace(";", "); ", $PropertyValue);
		$PropertyValueParsed = str_replace("|", " (", $PropertyValueParsed);
		$PropertyValueParsedNext = str_replace(";", "); ", $PropertyValueNext);
		$PropertyValueParsedNext = str_replace("|", " (", $PropertyValueParsedNext);
		$PropertyValueParsedPrev = str_replace(";", "); ", $PropertyValuePrev);
		$PropertyValueParsedPrev = str_replace("|", " (", $PropertyValueParsedPrev);

       		$src = ["shturman-files ", "shturman-silent", "shturman-config-spbmetro3thline", "shturman-config-spbmetro4thline", "shturman", "(20"];
//		$trg = ["BG", "Cron", "Dios", "Hub", "Logc", "Math", "Mod", "Nen", "Pwr", "Snd", "Udv"];
		$trg = ["fls", "slnt", "c3s", "c4s", "sh", "("];

		$PropertyValueParsed = str_replace($src, $trg, $PropertyValueParsed);
		$PropertyValueParsedNext = str_replace($src, $trg, $PropertyValueParsedNext);
		$PropertyValueParsedPrev = str_replace($src, $trg, $PropertyValueParsedPrev);

		
	}
	elseif ( $PropertyList[0] == "SoftwareVer" && strtoupper($type) == "SERVICESSTARTS" )
	{
		$PropertyValueParsed = parseBlockRepVersion ($PropertyValue, "restarts", TRUE );
		$PropertyValueParsed = ( $PropertyValueParsed == "" ) ? $LastCorrectValue : $PropertyValueParsed;
		$PropertyValueParsedPrev = parseBlockRepVersion ($PropertyValuePrev, "restarts", TRUE );
		$PropertyValueParsedPrev = ( $PropertyValueParsedPrev == "" ) ? $LastCorrectValue : $PropertyValueParsedPrev;

		$LastCorrectValue = ( $PropertyValueParsed != "" ) ? $PropertyValueParsed : $LastCorrectValue;

		$PropertyValueParsedNext = parseBlockRepVersion ($PropertyValueNext, "restarts", TRUE );
		$PropertyValueParsedNext = ( $PropertyValueParsedNext == "" ) ? $LastCorrectValue : $PropertyValueParsedNext;

		$LastCorrectValue = ( $PropertyValueParsed != "" ) ? $PropertyValueParsed : $LastCorrectValue;
	}
	elseif ( $PropertyList[0] == "SoftwareVer" ) 	
	{
/*

		$PropertyValueParsed = ( $PropertyValue != "" ) ? @explode(";", $PropertyValue)[0] . " ( " . @explode(";", $PropertyValue)[1] . "  )" : "";
		$PropertyValueParsedNext = ( $PropertyValueNext != "" ) ?  @explode(";", $PropertyValueNext)[0] . " ( " . @explode(";", $PropertyValueNext)[1] . "  )" : "";
		$PropertyValueParsedPrev = ( $PropertyValuePrev != "" ) ?  @explode(";", $PropertyValuePrev)[0] . " ( " . @explode(";", $PropertyValuePrev)[1] . "  )" : "";
*/
		$PropertyValueParsed = parseBlockRepVersion ($PropertyValue, "version", TRUE );
		$PropertyValueParsed = ( $PropertyValueParsed == "" ) ? $LastCorrectValue : $PropertyValueParsed;

		$PropertyValueParsedNext = parseBlockRepVersion ($PropertyValueNext, "version", TRUE );
		$PropertyValueParsedNext = ( $PropertyValueParsedNext == "" ) ? $LastCorrectValue : $PropertyValueParsedNext;
		$PropertyValueParsedPrev = parseBlockRepVersion ($PropertyValuePrev, "version", TRUE );
		$PropertyValueParsedPrev = ( $PropertyValueParsedPrev == "" ) ? $LastCorrectValue : $PropertyValueParsedPrev;

		$LastCorrectValue = ( $PropertyValueParsed != "" ) ? $PropertyValueParsed : $LastCorrectValue;


	}
	elseif ( $PropertyList[0] == "SrvcLastLogRecord" )
	{
		$PropertyValueParsed = parse_SrvcLastLogRecord($PropertyValue);
		$PropertyValueParsedNext = parse_SrvcLastLogRecord($PropertyValueNext);
		$PropertyValueParsedPrev = parse_SrvcLastLogRecord($PropertyValuePrev);
	}
	elseif ( $PropertyList[0] == "SrvcQueueSizes" )
	{
		$PropertyValueParsed = parse_SrvcQueueSizes($PropertyValue);
		$PropertyValueParsedNext = parse_SrvcQueueSizes($PropertyValueNext);
		$PropertyValueParsedPrev = parse_SrvcQueueSizes($PropertyValuePrev);
	}
	elseif ( $PropertyList[0] == "UpTimeSeconds" )
	{
		//$PropertyValueParsed = $PropertyValue;
		$PropertyValueParsed = sprintf("%s:%'02s:%'02s\n", intval($PropertyValue/60/60), abs(intval(($PropertyValue%3600) / 60)), abs($PropertyValue%60));
		//$PropertyValueParsedNext = $PropertyValueNext;
		$PropertyValueParsedNext = sprintf("%s:%'02s:%'02s\n", intval($PropertyValueNext/60/60), abs(intval(($PropertyValueNext%3600) / 60)), abs($PropertyValueNext%60));
		//$PropertyValueParsedPrev = $PropertyValuePrev;
		$PropertyValueParsedPrev = sprintf("%s:%'02s:%'02s\n", intval($PropertyValuePrev/60/60), abs(intval(($PropertyValuePrev%3600) / 60)), abs($PropertyValuePrev%60));
	}
	else
	{
		$PropertyValueParsed = $PropertyValue;
		$PropertyValueParsedNext = $PropertyValueNext;
		$PropertyValueParsedPrev = $PropertyValuePrev;
	}

	$VersionSoft = @explode(";", $Version[$Date]);
	$VersionSoft = explode(":", $VersionSoft[0]);
	$VersionSoft = @$VersionSoft[1];
	$VersionStyle = ( $VersionSoft != $VersionSoftPrev and $VersionSoft != "" and $VersionSoftPrev != "" ) ? $style["color-red"] . $style["bold"] : $VersionStyle;

        $Started = "";
        $Finished = ( $Finished == "" ) ? $Date : $Finished;
        $Total_Hours = "";

//	$VersionSoft = $data[$i]["SoftwareVer"];
//	$VersionPack = $data[$i]["PacketInstalled"];
/*
*/
	if ( $PropertyValueParsed != $PropertyValueParsedPrev )
//	if ( TRUE )
	{
		$template->assign_block_vars('row', array(
			'DATE' => $Date,
	//		'PROPERTY' => $PropertyName,
			'VALUE' => $PropertyValueParsed,
//			'VALUE' => $PropertyValueParsed . "[$PropertyValue]",
//			'FROM' => $Started,
			'TO' => $Date,
			'HOURS' => $Total_Hours,
			'VERSION_SOFT' => $VersionSoft,
			'VERSION_STYLE' => $VersionStyle,
//			'XXX' => $VersionStyle,
	//		'VERSION_PACKAGE' => $VersionPack,
		));
		$VersionStyle = "";
		$CreatedLinesCnt++;
	}

	if ( $PropertyValueParsed != $PropertyValueParsedNext )
	{
		$Started = $Date;

		$dateS = new DateTime($Started);
		$dateF = new DateTime($Finished);
		$diff = date_diff($dateS, $dateF);
		$diff = ( $diff->format('%a') ) ? $diff->format('%a') . " d " . $diff->format('%h') . " h" : $diff->format('%h');
		
//		echo $date->getTimestamp();
		$Finished = "";

		$template->assign_block_vars('row.datefrom', array(
//			'FROM' => "d".$Started . " = [" . $PropertyValueParsed ."]-[". $PropertyValueParsedPrev."]-[".$PropertyValueParsedNext."]",
			'FROM' => $Started,
//			'FROM' => $Started,
			'HOURS' => $diff,
//			'TO' => $Finished,
		));
	}
	
	$VersionSoftPrev = $VersionSoft;

	$i++;
}

$template->pparse('body');












exit;

echo "<table>
	<tr>
		<th>Date</th>
		<!--th>Name</th-->
		<th>BOVI</th>
		<th>Route</th>
		<th>Servers Pool</th>
		<th>IPAddress</th>
		<th>Packets</th>
		<th>Config Pack</th>
	</tr>
";

$i = 0;
while ( @$datesList[$i] )
{
	$date = $datesList[$i];

	$BlockSerialNo = $data[$datesList[$i]]["BlockSerialNo"];
	$Hostname = $data[$datesList[$i]]["Hostname"];
	$BlockName = ( $BlockSerialNo == $Hostname ) ? $BlockSerialNo : "$BlockSerialNo / $Hostname";

	$BlockOrientation = $data[$datesList[$i]]["BlockOrientation"];
	$FirmwareEINKver = $data[$datesList[$i]]["FirmwareEINKver"];
//	$FirmwareEINKver = 
	$FirmwareEINKverVer = substr($FirmwareEINKver, 0, strpos($FirmwareEINKver, ";"));
	$FirmwareEINKverVerHex = ( $FirmwareEINKverVer != "" ) ? strtoupper(dechex($FirmwareEINKverVer)) : "";
	$FirmwareEINKverVerShort = ( $FirmwareEINKverVer != "" ) ? " " . substr($FirmwareEINKverVerHex, 5) : "";
//	$BlockCfg_FirmwareEINKverVerShort = ( ( $BlockCfg_FirmwareEINKverVer == "" || $BlockCfg_FirmwareEINKverVer == "N/A" ) && $BlockCfg_Orientation != "" ) ? " ??" : $BlockCfg_FirmwareEINKverVerShort;

	
	$BaseRoute = $data[$datesList[$i]]["BaseRoute"];
	$CurrentRoute = $data[$datesList[$i]]["CurrentRoute"];
	$MaxRoute = $data[$datesList[$i]]["MaxRoute"];

	$ServersPool = $data[$datesList[$i]]["ServersPool"];
	$ServersPool = str_replace("192.168.51.","*.", $ServersPool);
	$PacketInstalled = $data[$datesList[$i]]["PacketInstalled"];
	$PacketInstalled = str_replace("shturman","sh",$PacketInstalled);
	$PacketInstalled = str_replace("config-spbmetro4thline","cfg-4s ",$PacketInstalled);
	$PacketInstalled = str_replace(";","); ",$PacketInstalled);
	$PacketInstalled = str_replace("|"," (",$PacketInstalled);

	$ServicesRunning = $data[$datesList[$i]]["ServicesRunning"];
	$ServicesStopped = $data[$datesList[$i]]["ServicesStopped"];
	$ConfigurrationPacket = $data[$datesList[$i]]["ConfigurrationPacket"];
	$ConfigurrationPacket = str_replace("shturman","sh",$ConfigurrationPacket);
	$ConfigurrationPacket = str_replace("config-spbmetro4thline","cfg-4s ",$ConfigurrationPacket);
	$ConfigurrationPacket = str_replace(";"," (",$ConfigurrationPacket);
	$ConfigurrationPacket .= ( $ConfigurrationPacket != "" ) ? ")" : "";

	$IPAddress = $data[$datesList[$i]]["IPAddress"];
	$IPAddress = str_replace("192.168.2.1;", "", $IPAddress);
	$IPAddress = str_replace("192.168.2.1", "", $IPAddress);
	$IPAddress = str_replace("10.110.", "*0.", $IPAddress);
	$IPAddress = str_replace("10.168.", "*8.", $IPAddress);
	$SoftwareVer = $data[$datesList[$i]]["SoftwareVer"];

	echo "<tr>
			<td>$date</td>
			<!--td>$BlockName</td-->
			<td>$BlockOrientation $FirmwareEINKverVerShort</td>
			<td>$BaseRoute/$CurrentRoute/$MaxRoute</td>
			<td>$ServersPool</td>
			<td>$IPAddress</td>
			<td>$PacketInstalled</td>
			<td>$ConfigurrationPacket</td>
		</tr>";
	$i++;
}
echo "</table>";

/*

Hostname
BlockOrientation
BaseRoute
CurrentRoute
MaxRoute
ServersPool
PacketInstalled
ServicesRunning
ServicesStopped
ConfigurrationPacket
IPAddress
FirmwareEINKver
SoftwareVer
*/

?>