<?php
if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

// путь к админской панели.
$admin_patch = "/admin";

$site_url = 'http://bj.taz.ru';
$table_prefix = 'etalon_';

// database information
$database['name'] = 'mysql';
$database['host'] = 'localhost';
//$database['database'] = 'comfs';
$database['database'] = 'bj_bj';
//$database['user'] = 'comfortn_cs';
$database['password'] = 'Zorg1';
//$database['database'] = 'comfortn_mguard';
//$database['user'] = 'root';
//$database['password'] = '';


// замещение переменных (для девелопмента)
$fakecfg =  substr($_SERVER["DOCUMENT_ROOT"], 0, strpos($_SERVER["DOCUMENT_ROOT"],"www")) . "db.php";
if (is_file($fakecfg)) {
	include $fakecfg;
	$table_prefix = $site_table_prefix;
	$database['database'] = $site_db;
};

// совместимость с phpbb движком
$dbms = $database['name'];

$dbhost = $database['host'];
$dbname = $database['database'];
$dbuser = $database['user'];
$dbpasswd = $database['password'];

$phpEx = "php";

//
// Настройки загрузки фото-видио
//
// минигалерея статьи
$miniGal_path = "/img/smgal/";				// Путь к минигалерее
$miniGal_width = 133;						// размер создаваемой превьюшки
$miniGal_height = 100;
$miniGal_item_per_page = 20;
// Базовая галерея
$gallery_path = "/img/gallery/";			// Путь к основной галерее
$gallery_video_path = "/video/gallery/";
$gallery_width = 175;						// размер создаваемой превьюшки
$gallery_height = 131;
$gallery_item_per_page = 5;
// новости
$config["news"]["img_path"] = "/img/news/";	// Путь к картинкам новостей
$config["news"]["img_width"] = 150;				// размер создаваемой превьюшки (дефолтный для всех разделов новостных)
$config["news"]["img_height"] = 113;
$config["news"]["img_src3_width"] = 202;		// персональные настройки для определенных разделов.
$config["news"]["img_src3_height"] = 175;
$newsImg_path = "/img/news/";				
$newsImg_width = 150;						
$newsImg_height = 113;

//$upload_img_path[0] = "/img/events/";				// Путь к 
$upload_img_path[0] = "/img/news/";				// Путь к загружаемым
// допустимые типы файлов для загружаемых картинок
$imgFileExt = '*.jpg;*.png;*.gif';		// формат *.jpg;*.png; и т.д.
$imgFileDesc = '*.jpg;*.png;*.gif';		// отображеется в диалоге "Browse..." в допустимых типах файлов. указывать можно в любом виде

$img_ext_list = array(".jpg", ".gif", ".png", ".jpeg", ".flv");	// допустимые расширения для загружаемых файлов.

$starttime = 0;

//
// Прочие настройки
//
$config["paragraf_title_sep"] = ' - '; // Разделитель для строчки с названием текущего раздела

// хостозависимые настройки
$localhost = false;
if ($_SERVER["HTTP_HOST"] == 'deputat') {
	$DRoot = $_SERVER["DOCUMENT_ROOT"]; // локально
//	$localhost = true;
	}
Else {
 	$DRoot = $_SERVER["DOCUMENT_ROOT"] ; // на сервере
};

// Администрирование
$adm = false;
if (strpos(substr($_SERVER["REQUEST_URI"],0,6), 'admin')) {
	$adm = True;
//	$localhost = true;
	}
else {
 	$adm = false;
};


// включение дополнительных модов: раскоментируйте или укажите TRUE/FALSE в соответствующей строке
//$mod_news = "last";
$mod_article_list = TRUE;
$mod_blocks = TRUE;
$mod_cruch_calendar = FALSE;

// for debug
$debug_mode = TRUE;
//$debug_mode = FALSE;

$search_text = "Яндекс: поиск...";
?>