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

// Get Vars
//var_dump($_GET);
$Server = substr(@$_GET["Server"],0,10);
$Server = str_remove_sql_char($Server);

//Add New
$blocks = substr(@$_GET["blocks"],0,2000);
$ActionName = substr(@$_GET["ActionName"],0,20);
$services = substr(@$_GET["services"],0,1000);
//$Date_One = substr(@$_GET["Date_One"],0,10);
$Date_One = substr(@$_GET["date"],0,10);
$dateFrom = substr(@$_GET["dateFrom"],0,10);
$dateTo = substr(@$_GET["dateTo"],0,10);
$DateTime_Date = substr(@$_GET["DateTime_Date"],0,10);
$DateTime_Time_From = substr(@$_GET["DateTime_Time_From"],0,8);
$DateTime_Time_To = substr(@$_GET["DateTime_Time_To"],0,8);
$timeFrom = str_remove_sql_char(substr(@$_GET["timeFrom"],0,5));
$timeTo = str_remove_sql_char(substr(@$_GET["timeTo"],0,8));
$source = str_remove_sql_char(substr(@$_GET["source"],0,200));
$target = str_remove_sql_char(substr(@$_GET["target"],0,200));
$task_guid = substr(@$_GET["task_guid"],0,36);
$status = substr(@$_GET["Status"],0,20);
$task_owner = str_remove_sql_char(substr(@$_GET["username"],0,20));

//echo "<pre>"; var_dump($_GET); echo "</pre>"; exit;

$user["GUID"] = 'e07ff06a-7c37-4d37-a21f-09572bef69b8';

$rnd = rand(1000000,10000000);

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );	

//Add Task
$blocksList = explode(",", $blocks);
$svcList = explode(",", $services);

// Get lists 
$BlockList = array();
$BlockListBN = array();
$ActionsList = array();
$ActionsListBN = array();
$ServicesList = array();
$ServicesListBN = array();
// Block
//$Options = "<option name='BlockGuid value='NULL'>---</option>";
$SQL = $SQL_QUERY["BlocksAll"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//	$Options .= "<option name='BlockGuid' value='".$row["Guid"]."'>". $row["BlockSerialNo"] . "</option>";
	$BlockList[] = $row;
	$BlockListBN[$row["BlockSerialNo"]] = $row;
}
//echo "<pre>$SQL</pre>";
//var_dump($BlockList);
// Action
$SQL = $SQL_QUERY["ActionsAll"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//	$Options .= "<option name='ActionGuid' value='".$row["Guid"]."'>". $row["ActionName"] . "    (" . $row["ActionDescription"] .")</option>";
	$ActionsList[] = $row;
	$xlActionName = preg_replace("/[^A-Za-z0-9]/", '', $row["ActionName"]);
	$ActionsListBN[$xlActionName] = $row;
}
//echo "<pre>"; var_dump($ActionsList); echo "</pre>";
// Service
$SQL = $SQL_QUERY["ServicesAll"] . "WHERE [ServiceSide] = 'Olimex' AND [ServiceName] NOT IN ('Uncknown', 'ShturmanCore', 'PowerMonitor')";
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//	$Options .= "<option name='ServiceGuid' value='".$row["Guid"]."'>". $row["ServiceName"] . "</option>";
	$ServicesList[] = $row;
	$ServicesListBN[$row["ServiceName"]] = $row;
}
//var_dump($ServicesList);

//Change status
if ( $status )
{
//	echo "Change Status";
	TaskStatusChange ($task_guid, $status);
}

$addNew = FALSE; // flag если проверки и конвертиции пройдены - взводится в ТРУ

