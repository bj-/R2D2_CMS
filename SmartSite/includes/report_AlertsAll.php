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
//	echo '<pre>'; print_r($topmenu_pids); echo '</pre>';

	$date = @$filters["date"];
	$time = @$filters["time"];
	$dateFrom = @$filters["dateFrom"];
	$timeFrom = @$filters["timeFrom"];
	$DateTo = @$filters["dateTo"];
	$TimeTo = @$filters["timeTo"];
	$prefix = @$filters["Prefix"];
	//$ = $data[""];

	$sql_server = "Shturman_Root";
	//$sql_server_addr = $CONFIG_SHTURMAN["SQL_Server_".$sql_server]; 
	$Server = $CONFIG_SHTURMAN["SQL_Alias_Server_Name"][$CONFIG_SHTURMAN["SQL_Server_".$sql_server]];
	
//	$conn = MSSQLconnect( "SpbMetro-sRoot", "Shturman" );
	$conn = MSSQLconnect( $sql_server, "Shturman_Root" );
	//$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

	//$conn = MSSQLconnect( "SpbMetro-sRoot", "Shturman" );

	$SQL = "
/****** Количество тревог гиперактиваций / монотоний / медосмотров / попратедатчик  ******/
declare
	@from datetime2(7) = '$dateFrom $timeFrom',
	@to datetime2(7) = '$DateTo $TimeTo';

--select concat('Согласно данным аналитического терминала АСПМ, за период с ',
--format(@from, 'dd.MM.yyy'), ' по ',format(dateADD(day,-1,@to), 'dd.MM.yyy'),' на терминал оперативного мониторинга (оператору линейного пункта) было выдано:') AS [rep]
--union
SELECT
	count(*) AS [Count], 'Тревожных сообщений при опасном отклонении функционального состояния машиниста в сторону «монотонии»:' as txt
FROM [Shturman3].[dbo].[Events_System]
where NeedsConfirmation = 1
	and Created between @from and @to
	and Events_Actions_ID = 15
	--and text like '%онот%'

union
SELECT
	count(*) AS [Count], 'Тревожных сообщения при опасном отклонении функционального состояния машиниста в сторону «гиперактивации»:' as txt
--[Text],
--[Events_Actions_ID]
FROM [Shturman3].[dbo].[Events_System]
where NeedsConfirmation = 1
	and Created between @from and @to
	and Events_Actions_ID = 16
	--and text like '%тивац%'
union
SELECT
	count(*) AS [Count], 'Рекомендации направить машиниста на внутрисменный медицинский осмотр:' as txt
--[Text],
--[Events_Actions_ID]
FROM [Shturman3].[dbo].[Events_System]
where NeedsConfirmation = 1
	and Created between @from and @to
	and Events_Actions_ID = 22
	--and text like '%тивац%'
union
SELECT
	count(*) AS [Count], 'Рекомендаций связаться с машинистом и указать ему на необходимость поправить индивидуальный датчик:' as txt
--[Text],
--[Events_Actions_ID]
FROM [Shturman3].[dbo].[Events_System]
where NeedsConfirmation = 1
	and Created between @from and @to
	and Events_Actions_ID = 19
	--and text like '%тивац%'

";

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[]= $row;
	}


	$template->set_filenames(array(
		'report' => 'incl/report_AlertsAll.tpl')
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
	
	foreach ( $data as $row )
	{
		$value = $row["Count"];
		$text = $row["txt"];
		$template->assign_block_vars('row',array(
			'NAME' => $text,
			'VALUE' => $value,
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
