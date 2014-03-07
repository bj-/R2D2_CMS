<form action="?action=edit&form_id={FORM_ID}" method="post">
<input name="field_id" type="hidden" value="{FIELD_ID}" />
<table>
	<tr><td>Название</td><td><input name="field_name" type="text" value="{FIELD_NAME}" size="60" maxlength="255" /></td></tr>
	<tr>
		<td>Тип поля</td>
		<td>
			<select name="field_type" id="field_type" onchange="change_size(document.getElementById('field_type').value)">
				<!-- BEGIN field_type_list -->
				<option value="{field_type_list.FIELD_TYPE_ID}"{field_type_list.FIELD_TYPE_SEL}>{field_type_list.FIELD_TYPE_NAME}</option>
				<!-- END field_type_list -->
			</select>
		</td>
	</tr>
	<tr><td>Значение по умолчанию</td><td><input name="field_val" type="text" value="{FIELD_VAL}" size="60" maxlength="255" /></td></tr>
	<tr>
		<td>Размер</td>
		<td>
			<div id="t_field" style="display:none">
				<input name="field_size" type="text" value="{FIELD_SIZE}" size="10" maxlength="10" />
			</div>
			<div id="t_textarea" style="display:none">
				Ширина: <input name="field_size_width" type="text" value="{FIELD_SIZE_WIDTH}" size="5" maxlength="5" /> &nbsp; &nbsp; Высота: <input name="field_size_height" type="text" value="{FIELD_SIZE_HEIGHT}" size="5" maxlength="5" />
			</div>
		</td>
	</tr>
	<tr><td>Макс длина</td><td><input name="field_maxlen" id="field_maxlen" type="text" value="{FIELD_MAXLEN}" size="10" maxlength="5" /></td></tr>
	<tr><td>Обязательное</td><td><input name="field_require" type="checkbox" value="1"{FIELD_REQUIRED} /></td></tr>
	<tr><td>CSS Class</td><td><input name="field_class" type="text" value="{FIELD_CLASS}" size="60" maxlength="255" /></td></tr>
	<tr><td>CSS Style</td><td><input name="field_style" type="text" value="{FIELD_STYLE}" size="60" maxlength="255" /></td></tr>
	<tr><td></td><td><input name="save_field" type="submit" value="Сохранить"></td></tr>
</table>
</form>
<script language="JavaScript" type="text/javascript">
function change_size(opt_id) {
	if (opt_id == 4) { // текстареа = 4, см. конфиг.
		document.getElementById('t_field').style.display="none"; 
		document.getElementById('t_textarea').style.display="block"; 
		
		// Меняем макс длину вводимого текста при смене типа поля
		if (document.getElementById('field_maxlen').value == "250") {
			document.getElementById('field_maxlen').value = "3000"
		};
	}
	else {
		document.getElementById('t_textarea').style.display="none"; 
		document.getElementById('t_field').style.display="block"; 

		// Меняем макс длину вводимого текста при смене типа поля
		if (document.getElementById('field_maxlen').value == "3000") {
			document.getElementById('field_maxlen').value = "250"
		};
	}
};
change_size('{FIELD_TYPE_ID}');
</script>