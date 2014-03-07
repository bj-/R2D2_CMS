

<div style="float:left; width:70%">
	<p>Текущая база: {CURRENT_BASE} [<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php?action=prewiev', '#action')"><strong>Превью и статистика базы</strong></a>] [<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php?action=stat_temp_db', '#action_right')"><strong>Статистика временной базы</strong></a>]<br /></p>
	
	<strong>Импорт и обновление карты галактики</strong>
	<table width="100%">
	<tr>
		<td style="font-size:large; font-weight:bold; width:60px;">Шаг 1</td>
		<td>
			<div id="import_step_1" style="font-weight:bold; font-size:larger; color:green; float:left;"></div>
			<div style="float:left;"><a style="cursor:pointer;" onclick="vg_import('/admin/vg/dynamic/admin_dyn_action.php?action=p_transfer', '1')">Перенос планет в мускул 1:1 + импорт своих планет в отдельную таблицу</a></div>
			<div style="clear:left;"></div>
		</td>
	</tr>
	<tr><td style="font-size:large; font-weight:bold; width:60px;">Шаг 2</td>
		<td>
			<div id="import_step_2" style="font-weight:bold; font-size:larger; color:green; float:left;"></div>
			<div style="float:left;"><a style="cursor:pointer;" onclick="vg_import('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=import_names', '2')">Импорт названий планет, альянсов, новых игроков</a></div>
			<div style="clear:left;"></div>
		</td>
	</tr>

	<tr><td style="font-size:large; font-weight:bold; width:60px;">Шаг 3</td>
		<td>
			<div id="import_step_3" style="font-weight:bold; font-size:larger; color:green; float:left;"></div>
			<div style="float:left;"><a style="cursor:pointer;" onclick="vg_import('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=id_history', '3')">Импорт истории ID</a></div>
			<div style="clear:left;"></div>
		</td>
	</tr>

	<tr><td style="font-size:large; font-weight:bold; width:60px;">Шаг 4</td>
		<td><div id="import_step_4" style="font-weight:bold; font-size:larger; color:green; float:left;"></div>
		<div style="float:left;"><a style="cursor:pointer;" onclick="vg_import('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=new', '4')">Добавление новых планет</a></div><div style="clear:left;"></div>
	</tr>

	<tr><td style="font-size:large; font-weight:bold; width:60px;">Шаг 5</td>
		<td><div id="import_step_5" style="font-weight:bold; font-size:larger; color:green; float:left;"></div>
			<div style="float:left;"><a style="cursor:pointer;" onclick="vg_import('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=upd', '5')">Обновляем имеющиеся планеты</a></div><div style="clear:left;"></div>
	</tr>

	<tr><td style="font-size:large; font-weight:bold; width:60px;">Шаг 6</td>
		<td><div id="import_step_6" style="font-weight:bold; font-size:larger; color:green; float:left;"></div>
			<div style="float:left;"><a style="cursor:pointer;" onclick="vg_import('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=del', '6')">Помечаем удаленные планты (!ВНИМАНИЕ! желательно не удалить нах всю базу)</a></div><div style="clear:left;"></div>
	</tr>

	<tr><td style="font-size:large; font-weight:bold; width:60px;">Шаг 7</td>
		<td><div id="import_step_7" style="font-weight:bold; font-size:larger; color:green; float:left;"></div>
			<div style="float:left;"><a style="cursor:pointer;" onclick="vg_import('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=otaw', '7')">Импорт статусов (ot, aw)</a></div><div style="clear:left;"></div>
	</tr>

	<tr><td style="font-size:large; font-weight:bold; width:60px;">Шаг 8</td>
		<td><div id="import_step_8" style="font-weight:bold; font-size:larger; color:green; float:left;"></div>
			<div style="float:left;"><a style="cursor:pointer;" onclick="vg_import('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=gamers_alliances', '8')">Привязываем аккаунты к альянсам</a></div><div style="clear:left;"></div>
	</tr>

	</table>

<div>Лог операций:</div>
<table width="100%" border="0" cellpadding="0" cellspacing="5">
	<tr><td style="border-bottom:solid 1px gray;"><div id="import_step_log_step_1"></div></td><td style="border-bottom:solid 1px gray;"><div id="import_step_log_1"></div></td></tr>
	<tr><td style="border-bottom:solid 1px gray;"><div id="import_step_log_step_2"></div></td><td style="border-bottom:solid 1px gray;"><div id="import_step_log_2"></div></td></tr>
	<tr><td style="border-bottom:solid 1px gray;"><div id="import_step_log_step_3"></div></td><td style="border-bottom:solid 1px gray;"><div id="import_step_log_3"></div></td></tr>
	<tr><td style="border-bottom:solid 1px gray;"><div id="import_step_log_step_4"></div></td><td style="border-bottom:solid 1px gray;"><div id="import_step_log_4"></div></td></tr>
	<tr><td style="border-bottom:solid 1px gray;"><div id="import_step_log_step_5"></div></td><td style="border-bottom:solid 1px gray;"><div id="import_step_log_5"></div></td></tr>
	<tr><td style="border-bottom:solid 1px gray;"><div id="import_step_log_step_6"></div></td><td style="border-bottom:solid 1px gray;"><div id="import_step_log_6"></div></td></tr>
	<tr><td style="border-bottom:solid 1px gray;"><div id="import_step_log_step_7"></div></td><td style="border-bottom:solid 1px gray;"><div id="import_step_log_7"></div></td></tr>
	<tr><td style="border-bottom:solid 1px gray;"><div id="import_step_log_step_8"></div></td><td style="border-bottom:solid 1px gray;"><div id="import_step_log_8"></div></td></tr>
	<!--tr><td style="border-bottom:solid 1px gray;"><div id="import_step_log_step_9"></div></td><td style="border-bottom:solid 1px gray;"><div id="import_step_log_9"></div></td></tr-->
</table>
<p><br /></p>

<script>
function vg_import(action, step_id){
	show_content(action, '#import_step_log_'+step_id)
	$('#import_step_'+step_id).html('PASSED &nbsp; ');
	$('#import_step_log_step_'+step_id).html('Step ' + step_id + ' &nbsp; ');
};
</script>


	<!--a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=truncate_temp&db=base', '#imp_action')">1. Чистим временную таблицу</a><br /-->
	<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=truncate_temp&db=my', '#imp_action')">2. Чистим временную таблицу своих планет</a><br />

<br />
	<!--a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php?action=p_transfer', '#imp_action')">1. Перенос планет в мускул 1:1 + импорт своих планет в отдельную таблицу</a><br /-->
	<br />
	2. Отчеты: 
	<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php?action=t_transfer', '#imp_action')">Конвертация с переносом</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php?action=t_parse', '#imp_action')">Парсинг</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php?action=spy_reports', '#imp_action')">Импорт</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php?action=report_ally_import', '#imp_action')">Привязка к альянсам</a>; 
	<br />
	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=transfer_my', '#imp_action')">2. импорт своих планет в отдельную таблицу</a><br /-->

	<!--br />
	Импорт:<br>
	<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=import_names', '#imp_action')">1. Импорт названий планет, альянсов, новых игроков</a><br />
	<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=id_history', '#imp_action')">2. истории ID</a><br />
	5. импортим планеты: 
	<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=new', '#imp_action')">3. новые</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=upd', '#imp_action')">4. обновленные</a>
		<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=del', '#imp_action')">5. удаленные</a>
		<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=otaw', '#imp_action')">6. статусы (ot, aw)</a>
<br />
	<a style="cursor:pointer;" onclick="show_content('/admin/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=gamers_alliances', '#imp_action')">6. затычка для привязки к альянсам.</a><br /-->


	<p>{SRC_DB_STAT}</p>

	<div id="action">{FUNC_RET}</div>
	<div id="imp_action"></div>
</div>
<div style="float:left; width:30%; cursor:pointer;">
	[ <a onclick="show_content('/admin/vg/dynamic/admin_dyn_src_db_list.php?id=', '#src_db_list')">Файлы баз</a> ]
	<div id="src_db_list"></div>
	<div id="action_right"></div>
</div>
<div style="clear:left;"></div>
