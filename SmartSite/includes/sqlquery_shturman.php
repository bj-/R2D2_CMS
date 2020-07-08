<?php

$SQL_QUERY["ServerDiagInfo"] = "
/**** Servers Diag Info ****/
SELECT
 [s].[BlockSerialNo],
-- [st].[stName] AS [SrvTypeName],
-- [st].[stShortName] AS [SrvTypeCode],
 [mg].[Code] AS [GroupCode],
 [mg].[Name] AS [GroupName],
 [mg].[Description] AS [GroupDesc],
 [m].[Code] AS [MetricCode],
 [m].[Name] AS [MetricName],
 [m].[Description] AS [MetricDesc],
 [m].[ObjectGuid] AS [MetricObjGuid],
 [m].[Limit] AS [MetricLimit],
 [d].[value],
 [d].[Server_Guid],
 [d].[MetricGroup_Guid],
 [d].[Metric_Guid],
 [d].[WriteDate] AS [WriteDate],
 FORMAT([d].[WriteDate], 'dd.MM.yyy HH:mm') AS [WriteDateF],
 DATEDIFF(MINUTE, [d].[WriteDate], sysutcdatetime()) AS [MinutesAgo]
FROM 
 (
 SELECT [Server_Guid], [MetricGroup_Guid], [Metric_Guid], MAX([Writedate]) AS [MaxWriteDate] 
 FROM [DiagData] 
 GROUP BY [Server_Guid], [MetricGroup_Guid], [Metric_Guid]
 ) AS [DatesList]
 INNER JOIN [MetricGroups] AS [mg] ON [mg].[Guid] = [DatesList].[MetricGroup_Guid]
 INNER JOIN [Metrics] AS [m] ON [m].[Guid] = [DatesList].[Metric_Guid]
 INNER JOIN [Servers] AS [s] ON [s].[Guid] = [DatesList].[Server_Guid]
 INNER JOIN [Server_Types] AS [st] ON [st].[Guid] = [s].[ServerType_Guid]
 INNER JOIN [DiagData] AS [d]   on
 [d].[Metric_Guid] = [DatesList].[Metric_Guid]
 AND [d].[Server_Guid] = [DatesList].[Server_Guid]
 AND [d].[MetricGroup_Guid] = [DatesList].[MetricGroup_Guid]
 AND [d].[WriteDate] = [DatesList].[MaxWriteDate]

WHERE 
NOT ([m].[Guid] IN ('9EC8E66F-FCEC-4609-AED3-F670D041D143','BB9B6AC8-6281-4E7B-8E27-1D9F346A9C2C') AND [s].[Guid] = '9D13BD19-FD39-448D-B5E4-761E83824CEA')

ORDER BY [mg].[Order] ASC, [m].[Name] ASC, [s].[OrderNo] ASC

OPTION (FORCE ORDER)
";

$SQL_QUERY["BlockLogErrors"] = "
/*    *.Error ы возникшие на блоках    */
SELECT 
	[ble].[id],
	[s].[BlockSerialNo],
	[sv].[ServiceName],
	[sv].[ServiceShortName],
	[ble].[DateTime],
	FORMAT([ble].[DateTime], 'dd.MM.yyy') AS [Date],
	FORMAT([ble].[DateTime], 'hh.mm.ss') AS [Time],
	DATEDIFF(hour,[ble].[DateTime], SYSDATETIME()) AS [TimeAgo],
	[ble].[ErrorText],
	[ble].[LogPartAllFiles],
	[ble].[LogPartFile],
	[ble].[Active],
	[ble].[WriteDate]
FROM [BlockLogErrors] AS [ble]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [ble].[Server_Guid]
INNER JOIN [Services] AS [sv] ON [sv].[Guid] = [ble].[Service_Guid]
WHERE 1=1
	AND [ble].[DateTime] > DATEADD(hour,-72,SYSDATETIME())
ORDER BY 
	[s].[BlockSerialNo] ASC,
	[sv].[ServiceName] ASC,
	[ble].[DateTime] DESC
";


$SQL_QUERY["StatusesAll"] = "
/****** All Statuses  ******/
SELECT 
	[Guid],
	[StatusId],
	[StatusName]
FROM [Statuses]
";

$SQL_QUERY["BlocksAll"] = "
/****** All Blocks  ******/
SELECT 
	[Guid],
	[BlockSerialNo],
	[Is_Connected],
	[IpAddress]
