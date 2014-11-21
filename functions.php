<?php

    namespace Theme\Functions;

    //l18n
    add_action( 'after_setup_theme', __NAMESPACE__ . '\\l18n' );

    function l18n(){
        \load_theme_textdomain( get_path() . 'languages/' );
    }


    // Remove empty paragraph tags
    add_filter( 'the_content', __NAMESPACE__ . '\\removeEmptyParagraphs', 9999 );
    remove_filter('the_excerpt', 'wpautop'); 

    function removeEmptyParagraphs( $content ) 
    {
        $content = str_replace("<p></p>","",$content);
        return $content;
    }

    // remove <p></p> from images
    add_filter( 'the_content', __NAMESPACE__ . 'filterPtagsImages' );

    function filterPtagsImages($content) 
    {
        return preg_replace('/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '\1', $content);
    }

    // remove generators
    add_filter( 'the_generator', __NAMESPACE__ . '\\removeGenerators' );

    function removeGenerators() 
    {
        return '';
    }

    // Disable WordPress version reporting as a basic protection against attacks
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );

    // remove wp version param from any enqueued scripts
    add_filter( 'style_loader_src', __NAMESPACE__ . '\\removeWpVer', 9999 );
    add_filter( 'script_loader_src', __NAMESPACE__ . '\\removeWpVer', 9999 );

    function removeWpVer( $src ) 
    {
        if ( strpos( $src, 'ver=' ) )
            $src = \remove_query_arg( 'ver', $src );
        return $src;
    }

    // always define ajaxurl
    add_action( 'wp_head', __NAMESPACE__ . '\\ajaxurl' );

    function ajaxurl() {
        echo '<script type="text/javascript">var ajaxurl = "' . \admin_url('admin-ajax.php') . '"</script>';
    }

/**
 *	REWRITES
 */

    add_action( 'init', __NAMESPACE__ . '\\rewrites' );

    function rewrites() 
    {
    	// add_rewrite_rule( 'profile$', 'adminpage/user-profile/', 'top' );
    }

/**
 *	ENQUEUE SCRIPTS
 */

    add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\addScripts' );
    // add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\addAdminScripts' );

    function addScripts() 
    {
        \wp_register_script( 'app', namespace\Path::js() . 'app.min.js', array( 'jquery' ), '', false );

    	\wp_enqueue_script( array(
                'app',    	
        	) 
    	);

    	// \wp_localize_script( handle, name, array(

            //) 
        //);
    }

    function addAdminScripts($hook_suffix) 
    {
        \wp_register_script( '', get_path( 'js' ) . '', array( 'jquery' ), '', true );

    	if ( $hook_suffix == '' ) {
    		wp_enqueue_script( array(

    		) );
    	}
    }

/**
 *	ENQUEUE STYLES
 */

    // add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\addStyles' );

    function addStyles() 
    {
        wp_register_style( $handle, $src, $deps, $ver, $media );

        wp_enqueue_style( array( 

            )
        );
    }

/** 
 *   INTEGRATE PLUGINS IN THEME
 */

    // ACF
    // define( 'ACF_LITE', true );
    // include_once('inc/plugins/advanced-custom-fields/acf.php');
    // include_once('inc/plugins/acf-options-page/acf-options-page.php');
    // include_once( 'inc/plugins/acf-repeater/repeater.php' );

    //name the options page(s)
    // add_filter('acf/options_page/settings', __NAMESPACE__ . '\\options_page_settings');

    // function options_page_settings( $options )
    // {
    // 	$options['title'] = '';
    // 	$options['pages'] = array(

    // 		);
     
    // 	return $options;
    // }

// /**
// 	ADMIN STYLES
// ******/

    // quick admin style tweaks
    // add_action( 'admin_head', __NAMESPACE__ . '\\admin_theme' );

    // function admin_theme() {
    //    echo '<link rel="stylesheet" href="' . get_path( 'theme' ) . 'admin/styles/quick-tweaks.css">';
    // }

/**
 *	LITTLE HELPERS
 */

    function dumpit( $var, $dump = 'export', $return = false ) {
        $text = '<code><pre>';
        switch( $dump ) {
            case 'export' : 
                $text .= var_export( $var, true );
                break;
            case 'html' : 
                $text .= htmlentities( var_export( $var, true ) );
                break;
        }
        $text .= '</pre></code>';
        if ( $return ) {
            return $text;
        } else {
            echo $text;
        }
    }

    // dumpit( get_defined_vars(), 'dump' );
    // dumpit( $_POST );
    // dumpit( $_GET );
    // dumpit( $_SERVER );

    // function random_string( $length = 13 ) {
    //     return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    // }

    // function redirect_after_headers( $url ) {
    //     $string = '<script type="text/javascript">';
    //     $string .= 'window.location = "' . $url . '"';
    //     $string .= '</script>';

    //     echo $string;
    // }	

