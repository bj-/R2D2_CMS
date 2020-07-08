<div id="Tasks_{OBJECT_ID}">
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=All', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ ALL ]</a> &nbsp; / &nbsp; 
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=Opened', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ Открытые ]</a> &nbsp; / &nbsp; 
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=Repair', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ В Ремонт ]</a> &nbsp; / &nbsp; 
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=Dev', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ Девелоперам ]</a> &nbsp; / &nbsp; 
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=Hardware', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ Железячникам ]</a> &nbsp; / &nbsp; 
<a onclick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=Tasks&prms=ObjectId={OBJECT_ID};ObjectType={OBJECT_TYPE};Filter=Bad', '#Tasks_{OBJECT_ID}');" style="cursor:pointer;">[ Глючные ]</a>
<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='100%'>
	<tr>
		<td><h3>Блок: {OBJECT_ID} ({OBJECT_TYPE}) (Жалобы по блокам)</h3></td>
		<td align="right"><a OnClick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=TasksEdit&prms=Action=New;ObjectID={OBJECT_ID};ObjectType=Block', '#AddTask');" style="cursor:pointer;">[+] Add Task</a></td>
	</tr>
</table>

<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='100%'>
	<tr>        
		<th><img src="/pic/ico/attention_2_24x24.png" title="Severity" width="16" height="16" border="0" /></th>
		<th><img src="/pic/ico/pulse-box_20x20.png" title="Status" width="16" height="16" border="0" /></th>
		<th>Вагон</th>
		<th>Жалоба</th>
		<th>Рекомендация</th>
		<th>Сделано</th>
		<th>Issue Date</th>
		<th>Assigned</th>
		<th title="Дата создания и закрытия">Created</th>
		<th title="Последний срок">DLn</th>
		<th>Reporter</th>
		<th>Author</th>
		<th>&nbsp;</th>
	</tr>
	<!-- BEGIN row -->
	<tr onclick="ShowHideDiv('{row.TASK_ID}_TaskProp');$('{row.TASK_ID}_TaskProp').attr('colspan',12);" style="cursor:pointer;">
		<td style="{row.STYLE_ROW}">{row.SEVERITY}</td>
		<td style="{row.STYLE_ROW}">{row.STATUS}</td>
		<td style="{row.STYLE_ROW}">{row.NAME}</td>
		<td style="{row.STYLE_ROW}" Title="">{row.SUBJECT}</td>
		<td style="{row.STYLE_ROW}">{row.DESIGION}</td>
		<td style="{row.STYLE_ROW}">{row.RESULT}</td>
		<td style="{row.STYLE_ROW}"><span title="C [{row.ISSUE_DATE}] по [{row.ISSUE_DATE_END}]">{row.ISSUE_DATE}</span></td>
		<td style="{row.STYLE_ROW}"><span title="{row.ASSIGNED_DESCRIPTION}">{row.ASSIGNED_NAME}</span></td>
		<td style="{row.STYLE_ROW}" title="Закрыта: {row.FINISHED}">{row.CREATED}</td>
		<td style="{row.STYLE_ROW}{row.STYLE_DEADLINE}" title="Последний срок: {row.DEADLINE_DATE}" align="right">{row.DEADLINE_DAYS}</td>
		<td style="{row.STYLE_ROW}">{row.REPORTER}</td>
		<td style="{row.STYLE_ROW}">{row.AUTHOR}</td>
		<td style="{row.STYLE_ROW}" OnClick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=TasksEdit&prms=Action=Edit;ID={row.TASK_ID}', '#TaskEdit_{row.TASK_ID}');" style="cursor:pointer;">
				<img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0">
		</td>

	</tr>
	<!--tr name="{row.TASK_ID}_TaskProp" id="{row.TASK_ID}_TaskProp" style="display:none;cursor:pointer;"-->
	<tr>
		<!--td colspan="10" name="{row.TASK_ID}_TaskProp" id="{row.TASK_ID}_TaskProp" style="display:none;"-->
		<td colspan="12" style="padding-left:50px;">
			<div name="{row.TASK_ID}_TaskProp" id="{row.TASK_ID}_TaskProp" style="display:none;">
			<span title="Текст жалобы не оскорбляющий чувств верующего">{row.COMPLAIN}</span>
				<div id="TaskEdit_{row.TASK_ID}"></div>
				<!-- BEGIN task_details -->
				Детализация по таске:
				<table cellspacing='1px' cellpadding='1px' border='0px' class='tbl' width='100%'>
					<tr>
						<th>Name</th>
						<th>Value</th>
						<th width="30px">&nbsp;</th>
						<th width="40px" style="cursor:pointer;" OnClick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=TasksPropEdit&prms=Action=New;TaskID={row.TASK_ID}', '#TaskPropEdit_{row.TASK_ID}');">[+]</th>
					</tr>
				      <!-- BEGIN row_prop -->
					<!-- BEGIN group -->
					<tr>
						<th colspan="4" align="left">
							<span title="{row.task_details.row_prop.group.DESCRIPTION}">{row.task_details.row_prop.group.NAME}</span>
						</th>
					</tr>
					<!-- END group -->
					<!-- BEGIN details -->
					<tr>
						<td style="padding-left:30px;{row.task_details.row_prop.details.STYLE_ROW}">{row.task_details.row_prop.details.NAME}</td>
						<td style="{row.task_details.row_prop.details.STYLE_ROW}">{row.task_details.row_prop.details.VALUE}</td>
						<td style="{row.task_details.row_prop.details.STYLE_ROW}" align="center">
							<img src="/pic/ico/calendar_16x16.png" title="Создано: [{row.task_details.row_prop.details.CREATED}]; Отредактировано: [{row.task_details.row_prop.details.LASTEDIT}]; Автор: [{row.task_details.row_prop.details.EDITOR}];">
						</td>
						<td style="{row.task_details.row_prop.details.STYLE_ROW}" align="center">
							<a style="cursor:pointer;" OnClick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=TasksPropEdit&prms=Action=Edit;TaskID={row.TASK_ID};PropID={row.task_details.row_prop.details.ID}', '#TaskPropEdit_{row.TASK_ID}');">
								<img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0">
							</a>
						</td>
					</tr>
					<!-- END details -->
				     <!-- END row_prop -->
				</table>
				<!-- END task_details -->
				<div id="TaskPropRedmineTask_{row.TASK_ID}" name="TaskPropRedmineTask_{row.TASK_ID}"></div>
				<div id='TaskPropEdit_{row.TASK_ID}'>
					<br />
					<a style="cursor:pointer;" OnClick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=TasksPropEdit&prms=Action=New;TaskID={row.TASK_ID}', '#TaskPropEdit_{row.TASK_ID}');">
						[+] Добавить деталей по таске
					</a>
				</div>
				<p><br /></p>
			</div>
		</td>
	</tr>
	<!--tr>
		<td colspan="#">
			Детализация по таске
		</td>
	</tr-->
	<!-- END row -->
