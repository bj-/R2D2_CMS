<style>
.employer_block { border-bottom:1px solid gray; padding-left:10px;}
.employer_num {width:45px; float:left;}
.employer_comp { width:270px; float:left;}
.employer_cont {width:120px; float:left;}
.employer_addr {width:300px; float:left;}
.employer_vac {width:200px; float:left;}
.employer_stat {width:330px; float:left;}
.employer_os {width:120px; float:left;}
.employer_act {width:50px; float:left;}
</style>

<!-- {SAVED} -->
<!-- BEGIN switch_form_sended -->
{switch_form_sended.TEXT}
<!-- END switch_form_sended -->

<p>--------------------------------------</p>

<table>
<tr>
	<td>Тип</td>
	<td>
		<select>
			<option>Диод</option>
			<option>Резистор</option>
			<option>Транзистор</option>
			<option>Оптопара</option>
		</select>
	</td>
</tr>
</td>	
<table>

<table width="95%" border="1" cellspacing="0" cellpadding="0">
<tr>
	<td>Тип</td>
	<td>Название</td>
	<td>Кол-во</td>
	<td>Производитель</td>
	<td>Материал</td>
	<td>U Forw</td>
	<td>U Rev Max</td>
	<td>U Rev Imp Max</td>
	<td>I Forw Max A</td>
	<td>I Forw Imp Max A</td>
	<td>I Rev Max A</td>
	<td>Корп.</td>
	<td>Темп.</td>
	<td>Посадка</td>
	<td>Чертеж</td>
	<td>Шит</td>
</tr>

</table>
id 
type 
name 
quantity 
manuf 
material 
UForw 
URevMax 
URevImpMax 
IforwMaxA 
IforwImpMaxA 
IRevMaxA 
case 
tWork 
mounting 
design 
dSheet

<p>--------------------------------------</p>

<div class="employer_block">
	<div class="employer_num" style="float:left;">№</div>
	<div class="employer_comp" style="text-align: center; font-weight:bold;">
		<form action="" method="get" name="fs">
			Компания 
		<a onclick="show_content('/dynamic/employer-edit.php?id={employers_list.EMPLOYER_ID}&action=add_employer', '#employer-edit{employers_list.EMPLOYER_ID}')" style="cursor: pointer;"><img src="/pic/ico/page_add.gif" width="16" height="16" border="0" /></a> <input name="employer_search" id="employer_search" type="text" value="Поиск..." size="15" onclick="clear_field('employer_search', 'Поиск...')" onblur="unclear_field('employer_search', 'Поиск...')" onchange="show_content('/dynamic/dyn-employer-search.php?text=' + document.getElementById('employer_search').value, '#search_result')" />
		</form>
	</div>
	<div class="employer_addr" style="text-align: center; font-weight:bold;">Адрес</div>
	<div class="employer_vac" style="text-align: center; font-weight:bold;">Вакансии</div>
	<div class="employer_stat" style="text-align: center; font-weight:bold;">Отклик<br />J-сайт/директом/(фейком)</div>
	<div class="employer_os" style="text-align: center; font-weight:bold;">Направление/OS</div>
	<div class="employer_act" style="text-align: center;">.</div>
	<div style="clear: left;"></div>
	<div id="search_result"></div>
</div>
<div id="employer-edit"></div>
<!-- BEGIN employers_list -->
<div class="employer_block" id="employer-edit{employers_list.EMPLOYER_ID}">
	<a name="#employer{employers_list.EMPLOYER_ID}"</a>
		<div class="employer_num">
			{employers_list.EMPLOYER_NUM}&nbsp;
			<a onclick="show_content('/dynamic/employer-edit.php?id={employers_list.EMPLOYER_ID}&action=edit', '#employer-edit{employers_list.EMPLOYER_ID}')" style="cursor: pointer;"><img src="/pic/ico/edit.gif" width="14" height="14" border="0" /></a>
		</div>
		<div class="employer_comp">
			<strong><a onclick="show_content('/dynamic/employer-edit.php?id={employers_list.EMPLOYER_ID}&action=details', '#details_employer{employers_list.EMPLOYER_ID}')" style="cursor: pointer;">
				{employers_list.EMPLOYER_NAME}
			</a></strong></div>
		<div class="employer_addr">м. {employers_list.EMPLOYER_METRO}, {employers_list.EMPLOYER_ADDRESS}</div>
		<div class="employer_vac">
			<a onclick="show_content('/dynamic/employer-vacancy.php?id={employers_list.EMPLOYER_ID}&action=list', '#vacancy_list_employer{employers_list.EMPLOYER_ID}')" style="cursor: pointer;">список вакансий</a>
			<a onclick="$('#vacancy_list_employer{employers_list.EMPLOYER_ID}').html('<iframe src=\'/dynamic/employer-vacancy.php?employer_id={employers_list.EMPLOYER_ID}&amp;action=add\' name=\'vacancy\' width=\'1000\' height=\'700\' scrolling=\'Auto\'></iframe>');" style="cursor: pointer;">
				<img src="/pic/ico/page_add.gif" width="14" height="14" border="0" />
			</a>
		</div>
		<div class="employer_stat">
			<div style="float:left; width:80px;">{employers_list.EMPLOYER_RESP_SITE}</div>
			<div style="float:left; width:80px; padding-left:5px; padding-right:5px;">{employers_list.EMPLOYER_RESP_DIRECT}</div>
			<div style="float:left; width:80px;">{employers_list.EMPLOYER_RESP_FAKE}</div>
			<div style="clear:left;"></div>
		</div>
		<div class="employer_os">{employers_list.EMPLOYER_OS}</div>
		<div class="employer_act">
			<a onclick="show_content('/dynamic/employer-edit.php?id={employers_list.EMPLOYER_ID}&action=edit', '#employer-edit{employers_list.EMPLOYER_ID}')" style="cursor: pointer;"><img src="/pic/ico/edit.gif" width="14" height="14" border="0" /></a>
			</div>
	<div style="clear: left;"></div>
	<div id="details_employer{employers_list.EMPLOYER_ID}" style="margin-left:50px;"></div>
	<div id="vacancy_list_employer{employers_list.EMPLOYER_ID}" style="margin-left:50px;"></div>
</div>
<!-- END employers_list -->

<!--
('/dynamic/employer-vacancy.php?id=1&action=edit')
 document.window.openDialog('/index.php', 'aaaa')
<!-- Добавить работодателя

<div>
Название
URL
E-Mail
Job E-mail
Метро
Адрес
</div>
-->
{ARTICLE}
<p align="right">{ADMIN_LINK}</p>
