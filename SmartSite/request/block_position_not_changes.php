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

//var_dump (@$_GET);

$bp["wagon_in_train_not_connected_warning"] = 5760; // minutes
$bp["wagon_in_train_not_connected"] = 2880; // minutes
$bp["wagon_in_train_not_connected_light"] = 720; // minutes
$bp["wagon_in_train_not_connected_Diff"] = 300; // minutes (Disconnected time Difference betwen wagons in train )
$bp["wagon_alone_not_connected"] = 4320; // minutes
$bp["wagon_left_connected"] = 10; // блоки пропавшие со связи менее [минут] назад.
$bp["position_changed_time"] = 10; // блоки не менявшие местоположение более [минут].
$bp["position_changed_time_reboot"] = 20; // блоки не менявшие местоположение более [минут].
$bp["svc_working_time_diff"] = 3600; // Разница во времени работы сервисов на блоках [секунд].

$rnd = rand ( 0 , 1000000000 );

function compact_station_name($StationName)
{
	$StationName = str_replace("улица", "ул.", $StationName );
	$StationName = str_replace("Александра Невского", "А.Невского", $StationName );
	$StationName = str_replace(Array("проспект","Проспект"), "пр.", $StationName );
	return $StationName;
}


$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );


$SQL = "
/****** Blocks list  ******/
SELECT
	[s].[Alias] AS [BlockSerialNo],
	[v].[Name] AS [Wagon],
	[c].[Name] AS [Train],
	-- AS [StationName]
--	[c].[Route],
--	[v].[WayNo],
	[st].[Name] AS [StationName],
	[st].[Stations_Types_Id], -- тип станции: 1 обычная; другое - депо/пто и пр не станции
--	[l].[Name] AS [LineName],
--	[v].[Position_Changed] AS [PosChangedTime],
	FORMAT(DATEADD(hour,3,[v].[Position_Changed]), 'dd.MM.yyy HH:mm:ss') AS [PosChangedTime],
	FORMAT(DATEADD(hour,3,[v].[Position_Changed]), 'yyy-MM-dd HH:mm:ss.0000000') AS [PosChangedTimeSQL],
	DATEDIFF(MINUTE, [v].[Position_Changed], getutcdate()) AS [PosChangedTimeAgo],
--	[c].[Route_Changed] AS [RouteChangedTime],
--	FORMAT(DATEADD(hour,3,[c].[Route_Changed]), 'dd.MM.yyy HH:mm') AS [RouteChangedTime],
--	DATEDIFF(MINUTE, [c].[Route_Changed], getutcdate()) AS [RouteChangedTimeAgo],
--	[c].[Users_Guid] AS [CouplingChangeByUser],
--	[p].[Last_Name] AS [CouplingChangeByUser],
	[s].[Is_Connected],
	[s].[IpAddress],
--	[ud].[Guid],
--	CONCAT([p].[Last_Name],' ',[p].[First_Name],' ',[p].[Middle_Name]) AS [CouplingDriver],
--	CONCAT([pd].[Last_Name],' ',[pd].[First_Name],' ',[pd].[Middle_Name]) AS [WagonDriver],
--	[sn].[SerialNo] AS [HID_SerialNo],
--	[sn].[Battery_Level] AS [HID_Battery],
--	[sn].[FW_Version] AS [HID_FWVer],
--	[s].[Changed],
--	FORMAT([s].[Changed], 'dd.MM HH:mm') AS [dConnected]
	FORMAT(DATEADD(hour,3,[s].[Changed]), 'dd.MM HH:mm') AS [dChanged],
	FORMAT(DATEADD(hour,3,[s].[Changed]), 'dd.MM.yyy HH:mm') AS [dChangedFull],
	FORMAT(DATEADD(hour,3,[s].[Changed]), 'yyy-MM-dd HH:mm:ss.0000000') AS [dChangedFullSQL],
	DATEDIFF(MINUTE,[s].[Changed], getutcdate()) AS [ConnTimeAgo]
