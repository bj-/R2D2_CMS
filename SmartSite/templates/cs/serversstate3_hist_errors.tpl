<!-- $Version["serversstate3_hist_errors.tpl"] = '1.0.1'; -->
<h3>История падений сервиса [{SERVICE}] на сервере [{SERVER}] в [{CLIENT}]</h3>

<table width="100%" class="tbl">
	<!-- BEGIN row -->
	<tr>
		<th align="left">Date: {row.DATE}</th>
		<th align="left">Type: {row.TYPE}</th>
		<th align="left">Text: {row.TEXT}</th>
	</tr>
	<tr>
		<td colspan="3">
			{row.STACK}
			<br /><br /><br />
		</td>
	</tr>
	<!-- END row -->
</table>

<hr />

{ARTICLE}
{TEMP}

<!-- BEGIN legend -->
<h3>Легенда</h3>
<ul>
	<li></li>
	<li></li>
</ul>
<!-- END legend -->

