<?php
/*******************************************************************
*
*                  Shturman functions
*
*******************************************************************/
$ver["functions_shturman"] = "1.0.1"; // Version of script
/*
1.0.1
	- add function: check_config_property($Config, $Name, $Value, $Group_Id, $Description)
*/


/*
function encode_ip($dotquad_ip)
{
	$ip_sep = explode('.', $dotquad_ip);
	return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
}
*/
/*
function str_remove_sql_char($str)
{
	$str = trim($str);
//	$str = str_replace("UNION", "", $str);
	$str = str_replace("\\", "", $str);
	$str = preg_replace('/["\']/', '', $str);
	
	return $str;
}
*/                               
function ShowTask ($ShowType)
{
        //
	// НЕ ИСПОЛЬЗУЕТСЯ
	//

	global $conn,$SQL_QUERY,$CONFIG_SHTURMAN,$Edit;

	echo "<h2>All (Active) Task List</h2>";

	if ( $ShowType == "ACTIVE" )
	{
		$SQL = $SQL_QUERY["iAll_Active"];
	}
	else
	{
		$SQL = $SQL_QUERY["iAll_Actual"];
	}

	$SQL .= "\nORDER BY [i].[Created] DESC;";

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}


	echo "<a href='index.php'>Refresh</a>";
	echo "<table cellspacing='1px' cellpadding='0px'>\n";
	echo "<tr><th>A</th><th>Status</th><th>Block</th><th>Action</th><th>Service</th><th>Source</th><th>Target</th><th>Parameters</th><th>Block Result</th><th>Block Comment</th><th>Uploaded File</th><th>Date Added</th><th>Date Passed</th><th>Author</th></tr>\n\r";
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$AddClass = (( $row["StatusId"] == "closed" ) || ( $row["StatusId"] == "deleted" )) ? " class='closed'" : "";
		$AddClass = ( $row["StatusId"] == "error" ) ? " class='error'" : $AddClass;
		$AddClass = ( $row["StatusId"] == "new" ) ? " class='new'" : $AddClass;
		if (file_exists($CONFIG_SHTURMAN["UploadedFilesDirPathLocal"] . $row["BlockSerialNo"] . "\\" . $row["UploadedFile"]))
		{
			$UploadedFileLink = "<a href='". $CONFIG_SHTURMAN["UploadedFilesDirPathLocal"] . $row["BlockSerialNo"] . "\\" . $row["UploadedFile"] . "'>" . $row["UploadedFile"] . "</a>";
			$AddClass = ( $row["StatusId"] == "done" ) ? " class='done'" : $AddClass;
		}
		elseif ( substr($row["ActionType"],0,3) != "put" )
		{
//			echo substr($row["ActionType"],0,3);
			$UploadedFileLink = "--";
		}
		else
		{
			$UploadedFileLink = "-- in transit --";
//			$AddClass = ( $row["StatusId"] == "done" ) ? " class='done'" : $AddClass;
		}
//echo		$UploadedFileExist = (file_exists($CONFIG_SHTURMAN["UploadedFilesDirPathLocal"] . $row["BlockSerialNo"] . "\\" . $row["UploadedFile"])) ? "exist" : "-- in transit --";
		echo "<tr" . $AddClass . ">";
			$EditLine = "";
			$EditLine = ($Edit && ($row["StatusId"] == "closed" || $row["StatusId"] == "deleted" || $row["StatusId"] == "done" || $row["StatusId"] == "error" || $row["StatusId"] == "new")) ? $EditLine . " [<a style='color:red;text-weght:bold;' href='?id=$row[Guid]&Status=Active' title='Active'>A</a>]" : $EditLine;
			$EditLine = ($Edit && ($row["StatusId"] == "active" || $row["StatusId"] == "done" || $row["StatusId"] == "error")) ? $EditLine . " [<a style='color:red;text-weght:bold;' href='?id=$row[Guid]&Status=Closed' title='Close'>C</a>]" : $EditLine;
			$EditLine = ($Edit && ($row["StatusId"] == "active" || $row["StatusId"] == "done" || $row["StatusId"] == "error") || $row["StatusId"] == "uncknown" || $row["StatusId"] == "new") ? $EditLine . " [<a style='color:red;text-weght:bold;' href='?id=$row[Guid]&Status=Deleted' title='Delete'>D</a>]" : $EditLine;
