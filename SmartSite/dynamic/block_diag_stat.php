<?php
/***************************************************************************
 *                                block_diag_stat.php
 *                            -------------------
 *   begin                : Jun 13, 2018
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: blockdetails.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   License
 *
 ***************************************************************************/
$microTime=microtime(true); 

define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');


// Get params from url
//$AlertOnly = ( strtoupper(@$_GET["AlertOnly"]) == "TRUE" ) ? TRUE : FALSE;
//$NeedRepairing = ( strtoupper(@$_GET["NeedRepairing"]) == "TRUE" ) ? TRUE : FALSE;
//$NoLegend = ( strtoupper(@$_GET["NoLegend"]) == "TRUE" ) ? TRUE : FALSE;

$Block = ( @$_GET["BlockSerNo"] ) ? str_remove_sql_char(substr(@$_GET["BlockSerNo"], 0, 20)) : "";


//exit;

//var_dump (@$_GET);

//$bp["wagon_in_train_not_connected_warning"] = 5760; // minutes

$rnd = rand ( 0 , 1000000000 );


$conn = MSSQLconnect( "Diag", "Diag" );
//$conn = MSSQLconnect( "SpbMetro-sRoot", "Shturman" );


$SQL = "
SELECT 
	[Block_First],
	[Block_Second],
	[Vehicle_First],
	[Vehicle_Second],
	[stF].[stShortName] AS [BlockType_First],
	[stS].[stShortName] AS [BlockType_Second],
	[Coupling],
	[Train_First],
	[Train_Second],
	[Route],
	[PropertyName_First],
	[Value_First],
	[Value_First_PerHour],
	[PropertyName_Second],
	[Value_Second],
	[Value_Second_PerHour],
	[Value_Diff_Percent],
	[Value_Diff_perHour_Percent],
	[SrvcWrkTime_First],
	[SrvcWrkTime_Second],
	[SrvcWrkTime_DiffPercent],
	FORMAT([Date], 'dd.MM.yyy') AS [DateF],
	[Date] AS [Date],
	FORMAT([Written], 'dd.MM.yyy') AS [Written]
FROM [Block_Diag_Stat_Full] AS [stat]
LEFT JOIN [Servers] AS [sF] ON [sF].[BlockSerialNo] = [stat].[Block_First]
LEFT JOIN [Servers] AS [sS] ON [sS].[BlockSerialNo] = [stat].[Block_Second]
LEFT JOIN [Server_Types] AS [stF] ON [stF].[Guid] = [sF].[ServerType_Guid]
LEFT JOIN [Server_Types] AS [stS] ON [stS].[Guid] = [sS].[ServerType_Guid]
WHERE 1=1
	AND [Block_First] in ('%%SERIALNO%%')
ORDER BY 
	
	[Block_First] ASC,
	[Date] DESC,
	[PropertyName_First] ASC
";

$SQL = ( $Block ) ? str_replace("%%SERIALNO%%",$Block, $SQL ) : $SQL ;
//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//echo "Step1b: [" . (microtime(true) - $microTime) . "] sec<br />";

$data = Array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//	if ( !in_array($row["BlockSerialNo"], $BlockList ) ) { $BlockList[] = $row["BlockSerialNo"]; }
	$data[]= $row;
}
//echo "<pre>"; var_dump($data); echo "</pre>";



sqlsrv_close($conn) ;


$currentdate = date("Y-m-d", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));

