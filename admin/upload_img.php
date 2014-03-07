<?php

/***************************************************************************
 *                                upload_img.php
 *                            -------------------
 *   begin                : November 10, 2010
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: upload_img.php,v 0.6 (alfa) 2010/11/16 17:17:40 $
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

// ��� ���������������� ������� �������:
//$_REQUEST['gallery_type'] = 'base';
//$_FILES['Filedata']['tmp_name'] = 'sdfsdfsdf.tmp';


$source_id = substr($_REQUEST['source_id'],0,11);
$page_id = substr($_REQUEST['page_id'],0, 255);
$gallery_id = intval(substr($_REQUEST['gallery_id'],0, 11));
$gallery_type = substr($_REQUEST['gallery_type'],0, 255);
$article_id = intval(substr($_REQUEST['article_id'],0,11));
$content_type = substr($_REQUEST['content_type'],0,15);
$news_source_id = intval(substr($_REQUEST['news_source_id'],0,11));

//print_r($_REQUEST);
//print_r($_FILES);
// ������������� ����� ������� � ��������
if (@$gallery_type == 'base') {
	if ($content_type == 'video') {
		$Gal_path = $gallery_video_path;
		$generate_preview = FALSE;
	}
	else {
		$Gal_path = $gallery_path;
		$generate_preview = TRUE;
	};
	$upload_original = TRUE;
	$to_db = TRUE;
	$gallery_dir = $gallery_id . "/";
	$tumb_width = $gallery_width;
	$tumb_height = $gallery_height;
}
elseif (@$gallery_type == 'mini') {
	$Gal_path = $miniGal_path;
	$upload_original = TRUE;
	$generate_preview = TRUE;
	$gallery_dir = '';
	$to_db = TRUE;
	$tumb_width = $miniGal_width;
	$tumb_height = $miniGal_height;
}
elseif (@$gallery_type == 'gallery_catimg') {
	$Gal_path = $gallery_path;
	$upload_original = FALSE;
	$generate_preview = TRUE;
	$to_db = TRUE;
	$gallery_dir = "";
	$tumb_width = $gallery_width;
	$tumb_height = $gallery_height;
}
elseif (@$gallery_type == 'oneimg') {
	// $source_id
	// 'news' - �������
	// 2 - 
	if ($source_id == "news") {
		
		$sql = 'SELECT `news_source_id` FROM `'.TABLE_NEWS.'` WHERE `news_id` = "'.$article_id.'" LIMIT 1;';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, '���� �������� �����������', '', __LINE__, __FILE__, $sql);
		};
		$row = $db->sql_fetchrow($result);

		$sql = 'SELECT * FROM `'.TABLE_NEWS.'` WHERE `news_source_id` = "'.$row["news_source_id"].'" AND `news_id` < 0 LIMIT 1;';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, '���� �������� �����������', '', __LINE__, __FILE__, $sql);
		};
		$news_settings = $db->sql_fetchrow($result);
		$news_settings["news_img_size_wh"] = explode(";", $news_settings["news_img_size"]);
//		print_r($news_settings);
		
		$sql_insert = "UPDATE `".TABLE_NEWS."` SET `news_img` = '%1', `news_img_size` = '%2'  WHERE `news_id` ='".$article_id."'; ";
		$Gal_path = $config["news"]["img_path"];
		$tumb_width = ($news_settings["news_img_size_wh"][0]) ? $news_settings["news_img_size_wh"][0] : $config["news"]["img_width"];
		$tumb_height = ($news_settings["news_img_size_wh"][1]) ? $news_settings["news_img_size_wh"][1] : $config["news"]["img_height"];

//		$tumb_width = ($config["news"]["img_src".$news_source_id."_width"]) ? $config["news"]["img_src".$news_source_id."_width"] : $config["news"]["img_width"];
//		$tumb_height = ($config["news"]["img_src".$news_source_id."_height"]) ? $config["news"]["img_src".$news_source_id."_height"] : $config["news"]["img_height"];
/*		
		$tumb_width = $newsImg_width;
		$tumb_height = $newsImg_height;
*/
	};
	$upload_original = FALSE;
	$generate_preview = TRUE;
	$to_db = TRUE;
	$gallery_dir = "";
}
else {
	$upload_original = TRUE;
	$generate_preview = FALSE;
	$gallery_dir = '';
	$to_db = FALSE;
};

