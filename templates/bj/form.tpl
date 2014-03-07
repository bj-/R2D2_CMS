<!-- BEGIN form_list -->
<select name="form_id">
<option value="0">Форма не выбрана</option>
<option value="0"></option>
	<!-- BEGIN form_name -->
	<option value="{form_list.form_name.FORM_ID}"{form_list.form_name.FORM_SELECTED}>{form_list.form_name.FORM_NAME}</option>
	<!-- END form_name -->
</select>
<!-- END form_list -->