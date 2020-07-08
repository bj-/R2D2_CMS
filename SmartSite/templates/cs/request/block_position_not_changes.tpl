Дата: {CURRENTDATE} Время: {CURRENTTIME}
<table cellspacing='1px' cellpadding='0px' border='0px' class='tbl' width='100%'>
	<tr>
		<th>BlockSerNo</th>
		<th>Train</th>

		<th>Wagon</th>
		<th>Connnected</th>
		<th>Position</th>
		<th>Pos Last Changed time; min ago; Formated</th>
		<th>Second Wagon</th>
		<th>Second Connnected</th>
		<th>Second Position</th>
		<th>Second Pos Last Changed time; min ago; Formated</th>
	</tr>

	<!-- BEGIN row -->
	<tr style=''>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_RENAME_ERROR}'>
			{row.BLOCKSERIALNO}
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_RENAME_ERROR}'>
			{row.TRAIN}
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_RENAME_ERROR}'>
			{row.BLOCK}
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_RENAME_ERROR}'>
			{row.CONNECTED}
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_RENAME_ERROR} {row.STYLE_POSITION_CHANGE_ALERT}'>
			{row.STATION_NAME}
			{row.TEST}
		</td>
		<td style='{row.STYLE_CONNECTED} {row.STYLE_CONNECTED_ALERT} {row.STYLE_RENAME_ERROR} {row.STYLE_POSITION_CHANGE_ALERT}'>
			{row.POSITION_CHANGED_TIME}; {row.POSITION_CHANGED_TIME_AGO} min ago; ~ {row.POSITION_CHANGED_TIME_AGO_FORMATED}
		</td>
		<td style='{row.STYLE_CONNECTED_SECOND} {row.STYLE_CONNECTED_SECOND_ALERT} {row.STYLE_RENAME_ERROR}'>
			{row.SECOND_WAGON}
		</td>
		<td style='{row.STYLE_CONNECTED_SECOND} {row.STYLE_CONNECTED_SECOND_ALERT} {row.STYLE_RENAME_ERROR}'>
			{row.CONNECTED_SECOND}
		</td>
		<td style='{row.STYLE_CONNECTED_SECOND} {row.STYLE_CONNECTED_SECOND_ALERT} {row.STYLE_RENAME_ERROR}'>
			{row.STATION_NAME_SECOND}
		</td>
		<td style='{row.STYLE_CONNECTED_SECOND} {row.STYLE_CONNECTED_SECOND_ALERT} {row.STYLE_RENAME_ERROR} {row.STYLE_POSITION_CHANGE_SECOND_ALERT}'>
			{row.POSITION_CHANGED_SECOND_TIME}; {row.POSITION_CHANGED_SECOND_TIME_AGO} min ago; ~ {row.POSITION_CHANGED_SECOND_TIME_AGO_FORMATED}
		</td>
	</tr>
	<!-- END row -->
</table>

{ARTICLE}
{TEMP}

<!-- BEGIN legend -->
<h2>Легенда:</h2>

<table class='tbl'>
	<!--tr>
		<td></td>
		<td></td>
		<td></td>
	</tr-->
</table>
<!-- END legend -->

