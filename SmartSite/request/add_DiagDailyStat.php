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

//exit;

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

$reportDate = trim($row[1]);
$reportDate = str_replace(".","-", $reportDate);
$reportDate = (strlen($reportDate) == 8 ) ? "20$reportDate" : $reportDate;

$Content = trim($row[2]);
$Content = hex2bin($Content);

//echo "\nBlock: [$Block]\nreportDate: [$reportDate]\nContent: [$Content]" ;

//exit;
function convertdate($data)
{
	//$ret = '';
	$fromEng = Array("-Jan-","-Feb-","-Mar-","-Apr-","-May-","-Jun-","-Jul-","-Aug-","-Sep-","-Oct-","-Nov-","-Dec-");
	$fromRus = Array("-янв-","-фев-","-мар-","-апр-","-май-","-июн-","-июл-","-авг-","-сен-","-окт-","-ноя-","-дек-");
	$to =      Array("-01-", "-02-", "-03-", "-04-", "-05-", "-06-", "-07-", "-08-", "-09-", "-10-", "-11-", "-12-");

	$data = iconv("UTF-8", "Windows-1251", $data);

	$data = str_replace($fromEng, $to, $data);
	$data = str_replace($fromRus, $to, $data);
	return $data;
}

$i = 0;
foreach ( explode("\n", $Content) as $line )
{
	$line = trim($line);
	//$line = convertdate($line);
	if ( $line != "")
	{
		$line = str_remove_sql_char($line);
		$Name = substr($line, 0, strpos($line, ";"));
		$Value = substr($line, (strpos($line, ";")+1) );
		
		if ( $Name != "" and $Value != "" )
		{
			$eArr[$i][] = "('$Block', '$reportDate', '$Name', '$Value')";
		}
	}
	$i = ( count($eArr[$i]) >= 900 ) ? $i+1 : $i;
}

//echo "<pre>"; var_dump($eArr); echo "</pre>";

//exit;
//echo "--" . @count($eArr[0]) . "--";

if ( @count($eArr[0]) )
{
	foreach ( $eArr as $xArr )
	{

		$InsertStr = implode (", \n", $xArr );

		$SQL = "INSERT INTO [dbo].[Block_DailyStat_RAW] 
([Server_Guid],[Date],[Name],[Value]) 
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