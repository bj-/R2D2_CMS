//
// �������� ������ ����
//
function ShowDiv (div_id) {
		document.getElementById(div_id).style.display="block"; 
};
function HideDiv (div_id) {
		document.getElementById(div_id).style.display="none"; 
};

//
// �������� �������� � div
//
function show_content(page_url, div_id) {
//alert(page_url + "===" + div_id);
		$.ajax({
			url: page_url,
			cache: false,
			beforeSend: function() {
					$(div_id).html('��������...');
				},
				success: function(html){
					$(div_id).html(html);
				}
			});
		return false;
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