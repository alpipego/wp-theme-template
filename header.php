<?php
    $path = new \Theme\Functions\Path();

?>
<!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php bloginfo( 'name' ); ?></title>

	
	<link rel="icon" href="<?php $path->images(); ?>favicon.ico" type="">
	<link rel="shortcut icon" href="<?php  $path->images(); ?>favicon.ico" type="image/x-icon" />

    <?php // styles either here or the proper way via wp_enqueue_styles in functions.php ?>
	<link rel="stylesheet" href="<?= $path->css(); ?>normalize.css" />
	<link rel="stylesheet" href="<?php $path->css(); ?>styles.css" />
	<?php wp_head(); ?>
	<script>
		if (window.navigator.msMaxTouchPoints) {
			$( 'html' ).removeClass( 'no-touch' ).addClass( 'touch' );
		}
	</script>

</head>
<body id="#top" class="">
	

	<header>
		<div class="nav">

		</div>
	</header>

	<div id="main" class="main">	
		<div class="main-inner">
