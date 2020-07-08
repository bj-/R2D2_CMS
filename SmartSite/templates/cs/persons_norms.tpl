<table width="100%"><tr>
	<td><h3>Валидность норм</h3></td>
	<td align="right" style="font-weight:bold;"><a OnClick="clear_content('#persons_norms');" style="cursor:pointer;">[ CLOSE ]</a></td>
</tr></table>

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
<!-- BEGIN pers_norm -->
	<tr>
		<td>{pers_norm.CALCULATED}</td>
		<td align="center">{pers_norm.VALIDITY}</td>
		<td class="xx-small">{pers_norm.RR_MD_MIN}</td>
		<td class="xx-small">{pers_norm.RR_MD_AVG}</td>
		<td class="xx-small">{pers_norm.RR_MD_MAX}</td>
		<td class="xx-small">{pers_norm.RR_AM_MIN}</td>
		<td class="xx-small">{pers_norm.RR_AM_AVG}</td>
		<td class="xx-small">{pers_norm.RR_AM_MAX}</td>
		<td class="xx-small">{pers_norm.BOI_MD_MIN}</td>
		<td class="xx-small">{pers_norm.BOI_MD_AVG}</td>
		<td class="xx-small">{pers_norm.BOI_MD_MAX}</td>
		<td class="xx-small">{pers_norm.BOI_AM_MIN}</td>
		<td class="xx-small">{pers_norm.BOI_AM_AVG}</td>
		<td class="xx-small">{pers_norm.BOI_AM_MAX}</td>
	</tr>
<!-- END pers_norm -->
</table>
<br/>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Индивидуальные нормы (Среднее качество сигнала на блоке (=Validity)) {{collapse(Показать)
|Calculated|Validity|RR_MD_Min|RR_MD_Max|RR_MD_Avg|RR_AM_Min|RR_AM_Max|RR_AM_Avg|BOI_MD_Min|BOI_MD_Max|BOI_MD_Avg|BOI_AM_Min|BOI_AM_Max|BOI_AM_Avg|
<!-- BEGIN pers_norm -->
|{pers_norm.CALCULATED}|{pers_norm.VALIDITY}|{pers_norm.RR_MD_MIN}|{pers_norm.RR_MD_AVG}|{pers_norm.RR_MD_MAX}|{pers_norm.RR_AM_MIN}|{pers_norm.RR_AM_AVG}|{pers_norm.RR_AM_MAX}|{pers_norm.BOI_MD_MIN}|{pers_norm.BOI_MD_AVG}|{pers_norm.BOI_MD_MAX}|{pers_norm.BOI_AM_MIN}|{pers_norm.BOI_AM_AVG}|{pers_norm.BOI_AM_MAX}|
<!-- END pers_norm -->
}}
</textarea>

{ARTICLE}
{TEMP}