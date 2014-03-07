<!-- BEGIN news_source_1 -->
	<!-- BEGIN newsrow -->
		<!-- BEGIN newsrow_img -->
		<div style="border:solid 2px #FFFFFF;"><a href="/ru/news/{news_source_1.newsrow.NEWS_ID}">{news_source_1.newsrow.NEWS_IMG}</a></div>
		<!-- END newsrow_img -->
		<div class="news_block_date">{news_source_1.newsrow.NEWS_DATE}</div>
		<div class="news_block_title"><a href="/ru/news/{news_source_1.newsrow.NEWS_ID}">{news_source_1.newsrow.NEWS_TITLE}</a></div>
		<div class="news_block_sep"></div>
		<!-- BEGIN newsfull -->
		<!-- END newsfull -->
	<!-- END newsrow -->
	<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td style="background:url(/templates/corruption/images/news_bg_link_bl.png) no-repeat right; height:26px; text-align:right; padding-right:16px;"><a href="/ru/news/" style="text-decoration:underline; color:#FFFFFF;">все новости >></a></td></tr></table>
<!-- END news_source_1 -->

<!-- BEGIN news_source_2 -->
	<!-- BEGIN newsrow -->
		<!-- BEGIN newsrow_img -->
		<div style="border:solid 2px #FFFFFF;"><a href="/ru/jkhnews/{news_source_2.newsrow.NEWS_ID}">{news_source_2.newsrow.NEWS_IMG}</a></div>
		<!-- END newsrow_img -->
		<p class="news_block_date">{news_source_2.newsrow.NEWS_DATE}</p>
		<p class="news_block_title"><a href="/ru/jkhnews/{news_source_2.newsrow.NEWS_ID}">{news_source_2.newsrow.NEWS_TITLE}</a></p>
		<div class="news_block_sep"></div>
		<!-- BEGIN newsfull -->
		<!-- END newsfull -->
	<!-- END newsrow -->
	<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td style="background:url(/templates/corruption/images/news_bg_link_red.png) no-repeat right; height:26px; text-align:right; padding-right:16px;"><a href="/ru/jkhnews/" style="text-decoration:underline; color:#FFFFFF;">все новости >></a></td></tr></table>
<!-- END news_source_2 -->

<!-- BEGIN news_source_3 -->
	<div style="border-bottom: solid 2px #ececed; margin: 20px 0px 20px 0px;">
	<!-- BEGIN newsrow -->
		<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
			<td style="padding-right:25px;">
				<p style="line-height: 20px; text-align: justify;">{news_source_3.newsrow.NEWS_TEXT}</p>
				<p class="darkblue">{news_source_3.newsrow.NEWS_TITLE}</p>
		<div style="background:url(/templates/yconcil/images/button_allimg.png) no-repeat left; margin-bottom:15px;">
			<div style="display:table-cell; width:87px; height:23px; vertical-align:middle; padding-right:20px; padding-left:15px; padding-bottom:4px;"><a href="/ru/leaders/" title="Мнение Лидеров" style="color:#FFFFFF;">Читать все</a></div>
		</div>
			</td>
			<td align="right" valign="bottom">{news_source_3.newsrow.NEWS_IMG}</td>
		</tr></table>
		<!-- BEGIN newsfull -->
		<!-- END newsfull -->
	<!-- END newsrow -->
	</div>
<!-- END news_source_3 -->
