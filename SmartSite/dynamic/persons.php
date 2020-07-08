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
$ShowAlert = substr(@$_GET["ShowAlert"], 0, 20);

$rnd = rand ( 0 , 1000000000 );

//$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );
$conn = MSSQLconnect( "SpbMetro-sRoot", "Shturman" );

$SQL_QUERY["List"] = "
SELECT 
	[p].[Last_Name],
	[p].[First_Name],
	[p].[Middle_Name],
	[sn].[SerialNo] AS [SensorSerialNo],
	[sn].[MacAddress],
	[u].[Guid],
	--[u].[Vehicles_Guid],
	--[u].[Users_Roles_Guid],
	[ur].[Name] AS [UserRole],
	[u].[Is_Active],
	[v].[Name] AS [Wagon],
	[s].[Alias] AS [BlockSerialNo],
	[u].[Deleted] AS [Fired],
	FORMAT(MAX([ex].[ExaminationDateTime]), 'dd.MM.yyy HH:mm:ss') AS [LastMedExam]
FROM [Users] AS [u]
INNER JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
LEFT JOIN [Vehicles] AS [v] ON [v].[Guid] = [u].[Vehicles_Guid]
LEFT JOIN [Sensors_Cardio] AS [sc] ON [sc].[Users_Guid] = [u].[Guid]
LEFT JOIN [Sensors] AS [sn] ON [sn].[Guid] = [sc].[Guid]
LEFT JOIN [Servers] AS [s] ON [s].[Guid] = [sc].Servers_Guid
LEFT JOIN [Users_Roles] AS [ur] ON [ur].[Guid] = [u].[Users_Roles_Guid]
LEFT JOIN [Sensors_Exam] AS [ex] on [ex].[Users_Guid] = [u].[Guid]

GROUP BY
	[p].[Last_Name],
	[p].[First_Name],
	[p].[Middle_Name],
	[sn].[SerialNo],
	[sn].[MacAddress],
	[u].[Guid],
	[ur].[Name],
	[u].[Is_Active],
	[v].[Name],
	[s].[Alias],
	[u].[Deleted]
";
/*
SELECT 
	[p].[Last_Name],
	[p].[First_Name],
	[p].[Middle_Name],
	[sn].[SerialNo] AS [SensorSerialNo],
	[u].[Guid],
	--[u].[Vehicles_Guid],
	--[u].[Users_Roles_Guid],
	[ur].[Name] AS [UserRole],
	[u].[Is_Active],
	[v].[Name] AS [Wagon],
	[s].[Alias] AS [BlockSerialNo]
	
FROM [Users] AS [u]
INNER JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
LEFT JOIN [Vehicles] AS [v] ON [v].[Guid] = [u].[Vehicles_Guid]
LEFT JOIN [Sensors_Cardio] AS [sc] ON [sc].[Users_Guid] = [u].[Guid]
LEFT JOIN [Sensors] AS [sn] ON [sn].[Guid] = [sc].[Guid]
LEFT JOIN [Servers] AS [s] ON [s].[Guid] = [sc].Servers_Guid
LEFT JOIN [Users_Roles] AS [ur] ON [ur].[Guid] = [u].[Users_Roles_Guid]
";
*/

$SQL = $SQL_QUERY["List"] . "
ORDER BY 
	[p].[Last_Name],
	[p].[First_Name],
	[p].[Middle_Name]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$dataFIO[]= $row;
}

$SQL = $SQL_QUERY["List"] . "
ORDER BY 
	[sn].[SerialNo] ASC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$dataSen[] = $row;
}

//=================================  LIST  =====================

$SQL = $SQL_QUERY["Persons"] . "
ORDER BY
	[p].[Last_Name] ASC,
	[p].[First_Name] ASC,
	[p].[Middle_Name] ASC

";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$maxFW = 0;

$data = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
	$maxFW = ( $maxFW < $row["HID_FW"] ) ? $row["HID_FW"] : $maxFW;
}

//echo "<pre>";var_dump($dataSen);echo "</pre>";

