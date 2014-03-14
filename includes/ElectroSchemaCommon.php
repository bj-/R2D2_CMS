<?php

// одрезаем нули справа и, затем, точку если она стала последней => получаем целое число если у него не было дроби
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

// получаем параметры фильтра компонентов

function get_filter_parameters($name, $value, $operator)
{
	// name = column name (ID)
	// value = value (755)
	// operator = >= , Like, < etc (like)
	// ex: ID like "755"
	 
	// по этим занчениям фильтры не задаются
	$notInFilter = array(
		"Название...",
		"Цвет...",
		"Корпус...",
		"Монтаж...",
		""
		);
	
	$value = trim($value);
	$value = iconv("UTF-8", "CP1251", $value);
	$value = substr($value,0,20);
	$value = str_encode_char($value); //Экранируем апостроф
	$value = str_replace('"', '', $value); //убираем двойные ковычки нафиг
	
	// если значени в списке недопустимых - ничего не возвращаем
	if (in_array($value, $notInFilter))
	{
		return false;
	}
	
	
	if ($operator == "like")
	{
		return '`' . $name . '` like "%' . $value . '%"'; // собираем параметр фильтрации
	}
	else
	{
		return '`' .$name . '` ' . $operator . ' "' . $value . '"'; // собираем параметр фильтрации
	}
	
};

?>