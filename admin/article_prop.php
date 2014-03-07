<?php
/***************************************************************************
 *                                article_prop.php
 *                            -------------------
 *   begin                : Sep 20, 2010
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: article_prop.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
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
include("../includes/config.php");
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

// ��������� ����� ������������. ���� ��� - ����������
if ($userdata['user_level'] < 1) {
	message_die(GENERAL_ERROR, '������������ �� ����� ����', '', __LINE__, __FILE__, $sql);
	exit;
};

// �������� ����������
$get_article = intval(substr($_GET['id'],0,11));

//����� ��� ����������� ������, ���� � ��������� ������� - ��� ��������
$article_prop = TRUE;

// ������ ����������

// ��������� � ��������� ������� ���������� �� ����������� � �� ������ ����� ����������
if ($_POST['save'] and ($userdata['user_level'] >0)) {
	$save_page_title = mysql_real_escape_string(substr($_POST['page_title'],0,240));
	$save_page_desc = mysql_real_escape_string(substr($_POST['page_desc'],0,240));
	$save_page_keywords = mysql_real_escape_string(substr($_POST['page_keywords'],0,240));
	$save_paragraf_id = intval(substr($_POST['paragraf_id'],0,11));
	$save_article_primary = intval(substr($_POST['article_primary'],0,3));
	$save_page_path = str_remove_enemy_char(substr($_POST['page_path'],0,240));
	$save_page_text = mysql_real_escape_string($_POST['page_text']);
	$save_form_email = mysql_real_escape_string(substr($_POST['form_email'],0,240));
	$save_form_subject = mysql_real_escape_string(substr($_POST['form_subject'],0,240));
	$current_article_id = intval(substr($_POST['article_id'],0,11));
	$save_form_id = intval(substr($_POST['form_id'],0,11));
	
	if($_POST['date_sel'] == "current_date") {
		$article_date = time();
		}
	else {
		// ��������� ���� � ����� ������
		$article_date = substr($_POST['page_date'], 0 , 10);
		$article_date_arr = explode(".", $article_date);
		$article_date = mktime(0, 0, 0, $article_date_arr[1], $article_date_arr[0], $article_date_arr[2]);
	};

};

// ��������� ����������������� ������
if ($_POST['save'] and $_POST['new'] and ($userdata['user_level'] >0)) { 

	// �������� ��������� ID ������
	$sql = "SELECT MAX(`article_id`) AS `max_article_id` FROM `" . TABLE_ARTICLE . "`";

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, '������ ��������� ������������� ID ������', '', __LINE__, __FILE__, $sql);
		};
	$new_article_id_data = array();
	$new_article_id_data = $db->sql_fetchrow($result);
	$new_article_id = $new_article_id_data["max_article_id"]+1;
	$get_article = $new_article_id;
	
	$sql = 'INSERT INTO `' . TABLE_ARTICLE . '` '.
			'(`article_id`, `article_title` , `article_desc` , `article_classification` , `article_keywords` , `paragraf_id` , `primary_article` , '.
			'`article_name` , `article_text` , `article_date` , `article_status` , `article_vote_id` , `article_lang`, '.
			'`form_email`, `form_subject`, `form_id` '.
			') '.
			"VALUES ('".$new_article_id."', '".$save_page_title."', '".$save_page_desc."', '', '".$save_page_keywords."', '".$save_paragraf_id."', '".$save_article_primary.
			"', '".$save_page_path."', '".$save_page_text."', '".$news_date."', '1', '', '1', ".
			"'".$save_form_email."', '".$save_form_subject."', '".$save_form_id."'".
			" );";

	$current_paragraf_id = $save_paragraf_id;
	$current_article_id = FALSE;
	

	if ($db->sql_query($sql)) {
		$saved = '<strong>' . $lang['Saved'] . '</strong>';
	 	if ($_GET['adm']) {
			redirect("/admin/index.php?edit=articles");
			exit;
		};
	}
	else {
		message_die(GENERAL_ERROR, '������ ���������� ������', '', __LINE__, __FILE__, $sql);
	};
}
elseif ($_POST['save'] and ($userdata['user_level'] >0)) {
	$sql = 
	"UPDATE `" . TABLE_ARTICLE . "` SET ".
	"`article_title` = '".$save_page_title."', ".
	"`article_desc` = '".$save_page_desc."', ".
	"`article_classification` =  '', ".
	"`article_keywords` = '".$save_page_keywords."', ".
	"`paragraf_id` = '".$save_paragraf_id."', ".
	"`primary_article` =  '".$save_article_primary."', ".
	"`article_name` = '".$save_page_path."', ".
	"`article_text` = '".$save_page_text."', ".
	"`article_date` =  '".$article_date."', ".
	"`article_status` =  '1', ".
	"`article_vote_id` =  '', ".
	"`article_lang` =  '1', ".
	"`form_id` = '".$save_form_id."'".
	", `form_email` =  '".$save_form_email."' ".
	", `form_subject` =  '".$save_form_subject."' ".
	"WHERE `article_id` = '" . substr($_POST['article_id'],0, 11) ."'";
//echo "<!--" . $sql . "-->";
	if ($db->sql_query($sql)) {
		$saved = '<strong>' . $lang['Saved'] . '</strong>';
	}
	else {
		message_die(GENERAL_ERROR, '������ ���������� ������', '', __LINE__, __FILE__, $sql);
	};
}
elseif ($_POST["video_add"] and ($userdata['user_level'] >0)) {

	$video_id = mysql_real_escape_string(substr($_POST['video_id'], 0, 250));
	$video_thumb = mysql_real_escape_string(substr($_POST['video_thumb'], 0, 250));
	$video_prov = mysql_real_escape_string(substr($_POST['video_prov'], 0, 30));

	// ������ �� �����, ���������� � ����������
	if ($_POST['video_type'] == "parsed" and $_POST['video_prov'] == "youtube") {
		$video_code = '<iframe width="560" height="345" src="http://www.youtube.com/embed/'.$video_id.'" frameborder="0" allowfullscreen></iframe>';
		
	}
	else {
		$video_code = $_POST['video_code'];
	};
		$video_desc = mysql_real_escape_string(substr($_POST['video_desc'],0,254));
		$video_name = mysql_real_escape_string(substr($_POST['video_name'],0,254));
		$video_size = ($_POST['video_size']) ? "'".mysql_real_escape_string(substr($_POST['video_size'],0,254))."'" : 'NULL';
		

	$sql_max_srt = 'SELECT MAX(`img_sort`) as `max_srt` FROM `'.TABLE_ARTILE_GALLERY.'` where `article_id` = "'.$get_article.'" and `video_thumb` IS NOT NULL;';
	if ( !($result = $db->sql_query($sql_max_srt)) ) {
		message_die(GENERAL_ERROR, '������ ��������� ���-�� ���������� � ���������', '', __LINE__, __FILE__, $sql);
	};
	$sm_gal_data = $db->sql_fetchrow($result);
	$sm_gal_sort = $sm_gal_data["max_srt"]+1;
	
	$sql_insert = "INSERT INTO `".TABLE_ARTILE_GALLERY."` ".
					" (`article_id`, `source_id`, `img_sort` , `img_name`, `img_desc`, `video_path`, `video_id`, `video_src`, `video_thumb`, `simg_size`) ".
					" VALUES ('".$get_article."', '1', '".$sm_gal_sort."', '".$video_name."', '".$video_desc."', '".$video_code."', '".$video_id."', '".$video_prov."', '".$video_thumb."', ".$video_size.");";
					
	if ( !($result = $db->sql_query($sql_insert)) ) {
		message_die(GENERAL_ERROR, '������ ��������� ����������� � �������', '', __LINE__, __FILE__, $sql);
	};
	

	
	
	
	
/*	
	$video_code = substr($_POST["video_code"],0,15000);
	$video_desc = substr($_POST["video_desc"],0,255);
	$video_name = substr($_POST["video_name"],0,255);
//	echo $_POST["video_code"] . $_POST["video_desc"] . $_POST["video_name"];
	
		$sql_max_srt = 'SELECT MAX(`img_sort`) as `max_srt` FROM `'.TABLE_ARTILE_GALLERY.'` where `article_id` = "'.$get_article.'" and `source_id` = "'.$sgal_source_id.'" and `video_path` is NOT NULL;';
	if ( !($result = $db->sql_query($sql_max_srt)) ) {
		message_die(GENERAL_ERROR, '������ ��������� ���-�� ���������� � ���������', '', __LINE__, __FILE__, $sql);
	};
	$sm_gal_data = $db->sql_fetchrow($result);
	$sm_gal_sort = $sm_gal_data["max_srt"]+1;
	
echo	$sql_insert = "INSERT INTO `".TABLE_ARTILE_GALLERY."` ".
					" (`article_id`, `source_id` , `img_sort` , `img_name` , `img_desc` , `video_path`) ".
					" VALUES ('".$get_article."', '".$sgal_source_id."', '".$sm_gal_sort."', '".$video_name."', '".$video_desc."', '".$video_code."');";
//	if ( !($result = $db->sql_query($sql_insert)) ) {
//		message_die(GENERAL_ERROR, '������ ��������� ����������� � �������', '', __LINE__, __FILE__, $sql);
//	};
*/
};


