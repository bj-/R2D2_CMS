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

$row = explode(";", $iData);

$Block = str_remove_sql_char(substr($row[0], 0, 36));
$Block = ( @$ServersAll[$Block] ) ? $ServersAll[$Block] : "00000000-0000-0000-0000-000000000000";

$Content = trim($row[1]);
$Content = hex2bin($Content);

//echo "-$Content-" ;

$i = 0;

foreach ( explode("\n", $Content) as $line )
{
	$line = trim($line);
	if ( $line != "" and strlen($line) <= 110)
	{
		$iArr = explode(";", $line);
		//echo "<pre>";var_dump($iArr); echo "<pre>";
		if ( @$iArr[5] != "" )
		{
			$DateF = substr($iArr[0], strpos($iArr[0], "=")+1 );

			$time = substr($iArr[5], strpos($iArr[5], "=")+1 );
			$time = ( $time == "N/A" or $time == "N/A}" or $time == "" ) ? 'NULL' : "$time";

			$DateUnix = substr($iArr[1], strpos($iArr[1], "=")+1 );
			$DateUnix = ( $DateUnix == "N/A" or $time == "NULL" ) ? 'NULL' : "'$DateUnix'";
			$addr = substr($iArr[2], strpos($iArr[2], "=")+1 );
			$icmp_seq = substr($iArr[3], strpos($iArr[3], "=")+1 );
			$icmp_seq = ( $icmp_seq == "N/A" or $time == "NULL" ) ? 'NULL' : "'$icmp_seq'";
			$ttl = substr($iArr[4], strpos($iArr[4], "=")+1 );
			$ttl = ( $ttl == "N/A" or $time == "NULL" ) ? 'NULL' : "'$ttl'";
			
			if ( strlen($DateF) == 19 )
			{
				$eArr[$i][] = "('$Block', '$DateF', $DateUnix, '$addr', $icmp_seq, $ttl, $time)";
			}
	}

		/*if ( count($eArr[$i]) >= 10 ) 
		{
			$i++;
		}*/
		$i = ( count($eArr[$i]) >= 900 ) ? $i+1 : $i;
		//echo "Line: $line + ('$Block', '$DateF', $DateUnix, '$addr', $icmp_seq, $ttl, $time) \n";
	}
	
}

//echo "<pre>"; var_dump($eArr); echo "</pre>";

//exit;
//echo "--" . @count($eArr[0]) . "--";

if ( @count($eArr[0]) )
{
	foreach ( $eArr as $xArr )
	{

		$InsertStr = implode (", \n", $xArr );

		$SQL = "INSERT INTO [dbo].[Block_Ping_Stat] 
([Server_Guid],[Date],[UnixDate],[Address],[icmp_seq],[ttl],[time]) 
VALUES $InsertStr";

		//echo "<pre>\n$SQL\n</pre>";

		if (0)
		{
			echo "\n";
			echo "ServerName (\$Block): [".$Block."]\n";
			echo "File (\$Content): [$Content]\n";
			//echo "\$SQL: [$SQL]\n";
		}

		//echo "================SQL_QUERY================ \n";
		//echo $SQL;
		$SQL = iconv("UTF-8", "Windows-1251", $SQL);
		//echo "Converted to Win1251\n $SQL";

		MSSQLsiletnQuery($SQL);
	}
}

sqlsrv_close($conn) ;
//echo "Script Ended. SQL connection closed";


?>