//			$EditLine = ($Edit) ? $EditLine . " [<a style='color:red;text-weght:bold;' href='?id=$row[Guid]&Edit=1'>E</a>]" : $EditLine;
			echo "<td width='60px'>$EditLine</td>";
			echo "<td>$row[StatusName]</td>";
			$SerialNo = (substr($row["BlockSerialNo"],0,3) == "STB") ? str_replace("STB", "<span style='color:#cccccc'>STB</span>",$row["BlockSerialNo"]) : $row["BlockSerialNo"];
			echo "<td>$SerialNo</td>";
			echo "<td>$row[ActionName]</td>";
			echo "<td>$row[ServiceShortName]</td>";
			echo "<td>$row[Source]</td>";
			echo "<td>$row[Target]</td>";
			echo "<td>$row[Parameters]</td>";
			echo "<td>$row[ResultName]</td>";
			echo "<td>$row[BlockComment]</td>";
			echo "<td>$UploadedFileLink</td>";
			echo "<td>$row[Created]</td>";
				//echo "<td><pre>"; var_dump ($row[Created]); "</pre></td>";
			echo "<td>$row[DatePassed]</td>";
			echo "<td>$row[UserName]</td>";
		echo "</tr>";
		echo "\n\r";
	}
	echo "</table>\n";

//	var_dump($_POST);

	// Block
	$Options = "<option name='BlockGuid value='NULL'>---</option>";
	$SQL = $SQL_QUERY["BlocksAll"];
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$Options .= "<option name='BlockGuid' value='".$row["Guid"]."'>". $row["BlockSerialNo"] . "</option>";
	}
	$Select_Block = "<select name='BlockGuid'>" . $Options . "</select>";
	// Action
	$Options = "<option name='ActionGuid' value='NULL'>---</option>";
	$SQL = $SQL_QUERY["ActionsAll"];
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$Options .= "<option name='ActionGuid' value='".$row["Guid"]."'>". $row["ActionName"] . "    (" . $row["ActionDescription"] .")</option>";
	}
	$Select_Action = "<select name='ActionGuid'>" . $Options . "</select>";
	// Service
	$Options = "<option name='ServiceGuid' value='NULL'>---</option>";
	$SQL = $SQL_QUERY["ServicesAll"];
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$Options .= "<option name='ServiceGuid' value='".$row["Guid"]."'>". $row["ServiceName"] . "</option>";
	}
	$Select_Service = "<select name='ServiceGuid'>" . $Options . "</select>";

	echo "<h3>Add New</h3>
	<p>Первый раз? Воспользуйся услугами консультанта.</p>
	<form action='index.php' id='addtask' name='addtask' method='post'>
	<input type='hidden' name='id' value='new' />
	<table cellspacing='1px' cellpadding='0px'>
		<tr><td>Block</td><td>" . $Select_Block . "</td></tr>
		<tr><td>Action</td><td>" . $Select_Action ."</td></tr>
		<tr><td>Service</td><td>" . $Select_Service . "</td></tr>
		<tr><td>Source</td><td><input name='Source' type='text' value='' size='100' maxlength='200' /><br />
			<b>Send:File only.</b> Абсолютный путь к файлу</td></tr>
		<tr><td>Target</td><td><input name='Target' type='text' value='' size='100' maxlength='200' /><br />
			<b>Send:File only.</b> Имя архива в который запаковать (Если пусто - используется [оригинальное_имя.tar.gz])</td></tr>
		<tr><td>Parameters</td><td><input name='Parameter' type='text' value='' size='100' maxlength='200' /><br />
			<b>Send:Log* only.</b> [yy.mm.dd] (log file date) | [yy.mm.dd-hh:mm-hh:mm] (log file part from-to)</td></tr>
		<tr><td></td><td align='right'><!--a href='index.php' OnClick='Submit();'>Save</a--><button type='Submit' value='Go'>Save</Button></td></tr>
	</table>
	</form>
	<h3>TODO List</h3>
	<ul>
		<li>Редактирование тасок</li>
		<li>Человеческое добавление задач, а не написанное по быстрому</li>
		<li>Добавление нескольких задач за раз ( для нескольких блоков, скачка логов за несколько дней) </li>
		<li>Настоящие девелоперы перенесут в морду Штурмана</li>
		<li>Реализовать поддержку типов тасок Get:File ; Send:LogPart ; Report</li>
		<li>Вписать в иделогию портала: Авторизация, Шаблоны, Динамический html</li>
	</ul>
	";
}

