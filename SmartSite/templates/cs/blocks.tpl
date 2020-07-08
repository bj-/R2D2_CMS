<div class="textwrapper">
	<a href="include-long.htm" onclick="return hs.htmlExpand(this, { objectType: 'ajax', contentId: 'highslide-html-8' } )"
			class="highslide">
	</a>
	<div class="highslide-html-content" id="highslide-html-8" style="padding: 15px; width: auto">
	    <div class="highslide-body" style="padding: 10px"></div>
	</div>

</div>
<script type="text/javascript">
</script>

<!-- BEGIN version_types -->
<!-- Last Version: {VERSION_MAX} ({VERSION_MAX_CNT} / {VERSION_TOTAL} = {VERSION_PERCENT} %) -->
<table class="tbl" align="right" width="40%">
	<tr>
		<th>Тип БО</th>
		<th>Версия</th>
		<th>Обновлено</th>
		<th>Всего</th>
		<th>Процент</th>
	</tr>
	<!-- BEGIN ver -->
	<tr>
		<td>{version_types.ver.TYPE}</td>
		<td title="Минимальная версия: {version_types.ver.MIN}" align="center">{version_types.ver.MAX}</td>
		<td align="center">{version_types.ver.MAX_CNT}</td>
		<td align="center">{version_types.ver.TOTAL}</td>
		<td align="center">{version_types.ver.PERCENT} %</td>
	</tr>
	<!-- END ver -->
</table>
<!-- END version_types -->

