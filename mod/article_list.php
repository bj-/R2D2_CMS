<?php
/***************************************************************************************
*
*			ћод - список статей в разделе
*
*	$list_type
* array - массив 
* menu_link - списком меню в формате: <table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td>
*										<a href="#" class="vmenu">'.$mod_article_list[$i]["article_title"].'</a></td></tr></table>
*										<img src="pic/bluepx.gif" alt="" width="200" height="1" border="0"><br>';
*
****************************************************************************************/

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

function mod_article_list($list_type) {
	global $db, $current_paragraf_id, $url_lang;
	$ret = '';

	$sql = "SELECT * FROM `" . TABLE_ARTICLE ."` WHERE `paragraf_id` = '".$current_paragraf_id."' and `primary_article` = '0' ORDER BY `article_id`";

	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ѕаза статей отсутствует', '', __LINE__, __FILE__, $sql);
		};


	$mod_article_list = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$mod_article_list[] = $row;
		};

	if (!$mod_article_list[0]["article_id"]) {
	//	message_die(GENERAL_ERROR, '«апрошенна€ новость не найдена ' . @$add_news, '', __LINE__, __FILE__, $sql);
		};

	If ($list_type == "array") {
	
	}
	Elseif ($list_type == "menu_link") {
		$i=0;
		while ($mod_article_list[$i]["article_id"]) {
			$url = "/" . $url_lang . "/" . get_full_url($mod_article_list[$i]["paragraf_id"]) . "/" . $mod_article_list[$i]["article_name"] . "-" . $mod_article_list[$i]["article_id"] . ".html";
			$ret .= '<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td><a href="'.$url.'" class="vmenu">'.$mod_article_list[$i]["article_title"].'</a></td></tr></table><img src="pic/bluepx.gif" alt="" width="200" height="1" border="0"><br>';

			$i++;
		};
	};
	
	return $ret;
};
?>