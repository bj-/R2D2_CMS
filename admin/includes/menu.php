<?php

if ( !defined('IN_R2D2') or $userdata['user_level'] < 1)
{
	die("Hacking attempt");
}

$id = intval(substr($_GET["menu_id"],0,11));

$post_menu_id = 	intval(substr($_POST["menu_id"],0,11));
$post_menu_name = ($_POST["menu_name"]) ? substr($_POST["menu_name"],0,255) : "��� ��������";
$post_menu_pid = ($_POST["menu_pid"]) ? intval(substr($_POST["menu_pid"],0,3)) : "0";
$post_menu_group = ($_POST["menu_group"]) ? intval(substr($_POST["menu_group"],0,3)) : "0";
$post_sortorder = ($_POST["sortorder"]) ? intval(substr($_POST["sortorder"],0,11)) : "0";
$post_menu_path = (substr($_POST["menu_path"],0,255)) ? substr($_POST["menu_path"],0,255) : "menu".mktime();
$post_menu_desc = (substr($_POST["menu_desc"],0,255)) ? substr($_POST["menu_desc"],0,255) : "";
$post_link_type = ($_POST["link_type"]) ? intval(substr($_POST["link_type"],0,3)) : "";
$post_menu_img = 	($_POST["menu_img"]) ? substr($_POST["menu_img"],0,255) : "";
$post_menu_class = 	($_POST["menu_class"]) ? substr($_POST["menu_class"],0,255) : "";



$menu_group_list = array(
								0=>"������� ����", 
								1=>"���� �����", 
								2=>"���� ������", 
								3=>"������ ����", 
								90=>"����������� ������", 
								99=>"����������� ������", 
								);
$menu_type_list = array(
								0=>"��������", 
								1=>'������� target="_blank"', 
								2=>"�������", 
								3=>"���� ��� ������", 
								);
								
//
// ������ �������� ����.
//
function admin_buildtopmenu_table ($pid, $menu_desc, $lvl, $m_group) {
	global $topmenu_pids, $topmenu_data, $url_lang, $localhost, $blank_tree_top_menu, $template, $theme, $menu_group_list, $menu_type_list;
	$menu_top_first = (@$menu_top_first) ? $menu_top_first = $menu_top_first : $menu_top_first = 1; // ����� ��� ������������ ���� 1 - ��� ��� ���� � ������ ���� � �������, 2 - ��� ��� ���� (��������� ��� ���������� ��� ��������� ������������ � ��.)
	$menu_top_first = (@$blank_tree_top_menu) ? 0 : $menu_top_first; // ���� ���� ������� ���� ������ �������.
	$i = 0;
	$ret = "";
	while ($topmenu_pids[$pid][$i]) {
		$c_id = $topmenu_pids[$pid][$i];								// ����� �������� ���������� ��������� (�������� ����)
		
		if ($topmenu_data[$c_id]['link_type'] == 3) {
			$url = '';
			$menu_name = $topmenu_data[$c_id]["menu_name"];
			}
		else {
			$url = "/" .$url_lang."/".get_full_url($c_id);
			$menu_name = $topmenu_data[$c_id]["menu_name"];
//			$menu_name = '<a href="'.$url.'/">'.$topmenu_data[$c_id]["menu_name"].'</a>';
		};
		
		$menu_img = ($topmenu_data[$c_id]["menu_img"]) ? '<a href="/templates/'.$theme["template_name"].'/images/'.$topmenu_data[$c_id]["menu_img"].'" title="���� ����������� - ���������� �������� (���������� ��� ���� �������� ����� ��������� � ��������� ������ ���������� � ��������)."><img src="/pic/ico/photo_16x16.png" width="16" height="16" border="0" alt="���� ����������� - ���������� ��������." /></a>' : "";
		$menu_class = ($topmenu_data[$c_id]["menu_class"]) ? '<img src="/pic/ico/style_16x16.png" alt="������ ���� �������� CSS ����� - '.$topmenu_data[$c_id]["menu_class"].'" width="16" height="16" border="0" />' : "";

		$link_type = ($topmenu_data[$c_id]["link_type"]) ? $topmenu_data[$c_id]["link_type"]. " - ".$menu_type_list[$topmenu_data[$c_id]["link_type"]] : $menu_type_list[0];

		$i_lev = 0;
		$sep = "";
		while ($i_lev < $lvl) {
			$sep .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			$i_lev++;
		};
			
		$template->assign_block_vars('swich_menu_list.menu_list', array(
			'MENU_ID' => $topmenu_data[$c_id]["menu_id"],
			'MENU_NAME' => $sep.$menu_name,
			'MENU_IMG' => $menu_img,
			'MENU_IMG_PATH' => $topmenu_data[$c_id]["menu_img"],
			'MENU_PATH' => $url,
			'MENU_SORT' => $topmenu_data[$c_id]["sortorder"],
			'MENU_TYPE' => $link_type,
			'MENU_DESC' => $topmenu_data[$c_id]["menu_desc"],
			'MENU_CLASS' => $menu_class,
			'MENU_CLASS_NAME' => $topmenu_data[$c_id]["menu_class"]
		));

		$menu_group = $topmenu_data[$c_id]["menu_group"] . " - ".$menu_group_list[$topmenu_data[$c_id]["menu_group"]];
		// ��������� ����������� ����� ����
		if ($m_group <> $topmenu_data[$c_id]["menu_group"]) {
			$template->assign_block_vars('swich_menu_list.menu_list.menu_sep', array(
				'MENU_GROUP' => $menu_group
			));

			$m_group = $topmenu_data[$c_id]["menu_group"];
		};
		
		// ��������� �������
		$lvl++;
		$tree .= admin_buildtopmenu_table($c_id, $menu_desc, $lvl, $m_group);
		$lvl--;
		
		$i++;
	};
	return $ret;
};



