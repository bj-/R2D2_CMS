<?php
/***************************************************************************
 *                                vgbase.php
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


include($DRoot . '/mod/vg/vg_config.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);
//
// End session management
//

//
// �������� � ������� ������� ����������.
// 
$get_lang = substr($_GET['lang'],0, 3); // �������� id ����� �� 3 �������� ��� ������ � �����������

//
// �������� �������� �� �������
//

$id = intval($_GET["id"]);
//$action = substr($_GET["action"], 0, 32);
//$sdate = substr($_GET["sdate"], 0, 32);



//
// Start output of page
//

if ($id) {
	$sql = 'SELECT * FROM `'.TABLE_VG_SCAN_TMP.'` WHERE `user_id` = "'.$userdata["user_id"].'" AND `scan_id` = "'.$id. '";';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, '������ ������� ������ �� ��������� �������', '', __LINE__, __FILE__, $sql);
	};
	$scan_tmp = $db->sql_fetchrow($result);

	echo nl2br($scan_tmp["scan_src"]);
};





?>