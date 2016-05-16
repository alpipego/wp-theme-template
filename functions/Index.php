<?php

function getMorePosts($options = [])
{
    $request = array_merge(
        [
            'posts_per_page' => 10,
            'post_status' => 'publish',
            'post_type' => 'post',
            'paged' => 1,
        ],
        $options
    );

    $mainQuery = new WP_Query($request);
    $ajax = defined('DOING_AJAX') && DOING_AJAX;

    if ($mainQuery->have_posts()) :
        if (!$ajax) :
    ?>
            <div class="index-posts" id="index-posts">
    <?php
        endif;
        while ($mainQuery->have_posts()) :
            $mainQuery->the_post();
    ?>
            <div class="index-single-post-container<?= $ajax ? ' isotope-hidden' : ''; ?>" data-id="<?= $mainQuery->post->ID; ?>">
                <div class="index-single-post">
                    <a href="<?= get_the_permalink(); ?>" class="index-single-post-link">
                        <div class="index-single-title">
                            <div class="index-single-meta"><?= the_time('l, d. F Y'); ?></div>
                            <h2><?= get_the_title(); ?></h2>
                            <div class="index-single-rating">
                                <?php
                                    $hearts = get_field('leser_bewertung') ?: 0;
                                    $half = ($hearts * 2) % 2;
                                    for ($i=0; $i < $hearts; $i++) {
                                        echo ($half && $i == (int) $hearts) ? '&frac12;' : '&hearts;';
                                    }
                                ?>
                            </div>
                        </div>
                    </a>
                    <img class="wp-post-image lazy" src="" data-src="<?= wp_get_attachment_url(get_post_thumbnail_id($mainQuery->post->ID)); ?>" alt="" />
                    <?php
                    //getQuote($mainQuery->post->ID, 'index-');
                    ?>
                    <div class="index-single-content-container"></div>
                </div>
            </div>
    <?php
        endwhile;
        if (!$ajax) :
    ?>
            </div>
    <?php
        endif;
    endif;

    if (!$ajax) {
        return $mainQuery->max_num_pages;
    }
}

add_action('wp_ajax_get_index_posts', 'getMorePostsAjax');
add_action('wp_ajax_nopriv_get_index_posts', 'getMorePostsAjax');

function getMorePostsAjax()
{
    $options = ['paged' => (int) $_GET['offset']];
    if (!empty($_GET['tax'])) {
        $options['tax_query'][] = array_map('sanitize_text_field', $_GET['tax']);
    }
    if (!empty($_GET['post_type'])) {
        $options['post_type'] = sanitize_text_field($_GET['post_type']);
    }

    ob_start();
    getMorePosts($options);
    $posts = ob_get_contents();
    ob_end_clean();

    echo $posts;
    wp_die();
}

function getQuote($postId, $classPrefix = '') {
    $quote = get_field('leser_zitat', $postId);
    $cite = get_field('leser_quelle', $postId);
    if ($quote) :
?>
        <blockquote class="<?= $classPrefix; ?>single-quote">
            <p>&#8222;<?= $quote; ?>&#8220;</p>
            <?php if ($cite) : ?>
                <footer>
                    <cite>&ndash;&nbsp;<?= $cite; ?></cite>
                </footer>
            <?php endif; ?>
        </blockquote>
<?php endif;
}

add_action('wp_ajax_get_share_urls', 'getShareUrls');
add_action('wp_ajax_nopriv_get_share_urls', 'getShareUrls');

function getShareUrls()
{
    echo json_encode([
        'permalinkUrl' => get_permalink((int) $_GET['post_id']),
        'facebookUrl' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode(get_permalink((int) $_GET['post_id'])),
        'twitterUrl' => 'https://twitter.com/intent/tweet?text=' . htmlspecialchars(urlencode(html_entity_decode(get_the_title((int) $_GET['post_id']), ENT_COMPAT, 'UTF-8'))) . '&url=' . get_permalink((int) $_GET['post_id'])
    ]);
    wp_die();
}