FROM [Servers]
WHERE [BlockSerialNo] LIKE 'STB%'
ORDER BY [BlockSerialNo] ASC
";

$SQL_QUERY["ServicesAll"] = "
/****** All Services  ******/
SELECT 
	[Guid],
	[ServiceName],
	[ServiceLogFileName],
	[ServiceLogFileNameArchived],
	[ServiceShortName]
FROM [Services]
";


$SQL_QUERY["ResultsAll"] = "
/****** All Results ******/
SELECT 
	[Guid],
	[ResultCode],
	[ResultName]
FROM [Results]
";

$SQL_QUERY["MetricGroupsAll"] = "
/****** All MetricGroups ******/
SELECT 
	[Guid],
        [Code],
        [Name],
        [Description]
FROM [MetricGroups]
";

$SQL_QUERY["MetricsAll"] = "
/****** All Metrics ******/
SELECT 
	[Guid],
	[GroupGuid],
        [ObjectGuid],
        [Code],
        [Name],
        [Description]
FROM [Metrics]
";


$SQL_QUERY["ServersAll"] = "
/****** All Servers ******/
SELECT
	[Guid],
	[BlockSerialNo],
	[Is_Connected],
	[IpAddress],
	[ServerType_Guid],
	[IpAddressInternal] 
FROM [Servers]
";

$SQL_QUERY["ServersInDiagAll_old"] = "
/****** All Actual Servers in DiagTable ******/
SELECT [s].[BlockSerialNo] 
FROM 
	[DiagData] AS [d] 
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [d].[Server_Guid] 
GROUP BY [s].[BlockSerialNo]
";

$SQL_QUERY["ServersInDiagAll"] = "
/****** All Actual Servers in DiagTable ******/
SELECT [s].[BlockSerialNo]
FROM 
	[DiagData] AS [d] 
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [d].[Server_Guid] 
GROUP BY [s].[BlockSerialNo], [s].[OrderNo]
ORDER BY [s].[OrderNo]
";



$SQL_QUERY["AddTask"] = "
INSERT INTO [Instructions]
	([TaskStatus_Guid],[Server_Guid],[Action_Guid],[BlockService_Guid],[Source],[Target],[Parameters],[User_Guid])
VALUES
	('%%1%%','%%2%%','%%3%%',%%4%%,%%5%%,%%6%%,%%7%%,'%%8%%')
";

$SQL_QUERY["InsertDiagData"] = "INSERT INTO [DiagData] ([Server_Guid],[MetricGroup_Guid],[Metric_Guid],[Value]) VALUES ";

$SQL_QUERY["InsertBlocksLogData"] = "INSERT INTO [dbo].[BlocksLog] ([ServerGuid],[Date],[Count],[Type],[Code],[Device],[Text]) VALUES ";


$SQL_QUERY["UpdateTaskStatus"] = "UPDATE [Instructions] SET [TaskStatus_Guid] = '%%2%%', [Changed] = sysdatetime() WHERE [Guid] = '%%1%%'";
//$SQL_QUERY["UpdateTaskStatus"] = "UPDATE [Instructions] SET [TaskStatus_Guid] = '%%2%%' WHERE [Guid] = '%%1%%'";
$SQL_QUERY["BlockReport"] = "UPDATE [Instructions] SET [Block_Result_Guid] = '%%2%%', BlockComment = '%%3%%', UploadedFile = '%%4%%', [TaskStatus_Guid] = '%%5%%', [DatePassed] = sysdatetime(), [Changed] = sysdatetime()  WHERE [Guid] = '%%1%%'";




////=======================


$SQL_QUERY["iAll"] = "
/****** All Instructions  ******/
SELECT 
	[i].[Guid],
	[st].[StatusId],
	[st].[StatusName],
	[a].[ActionName],
	[a].[ActionType],
	[s].[BlockSerialNo],
	[sc].[ServiceName],
	[sc].[ServiceShortName],
	[sc].[ServiceLogFileName],
	[sc].[ServiceLogFileNameArchived],
	[i].[Source],
	[i].[Target],
	[i].[Parameters],
	[r].[ResultName],
	[i].[BlockComment],
	[i].[UploadedFile],
	FORMAT(DATEADD(hour, +3, [i].[Created]), 'dd.MM HH:mm') AS [Created],