function TaskStatusChange ($id, $State)
{
        global $conn,$SQL_QUERY;

//	echo $State;
        //echo "AAAAA";
	$iState = "00000000-0000-0000-0000-000000000000"; //Uncknown state
	$id = str_remove_sql_char(substr($id, 0, 36));

	$SQL = $SQL_QUERY["StatusesAll"];

	// Get Status Guid from Name
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		//echo $row["StatusId"] . "-";
		if ( strtolower($State) == strtolower($row["StatusId"]) )
		{
			$iState = $row["Guid"];
		}
	}

	//echo $iState . "<br>";
	//echo $id . "<br>";

	$SQL = str_replace("%%2%%", $iState, $SQL_QUERY["UpdateTaskStatus"]);
	$SQL = str_replace("%%1%%", $id, $SQL);
//	echo $SQL;
	MSSQLsiletnQuery($SQL);
}

function UpdateTaskShangedDate($TaskID)
{
	// Обновление даты редактироания таски
	$SQL = "UPDATE [Tasks]
		SET [ChangedDate] = sysdatetime()
		WHERE [ID] = '$TaskID';
		";
	//уcho "<pre>$SQL</pre>";
	$SQL = iconv("UTF-8", "Windows-1251", $SQL);
	MSSQLsiletnQuery($SQL);
}


function BlockActiveTask ($BlockSerialNo)
{
	global $conn,$SQL_QUERY;

	//$SQL = $SQL_AllBlockInstruction . "\nWHERE `b`.`BlockName` = '$BlockSerialNo'";
	$SQL = str_replace("%%1%%", $BlockSerialNo, $SQL_QUERY["iAll_BlockActive"]);

	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		echo "$row[Guid];$row[ActionType];$row[ServiceName];$row[ServiceLogFileName];$row[ServiceLogFileNameArchived];$row[Source];$row[Target];$row[Parameters];";
		echo "\n";
	}

}

