<?php
/***************************************************************************
 *                                incl_menu.php
 *								  			build menu
 *                            -------------------
 *   begin                : Saturday, Jul 13, 2010
 *   copyright            : (C) 2001 The R2D2 Group
 *
 *   $Id: msend.php,v 1.99.2.1 2002/12/19 17:17:40 psotfx Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 * 
 *
 ***************************************************************************/

 
//
//отсылка почты из формы
//

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}
//echo "<pre>";
//print_r($topmenu_data);

function buildmenu($pid, $menutype) {
	
//	echo $menutype;
	/*
		$menutype	= группа меню (menu group)
		 $group		= группа меню
		 $startlvl	= parent ID с которого начинать строить меню
		 $levels		= глубина (количество уровней)
		 $target		= указатель на шаблон который использовать
	*/
	global $template;
	global $topmenu_pids, $topmenu_data, $url_lang;

	$ret = "";

//echo '<pre>';
//print_r($topmenu_pids);
//echo '</pre>';

	if ($menutype == 0) 	{ // Главное меню
		$menu_group_name = 'topmenu';
	}
	elseif ($menutype == 1) { // Вспомогательнео меню (слева)
		$menu_group_name = 'leftmenu';
	}
	elseif ($menutype == 2) { // Вспомогательнео меню (справа)
		$menu_group_name = 'rightmenu';
	}
	elseif ($menutype == 3) { // Вспомогательнео меню (снизу)
		$menu_group_name = 'bottommenu';
	};

	$template->set_filenames(array(
		'menu'.$menu_group_name => 'incl/incl_menu_'.$menu_group_name.'.tpl')
	);

	$template->assign_block_vars($menu_group_name,array());

	$i = 0;
	while ($topmenu_pids[$pid][$i]) {
		$c_id = $topmenu_pids[$pid][$i];
		
//		$url = "/" .$url_lang."/".get_full_url($c_id);
			if ($topmenu_data[$c_id]["menu_group"] == $menutype) {
				
				if ($topmenu_data[$c_id]['link_type']) {
					$url = $topmenu_data[$c_id]['menu_path'];
				}
				else {
					$url = "/" .$url_lang."/".get_full_url($c_id) . "/";
				};
				$url_target = ($topmenu_data[$c_id]['link_type'] == '1') ? ' target="_blank" ': "";
//var_dump($topmenu_data);
				$tag_a_open = ($topmenu_data[$c_id]['link_type'] == '3') ? "" : '<a class="'.$topmenu_data[$c_id]['menu_class'].'" href="'.$url . '" title="'.$topmenu_data[$c_id]['menu_name'].'" '.$url_target.'>';
				$tag_a_close = ($topmenu_data[$c_id]['link_type'] == '3') ? "" : "</a>";				
//				$url_class = ($topmenu_data[$c_id]['link_type'] == '3') ? "label" : "";

//echo $topmenu_data[$c_id]['menu_name'];
				$template->assign_block_vars($menu_group_name.'.menulist',array(
					'MENU' => $topmenu_data[$c_id]['menu_name'],
					'MENU_DESC' => $topmenu_data[$c_id]['menu_desc'],
					'TAG_A_OPEN' => $tag_a_open,
					'TAG_A_CLOSE' => $tag_a_close,
					'PATH' => $url,
					'URL_TARGET' => $url_target,
					'URL_CLASS' => $url_class,
					'ID' => $topmenu_data[$c_id]['menu_id'],
					'MENU_IMG' => $topmenu_data[$c_id]['menu_img'],
					'CLASS' => $topmenu_data[$c_id]['menu_class'],
				));
				
				// сепаратор для меню.
				if ($topmenu_pids[$pid][$i+1]) {
					$template->assign_block_vars($menu_group_name.'.menulist.menu_sep',array(
						'CLASS' => $topmenu_data[$topmenu_pids[$pid][$i+1]]['menu_class'],
					));
				};
				
				if ($topmenu_pids[$c_id][0]) {	// Подменю
					$template->assign_block_vars($menu_group_name.'.menulist.menu_sub_1',array());

					$i2=0;
					while ($topmenu_pids[$c_id][$i2]) {
						$c_sub_id = $topmenu_pids[$c_id][$i2];

						$url = "/" .$url_lang."/".get_full_url($c_sub_id);

						$template->assign_block_vars($menu_group_name.'.menulist.menu_sub_1.menu_sub_1_list',array(
							'MENU' => $topmenu_data[$c_sub_id]['menu_name'],
							'MENU_DESC' => $topmenu_data[$c_sub_id]['menu_desc'],
							'PATH' => $url . "/",
							'ID' => $topmenu_data[$c_sub_id]['menu_id'],
							'MENU_IMG' => $topmenu_data[$c_sub_id]['menu_img'],
							'CLASS' => $topmenu_data[$c_sub_id]['menu_class'],

						));
//						menulist_sub_1
//						echo $topmenu_pids[$c_id][$i2];
						$i2++;
					};
				};
				
			};	
		$i++;
	};

	if ($menutype == 0) 	{ // Главное меню
		$template->assign_var_from_handle('TOP_MENU', 'menu'.$menu_group_name);
	}
	elseif ($menutype == 1) { // Вспомогательнео меню (слева)
		$template->assign_var_from_handle('LEFT_MENU', 'menu'.$menu_group_name);
	}
	elseif ($menutype == 2) { // Вспомогательнео меню (справа)
		$template->assign_var_from_handle('RIGHT_MENU', 'menu'.$menu_group_name);
	}
	elseif ($menutype == 3) { // Вспомогательнео меню (снизу)
		$template->assign_var_from_handle('BOTTOM_MENU', 'menu'.$menu_group_name);
	};
	
	return $ret;

};
//echo "ddddывпвыпвы";

?>

