<?php
if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

//
// дополнительные блоки на странице
//
$sql = "SELECT * FROM `" . TABLE_BLOCKS . '`';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'Дополнительные блоки отсутствуют', '', __LINE__, __FILE__, $sql);
	};
$blocks_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	// новый вариант
	$blok = array( "id" => $row["block_id"],
							"text" => $row["block_text"],
							"edit" => '<div style="position:absolute; top: -8px; right: 20px;"><a href="/admin/index.php?edit=blocks&block='.$row["block_id"].'" title="Редактировать блок">'.
											'<img src="/pic/ico/edit.gif" alt="Редактировать блок" width="16" height="16" border="0" /></a></div>'
					 );
	$blocks_xdata[] = $blok;
	
	//старый вариант
	$blocks_data[$row["block_aligment"]][$row["block_xid"]]["id"] = $row["block_id"];
	$blocks_data[$row["block_aligment"]][$row["block_xid"]]["text"] = $row["block_text"];
	$blocks_data[$row["block_aligment"]][$row["block_xid"]]["edit"] = '<div style="position:absolute; top: -8px; right: 20px;"><a href="/admin/index.php?edit=blocks&block='.$row["block_id"].'" title="Редактировать блок">'.
																							'<img src="/pic/ico/edit.gif" alt="Редактировать блок" width="16" height="16" border="0" /></a></div>';
	
	// Дерево блоков для автопостроителя.
	$bloks[] = $row;
	$blocks_edit[] = '<div style="position:absolute; top: -8px; right: 20px;"><a href="/admin/index.php?edit=blocks&block='.$row["block_id"].'" title="Редактировать блок">'.
							'<img src="/pic/ico/edit.gif" alt="Редактировать блок" width="16" height="16" border="0" /></a></div>';

//	$blocks_data[$row["block_id"]] = $row["block_text"];
};
//var_dump($blocks_xdata);
?>