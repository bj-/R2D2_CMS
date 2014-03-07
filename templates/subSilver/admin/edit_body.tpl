<!-- TinyMCE -->
<script type="text/javascript" src="/script/tmce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "imagemanager,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
		language : 'ru',
		relative_urls : false,
//		convert_urls : false,

		// Theme options

<!-- BEGIN switch_blocks_edit -->
// save,|,
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,|,insertdate,inserttime,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,fullscreen,|,nonbreaking",
<!-- END switch_blocks_edit -->

<!-- BEGIN switch_news_edit -->
// save,|,
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,|,insertdate,inserttime,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,fullscreen,|,nonbreaking",
<!-- END switch_news_edit -->

<!-- BEGIN switch_article_edit -->
// save,|,
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
<!-- END switch_article_edit -->

		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "script/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});</script>
<!-- /TinyMCE -->
<!-- BEGIN switch_article_edit -->
<!--Адрес статьи: <a href="{ARTICLE_PATH}">{ARTICLE_PATH}</a><br>
<hr size="1" style="border: 1px solid #043198;"-->
<!-- END switch_article_edit -->
<form action="{SUBMIT_PATH}" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<col valign="top" width="120">
<tr><td>Название:</td><td><input type="text" name="page_title" value="{PAGE_TITLE}" size="100"><br>
<!-- BEGIN switch_news_edit -->
<small>Название новости</small>
<!-- END switch_news_edit -->
<!-- BEGIN switch_article_edit -->
<small>Название статьи. Отображается в заголовке браузера.</small>
<!-- END switch_article_edit -->
</td></tr>
<tr><td>Дата:</td><td>
	<script type="text/javascript" src="/script/jquery/js/jquery-ui-timepicker-addon.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$('#datepicker').datetimepicker({
				dateFormat: 'dd.mm.yy',
				timeOnlyTitle: 'Выберите время',
				timeText: 'Время',
				hourText: 'Часы',
				minuteText: 'Минуты',
				secondText: 'Секунды',
				currentText: 'Сегодня'
			});
		});
	</script>
<input type="text" id="datepicker" name="page_date" value="{PAGE_DATE}" size="20"><br><small>Дата добавления</small></td></tr>
<!-- BEGIN switch_article_edit -->
<tr><td>Название файла:</td><td><input type="text" name="page_path" value="{PAGE_PATH}" size="100"><br><small>На английском. Использовать только цифры, латинские буквы и "-", вместо пробелов использовать "_".</small></td></tr>
	<!-- BEGIN switch_article_paragraf_edit -->
<tr><td>Раздел:</td><td><select name="paragraf_id">{GENERAL_PAGE}{ARTICLE_PARAGRAF}</select><br><small><input type="checkbox" name="article_primary" value="1"{CHECK_PRIMARY_ARTICLE}>Основная статья раздела (Статья будет отображаться при входе в данный раздел)<br>Выберете раздел для данной статьи, отметте галочку если хотите сделать статью основной в разделе.</small></td></tr>
	<!-- END switch_article_paragraf_edit -->
<tr><td colspan="2"><strong>Мета теги:</strong></td></tr>
<tr><td>Описание:</td><td><input type="text" name="page_desc" value="{PAGE_DESC}" size="100"><br><small>Краткое описание статьи. Для поисковиков.</small></td></tr>
<!--tr><td>Классификация:</td><td><input type="text" name="page_classification" value="{CLASSIFICATION}" size="100"></td></tr-->
<tr><td>Ключевые слова:</td><td><input type="text" name="page_keywords" value="{PAGE_KEYWORDS}" size="100"><br><small>До 15 ключевых слов через пробел</small></td></tr>
<!--tr><td>S_CONTENT_DIRECTION:</td><td><input type="text" name="content_direction" value="{S_CONTENT_DIRECTION}"></td></tr-->
<!--tr><td>Голосование:</td><td><input type="checkbox" name="article_vove" value="1"> Добавить голосование к статье (<a href="#">Редактировать опрос</a>)</td></tr-->
<tr>
	<td>Форма обратной связи:</td>
	<td>
		{FORM_LIST}<br />
		E-Mail: <input type="text" name="form_email" value="{FORM_EMAIL}"> Тема сообщения: <input type="text" name="form_subject" value="{FORM_SUBJECT}" size="49">
		<!--br><small>Если указан E-Mail - внизу статьи будет добавлена форма обратной связи.</small-->
	</td>
