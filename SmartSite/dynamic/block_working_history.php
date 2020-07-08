<?php
/***************************************************************************
 *                                block_working_history.php
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
$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));

define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');


// Get params from url
$iBlockSerNo = str_remove_sql_char(substr(@$_GET["BlockSerNo"], 0, 50));


$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

$SQL_QUERY["block_working_history"] = "
/****** Wagons history BT, Modems, etc ******/
SELECT
	[BlockSerialNo],
	[Wagon],
	[Train],
	[ProperyName],
	[Value],
	--,[Written],
	FORMAT(DATEADD(day, -1, [Written]), 'dd.MM.yyy') AS [WrittenDay]
FROM [ServersHistory]
WHERE 1=1
	%%1%%
	AND [ProperyName] IN ('WorkingTime', 'StationsCount', 'RRCount', 'BG_STH_Count', 'BG_STH_ConnectCount', 'BG_STL_Count')
	AND [Written] > DATEADD(day,-90,SYSDATETIME())
ORDER BY 
	[Written] DESC
";

$SQL = str_replace("%%1%%", "AND [BlockSerialNo] = '$iBlockSerNo'", $SQL_QUERY["block_working_history"]);

//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data[] = Array();
//$second[] = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$Date = $row["WrittenDay"];
	$BlockSerialNo = $row["BlockSerialNo"];
	$Coupling = ( $row["Train"] ) ? $row["Train"] : "---";
	$Wagon = $row["Wagon"];
	$Wagon_Second = str_replace("-", "", str_replace($Wagon, "", $Coupling));
	$ProperyName = $row["ProperyName"];
	$Value = $row["Value"];
	$day = $row["WrittenDay"];

	
	
	//$data[$WrittenDay]["BlockSerialNo"] = $BlockSerialNo;
	//$data[$WrittenDay][$BlockSerialNo] = $row;
//	$data[$Date][$Coupling][$ProperyName]["Value"][] = $Value;
	$data[$Date][$Coupling][$ProperyName] = $Value;
	$data[$Date][$Coupling]["Vehicle_Second"] = $Wagon_Second;
	$data[$Date]["BlockSerialNo"] = $BlockSerialNo;
	$data[$Date]["Wagon"] = $Wagon;
	$data[$Date]["Day"] = $day;
	if ( @!in_array($Coupling, $data[$Date]["Couplings"]) )
	{
		$data[$Date]["Couplings"][] = $Coupling;
	}
	if ( @!in_array($Wagon_Second, $second) and strlen(trim($Wagon_Second)) )
	{
		//echo "[$Wagon_Second]";
		$second[] = $Wagon_Second;
	}
}

//echo "<pre>";var_dump($second);echo "</pre>";
//echo "<pre>";var_dump($data);echo "</pre>";

$SQL = str_replace("%%1%%", "AND [Wagon] IN ( '" . implode("', '", $second ) . "' )", $SQL_QUERY["block_working_history"]);

//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data_second[] = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$Coupling = ( $row["Train"] ) ? $row["Train"] : "---";
	$Wagon = $row["Wagon"];
	$Date = $row["WrittenDay"];
	$ProperyName = $row["ProperyName"];
	$Coupling = ( $row["Train"] ) ? $row["Train"] : "---";
	$Value = $row["Value"];

//	$Wagon_Second = str_replace("-", "", str_replace($Wagon, "", $Coupling));

	$data_second[$Wagon][$Date][$Coupling][$ProperyName] = $Value;
}

//echo "<pre>";var_dump($data_second);echo "</pre>";

sqlsrv_close($conn) ;

// sqlsrv_close($conn) ;

$currentdate = date("Y-m-d", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'block_working_history.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,

	));

/*
	$template->assign_block_vars('row', array(
		'L_LINK' => $l_Link,
	));

*/

