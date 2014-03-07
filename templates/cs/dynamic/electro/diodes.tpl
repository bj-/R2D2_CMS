Диоды
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
	<th>Тип</th>
	<th>Кол-во</th>
	<th>Произв.</th>
	<th>Материал</th>
	<th>U Rev Max</th>
	<th>I forw Max A</th>
	<th>Корп.</th>
	<th>t раб.</th>
	<th>Уст.</th>
	<th>Инф.</th>
</tr>
<!-- BEGIN item_list -->
<tr>
	<td>
		<a style="cursor:pointer; font-weight:bold;" onClick='ShowDetail("item_diod_{item_list.ID}", "button_diod_{item_list.ID}");'>
			<span id="button_diod_{item_list.ID}">[+]<span>&nbsp;
		</a>
	<td style="font-weight:bold;">{item_list.NAME}</td>
	<td>{item_list.TYPE}</td>
	<td>{item_list.QUANTITY}</td>
	<td>{item_list.MANUFACTURER}</td>
	<td>{item_list.MATERIAL}</td>
	<td>{item_list.UREVMAX}</td>
	<td>{item_list.IFORWMAXA}</td>
	<td>{item_list.CASE}</td>
	<td>{item_list.TWORK}</td>
	<td>{item_list.MOUNTING}</td>
	<td>
		<div style="position:relative;width:56px;">
			{item_list.DESIGN}
			{item_list.PHOTO}
			{item_list.DSHEET}
		</div>
	</td>
</tr>
<tr class="displayNone" id="item_diod_{item_list.ID}">
	<td colspan="12">
		<table width="80%" cellspacing="0" cellpadding="0" align="center">
			<tr><td>ID</td><td>{item_list.ID}</td></tr>
			<tr><td>Тип</td><td>{item_list.TYPE}</td>
			<tr><td>Название</td><td>{item_list.NAME}</td>
			<tr><td>Производитель</td><td>{item_list.MANUFACTURER}</td>
			<tr><td>Материал</td><td>{item_list.MATERIAL}</td>
			<tr><td>U Forw</td><td>{item_list.UFORW}</td>
			<tr><td>U Rev Max</td><td>{item_list.UREVMAX}</td>
			<tr><td>U Rev Imp Max</td><td>{item_list.UREVIMPMAX}</td>
			<tr><td>I forw Max A</td><td>{item_list.IFORWMAXA}</td>
			<tr><td>I forw Imp Max A</td><td>{item_list.IFORWIMPMAXA}</td>
			<tr><td>I Rev Max A</td><td>{item_list.IREVMAXA}</td>
			<tr><td>Корпус</td><td>{item_list.CASE}</td>
			<tr><td>t рабочая</td><td>{item_list.TWORK}</td>
			<tr><td>Тип установочного места</td><td>{item_list.MOUNTING}</td>
			<!--tr><td>Дополнительная информация</td><td>{item_list.DESIGN}{item_list.PHOTO}{item_list.DSHEET}</td></tr-->
		</table>
	</td>
</tr>
<!-- END item_list -->
</table>