FROM [Servers] AS [s]
	LEFT JOIN [Vehicles] AS [v] ON [v].[Guid] = [s].[Vehicles_Guid]
	LEFT JOIN [Couplings] AS [c] ON [c].[Guid] = [v].[Couplings_Guid]
	LEFT JOIN [Stations] AS [st] ON [st].[Guid] = [v].[Stations_Guid]
--	LEFT JOIN [Lines] AS [l] ON [l].[Guid] = [st].[Lines_Guid]
--	LEFT JOIN [Users] AS [u] ON [u].[Guid] = [c].[Users_Guid]
--	LEFT JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
--	LEFT JOIN [Users] AS [ud] ON [ud].[Vehicles_Guid] = [v].[Guid]
--	LEFT JOIN [Users_Persons] AS [pd] ON [pd].[Guid] = [ud].[Users_Persons_Guid]
--	LEFT JOIN [Sensors_Cardio] AS [sc] ON [sc].[Users_Guid] = [ud].[Guid]
--	LEFT JOIN [Sensors] AS [sn] ON [sn].[Guid] = [sc].[Guid]
WHERE
	[s].[Alias] LIKE 'STB%'
	AND [s].[Alias] NOT IN ('STB008626', 'STB2278')
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
//	$WagonDrivers[$row["BlockSerialNo"]][] = array("WagonDriver" => trim($row["WagonDriver"]), "HID_SerialNo" => $row["HID_SerialNo"], 
//							"HID_Battery" => $row["HID_Battery"], "HID_FWVer" => $row["HID_FWVer"]);
//	$data[]= $row;
}
//echo "<pre>"; var_dump($WagonList); echo "</pre>";
//echo "<pre>"; var_dump($data); echo "</pre>";
//echo "<pre>"; var_dump($WagonDrivers); echo "</pre>";

sqlsrv_close($conn) ;



$currentdate = date("Y-m-d", time());
$currenttime = date("H:i:s", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));

//var_dump(
//echo date("H:i:s", localtime(time())); 
//if (time() >= strtotime("10:08:10")) 

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'request/block_position_not_changes.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTTIME' => $currenttime,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,


	));



$BadBlocks = Array();

$i = 0;
while ( @$BlockList[$i] )
{

 	$BlockSerialNo = $BlockList[$i]; //iconv("Windows-1251", "UTF-8", $data[$i]["BlockSerialNo"]);
	$BlockSerialNoShort = str_replace("STB0", "", $BlockSerialNo);
	$BlockSerialNoShort = str_replace("STB", "", $BlockSerialNoShort);
	$Wagon = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["Wagon"]);
	$Train = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["Train"]);
	$WagonSecond = str_replace($Wagon,"", $Train);
	$WagonSecond = str_replace("-","", $WagonSecond);
//	$WagonSecond = ($WagonSecond) ? "-$WagonSecond" : "";
	$BlockSerialNoSecond = ( $WagonSecond != "" ) ? @$WagonList[$WagonSecond] : "" ;


	$Is_Connected = $data[$BlockSerialNo]["Is_Connected"];
	$Is_Connected_Second = @$data[$BlockSerialNoSecond]["Is_Connected"];
	$Connected = $data[$BlockSerialNo]["dChanged"];
	$ConnectedSecond = @$data[$BlockSerialNoSecond]["dChanged"];
	$ConnectedFull = $data[$BlockSerialNo]["dChangedFull"];
	$ConnectedFullSQL = $data[$BlockSerialNo]["dChangedFullSQL"];
	$ConnectedSecondFull = @$data[$BlockSerialNoSecond]["dChangedFull"];
	$ConnectedSecondFullSQL = @$data[$BlockSerialNoSecond]["dChangedFullSQL"];
	$ConnTimeAgo = $data[$BlockSerialNo]["ConnTimeAgo"];
	$ConnTimeAgoSecond = @$data[$BlockSerialNoSecond]["ConnTimeAgo"];
	$ConnTimeAgoDIFF = abs($ConnTimeAgo-$ConnTimeAgoSecond);
