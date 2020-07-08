<?php
/***************************************************************************
 *                                reports.php
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

//echo "sdfsdfsdf";
// Get params from url
//$ShowAlert = substr(@$_GET["ShowAlert"], 0, 20);

//echo "dddddddddddd<br>";
$ReportName = str_remove_sql_char(substr(@$_GET["Report"],0,30));
$ShowFilters = ( @$_GET["ShowFilters"] == "true" or @$_GET["ShowFilters"] == ""  ) ? TRUE  : FALSE;
$ShowReport = ( @$_GET["ShowReport"] == "true" or @$_GET["ShowReport"] == ""  ) ? TRUE  : FALSE;

$filters["date"] = str_remove_sql_char(substr(@$_GET["date"],0,10));
$filters["time"] = str_remove_sql_char(substr(@$_GET["time"],0,5));
$filters["dateFrom"] = str_remove_sql_char(substr(@$_GET["DateFrom"],0,10));
$filters["UseDateFrom"] = ( @$_GET["UseDateFrom"] == "true" ) ? TRUE : FALSE;
$filters["timeFrom"] = str_remove_sql_char(substr(@$_GET["TimeFrom"],0,5));
$filters["dateTo"] = str_remove_sql_char(substr(@$_GET["DateTo"],0,10));
$filters["UseDateTo"] = ( @$_GET["UseDateTo"] == "true" ) ? TRUE : FALSE;
$filters["timeTo"] = str_remove_sql_char(substr(@$_GET["TimeTo"],0,5));
$filters["Prefix"] = str_remove_sql_char(substr(@$_GET["Prefix"],0,30));

$filters["UseSenOrFio"] = ( @$_GET["UseSenOrFio"] == "useFIO" ) ? "useFIO" : "";
$filters["UseSenOrFio"] = ( @$_GET["UseSenOrFio"] == "useSEN" ) ? "useSEN" : $filters["UseSenOrFio"];
$filters["uGuid"] = ( strlen(@$_GET["uGuid"]) == 36 ) ? str_remove_sql_char(substr(@$_GET["uGuid"],0,36)) : "";
$filters["SensorGuid"] = ( strlen(@$_GET["SensorGuid"]) == 36 ) ? str_remove_sql_char(substr(@$_GET["SensorGuid"],0,36)) : "";


$filters["VehGrp"] = str_remove_sql_char(substr(@$_GET["VehGrp"],0,50));


$filters["srv"] = str_remove_sql_char(substr(@$_GET["srv"],0,20));


//echo "<pre>";var_dump($_GET);echo "</pre>";

$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-sRoot", "Shturman" );

$SQL_QUERY["List"] = "
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

$SQL = "
/****** Группы вагонов  ******/
SELECT 
	--[s].Alias AS [BlockSerialNo],
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
GROUP BY [g].[Code], [g].[Name]
";
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$dataGrpVeh[] = $row;
}


$SQL = "
/****** Список сенсеров  ******/
SELECT
	[sn].[Guid],
	[sn].[SerialNo],
	[sn].[Name],
	'Description' AS [Description]
FROM [Sensors] AS [sn]
ORDER BY [sn].[SerialNo]
";
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$dataSensors[] = $row;
}


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
$page_title = "Reports";
$page_text = "";

$template->set_filenames(array(
	'body' => 'reports.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'CURRENTDATENEXT' => $currentdateNext,
	'REPORT_PAGE' => $ReportName,
	'SERVER' => $filters["srv"],

	));

/*
	$template->assign_block_vars('row', array(
		'L_LINK' => $l_Link,
	));

*/

