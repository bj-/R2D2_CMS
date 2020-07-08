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


// Get params from url
$AlertOnly = ( @$_GET["AlertOnly"] ) ? TRUE : FALSE;
$ShowHeader = ( @$_GET["ShowHeader"] ) ? TRUE : FALSE;
//$BlockSerNo = str_remove_sql_char(substr(@$_GET["BlockSerNo"], 0, 20));

$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

$SQL = "
/****** Состояние меток станций  ******/
SELECT
	--[sl].[Guid],
	--[sl].[Stations_Guid],
	[sn].[Guid] AS [Sensor_Guid],
	[l].[Line_Number],
	[l].[Name] AS [Line_Name],
	[st].[Name] AS [Station_Name],
	[sn].[SerialNo],
	[sn].[Name],
	[sn].[MacAddress],
	[sn].[FW_Version],
	[sn].[Battery_Level],
	--[sn].[Battery_Time],
	FORMAT([sn].[Battery_Time], 'dd.MM.yyy') AS [Battery_Time],
	DATEDIFF(day,[sn].[Battery_Time], SYSUTCDATETIME() ) AS [Battery_Time_DayAgo],
	--[sn].[Activity], -- Поле не заполняется
	--FORMAT([sn].[Activity], 'dd.MM.yyy') AS [Activity],
	--DATEDIFF(day,[sn].[Activity], SYSUTCDATETIME() ) AS [Activity_DayAgo],
	--(SELECT TOP 1 MAX([Happend]) FROM [Events_Sensors_1] AS [es1] WHERE [sl].[Guid] = [es1].[Sensors_Guid]) AS [Last_WeekActivity],
	--FORMAT((SELECT TOP 1 MAX([Happend]) FROM [Events_Sensors_1] AS [es1] WHERE [sl].[Guid] = [es1].[Sensors_Guid]), 'dd.MM.yyy') AS [ActivityInWeek],
--	IIF (DATEDIFF(day,(SELECT TOP 1 MAX([Happend]) FROM [Events_Sensors_1] AS [es1] WHERE [sl].[Guid] = [es1].[Sensors_Guid]), SYSUTCDATETIME() ) IS NOT NULL, '1', NULL) AS [Activity_AtLastWeek],
	IIF ((select top 1 ss.Written FROM Stages_Sensors as ss where ss.Sensors_Guid = [sl].[Guid] AND ss.Written > DATEADD(day, -7, SYSUTCDATETIME())) IS NOT NULL, 1, NULL) AS [Activity_AtLastWeek],
	FORMAT((select top 1 ss.Written FROM Stages_Sensors as ss where ss.Sensors_Guid = [sl].[Guid] ORDER BY ss.Written DESC), 'dd.MM.yyy') AS [LastActivity],
	DATEDIFF(minute, (select top 1 ss.Written FROM Stages_Sensors as ss where ss.Sensors_Guid = [sl].[Guid] ORDER BY ss.Written DESC), SYSUTCDATETIME()) AS [LastActivityMinAgo],
	--[st].Lines_Guid--
	[st].[OrderNo] AS [Station_OrderNo],
	[st].[Stations_Types_Id] AS [Station_Types_Id],  
	IIF ([st].[Stations_Types_Id] = 1, 
		IIF ([l].[Line_Number] = 4, 
			IIF ([sl].[WayNo] = 1, 
				'Спасская', 
				IIF ([sl].[WayNo] = 2, 'Дыбенко', NULL )
				), 
			IIF ([l].[Line_Number] = 3, 
				IIF ([sl].[WayNo] = 1, 
					'Рыбацкое', 
					IIF ([sl].[WayNo] = 2, 'Беговая', NULL )
					), 
				NULL
				)
			), 
			NULL
		) AS [Way_Direction],
	[sl].[WayNo],
	[sl].[TxPwr],
	[sl].[TxPwr_Required],
	--[sl].[TxPwr_Changed],
	FORMAT([sl].[TxPwr_Changed], 'dd.MM.yyy') AS [TxPwr_Changed]
FROM [Sensors_Labels] AS [sl]
LEFT JOIN [Stations] AS [st] ON [st].[Guid] = [sl].[Stations_Guid]
LEFT JOIN [Lines] AS [l] ON [l].[Guid] = [st].[Lines_Guid]
LEFT JOIN [Sensors] AS [sn] ON [sn].[Guid] = [sl].[Guid]
--LEFT JOIN [Events_Sensors_1] AS [es1] ON [es1].[Sensors_Guid] = [sl].[Guid]
WHERE 1=1
	AND [l].[Name] IS NOT NULL
	AND ( [sn].[FW_Version] IS NOT NULL OR [sl].[TxPwr_Required] IS NOT NULL )
	--AND [sn].[FW_Version] IS NOT NULL
	--AND [l].[Line_Number] = 3
	--[sl].[TxPwr] = 5
ORDER BY 
	[st].[Stations_Types_Id] ASC, 
	[l].[Line_Number] ASC,
	[st].[OrderNo] ASC

";
//echo "<pre>$SQL</pre>"; 

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$data = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[]= $row;
}

// echo "<pre>"; var_dump($data); echo "</pre>"; 


sqlsrv_close($conn) ;


$currentdate = date("Y-m-d", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));

$Header = ( $ShowHeader ) ? "Метки" : "";

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'labels.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'HEADER' => $Header,

	));

