<?php
/***************************************************************************
 *                                block_onbtext.php
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
include($DRoot . '/includes/form_date.php');

// Get params from url
//$ShowAlert = substr(@$_GET["ShowAlert"], 0, 20);
//$iObjectId = str_remove_sql_char(substr(@$_GET["ObjectId"], 0, 100));
//$iObjectType = str_remove_sql_char(substr(@$_GET["ObjectType"], 0, 20));

$BlockNo = str_remove_sql_char(substr(@$_GET["BlockSerNo"], 0, 30));
$Filter = str_remove_sql_char(substr(@$_GET["Filter"], 0, 30));

$ch_All = ( @$_GET["All"] == "true" ) ? TRUE : FALSE;
$ch_All_Actual = ( @$_GET["All_Actual"] == "true" ) ? TRUE : FALSE;
$ch_Stations = ( @$_GET["Stations"] == "true" ) ? TRUE : FALSE;
$ch_StationsTunnel = ( @$_GET["StationsTunnel"] == "true" ) ? TRUE : FALSE;
$ch_Driver = ( @$_GET["Driver"] == "true" ) ? TRUE : FALSE;
$ch_Quality = ( @$_GET["Quality"] == "true" ) ? TRUE : FALSE;
$ch_Alerts = ( @$_GET["Alerts"] == "true" ) ? TRUE : FALSE;
$ch_Alerts_Hid = ( @$_GET["Alerts_Hid"] == "true" ) ? TRUE : FALSE;
$ch_Alerts_Connect = ( @$_GET["Alerts_Connect"] == "true" ) ? TRUE : FALSE;
$ch_Buttons = ( @$_GET["Buttons"] == "true" ) ? TRUE : FALSE;
$ch_State = ( @$_GET["State"] == "true" ) ? TRUE : FALSE;

$i_DateFrom = ( @$_GET["Date_From"] ) ? str_remove_sql_char(substr(@$_GET["Date_From"], 0, 10)) : "";
$i_TimeFrom = ( @$_GET["Time_From"] ) ? str_remove_sql_char(substr(@$_GET["Time_From"], 0, 5)) : "";
$i_DateTo = ( @$_GET["Date_To"] ) ? str_remove_sql_char(substr(@$_GET["Date_To"], 0, 10)) : "";
$i_TimeTo = ( @$_GET["Time_To"] ) ? str_remove_sql_char(substr(@$_GET["Time_To"], 0, 5)) : "";

$Filtered = ( @$_GET["Filtered"] == "true" ) ? TRUE : FALSE;

$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

$SQL = "
/****** Сообщения с блоков  ******/
SELECT TOP 500 
	[s].[Alias] AS [BlockSerNo],
	CONCAT([p].[Last_Name], ' ', [p].[First_Name], ' ', [p].[Middle_Name]) AS [FIO],
	[sn].[SerialNo] AS [HID],
      --,[Users_Guid]
	[mt].[Name] AS [Msg_Type],
	[onbm].[Text],
	--[onbm].[Message_Types_Id],
	FORMAT(DATEADD(hour, 3, [onbm].[Started]), 'dd.MM.yyy HH:mm:ss') AS [Started],
	FORMAT(DATEADD(hour, 3, [onbm].[Finished]), 'dd.MM.yyy HH:mm:ss') AS [Finished]
--      ,[Services_Guid]
      --,[Written]
FROM [Shturman3].[dbo].[Stages_Onboard_Message] AS [onbm]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [onbm].[Servers_Guid]
LEFT JOIN [Sensors] AS [sn] ON [sn].[Guid] = [onbm].[Sensors_Guid]
LEFT JOIN [Users] AS [u] ON [u].[Guid] = [onbm].[Users_Guid]
LEFT JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
LEFT JOIN [Onboard_Message_Types] AS [mt] ON [mt].[Id] = [onbm].[Message_Types_Id]
WHERE
	[onbm].[Started] > %%FROM%%
	AND [onbm].[Finished] < %%TO%%
	AND s.[Alias] IN ('%%1%%')
	%%2%%
ORDER BY 
		[onbm].[Started] DESC
