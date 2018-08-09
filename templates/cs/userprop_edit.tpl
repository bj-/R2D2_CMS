<form action="{U_PROFILE}" method="post" target="_top">

<table>
	<tr>
		<td>{L_USERNAME}</td>
		<td><strong>{USERNAME}</strong></td>
	</tr>
	<tr>
		<td>E-Mail</td>
		<td></td>
	</tr>
	<tr>
		<!-- BEGIN switch_saved -->
		<tr><td></td><td><strong>{L_SAVED}</strong></td></tr>
		<!-- END switch_saved -->

		<!-- BEGIN switch_user_form -->
		<td>{L_PASSWORD}</td>
		<td>
			<table>
				<tr><td>{L_CURRENT_PASSWORD}:</td><td><input type="password" name="password_old" size="25" maxlength="32" /> <span style="color:red;">{L_WRONG_PASSWORD}</span></td></tr>
				<tr><td>{L_NEW_PASSWORD}:</td><td><input type="password" name="password_new" size="25" maxlength="32" /> <span style="color:red;">{L_PASS_MISMATCH}</span></td></tr>
				<tr><td>{L_CONFIRN_PASSWORD}:</td><td><input type="password" name="password_repeat" size="25" maxlength="32" /> <span style="color:red;">{L_LONG_PASSWORD}</span></td></tr>
			</table>
		</td>
		<!-- END switch_user_form -->
	</tr>
	<tr>
		<td>{S_HIDDEN_FIELDS}</td>
		<td>
			<!-- BEGIN switch_user_form -->
			<input type="submit" name="Save" class="bold" value="{L_SAVE}" />
			<!-- END switch_user_form -->
		<td>
	</tr>
</table>
</form>