<?php
/***************************************************************************
 *                                blockservicesworkingtimehistory.php
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

$BlockSerNo = str_remove_sql_char(substr(@$_GET["BlockSerNo"], 0, 20));
//$Properties = str_remove_sql_char(substr(@$_GET["property"], 0, 200));
//$type = str_remove_sql_char(substr(@$_GET["type"], 0, 200));
//echo "<pre>".var_dump($_GET) . "</pre>";
$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

//$SQL = $SQL_QUERY["Persons"] . " AND [u].[Guid] = '$UserGuid'";
//$PropertyList = explode(":", $Properties);

//$PropertySql = implode("','", $PropertyList);

//exit;

if (! strlen($BlockSerNo) )
{
	echo "Paramerters are not specified!";
	exit;
}

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

$SQL = "
/****** Script for SelectTopNRows command from SSMS  ******/
SELECT [Alias] AS [BlockSerialNo]
      ,[Servers_Guid]
      ,[Bluegiga]
      ,[Cron]
      ,[Dios]
      ,[Logic]
      ,[Math]
      ,[Modems]
      ,[Netmon]
      ,[Power]
      ,[Sound]
      ,[Udev]
      ,[Hub]
      ,[Written]
	  ,FORMAT(DATEADD(day, -1, [Written]), 'dd.MM.yyy') AS [Date]
      ,[MinWorkingTime]
      ,[MaxWorkingTime]
      ,[DiffWorkingTime]
  FROM [BlockWorkingTimeHistory]
  WHERE 1=1
	--[DiffWorkingTime] > 10000
	AND [Written] > DATEADD(day, -100, GETDATE())
	AND [MaxWorkingTime] > 1000
	AND [Alias] = '$BlockSerNo'
ORDER BY [Written] DESC, [BlockSerialNo] ASC
";

//echo "<pre>$SQL</pre>";

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//echo "Step1b: [" . (microtime(true) - $microTime) . "] sec<br />";

$data = array();
//$dates  = array();
//$BlockGuid = '';

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}

//var_dump($dates[0]);

sqlsrv_close($conn) ;

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'blockservicesworkingtimehistory.tpl')
);

// Пороговые лимиты для раскраски в секундах
$DiffWorkingTimeLimit["Level1"] = 600;
$DiffWorkingTimeLimit["Level2"] = 3600;
$DiffWorkingTimeLimit["Level3"] = 7200;
$DiffWorkingTimeLimit["Level4"] = 14400;
$DiffWorkingTimeLimit["Level5"] = 28800;
$DiffWorkingTimeLimit["Level6"] = 36000;

$template->assign_vars(array(
//	'PROPERTIES' => implode(", ", $PropertyList),
	'BLOCK_SER_NO' => $BlockSerNo,
	'ALERT_LEVEL1' => gmdate("H:i:s", $DiffWorkingTimeLimit["Level1"]),
	'ALERT_LEVEL1_STYLE' => $style["bg-lllll-red"],
	'ALERT_LEVEL2' => gmdate("H:i:s", $DiffWorkingTimeLimit["Level2"]),
	'ALERT_LEVEL2_STYLE' => $style["bg-llll-red"],
	'ALERT_LEVEL3' => gmdate("H:i:s", $DiffWorkingTimeLimit["Level3"]),
	'ALERT_LEVEL3_STYLE' => $style["bg-lll-red"],
	'ALERT_LEVEL4' => gmdate("H:i:s", $DiffWorkingTimeLimit["Level4"]),
	'ALERT_LEVEL4_STYLE' => $style["bg-ll-red"],
	'ALERT_LEVEL5' => gmdate("H:i:s", $DiffWorkingTimeLimit["Level5"]),
	'ALERT_LEVEL5_STYLE' => $style["bg-l-red"],
	'ALERT_LEVEL6' => gmdate("H:i:s", $DiffWorkingTimeLimit["Level6"]),
	'ALERT_LEVEL6_STYLE' => $style["bg-red"],
	));