//	$ConnTimeAgoDIFF = $ConnTimeAgo-$ConnTimeAgoSecond;

	$StationName = iconv("Windows-1251", "UTF-8", $data[$BlockSerialNo]["StationName"]);
	$StationNameSecond = ( $WagonSecond != "" ) ? @iconv("Windows-1251", "UTF-8", $data[$BlockSerialNoSecond]["StationName"]) : "";
	$StationNameIdentical = ( $StationName == $StationNameSecond ) ? TRUE : FALSE;

	$Stations_Types_Id = $data[$BlockSerialNo]["Stations_Types_Id"];
	$Stations_Types_Id_Second = @$data[$BlockSerialNoSecond]["Stations_Types_Id"];
	$PosChangedTime = $data[$BlockSerialNo]["PosChangedTime"];
	$PosChangedTimeSecond = @$data[$BlockSerialNoSecond]["PosChangedTime"];
	$PosChangedTimeSQL = $data[$BlockSerialNo]["PosChangedTimeSQL"];
	$PosChangedTimeSQLSecond = @$data[$BlockSerialNoSecond]["PosChangedTimeSQL"];
	$PosChangedTimeAgo = $data[$BlockSerialNo]["PosChangedTimeAgo"];
	$PosChangedTimeAgoSecond = @$data[$BlockSerialNoSecond]["PosChangedTimeAgo"];
	$PosChangedTimeAgoActual = ( $Is_Connected ) ? $PosChangedTimeAgo : ($PosChangedTimeAgo - $ConnTimeAgo) ;
	$PosChangedTimeAgoActualSecond = ( $Is_Connected_Second ) ? $PosChangedTimeAgoSecond : ($PosChangedTimeAgoSecond - $ConnTimeAgoSecond) ;
	
	$TEMP_PosIsPrimorskayaX3Alert = ( $StationName == "Приморская" and ($PosChangedTimeAgo < $bp["position_changed_time"]*3) ) ? TRUE : FALSE;
	
