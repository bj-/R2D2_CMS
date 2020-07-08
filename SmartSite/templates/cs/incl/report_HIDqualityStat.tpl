<!--h3>Сервер: sRoot</h3-->
<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<h3>Качество сигнала с гарнитуры (среднее за день и за месяц)</h3>
<!--h1>не полетело. в студии квиря выполняется, а в скрипте - пустое возвращает.</h1-->
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} </h4>

<span style="color:red;">{REQUIRED}</span>
	<table width='90%' class="tbl">
		<tr>
			<th rowspan="2">ФИО</th>
			<th colspan="5">За сутки</th>
			<th colspan="2">За месяц</th>
		</tr>
		<tr>
			<th>Дата</th>
			<th>Среднее</th>
			<th>Мин</th>
			<th>Макс</th>
			<th>Кол-во</th>
			<th>Среднее 30d</th>
			<th>Кол-во 30d</th>
		</tr>
		<!-- BEGIN row -->
		<tr>
			<td align='center'>{row.FIO}</td>
			<td align='center'>{row.DATE}</td>
			<td align='center'>{row.AVG_QUALITY}</td>
			<td align='center'>{row.MIN_QUALITY}</td>
			<td align='center'>{row.MAX_QUALITY}</td>
			<td align='center'>{row.COUNT}</td>
			<td align='center'>{row.AVG_QUALITY_30D}</td>
			<td align='center'>{row.COUNT_30D}</td>
			</td>
		</tr>
		<!-- END row -->
	</table>

<p><hr /></p>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Качество сигнала с гарнитуры на блоках (за период С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} Сервер: {SERVER} ({CLIENT_NAME})) {{collapse(Показать)
|ФИО	|Дата	|Среднее	|Мин	|Макс	|Кол-во	|Среднее 30d	|Кол-во 30d	|
<!-- BEGIN row -->
|{row.FIO}|{row.DATE}|{row.AVG_QUALITY}|{row.MIN_QUALITY}|{row.MAX_QUALITY}|{row.COUNT}|{row.AVG_QUALITY_30D}|{row.COUNT_30D}|
<!-- END row -->
}}
</textarea>

{REPORT}

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