//print_r($userdata);
if (!empty($_FILES) and ($userdata['user_level'] >0)) {

	/* ------------------------------------------
	*															*
	*		�������� � ��������� �����					*
	*															*
	-------------------------------------------- */
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath =  $DRoot . $Gal_path . @$gallery_dir;
	$targetPathTmb =  $DRoot . $Gal_path . @$gallery_dir . "sm/";

	$Uploaded_Complette = (file_exists($tempFile)) ? TRUE : FALSE; // ���� ���� ������ �� ������ ���� ���� �� ������������.

	$article_id = substr($_REQUEST['article_id'],0,11);
	
	// �������� ��������� ������� �� �������� �����, 
	$tFileName = substr($_FILES['Filedata']['name'], 0, strrpos($_FILES['Filedata']['name'], "."));
	$tFileExt = strtolower(substr($_FILES['Filedata']['name'], strrpos($_FILES['Filedata']['name'], ".")));
	$tFileName = str_remove_enemy_char($tFileName);
	$tFileName = (strlen($tFileName)) ? $tFileName : "_";
	$tFileName = $article_id . "_" . $tFileName;
	
	if (!in_array($tFileExt, $img_ext_list)) {
		message_die(GENERAL_ERROR, '������������ ���������� �����', $tFileExt, __LINE__, __FILE__, $sql);
		exit;
	};
	
	// �������� �� ������������ ����� � ���������� _X � ����� ����� ����� ���� ������� ��� ����.
	if (file_exists($targetPath . $tFileName.$tFileExt)) {
		$i = 2;
		while (file_exists($targetPath . $tFileName. "_" . $i . $tFileExt)) {
			$i++;
		};
		$tFileName = $tFileName . "_" . $i;
	}
	else {
		$tFileName = $tFileName;
	};
	

	$targetFileName = strtolower($tFileName . $tFileExt);
	$targetFile =  $targetPath . $targetFileName;

	$fileTypes  = str_replace("*." , "", $imgFileExt);
	$typesArray = split(';',$fileTypes);
	$fileParts  = pathinfo($targetFileName);

	if ($Uploaded_Complette) {
		if ($upload_original) {
			move_uploaded_file($tempFile,$targetFile);
			if ($content_type == 'video' and var_dump(extension_loaded('ffmpeg'))) {
				
				$movie = new ffmpeg_movie($targetFile);
				$duration = $movie->getDuration();
				$video_w = $movie->getFrameWidth(); // � ������ ������ � ��������
				$video_h = $movie->getPixelFormat(); // � ������ ������ � ��������
				$sql_size_img_big = $video_w . ";" . $video_h . ";" . $duration;
			}
			else {
				$size_img_big = getimagesize($targetFile); 
				$sql_size_img_big = $size_img_big[0].";".$size_img_big[1];
			};
		}
		else {
			$smallimage = $targetPath . $targetFileName;  
			if($is_resizing = resizeimg($tempFile, $smallimage, $tumb_width, $tumb_height)) {
				$size_img_small = getimagesize($targetPath . $targetFileName); 
				$sql_size_img_small = $size_img_small[0].";".$size_img_small[1];
				$generate_preview = FALSE;
			}
			else {
				message_die(GENERAL_ERROR, '������ ��� �������� ����������� ����� ����������� � ������� ���������� GDLib', '', __LINE__, __FILE__, $sql);
			};
		};
	};

	/* ------------------------------------------
	*                                           *
	*              �������� ������              *
	*                                           *
	------------------------------------------ */
	 if ($Uploaded_Complette and $generate_preview) {
	    $image = $targetPath.$targetFileName;
		$smallimage = $targetPathTmb . $targetFileName;  
		if (!file_exists($targetPathTmb)) {
			if (!mkdir($targetPathTmb, 0755, true)) {
				message_die(GENERAL_ERROR, '������ �������� ������� ��� ������ ������', '', __LINE__, __FILE__, $sql);
 			};
		};
//		$content_type == 'video'
		// 
		if(resizeimg($image, $smallimage, $tumb_width, $tumb_height)) {
			$size_img_small = getimagesize($targetPathTmb . $targetFileName); 
			$sql_size_img_small = $size_img_small[0].";".$size_img_small[1];
		}
		else {
			message_die(GENERAL_ERROR, '������ ��� �������� ����������� ����� ����������� � ������� ���������� GDLib', '', __LINE__, __FILE__, $sql);
		};
	};

	/* ------------------------------------------
	*                                           *
	*            �������� � ����                *
	*                                           *
	------------------------------------------ */
	if ($to_db) {
		if (@$gallery_type == 'base') {
			// ������� ���������� ���������� � �������, ����� � ���������� ������� +1
			$gallery_sort = ($content_type == 'video') ? " and `video_path` is NOT NULL" : " and `video_path` is NULL";
			$sql_max_srt = 'SELECT MAX(`img_sort`) as `max_srt` FROM `'.TABLE_GALLERY_IMG.'` where `cat_id` = "'.$gallery_id.'"' .$gallery_sort.';';
			$sql_max_srt_go = TRUE;
		}
		ElseIf (@$gallery_type == 'mini') {
			// ������� ���������� ���������� � �������, ����� � ���������� ������� +1
			$sql_max_srt = 'SELECT MAX(`img_sort`) as `max_srt` FROM `'.TABLE_ARTILE_GALLERY.'` where `article_id` = "'.$article_id.'" AND `source_id`="'.$source_id.'";';
			$sql_max_srt_go = TRUE;
		};
		if (@$sql_max_srt_go) {
			if ( !($result = $db->sql_query($sql_max_srt)) ) {
				message_die(GENERAL_ERROR, '������ ��������� ���-�� ���������� � ���������', '', __LINE__, __FILE__, $sql);
			};
			$sm_gal_data = $db->sql_fetchrow($result);
			$sm_gal_sort = $sm_gal_data["max_srt"]+1;
		};
		
		if (@$gallery_type == 'base') {
			if ($content_type == 'video') {
				$sql_insert = "INSERT INTO `".TABLE_GALLERY_IMG."` ".
								" (`cat_id`, `img_sort` , `img_name`, `img_desc`, `video_path`, `img_size`,  `simg_size`) ".
								" VALUES ('".$gallery_id."', '".$sm_gal_sort."', NULL, NULL, '".$targetFileName."', '".
											@$sql_size_img_big."', '".@$sql_size_img_small."');";
			}
			else {
				$sql_insert = "INSERT INTO `".TABLE_GALLERY_IMG."` ".
								" (`cat_id`, `img_sort` , `img_name`, `img_desc`, `img_path`, `img_size`,  `simg_size`) ".
								" VALUES ('".$gallery_id."', '".$sm_gal_sort."', NULL, NULL, '".$targetFileName."', '".
											$sql_size_img_big."', '".$sql_size_img_small."');";
			};
			$sql_insert_go = TRUE;
		}
		elseif (@$gallery_type == 'mini') {
			$sql_insert = "INSERT INTO `".TABLE_ARTILE_GALLERY."` ".
							"(`article_id`, `img_sort` , `img_path`, `source_id`) ".
							" VALUES ('".$article_id."', '".$sm_gal_sort."', '".$targetFileName."', '".$source_id."');";
			$sql_insert_go = TRUE;
		}
		elseif (@$gallery_type == 'gallery_catimg') {
			$sql_insert = "UPDATE `".TABLE_GALLERY_CAT."` ".
							" SET `cat_img` = '".$targetFileName."'  WHERE `cat_id` ='".$gallery_id."'; ";
			$sql_insert_go = TRUE;
		}
		elseif (@$gallery_type == 'oneimg') {
			$sql_insert = str_replace("%2", $smallimage, $sql_insert);
			$sql_insert = str_replace("%2", $size_img_small, $sql_insert);
		};
		// ������� ���������� � ����� � ��
		if (@$sql_insert_go) {
			if ( !($result = $db->sql_query($sql_insert)) ) {
				message_die(GENERAL_ERROR, '������ ��������� ���������� �� � �����������', '', __LINE__, __FILE__, $sql);
			};
		};
	};

	// ���������� ���� � ����������� ���������� ��� ������������� ���������� �������
	echo $Gal_path . $targetFileName;

	}
