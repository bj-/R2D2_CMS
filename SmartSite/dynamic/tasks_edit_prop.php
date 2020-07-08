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

//echo "sfsdfsfsdf";
// Get params from url
$TaskID = str_remove_sql_char(substr(@$_GET["TaskID"], 0, 20));
$PropID = str_remove_sql_char(substr(@$_GET["PropID"], 0, 20));
$Action = strtoupper(str_remove_sql_char(substr(@$_GET["Action"], 0, 20)));
$New = ( !$PropID ) ? TRUE : FALSE;
$Edit= ( $PropID ) ? TRUE : FALSE;
$Save = ( @$_GET["Save"] ) ? TRUE : FALSE;
$Delete = ( $Action == "DELETE" ) ? TRUE : FALSE;

$List = FALSE; // List table of properties

$rnd = rand ( 0 , 1000000000 );

$currentdate = date("Y-m-d", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));


$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

// Детализация по таскам
$SQL = "
/****** Task Properties List  ******/
SELECT 
	[tf].[ID],
	[tf].[Task_ID],
	[tf].[Field_ID],
--	[tfl].[ID] AS [Field_ID],
	[tfl].[Group_ID] AS [Field_Group_ID],
	[tfl].[Code] AS [Field_Code],
	[tfl].[Name] AS [Field_Name],
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
WHERE [tf].[Task_ID] = '$TaskID'
ORDER BY [tf].[ID] ASC, [tfl].[Group_ID] ASC, [tfl].[Code] ASC
";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data = Array();
$TaskProp = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$TaskProp[$row["Task_ID"]][$row["ID"]]= $row;
}
// echo "<pre>"; var_dump($TaskProp); echo "</pre>"; 

//	$Subject = iconv("Windows-1251", "UTF-8", $row["Subject"]);


// Список Пропертей
$SQL = "
/****** Task Property list  ******/
SELECT
	[tfl].[ID],
	[tfl].[Group_ID],
	[tfg].[Code]        AS [Group_Code],
	[tfg].[Name]        AS [Group_Name],
	[tfg].[Description] AS [Group_Description],
--	[tfg].[Order]       AS [Group_Order],
	[tfl].[Code],
	[tfl].[Name],
	[tfl].[Description]
--	[tfl].[Order]
FROM [Tasks_Fields_List] AS [tfl]
INNER JOIN [Tasks_Fields_Groups] AS [tfg] ON [tfg].[ID] = [tfl].[Group_ID]
ORDER BY [tfg].[Order] ASC, [tfl].[Order] ASC, [tfl].[Code] ASC
";
/*
--/****** Task Property list  ******
SELECT
	[ID],
	[Group_ID],
	[Code],
	[Name],
	[Description],
	[Order]
FROM [Tasks_Fields_List]
ORDER BY 
	[Group_ID] ASC,
	[Order] ASC,
	[Code] ASC
*/

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data = Array();
$TasksFieldsList = Array();
$TasksFieldsListCode = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$TasksFieldsList[$row["ID"]]= $row;
	$TasksFieldsListCode[$row["Code"]]= $row;
}
//echo "<pre>";var_dump($TasksFieldsList);echo "</pre>";

