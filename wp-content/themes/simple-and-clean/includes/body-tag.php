<?php

/**
 * Adds additional classes to the body tag
 *
 *
 * @package WordPress
 * @subpackage Simple
 * @since 1.0.0
 *
 *
 */

// Add browser to body tag 
	function organizedthemes_browser_body_class($classes) {
	
	    global $is_gecko, $is_IE, $is_opera, $is_safari, $is_chrome;
	
	    if($is_gecko)      $classes[] = 'gecko';
	    elseif($is_opera)  $classes[] = 'opera';
	    elseif($is_safari) $classes[] = 'safari';
	    elseif($is_chrome) $classes[] = 'chrome';
	    elseif($is_IE)     $classes[] = 'ie';
	    else               $classes[] = 'unknown';
	
	    return $classes;
	
	}
	add_filter('body_class','organizedthemes_browser_body_class');

// Add category name to single posts
	function organizedthemes_category_single($classes) {
	
		if ( is_single() ) {
			global $post;
			foreach((get_the_category($post->ID)) as $category) {
				$classes[] = $category->category_nicename;
			}
		}
		
		return $classes;
		
	}
	add_filter('body_class','organizedthemes_category_single');
