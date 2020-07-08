<table width="100%"><tr>
	<td><h3>Медосмотры прочитанные из гарнитуры</h3></td>
	<td align="right" style="font-weight:bold;"><a OnClick="clear_content('#persons_medinsp');" style="cursor:pointer;">[ CLOSE ]</a></td>
</tr></table>

<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width="80%">
	<tr>
		<th>ФИО</th>
		<th>Дата Считывания</th>
		<th>Дата Медосмотра</th>
	</tr>
<!-- BEGIN row -->
	<tr>
		<td>{row.FIO}</td>
		<td>{row.EXAM_READ_DATE}</td>
		<td>{row.EXAM_DATE}</td>
	</tr>
<!-- END row -->
</table>

{ARTICLE}
{TEMP}