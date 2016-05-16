<?php

add_filter('the_content', 'cleanContent');
add_filter('content_edit_pre', 'cleanContent');

function cleanContent($content) {
    global $post;
    $postId = isset($GLOBALS['id']) ? $GLOBALS['id'] : $post->ID;

    $content = preg_replace('%\R%', '', $content);
    $content = preg_replace('%<span style="font-size:\s?(?:x-)?large;"><b>(.+?)</b></span>%', '<h2>$1</h2>', $content);
    $content = preg_replace('%<b><span style="font-size:\s?(?:x-)?large;">(.+?)</span></b>%', '<h2>$1</h2>', $content);
    $content = preg_replace('%\s?style=".+?"%', '', $content);
    $content = preg_replace('%<br\s?/?>%', '', $content);
    $content = preg_replace('%(</?div>)%', '', $content);
    $content = preg_replace('%((<blockquote>)+)%', '$2', $content);
    $content = preg_replace('%((</blockquote>)+)%', '$2', $content);
    $content = preg_replace('%<a[^/>]*?>(<img.*?>)</a>%', '$1', $content);
    $content = preg_replace('%<(?:[a-z1-6]+?)[^/>]*?></(?:[a-z1-6]+?)>%', '', $content, -1, $count);

    while ($count > 0) {
        $content = preg_replace('%<(?:[a-z1-6]+?)[^/>]*?></(?:[a-z1-6]+?)>%', '', $content, -1, $count);
    }


    // remove featured image
    $content = preg_replace('%^(?:<(?:div|p)[^/>]*?>)*?(?:<a[^>]+?>)?<img.+?/>(?:</(?:div|a|p)>)*%', '$1', $content);

    preg_match_all('%<img.*?src="(https?://(?:[^/]*?blogspot.com/.+?))".*?/?>%', $content, $images);

    if (!empty($images[1])) {
        if (!function_exists('media_sideload_image')) {
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
        }

        $uploads = wp_upload_dir();
        stream_context_set_default(['http' => [ 'timeout' => 2, 'method' => 'HEAD',]]);

        foreach ($images[1] as $image) {
            $file = explode('/', $image);
            $remoteFile = $image;
            $localFile = trailingslashit($uploads['basedir']) . sanitize_file_name(end($file));
            $localFileUrl = trailingslashit($uploads['baseurl']) . sanitize_file_name(end($file));
            $remoteFileExists = (bool) strpos(get_headers($remoteFile, 1)[0], '200');
            $localFileExists = file_exists($localFile);

            // remove links, if they only link images
            $content = preg_replace('%<a[^>]*?href="[^"]+?\.(?:jpg|jpeg|png)"[^>]*?>(<img[^>]*?>)</a>%i', '$1', $content);

            if (!$remoteFileExists || ($remoteFile === $localFile && $localFileExists)) {
                continue;
            }

            if (!$localFileExists) {
                media_sideload_image($remoteFile, $postId);
            }

            $content = str_replace($remoteFile, $localFileUrl, $content);
        }
    }

    // remove useless tag combinations
    $content = preg_replace('%(?<=<blockquote>)<h2>(.*?)</h2>(?=</blockquote>)%', '$1', $content);
    $content = preg_replace('%(?<=<h2>)<span>(.*?)</span>(?=</h2>)%', '$1', $content);

    return $content;
}
