<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<h3>Сигналы Монотония и Стресс в вагоне и оператору (за период)</h3>
<!--h1>не полетело. в студии квиря выполняется, а в скрипте - пустое возвращает.</h1-->
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} </h4>
	<table width='90%' class="tbl">
		<tr>
			<th>ФИО</th>
			<th>HID</th>
			<th>Монотония в кабине</th>
			<th>Гиперактивация в кабине</th>
			<th>Монотония Опреатору</th>
			<th>Гиперактивация Оператору</th>
		</tr>
		<!-- BEGIN row -->
		<tr>
			<td>{row.FIO}</td>
			<td>{row.SERIALNO}</td>
			<td align='center'>{row.SLEEP_CABIN}</td>
			<td align='center'>{row.STRESS_CABIN}</td>
			<td align='center'>{row.SLEEP_OPERATOR}</td>
			<td align='center'>{row.STRESS_OPERATOR}</td>
			</td>
		</tr>
		<!-- END row -->
	</table>
	
<p><hr /></p>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Сигналы Монотония и Стресс в вагоне и оператору (за период С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} ) Сервер: {SERVER} ({CLIENT_NAME}) {{collapse(Показать)
|ФИО	|HID	|Монотония в кабине	|Гиперактивация в кабине	|Монотония Опреатору	|Гиперактивация Оператору|
<!-- BEGIN row -->
|{row.FIO}|{row.SERIALNO}|{row.SLEEP_CABIN}|{row.STRESS_CABIN}|{row.SLEEP_OPERATOR}|{row.STRESS_OPERATOR}|
<!-- END row -->
}}
</textarea>
	
{REPORT}

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
