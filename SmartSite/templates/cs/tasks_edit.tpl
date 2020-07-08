<input type="hidden" name="TaskID_{TASK_ID}" value="{TASK_ID}" />
<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='100%'>
	<tr>
		<td>Срочность</td>
		<td>
			<select name="Severity_t{TASK_ID}">
				<option value="0">Не выбрано...</option>
				<!-- BEGIN row_severity -->
				<option value="{row_severity.CODE}" {row_severity.SELECTED}> &nbsp; &nbsp; {row_severity.NAME}</option>
				<!-- END row_severity -->
			</select>
		</td>
	</tr>
	<tr>
		<td>Статус</td>
		<td>
			<select name="Status_t{TASK_ID}">
				<option value="0">Не выбрано...</option>
				<!-- BEGIN row_status -->
				<option value="{row_status.CODE}" {row_status.SELECTED}> &nbsp; &nbsp; {row_status.NAME}</option>
				<!-- END row_status -->
			</select>
		</td>
	</tr>
	<tr>
		<td>Объект</td>
		<td>
			<select name="Object_t{TASK_ID}">
				<option value="0">Не выбрано...</option>
				<!-- BEGIN row_servers -->
				<option value="{row_servers.CODE}" {row_servers.SELECTED}> &nbsp; &nbsp; {row_servers.NAME}</option>
				<!-- END row_servers -->
			</select>
			 &nbsp; &nbsp; Тип: 
			<select name="Object_Type_t{TASK_ID}">
				<option value="0">Не выбрано...</option>
				<!-- BEGIN row_object_type -->
				<option value="{row_object_type.CODE}" {row_object_type.SELECTED}> &nbsp; &nbsp; {row_object_type.NAME}</option>
				<!-- END row_object_type -->
			</select>
		</td>
	</tr>
	<tr>
		<td>Исполнитель</td>
		<td>
			<select name="Assigned_t{TASK_ID}">
				<option value="0">Не выбрано...</option>
				<!-- BEGIN row_assigned -->
				<option value="{row_assigned.CODE}" {row_assigned.SELECTED}> &nbsp; &nbsp; {row_assigned.NAME} ({row_assigned.DESCRIPTION})</option>
				<!-- END row_assigned -->
			</select>
		</td>
	</tr>
	<tr>
		<td>Жалоба</td>
		<td>
			<select style="width:663px;" name="Complain_t{TASK_ID}">
				<option value="0">Жалоба ==&gt;</option>
				<!-- BEGIN template_complain -->
				<option value="{template_complain.ID}" {template_complain.SELECTED}>{template_complain.NAME}</option>
				<!-- END template_complain -->
       		</select>
			<div id="Complain_t{TASK_ID}_req" style="color:red;font-weight:bold;display:none;">Обязательно к заполнению. (Политкорреткный текст жалобы)</div>
		</td>
	</tr>
		<td>Subject</td>
		<td>
			<input name="Subject_t{TASK_ID}" value="{SUBJECT}" maxlength="200" size="90" />
			<div id="Subject_t{TASK_ID}_req" style="color:red;font-weight:bold;display:none;">Обязательно к заполнению.</div>
			<select style="width:150px;" name="Subject_tpl_t{TASK_ID}" OnChange="SetInputVal('Subject_t{TASK_ID}', $(this).find('option:selected').text(), $(this).find('option:selected').val());">
				<option value="0">Шаблон ==&gt;</option>
				<option value="1">В сцепке, но не выходит на связь</option>
				<option value="1">Изображение мутное/смазанное или не читаемое</option>
				<option value="1">Не работает монитор АСПМ</option>
				<option value="1">Состав на линии, а станции не менются</option>
				<option value="1">Не реагирует на нажатие кнопок</option>
				<option value="1">Отсутвует или невозможно выставить маршрут</option>
				<option value="1">Нет подсветки кнопок</option>
				<option value="1">Фамилия машиниста отсутвует в списке машинистов</option>
				<option value="1">Механические повреждения</option>
				<option value="1">На линии более 10 минут "Нет связи с сервером"</option>
				<option value="1">Самопроизвольная перезагрузка</option>
				<option value="1">Постоянно сообщение "Поправьте датчик"</option>
        		</select>
		</td>
	</tr>
	<tr>
		<td>Desigion</td>
		<td>
			<input name="Desigion_t{TASK_ID}" value="{DESIGION}" maxlength="200" size="90" />
			<select style="width:150px;" name="Subject_tpl_t{TASK_ID}" OnChange="SetInputVal('Desigion_t{TASK_ID}', $(this).find('option:selected').text(), $(this).find('option:selected').val());">
				<option value="0">Шаблон ==&gt;</option>
				<option value="1">Заменить БОВИ</option>
				<option value="1">Осмотр на месте</option>
				<option value="1">Осмотр на месте с комплектом ЗИП</option>
				<option value="1">Заменить ОБ</option>
				<option value="1">Наблюдать (ничего не делать)</option>
        		</select>
		</td>
	</tr>
	<tr>
		<td>Result</td>
		<td>
			<input name="Result_t{TASK_ID}" value="{RESULT}" maxlength="200" size="90" />
			<select style="width:150px;" name="Subject_tpl_t{TASK_ID}" OnChange="SetInputVal('Result_t{TASK_ID}', $(this).find('option:selected').text(), $(this).find('option:selected').val());">
				<option value="0">Шаблон ==&gt;</option>
				<option value="1">Ложная жалоба</option>
				<option value="1">Заменен БОВИ</option>
				<option value="1">Заменен ОБ</option>
				<option value="1">Включен рубильник</option>
				<option value="1">Заменен БА</option>
				<option value="1">Заменен Шнур к БОВИ</option>
				<option value="1">Замнене Шнур питания</option>
				<option value="1">Заменен БП</option>
        		</select>
		</td>
	</tr>
	<tr>
		<td>Issue Date Start</td>
		<td>
			Date: <input type="date" name="date_t{TASK_ID}" value="{ISSUE_DATE}" /> &nbsp; &nbsp; &nbsp; 
			Time: <input type="time" name="time_t{TASK_ID}" value="{ISSUE_TIME}" />
		</td>
	</tr>
	<tr>
		<td>Issue Date End</td>
		<td>
			Date: <input type="date" name="dateEnd_t{TASK_ID}" value="{ISSUE_DATE_END}" /> &nbsp; &nbsp; &nbsp; 
			Time: <input type="time" name="timeEnd_t{TASK_ID}" value="{ISSUE_TIME_END}" />
		</td>
	</tr>
	<tr>
		<td>Reporter</td>
		<td>
			<select name="Reporter_t{TASK_ID}">
				<option value="0">Не выбрано...</option>
				<!-- BEGIN row_reporters -->
				<option value="{row_reporters.CODE}" {row_reporters.SELECTED}> &nbsp; &nbsp; {row_reporters.NAME}</option>
				<!-- END row_reporters -->
			</select>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><a OnClick="saveTask('{TASK_ID}');" style="cursor:pointer;">Save</a></td>
	</tr>
</table>
<p><br /></p>
<div id="Result_{TASK_ID}"></div>

{ARTICLE}
{TEMP}