function BlockConfirm ($BlockSerialNo, $id, $Result, $Comment, $UploadedFileName)
{
        global $conn,$SQL_QUERY;

        $BlockSerialNo=str_remove_sql_char(substr($BlockSerialNo,0,20));
	$id=str_remove_sql_char(substr($id,0,36));
	$Result=substr($Result,0,10);
	//$Comment=substr(@$_GET["Comment"],0,200);
	$UploadedFileName=str_remove_sql_char(substr($UploadedFileName,0,200));
	$Comment=str_remove_sql_char(substr($Comment,0,100));


	//Get Result Guid from name
        $iResult = "00000000-eeee-0000-0000-000000000000";
        $SQL = $SQL_QUERY["ResultsAll"];
	$stmt = sqlsrv_query( $conn, $SQL );
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		if ( strtolower($Result) == strtolower($row["ResultCode"]) )
		{
			$iResult = $row["Guid"];
		}
	}

	(bool) $InvalidInputData = FALSE;

	if ( $id == "" ) { $InvalidInputData = TRUE; };

	if ( ! $InvalidInputData )
	{
		$SQL = $SQL_QUERY["BlockReport"];

		$SQL = str_replace("%%1%%",$id , $SQL);
		$SQL = str_replace("%%2%%",$iResult , $SQL);
		$SQL = str_replace("%%3%%",$Comment , $SQL);
		$SQL = str_replace("%%4%%",$UploadedFileName , $SQL);
		$SQL = ( $iResult == 'CD999893-AEDF-43CB-9FF5-E4C40A2124B2' ) ? str_replace("%%5%%","F3B22A39-D4C2-45C3-94D1-9545EBCA8D23" , $SQL) : str_replace("%%5%%","DB0D0472-3158-4AD0-8346-60D6AEDE187E" , $SQL); // Done | Error

		//echo $SQL;
		MSSQLsiletnQuery($SQL);
	}
	else
	{
		echo "Invalid Input Data";
	}

	echo "<pre>";
	echo "BlockSerialNo: [$BlockSerialNo]\n";
	echo "Task ID: [$id]\n";
	echo "Result: [$Result]\n";
	echo "iResult: [$iResult]\n";
	echo "Comment: [$Comment]\n";
	echo "UploadedFileName: [$UploadedFileName]\n";
	echo "</pre>";

}

function Get_UserGuid($username)
{
	global $conn;

	$UserGuid = "";

	$username = str_remove_sql_char($username);
	$SQL = "SELECT [Guid], [UserName] FROM [Users] WHERE [UserName] = '$username'";
//	echo "<pre>$SQL</pre>";
//exit;
	$stmt = sqlsrv_query( $conn, $SQL );
	if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}
	$UserGuid = "";
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	//	$Options .= "<option name='ServiceGuid' value='".$row["Guid"]."'>". $row["ServiceName"] . "</option>";
		$UserGuid = @$row["Guid"];
	}

	if ( $UserGuid == "" )
	{
		// Добавляем пользователя если его нет.
		$SQL = "INSERT INTO [Users] ( [UserName], [UserDisplayName], [Is_Active])
			     VALUES ('$username','$username',1)";
		MSSQLsiletnQuery($SQL);

		// Повторно вызываем для получения гуида для нового пользователя
		$UserGuid = Get_UserGuid($username);
	}
	return $UserGuid;
}

function AddTask($p_id, $BlockGuid, $ActionGuid, $ServiceGuid, $Source, $Target, $Parameter, $task_owner)
{
	global $conn,$user,$SQL_QUERY;

	$p_id = (strlen($p_id) != 36 ) ? "00000000-0000-0000-0000-000000000000" : $p_id;
	$BlockGuid = (strlen($BlockGuid) != 36 ) ? "00000000-0000-0000-0000-000000000000" : $BlockGuid;
	$ActionGuid = (strlen($ActionGuid) != 36 ) ? "00000000-0000-0000-0000-000000000000" : $ActionGuid;

	
	$UserGuid = Get_UserGuid($task_owner);
//	echo "task_owner [$task_owner]<br /> $UserGuid [$UserGuid]"; exit;
	
	$SQL = $SQL_QUERY["AddTask"];
	$SQL = str_replace("%%1%%",$p_id , $SQL);
	$SQL = str_replace("%%2%%",$BlockGuid , $SQL);
	$SQL = str_replace("%%3%%",$ActionGuid , $SQL);
	$SQL = ( strlen($ServiceGuid) != 36 ) ? str_replace("%%4%%", "NULL", $SQL) : str_replace("%%4%%", "'$ServiceGuid'", $SQL);
	$SQL = ( $Source == "" ) ? str_replace("%%5%%", "NULL", $SQL) : str_replace("%%5%%", "'$Source'", $SQL);
	$SQL = ( $Target == "" ) ? str_replace("%%6%%", "NULL", $SQL) : str_replace("%%6%%", "'$Target'", $SQL);
	$SQL = ( $Parameter == "" ) ? str_replace("%%7%%", "NULL", $SQL) : str_replace("%%7%%", "'$Parameter'", $SQL);
	$SQL = str_replace("%%8%%",$UserGuid, $SQL);
//	echo "<pre>$SQL</pre>";
//exit;
	MSSQLsiletnQuery($SQL);

}

