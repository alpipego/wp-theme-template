<?php

get_header();

$request = ['offset' => get_query_var('paged') ? get_query_var('paged') : 1];
if (is_archive()) {
    $tax = get_queried_object();
    $request['tax_query'][] = [
        'taxonomy' => $tax->taxonomy,
        'field' => 'term_taxonomy_id',
        'terms' => $tax->term_taxonomy_id
    ];
}

$maxPages = getMorePosts($request);

?>
<div class="index-pagination" id="pagination">
    <?= get_previous_posts_link(); ?>
    <?= get_next_posts_link('Next Page &raquo;', $maxPages); ?>
</div>
<?php
get_footer();
