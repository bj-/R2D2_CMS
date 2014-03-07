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
// приводим в порядок входные переменные.
// 
$get_lang = substr($_GET['lang'],0, 3); // обрезаем id языка до 3 символов для борьбы и инжекшенами

//
// собираем страницу со статьей
//

if ($userdata['user_level'] >0) {
	$edit = '';
};

$id = intval($_GET["id"]);
$action = substr($_GET["action"], 0, 32);


// парсинг отчетов
function scan_parse($scan) {
	
		// Флот
		$dSAB = FALSE;
		$fl_sum_ti = 0;
		$fl_sum_si = 0;
		$parsed["osab"] = 'NULL'; // взводим флаг что задетекчен осаб.
		preg_match_all("/(Шатл:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		if ($res[2][0]) {
			$parsed["f_sh"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 2;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 2;
		}
		else {
			$parsed["f_sh"] = 'NULL';
		}
		preg_match_all("/(Транспорт:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
		if ($res[2][0]) {
			$parsed["f_tr"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 6;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 6;
		}
		else {
			$parsed["f_tr"] = 'NULL';
		}
		preg_match_all("/(Истребитель:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_fi"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_fi"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 3;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 1;
		}
		else {
			$parsed["f_fi"] = 'NULL';
		}
		preg_match_all("/(Штурмовик:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_at"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_at"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 6;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 4;
		}
		else {
			$parsed["f_at"] = 'NULL';
		}
		preg_match_all("/(Корвет:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_kr"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_kr"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 20;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 7;
		}
		else {
			$parsed["f_kr"] = 'NULL';
		}
		preg_match_all("/(Фрегат:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_fr"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_fr"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 45;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 15;
		}
		else {
			$parsed["f_fr"] = 'NULL';
		}
		preg_match_all("/(Первопроходец:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_pp"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_pp"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 10;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 20;
		}
		else {
			$parsed["f_pp"] = 'NULL';
		}
		preg_match_all("/(Коллектор:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_kl"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_kl"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 10;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 6;
		}
		else {
			$parsed["f_kl"] = 'NULL';
		}
		preg_match_all("/(Разведзонд:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_rz"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_rz"] = '"'.$res[2][0].'"';
			$fl_sum_si = $fl_sum_si + $res[2][0];
		}
		else {
			$parsed["f_rz"] = 'NULL';
		}
		preg_match_all("/(Бомбардир:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_bm"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_bm"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 50;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 25;
		}
		else {
			$parsed["f_bm"] = 'NULL';
		}
		preg_match_all("/(Энергодрон:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_en"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_en"] = '"'.$res[2][0].'"';
			$fl_sum_si = $fl_sum_si + $res[2][0] * 2;
		}
		else {
			$parsed["f_en"] = 'NULL';
		}
		preg_match_all("/(Колосс:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_klbk"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_klbk"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 5000;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 1000;
		}
		else {
			$parsed["f_klbk"] = 'NULL';
		}
		preg_match_all("/(Разрушитель:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_raz"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_raz"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 60;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 50;
		}
		else {
			$parsed["f_raz"] = 'NULL';
		}
		preg_match_all("/(Галактион:)\s+([0-9]{0,})/", $scan,$res);
		$dSAB = (count($res[0]) > 1) ? TRUE : $dSAB;
//		$parsed["f_g"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';
		if ($res[2][0]) {
			$parsed["f_g"] = '"'.$res[2][0].'"';
			$fl_sum_ti = $fl_sum_ti + $res[2][0] * 30;
			$fl_sum_si = $fl_sum_si + $res[2][0] * 40;
		}
		else {
			$parsed["f_g"] = 'NULL';
		};
		// данные о стоимости флота  в массив заносим	
		$parsed["f_sum_ti"] = $fl_sum_ti;
		$parsed["f_sum_si"] = $fl_sum_si;
/*
		// Стоимость флота
		$parsed["f_sum_ti"] = '"'.$parsed["f_sh"] * 2
									+ $parsed["f_tr"] * 6
									+ $parsed["f_fi"] * 3
									+ $parsed["f_at"] * 6
									+ $parsed["f_kr"] * 20
									+ $parsed["f_fr"] * 45
									+ $parsed["f_pp"] * 10
									+ $parsed["f_kl"] * 10
//									+ $parsed["f_rz"] * 0
									+ $parsed["f_bm"] * 50
//									+ $parsed["f_en"] * 0
									+ $parsed["f_klbk"] * 5000
									+ $parsed["f_raz"] * 60
									+ $parsed["f_g"] * 30 . '"' ; 
		$parsed["f_sum_si"] = '"'.$parsed["f_sh"] * 2
									+ $parsed["f_tr"] * 6
									+ $parsed["f_fi"] * 1
									+ $parsed["f_at"] * 4
									+ $parsed["f_kr"] * 7
									+ $parsed["f_fr"] * 15
									+ $parsed["f_pp"] * 20
									+ $parsed["f_kl"] * 6
									+ $parsed["f_rz"] 
									+ $parsed["f_bm"] * 25
									+ $parsed["f_en"] * 2
									+ $parsed["f_klbk"] * 4000
									+ $parsed["f_raz"] * 50
									+ $parsed["f_g"] * 40 . '"' ; 
*/
		// если обнаружен саб - сбрасываем флоты т.к. нет воможности определить кто сабер, а кто хозяин
		if ($dSAB) {
			$parsed["f_sh"] = 'NULL';
			$parsed["f_tr"] = 'NULL';
			$parsed["f_fi"] = 'NULL';
			$parsed["f_at"] = 'NULL';
			$parsed["f_kr"] = 'NULL';
			$parsed["f_fr"] = 'NULL';
			$parsed["f_pp"] = 'NULL';
			$parsed["f_kl"] = 'NULL';
			$parsed["f_rz"] = 'NULL';
			$parsed["f_bm"] = 'NULL';
			$parsed["f_en"] = 'NULL';
			$parsed["f_klbk"] = 'NULL';
			$parsed["f_raz"] = 'NULL';
			$parsed["f_g"] = 'NULL';
			$parsed["osab"] = '"1"'; // взводим флаг что задетекчен осаб.
			$parsed["f_sum_ti"] = 'NULL';
			$parsed["f_sum_si"] = 'NULL';
		};

		// Оборона
		$def_sum_ti = 0;
		$def_sum_si = 0;
		preg_match("/(Ракетный блок:)\s+([0-9]{0,})/", $scan,$res);
//		$parsed["d_rb"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		if ($res[2]) {
			$parsed["d_rb"] = '"'.$res[2].'"';
			$def_sum_ti = $def_sum_ti + $res[2] * 2;
		}
		else {
			$parsed["d_rb"] = 'NULL';
		}
		preg_match("/(Инфракрасный лазер:)\s+([0-9]{0,})/", $scan,$res);
//		$parsed["d_ir"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		if ($res[2]) {
			$parsed["d_ir"] = '"'.$res[2].'"';
			$def_sum_ti = $def_sum_ti + $res[2] * 1.5;
			$def_sum_si = $def_sum_si + $res[2] * 0.5;
		}
		else {
			$parsed["d_ir"] = 'NULL';
		}
		preg_match("/(Ультрафиолетовый лазер:)\s+([0-9]{0,})/", $scan,$res);
//		$parsed["d_uv"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		if ($res[2]) {
			$parsed["d_uv"] = '"'.$res[2].'"';
			$def_sum_ti = $def_sum_ti + $res[2] * 6;
			$def_sum_si = $def_sum_si + $res[2] * 2;
		}
		else {
			$parsed["d_uv"] = 'NULL';
		}
		preg_match("/(Гравитонное орудие:)\s+([0-9]{0,})/", $scan,$res);
//		$parsed["d_gr"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		if ($res[2]) {
			$parsed["d_gr"] = '"'.$res[2].'"';
			$def_sum_ti = $def_sum_ti + $res[2] * 20;
			$def_sum_si = $def_sum_si + $res[2] * 15;
		}
		else {
			$parsed["d_gr"] = 'NULL';
		}
		preg_match("/(Фотонная пушка:)\s+([0-9]{0,})/", $scan,$res);