$SQL ="
/***** Users Groups *****/
SELECT 
	[ug].[Users_Guid],
	--[ug].[Groups_Guid],
	[g].[Code],
	[g].[Name]
FROM [Users_In_Groups] AS [ug]
INNER JOIN [Groups] AS [g] ON [g].[Guid] = [ug].[Groups_Guid]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	//$UserGroups[$row["Users_Guid"]][] = Array("CroupCode" => iconv("Windows-1251", "UTF-8", $row["Code"]), "CroupName" => iconv("Windows-1251", "UTF-8", $row["Name"]));
	$UserGroups[$row["Users_Guid"]][] =  iconv("Windows-1251", "UTF-8", $row["Name"]);
}
//echo "<pre>";var_dump($UserGroups);echo "</pre>";

$SQL ="
/******** Last Medical exam  **********/
SELECT 
	[Users_Guid],
	FORMAT(MAX([ExaminationDateTime]), 'dd.MM.yyy HH:mm:ss') AS [LastMedExam]
FROM [Sensors_Exam]
GROUP BY
	[Users_Guid]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	//$UserGroups[$row["Users_Guid"]][] = Array("CroupCode" => iconv("Windows-1251", "UTF-8", $row["Code"]), "CroupName" => iconv("Windows-1251", "UTF-8", $row["Name"]));
	$exams[$row["Users_Guid"]] =  $row["LastMedExam"];
}
//echo "<pre>";var_dump($UserGroups);echo "</pre>";

	$SQL = "
/****** Потеря 90% зарада гарнитурой менее чем за 8 часов  ******/
SELECT 
	FIO, 
	[SerialNo],
	[UserGuid],
	format(CAST(AVG(CAST([Working Time] AS FLOAT)) AS DATETIME),'HH:mm:ss') AS [AvgWorkingTime] , 
	datediff(minute,0, (CAST(AVG(CAST([Working Time] AS FLOAT)) AS DATETIME))) AS [AvgWorkingTimeMinutes] , 
	count(*) AS [Count] 
FROM (
select top 10000000
	CONCAT([p].[Last_Name], ' ', [p].[First_Name], ' ', [p].[Middle_Name]) AS [FIO],
	[u].[Guid] AS [UserGuid],
	[s].[SerialNo],
	--[sbj].[BatteryLevel],
	MAX([sbj].[Battery_Level]) AS [MAXBat],
	MIN([sbj].[Battery_Level]) AS [MinBat],
	format([sbj].[Written] ,'yyy.MM.dd') AS [Date],
	format(MIN([sbj].[Written]),'HH:mm:ss') AS [StartTime],
	format(MAX([sbj].[Written]),'HH:mm:ss') AS [FinishTime],
	--dateadd(SECOND,MAX([sbj].[Changed]),MIN([sbj].[Changed]))
	--format(dateadd(SECOND, DATEDIFF(SECOND, MIN([sbj].[Written]), MAX([sbj].[Written])), ''),'HH:mm:ss') AS [Working Time1],
	--format(dateadd(SECOND, DATEDIFF(SECOND, MIN([sbj].[Written]), MAX([sbj].[Written])), ''),'HH:mm:ss') AS [Working Time1],
	dateadd(SECOND, DATEDIFF(SECOND, MIN([sbj].[Written]), MAX([sbj].[Written])), '') AS [Working Time]
--	DATEDIFF(SECOND, MIN([sbj].[Written]), MAX([sbj].[Written])) AS [Working Time]
	--DATEDIFF(MAX([sbj].[Changed])-MIN([sbj].[Changed]))
	-- [sbj].[Changed]
	--, *
FROM [Stages_Battery] AS [sbj]
INNER JOIN [Sensors_Cardio] as [sc] ON [sbj].[Sensors_Guid] = [sc].[Guid]
INNER JOIN [users] AS [u] ON [sc].[Users_Guid] = [u].[Guid]
INNER JOIN [Users_Persons] AS [p] ON [u].[Users_Persons_Guid] = [p].[Guid]
INNER JOIN [Sensors] AS [s] ON [s].[Guid] = [sbj].[Sensors_Guid]

where 1=1
	--[p].[Last_Name] like '%реев%'
	--[s].[SerialNo] = 'STH00-388'
	AND [sbj].[Written] >= DATEADD(day, -30, getdate())
	-- SerialNo = 'STH00-122'
	--and [sbj].[Battery_Level] = '100'
Group BY format([sbj].[Written] ,'yyy.MM.dd'), [p].[Last_Name], [p].[First_Name], [p].[Middle_Name], [u].[guid], [s].[SerialNo]
ORDER BY 
	[p].[Last_Name] ASC,
	[Date] DESC
) as t

