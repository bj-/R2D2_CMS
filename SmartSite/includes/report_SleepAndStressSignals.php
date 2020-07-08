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

	$sql_server = "Shturman_Root";
	$Server = $CONFIG_SHTURMAN["SQL_Alias_Server_Name"][$CONFIG_SHTURMAN["SQL_Server_".$sql_server]];
	$conn = MSSQLconnect( $sql_server, "Shturman_Root" );
	//$conn = MSSQLconnect( "SpbMetro-sRoot", "Shturman" );
	//$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

	$SQL = "
/* Сигналы Монотония и Стресс в вагоне и оператору (за период) */

DECLARE
	@Started datetime2(7) = '$dateFrom $timeFrom',
	@Finished datetime2(7) = '$DateTo $TimeTo';

Select 
	[usr].[Last_Name], [usr].[First_Name], [usr].[Middle_Name],
	[usr].[FIO],
	[usr].[HID] AS [SerialNo],
	[slpW].[cnt] AS [Sleep_Cabin],
	[strsW].[cnt] AS [Stress_Cabin],
	[slpO].[cnt] AS [Sleep_Operator],
	[strsO].[cnt] AS [Stress_Operator]
FROM
	(
	SELECT
		[p].[Last_Name], [p].[First_Name], [p].[Middle_Name],
		CONCAT([p].[Last_Name], ' ', [p].[First_Name], ' ', [p].[Middle_Name]) AS [FIO],
		[sn].[Name] AS [HID],
		[u].[Guid] AS [User_Guid]
	FROM 
		[Users] AS [u]
		INNER JOIN [Users_Persons] AS [p] on [p].[Guid] = [u].[Users_Persons_Guid]
		INNER JOIN [Users_In_Groups] AS [ug] ON [ug].[Users_Guid] = [u].[Guid]
		INNER JOIN [Groups] AS [g] ON [g].[Guid] = [ug].[Groups_Guid]
		INNER JOIN [Sensors_Cardio] AS [sc] ON [sc].[Users_Guid] = [u].[Guid]
		INNER JOIN [Sensors] AS [sn] ON [sn].[Guid] = [sc].[Guid]
		INNER JOIN [Users_Roles] AS [r] ON [r].[Guid] = [u].[Users_Roles_Guid]
	WHERE 1=1
		AND [g].[Name] = 'Линия 3'
		AND [r].[Code] = 'Driver'
		%%1%%
	GROUP BY [p].[Last_Name], [p].[First_Name], [p].[Middle_Name], [u].[Guid], [sn].[Name]
	) AS [usr]
	LEFT JOIN
	(
		SELECT 
			[sus].[Users_Guid], 
			[us].[Code], 
			COUNT(*) AS [cnt]
		FROM 
		[Stages_Users_States] AS [sus]
		LEFT JOIN [Users_States] AS [us] ON [us].[Id] = [sus].[Users_States_Id]
		WHERE  1=1
			AND [us].[Code] = 'SignalSleep'
			AND (
				[sus].[Started] BETWEEN @Started AND @Finished
				OR [sus].[Finished] BETWEEN @Started AND @Finished
			)
		GROUP BY [sus].[Users_Guid], [us].[Code]
		) AS [slpW] ON [slpW].[Users_Guid] = [usr].[User_Guid]
	LEFT JOIN
	(
		SELECT 
			[sus].[Users_Guid], 
			[us].[Code], 
			COUNT(*) AS [cnt]
		FROM 
		[Stages_Users_States] AS [sus]
		LEFT JOIN [Users_States] AS [us] ON [us].[Id] = [sus].[Users_States_Id]
		WHERE  1=1
			AND [us].Code = 'SignalStress'
				--)
			AND (
				[sus].[Started] BETWEEN @Started AND @Finished
				OR [sus].[Finished] BETWEEN @Started AND @Finished
			)
		GROUP BY [sus].[Users_Guid], [us].[Code]
	) AS [strsW] ON [strsW].[Users_Guid] = [usr].[User_Guid]
	LEFT JOIN
	(
		SELECT 
			[ese].[Entity_Guid] AS [Users_Guid], 
			COUNT(*) AS [cnt]
		FROM  Events_System AS [es]
			INNER JOIN [Events_System_Entities] AS [ese] ON [ese].[Events_System_Guid] = [es].[Guid]
			INNER JOIN [Events_Actions] AS [ea] ON [ea].[Id] = [es].[Events_Actions_ID]
		WHERE  1=1
			AND [ese].[Entity_Name] = 'Users'
			AND [es].[NeedsConfirmation] = 1
			AND [ea].[Code] = 'Sleep'
			AND [es].[Created] BETWEEN @Started AND @Finished
		GROUP BY [ese].[Entity_Guid]
	) AS [slpO] ON [slpO].[Users_Guid] = [usr].[User_Guid]
	LEFT JOIN
	(
		SELECT 
			[ese].[Entity_Guid] AS [Users_Guid], 
			COUNT(*) AS [cnt]
		FROM  Events_System AS [es]
			INNER JOIN [Events_System_Entities] AS [ese] ON [ese].[Events_System_Guid] = [es].[Guid]
			INNER JOIN [Events_Actions] AS [ea] ON [ea].[Id] = [es].[Events_Actions_ID]
		WHERE  1=1
			AND [ese].[Entity_Name] = 'Users'
			AND [es].[NeedsConfirmation] = 1
			AND [ea].[Code] = 'Stress'
			AND [es].[Created] BETWEEN @Started AND @Finished
		GROUP BY [ese].[Entity_Guid]
	) AS [strsO] ON [strsO].[Users_Guid] = [usr].[User_Guid]
WHERE 1=1
	-- AND ([slp].[cnt] IS NOT NULL OR [strs].[cnt] IS NOT NULL) -- remove empty
ORDER BY [usr].[FIO] ASC
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
		'report' => 'incl/report_SleepAndStressSignals.tpl')
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
		$Sleep_Cabin = $row["Sleep_Cabin"];
		$Stress_Cabin = $row["Stress_Cabin"];
		$Sleep_Operator = $row["Sleep_Operator"];
		$Stress_Operator = $row["Stress_Operator"];
		
		$template->assign_block_vars('row',array(
			'FIO' => $FIO,
			'SERIALNO' => $SerialNo,
			//'AVG_WORKING_TIME' => $AvgWorkingTime,
			//'COUNT' => $Count,
			'SLEEP_CABIN' => $Sleep_Cabin,
			'STRESS_CABIN' => $Stress_Cabin,
			'SLEEP_OPERATOR' => $Sleep_Operator,
			'STRESS_OPERATOR' => $Stress_Operator,
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
