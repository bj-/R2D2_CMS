

<div style="float:left; width:70%">
	������� ����: {CURRENT_BASE} [<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=prewiev', '#action')"><strong>������ � ���������� ����</strong></a>] [<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=stat_temp_db', '#action_right')"><strong>���������� ��������� ����</strong></a>]<br />

	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=truncate_temp&db=base', '#imp_action')">1. ������ ��������� �������</a><br /-->
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=truncate_temp&db=my', '#imp_action')">2. ������ ��������� ������� ����� ������</a><br />

<br />
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=p_transfer', '#imp_action')">1. ������� ������ � ������ 1:1 + ������ ����� ������ � ��������� �������</a><br />
	<br />
	2. ������: 
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=t_transfer', '#imp_action')">����������� � ���������</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=t_parse', '#imp_action')">�������</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=spy_reports', '#imp_action')">������</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php?action=report_ally_import', '#imp_action')">�������� � ��������</a>; 
	<br />
	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=transfer_my', '#imp_action')">2. ������ ����� ������ � ��������� �������</a><br /-->

	<br />
	������:<br>
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=import_names', '#imp_action')">1. ������ �������� ������, ��������, ����� �������</a><br />
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=id_history', '#imp_action')">2. ������� ID</a><br />
	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=aname_import', '#imp_action')">2. ������ �������� ��������</a><br /-->
	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=gamers_check', '#imp_action')">3. �������� ID ������� (���������������� ������������)</a><br /-->
	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=import_vkid', '#imp_action')">4. ������ ����� �������</a><br /-->
	5. �������� �������: 
	<!--a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_mark&mark=upd', '#imp_action')">1. �����������;</a> 
	2. �������������;  -->
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=new', '#imp_action')">3. �����</a>; 
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=upd', '#imp_action')">4. �����������</a>
		<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=tplanet_import&mark=del', '#imp_action')">5. ���������</a>
		<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=otaw', '#imp_action')">6. ������� (ot, aw)</a>
<br />
	<a style="cursor:pointer;" onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_action.php/admin/mod/vg/admin_dyn_action.php?action=gamers_alliances', '#imp_action')">6. ������� ��� �������� � ��������.</a><br />


	<p>{SRC_DB_STAT}</p>

	<div id="action">{FUNC_RET}</div>
	<div id="imp_action"></div>
</div>
<div style="float:left; width:30%; cursor:pointer;">
	[ <a onclick="show_content('/admin/mod/vg/dynamic/admin_dyn_src_db_list.php?id=', '#src_db_list')">����� ���</a> ]
	<div id="src_db_list"></div>
	<div id="action_right"></div>
</div>
<div style="clear:left;"></div>
