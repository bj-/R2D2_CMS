<!-- BEGIN switch_left_menu -->
<!-- END switch_left_menu -->
<!-- {SAVED} -->
<!-- BEGIN switch_form_sended -->
{switch_form_sended.TEXT}
<!-- END switch_form_sended -->
{ARTICLE}
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
<hr style="height: 1px; border-bottom: 1px solid Silver;" />
<div class="highslide-gallery">
	<ul>
<!-- BEGIN smgallery_list -->
	<li>
	<div class="thumbwrapper">
		<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="center">
<a href="{switch_smgallery.smgallery_list.IMG_PATH}" class="highslide" onclick="return hs.expand(this)" title="{switch_smgallery.smgallery_list.IMG_NAME}">
<img src="{switch_smgallery.smgallery_list.SMALL_IMG_PATH}" alt="{switch_smgallery.smgallery_list.IMG_NAME}" title="{switch_smgallery.smgallery_list.IMG_NAME}" /></a>
		</td></tr></table>
		<div class="highslide-heading">
			<strong>{switch_smgallery.smgallery_list.IMG_NAME}</strong><br />
		</div>
		<div class="highslide-caption">
		    {switch_smgallery.smgallery_list.IMG_DESC}
		</div>
	</div>
	</li>
<!-- END smgallery_list -->
	</ul>
	<div style="clear:both"></div>
</div>
<!-- END switch_smgallery -->
<p align="right">{ADMIN_LINK}</p>