if ( $ActionName == "SendFile")
{
	//$source
	//$target
	$i = 0;
	while ( @$blocksList[$i] ) 
	{
		$p_id = "3179A392-D1FC-42FB-86A7-813744320429"; // [TaskStatus_Guid]

		$BlockSerNo = $blocksList[$i];
		$BlockGuid = $BlockListBN[$BlockSerNo]["Guid"];

		$ActionGuid = $ActionsListBN[$ActionName]["Guid"];

		$ServiceName = "";
		$ServiceGuid = "";
		$Parameter = "";

		$Source = $source;
		$Target = $target;
				
//		exit;
		AddTask($p_id, $BlockGuid, $ActionGuid, $ServiceGuid, $Source, $Target, $Parameter, $task_owner);
		$i++;

	}
}
elseif ( $ActionName == "SendLogPart" )
{
	//$date = explode("-",$DateTime_Date);
	$pDate = date("y.m.d", strtotime($Date_One));
//	$paramsList[0] = 

	$i = 0;
	while ( @$blocksList[$i] ) 
	{
		$s = 0;
		while ( @$svcList[$s] )
		{
			$p_id = "3179A392-D1FC-42FB-86A7-813744320429"; // [TaskStatus_Guid]

			$BlockSerNo = $blocksList[$i];
			$BlockGuid = $BlockListBN[$BlockSerNo]["Guid"];

			$ActionGuid = $ActionsListBN[$ActionName]["Guid"];

			$ServiceName = $svcList[$s];
			$ServiceGuid = $ServicesListBN[$ServiceName]["Guid"];
			$Parameter = "$pDate-$timeFrom-$timeTo";

			$Source = $source;
			$Target = $target;
				
			//exit;
			AddTask($p_id, $BlockGuid, $ActionGuid, $ServiceGuid, $Source, $Target, $Parameter, $task_owner);
		$s++;
		}

		$i++;
	}

}
elseif ( $ActionName == "SendLog" )
{
//	echo "SendLog";
	if ( $Date_One )
	{
		$paramsList[] = date("y.m.d", strtotime($Date_One));
	}
	else
	{
		if ( strtotime($dateFrom) > strtotime($dateTo) )
		{
			$x = $dateFrom;
			$dateFrom = $dateTo;
			$dateTo = $x;
		}

		$paramsList[] = date("y.m.d", strtotime($dateFrom));
		$curdate = date('Y-m-d', strtotime($dateFrom. ' + 1 days'));
		while ( strtotime($curdate) <= strtotime($dateTo) )
		{
			$paramsList[] = date("y.m.d", strtotime($curdate));
			$curdate = date('Y-m-d', strtotime($curdate. ' + 1 days'));
		}
//		echo $dateTo;
//		var_dump($paramsList);
//		$date = explode("-", $Date_One);
//		$addNew = TRUE;
	}
	$i = 0;
	while ( @$blocksList[$i] ) 
	{
		$s = 0;
		while ( @$svcList[$s] )
		{
			$p = 0;
			while ( @$paramsList[$p] )
			{
				$p_id = "3179A392-D1FC-42FB-86A7-813744320429";
		
				$BlockSerNo = $blocksList[$i];
				$BlockGuid = $BlockListBN[$BlockSerNo]["Guid"];

//				$ActionName = $ActionName;
				$ActionGuid = $ActionsListBN[$ActionName]["Guid"];

				$ServiceName = $svcList[$s];
				$ServiceGuid = $ServicesListBN[$ServiceName]["Guid"];
				$Parameter = $paramsList[$p];

				$Source = "";
				$Target = "";
				
//				echo $blocksList[$i] . "-" . $svcList[$s] . "-" . $paramsList[$p] . "<br/>";
//				echo $BlockGuid . "-" . $ServiceGuid . "-" . $Parameter . "--" . $ActionGuid ."<br/>";

/*				echo "<pre>
					$p_id = [$p_id];
					$BlockGuid = [$BlockGuid];
					$ActionGuid = [$ActionGuid];
					$ServiceGuid = [$ServiceGuid];
					$Source = [$Source];
					$Target = [$Target];
					$Parameter = [$Parameter];
					$task_owner = [$task_owner];
				</pre>";
				exit;
*/

				AddTask($p_id, $BlockGuid, $ActionGuid, $ServiceGuid, $Source, $Target, $Parameter, $task_owner);

//				AddTask
				$p++;
			}
			$s++;
		}
		$i++;
	}
}
elseif ( $ActionName == "GetFile" )
{
}
elseif ( $ActionName == "Reboot" or $ActionName == "ClearCache" )
{
	//echo "asdas";
	//$pDate = date("y.m.d", strtotime($Date_One));

	//var_dump($blocksList);

	$i = 0;
	while ( @$blocksList[$i] ) 
	{
		$p_id = "3179A392-D1FC-42FB-86A7-813744320429"; // [TaskStatus_Guid]
		$BlockSerNo = $blocksList[$i];
		$BlockGuid = $BlockListBN[$BlockSerNo]["Guid"];

		$ActionGuid = $ActionsListBN[$ActionName]["Guid"];

		$ServiceName = "";
		$ServiceGuid = "";
		$Parameter = "";

		$Source = $source;
		$Target = $target;
				
		//echo "($p_id, $BlockGuid, $ActionGuid, $ServiceGuid, $Source, $Target, $Parameter, $task_owner)";

		//exit;
		AddTask($p_id, $BlockGuid, $ActionGuid, $ServiceGuid, $Source, $Target, $Parameter, $task_owner);

		$i++;
	}

}
/*
if ( $addNew )
{
	$i = 0;
	while ( @$blocksList[$i] ) 
	{
//		if (
		$i++;
	}
}
*/