/*	$PosChangedAlert = ( $Train and $PosChangedTimeAgo > $bp["position_changed_time"] and 
				//$Is_Connected and 
				$Stations_Types_Id == 1 and ($PosChangedTimeAgo - $PosChangedTimeAgoSecond) > $bp["position_changed_time"]
				) ? TRUE : FALSE;
*/
	$PosChangedAlert = ( $Train 
				and $PosChangedTimeAgo > $bp["position_changed_time"] 
				and $Stations_Types_Id == 1 and ($PosChangedTimeAgo - $PosChangedTimeAgoSecond) > $bp["position_changed_time"]
				and $Stations_Types_Id_Second == 1
				and ( ($PosChangedTimeAgo - $ConnTimeAgo) > $bp["position_changed_time"] or $Is_Connected ) // выходил на связь, но так и не обновил станцию.
				and !$StationNameIdentical
				and !$TEMP_PosIsPrimorskayaX3Alert // TODO костыль для приморской. там поезда не видят станции 21-25 минут
				//$Is_Connected and 
				) ? TRUE : FALSE;

	// В ребут отправляем только тех у кого в 2 раза больше разница во времени между станциями
	$PosChangedAlertReboot = ( $PosChangedAlert and ($PosChangedTimeAgo - $PosChangedTimeAgoSecond) > $bp["position_changed_time_reboot"] and $Is_Connected ) ? TRUE : FALSE;

	//$PosChangedTimeAgoStr = ($PosChangedTimeAgo > 59) ? round(($PosChangedTimeAgo / 60),0) . "h" : $PosChangedTimeAgo . "m";
	$PosChangedTimeAgoStr = ($PosChangedTimeAgoActual > 59) ? round(($PosChangedTimeAgoActual / 60),0) . "h" : $PosChangedTimeAgoActual . "m";
	$PosChangedTimeAgoStr = ($PosChangedTimeAgoActual >= (60*24)) ? round(($PosChangedTimeAgoActual / (60*24)),0) . "d" : $PosChangedTimeAgoStr;
	$PosChangedTimeAgoStr = ($PosChangedTimeAgoActual >= (60*24*30)) ? round(($PosChangedTimeAgoActual / (60*24*30)),0) . "M" : $PosChangedTimeAgoStr;

	$PosChangedTimeAgoStrSecond = ($PosChangedTimeAgoActualSecond > 59) ? round(($PosChangedTimeAgoActualSecond / 60),0) . "h" : $PosChangedTimeAgoActualSecond . "m";
	$PosChangedTimeAgoStrSecond = ($PosChangedTimeAgoActualSecond >= (60*24)) ? round(($PosChangedTimeAgoActualSecond / (60*24)),0) . "d" : $PosChangedTimeAgoStrSecond;
	$PosChangedTimeAgoStrSecond = ($PosChangedTimeAgoActualSecond >= (60*24*30)) ? round(($PosChangedTimeAgoActualSecond / (60*24*30)),0) . "M" : $PosChangedTimeAgoStrSecond;

	// Styles
	// Row bg connection style
	$StyleConnected = "";
	$StyleConnected = ( $Is_Connected ) ? $style["bg-lll-green"] : $StyleConnected; // На связи
	$StyleConnected = ( $ConnTimeAgo < $bp["wagon_left_connected"] and ! $Is_Connected ) ? $style["bg-lllll-green"] : $StyleConnected; // ушел со связи совсем недавно
	$StyleConnected = ( $ConnTimeAgo > $bp["wagon_alone_not_connected"] and ! $Train ) ? $style["color-dark-red"] : $StyleConnected; // вагон отцеплен и ушел связи
	// в сцепке, но ОБА вагона не на связи
	$StyleConnected = ( $ConnTimeAgo > $bp["wagon_alone_not_connected"] and $ConnTimeAgoDIFF < $bp["wagon_in_train_not_connected_Diff"] and $Train != "" ) ? $style["color-dark-red"] : $StyleConnected; // вагон отцеплен и ушел связи

	$StyleConnectedSecond = "";
	$StyleConnectedSecond = ( $Is_Connected_Second ) ? $style["bg-lll-green"] : $StyleConnectedSecond; // На связи
	$StyleConnectedSecond = ( $ConnTimeAgo < $bp["wagon_left_connected"] and ! $Is_Connected_Second ) ? $style["bg-lllll-green"] : $StyleConnectedSecond; // ушел со связи совсем недавно
	$StyleConnectedSecond = ( $ConnTimeAgo > $bp["wagon_alone_not_connected"] and ! $Train ) ? $style["color-dark-red"] : $StyleConnectedSecond; // вагон отцеплен и ушел связи
	// в сцепке, но ОБА вагона не на связи
	$StyleConnectedSecond = ( $ConnTimeAgo > $bp["wagon_alone_not_connected"] and $ConnTimeAgoDIFF < $bp["wagon_in_train_not_connected_Diff"] and $Train != "" ) ? $style["color-dark-red"] : $StyleConnectedSecond; // вагон отцеплен и ушел связи
	//$Is_Connected_Second
	
	// в сцепке, но ТОЛЬКО ЭТОТ вагон не на связи ( мало | долго | очень долго )
	$StyleConnectedAlert = "";
	$StyleConnectedAlert = ( $ConnTimeAgo > $ConnTimeAgoSecond and $Train != "" and $ConnTimeAgoDIFF > $bp["wagon_in_train_not_connected_Diff"] and $ConnTimeAgo > $bp["wagon_in_train_not_connected_light"] ) ? $style["bg-lllll-red"] : $StyleConnectedAlert;
	$StyleConnectedAlert = ( $ConnTimeAgo > $ConnTimeAgoSecond and $Train != "" and $ConnTimeAgoDIFF > $bp["wagon_in_train_not_connected_Diff"] and $ConnTimeAgo > $bp["wagon_in_train_not_connected"] ) ? $style["bg-lll-red"] : $StyleConnectedAlert;
	$StyleConnectedAlert = ( $ConnTimeAgo > $ConnTimeAgoSecond and $Train != "" and $ConnTimeAgoDIFF > $bp["wagon_in_train_not_connected_Diff"] and $ConnTimeAgo > $bp["wagon_in_train_not_connected_warning"] ) ? $style["bg-l-red"] : $StyleConnectedAlert;

	// Не менял местоположение длительное время
