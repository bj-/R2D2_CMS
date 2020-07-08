<!--h3>Сервер: sRoot</h3-->
<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<h3>Качество сигнала с гарнитуры (текущее)</h3>
<!--h1>не полетело. в студии квиря выполняется, а в скрипте - пустое возвращает.</h1-->
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO}, время: MSK</h4>

<span style="color:red;">{REQUIRED}</span>
	<table width='90%' class="tbl">
		<tr>
			<th>ФИО</th>
			<th>Дата</th>
			<th>Качество</th>
		</tr>
		<!-- BEGIN row -->
		<tr>
			<td align='center'>{row.FIO}</td>
			<td align='center'>{row.DATE}</td>
			<td align='center'>{row.COUNT}</td>
			</td>
		</tr>
		<!-- END row -->
	</table>

<p><hr /></p>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Качество сигнала с гарнитуры на блоках (за период С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} Сервер: {SERVER} ({CLIENT_NAME})) {{collapse(Показать)
|ФИО	|Дата	|Кач-во	|
<!-- BEGIN row -->
|{row.FIO}|{row.DATE}|{row.COUNT}|
<!-- END row -->
}}
</textarea>

{REPORT}

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
