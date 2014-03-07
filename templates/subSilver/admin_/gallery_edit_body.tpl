
<!-- 
===========================================

				Фотогалерея

===========================================
-->

<!-- BEGIN swich_gallery -->
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
<td><strong>Альбом:</strong> {swich_gallery.CAT}<br /> [ <a href="/admin/index.php?edit=photo&cat={PAGE_ID}">Фото</a> ] &nbsp; [ <a href="/admin/index.php?edit=video&cat={PAGE_ID}">Видео</a> ]</td>
<td align="right">
<!-- BEGIN swich_gallery_upload -->
<a href="/admin/index.php?edit=photo&imageprop=1&cat={PAGE_ID}" title="Редактировать альбом"><img src="/pic/ico/edit.gif" alt="Редактировать альбом" width="16" height="16" border="0" /></a> &nbsp; 
<a style="cursor: pointer;" onClick="ShowDiv('upload_form')" title="Добавить фотографии"><img src="/pic/ico/image_add.png" alt="Добавить фотографии" width="16" height="16" border="0" /></a><br>
<!-- END swich_gallery_upload -->
<!-- BEGIN swich_gallery_video_upload -->
<a href="/admin/index.php?edit=video&imageprop=1&cat={PAGE_ID}" title="Редактировать альбом"><img src="/pic/ico/edit.gif" alt="Редактировать альбом" width="16" height="16" border="0" /></a> &nbsp; 
<a style="cursor: pointer;" onClick="ShowDiv('upload_form')" title="Добавить фотографии"><img src="/pic/ico/image_add.png" alt="Добавить видео" width="16" height="16" border="0" /></a><br>
<!-- END swich_gallery_video_upload -->
</td></tr></table>
<!-- BEGIN swich_gallery_upload -->
<script type="text/javascript" src="/script/jquery/js/swfobject.js"></script>
<script src="/script/jquery/js/uploadify.v2.1.4.min.js" type="text/javascript"></script>
<div id="upload_form" style="display: none;">
<table><tr>
<td width="70">Начать<br>загрузку</td>
<td width="75">Очистить<br>список</td>
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
//		'script'		: '/admin/upload_img.php',
		'cancelImg'		: '/script/jquery/uploadify/cancel.png',
		'multi'			: true,
//		'auto'			: true,
	    'fileDesc'		: '*.jpg;*.png;*.gif',
	    'fileExt'		: '*.jpg;*.png;*.gif',
		'sizeLimit'		: '2048000',
		'buttonImg'		: '/pic/ico/folder_search_48x48.png',
		'width'			: '48',
		'height'		: '48',
//		'buttonText'	: 'Browse...',
		'onAllComplete' : function(){
			show_content("/dynamic/gallery_list.php?id={PAGE_ID}", '#divGalleryPage');
		},
	    'onComplete'	: function(event,queueID,fileObj,response,data) { 
			$('#upl_resp').html("/show_img.php?img=" + response);
//			show_content("/show_img.php?img=" + response, '#CatImg');

//			document.getElementById('upl_res').value="block"; 
//			alert(response)
//			show_image(response);
			},
		scriptData : ({
			'gallery_type'	: 'base',
			'gallery_id'	: '{PAGE_ID}',
			'article_id'	: '{PAGE_ID}',
			'source_id'		: '{SOURCE_ID}'
		})
	});
 });
</script>
</div>

<div id="upl_resp"><!--ответ скрипта--></div>
<!-- END swich_gallery_upload -->
<!-- BEGIN swich_gallery_video_upload -->
<div id="upload_form" style="display: none;">
<form action="/admin/index.php?edit=video&cat={PAGE_ID}" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr><td width="130px">Название</td><td><input type="text" name="video_name" value="" size="93"></td></tr>
<tr><td>Описание</td><td><textarea cols="70" rows="5" name="video_desc"></textarea></td></tr>
<tr><td>Код ролика</td><td><textarea cols="70" rows="5" name="video_code"></textarea></td></tr>
<tr><td></td><td><input type="submit" name="video_add" value="Добавить"></td></tr>
</table>

</form>
</div>

<div id="upl_resp"><!--ответ скрипта--></div>
<!-- END swich_gallery_video_upload -->


<br /><br />
<!-- END swich_gallery -->

