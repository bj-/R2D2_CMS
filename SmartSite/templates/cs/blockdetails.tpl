<div id="{BLOCKSERNO}_tasks">
{ARTICLE}
<div style="align:center">[ <a style="cursor:pointer;" OnClick="show_content('/dynamic/proxy.php?page=BlockDetails&params=Server={BLOCKSERNO};rnd={RANDOM}', '#{BLOCKSERNO}_tasks');">Refresh</a> ]</div>

<table cellspacing="1px" cellpadding="0px" width="100%">
	<tr>
		<th>A</th>
		<th>Status</th>
		<th>Block</th>
		<th>Action</th>
		<th>Service</th>
		<!--th>Source</th>
		<th>Target</th-->
		<th>Parameters</th>
		<th>Result</th>
		<!--th>Block Comment</th-->
		<th>UFile</th>
		<th>Date</th>
		<th>Author</th>
	</tr>

<!-- BEGIN row -->
	<tr style="{row.S_ROWSTYLE}">
		<td width="100px">
			<!-- BEGIN active -->
			[<a style="color:red;text-weght:bold;cursor:pointer;" OnClick="show_content('/dynamic/proxy.php?page=BlockDetails&params=Server={BLOCKSERNO};task_guid={row.GUID};Status=Active', '#{BLOCKSERNO}_tasks');" title='Active'>A</a>]
			<!-- END active -->
			<!-- BEGIN close -->
			[<a style="color:red;text-weght:bold;cursor:pointer;" OnClick="show_content('/dynamic/proxy.php?page=BlockDetails&params=Server={BLOCKSERNO};task_guid={row.GUID};Status=Closed', '#{BLOCKSERNO}_tasks');" title='Closed'>C</a>]
			<!-- END close -->
			<!-- BEGIN delete -->
			[<a style="color:red;text-weght:bold;cursor:pointer;" OnClick="show_content('/dynamic/proxy.php?page=BlockDetails&params=Server={BLOCKSERNO};task_guid={row.GUID};Status=Deleted', '#{BLOCKSERNO}_tasks');" title='Deleted'>D</a>]
			<!-- END delete -->
		</td>
		<td>{row.STATUS}</td>
		<td>{row.BLOCK}</td>
		<td>{row.ACTION}</td>
		<td>{row.SERVICE}</td>
		<td>
			{row.SOURCE}
			{row.TARGERT}
			{row.PARAMETERS}
		</td>
		<td><a title="{row.BLOCK_COMMENT}">{row.BLOCK_RESULT}</a></td>
		<td>
			<!-- BEGIN no_link -->
			{row.U_UPLOADED_FILE}
			<!-- END no_link -->
			<!-- BEGIN link -->
			<a href="{row.U_UPLOADED_FILE}" title="{row.U_UPLOADED_FILE}">{row.L_LINK}</a>
			<!-- END link -->
		</td>
		<td><a title="Added: {row.DATE_ADDED}; Passed: {row.DATE_PASSED}">{row.DATE_ADDED}<a/></td>
		<td><a onclick="testxxx()">{row.AUTHOR}</a></td>
	</tr>
<!-- END row -->
</table>


<h3>Add New</h3>

