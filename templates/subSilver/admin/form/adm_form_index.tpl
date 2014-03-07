<!-- BEGIN switch_form_show -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<p><span style="font-size:large;">Формы отправки сообщений</span></p>
		</td>
		<td align="right">
			<p><a href="#" title="Создать форму"><img src="/pic/ico/folder_add.gif" alt="Создать форму" width="16" height="16" border="0" /></a></p>
		</td>
	</tr>
</table>

<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<tr>
		<th style="border-bottom:solid 1px; font-weight:bold; width:20px;">ID</th>
		<td style="border-bottom:solid 1px; font-weight:bold">Название</td>
		<!--td style="border-bottom:solid 1px; font-weight:bold">Поля</td-->
		<td style="border-bottom:solid 1px; font-weight:bold; width:35px;">Ред.</td>
	</tr>
	<!-- BEGIN form_list -->
		<!-- BEGIN form_type -->
	<tr><th style="border-bottom:solid 1px; border-top: solid 1px; text-align:left;" colspan="5">{switch_form_show.form_list.form_type.FORM_TYPE}</th></tr>
		<!-- END form_type -->
	<tr>
		<td style="text-align:center; border-right:dotted 1px;">{switch_form_show.form_list.FORM_ID}</td>
		<td>
			<div onclick="show_m_desc({switch_form_show.form_list.FORM_ID});" style="cursor:pointer;">
				<!--img src="/pic/ico/arrow_down_16x16.png" width="16" height="16" border="0" /-->
				{switch_form_show.form_list.FORM_NAME}
			</div>
		</td>
		<td>
			<a href="/admin/form/index.php?action=edit&form_id={switch_form_show.form_list.FORM_ID}"><img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0"></a> 
			<a href="/admin/form/index.php?action=remove&form_id={switch_form_show.form_list.FORM_ID}"><img src="/pic/ico/delete.gif" alt="Удалить" width="16" height="16" border="0"></a>
		</td>
	</tr>
	<tr>
		<td style="border-bottom:dotted 1px;  border-right:dotted 1px;"></td>
		<td colspan="4" style="border-bottom:dotted 1px;">
		<!--div style="background:red; border-radius:6px; width:50px; height:50px;">sdfsdf</div-->
			<div id="formdesc{switch_form_show.form_list.FORM_ID}" style="display:none; position:absolute; border:solid 1px gray; padding:8px; background:#FFFFFF; min-width:400px; padding:0px; border-radius:10px;">
				<div style="background:url(/pic/dlg_topbar_r.png) right; color:black; position:absolute; top:0px; left:0px; width:100%; height:30px; font-weight:bold; font-size:15px;">
					<div style="background:url(/pic/dlg_topbar_l.png) left no-repeat; width:7px; height:30px; position:absolute; top:0px; left:0px;"></div>
					<div style="width:21px; height:21px; position:absolute; right:5px; top:5px; cursor:pointer;" onclick="show_m_desc({switch_form_show.form_list.FORM_ID});"></div>
					<div style="padding-top:7px; padding-left:7px;">Детальная информация о форме</div>
				</div>
				<div style="margin-top:30px; padding:7px;">
					{switch_form_show.form_list.FORM_DESC}<br />
					{switch_form_show.form_list.FORM_PREVIEW}
				</div>
			</div>
		</td>
	</tr>
	<!-- END form_list -->
</table>
<p><a onclick="document.getElementById('add_form').style.display='block';" style="cursor:pointer;">Добавить форму</a></p>
<div id="add_form" style="display:none;">
	<form action="" method="post">
	<input name="new_form" type="hidden" value="1" />
	<table width="100%" border="0" cellpadding="0" cellspacing="2">
		<tr><td>Название</td><td><input name="form_name" type="text" value="" size="70" maxlength="255"></td><td rowspan="3" width="300px">{switch_form_edit.FORM_PREVIEW}</td></tr>
		<tr><td>Описание</td><td><input name="form_desc" type="text" value="" size="70" maxlength="255"></td></tr>
		<tr><td></td><td><input name="save_form" type="submit" value="Создать форму" /></td></tr>
	</table>
	</form>
</div>
<script>
var i_id_list = new Array(
	<!-- BEGIN form_list -->
	{switch_form_show.form_list.FORM_ID}, 
	<!-- END form_list -->
	0
	);

function show_m_desc(m_id) {
	if (document.getElementById('formdesc'+m_id).style.display=="block") {
		document.getElementById('formdesc'+m_id).style.display="none"; 
	}
	else {
		i_id = 0;
		while (i_id_list[i_id]) {
			document.getElementById('formdesc'+i_id_list[i_id]).style.display="none"; 
			i_id++;
		};
		document.getElementById('formdesc'+m_id).style.display="block"; 
	};
};
</script>
<!-- END switch_form_show -->
<!-- BEGIN switch_form_edit -->
<p>Форма. Редактирование.</p>
<form action="?action=edit&form_id={switch_form_edit.FORM_ID}" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="2">
	<tr><td>Название</td><td><input name="form_name" type="text" value="{switch_form_edit.FORM_NAME}" size="70" maxlength="255"></td><td rowspan="3" width="300px">{switch_form_edit.FORM_PREVIEW}</td></tr>
	<tr><td>Описание</td><td><input name="form_desc" type="text" value="{switch_form_edit.FORM_DESC}" size="70" maxlength="255"></td></tr>
	<tr><td></td><td><input name="save_form" type="submit" value="Сохранить название" /></td></tr>
</table>
</form>
<p>Поля формы:</p>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
	<tr><th>Название поля</th><th>Тип</th><th>Значение по умолчанию</th><th>Размер<br />контрола</th><th>Длина<br />значения</th><th>Сорт-ка</th><th></th></tr>
	<!-- BEGIN form_field_list -->
	<tr>
		<td>
			<div style="position:relative; width:100%;">
				<div style="position:absolute; right:3px;">
					{switch_form_edit.form_field_list.CSS}
				</div>
				{switch_form_edit.form_field_list.NAME} {switch_form_edit.form_field_list.REQ}
			</div>
		</td>
		<td>{switch_form_edit.form_field_list.TYPE}</td>
		<td>{switch_form_edit.form_field_list.VAL}</td>
		<td>{switch_form_edit.form_field_list.SIZE}</td>
		<td>{switch_form_edit.form_field_list.LEN}</td>
		<td align="center">{switch_form_edit.form_field_list.SORT}</td>
		<td>
			<a onclick="show_content('/admin/form/dyn_form_prop.php?action=edit&id={switch_form_edit.form_field_list.FIELD_ID}&form_id={switch_form_edit.form_field_list.FORM_ID}', '#edit_field');" style="cursor:pointer;"><img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0"></a> 
			<a href="/admin/form/index.php?action=edit&form_id={switch_form_edit.form_field_list.FORM_ID}&delete_feild_id={switch_form_edit.form_field_list.FIELD_ID}"><img src="/pic/ico/delete.gif" alt="Удалить" width="16" height="16" border="0"></a>
		</td>
		</tr>
	<!-- END form_field_list -->
</table>
<p><a onclick="show_content('/admin/form/dyn_form_prop.php?action=add&id={switch_form_edit.form_field_list.FIELD_ID}&form_id={switch_form_edit.FORM_ID}', '#edit_field');" style="cursor:pointer;">Добавить поле в форму</a></p>
<div id="edit_field">
</div>
<!-- END switch_form_edit -->
