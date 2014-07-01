<?php

//l18n
add_action('after_setup_theme', 'pi_l18n');
function pi_l18n(){
    load_theme_textdomain( 'pi', pi_get_path() . 'languages/themes/' );
}

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

// Remove empty paragraph tags
function removeEmptyParagraphs( $content ) {
    $content = str_replace("<p></p>","",$content);
    return $content;
}
add_filter( 'the_content', 'removeEmptyParagraphs', 9999 );
remove_filter('the_excerpt', 'wpautop'); 

// remove <p></p> from images
function filter_ptags_on_images($content) {
    return preg_replace('/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '\1', $content);
}
add_filter('the_content', 'filter_ptags_on_images');

// Disable WordPress version reporting as a basic protection against attacks
function remove_generators() {
	return '';
}		

add_filter('the_generator','remove_generators');

add_filter('the_generator', create_function('', 'return "";'));
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

// remove wp version param from any enqueued scripts
function vc_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );


/**
	REWRITES
******/

add_action( 'init', 'pi_rewrites' );

function pi_rewrites() {
	// add_rewrite_rule( 'profile$', 'adminpage/user-profile/', 'top' );
}

/**
	ENQUEUE SCRIPTS
******/
add_action( 'wp_enqueue_scripts', 'pi_add_scripts' );
add_action( 'admin_enqueue_scripts', 'pi_add_admin_scripts' );