<table cellspacing='1px' cellpadding='0px' border='0px' class='tbl' width='100%'>
	<tr>
		<th>BlockNo</th>
		<!--th>Conn</th-->
		<th>IP</th>
		<th colspan="2"><a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Blocks&prms=Filter=LastVersion', '#Blocks');" Title='Current block Sw version. Click to open block configuration report.'>Ver.</a></th>
		<th><a Title='Last time changed connection state (MSK time)'>Changed</a></th>
		<th>Position</th>
		<!-- BEGIN col_route -->
		<th><a Title='Route number. [by Server / by Block] or [Identical]'>Route<br />Srv/Bl</a></th>
		<!-- END col_route -->

		<th><a Title="Группы к которым привязано ТС (Депо/Линия/итд)">Grp</a></th>
		<th><a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Blocks&prms=Filter=NoCfgPacket', '#Blocks');" Title='Line (ConfigurationPacket).'>Ln</a></th>
		<th align="center" valign="center">
			<a href='/dynamic/proxy.php?srv={SERVER_NAME}&page=Hardware_Transer_Between_Blocks&prms=HwName=BG_MAK_Address;HwTitle=BOVI;HwCode=MAK_BG&rnd={RANDOM}' onclick="return hs.htmlExpand(this, { slideshowGroup: 'hwTransfer-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"  title='Orientation and BOVI FW version (2 last HEX sym)'>BOVI</a>
		</th>
		<th width="25" align="center" valign="center">
			<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Blocks&prms=Filter=ModemConnect', '#Blocks');">
			<img src='/pic/ico/phone_2_24x24.png' width='14' height='14' Title='Modem Connections (Sessions on xx% les than second wagon)' /></a>
		</th>
		<th width="25" align="center" valign="center">
			<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Blocks&prms=Filter=BTDiff', '#Blocks');">
			<img src='/pic/ico/BlueTooth_1_24x24.png' width='16' height='16' Title='BlueTooth diff (Got station labels xx% less than second wagon of train)' /></a>
		</th>
		<!-- BEGIN col_positionlost -->
		<th width="25" align="center" valign="center">
			<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Blocks&prms=Filter=StationsNoChange', '#Blocks');">
			<img src='/pic/ico/hangar_20x20.png' width='16' height='16' Title='Не менются станции ХХ времени' /></a>
		</th>
		<!-- END col_positionlost -->
		<th width="60" align="center" valign="center">
			<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Blocks&prms=Filter=USBLost', '#Blocks');">
				<!--img src='/pic/ico/usb_24x24.png' width='16' height='16' Title='USB Devices lost' /-->
				<img src='/pic/ico/scull_20x20.png' width='14' height='16' Title='USB Devices lost' />
			</a>
		</th>
		<th width="25" align="center" valign="center">
			<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Blocks&prms=Filter=BlockAlerts', '#Blocks');">
			<img src='/pic/ico/quest_12x20.png' width='12' height='16' Title='Blocks Alerts' /></a>
		</th>
		<th width="25" align="center" valign="center">
			<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Blocks&prms=Filter=SrvcCrashes', '#Blocks');">
			<img src='/pic/ico/attention_2_24x24.png' width='16' height='16' Title='Crash or Exeption (Today / Yesterday / d.b.Yesterday)' /></a>
		</th>
		<th width="25" align="center" valign="center">
			<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Blocks&prms=Filter=SrvcReset', '#Blocks');">
			<img src='/pic/ico/reboot_18x18.png' width='16' height='16' Title='Services restarts (Today)' /></a>
		</th>
		<th width="25" align="center" valign="center">
			<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Blocks&prms=Filter=BlocksRainbow', '#Blocks');">
			<img src='/pic/ico/rainbow_20x20.png' width='16' height='16' Title='Блоки с нетрадиционной ориентацией' /></a>
		</th>
		<th width="25" align="center" valign="center">
			<img src='/pic/ico/attention_20x20.png' width='16' height='16' Title='Другие ошибки' />
		</th>
		<th width="25" align="center" valign="center">
			<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Blocks&prms=Filter=TasksExist', '#Blocks');">
			<img src='/pic/ico/task_128x128.png' width='16' height='16' Title='Жалобы по блоку (Иконка: Есть открытые жалобы)' />
			</a>
		</th>
	</tr>

	<!-- BEGIN row -->
	<tr style=''>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_RENAME_ERROR}'>
			<a href="/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockDetails&prms=Server={row.BLOCKSERIALNO}&rnd={RANDOM}" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'BlockSerNo-group', objectType: 'ajax', contentId: 'highslide-html-8' } );">
				{row.BLOCK}
			</a>
			<span style="color:#A0A0A0;">{row.SECOND_WAGON}</span>
			<!-- BEGIN wagonnotconnected -->
			<img src="/pic/ico/bus_16x16.png" width="16" height="16" title="Server does not connected to wagon or wagon does not exist">
			<!-- END wagonnotconnected -->
			<div name="{row.BLOCKSERIALNO}_versionProp" id="{row.BLOCKSERIALNO}_versionProp" 
				style="position:absolute;display:none;margin-left:-200px;background-color:white;padding:10px;border:5px;box-shadow: -1px -1px 5px 5px rgba(0, 0, 0, .2);color:black;font-weight:normal;">
				<p align="right"><a style="cursor:pointer;font-weight:bold;align:right;" onclick="ShowHideDiv('{row.BLOCKSERIALNO}_versionProp');">[ CLOSE ]</a></p>
				<br>
				<h3>By block reports: {row.BCFG_BLOCKSERNO}</h3>
				<table cellspacing="1px" cellpadding="1px" class="tbl" width="100%">
					<tbody>
					<tr>
						<th width="200px">Name</th>
						<th>Value</th>
					</tr>
					<tr>
						<td>Report Date</td>
						<td><span style="font-weight:bold;{row.STYLE_BCFG_REPORTDATE}">{row.BCFG_REPORT_DATE} ({row.BCFG_DAY_AGO})</span></td>
					</tr>
					<tr>
						<td>BlockSerialNo / Hostname</td>
						<td>{row.BCFG_HOSTNAME} / {row.BCFG_BLOCKSERNO}</td>
					</tr>
					<tr>
						<td>Block Type</td>
						<td>{row.BLOCK_TYPE}</td>
					</tr>
					<tr>
						<td>
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=IPAddress', '#{row.BLOCKSERIALNO}_blockhistory');">IP Address</a>
							(<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=APNName', '#{row.BLOCKSERIALNO}_blockhistory');">APN</a>)
						</td>
						<td>{row.BCFG_IP_ADDRESSES}; ({row.BCFG_APN_NAME})</td>
					</tr>
					<tr>
						<td><a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=FirmwareEINKver', '#{row.BLOCKSERIALNO}_blockhistory');">Firmware EINK ver</a></td>
						<td>
							Тип: {row.BCFG_FIRMWARE_DIOS_TYPE}
							Версия: <span style="{row.STYLE_BCFG_FIRMWARE_EINK}">{row.BCFG_FIRMWARE_EINK}</span> 
							(проверно: {row.BCFG_FIRMWARE_EINK_CHECKTIME} )
						</td>
					</tr>
					<tr>
						<td><a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=BlockOrientation', '#{row.BLOCKSERIALNO}_blockhistory');">Orientation</a></td>
						<td>
							{row.BCFG_FIRMWARE_EINK_ORIENTATION} 
							{row.BCFG_FIRMWARE_EINK_ORIENTATION_IMG} 
						</td>
					</tr>
					<tr>
						<td>
							Route [ 
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=BaseRoute', '#{row.BLOCKSERIALNO}_blockhistory');">Base</a> / 
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=CurrentRoute', '#{row.BLOCKSERIALNO}_blockhistory');">Current</a> / 
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=MaxRoute', '#{row.BLOCKSERIALNO}_blockhistory');">Max</a> ]
						</td>
						<td>{row.BCFG_ROUTE_MIN} / {row.BCFG_ROUTE_CURRENT} / {row.BCFG_ROUTE_MAX}</td>
					</tr>
					<tr>
						<td><a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=ConfigurrationPacket', '#{row.BLOCKSERIALNO}_blockhistory');">Line</a></td>
						<td style="{row.STYLE_BCFG_CONFIG_PACKET}">{row.BCFG_CONFIG_PACKET_SHORT}</td>
					</tr>
					<tr>
						<td>
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=ConfigurrationPacket', '#{row.BLOCKSERIALNO}_blockhistory');">Configurration Packet</a>
						</td>
						<td style="{row.STYLE_BCFG_CONFIG_PACKET}">{row.BCFG_CONFIG_PACKET}</td>
					</tr>
					<tr>
						<td>
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=SoftwareVer', '#{row.BLOCKSERIALNO}_blockhistory');">Software Ver</a>
						</td>
						<td>
							{row.BCFG_SERVICES_VERSIONS}
						</td>
					</tr>
					<tr>
						<td>
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=SrvcQueueSizes', '#{row.BLOCKSERIALNO}_blockhistory');">Queues sizes</a>
						</td>
						<td>
							{row.BCFG_QUEUES}
						</td>
					</tr>
					<tr>
						<td>
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=SrvcLastLogRecord', '#{row.BLOCKSERIALNO}_blockhistory');">Log Last Record</a>
						</td>
						<td>
							{row.BCFG_LOGLASTREC}
						</td>
					</tr>
					<tr>
						<td style="">
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=SoftwareVer;type=ServicesStarts', '#{row.BLOCKSERIALNO}_blockhistory');">Services Starts</a> (Today)
						</td>
						<td>{row.BCFG_SERVICES_RESTARTS}</td>
					</tr>
					<tr>
						<td>Services Running</td>
						<td>{row.BCFG_SERVICES_RUNNING}</td>
					</tr>
					<tr>
						<td>Services Stopped</td>
						<td>{row.BCFG_SERVICES_STOPPED}</td>
					</tr>
					<tr>
						<td>
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=PacketInstalled', '#{row.BLOCKSERIALNO}_blockhistory');">Packet Installed</a>
						</td>
						<td>{row.BCFG_PACKETS_INSTALLED}</td>
					</tr>
					<tr>
						<td>
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=ServersPool', '#{row.BLOCKSERIALNO}_blockhistory');">Servers Pool</a>
						</td>
						<td>{row.BCFG_SERVERS_POOL}</td>
					</tr>
					<tr>
						<td>
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=StartTime', '#{row.BLOCKSERIALNO}_blockhistory');">Start</a> 
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=UpTime', '#{row.BLOCKSERIALNO}_blockhistory');">(Up)</a>
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=UpTimeSeconds', '#{row.BLOCKSERIALNO}_blockhistory');">(UpS)</a>
							 time 
						</td>
						<td>{row.BCFG_SYSTEM_STARTED} ({row.BCFG_TIMEZONE}) UpTime: {row.BCFG_STARTUPTIME}{row.BCFG_SYSTEM_STARTED_DATE_FAIL}</td>
					</tr>
					<tr>
						<td>
							UUID 
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=UUID_nanda', '#{row.BLOCKSERIALNO}_blockhistory');">nanda</a> / 
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=UUID_sda', '#{row.BLOCKSERIALNO}_blockhistory');">sda</a>
						</td>
						<td>{row.BCFG_UUID_NANDA} / {row.BCFG_UUID_SDA}</td>
					</tr>
					<tr>
						<td>
							MAK 
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=MAK_usb', '#{row.BLOCKSERIALNO}_blockhistory');">USB</a> / 
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=MAK_wlan', '#{row.BLOCKSERIALNO}_blockhistory');">WiFi</a> / 
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=MAK_BG', '#{row.BLOCKSERIALNO}_blockhistory');">BG</a>
						</td>
						<td>{row.BCFG_MAK_USB} / {row.BCFG_MAK_WIFI} / {row.BCFG_MAK_BG}</td>
					</tr>
					<tr>
						<td>
							<a style="cursor:pointer;" onclick="show_content('/dynamic/proxy.php?srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=UID_Hardware', '#{row.BLOCKSERIALNO}_blockhistory');">UID Hardware</a>
						</td>
						<td>{row.BCFG_HARDWARE_UID}</td>
					</tr>
				</tbody>
				</table>
			
				<div id="{row.BLOCKSERIALNO}_blockhistory">...</div>

			</div>
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT}' title="{row.BCFG_APN_NAME}" >{row.IPADDRESS}</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT}' align="center">
				{row.BLOCK_TYPE_IMG}
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_VERSION}' align="center">
			<a style='cursor:pointer;' OnClick="ShowHideDiv('{row.BLOCKSERIALNO}_versionProp');" title="">
				{row.VERSION}
			</a>
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_STARTED_DATE_FAIL_ALERT}'>
			<a title="Full date: {row.CONNECTED_FULL}{row.BCFG_SYSTEM_STARTED_DATE_FAIL}" style="cursor:default;">{row.CONNECTED}</a>
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT}'>
			{row.WAY_DIRECTION}
			<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Block_OnbText&prms=BlockSerNo={row.BLOCKSERIALNO};Filter=Position" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'position-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
				title="Местоположение изменено: {row.POSITION_CHANGED_TIME} ({row.POSITION_CHANGED_TIME_AGO} мин назад / ~{row.POSITION_CHANGED_TIME_AGO_FORMATED})" 
				style="cursor:pointer;color:black; {row.STYLE_POSITION_CHANGE_ALERT}">
				{row.STATION_NAME} {row.POSITION_CHANGED_TIME_AGO_ALERT_FORMATED} {row.GEO_ADDRESS}
			</a>
			<!-- BEGIN driver -->
			<img src="/pic/ico/person{row.driver.DRIVER_ICO_TYPE}_20x20.png" width="15" height="16" title="Driver: [{row.driver.DRIVER_FIO}] ({row.driver.DRIVER_HID_ID}), Bat: [{row.driver.DRIVER_BAT}%], FW ver: [{row.driver.DRIVER_FW_VER}]">
			{row.DRIVER_ADD}
			<!-- END driver -->
			{row.TEST}
		</td>
		<!-- BEGIN col_route -->
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT}'>
				<a title="Маршрут изменен: {row.ROUTE_CHANGED_TIME} Srv: [{row.ROUTE_SRV}] / Block: [{row.ROUTE_BLOCK}]">
					{row.ROUTE}
				</a>
			</td>
		<!-- END col_route -->
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT}' Title="Группы: {row.GROUPS}">
			{row.GROUPS_SHORT}
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_BCFG_CONFIG_PACKET}'>
			<span title="{row.BCFG_CONFIG_PACKET}">{row.BCFG_CONFIG_PACKET_SHORT}</span>
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_BCFG_FIRMWARE_EINK} {row.STYLE_BCFG_BOVI_TYPE}' align="center">
			{row.BCFG_FIRMWARE_EINK_ORIENTATION_IMG}
			{row.BCFG_FIRMWARE_EINK_SHORT}
		</td>

		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_MODEM_ALERT}' align="center">
			<!--a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=BlockStatHistory&prms={row.BLOCK}"--> 
			<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Block_Diag_Stat&prms=BlockSerNo={row.BLOCKSERIALNO}" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'modem-group', objectType: 'ajax', contentId: 'highslide-html-8' } )">
				<!--{row.WORKING_TIME}-->
				{row.DIAG_CONNECTION_TIME}
			</a>
		</td>

		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_BT_ALERT}' align="center">
			<!--a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=BlockStatHistory&prms={row.BLOCK}"--> 
			<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Block_Working_History&prms=BlockSerNo={row.BLOCKSERIALNO}" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'bluetooth-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
				title="Got Station labels on {row.STATION_CNT_PERCENT}% less than second block. Stations labels cnt: [{row.STATION_CNT}] / [{row.STATION_CNT_SECOND}]">
				<!--{row.BT_ALERT}-->
				{row.DIAG_BT}
			</a>
		</td>                                                                      
		<!-- BEGIN col_positionlost -->
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_POSLOST_ALERT}' align="center">
			<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=BlockPosNotChanged&params=Server={row.BLOCKSERIALNO}" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'BlockPosNotChanged-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
				title="">
				{row.POSITION_LOST}
			</a>
		</td>                                                                      
		<!-- END col_positionlost -->
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_USB_ALERT}' align="center" title="{row.USB_TITLE}">
			<!--a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Block_USBLost_History&prms=BlockSerNo={row.BLOCKSERIALNO}" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'BlockUSBLost-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
				title=""-->
			<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Block_Diag_Stat&prms=BlockSerNo={row.BLOCKSERIALNO}" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'diag-group', objectType: 'ajax', contentId: 'highslide-html-8' } )">


				{row.BLOCK_DIAG}
				<!--{row.USB_ALERT}-->
				<!--{row.USB_LOST_INK}{row.USB_LOST_ANT}{row.USB_LOST_BG}{row.USB_LOST_ST}{row.USB_LOST_MODEM}{row.USB_LOST_GPS}-->
			</a>
		</td>                                                                      
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_BLOCK_ALERT}' align="center">
			<!--a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=BlockUSBLost&params=Server={row.BLOCKSERIALNO}" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'BlockUSBLost-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
				title=""-->
				{row.BLOCK_ALERT}
			<!--/a-->
		</td>                                                                      
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_ERORRS}' align="center">
			<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=spbMetroBlockErrorsDetails&prms={row.BLOCKSERIALNO}" 
				onclick="return hs.htmlExpand(this, {  slideshowGroup: 'errors-group', objectType: 'ajax', contentId: 'highslide-html-8' } )">
				{row.ERORRS}
			</a>
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_BCFG_SRVC_RESTARTS}' align="center">
			<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=BlockHistory&prms=BlockSerNo={row.BLOCKSERIALNO};property=SoftwareVer;type=ServicesStarts" 
				onclick="return hs.htmlExpand(this, {  slideshowGroup: 'errors-group', objectType: 'ajax', contentId: 'highslide-html-8' } )">
				{row.SRVC_RESTARTS}
			</a>
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT}' align="center">
			<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=BlockServicesWorkingTimeHistory&prms=BlockSerNo={row.BLOCKSERIALNO}" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'BlockServiceWorkingTime-group', objectType: 'ajax', contentId: 'highslide-html-8' } )">
				{row.SVC_ALERT}
			</a>
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_ATTENTION}' align="center">
			<!--a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=BlockServicesWorkingTimeHistory&prms=BlockSerNo={row.BLOCKSERIALNO}" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'BlockServiceWorkingTime-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"-->
				{row.ATTENTION}
			<!--/a-->
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT}' align="center">
			<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=Tasks&prms=ObjectId={row.BLOCKSERIALNO};ObjectType=Block" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'Tasks-group', objectType: 'ajax', contentId: 'highslide-html-8' } )">
				{row.TASK}
			</a>
		</td>
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

