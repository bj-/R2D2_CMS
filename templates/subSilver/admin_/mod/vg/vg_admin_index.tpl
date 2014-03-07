

<div style="float:left; width:70%">
	Текущая база: {CURRENT_BASE} [<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=prewiev', '#action')"><strong>Превью и статистика базы</strong></a>] [<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=stat_temp_db', '#action_right')"><strong>Статистика временной базы</strong></a>]<br />

	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=truncate_temp&db=base', '#imp_action')">1. Чистим временную таблицу</a><br /-->
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=truncate_temp&db=my', '#imp_action')">2. Чистим временную таблицу своих планет</a><br />

<br />
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=p_transfer', '#imp_action')">1. Перенос планет в мускул 1:1 + импорт своих планет в отдельную таблицу</a><br />
	<br />
	2. Отчеты: 
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=t_transfer', '#imp_action')">Конвертация с переносом</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=t_parse', '#imp_action')">Парсинг</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=spy_reports', '#imp_action')">Импорт</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=report_ally_import', '#imp_action')">Привязка к альянсам</a>; 
	<br />
	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=transfer_my', '#imp_action')">2. импорт своих планет в отдельную таблицу</a><br /-->

	<br />
	Импорт:<br>
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=import_names', '#imp_action')">1. Импорт названий планет, альянсов, новых игроков</a><br />
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=id_history', '#imp_action')">2. истории ID</a><br />
	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=aname_import', '#imp_action')">2. Импорт названий альянсов</a><br /-->
	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=gamers_check', '#imp_action')">3. Проверка ID игроков (отфильтровывание существующих)</a><br /-->
	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=import_vkid', '#imp_action')">4. Импорт новых игроков</a><br /-->
	5. импортим планеты: 
	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_mark&mark=upd', '#imp_action')">1. обновленные;</a> 
	2. необновленные;  -->
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=new', '#imp_action')">3. новые</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=upd', '#imp_action')">4. обновленные</a>
		<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=del', '#imp_action')">5. удаленные</a>
		<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=otaw', '#imp_action')">6. статусы (ot, aw)</a>
<br />
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=gamers_alliances', '#imp_action')">6. затычка для привязки к альянсам.</a><br />


	<p>{SRC_DB_STAT}</p>

	<div id="action">{FUNC_RET}</div>
	<div id="imp_action"></div>
</div>
<div style="float:left; width:30%; cursor:pointer;">
	[ <a onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_src_db_list.php?id=', '#src_db_list')">Файлы баз</a> ]
	<div id="src_db_list"></div>
	<div id="action_right"></div>
</div>
<div style="clear:left;"></div>
