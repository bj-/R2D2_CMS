<script>

function ShowDetail(itemID, buttonID)
{

	//if (document.getElementById(itemID).style.fontSize == "0px")
	//if (document.getElementById(itemID).style.display == 'none')
	if ((sc1 = document.getElementById(itemID)).className)
	{
		//document.getElementById(itemID).style.fontSize="inherit";
		//document.getElementById(itemID).style.display='';
		sc1.className = '';
		document.getElementById(buttonID).innerHTML = "[-]";
	}
	else
	{
		//document.getElementById(itemID).style.display=='none';
		//document.getElementById(itemID).style.fontSize="0px";
		sc1.className = 'displayNone';
		document.getElementById(buttonID).innerHTML = "[+]";
	}
};

</script>

<table cellspacing="1" cellpadding="0" class="tbl" width="100%">
<tr>
	<th></th>
	<th>Название</th>
	<th>Цвет</th>
	<th>Корп.</th>
	<th>Уст.</th>
	<th>Кол-во</th>
	<th>Инф.</th>
</tr>
<!-- BEGIN item_list -->
<tr>
	<td>
		<a style="cursor:pointer; font-weight:bold;" onClick='ShowDetail("item_led_{item_list.ID}", "button_led_{item_list.ID}");'>
			<span id="button_led_{item_list.ID}">[+]<span>&nbsp;
		</a>
	<td style="font-weight:bold;">{item_list.NAME}</td>
	<td>{item_list.COLOR}</td>
	<td>{item_list.CASE}</td>
	<td>{item_list.MOUNTING}</td>
	<td>{item_list.QUANTITY}</td>
	<td>
		<div style="position:relative;width:56px;">
			{item_list.DESIGN}
			{item_list.PHOTO}
			{item_list.DSHEET}
		</div>
	</td>
</tr>
<tr class="displayNone" id="item_led_{item_list.ID}">
	<td colspan="12">
		<table width="80%" cellspacing="0" cellpadding="0" align="center">
			<tr><td>ID</td><td>{item_list.ID}</td></tr>
			<tr><td>Цвет</td><td>{item_list.COLOR}</td></tr>
			<tr><td>Название</td><td>{item_list.NAME}</td></tr>
			<tr><td>Производитель</td><td>{item_list.MANUFACTURER}</td></tr>
			<tr><td>U Forw Min</td><td>{item_list.UFORWMIN}</td></tr>
			<tr><td>U Forw Typ</td><td>{item_list.UFORWTYP}</td></tr>
			<tr><td>U Forw Max</td><td>{item_list.UFORWMAX}</td></tr>
			<tr><td>I forw A</td><td>{item_list.ITYP}</td></tr>
			<tr><td>I forw Imp Max A</td><td>{item_list.IIMPMAX}</td></tr>


			<tr><td>Мощность (Ватт)</td><td>{item_list.POWER}</td></tr>
			<tr><td>Длина волны</td><td>{item_list.WAWELENGTH}</td></tr>
			<tr><td>Люмен</td><td>{item_list.LUMINOUS}</td></tr>
			<tr><td>mCd</td><td>{item_list.MCD}</td></tr>
			<tr><td>Угол</td><td>{item_list.ANGLE}</td></tr>


			<tr><td>Корпус</td><td>{item_list.CASE}</td></tr>
			<tr><td>t рабочая</td><td>{item_list.TWORK}</td></tr>

			<tr><td>Тип установочного места</td><td>{item_list.MOUNTING}</td></tr>
			<!--tr><td>Дополнительная информация</td><td>{item_list.DESIGN}{item_list.PHOTO}{item_list.DSHEET}</td></tr-->
		</table>
	</td>
</tr>
<!-- END item_list -->
</table>