function ParseValue($data)
{
	// Парсинг значений для Статистики работы серверов
	$ret = Array();

	$ret["Value"] = "N/A";
	$ret["OwerLimit"] = -1;

//	$BlockSerialNo = $data["BlockSerialNo"];
	$GroupCode = $data["GroupCode"];
//	$GroupName = $data["GroupName"];
//	$GroupDesc = $data["GroupDesc"];
	$MetricCode = $data["MetricCode"];
//	$MetricName = $data["MetricName"];
//	$MetricDesc = $data["MetricDesc"];
//	$MetricObjGuid = $data["MetricObjGuid"];
	$MetricLimit = $data["MetricLimit"];
//	$MetricLimit = 5000;
	$value = $data["value"];
//	$Server_Guid = $data["Server_Guid"];
//	$MetricGroup_Guid = $data["MetricGroup_Guid"];
//	$Metric_Guid = $data["Metric_Guid"];
//	$WriteDate = $data["WriteDate"];
//	$WriteDateF = $data["WriteDateF"];
//	$MinutesAgo = $data["MinutesAgo"];


	$ret["OwerLimit"] = ( (int)$value > (int)$MetricLimit ) ? 1 : 0;
	$ret["Value"] = $value;
//	$ret["TimeLimit"] = ( $data["MinutesAgo"] ) ? 1 : 0;
	
	if ( $GroupCode == "Queue" )
	{
		$ret["Value"] = (round((int) $value / 1024,0));
	}
	elseif ( $GroupCode == "Frames" or $GroupCode == "Messages")
	{
		$ret["Value"] = $value;
	}
	elseif ( $GroupCode == "LogActivity" )
	{
		$res = ( (int)$value < 60 ) ? round((int)$value, 0) . "s" : "";
		$res = ( (int)$value > 59 and (int)$value < 3600) ? round((int)$value / 60, 0) . "m" : $res;
		$res = ( (int)$value > 3599 ) ? round((int)$value / 3600, 0) . "h" : $res;
		$res = ( $value == "N/A" ) ? $value : $res;
		$ret["Value"] = $res;
	}
	elseif ( $GroupCode == "Resource" )
	{
		$valList = array();
		$valList = explode(":", $value);
		$res = "";
		for ($vi=0; $vi < Count($valList); $vi++)
		{
			$nv = array();
			$nv = explode("=", $valList[$vi]);
//			var_dump ($nv);
			switch ($nv[0])
			{
				case "Handles":
					$res .= ( (int)$nv[1] > 2000 ) ? "$valList[$vi]<br>" : "";
//					$res .= ( (int)$nv[1] > 400 ) ? "$valList[$vi]<br>" : "";
					$ret["OwerLimit"] = ( (int)$nv[1] > 2000 ) ? 1 : $ret["OwerLimit"] ;
					break;
				case "Threads":
					$res .= ( (int)$nv[1] > 200 ) ? "$valList[$vi]<br>" : "";
					$ret["OwerLimit"] = ( (int)$nv[1] > 200 ) ? 1 : $ret["OwerLimit"] ;
					break;
				case "PM":
					$res .= ( (int)$nv[1] > 500000000 ) ? "$nv[0]=". round(((int)$nv[1]/1024/1024),0) ."Mb<br>" : "";
					$ret["OwerLimit"] = ( (int)$nv[1] > 500000000 ) ? 1 : $ret["OwerLimit"] ;
					break;
				case "VM":
					$res .= ( (int)$nv[1] > 500000000 ) ? "$nv[0]=". round(((int)$nv[1]/1024/1024),0) ."Mb<br>" : "";
					$ret["OwerLimit"] = ( (int)$nv[1] > 500000000 ) ? 1 : $ret["OwerLimit"] ;
					break;
				case "WS":
					$res .= ( (int)$nv[1] > 500000000 ) ? "$nv[0]=". round(((int)$nv[1]/1024/1024),0) ."Mb<br>" : "";
					$ret["OwerLimit"] = ( (int)$nv[1] > 500000000 ) ? 1 : $ret["OwerLimit"] ;
					break;
				case "CPU":
					$res .= ( (int)$nv[1] > 500000000 ) ? "$nv[0]=". round(((int)$nv[1]/1024/1024),0) ."Mb<br>" : "";
					$ret["OwerLimit"] = ( (int)$nv[1] > 500000000 ) ? 1 : $ret["OwerLimit"] ;
					break;
			}
		}
		$ret["Value"] = ( strlen($res) > 0 ) ? $res : "&nbsp;-&nbsp;";

	}
	elseif ( $GroupCode == "HDD" )
	{
		$ProblemExist = FALSE;
		$valList = array();
		$valList = explode(":", $value);
		$res = "";
		for ($vi=0; $vi < Count($valList); $vi++)
		{
			$nv = array();
			$nv = explode("=", $valList[$vi]);
//			var_dump ($nv);
			switch ($nv[0])
			{
				case "FreeSpace":
					$res .= round((int)$nv[1]/1024/1024/1024,0);
					$ProblemExist = ( (int)$nv[1] < (int)$MetricLimit ) ? TRUE : $ProblemExist;
					break;
				case "Size":
//					$res .= ( (int)$nv[1] > 1 ) ? "$valList[$vi]<br>" : "";
//					$ProblemExist = ( (int)$nv[1] > 1 ) ? $TRUE : $ProblemExist;
					break;
				case "VolumeName":
//					$res .= ( $nv[1] ) ? "$nv[0]=$nv[1]<br>" : "";
//					$ProblemExist = ( (int)$nv[1] > 1 ) ? $TRUE : $ProblemExist;
					break;
			}
		}
		$ret["OwerLimit"] = ( $ProblemExist ) ? 1 : 0;
		$ret["Value"] = $res;
	}
	elseif ( $GroupCode == "Errors" )
	{
		if ( $MetricCode == "Cnt" )
		{
			$res = $value;	
		}
		elseif ( $MetricCode == "Files" )
		{
			$res = "";
			$res = str_replace(":", "<br />", $value);
			$res = str_replace("Server.exe", "", $res);
			$res = str_replace(".Error", "", $res);
		}
		$ret["Value"] = "</a>$res<a>";
		$ret["OwerLimit"] = 0;

	}
	elseif ( $GroupCode == "SQL" )
	{
		if ( in_array($MetricCode, array("LastBOINormsCalculated", "LastRR", "LastBOIParams", "LastFOSStage", "LastFOSDriverAlert", 
						"LastAlarm", "LastDriverOnVehicle", "LastMathStatFOSState", "LastMathStatOnVehicle", "LastMathStatValidRRs")) )
		{
			$res = ( (int)$value < 60 ) ? round((int)$value, 0) . "s" : "";
			$res = ( (int)$value > 59 and (int)$value < 3600) ? round((int)$value / 60, 0) . "m" : $res;
			$res = ( (int)$value > 3599 ) ? round((int)$value / 3600, 0) . "h" : $res;
			$res = ( $value == "N/A" ) ? $value : $res;
			$ret["Value"] = $res;
		}
		elseif ( in_array($MetricCode, array("ConnectedBlocks","HIDConnected")) )
		{
			// Invert Check
			$ret["OwerLimit"] = ( $value <= $MetricLimit) ? 1 : 0;
//			$ret["Value"] = "$value - $MetricLimit";

		}
		elseif ( in_array($MetricCode, array("LastMathStat", "MathStat")) )
		{
			$ret["OwerLimit"] = ( $value == $MetricLimit) ? 0 : 1;
			$ret["Value"] = ( $value == "1" ) ? "Ok" : "FAILED";
		}
/*		else 
		{
			$ret["OwerLimit"] = 0;
			$ret["Value"] = "sss";
		}
*/
	}

	return $ret;
}

