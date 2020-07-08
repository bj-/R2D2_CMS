<table width="100%"><tr>
	<td><h3>Поправте HID (на блоке)</h3></td>
	<td align="right" style="font-weight:bold;"><a OnClick="clear_content('#persons_checkhidbywagon');" style="cursor:pointer;">[ CLOSE ]</a></td>
</tr></table>

<table cellspacing="1" class="tbl">
	<tr>
		<th>Вагон</th><th>Количество</th>
	</tr>
<!-- BEGIN row -->
	<tr>
		<td>{row.NAME}</td>
		<td align="center">{row.VALUE}</td>
	</tr>
<!-- END row -->
</table>
<br/>
<b>Wiki:</b> <textarea cols="150" rows="3" class="xx-small"> 
* Поправте HID (на блоке2) {{collapse(Показать)
|Вагон|Count|
<!-- BEGIN row -->
|{row.NAME}|{row.VALUE}|
<!-- END row -->
}}
</textarea>

{ARTICLE}
{TEMP}