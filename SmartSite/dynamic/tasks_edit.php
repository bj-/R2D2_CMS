<?php
/***************************************************************************
 *                                task_edit.php
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

//echo "фыапукпруцкпрукцпуйкпрцукпуцкпукЭж";

// Get params from url
//$ShowAlert = substr(@$_GET["ShowAlert"], 0, 20);
$TaskID = str_remove_sql_char(substr(@$_GET["ID"], 0, 20));
$TaskID = ( !$TaskID ) ? str_remove_sql_char(substr(@$_GET["TaskID"], 0, 20)) : $TaskID ;
$Action = str_remove_sql_char(substr(@$_GET["Action"], 0, 20));
$TaskNew = ( strtoupper($Action) == "NEW" ) ? TRUE : FALSE;
$TaskEdit= ( strtoupper($Action) == "EDIT" ) ? TRUE : FALSE;
$TaskNewObjectType = str_remove_sql_char(substr(@$_GET["ObjectType"], 0, 20));
$TaskNewObjectId = str_remove_sql_char(substr(@$_GET["ObjectID"], 0, 40));


$Save = ( @$_GET["Save"] ) ? TRUE : FALSE;

//echo "<pre>"; var_dump($_GET); echo "</pre>";

$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

$SQL = "
/****** Task  ******/
SELECT TOP 1000 
	[t].[ID],
	[t].[Status_ID],
	[ts].[Code] AS [Status_Code], 
	[ts].[Name] AS [Status_Name], 
	[ts].[Description] AS [Status_Description], 
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
	[t].[Complain_ID],
	[t].[Subject],
	[t].[Desigion],
	[t].[Result],
	FORMAT([Issue_DateTimeStart], 'yyy-MM-dd HH:mm') AS [Issue_DateTime_Start],
	FORMAT([Issue_DateTimeStart], 'yyy-MM-dd') AS [Issue_Date_Start],
	FORMAT([Issue_DateTimeStart], 'HH:mm') AS [Issue_Time_Start],
	FORMAT([Issue_DateTimeEnd], 'yyy-MM-dd HH:mm') AS [Issue_DateTime_End],
	FORMAT([Issue_DateTimeEnd], 'yyy-MM-dd') AS [Issue_Date_End],
	FORMAT([Issue_DateTimeEnd], 'HH:mm') AS [Issue_Time_End],
	[t].[Assigned_ID],
	[a].[Code] AS [Assigned_Code],
	[a].[Name] AS [Assigned_Name],
	[a].[Description] AS [Assigned_Description],
	[t].[Issue_Reporter] AS [Issue_ReporterID],
	[tr].[Name] AS [Issue_Reporter],
	[t].[Author],
	--[t].[Created],
	FORMAT([t].[Created], 'dd.MM.yy') AS [Created],
	--[t].[Finished],
	FORMAT([t].[Finished], 'dd.MM.yy') AS [Finished]
FROM [Tasks] AS [t]
INNER JOIN [Tasks_Statuses] AS [ts] ON [ts].[ID] = [t].[Status_ID]
LEFT JOIN [Tasks_ObjectTypes] AS [ot] ON [ot].[ID] = [t].[Object_TypeID]
LEFT JOIN [Servers] AS [s] ON [s].[Guid] = [t].[Object_Guid]
INNER JOIN [Tasks_Severity] AS [tsv] ON [tsv].[ID] = [t].[Severity_ID]
LEFT JOIN [Tasks_Reporters] AS [tr] ON [tr].[ID] = [t].[Issue_Reporter]
LEFT JOIN [Tasks_Assigned] AS [a] ON [a].[ID] = [t].[Assigned_ID]
WHERE 1=1
	AND [t].[ID] = '$TaskID'