where
	MAXBat = 100 
	and MinBat < 10
	and [Working Time] < '08:00:00'
	--and [Count] > 2
--
group by FIO, UserGuid, [SerialNo]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	//$UserGroups[$row["Users_Guid"]][] = Array("CroupCode" => iconv("Windows-1251", "UTF-8", $row["Code"]), "CroupName" => iconv("Windows-1251", "UTF-8", $row["Name"]));
	$BATs[$row["UserGuid"]] = $row;
}

	$SQL = "
SELECT 
	[u].[Guid] AS [UserGuid],
	CONCAT([p].[Last_Name], ' ',[p].[First_Name], ' ',[p].[Middle_Name]) AS [FIO],
	--[u].[Users_Persons_Guid]
	count (*) AS [Cnt],
	(SELECT [Sensors] = STUFF((
SELECT '; ' + [sn2].[SerialNo]
FROM [Sensors_Cardio] AS [sc2]
INNER JOIN [Sensors] AS [sn2] ON [sn2].[Guid] = [sc2].[Guid]
WHERE [sc2].[Users_Guid] = [u].[Guid]
FOR XML PATH('')), 1, 2,'')) AS [Sensors]
	--(select Se)
FROM [Users] AS [u]
INNER JOIN [Sensors_Cardio] AS [sc] ON [sc].[Users_Guid] = [u].[Guid]
INNER JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
--WHERE count(*) > 1
GROUP BY [u].[Guid], [p].[Last_Name], [p].[First_Name], [p].[Middle_Name]
HAVING COUNT(*) > 1
ORDER BY [FIO] ASC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	//$UserGroups[$row["Users_Guid"]][] = Array("CroupCode" => iconv("Windows-1251", "UTF-8", $row["Code"]), "CroupName" => iconv("Windows-1251", "UTF-8", $row["Name"]));
	$MultiHID[$row["UserGuid"]] = $row;
}

	$SQL = "
	/****** Последний день когда использовалась 13-я версия прошивки гарнитуры *****/
SELECT top 1000
	[boi].[Users_Guid],
	FORMAT(MAX([boi].[Calculated]), 'dd.MM.yyy') AS [LastDate_FW13]
	--MAX([boi].[Calculated]) as [LastDate2]
FROM [Users_BOINorms] AS [boi]
WHERE BOI_MD_Avg < 100
GROUP BY [Users_Guid]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	//$UserGroups[$row["Users_Guid"]][] = Array("CroupCode" => iconv("Windows-1251", "UTF-8", $row["Code"]), "CroupName" => iconv("Windows-1251", "UTF-8", $row["Name"]));
	$HIDLastDateFW13[$row["Users_Guid"]] = $row["LastDate_FW13"];
}

	$SQL = "
/****** Какие гарнитуры были у пользователя  ******/
SELECT DISTINCT TOP 1000 
	[sld].[Users_Guid],
	--CONCAT ([p].[Last_Name], ' ', [p].[First_Name], ' ', [p].[Middle_Name]) AS [FIO],
	[sn].[SerialNo] AS [HID]
	--MAX([sld].[Disconnected])
	--[Sensors_Guid]
