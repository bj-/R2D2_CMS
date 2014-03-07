<?php
$img = substr($_GET["img"],0,255);
$img = str_replace("//", "/", $img);
//	echo '<img src="'.$img.'" alt="" width="100" height="100" border="0" />';

if ($img) {
	echo '<img src="'.$img.'" alt="" border="0" />
	<input type="hidden" name="img_url" value="'.$img.'">';
};
?>