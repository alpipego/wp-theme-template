<?php

// Remove empty paragraph tags
add_filter('the_content', function($content) {
   $content = preg_replace('%<p>(?:&nbsp;)*?\s*?</p>%', '', $content);
    return $content;
}, 9999);

remove_filter('the_excerpt', 'wpautop'); 

// remove generators
add_filter( 'the_generator', function() {
    return '';
});

// Disable WordPress version reporting as a basic protection against attacks
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

// remove wp version param from any enqueued scripts
add_filter( 'style_loader_src', 'removeWpVer', 9999 );
add_filter( 'script_loader_src', 'removeWpVer', 9999 );

function removeWpVer( $src ) 
{
    if ( strpos( $src, 'ver=' ) )
        $src = \remove_query_arg( 'ver', $src );
    return $src;
}

// always define ajaxurl
add_action('wp_head', function() {
    echo sprintf('<script>var ajaxurl = "%s"</script>', admin_url('admin-ajax.php'));
});