foreach ( @$data as $item )
{
//	$LastName = iconv("Windows-1251", "UTF-8", $data[$i]["Last_Name"]);

	$BlockSerialNo = $item["BlockSerialNo"];
	$Wagon = $item["Wagon"];
	$Day = $item["Day"];

	foreach ( $item["Couplings"] as $couplingId )
	{
		$Coupling = $couplingId;
		$Vehicle_Second = @$item[$couplingId]["Vehicle_Second"];
		$Modem = @$item[$couplingId]["WorkingTime"];
		$BT = @$item[$couplingId]["StationsCount"];
		$RR = @$item[$couplingId]["RRCount"];
		$STH = @$item[$couplingId]["BG_STH_Count"];
		$STH_Connect = @$item[$couplingId]["BG_STH_ConnectCount"];
		$STL = @$item[$couplingId]["BG_STL_Count"];
		
		
		$Modem_Second = @$data_second[$Vehicle_Second][$Day][$Coupling]["WorkingTime"];
		$BT_Second    = @$data_second[$Vehicle_Second][$Day][$Coupling]["StationsCount"];
		$RR_Second    = @$data_second[$Vehicle_Second][$Day][$Coupling]["RRCount"];
		$STH_Second			= @$data_second[$Vehicle_Second][$Day][$Coupling]["BG_STH_Count"];
		$STH_Connect_Second = @$data_second[$Vehicle_Second][$Day][$Coupling]["BG_STH_ConnectCount"];
		$STL_Second			= @$data_second[$Vehicle_Second][$Day][$Coupling]["BG_STL_Count"];

		$Modem_Diff = ( $Modem_Second and ( $Modem > 3000 or $Modem_Second > 3000 ) ) ? round(($Modem / $Modem_Second * 100) - 100) . " %"  : "--" ;
		$BT_Diff = ( $BT_Second and ( $BT > 100 or $BT_Second > 100 ) ) ? round(($BT / $BT_Second * 100) - 100) . " %" : "--";
		$RR_Diff = ( $RR_Second and ( $RR > 3000 or $RR_Second > 3000 ) ) ? round(($RR / $RR_Second * 100) - 100) . " %" : "--";
		$STH_Diff = ( $STH_Second and ( $STH > 1000 or $STH_Second > 1000 ) ) ? round(($STH / $STH_Second * 100) - 100) . " %" : "--";
		$STH_Connect_Diff = ( $STH_Connect_Second and ( $STH_Connect > 10 or $STH_Connect_Second > 10 ) ) ? round(($STH_Connect / $STH_Connect_Second * 100) - 100) . " %" : "--";
		$STL_Diff = ( $STL_Second and ( $STL > 100 or $STL_Second > 100 ) ) ? round(($STL / $STL_Second * 100) - 100) . " %" : "--";

		$Style_Modem = "";
		$Style_Modem = ( $Modem_Diff < -10 ) ? $style["bg-lllll-red"] : $Style_Modem;
		$Style_Modem = ( $Modem_Diff < -20 ) ? $style["bg-llll-red"] : $Style_Modem;
		$Style_Modem = ( $Modem_Diff < -30 ) ? $style["bg-lll-red"] : $Style_Modem;
		$Style_Modem = ( $Modem_Diff < -40 ) ? $style["bg-ll-red"] : $Style_Modem;
		$Style_Modem = ( $Modem_Diff < -50 ) ? $style["bg-l-red"] : $Style_Modem;
		$Style_Modem = ( $Modem_Diff < -75 ) ? $style["bg-red"] : $Style_Modem;


		$Style_BT = "";
		$Style_BT = ( $BT_Diff < -10 ) ? $style["bg-lllll-red"] : $Style_BT;
		$Style_BT = ( $BT_Diff < -20 ) ? $style["bg-llll-red"] : $Style_BT;
		$Style_BT = ( $BT_Diff < -30 ) ? $style["bg-lll-red"] : $Style_BT;
		$Style_BT = ( $BT_Diff < -40 ) ? $style["bg-ll-red"] : $Style_BT;
		$Style_BT = ( $BT_Diff < -50 ) ? $style["bg-l-red"] : $Style_BT;
		$Style_BT = ( $BT_Diff < -75 ) ? $style["bg-red"] : $Style_BT;

		$Style_RR = "";
		$Style_RR = ( $RR_Diff < -10 ) ? $style["bg-lllll-red"] : $Style_RR;
		$Style_RR = ( $RR_Diff < -20 ) ? $style["bg-llll-red"] : $Style_RR;
		$Style_RR = ( $RR_Diff < -30 ) ? $style["bg-lll-red"] : $Style_RR;
		$Style_RR = ( $RR_Diff < -40 ) ? $style["bg-ll-red"] : $Style_RR;
		$Style_RR = ( $RR_Diff < -50 ) ? $style["bg-l-red"] : $Style_RR;
		$Style_RR = ( $RR_Diff < -75 ) ? $style["bg-red"] : $Style_RR;

		$Style_STH = "";
		$Style_STH = ( $STH_Diff < -10 ) ? $style["bg-lllll-red"] : $Style_STH;
		$Style_STH = ( $STH_Diff < -20 ) ? $style["bg-llll-red"] : $Style_STH;
		$Style_STH = ( $STH_Diff < -30 ) ? $style["bg-lll-red"] : $Style_STH;
		$Style_STH = ( $STH_Diff < -40 ) ? $style["bg-ll-red"] : $Style_STH;
		$Style_STH = ( $STH_Diff < -50 ) ? $style["bg-l-red"] : $Style_STH;
		$Style_STH = ( $STH_Diff < -75 ) ? $style["bg-red"] : $Style_STH;

		$Style_STH_Connect = "";
		$Style_STH_Connect = ( $STH_Connect_Diff < -10 ) ? $style["bg-lllll-red"] : $Style_STH_Connect;
		$Style_STH_Connect = ( $STH_Connect_Diff < -20 ) ? $style["bg-llll-red"] : $Style_STH_Connect;
		$Style_STH_Connect = ( $STH_Connect_Diff < -30 ) ? $style["bg-lll-red"] : $Style_STH_Connect;
		$Style_STH_Connect = ( $STH_Connect_Diff < -40 ) ? $style["bg-ll-red"] : $Style_STH_Connect;
		$Style_STH_Connect = ( $STH_Connect_Diff < -50 ) ? $style["bg-l-red"] : $Style_STH_Connect;
		$Style_STH_Connect = ( $STH_Connect_Diff < -75 ) ? $style["bg-red"] : $Style_STH_Connect;

		$Style_STL = "";
		$Style_STL = ( $STL_Diff < -10 ) ? $style["bg-lllll-red"] : $Style_STL;
		$Style_STL = ( $STL_Diff < -20 ) ? $style["bg-llll-red"] : $Style_STL;
		$Style_STL = ( $STL_Diff < -30 ) ? $style["bg-lll-red"] : $Style_STL;
		$Style_STL = ( $STL_Diff < -40 ) ? $style["bg-ll-red"] : $Style_STL;
		$Style_STL = ( $STL_Diff < -50 ) ? $style["bg-l-red"] : $Style_STL;
		$Style_STL = ( $STL_Diff < -75 ) ? $style["bg-red"] : $Style_STL;

		
		$template->assign_block_vars('row', array(
			'DAY' => $Day,
			'BLOCKSERIALNO' => $BlockSerialNo,
			'WAGON' => $Wagon,
			'COUPLING' => $Coupling,
			'WAGON_SECOND' => '',
			//'TRAIN' => $Train,
			'MODEM' => $Modem,
			'MODEM_SECOND' => $Modem_Second,
			'MODEM_DIFF' => $Modem_Diff,
			'BT' => $BT,
			'BT_SECOND' => $BT_Second,
			'BT_DIFF' => $BT_Diff,

			'RR' => $RR,
			'RR_SECOND' => $RR_Second,
			'RR_DIFF' => $RR_Diff,

			'STH' => $STH,
			'STH_SECOND' => $STH_Second,
			'STH_DIFF' => $STH_Diff,

			'STH_CONNECT' => $STH_Connect,
			'STH_CONNECT_SECOND' => $STH_Connect_Second,
			'STH_CONNECT_DIFF' => $STH_Connect_Diff,

			'STL' => $STL,
			'STL_SECOND' => $STL_Second,
			'STL_DIFF' => $STL_Diff,
			
			'STYLE_MODEM' => $Style_Modem,
			'STYLE_BT' => $Style_BT,
			'STYLE_RR' => $Style_RR,
			'STYLE_STH' => $Style_STH,
			'STYLE_STH_CONNECT' => $Style_STH_Connect,
			'STYLE_STL' => $Style_STL,
		));

	}


}

if ( TRUE ) 
{
	$template->assign_block_vars('legend', array(
	));
}


$template->pparse('body');
?>