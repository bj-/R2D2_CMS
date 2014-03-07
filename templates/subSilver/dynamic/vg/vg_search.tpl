{QSTRING}
{NOT_FOUND}
<!-- BEGIN switch_id -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<col style="width:110px;">
	<tr><td colspan="2" style="text-align:right;">
		<a href="http://forum.v-galaktike.ru/search.php?do=process&query={switch_id.G_ID}" target="_blank"><strong>Поиск по ID на Офф-форуме</strong></a>
	</td></tr>
	<tr>
		<td valign="top">
			ID:
			<!-- BEGIN swich_vk_module -->
			<!-- END swich_vk_module -->
		</td>
		<td valign="top">
			<!-- BEGIN swich_vk_module -->
			<div style="float:left;">
			<a href="http://vkontakte.ru/id{switch_id.swich_vk_module.G_ID}" target="_blank"><strong>
			<!-- END swich_vk_module -->
			{switch_id.G_ID}
			<!-- BEGIN swich_vk_module -->
			</strong></a>&nbsp;&nbsp;&nbsp;&nbsp;</div>
			<div id="vkstat" name="vkstat" style="float:left;"></div>
			<div style="clear:left;"></div>
			<!-- END swich_vk_module -->
		</td>
	</tr>
	<!-- BEGIN swich_vk_module -->
	<!-- END swich_vk_module -->
	<tr>
		<td>
<!--tr><td>Статус:</td><td>{switch_id.G_MEMBER}</td></tr-->
	<tr><td>Найдено планет:</td><td>{switch_id.PLANETS_CNT}{switch_id.PLANET_SCAN}</td></tr>
	<!-- BEGIN switch_alliance -->
	<tr>
		<td valign="top">
			<a onclick="show_content('/dynamic/mod/vg/vg_search.php?id={switch_id.G_ID}&ftype=ally_history', '#search_res_r')" style="cursor:pointer;">Альянсы:</a>
		</td>
		<td>
		<!-- BEGIN alliance_list -->
		{switch_id.switch_alliance.alliance_list.ALLIANCE}<br />
		<!-- END alliance_list -->
		</td>
	</tr>
	<!-- END switch_alliance -->
<!--
Отпуск: {switch_id.OT}[нет данных]
aw:{switch_id.AW}[нет данных]
Протект:{switch_id.PROTECT}[нет данных]
Ник и UID на форуме
Имя/фамилий из вконтакта
-->
	<tr><td colspan="2">{switch_id.REGISTERED_USER}</td></tr>
</table>
<hr />
<script>
$(document).ready(function(){
// состояние вконтакта
//show_content('http://milonov.ru/temp/vg_vk.php?vkid={switch_id.G_ID}', '#vkstat');
show_content('/dynamic/mod/vg/vg_vk2.php?vkid={switch_id.G_ID}', '#vkstat');
});
</script>
<!-- END switch_id -->

<!-- BEGIN switch_coords -->
<div id="d_{switch_coords.coords_list.NUM}" name="d_{switch_coords.coords_list.NUM}">
	</div>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<th style="width:120px; text-align:left;">Координаты&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th style="width:120px; text-align:left;">Название&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th style="width:120px; text-align:left;">Дата обновления</th>
	</tr>
	<!-- BEGIN coords_list -->
	<tr>
		<td>{switch_coords.coords_list.COORDS}</td>
		<td>
			<a onclick="show_p_det('{switch_coords.coords_list.NUM}');" style="cursor:pointer;">{switch_coords.coords_list.NAME}</a>
		</td>
		<td>{switch_coords.coords_list.MAPDATE}</td>
	</tr>
	<tr><td colspan="3">
		<div id="x_{switch_coords.coords_list.NUM}"><div>
	</td></tr>
	<!-- END coords_list -->
</table>
<script>
var ScanArr = new Array();
var ShowScanArr = new Array();

	<!-- BEGIN coords_list -->
