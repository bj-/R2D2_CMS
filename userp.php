<?php
/***************************************************************************
 *                                userp.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: userp.php,v 1.47.2.13 2003/06/20 07:40:27 acydburn Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));


//
// Allow people to reach login page if
// board is shut down
//
define('IN_R2D2', true);
include "includes/config.php";
include $DRoot . "/includes/common.php";
//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);
//
// End session management
//

// session id check
if (!empty($_POST['sid']) || !empty($_GET['sid']))
{
	$sid = (!empty($_POST['sid'])) ? $_POST['sid'] : $_GET['sid'];
}
else
{
	$sid = '';
}


$username = ( $userdata['user_id'] != ANONYMOUS ) ? $userdata['username'] : '';


$page_title = $lang['Login'];                      	
include($DRoot . '/includes/page_header.'.$phpEx);

//var_dump($userdata);
$EditProfile = ( $_GET["mode"] == "editprofile" ) ? true : false;
$modeRegister = ( $_GET["mode"] == "register" ) ? true : false;
$post_save    = ( $_POST["Save"] != "") ? true : false;
$post_oldPass = md5($_POST["password_old"]);
$post_NewPass = ( strlen($_POST["password_new"]) < 33 ) ? md5($_POST["password_new"]) : "sdfsdfsdfsdf521g562h45yb28nn8724q36b3476n457n442b636ASDGnytj56$#T$^";
$post_RepeatPass = md5($_POST["password_repeat"]);
$post_username = substr(trim($_POST["username"]), 0, 32);
$post_email = trim($_POST["email"]);
$post_lastname = substr(trim($_POST["lastname"]), 0, 32);
$post_firstname = substr(trim($_POST["firstname"]), 0, 32);
//str_encode_char(

if ( $userdata['session_logged_in'] and $EditProfile ) 
{
	//echo "Edit user prop";
	$template->set_filenames(array(
		'body' => 'userprop_edit.tpl'
	));
	if ( $post_NewPass == $post_RepeatPass and $userdata["user_password"] == $post_oldPass ) 
	{
		//echo "set new pass";

		$sql = 'UPDATE `' . USERS_TABLE . '` '.
			'SET `user_password` = "'.$post_NewPass.'" WHERE `user_id` = "'. $userdata["user_id"] .'"';

		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'Ошибка изменения пароля. Велено грустить.', '', __LINE__, __FILE__, $sql);
		};

		$Saved = $lang['Saved'];

		$template->assign_block_vars('switch_saved', array(
		));

		//$article_data = array();
		//while( $row = $db->sql_fetchrow($result) ) {
		//	$article_data[] = $row;
		///};


		
	}
	else
	{

		$template->assign_block_vars('switch_user_form', array(
		));

//		echo "set new pass";
		$ErrNewPass = ( $post_NewPass != $post_RepeatPass) ? $lang['Password_mismatch'] : "";
		$ErrOldPass = ( $userdata["user_password"] != $post_oldPass and $post_save ) ? $lang['Current_password_mismatch'] : "";
		$ErrLongPass = ( strlen($_POST["password_new"]) > 32 ) ? $lang['Password_long'] : "";

	}	


}
elseif ( $userdata['session_logged_in'] and !$EditProfile ) 
{
	//echo "User profile";
	$template->set_filenames(array(
		'body' => 'userprop_body.tpl'
	));

}
elseif ( $modeRegister ) 
{

	// New User ID
	$sql = 'SELECT max(`user_id`) AS `user_id` FROM `' . USERS_TABLE . '`';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка запроса максимального ID пользователя.', '', __LINE__, __FILE__, $sql);
	};
	$max_id = array();
	$row = $db->sql_fetchrow($result);
	$max_id = $row;
	$new_user_id = $max_id["user_id"] + 1;

	// existed users list
	$sql = 'SELECT `username`, `user_email` FROM `' . USERS_TABLE . '`';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка запроса списка пользователей.', '', __LINE__, __FILE__, $sql);
	};
	$userlist = array();
	$emaillist = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$userlist[] = $row["username"];
		$emaillist[] = $row["user_email"];
	};

	//echo "Register new user";
	$template->set_filenames(array(
		'body' => 'userprop_reg.tpl'
	));
	$template->assign_vars(array(
		'REG_USERNAME' => @$post_username,
		'REG_EMAIL' => @$post_email,
		'REG_LASTNAME' => @$post_lastname,
		'REG_FIRSTNAME' => @$post_firstname

	));


	if ( $post_save )
	{
		$user_exist = ( in_array($post_username, $userlist) ) ? $lang['Username_taken'] : "";
		$user_long = ( strlen($post_username) > 32 ) ? $lang['Username_disallowed'] : "";
		//$user_invalid = ( strpos($post_username) > 32 ) ? $lang['Username_invalid'] : "";
		$email_exist = ( in_array($post_email, $emaillist) ) ? $lang['Email_taken'] : "";
		$email_long = ( strlen($post_email) > 32 ) ? $lang['Email_invalid'] : "";
		$email_invalid = ( ! strpos($post_email, "@") ) ? $lang['Email_invalid'] : "";
		$ErrNewPass = ( $post_NewPass != $post_RepeatPass) ? $lang['Password_mismatch'] : "";
		$ErrLongPass = ( strlen($post_NewPass) > 32 ) ? $lang['Password_long'] : "";

		//echo strlen($post_email) . strlen($post_username) . strlen($_POST["password_new"]) . strlen($_POST["password_repeat"]);
		$fileds_empty = ( strlen($post_email) and strlen($post_username) and strlen($post_lastname) and strlen($post_firstname) and strlen($_POST["password_new"]) and strlen($_POST["password_repeat"]) ) ? "" : $lang['Fields_empty'];

		// correct/incorrect form
		if ( $user_exist or $user_long or $email_exist or $email_long or $email_invalid or $ErrNewPass or $ErrLongPass or $fileds_empty )
		{
			// incorrect data. show form again
			$template->assign_block_vars('switch_reg_form', array(
			));
		}
		else
		{
			$post_username = str_replace('"', '', str_encode_char($post_username));
			$post_email = str_replace('"', '', str_encode_char($post_email));
			$post_lastname = str_replace('"', '', str_encode_char($post_lastname));
			$post_firstname = str_replace('"', '', str_encode_char($post_firstname));
			// register user
			$sql = 'INSERT INTO `' . USERS_TABLE . '` 
				(`user_id`, `username`, `user_email`, `name_last_r`, `name_first_r`, `user_password`) 
				VALUES ("'.$new_user_id.'", "'. $post_username .'","'. $post_email .'","'. $post_lastname .'","'. $post_firstname .'","'. $post_NewPass .'") ';
	
			if ( !($result = $db->sql_query($sql)) ) {
				message_die(GENERAL_ERROR, 'Ошибка регистрации пользователя.', '', __LINE__, __FILE__, $sql);
			};

			// continue registration
			$template->assign_block_vars('switch_reg_complette', array(
			));
		}

	}
	else
	{
		$template->assign_block_vars('switch_reg_form', array(
		));
	}

//	$ErrNewPass = ( $post_NewPass != $post_RepeatPass) ? $lang['Password_mismatch'] : "";
//	$ErrLongPass = ( strlen($_POST["password_new"]) > 32 ) ? $lang['Password_long'] : "";


}
else
{
	echo "Impossible way";
}


//$lang['Current_password']
//$lang['New_password']
//$lang['Confirm_password']


$template->assign_vars(array(
	'USERNAME' => $username,

	'L_USER_EXIST' => $user_exist,
	'L_USERNAME_LONG' => $user_long,
	//'L_' => $user_invalid,
	'L_EMAIL_EXIST' => $email_exist,
	'L_EMAIL_LONG' => $email_long,
	'L_EMAIL_INVALID' => $email_invalid,
	'L_FIELDS_EMPTY' => $fileds_empty,
	'L_ACCOUNT_ADDED' => $lang['Account_added'],
	'L_LOGIN_ACTION' => $lang['Login'],

	'L_LASTNAME' => "Last Name",
	'L_FIRSTNAME' => "First Name",

	'L_SAVE' => $lang['Save'],
	'L_SAVED' => $lang['Saved'],
	'L_EMAIL' => $lang['Email'],
	'L_CURRENT_PASSWORD' => $lang['Current_password'],
	'L_NEW_PASSWORD' => $lang['New_password'],
	'L_CONFIRN_PASSWORD' => $lang['Confirm_password'],
	'L_REGISTER' => $lang['Register'],
	'L_ACCOUNT_ADDED' => $lang['Account_added'],
	'L_PASS_MISMATCH' => @$ErrNewPass,
	'L_WRONG_PASSWORD' => @$ErrOldPass,
	'L_LONG_PASSWORD' => @$ErrLongPass,
	'L_SAVED' => @$Saved,

	'U_SEND_PASSWORD' => append_sid("userp.$phpEx?mode=sendpassword"),



	'S_HIDDEN_FIELDS' => $s_hidden_fields
));      	

$template->pparse('body');


include($DRoot . '/includes/page_tail.'.$phpEx);

?>