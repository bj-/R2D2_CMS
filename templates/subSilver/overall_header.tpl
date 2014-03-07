<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/xml; charset={S_CONTENT_ENCODING}" />
<meta name="Copyright" content='{SITE_DESCRIPTION} -- {SITENAME}' />
<!--link REL="SHORTCUT ICON" href="/favicon.ico" /-->

	<!-- Сохранено с сайта {SITE_DESCRIPTION} - http://{SITENAME} -->

<link href="/script/common.css" rel="stylesheet" type="text/css" />
<link href="/script/jquery/css/smoothness/jquery-ui-1.8.6.custom.css" rel="stylesheet" type="text/css" />
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
<script type="text/javascript" src="/script/jquery/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/script/jquery/js/jquery-ui-1.8.6.custom.min.js"></script>
<script type="text/javascript" src="/script/jquery/ui/i18n/jquery.ui.datepicker-ru.js"></script>
<script language="javascript">
function topmenufocus(menuid, act, menudesc) {
	if (act) {
		$("#menu-"+menuid).css("background-image","url(/pic/tab_right2.png)");
		$("#menu-lb-"+menuid).css("background-image","url(/pic/tab_left2.png)");
		$('#top_menu_desc').html(menudesc);
	}
	else {
		$("#menu-"+menuid).css("background-image","url(/pic/tab_right.png)");
		$("#menu-lb-"+menuid).css("background-image","url(/pic/tab_left.png)");
	};
};
</script>
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
<div style="position:absolute; right:20px; top:10px; color:white; background-color:#030303; padding:5px;">
	<!-- BEGIN switch_user_logged_in -->
	<a href="/admin/" style="color:white; text-decoration:none;">Администрирование</a>&nbsp;&nbsp;<br />
	<a href="/{U_LOGIN_LOGOUT}" style="color:white; text-decoration:none;">{L_LOGIN_LOGOUT}</a>&nbsp;&nbsp;
	<!-- END switch_user_logged_in -->
	<!-- BEGIN switch_user_logged_out -->
<form method="post" action="{S_LOGIN_ACTION}">
<table border="0" cellspacing="0" cellpadding="0" align="right">
<tr><td colspan="2" class="lr_padding"><input type="text" name="username" id="uLogin" value="{L_USERNAME}" size="10" maxlength="255" class="input"  onclick="clear_field(uLogin)" onblur="unclear_field(uLogin)" />
{L_PASSWORD}: <input type="password" name="password" size="10" maxlength="32" value="" class="input" />
<input type="submit" name="login" value="{L_LOGIN}" class="input" /></td></tr>
<tr><td style="padding-left: 5px; font-size: 10px;"><input type="checkbox" name="autologin" value="1" checked="checked" style="width: 10px; height: 15px;" /> {L_AUTO_LOGIN}</td><td align="right" class="lr_padding" style="font-size: 10px;"><a href="{U_REGISTER}">{L_REGISTER}</a></td></tr>
</table>
</form>
<script language="Javascript" type="text/javascript">
function clear_field(fld) {
	if (fld.value == "{L_USERNAME}") {
		fld.value = "";
	};
	if (fld.value == "{L_PASSWORD}") { 
		fld.value = "";
	};
};
function unclear_field(fld) {
	if (fld.value == "") {
		fld.value = "{L_USERNAME}";
	};
};
</script>
	<!-- END switch_user_logged_out -->
</div>
<div class="top"><div class="top2">
	<div class="logo"><a href="/"><img src="/pic/im_logo.png" width="151" height="93" onmouseover="$('#top_menu_desc').html('IMA OSS + Мыши Рокеры с Марса');" border="0" /></a>
	<!--a title="IMA OSS" style="cursor:crosshair" onmouseover="$('#top_menu_desc').html('IMA OSS + Мыши Рокеры с Марса');">I.</a><a title="Mouse from Mars" style="cursor:crosshair" onmouseover="$('#top_menu_desc').html('IMA OSS + Мыши Рокеры с Марса');">M.</a--></div>
</div></div>
<div class="topmenubar">
	<!-- старт Главное меню -->
	{TOP_MENU}
	<!-- конец Главное меню -->