function pi_add_scripts() {
	global $post;
	wp_register_script( 'modernizr', pi_get_path( 'js') . 'modernizr.js', false, '', false );
	wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://code.jquery.com/jquery-latest.min.js' );
    wp_register_script( 'jquery_cookie', pi_get_path( 'js' ) . 'jquery.cookie.js', array( 'jquery' ), '', true );
    wp_register_script( 'pi_header', pi_get_path( 'js' ) . 'pi-header.js', array( 'jquery', 'hammer', 'jquery_hammer', 'smartresize' ), '0.1', true );
    wp_register_script( 'hammer', pi_get_path( 'js' ) . 'hammer/dist/hammer.min.js', false, '', true );
    wp_register_script( 'jquery_hammer', pi_get_path( 'js' ) . 'hammer/dist/jquery.hammer.min.js', array( 'jquery', 'hammer' ), '', true );
    wp_register_script( 'pi_comments', pi_get_path( 'js' ) . 'pi-comments.js', array( 'jquery' ), '', true );
    wp_register_script( 'pi_polls', pi_get_path( 'js' ) . 'pi-polls.js', array( 'jquery', 'smartresize' ), '', true );
    wp_register_script( 'pi_taxonomy_search', pi_get_path( 'js' ) . 'pi-taxonomy-search.js', false, '', true );
    wp_register_script( 'pi_substance_search', pi_get_path( 'js' ) . 'pi-substance-search.js', false, '', true );
    wp_register_script( 'pi_index_search', pi_get_path( 'js' ) . 'pi-index-search.js', false, '', true );
    wp_register_script( 'scrollspy', pi_get_path( 'js' ) . 'scrollspy.js', array( 'jquery' ), '', true );
    wp_register_script( 'smartresize', pi_get_path( 'js' ) . 'smartresize.js', array( 'jquery' ), '0.1', true );
    wp_register_script( 'pi-scrollspy', pi_get_path( 'js' ) . 'pi-scrollspy.js', array( 'jquery', 'scrollspy', 'smartresize' ), '0.1', true );
    wp_register_script( 'pi-dashboard', pi_get_path( 'js' ) . 'pi-dashboard.js', array( 'jquery', 'smartresize' ), '0.1', true );
    wp_register_script( 'pi_delete_article', pi_get_path( 'js' ) . 'pi-delete-article.js', array( 'jquery' ), '', true );
    wp_register_script( 'pi_smooth', pi_get_path( 'js' ) . 'pi-smooth-scroll.js', array( 'jquery' ), '', true );
    wp_register_script( 'pi_search', pi_get_path( 'js' ) . 'pi-search.js', array( 'jquery', 'jquery_hammer' ), '', true );
    wp_register_script( 'pi_submissions', pi_get_path( 'js' ) . 'pi-submissions.js', array( 'jquery' ), '', true );
    wp_register_script( 'pi_article_translation', pi_get_path( 'js' ) . 'pi-article-translation.js', array( 'jquery' ), '', true );
    wp_register_script( 'pi_js_throttle', pi_get_path( 'js' ) . 'jquery.ba-throttle-debounce.min.js', array( 'jquery' ), '', true );
    wp_register_script( 'pi_mediaqueries', pi_get_path( 'js' ) . 'css3-mediaqueries.js', array( 'jquery'), '0.1', true );

	wp_enqueue_script( array(
			'jquery',
			'jquery_cookie',
			'modernizr',
			'pi_header',
			'hammer',
			'jquery_hammer',
			'scrollspy',
			'pi-scrollspy',
			'smartresize',
			'pi_delete_article',
			'pi_smooth', 
			'pi_search', 
			'pi_js_throttle',
		) 
	);

	wp_localize_script( 'pi_comments', 'pi_comments_localization', array(
		/*translators: The commment reply title (heading)*/
		'reply_title'  => _x( 'Reply', 'comment reply title (heading)', 'pi' ),
		/*translators: The text for the reply button*/
		'reply_button' => _x( 'Reply', 'reply button text', 'pi' ),
		'oldest_first' => __( 'Oldest on top', 'pi' ),
		'newest_first' => __( 'Newest on top', 'pi' ),
		'submit_button' => __( 'Submit', 'pi' ),
		'cancel_button' => __( 'Cancel reply', 'pi'),
		/*translators: Needed for script execution, don't change this*/
		'siteurl'		=> parse_url( get_option( 'siteurl' ), PHP_URL_PATH ) . '/'
	) );

	wp_localize_script( 'pi_delete_article', 'pi_delete_article_localization', array(
		'confirmation_text'  => __( 'Do you really want to delete your article?', 'pi' ),
		'article_deleted'  => __( 'Article deleted', 'pi' ),
		/*translators: Needed for script execution, don't change this*/
		'redirect_url' => pi_get_adminpage( 'submissions' )
	) );

	wp_localize_script( 'pi_submissions', 'pi_submissions_localization', array(
		'all_submissions' => __( 'All Submissions', 'pi' ), 
		'my_submissions'  => __( 'My Submissions', 'pi' ),
		'evaluations'     => __( 'Evaluations', 'pi' ),
		'news'            => __( 'News', 'pi' ),
		'standards'       => __( 'Standards', 'pi' ),
		'payerarticle'    => __( 'Payerarticles', 'pi' ),
		'url'             => pi_get_adminpage( 'submissions' ),
		'user_id'         => get_current_user_id()
	) );

	wp_localize_script( 'pi-dashboard', 'pi_dashboard_localization', array(
		'newest'    => __( 'Newest Articles', 'pi' ),
		'payers'    => '',
		'industry'  => __( 'Sponsors', 'pi' ),
		'url'       => pi_get_adminpage( 'dashboard' ),
		'user_id'   => get_current_user_id()
	) );

	wp_localize_script( 'pi_article_translation', 'pi_article_translation_localization', array(
		'post_id'	=> ( is_object( $post ) ? get_the_ID() : 0 )
	) );

	//conditionally show javascripts on different pages
	$posttype = get_post_type();

	//js for userprofile page
	if( pi_is_page_template( 'page-userprofile.php' ) ) {
		wp_enqueue_script( array(
			'password-strength-meter',
			'parser_rules',
		) );
	}
	//the old frontendeditor page (let's keep it in here until we are really sure that we don't need it anymore)
	if( pi_is_page_template( 'page-frontendeditor-old.php' ) ) {
		wp_enqueue_script( array(
			'parser_rules',
			'pi_substance_search'
		) );	
	}

	//the frontendeditor page
	if( pi_is_page_template( 'page-frontendeditor.php' ) ) {
		wp_enqueue_script( array(
			'pi_substance_search'
		) );	
	}
	//dashboard js only on dashboard
	if( pi_is_page_template( 'page-dashboard.php' ) ) {
		wp_enqueue_script( array(
			'pi-dashboard'
		) );	
	}
	//taxonomy search and results ajax only needed on tax search
	if( pi_is_page_template( 'page-indexsearch.php' ) ) {
		wp_enqueue_script( array(
			'pi_taxonomy_search',
			'pi_index_search',
		) );
	}
	//search results ajax only needed on the indexlist
	if( pi_is_page_template( 'page-indexlist.php' ) ) {
		wp_enqueue_script( array(
			'pi_index_search'
		) );
	}
	//polls for some posttypes
	if( in_array( $posttype, array( 'post', 'evaluation', 'news', 'standard' ) ) ) {
		wp_enqueue_script( array(
			'pi_polls'
		) );	
	}
	//localization for some posttypes
	if( in_array( $posttype, array ( 'evaluation', 'post', 'page', 'faq', 'announcement' ) ) ) {
		wp_enqueue_script( array(
			'pi_article_translation'
		) );	
	}
	//comments only needed on certain single post types
	if( in_array( $posttype, array( 'post', 'evaluation', 'memberarticle', 'industryarticle' ) ) ) {
		wp_enqueue_script( array(
			'pi_comments'
		) );
	}

	//submissions ajax only needed on submissions page
	if( pi_is_page_template( 'page-submissions.php' ) ) {
		wp_enqueue_script( array(
			'pi_submissions'
		) );	
	}
}

