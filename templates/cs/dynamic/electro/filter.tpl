<!-- {SAVED} -->

<script type="text/javascript">// <![CDATA[

//show_content('/dynamic/electro/led.php', '#leds');
//show_content('/dynamic/electro/filter.php?type=led', '#leds_filter');

</script>


<!-- BEGIN filter_led -->
<p>����������</p>
<form id="filter_led">
<table cellspacing="1" cellpadding="0" class="tbl" width="100%">
<tr>
	<th>��������</th>
	<th>����</th>
	<th>����.</th>
	<th>���.</th>
</tr>
<tr>
	<td><input id="filt_led_name" name="name" type="text" value="��������..." onclick="clear_field('filt_led_name', '��������...')" onblur="unclear_field('filt_led_name', '��������...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#leds', '#filter_led');" /></td>


	<td><input id="filt_led_color" name="color" type="text" value="����..." onclick="clear_field('filt_led_color', '����...')" onblur="unclear_field('filt_led_color', '����...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#leds', '#filter_led');" /></td>
	<td><input id="filt_led_case" name="case" type="text" value="������..." onclick="clear_field('filt_led_case', '������...')" onblur="unclear_field('filt_led_case', '������...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#leds', '#filter_led');" /></td>
	<td><input id="filt_led_mount" name="mount" type="text" value="������..." onclick="clear_field('filt_led_mount', '������...')" onblur="unclear_field('filt_led_mount', '������...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#leds', '#filter_led');" /></td>
</tr>
</table>
</form>

<!-- END filter_led -->


<!-- BEGIN filter_resistor -->
<p>���������</p>
<form id="filter_resistor">
<table cellspacing="1" cellpadding="0" class="tbl" width="100%">
<tr>
	<th>�������</th>
	<th>����.</th>
	<th>����.</th>
	<th>���.</th>
</tr>
<tr>
	<td><input id="filt_ressitor_value" name="value" type="text" value="��..." onclick="clear_field('filt_ressitor_value', '��...')" onblur="unclear_field('filt_ressitor_value', '��...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#resistor', '#filter_resistor');" /></td>


	<td><input id="filt_ressitor_power" name="color" type="text" value="����..." onclick="clear_field('filt_ressitor_power', '����...')" onblur="unclear_field('filt_ressitor_power', '����...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#resistor', '#filter_resistor');" /></td>
	<td><input id="filt_ressitor_case" name="case" type="text" value="������..." onclick="clear_field('filt_ressitor_case', '������...')" onblur="unclear_field('filt_ressitor_case', '������...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#resistor', '#filter_resistor');" /></td>
	<td><input id="filt_ressitor_mount" name="mount" type="text" value="������..." onclick="clear_field('filt_ressitor_mount', '������...')" onblur="unclear_field('filt_ressitor_mount', '������...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#resistor', '#filter_resistor');" /></td>
</tr>
</table>
</form>

<!-- END filter_resistor -->