if (@$_POST['menu_save'] and $post_menu_id == "") {

	$insert_link_type = ($post_link_type) ? '"'.$post_link_type.'"' : 'NULL';
	$insert_menu_img = ($post_menu_img) ? '`"'.$post_menu_img.'"' : 'NULL';
	$insert_menu_class = ($post_menu_class) ? '`"'.$post_menu_class.'"' : 'NULL';


	$sql = 'INSERT INTO `'.TABLE_TOPMENU.'` '.
			'(`menu_pid` , `menu_group` , `sortorder` , `menu_path` , `menu_name` , `menu_desc` , `link_type` , `menu_img`, `menu_class`) '.
			'VALUES ("'.$post_menu_pid.'", "'.$post_menu_group.'", "'.$post_sortorder.'", "'.$post_menu_path.'", "'.$post_menu_name.'", "'.$post_menu_desc.'", '.$insert_link_type.', '.$insert_menu_img.', '.$insert_menu_class.');';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, '������ ���������� ������ ����', '', __LINE__, __FILE__, $sql);
		}
	else {
		$template->assign_block_vars('swich_save', array());
	};

	echo "<strong>���������</strong>";
	menutree(); // �������� ����

}
elseif (@$_GET['menu_remove']) {

	$sql = "DELETE FROM `".TABLE_TOPMENU."` WHERE `menu_id` = ".intval($_GET['menu_remove']).";";

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, '������ �������� ������ ����', '', __LINE__, __FILE__, $sql);
		}
	else {
		$template->assign_block_vars('swich_save', array());
	};

}
elseif (@$_POST['menu_save']) {

	$update_link_type = ($post_link_type) ? '`link_type` = "'.$post_link_type.'", ' : '`link_type` = NULL, ';
	$update_menu_img = ($post_menu_img) ? '`menu_img` = "'.$post_menu_img.'", ' : '`menu_img` = NULL, ';
	$update_menu_class = ($post_menu_class) ? '`menu_class` = "'.$post_menu_class.'", ' : '`menu_class` = NULL, ';

	// ���������� ������� ���� �� ��������� � ����������� id
	$post_menu_pid = ($post_menu_id == $post_menu_pid) ? "0" : $post_menu_pid;

	$sql = 'UPDATE `'.TABLE_TOPMENU.'` SET  '.
			'`sortorder` = "'.$post_sortorder.'", '.
			'`menu_pid` = "'.$post_menu_pid.'", '.
			'`menu_path` = "'.$post_menu_path.'", '.
			'`menu_name` = "'.$post_menu_name.'", '.
			'`menu_group` = "'.$post_menu_group.'", '.
			$update_link_type.
			$update_menu_img.
			$update_menu_class.
			'`menu_desc` = "'.$post_menu_desc.'" '.
			'WHERE `menu_id` ="'.$post_menu_id.'";';


	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, '������ ���������� ������ ����', '', __LINE__, __FILE__, $sql);
		}
	else {
		$template->assign_block_vars('swich_save', array());
	};

	echo "<strong>���������</strong>";
	menutree();  // �������� ����
};


//
// �������������� ���� ��������
//

$template->set_filenames(array(
	'body' => 'admin/menu/admin_menu_body.tpl')
);

if ((@$_GET['menu_id'] and $_GET['edit']=='menu') or $_GET["action"] == "addmenu") {
	
	$sql = 'SELECT * '.
			'FROM `' . TABLE_TOPMENU . '` '.
			'WHERE menu_id="'.$id.'";';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, '���� ������ �����������', '', __LINE__, __FILE__, $sql);
	};
	$menu_edit_data = $db->sql_fetchrow($result);

	$root_paragraf = ($menu_edit_data["menu_pid"] == 0) ? "<option value='0' selected>�������� ������</option>" : "<option value='0'>�������� ������</option>";	

	// �������������� ������ ����
	$template->assign_block_vars('swich_menu_edit', array(
		'MENU_NAME' => $menu_edit_data["menu_name"],
		'MENU_ID' => $menu_edit_data["menu_id"],
		'MENU_PID' =>  $menu_edit_data["menu_pid"],
		'MENU_PARAGRAF_ROOT' => $root_paragraf,
		'MENU_PARAGRAF' => buildparagraflist(0,0),
		'MENU_GROUP' => $menu_edit_data["menu_group"],
		'MENU_SORT' => $menu_edit_data["sortorder"],
		'MENU_PATH' => $menu_edit_data["menu_path"],
		'MENU_DESC' => $menu_edit_data["menu_desc"],
		'MENU_TYPE_'.$menu_edit_data["link_type"] => "selected",
		'MENU_IMG' => $menu_edit_data["menu_img"],
		'MENU_CLASS' => $menu_edit_data["menu_class"],
	));
}
elseif ($_GET['edit']=='menu') {
	// ������� ����
	$template->assign_block_vars('swich_menu_list', array());
	admin_buildtopmenu_table(0,"", 0, -1);

};

?>