/*
	$template->assign_block_vars('row', array(
		'L_LINK' => $l_Link,
	));

*/


$i = 0;
while ( @$data[$i] )
{
	$SensorGuid = $data[$i]["Sensor_Guid"];

	$Line_Number = $data[$i]["Line_Number"];
	$Line_Name = iconv("Windows-1251", "UTF-8", $data[$i]["Line_Name"]);
	$Station_Name = iconv("Windows-1251", "UTF-8", $data[$i]["Station_Name"]);
	$SerialNo = $data[$i]["SerialNo"];
	$Name = $data[$i]["Name"];
	$MacAddress = $data[$i]["MacAddress"];
	$FW_Version = $data[$i]["FW_Version"];
	$Battery_Level = $data[$i]["Battery_Level"];
	$Battery_Time = $data[$i]["Battery_Time"];
	$Battery_Time_DayAgo = $data[$i]["Battery_Time_DayAgo"];
	$Activity_AtLastWeek = $data[$i]["Activity_AtLastWeek"];
	$LastActivity = $data[$i]["LastActivity"];
	$LastActivityMinAgo = $data[$i]["LastActivityMinAgo"];

	$LastActivityTimeAgoStr = ($LastActivityMinAgo > 59) ? round(($LastActivityMinAgo / 60),0) . "h" : $LastActivityMinAgo . "m";
	$LastActivityTimeAgoStr = ($LastActivityMinAgo >= (60*24)) ? round(($LastActivityMinAgo / (60*24)),0) . "d" : $LastActivityTimeAgoStr;
	$LastActivityTimeAgoStr = ($LastActivityMinAgo >= (60*24*30)) ? round(($LastActivityMinAgo / (60*24*30)),0) . "M" : $LastActivityTimeAgoStr;
	
	
	$Station_OrderNo = $data[$i]["Station_OrderNo"];
	$Station_Types_Id = $data[$i]["Station_Types_Id"];
	$Station_Type = ( $data[$i]["Station_Types_Id"] == 1 ) ? "Станция" : "";
	$Station_Type = ( $data[$i]["Station_Types_Id"] == 2 ) ? "Депо" : $Station_Type;
	$Station_Type = ( $data[$i]["Station_Types_Id"] == 3 ) ? "ПТО" : $Station_Type;
	$Station_Type = ( $data[$i]["Station_Types_Id"] == 4 ) ? "Тупик" : $Station_Type;
	$Way_Direction = iconv("Windows-1251", "UTF-8", $data[$i]["Way_Direction"]);
	$Way_Direction = $data[$i]["Way_Direction"];
	$WayNo = $data[$i]["WayNo"];
	$TxPwr = $data[$i]["TxPwr"];
	$TxPwr_Required = trim($data[$i]["TxPwr_Required"]);
	$TxPwr_Changed = $data[$i]["TxPwr_Changed"];

	$Style_Label_Died = ( $Activity_AtLastWeek == "" ) ? $style["bg-l-red"] : "";
	$Style_Label_BAT = ( $Battery_Level <= 70 ) ? $style["bg-l-red"] : "";
	$Style_Label_TxPwr = ( $TxPwr_Required != $TxPwr ) ? $style["bg-l-red"] : "";

	if ( ($AlertOnly and ($Style_Label_Died or $Style_Label_BAT or $Style_Label_TxPwr)) or !$AlertOnly )
	{

//	$StyleBgRow = ( $i % 2 == 0 ) ? "" : $style["bg-limelight"];

	$template->assign_block_vars('row', array(
		'SENSOR_GUID' => $SensorGuid,
		'LINE_NUMBER' => $Line_Number,
		'LINE_NAME' => $Line_Name,
		'STATION_NAME' => $Station_Name,
		'SERIALNO' => $SerialNo,
		'NAME' => $Name,
		'MAC' => $MacAddress,
		'FWVERSION' => $FW_Version,
		'BAT' => $Battery_Level,
		'BAT_TIME' => $Battery_Time,
		'BAT_TIME_AGO' => $Battery_Time_DayAgo,
		'ACTIVED' => $Activity_AtLastWeek,
		'LAST_ACTIVITY' => $LastActivity,
		'LAST_ACTIVITY_AGO' => $LastActivityTimeAgoStr,
		
		'SQL_DATE_NOW' => date("Y-m-d"),
		'SQL_DATE_3MAGO' => date("Y-m-d", (time() - (90 * 24 * 60 * 60)) ),
		'SQL_DATE_6MAGO' => date("Y-m-d", (time() - (180 * 24 * 60 * 60)) ),

		
		'STATION_ORDERNO' => $Station_OrderNo,
//		'STATION_TYPE_ID' => $Station_Types_Id,
		'STATION_TYPE' => $Station_Type,
		'WAY_DIRECTION' => $Way_Direction,
		'WAY_NO' => $WayNo,
		'TXPOWER' => $TxPwr,
		'TXPOWER_REQUIRED' => $TxPwr_Required,
		'TXPOWER_CHANGED' => $TxPwr_Changed,

		'STYLE_LABEL_DIED' => $Style_Label_Died,
		'STYLE_LABEL_BAT' => $Style_Label_BAT,
		'STYLE_LABEL_TXPWR' => $Style_Label_TxPwr,

	));
	}
	$i++;
}

if ( !$AlertOnly )
{
	$template->assign_block_vars('legend', array(
	));
}

$template->pparse('body');
?>