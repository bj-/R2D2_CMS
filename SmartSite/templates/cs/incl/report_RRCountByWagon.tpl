<!--h3>Сервер: sRoot</h3-->
<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<h3>Полученно РРов по человеку в день на вагонах за период</h3>
<!--h1>не полетело. в студии квиря выполняется, а в скрипте - пустое возвращает.</h1-->
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} </h4>
	<table width='90%' class="tbl">
		<tr>
			<th>ФИО</th>
			<th>HID</th>
			<th>Дата</th>
			<th>Блок</th>
			<th>Кол-во</th>
		</tr>
		<!-- BEGIN row -->
		<tr>
			<td>{row.FIO}</td>
			<td align='center'>{row.SERIALNO}</td>
			<td align='center'>{row.DATE}</td>
			<td align='center'>{row.BLOCK}</td>
			<td align='center'>{row.COUNT}</td>
			</td>
		</tr>
		<!-- END row -->
	</table>

<p><hr /></p>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Полученно РРов по человеку в день на вагонах (за период С {DATE_FROM} ДО {DATE_TO} ) Сервер: {SERVER} ({CLIENT_NAME}) {{collapse(Показать)
|ФИО	|HID	|Дата	|Блок	|Кол-во	|
<!-- BEGIN row -->
|{row.FIO}|{row.SERIALNO}|{row.DATE}|{row.BLOCK}|{row.COUNT}|
<!-- END row -->
}}
</textarea>

{REPORT}

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
