<?php
/***************************************************************************
 *                                msend.php
 *								  Mail send
 *                            -------------------
 *   begin                : Saturday, Jul 13, 2010
 *   copyright            : (C) 2001 The R2D2 Group
 *
 *   $Id: msend.php,v 1.99.2.1 2002/12/19 17:17:40 psotfx Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 * 
 *
 ***************************************************************************/

 
//
//отсылка почты из формы
//
if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
};

//	$form_article_id = ($article_data[0]["article_id"]) ? $article_data[0]["article_id"] : intval(substr($_POST['article_id'],0,11));
	
/*	$sql = "SELECT * FROM `" . 
	TABLE_ARTICLE . '` WHERE `article_id` = "'.$article_data[0]["article_id"].'" and `article_lang` = "'.$user_lang.'"';

	if ( !($result = $db->sql_query($sql)) ) {
		message_die(GENERAL_ERROR, 'База статей отсутствует', '', __LINE__, __FILE__, $sql);
		};


	$article_data = array();
	while( $row = $db->sql_fetchrow($result) ) {
		$article_data[] = $row;
		};
		
	if (!$article_data[0]["article_id"]) {
		message_die(GENERAL_ERROR, 'Запрошенная статья не найдена ' . $add, '', __LINE__, __FILE__, $sql);
		};
*/

	$s_val = array("'", '"', '\\');
	$t_val = array("", "", "");

	if ($article_data[0]["form_id"]) {
		// если включена форма из билдера
		// выбираем поля этой формы.
		$sql = 'SELECT * FROM `' . TABLE_FORMS_FIELDS . '` WHERE `form_id` = "'.$article_data[0]["form_id"].'";';

		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'Таблица полей форм отсутствует', '', __LINE__, __FILE__, $sql);
		};

		$form_field_data = array();
		while( $row = $db->sql_fetchrow($result) ) {
			$form_field_data[] = $row;
		};

		if (!$form_field_data[0]["field_id"]) {
			$add = " form_id=".$article_data[0]["form_id"].";article_id=".$article_data[0]["article_id"];
			message_die(GENERAL_ERROR, 'У формы нет полей' . $add, '', __LINE__, __FILE__, $sql);
		};
		
		// собираем тело письма.
		$msend_message = "";
		$i = 0;
		while ($form_field_data[$i]["field_id"]) {
			$msend_message .= "<tr><td>". $form_field_data[$i]["field_name"] .": </td><td>" . $_POST["field_".$form_field_data[$i]["field_id"]] . "</td></tr>";
			
			$i++;
		};
		$msend_message = '<table border="0" cellpadding="0" cellspacing="3">'.$msend_message.'</table>';
	}
	else {
		// кастомные формы

		// проверяем и обрезаем введенные значения
		$msend_emailform_company		 = htmlspecialchars(str_replace($s_val, $t_val, 				substr($_POST['emailform_company'], 	0, 255)));
		$msend_emailform_name			 = htmlspecialchars(str_replace($s_val, $t_val, 				substr($_POST['emailform_name'], 		0, 255)));
		$emailform_address				 = htmlspecialchars(str_replace($s_val, $t_val, strip_tags(	substr($_POST['emailform_address'], 	0, 255))));
		$emailform_city_district		 = htmlspecialchars(str_replace($s_val, $t_val, strip_tags(	substr($_POST['emailform_city_district'], 0, 255))));
		$emailform_house_manage_company = htmlspecialchars(str_replace($s_val, $t_val, strip_tags(substr($_POST['emailform_house_manage_company'], 0, 255))));
		$msend_emailform_phone			 = htmlspecialchars(str_replace($s_val, $t_val, 				substr($_POST['emailform_phone'], 		0, 30)));
		$msend_emailform_email			 =	htmlspecialchars(str_replace($s_val, $t_val, 				substr($_POST['emailform_email'], 		0, 128)));
		$msend_emailform_text			 = htmlspecialchars(str_replace($s_val, $t_val, strip_tags(	substr($_POST['emailform_text'], 		0, 5000))));
		$msend_emailform_concurse		 = htmlspecialchars(str_replace($s_val, $t_val, strip_tags(	substr($_POST['emailform_cuncurse'], 	0, 255))));
	
		// Формируем кусочки тела письма:
		$msend_emailform_concurse = ($msend_emailform_concurse) ? "<tr><td>Конкурс: </td><td>" . $msend_emailform_concurse . "</td></tr>" : "";
		$msend_emailform_company = ($msend_emailform_company) ? "<tr><td>Компания: </td><td>" . $msend_emailform_company . "</td></tr>" : "" ;
		$msend_emailform_name = ($msend_emailform_name) ? "<tr><td>ФИО: </td><td>" . $msend_emailform_name . "\n" : "<tr><td>ФИО: </td><td>Анонимно</td></tr>";
		$emailform_address = ($emailform_address) ? "<tr><td>Адрес: </td><td>" . $emailform_address . "</td></tr>" : "";
		$emailform_city_district = ($emailform_city_district) ? "<tr><td>Район города: </td><td>" . $emailform_city_district . "</td></tr>" : "";
		$emailform_house_manage_company = ($emailform_house_manage_company) ? "Управляющая компания: </td><td>" . $emailform_house_manage_company . "</td></tr>" : "";
		$msend_emailform_phone = ($msend_emailform_phone) ? "<tr><td>Телефон: </td><td>" . $msend_emailform_phone . "</td></tr>" : "<tr><td>Телефон: </td><td>не указан</td></tr>";
		$msend_emailform_email = ($msend_emailform_email) ? "<tr><td>E-Mail: </td><td>" . $msend_emailform_email . "</td></tr>" : "<tr><td>E-Mail: </td><td>не указан</td></tr>";
		$msend_emailform_text = ($msend_emailform_text) ? "<tr><td>Сообщение: </td><td>" . $msend_emailform_text . "</td></tr>" : "<tr><td>Сообщение: </td><td>-</td></tr>";

		// кастомные чекбоксы
		$msend_chk = "";
		$i = 0;
		while ($_POST['emailform_customfield_chk'][$i] and $i < 50) {
			$msend_chk .= htmlspecialchars(str_replace($s_val, $t_val, substr($_POST['emailform_customfield_chk'][$i], 0, 255))) . '<br>';
			$i++;
		};
		$msend_chk = ($msend_chk) ? '<tr><td>Отмеченные пункты: </td><td>' . $msend_chk . '</td></tr>' : '';
//	print_r($_POST['mailform_customfield_chk']);
	
		$msend_message = '<table border="0" cellpadding="0" cellspacing="3">'.
							$msend_emailform_company .
							$msend_emailform_phone .
							$msend_emailform_email .
							$msend_emailform_name .
							$emailform_city_district .
							$emailform_address .
							$emailform_house_manage_company .
							$msend_chk .
							$msend_emailform_text .
							'</table>';
	};
					
	$msend_message = nl2br($msend_message);

	$msend_to      = $article_data[0]["form_email"];
	$msend_subject = '=?windows-1251?B?' . base64_encode($article_data[0]["form_subject"]) . '?=';

	$msend_headers = 
//							"From: bmw518@bk.ru\n" .
							'From: mailform@'.$board_config['server_name'] . "\n" .
							"Content-type: text/html; charset=windows-1251\n".
//							"Reply-To: bmw518@bk.ru\n" .
							'Reply-To: mailform@'.$board_config['server_name'] . "\n" .
							'X-Mailer: PHP/' . phpversion();


	if (mail($msend_to, $msend_subject, $msend_message, $msend_headers)) {
		$msend_result = "<p><div align='center'><strong>Сообщение отправлено</strong></p></div>";
		$show_article = FALSE;
	}
	else {
		$add = "\n" . $msend_to . "\n" . $msend_subject . "\n" . $msend_message . "\n" . $msend_headers;
		message_die(GENERAL_ERROR, '<strong><span style="color: #FF0000;">Ошибка отправки сообщения.</span> Будем разбираться.</strong><p>' . $add, '</p>', __LINE__, __FILE__, $sql);
		$show_article = FALSE;
	};


//$msend_result = "<p><div align='center'><strong>Сообщение отправлено</strong></p></div>" . $msend_message;
//$show_article = FALSE;


$template->assign_block_vars('switch_form_sended', array(
	'TEXT' => $msend_result
));


?>

