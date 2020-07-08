<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<!--h3>Сервер: Anal</h3-->
<h3>История состояний водителя (макс 1000 строк).</h3>
<!--h1>не полетело. в студии квиря выполняется, а в скрипте - пустое возвращает.</h1-->
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} </h4>
	<table width='90%' class="tbl">
		<tr>
			<th>ФИО</th>
			<th>Состояние</th>
			<th>Satrted</th>
			<th>Finished</th>
		</tr>
		<!-- BEGIN row -->
		<tr>
			<td align='center'>{row.FIO}</td>
			<td align='center'>{row.STATE}</td>
			<td align='center'>{row.STARTED}</td>
			<td align='center'>{row.FINISHED}</td>
			</td>
		</tr>
		<!-- END row -->
	</table>

<p><hr /></p>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* История состояний водителя (макс 1000 строк). (за период С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} Сервер: {SERVER} ({CLIENT_NAME})) {{collapse(Показать)
|ФИО	|Состояние	|Satrted	|Finished	|
<!-- BEGIN row -->
|{row.FIO}|{row.STATE}|{row.STARTED}|{row.FINISHED}|
<!-- END row -->
}}
</textarea>

{REPORT}

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
