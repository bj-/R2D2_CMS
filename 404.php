<?php
include "db/common.php";

echo $header;

$ptitle = '����"';
$pclassification = '';
$pdesc = '';
$pkeywords = '';
?>
	<title><?php echo $ptitle;?></title>
	<meta name="Classification" content="<?php echo $pclassification; ?>">
	<meta name="Description" content="<?php echo $pdesc; ?>">
	<meta name="Keywords" content="<?php echo $pkeywords; ?>">

<?php echo $pagetop; ?>
<p><br><h3>������������� �������� �� ������</h3>

<p>������� <a href = "<?php echo $site_url;?>">�� ������� ��������</a>
<br><p><br>
<?php echo $pagebottom; ?>

