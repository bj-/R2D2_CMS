<?php
/***************************************************************************
 *                                report_HIDBatteryList.php
 *								  			build menu
 *                            -------------------
 *   begin                : Saturday, Jul 13, 2010
 *   copyright            : (C) 2001 The R2D2 Group
 *
 *   $Id: msend.php,v 1.99.2.1 2002/12/19 17:17:40 psotfx Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 * 
 *
 ***************************************************************************/

 
//
//отсылка почты из формы
//
//echo "dfdfdF";

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}
//echo "<pre>"; print_r($topmenu_data); echo "</pre>";
//echo "safdsafsaf";

function compileReport( $filters ) 
{
	/*
		$type		= тип (по какому полю соритруется FIO или SEN (номеру сенсора)
		$valueType	= Что будет указано в качестве Value - GUID | NAME (Фио с носером сенсоар версией прошики и зарядом)
		
	*/
	global $template, $conn, $CONFIG_SHTURMAN;
//	global $topmenu_pids, $topmenu_data, $url_lang;

	$ret = "";

//	echo "ssdfsdfs";
	//echo '<pre>'; print_r($filters); echo '</pre>';

	$date = @$filters["date"];
	$time = @$filters["time"];
	$dateFrom = @$filters["dateFrom"];
	$timeFrom = @$filters["timeFrom"];
	$DateTo = @$filters["dateTo"];
	$TimeTo = @$filters["timeTo"];
	$prefix = @$filters["Prefix"];
	$uGuid = @$filters["uGuid"];
	//$ = $data[""];

	$sql_server = "Shturman_Anal";
	$Server = $CONFIG_SHTURMAN["SQL_Alias_Server_Name"][$CONFIG_SHTURMAN["SQL_Server_".$sql_server]];
	$conn = MSSQLconnect( $sql_server, "Shturman_Anal" );
	
	//$Server = "SpbMetro-Anal";
	//$Server = "SpbMetro-sRoot";
	//$conn = MSSQLconnect( $Server, "Shturman-Anal" );

	$SQL = "
/****** Падение заряда батарейки  ******/
declare
  @Started datetime2(7) = '$dateFrom $timeFrom',
  @Finished datetime2(7) = '$DateTo $TimeTo';
  --@Sen nvarchar(20),
  --@UserGuid uniqueidentifier = '%%uGuid%%';

select TOP 1000
	CONCAT([p].[Last_Name], ' ', [p].[First_Name], ' ', [p].[Middle_Name]) AS [FIO],
	[s].[SerialNo],
	[sbj].[Battery_Level],
--	format([sbj].[Written] ,'yyy.MM.dd HH:mm') AS [Date] -- вриттен будет от той даты когда придет новяй заряд батарейки. а это может быть и через неделю.
	format([sbj].[Started] ,'yyy.MM.dd HH:mm') AS [Date]
FROM [Stages_Battery] AS [sbj]
INNER JOIN [Sensors_Cardio] as [sc] ON [sbj].[Sensors_Guid] = [sc].[Guid]
INNER JOIN [users] AS [u] ON [sc].[Users_Guid] = [u].[Guid]
INNER JOIN [Users_Persons] AS [p] ON [u].[Users_Persons_Guid] = [p].[Guid]
INNER JOIN [Sensors] AS [s] ON [s].[Guid] = [sbj].[Sensors_Guid]

where 1=1
	%%1%%
	AND [sbj].[Written] BETWEEN @Started AND @Finished
ORDER BY 
	[p].[Last_Name] ASC,
	[Date] DESC

	";
	
	$SQL = ( $uGuid ) ? str_replace("%%1%%", " AND [u].[Guid] = '$uGuid' ", $SQL) : str_replace("%%1%%", "", $SQL);
	$SQL = ( $uGuid ) ? str_replace("%%uGuid%%", $uGuid, $SQL) : str_replace("%%uGuid%%", "", $SQL);
	//echo "<pre>$SQL</pre>";
	//exit;

	$SQL = iconv("UTF-8", "Windows-1251", $SQL);

	//set_time_limit(60); // увеличиваем время выполнения скрипта

	$Required = "";
	//$Required = ( !$uGuid ) ? "<h3>Обязательно выбрать Водителя</h3>" : "";

	if ( !$Required )
	{
		$stmt = sqlsrv_query( $conn, $SQL );
		if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$data[]= $row;
		}
		//echo "<pre>";var_dump(@$data);echo "</pre>";
	}
	
	$template->set_filenames(array(
		'report' => 'incl/report_HIDBatteryList.tpl')
	);

	
	$template->assign_vars(array(
		'SERVER' => $Server,
		'CLIENT_NAME' => iconv("Windows-1251", "UTF-8", $CONFIG_SHTURMAN["Client_Name"]),
		'DATE' => sql2rus($date),
		'TIME' => $time,
		'DATE_FROM' => sql2rus($dateFrom),
		'TIME_FROM' => $timeFrom,
		'DATE_TO' => sql2rus($DateTo),
		'TIME_TO' => $TimeTo,
		'PREFIX' => $prefix,
		'REQUIRED' => $Required,
//		'SERVER' => $Server,
	));
	
	foreach ( is_array(@$data) ? $data : array() as $row )
	{
		$FIO = iconv("Windows-1251", "UTF-8", $row["FIO"]);
		$SerialNo = $row["SerialNo"];
		$Date =  $row["Date"];
		//$Written = $row["Written"];
		//$AvgWorkingTime = $row["AvgWorkingTime"];
		//$Count = $row["Count"];
		//$Sleep_Cabin = $row["Sleep_Cabin"];
		//$Stress_Cabin = $row["Stress_Cabin"];
		//$Sleep_Operator = $row["Sleep_Operator"];
		//$Stress_Operator = $row["Stress_Operator"];
		//$RR_Count = $row["RR_Count"];
		//$BlockSerialNo = $row["BlockSerialNo"];
		//$Text = iconv("Windows-1251", "UTF-8", $row["Text"]);
		$Battery_Level = $row["Battery_Level"];
		
		$template->assign_block_vars('row',array(
			'FIO' => $FIO,
			'SERIALNO' => $SerialNo,
			'DATE' => $Date,
			//'DATE' => $Written,
			//'AVG_WORKING_TIME' => $AvgWorkingTime,
			//'TEXT' => $Text,
			//'COUNT' => $Count,
			//'COUNT' => $RR_Count,
			//'SLEEP_CABIN' => $Sleep_Cabin,
			//'STRESS_CABIN' => $Stress_Cabin,
			//'SLEEP_OPERATOR' => $Sleep_Operator,
			//'STRESS_OPERATOR' => $Stress_Operator,
			//'BLOCK' => $BlockSerialNo,
			'BATTERY_LEVEL' => $Battery_Level,
			));
	}
/*
	$template->assign_block_vars('row',array());

	$template->assign_block_vars('row.item',array(
		'NAME' => "",
		'VALUE' => "",
		'INDENT' => "", // " &nbsp; &nbsp; " // Отступ в листе
	));
*/
	$template->assign_var_from_handle('REPORT', 'report');
	
	return $ret;

};
?>
