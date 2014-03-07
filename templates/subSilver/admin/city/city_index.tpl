<script src="/script/jquery/js/jquery.form.js"></script>
<script>
var edit_link = 'admin';

function save_str() {
//	alert("sfdsdf");
//	$('#search_res_l').html('');
//	$('#search_res_r').html('');
	if (document.getElementById('str_type').value > 0 && document.getElementById('str_name').value != '') {
		var qString = $("#city_street_edit").formSerialize();
		show_content('/admin/city/city_edit.php?save=1&type=street&' + qString, '#edit_obj');
	}
	else {
		alert ('Не указано название или тип улицы');
	};
//	alert(qString);
};

function save_house() {
	if (document.getElementById('mng_company').value > 0 && document.getElementById('house_num').value != '') {
		var qString = $("#city_house_edit").formSerialize();
		show_content('/admin/city/city_edit.php?save=1&type=house&' + qString, '#edit_obj');
	}
	else {
		alert ('Не указан номер дома или управляющая компания');
	};
//	alert(qString);
};

function m_comp_edit() {
	if (document.getElementById('m_comp').value > 0) {
		show_content('/admin/city/city_edit.php?type=m_comp&action=edit&m_comp_id=' + document.getElementById('m_comp').value, '#edit_obj');
	}
	else {
		alert('выберите компанию')
	};
};

function save_m_comp() {
	if (document.getElementById('m_comp_name').value != '') {
		var qString = $("#form_m_comp").formSerialize();
		show_content('/admin/city/city_edit.php?save=1&type=m_comp&' + qString, '#edit_obj');
	}
	else {
		alert ('Не указано название компании');
	};
};
//

function change_district() {
	show_content('/dynamic/city/city_search.php?type=c_street&district_id=' + document.getElementById('c_district').value, '#street');
	$('#street').html('');
	$('#house').html('');
	$('#m_company').html('');
	$('#edit_obj').html('');
};
</script>

<div style="text-align:right;">
Редактирование управляющих компаний<br />
<!-- BEGIN swich_m_comp -->
<select id="m_comp" name="m_comp">
	<option value="0">Выберите компанию</option>
	<!-- BEGIN m_comp_data -->
	<option value="{swich_m_comp.m_comp_data.M_COMPANY_ID}">{swich_m_comp.m_comp_data.M_COMPANY_NAME}</option>
	<!-- END m_comp_data -->
</select> 
<!-- END swich_m_comp -->
<a onclick="m_comp_edit();" style="cursor:pointer;">Редактировать</a>; 
<a onclick="show_content('/admin/city/city_edit.php?type=m_comp&action=add', '#edit_obj');" style="cursor:pointer;">Добавить</a>
</div>

<div id="district"> 
<!-- BEGIN swich_district -->
<select id="c_district" name="c_district" onchange="change_district();">
	<option value="0">Выберите район города</option>
	<!-- BEGIN district_data -->
	<option value="{swich_district.district_data.DISTRICT_ID}">{swich_district.district_data.DISTRICT_NAME}</option>
	<!-- END district_data -->
</select>
<!-- END swich_district -->
</div>

<div id="street">
</div>

<div id="house">
</div>

<div id="m_company">
</div>

<div id="edit_obj"></div>

{TEXT}
{TEMP}