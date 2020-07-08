<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/xml; charset=utf-8" />
<link href="/templates/cs/css/common.css" rel="stylesheet" type="text/css" />
<title>Штурман - Жалоба</title>
<script type="text/javascript" src="/script/r2d2_functions.js"></script>
<script type="text/javascript" src="/script/shturman_functions.js"></script>
<script type="text/javascript" src="/script/jquery/js/jquery-3.2.1.min.js"></script>
</head>
<body>

<script type="text/javascript">
function checkAndShow( name, val )
{
	var div_id = name + '_detailed';
	var oRadio = document.getElementsByName(name);
	for(var i = 0; i < oRadio.length; i++) 
	{ 
		if(oRadio[i].value == val) {
			//alert (oRadio[i].value + "--" + val);
			oRadio[i].checked = true;
		}
	}	
	if ( val == "yes" )
	{
		document.getElementById(div_id).style.display='block';
	}
	else
	{
		document.getElementById(div_id).style.display='none';
	}
}

function checkedIt ( name )
{
//	var oCheck = document.getElementsByName(name);
//	alert (document.getElementsByName(name).checked);
	if ( $('input[name='+name+']').prop('checked') )
	{
		$('input[name='+name+']').prop('checked', false);
	}
	else
	{
		$('input[name='+name+']').prop('checked', true);
	}
//	alert (isChecked);
}
</script>

