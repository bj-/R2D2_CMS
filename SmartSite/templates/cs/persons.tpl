<div class="textwrapper">
	<a href="include-long.htm" onclick="return hs.htmlExpand(this, { objectType: 'ajax', contentId: 'highslide-html-8' } )"
			class="highslide">
	</a>
	<div class="highslide-html-content" id="highslide-html-8" style="padding: 15px; width: auto">
	    <div class="highslide-body" style="padding: 10px"></div>
	</div>

</div>
<script type="text/javascript">
</script>
<!-- BEGIN hidereports -->
<!--11
<!-- END hidereports -->
<h4>sRoot</h4>
<form>
	<table width='100%'>
		<tr>
			<td align='right'>
				<table width="300px" border="0">
					<tr>
						<td>From:</td>
						<td>
							<input type="checkbox" id="UseDateFrom" value="1" checked />
							<input type="date" id="DateFrom" value="{CURRENTDATEONEMONAGO}" />
							<input type="time" id="TimeFrom" value="00:00" />
						</td>
					</tr>
					<tr>
						<td>Until:</td>
						<td>
							<input type="checkbox" id="UseDateTo" value="1" checked />
							<input type="date" id="DateTo" value="{CURRENTDATENEXT}" />
							<input type="time" id="TimeTo" value="00:00" />
						</td>
					</tr>
				</table>
			</td>
			<td>
				FIO: <input type="radio" id="UseFio" name="UseSenOrFio" value="useFIO" checked /> 
				<select id="FIO">
					<!-- BEGIN row_person -->
					<option value="{row_person.GUID}">{row_person.LASTNAME} {row_person.FIRSTNAME} {row_person.MIDDLENAME} ({row_person.SENSORSERNO})</option>
					<!-- END row_person -->
				</select><br />
				Sen: <input type="radio" id="UseSen" name="UseSenOrFio" value="useSEN" /> 
				<select id="SEN">
					<!-- BEGIN row_sensor -->
					<option value="{row_sensor.GUID}">{row_sensor.SENSORSERNO} ({row_sensor.LASTNAME} {row_sensor.FIRSTNAME} {row_sensor.MIDDLENAME})</option>
					<!-- END row_sensor -->
				</select>
			</td>
			<td align='center'>
				<a style="cursor:pointer;" OnClick="showGo();">Show<br />must<br />Start</a>
			</td>
		</tr>
	</table>
	<table width='100%'>
		<tr>
			<td>
				<a style="cursor:pointer;" OnClick="showGo('persons_about', 'persons_about');">О персоне</a>
				<a style="cursor:pointer;" OnClick="clear_content('#persons_about');">[X]</a>
			</td>
			<td>
				<a style="cursor:pointer;" OnClick="showGo('persons_norms', 'persons_norms');">Валидность норм (ср.кач.)</a>
				<a style="cursor:pointer;" OnClick="clear_content('#persons_norms');">[X]</a><br />
				<a style="cursor:pointer;" OnClick="showGo('persons_gpnorms', 'persons_gpnorms');">Групповые нормы</a>
				<a style="cursor:pointer;" OnClick="clear_content('#persons_gpnorms');">[X]</a>
			</td>
			<td>
				<a style="cursor:pointer;" OnClick="showGo('persons_medinsp', 'persons_medinsp');">Медосмотры</a>
				<a style="cursor:pointer;" OnClick="clear_content('#persons_medinsp');">[X]</a>
			</td>
			<td>
				<a style="cursor:pointer;" OnClick="showGo('report_CheckHIDbyWagon', 'report_CheckHIDbyWagon', 'NoFilters');">Поправте HID (на блоке)</a>
				<a style="cursor:pointer;" OnClick="clear_content('#report_CheckHIDbyWagon');">[X]</a><br />
				<a style="cursor:pointer;" OnClick="showGo('report_CheckHIDbyDate', 'report_CheckHIDbyDate', 'NoFilters');">Поправте HID (По датам)</a>
				<a style="cursor:pointer;" OnClick="clear_content('#report_CheckHIDbyDate');">[X]</a>
			</td>
		</tr>
	</table> 
	<table width='100%'>
		<tr>
			<td>
				<a style="cursor:pointer;" OnClick="showGo('report_HIDquality', 'report_HIDquality', 'NoFilters');">Качество сигнала с гарнитуры</a>
				<a style="cursor:pointer;" OnClick="clear_content('#report_HIDquality');">[X]</a><br />
				<a style="cursor:pointer;" OnClick="showGo('report_HIDqualityStat', 'report_HIDqualityStat', 'NoFilters');">Качество сигнала с гарнитуры (Среднее)</a>
				<a style="cursor:pointer;" OnClick="clear_content('#report_HIDqualityStat');">[X]</a>
			</td>
			<td>
				<a style="cursor:pointer;" OnClick="showGo('report_HIDBattery', 'report_HIDBattery', 'NoFilters');">Заряд батарейки</a>
				<a style="cursor:pointer;" OnClick="clear_content('#report_HIDBattery');">[X]</a><br />
				<a style="cursor:pointer;" OnClick="showGo('report_HIDBatteryList', 'report_HIDBatteryList', 'NoFilters');">Заряд батарейки (падение списком)
				<a style="cursor:pointer;" OnClick="clear_content('#report_HIDBatteryList');">[X]</a></a>
			</td>
			<td>
				<a style="cursor:pointer;" OnClick="showGo('report_RRCount', 'report_RRCount', 'NoFilters');">Полученно РРов (шт за день)</a>
				<a style="cursor:pointer;" OnClick="clear_content('#report_RRCount');">[X]</a><br />
				<a style="cursor:pointer;" OnClick="showGo('report_RRCountByWagon', 'report_RRCountByWagon', 'NoFilters');">Полученно РРов (шт за день по вагонам)</a>
				<a style="cursor:pointer;" OnClick="clear_content('#report_RRCountByWagon');">[X]</a><br />
				<a style="cursor:pointer;" OnClick="showGo('report_RRList', 'report_RRList', 'NoFilters');">Получено РРов (Списком)</a>
				<a style="cursor:pointer;" OnClick="clear_content('#report_RRList');">[X]</a>
			</td>
		</tr>
	</table> 
	<table width='100%'>
		<tr>
			<td>
				<a style="cursor:pointer;" OnClick="showGo('report_SleepAndStressSignals', 'report_SleepAndStressSignals', 'NoFilters');">Сигналы Монотония и Стресс в вагоне и оператору</a>
				<a style="cursor:pointer;" OnClick="clear_content('#report_SleepAndStressSignals');">[X]</a>
			</td>
		</tr>
	</table>

	<table width='100%'>
		<tr>
			<td><a style="cursor:pointer;" OnClick="showGo('test', 'Frame1');">[-]Выданные сигналы</a></td>
			<td><a style="cursor:pointer;" OnClick="showGo('test', 'Frame1');">[-]Смены</a></td>
			<td><a style="cursor:pointer;" OnClick="showGo('test', 'Frame1');">[-]Ношение гарнитуры</a></td>
			<td>[-]Нахождение на составах</td>
		</tr>
	</table> 
