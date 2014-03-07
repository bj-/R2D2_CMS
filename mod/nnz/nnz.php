<?php
$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));

define('IN_R2D2', true);
include("includes/config.php");
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);

set_time_limit(1000);

//phpinfo();
/*
$url = "http://www.example.com/protected/"; 
$ch = curl_init();     
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($ch, CURLOPT_URL, $url);  
curl_setopt($ch, CURLOPT_USERPWD, "myusername:mypassword");  
$result = curl_exec($ch);  
curl_close($ch);  
echo $result; 
*/


$monname = array ("Неизвестно", "января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "Сентября", "октября", "ноября", "декабря");



function parse_nnz_people($url, $id) {
	global $monname, $db;
	//echo login("http://local/SitePages/view.aspx?t_number=2705","a.smirnov","123456");

	//preg_match("!Резюме сотрудника(.*)Это не ваша страница!", $page, $result);

	preg_match("!<TABLE style=\"WIDTH: 710px; BORDER-COLLAPSE: collapse; HEIGHT: 50px; COLOR: #333333\" id=(.*?)TABLE>!", $url, $result);

	$l_id = "???";
	$nnz_id = '"'. $id . '", ';
	preg_match("!ФИО(.*?)<TD>(.*?)</TD>!", $result[0], $FIO_r);
	$FIO 			= chop($FIO_r[2]);
	
	$Lastname	= substr($FIO, 0, strpos($FIO, " "));
	$Lastname	= (chop($Lastname)) ? '"' . mysql_real_escape_string(chop($Lastname)). '", ' : "null, ";
	
						$tmp = substr($FIO, strpos($FIO, " ")+1);
	$Firstname	= substr($tmp, 0, strpos($tmp, " "));
	$Firstname	= (chop($Firstname)) ? '"' . mysql_real_escape_string(chop($Firstname)). '", ' : "null, ";
						$tmp = substr($tmp, strpos($tmp, " ")+1);
						
	$Middlename	= (chop($tmp)) ? '"' . mysql_real_escape_string(chop($tmp)). '", ' : "null, ";;

	$FIO 			= ($FIO_r[2]) ? '"' . mysql_real_escape_string(chop($FIO_r[2])). '", ' : "null, ";;
	
	preg_match("!IMG border=0 src=\"(.*?)\"!", $result[0], $img_url_r);
	$img_url = ($img_url_r[1]) ? '"' . mysql_real_escape_string(chop($img_url_r[1])) .'", ' : "null, ";
// ) ? '"' . mysql_real_escape_string(chop($xxx)). '", ' : "null, ";

	preg_match("!http://lps.nnz.ru/getphoto[?]id[=](.*?)\"!", $result[0], $img_nnz_id_r);
	$img_nnz_id = ($img_nnz_id_r[1] ) ? '"' . mysql_real_escape_string(chop($img_nnz_id_r[1])). '", ' : "null, ";

	preg_match("!№ Отдела</TD(.*?)<TD>(.*?)</TD>!", $result[0], $dep_id_r);
	$dep_id = ($dep_id_r[2] ) ? '"' . mysql_real_escape_string(chop($dep_id_r[2])). '", ' : "null, ";
	$fireddate = ($dep_id == '202') ? '"'. time() . '", '  : "null, ";
	
	preg_match("!Отдел</TD(.*?)<TD>(.*?)</TD>!", $result[0], $dep_name_r);
	$dep_name = ($dep_name_r[2] ) ? '"' . mysql_real_escape_string(chop($dep_name_r[2])). '", ' : "null, ";
	
	preg_match("!Должность(.*?)<TD>(.*?)</TD>!", $result[0], $position_r);
	$position = ($position_r[2] ) ? '"' . mysql_real_escape_string(chop($position_r[2])). '", ' : "null, ";
	
	preg_match("!Сфера ответственности(.*?)<TD>(.*?)</TD>!", $result[0], $sfera_otv_r);
	$sfera_otv = ($sfera_otv_r[2] ) ? '"' . mysql_real_escape_string(chop($sfera_otv_r[2])). '", ' : "null, ";
	
	preg_match("!Юр. Лицо(.*?)<TD>(.*?)</TD>!", $result[0], $company_r);
	$company = ($company_r[2] ) ? '"' . mysql_real_escape_string(chop($company_r[2])). '", ' : "null, ";
	
	preg_match("!Телефон(.*?)<TD>(.*?)</TD>!", $result[0], $phone_r);
	$phone = ($phone_r[2] ) ? '"' . mysql_real_escape_string(chop($phone_r[2])). '", ' : "null, ";
	
	preg_match("!Электронный адрес(.*?)<TD>(.*?)</TD>!", $result[0], $email_r);
	$email = ($email_r[2] ) ? '"' .mysql_real_escape_string(strip_tags(chop($email_r[2]))). '", ' : "null, ";
	
	preg_match("!Дата рождения(.*?)<TD>(.*?)</TD>!", $result[0], $birth_date_r);
	$birth_date = strip_tags(chop($birth_date_r[2]));
	$birth_date_a = explode(" ", $birth_date);
	$birth_date_a["mon"] = array_search($birth_date_a[1], $monname);
	if ($birth_date_a["mon"]) {
		$birth_date = '"'. mktime(12,0,0, $birth_date_a["mon"],  $birth_date_a[0], 2000) .'", ';
	}
	else {
		$birth_date = '"0", ';
	}


//echo date("d m Y", mktime(12,0,0, $birth_date_a["mon"],  $birth_date_a[0], 2000)) . " - " . $birth_date_r[2];

//	$birth_date = ($birth_date_r[2] ) ? '"' . mysql_real_escape_string(chop($birth_date_r[2])). '", ' : "null, ";
	
	
	preg_match("!Дата прихода в компанию(.*?)<TD>(.*?)</TD>!", $result[0], $inc_date_r);
	$inc_date = strip_tags(chop($inc_date_r[2]));
	$inc_date_a = explode(" ", $inc_date);
	$inc_date_a["mon"] = array_search($inc_date_a[1], $monname);
	if ($inc_date_a["mon"]) {
		$inc_date = '"'. mktime(12,0,0, $inc_date_a["mon"],  $inc_date_a[0], $inc_date_a[2]) .'", ';
		$noinc_date = 'null, ';
	}
	else {
		$inc_date = '"0", ';
		$noinc_date = '"'. time() . '", ';
	}
	
//echo  $inc_date . " - " . $inc_date_a["mon"] ."/".  $inc_date_a[0]."/". $inc_date_a[2] . "<br>\n";
//echo date("d m Y", 816598800) . " - " . $inc_date_r[2];
//var_dump($inc_date_a);

	
	
//	$inc_date = ($inc_date_r[2] ) ? '"' . mysql_real_escape_string(chop($inc_date_r[2])). '", ' : "null, ";
	
	preg_match("!Образование(.*?)<TD>(.*?)</TD>!", $result[0], $edu_r);
	$edu = ($edu_r[2] ) ? '"' . mysql_real_escape_string(chop($edu_r[2])). '", ' : "null, ";
	
	preg_match("!Опыт предыдущей работы(.*?)<TD>(.*?)</TD>!", $result[0], $lastwork_r);
	$lastwork = ($lastwork_r[2] ) ? '"' . mysql_real_escape_string(chop($lastwork_r[2])). '", ' : "null, ";
	
	preg_match("!Интересные факты из жизни(.*?)<TD>(.*?)</TD>!", $result[0], $livefacts_r);
	$livefacts = ($livefacts_r[2] ) ? '"' . mysql_real_escape_string(chop($livefacts_r[2])). '", ' : "null, ";
	
	preg_match("!За что отвечает в компании(.*?)<TD>(.*?)</TD>!", $result[0], $zachtootv_r);
	$zachtootv = ($zachtootv_r[2] ) ? '"' . mysql_real_escape_string(chop($zachtootv_r[2])). '", ' : "null, ";
	
	preg_match("!О себе(.*?)<TD>(.*?)</TD>!", $result[0], $about_p_r);
	$about_p = ($about_p_r[2] ) ? '"' . mysql_real_escape_string(chop($about_p_r[2])). '" ' : "null ";
	
	preg_match("!Мобильный телефон(.*?)<TD>(.*?)</TD>!", $result[0], $mphone_r);
	$mphone = ($mphone_r[2] ) ? '"' . mysql_real_escape_string(chop($mphone_r[2])). '", ' : "null, ";
	
	preg_match("!Icq(.*?)<TD>(.*?)</TD>!", $result[0], $icq_r);
	$icq = ($icq_r[2] ) ? '"' . mysql_real_escape_string(chop($icq_r[2])). '", ' : "null, ";
	

	$upd_date = '"'. time() .'", ';
/*



inc_date
noinc_date

fireddate
upd_date

*/
//	var_dump($img_url_r);
	
/*
	echo "<table>".
"<tr><td>id: </td><td>". $l_id. "</td></tr>".
"<tr><td>nnz_id: </td><td>". $nnz_id. "</td></tr>".
"<tr><td>Lastname: </td><td>". $Lastname. "</td></tr>".
"<tr><td>Firstname: </td><td>". $Firstname. "</td></tr>".
"<tr><td>Middlename: </td><td>". $Middlename. "</td></tr>".
"<tr><td>FIO: </td><td>". $FIO . "</td></tr>".
"<tr><td>img_url: </td><td>". $img_url. "</td></tr>".
"<tr><td>img_nnz_id: </td><td>". $img_nnz_id. "</td></tr>".
"<tr><td>position: </td><td>". $position. "</td></tr>".
"<tr><td>dep_id: </td><td>". $dep_id. "</td></tr>".
"<tr><td>dep_name: </td><td>". $dep_name. "</td></tr>".
"<tr><td>sfera_otv: </td><td>". $sfera_otv. "</td></tr>".
"<tr><td>company: </td><td>". $company. "</td></tr>".
"<tr><td>phone: </td><td>". $phone. "</td></tr>".
"<tr><td>email: </td><td>". $email. "</td></tr>".
"<tr><td>birth_date: </td><td>". $birth_date. "</td></tr>".
"<tr><td>inc_date: </td><td>". $inc_date. "</td></tr>".
"<tr><td>noinc_date: </td><td>". $noinc_date. "</td></tr>".
"<tr><td>edu: </td><td>". $edu. "</td></tr>".
"<tr><td>lastwork: </td><td>". $lastwork. "</td></tr>".
"<tr><td>livefacts: </td><td>". $livefacts. "</td></tr>".
"<tr><td>zachtootv: </td><td>". $zachtootv. "</td></tr>".
"<tr><td>about_p: </td><td>". $about_p. "</td></tr>".
"<tr><td>mphone: </td><td>". $mphone. "</td></tr>".
"<tr><td>icq:</td><td> ". $icq. "</td></tr>".
"<tr><td>fireddate: </td><td>". $fireddate. "</td></tr>".
"<tr><td>upd_date:</td><td> ". $upd_date. "</td></tr>".
"</table>";
	*/

/*

id
nnz_id
Lastname
Firstname
Middlename
img_url
img_nnz_id
dep_id
dep_name
sfera_otv
company
phone
email
birth_date
inc_date
noinc_date
edu
lastwork
livefacts
zachtootv
about_p
mphone
fireddate
upd_date
*/
//echo "<pre>"; 
	$sql = 'INSERT INTO '.
				'`etalon_nnz_people` '.
				'('.
				'`nnz_id`, `Lastname`, `Firstname`, `Middlename`, `FIO`, `img_url`, `img_nnz_id`, '.
				'`dep_id`, `dep_name`, `position`, `company`, '.
				'`phone`, `email`, `icq`, `mphone`, '.
				'`birth_date`, `inc_date`, `noinc_date`, `fireddate`, `upd_date`, '.
				'`sfera_otv`, `edu`, `lastwork`, `livefacts`, `zachtootv`, `about_p` '.
				') '.
				'VALUES ('.
					$nnz_id. $Lastname. $Firstname. $Middlename. $FIO . $img_url. $img_nnz_id.
					$dep_id. $dep_name. $position. $company.
					$phone. $email. $icq. $mphone.
					$birth_date. $inc_date. $noinc_date. $fireddate. $upd_date. 
					$sfera_otv. $edu. $lastwork. $livefacts. $zachtootv. $about_p.
				');';

//echo "<br /> \n<br /> \n";
/*
	$sql = 'INSERT INTO '.
				'`etalon_nnz_people` '.
				'(`id`, `nnz_id`, `Lastname`, `Firstname`, `Middlename`, `FIO`, `img_url`, `img_nnz_id`, `dep_id`, `dep_name`, `sfera_otv`, `company`, `phone`, `email`, `birth_date`, `inc_date`, `noinc_date`, `edu`, `lastwork`, `livefacts`, `zachtootv`, `about_p`, `mphone`, `fireddate`, `upd_date`) '.
				'VALUES (' .
				'NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL' .
				');';
*/

//	echo $result[0];

if ( !($result = $db->sql_query($sql)) ) {
	message_die(GENERAL_ERROR, 'Ошибка добавление сотрудника в базу', '', __LINE__, __FILE__, $sql);
};


};

function Prerare_nnz_data($url) {
	$lines = file($url);

	$page = implode("", $lines);
	$page = str_replace("\n", "", $page); 
	return $page;
};


if ($_GET["go"]) {
	// Prepare data
	$i = 1;
	while ($i < 4000) {
	//	$url = $_SERVER["DOCUMENT_ROOT"] . "/nnz/_10a.log";
		$url = $_SERVER["DOCUMENT_ROOT"] . "/nnz/".$i.".log";
		$page = Prerare_nnz_data($url);
		parse_nnz_people($page, $i);
		$i++;
	};
}
else {
echo 'Скажи <a href="?go=1">Го</a> и проходи.';

};

//echo $page;

	//var_dump($result);

?>