function pi_add_admin_scripts($hook_suffix) {
    wp_register_script( 'pi_blocklist', pi_get_path( 'js' ) . 'pi-blocklist.js', array( 'jquery' ), '', true );

	$user = wp_get_current_user();
	wp_localize_script( 'pi_blocklist', 'pi_blocklist_localization', array(
		'add_user'  => __( 'Add User', 'pi' ),
		'add_domain'  => __( 'Add Domain', 'pi' ),
		/*translators: Needed for script execution, don't change this*/
		'is_admin' => $user->has_cap('gold_level')
	) );
	
	if ( $hook_suffix == 'more-options_page_acf-options-email-blocklist' ) {
		wp_enqueue_script( array(
			'pi_blocklist'
		) );
	}
}

/**
	ENQUEUE STYLES
******/


/** 
    INTEGRATE PLUGINS IN THEME
******/

// ACF
// define( 'ACF_LITE', true );
// include_once('inc/plugins/advanced-custom-fields/acf.php');
// include_once('inc/plugins/acf-options-page/acf-options-page.php');

// add_action('acf/register_fields', 'my_register_fields');

// function my_register_fields()
// {
//     include_once('inc/plugins/acf-repeater/repeater.php');
// }
// include_once('inc/functions/acf.php');

//name the options page(s)
add_filter('acf/options_page/settings', 'pi_options_page_settings');

function pi_options_page_settings( $options )
{
	$options['title'] = 'More Options';
	$options['pages'] = array(
		'Email Whitelist',
		'Email Blocklist',
		'Divisions', 
		'New User Notification'
		);
 
	return $options;
}

// save user language to database and redirect on language change
add_action( 'init', 'pi_save_user_language_and_redirect' );
function pi_save_user_language_and_redirect() {
	if ( isset( $_REQUEST['lang'] ) ) {
		if ( is_user_logged_in() ) {
			update_user_meta( get_current_user_id(), 'pi_language', $_REQUEST['lang'] );
			if ( !is_admin() ) {
				// i don't know why this doesn't contain lang like request does
				pi_redirect_after_headers( $_SERVER["REQUEST_URI"] );
			}
		}
	}
}

// always define ajaxurl
add_action( 'wp_head','pi_ajaxurl' );
function pi_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}

//Load the divisions in the whitelist screen
add_filter( 'acf/load_field/name=pi_whitelist_division', 'pi_load_divisions_field' );
add_filter( 'acf/load_field/name=pi_whitelist_filter', 'pi_load_divisions_field' );

function pi_load_divisions_field( $field ) {
	// reset choices
	$field[ 'choices' ] = array();
 
	// load repeater from the options page
	if( get_field( 'pi_division', 'option' ) ) {
		while( has_sub_field( 'pi_division', 'option' ) ) {
			$value = get_sub_field( 'pi_division_slug' );
			$label = get_sub_field( 'pi_division_nicename' );

			$field[ 'choices' ][ $value ] = $label;
		}
	}

    // Important: return the field
    return $field;
}

