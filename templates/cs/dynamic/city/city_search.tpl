<!-- BEGIN swich_district -->
<select id="c_district" name="c_district" onchange="change_district();">
	<option value="0">Выберите район города</option>
	<!-- BEGIN district_data -->
	<option value="{swich_district.district_data.DISTRICT_ID}">{swich_district.district_data.DISTRICT_NAME}</option>
	<!-- END district_data -->
</select>
<script>
function change_district() {
	show_content('/dynamic/city/city_search.php?edit='+edit_link+'&type=c_street&district_id=' + document.getElementById('c_district').value, '#street');
	$('#street').html('');
	$('#house').html('');
	$('#m_company').html('');
	$('#edit_obj').html('');
}
</script>
<!-- END swich_district -->

<!-- BEGIN swich_street -->
&nbsp;> <select id="c_street" name="c_street" onchange="change_street();">
	<option value="0">Выберите улицу</option>
	<!-- BEGIN street_data -->
	<option value="{swich_street.street_data.STREET_ID}">{swich_street.street_data.STREET_NAME} {swich_street.street_data.STREET_TYPE_SHORT}</option>
	<!-- END street_data -->
</select>
	<!-- BEGIN street_edit -->
		&nbsp; &nbsp; <a onclick="show_content('/admin/city/city_edit.php?type=street&district_id=' + document.getElementById('c_district').value, '#edit_obj');" style="cursor:pointer;">добавить улицу</a> 
		<a onclick="show_content('/admin/city/city_edit.php?type=street&?action=edit&district_id=' + document.getElementById('c_street').value, '#edit_obj');" style="cursor:pointer;">редактировать улицу</a>
	<!-- END street_edit -->
<script>
function change_street() {
	show_content('/dynamic/city/city_search.php?edit='+edit_link+'&type=c_house&str_id=' + document.getElementById('c_street').value, '#house');
	$('#house').html('');
	$('#m_company').html('');
	$('#edit_obj').html('');
}
</script>
<!-- END swich_street -->

<!-- BEGIN swich_house -->
&nbsp;> <select name="c_house" id="c_house" onchange="show_content('/dynamic/city/city_search.php?edit='+edit_link+'&type=m_company&house_id=' + document.getElementById('c_house').value, '#m_company');">
	<option value="0">Выберите дом</option>
	<!-- BEGIN house_data -->
	<option value="{swich_house.house_data.HOUSE_ID}">{swich_house.house_data.HOUSE_NUM}{swich_house.house_data.HOUSE_CORP}{swich_house.house_data.HOUSE_LETTER}</option>
	<!-- END house_data -->
</select>
	<!-- BEGIN house_edit -->
<script>
		function house_edit() {
			if (document.getElementById('c_house').value > 0) {
				show_content('/admin/city/city_edit.php?type=house&action=edit&str_id=' + document.getElementById('c_street').value +'&house_id=' + document.getElementById('c_house').value, '#edit_obj');
			}
			else {
				alert('Не выбран дом');
			};
		};
		</script>
		&nbsp; &nbsp; <a onclick="show_content('/admin/city/city_edit.php?type=house&str_id=' + document.getElementById('c_street').value, '#edit_obj');" style="cursor:pointer;">добавить дом</a>, 
		<a onclick="house_edit();" style="cursor:pointer;">редактировать дом</a>
	<!-- END house_edit -->
<!-- END swich_house -->
<!-- BEGIN swich_house_list -->
	дома:
	<!-- BEGIN house_data -->
	<a >{swich_house.house_data.HOUSE_ID}, {swich_house.house_data.HOUSE_NUM}</a>; 
	<!-- END house_data -->
<!-- END swich_house_list -->

<!-- BEGIN swich_m_company -->
<p><br />Управляющая компания: <strong>{swich_m_company.M_COMPANY_NAME}</strong><br />
Рейтинг компании по 5-ти бальной шкале: <strong>{swich_m_company.M_COMPANY_VOTE}</strong>.<br />
Всего проголосовало: <strong>{swich_m_company.M_COMPANY_VOTE_COUNT}</strong></p>

<!-- BEGIN mc_vote_form -->
<table width="100%" border="0" cellpadding="0" cellspacing="5"><tr>
<td width="30%" valign="top" nowrap="nowrap">
	Поставте свою оценку компании:
</td>
<td>
<form action="" method="get" name="mcg_vote" id="mcg_vote">
	<input id="mc_vote" name="mc_vote" type="radio" value="1" /> ужасно<br />
	<input id="mc_vote" name="mc_vote" type="radio" value="2" /> плохо<br />
	<input id="mc_vote" name="mc_vote" type="radio" value="3" /> средне<br />
	<input id="mc_vote" name="mc_vote" type="radio" value="4" /> хорошо<br />
	<input id="mc_vote" name="mc_vote" type="radio" value="5" /> отлично<br />
</form>
</td></tr>
<tr><td></td>
<td>
	<input type="button" onclick="save_vote();" value="Отправить" name="save" />
</td>
</tr></table>
</p>
<script>
function save_vote() {
	if ($("input[name='mc_vote']:checked").val()) {
		show_content('/dynamic/city/city_search.php?edit='+edit_link+'&type=m_company&house_id=' + document.getElementById('c_house').value + '&vote=' + $("input[name='mc_vote']:checked").val(), '#m_company');
	};
};
</script>
<!-- END mc_vote_form -->
<!-- END swich_m_company -->


{TEXT}
{TEMP}