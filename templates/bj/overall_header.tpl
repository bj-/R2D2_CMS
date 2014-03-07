<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/xml; charset={S_CONTENT_ENCODING}" />
<meta name="Copyright" content='{SITE_DESCRIPTION} -- {SITENAME}' />
<!--link REL="SHORTCUT ICON" href="/favicon.ico" /-->

	<!-- Сохранено с сайта {SITE_DESCRIPTION} - http://{SITENAME} -->

<link href="/templates/{TEMPLATE_NAME}/css/common.css" rel="stylesheet" type="text/css" />
<link href="/script/jquery/css/smoothness/jquery-ui-1.8.6.custom.css" rel="stylesheet" type="text/css" />
<link href="/script/jquery/css/uploadify.css" rel="stylesheet" type="text/css" />
<link href="/script/highslide/highslide.css" rel="stylesheet" type="text/css" />
<!-- BEGIN switch_edit -->
<link type="text/css" href="/script/jquery/css/timepicker.css" rel="stylesheet" />
<!-- END switch_edit -->
<title>{SITE_DESCRIPTION} :: {PAGE_TITLE}</title>
<meta name="Classification" content="{CLASSIFICATION}" />
<meta name="Description" content="{PAGE_DESC}" />
<meta name="Keywords" content="{PAGE_KEYWORDS}" />
<!--html dir="{S_CONTENT_DIRECTION}" /-->
{META}
<script type="text/javascript" src="/script/r2d2_functions.js"></script>
<script type="text/javascript" src="/script/jquery/js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="/script/jquery/js/jquery-ui-1.8.6.custom.min.js"></script>
<script type="text/javascript" src="/script/jquery/ui/i18n/jquery.ui.datepicker-ru.js"></script>
<!-- BEGIN switch_enable_pm_popup -->
<script type="text/javascript">
<!--
	if ( {PRIVATE_MESSAGE_NEW_FLAG} )
	{
		window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
	}
//-->
</script>
<!-- END switch_enable_pm_popup -->
</head>
<body>
		<div class="admblock">
			<!-- BEGIN switch_user_logged_in -->
			<a href="/admin/" style="color:black; text-decoration:none;">Администрирование</a>&nbsp;&nbsp;<br />
			<a href="/{U_LOGIN_LOGOUT}" style="color:black; text-decoration:none;">{L_LOGIN_LOGOUT}</a>&nbsp;&nbsp;
			<!-- END switch_user_logged_in -->
		</div>
<!-- {BLOCK_1_EDIT}{BLOCK_1} -->
<script>
function image_replace(img_id, img_url) {
//	alert(img_id + img_url);
	document.getElementById(img_id).src=img_url; 
};

</script>

<!-- Menu TOP START -->
<div>
{TOP_MENU}
</div>
<!-- Menu TOP END -->

<!-- BEGIN body_header -->
			<!-- Left block END -->

			<!-- Page START -->
<!--
{_LEFT_MENU}
{_BLOCK_LEFT_1_EDIT}
{_BLOCK_LEFT_1}
-->
<!-- старт Контент -->
<!-- END body_header -->

<!-- BEGIN page_header -->
<!-- прочие страницы хидер -->
<div style="font-size:18px; font-weight:bold; padding-left:9px; padding-bottom:10px; background-color:#FFFFFF; ">{CURRENT_PARAGRAF_NAME_TEXT}</div>

<div style="position:absolute; top: -5px; right:-8px">{EDIT}</div>
{CURRENT_PARAGRAF_NAME}
<!-- END page_header -->

<!-- BEGIN admin_body_header -->
<!--
*===================================*
*                                   *
*      дизайн админской части       *
*                                   *
*===================================*
-->
<div align="center">
	<div class="middleblock" style="text-align:left;">


