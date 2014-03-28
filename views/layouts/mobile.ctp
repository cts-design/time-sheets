<html>
<head>
	<title>
		<?= Configure::read('Website.name') ?>
	</title>

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.5">
	<script src="/js/jquery1110.min.js"></script>

	<link rel="stylesheet" href="/css/mobile_link.css" />
</head>
<body>
	<img src="/img/theme/florida-logo.png" alt="" class="mobile-logo" />
	<?= $content_for_layout ?>
</body>
</html>