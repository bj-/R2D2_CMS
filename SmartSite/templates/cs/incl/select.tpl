<select name="{NAME}">
	<option value="0">Не выбрано...</option>
     <!-- BEGIN row -->
	<!-- BEGIN group -->
	<option value="{row.group.VALUE}"> &nbsp; {row.group.NAME}</option>
	<!-- END group -->
	<!-- BEGIN item -->
	<option value="{row.item.VALUE}" {row.item.SELECTED}>{row.item.INDENT}{row.item.NAME}</option>
	<!-- END item -->
     <!-- END row -->
</select>