function style_diff_percent10_60($diff, $Value_Diff_Percent, $direction = "DOWN", $Scale_Up = 10, $Scale_Dn = 10, $color = "red")
{
	global $style;
	
	$Style_row = ( $diff and $Value_Diff_Percent >= -10 and $Value_Diff_Percent <= 10 ) ? "color: darkgrey;" : "";
	
	if ( strtoupper($direction) == "DOWN" or strtoupper($direction) == "BOTH" )
	{
		$Style_row = ( $diff and $Value_Diff_Percent < -1*$Scale_Dn ) ? $style["bg-lllll-$color"] : $Style_row;
		$Style_row = ( $diff and $Value_Diff_Percent < -2*$Scale_Dn ) ? $style["bg-llll-$color"] : $Style_row;
		$Style_row = ( $diff and $Value_Diff_Percent < -3*$Scale_Dn ) ? $style["bg-lll-$color"] : $Style_row;
		$Style_row = ( $diff and $Value_Diff_Percent < -4*$Scale_Dn ) ? $style["bg-ll-$color"] : $Style_row;
		$Style_row = ( $diff and $Value_Diff_Percent < -5*$Scale_Dn ) ? $style["bg-l-$color"] : $Style_row;
		$Style_row = ( $diff and $Value_Diff_Percent < -6*$Scale_Dn ) ? $style["bg-$color"] : $Style_row;
	}
	if ( strtoupper($direction) == "UP" or strtoupper($direction) == "BOTH" )
	{
		$Style_row = ( $diff and $Value_Diff_Percent > 1*$Scale_Up ) ? $style["bg-lllll-$color"] : $Style_row;
		$Style_row = ( $diff and $Value_Diff_Percent > 2*$Scale_Up ) ? $style["bg-llll-$color"] : $Style_row;
		$Style_row = ( $diff and $Value_Diff_Percent > 3*$Scale_Up ) ? $style["bg-lll-$color"] : $Style_row;
		$Style_row = ( $diff and $Value_Diff_Percent > 4*$Scale_Up ) ? $style["bg-ll-$color"] : $Style_row;
		$Style_row = ( $diff and $Value_Diff_Percent > 5*$Scale_Up ) ? $style["bg-l-$color"] : $Style_row;
		$Style_row = ( $diff and $Value_Diff_Percent > 6*$Scale_Up ) ? $style["bg-$color"] : $Style_row;
	}
	$Style_row = ( !$diff ) ? "color: darkgrey;" : $Style_row;

	return $Style_row;
}

