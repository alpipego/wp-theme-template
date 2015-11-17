<?php

add_action( 'wp_enqueue_scripts', function() {
    $js = get_stylesheet_directory_uri() . '/js/';
    wp_register_script('app', $js . 'app.min.js', array('jquery'), '', false);

    wp_enqueue_script( array(
            'app',      
        ) 
    );

    // \wp_localize_script( handle, name, array(

        //) 
    //);
});

// Inline script
add_action('wp_head', function() {
    echo '<script>if (window.navigator.msMaxTouchPoints) { $("html").removeClass("no-touch").addClass("touch"); }</script>';
});

function addAdminScripts($hook_suffix) 
{
    wp_register_script( '', get_path( 'js' ) . '', array( 'jquery' ), '', true );

    if ( $hook_suffix == '' ) {
        wp_enqueue_script( array(

        ) );
    }
}

/**
*   ENQUEUE STYLES
*/

add_action('wp_enqueue_scripts', function() {
    $css = get_stylesheet_directory_uri() . '/css/';
    wp_register_style('normalize', $css . 'normalize.css', array());
    wp_register_style('app', $css . 'app.css', array('normalize'));

    wp_enqueue_style(array( 
        'normalize',
        'app'
    ));
});