--	[i].[Created],
	FORMAT([i].[DatePassed], 'dd.MM HH:mm') AS [DatePassed],
--	[i].[DatePassed],
	FORMAT([i].[Changed], 'dd.MM.yyy HH:mm') AS [DateChanged],
	DATEDIFF(DAY, [i].[Changed], sysdatetime()) AS [DaysAgo],
	[i].[Changed],
	[u].[UserName]

FROM [Instructions] AS [i]
	INNER JOIN [Statuses] AS [st] ON [st].[Guid] = [i].[TaskStatus_Guid]
	INNER JOIN [Servers] AS [s] ON [s].[Guid] = [i].[Server_Guid]
	INNER JOIN [Actions] AS [a] ON [a].[Guid] = [i].[Action_Guid]
	LEFT JOIN [Users] AS [u] ON [u].[Guid] = [i].[User_Guid]
	LEFT JOIN [Services] AS [sc] ON [sc].[Guid] = [i].[BlockService_Guid]
	LEFT JOIN [Results] AS [r] ON [r].[Guid] = [i].[Block_Result_Guid]
";



$SQL_QUERY["ServerDiagInfo"] = "
/**** Servers Diag Info ****/
SELECT
 [s].[BlockSerialNo],
-- [st].[stName] AS [SrvTypeName],
-- [st].[stShortName] AS [SrvTypeCode],
 [mg].[Code] AS [GroupCode],
 [mg].[Name] AS [GroupName],
 [mg].[Description] AS [GroupDesc],
 [m].[Code] AS [MetricCode],
 [m].[Name] AS [MetricName],
 [m].[Description] AS [MetricDesc],
 [m].[ObjectGuid] AS [MetricObjGuid],
 [m].[Limit] AS [MetricLimit],
 [d].[value],
 [d].[Server_Guid],
 [d].[MetricGroup_Guid],
 [d].[Metric_Guid],
 [d].[WriteDate] AS [WriteDate],
 FORMAT([d].[WriteDate], 'dd.MM.yyy HH:mm') AS [WriteDateF],
 DATEDIFF(MINUTE, [d].[WriteDate], sysutcdatetime()) AS [MinutesAgo]
FROM 
 (
 SELECT [Server_Guid], [MetricGroup_Guid], [Metric_Guid], MAX([Writedate]) AS [MaxWriteDate] 
 FROM [DiagData] 
 GROUP BY [Server_Guid], [MetricGroup_Guid], [Metric_Guid]
 ) AS [DatesList]
 INNER JOIN [MetricGroups] AS [mg] ON [mg].[Guid] = [DatesList].[MetricGroup_Guid]
 INNER JOIN [Metrics] AS [m] ON [m].[Guid] = [DatesList].[Metric_Guid]
 INNER JOIN [Servers] AS [s] ON [s].[Guid] = [DatesList].[Server_Guid]
 INNER JOIN [Server_Types] AS [st] ON [st].[Guid] = [s].[ServerType_Guid]
 INNER JOIN [DiagData] AS [d]   on
 [d].[Metric_Guid] = [DatesList].[Metric_Guid]
 AND [d].[Server_Guid] = [DatesList].[Server_Guid]
 AND [d].[MetricGroup_Guid] = [DatesList].[MetricGroup_Guid]
 AND [d].[WriteDate] = [DatesList].[MaxWriteDate]

WHERE 
NOT ([m].[Guid] IN ('9EC8E66F-FCEC-4609-AED3-F670D041D143','BB9B6AC8-6281-4E7B-8E27-1D9F346A9C2C') AND [s].[Guid] = '9D13BD19-FD39-448D-B5E4-761E83824CEA')

ORDER BY [mg].[Order] ASC, [m].[Name] ASC, [s].[OrderNo] ASC

OPTION (FORCE ORDER)
";

$SQL_QUERY["BlockLogErrorsShort"] = "
/*    *.Error ы возникшие на блоках    */
SELECT 
	--[ble].[id],
	[s].[BlockSerialNo],
	[sv].[ServiceName],
	--[sv].[ServiceShortName],
	--[ble].[DateTime],
	--FORMAT([ble].[DateTime], 'dd.MM.yyy') AS [Date],
	--FORMAT([ble].[DateTime], 'HH.mm.ss') AS [Time],
	--MIN(DATEDIFF(hour,[ble].[DateTime], SYSDATETIME())) AS [TimeAgo],
	--[ble].[ErrorText],
	--[ble].[LogPartAllFiles],
	--[ble].[LogPartFile],
	--[ble].[Active],
	--[ble].[WriteDate]
	count(*) AS [TotalCnt]