<!--
	<div id="menu-lb-site" class="topmenuleftborder"></div>
	<div id="menu-site" class="topmenu" onmouseover="topmenufocus('site',1,'Главная страница')" onmouseout="topmenufocus('site',0,'')">
		<a id="menu-link-site" href="#" class="black">Сайт АЛЬЯНСА</a>
	</div>
	<div id="menu-lb-forum" class="topmenuleftborder"></div>
	<div id="menu-forum" class="topmenu" onmouseover="topmenufocus('forum',1,'Форум')" onmouseout="topmenufocus('forum',0,'')">
		<a id="menu-link-forum" href="#" class="black">Форум</a>
	</div>
	<div id="menu-lb-school" class="topmenuleftborder"></div>
	<div id="menu-school" class="topmenu" onmouseover="topmenufocus('school',1,'База знаний, учебные мотериалы')" onmouseout="topmenufocus('school',0,'')">
		<a id="menu-link-school" href="#" class="black">Учебка</a>
	</div>
	<div id="menu-lb-base" class="topmenuleftborder"></div>
	<div id="menu-base" class="topmenu" onmouseover="topmenufocus('base',1,'Поиск планет, флотов по ID')" onmouseout="topmenufocus('base',0,'')">
		<a id="menu-link-base" href="#" class="black">База ID</a>
	</div>
-->
	<div style="clear:left;"></div>
	<div class="topmenudesc"><div id="top_menu_desc" style="margin-left:50px;">I.M. Alliance</div></div>
	<div style="clear:left;"></div>
</div>



<!-- BEGIN body_header -->
<div id="body_block" style="margin-top:30px; margin-left:20px; width:980px;">
	<div id="left_menu" style="float:left; color:white; width:250px;">
		<div class="lmenutop">Назв. Раздела.</div><div style="clear:left;"></div>
		<div class="lmenu">пункт меню</div><div style="clear:left;"></div>
		<div class="lmenu">пункт меню</div><div style="clear:left;"></div>
		<div class="lmenu">пункт меню</div><div style="clear:left;"></div>
		<div class="lmenubottom"></div>
	</div>
	<div id="textbox" class="textbox">
<!-- старт Контент -->
<!-- END body_header -->

<!-- BEGIN admin_body_header -->
<!--
*===================================*
*                                   *
*      дизайн админской части       *
*                                   *
*===================================*
-->
<div class="minmax" style="margin-top: 5px;">

<table width="100%" border="0" cellspacing="0" cellpadding="20">
<tr valign="top">
<td style="width: 200px; border-right-color: Gray; border-right-width: 1px; border-right-style: solid;">

	<!-- BEGIN switch_article_prop_menu -->
		<strong><a href="/admin/article_prop.php?id={PAGE_ID}">Атрибуты статьи</a></strong><br>
		<br>
		<strong>Галерея</strong>&nbsp;&nbsp;&nbsp;
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery=upload"><img src="/pic/ico/upload.png" width="16" height="16" alt="Загрузка" border="0" /></a>&nbsp;
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery=edit"><img src="/pic/ico/edit.gif" width="16" height="16" alt="Редактирование" border="0" /></a><br>
		Галерея {GALLERY_ONOFF}
		<!-- BEGIN switch_smgal_on -->
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery=on" title="Включить галлерею"><img src="/pic/ico/stock_form-checkbox.png" width="16" height="16" border="0" alt="Включить" /></a><br>
		<!-- END switch_smgal_on -->
		<!-- BEGIN switch_smgal_off -->
		<a href="/admin/article_prop.php?id={PAGE_ID}&sgallery=off" title="Отключить галлерею"><img src="/pic/ico/delete.png" width="16" height="16" alt="Отключить" border="0" /></a><br>
		<!-- END switch_smgal_off -->
		
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

	<br />
	Церковный календарь<br />
	<a href="/admin/cruch_calendar.php">Обслуживание</a><br />
	
<br /><br /><br /><hr />
	<a href="/ru/about_r2d2-3.html">Об авторе</a><br />
	<a href="/ru/license-2.html">Лицензия</a><br />
	<a href="/ru/debug-1.html">Исправления</a><br />
	</td></tr></table>
<!-- END siteadmin_left_menu -->
</td>
<td>
<!-- END admin_body_header -->

<!-- BEGIN switch_left_menu -->
<!-- END switch_left_menu -->
