<?php

define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/common.php');
include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');

$DocumentRoot = $_SERVER["DOCUMENT_ROOT"];

//include($DocumentRoot."/includes/config.php");
//include($DocumentRoot."/includes/functions.php");

$conn = MSSQLconnect( "SpbMetro-Anal", "Block" );


$SQL = $SQL_QUERY["ServersInDiagAll"];
$stmt = sqlsrv_query( $conn, $SQL );
if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
$ServersList= array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$ServersList[] = $row["BlockSerialNo"];
}


$SQL = $SQL_QUERY["ServerDiagInfo"];


	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	echo "<table cellspacing='1px' cellpadding='0px' class='tbl' width='100%'>\n";
	$HeadLine = "<th>Metric</th>";
//	echo "<tr><th>Metric</th>";
	for ($i=0; $i < Count($ServersList); $i++){
//        	echo "<th>".$ServersList[$i]."</th>";
        	$HeadLine .= "<th>".$ServersList[$i]."</th>";
	}
//	echo "</tr>\n\r";
//	$HeadLine .= 
//	echo "<tr><th>" . $HeadLine . "</tr>\n\r";
	echo "<th></th>";

	$MetricGroupCurrent = "";
	$MetricCurrent = "";
	$ServerCount = 0;

	$WriteMinutesAgoWarning = 10;