function write_property($section, $Values, $Prop)
{
	global $template, $srvc_wrk_actual;

	$format 		= ( @$Prop["format"] ) 			? $Prop["format"] 				: "";		// [kkk | hours | FALSE] 	- формат значения (перевести в "ккк" или в "часы/минуты/дни")
	$invert 		= ( @$Prop["invert"] ) 			? $Prop["invert"] 				: FALSE;

	$direction		= ( $invert ) 					? "DOWN" 						: "UP";	// Временка. - конвертация инверта в дирекшин
	$direction		= ( @$Prop["direction"] ) 		? strtoupper($Prop["direction"]): $direction; //FALSE;	// [Up | Down | Both | False] - в какую сторону отклонение считать критичным

	$Scale_Up		= ( @$Prop["Scale_Up"] ) 		? $Prop["Scale_Up"] 			: 10;		// Масштаб процентов - вверх (фактически множитель), т.е. краснеть начет не с 10, а с 10*множитель
	$Scale_Dn		= ( @$Prop["Scale_Dn"] ) 		? $Prop["Scale_Dn"] 			: 10;		// тоже но в ниже нуля
	$diff_limit 	= ( @$Prop["diff_limit"] ) 		? $Prop["diff_limit"] 			: 5;
	$diff_limit_PH 	= ( @$Prop["diff_limit_PH"] ) 	? $Prop["diff_limit_PH"] 		: 1;

	$val_limit_F	= ( @$Prop["val_limit_F"] ) 	? $Prop["val_limit_F"] 			: 0;
	//$val_limit 		= ( @$Prop["val_limit"] ) 		? $Prop["val_limit"] 			: 0;
	$val_limit_S	= ( @$Prop["val_limit"] ) 		? $Prop["val_limit"] 			: 0;
	$val_limit_S	= ( @$Prop["val_limit_S"] ) 	? $Prop["val_limit_S"] 			: $val_limit_S;

	$val_limit_F_PH = ( @$Prop["val_limit_F_PH"] ) 	? $Prop["val_limit_F_PH"] 		: 0;
	//$val_limit_PH 	= ( @$Prop["val_limit_PH"] ) 	? $Prop["val_limit_PH"] 		: 0;
	$val_limit_S_PH = ( @$Prop["val_limit_PH"] ) 	? $Prop["val_limit_PH"] 		: 0;
	$val_limit_S_PH = ( @$Prop["val_limit_S_PH"] ) 	? $Prop["val_limit_S_PH"] 		: $val_limit_S_PH;
	
	//$val_limit_O	= ( @$Prop["val_limit_O"] ) 	? $Prop["val_limit_O"] 			: 0;	// если значение превысит указанное - оно не будет краситься серым.
	//$val_limit_O_PH	= ( @$Prop["val_limit_O_PH"] ) 	? $Prop["val_limit_O_PH"] 		: 0;	// тоже но в час
	$bg_color 		= ( @$Prop["color"] ) 			? $Prop["color"] 				: "red";



	$Coupling 			= $Values["Coupling"];
	$First 				= $Values["Value_First"];
	$Second 			= $Values["Value_Second"];
	$Diff_Percent 		= $Values["Value_Diff_Percent"];
	$First_PerHour 		= $Values["Value_First_PerHour"];
	$Second_PerHour 	= $Values["Value_Second_PerHour"];
	$Diff_PH_Percent 	= $Values["Value_Diff_perHour_Percent"];
	$Type_First 		= ( $Values["BlockType_First"]  == "olimex" ) ? TRUE : FALSE;
	$Type_Second 		= ( $Values["BlockType_Second"] == "olimex" ) ? TRUE : FALSE;
	
	$Second_Exist		 = $Values["Second_Exist"]; // Second property is exist

/*
row.conn
row.stations
row.sthconn
row.sth
row.stl
row.rr
row.lostbg
*/
	// Показывать/скрывать значение
	if ( in_array($section, array("row.lostant","row.lostink","row.lostst","row.lostgps","row.lostmodem")) )
	{
		$ShowFirst  = ( $Type_First )  ? TRUE : FALSE;
		$ShowSecond = ( $Type_Second ) ? TRUE : FALSE;
	}
	else
	{
		$ShowFirst  = TRUE;
		$ShowSecond = TRUE;
	}
	
	$diff 		= ( abs($First-$Second) > $diff_limit 
					and $Coupling 
					and ( $First > $val_limit_F or $Second > $val_limit_S )
					and $srvc_wrk_actual
					//AND 
					) ? TRUE : FALSE;
	$Style_diff = style_diff_percent10_60($diff, $Diff_Percent, $direction, $Scale_Up, $Scale_Dn, $bg_color);

	$diff_PH 		= ( abs($First_PerHour-$Second_PerHour) > $diff_limit_PH 
						and $Coupling 
						and ( $First > $val_limit_F or $Second > $val_limit_S )
						and ( $First_PerHour > $val_limit_F_PH or $Second_PerHour > $val_limit_S_PH )
						) ? TRUE : FALSE;
	$Style_diff_PH = style_diff_percent10_60($diff_PH, $Diff_PH_Percent, $direction, $Scale_Up, $Scale_Dn, $bg_color);
	
	if ( $format == "kkk" )
	{
		$First_F = int2kkk($First);
		$Second_F = ( $Second ) ? int2kkk($Second) : "";
		$Avg_F = int2kkk(round(($First + $Second ) / 2));
		$First_PerHour_F = int2kkk($First_PerHour);
		$Second_PerHour_F = ( $Second_PerHour ) ? int2kkk($Second_PerHour) : "";
		$Avg_PerHour_F = int2kkk(round(($Second_PerHour + $Second_PerHour ) / 2));
	}
	elseif ( $format == "hours" )
	{
		$First_F = sec2hours($First);
		$Second_F = ( $Second ) ? sec2hours($Second) : "";
		$Avg_F = sec2hours(round(($First + $Second ) / 2));
		$First_PerHour_F = sec2hours($First_PerHour);
		$Second_PerHour_F = ( $Second_PerHour ) ? sec2hours($Second_PerHour) : "";
		$Avg_PerHour_F = sec2hours(round(($Second_PerHour + $Second_PerHour ) / 2));
	}
	else
	{
		$First_F = $First;
		$Second_F = ( $Second ) ? $Second : "";
		$Avg_F = round(($First + $Second ) / 2);
		$First_PerHour_F = $First_PerHour;
		$Second_PerHour_F = ( $Second_PerHour ) ? $Second_PerHour : "";
		$Avg_PerHour_F = round(($First_PerHour_F + $Second_PerHour ) / 2);
	}

	if ( $ShowFirst or $ShowSecond )
	{
		//if ( $diff )
		// замена индивидуальных значений на среднее, если значений в пределах допуска. либо на первое, если оно всего одно.
		if ( $Second_Exist )
		{
			if (
				abs($Diff_Percent) < 5 
				or abs($First - $Second ) < $diff_limit 
				)
			{
				$section = $section . "_avg";
			}
			$avg = round(($First + $Second ) / 2);
			$avg_PH = round(($First_PerHour + $Second_PerHour ) / 2);
		}
		else
		{
			$section = $section . "_avg";
			$avg = $First;
			$avg_PH = $First_PerHour;
			$Avg_F = $First_F;
			$Avg_PerHour_F = $First_PerHour_F;
		}
	//$avg .= "[".$Second_Exist."]";
		/*
		if ( 
			
			and (
				abs($Diff_Percent) < 5 
				or abs($First - $Second ) < $diff_limit 
				)
			//and $Diff_Percent > -5
			)
		{
			$section = $section . "_avg";
		}
		*/
		
		$Diff_Percent_str = ( $Diff_Percent > 100 ) ? round($Diff_Percent/100) . "x" : $Diff_Percent . "%";
		
		$template->assign_block_vars($section, array(
			'FIRST' 	=> ( $ShowFirst ) 	? $First 				: "-",
			'SECOND' 	=> ( $ShowSecond ) 	? $Second 				: "-",
			'AVG'	 	=> $avg,
			'FIRST_F' 	=> ( $ShowFirst ) 	? $First_F 				: "-",
			'SECOND_F' 	=> ( $ShowSecond ) 	? $Second_F 			: "-",
			'AVG_F'	 	=> $Avg_F,
			'DIFF' 		=> ( $diff ) 		? $Diff_Percent_str 	: "-",
	
			'FIRST_PH' 		=> ( $ShowFirst ) 	? $First_PerHour 			: "-",
			'SECOND_PH' 	=> ( $ShowSecond ) 	? $Second_PerHour 			: "-",
			'AVG_PH'	 	=> $avg_PH,
			'FIRST_F_PH' 	=> ( $ShowFirst ) 	? $First_PerHour_F 			: "-",
			'SECOND_F_PH' 	=> ( $ShowSecond ) 	? $Second_PerHour_F 		: "-",
			'AVG_F_PH'	 	=> $Avg_PerHour_F,
			'DIFF_PH' 		=> ( $diff_PH ) 	? $Diff_PH_Percent . "%" 	: "-",
	
			'STYLE_DIFF' 	=> $Style_diff,
			'STYLE_DIFF_PH' => $Style_diff_PH,
		));
		
		/*
		$section =  str_replace("row.", "", $section);
		$template->set_filenames(array(
			$section => 'incl/blockdiagstat_cell.tpl')
			);

		$template->assign_block_vars('row.diff', array(
			'VAL_FIRST' => $First_F . "--" . $Second_F,
		));


		$template->assign_var_from_handle('CELL_' . strtoupper($section), $section);
		*/
	}
}

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'block_diag_stat.tpl')
);


