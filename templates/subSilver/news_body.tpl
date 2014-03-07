{SAVED}
<!-- BEGIN news_source_1 -->
	<!-- BEGIN newsrow -->
	<div style="position: relative;">
	<div class="edit-buttons">
		{news_source_1.newsrow.EDIT}
	</div>
		<table width="98%" border="0" cellspacing="0" cellpadding="5" style="background-image: url(/pic/news_bg.gif);"><tr>
		<td width="170px" height="125px" align="center" valign="middle">{news_source_1.newsrow.NEWS_IMG}</td>
		<td><strong>{news_source_1.newsrow.NEWS_DATE} {news_source_1.newsrow.NEWS_TIME}</strong>,&nbsp;&nbsp;&nbsp;{news_source_1.newsrow.NEWS_SOURCE}<br />
		<strong><a href="/ru/news/{news_source_1.newsrow.NEWS_ID}">{news_source_1.newsrow.NEWS_TITLE}</a></strong><br />
		{news_source_1.newsrow.NEWS_TEXT}{news_source_1.newsrow.NEWS_FULL}</td>
		</tr></table>
	</div>
	<!-- END newsrow -->
<br />
<!-- END news_source_1 -->



<!-- BEGIN news_source_2 -->
	<!-- BEGIN newsrow -->
	<div style="position: relative;">
	<div class="edit-buttons">
		{news_source_2.newsrow.EDIT}
	</div>
	<table width="98%" style="background-image: url(/pic/news_bg.gif);" border="0" cellspacing="0" cellpadding="10"><tr><td height="125px">
		<strong>{news_source_2.newsrow.NEWS_DATE}</strong>,&nbsp;&nbsp;&nbsp;{news_source_1.newsrow.NEWS_SOURCE}<br />
		<strong><a href="/ru/massmedia/{news_source_2.newsrow.NEWS_ID}">{news_source_2.newsrow.NEWS_TITLE}</a></strong><br />
		{news_source_2.newsrow.NEWS_TEXT}{news_source_2.newsrow.NEWS_FULL}
	</td></tr></table>
	</div>
	<!-- END newsrow -->
<br />
<!-- END news_source_2 -->


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

<!-- BEGIN show_news_source_1 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><strong>{show_news_source_1.NEWS_DATE} {show_news_source_1.NEWS_TIME}</strong>&nbsp;&nbsp;&nbsp;{show_news_source_1.NEWS_SOURCE}</td><td align="right">{show_news_source_1.EDIT}</td></tr></table>
<p><strong>{show_news_source_1.NEWS_TITLE}</strong></p>
{show_news_source_1.NEWS_IMG}{show_news_source_1.NEWS_TEXT}
<!-- END show_news_source_1 -->

<!-- BEGIN show_news_source_2 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><strong>{show_news_source_2.NEWS_DATE}</strong>&nbsp;&nbsp;&nbsp;{show_news_source_2.NEWS_SOURCE}</td><td align="right">{show_news_source_2.EDIT}</td></tr></table>
<p><strong>{show_news_source_2.NEWS_TITLE}</strong></p>
{show_news_source_2.NEWS_IMG}{show_news_source_2.NEWS_TEXT}
<!-- END show_news_source_2 -->