//make the email whitelist filterable by division
add_action( 'acf/input/admin_head', 'pi_filter_divisions' );

function pi_filter_divisions() {
	?>
	<script type="text/javascript">
		jQuery( "document" ).ready( function($){
			$( "#acf-field-pi_whitelist_filter" ).change( function(){
				var filter_value = $( "#acf-field-pi_whitelist_filter" ).val();
				$( "#acf-pi_whitelist tbody tr" ).each( function(){
					if( filter_value === "null" ) {
						if( ! $( this ).hasClass( "row-clone" ) ) {
							$( this ).show();
						}
					} else {
						if( ! $( this ).hasClass( "row-clone" ) ) {
							$( this ).show();
						}
						if( $( "td.field_type-select > select", this ).val() !== filter_value ) {
							$( this ).hide();
						}
					}
				});
			});
		});
	</script>
	<?php
}



/**
	INCLUDE OTHER FUNCTIONS 
******/

//register the post types and caps
include( 'admin/functions/cpt-capabilities.php' );

//add and edit userroles
include( 'admin/functions/roles.php' );

//build the acl
include( 'admin/functions/acl.php' );

//include user handling
include( 'admin/functions/user-handling.php' );

//include dashboard/sidebar functions
include( 'admin/functions/dashboard.php' ); 

//comment functions
include( 'admin/functions/comments.php' );

//email registrations/temp database
include( 'admin/functions/email-blocklist.php' );

//user polls on evaluations
include( 'admin/functions/polls.php' );
include( 'admin/functions/statistics.php' );

//indexing of the page/active substances
include( 'admin/functions/taxonomy-search.php' );

//include fulltext search ajax
include( 'admin/functions/fulltext-search.php' );

//markup cleaning for wysiwyg editor
include( 'admin/functions/markup-cleanup.php' );

//frontendeditor based on acf
include( 'admin/functions/frontendeditor.php' );

//index of the substances
include( 'admin/functions/index-search.php' );

//delete article with ajax
include( 'admin/functions/delete-article.php' );

//ajaxify the submissions page/filters
include( 'admin/functions/submissions.php' );

//translation for news and evaluation
include( 'admin/functions/article-translation.php' );

/**
	ADMIN STYLES
******/


// quick admin style tweaks
add_action( 'admin_head', 'pi_admin_theme' );

function pi_admin_theme() {
   echo '<link rel="stylesheet" href="' . pi_get_path( 'theme' ) . 'admin/styles/quick-tweaks.css">';
}

include_once( 'admin/functions/login-form.php' );

/**
	ROLES & CAPBILITIES
******/

// Remove Super Admin and/or Administrator from "Editable Roles", if current user is not itself in these groups
add_action( 'editable_roles' , 'pi_hide_super_admin_editable_roles' );

function pi_hide_super_admin_editable_roles( $roles ) {
	$user      = wp_get_current_user();
	$user_role = $user->roles;

	if( ! in_array( 'super_admin', $user_role ) ) {
		if ( isset( $roles['super_admin'] ) ) {
			unset( $roles['super_admin'] );
		}
		if( ! in_array( 'administrator', $user_role ) ) {
			if ( isset( $roles['administrator'] ) ) {
				unset( $roles['administrator'] );
			}
		}
	}

	return $roles;
}

//don't show super_admins and administrators to anyone else
add_action('pre_user_query','pi_pre_user_query');

function pi_pre_user_query( $user_search ) {
	$user = wp_get_current_user();

	if( ! current_user_can( 'gold_level' ) ) {
		global $wpdb;
		
		$user_search->query_where = 
        	str_replace('WHERE 1=1', 
            	"WHERE 1=1 AND {$wpdb->users}.ID IN (
                	SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
                    WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities' 
                    AND {$wpdb->usermeta}.meta_value NOT LIKE '%super_admin%')", 
            	$user_search->query_where
        );
	}
	if( ! current_user_can( 'silver_level' ) ) {
		global $wpdb;
		
		$user_search->query_where = 
        	str_replace('WHERE 1=1', 
            	"WHERE 1=1 AND {$wpdb->users}.ID IN (
                	SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
                    WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities' 
                    AND ({$wpdb->usermeta}.meta_value NOT LIKE '%super_admin%') AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')", 
            	$user_search->query_where
        );
	}
}