// Show tasks
// Working Time Difference by Wagons
if ( $ActionName != "" )
{
	$SQL =  $SQL_QUERY["iAll_Added"];
}
else
{
	$SQL = ( $Server !== "" ) ? str_replace("%%1%%", $Server, $SQL_QUERY["iAll_BlockActual"]) : $SQL_QUERY["iAll_Actual"] ;
	$SQL = $SQL . "ORDER BY [i].[Created] DESC";
}

//$SQL = str_replace("%%1%%", $Server, $SQL_QUERY["iAll_BlockActual"]);

//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
$data = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}

//var_dump($data);




$Edit = true;

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'blockdetails.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'BLOCKSERNO' => $Server,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	));

$a = "a";
$i = 0;
//var_dump($data[$i]);
while ( @$data[$i] )
{
	$RowStyle = (( $data[$i]["StatusId"] == "closed" ) || ( $data[$i]["StatusId"] == "deleted" )) ? "background-color:#ffffff;" : "";
	$RowStyle = ( $data[$i]["StatusId"] == "error" ) ? "background-color:#FFaaaa;" : $RowStyle;
	$RowStyle = ( $data[$i]["StatusId"] == "new" ) ? "background-color:#cccccc;" : $RowStyle;
	$RowStyle = ( $data[$i]["StatusId"] == "done" ) ? "background-color:#aaFFaa;" : $RowStyle;


	$EditLine = "";
	$EditLine = ($Edit && ($data[$i]["StatusId"] == "closed" || $data[$i]["StatusId"] == "deleted" || $data[$i]["StatusId"] == "done" || $data[$i]["StatusId"] == "error" || $data[$i]["StatusId"] == "new")) ? $EditLine . " [<a style='color:red;text-weght:bold;' href='?id=".$data[$i]["Guid"]."&Status=Active' title='Active'>A</a>]" : $EditLine;
	$EditLine = ($Edit && ($data[$i]["StatusId"] == "active" || $data[$i]["StatusId"] == "done" || $data[$i]["StatusId"] == "error")) ? $EditLine . " [<a style='color:red;text-weght:bold;' href='?id=$row[Guid]&Status=Closed' title='Close'>C</a>]" : $EditLine;
	$EditLine = ($Edit && ($data[$i]["StatusId"] == "active" || $data[$i]["StatusId"] == "done" || $data[$i]["StatusId"] == "error") || $data[$i]["StatusId"] == "uncknown" || $data[$i]["StatusId"] == "new") ? $EditLine . " [<a style='color:red;text-weght:bold;' href='?id=".$data[$i]["Guid"]."&Status=Deleted' title='Delete'>D</a>]" : $EditLine;

	$StatusId = $data[$i]["StatusId"];
//{BLOCKSERNO}_tasks

//	$SerialNo = (substr($data[$i]["BlockSerialNo"],0,3) == "STB") ? str_replace("STB", "<span style='color:#cccccc'>STB</span>",$data[$i]["BlockSerialNo"]) : $data[$i]["BlockSerialNo"];
	$SerialNo = str_replace("STB", "", (str_replace("STB0", "", $data[$i]["BlockSerialNo"])));

	$UploadedFileName = $data[$i]["UploadedFile"];
//	$UploadedFileLink = $CONFIG_SHTURMAN["UploadedFilesDirPathLocal"] . $data[$i]["BlockSerialNo"] . "\\" . $data[$i]["UploadedFile"];

	if (file_exists($CONFIG_SHTURMAN["UploadedFilesDirPathLocal"] . $data[$i]["BlockSerialNo"] . "\\" . $data[$i]["UploadedFile"]))
	{
//		$UploadedFileLink = "<a href='". $CONFIG_SHTURMAN["UploadedFilesDirPathLocal"] . $data[$i]["BlockSerialNo"] . "\\" . $data[$i]["UploadedFile"] . "'>" . $data[$i]["UploadedFile"] . "</a>";
		//$UploadedFileLink = $CONFIG_SHTURMAN["UploadedFilesDirPathLocal"] . $data[$i]["BlockSerialNo"] . "\\" . $data[$i]["UploadedFile"];
		$UploadedFileLink = "\\\\192.168.51.92\BlockUpload$\\" . $data[$i]["BlockSerialNo"] . "\\" . $data[$i]["UploadedFile"];
	}
	elseif ( substr($data[$i]["ActionType"],0,3) != "put" )
	{
		$UploadedFileLink = "--";
	}
	else
	{
		$UploadedFileLink = "-- in transit --";
	}

	// Временное решение. 
	if ( $UploadedFileLink == "--" or $UploadedFileLink == "-- in transit --" )
	{
		$RowStyle = "background-color:#ffffff;";
	}


	$source = ( $data[$i]["Source"] != "" ) ? "Source: " . $data[$i]["Source"] : "";
	$target = ( $data[$i]["Target"] != "" ) ? "Target: " . $data[$i]["Target"] : "";

	$l_Link = ( $UploadedFileName ) ? "Link" : "";

	$template->assign_block_vars('row', array(
		'GUID' => $data[$i]["Guid"],
		'S_ROWSTYLE' => $RowStyle,
		'EDIT' => $EditLine,
		'STATUS' => $data[$i]["StatusName"],
		'BLOCK' => $SerialNo,
		'ACTION' => $data[$i]["ActionName"],
		'SERVICE' => $data[$i]["ServiceShortName"],
		'SOURCE' => $source,
		'TARGERT' => $target,
		'PARAMETERS' => $data[$i]["Parameters"],
		'BLOCK_RESULT' => $data[$i]["ResultName"],
		'BLOCK_COMMENT' => $data[$i]["BlockComment"],
		'UPLOADED_FILE' => $UploadedFileName,
		'U_UPLOADED_FILE' => $UploadedFileLink,
		'DATE_ADDED' => $data[$i]["Created"],
		'DATE_PASSED' => $data[$i]["DatePassed"],
		'AUTHOR' => $data[$i]["UserName"],
		'L_LINK' => $l_Link,
		
	));

	if ( $UploadedFileLink == "--" or $UploadedFileLink == "-- in transit --" )
	{
		$template->assign_block_vars('row.no_link', array(
		));
	}
	else
	{
		$template->assign_block_vars('row.link', array(
		));
	}

	if ( in_array($StatusId, array('closed','deleted','done','error','new')) )
	{
		$template->assign_block_vars('row.active', array(
			'XXX' => "AA",
		));
	}
	if ( in_array($StatusId, array('active','done','error')) )
	{
		$template->assign_block_vars('row.close', array(
			'XXX' => "CC",
		));
	}
	if ( in_array($StatusId, array('active','done','error','uncknown','new')) )
	{
		$template->assign_block_vars('row.delete', array(
			'XXX' => "DD",
		));
	}

	$i++;
}

