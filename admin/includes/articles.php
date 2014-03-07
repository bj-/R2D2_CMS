<?php

//
// выбираем все меню из бд
// 
$sql = "SELECT * FROM `" . 
TABLE_ARTICLE . '` WHERE `article_id` > "3" ORDER BY `paragraf_id` ASC';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
	};

$article_edit_data = array();
while( $row = $db->sql_fetchrow($result) ) {
	$article_edit_data[] = $row;
	};

if (!$article_edit_data[0]["article_id"]) {
	message_die(GENERAL_ERROR, 'Запрошенная статья не найдена ' . $add, '', __LINE__, __FILE__, $sql);
};


if (@$_GET['menu_id']) {
}
elseif ($_GET['edit']=='articles') {

	$template->assign_block_vars('swich_article_list', array());
	$i=0;

	while ($article_edit_data[$i]["article_id"]) {

		if (@$article_edit_data[$i]["paragraf_id"]) {
			$url = "/" . $url_lang . "/" . get_full_url($article_edit_data[$i]["paragraf_id"]) . "/" . $article_edit_data[$i]["article_name"] . "-" . $article_edit_data[$i]["article_id"] . ".html";
//			$url = "/" . $url_lang . "/" . $article_edit_data[$i]["paragraf_id"] . "/" . $article_edit_data[$i]["article_name"] . "-" . $article_edit_data[$i]["article_id"] . ".html";
		}
		else {
			$url = '/';
		};

		$template->assign_block_vars('swich_article_list.article_list', array(
			'ARTICLE_ID' => $article_edit_data[$i]["article_id"],
			'ARTICLE_PARAGRAF' => $topmenu_data[$article_edit_data[$i]["paragraf_id"]]['menu_name'],
			'ARTICLE_NAME' => $article_edit_data[$i]["article_title"],
			'ARTICLE_LINK' => $url
		));

		$i++;
	};

};

?>