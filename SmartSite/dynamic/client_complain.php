<?php
/***************************************************************************
 *                                client_complain.php
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

include $DRoot . '/includes/select_person.php';


// Get params from url
//$ShowAlert = substr(@$_GET["ShowAlert"], 0, 20);

$rnd = rand ( 0 , 1000000000 );
$currentdate = date("Y-m-d", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));

$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

$SQL_QUERY["List"] = "
SELECT 
	[p].[Last_Name],
	[p].[First_Name],
	[p].[Middle_Name],
	[sn].[SerialNo] AS [SensorSerialNo],
	[u].[Guid],
	--[u].[Vehicles_Guid],
	--[u].[Users_Roles_Guid],
	[ur].[Name] AS [UserRole],
	[u].[Is_Active],
	[v].[Name] AS [Wagon],
	[s].[Alias] AS [BlockSerialNo]
	
FROM [Users] AS [u]
INNER JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
LEFT JOIN [Vehicles] AS [v] ON [v].[Guid] = [u].[Vehicles_Guid]
LEFT JOIN [Sensors_Cardio] AS [sc] ON [sc].[Users_Guid] = [u].[Guid]
LEFT JOIN [Sensors] AS [sn] ON [sn].[Guid] = [sc].[Guid]
LEFT JOIN [Servers] AS [s] ON [s].[Guid] = [sc].Servers_Guid
LEFT JOIN [Users_Roles] AS [ur] ON [ur].[Guid] = [u].[Users_Roles_Guid]
";

$SQL = $SQL_QUERY["List"] . "
ORDER BY 
	[p].[Last_Name],
	[p].[First_Name],
	[p].[Middle_Name]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$dataFIO[]= $row;
}

$SQL = $SQL_QUERY["List"] . "
ORDER BY 
	[sn].[SerialNo] ASC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$dataSen[] = $row;
}

sqlsrv_close($conn) ;


//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'client_complain.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,

	));

	$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

	select_driver( "FIO", "NAME" ); // DDL выбора Водителя по FIO

	sqlsrv_close($conn) ;

/*
	$template->assign_block_vars('row', array(
		'L_LINK' => $l_Link,
	));

*/


if ( FALSE ) 
{
	$template->assign_block_vars('legend', array(
	));
}


$template->pparse('body');
?>