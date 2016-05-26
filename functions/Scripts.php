<?php

add_action('wp_enqueue_scripts', function() {
    $js = get_stylesheet_directory_uri() . '/js/';
    wp_register_script('app', $js . 'app.min.js', ['jquery'], '', true);

    wp_enqueue_script([
            'app',
        ]
    );

    // \wp_localize_script( handle, name, array(

        //)
    //);
});

function addAdminScripts($hook_suffix)
{
    wp_register_script( '', get_path('js') . '', array( 'jquery' ), '', true );

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
    wp_register_style('app', $css . 'app.css');

    wp_enqueue_style('app');
});
