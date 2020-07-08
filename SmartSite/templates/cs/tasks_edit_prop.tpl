<!--input type="hidden" name="TaskID" value="{TASK_ID}" />
<input type="hidden" name="TaskPropID" value="{TASK_PROP_ID}" /-->

<b>Add/Edit:</b> <select name="Property_t{TASK_ID}">
	<option value="0">Не выбрано...</option>
	<!-- BEGIN listitem -->
	  <!-- BEGIN group -->
		<option value="{listitem.group.CODE}" {listitem.group.SELECTED} style="font-weight:bold;">&nbsp;== {listitem.group.NAME} ==</option>
	  <!-- END group -->
	  <!-- BEGIN property -->
		<option value="{listitem.property.CODE}" {listitem.property.SELECTED}> &nbsp; &nbsp; &nbsp; &nbsp; {listitem.property.NAME}</option>
	  <!-- END property -->
	<!-- END listitem -->
</select>
Значение: <input name="Value_t{TASK_ID}" value="{PROP_VALUE}" maxlength="200" size="100" />
<br />
<div align="right"> 
	<a OnClick="saveTaskProp('{TASK_ID}', '{TASK_PROP_ID}');" style="cursor:pointer;">
		[Сохранить]
	</a>
	&nbsp;
	<a OnClick="$('#TaskPropEdit_{TASK_ID}').html('');" style="cursor:pointer;">
		[Отмена]
	</a>
	&nbsp; &nbsp; &nbsp;
	<a style="cursor:pointer;color:red;" OnClick="deleteTaskProp('{TASK_ID}', '{TASK_PROP_ID}');">
		[Удалить строку]
	</a>
</div>
<div id="ResultsSP_{TASK_ID}"></div>

{ARTICLE}
{TEMP}
