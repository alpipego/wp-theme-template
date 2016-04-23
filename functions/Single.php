<?php

function getSinglePost($postId)
{
    global $post;
    $post = get_post($postId);
    setup_postdata($post);

    if (!defined('DOING_AJAX') || !DOING_AJAX) :
    ?>
        <h1><?= get_the_title(); ?></h1>
    <?php
    endif;

    getQuote($mainQuery->post->ID);

    the_content();
}

add_action('wp_ajax_get_single_post', 'getSinglePostAjax');
add_action('wp_ajax_nopriv_get_single_post', 'getSinglePostAjax');

function getSinglePostAjax()
{
    getSinglePost($_GET['post_id']);
    wp_die();
}
