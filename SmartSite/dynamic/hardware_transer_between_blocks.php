<?php
/***************************************************************************
 *                                hardware_transer_between_blocks.php
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


// Get params from url

$rnd = rand ( 0 , 1000000000 );

$hw_title = str_remove_sql_char(substr(@$_GET["HwTitle"], 0, 100));
$hw_name = str_remove_sql_char(substr(@$_GET["HwName"], 0, 100));
$hw_code = str_remove_sql_char(substr(@$_GET["HwCode"], 0, 20));


$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

$SQL = "
/****** BOVI transfer between blocks history  ******/
SELECT TOP 10000
	[sc].[PropertyValue] AS [PropertyValue], 
	--[ServerGuid],
	[s].[BlockSerialNo],
	--MIN(sc.[Reported]) AS [From],
	FORMAT(MIN(sc.[Reported]), 'dd.MM.yyy') AS [From],
	--MAX(sc.[Reported]) AS [To],
	FORMAT(MAX(sc.[Reported]), 'dd.MM.yyy') AS [To]
FROM [ServersConfig] AS [sc]
	INNER JOIN [Servers] AS [s] ON [s].[Guid] = [sc].[ServerGuid]
WHERE 1=1
	AND [sc].[PropertyName] = '$hw_code'
	AND [sc].[PropertyValue] <> ''
	AND [s].[BlockSerialNo] NOT IN ('STB0001')
GROUP BY [sc].[PropertyValue], [s].[BlockSerialNo]
ORDER BY 
	[sc].[PropertyValue] ASC,
	[From] DESC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$data = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}

sqlsrv_close($conn) ;

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'hardware_transer_between_blocks.tpl')
);


$template->assign_vars(array(
	'NAME' => $hw_title,
	));


$i=0;
while ( @$data[$i] )
{
        $From = $data[$i]["From"];
        $To = $data[$i]["To"];
//        $PropertyName = $data[$i]["PropertyName"];
	$BlockSerialNo = $data[$i]["BlockSerialNo"];
        $PropertyValue = $data[$i]["PropertyValue"];
        $PropertyValueNext = @$data[$i+1]["PropertyValue"];
        $PropertyValuePrev = @$data[$i-1]["PropertyValue"];

	if ( $PropertyValue == $PropertyValueNext AND $PropertyValue != $PropertyValuePrev )
	{
		// New Group
		$template->assign_block_vars('group', array(
			'NAME' => str_replace("_", " ", $hw_name),
			'VALUE' => $PropertyValue,
		));
	}

	if ( $PropertyValue == $PropertyValueNext OR $PropertyValue == $PropertyValuePrev )
	{
		$template->assign_block_vars('group.row', array(
			'FROM' => $From,
			'TO' => $To,
			'BLOCKSERNO' => $BlockSerialNo,
		));

		
	}

	$i++;
}




$template->pparse('body');

?>