// ����� ����������




define('SHOW_ONLINE', true);
//
// ���������/���������� ������� �� �������� ��������.
//
if ($_GET['sgallery'] or $_GET['svgallery']) { // �������
	include $DRoot . "/admin/includes/module_swich.php";
}

$sql = 'SELECT * '.
		'FROM `' . TABLE_ARTICLE . '` AS `a` '.
		'JOIN `' . TABLE_FORMS . '` AS `f` ON `f`.`form_id` = `a`.`form_id` '.
		'WHERE `article_id` = "'.$get_article.'"';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, '������ �����������', '', __LINE__, __FILE__, $sql);
	};
$article_data = array();
$article_data = $db->sql_fetchrow($result);

if (!$article_data["article_id"]) {
	$no_article = TRUE;
	message_die(GENERAL_ERROR, '����������� ������ �� ������� ' . $add, '', __LINE__, __FILE__, $sql);
};
/*
// �������� ID ����� ���� ��� ���� � ������
if($article_data["form_id"]) {
	$sql = 'SELECT * '.
		'FROM `' . TABLE_FORMS . '` '.
		'WHERE `form_id` = "'.$article_data["form_id"].'"';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, '������ �����������', '', __LINE__, __FILE__, $sql);
		};

	$form_name_data = $db->sql_fetchrow($result);
};
*/
// �������� � ���������� ������ � ������
$page_title = $article_data["article_title"];
$page_classification = $article_data["article_classification"];
$page_desc = $article_data["article_desc"];
$page_keywords = $article_data["article_keywords"];
$page_content_direction = "";