FROM [BlockLogErrors] AS [ble]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [ble].[Server_Guid]
INNER JOIN [Services] AS [sv] ON [sv].[Guid] = [ble].[Service_Guid]
WHERE 1=1
	AND [ble].[DateTime] > DATEADD(hour,-72,SYSDATETIME())
GROUP BY
	[s].[BlockSerialNo],
	[sv].[ServiceName]
	--[sv].[ServiceShortName]
ORDER BY 
	[s].[BlockSerialNo] ASC,
	[sv].[ServiceName] ASC
	--[sv].[ServiceShortName] ASC
	--[ble].[DateTime] DESC
";


$SQL_QUERY["BlockLogErrors"] = "
/****** Script for SelectTopNRows command from SSMS  ******/
  SELECT 
	[ble].[id],
	[s].[BlockSerialNo],
	[sv].[ServiceName],
	[sv].[ServiceShortName],
	--[ble].[DateTime],
	FORMAT([ble].[DateTime], 'dd.MM.yyy') AS [Date],
	FORMAT([ble].[DateTime], 'HH.mm.ss') AS [Time],
	--MIN(DATEDIFF(hour,[ble].[DateTime], SYSDATETIME())) AS [TimeAgo],
	[ble].[ErrorText],
	[ble].[LogPartAllFiles],
	[ble].[LogPartFile],
	--[ble].[Active],
	[ble].[WriteDate]
	--count(*) AS [TotalCnt]
FROM [BlockLogErrors] AS [ble]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [ble].[Server_Guid]
INNER JOIN [Services] AS [sv] ON [sv].[Guid] = [ble].[Service_Guid]
WHERE 1=1
	%%1%%
	--AND [ble].[DateTime] > DATEADD(hour,-72,SYSDATETIME()) AND [s].[BlockSerialNo] = 'STB01154'
	--AND [ble].[DateTime] > DATEADD(hour,-72,SYSDATETIME())
--GROUP BY
	--[s].[BlockSerialNo],
	--[sv].[ServiceName]
	--[sv].[ServiceShortName]
ORDER BY 
	[s].[BlockSerialNo] ASC,
	--[sv].[ServiceName] ASC
	--[sv].[ServiceShortName] ASC
	[ble].[DateTime] DESC
";

$SQL_QUERY["BlockLogErrorsBySrvcStat"] = "
/* Stat: Exeptions by services and blocks */
  SELECT 
	[sv].[ServiceShortName],
	[sv].[ServiceName],
	[s].[BlockSerialNo],
	count(*) AS [TotalCnt]
FROM [BlockLogErrors] AS [ble]
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [ble].[Server_Guid]
INNER JOIN [Services] AS [sv] ON [sv].[Guid] = [ble].[Service_Guid]
WHERE 1=1
	%%1%%
GROUP BY
	[sv].[ServiceShortName],
	[sv].[ServiceName],
	[s].[BlockSerialNo]
ORDER BY 
	[sv].[ServiceShortName] ASC,
	[sv].[ServiceName] ASC,
	[s].[BlockSerialNo] ASC
";

$SQL_QUERY["StatusesAll"] = "
/****** All Statuses  ******/
SELECT 
	[Guid],
	[StatusId],
	[StatusName]
FROM [Statuses]
";

$SQL_QUERY["ActionsAll"] = "
/****** All Actions  ******/
SELECT 
	[Guid],
	[ActionType],
	[ActionName],
	[ActionDescription],
	[ActionDirection]
FROM [Actions]
";

$SQL_QUERY["ServicesAll"] = "
/****** All Services  ******/
SELECT 
	[Guid],
	[ServiceName],
	[ServiceLogFileName],
	[ServiceLogFileNameArchived],
	[ServiceShortName]
FROM [Services]
";


$SQL_QUERY["ResultsAll"] = "
/****** All Results ******/
SELECT 
	[Guid],
	[ResultCode],
	[ResultName]
FROM [Results]
";

