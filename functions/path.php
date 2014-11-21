<?php

    namespace Theme\Functions;

    class Path 
    {

        private static function urlWithoutProtocol($url) {
            $urlArr = \parse_url($url);
            return '//' . $urlArr['host'] . $urlArr['path'];
        }

        static function parentTheme()
        {
            return self::urlWithoutProtocol(\trailingslashit(\get_template_directory_uri()));
        }

        static function theme() 
        {
            return self::urlWithoutProtocol(\trailingslashit(\get_stylesheet_directory_uri()));
        }

        static function assets() 
        {
            if (!defined('\WP_CONTENT_FOLDERNAME')) {
                return 'wp_content';
            } else {
                return \constant('\WP_CONTENT_FOLDERNAME');
            }
        }

        static function css()
        {
            if (self::assets() === 'wp_content') {
                return \trailingslashit(self::theme() . 'css/');
            } else {
                return self::urlWithoutProtocol(\constant('\WP_CONTENT_URL') . '/css/');
            }
        }

        static function js()
        {
            if (self::assets() === 'wp_content') {
                return \trailingslashit(self::theme() . 'js');
            } else {
                return self::urlWithoutProtocol(\constant('\WP_CONTENT_URL') . '/js/');
            }
        }

        static function images()
        {
            if (self::assets() === 'wp_content') {
                return \trailingslashit(self::theme() . 'images');
            } else {
                return self::urlWithoutProtocol(\constant('\WP_CONTENT_URL') . '/img/');
            }
        }
    }
