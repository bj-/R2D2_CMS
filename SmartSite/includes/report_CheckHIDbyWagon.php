<?php
/***************************************************************************
 *                                report_CheckHIDbyWagon.php
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
	
//	$conn = MSSQLconnect( "SpbMetro-sRoot", "Shturman" );
//	$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );
	//$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

	$SQL = "
/* Количество сообщений выданных машинисту о необходимости поправить гарнитуру */

declare
  @Started datetime2(7) = '$dateFrom $timeFrom',
  @Finished datetime2(7) = '$DateTo $TimeTo';
  --@UserGuid uniqueidentifier = '$uGuid';
  
  
  /****** Script for SelectTopNRows command from SSMS  ******/
SELECT 
	[Alias] AS [BlockSerialNo], 
	--[StartedF],
	count (*) AS [Count]
	--[Text]
FROM (
SELECT --TOP 1000 
	--[Guid]
	[s].[Alias],
	--[Servers_Guid],
	--,[Sensors_Guid]
	--,[Users_Guid]
	--[Started],
	FORMAt([som].[Started], 'dd.MM.yyy') AS [StartedF],
	--,[Finished]
	[som].[Text]
	--,[Message_Types_Id]
	--,[Services_Guid]
	--,[Written]
FROM [Stages_Onboard_Message] as [som]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [som].[Servers_Guid]
WHERE 
	[Text] = 'Поправьте датчик'
	AND [Started] BETWEEN @Started and @Finished
	%%1%%
	--and happend between @Started and @Finished
	--order by [Started] desc
) AS t
--GROUP BY [StartedF]
GROUP BY [Alias]
ORDER BY [Alias]
--ORDER BY [StartedF] DESC
	";
	
//	$SQL = ( $uGuid ) ? str_replace("%%1%%", " AND [u].[Guid] = '$uGuid' ", $SQL) : $SQL_wagons;
	$SQL = ( $uGuid ) ? str_replace("%%1%%", " AND [som].[Users_Guid] = '$uGuid' ", $SQL) : str_replace("%%1%%", "", $SQL);
	//echo "<pre>$SQL</pre>";
	//exit;

	$SQL = iconv("UTF-8", "Windows-1251", $SQL);

	set_time_limit(60); // увеличиваем время выполнения скрипта

	
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[]= $row;
	}
	//echo "<pre>";var_dump(@$data);echo "</pre>";

	$template->set_filenames(array(
		'report' => 'incl/report_CheckHIDbyWagon.tpl')
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
		//$FIO = iconv("Windows-1251", "UTF-8", $row["FIO"]);
		//$SerialNo = $row["SerialNo"];
		//$Date =  $row["Date"];
		//$AvgWorkingTime = $row["AvgWorkingTime"];
		$Count = $row["Count"];
		//$Sleep_Cabin = $row["Sleep_Cabin"];
		//$Stress_Cabin = $row["Stress_Cabin"];
		//$Sleep_Operator = $row["Sleep_Operator"];
		//$Stress_Operator = $row["Stress_Operator"];
		//$Written = $row["Written"];
		//$RR_Count = $row["RR_Count"];
		$BlockSerialNo = $row["BlockSerialNo"];
		
		$template->assign_block_vars('row',array(
			//'FIO' => $FIO,
			//'SERIALNO' => $SerialNo,
			//'DATE' => $Date,
			//'DATE' => $Written,
			//'AVG_WORKING_TIME' => $AvgWorkingTime,
			'COUNT' => $Count,
			//'COUNT' => $RR_Count,
			//'SLEEP_CABIN' => $Sleep_Cabin,
			//'STRESS_CABIN' => $Stress_Cabin,
			//'SLEEP_OPERATOR' => $Sleep_Operator,
			//'STRESS_OPERATOR' => $Stress_Operator,
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