function style_by_val($Value, $direction = "DOWN", $Scale_Up = 10, $Scale_Dn = 10, $Color = "red")
{
	global $style;
	
	$diff = TRUE;
	
	$Style_item = ( $diff and $Value >= -10 and $Value <= 10 ) ? "color: darkgrey;" : "";
	
	if ( strtoupper($direction) == "DOWN" or strtoupper($direction) == "BOTH" )
	{
		$Style_item = ( $diff and $Value < -1*$Scale_Dn ) ? $style["bg-lllll-$Color"] : $Style_item;
		$Style_item = ( $diff and $Value < -2*$Scale_Dn ) ? $style["bg-llll-$Color"] : $Style_item;
		$Style_item = ( $diff and $Value < -3*$Scale_Dn ) ? $style["bg-lll-$Color"] : $Style_item;
		$Style_item = ( $diff and $Value < -4*$Scale_Dn ) ? $style["bg-ll-$Color"] : $Style_item;
		$Style_item = ( $diff and $Value < -5*$Scale_Dn ) ? $style["bg-l-$Color"] : $Style_item;
		$Style_item = ( $diff and $Value < -6*$Scale_Dn ) ? $style["bg-$Color"] : $Style_item;
	}
	if ( strtoupper($direction) == "UP" or strtoupper($direction) == "BOTH" )
	{
		$Style_item = ( $diff and $Value > 1*$Scale_Up ) ? $style["bg-lllll-$Color"] : $Style_item;
		$Style_item = ( $diff and $Value > 2*$Scale_Up ) ? $style["bg-llll-$Color"] : $Style_item;
		$Style_item = ( $diff and $Value > 3*$Scale_Up ) ? $style["bg-lll-$Color"] : $Style_item;
		$Style_item = ( $diff and $Value > 4*$Scale_Up ) ? $style["bg-ll-$Color"] : $Style_item;
		$Style_item = ( $diff and $Value > 5*$Scale_Up ) ? $style["bg-l-$Color"] : $Style_item;
		$Style_item = ( $diff and $Value > 6*$Scale_Up ) ? $style["bg-$Color"] : $Style_item;
	}
	$Style_item = ( !$diff ) ? "color: darkgrey;" : $Style_item;

	return $Style_item;
}


function check_config_property($Config, $Name, $Value, $Group_Id, $Description)
{
	// Проверка Существования конфигурационной проперти и создание ее если ее нет.
	global $CONFIG_SHTURMAN;
	// Check exuisting property in board configuration 
	if ( strtoupper($Config) == "SHTURMAN" ) 
	{
		if ( !isset($CONFIG_SHTURMAN[$Name]) )
		{
			$conn_cfg = MSSQLconnect( "Config", "Config" );

			$SQL = "
INSERT INTO [dbo].[Config]
	([Name],[Value],[Group_Id],[Description])
VALUES
	('$Name','$Value','$Group_Id','$Description')
";
			//echo "<pre>$SQL</pre>";
			//MSSQLsiletnQuery($SQL);
			$stmt = sqlsrv_query( $conn_cfg, $SQL );
			if( $stmt === false ) {die( print_r( sqlsrv_errors(), true));}

			sqlsrv_close($conn_cfg) ;
			
			echo "<pre>DBG: Added [$Name] to Configuration table</pre>";
		}
	}
}

?>