<!-- BEGIN swich_gallery_script -->
<!-- =======================================+
|											|
|    		  HighSlide script				|
|											|
+=======================================- -->
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
<!-- END swich_gallery_script -->


<!-- BEGIN swich_gallery_preview -->
<!-- =======================================+
|											|
|    		  	Gallery Page				|
|											|
+=======================================- -->

<div id="divGalleryPage" class="highslide-gallery175">
<ul>
<!-- BEGIN gallery_preview_list -->
	<li>
	<div class="thumbwrapper">
<a href="{swich_gallery_preview.gallery_preview_list.IMG_PATH}" class="highslide" onclick="return hs.expand(this)" title="{swich_gallery_preview.gallery_preview_list.IMG_NAME}">
<img src="{swich_gallery_preview.gallery_preview_list.SMALL_IMG_PATH}" alt="{swich_gallery_preview.gallery_preview_list.IMG_NAME}" title="{swich_gallery_preview.gallery_preview_list.IMG_NAME}" /></a>
	<div class="highslide-heading">
		<strong>{swich_gallery_preview.gallery_preview_list.IMG_NAME}</strong><br />
	</div>
	<div class="highslide-caption">
	    {swich_gallery_preview.gallery_preview_list.IMG_DESC}
	</div>
	</div>
	</li>
<!-- END gallery_preview_list -->
</ul>
	<div style="clear:both"></div>
</div>
<!-- END swich_gallery_preview -->

<!-- BEGIN switch_video_gallery -->
<!-- =======================================+
|											|
|    		  	Gallery VIDEO				|
|											|
+=======================================- -->
<!-- BEGIN gallery_video_list -->
<div class="video-gallery">
{switch_video_gallery.gallery_video_list.VIDEO_PATH}<br />
<strong>{switch_video_gallery.gallery_video_list.VIDEO_NAME}</strong><br />
{switch_video_gallery.gallery_video_list.VIDEO_DESC}<br />
</div>
<!-- END gallery_video_list -->
<!-- END switch_video_gallery -->



<!-- BEGIN swich_gallery_edit_list -->
<!-- =======================================+
|											|
|    Редактирование описаний фотографий		|
|											|
+=======================================- -->
<form action="/admin/index.php?edit=photo&cat={PAGE_ID}" method="post">
<table width="100%" border="0" cellspacing="5" cellpadding="0">
<col valign="top">
<col valign="top">
<!-- BEGIN gallery_img -->
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">
<col valign="top">
<col valign="top">
<tr><td>Название:</td><td><input type="text" name="img_name[]" value="{swich_gallery_edit_list.gallery_img.IMG_NAME}" size="66" maxlength="255">
&nbsp;&nbsp;<a href="/admin/index.php?edit=photo&imageprop=1&cat={PAGE_ID}&sgal_action=del-{swich_gallery_edit_list.gallery_img.IMG_ID}" title="Удалить"><img src="/pic/ico/delete.png" alt="Удалить" width="16" height="16" border="0"></a></td></tr>
<tr><td>Описание:</td><td><textarea cols="50" rows="3" name="img_desc[]">{swich_gallery_edit_list.gallery_img.IMG_DESC}</textarea>
<input type="hidden" name="img_id[]" value="{swich_gallery_edit_list.gallery_img.IMG_ID}"></td></tr>
<tr><td><small>Имя файла:</small></td><td><small>{swich_gallery_edit_list.gallery_img.IMG_PATH}</small></td></tr>
</table>
</td>
<td valign="top">
	<div class="thumbwrapper">
<a href="{swich_gallery_edit_list.gallery_img.IMG_PATH}" class="highslide" onclick="return hs.expand(this)" title="{swich_gallery_edit_list.gallery_img.IMG_NAME}">
<img src="{swich_gallery_edit_list.gallery_img.SMALL_IMG_PATH}" alt="{swich_gallery_edit_list.gallery_img.IMG_NAME}" title="{swich_gallery_edit_list.gallery_img.IMG_NAME}" border="0" /></a>
	<div class="highslide-heading">
		<strong>{swich_gallery_edit_list.gallery_img.IMG_NAME}</strong><br />
	</div>
	<div class="highslide-caption">
	    {swich_gallery_edit_list.gallery_img.IMG_DESC}
	</div>
	</div>
</td></tr>
<tr><td height="1" colspan="2" style="background-color: Black;"></td></tr>