if ( $ShowFilters )
{
	//echo "sadfs";

	$personsShow = FALSE;
	$personsShow = FALSE;
	$gobuttonShow = FALSE;
	$timeFromShow = FALSE;
	$timeToShow = FALSE;
	$SensorsShow = FALSE;

	$VehicleGroupShow = FALSE;

	if ( $ReportName == "report_TaskActivity" )
	{
		$datesShow = TRUE;
		$gobuttonShow = TRUE;
		$VehicleGroupShow = TRUE;
	}
	elseif ( $ReportName == "report_BOIGroupNorms" )
	{
		$datesShow = TRUE;
		$gobuttonShow = TRUE;
	}
	elseif ( $ReportName == "report_SensorBatteryByDays" )
	{
		$datesShow = TRUE;
		$gobuttonShow = TRUE;
		$SensorsShow = TRUE;
	}
	else
	{
		$datesShow = TRUE;
		$timeFromShow = TRUE;
		$timeToShow = TRUE;
		$personsShow = TRUE;
		$gobuttonShow = TRUE;
	}

	if ( $datesShow ) { $template->assign_block_vars('dates', array()); }
	if ( $timeFromShow ) { $template->assign_block_vars('dates.timeFrom', array()); }
	if ( $timeToShow ) { $template->assign_block_vars('dates.timeTo', array()); }
	if ( $personsShow )
	{
		$template->assign_block_vars('persons', array());

		$i = 0;
		while ( @$dataFIO[$i] )
		{
			$LastName  = iconv("Windows-1251", "UTF-8", $dataFIO[$i]["Last_Name"]);
			$FirstName = iconv("Windows-1251", "UTF-8", $dataFIO[$i]["First_Name"]);
			$MiddleName = iconv("Windows-1251", "UTF-8", $dataFIO[$i]["Middle_Name"]);
			$SensorSerNo = $dataFIO[$i]["SensorSerialNo"];
			$UserGuid = $dataFIO[$i]["Guid"];

			$template->assign_block_vars('persons.row_person', array(
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
		
			$template->assign_block_vars('persons.row_sensor', array(
				'GUID' => $UserGuid,
				'LASTNAME' => $LastName,
				'FIRSTNAME' => $FirstName,
				'MIDDLENAME' => $MiddleName,
				'SENSORSERNO' => $SensorSerNo,
			));
			$i++;
		}

	}
	if ( $VehicleGroupShow )
	{
		$template->assign_block_vars('veh_groups', array());

		foreach( $dataGrpVeh as $item )
		{
			$grp_code =  iconv("Windows-1251", "UTF-8", $item["Code"]);
			$grp_name =  iconv("Windows-1251", "UTF-8", $item["Name"]);
			
			$template->assign_block_vars('veh_groups.veh_row_grp', array(
				'CODE' => $grp_code,
				'NAME' => $grp_name,
			));
		}
	}
	if ( $SensorsShow ) 
	{
		$template->assign_block_vars('sensors', array());

		$i = 0;
		while ( @$dataSensors[$i] )
		{
			$Guid = $dataSensors[$i]["Guid"];
			$Name = iconv("Windows-1251", "UTF-8", $dataSensors[$i]["Name"]);
			$SerialNo = iconv("Windows-1251", "UTF-8", $dataSensors[$i]["SerialNo"]);
			$Description = iconv("Windows-1251", "UTF-8", $dataSensors[$i]["Description"]);

			$template->assign_block_vars('sensors.row', array(
				'GUID' => $Guid,
				'NAME' => $Name,
				'SERNO' => $SerialNo,
				'DESC' => ($Description) ? " ($Description)" : "",
			));

		//	echo "<option value=\"$UserGuid\">$LastName $FirstName $MiddleName ($SensorSerNo)</option>";
		//	echo $LastName . " " . $FirstName . " " . $MiddleName . "<br />";
			$i++;
		}
		
	}
	if ( $gobuttonShow )
	{
		$template->assign_block_vars('gobutton', array());
	}
	
}

// Отчет
//echo $ReportName;

if ( $ReportName == "report_AlertsAll" )
{
	include($DRoot . '/includes/report_AlertsAll.php');
}
elseif ( $ReportName == "report_HIDBattery" )
{
	include($DRoot . '/includes/report_HIDBattery.php');
}
elseif ( $ReportName == "report_HIDBatteryLostFast" )
{
	include($DRoot . '/includes/report_HIDBatteryLostFast.php');
}
elseif ( $ReportName == "report_SleepAndStressSignals" )
{
	include($DRoot . '/includes/report_SleepAndStressSignals.php');
}
elseif ( $ReportName == "report_RRCount" )
{
	include($DRoot . '/includes/report_RRCount.php');
}
elseif ( $ReportName == "report_RRCountByWagon" )
{
	include($DRoot . '/includes/report_RRCountByWagon.php');
}
elseif ( $ReportName == "report_CheckHIDbyDate" )
{
	include($DRoot . '/includes/report_CheckHIDbyDate.php');
}
elseif ( $ReportName == "report_CheckHIDbyWagon" )
{
	include($DRoot . '/includes/report_CheckHIDbyWagon.php');
}
elseif ( $ReportName == "report_HIDquality" )
{
	include($DRoot . '/includes/report_HIDquality.php');
}
elseif ( $ReportName == "report_HIDqualityStat" )
{
	include($DRoot . '/includes/report_HIDqualityStat.php');
}
elseif ( $ReportName == "report_RRList" )
{
	include($DRoot . '/includes/report_RRList.php');
}
elseif ( $ReportName == "report_HIDBatteryList" )
{
	include($DRoot . '/includes/report_HIDBatteryList.php');
}
elseif ( $ReportName == "report_HIDBatteryLevels" )
{
	include($DRoot . '/includes/report_HIDBatteryLevels.php');
}
elseif ( $ReportName == "report_TaskActivity" )
{
	include($DRoot . '/includes/report_TaskActivity.php');
}
elseif ( $ReportName == "report_DriversStatesHistory" )
{
	include($DRoot . '/includes/report_DriversStatesHistory.php');
}
elseif ( $ReportName == "report_BOINormValidity" )
{
	include($DRoot . '/includes/report_BOINormValidity.php');
}
elseif ( $ReportName == "report_BOIGroupNorms" )
{
	include($DRoot . '/includes/report_BOIGroupNorms.php');
}
elseif ( $ReportName == "report_MedicalInspections" )
{
	include($DRoot . '/includes/report_MedicalInspections.php');
}
elseif ( $ReportName == "report_BlockMessages" )
{
	include($DRoot . '/includes/report_BlockMessages.php');
}
elseif ( $ReportName == "report_Shifts" )
{
	include($DRoot . '/includes/report_Shifts.php');
}
elseif ( $ReportName == "report_SensorBatteryByDays" )
{
	include($DRoot . '/includes/report_SensorBatteryByDays.php');
}





if ( $ReportName and $ShowReport )
{
	//$filters = "";
	compileReport( $filters ) ;
}

$template->pparse('body');
?>