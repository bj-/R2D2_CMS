<?php
/***************************************************************************
 *                                serversstate.php
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
//include($DRoot . '/includes/functions_shturman.php');


// Get params from url
//$ShowAlert = substr(@$_GET["ShowAlert"], 0, 20);
//var_dump(@$_GET);
//$ViewType = ( strtoupper(@$_GET["type"]) == "ERRORS" ) ? "ERRORS" : "ALL";  // Show only errors
//$ViewType = ( $ViewType ) ? $ViewType : "ALL";  // Show only errors
$hoursAgoAnalyze = 72;

$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

$SQL = $SQL_QUERY["ServersInDiagAll"];

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$ServersList = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersList[] = $row["BlockSerialNo"];
}


$SQL = "
SELECT TOP 1000
 [s].[BlockSerialNo],
-- [st].[stName] AS [SrvTypeName],
-- [st].[stShortName] AS [SrvTypeCode],
 [mg].[Code] AS [GroupCode],
 [mg].[Name] AS [GroupName],
 [mg].[Description] AS [GroupDesc],
 [m].[Code] AS [MetricCode],
 [m].[Name] AS [MetricName],
 [m].[Description] AS [MetricDesc],
 --[m].[ObjectGuid] AS [MetricObjGuid],
 [m].[Limit] AS [MetricLimit],
 [d].[value] AS [value],
-- CONVERT(INT, CAST ([d].[value] AS float)) AS [xx],
 --[d].[WriteDate] AS [WriteDate],
 FORMAT([d].[WriteDate], 'dd.MM.yyy HH:mm') AS [WriteDateF],
 DATEDIFF(MINUTE, [d].[WriteDate], sysutcdatetime()) AS [MinutesAgo],
 --CAST([d].[value] AS INT) AS [ValueInt],
 --CAST([d].[value] AS FLOAT) AS [ValueInt],
 --CONVERT(FLOAT, [d].[value]) AS [ValueInt2],
 [d].[Server_Guid],
 [d].[MetricGroup_Guid],
 [d].[Metric_Guid]
-- ,(CAST([m].[Limit] AS INT) - CAST([d].[value] AS INT)) AS [s]
FROM 
	[DiagData] AS [d]
	INNER JOIN [MetricGroups] AS [mg] ON [mg].[Guid] = [d].[MetricGroup_Guid]
	INNER JOIN [Metrics] AS [m] ON [m].[Guid] = [d].[Metric_Guid]
	INNER JOIN [Servers] AS [s] ON [s].[Guid] = [d].[Server_Guid]
	INNER JOIN [Server_Types] AS [st] ON [st].[Guid] = [s].[ServerType_Guid]
 
WHERE 
	(NOT ([m].[Guid] IN ('9EC8E66F-FCEC-4609-AED3-F670D041D143','BB9B6AC8-6281-4E7B-8E27-1D9F346A9C2C') AND [s].[Guid] = '9D13BD19-FD39-448D-B5E4-761E83824CEA'))
	AND [m].[Limit] IS NOT NULL
	AND [d].[value] NOT IN ('N/A')
	AND ISNUMERIC([d].[value]) = 1
	--AND ISNUMERIC([d].[value])
	AND [d].[MetricGroup_Guid] NOT IN ('7E4F1510-2783-4D62-A1DF-20C69F333B28')
	AND [d].[Metric_Guid] NOT IN ('0736EF50-9D0E-4A0E-A4D4-213C336373E4', '216C4A78-FFC7-41E4-9999-EC9CD11331A2') 
--, '7E4F1510-2783-4D62-A1DF-20C69F333B28', '2B82E11B-1455-4EC4-8158-CBC15C7E074C')
	--AND CAST([m].[Limit] AS INT) < CONVERT(int, CAST([d].[value] AS INT))
	AND [d].[WriteDate] > DATEADD(HOUR, -%%1%%, sysutcdatetime())
	AND CONVERT(BIGINT, CAST ([d].[value] AS float)) > CAST([m].[Limit] AS BIGINT)

ORDER BY [mg].[Order] ASC, [m].[Name] ASC, [s].[OrderNo] ASC, [d].[value] DESC

";
$SQL = str_replace ("%%1%%", $hoursAgoAnalyze, $SQL);

//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$dataRAW = array();
$ErrorCount = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$dataRAW[] = $row;
	if ( $row["value"] > @$dataMaxVal[$row["Server_Guid"].$row["MetricGroup_Guid"].$row["Metric_Guid"]] )
	{
		$dataMaxVal[$row["Server_Guid"].$row["MetricGroup_Guid"].$row["Metric_Guid"]] = $row["value"];
	}
	//$row["Server_Guid"].$row["MetricGroup_Guid"].$row["Metric_Guid"]
	if ( $row["MinutesAgo"] < 60*6 ) { @$ErrorCount[$row["BlockSerialNo"]]["6h"]++; }
	if ( $row["MinutesAgo"] >= 60*6 and $row["MinutesAgo"] < 60*12 ) { @$ErrorCount[$row["BlockSerialNo"]]["12h"]++; }
	if ( $row["MinutesAgo"] >= 60*12 and $row["MinutesAgo"] < 60*24 ) { @$ErrorCount[$row["BlockSerialNo"]]["24h"]++; }
	if ( $row["MinutesAgo"] >= 60*24 and $row["MinutesAgo"] < 60*48 ) { @$ErrorCount[$row["BlockSerialNo"]]["48h"]++; }
	if ( $row["MinutesAgo"] >= 60*48 and $row["MinutesAgo"] < 60*72 ) { @$ErrorCount[$row["BlockSerialNo"]]["72h"]++; }
}

//echo "<pre>" . var_dump (@$ErrorCount) . "</pre>";

//exit;
$i = 0;
while ( @$dataRAW[$i] )
{
	if ( $dataRAW[$i]["value"] == $dataMaxVal[$dataRAW[$i]["Server_Guid"].$dataRAW[$i]["MetricGroup_Guid"].$dataRAW[$i]["Metric_Guid"]] )
	{
		$data[] = $dataRAW[$i];
	}
	$i++;
}
//	$dataRAW[] = $row;


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
	'body' => 'serversstatehistory.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTTIME' => $currenttime,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'HOURS_AGO_ANALYZE' => $hoursAgoAnalyze,
	'COLUMN_COUNT' => "3",

	));

$MetricGroupCurrent = "";
$MetricCurrent = "";
//$ServerCount = 0;
$NewGroup = FALSE;
$NewMetric = FALSE;
//$OverLimitSum = 0; // флаг наличия ошибок


$i = 0;
while ( @$ServersList[$i] )
{

	$BlockSerialNo = $ServersList[$i];


	$c6h = @$ErrorCount[$ServersList[$i]]["6h"];
	$c12h = @$ErrorCount[$ServersList[$i]]["12h"];
	$c24h = @$ErrorCount[$ServersList[$i]]["24h"];
	$c48h = @$ErrorCount[$ServersList[$i]]["48h"];
	$c72h = @$ErrorCount[$ServersList[$i]]["72h"];

	if ( $c6h or $c12h or $c24h or $c48h or $c72h )
	{
		$template->assign_block_vars('statrow', array(
			'SERVERNAME' => $BlockSerialNo,
//			'SERVER_GUID' => $Server_Guid,
//			'METRIC_GROUP_GUID' => $MetricGroup_Guid,
//			'METRIC_GUID' => $Metric_Guid,
//			'METRICNAME' => $MetricName,
//			'STYLE' => $Style,
//			'VALUE' => $xValue,
//			'TIME_AGO' => "$TimeAgo",
			'6H' => $c6h,
			'12H' => $c12h,
			'24H' => $c24h,
			'48H' => $c48h,
			'72H' => $c72h,
		));
	}
	$i++;
}

$i = 0;
//while ( @$data[$i]["Last_Name"] )
while ( @$data[$i] )
{

	$BlockSerialNo = $data[$i]["BlockSerialNo"];
	$GroupCode = $data[$i]["GroupCode"];
	$GroupName = $data[$i]["GroupName"];
	$GroupDesc = $data[$i]["GroupDesc"];
	$MetricCode = $data[$i]["MetricCode"];
	$MetricValueIsLast = ( $data[$i]["MetricCode"] != @$data[$i+1]["MetricCode"] ) ? TRUE : FALSE;
	$MetricName = iconv("Windows-1251", "UTF-8", $data[$i]["MetricName"]);
	$MetricDesc = $data[$i]["MetricDesc"];
//	$MetricObjGuid = $data[$i]["MetricObjGuid"];
	$MetricLimit = $data[$i]["MetricLimit"];
 	$value = $data[$i]["value"];
	$Server_Guid = $data[$i]["Server_Guid"];
	$MetricGroup_Guid = $data[$i]["MetricGroup_Guid"];
	$Metric_Guid = $data[$i]["Metric_Guid"];
//	$WriteDate = $data[$i]["WriteDate"];
	$WriteDateF = $data[$i]["WriteDateF"];
	$MinutesAgo = $data[$i]["MinutesAgo"];
	$TimeAgo = ($MinutesAgo > 59) ? round(($MinutesAgo / 60),0) . "h" : $MinutesAgo . "m";
	$TimeAgo = ( $MinutesAgo > 10 ) ? "$TimeAgo" : "";

	$valuePArr = ParseValue($data[$i]);
	$xValue = $valuePArr["Value"];
//	$OverLimit = 0;
	$Style = ""; //( $OverLimit == 1 ) ? "background-color:#FF7777;" : ""; // Alert

	//$LastName = iconv("Windows-1251", "UTF-8", $data[$i]["Last_Name"]);

	if ( $data[$i]["GroupCode"] != $MetricGroupCurrent ) 
	{ 
		$MetricGroupCurrent = $data[$i]["GroupCode"];
		$NewGroup = TRUE;
	}

	if ( $NewGroup ) // and $ViewType == "ALL" )
	{
		$template->assign_block_vars('group', array(
			'NAME' => $GroupName,
		));
		$NewGroup = FALSE;
	}
	

	$template->assign_block_vars('group.row', array(
		'SERVERNAME' => $BlockSerialNo,
		'SERVER_GUID' => $Server_Guid,
		'METRIC_GROUP_GUID' => $MetricGroup_Guid,
		'METRIC_GUID' => $Metric_Guid,
		'METRICNAME' => $MetricName,
		'STYLE' => $Style,
		'VALUE' => $xValue,
		'TIME_AGO' => "$TimeAgo",
	));

	$i++;
}


//echo @$OverLimitSum;
if ( ! @$data )
{
	$template->assign_block_vars('noerrors', array(
	));

}

$template->pparse('body');
?>