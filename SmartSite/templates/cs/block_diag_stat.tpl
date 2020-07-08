<div class="textwrapper">
	<a href="include-long.htm" onclick="return hs.htmlExpand(this, { objectType: 'ajax', contentId: 'highslide-html-8' } )"
			class="highslide">
	</a>
	<div class="highslide-html-content" id="highslide-html-8" style="padding: 15px; width: auto">
	    <div class="highslide-body" style="padding: 10px"></div>
	</div>

</div>


<a onClick="jQuery('.absolute').show();jQuery('.perHour').hide();" style="cursor:pointer;">[Абсолютные цифры]</a> / 
<a onClick="jQuery('.perHour').show();jQuery('.absolute').hide();" style="cursor:pointer;">[Количество событий в час]</a> / 
<a onClick="jQuery('.perHour').show();jQuery('.absolute').show();" style="cursor:pointer;" Title="Верхняя строка - общее, нижняя - в час">[Оба два, тот и другой]</a>
<br /><br />

Показать снова колонки (что бы скрыть колонку - кликнуть по ее названию):<br />
<span class="coupling_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.coupling').show();jQuery('.coupling_Label').hide();">Coupling - Сцепка</a><br /></span>
<span class="t_date_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.t_date').show();jQuery('.t_date_Label').hide();">Date - Дата</a><br /></span>
<span class="srvc_wrk_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.srvc_wrk').show();jQuery('.srvc_wrk_Label').hide();">Service wrk - Время работы сервисов</a><br /></span>
<span class="conn_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.conn').show();jQuery('.conn_Label').hide();" style="cursor:pointer;">Connection - Время подключения блоков</a><br /></span>
<span class="onb_NoConnection_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.onb_NoConnection').show();jQuery('.onb_NoConnection_Label').hide();">No Link - сообщения [Нет связи с сервером] на блоке</a><br /></span>
<span class="stations_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.stations').show();jQuery('.stations_Label').hide();">Labels cnt - Меток по Валерьяну</a><br /></span>
<span class="stl_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.stl').show();jQuery('.stl_Label').hide();" >STL (by Log) - STL в логе</a><br /></span>
<span class="onb_Station_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.onb_Station').show();jQuery('.onb_Station_Label').hide();">ON.B. Text - Станции и перегоны показанные на блоке</a><br /></span>
<span class="sthconn_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.sthconn').show();jQuery('.sthconn_Label').hide();">STH Connected</a><br /></span>
<span class="onb_Driver_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.onb_Driver').show();jQuery('.onb_Driver_Label').hide();" >On.B.Drv</a><br /></span>
<span class="sth_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.sth').show();jQuery('.sth_Label').hide();">STH cnt</a><br /></span>
<span class="rr_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.rr').show();jQuery('.rr_Label').hide();">RR cnt</a><br /></span>
<span class="onb_HID_Check_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.onb_HID_Check').show();jQuery('.onb_HID_Check_Label').hide();">Check HID</a><br /></span>
<!--span class="onb_HID_Broken_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.onb_HID_Broken').show();jQuery('.onb_HID_Broken_Label').hide();">HID Broken</a><br /></span-->
<span class="onb_HID_Broken_Label" style="cursor:pointer;"><a OnClick="jQuery('.onb_HID_Broken').show();jQuery('.onb_HID_Broken_Label').hide();">HID Broken</a><br /></span>
<span class="lostant_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.lostant').show();jQuery('.lostant_Label').hide();">ANT</a><br /></span>
<span class="lostbg_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.lostbg').show();jQuery('.lostbg_Label').hide();">BG</a><br /></span>
<span class="lostink_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.lostink').show();jQuery('.lostink_Label').hide();">INK</a><br /></span>
<span class="lostst_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.lostst').show();jQuery('.lostst_Label').hide();">SimTech</a><br /></span>
<span class="lostgps_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.lostgps').show();jQuery('.lostgps_Label').hide();">GPS</a><br /></span>
<span class="lostmodem_Label displayNone" style="cursor:pointer;"><a OnClick="jQuery('.lostmodem').show();jQuery('.lostmodem_Label').hide();">Modem</a><br /></span>

<br /><br />
<!--
Coupling - Сцепка
Date - Дата
Service wrk - Время работы сервисов
Connection - Время подключения блоков
No Link - сообщения [Нет связи с сервером] на блоке
Labels cnt - Меток по Валерьяну
STL (by Log) - STL в логе
ON.B. Text - Станции и перегоны показанные на блоке
Connected
On.B.Drv
STH cnt
RR cnt
Check HID
HID Broken
ANT	BG
INK
SimTech
GPS
Modem
-->