/*
 * these work pretty good in the setup in which they were built and pretty shitty in every other setup
 * have to be heavily customized to get it working probably
 * very ungeneric
 */

    // CREATE ASSET FOLDERS OUTSIDE OF ACTUAL THEME
    function get_template_directory_uri() {
    	$wpurl = trailingslashit( get_bloginfo( 'wpurl' ) );
    	return $wpurl;
    }

    // echo the url
    function path( $asset = '' ) {
    	$wpurl = \get_template_directory_uri();
    	switch ( $asset ) {
    		case 'images':
    			echo trailingslashit( $wpurl . 'assets/' . 'images' );
    			break;
    		case 'img':
    			echo trailingslashit( $wpurl . 'assets/' . 'images' );
    			break;
    		case 'css':
    			echo trailingslashit( $wpurl . 'assets/' . 'styles' );
    			break;
    		case 'styles':
    			echo trailingslashit( $wpurl . 'assets/' . 'styles' );
    			break;
    		case 'js':
    			echo trailingslashit( $wpurl . 'assets/' . 'js' );
    			break;
    		case 'theme':
    			echo trailingslashit( $wpurl . 'assets/themes/' . __NAMESPACE__ );
    			break;
    		case '' :
    			echo trailingslashit( $wpurl . 'assets/' );
    			break;
    	}
    }

    //return the url
    function get_path( $asset = '' ) {
    	$wpurl = get_template_directory_uri();
    	switch ( $asset ) {
    		case 'images':
    			return trailingslashit( $wpurl . 'assets/' . 'images' );
    			break;
    		case 'img':
    			return trailingslashit( $wpurl . 'assets/' . 'images' );
    			break;
    		case 'css':
    			return trailingslashit( $wpurl . 'assets/' . 'styles' );
    			break;
    		case 'styles':
    			return trailingslashit( $wpurl . 'assets/' . 'styles' );
    			break;
    		case 'js':
    			return trailingslashit( $wpurl . 'assets/' . 'js' );
    			break;
    		case 'theme':
    			return trailingslashit( $wpurl . 'assets/themes/' . __NAMESPACE__ );
    			break;	
    		case '' :
    			return trailingslashit( $wpurl . 'assets/' );
    			break;
    	}
    }

    // function get_wp_load_path() {
    // 	$path = dirname( __FILE__ );
    // 	while ( !@file_exists( $path . '/wp-load.php' )) {
    // 		if ( $path == dirname( $path ) ) {
    // 			$path = false;
    // 			break;
    // 		}
    // 		$path = dirname( $path );
    // 	};
    // 	if ( $path !== false ) {
    // 		$path = str_replace( '\\', '/', $path );
    // 	}
    //     return $path;
    // }


    // function prefill_form_field( $value ) {
    // 	if( isset( $_REQUEST[ $value ] ) ) {
    // 		echo $_REQUEST[ $value ];
    // 	}
    // }

    // function prefill_form_select( $select, $value ) {
    // 	if( isset( $_REQUEST[ $select ] ) && $_REQUEST[ $select ] == $value ) {
    // 		echo 'selected';
    // 	}
    // }

    // function form_errors( $field ) {
    // 	global $errors;
    // 	if( isset( $errors[ $field ] ) ) {
    // 		echo $errors[ $field ];
    // 	} else {
    // 		echo '';
    // 	}
    // }

    // // add_action('wp_head', __NAMESPACE__ . '\\showTemplate');
    // function showTemplate( $return = false ) {
    //     global $template;
    //     switch ( $return ) {
    //     	case false:
    //     		print_r( $template );
    //     		break;
    //     	case true :
    //     		return( $template );
    //     		break;
    //     }
    // }

    // highlight search results
    // add_filter('the_excerpt', __NAMESPACE__ . '\\highlightSearchTerm');
    // add_filter('the_title', __NAMESPACE__ . '\\highlightSearchTerm');

    // function highlightSearchTerm( $text ){
    //     if( is_search() ){
    //      $keys = implode( '|', explode( ' ', get_search_query() ) );
    //      $text = preg_replace( '/(' . $keys .')/iu', '<span class="search-term">\0</span>', $text );
    //  }
    //     return $text;
    // }

    //customize post excerpt
    // add_filter( 'excerpt_length', __NAMESPACE__ . '\\customExcerptLength', 999 );
    // function customExcerptLength( $length ) {
    //  return 20;
    // }

    // add_filter('excerpt_more', __NAMESPACE__ . '\\newExcerptMore');
    // function newExcerptMore( $more ) {
    //  return ' <a href="' . get_permalink() . '">' . __( 'more...', __NAMESPACE__ ) . '</a>';
    // }


    // /**
    //  * Inserts an array of strings into a file (.htaccess ), placing it between
    //  * BEGIN and END markers. Replaces existing marked info. Retains surrounding
    //  * data. Creates file if none exists.
    //  *
    //  * @param array|string $insertion
    //  * @return bool True on write success, false on failure.
    //  */

    // add_action( 'admin_init', __NAMESPACE__ . '\\addHtaccess' );

    // function addHtaccess($insertion)
    // {
    //     $htaccess_file = ABSPATH.'.htaccess';
    //     $insertion = array(
    //     	'AddType application/vnd.ms-fontobject .eot',
    // 		'AddType font/ttf .ttf',
    // 		'AddType font/otf .otf',
    // 		'AddType application/font-woff .woff',
    // 		'AddType application/x-font-woff .woff'
    //     	);
    //     return insert_with_markers($htaccess_file, 'Font-MIME-Type', $insertion);
    // }

/**
 * Add More Functions
 */

    require_once 'functions/path.php';
    require_once 'functions/clean-wp-functions.php';
