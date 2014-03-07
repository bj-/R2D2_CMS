{ARTICLE}

<!-- BEGIN _switch_user_logged_out -->
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
		&nbsp;&nbsp; &nbsp;&nbsp;<input type="submit" class="bold" name="login" value="{L_LOGIN}" /><br />
		<input class="text" type="checkbox" name="autologin" checked /> {L_AUTO_LOGIN}
		<br><a href="{U_REGISTER}">[ {L_REGISTER} ]</a>
		</td>
	</tr>
  </table>
</div>
</form>
<!-- END _switch_user_logged_out -->
{TEMP}

<p align="right">{ADMIN_LINK}<br /></p>
