<?php

add_action('widgets_init', function() {
    register_sidebar([
        'name'          => 'Sidebar (Single Post)',
        'id'            => 'sidebar_single',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ]);
});
