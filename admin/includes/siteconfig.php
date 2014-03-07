<?php

if (@$_POST['prop_save']) {
	
	$sql = "UPDATE `".CONFIG_TABLE."` SET 
`config_value` = '".$_POST['prop_value']."'
 WHERE `config_name` = '".$_POST['prop_name']."';";


	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка сохранения пункта меню', '', __LINE__, __FILE__, $sql);
		}
	Else {
		$template->assign_block_vars('swich_save', array());
	};

};


//
// выбираем все меню из бд
//
$sql = 'SELECT * FROM `' . CONFIG_TABLE . '` WHERE `config_desc` IS NOT NULL;';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$prop_edit_list = array();
while( $row = $db->sql_fetchrow($result) ) {
	$prop_edit_list[] = $row;
	};

if (@$_GET['prop_name']) {

	$template->assign_block_vars('prop_edit', array(
		'PROP_NAME' => $_GET['prop_name'],
		'PROP_VALUE' => $prop_edit_list[$_GET['prop_name']]
	));
}
elseif ($_GET['edit']=='prop') {

	$template->assign_block_vars('swich_siteprop', array());
	
	$i=0;
	while ($prop_edit_list[$i]['config_name']) {
		$template->assign_block_vars('swich_siteprop.prop_list', array(
			'PROP_DESC' => $prop_edit_list[$i]['config_desc'],
			'PROP_VALUE' => $prop_edit_list[$i]['config_value'],
			'PROP_NAME' => $prop_edit_list[$i]['config_name'],
		));
		$i++;
	};


};

?>