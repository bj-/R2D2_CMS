<?php
/***************************************************************************
 *                                form_date.php
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
	//$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

	$SQL = "
/****** Минимальный и максимальный заряд гарнитуры в течение дня  + время прошедшее между мин и макс зарядом  ******/
select TOP 100
	CONCAT([p].[Last_Name], ' ', [p].[First_Name], ' ', [p].[Middle_Name]) AS [FIO],
	[s].[SerialNo],
	--[sbj].[BatteryLevel],
	MAX([sbj].[Battery_Level]) AS [MAXBat],
	MIN([sbj].[Battery_Level]) AS [MinBat],
	--format([sbj].[Written] ,'yyy.MM.dd') AS [Date],
	format([sbj].[Written] ,'dd.MM.yyy') AS [Date],
	format([sbj].[Written] ,'yyyMMdd') AS [DateSRT],
	format(MIN([sbj].[Written]),'HH:mm:ss') AS [StartTime],
	format(MAX([sbj].[Written]),'HH:mm:ss') AS [FinishTime],
	--dateadd(SECOND,MAX([sbj].[Changed]),MIN([sbj].[Changed]))
	format(dateadd(SECOND, DATEDIFF(SECOND, MIN([sbj].[Written]), MAX([sbj].[Written])), ''),'HH:mm:ss') AS [Working Time]
	--DATEDIFF(MAX([sbj].[Changed])-MIN([sbj].[Changed]))
	-- [sbj].[Changed]
	--, *
FROM [Stages_Battery] AS [sbj]
INNER JOIN [Sensors_Cardio] as [sc] ON [sbj].[Sensors_Guid] = [sc].[Guid]
INNER JOIN [users] AS [u] ON [sc].[Users_Guid] = [u].[Guid]
INNER JOIN [Users_Persons] AS [p] ON [u].[Users_Persons_Guid] = [p].[Guid]
INNER JOIN [Sensors] AS [s] ON [s].[Guid] = [sbj].[Sensors_Guid]

where 1=1
	%%1%%
	--[s].[SerialNo] = 'STH00-388'
	AND [sbj].[Written] BETWEEN '$dateFrom $timeFrom' AND '$DateTo $TimeTo'
	-- SerialNo = 'STH00-122'
Group BY format([sbj].[Written] ,'dd.MM.yyy'), format([sbj].[Written] ,'yyyMMdd'), [p].[Last_Name], [p].[First_Name], [p].[Middle_Name], [s].[SerialNo]
ORDER BY 
	[p].[Last_Name] ASC,
	[DateSRT] DESC
";
	$SQL = ( $uGuid ) ? str_replace("%%1%%", " AND [u].[Guid] = '$uGuid' ", $SQL) : str_replace("%%1%%", "", $SQL);
	//echo "<pre>$SQL</pre>";

	$SQL = iconv("UTF-8", "Windows-1251", $SQL);

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[]= $row;
	}


	$template->set_filenames(array(
		'report' => 'incl/report_HIDBattery.tpl')
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
		$MAXBat = $row["MAXBat"];
		$MinBat = $row["MinBat"];
		$Date = $row["Date"];
		$StartTime = $row["StartTime"];
		$FinishTime = $row["FinishTime"];
		$WorkingTime = $row["Working Time"];
		
		$template->assign_block_vars('row',array(
			'FIO' => $FIO,
			'SERIALNO' => $SerialNo,
			'MAX_BAT' => $MAXBat,
			'MIN_BAT' => $MinBat,
			'DATE' => $Date,
			'START_TIME' => $StartTime,
			'FINISH_TIME' => $FinishTime,
			'WORKING_TIME' => $WorkingTime,
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
