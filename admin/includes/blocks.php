<?php

if (@$_POST['save']) {
	$sql = "UPDATE `".TABLE_BLOCKS."` SET 
`block_text` = '".$_POST['page_text']."'
 WHERE `block_id` = '".$_POST['article_id']."';";

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка сохранения пункта меню', '', __LINE__, __FILE__, $sql);
		}
	Else {
		$template->assign_block_vars('swich_save', array());
	};

};


//
// выбираем все блоки из бд
//
$sql = "SELECT * FROM `" . 
TABLE_BLOCKS . '`';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'Дополнительные блоки отсутствуют', '', __LINE__, __FILE__, $sql);
	};


$blocks_edit_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$blocks_edit_data[] = $row;
	};

//if (!$blocks_edit_data[0]["block_id"]) {
//	message_die(GENERAL_ERROR, 'Запрошенный блок не найден ' . @$add, '', __LINE__, __FILE__, $sql);
//};

if (@$_GET['block']) {
	$i=0;
	while ($blocks_edit_data[$i]["block_id"]) {
		if ($blocks_edit_data[$i]["block_id"]==$_GET['block']) {
//			$template->pparse('body');

			$submit_path = "/admin/index.php?edit=blocks";
			$page_id = $blocks_edit_data[$i]["block_id"];
			$page_text = $blocks_edit_data[$i]["block_text"];

			$rows_edit = "40"; // кол-во строк в редакторе

			include $DRoot . "/includes/edit.php";

			$template->assign_block_vars('switch_full_edit', array());
			$template->assign_block_vars('switch_blocks_edit', array());

		};
		$i++;
	};
}
elseif ($_GET['edit']=='blocks') {
	$template->assign_block_vars('swich_blocks_edit', array());


	$i=0;
	while ($blocks_edit_data[$i]["block_id"]) {
		$template->assign_block_vars('swich_blocks_edit.blocks_list', array(
			'BLOCK_ID' => $blocks_edit_data[$i]["block_id"],
			'BLOCK_TEXT' => $blocks_edit_data[$i]["block_text"]
		));
		$i++;
	};

};

?>
