<?php
/***************************************************************************
 *                                blockdetails.php
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
echo $ServiceName = str_remove_sql_char(substr(@$_GET["Service"], 0, 20));

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );


$SQL = $SQL_QUERY["BlockLogErrors"];
//$SQL = str_replace("%%1%%" , "AND [ble].[DateTime] > DATEADD(hour,-72,SYSDATETIME()) AND [s].[BlockSerialNo] = '$ServerID'", $SQL);
$SQL = str_replace("%%1%%" , "AND [ble].[DateTime] > DATEADD(hour,-288,SYSDATETIME()) AND [s].[BlockSerialNo] = '$ServerID'", $SQL);
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
	'body' => 'blockerrordetails.tpl')
);

$template->assign_vars(array(
//	'TITLE' => $page_title,
//	'BLOCKSERNO' => $Server,
	'ARTICLE' => $page_text,
	));


$i = 0;
while ( @$data[$i]["Date"] != "" )
{
//	echo @$ServiceName;

	if ( @$ServiceName == "" or @$ServiceName == $data[$i]["ServiceName"])
	{
//		$LogPartFile = ( $data[$i]["LogPartFile"] ) ? $data[$i]["LogPartFile"] : "--none--";
//		$LogPartFileAll = str_replace(";", "<br />", $data[$i]["LogPartAllFiles"]);

		$BlockSerNo = $data[$i]["BlockSerialNo"];
		$ServiceNameText = $data[$i]["ServiceName"];
		$DateTime = $data[$i]["Date"] . " " . $data[$i]["Time"];
		$LogErrorText = $data[$i]["ErrorText"];
		$LogDivId = "errlog" . $data[$i]["BlockSerialNo"] . $data[$i]["id"]; 
		$LogId = $data[$i]["id"];

		$template->assign_block_vars('row', array(
			'BLOCKSERNO' => $BlockSerNo,
			'SERVICE_NAME' => $ServiceNameText,
			'DATE_TIME' => $DateTime,
			'ERROR_TEXT' => $LogErrorText,
			'LOGDIVID' => $LogDivId,
			'LOGID' => $LogId,
		));

	}
	$i++;
}

//echo "</table>";


//echo "<p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p>";
//echo "<p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p>";

$template->pparse('body');


?>
