<table width="100%"><tr>
	<td><h3>Групповые нормы</h3></td>
	<td align="right" style="font-weight:bold;"><a OnClick="clear_content('#persons_gpnorms');" style="cursor:pointer;">[ CLOSE ]</a></td>
</tr></table>

Сервер: {SRV_NAME}; Даты с {DATE_FROM} до {DATE_UNTIL}
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
<!-- BEGIN group_norm -->
	<tr>
		<td>{group_norm.CALCULATED}</td>
		<td align="center">{group_norm.VALIDITY}</td>
		<td class="xx-small">{group_norm.RR_MD_MIN}</td>
		<td class="xx-small">{group_norm.RR_MD_AVG}</td>
		<td class="xx-small">{group_norm.RR_MD_MAX}</td>
		<td class="xx-small">{group_norm.RR_AM_MIN}</td>
		<td class="xx-small">{group_norm.RR_AM_AVG}</td>
		<td class="xx-small">{group_norm.RR_AM_MAX}</td>
		<td class="xx-small">{group_norm.BOI_MD_MIN}</td>
		<td class="xx-small">{group_norm.BOI_MD_AVG}</td>
		<td class="xx-small">{group_norm.BOI_MD_MAX}</td>
		<td class="xx-small">{group_norm.BOI_AM_MIN}</td>
		<td class="xx-small">{group_norm.BOI_AM_AVG}</td>
		<td class="xx-small">{group_norm.BOI_AM_MAX}</td>
	</tr>
<!-- END group_norm -->
</table>

<p>
<h4>Груповые нормы на: {CALCULATED}</h4>
<ul>
	<li>Validity: {VALIDITY}</li>
	<li>RR_MD_Min: {RR_MD_MIN}</li>
	<li>RR_MD_Max: {RR_MD_MAX}</li>
	<li>RR_MD_Avg: {RR_MD_AVG}</li>
	<li>RR_AM_Min: {RR_AM_MIN}</li>
	<li>RR_AM_Max: {RR_AM_MAX}</li>
	<li>RR_AM_Avg: {RR_AM_AVG}</li>
	<li>BOI_MD_Min: {BOI_MD_MIN}</li>
	<li>BOI_MD_Max: {BOI_MD_MAX}</li>
	<li>BOI_MD_Avg: {BOI_MD_AVG}</li>
	<li>BOI_AM_Min: {BOI_AM_MIN}</li>
	<li>BOI_AM_Max: {BOI_AM_MAX}</li>
	<li>BOI_AM_Avg: {BOI_AM_AVG}</li>
</ul>


<br/>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Групповые нормы {{collapse(Показать)
|Calculated|Validity|RR_MD_Min|RR_MD_Max|RR_MD_Avg|RR_AM_Min|RR_AM_Max|RR_AM_Avg|BOI_MD_Min|BOI_MD_Max|BOI_MD_Avg|BOI_AM_Min|BOI_AM_Max|BOI_AM_Avg|
<!-- BEGIN group_norm -->
|{group_norm.CALCULATED}|{group_norm.VALIDITY}|{group_norm.RR_MD_MIN}|{group_norm.RR_MD_MAX}|{group_norm.RR_MD_AVG}|{group_norm.RR_AM_MIN}|{group_norm.RR_AM_MAX}|{group_norm.RR_AM_AVG}|{group_norm.BOI_MD_MIN}|{group_norm.BOI_MD_MAX}|{group_norm.BOI_MD_AVG}|{group_norm.BOI_AM_MIN}|{group_norm.BOI_AM_MAX}|{group_norm.BOI_AM_AVG}|
<!-- END group_norm -->
}}

* Груповые нормы на: {CALCULATED}
** Validity: {VALIDITY}
** RR_MD_Min: {RR_MD_MIN}
** RR_MD_Max: {RR_MD_MAX}
** RR_MD_Avg: {RR_MD_AVG}
** RR_AM_Min: {RR_AM_MIN}
** RR_AM_Max: {RR_AM_MAX}
** RR_AM_Avg: {RR_AM_AVG}
** BOI_MD_Min: {BOI_MD_MIN}
** BOI_MD_Max: {BOI_MD_MAX}
** BOI_MD_Avg: {BOI_MD_AVG}
** BOI_AM_Min: {BOI_AM_MIN}
** BOI_AM_Max: {BOI_AM_MAX}
** BOI_AM_Avg: {BOI_AM_AVG}
</textarea>

{ARTICLE}
{TEMP}