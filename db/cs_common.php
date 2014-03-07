<?php
// конфигурация (остальное в db.php)

//include $DRoot . '/db/db.php';
include $DRoot . '/db/pathdesc.php';
include($DRoot . '/db/extension.inc');
//include($DRoot . '/db/pbb_common.'.$phpEx);


function gen_url($url) {
// парсим урл для домашнего и серверного использования с и без мод-реврайта
	global $localhost;
	$ret = '';
	if ($localhost) {
		$lang = '';
		if (!strpos($url, "/")) { 
			$surl = substr($url, 1); 
			$lang = substr($surl, 0, strpos($surl, "/"));
			$url = substr($surl, strpos($surl, "/"));
		};
		$ret = "/article.php?lang=".$lang."&article=".$url;
		}
	Else {
		$ret = $url;
	};
	return $ret;
};

$header = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="Copyright" content="Фонд Комфортная страна -- http://www.comfortnayastrana.ru">
<!--LINK REL="SHORTCUT ICON" href="/favicon.ico"-->

	<!-- Сохранено с сайта Фонда "Комфортная страна" -- http://www.comfortnayastrana.ru -->

<link href="/script/fonts_menu.css" rel="stylesheet" type="text/css">
<link href="/script/common.css" rel="stylesheet" type="text/css">
<SCRIPT type="text/javascript" src="/script/menu.js"></SCRIPT>';

$ptop = '</head>
<body topmargin="0" style="font-family: Arial, Helvetica, sans-serif; font-size: smaller;">

