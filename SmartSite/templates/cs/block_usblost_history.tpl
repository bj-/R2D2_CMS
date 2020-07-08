<h2>Потери USB устройств</h2>

<table cellspacing="1px" cellpadding="0px" class="tbl" width="90%" border="0" style="background:#CCCCCC;">
		<tr>
			<th>Date</th>
			<th>Wagon</th>
			<th>Coupling</th>
			<th>Connection Time</th>
			<th Title="INK">INK</th>
			<th Title="ANT">ANT</th>
			<th Title="BG">BG</th>
			<th Title="SimTech">SimTech</th>
			<th Title="Modem">Modem</th>
			<th Title="GPS">GPS</th>
		</tr>
	<!-- BEGIN row -->
		<tr align="center">
			<td>{row.DAY}</td>
			<td>{row.WAGON}</td>
			<td>{row.COUPLING}</td>
			<td>{row.WORKINGTIME} / {row.WORKINGTIME_SECOND}</td>
			<td style="{row.STYLE_INK}">{row.INK}</td>
			<td style="{row.STYLE_ANT}">{row.ANT}</td>
			<td style="{row.STYLE_BG}">{row.BG}</td>
			<td style="{row.STYLE_SIMTECH}">{row.SIMTECH}</td>
			<td style="{row.STYLE_MODEM}">{row.MODEM}</td>
			<td style="{row.STYLE_GPS}">{row.GPS}</td>
		</tr>
	<!-- END row -->
</table>
	
{ARTICLE}
{TEMP}

<!-- BEGIN legend -->
<h3>Легенда</h3>
<ul>
<li>Статистика по работе Блоков</li>
<li>Modem - время блока проведенное на связи в секундах</li>
<li>BT - количество меток станций увиденных блоком</li>
<li>% - на сколько больше/меньше значение относительно второго блока сцепки</li>
<li>Градациями красного подсвеченвы выходы за пороги 10-20-30-40-50-75%</li>
<li>"--" вместо % - показатели у обоих вагонов ниже минимального порога (мало ездили/почти не эксплуатировались в этот день) и проценты считать не имеет смысла.</li>
</ul>
<!-- END legend -->
