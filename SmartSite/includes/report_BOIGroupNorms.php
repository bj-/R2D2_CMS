<?php
/***************************************************************************
 *                                report_BOIGroupNorms.php
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

	
	$SQL = $SQL_QUERY["Persons_NormsGroup"];
	$SQL = str_replace("%%FROM%%", $dateFrom, $SQL);
	$SQL = str_replace("%%TO%%", $DateTo, $SQL);

	
	//$SQL = ( $uGuid ) ? str_replace("%%1%%", " AND [u].[Guid] = '$uGuid' ", $SQL) : str_replace("%%1%%", "", $SQL);
	//echo "<pre>$SQL</pre>";

	//$SQL = iconv("UTF-8", "Windows-1251", $SQL);

	set_time_limit(60); // увеличиваем время выполнения скрипта

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[]= $row;
	}
	//echo "<pre>";var_dump(@$data);echo "</pre>";

	$template->set_filenames(array(
		'report' => 'incl/report_BOIGroupNorms.tpl')
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
		//'FIO' => iconv("Windows-1251", "UTF-8", $xFIO),
	));
	
	foreach ( is_array(@$data) ? $data : array() as $row )
	{
		//$FIO = iconv("Windows-1251", "UTF-8", $row["FIO"]);
		//$SerialNo = $row["SerialNo"];
		//$Date =  $row["Date"];
		//$AvgWorkingTime = $row["AvgWorkingTime"];
		//$Count = $row["Count"];
		//$Sleep_Cabin = $row["Sleep_Cabin"];
		//$Stress_Cabin = $row["Stress_Cabin"];
		//$Sleep_Operator = $row["Sleep_Operator"];
		//$Stress_Operator = $row["Stress_Operator"];
		//$State = iconv("Windows-1251", "UTF-8", $row["State"]);
		//$Started = $row["Started"];
		//$Finished = $row["Finished"];

		$Calculated = $row["Calculated"];
		$Validity = $row["Validity"];
		$RR_MD_Min = $row["RR_MD_Min"];
		$RR_MD_Max = $row["RR_MD_Max"];
		$RR_MD_Avg = $row["RR_MD_Avg"];
		$RR_AM_Min = $row["RR_AM_Min"];
		$RR_AM_Max = $row["RR_AM_Max"];
		$RR_AM_Avg = $row["RR_AM_Avg"];
		$BOI_MD_Min = $row["BOI_MD_Min"];
		$BOI_MD_Max = $row["BOI_MD_Max"];
		$BOI_MD_Avg = $row["BOI_MD_Avg"];
		$BOI_AM_Min = $row["BOI_AM_Min"];
		$BOI_AM_Max = $row["BOI_AM_Max"];
		$BOI_AM_Avg = $row["BOI_AM_Avg"];
		
		//$Written = $row["Written"];
		//$RR_Count = $row["RR_Count"];
		//$BlockSerialNo = $row["BlockSerialNo"];
		
		$template->assign_block_vars('row',array(
			//'FIO' => $FIO,
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
			//'STATE' => $State,
			//'STARTED' => $Started,
			//'FINISHED' => $Finished,
			//'BLOCK' => $BlockSerialNo,

			'CALCULATED' => $Calculated,
			'VALIDITY' => $Validity,
			'RR_MD_MIN' => $RR_MD_Min,
			'RR_MD_MAX' => $RR_MD_Max,
			'RR_MD_AVG' => $RR_MD_Avg,
			'RR_AM_MIN' => $RR_AM_Min,
			'RR_AM_MAX' => $RR_AM_Max,
			'RR_AM_AVG' => $RR_AM_Avg,
			'BOI_MD_MIN' => $BOI_MD_Min,
			'BOI_MD_MAX' => $BOI_MD_Max,
			'BOI_MD_AVG' => $BOI_MD_Avg,
			'BOI_AM_MIN' => $BOI_AM_Min,
			'BOI_AM_MAX' => $BOI_AM_Max,
			'BOI_AM_AVG' => $BOI_AM_Avg,
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
