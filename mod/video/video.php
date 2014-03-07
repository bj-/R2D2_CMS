<?php
/***************************************************************************
 *                                article.php
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
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);
//
// End session management
//

//
// собираем страницу со статьей
//
$get_article = $_GET['id'];
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами
$article_sgal = True;


//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = (@$no_article) ? "Статья не найдена" : $article_data[0]["article_title"];
$page_classification = $article_data[0]["article_classification"];
$page_desc = $article_data[0]["article_desc"];
$page_keywords = $article_data[0]["article_keywords"];
$page_content_direction = "";

$page_paragraf_id = $article_data[0]["paragraf_id"];
$page_paragraf_name_text = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_name'];
$page_paragraf_name = $paragraf_title_patch; //$topmenu_data[$article_data[0]["paragraf_id"]]['menu_name'];
$page_paragraf_path = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_path'];
$page_paragraf_title_patch = $paragraf_title_patch;

$submit_path = "/article.php?article=".$article_data[0]["article_id"];
$page_id = $article_data[0]["article_id"];
$page_unix_date = $article_data[0]["article_date"];
$page_date = create_date($board_config['article_dateformat'], $article_data[0]["article_date"], $board_config['board_timezone']);
$check_primary_article = ($article_data[0]["primary_article"]) ? " checked" : "";

$page_form_email = $article_data[0]["form_email"];
$page_form_subj = $article_data[0]["form_subject"];

$page_path = $article_data[0]["article_name"];
$page_text = (@$no_article) ? "<p><h2>Запрошенная статья не найдена</h2></p><p>Жалоба администратору сайта уже написана автоматически, спасибо за помощь.</p>" : $article_data[0]["article_text"];

$smGal_status = $article_data[0]["article_sgal_on"];


include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'article_body.tpl')
);


$url = 'http://gdata.youtube.com/feeds/api/videos?vq==kAzeiMKVufM';       //адрес XML документа

$xml_file = file_get_contents($url);

preg_match_all("/(<media:thumbnail )(.*?)(\/\>)/", $xml_file,$video_thumb_res);
$video_data_arr = explode(" ", $video_thumb_res[2][0]);
// превью
$video_data_arr[0] = substr($video_data_arr[0], (strpos($video_data_arr[0], "=")+2));
$video_thumb = substr($video_data_arr[0], 0, (strlen($video_data_arr[0])-1));
// размеры
$video_h = preg_replace('/[^0-9]+/', '', $video_data_arr[1]);
$video_w = preg_replace('/[^0-9]+/', '', $video_data_arr[2]);

//preg_match("/([0-9])/", $video_data_arr[1], $res);
//print_r($res);
$xml= simplexml_load_string($xml_file); 
//foreach ($xml as $item) {
	$video_title = iconv("UTF-8", "windows-1251", $xml->entry->title); 
	$video_content = iconv("UTF-8", "windows-1251", $xml->entry->content); 
	$video_author =  iconv("UTF-8", "windows-1251", $xml->entry->author);
//};

//echo "==".$xml->entry->title."==<br>";

echo 
$video_thumb . "<br>".
$video_title . "<br>".
$video_content . "<br>".
$video_h . "<br>".
$video_w . "<br>";

//print_r($video_thimb[2]);

echo "<pre>";
print_r($xml);
echo "</pre>";


/*
set_include_path($DRoot."/script/");
include $DRoot . "/script/Zend/Gdata/YouTube/VideoEntry.php";
//include $DRoot . "/script/Zend/Gdata/YouTube.php";

function getAndPrintVideoFeed($location = Zend_Gdata_YouTube::VIDEO_URI)
{
  $yt = new Zend_Gdata_YouTube();
	$yt->getVideoEntry('YOO4moRIlGo');
//  $videoFeed = $yt->getVideoFeed($location);
//  foreach ($videoFeed as $videoEntry) {
//    printVideoEntry($videoEntry); // this function is documented fully here
//  }
}

$video = "http://www.youtube.com/watch?v=YOO4moRIlGo";
getAndPrintVideoFeed($video);

//$yt->getVideoEntry('the0KZLEacs');
*/
//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>