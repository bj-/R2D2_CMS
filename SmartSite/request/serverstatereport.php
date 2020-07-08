<?php

define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/common.php');
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');

$DocumentRoot = $_SERVER["DOCUMENT_ROOT"];

//include($DocumentRoot."/includes/config.php");
include($DocumentRoot."/includes/functions.php");

//$conn = MSSQLconnect( "SpbMetro4s-Root4", "Shturman" );
$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );


$iData = @$_POST["data"];

if ( $iData =="" ) exit;

// $MetricGroupsAll
$MetricGroupsAll = array();
$SQL = $SQL_QUERY["MetricGroupsAll"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$MetricGroupsAll[$row["Code"]] = $row["Guid"];
}
//var_dump($MetricGroupsAll);
// MetricAll
$MetricsAll = array();
$SQL = $SQL_QUERY["MetricsAll"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$MetricsAll[$row["GroupGuid"]][$row["Code"]] = $row["Guid"];
}
//var_dump($MetricsAll);
// ServersAll
$ServersAll = array();
$SQL = $SQL_QUERY["ServersAll"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersAll[$row["BlockSerialNo"]] = $row["Guid"];
}
//var_dump($ServersAll);

//var_dump($MetricsAll);

//$SQL_Q = "INSERT INTO [DiagData] ([Server_Guid],[MetricGroup_Guid],[Metric_Guid],[Value]) VALUES ('%%1%%','%%2%%','%%3%%','%%4%%')";

$iList = array();

foreach ( explode("\n", $iData) as $line )
{
	if ( substr($line,0,5) != "-----" and $line != "" )
	{
//		echo $line . "<br>";
		$arrLine = explode(";", $line);
//		echo "arrLine[0]: [".$arrLine["0"]."]\n";
		$p0 = str_remove_sql_char(substr($arrLine["0"], 0, 36));
//		echo "p0: [$p0]\n";
//		echo "p0: [$p0]\n";
		$p0 = ( @$ServersAll[$p0] ) ? $ServersAll[$p0] : "00000000-0000-0000-0000-000000000000";
//		echo "p0: [$p0]\n";

		$p1 = str_remove_sql_char(substr($arrLine["1"], 0, 36));
		$p1 = ( @$MetricGroupsAll[$p1] ) ? $MetricGroupsAll[$p1] : "00000000-0000-0000-0000-000000000000";

		$p2 = str_remove_sql_char(substr($arrLine["2"], 0, 36));
//		echo "$p1 - $p2 - @$MetricsAll[$p1][$p2]\n";
		$p2 = ( @$MetricsAll[$p1][$p2] ) ? $MetricsAll[$p1][$p2] : "00000000-0000-0000-0000-000000000000";

		$p3 = str_remove_sql_char(substr($arrLine["3"], 0, 999));

		$iList[] = "'".$p0."','".$p1."','".$p2."','".$p3."'";
	//	$SQL_Q = "INSERT INTO "
		
	}

}

echo "================SQL_QUERY================ \n";


$SQL = $SQL_QUERY["InsertDiagData"] ."\n (".implode("),\n(", $iList) . ")";

MSSQLsiletnQuery($SQL);

//echo $SQL;

sqlsrv_close($conn) ;


?>