<table width="100%" border="0" cellspacing="0" cellpadding="20">
<tr valign="top">
<td style="width: 200px; border-right-color: Gray; border-right-width: 1px; border-right-style: solid;">

	<!-- BEGIN switch_article_prop_menu -->
		<strong><a href="/admin/article_prop.php?id={PAGE_ID}">Атрибуты статьи</a></strong><br>
		<br>
		<strong>Галерея</strong>&nbsp;&nbsp;&nbsp;
		<!-- BEGIN x_switch_smgal_off -->
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery=upload"><img src="/pic/ico/upload.png" width="16" height="16" alt="Загрузка" border="0" /></a>&nbsp;
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery=edit"><img src="/pic/ico/edit.gif" width="16" height="16" alt="Редактирование" border="0" /></a>
		<!-- END x_switch_smgal_off -->
		<br>
		Фото: {GALLERY_ONOFF}
		<!-- BEGIN switch_smgal_on -->
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery=on" title="Включить галлерею"><img src="/pic/ico/stock_form-checkbox.png" width="16" height="16" border="0" alt="Включить" /></a><br>
		<!-- END switch_smgal_on -->
		<!-- BEGIN switch_smgal_off -->
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery=upload"><img src="/pic/ico/upload.png" width="16" height="16" alt="Загрузка" border="0" /></a>&nbsp;
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery=edit"><img src="/pic/ico/edit.gif" width="16" height="16" alt="Редактирование" border="0" /></a>&nbsp;
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery=off" title="Отключить галлерею"><img src="/pic/ico/delete.png" width="16" height="16" alt="Отключить" border="0" /></a><br>
		<!-- END switch_smgal_off -->
		Видео: {VGALLERY_ONOFF}
		<!-- BEGIN switch_smvideogal_on -->
		<a href="/admin/article_prop.php?id={PAGE_ID}&svgallery=on" title="Включить галлерею"><img src="/pic/ico/stock_form-checkbox.png" width="16" height="16" border="0" alt="Включить" /></a><br>
		<!-- END switch_smvideogal_on -->
		<!-- BEGIN switch_smvideogal_off -->
		<a onclick="show_content('/admin/gallery/dynamic/adm_dyn_upload.php?id={PAGE_ID}&type=smvideo&redirect_url=/admin/article_prop.php?id={PAGE_ID}&svgallery=upload', '#item-add')" title="Добавить видео">
			<img src="/pic/ico/film_add.png" alt="Добавить видео" width="16" height="16" border="0" style="cursor:pointer;" />
		</a>&nbsp;
		<!--a href="/admin/article_prop.php?id={PAGE_ID}&svgallery=upload"><img src="/pic/ico/upload.png" width="16" height="16" alt="Загрузка" border="0" /></a>&nbsp;-->
		<a href="/admin/article_prop.php?id={PAGE_ID}&svgallery=edit"><img src="/pic/ico/edit.gif" width="16" height="16" alt="Редактирование" border="0" /></a>&nbsp;
		<a href="/admin/article_prop.php?id={PAGE_ID}&svgallery=off" title="Отключить галлерею"><img src="/pic/ico/delete.png" width="16" height="16" alt="Отключить" border="0" /></a><br>
		<!-- END switch_smvideogal_off -->
		<br />
		
		<br>
		<strong>Форма обратной связи</strong><br>
		Форма {FORM_ONOFF}
		<!-- BEGIN switch_form_on -->
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery={SGALLERY_EDITTYPE}&gallery=on"><img src="/pic/ico/stock_form-checkbox.png" width="16" height="16" border="0" alt="Включить" /></a><br>
		<!-- END switch_form_on -->
		<!-- BEGIN switch_form_off -->
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery={SGALLERY_EDITTYPE}&gallery=off"><img src="/pic/ico/delete.png" width="16" height="16" alt="Отключить" border="0" /></a><br>
		<!-- END switch_form_off -->
		<br>
		<br>
		<a href="{ARTICLE_PATH}">К статье</a><br>		
	<!-- END switch_article_prop_menu -->

	<!-- BEGIN switch_event_prop_menu -->
		<strong><a href="/admin/events_prop.php?id={PAGE_ID}">Атрибуты</a></strong><br>
		<br>
		<strong>Фото Галерея</strong>&nbsp;&nbsp;&nbsp;<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=upload"><img src="/pic/ico/upload.png" width="16" height="16" alt="Загрузка" border="0" /></a>&nbsp;
		<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=edit"><img src="/pic/ico/edit.gif" width="16" height="16" alt="Редактирование" border="0" /></a><br>
		{GALLERY_ONOFF}
		<!-- BEGIN switch_smgal_on -->
		<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=on" title="Включить галлерею"><img src="/pic/ico/stock_form-checkbox.png" width="16" height="16" border="0" alt="Включить" /></a><br>
		<!-- END switch_smgal_on -->
		<!-- BEGIN switch_smgal_off -->
		<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=off" title="Отключить галлерею"><img src="/pic/ico/delete.png" width="16" height="16" alt="Отключить" border="0" /></a><br>
		<!-- END switch_smgal_off -->
		<br>
		<strong>Видео Галерея</strong>&nbsp;&nbsp;&nbsp;<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=v-upload"><img src="/pic/ico/upload.png" width="16" height="16" alt="Загрузка" border="0" /></a>&nbsp;
		<a href="/admin/events_prop.php?id={PAGE_ID}&sgallery=v-edit"><img src="/pic/ico/edit.gif" width="16" height="16" alt="Редактирование" border="0" /></a><br>
		{VGALLERY_ONOFF}
		<!-- BEGIN switch_smvideogal_on -->
		<a href="/admin/events_prop.php?id={PAGE_ID}&svgallery=on" title="Включить галлерею"><img src="/pic/ico/stock_form-checkbox.png" width="16" height="16" border="0" alt="Включить" /></a><br>
		<!-- END switch_smvideogal_on -->
		<!-- BEGIN switch_smvideogal_off -->
		<a href="/admin/events_prop.php?id={PAGE_ID}&svgallery=off" title="Отключить галлерею"><img src="/pic/ico/delete.png" width="16" height="16" alt="Отключить" border="0" /></a><br>
		<!-- END switch_smvideogal_off -->
		<br>
		<br>
		
		<br>
		<br>
		<a href="{ARTICLE_PATH}">К статье</a><br>
	<!-- END switch_event_prop_menu -->

	<!-- BEGIN siteadmin_left_menu -->
	<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td>
	<a href="/admin/index.php?edit=prop">Настройки сайта</a><br>
	<a href="/admin/index.php?edit=menu">Редактирование меню</a><br>
	<a href="/admin/index.php?edit=blocks">Блоки</a><br>
	<br>
	Статьи:<br>
	<a href="/admin/article_edit.php?add=1&adm=1">Создать новую статью</a><br>
	<a href="/admin/index.php?edit=articles">Список всех статей</a><br>
	<br>
	Фотогалерея:<br>
	<a href="/admin/index.php?edit=gallery">Доступные галереи</a><br>
	<a href="/admin/index.php?edit=gallery&cat_type=hidden">Служебные галереи</a><br>
	<a href="/admin/index.php?edit=gallery&cat_type=del">Удаленные галереи</a><br>

	<br>
	Формы:<br>
	<a href="/admin/form/index.php">Формы</a><br>
	<a href="#">Просмотр сообщений</a><br>
	<br />

<br /><br /><br /><hr />
	<a href="/about.php?r2d2">Об авторе</a><br />
	<a href="/about.php?license">Лицензия</a><br />
	<a href="/about.php?news">Исправления</a><br />
	</td></tr></table>
<!-- END siteadmin_left_menu -->
</td>
<td>
<!-- END admin_body_header -->