<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='1200'>
	<tr>        
		<th colspan="2">Донесение по эксплуатации системы АСПМ «ШТУРМАН»</th>
	</tr>
	<tr>        
		<td width="20%">Дата/время донесения</td>
		<td><input name="Date" value="{DATE}" type="date" /> <input name="Date" value="{DATE}" type="time" /> (остаьте пустым, если дата/время текущее)</td>
	</tr>
	<tr>        
		<td>ФИО Машиниста</td>
		<td>{SELECT_DRIVER_BY_FIO}</td>
	</tr>
	<tr>        
		<th colspan="2">1. ГАРНИТУРА</th>
	</tr>
	<tr>        
		<td valign="top">1.1. На Гарнитуре присутствуют механические повреждения</td>
		<td valign="top">
			<span OnClick="checkAndShow('HIDDamaged', 'yes')" style="cursor:pointer;"><input type="radio" name="HIDDamaged" value="yes" {HID_DAMGAGED_YES_CHECKED_CHECKED} /> да</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDDamaged', 'no')" style="cursor:pointer;"><input type="radio" name="HIDDamaged" value="no" {HID_DAMGAGED_NO_CHECKED} /> нет</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDDamaged', 'uncknown')" style="cursor:pointer;"><input type="radio" name="HIDDamaged" value="uncknown" {HID_DAMGAGED_UNCK_CHECKED} /> не знаю</span><br />
			<div id="HIDDamaged_detailed" style="display:none;">
				В случае ответа «да» отметить, что именно повреждено:<br />
				<input type="checkbox" name="HIDDamagedBody" {HID_DAMGAGED_BODY_CHECKED} /><span OnClick="checkedIt('HIDDamagedBody')" style="cursor:pointer;">корпус</span> &nbsp; &nbsp; <br />
				<input type="checkbox" name="HIDDamagedButton" {HID_DAMGAGED_BUTTON_CHECKED} />кнопка &nbsp; &nbsp; <br />
				<input type="checkbox" name="HIDDamagedCable" {HID_DAMGAGED_CABLE_CHECKED} />кабель &nbsp; &nbsp; <br />
				<input type="checkbox" name="HIDDamagedConnector" {HID_DAMGAGED_CONNECTOR_CHECKED} />разъем &nbsp; &nbsp; <br />
				<input type="checkbox" name="HIDDamagedClip" {HID_DAMGAGED_CLIP_CHECKED} />клипса &nbsp; &nbsp; <br />
				<input type="checkbox" name="HIDDamagedSpring" {HID_DAMGAGED_SPRING_CHECKED} />пружина клипсы &nbsp; &nbsp; <br />
				<input type="checkbox" name="HIDDamagedEmbouchure" {HID_DAMGAGED_EMBOUCHURE_CHECKED} />амбушюры<br />
				Другое <br /><input type="text" name="HIDDamagedOther" value="{HID_DAMGAGED_OTHER}" size="100" width="200" />
			</div>
	</tr>
	<tr>        
		<td valign="top">1.2. Индикация «Датчик неисправен» (частые попеременные сигналы красного и голубого светодиодов)</td>
		<td valign="top">
			<span OnClick="checkAndShow('HIDSelfBroken', 'yes')" style="cursor:pointer;"><input type="radio" name="HIDSelfBroken" value="yes" {HID_SELFBROKEN_YES_CHECKED} /> да</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDSelfBroken', 'no')" style="cursor:pointer;"><input type="radio" name="HIDSelfBroken" value="no" {HID_SELFBROKEN_NO_CHECKED} /> нет</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDSelfBroken', 'uncknown')" style="cursor:pointer;"><input type="radio" name="HIDSelfBroken" value="uncknown" {HID_SELFBROKEN_UNCK_CHECKED} /> не знаю</span><br />
			<div id="HIDSelfBroken_detailed" style="display:none;">
				Подробнее (при необходимости) <br /><input type="text" name="HIDSelfBrokenDetails" value="{HID_SELFBROKEN_DETAILS}" size="100" width="200" />
			</div>
		</td>
	</tr>
	<tr>        
		<td valign="top">1.3. Появлялась надпись «Датчик неисправен»</td>
		<td valign="top">
			<span OnClick="checkAndShow('HIDMsgBroken', 'yes')" style="cursor:pointer;"><input type="radio" name="HIDMsgBroken" value="yes" {HID_MSGBROKEN_YES_CHECKED}/> да</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDMsgBroken', 'no')" style="cursor:pointer;"><input type="radio" name="HIDMsgBroken" value="no" {HID_MSGBROKEN_NO_CHECKED} /> нет</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDMsgBroken', 'uncknown')" style="cursor:pointer;"><input type="radio" name="HIDMsgBroken" value="uncknown" {HID_MSGBROKEN_UNCK_CHECKED}/> не знаю</span><br />
			<div id="HIDMsgBroken_detailed" style="display:none;">
				Подробнее (при необходимости) <br /><input type="text" name="HIDMsgBrokenDetails" value="{HID_MSGBROKEN_DETAILS}" size="100" width="200" />
			</div>
		</td>
	</tr>
	<tr>        
		<td valign="top">1.4. Гарнитура проверялась на  терминале</td>
		<td valign="top">
			<span OnClick="checkAndShow('HIDChecked', 'yes')" style="cursor:pointer;"><input type="radio" name="HIDChecked" value="yes" {HID_CHECKED_YES_CHECKED} /> да</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDChecked', 'no')" style="cursor:pointer;"><input type="radio" name="HIDChecked" value="no" {HID_CHECKED_NO_CHECKED} /> нет</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDChecked', 'uncknown')" style="cursor:pointer;"><input type="radio" name="HIDChecked" value="uncknown" {HID_CHECKED_UNCK_CHECKED} /> не знаю</span><br />
			<div id="HIDChecked_detailed" style="display:none;">
				В случае ответа «да» результат общей последней оценки %: <input type="text" name="HIDCheckedValue" value="{HID_CHECKED_VALUE}" size="5" width="3" />
			</div>
		</td>
	</tr>
	<tr>        
		<th colspan="2">2. ФУТЛЯР</th>
	</tr>
	<tr>        
		<td>&nbsp;</td>
		<td valign="top"><input type="checkbox" name="HIDNoCase" value="yes" {HIDCASE_NOTEXIST_CHECKED} /> футляр отсутствует</td>
	</tr>
	<tr>        
		<td valign="top">2.1. На футляре присутствуют механические повреждения </td>
		<td valign="top">
			<span OnClick="checkAndShow('HIDCaseDamaged', 'yes')" style="cursor:pointer;"><input type="radio" name="HIDCaseDamaged" value="yes" {HIDCASE_DAMAGED_YES_CHECKED} /> да</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDCaseDamaged', 'no')" style="cursor:pointer;"><input type="radio" name="HIDCaseDamaged" value="no" {HIDCASE_DAMAGED_NO_CHECKED} /> нет</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDCaseDamaged', 'uncknown')" style="cursor:pointer;"><input type="radio" name="HIDCaseDamaged" value="uncknown" {HIDCASE_DAMAGED_UNCK_CHECKED} /> не знаю</span><br />
			<div id="HIDCaseDamaged_detailed" style="display:none;">
				В случае ответа «да» описать, что именно сломано: <br />
				<input type="checkbox" name="HIDCaseDamagedConnectorIn" {HIDCASE_DAMAGED_CONNECTORIN} /> разъем для подзарядки футляра<br />
				<input type="checkbox" name="HIDCaseDamagedConnectorOut" {HIDCASE_DAMAGED_CONNECTOROUT} /> штекер кабеля футляр-гарнитура<br />
				<input type="checkbox" name="HIDCaseDamagedMgCapLoop" {HIDCASE_DAMAGED_MGCAPLOOP} /> магнитная петля крышки футляра<br />
				<input type="checkbox" name="HIDCaseDamagedMgBoxLoop" {HIDCASE_DAMAGED_MGBOXLOOP}/> магнитная петля коробки футляра<br />
				другое <br /><input type="text" name="HIDCaseDamagedOther" value="{HIDCASE_DAMAGED_OTHER}" size="100" width="200" />
			</div>
		</td valign="top">
	</tr>
	<tr>        
		<td valign="top">2.2. Уровень заряда не превышает 40 %</td>
		<td valign="top">
			<span OnClick="checkAndShow('HIDCaseCharge', 'yes')" style="cursor:pointer;"><input type="radio" name="HIDCaseCharge" value="yes" {HIDCASE_CHARGE_YES_CHECKED} /> да</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDCaseCharge', 'no')" style="cursor:pointer;"><input type="radio" name="HIDCaseCharge"  value="no" {HIDCASE_CHARGE_NO_CHECKED} /> нет</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('HIDCaseCharge', 'uncknown')" style="cursor:pointer;"><input type="radio" name="HIDCaseCharge"  value="uncknown" {HIDCASE_CHARGE_UNCK_CHECKED} /> не знаю</span><br />
			<div id="HIDCaseCharge_detailed" style="display:none;">
				В случае ответа «да» максимальный уровень заряда %: <input type="text" name="HIDCaseChargeValue" value="{HIDCASE_CHARGE_VALUE}" size="5" width="3" />
			</div>
		</td>
	</tr>
	<tr>        
		<th colspan="2">3. БОРТОВОЕ ОБОРУДОВАНИЕ</th>
	</tr>
	<tr>        
		<td valign="top">3.0. Вагон</td>
		<td valign="top">
			<table>
				<tr>
					<td colspan="4">№ вагона [SELECT]</td>
				</tr>
				<tr>
					<td>Дата:</td>
					<td><input type="date" name="BODate"  value="{BO_DATE}" /></td>
					<td>Время: с</td>
					<td><input type="time" name="BOTimeFrom"  value="{BO_TIME_FROM}" /> по <input type="time" name="BOTimeTo"  value="{BO_TIME_TO}" /><br /></td>
				</tr>
				<tr>
					<td>Маршрут</td>
					<td><input type="text" name="Route"  value="{ROUTE}"  size="5" width="5" /></td>
					<td>Состав</td>
					<td><input type="text" name="Coupling"  value="{COUPLING}"  size="20" width="100"  /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>        
		<td valign="top">3.1. На бортовом оборудовании присутствуют механические повреждения</td>
		<td valign="top">
			<span OnClick="checkAndShow('BODamaged', 'yes')" style="cursor:pointer;"><input type="radio" name="BODamaged" value="yes" {BO_DAMAGED_YES_CHECKED} /> да</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('BODamaged', 'no')" style="cursor:pointer;"><input type="radio" name="BODamaged"  value="no" {BO_DAMAGED_NO_CHECKED} /> нет</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('BODamaged', 'uncknown')" style="cursor:pointer;"><input type="radio" name="BODamaged"  value="uncknown" {BO_DAMAGED_UNCK_CHECKED} /> не знаю</span><br />
			<div id="BODamaged_detailed" style="display:none;">
				В случае ответа «да» описать характер повреждений: <br /><input type="text" name="BODamagedDetails" value="{BO_DAMAGED_DETAILS}" size="100" width="200" />
			</div>
		</td>
	</tr>
	<tr>        
		<td valign="top">3.2. При движении по линии на мониторе более 10 минут подряд отображается информационное сообщение «Нет связи с сервером»</td>
		<td valign="top">
			<span OnClick="checkAndShow('BONoLink', 'yes')" style="cursor:pointer;"><input type="radio" name="BONoLink" value="yes" {BO_NO_LINK_YES_CHECKED} /> да</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('BONoLink', 'no')" style="cursor:pointer;"><input type="radio" name="BONoLink"  value="no" {BO_NO_LINK_NO_CHECKED} /> нет</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('BONoLink', 'uncknown')" style="cursor:pointer;"><input type="radio" name="BONoLink"  value="uncknown" {BO_NO_LINK_UNCK_CHECKED} /> не знаю</span><br />
			<div id="BONoLink_detailed" style="display:none;">
				Подробнее (при необходимости): <br /><input type="text" name="BONoLinkDetails" value="{BO_DAMAGED_DETAILS}" size="100" width="200" />
			</div>
		</td>
	</tr>
	<tr>        
		<td valign="top">3.3. Происходит самопроизвольная перезагрузка бортового оборудования</td>
		<td valign="top">
			<span OnClick="checkAndShow('BOReboot', 'yes')" style="cursor:pointer;"><input type="radio" name="BOReboot" value="yes" {BO_REBOOT_YES_CHECKED} /> да</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('BOReboot', 'no')" style="cursor:pointer;"><input type="radio" name="BOReboot"  value="no" {BO_REBOOT_NO_CHECKED} /> нет</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('BOReboot', 'uncknown')" style="cursor:pointer;"><input type="radio" name="BOReboot"  value="uncknown" {BO_REBOOT_UNCK_CHECKED} /> не знаю</span><br />
			<div id="BOReboot_detailed" style="display:none;">
				В случае ответа «да» указать частоту перезагрузок: <br />
				~ <input type="number" name="BORebootCount" value="{BO_REBOOT_COUNT}" size="3" width="5" /> шт 
				в 
				<input type="radio" name="BORebootPeriod" value="hour" {BO_REBOOT_PERIOD_HOUR_CHECKED} /> В час &nbsp; &nbsp; 
				<input type="radio" name="BORebootPeriod"  value="smena" {BO_REBOOT_PERIOD_SMENA_CHECKED} /> В смену &nbsp; &nbsp; 
				<input type="radio" name="BORebootPeriod"  value="day" {BO_REBOOT_PERIOD_DAY_CHECKED} /> В день<br />
				Подробнее (при необходимости): <br /><input type="text" name="BORebootPeriodDetails" value="{BO_REBOOT_PERIOD_DETAILS}" size="100" width="200" />
			</div>
		</td>
	</tr>
	<tr>        
		<td valign="top">3.4. На мониторе «зависает» или отображается неполная информация</td>
		<td valign="top">
			<span OnClick="checkAndShow('BOBoviDamaged', 'yes')" style="cursor:pointer;"><input type="radio" name="BOBoviDamaged" value="yes" {BO_BOVI_DAMAGED_YES_CHECKED} /> да</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('BOBoviDamaged', 'no')" style="cursor:pointer;"><input type="radio" name="BOBoviDamaged"  value="no" {BO_BOVI_DAMAGED_NO_CHECKED} /> нет</span> &nbsp; &nbsp; 
			<span OnClick="checkAndShow('BOBoviDamaged', 'uncknown')" style="cursor:pointer;"><input type="radio" name="BOBoviDamaged"  value="uncknown" {BO_BOVI_DAMAGED_UNCK_CHECKED} /> не знаю</span><br />
			<div id="BOBoviDamaged_detailed" style="display:none;">
				В случае ответа «да» отметить, что именно происходило:<br />
				<input type="checkbox" name="BOBoviDamagedStations" {BO_BOVI_DAMAGED_STATIONS_CHECKED} /> состав находится в движении, а станции не меняются<br />
				<input type="checkbox" name="BOBoviDamagedButtons" {BO_BOVI_DAMAGED_BUTTONS_CHECKED} /> монитор не реагирует на нажатие кнопок<br />
				<input type="checkbox" name="BOBoviDamagedDisplay" {BO_BOVI_DAMAGED_DISPLAY_CHECKED} /> изображение мутное или нечитаемое <br />
				<input type="checkbox" name="BOBoviDamagedNotWork" {BO_BOVI_DAMAGED_NOT_WORK_CHECKED} /> изображение отсутствует (черный экран)<br />
				<input type="checkbox" name="BOBoviDamagedRoute" {BO_BOVI_DAMAGED_ROUTE_CHECKED} /> отсутствует или невозможно выставить маршрут<br />
				<input type="checkbox" name="BOBoviDamagedLight" {BO_BOVI_DAMAGED_LIGHT_CHECKED} /> отсутствует подсветка кнопок и монитора<br />
				<input type="checkbox" name="BOBoviDamagedDriver" {BO_BOVI_DAMAGED_DRIVER_CHECKED} /> фамилия машиниста отсутствует в списке машинистов<br />
				Подробнее (при необходимости): <br /><input type="text" name="BOBoviDamagedDetails" value="{BO_BOVI_DAMAGED_DETAILS}" size="100" width="200" />
			</div>
		</td>
	</tr>
	<tr>        
		<td valign="top">3.5. Другое (опишите подробно: двигается ли состав, подсвечены ли кнопки и монитор, реагирует ли блок на нажатие кнопок, есть ли на мониторе информация о машинисте, маршруте и станции, включена ли Гарнитура, есть ли сообщение «Поправьте датчик» и т.п.)</td>
		<td valign="top">
			<textarea name="BOOther" cols="102" rows="10" maxlength="2000">{BO_OTHER}</textarea>
		</td>
	</tr>	<tr>        
		<td valign="top">3.6. Вагоны, на которых были замечены аналогичные неполадки</td>
		<td valign="top">
			<table>
				<tr>
					<th>№ вагона</th>
					<th>Дата</th>
					<th>Время</th>
					<th>Жалоба</th>
				</tr>
				<tr>
					<td>[SELECT]</td>
					<td>
						<input type="date" name="BOAddDate"  value="{BO_DATE}" />, 
					</td>
					<td>
						c <input type="time" name="BOAddTimeFrom"  value="{BO_TIME_FROM}" /> 
						по <input type="time" name="BOAddTimeTo"  value="{BO_TIME_TO}" /><br />
					</td>
					<td>
						<select name="BOAddComplainType" style="width:275px;">
							<option value="0">Выбрать</option>
							<option value="BODamaged"> &nbsp; &nbsp; Механические повреждения</option>
							<option value="BONoLink"> &nbsp; &nbsp; Нет связи с сервером (на линии)</option>
							<option value="BOReboot"> &nbsp; &nbsp; Перезагрузка бортового оборудования</option>
							<option value="BOBoviDamagedStations"> &nbsp; &nbsp; Состав находится в движении, а станции не меняются</option>
							<option value="BOBoviDamagedButtons"> &nbsp; &nbsp; Монитор не реагирует на нажатие кнопок</option>
							<option value="BOBoviDamagedDisplay"> &nbsp; &nbsp; Изображение мутное или нечитаемое</option>
							<option value="BOBoviDamagedNotWork"> &nbsp; &nbsp; Изображение отсутствует (черный экран)</option>
							<option value="BOBoviDamagedRoute"> &nbsp; &nbsp; Отсутствует или невозможно выставить маршрут</option>
							<option value="BOBoviDamagedLight"> &nbsp; &nbsp; Отсутствует подсветка кнопок и монитора</option>
							<option value="BOBoviDamagedDriver"> &nbsp; &nbsp; Фамилия машиниста отсутствует в списке машинистов</option>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>        
		<th colspan="2">4. ПРОЧЕЕ</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top"><textarea name="Additional" cols="102" rows="20" maxlength="2000">{BO_DAMAGED_DETAILS}</textarea></td>
	</tr>
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
		<td>a</td>
		<td>b</td>
		<td>c</td>
	</tr>
</table>
<!-- END legend -->