//	echo "<tr><th>AAAAA</th>";

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		if ( $row["GroupName"] != $MetricGroupCurrent )
		{
			if ( $ServerCount != 0 and $ServerCount < Count($ServersList) )
			{
				// Добавляет ячейки если она не существует (для конца группы)
				for ( $tt = $ServerCount; $tt < Count($ServersList); $tt++ )
				{
//					echo "<td>=A$ServerCount=</td>";
					echo "<td style='background-color:#EDEDED'></td>";
					$ServerCount++;
				}
//				echo "<td style='background-color:#EDEDED'>AA</td>";
//				echo "<td>=A=</td>";
//				$ServerCount++;
			}
			echo "</tr>";
			echo "<tr>";
				echo "<th colspan='".(Count($ServersList)+1)."'>". $row["GroupName"] . "</th>";
			echo "</tr>\n\r";
			echo "<tr>" . $HeadLine . "\n\r";

			$MetricGroupCurrent = $row["GroupName"];
		}

		if ( $row["MetricName"] != $MetricCurrent )
		{
			if ( $ServerCount != 0 and $ServerCount < Count($ServersList) )
			{
				// Добавляет ячейки если она не существует (для конца строки)
				for ( $tt = $ServerCount; $tt < Count($ServersList); $tt++ )
				{
//					echo "<td>=A$ServerCount=</td>";
					echo "<td style='background-color:#EDEDED'></td>";
					$ServerCount++;
				}

			}

			echo "</tr>";
			echo "\n\r";
			echo "<tr>";
			echo "<td>$row[MetricName]</td>";
			$ServerCount = 0;
		}

		if ( $ServersList[$ServerCount] != $row["BlockSerialNo"] )
		{
			while ( $ServersList[$ServerCount] != $row["BlockSerialNo"] )
			{
//				echo "<td>=$ServerCount=</td>";
				echo "<td style='background-color:#EDEDED'></td>";
//				echo "<td></td>";
				$ServerCount++;
			}
		}
		$ServerCount++;

		// Для каждой группы метрик  персональный обработчик значений (парсер и тп.)
		if ( $row["GroupCode"] == "Queue" )
		{
			$style = ( (int) $row["value"] > (int)$row["MetricLimit"] ) ? " style='background-color:#FF7777;'" : "";
//			$style = ( (int) $row["value"] > 500 ) ? " style='background-color:#FF7777;'" : "";
//			$Details = ( strlen($style) != "" ) ? " <a href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]'><img src='/pic/ico/table_16x16.png' width='16' height='16' alt='Detailed List' /></a>" : "";
			$Details = ( strlen($style) != "" ) ? " <a href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\"><img src='/pic/ico/table_16x16.png' width='16' height='16' alt='Detailed List' /></a>" : "";
			$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";
			$res = (round((int) $row["value"] / 1024,0));
			$res = "<a style=\"color:#000000;\" href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">".$res."</a>";
			echo "<td align='center'$style>" . $res . "$OldDataVarning$Details</td>";	
//			echo "<td>$style $row[value]</td>";
			$style="";
			$OldDataVarning="";
		}
		elseif ( $row["GroupCode"] == "Frames" )
		{
			$style = ( (int) $row["value"] > (int)$row["MetricLimit"] ) ? " style='background-color:#FF7777;'" : "";
//			$style = ( (int) $row["value"] > 10 ) ? " style='background-color:#FF7777;'" : "";
			$Details = ( strlen($style) != "" ) ? " <a href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\"><img src='/pic/ico/table_16x16.png' width='16' height='16' alt='Detailed List' /></a>" : "";
			$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";
			$res = "<a style=\"color:#000000;\" href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">".$row["value"]."</a>";
			echo "<td align='center'$style>" . $res . "$OldDataVarning$Details</td>";	
//			echo "<td>$style $row[value]</td>";
			$style="";
			$OldDataVarning="";
		}
		elseif ( $row["GroupCode"] == "Messages" )
		{
			$style = ( (int) $row["value"] > (int)$row["MetricLimit"] ) ? " style='background-color:#FF7777;'" : "";
//			$style = ( (int) $row["value"] > 10 ) ? " style='background-color:#FF7777;'" : "";
			$Details = ( strlen($style) != "" ) ? " <a href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\"><img src='/pic/ico/table_16x16.png' width='16' height='16' alt='Detailed List' /></a>" : "";
			$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";
			$res = "<a style=\"color:#000000;\" href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">".$row["value"]."</a>";
			echo "<td align='center'$style>" . $res . "$OldDataVarning$Details</td>";	
//			echo "<td>$style $row[value]</td>";
			$style="";
			$OldDataVarning="";
		}
		elseif ( $row["GroupCode"] == "LogActivity" )
		{
			$style = ( (int) $row["value"] > (int)$row["MetricLimit"] ) ? " style='background-color:#FF7777;'" : "";
//			$style = ( (int) $row["value"] > 30 ) ? " style='background-color:#FF7777;'" : "";
			$res = ( (int)$row["value"] < 60 ) ? round((int)$row["value"], 0) . "s" : "";
			$res = ( (int)$row["value"] > 59 and (int)$row["value"] < 3600) ? round((int)$row["value"] / 60, 0) . "m" : $res;
			$res = ( (int)$row["value"] > 3599 ) ? round((int)$row["value"] / 3600, 0) . "h" : $res;
			$res = ( $row["value"] == "N/A" ) ? $row["value"] : $res;
			$res = "<a style=\"color:#000000;\" href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">$res</a>";

			$Details = ( strlen($style) != "" ) ? " <a href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\"><img src='/pic/ico/table_16x16.png' width='16' height='16' alt='Detailed List' /></a>" : "";
			$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";
			echo "<td align='center'$style>$res$OldDataVarning$Details</td>";	
//			echo "<td>$style $row[value]</td>";
			$style="";
			$OldDataVarning="";
		}
		elseif ( $row["GroupCode"] == "Resource" )
		{
			$valList = array();
			$valList = explode(":", $row["value"]);
			$res = "";
			for ($vi=0; $vi < Count($valList); $vi++)
			{
				$nv = array();
				$nv = explode("=", $valList[$vi]);
//				var_dump ($nv);
				switch ($nv[0])
				{
					case "Handles":
						$res .= ( (int)$nv[1] > 2000 ) ? "$valList[$vi]<br>" : "";
//						$res .= ( (int)$nv[1] > 400 ) ? "$valList[$vi]<br>" : "";
						break;
					case "Threads":
						$res .= ( (int)$nv[1] > 200 ) ? "$valList[$vi]<br>" : "";
						break;
					case "PM":
						$res .= ( (int)$nv[1] > 500000000 ) ? "$nv[0]=". round(((int)$nv[1]/1024/1024),0) ."Mb<br>" : "";
						break;
					case "VM":
						$res .= ( (int)$nv[1] > 500000000 ) ? "$nv[0]=". round(((int)$nv[1]/1024/1024),0) ."Mb<br>" : "";
						break;
					case "WS":
						$res .= ( (int)$nv[1] > 500000000 ) ? "$nv[0]=". round(((int)$nv[1]/1024/1024),0) ."Mb<br>" : "";
						break;
					case "CPU":
						$res .= ( (int)$nv[1] > 500000000 ) ? "$nv[0]=". round(((int)$nv[1]/1024/1024),0) ."Mb<br>" : "";
						break;
				}
			}
			$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";

			$Details = ( strlen($res) != 1 ) ? " <a href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\"><img src='/pic/ico/table_16x16.png' width='16' height='16' alt='Detailed List' /></a>" : "";

//			$res = "<a style=\"color:#000000;\" href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">".$res."</a>";

			$res = ( strlen($res) > 0 ) ? "<td style='background-color:#FF7777;' align='center'>$res$OldDataVarning$Details</td>" : "<td align='center'><a style=\"color:#000000;\" href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">-</a>$OldDataVarning</td>";
//			$res = ( strlen($Details) > 0 ) ? "<td style='background-color:#FF7777;' align='center'>$res$OldDataVarning$Details</td>" : "<td align='center'>-$OldDataVarning</td>";
			//$res = $row["value"];
			echo $res;	
			$OldDataVarning="";
		}
		elseif ( $row["GroupCode"] == "HDD" )
		{
			$ProblemExist = FALSE;
//			echo "<td>$row[MetricLimit]</td>";
			$valList = array();
			$valList = explode(":", $row["value"]);
			$res = "";
			for ($vi=0; $vi < Count($valList); $vi++)
			{
				$nv = array();
				$nv = explode("=", $valList[$vi]);
//				var_dump ($nv);
				switch ($nv[0])
				{
					case "FreeSpace":
						$res .= round((int)$nv[1]/1024/1024/1024,0);
						$ProblemExist = ( (int)$nv[1] < (int)$row["MetricLimit"] ) ? TRUE : $ProblemExist;
//						$ProblemExist = ( (int)$nv[1] < 20000000000 ) ? TRUE : $ProblemExist;
						break;
					case "Size":
//						$res .= ( (int)$nv[1] > 1 ) ? "$valList[$vi]<br>" : "";
//						$ProblemExist = ( (int)$nv[1] > 1 ) ? $TRUE : $ProblemExist;
						break;
					case "VolumeName":
//						$res .= ( $nv[1] ) ? "$nv[0]=$nv[1]<br>" : "";
//						$ProblemExist = ( (int)$nv[1] > 1 ) ? $TRUE : $ProblemExist;
						break;
				}
			}
			$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";
			$Details = ( $ProblemExist ) ? " <a href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\"><img src='/pic/ico/table_16x16.png' width='16' height='16' alt='Detailed List' /></a>" : "";
			$res = "<a style=\"color:#000000;\" href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&params=$row[Server_Guid];$row[MetricGroup_Guid];$row[Metric_Guid]' onclick=\"return hs.htmlExpand(this, { objectType: 'ajax'} )\">".$res."</a>";
			$res = ( $ProblemExist ) ? "<td style='background-color:#FF7777;' align='center'>$res$OldDataVarning$Details</td>" : "<td align='center'>$res$OldDataVarning</td>";
			//$res = $row["value"];
			echo $res;	
			$OldDataVarning="";
		}
		elseif ( $row["GroupCode"] == "Errors" )
		{
			if ( $row["MetricCode"] == "Cnt" )
			{
				$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";
				echo "<td align='center'$style>" . $row["value"] . "$OldDataVarning</td>";	
			}
			elseif ( $row["MetricCode"] == "Files" )
			{
				$val = "";
				$val = str_replace(":", "<br />", $row["value"]);
				$val = str_replace("Server.exe", "", $val);
				$val = str_replace(".Error", "", $val);
				$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";
				echo "<td align='center' style='$style'>$val$OldDataVarning</td>";	
			}
//			$style = ( (int) $row["value"] > (int)$row["MetricLimit"] ) ? " style='background-color:#FF7777;'" : "";
//			echo "<td>$style $row[value]</td>";
			$style="";
			$OldDataVarning="";
		}
		else 
		{
			echo "<td>$row[value]</td>";
		}
			
		if ( $row["MetricName"] != $MetricCurrent )
		{
			$MetricCurrent = $row["MetricName"];
		}
	}
			echo "</tr>";
			echo "\n\r";
	echo "</table>\n";

sqlsrv_close($conn) ;

echo "<p>Generated at: " . date("d.m.Y H:i:s") . "</p>";

?>