<!-- START top-->
<table width="962" cellspacing="1" cellpadding="0" align="center" background="/pic/head_t.jpg" class="maintable"><tr><td height="93" align="right" valign="top">
<!--table border="0" cellspacing="0" cellpadding="0" align="right" bgcolor="#FFFFFF"><tr>
<td class="lr_padding">Юзернейм</td>
<td class="lr_padding">Логаут</td>
</tr></table-->
</td></tr></table>
<table width="962" border="0" cellspacing="0" cellpadding="0" align="center" class="maintable"><tr><td width="100" background="/pic/tmenubar.gif"></td><td>
<table id="header2" width="100%" border="0" cellspacing="0" cellpadding="0" align="center" background="/pic/tmenubar.gif"><TBODY>
<TR>
<TD class="menunav">
<DIV id="navbox"><UL id="nav">
	<li><img src="/pic/tmenusep.gif" alt="" width="2" height="30" border="0"></li>
	<LI class="navtop"><a href="'.gen_url('/ru/country/').'">Страна</a><UL>
		<LI style="color:#000000; PADDING: 8px;">Государственное устройство и муниципальное управление</LI>
		<LI><A href="'.gen_url('/ru/country/legislation/').'">Проекты законов</A></LI>
		<LI><A href="'.gen_url('/ru/country/foreign/').'">Зарубежный опыт в устройстве комфортной жизни</A>
		<LI><A href="'.gen_url('/ru/country/domestic/').'">Примеры комфортной жизни в России</A>
		<LI><A href="'.gen_url('/ru/country/suggestion/').'">Ваши предложения</A>
			<!--UL>
				<LI><A href="#">sub Меню 1-2-1</A></LI>
				<LI><A href="#">sub Меню 1-2-2</A></LI>
			</UL-->
		</LI>
	</UL></LI>
	<li><img src="/pic/tmenusep.gif" alt="" width="2" height="30" border="0"></li>
	<LI><A href="#">Человек</A><UL>
		<LI style="color:#000000; PADDING: 8px;">Воспитание творческой созидательной личности и гражданина</LI>
		<LI><A href="#">Зарубежный опыт</A></LI>
		<LI><A href="#">Отечественный опыт</A></LI>
		<LI><A href="#">Ваши предложения</A></LI>
	</UL></LI>
	<li><img src="/pic/tmenusep.gif" alt="" width="2" height="30" border="0"></li>
	<LI><A href="#">Жизнь</A><UL>
		<LI style="color:#000000; PADDING: 8px;">Семья как основа комфортной жизни<br>Комфортный дом как благополучие страны<br>Территории для комфортной жизни</LI>
		<LI><A href="#">Зарубежный опыт</A></LI>
		<LI><A href="#">Отечественный опыт</A></LI>
		<LI><A href="#">Ваши предложения</A></LI>
	</UL></LI>
	<li><img src="/pic/tmenusep.gif" alt="" width="2" height="30" border="0"></li>
	<LI><A href="#">Образование</A><UL>
		<LI style="color:#000000; PADDING: 8px;">Концепции и программы образования как основа эффективного развития страны</LI>
		<LI><A href="#">Зарубежный опыт</A></LI>
		<LI><A href="#">Отечественный опыт</A></LI>
		<LI><A href="#">Предложения по обучению в России и за рубежом</A></LI>
		<LI><A href="#">Ваши предложения</A></LI>
	</UL></LI>
	<li><img src="/pic/tmenusep.gif" alt="" width="2" height="30" border="0"></li>
	<LI><A href="#">Финансы</A><UL>
		<LI style="color:#000000; PADDING: 8px;">Бюджет и налоги комфортной страны</LI>
		<LI><A href="#">Зарубежный опыт</A></LI>
		<LI><A href="#">Отечественный опыт</A></LI>
		<LI><A href="#">Ваши предложения</A></LI>
	</UL></LI>
	<li><img src="/pic/tmenusep.gif" alt="" width="2" height="30" border="0"></li>
	<LI><A href="#">Бизнес</A><UL>
		<LI style="color:#000000; PADDING: 8px;">Малые и средние предприятия</LI>
		<LI><A href="#">Зарубежный опыт</A></LI>
		<LI><A href="#">Отечественный опыт</A></LI>
		<LI><A href="#">Семинары, конференции, деловые поездки и контакты</A></LI>
		<LI><A href="#">Ваши предложения</A></LI>
	</UL></LI>
	<li><img src="/pic/tmenusep.gif" alt="" width="2" height="30" border="0"></li>
	<LI><A href="#">Творчество</A><UL>
		<LI style="color:#000000; PADDING: 8px;">Техническое и художественное творчество <br>как основа благосостояния комфортной страны</LI>
		<LI><A href="#">Зарубежный опыт</A></LI>
		<LI><A href="#">Отечественный опыт</A></LI>
		<LI><A href="#">Ваши предложения</A></LI>
	</UL></LI>
	<li><img src="/pic/tmenusep.gif" alt="" width="2" height="30" border="0"></li>
	<LI><A href="#">Экология</A><UL>
		<LI style="color:#000000; PADDING: 8px;">Экологическая и общая безопасность</LI>
		<LI><A href="#">Зарубежный опыт</A></LI>
		<LI><A href="#">Отечественный опыт</A></LI>
		<LI><A href="#">Ваши предложения</A></LI>
	</UL></LI>
	<li><img src="/pic/tmenusep.gif" alt="" width="2" height="30" border="0"></li>
	<LI><A href="#">Отдых</A><UL>
		<LI style="color:#000000; PADDING: 8px;">Отдых, туризм, выставки, фестивали</LI>
		<LI><A href="#">Предложения по России</A></LI>
		<LI><A href="#">Предложения за рубежом</A></LI>
		<LI><A href="#">Ваши предложения</A></LI>
	</UL></LI>
	<li><img src="/pic/tmenusep.gif" alt="" width="2" height="30" border="0"></li>        		
</UL></DIV>
<IMG alt="" src="quest_files/spacer.gif" height="0">
</TD></TR>
</TBODY></TABLE>
</td></tr>
</table>
<table width="962" cellspacing="0" cellpadding="0" align="center" background="/pic/head_b.jpg" class="maintable"><tr><td height="8" align="right" valign="top"></td>
<tr><td height="9" background="/pic/topbargradient.jpg" style="background-repeat: no-repeat; background-color: #FFFF00; background-position: left; background-image: url(/pic/topbargradient.jpg);"><span class="px">&nbsp;</span></td></tr>
</tr></table>';