//		$parsed["d_f"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		if ($res[2]) {
			$parsed["d_f"] = '"'.$res[2].'"';
			$def_sum_ti = $def_sum_ti + $res[2] * 2;
			$def_sum_si = $def_sum_si + $res[2] * 6;
		}
		else {
			$parsed["d_f"] = 'NULL';
		}
		preg_match("/(Лептонная пушка:)\s+([0-9]{0,})/", $scan,$res);
//		$parsed["d_l"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		if ($res[2]) {
			$parsed["d_l"] = '"'.$res[2].'"';
			$def_sum_ti = $def_sum_ti + $res[2] * 50;
			$def_sum_si = $def_sum_si + $res[2] * 50;
		}
		else {
			$parsed["d_l"] = 'NULL';
		}
		preg_match("/(Малый энергокупол:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["d_mk"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Большой энергокупол:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["d_bk"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';

		$parsed["def_sum"] = $def_sum_ti + $def_sum_si;
/*	
		$parsed["def_sum"] = '"'.$parsed["d_rb"] * 
									+ $parsed["d_ir"] * 
									+ $parsed["d_uv"] * 
									+ $parsed["d_gr"] * 
									+ $parsed["d_f"] * 
									+ $parsed["d_l"] * 
*/
		
		// Строения
		preg_match("/(Титановая шахта:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_ti"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Кремниевая шахта:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_si"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Коллайдер:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_kol"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Нейтринная электростанция:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_energy"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Аннигиляционный реактор:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_anih"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Робофабрика:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_robo"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Нанофабрика:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_nano"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Док:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_doc"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Склад титана:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_sti"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Склад кремния:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_ssi"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Склад антиматерии:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_sam"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Научный центр:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_nc"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Планетарный энергоузел:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_pen"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Заправочная база:)\s+(([0-9]{0,})|(Присутствует))/", $scan,$res);
//print_r($res);
		if ($res[2]) {
			$parsed["b_pbase"] = '"'. intval($res[2]) .'"';
		}
		elseif ($res[1])
			$parsed["b_pbase"] = '"1"';
		
		else {
			$parsed["b_pbase"] = 'NULL';
		};
//		$parsed["b_pbase"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Центр нанотехнологий:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_cnt"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Телепорт:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["b_tp"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';

		// Техи
		preg_match("/(Планетарное сканирование:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_ps"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Навигация:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_navi"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Вооружения:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_at"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Защита кораблей:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_def"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Энергетические поля:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_sh"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Энергетика:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_eng"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Перемещение в подпространстве:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_ssm"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Барионный двигатель:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_be"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Аннигиляционный двигатель:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_ae"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Подпространственный двигатель:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_sse"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Боевые лазеры:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_lsr"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Фотонное оружие:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_fot"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Лептонное оружие:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_lep"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Тахионное сканирование:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_tah"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Освоение планет:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_op"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';
		preg_match("/(Вибротрон:)\s+([0-9]{0,})/", $scan,$res);
		$parsed["t_vib"] = ($res[2]) ? '"'.$res[2].'"' : 'NULL';

		// Принадлежность скана
		preg_match_all("/(['])([0-9]{1,})/", $scan,$res); 
		$parsed["vk_id"] = ($res[2][0]) ? '"'.$res[2][0].'"' : 'NULL';

		preg_match("!(Альянс: )(.*?)((Титан)|(\r))!", $scan,$res); 
		$parsed["ally"] = ($res[2]) ? '"'.mysql_real_escape_string(substr($res[2],0,200)).'"' : 'NULL';

		preg_match_all("/\[(.*?)\]/", $scan,$res); 
		$parsed["coord_s"] = ($res[1][0]) ? '"'.$res[1][0].'"' : 'NULL';
		$coord_arr = ($res[1][0]) ? explode(":", $res[1][0]) : 0;
		$parsed["coord"] = ($coord_arr[0]*1048576) + $coord_arr[1]*16 + $coord_arr[2]-1;

		preg_match("/(.*?)(\[)/", $scan,$res); 
		$parsed["p_name"] = ($res[1]) ? '"'.mysql_real_escape_string(substr($res[1],0,200)).'"' : 'NULL';
		
		

//		echo "<pre>";
//	print_r($parsed);
	return $parsed;

};

// собираем строку для инсерта в базу
function build_inesrt($s, $src_scan) {
	global $userdata;
//echo coords2($s["coord"]);

	$sql = 'INSERT INTO `'.TABLE_VG_SCAN_TMP.'` ('.
			'`user_id`, `coord`, `p_name`, `r_ally`, `vkid`, `osab`, `scan_date`, `scan_date_add`, '.
			'`fl_sum_ti`, `fl_sum_si`, `def_sum`, '.
			'`f_sh`, `f_tr`, `f_fi`, `f_at`, `f_kr`, `f_fr`, `f_pp`, `f_kl`, `f_rz`, `f_bm`, `f_en`, `f_klbk`, `f_raz`, `f_g`, '.
			'`d_rb`, `d_ir`, `d_uv`, `d_gr`, `d_f`, `d_l`, `d_mk`, `d_bk`, '.
			'`b_ti`, `b_si`, `b_kol`, `b_energy`, `b_anih`, `b_robo`, `b_nano`, `b_doc`, `b_sti`, `b_ssi`, `b_sam`, `b_nc`, `b_pen`, `b_pbase`, `b_cnt`, `b_tp`,  '.
			'`t_scan`, `t_nav`, `t_att`, `t_arm`, `t_shld`, `t_en`, `t_ssmov`, `t_eng_bar`, `t_eng_ann`, `t_eng_ssp`, `t_las`, `t_fot`, `t_lep`, `t_tscan`, `t_planet`, `t_vib`, '.
			'`scan_src`'.
			') VALUES ('.

			// инфа о скане
			$userdata["user_id"] . ', '.
			$s["coord"].', '.
			$s["p_name"].', '.
			$s["ally"].', '.
			$s["vk_id"].', '.
			$s["osab"].', '.
			'NULL, '.
			mktime().', '.
			
			// стоимость флота-обороны
			$s["f_sum_ti"].', '.$s["f_sum_si"].', '.$s["def_sum"].', '.

			// Fleet
			$s["f_sh"].', '.$s["f_tr"].', '.$s["f_fi"].', '.$s["f_at"].', '.$s["f_kr"].', '.$s["f_fr"].', '.$s["f_pp"].', '.$s["f_kl"].', '.
			$s["f_rz"].', '.$s["f_bm"].', '.$s["f_en"].', '.$s["f_klbk"].', '.$s["f_raz"].', '.$s["f_g"].', '.
			// Defense
			$s["d_rb"].', '.$s["d_ir"].', '.$s["d_uv"].', '.$s["d_gr"].', '.$s["d_f"].', '.$s["d_l"].', '.$s["d_mk"].', '.$s["d_bk"].', '.
			
			// Buildings
			$s["b_ti"].', '.$s["b_si"].', '.$s["b_kol"].', '.$s["b_energy"].', '.$s["b_anih"].', '.$s["b_robo"].', '.$s["b_nano"].', '.
			$s["b_doc"].', '.$s["b_sti"].', '.$s["b_ssi"].', '.$s["b_sam"].', '.$s["b_nc"].', '.$s["b_pen"].', '.$s["b_pbase"].', '.$s["b_cnt"].', '.$s["b_tp"].

			// Tech
			', '.$s["t_ps"].', '.$s["t_navi"].', '.$s["t_at"].', '.$s["t_def"].', '.$s["t_sh"].', '.$s["t_eng"].
			', '.$s["t_ssm"].', '.$s["t_be"].', '.$s["t_ae"].', '.$s["t_sse"].', '.$s["t_lsr"].', '.$s["t_fot"].
			', '.$s["t_lep"].', '.$s["t_tah"].', '.$s["t_op"].', '.$s["t_vib"].', '.

			'"'.mysql_real_escape_string($src_scan).'" '.
			
			');';
	


/*
			$ret = 
			// инфа о скане
			'`r_ally` = '.$s["ally"].', '.
			'`vk_id` = '.$s["vk_id"].', '.
			'`coord_s` = '.$s["coord_s"].', '.
			'`osab` = '.$s["osab"].', '.
			'`p_name` = '.$s["p_name"].

			// Fleet
			', `f_sh` = '.$s["f_sh"].', `f_tr` = '.$s["f_tr"].', `f_fi` = '.$s["f_fi"].', `f_at` = '.$s["f_at"].', `f_kr` = '.$s["f_kr"].', `f_fr` = '.$s["f_fr"].
			', `f_pp` = '.$s["f_pp"].', `f_kl` = '.$s["f_kl"].', `f_rz` = '.$s["f_rz"].', `f_bm` = '.$s["f_bm"].', `f_en` = '.$s["f_en"].', `f_klbk` = '.$s["f_klbk"].
			', `f_raz` = '.$s["f_raz"].', `f_g` = '.$s["f_g"].
			// Defense
			', `d_rb` = '.$s["d_rb"].', `d_ir` = '.$s["d_ir"].', `d_uv` = '.$s["d_uv"].', `d_gr` = '.$s["d_gr"].', `d_f` = '.$s["d_f"].', `d_l` = '.$s["d_l"].
			', `d_mk` = '.$s["d_mk"].', `d_bk` = '.$s["d_bk"].
			
			// Buildings
			', `b_ti` = '.$s["b_ti"].', `b_si` = '.$s["b_si"].', `b_kol` = '.$s["b_kol"].', `b_energy` = '.$s["b_energy"].', `b_anih` = '.$s["b_anih"].
			', `b_robo` = '.$s["b_robo"].', `b_nano` = '.$s["b_nano"].', `b_doc` = '.$s["b_doc"].', `b_sti` = '.$s["b_sti"].', `b_ssi` = '.$s["b_ssi"].
			', `b_sam` = '.$s["b_sam"].', `b_nc` = '.$s["b_nc"].', `b_pen` = '.$s["b_pen"].', `b_pbase` = '.$s["b_pbase"].', `b_cnt` = '.$s["b_cnt"].
			', `b_tp` = '.$b_tp.

			// Tech
			', `t_ps` = '.$s["t_ps"].', `t_navi` = '.$s["t_navi"].', `t_at` = '.$s["t_at"].', `t_def` = '.$s["t_def"].', `t_sh` = '.$s["t_sh"].', `t_eng` = '.$s["t_eng"].
			', `t_ssm` = '.$s["t_ssm"].', `t_be` = '.$s["t_be"].', `t_ae` = '.$s["t_ae"].', `t_sse` = '.$s["t_sse"].', `t_lsr` = '.$s["t_lsr"].', `t_fot` = '.$s["t_fot"].
			', `t_lep` = '.$s["t_lep"].', `t_tah` = '.$s["t_tah"].', `t_op` = '.$s["t_op"].', `t_vib` = '.$s["t_vib"].' ';

*/			
			return $sql;
			
};


// удаляем скан, если этого хотел юзверь
//echo $action;
if ($action == "remove_scan") {
	$sql = 'DELETE FROM `'.TABLE_VG_SCAN_TMP.'` WHERE `user_id` = "'.$userdata["user_id"].'" AND `scan_id` = "'.$id. '";';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
	};
}
elseif ($action == "import") {
	// апдейтим нулевые имена планет (кривой импорт).
	$sql = 'UPDATE `'.TABLE_VG_SCAN_TMP.'` SET `p_name` = "[color:red]без названия[/color]" WHERE `p_name` IS NULL;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
	};

	// импортим новые имена планет
	$sql = 'SELECT  DISTINCT  `t`.`p_name`, `pn`.`name_id` '.
			'FROM `'.TABLE_VG_SCAN_TMP.'` AS `t` '.
			'LEFT JOIN `'.TABLE_VG_PLANET_NAME.'` AS `pn` ON `t`.`p_name` = `pn`.`planet_name` '.
			'WHERE `pn`.`name_id` IS NULL;';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
	};
	$pname_tmp = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$pname_tmp[] =  mysql_real_escape_string($row["p_name"]);
	};
	if (count($pname_tmp)) {
		$sql = 'INSERT INTO `'.TABLE_VG_PLANET_NAME.'` (`planet_name`) VALUES ("'.implode('"), ("', $pname_tmp).'");';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
		};
	};
	unset($pname_tmp);
	
	// импортим новые имена альянсов
	$sql = 'SELECT  DISTINCT `t`.`r_ally`, `a`.`alliance_id` '.
			'FROM `'.TABLE_VG_SCAN_TMP.'` AS `t` '.
			'LEFT JOIN `'.TABLE_VG_ALLIANCE.'` AS `a` ON `t`.`r_ally` = `a`.`alliance_name` '.
			'WHERE `a`.`alliance_id` IS NULL AND t.`r_ally` LIKE "%"';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
	};
	$ally_tmp = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$ally_tmp[] =  mysql_real_escape_string($row["r_ally"]);
	};
	if (count($ally_tmp)) {
		$sql = 'INSERT INTO `'.TABLE_VG_ALLIANCE.'` (`alliance_name`) VALUES ("'.implode('"), ("', $ally_tmp).'");';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
		};
	};
	unset($ally_tmp);

	// непосредственно импорт
	$nodate_import = ($_POST["no_date"]) ? '' : ' AND `t`.`scan_date` > 0 ';
	
	$sql = 'INSERT INTO `'.TABLE_VG_SCAN.'` '.
		'SELECT `t`.`osab`, `t`.`user_id`, `t`.`coord`, `t`.`p_name`, `pn`.`name_id`, `t`.`r_ally`, `a`.`alliance_id`, `t`.`vkid`, `g`.`gamers_id`, `t`.`osab`, `t`.`scan_date`, `t`.`scan_date_add`, '.
		'`t`.`fl_sum_ti`, `t`.`fl_sum_si`, `t`.`def_sum`, `t`.`f_sh`, `t`.`f_tr`, `t`.`f_fi`, `t`.`f_at`, `t`.`f_kr`, `t`.`f_fr`, `t`.`f_pp`, `t`.`f_kl`, `t`.`f_rz`, `t`.`f_bm`, `t`.`f_en`, `t`.`f_klbk`, `t`.`f_raz`, `t`.`f_g`, '.
		'`t`.`d_rb`, `t`.`d_ir`, `t`.`d_uv`, `t`.`d_gr`, `t`.`d_f`, `t`.`d_l`, `t`.`d_mk`, `t`.`d_bk`, '.
		'`t`.`b_ti`, `t`.`b_si`, `t`.`b_kol`, `t`.`b_energy`, `t`.`b_anih`, `t`.`b_robo`, `t`.`b_nano`, `t`.`b_doc`, `t`.`b_sti`, `t`.`b_ssi`, `t`.`b_sam`, `t`.`b_nc`, `t`.`b_pen`, `t`.`b_pbase`, `t`.`b_cnt`, `t`.`b_tp`, '.
		'`t`.`t_scan`, `t`.`t_nav`, `t`.`t_att`, `t`.`t_arm`, `t`.`t_shld`, `t`.`t_en`, `t`.`t_ssmov`, `t`.`t_eng_bar`, `t`.`t_eng_ann`, `t`.`t_eng_ssp`, `t`.`t_las`, `t`.`t_fot`, `t`.`t_lep`, `t`.`t_tscan`, `t`.`t_planet`, `t`.`t_vib` '.
		'FROM `'.TABLE_VG_SCAN_TMP.'` AS `t` '.
		'INNER JOIN `'.TABLE_VG_GAMERS.'` AS `g` ON `t`.`vkid` = `g`.`gamers_socialnet_id` '.
		'LEFT JOIN `'.TABLE_VG_ALLIANCE.'` AS `a` ON `t`.`r_ally` = `a`.`alliance_name` '.
		'INNER JOIN `'.TABLE_VG_PLANET_NAME.'` AS `pn` ON `t`.`p_name` = `pn`.`planet_name` '.
		'WHERE `t`.`user_id` = "'.$userdata["user_id"].'" AND `t`.`osab` IS NULL '.$nodate_import.';';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
	};
	
	// выбираем iD сканов что импортнули для удаления оных.
	$sql = 'SELECT `t`.`scan_id` FROM `'.TABLE_VG_SCAN_TMP.'` AS `t` WHERE `t`.`user_id` = "'.$userdata["user_id"].'" AND `t`.`osab` IS NULL '.$nodate_import.';';
	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
	};
	$scantodel_tmp = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$scantodel_tmp[] =  mysql_real_escape_string($row["scan_id"]);
	};
	if (count($scantodel_tmp)) {
		$sql = 'DELETE FROM `'.TABLE_VG_SCAN_TMP.'` WHERE `scan_id` IN ("'.implode('", "', $scantodel_tmp).'");';
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
		};
	};
	unset($scantodel_tmp);
	
};

