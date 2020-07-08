<h3>Сервер: {SERVER} ({CLIENT_NAME})</h3>
<h4>Индивидуальные нормы: {FIO}<br />Среднее качество сигнала на блоке (=Validity)</h4>
<table cellspacing="1" class="tbl">
	<tr>
		<th>Calculated</th><th>Validity</th>
		<th class="xx-small">RR MD Min</th>
		<th class="xx-small">RR MD Avg</th>
		<th class="xx-small">RR MD Max</th>
		<th class="xx-small">RR_AM_Min</th>
		<th class="xx-small">RR_AM_Avg</th>
		<th class="xx-small">RR_AM_Max</th>
		<th class="xx-small">BOI_MD_Min</th>
		<th class="xx-small">BOI_MD_Avg</th>
		<th class="xx-small">BOI_MD_Max</th>
		<th class="xx-small">BOI_AM_Min</th>
		<th class="xx-small">BOI_AM_Avg</th>
		<th class="xx-small">BOI_AM_Max</th>
	</tr>
<!-- BEGIN row -->
	<tr>
		<td>{row.CALCULATED}</td>
		<td align="center">{row.VALIDITY}</td>
		<td class="xx-small">{row.RR_MD_MIN}</td>
		<td class="xx-small">{row.RR_MD_AVG}</td>
		<td class="xx-small">{row.RR_MD_MAX}</td>
		<td class="xx-small">{row.RR_AM_MIN}</td>
		<td class="xx-small">{row.RR_AM_AVG}</td>
		<td class="xx-small">{row.RR_AM_MAX}</td>
		<td class="xx-small">{row.BOI_MD_MIN}</td>
		<td class="xx-small">{row.BOI_MD_AVG}</td>
		<td class="xx-small">{row.BOI_MD_MAX}</td>
		<td class="xx-small">{row.BOI_AM_MIN}</td>
		<td class="xx-small">{row.BOI_AM_AVG}</td>
		<td class="xx-small">{row.BOI_AM_MAX}</td>
	</tr>
<!-- END row -->
</table>
<br/>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Индивидуальные нормы (Среднее качество сигнала на блоке (=Validity)) {{collapse(Показать)
|Calculated|Validity|RR_MD_Min|RR_MD_Max|RR_MD_Avg|RR_AM_Min|RR_AM_Max|RR_AM_Avg|BOI_MD_Min|BOI_MD_Max|BOI_MD_Avg|BOI_AM_Min|BOI_AM_Max|BOI_AM_Avg|
<!-- BEGIN row -->
|{row.CALCULATED}|{row.VALIDITY}|{row.RR_MD_MIN}|{row.RR_MD_AVG}|{row.RR_MD_MAX}|{row.RR_AM_MIN}|{row.RR_AM_AVG}|{row.RR_AM_MAX}|{row.BOI_MD_MIN}|{row.BOI_MD_AVG}|{row.BOI_MD_MAX}|{row.BOI_AM_MIN}|{row.BOI_AM_AVG}|{row.BOI_AM_MAX}|
<!-- END row -->
}}
</textarea>

<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
