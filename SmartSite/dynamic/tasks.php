<?php
/***************************************************************************
 *                                tasks.php
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
$iObjectId = str_remove_sql_char(substr(@$_GET["ObjectId"], 0, 100));
$iObjectType = str_remove_sql_char(substr(@$_GET["ObjectType"], 0, 20));

$Filter =  str_remove_sql_char(substr(@$_GET["Filter"], 0, 20));

$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

$SQL = "
/****** Tasks list  ******/
SELECT TOP 500 
	[t].[ID],
	[t].[Status_ID],
	[ts].[Code] AS [Status_Code], 
	[ts].[Name] AS [Status_Name], 
	[ts].[Description],
	--[t].[Status_ChangeDate],
	FORMAT([t].[Status_ChangeDate], 'dd.MM.yyy HH:mm') AS [Status_ChangeDate],
	[t].[Severity_ID],
	[tsv].[Code] AS [Severity_Code],
	[tsv].[Name] AS [Severity_Name],
	[t].[Object_Guid],
	[t].[Object_ID],
	[t].[Object_TypeID],
	[s].[BlockSerialNo],
	[ot].[Code] AS [Object_Code],
	[ot].[Name] AS [Object_Name],
	[ctt].[Name] AS [Complain_Name],
	[t].[Subject],
	[t].[Desigion],
	[t].[Result],
	--[t].[Issue_DateTimeStart],
	FORMAT([t].[Issue_DateTimeStart], 'dd.MM.yyy HH:mm') AS [Issue_DateTimeStart],
	--[t].[Issue_DateTimeEnd],
	FORMAT([t].[Issue_DateTimeEnd], 'dd.MM.yyy HH:mm') AS [Issue_DateTimeEnd],
--	[t].[Issue_Reporter],
--	[t].[Assigned_ID]
	[a].[Code] AS [Assigned_Code],
	[a].[Name] AS [Assigned_Name],
	[a].[Description] AS [Assigned_Description],
	[tr].[Name] AS [Issue_Reporter],
	[t].[Author],
	--[t].[Created],
	FORMAT([t].[Created], 'dd.MM.yy') AS [Created],
	FORMAT(DATEADD(day,+21, [t].[Created]), 'dd.MM.yyy') AS [DeadLine],
	DATEDIFF(DAY ,GETUTCDATE(), DATEADD(day,+21, [t].[Created])) AS [DeadLineDays],
	--[t].[Finished],
	FORMAT([t].[Finished], 'dd.MM.yy') AS [Finished]
FROM [Tasks] AS [t]
INNER JOIN [Tasks_Statuses] AS [ts] ON [ts].[ID] = [t].[Status_ID]
LEFT JOIN [Tasks_ObjectTypes] AS [ot] ON [ot].[ID] = [t].[Object_TypeID]
LEFT JOIN [Servers] AS [s] ON [s].[Guid] = [t].[Object_Guid]
INNER JOIN [Tasks_Severity] AS [tsv] ON [tsv].[ID] = [t].[Severity_ID]
LEFT JOIN [Tasks_Reporters] AS [tr] ON [tr].[ID] = [t].[Issue_Reporter]
LEFT JOIN [Tasks_Assigned] AS [a] ON [a].[ID] = [t].[Assigned_ID]
LEFT JOIN [Tasks_Templates] AS [ctt] ON [ctt].[ID] = [t].[Complain_ID]
WHERE 1=1
	%%1%%
	%%2%%
ORDER BY 
	[t].[Finished] ASC,
	[s].[BlockSerialNo] ASC
";

