<h3>{PROPERTIES}<br />
{BLOCK_SER_NO}</h3>

<table width='100%' border="0">
	<tr>
		<th>Date</th>
		<th>Version</th>
		<th>Value</th>
		<th>From</th>
		<th>To</th>
		<th>Hours w/o changes</th>
	</tr>
	<!-- BEGIN row -->
	<tr>
		<td align='center'>{row.DATE}</td>
		<td align='center' style="{row.VERSION_STYLE}">{row.VERSION_SOFT}</td>
		<td align='center'>{row.VALUE}</td>
		<td align='center'>
			<!-- BEGIN datefrom -->
			{row.datefrom.FROM}
			<!-- END datefrom -->
		</td>
		<td align='center'>
			{row.TO}
			</td>
		<td align='center'>
			<!-- BEGIN datefrom -->
			{row.datefrom.HOURS}
			<!-- END datefrom -->
		</td>
	</tr>
	<!-- END row -->
</table>