/*
	if ( $Compact )
	{
		$src = ["BlueGiga", "Cron", "Dios", "Hub", "Logic", "Math", "Modems", "Netmon", "Power", "Sound", "Udev"];
//		$trg = ["BG", "Cron", "Dios", "Hub", "Logc", "Math", "Mod", "Nen", "Pwr", "Snd", "Udv"];
		$trg = [
			"<img src=\"/pic/ico/BlueTooth_1_24x24.png\" width=\"16\" height=\"16\" title=\"BlueGiga\">", 
			"<img src=\"/pic/ico/Task_128x128.png\" width=\"16\" height=\"16\" title=\"Cron \">", 
			"<img src=\"/pic/ico/display_24x24.png\" width=\"16\" height=\"16\" title=\"Dios\">", 
			"<img src=\"/pic/ico/data-storage_24x24.png\" width=\"16\" height=\"16\" title=\"Hub\">", 
			"<img src=\"/pic/ico/logic_24x24.png\" width=\"14\" height=\"14\" title=\"Logic\">", 
			"<img src=\"/pic/ico/calc_24x24.png\" width=\"14\" height=\"14\" title=\"Math\">", 
			"<img src=\"/pic/ico/phone_2_24x24.png\" width=\"14\" height=\"14\" title=\"Modem\">", 
			"<img src=\"/pic/ico/network_24x24.png\" width=\"14\" height=\"14\" title=\"Network\">", 
			"<img src=\"/pic/ico/poweron_20x20.png\" width=\"16\" height=\"16\" title=\"Power\">", 
			"<img src=\"/pic/ico/melody_24x24.png\" width=\"16\" height=\"16\" title=\"Sound\">", 
			"<img src=\"/pic/ico/usb_24x24.png\" width=\"16\" height=\"16\" title=\"Udev\">"
			];

		$SrvcRestarts = str_replace($src, $trg, $SrvcRestarts);
		$SoftwareVer = str_replace($src, $trg, $SoftwareVer);
	}
*/

function get_bg_style ($time, $max_time)
{
	global $style, $DiffWorkingTimeLimit;

	$ret = "";
	$ret = ( ($max_time - $time) > $DiffWorkingTimeLimit["Level1"] and $time != "" ) ? $style["bg-lllll-red"] : $ret;
	$ret = ( ($max_time - $time) > $DiffWorkingTimeLimit["Level2"] and $time != "" ) ? $style["bg-llll-red"] : $ret;
	$ret = ( ($max_time - $time) > $DiffWorkingTimeLimit["Level3"] and $time != "" ) ? $style["bg-lll-red"] : $ret;
	$ret = ( ($max_time - $time) > $DiffWorkingTimeLimit["Level4"] and $time != "" ) ? $style["bg-ll-red"] : $ret;
	$ret = ( ($max_time - $time) > $DiffWorkingTimeLimit["Level5"] and $time != "" ) ? $style["bg-l-red"] : $ret;
	$ret = ( ($max_time - $time) > $DiffWorkingTimeLimit["Level6"] and $time != "" ) ? $style["bg-red"] : $ret;
	return $ret;
}

