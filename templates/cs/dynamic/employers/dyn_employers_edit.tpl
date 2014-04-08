<!-- BEGIN employer_edit -->
<form action="#employer{EMPLOYER_ID}" id="employer_edit" name="employer_edit" method="post">
	<input name="id" type="hidden" value="{EMPLOYER_ID}" />
	<input name="action" type="hidden" value="{ACTION}" />
	<div class="employer_id">{EMPLOYER_ID}</div>
	<div style="float:left; width:250px;">
		Компания:<br /><input name="employer_name" type="text" value="{EMPLOYER_NAME}" size="35" maxlength="255" /><br />
		URL:<br /><input name="employer_url" type="text" value="{EMPLOYER_URL}" size="35" maxlength="255" />
	</div>
	<div style="float:left; width:200px;">
		e-mail:<br /><input name="employer_email" type="text" value="{EMPLOYER_EMAIL}" size="25" maxlength="255" /><br />
		job:<br /><input name="employer_jobemail" type="text" value="{EMPLOYER_JOBEMAIL}" size="25" maxlength="255" />
	</div>
	<div style="float:left; width:200px;">
		метро:<br /><input name="employer_metro" type="text" value="{EMPLOYER_METRO}" size="25" maxlength="255" /><br />
		Адрес:<br /><input name="employer_address" type="text" value="{EMPLOYER_ADDRESS}" size="25" maxlength="255" /><br />
	</div>
	<div style="float:left; width:150px;">
		Отклик сайт<br />
		<select name="employer_resp_site">
			{EMPLOYER_RESP_SITE}
		</select><br />
		Отклик директом<br />
		<select name="employer_resp_direct">
			{EMPLOYER_RESP_DIRECT}
		</select>
		Отклик фейком<br />
		<select name="employer_resp_fake">
			{EMPLOYER_RESP_FAKE}
		</select>
	</div>
	<div style="float:left; width:200px;">
		Направление/OS:<br /><input name="employer_os" type="text" value="{EMPLOYER_OS}" size="25" maxlength="255" /><br />
	</div>
	<div style="float:left; width:30px;"><a onclick='document.getElementById("employer_edit").submit()' style="cursor: pointer;"><img src="/pic/ico/disk.gif" width="16" height="16" border="0" /></a></div>
<div style="clear: left;"></div>
<div><textarea name="employer_desc" cols="80" rows="15">{EMPLOYER_DESC}</textarea></div>
</form>
<!-- END employer_edit -->

<!-- BEGIN employer_details -->
	<div style="float:left;"><a href="http://{EMPLOYER_URL}" target="_blank">{EMPLOYER_URL}</a></div>
	<div style="float:left; padding-left:20px;">email: {EMPLOYER_EMAIL}</div>
	<div style="float:left; padding-left:20px;">J-email: {EMPLOYER_JOBEMAIL}</div>
	<div style="float:left; padding-left:20px;">Адрес: {EMPLOYER_ADDRESS}</div>
	<div style="clear: left;"></div>
	<div>{EMPLOYER_DESC}</div>
<!-- END employer_details -->