// disallow ajax for everyone who has not completed registration process
add_action( 'admin_init', 'pi_disallow_ajax', 1 );

function pi_disallow_ajax() {
	if( $_SERVER['PHP_SELF'] === parse_url( site_url( 'wp-admin/admin-ajax.php' ), PHP_URL_PATH ) ) {
		if ( ! ( is_user_logged_in() &&  get_user_meta( get_current_user_id(), 'user_status', true ) == 'online' ) ) {
			die();
		}
	}
}

// don't show wp-admin to normal users
add_action( 'admin_init', 'pi_dontshow_admin', 1 );

function pi_dontshow_admin() {
	if( ! current_user_can( 'work_in_admin' ) && $_SERVER['PHP_SELF'] != parse_url( site_url( 'wp-admin/admin-ajax.php' ), PHP_URL_PATH ) ) {
		wp_redirect( pi_get_adminpage( 'dashboard') );
	}
}

//in case wordpress' cap check is first
add_action( 'admin_page_access_denied', 'pi_dontshow_admin_denied', 1 );
 
function pi_dontshow_admin_denied() {
    echo '<meta http-equiv="refresh" content="0;' . site_url() . '/adminpage/user-profile">';
}

// highlight search results
add_filter('the_excerpt', 'pi_highlight_search_term');
add_filter('the_title', 'pi_highlight_search_term');

function pi_highlight_search_term( $text ){
    if( is_search() ){
		$keys = implode( '|', explode( ' ', get_search_query() ) );
		$text = preg_replace( '/(' . $keys .')/iu', '<span class="search-term">\0</span>', $text );
	}
    return $text;
}

//customize post excerpt
function pi_custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'pi_custom_excerpt_length', 999 );

function pi_new_excerpt_more( $more ) {
	return ' <a href="' . get_permalink() . '">' . __( 'mehr...', 'pi' ) . '</a>';
}
add_filter('excerpt_more', 'pi_new_excerpt_more');

/**
	SWEET LITTLE HELPERS
******/

// dumpit( get_defined_vars(), 'dump' );
// dumpit( $_POST );
// dumpit( $_GET );
// dumpit( $_SERVER );

function pi_random_string( $length = 13 ) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

function pi_redirect_after_headers( $url ) {
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';

    echo $string;
}	


// CREATE ASSET FOLDERS OUTSIDE OF ACTUAL THEME
function pi_get_template_directory_uri() {
	$wpurl = trailingslashit( get_bloginfo( 'wpurl' ) );
	return $wpurl;
}

// echo the url
function pi_path( $asset = '' ) {
	$wpurl = pi_get_template_directory_uri();
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
			echo trailingslashit( $wpurl . 'assets/themes/pi/' );
			break;
		case '' :
			echo trailingslashit( $wpurl . 'assets/' );
			break;
	}
}

//return the url
function pi_get_path( $asset = '' ) {
	$wpurl = pi_get_template_directory_uri();
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
			return trailingslashit( $wpurl . 'assets/themes/pi/' );
			break;	
		case '' :
			return trailingslashit( $wpurl . 'assets/' );
			break;
	}
}

function pi_get_wp_load_path() {
	$path = dirname( __FILE__ );
	while ( !@file_exists( $path . '/wp-load.php' )) {
		if ( $path == dirname( $path ) ) {
			$path = false;
			break;
		}
		$path = dirname( $path );
	};
	if ( $path !== false ) {
		$path = str_replace( '\\', '/', $path );
	}
    return $path;
}

//taken from http://jobyj.in/how-to/php-function-to-delete-rows-from-a-csv-file/

