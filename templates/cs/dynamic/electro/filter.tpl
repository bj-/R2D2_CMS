<!-- {SAVED} -->

<script type="text/javascript">// <![CDATA[

//show_content('/dynamic/electro/led.php', '#leds');
//show_content('/dynamic/electro/filter.php?type=led', '#leds_filter');

</script>


<!-- BEGIN filter_led -->
<p>Светодиоды</p>
<form id="filter_led">
<table cellspacing="1" cellpadding="0" class="tbl" width="100%">
<tr>
	<th>Название</th>
	<th>Цвет</th>
	<th>Корп.</th>
	<th>Уст.</th>
</tr>
<tr>
	<td><input id="filt_led_name" name="name" type="text" value="Название..." onclick="clear_field('filt_led_name', 'Название...')" onblur="unclear_field('filt_led_name', 'Название...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#leds', '#filter_led');" /></td>


	<td><input id="filt_led_color" name="color" type="text" value="Цвет..." onclick="clear_field('filt_led_color', 'Цвет...')" onblur="unclear_field('filt_led_color', 'Цвет...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#leds', '#filter_led');" /></td>
	<td><input id="filt_led_case" name="case" type="text" value="Корпус..." onclick="clear_field('filt_led_case', 'Корпус...')" onblur="unclear_field('filt_led_case', 'Корпус...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#leds', '#filter_led');" /></td>
	<td><input id="filt_led_mount" name="mount" type="text" value="Монтаж..." onclick="clear_field('filt_led_mount', 'Монтаж...')" onblur="unclear_field('filt_led_mount', 'Монтаж...')" onchange="assembly_filter_data('/dynamic/electro/led.php', '#leds', '#filter_led');" /></td>
</tr>
</table>
</form>

<!-- END filter_led -->