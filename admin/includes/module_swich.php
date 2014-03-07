<?php
// включение/отключение модулей

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

// включить выключить галерею)
//echo $_GET['svgallery'];
if ($_GET['sgallery'] == 'on' or $_GET['svgallery'] == 'on') {
	if (@$event_prop) {
		if ($_GET['sgallery'] == 'on') {
			$gal_type_set = 'event_foto';
		}
		ElseIf ($_GET['svgallery'] == 'on'){
			$gal_type_set = 'event_video';
		};
		$sql = "UPDATE `" . TABLE_EVENTS . "` SET `".$gal_type_set."` =  '1' WHERE `event_id` = '" . $get_id ."';";
	}
	ElseIf (@$article_prop) {
		if ($_GET['sgallery'] == 'on') {
			$gal_type_set = 'article_sgal_on';
		}
		ElseIf ($_GET['svgallery'] == 'on'){
			$gal_type_set = 'article_svgal_on';
		};
		$sql = "UPDATE `" . TABLE_ARTICLE . "` SET `".$gal_type_set."` =  '1' WHERE `article_id` = '" . $get_article ."';";
	};
	if ($_GET['sgallery'] == 'on') {
		$smGal_status = 1;
		$show_prop = TRUE;
		$smGal_onoff = TRUE;
	}
	ElseIf ($_GET['svgallery'] == 'on'){
		$smVideoGal_status = 1;
		$show_prop = TRUE;
		$smVideoGal_onoff = TRUE;
	};
}
ElseIf ($_GET['sgallery'] == 'off' or $_GET['svgallery'] == 'off') {
	if (@$event_prop) {
		if ($_GET['sgallery'] == 'off') {
			$gal_type_set = 'event_foto';
		}
		ElseIf ($_GET['svgallery'] == 'off'){
			$gal_type_set = 'event_video';
		};
		$sql = "UPDATE `" . TABLE_EVENTS . "` SET `".$gal_type_set."` =  NULL WHERE `event_id` = '" . $get_id ."';";
	}
	ElseIf (@$article_prop) {
		if ($_GET['sgallery'] == 'off') {
			$gal_type_set = 'article_sgal_on';
		}
		ElseIf ($_GET['svgallery'] == 'off'){
			$gal_type_set = 'article_svgal_on';
		};
		$sql = "UPDATE `" . TABLE_ARTICLE . "` SET `".$gal_type_set."` =  NULL WHERE `article_id` = '" . $get_article ."'";
	};
	if ($_GET['sgallery'] == 'off') {
		$smGal_status = 0;
		$show_prop = TRUE;
		$smGal_onoff = TRUE;
	}
	ElseIf ($_GET['svgallery'] == 'off'){
		$smVideoGal_status = 0;
		$show_prop = TRUE;
		$smVideoGal_onoff = TRUE;
	};
};
if (@$smGal_onoff or @$smVideoGal_onoff) {
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'Ошибка включения/отключения галереи', '', __LINE__, __FILE__, $sql);
	};
};


?>
