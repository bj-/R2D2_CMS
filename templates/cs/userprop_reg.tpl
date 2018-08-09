<!-- BEGIN switch_reg_form -->
<form action="{U_REGISTER}" method="post" target="_top">
<table>
<tr><td>{L_USERNAME}:</td><td><input type="text" name="username" size="25" maxlength="40" value="{REG_USERNAME}" />{L_USER_EXIST} {L_USERNAME_LONG}</td></tr>
<tr><td>{L_EMAIL}:</td><td><input type="text" name="email" size="25" maxlength="40" value="{REG_EMAIL}" />{L_EMAIL_EXIST} {L_EMAIL_LONG} {L_EMAIL_INVALID}</td></tr>
<tr><td>{L_LASTNAME}:</td><td><input type="text" name="lastname" size="25" maxlength="40" value="{REG_LASTNAME}" /></td></tr>
<tr><td>{L_FIRSTNAME}:</td><td><input type="text" name="firstname" size="25" maxlength="40" value="{REG_FIRSTNAME}" /></td></tr>
<tr><td>{L_NEW_PASSWORD}:</td><td><input type="password" name="password_new" size="25" maxlength="32" /> <span style="color:red;">{L_PASS_MISMATCH}</span></td></tr>
<tr><td>{L_CONFIRN_PASSWORD}:</td><td><input type="password" name="password_repeat" size="25" maxlength="32" /> <span style="color:red;">{L_LONG_PASSWORD}</span></td></tr>
<tr><td></td><td>{L_FIELDS_EMPTY}</td></tr>
<tr><td></td><td>{S_HIDDEN_FIELDS}<input type="submit" name="Save" class="bold" value="{L_REGISTER}" /></td></tr>
</table>
</form>
<!-- END switch_reg_form -->

<!-- BEGIN switch_reg_complette -->
{L_ACCOUNT_ADDED}
<a href="{S_LOGIN_ACTION}">{L_LOGIN_ACTION}</a>
<!-- END switch_reg_complette -->
