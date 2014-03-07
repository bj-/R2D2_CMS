
<form action="{S_PROFILE_ACTION}" {S_FORM_ENCTYPE} method="post">

{ERROR_BOX}

&nbsp; <b><a href="{U_INDEX}">{L_INDEX}</a></b>

<div align="center"><table border="0" cellpadding="3" cellspacing="1" width="90%">
	<tr> 
		<th colspan="2">{L_REGISTRATION_INFO}</th>
	</tr>
	<tr> 
		<td colspan="2"><small>{L_ITEMS_REQUIRED}</small></td>
	</tr>
	<!-- BEGIN switch_namechange_disallowed -->
	<tr> 
		<td class="row1" width="38%">{L_USERNAME}: *</td>
		<td class="row2"><input type="hidden" name="username" value="{USERNAME}" /><b>{USERNAME}</b></td>
	</tr>
	<!-- END switch_namechange_disallowed -->
	<!-- BEGIN switch_namechange_allowed -->
	<tr> 
		<td class="row1" width="38%">{L_USERNAME}: *</td>
		<td class="row2"><input type="text" name="username" size="25" maxlength="25" value="{USERNAME}" /></td>
	</tr>
	<!-- END switch_namechange_allowed -->
	<tr> 
		<td class="row1">{L_EMAIL_ADDRESS}: *</td>
		<td class="row2"><input type="text" name="email" size="25" maxlength="255" value="{EMAIL}" /></td>
	</tr>
	<!-- BEGIN switch_edit_profile -->
	<tr> 
	  <td class="row1">{L_CURRENT_PASSWORD}: *<br />
		<small>{L_CONFIRM_PASSWORD_EXPLAIN}</small></td>
	  <td class="row2"> 
		<input type="password" name="cur_password" size="25" maxlength="32" value="{CUR_PASSWORD}" />
	  </td>
	</tr>
	<!-- END switch_edit_profile -->
	<tr> 
	  <td class="row1">{L_NEW_PASSWORD}: *<br />
		<small>{L_PASSWORD_IF_CHANGED}</small></td>
	  <td class="row2"> 
		<input type="password" name="new_password" size="25" maxlength="32" value="{NEW_PASSWORD}" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1">{L_CONFIRM_PASSWORD}: * <br />
		<small>{L_PASSWORD_CONFIRM_IF_CHANGED}</small></td>
	  <td class="row2"> 
		<input type="password" name="password_confirm" size="25" maxlength="32" value="{PASSWORD_CONFIRM}" />
	  </td>
	</tr>
	<!-- Visual Confirmation -->
	<!-- BEGIN switch_confirm -->
	<tr>
		<td class="row1" colspan="2" align="center"><small>{L_CONFIRM_CODE_IMPAIRED}</small><br /><br />{CONFIRM_IMG}<br /><br /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_CONFIRM_CODE}: * <br /><small>{L_CONFIRM_CODE_EXPLAIN}</small></td>
	  <td class="row2"><input type="text" name="confirm_code" size="6" maxlength="6" value="" /></td>
	</tr>
	<!-- END switch_confirm -->
	<tr><td colspan="2"><hr></td></tr>
	<tr> 
	  <th colspan="2">{L_PROFILE_INFO}</th>
	</tr>
	<tr> 
	  <td colspan="2"><small>{L_PROFILE_INFO_NOTICE}</small></td>
	</tr>
	<tr> 
	  <td class="row1">{L_CAR}: *</td>
	  <td class="row2"> 
		<input type="text" name="car" size="35" maxlength="64" value="{CAR}" /><br>
		<small>{L_CAR_DESCR}</small>
	  </td>
	</tr>
	<tr> 
	  <td class="row1">{L_LOCATION}: *</td>
	  <td class="row2"> 
		<input type="text" name="location" size="25" maxlength="100" value="{LOCATION}" /><br>
		<small>{L_LOCATION_DESCR}</small>
	  </td>
	</tr>
	<tr> 
	  <td class="row1">{L_WEBSITE}:</td>
	  <td class="row2"> 
		<input type="text" name="website" size="25" maxlength="255" value="{WEB_SITE}" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1">{L_OCCUPATION}:</td>
	  <td class="row2"> 
		<input type="text" name="occupation" size="25" maxlength="100" value="{OCCUPATION}" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1">{L_INTERESTS}:</td>
	  <td class="row2"> 
		<input type="text" name="interests" size="35" maxlength="150" value="{INTERESTS}" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1">{L_SIGNATURE}:<br /><small>{L_SIGNATURE_EXPLAIN}<br /><br />{HTML_STATUS}<br />{BBCODE_STATUS}<br />{SMILIES_STATUS}</small></td>
	  <td class="row2"> 
		<textarea name="signature" rows="6" cols="40">{SIGNATURE}</textarea>
	  </td>
	</tr>
	<tr> 
	  <td class="row1">{L_ICQ_NUMBER}:</td>
	  <td class="row2"> 
		<input type="text" name="icq" size="20" maxlength="15" value="{ICQ}" />
	  </td>
	</tr>
	
	<input type="hidden" name="aim" value="{AIM}">
	<input type="hidden" name="msn" value="{MSN}">
	<input type="hidden" name="yim" value="{YIM}">
	<!-- BEGIN other_msg -->
	<tr> 
	  <td class="row1">{L_AIM}:</td>
	  <td class="row2"> 
		<input type="text" name="aim" size="20" maxlength="255" value="{AIM}" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1">{L_MESSENGER}:</td>
	  <td class="row2"> 
		<input type="text" name="msn" size="20" maxlength="255" value="{MSN}" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1">{L_YAHOO}:</td>
	  <td class="row2"> 
		<input type="text" name="yim" size="20" maxlength="255" value="{YIM}" />
	  </td>
	</tr>
	<!-- END other_msg -->
	<tr><td colspan="2"><hr></td></tr>
	<tr> 
	  <th colspan="2">{L_PREFERENCES}</th>
	</tr>
	<tr> 
	  <td class="row1">{L_FORUM_TREE}:</td>
	  <td class="row2"> 
		<input type="radio" name="viewtree" value="1" {VIEW_TREE_YES} />{L_TREE_YES}&nbsp;&nbsp; 
		<input type="radio" name="viewtree" value="0" {VIEW_TREE_NO} />{L_TREE_NO}</td>
	</tr>
	<tr> 
	  <td class="row1">{L_TOOPTIPS}:<br><Small>{L_DESCR_TOOPTIPS}</SMALL></td>
	  <td class="row2"> 
		<input type="text" name="aim" size="50" maxlength="5" value="{AIM}" /><br><Small>{L_DESCR_TOOPTIPS2}</SMALL>
	  </td>
	</tr>
	<tr> 
	  <td class="row1">{L_PUBLIC_VIEW_EMAIL}:</td>
	  <td class="row2"> 
		<input type="radio" name="viewemail" value="1" {VIEW_EMAIL_YES} />{L_YES}&nbsp;&nbsp; 
		<input type="radio" name="viewemail" value="0" {VIEW_EMAIL_NO} />{L_NO}</td>
	</tr>
	<tr> 
	  <td class="row1">{L_NOTIFY_ON_REPLY}:<br /><small>{L_NOTIFY_ON_REPLY_EXPLAIN}</small></td>
	  <td class="row2"> 
		<input type="radio" name="notifyreply" value="1" {NOTIFY_REPLY_YES} />{L_YES}&nbsp;&nbsp; 
		<input type="radio" name="notifyreply" value="0" {NOTIFY_REPLY_NO} />{L_NO}</td>
	</tr>
	<tr> 
	  <td class="row1">{L_NOTIFY_ON_PRIVMSG}:</td>
	  <td class="row2"> 
		<input type="radio" name="notifypm" value="1" {NOTIFY_PM_YES} />{L_YES}&nbsp;&nbsp; 
		<input type="radio" name="notifypm" value="0" {NOTIFY_PM_NO} />{L_NO}</td>
	</tr>

	  	<input type="hidden" name="hideonline" value="0"> 
		<input type="hidden" name="popup_pm" value="1">
		<input type="hidden" name="attachsig" value="1"> 
		<input type="hidden" name="allowbbcode" value="1">
		<input type="hidden" name="allowhtml" value="0">
		<input type="hidden" name="allowsmilies" value="1"> 

	<!-- BEGIN other_option -->
	<tr> 
	  <td class="row1"><span class="gen">{L_HIDE_USER}:</span></td>
	  <td class="row2"> 
	  	<input type="radio" name="hideonline" value="1" {HIDE_USER_YES} />{L_YES}&nbsp;&nbsp; 
		<input type="radio" name="hideonline" value="0" {HIDE_USER_NO} />{L_NO}</td>
	</tr>
	<tr> 
	  <td class="row1">{L_POPUP_ON_PRIVMSG}:<br /><small>{L_POPUP_ON_PRIVMSG_EXPLAIN}</small></td>
	  <td class="row2"> 
		<input type="radio" name="popup_pm" value="1" {POPUP_PM_YES} />{L_YES}&nbsp;&nbsp; 
		<input type="radio" name="popup_pm" value="0" {POPUP_PM_NO} />{L_NO}</td>
	</tr>
	<tr> 
	  <td class="row1">{L_ALWAYS_ADD_SIGNATURE}:</td>
	  <td class="row2"> 
		<input type="radio" name="attachsig" value="1" {ALWAYS_ADD_SIGNATURE_YES} />{L_YES}&nbsp;&nbsp; 
		<input type="radio" name="attachsig" value="0" {ALWAYS_ADD_SIGNATURE_NO} />{L_NO}</td>
	</tr>
	<tr> 
	  <td class="row1">{L_ALWAYS_ALLOW_BBCODE}:</td>
	  <td class="row2"> 
		<input type="radio" name="allowbbcode" value="1" {ALWAYS_ALLOW_BBCODE_YES} />{L_YES}&nbsp;&nbsp; 
		<input type="radio" name="allowbbcode" value="0" {ALWAYS_ALLOW_BBCODE_NO} />{L_NO}</td>
	</tr>
	<tr> 
	  <td class="row1">{L_ALWAYS_ALLOW_HTML}:</td>
	  <td class="row2"> 
		<input type="radio" name="allowhtml" value="1" {ALWAYS_ALLOW_HTML_YES} />{L_YES}&nbsp;&nbsp; 
		<input type="radio" name="allowhtml" value="0" {ALWAYS_ALLOW_HTML_NO} />{L_NO}</td>
	</tr>
	<tr> 
	  <td class="row1">{L_ALWAYS_ALLOW_SMILIES}:</td>
	  <td class="row2"> 
		<input type="radio" name="allowsmilies" value="1" {ALWAYS_ALLOW_SMILIES_YES} />{L_YES}&nbsp;&nbsp; 
		<input type="radio" name="allowsmilies" value="0" {ALWAYS_ALLOW_SMILIES_NO} />{L_NO}</td>
	</tr>
	<!-- END other_option -->
	
	<tr><td class="row1">{L_BOARD_LANGUAGE}:</td><td class="row2"><small>{LANGUAGE_SELECT}</small></td></tr>
	<tr><td class="row1">{L_BOARD_STYLE}:</td><td class="row2"><small>{STYLE_SELECT}</small></td></tr>
	<tr><td class="row1">{L_TIMEZONE}:</td><td class="row2"><small>{TIMEZONE_SELECT}</small></td></tr>
	<tr> 
	  <td class="row1">{L_DATE_FORMAT}:<br /><small>{L_DATE_FORMAT_EXPLAIN}</small></td>
	  <td class="row2"><input type="text" name="dateformat" value="{DATE_FORMAT}" maxlength="14" class="post" /></td>
	</tr>
	<!-- BEGIN switch_avatar_block -->
	<tr> <td colspan="2"><hr></td></tr>
	<tr> 
	  <th colspan="2" height="12" valign="middle">{L_AVATAR_PANEL}</th>
	</tr>
	<tr> 
		<td class="row1" colspan="2"><table width="70%" cellspacing="2" cellpadding="0" border="0" align="center">
			<tr> 
				<td width="65%">{L_AVATAR_EXPLAIN}</td>
				<td align="center">{L_CURRENT_IMAGE}<br />{AVATAR}<br /><input type="checkbox" name="avatardel" />&nbsp;<small>{L_DELETE_AVATAR}</small></td>
			</tr>
		</table></td>
	</tr>
	<!-- BEGIN switch_avatar_local_upload -->
	<tr> 
	  <td class="row1">{L_SHOW_AVATAR}:<br>
	  <small>{L_SHOW_AVATAR_DESCR}</small></td>
	  <td class="row2"> 
		<input type="radio" name="showavatar" value="1" {SHOW_AVATAR_YES} />{L_YES}&nbsp;&nbsp; 
		<input type="radio" name="showavatar" value="0" {SHOW_AVATAR_NO} />{L_NO}</td>
	</tr>
	<tr> 
		<td class="row1">{L_UPLOAD_AVATAR_FILE}:</td>
		<td class="row2"><input type="hidden" name="MAX_FILE_SIZE" value="{AVATAR_SIZE}" /><input type="file" name="avatar" class="post" style="width:200px" /></td>
	</tr>
	<!-- END switch_avatar_local_upload -->
	<!-- BEGIN switch_avatar_remote_upload -->
	<tr> 
		<td class="row1">{L_UPLOAD_AVATAR_URL}:<br /><small>{L_UPLOAD_AVATAR_URL_EXPLAIN}</small></td>
		<td class="row2"><input type="text" name="avatarurl" size="40" style="width:200px" /></td>
	</tr>
	<!-- END switch_avatar_remote_upload -->
	<!-- BEGIN switch_avatar_remote_link -->
	<tr> 
		<td class="row1">{L_LINK_REMOTE_AVATAR}:<br /><small>{L_LINK_REMOTE_AVATAR_EXPLAIN}</small></td>
		<td class="row2"><input type="text" name="avatarremoteurl" size="40" style="width:200px" /></td>
	</tr>
	<!-- END switch_avatar_remote_link -->
	<!-- BEGIN switch_avatar_local_gallery -->
	<tr> 
		<td class="row1">{L_AVATAR_GALLERY}:</td>
		<td class="row2"><input type="submit" name="avatargallery" value="{L_SHOW_GALLERY}" /></td>
	</tr>
	<!-- END switch_avatar_local_gallery -->
	<!-- END switch_avatar_block -->
</table></div>
		<p align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="bold" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" /></p>

</form>
