<?php 

/**
 * The loads all theme functions
 *
 * The theme's various functions are loaded
 * here or in one of the attached files
 *
 * @package WordPress
 * @subpackage Simple
 * @since 1.0.0
 *
 *
 */

// Define Version
	define( 'SIMPLE_VERSION', '1.0.5' );

// options framework
	if ( !function_exists( 'optionsframework_init' ) ) {
		define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
		require_once dirname( __FILE__ ) . '/inc/options-framework.php';
	}
	
	// loads options file directly as required by theme customizer
		require_once dirname( __FILE__ ) . '/includes/options.php';



// Other Theme Functions

	if ( ! function_exists( 'simple_setup' ) ):

		function simple_setup() {
				
			// For localization
				load_theme_textdomain( 'simple', get_template_directory().'/languages' );
			
			// Add default posts and comments RSS feed links to head
				add_theme_support( 'automatic-feed-links' );
			
			// This theme uses wp_nav_menu() in two locations.
				register_nav_menus( array(
					'primary' => __( 'Header Navigation', 'simple' ),
				) );
			
			// Featured Images
				add_theme_support( 'post-thumbnails' );
			
			// Title tag support
				add_theme_support( 'title-tag' );
			
			// Formats	
				add_theme_support( 'post-formats', array( 'image', 'video' ) );
				add_post_type_support( 'page', 'post-formats' );
			
			// Add custom background support
				add_theme_support( 'custom-background', array(
					'default-color' => 'd6d6d6',
				) );
			
			// Add HTML5 markup for comments
				add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
			
			// Add support for Jetpack infinite scroll
				add_theme_support( 'infinite-scroll', array(
				    'container'    => 'content',
				    'footer'  => false
				) );
			
			// Define Theme Content Width
				if ( ! isset( $content_width ) ) $content_width = 750;
			
		}
	
	endif; // simple_setup
	
	add_action( 'after_setup_theme', 'simple_setup' );


// Include Required Files	
	if ( ! function_exists( 'simple_required_files' ) ):

		function simple_required_files() {

		// include required files
			include(get_template_directory()."/includes/fonts.php");
			include(get_template_directory()."/includes/scripts.php");
			include(get_template_directory()."/includes/body-tag.php");
			include(get_template_directory()."/includes/custom-js.php");
			include(get_template_directory()."/includes/custom-css.php");
			include(get_template_directory()."/includes/author-fields.php");
			include(get_template_directory()."/includes/theme-customizer.php");
			include(get_template_directory()."/includes/comment-functions.php");
			
			
		// include widgets
			include(get_template_directory()."/includes/widgets.php");
	
		}
	
	endif; // simple_required_files
	
	add_action( 'after_setup_theme', 'simple_required_files' );


// Get First Video Function from Elegant Themes
	if ( ! function_exists( 'organizedthemes_get_first_video' ) ) :
	
		function organizedthemes_get_first_video() {
			$first_oembed  = '';
			$custom_fields = get_post_custom();
		
			foreach ( $custom_fields as $key => $custom_field ) {
				if ( 0 !== strpos( $key, '_oembed_' ) ) continue;
		
				$first_oembed = $custom_field[0];
				
				$first_oembed = preg_replace( '/<embed /', '<embed wmode="transparent" ', $first_oembed );
				$first_oembed = preg_replace( '/<\/object>/','<param name="wmode" value="transparent" /></object>', $first_oembed );
		
				break;
			}
		
			return ( '' !== $first_oembed ) ? $first_oembed : false;
		
		}
		
	endif; // organizedthemes_get_first_video
	
	
	
// Wrap post videos in responsive div
	if ( ! function_exists( 'organizedthemes_fit_video_wrap' ) ) :
		
		add_filter('embed_oembed_html', 'organizedthemes_fit_video_wrap', 99, 4);
		
		function organizedthemes_fit_video_wrap($html, $url, $attr, $post_id) {
			return '<div class="fit-video">' . $html . '</div>';
		}		
	
	endif; // organizedthemes_fit_video_wrap


// Fallback for titles
	if ( ! function_exists( '_wp_render_title_tag' ) ) :
		function simple_render_title() { ?>
		
			<title><?php wp_title( '|', true, 'right' ); ?></title>
	
		<?php }
		
		add_action( 'wp_head', 'simple_render_title' );
	
	endif;
	
