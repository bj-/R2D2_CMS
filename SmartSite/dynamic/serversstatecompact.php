<?php

define('IN_R2D2', true);


$DocumentRoot = $_SERVER["DOCUMENT_ROOT"];

include($DocumentRoot."/includes/config.php");
include($DocumentRoot."/includes/functions.php");

include($DRoot . "/includes/config_shturman.php");
include($DRoot . '/includes/common_shturman.php');


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


	$MetricGroupCurrent = "";
	$MetricCurrent = "";
	$ServerCount = 0;

	$WriteMinutesAgoWarning = 10;

	$result = "";

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

		// Для каждой группы метрик  персональный обработчик значений (парсер и тп.)
		if ( $row["GroupCode"] == "Queue" )
		{
			$style = ( (int) $row["value"] > (int)$row["MetricLimit"] ) ? " style='background-color:#FF7777;'" : "";
			$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";
			if ( $style != "" and $OldDataVarning == "" )
			{
       				$result .= "<tr><td$style>" . $row["BlockSerialNo"] . " > ".$row["GroupCode"]."</td><td$style>" . (round((int) $row["value"] / 1024,0)) . "</td></tr>\n\r";
//				echo $row["BlockSerialNo"] . ":" . $row["GroupCode"] . ":" . (round((int) $row["value"] / 1024,0)) . "<br />";
			}
			$style="";
			$OldDataVarning="";
		}
		elseif ( $row["GroupCode"] == "Frames" )
		{
			$style = ( (int) $row["value"] > (int)$row["MetricLimit"] ) ? " style='background-color:#FF7777;'" : "";
			$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";
			if ( $style != "" and $OldDataVarning == "" )
			{
       				$result .= "<tr><td$style>" . $row["BlockSerialNo"] . " > ".$row["MetricName"]." ".$row["GroupCode"]."</td><td$style>" . $row["value"] . "</td></tr>";//. "<br />";
//				echo $row["BlockSerialNo"] . ":" . $row["MetricName"] . ":" . $row["GroupCode"] . ":" . $row["value"] . "<br />";
			}
			$style="";
			$OldDataVarning="";
		}
		elseif ( $row["GroupCode"] == "Messages" )
		{
			$style = ( (int) $row["value"] > (int)$row["MetricLimit"] ) ? " style='background-color:#FF7777;'" : "";
			$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";
			if ( $style != "" and $OldDataVarning == "" )
			{
       				$result .= "<tr><td$style>" . $row["BlockSerialNo"] . " > ".$row["MetricName"]." ".$row["GroupCode"]."</td><td$style>" . $row["value"] . "</td></tr>";//. "<br />";
//				echo $row["BlockSerialNo"] . ":" . $row["MetricName"] . ":" . $row["GroupCode"] . ":" . $row["value"] . "<br />";
			}
//			echo "<td align='center'$style>" . $row["value"] . "$OldDataVarning</td>";	
//			echo "<td>$style $row[value]</td>";
			$style="";
			$OldDataVarning="";
		}
		elseif ( $row["GroupCode"] == "LogActivity" )
		{
			$style = ( (int) $row["value"] > (int)$row["MetricLimit"] ) ? " style='background-color:#FF7777;'" : "";
			$res = ( (int)$row["value"] < 60 ) ? round((int)$row["value"], 0) . "s" : "";
			$res = ( (int)$row["value"] > 59 and (int)$row["value"] < 3600) ? round((int)$row["value"] / 60, 0) . "m" : $res;
			$res = ( (int)$row["value"] > 3599 ) ? round((int)$row["value"] / 3600, 0) . "h" : $res;
			$res = ( $row["value"] == "N/A" ) ? $row["value"] : $res;

			$OldDataVarning = ( $row["MinutesAgo"] > $WriteMinutesAgoWarning ) ? "<span style='color:orange;'> (".( $v = ($row["MinutesAgo"] > 59) ? round(($row["MinutesAgo"] / 60),0)."h" : $row["MinutesAgo"]."m" ).")</span>" : "";
			if ( $style != "" and $OldDataVarning == "" )
			{
				$result .= "<tr><td$style>" . $row["BlockSerialNo"] . " > ".$row["MetricName"]." (".$row["GroupCode"].")</td><td$style>" . $res . "</td></tr>";//. "<br />";
//				echo $row["BlockSerialNo"] . ":" . $row["MetricName"] . ":" . $row["GroupCode"] . ":" . $res . "<br />";
			}
//			echo "<td align='center'$style>$res$OldDataVarning</td>";	
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
			$style = ( strlen($res) > 0 ) ? " style='background-color:#FF7777;'" : "";
			//$res = $row["value"];
			if ( $style != "" and $OldDataVarning == "" )
			{
				$result .= "<tr$style><td$style>" . $row["BlockSerialNo"] . " > ".$row["MetricName"]."</td><td$style>" . $res . "</td></tr>";//. "<br />";
			}
//			echo $res;	
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
			$style = ( $ProblemExist ) ? " style='background-color:#FF7777;'" : "";
			if ( $style != "" and $OldDataVarning == "" )
			{
				$result .= "<tr><td$style>" . $row["BlockSerialNo"] . " > ".$row["MetricName"]."</td><td$style>" . $res . "</td></tr>";//. "<br />";
//				echo $row["BlockSerialNo"] . ":" . $row["MetricName"] . ":" . $row["GroupCode"] . ":" . $res . "<br />";
			}
			//$res = $row["value"];
//			echo $res;	
			$OldDataVarning="";
		}
/*		elseif ( $row["GroupCode"] == "Errors" )
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
//				echo "<td align='center'$style>$val$OldDataVarning</td>";	
			}
//			$style = ( (int) $row["value"] > (int)$row["MetricLimit"] ) ? " style='background-color:#FF7777;'" : "";
//			echo "<td>$style $row[value]</td>";
			$style="";
			$OldDataVarning="";
		}
*/
		else 
		{
//			echo "<td>$row[value]</td>";
		}
			
		if ( $row["MetricName"] != $MetricCurrent )
		{
			$MetricCurrent = $row["MetricName"];
		}
	}

	if ( $result != "" )
	{
		echo "<table cellspacing='1px' cellpadding='0px' class='tbl' width='100%'>\n";
		echo "<tr><th colspan='2'>Server's problems
			<br/>at: " . date("d.m.Y H:i:s") . "</th></tr>";
		echo $result;
		echo "\n\r";
		echo "</table>\n";

	}
	else
	{
		echo "
			<div width='100%' style='text-align:center; background-color:#aaFFaa;'>
				 <br>Сервера работают с невидимыми ошибками.<br>
				<!--img src='/pic/ico/crying_128x128.png' width='128' height='128' align='center' />
				 Надо писать новую версию...--><br>
			</div>
		";
//		echo "<div width='100%' style='text-align:center; bgcolor:red'>Вах! Сервера работают!";
	}

sqlsrv_close($conn) ;

?>