";
/*
SELECT
	[ID],
	[Status_ID],
	[Status_ChangeDate],
	[Severity_ID],
	[Assigned_ID],
	[Object_Guid],
	[Object_ID],
	[Object_TypeID],
	[Subject],
	[Desigion],
	[Result],
	[Issue_DateTimeStart],
	FORMAT([Issue_DateTimeStart], 'yyy-MM-dd') AS [Issue_Date_Start],
	FORMAT([Issue_DateTimeStart], 'HH:mm') AS [Issue_Time_Start],
	FORMAT([Issue_DateTimeStart], 'yyy-MM-dd HH:mm') AS [Issue_DateTime_Start],
	FORMAT([Issue_DateTimeEnd], 'yyy-MM-dd') AS [Issue_Date_End],
	FORMAT([Issue_DateTimeEnd], 'HH:mm') AS [Issue_Time_End],
	FORMAT([Issue_DateTimeEnd], 'yyy-MM-dd HH:mm') AS [Issue_DateTime_End],
	[Issue_DateTimeEnd],
	[Issue_Reporter] AS [Issue_ReporterID],
	[Author],
	[Created],
	[Finished]
FROM [Tasks]
WHERE 1=1

*/

//echo "<pre>$SQL</pre>";

//$SQL = ( strlen($BlockSerNo) > 4 ) ? str_replace("%%1%%", "AND [s].[BlockSerialNo] = '$BlockSerNo'", $SQL) : str_replace("%%1%%", "", $SQL);

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$data = Array();
//$TaskID_List = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

	$TaskID = $row["ID"];
	$StatusID = $row["Status_ID"];
	$StatusCode = $row["Status_Code"];
	$StatusName = iconv("Windows-1251", "UTF-8", $row["Status_Name"]);
	$StatusDescription = iconv("Windows-1251", "UTF-8", $row["Status_Description"]);
	$StatusChangeDate = $row["Status_ChangeDate"];

	$SeverityID = $row["Severity_ID"];
	$SeverityCode = $row["Severity_Code"];
	$SeverityName = iconv("Windows-1251", "UTF-8", $row["Severity_Name"]);

	$AssignedID = $row["Assigned_ID"];
	$AssignedCode = $row["Assigned_Code"];
	$AssignedName = iconv("Windows-1251", "UTF-8", $row["Assigned_Name"]);
	$AssignedDescription = iconv("Windows-1251", "UTF-8", $row["Assigned_Description"]);

	$ObjectGuid = $row["Object_Guid"];
	$ObjectID = $row["Object_ID"];
	$ObjectTypeID = $row["Object_TypeID"];
	$ObjectBlockSerialNo = iconv("Windows-1251", "UTF-8", $row["BlockSerialNo"]);
	$ObjectCode = $row["Object_Code"];
	$ObjectName = iconv("Windows-1251", "UTF-8", $row["Object_Name"]);

	$ComplainID = $row["Complain_ID"];

	$Subject = iconv("Windows-1251", "UTF-8", $row["Subject"]);
	$Desigion = iconv("Windows-1251", "UTF-8", $row["Desigion"]);
	$Result = iconv("Windows-1251", "UTF-8", $row["Result"]);

	$ReporterID = iconv("Windows-1251", "UTF-8", $row["Issue_ReporterID"]);
	$Reporter = iconv("Windows-1251", "UTF-8", $row["Issue_Reporter"]);
	$Author = iconv("Windows-1251", "UTF-8", $row["Author"]);

//	$IssueDateTime = $row["Issue_DateTimeStart"];

	$IssueDate = $row["Issue_Date_Start"];
	$IssueTime = $row["Issue_Time_Start"];
	$IssueDateTimeStart = $row["Issue_DateTime_Start"];
	$IssueDateEnd = $row["Issue_Date_End"];
	$IssueTimeEnd = $row["Issue_Time_End"];
	$IssueDateTimeEnd = $row["Issue_DateTime_End"];

	$IssueCreated = $row["Created"];
	$IssueFinished = $row["Finished"];
}


// echo "<pre>"; var_dump($data); echo "</pre>"; 

// Список Severity
$SQL = "
/****** Severeties list  ******/
SELECT [ID]
      ,[Code]
      ,[Name]
  FROM [Tasks_Severity]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data = Array();
$SeverityList = Array();
$SeverityListCode = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$SeverityList[$row["ID"]]= $row;
	$SeverityListCode[$row["Code"]]= $row;
}

