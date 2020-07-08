<?php
/***************************************************************************
 *                                serversstate3_hist_errors.php
 *                            -------------------
 *   begin                : Jun 13, 2018
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: blockdetails.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
 *
 *
 ***************************************************************************/
$ver["serversstate3_hist_errors"] = "1.0.1"; // Version of script
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

// Get params from url
//$ShowAlert = substr(@$_GET["ShowAlert"], 0, 20);
//var_dump(@$_GET);
//$ViewType = ( strtoupper(@$_GET["type"]) == "ERRORS" ) ? "ERRORS" : "ALL";  // Show only errors
//$ViewType = ( $ViewType ) ? $ViewType : "ALL";  // Show only errors
//echo "<pre>"; var_dump($_GET); echo "</pre>";

$params = (@$_POST) ? $_POST : $_GET;
$Srv = @$params["srv"];
$cSrvID = @$params["srv"];

$InstanceGuid = ( strlen(@$params["Instance"]) == 36 ) ? str_remove_sql_char(substr(@$params["Instance"],0,36)) : "";
$ServiceName = str_remove_sql_char(substr(@$params["Service"],0,30));

// TODO костыль для метро. прдумать общие правила (конфиг вероятно)
//$Srv_FrendlyNames = array("SRV-SHTURMAN" => "Root4/Root4H4","SRV-SHTURMAN-C" => "Anal","SRV-SHTURMAN-G1" => "sRoot","SRV-SHTURMAN-G3" => "Root3"); 

//print_r($_GET);

$rnd = rand ( 0 , 1000000000 );


$sql_server = "Diag";
$db = "DiagSrv";
$conn = MSSQLconnect( $sql_server, $db );

$SQL = "SELECT
    [Guid],
    [ComputerName],
    [IpAddress],
    --[Created],
	FORMAT(DATEADD(HOUR, +3, [Created]), 'dd.MM.yyy HH:mm:ss') AS [Created],
    [MemoryTotalBytes],
    [MemoryAvailBytes],
    --[MemoryReceived],
	FORMAT(DATEADD(HOUR, +3, [MemoryReceived]), 'dd.MM.yyy HH:mm:ss') AS [MemoryReceived],
	DATEDIFF(second, [MemoryReceived], SYSUTCDATETIME()) AS [MemoryReceived_sAgo]
FROM [diagnostics].[Shturman_Instance] 
ORDER BY [ComputerName] ASC" ;

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$ServersList = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersList[$row["Guid"]] = $row;
}
//echo $InstanceGuid;
//echo "<pre>"; var_dump($ServersList); echo "</pre>";
//echo "<pre>$SQL</pre>";

$SQL = "
/****** Service's Errors  ******/
SELECT TOP 100 
	--[id],
	--[Instance_Guid],
	--[Service_Name],
	--[Written],
	--FORMAT(DATEADD(HOUR, +3, [Written]), 'dd.MM.yyy HH:mm:ss') AS [Written],
	--[ErrorDateTime],
	FORMAT(DATEADD(HOUR, +3, [ErrorDateTime]), 'dd.MM.yyy HH:mm:ss') AS [ErrorDateTime],
	[ErrorText],
	[ErrorType],
	[ErrorStack]
FROM [diagnostics].[Service_Error] AS [e]
WHERE 
	[Instance_Guid] = '%%GUID%%'
	AND [Service_Name] = '%%SERVICE%%'
ORDER BY
	[e].[ErrorDateTime] DESC
	" ;

$SQL = ($InstanceGuid) ? str_replace('%%GUID%%', $InstanceGuid, $SQL) : $SQL ;
$SQL = ($ServiceName) ? str_replace('%%SERVICE%%', $ServiceName, $SQL) : $SQL;

//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$ServersList = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}

//echo "<pre>"; var_dump($data); echo "</pre>";


sqlsrv_close($conn) ;



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
	'body' => 'serversstate3_hist_errors.tpl')
);
//echo $ServersList[$InstanceGuid]["ComputerName"];

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTTIME' => $currenttime,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	//'COLUMN_COUNT' => @$ColumnCount,
	'SERVER' => @$ServersList[$InstanceGuid]["ComputerName"],
	'CLIENT' => iconv("Windows-1251", "UTF-8", $CONFIG_SHTURMAN["Client_Name"]),
	'CLIENT_SRV' => $Srv,
	'SERVICE' => $ServiceName,

	));

//foreach ( $ServersList AS $Srv )
foreach ( $data AS $row )
{
	//$Srv_Guid = $Srv["Guid"];
	//$Srv_IpAddress = substr($Srv_Ips, 0, strpos($Srv_Ips, "169.")-1);
	//$Srv_MemoryTotalBytesStr = format_bytes($Srv_MemoryTotalBytes);
	//$Srv_MemoryReceived_tAgo = sec2hours($Srv["MemoryReceived_sAgo"]);

	$ErrorDateTime = $row["ErrorDateTime"];
	$ErrorText = $row["ErrorText"];
	$ErrorType = $row["ErrorType"];
	$ErrorStack = iconv("Windows-1251", "UTF-8", $row["ErrorStack"]);
	$ErrorStack = nl2br($ErrorStack);
	
	$template->assign_block_vars('row', array(
		'DATE' => $ErrorDateTime,
		'TEXT' => $ErrorText,
		'TYPE' => $ErrorType,
		'STACK' => $ErrorStack,
	));

}

$template->assign_block_vars('legend', array());

$template->pparse('body');
?>