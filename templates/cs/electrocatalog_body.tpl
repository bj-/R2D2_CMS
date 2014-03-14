<!-- {SAVED} -->

<p>Каталог электро деталей</p>

<select id="type_selection" onchange="change_type();">
	<option value="all">Все</option>
	<option value="diodes">Диод</option>
	<option value="led">LED</option>
	<option value="resistor">Резисторы</option>
	<!--option value="all">Диодный мост</option-->
	<!--option value="all">Транзистор</option-->
</select>

{ARTICLE}

<div id="electrocat_filter"></div>
<div id="electrocat_list"></div>

<!--
<div id="diodes">...</div>
<div id="transistors">...</div>

<div id="resistor_group">
<div id="resistor_filter">...</div>
<div id="resistor">...</div>
</div>

<div id="leds_group">
<div id="leds_filter">...</div>
<div id="leds">...</div>
</div>
-->

<script type="text/javascript">// <![CDATA[
function change_type()
{
	//var s = $("#type_selection option:selected").text();
	var s = $("#type_selection").val();
//	alert('/dynamic/electro/' + s + '.php');
	
	show_content('/dynamic/electro/' + s + '.php', '#electrocat_list');
	show_content('/dynamic/electro/filter.php?type=' + s, '#electrocat_filter');
}

/*
<!-- BEGIN switch_led -->
show_content('/dynamic/electro/led.php', '#leds');
show_content('/dynamic/electro/filter.php?type=led', '#leds_filter');
<!-- END switch_led -->


<!-- BEGIN switch_resistors -->
show_content('/dynamic/electro/resistor.php', '#resistor');
<!-- END switch_resistors -->


<!-- BEGIN switch_diodes -->
show_content('/dynamic/electro/diodes.php', '#diodes');
<!-- END switch_diodes -->

<!-- BEGIN switch_transistors -->
show_content('/dynamic/electro/transistors.php', '#transistors');
<!-- END switch_transistors -->
*/
</script>



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