</tr>
<!-- END switch_article_edit -->

<!-- BEGIN switch_news_edit -->
<tr><td>Источник:</td><td><input type="text" name="page_source_name" value="{PAGE_SOURCE_NAME}" size="100"><br><small>название СМИ / сайта откуда взята новость.</small></td></tr>
<tr><td>Фото:</td>
	<td>
		<table>
			<tr>
				<td>
					<div id="article_img">{PAGE_PHOTO}{PAGE_PHOTO_EXIST}</div>
					<link rel="stylesheet" href="/script/jquery/css/uploadify.css" type="text/css" media="screen" />
					<script type="text/javascript" src="/script/jquery/js/swfobject.js"></script>
					<script src="/script/jquery/js/uploadify.v2.1.4.min.js" type="text/javascript"></script>
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
			$('#upl_resp').html("/dynamic/show_img.php?img=" + response);
//			alert(response)
			show_content("/dynamic/show_img.php?img=" + response, '#article_img');
//			show_image('#article_img', );
			},
		scriptData : ({
			'gallery_type'	: 'oneimg',
			'article_id'	: '{PAGE_ID}',
			'source_id'		: '{SOURCE_ID}',
			'news_source_id'		: '{NEWS_SOURCE_ID}'
		})
	});
 });
</script>
					<small>Загрузить фотографию</small>
				</td>
				<td valign="bottom">
				&nbsp;&nbsp;&nbsp;<input name="delete_img" type="checkbox" value="1" /> Удалить фотографию<br /><br />
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr><td>Код видеоролика:</td><td><input type="text" name="page_video" value="{PAGE_VIDEO}" size="100"><br><small>название СМИ / сайта откуда взята новость.</small></td></tr>
<!-- END switch_news_edit -->

</table>
<input type="hidden" name="article_id" value="{ARTICLE_ID}">
<!-- BEGIN switch_add -->
<input type="hidden" name="new" value="1">
<!-- END switch_add -->
<hr size="1" style="border: 1px solid #043198;">
	<div>
		<!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
		<div>
			<textarea id="elm1" name="page_text" rows="{ROWS}" cols="80" style="width: 100%">{ARTICLE}</textarea>
		</div>

		<!-- Some integration calls -->
		<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
		<td>
			<input type="submit" name="save" value="Сохранить" />
			<input type="reset" name="reset" value="Убрать изменения" />
		</td>
		<td align="right">
			<a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[WISYWIG]</a>
			<a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Code]</a>
			<!--a href="javascript:;" onmousedown="tinyMCE.get('elm1').execCommand('Bold');">[Bold]</a>
			<a href="javascript:;" onmousedown="alert(tinyMCE.get('elm1').getContent());">[Get contents]</a>
			<a href="javascript:;" onmousedown="alert(tinyMCE.get('elm1').selection.getContent());">[Get selected HTML]</a>
			<a href="javascript:;" onmousedown="alert(tinyMCE.get('elm1').selection.getContent({format : 'text'}));">[Get selected text]</a>
			<a href="javascript:;" onmousedown="alert(tinyMCE.get('elm1').selection.getNode().nodeName);">[Get selected element]</a>
			<a href="javascript:;" onmousedown="tinyMCE.execCommand('mceInsertContent',false,'<b>Hello world!!</b>');">[Insert HTML]</a>
			<a href="javascript:;" onmousedown="tinyMCE.execCommand('mceReplaceContent',false,'<b>{$selection}</b>');">[Replace selection]</a-->
		</td>
		</tr></table>
	</div>
</form>
<div id="upl_resp"><!--ответ скрипта--></div>
<script type="text/javascript">
if (document.location.protocol == 'file:') {
	alert("The examples might not work properly on the local file system due to security settings in your browser. Please use a real webserver.");
}
</script>