/*
SELECT TOP 500 
	--[t].[Guid],
	--[Server_Guid],
	[s].[Alias] AS [BlockSerNo],
	[t].[Source] AS [Type],
	--[t].[Happend],
	FORMAT([t].[Happend], 'dd.MM.yyy HH:mm:ss') AS [HappendF],
	--[t].[Written],
	FORMAT([t].[Written], 'dd.MM.yyy HH:mm:ss') AS [WrittenF],
	[t].[Text]
FROM [dbo].[Events_Onbtext] AS [t]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [t].[Server_Guid]
WHERE 1=1
	AND [s].[Alias] IN ('%%1%%')
	%%2%%
	--AND [t].[Source] IN ('Местоположение', 'Машинист', 'Качество сигнала', 'Тревожное сообщение', 'Нажатие кнопки', 'Состояние') -- Местоположение Машинист  Качество сигнала
	--AND [t].[Source] NOT IN ('Местоположение', 'Машинист', 'Качество сигнала', 'Тревожное сообщение', 'Нажатие кнопки', 'Состояние') -- Местоположение Машинист  Качество сигнала
	-- AND [t].[Happend] BETWEEN '' AND ''
	-- AND [t].[Happend] BETWEEN '' AND ''
	-- AND [t].[Text] LIKE ''
	-- AND [t].[Text] NOT LIKE '%>%'
ORDER BY 
	[t].[Happend] DESC
*/
";

if ( $BlockNo )
{
	$SQL = str_replace("%%1%%", $BlockNo, $SQL);

	if ( !$i_DateFrom and $i_DateTo )
	{
		$SQL = str_replace("%%FROM%%", "DATEADD(day, -30, '$i_DateTo')", $SQL);
	}
	else
	{
		$SQL = ( $i_DateFrom ) ? str_replace("%%FROM%%", "'$i_DateFrom $i_TimeFrom'", $SQL) : str_replace("%%FROM%%", "DATEADD(day, -30, SYSDATETIME())", $SQL);
	}
	$SQL = ( $i_DateTo ) ? str_replace("%%TO%%", "'$i_DateTo $i_TimeTo'", $SQL) : str_replace("%%TO%%", "SYSDATETIME()", $SQL);

//		$SQL = ( $i_DateFrom ) ? str_replace("%%FROM%%", "'$i_DateFrom $i_TimeFrom'", $SQL) : str_replace("%%FROM%%", "DATEADD(day, -30, SYSDATETIME())", $SQL);

}
else
{
	echo "BlockNo must be specified";
	exit;
}


