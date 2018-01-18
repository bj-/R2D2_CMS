<!-- BEGIN swich_menu_list -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<p><span style="font-size:large;">Структура меню</span></p>
		</td>
		<td align="right">
			<p><a href="/admin/index.php?edit=menu&action=addmenu" title="Создать пункт меню"><img src="/pic/ico/folder_add.gif" alt="Создать пункт меню" width="16" height="16" border="0" /></a></p>
		</td>
	</tr>
</table>

<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<tr>
		<th style="border-bottom:solid 1px; font-weight:bold">ID</th>
		<td style="border-bottom:solid 1px; font-weight:bold">Название</td>
		<td style="border-bottom:solid 1px; font-weight:bold">Путь</td>
		<td style="border-bottom:solid 1px; font-weight:bold">Сорт</td>
		<td style="border-bottom:solid 1px; font-weight:bold">Ред.</td>
	</tr>
	<!-- BEGIN menu_list -->
		<!-- BEGIN menu_sep -->
	<tr><th style="border-bottom:solid 1px; border-top: solid 1px; text-align:left;" colspan="5">Группа меню № {swich_menu_list.menu_list.menu_sep.MENU_GROUP}</th></tr>
		<!-- END menu_sep -->
	<tr>
		<td style="text-align:center; border-right:dotted 1px;">{swich_menu_list.menu_list.MENU_ID}</td>
		<td>
			<div style="float:right">{swich_menu_list.menu_list.MENU_IMG} {swich_menu_list.menu_list.MENU_CLASS}</div>
			<div onclick="show_m_desc({swich_menu_list.menu_list.MENU_ID});" style="cursor:pointer;">
				<!--img src="/pic/ico/arrow_down_16x16.png" width="16" height="16" border="0" /-->
				{swich_menu_list.menu_list.MENU_NAME}
			</div>
		</td>
		<td><a href="{swich_menu_list.menu_list.MENU_PATH}/">{swich_menu_list.menu_list.MENU_PATH}</a></td>
		<td>{swich_menu_list.menu_list.MENU_SORT}</td>
		<td>
			<a href="/admin/index.php?edit=menu&menu_id={swich_menu_list.menu_list.MENU_ID}"><img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0"></a> 
			<a href="/admin/index.php?edit=menu&menu_remove={swich_menu_list.menu_list.MENU_ID}"><img src="/pic/ico/delete.gif" alt="Удалить" width="16" height="16" border="0"></a>
		</td>
	</tr>
	<tr>
		<td style="border-bottom:dotted 1px;  border-right:dotted 1px;"></td>
		<td colspan="4" style="border-bottom:dotted 1px;">
			<div id="menudesc{swich_menu_list.menu_list.MENU_ID}" style="display:none; position:absolute; border:solid 1px gray; padding:8px; background:#FFFFFF; min-width:400px;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr><td style="font-size:medium">Детальная информация о пункте меню</td><td align="right"><a onclick="show_m_desc({swich_menu_list.menu_list.MENU_ID});" style="cursor:pointer; color:red; text-decoration:none;" title="Закрыть"><strong>[ X ]</strong></a></td></tr>
				</table>
				<br />
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr><td nowrap="nowrap" width="10%">Тип ссылки:</td><td><strong>{swich_menu_list.menu_list.MENU_TYPE}</strong></td></tr>
					<tr><td nowrap="nowrap">Описание ссылки:</td><td>{swich_menu_list.menu_list.MENU_DESC}</td></tr>
					<tr><td nowrap="nowrap">CSS Class:</td><td><strong>{swich_menu_list.menu_list.MENU_CLASS_NAME}</strong></td></tr>
				</table>
				<br />
			</div>
		</td>
	</tr>
	<!-- END menu_list -->
</table>
<p>
<span style="font-size:medium;">Описание:</span><br />
<img src="/pic/ico/photo_16x16.png" width="16" height="16" alt="графическое меню" border="0" /> - графическое меню<br />
<img src="/pic/ico/style_16x16.png" width="16" height="16" alt="графическое меню" border="0" /> - указан персональный стиль<br />
</p>
<script>
//var i_id_list = new Array(1, 2, 3, 4,);


var i_id_list = new Array(
	<!-- BEGIN menu_list -->
	{swich_menu_list.menu_list.MENU_ID}, 
	<!-- END menu_list -->
	0
	);


function show_m_desc(m_id) {
	if (document.getElementById('menudesc'+m_id).style.display=="block") {
		document.getElementById('menudesc'+m_id).style.display="none"; 
	}
	else {
		i_id = 0;
		while (i_id_list[i_id]) {
//		for (i_id=1; i_id<=6; i_id++) {
//			if (document.getElementById('menudesc'+i_id).style.display=="block") {
				document.getElementById('menudesc'+i_id_list[i_id]).style.display="none"; 
//			};
//			alert(i_id);
			i_id++;
		};
		document.getElementById('menudesc'+m_id).style.display="block"; 
	};
//	document.getElementById(div_id).style.display="none"; 
//	alert(m_id);
};
</script>
<!-- END swich_menu_list -->
<!-- BEGIN swich_menu_edit -->
<p style="font-weight:bold;">Редактирование/добавление пункта меню</p>
<form action="/admin/index.php?edit=menu" method="post">
<input type="hidden" name="menu_id" value="{swich_menu_edit.MENU_ID}" />
<table>
	<tr><td>Название			</td><td><input style="width:400px;" name="menu_name" value="{swich_menu_edit.MENU_NAME}" /></td></tr>
	<tr>
		<td>Раздел			</td>
		<td>
			<select name="menu_pid">
				{swich_menu_edit.MENU_PARAGRAF_ROOT}
				{swich_menu_edit.MENU_PARAGRAF}
			</select>
			{swich_menu_edit.MENU_PID} 
		</td>
	</tr>
	<tr><td>Группа				</td><td><input name="menu_group" value="{swich_menu_edit.MENU_GROUP}" /></td></tr>
	<tr><td>Сортировка		</td><td><input name="sortorder" value="{swich_menu_edit.MENU_SORT}" /></td></tr>
	<tr><td>Путь				</td><td><input name="menu_path" value="{swich_menu_edit.MENU_PATH}" /></td></tr>
	<tr><td>Описание			</td><td><input name="menu_desc" value="{swich_menu_edit.MENU_DESC}" /></td></tr>
	<tr><td>CSS Class			</td><td><input name="menu_class" value="{swich_menu_edit.MENU_CLASS}" /></td></tr>
	<tr>
		<td>Тип пункта меню	</td>
		<td>
		<select name="link_type">
			<option value="0">Не указан</option>
			<option value="1" {swich_menu_edit.MENU_TYPE_1}>Внешняя ссылка (откроется в новом окне)</option>
			<option value="2" {swich_menu_edit.MENU_TYPE_2}>Жесткая ссылка (откроется в том же окне)</option>
			<option value="3" {swich_menu_edit.MENU_TYPE_3}>Без ссылки</option>
		</select>
		</td>
	</tr>
	<tr><td>Картинка (URL)	</td><td><input name="menu_img" value="{swich_menu_edit.MENU_IMG}" /></td></tr>
</table>
<input name="menu_save" type="submit" value="Сохранить" />
</form>
<!-- END swich_menu_edit -->