$SQL_QUERY["MetricGroupsAll"] = "
/****** All MetricGroups ******/
SELECT 
	[Guid],
        [Code],
        [Name],
        [Description]
FROM [MetricGroups]
";

$SQL_QUERY["MetricsAll"] = "
/****** All Metrics ******/
SELECT 
	[Guid],
	[GroupGuid],
        [ObjectGuid],
        [Code],
        [Name],
        [Description]
FROM [Metrics]
";


$SQL_QUERY["ServersAll"] = "
/****** All Servers ******/
SELECT
	[Guid],
	[BlockSerialNo],
	[Is_Connected],
	[IpAddress],
	[ServerType_Guid],
	[IpAddressInternal] 
FROM [Servers]
";

$SQL_QUERY["ServersInDiagAll_old"] = "
/****** All Actual Servers in DiagTable ******/
SELECT [s].[BlockSerialNo] 
FROM 
	[DiagData] AS [d] 
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [d].[Server_Guid] 
GROUP BY [s].[BlockSerialNo]
";

$SQL_QUERY["ServersInDiagAll"] = "
/****** All Actual Servers in DiagTable ******/
SELECT [s].[BlockSerialNo]
FROM 
	[DiagData] AS [d] 
INNER JOIN [Servers] AS [s] ON [s].[Guid] = [d].[Server_Guid] 
GROUP BY [s].[BlockSerialNo], [s].[OrderNo]
ORDER BY [s].[OrderNo]
";

$SQL_QUERY["InsertDiagData"] = "INSERT INTO [DiagData] ([Server_Guid],[MetricGroup_Guid],[Metric_Guid],[Value]) VALUES ";

$SQL_QUERY["InsertBlocksLogData"] = "INSERT INTO [dbo].[BlocksLog] ([ServerGuid],[Date],[Count],[Type],[Code],[Device],[Text]) VALUES ";


$SQL_QUERY["UpdateTaskStatus"] = "UPDATE [Instructions] SET [TaskStatus_Guid] = '%%2%%', [Changed] = sysdatetime() WHERE [Guid] = '%%1%%'";
//$SQL_QUERY["UpdateTaskStatus"] = "UPDATE [Instructions] SET [TaskStatus_Guid] = '%%2%%' WHERE [Guid] = '%%1%%'";
$SQL_QUERY["BlockReport"] = "UPDATE [Instructions] SET [Block_Result_Guid] = '%%2%%', BlockComment = '%%3%%', UploadedFile = '%%4%%', [TaskStatus_Guid] = '%%5%%', [DatePassed] = sysdatetime(), [Changed] = sysdatetime()  WHERE [Guid] = '%%1%%'";


$SQL_QUERY["iAll_Actual"] = $SQL_QUERY["iAll"] . "\nWHERE ([i].[DatePassed] > DATEADD(day, -30, GETDATE()) OR [i].[Created] > DATEADD(day, -30, GETDATE()) OR [i].[DatePassed] IS NULL) AND ([st].[StatusId] not in ('closed', 'deleted') OR ( DATEDIFF(DAY, [i].[Changed], sysdatetime()) < 10 AND [st].[StatusId] in ('closed', 'deleted'))) \n";
$SQL_QUERY["iAll_Active"] = $SQL_QUERY["iAll"] . "\nWHERE [st].[StatusId] in ('active') \n";
$SQL_QUERY["iAll_BlockActive"] = $SQL_QUERY["iAll"] . "\nWHERE [st].[StatusId] in ('active') AND [s].[BlockSerialNo] = '%%1%%'";
$SQL_QUERY["iAll_BlockActual"] = $SQL_QUERY["iAll_Actual"] . "\nAND [s].[BlockSerialNo] = '%%1%%'";

//$SQL_QUERY["iAll_Added"] = $SQL_QUERY["iAll_Actual"] . "\n AND [i].[Created] > DATEADD(minute, -1000, sysdatetime()) \n";
$SQL_QUERY["iAll_Added"] = $SQL_QUERY["iAll_Actual"] . "\n AND [i].[Created] > DATEADD(minute, -1, GETUTCDATE()) ORDER BY [s].[BlockSerialNo] ASC, [sc].[ServiceName] ASC, [i].[Parameters] ASC, [i].[Created] DESC\n";

