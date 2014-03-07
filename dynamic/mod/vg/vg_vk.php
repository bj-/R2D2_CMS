<?php
$vkid = preg_replace('/[^0-9]+/', '', substr($_GET['vkid'],0, 20));
$lines = @file('http://vk.com/id'.$vkid);

$page = @implode("", $lines);

preg_match("!<title>(.*?)</title>!", $page, $header);
//print_r($header);
//$name = substr($header[1], (strpos($header[1], '| ')+2));
$name = $header[1];
// для выдирания ника
$fname = substr($name, 0, strpos($name, ' '));
$lname = substr($name, (strrpos($name, ' ')+1));

$online = (strpos($page, '<b class="fl_r">Онлайн</b>')) ? " (<b>Онлайн</b>)" : "";


//$page = substr($page, strpos($page, '</title>'));
//$page = substr($page, strpos($page, $lname));

//preg_match('!Сергей(.*?)!', $page, $name2);

//preg_match('!'.$fname.' (.*?) '.$lname.'!', $page, $name2);
//$page = $name2[1];

//print_r($name2);
preg_match_all('!'.$fname.'(.*?)'.$lname.'!', $page, $namex);
$user_nic = trim($namex[1][2]);


//preg_match('!'.$fname.' (.*?) '.$lname.'!', $page, $name3);
//$user_nic = $name3[1];
//echo $user_nic;

//echo "&lt". "&gt;";
$str_src = array ("<", ">");
$str_repl = array("&lt", "&gt;");
if ($name == 'Информация') {
	echo '<small>Страница удалена, либо еще не создана.</small>';
}
elseif ($name == 'Ошибка') {
	echo '<small>Ошибка запроса данных из вконтакта</small>';
}
else {
	echo htmlspecialchars($name) . $online. "<br/>\n";
	if ($user_nic and strlen($user_nic)<100) {
//		echo strlen($user_nic);
		echo 'Ник: ' . str_replace($str_src, $str_repl, substr($user_nic, 0, 80)). "<br/>\n";
	};
};

echo "<pre>";
//echo htmlspecialchars($name2[1]);
//print_r($namex); 
//echo htmlspecialchars($page);
echo "</pre>";
?>