if ( $Filter == "All" )
{
	$SQL = str_replace("%%2%%", "", $SQL);
}
elseif ( $Filter == "AllImportant" )
{
/*	$SQL = str_replace("%%2%%", "AND [t].[Source] IN ('Местоположение', 'Тревожное сообщение', 'Машинист') AND [t].[Text] NOT LIKE '%>%'", $SQL); */
	$SQL = str_replace("%%2%%", "AND [mt].[Name] IN ('Датчик неисправен', 'Нет связи с сервером', 'Станция', 'Поправьте датчик', 'Машинист', 'Маршрут') AND [onbm].[Text] NOT LIKE '%>%'", $SQL);
}
elseif ( $Filter == "Position" )
{
	$SQL = str_replace("%%2%%", "AND [mt].[Name] IN ('Станция') AND [onbm].[Text] NOT LIKE '%>%'", $SQL);
}
elseif ( $Filter == "PositionAll" )
{
	$SQL = str_replace("%%2%%", "AND [mt].[Name] IN ('Станция')", $SQL);
}
elseif ( $Filter == "DrvState" )
{
	$SQL = str_replace("%%2%%", "AND [mt].[NameXXXXX] IN ('Состояние')", $SQL);
}
elseif ( $Filter == "Button" )
{
	$SQL = str_replace("%%2%%", "AND [mt].[NameXXXXX] IN ('Нажатие кнопки')", $SQL);
}
elseif ( $Filter == "Alerts" )
{
	$SQL = str_replace("%%2%%", "AND [mt].[Name] IN ('Датчик неисправен', 'Нет связи с сервером', 'Поправьте датчик', 'Неизвестный тип сообщения')", $SQL);
}
elseif ( $Filter == "AlertsSen" )
{
	$SQL = str_replace("%%2%%", "AND [mt].[Name] IN ('Датчик неисправен', 'Поправьте датчик')", $SQL);
}
elseif ( $Filter == "AlertsLink" )
{
	$SQL = str_replace("%%2%%", "AND [mt].[Name] IN ('Нет связи с сервером')", $SQL);
}
elseif ( $Filter == "Signal" )
{
	$SQL = str_replace("%%2%%", "AND [mt].[Name] IN ('Качество сигнала')", $SQL);
}
elseif ( $Filter == "Driver" )
{
	$SQL = str_replace("%%2%%", "AND [mt].[Name] IN ('Машинист')", $SQL);
}
elseif ( $Filtered ) 
{
	$f = "";
	$f .= ( $ch_All ) ? " OR 1=1 " : "";
	$f .= ( $ch_All_Actual ) ? " OR ([mt].[Name] IN ('Датчик неисправен', 'Нет связи с сервером', 'Станция', 'Поправьте датчик', 'Машинист', 'Маршрут') AND [onbm].[Text] NOT LIKE '%>%') " : "";
	$f .= ( $ch_Stations ) ? " OR ([mt].[Name] IN ('Станция') AND [onbm].[Text] NOT LIKE '%>%') " : "";
	$f .= ( $ch_StationsTunnel ) ? " OR [mt].[Name] IN ('Станция') " : "";
	$f .= ( $ch_Driver ) ? " OR [mt].[Name] IN ('Машинист') " : "";
	$f .= ( $ch_Quality ) ? " OR [mt].[Name] IN ('Качество сигнала') " : "";
	$f .= ( $ch_Alerts ) ? " OR [mt].[Name] IN ('Датчик неисправен', 'Нет связи с сервером', 'Поправьте датчик', 'Неизвестный тип сообщения') " : "";
	$f .= ( $ch_Alerts_Hid ) ? " OR [mt].[Name] IN ('Датчик неисправен', 'Поправьте датчик') " : "";
	$f .= ( $ch_Alerts_Connect ) ? " OR [mt].[Name] IN ('Нет связи с сервером') " : "";
	$f .= ( $ch_Buttons ) ? " OR [mt].[NameXXXXX] IN ('Нажатие кнопки') " : "";
	$f .= ( $ch_State ) ? " OR [mt].[NameXXXXX] IN ('Состояние') " : "";

	$f_dFrom = ( $i_DateFrom ) ? $i_DateFrom : "";
	$f_dFrom = ( $i_DateFrom and $i_TimeFrom ) ? "$i_DateFrom $i_TimeFrom" : $f_dFrom;
	$f_dTo = ( $i_DateTo ) ? $i_DateTo : "";
	$f_dTo = ( $i_DateTo and $i_TimeTo ) ? "$i_DateTo $i_TimeTo" : $f_dTo;

	$f_date = ( $f_dFrom ) ? " AND [onbm].[Started] > '$f_dFrom' " : "";
	$f_date .= ( $f_dTo ) ? " AND [onbm].[Finished] < '$f_dTo' " : "";
	//$f_date = ( $f_dFrom and $f_dTo ) ? " AND [onbm].[Happend] BETWEEN '$f_dFrom' AND '$f_dTo' " : $f_date;
	//$f_date
	//*/	$f = "$f1 $f2";
	
//	$i_DateFrom = ( @$_GET["Date_From"] ) ? str_remove_sql_char(substr(@$_GET["Date_From"], 0, 10)) : "";
//	$i_TimeFrom = ( @$_GET["Time_From"] ) ? str_remove_sql_char(substr(@$_GET["Time_From"], 0, 5)) : "";
//	$i_DateTo = ( @$_GET["Date_To"] ) ? str_remove_sql_char(substr(@$_GET["Date_To"], 0, 10)) : "";
//	$i_TimeTo = ( @$_GET["Time_To"] ) ? str_remove_sql_char(substr(@$_GET["Time_To"], 0, 5)) : "";

//	echo "sadsafd";
	$SQL = str_replace("%%2%%", "$f_date AND ( 1=2 $f )", $SQL);
	//echo "<pre>$SQL</pre>";
}
else {
	$SQL = str_replace("%%2%%", "", $SQL);
}