if ( $Save )
{

	// /dynamic/proxy.php?page=TasksPropEdit
	// &prms=Save=true;List=true;TaskID=2;PropID=1;Editor=bj;PropCode=DriverFIO;PropValue=d092d0bed0b4d0bed0bad0b0d187d0bad0b8d0bd20d0a12ed0922e

	$oldCode = @$TaskProp[$TaskID][$PropID]["Field_Code"];
	$oldValue = iconv("Windows-1251", "UTF-8", @$TaskProp[$TaskID][$PropID]["Value"]);

	$Code = str_remove_sql_char(substr(@$_GET["PropCode"], 0, 100));
	$FieldCodeID = $TasksFieldsListCode[$Code]["ID"];
	$Value = str_remove_sql_char(hex2bin(substr(@$_GET["PropValue"], 0, 2000)));

	$Author = str_remove_sql_char(substr(@$_GET["Editor"], 0, 20));

	$oldDeleted = ( @$TaskProp[$TaskID][$PropID]["Deleted"] ) ? TRUE : FALSE;
	$oldDeletedS = ( $oldDeleted ) ? "Deleted" : "Active";


	//echo "TaskID : [$TaskID]<br>PropID : [$PropID]<br>Code : [$Code] ($FieldCodeID) <br>Code old : [$oldCode]<br>Value : [$Value]<br>Value old : [$oldValue]<br>Author : [$Author]<br>";

	$Set_Vars = Array();
	$ChangeLog = Array();
	$TNewCols = Array();
	$TNewVals = Array();

	if ( $Code and ($Code != $oldCode)) 
	{ 
		$Set_Vars[] = "[Field_ID]=".$FieldCodeID;
		$ChangeLog[] = "'Field Code for FieldID: [$PropID]', '$Code', '$oldCode'";
		$TNewCols[] = "Field_ID";
		$TNewVals[] = "'$FieldCodeID'";
	};

	if ( $Value and ($Value != $oldValue)) 
	{ 
		$Set_Vars[] = "[Value]='".$Value."'";  
		$ChangeLog[] = "'Field Value for FieldID: [$PropID] ($Code)', '$Value', '$oldValue'";
		$TNewCols[] = "Value";
		$TNewVals[] = "'$Value'";
	};

	if ( $oldDeleted ) 
	{ 
		$Set_Vars[] = "[Deleted]=NULL";  
		$ChangeLog[] = "'Property: [$PropID], Code: [$Code]', 'Active', '$oldDeletedS'";
	};

	if ( count($Set_Vars) )
	{
		$Set_Vars[] = "[LastEdit]=sysutcdatetime()";  
		$Set_Vars[] = "[Editor]='".$Author."'";  
//		$ChangeLog[] = "'Field Value for FieldID: [$PropID] ($Code)', '$Value', '$oldValue'";

		$TNewCols[] = "Editor";
		$TNewVals[] = "'$Author'";
		$TNewCols[] = "Task_ID";
		$TNewVals[] = "'$TaskID'";
	
	}


	$SQL = "";
	if ( $Edit and count($Set_Vars) )
	{

		// Таблица логгирования изменений в тасках
		$SQL = "INSERT INTO [Tasks_ChangeLog]
			([TaskID], [Author], [PropertyName], [Value], [ValueOld])
			VALUES
			($TaskID,'$Author',". implode("),\n	($TaskID,'$Author',", $ChangeLog) . ")
			";
//		echo "<pre>$SQL</pre>";
	        $SQL = iconv("UTF-8", "Windows-1251", $SQL);

	
		MSSQLsiletnQuery($SQL);


		$SQL = "UPDATE [Tasks_Fields]
		SET
		". implode(",\n	", $Set_Vars) . "
		WHERE [ID] = '$PropID';
		";

	}
	elseif ( $New )
	{
		$SQL = "INSERT INTO [dbo].[Tasks_Fields]
	           ([".implode("],[", $TNewCols)."])
	     VALUES
		(".implode(",", $TNewVals).")
		";

	}
//	echo "<pre>$SQL</pre>";

        $SQL = iconv("UTF-8", "Windows-1251", $SQL);

	MSSQLsiletnQuery($SQL);


	UpdateTaskShangedDate($TaskID); // Обновление даты редактироания таски

	$List = TRUE;
}
elseif ( $Delete and $TaskID and $PropID )
{

	$oldCode = @$TaskProp[$TaskID][$PropID]["Field_Code"];

	$Author = str_remove_sql_char(substr(@$_GET["Editor"], 0, 20));

	$oldDeleted = ( @$TaskProp[$TaskID][$PropID]["Deleted"] == 1 ) ? TRUE : FALSE;
	$oldDeletedS = ( $oldDeleted ) ? "Deleted" : "Active";


	//echo "TaskID : [$TaskID]<br>PropID : [$PropID]<br>Code : [$Code] ($FieldCodeID) <br>Code old : [$oldCode]<br>Value : [$Value]<br>Value old : [$oldValue]<br>Author : [$Author]<br>";

	$Set_Vars = Array();
	$ChangeLog = Array();

	if ( $Delete and ( !$oldDeleted)) 
	{ 
		$Set_Vars[] = "[Deleted]=1";
		$ChangeLog[] = "'Property: [$PropID], Code: [$oldCode]', 'Deleted', '$oldDeletedS'";
	};

	if ( count($Set_Vars) )
	{
		$Set_Vars[] = "[LastEdit]=sysutcdatetime()";  
		$Set_Vars[] = "[Editor]='".$Author."'";  
	}



	if ( count($Set_Vars) )
	{

		// Таблица логгирования изменений в тасках
		$SQL = "INSERT INTO [Tasks_ChangeLog]
			([TaskID], [Author], [PropertyName], [Value], [ValueOld])
			VALUES
			($TaskID,'$Author',". implode("),\n	($TaskID,'$Author',", $ChangeLog) . ")
			";
//			echo "<pre>$SQL</pre>";
		$SQL = iconv("UTF-8", "Windows-1251", $SQL);
	
	
		MSSQLsiletnQuery($SQL);


		$SQL = "UPDATE [Tasks_Fields]
			SET
			". implode(",\n	", $Set_Vars) . "
			WHERE [ID] = '$PropID';
			";
//			echo "<pre>$SQL</pre>";
		MSSQLsiletnQuery($SQL);

		UpdateTaskShangedDate($TaskID); // Обновление даты редактироания таски
	
	}

	$List = TRUE;
}
elseif ( $Delete or $Save )
{
	$List = TRUE;
}


