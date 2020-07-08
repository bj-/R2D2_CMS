<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
	<table width='90%'>
		<tr><th align="left" colspan="2">
			Согласно данным аналитического терминала АСПМ, за период С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} на терминалы оперативного мониторинга (оператору) было выдано:
		</th></tr>
		<!-- BEGIN row -->
		<tr>
			<td align='left'>{row.NAME}
			</td>
			<td align='center' width="10%">{row.VALUE} шт.
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
