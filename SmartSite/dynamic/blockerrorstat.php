<?php
define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/common.php');
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');

//<!-- Это необходимо один раз только дял того чтоб окошко на весь экран открывалось -->
echo '
<div class="textwrapper">
	<a href="include-long.htm" onclick="return hs.htmlExpand(this, { objectType: \'ajax\', contentId: \'highslide-html-8\' } )"
			class="highslide">
	</a>
	<div class="highslide-html-content" id="highslide-html-8" style="padding: 15px; width: auto">
	    <div class="highslide-body" style="padding: 10px"></div>
	</div>

</div>
';


$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

$SQL = "
/****** Services Versions and settings by blocks  ******/
SELECT --TOP 1000
	[s].[Alias] AS [BlockSerialNo],
	[sv].[Alias] AS [ServiceName],
	[sa].[Name] AS [PropertyName],
	[sa].[Value],
	--[sa].[Created],
	[sa].[Modified],
	--[sa].[Written],
	[sv].[Is_Connected],
	[sv].[Changed] AS [ConnectionChanged]
FROM [Services_Attributes] AS [sa]
LEFT JOIN [services] AS [sv] ON [sv].[Guid] = [sa].[Services_Guid]
LEFT JOIN [Servers] AS [s] ON [s].[Guid] = [sv].[Servers_Guid]
WHERE [s].[Alias] LIKE 'STB%'
	--AND [sv].[Is_Connected] = 1
ORDER BY [s].[Alias] ASC, [sv].[Alias] ASC, [sa].[Name] ASC, [sa].[Value] DESC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$ServicesNamesList = array();
$ServicesPropsList = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

	$ServicesProperties[$row["BlockSerialNo"]][$row["ServiceName"]][$row["PropertyName"]]= $row;

	// Create all exist Services list
	if ( !in_array($row["ServiceName"], $ServicesNamesList)  )
	{
		$ServicesNamesList[] = $row["ServiceName"];
		//echo $row["ServiceName"];
	}
	if ( !in_array($row["PropertyName"], $ServicesPropsList)  )
	{
		$ServicesPropsList[] = $row["PropertyName"];
		//echo $row["PropertyName"];
	}
	
}

sqlsrv_close($conn) ;




$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

//$ServerID = str_remove_sql_char(substr(@$_GET["Server"], 0, 20));

//$ServerID = ( strlen($ServerID) == 36 ) ? $ServerID : "00000000-0000-0000-0000-000000000000";


$rnd = rand ( 0 , 1000000000 );

$SQL = $SQL_QUERY["BlockLogErrorsBySrvcStat"];
//$SQL = str_replace("%%1%%" , "AND [ble].[DateTime] > DATEADD(hour,-72,SYSDATETIME()) AND [s].[BlockSerialNo] = '$ServerID'", $SQL);

$SQL = str_replace("%%1%%", "AND [ble].[DateTime] > DATEADD(hour,-72,SYSDATETIME())", $SQL);


//echo $SQL;

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
//var_dump($stmt);

$data = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}
//echo "<pre>";
//var_dump ($data);
//echo "</pre>";

//echo "<h3>Block Error Details</h3>";

$SQL = $SQL_QUERY["BlockLogErrorsBySrvcStat"];
//$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-24,SYSDATETIME()) AND DATEADD(hour,-0,SYSDATETIME())", $SQL);
$SQL = str_replace("%%1%%", "AND [ble].[DateTime] > DATEADD(hour,-24,SYSDATETIME())", $SQL);
//$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-24,SYSDATETIME()) AND DATEADD(hour,-18,SYSDATETIME())", $SQL);

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
$data0 = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data0[$row["BlockSerialNo"]][$row["ServiceName"]] = $row["TotalCnt"];
}

$SQL = $SQL_QUERY["BlockLogErrorsBySrvcStat"];
$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-48,SYSDATETIME()) AND DATEADD(hour,-24,SYSDATETIME())", $SQL);

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
$data1 = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data1[$row["BlockSerialNo"]][$row["ServiceName"]] = $row["TotalCnt"];
}

