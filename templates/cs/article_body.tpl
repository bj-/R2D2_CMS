<!-- BEGIN switch_left_menu -->
<!-- END switch_left_menu -->
<!-- {SAVED} -->
<!-- BEGIN switch_form_sended -->
{switch_form_sended.TEXT}
<!-- END switch_form_sended -->
<!-- BEGIN submenu -->
	<div style="line-height: 20px;">
	<!-- BEGIN submenu_list -->
		<a href="{submenu.submenu_list.MENU_PATH}" class="{submenu.submenu_list.MENU_CLASS}" title="{submenu.submenu_list.MENU_NAME}">{submenu.submenu_list.MENU_NAME}</a><br />
	<!-- END submenu_list -->
	</div>
<!-- END submenu -->
{ARTICLE}
<!-- BEGIN show_form -->
<form action="" method="post" name="email_send_form">
<input name="form_baseform" type="hidden" value="1" />
<table width="100%" border="0" cellpadding="0" cellspacing="5">
	<!-- BEGIN form_field_varchar -->
	<tr>
		<td>{show_form.form_field_varchar.NAME}{show_form.form_field_varchar.REQUIRED}</td>
		<td>
			<input name="{show_form.form_field_varchar.FIELD_NAME}" type="{show_form.form_field_varchar.TYPE}" value="{show_form.form_field_varchar.VALUE}" size="{show_form.form_field_varchar.SIZE}" maxlength="{show_form.form_field_varchar.MAXLEN}"{show_form.form_field_varchar.CLASS}{show_form.form_field_varchar.STYLE} />
		</td>
	</tr>
	<!-- END form_field_varchar -->
	<!-- BEGIN form_field_textarea -->
	<tr><td valign="top">{show_form.form_field_textarea.NAME}{show_form.form_field_textarea.REQUIRED}</td><td><textarea name="{show_form.form_field_textarea.FIELD_NAME}" cols="{show_form.form_field_textarea.COLS}" rows="{show_form.form_field_textarea.ROWS}"{show_form.form_field_textarea.CLASS}{show_form.form_field_textarea.STYLE}>{show_form.form_field_textarea.VALUE}</textarea></td></tr>
	<!-- END form_field_textarea -->
	<tr><td></td><td><input name="send_mail" type="submit" value="Отправить" /></td></tr>
</table>
</form>
<!-- END form -->

<!-- BEGIN switch_smgallery_script -->
<script type="text/javascript" src="/script/highslide/highslide-full.min.js"></script>
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
<!-- END switch_smgallery_script -->

<!-- BEGIN switch_smgallery -->
<div class="highslide-gallery">
	<ul>
<!-- BEGIN smgallery_list -->
	<li style=" background-color:#FFFFFF; border:none;">
	<div class="thumbwrapper" style="background-color:#FFFFFF; border:none;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="center">
<a href="{switch_smgallery.smgallery_list.IMG_PATH}" class="highslide" onclick="return hs.expand(this)" title="{switch_smgallery.smgallery_list.IMG_NAME}">
<img style="box-shadow:0px 0px 6px #aaaaaa;" src="{switch_smgallery.smgallery_list.SMALL_IMG_PATH}" alt="{switch_smgallery.smgallery_list.IMG_NAME}" title="{switch_smgallery.smgallery_list.IMG_NAME}" /></a>
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

<!-- BEGIN switch_smvgallery -->
<div id="galview">...</div>
<script type="text/javascript">// <![CDATA[
show_content('/dynamic/gallery/dyn_gal_galpreview.php?id={PAGE_ID}&type=sm_video&page=0&order=desc&img_cnt=500&thumb_w=175&thumb_h=135', '#galview');
</script>
<!-- END switch_smvgallery -->