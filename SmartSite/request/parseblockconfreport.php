<?php

/** parseblockconfreport.php **/

define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/common.php');
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');


//echo getdate();
$microTime=microtime(true); 

$DocumentRoot = $_SERVER["DOCUMENT_ROOT"];

//include($DocumentRoot."/includes/config.php");
include($DocumentRoot."/includes/functions.php");

//$conn = MSSQLconnect( "SpbMetro4s-Root4", "Shturman" );
//$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );
$conn = MSSQLconnect( "SpbMetro-Anal", "Diag" );


// ServersAll
$ServersAll = array();
$SQL = $SQL_QUERY["ServersAll"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersAll[$row["BlockSerialNo"]] = $row["Guid"];
}

/*
$path = "D:\\BlockUpload\\xxx.txt";
unlink($path);
exit;
*/


function scanFileNameRecursivly($path = '', &$name = array(), $include = "")
{
	$path = $path == ''? dirname(__FILE__) : $path;
	$lists = @scandir($path);
  
	if(!empty($lists))
	{
		foreach($lists as $f)
		{ 
    
			if(is_dir($path.DIRECTORY_SEPARATOR.$f) && $f != ".." && $f != "." )
			{
				scanFileNameRecursivly($path.DIRECTORY_SEPARATOR.$f, $name, $include); 
				//echo $path.DIRECTORY_SEPARATOR.$f . "<br>";
			}
			else
			{
				if ( strlen($include) && strlen(stristr($f, $include)) )
				//if (true )
				{
					//echo "s";
					$name[] = $path.DIRECTORY_SEPARATOR.$f;
				//echo $path.DIRECTORY_SEPARATOR.$f . "<br>";
				}
				elseif ( strlen($include) == 0 )
				{
					$name[] = $path.DIRECTORY_SEPARATOR.$f;
				}
			}
		}
	}
	return $name;
}

$path = "D:\\BlockUpload";
$name = array();
$Files = scanFileNameRecursivly($path, $name, "BlockReport");


echo "<pre>";

$i = 0;
foreach ( $Files as $file )
{
	if ( !strpos($file, ".gz") )
	{
	$BlockSerNo = substr($file, 0, strrpos($file,"\\"));
	$BlockSerNo = substr($BlockSerNo, strrpos($BlockSerNo,"\\")+1 );
	$ServerGuid = ( @$ServersAll["$BlockSerNo"] ) ? $ServersAll["$BlockSerNo"] : "00000000-0000-0000-0000-000000000000";

//	echo $BlockSerNo. $file . "<br>";


	$lines = file($file);

	//echo $file; 

	// Осуществим проход массива и выведем содержимое в виде HTML-кода вместе с номерами строк.
	$a = array();
	$ReportDateTime = "";
	$FileCorrectlyEnded = FALSE; // сбрасываем благ нормального окончания файла (если файл нормально не закончился - не надо его заливать в бд)
	foreach ($lines as $line_num => $line) 
	{
//		echo "Строка #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
		$line = trim($line);
		$dpos = strpos($line, "=");
//		$prop = "";
//		$val = "";
		$prop = substr($line, 0, $dpos);
		$val = substr(substr($line, $dpos+1),0,200);
		if ( $prop == "ReportDateTime" )
		{
			$ReportDateTime = str_replace(";", " ", $val);
			$ReportDateTime = ( strpos($ReportDateTime, ".") == 2 ) ? "20" . $ReportDateTime : $ReportDateTime;
			$ReportDateTime = str_replace(".", "-", $ReportDateTime);
//			$ReportDateTime = 
//			$ReportDateTime = implode($val, " ");
		}
		elseif ( $prop == "ReportDateTimeGMT" )
		{
			$ReportDateTimeGMT = str_replace(";", " ", $val);
			$ReportDateTimeGMT = ( strpos($ReportDateTimeGMT, ".") == 2 ) ? "20" . $ReportDateTimeGMT : $ReportDateTimeGMT;
			$ReportDateTimeGMT = str_replace(".", "-", $ReportDateTimeGMT);
			$ReportDateGMT = substr($val, 0, strpos($val, ";"));
			$ReportDateGMT = ( strpos($ReportDateGMT, ".") == 2 ) ? "20" . $ReportDateGMT : $ReportDateGMT;
			$ReportDateGMT = str_replace(".", "-", $ReportDateGMT);
		}
		elseif ( $prop != "EndOfFile" )
		{
			$a[] = "('" . $ServerGuid . "', '" . $prop . "', '" . $val . "', '%REPORTDATE%', %REPORTDATETIMEGMT%, %REPORTDATEGMT%)";
		}
		elseif ( $prop == "EndOfFile" )
		{
			$FileCorrectlyEnded = TRUE;
		}
	}
	$insert = implode($a, ",\n");
	$insert = str_replace("%REPORTDATE%", $ReportDateTime, $insert);
	$insert = ( strlen(@$ReportDateTimeGMT) ) ? str_replace("%REPORTDATETIMEGMT%", "'$ReportDateTimeGMT'", $insert) : str_replace("%REPORTDATETIMEGMT%", "NULL", $insert);
	$insert = ( strlen(@$ReportDateGMT) ) ? str_replace("%REPORTDATEGMT%", "'$ReportDateGMT'", $insert) : str_replace("%REPORTDATEGMT%", "NULL", $insert);

	//echo $file . "<br />";

	if ( $FileCorrectlyEnded )
	{
		$SQL="
INSERT INTO [dbo].[ServersConfig]
	([ServerGuid],[PropertyName],[PropertyValue],[Reported],[Reported_GMT],[Date])
	VALUES 
		$insert
";

		//echo $SQL;

		MSSQLsiletnQuery($SQL);

		$SQL="
UPDATE [dbo].[Servers]
   SET [ReportDateTime] = '$ReportDateTime'
 WHERE [Guid] = '$ServerGuid'
";

//	       	echo $SQL;

		MSSQLsiletnQuery($SQL);
	}
}

unlink($file);

echo "Processed [$file]<br />";
/*
	echo "\$BlockSerNo = [$BlockSerNo]<br>";
	echo "\$file = [$file]<br>";
//	echo "\$insert = [$insert]<br>";
	echo "\$SQL = [$SQL]<br>";
	//*/


}
//var_dump($file_names);



echo "</pre>"; 

/*
$dir    = 'D:\\BlockUpload\\';
$files1 = scandir($dir);
$files2 = scandir($dir, 1);

echo "<pre>";
print_r($files1);
print_r($files2);

echo "</pre>";
  */
/*

$SQL = $SQL_QUERY["ServersAll"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersAll[$row["BlockSerialNo"]] = $row["Guid"];
}
//var_dump($ServersAll);

*/

sqlsrv_close($conn) ;


echo (microtime(true) - $microTime) . ' сек.';

?>