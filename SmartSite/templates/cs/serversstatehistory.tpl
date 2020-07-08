<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='100%'>
	<tr>
		<th colspan="6">
			Server's Over Limits history<br />
			<span style="font-weight:normal;">at: {CURRENTDATE} {CURRENTTIME} (GMT)</span>
		</th>
	</tr>
	<tr>
		<th>Server</th>
		<th>6h</th>
		<th>12h</th>
		<th>24h</th>
		<th>48h</th>
		<th>72h</th>
	</tr>
	<!-- BEGIN statrow -->
	<tr>
		<td><a style="cursor:pointer;" onclick="ShowHideDiv('HistDetails');ShowHideDiv('{statrow.SERVERNAME}_histDetails');">{statrow.SERVERNAME}</a></td>
		<td align="center">{statrow.6H}</td>
		<td align="center">{statrow.12H}</td>
		<td align="center">{statrow.24H}</td>
		<td align="center">{statrow.48H}</td>
		<td align="center">{statrow.72H}</td>
	</tr>
	<!-- END statrow -->
</table>

<!--div name="HistDetails" id="HistDetails" style="z-index:10;position:absolute;display:none;margin-left:-0px;background-color:white;padding:10px;border:5px;box-shadow: -1px -1px 5px 5px rgba(0, 0, 0, .2);color:black;font-weight:normal;"-->
<div name="HistDetails" id="HistDetails" style="display:none;">
<br /><br />
<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='100%'>
	<tr>
		<th colspan="{COLUMN_COUNT}">
			Server's history: TOP Errors<br />
			<span style="font-weight:normal;">at: {CURRENTDATE} {CURRENTTIME} (GMT)</span>
		</th>
	</tr>
	<!-- BEGIN noerrors -->
	<tr>
		<td colspan="{COLUMN_COUNT}" style='text-align:center; background-color:#aaFFaa;'>
				 <br />Шайтансофта более {HOURS_AGO_ANALYZE} часов работает только с потаенными проблемами.<br /><br />
		</td>
	</tr>
	<!-- END noerrors -->
	<tr><td colspan="{COLUMN_COUNT}" style="font-size:2px;">&nbsp;</td></tr>

	<!-- BEGIN group -->
	<tr>
		<th colspan="{COLUMN_COUNT}">{group.NAME}</th>
	</tr>
	<tr>
		<th>Name</th>
		<th>Value</th>
		<th>t&nbsp;Ago</th>
	</tr>


	<!-- BEGIN row -->
	<tr>
		<td align="left" style="{group.row.STYLE}">{group.row.SERVERNAME} -> {group.row.METRICNAME}</td>

		<td align="center" style="{group.row.STYLE}">
			<a style="color:#000000;" 
				href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&rnd={RANDOM}&params={group.row.SERVER_GUID};{group.row.METRIC_GROUP_GUID};{group.row.METRIC_GUID}' 
				onclick="return hs.htmlExpand(this, { objectType: 'ajax'} )">{group.row.VALUE}</a>
		</td>
		<td align="center" style="{group.row.STYLE}">
					{group.row.TIME_AGO}
		</td>
	</tr>
	<!-- END row -->


	<!-- END group -->
</table>
</div>

{ARTICLE}
{TEMP}

<!-- BEGIN legend -->
<h3>Легенда</h3>
<ul>
<li>Красным подсвечивает ячейки значения в которых выходят за определенный лимит (настраивается в БД).</li>
<li>В группе "*.Errors files (last 7 days)" - не подсвечивает ничего. только статистическая информация.</li>
<li>при клике в значение ячейки - просмотр истории. С подсветкой выходящих за лимиты.</li>
</ul>
<!-- END legend -->