<!-- END gallery_img -->
</table>
<input type="hidden" name="img_total" value="{IMG_TOTAL}">
<input type="submit" name="GalImgSave" value="Сохранить">
</form>
<!-- END swich_gallery_edit_list -->



<!-- BEGIN swich_gallery_video_edit_list -->
<!-- =======================================+
|											|
|    Редактирование описаний видео			|
|											|
+=======================================- -->
<form action="/admin/index.php?edit=video&cat={PAGE_ID}" method="post">
<!-- BEGIN gallery_video -->

<div class="video-gallery">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<col valign="top">
<col valign="top">
<tr><td>Название:</td><td><input type="text" name="img_name[]" value="{swich_gallery_video_edit_list.gallery_video.IMG_NAME}" size="66" maxlength="255">
&nbsp;&nbsp;<a href="/admin/index.php?edit=photo&imageprop=1&cat={PAGE_ID}&sgal_action=del-{swich_gallery_video_edit_list.gallery_video.IMG_ID}" title="Удалить"><img src="/pic/ico/delete.png" alt="Удалить" width="16" height="16" border="0"></a></td></tr>
<tr><td>Описание:</td><td><textarea cols="50" rows="3" name="img_desc[]">{swich_gallery_video_edit_list.gallery_video.IMG_DESC}</textarea>
<input type="hidden" name="img_id[]" value="{swich_gallery_video_edit_list.gallery_video.IMG_ID}"></td></tr>
<tr><td><small>Ролик:</small></td><td><small>{swich_gallery_video_edit_list.gallery_video.VIDEO_PATH}</small></td></tr>
<!--tr><td><small>Превью:</small></td><td><small>{swich_gallery_video_edit_list.gallery_video.SMALL_IMG_PATH}</small></td></tr-->
</table>
</div>
<hr />
<!-- END gallery_video -->

<input type="hidden" name="img_total" value="{IMG_TOTAL}">
<input type="submit" name="GalImgSave" value="Сохранить">
</form>
<!-- END swich_gallery_video_edit_list -->


<!-- BEGIN gallery_catlist -->

<table width="100%" border="1" cellspacing="0" cellpadding="0"><tr><th>ID</th><th>PID</th><th>Галерея</th><th>Название, Описание, Путь</th><th width="50px">Ред.</th></tr>
	<col align="center">
	<col align="center">
	<col align="center">
	<!-- BEGIN gallery_catlist_row -->
	<tr><td>{gallery_catlist.gallery_catlist_row.CAT_ID}</td><td>{gallery_catlist.gallery_catlist_row.CAT_PID}</td>
	<td><a href="/admin/index.php?edit=photo&cat={gallery_catlist.gallery_catlist_row.CAT_ID}"><img src="{gallery_catlist.gallery_catlist_row.CAT_IMG}" alt="" border="0"></a></td>
	<td><a href="/admin/index.php?edit=photo&cat={gallery_catlist.gallery_catlist_row.CAT_ID}">{gallery_catlist.gallery_catlist_row.CAT_NAME}</a><br>{gallery_catlist.gallery_catlist_row.CAT_DESC}<br>{gallery_catlist.gallery_catlist_row.CAT_PATH}</td>
	<td width="50px" align="center">
	<a href="/admin/index.php?edit=gallery&cat_edit={gallery_catlist.gallery_catlist_row.CAT_ID}"><img src="/pic/ico/edit.gif" alt="Редактировать" width="16" height="16" border="0"></a>&nbsp;
	{gallery_catlist.gallery_catlist_row.CAT_DEL}
	{gallery_catlist.gallery_catlist_row.CAT_ADD}
	</td></tr>
	<!-- END gallery_catlist_row -->
</table>
<br><br>
<a href="/admin/index.php?edit=gallery&add=cat">Добавить галерую</a><br>