$menuleftstart = '<table width="962" border="0" cellspacing="0" cellpadding="0" align="center" class="maintable">
<tr valign="top"><td width="200" bgcolor="#CCCCCC" style="background-image: url(/pic/vpatten.gif); background-position: top; background-repeat: repeat-x;">';
$menul = '
	<!--table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td><a href="#" class="vmenu">Меню?</a></td></tr></table><img src="pic/bluepx.gif" alt="" width="200" height="1" border="0"><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td><a href="#" class="vmenu">Меню?</a></td></tr></table><img src="pic/bluepx.gif" alt="" width="200" height="1" border="0"><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td><a href="#" class="vmenu">Меню?</a></td></tr></table><img src="pic/bluepx.gif" alt="" width="200" height="1" border="0"><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td><a href="#" class="vmenu">Меню?</a></td></tr></table><img src="pic/bluepx.gif" alt="" width="200" height="1" border="0"><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td><a href="#" class="vmenu">Меню?</a></td></tr></table><img src="pic/bluepx.gif" alt="" width="200" height="1" border="0"><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td><a href="#" class="vmenu">Меню?</a></td></tr></table><img src="pic/bluepx.gif" alt="" width="200" height="1" border="0"><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td><a href="#" class="vmenu">Меню?</a></td></tr></table><img src="pic/bluepx.gif" alt="" width="200" height="1" border="0"><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td><a href="#" class="vmenu">Меню?</a></td></tr></table><img src="pic/bluepx.gif" alt="" width="200" height="1" border="0"><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td><a href="#" class="vmenu">Меню?</a></td></tr></table><img src="pic/bluepx.gif" alt="" width="200" height="1" border="0"><br-->
	';
$menuleftend = '</td><td width="1" bgcolor="#043198"></td><td>
	<table width="100%" border="0" cellspacing="0" cellpadding="8"><tr><td>';

$bottom = '	</td></tr></table>
</td></tr></table>
<table width="962" border="0" cellspacing="0" cellpadding="0" align="center" background="/pic/bmenubar.gif" class="maintable"><tr align="center">
<td width="200" height="22">&nbsp;</td><td width="2"><img src="/pic/bmenusep.gif" alt="" width="2" height="22" border="0"></td>
	<td><a href="http://www.kremlin.ru/" target="_blank" class="bottommenu">Президент</a></td><td width="2"><img src="/pic/bmenusep.gif" alt="" width="2" height="22" border="0"></td>
	<td><a href="http://www.government.ru" target="_blank" class="bottommenu">Правительство</a></td><td width="2"><img src="/pic/bmenusep.gif" alt="" width="2" height="22" border="0"></td>
	<td><a href="#" target="_blank" class="bottommenu">Партии</a></td><td width="2"><img src="/pic/bmenusep.gif" alt="" width="2" height="22" border="0"></td>
	<td><a href="#" target="_blank" class="bottommenu">Фонды</a></td><td width="2"><img src="/pic/bmenusep.gif" alt="" width="2" height="22" border="0"></td>
	<td><a href="#" target="_blank" class="bottommenu">Народ</a></td><td width="2"><img src="/pic/bmenusep.gif" alt="" width="2" height="22" border="0"></td>
	<td><a href="#" target="_blank" class="bottommenu">Меценаты</a></td><td width="2"><img src="/pic/bmenusep.gif" alt="" width="2" height="22" border="0"></td>
	<td><a href="http://www.patriarchia.ru/" target="_blank" class="bottommenu">Церковь</a></td><td width="2">
</tr></table>

</body>
</html>';

// формируем левое меню
$menuleft = $menuleftstart . $menul . $menuleftend;

// формируем верх странички
$pagetop = $ptop . $menuleft;

// формируем низ странички
$pagebottom = $bottom;
?>