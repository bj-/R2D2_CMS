<?php
include "db/common.php";

echo $header;

$ptitle = 'Сайт"';
$pclassification = '';
$pdesc = '';
$pkeywords = '';
?>
	<title><?php echo $ptitle;?></title>
	<meta name="Classification" content="<?php echo $pclassification; ?>">
	<meta name="Description" content="<?php echo $pdesc; ?>">
	<meta name="Keywords" content="<?php echo $pkeywords; ?>">

<?php echo $pagetop; ?>
<p><br><h3>Запрашиваемый документ не найден</h3>

<p>Перейти <a href = "<?php echo $site_url;?>">на главную страницу</a>
<br><p><br>
<?php echo $pagebottom; ?>

