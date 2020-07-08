<?php
/***************************************************************************
 *                                block_usb_lost.php
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


$ServerID = str_remove_sql_char(substr(@$_GET["Server"], 0, 20));
//$ServiceName = str_remove_sql_char(substr(@$_GET["Service"], 0, 20));

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );


$SQL = 
"
/****** Изменение кол-ва USB устройств на блоке  ******/
SELECT TOP 500 
--	[ID],
	[BlockSerialNo],
--	[Date],
--	[Time],
--	[DateTime],
	FORMAT([DateTime], 'dd.MM.yyy HH:mm') AS [DateTimeF],
	[New],
	[Old]
FROM [Block_DeviceLostStat]
WHERE 1=1
	AND [BlockSerialNo] = '%%1%%'
ORDER BY [DateTime] DESC
";
//$SQL = str_replace("%%1%%" , "AND [ble].[DateTime] > DATEADD(hour,-72,SYSDATETIME()) AND [s].[BlockSerialNo] = '$ServerID'", $SQL);
$SQL = str_replace("%%1%%" , "$ServerID", $SQL);
//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
//var_dump($stmt);

$data = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}

sqlsrv_close($conn) ;


define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'block_usb_lost.tpl')
);

$template->assign_vars(array(
//	'TITLE' => $page_title,
//	'BLOCKSERNO' => $Server,
	'ARTICLE' => $page_text,
	));


$i = 0;
while ( @$data[$i] )
{
//	echo @$ServiceName;
	$BlockSerialNo = $data[$i]["BlockSerialNo"];
//	$PositionName = iconv("Windows-1251", "UTF-8", $data[$i]["PositionName"]);
	$DateTimeF = $data[$i]["DateTimeF"];
	$NewVal = $data[$i]["New"];
	$OldVal = $data[$i]["Old"];


	$template->assign_block_vars('row', array(
		'BLOCKSERNO' => $BlockSerialNo,
		'DATE_TIME' => $DateTimeF,
		'NEW_VAL' => $NewVal,
		'OLD_VAL' => $OldVal,
	));

	$i++;
}


$template->pparse('body');


?>
