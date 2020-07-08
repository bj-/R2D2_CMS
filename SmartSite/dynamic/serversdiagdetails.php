<?php

define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/common.php');
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');


$DocumentRoot = $_SERVER["DOCUMENT_ROOT"];

//include($DocumentRoot."/includes/config.php");
include($DocumentRoot."/includes/functions.php");

$rnd = rand ( 0 , 1000000000 );

$style["bg-light-red"] = "background-color:#FFaaaa;";
//$style["bg-light-red"] = "background-color:#FFe9e9;";
$style["bg-light-green"] = "background-color:#aaFFaa;";
$style["bg-red"] = "background-color:#FF0000;";
$style["color-dark-red"] = "color:#BB0000;";
$style["color-gray"] = "color:#AAAAAA;";


//str_remove_sql_char(substr($arrLine["1"], 0, 36));

$ServerID = str_remove_sql_char(substr(@$_GET["Server"], 0, 36));
$MetricID = str_remove_sql_char(substr(@$_GET["Metric"], 0, 36));
$MetricGroupID = str_remove_sql_char(substr(@$_GET["MetricGroup"], 0, 36));
$ClearStat = ( strtoupper(@$_GET["ClearStat"]) == "TRUE" ) ?  TRUE : FALSE;

//echo "<pre>";var_dump($_GET);echo "</pre>";

$ServerID = ( strlen($ServerID) == 36 ) ? $ServerID : "00000000-0000-0000-0000-000000000000";
$MetricID = ( strlen($MetricID) == 36 ) ? $MetricID : "00000000-0000-0000-0000-000000000000";
$MetricGroupID = ( strlen($MetricGroupID) == 36 ) ? $MetricGroupID : "00000000-0000-0000-0000-000000000000";


//echo "ffff";
//exit;

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );


if ( $ClearStat )
{
	$SQL = "
DELETE FROM 
	[DiagData]
WHERE 
	[MetricGroup_Guid] = '$MetricGroupID'
	AND [Metric_Guid] = '$MetricID'
	AND [Server_Guid] = '$ServerID'
";
	//echo "<pre>$SQL</pre>";

	MSSQLsiletnQuery($SQL);

	echo "статистика прогажена";
}

$SQL = "
SELECT 
	[s].[BlockSerialNo], 
	[mg].[Code] AS [GroupCode], 
	[mg].[Name] AS [GroupName], 
	[m].[Code] AS [MetricCode], 
	[m].[Name] AS [MetricName], 
	[m].[Limit] AS [MetricLimit], 
	[m].[vDivider] AS [MetricDivider], 
	[dd].[WriteDate], 
--	FORMAT([dd].[WriteDate], 'dd.MM HH:mm') AS [Writed_GMT],
	FORMAT(DATEADD(hour, 3, [dd].[WriteDate]), 'dd.MM HH:mm') AS [Writed],
	[dd].[Value]
FROM [DiagData] AS [dd]
INNER JOIN [MetricGroups] AS [mg] ON [mg].[Guid] = [dd].[MetricGroup_Guid]
INNER JOIN [Metrics] AS [m] ON [m].[Guid] = [dd].[Metric_Guid]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [dd].[Server_Guid]
WHERE 
	[dd].[MetricGroup_Guid] = '$MetricGroupID'
	AND [dd].[Metric_Guid] = '$MetricID'
	AND [dd].[Server_Guid] = '$ServerID'
	AND [WriteDate] > DATEADD(day,-3, SYSDATETIME())
ORDER BY 
	[s].[OrderNo] ASC, [dd].[WriteDate] DESC
";

$data = array();

$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
//$ServersList= array();
//$i = 0;