//echo "<pre>";var_dump($SeverityList);echo "</pre>";

// Список Statuses
$SQL = "
/****** Statuses list  ******/
SELECT [ID]
      ,[Code]
      ,[Name]
      ,[Description]
  FROM [Tasks_Statuses]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data = Array();
$StatusesList = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$StatusesList[$row["ID"]]= $row;
	$StatusesListCode[$row["Code"]]= $row;
}

/***** Репортеры. От кого послупила жалоба изначально. *****/
$SQL = "
SELECT 
	[ID],
	[Code],
	[Name]
FROM [Tasks_Reporters]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data = Array();
$ReporterList = Array();
$ReporterListCode = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ReporterList[$row["ID"]]= $row;
	$ReporterListCode[$row["Code"]]= $row;
}
//echo "<pre>";var_dump($ReporterList);echo "</pre>";

$Reporter = iconv("Windows-1251", "UTF-8", @$ReporterList[$ReporterID]["Name"]);


/***** Блоки *****/
$SQL = "
SELECT 
	[s].[Guid] AS [ID],
	[s].[BlockSerialNo] AS [Name],
	[s].[BlockSerialNo] AS [Code]
--	[Is_Connected],
--	[IpAddress],
--	[ServerType_Guid],
--	[IpAddressInternal],
--	[OrderNo],
--	[ReportDateTime]
FROM [Servers] AS [s]
INNER JOIN [Server_Types] AS [st] ON [s].[ServerType_Guid] = [st].[Guid]
WHERE 
	--[s].[ServerType_Guid] IN ('F2585ED4-8D03-4E82-A895-3982E93B860C', 'F63848B9-F300-4955-ACCD-AFB8DC20DE6A')
	[st].[stShortName] IN ('olimex', 'linuxpad', 'win')
	AND [s].[Guid] NOT IN ('00000000-1001-0000-0000-000000000001')
ORDER BY
	[s].[BlockSerialNo] ASC 
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data = Array();
$ServersList = Array();
$ServersListCode = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersList[$row["ID"]]= $row;
	$ServersListCode[$row["Code"]]= $row;
}
//echo "<pre>";var_dump($ServersList);echo "</pre>";

//$ObjectCode = $ServersList[$ObjectGuid]["Code"];

/***** Типы Объектов. Блоки / машинисты / метки *****/
$SQL = "
SELECT 
	[ID],
	[Code],
	[Name]
FROM [Tasks_ObjectTypes]
ORDER BY [Name] ASC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data = Array();
$ObjectTypesList = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ObjectTypesList[$row["ID"]]= $row;
	$ObjectTypesListCode[$row["Code"]]= $row;
}

$ObjectTypeID = ( $TaskNew ) ? $ObjectTypesListCode[$TaskNewObjectType]["ID"] : @$ObjectTypeID;


/***** На кого назначена *****/
$SQL = "
/****** Assigned list  ******/
SELECT 
	[ID],
	[Code],
	[Name],
	[Description]
FROM [dbo].[Tasks_Assigned]
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data = Array();
$AssignedTypesList = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$AssignedTypesList[$row["ID"]]= $row;
	$AssignedTypesListCode[$row["Code"]]= $row;
}



// Список шаблонных фраз
$SQL = "
/****** Шаблоны фраз для тасок  ******/
SELECT
	[tt].[ID],
	[tt].[Group] AS [GroupID],
	[ttg].[Name] AS [Group_Name],
	[ttg].[Order] AS [Group_Order],
	[tt].[Name] AS [Template],
	[tt].[Order] AS [Template_Order]
FROM [Tasks_Templates] AS [tt]
INNER JOIN [Tasks_Templates_Groups] AS [ttg] ON [ttg].[ID] = [tt].[Group]
ORDER BY 
	[ttg].[Order] ASC, [tt].[Order] ASC

";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$TaskTemplates = Array();
$TaskTemplatesGroups = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$TaskTemplates[$row["GroupID"]][] = $row;
/*	if ( !in_array("", $TaskTemplatesGroups ) )
	{
		$TaskTemplatesGroups[] = $row;
	}
*/
}


