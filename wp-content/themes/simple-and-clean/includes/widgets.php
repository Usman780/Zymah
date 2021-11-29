<?php

/**
 * This registers our widget areas
 * and sets a few widget related options
 *
 * @package WordPress
 * @subpackage Simple
 * @since 1.0.0
 *
 */

// Register Widgets
	add_action( 'init', 'organizedthemes_sidebars' );
	
	function organizedthemes_sidebars() {
		
		register_sidebar( array(
			'name' => __('Default Sidebar', 'simple'),
			'id' => 'sidebar',
			'description' => __('Place your sidebar widgets here.', 'simple'),
			'before_widget' => '
				<aside id="%1$s" class="widget %2$s clearfix">
			',
			'after_widget' => '
				</aside>
			',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3><div class="line"></div><div class="clear"></div>'
		) );
		
	}

// Adds shortcode support to text widgets
	add_filter('widget_text', 'do_shortcode');

// remove inline styling from recent comments widget
	function organizedthemes_remove_recent_comments_style() {
		global $wp_widget_factory;
		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
	}
	add_action( 'widgets_init', 'organizedthemes_remove_recent_comments_style' );