</form>

<div id='persons_about'></div>
<div id='persons_usedwagons'></div>
<div id='persons_norms'></div>
<div id='persons_gpnorms'></div>
<div id='persons_medinsp'></div>
<div id='report_CheckHIDbyWagon'></div>
<div id='report_CheckHIDbyDate'></div>
<div id='report_HIDBattery'></div>
<div id='report_HIDBatteryList'></div>
<div id='report_RRCount'></div>
<div id='report_RRCountByWagon'></div>
<div id='report_RRList'></div>
<div id='report_SleepAndStressSignals'></div>
<div id='report_HIDquality'></div>
<div id='report_HIDqualityStat'></div>
<div id='Frame1'></div>


<h3>Сводная ведомость для геноцида:</h3>
<!-- BEGIN hidereports -->
-->
<!-- END hidereports -->
<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='100%'>
	<tr>        
		<th title="Группы пользователя (линия/парк/депо)">Group</th>
		<th>Вредитель</th>
		<th colspan="2">HID</th>
		<th colspan="2"><a OnClick="show_content('/dynamic/proxy.php?page=Persons&params=ShowAlert=BAT', '#shPersons');" style="cursor:pointer;" Title="Показать только проблемные">Bat</a></th>
		<th><a OnClick="show_content('/dynamic/proxy.php?page=Persons&params=ShowAlert=FW', '#shPersons');" style="cursor:pointer;" Title="Показать только проблемные">FW</a></th>
		<th><a OnClick="show_content('/dynamic/proxy.php?page=Persons&params=ShowAlert=LastActivity', '#shPersons');" style="cursor:pointer;" Title="Показать только проблемные">Last Activity</a></th>
		<th>Position</th>
	</tr>
	<!-- BEGIN row -->
	<tr>
		<td style="{row.S_HIDCONNECTED}{row.S_ROLE}{row.S_FIRED}" align="center">
			<span title="Группы: {row.GROUPS}">{row.LINE}</span>
			<div name="d{row.ID}_Prop" id="d{row.ID}_Prop" style="position:absolute;display:none;margin-left:-0px;margin-top:10px;background-color:white;padding:10px;border:5px;box-shadow: -1px -1px 5px 5px rgba(0, 0, 0, .2);color:black;font-weight:normal;">
				<a style="cursor:pointer;font-weight:bold;text-align:right;" OnClick="HideDiv('d{row.ID}_Prop');">[ CLOSE ]</a>
				<h3 align="left">ФИО: {row.LASTNAME} {row.FIRSTNAME} {row.MIDDLENAME} ({row.SENSORSERNO})</h3>
				<table width="700px">
					<tr>
						<th>Название</th>
						<th>Значение</th>
					</tr>
					<tr>
						<td>Группы</td>
						<td>{row.GROUPS}</td>
					</tr>
					<tr>
						<td>Роль</td>
						<td style="{row.S_ROLE}">{row.ROLE} ({row.ROLE_CODE})</td>
					</tr>
					<tr>
						<td>Последний медосмотр</td>
						<td>{row.LAST_MED_EXAM}</td>
					</tr>
					<tr>
						<td>Состояние</td>
						<td>{row.STATE}</td>
					</tr>
					<tr>
						<td>Качество сигнала</td>
						<td>{row.HID_QUALITY_STR}</td>
					</tr>
					<tr>
						<td>Батарейка</td>
						<td>Последний заряд: {row.BATTERY_LEVEL}; {row.BAT_LOST_TEXT}</td>
					</tr>
					<tr>
						<td>MAC Адрес</td>
						<td>{row.MAC_ADDRESS}</td>
					</tr>
					<tr>
						<td>Другие гарнитуры пользователя:</td>
						<td>{row.MULTI_HID}</td>
					</tr>
					<tr>
						<td>Версия прошивки</td>
						<td>{row.HID_FW}; {row.HID_FW13_LASTDATE}</td>
					</tr>
					
					<tr>
						<td>История гарнитур:</td>
						<td>{row.HID_HISTORY}</td>
					</tr>
					<tr>
						<td>Person Guid:</td>
						<td>{row.PERSON_GUID}</td>
					</tr>
					<tr>
						<td>Sensor Guid:</td>
						<td>{row.HID_GUID}</td>
					</tr>
					<tr>
						<td>User Guid:</td>
						<td>{row.USER_GUID}</td>
					</tr>
					<tr>
						<td>Даты персоны</td>
						<td>Создание: {row.DATE_P_CREATED}; Записано в БД: {row.DATE_P_WRITTEN}</td>
					</tr>
					<tr>
						<td>Даты Пользователя</td>
						<td>Создание: {row.DATE_U_CREATED}; Записано в БД: {row.DATE_U_WRITTEN}</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
					</tr>
				</table>
				<b>Wiki:</b> <textarea cols="100" rows="3" class="xx-small"> 