$i = 0;
while ( @$data[$i] )
{
	$MinWorkingTime = $data[$i]["MinWorkingTime"];
	$MaxWorkingTime = $data[$i]["MaxWorkingTime"];
	$DiffWorkingTime = $data[$i]["DiffWorkingTime"];


        $Date = $data[$i]["Date"];
	//$Servers_Guid = $data[$i]["Servers_Guid"];
	$Bluegiga = $data[$i]["Bluegiga"];
	$BluegigaStyle = get_bg_style ($Bluegiga, $MaxWorkingTime);
	$Bluegiga_time = ($Bluegiga != "" ) ? gmdate("H:i:s", $Bluegiga) : "N/A";


	$Cron = $data[$i]["Cron"];
	$CronStyle = get_bg_style ($Cron, $MaxWorkingTime);
	$Cron_time = ($Cron != "" ) ? gmdate("H:i:s", $Cron) : "N/A";

	$Dios = $data[$i]["Dios"];
	$DiosStyle = get_bg_style ($Dios, $MaxWorkingTime);
	$Dios_time = ($Dios != "" ) ? gmdate("H:i:s", $Dios) : "N/A";

	$Logic = $data[$i]["Logic"];
	$LogicStyle = get_bg_style ($Logic, $MaxWorkingTime);
	$Logic_time = ($Logic != "" ) ? gmdate("H:i:s", $Logic) : "N/A";

	$Math = $data[$i]["Math"];
	$MathStyle = get_bg_style ($Math, $MaxWorkingTime);
	$Math_time = ($Math != "" ) ? gmdate("H:i:s", $Math) : "N/A";

	$Modems = $data[$i]["Modems"];
	$ModemsStyle = get_bg_style ($Modems, $MaxWorkingTime);
	$Modems_time = ($Modems != "" ) ? gmdate("H:i:s", $Modems) : "N/A";

	$Netmon = $data[$i]["Netmon"];
	$NetmonStyle = get_bg_style ($Netmon, $MaxWorkingTime);
	$Netmon_time = ($Netmon != "" ) ? gmdate("H:i:s", $Netmon) : "N/A";

	$Power = $data[$i]["Power"];
	$PowerStyle = get_bg_style ($Power, $MaxWorkingTime);
	$Power_time = ($Power != "" ) ? gmdate("H:i:s", $Power) : "N/A";

	$Sound = $data[$i]["Sound"];
	$SoundStyle = get_bg_style ($Sound, $MaxWorkingTime);
	$Sound_time = ($Sound != "" ) ? gmdate("H:i:s", $Sound) : "N/A";

	$Udev = $data[$i]["Udev"];
	$UdevStyle = get_bg_style ($Udev, $MaxWorkingTime);
	$Udev_time = ($Udev != "" ) ? gmdate("H:i:s", $Udev) : "N/A";

	$Hub = $data[$i]["Hub"];
	$HubStyle = get_bg_style ($Hub, $MaxWorkingTime);
	$Hub_time = ($Hub != "" ) ? gmdate("H:i:s", $Hub) : "N/A";

	$Written = $data[$i]["Written"];
	$MinWorkingTime = $data[$i]["MinWorkingTime"];
	$MaxWorkingTime = $data[$i]["MaxWorkingTime"];
	$DiffWorkingTime = $data[$i]["DiffWorkingTime"];


	$template->assign_block_vars('row', array(
		'DATE' => $Date,
		'MAXWORKINGTIME' => gmdate("H:i:s", $MaxWorkingTime),
		'DIOS' => $Dios_time,
		'DIOS_STYLE' => $DiosStyle,
		'BLUEGIGA' => $Bluegiga_time,
		'BLUEGIGA_STYLE' => $BluegigaStyle,
		'MODEMS' => $Modems_time, 
		'MODEMS_STYLE' => $ModemsStyle,
		'CRON' => $Cron_time,
		'CRON_STYLE' => $CronStyle,
		'LOGIC' => $Logic_time,
		'LOGIC_STYLE' => $LogicStyle,
		'MATH' => $Math_time,
		'MATH_STYLE' => $MathStyle,
		'NETMON' => $Netmon_time,
		'NETMON_STYLE' => $NetmonStyle,
		'POWER' => $Power_time,
		'POWER_STYLE' => $PowerStyle,
		'SOUND' => $Sound_time,
		'SOUND_STYLE' => $SoundStyle,
		'UDEV' => $Udev_time,
		'UDEV_STYLE' => $UdevStyle,
		'HUB' => $Hub_time,
		'HUB_STYLE' => $HubStyle,
		));

	$i++;
}

$template->pparse('body');












exit;

echo "<table>
	<tr>
		<th>Date</th>
		<!--th>Name</th-->
		<th>BOVI</th>
		<th>Route</th>
		<th>Servers Pool</th>
		<th>IPAddress</th>
		<th>Packets</th>
		<th>Config Pack</th>
	</tr>
";

