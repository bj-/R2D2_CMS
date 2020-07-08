<!--h3>Сервер: sRoot</h3-->
<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<h3>Падение батарейки гарнитуры</h3>
<!--h1>не полетело. в студии квиря выполняется, а в скрипте - пустое возвращает.</h1-->
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} </h4>

<span style="color:red;">{REQUIRED}</span>
	<table width='90%' class="tbl">
		<tr>
			<th>ФИО</th>
			<th>HID</th>
			<!--th>Блок</th-->
			<!--th>Текст</th-->
			<th>BAT</th>
			<th>Дата</th>
			<!--th>Кол-во</th-->
		</tr>
		<!-- BEGIN row -->
		<tr>
			<!--td align='center'>{row.BLOCK}</td-->
			<!--td align='center'>{row.TEXT}</td-->
			<td align='center'>{row.FIO}</td>
			<td align='center'>{row.SERIALNO}</td>
			<td align='center'>{row.BATTERY_LEVEL}</td>
			<td align='center'>{row.DATE}</td>
			</td>
		</tr>
		<!-- END row -->
	</table>

<p><hr /></p>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Падение батарейки гарнитуры (за период С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} Сервер: {SERVER} ({CLIENT_NAME})) {{collapse(Показать)
|ФИО	|HID	|BAT	|Дата	|
<!-- BEGIN row -->
|{row.FIO}|{row.SERIALNO}|{row.BATTERY_LEVEL}|{row.DATE}|
<!-- END row -->
}}
</textarea>

{REPORT}

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
