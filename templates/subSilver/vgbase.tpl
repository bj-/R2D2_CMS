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
<tr><th>���</th><th>���</th><th></th></tr>
<tr>
<td>
	<input name="id" id="id" type="text" value="{ID}" />
</td>
<td>
	<select name="ftype" id="ftype" onchange="show_diapason(document.getElementById('ftype').value)">
		<option value="id"{SEL_ID}>ID</option>
		<option value="all"{SEL_ALL}>������</option>
		<!--option value="planet"{SEL_PLANET}>�������</option-->
	</select>
</td>
<td><input name="go" onclick="search();" type="button" value="������" /></td></tr>
<tr><td colspan="3">
	<div id="coord_diapason" style="display:none;">
	<table><tr>
		<td style="text-align:right; padding-right:10px;">����������: �
		</td>
		<td>
			<input name="gstart" type="text" value="1" size="1" maxlength="2" /> :
			<input name="sstart" type="text" value="1" size="3" maxlength="5" /> :
			<input name="pstart" type="text" disabled="disabled" value="1" size="1" maxlength="2" readonly="readonly" />
		</td>
	</tr>
	<tr>
		<td style="text-align:right; padding-right:10px;">��</td>
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
������� �� ��� ������ ��� �� ��������. 
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
<h2>���������:</h2>
<div><strong>����� �� ID:</strong></div>
<div class="pleft5px"><strong>����� ������:</strong>
	<div class="pleft10px">
		<strong>���� ������:</strong>
		<div class="pleft10px">
			<strong>������:</strong><br />
			<div  class="pleft5px">
				- ID, �� ������� �� ��������<br />
				- ����� �� ID �� ��� ������<br />
				- ���� �� ������ "�������:" - ������� ��������� �������� � ������.<br />
			</div>
			<strong>������:</strong><br />
			<div  class="pleft5px">
				- ID<br />
				- ��� � ��� �� ����������<br />
				- ������� ������ ������ � ��������<br />
				- ���-�� ��������� ������ � ���������� ��<br />
				- ������� � ������� ��� ������� �����<br />
			</div>
		</div>
		<strong>���� ������ (�������):</strong>
		<div class="pleft10px">
			<strong>������:</strong><br />
			<div  class="pleft5px">
				- �������� ������� - ���������� � ������� (�������, ���������) (������ ���� ������� ����)
			</div>
			<strong>������:</strong><br />
			<div  class="pleft5px">
				- ������� � ������� �����������<br />
				- ���� ���������� ����� ��� ������ �������<br />
			</div>
		</div>
	</div>
</div>
<br />
<div><strong>����� �� ��������:</strong></div>
<div class="pleft10px">
	<strong>��������� ������:</strong>
	<div class="pleft10px">
		- ���������� ����� �������� ������� (�����) -> ����� -> �������� ������ ��������<br />
		- ��������� ������ ��� ������ -> ������ ������ 100 ������ � ������ ������� (����� ���������� �������� ������)<br />
		- ���� �� ID ������ � ����� ������� -> ������ ������ ������� ������.<br />
	</div>
	<strong>�����-����:</strong>
	<div class="pleft10px">
		- ������ �������� � ������� ���� �������� ������ �� ������� �� �������� ����������� �����. ����� ������ - ����� ����������<br />
	</div>
</div>		
</p>
<br />

<a onclick="ShowDiv('bugfix');" style="cursor:pointer;">��������</a><br />
<div id="bugfix" style="display:none;">
<small>
<p>
������: [+] - �����; [!] ����; [*] - ���������.<br />
06.04<br />
[+] ���������� �� �������� - ����, ����������� ���������� ���� (= ������������ ���������� ������ ������ ���� �� ����� �������) �� ���� ��������� ������<br />
[+] ���������� �� ������� - ���������, �������<br />
[+] ���, ���, ��������� ������� �� ���������<br />
[+] ������� �������� �� ID ������ (������� �� ����� �������, ����� ������ ���������� �� ������)<br />
[+] ��������� ���� �� ������ :)
[*] ������ ����� 11-� ����<br />
30.03<br />
[+] - �������� "�����" � ������ �� �������.<br />
[!] - ������ ������������ ������ ������������������ �����������������.<br />
27.03<br />
[+] - ����� ������� �� ��������. ���� ������ ������ �� ������ ���������� ���. ����� ���� ����������� �������.<br />
20.03<br />
[!] - ��� ������ �� ID �������������������� ������������� �� ������������ �������� �������� � ������� ������� ID � �� �������� ������ ������������ ������ ������ 2 �����<br />
12.03<br />
[+] - ����� �� ��������. ����� ������ ������ ������ 100 ������. ������������� �������� ������.<br />
[+] - ����� �� �� ID �� ���. ������<br />
10.03<br />
[ ! ] - ���������� ������� ������.<br />
09.03<br />
[+] - ������ ���������. ������ ������������������ ������������. �� ����������� �������� �� ��� :)<br />
[+] - ��������� ������� ���������� ���������� ��������������.<br />
[+] - ����������� ������ �������, ���� ��������� �������������� ������.<br />
28.02<br />
[+] - ����������� ������� � ��������� - ��� ����� ������ ������� � �����.<br />
<br />
�����������:<br />
- ������� ��������: ��������, ���������, ��<br />
- ������� ��������<br />
- ������� ID<br />
- ������ �������������� ��������<br />

<br />
����:<br />
</p>
</small>
</div>

<p align="right">{ADMIN_LINK}</p>

