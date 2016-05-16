<?php

add_action('wp_enqueue_scripts', function() {
    $js = get_stylesheet_directory_uri() . '/js/';
    wp_register_script('app', $js . 'app.min.js', ['jquery', 'scrollspy'], '', true);
    wp_register_script('index', $js . 'index.min.js', ['jquery', 'isotope', 'app', 'loadie'], '', true);
    wp_register_script('single', $js . 'single.min.js', ['jquery', 'app'], '', true);
    wp_register_script('loadie', $js . 'loadie.min.js', ['jquery'], '', true);
    wp_register_script('scrollspy', $js . 'scrollspy.min.js', ['jquery'], '', true);
    wp_register_script('masonry', 'https://npmcdn.com/masonry-layout@4.0/dist/masonry.pkgd.min.js', ['jquery'], '', true);
    wp_register_script('isotope', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js', ['jquery', 'imagesLoaded'], '', true);
    wp_register_script('imagesLoaded', 'https://npmcdn.com/imagesloaded@4.1/imagesloaded.pkgd.min.js', [], '', true);

    wp_enqueue_script([
            'app'
        ]
    );

    if (is_home() || is_archive()) {
        wp_enqueue_script('index');
    }

    if (is_single()) {
        wp_enqueue_script('single');
    }

    if (is_archive()) {
        if (is_tax() || is_tag()) {
            $tax = get_queried_object();

            wp_localize_script( 'index', 'query_request', [
                'tax_query' => [
                    'taxonomy' => $tax->taxonomy,
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $tax->term_taxonomy_id
                ]
            ] );
        }

        if (is_post_type_archive()) {
            $cpt = get_queried_object();
            wp_localize_script( 'index', 'query_request', [
                'post_type' => $cpt->name,
            ]);
        }
    }
});

// Inline script
add_action('wp_head', function() {
//    echo '<script>if (window.navigator.msMaxTouchPoints) { $("html").removeClass("no-touch").addClass("touch"); }</script>';
    ?>
    <script>window.speedTestStart = (new Date()).getTime();</script>
<?php
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
    wp_register_style('app', $css . 'app.css', []);

    wp_enqueue_style('app');
});
