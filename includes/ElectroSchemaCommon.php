<?php

// �������� ���� ������ �, �����, ����� ���� ��� ����� ��������� => �������� ����� ����� ���� � ���� �� ���� �����
function rTrimZeroAndDot($string)
{
	$ret = rtrim($string,"0");
	$ret = rtrim($ret,".");
	return $ret;
};


function MakePropetyLinks($DataSheetPath, $type, $data)
{

	if ($type == "design" and $data)
	{
		$ret = '<a href="/img/files/electro/DataSheets/'.$DataSheetPath.'/Cases/' . $data . '"><img src="/pic/ico/pencil-ruler_7907.png" alt="Data Sheet" border="0" height="16" width="16" /></a>';
	}
	elseif($type == "photo" and $data)
	{
		$ret = '<a href="/img/files/electro/DataSheets/'.$DataSheetPath.'/Photo/' . $data . '"><img src="/pic/ico/photo_16x16.png" alt="Photo" border="0" height="16" width="16" /></a>';
	}
	elseif($type == "dSheet" and $data)
	{
		$ret = '<a href="/img/files/electro/DataSheets/'.$DataSheetPath.'/' . $data . '"><img src="/pic/ico/document.png" alt="Item Design" border="0" height="16" width="16" /></a>';
	}
	else
	{
		$ret = '<img src="/pic/1pxtransparent.png" width="16" height="16" />';
	}

	return $ret;
}

// �������� ��������� ������� �����������

function get_filter_parameters($name, $value, $operator)
{
	// name = column name (ID)
	// value = value (755)
	// operator = >= , Like, < etc (like)
	// ex: ID like "755"
	 
	// �� ���� ��������� ������� �� ��������
	$notInFilter = array(
		"��������...",
		"����...",
		"������...",
		"������...",
		""
		);
	
	$value = trim($value);
	$value = iconv("UTF-8", "CP1251", $value);
	$value = substr($value,0,20);
	$value = str_encode_char($value); //���������� ��������
	$value = str_replace('"', '', $value); //������� ������� ������� �����
	
	// ���� ������� � ������ ������������ - ������ �� ����������
	if (in_array($value, $notInFilter))
	{
		return false;
	}
	
	
	if ($operator == "like")
	{
		return '`' . $name . '` like "%' . $value . '%"'; // �������� �������� ����������
	}
	else
	{
		return '`' .$name . '` ' . $operator . ' "' . $value . '"'; // �������� �������� ����������
	}
	
};

?>