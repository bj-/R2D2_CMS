<!-- BEGIN switch_left_menu -->
<!-- END switch_left_menu -->
<!-- {SAVED} -->
<!-- BEGIN switch_form_sended -->
{switch_form_sended.TEXT}
<!-- END switch_form_sended -->
<!-- BEGIN submenu -->
	<div style="line-height: 20px;">
	<!-- BEGIN submenu_list -->
		<a href="{submenu.submenu_list.MENU_PATH}" class="{submenu.submenu_list.MENU_CLASS}" title="{submenu.submenu_list.MENU_NAME}">{submenu.submenu_list.MENU_NAME}</a><br />
	<!-- END submenu_list -->
	</div>
<!-- END submenu -->

<form action="" method="post" enctype="application/x-www-form-urlencoded">
Поиск сотрудников:
Фамилия <input name="lastname" type="text" value="{S_LASTNAME}" size="20" maxlength="20" />
Имя <input name="firstname" type="text" value="{S_FIRSTNAME}" size="20" maxlength="20" />
Отчество <input name="middlename" type="text" value="{S_MIDDLENAME}" size="20" maxlength="20" />
<br /><input type="submit" value="Апорт!" />
</form>
{ARTICLE}

<table>
<tr><th>ID</th><th>Фото</th><th>ФИО</th><th>Департамент</th></tr>
<!-- BEGIN searchresult -->
<tr>
	<td>{searchresult.ID}</td>
	<td>{searchresult.PHOTO}</td>
	<td>{searchresult.LASTMANE} {searchresult.FIRSTMANE} {searchresult.MIDDLETMANE}</td>
	<td>{searchresult.DEP_NAME}</td>
</tr>
<!-- END searchresult -->
</table>