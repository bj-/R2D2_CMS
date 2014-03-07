<!-- BEGIN cruch_calendar_show -->
<div style="width: 230px;" align="left">
	<img src="/pic/cruch_calendar.png" width="230" height="19" alt="Церковный календарь" border="0" />
	<div style="margin-top: 12px;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
			<td align="left" valign="top">{cruch_calendar_show.CRUCH_ICON}</td>
			<td valign="top"><div style="margin-left: 15px;">{cruch_calendar_show.CRUCH_WEEK} {cruch_calendar_show.CRUCH_POST}<hr style="height: 1px;" /></div>
			</td>
		</tr></table>
		<p style="color: #a30d0a;" align="left">{cruch_calendar_show.CRUCH_DAY}</p>
		<p align="left">{cruch_calendar_show.CRUCH_HOLIDAY}</p>
		<hr style="height: 1px;" />
		<!-- BEGIN cruch_saints -->
			<img src="/pic/cruch_star.gif" width="14" height="14" alt="" border="0" align="left" />&nbsp;{cruch_calendar_show.cruch_saints.CRUCH_SAINTS}
			<hr style="height: 1px;" />
		<!-- END cruch_saints -->
		<!-- BEGIN cruch_righting -->
			{cruch_calendar_show.cruch_righting.CRUCH_RIGHTING} 
		<hr style="height: 1px;" />
		<!-- END cruch_righting -->
	</div>
</div>
<!-- END cruch_calendar_show -->
