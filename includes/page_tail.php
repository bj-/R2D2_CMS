<?php
/***************************************************************************
 *                              page_tail.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: page_tail.php,v 1.27.2.2 2002/11/26 11:42:12 psotfx Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if ( !defined('IN_R2D2') )
{
	die('Hacking attempt');
}

//$template->pparse('overall_header');

//
// Show the overall footer.
//
$admin_link = ( $userdata['user_level'] == ADMIN ) ? '<a href="admin/index.' . $phpEx . '?sid=' . $userdata['session_id'] . '">' . $lang['Admin_panel'] . '</a><br /><br />' : '';

$template->set_filenames(array(
	'overall_footer' => ( empty($gen_simple_header) ) ? 'overall_footer.tpl' : 'simple_footer.tpl')
);

//
// Блоки вставляем в страницы.
//
if ($mod_blocks) {
	$i = 0;
	while ($blocks_xdata[$i]["id"]) {
		$template->assign_vars(array(
			'BLOCK_'.$blocks_xdata[$i]["id"] => $blocks_xdata[$i]["text"],
		));
		if ($userdata['user_level'] >0) {
			$template->assign_vars(array(
				'BLOCK_'.$blocks_xdata[$i]["id"].'_EDIT' => $blocks_xdata[$i]["edit"],
			));
		};
		
		$i++;
	};
};

$template->assign_vars(array(
	'TRANSLATION_INFO' => ( isset($lang['TRANSLATION_INFO']) ) ? $lang['TRANSLATION_INFO'] : '', 
	'BLOCK_RIGHT_1' => $blocks_data["right"][1]["text"],
	'BLOCK_FOOTER_1' => $blocks_data["bottom"][1]["text"],
	'ADMIN_LINK' => $admin_link)
);

if ($page_type <> 'index') {
//	$template->assign_block_vars('page_header', array());
};


// переключатель шаблона страницы админ/не админ
if ( $adm )
{
	$template->assign_block_vars('admin_body_footer', array());
}
else {
	$template->assign_block_vars('body_footer', array());
}

$template->pparse('overall_footer');

//
// Close our DB connection.
//
$db->sql_close();

// Mod_Rewrite
$contents = ob_get_contents();
ob_end_clean();
//echo replace_mod_rewrite($contents);
echo $contents;
global $dbg_starttime;


//
// Compress buffered output if required and send to browser
//
if ( $do_gzip_compress )
{
	//
	// Borrowed from php.net!
	//
	$gzip_contents = ob_get_contents();
	ob_end_clean();

	// Mod_Rewrite
	echo replace_for_mod_rewrite($contents);
	global $dbg_starttime;

	$gzip_size = strlen($gzip_contents);
	$gzip_crc = crc32($gzip_contents);

	$gzip_contents = gzcompress($gzip_contents, 9);
	$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

	echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	echo $gzip_contents;
	echo pack('V', $gzip_crc);
	echo pack('V', $gzip_size);
}

//$template->display('body');


$ddd=microtime();
$ddd=((double)strstr($ddd, ' ')+(double)substr($ddd,0,strpos($ddd,' ')));
$xxx = (@number_format((@$ddd-$GLOBALS["ttt"]),3));
echo '<script type="text/javascript">' . " window.status='Время генерации страницы: " . $xxx ." секунд';</script>
</body>
</html>";


exit;

?>