<?php
/***************************************************************************
 *                                dyn_form_prop.php
 *                            -------------------
 *   begin                : Jun 13, 2010
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: event.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   License
 *
 ***************************************************************************/
$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));


define('IN_R2D2', true);
include("../../includes/config.php");
include($DRoot . '/includes/common.'.$phpEx);
include($DRoot . '/mod/form/form_config.'.$phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);
//
// End session management
//

// ѕровер€ем права пользовател€. если нет - выкидываем
if ($userdata['user_level'] < 1) {
	message_die(GENERAL_ERROR, 'ѕользователь не имеет прав', '', __LINE__, __FILE__, $sql);
	exit;
};

$get_id = intval(substr($_GET["id"],0,11));
$get_form_id = intval(substr($_GET["form_id"],0,11));
$get_action = substr($_GET["action"],0,20);



define('SHOW_ONLINE', true);

$template->set_filenames(array(
	'body' => 'admin/form/adm_dyn_form_prop.tpl')
);

if ($_GET["action"] == "edit") {
	// выбираем название и описание формы
	$sql = 'SELECT * '.
			'FROM `' . TABLE_FORMS_FIELDS . '` '.
			'WHERE `field_id` = "'.$get_id.'";';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, '“аблица полей форм отсутствует', '', __LINE__, __FILE__, $sql);
	};
	$forms_data = $db->sql_fetchrow($result);
	
	$field_id = $forms_data["field_id"];
	$form_id = $forms_data["form_id"];
	$field_name = $forms_data["field_name"];
	$field_f_type = $forms_data["field_type"];
	$field_val = $forms_data["field_val"];
	$field_size = $forms_data["field_size"];
	$field_maxlen = $forms_data["field_maxlen"];
	$field_require = ($forms_data["field_require"]) ? ' checked="checked"' : "";
	$field_sort = $forms_data["field_sort"];
	$field_class = $forms_data["field_class"];
	$field_style  = $forms_data["field_style"];
	
	if (strpos($field_size, ";")) {
		$field_size_xy = explode(";",$field_size);
		$field_size_width = $field_size_xy[0];
		$field_size_height = $field_size_xy[1];
	};
	

}
else {
	// дефолтные значени€ при создании нового пол€ формы.

	$field_id = 'new';
	$form_id = $get_form_id;
	$field_name = "";
	$field_f_type = "1";
	$field_val = "";
	$field_size = "50";
		$field_size_width = 60;
		$field_size_height = 15;
	$field_maxlen = 250;

};

$template->assign_vars(array(
	'FIELD_ID' => $field_id,
	'FIELD_NAME' => $field_name,
	'FORM_ID' => $form_id,
	'FIELD_VAL' => $field_val,
	'FIELD_SIZE' => $field_size,
	'FIELD_MAXLEN' => $field_maxlen,
	'FIELD_REQUIRED' => $field_require,
	'FIELD_SORT' => $field_sort,
	'FIELD_CLASS' => $field_class,
	'FIELD_STYLE' => $field_style,
	'FIELD_TYPE_ID' => $field_f_type,
	'FIELD_SIZE_WIDTH' => $field_size_width,
	'FIELD_SIZE_HEIGHT' => $field_size_height
));

$i = 1;
while ($field_type[$i]) {
	$ffsel = ($i == $field_f_type) ? " selected" : "";
	$template->assign_block_vars('field_type_list', array(
		'FIELD_TYPE_ID' => $i,
		'FIELD_TYPE_NAME' => $field_type[$i],
		'FIELD_TYPE_SEL' => $ffsel,
	));
	$i++;
};



//
// Generate the page
//
$template->pparse('body');

?>