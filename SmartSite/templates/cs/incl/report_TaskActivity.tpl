<!--h3>Сервер: sRoot</h3-->
<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<h3>Активность по таскам</h3>
<!--h1>не полетело. в студии квиря выполняется, а в скрипте - пустое возвращает.</h1-->
<h4>Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO}<br />
Группа ТС: {VEHICLE_GROUP}</h4>

<span style="color:red;">{REQUIRED}</span>
<table width='90%' class="tbl">
	<tr>
		<th>Номер</th>
		<th>ТС</th>
		<th>Тема</th>
		<th width='10%'>От</th>
		<th width='15%'>Изменена</th>
		<!--th>Кол-во</th-->
	</tr>
	<!-- BEGIN row -->
	<tr>
		<td align='center'>{row.ISSUE_ID}</td>
		<td align='center'>{row.BLOCKSERNO}</td>
		<td align='left'>{row.SUBJECT}</td>
		<td align='center'>{row.ISSUE_DATE_START}</td>
		<td align='center'>{row.CHANGED}</td>
		</td>
	</tr>
	<!-- BEGIN prop -->
	<tr><td colspan="5" style="padding-left:10px;padding-right:10px;padding-bottom:10px;">
	<table width='70%' class="tbl">
	<!--tr>
		<th width='30%'>Название</th>
		<th>Значение</th>
		<th width='15%'>Дата</th>
	</tr-->
	<!-- BEGIN prow -->
	<tr>
		<td width='40%'>{row.prop.prow.FIELD_NAME}</td>
		<td>{row.prop.prow.FIELD_VALUE}</td>
		<!--td>{row.prop.prow.FIELD_CREATED}</td-->
	</tr>
	<!-- END prow -->
	</table></td></tr>
	<!-- END prop -->
	<!-- END row -->
</table>

<p><hr /></p>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Активность по таскам (Сервер: {SERVER} ({CLIENT_NAME})); Период: С {DATE_FROM} {TIME_FROM} ДО {DATE_TO} {TIME_TO}; Группа ТС: {VEHICLE_GROUP} {{collapse(Показать)
|_.Номер	|_.ТС	|_.Тема	|_.От	|_.Изменена	|
<!-- BEGIN row -->
|{row.ISSUE_ID}|{row.BLOCKSERNO}|{row.SUBJECT}|{row.ISSUE_DATE_START}|{row.CHANGED}|
<!-- BEGIN prop -->
|\5.
<!-- BEGIN prow -->
{row.prop.prow.FIELD_NAME}: {row.prop.prow.FIELD_VALUE} (от {row.prop.prow.FIELD_CREATED})
<!-- END prow -->
.|
<!-- END prop -->
<!-- END row -->
}}
</textarea>

{REPORT}

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
