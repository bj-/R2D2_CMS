<?php
/***************************************************************************
 *                                dyn_news_list.php
 *                            -------------------
 *   begin                : Jun 13, 2010
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: article.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
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
//include($DRoot . '/db/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);
//
// End session management
//

//$cat_id = substr($_GET["id"],0,11);
//$page =  substr($_GET["page"],0,11);
$limit = intval(substr($_GET["limit"],0,2));
$source_id = intval(substr($_GET["src"],0,3));
$rnd = ($_GET["random"]) ? 'GROUP BY RAND() ' : '';

$sql = 'SELECT `news_id`, `news_active`, `news_source_id`, `news_date`, `news_name`, `path_name`, `news_img`, `news_img_size`, `news_video`, `news_source`, `news_text`  '.
		' FROM `' . TABLE_NEWS . '` ' .
		'WHERE `news_source_id` = "'.$source_id.'" AND `news_active` = "1" '.
		$rnd .
		'ORDER BY `news_date` DESC '.
		'LIMIT 0, '.$limit.';';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'База новостей отсутствует', '', __LINE__, __FILE__, $sql);
	};


$news = array();
while( $row = $db->sql_fetchrow($result) ) {
	$news[] = $row;
	};


$template->set_filenames(array(
	'body' => 'dynamic/news/dyn_news_list.tpl')
);

//$template->assign_vars(array(
//	'GALLERY_CLASS' => "highslide-gallery175"
//));


$template->assign_block_vars('news_source_'.$source_id, array());

$i = 0;
while ($news[$i]["news_id"]) {
	$news_text_short = substr($news[$i]["news_text"], 0, 400);
	$news_text = (strlen($news[$i]["news_text"])<300) ? $news[$i]["news_text"] : substr($news_text_short, 0, strrpos($news_text_short, " "));
	$news_text = strip_tags($news_text, '<a><br>');
	$news_img_size = explode(';', $news[$i]["news_img_size"]);
	$news_img = '<img src="'.$news[$i]["news_img"].'" alt="" width="'.$news_img_size[0].'" height="'.$news_img_size[1].'" border="0" />';
	$news_img_url = 
	$news_link = '<a href="/ru/news/'.$news[$i]["news_id"].'">';
	$news_full = (strlen($news[$i]["news_text"]) > strlen($news_text_short)) ? '&nbsp;'.$news_link.'далее...</a>' : "";

	$template->assign_block_vars('news_source_'.$source_id.'.newsrow', array(
		'NEWS_ID' => $news[$i]["news_id"],
		'NEWS_TITLE' => $news[$i]["news_name"],
		'NEWS_DATE' => create_date($board_config['news_dateformat'], $news[$i]["news_date"], $board_config['board_timezone']),
		'NEWS_TIME' => create_date("G:i", $news[0]["news_date"], $board_config['board_timezone']),
		'NEWS_TEXT' => $news_text,
		'NEWS_SOURCE' => $news[$i]["news_source"],
		'NEWS_IMG' => $news_img,
		'NEWS_FULL' => $news_full,
		'NEWS_VIDEO' => $news[$i]["news_video"],
		'EDIT' => @$edit
	));

	if ($news[$i]["news_img"]) {
		$template->assign_block_vars('news_source_'.$source_id.'.newsrow.newsrow_img', array());
	};
			
	if ($news_full) {
		$template->assign_block_vars('news_source_'.$source_id.'.newsrow.newsfull', array());
	};
			
	$i++;
};

/*
$i = 0;
while ($news_data[$i]["news_id"]) {
//	$gat_img = ($gallery_cat_data[$i]["cat_img"]) ? $gallery_path.$gallery_cat_data[$i]["cat_img"] : "/pic/ico/question_b.gif";
	$template->assign_block_vars('news_source_'.$source, array(
		'NEWS_DATE' => $news_data[$i]["news_date"],
		'NEWS_NAME' => $news_data[$i]["news_name"],
		'NEWS_TEXT' => $news_data[$i]["news_text"],
	));
	$i++;
};
*/



//
// Generate the page
//
$template->pparse('body');

?>