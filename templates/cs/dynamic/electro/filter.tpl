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