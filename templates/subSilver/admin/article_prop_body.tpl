<div id="item-add"></div>

<!-- BEGIN switch_article_properties -->
<!--
-----------------------------------------------

				Проперти статьи

-----------------------------------------------
-->
<script type="text/javascript" src="/script/jquery/js/jcarousellite_1.0.1.pack.js"></script>
<script type="text/javascript" src="/script/jquery/js/jcarousellite.mousewheel.min.js"></script>

<strong>Атрибуты статьи</strong><br><br>
<table width="100%" cellspacing="5" cellpadding="0">
<col valign="top">
<col valign="top">
<tr><td class="tddottedbottom" width="150px">ID статьи</td><td class="tddottedbottom">{PAGE_ID}</td></tr>
<tr><td class="tddottedbottom">Название:</td><td class="tddottedbottom">{PAGE_TITLE}</td></tr>
<tr><td class="tddottedbottom">Описание:</td><td class="tddottedbottom">{PAGE_DESC}</td></tr>
<tr><td class="tddottedbottom">Ключевые слова:</td><td class="tddottedbottom">{PAGE_KEYWORDS}</td></tr>
<!--tr><td>Классификация</td><td class="tddottedbottom">{PAGE_CLASSIFICATION}</td></tr>
<tr><td>content direction</td><td class="tddottedbottom">{S_CONTENT_DIRECTION}</td></tr-->
<tr><td class="tddottedbottom">Дата:</td><td class="tddottedbottom">{PAGE_DATE}</td></tr>
<tr><td class="tddottedbottom">Раздел:</td><td class="tddottedbottom">{PAGE_PARAGRAF}{PAGE_PRIMARY}</td></tr>
<tr><td class="tddottedbottom">Название файла<br><small>Полный путь:</small></td><td class="tddottedbottom">{PAGE_PATH}<br><small>{ARTICLE_PATH}</small></td></tr>
<tr><td class="tddottedbottom">Форма обратной связи:</td><td class="tddottedbottom">{PAGE_FORM_NAME}<br />E-Mail: {PAGE_EMAIL}<br>Тема: {PAGE_EMAIL_SUBJ}</td></tr>
<tr><td class="tddottedbottom">Галерея статьи:</td><td class="tddottedbottom">{GALLERY_ONOFF}
</td></tr>
<tr><td class="tddottedbottom">Текст</td><td class="tddottedbottom"><small>{PAGE_TEXT}</small></td></tr>
<tr>
	<td colspan="2">
 
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<img id="gal_preview_left" src="/templates/cherepanov/images/gal_arr_left.png" width="1" height="1" border="0" />
		</td>
		<td>
<div id="galview">...</div>
		</td>
		<td>
			<img id="gal_preview_right" src="/templates/cherepanov/images/gal_arr_right.png" width="1" height="1" border="0" />
		</td>
	</tr>
</table>
<script type="text/javascript">// <![CDATA[
show_content('/dynamic/gallery/dyn_gal_galpreview.php?id={PAGE_ID}&type=sm_video&page=0&order=desc&img_cnt=5&thumb_w=100&thumb_h=80', '#galview');
// ]]></script>
<script src="/script/highslide/highslide-full.min.js" type="text/javascript"></script>
<script type="text/javascript">// <![CDATA[
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
// ]]></script>

	</td>
</tr>
</table>
<!-- END switch_article_properties -->



<!-- BEGIN switch_events_properties -->
<!--
-----------------------------------------------

          Проперти календаря событий

-----------------------------------------------
-->
<strong>Атрибуты</strong><br><br>
<table width="100%" cellspacing="5" cellpadding="0">
<col valign="top">
<col valign="top">
<tr><td class="tddottedbottom" width="150px">ID статьи</td><td class="tddottedbottom">{PAGE_ID}</td></tr>
<tr><td class="tddottedbottom">Название:</td><td class="tddottedbottom">{PAGE_TITLE}</td></tr>
<tr><td class="tddottedbottom">Дата:</td><td class="tddottedbottom">{PAGE_DATE}</td></tr>
<tr><td class="tddottedbottom">Полный путь:</td><td class="tddottedbottom">{ARTICLE_PATH}</td></tr>
<tr><td class="tddottedbottom">Фото Галерея:</td><td class="tddottedbottom">
		<!-- BEGIN switch_smgal_on -->
		<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=on" title="Включить галлерею"><img src="/pic/ico/stock_form-checkbox.png" width="16" height="16" border="0" alt="Включить" /></a>
		<!-- END switch_smgal_on -->
		<!-- BEGIN switch_smgal_off -->
		<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=off" title="Отключить галлерею"><img src="/pic/ico/delete.png" width="16" height="16" alt="Отключить" border="0" /></a>
		<!-- END switch_smgal_off -->
		{GALLERY_ONOFF}&nbsp;&nbsp;&nbsp;<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=upload"><img src="/pic/ico/upload.png" width="16" height="16" alt="Загрузка" border="0" /></a>&nbsp;
		<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=edit"><img src="/pic/ico/edit.gif" width="16" height="16" alt="Редактирование" border="0" /></a>
