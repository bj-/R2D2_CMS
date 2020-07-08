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



$iList = array();
$i = 0;

foreach ( explode("\n", $iData) as $line )
{
	if ( substr($line,0,5) != "-----" and $line != "" )
	{

		//        $sResult = "$WagonName!;!$FileDate!;!$count!;!$type!;!$code!;!$device!;!$text"
//		echo $line . "<br>";
		$arrLine = explode("!;!", $line);
//		echo "arrLine[0]: [".$arrLine["0"]."]\n";
		$p0 = str_remove_sql_char(substr($arrLine["0"], 0, 36));
//		echo "p0: [$p0]\n";
//		echo "p0: [$p0]\n";
		$p0 = ( @$ServersAll[$p0] ) ? $ServersAll[$p0] : "00000000-0000-0000-0000-000000000000";
//		echo "p0: [$p0]\n";

		$p1 = str_remove_sql_char(substr($arrLine["1"], 0, 10));

		$p2 = str_remove_sql_char(substr($arrLine["2"], 0, 10));
		$p3 = str_remove_sql_char(substr($arrLine["3"], 0, 10));
		$p4 = str_remove_sql_char(substr($arrLine["4"], 0, 10));
		$p5 = str_remove_sql_char(substr($arrLine["5"], 0, 50));
		$p6 = str_remove_sql_char(substr($arrLine["6"], 0, 200));
		$p6 = str_remove_sql_char(substr(hex2Bin($arrLine["6"]), 0, 2000));
		//$p6 = "Вова, тут на меня ругаются.";

		$iList[] = "'".$p0."','".$p1."','".$p2."','".$p3."','".$p4."','".$p5."','".$p6."'";

		if ( $i == 900 )
		{
			// separate INSERT query by 900 rows (MSSQL can not insert more than  1000 rows)
			$SQL = $SQL_QUERY["InsertBlocksLogData"] ."\n (".implode("),\n(", $iList) . ")";
			$SQL = iconv("UTF-8", "Windows-1251", $SQL);
			MSSQLsiletnQuery($SQL);

//			echo "900 rows";
			$iList = array();
//			echo count($iList);
			$i = 0;
		}
		$i++;

		//$x = "'".$p0."','".$p1."','".$p2."','".$p3."','".$p4."','".$p5."','".$p6."'";
		//$x = "'"."','"."','".$p3."','".$p4."','".$p5."','".$p6."'";
		//echo iconv("UTF-8", "Windows-1251", $x);
	//	$SQL_Q = "INSERT INTO "
		
	}

}

//echo "================SQL_QUERY================ \n";


//
//   СКРИПТ НЕ ДОЛЖЕН НИСЕГО ПИСАТЬ В ОТВЕТ
//   Если хоть что-тобудет в теле страницы - повершеловский скрипт считает что импорт в БД обломился и не удаляет исходные файлы
//

$SQL = $SQL_QUERY["InsertBlocksLogData"] ."\n (".implode("),\n(", $iList) . ")";

//echo "Compiled SQL\n";
//echo count($iList) . "\n";


//echo $SQL . "ddd"; // $SQL;


$SQL = iconv("UTF-8", "Windows-1251", $SQL);
//echo "Converted to Win1251\n";



//MSSQLsiletnQuery("truncate table [dbo].[BlocksLog]");
MSSQLsiletnQuery($SQL);
//echo "SQL Executed\n";

$iData = "";

sqlsrv_close($conn) ;
//echo "Script Ended. SQL connection closed";


?>