/// ADD New
//var_dump($BlockList)
$i = 0;
while ( @$BlockList[$i] )
{
	$selected = ( $Server == @$BlockList[$i]["BlockSerialNo"] ) ? "selected" : "";

	$template->assign_block_vars('blockslist', array(
		'BLOCKSERNO' => @$BlockList[$i]["BlockSerialNo"],
		'SELECTED' => $selected,
	));
	$i++;
}

//var_dump($ActionsList);
$i = 0;
while ( @$ActionsList[$i] )
{
//	echo $i;
//	var_dump($ActionsList);
//	echo $ActionsList[$i];
	$ActionNameX = preg_replace("/[^A-Za-z0-9]/", '', $ActionsList[$i]["ActionName"]);
	//$Options .= "<option name='ActionGuid' value='".$row["Guid"]."'>". $row["ActionName"] . "    (" . $row["ActionDescription"] .")</option>";
	$template->assign_block_vars('actionslist', array(
		'GUID' => $ActionsList[$i]["Guid"],
		'ACTION' => $ActionsList[$i]["ActionName"],
		'DESCRIPTION' => $ActionsList[$i]["ActionDescription"],
		'ACTION_NAME' => $ActionNameX,
	));                          	
	$i++;
}

$i = 0;
while ( @$ServicesList[$i] )
{
	//$Options .= "<option name='ActionGuid' value='".$row["Guid"]."'>". $row["ActionName"] . "    (" . $row["ActionDescription"] .")</option>";
	$template->assign_block_vars('serviceslist', array(
		'GUID' => $ServicesList[$i]["Guid"],
		'NAME' => $ServicesList[$i]["ServiceName"],
		'NAME_SHORT' => $ServicesList[$i]["ServiceShortName"],
//		'DESCRIPTION' => $ServicesList[$i]["ActionDescription"],
	));
	$i++;
}



//
// Generate the page
//
$template->pparse('body');

?>