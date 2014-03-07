

<div id="divGalleryPage">
	<div style="clear:both"></div>
</div>


<!-- BEGIN cat_list -->

<div class="highslide-gallery175">
	<div><a href="/ru/gallery/{cat_list.CAT_PATH}-{cat_list.CAT_ID}/" title="{cat_list.CAT_NAME}" class="label">{cat_list.CAT_NAME}</a><br /><br /></div>
	<ul>
	<!-- BEGIN img_list -->
		<li style="background-color:#FFFFFF; border:0px;">
			<div class="thumbwrapper" style="display:table-cell; text-align:center; vertical-align:middle; height:135px; width:175px;">
				<div class="gallery-cat-edit">
					{switch_gallery.gallery_img_list.DEL_ITEM}
				</div>
				<a href="/img/gallery/{cat_list.img_list.IMG_CAT_ID}/{cat_list.img_list.IMG_PATH}" class="highslide" onclick="return hs.expand(this, { slideshowGroup: {cat_list.img_list.IMG_CAT_ID} } )" title="{cat_list.img_list.IMG_NAME}">
				<img style="box-shadow:0px 0px 6px #aaaaaa;" src="/img/gallery/{cat_list.img_list.IMG_CAT_ID}/sm/{cat_list.img_list.IMG_PATH}" width="{cat_list.img_list.IMG_SM_WIDTH}" height="{cat_list.img_list.IMG_SM_HEIGHT}" alt="{cat_list.img_list.IMG_NAME}" /></a>
				<div class="highslide-heading">
					<strong>{cat_list.img_list.IMG_NAME}</strong><br />
				</div>
				<div class="highslide-caption">
				    {cat_list.img_list.IMG_DESC}
				</div>
			</div>
				<!--div style="line-height:13px;">{cat_list.img_list.IMG_NAME}</div-->
		</li>
	<!-- END img_list -->
	</ul>
	<div style="clear:both"></div>
<div style="text-align:right; margin-top:15px;">
	<div style="float:right; background:url(/templates/yconcil/images/button_allimg.png) no-repeat right;">
		<div style="display:table-cell; width:87px; height:23px; vertical-align:middle; padding-right:20px; padding-bottom:4px;"><a href="/ru/gallery/{cat_list.CAT_PATH}-{cat_list.CAT_ID}/" title="{cat_list.CAT_NAME}" style="color:#FFFFFF;">cмотреть все</a></div>
	</div>
	<div style="clear:right"></div>
</div>
</div>
<br /><br />
<!-- END cat_list -->

<!-- BEGIN cat_preview -->
<!-- Одна категория с переходжом по страницам -->
<script type="text/javascript"> 
jQuery(function(){
 
jQuery(".highslide-gallery100").jCarouselLite({
		btnNext: "#gal_preview_right",
		btnPrev: "#gal_preview_left",
		scroll:	3,
		visible: 8,
		mouseWheel: true,
//		circular:	false
	});
 
 
});
</script>
 
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color:#b5c4dc;">
	<tr>
		<td width="39">
			<img id="gal_preview_left" src="/templates/cherepanov/images/gal_arr_left.png" width="39" height="111" border="0" onmouseover="document.getElementById('gal_preview_left').src='/templates/cherepanov/images/gal_arr_left_on.png'" onmouseout="document.getElementById('gal_preview_left').src='/templates/cherepanov/images/gal_arr_left.png'" />
		</td>
		<td>
<div class="highslide-gallery100">
	<!--div><a href="/ru/gallery/{cat_list.CAT_PATH}-{cat_list.CAT_ID}/" title="{cat_list.CAT_NAME}" class="label">{cat_list.CAT_NAME}</a><br /><br /></div-->
	<ul>
	<!-- BEGIN img_list -->
		<li style="background-color:#b5c4dc; border:0px;">
			<div class="thumbwrapper" style="display:table-cell; text-align:center; vertical-align:middle; height:97px; width:120px;">
				<div class="gallery-cat-edit">
					{switch_gallery.gallery_img_list.DEL_ITEM}
				</div>
				<a href="/img/gallery/{cat_preview.img_list.IMG_CAT_ID}/{cat_preview.img_list.IMG_PATH}" class="highslide" onclick="return hs.expand(this, { slideshowGroup: {cat_preview.img_list.IMG_CAT_ID} } )" title="{cat_preview.img_list.IMG_NAME}">
				<img style="box-shadow:0px 0px 6px white; border:2px solid white;" src="/img/gallery/{cat_preview.img_list.IMG_CAT_ID}/sm/{cat_preview.img_list.IMG_PATH}" width="{cat_preview.img_list.IMG_SM_WIDTH}" height="{cat_preview.img_list.IMG_SM_HEIGHT}" alt="{cat_preview.img_list.IMG_NAME}" /></a>
				<div class="highslide-heading">
					<strong>{cat_preview.img_list.IMG_NAME}</strong><br />
				</div>
				<div class="highslide-caption">
				    {cat_preview.img_list.IMG_DESC}
				</div>
			</div>
				<!--div style="line-height:13px;">{cat_list.img_list.IMG_NAME}</div-->
		</li>
	<!-- END img_list -->
	</ul>
	<div style="clear:both"></div>
