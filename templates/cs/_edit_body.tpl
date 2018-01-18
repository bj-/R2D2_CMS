<!-- TinyMCE -->
<script type="text/javascript" src="/tmce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
		language : 'ru',

		// Theme options

<!-- BEGIN switch_news_edit -->
		theme_advanced_buttons1 : "save,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,|,insertdate,inserttime,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,fullscreen,|,nonbreaking",
<!-- END switch_news_edit -->

<!-- BEGIN switch_article_edit -->
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
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
	});
</script>
<!-- /TinyMCE -->
<!-- BEGIN switch_article_edit -->
<!--Адрес статьи: <a href="{ARTICLE_PATH}">{ARTICLE_PATH}</a><br>
<hr size="1" style="border: 1px solid #043198;"-->
<!-- END switch_article_edit -->
<form method="post" action="{SUBMIT_PATH}">
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
		<script type="text/javascript" src="/jquery/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/jquery/js/jquery-ui-1.8.4.custom.min.js"></script>
		<script type="text/javascript" src="/jquery/ui/i18n/jquery.ui.datepicker-ru.js"></script>
		<script type="text/javascript">
			$(function(){
				$.datepicker.setDefaults(  
					$.extend($.datepicker.regional["ru"])
				);  

				// Datepicker
				$('#datepicker').datepicker({
						dateFormat: 'dd.mm.yy',
					inline: true
				});
				
				//hover states on the static widgets
				$('#dialog_link, ul#icons li').hover(
					function() { $(this).addClass('ui-state-hover'); }, 
					function() { $(this).removeClass('ui-state-hover'); }
				);
				
			});
		</script>
<input type="radio" name="date_sel" value="current_date"{CHECK_CURRENT_DATE}>Текущая; &nbsp; <input type="radio" name="date_sel" value="selected_date"{CHECK_SELECTED_DATE}>Указать: <input type="text" id="datepicker" name="page_date" value="{PAGE_DATE}" size="20"><br><small>Дата добавления</small></td></tr>
<!-- BEGIN switch_article_edit -->
<tr><td>Название файла:</td><td><input type="text" name="page_path" value="{PAGE_PATH}" size="100"><br><small>На английском. Использовать только цифры, латинские буквы и "-", вместо пробелов использовать "_".</small></td></tr>
<tr><td>Раздел:</td><td><select name="paragraf_id">{ARTICLE_PARAGRAF}</select><br><small><input type="checkbox" name="article_primary" value="1"{CHECK_PRIMARY_ARTICLE}>Основная статья раздела (Статья будет отображаться при входе в данный раздел)<br>Выберете раздел для данной статьи, отметте галочку если хотите сделать статью основной в разделе.</small></td></tr>
<tr><td colspan="2"><strong>Мета теги:</strong></td></tr>
<tr><td>Описание:</td><td><input type="text" name="page_desc" value="{PAGE_DESC}" size="100"><br><small>Краткое описание статьи. Для поисковиков.</small></td></tr>
<!--tr><td>Классификация:</td><td><input type="text" name="page_classification" value="{CLASSIFICATION}" size="100"></td></tr-->
<tr><td>Ключевые слова:</td><td><input type="text" name="page_keywords" value="{PAGE_KEYWORDS}" size="100"><br><small>До 15 ключевых слов через пробел</small></td></tr>
<!--tr><td>S_CONTENT_DIRECTION:</td><td><input type="text" name="content_direction" value="{S_CONTENT_DIRECTION}"></td></tr-->
<!--tr><td>Голосование:</td><td><input type="checkbox" name="article_vove" value="1"> Добавить голосование к статье (<a href="#">Редактировать опрос</a>)</td></tr-->
<tr><td>Форма обратной связи:</td><td>E-Mail: <input type="text" name="form_email"> Тема сообщения: <input type="text" name="form_subject" size="49"></td></tr>
<!-- END switch_article_edit -->
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
			<input type="submit" name="save" value="Submit" />
			<input type="reset" name="reset" value="Reset" />
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

<script type="text/javascript">
if (document.location.protocol == 'file:') {
	alert("The examples might not work properly on the local file system due to security settings in your browser. Please use a real webserver.");
}
</script>
