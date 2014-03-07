{SAVED}
<!-- --------------------------
       Превью блоков
------------------------------->
<!-- BEGIN news_source_1 -->
	<!-- BEGIN newsrow -->
	<div style="position: relative; border-bottom:1px solid #d5d5d5; padding-bottom:10px;">
		<div class="edit-buttons">
			{news_source_1.newsrow.EDIT}
		</div>
		<div class="news_block_date" style="margin-top:15px;">{news_source_1.newsrow.NEWS_DATE} &nbsp; </div>
		<table width="98%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td align="center" valign="middle">{news_source_1.newsrow.NEWS_IMG}</td>
				<td style="vertical-align:top;" width="100%">
					<strong>{news_source_1.newsrow.NEWS_TITLE}</strong>
					<div style="margin-top:10px;">{news_source_1.newsrow.NEWS_TEXT}<!--{news_source_1.newsrow.NEWS_FULL}--></div>
					<!-- BEGIN newsfull -->
					<div style="text-align:right; background:url(/templates/yconcil/images/blue_arrow.png) no-repeat right; padding-right:10px; margin-top:10px;"><a href="/ru/news/{news_source_1.newsrow.NEWS_ID}" class="bluetext">Подробнее</a></div>
					<!-- END newsfull -->
				</td>
			</tr>
		</table>
	</div>
	<!-- END newsrow -->
<br />
<!-- END news_source_1 -->



<!-- BEGIN news_source_2 -->
	<!-- BEGIN newsrow -->
	<div style="position: relative; border-bottom:1px solid #d5d5d5; padding-bottom:10px;">
		<div class="edit-buttons">
			{news_source_2.newsrow.EDIT}
		</div>
		<div class="news_block_date" style="margin-top:15px;">{news_source_2.newsrow.NEWS_DATE} &nbsp; </div>
		<table width="98%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td align="center" valign="middle">{news_source_2.newsrow.NEWS_IMG}</td>
				<td style="vertical-align:top;" width="100%">
					<strong>{news_source_2.newsrow.NEWS_TITLE}</strong>
					<div style="margin-top:10px;">{news_source_2.newsrow.NEWS_TEXT}<!--{news_source_1.newsrow.NEWS_FULL}--></div>
					<!-- BEGIN newsfull -->
					<div style="text-align:right; background:url(/templates/yconcil/images/blue_arrow.png) no-repeat right; padding-right:10px; margin-top:10px;"><a href="/ru/news/{news_source_2.newsrow.NEWS_ID}" class="bluetext">Подробнее</a></div>
					<!-- END newsfull -->
				</td>
			</tr>
		</table>
	</div>
	<!-- END newsrow -->
<br />

<!-- END news_source_2 -->


<!-- BEGIN news_source_3 -->
	<!-- BEGIN newsrow -->
	<div style="position: relative; border-bottom:1px solid #d5d5d5; padding-bottom:10px;">
		<div class="edit-buttons">
			{news_source_3.newsrow.EDIT}
		</div>
		<table width="98%" border="0" cellspacing="0" cellpadding="5" style="background-image: url(/pic/news_bg.gif);">
		<tr>
		<td width="170px" height="125px" align="center" valign="middle">{news_source_3.newsrow.NEWS_IMG}</td>
		<td style="vertical-align:top;">
			<span class="darkblue label">{news_source_3.newsrow.NEWS_DATE} &nbsp; {news_source_3.newsrow.NEWS_TITLE}</span><br />
			<p>{news_source_3.newsrow.NEWS_TEXT}</p>
			<!-- BEGIN newsfull -->
			<div style="text-align:right; background:url(/templates/yconcil/images/blue_arrow.png) no-repeat right; padding-right:10px;"><a href="/ru/leaders/{news_source_3.newsrow.NEWS_ID}" class="bluetext">Подробнее</a></div>
			<!-- END newsfull -->
		</td>
		</tr>
		</table>
	</div>
	<!-- END newsrow -->
<br />
<!-- END news_source_1 -->



<!-- BEGIN news_source_1 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><strong>{news.NEWS_DATE}</strong></td><td align="right">{news.EDIT}</td></tr></table>
<p><strong>{news.NEWS_TITLE}</strong></p>
{news.NEWS_TEXT}
<!-- END news_source_1 -->

<!-- BEGIN news_source_2 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><strong>{news.NEWS_DATE}</strong></td><td align="right">{news.EDIT}</td></tr></table>
<p><strong>{news.NEWS_TITLE}</strong></p>
{news.NEWS_TEXT}
<!-- END news_source_2 -->

<!-- BEGIN news_source_3 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><strong>{news.NEWS_DATE}</strong></td><td align="right">{news.EDIT}</td></tr></table>
<p><strong>{news.NEWS_TITLE}</strong></p>
{news.NEWS_TEXT}
<!-- END news_source_3 -->

<!-- BEGIN show_news_source_1 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><strong>{show_news_source_1.NEWS_DATE}</strong>&nbsp;&nbsp;&nbsp;{show_news_source_1.NEWS_SOURCE}</td><td align="right">{show_news_source_1.EDIT}</td></tr></table>
<p><strong>{show_news_source_1.NEWS_TITLE}</strong></p>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>{show_news_source_1.NEWS_IMG}{show_news_source_1.NEWS_TEXT}</td></tr></table>
<div style="text-align:center;">
	{show_news_source_1.NEWS_VIDEO}
</div>
<!-- END show_news_source_1 -->

<!-- BEGIN show_news_source_2 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><strong>{show_news_source_2.NEWS_DATE}</strong><!--&nbsp;&nbsp;&nbsp;{show_news_source_2.NEWS_SOURCE}--></td><td align="right">{show_news_source_2.EDIT}</td></tr></table>
<p><strong>{show_news_source_2.NEWS_TITLE}</strong></p>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>{show_news_source_2.NEWS_IMG}{show_news_source_2.NEWS_TEXT}</td></tr></table>
<div style="text-align:center;">
	{show_news_source_2.NEWS_VIDEO}
</div>
<!-- END show_news_source_2 -->

<!-- BEGIN show_news_source_3 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			{show_news_source_3.NEWS_TEXT}
			<p><strong>{show_news_source_3.NEWS_TITLE}</strong></p>
		</td>
		<td align="right">
			{show_news_source_3.NEWS_IMG}
		</td>
		<td align="right" valign="top" width="35px;">{show_news_source_3.EDIT}</td>
	</tr>
</table>
<!-- END show_news_source_3 -->
