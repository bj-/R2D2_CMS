<?php
/***************************************************************************
 *                                report_HIDBatteryLostFast.php
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
	//$conn = MSSQLconnect( "SpbMetro-sRoot", "Shturman" );
//	$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );
	//$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

	$SQL = "
	
	/****** RRs by HID, Date and Wagon  ******/
SELECT TOP 1000 [FIO], [SerialNo], [Written], [BlockSerialNo],  SUM([RR_Count]) AS [RR_Count]
FROM (
SELECT
	CONCAT([p].[Last_Name], ' ', [p].[First_Name], ' ', [p].[Middle_Name]) AS [FIO], 
	--[rrs].[Guid],
	--[rrs].[Servers_Guid],
	--[rrs].[Services_Guid],
	--[rrs].[Sensors_Guid],
	[sn].[SerialNo],
	[s].[Alias] AS [BlockSerialNo],
	FORMAT( [rrs].[Written], 'dd.MM.yyyy') AS [Written],
	FORMAT( [rrs].[Written], 'yyyMMdd') AS [WrittenSRT],
	--[rrs].[Written],
	--[rrs].[RRTimeStamp_Started],
	--[rrs].[RRTimeStamp_Finished],
	--[rrs].[RRs],
	--[rrs].[Actual],
	[rrs].[RR_Count]
	--[rrs].[BaseDateTime]
	--[rrs].[BaseTimeStamp]
FROM [Sensors_RRs] as [rrs]
inner join [Sensors] as [sn] on [sn].[Guid] = [rrs].[Sensors_Guid]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [rrs].[Servers_Guid]
INNER JOIN [Sensors_Cardio] AS [sc] ON [sc].[Guid] = [sn].[Guid]
INNER JOIN [Users] AS [u] ON [u].[Guid] = [sc].[Users_Guid]
INNER JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
WHERE 
	[rrs].[Actual] = 1
	%%1%%
	and [rrs].[Written] BETWEEN '$dateFrom $timeFrom' AND '$DateTo $TimeTo'
	--AND sn.Name = 'STH00-393'
	--ORDER BY [rrs].[Written] DESC
) AS t
GROUP BY [FIO], [SerialNo], [Written], [WrittenSRT], [BlockSerialNo]
ORDER BY [FIO], [SerialNo], [WrittenSRT] DESC, [BlockSerialNo]
	";
	
	$SQL = ( $uGuid ) ? str_replace("%%1%%", " AND [u].[Guid] = '$uGuid' ", $SQL) : str_replace("%%1%%", "", $SQL);
	//echo "<pre>$SQL</pre>";

	$SQL = iconv("UTF-8", "Windows-1251", $SQL);

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[]= $row;
	}
	//echo "<pre>";var_dump(@$data);echo "</pre>";

	$template->set_filenames(array(
		'report' => 'incl/report_RRCountByWagon.tpl')
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
	));
	
	foreach ( is_array(@$data) ? $data : array() as $row )
	{
		$FIO = iconv("Windows-1251", "UTF-8", $row["FIO"]);
		$SerialNo = $row["SerialNo"];
		//$AvgWorkingTime = $row["AvgWorkingTime"];
		//$Count = $row["Count"];
		//$Sleep_Cabin = $row["Sleep_Cabin"];
		//$Stress_Cabin = $row["Stress_Cabin"];
		//$Sleep_Operator = $row["Sleep_Operator"];
		//$Stress_Operator = $row["Stress_Operator"];
		$Written = $row["Written"];
		$RR_Count = $row["RR_Count"];
		$BlockSerialNo = $row["BlockSerialNo"];
		
		$template->assign_block_vars('row',array(
			'FIO' => $FIO,
			'SERIALNO' => $SerialNo,
			//'AVG_WORKING_TIME' => $AvgWorkingTime,
			//'COUNT' => $Count,
			//'SLEEP_CABIN' => $Sleep_Cabin,
			//'STRESS_CABIN' => $Stress_Cabin,
			//'SLEEP_OPERATOR' => $Sleep_Operator,
			//'STRESS_OPERATOR' => $Stress_Operator,
			'DATE' => $Written,
			'COUNT' => $RR_Count,
			'BLOCK' => $BlockSerialNo,
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
