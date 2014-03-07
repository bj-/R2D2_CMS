<?php

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

?>