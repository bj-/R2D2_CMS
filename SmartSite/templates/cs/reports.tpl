<form>
	<table width='100%'>
		<tr>
			<!-- BEGIN dates -->
			<td align='right'>
				<table width="100%">
					<tr>
						<td>From:</td>
						<td>
							<input type="checkbox" id="UseDateFrom" value="1" checked />
							<input type="date" id="DateFrom" value="{CURRENTDATEONEMONAGO}" />
							<!-- BEGIN timeFrom -->
							<input type="time" id="TimeFrom" value="00:00" />
							<!-- END timeFrom -->
						</td>
					</tr>
					<tr>
						<td>Until:</td>
						<td>
							<input type="checkbox" id="UseDateTo" value="1" checked />
							<input type="date" id="DateTo" value="{CURRENTDATENEXT}" />
							<!-- BEGIN timeTo -->
							<input type="time" id="TimeTo" value="00:00" />
							<!-- END timeTo -->
						</td>
					</tr>
				</table>
			</td>
			<!-- END dates -->
			<!-- BEGIN persons -->
			<td>&nbsp;</td>
			<td>
				FIO: <input type="radio" id="UseFio" name="UseSenOrFio" value="useFIO" checked /> 
				<select id="FIO">
					<option value="---">Выбрать персону</option>
					<!-- BEGIN row_person -->
					<option value="{persons.row_person.GUID}">{persons.row_person.LASTNAME} {persons.row_person.FIRSTNAME} {persons.row_person.MIDDLENAME} ({persons.row_person.SENSORSERNO})</option>
					<!-- END row_person -->
				</select><br />
				Sen: <input type="radio" id="UseSen" name="UseSenOrFio" value="useSEN" /> 
				<select id="SEN">
					<option value="---">Выбрать персону</option>
					<!-- BEGIN row_sensor -->
					<option value="{persons.row_sensor.GUID}">{persons.row_sensor.SENSORSERNO} ({persons.row_sensor.LASTNAME} {row_sensor.FIRSTNAME} {persons.row_sensor.MIDDLENAME})</option>
					<!-- END row_sensor -->
				</select>
			</td>
			<!-- END persons -->
			<!-- BEGIN sensors -->
			<td>
				<table>
					<tr>
						<td>Сенсор:</td>
						<td>
							<select id="sensor">
								<option value="0">Все</option>
								<!-- BEGIN row -->
								<option value="{sensors.row.GUID}">{sensors.row.SERNO}{sensors.row.DESCRIPTION}</option>
								<!-- END row -->
							</select>
						</td>
					</tr>
				</table>
			</td>
			<!-- END sensors -->
			<!-- BEGIN gobutton -->
			<td align='center'>
				<a style="cursor:pointer;" OnClick="showGo('{REPORT_PAGE}', 'ReportPage', 'NoFilters');"><b>Compile<br />Report!</b></a>
			</td>
			<!-- END gobutton -->
		</tr>
	</table>
	<!-- BEGIN veh_groups -->
	<table width='40%'>
		<tr>
			<td>Группа ТС:</td>
			<td>
				<select id="VehGrp">
					<option value="0">Все</option>
					<!-- BEGIN veh_row_grp -->
					<option value="{veh_groups.veh_row_grp.CODE}">{veh_groups.veh_row_grp.NAME}</option>
					<!-- END veh_row_grp -->
				</select>
			</td>
		</tr>
	</table>
	<!-- END veh_groups -->
<input type="hidden" name="Server" value="{SERVER}"> 
</form>

{REPORT}

<!-- BEGIN legend_comment -->
<h3>Легенда</h3>
<ul>
<li>Все хорошо</li>
</ul>
<!-- END legend_comment -->
