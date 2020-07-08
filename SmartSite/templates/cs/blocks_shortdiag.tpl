<table cellspacing='1px' cellpadding='0px' border='0px' class='tbl' width='100%'>
	<tr>
		<th>BlockNo</th>
		<!--th>Conn</th-->
		<th>IP</th>
		<th Title='Current block Sw version. Click to open block configuration report.'>Ver.</th>
		<th Title='Last time changed connection state (MSK time)'>Changed</th>
		<th>Station</th>
		<th Title='Route number. [by Server / by Block] or [Identical]'>Route<br />Srv/Bl</th>
		<th Title="Группы к которым привязано ТС (Депо/Линия/итд)">Grp</th>
	</tr>

	<!-- BEGIN row -->
	<tr style=''>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_RENAME_ERROR}'>
				{row.BLOCK}
			<span style="color:#A0A0A0;">{row.SECOND_WAGON}</span>
			<!-- BEGIN wagonnotconnected -->
			<img src="/pic/ico/bus_16x16.png" width="16" height="16" title="Server does not connected to wagon or wagon does not exist">
			<!-- END wagonnotconnected -->
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT}'>{row.IPADDRESS}</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_VERSION}' align="center">
			{row.VERSION}
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_STARTED_DATE_FAIL_ALERT}'>
			<a title="Full date: {row.CONNECTED_FULL}{row.BCFG_SYSTEM_STARTED_DATE_FAIL}" style="cursor:default;">{row.CONNECTED}</a>
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT}'>
			{row.WAY_DIRECTION}
			{row.STATION_NAME} {row.POSITION_CHANGED_TIME_AGO_ALERT_FORMATED}
			<!-- BEGIN driver -->
			<img src="/pic/ico/person{row.driver.DRIVER_ICO_TYPE}_20x20.png" width="15" height="16" title="Driver: [{row.driver.DRIVER_FIO}] ({row.driver.DRIVER_HID_ID}), Bat: [{row.driver.DRIVER_BAT}%], FW ver: [{row.driver.DRIVER_FW_VER}]">
			{row.DRIVER_ADD}
			<!-- END driver -->
			{row.TEST}
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT}'><a title="Маршрут изменен: {row.ROUTE_CHANGED_TIME}">{row.ROUTE}</a></td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT}' Title="Группы: {row.GROUPS}">
			{row.GROUPS_SHORT}
		</td>
	</tr>
	<!-- END row -->
</table>
{ARTICLE}
{TEMP}