/*	$StylePositionAlert = ( $Train and $PosChangedTimeAgo > $bp["position_changed_time"] and 
				//$Is_Connected and 
				$Stations_Types_Id == 1 and ($PosChangedTimeAgo - $PosChangedTimeAgoSecond) > $bp["position_changed_time"]
				) ? $style["bg-l-red"] : "";
*/
	$StylePositionAlert = ( $PosChangedAlert ) ? $style["bg-l-red"] : "";

	if ( $StylePositionAlert and ! $StyleConnectedAlert )
	{
		if ( $PosChangedAlertReboot ) { $toRebootBlock[] = $BlockSerialNo; }
		$SQL_WagonSecond = ( strlen($BlockSerialNoSecond) ) ? "'$BlockSerialNoSecond'" : "NULL";
		$SQL_ConnectedSecondFull = ( strlen($ConnectedSecondFullSQL) ) ? "'$ConnectedSecondFullSQL'" : "NULL";
		$SQL_StationNameSecond = ( strlen($StationNameSecond) ) ? "'$StationNameSecond'" : "NULL";
		$SQL_PosChangedTimeSQLSecond = ( strlen($PosChangedTimeSQLSecond) ) ? "'$PosChangedTimeSQLSecond'" : "NULL";
		$SQLPosChangedTimeAgoSecond = ( strlen($PosChangedTimeAgoSecond) ) ? "'$PosChangedTimeAgoSecond'" : "NULL";
		$SQL_PosChangedTimeAgoStrSecond = ( strlen($PosChangedTimeAgoStrSecond) ) ? "'$PosChangedTimeAgoStrSecond'" : "NULL";
		$BadBlocks[] = "'$BlockSerialNo','$ConnectedFullSQL','$StationName','$PosChangedTimeSQL','$PosChangedTimeAgoActual','$PosChangedTimeAgoStr',sysutcdatetime(),$SQL_WagonSecond,$SQL_ConnectedSecondFull,$SQL_StationNameSecond,$SQL_PosChangedTimeSQLSecond,$SQLPosChangedTimeAgoSecond,$SQL_PosChangedTimeAgoStrSecond";
		$template->assign_block_vars('row', array(
			'BLOCKSERIALNO' => $BlockSerialNo,
			'BLOCK' => $BlockSerialNoShort,
			'SECOND_WAGON' => $WagonSecond,
			'TRAIN' => $Train,
			
			'CONNECTED' => $ConnectedFull,
			'CONNECTED_SECOND' => $ConnectedSecondFull,
	
			'STATION_NAME' => compact_station_name($StationName),
			'STATION_NAME_SECOND' => compact_station_name($StationNameSecond),
			'POSITION_CHANGED_TIME' => $PosChangedTime,
			'POSITION_CHANGED_TIME_SQL' => $PosChangedTimeSQL,
			'POSITION_CHANGED_TIME_AGO' => $PosChangedTimeAgo,
			'POSITION_CHANGED_TIME_AGO_FORMATED' => $PosChangedTimeAgoStr,
			'POSITION_CHANGED_TIME_AGO_ALERT_FORMATED' => ( $PosChangedAlert ) ? "($PosChangedTimeAgoStr)" : "",
	
			'POSITION_CHANGED_SECOND_TIME' => $PosChangedTimeSecond,
			'POSITION_CHANGED_SECOND_TIME_SQL' => $PosChangedTimeSQLSecond,
			'POSITION_CHANGED_SECOND_TIME_AGO' => $PosChangedTimeAgoSecond,
			'POSITION_CHANGED_SECOND_TIME_AGO_FORMATED' => $PosChangedTimeAgoStrSecond,
//			'POSITION_CHANGED_SECOND_TIME_AGO_ALERT_FORMATED' => ( $PosChangedAlertSecond ) ? "($PosChangedTimeAgoStrSecond)" : "",
	
	
			// Styles
			'STYLE_CONNECTED' => $StyleConnected,
			'STYLE_CONNECTED_SECOND' => $StyleConnectedSecond,
			'STYLE_CONNECTED_ALERT' => $StyleConnectedAlert,
			'STYLE_POSITION_CHANGE_ALERT' => $StylePositionAlert,
	
			'TEST' => @$test,
		));
	}
	$i++;
}

