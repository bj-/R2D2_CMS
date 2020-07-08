<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<!--h3>Сервер: Anal</h3-->
<h3>Количество сообщений выданных машинисту о необходимости поправить гарнитуру на основе сообщений "Поправьте датчик на блоке" (OnBText3)</h3>
<!--h1>не полетело. в студии квиря выполняется, а в скрипте - пустое возвращает.</h1-->
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} </h4>
	<table width='90%' class="tbl">
		<tr>
			<th>Дата</th>
			<th>Кол-во</th>
		</tr>
		<!-- BEGIN row -->
		<tr>
			<td align='center'>{row.DATE}</td>
			<td align='center'>{row.COUNT}</td>
			</td>
		</tr>
		<!-- END row -->
	</table>

<p><hr /></p>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Количество сообщений выданных машинисту о необходимости поправить гарнитуру (за период С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} Сервер: {SERVER} ({CLIENT_NAME})) {{collapse(Показать)
|Дата	|Кол-во	|
<!-- BEGIN row -->
|{row.DATE}|{row.COUNT}|
<!-- END row -->
}}
</textarea>

{REPORT}

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
