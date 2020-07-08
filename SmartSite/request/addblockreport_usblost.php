<?php
/***************************************************************************
 *                                blocks.php
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
$microTime=microtime(true); 

define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');


// Get params from url
$AlertOnly = ( strtoupper(@$_GET["AlertOnly"]) == "TRUE" ) ? TRUE : FALSE;
$NeedRepairing = ( strtoupper(@$_GET["NeedRepairing"]) == "TRUE" ) ? TRUE : FALSE;

//var_dump (@$_GET);
/*
$bp["wagon_in_train_not_connected_warning"] = 5760; // minutes
$bp["wagon_in_train_not_connected"] = 2880; // minutes
$bp["wagon_in_train_not_connected_light"] = 720; // minutes
$bp["wagon_in_train_not_connected_Diff"] = 300; // minutes (Disconnected time Difference betwen wagons in train )
$bp["wagon_alone_not_connected"] = 4320; // minutes
$bp["wagon_left_connected"] = 10; // блоки пропавшие со связи менее [минут] назад.
$bp["position_changed_time"] = 10; // блоки не менявшие местоположение более [минут].
$bp["svc_working_time_diff"] = 3600; // Разница во времени работы сервисов на блоках [секунд].
*/
$rnd = rand ( 0 , 1000000000 );

$data = @$_POST["data"];
$dataArr = explode(";", $data);

$ServerName = $dataArr[0];
$Date = $dataArr[1];
$DateA = explode(".", $Date);
$DateSQL = "20".$DateA[0]."-".$DateA[1]."-".$DateA[2];
$Lines = hex2bin($dataArr[2]);
$LinesArr = explode("\n", $Lines);

//var_dump($LinesArr);

//echo "ServerName = [$ServerName]\n";
//echo "Date = [$Date]\n";
//echo "DateSQL = [$DateSQL]\n";
//echo "LinesArray = [$Lines]\n";

$InsertArr = Array();
foreach ( $LinesArr as $line )
{

	$lineArr = explode(";", $line);
	if ( @$lineArr[2] )
	{
		$time = $lineArr[0];
		$CntNew = $lineArr[1];
		$CntOld = $lineArr[2];
		$InsertArr[] = "'$ServerName', '$DateSQL', '$time', '$DateSQL $time', $CntNew, $CntOld" ;
	}

}

$SQL = "
INSERT INTO [dbo].[Block_DeviceLostStat]
           ( [BlockSerialNo], [Date], [Time], [DateTime], [New], [Old] )
VALUES 
	(" . implode("),\n	(", $InsertArr) . ")
";

$SQL = "
merge [Block_DeviceLostStat] as target
using (values
	(" . implode("),\n	(", $InsertArr) . ")
      ) as source ([BlockSerialNo], [Date], [Time], [DateTime], [New], [Old])
on target.[BlockSerialNo] = source.[BlockSerialNo] AND target.[DateTime] = source.[DateTime]
--when matched then update
--	set target.[LastChanged] = source.[LastChanged],  target.[TimeAgo] = source.[TimeAgo],  target.[TimeAgoFormated] = source.[TimeAgoFormated], target.[Written] = source.[Written]
when not matched then insert 
	([BlockSerialNo], [Date], [Time], [DateTime], [New], [Old])
values (source.[BlockSerialNo], source.[Date], source.[Time], source.[DateTime], source.[New], source.[Old]);
";

//echo "<pre>$SQL</pre>";

$SQL = iconv("UTF-8", "Windows-1251", $SQL);

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );	

MSSQLsiletnQuery($SQL);


sqlsrv_close($conn) ;


$currentdate = date("Y-m-d", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));
/*
//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'request/addblockreport_usblost.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
//	'CURRENTDATE' => $currentdate,
//	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
));


//	$template->assign_block_vars('row', array(
//	));


$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));

// Page generation time spent
$microTime = microtime(true) - $microTime;

$template->assign_vars(array(
	'SPENT_TIME' => $microTime,
));

$template->pparse('body');
*/
?>