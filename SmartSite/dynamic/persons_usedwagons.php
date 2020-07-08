<?php
//echo getdate();

set_time_limit(120); // 120 second

$microTime=microtime(true); 
define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/common.php');
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');


// Get params from url

$params = $_GET["params"];
$params = explode(";", $params);

$DateFrom = $params[0];
$DateFromUse = ( $params[1] == "true" ) ? "checked" : "";
$DateFromReal = ( $params[1] == "true" ) ? $DateFrom : "[начала времен]";
$DateFromSQL = ( $params[1] == "true" ) ? $DateFrom : "1970-01-01";
$DateTo = $params[2];
$DateToUse = ( $params[3] == "true" ) ? "checked" : "";
$DateToReal = ( $params[3] == "true" ) ? $DateTo : "[конца времен]";
$DateToSQL = ( $params[3] == "true" ) ? $DateTo : "2030-01-01";
$UserGuid = $params[5];                     

$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

$SQL = "
/****** Проперти пользователя  ******/

SELECT 
	[p].[Last_Name] 
FROM [Users_Persons] AS [p]
INNER JOIN [Users] AS [u] on [u].[Users_Persons_Guid] = [p].[Guid]
WHERE [u].[Guid] = '$UserGuid';
";

/*
echo $SQL = "
SELECT Alias from servers
";
*/
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$pdata = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$pdata= $row;
}

$PersonLastName = $pdata["Last_Name"];


//exit;

$SQL = "
/****** Составы на которых появлялась фамилия машиниста на экране  ******/
declare
  @LastName nvarchar(50);

SELECT @LastName = [p].[Last_Name] FROM [Users_Persons] AS [p]
INNER JOIN [Users] AS [u] on [u].[Users_Persons_Guid] = [p].[Guid]
WHERE [u].[Guid] = '$UserGuid';

SELECT DISTINCT [s].[Alias]
FROM [Events_Onbtext] AS [onbt]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [onbt].[Server_Guid]
WHERE
--	[onbt].[Source] = 'Машинист'
	 [onbt].[Happend] BETWEEN '$DateFromSQL' AND '$DateToSQL'
	AND [onbt].[Text] LIKE  CONCAT(@LastName,'%');
";

$SQL = iconv("UTF-8", "Windows-1251", $SQL);


/*
echo $SQL = "
SELECT Alias from servers
";
*/
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$data = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//	echo $row["Alias"];
//	$data[]= $row["Alias"];
	$data[]= $row;
}


echo "
<h3>Нахождение на вагонах</h3>
<!--
From: <input type=\"checkbox\" id=\"UseDateFrom\" value=\"1\" $DateFromUse />
<input type=\"date\" id=\"DateFrom\" value=\"$DateFrom\" />; 
Up To: <input type=\"checkbox\" id=\"UseDateTo\" value=\"1\" $DateToUse />
<input type=\"date\" id=\"DateTo\" value=\"$DateTo\" />
<a style=\"cursor:pointer;\" OnClick=\"showGo();\">Refresh</a>
-->


* Нахождение на вагонах c $DateFromReal до $DateToReal<br />
** Вагоны: 
<!--<table width='100%'>
	<tr>
		<th></th>
	</tr>
-->
";

//var_dump($data);

$i = 0;
while ( @$data[$i] )
{
//	$LastName = iconv("Windows-1251", "UTF-8", $data[$i]["Last_Name"]);
	$wagon = $data[$i]["Alias"];
	$wagon = str_replace("STB0", "", $wagon);
	$wagon = str_replace("STB", "", $wagon);

	echo "$wagon, ";
	$i++;
}
echo "	
<!--/table-->
";

sqlsrv_close($conn) ;

?>