//echo "<pre>";var_dump($BadBlocks);echo "</pre>";


if ( count($BadBlocks) )
{
	$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );	
	$SQL = 
"merge [BlockPositionChangeHistory] as target
using (values
	(" . implode("),\n	(", $BadBlocks) . ")
        ) as source (
			[ServerName],       [Connected],        [PositionName],        [LastChanged],        [TimeAgo],        [TimeAgoFormated],     [Written], 
			[Second_ServerName],[Second_Connected],	[Second_PositionName], [Second_LastChanged], [Second_TimeAgo], [Second_TimeAgoFormated])
on target.[ServerName] = source.[ServerName] AND target.[LastChanged] = source.[LastChanged]
when matched then update
	set 
		target.[Connected] = source.[Connected],
		target.[LastChanged] = source.[LastChanged],
		target.[TimeAgo] = source.[TimeAgo],
		target.[TimeAgoFormated] = source.[TimeAgoFormated],
		target.[Written] = source.[Written],
		target.[Second_ServerName] = source.[Second_ServerName],
		target.[Second_Connected] = source.[Second_Connected],
		target.[Second_PositionName] = source.[Second_PositionName],
		target.[Second_LastChanged] = source.[Second_LastChanged],
		target.[Second_TimeAgo] = source.[Second_TimeAgo],
		target.[Second_TimeAgoFormated] = source.[Second_TimeAgoFormated]

when not matched then insert 
    ([ServerName], [Connected], [PositionName], [LastChanged],[TimeAgo],[TimeAgoFormated],
	[Second_ServerName],[Second_Connected],	[Second_PositionName], [Second_LastChanged], [Second_TimeAgo], [Second_TimeAgoFormated])
values (
			source.[ServerName],
			source.[Connected],
			source.[PositionName],
			source.[LastChanged],
			source.[TimeAgo],
			source.[TimeAgoFormated],
			source.[Second_ServerName],
			source.[Second_Connected],
			source.[Second_PositionName],
			source.[Second_LastChanged],
			source.[Second_TimeAgo],
			source.[Second_TimeAgoFormated]
			);
";
/*
ALTER TABLE dbo.[BlockPositionChangeHistory] ADD [Second_ServerName] [nvarchar](100) NULL; 
ALTER TABLE dbo.[BlockPositionChangeHistory] ADD [Second_Connected] [datetime2](7) NULL; 
ALTER TABLE dbo.[BlockPositionChangeHistory] ADD [Second_PositionName] [nvarchar](100) NULL; 
ALTER TABLE dbo.[BlockPositionChangeHistory] ADD [Second_LastChanged] [datetime2](7) NULL; 
ALTER TABLE dbo.[BlockPositionChangeHistory] ADD [Second_TimeAgo] [int] NULL; 
ALTER TABLE dbo.[BlockPositionChangeHistory] ADD [Second_TimeAgoFormated] [nvarchar](20) NULL; 


	$SQL = 
" merge [BlockPositionChangeHistory] as target
 using (values
	(" . implode("),\n	(", $BadBlocks) . ")
        ) as source ([ServerName], [PositionName], [LastChanged],[TimeAgo],[TimeAgoFormated],[Written])
  on target.[ServerName] = source.[ServerName] AND target.[LastChanged] = source.[LastChanged]
  when matched then update
    set target.[LastChanged] = source.[LastChanged],  target.[TimeAgo] = source.[TimeAgo],  target.[TimeAgoFormated] = source.[TimeAgoFormated], target.[Written] = source.[Written]
  when not matched then insert 
    ([ServerName], [PositionName], [LastChanged],[TimeAgo],[TimeAgoFormated])
  values (source.[ServerName], source.[PositionName], source.[LastChanged], source.[TimeAgo], source.[TimeAgoFormated]);
";

*/