</table>

{ARTICLE}
{TEMP}

<p></br /></p>
<div name="AddTask" id="AddTask"></div>


<h3>Легенда</h3>
<ul>
<li>!!! Настоятельно не рекомендуется редактировать таски в IE, т.к. он не знает полей с типом Дата/Время (в ИЕ их надо заполнять ручками в формате YYYY-MM-DD HH:MM), а при неверном заполнении таска не создастся.</li>
<li>В полях таски "Жалоба", "Рекомендация", "Сделано" - писать коротко.</li>
<li>Длина всех текстовых полей ограничена - Максимум 200 символов.</li>
<li>Reporter - от кого изначально пришла жалоба (Депо / Сами заметили / и т.п.)</li>
<li>Таски закрываются статусами "Ложная" / "Отклонена" / "Done" / "Удалена"</li>
<li>Закрытые таски - серое по серому</li>
<li>Назначена: кому адресована таска (исполнитель).</li>
<li>Клик по строке с таской - показать детализацию </li>
<li>В детализации таски можно создать сколько угодно дополнительных полей с информацией в т.ч. одинаковых по типу.</li>
<li>Если проперть случайно удалили и требуется ее вернуть - редактирование -&gt; Сохранить</li>
<li>Если проперть требуется удалить - Редактирование -&gt; Удалить строку</li>
<li>Цвет фона - исключительно для удобства чтения множества строк и ни на что не виляет.</li>
<li>[+] - добавить новое. [<img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0">] - редактирование (таски пли доп информации) </li>
<li>DDL "Шаблон ==&gt;" - позволяется быстро выбрать типовые жалобы/результаты работ. </li>
</ul>
<p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p>
<p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p><p></br /></p>
</div>