$i = 0;
while ( @$datesList[$i] )
{
	$date = $datesList[$i];

	$BlockSerialNo = $data[$datesList[$i]]["BlockSerialNo"];
	$Hostname = $data[$datesList[$i]]["Hostname"];
	$BlockName = ( $BlockSerialNo == $Hostname ) ? $BlockSerialNo : "$BlockSerialNo / $Hostname";

	$BlockOrientation = $data[$datesList[$i]]["BlockOrientation"];
	$FirmwareEINKver = $data[$datesList[$i]]["FirmwareEINKver"];
//	$FirmwareEINKver = 
	$FirmwareEINKverVer = substr($FirmwareEINKver, 0, strpos($FirmwareEINKver, ";"));
	$FirmwareEINKverVerHex = ( $FirmwareEINKverVer != "" ) ? strtoupper(dechex($FirmwareEINKverVer)) : "";
	$FirmwareEINKverVerShort = ( $FirmwareEINKverVer != "" ) ? " " . substr($FirmwareEINKverVerHex, 5) : "";
//	$BlockCfg_FirmwareEINKverVerShort = ( ( $BlockCfg_FirmwareEINKverVer == "" || $BlockCfg_FirmwareEINKverVer == "N/A" ) && $BlockCfg_Orientation != "" ) ? " ??" : $BlockCfg_FirmwareEINKverVerShort;

	
	$BaseRoute = $data[$datesList[$i]]["BaseRoute"];
	$CurrentRoute = $data[$datesList[$i]]["CurrentRoute"];
	$MaxRoute = $data[$datesList[$i]]["MaxRoute"];

	$ServersPool = $data[$datesList[$i]]["ServersPool"];
	$ServersPool = str_replace("192.168.51.","*.", $ServersPool);
	$PacketInstalled = $data[$datesList[$i]]["PacketInstalled"];
	$PacketInstalled = str_replace("shturman","sh",$PacketInstalled);
	$PacketInstalled = str_replace("config-spbmetro4thline","cfg-4s ",$PacketInstalled);
	$PacketInstalled = str_replace(";","); ",$PacketInstalled);
	$PacketInstalled = str_replace("|"," (",$PacketInstalled);

	$ServicesRunning = $data[$datesList[$i]]["ServicesRunning"];
	$ServicesStopped = $data[$datesList[$i]]["ServicesStopped"];
	$ConfigurrationPacket = $data[$datesList[$i]]["ConfigurrationPacket"];
	$ConfigurrationPacket = str_replace("shturman","sh",$ConfigurrationPacket);
	$ConfigurrationPacket = str_replace("config-spbmetro4thline","cfg-4s ",$ConfigurrationPacket);
	$ConfigurrationPacket = str_replace(";"," (",$ConfigurrationPacket);
	$ConfigurrationPacket .= ( $ConfigurrationPacket != "" ) ? ")" : "";

	$IPAddress = $data[$datesList[$i]]["IPAddress"];
	$IPAddress = str_replace("192.168.2.1;", "", $IPAddress);
	$IPAddress = str_replace("192.168.2.1", "", $IPAddress);
	$IPAddress = str_replace("10.110.", "*0.", $IPAddress);
	$IPAddress = str_replace("10.168.", "*8.", $IPAddress);
	$SoftwareVer = $data[$datesList[$i]]["SoftwareVer"];

	echo "<tr>
			<td>$date</td>
			<!--td>$BlockName</td-->
			<td>$BlockOrientation $FirmwareEINKverVerShort</td>
			<td>$BaseRoute/$CurrentRoute/$MaxRoute</td>
			<td>$ServersPool</td>
			<td>$IPAddress</td>
			<td>$PacketInstalled</td>
			<td>$ConfigurrationPacket</td>
		</tr>";
	$i++;
}
echo "</table>";

/*

Hostname
BlockOrientation
BaseRoute
CurrentRoute
MaxRoute
ServersPool
PacketInstalled
ServicesRunning
ServicesStopped
ConfigurrationPacket
IPAddress
FirmwareEINKver
SoftwareVer
*/

?>