if ( $Save )
{
//	echo "<pre>";var_dump($_GET);echo "</pre>";
	$newSeverity = str_remove_sql_char(substr(@$_GET["Severity"], 0, 20));
	$newSeverityID = @$SeverityListCode[$newSeverity]["ID"];
	$newStatus = str_remove_sql_char(substr(@$_GET["Status"], 0, 20));
	$newStatusID = @$StatusesListCode[$newStatus]["ID"];

	$newObjectType = str_remove_sql_char(substr(@$_GET["Object_Type"], 0, 20));
	$newObjectTypeID = @$ObjectTypesListCode[$newObjectType]["ID"];
	$newObject = str_remove_sql_char(substr(@$_GET["Object"], 0, 36));
	if ( in_array($newObjectTypeID, Array("1","4","5","6")) )
	{
		$newObjectID = $ServersListCode[$newObject]["ID"];
	}
	else 
	{
		$newObjectID = " #TODO ";  // TODO Другие. не блоки.               dd
		echo "<h1> TODO Изменение типа объекта на не вагон - НЕ НАПИСАНО</h1>";
		exit;
	}
//	$newObjectID = $ServersListCode[$newObject]["ID"];
 	$newAssigned = str_remove_sql_char(substr(@$_GET["Assigned"], 0, 20));
 	$newAssignedName = iconv("Windows-1251", "UTF-8", @$AssignedTypesListCode[$newAssigned]["Name"]);
// 	$newAssignedName = @$AssignedTypesListCode[$newAssigned]["Name"];
	$newAssignedID = @$AssignedTypesListCode[$newAssigned]["ID"];
 	$newComplainID = str_remove_sql_char(substr(@$_GET["Complain"], 0, 5));
 	$newSubject = str_remove_sql_char(substr(hex2bin(@$_GET["Subject"]), 0, 200));
	$newDesigion = str_remove_sql_char(substr(hex2bin(@$_GET["Desigion"]), 0, 200));
	$newResult = str_remove_sql_char(substr(hex2bin(@$_GET["Result"]), 0, 200));
	$newReporter = str_remove_sql_char(substr(@$_GET["Reporter"], 0, 20));
	$newReporterName = iconv("Windows-1251", "UTF-8", @$ReporterListCode[$newReporter]["Name"]);
	$newReporterID = @$ReporterListCode[$newReporter]["ID"];
	$newIssueDate = str_remove_sql_char(substr(@$_GET["Date"], 0, 10));
	$newIssueTime = str_remove_sql_char(substr(@$_GET["Time"], 0, 5));
	$newIssueDateTimeStart = "$newIssueDate $newIssueTime";
 	$newIssueDateEnd = str_remove_sql_char(substr(@$_GET["DateEnd"], 0, 10));
	$newIssueTimeEnd = str_remove_sql_char(substr(@$_GET["TimeEnd"], 0, 5));
	$newIssueDateTimeEnd = "$newIssueDateEnd $newIssueTimeEnd";
//	$ObjectGuid = str_remove_sql_char(substr(@$_GET["Object"], 0, 36));
	$newusername = str_remove_sql_char(substr(@$_GET["username"], 0, 20));

	// TODO Validate input variables
	// TODO	

	$Set_Vars = Array();
	$ChangeLog = Array();
	$TNewCols = Array();
	$TNewVals = Array();
	if ( $newSeverityID and (@$SeverityID != $newSeverityID)) 
	{ 
		$Set_Vars[] = "Severity_ID=".$newSeverityID;  
		$ChangeLog[] = "'Severity', '$newSeverity', " . ( ( @$SeverityCode ) ? "'".@$SeverityCode."'" : "NULL" );
		$TNewCols[] = "Severity_ID";
		$TNewVals[] = "'$newSeverityID'";
	};
	if ( $newStatusID and (@$StatusID != $newStatusID)) 
	{ 
		$StatusID = $StatusesListCode[$newStatus]["ID"];
		$Set_Vars[] = "Status_ID=" . $newStatusID; 
		$TNewCols[] = "Status_ID";
		$TNewVals[] = "'$newStatusID'";
		$Set_Vars[] = "Status_ChangeDate=sysutcdatetime()"; 
		$ChangeLog[] = "'Status', '$newStatus', " . ( ( @$StatusCode ) ? "'".@$StatusCode."'" : "NULL" );
//		$ChangeLog[] = "'Status_ChangeDate', sysutcdatetime()";
		if ( in_array($newStatusID, Array("3","4","5","6")) )
		{
			$Set_Vars[] = "Finished=sysutcdatetime()"; 
			$ChangeLog[] = "'Finished', '{currentdate}', " . ( ( @$IssueFinished ) ? "'".$IssueFinished."'" : "NULL" );
			$TNewCols[] = "Finished";
			$TNewVals[] = "sysutcdatetime()";
		}
		else
		{
			// TODO не писать нулл если меняется с неконечного на неконечный.
			if ( in_array($StatusID, Array("1","2")) )
			{
				$Set_Vars[] = "Finished=NULL"; 
				$ChangeLog[] = "'Finished', NULL, '$IssueFinished'";
			}
		}
	};
	if ( $newAssignedID and (@$AssignedID != $newAssignedID) ) 
	{ 
		$Set_Vars[] = "Assigned_ID='$newAssignedID'"; 
		$ChangeLog[] = "'Assigned', '$newAssignedName', " . ( ( @$AssignedName ) ? "'".@$AssignedName."'" : "NULL" );
		$TNewCols[] = "Assigned_ID";
		$TNewVals[] = "'$newAssignedID'";
	};

	if ( $newObjectID and (@$ObjectGuid != $newObjectID) ) 
	{ 
		$Set_Vars[] = "Object_Guid='$newObjectID'"; 
		
		$ObjectNameOldX = "";
		$ObjectNameOldX = ( @$ObjectBlockSerialNo ) ? @$ObjectBlockSerialNo : $ObjectNameOldX;
//		$ObjectNameOldX = ( @$ObjectBlockSerialNo ) ? @$ObjectBlockSerialNo : $ObjectNameOldX; // TODO Другие типы объектов
		$ChangeLog[] = "'Object', '$newObject', '$ObjectNameOldX'";
		$TNewCols[] = "Object_Guid";
		$TNewVals[] = "'$newObjectID'";
	};
	if ( $newObjectTypeID and (@$ObjectTypeID != $newObjectTypeID) ) 
	{ 
		$Set_Vars[] = "Object_TypeID=".$newObjectTypeID; 
		$ChangeLog[] = "'Object_Type', '$newObjectType', " . ( ( @$ObjectCode ) ? "'".@$ObjectCode."'" : "NULL" );
		$TNewCols[] = "Object_TypeID";
		$TNewVals[] = "'$newObjectTypeID'";
	};
	if ( $newComplainID and (@$ComplainID != $newComplainID) ) 
	{ 
		$Set_Vars[] = "Complain_ID='$newComplainID'"; 
		$ChangeLog[] = "'Complain_ID', '$newComplainID', " . ( ( @$ComplainID ) ? "'".@$ComplainID."'" : "NULL" );
		$TNewCols[] = "Complain_ID";
		$TNewVals[] = "'$newComplainID'";
	};
	if ( $newSubject and (@$Subject != $newSubject) ) 
	{ 
		$Set_Vars[] = "Subject='$newSubject'"; 
		$ChangeLog[] = "'Subject', '$newSubject', " . ( ( @$Subject ) ? "'".@$Subject."'" : "NULL" );
		$TNewCols[] = "Subject";
		$TNewVals[] = "'$newSubject'";
	};
	if ( $newDesigion and (@$Desigion != $newDesigion)) 
	{ 
		$Set_Vars[] = "Desigion='$newDesigion'"; 
		$ChangeLog[] = "'Desigion', '$newDesigion', " . ( ( @$Desigion ) ? "'".@$Desigion."'" : "NULL" );
		$TNewCols[] = "Desigion";
		$TNewVals[] = "'$newDesigion'";
	};
	if ( $newResult and (@$Result != $newResult) ) 
	{ 
		$Set_Vars[] = "Result='$newResult'"; 
		$ChangeLog[] = "'Result', '$newResult', " . ( ( @$Result ) ? "'".@$Result."'" : "NULL" );
		$TNewCols[] = "Result";
		$TNewVals[] = "'$newResult'";
	};
	if ( $newReporterID and (@$ReporterID != $newReporterID) ) 
	{ 
		$Set_Vars[] = "Issue_Reporter=".$newReporterID; 
		$ChangeLog[] = "'Issue_Reporter', '$newReporterName', " . ( ( @$Reporter ) ? "'".@$Reporter."'" : "NULL" );
		$TNewCols[] = "Issue_Reporter";
		$TNewVals[] = "'$newReporterID'";
	};
	if ( $newIssueDateTimeStart and ( @$IssueDate != $newIssueDate or @$IssueTime != $newIssueTime ) ) 
	{ 
		$Set_Vars[] = "Issue_DateTimeStart='$newIssueDateTimeStart'"; 
		$ChangeLog[] = "'Issue_DateTimeStart', '$newIssueDateTimeStart', " . ( ( @$IssueDateTimeStart ) ? "'".@$IssueDateTimeStart."'" : "NULL" );
		$TNewCols[] = "Issue_DateTimeStart";
		$TNewVals[] = "'".trim($newIssueDateTimeStart)."'";
	};
	if ( trim($newIssueDateTimeEnd) and (@$IssueDateEnd != $newIssueDateEnd or @$IssueTimeEnd != $newIssueTimeEnd ) ) 
	{ 
		$Set_Vars[] = "Issue_DateTimeEnd='$newIssueDateTimeEnd'"; 
		$ChangeLog[] = "'Issue_DateTimeEnd', '$newIssueDateTimeEnd', " . ( ( @$IssueDateTimeEnd ) ? "'".@$IssueDateTimeEnd."'" : "NULL" );
		$TNewCols[] = "Issue_DateTimeEnd";
		$TNewVals[] = "'$newIssueDateTimeEnd'";
	};
	if ( $newusername and count($Set_Vars) ) 
	{ 
		$Set_Vars[] = "Editor='$newusername'"; 
		$Set_Vars[] = "ChangedDate=sysutcdatetime()"; 
		$TNewCols[] = "Author";
		$TNewVals[] = "'$newusername'";
//		$ChangeLog[] = "'Editor', '$newusername'";
	};

	// TODO если таска новая ---- Author


	//echo "<pre>";var_dump($Set_Vars);echo "</pre>";
	//echo "<pre>";var_dump($ChangeLog);echo "</pre>";

	if ( $TaskID and count($Set_Vars))
	{
		$SQL = "UPDATE [Tasks] SET
			". implode(",\n	", $Set_Vars) . "
			WHERE [ID] = '$TaskID';
		";
	}
	elseif ( ! $TaskID )
	{
		$SQL = "INSERT INTO [dbo].[Tasks]
	           ([".implode("],[", $TNewCols)."])
		     VALUES
			(".implode(",", $TNewVals).")
		";
	}
//	echo "<pre>$SQL</pre>";

    $SQL = iconv("UTF-8", "Windows-1251", $SQL);

	MSSQLsiletnQuery($SQL);


	if ( $TaskID and count($Set_Vars) )
	{
		// Таблица логгирования изменений в тасках
		$SQL = "
INSERT INTO [Tasks_ChangeLog]
	([TaskID], [Author], [PropertyName], [Value], [ValueOld])
VALUES
		($TaskID,'$newusername',". implode("),\n		($TaskID,'$newusername',", $ChangeLog) . ")
		";
//		echo "<pre>$SQL</pre>";

	        $SQL = iconv("UTF-8", "Windows-1251", $SQL);
	
		MSSQLsiletnQuery($SQL);
	}

	UpdateTaskShangedDate($TaskID); // Обновление даты редактироания таски

	sqlsrv_close($conn) ;

//exit;
	//
	// Start output of page
	//
	define('SHOW_ONLINE', true);
	$template->set_filenames(array(
		'body' => 'saved.tpl')
	);

	$template->assign_vars(array(
		'RANDOM' => $rnd,
	));

	// TODO что то сделать туда надо. больно уж завязано только на блоки
	if ( @$ObjectGuid )
	{
		$ObjectCode = @$ServersList[$ObjectGuid]["Code"];
	}
	elseif ( $newObjectID )
	{
		$ObjectCode = @$ServersList[$newObjectID]["Code"];
	}
	else 
	{
		$ObjectCode = "";
	}

	$template->assign_block_vars('show_content', array(
		'PAGE_NAME' => "Tasks",
		'PARAMS' => "&prms=ObjectId=".$ObjectCode.";ObjectType=Block",
		'DIV_ID' => "Tasks_".$ObjectCode,
	));


	$template->pparse('body');

	exit;
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
	'body' => 'tasks_edit.tpl')
);

