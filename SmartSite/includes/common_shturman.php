<?php

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}


include($DRoot . '/includes/functions_shturman.php');
include($DRoot . '/includes/functions_mssql.php');
include($DRoot . '/includes/sqlquery_shturman.php');

$SrvName = $CONFIG_SHTURMAN["Server_Name"];


$conn = MSSQLconnect( "Config", "Config" );

$SQL = "SELECT * FROM Config";
//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$CONFIG_SHTURMAN[$row["Name"]] = $row["Value"];
}
$ServerAlias = @explode(";", $CONFIG_SHTURMAN["SQL_Alias_Server"] );
foreach( $ServerAlias as $item ) 
{
	$sa = explode("=", $item);
	$CONFIG_SHTURMAN["SQL_Alias_Server_Name"][$sa[0]] = $sa[1];
}

//echo "<pre>"; var_dump($CONFIG_SHTURMAN); echo "</pre>";
sqlsrv_close($conn) ;


?>