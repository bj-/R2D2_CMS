<?php

//$debug_mode = TRUE;
$debug_mode = FALSE;

$SiteName = "taz.ru";


if ( $debug_mode )
{
	// показывать все ошибки в дебаге
	error_reporting( E_ALL );
}

function writeSendMailLog($text)
{
	global $debug_mode; 

	// Write log
	// Дата / время / страница / IP откуда вызывали скрипт / текст
	// TODO -- сделай сам
	if ( $debug_mode )
	{
		echo $text;
	}
}

function sendmail($to, $subject, $message)
{

	global $debug_mode, $SiteName;
	
	$MessageSent = FALSE;
	$correctData = TRUE;
	$msend_result = "";

	// Проверка входящий данных ОБЯЗАТЕЛЬНО
	// убрать вражеские символы из переменных
	// TODO
//	echo $to;
 	$enemy_To = array("'", '"');
	$to = str_replace($enemy_To, "", $to);
//	var_dump( $enemy_To );
//	echo $to;
//	exit;
//	$to 
//	$subject
//	$message

//	$correctData = FALSE; // если проверка не пройдена - запрет на отправку.
//	$msend_result .= ($debug_mode) ? "<pre>\n" . $to . "\n" . $subject . "\n" . $message . "</pre>" : "";

	if ( $to == "" or $subject == "" or $message == "")
	{
		$msend_result = "<p><div align='center'><strong>Ошибка отправки сообщения</strong> (Не указано Тема, Получатель или Сообщение)</p></div>";
		$diagdata = "<pre>\n" . $to . "\n" . $subject . "\n" . $message . "</pre>";
		$msend_result .= ($debug_mode) ? $diagdata : "";
		writeSendMailLog ( "$msend_result . $diagdata", FALSE ); // Записать в лог ошибку

		$correctData = FALSE;
	}

	if ( $correctData ) 
	{
		$msend_message = nl2br($message);
	
		$msend_to      = $to;
		$msend_subject = '=?windows-1251?B?' . base64_encode($subject) . '?=';
	
		$msend_headers = 
	//							"From: mailform@" . $SiteName . "\n" .
								"From: Отправляшка <mailform@" . $SiteName . ">\n" . // адрес отправителя
								"Content-type: text/html; charset=windows-1251\n".
								'Reply-To: mailform@'. $SiteName . "\n" . // этот адрес подставится если получатель решит ответить
								'X-Mailer: PHP/' . phpversion();
	
		if (mail($msend_to, $msend_subject, $msend_message, $msend_headers)) {
			$msend_result = "<p><div align='center'><strong>Сообщение отправлено</strong></p></div>";
			$MessageSent = TRUE;

			$diagdata = "<pre>\n" . $msend_to . "\n" . $msend_subject . "\n" . $msend_message . "\n" . $msend_headers . "</pre>";
			// Записать в лог что было отправлено и дату/время
			writeSendMailLog ( "$msend_result . $diagdata", TRUE ); // Записать в лог ошибку
		}
		else {
			$msend_result = "<p><div align='center'><strong>Ошибка отправки сообщения</strong></p></div>";

			// Записать в лог что ошибку и дату/время
			$diagdata = "<pre>\n" . $msend_to . "\n" . $msend_subject . "\n" . $msend_message . "\n" . $msend_headers . "</pre>";
			$msend_result .= ($debug_mode) ? $diagdata : "";
			writeSendMailLog ( "$msend_result . $diagdata", FALSE ); // Записать в лог ошибку
		};
	}

	echo  $msend_result;
	return 	$MessageSent;
}

// вызов (откуда угодно подключив этот файл)

$mailto = "Мой милый Друх <bmw518@bk.ru>";
$subject = "Тема сообщения (Kiss my shiny ass!)";
$mailmessage = "
<h1>Заголовок</h1>
<ul>
	<li>пункт1</li>
	<li>пункт2</li>
	<li>пункт3</li>
</ul>
";

// вызов и получение результата работы функции TRUE/FALSE для дальнейшего использования
$sent_result = sendmail($mailto, $subject, $mailmessage)

//echo $sent_result;

?>

 