/*
$Subject
*/

//$TaskID

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'TASK_ID' => $TaskID,
	'SUBJECT' => @$Subject,
	'DESIGION' => @$Desigion,
	'RESULT' => @$Result,
	'ISSUE_DATE' => @$IssueDate,
	'ISSUE_TIME' => @$IssueTime,
	'ISSUE_DATE_END' => @$IssueDateEnd,
	'ISSUE_TIME_END' => @$IssueTimeEnd,
	));

// Severity DDL
//$i = 0;
foreach ( $SeverityList AS $Severity )
{
        $SevID = $Severity["ID"];
        $SevCode = $Severity["Code"];
        $SevName = iconv("Windows-1251", "UTF-8", $Severity["Name"]);
//        $SeverityDescription = @$SeverityList[$i][""];
	$Selected = ( $SevID == @$SeverityID or ( $TaskNew and $SevID == 3 ) ) ? "selected" : "";
		
	$template->assign_block_vars('row_severity', array(
		'ID' => $SevID,
		'CODE' => $SevCode,
		'NAME' => $SevName,
//		'SEVERITY_DESCRIPTION' => $SeverityDescription,
		'SELECTED' => $Selected,
	));
//	$i++;
}

// Statuses DDL
//$i = 0;
foreach ( @$StatusesList AS $Status )
//while (	@$StatusesList[$i] )
{
        $StatID = $Status["ID"];
        $StatCode = $Status["Code"];
        $StatName = iconv("Windows-1251", "UTF-8", $Status["Name"]);
        $StatDescription = $Status["Description"];
	$Selected = ( $StatID == @$StatusID or ( $TaskNew and $StatID == 1 ) ) ? "selected" : "";
		
	$template->assign_block_vars('row_status', array(
		'ID' => $StatID,
		'CODE' => $StatCode,
		'NAME' => $StatName,
		'DESCRIPTION' => $StatDescription,
		'SELECTED' => $Selected,
	));
//	$i++;
}