//echo "<pre>$SQL</pre>";

$SQL = iconv("UTF-8", "Windows-1251", $SQL);

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//$data = Array();
//$TaskID_List = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data[]= $row;
}

//"<pre>"; var_dump($data); echo "</pre>"; 

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
	'body' => 'block_onbtext.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'BLOCKSERIALNO' => $BlockNo,

	'ALL_CHECKED' => ($ch_All) ? "checked" : "",
	'ALL_ACTUAL_CHECKED' => ($ch_All_Actual) ? "checked" : "",
	'STATIONS_CHECKED' => ($ch_Stations) ? "checked" : "",
	'STATIONS_TUNNEL_CHECKED' => ($ch_StationsTunnel) ? "checked" : "",
	'DRIVER_CHECKED' => ($ch_Driver) ? "checked" : "",
	'QUALITY_CHECKED' => ($ch_Quality) ? "checked" : "",
	'ALERTS_CHECKED' => ($ch_Alerts) ? "checked" : "",
	'ALERTS_HID_CHECKED' => ($ch_Alerts_Hid) ? "checked" : "",
	'ALERTS_CONNECT_CHECKED' => ($ch_Alerts_Connect) ? "checked" : "",
	'BUTTONS_CHECKED' => ($ch_Buttons) ? "checked" : "",
	'STATE_CHECKED' => ($ch_State) ? "checked" : "",
	
	));

/*
	$template->assign_block_vars('row', array(
	));
*/
//echo $i_DateTo;
$fDate = Array ( "dateFrom" => $i_DateFrom,  "timeFrom" => $i_TimeFrom, "dateTo" => $i_DateTo, "timeTo" => $i_TimeTo, "Prefix" => $BlockNo."_");
filter_date( "DateTimePeriod", $fDate ); // DDL выбора Водителя по FIO

