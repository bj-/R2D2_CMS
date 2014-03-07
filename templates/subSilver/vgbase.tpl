<!-- BEGIN switch_left_menu -->
<!-- END switch_left_menu -->
<!-- {SAVED} -->
<!-- BEGIN switch_form_sended -->
{switch_form_sended.TEXT}
<!-- END switch_form_sended -->
<script src="/script/jquery/js/jquery.form.js"></script>
<script>
function search() {
	$('#search_res_l').html('');
	$('#search_res_r').html('');
	var qString = $("#vgdb").formSerialize();
	show_content('/dynamic/mod/vg/vg_search.php?' + qString, '#search_res_l');
//	alert(qString);
};
function show_diapason(opt_id) {
	if (opt_id == 'all') {
		document.getElementById('coord_diapason').style.display="block"; 
	}
	else {
		document.getElementById('coord_diapason').style.display="none"; 
	};
};
</script>
<div>
<div style="float:left;  width:380px;">
<form action="" method="post" name="vgdb" id="vgdb">
<table>
<tr><th>Что</th><th>Где</th><th></th></tr>
<tr>
<td>
	<input name="id" id="id" type="text" value="{ID}" />
</td>
<td>
	<select name="ftype" id="ftype" onchange="show_diapason(document.getElementById('ftype').value)">
		<option value="id"{SEL_ID}>ID</option>
		<option value="all"{SEL_ALL}>Альянс</option>
		<!--option value="planet"{SEL_PLANET}>Планета</option-->
	</select>
</td>
<td><input name="go" onclick="search();" type="button" value="Искать" /></td></tr>
<tr><td colspan="3">
	<div id="coord_diapason" style="display:none;">
	<table><tr>
		<td style="text-align:right; padding-right:10px;">Координаты: с
		</td>
		<td>
			<input name="gstart" type="text" value="1" size="1" maxlength="2" /> :
			<input name="sstart" type="text" value="1" size="3" maxlength="5" /> :
			<input name="pstart" type="text" disabled="disabled" value="1" size="1" maxlength="2" readonly="readonly" />
		</td>
	</tr>
	<tr>
		<td style="text-align:right; padding-right:10px;">по</td>
		<td>
			<input name="gend" type="text" value="90" size="1" maxlength="2" /> :
			<input name="send" type="text" value="20000" size="3" maxlength="5" /> :
			<input name="pend" type="text" disabled="disabled" value="14" size="1" maxlength="2" readonly="readonly" />
		</td>
	</tr></table>
	</div>
	</td></tr>
</table>
</form>
</div>
<div style="float:left;">
Находим то что другие еще не потеряли. 
</div>
<div style="clear:left"></div>
</div>


<br />
<br />
<div>
	<div id="search_res_l" style="float:left; width:380px;">
	</div>
	<div id="search_res_r" style="float:left; width:320px;">
	</div>
	<div style="clear:left"></div>
</div>




<hr />
<br />
<p>
<h2>Микрохелп:</h2>
<div><strong>Поиск по ID:</strong></div>
<div class="pleft5px"><strong>Умеет искать:</strong>
	<div class="pleft10px">
		<strong>Блок первый:</strong>
		<div class="pleft10px">
			<strong>Ссылки:</strong><br />
			<div  class="pleft5px">
				- ID, со ссылкой на вконтакт<br />
				- Поиск по ID на офф форуме<br />
				- линк на лейбле "Альянсы:" - история изменения альянсов с датами.<br />
			</div>
			<strong>Данные:</strong><br />
			<div  class="pleft5px">
				- ID<br />
				- ФИО и ник из вкконтакта<br />
				- текущуй Онлайн игрока в контакте<br />
				- кол-во найденных планет и замеченное ОП<br />
				- Альянсы в которых был замечен игрок<br />
			</div>
		</div>
		<strong>Блок второй (планеты):</strong>
		<div class="pleft10px">
			<strong>Ссылки:</strong><br />
			<div  class="pleft5px">
				- название планеты - информация о планете (оборона, постройки) (второй клик спрячет скан)
			</div>
			<strong>Данные:</strong><br />
			<div  class="pleft5px">
				- Планеты в порядке возрастания<br />
				- Дата обновления карты для каждой планеты<br />
			</div>
		</div>
	</div>
</div>
<br />
<div><strong>Поиск по Альянсам:</strong></div>
<div class="pleft10px">
	<strong>Технологя поиска:</strong>
	<div class="pleft10px">
		- указываете часть названия альянса (любую) -> поиск -> получите список альянсов<br />
		- выбираете нужный вам альянс -> список первых 100 планет в данном альянсе (можно ограничить диапазон поиска)<br />
		- клик по ID игрока у любой планеты -> список планет данного игрока.<br />
	</div>
	<strong>Кросс-аллы:</strong>
	<div class="pleft10px">
		- Список альянсов в которых были замечены игроки из альянса по которому производите поиск. Иначе говоря - поиск твинкоалов<br />
	</div>
</div>		
</p>
<br />

<a onclick="ShowDiv('bugfix');" style="cursor:pointer;">Багфиксы</a><br />
<div id="bugfix" style="display:none;">
<small>
<p>
Правки: [+] - новое; [!] фикс; [*] - замечание.<br />
06.04<br />
[+] информация по аккаунту - техи, максимально замеченный флот (= максимальное количество юнитов одного вида на одной планете) из всех имеющихся сканов<br />
[+] информация по планете - постройки, оборона<br />
[+] ФИО, ник, состояние онлайна из вконтакта<br />
[+] История альянсов по ID игрока (кликаем на лейбл альянсы, после поиска информации по игроку)<br />
[+] меленький хелп по поиску :)
[*] апдейт карты 11-й галы<br />
30.03<br />
[+] - кнопочка "назад" в поиске по кроссам.<br />
[!] - кроссы показываются только зарегистрированным пользользователям.<br />
27.03<br />
[+] - Поиск кроссов по альянсам. ищет только кроссы по одному стартовому алу. иначе цикл бесконечный выходит.<br />
20.03<br />
[!] - при поиске по ID незарегистрированным пользователям не показываются названия альянсов в которых замечен ID и из названия планет показываются только первые 2 буквы<br />
12.03<br />
[+] - Поиск по альянсам. поиск выдает только первые 100 планет. ограничивайте диапазон поиска.<br />
[+] - Поиск по по ID на офф. форуме<br />
10.03<br />
[ ! ] - обработчик входных данных.<br />
09.03<br />
[+] - доступ ограничен. только зарегистрированные пользователи. за регистрацие общаться ко мне :)<br />
[+] - временным макаром прикручена альянсовая принадлежность.<br />
[+] - переработан скрипт импорта, база полностью импортированна заново.<br />
28.02<br />
[+] - добавляются пробелы к названиям - для более ровной вставки в скайп.<br />
<br />
Предложения:<br />
- история статусов: отпусков, протектов, ав<br />
- история альянсов<br />
- история ID<br />
- дерево принадлежности альянсов<br />

<br />
Баги:<br />
</p>
</small>
</div>

<p align="right">{ADMIN_LINK}</p>

