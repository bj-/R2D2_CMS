<table cellspacing="1px" cellpadding="0px" class="tbl" width="90%" border="0" style="background:#CCCCCC;">
		<tr>
			<th>Date</th>
			<th>Wagon</th>
			<th>Coupling</th>
			<th>Modem</th>
			<th>Modem %</th>
			<th>BT</th>
			<th>BT %</th>
			<th Title="Пришло RR'ов с блока">RR</th>
			<th Title="Пришло RR'ов с блока">RR %</th>
			<th Title="Сколько раз упомянута любая гарнитура в логе БГ">STH</th>
			<th Title="Сколько раз упомянута любая гарнитура в логе БГ">STH %</th>
			<th Title="Сколько раз подключалась любая гарнитура в логе БГ">STH C</th>
			<th Title="Сколько раз подключалась любая гарнитура в логе БГ">STH C %</th>
			<th Title="Сколько раз упомянута любая Метка в логе БГ">STL</th>
			<th Title="Сколько раз упомянута любая Метка в логе БГ">STL %</th>
		</tr>
	<!-- BEGIN row -->
		<tr align="center">
			<td>{row.DAY}</td>
			<td>{row.WAGON}</td>
			<td>{row.COUPLING}</td>
			<td>{row.MODEM} / {row.MODEM_SECOND}</td>
			<td style="{row.STYLE_MODEM}">{row.MODEM_DIFF}</td>
			<td>{row.BT} / {row.BT_SECOND}</td>
			<td style="{row.STYLE_BT}">{row.BT_DIFF}</td>
			<td>{row.RR} / {row.RR_SECOND}</td>
			<td style="{row.STYLE_RR}">{row.RR_DIFF}</td>
			<td>{row.STH} / {row.STH_SECOND}</td>
			<td style="{row.STYLE_STH}">{row.STH_DIFF}</td>
			<td>{row.STH_CONNECT} / {row.STH_CONNECT_SECOND}</td>
			<td style="{row.STYLE_STH_CONNECT}">{row.STH_CONNECT_DIFF}</td>
			<td>{row.STL} / {row.STL_SECOND}</td>
			<td style="{row.STYLE_STL}">{row.STL_DIFF}</td>
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
