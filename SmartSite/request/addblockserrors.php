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
//$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );
$conn = MSSQLconnect( "SpbMetro-Anal", "Diag" );


$iData = @$_POST["data"];

if ( $iData =="" ) exit;

// ServersAll
$ServersAll = array();
$SQL = $SQL_QUERY["ServersAll"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersAll[$row["BlockSerialNo"]] = $row["Guid"];
}
//var_dump($ServersAll);

$ServicesAll = array();
$SQL = $SQL_QUERY["ServicesAll"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServicesAll[$row["ServiceName"]] = $row["Guid"];
}
//var_dump($ServicesAll);



$row = explode(";", $iData);

$p0 = str_remove_sql_char(substr($row["0"], 0, 36));
$p0 = ( @$ServersAll[$p0] ) ? $ServersAll[$p0] : "00000000-0000-0000-0000-000000000000";

$p1 = str_remove_sql_char(substr($row["1"], 0, 36));
$p1 = ( $ServicesAll[$p1] ) ? $ServicesAll[$p1] : "00000000-0000-0000-0000-000000000000";

$p2 = str_remove_sql_char(substr($row["2"], 0, 30));

$p3 = str_remove_sql_char(substr(hex2Bin($row["3"]), 0, 4000));

$p4 = str_remove_sql_char(substr($row["4"], 0, 2000));
$p4 = str_replace(":", ";", $p4);
$p4 = ( strlen($p4) == 0 ) ? "NULL" : "'".$p4."'";

$p5 = str_remove_sql_char(substr($row["5"], 0, 255));
$p5 = ( strlen($p5) == 0 ) ? "NULL" : "'".$p5."'";


$SQL = "INSERT INTO [dbo].[BlockLogErrors] 
([Server_Guid],[Service_Guid],[DateTime],[ErrorText],[LogPartAllFiles],[LogPartFile]) 
VALUES ('".$p0."','".$p1."','".$p2."','".$p3."',".$p4.",".$p5.")";


if (0)
{
	echo "\n";
	echo "ServerName (\$row[0]): [".$row[0]."]\n";
	echo "ServerGuid (\$p0): [$p0]\n";
	echo "ServiceName (\$row[1]): [".$row[1]."]\n";
	echo "ServiceGuid (\$p2): [$p1]\n";
	echo "DateTime (\$p2): [$p2]\n";
	echo "ErrorText (\$p3): [".substr($p3,0,100)."...]\n";
	echo "AllFiles (\$p4): [$p4]\n";
	echo "File (\$p5): [$p5]\n";
	//echo "\$SQL: [$SQL]\n";
}



//echo "================SQL_QUERY================ \n";


//echo $SQL;


$SQL = iconv("UTF-8", "Windows-1251", $SQL);
//echo "Converted to Win1251\n";
//$SQL = str_replace("", "", $SQL);

//echo $SQL;
//var_dump( $SQL);


MSSQLsiletnQuery($SQL);

//echo "aaaa";

//$iData = "";

sqlsrv_close($conn) ;
//echo "Script Ended. SQL connection closed";


?>