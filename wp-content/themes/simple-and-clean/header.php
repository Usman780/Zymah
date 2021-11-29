<?php
/**
 * The file for displaying the header.
 *
 * This file also loads wp_head, the header
 * section and loads the content #wrap
 *
 * @package WordPress
 * @subpackage Simple
 * @since 1.0.0
 *
 *
 */?><!DOCTYPE html>
 <!--[if IE 8 ]> <html class="ie8"> <![endif]-->
 <!--[if IE 9 ]> <html class="ie9"> <![endif]-->
 <!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
 <head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	
	<?php if ( of_get_option('favicon', $single = true) != "" ) : ?>
	<link rel="shortcut icon" href="<?php echo of_get_option('favicon'); ?>" type="image/x-icon" />
	<?php endif; ?>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!--Load WP Head-->
	<?php wp_head(); ?>
	
</head>

<body <?php body_class(); ?>>
	
	<div id="header" class="clearfix">
		
		<div id="header-content" class="clearfix">
		
			<?php wp_nav_menu( array( 
				'theme_location'	=> 'primary',
				'container'			=> 'nav',
				'container_id'		=> 'main-menu',
				'menu_id'			=> 'top-menu',
				'menu_class'		=> 'clearfix',
				'depth'				=> 2,
				'fallback_cb'		=> false
				) ); ?>
		
		</div><!--#header-content -->
		
	</div><!-- #header -->
	
	<?php if ( '' != of_get_option( 'logo' ) ) : ?>
		
		<h1 class="image-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( of_get_option( 'logo' ) ); ?>"></a></h1>
	
	<?php else : ?>
			
		<h1 class="text-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo get_bloginfo('name'); ?></a></h1>
		
	<?php endif ; // if logo ?>	
	
	<div id="wrap" class="clearfix">