ScanArr[{switch_coords.coords_list.NUM}] = '<small>' + 
			'{switch_coords.coords_list.DEFENCE}' + 
			'{switch_coords.coords_list.MINES}' + 
			'{switch_coords.coords_list.STORE}' + 
			'{switch_coords.coords_list.BATTLE}' + 
			'{switch_coords.coords_list.BUILDINGS}' + 
			'{switch_coords.coords_list.SCAN_DATE}' + 
			'</small>';
ShowScanArr[{switch_coords.coords_list.NUM}] = 0;
	<!-- END coords_list -->

function show_p_det(opt_id) {
//	alert(ScanArr[opt_id]);
	if (ShowScanArr[opt_id]) {
		$('#x_'+opt_id).html('');
		ShowScanArr[opt_id] = 0;
	}
	else {
		$('#x_'+opt_id).html(ScanArr[opt_id]);
		ShowScanArr[opt_id] = 1;
	};
};

</script>
<!-- END switch_coords -->

<!-- BEGIN switch_fleet -->
<hr />
Максимально замеченный флот:<br />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td valign="top">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr><td>Шатл:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_fleet.FLEET_SHATTLE}</td></tr>
	<tr><td>Транспорт:&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_fleet.FLEET_TRANSTORT}</td></tr>
	<tr><td>Проходимец:&nbsp;</td><td>{switch_fleet.FLEET_PP}</td></tr>
	<tr><td>Коллектор:&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_fleet.FLEET_KOLLECTOR}</td></tr>
	<tr><td>Разведзонд:&nbsp;&nbsp;</td><td>{switch_fleet.FLEET_SPYZOND}</td></tr>
	<tr><td>Энергодрон:&nbsp;&nbsp;</td><td>{switch_fleet.FLEET_ENERGODRON}</td></tr>
 	</table>
</td><td>
	<table border="0" cellpadding="0" cellspacing="0">
	<tr><td>Истрибитель:&nbsp;</td><td>{switch_fleet.FLEET_FIGHTER}</td></tr>
	<tr><td>Штурмовик:&nbsp;&nbsp;&nbsp;</td><td>{switch_fleet.FLEET_ATACKER}</td></tr>
	<tr><td>Корвет:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_fleet.FLEET_KORVET}</td></tr>
	<tr><td>Фрегат:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_fleet.FLEET_FREGAT}</td></tr>
	<tr><td>Бомбардир:&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_fleet.FLEET_BOMBARDIER}</td></tr>
	<tr><td>Коллос:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_fleet.FLEET_DEATHSTAR}</td></tr>
	<tr><td>Разрушитель:&nbsp;</td><td>{switch_fleet.FLEET_RAZR}</td></tr>
	<tr><td>Галлактион:&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_fleet.FLEET_GALLACTIONE}</td></tr>
	</table>
</td></tr>
</table>
<div style="text-align:right;"><small>По сканам до: {switch_fleet.SCAN_DATE}</small></div>
<!-- END switch_fleet -->

<!-- BEGIN switch_tech -->
<hr />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0">
	<tr><td>ПС:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_PS}</td></tr>
	<tr><td>Нави:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_NAVI}</td></tr>
	<tr><td>ОП:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_PLANETOLOGY}</td></tr>
	<tr><td>П в П:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_SSM}</td></tr>
	<tr><td>ТСкан:&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_TAHYONSCAN}</td></tr>
	<tr><td>Б.Лазеры:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_LASER}</td></tr>
	<tr><td>Фотонное:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_FOTON}</td></tr>
	<tr><td>Лептонное:&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_LEPTON}</td></tr>
	<tr><td>Энергетика:&nbsp;</td><td>{switch_tech.TECH_ENERGY}</td></tr>
	<tr><td>Вибротрон:&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_VIBROTRON}</td></tr>
	</table>
</td>
<td style="vertical-align:text-top;">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr><td>Вооружения:&nbsp;&nbsp;</td><td>{switch_tech.TECH_ATTACK}</td></tr>
	<tr><td>Защита:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_ARMOR}</td></tr>
	<tr><td valign="top">Поля:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_SHIELDS}<br /><br /></td></tr>
	<tr><td>Барионный:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_ENG_BAR}</td></tr>
	<tr><td>Анниг.:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_ENG_ANN}</td></tr>
	<tr><td>ППД:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{switch_tech.TECH_ENG_SSE}</td></tr>
	</table>