$onetime = TRUE;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServerName = $row["BlockSerialNo"];
	$MetricName = $row["MetricName"]; 
	$GroupName =  $row["GroupCode"];
    if ( $onetime and $row["GroupCode"] == "Resource" )
    {
		echo "<table cellspacing='1px' cellpadding='0px' class='tbl' align='center'>\n";
		echo "<tr><th colspan='7'>$ServerName -&gt; $GroupName -&gt; $MetricName</th><tr>";
		echo "<tr><th>Date</th><th>Handles</th><th>Threads</th><th>PM</th><th>VM</th><th>WS</th><th>CPU</th></tr>\n";
		$onetime = FALSE;
	}
    if ( $onetime and $row["GroupCode"] == "HDD" )
    {
		echo "<table cellspacing='1px' cellpadding='0px' class='tbl' align='center'>\n";
		echo "<tr><th colspan='4'>$ServerName -&gt; $GroupName -&gt; $MetricName</th><tr>";
		echo "<tr><th>Date</th><th>Free Space</th><th>Total Space</th><th>Volume Name</th></tr>\n";
		$onetime = FALSE;
	}
    if ( $onetime and $row["GroupCode"] == "LogActivity" )
    {
		echo "<table cellspacing='1px' cellpadding='0px' class='tbl' align='center'>\n";
		echo "<tr><th colspan='2'>$ServerName -&gt; $GroupName -&gt; $MetricName</th><tr>";
		echo "<tr><th>Date</th><th>Minutes Ago</th></tr>\n";
		$onetime = FALSE;
	}
    elseif ( $onetime )
    {
		echo "<table cellspacing='1px' cellpadding='0px' class='tbl' align='center'>\n";
		echo "<tr><th colspan='2'>$ServerName -&gt; $GroupName -&gt; $MetricName</th><tr>";
		echo "<tr><th>Date</th><th>Value</th></tr>\n";
		$onetime = FALSE;
    }

        
    if ( $row["GroupCode"] == "HDD" )
    {
//		$ProblemExist = FALSE;
//		echo "<td>$row[MetricLimit]</td>";
		$valList = array();
		$valList = explode(":", $row["Value"]);
		$res = "";
		echo "<tr>\n";	
		echo "<td>$row[Writed]</td>\n";	
		for ($vi=0; $vi < Count($valList); $vi++)
		{
			$nv = array();
			$nv = explode("=", $valList[$vi]);
//			var_dump ($nv);
			$res = "";
			$warn = FALSE;
			switch ($nv[0])
			{
				case "FreeSpace":
					$warn = ( (int)$nv[1] < (int)$row["MetricLimit"] ) ? TRUE : FALSE;
					$res = round(((int)$nv[1] / $row["MetricDivider"]),0) . "&nbsp;Gb";
//					$res .= round((int)$nv[1]/1024/1024/1024,0);
//					$ProblemExist = ( (int)$nv[1] < (int)$row["MetricLimit"] ) ? TRUE : FALSE;
					break;
				case "Size":
//					$warn = ( (int)$nv[1] < 15000000000 ) ? TRUE : FALSE;
					$res = round(((int)$nv[1] / $row["MetricDivider"]),0) . "&nbsp;Gb";
//					$res .= ( (int)$nv[1] > 1 ) ? "$valList[$vi]<br>" : "";
//					$ProblemExist = ( (int)$nv[1] > 1 ) ? $TRUE : $ProblemExist;
					break;
				case "VolumeName":
//					$warn = ( (int)$nv[1] > 600 ) ? TRUE : FALSE;
					$res = $nv[1];
//					$res .= ( $nv[1] ) ? "$nv[0]=$nv[1]<br>" : "";
//					$ProblemExist = ( (int)$nv[1] > 1 ) ? $TRUE : $ProblemExist;
					break;
			}
			$AddStyle = ( $warn ) ? "background-color:#FF7777;" : "";
			echo "	<td style='$AddStyle' align='center'>$res</td>\n";
		}
		echo "</tr>\n";	

        }
        elseif ( $row["GroupCode"] == "Resource" )
        {
			$valList = array();
//			echo $row["Value"];
			$valList = explode(":", $row["Value"]);
			$res = "";
			echo "<tr>\n";	
			echo "<td>$row[Writed]</td>\n";	
			for ($vi=0; $vi < Count($valList); $vi++)
			{
				$nv = array();
				$nv = explode("=", $valList[$vi]);
				//var_dump ($nv);
				$warn = FALSE;
				switch ($nv[0])
				{
					case "Handles":
//						echo $nv[0] . $nv[1];
						$warn = ( (int)$nv[1] > 2000 ) ? TRUE : $warn;
						$res = $nv[1];
						break;
					case "Threads":
//						$res = ( (int)$nv[1] > 100 ) ? $nv[1] : "";
						$warn = ( (int)$nv[1] > 200 ) ? TRUE : $warn;
						$res = $nv[1];
						break;
					case "PM":
//						$res = ( (int)$nv[1] > 500000000 ) ? ". round(((int)$nv[1]/1024/1024),0) ."Mb<br>" : "";
						$warn = ( (int)$nv[1] > 500000000 ) ? TRUE : $warn;
						$res = round(((int)$nv[1]/1024/1024),0) ."&nbsp;Mb";
						break;
					case "VM":
//						$res = ( (int)$nv[1] > 500000000 ) ? "$nv[0]=". round(((int)$nv[1]/1024/1024),0) ."Mb<br>" : "";
						$warn = ( (int)$nv[1] > 500000000 ) ? TRUE : $warn;
						$res = round(((int)$nv[1]/1024/1024),0) ."&nbsp;Mb";
						break;
					case "WS":
//						$res = ( (int)$nv[1] > 500000000 ) ? "$nv[0]=". round(((int)$nv[1]/1024/1024),0) ."Mb<br>" : "";
						$warn = ( (int)$nv[1] > 500000000 ) ? TRUE : $warn;
						$res = round(((int)$nv[1]/1024/1024),0) ."&nbsp;Mb";
						break;
					case "CPU":
//						$res = ( (int)$nv[1] > 10000 ) ? "$nv[0]=". round(((int)$nv[1]),0) ."Mb<br>" : "";
						$warn = ( (int)$nv[1] > 50000 ) ? TRUE : $warn;
						$res = round(((int)$nv[1]),0);
						break;
				}
				$AddStyle = ( $warn ) ? "background-color:#FF7777;" : "";
				echo "	<td style='$AddStyle' align='center'>$res</td>\n";

			}
			echo "</tr>\n";	
        }
        elseif ( $row["GroupCode"] == "LogActivity" )
        {
		$AddStyle = ( ((int)$row["Value"] - (int)$row["MetricLimit"]) > 0 ) ? $style["bg-light-red"] : "";
		$res = ( $row["MetricDivider"] and $row["Value"] != "N/A" ) ? round(($row["Value"] / $row["MetricDivider"]),0) . " m" : $row["Value"];
		echo "<tr><td align='center'>$row[Writed]</td><td align='right' style='$AddStyle'>$res</td><tr>";
	}
        else
        {
		$AddStyle = ( ((int)$row["Value"] - (int)$row["MetricLimit"]) > 0 ) ? $style["bg-light-red"] : "";
		$res = ( $row["MetricDivider"] ) ? round(($row["Value"] / $row["MetricDivider"]),0) : $row["Value"];
		echo "<tr><td align='center'>$row[Writed]</td><td align='right' style='$AddStyle'>$res</td><tr>";
	}
//	$data[$i]["Writed"] = $row["Writed"];
//	$data[$i]["Value"] = $row["Value"];
//	$i++;
}
echo "</table>";

echo "<a href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&rnd=$rnd&prms=Server=$ServerID;MetricGroup=$MetricGroupID;Metric=$MetricID;ClearStat=true'>[Clear Stat]</a><br /><br />";


sqlsrv_close($conn) ;


/*
echo "<pre>";
var_dump($data);
echo "</pre>";
*/

/*
$i = 0;
while ( @$data[][][$i] != "" )
{
	echo "$i - <br>";
	$i++;
}
*/


?>
