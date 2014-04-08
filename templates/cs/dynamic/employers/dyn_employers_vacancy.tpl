<!-- BEGIN vacancy_list -->
	<div>
		<div style="float:left; width:50px;">{vacancy_list.VACANCY_ID}</div>
		<div style="float:left; width:400px;">
			<a onclick="show_content('/dynamic/employer-vacancy.php?id={vacancy_list.VACANCY_ID}&action=text', '#vacancy_text{vacancy_list.VACANCY_ID}')" style="cursor: pointer;">{vacancy_list.VACANCY_NAME}</a><br />
			<small><a href="{vacancy_list.VACANCY_URL}" target="_blank">{vacancy_list.VACANCY_URL}</a></small>
		</div>
		<div style="float:left; width:100px;">{vacancy_list.VACANCY_MANY}</div>
		<div style="float:left; width:100px;">{vacancy_list.VACANCY_RESPONSE}</div>
		<div style="float:left; width:50px;">
			<a onclick="$('#vacancy_text{vacancy_list.VACANCY_ID}').html('<iframe src=\'/dynamic/employer-vacancy.php?id={vacancy_list.VACANCY_ID}&amp;action=edit\' name=\'vacancy\' width=\'1000\' height=\'700\' scrolling=\'Auto\'></iframe>');" style="cursor: pointer;">
				<img src="/pic/ico/edit.gif" width="16" height="16" border="0" />
			</a>
		</div>
		<div style="clear:left;"></div>
		<div id="vacancy_text{vacancy_list.VACANCY_ID}"></div>
	</div>
<!-- END vacancy_list -->

<!-- BEGIN vacancy_text -->
	<div style="margin-left:50px;">
		{vacancy_text.VACANCY_TEXT}
	</div>
<!-- END vacancy_text -->

<!-- BEGIN vacancy_edit -->
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

// save,|,
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",

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
	<div style="margin-left:50px;">
		<form action="" method="post">
		<input name="vacancy_id" type="hidden" value="{vacancy_edit.VACANCY_ID}" />
		<input name="employer_id" type="hidden" value="{vacancy_edit.EMPLOYER_ID}" />
		<input name="action" type="hidden" value="{vacancy_edit.ACTION}" />
		<!--div style="float:left; width:150px;">Работодатель:</div>
		<div style="float:left;">ID: {vacancy_edit.EMPLOYER_ID}</div>
		<div style="clear:left;"></div-->
		<div style="float:left; width:150px;">Название вакансии:</div>
		<div style="float:left;">
			<span style="font-size:1px; color:#FFFFFF;">.</span>
			<input name="vacancy_name" type="text" value="{vacancy_edit.VACANCY_NAME}" size="70" maxlength="255" />
		</div>
		<div style="float:left; width:100px; margin-left:50px;"><input type="submit" name="save" value="Сохранить" /></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:150px;">URL:</div>
		<div style="float:left;">
			<span style="font-size:1px; color:#FFFFFF;">.</span>
			<input name="vacancy_url" type="text" value="{vacancy_edit.VACANCY_URL}" size="70" maxlength="255" />
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:150px;">Отклик:</div>
		<div style="float:left;">
			<span style="font-size:1px; color:#FFFFFF;">.</span>
			<select name="vacancy_response">
				{vacancy_edit.VACANCY_RESPONSE}
			</select>
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:150px;">З/п:</div>
		<div style="float:left;">
			<span style="font-size:1px; color:#FFFFFF;">.</span>
			<input name="vacancy_many" type="text" value="{vacancy_edit.VACANCY_MANY}" size="70" maxlength="255" />
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:150px;">Описание:</div>
		<div style="float:left;">
			<textarea id="elm1" name="page_text" rows="30" cols="80" style="width: 100%">{vacancy_edit.VACANCY_TEXT}</textarea>
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
		<div style="clear:left;"></div>
		</form>
	</div>
<!-- END vacancy_edit -->
