<?php

add_theme_support('post-thumbnails');

// remove default sizes
add_filter('intermediate_image_sizes_advanced', function($sizes) {
    unset($sizes['thumbnail']);
    unset($sizes['medium']);
    unset($sizes['large']);

    return $sizes;
});
