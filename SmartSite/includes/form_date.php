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
function filter_date( $type, $data ) 
{
	/*
		$type		= тип (по какому полю соритруется FIO или SEN (номеру сенсора)
		$valueType	= Что будет указано в качестве Value - GUID | NAME (Фио с носером сенсоар версией прошики и зарядом)
		
	*/
	global $template, $conn;
//	global $topmenu_pids, $topmenu_data, $url_lang;

	$ret = "";

//	echo "ssdfsdfs";
//	echo '<pre>'; print_r($topmenu_pids); echo '</pre>';

	$template->set_filenames(array(
		'date' => 'incl/date.tpl')
	);

	$date = @$data["date"];
	$time = @$data["time"];
	$dateFrom = @$data["dateFrom"];
	$timeFrom = @$data["timeFrom"];
	$DateTo = @$data["dateTo"];
	$TimeTo = @$data["timeTo"];
	$prefix = @$data["Prefix"];
	//$ = $data[""];

	$template->assign_vars(array(
		'DATE' => $date,
		'TIME' => $time,
		'DATE_FROM' => $dateFrom,
		'TIME_FROM' => $timeFrom,
		'DATE_TO' => $DateTo,
		'TIME_TO' => $TimeTo,
		'PREFIX' => $prefix,
	));
	
	if ( $type == "Date" )
	{
		$template->assign_block_vars('date',array());
	}
	elseif ( $type == "Time" )
	{
		$template->assign_block_vars('time',array());
	}
	elseif ( $type == "DateTime" )
	{
		$template->assign_block_vars('date',array());
		$template->assign_block_vars('time',array());
	}
	elseif ( $type == "DatePeriod" )
	{
	}
	elseif ( $type == "TimePeriod" )
	{
	}
	elseif ( $type == "DateTimePeriod" )
	{
		$template->assign_block_vars('from',array());
		$template->assign_block_vars('date_from',array());
		$template->assign_block_vars('time_from',array());
		$template->assign_block_vars('to',array());
		$template->assign_block_vars('date_to',array());
		$template->assign_block_vars('time_to',array());
	}
	else
	{
		$template->assign_block_vars('date',array());
		$template->assign_block_vars('time',array());
	}
/*
	$template->assign_block_vars('row',array());

	$template->assign_block_vars('row.item',array(
		'NAME' => "",
		'VALUE' => "",
		'INDENT' => "", // " &nbsp; &nbsp; " // Отступ в листе
	));
*/
	$template->assign_var_from_handle('FILTER_DATE', 'date');
	
	return $ret;

};
?>
