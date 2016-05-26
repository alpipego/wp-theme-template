<?php

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();
        ?>
        <h2><a href="<?= get_the_permalink(); ?>"><?= get_the_title(); ?></a></h2>
        <div class="index-content">
            <?= get_the_excerpt(); ?>
        </div>
        <?php
    endwhile;
endif;

get_footer();
