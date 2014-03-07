<!-- BEGIN switch_left_menu -->
<!-- END switch_left_menu -->

<!-- 
	{PRINT_VERSION_NAME}
	<a href="{PRINT_VERSION_URL}&print=1"><img src="/pic/ico/printer.png" alt="{PRINT_VERSION_NAME}Версия для печати" width="16" height="16" border="0"></a>

	{SAVED}
-->
<!-- BEGIN swith_event -->
	<p><strong>{swith_event.PAGE_WEEK_DAY}, {swith_event.PAGE_DATE}</strong></p>
	<p><strong>{swith_event.PAGE_NAME}</strong></p>
	<table border="0" cellspacing="0" cellpadding="0"><tr align="center">
	<!-- BEGIN event_details_menu -->
		<td height="37" style="background-image: url(/pic/event_menu_bg.gif); width: 114px; max-height: 37px;"><a href="{EVENT_PATH}" style="{swith_event.event_details_menu.MENU_SELECTED_STYLE}">Подробности</a></td>
	<!-- END event_details_menu -->
	<!-- BEGIN event_photo_menu -->
		<td style="background-image: url(/pic/event_menu_bg.gif); width: 70px; max-height: 37px;"><a href="{EVENT_PATH}photo/" style="{swith_event.event_photo_menu.MENU_SELECTED_STYLE}">Фото</a></td>
	<!-- END event_photo_menu -->
	<!-- BEGIN event_video_menu -->
		<td style="background-image: url(/pic/event_menu_bg.gif); width: 75px; max-height: 37px;"><a href="{EVENT_PATH}video/" style="{swith_event.event_video_menu.MENU_SELECTED_STYLE}">Видео</a></td>
	<!-- END event_video_menu -->
	</tr></table>
<!-- END swith_event -->

<!-- BEGIN swich_event_details -->
	{swich_event_details.PAGE_TEXT}
<!-- END swich_event_details -->


<!-- BEGIN switch_smgallery -->

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
<div align="right">{switch_smgallery.PAGINATION}{PAGINATION}</div><br />
<div class="highslide-gallery">
	<ul>
<!-- BEGIN smgallery_list -->
	<li>
	<!--div class="thumbwrapper">
		<table width="100%" border="2" cellspacing="0" cellpadding="0"><tr><td align="center"-->
<a href="{switch_smgallery.smgallery_list.IMG_PATH}" class="highslide" onclick="return hs.expand(this)" title="{switch_smgallery.smgallery_list.IMG_NAME}">
<img src="{switch_smgallery.smgallery_list.SMALL_IMG_PATH}" alt="{switch_smgallery.smgallery_list.IMG_NAME}" title="{switch_smgallery.smgallery_list.IMG_NAME}" /></a>
		<!--/td></tr></table-->
		<div class="highslide-heading">
			<strong>{switch_smgallery.smgallery_list.IMG_NAME}</strong>
		</div>
		<div class="highslide-caption">
		    {switch_smgallery.smgallery_list.IMG_DESC}
		</div>
	<!--/div-->
	</li>
<!-- END smgallery_list -->
</ul>
	<div style="clear:both"></div></div>
<div align="right">{switch_smgallery.PAGINATION}{PAGINATION}</div><br />
<!-- END switch_smgallery -->

<!-- BEGIN switch_video_gallery -->
<!-- BEGIN gallery_video_list -->
<table border="0" cellspacing="5" cellpadding="0" align="center">
<tr><td align="left" style="border: 1px solid silver; background: #ededed; margin: 7px; padding: 5px;">
<div align="center">{switch_video_gallery.gallery_video_list.VIDEO_PATH}</div>
<strong>{switch_video_gallery.gallery_video_list.VIDEO_NAME}</strong><br />
{switch_video_gallery.gallery_video_list.VIDEO_DESC}<br />
</td></tr>
</table>
<!-- END gallery_video_list -->
<div style="clear:both"></div>
<!-- END switch_video_gallery -->



<!-- BEGIN swith_no_event -->
<p><h3>Для данной даты события отсутствуют</h3></p>
<!-- END swith_no_event -->

<p align="right">{ADMIN_LINK}<br /></p>