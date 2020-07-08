<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<h3>Отчет: Гарнитуры теряющие более 90% заряда менее чем за 8 часов</h3>
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} </h4>
	<table width='90%'>
		<tr>
			<th>ФИО</th>
			<th>HID</th>
			<th>Среднее время работы от батарейки</th>
			<th>Раз за период</th>
		</tr>
		<!-- BEGIN row -->
		<tr>
			<td>{row.FIO}</td>
			<td>{row.SERIALNO}</td>
			<td align='center'>{row.AVG_WORKING_TIME}</td>
			<td align='right'>{row.COUNT}</td>
			</td>
		</tr>
		<!-- END row -->
	</table>
</form>

{REPORT}

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