if ( $List ) // List table of properties
{
//	echo "List Prop";
	// Show task's Fields List

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
/*
-- /****** Task Properties List  ******
SELECT 
	[tf].[ID],
	[tf].[Task_ID],
	[tf].[Field_ID],
--	[tfl].[ID] AS [Field_ID],
	[tfl].[Group_ID] AS [Field_Group_ID],
	[tfl].[Code] AS [Field_Code],
	[tfl].[Name] AS [Field_Name],
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
WHERE [tf].[Task_ID] = '$TaskID'
ORDER BY
	[tf].[Deleted] ASC,
	[tf].[ID] ASC, [tfl].[Group_ID] ASC, [tfl].[Code] ASC

*/

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	//$data = Array();
	$TaskProp = Array();
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//		$TaskProp[$row["Task_ID"]][$row["ID"]]= $row;
		$TaskProp[$row["Task_ID"]][]= $row;
	}
	//echo "<pre>"; var_dump($TaskProp); echo "</pre>"; 

//	$Subject = iconv("Windows-1251", "UTF-8", $row["Subject"]);



	sqlsrv_close($conn) ;

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

	$template->assign_block_vars('row', array(
		'TASK_ID' => $TaskID,
	));
	$template->assign_block_vars('row.task_details', array(
	));