FROM [Stages_Logic_Drv] AS [sld]
INNER JOIN [Sensors] AS [sn] ON [sn].[Guid] = [sld].[Sensors_Guid]
INNER JOIN [Users] AS [u] ON [u].[Guid] = [sld].[Users_Guid]
INNER JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
--GROUP BY [sld].[Users_Guid], [sn].[SerialNo]
--ORDER BY [FIO]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	//$UserGroups[$row["Users_Guid"]][] = Array("CroupCode" => iconv("Windows-1251", "UTF-8", $row["Code"]), "CroupName" => iconv("Windows-1251", "UTF-8", $row["Name"]));
	$UsersHIDs[$row["Users_Guid"]][] = $row["HID"];
}



sqlsrv_close($conn) ;

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

	$SQL = "
/****** Статистика по качеству сигнала получаемого с датчиков по дням (среднее/мин/макс/кол-во  ******/
SELECT *
from 
(
	select
		[User_Guid] AS [UserGuid],
		--[FIO],
		--[HID],
		FORMAT([Date], 'dd.MM.yyy') AS [Date],
		[AvgQuality],
		[MinQuality],
		[MaxQuality],
		[Count],
		[AvgQuality30d],
		[Count30d],
		RANK() OVER   
			(PARTITION BY [HID] ORDER BY [Date] DESC) AS Rank  
			FROM [Shturman3Diag].[dbo].[Person_HIDQualityStat]
	WHERE [Count] > 100
) r
 where 
   rank =1
";
//echo "<pre>$SQL</pre>";


$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	//$UserGroups[$row["Users_Guid"]][] = Array("CroupCode" => iconv("Windows-1251", "UTF-8", $row["Code"]), "CroupName" => iconv("Windows-1251", "UTF-8", $row["Name"]));
	$QualityArr[$row["UserGuid"]] = $row;
}
//echo "<pre>";var_dump($QualityArr);echo "</pre>";


sqlsrv_close($conn) ;

//var_dump($dataSen);

// sqlsrv_close($conn) ;

$currentdate = date("Y-m-d", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));
$currentdateNext = date("Y-m-d", time()+(24*60*60));

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'persons.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'CURRENTDATENEXT' => $currentdateNext,

	));

/*
	$template->assign_block_vars('row', array(
		'L_LINK' => $l_Link,
	));

*/

$i = 0;

while ( @$dataFIO[$i] )
{
	$LastName  = iconv("Windows-1251", "UTF-8", $dataFIO[$i]["Last_Name"]);
	$FirstName = iconv("Windows-1251", "UTF-8", $dataFIO[$i]["First_Name"]);
	$MiddleName = iconv("Windows-1251", "UTF-8", $dataFIO[$i]["Middle_Name"]);
	$SensorSerNo = $dataFIO[$i]["SensorSerialNo"];
	$UserGuid = $dataFIO[$i]["Guid"];

	$template->assign_block_vars('row_person', array(
		'GUID' => $UserGuid,
		'LASTNAME' => $LastName,
		'FIRSTNAME' => $FirstName,
		'MIDDLENAME' => $MiddleName,
		'SENSORSERNO' => $SensorSerNo,
	));

//	echo "<option value=\"$UserGuid\">$LastName $FirstName $MiddleName ($SensorSerNo)</option>";
//	echo $LastName . " " . $FirstName . " " . $MiddleName . "<br />";
	$i++;
}

$i = 0;
while ( @$dataSen[$i] )
{
	$LastName   = iconv("Windows-1251", "UTF-8", $dataSen[$i]["Last_Name"]);
	$FirstName  = iconv("Windows-1251", "UTF-8", $dataSen[$i]["First_Name"]);
	$MiddleName = iconv("Windows-1251", "UTF-8", $dataSen[$i]["Middle_Name"]);
	$SensorSerNo = $dataSen[$i]["SensorSerialNo"];
	$UserGuid = $dataSen[$i]["Guid"];

	$template->assign_block_vars('row_sensor', array(
		'GUID' => $UserGuid,
		'LASTNAME' => $LastName,
		'FIRSTNAME' => $FirstName,
		'MIDDLENAME' => $MiddleName,
		'SENSORSERNO' => $SensorSerNo,
	));

//	echo $LastName . " " . $FirstName . " " . $MiddleName . "<br />";
	$i++;
}