<table border="0">
	<tr>
		<td>
			<b>Выбрать блок<br />(один или несколько)</b><br />
			<select id="BlocksList" name="Blocks[]" multiple size="23" OnClick="BlockList()" style="width:150px;">
				<!-- BEGIN blockslist -->
				<option value ="{blockslist.BLOCKSERNO}" {blockslist.SELECTED}>{blockslist.BLOCKSERNO}</option>
				<!-- END blockslist -->
			</select>
		</td>
		<td style="padding-top:60px;" valign="top"><img src="/pic/arrow_3r_512x512.png" width="30" height="100" /></td>
		<td valign="top">
			<table border="0">
				<tr>
					<td colspan="2">
						<div id="actionsList">
						<b>Выбрать действие</b><br /><br />
						<!-- BEGIN actionslist -->
						<input type="radio" name="Action" OnClick="ShowParams('{actionslist.ACTION_NAME}');" value="{actionslist.ACTION_NAME}">{actionslist.ACTION} ({actionslist.DESCRIPTION})<br />
						<!-- END actionslist -->
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div id="params_NOTEXIST" style="display:none">
							<br /><br /><span style="color:red;"><b>Не реализовано (на сервере и блоке)</b></style>
						</div>
						<div id="params_NOTEXIST_BLOCK" style="display:none">
							<br /><br /><span style="color:red;"><b>Не реализовано (на стороне блока)</b></style>
						</div>
						<div id="params_serviceslist" style="display:none">
							<br /><b>Select Services:</b><br />
							<select id="ServicesList" name="serviceslist[]" OnClick="ServicesList()" multiple size="12">
								<!-- BEGIN serviceslist -->
								<option value ="{serviceslist.NAME}">{serviceslist.NAME_SHORT}</option>
								<!-- END serviceslist -->
							</select>
						</div>
					</td>
					<td>
						<div id="params_datetime_period" style="display:none">
							<b>Select Date and Time</b><br /><br />
							<table>
								<tr><td valign="top">Date:</td>
								<td><input type="date" name="DateTime_Date" /><br /><br /><br /></td></tr>
								<tr><td>Time:</td>
								<td><input type="time" name="DateTime_Time_From" value="00:00:00" />
								<br />- <br /><input type="time" name="DateTime_Time_To" value="23:59:59" /></td></tr>
							</table>
						</div>
						<div id="params_datetime" style="display:none">
							<b>Select Date / Period</b><br /><br />
							<table>
								<tr><td valign="top"><input type="radio" name="Date_Period" value="Date_One" checked>One Date:</td>
								<td><input type="date" name="Date_One" /><br /><br /><br /></td></tr>
								<tr><td><input type="radio" name="Date_Period" value="Date_Period">Period:</td>
								<td><input type="date" name="DatePeriod_From" />
								<br />- <br />
								<input type="date" name="DatePeriod_To" /></td></tr>
							</table>
						</div>
						<div id="params_files" style="display:none;">
							<b>Source and target files</b><br /><br />
							<table cellspacing="1px" cellpadding="0px">
								<tr>
									<td valign="top">Source</td>
									<td><input name='Source' type='text' value='' size='50' maxlength='200' /><br />
									Абсолютный путь к файлу, который необхоимо залить на сервер.
									</td>
								</tr>
								<tr><td valign="top">
									Target</td><td><input name='Target' type='text' value='' size='50' maxlength='200' /><br />
									Имя архива в который запаковать [имя].tar.gz (Если пусто - используется оригинальное имя)
								</td></tr>
							</table>
						</div>
					</td>
				</tr>
			</table>				
		</td>
		<td style="padding-top:60px;" valign="top"><img src="/pic/arrow_3r_512x512.png" width="30" height="100" /></td>
		<td valign="top" align="center">
			<br /><br /><br /><br /><a style="cursor:pointer;" onClick="saveRequest('#{BLOCKSERNO}_tasks');"><img src="/pic/school-bus_64x64.png" width="64" height="64" /><br><b>Order</b></a>
			<div id="SaveAttention" style="color:red;font-weight:bold;display:none;">
				То ли соли не хватает, <br />то ли тарелки. <br />Никак не могу составить заказ.
			</div>
		</td>
	</tr>
</table>
<input type="hidden" name="Server" value="{BLOCKSERNO}"> 

<h3>Summary</h3>
<div id="tAdd_BlockList"></div>
<div id="tAdd_Action"></div>
<div id="tAdd_ServicesList"></div>
<div id="tAdd_Parameters"></div>

<!--h3>SaveString</h3-->
<div id="savestr"></div>

</div>
<p></p><p></p><p></p><p></p><p></p>


{TEMP}