<table class='tbl'>
	<tr>
		<th>Обозначение</th>
		<th>Значение</th>
		<th>Что делать</th>
	</tr>
	<tr>
		<td>Красный фон строки</td>
		<td>Блок в сцепке, но не выходит на связь более Х Дней</td>
		<td>Осмотр на месте с заменой неисправного</td>
	</tr>
	<tr>
		<td>Красный текст</td>
		<td>Блок не эксплуатируется с указанной даты</td>
		<td>---</td>
	</tr>
	<tr>
		<td>
			&nbsp;<img src='/pic/ico/phone_2_24x24.png' width='14' height='14' Title='Modem Connections (Sessions on xx% les than second wagon)' />&nbsp;
			&nbsp;<img src='/pic/ico/BlueTooth_1_24x24.png' width='16' height='16' Title='BlueTooth diff (Got station labels xx% less than second wagon of train)' />&nbsp;
		</td>
		<td>Чем ярче тем сильнее болеет. Для более точного диагноза смотреть историю.</td>
		<td>Если в истории будет часто красное - что-то менять ( ОБ / Шнур / БОВИ )</td>
	</tr>
	<tr>
		<td>№ Вагона</td>
		<td>Номер вагона и с чем он сцеплен в данный момент</td>
		<td>Клик: Скачать логи с блока / Выполнить дейтсвия на блоке.</td>
	</tr>
	<tr>
		<td><a Title='Current block Sw version. Click to open block configuration report.'>Version</a></td>
		<td>Версия ПО.</td>
		<td>Клик: Детальный отчет по конфигурации блока с историей изменений каждого параметра</td>
	</tr>
	<tr>
		<td><a Title='Last time changed connection state (MSK time)'>Changed</a></td>
		<td colspan='2'>Последнее изменение состояния 'на связи'/'не на связи'</td>
	</tr>
	<tr>
		<td>Красный фон Местоположения</td>
		<td>Блок в сцепке, на связи, но не меняет местоположение более [хх] минут</td>
		<td>Вероятно проблема с блюгигой.</td>
	</tr>
	<tr>
		<td><a Title='Route number. [by Server / by Block] or [Identical]'>Route<br />Srv/Bl</a></td>
		<td colspan='2'>Номер маршрута установленный на Сервере и на блоке ( Сервер / Блок ). если номер только один - номер совпадает на обоих сторонах.</td>
	</tr>
	<tr>
		<td><a Title='Line'>Ln</a></td>
		<td colspan='2'>Краткое название конфигурационного пакета привязывающего блок к конкретной линии.</td>
	</tr>
	<tr>
		<td><a title='Orientation and BOVI FW version (2 last HEX sym)'>BOVI</a></td>
		<td>Ориентация экрана и номер версии.</td>
		<td>
			- Если указан номер версии на красном фоне - на блоке стоит старая версия и блок надо перепрошивать. Должно выполняться автоматически.<br />
			- Просто красный фон и/или N/A - номер версии не считался. игнорировать.
		</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/phone_2_24x24.png' width='14' height='14' Title='Modem Connections (Sessions on xx% les than second wagon)' />&nbsp;</td>
		<td>Соотношение суммы периодов на связи относительно второго блока текущей сцепки.</td>
		<td>Клик: просмотр истории.</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/BlueTooth_1_24x24.png' width='16' height='16' Title='BlueTooth diff (Got station labels xx% less than second wagon of train)' />&nbsp;</td>
		<td>Соотношение суммы зафиксированных станций относительно второго блока текущей сцепки.</td>
		<td>Клик: просмотр истории.</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/hangar_20x20.png' width='16' height='16' Title='Поезд двигается по линии, но не меняются станции' />&nbsp;</td>
		<td>Поезд двигается по линии, но не меняются станции у этой головы.</td>
		<td>ХЗ</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/pulse_20x20.png' width='16' height='16' Title='Получено RR шт по блокам сцепки.' />&nbsp;</td>
		<td>Получено RR шт по блокам сцепки; Тревога если за последние 7 дней повторялось несколько раз.</td>
		<td>При систематическом отклонении - замена БОВИ / ОБ / Шнурка</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/wireless-charging_20x20.png' width='16' height='16' Title='Кол-во раз в логе BG встречено событие STH-Connect по блокам сцепки' />&nbsp;</td>
		<td>Кол-во раз в логе BG встречено событие STH-Connect по блокам сцепки; Тревога если за последние 7 дней повторялось несколько раз.</td>
		<td>--//--</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/hid-ear_20x20.png' width='16' height='16' Title='Кол-во раз в логе BG упомянуто STH по блокам сцепки.' />&nbsp;</td>
		<td>Кол-во раз в логе BG упомянуто STH по блокам сцепки; Тревога если за последние 7 дней повторялось несколько раз.</td>
		<td>--//--</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/label_20x20.png' width='16' height='16' Title='Кол-во раз в логе BG упомянуто STL по блокам сцепки.' />&nbsp;</td>
		<td>Кол-во раз в логе BG упомянуто STL по блокам сцепки; Тревога если за последние 7 дней повторялось несколько раз.</td>
		<td>--//--</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/ant-2_20x20.png' width='16' height='16' Title='Кол-во отвалов ANT по логу ОБ по блокам сцепки.' />&nbsp;</td>
		<td>Кол-во отвалов ANT по логу ОБ по блокам сцепки; Тревога если за последние 7 дней повторялось несколько раз.</td>
		<td>--//--</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/bluetooth-2_20x20.png' width='16' height='16' Title='Кол-во отвалов BT по логу ОБ по блокам сцепки.' />&nbsp;</td>
		<td>Кол-во отвалов BT по логу ОБ по блокам сцепки; Тревога если за последние 7 дней повторялось несколько раз.</td>
		<td>--//--</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/display_24x24.png' width='16' height='16' Title='Кол-во отвалов INK по логу ОБ по блокам сцепки.' />&nbsp;</td>
		<td>Кол-во отвалов INK по логу ОБ по блокам сцепки; Тревога если за последние 7 дней повторялось несколько раз.</td>
		<td>--//--</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/chip_20x20.png' width='16' height='16' Title='Кол-во отвалов SimTech по логу ОБ по блокам сцепки.' />&nbsp;</td>
		<td>Кол-во отвалов SimTech по логу ОБ по блокам сцепки; Тревога если за последние 7 дней повторялось несколько раз.</td>
		<td>--//--</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/gps_16x16.png' width='16' height='16' Title='Кол-во отвалов GPS по логу ОБ по блокам сцепки.' />&nbsp;</td>
		<td>Кол-во отвалов GPS по логу ОБ по блокам сцепки; Тревога если за последние 7 дней повторялось несколько раз.</td>
		<td>--//--</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/modem_dongle_20x20.png' width='16' height='16' Title='Кол-во отвалов Modem по логу ОБ по блокам сцепки.' />&nbsp;</td>
		<td>Кол-во отвалов Modem по логу ОБ по блокам сцепки; Тревога если за последние 7 дней повторялось несколько раз.</td>
		<td>--//--</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/quest_12x20.png' width='12' height='16' Title='Активность сервисов в логах, размеры очередей' />&nbsp;</td>
		<td colspan='2'>Активность сервисов в логах, размеры очередей.</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/attention_2_24x24.png' width='16' height='16' Title='Crash or Exeption (Today / Yesterday / d.b.Yesterday)' />&nbsp;</td>
		<td>Падение сервисов на блоке</td>
		<td>Клик: просмотр падений</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/reboot_18x18.png' width='16' height='16' Title='Services restarts (Today)' />&nbsp;</td>
		<td colspan='2'>
			Количество рестартов сервисов. Краснеет если один из сервисов рестартует чаще чем другие.<br />
			Количество перезапусков блока. Краснеет если рестартов слишком много.
		</td>
	</tr>
	<tr>
		<td>&nbsp;<img src='/pic/ico/attention_20x20.png' width='16' height='16' Title='Другие ошибки' />&nbsp;</td>
		<td colspan='2'>Другие ошибки на блоке</td>
	</tr>

	<!--tr>
		<td></td>
		<td></td>
		<td></td>
	</tr-->
</table>
<!-- END legend -->

<p>[time spent is: [{SPENT_TIME}] sec]</p>