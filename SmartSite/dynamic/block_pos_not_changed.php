<?php
/***************************************************************************
 *                                block_pos_not_changed.php
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

$SQL = "
-- ****** Статистика по замершему местоположению у головы двигающегося состава  ******
SELECT TOP 200
--	[ID],
	[ServerName] AS [BlockSerialNo],
--	[Connected],
	FORMAT([Connected], 'dd.MM.yyy HH:mm') AS [ConnectedF],
	[PositionName],
	--[LastChanged],
	FORMAT([LastChanged], 'dd.MM.yyy HH:mm') AS [LastChangedF],
	[TimeAgo],
	[TimeAgoFormated],
--	[Written],
--	[Created],
	[Second_ServerName] AS [Second_BlockSerialNo],
--	[Second_Connected],
	FORMAT([Second_Connected], 'dd.MM.yyy HH:mm') AS [Second_ConnectedF],
	[Second_PositionName],
--	[Second_LastChanged],
	FORMAT([Second_LastChanged], 'dd.MM.yyy HH:mm') AS [Second_LastChangedF],
	[Second_TimeAgo],
	[Second_TimeAgoFormated]
FROM [BlockPositionChangeHistory]
WHERE 1=1
	AND [ServerName] = '%%1%%'
ORDER BY [LastChanged] DESC
";
/*	
$SQL = 
"
-- ****** Статистика по замершему местоположению у головы двигающегося состава  ******
SELECT TOP 200
	--[ID],
	[ServerName] AS [BlockSerialNo],
	[PositionName],
	--[LastChanged],
	FORMAT([LastChanged], 'dd.MM.yyy HH:mm') AS [LastChangedF],
	[TimeAgo],
	[TimeAgoFormated]
	--[Written],
	--[Created]
FROM [BlockPositionChangeHistory]
WHERE 1=1
	AND [ServerName] = '%%1%%'
ORDER BY [LastChanged] DESC
";
*/

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
	'body' => 'block_pos_not_changed.tpl')
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
	$ConnectedF = $data[$i]["ConnectedF"];
	$PositionName = iconv("Windows-1251", "UTF-8", $data[$i]["PositionName"]);
	$LastChangedF = $data[$i]["LastChangedF"];
	$TimeAgo = $data[$i]["TimeAgo"];
	$TimeAgoFormated = $data[$i]["TimeAgoFormated"];

	$Second_BlockSerialNo = $data[$i]["Second_BlockSerialNo"];
	$Second_ConnectedF = $data[$i]["Second_ConnectedF"];
	$Second_PositionName = iconv("Windows-1251", "UTF-8", $data[$i]["Second_PositionName"]);
	$Second_LastChangedF = $data[$i]["Second_LastChangedF"];
	$Second_TimeAgo = $data[$i]["Second_TimeAgo"];
	$Second_TimeAgoFormated = $data[$i]["Second_TimeAgoFormated"];

	$Coupling = ($Second_BlockSerialNo) ? str_replace("STB", "", (str_replace("STB0", "", $BlockSerialNo))) . "-" . str_replace("STB", "", (str_replace("STB0", "", $Second_BlockSerialNo))) : "";

	$template->assign_block_vars('row', array(
		'COUPLING' => $Coupling,

		'BLOCKSERNO' => $BlockSerialNo,
		'CONNECTED' => $ConnectedF,
		'POSITION' => $PositionName,
		'DATE_TIME' => $LastChangedF,
		'TIME_AGO' => $TimeAgo,
		'TIME_AGO_STR' => ($TimeAgo) ? "(~$TimeAgoFormated / $TimeAgo min)" : "",
		'TIME_AGO_F' => $TimeAgoFormated,

		'BLOCKSERNO_SECOND' => $Second_BlockSerialNo,
		'CONNECTED_SECOND' => $Second_ConnectedF,
		'POSITION_SECOND' => $Second_PositionName,
		'DATE_TIME_SECOND' => $Second_LastChangedF,
		'TIME_AGO_SECOND' => $Second_TimeAgo,
		'TIME_AGO_STR_SECOND' => ($Second_TimeAgo) ? "(~$Second_TimeAgoFormated / $Second_TimeAgo min)" : "",
		'TIME_AGO_F_SECOND' => $Second_TimeAgoFormated,
		));

	$i++;
}

//echo "</table>";


//echo "<p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p>";
//echo "<p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p>";

$template->pparse('body');


?>
