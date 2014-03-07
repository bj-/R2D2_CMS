<?php
$vkid = preg_replace('/[^0-9]+/', '', substr($_GET['vkid'],0, 20));
$lines = @file('http://msk2011.ru/test/vg_vk.php?vkid='.$vkid);
//$lines = @file('http://milonov.ru/temp/vg_vk.php?vkid='.$vkid);

$page = @implode("", $lines);

echo $page;
?>