</td>
</tr>
</table>
<div style="text-align:right;"><small>Скан от: {switch_tech.SCAN_DATE}</small></div>
<!-- END switch_tech -->

<!-- BEGIN alliance_list -->
	<!-- BEGIN alliance_list_nav -->
		<div style="text-align:right;">
		<a onclick="show_content('/dynamic/mod/vg/vg_search.php?id={alliance_list.alliance_list_nav.ALLIANCE_ID}&ftype=ally_cross&coordstart={alliance_list.alliance_list_nav.COORD_START}&coordend={alliance_list.alliance_list_nav.COORD_END}', '#search_res_l')" style="cursor:pointer; font-weight:bold;">&lt;--Назад</a>
		</div>
	<!-- END alliance_list_nav -->

Выбирете альянс для поиска:
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<!-- BEGIN alliance_list_row -->
	<tr><td style="overflow:hidden; border-bottom:dotted gray 1px;">
			<a onclick="show_content('/dynamic/mod/vg/vg_search.php?id={alliance_list.alliance_list_row.ALLIANCE_ID}&ftype=allid&coordstart={alliance_list.alliance_list_row.COORD_START}&coordend={alliance_list.alliance_list_row.COORD_END}', '#search_res_l')" style="cursor:pointer; font-weight:bold;">{alliance_list.alliance_list_row.ALLIANCE_NAME}</a>
		</td></tr>
	<!-- END alliance_list_row -->
	</table>
		</td>
		<td align="right" style="text-align:right;">
	<!-- BEGIN alliance_list_row -->
		<div style="overflow:hidden; border-bottom:dotted gray 1px;">
			<a onclick="show_content('/dynamic/mod/vg/vg_search.php?id={alliance_list.alliance_list_row.ALLIANCE_ID}&ftype=ally_cross&coordstart={alliance_list.alliance_list_row.COORD_START}&coordend={alliance_list.alliance_list_row.COORD_END}&from_id={alliance_list.alliance_list_row.FROM_ID}', '#search_res_l')" style="cursor:pointer; font-weight:bold;">Кросс-аллы</a>
		</div>
	<!-- END alliance_list_row -->
		</td></tr>
</table>
<!-- END alliance_list -->

<!-- BEGIN alliance_planet -->
Всего планет в альянсе: {alliance_planet.TOTAL}
<table>
	<tr><!--th>#</th--><th style="width:120px; text-align:left;">Координаты&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th style="width:120px; text-align:left;">Название&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>ID</th><th style="width:120px; text-align:left;">Дата</th></tr>
	<!-- BEGIN alliance_planet_list -->
<tr><td>{alliance_planet.alliance_planet_list.COORD}</td>
	<td>{alliance_planet.alliance_planet_list.P_NAME}</td>
	<td><a onclick="show_content('/dynamic/mod/vg/vg_search.php?id={alliance_planet.alliance_planet_list.G_ID}&ftype=id', '#search_res_r');" style="cursor:pointer;">{alliance_planet.alliance_planet_list.G_ID}</a></td>
	<td>{alliance_planet.alliance_planet_list.UPDATE}</td>
</tr>
	<!-- END alliance_planet_list -->
</table>
<!-- END alliance_planet -->

<!-- BEGIN switch_ally_history -->
<div style="padding-left:30px; padding-top:100px">
<strong>История альянсов:</strong><br /><br />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><th align="left">Дата</th><th align="left">Альянс</th></tr>
	<!-- BEGIN ally_list -->
	<tr><td>{switch_ally_history.ally_list.ANNY_DATE}</td><td>{switch_ally_history.ally_list.ALLY_NAME}</td></tr>
	<!-- END ally_list -->
</table>
</div>
<!-- END switch_ally_history -->

<!-- BEGIN switch_history -->
<p><br />История:<br />
Альянс:</p>
<!-- END switch_history -->
{ARTICLE}