foreach ( $AssignedTypesList AS $item )
//while (	@$StatusesList[$i] )
{
        $ID = $item["ID"];
        $Code = $item["Code"];
        $Name = iconv("Windows-1251", "UTF-8", $item["Name"]);
        $Description = iconv("Windows-1251", "UTF-8", $item["Description"]);
	$Selected = ( $ID == @$AssignedID or ( $TaskNew and $ID == 1 ) ) ? "selected" : "";
		
	$template->assign_block_vars('row_assigned', array(
		'ID' => $ID,
		'CODE' => $Code,
		'NAME' => $Name,
		'DESCRIPTION' => $Description,
		'SELECTED' => $Selected,
	));
//	$i++;
}


// Шаблонны заполнения полей
//echo "<pre>";var_dump($TaskTemplates[6]);echo "</pre>";
foreach ( $TaskTemplates[6] AS $item )
{
    $ID = $item["ID"];
    $Name = iconv("Windows-1251", "UTF-8", $item["Template"]);
    //$Description = iconv("Windows-1251", "UTF-8", $item["Description"]);
	//$Selected = ( $ID == @$ComplainID or ( $TaskNew and $ID == 1 ) ) ? "selected" : "";
	$Selected = ( $ID == @$ComplainID ) ? "selected" : "";
	//echo "$ID=$ComplainID-";
		
	$template->assign_block_vars('template_complain', array(
		'ID' => $ID,
		//'CODE' => $Code,
		'NAME' => $Name,
		//'DESCRIPTION' => $Description,
		'SELECTED' => $Selected,
	));
//	$i++;
}


