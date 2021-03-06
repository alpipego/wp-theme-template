<?php

/**
 * Basic Theme Setup
 */
include 'functions/BasicSetup.php';


/**
 *  Enqueue Scripts and Styles
 */

include 'functions/Scripts.php';


/**
 * Post Thumbnails
 */

include 'functions/PostThumbnails.php';

/**
 * Sidebar
 */

include 'functions/Sidebar.php';

/**
 * Navigation Menus
 */

include 'functions/NavigationMenus.php';


/**
 * Post Excerpt
 */

include 'functions/Excerpt.php';

/**
 * print template in footer
 */
add_action( 'wp_footer', function () {
	global $template;

	if ( isset( $_GET['template'] ) && $_GET['template'] ) {
		print_r( $template );
	}
} );

/**
 * Add meta information to head
 */
// add_action('wp_head', function() {
//     if (get_previous_posts_link() !== null) {
//         preg_match('%href="(.+?)"%', get_previous_posts_link(), $matches);
//         echo sprintf('<link rel="prev" href="%s" />', $matches[1]);
//     }

//     if (get_next_posts_link() !== null) {
//         preg_match('%href="(.+?)"%', get_next_posts_link(), $matches);
//         echo sprintf('<link rel="next" href="%s" />', $matches[1]);
//     }
// });