$SQL = $SQL_QUERY["BlockLogErrorsBySrvcStat"];
$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-72,SYSDATETIME()) AND DATEADD(hour,-48,SYSDATETIME())", $SQL);

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
$data2 = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data2[$row["BlockSerialNo"]][$row["ServiceName"]] = $row["TotalCnt"];
}
//echo "<pre>";
//var_dump($data0);
//echo "</pre>";


$SQL = $SQL_QUERY["BlockLogErrorsBySrvcStat"];
//$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-24,SYSDATETIME()) AND DATEADD(hour,-0,SYSDATETIME())", $SQL);
$SQL = str_replace("%%1%%", "AND [ble].[DateTime] > DATEADD(hour,-6,SYSDATETIME())", $SQL);
//$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-72,SYSDATETIME()) AND DATEADD(hour,-48,SYSDATETIME())", $SQL);

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
$data0_1 = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data0_1[$row["BlockSerialNo"]][$row["ServiceName"]] = $row["TotalCnt"];
}

$SQL = $SQL_QUERY["BlockLogErrorsBySrvcStat"];
//$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-24,SYSDATETIME()) AND DATEADD(hour,-0,SYSDATETIME())", $SQL);
//$SQL = str_replace("%%1%%", "AND [ble].[DateTime] > DATEADD(hour,-12,SYSDATETIME())", $SQL);
$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-12,SYSDATETIME()) AND DATEADD(hour,-6,SYSDATETIME())", $SQL);

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
$data0_2 = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data0_2[$row["BlockSerialNo"]][$row["ServiceName"]] = $row["TotalCnt"];
}

$SQL = $SQL_QUERY["BlockLogErrorsBySrvcStat"];
//$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-24,SYSDATETIME()) AND DATEADD(hour,-0,SYSDATETIME())", $SQL);
//$SQL = str_replace("%%1%%", "AND [ble].[DateTime] > DATEADD(hour,-18,SYSDATETIME())", $SQL);
$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-18,SYSDATETIME()) AND DATEADD(hour,-12,SYSDATETIME())", $SQL);

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
$data0_3 = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data0_3[$row["BlockSerialNo"]][$row["ServiceName"]] = $row["TotalCnt"];
}
$SQL = $SQL_QUERY["BlockLogErrorsBySrvcStat"];
//$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-24,SYSDATETIME()) AND DATEADD(hour,-0,SYSDATETIME())", $SQL);
//$SQL = str_replace("%%1%%", "AND [ble].[DateTime] > DATEADD(hour,-18,SYSDATETIME())", $SQL);
$SQL = str_replace("%%1%%", "AND [ble].[DateTime] BETWEEN DATEADD(hour,-24,SYSDATETIME()) AND DATEADD(hour,-18,SYSDATETIME())", $SQL);

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
$data0_4 = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data0_4[$row["BlockSerialNo"]][$row["ServiceName"]] = $row["TotalCnt"];
}




echo "<table cellspacing='1px' cellpadding='0px' class='tbl' width='80%' border='0' style='background:#CCCCCC;'>";
echo "<tr><!--th>Service</th--><th>Block</th><th>Version</th><th>Count (72h)</th><th>0-6h</th><th>6-12h</th><th>12-18h</th><th>18-24h</th><th>0-24h</th><th>24-48h</th><th>48-72h</th><tr>";
//echo "<tr><th>Date</th><th>Type</th><th>Device</th><th>Code</th><th>Count</th><th>Message</th><tr>";


$ServiceShortNamePervious = "";