//
// Start output of page
//
define('SHOW_ONLINE', true);
$page_title = (@$no_article) ? "Статья не найдена" : "База ID";
$page_classification = "";
$page_desc = "";
$page_keywords = "";
$page_content_direction = "";

$page_paragraf_id = $article_data[0]["paragraf_id"];
$page_paragraf_name = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_name'];
$page_paragraf_path = $topmenu_data[$article_data[0]["paragraf_id"]]['menu_path'];

$submit_path = "";
$page_id = "";

$page_path = "";
$page_text = (@$no_article) ? "<p><h2>Запрошенная статья не найдена</h2></p><p>Жалоба администратору сайта уже написана автоматически, спасибо за помощь.</p>" : $article_data[0]["article_text"];

$g_member = ($id_row["gamers_member"]) ? $id_row["gamers_member"] : "враг";
$finded_planet = count($planet_data);
date_default_timezone_set("Europe/Moscow");

include($DRoot . '/includes/page_header.'.$phpEx);


$template->set_filenames(array(
	'body' => 'vg/vg_add_scan.tpl')
);


//echo $_POST["vg_scan"] . "<p></p>";
$scan_src = str_replace("\n", "", $_POST["vg_scan"]);
//$scan_src = str_replace("\r", "", $scan_src);
preg_match_all('!Сырьё на (.*?)((Шпионский спутник был уничтожен)|(Погрешность информации))!', $scan_src, $scan);