Блок: [<b>{BLOCK}</b>]
<table cellspacing='1px' cellpadding='0px' border='0px' class='tbl' width='100%' style="background: #CCCCCC;">
	<tr>
		<th colspan="2">Block</th>
		<th colspan="3" style="background-color: darkgray;">Time</th>
		<th colspan="3">Stations counts</th>
		<th colspan="6" style="background-color: darkgray;">HID</th>
		<th colspan="6">USB Lost Device</th>
	</tr>
	<tr>
		<th style="cursor:pointer;" title="В какой сцепке был вагон на дату" OnClick="jQuery('.coupling').hide();jQuery('.coupling_Label').show();"><span class="coupling">Coupling</span></th>
		<th style="cursor:pointer;" title="Статистика на дату" OnClick="jQuery('.t_date').hide();jQuery('.t_date_Label').show();"><span class="t_date">Date</span></th>
		<th style="background-color: darkgray;cursor:pointer;" title="Наибольшее время работы любого сервиса на блоке, Косвенно говорит об UpTime блока" OnClick="jQuery('.srvc_wrk').hide();jQuery('.srvc_wrk_Label').show();"><span class="srvc_wrk">Srvc wrk</span></th>
		<th style="background-color: darkgray;cursor:pointer;" title="Время подключенность блока к серверу" OnClick="jQuery('.conn').hide();jQuery('.conn_Label').show();"><span class="conn">Connection</span></th>
		<th style="background-color: darkgray;cursor:pointer;" title="Показано сообщений [Нет связи с сервером] на блоке" OnClick="jQuery('.onb_NoConnection').hide();jQuery('.onb_NoConnection_Label').show();"><span class="onb_NoConnection">No<br />Lnk</span></th>
		<th style="cursor:pointer;" title="По версии Валерьяна" OnClick="jQuery('.stations').hide();jQuery('.stations_Label').show();"><span class="stations">Labels cnt</span></th>
		<th style="cursor:pointer;" title="Встречено STL в логе блюгиги" OnClick="jQuery('.stl').hide();jQuery('.stl_Label').show();"><span class="stl">STL (by Log)</span></th>
		<th style="cursor:pointer;" title="Показано станций и перегонов на блороту" OnClick="jQuery('.onb_Station').hide();jQuery('.onb_Station_Label').show();"><span class="onb_Station">ON.B. Text</span></th>
		<th style="background-color: darkgray;cursor:pointer;" title="Встречено STH Connect в логе блюгиги" OnClick="jQuery('.sthconn').hide();jQuery('.sthconn_Label').show();"><span class="sthconn">Connected</span></th>
		<th style="background-color: darkgray;cursor:pointer;" title="Показано машинистов на блоке" OnClick="jQuery('.onb_Driver').hide();jQuery('.onb_Driver_Label').show();"><span class="onb_Driver">On.B.Drv</span></th>
		<th style="background-color: darkgray;cursor:pointer;" title="Встречено STH в логе блюгиги" OnClick="jQuery('.sth').hide();jQuery('.sth_Label').show();"><span class="sth">STH cnt</span></th>
		<th style="background-color: darkgray;cursor:pointer;" title="Получено RR с блока" OnClick="jQuery('.rr').hide();jQuery('.rr_Label').show();"><span class="rr">RR cnt</span></th>
		<th style="background-color: darkgray;cursor:pointer;" title="Показано сообщений [Поправьте датчик] на блоке" OnClick="jQuery('.onb_HID_Check').hide();jQuery('.onb_HID_Check_Label').show();"><span class="onb_HID_Check">Check HID</span></th>
		<th style="background-color: darkgray;cursor:pointer;" title="Показано сообщений [Датчик не исправен] на блоке" OnClick="jQuery('.onb_HID_Broken').hide();jQuery('.onb_HID_Broken_Label').show();"><span class="displayNone onb_HID_Broken">HID Broken</span></th>
		<th style="cursor:pointer;" title="Отвалов устройства по логу" OnClick="jQuery('.lostant').hide();jQuery('.lostant_Label').show();"><span class="lostant">ANT</span></th>
		<th style="cursor:pointer;" title="Отвалов устройства по логу" OnClick="jQuery('.lostbg').hide();jQuery('.lostbg_Label').show();"><span class="lostbg">BG</span></th>
		<th style="cursor:pointer;" title="Отвалов устройства по логу" OnClick="jQuery('.lostink').hide();jQuery('.lostink_Label').show();"><span class="lostink">INK</span></th>
		<th style="cursor:pointer;" title="Отвалов устройства по логу" OnClick="jQuery('.lostst').hide();jQuery('.lostst_Label').show();"><span class="lostst">SimTech</span></th>
		<th style="cursor:pointer;" title="Отвалов устройства по логу" OnClick="jQuery('.lostgps').hide();jQuery('.lostgps_Label').show();"><span class="lostgps">GPS</span></th>
		<th style="cursor:pointer;" title="Отвалов устройства по логу" OnClick="jQuery('.lostmodem').hide();jQuery('.lostmodem_Label').show();"><span class="lostmodem">Modem</span></th>
 
	</tr>
	<!-- BEGIN row -->
	<tr>
		<td align="center" style="font-size:x-small"><span class="coupling">{row.VEHICLE_FIRST}-{row.VEHICLE_SECOND}</span></td>
		<td align="center"><span class="t_date">{row.DATE}</span></td>
		<td style="padding-right:5px;">
			<!-- BEGIN srvc_wrk_diff -->
			<table class="srvc_wrk" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.srvc_wrk_diff.SRVC_STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.srvc_wrk_diff.SRVC_STYLE_DIFF}" title="{row.srvc_wrk_diff.SRVC_TIME_FIRST} sec">{row.srvc_wrk_diff.SRVC_TIME_FIRST_F}</td>
					<td width="10%" align="center" style="{row.srvc_wrk_diff.SRVC_STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.srvc_wrk_diff.SRVC_STYLE_DIFF}" title="{row.srvc_wrk_diff.SRVC_TIME_SECOND} sec">{row.srvc_wrk_diff.SRVC_TIME_SECOND_F}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.srvc_wrk_diff.SRVC_STYLE_DIFF}">{row.srvc_wrk_diff.SRVC_TIME_DIFF}</td>
				</tr>
			</table>
			<!-- END srvc_wrk_diff -->
			<!-- BEGIN srvc_wrk_avg -->
			<center class="srvc_wrk" style="color: darkgrey;" title="First: {row.srvc_wrk_avg.SRVC_TIME_FIRST_F} ({row.srvc_wrk_avg.SRVC_TIME_FIRST} sec) / Second {row.srvc_wrk_avg.SRVC_TIME_SECOND_F} ({row.srvc_wrk_avg.SRVC_TIME_SECOND} sec); Diff: {row.srvc_wrk_avg.SRVC_TIME_DIFF}">{row.srvc_wrk_avg.SRVC_TIME}</center>
			<!-- END srvc_wrk_avg -->
		</td>
		<td>
			<!-- BEGIN conn -->
			<table class="absolute conn" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.conn.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.conn.STYLE_DIFF}" title="{row.conn.FIRST} sec">{row.conn.FIRST_F}</td>
					<td width="10%" align="center" style="{row.conn.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.conn.STYLE_DIFF}" title="{row.conn.SECOND} sec">{row.conn.SECOND_F}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.conn.STYLE_DIFF}">{row.conn.DIFF}</td>
				</tr>
			</table>
			<table class="displayNone perHour conn" cellspacing='0px' cellpadding='0px' border='0px' width='100%' style="{row.conn.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.conn.STYLE_DIFF_PH}" title="{row.conn.FIRST_PH} sec">{row.conn.FIRST_F_PH}</td>
					<td width="10%" align="center" style="{row.conn.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.conn.STYLE_DIFF_PH}" title="{row.conn.SECOND_PH} sec">{row.conn.SECOND_F_PH}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.conn.STYLE_DIFF_PH}">{row.conn.DIFF_PH}</td>
				</tr>
			</table>
			<!-- END conn -->
			<!-- BEGIN conn_avg -->
			<center class="absolute conn" style="color: darkgrey;" title="First: {row.conn_avg.FIRST_F} ({row.conn_avg.FIRST} sec) / Second {row.conn_avg.SECOND_F} ({row.conn_avg.SECOND} sec); Diff: {row.conn_avg.DIFF}">{row.conn_avg.AVG_F}</center>
			<center class="displayNone perHour conn" style="color: darkgrey;" title="First: {row.conn_avg.FIRST_F_PH} ({row.conn_avg.FIRST_PH} sec) / Second {row.conn_avg.SECOND_F_PH} ({row.conn_avg.SECOND_PH} sec); Diff: {row.conn_avg.DIFF_PH}">{row.conn_avg.AVG_F_PH}</center>
			<!-- END conn_avg -->
		</td>
		<td>
			<!-- BEGIN onb_NoConnection -->
			<table class="absolute onb_NoConnection" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.onb_NoConnection.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.onb_NoConnection.STYLE_DIFF}">{row.onb_NoConnection.FIRST}</td>
					<td width="10%" align="center" style="{row.onb_NoConnection.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.onb_NoConnection.STYLE_DIFF}">{row.onb_NoConnection.SECOND}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.onb_NoConnection.STYLE_DIFF}">{row.onb_NoConnection.DIFF}</td-->
				</tr>
			</table>
			<table class="displayNone perHour onb_NoConnection" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.onb_NoConnection.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.onb_NoConnection.STYLE_DIFF_PH}">{row.onb_NoConnection.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.onb_NoConnection.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.onb_NoConnection.STYLE_DIFF_PH}">{row.onb_NoConnection.SECOND_PH}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.onb_NoConnection.STYLE_DIFF_PH}">{row.onb_NoConnection.DIFF_PH}</td-->
				</tr>
			</table>
			<!-- END onb_NoConnection -->
			<!-- BEGIN onb_NoConnection_avg -->
			<center class="absolute onb_NoConnection" style="color: darkgrey;" title="First: {row.onb_NoConnection_avg.FIRST_F} ({row.onb_NoConnection_avg.FIRST} pcs) / Second {row.onb_NoConnection_avg.SECOND_F} ({row.onb_NoConnection_avg.SECOND} pcs); Diff: {row.onb_NoConnection_avg.DIFF}">{row.onb_NoConnection_avg.AVG_F}</center>
			<center class="displayNone perHour onb_NoConnection" style="color: darkgrey;" title="First: {row.onb_NoConnection_avg.FIRST_F_PH} ({row.onb_NoConnection_avg.FIRST_PH} pcs) / Second {row.onb_NoConnection_avg.SECOND_F_PH} ({row.onb_NoConnection_avg.SECOND_PH} pcs); Diff: {row.onb_NoConnection_avg.DIFF_PH}">{row.onb_NoConnection_avg.AVG_F_PH}</center>
			<!-- END onb_NoConnection_avg -->
		</td>
		<td>
			<!-- BEGIN stations -->
			<table class="absolute stations" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.stations.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.stations.STYLE_DIFF}" title="{row.stations.FIRST}">{row.stations.FIRST_F}</td>
					<td width="10%" align="center" style="{row.stations.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.stations.STYLE_DIFF}" title="{row.stations.SECOND}">{row.stations.SECOND_F}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.stations.STYLE_DIFF}">{row.stations.DIFF}</td>
				</tr>
			</table>
			<table class="displayNone perHour stations" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.stations.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.stations.STYLE_DIFF_PH}" title="{row.stations.FIRST_PH} pcs">{row.stations.FIRST_F_PH}</td>
					<td width="10%" align="center" style="{row.stations.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.stations.STYLE_DIFF_PH}" title="{row.stations.SECOND_PH} pcs">{row.stations.SECOND_F_PH}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.stations.STYLE_DIFF_PH}">{row.stations.DIFF_PH}</td>
				</tr>
			</table>
			<!-- END stations -->
			<!-- BEGIN stations_avg -->
			<center class="absolute stations" style="color: darkgrey;" title="First: {row.stations_avg.FIRST_F} ({row.stations_avg.FIRST} pcs) / Second {row.stations_avg.SECOND_F} ({row.stations_avg.SECOND} pcs); Diff: {row.stations_avg.DIFF}">{row.stations_avg.AVG_F}</center>
			<center class="displayNone perHour stations" style="color: darkgrey;" title="First: {row.stations_avg.FIRST_F_PH} ({row.stations_avg.FIRST_PH} pcs) / Second {row.stations_avg.SECOND_F_PH} ({row.stations_avg.SECOND_PH} pcs); Diff: {row.stations_avg.DIFF_PH}">{row.stations_avg.AVG_F_PH}</center>
			<!-- END stations_avg -->
		</td>
		<td>
			<!-- BEGIN stl -->
			<table class="absolute stl" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.stl.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.stl.STYLE_DIFF}">{row.stl.FIRST}</td>
					<td width="10%" align="center" style="{row.stl.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.stl.STYLE_DIFF}">{row.stl.SECOND}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.stl.STYLE_DIFF}">{row.stl.DIFF}</td>
				</tr>
			</table>
			<table class="displayNone perHour stl" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.stl.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.stl.STYLE_DIFF_PH}">{row.stl.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.stl.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.stl.STYLE_DIFF_PH}">{row.stl.SECOND_PH}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.stl.STYLE_DIFF_PH}">{row.stl.DIFF_PH}</td>
				</tr>
			</table>
			<!-- END stl -->
			<!-- BEGIN stl_avg -->
			<center class="absolute stl" style="color: darkgrey;" title="First: {row.stl_avg.FIRST_F} ({row.stl_avg.FIRST} pcs) / Second {row.stl_avg.SECOND_F} ({row.stl_avg.SECOND} pcs); Diff: {row.stl_avg.DIFF}">{row.stl_avg.AVG_F}</center>
			<center class="displayNone perHour stl" style="color: darkgrey;" title="First: {row.stl_avg.FIRST_F_PH} ({row.stl_avg.FIRST_PH} pcs) / Second {row.stl_avg.SECOND_F_PH} ({row.stl_avg.SECOND_PH} pcs); Diff: {row.stl_avg.DIFF_PH}">{row.stl_avg.AVG_F_PH}</center>
			<!-- END stl_avg -->
		</td>
		<td>
			<!-- BEGIN onb_Station -->
			<table class="absolute onb_Station" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.onb_Station.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.onb_Station.STYLE_DIFF}">{row.onb_Station.FIRST}</td>
					<td width="10%" align="center" style="{row.onb_Station.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.onb_Station.STYLE_DIFF}">{row.onb_Station.SECOND}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.onb_Station.STYLE_DIFF}">{row.onb_Station.DIFF}</td>
				</tr>
			</table>
			<table class="displayNone perHour onb_Station" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.onb_Station.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.onb_Station.STYLE_DIFF_PH}">{row.onb_Station.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.onb_Station.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.onb_Station.STYLE_DIFF_PH}">{row.onb_Station.SECOND_PH}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.onb_Station.STYLE_DIFF_PH}">{row.onb_Station.DIFF_PH}</td>
				</tr>
			</table>
			<!-- END onb_Station -->
			<!-- BEGIN onb_Station_avg -->
			<center class="absolute onb_Station" style="color: darkgrey;" title="First: {row.onb_Station_avg.FIRST_F} ({row.onb_Station_avg.FIRST} pcs) / Second {row.onb_Station_avg.SECOND_F} ({row.onb_Station_avg.SECOND} pcs); Diff: {row.onb_Station_avg.DIFF}">{row.onb_Station_avg.AVG_F}</center>
			<center class="displayNone perHour onb_Station" style="color: darkgrey;" title="First: {row.onb_Station_avg.FIRST_F_PH} ({row.onb_Station_avg.FIRST_PH} pcs) / Second {row.onb_Station_avg.SECOND_F_PH} ({row.onb_Station_avg.SECOND_PH} pcs); Diff: {row.onb_Station_avg.DIFF_PH}">{row.onb_Station_avg.AVG_F_PH}</center>
			<!-- END onb_Station_avg -->
		</td>
		<td>
			<!-- BEGIN sthconn -->
			<table class="absolute sthconn" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.sthconn.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.sthconn.STYLE_DIFF}">{row.sthconn.FIRST}</td>
					<td width="10%" align="center" style="{row.sthconn.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.sthconn.STYLE_DIFF}">{row.sthconn.SECOND}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.sthconn.STYLE_DIFF}">{row.sthconn.DIFF}</td-->
				</tr>
			</table>
			<table class="displayNone perHour sthconn" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.sthconn.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.sthconn.STYLE_DIFF_PH}">{row.sthconn.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.sthconn.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.sthconn.STYLE_DIFF_PH}">{row.sthconn.SECOND_PH}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.sthconn.STYLE_DIFF_PH}">{row.sthconn.DIFF_PH}</td-->
				</tr>
			</table>
			<!-- END sthconn -->
			<!-- BEGIN sthconn_avg -->
			<center class="absolute sthconn" style="color: darkgrey;" title="First: {row.sthconn_avg.FIRST_F} ({row.sthconn_avg.FIRST} pcs) / Second {row.sthconn_avg.SECOND_F} ({row.sthconn_avg.SECOND} pcs); Diff: {row.sthconn_avg.DIFF}">{row.sthconn_avg.AVG_F}</center>
			<center class="displayNone perHour sthconn" style="color: darkgrey;" title="First: {row.sthconn_avg.FIRST_F_PH} ({row.sthconn_avg.FIRST_PH} pcs) / Second {row.sthconn_avg.SECOND_F_PH} ({row.sthconn_avg.SECOND_PH} pcs); Diff: {row.sthconn_avg.DIFF_PH}">{row.sthconn_avg.AVG_F_PH}</center>
			<!-- END sthconn_avg -->
		</td>
		<td>
			<!-- BEGIN onb_Driver -->
			<table class="absolute onb_Driver" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.onb_Driver.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.onb_Driver.STYLE_DIFF}">{row.onb_Driver.FIRST}</td>
					<td width="10%" align="center" style="{row.onb_Driver.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.onb_Driver.STYLE_DIFF}">{row.onb_Driver.SECOND}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.onb_Driver.STYLE_DIFF}">{row.onb_Driver.DIFF}</td-->
				</tr>
			</table>
			<table class="displayNone perHour onb_Driver" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.onb_Driver.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.onb_Driver.STYLE_DIFF_PH}">{row.onb_Driver.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.onb_Driver.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.onb_Driver.STYLE_DIFF_PH}">{row.onb_Driver.SECOND_PH}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.onb_Driver.STYLE_DIFF_PH}">{row.onb_Driver.DIFF_PH}</td-->
				</tr>
			</table>
			<!-- END onb_Driver -->
			<!-- BEGIN onb_Driver_avg -->
			<center class="absolute onb_Driver" style="color: darkgrey;" title="First: {row.onb_Driver_avg.FIRST_F} ({row.onb_Driver_avg.FIRST} pcs) / Second {row.onb_Driver_avg.SECOND_F} ({row.onb_Driver_avg.SECOND} pcs); Diff: {row.onb_Driver_avg.DIFF}">{row.onb_Driver_avg.AVG_F}</center>
			<center class="displayNone perHour onb_Driver" style="color: darkgrey;" title="First: {row.onb_Driver_avg.FIRST_F_PH} ({row.onb_Driver_avg.FIRST_PH} pcs) / Second {row.onb_Driver_avg.SECOND_F_PH} ({row.onb_Driver_avg.SECOND_PH} pcs); Diff: {row.onb_Driver_avg.DIFF_PH}">{row.onb_Driver_avg.AVG_F_PH}</center>
			<!-- END onb_Driver_avg -->
		</td>
		<td>
			<!-- BEGIN sth -->
			<table class="absolute sth" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.sth.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.sth.STYLE_DIFF}" title="{row.sth.FIRST}">{row.sth.FIRST_F}</td>
					<td width="10%" align="center" style="{row.sth.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.sth.STYLE_DIFF}" title="{row.sth.SECOND}">{row.sth.SECOND_F}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.sth.STYLE_DIFF}">{row.sth.DIFF}</td>
				</tr>
			</table>
			<table class="displayNone perHour sth" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.sth.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.sth.STYLE_DIFF_PH}" title="{row.sth.FIRST_PH} pcs">{row.sth.FIRST_F_PH}</td>
					<td width="10%" align="center" style="{row.sth.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.sth.STYLE_DIFF_PH}" title="{row.sth.SECOND_PH} pcs">{row.sth.SECOND_F_PH}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.sth.STYLE_DIFF_PH}">{row.sth.DIFF_PH}</td>
				</tr>
			</table>
			<!-- END sth -->
			<!-- BEGIN sth_avg -->
			<center class="absolute sth" style="color: darkgrey;" title="First: {row.sth_avg.FIRST_F} ({row.sth_avg.FIRST} pcs) / Second {row.sth_avg.SECOND_F} ({row.sth_avg.SECOND} pcs); Diff: {row.sth_avg.DIFF}">{row.sth_avg.AVG_F}</center>
			<center class="displayNone perHour sth" style="color: darkgrey;" title="First: {row.sth_avg.FIRST_F_PH} ({row.sth_avg.FIRST_PH} pcs) / Second {row.sth_avg.SECOND_F_PH} ({row.sth_avg.SECOND_PH} pcs); Diff: {row.sth_avg.DIFF_PH}">{row.sth_avg.AVG_F_PH}</center>
			<!-- END sth_avg -->
		</td>
		<td>
			<!-- BEGIN rr -->
			<table class="absolute rr" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.rr.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.rr.STYLE_DIFF}" title="{row.rr.FIRST}">{row.rr.FIRST_F}</td>
					<td width="10%" align="center" style="{row.rr.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.rr.STYLE_DIFF}" title="{row.rr.SECOND}">{row.rr.SECOND_F}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.rr.STYLE_DIFF}">{row.rr.DIFF}</td>
				</tr>
			</table>
			<table class="displayNone perHour rr" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.rr.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.rr.STYLE_DIFF_PH}" title="{row.rr.FIRST_PH} pcs">{row.rr.FIRST_F_PH}</td>
					<td width="10%" align="center" style="{row.rr.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.rr.STYLE_DIFF_PH}" title="{row.rr.SECOND_PH} pcs">{row.rr.SECOND_F_PH}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.rr.STYLE_DIFF_PH}">{row.rr.DIFF_PH}</td>
				</tr>
			</table>
			<!-- END rr -->
			<!-- BEGIN rr_avg -->
			<center class="absolute rr" style="color: darkgrey;" title="First: {row.rr_avg.FIRST_F} ({row.rr_avg.FIRST} pcs) / Second {row.rr_avg.SECOND_F} ({row.rr_avg.SECOND} pcs); Diff: {row.rr_avg.DIFF}">{row.rr_avg.AVG_F}</center>
			<center class="displayNone perHour rr" style="color: darkgrey;" title="First: {row.rr_avg.FIRST_F_PH} ({row.rr_avg.FIRST_PH} pcs) / Second {row.rr_avg.SECOND_F_PH} ({row.rr_avg.SECOND_PH} pcs); Diff: {row.rr_avg.DIFF_PH}">{row.rr_avg.AVG_F_PH}</center>
			<!-- END rr_avg -->
		</td>
		<td>
			<!-- BEGIN onb_HID_Check -->
			<table class="absolute onb_HID_Check" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.onb_HID_Check.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.onb_HID_Check.STYLE_DIFF}">{row.onb_HID_Check.FIRST}</td>
					<td width="10%" align="center" style="{row.onb_HID_Check.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.onb_HID_Check.STYLE_DIFF}">{row.onb_HID_Check.SECOND}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.onb_HID_Check.STYLE_DIFF}">{row.onb_HID_Check.DIFF}</td>
				</tr>
			</table>
			<table class="displayNone perHour onb_HID_Check" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.onb_HID_Check.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.onb_HID_Check.STYLE_DIFF_PH}">{row.onb_HID_Check.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.onb_HID_Check.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.onb_HID_Check.STYLE_DIFF_PH}">{row.onb_HID_Check.SECOND_PH}</td>
					<td width="15%" align="right" style="padding-right:5px; {row.onb_HID_Check.STYLE_DIFF_PH}">{row.onb_HID_Check.DIFF_PH}</td>
				</tr>
			</table>
			<!-- END onb_HID_Check -->
			<!-- BEGIN onb_HID_Check_avg -->
			<center class="absolute onb_HID_Check" style="color: darkgrey;" title="First: {row.onb_HID_Check_avg.FIRST_F} / Second {row.onb_HID_Check_avg.SECOND_F}; Diff: {row.onb_HID_Check_avg.DIFF}">{row.onb_HID_Check_avg.AVG_F}</center>
			<center class="displayNone perHour onb_HID_Check" style="color: darkgrey;" title="First: {row.onb_HID_Check_avg.FIRST_F_PH} / Second {row.onb_HID_Check_avg.SECOND_F_PH}; Diff: {row.onb_HID_Check_avg.DIFF_PH}">{row.onb_HID_Check_avg.AVG_F_PH}</center>
			<!-- END onb_HID_Check_avg -->
		</td>
		<td>
			<!-- BEGIN onb_HID_Broken -->
			<table class="absolute displayNone onb_HID_Broken" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.onb_HID_Broken.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.onb_HID_Broken.STYLE_DIFF}">{row.onb_HID_Broken.FIRST}</td>
					<td width="10%" align="center" style="{row.onb_HID_Broken.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.onb_HID_Broken.STYLE_DIFF}">{row.onb_HID_Broken.SECOND}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.onb_HID_Broken.STYLE_DIFF}">{row.onb_HID_Broken.DIFF}</td-->
				</tr>
			</table>
			<table class="displayNone perHour onb_HID_Broken" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.onb_HID_Broken.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.onb_HID_Broken.STYLE_DIFF_PH}">{row.onb_HID_Broken.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.onb_HID_Broken.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.onb_HID_Broken.STYLE_DIFF_PH}">{row.onb_HID_Broken.SECOND_PH}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.onb_HID_Broken.STYLE_DIFF_PH}">{row.onb_HID_Broken.DIFF_PH}</td-->
				</tr>
			</table>
			<!-- END onb_HID_Broken -->
			<!-- BEGIN onb_HID_Broken_avg -->
			<center class="absolute displayNone onb_HID_Broken" style="color: darkgrey;" title="First: {row.onb_HID_Broken_avg.FIRST_F} / Second {row.onb_HID_Broken_avg.SECOND_F}; Diff: {row.onb_HID_Broken_avg.DIFF}">{row.onb_HID_Broken_avg.AVG_F}</center>
			<center class="displayNone perHour onb_HID_Broken" style="color: darkgrey;" title="First: {row.onb_HID_Broken_avg.FIRST_F_PH} / Second {row.onb_HID_Broken_avg.SECOND_F_PH}; Diff: {row.onb_HID_Broken_avg.DIFF_PH}">{row.onb_HID_Broken_avg.AVG_F_PH}</center>
			<!-- END onb_HID_Broken_avg -->
		</td>
		<td>
			<!-- BEGIN lostant -->
			<table class="absolute lostant" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostant.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.lostant.STYLE_DIFF}">{row.lostant.FIRST}</td>
					<td width="10%" align="center" style="{row.lostant.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.lostant.STYLE_DIFF}">{row.lostant.SECOND}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostant.STYLE_DIFF}">{row.lostant.DIFF}</td-->
				</tr>
			</table>
			<table class="displayNone perHour lostant" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostant.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.lostant.STYLE_DIFF_PH}">{row.lostant.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.lostant.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.lostant.STYLE_DIFF_PH}">{row.lostant.SECOND_PH}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostant.STYLE_DIFF_PH}">{row.lostant.DIFF_PH}</td-->
				</tr>
			</table>
			<!-- END lostant -->
			<!-- BEGIN lostant_avg -->
			<center class="absolute lostant" style="color: darkgrey;" title="First: {row.lostant_avg.FIRST_F} / Second {row.lostant_avg.SECOND_F}; Diff: {row.lostant_avg.DIFF}">{row.lostant_avg.AVG_F}</center>
			<center class="displayNone perHour lostant" style="color: darkgrey;" title="First: {row.lostant_avg.FIRST_F_PH} / Second {row.lostant_avg.SECOND_F_PH}; Diff: {row.lostant_avg.DIFF_PH}">{row.lostant_avg.AVG_F_PH}</center>
			<!-- END lostant_avg -->
		</td>
		<td>
			<!-- BEGIN lostbg -->
			<table class="absolute lostbg" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostbg.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.lostbg.STYLE_DIFF}">{row.lostbg.FIRST}</td>
					<td width="10%" align="center" style="{row.lostbg.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.lostbg.STYLE_DIFF}">{row.lostbg.SECOND}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostbg.STYLE_DIFF}">{row.lostbg.DIFF}</td-->
				</tr>
			</table>
			<table class="displayNone perHour lostbg" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostbg.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.lostbg.STYLE_DIFF_PH}">{row.lostbg.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.lostbg.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.lostbg.STYLE_DIFF_PH}">{row.lostbg.SECOND_PH}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostbg.STYLE_DIFF_PH}">{row.lostbg.DIFF_PH}</td-->
				</tr>
			</table>
			<!-- END lostbg -->
			<!-- BEGIN lostbg_avg -->
			<center class="absolute lostbg" style="color: darkgrey;" title="First: {row.lostbg_avg.FIRST_F} / Second {row.lostbg_avg.SECOND_F}; Diff: {row.lostbg_avg.DIFF}">{row.lostbg_avg.AVG_F}</center>
			<center class="displayNone perHour lostbg" style="color: darkgrey;" title="First: {row.lostbg_avg.FIRST_F_PH} / Second {row.lostbg_avg.SECOND_F_PH}; Diff: {row.lostbg_avg.DIFF_PH}">{row.lostbg_avg.AVG_F_PH}</center>
			<!-- END lostbg_avg -->
		</td>
		<td>
			<!-- BEGIN lostink -->
			<table class="absolute lostink" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostink.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.lostink.STYLE_DIFF}">{row.lostink.FIRST}</td>
					<td width="10%" align="center" style="{row.lostink.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.lostink.STYLE_DIFF}">{row.lostink.SECOND}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostink.STYLE_DIFF}">{row.lostink.DIFF}</td-->
				</tr>
			</table>
			<table class="displayNone perHour lostink" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostink.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.lostink.STYLE_DIFF_PH}">{row.lostink.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.lostink.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.lostink.STYLE_DIFF_PH}">{row.lostink.SECOND_PH}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostink.STYLE_DIFF_PH}">{row.lostink.DIFF_PH}</td-->
				</tr>
			</table>
			<!-- END lostink -->
			<!-- BEGIN lostink_avg -->
			<center class="absolute lostink" style="color: darkgrey;" title="First: {row.lostink_avg.FIRST_F} / Second {row.lostink_avg.SECOND_F}; Diff: {row.lostink_avg.DIFF}">{row.lostink_avg.AVG_F}</center>
			<center class="displayNone perHour lostink" style="color: darkgrey;" title="First: {row.lostink_avg.FIRST_F_PH} / Second {row.lostink_avg.SECOND_F_PH}; Diff: {row.lostink_avg.DIFF_PH}">{row.lostink_avg.AVG_F_PH}</center>
			<!-- END lostink_avg -->
		</td>
		<td>
			<!-- BEGIN lostst -->
			<table class="absolute lostst" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostst.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.lostst.STYLE_DIFF}">{row.lostst.FIRST}</td>
					<td width="10%" align="center" style="{row.lostst.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.lostst.STYLE_DIFF}">{row.lostst.SECOND}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostst.STYLE_DIFF}">{row.lostst.DIFF}</td-->
				</tr>
			</table>
			<table class="displayNone perHour lostst" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostst.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.lostst.STYLE_DIFF_PH}">{row.lostst.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.lostst.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.lostst.STYLE_DIFF_PH}">{row.lostst.SECOND_PH}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostst.STYLE_DIFF_PH}">{row.lostst.DIFF_PH}</td-->
				</tr>
			</table>
			<!-- END lostst -->
			<!-- BEGIN lostst_avg -->
			<center class="absolute lostst" style="color: darkgrey;" title="First: {row.lostst_avg.FIRST_F} / Second {row.lostst_avg.SECOND_F}; Diff: {row.lostst_avg.DIFF}">{row.lostst_avg.AVG_F}</center>
			<center class="displayNone perHour lostst" style="color: darkgrey;" title="First: {row.lostst_avg.FIRST_F_PH} / Second {row.lostst_avg.SECOND_F_PH}; Diff: {row.lostst_avg.DIFF_PH}">{row.lostst_avg.AVG_F_PH}</center>
			<!-- END lostst_avg -->
		</td>
		<td>
			<!-- BEGIN lostgps -->
			<table class="absolute lostgps" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostgps.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.lostgps.STYLE_DIFF}">{row.lostgps.FIRST}</td>
					<td width="10%" align="center" style="{row.lostgps.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.lostgps.STYLE_DIFF}">{row.lostgps.SECOND}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostgps.STYLE_DIFF}">{row.lostgps.DIFF}</td-->
				</tr>
			</table>
			<table class="displayNone perHour lostgps" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostgps.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.lostgps.STYLE_DIFF_PH}">{row.lostgps.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.lostgps.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.lostgps.STYLE_DIFF_PH}">{row.lostgps.SECOND_PH}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostgps.STYLE_DIFF_PH}">{row.lostgps.DIFF_PH}</td-->
				</tr>
			</table>
			<!-- END lostgps -->
			<!-- BEGIN lostgps_avg -->
			<center class="absolute lostgps" style="color: darkgrey;" title="First: {row.lostgps_avg.FIRST_F} / Second {row.lostgps_avg.SECOND_F}; Diff: {row.lostgps_avg.DIFF}">{row.lostgps_avg.AVG_F}</center>
			<center class="displayNone perHour lostgps" style="color: darkgrey;" title="First: {row.lostgps_avg.FIRST_F_PH} / Second {row.lostgps_avg.SECOND_F_PH}; Diff: {row.lostgps_avg.DIFF_PH}">{row.lostgps_avg.AVG_F_PH}</center>
			<!-- END lostgps_avg -->
		</td>
		<td>
			<!-- BEGIN lostmodem -->
			<table class="absolute lostmodem" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostmodem.STYLE_DIFF}">
				<tr>
					<td width="35%" align="right" style="{row.lostmodem.STYLE_DIFF}">{row.lostmodem.FIRST}</td>
					<td width="10%" align="center" style="{row.lostmodem.STYLE_DIFF}">/</td>
					<td width="35%" align="left" style="{row.lostmodem.STYLE_DIFF}">{row.lostmodem.SECOND}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostmodem.STYLE_DIFF}">{row.lostmodem.DIFF}</td-->
				</tr>
			</table>
			<table class="displayNone perHour lostmodem" cellspacing='0px' cellpadding='0px' border='0px' class='tbl' width='100%' style="{row.lostmodem.STYLE_DIFF_PH}">
				<tr>
					<td width="35%" align="right" style="{row.lostmodem.STYLE_DIFF_PH}">{row.lostmodem.FIRST_PH}</td>
					<td width="10%" align="center" style="{row.lostmodem.STYLE_DIFF_PH}">/</td>
					<td width="35%" align="left" style="{row.lostmodem.STYLE_DIFF_PH}">{row.lostmodem.SECOND_PH}</td>
					<!--td width="15%" align="right" style="padding-right:5px; {row.lostmodem.STYLE_DIFF_PH}">{row.lostmodem.DIFF_PH}</td-->
				</tr>
			</table>
			<!-- END lostmodem -->
			<!-- BEGIN lostmodem_avg -->
			<center class="absolute lostmodem" style="color: darkgrey;" title="First: {row.lostmodem_avg.FIRST_F} / Second {row.lostmodem_avg.SECOND_F}; Diff: {row.lostmodem_avg.DIFF}">{row.lostmodem_avg.AVG_F}</center>
			<center class="displayNone perHour lostmodem" style="color: darkgrey;" title="First: {row.lostmodem_avg.FIRST_F_PH} / Second {row.lostmodem_avg.SECOND_F_PH}; Diff: {row.lostmodem_avg.DIFF_PH}">{row.lostmodem_avg.AVG_F_PH}</center>
			<!-- END lostmodem_avg -->
		</td>
		<!--td style='{row.STYLE_XXX}' title="{row.XXX}" >{row.IPADDRESS}</td-->
	</tr>
	<!-- END row -->
