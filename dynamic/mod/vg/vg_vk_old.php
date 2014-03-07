<?php
$vkid = preg_replace('/[^0-9]+/', '', substr($_GET['vkid'],0, 20));
$lines = file('http://vk.com/id'.$vkid);

$page = implode("", $lines);

preg_match("!<title>(.*?)</title>!", $page, $header);
$name = substr($header[1], (strpos($header[1], '| ')+2));
// для выдирания ника
$fname = substr($name, 0, strpos($name, ' '));
$lname = substr($name, (strrpos($name, ' ')+1));

$online = (strpos($page, '<b class="fl_r">Онлайн</b>')) ? " (<b>Онлайн</b>)" : "";

preg_match('!'.$fname.' (.*?) '.$lname.'!', $page, $name2);
$user_nic = $name2[1];

if ($name == 'Информация') {
	echo '<small>Страница удалена, либо еще не создана.</small>';
}
elseif ($name == 'Ошибка') {
	echo '<small>Ошибка запроса данных из вконтакта</small>';
}
else {
	echo $name . $online. "<br/>\n";
	if ($user_nic) {
		echo 'Ник: ' . $user_nic. "<br/>\n";
	};
};

//echo "<pre>";
//echo htmlspecialchars($page);
//echo "</pre>";
?>