$page_paragraf = $article_data["paragraf_id"];

//$submit_path = "/article.php?article=".$article_data[0]["article_id"];
$page_id = $article_data["article_id"];
$page_unix_date = $article_data["article_date"];
$page_date = create_date($board_config['article_dateformat'], $article_data["article_date"], $board_config['board_timezone']);
$primary_article = $article_data["primary_article"];

$page_form_email = $article_data["form_email"];
$page_form_subj = $article_data["form_subject"];
$page_form_onoff = ($article_data["form_email"]) ? '<font style="color:green">����������</font>' : '<font style="color:red">���������</font>';

$page_path = $article_data["article_name"];
$page_text = (@$no_article) ? "<p><h2>����������� ������ �� �������</h2></p><p>������ �������������� ����� ��� �������� �������������, ������� �� ������.</p>" : $article_data["article_text"];

$smGal_status = $article_data["article_sgal_on"];
$smgal_onoff = ($smGal_status) ? '<font style="color:green">����������</font>' : '<font style="color:red">���������</font>';
$smVideoGal_status = $article_data["article_svgal_on"];
$smVideoGal_onoff = ($smVideoGal_status) ? '<font style="color:green">����������</font>' : '<font style="color:red">���������</font>';


$article_prop = TRUE; //����� ��� ����������� ������ ����� ���� ��������

$article_form_name = ($article_data["form_name"]) ? $article_data["form_name"] : "���";

if ($primary_article) {
	$article_path = ($page_paragraf) ? "/".$url_lang."/".get_full_url($page_paragraf) . "/" : "http://" . $board_config["server_name"] . "/";
}
else {
	$article_path = "/".$url_lang."/".get_full_url($page_paragraf) . "/" . $page_path . "-" . $page_id . ".html";
}

include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'admin/article_prop_body.tpl')
);


//
// ����� �������� ����������
//
if ($_GET['sgallery'] or $_GET['svgallery']) { // �������
	include $DRoot . "/admin/includes/sgallery.php";
}
else {
	include $DRoot . "/admin/includes/article_prop_list.php";
};
// �������� ������ ��� ����������� ��������� �������� ����� ���������� ���������������� ��������
if ($show_prop) {
	include $DRoot . "/admin/includes/article_prop_list.php";
};

$template->assign_vars(array(
	'FORM_ONOFF' => $page_form_onoff,
	'ARTICLE_PATH' => $article_path,

));




//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>