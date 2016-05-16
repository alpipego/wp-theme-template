<?php

get_header();

$request = ['paged' => get_query_var('paged') ? get_query_var('paged') : 1];

if (is_archive()) {
    if (is_tax() || is_tag()) {
        $tax = get_queried_object();
        $request['tax_query'][] = [
            'taxonomy' => $tax->taxonomy,
            'field'    => 'term_taxonomy_id',
            'terms'    => $tax->term_taxonomy_id
        ];
    }

    if (is_post_type_archive()) {
        $cpt = get_queried_object();
        $request['post_type'] = $cpt->name;
    }
}

$maxPages = getMorePosts($request);

?>
<div class="index-pagination" id="pagination">
    <?= get_previous_posts_link(); ?>
    <?= get_next_posts_link('Next Page &raquo;', $maxPages); ?>
</div>

<?php
    $uploads = wp_upload_dir();
?>
<img src="<?= $uploads['baseurl']; ?>/DSCN1335.jpg" alt="test-image" class="test-image" id="speedTest" />
<?php
get_footer();
