<?php
/***************************************************************************
 *                                report_file_upload.php
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
$Data = str_remove_sql_char(substr(@$_GET["Data"],0,250));
$DataArr = explode(":", $Data);
$Block = $DataArr[0];
$File = $DataArr[1];
//$Block = str_remove_sql_char(substr(@$_GET["BlockSerialNo"],0,50));
//$File = str_remove_sql_char(substr(@$_GET["File"],0,200));

//var_dump (@$_GET);

if ( $Block and $File )
{
	$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );

	$SQL = "
			INSERT INTO [dbo].[Block_Uploaded_Files]
				([BlockSerailNo],[File])
			VALUES
				('$Block','$File')
			";

	//echo "<pre>$SQL</pre>";

	$SQL = iconv("UTF-8","Windows-1251", $SQL);

	MSSQLsiletnQuery($SQL);

	sqlsrv_close($conn);
	$Status = "Added uploaded status for file [$Block\\$File]";
}


$currentdate = date("Y-m-d", time());
$currentdateOneMonthAgo = date("Y-m-d", time()-(30*24*60*60));
$rnd = rand ( 0 , 1000000000 );


//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";

$template->set_filenames(array(
	'body' => 'request/report_file_upload.tpl')
);

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ARTICLE' => $page_text,
	'RANDOM' => $rnd,
	'CURRENTDATE' => $currentdate,
	'CURRENTDATEONEMONAGO' => $currentdateOneMonthAgo,
	'STATUS' => $Status,


	));


// Легенда
if ( FALSE )
{
	$template->assign_block_vars('legend', array(
	));
}

$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));

// Page generation time spent
$microTime = microtime(true) - $microTime;
$template->assign_vars(array(
	'SPENT_TIME' => $microTime,
));

$template->pparse('body');
?>