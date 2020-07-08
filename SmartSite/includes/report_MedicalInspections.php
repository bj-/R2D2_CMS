<?php
/***************************************************************************
 *                                report_MedicalInspections.php
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
	global $template, $conn, $CONFIG_SHTURMAN, $SQL_QUERY;
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


	$sql_server = "Shturman_Root";
	$Server = $CONFIG_SHTURMAN["SQL_Alias_Server_Name"][$CONFIG_SHTURMAN["SQL_Server_".$sql_server]];
	$conn = MSSQLconnect( $sql_server, "Shturman_Root" );
	//$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

	$SQL = "
/****** Полученные медосмотры из гарнитур по человекам  ******/
SELECT TOP 1000
	--[s].[Alias] AS [ReceivedFromBlock],
	--[svc].[Alias] AS [ReceivedFromService],
	concat([p].[Last_Name], ' ', [p].[First_Name], ' ', [p].[Middle_Name]) AS [FIO], 
	[sn].[Name] AS [HID],
	--[ex].[Guid],
	--[ex].[ExaminationGuid],
	--[ex].[Servers_Guid],
	--[ex].[Services_Guid],
	--[ex].[Sensors_Guid],
	--[ex].[Users_Guid],
	--[ex].[Users_Persons_Guid],
	--[ex].[ExaminationRead],
	MIN(FORMAT( [ex].[ExaminationRead], 'dd.MM.yyyy HH:mm:ss')) AS [ExaminationRead],
	--[ex].[RecordingState],
	--[ex].[SystPressure],
	--[ex].[DistPressure],
	--[ex].[ExaminationType],
	--[ex].[ExaminationDateTime],
	FORMAT( [ex].[ExaminationDateTime], 'dd.MM.yyyy HH:mm:ss') AS [ExaminationDateTime]
	--[ex].[ExaminationResult],
	--[ex].[InterShiftExaminationType],
	--[ex].[InterShiftExaminationDateTime],
	--[ex].[InterShiftExaminationResult],
	--[ex].[Created] AS [Written],
	--[ex].[Last_Name],
	--[ex].[First_Name],
	--[ex].[Middle_Name]
FROM [Sensors_Exam] AS [ex]
	INNER JOIN [Servers] AS [s] ON [s].[Guid] = [ex].[Servers_Guid]
	INNER JOIN [Sensors] AS [sn] ON [sn].[Guid] = [ex].[Sensors_Guid]
	INNER JOIN [Users_Persons] AS [p] ON [p].[Guid] = [ex].[Users_Persons_Guid]
	INNER JOIN [Services] AS [svc] ON [svc].[Guid] = [ex].[Services_Guid]
WHERE 1=1
	%%GUID%%
	--AND p.Last_Name LIKE '%Крут%'
	--AND p.First_Name LIKE '%Сергей%'
	--AND [sn].[Name] LIKE '%352'
	AND [ex].[Created] BETWEEN '%%FROM%%' AND '%%TO%%'
	AND [ex].[ExaminationDateTime] IS NOT NULL
GROUP BY 
	[p].[Last_Name], [p].[First_Name], [p].[Middle_Name], [sn].[Name], [ex].[ExaminationDateTime]
ORDER BY
	[p].[Last_Name], [p].[First_Name], [p].[Middle_Name],
	[ex].[ExaminationDateTime] DESC
--	[ex].[Created] DESC
	";
	//$SQL_QUERY["Persons_MedicalInspections"];
	//$SQL = str_replace("%%GUID%%", $uGuid, $SQL);
	$SQL = str_replace("%%FROM%%", $dateFrom, $SQL);
	$SQL = str_replace("%%TO%%", $DateTo, $SQL);

	$SQL = ( $uGuid ) ? str_replace("%%GUID%%", " AND [ex].[Users_Guid] = '$uGuid' ", $SQL) : str_replace("%%GUID%%", "", $SQL);
	//echo "<pre>$SQL</pre>";

	//$SQL = iconv("UTF-8", "Windows-1251", $SQL);

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[]= $row;
	}


	$template->set_filenames(array(
		'report' => 'incl/report_MedicalInspections.tpl')
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
		//$SerialNo = $row["SerialNo"];
		//$MAXBat = $row["MAXBat"];
		//$MinBat = $row["MinBat"];
		//$Date = $row["Date"];
		//$StartTime = $row["StartTime"];
		//$FinishTime = $row["FinishTime"];
		//$WorkingTime = $row["Working Time"];
		
		$HID = $row["HID"];
		$ExaminationRead = $row["ExaminationRead"];
		//$RecordingState = $data[$i]["RecordingState"];
		//$SystPressure = $data[$i]["SystPressure"];
		//$DistPressure = $data[$i]["DistPressure"];
		//$ExaminationType = $data[$i]["ExaminationType"];
		$ExaminationDateTime = $row["ExaminationDateTime"];

		
		$template->assign_block_vars('row',array(
			'FIO' => $FIO,
			//'SERIALNO' => $SerialNo,
			//'MAX_BAT' => $MAXBat,
			//'MIN_BAT' => $MinBat,
			//'DATE' => $Date,
			//'START_TIME' => $StartTime,
			//'FINISH_TIME' => $FinishTime,
			//'WORKING_TIME' => $WorkingTime,
			'HID' => $HID,
			'EXAM_READ_DATE' => $ExaminationRead,
			'EXAM_DATE' => $ExaminationDateTime,
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