//echo $ReporterID;

// Репортеры DDL
foreach ( @$ReporterList AS $row )
//while (	@$ReporterList[$i] )
{
        $ID = $row["ID"];
        $Code = $row["Code"];
        $Name = iconv("Windows-1251", "UTF-8", $row["Name"]);
//        $StatDescription = @$StatusesList[$i][""];
	$Selected = ( $ID == @$ReporterID ) ? "selected" : "";
		
	$template->assign_block_vars('row_reporters', array(
		'ID' => $ID,
		'CODE' => $Code,
		'NAME' => $Name,
//		'DESCRIPTION' => $StatDescription,
		'SELECTED' => $Selected,
	));
//	$i++;
}

// Блоки DDL
//$i = 0;
//echo "ffff".$ObjectTypeID;
if ( in_array(@$ObjectTypeID, Array("1","4","5","6")) )
{
	$ObjectList = $ServersList;
}
elseif ( in_array(@$ObjectTypeID, Array("2","3")) )
{
	$ObjectList = Array();
}
else
{
	$ObjectList = Array(0 => Array("ID" => "UNCKNOWN", "Name" => "UNCKNOWN"));
}
//echo "<pre>";var_dump($ObjectList);echo "</pre>";

//echo $ObjectGuid;

foreach ( @$ObjectList AS $row )
{
        $ID = $row["ID"];
        $Code = @$row["Code"];
//        $SrvCode = $Server["BlockSerialNo"];
        $Name = iconv("Windows-1251", "UTF-8", $row["Name"]);
//        $StatDescription = @$StatusesList[$i][""];
/*	$Selected = "";
	if ( $TaskNewObjectId == $Code or $ID == @$ObjectGuid )
	{
		$Selected = "selected";
		
	}
	elseif ( $ID == @$ObjectGuid )
	{
		$Selected = ( $ID == @$ObjectGuid ) ? "selected" : "";
	}
*/
	$Selected = ( $TaskNewObjectId == $Code or $ID == @$ObjectGuid ) ? "selected" : "";
		
	$template->assign_block_vars('row_servers', array(
		'ID' => $ID,
		'CODE' => $Code,
		'NAME' => $Name, // ."-".$ID."-". $ObjectGuid,
//		'DESCRIPTION' => $StatDescription,
		'SELECTED' => $Selected,
	));
//	$i++;
}


foreach ( $ObjectTypesList AS $row )
{
        $ID = $row["ID"];
        $Code = $row["Code"];
        $Name = iconv("Windows-1251", "UTF-8", $row["Name"]);
//        $StatDescription = @$StatusesList[$i][""];
	$Selected = ( $ID == @$ObjectTypeID ) ? "selected" : "";
		
	$template->assign_block_vars('row_object_type', array(
		'ID' => $ID,
		'CODE' => $Code,
		'NAME' => $Name,
//		'DESCRIPTION' => $StatDescription,
		'SELECTED' => $Selected,
	));
}

//	$StatusesList[]= $row;


/*
$TaskID = str_remove_sql_char(substr(@$_GET["TaskID"], 0, 20));
$Severity = str_remove_sql_char(substr(@$_GET["Severity"], 0, 500));
$Status = str_remove_sql_char(substr(@$_GET["Status"], 0, 500));
$BlockSerNo = str_remove_sql_char(substr(@$_GET["BlockSerNo"], 0, 500));
$Reporter = str_remove_sql_char(substr(@$_GET["Reporter"], 0, 500));
*/
/*
	$template->assign_block_vars('row', array(
		'L_LINK' => $l_Link,
	));

*/

$template->pparse('body');
?>