<?php

get_header();
?>
<div class="index-single-post-container single-full">
    <div class="index-single-post">
        <img class="wp-post-image lazy" src="" data-src="<?= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>" alt="" />

        <div class="index-single-content-container">
            <div class="single-title">
                <div class="index-single-meta"><?= the_time('l, d. F Y'); ?></div>
                <h1><?= get_the_title(); ?></h1>
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
            <?php getSinglePost(get_the_ID()); ?>
        </div>
    </div>
</div>

<?php

get_footer();
