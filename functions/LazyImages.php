<?php

add_filter('the_content', function($content) {
    preg_match_all('%<img([^>]*?)src="(.*?)"([^/>]*?)/?>%', $content, $images, PREG_SET_ORDER);

    foreach ($images as $img) {
        $content = str_replace($img[0], sprintf('<img src="" data-src="%s" class="lazy" %s %s />', esc_url($img[2]), trim($img[1]), trim($img[3])), $content);
    }

    return $content;
}, 11);
