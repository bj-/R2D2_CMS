<table cellspacing="1px" cellpadding="0px" class="tbl" width="100%" border="0" style="background:#CCCCCC;">
	<!-- BEGIN row -->
	<tr>
		<th align='left'>
			<table width='1000px'>
				<tr>
					<th width='100px'>Block</th>
					<th width='150px'>Service</th>
					<th width='200px'>Date</th>
				</tr>
				<tr style='text-align:center;'>
					<td>{row.BLOCKSERNO}</td>
					<td>{row.SERVICE_NAME}</td>
					<td>{row.DATE_TIME}</td>
				</tr>
			</table>
	      <th>
	</tr>
	<tr>
		<td><pre>{row.ERROR_TEXT}</pre></td>
	</tr>
	<tr>
		<td>
			<div id="{row.LOGDIVID}">
				<a style="cursor:pointer;font-weight:bold;" OnClick="show_content('/dynamic/proxy.php?page=spbMetroBlockErrorsDetailsShowLog&params={row.LOGID}', '#{row.LOGDIVID}');">
					Show Log &gt;&gt;
				</a>
			</div>
			<br /><br />
		</td>
	</tr>
	<!-- END row -->
</table>
<p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p>
<p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p><p><br /></p>
{ARTICLE}
{TEMP}
