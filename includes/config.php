<?php
if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

// ���� � ��������� ������.
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


// ��������� ���������� (��� ������������)
$fakecfg =  substr($_SERVER["DOCUMENT_ROOT"], 0, strpos($_SERVER["DOCUMENT_ROOT"],"www")) . "db.php";
if (is_file($fakecfg)) {
	include $fakecfg;
	$table_prefix = $site_table_prefix;
	$database['database'] = $site_db;
};

// ������������� � phpbb �������
$dbms = $database['name'];

$dbhost = $database['host'];
$dbname = $database['database'];
$dbuser = $database['user'];
$dbpasswd = $database['password'];

$phpEx = "php";

//
// ��������� �������� ����-�����
//
// ����������� ������
$miniGal_path = "/img/smgal/";				// ���� � �����������
$miniGal_width = 133;						// ������ ����������� ���������
$miniGal_height = 100;
$miniGal_item_per_page = 20;
// ������� �������
$gallery_path = "/img/gallery/";			// ���� � �������� �������
$gallery_video_path = "/video/gallery/";
$gallery_width = 175;						// ������ ����������� ���������
$gallery_height = 131;
$gallery_item_per_page = 5;
// �������
$config["news"]["img_path"] = "/img/news/";	// ���� � ��������� ��������
$config["news"]["img_width"] = 150;				// ������ ����������� ��������� (��������� ��� ���� �������� ���������)
$config["news"]["img_height"] = 113;
$config["news"]["img_src3_width"] = 202;		// ������������ ��������� ��� ������������ ��������.
$config["news"]["img_src3_height"] = 175;
$newsImg_path = "/img/news/";				
$newsImg_width = 150;						
$newsImg_height = 113;

//$upload_img_path[0] = "/img/events/";				// ���� � 
$upload_img_path[0] = "/img/news/";				// ���� � �����������
// ���������� ���� ������ ��� ����������� ��������
$imgFileExt = '*.jpg;*.png;*.gif';		// ������ *.jpg;*.png; � �.�.
$imgFileDesc = '*.jpg;*.png;*.gif';		// ������������ � ������� "Browse..." � ���������� ����� ������. ��������� ����� � ����� ����

$img_ext_list = array(".jpg", ".gif", ".png", ".jpeg", ".flv");	// ���������� ���������� ��� ����������� ������.

$starttime = 0;

//
// ������ ���������
//
$config["paragraf_title_sep"] = ' - '; // ����������� ��� ������� � ��������� �������� �������

// �������������� ���������
$localhost = false;
if ($_SERVER["HTTP_HOST"] == 'deputat') {
	$DRoot = $_SERVER["DOCUMENT_ROOT"]; // ��������
//	$localhost = true;
	}
Else {
 	$DRoot = $_SERVER["DOCUMENT_ROOT"] ; // �� �������
};

// �����������������
$adm = false;
if (strpos(substr($_SERVER["REQUEST_URI"],0,6), 'admin')) {
	$adm = True;
//	$localhost = true;
	}
else {
 	$adm = false;
};


// ��������� �������������� �����: ��������������� ��� ������� TRUE/FALSE � ��������������� ������
//$mod_news = "last";
$mod_article_list = TRUE;
$mod_blocks = TRUE;
$mod_cruch_calendar = FALSE;

// for debug
$debug_mode = TRUE;
//$debug_mode = FALSE;

$search_text = "������: �����...";
?>