$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'BLOCK' => $Block,
	));


$DateNew = TRUE;
$DateLast = "";
foreach ( $data as $item )
{
	
 	$Block_First = iconv("Windows-1251", "UTF-8", $item["Block_First"]);
 	$Block_Second = iconv("Windows-1251", "UTF-8", $item["Block_Second"]);
 	$Vehicle_First = iconv("Windows-1251", "UTF-8", $item["Vehicle_First"]);
 	$Vehicle_Second = iconv("Windows-1251", "UTF-8", $item["Vehicle_Second"]);
 	$BlockType_First = iconv("Windows-1251", "UTF-8", $item["BlockType_First"]);
 	$BlockType_Second = iconv("Windows-1251", "UTF-8", $item["BlockType_Second"]);

 	$Coupling = iconv("Windows-1251", "UTF-8", $item["Coupling"]);
 	$Train_First = iconv("Windows-1251", "UTF-8", $item["Train_First"]);
 	$Train_Second = iconv("Windows-1251", "UTF-8", $item["Train_Second"]);

 	$Route = iconv("Windows-1251", "UTF-8", $item["Route"]);
	
 	$PropertyName_First = $item["PropertyName_First"];
 	$Value_First = $item["Value_First"];
 	$Value_First_PerHour = $item["Value_First_PerHour"];
 	$PropertyName_Second = $item["PropertyName_Second"];
	$Second_Exist = ( strlen($PropertyName_Second) ) ? TRUE : FALSE;
 	$Value_Second = $item["Value_Second"];
 	$Value_Second_PerHour = $item["Value_Second_PerHour"];
 	$Value_Diff_Percent = $item["Value_Diff_Percent"];
 	$Value_Diff_perHour_Percent = $item["Value_Diff_perHour_Percent"];

 	$SrvcWrkTime_First = $item["SrvcWrkTime_First"];
 	$SrvcWrkTime_Second = $item["SrvcWrkTime_Second"];
 	$SrvcWrkTime_DiffPercent = ( ($SrvcWrkTime_Second - $SrvcWrkTime_First) == $SrvcWrkTime_Second and $SrvcWrkTime_First == 0 ) ? -100 : $item["SrvcWrkTime_DiffPercent"];

 	$Date = $item["DateF"];
	$DateNew = ( $DateLast != $Date ) ? TRUE : FALSE;

 	$Written = $item["Written"];

	$Values = Array(
					"Coupling" => $Coupling,
					"Value_First" => $Value_First, 
					"Value_Second" => $Value_Second, 
					"Value_Diff_Percent" => $Value_Diff_Percent, 
					"Value_First_PerHour" => $Value_First_PerHour, 
					"Value_Second_PerHour" => $Value_Second_PerHour, 
					"Value_Diff_perHour_Percent" => $Value_Diff_perHour_Percent,
					"BlockType_First" => $BlockType_First, 
					"BlockType_Second" => $BlockType_Second, 
					"Second_Exist" => $Second_Exist,
					);


	//$Style_Attention = "";
	//$Style_Attention = ( $bcfg_StatDIOSPacketErrorReceivedCntMin > 1 ) ?  $style["bg-llll-red"] : $StyleAttention;

	if ( $DateNew )
	{
		$diff = ( $SrvcWrkTime_First > 10800 or $SrvcWrkTime_Second > 10800 ) ? TRUE : FALSE;  // только если сервисы работали больше 3х часов.
		$srvc_wrk_actual = $diff;
		$SrvcWrkTime_DiffPercent = ( $diff and $Coupling ) ? $SrvcWrkTime_DiffPercent : "-";

		$template->assign_block_vars('row', array(
			'BLOCK_FIRST' => $Block_First,
			'BLOCK_SECOND' => $Block_Second,
			'VEHICLE_FIRST' => $Vehicle_First,
			'VEHICLE_SECOND' => $Vehicle_Second,
			'COUPLING' => $Coupling,
			'DATE' => str_replace(".20", "", str_replace(".20", ".", $Date)),
		));

		if ( abs($SrvcWrkTime_DiffPercent) > 5 )
		{
			$template->assign_block_vars('row.srvc_wrk_diff', array(
				'SRVC_TIME_FIRST' => $SrvcWrkTime_First,
				'SRVC_TIME_FIRST_F' => sec2hours($SrvcWrkTime_First),
				'SRVC_TIME_SECOND' => $SrvcWrkTime_Second,
				'SRVC_TIME_SECOND_F' => ( $SrvcWrkTime_Second ) ? sec2hours($SrvcWrkTime_Second) : "",
				//'SRVC_TIME_DIFF' => ( $SrvcWrkTime_First-$SrvcWrkTime_Second <= 100 ) ? $SrvcWrkTime_First-$SrvcWrkTime_Second . $SrvcWrkTime_DiffPercent : "-",
				'SRVC_TIME_DIFF' => ( $diff ) ? $SrvcWrkTime_DiffPercent . "%" : "-",
				'SRVC_STYLE_DIFF' => style_diff_percent10_60($diff, $SrvcWrkTime_DiffPercent, "Down", null, 5),
			));
		}
		else
		{
			$template->assign_block_vars('row.srvc_wrk_avg', array(
				'SRVC_TIME' => sec2hours(round(($SrvcWrkTime_First+$SrvcWrkTime_Second)/2)),

				'SRVC_TIME_FIRST' => $SrvcWrkTime_First,
				'SRVC_TIME_FIRST_F' => sec2hours($SrvcWrkTime_First),
				'SRVC_TIME_SECOND' => $SrvcWrkTime_Second,
				'SRVC_TIME_SECOND_F' => ( $SrvcWrkTime_Second ) ? sec2hours($SrvcWrkTime_Second) : "",
				'SRVC_TIME_DIFF' => ( $diff ) ? $SrvcWrkTime_DiffPercent . "%" : "-",
			));
		}
	}
	
	switch ( $PropertyName_First )
	{
		case "WorkingTime":  // Modem connection time
			write_property('row.conn', $Values, $Prop=Array("format"=>"hours", "direction"=>"Down", "diff_limit"=>5, "diff_limit_PH"=>1, "val_limit"=>3600, "Scale_Dn"=>15));
		break;
		case "StationsCount":
			write_property('row.stations', $Values, $Prop=Array("format"=>"kkk", "val_limit"=>100, "diff_limit"=>50, "direction"=>"Down"));
		break;
		case "BG_STH_ConnectCount":
			write_property('row.sthconn', $Values, $Prop=Array("direction"=>"Both", "Scale_Up"=>100, "color"=>"yellow" ));
		break;
		case "BG_STH_Count":
			write_property('row.sth', $Values, $Prop=Array("format"=>"kkk", "direction"=>"Down", "diff_limit"=>200, "Scale_Dn"=>15)); //"diff_limit_I"=>, "diff_limit_I_PH"=>1,
		break;
		case "BG_STL_Count":
			write_property('row.stl', $Values, $Prop=Array("direction"=>"Down", "val_limit"=>100, "diff_limit"=>100, "Scale_Dn"=>15));
		break;
		case "RRCount":
			write_property('row.rr', $Values, $Prop=Array("format"=>"kkk", "direction"=>"Down", "diff_limit"=>1000, "val_limit_PH"=>100));
		break;
		case "USBLostANTCnt":
			write_property('row.lostant', $Values, $Prop=Array());
		break;
		case "USBLostBGCnt":
			write_property('row.lostbg', $Values, $Prop=Array("Scale_Up"=>150));
		break;
		case "USBLostINKCnt":
			write_property('row.lostink', $Values, $Prop=Array());
		break;
		case "USBLostSimTechCnt":
			write_property('row.lostst', $Values, $Prop=Array("diff_limit"=>20, "diff_limit"=>90, "Scale_Up"=>20));
		break;
		case "USBLostSimTechGPSCnt":
			write_property('row.lostgps', $Values, $Prop=Array("diff_limit"=>10));
		break;
		case "USBLostSimTechModemCnt":
			write_property('row.lostmodem', $Values, $Prop=Array("diff_limit"=>10));
		break;
		case "onb_Station":
			write_property('row.onb_Station', $Values, $Prop=Array("diff_limit"=>50, "direction"=>"Down"));
		break;
		case "onb_HID_Check":
			write_property('row.onb_HID_Check', $Values, $Prop=Array("diff_limit"=>10));
		break;
		case "onb_Driver":
			write_property('row.onb_Driver', $Values, $Prop=Array("diff_limit"=>5, "direction"=>"Down"));
		break;
		case "onb_HID_Broken":
			write_property('row.onb_HID_Broken', $Values, $Prop=Array("diff_limit"=>5));
		break;
		case "onb_NoConnection":
			write_property('row.onb_NoConnection', $Values, $Prop=Array("diff_limit"=>1));
		break;
	}
/*
	$template->assign_block_vars('row', array(
		'BLOCK_FIRST' => $Block_First,
		'xxx' => $Block_Second,
		'xxx' => $Vehicle_First,
		'xxx' => $Vehicle_Second,
		'xxx' => $Coupling,
		'xxx' => $Train_First,
		'xxx' => $Train_Second,
		'xxx' => $Route,
		'xxx' => $PropertyName_First,
		'xxx' => $xxx,
		'xxx' => $xxx,
		'xxx' => $xxx,
		'xxx' => $xxx,
		'xxx' => $xxx,
		'xxx' => $xxx,
		'xxx' => $xxx,
		'xxx' => $xxx,

		// Styles
		'STYLE_CONNECTED' => $StyleConnected,
		'STYLE_CONNECTED_ALERT' => $StyleConnectedAlert,
		'STYLE_STARTED_DATE_FAIL_ALERT' => ($bcfg_StartTimeFailsCnt) ? $style["bg-l-red"] : "",
		

		'TEST' => @$test,
		));
*/

	//$template->assign_block_vars('row.wagonnotconnected', array());
	
	// указываем текущую дату как последнюю использованную
	$DateLast = $Date;

}


// Легенда
$NoLegend = FALSE;
if ( !$NoLegend)
{
	$template->assign_block_vars('legend', array(
	));
}


// Update Block List in Diag DB




$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));

// Page generation time spent
$microTime = microtime(true) - $microTime;
$template->assign_vars(array(
	'SPENT_TIME' => $microTime,
));

$template->pparse('body');
?>