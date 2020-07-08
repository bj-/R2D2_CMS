<script>

</script>

<div id="BlockMsg_{BLOCKSERIALNO}">
<input type="hidden" id="{BLOCKSERIALNO}_BlockSerNo" value="{BLOCKSERIALNO}" />
<input type="checkbox" id="{BLOCKSERIALNO}_All" {ALL_CHECKED} /> <a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Block_OnbText&prms=BlockSerNo={BLOCKSERIALNO};Filter=All', '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">[ Все ]</a> &nbsp; / &nbsp; 
<input type="checkbox" id="{BLOCKSERIALNO}_All_Actual" {ALL_ACTUAL_CHECKED} /> <a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Block_OnbText&prms=BlockSerNo={BLOCKSERIALNO};Filter=AllImportant', '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">[ Все Значимые ]</a> &nbsp; / &nbsp; 
<input type="checkbox" id="{BLOCKSERIALNO}_Stations" {STATIONS_CHECKED} /> <a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Block_OnbText&prms=BlockSerNo={BLOCKSERIALNO};Filter=Position', '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">[ Станции ]</a> &nbsp; / &nbsp; 
<input type="checkbox" id="{BLOCKSERIALNO}_StationsTunnel" {STATIONS_TUNNEL_CHECKED} /> <a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Block_OnbText&prms=BlockSerNo={BLOCKSERIALNO};Filter=PositionAll', '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">[ Станции с перегонами ]</a> &nbsp; / &nbsp; 
<input type="checkbox" id="{BLOCKSERIALNO}_Driver" {DRIVER_CHECKED} /> <a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Block_OnbText&prms=BlockSerNo={BLOCKSERIALNO};Filter=Driver', '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">[ Водитель ]</a> &nbsp; / &nbsp; 
<input type="checkbox" id="{BLOCKSERIALNO}_Quality" {QUALITY_CHECKED} /> <a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Block_OnbText&prms=BlockSerNo={BLOCKSERIALNO};Filter=Signal', '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">[ Качество ]</a> &nbsp; / &nbsp; 
<input type="checkbox" id="{BLOCKSERIALNO}_Alerts" {ALERTS_CHECKED} /> <a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Block_OnbText&prms=BlockSerNo={BLOCKSERIALNO};Filter=Alerts', '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">[ Тревоги ]</a> &nbsp; / &nbsp; 
<input type="checkbox" id="{BLOCKSERIALNO}_Alerts_Hid" {ALERTS_HID_CHECKED} /> <a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Block_OnbText&prms=BlockSerNo={BLOCKSERIALNO};Filter=AlertsSen', '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">[ Тревоги (Датчик) ]</a> &nbsp; / &nbsp; 
<input type="checkbox" id="{BLOCKSERIALNO}_Alerts_Connect" {ALERTS_CONNECT_CHECKED} /> <a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Block_OnbText&prms=BlockSerNo={BLOCKSERIALNO};Filter=AlertsLink', '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">[ Тревоги (Связь) ]</a> &nbsp; / &nbsp; 
<input type="checkbox" id="{BLOCKSERIALNO}_Buttons" {BUTTONS_CHECKED} /> <a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Block_OnbText&prms=BlockSerNo={BLOCKSERIALNO};Filter=Button', '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">[ Кнопки ]</a> &nbsp; / &nbsp; 
<input type="checkbox" id="{BLOCKSERIALNO}_State" {STATE_CHECKED} /> <a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Block_OnbText&prms=BlockSerNo={BLOCKSERIALNO};Filter=DrvState', '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">[ Состояние ]</a>
<!-- &nbsp; / &nbsp; -->
<br />За период: {FILTER_DATE}
 &nbsp; <a onclick="show_content(CreateUrl('proxy','Block_OnbText', '{BLOCKSERIALNO}_', ['BlockSerNo', 'All','All_Actual', 'Stations', 'StationsTunnel', 'Driver', 'Quality', 'Alerts', 
								'Alerts_Hid', 'Alerts_Connect', 'Buttons', 'State', 'Date_From', 'Time_From', 'Date_To', 'Time_To']), '#BlockMsg_{BLOCKSERIALNO}');" style="cursor:pointer;">Показать</a>
<!--
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=All', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ ALL ]</a> &nbsp; / &nbsp; 
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=Opened', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ Открытые ]</a> &nbsp; / &nbsp; 
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=Repair', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ В Ремонт ]</a> &nbsp; / &nbsp; 
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=Dev', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ Девелоперам ]</a> &nbsp; / &nbsp; 
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=Hardware', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ Железячникам ]</a> &nbsp; / &nbsp; 
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=Bad', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ Глючные ]</a>
-->
<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='100%'>
	<tr>        
		<th>Вагон</th>
		<th>Тип</th>
		<th>Текст</th>
		<th>Started</th>
		<th>Finished</th>
		<th colspan="2">Активно (время)</th>
	</tr>
	<!-- BEGIN row -->
	<tr>
		<td style="{row.STYLE_ROW}">{row.BLOCKSERNO}</td>
		<td style="{row.STYLE_ROW}">{row.TYPE}</td>
		<td style="{row.STYLE_ROW}">{row.TEXT}{row.TEST}</td>
		<td style="{row.STYLE_ROW}">{row.STARTED}</td>
		<td style="{row.STYLE_ROW}">{row.FINISHED}</td>
		<td style="{row.STYLE_ROW} {row.S_ACTIVITY}">~ {row.TIME_SPENT}</td>
		<td style="{row.STYLE_ROW}">{row.TIME_DIFF}</td>
	</tr>
		<!-- END row -->
</table>

{ARTICLE}
{TEMP}

<h3>Легенда</h3>
<ul>
<li></li>
<li></li>
<li></li>
<li></li>
</ul>
<p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p>
<p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p>
</div>