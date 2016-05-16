<!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <title><?= get_bloginfo('name'); ?></title>

    <link rel="icon" href="<?= path(); ?>images/favicon.ico">
    <link rel="shortcut icon" href="<?= path(); ?>images/favicon.ico" type="image/x-icon" />

    <?php wp_head(); ?>

</head>
<body id="#top" class="<?= is_admin_bar_showing() ? 'adminbar' : ''; ?>">


    <header>
        <a href="<?= get_bloginfo('url'); ?>">
            <img src="" class="header-logo" id="header-logo" data-src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
        </a>
        <nav class="nav" id="nav">
            <ul class="main-nav">
                <li class="menu-item menu-toggle" id="menu-toggle">
                    <label>Menu</label>
                </li>
                <div class="mobile-menu" id="mobile-menu">
                    <li class="menu-item">
                        <a href="/was">Was?</a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= get_post_type_archive_link('interview'); ?>">Interviews</a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= get_post_type_archive_link('querverweis'); ?>">Querverweise</a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= get_post_type_archive_link('selfie'); ?>">Selfies</a>
                    </li>

                    <div class="socials">
                        <?php
                            $facebookUrl = is_home() ? 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode(get_bloginfo('url')) : 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode(get_permalink());
                            $twitterUrl = is_home() ? 'https://twitter.com/intent/tweet?text=' . htmlspecialchars(urlencode(html_entity_decode(get_bloginfo('title'), ENT_COMPAT, 'UTF-8'))) . '&url=' . get_bloginfo('url') : 'https://twitter.com/intent/tweet?text=' . htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8'))) . '&url=' . get_permalink();

                        ?>
                        <a href="<?= $twitterUrl; ?>" target="_blank" id="twitter-share" class="social"><img src="<?= get_stylesheet_directory_uri() . '/img/twitter.svg'; ?>" alt="Twitter Share Button"></a>
                        <a href="<?= $facebookUrl; ?>" target="_blank" id="facebook-share" class="social"><img src="<?= get_stylesheet_directory_uri() . '/img/facebook.svg'; ?>" alt="Twitter Share Button"></a>
                        <a href="<?= get_bloginfo('url') . $_SERVER['REQUEST_URI']; ?>" class="social" id="link-share"><img class="link" src="<?= get_stylesheet_directory_uri() . '/img/link.svg'; ?>" alt="link image"></a>
                    </div>
                </div>

                <li class="search menu-item" id="search-toggle">
                    <label class="search-toggle-closed">Suche</label>
                    <label class="search-toggle-open">Suche schließen</label>
                </li>
            </ul>


                <div class="filter" id="filter">
                    <div class="filter-container-years">
                        <h3>Erscheinungsjahr</h3>
                        <?php
                            $years = get_terms('erscheinungsjahr');
                            $decades = [];

                            foreach ($years as $year) {
                                $decades[substr($year->slug, 0, 3) . '0'][] = $year;
                            }
                        ?>
                        <ul class="filter-decades">
                            <?php foreach ($decades as $decade => $years) : ?>
                                <li class="filter-decade">
                                    <a href="#"><?= $decade; ?>er</a>
                                    <ul class="filter-years">
                                        <?php foreach ($years as $year) : ?>
                                            <a href="<?= get_term_link($year->term_id); ?>"><li class="filter-year"><?= $year->name; ?></li></a>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="filter-container-tags">
                        <h3>Schlagwort</h3>
                        <ul class="filter-tags">
                        <?php
                            $tags = get_terms('post_tag', ['hide_empty' => true]);
                            foreach ($tags as $tag) :
                        ?>
                                <a href="<?= get_term_link($tag->term_id); ?>"><li class="filter-tag"><?= $tag->name; ?></li></a>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
        </nav>
    </header>

    <div id="main" class="main">
        <div class="main-inner">