<tr><td class="tddottedbottom">Видео Галерея:</td><td class="tddottedbottom">
		<!-- BEGIN switch_smvideogal_on -->
		<a href="/admin/events_prop.php?id={PAGE_ID}&svgallery=on" title="Включить галлерею"><img src="/pic/ico/stock_form-checkbox.png" width="16" height="16" border="0" alt="Включить" /></a>
		<!-- END switch_smvideogal_on -->
		<!-- BEGIN switch_smvideogal_off -->
		<a href="/admin/events_prop.php?id={PAGE_ID}&svgallery=off" title="Отключить галлерею"><img src="/pic/ico/delete.png" width="16" height="16" alt="Отключить" border="0" /></a>
		<!-- END switch_smvideogal_off -->
		{VGALLERY_ONOFF}&nbsp;&nbsp;&nbsp;<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=v-upload"><img src="/pic/ico/upload.png" width="16" height="16" alt="Загрузка" border="0" /></a>&nbsp;
		<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=v-edit"><img src="/pic/ico/edit.gif" width="16" height="16" alt="Редактирование" border="0" /></a>
</td></tr>
<tr><td class="tddottedbottom">Текст</td><td class="tddottedbottom"><small>{PAGE_TEXT}</small></td></tr>

</table>
<!-- END switch_events_properties -->



<!-- BEGIN switch_smgallery_upload -->
<!--
-----------------------------------------------

				Галерея статьи

-----------------------------------------------
-->
<!--script src="/script/jquery/js/jquery-1.4.2.min.js" type="text/javascript"></script-->
<script type="text/javascript" src="/script/jquery/js/swfobject.js"></script>
<script src="/script/jquery/js/uploadify.v2.1.4.min.js" type="text/javascript"></script>



<p><strong>Загрузка файлов в минигалерею</strong></p>
<table><tr>
<td width="70">Начать<br>загрузку</td>
<td width="70">Очистить<br>список</td>
<td width="70">Выбор<br>файлов</td>
</tr></table>
<a href="javascript:$('#fileInput1').uploadifyUpload();"><img src="/pic/ico/cnruninstall_48x48.png" alt="Начать загрузку" width="48" height="48" border="0"></a> &nbsp; &nbsp; &nbsp; 
<a href="javascript:$('#fileInput1').uploadifyClearQueue();"><img src="/pic/ico/delete_48x48.png" alt="Очистить очередь" width="48" height="48" border="0"></a> &nbsp; &nbsp; &nbsp; 
<input id="fileInput1" name="fileInput1" type="file" />
<script type="text/javascript">
 $(document).ready(function() {
	$("#fileInput1").uploadify({
		'uploader'		: '/script/jquery/uploadify/uploadify.swf',
		'script'		: '/admin/upload_img.php',
		'cancelImg'		: '/script/jquery/uploadify/cancel.png',
		'multi'			: true,
	    'fileDesc'		: '*.jpg;*.png;*.gif',
	    'fileExt'		: '*.jpg;*.png;*.gif',
		'sizeLimit'		: '2090000',
//		'buttonText'	: 'Browse...',
		'buttonImg'		: '/pic/ico/folder_search_48x48.png',
		'width'			: '48',
		'height'		: '48',
	    'onComplete'	: function(event,queueID,fileObj,response,data) { 
			$('#upl_resp').html("/show_img.php?img=" + response);
			show_content("/dynamic/show_img.php?img=" + response, '#GalCatImg');
			},
		scriptData : ({
			'gallery_type'	: 'mini',
			'article_id'	: '{PAGE_ID}',
			'source_id'		: '{SOURCE_ID}'
		})
	});
 });
</script>
<div id="upl_resp"><!--ответ скрипта--></div>
<!-- END switch_smgallery_upload -->

<!-- BEGIN switch_smgallery_edit -->
<script type="text/javascript" src="/script/highslide/highslide-with-gallery.min.js"></script>
<script type="text/javascript">
hs.graphicsDir = '/script/highslide/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.fadeInOut = true;
hs.dimmingOpacity = 0.8;
hs.outlineType = 'rounded-white';
hs.captionEval = 'this.thumb.alt';
hs.marginBottom = 105; // make room for the thumbstrip and the controls
hs.numberPosition = 'caption';

// Add the slideshow providing the controlbar and the thumbstrip
hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
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

