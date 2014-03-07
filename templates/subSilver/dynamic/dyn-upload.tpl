<!-- BEGIN switch_upload_photo -->
<script type="text/javascript" src="/script/jquery/js/swfobject.js"></script>
<script src="/script/jquery/js/uploadify.v2.1.4.min.js" type="text/javascript"></script>
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
<!-- END switch_upload_photo -->

<!-- BEGIN switch_upload_video -->
<form action="/admin/index.php?edit=video&cat={PAGE_ID}" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr><td width="130px">Название</td><td><input type="text" name="video_name" value="" size="93"></td></tr>
<tr><td>Описание</td><td><textarea cols="70" rows="5" name="video_desc"></textarea></td></tr>
<tr><td>Код ролика</td><td><textarea cols="70" rows="5" name="video_code"></textarea></td></tr>
<tr><td></td><td><input type="submit" name="video_add" value="Добавить"></td></tr>
</table>
</form>
<!-- END switch_upload_video -->