function csv_delete_rows($filename=NULL, $startrow=0, $endrow=0, $inner=true) {
    $status = 0;
    //check if file exists
    if (file_exists($filename)) {
        //end execution for invalid startrow or endrow
        if ($startrow < 0 || $endrow < 0 || $startrow > 0 && $endrow > 0 && $startrow > $endrow) {
            die('Invalid startrow or endrow value');
        }
        $updatedcsv = array();
        $count = 0;
        //open file to read contents
        $fp = fopen($filename, "r");
        //loop to read through csv contents
        while ($csvcontents = fgetcsv($fp)) {
            $count++;
            if ($startrow > 0 && $endrow > 0) {
                //delete rows inside startrow and endrow
                if ($inner) {
                    $status = 1;
                    if ($count >= $startrow && $count <= $endrow)
                        continue;
                    array_push($updatedcsv, implode(',', $csvcontents));
                }
                //delete rows outside startrow and endrow
                else {
                    $status = 2;
                    if ($count < $startrow || $count > $endrow)
                        continue;
                    array_push($updatedcsv, implode(',', $csvcontents));
                }
            }
            else if ($startrow == 0 && $endrow > 0) {
                $status = 3;
                if ($count <= $endrow)
                    continue;
                array_push($updatedcsv, implode(',', $csvcontents));
            }
            else if ($endrow == 0 && $startrow > 0) {
                $status = 4;
                if ($count >= $startrow)
                    continue;
                array_push($updatedcsv, implode(',', $csvcontents));
            }
            else if ($startrow == 0 && $endrow == 0) {
                $status = 5;
            } else {
                $status = 6;
            }
        }//end while
        if ($status < 5) {
            $finalcsvfile = implode("\n", $updatedcsv);
            fclose($fp);
            $fp = fopen($filename, "w");
            fwrite($fp, $finalcsvfile);
        }
        fclose($fp);
        return $status;
    } else {
        die('File does not exist');
    }
}

function pi_prefill_form_field( $value ) {
	if( isset( $_REQUEST[ $value ] ) ) {
		echo $_REQUEST[ $value ];
	}
}

function pi_prefill_form_select( $select, $value ) {
	if( isset( $_REQUEST[ $select ] ) && $_REQUEST[ $select ] == $value ) {
		echo 'selected';
	}
}

function pi_form_errors( $field ) {
	global $errors;
	if( isset( $errors[ $field ] ) ) {
		echo $errors[ $field ];
	} else {
		echo '';
	}
}

// add_action('wp_head', 'show_template');
function show_template( $return = false ) {
    global $template;
    switch ( $return ) {
    	case false:
    		print_r( $template );
    		break;
    	case true :
    		return( $template );
    		break;
    }
}

function pi_post_type() {
	$template = show_template( true );

	$script = pathinfo($template);
	$script = $script['filename'];

	$post_type = get_post_type();

	if( $post_type == $script ) {
		echo $post_type;
	} else {
		echo $post_type . ' ' . $script;
	}
}

// is_page_template function for custom post types
function pi_is_page_template( $filename ) {
	global $wp_query;
	$template_name = get_post_meta( @$wp_query->post->ID, '_wp_page_template', true );

	if( $template_name == $filename ) {
		return true;
	} else {
		return false;
	}
}

// get the adminpage url

function pi_get_adminpage( $page, $dump = '' ) {
	switch ( $page ) {
		case 'profile' :
			$adminpages = get_posts( array(
				'post_type'  => 'adminpage',
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-userprofile.php'
			));
			break;
		case 'compose' :
			$adminpages = get_posts( array(
				'post_type'  => 'adminpage',
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-frontendeditor.php'
			));
			break;
		case 'dashboard' :
			$adminpages = get_posts( array(
				'post_type'  => 'adminpage',
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-dashboard.php'
			));
			break;
		case 'submissions' :
			$adminpages = get_posts( array(
				'post_type'  => 'adminpage',
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-submissions.php'
			));
			break;
		case 'all-submissions' :
			$adminpages = get_posts( array(
				'post_type'  => 'adminpage',
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-allarticles.php'
			));
			break;
		case 'advisory' :
			$adminpages = get_posts( array(
				'post_type'  => 'adminpage',
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-advisory.php'
			));
			break;			
		case 'indexsearch' :
			$adminpages = get_posts( array(
				'post_type'  => 'adminpage',
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-indexsearch.php'
			));
			break;
		case 'indexlist' :
			$adminpages = get_posts( array(
				'post_type'  => 'adminpage',
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-indexlist.php'
			));
			break;
		case 'search' :
			$adminpages = get_posts( array(
				'post_type'  => 'adminpage',
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-search.php'
			));
			break;
	}

	if( $dump != '' ) {
		return $adminpages[0]->ID;
	} else {
		//skip the foreach loop, we only want one page with that template. If two pages should exist, only take the first (' . $adminpages[0]->post_type . ')
		return get_permalink( $adminpages[0]->ID );
		// echo '<code><pre>';
		// 	var_dump($adminpages[0]->ID);
		// echo '</pre></code>';
	}
}