//	echo "<pre>$SQL</pre>";

	$SQL = iconv("UTF-8","Windows-1251", $SQL);

	MSSQLsiletnQuery($SQL);

	sqlsrv_close($conn) ;
}


if ( 1 )
{
	$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );	
	// create reboot task if block did not change position a lot of time
	if ( @count($toRebootBlock) > 0 )
	//if ( 1 )
	{
		//$toRebootBlock[] = "STB0001";
		//$toRebootBlock[] = "STB0007";
		//$toRebootBlock[] = "STB0045";

		// Get Action Guid
		$SQL = "SELECT [Guid], [ActionType] FROM [Actions] where ActionType = 'reboot'";

		$stmt = sqlsrv_query( $conn, $SQL );
		if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$ActionGuid = $row["Guid"];
		}		

		// Get User Guid
		$SQL = "SELECT TOP 1 [Guid], [UserName] FROM [Users] WHERE [UserName] = 'php'";

		$stmt = sqlsrv_query( $conn, $SQL );
		if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$UserGuid = $row["Guid"];
		}		

		// Get User Guid
		$SQL = "SELECT TOP 1 [Guid] ,[StatusId] FROM [Statuses] WHERE [StatusId] = 'active'";
		//$SQL = "SELECT TOP 1 [Guid] ,[StatusId] FROM [Statuses] WHERE [StatusId] = 'new'";

		$stmt = sqlsrv_query( $conn, $SQL );
		if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$TaskStatusGuid = $row["Guid"];
		}		

		//Get Exist tasks ( дабы не плодить множество тасок на один блок)
		$SQL = "SELECT [Server_Guid] AS [Guid] FROM [Instructions] WHERE 
([TaskStatus_Guid] = '$TaskStatusGuid' AND [Action_Guid] = '$ActionGuid')
OR [Changed] > DATEADD(minute, -60, sysdatetime())
";

		$stmt = sqlsrv_query( $conn, $SQL );
		if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$TaskExistList[] = $row["Guid"];
		}		



		// BlockList
		$SQL = "SELECT [Guid], [BlockSerialNo] FROM [Servers]";

		$stmt = sqlsrv_query( $conn, $SQL );
		if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$BlockList[$row["BlockSerialNo"]] = $row["Guid"];
		}		
		
		//echo "<pre>"; var_dump($toRebootBlock); echo "</pre>";

//		$SQLInsert = "";
		foreach ( $toRebootBlock as $item )
		{
			$BlockGuid = ( @$BlockList[$item] ) ? $BlockList[$item] : "00000000-0000-0000-0000-000000000000";

			//echo "<pre>"; var_dump($TaskExistList); echo "</pre>";
			
			if ( !@in_array($BlockGuid, @$TaskExistList) )
			{
				$InsertArr[] = "\n('$TaskStatusGuid', '$BlockGuid', '$ActionGuid', '$UserGuid')";
			}
		}

		if ( @count($InsertArr) > 0 )
		{
			$SQL = "INSERT INTO [Instructions] 
	([TaskStatus_Guid], [Server_Guid], [Action_Guid], [User_Guid])
VALUES " . implode(",", $InsertArr );

			$SQL = iconv("UTF-8","Windows-1251", $SQL);
			//echo "<pre>$SQL</pre>";

			MSSQLsiletnQuery($SQL);

		}
	}
	sqlsrv_close($conn) ;

}

//	MSSQLsiletnQuery($SQL);



// Легенда
if ( FALSE )
{
	$template->assign_block_vars('legend', array(
	));
}

$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));

// Page generation time spent
$microTime = microtime(true) - $microTime;
$template->assign_vars(array(
	'SPENT_TIME' => $microTime,
));

$template->pparse('body');
?>