<!--h2>{HEADER}</h2-->
<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='100%'>
	<tr>        
		<th>Линия</th>
		<th>Метка</th>
		<th><a title="В скобках - направление движение состава. Если совпадает = в оборот">Станция</a></th>
		<th>Last</th>
		<th>FW</th>
		<th>BAT</th>
		<th><a title="Текущая / требуется установить">Мощность</a></th>
	</tr>
	<!-- BEGIN row -->
	<tr onclick="ShowHideDiv('{row.SERIALNO}_LabelProp');$('{row.SERIALNO}_LabelTd').attr('colspan',6);" style="cursor:pointer;">
		<td style="{row.STYLE_LABEL_DIED}" align="center">{row.LINE_NAME}</td>
		<td style="{row.STYLE_LABEL_DIED}">{row.SERIALNO}</td>
		<td style="{row.STYLE_LABEL_DIED}">{row.STATION_NAME} (==&gt; {row.WAY_DIRECTION})</td>
		<td style="{row.STYLE_LABEL_DIED}" align="center" title="Последний раз видели: {row.LAST_ACTIVITY}">{row.LAST_ACTIVITY_AGO}</td>
		<td style="{row.STYLE_LABEL_DIED}" align="center">{row.FWVERSION}</td>
		<td style="{row.STYLE_LABEL_BAT} {row.STYLE_LABEL_DIED}" align="center">
			<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=report_SensorBatteryByDays&prms=ShowFilters=false;DateFrom={row.SQL_DATE_6MAGO};TimeFrom=;UseDateFrom=true;DateTo={row.SQL_DATE_NOW};TimeTo=;UseDateTo=true;UseSenOrFio=undefined;uGuid=undefined;VehGrp=;SensorGuid={row.SENSOR_GUID}" 
				onclick="return hs.htmlExpand(this, { slideshowGroup: 'LabelsBattery-group', objectType: 'ajax', contentId: 'highslide-html-8' } );" 
				title="от {row.BAT_TIME} ({row.BAT_TIME_AGO} дней назад)"
				style="color:black;">
				{row.BAT}
			</a>
		</td>
		<td style="{row.STYLE_LABEL_TXPWR} {row.STYLE_LABEL_DIED}" align="center">
			<a title="Текущая: [{row.TXPOWER}] / Требуемая: [{row.TXPOWER_REQUIRED}] (изменено {row.TXPOWER_CHANGED})">{row.TXPOWER} / {row.TXPOWER_REQUIRED}</a>
		</td>
	</tr>
	<tr>
		<td colspan="7" style="padding:0;padding-left:50px;spacing:0;">
			<div name="{row.SERIALNO}_LabelProp" id="{row.SERIALNO}_LabelProp" style="display:none;">
				<br />
				<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='100%'>
					<tr><th colspan="2">Детализация по метке</th></tr>
					<tr><td>Линия</td><td>{row.LINE_NAME} (№ {row.LINE_NUMBER})</td></tr>
					<tr><td>MAС</td><td>{row.MAC}</td></tr>
					<tr><td>Батарейка:</td><td style="{row.STYLE_LABEL_BAT}">{row.BAT} от {row.BAT_TIME} ({row.BAT_TIME_AGO} дней назад)</td></tr>
					<tr><td>Послединй раз замечена</td><td>{row.LAST_ACTIVITY} ({row.LAST_ACTIVITY_AGO} назад)</td></tr>
					<tr><td>Порядковый номер станции</td><td>{row.STATION_ORDERNO}</td></tr>
					<tr><td>Тип станции</td><td>{row.STATION_TYPE}</td></tr>
					<tr><td>Путь</td><td>{row.WAY_NO}</td></tr>
					<tr><td>Станция</td><td>{row.STATION_NAME}</td></tr>
					<tr><td>Направление</td><td>В сторону: {row.WAY_DIRECTION}</td></tr>
					<tr><td>Мощность:</td><td style="{row.STYLE_LABEL_TXPWR}">Текущая: [{row.TXPOWER}] / Требуемая: [{row.TXPOWER_REQUIRED}] (изменено {row.TXPOWER_CHANGED})</td></tr>
				</table>
				<p><br /></p>
			</div>
		</td>
	</tr>
	<!-- END row -->
</table>

{ARTICLE}
{TEMP}

<!-- BEGIN legend -->
<h3>Легенда</h3>
<table class='tbl'>
	<tr>
		<th>Обозначение</th>
		<th>Значение</th>
		<th>Что делать</th>
	</tr>
	<tr>
		<td>Красный фон строки</td>
		<td>Метка не видна в эфире более 1-7 дней</td>
		<td>Чинить / менять</td>
	</tr>
	<tr>
		<td>Красный фон Мощности при белой строке</td>
		<td>Слетела мощность</td>
		<td>Установить требуемую мощность</td>
	</tr>
	<tr>
		<td>Красный фон BAT при белой строке</td>
		<td>Садится батарейка</td>
		<td>Зарядить</td>
	</tr>
	<tr>
		<td>Клик по строке</td>
		<td colspan="2">Просмотр детализации по метке. Скрыть вторым кликом.</td>
	</tr>
	<tr>
		<td>Колонка "Станция"</td>
		<td colspan="2">
			Станция на которйо стоит метка, по направлению движения состава к станции в скобках.<br />
			Если названия сопадают = метка стоит в сторону оборота.
		</td>
	</tr>
</table>
<!-- END legend -->