else {
	$add = $targetFileName . " admin_mode:[". $userdata['user_level'] ."]";
	message_die(GENERAL_ERROR, 'Income file is not present or no Right' . $add, $add, __LINE__, __FILE__, $sql);
};


// ���������� ����
if (@$debug_mode) {
//$fwrt = "";
$fwrt .= "tempFile=" . $tempFile . "\n";
$fwrt .= "file_exists=" . $tfe . "\n";
$fwrt .= "sql_max_srt=" . $sql_max_srt . "\n";
$fwrt .= "sql_insert=" . $sql_insert . "\n";
$fwrt .= "tFileExt=" . $tFileExt . "\n";
$fwrt .= "targetFile=" . $targetFile . "\n";
$fwrt .= "_REQUEST['folder']=" . $_REQUEST['folder']  . "\n";
$fwrt .= "_REQUEST['article_id']=" . $_REQUEST['article_id']  . "\n";
$fwrt .= "_REQUEST['source_id']=" . $_REQUEST['source_id']  . "\n";
$fwrt .= "_REQUEST['gallery_type']=" . $_REQUEST['gallery_type']  . "\n";
$fwrt .= "_REQUEST['gallery_id']=" . $_REQUEST['gallery_id']  . "\n";

$fp = fopen($DRoot . "/upload_img_log.txt", "a");
fwrite($fp, $fwrt);
fclose($fp);
};

?>