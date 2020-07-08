<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<h3>Смены</h3>
<!--h1>не полетело. в студии квиря выполняется, а в скрипте - пустое возвращает.</h1-->
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} </h4>
<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width="95%">
	<tr>
		<th>ФИО</th>
		<th>Начало</th>
		<th>Окончание</th>
		<th>Продолжительность</th>
		<th>В кабине</th>
		<th>Вне кабины</th>
		<th>Тип</th>
	</tr>
<!-- BEGIN row -->
	<tr>
		<td>{row.FIO}</td>
		<td align="center">{row.STARTED}</td>
		<td align="center">{row.FINISHED}</td>
		<td align="center">{row.LENGTH}</td>
		<td align="center">{row.INSIDE}</td>
		<td align="center">{row.OUTSIDE}</td>
		<td align="center">{row.KIND}</td>
	</tr>
<!-- END row -->
</table>

<p><hr /></p>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Смены (за период С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} Сервер: {SERVER} ({CLIENT_NAME})) {{collapse(Показать)
|ФИО	|Начало	|Окончание	|Продолжительность	|В кабине	|Вне кабины	|Тип	|
<!-- BEGIN row -->
|{row.FIO}|{row.STARTED}|{row.FINISHED}|{row.LENGTH}|{row.INSIDE}|{row.OUTSIDE}|{row.KIND}|
<!-- END row -->
}}
</textarea>

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