if ( $Filter == "All" )
{
	$SQL = str_replace("%%2%%", "", $SQL);
}
elseif ( $Filter == "Opened" )
{
	$SQL = str_replace("%%2%%", "AND [t].[Finished] IS NULL", $SQL);
}
elseif ( $Filter == "Repair" )
{
	$SQL = str_replace("%%2%%", "AND [a].[Code] IN ('RepairDep') AND [t].[Finished] IS NULL", $SQL);
}
elseif ( $Filter == "Dev" )
{
	$SQL = str_replace("%%2%%", "AND [a].[Code] IN ('SoftwareDep') AND [t].[Finished] IS NULL", $SQL);
}
elseif ( $Filter == "Hardware" )
{
	$SQL = str_replace("%%2%%", "AND [a].[Code] IN ('HardwareDep') AND [t].[Finished] IS NULL", $SQL);
}
elseif ( $Filter == "Bad" )
{
	$SQL = str_replace("%%2%%", "AND [a].[Code] IN ('SupportDep', 'HardwareDep', 'SoftwareDep', 'TestDep', 'ConfigDep', 'Uncknown') AND [t].[Finished] IS NULL", $SQL);
}
else {
	$SQL = str_replace("%%2%%", "", $SQL);
}

// Для типа Block
$SQL = ( strlen($iObjectId) > 4 and $iObjectType == "Block" ) ? str_replace("%%1%%", "AND [s].[BlockSerialNo] = '$iObjectId'", $SQL) : str_replace("%%1%%", "", $SQL);

//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$data = Array();
$TaskID_List = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[]= $row;
	$TaskID_List[] = $row["ID"];
}

//"<pre>"; var_dump($data); echo "</pre>"; 

// Детализация по таскам
$SQL = "
/****** Task Properties  ******/
SELECT 
	[tf].[ID],
	[tf].[Task_ID],
	[tf].[Field_ID],
	[tfg].[Code]        AS [Group_Code],
	[tfg].[Name]        AS [Group_Name],
	[tfg].[Description] AS [Group_Description],
	[tfg].[Order]       AS [Group_Order],
	[tfl].[Group_ID]    AS [Field_Group_ID],
	[tfl].[Order]       AS [Field_Order],
	[tfl].[Code]        AS [Field_Code],
	[tfl].[Name]        AS [Field_Name],
	[tfl].[Description] AS [Field_Description],
	[tf].[Value],
	--[tf].[Created],
	FORMAT([tf].[Created], 'dd.MM.yyy HH:mm') AS [Created],
	--[tf].[LastEdit]
	FORMAT([tf].[LastEdit], 'dd.MM.yyy HH:mm') AS [LastEdit],
	[tf].[Editor],
	[tf].[Deleted]
FROM [Tasks_Fields] AS [tf]
INNER JOIN [Tasks_Fields_List] AS [tfl] ON [tfl].[ID] = [tf].[Field_ID]
INNER JOIN [Tasks_Fields_Groups] AS [tfg] ON [tfg].[ID] = [tfl].[Group_ID]
ORDER BY [tf].[Deleted] ASC, [tfg].[Order] ASC, [tfl].[Order] ASC, [tfl].[Code] ASC
--LEFT JOIN
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data = Array();
$TaskProp = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$TaskProp[$row["Task_ID"]][]= $row;
}

sqlsrv_close($conn) ;


$currentdate = date("Y-m-d", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'tasks.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'OBJECT_ID' => $iObjectId,
	'OBJECT_TYPE' => $iObjectType,

	));

/*
	$template->assign_block_vars('row', array(
		'L_LINK' => $l_Link,
	));

*/

//$TaskProp_Field_Group_ID_Last = ""

$TaskProp_Deleted_Curr = FALSE; // Объединение удаленных пропертей в одну группу