$i = 0;
while ( @$data[$i]["ServiceShortName"] != "" )
{

//ServiceShortName
//ServiceName
//BlockSerialNo
//TotalCnt

//	$ServiceShortName = ( $ServiceShortNamePervious == @$data[$i]["ServiceShortName"] ) ? "" : $data[$i]["ServiceShortName"];
	$ServiceShortName = ( @$data[$i-1]["ServiceShortName"] == @$data[$i]["ServiceShortName"] ) ? "" : $data[$i]["ServiceShortName"];
	$ServiceNameRAW = $data[$i]["ServiceName"];
	$ServiceName = ( @$data[$i-1]["ServiceName"] == $ServiceNameRAW ) ? "" : $ServiceNameRAW;
//	$ServiceName = $data[$i]["ServiceName"];
	$BlockSerialNo = $data[$i]["BlockSerialNo"];
	$BlockSerialNoText = str_replace("STB", "", str_replace("STB0", "", $BlockSerialNo)) ;
	$BlockSerialNoText = "<a href='/dynamic/proxy.php?rnd=$rnd&page=spbMetroBlockErrorsDetails&params=$BlockSerialNo;$ServiceNameRAW' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax', contentId: 'highslide-html-8' } )\">$BlockSerialNoText</a>";

	$TotalCnt = $data[$i]["TotalCnt"];

	$ServiceNameText = ( $ServiceShortName or $ServiceName ) ? "<b>$ServiceShortName ($ServiceName)</b>" : "--//--";

	if ( $ServiceShortNamePervious <> $data[$i]["ServiceShortName"] )
	{
		echo "<tr><th colspan='7' style='background-color:#cccccc;' align='left'>$ServiceNameText</th></tr>";
	}
	$ServiceShortNamePervious = $data[$i]["ServiceShortName"];


	$UpdVersion = ""; // get max services version from services attributes (properties)
	$snl=0;
	while ( @$ServicesNamesList[$snl] != "" ) {
		$x = 0;
		while ( @$ServicesPropsList[$x] != "" )
		{
			if ( $ServicesPropsList[$x] == "Version" and @$ServicesProperties[$BlockSerialNo][$ServicesNamesList[$snl]][$ServicesPropsList[$x]]["Value"] > $UpdVersion )
			{
				$UpdVersion = @$ServicesProperties[$BlockSerialNo][$ServicesNamesList[$snl]][$ServicesPropsList[$x]]["Value"];
			}
			$val = @$ServicesProperties[$BlockSerialNo][$ServicesNamesList[$snl]][$ServicesPropsList[$x]]["Value"];
			$val = iconv("Windows-1251", "UTF-8", $val);
			$x++;
		}
		$snl++;

	}

	$day0 = @$data0[$BlockSerialNo][$ServiceNameRAW];
	$day0_1 = @$data0_1[$BlockSerialNo][$ServiceNameRAW];
	$day0_2 = @$data0_2[$BlockSerialNo][$ServiceNameRAW];
	$day0_3 = @$data0_3[$BlockSerialNo][$ServiceNameRAW];
	$day0_4 = @$data0_4[$BlockSerialNo][$ServiceNameRAW];
/*
	$day0_1 = ( $day0 > 0 ) ? $day0_2x - $day0_1x : $day0_2x;
	$day0_1 = ( $day0_2 == 0 ) ? "" : $day0_2;

	$day0_2 = ( $day0_1x > 0 ) ? $day0_2x - $day0_1x : $day0_2x;
	$day0_2 = ( $day0_2 == 0 ) ? "" : $day0_2;

	$day0_3 = ( $day0_2x > 0 ) ? $day0_3x - $day0_2x : $day0_3x;
	$day0_3 = ( $day0_3 == 0 ) ? "" : $day0_3;
*/
	$day1 = @$data1[$BlockSerialNo][$ServiceNameRAW];
	$day2 = @$data2[$BlockSerialNo][$ServiceNameRAW];

	echo "<tr align='center'>
		<!--td>$ServiceNameText</td-->
		<td align='left'>$BlockSerialNoText</td>
		<td>$UpdVersion</td>
		<td>$TotalCnt</td>
		<td style='background-color:#EDEDED;'>$day0_1</td>
		<td style='background-color:#EDEDED;'>$day0_2</td>
		<td style='background-color:#EDEDED;'>$day0_3</td>
		<td style='background-color:#EDEDED;'>$day0_4</td>
		<td>$day0</td>
		<td>$day1</td>
		<td>$day2</td>
	</tr>";


	$i++;
}

echo "</table>";

// Block Log Errors Stat



$SQL = "
/****** Block Log Errors Stat  ******/
SELECT 
	[Date],
	FORMAT(DATEADD(day, -1, [Date]), 'dd.MM.yyy') AS [DateDay],
      [ShortName]
      ,[Name]
      ,[BlocksCount]
      ,[TotalCount]
FROM [Shturman3Diag].[dbo].[BlockLogErrorsStat]
WHERE 
	[Date] > DATEADD(day,-50,SYSDATETIME())
ORDER BY 
	[Date] DESC,
	[ShortName] ASC,
	[Name] ASC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
