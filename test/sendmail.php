<?php

//$debug_mode = TRUE;
$debug_mode = FALSE;

$SiteName = "taz.ru";


if ( $debug_mode )
{
	// ���������� ��� ������ � ������
	error_reporting( E_ALL );
}

function writeSendMailLog($text)
{
	global $debug_mode; 

	// Write log
	// ���� / ����� / �������� / IP ������ �������� ������ / �����
	// TODO -- ������ ���
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

	// �������� �������� ������ �����������
	// ������ ��������� ������� �� ����������
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

//	$correctData = FALSE; // ���� �������� �� �������� - ������ �� ��������.
//	$msend_result .= ($debug_mode) ? "<pre>\n" . $to . "\n" . $subject . "\n" . $message . "</pre>" : "";

	if ( $to == "" or $subject == "" or $message == "")
	{
		$msend_result = "<p><div align='center'><strong>������ �������� ���������</strong> (�� ������� ����, ���������� ��� ���������)</p></div>";
		$diagdata = "<pre>\n" . $to . "\n" . $subject . "\n" . $message . "</pre>";
		$msend_result .= ($debug_mode) ? $diagdata : "";
		writeSendMailLog ( "$msend_result . $diagdata", FALSE ); // �������� � ��� ������

		$correctData = FALSE;
	}

	if ( $correctData ) 
	{
		$msend_message = nl2br($message);
	
		$msend_to      = $to;
		$msend_subject = '=?windows-1251?B?' . base64_encode($subject) . '?=';
	
		$msend_headers = 
	//							"From: mailform@" . $SiteName . "\n" .
								"From: ����������� <mailform@" . $SiteName . ">\n" . // ����� �����������
								"Content-type: text/html; charset=windows-1251\n".
								'Reply-To: mailform@'. $SiteName . "\n" . // ���� ����� ����������� ���� ���������� ����� ��������
								'X-Mailer: PHP/' . phpversion();
	
		if (mail($msend_to, $msend_subject, $msend_message, $msend_headers)) {
			$msend_result = "<p><div align='center'><strong>��������� ����������</strong></p></div>";
			$MessageSent = TRUE;

			$diagdata = "<pre>\n" . $msend_to . "\n" . $msend_subject . "\n" . $msend_message . "\n" . $msend_headers . "</pre>";
			// �������� � ��� ��� ���� ���������� � ����/�����
			writeSendMailLog ( "$msend_result . $diagdata", TRUE ); // �������� � ��� ������
		}
		else {
			$msend_result = "<p><div align='center'><strong>������ �������� ���������</strong></p></div>";

			// �������� � ��� ��� ������ � ����/�����
			$diagdata = "<pre>\n" . $msend_to . "\n" . $msend_subject . "\n" . $msend_message . "\n" . $msend_headers . "</pre>";
			$msend_result .= ($debug_mode) ? $diagdata : "";
			writeSendMailLog ( "$msend_result . $diagdata", FALSE ); // �������� � ��� ������
		};
	}

	echo  $msend_result;
	return 	$MessageSent;
}

// ����� (������ ������ ��������� ���� ����)

$mailto = "��� ����� ���� <bmw518@bk.ru>";
$subject = "���� ��������� (Kiss my shiny ass!)";
$mailmessage = "
<h1>���������</h1>
<ul>
	<li>�����1</li>
	<li>�����2</li>
	<li>�����3</li>
</ul>
";

// ����� � ��������� ���������� ������ ������� TRUE/FALSE ��� ����������� �������������
$sent_result = sendmail($mailto, $subject, $mailmessage)

//echo $sent_result;

?>

 