$i = 0;
while ( @$data[$i] )
{
	$test = "";
	//$TaskID = $data[$i]["ID"];
	//$Status_Name = iconv("Windows-1251", "UTF-8", $data[$i]["Status_Name"]);
	$BlockSerNo = iconv("Windows-1251", "UTF-8", $data[$i]["BlockSerNo"]);
	$Type = iconv("Windows-1251", "UTF-8", $data[$i]["Msg_Type"]);
	$Text = iconv("Windows-1251", "UTF-8", $data[$i]["Text"]);
	$Started = $data[$i]["Started"];
	$Finished = $data[$i]["Finished"];
	$HappendF = $data[$i]["Started"];
	$HappendFPrev = @$data[$i-1]["Started"];
	$WrittenF = $data[$i]["Finished"];

/*	
	$dteStart = new DateTime($HappendF); 
	$dteEnd   = new DateTime($HappendFPrev); 
	$dteDiff  = $dteStart->diff($dteEnd); 
	$timeDiff = $dteDiff->format("%H:%I:%S"); 
	$timeSpent = ( $dteDiff->format("%S") !== "00"  ) ? $dteDiff->format("%s") . "s" : "0s" ; 
	$timeSpent = ( $dteDiff->format("%I") !== "00" ) ? $dteDiff->format("%i") . "m" : $timeSpent ; 
	$timeSpent = ( $dteDiff->format("%H") !== "00" ) ? $dteDiff->format("%h") . "h" : $timeSpent ; 
	$timeSpent = ( $dteDiff->format("%a") ) ? $dteDiff->format("%a") . "d" : $timeSpent ;
	$TotalTimeSpent = $dteDiff->format("%a") * 24 + $dteDiff->format("%H") * 60 + $dteDiff->format("%I");
	//$timeSpent = ( $dteDiff->format("%S") ) ? $dteDiff->format("%S") . "s" : $timeSpent ; 
	//$timeSpent = $dteDiff->format("%I"); 
*/
$timeSpent = "xxx";
$timeDiff = "xxx";
$TotalTimeSpent  ="xxx";

	$S_Text = "";
	$S_Text = ( $Type == "Тревожное сообщение" ) ? $style["bg-lll-purple"] : $S_Text;
	//$S_Text = ( $Type == "" ) ? "" : $S_Text;
	//$S_Text = ( $Type == "" ) ? "" : $S_Text;
	//$S_Row = 

	$S_Row = ($i % 2 == 0) ? "" : $style["bg-beige"];

	if ( $Filter == "All" )
	{
		//$S_ActiviyAlarm = $style["bg-lll-red"];
	}
	elseif ( $Filter == "AllImportant" )
	{
		//$S_ActiviyAlarm = $style["bg-lll-red"];
	}
	elseif ( ($Filter == "Position" or $Filter == "PositionAll") and $Text != "..." and !strpos($Text, "/д Невское") and $TotalTimeSpent > 20 )
	{
		$S_ActiviyAlarm = ($TotalTimeSpent > 10) ? $style["bg-lll-red"] : "";
		$S_ActiviyAlarm = ($TotalTimeSpent > 20) ? $style["bg-ll-red"] : $S_ActiviyAlarm;
		$S_ActiviyAlarm = ($TotalTimeSpent > 40) ? $style["bg-l-red"] : $S_ActiviyAlarm;
		$S_ActiviyAlarm = ($TotalTimeSpent > 60) ? $style["bg-red"] : $S_ActiviyAlarm;
		//$test = strpos($Text, "/д Невское");
	}
	elseif ( $Filter == "DrvState" )
	{
		//$S_ActiviyAlarm = $style["bg-lll-red"];
	}
	elseif ( $Filter == "Button" )
	{
		//$S_ActiviyAlarm = $style["bg-lll-red"];
	}	
		elseif ( $Filter == "Alerts" )
	{
		//$S_ActiviyAlarm = $style["bg-lll-red"];
	}
	elseif ( $Filter == "AlertsSen" )
	{
		//$S_ActiviyAlarm = $style["bg-lll-red"];
	}
	elseif ( $Filter == "AlertsLink" and $Text and $TotalTimeSpent > 20 )
	{
		$S_ActiviyAlarm = ($TotalTimeSpent > 20) ? $style["bg-lll-red"] : "";
		$S_ActiviyAlarm = ($TotalTimeSpent > 40) ? $style["bg-ll-red"] : $S_ActiviyAlarm;
		$S_ActiviyAlarm = ($TotalTimeSpent > 60) ? $style["bg-l-red"] : $S_ActiviyAlarm;
		$S_ActiviyAlarm = ($TotalTimeSpent > 120) ? $style["bg-red"] : $S_ActiviyAlarm;
		//$test = $TotalTimeSpent;
	}	
	elseif ( $Filter == "Signal" )
	{
		//$S_ActiviyAlarm = $style["bg-lll-red"];
	}
	elseif ( $Filter == "Driver" )
	{
		//$S_ActiviyAlarm = $style["bg-lll-red"];
	}
	else
	{
		$S_ActiviyAlarm = "";
	}
	/*
	$timeSpent = $dteDiff->format("%H:%I:%S"); 

	$timeSpent .= "-" . $dteDiff->format("%s"); 
	$timeSpent .= "=" .$dteDiff->s;
	    $timeSpent .=  "==". $dteDiff->format('%r').( // prepend the sign - if negative, change it to R if you want the +, too
                ($dteDiff->s)+ // seconds (no errors)
                (60*($dteDiff->i))+ // minutes (no errors)
                (60*60*($dteDiff->h))+ // hours (no errors)
                (24*60*60*($dteDiff->d))+ // days (no errors)
                (30*24*60*60*($dteDiff->m))+ // months (???)
                (365*24*60*60*($dteDiff->y)) // years (???)
                );
	*/
	//$timeSpent = 
	//$HappendFPrev = ()
	
	$template->assign_block_vars('row', array(
		'TEST' => $test,
		'BLOCKSERNO' => $BlockSerNo,
		'TYPE' => $Type,
		'TEXT' => $Text,
		'STARTED' => $Started,
		'FINISHED' => $Finished,
		'TIME_DIFF' => $timeDiff,
		'TIME_SPENT' => $timeSpent,
		'STYLE_ROW' => $S_Row,
		'S_ACTIVITY' => @$S_ActiviyAlarm,
		
	));

	$i++;
}

$template->pparse('body');
?>