//	foreach ( $TaskProp AS $TaskProperty )

	$TaskProp_Deleted_Curr = FALSE; // Объединение удаленных пропертей в одну группу
	$d = 0;
	while ( @$TaskProp[$TaskID][$d] )
	{
/*
		$TaskProp_ID = $TaskProp[$TaskID][$d]["ID"];
		$TaskProp_Task_ID = $TaskProp[$TaskID][$d]["Task_ID"];
		$TaskProp_Field_ID = "";
		$TaskProp_Field_Group_ID = "";
		$TaskProp_Field_Code = "";
		$TaskProp_Field_Name = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Field_Name"]);
		$TaskProp_Field_Description = "";
		$TaskProp_Value = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Value"]);
		$TaskProp_Created = $TaskProp[$TaskID][$d]["Created"];
		$TaskProp_LastEdit = $TaskProp[$TaskID][$d]["LastEdit"];
		$TaskProp_Editor = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Editor"]);
		$TaskProp_Deleted = ( $TaskProp[$TaskID][$d]["Deleted"] ) ? TRUE : FALSE;

		$StyleBgRow = ( $d % 2 == 0 ) ? "" : $style["bg-beige"];
		$StyleBgRow = ( $TaskProp_Deleted ) ? "color:gray;text-decoration:line-through;" : $StyleBgRow;
*/
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
		$TaskProp_Created = $TaskProp[$TaskID][$d]["Created"];
		$TaskProp_LastEdit = $TaskProp[$TaskID][$d]["LastEdit"];
		$TaskProp_Editor = iconv("Windows-1251", "UTF-8", $TaskProp[$TaskID][$d]["Editor"]);
		$TaskProp_Deleted = ( $TaskProp[$TaskID][$d]["Deleted"] ) ? TRUE : FALSE;

		$StyleBgRow = ( $d % 2 == 0 ) ? "" : $style["bg-beige"];
		$StyleBgRow = ( $TaskProp_Deleted ) ? "color:gray;text-decoration:line-through;" : $StyleBgRow;


//	$oldValue = iconv("Windows-1251", "UTF-8", @$TaskProp[$TaskID][$PropID]["Value"]);

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
		$template->assign_block_vars('row.task_details.row_details', array(
			'ID' => $TaskProp_ID,
			'TASK_ID' => $TaskProp_Task_ID,
			'FIELD_ID' => $TaskProp_Field_ID,
			'GROUP_ID' => $TaskProp_Field_Group_ID,
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
*/
		$d++;
	}

//		$TaskProp[$row["Task_ID"]][$row["ID"]]= $row;

	// TODO А сделать показ пропертей.
}
else // Edit Form
{

	sqlsrv_close($conn) ;

	//
	// Start output of page
	//
	define('SHOW_ONLINE', true);
	$page_title = "Title";
	$page_text = "";
	
	$template->set_filenames(array(
		'body' => 'tasks_edit_prop.tpl')
	);


	$PropertyValue = iconv("Windows-1251", "UTF-8", @$TaskProp[$TaskID][$PropID]["Value"]);
	$Field_ID = @$TaskProp[$TaskID][$PropID]["ID"];

	$template->assign_vars(array(
		'TITLE' => $page_title,
		'ARTICLE' => $page_text,
		'RANDOM' => $rnd,
		'CURRENTDATE' => $currentdate,
		'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
		'TASK_ID' => $TaskID,
		'PROP_VALUE' => $PropertyValue,
		'TASK_PROP_ID' => $Field_ID,
		));
	
// Task fileds list
	foreach ( $TasksFieldsList AS $row )
	{
	        $ID = $row["ID"];
	        $GroupID = $row["Group_ID"];

		$Group_Code = $row["Group_Code"];
	        $Group_Name = iconv("Windows-1251", "UTF-8", $row["Group_Name"]);
	        $Group_Description = iconv("Windows-1251", "UTF-8", $row["Group_Description"]);

	        $Code = $row["Code"];
	        $Name = iconv("Windows-1251", "UTF-8", $row["Name"]);
	        $Description = iconv("Windows-1251", "UTF-8", $row["Description"]);
//	        $Order = $row["Order"];
	
		$Selected = ( $Code == @$TaskProp[$TaskID][$PropID]["Field_Code"] ) ? "selected" : "";


		$template->assign_block_vars('listitem', array(
		));

		if ( $GroupID != @$GroupID_Last )
		{
			$template->assign_block_vars('listitem.group', array(
				'CODE' => "0", //$Group_Code,
				'NAME' => $Group_Name,
				'DESCRIPTION' => $Group_Description,
			));
			$GroupID_Last = $GroupID;
		}
		$template->assign_block_vars('listitem.property', array(
			'ID' => $ID,
			'CODE' => $Code,
			'NAME' => $Name,
			'DESCRIPTION' => $Name,
			'SELECTED' => $Selected,
		));

/*
			
		$template->assign_block_vars('row_property', array(
			'ID' => $ID,
			'CODE' => $Code,
			'NAME' => $Name,
			'DESCRIPTION' => $Name,
			'SELECTED' => $Selected,
		));
*/
	}

}

$template->pparse('body');

?>