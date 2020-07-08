<?php
/***************************************************************************
 *                                persons.php
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
//$ShowAlert = substr(@$_GET["ShowAlert"], 0, 20);
//var_dump(@$_GET);
$ViewType = ( strtoupper(@$_GET["type"]) == "ERRORS" ) ? "ERRORS" : "ALL";  // Show only errors
//$ViewType = ( $ViewType ) ? $ViewType : "ALL";  // Show only errors


$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );


$SQL = $SQL_QUERY["ServersInDiagAll"];

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$ServersList = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersList[] = $row["BlockSerialNo"];
}
$ServersCount = count($ServersList);
$ColumnCount = ( $ViewType == "ERRORS" ) ? "2": $ServersCount+1;

//echo "<pre>"; var_dump($ServersList); echo "</pre>";

$SQL = $SQL_QUERY["ServerDiagInfo"];

//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$data = array();
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
	'body' => 'serversstate.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTTIME' => $currenttime,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'COLUMN_COUNT' => $ColumnCount,

	));

$MetricGroupCurrent = "";
$MetricCurrent = "";
//$ServerCount = 0;
$NewGroup = FALSE;
$NewMetric = FALSE;
$OverLimitSum = 0; // флаг наличия ошибок

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
	$MetricObjGuid = $data[$i]["MetricObjGuid"];
	$MetricLimit = $data[$i]["MetricLimit"];
	$value = $data[$i]["value"];
	$Server_Guid = $data[$i]["Server_Guid"];
	$MetricGroup_Guid = $data[$i]["MetricGroup_Guid"];
	$Metric_Guid = $data[$i]["Metric_Guid"];
//	$WriteDate = $data[$i]["WriteDate"];
	$WriteDateF = $data[$i]["WriteDateF"];
	$MinutesAgo = $data[$i]["MinutesAgo"];
	$TimeAgo = ($MinutesAgo > 59) ? round(($MinutesAgo / 60),0) . "h" : $MinutesAgo . "m";
	$TimeAgo = ( $MinutesAgo > 10 ) ? " ($TimeAgo)" : "";

	$valuePArr = ParseValue($data[$i]);
	$xValue = $valuePArr["Value"];
	$OverLimit = $valuePArr["OwerLimit"];
//	$OverLimit = 1;
//	$OverLimit = 0;
	$OverLimitSum = $OverLimitSum + $OverLimit;
	$Style = ( $OverLimit == 1 ) ? "background-color:#FF7777;" : ""; // Alert
//	$BlockSerialNo = $data[$i][]

	if ( $TimeAgo )
	{
		$TimeAgoServers[$BlockSerialNo]["Value"] = $TimeAgo;
		$TimeAgoServers[$BlockSerialNo]["Server"] = $BlockSerialNo;
	}

	
	if ( $data[$i]["GroupCode"] != $MetricGroupCurrent ) 
	{ 
		$MetricGroupCurrent = $data[$i]["GroupCode"];
		$NewGroup = TRUE;
	}

	if ( $data[$i]["MetricCode"] != $MetricCurrent ) 
	{ 
		$MetricCurrent = $data[$i]["MetricCode"];
		$NewMetric = TRUE;
	}

	//$LastName = iconv("Windows-1251", "UTF-8", $data[$i]["Last_Name"]);

	if ( $NewGroup ) // and $ViewType == "ALL" )
	{
//		echo $OverLimit;
		if ( $ViewType == "ALL" or $OverLimit )
		{
			$template->assign_block_vars('group', array(
				'NAME' => $GroupName,
			));
	
			$template->assign_block_vars('group.row', array(
			));
			$NewGroup = FALSE;
			
		}

		if ( $ViewType == "ALL" )
		{
			$template->assign_block_vars('group.row.col_header', array(
				'NAME' => "Metric",
			));

			$s = 0;
			while ( @$ServersList[$s] ) 
			{
				$ServerName = $ServersList[$s];
				$template->assign_block_vars('group.row.col_header', array(
					'NAME' => $ServerName,
				));
				$s++;
			}
		}

	}
//	elseif ( $NewGroup and $OverLimit )
//	{
		
//	}
	

	if ( $NewMetric and $ViewType == "ALL" )
	{
		
		$template->assign_block_vars('group.row', array(
		));
		$template->assign_block_vars('group.row.col', array(
			'VALUE' => $MetricName,
		));

//		$template->assign_block_vars('group.row.col', array(
//			'VALUE' => $MetricName,
//			'METRIC_NAME' => $MetricName,
//		));
		
		$NewMetric = FALSE;
		$ServerId = 0;
	}
	else
	{
		$NewMetric = FALSE;
	}
	

	if ( ! $NewMetric and ! $NewGroup)
	{

//		echo $ViewType;
		if ( $ViewType == "ERRORS" ) //and $OverLimit == 1 )
		{
//			if ( $OverLimit or 1)
			if ( $OverLimit )
			{
				$Name = $BlockSerialNo . " -> " . $MetricName ;
//				echo "dd";
				$template->assign_block_vars('group.rowerrors', array(
					'SERVERNAME' => $BlockSerialNo,
					'SERVER_GUID' => $Server_Guid,
					'METRIC_GROUP_GUID' => $MetricGroup_Guid,
					'METRIC_GUID' => $Metric_Guid,
					'METRICNAME' => $MetricName,
					'STYLE' => $Style,
					'VALUE' => $xValue,
					'TIME_AGO' => "$TimeAgo",
				));
			}
		}
		else
		{
/////////////

		while ( $BlockSerialNo != @$ServersList[$ServerId] )
		{
			$template->assign_block_vars('group.row.col', array(
				'STYLE' => "background-color:#EDEDED;",
				'VALUE' => "",
			));
			$ServerId++;
		}

//		$warningStyle = ( $OverLimit == 2 ) ? "color:orange;" : $warningStyle; // TimeWarning

		$template->assign_block_vars('group.row.col', array(
			'STYLE' => $Style,
			'VALUE' => $xValue,
			'TIME_AGO' => $TimeAgo,
			'SERVER_GUID' => $Server_Guid,
			'METRIC_GROUP_GUID' => $MetricGroup_Guid,
			'METRIC_GUID' => $Metric_Guid,
		));

		$ServerId++;

		if ( $MetricValueIsLast )
		{
			while ( $ServerId < count($ServersList) )
			{
				$template->assign_block_vars('group.row.col', array(
					'STYLE' => "background-color:#EDEDED;",
					'VALUE' => "",
				));
				$ServerId++;
			}
		}
////////
		}

	}

	$i++;
}

//$i = 0;
if ( ($ViewType == "ERRORS") ) //and $OverLimit == 1 )
{
	if ( @$TimeAgoServers )
	{
		$template->assign_block_vars('group', array(
			'NAME' => "Stat time errors",
		));

		foreach ( @$TimeAgoServers as $s )
		{
		
			$template->assign_block_vars('group.rowerrors', array(
				'SERVERNAME' => $s["Server"],
				'METRICNAME' => "No Stat",
				'STYLE' => "background-color:#FFFF77;",
				'VALUE' => $s["Value"],
			));
		}
	}

}
else 
{
	$template->assign_block_vars('legend', array(
	));
}

//echo @$OverLimitSum;
if ( @$OverLimitSum == 0 )
{
	$template->assign_block_vars('noerrors', array(
	));

}

$template->pparse('body');
?>