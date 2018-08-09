//
// показать скрыть блок
//
function ShowDiv (div_id) {
		document.getElementById(div_id).style.display="block"; 
};
function HideDiv (div_id) {
		document.getElementById(div_id).style.display="none"; 
};

function ShowHideDiv (div_id) {
	if ( document.getElementById(div_id).style.display=="none" )
	{
		document.getElementById(div_id).style.display="block"; 
	}
	else
	{
		document.getElementById(div_id).style.display="none"; 
	}
	
};

//
// загрузка контента в div
//
function show_content(page_url, div_id) {
//document.write(page_url + "==-=" + div_id);
		$.ajax({
			url: page_url,
			cache: false,
			beforeSend: function() {
					$(div_id).html('Загрузка...');
				},
				success: function(html){
					$(div_id).html(html);
				}
			});
		return false;
//*/
};

//
// сброс контента в div
//
function clear_content(div_id) {
//	alert(div_id);
	$(div_id).html('...');
};


function assembly_filter_data(page_url, div_id, form_id)
{

	var s = $(form_id).serialize();
	show_content(page_url + '?' + s, div_id);
};

function clear_field(fld, replaceval) {
	//alert("clear" + fld + replaceval);
	if (document.getElementById(fld).value == replaceval) {
		document.getElementById(fld).value = "";
	};
};
function unclear_field(fld, replaceval) {
	//alert("unclear" + fld + replaceval);
	if (document.getElementById(fld).value == "") {
		document.getElementById(fld).value = replaceval;
	};
};