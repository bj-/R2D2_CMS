<?php

define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/common.php');
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');
include($DRoot . "/includes/functions.php");

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );


$ID = str_remove_sql_char(substr(@$_GET["id"], 0, 10));
//var_dump($_GET);

//$ServerID = ( strlen($ServerID) == 36 ) ? $ServerID : "00000000-0000-0000-0000-000000000000";


$SQL = "
SELECT 
	[s].[BlockSerialNo],
	[ble].[LogPartFile]
FROM [BlockLogErrors] AS [ble]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [ble].[Server_Guid]
WHERE [ble].[id] = '%%1%%'
ORDER BY [ble].[DateTime] DESC
";
$SQL = str_replace("%%1%%" , "$ID", $SQL);

//echo $SQL;

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
//var_dump($stmt);

$data = Array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$data = $row;
}

//var_dump($data);

$BlockSerialNo=$data["BlockSerialNo"];

$FilePath = $CONFIG_SHTURMAN["UploadedFilesDirPathLocal"] . $data["BlockSerialNo"] . '\\Errors\\' . $data["LogPartFile"];

if ( file_exists ($FilePath) )
{
	$Content = file_get_contents($FilePath);
	$Lines = explode("\n", $Content);
//	foreach ($Lines as $line)
//	{
//        $UselessMessages = Array("Отправлен пакет", "Получен пакет");
	$started = true; // если метка сервис запущен уже была - новую не стаить
//	echo "Отправлен пакет";
	$label_cnt=0;
	$label = $data["BlockSerialNo"] . $data["LogPartFile"]; //. "-" . $label_cnt;

	echo "
	<div id='content_".$label."' name='content_".$label."'>
	<table width='1000px'><tr><td align='center'>
		<a style='cursor:pointer;' OnClick=\"closeLog();\" name='close_".$label."'><b>[CLOSE LOG]</b></a>
		<br /><br />
		<a href='#".$label."'><b>[Go to start line after crash (if exist)]</b></a></p>
	</td></tr></table>
	";

	echo "<pre>";
//	echo "<a style='cursor:pointer;display:table;margin-left: auto;margin-right: auto;' OnClick=\"clear_content(this.parentNode);\"><b>[CLOSE LOG]</b></a>\n"; 
//	echo "<p align='center'><a href='#".$label."'><b>[Go to start line after crash (if exist)]</b></a></p>\n";
	$i=0;
	while ( @$Lines[$i] )
	{
		if ( strpos($Lines[$i], "ERR:") )
		{
			echo "<span style='".$style["bg-ll-red"]."'>".$Lines[$i]."</span>";
		}
		elseif ( strpos($Lines[$i], "WRN:") )
		{
			echo "<span style='".$style["bg-ll-yellow"]."'>".$Lines[$i]."</span>";
		}
		elseif ( ( strpos($Lines[$i], " 00103 ") or strpos($Lines[$i], " 00000 ") ) and  $started )
		{
//			echo "\n<a style='cursor:pointer;display:table;margin-left: 450px;' OnClick=\"clear_content(this.parentNode);location.hash='close_".$label."';\" name='$label'><b>[CLOSE LOG]</b></a>\n"; 
//			echo "\n<a style='cursor:pointer;display:table;margin-left: 450px;' OnClick=\"clear_content('content_".$label."');location.hash='content_".$label."';\" name='$label'><b>[CLOSE LOG]</b></a>\n"; 
			echo "\n<a style='cursor:pointer;display:table;margin-left: 450px;' OnClick=\"closeLog();location.hash='content_".$label."';\" name='$label-$label_cnt' id='$label-$label_cnt'><b>[CLOSE LOG]</b></a>\n"; 

//			echo "<p align='center'><span style='text-align:right;'>
//				<a style='cursor:pointer;' OnClick=\"clear_content(this.parentNode);\"><b>[CLOSE LOG]</b></a></span></p>\n"; 
			echo "<span style='".$style["bg-ll-purple"]."'>!!! SERVICE STARTED...</span>\n";
			echo "<span style='".$style["bg-ll-purple"]."'>".$Lines[$i]."</span>\n";
			$label_cnt++;
			//$started = false;
		}
		elseif ( 
			strpos($Lines[$i], "Отправлен пакет") or  
			strpos($Lines[$i], "Получен пакет") or
			strpos($Lines[$i], "Отправлен фрейм") or  
			strpos($Lines[$i], "Получен фрейм") or  
			strpos($Lines[$i], "добавлен в очередь") or
			strpos($Lines[$i], "Подтверждено получение") or  
			strpos($Lines[$i], "Передано на")  or  
			strpos($Lines[$i], "[ALIVE]") or  
			strpos($Lines[$i], "Передан пакет") //or  
//			strpos($Lines[$i], "Полученфрейм") or  
//			strpos($Lines[$i], "Полученфрейм") 
			//or  
			//or  
			)
		{
			echo "<span style='".$style["color-ll-gray"]."'>".$Lines[$i]."</span>";
		}
		else 
		{
			echo $Lines[$i] . "\n";
		}
		$i++;
	}
//	echo $Lines;
//	echo "<a style='cursor:pointer;display:table;margin-left: auto;margin-right: auto;' OnClick=\"clear_content(this.parentNode);\" name='$label'><b>[CLOSE LOG]</b></a>\n"; 
//	echo "\n<a style='cursor:pointer;display:table;margin-left: 450px;' OnClick=\"clear_content(this.parentNode);\" name='$label'><b>[CLOSE LOG]</b></a>\n"; 
	echo "\n<a style='cursor:pointer;display:table;margin-left: 450px;' OnClick=\"closeLog()\" name='$label'><b>[CLOSE LOG]</b></a>\n"; 

	echo "</pre><br /><br /><br />";
	echo "
<input type=\"hidden\" id=\"hidden_".$label."\" value=\"111\" />
<script type=\"text/javascript\">
	navigate_to('$label-".($label_cnt-1)."');
	function closeLog()
	{
		//alert('sdfsdf22');

		var ShowLogStr='<a style=\"cursor:pointer;font-weight:bold;\" onclick=\"show_content(\\'/dynamic/proxy.php?page=spbMetroBlockErrorsDetailsShowLog&amp;params=$ID\\', \\'#errlog$BlockSerialNo$ID\\');\">Show Log Again &gt;&gt;</a>';
		document.getElementById('content_".$label."').innerHTML = ShowLogStr;


	}
</script>
</div>";
//	}
}
else
{
	echo "I'm... I'm sorry... File was proyebaned...";
}



sqlsrv_close($conn) ;


?>