$i=0;
while ($scan[1][$i]) {
	$scan_parsed = scan_parse($scan[1][$i]);
	$sql = build_inesrt($scan_parsed, $scan[1][$i]);

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'ошибка добавления скана в базу', '', __LINE__, __FILE__, $sql);
	};

//	echo $scan[1][$i];
//	echo "<p></p>";
	$i++;
};
//echo "<pre>";
//print_r($scan);
//echo "</pre>";

$sql = 'SELECT * FROM `'.TABLE_VG_SCAN_TMP.'` WHERE `user_id` = "'.$userdata["user_id"].'" ORDER BY `scan_id` ASC;';

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'ошибка запроса сканов из временной таблицы', '', __LINE__, __FILE__, $sql);
};
$scan_tmp = array();
while( $row = $db->sql_fetchrow($result) ) {
	$scan_tmp[] =  $row;
};

$i=0;
while ($scan_tmp[$i]["scan_id"]) {
	
	// Планета/ аккаунт
	$planet = "";
	$sum = "";
	$planet .= ($scan_tmp[$i]["coord"]) ? "". coords2($scan_tmp[$i]["coord"]) ."<br>" : "";
	$planet .= ($scan_tmp[$i]["p_name"]) ? "". $scan_tmp[$i]["p_name"] ."<br>" : "";
	$planet .= ($scan_tmp[$i]["r_ally"]) ? "". $scan_tmp[$i]["r_ally"] ."<br>" : "";
	$planet .= ($scan_tmp[$i]["vkid"]) ? "ID: ". $scan_tmp[$i]["vkid"] ."<br>" : "";
	$sum .= ($scan_tmp[$i]["fl_sum_ti"] or $scan_tmp[$i]["fl_sum_si"]) ? "Флот: ". number_format((($scan_tmp[$i]["fl_sum_ti"] + $scan_tmp[$i]["fl_sum_si"])/1000), 0, ',', '.')  ."кк<br>" : "";
	$sum .= ($scan_tmp[$i]["def_sum"]) ? "Оборона: ". number_format(($scan_tmp[$i]["def_sum"]/1000), 0, ',', '.') ."кк" : "";
	
	
	// Флот
	$fleet = "";
	$fleet .= ($scan_tmp[$i]["osab"]) ? '<span style="color:red; font-weight:bold;">На планете замечен осаб. флот проигронирован</span> <a onclick="show_content(\'/dynamic/vg/vg_show_scan.php?id='.$scan_tmp[$i]["scan_id"].'\', \'#scan_id_'.$scan_tmp[$i]["scan_id"].'\')" style="cursor:pointer;">Показать скан</a>' : "";
	$fleet .= ($scan_tmp[$i]["f_sh"]) ? "Ш: ". $scan_tmp[$i]["f_sh"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_tr"]) ? "Т: ". $scan_tmp[$i]["f_tr"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_kl"]) ? "Кл: ". $scan_tmp[$i]["f_kl"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_pp"]) ? "Пп: ". $scan_tmp[$i]["f_pp"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_en"]) ? "Эн: ". $scan_tmp[$i]["f_en"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_rz"]) ? "Рз: ". $scan_tmp[$i]["f_rz"] ."; " : "";
	$fleet = ($fleet) ? $fleet ."<br>" : "";
	$fleet .= ($scan_tmp[$i]["f_fi"]) ? "И: ". $scan_tmp[$i]["f_fi"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_at"]) ? "Шт: ". $scan_tmp[$i]["f_at"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_kr"]) ? "К: ". $scan_tmp[$i]["f_kr"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_fr"]) ? "Фр: ". $scan_tmp[$i]["f_fr"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_g"]) ? "Г: ". $scan_tmp[$i]["f_g"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_raz"]) ? "Р: ". $scan_tmp[$i]["f_raz"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_bm"]) ? "Б: ". $scan_tmp[$i]["f_bm"] ."; " : "";
	$fleet .= ($scan_tmp[$i]["f_klbk"]) ? "Колосс: ". $scan_tmp[$i]["f_klbk"] ."; " : "";
	
	// оборона
	$def = "";
	$def .= ($scan_tmp[$i]["d_rb"]) ? "Рб: ". $scan_tmp[$i]["d_rb"] ."; " : "";
	$def .= ($scan_tmp[$i]["d_ir"]) ? "Ик: ". $scan_tmp[$i]["d_ir"] ."; " : "";
	$def .= ($scan_tmp[$i]["d_uv"]) ? "Уф: ". $scan_tmp[$i]["d_uv"] ."; " : "";
	$def .= ($scan_tmp[$i]["d_gr"]) ? "Го: ". $scan_tmp[$i]["d_gr"] ."; " : "";
	$def .= ($scan_tmp[$i]["d_f"]) ? "Фп: ". $scan_tmp[$i]["d_f"] ."; " : "";
	$def .= ($scan_tmp[$i]["d_l"]) ? "Лп: ". $scan_tmp[$i]["d_l"] ."; " : "";
	$def .= ($scan_tmp[$i]["d_mk"]) ? "МК; " : "";
	$def .= ($scan_tmp[$i]["d_bk"]) ? "БК" : "";
	
	// Buildings
	$build = "";

	$build .= "Шахты: ";
	$build .= ($scan_tmp[$i]["b_ti"]) ? $scan_tmp[$i]["b_ti"] ."/" : "-/";
	$build .= ($scan_tmp[$i]["b_si"]) ? $scan_tmp[$i]["b_si"] ."/" : "-/";
	$build .= ($scan_tmp[$i]["b_kol"]) ? $scan_tmp[$i]["b_kol"] ."; " : "-; ";
	$build .= ($scan_tmp[$i]["b_energy"]) ? "En: ". $scan_tmp[$i]["b_energy"] ."; " : "";
	$build .= ($scan_tmp[$i]["b_anih"]) ? "Ар: ". $scan_tmp[$i]["b_anih"] ."; " : "";
	$build .= ($scan_tmp[$i]["b_robo"]) ? "Робо: ". $scan_tmp[$i]["b_robo"] ."; " : "";
	$build .= ($scan_tmp[$i]["b_nano"]) ? "Нано: ". $scan_tmp[$i]["b_nano"] ."; " : "";
	$build .= ($scan_tmp[$i]["b_doc"]) ? "Док: ". $scan_tmp[$i]["b_doc"] ."; " : "";
	$build .= "<br />Склады: ";
	$build .= ($scan_tmp[$i]["b_sti"]) ? $scan_tmp[$i]["b_sti"] ."/" : "-/";
	$build .= ($scan_tmp[$i]["b_ssi"]) ? $scan_tmp[$i]["b_ssi"] ."/" : "-/";
	$build .= ($scan_tmp[$i]["b_sam"]) ? $scan_tmp[$i]["b_sam"] ."; " : "-; ";
	$build .= ($scan_tmp[$i]["b_nc"]) ? "НЦ: ". $scan_tmp[$i]["b_nc"] ."; " : "";
	$build .= ($scan_tmp[$i]["b_pen"]) ? "ЭнУз: ". $scan_tmp[$i]["b_pen"] ."; " : "";
	$build .= ($scan_tmp[$i]["b_pbase"]) ? "ЗБ: ". $scan_tmp[$i]["b_pbase"] ."; " : "";
	$build .= ($scan_tmp[$i]["b_cnt"]) ? "ЦНТ: ". $scan_tmp[$i]["b_cnt"] ."; " : "";
	$build .= ($scan_tmp[$i]["b_tp"]) ? "ТП: ". $scan_tmp[$i]["b_tp"] ."; " : "";

	// Tech
	$tech = "";
	$tech .= "Боевые: ";
	$tech .= ($scan_tmp[$i]["t_att"]) ? $scan_tmp[$i]["t_att"] ."/" : "-/";
	$tech .= ($scan_tmp[$i]["t_arm"]) ? $scan_tmp[$i]["t_arm"] ."/" : "-/";
	$tech .= ($scan_tmp[$i]["t_shld"]) ? $scan_tmp[$i]["t_shld"] ."; " : "-;";
	$tech .= "Двигатели: ";
	$tech .= ($scan_tmp[$i]["t_eng_bar"]) ? $scan_tmp[$i]["t_eng_bar"] ."/" : "-/";
	$tech .= ($scan_tmp[$i]["t_eng_ann"]) ? $scan_tmp[$i]["t_eng_ann"] ."/" : "-/";
	$tech .= ($scan_tmp[$i]["t_eng_ssp"]) ? $scan_tmp[$i]["t_eng_ssp"] ."; " : "-;";
	$tech .= ($scan_tmp[$i]["t_scan"]) ? "ПС: ". $scan_tmp[$i]["t_scan"] ."; " : "";
	$tech .= ($scan_tmp[$i]["t_planet"]) ? "ОП: ". $scan_tmp[$i]["t_planet"] ."; " : "";
	$tech .= ($scan_tmp[$i]["t_nav"]) ? "Нави: ". $scan_tmp[$i]["t_nav"] ."; " : "";
	$tech .= "<br />";
	$tech .= ($scan_tmp[$i]["t_en"]) ? "Эн: ". $scan_tmp[$i]["t_en"] ."; " : "";
	$tech .= ($scan_tmp[$i]["t_ssmov"]) ? "Подпр: ". $scan_tmp[$i]["t_ssmov"] ."; " : "";
	$tech .= ($scan_tmp[$i]["t_las"]) ? "Лазер: ". $scan_tmp[$i]["t_las"] ."; " : "";
	$tech .= ($scan_tmp[$i]["t_fot"]) ? "Фот: ". $scan_tmp[$i]["t_fot"] ."; " : "";
	$tech .= ($scan_tmp[$i]["t_lep"]) ? "Лепт: ". $scan_tmp[$i]["t_lep"] ."; " : "";
	$tech .= ($scan_tmp[$i]["t_tscan"]) ? "Тах: ". $scan_tmp[$i]["t_tscan"] ."; " : "";
	$tech .= ($scan_tmp[$i]["t_vib"]) ? "Вибро: ". $scan_tmp[$i]["t_vib"] ."; " : "";
	
	$date = ($scan_tmp[$i]["scan_date"]) ? date("d.m.Y", $scan_tmp[$i]["scan_date"]) : '<span style="color:red;">Дата не указана</span><br>Будет указана текущая';
	$template->assign_block_vars('scan_list', array(
		'SCAN_ID' => $scan_tmp[$i]["scan_id"],
		'PLANET' => $planet,
		'SUM' => $sum,
		'FLEET' => $fleet,
		'DEFENCE' => $def,
		'BUILDING' => $build,
		'TECH' => $tech,
		'DATE' => $date
	));
	
	// возможность указать дату, если она не была указана.
	if (!$scan_tmp[$i]["scan_date"]) {
		$template->assign_block_vars('scan_list.no_date', array());
	};
	$i++;
	
};



$template->assign_vars(array(
//	'SAVED' => @$saved,
//	'PATCH_DESCRIPTION' => $paragrad_desc,
//	'ARTICLE' => $page_text,
	'ID' => $get_id,
	'SEL_ID' => @$sel_id,
	'SEL_ALL' => @$sel_all,
	'SEL_PLANET' => @$sel_planet
	
//	'EDIT' => @$edit
	));


//
// Generate the page
//
$template->pparse('body');

include($DRoot . '/includes/page_tail.'.$phpEx);

?>