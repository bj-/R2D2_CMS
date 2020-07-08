<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='100%'>
	<tr>
		<th colspan="{COLUMN_COUNT}">
			Server's state report <br />
			at: {CURRENTDATE} {CURRENTTIME}
		</th>
	</tr>
	<!-- BEGIN noerrors -->
	<tr>
		<td colspan="{COLUMN_COUNT}" style='text-align:center; background-color:#aaFFaa;'>
				 <br />Сервера работают с невиданными ошибками.<br /><br />
		</td>
	</tr>
	<!-- END noerrors -->
	<tr><td colspan="{COLUMN_COUNT}" style="font-size:2px;">&nbsp;</td></tr>

	<!-- BEGIN group -->
	<tr>
		<th colspan="{COLUMN_COUNT}">{group.NAME}</th>
	</tr>


	<!-- BEGIN row -->
	<tr>
		<!-- BEGIN col_header -->
		<th>{group.row.col_header.NAME}</th>
		<!-- END col_header -->

		<!-- BEGIN col -->
		<td align="center" style="{group.row.col.STYLE}">
	<a style="color:#000000;" 
		href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&rnd={RANDOM}&prms=Server={group.row.col.SERVER_GUID};MetricGroup={group.row.col.METRIC_GROUP_GUID};Metric={group.row.col.METRIC_GUID}' 
		onclick="return hs.htmlExpand(this, { objectType: 'ajax'} )">{group.row.col.VALUE}</a>
			<span style='color:orange;'>
				{group.row.col.TIME_AGO}
			</span>
		</td>
		<!-- END col -->
	</tr>
	<!-- END row -->

	<!-- BEGIN rowerrors -->
	<tr>
		<td align="left" style="{group.rowerrors.STYLE}">{group.rowerrors.SERVERNAME} -> {group.rowerrors.METRICNAME}</td>

		<td align="center" style="{group.rowerrors.STYLE}">
			<a style="color:#000000;" 
				href='/dynamic/proxy.php?page=spbMetroServersDiagDetails&rnd={RANDOM}&prms=Server={group.rowerrors.SERVER_GUID};MetricGroup={group.rowerrors.METRIC_GROUP_GUID};Metric={group.rowerrors.METRIC_GUID}' 
				onclick="return hs.htmlExpand(this, { objectType: 'ajax'} )">{group.rowerrors.VALUE}</a>
				<span style='color:red;background-color:#FFFFFF;'>
					{group.rowerrors.TIME_AGO}
				</span>
		</td>
	</tr>
	<!-- END rowerrors -->


	<!-- END group -->

	<!-- BEGIN timerow -->
	<tr>
		<td style='{timerow.STYLE}'>
			{timerow.SERVERNAME} (no Stat)
		</td>
		<td style='{timerow.STYLE}'>
			{timerow.VALUE}
		</td>
	</tr>
	<!-- END timerow -->

</table>

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