$SQL_QUERY["Persons"] = "
/****** Persons Data  ******/
SELECT 
	[u].[Guid],
	[p].[Guid] AS [Person_Guid],
	[sn].[Guid] AS [Sensor_Guid],
	[p].[Last_Name],
	[p].[First_Name],
	[p].[Middle_Name],
	[r].[Code] AS [Role_Code],
	[r].[Name] AS [Role],
	[ust].[Name] AS [User_State], 
	[ust].[Code] AS [User_State_Code], 
	[u].[Users_States_Changed],
	[u].[Created] AS [Registered],
	[u].[Deleted] AS [Fired],
	[sn].[SerialNo] AS [HID],
	[sn].[Battery_Level],
	[sn].[MacAddress] AS [HID_MAC_Address],
	[sn].[FW_Version] AS [HID_FW],
	FORMAT([sn].[Activity], 'dd.MM.yy') AS [LastActivityDate],
	FORMAT([sn].[Activity], 'hh:mm') AS [LastActivityTime],
	DATEDIFF(day,[sn].[Activity], GETUTCDATE()) AS [LastActivityDaysAgo],
	[sn].[Is_Connected] AS [HID_Connected],
	[s].[Alias] AS [BlockSerialNo],
	[v].[Name] AS [Wagon],
	[cpl].[Name] AS [Train],
	[stt].[Name] AS [StationName],
	[ln].[Name] AS [LineName],
	[ln].[Line_Number] AS [LineNum],
	[v].[WayNo],
	[u].[Vehicles_Changed],
	FORMAT([p].[Created], 'dd.MM.yyy HH:mm:ss') AS [Person_Created],
	FORMAT([p].[Written], 'dd.MM.yyy HH:mm:ss') AS [Person_Written],
	FORMAT([u].[Created], 'dd.MM.yyy HH:mm:ss') AS [User_Created],
	FORMAT([u].[Written], 'dd.MM.yyy HH:mm:ss') AS [User_Written]
FROM [Users] AS [u]
INNER JOIN [Users_Roles] AS [r] ON [r].[Guid] = [u].[Users_Roles_Guid]
INNER JOIN [Users_Persons] AS [p] ON [p].[Guid] = [u].[Users_Persons_Guid]
LEFT JOIN [Users_States] AS [ust] ON [ust].[Id] = [u].[Users_States_Id]
LEFT JOIN [Vehicles] AS [v] ON [v].[Guid] = [u].[Vehicles_Guid]
LEFT JOIN [Couplings] AS [cpl] ON [cpl].[Guid] = [v].[Couplings_Guid]
LEFT JOIN [Stations] AS [stt] ON [stt].[Guid] = [v].[Stations_Guid]
LEFT JOIN [Lines] AS [ln] ON [ln].[Guid] = [stt].[Lines_Guid]
LEFT JOIN [Sensors_Cardio] AS [sc] ON [sc].[Users_Guid] = [u].[Guid]
LEFT JOIN [Sensors] AS [sn] ON [sn].[Guid] = [sc].[Guid]
LEFT JOIN [Servers] AS [s] ON [s].[Guid] = [sc].[Servers_Guid]
WHERE
	[r].[Code] IN ( 'Driver', 'Worker' )
	--[u].[Users_Roles_Guid] IN ('89270CE1-E42F-4724-8FAD-346252F34FC3', 'F1970705-647B-4C68-AAB8-3E73CD5FD7C6')
";

$SQL_QUERY["Persons_NormsPers"] =
"
/****** Нормы Групповые и индивидуальные за период  ******/
SELECT TOP 1000 
	CONCAT([p].[Last_Name], ' ', [p].[First_Name], ' ', [p].[Middle_Name]) AS [FIO],
	FORMAT( [bn].[Calculated], 'dd.MM.yyyy') AS [Calculated],
	[bn].[RR_MD_Min],
	[bn].[RR_MD_Max],
	[bn].[RR_MD_Avg],
	[bn].[RR_AM_Min],
	[bn].[RR_AM_Max],
	[bn].[RR_AM_Avg],
	[bn].[BOI_MD_Min],
	[bn].[BOI_MD_Max],
	[bn].[BOI_MD_Avg],
	[bn].[BOI_AM_Min],
	[bn].[BOI_AM_Max],
	[bn].[BOI_AM_Avg],
	[bn].[Validity]
