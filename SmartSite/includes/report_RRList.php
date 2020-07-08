<?php
/***************************************************************************
 *                                report_RRList.php
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
	//$conn = MSSQLconnect( $Server, "Shturman" );

	$SQL = "
/****** Полученно РРов по человеку за период  ******/
declare
  @Started datetime2(7) = '$dateFrom $timeFrom',
  @Finished datetime2(7) = '$DateTo $TimeTo';
  --@Sen nvarchar(20),
  --@UserGuid uniqueidentifier = '%%uGuid%%';

  SELECT TOP 1000
	--[s].[Alias],
	CONCAT([p].[Last_Name], ' ', [p].[First_Name], ' ', [p].[Middle_Name]) AS [FIO], 
	[sn].[SerialNo],
--      ,[Services_Guid]
	FORMAT( [rr].[Written], 'dd.MM.yyyy HH:mm') AS [Written],
	--[rr].[Written],
	--[rr].[RRTimeStamp_Started],
	--[rr].[RRTimeStamp_Finished],
	--[rr].[RRs],
	--[rr].[Actual]
	--SUM([rr].[RR_Count]) AS [Sum_RRS]
	[rr].[RR_Count]
	--[rr].[BaseDateTime],
	--[rr].[BaseTimeStamp]
FROM [Sensors_RRs] AS [rr]
INNER JOIN [Sensors] AS [sn] ON [sn].[Guid] = [rr].[Sensors_Guid]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [rr].[Servers_Guid]
INNER JOIN [Sensors_Cardio] AS [sc] ON [sc].[Guid] = [sn].[Guid]
INNER JOIN [Users] AS [u] ON [u].[Guid] = [sc].[Users_Guid]
INNER JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
WHERE
	[rr].[Actual] = 1
	%%1%% 
--	AND [rr].[Written] BETwEEN '$dateFrom $timeFrom' AND '$DateTo $TimeTo'
	AND [rr].[Written] BETwEEN @Started AND @Finished
--GROUP BY [p].[Last_Name], [p].[First_Name], [p].[Middle_Name], [sn].[SerialNo],
ORDER BY
	[rr].[Written] DESC
	";
	
	$SQL = ( $uGuid ) ? str_replace("%%1%%", " AND [u].[Guid] = '$uGuid' ", $SQL) : str_replace("%%1%%", "", $SQL);
	$SQL = ( $uGuid ) ? str_replace("%%uGuid%%", $uGuid, $SQL) : str_replace("%%uGuid%%", "", $SQL);
//	echo "<pre>$SQL</pre>";
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
		'report' => 'incl/report_RRList.tpl')
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
		//$Date =  $row["Date"];
		$Written = $row["Written"];
		//$AvgWorkingTime = $row["AvgWorkingTime"];
		//$Count = $row["Count"];
		//$Sleep_Cabin = $row["Sleep_Cabin"];
		//$Stress_Cabin = $row["Stress_Cabin"];
		//$Sleep_Operator = $row["Sleep_Operator"];
		//$Stress_Operator = $row["Stress_Operator"];
		$RR_Count = $row["RR_Count"];
		//$BlockSerialNo = $row["BlockSerialNo"];
		//$Text = iconv("Windows-1251", "UTF-8", $row["Text"]);
		
		$template->assign_block_vars('row',array(
			'FIO' => $FIO,
			'SERIALNO' => $SerialNo,
			//'DATE' => $Date,
			'DATE' => $Written,
			//'AVG_WORKING_TIME' => $AvgWorkingTime,
			//'TEXT' => $Text,
			//'COUNT' => $Count,
			'COUNT' => $RR_Count,
			//'SLEEP_CABIN' => $Sleep_Cabin,
			//'STRESS_CABIN' => $Stress_Cabin,
			//'SLEEP_OPERATOR' => $Sleep_Operator,
			//'STRESS_OPERATOR' => $Stress_Operator,
			//'BLOCK' => $BlockSerialNo,
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