<!-- END gallery_catlist -->
<!-- BEGIN swich_gallery_addcat -->
Создание новой фотогалерии
<form action="/admin/index.php?edit=gallery" method="post">
<table>
<col valign="top">
<!--tr><td>Обложка</td><td><input type="text" name="cat_img" value="" size="100" maxlength="255"><br><small>Путь к файлу обложки. Если оставить пустым - при добавлении первой фотографии она станет обложкой</small></td></tr-->
<tr><td>Название</td><td><input type="text" name="cat_name" value="" size="100" maxlength="255"><br><small>До 255 символов</small></td></tr>
<tr><td>Описание</td><td><input type="text" name="cat_desc" value="" size="100" maxlength="255"><br><small>До 255 символов</small></td></tr>
<tr><td>Путь</td><td><input type="text" name="cat_path" value="" size="50" maxlength="128"><br><small>Использовать только латинские буквы, цифры, знаки "-" и "_"</small></td></tr>
<tr><td>Галерея</td><td><strong>{swich_gallery_addcat.CAT_PID_NAME}</strong><br><small>Название галерии в которой создаем новую галерею</small>
<input type="hidden" name="cat_pid" value="{swich_gallery_addcat.CAT_PID}"></td></tr>
</table>
<input type="submit" name="add_cat" value="Добавить">
</form>
<!-- END swich_gallery_addcat -->

<!-- BEGIN swich_gallery_editcat -->
Редактирование фотогалерии
<form action="/admin/index.php?edit=gallery" method="post">
<table>
<col valign="top">
<tr><td>
<link rel="stylesheet" href="/script/jquery/css/uploadify.css" type="text/css" media="screen" />
<script type="text/javascript" src="/script/jquery/js/swfobject.js"></script>
<script src="/script/jquery/js/uploadify.v2.1.4.min.js" type="text/javascript"></script>
<div id="GalCatImg">{swich_gallery_editcat.CAT_PHOTO}</div>
<input id="fileInput1" name="fileInput1" type="file" />

<script type="text/javascript">
 $(document).ready(function() {
	$("#fileInput1").uploadify({
		'uploader'		: '/script/jquery/uploadify/uploadify.swf',
		'script'		: '/admin/upload_img.php',
		'cancelImg'		: '/script/jquery/uploadify/cancel.png',
		'multi'			: false,
		'auto'			: true,
	    'fileDesc'		: '*.jpg;*.png;*.gif',
	    'fileExt'		: '*.jpg;*.png;*.gif',
		'sizeLimit'		: '1024000',
		'buttonText'	: 'Browse...',
	    'onComplete'	: function(event,queueID,fileObj,response,data) { 
//			$('#upl_resp').html("/show_img.php?img=" + response);
			show_content("/dynamic/show_img.php?img=" + response, '#GalCatImg');
			},
		scriptData : ({
			'gallery_type'	: 'gallery_catimg',
			'img_type'		: 'tumb',
			'gallery_id'	: '{swich_gallery_editcat.CAT_ID}',
			'article_id'	: '{swich_gallery_editcat.CAT_ID}',
			'source_id'		: '{SOURCE_ID}'
		})
	});
 });
</script>
<!--small>Загрузить фотографию</small-->
</td><td>

<table>
<col valign="top">
<!--tr><td>Обложка</td><td><input type="text" name="cat_img" value="" size="100" maxlength="255"><br><small>Путь к файлу обложки. Если оставить пустым - при добавлении первой фотографии она станет обложкой</small></td></tr-->
<tr><td>Название</td><td><input type="text" name="cat_name" value="{swich_gallery_editcat.CAT_NAME}" size="100" maxlength="255"><br><small>До 255 символов</small></td></tr>
<tr><td>Описание</td><td><input type="text" name="cat_desc" value="{swich_gallery_editcat.CAT_DESC}" size="100" maxlength="255"><br><small>До 255 символов</small></td></tr>
<tr><td>Тип галереи</td><td><input type="radio" name="cat_type" value="0"{swich_gallery_editcat.CAT_TYPE_0}>удаленная, 
<input type="radio" name="cat_type" value="1"{swich_gallery_editcat.CAT_TYPE_1}>обычная, 
<input type="radio" name="cat_type" value="50"{swich_gallery_editcat.CAT_TYPE_50}>скрытая (галерея используется только в статьях)</td></tr>
<tr><td>Путь</td><td>{swich_gallery_editcat.CAT_PATH}</td></tr>
<!--tr><td>Галерея</td><td><strong>{swich_gallery_editcat.CAT_PID_NAME}</strong><br><small>Путь и название родительской галереи</small></td></tr-->
</table>
<input type="hidden" name="cat_id" value="{swich_gallery_editcat.CAT_ID}">
<input type="submit" name="cat_save" value="Сохранить">
</td></tr></table>

</form>
<div id="upl_resp"><!--ответ скрипта--></div>
<!-- END swich_gallery_editcat -->


