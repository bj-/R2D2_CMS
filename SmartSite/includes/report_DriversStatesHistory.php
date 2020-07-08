<?php
/***************************************************************************
 *                                report_DriversStatesHistory.php
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

	$SQL = "
/****** история нахождения водителей в различных состояниях  ******/

declare
  @Started datetime2(7) = '$dateFrom $timeFrom',
  @Finished datetime2(7) = '$DateTo $TimeTo';
  --@UserGuid uniqueidentifier = '$uGuid';

SELECT 
	 TOP 1000
CONCAT([p].[Last_Name], ' ', [p].[First_Name],  ' ', [p].[Middle_Name]) AS [FIO],
[s].[Name] AS [State],
format(dateadd(hour, 3, [sus].[Started]),'dd.MM.yyyy HH:mm:ss') AS [Started],
format(dateadd(hour, 3, [sus].[Finished]),'dd.MM.yyyy HH:mm:ss') AS [Finished],
format(dateadd(hour, 3, [sus].[Written]),'dd.MM.yyyy HH:mm:ss') AS [Written]

 --     ,[Users_Guid]
--      ,us.[Users_States_Id]
FROM [Stages_Users_States] AS [sus]
join [Users_States] AS [s] on [sus].[Users_States_Id] = [s].[id]
join [Users] AS [u] ON sus.Users_Guid = [u].[Guid]
join [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
where 
	dateadd(hour, 3, [sus].[Started]) > @Started  
	AND dateadd(hour,3 ,[sus].[Finished]) < @Finished
	%%1%%
	--[u].[Guid] = ''
	--[Last_Name] like '%Андреев%' or  [Last_Name] like '%Машугин%'
order by 
	[p].[Last_Name] ASC,
	[p].[First_Name] ASC,
	[p].[Middle_Name] ASC,
	[sus].[Started] DESC
      
/*
SELECT TOP 1000
      s.Name
     ,u.[Written]
       , u.Users_States_Changed
	   [Last_Name]
      ,[First_Name]
      ,[Middle_Name]
 --     ,[Users_Guid]
--      ,us.[Users_States_Id]
  FROM [Shturman3].[dbo].[Users] u
  join   [Shturman3].[dbo].[Users_States] s on u.Users_States_Id = s.id
  join [Shturman3].[dbo].[Users_Persons] p on p.Guid = u.Users_Persons_Guid

  where
   [Last_Name] like '%анд%'
order by u.Users_States_Changed  desc
*/
	";
	
	$SQL = ( $uGuid ) ? str_replace("%%1%%", " AND [u].[Guid] = '$uGuid' ", $SQL) : str_replace("%%1%%", "", $SQL);
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
		'report' => 'incl/report_DriversStatesHistory.tpl')
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
		//$Date =  $row["Date"];
		//$AvgWorkingTime = $row["AvgWorkingTime"];
		//$Count = $row["Count"];
		//$Sleep_Cabin = $row["Sleep_Cabin"];
		//$Stress_Cabin = $row["Stress_Cabin"];
		//$Sleep_Operator = $row["Sleep_Operator"];
		//$Stress_Operator = $row["Stress_Operator"];
		$State = iconv("Windows-1251", "UTF-8", $row["State"]);
		$Started = $row["Started"];
		$Finished = $row["Finished"];
		
		$Written = $row["Written"];
		//$RR_Count = $row["RR_Count"];
		//$BlockSerialNo = $row["BlockSerialNo"];
		
		$template->assign_block_vars('row',array(
			'FIO' => $FIO,
			//'SERIALNO' => $SerialNo,
			//'DATE' => $Date,
			//'DATE' => $Written,
			//'AVG_WORKING_TIME' => $AvgWorkingTime,
			//'COUNT' => $Count,
			//'COUNT' => $RR_Count,
			//'SLEEP_CABIN' => $Sleep_Cabin,
			//'STRESS_CABIN' => $Stress_Cabin,
			//'SLEEP_OPERATOR' => $Sleep_Operator,
			//'STRESS_OPERATOR' => $Stress_Operator,
			'STATE' => $State,
			'STARTED' => $Started,
			'FINISHED' => $Finished,
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