</table>

<!--table cellspacing='1px' cellpadding='0px' border='0px' class='tbl' width='30%'>
	<tr><th colspan="2">Блоки по типам</th></tr>
	<tr><td>Linux Pad:</td><td>{CNT_LINUX_PAD}</td></tr>
	<tr><td>Windows Pad:</td><td>{CNT_WINDOWS_PAD}</td></tr>
	<tr><td>Olimex:</td><td>{CNT_OLIMEX}</td></tr>
	<tr><td>Неизвестные:</td><td>{CNT_UNCKNOWN}</td></tr>
	<tr><td colspan="2">данные приблизительные <br/>и считаются по косвенным признакам <br/>(тип установленного пакета)</td></tr>
</table-->


{ARTICLE}
{TEMP}

<!-- BEGIN legend -->
<h2>Легенда:</h2>

<h3>Гадание на цифрах цвета октября</h3>

<table class='tbl' style="background: #CCCCCC;">
	<tr>
		<th colspan="2">Ссылки вверху</th>
		<th>Назначение</th>
	</tr>
	<tr>
		<td colspan="2">&nbsp;[Абсолютные цифры]&nbsp;</td>
		<td>
			Суммарное время / количество событий (шт) - за сутки по GMT
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;[Количество событий в час]&nbsp;</td>
		<td>Тоже, но за чем работы блока. Время работы блока берется из колонки "Service wrk"</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;[Оба два, тот и другой]&nbsp;</td>
		<td>Оба варианта - верхняя строка в ячейке итого, нижняя - в час</td>
	</tr>
	<tr>
		<th>Колонка</th>
		<th>Шаманская трактовка</th>
		<th>Какую ведьму сжигать</th>
	</tr>
	<tr>
		<td>&nbsp;Coupling&nbsp;</td>
		<td>Сцепка на указанный день.</td>
		<td></td>
	</tr>
	<tr>
		<td>&nbsp;Date&nbsp;</td>
		<td>Дата</td>
		<td>Сутки считаются по GMT</td>
	</tr>
	<tr>
		<td>&nbsp;Service wrk&nbsp;</td>
		<td>Максимальное суммарное время работы самого долгоработающего сервиса</td>
		<td>
			При большом времени работы на одном блоке (более 5, а лучше 10 часов) и малом на другом - косвенно говорит о том что второй блок или был выключен или завис. Т.е. не работала OS. 
			При малых периодах работы на обоих блоках - ни о чем не говорит т.к. это могли быть включения в Депо и только одной головы.<br />
			Также при малом времени работы на текущем блоке - все остальыне показатели также просядут т.к. Без ОБ все остальнео тоже не работает.<br />
			Говорит о проблемах с ОБ.
		</td>
	</tr>
	<tr>
		<td>&nbsp;Connection&nbsp;</td>
		<td>Время нахождения на связи каждого блока и разница между ними.</td>
		<td>Разовые провалы - ни о чем не говорят. т.к. состав мог стоить в нычке и одна из голов не видела связи. Обращать внимание стоит на постоянные сущесвенные провалы.
			<br />Косвенно говорит о проблемах с модемом.
		</td>
	</tr>
	<tr>
		<td>&nbsp;Labels cnt&nbsp;</td>
		<td>Количество станций замеченных блоком.</td>
		<td>
			--//--
			<br />Косвенно говорит о проблемах с БГ.
		</td>
	</tr>
	<tr>
		<td>&nbsp;STL (by Log)&nbsp;</td>
		<td>Кол-во раз в логе BG упомянуто STL по блокам сцепки.</td>
		<td>
			--//--
			<br />Косвенно говорит о проблемах с БГ.
		</td>
	</tr>
	<tr>
		<td>&nbsp;HID: Connected&nbsp;</td>
		<td>Кол-во раз в логе BG встречено событие STH-Connect по блокам сцепки.</td>
		<td>
			--//--<br />
			Подозрительны обе ситуации - слишком много коннектов и слишком мало коннектов. <br />
			Если коннектов слишком много, а остальные параметры, вроде полученные RR, в норме - значит гарнитуры постоянно отваливаются.<br />
			Если коннектов слишком мало, а остальные параметры, вроде полученных RR также малы - гарнитуры не могут приконнектиться.<br />
			Косвенно говорит о проблемах с БГ, либо линии связи ОБ-БОВИ.
		</td>
	</tr>
	<tr>
		<td>&nbsp;STH cnt&nbsp;</td>
		<td>Кол-во раз в логе BG упомянуто STH по блокам сцепки.</td>
		<td>
			--//--
			<br />Косвенно говорит о проблемах с БГ.
		</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/pulse_20x20.png' width='16' height='16' Title='Получено RR шт по блокам сцепки.' />&nbsp;</td>
		<td>Получено RR шт по блокам сцепки.</td>
		<td>
			--//--<br />
			Косвенно говорит о проблемах с БГ.
		</td>
	</tr>
	<tr>
		<td>&nbsp;ANT&nbsp;</td>
		<td>Кол-во отвалов ANT по логу ОБ по блокам сцепки.</td>
		<td>
			Нормальная ситуация - отвалов 0 шт.<br />
			Косвенно говорит о проблемах с линией связи (Порт - Шнур - Порт, т.е. это не обязательно шнур виноват).
		</td>
	</tr>
	<tr>
		<td>&nbsp;BG&nbsp;</td>
		<td>Кол-во отвалов BG по логу ОБ по блокам сцепки.</td>
		<td>
			Нормальная ситуация - отвалов &lt;10 шт.<br />
			Косвенно говорит о проблемах с линией связи (Порт - Шнур - Порт, т.е. это не обязательно шнур виноват).
		</td>
	</tr>
	<tr>
		<td>&nbsp;INK&nbsp;</td>
		<td>Кол-во отвалов INK по логу ОБ по блокам сцепки.</td>
		<td>
			Нормальная ситуация - отвалов 0 шт.<br />
			Косвенно говорит о проблемах с линией связи (Порт - Шнур - Порт, т.е. это не обязательно шнур виноват).
		</td>
	</tr>
	<tr>
		<td>&nbsp;SimTech&nbsp;</td>
		<td>Кол-во отвалов SimTech по логу ОБ по блокам сцепки.</td>
		<td>
			Нормальная ситуация - отвалов &lt;50 шт.<br />
			Их больше чем других потому что на каждый отвал можема генерится около 4-х событый.
			Косвенно говорит о проблемах с линией связи (Порт - Шнур - Порт, т.е. это не обязательно шнур виноват).
		</td>
	</tr>
	<tr>
		<td>&nbsp;GPS&nbsp;</td>
		<td>Кол-во отвалов GPS по логу ОБ по блокам сцепки.</td>
		<td>
			Нормальная ситуация - отвалов &lt;10 шт.<br />
			Косвенно говорит о проблемах с линией связи (Порт - Шнур - Порт, т.е. это не обязательно шнур виноват).
		</td>
	</tr>
	<tr>
		<td>&nbsp;Modem&nbsp;</td>
		<td>Кол-во отвалов Modem по логу ОБ по блокам сцепки.</td>
		<td>
			Нормальная ситуация - отвалов &lt;10 шт.<br />
			Косвенно говорит о проблемах с линией связи (Порт - Шнур - Порт, т.е. это не обязательно шнур виноват).
		</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
	</tr>

	<tr>
		<th colspan="2">Цветовая дифференсация общества</th>
		<th>Значение</th>
	</tr>
	<tr>
		<td colspan="2">Красный фон строки</td>
		<td>Насыщенность цвета соответствует разнице в % либо в абсолютной цифре. Шаг изменения цвета и принцип выбора метрики - индивидуален для каждого параметра.</td>
	</tr>
	<tr>
		<td colspan="2">Серый текст</td>
		<td>Значения находящиеся в норме на обоих вагонах.</td>
	</tr>
	<tr>
		<td colspan="2">Черный текст</td>
		<td>Значения сильно лучше чем на втором вагоне. Стоит посмотреть на соседа.</td>
	</tr>

	<tr>
		<th colspan="2">Трактовки комплексных ситуаций</th>
		<th>Значение</th>
	</tr>
	<tr>
		<td colspan="2"> по времени на связи и множественные отвалы GPS  и Modem.</td>
		<td>Явно какая-то проблема с модемом</td>
	</tr>
	<tr>
		<td colspan="2">Одновременный провал данных от гарнитуры и множественные отвалы BG</td>
		<td>Явная проблема с BG</td>
	</tr>
	<tr>
		<td colspan="2">Одна из вышеперечисленных ситуаций, но нет отвалов INK и ANT</td>
		<td>Если не отваливается INK / ANT, а отваваются кокретные устройства - то нет смысла грешить на блочную часть и на межкоробочную линию связи.</td>
	</tr>


</table>



<table class='tbl'>

	<!--tr>
		<td></td>
		<td></td>
		<td></td>
	</tr-->
</table>
<!-- END legend -->

<script type="text/javascript">
// hide columns by defaULT - НЕ РАБОТАЕТ
//jQuery('.onb_HID_Broken').hide();
///jQuery('.onb_HID_Broken_Label').show();
</script>


<p>[time spent is: [{SPENT_TIME}] sec]</p>