<?php

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

if ($news_type =='last') {
$sql = "SELECT * FROM `" . TABLE_NEWS ."` WHERE `news_active` = '1'". $news_id . ' ORDER BY news_date DESC LIMIT 0,1';
};

/*
$news_id = "";
if (@$get_news) {
	$news_id = ' and `news_id` = "' . $get_news . '"';
};
*/

//$sql = "SELECT * FROM `" . TABLE_NEWS ."` WHERE `news_active` = '1'". $news_id . ' ORDER BY news_date DESC LIMIT 0,30';
	
if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База новостей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$mod_news = array();
while( $row = $db->sql_fetchrow($result) ) {
	$mod_news[] = $row;
	};

if (!$mod_news[0]["news_id"]) {
	message_die(GENERAL_ERROR, 'Запрошенная новость не найдена ' . @$add_news, '', __LINE__, __FILE__, $sql);
	};

if (@$news_len) {
	$mod_news_text_cut = substr($mod_news[0]["news_text"], 0, $news_len);
	if ($mod_news_text_cut <> $mod_news[0]["news_text"]) {
		$mod_news[0]["news_text"] = substr($mod_news_text_cut, 0, strrpos($mod_news_text_cut, ".")+1);
		$mod_news_cut = 1;
	};
};
?>