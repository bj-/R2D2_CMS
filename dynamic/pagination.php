<?php
// �������������� ������� (������)

if ( !defined('IN_R2D2') )
{
	die("Hacking attempt");
}

// �������� ����������
// current_page - ������� ��������
// item_per_page - ��������� �� ��������
// items_array - ������ � �������
// page_url - ��� ��������

function pagination($current_page, $item_per_page, $items_array, $page_url) {
	global $template;

	$current_page = ($current_page) ? $current_page : 1; //���� �� ������ ����� ��������
	$item_in_array = count($items_array);

	$total_pages = $item_in_array/$item_per_page;
	$total_pages = ((substr($total_pages,0,strpos($total_pages,"."))) == ($item_in_array/$item_per_page)) ? $total_pages : substr($total_pages,0,strpos($total_pages,"."))+1;

	$ret = "";

	$template->set_filenames(array(
		'pagination' => 'pagination.tpl')
	);

	// ���������� ��������
	if ($current_page==1) {
		$template->assign_block_vars('swich_pagination_prev_inactive',array());
	}
	Else {
		$template->assign_block_vars('swich_pagination_prev_active',array(
			'PREV_PAGE' => $page_url.'page-'.($current_page-1).'.html'
		));
	};

	// ������� ��������
	$i = 1;
	while ($total_pages >= $i) {
		$template->assign_block_vars('swich_pagination_page_list',array(
			'PAGE_ID' => $i
		));
	
		if (($i != $current_page)) {
			$template->assign_block_vars('swich_pagination_page_list.swich_pagination_page_active',array(
				'PAGE_URL' => $page_url.'page-'.$i.'.html'
			));
		};
		$i++;
	};

	// ��������� ��������
	if ($current_page==$total_pages) {
		$template->assign_block_vars('swich_pagination_next_inactive',array());
	}
	Else {
		$template->assign_block_vars('swich_pagination_next_active',array(
			'NEXT_PAGE' => $page_url.'page-'.($current_page+1).'.html'
		));
	};

	$template->assign_var_from_handle('PAGINATION', 'pagination');

	return $ret;

};

?>