$i = 0;
while ( @$data[$i] )
{
	$TaskID = $data[$i]["ID"];
//	$Status_ID = $data[$i]["Status_ID"];
	$Status_Code = $data[$i]["Status_Code"];
	$Active = ( in_array($Status_Code, Array("New","InProgress","Done")) ) ? TRUE : FALSE;

	$Status_Name = iconv("Windows-1251", "UTF-8", $data[$i]["Status_Name"]);
	if ( $Status_Code == "New" ) { $Status_Image = "<img src=\"/pic/ico/new_20x20.png\" title=\"$Status_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	elseif ( $Status_Code == "InProgress" ) { $Status_Image = "<img src=\"/pic/ico/inprogress_20x20.png\" title=\"$Status_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	elseif ( $Status_Code == "Fake" ) { $Status_Image = "<img src=\"/pic/ico/closed_20x20.png\" title=\"$Status_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	elseif ( $Status_Code == "Rejected" ) { $Status_Image = "<img src=\"/pic/ico/ArrowDown_20x20.png\" title=\"$Status_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	elseif ( $Status_Code == "Done" ) { $Status_Image = "<img src=\"/pic/ico/done_20x20.png\" title=\"$Status_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	elseif ( $Status_Code == "Closed" ) { $Status_Image = "<img src=\"/pic/ico/done_20x20.png\" title=\"$Status_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	elseif ( $Status_Code == "Deleted" ) { $Status_Image = "<img src=\"/pic/ico/trash_20x20.png\" title=\"$Status_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	else { $Status_Image = $Status_Name; }
//	$Status_ChangeDate = $data[$i]["Status_ChangeDate"];
//	$Severity_ID = $data[$i]["Severity_ID"];
	$Severity_Code = $data[$i]["Severity_Code"];
	$Severity_Name = iconv("Windows-1251", "UTF-8", $data[$i]["Severity_Name"]);
	if ( $Severity_Code == "Minor" ) { $Severity_Image = "<img src=\"/pic/ico/lightblue.png\" title=\"$Severity_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	elseif ( $Severity_Code == "Low" ) { $Severity_Image = "<img src=\"/pic/ico/lightgreen.png\" title=\"$Severity_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	elseif ( $Severity_Code == "Medium" ) { $Severity_Image = "<img src=\"/pic/ico/green.png\" title=\"$Severity_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	elseif ( $Severity_Code == "High" ) { $Severity_Image = "<img src=\"/pic/ico/orange.png\" title=\"$Severity_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	elseif ( $Severity_Code == "Critical" ) { $Severity_Image = "<img src=\"/pic/ico/red.png\" title=\"$Severity_Name\" width=\"16\" height=\"16\" border=\"0\" />"; }
	else { $Severity_Image = $Severity_Name; }

//	$Object_Guid = $data[$i]["Object_Guid"];
//	$Object_ID = $data[$i]["Object_ID"];
//	$Object_TypeID = $data[$i]["Object_TypeID"];
	$BlockSerialNo = iconv("Windows-1251", "UTF-8", $data[$i]["BlockSerialNo"]);
	$BlockSerialNoShort = str_replace("STB", "", str_replace("STB0", "", $BlockSerialNo));
//	$Object_Code = $data[$i]["Object_Code"];
//	$Object_Name = $data[$i]["Object_Name"];
	$Complain_Name = iconv("Windows-1251", "UTF-8", $data[$i]["Complain_Name"]);
	$Subject = iconv("Windows-1251", "UTF-8", $data[$i]["Subject"]);
	$Desigion = iconv("Windows-1251", "UTF-8", $data[$i]["Desigion"]);
	$Result = iconv("Windows-1251", "UTF-8", $data[$i]["Result"]);
	$Issue_Reporter = iconv("Windows-1251", "UTF-8", $data[$i]["Issue_Reporter"]);
	$Author = iconv("Windows-1251", "UTF-8", $data[$i]["Author"]);
	$Issue_DateTimeStart = str_replace("00:00", "",  $data[$i]["Issue_DateTimeStart"]);
	$Issue_DateTimeEnd   = str_replace("00:00", "",  $data[$i]["Issue_DateTimeEnd"]);
	$Created = $data[$i]["Created"];
	$DeadLine = $data[$i]["DeadLine"];
	$DeadLineDays = $data[$i]["DeadLineDays"];
	$Finished = $data[$i]["Finished"];
//	$Finished = $data[$i]["Finished"];
	
    $Assigned_Code = $data[$i]["Assigned_Code"];
	$Assigned_Name = iconv("Windows-1251", "UTF-8", $data[$i]["Assigned_Name"]);
	$Assigned_Description = iconv("Windows-1251", "UTF-8", $data[$i]["Assigned_Description"]);

	$StyleBgRow = ( $i % 2 == 0 ) ? "" : $style["bg-l-beige"];
	$StyleBgRow = ( $Active ) ? $StyleBgRow : $style["bg-llll-gray"] . $style["color-l-gray"] ;
	
	$Style_DeadLine = ( $DeadLineDays < 10 and !$Finished ) ? $style["bg-l-yellow"] : "";
	$Style_DeadLine = ( $DeadLineDays < 0 and !$Finished ) ? $style["bg-l-red"] : $Style_DeadLine;

	$template->assign_block_vars('row', array(
//		'GUID' => $UserGuid,
		'TASK_ID' => $TaskID,
		'SEVERITY' => $Severity_Image, //$Severity_Name,
		'STATUS' => $Status_Image, //$Status_Name,
		'NAME' => $BlockSerialNoShort,
		'COMPLAIN' => ($Complain_Name) ? "Жалоба: $Complain_Name" : "",
		'SUBJECT' => $Subject,
		'DESIGION' => $Desigion,
		'RESULT' => $Result,
		'ISSUE_DATE' => $Issue_DateTimeStart,
		'ISSUE_DATE_END' => $Issue_DateTimeEnd,
		'CREATED' => $Created,
		'DEADLINE_DATE' => (!$Finished) ? $DeadLine : "",
		'DEADLINE_DAYS' => (!$Finished) ? $DeadLineDays : "",
		'FINISHED' => $Finished,
		'REPORTER' => $Issue_Reporter,
		'AUTHOR' => $Author,
		'STYLE_ROW' => $StyleBgRow,
		'STYLE_DEADLINE' => $Style_DeadLine,
		'ASSIGNED_CODE' => $Assigned_Code,
		'ASSIGNED_NAME' => $Assigned_Name,
		'ASSIGNED_DESCRIPTION' => $Assigned_Description,
	));

	if ( @$TaskProp[$TaskID] ) 
	{
		$template->assign_block_vars('row.task_details', array(
		));
	}

	
	$d = 0;
	while ( @$TaskProp[$TaskID][$d] )
	{


		$TaskProp_ID = $TaskProp[$TaskID][$d]["ID"];
		$TaskProp_Task_ID = $TaskProp[$TaskID][$d]["Task_ID"];
		$TaskProp_Field_ID = $TaskProp[$TaskID][$d]["Field_ID"];

		$TaskProp_Group_Code = $TaskProp[$TaskID][$d]["Group_Code"];
		$TaskProp_Group_Name = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Group_Name"]);
		$TaskProp_Group_Description = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Group_Description"]);
		$TaskProp_Group_Order = $TaskProp[$TaskID][$d]["Group_Order"];

		$TaskProp_Field_Group_ID = $TaskProp[$TaskID][$d]["Field_Group_ID"];
		$TaskProp_Field_Code = $TaskProp[$TaskID][$d]["Field_Code"];
		$TaskProp_Field_Name = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Field_Name"]);
		$TaskProp_Field_Description = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Field_Description"]);

		$TaskProp_Value = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Value"]);
		if ( $TaskProp_Field_Code == "Redmine_Tasks" )
		{
			$rv = $TaskProp_Value;
			$rv = preg_replace ('/\s+/', ';', $rv);
			$rv = str_replace (array(",", "#"), ";", $rv);
			$rv = preg_replace ('/;+/', ';', $rv);
			$rv = (substr($rv,0,1) == ";" ) ? substr($rv,1) : $rv;
			$rvArr = explode(";",$rv);

			$TaskProp_Value = "";
			foreach ( $rvArr as $R_Task)
			{
				$TaskProp_Value .= "<a OnClick=\"redmine_issue_show('$R_Task', '#TaskPropRedmineTask_$TaskID');\" title=\"Show Redmine Task details\" style=\"cursor:pointer;font-weight:bold;\">$R_Task</a>; "; 
			}
		}

		$TaskProp_Created = $TaskProp[$TaskID][$d]["Created"];
		$TaskProp_LastEdit = $TaskProp[$TaskID][$d]["LastEdit"];
		$TaskProp_Editor = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Editor"]);
		$TaskProp_Deleted = ( $TaskProp[$TaskID][$d]["Deleted"] ) ? TRUE : FALSE;

		$StyleBgRow = ( $d % 2 == 0 ) ? "" : $style["bg-beige"];
		$StyleBgRow = ( $TaskProp_Deleted ) ? "color:gray;text-decoration:line-through;" : $StyleBgRow;

//		$oldValue = iconv("Windows-1251", "UTF-8", @$TaskProp[$TaskID][$PropID]["Value"]);

		// Task Properties
		$template->assign_block_vars('row.task_details.row_prop', array(
		));		

		if ( $TaskProp_Field_Group_ID != @$TaskProp_Field_Group_ID_Last and !$TaskProp_Deleted )
		{
			$template->assign_block_vars('row.task_details.row_prop.group', array(
				'NAME' => $TaskProp_Group_Name,
				'DESCRIPTION' => $TaskProp_Group_Description,
			));
			$TaskProp_Field_Group_ID_Last = $TaskProp_Field_Group_ID;
		}
		elseif ( $TaskProp_Deleted and !$TaskProp_Deleted_Curr )
		{
			$template->assign_block_vars('row.task_details.row_prop.group', array(
				'NAME' => "Удаленные",
				'DESCRIPTION' => "Удаленные параметры",
			));
			$TaskProp_Deleted_Curr = TRUE;
		}
		$template->assign_block_vars('row.task_details.row_prop.details', array(
			'ID' => $TaskProp_ID,
			'TASK_ID' => $TaskProp_Task_ID,
			'FIELD_ID' => $TaskProp_Field_ID,
//			'GROUP_ID' => $TaskProp_Field_Group_ID,
			'CODE' => $TaskProp_Field_Code,
			'NAME' => $TaskProp_Field_Name,
			'DESCRIPTION' => $TaskProp_Field_Description,
			'VALUE' => $TaskProp_Value,
			'CREATED' => $TaskProp_Created,
			'LASTEDIT' => $TaskProp_LastEdit,
			'EDITOR' => $TaskProp_Editor,
			'DELETED' => $TaskProp_Deleted,


			'STYLE_ROW' => $StyleBgRow,
		));

/*




		$TaskProp_ID = $TaskProp[$TaskID][$d]["ID"];
//		$TaskProp_Task_ID = $TaskProp[$TaskID][$d]["Task_ID"];
//		$TaskProp_Field_ID = $TaskProp[$TaskID][$d]["Field_ID"];
//		$TaskProp_Field_Group_ID = $TaskProp[$TaskID][$d]["Field_Group_ID"];
//		$TaskProp_Created = $TaskProp[$TaskID][$d]["Created"];
//		$TaskProp_LastEdit = $TaskProp[$TaskID][$d]["LastEdit"];
//		$TaskProp_Field_Code = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Field_Code"]);
		$TaskProp_Field_Name = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Field_Name"]);
//		$TaskProp_Field_Description = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Field_Description"]);
		$TaskProp_Value = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Value"]);

		$StyleTaskPropBgRow = ( $d % 2 == 0 ) ? "" : $style["bg-beige"];

		$template->assign_block_vars('row.task_details.row_details', array(
			'ID' => $TaskProp_ID,
			'NAME' => $TaskProp_Field_Name,
			'VALUE' => $TaskProp_Value,

			'STYLE_ROW' => $StyleTaskPropBgRow,

		));
*/
		$d++;
	}

	$i++;
}

$template->pparse('body');
?>