FROM [Users_BOINorms] AS [bn]
	INNER JOIN [Users] AS [u] ON [u].[Guid] = [bn].[Users_Guid]
	INNER JOIN [Users_Persons] AS [p] on [p].[Guid] = [u].[Users_Persons_Guid]
WHERE 
	[u].[Guid] = '%%GUID%%'
	AND [bn].[Calculated] BETWEEN '%%FROM%%' AND '%%TO%%'
ORDER BY 
	[bn].[Calculated] DESC
";

$SQL_QUERY["Persons_NormsGroup"] =
"
/****** Нормы Групповые за период  ******/
SELECT
	FORMAT( [bn].[Calculated], 'dd.MM.yyyy') AS [Calculated],
	[bn].[Validity],
	[bn].[RR_MD_Min],
	[bn].[RR_MD_Avg],
	[bn].[RR_MD_Max],
	[bn].[RR_AM_Min],
	[bn].[RR_AM_Avg],
	[bn].[RR_AM_Max],
	[bn].[BOI_MD_Min],
	[bn].[BOI_MD_Avg],
	[bn].[BOI_MD_Max],
	[bn].[BOI_AM_Min],
	[bn].[BOI_AM_Avg],
	[bn].[BOI_AM_Max]
FROM [Users_BOINorms] AS [bn]
WHERE 
	[bn].[Users_Guid] IS NULL
	AND [bn].[Calculated] BETWEEN '%%FROM%%' AND '%%TO%%'
ORDER BY 
	[bn].[Calculated] DESC
";

$SQL_QUERY["Persons_MedicalInspections"] =
"
/****** Полученные медосмотры из гарнитур по человекам  ******/
SELECT
	--[s].[Alias] AS [ReceivedFromBlock],
	--[svc].[Alias] AS [ReceivedFromService],
	concat(p.Last_Name, ' ', p.First_Name, ' ', p.Middle_Name) AS [FIO], 
	[sn].[Name] AS [HID],
	--[ex].[Guid],
	--[ex].[ExaminationGuid],
	--[ex].[Servers_Guid],
	--[ex].[Services_Guid],
	--[ex].[Sensors_Guid],
	--[ex].[Users_Guid],
	--[ex].[Users_Persons_Guid],
	--[ex].[ExaminationRead],
	MIN(FORMAT( [ex].[ExaminationRead], 'dd.MM.yyyy HH:mm:ss')) AS [ExaminationRead],
	--[ex].[RecordingState],
	--[ex].[SystPressure],
	--[ex].[DistPressure],
	--[ex].[ExaminationType],
	--[ex].[ExaminationDateTime],
	FORMAT( [ex].[ExaminationDateTime], 'dd.MM.yyyy HH:mm:ss') AS [ExaminationDateTime]
	--[ex].[ExaminationResult],
	--[ex].[InterShiftExaminationType],
	--[ex].[InterShiftExaminationDateTime],
	--[ex].[InterShiftExaminationResult],
	--[ex].[Created] AS [Written],
	--[ex].[Last_Name],
	--[ex].[First_Name],
	--[ex].[Middle_Name]
FROM [Sensors_Exam] AS [ex]
	INNER JOIN [Servers] AS [s] ON [s].[Guid] = [ex].[Servers_Guid]
	INNER JOIN [Sensors] AS [sn] ON [sn].[Guid] = [ex].[Sensors_Guid]
	INNER JOIN [Users_Persons] AS [p] ON [p].[Guid] = [ex].[Users_Persons_Guid]
	INNER JOIN [Services] AS [svc] ON [svc].[Guid] = [ex].[Services_Guid]
WHERE 1=1
	AND [ex].[Users_Guid] = '%%GUID%%'
	--AND p.Last_Name LIKE '%Крут%'
	--AND p.First_Name LIKE '%Сергей%'
	--AND [sn].[Name] LIKE '%352'
	AND [ex].[Created] BETWEEN '%%FROM%%' AND '%%TO%%'
	AND [ex].[ExaminationDateTime] IS NOT NULL
GROUP BY 
	[p].[Last_Name], [p].[First_Name], [p].[Middle_Name], [sn].[Name], [ex].[ExaminationDateTime]
ORDER BY
	[ex].[ExaminationDateTime] DESC
--	[ex].[Created] DESC
 ";

?>