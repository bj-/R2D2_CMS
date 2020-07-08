<h3>{BLOCK_SER_NO}</h3>
<p>Статистика по времени работы сервисов на блоке</p>

<table width='100%' border="0">
	<tr>
		<th>Date</th>
		<th>MAX Working Time</th>
		<th><img src="/pic/ico/display_24x24.png" width="16" height="16" title="Dios"> Dios</th>
		<th><img src="/pic/ico/BlueTooth_1_24x24.png" width="16" height="16" title="BlueGiga"> BlueGiga</th>
		<th><img src="/pic/ico/phone_2_24x24.png" width="14" height="14" title="Modem"> Modems</th>
		<th><img src="/pic/ico/Task_128x128.png" width="16" height="16" title="Cron "> Cron</th>
		<th><img src="/pic/ico/logic_24x24.png" width="14" height="14" title="Logic"> Logic</th>
		<th><img src="/pic/ico/calc_24x24.png" width="14" height="14" title="Math"> Math</th>
		<th><img src="/pic/ico/network_24x24.png" width="14" height="14" title="Network"> Netmon</th>
		<th><img src="/pic/ico/poweron_20x20.png" width="16" height="16" title="Power"> Power</th>
		<th><img src="/pic/ico/melody_24x24.png" width="16" height="16" title="Sound"> Sound</th>
		<th><img src="/pic/ico/usb_24x24.png" width="16" height="16" title="Udev"> Udev</th>
		<th><img src="/pic/ico/data-storage_24x24.png" width="16" height="16" title="Hub"> Hub</th>
	</tr>
	<!-- BEGIN row -->
	<tr>
		<td align='center'>{row.DATE}</td>
		<td align='center'>{row.MAXWORKINGTIME}</td>
		<td align='center' style="{row.DIOS_STYLE}">{row.DIOS}</td>
		<td align='center' style="{row.BLUEGIGA_STYLE}">{row.BLUEGIGA}</td>
		<td align='center' style="{row.MODEMS_STYLE}">{row.MODEMS}</td>
		<td align='center' style="{row.CRON_STYLE}">{row.CRON}</td>
		<td align='center' style="{row.LOGIC_STYLE}">{row.LOGIC}</td>
		<td align='center' style="{row.MATH_STYLE}">{row.MATH}</td>
		<td align='center' style="{row.NETMON_STYLE}">{row.NETMON}</td>
		<td align='center' style="{row.POWER_STYLE}">{row.POWER}</td>
		<td align='center' style="{row.SOUND_STYLE}">{row.SOUND}</td>
		<td align='center' style="{row.UDEV_STYLE}">{row.UDEV}</td>
		<td align='center' style="{row.HUB_STYLE}">{row.HUB}</td>
	</tr>
	<!-- END row -->
</table>

<h3>Легенда:</h3>
<p>По цветам выделяются Сервисы работавшие на ЧЧ:ММ:СС времени меньше чем самый долгоработающий сервис.</p>
<table>
<tr><td style="{ALERT_LEVEL1_STYLE}">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Работал на {ALERT_LEVEL1} меньше</td></tr>
<tr><td style="{ALERT_LEVEL2_STYLE}">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Работал на {ALERT_LEVEL2} меньше</td></tr>
<tr><td style="{ALERT_LEVEL3_STYLE}">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Работал на {ALERT_LEVEL3} меньше</td></tr>
<tr><td style="{ALERT_LEVEL4_STYLE}">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Работал на {ALERT_LEVEL4} меньше</td></tr>
<tr><td style="{ALERT_LEVEL5_STYLE}">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Работал на {ALERT_LEVEL5} меньше</td></tr>
<tr><td style="{ALERT_LEVEL6_STYLE}">&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Работал на {ALERT_LEVEL6} меньше</td></tr>
</table>
