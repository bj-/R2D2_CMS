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


$FilesPath="D:\\BlockUpload\\";

// Get params from url
//$ShowAlert = substr(@$_GET["ShowAlert"], 0, 20);

//var_dump($_POST);
//$BlockSerNo = str_remove_sql_char(substr(@$_POST["BlockSerNo"], 0, 20));
//$FileName = str_replace(array(":", "\\", "/"), "", str_remove_sql_char(substr(@$_POST["FileName"],0 ,50))) ;
$BlockSerNo = str_remove_sql_char(substr(@$_GET["BlockSerNo"], 0, 20));
$FileName = str_replace(array(":", "\\", "/"), "", str_remove_sql_char(substr(@$_GET["FileName"],0 ,50))) ;
$FileNamePath = $FilesPath . $BlockSerNo . "\\" . $FileName;
if ( $BlockSerNo == "" or $FileName == "" or ! file_exists($FileNamePath) ) exit;

echo "<pre>$BlockSerNo\n$FileName</pre>";



$conn = MSSQLconnect( "SpbMetro-Anal", "Diag" );



/*
foreach ( explode("\n", $iData) as $line )
{
	$x = explode(";", $iData);
	$aData["ServiceName"] = $x[0];
	$aData["Date"] = $x[0];
	$aData["EventName"] = $x[0];
	$aData["id"] = $x[0];
	$aData[""] = $x[0];
	$aData[""] = $x[0];
	$aData[""] = $x[0];
	echo "$line\n";
}
*/

$rnd = rand ( 0 , 1000000000 );


$SQL = "
SELECT [Guid], [ServiceName] FROM [Services] WHERE ServiceSide = 'Olimex'
";
	
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	
$ServicesList = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServicesList[$row["ServiceName"]] = $row["Guid"];
}

//var_dump($ServicesList);

// Servers Guids list
$SQL = "
SELECT [BlockSerialNo], [Guid] FROM [Servers] WHERE [ServerType_Guid] ='F2585ED4-8D03-4E82-A895-3982E93B860C' ORDER BY [BlockSerialNo]
";
	
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	
$ServersList = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersList[$row["BlockSerialNo"]] = $row["Guid"];
}

//var_dump($ServicesList);


$lines = file($FileNamePath);

foreach ($lines as $line_num => $line) {

	$arr = array();
	$arr = explode(";", $line);

	$ServerGuid = ( @$ServersList[$BlockSerNo] ) ? $ServersList[$BlockSerNo] : "00000000-0000-0000-0000-000000000000" ;
	$ServiceName = $arr[0];
	$ServiceGuid = ( $ServicesList[$ServiceName] ) ? $ServicesList[$ServiceName] : "00000000-0000-0000-0000-000000000000" ;
	$date = substr($arr[1], 0, 10);

	// repair time if needed
	$time = $arr[2];
	// если первая строка лога не с начала и во времмени затесались мусорные символы.
	// учитывает только вариант вроде: [10:15:[10:17:22:123], т.е. "10:15:10:17:22:123" в отчете о работе сервисов..
	$time = ( strlen($time) > 13 ) ? substr($time, (strlen($time) - 12) ) : $time;
	//$time = substr($arr[2], 0, 12);

	$DateTime = str_replace(".", "-", $date) ." " . $time;
	$EventType = $arr[3];
	//$Event_n = intval($arr[3]);
	$msgType = $arr[4];
	$msgID = $arr[5];

	$msg_UTF8 = trim($arr[6]);
	$msg = iconv("UTF-8", "Windows-1251", $msg_UTF8);


	// Add Raw Data
	$SQL = "
		exec [BlockServicesWorkingTime_RAW_ADD]
			@Server_Guid  = '%%Server_Guid%%',
			@Service_Guid = '%%Service_Guid%%',
			@Date = '%%Date%%', 
			@EventType = '%%EventType%%',
			@Message_type  = '%%Message_type%%', 
			@Message_ID = '%%Message_ID%%', 
			@Message = '%%Message%%'
		";


	$SQL =  str_replace("%%Server_Guid%%", $ServerGuid, $SQL);
	$SQL =  str_replace("%%Service_Guid%%", $ServiceGuid, $SQL);
	$SQL =  str_replace("%%Date%%", $DateTime, $SQL);
	$SQL =  str_replace("%%EventType%%", $EventType, $SQL);
	$SQL =  str_replace("%%Message_type%%", $msgType, $SQL);
	$SQL =  str_replace("%%Message_ID%%", $msgID, $SQL);
	$SQL =  str_replace("%%Message%%", $msg, $SQL);
//echo "<pre>$SQL</pre>";
//exit;


//echo $ServiceGuid;
	
	echo "Строка #<b>{$line_num}</b> : " . htmlspecialchars("$BlockSerNo;$ServerGuid;$ServiceName;$ServiceGuid;$DateTime;$EventType;$msgType;$msgID;$msg_UTF8" ) . "<br />\n";
	echo "Строка #<b>{$line_num}</b> : " . htmlspecialchars("$BlockSerNo;$ServerGuid;$ServiceName;$ServiceGuid;$DateTime;$EventType;$msgType;$msgID;----" ) . "<br />\n";
	MSSQLsiletnQuery($SQL);

/*
	$SQL = "
	SELECT * FROM  WHERE ServiceSide = 'Olimex'
	";
	
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
		
	$ServicesList = array();
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$ServicesList[$row["ServiceName"]] = $row["Guid"];
	}
*/	

}


sqlsrv_close($conn) ;

$currentdate = date("Y-m-d", time());


//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'request/olimex_services_working_report.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	));

$template->pparse('body');
?>