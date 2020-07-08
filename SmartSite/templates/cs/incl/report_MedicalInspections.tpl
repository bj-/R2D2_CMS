<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<h3>Медосмотры прочитанные из гарнитуры</h3>
<!--h1>не полетело. в студии квиря выполняется, а в скрипте - пустое возвращает.</h1-->
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} </h4>
<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width="80%">
	<tr>
		<th>ФИО</th>
		<th>HID</th>
		<th>Дата Считывания</th>
		<th>Дата Медосмотра</th>
	</tr>
<!-- BEGIN row -->
	<tr>
		<td>{row.FIO}</td>
		<td align="center">{row.HID}</td>
		<td align="center">{row.EXAM_READ_DATE}</td>
		<td align="center">{row.EXAM_DATE}</td>
	</tr>
<!-- END row -->
</table>

<p><hr /></p>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Медосмотры прочитанные из гарнитуры (за период С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO} Сервер: {SERVER} ({CLIENT_NAME})) {{collapse(Показать)
|ФИО	|HID	|Дата Считывания	|Дата Медосмотра	|
<!-- BEGIN row -->
|{row.FIO}|{row.HID}|{row.EXAM_READ_DATE}|{row.EXAM_DATE}|
<!-- END row -->
}}
</textarea>

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
