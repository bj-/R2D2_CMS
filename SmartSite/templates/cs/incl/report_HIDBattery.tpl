<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<h3>Заряд батарейки (за день)</h3>
<!--h1>не полетело. в студии квиря выполняется, а в скрипте - пустое возвращает.</h1-->
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} </h4>
	<table width='90%'>
		<tr>
			<th>ФИО</th>
			<th>HID</th>
			<th>Макс</th>
			<th>Мин</th>
			<th>Дата</th>
			<th>С</th>
			<th>По</th>
			<th>Отработано</th>
		</tr>
		<!-- BEGIN row -->
		<tr>
			<td>{row.FIO}</td>
			<td>{row.SERIALNO}</td>
			<td align='right'>{row.MAX_BAT}</td>
			<td align='right'>{row.MIN_BAT}</td>
			<td align='center'>{row.DATE}</td>
			<td align='center'>{row.START_TIME}</td>
			<td align='center'>{row.FINISH_TIME}</td>
			<td align='center'>{row.WORKING_TIME}</td>
			</td>
		</tr>
		<!-- END row -->
	</table>

<p><hr /></p>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Заряд батарейки (за день) (за период С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} Сервер: {SERVER} ({CLIENT_NAME})) {{collapse(Показать)
|ФИО	|HID	|Макс	|Мин	|Дата	|С	|По	|Отработано	|
<!-- BEGIN row -->
|{row.FIO}|{row.SERIALNO}|{row.MAX_BAT}|{row.MIN_BAT}|{row.DATE}|{row.START_TIME}|{row.FINISH_TIME}|{row.WORKING_TIME}|
<!-- END row -->
}}
</textarea>

{REPORT}

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