* ФИО: {row.LASTNAME} {row.FIRSTNAME} {row.MIDDLENAME}  ({row.SENSORSERNO}; FW ver.: {row.HID_FW}) {{collapse(Детально)
Группы: {row.GROUPS}
Последний медосмотр: {row.LAST_MED_EXAM}
Состояние: {row.STATE}
Качество сигнала: {row.HID_QUALITY_STR} 
MAC Адрес: {row.MAC_ADDRESS}
Другие гарнитуры пользователя: {row.MULTI_HID}
Версия прошивки: {row.HID_FW}; {row.HID_FW13_LASTDATE}
История гарнитур: {row.HID_HISTORY}
Батарейка: Последний заряд: {row.BATTERY_LEVEL}; {row.BAT_LOST_TEXT}
Person Guid: {row.PERSON_GUID}
Sensor Guid: {row.HID_GUID}
User Guid: {row.USER_GUID}
}}
</textarea>

			</div>
			</td>
		<td style="cursor:pointer;{row.S_HIDCONNECTED}{row.S_ROLE}{row.S_FIRED}{row.S_MULTI_HID}" onclick="ShowHideDiv('d{row.ID}_Prop');" Title="{row.MULTI_HID}">
			{row.LASTNAME} {row.FIRSTNAME} {row.MIDDLENAME} {row.IMG_USTATE}
		</td>
		<td style="{row.S_HIDCONNECTED}{row.S_ROLE}{row.S_FIRED}"  align="center" Title="{row.HID_QUALITY_STR}" nowrap>
			<a href="/dynamic/proxy.php?rnd={RANDOM}&page=report_HIDqualityStat&prms=DateFrom={CURRENTDATEONEMONAGO};DateTo={CURRENTDATENEXT};uGuid={row.USER_GUID};ShowFilters=false" onclick="return hs.htmlExpand(this, { slideshowGroup: 'hidqualitystat-group', objectType: 'ajax', contentId: 'highslide-html-8' } )" style="cursor:pointer;color:black; ">
				{row.HID_QUALITY}
			</a>
		</td>
		<td style="{row.S_HIDCONNECTED}{row.S_ROLE}{row.S_FIRED}{row.S_MAC_ADDRESS}"  align="center" nowrap>
			<span title="Last Med exam: [{row.LAST_MED_EXAM}]; МАС адрес: [{row.MAC_ADDRESS}]">
				<a href="/dynamic/proxy.php?rnd={RANDOM}&page=report_HIDquality&prms=DateFrom={CURRENTDATEONEMONAGO};DateTo={CURRENTDATENEXT};uGuid={row.USER_GUID};ShowFilters=false" onclick="return hs.htmlExpand(this, { slideshowGroup: 'hidquality-group', objectType: 'ajax', contentId: 'highslide-html-8' } )" style="cursor:pointer;color:black; ">
					{row.SENSORSERNO}
				</a>
			</span>
		</td>
		<td style="{row.S_HIDCONNECTED}{row.S_ROLE}{row.S_FIRED}{row.S_HID_BAT}" align='center'>
			<a href="/dynamic/proxy.php?rnd={RANDOM}&page=report_HIDBattery&prms=DateFrom={CURRENTDATEONEMONAGO};DateTo={CURRENTDATENEXT};uGuid={row.USER_GUID};ShowFilters=false" onclick="return hs.htmlExpand(this, { slideshowGroup: 'hidbatstat-group', objectType: 'ajax', contentId: 'highslide-html-8' } )" style="cursor:pointer;color:black; ">
				{row.BAT_IMG}
			</a>
		</td>
		<td style="{row.S_HIDCONNECTED}{row.S_ROLE}{row.S_FIRED}" align='right'>
			<a href="/dynamic/proxy.php?rnd={RANDOM}&page=report_HIDBatteryList&prms=DateFrom={CURRENTDATEONEMONAGO};DateTo={CURRENTDATENEXT};uGuid={row.USER_GUID};ShowFilters=false" onclick="return hs.htmlExpand(this, { slideshowGroup: 'hidbatlevels-group', objectType: 'ajax', contentId: 'highslide-html-8' } )" style="cursor:pointer;color:black; ">
				{row.BATTERY_LEVEL}
			</a>
		</td>
		<td style="{row.S_HIDCONNECTED}{row.S_ROLE}{row.S_FIRED}{row.S_FWVERALERT}" align='center' title="{row.HID_FW13_LASTDATE}">{row.HID_FW}</td>
		<td style="{row.S_HIDCONNECTED}{row.S_DRIVERCONNAGO}{row.S_ROLE}{row.S_FIRED}" align='center'>{row.LAST_ACTIVITY}</td>
		<td style="{row.S_HIDCONNECTED}{row.S_ROLE}{row.S_FIRED}">{row.POSITION}</td>
	</tr>
	<!-- END row -->
</table>

HID Versions stat: v. 
	<!-- BEGIN row_fw_stat -->
	{row_fw_stat.VERSION}: {row_fw_stat.COUNT} pcs;
	<!-- END row_fw_stat -->

{ARTICLE}
{TEMP}

<!-- BEGIN legend -->
<h3>Легенда</h3>
<ul>
<li>"Вредитель" – ФИО и текущее состояние (состояние показывает только у тех кто на связи. На связи - красит в зеленый фон.).</li>
<li>"BAT" – Последний уровень заряда батарейки.</li>
<li>"FW" – покрашенная в красный фон – гаринтура выходившая на связь в течение последнего месяца, но у которой не последняя прошивка.</li>
<li>"Last Activity" – Дата последней активности. Покрашенная в красный текст – гаринтура не выходила на связь более 3-х месяцев.</li>
<li>"Position" – текущее местоположение: "вагон (блок), станция (линия)".</li>
<li>Клик по колонам - "FW" / "Last Activity" – показать только отмеченные как проблемные в выбранной колонке.</li>
</ul>
<!-- END legend -->
