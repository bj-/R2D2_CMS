<?php
/***************************************************************************
 *                                ads_config.php
 *                            -------------------
 *   begin                : Feb 25, 2011
 *   copyright            : (C) 2010-2011 The R2D2 Group
 *
 *   $Id: vg_config.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   License
 *
 ***************************************************************************/

// ������������
if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
};

/*
//������������ ������� ����� �������������� � ������� ���
$vg_privilegies["vg_admin_groups"] = array (2); 	// userlevel
$vg_privilegies["vg_admin_users"] = array ("all"); // ���������� �� ����������� ������������ (all - ��� �����������)

// ��������� ���� ��������.
$vg_privilegies["vg_editors_groups"] = array (2, 11);
$vg_privilegies["vg_user_groups"] = array ("all"); 	// userlevel

// ���������� ��������� �������� ��� ��� ����.
$predefinded_var = array("[color:red]��� ��������[/color]" => 1);
*/


// ��������� ������
define('TABLE_VG_CONFIG', $table_prefix.'vg_config');	// 

define('TABLE_ADS_BANNER', $table_prefix.'ads_banners');	// 
define('TABLE_ADS_CAT', $table_prefix.'ads_category');				// 


// ������ ��������� �������
function ads_config() {
/*	global $db;

	$sql = 'SELECT * FROM `' . TABLE_VG_CONFIG . '`';
	
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, '������ ������� � ������� ���� ������', '', __LINE__, __FILE__, $sql);
		};

	$vg_config = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$vg_config[$row["vg_config_name"]] =  $row["vg_config_value"];

	};
	return $vg_config;
	*/
};

?>