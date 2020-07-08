<?php
/***************************************************************************
 *                                report_TaskActivity.php
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

	$VehGrp = @$filters["VehGrp"];

	$sql_server = "Diag";
	$Server = $CONFIG_SHTURMAN["SQL_Alias_Server_Name"][$CONFIG_SHTURMAN["SQL_Server_".$sql_server]];
	$conn = MSSQLconnect( $sql_server, "Diag" );
	//$Server = "SpbMetro-Anal";
	//$Server = "SpbMetro-sRoot";
	//$conn = MSSQLconnect( $Server, "Block" );

	$SQL = "
/****** Активность по таскам за период  ******/

DECLARE @dateFrom datetime2(7) = '$dateFrom',
		@dateTo datetime2(7) = '$DateTo';
	--@date_add datetime2(7) = DATEADD('2019-10-01';

SELECT 
	[t].[ID] AS [Issue_ID],
	[tst].[Name] AS [Status],
	--[t].[Status_ID],
	[ts].[Name] AS [Severity],
	[s].[BlockSerialNo],
	--[tot].[Code] AS [ObjectTypeCode],
	[tot].[Name] AS [ObjectTypeName],
	--[t].[Severity_ID],
	--[t].[Object_Guid],
	--[t].[Object_ID],
	--[t].[Object_TypeID],
	[t].[Subject],
	[t].[Desigion],
	[t].[Result],
	--FORMAT([t].[ChangedDate], 'dd.MM.yyy HH:mm:ss') AS [ChangedDate],

	FORMAT([t].[Issue_DateTimeStart], 'dd.MM.yyy HH:mm') AS [Issue_DateTimeStart],
	FORMAT([t].[Issue_DateTimeEnd], 'dd.MM.yyy HH:mm') AS [Issue_DateTimeEnd],
	[t].[Author],
	FORMAT(DATEADD(hour, 3, [t].[Created]), 'dd.MM.yyy HH:mm') AS [Created],
	FORMAT([t].[Finished], 'dd.MM.yyy HH:mm') AS [Finished],
	[t].[Editor],
	FORMAT(DATEADD(hour, 3, [t].[Status_ChangeDate]), 'dd.MM.yyy HH:mm') AS [Status_ChangeDate],
	FORMAT([t].[ChangedDate], 'dd.MM.yyy HH:mm') AS [ChangedDate],
	[tr].[Name] AS [Reporter],
	[ta].[Name] AS [Assigned_To],

	-- Task Fields
	[tfl].[Name] AS [FieldName],
	[tf].[Field_ID] AS [Field_Field_ID],
	[tf].[Value] AS [Field_Value],
	FORMAT([tf].[Created], 'dd.MM.yyy HH:mm') AS [Field_Created],
	FORMAT([tf].[LastEdit], 'dd.MM.yyy HH:mm') AS [Filed_LastEdit],
	[tf].[Editor] AS [Field_Editor],
	[tf].[Deleted] AS [Field_Deleted]

	--[t].[Assigned_ID]
FROM [Tasks] AS [t]
LEFT JOIN [Tasks_Fields] AS [tf] ON [tf].[Task_ID] = [t].[ID]
LEFT JOIN [Tasks_Fields_List] AS [tfl] ON [tfl].[ID] = [tf].[Field_ID]
INNER JOIN [Tasks_Statuses] AS [tst] ON [tst].[ID] = [t].[Status_ID]
INNER JOIN [Tasks_Severity] AS [ts] ON [ts].[ID] = [t].[Severity_ID]
LEFT JOIN [Tasks_ObjectTypes] AS [tot] ON [tot].[ID] = [t].[Object_TypeID]
LEFT JOIN [Servers] AS [s] ON [s].[Guid] = [t].[Object_Guid]
LEFT JOIN [Tasks_Assigned] AS [ta] ON [ta].[ID] = [t].[Assigned_ID]
LEFT JOIN [Tasks_Reporters] AS [tr] ON [tr].[ID] = [t].[Issue_Reporter]
WHERE 1=1
	AND (
		-- 'этот and надо почиститьт будет т.к. теперь заполянется поле [t].[ChangedDate]
		[tf].[Created] BETWEEN @dateFrom AND @dateTo
		OR [tf].[LastEdit] BETWEEN @dateFrom AND @dateTo
		OR [t].[Issue_DateTimeStart] BETWEEN @dateFrom AND @dateTo
		OR [t].[Issue_DateTimeEnd] BETWEEN @dateFrom AND @dateTo
		OR [t].[Created] BETWEEN @dateFrom AND @dateTo
		OR [t].[Finished] BETWEEN @dateFrom AND @dateTo
		OR [t].[Status_ChangeDate] BETWEEN @dateFrom AND @dateTo
		OR [t].[ChangedDate] BETWEEN @dateFrom AND @dateTo
		)
/*	AND (
		[t].[Created] BETWEEN @dateFrom AND @dateTo 
		OR [t].[ChangedDate] BETWEEN @dateFrom AND @dateTo
		) 
*/
	AND [s].[BlockSerialNo] NOT IN ('STB0001')
	--AND ( [tf].[Field_ID] = 13 OR [tf].[Field_ID] IS NULL )
ORDER BY 
	[s].[BlockSerialNo] ASC,
	[tst].[ID] ASC,
	[t].[ID] ASC
	";
	
	//$SQL = ( $uGuid ) ? str_replace("%%1%%", " AND [u].[Guid] = '$uGuid' ", $SQL) : str_replace("%%1%%", "", $SQL);
	//$SQL = ( $uGuid ) ? str_replace("%%uGuid%%", $uGuid, $SQL) : str_replace("%%uGuid%%", "", $SQL);
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


	sqlsrv_close($conn) ;


	$sql_server = "Shturman_Root";
	//$Server = $CONFIG_SHTURMAN["SQL_Alias_Server_Name"][$CONFIG_SHTURMAN["SQL_Server_".$sql_server]];
	$conn = MSSQLconnect( $sql_server, "Shturman_Root" );

	//$Server = "SpbMetro-sRoot";
	//$Server = "SpbMetro-sRoot";
	//$conn = MSSQLconnect( $Server, "Shturman" );

	$SQL ="
/****** Группы вагонов  ******/
SELECT 
	[s].Alias AS [BlockSerialNo],
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
	";

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$Block_Groups[$row["BlockSerialNo"]][] = $row;
		$Block_Groups_Code[$row["BlockSerialNo"]][] = iconv("Windows-1251", "UTF-8", $row["Code"]);
		$Block_Groups_Name[$row["BlockSerialNo"]][] = iconv("Windows-1251", "UTF-8", $row["Name"]);
		$Block_Groups_List[iconv("Windows-1251", "UTF-8", $row["Code"])] = iconv("Windows-1251", "UTF-8", $row["Name"]);
		
		//$Block_with_Groups[] = iconv("Windows-1251", "UTF-8", $row["BlockSerialNo"]);
	}
	//echo "<pre>";var_dump($Block_Groups);echo "</pre>";

	sqlsrv_close($conn) ;



	
	$template->set_filenames(array(
		'report' => 'incl/report_TaskActivity.tpl')
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
		'VEHICLE_GROUP' => ( $VehGrp ) ? @$Block_Groups_List[$VehGrp] : "Все",
	));
	
	$Block_Prefixes = explode(";", $CONFIG_SHTURMAN["Block_Prefixes"]);
	
	$Issue_ID_Prev = "";
	foreach ( is_array(@$data) ? $data : array() as $row )
	{
		$Issue_ID = $row["Issue_ID"];
		$Status = iconv("Windows-1251", "UTF-8", $row["Status"]);
		$Severity = iconv("Windows-1251", "UTF-8", $row["Severity"]);
		$BlockSerialNo = iconv("Windows-1251", "UTF-8", $row["BlockSerialNo"]);
		$BlockSerialNoShort = str_replace($Block_Prefixes,"",$BlockSerialNo);
		$ObjectTypeName = iconv("Windows-1251", "UTF-8", $row["ObjectTypeName"]);
		$Subject = iconv("Windows-1251", "UTF-8", $row["Subject"]);
		$Desigion = iconv("Windows-1251", "UTF-8", $row["Desigion"]);
		$Result = iconv("Windows-1251", "UTF-8", $row["Result"]);
		$Issue_DateTimeStart = $row["Issue_DateTimeStart"];
		$Issue_DateTimeStart = str_replace(" 00:00", "", $Issue_DateTimeStart);
		$Issue_DateTimeEnd = $row["Issue_DateTimeEnd"];
		$Issue_DateTimeEnd = str_replace(" 00:00", "", $Issue_DateTimeEnd);
		$Author = iconv("Windows-1251", "UTF-8", $row["Author"]);
		$Created = $row["Created"];
		$Finished = $row["Finished"];
		$Editor = iconv("Windows-1251", "UTF-8", $row["Editor"]);
		$Status_ChangeDate = $row["Status_ChangeDate"];
		$ChangedDate = $row["ChangedDate"];
		$Reporter = iconv("Windows-1251", "UTF-8", $row["Reporter"]);
		$Assigned_To = iconv("Windows-1251", "UTF-8", $row["Assigned_To"]);
		$FieldName = iconv("Windows-1251", "UTF-8", $row["FieldName"]);
		$Field_Field_ID = $row["Field_Field_ID"];
		$Field_Value = iconv("Windows-1251", "UTF-8", $row["Field_Value"]);
		$Field_Created = $row["Field_Created"];
		$Filed_LastEdit = $row["Filed_LastEdit"];
		$Field_Editor = iconv("Windows-1251", "UTF-8", $row["Field_Editor"]);
		$Field_Deleted = $row["Field_Deleted"];

		$Groups = @implode("; ", $Block_Groups_Name[$BlockSerialNo]);
		$GroupsCodes = @implode("; ", $Block_Groups_Code[$BlockSerialNo]);

		//$ShowRow = ( $VehGrp == 0 or in_array($VehGrp, $Block_Groups_Code[$BlockSerialNo])) ? TRUE : FALSE;
		$ShowRow = ( $VehGrp == "0" or @in_array($VehGrp, $Block_Groups_Code[$BlockSerialNo]) or !@$Block_Groups_Code[$BlockSerialNo][0] ) ? TRUE : FALSE;
		//$ShowRow = ( $VehGrp == 0 or in_array($Block_Groups[$BlockSerialNo], $VehGrp) ) ? TRUE : FALSE;
		//$Block_Groups[$BlockSerialNo]
		//echo "$BlockSerialNo=$VehGrp-$Groups-$GroupsCodes-$ShowRow-<br>";

		if ( $ShowRow )
		{

		if ( $Issue_ID_Prev != $Issue_ID ) 
		{
			$template->assign_block_vars('row',array(
				'ISSUE_ID' => $Issue_ID,
				'STATUS' => $Status,
				'SEVERITY' => $Severity,
				'BLOCKSERNO' => $BlockSerialNoShort,
				'OBJECT_TYPE_NAME' => $ObjectTypeName,
				'SUBJECT' => $Subject,
				'DESIGION' => $Desigion,
				'RESULT' => $Result,
				'ISSUE_DATE_START' => $Issue_DateTimeStart,
				'ISSUE_DATE_END' => $Issue_DateTimeEnd,
				'AUTHOR' => $Author,
				'CREATED' => $Created,
				'FINISHED' => $Finished,
				'EDITOR' => $Editor,
				'STATUS_CHANGE_DATE' => $Status_ChangeDate,
				'CHANGED' => $ChangedDate,
				'REPORTER' => $Reporter,
				'ASSIGNED_TO' => $Assigned_To,
				));
		}

		if ( $Issue_ID_Prev != $Issue_ID ) { $FirstField = TRUE; };
		//$FirstField = TRUE;

		if ( $Field_Field_ID == "13" and !$Field_Deleted ) 
		{
			if ( $FirstField ) 
			{
				$template->assign_block_vars('row.prop',array());
				$FirstField = FALSE;
			}
			
//			if ( !$Field_Deleted and $Field_Field_ID == "13" )
			//{
				$template->assign_block_vars('row.prop.prow',array(
					'FIELD_NAME' => $FieldName,
					'FIELD_VALUE' => $Field_Value,
					'FILED_ID' => $Field_Field_ID,
					'FIELD_CREATED' => $Field_Created,
					'FIELD_CHANGED' => $Filed_LastEdit,
					'FIELD_EDITOR' => $Field_Editor,
					'FIELD_DELETED' => $Field_Deleted,
				));
		//	}
		}
/*		
		if ( $Issue_ID_Prev == $Issue_ID or $FieldName and !$Field_Deleted and $Field_Field_ID == "13")
		{
			if ( $Issue_ID_Prev != $Issue_ID ) 
			{
				$template->assign_block_vars('row.prop',array());
			}
			
			$template->assign_block_vars('row.prop.prow',array(
				'FIELD_NAME' => $FieldName,
				'FIELD_VALUE' => $Field_Value,
				'FILED_ID' => $Field_Field_ID,
				'FIELD_CREATED' => $Field_Created,
				'FIELD_CHANGED' => $Filed_LastEdit,
				'FIELD_EDITOR' => $Field_Editor,
				'FIELD_DELETED' => $Field_Deleted,
			));
		}
		*/
		}

		$Issue_ID_Prev = $Issue_ID;

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
