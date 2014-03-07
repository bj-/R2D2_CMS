<table width="962" border="0" cellspacing="0" cellpadding="0" align="center" class="maintable">
<tr valign="top">
<td><table width="100%" border="0" cellspacing="0" cellpadding="8"><tr><td>
<!-- off Design -->

<link rel="stylesheet" href="/jquery/css/uploadify.css" type="text/css" media="screen" />
<script src="/jquery/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/jquery/js/swfobject.js"></script>
<script src="/jquery/js/uploadify.v2.1.0.min.js" type="text/javascript"></script>

Фотогалерея статьи<br>


 
<!-- begin WP sIFR -->
</head>
 
<body id="page_">

<a href="javascript:$('#fileInput1').uploadifyUpload();"><img src="/pic/ico/upload_48x48.png" alt="Начать загрузку" width="48" height="48" border="0"></a> 
<a href="javascript:$('#fileInput1').uploadifyClearQueue();"><img src="/pic/ico/edit-clear_48x48.png" alt="Очистить очередь" width="48" height="48" border="0"></a>
<img src="/pic/ico/preview-file_48x48.png" alt="" width="48" height="48" border="0">

<input id="fileInput1" name="fileInput1" type="file" />
<script type="text/javascript">
 $(document).ready(function() {
	$("#fileInput1").uploadify({
		'uploader'		: '/jquery/uploadify/uploadify.swf',
		'script'		: '/jquery/uploadify/uploadify.php',
		'cancelImg'		: '/jquery/uploadify/cancel.png',
		'folder'		: '/gallery',
		'multi'			: true,
	    'fileDesc'		: '*.jpg;*.png;*.gif',
	    'fileExt'		: '*.jpg;*.png;*.gif',
		'buttonText'	: 'Browse...'

	});
 });
</script>
<!-- on Design -->
	</td></tr></table>
</td></tr></table>