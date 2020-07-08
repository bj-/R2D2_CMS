<?php
/***************************************************************************
 *                                persons_checkhid.php
 *                            -------------------
 *   begin                : Jun 13, 2018
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: persons_checkhid.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
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
//$params = $_GET["params"];
//$params = explode(";", $params);
//$UserGuid = $params[5];                     
//var_dump($_GET);

$DateFrom = substr($_GET["DateFrom"], 0, 10);
$UseDateFrom = substr($_GET["UseDateFrom"], 0, 10);
$DateTo = substr($_GET["DateTo"], 0, 10);
$UseDateTo = substr($_GET["UseDateTo"], 0, 10);
$UseSenOrFio = substr($_GET["UseSenOrFio"], 0, 10);
$UserGuid = substr($_GET["uGuid"], 0, 36);


$rnd = rand ( 0 , 1000000000 );

$conn = MSSQLconnect( "SpbMetro-Anal", "Shturman" );

$SQL = "
/* Количество сообщений выданных машинисту о необходимости поправить гарнитуру */

--USE [Shturman3]
--declare
--  @Started datetime2(7) = '%%FROM%%',
--  @Finished datetime2(7) = '%%TO%%',
--  @UserGuid uniqueidentifier = '%%GUID%%';

--select [Date] AS [Name], count(*) AS [Count] from (

select top 100 
	FORMAT(onb.[Happend], 'yyy-MM-dd') AS [Date]
	--s.Alias AS [BlockSerailNo]
	--onb.[Source],
	--onb.[Happend],
	--onb.[Text]
	--count (*)
from [Events_Onbtext] AS [onb]
join [Servers] AS [s] on [s].[Guid] = [onb].[Server_Guid]

where [onb].[Guid] in (
  select distinct [evt].[Guid] from [Events_Onbtext] [evt], [Stages_Users_Vehicles] [stg], [Servers] [srv], [Vehicles] [vhl]
  where [Happend] between '%%FROM%%' and '%%TO%%'
  and [stg].[Started] <= [evt].[Happend] and [evt].[Happend] <= [stg].[Finished]
  and [evt].[Text] like '%поправьте%'
  and [srv].[Guid] = [evt].[Server_Guid]
  and [vhl].[Guid] = [srv].[Vehicles_Guid]
  and [stg].[Users_Guid] = '%%GUID%%'
)

--GROUP BY s.Alias, onb.[Source], onb.[Text]
--group by onb.[Happend], s.Alias
--ORDER BY 
--	onb.[Happend] ASC, s.Alias

--) as t
--Group by t.[Date]
--ORDER BY t.[Date] DESC
";

$SQL = "
SELECT
	'sdsd'
	--FORMAT([onb].[Happend], 'yyy-MM-dd') AS [Date]
FROM Events_Onbtext AS [onb]
INNER JOIN [Servers] AS [s] on [s].[Guid] = [onb].[Server_Guid]
where [onb].[Guid] in (
	SELECT DISTINCT [evt].[Guid]
	FROM [Events_Onbtext] AS [evt]
	INNER JOIN [Stages_Users_Vehicles] AS [stg] ON stg.Started <= evt.Happend and evt.Happend <= stg.Finished
	INNER JOIN [Servers] AS [srv] ON [srv].[Guid] = [evt].[Server_Guid]
	INNER JOIN [Vehicles] AS [vhl] ON [vhl].[Guid] = [srv].[Vehicles_Guid]
	where 1=1
	--evt.Happend between '2019-02-24' and '2019-03-26'
		--and stg.Started <= evt.Happend and evt.Happend <= stg.Finished
		and [evt].[Text] like '%поправьте%'
		and [stg].[Users_Guid] = '8DC3C3A9-C6B9-470F-8CA7-BC48D86367B6'
)
";

$SQL = str_replace("%%GUID%%", $UserGuid, $SQL);
$SQL = str_replace("%%FROM%%", $DateFrom, $SQL);
$SQL = str_replace("%%TO%%", $DateTo, $SQL);
//$SQL = "select Guid as [Name],Password_Hash as [Count]  from users";
//echo "<pre>$SQL</pre>";


$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

//echo "<pre>";var_dump($stmt);echo "</pre>";
//echo "<pre>";var_dump(sqlsrv_fetch_array($stmt));echo "</pre>";

$data = array();
while ($row = sqlsrv_fetch_array($stmt)) {
	//var_dump($row);
	//echo $row["Date"];
	$data[] = $row;
}
echo "<pre>";var_dump($data);echo "</pre>";


sqlsrv_close($conn) ;

//$HID_SerNo = iconv("Windows-1251", "UTF-8", $data[$i]["HID"]);

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = "Title";
$page_text = "";
//$FIO = iconv("Windows-1251", "UTF-8", @$data[0]["FIO"]);

$template->set_filenames(array(
	'body' => 'persons_checkhid.tpl')
);


$template->assign_vars(array(
	'ARTICLE' => $page_text,
	'FIO' => @$FIO,
	));

$i = 0;
while ( @$data[$i] )
{
	$Name = @$data[$i]["Name"];
	$Count = @$data[$i]["Count"];

	$template->assign_block_vars('row', array(
		'NAME' => $Name,
		'VALUE' => $Count,
	));
		
	$i++;
}

$template->pparse('body');

?>