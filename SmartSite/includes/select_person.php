<?php
/***************************************************************************
 *                                select_person.php
 *								  			build menu
 *                            -------------------
 *   begin                : Saturday, Jul 13, 2010
 *   copyright            : (C) 2001 The R2D2 Group
 *
 *   $Id: msend.php,v 1.99.2.1 2002/12/19 17:17:40 psotfx Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 * 
 *
 ***************************************************************************/

 
//
//отсылка почты из формы
//

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}
//echo "<pre>"; print_r($topmenu_data); echo "</pre>";
//echo "safdsafsaf";
function select_driver( $type, $valueType ) 
{
	/*
		$type		= тип (по какому полю соритруется FIO или SEN (номеру сенсора)
		$valueType	= Что будет указано в качестве Value - GUID | NAME (Фио с носером сенсоар версией прошики и зарядом)
		
	*/
	global $template, $conn;
//	global $topmenu_pids, $topmenu_data, $url_lang;

	$ret = "";

//	echo "ssdfsdfs";
//	echo '<pre>'; print_r($topmenu_pids); echo '</pre>';

	$template->set_filenames(array(
		'select' => 'incl/select.tpl')
	);

	

	$SQL_QUERY["List"] = "
		SELECT 
			[p].[Last_Name],
			[p].[First_Name],
			[p].[Middle_Name],
			[sn].[SerialNo] AS [SensorSerialNo],
			[u].[Guid],
			[sn].[FW_Version],
			[sn].[Battery_Level],
		--	[u].[Vehicles_Guid],
		--	[u].[Users_Roles_Guid],
			[ur].[Name] AS [UserRole]
		--	[u].[Is_Active],
		--	[v].[Name] AS [Wagon],
		--	[s].[Alias] AS [BlockSerialNo]
		FROM [Users] AS [u]
		INNER JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
		--LEFT JOIN [Vehicles] AS [v] ON [v].[Guid] = [u].[Vehicles_Guid]
		LEFT JOIN [Sensors_Cardio] AS [sc] ON [sc].[Users_Guid] = [u].[Guid]
		LEFT JOIN [Sensors] AS [sn] ON [sn].[Guid] = [sc].[Guid]
		--LEFT JOIN [Servers] AS [s] ON [s].[Guid] = [sc].Servers_Guid
		LEFT JOIN [Users_Roles] AS [ur] ON [ur].[Guid] = [u].[Users_Roles_Guid]
		WHERE 
			[ur].[Name] = 'Машинист'
	";

	if ( $type == "FIO" )
	{
		$SQL = $SQL_QUERY["List"] . "
			ORDER BY 
			[p].[Last_Name],
			[p].[First_Name],
			[p].[Middle_Name]
		";
	}
	elseif ( $type == "SEN" )
	{
		$SQL = $SQL_QUERY["List"] . "
		ORDER BY 
			[sn].[SerialNo] ASC
		";
		
	}
	
	$SQL = utf2win($SQL);

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	$data = Array();
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$data[]= $row;
	}

	foreach ( $data as $item)
	{
		$Last_Name = win2utf($item["Last_Name"]);
		$First_Name = win2utf($item["First_Name"]);
		$Middle_Name = win2utf($item["Middle_Name"]);

		$SensorSerialNo = win2utf($item["SensorSerialNo"]);
		$FW_Version = win2utf($item["FW_Version"]);
		$FW_VersionStr = ( $FW_Version ) ? "; FW: $FW_Version" : "";
		$Battery_Level = win2utf($item["Battery_Level"]);
		$Battery_LevelStr = ( $Battery_Level ) ? "; Bat: $Battery_Level%" : "";
		
//		$SenStr = ( $SensorSerialNo ) ? " ( $SensorSerialNo $FW_VersionStr $Battery_LevelStr )" : "";
		$SenStr = ( $SensorSerialNo ) ? " ( $SensorSerialNo )" : "";
		
		$Guid = $item["Guid"];

		if ( $valueType == "GUID" )
		{
			$value = $Guid;
		}
		elseif ( $valueType == "NAME" )
		{
			$value = "$Last_Name $First_Name $Middle_Name $SenStr";
		}
		else { $value = $Guid; }
		
		$template->assign_block_vars('row',array());

		$template->assign_block_vars('row.item',array(
			'NAME' => "$Last_Name $First_Name $Middle_Name $SenStr",
			'VALUE' => $value,
//			'NAME' => $Last_Name . " " . $First_Name . " " . $Middle_Name . " (".$SensorSerialNo.")",
			'INDENT' => "", // " &nbsp; &nbsp; " // Отступ в листе
		));
	}

	if ( $type == "FIO" )
	{
		$template->assign_var_from_handle('SELECT_DRIVER_BY_FIO', 'select');
	}
	elseif ( $type == "SEN" )
	{
		$template->assign_var_from_handle('SELECT_DRIVER_BY_SEN', 'select');
		
	}
	
	return $ret;

};
?>




<?php

/*
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

//=================================  LIST  =====================

$SQL = $SQL_QUERY["Persons"] . "
ORDER BY
	[p].[Last_Name] ASC,
	[p].[First_Name] ASC,
	[p].[Middle_Name] ASC

";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

$maxFW = 0;

$data = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
	$maxFW = ( $maxFW < $row["HID_FW"] ) ? $row["HID_FW"] : $maxFW;
}

//var_dump($dataSen);

sqlsrv_close($conn) ;

*/
?>