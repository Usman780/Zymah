<?php

/**
 * This file registers and enqueues our
 * various scripts in the theme
 *
 * @package WordPress
 * @subpackage Simple
 * @since 1.0.0
 *
 */

	// Register Scripts
		function organizedthemes_script_register() {
			if( !is_admin()){
				wp_register_script('tinynav', get_template_directory_uri() . '/js/tinynav.js', array('jquery'), NULL, true );
			}
		}
		add_action('init', 'organizedthemes_script_register');
	
	
	// Load scripts 
		function organizedthemes_load_default_scripts() {
			if( !is_admin() ) {
			   wp_enqueue_script('tinynav');
			}
			
			if ( is_singular() ) wp_enqueue_script( "comment-reply" );
			
		}
		add_action('wp_enqueue_scripts', 'organizedthemes_load_default_scripts');
	
	
	
	// add ie conditional html5 and responsive shims to header
		function organizedthemes_ie_shim () {
			
			    echo '<!--[if lt IE 9]>';
			    echo '<script src="'.get_template_directory_uri().'/js/html5.js"></script>';
			    echo '<script src="'.get_template_directory_uri().'/js/respond.js"></script>';
			    echo '<![endif]-->';
			
		}
		add_action('wp_head', 'organizedthemes_ie_shim');
	
	
	// Load Stylesheet
		function organizedthemes_load_stylesheets() {
		
			wp_enqueue_style( 'main', get_stylesheet_uri(), false, SIMPLE_VERSION, 'screen' );
			
		}
		
		add_action( 'wp_enqueue_scripts', 'organizedthemes_load_stylesheets' );
	
	
	// Simple Theme Meta Generator
		
		if ( ! function_exists( 'simple_meta_generator' ) ):
		
			function simple_meta_generator() {
				
			echo '<meta name="generator" content="Simple and Clean '.SIMPLE_VERSION.'" />
		
				';
				
			}
	
		endif;
		
		add_action( 'wp_head', 'simple_meta_generator', 1 );