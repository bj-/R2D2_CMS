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
	
//	$conn = MSSQLconnect( "SpbMetro-sRoot", "Shturman" );
//	$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );
	//$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

	$SQL = "
/* Количество сообщений выданных машинисту о необходимости поправить гарнитуру */

declare
  @Started datetime2(7) = '$dateFrom $timeFrom',
  @Finished datetime2(7) = '$DateTo $TimeTo';
  --@UserGuid uniqueidentifier = '$uGuid';

select [Date], [DateSRT], count(*) AS [Count] from (

select 
	FORMAT(onb.[Happend], 'dd.MM.yyy') AS [Date],
	FORMAT(onb.[Happend], 'yyy-MM-dd') AS [DateSRT]
	--s.Alias AS [BlockSerailNo]
	--onb.[Source],
	--onb.[Happend],
	--onb.[Text]
	--count (*)
from Events_Onbtext onb
join Servers s on s.Guid = onb.Server_Guid
where onb.Guid in (
  select distinct evt.Guid from Events_Onbtext evt, Stages_Users_Vehicles stg, Servers srv, Vehicles vhl, users u
  where Happend between @Started and @Finished
  and stg.Started <= evt.Happend and evt.Happend <= stg.Finished
  and evt.Text like '%поправьте%'
  and srv.Guid = evt.Server_Guid
  and vhl.Guid = srv.Vehicles_Guid
  and stg.Users_Guid = u.Guid
  %%1%%
  --and stg.Users_Guid = @UserGuid
)
--GROUP BY s.Alias, onb.[Source], onb.[Text]
--group by onb.[Happend], s.Alias
--ORDER BY 
--	onb.[Happend] ASC, s.Alias
) as t
Group by t.[Date], t.[DateSRT]
ORDER BY t.[DateSRT] DESC
	";

$SQL = "
/* Количество сообщений выданных машинисту о необходимости поправить гарнитуру */

declare
  @Started datetime2(7) = '$dateFrom $timeFrom',
  @Finished datetime2(7) = '$DateTo $TimeTo';
  --@UserGuid uniqueidentifier = '$uGuid';
  
SELECT 
	--[Alias] AS [BlockSerialNo], 
	--FORMAt([Date], 'dd.MM.yyy') AS [Date],
	[Date],
	count (*) AS [Count]
	--[Text]
FROM (
SELECT --TOP 1000 
	--[Guid]
	--[s].[Alias],
	--[Servers_Guid],
	--,[Sensors_Guid]
	--,[Users_Guid]
	--[Started],
	--FORMAt([som].[Started], 'dd.MM.yyy') AS [Date],
	FORMAt([som].[Started], 'yyy.MM.dd') AS [Date],
	--,[Finished]
	[som].[Text]
	--,[Message_Types_Id]
	--,[Services_Guid]
	--,[Written]
FROM [Stages_Onboard_Message] as [som]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [som].[Servers_Guid]
WHERE 
	[som].[Text] = 'Поправьте датчик'
	AND [som].[Started] BETWEEN @Started and @Finished
	%%1%%
	--and happend between @Started and @Finished
	--order by [Started] desc
) AS t
GROUP BY [Date]
--GROUP BY [Alias]
--ORDER BY [Alias]
ORDER BY [Date] DESC
";
	
	$SQL = ( $uGuid ) ? str_replace("%%1%%", " AND [som].[Users_Guid] = '$uGuid' ", $SQL) : str_replace("%%1%%", "", $SQL);
	//echo "<pre>$SQL</pre>";

	$SQL = iconv("UTF-8", "Windows-1251", $SQL);

	set_time_limit(60); // увеличиваем время выполнения скрипта

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[]= $row;
	}
	//echo "<pre>";var_dump(@$data);echo "</pre>";

	$template->set_filenames(array(
		'report' => 'incl/report_CheckHIDbyDate.tpl')
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
		$Date =  $row["Date"];
		//$AvgWorkingTime = $row["AvgWorkingTime"];
		$Count = $row["Count"];
		//$Sleep_Cabin = $row["Sleep_Cabin"];
		//$Stress_Cabin = $row["Stress_Cabin"];
		//$Sleep_Operator = $row["Sleep_Operator"];
		//$Stress_Operator = $row["Stress_Operator"];
		//$Written = $row["Written"];
		//$RR_Count = $row["RR_Count"];
		//$BlockSerialNo = $row["BlockSerialNo"];
		
		$template->assign_block_vars('row',array(
			//'FIO' => $FIO,
			//'SERIALNO' => $SerialNo,
			'DATE' => $Date,
			//'DATE' => $Written,
			//'AVG_WORKING_TIME' => $AvgWorkingTime,
			'COUNT' => $Count,
			//'COUNT' => $RR_Count,
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
