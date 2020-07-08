<h3>Блоки в сцепке, но не обновлящие свое местоположение, при том что вторая голова ездит</h3>
<table cellspacing="1px" cellpadding="0px" class="tbl" width="100%" align="center" border="0" style="background:#CCCCCC;">
	<tr>
		<th rowspan="2">Coupling</th>
		<th colspan="4">Vehicle</th>
		<th colspan="4">Vehicle second</th>
	</tr>
	<tr>
		<th>Block</th>
		<th>Conn Changed</th>
		<th>Последнее местоположение</th>
		<th>Не меняется с</th>
		<!--th>Не обновлялось (время)</th-->

		<th>Block</th>
		<th>Conn Changed</th>
		<th>Последнее местоположение</th>
		<th>Последний раз менялось</th>
		<!--th>Не обновлялось (время)</th-->
	</tr>
	<!-- BEGIN row -->
	<tr>
		<td align="center">{row.COUPLING}</td>

		<td align="center">{row.BLOCKSERNO}</td>
		<td align="center">{row.CONNECTED}</td>
		<td align="center">{row.POSITION}</td>
		<td align="center">{row.DATE_TIME} {row.TIME_AGO_STR}</td>
		<!--td align="center">{row.TIME_AGO_F}</td-->

		<td align="center">{row.BLOCKSERNO_SECOND}</td>
		<td align="center">{row.CONNECTED_SECOND}</td>
		<td align="center">{row.POSITION_SECOND}</td>
		<td align="center">{row.DATE_TIME_SECOND} {row.TIME_AGO_STR_SECOND}</td>
		<!--td align="center">{row.TIME_AGO_F_SECOND}</td-->
	</tr>
	<!-- END row -->
</table>
{ARTICLE}
{TEMP}

<h3>Легенда</h3>
<ul>
<li>Статистика собирается только в период с 7 утра до 00 часов для уменьшения ложно положительных срабатываний</li>
<li><b>Ложно положительное срабатываение:</b> при включении состава только одна из голов видит метку, 
	статистика сравнивает когда последний раз видили метку обе головы и видит большую разницу = ложное срабатывание (вторая голова просто не успела увидеть метку).<br />
	Первый кто придумает как это исключить - может сообщить автору для исправления.
</li>
<li>Игнорируемые ситуации (не вызывающие жалобы):
<ul>
	<li>Оба вагона последний раз видели одну и туже станцию (поезд в нычке и метку видит только одна голова)</li>
	<li>Если последняя станция приморская - время срабатывания увеличено в 3 раза (из-за отсутвия меток на новых станциях получалось что при возврате на приморскую после оборота была большая разница между последним станциями)</li>
</ul>
</li>
<li></li>
<li></li>
</ul>