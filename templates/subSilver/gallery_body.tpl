<!-- BEGIN switch_left_menu -->
<!-- END switch_left_menu -->

<!--script type="text/javascript" src="/script/highslide/highslide-with-gallery.min.js"></script-->
<script type="text/javascript" src="/script/highslide/highslide-with-gallery.min.js"></script>
<script type="text/javascript">
hs.graphicsDir = '/script/highslide/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.fadeInOut = true;
hs.dimmingOpacity = 0.8;
hs.outlineType = 'rounded-white';
//hs.captionEval = 'this.thumb.alt';
hs.marginBottom = 105; // make room for the thumbstrip and the controls
//hs.numberPosition = 'caption';
hs.numberPosition = 'heading';

// Add the slideshow providing the controlbar and the thumbstrip
hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
//	fixedControls: false,
	overlayOptions: {
		className: 'text-controls',
		position: 'bottom center',
		relativeTo: 'viewport',
		offsetY: -60
	},
	thumbstrip: {
		position: 'bottom center',
		mode: 'horizontal',
		relativeTo: 'viewport'
	}
});
</script>

<div id="item-add"><!-- загрузка фото-видео--></div>

{GALLERY_TEXT}

<!-- BEGIN gallery_content_menu -->
<table border="0" cellspacing="0" cellpadding="0" style="background-image: url(/pic/event_menu_bg.gif);"><tr>
<!-- BEGIN gallery_content_photo_menu -->
<td align="center" style="height:37px; width:70px;"><a href="/ru/gallery/{GALLERY_PATH}" {gallery_content_menu.gallery_content_photo_menu.MENU_STYLE}>Фото</a></td>
<!-- END gallery_content_photo_menu -->
<!-- BEGIN gallery_content_video_menu -->
<td  style="height:37px; width:70px;" align="center"><a href="/ru/gallery/{GALLERY_PATH}video/" {gallery_content_menu.gallery_content_video_menu.MENU_STYLE}>Видео</a></td>
<!-- END gallery_content_video_menu -->
</tr></table>
<!-- END gallery_content_menu -->

<!-- BEGIN gallery_catlist -->
<br /><br />
<div id="divGalleryPage">
	<!-- BEGIN gallery_catlist_row -->
	<div class="gallery-cat">
		<div class="gallery-cat-edit">
			{gallery_catlist.gallery_catlist_row.CAT_EDIT}
			{gallery_catlist.gallery_catlist_row.CAT_ADD_SUB}
			{gallery_catlist.gallery_catlist_row.CAT_DEL}
		</div>
		<div class="gallery-cat-content">
			<table width="100%" border="0" cellspacing="0"><tr><td align="center" valign="middle">
			<div class="gallery-name" align="center">{gallery_catlist.gallery_catlist_row.CAT_NAME}</div>
			<div class="cat_img" align="center">
			<a href="{gallery_catlist.gallery_catlist_row.CAT_PATH}" title="{gallery_catlist.gallery_catlist_row.IMG_NAME}">
<img src="{gallery_catlist.gallery_catlist_row.CAT_IMG}" alt="{gallery_catlist.gallery_catlist_row.IMG_NAME}" title="{gallery_catlist.gallery_catlist_row.IMG_NAME}" border="0" /></a>
			</div>
			<div class="cat_desc" align="center">
			    {gallery_catlist.gallery_catlist_row.CAT_DESC}
			</div>
			</td></tr></table>
		</div>
	</div>
	<!-- END gallery_catlist_row -->
	<div style="clear:both"></div>
</div>
<!-- END gallery_catlist -->

<!-- BEGIN switch_gallery -->
<div class="highslide-gallery175">
	<ul>
<!-- BEGIN gallery_img_list -->
	<li>
	<div class="thumbwrapper">
		<div class="gallery-cat-edit">
			{switch_gallery.gallery_img_list.DEL_ITEM}
		</div>
	<table width="100%" border="0" cellspacing="1" cellpadding="1"><tr><td align="center" height="135px;">
<a href="{switch_gallery.gallery_img_list.IMG_PATH}" class="highslide" onclick="return hs.expand(this)" title="{switch_gallery.gallery_img_list.IMG_NAME}">
<img src="{switch_gallery.gallery_img_list.SMALL_IMG_PATH}" alt="{switch_gallery.gallery_img_list.IMG_NAME}" title="{switch_gallery.gallery_img_list.IMG_NAME}" /></a>
	</td></tr></table>
	<div class="highslide-heading">
		<strong>{switch_gallery.gallery_img_list.IMG_NAME}</strong><br />
	</div>
	<div class="highslide-caption">
	    {switch_gallery.gallery_img_list.IMG_DESC}
	</div>

	</div>
	</li>
<!-- END gallery_img_list -->
</ul>
	<div style="clear:both"></div>
</div>
<!-- END switch_gallery -->


<!-- BEGIN switch_video_gallery -->
<!-- BEGIN gallery_video_list -->
<table border="0" cellspacing="5" cellpadding="0" align="center">
<tr><td align="left" style="border: 1px solid silver; background: #ededed; margin: 7px; padding: 5px;">
	<div align="right">{switch_video_gallery.gallery_video_list.DEL_ITEM}</div>
	<div align="center">{switch_video_gallery.gallery_video_list.VIDEO_PATH}</div>
	<strong>{switch_video_gallery.gallery_video_list.VIDEO_NAME}</strong><br>
	{switch_video_gallery.gallery_video_list.VIDEO_DESC}
</td></tr>
</table>
<!-- END gallery_video_list -->
<div style="clear:both"></div>
<!-- END switch_video_gallery -->

<p align="right">{ADMIN_LINK}<br /></p>

