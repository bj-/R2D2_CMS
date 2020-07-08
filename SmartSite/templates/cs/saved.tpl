<!-- BEGIN saved -->
<h3>Сохранено</h3>
<!-- END saved -->

<!-- BEGIN show_content -->
<script type="text/javascript">
show_content('/dynamic/proxy.php?rnd={RANDOM}&page={show_content.PAGE_NAME}{show_content.PARAMS}', '#{show_content.DIV_ID}');
</script>
<!--Страница:{show_content.PAGE_NAME}; Объект: {show_content.PARAMS}-->
<!-- END show_content -->


<!-- BEGIN row -->
<!-- BEGIN task_details -->
<div id="TaskEdit_{row.TASK_ID}"></div>
				Детализация по таске NEW:
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
				<div id='TaskPropEdit_{row.TASK_ID}'>
					<br />
					<a style="cursor:pointer;" OnClick="show_content('/dynamic/proxy.php?rnd={RANDOM}&page=TasksPropEdit&prms=Action=New;TaskID={row.TASK_ID}', '#TaskPropEdit_{row.TASK_ID}');">
						[+] Добавить деталей по таске
					</a>
				</div>

<p><br /></p>
<!-- END task_details -->
<!-- END row -->