<strong>Редактирование галереи.</strong><br><br>
Название и описание файлов - не более 255 символов
<hr>
<form action="{SUBMIT_PATH}" method="post">
<table width="100%" border="0" cellspacing="5" cellpadding="0">
<col valign="top">
<col valign="top">
<!-- BEGIN smgallery_edit_list -->
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">
<col valign="top">
<col valign="top">
<tr><td>Название:</td><td><input type="text" name="img_name[]" value="{switch_smgallery_edit.smgallery_edit_list.IMG_NAME}" size="66" maxlength="255">
&nbsp;&nbsp;<a href="{SUBMIT_PATH}&sgal_action=del-{switch_smgallery_edit.smgallery_edit_list.IMG_ID}" title="Удалить"><img src="/pic/ico/delete.png" alt="" width="16" height="16" border="0"></a></td></tr>
<tr><td>Описание:</td><td><textarea cols="50" rows="3" name="img_desc[]">{switch_smgallery_edit.smgallery_edit_list.IMG_DESC}</textarea>
<input type="hidden" name="img_id[]" value="{switch_smgallery_edit.smgallery_edit_list.IMG_ID}"></td></tr>
<tr><td><small>Имя файла:</small></td><td><small>{switch_smgallery_edit.smgallery_edit_list.IMG_PATH}</small></td></tr>
</table>
</td>
<td valign="top"><a class='highslide' href="{switch_smgallery_edit.smgallery_edit_list.IMG_PATH}" onclick="return hs.expand(this)" title="{switch_smgallery_edit.smgallery_edit_list.IMG_NAME}">
<img src="{switch_smgallery_edit.smgallery_edit_list.SMALL_IMG_PATH}" alt="{switch_smgallery_edit.smgallery_edit_list.IMG_NAME}<br><small>{switch_smgallery_edit.smgallery_edit_list.IMG_DESC}</small>" border="0"></a></td></tr>
<tr><td height="1" colspan="2" style="background-color: Black;"></td></tr>
<!-- END smgallery_edit_list -->
</table>
<input type="hidden" name="img_total" value="{IMG_TOTAL}">
<input type="submit" name="smGalSave" value="Сохранить">
</form>
<!-- END switch_smgallery_edit -->

<!-- BEGIN switch_smvideogallery_edit -->
<strong>Редактирование галереи.</strong><br><br>
Название и описание - не более 255 символов
<hr>
<form action="{SUBMIT_PATH}" method="post">
<!-- BEGIN smgallery_edit_list -->
<div class="video-gallery">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<col valign="top">
<col valign="top">
<tr><td>Название:</td><td><input type="text" name="img_name[]" value="{switch_smvideogallery_edit.smgallery_edit_list.IMG_NAME}" size="66" maxlength="255">
&nbsp;&nbsp;<a href="{SUBMIT_PATH}&sgal_action=del-{switch_smvideogallery_edit.smgallery_edit_list.IMG_ID}" title="Удалить"><img src="/pic/ico/delete.png" alt="" width="16" height="16" border="0"></a></td></tr>
<tr><td>Описание:</td><td><textarea cols="50" rows="3" name="img_desc[]">{switch_smvideogallery_edit.smgallery_edit_list.IMG_DESC}</textarea>
<input type="hidden" name="img_id[]" value="{switch_smvideogallery_edit.smgallery_edit_list.IMG_ID}"></td></tr>
<tr><td><small>Ролик:</small></td><td><small>{switch_smvideogallery_edit.smgallery_edit_list.VIDEO_PATH}</small></td></tr>
<!--tr><td><small>Превью:</small></td><td><small>{swich_gallery_video_edit_list.gallery_video.SMALL_IMG_PATH}</small></td></tr-->
</table>
</div>
<hr />


<!-- END smgallery_edit_list -->
<input type="hidden" name="img_total" value="{IMG_TOTAL}">
<input type="submit" name="smGalSave" value="Сохранить">
</form>
<!-- END switch_smvideogallery_edit -->

<!-- BEGIN switch_smvideogallery_upload -->
<!--
-----------------------------------------------

				Видео галерея статьи

-----------------------------------------------
-->

<p><strong>Загрузка Видео в минигалерею</strong></p>
<form action="{SUBMIT_PATH}" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr><td width="130px">Название</td><td><input type="text" name="video_name" value="" size="93"></td></tr>
<tr><td>Описание</td><td><textarea cols="70" rows="5" name="video_desc"></textarea></td></tr>
<tr><td>Код ролика</td><td><textarea cols="70" rows="5" name="video_code"></textarea></td></tr>
<tr><td></td><td><input type="submit" name="video_add" value="Добавить"></td></tr>
</table>

</form>
<div id="upl_resp"><!--ответ скрипта--></div>
<!-- END switch_smvideogallery_upload -->