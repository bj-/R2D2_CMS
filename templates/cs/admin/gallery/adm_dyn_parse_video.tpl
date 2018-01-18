<!-- BEGIN show_data -->
<form action="?edit=video&cat={PAGE_ID}&id={PAGE_ID}" method="post">
	<input name="video_type" type="hidden" value="parsed">
	<input name="video_prov" type="hidden" value="youtube">
	<input name="video_id" type="hidden" value="{VIDEO_ID}">
	<input name="video_size" type="hidden" value="{VIDEO_W};{VIDEO_H}">
	<input name="video_thumb" type="hidden" value="{VIDEO_THUMB_URL_S}">
	<input name="galtype" type="hidden" value="{GALTYPE}">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="130px">Название</td>
			<td><input type="text" name="video_name" value="{VIDEO_TITLE}" size="93"></td>
			<!--td rowspan="2">
				<img src="" border="0" />
			</td-->
		</tr>
		<tr><td>Описание</td><td><textarea cols="70" rows="5" name="video_desc">{VIDEO_CONTENT}</textarea></td></tr>
		<!--tr>
			<td>Код ролика</td>
			<td>
				<textarea cols="70" rows="5" name="video_code"></textarea>
			</td>
		</tr-->
		<tr><td></td><td><input type="submit" name="video_add" value="Добавить"></td></tr>
		<tr>
			<td></td>
			<td>
			<p><br/></p>
				<iframe width="560" height="345" src="http://www.youtube.com/embed/{VIDEO_ID}" frameborder="0" allowfullscreen></iframe>
			</td>
		</tr>
	</table>
</form>
<!-- END show_data -->

<!-- BEGIN set_url -->
	<!-- BEGIN url_not_resolved -->
		Данные о ролике отсутсвуют. Вероятно указан неверно URL ролика.
	<!-- END url_not_resolved -->
<form action="/admin/index.php?edit=video&cat={PAGE_ID}" method="post" id="yt_parse_url" onSubmit="parse_yt_video('yt_video_url', 'yt')">
	<table>
		<tr><td>Адрес ролика:</td><td><input type="text" name="video_url" id="yt_video_url" value="{VIDEO_URL}" size="93"></td></tr>
		<tr><td></td><td><input name="video_add" id="video_add" type="button" onclick="parse_yt_video('yt_video_url', 'yt')" value="Получить данные о ролике" /></td></tr>
	</table>
</form>
<!-- END set_url -->
