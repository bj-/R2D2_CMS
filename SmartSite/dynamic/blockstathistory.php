<?php
define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/common.php');
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');

//$wagon = Array();
//$wagon[0] = "22013";
//$wagon[1] = "22015";

$wagonF = substr(@$_GET["id"],0,10);


//var_dump($wagon);

//$wagons = implode("', '", $wagon);

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

$query = "
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
WHERE
	[Wagon] in ( '%%1%%' )
	AND [Written] > DATEADD(day,-90,SYSDATETIME())
ORDER BY 
	[Written] DESC
";

$SQL = str_replace("%%1%%", $wagonF, $query);

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
//var_dump($stmt);

$data = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
	$second[] = str_replace("-", "", str_replace($row["Wagon"], "", $row["Train"]));
}

//var_dump($second);


$wagons = @implode("', '", array_unique(@$second));
$SQL = str_replace("%%1%%", $wagons, $query);

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
//var_dump($stmt);

$dataSec = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$dataSec[$row["WrittenDay"]][$row["Wagon"]][$row["ProperyName"]] = $row["Value"];
//	$second = str_replace("-", "", str_replace($data["Wagon"], "", $data["Train"]));
}


sqlsrv_close($conn) ;

//echo "<pre>";
//var_dump ($data);
//echo "</pre>";

//echo "<h3>Block Error Details</h3>";





echo "
<h2>Vehicle: $wagonF</h2>
<table cellspacing='1px' cellpadding='0px' class='tbl' width='60%' border='0' style='background:#CCCCCC;'>
	<tr>
		<th>Date</th>
		<th>Wagon</th>
		<th>Modem</th>
		<th>Modem %</th>
		<th>BT</th>
		<th>BT %</th>
	</tr>
";

$newDate = false;
$olddate = "";
$i = 0;
while ( @$data[$i]["WrittenDay"] )
{
	$BlockSerialNo = $data[$i]["BlockSerialNo"];
	$Wagon = $data[$i]["Wagon"];
	$Train = $data[$i]["Train"];
	$ProperyName = $data[$i]["ProperyName"];
	$Value = $data[$i]["Value"];
	$day = $data[$i]["WrittenDay"];

	$newDate = ( $day != $olddate and $olddate != "") ? true : false;
	$olddate = $day;

//	echo $olddate . "<br>"; 

	if ( $newDate )
	{
//		$PowerMon = ( @$aStat["PowerMon"]["TotalCnt"] > 0 ) ? $aStat["PowerMon"]["Block"] ." / ".  $aStat["PowerMon"]["TotalCnt"] : ""; 
 		$rDate = $aData["Date"];
		$rTrain = $aData["Train"];
		$wagonSecond = str_replace("-", "", str_replace($wagonF, "", $rTrain));
		$rWorkingTimeFirst = $aData["WorkingTimeFirst"];
		$rWorkingTimeSecond = @$dataSec[$rDate][$wagonSecond]["WorkingTime"];
		$rStationsCountFirst = $aData["StationsCountFirst"];
		$rStationsCountSecond = @$dataSec[$rDate][$wagonSecond]["StationsCount"];
		$wagonFirst = $wagonF;

		$rWorkingTimeDiff = ( $rWorkingTimeSecond and ( $rWorkingTimeFirst > 3000 or $rWorkingTimeSecond > 3000 ) ) ? round(($rWorkingTimeFirst / $rWorkingTimeSecond * 100) - 100) . " %"  : "--" ;
		$rStationsCountDiff = ( $rStationsCountSecond and ( $rStationsCountFirst > 100 or $rStationsCountSecond > 100 ) ) ? round(($rStationsCountFirst / $rStationsCountSecond * 100) - 100) . " %" : "--";

		$rTimeStyleWarn = "";
		$rTimeStyleWarn = ( $rWorkingTimeDiff < -10 ) ? $style["bg-lllll-red"] : $rTimeStyleWarn;
		$rTimeStyleWarn = ( $rWorkingTimeDiff < -20 ) ? $style["bg-llll-red"] : $rTimeStyleWarn;
		$rTimeStyleWarn = ( $rWorkingTimeDiff < -30 ) ? $style["bg-lll-red"] : $rTimeStyleWarn;
		$rTimeStyleWarn = ( $rWorkingTimeDiff < -40 ) ? $style["bg-ll-red"] : $rTimeStyleWarn;
		$rTimeStyleWarn = ( $rWorkingTimeDiff < -50 ) ? $style["bg-l-red"] : $rTimeStyleWarn;
		$rTimeStyleWarn = ( $rWorkingTimeDiff < -75 ) ? $style["bg-red"] : $rTimeStyleWarn;


		$rStationStyleWarn = "";
		$rStationStyleWarn = ( $rStationsCountDiff < -10 ) ? $style["bg-lllll-red"] : $rStationStyleWarn;
		$rStationStyleWarn = ( $rStationsCountDiff < -20 ) ? $style["bg-llll-red"] : $rStationStyleWarn;
		$rStationStyleWarn = ( $rStationsCountDiff < -30 ) ? $style["bg-lll-red"] : $rStationStyleWarn;
		$rStationStyleWarn = ( $rStationsCountDiff < -40 ) ? $style["bg-ll-red"] : $rStationStyleWarn;
		$rStationStyleWarn = ( $rStationsCountDiff < -50 ) ? $style["bg-l-red"] : $rStationStyleWarn;
		$rStationStyleWarn = ( $rStationsCountDiff < -75 ) ? $style["bg-red"] : $rStationStyleWarn;

		echo "
			<tr align='center'>
				<td>$rDate</td>
				<td>$wagonFirst<span style='color:gray;'>-$wagonSecond</span></td>
				<td>$rWorkingTimeFirst / $rWorkingTimeSecond</td>
				<td style='$rTimeStyleWarn'>$rWorkingTimeDiff</td>
				<td>$rStationsCountFirst / $rStationsCountSecond</td>
				<td style='$rStationStyleWarn'>$rStationsCountDiff</td>
			</tr>
			";
		$newDate = false;
		$aData = array();
	}
//	$aData["WagonFirst"] = ($wagon[0] $day;
//	$aData["WagonFirst"] = $day;
//	$aData["WagonSecond"] = $day;
	$aData["Date"] = $day;
	$aData["Train"] = $Train;
	$aData["WorkingTimeFirst"] = (  $ProperyName == "WorkingTime" ) ? $Value : @$aData["WorkingTimeFirst"];
	$aData["StationsCountFirst"] =  ( $ProperyName == "StationsCount" ) ? $Value : @$aData["StationsCountFirst"];
//	$aStat[$ShortName]["TotalCnt"] = $TotalCnt;
//	var_dump($aData);
	$i++;
	
}
echo "</table>";

?>