</div>
		</td>
		<td width="39">
			<img id="gal_preview_right" src="/templates/cherepanov/images/gal_arr_right.png" width="39" height="111" border="0" onmouseover="document.getElementById('gal_preview_right').src='/templates/cherepanov/images/gal_arr_right_on.png'" onmouseout="document.getElementById('gal_preview_right').src='/templates/cherepanov/images/gal_arr_right.png'" />
		</td>
	</tr>
</table>
<!-- END cat_preview -->

<!-- BEGIN cat_preview_img -->
<script type="text/javascript"> 
jQuery(function(){
 
jQuery(".highslide-gallery100").jCarouselLite({
		btnNext: "#gal_preview_right",
		btnPrev: "#gal_preview_left",
		scroll:	{PREVIEW_SCROLL},
		visible: {PREVIEW_VISIBLE},
		mouseWheel: true,
//		circular:	false
	});
 
 
});
</script>
 
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color:#b5c4dc;">
	<tr>
		<td width="39">
			<img id="gal_preview_left" src="/templates/cherepanov/images/gal_arr_left.png" width="39" height="111" border="0" onmouseover="document.getElementById('gal_preview_left').src='/templates/cherepanov/images/gal_arr_left_on.png'" onmouseout="document.getElementById('gal_preview_left').src='/templates/cherepanov/images/gal_arr_left.png'" />
		</td>
		<td>
<div class="highslide-gallery100">
	<!--div><a href="/ru/gallery/{cat_list.CAT_PATH}-{cat_list.CAT_ID}/" title="{cat_list.CAT_NAME}" class="label">{cat_list.CAT_NAME}</a><br /><br /></div-->
	<ul>
	<!-- BEGIN img_list -->
		<li style="background-color:#b5c4dc; border:0px;">
			<div class="thumbwrapper" style="display:table-cell; text-align:center; vertical-align:middle; height:97px; width:120px;">
				<div class="gallery-cat-edit">
					{switch_gallery.gallery_img_list.DEL_ITEM}
				</div>
				<a href="/img/gallery/{cat_preview.img_list.IMG_CAT_ID}/{cat_preview.img_list.IMG_PATH}" class="highslide" onclick="return hs.expand(this, { slideshowGroup: {cat_preview.img_list.IMG_CAT_ID} } )" title="{cat_preview.img_list.IMG_NAME}">
				<img style="box-shadow:0px 0px 6px white; border:2px solid white;" src="/img/gallery/{cat_preview.img_list.IMG_CAT_ID}/sm/{cat_preview.img_list.IMG_PATH}" width="{cat_preview.img_list.IMG_SM_WIDTH}" height="{cat_preview.img_list.IMG_SM_HEIGHT}" alt="{cat_preview.img_list.IMG_NAME}" /></a>
				<div class="highslide-heading">
					<strong>{cat_preview.img_list.IMG_NAME}</strong><br />
				</div>
				<div class="highslide-caption">
				    {cat_preview.img_list.IMG_DESC}
				</div>
			</div>
				<!--div style="line-height:13px;">{cat_list.img_list.IMG_NAME}</div-->
		</li>
	<!-- END img_list -->
	</ul>
	<div style="clear:both"></div>
</div>
		</td>
		<td width="39">
			<img id="gal_preview_right" src="/templates/cherepanov/images/gal_arr_right.png" width="39" height="111" border="0" onmouseover="document.getElementById('gal_preview_right').src='/templates/cherepanov/images/gal_arr_right_on.png'" onmouseout="document.getElementById('gal_preview_right').src='/templates/cherepanov/images/gal_arr_right.png'" />
		</td>
	</tr>
</table>
<!-- END cat_preview_img -->

<!-- BEGIN cat_preview_video -->
<div class="highslide-gallery100" id="article_video">
	<!-- BEGIN img_list -->
	<div style="float:left; padding:10px; position:relative; width:130px; height:150px; overflow:hidden;">
		<div style="position:absolute; right:0px; top:0px;">
			{cat_preview_video.img_list.DEL_ITEM}
		</div>
		<a href="http://www.youtube.com/embed/{cat_preview_video.img_list.VIDEO_ID}?rel=0&amp;wmode=transparent" 
				onclick="return hs.htmlExpand(this, {objectType: 'iframe', width: 560, height: 345, 
				allowSizeReduction: false, wrapperClassName: 'draggable-header no-footer', 
				preserveContent: false, objectLoadTime: 'after'})"
		        class="highslide">
			<img src="{cat_preview_video.img_list.VIDEO_THUMB}" /><br />
		   <div style="text-align:center; width:100%;">
				{cat_preview_video.img_list.IMG_NAME}
			</div>
		</a>
	</div>
	<!-- END img_list -->
	<div style="clear:both"></div>
</div>
<script type="text/javascript"> 
jQuery(function(){
 
jQuery(".highslide-gallery100").jCarouselLite({
		btnNext: "#gal_preview_right",
		btnPrev: "#gal_preview_left",
		scroll:	2,
		visible: 4,
		mouseWheel: true,
//		circular:	false
	});
 
 
});
</script>
<!-- END cat_preview_video -->