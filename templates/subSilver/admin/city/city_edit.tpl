<!-- BEGIN swich_street -->
<p>Редактирование/добавление улицы</p>

<form action="" method="get" name="city_street_edit" id="city_street_edit">
Район: 
<select name="district_s">
	<option value="0">Выберете район</option>
	<!-- BEGIN district_data -->
	<option value="{swich_street.district_data.DISTRICT_ID}"{swich_street.district_data.DISTRICT_SEL}>{swich_street.district_data.DISTRICT_NAME}</option>
	<!-- END district_data -->
</select>

Название: <input id="str_name" name="str_name" type="text" value="{swich_street.STR_NAME}">
			<input id="str_name_id" name="str_name_id" type="hidden" value="{swich_street.STR_NAME_ID}">
тип: 
<select name="str_type" id="str_type">
	<option value="0">Выберете тип</option>
	<!-- BEGIN str_type -->
	<option value="{swich_street.str_type.STR_TYPE_ID}">{swich_street.str_type.STR_TYPE_NAME_FULL}</option>
	<!-- END str_type -->
</select>

<input type="button" onclick="save_str();" value="Сохранить" name="save" />
</form>
<!-- END swich_street -->

<!-- BEGIN swich_street_ok -->
<script>
show_content('/dynamic/city/city_search.php?type=c_street&district_id={swich_street_ok.DISTRICT_ID}', '#street')
//$('#search_res_r').html('');
</script>
<!-- END swich_street_ok -->

<!-- BEGIN swich_house -->
<p>Добавление/редактирование дома</p>
{swich_house.STR_NAME} {swich_house.STR_TYPE_NAME_SHORT}, {swich_house.DISTRICT_NAME} р-н.<br />
<form action="" method="get" name="city_house_edit" id="city_house_edit">
<input type="hidden" name="house_id" value="{HOUSE_ID}" />
<input type="hidden" name="action" value="{ACTION}" />
<input type="hidden" name="str_id" value="{swich_house.STR_ID}" />
Номер дома: <input name="house_num" type="text" id="house_num" value="{HOUSE_NUM}" size="5"> 
Корпус: <input name="house_corp" id="house_corp" value="{HOUSE_CORP}" size="5">
Литера: <input name="house_letter" id="house_letter" value="{HOUSE_LETTER}" size="5">

<select name="mng_company" id="mng_company">
	<option value="0">Выберете компанию</option>
	<!-- BEGIN m_company -->
	<option value="{swich_house.m_company.M_COMPANY_ID}"{swich_house.m_company.M_COMPANY_SEL}>{swich_house.m_company.M_COMPANY_NAME}</option>
	<!-- END m_company -->
</select>

<input type="button" onclick="save_house();" value="Сохранить" name="save">
</form>
<!-- END swich_house -->

<!-- BEGIN swich_house_ok -->
<script>
show_content('/dynamic/city/city_search.php?type=c_house&str_id={swich_house_ok.STR_ID}', '#house');
$('#m_company').html('');
$('#edit_obj').html('');
</script>
</script>
<!-- END swich_house_ok -->

<!-- BEGIN swich_m_comp -->
<p><br />Редактирование/добавление управляющей компании</p>
<form action="" method="get" name="form_m_comp" id="form_m_comp">
<input type="hidden" name="action" id="action" value="{ACTION}">
<input type="hidden" name="m_comp_id" id="m_comp_id" value="{swich_m_comp.M_COMPANY_ID}">
<input name="m_comp_name" type="text" id="m_comp_name" value="{swich_m_comp.M_COMPANY_NAME}" size="60">
<input type="button" onclick="save_m_comp();" value="Сохранить" name="save">
</form>
<!-- END swich_m_comp -->
