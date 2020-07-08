<?php
/***************************************************************************
 *                                block_diag_stat_calc.php
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

// Set Execution Time Limit
echo "Set set_time_limit(60) Sec<br />";
set_time_limit ( 60 );

break;

exit;

// Get params from url
//$ShowAlert = substr(@$_GET["ShowAlert"], 0, 20);
//var_dump(@$_GET);
//$ViewType = ( strtoupper(@$_GET["type"]) == "ERRORS" ) ? "ERRORS" : "ALL";  // Show only errors
//$ViewType = ( $ViewType ) ? $ViewType : "ALL";  // Show only errors
//$Srv = @$_GET["srv"];

// TODO костыль для метро. прдумать общие правила (конфиг вероятно)
//$Srv_FrendlyNames = array("SRV-SHTURMAN" => "Root4/Root4H4","SRV-SHTURMAN-C" => "Anal","SRV-SHTURMAN-G1" => "sRoot","SRV-SHTURMAN-G3" => "Root3"); 

//print_r($_GET);

$rnd = rand ( 0 , 1000000000 );

$sql_server = "Diag";
$db = "Diag";
$conn = MSSQLconnect( $sql_server, $db );

//var_dump($conn);

function Read_Blocks_List()
{
	global $conn, $CONFIG_SHTURMAN;
	// Limits
	$SQL = "
SELECT 
	[BlockSerialNo]
FROM [Servers] AS [s]
INNER JOIN [Server_Types] AS [st] ON [st].[Guid] = [s].[ServerType_Guid]
WHERE [st].[stShortName] = 'olimex'
	" ;

	//$SQL = str_replace("%%SQL_LinkedName_Root%%", "[".$CONFIG_SHTURMAN["SQL_LinkedName_Root"]."].", $SQL);
	//echo "<pre>$SQL</pre>";

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$Blocks[$row["BlockSerialNo"]] = array( "BlockSerialNo" => $row["BlockSerialNo"]);
	}
	echo "<pre>aa"; var_dump($Blocks); echo "</pre>";

	//MSSQLsiletnQuery($SQL);
//	return @$Limit;
}
/*
function Coupling_History_Delete()
{
	global $conn;
	// Limits
	$SQL = "
USE [Shturman3Diag]

IF OBJECT_ID('tempdb.dbo.#diag_Couplings_History', 'U') IS NOT NULL
	DROP TABLE #diag_Couplings_History; 
	" ;

	MSSQLsiletnQuery($SQL);
//	return @$Limit;
}
*/

echo "Зачитываем список блоков(вагонов)<br />";
Read_Blocks_List();
//echo "- Заливаю: ModemAndBG<br />";
//Block_DiagStat_ModemAndBG(100);
//echo "- Заливаю: By Block Reports<br />";
//Block_DiagStat_ByReports(100);



// Сбор статы со Штурман базы
sqlsrv_close($conn) ;



/*
$SQL = "SELECT top 2 * FROM #diag_Couplings_History" ;

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$ServersList = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$Measure_Definition[] = $row;
}
//$ServersCount = count($Measure_Definition);
echo "<pre>"; var_dump($Measure_Definition); echo "</pre>";
*/


//exit;


//$currentdate = date("Y-m-d", time());
$currentdate = date("d.m.Y", time());
$currenttime = date("H:i:s", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'request/block_diag_stat_calc.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTTIME' => $currenttime,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'COLUMN_COUNT' => @$ColumnCount,
	'CLIENT' => iconv("Windows-1251", "UTF-8", $CONFIG_SHTURMAN["Client_Name"]),
	//'CLIENT_SRV' => $Srv,

	));

/*
foreach ( $data AS $Srv )
{

	$template->assign_block_vars('server', array(
		'SRV_GUID' => $Srv_Guid,
		'COMPUTER_NAME' => $Srv_ComputerName,
		//'SERVER_NAME' => $Srv_ComputerFrendlyName,
		'NAME' => $Srv_ComputerFrendlyName,
		'IP' => $Srv_IpAddress,
		'IP_METRO' => $Srv_Ip_Metro,
		'IP_USELESS' => $Srv_Ip_Useless,
		'CREATED' => $Srv_Created,
		'MEM_TOTAL' => $Srv_MemoryTotalBytesStr,
		'MEM_FREE' => $Srv_MemoryAvailBytesStr,
		'MEM_USED' => $Srv_MemoryUsedStr,
		'MEM_UPDATED' => $Srv_MemoryReceived,
		'MEM_UPDATED_TAGO' => $Srv_MemoryReceived_tAgo,
		
		'S_MEM_FREE' => $Style_Srv_MemoryAvailBytes,
	));
	

}
*/


$template->assign_block_vars('legend', array(
	));


//Coupling_History_Delete();

//sqlsrv_close($conn) ;


$template->pparse('body');
?>