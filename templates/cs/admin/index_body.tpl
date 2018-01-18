
{SAVED}

<!-- BEGIN switch_user_logged_out -->
<br><br><br>
<div align="center">Вы не вошли в систему. <a href="/login.php"><strong>Вход</strong></a></div>
<br><br><br>
<!--
<form method="post" action="{S_LOGIN_ACTION}">
<div align="center">
  <table cellpadding="3" cellspacing="0" border="0">
	<tr> 
	  <th height="28" align="left"><a name="login"></a>{L_LOGIN_LOGOUT}</th>
	</tr>
	<tr> 
	  <td class="row1" align="center" valign="middle" height="28">{L_USERNAME}: 
		<input class="post" type="text" name="username" size="10" />
		&nbsp;&nbsp;&nbsp;{L_PASSWORD}: 
		<input class="post" type="password" name="password" size="10" maxlength="32" />
		&nbsp;&nbsp; &nbsp;&nbsp;<input type="submit" class="bold" name="login" value="{L_LOGIN}" /><br>
		<div align="right"><input class="text" type="checkbox" name="autologin" checked /> {L_AUTO_LOGIN}</div>
		<br><a href="{U_REGISTER}">[ {L_REGISTER} ]</a>
		</td>
	</tr>
  </table>
</div>
</form>
-->
<br>
<!-- END switch_user_logged_out -->

<!-- BEGIN switch_menu -->
<!-- END switch_menu -->

<!-- BEGIN swich_save -->
<strong>Сохранено</strong>
<!-- END swich_save -->


<!-- BEGIN swich_menu_list -->
<!-- 
===========================================

				Главное меню

===========================================
-->
<table width="95%" border="1" cellspacing="0" cellpadding="3" align="center">
<col>
<col>
<col>
<col align="center">
<col align="center">
<tr><th>id</th><th>Название</th><th>Путь</th><th>Сорт.</th><th width="50">Ред.</th></tr>
	<!-- BEGIN menu_list -->
<tr><td>{swich_menu_list.menu_list.MENU_ID}</td>
<td width="50%">{swich_menu_list.menu_list.MENU_NAME}</td>
<td>{swich_menu_list.menu_list.MENU_PATH}</td>
<td>{swich_menu_list.menu_list.MENU_SORT}</td>
<td><a href="/admin/index.php?edit=menu&menu_id={swich_menu_list.menu_list.MENU_ID}"><img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0"></a> <a href="/admin/index.php?edit=menu&menu_remove={swich_menu_list.menu_list.MENU_ID}"><img src="/pic/ico/delete.gif" alt="Удалить" width="16" height="16" border="0"></a></td></tr>
	<!-- END menu_list -->
</table>
<form action="/admin/index.php?edit=menu" method="post">
<br><br>
Добавить пункт меню:
<table>
<tr><th>Название</th><th>Путь</th><th>Сортировка</th><th></th></tr>
<tr><td><input type="text" name="add_menu_name"></td><td><input type="text" name="add_menu_path"></td><td><input type="text" name="add_menu_sort"></td></tr>
<tr><td colspan="3"><input type="submit" name="menu_add" value="Добавить"></td></tr>
</table>
</form>
<!-- END swich_menu_list -->

<!-- BEGIN menu_edit -->
<form action="/admin/index.php?edit=menu" method="post">
<table width="95%" border="0" cellspacing="0" cellpadding="3" align="center">
<tr><td>id</td><td>Название</td><td>Путь</td><td>Сортировка</td></tr>
<tr><td><input type="text" name="menu_id" value="{menu_edit.MENU_ID}" size="1" readonly></td><td><input type="text" name="menu_name" value="{menu_edit.MENU_NAME}" size="50"></td><td><input type="text" name="menu_path" value="{menu_edit.MENU_PATH}"></td><td><input type="text" name="menu_sort" value="{menu_edit.MENU_SORT}" size="6" maxlength="11"></td></tr>
</table>
<table width="95%" border="0" cellspacing="0" cellpadding="3" align="center"><tr><td><input type="submit" name="menu_save" value="Сохранить"></td></tr></table>
</form>
<!-- END menu_edit -->