// hide update (and other) nags
add_action('admin_menu','wphidenag');
function wphidenag() {
	remove_action( 'admin_notices', 'update_nag', 3 );
}

// check the current post for the existence of a short code
function pi_has_shortcode( $shortcode = NULL ) {

    $post_to_check = get_post( get_the_ID() );

    // false because we have to search through the post content first
    $found = false;

    // if no short code was provided, return false
    if ( ! $shortcode ) {
        return $found;
    }
    // check the post content for the short code
    if ( stripos( $post_to_check->post_content, '[' . $shortcode) !== FALSE ) {
        // we have found the short code
        $found = TRUE;
    }

    // return our final results
    return $found;
}

/**
 * Inserts an array of strings into a file (.htaccess ), placing it between
 * BEGIN and END markers. Replaces existing marked info. Retains surrounding
 * data. Creates file if none exists.
 *
 * @param array|string $insertion
 * @return bool True on write success, false on failure.
 */

add_action( 'admin_init', 'add_htaccess' );

function add_htaccess($insertion)
{
    $htaccess_file = ABSPATH.'.htaccess';
    $insertion = array(
    	'AddType application/vnd.ms-fontobject .eot',
		'AddType font/ttf .ttf',
		'AddType font/otf .otf',
		'AddType application/font-woff .woff',
		'AddType application/x-font-woff .woff'
    	);
    return insert_with_markers($htaccess_file, 'Font-MIME-Type', $insertion);
}

//add csv data to repeater field, not a regular task
// --> therefore no interface whatsoever
// add_action( 'admin_init', 'pi_add_krankenkassen' ); 

function pi_add_krankenkassen() {

	$kdomain_data = csv_to_array( get_theme_root() . '/pi/kdomains.csv' );

	/***
		dry run it before you actually use it... check the options with SELECT * FROM payers_insight.payersdev_options where option_name LIKE "%pi_whitelist%";
		don't overwrite existing data
	**/

	//only do this if I am the one logged in
	$user_id = get_current_user_id();
	if ($user_id !== 62) 
		die();

	// echo '<pre>';
	// 	var_export( $kdomain_data );
	// echo '</pre>';

	//get current number of options
	$options = get_option( 'options_pi_whitelist' );

	//save this as the offset (in order not to overwrite existing entries)
	$offset  = intval($options);

	//loop through the array
	for( $i = 0; $i < ( count($kdomain_data) ); $i++ ) {
		$index = $i + $offset;
		//add to the option count
		$options++;
		
		//add options
		update_option( "options_pi_whitelist_{$index}_pi_whitelist_domain", $kdomain_data[$i]['kdomain'], '', 'yes' );
		update_option( "_options_pi_whitelist_{$index}_pi_whitelist_domain", 'field_520d1edbcd138', '', 'yes' );
		update_option( "options_pi_whitelist_{$index}_pi_whitelist_organization", $kdomain_data[$i]['kname'], '', 'yes' );
		update_option( "_options_pi_whitelist_{$index}_pi_whitelist_organization", 'field_520d2701d5298', '', 'yes' );
		update_option( "options_pi_whitelist_{$index}_pi_whitelist_division", $kdomain_data[$i]['kdivision'], '', 'yes' );
		update_option( "_options_pi_whitelist_{$index}_pi_whitelist_division", 'field_520d1f07cd139', '', 'yes' );
	}

	//update the options count in the db
	update_option( 'options_pi_whitelist', $options );
}

function csv_to_array($filename='', $delimiter=',')
{
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;
	
	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
		{
			if(!$header)
				$header = $row;
			else
				$data[] = array_combine($header, $row);
		}
		fclose($handle);
	}
	return $data;
}