// Общий список 

$HID_FW_Stat = Array();
$HID_FW_List = Array();

$i = 0;
//while ( @$data[$i]["Last_Name"] )
while ( @$data[$i] )
{
	/*
	[ust].[Name] AS [User_State], 
	[u].[Users_States_Changed],
	[u].[Created] AS [Registered],
	[sn].[Battery_Level],
	[u].[Vehicles_Changed]
	*/
	//$UserGuid = $data[$i]["Guid"];
	$Guid = $data[$i]["Guid"];

	$Person_Guid = $data[$i]["Person_Guid"];
	$Sensor_Guid = $data[$i]["Sensor_Guid"];
	
	$LastName = iconv("Windows-1251", "UTF-8", $data[$i]["Last_Name"]);
	$First_Name = iconv("Windows-1251", "UTF-8", $data[$i]["First_Name"]);
	$Middle_Name = iconv("Windows-1251", "UTF-8", $data[$i]["Middle_Name"]);

	$Role = iconv("Windows-1251", "UTF-8", $data[$i]["Role"]);
	$RoleCode = $data[$i]["Role_Code"];
	$S_Role = ( $RoleCode == "Worker" ) ? $style["bg-ll-yellow"] : "";

	$Groups = @$UserGroups[$Guid];
	$GroupsCompact = @implode(";",$Groups);
	$GroupsCompact = ( $GroupsCompact == "ТЧ-5;Линия 3" ) ? "3s (5)" : $GroupsCompact;
	$GroupsCompact = ( $GroupsCompact == "ТЧ-5;Линия 4" ) ? "4s (5)" : $GroupsCompact;
	$GroupsCompact = ( $GroupsCompact == "ТЧ-3;Линия 4" ) ? "4s (3)" : $GroupsCompact;
	$GroupsCompact = ( $GroupsCompact == "" ) ? "----" : $GroupsCompact;
//	$line

//	$GroupsCompact = "==";

	//$Groups = implode("/", $UserGroups[$UserGuid]);
	
	$HID_SerNo = iconv("Windows-1251", "UTF-8", $data[$i]["HID"]);
	$HID_Connected = $data[$i]["HID_Connected"];

	$HID_History = @implode("; ", $UsersHIDs[$Guid]);

	$HID_QualityAVG = @$QualityArr[$Guid]["AvgQuality"];
	$HID_QualityMIN = @$QualityArr[$Guid]["MinQuality"];
	$HID_QualityMAX = @$QualityArr[$Guid]["MaxQuality"];
	$HID_QualityCnt = @$QualityArr[$Guid]["Count"];
	$HID_QualityAVG30d = @$QualityArr[$Guid]["AvgQuality30d"];
	$HID_QualityCnt30d = @$QualityArr[$Guid]["Count30d"];
	$HID_QualityDate = @$QualityArr[$Guid]["Date"];
	$HID_QualityStr = "За $HID_QualityDate среднее качество: $HID_QualityAVG% ($HID_QualityCnt шт); за 30 дн: $HID_QualityAVG30d% ($HID_QualityCnt30d шт.)";
	$HID_QualityIMG = "&nbsp;&nbsp;&nbsp;";
	$HID_QualityIMG = ( $HID_QualityAVG < 50 or $HID_QualityAVG30d < 50 ) ? "<img src='/pic/ico/signallevel-level5_128x128.png' width='16' height='16' />" : @$HID_QualityIMG;
	$HID_QualityIMG = ( $HID_QualityAVG < 40 or $HID_QualityAVG30d < 40 ) ? "<img src='/pic/ico/signallevel-level4_128x128.png' width='16' height='16' />" : @$HID_QualityIMG;
	$HID_QualityIMG = ( $HID_QualityAVG < 30 or $HID_QualityAVG30d < 30 ) ? "<img src='/pic/ico/signallevel-level3_128x128.png' width='16' height='16' />" : @$HID_QualityIMG;
	$HID_QualityIMG = ( $HID_QualityAVG < 20 or $HID_QualityAVG30d < 20 ) ? "<img src='/pic/ico/signallevel-level2_128x128.png' width='16' height='16' />" : @$HID_QualityIMG;
	$HID_QualityIMG = ( $HID_QualityAVG < 10 or $HID_QualityAVG30d < 10 ) ? "<img src='/pic/ico/signallevel-level1_128x128.png' width='16' height='16' />" : @$HID_QualityIMG;
	$HID_QualityIMG = ( $HID_QualityAVG < 5 or $HID_QualityAVG30d < 5 ) ? "<img src='/pic/ico/signallevel-level0_128x128.png' width='16' height='16' />" : @$HID_QualityIMG;
	$HID_QualityIMG = ( $HID_QualityAVG == "" or $HID_QualityAVG30d == "" ) ? "" : @$HID_QualityIMG;

	$HID_MiltiHID = iconv("Windows-1251", "UTF-8", @$MultiHID[$Guid]["Sensors"]);
	
	$HID_BAT_AvgWorkingTime = @$BATs[$Guid]["AvgWorkingTime"];
	$HID_BAT_AvgWorkingTimeMinutes = (@$BATs[$Guid]["AvgWorkingTimeMinutes"]) ? @$BATs[$Guid]["AvgWorkingTimeMinutes"] : "100000";
	$HID_BAT_Count = @$BATs[$Guid]["Count"];

	$HID_BAT_IMG = ""; // $HID_BAT_Count - $HID_BAT_AvgWorkingTime ";
	$S_HID_BAT = "";
	$HID_Bat_text = ($HID_BAT_Count) ?  "90% теряла [$HID_BAT_Count] раз, за [$HID_BAT_AvgWorkingTime] (в среднем)" : "";
	if ( $HID_BAT_Count == 1 )
	{
		$HID_BAT_IMG = "<img src='/pic/ico/battery-full_20x20.png' width='16' height='16' Title='$HID_Bat_text' />";
		$S_HID_BAT = ""; //$style["bg-ll-red"];
	}
	elseif ( $HID_BAT_Count > 10 and $HID_BAT_AvgWorkingTimeMinutes < 3*60 )
	{
		$HID_BAT_IMG = "<img src='/pic/ico/battery-empty_20x20.png' width='14' height='14' Title='$HID_Bat_text' />";
		$S_HID_BAT = $style["bg-red"];
	}
	elseif ( $HID_BAT_Count > 5 and $HID_BAT_AvgWorkingTimeMinutes < 5*60 )
	{
		$HID_BAT_IMG = "<img src='/pic/ico/battery-low_20x20.png' width='16' height='16' Title='$HID_Bat_text' />";
		$S_HID_BAT = $style["bg-l-red"];
	}
	elseif ( $HID_BAT_Count > 5 and $HID_BAT_AvgWorkingTimeMinutes < 7*60 )
	{
		$HID_BAT_IMG = "<img src='/pic/ico/battery-mid_20x20.png' width='16' height='16' Title='$HID_Bat_text' />";
		$S_HID_BAT = $style["bg-ll-red"];
	}
	elseif ( $HID_BAT_Count > 5 and $HID_BAT_AvgWorkingTimeMinutes < 8*60 )
	{
		$HID_BAT_IMG = "<img src='/pic/ico/battery-high_20x20.png' width='16' height='16' Title='$HID_Bat_text' />";
		$S_HID_BAT = $style["bg-lll-red"];
	}
	elseif ( $HID_BAT_Count > 2 and $HID_BAT_AvgWorkingTimeMinutes < 8*60 )
	{
		$HID_BAT_IMG = "<img src='/pic/ico/battery-full_20x20.png' width='16' height='16' Title='$HID_Bat_text' />";
		$S_HID_BAT = $style["bg-llll-red"];
	}
	elseif ( $HID_BAT_Count > 1 and $HID_BAT_AvgWorkingTimeMinutes < 8*60 )
	{
		$HID_BAT_IMG = "<img src='/pic/ico/battery-full_20x20.png' width='16' height='16' Title='$HID_Bat_text' />";
		$S_HID_BAT = "";
	}


	$MacAddress = $data[$i]["HID_MAC_Address"];
	
	$LastActivity = $data[$i]["LastActivityDate"];
	$LastActivityDaysAgo = $data[$i]["LastActivityDaysAgo"];

	$State = iconv("Windows-1251", "UTF-8", $data[$i]["User_State"]);
	$StateCode = iconv("Windows-1251", "UTF-8", $data[$i]["User_State_Code"]);
	
	if ( $StateCode == "Normal" and $HID_Connected ) { $SateImg = "<img src=\"/pic/ico/check_24x24.png\" width=\"17\" height=\"17\" alt=\"$State\" Title=\"$State\" />"; }
	elseif ( $StateCode == "Sleep" and $HID_Connected ) { $SateImg = "<img src=\"/pic/ico/mon_blue_24x24.png\" width=\"17\" height=\"17\" alt=\"$State\" Title=\"$State\" />"; }
	elseif ( $StateCode == "SemiSleep" and $HID_Connected ) { $SateImg = "<img src=\"/pic/ico/mon_blue_24x24.png\" width=\"17\" height=\"17\" alt=\"$State\" Title=\"$State\" />"; }
	elseif ( $StateCode == "SemiStress" and $HID_Connected ) { $SateImg = "<img src=\"/pic/ico/alert-square-red_24x24.png\" width=\"17\" height=\"17\" alt=\"$State\" Title=\"$State\" />"; }
	elseif ( $StateCode == "Stress" and $HID_Connected ) { $SateImg = "<img src=\"/pic/ico/alert-square-red_24x24.png\" width=\"17\" height=\"17\" alt=\"$State\" Title=\"$State\" />"; }
	elseif ( $StateCode == "Undefined" and $HID_Connected ) { $SateImg = "<img src=\"/pic/ico/flickr_circle_gray_24x24.png\" width=\"17\" height=\"17\" alt=\"$State\" Title=\"$State\" />"; }
	else { $SateImg = ""; }
//	check_24x24.png
//alert-square-red_24x24
//alert_round_16x16
//mon_blue_24x24

//	$MacAddress = iconv("Windows-1251", "UTF-8", $data[$i]["MacAddress"]);
	$HID_FW = iconv("Windows-1251", "UTF-8", $data[$i]["HID_FW"]);
	$HID_FW13_LastDate = ( $HID_FW >= 15 ) ? @$HIDLastDateFW13[$Guid] : "";
	//$HIDLastDateFW13
	
	$FWVerAlertStyle = ( $maxFW != $HID_FW and $HID_FW !="" and $LastActivityDaysAgo < 10 ) ? $style["bg-ll-red"] : "";
	$HID_ConnectedStyle = ( $HID_Connected == 1 ) ? $style["bg-lll-green"] : "";


	$DriverConnAgoStyle = ( $LastActivityDaysAgo > 90 ) ? $style["color-red"] : "";

	if ( in_array($HID_FW, $HID_FW_List) == true )
	{
		$HID_FW_Stat["$HID_FW"]++;
	
	}
	else
	{
		$HID_FW_List[] = $HID_FW;
		$HID_FW_Stat["$HID_FW"] = 1;
	}
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

	$Fired = ( $data[$i]["Fired"]) ? TRUE : FALSE;
	$LastMedExam = @$exams[$Guid];
	
	$Person_Created = $data[$i]["Person_Created"];
	$Person_Written = $data[$i]["Person_Written"];
	$User_Created = $data[$i]["User_Created"];
	$User_Written = $data[$i]["User_Written"];

	$Style_Fired = ( $Fired ) ? "text-decoration:line-through;color:gray;" : "";

	$S_MacAddress = ( $MacAddress == "FF:FF:FF:FF:FF:FF" ) ? $style["bg-ll-red"] : "";
	
	$ShowRow = FALSE;
	$ShowRow = ( $ShowAlert == "LastActivity" and $DriverConnAgoStyle != "" ) ? TRUE : $ShowRow;
	$ShowRow = ( $ShowAlert == "FW" and $FWVerAlertStyle != "" ) ? TRUE : $ShowRow;
	$ShowRow = ( $ShowAlert == "BAT" and $S_HID_BAT != "" ) ? TRUE : $ShowRow;
	$ShowRow = ( $ShowAlert == "Repair_FW" and $S_MacAddress ) ? TRUE : $ShowRow;
	$ShowRow = ( $ShowAlert == "Repair_BAT" and $S_HID_BAT ) ? TRUE : $ShowRow;
	$ShowRow = ( $ShowAlert == "Repair_VER" and $FWVerAlertStyle ) ? TRUE : $ShowRow;
	$ShowRow = ( $ShowAlert == "" ) ? TRUE : $ShowRow;

	$S_MultiHID = ( $HID_MiltiHID ) ? $style["bg-ll-red"] : "";
	
	if ( $ShowRow )
	{
	$template->assign_block_vars('row', array(
//		'GUID' => $UserGuid,
		'ID' => $i,
		'USER_GUID' => $Guid,
		'PERSON_GUID' => $Person_Guid,
		'HID_GUID' => $Sensor_Guid,
	
		'LASTNAME' => $LastName,
		'FIRSTNAME' => $First_Name,
		'MIDDLENAME' => $Middle_Name,
		
		'ROLE' => $Role,
		'ROLE_CODE' => $RoleCode,
		
		
		'HID_QUALITY' => $HID_QualityIMG,
		'HID_QUALITY_STR' => $HID_QualityStr,
		'SENSORSERNO' => $HID_SerNo,
		'LAST_MED_EXAM' => $LastMedExam,
		'STATE' => $State,
		'BATTERY_LEVEL' => $Battery_Level,
		'BAT_IMG' => $HID_BAT_IMG,
		'BAT_LOST_TEXT'=> $HID_Bat_text,
		'HID_FW' => $HID_FW,
		'HID_FW13_LASTDATE' => ( $HID_FW13_LastDate ) ? "Последний день 13-й прошивки: [$HID_FW13_LastDate]" : "",
		'HID_HISTORY' =>$HID_History,
		'POSITION' => $Position,
		'LINE' => $GroupsCompact,
		'GROUPS' => @implode("; ", $Groups),
		'LAST_ACTIVITY' => $LastActivity,
		'MAC_ADDRESS' => $MacAddress,
		
		'MULTI_HID' => $HID_MiltiHID,
		
		'DATE_P_CREATED' => $Person_Created,
		'DATE_P_WRITTEN' => $Person_Written,
		'DATE_U_CREATED' => $User_Created,
		'DATE_U_WRITTEN' => $User_Written,
		
		'S_FWVERALERT' => $FWVerAlertStyle,
		'S_DRIVERCONNAGO' => $DriverConnAgoStyle,
		'S_HIDCONNECTED' =>$HID_ConnectedStyle,
		'S_HID_BAT' => $S_HID_BAT,
		'S_MAC_ADDRESS' => $S_MacAddress,
		'S_MULTI_HID' => $S_MultiHID,
		'S_ROLE' => $S_Role,

		'IMG_USTATE' => $SateImg,
		
		'S_FIRED' => $Style_Fired,
	));
	}

	$i++;
}

//var_dump($HID_FW_List);

$i = 0;
foreach ( $HID_FW_List as $item )
//while ( @$HID_FW_List[$i] )
{
	//$ver = $HID_FW_List[$i];
	$ver = ( $item != "" ) ? $item : "Empty";
	$ver_cnt = $HID_FW_Stat[$item];
	$template->assign_block_vars('row_fw_stat', array(
		'VERSION' => $ver,
		'COUNT' => $ver_cnt,
	));

}
//	var_dump($HID_FW_Stat);

if ( $ShowAlert ) 
{
	$template->assign_block_vars('hidereports', array(
	));
}
else
{
	$template->assign_block_vars('legend', array(
	));
}


$template->pparse('body');
?>