<!-- BEGIN menu_add -->
<form action="/admin/index.php?edit=menu" method="post">
<table width="95%" border="0" cellspacing="0" cellpadding="3" align="center">
<tr><td>Название</td><td>Путь</td><td>Сортировка</td></tr>
<tr><td><input type="text" name="menu_name" value="{menu_edit.MENU_NAME}" size="50"></td><td><input type="text" name="menu_path" value="{menu_edit.MENU_PATH}"></td><td><input type="text" name="menu_sort" value="{menu_edit.MENU_SORT}" size="6" maxlength="11"></td></tr>
</table>
<table width="95%" border="0" cellspacing="0" cellpadding="3" align="center"><tr><td><input type="submit" name="menu_save" value="Сохранить"></td></tr></table>
</form>
<!-- END menu_add -->



<!-- BEGIN swich_siteprop -->
<!-- 
===========================================

				Настройки сайта

===========================================
-->
<p>Настройки сайта.</p>
<p>Изменив данные настройки вы можете привести сайт в нерабочеспособное состояние.</p>
<table width="100%" border="1" cellspacing="0" cellpadding="3" align="center">
<col>
<col>
<col>
<col align="center">
<tr><th>Название</th><th>Значение</th><th>Ред.</th></tr>
<!-- BEGIN prop_list -->
<tr>
	<td>{swich_siteprop.prop_list.PROP_DESC}</td>
	<td><strong>{swich_siteprop.prop_list.PROP_VALUE}</strong></td>
	<td><a href="/admin/index.php?edit=prop&prop_name={swich_siteprop.prop_list.PROP_NAME}"><img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0"></a></td>
</tr>
<!-- END prop_list -->
</table>
<!-- END swich_siteprop -->

<!-- BEGIN prop_edit -->
{prop_edit.PROP_DESC}
<form action="/admin/index.php?edit=prop" method="post">
<input type="hidden" name="prop_name" value="{prop_edit.PROP_NAME}">
<input type="text" name="prop_value" value="{prop_edit.PROP_VALUE}" size="50">
<input type="submit" name="prop_save" value="Сохранить"></td></tr></table>
</form>
<!-- END prop_edit -->





<!-- BEGIN swich_blocks_edit -->
<!-- 
===========================================

				Дополнительные блоки

===========================================
-->
<table width="90%" border="1" cellspacing="0" cellpadding="5" align="center">
<!-- BEGIN blocks_list -->
<tr><td>{swich_blocks_edit.blocks_list.BLOCK_TEXT}</td><td><a href="/admin/index.php?edit=blocks&block={swich_blocks_edit.blocks_list.BLOCK_ID}"><img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0"></a></td></tr>
<!-- END blocks_list -->
</table>
<!-- END swich_blocks_edit -->

<!-- BEGIN blocks_edit -->
{blocks_edit.BLOCK_ID}
{blocks_edit.BLOCK_TEXT}
<!-- END blocks_edit -->




<!-- BEGIN swich_article_list -->
<!-- 
===========================================

				Статьи

===========================================
-->
<strong>Ссылка на статью:</strong> <input type="text" name="article_link" id="article_link" size="50">
<br>Кликните на "Получить ссылку" для отображения ссылки.<br><br>
<table width="100%" border="1" cellspacing="0" cellpadding="3" align="center">
<tr><th>id</th><th>Раздел</th><th>Название</th><th>Ссылка</th></tr>
	<!-- BEGIN article_list -->
<tr><td width="20">{swich_article_list.article_list.ARTICLE_ID}</td>
<td width="25%">{swich_article_list.article_list.ARTICLE_PARAGRAF}</td>
<td>
	<div style="position:relative; width:100%;">
		<div style="position:absolute; right:0px;"><a href="/admin/article_edit.php?id={swich_article_list.article_list.ARTICLE_ID}&edit=1" title="Редактировать"><img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0" /></a></div>
		<a href="{swich_article_list.article_list.ARTICLE_LINK}">{swich_article_list.article_list.ARTICLE_NAME}</a>
	</div>
</td>
<td><a onClick="showlink('{swich_article_list.article_list.ARTICLE_LINK}')">Получить ссылку на статью</a></td></tr>
	<!-- END article_list -->
</table>
<script type="text/javascript">
function showlink(linkname) {
	document.getElementById('article_link').value=linkname;
}
</script>

<!-- END swich_article_list -->




{TEXT}

{TEMP}