//var_dump($stmt);

$data = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$dataStat[] = $row;
}


sqlsrv_close($conn) ;


echo "
<p>Crash Stat by services per day.<br>
<!--[Block Cnt] / [Total Errors]-->
[Всего произошло ошибок] / [На блоках (шт)]
</p>
<table cellspacing='1px' cellpadding='0px' class='tbl' width='100%' border='0' style='background:#CCCCCC;'>
	<tr>
		<th>Date</th>
		<!--th>PwrMon</th-->
		<th>Trac</th>
		<th>BG</th>
		<th>Cron</th>
		<th>Dios</th>
		<th>Hub</th>
		<th>Logic</th>
		<th>Math</th>
		<th>Modem</th>
		<th>Net</th>
		<th>PWR</th>
		<th>Udev</th>
		<th>??</th>
		<th>Total</th>
	<tr>";


$newDate = false;
$olddate = "";
$i = 0;
while ( @$dataStat[$i]["DateDay"] )
{
	$day = $dataStat[$i]["DateDay"];
	$ShortName = $dataStat[$i]["ShortName"];
	$sName = $dataStat[$i]["Name"];
	$BlockCnt = $dataStat[$i]["BlocksCount"];
	$TotalCnt = $dataStat[$i]["TotalCount"];

	$newDate = ( $day != $olddate and $olddate != "") ? true : false;
	$olddate = $day;

	if ( $newDate )
	{
		$rDate = $aStat["Date"];
/*
//		$PowerMon = ( @$aStat["PowerMon"]["TotalCnt"] > 0 ) ? $aStat["PowerMon"]["Block"] ." / ".  $aStat["PowerMon"]["TotalCnt"] : ""; 
		$Tracking = ( @$aStat["Tracking"]["TotalCnt"] > 0 ) ? $aStat["Tracking"]["Block"] ." / ".  $aStat["Tracking"]["TotalCnt"] : ""; 
		$Bluegiga = ( @$aStat["Bluegiga"]["TotalCnt"] > 0 ) ? $aStat["Bluegiga"]["Block"] ." / ".  $aStat["Bluegiga"]["TotalCnt"] : ""; 
		$Cron = ( @$aStat["Cron"]["TotalCnt"] ) ? $aStat["Cron"]["Block"] ." / ".  $aStat["Cron"]["TotalCnt"] : ""; 
		$Dios = ( @$aStat["Dios"]["TotalCnt"] ) ? $aStat["Dios"]["Block"] ." / ".  $aStat["Dios"]["TotalCnt"] : ""; 
		$Hub = ( @$aStat["Hub"]["TotalCnt"] ) ? $aStat["Hub"]["Block"] ." / ".  $aStat["Hub"]["TotalCnt"] : ""; 
		$Logic = ( @$aStat["Logic"]["TotalCnt"] ) ? $aStat["Logic"]["Block"] ." / ".  $aStat["Logic"]["TotalCnt"] : ""; 
		$Math = ( @$aStat["Math"]["TotalCnt"] ) ? $aStat["Math"]["Block"] ." / ".  $aStat["Math"]["TotalCnt"] : ""; 
		$Modems = ( @$aStat["Modems"]["TotalCnt"] ) ? $aStat["Modems"]["Block"] ." / ".  $aStat["Modems"]["TotalCnt"] : ""; 
		$Netmon = ( @$aStat["Netmon"]["TotalCnt"] ) ? $aStat["Netmon"]["Block"] ." / ".  $aStat["Netmon"]["TotalCnt"] : ""; 
		$Power = ( @$aStat["Power"]["TotalCnt"] ) ? $aStat["Power"]["Block"] ." / ".  $aStat["Power"]["TotalCnt"] : ""; 
		$Udev = ( @$aStat["Udev"]["TotalCnt"] ) ? $aStat["Udev"]["Block"] ." / ".  $aStat["Udev"]["TotalCnt"] : ""; 
		$Unckn = ( @$aStat["Unckn"]["TotalCnt"] ) ? $aStat["Unckn"]["Block"] ." / ".  $aStat["Unckn"]["TotalCnt"] : ""; 
*/
//		$PowerMon = ( @$aStat["PowerMon"]["TotalCnt"] > 0 ) ? $aStat["PowerMon"]["TotalCnt"] ." / ".  $aStat["PowerMon"]["Block"] : ""; 
		$Tracking = ( @$aStat["Tracking"]["TotalCnt"] > 0 ) ? $aStat["Tracking"]["TotalCnt"] ." / ".  $aStat["Tracking"]["Block"] : ""; 
		$Bluegiga = ( @$aStat["Bluegiga"]["TotalCnt"] > 0 ) ? $aStat["Bluegiga"]["TotalCnt"] ." / ".  $aStat["Bluegiga"]["Block"] : ""; 
		$Cron = ( @$aStat["Cron"]["TotalCnt"] ) ? $aStat["Cron"]["TotalCnt"] ." / ".  $aStat["Cron"]["Block"] : ""; 
		$Dios = ( @$aStat["Dios"]["TotalCnt"] ) ? $aStat["Dios"]["TotalCnt"] ." / ".  $aStat["Dios"]["Block"] : ""; 
		$Hub = ( @$aStat["Hub"]["TotalCnt"] ) ? $aStat["Hub"]["TotalCnt"] ." / ".  $aStat["Hub"]["Block"] : ""; 
		$Logic = ( @$aStat["Logic"]["TotalCnt"] ) ? $aStat["Logic"]["TotalCnt"] ." / ".  $aStat["Logic"]["Block"] : ""; 
		$Math = ( @$aStat["Math"]["TotalCnt"] ) ? $aStat["Math"]["TotalCnt"] ." / ".  $aStat["Math"]["Block"] : ""; 
		$Modems = ( @$aStat["Modems"]["TotalCnt"] ) ? $aStat["Modems"]["TotalCnt"] ." / ".  $aStat["Modems"]["Block"] : ""; 
		$Netmon = ( @$aStat["Netmon"]["TotalCnt"] ) ? $aStat["Netmon"]["TotalCnt"] ." / ".  $aStat["Netmon"]["Block"] : ""; 
		$Power = ( @$aStat["Power"]["TotalCnt"] ) ? $aStat["Power"]["TotalCnt"] ." / ".  $aStat["Power"]["Block"] : ""; 
		$Udev = ( @$aStat["Udev"]["TotalCnt"] ) ? $aStat["Udev"]["TotalCnt"] ." / ".  $aStat["Udev"]["Block"] : ""; 
		$Unckn = ( @$aStat["Unckn"]["TotalCnt"] ) ? $aStat["Unckn"]["TotalCnt"] ." / ".  $aStat["Unckn"]["Block"] : ""; 

		$Total = @$aStat["Tracking"]["TotalCnt"] +
			@$aStat["Bluegiga"]["TotalCnt"] +
			@$aStat["Cron"]["TotalCnt"] +
			@$aStat["Dios"]["TotalCnt"] +
			@$aStat["Hub"]["TotalCnt"] +
			@$aStat["Logic"]["TotalCnt"] +
			@$aStat["Math"]["TotalCnt"] +
			@$aStat["Modems"]["TotalCnt"] +
			@$aStat["Netmon"]["TotalCnt"] +
			@$aStat["Power"]["TotalCnt"] +
			@$aStat["Udev"]["TotalCnt"] +
			@$aStat["Unckn"]["TotalCnt"];
	
		echo "
			<tr align='center'>
				<td>$rDate</td>
				<td>$Tracking</td>
				<td>$Bluegiga</td>
				<td>$Cron</td>
				<td>$Dios</td>
				<td>$Hub</td>
				<td>$Logic</td>
				<td>$Math</td>
				<td>$Modems</td>
				<td>$Netmon</td>
				<td>$Power</td>
				<td>$Udev</td>
				<td>$Unckn</td>
				<td>$Total</td>
			</tr>
			";
		$newDate = false;
		$aStat = array();
	}
	$aStat["Date"] = $day;
	$aStat[$ShortName]["Block"] = $BlockCnt;
	$aStat[$ShortName]["TotalCnt"] = $TotalCnt;
//	var_dump($aStat);
	$i++;
	
}
echo "</table>";
echo "<p>Generated at: " . date("d.m.Y H:i:s") . "</p>";

echo "<p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p>";
echo "<p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p>";

?>
