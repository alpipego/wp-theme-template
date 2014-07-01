<!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php bloginfo( 'name' ); ?></title>

	
	<link rel="icon" href="<?php pi_path( 'img' ); ?>favicon.ico" type="">
	<link rel="shortcut icon" href="<?php  pi_path( 'img' ); ?>favicon.ico" type="image/x-icon" />


	<?php if( is_user_logged_in() ) : ?>
		<style>
			.main-container {display:none;}
		</style>
	<?php endif; ?>
	<link rel="stylesheet" href="<?php pi_path( 'css' ); ?>normalize.css" />
	<link rel="stylesheet" href="<?php pi_path( 'css' ); ?>styles.css" />
	<?php wp_head(); ?>
	<script>
		var $ = jQuery.noConflict();
		if (window.navigator.msMaxTouchPoints) {
			$( 'html' ).removeClass( 'no-touch' ).addClass( 'touch' );
		}
	</script>

</head>
<body class="" id="#top">
	

	<header>
		<div class="nav">

		</div>
	</header>

	<div id="main" class="main">	
		<div class="main-inner">